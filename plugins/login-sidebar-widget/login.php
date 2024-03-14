<?php
/*
Plugin Name: Login Widget With Shortcode
Plugin URI: https://wordpress.org/plugins/login-sidebar-widget/
Description: This is a simple login form in the widget. just install the plugin and add the login widget in the sidebar. Thats it. :)
Version: 6.1.1
Text Domain: login-sidebar-widget
Domain Path: /languages
Author: aviplugins.com
Author URI: https://www.aviplugins.com/
*/

/*
	  |||||   
	<(`0_0`)> 	
	()(afo)()
	  ()-()
*/

define('LSW_DIR_NAME', 'login-sidebar-widget');
define('LSW_DIR_PATH', dirname(__FILE__));

// CONFIG
include_once LSW_DIR_PATH . '/config/config-emails.php';
include_once LSW_DIR_PATH . '/config/config-default-fields.php';

function plug_install_lsw() {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    if (is_plugin_active('fb-login-widget-pro/login.php') || is_plugin_active('social-login-no-setup/login.php')) {
        wp_die('It seems you have <strong>Facebook Login Widget (PRO)</strong> or <strong>Social Login No Setup</strong> plugin activated. Please deactivate that to continue.');
        exit;
    }

    include_once LSW_DIR_PATH . '/includes/class-settings.php';
    include_once LSW_DIR_PATH . '/includes/class-scripts.php';
    include_once LSW_DIR_PATH . '/includes/class-form.php';
    include_once LSW_DIR_PATH . '/includes/class-forgot-password.php';
    include_once LSW_DIR_PATH . '/includes/class-message.php';
    include_once LSW_DIR_PATH . '/includes/class-login-log-adds.php';
    include_once LSW_DIR_PATH . '/includes/class-security.php';
    include_once LSW_DIR_PATH . '/includes/class-login-log.php';
    include_once LSW_DIR_PATH . '/includes/class-paginate.php';
    include_once LSW_DIR_PATH . '/includes/class-login-form.php';
    include_once LSW_DIR_PATH . '/login-ap-widget.php';
    include_once LSW_DIR_PATH . '/process.php';
    include_once LSW_DIR_PATH . '/login-ap-widget-shortcode.php';
    include_once LSW_DIR_PATH . '/functions.php';

    new Login_Settings;
    new Login_Scripts;
    new AP_Login_Log;
    new AP_Login_Form;
}

class LSW_Load_Init {
    function __construct() {
        plug_install_lsw();
    }
}

new LSW_Load_Init;

add_action('widgets_init', function () {register_widget('Login_Widget');});

add_action('init', 'login_validate');
add_action('init', 'forgot_pass_validate');

add_shortcode('login_widget', 'login_widget_ap_shortcode');
add_shortcode('forgot_password', 'forgot_password_ap_shortcode');

add_action('plugins_loaded', 'security_init');

add_action('plugins_loaded', 'login_widget_ap_text_domain');

add_filter('lsw_login_errors', 'lsw_login_error_message', 10, 1);

add_filter('lwws_user_captcha_field', 'lwws_user_captcha_field_no_auto', 10, 1);

add_filter('lwws_admin_captcha_field', 'lwws_user_captcha_field_no_auto', 10, 1);

add_action('template_redirect', 'start_session_if_not_started');

register_activation_hook(__FILE__, 'lsw_setup_init');