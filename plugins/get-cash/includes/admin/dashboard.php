<?php if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'admin_enqueue_scripts', 'get_cash_admin_css' );
function get_cash_admin_css() {
	if ( is_admin() ) {
		$currentScreen = get_current_screen();
		if ( strpos($currentScreen->id, 'get_cash') !== false || strpos($currentScreen->id, 'get-cash') !== false ) {
		// if ($currentScreen->id == 'toplevel_page_get-cash' || $currentScreen->id == 'get-cash_page_get_cash_recommended_menu_page' || $currentScreen->id == 'get-cash_page_get_cash_help_menu_page' ) {
			wp_register_style( 'bootstrap', GET_CASH_PLUGIN_DIR_URL . 'includes/css/bootstrap.min.css');
			wp_enqueue_style( 'bootstrap' );
		} else { return; }
	}
}

add_action( 'admin_menu', 'get_cash_menu' );
function get_cash_menu() {
	$parent_slug = 'get-cash';
	$capability = 'manage_options';

	$new = " <sup style='color:#0c0;'>NEW</sup>";
	$improved = " <sup style='color:#0c0;'>IMPROVED</sup>";
	$comingSoon = " <sup style='color:#00c;'>COMING SOON</sup>";

	add_menu_page( null, 'Get Cash', $capability, $parent_slug, 'get_cash_settings_page', 'dashicons-money-alt', 20 );
	add_submenu_page( $parent_slug, 'Transactions', 'Transactions' . $new, $capability, admin_url('edit.php?post_type=gc-transactions'), null, null );
	add_submenu_page( $parent_slug, 'Review Get Cash', 'Review', $capability, 'https://wordpress.org/support/plugin/get-cash/reviews/?filter=5', null, null );
	add_submenu_page( $parent_slug, 'Our Plugins', '<span style="color:yellow">Free Recommended Plugins</span>', $capability, admin_url("plugin-install.php?s=theafricanboss&tab=search&type=author"), null, null );
	add_submenu_page( $parent_slug, 'Recommended', 'Premium recommended plugins', $capability, 'get_cash_recommended_menu_page', 'get_cash_recommended_menu_page', null );
	add_submenu_page( $parent_slug, 'FAQ', 'FAQ', $capability, 'get_cash_help_menu_page', 'get_cash_help_menu_page', null );
}

function get_cash_settings_page() {
	require_once GET_CASH_PLUGIN_DIR . 'includes/admin/settings.php';
}

function get_cash_recommended_menu_page() {
	require_once GET_CASH_PLUGIN_DIR . 'includes/admin/recommended.php';
}

function get_cash_help_menu_page() {
	require_once GET_CASH_PLUGIN_DIR . 'includes/admin/help.php';
}

function get_cash_tutorials_menu_page() {
	require_once GET_CASH_PLUGIN_DIR . 'includes/admin/tutorials.php';
}

?>