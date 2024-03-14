<?php
/**
 * 2FA setup form, including the available methods to the user
 *
 * @version 1.0.0
 * @package Login With Ajax
 * @var WP_User $user
 */

use Login_With_AJAX\TwoFA\Account;
?>
<form class="lwa-2FA-setup" data-verify-url="<?php echo admin_url('admin-ajax.php'); ?>">
	<?php Account::setup_form_methods( $user ); ?>
	<input type="hidden" name="login-with-ajax" value="2FA_setup_save">
	<input type="hidden" name="log" value="<?php echo esc_attr($user->user_login); ?>">
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('2FA-setup-save-' . $user->ID); ?>">
	<div class="lwa-2FA-formdata hidden"></div>
	<p class="lwa-2FA-footer-links">
		<button type="submit" class="button-primary"><?php esc_html_e('Save and Continue', 'login-with-ajax-pro'); ?></button>
	</p>
</form>
<?php