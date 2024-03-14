<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Import / Exports settings page
///////////////////////////////////////////////////////////////////////////////////////////////////

//Export WP Admin UI Settings in JSON
function wpui_export_settings() {
    if( empty( $_POST['wpui_action'] ) || 'export_settings' != $_POST['wpui_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['wpui_export_nonce'], 'wpui_export_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;
    
    $settings["wpui_option_name"]                   = get_option( 'wpui_option_name' );
    $settings["wpui_activated"]                     = get_option( 'wpui_activated' );
    $settings["wpuipro_activated"]                  = get_option( 'wpuipro_activated' );
    $settings["wpui_admin_menu_slug"]               = get_option( 'wpui_admin_menu_slug' );
    $settings["wpui_dashboard_list_all_widgets"]    = get_option( 'wpui_dashboard_list_all_widgets' );
    $settings["wpui_themes_option_name"]            = get_option( 'wpui_themes_option_name' );
    $settings["wpui_login_option_name"]             = get_option( 'wpui_login_option_name' );
    $settings["wpui_global_option_name"]            = get_option( 'wpui_global_option_name' );
    $settings["wpui_dashboard_option_name"]         = get_option( 'wpui_dashboard_option_name' );
    $settings["wpui_admin_menu_option_name"]        = get_option( 'wpui_admin_menu_option_name' );
    $settings["wpui_admin_menu_list_option_name"]   = get_option( 'wpui_admin_menu_list_option_name' );
    $settings["wpui_admin_bar_option_name"]         = get_option( 'wpui_admin_bar_option_name' );
    $settings["wpui_editor_option_name"]            = get_option( 'wpui_editor_option_name' );
    $settings["wpui_metaboxes_option_name"]         = get_option( 'wpui_metaboxes_option_name' );
    $settings["wpui_columns_option_name"]           = get_option( 'wpui_columns_option_name' );
    $settings["wpui_library_option_name"]           = get_option( 'wpui_library_option_name' );
    $settings["wpui_profil_option_name"]            = get_option( 'wpui_profil_option_name' );
    $settings["wpui_plugins_option_name"]           = get_option( 'wpui_plugins_option_name' );
    $settings["wpui_roles_option_name"]             = get_option( 'wpui_roles_option_name' );
    $settings["wpui_mails_option_name"]             = get_option( 'wpui_mails_option_name' );

    ignore_user_abort( true );
    nocache_headers();
    header( 'Content-Type: application/json; charset=utf-8' );
    header( 'Content-Disposition: attachment; filename=wpui-settings-export-' . date( 'm-d-Y' ) . '.json' );
    header( "Expires: 0" );
    echo json_encode( $settings );
    exit;
}
add_action( 'admin_init', 'wpui_export_settings' );

//Import WP Admin UI Settings from JSON
function wpui_import_settings() {
    if( empty( $_POST['wpui_action'] ) || 'import_settings' != $_POST['wpui_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['wpui_import_nonce'], 'wpui_import_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;
    $extension = end( explode( '.', $_FILES['import_file']['name'] ) );
    if( $extension != 'json' ) {
        wp_die( __( 'Please upload a valid .json file' ) );
    }
    $import_file = $_FILES['import_file']['tmp_name'];
    if( empty( $import_file ) ) {
        wp_die( __( 'Please upload a file to import' ) );
    }

    $settings = (array) json_decode( file_get_contents( $import_file ), true );

    update_option( 'wpui_option_name', $settings["wpui_option_name"] ); 
    update_option( 'wpui_activated', $settings["wpui_activated"] ); 
    update_option( 'wpuipro_activated', $settings["wpuipro_activated"] ); 
    update_option( 'wpui_admin_menu_slug', $settings["wpui_admin_menu_slug"] ); 
    update_option( 'wpui_dashboard_list_all_widgets', $settings["wpui_dashboard_list_all_widgets"] ); 
    update_option( 'wpui_themes_option_name', $settings["wpui_themes_option_name"] ); 
    update_option( 'wpui_login_option_name', $settings["wpui_login_option_name"] ); 
    update_option( 'wpui_global_option_name', $settings["wpui_global_option_name"] ); 
    update_option( 'wpui_dashboard_option_name', $settings["wpui_dashboard_option_name"] ); 
    update_option( 'wpui_admin_menu_option_name', $settings["wpui_admin_menu_option_name"] ); 
    update_option( 'wpui_admin_menu_list_option_name', $settings["wpui_admin_menu_list_option_name"] ); 
    update_option( 'wpui_admin_bar_option_name', $settings["wpui_admin_bar_option_name"] ); 
    update_option( 'wpui_editor_option_name', $settings["wpui_editor_option_name"] ); 
    update_option( 'wpui_metaboxes_option_name', $settings["wpui_metaboxes_option_name"] ); 
    update_option( 'wpui_columns_option_name', $settings["wpui_columns_option_name"] );
    update_option( 'wpui_library_option_name', $settings["wpui_library_option_name"] );
    update_option( 'wpui_profil_option_name', $settings["wpui_profil_option_name"] );
    update_option( 'wpui_plugins_option_name', $settings["wpui_plugins_option_name"] );
    update_option( 'wpui_roles_option_name', $settings["wpui_roles_option_name"] );
    update_option( 'wpui_mails_option_name', $settings["wpui_mails_option_name"] );
     
    wp_safe_redirect( admin_url( 'admin.php?page=wpui-import-export' ) ); exit;
}
add_action( 'admin_init', 'wpui_import_settings' );

//Reset WP Admin UI Settings
function wpui_reset_settings() {
    if( empty( $_POST['wpui_action'] ) || 'reset_settings' != $_POST['wpui_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['wpui_reset_nonce'], 'wpui_reset_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    global $wpdb;
    
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'wpui_%' ");
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'wpuipro_activated' ");
     
    wp_safe_redirect( admin_url( 'admin.php?page=wpui-import-export' ) ); exit;
}
add_action( 'admin_init', 'wpui_reset_settings' );