<?php
/**
 * 2FA Setup template, confirming setup of 2FA for an account user
 *
 * @version 1.0.0
 * @package Login With Ajax
 * @var WP_User $user
 */
$site_icon = get_site_icon_url();
?>
<div class="lwa-2FA-setup-success hidden">
	<?php if ( $site_icon ) : ?>
		<div class="site-icon"><img src="<?php echo esc_url($site_icon); ?>"></div>
	<?php endif; ?>
	<h3><?php esc_html_e('Security Setup Complete', 'login-with-ajax'); ?></h3>
	<p><?php esc_html_e('Your account is now more secure, you will be asked for further verification when you next log in.', 'login-with-ajax-pro'); ?></p>
	<p><?php esc_html_e('Thanks for your cooperation!', 'login-with-ajax-pro'); ?></p>
	<p class="lwa-2FA-footer-links">
		<a href="#" class="button button-primary lwa-2FA-setup-confirm"><?php esc_html_e('Continue', 'login-with-ajax-pro'); ?></a>
	</p>
</div>
<?php