<?php

namespace AU\CASAuth;

function upgrade20150911() {
	$version = (int) elgg_get_plugin_setting('version', PLUGIN_ID);
	if ($version >= 20150911) {
		return;
	}
	elgg_set_plugin_setting('version', 20150911, PLUGIN_ID);
}