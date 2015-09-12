<?php
/**
 *  au_cas_auth
 *  a modified cas authentication plugin for Elgg 1.8
 *  modified to fit the needs of the Landing Project
 *  https://landing.athabascau.ca
 */
// grab lib
require_once('lib/functions.php');

/**
 * 
 * @return unknown_type
 */
function au_cas_auth_init() {
//  register_pam_handler('au_cas_auth_authenticate');
  elgg_register_page_handler('au_cas_auth', 'au_cas_auth_page_handler');
  elgg_register_plugin_hook_handler('permissions_check', 'all', 'au_cas_permissions_check');
}


// pagehandler for au_cas_auth
// only thing directing here should be au_cas_auth/login
function au_cas_auth_page_handler($page){
  // this defines a page
  // that handles the return info from the cas server
  // can't be an action due to __elgg_token and __elgg_ts not being passed through properly
  if($page[0] == "login"){
  	include "pages/login.php";
  	return TRUE;
  }
  return FALSE;
}

/**
 * 
 * @param array $credentials
 * @return unknown_type
 */
function au_cas_auth_authenticate($credentials) {
    
    global $_PAM_HANDLERS, $_PAM_HANDLERS_MSG;

    // build cas url
    $casurl = au_cas_auth_build_cas_url('', TRUE, $credentials);

    //var_dump($casurl); exit;
    // Perform the authentication
    
    // user is already authenticated
    /*
    if($_PAM_HANDLERS_MSG['pam_auth_userpass'] == 'Authenticated!'){
      return FALSE;
    }
    elseif($_PAM_HANDLERS_MSG['pam_auth_userpass'] == 'Not Authenticated.'){
      // user tried to login to guest form but failed for some reason
      return FALSE;
    }
    else{
    */
        // do a redirect to the cas URL
       $config = elgg_get_plugin_from_id('au_cas_auth');
        
    $result = send_to_cas($config, $credentials);
        //forward($casurl);
        //var_dump($result); exit;
    //}

    return FALSE;

}

elgg_register_event_handler('init','system','au_cas_auth_init');