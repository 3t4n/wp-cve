<?php
/**
 *  Dashboard Changes.
 *
 * @package white-label
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Replace Default Welcome Panel with WL Panel.
 *
 * @return void
 */
function white_label_enable_welcome_panel()
{
    $wl_panel = white_label_get_option('admin_welcome_panel_content', 'white_label_dashboard', false);

    if (!empty($wl_panel)) {
        remove_action('welcome_panel', 'wp_welcome_panel');
        add_action('welcome_panel', 'white_label_welcome_panel_content');
    }
}

add_action('admin_init', 'white_label_enable_welcome_panel', 90);

/**
 * WL Welcome Panel Content.
 *
 * @return void
 */
function white_label_welcome_panel_content()
{
    $wl_panel = do_shortcode(stripslashes(white_label_get_option('admin_welcome_panel_content', 'white_label_dashboard', false)));

    if (!empty($wl_panel)) {
        echo $wl_panel; // phpcs:ignore
    }
}

/**
 * Find and store current Dashboard widgets
 *
 * @return void
 */
function white_label_store_dashboard_widgets()
{
    global $wp_meta_boxes;

    $widgets = [];

    $locations = ['normal', 'side'];
    foreach ($locations as $location) {
        if (isset($wp_meta_boxes['dashboard'][$location])) {
            foreach ($wp_meta_boxes['dashboard'][$location] as $l) {
                foreach ($l as $w) {
                    if (!isset($widgets['id']) && isset($w['id']) && !preg_match('/white_label/', $w['id'])) {
                        $widgets[$w['id']] = strip_tags($w['title']);
                    }
                }
            }
        }
    }

    update_option('white_label_cached_dashboard_widgets', $widgets);
}
add_action('wp_dashboard_setup', 'white_label_store_dashboard_widgets', 9998);

/**
 * Remove Default Dashboard widgets
 *
 * @return void
 */
function white_label_remove_dashboard_metaboxes()
{
    $default_widgets = [
        'dashboard_activity',
        'dashboard_right_now',
        'dashboard_incoming_links',
        'dashboard_secondary',
        'dashboard_php_nag',
        'dashboard_plugins',
        'dashboard_quick_press',
        'dashboard_recent_comments',
        'dashboard_recent_drafts',
        'dashboard_site_health',
        'dashboard_primary',
    ];

    $wl_remove_dashboard_widgets = white_label_get_option('admin_remove_dashboard_widgets', 'white_label_dashboard', []);

    // Remove Default Widgets
    if (isset($wl_remove_dashboard_widgets['admin_remove_default_widgets']) && $wl_remove_dashboard_widgets['admin_remove_default_widgets'] === 'on') {
        // Welcome Panel
        remove_action('welcome_panel', 'wp_welcome_panel');

        // Default Widgets
        foreach ($default_widgets as $dw) {
            remove_meta_box($dw, 'dashboard', 'normal');
            remove_meta_box($dw, 'dashboard', 'side');
        }
    }

    // Remove All Widgets
    if (isset($wl_remove_dashboard_widgets['admin_remove_third_party_widgets']) && $wl_remove_dashboard_widgets['admin_remove_third_party_widgets'] === 'on') {
        $dashboard_widgets = get_option('white_label_cached_dashboard_widgets', []);

        foreach ($dashboard_widgets as $dw_id => $dw_title) {
            if (!in_array($dw_id, $default_widgets)) {
                remove_meta_box($dw_id, 'dashboard', 'normal');
                remove_meta_box($dw_id, 'dashboard', 'side');
            }
        }
    }

}
add_action('wp_dashboard_setup', 'white_label_remove_dashboard_metaboxes', 9999);

/**
 * Add White Label widget to the dashboard.
 *
 * @return void
 */
function white_label_dashboard_widget_one()
{
    // Add Custom Dashboard widget.
    $enable_widget = white_label_get_option('admin_enable_widget', 'white_label_dashboard', false);

    if ($enable_widget !== 'on') {
        return;
    }

    $title = white_label_get_option('admin_widget_title', 'white_label_dashboard', '');

    wp_add_dashboard_widget(
        'white_label_dashboard_widget_one',
        $title,
        'white_label_dashboard_widget_one_content'
    );
}

add_action('wp_dashboard_setup', 'white_label_dashboard_widget_one');

/**
 * Widget Content.
 *
 * @return void
 */
function white_label_dashboard_widget_one_content()
{
    $content = do_shortcode(stripslashes(white_label_get_option('admin_widget_content', 'white_label_dashboard', false)));

    if (empty($content)) {
        return;
    }

    echo wpautop($content); // phpcs:ignore
}
