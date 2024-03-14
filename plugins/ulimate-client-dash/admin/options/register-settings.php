<?php


// Register Ulitmate Client Dashs settings
add_action( 'ucd_create_options', 'ucd_register_options', 1 );
function ucd_register_options() {


// Register Dashboard Settings Fields
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_modern_theme' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_accent' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_text_color' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_text_hover_color' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_submenu_text_color' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_admin_bar_text_color' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_link_text_color' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_background_light' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_background_dark' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_border_color' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_hide_wp_logo' );
register_setting( 'ultimate-client-dash-settings', 'ucd_howdy_text' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_admin_bar_disable_frontend' );
register_setting( 'ultimate-client-dash-settings', 'ucd_admin_footer_text' );
register_setting( 'ultimate-client-dash-settings', 'ucd_admin_footer_version' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_admin_bar_updates_link' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_admin_bar_comments_link' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_admin_bar_add_new_Menu' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_admin_bar_screen_options' );
register_setting( 'ultimate-client-dash-settings', 'ucd_dashboard_admin_bar_help' );


// Register Login Page Settings Fields
register_setting( 'ultimate-client-dash-login', 'ucd_login_logo' );
register_setting( 'ultimate-client-dash-login', 'ucd_logo_width' );
register_setting( 'ultimate-client-dash-login', 'ucd_logo_height' );
register_setting( 'ultimate-client-dash-login', 'ucd_logo_padding_bottom' );
register_setting( 'ultimate-client-dash-login', 'ucd_login_body_text_color' );
register_setting( 'ultimate-client-dash-login', 'ucd_login_background_color' );
register_setting( 'ultimate-client-dash-login', 'ucd_login_background_image' );
register_setting( 'ultimate-client-dash-login', 'ucd_login_overlay_color' );
register_setting( 'ultimate-client-dash-login', 'ucd_login_overlay_opacity' );
register_setting( 'ultimate-client-dash-login', 'ucd_login_custom_content' );
register_setting( 'ultimate-client-dash-login', 'ucd_login_hide_main_site_link' );
register_setting( 'ultimate-client-dash-login', 'ucd_login_hide_password_link' );
register_setting( 'ultimate-client-dash-login', 'ucd_login_logo_url' );
register_setting( 'ultimate-client-dash-login', 'ucd_login_logo_title' );


// Register Client Access Settings Fields
register_setting( 'ultimate-client-dash-client', 'ucd_client_access' );
register_setting( 'ultimate-client-dash-client', 'ucd_client_appearance' ); // Customize
register_setting( 'ultimate-client-dash-client', 'ucd_client_settings' ); // Manage Settings
register_setting( 'ultimate-client-dash-client', 'ucd_client_manage_users' ); // Manage Users
register_setting( 'ultimate-client-dash-client', 'ucd_client_manage_administrators' ); // Manage Administrators
register_setting( 'ultimate-client-dash-client', 'ucd_client_manage_plugins' ); // Manage Plugins
register_setting( 'ultimate-client-dash-client', 'ucd_client_manage_themes' ); // Manage Themes
register_setting( 'ultimate-client-dash-client', 'ucd_client_update_capability' ); // Update Capability
register_setting( 'ultimate-client-dash-client', 'ucd_client_edit_files' ); // Edit Files
register_setting( 'ultimate-client-dash-client', 'ucd_client_import' ); // Import
register_setting( 'ultimate-client-dash-client', 'ucd_client_export' ); // Export


// Register Widget Settings Fields
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_welcome' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_glutenberg' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_primary' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_secondary' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_activity' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_glance' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_draft' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_recent_drafts' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_incoming_links' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_recent_comments' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_gravity_forms' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_woocommerce' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_yoast' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_elementor' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_site_health' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_php_update' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_rows' );
register_setting( 'ultimate-client-dash-widget', 'ucd_custom_widget' );
register_setting( 'ultimate-client-dash-widget', 'ucd_custom_widget_shortcode' );
register_setting( 'ultimate-client-dash-widget', 'ucd_custom_widget_two_shortcode' );
register_setting( 'ultimate-client-dash-widget', 'ucd_custom_widget_three_shortcode' );
register_setting( 'ultimate-client-dash-widget', 'ucd_custom_widget_four_shortcode' );
register_setting( 'ultimate-client-dash-widget', 'ucd_custom_widget_two' );
register_setting( 'ultimate-client-dash-widget', 'ucd_custom_widget_three' );
register_setting( 'ultimate-client-dash-widget', 'ucd_custom_widget_four' );
register_setting( 'ultimate-client-dash-widget', 'ucd_custom_widget_count' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_title' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_body' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_two_title' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_two_body' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_three_title' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_three_body' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_four_title' );
register_setting( 'ultimate-client-dash-widget', 'ucd_widget_four_body' );


// Register Welcome Message Fields
register_setting( 'ultimate-client-dash-message', 'ucd_message_title' );
register_setting( 'ultimate-client-dash-message', 'ucd_message_body' );
register_setting( 'ultimate-client-dash-message', 'ucd_message_disable' );


// Register Tracking and Custom Code Fields
register_setting( 'ultimate-client-dash-tracking', 'ucd_tracking_google_analytics' );
register_setting( 'ultimate-client-dash-tracking', 'ucd_tracking_facebook_pixel' );
register_setting( 'ultimate-client-dash-tracking', 'ucd_tracking_custom_script' );
register_setting( 'ultimate-client-dash-tracking', 'ucd_tracking_custom_css' );
register_setting( 'ultimate-client-dash-tracking', 'ucd_tracking_custom_js' );


// Register Landing Page Fields
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_disable' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_login_logo' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_login_logo_width' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_title' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_title_padding_bottom' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_body' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_social_padding' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_facebook' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_instagram' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_twitter' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_linkedin' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_youtube' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_background_color' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_background_image' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_text_color' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_button_text_color' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_font_family' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_overlay_color' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_overlay_opacity' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_construction_logo_padding_bottom' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_button_text' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_button_link' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_button_color' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_button_radius' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_meta_title' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_meta_description' );
register_setting( 'ultimate-client-dash-under_construction', 'ucd_under_construction_social_title' );


// Register Misc Fields
register_setting( 'ultimate-client-dash-misc', 'ucd_misc_admin_bar_frontend_styling' );


// Deprecated settings. Keep here to allows users using old versions to delete in /wp-admin/options.php
register_setting( 'ultimate-client-dash-tracking', 'ucd_tracking_landing_custom_css' );
register_setting( 'ultimate-client-dash-tracking', 'ucd_tracking_admin_custom_css' );
register_setting( 'ultimate-client-dash-tracking', 'ucd_tracking_google_pixel' );

}
