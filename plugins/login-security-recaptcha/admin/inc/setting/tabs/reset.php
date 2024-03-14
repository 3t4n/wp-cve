<?php
defined( 'ABSPATH' ) || die();
?>

<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="stlsr-reset-plugin-form">

	<?php $nonce = wp_create_nonce( 'reset-plugin' ); ?>
	<input type="hidden" name="reset-plugin" value="<?php echo esc_attr( $nonce ); ?>">

	<input type="hidden" name="action" value="stlsr-reset-plugin">

	<div class="st-alert-box st-alert-box-info notice notice-info">
		<p><?php esc_html_e( 'This will reset the plugin to its default state and clear all data created by this plugin.', 'login-security-recaptcha' ); ?></p>
	</div>

	<button type="submit" class="button button-primary" id="stlsr-reset-plugin-btn" data-message="<?php esc_attr_e( 'Are you sure to reset the plugin to its default state?', 'login-security-recaptcha' ); ?>"><?php esc_html_e( 'Reset Plugin', 'login-security-recaptcha' ); ?></button>

</form>
