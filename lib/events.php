<?php

namespace AU\CASAuth;

function upgrades() {
	if (!elgg_is_admin_logged_in()) {
		return;
	}
	
	require_once __DIR__ . '/upgrades.php';
	
	run_function_once(__NAMESPACE__ . '\\upgrade20150911');
}