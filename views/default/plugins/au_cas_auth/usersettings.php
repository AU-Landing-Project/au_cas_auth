<?php
/**
 * 
 */
?>

<p>
  <?php echo elgg_echo('au_cas_auth:usersettings:whichhomepage'); ?>
  
  <?php
  	$value = elgg_get_plugin_user_setting('homepage', elgg_get_logged_in_user_guid(), 'au_cas_auth');
    $options = array(
      'name' => "params[homepage]",
      'value' => $value,
      'options_values' => array(
        'home' => elgg_echo('au_cas_auth:usersettings:option:home'),
        'dashboard' => elgg_echo('au_cas_auth:usersettings:option:dashboard')
      ),
    );
  
    echo elgg_view('input/dropdown', $options);
  ?>
</p>