<?php
/**
* Plugin Name: WS Force Login Page
* Plugin URI: https://www.silvermuru.ee/en/wordpress/plugins/ws-force-login-page/
* Description: Redirecting user to login page if not logged in and when needed the custom message is shown
* Version: 3.0.3
* Author: UusWeb.ee
* Author URI: https://www.wordpressi-kodulehe-tegemine.ee/
* Text Domain: ws-force-login-page
**/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class WS_Force_Login_Page {
        public function __construct(){
        add_action( 'plugins_loaded', array( $this, 'ws_force_login_load_textdomain' ) );
        add_action( 'plugins_loaded', array( $this, 'check_if_user_logged_in' ) );
        add_action( 'init', array( $this, 'ws_force_login_message_show' ) );
        }

        public function ws_force_login_load_textdomain() {
                load_plugin_textdomain( 'ws-force-login-page', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
        }

        public function check_if_user_logged_in(){
                if ( !is_user_logged_in() ) {
                        global $pagenow;
                        if ( 'wp-login.php' !== $pagenow && get_option('wsforce-login-active-option') == '1' ){
                                wp_redirect( wp_login_url() . '?message=wsforce-login-page' );
                                exit;
                        }
                }
        }

        public function ws_force_login_message_show(){
                $login_variable_message = isset($_GET['message']) ? $_GET['message'] : '';
                global $pagenow;
                if ( !is_user_logged_in() &&  'wp-login.php' == $pagenow && $login_variable_message == 'wsforce-login-page' && get_option('wsforce-login-message-option') != false ) {
                        function force_login_message_text() {
                                return '<p class="message">' . get_option('wsforce-login-message-option')  . '</p>';
                        }
                        return add_filter('login_message', 'force_login_message_text');
                }
        }
}

if ( is_admin() ) {
	require plugin_dir_path( __FILE__ ) . '/admin/ws-force-login-page-admin.php';
}

$wpse_ws_force_login_page_plugin = new WS_Force_Login_Page();
?>
