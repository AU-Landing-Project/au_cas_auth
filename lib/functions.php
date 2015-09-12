<?php

namespace AU\CASAuth;

/**
 * Build cas url
 * 
 * @param string Elgg URL to redirect after logging in; should/will already be urlencoded?
 * @param boolean gateway?
 * @return cas url to redirect to or false
 */
function build_cas_url($redirect_url = '', $gateway = true, $credentials = NULL) {

	if (empty($redirect_url)) {
		$redirect_url = empty($_SESSION['last_forward_from']) ? current_page_url() : $_SESSION['last_forward_from'];
	}

	// grab plugin settings
	$plugin_settings = elgg_get_plugin_from_id('au_cas_auth');

	if ($plugin_settings == false) {
		return false;
	}

	// build cas url
	$url = 'https://' . $plugin_settings->cashostname . ':' . $plugin_settings->casport . $plugin_settings->casbaseuri . '/login?';

	// first add service to return to
	$url .= 'service=' . urlencode(elgg_get_site_url() . 'au_cas_auth/login?redirectURL=' . urlencode($redirect_url));


	// add credentials if they are provided
	if (isset($credentials['username']) && isset($credentials['password'])) {
		$url .= "&username={$credentials['username']}&password={$credentials['password']}";
	}


	// if cas acting as gateway
	if ($gateway) {
		$url .= '&gateway=true';
	}


	return $url;
}

/*
 * See what cas returned
 * 
 * @param array plugin settings
 * @param username or false
 */

function check_cas_result($config) {

	require_once dirname(__DIR__) . '/vendor/autoload.php';

	try {
		$cas_version = $config->cas_version ? $config->cas_version : CAS_VERSION_2_0;
		// phpCAS::setDebug();
		phpCAS::client($cas_version, $config->cashostname, (int) $config->casport, $config->casbaseuri, false);

		// don't automatically clear tickets from the url, we're taking care of that
		phpCAS::setNoClearTicketsFromUrl();


		// if a certificate is provided, use it, otherwise don't
		if ($config->cas_server_ca_cert_path != "") {
			// here we sould set the server certificate for production
			// '/etc/pki/tls/certs/DigiCertCA.crt'
			phpCAS::setCasServerCACert($config->cas_server_ca_cert_path);
		} else {
			// if you want to skip ssl verification
			if ($config->cas_server_no_validation) {
				phpCAS::setNoCasServerValidation();
			}
		}

		// check authentication; returns true/false
		if (phpCAS::checkAuthentication()) {
			// grab username
			$NetUsername = phpCAS::getUser();
			return $NetUsername;
		} else {
			return false;
		}
	} catch (Exception $e) {
		error_log("CAS ERROR: " . $e->getMessage());
		register_error($e->getMessage());
		return false;
	}
}

/*
 * Send user-provided credentials off to CAS
 * 
 * @param array plugin settings
 * @param username or false
 */

function send_to_cas($config) {

	require_once dirname(__DIR__) . '/vendor/autoload.php';

	try {
		// get module configuration
		//@todo - make sure this is a setting
		$cas_version = $config->cas_version ? $config->cas_version : CAS_VERSION_2_0;

		// phpCAS::setDebug();
		phpCAS::client($cas_version, $config->cashostname, (int) $config->casport, $config->casbaseuri, false);

		// check authentication; returns true/false
		$result = phpCAS::checkAuthentication();

		if ($result) {

			// grab username
			$NetUsername = phpCAS::getUser();
			return $NetUsername;
		} else {
			return false;
		}
	} catch (Exception $e) {
		error_log("CAS ERROR: " . $e->getMessage());
		register_error($e->getMessage());
		return false;
	}
}

/**
 * Lookup a user in Elgg
 *
 * @return user object from the user table if one exist
 */
function cas_getUserId($username, $ldap_attributes) {


	$uid = isset($ldap_attributes['textUid']) ? $ldap_attributes['textUid'] : str_replace(".", "", $username);

	$result = get_record_select('users', "username = ? AND active = ? AND user_type = ? ", array($uid, 'yes', 'person')
	);

	return $result;
}

/**
 * Insert user into elgg user table using info from ldap
 * Tries to insert, otherwise returns error
 *
 * @return user or error (false?)
 */
