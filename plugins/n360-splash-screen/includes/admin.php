<?php

function n360_admin_menu() {
	add_menu_page ( 
		'N360 | Splash Screen Settings',	// page title
		'Splash Screen',					// menu title
		'manage_options',
		'n360_settings_page', 				// page slug
		'n360_settings_page',				// callback function n360_settings_page()
		'dashicons-welcome-view-site',		// menu icon
		101
	);
}

add_action ( 'admin_menu', 'n360_admin_menu' );

function n360_settings_page() {
?>
	<div class="wrap">
		<h1><?php esc_html_e( get_admin_page_title() ); ?></h1>
		<?php $plugin_data = get_plugin_data( N360_SPLASH_PAGE_ROOT_PATH . 'n360-splash-screen.php' ); ?>
		<p><?php esc_html_e( 'Plugin Version: ' . $plugin_data['Version'] ); ?> by <b>notion360</b></p>
		<form method="post" action="options.php"><?php
			settings_fields( 'n360_version_cookie' );
			settings_fields( 'n360_config' );
			$options = get_option( 'n360_config' );
			n360_do_settings_sections( 'n360_settings_page' );
			submit_button();
			?>
		</form>
	</div>
<?php
}

function n360_settings_init() {
	register_setting( 'n360_config', 'n360_config' );
}

add_action ( 'admin_init', 'n360_settings_init' );

function n360_admin_scripts( $hook ) {
	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_register_style( 'admin_css', N360_SPLASH_PAGE_ROOT_URL . 'assets/css/admin.css' );
	wp_enqueue_style( 'admin_css' );
	wp_enqueue_script( 'n360-scripts', N360_SPLASH_PAGE_ROOT_URL . 'assets/js/admin-scripts.js', array('wp-color-picker') );
}

add_action ( 'admin_enqueue_scripts', 'n360_admin_scripts' );