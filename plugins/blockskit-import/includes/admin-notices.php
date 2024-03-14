<?php
/**
 * Enqueues style for admin notices.
 * 
 */
add_action( 'admin_enqueue_scripts', 'bk_import_admin_notice_styles' );
function bk_import_admin_notice_styles() {
    wp_enqueue_style( 'bk-import-admin-notice', BLOCKSKIT_TEMPLATE_URL . 'assets/admin-notice.css', false );

    wp_enqueue_script( 'bk-import-admin-notice-install', BLOCKSKIT_TEMPLATE_URL . 'assets/admin-notice.js', array( 'jquery' ), '1.0.0', true );
    wp_localize_script( 'bk-import-admin-notice-install', 'bk_import_install', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'btn_text' => esc_html__( 'Processing...', 'blockskit-import' ),
        'nonce'    => wp_create_nonce( 'bk_import_admin_notice_nonce' )
    ) );
}


/**
 * Adds store admin notice.
 * 
 */
function bk_import_admin_notice(){
    if( !get_user_meta( get_current_user_id(), 'admin_notice_dismissed' ) ){
        echo '<div class="bk-notice">';
            echo '<div class="getting-content">';
                if( is_plugin_active( 'blockskit/blockskit.php' ) ){
                    echo '<p class="text">Blockskit added successfully. You may dismiss this notice now.</p>';
                }else{
                    echo '<h2>New Awesome Plugin - Blockskit</h2>';
                    echo '<p class="text"><a href="https://wordpress.org/plugins/blockskit/" target="_blank">Blockskit</a> - new awesome free plugin with blocks and for demo import.</p>';
                    echo '<p class="text">Note: We are moving from Blockskit Import plugin to Blockskit plugin. Install Blockskit plugin to access new demos and exciting new features. Afterwards you can deactivate Blockskit Import plugin.</p>';
                    echo '<a href="https://wordpress.org/plugins/blockskit/" class="button button-primary bk-import-install-blockskit">Install Blockskit</a>';
                }
            echo '</div>';
            echo '<a href="' . esc_url( wp_nonce_url( add_query_arg( 'bk-import-admin-notice-dismissed', 'admin_notice_dismissed' ) ) ) . '" class="admin-notice-dismiss">Dismiss<button type="button" class="notice-dismiss"></button></a>';
        echo '</div>';
    }
}
add_action( 'admin_notices', 'bk_import_admin_notice' );

/**
 * Registers admin notice for current user.
 * 
 */
add_action( 'admin_init', 'bk_import_notice_dismissed' );
function bk_import_notice_dismissed() {
    if ( isset( $_GET['bk-import-admin-notice-dismissed'] ) ){
        add_user_meta( get_current_user_id(), 'admin_notice_dismissed', true, true );
    }
}

/**
 * Removes admin notice dismiss state for current user.
 * 
 */
add_action( 'switch_theme', 'bk_import_flush_admin_notices_dismiss_status' );
function bk_import_flush_admin_notices_dismiss_status(){
    delete_user_meta( get_current_user_id(), 'admin_notice_dismissed', true, true );
}

/**
 * Install Blockskit plugin.
 * 
 */
function bk_import_install_blockskit() {

    check_ajax_referer( 'bk_import_admin_notice_nonce', 'security' );

    $slug   = 'blockskit';
    $plugin = 'blockskit/blockskit.php';
    $status = array(
        'install' => 'plugin',
        'slug'    => sanitize_key( wp_unslash( $slug ) ),
    );
    $status['redirect'] = admin_url();

    if ( is_plugin_active_for_network( $plugin ) || is_plugin_active( $plugin ) ) {
        // Plugin is activated
        wp_send_json_success( $status );
    }

    if ( ! current_user_can( 'install_plugins' ) ) {
        $status['errorMessage'] = __( 'Sorry, you are not allowed to install plugins on this site.', 'blockskit-import' );
        wp_send_json_error( $status );
    }

    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

    // Looks like a plugin is installed, but not active.
    if ( file_exists( WP_PLUGIN_DIR . '/' . $slug ) ) {
        $plugin_data          = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
        $status['plugin']     = $plugin;
        $status['pluginName'] = $plugin_data['Name'];

        if ( current_user_can( 'activate_plugin', $plugin ) && is_plugin_inactive( $plugin ) ) {
            $result = activate_plugin( $plugin );

            if ( is_wp_error( $result ) ) {
                $status['errorCode']    = $result->get_error_code();
                $status['errorMessage'] = $result->get_error_message();
                wp_send_json_error( $status );
            }

            wp_send_json_success( $status );
        }
    }

    $api = plugins_api(
        'plugin_information',
        array(
            'slug'   => sanitize_key( wp_unslash( $slug ) ),
            'fields' => array(
                'sections' => false,
            ),
        )
    );

    if ( is_wp_error( $api ) ) {
        $status['errorMessage'] = $api->get_error_message();
        wp_send_json_error( $status );
    }

    $status['pluginName'] = $api->name;

    $skin     = new WP_Ajax_Upgrader_Skin();
    $upgrader = new Plugin_Upgrader( $skin );
    $result   = $upgrader->install( $api->download_link );

    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        $status['debug'] = $skin->get_upgrade_messages();
    }

    if ( is_wp_error( $result ) ) {
        $status['errorCode']    = $result->get_error_code();
        $status['errorMessage'] = $result->get_error_message();
        wp_send_json_error( $status );
    } elseif ( is_wp_error( $skin->result ) ) {
        $status['errorCode']    = $skin->result->get_error_code();
        $status['errorMessage'] = $skin->result->get_error_message();
        wp_send_json_error( $status );
    } elseif ( $skin->get_errors()->get_error_code() ) {
        $status['errorMessage'] = $skin->get_error_messages();
        wp_send_json_error( $status );
    } elseif ( is_null( $result ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        WP_Filesystem();
        global $wp_filesystem;

        $status['errorCode']    = 'unable_to_connect_to_filesystem';
        $status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'blockskit-import' );

        // Pass through the error from WP_Filesystem if one was raised.
        if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
            $status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
        }

        wp_send_json_error( $status );
    }

    $install_status = install_plugin_install_status( $api );

    if ( current_user_can( 'activate_plugin', $install_status['file'] ) && is_plugin_inactive( $install_status['file'] ) ) {
        $result = activate_plugin( $install_status['file'] );

        if ( is_wp_error( $result ) ) {
            $status['errorCode']    = $result->get_error_code();
            $status['errorMessage'] = $result->get_error_message();
            wp_send_json_error( $status );
        }
    }

    wp_send_json_success( $status );

}
add_action( 'wp_ajax_bk_import_install_blockskit', 'bk_import_install_blockskit' );