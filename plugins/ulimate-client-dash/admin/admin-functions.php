<?php


// Register field options
include( plugin_dir_path( __FILE__ ) . '/settings/main.php');
require( plugin_dir_path( __FILE__ ) . 'options/client-capabilities.php');
require( plugin_dir_path( __FILE__ ) . '/options/register-settings.php');
require( plugin_dir_path( __FILE__ ) . '/options/settings-activation.php');


// Add Ultimate Client Dash to admin menu
add_action('admin_menu', function() {
    add_menu_page( 'Ultimate Client', __( 'Ultimate Client', 'ultimate-client-dash' ), 'administrator', 'ultimate-client-dash', 'ucd_client_dash_page', 'dashicons-dashboard', 90  );
    add_submenu_page( 'ultimate-client-dash', 'ucd_dashboard_options_page', __( 'Dashboard', 'ultimate-client-dash' ), 'administrator', 'ultimate-client-dash&tab=dashboard-settings', 'ucd_client_dash_page' );
    add_submenu_page( 'ultimate-client-dash', 'ucd_dashboard_options_page', __( 'Login Page', 'ultimate-client-dash' ), 'administrator', 'ultimate-client-dash&tab=login-settings', 'ucd_client_dash_page' );
    add_submenu_page( 'ultimate-client-dash', 'ucd_dashboard_options_page', __( 'Welcome Message', 'ultimate-client-dash' ), 'administrator', 'ultimate-client-dash&tab=welcome-message', 'ucd_client_dash_page' );
    add_submenu_page( 'ultimate-client-dash', 'ucd_dashboard_options_page', __( 'Menu', 'ultimate-client-dash' ), 'administrator', 'ultimate-client-dash&tab=menu-items', 'ucd_client_dash_page' );
    add_submenu_page( 'ultimate-client-dash', 'ucd_dashboard_options_page', __( 'Widgets', 'ultimate-client-dash' ), 'administrator', 'ultimate-client-dash&tab=widget-settings', 'ucd_client_dash_page' );
    add_submenu_page( 'ultimate-client-dash', 'ucd_dashboard_options_page', __( 'Landing Page', 'ultimate-client-dash' ), 'administrator', 'ultimate-client-dash&tab=landing-page', 'ucd_client_dash_page' );
    add_submenu_page( 'ultimate-client-dash', 'ucd_dashboard_options_page', __( 'Client Access', 'ultimate-client-dash' ), 'administrator', 'ultimate-client-dash&tab=client-access', 'ucd_client_dash_page' );
    add_submenu_page( 'ultimate-client-dash', 'ucd_dashboard_options_page', __( 'Tracking/Code', 'ultimate-client-dash' ), 'administrator', 'ultimate-client-dash&tab=tracking-and-custom-code', 'ucd_client_dash_page' );
    add_submenu_page( 'ultimate-client-dash', 'ucd_dashboard_options_page', __( 'Misc', 'ultimate-client-dash' ), 'administrator', 'ultimate-client-dash&tab=misc', 'ucd_client_dash_page' );
    add_submenu_page( 'ultimate-client-dash', 'ucd_dashboard_options_page', __( 'Shortcodes', 'ultimate-client-dash' ), 'administrator', 'ultimate-client-dash&tab=shortcodes', 'ucd_client_dash_page' );
    remove_submenu_page('ultimate-client-dash','ultimate-client-dash');

    // Show Buy Pro Version if pro version is not installed
		if (!is_plugin_active('ultimate-client-dash-pro/ultimate-client-dash-pro.php') ) {
		  	add_submenu_page( 'ultimate-client-dash', 'ucd_dashboard_options_page', __( 'Buy Pro Version', 'ultimate-client-dash' ), 'administrator', 'ultimate-client-dash&tab=upgrade', 'ucd_client_dash_page');
		}
		// Show Activate License if pro version is installed
		if (is_plugin_active('ultimate-client-dash-pro/ultimate-client-dash-pro.php') ) {
		  	add_submenu_page( 'ultimate-client-dash', 'ucd_dashboard_options_page', __( 'Manage License', 'ultimate-client-dash' ), 'administrator', 'ultimate-client-dash&tab=activate', 'ucd_client_dash_page');
		}

});


// Display Landing Page Mode notice if enabled
add_action('admin_bar_menu', 'ucd_landing_page_notice');
function ucd_landing_page_notice($admin_bar) {
$ucd_enable_constructions = get_option('ucd_under_construction_disable');
    if (!empty($ucd_enable_constructions)) {
        $admin_bar->add_menu( array(
            'id'    => 'ucd-landing-page-notice',
            'parent' => 'top-secondary',
            'title' => 'Landing Page Active',
            'href'  => admin_url().'admin.php?page=ultimate-client-dash&tab=landing-page',
            'meta'  => array(
                'title' => __('Landing Page Active'),
            ),
        ) );
    }
}


// Include color picker assets
add_action( 'admin_enqueue_scripts', 'ucd_color_picker' );
function ucd_color_picker( $hook ) {
    if( is_admin() ) {
        // Add the color picker css file
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'ucd-custom-script-handle', plugins_url( '/settings/js/ucd-backend.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
    }
}