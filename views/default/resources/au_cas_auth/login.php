<?php

namespace AU\CASAuth;

/*
 * by the time this file is called,
 * the user is either returning from CAS
 * or else is a local login
 */

// need to grab the ticket first because it seems to get pulled out later down the line
$ticket = get_input('ticket');

$redirect_url = urldecode(get_input('redirectURL'));

// get plugin configuration
$plugin_settings = elgg_get_plugin_from_id('au_cas_auth');

// grab cas url
$cas_url = build_cas_url($redirect_url, true);

// if no ticket, redirect to cas
if (empty($ticket)) {
	// register error and redirect
	register_error(elgg_echo('au_cas_auth:failed:auth'));
	forward($redirect_url);
} else {
	// if ticket, has been to cas already    
	// set request var for phpCAS library to use
	$_REQUEST['ticket'] = $ticket;
}


// check cas result
$ldap_username = check_cas_result($plugin_settings);

// if no username returned, something went wrong, not sure what
if (!$ldap_username) {
	register_error("ERROR: Failed authenticating user.");

	forward($redirect_url);
}


// get user details out of LDAP.
$ldap_attributes = cas_ldapSearch($ldap_username);

// if returns null textUid, use cas username?!
if (!empty($ldap_attributes['textUid'])) {
	$username = $ldap_attributes['textUid'];
} else {
	$username = $ldap_username;
}


// elgg usernames cannot have periods
$clean_username = str_replace(".", "", $username);

// grab Elgg user, show hidden to find disabled users
$show_hidden = access_get_show_hidden_status();
access_show_hidden_entities(true);

$user = get_user_by_username($clean_username);
access_show_hidden_entities($show_hidden);

// if no user found in elgg, insert a new user
if (!$user) {

	// insert user
	// returns guid or false
	$user_guid = cas_insertUser($clean_username, $ldap_attributes, $plugin_settings);

	if (!$user_guid) {
		// error inserting user
		register_error(elgg_echo('cas:errors:cantinsert'));
	}


	// retrieve elgg user object
	$user = get_user_by_username($clean_username);

	if (!$user) {
		// error inserting user
		register_error(elgg_echo('cas:errors:cantinsert'));
	}

	// now log that user into Elgg
	$result = login($user, true);
} else {
	if ($user->isEnabled() && !$user->isBanned()) {
		// login the user
		$result = login($user, true);
	}
}

// start forwarding
if ($result) {
	$user->au_cas_login = 1;
	system_message(elgg_echo('loginok'));

	//
	//  If admins first time logging in
	if ((elgg_is_admin_logged_in()) && (!datalist_get('first_admin_login'))) {
		// first time an admin is logging in, let them know and send them to plugins admin
		system_message(elgg_echo('firstadminlogininstructions'));

		datalist_set('first_admin_login', time());

		forward('admin/plugins');
	}


	//
	// if redirect goes to home, check usersettings first
	if ($redirect_url == elgg_get_site_url() || empty($redirect_url)) {
		// if usersettings, forward to the destination
		if (elgg_get_plugin_user_setting('homepage', $user->guid, PLUGIN_ID) == 'home') {
			forward();
		}

		if (elgg_get_plugin_user_setting('homepage', $user->guid, PLUGIN_ID) == 'dashboard') {
			forward('dashboard');
		}

		// if nothing is set do default
		forward();
	}


	// if redirect goes elsewhere, send them elsewhere    
	if ($_SESSION['last_forward_from'] || $redirect_url) {

		$forward_url = $redirect_url;

		if (!$redirect_url) {
			// keeping $_SESSION for legacy reasons, use it if available
			$forward_url = $_SESSION['last_forward_from'];
		}

		$_SESSION['last_forward_from'] = "";

		forward($forward_url);
	}
} else {

	register_error(elgg_echo('loginerror'));
}
forward();
