<?php
/**
 * 2FA Verification Template, which includes inner modal HTML and is shown when a user selects a verification method and verifies it.
 *
 * @version 1.0.0
 * @package Login With Ajax
 * @var WP_User $user
 */
include(LoginWithAjax::locate_template('2FA/setup-intro.php'));
?>
<div class="lwa-2FA-setup-form hidden">
	<h3><?php esc_html_e('Secure Your Account', 'login-with-ajax'); ?></h3>
	<?php include(LoginWithAjax::locate_template('2FA/setup-form.php')); ?>
</div>
<?php
include(LoginWithAjax::locate_template('2FA/setup-complete.php'));