function cas_insertUser($username, $ldap_attributes, $config) {

	// name is 'cn' in ldap
	$name = $ldap_attributes['cn'];

	// remove periods from ldap username
	// ex. anthony.hopkins -> anthonyhopkins
	$uname = !empty($ldap_attributes['textUid']) ? $ldap_attributes['textUid'] : str_replace(".", "", $username);
	$email = $ldap_attributes['mail'];


	$user = new ElggUser();
	$user->username = $uname;
	$user->email = $email;
	$user->name = $name;
	$user->access_id = 2;
	$user->salt = generate_random_cleartext_password(); // Note salt generated before password!
	// cas users don't need password stored locally
	// so create an invalid password
	// a real password can be saved at a later time if they become a local user
	$user->password = md5(time()); //generate_user_password($user, $password);
	// returns guid or false
	$guid = $user->save();

	if (!$guid) {
		return false;
	}

	$obj = get_entity($guid);


	if (isset($config->casadminuser) && ($config->casadminuser == $uname)) {

		if ($obj instanceof \ElggUser) {

			//set context for permissions check
			elgg_push_context('au_cas_auth_make_admin');

			if (make_user_admin($guid)) {
				system_message(elgg_echo('admin:user:makeadmin:yes'));
			} else {
				register_error(elgg_echo('admin:user:makeadmin:no'));
			}

			// set context back
			elgg_pop_context();
		} else {
			register_error(elgg_echo('admin:user:makeadmin:no'));
		}
	}

	return $user;
}

/**
 * search ldap server
 *
 * Tries connect to specified ldap server.
 * Returns connection result or error.
 *
 * @return search result
 */
function cas_ldapSearch($uid) {

	$config = elgg_get_plugin_from_id(PLUGIN_ID);


	// Connect to the ldap server now
	$ldap_connection = cas_ldapConnect();

	if (!$ldap_connection) {
		register_error(elgg_echo('au_cas_auth:ldap:connect:fail'));
		return null;
	}

	//@todo - make sure this is a setting
	$ldap_context = $config->ldap_context;
	$ldap_search_pattern = "(uid=$uid)";

	// NB: requesting textuid but getting back textUid
	// request textUid? (IOW, is ldap case insensitive?)
	$ldap_fields_wanted = array('uid', 'cn', 'mail', 'textuid');


	$ldap_result = @ldap_search($ldap_connection, $ldap_context, $ldap_search_pattern, $ldap_fields_wanted);

	/* check and push results */
	$records = ldap_get_entries($ldap_connection, $ldap_result);
	// NOTE: ldap_get_entries returns array indices in lowercase.

	if (!$records) {
		register_error(elgg_echo('au_cas_auth:ldap:no_records'));
		return null;
	}

	$record["dn"] = $records[0]["dn"];
	$record["cn"] = $records[0]["cn"][0];
	$record["uid"] = $records[0]["uid"][0];
	$record["mail"] = $records[0]["mail"][0];
	if (isset($records[0]["textuid"][0])) {
		$record["textUid"] = $records[0]["textuid"][0];
	}

	ldap_close($ldap_connection);

	return $record;
}

/**
 * connects to ldap server
 *
 * Tries connect to specified ldap server.
 * Returns connection result or error.
 *
 * @return connection result
 */
function cas_ldapConnect($binddn = '', $bindpwd = '') {

	$config = elgg_get_plugin_from_id(PLUGIN_ID);


	//Select bind password, With empty values use
	//ldap_bind_* variables or anonymous bind if ldap_bind_* are empty
	if ($binddn == '' AND $bindpwd == '') {
		if (!empty($config->ldap_bind_dn)) {
			$binddn = $config->ldap_bind_dn;
		}
		if (!empty($config->ldap_bind_pw)) {
			$bindpwd = $config->ldap_bind_pw;
		}
	}

	if (!isset($config->ldaphostname)) {
		register_error(elgg_echo('au_cas_auth:ldap:no_info'));
		return false;
	}

	$server = trim($config->ldaphostname);

	if (!$server) {
		register_error(elgg_echo('au_cas_auth:ldap:no_info'));
		return false;
	}

	// no point continuing if no connection to ldap server
	$connresult = ldap_connect($server);
	if (!$connresult) {
		register_error(elgg_echo('au_cas_auth:ldap:no_info'));
		return false;
	}

	if (!empty($config->ldap_version)) {
		ldap_set_option($connresult, LDAP_OPT_PROTOCOL_VERSION, $config->ldap_version);
	}

	if (!empty($binddn)) {
		//bind with search-user
		//$debuginfo .= 'Using bind user'.$binddn.'and password:'.$bindpwd;
		$bindresult = ldap_bind($connresult, $binddn, $bindpwd);
	} else {
		//bind anonymously
		$bindresult = @ldap_bind($connresult);
	}

	if (!empty($config->ldap_opt_deref)) {
		ldap_set_option($connresult, LDAP_OPT_DEREF, $config->ldap_opt_deref);
	}

	if ($bindresult) {
		return $connresult;
	}

	// At this point, could not connect to ldap. Hence lets get some
	// debug information.
	$debuginfo .= "Server: '$server', Bind result: '" . ldap_error($connresult) . "'";
	//If any of servers are alive we have already returned connection
	register_error(elgg_echo('au_cas_auth:ldap:cannot_connect'));
	error_log('LDAP CONNECTION: ' . $debuginfo);

	return false;
}
