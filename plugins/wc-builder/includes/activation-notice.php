<?php

// Check plugin is Installed or not
function wpbforwpbakery_is_plugins_installed( $pl_file_path = NULL ){
    $installed_plugins_list = get_plugins();
    return isset( $installed_plugins_list[$pl_file_path] );
}

function wpbforwpbakery_is_plugins_active( $pl_file_path = NULL ){
    $active_plugins_list = get_option('active_plugins');
    return in_array( $pl_file_path, $active_plugins_list );
}

add_action( 'admin_notices', 'wpbforwpbakery_render_plugin_install_active_notice' );
function wpbforwpbakery_render_plugin_install_active_notice(){
    $vc = wpbforwpbakery_find_vc_installed();

    if( !$vc ){
        // not installed notice
        if( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        $activation_url = 'https://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431';
        $message = sprintf( __( '<strong>WC Builder</strong> requires %1$s"WPBakery Page Builder"%2$s plugin to be installed and activated. Please install WPBakery Page Builder to continue.', 'wpbforwpbakery' ), '<strong>', '</strong>' );
        $button_text = esc_html__( 'Get It Now', 'wpbforwpbakery' );


        $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
        printf( '<div class="error"><p>%1$s</p>%2$s</div>', __( $message ), $button );
    } else if(wpbforwpbakery_is_plugins_installed($vc) && !wpbforwpbakery_is_plugins_active($vc)){
        if( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $vc . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $vc );
        $message = __( '<strong>WC Builder</strong> requires WPBakery Page Builder plugin to be active. Please activate WPBakery Page Builder to continue.', 'wpbforwpbakery' );
        $button_text = esc_html__( 'Activate WPBakery Page Builder', 'wpbforwpbakery' );

        $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
        printf( '<div class="error"><p>%1$s</p>%2$s</div>', __( $message ), $button );
    }
}