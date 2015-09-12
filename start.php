<?php

namespace AU\CASAuth;

const PLUGIN_ID = 'au_cas_auth';
const PLUGIN_VERSION = 20150911;

/**
 *  au_cas_auth
 *  a modified cas authentication plugin for Elgg 1.8
 *  modified to fit the needs of the Landing Project
 *  https://landing.athabascau.ca
 */

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');


require_once __DIR__ . '/lib/hooks.php';
require_once __DIR__ . '/lib/events.php';
require_once __DIR__ . '/lib/functions.php';

/**
 * 
 * @return unknown_type
 */
function init() {
//  register_pam_handler('au_cas_auth_authenticate');
  elgg_register_page_handler('au_cas_auth', __NAMESPACE__ . '\\au_cas_auth_page_handler');
  elgg_register_plugin_hook_handler('permissions_check', 'all', __NAMESPACE__ . '\\permissions_check');
  elgg_register_event_handler('upgrade', 'system', __NAMESPACE__ . '\\upgrades');
}


// pagehandler for au_cas_auth
// only thing directing here should be au_cas_auth/login
function au_cas_auth_page_handler($page){
  // this defines a page
  // that handles the return info from the cas server
  // can't be an action due to __elgg_token and __elgg_ts not being passed through properly
  if($page[0] == "login"){
	echo elgg_view('resources/au_cas_auth/login');
  	return true;
  }
  return false;
}

/**
 * 
 * @param array $credentials
 * @return unknown_type
 */
function au_cas_auth_authenticate($credentials) {

    // do a redirect to the cas URL
    $config = elgg_get_plugin_from_id(PLUGIN_ID);
        
    send_to_cas($config, $credentials);
	
    return false;
}
