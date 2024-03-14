<?php

    /*
        Plugin Name: iDEAL-Checkout (native) - Rabo Smart Pay for WooCommerce
        Plugin URI: https://www.ideal-checkout.nl/plug-ins/free-plugins/wordpress-woocommerce
        Author: iDEAL Checkout
        Author URI: https://www.ideal-checkout.nl/over-ons
        Description: Accept payments through Visa, MasterCard, Apple Pay, Paypal, Maestro, iDEAL and Bancontact with Rabo Smart Pay for WooCommerce.
        Text Domain: ic-woo-omnikassa-2
        Domain Path: /languages
        Version: 2.2.9.4
    */

    // Block output if accessed directly
    if (!defined('ABSPATH')) {
        exit;
    }

    define('ICWOOROK_ROOT_PATH', plugin_dir_path(__FILE__));
    define('ICWOOROK_ROOT_URL', plugin_dir_url(__FILE__));

    // Load default plugin functions
    require_once ABSPATH.DIRECTORY_SEPARATOR.'wp-admin'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'plugin.php';

    // Load our libraries
    if (!defined('ICROK_FUNCTIONS_LOADED')) {
        require_once ICWOOROK_ROOT_PATH.'includes'.DIRECTORY_SEPARATOR.'functions.php';
    }

    // Load text domain
    load_plugin_textdomain('ic-woo-rabo-omnikassa-2', false, plugin_basename(dirname(__FILE__)).DIRECTORY_SEPARATOR.'languages/');

    // Check if cUrl is installed on this server
    if (!function_exists('curl_version')) {
        function icwoorok2_doShowCurlError()
        {
            echo '<div class="error"><p>Curl is not installed.<br>In order to use the Rabo Smart Pay, you must install install CURL.<br>Ask your system administrator/hosting provider to install php_curl</p></div>';
        }
        add_action('admin_notices', 'icwoorok2_doShowCurlError');
    }

    $bFound = false;

    // Is WooCommerce active on this Wordpress installation?
    if (is_plugin_active('woocommerce'.DIRECTORY_SEPARATOR.'woocommerce.php') || is_plugin_active_for_network('woocommerce'.DIRECTORY_SEPARATOR.'woocommerce.php')) {
        $bFound = true;
    } elseif (is_plugin_active('woocommerce/woocommerce.php') || is_plugin_active_for_network('woocommerce/woocommerce.php')) {
        $bFound = true;
    }

    if ($bFound) {
        include ICWOOROK_ROOT_PATH.'controllers'.DIRECTORY_SEPARATOR.'icwoorok2-controller.php';
        Icwoorok2Controller::init();
    } else {
        // Woocommerce isn't active, show error
        function icwoorok2_doShowWoocommerceError()
        {
            echo '<div class="error"><p>Rabo Smart Pay plugin requires WooCommerce to be active</p></div>';
        }
        add_action('admin_notices', 'icwoorok2_doShowWoocommerceError');
    }

    function icwoorok2_appendLinks($links_array, $plugin_file_name, $plugin_data, $status)
    {
        if (strpos($plugin_file_name, basename(__FILE__))) {
            // $links_array[] = '<a href="https://www.ideal-checkout.nl/nl/rpp">Documentation</a>';
            $links_array[] = '<a href="https://wordpress.org/support/plugin/woo-rabo-omnikassa/">Support</a>';
        }

        return $links_array;
    }
    add_filter('plugin_row_meta', 'icwoorok2_appendLinks', 10, 4);

    function icwoorok2_pluginLinks($links)
    {
        $actionLinks = [
            'settings' => '<a href="'.admin_url('admin.php?page=wc-settings&tab=checkout').'" aria-label="'.esc_attr__('View WooCommerce settings', 'woocommerce').'">'.esc_html__('Settings', 'woocommerce').'</a>',
        ];

        return array_merge($actionLinks, $links);
    }
    add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'icwoorok2_pluginLinks');



    add_action('plugins_loaded', 'icwoorok2_includeGateways', 0);
    function icwoorok2_includeGateways(){
        if (!class_exists('WC_Payment_Gateway'))
            return; // if the WC payment gateway class 
    
        include(ICWOOROK_ROOT_PATH . 'gateways/abstract.php');
        include(ICWOOROK_ROOT_PATH . 'gateways/ideal.php');
        include(ICWOOROK_ROOT_PATH . 'gateways/bancontact.php');
        include(ICWOOROK_ROOT_PATH . 'gateways/cards.php');
        include(ICWOOROK_ROOT_PATH . 'gateways/paypal.php');
        include(ICWOOROK_ROOT_PATH . 'gateways/sofort.php');
    }
    
    add_filter('woocommerce_payment_gateways', 'icwoorok2_addSmartpayGateways');
    
    function icwoorok2_addSmartpayGateways($gateways) {
        $gateways[] = 'icwoorok2_ideal';
        $gateways[] = 'icwoorok2_bancontact';
        $gateways[] = 'icwoorok2_cards';
        $gateways[] = 'icwoorok2_paypal';
        $gateways[] = 'icwoorok2_sofort';
        return $gateways;
    }
    
    /**
     * Custom function to declare compatibility with cart_checkout_blocks feature 
    */
    function icwoorok2_checkoutBlocksCompatibility() {
        // Check if the required class exists
        if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
            // Declare compatibility for 'cart_checkout_blocks'
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
        }
    }
    // Hook the custom function to the 'before_woocommerce_init' action
    add_action('before_woocommerce_init', 'icwoorok2_checkoutBlocksCompatibility');
    
    
    function icwoorok2_registerPaymentMethods() {
        // Check if the required class exists
        if ( ! class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
            return;
        }
    
        // Include the custom Blocks Checkout class
        require_once ICWOOROK_ROOT_PATH . 'blocks/ideal.php';
        require_once ICWOOROK_ROOT_PATH . 'blocks/bancontact.php';
        require_once ICWOOROK_ROOT_PATH . 'blocks/cards.php';
        require_once ICWOOROK_ROOT_PATH . 'blocks/paypal.php';
        require_once ICWOOROK_ROOT_PATH . 'blocks/sofort.php';
    
        // Hook the registration function to the 'woocommerce_blocks_payment_method_type_registration' action
        add_action(
            'woocommerce_blocks_payment_method_type_registration',
            function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
                // Register an instance of icwoorok2_Cards_Blocks
                $payment_method_registry->register( new Icwoorok2_Ideal_Blocks );
                $payment_method_registry->register( new Icwoorok2_Bancontact_Blocks );
                $payment_method_registry->register( new Icwoorok2_Cards_Blocks );
                $payment_method_registry->register( new Icwoorok2_Paypal_Blocks );
                $payment_method_registry->register( new Icwoorok2_Sofort_Blocks );
            }
        );
    }
    
    // Hook the custom function to the 'woocommerce_blocks_loaded' action
    add_action( 'woocommerce_blocks_loaded', 'icwoorok2_registerPaymentMethods' );

