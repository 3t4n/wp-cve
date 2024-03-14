<?php
wp_enqueue_style( 'mdwc-admin-style' );
wp_enqueue_script( 'mdwc-admin' );

$mail_debug_url = admin_url( 'edit.php?post_type=mail-debug' );
$settings_url   = mdwc_settings()->get_settings_menu_url();

$is_post_type = ! ( isset( $_GET['page'] ) && 'mdwc_settings_panel' === $_GET['page'] );
?>
<div class="mdwc-settings-box">
	<h1 class="mdwc-settings-title">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="fill: currentColor">
			<path d="M0 0h24v24H0V0z" fill="none"/>
			<path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-.4 4.25l-6.54 4.09c-.65.41-1.47.41-2.12 0L4.4 8.25c-.25-.16-.4-.43-.4-.72 0-.67.73-1.07 1.3-.72L12 11l6.7-4.19c.57-.35 1.3.05 1.3.72 0 .29-.15.56-.4.72z"/>
		</svg>
		Mail Debug for WooCommerce
	</h1>
	<nav class="mdwc-settings-tab-wrapper">
		<a class="mdwc-settings-tab <?php echo $is_post_type ? 'mdwc-settings-tab--active' : '' ?>" href="<?php echo $mail_debug_url; ?>">Mail Debug</a>
		<a class="mdwc-settings-tab <?php echo ! $is_post_type ? 'mdwc-settings-tab--active' : '' ?>" href="<?php echo $settings_url; ?>"><?php _e( 'Settings', 'mail-debug-for-woocommerce' ) ?></a>
	</nav>

	<div class="mdwc-settings-box-wrapper">
