<?php
/**
 * @package Tolstoy Video
 * @version 1.0.10
 */
/*
Plugin Name: Tolstoy Video
Plugin URI: https://www.gotolstoy.com/
Description: This is Tolstoy's widget integration
Author: Tolstoy
Version: 1.0.10
*/
function add_tolstoy_app_key() {
    wp_register_script( 'tolstoy-app-key', '', array(), '', false );
    wp_enqueue_script( 'tolstoy-app-key' );

    $options = get_option('tolstoy_plugin_options');
    $inline_script = 'window.tolstoyAppKey="' . esc_attr($options['app_key']) . '";';
    wp_add_inline_script( 'tolstoy-app-key', $inline_script );
}

function add_tolstoy_js() {
    $options = get_option('tolstoy_plugin_options');
    if ($options) {
        wp_enqueue_script('tolstoy', 'https://widget.gotolstoy.com/widget/widget.js?app-key=' . esc_attr($options['app_key']), array(), '', false);
    }
}

function add_tolstoy_settings_page() {
    add_options_page( 'Tolstoy plugin page', 'Tolstoy', 'manage_options', 'tolstoy-plugin', 'tolstoy_render_plugin_settings_page' );
}

function tolstoy_render_plugin_settings_page() {
?>
    <h2>Tolstoy Plugin Settings</h2>
    <form action="options.php" method="post">
        <?php
        settings_fields('tolstoy_plugin_options');
        do_settings_sections('tolstoy_plugin'); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save'); ?>" />
    </form>
<?php
}

function tolstoy_plugin_options_validate($options) {
    $input['app_key'] = trim($options['app_key']);
    if (!preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $input['app_key'])) {
        $input['app_key'] = '';
    }

    return $input;
}

function tolstoy_plugin_section_text() {
    echo '<p>Here you can set all the options for using the API</p>';
}

function tolstoy_plugin_setting_app_key()
{
    $options = get_option('tolstoy_plugin_options');
    $value = $options ? $options['app_key'] : '';

    echo "<input id='tolstoy_plugin_setting_app_key' name='tolstoy_plugin_options[app_key]' type='text' value='" . esc_attr($value) . "' size='40' />";
}

function tolstoy_register_settings() {
    register_setting(
        'tolstoy_plugin_options',
        'tolstoy_plugin_options',
        'tolstoy_plugin_options_validate'
    );
    add_settings_section( 'api_settings', 'API Settings', 'tolstoy_plugin_section_text', 'tolstoy_plugin' );
    add_settings_field( 'tolstoy_plugin_setting_app_key', 'App Key', 'tolstoy_plugin_setting_app_key', 'tolstoy_plugin', 'api_settings' );
}

add_action( 'admin_menu', 'add_tolstoy_settings_page' );
add_action( 'admin_init', 'tolstoy_register_settings' );
add_action( 'wp_enqueue_scripts', 'add_tolstoy_app_key' );
add_action( 'wp_enqueue_scripts', 'add_tolstoy_js' );

?>
