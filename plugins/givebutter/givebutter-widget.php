<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
Plugin Name: Givebutter Widgets
Description: Plugin for embedding Givebutter widgets via shortcodes
Version: 1.0.2
Author: Givebutter
Author URI: https://givebutter.com
*/

// Register the shortcode
function givebutter_widget_shortcode($atts)
{
    $atts = shortcode_atts([
        'id' => null,
        'position' => null,
        'align' => null,
    ], $atts, 'givebutter-widget');

    if (empty($atts['id'])) {
        return '';
    }

    $widgetHtml = '<givebutter-widget id="' . esc_attr($atts['id']) . '"';

    if (!empty($atts['position'])) {
        $widgetHtml .= ' position="' . esc_attr($atts['position']) . '"';
    }

    if (!empty($atts['align'])) {
        $widgetHtml .= ' align="' . esc_attr($atts['align']) . '"';
    }

    $widgetHtml .= '></givebutter-widget>';

    return $widgetHtml;
}
add_shortcode('givebutter-widget', 'givebutter_widget_shortcode');

// Enqueue the Givebutter widget library
function givebutter_widget_enqueue_script() {
    $account = sanitize_text_field(get_option('givebutter-widget-account'));

    if (!empty($account)) {
        $url = "https://widgets.givebutter.com/latest.umd.cjs?acct={$account}&p=wordpress";

        wp_enqueue_script('givebutter-widget-library', $url, [], null, ['strategy' => 'async', 'in_footer' => true]);
    }
}
add_action('wp_head', 'givebutter_widget_enqueue_script');
add_action('admin_head', 'givebutter_widget_enqueue_script');

// Register a custom menu item under the "Settings" menu
function givebutter_widget_settings_menu() {
    add_options_page(
        'Givebutter Widgets Settings',
        'Givebutter Widgets',
        'manage_options',
        'givebutter-widget-settings',
        'givebutter_widget_settings_page'
    );
}
add_action('admin_menu', 'givebutter_widget_settings_menu');

// Create the settings page content
function givebutter_widget_settings_page() {
    ?>
    <div class="wrap">
        <h2>Givebutter Widgets Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('givebutter-widget-settings-group'); ?>
            <?php do_settings_sections('givebutter-widget-settings'); ?>
            <input type="submit" class="button-primary" value="Save Changes">
        </form>
    </div>
    <?php
}

// Initialize and configure settings
function givebutter_widget_settings_init() {
    register_setting(
        'givebutter-widget-settings-group',
        'givebutter-widget-account'
    );

    add_settings_section(
        'givebutter-widget-settings-section',
        'General settings',
        '',
        'givebutter-widget-settings'
    );

    add_settings_field(
        'givebutter-widget-account',
        'Account Id',
        'givebutter_widget_account_field_callback',
        'givebutter-widget-settings',
        'givebutter-widget-settings-section'
    );
}
add_action('admin_init', 'givebutter_widget_settings_init');

// Field callback for the "account" property
function givebutter_widget_account_field_callback() {
    $value = get_option('givebutter-widget-account'); ?>
    <div>
        <input type="text" id="givebutter-widget-account" name="givebutter-widget-account" value="<?php echo esc_attr($value); ?>">
        <p>You can find your Account ID in your Givebutter Dashboard > Campaign > Sharing > Widgets > Installation.</p>
    </div>
    <?php
}
