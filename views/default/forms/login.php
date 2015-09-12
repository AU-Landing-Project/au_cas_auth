<?php

namespace AU\CASAuth;

/**
 * Elgg login form
 *
 * @package Elgg
 * @subpackage Core
 */
elgg_require_js('au_cas_auth/login');
$plugin_settings = elgg_get_plugin_from_id(PLUGIN_ID);

// grab casurl
$cas_url = build_cas_url('', FALSE);
?>

<div>
	<h2><?php echo elgg_echo('cas:au_login'); ?></h2>
	<p class="loginbox"><?php echo elgg_echo('cas:au_login:description'); ?></p>
	<a href="<?php echo $cas_url; ?>"><?php echo elgg_echo('cas:login'); ?></a>
	<br><br>
	<?php
	if ($plugin_settings->enable_local_login == 'true') {
		?>
		<h2><?php echo elgg_echo('cas:guest_login'); ?></h2>
		<p class="loginbox"><?php echo elgg_echo('cas:guest_login:description'); ?></p>

		<a href="javascript:void(0);" class="cas-guest-login-toggle"><?php echo elgg_echo('cas:guest_login_toggle'); ?></a>
		<div class="cas-guest-login" style='display:none'>
			<label><?php echo elgg_echo('loginusername'); ?></label>
			<?php
			echo elgg_view('input/text', array(
				'name' => 'username',
				'class' => 'elgg-autofocus',
			));
			?>

			<div>
				<label><?php echo elgg_echo('password'); ?></label>
				<?php echo elgg_view('input/password', array('name' => 'password')); ?>
			</div>

			<?php echo elgg_view('login/extend'); ?>

			<div class="elgg-foot">
				<label class="mtm float-alt">
					<input type="checkbox" name="persistent" value="true" />
					<?php echo elgg_echo('user:persistent'); ?>
				</label>

				<?php echo elgg_view('input/submit', array('value' => elgg_echo('login'))); ?>

				<?php
				if (isset($vars['returntoreferer'])) {
					echo elgg_view('input/hidden', array('name' => 'returntoreferer', 'value' => 'true'));
				}
				?>

				<ul class="elgg-menu elgg-menu-general mtm">
					<?php
					if (elgg_get_config('allow_registration')) {
						echo '<li><a class="registration_link" href="' . elgg_get_site_url() . 'register">' . elgg_echo('register') . '</a></li>';
					}
					?>
					<li><a class="forgot_link" href="<?php echo elgg_get_site_url(); ?>forgotpassword">
							<?php echo elgg_echo('user:password:lost'); ?>
						</a></li>
				</ul>
			</div>

		<?php
		} // end if($enable_local_login)
		?>
	</div>
