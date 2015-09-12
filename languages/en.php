<?php

    /**
	 * Elgg LDAP authentication
	 * 
	 * @package ElggLDAPAuth
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 */

    $en = array(
        'au_cas_auth' => 'AU Login Plugin',
        'au_cas_auth:usersettings:option:home' => 'back to the home page',
        'au_cas_auth:usersettings:option:dashboard' => 'to My Dashboard',
        'au_cas_auth:usersettings:option:notset' => 'Not Set',
        'au_cas_auth:usersettings:whichhomepage' => 'If you are not logged in, when you follow a link from another site or mail message
													to a non-public page, you should be presented with an option to log in after which
													the system will try to return you to where you wanted to go. However, if you log into
													the site from the home page, you may choose whether to see the main page of the site or your
													personal dashboard. When logging in directly, where would you like to go?<br>
        											After logging in from the home page send me:',
        'au_cas_auth:cas:password:reset' => 'To reset your password: AU students click <a href="https://secure.athabascau.ca/pwmanager/command.cgi">here</a> and AU staff click <a href="https://secure.athabascau.ca/cgi-bin/aume_chgpass.pl">here</a>.',
        
        'cas:guest_login_toggle' => 'Guests Log In Here',
        'cas:login' => 'AU Student/Staff Log In Here',
    
        'cas:settings:cas:label:certpath' => 'SSL Certificate path on CAS server',
        'cas:settings:cas:help:certpath' => 'eg. /etc/pki/tls/certs/mycertificate.crt - must leave blank if not needed',
    	'cas:settings:local:label' => 'Local Login',
        'cas:settings:local:enable_local_login' => 'Enable local login?',
        'cas:settings:local:help' => 'This will display an additional form on the login page for guest users.',
        'cas:settings:cas:label:host' => "CAS Settings",
        'cas:settings:cas:label:hostname'=>"CAS Hostname",
        'cas:settings:cas:help:hostname' => "The hostname for the CAS server.  For example, cas.domain.com",
        'cas:settings:cas:label:port'=>"CAS Port",
        'cas:settings:cas:help:port' => "The port CAS communicates on.  The default value is 443",
        'cas:settings:cas:label:baseuri' => "Base URI for CAS",
        'cas:settings:cas:adminuser' => "CAS User to set as admin",
        'cas:settings:cas:help:casadminuser' => "When this user logs in for the first time, they will be made admin.",
        'cas:settings:cas:help:baseuri' => "The directory CAS can be accessed in.  The default value is /cas",
        'cas:settings:cas:label:createuser' => "Create user in ELGG database",
        'cas:settings:cas:help:createuser' => "When logging in for the first time, a user account will be created in the Elgg database if this is enabled.",
        'cas:settings:cas:label:logout' => "Logout from CAS when logging out",
        'cas:settings:cas:help:logout' => "If enabled, Elgg will log the user out of CAS when logging out of the system.",
        'cas:settings:cas:settings' => "CAS-specific settings",
        'cas:settings:ldap:label:host' => "Host settings",
        'cas:settings:ldap:label:connection_search' => "LDAP settings",
        'cas:settings:ldap:label:hostname' => "LDAP Hostname",
        'cas:settings:ldap:help:hostname' => "Enter the canonical hostname, for example <i>ldap.yourcompany.com</i>",
        'cas:settings:ldap:label:port' => "The LDAP server port",
    	'cas:settings:ldap:help:port' => "The LDAP server port. Default is 389, which most hosts will use.",
        'cas:settings:ldap:label:version' => "LDAP protocol version",
        'cas:settings:ldap:help:version' => "LDAP protocol version. Defaults to 3, which most current LDAP hosts will use.",
        'cas:settings:ldap:label:ldap_bind_dn' => "LDAP bind DN",
        'cas:settings:ldap:help:ldap_bind_dn' => "Which DN to use for a non-anonymous bind, for exampe <i>cn=admin,dc=yourcompany,dc=com</i>",
        'cas:settings:ldap:label:ldap_bind_pwd' => "LDAP bind password",
        'cas:settings:ldap:help:ldap_bind_pwd' => "Which password to use when performing a non-anonymous bind.",
        'cas:settings:ldap:label:basedn' => "Base DN",
        'cas:settings:ldap:help:basedn' => "The base DN. Separate with a colon (:) to enter multiple DNs, for example <i>dc=yourcompany,dc=com : dc=othercompany,dc=com</i>",
        'cas:settings:ldap:label:filter_attr' => "Username filter attribute",
        'cas:settings:ldap:help:filter_attr' => "The filter to use for the username, common are <i>cn</i>, <i>uid</i> or <i>sAMAccountName</i>.",
        'cas:settings:ldap:label:search_attr' => "Search attributes",
        'cas:settings:ldap:help:search_attr' => "Enter search attibutes as key, value pairs with the key being the attribute description, and the value being the actual LDAP attribute.
         <i>firstname</i>, <i>lastname</i> and <i>mail</i> are used to create the Elgg user profile. The following example will work for ActiveDirectory:
         <p><br/>&nbsp;&nbsp; <i>firstname:givenname, lastname:sn, mail:mail</i></p><br/>",
        'cas:settings:ldap:label:user_create' => "Create users",
        'cas:settings:ldap:help:user_create' => "Optionally, an account can get created when a LDAP authentication was succesful.",
        'cas:errors:ldap:no_account' => "Your credentials are valid, but no account was found - please contact the system administrator",
        'cas:errors:ldap:no_register' => 'An account could not get created for you - please contact the system administrator.',
        'cas:errors:cantinsert' => 'The Landing was not able to create your user account. Please contact the system administrator.',
        'cas:guest_login' => 'Invited Guest Login',
        'cas:au_login' => 'AU Student/Staff Login',
        'cas:guest_login:description' => 'In order to register for a guest account on The Landing, please have your AU contact send a request to landing@athabascau.ca.',
        'cas:au_login:description' => 'Please use your standard AU login.',
        'cas:errors:wrongcredentials' => 'The username/password you supplied were not correct. Please try again.'
    );
    
    add_translation('en', $en);
?>
