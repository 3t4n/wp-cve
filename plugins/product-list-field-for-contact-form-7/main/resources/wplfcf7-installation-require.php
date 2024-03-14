<?php

// Error returns when contact form 7 and woocommerce is not installed
add_action('admin_init', 'WPLFCF7_check_plugin_state');
function WPLFCF7_check_plugin_state(){
    if ( ! ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) ) {
        set_transient( get_current_user_id() . 'wplfcf7error', 'message' );
    }

    if ( ! ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
        set_transient( get_current_user_id() . 'wplfcf7wwwerror', 'message' );
    }
}

// Use for Show backend notice
add_action( 'admin_notices', 'WPLFCF7_show_notice');
function WPLFCF7_show_notice() {
    if ( get_transient( get_current_user_id() . 'wplfcf7error' ) ) {
        deactivate_plugins( plugin_basename( WPLFCF7_PLUGIN_FILE ) );

        delete_transient( get_current_user_id() . 'wplfcf7error' );

        echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=contact+form+7">Contact Form 7</a> plugin installed and activated.</p></div>';
    }

    if ( get_transient( get_current_user_id() . 'wplfcf7wwwerror' ) ) {
        deactivate_plugins( plugin_basename( WPLFCF7_PLUGIN_FILE ) );

        delete_transient( get_current_user_id() . 'wplfcf7wwwerror' );

        echo '<div class="error"><p> This plugin is deactivated because it require <a href="plugin-install.php?tab=search&s=woocommerce">Woocommerce</a> plugin installed and activated.</p></div>';
    }
}