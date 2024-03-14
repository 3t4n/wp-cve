<?php


// Set default option values during plugin activation
function ucd_settings_activation_defaults() {
    $ucd_widget_count = get_option('ucd_custom_widget_count');
    if (empty($ucd_widget_count)) {
        update_option( 'ucd_custom_widget_count', 'one' );
    }
    $ucd_custom_widget = get_option('ucd_custom_widget');
    if (empty($ucd_custom_widget)) {
        update_option( 'ucd_custom_widget', '' );
    }
    $ucd_under_construction_body = get_option('ucd_under_construction_body');
    if (empty($ucd_under_construction_body)) {
        update_option( 'ucd_under_construction_body', '<p style="text-align: center;">Thank you for being patient. We are doing some work on the site and it will be live shortly.</p>' );
    }
    $ucd_under_construction_page_title = get_option('ucd_under_construction_title');
    if (empty($ucd_under_construction_page_title)) {
        update_option( 'ucd_under_construction_title', 'Website coming soon' );
    }
    $ucd_login_logo_url = get_option('ucd_login_logo_url');
    if (empty($ucd_login_logo_url)) {
        update_option( 'ucd_login_logo_url', '' );
    }
    $ucd_login_logo_title = get_option('ucd_login_logo_title');
    if (empty($ucd_login_logo_title)) {
        update_option( 'ucd_login_logo_title', '' );
    }
    $ucd_white_label_wordpress = get_option( 'ucd_dashboard_hide_wp_logo' );
    if (empty($ucd_white_label_wordpress)) {
        update_option( 'ucd_login_logo_title', '' );
    }
}
