<?php
if( !defined('ABSPATH') ) exit;

// Check Plugins is Installed or Not
if( !function_exists( 'skybootpostfoliogallery_is_plugins_active' ) ){
    function skybootpostfoliogallery_is_plugins_active( $pl_file_path = NULL ){
        $installed_plugins_list = get_plugins();
        return isset( $installed_plugins_list[$pl_file_path] );
    }
}

// Load Plugins
function skybootpostfoliogallery_load_plugin() {
    load_plugin_textdomain( 'skyboot-pg' );
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', 'skybootpostfoliogallery_check_elementor_status' );
        return;
    }
}
add_action( 'plugins_loaded', 'skybootpostfoliogallery_load_plugin' );	

// Check Elementor install or not.
function skybootpostfoliogallery_check_elementor_status(){
    $elementor = 'elementor/elementor.php';
    if( skybootpostfoliogallery_is_plugins_active( $elementor ) ) {
        if( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $elementor . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $elementor );

        $message = '<p>' . __( 'Skyboot Portfolio Gallery addons are not working because you need to activate the Elementor plugin.', 'skyboot-pg' ) . '</p>';
        $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'skyboot-pg' ) ) . '</p>';
    } else {
        if ( ! current_user_can( 'install_plugins' ) ) {
            return;
        }
        $install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
        $message = '<p>' . __( 'Skyboot Portfolio Gallery addons are not working because you need to install the Elementor plugin', 'skyboot-pg' ) . '</p>';
        $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, __( 'Install Elementor Now', 'skyboot-pg' ) ) . '</p>';
    }
    echo '<div class="error"><p>' . $message . '</p></div>';
}