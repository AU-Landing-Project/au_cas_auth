<?php

namespace AU\CASAuth;

/**
 * overwrites permissions for creating an administrator
 * 
 * @param type $h
 * @param type $t
 * @param type $r
 * @param type $p
 * @return boolean|null
 */
function permissions_check($h, $t, $r, $p) {
	$context = elgg_get_context();
	if($context == "au_cas_auth_make_admin"){
		return true;
	}
	
	return $r;
}
