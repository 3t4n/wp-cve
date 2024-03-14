<?php

namespace WOOER;

if (!defined('ABSPATH')) {
    exit;
}

class Main {

    public function __construct() {
        // Load translations
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        // Register JS
        add_action('wp_enqueue_scripts', array($this, 'register_js')); //front
        add_action('admin_enqueue_scripts', array($this, 'register_js'));//admin
        add_action('wp_ajax_change_currency_action', array($this, 'change_currency_action'));
        add_action('wp_ajax_nopriv_change_currency_action', array($this, 'change_currency_action'));
        // Register WP widget
        add_action('widgets_init', array($this, 'register_widgets'));
        // Init price manager with WooCommerce price hooks
        Price_Manager::init();
        // Init currency manager with WooCommerce currency hooks
        Currency_Manager::init();
        // Init order manager with WooCommerce order hooks
        Order_Manager::init();        
        // Init admin panel manager with WooCommerce settings page hooks
        if (is_admin()) {
            // Check plugin version and update database if needed
            wooer_upgrade();
            
            AdminPanel_Manager::init();
        }
    }

    public function register_widgets() {
        register_widget('\\WOOER\\Currency_List_Widget');
    }

    public function register_js() {
        wp_enqueue_script('ajax-script', WOOER_PLUGIN_URL . 'assets/js/woo-exchange-rate.js', array('jquery'));
        // in JavaScript, object properties are accessed as woo-exchange-rate.ajax_url
        wp_localize_script('ajax-script', 'woo_exchange_rate', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    /**
     * Ajax 'change_currency_action' action backend part
     */
    public function change_currency_action() {
        global $wp_widget_factory;

        //validate code
        $code = sanitize_text_field($_POST['currency_code']);

        //store in session new currency
        Currency_Manager::set_currency_code($code);

        //recalculate cart totals (refresh with new price)
        \WC()->cart->calculate_totals();

        //output JSON
        echo json_encode(array('currency_code' => $code));
        wp_die();
    }

    /**
     * Load plugin textdomain.
     */
    public function load_textdomain() {
        $file = 'woo-exchange-rate';
        load_plugin_textdomain('woo-exchange-rate', false, $file . '/languages/');
    }
    
    /**
     * Current plugin version from database
     * @return string
     */
    public static function get_plugin_db_version () {
        return get_option('wooer_plugin_version');
    }
    
    /**
     * Save plugin version in database
     * @param string $version
     * @return bool
     */
    public static function save_plugin_db_version ($version = '') {
        if (!$version) {
            $version = self::get_plugin_current_version();
            if (!$version) {
                return false;
            }
        }
        
        if (self::get_plugin_db_version())
        {
            return update_option('wooer_plugin_version', $version);
        }
        
        return add_option('wooer_plugin_version', $version);
    }

    /**
     * Current plugin version from PHP file
     * @return string
     */
    public static function get_plugin_current_version () {
        require_once ABSPATH . '/wp-admin/includes/plugin.php';
        $plugin_data = \get_plugin_data(__DIR__ . '/../woo-exchange-rate.php');
        return isset($plugin_data['Version']) ? $plugin_data['Version'] : '';
    }
    
    /**
     * Plugin database versions
     * @var array 
     */
    public static $versionMap = ['17.3'];

}
