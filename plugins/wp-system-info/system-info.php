<?php

/*
 * Plugin Name: WP System Info
 * Version: 1.5
 * Plugin URI: https://wordpress.org/plugins/wp-system-info
 * Description: See the basic and main system information about yout site and server. 
 * Author: Nurul Amin
 * Author URI: http://nurul.ninja
 * Requires at least: 5.0        
 * Tested up to: 6.0.2
 * License: GPL2
 * Text Domain: bsi
 * Domain Path: /lang/
 *
 */

class Bbtech_SI
{

    public $version = '1.5';
    public $db_version = '1.1';
    protected static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct()
    {

        $this->init_actions();

        $this->define_constants();
        spl_autoload_register(array($this, 'autoload'));
        // Include required files


        register_activation_hook(__FILE__, array($this, 'install'));
        //Do some thing after load this plugin

        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));

        do_action('bsi_loaded');
    }



    function install()
    {
    }

    function init_actions()
    {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
    }





    function autoload($class)
    {
        $name = explode('_', $class);
        if (isset($name[1])) {
            $class_name = strtolower($name[1]);
            $filename = dirname(__FILE__) . '/class/' . $class_name . '.php';
            if (file_exists($filename)) {
                require_once $filename;
            }
        }
    }

    public function define_constants()
    {

        $this->define('BSI_VERSION', $this->version);
        $this->define('BSI_DB_VERSION', $this->db_version);
        $this->define('BSI_PATH', plugin_dir_path(__FILE__));
        $this->define('BSI_URL', plugins_url('', __FILE__));
    }

    public function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }


    function load_textdomain()
    {
        load_plugin_textdomain('bsi', false, dirname(plugin_basename(__FILE__)) . '/lang/');
    }


    static function admin_scripts()
    {

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('bsi_admin', plugins_url('assets/js/script.js', __FILE__), '', false, true);
        wp_localize_script('bsi_admin', 'BSI_Vars', array(
            'ajaxurl'       => admin_url('admin-ajax.php'),
            'nonce'         => wp_create_nonce('bsi_nonce'),
            'pluginURL'     => BSI_URL,

        ));

        wp_enqueue_style('bsi_admin', plugins_url('/assets/css/style.css', __FILE__));

        wp_enqueue_style('dashicons');
        do_action('bsi_admin_scripts');
    }


    function admin_menu()
    {
        $capability = 'read'; //minimum level: subscriber
        $page_title = __('System Info', 'bsi');
        $menu_title = __('System Info', 'bsi');
        $menu_slug = "/bsi_system_info";
        $callback = array($this, 'system_info_view');
        $icon_url = '';
        $position = 70;
        add_menu_page($page_title,  $menu_title,  $capability,  $menu_slug,  $callback,  'dashicons-info',  $position);

        add_submenu_page(
            $menu_slug,
            __('PHP info()', 'bsi'),
            __('PHP info()', 'bsi'),
            $capability,
            'php-info',
            array($this, 'show_php_info'),
            1
        );

        add_submenu_page(
            $menu_slug,
            __('File Permission', 'bsi'),
            __('File Permission', 'bsi'),
            $capability,
            'file-permission',
            array($this, 'show_file_permission'),
            2
        );

        add_submenu_page(
            $menu_slug,
            __('Get More Free Item', 'bsi'),
            __('Get More Free Item', 'bsi'),
            $capability,
            'get-more-free-item',
            array($this, 'get_more_free_item'),
            3
        );


        do_action('bsi_admin_menu', $capability, $this);
    }

    function system_info_view()
    {
        require(BSI_PATH . '/view/status.php');
    }

    function show_php_info()
    {

        require(BSI_PATH . '/view/php-info.php');
    }

    function show_file_permission()
    {
        $files = $this->fetchWpFiles(ABSPATH);
        require(BSI_PATH . '/view/show-file-permission.php');
    }

    function get_more_free_item()
    {

        $freeItems = array(

            [
                'image' => "https://ps.w.org/subscription/assets/icon-128x128.png",
                "title" => " Subscription for WooCommerce",
                "text" => "A powerfull plugin that allow to enable subscription on woocommerce products.",
                "url" => "https://wordpress.org/plugins/subscription/",
            ],

            [
                'image' => "https://ps.w.org/wc-booking/assets/icon-128x128.png",
                "title" => "Booking for wooCommerce",
                "text" => "Show available dates, time in a simple dropdown, take booking for products and services.",
                "url" => "https://wordpress.org/plugins/wc-booking/",
            ],

            [
                'image' => "https://ps.w.org/wc-pre-order/assets/icon-256x256.jpg",
                "title" => "Pre Order Addon for WooCommerce",
                "text" => "Create gift vouchers, store credits, special discounts based on the amount spent, etc.",
                "url" => "https://wordpress.org/plugins/wc-pre-order/",
            ],


            [
                'image' => "https://ps.w.org/bulk-products-selling/assets/icon-128x128.png",
                "title" => "Bulk Product Selling ",
                "text" => "Sell many products in one Like Group Product. But you can use single price here.",
                "url" => "https://wordpress.org/plugins/bulk-products-selling/",
            ],

            [
                'image' => "https://ps.w.org/pdf-invoices-and-packing-slips/assets/icon-128x128.png",
                "title" => "PDF Invoices & Packing Slips",
                "text" => "With WooCommerce PDF Invoices and Packing Slips, attach necessary PDF invoices with order confirmation emails.",
                "url" => "https://wordpress.org/plugins/pdf-invoices-and-packing-slips/",
            ],


            [
                'image' => "https://ps.w.org/advance-coupons-for-woocommerce/assets/icon-128x128.png",
                "title" => "Advance Coupons for Woocommerce",
                "text" => "Create gift vouchers, store credits, special discounts based on the amount spent, etc.",
                "url" => "https://wordpress.org/plugins/advance-coupons-for-woocommerce/",
            ],

            
            [
                'image' => "https://ps.w.org/wc-sms-notification/assets/icon-128x128.png",
                "title" => "WC SMS Notification",
                "text" => "Order SMS Notofication for WooCommerce.",
                "url" => "https://wordpress.org/plugins/wc-sms-notification/",
            ],

            [
                'image' => "https://ps.w.org/product-sharing-buttons/assets/icon-128x128.png",
                "title" => "Social Sharing Button",
                "text" => "Share your zest with your friends and others.",
                "url" => "https://wordpress.org/plugins/product-sharing-buttons/",
            ],



            [
                'image' => "https://ps.w.org/checkout-field-customizer/assets/icon-128x128.png",
                "title" => "Checkout Field Customizer",
                "text" => "Customize your checkout fields easily !!",
                "url" => "https://wordpress.org/plugins/checkout-field-customizer/",
            ],







        );
        require(BSI_PATH . '/view/free-plugins.php');
    }



    function fetchWpFiles($dir)
    {
        try {
            $x = scandir($dir);
        } catch (exception $e) {
            return false;
        }
        $result = array();
        foreach ($x as $filename) {
            if ($filename == '.') continue;
            if ($filename == '..') continue;
            $result[] = $dir . $filename;
            $filePath = $dir . $filename;
            if (is_dir($filePath)) {
                $filePath = $dir . $filename . '/';
                foreach ($this->fetchWpFiles($filePath) as $childFilename) {
                    $result[] = $childFilename;
                }
            }
        }
        return $result;
    }
}


function bsi()
{
    return Bbtech_SI::instance();
}
//bsi instance.
$bsi = bsi();
