<?php

// Check plugin is Installed or not
function htmegavc_is_plugins_installed( $pl_file_path = NULL ){
    $installed_plugins_list = get_plugins();
    return isset( $installed_plugins_list[$pl_file_path] );
}

function htmegavc_is_plugins_active( $pl_file_path = NULL ){
    $active_plugins_list = get_option('active_plugins');
    return in_array( $pl_file_path, $active_plugins_list );
}

add_action( 'admin_notices', 'htmegavc_render_plugin_install_active_notice' );
function htmegavc_render_plugin_install_active_notice(){
    $vc = 'js_composer/js_composer.php';

    if(! htmegavc_is_plugins_installed($vc)){
        // not installed notice
        if( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        $activation_url = 'https://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431';
        $message = sprintf( __( '<strong>HTMega Addons for WPBakery Page Builder</strong> requires %1$s"WPBakery Page Builder"%2$s plugin to be installed and activated. Please install WPBakery Page Builder to continue.', 'htmegavc' ), '<strong>', '</strong>' );
        $button_text = __( 'Get It Now', 'htmegavc' );


        $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
        printf( '<div class="error"><p>%1$s</p>%2$s</div>', __( $message ), $button );
    } else if(htmegavc_is_plugins_installed($vc) && !htmegavc_is_plugins_active($vc)){
        if( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }
        $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $vc . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $vc );
        $message = __( '<strong>HTMega Addons for WPBakery Page Builder</strong> requires WPBakery Page Builder plugin to be active. Please activate WPBakery Page Builder to continue.', 'htmegavc' );
        $button_text = __( 'Activate WPBakery Page Builder', 'htmegavc' );

        $button = '<p><a href="' . $activation_url . '" class="button-primary">' . $button_text . '</a></p>';
        printf( '<div class="error"><p>%1$s</p>%2$s</div>', __( $message ), $button );
    }
}