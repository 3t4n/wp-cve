<?php
/**
 * 2FA Setup Intro, user first sees this if they log in and need to (or are reminded to) setup 2FA
 *
 * @version 1.0.0
 * @package Login With Ajax
 * @var WP_User $user
 */
use Login_With_AJAX\TwoFA;
$site_icon = get_site_icon_url();
?>
<div class="lwa-2FA-setup-intro">
	<?php
		$is_mandatory = TwoFA::is_setup_mandatory( $user );
		$required_ts = TwoFA::get_setup_required_time( $user );
	?>
	<?php if ( $site_icon ) : ?>
		<div class="site-icon"><img src="<?php echo esc_url($site_icon); ?>"></div>
	<?php endif; ?>
	<h3><?php esc_html_e('Secure Your Account', 'login-with-ajax'); ?></h3>
	<?php if ( $is_mandatory ) : ?>
		<p><?php esc_html_e('To continue logging in, you are required to set up at least one 2FA method for added security to your account.', 'login-with-ajax'); ?></p>
	<?php elseif( $required_ts !== false ) : ?>
		<p><?php echo sprintf( esc_html__('You have %s to enable 2FA verficiation methods, set one up now to avoid additional interruptions.', 'login-with-ajax'), human_time_diff( time(), $required_ts ) ) ; ?></p>
	<?php else : ?>
		<p><?php echo sprintf( esc_html__('You have %s to secure your account, at which point you will not be able to log in without administrator intervention.', 'login-with-ajax'), human_time_diff( time(), $required_ts ) ) ; ?></p>
	<?php endif; ?>
	<p class="lwa-2FA-footer-links">
		<button href="#" class="button button-primary lwa-2FA-setup-start"><?php esc_html_e('Continue', 'login-with-ajax-pro'); ?></button>
		<?php if( !$is_mandatory ): ?>
			<a class="button button-secondary lwa-2FA-setup-skip" href="<?php echo esc_attr(LoginWithAjax::getLoginRedirect($user)); ?>"><?php esc_html_e('Set Up Later', 'login-with-ajax-pro'); ?></a>
		<?php endif; ?>
	</p>
</div>
<?php