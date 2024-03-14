<?php
/*
Plugin Name: WP Register Profile With Shortcode
Plugin URI: https://wordpress.org/plugins/wp-register-profile-with-shortcode/
Description: This is a simple registration form in the widget. just install the plugin and add the register widget in the sidebar. Thats it. :)
Version: 3.6.0
Text Domain: wp-register-profile-with-shortcode
Domain Path: /languages
Author: aviplugins.com
Author URI: https://www.aviplugins.com/
 */

/*
/*      |||||
/*    <(`0_0`)>
/*    ()(afo)()
/*      ()-()
 */

// CONFIG

define('WPRPWS_DIR_NAME', 'wp-register-profile-with-shortcode');

define('WPRPWS_DIR_PATH', dirname(__FILE__));

include_once WPRPWS_DIR_PATH . '/config/config-emails.php';

include_once WPRPWS_DIR_PATH . '/config/config-default-fields.php';

// CONFIG

function wrrp_plug_install()
{
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    if (is_plugin_active('wp-register-profile-pro/register.php')) {
        wp_die('It seems you have <strong>WP Register Profile PRO</strong> plugin activated. Please deactivate to continue.');
        exit;
    }
    include_once WPRPWS_DIR_PATH . '/includes/class-settings.php';
    include_once WPRPWS_DIR_PATH . '/includes/class-scripts.php';
    include_once WPRPWS_DIR_PATH . '/includes/class-form.php';
    include_once WPRPWS_DIR_PATH . '/includes/class-admin-security.php';
    include_once WPRPWS_DIR_PATH . '/includes/class-edit-profile.php';
    include_once WPRPWS_DIR_PATH . '/includes/class-password-update.php';
    include_once WPRPWS_DIR_PATH . '/includes/class-register-process.php';
    include_once WPRPWS_DIR_PATH . '/includes/class-register-form.php';
    include_once WPRPWS_DIR_PATH . '/register-widget.php';
    include_once WPRPWS_DIR_PATH . '/register-widget-shortcode.php';
    include_once WPRPWS_DIR_PATH . '/functions.php';

    new Register_Settings;
    new Register_Scripts;
    new Register_Profile_Edit;
    new Register_Update_Password;
    new Register_Admin_Security;
    new Register_Process;

}

class WP_Register_Init
{
    public function __construct()
    {
        wrrp_plug_install();
    }
}
new WP_Register_Init;

register_activation_hook(__FILE__, 'wp_register_profile_set_default_data');

add_shortcode('rp_register_widget', 'register_widget_shortcode');

add_shortcode('rp_profile_edit', 'user_profile_edit_shortcode');

add_shortcode('rp_update_password', 'update_password_shortcode');

add_shortcode('rp_user_data', 'wprp_get_user_data');

add_action('wprp_after_insert_user', 'wprp_set_user_flag', 1, 1);

add_action('widgets_init', function () {register_widget('Register_Wid');});

add_action('plugins_loaded', 'wp_register_profile_text_domain');
