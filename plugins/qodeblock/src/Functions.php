<?php

namespace WordressLaravel\Wp;

use WordressLaravel\Wp\Mail;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Functions {

    /**
     * Actions on Init
     */
    public function init_actions() {
        add_action( 'admin_menu',    [ $this, 'laravel_wordpress_menu' ] );
        add_action( 'admin_menu',    [ $this, 'woocommerce_permissions_func'] );

//        add_action( 'wp_ajax_set_wc_access',        [ $this, 'set_wc_access' ] );
//        add_action( 'wp_ajax_nopriv_set_wc_access', [ $this, 'set_wc_access' ] );

        add_action( 'wp_ajax_set_api_script_file',        [ $this, 'set_api_script_file' ] );
        add_action( 'wp_ajax_nopriv_set_api_script_file', [ $this, 'set_api_script_file' ] );
    }

    /**
     * Create menus in plugin
     */
    public function laravel_wordpress_menu() {
        add_menu_page(
            'Plum: Spin Wheel & Email Pop-up',
            'Plum: Spin Wheel & Email Pop-up',
            'manage_options',
            'laravel_wordpress_integration',
            [ $this, 'laravel_wordpress_function' ],
            'dashicons-admin-site-alt'
        );
    }

    /**
     * Function configuration of woocommerce permission page
     */
    public function woocommerce_permissions_func() {
        add_menu_page(
            'Woocommerce Access page',
            'Woocommerce Access page',
            'manage_options',
            'woocommerce_access',
            [ $this, 'woocommerce_access_function']
        );
        remove_menu_page('woocommerce_access');
    }

    /**
     * Function for including view
     *
     */
    public function laravel_wordpress_function() {
        $wc_access = get_option('wc_api_access');

        if (isset($_GET['success'])) {
            $this->change_wc_access($_GET['success']);
        }

        if (empty($wc_access)) {
            $this->woocommerce_access_function();
        }
        else {
            require_once  plugin_dir_path(__FILE__) . 'templates/wordpress-platform.php';
        }
    }

    /**
     * Function for including view of woocommerce access
     *
     */
    public function woocommerce_access_function() {

        $config = include plugin_dir_path(__DIR__) . '/config/config.php';

        $ms_install_slug = $config['ms_install_slug'];
        $app_slug        = $config['app_slug'];
        $app_token       = $config['token'];
        $app_platform    = $config['platform'];

        $currentUser = wp_get_current_user();
        $store_url   = get_site_url();

        $endpoint = '/wc-auth/v1/authorize';
        $params = [
            'app_name'     => 'Laravel App',
            'scope'        => 'read_write',
            'user_id'      => $currentUser->id,
            'return_url'   => $store_url .'/wp-admin/admin.php?page=laravel_wordpress_integration',
            'callback_url' => $store_url . "/wp-json/laravel_wordpress/getWcData"
        ];
        $query_string = http_build_query( $params );

        $authWcLink = $store_url . $endpoint . '?' . $query_string;

        $wc_installed =  is_plugin_active( 'woocommerce/woocommerce.php' ) ? true : false;
        $wc_access = get_option('wc_api_access');
        $wc_access = (!empty($wc_access) && ($wc_access == 'yes')) ? true : false;
        $wc_access_true = (!empty($wc_installed) && (!empty($wc_access))) ? true : false;

        if ( !empty($wc_installed) && empty($wc_access) ) {
            wp_redirect($authWcLink);
            exit();
        }
        else {
            require_once plugin_dir_path(__FILE__) . 'templates/woocommerce-access-page.php';
        }
    }

    /**
     * Function callback for setting woocommerce api access
     *
     */
    public function set_wc_access() {
        $post = $_POST;

        $check = ($post['check'] == 'yes') ? 'yes' : 'no';
        $wc_access = get_option('wc_api_access');

        if (!empty($wc_access)) {
            update_option('wc_api_access', $check);
        }
        else {
            add_option('wc_api_access', $check);
        }

        $link = get_site_url() . "/wp-admin/admin.php?page=laravel_wordpress_integration";

        wp_send_json( array( 'error' => false, 'message' => 'Done.', 'href' => $link ), 200 );
    }


    /**
     * Function callback for setting api js file from application
     *
     */
    public function set_api_script_file() {
        $post = $_POST;
        $file_src = $post['file_src'];

        $api_file_link = get_option('api_file_link');
        $api_file_link = $api_file_link ?? '';

        if (!empty($api_file_link)) {
            update_option('api_file_link', $file_src);
        }
        else {
            add_option('api_file_link', $file_src);
        }

        wp_send_json(array('error' => false, 'message' => 'Done.'), 200 );
    }


    /**
     * Function callback for setting woocommerce api access
     *
     */
    public function change_wc_access($success) {
        $check = (!empty($success)) ? 'yes' : 'no';

        $wc_access = get_option('wc_api_access');

        if (!empty($wc_access)) {
            update_option('wc_api_access', $check);
        }
        else {
            add_option('wc_api_access', $check);
        }

        $link = get_site_url() . "/wp-admin/admin.php?page=laravel_wordpress_integration";

        wp_redirect($link);
        exit;
    }

}
