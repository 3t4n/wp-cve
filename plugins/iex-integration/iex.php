<?php
/*
Plugin Name: IEX Integration for WooCommerce
Plugin URI: http://iex.dk
Description: Syncronize products and orders to your ERP
Version: 2.2.5.11
Author: IEX Integration
Author URI: http://iex.dk
License: GPL2

* WC requires at least: 2.6
* WC tested up to: 3.5
 */

// Load IEX client api //
require_once('iex_api_client.php');

class IEX_Integration {

    private $iex_plugin_current_version = '2.2.5.11';
    private $general_settings_key = 'iex_general';
    private $advanced_settings_key = 'iex_settings';
    private $manual_sync_settings_key = 'iex_sync';
    private $plugin_options_key = 'iex-integration';
    private $plugin_settings_tabs = array();

    public function __construct() {
        // Add actions
        add_action('init', array($this, 'iex_localization_init'));
        // Make admin menu
        add_action('admin_menu', array($this, 'iex_settings_menu'));
        // Register settings
        add_action('admin_init', array($this, 'iex_register_settings'));
        // Add settings to plugin page
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'iex_action_links'));
        // Trigger function on product save
        add_action('save_post', array($this, 'iex_update_product'), 10);
        // Trigger function on order save
        add_action('save_post', array($this, 'order_create'), 10);
        if ( version_compare( WC_VERSION, '3.0', '>=' ) ) {
            //add_action('woocommerce_update_order', array($this, 'order_create'), 10); // REMOVED - Sends the order 3 times, which can inflict faulty updates.
        }
        // Trigger function on taxonomy save
        add_action( 'edited_product_cat', array($this, 'iex_term_edit'), 10, 2 );
        add_action( 'create_term', array( $this, 'iex_term_create' ), 10, 3 );

        add_action('woocommerce_checkout_order_processed', array($this, 'order_create_checkout'));

        add_action('admin_enqueue_scripts', array($this, 'iex_enqueue_scriptstyle') );

        add_filter('query_vars', array($this, 'iex_queryvars'));
        add_filter('wp', array($this, 'iex_request'));

        add_action( 'wp_ajax_iex_check_key', array($this, 'iex_check_key_callback') );

        add_filter( 'site_transient_update_plugins', array($this, 'iex_filter_plugin_updates') );

        $api = $this->iexClient();
        $this->api = $api;
    }

    /********************************
     *  Enqueue our scripts/styles  *
     ********************************/
    function iex_enqueue_scriptstyle(){
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        wp_enqueue_style('admin-css', plugin_dir_url(__FILE__) . 'css/admin.css');
        wp_enqueue_script('admin-script', plugin_dir_url(__FILE__) . 'js/admin.js');
    }

    /********************************
     *  Make sure we can localize   *
     ********************************/

    function iex_localization_init() {
        load_plugin_textdomain('iex_integration', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /********************************
     *    Disable Plugin updates    *
     ********************************/
    function iex_filter_plugin_updates( $value ) {
        // Uncomment the unset line below to disable update notification
        if ( isset( $value ) && is_object( $value ) ) {
            //unset( $value->response[ plugin_basename(__FILE__) ] );
        }
        return $value;
    }

    /********************************
     * Settings URL from plugin page*
     ********************************/

    function iex_action_links($links) {
        $links[] = '<a href="' . get_admin_url(null, 'admin.php?page=' . $this->plugin_options_key) . '">' . __('Settings') . '</a>';
        return $links;
    }

    /********************************
     *   Make the settings menu     *
     ********************************/

    function iex_settings_menu() {
        add_menu_page('IEX Integration', 'IEX Integration', 'manage_options', $this->plugin_options_key, array($this, 'iex_settings'), plugins_url('img/iex-integration-wordpress.png', __FILE__), 71.005);
    }

    /********************************
     *       Settings tabs          *
     ********************************/

    function iex_options_tabs() {

        $current_tab = isset($_GET['tab']) ? $_GET['tab'] : $this->general_settings_key;

        echo '<h2 class="nav-tab-wrapper">';
        foreach ($this->plugin_settings_tabs as $tab_key => $tab_caption) {
            $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
            echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
        }
        echo '</h2>';
    }

    /********************************
     *  Settings fields for input   *
     ********************************/

    function iex_settings_options() {

        $cust_info = '';

        $api_key = get_option('iex_api_key');

        if ($api_key) {
            $customer_info = get_option('iex_customer_information');

            if ($customer_info) {
                foreach ($customer_info as $key => $value) {
                    if ($key !== 'id' && $key !== 'status_message') {
                        $cust_info .= ucfirst($key) . ': ' . ucfirst($value) . '<br />';
                    }
                }
                $erp_system = $this->api->system;
                /* if ($erp_system) {
                    $cust_info .= 'ERP System: ' . ucfirst($erp_system['erp']);
                } */
            }
        }

        $options['general'] = array(
            array(
                'id' => 'general_section',
                'name' => __('General IEX Integration', 'iex_integration'),
                'type' => 'section',
                'explanation' => '',
                'section' => '',
            ),
            array(
                'id' => 'iex_api_key',
                'name' => __('API Key', 'iex_integration'),
                'type' => 'text',
                'explanation' => '<button id="iexcheckkey" class="button button-primary">'.__('Check key', 'iex_integration').'</button><div id="iexspin" style="float: none; margin: 0 0 5px" class="spinner"></div>',
                'section' => 'general_section',
                'placeholder' => __('API key', 'iex_integration')
            ),
            array(
                'id' => 'iex_api_key_2nd',
                'name' => __('API Key 2nd', 'iex_integration'),
                'type' => 'text',
                'explanation' => '<button id="iexsavekey" class="button button-primary">' . __('Save key', 'iex_integration') . '</button> ' . __('Only if you got more than one integration', 'iex_integration'),
                'section' => 'general_section',
                'placeholder' => __('API key 2nd', 'iex_integration'),
            ),
            array(
                'id' => 'iex_api_submit',
                'type' => 'submit',
                'section' => 'general_section',
                'explanation' => '<button id="iexcheckkey" class="button button-primary">'.__('Check key', 'iex_in     tegration').'</button><div id="iexspin" style="float: none; margin: 0 0 5px" class="spinner"></div>'
            ),
            array(
                'id' => 'iex_api_key_link',
                'name' => 'Info',
                'type' => 'cleartext',
                'explanation' => sprintf(__("If you don't have an API Key you can get a demo from %s.", 'iex_integration'), '<a target="_blank" href="https://iex.dk/en/more/demo-signup">iex.dk</a>'),
                'section' => 'general_section',
                'placeholder' => __('API key', 'iex_integration'),
                'classes' => 'demo_link'
            ),
            array(
                'id' => 'api_customer',
                'name' => __('Customer info', 'iex_integration'),
                'type' => 'cleartext',
                'explanation' => $cust_info,
                'section' => 'general_section',
                'classes' => 'customer_info'
            )
        );
        $options['settings'] = array(
            array(
                'id' => 'debug_section',
                'name' => __('Debug', 'iex_integration'),
                'type' => 'section',
                'explanation' => __('Debug settings', 'iex_integration'),
                'section' => '',
            ),
            array(
                'id' => 'iex_debug',
                'name' => __('Debug?', 'iex_integration'),
                'type' => 'checkbox',
                'explanation' => __('Enable debugging', 'iex_integration'),
                'section' => 'debug_section',
                'value' => 'true',
            ),
        );
        return $options;
    }

    /********************************
     *      Register settings       *
     ********************************/

    function iex_register_settings() {
        $customer_info = get_option('iex_customer_information');

        $this->plugin_settings_tabs[$this->general_settings_key] = __('General', 'iex_integration');

        if ($customer_info) {
            //$this->plugin_settings_tabs[$this->advanced_settings_key] = __('Settings', 'iex_integration');
            //$this->plugin_settings_tabs[$this->manual_sync_settings_key] = __('Manual Sync', 'iex_integration');
        }

        $options = $this->iex_settings_options();
        if ((isset($_GET['tab']) && $_GET['tab'] == 'iex_general') || !isset($_GET['tab'])) {
            $options = $options['general'];
        }
        if (isset($_GET['tab']) && $_GET['tab'] == 'iex_settings') {
            $options = $options['settings'];
        }
        if ($options) {
            foreach ($options as $option) {
                if (isset($option['type']) && $option['type'] == 'section') {
                    // Add the section to reading settings so we can add our fields to it
                    add_settings_section(
                        $option['id'], $option['name'], array($this, 'iex_setting_section_callback_function'), 'iex-settings', $args = array(
                        'explanation' => $option['explanation']
                    )
                    );
                } else {
                    if(isset($option['id']) && isset($option['name'])){
                        add_settings_field(
                            $option['id'], $option['name'], array($this, 'iex_setting_callback_function'), 'iex-settings', $option['section'], $args = array(
                            'text' => (isset($option['text']) ? $option['text'] : ''),
                            'id' => (isset($option['id']) ? $option['id'] : ''),
                            'type' => (isset($option['type']) ? $option['type'] : ''),
                            'explanation' => (isset($option['explanation']) ? $option['explanation'] : ''),
                            'value' => (isset($option['value']) ? $option['value'] : ''),
                            'placeholder' => (isset($option['placeholder']) ? $option['placeholder'] : ''),
                            'classes' => (isset($option['classes']) ? $option['classes'] : '')
                        )
                        );

                        // Register our setting so that $_POST handling is done for us and
                        // our callback function just has to echo the <input>
                        register_setting('iex-settings', $option['id']);
                    }
                } //End if
            }//End foreach
        }
    }

    /********************************
     *  Settings section callback   *
     ********************************/

    function iex_setting_section_callback_function($args = array()) {
        // Get explanation if any
        $options = $this->iex_settings_options();
        foreach ($options as $option) {
            $explanation = '';
            if (isset($option['id']) && isset($args['id']) && $option['id'] == $args['id']) {
                $explanation = $option['explanation'];
            }
        }
        echo '<p>' . $explanation . '</p>';
    }

    function iex_setting_callback_function($args = array()) {
        if ($args['type'] == 'checkbox') {
            echo '<input name="' . $args['id'] . '" id="' . $args['id'] . '" type="' . $args['type'] . '" value="' . $args['value'] . '" class="code" ' . checked($args['value'], get_option($args['id']), false) . ' /> ' . $args['explanation'];
        }
        if ($args['type'] == 'number') {
            echo '<input name="' . $args['id'] . '" id="' . $args['id'] . '" type="' . $args['type'] . '" class="code" value="' . get_option($args['id'], $args['value']) . '" /> ' . $args['explanation'];
        }
        if ($args['type'] == 'text') {
            echo '<input name="' . $args['id'] . '" id="' . $args['id'] . '" type="' . $args['type'] . '" class="code" value="' . get_option($args['id'], $args['value']) . '" placeholder="' . $args['placeholder'] . '" /> ' . $args['explanation'];
        }
        if ($args['type'] == 'cleartext') {
            echo '<div class="api_customer'.(isset($args['classes']) ? ' '.$args['classes'] : '').'">';
            echo '<span>' . $args['explanation'] . '</span>';
            echo '</div>';
        }
        if ($args['type'] == 'select') {
            echo '<select name="' . $args['id'] . '" id="' . $args['id'] . '">';
            foreach ($args['value'] as $key => $value) {
                echo '<option value="' . $key . '"' . ( get_option($args['id']) == $key ? ' selected="selected"' : '') . '>' . $value . '</option>';
            }
            echo '</select> ' . $args['explanation'];
        }
    }

    /********************************
     *      Output the settings     *
     ********************************/

    function iex_settings() {
        echo '<div class="wrap">';
        echo '<h2 class="iex_title">IEX Integration</h2>';
        $this->iex_options_tabs();
        if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
            echo '<div id="message" class="updated"><p>' . __('Settings saved.') . '</p></div>';
        }
        $api_key = get_option('iex_api_key');
        if (!$api_key) {
            echo '<div id="error" class="error"><p>' . __('Please enter API Key.', 'iex_integration') . '</p></div>';
        } else {
            $key_check = get_option('iex_customer_all_info');
            if ($key_check->status == 403 && $api_key) {
                echo '<div id="error" class="error"><p>' . __('ERROR: Invalid API Key!', 'iex_integration') . '</p></div>';
            }
            if ($key_check->status != 404 && $api_key && $key_check->message) {
                echo '<div id="error" class="error"><p>' . $key_check->message . '</p></div>';
            }
        }
        if ( (isset($_GET['tab']) && $_GET['tab'] == 'iex_general') || !isset($_GET['tab']) || $_GET['tab'] == 'iex_settings') {
            echo '<form method="post" action="options.php?tab=' . (isset($_GET['tab']) ? $_GET['tab'] : 'iex_general') . '">';
            settings_fields('iex-settings');
            do_settings_sections('iex-settings');
            //submit_button();
            echo '</form>';
            if ($api_key) {
                $this->iex_sanity_check_form();
            }
        }
        echo '</div>';
    }

    /*********************************
     * Make sure API is ready for us *
     *********************************/

    function iexClient() {
        $api_key = get_option('iex_api_key');
        $api_key_2nd = get_option('iex_api_key_2nd');

        return new IEX_APIClient($api_key, $api_key_2nd);
    }

    /********************************
     *  Ajax callback sanity check  *
     ********************************/

    function iex_check_key_callback(){

        update_option('iex_api_key', $_POST['key']);

        $this->api = $this->iexClient();

        $this->iex_api_get_info();

        $key_check = get_option('iex_customer_all_info');

        $customer_info = get_option('iex_customer_information');

        $cust_info = '';

        if ($customer_info) {
            foreach ($customer_info as $key => $value) {
                if ($key !== 'id' && $key !== 'status_message') {
                    $cust_info .= ucfirst($key) . ': ' . ucfirst($value) . '<br />';
                }
            }
            $erp_system = $this->api->system;
            if ($erp_system) {
                $cust_info .= 'ERP System: ' . ucfirst($erp_system['erp']);
            }
        }

        if($key_check->status == 404){
            echo json_encode(array('Success' => 'True', 'Message' => __('API Key checked ok!', 'iex_integration'), 'Returned' => $cust_info, 'api_ret' => $key_check));
        } else if ($key_check->status == 403) {
            echo json_encode(array('Success' => 'False', 'Message' => __('ERROR: Invalid API Key!', 'iex_integration')));
            update_option('iex_customer_information', '');
            update_option('iex_customer_system', '');
        } else if ($key_check->message) {
            echo json_encode(array('Success' => 'False', 'Message' => $key_check->message));
            update_option('iex_customer_information', '');
            update_option('iex_customer_system', '');
        } else {
            echo json_encode(array('Success' => 'False', 'Message' => __('Error getting info from IEX', 'iex_integration')));
            update_option('iex_customer_information', '');
            update_option('iex_customer_system', '');
        }

        wp_die();
    }

    /********************************
     *      Sanity check form       *
     ********************************/

    function iex_sanity_check_form() {
        echo '<form method="post" action="#sanity">';
        echo '<input type="hidden" value="true" name="sanity_check">';
        echo '<input type="submit" value="' . __('Sanity Check', 'iex_integration') . '" name="sanity" class="button">';
        echo '</form>';
        if (isset($_POST['sanity_check']) && $_POST['sanity_check'] == 'true') {
            $this->iex_sanity_check();
        }
    }

    /********************************
     *        Sanity check          *
     ********************************/

    function iex_sanity_check() {
        echo '<p id="sanity">' . __('Doing sanity checks. Please hold on.', 'iex_integration') . '</p>';

        // Let's first check if WooCommerce is activated!
        if (!class_exists('woocommerce')) {
            echo '<p>' . __('WooCommerce not activated! Please install and active WooCommerce to use this plugin.', 'iex_integration') . '</p>';
        } else {

            // TODO Is tax enabled check - reminder info to the customer
            $tax_enabled = get_option('woocommerce_calc_taxes');
            if ($tax_enabled == 'no') {
                echo '<p><b>' . __('NOTICE: Tax is not enabled in woocommerce shop configuration', 'iex_integration') . '</b></p>';
            }

            $this->iex_api_get_info();
            $this->iex_api_sanity();
            $this->erp_sanity_check();
            $this->shop_url_check();
            $this->iex_product_sanity();
        }
        echo '<p>' . __('Sanity check done. Please correct any errors above or your integration will not work properly!', 'iex_integration') . '</p>';
    }

    /********************************
     *    Get info from server      *
     ********************************/

    function iex_api_get_info() {
        $got_info = $this->api->get_info();

        if(isset($got_info->customer)){
            update_option('iex_customer_information', $got_info->customer);
        }
        if(isset($got_info->system)){
            update_option('iex_customer_system', $got_info->system);
        }
        if(isset($got_info->returned)){
            update_option('iex_customer_all_info', $got_info->returned);
        }
    }

    /********************************
     *        API Key check         *
     ********************************/

    function iex_api_sanity() {
        $key_check = get_option('iex_customer_all_info');

        echo '<p>' . __('Checking API Key', 'iex_integration') . '...</p>';

        if ($key_check->status == 404) {
            echo '<p>' . __('API Key checked ok!', 'iex_integration') . '</p>';
        } else if ($key_check->status == 403) {
            echo '<p><b>' . __('ERROR: Invalid API Key!', 'iex_integration') . '</b></p>';
        } else if ($key_check->message) {
            echo '<p><b>' . $key_check->message . '</b></p>';
        } else {
            echo '<p><b>' . __('Error getting info from IEX', 'iex_integration') . '!</b></p>';
        }
    }

    /********************************
     *      ERP System check        *
     ********************************/

    function erp_sanity_check() {
        $erp_system = get_option('iex_customer_system');

        echo '<p>' . __('Checking ERP System', 'iex_integration') . '...</p>';

        if (isset($erp_system['erp']) && $erp_system['erp'] !== '') {
            echo '<p>' . ucfirst($erp_system['erp']) . ' ' . __('found', 'iex_integration') . '.</p>';
        } else {
            echo '<p><b>' . __('ERROR: No ERP System setup. Contact IEX!', 'iex_integration') . '</b></p>';
        }
    }

    /********************************
     *        Shop URL check        *
     ********************************/

    function shop_url_check() {
        //$erp_system = $this->api->system;
        $all_info = get_option('iex_customer_all_info');

        $shop_urls = json_decode($all_info->customer_data->shopurl->meta_value);

        if (isset($all_info->customer_data->shopurl->meta_value)){
            $url_error = true;
            foreach ($shop_urls as $key => $shop_url) {
                if($shop_url->url == get_option('siteurl')){
                    echo '<p>' . __('Got: ', 'iex_integration') . $shop_url->url . '</p>';
                    $url_error = false;
                }
            }
            if($url_error){
                echo '<p><b>' . __('ERROR: Shop URL mismatch!', 'iex_integration') . '</b></p>';
                echo '<p><b>' . __('This: ', 'iex_integration') . get_option('siteurl') .'</b></p>';
            }
        }
    }

    /********************************
     *      Product SKU check       *
     ********************************/

    function iex_product_sanity() {
        echo '<p>' . __('Checking Product SKUs', 'iex_integration') . '...</p>';

        $args = array('post_type' => 'product', 'posts_per_page' => -1);
        $products = get_posts($args);

        $sku_check = '<p>' . __('All SKUs OK!', 'iex_integration') . '</p>';

        foreach ($products as $product) {
            $_pf = new WC_Product_Factory();

            $_product = $_pf->get_product($product->ID);

            if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
                $product_type = $_product->product_type;
                $post_id = $_product->post->ID;
                $post_title = $_product->post->post_title;
            } else {
                $product_type = $_product->get_type();
                $post_id = $_product->get_id();
                $post_title = $_product->get_title();
            }

            if ($product_type != 'variable' && $product_type!= "grouped") {
                if (strlen($_product->get_sku()) > 25) {
                    echo '<a href="post.php?post=' . $post_id . '&action=edit">' . $post_title . '</a> (#' . $post_id . ') - ' . __('SKU is more than 25 characters!', 'iex_integration') . '<br />';
                    $sku_check = '';
                }
                if (strlen($_product->get_sku()) < 1) {
                    echo '<a href="post.php?post=' . $post_id . '&action=edit">' . $post_title . '</a> (#' . $post_id . ') - ' . __('SKU empty!', 'iex_integration') . '<br />';
                    $sku_check = '';
                }
            } elseif ($product_type == 'variable') {
                $variations = $_product->get_available_variations();
                $parent_sku = $_product->get_sku();
                $vari_sku = false;
                foreach ($variations as $variation) {
                    if ($variation['sku'] != $parent_sku) {
                        if (strlen($variation['sku']) > 25) {
                            $vari_sku .= '<li style="margin-bottom: 0px">' . __('Variation', 'iex_integration') . ' #' . $variation['variation_id'] . ' - ' . __('SKU is more than 25 characters!', 'iex_integration') . '</li>';
                        }
                        if (strlen($variation['sku']) < 1) {
                            $vari_sku .= '<li style="margin-bottom: 0px">' . __('Variation', 'iex_integration') . ' #' . $variation['variation_id'] . ' - ' . __('SKU empty!', 'iex_integration') . '</li>';
                        }
                    } else {
                        $vari_sku .= '<li style="margin-bottom: 0px">' . __('Variation', 'iex_integration') . ' #' . $variation['variation_id'] . ' - ' . __('SKU same as parent product!', 'iex_integration') . '</li>';
                    }
                }
                if ($vari_sku) {
                    echo '<a href="post.php?post=' . $post_id . '&action=edit">' . $post_title . '</a> (#' . $post_id . ')';
                    echo '<ul style="margin-top: 0px; margin-left: 20px">' . $vari_sku . '</ul>';
                    $sku_check = '';
                }
            }
        }

        echo $sku_check;
    }

    /********************************
     *      On product update       *
     ********************************/

    function iex_update_product($post_id) {

        $post = get_post($post_id);

        // WooCommerce product?
        if ($post->post_type != 'product') {
            return false;
        }

        // Prevent autosaved and revisions to be transferred.
        if(wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return false;
        }

        // We got this far, now transfer the product
        $this->product_transfer($post_id);

        // Let's find all translations of this product (WPML Support)
        /*if(function_exists('icl_object_id')){
            $icl_products = icl_object_id($post_id, 'product', false);
        }*/

        $this->debug['product_transfer_return'] = $this->api->doTransfer(true);

        // Let's reset the transfer to make sure it isn't sent again
        $this->api->transfers = array();

        return true;
    }

    /********************************
     *       Product transfer       *
     ********************************/

    function product_transfer($post_id) {

        $products = array();

        if (empty($post_id)) {
            return;
        }

        // Lower than WC 2.7
        if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
            $product = get_product($post_id);

            if ($product->product_type == 'variable') {
                $products_varis = $this->iex_product_construct($product->id);
                if (count($products_varis) > 0) {
                    foreach ($products_varis as $products_vari) {
                        $products[] = $products_vari;
                    }
                }
            } else {
                $products[] = $this->iex_product_construct($product->id);
            }
        } else { // WC 2.7 and above
            $product = wc_get_product($post_id);
            if ($product->get_type() == 'variable') {
                $products_varis = $this->iex_product_construct($product->get_id());
                if (count($products_varis) > 0) {
                    foreach ($products_varis as $products_vari) {
                        $products[] = $products_vari;
                    }
                }
            } else {
                $products[] = $this->iex_product_construct($product->get_id());
            }
        }

        if (count($products) > 0 && !empty($products[0]) ) {
            $this->api->addTransfer(IEX_PRODUCTS, $products);
        }
    }

    /********************************
     *      Category transfer       *
     ********************************/

    function iex_term_edit( $term_id, $taxonomy ) {
        // Load term_id content
        $category = get_term( $term_id, $taxonomy );

        // Transfer to IEX
        if ($category->term_id && $category->taxonomy == 'product_cat') {
            // Add version and site url to category data
            $category->versions = $this->get_versions();
            $category->shop_url = get_option('siteurl');

            $this->api->addTransfer(IEX_CATEGORIES, $category);
            $this->api->doTransfer(true);
        }
    }

    function iex_term_create( $term_id, $tt_id, $taxonomy ) {
        if ( 'product_cat' != $taxonomy && ! taxonomy_is_product_attribute( $taxonomy ) ) {
            return;
        }

        // Load term_id content
        $category = get_term( $term_id, $taxonomy );

        // Transfer to IEX
        if ($category->term_id && $category->taxonomy == 'product_cat') {
            // Add version and site url to category data
            $category->versions = $this->get_versions();
            $category->shop_url = get_option('siteurl');

            $this->api->addTransfer(IEX_CATEGORIES, $category);
            $this->api->doTransfer(true);
        }
    }

    /********************************
     *         Order Checkout       *
     ********************************/

    function order_create_checkout($order_id) {
        global $wpdb, $woocommerce;

        $order = new WC_Order($order_id);

        if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
            $payment_method = $order->__get('payment_method');
        } else {
            $payment_method = $order->get_payment_method();
        }

        $allowed_methods = array('bacs', 'cod', 'cheque');

        if (!in_array($payment_method, $allowed_methods)) {
            return false;
        }

        $this->order_create($order_id);
    }

    /********************************
     *        Order Create          *
     ********************************/

    function order_create($order_id) {
        global $wpdb, $woocommerce;

        $post = get_post($order_id);

        // WooCommerce product?
        if ($post->post_type != 'shop_order') {
            return;
        }

        // Prevent autosaved and revisions to be transferred.
        if(wp_is_post_revision($order_id) || wp_is_post_autosave($order_id)) {
            return;
        }

        // Skip auto drafts
        if ($post->post_status == 'auto-draft') return;

        $the_order = $this->iex_order_construct($order_id);

        $this->debug['order'][] = $the_order;

        $this->api->addTransfer(IEX_ORDERS, $the_order);

        // Let's transfer the stuff
        $this->debug['order_transfer_return'] = $this->api->doTransfer(true);

        // Let's reset the transfer to make sure it isn't sent again
        $this->api->transfers = array();

        return true;
    }

    /********************************
     *        Order Contruct        *
     ********************************/

    function iex_order_construct($order_id) {
        global $wpdb;

        $woocommerce_price_num_decimals = get_option('woocommerce_price_num_decimals');

        if(!$woocommerce_price_num_decimals || $woocommerce_price_num_decimals < 2){
            $woocommerce_price_num_decimals = 2;
        }

        $order = new WC_Order($order_id);
        $orderlines = $order->get_items();

        $_tax = new WC_Tax(); //looking for appropriate vat for specific product

        $price_incl_tax = get_option('woocommerce_prices_include_tax', 'yes');

        $_orderlines = array();

        foreach ($orderlines as $orderline_key => $orderline) {
            if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
                $_product = get_product($orderline['product_id']);

                $orderline['line_tax'] = str_replace(',','.',$orderline['line_tax']);
                $orderline['line_total'] = str_replace(',','.',$orderline['line_total']);

                if($orderline['line_tax'] > 0 && $orderline['line_total'] > 0){
                    $orderline['taxrate'] = round( number_format(($orderline['line_tax'] / $orderline['line_total']) * 100, $woocommerce_price_num_decimals, '.', '') );
                    $orderline['taxable'] = true;
                }else{
                    $orderline['taxrate'] = (float) 0;
                    $orderline['taxable'] = false;
                }
                $orderline['sku'] = (!is_array($_product) && !is_object($_product) ? '' : $_product->get_sku()); // Set empty sku if product has been deleted!

                // Check if it is a variation product and get that SKU
                if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
                    $variation_id = $orderline['variation_id'];
                } else {
                    $variation_id = $orderline->get_variation_id();
                }

                if ($variation_id) {
                    $result = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_sku' AND post_id = '" . $variation_id . "'");
                    if ($result[0]->meta_value) {
                        $orderline['sku'] = $result[0]->meta_value;
                    }
                }

                $orderline['line_subtotal'] = str_replace(',','.',$orderline['line_subtotal']);
                $orderline['line_subtotal'] = (float)number_format((float)$orderline['line_subtotal'], $woocommerce_price_num_decimals, '.', '');

                $orderline['line_total'] = str_replace(',','.',$orderline['line_total']);
                $orderline['line_total'] = (float)number_format((float)$orderline['line_total'], $woocommerce_price_num_decimals, '.', '');

                $orderline['line_subtotal_tax'] = str_replace(',','.',$orderline['line_subtotal_tax']);
                $orderline['line_subtotal_tax'] = (float)number_format((float)$orderline['line_subtotal_tax'], $woocommerce_price_num_decimals, '.', '');

                $orderline['line_discount_price'] = $orderline['line_subtotal'] - $orderline['line_total'];
                $orderline['line_discount'] = ($orderline['line_discount_price'] > 0 ? ($orderline['line_discount_price'] / $orderline['line_subtotal']) * 100 : 0);

                $orderline['unit_price'] = (float) number_format((float)str_replace(',','.',$orderline['line_subtotal'] / $orderline['qty']), $woocommerce_price_num_decimals, '.', '');
                $orderline['unit_price_total'] = (float) number_format((float)str_replace(',','.',$orderline['line_total'] / $orderline['qty']), $woocommerce_price_num_decimals, '.', '');


                $orderline['stock_total'] = (float) number_format((float)str_replace(',','.',get_post_meta($orderline['product_id'], '_stock', true)), $woocommerce_price_num_decimals, '.', '');

                unset($orderline['line_tax_data']);
                unset($orderline['item_meta']);
                unset($orderline['item_meta_array']);

                // Make sure all numbers are in the correct format and using the correct number of decimals
                $orderline['line_tax'] = str_replace(',','.',$orderline['line_tax']);
                $orderline['line_tax'] = (float)number_format((float)$orderline['line_tax'], $woocommerce_price_num_decimals, '.', '');

                $orderline['line_discount_price'] = str_replace(',','.',$orderline['line_discount_price']);
                $orderline['line_discount_price'] = (float)number_format((float)$orderline['line_discount_price'], $woocommerce_price_num_decimals, '.', '');

                $orderline['line_discount'] = str_replace(',','.',$orderline['line_discount']);
                $orderline['line_discount'] = (float)number_format((float)$orderline['line_discount'], $woocommerce_price_num_decimals, '.', '');

                $orderline['unit_tax'] = ($orderline['line_subtotal_tax'] > 0) ? (float) number_format((float)str_replace(',','.',$orderline['line_subtotal_tax'] / $orderline['qty']), $woocommerce_price_num_decimals, '.', '') : 0.00;
                $orderline['unit_price_incvat'] = $orderline['unit_price'] + $orderline['unit_tax'];

                $linedata = $orderline;

            } else {
                $prod_id = $orderline->get_product_id();
                $_product = wc_get_product($prod_id);

                $linedata = array();

                $linedata['line_tax'] = str_replace(',','.',$orderline->get_total_tax());
                $linedata['line_total'] = str_replace(',','.',$orderline->get_total());
                $linedata['name'] = $orderline->get_name();
                $linedata['qty'] = $orderline->get_quantity();
                $linedata['product_id'] = $orderline->get_product_id();
                if (is_object($_product)) {
                    $linedata['parent_sku'] = $_product->get_sku();
                }
                $linedata['tax_class'] = $orderline->get_tax_class();
                $linedata['tax_rate_id'] = '';
                $linedata['taxrate'] = 0;


                // Get tax rate id
                $orderline_data = $orderline->get_data();
                $line_taxes = $orderline_data['taxes'];

                foreach ($line_taxes['total'] as $tax_key => $line_tax) {
                    $linedata['tax_rate_id'] = $tax_key;
                }

                // Get tax rate
                if ($linedata['tax_rate_id']) {
                    $tax_data = $_tax->_get_tax_rate($linedata['tax_rate_id']);
                    $linedata['tax_rate_info'] = $tax_data;
                    $linedata['taxrate'] = (float) number_format($tax_data['tax_rate'], 2, '.', '');
                }

                // Add orderline metadata
                if ($orderline->get_formatted_meta_data()) {
                    foreach ($orderline->get_formatted_meta_data() as $meta_data) {
                        $linedata['name'] .= "\n" . $meta_data->display_key . ": " . str_replace('<br>',"\n",$meta_data->value);
                    }
                }

                if($linedata['line_tax'] > 0 && $linedata['line_total'] > 0){
                    //$linedata['taxrate'] = round( number_format(($linedata['line_tax'] / $linedata['line_total']) * 100, $woocommerce_price_num_decimals, '.', '') );
                    $linedata['taxable'] = true; // TODO Check product data
                } else {
                    $linedata['taxrate'] = (float) 0;
                    $linedata['taxable'] = false;
                }
                $linedata['sku'] = (!is_array($_product) && !is_object($_product) ? '' : $_product->get_sku()); // Set empty sku if product has been deleted!

                // Check if it is a variation product and get that SKU
                if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
                    $variation_id = $orderline['variation_id'];
                } else {
                    $variation_id = $orderline->get_variation_id();
                }

                if ($variation_id) {
                    $result = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_sku' AND post_id = '" . $variation_id . "'");
                    if ($result[0]->meta_value) {
                        $linedata['sku'] = $result[0]->meta_value;
                    }
                    $linedata['variation_id'] = $variation_id;
                }

                $linedata['line_subtotal'] = str_replace(',','.',$orderline->get_subtotal());
                $linedata['line_subtotal'] = (float)number_format((float)$orderline->get_subtotal(), $woocommerce_price_num_decimals, '.', '');

                $linedata['line_total'] = str_replace(',','.',$orderline->get_total());
                $linedata['line_total'] = (float)number_format((float)$orderline->get_total(), $woocommerce_price_num_decimals, '.', '');

                $linedata['line_subtotal_tax'] = str_replace(',','.',$orderline->get_subtotal_tax());
                $linedata['line_subtotal_tax'] = (float)number_format((float)$orderline->get_subtotal_tax(), $woocommerce_price_num_decimals, '.', '');

                $linedata['line_discount_price'] = $linedata['line_subtotal'] - $linedata['line_total'];
                $linedata['line_discount'] = ($linedata['line_discount_price'] > 0 ? ($linedata['line_discount_price'] / $linedata['line_subtotal']) * 100 : 0);

                $linedata['unit_price'] = (float) number_format((float)str_replace(',','.',$linedata['line_subtotal'] / $linedata['qty']), $woocommerce_price_num_decimals, '.', '');
                $linedata['unit_price_total'] = (float) number_format((float)str_replace(',','.',$linedata['line_total'] / $linedata['qty']), $woocommerce_price_num_decimals, '.', '');


                $linedata['stock_total'] = (float) number_format((float)str_replace(',','.',get_post_meta($orderline->get_product_id(), '_stock', true)), $woocommerce_price_num_decimals, '.', '');

                // Make sure all numbers are in the correct format and using the correct number of decimals
                $linedata['line_tax'] = str_replace(',','.',$linedata['line_tax']);
                $linedata['line_tax'] = (float)number_format((float)$linedata['line_tax'], $woocommerce_price_num_decimals, '.', '');

                $linedata['line_discount_price'] = str_replace(',','.',$linedata['line_discount_price']);
                $linedata['line_discount_price'] = (float)number_format((float)$linedata['line_discount_price'], $woocommerce_price_num_decimals, '.', '');

                $linedata['line_discount'] = str_replace(',','.',$linedata['line_discount']);
                $linedata['line_discount'] = (float)number_format((float)$linedata['line_discount'], $woocommerce_price_num_decimals, '.', '');

                $linedata['unit_tax'] = ($linedata['line_subtotal_tax'] > 0) ? (float) number_format((float)str_replace(',','.',$linedata['line_subtotal_tax'] / $linedata['qty']), $woocommerce_price_num_decimals, '.', '') : 0.00;
                $linedata['unit_price_incvat'] = $linedata['unit_price'] + $linedata['unit_tax'];

                // Add meta to the orderline
                if ($variation_id) {
                    $line_post_meta = get_post_meta($variation_id, '', true);
                } else {
                    $line_post_meta = get_post_meta($linedata['product_id'], '', true);
                }

                $linedata['meta'] = array();

                if (!empty($line_post_meta) && is_array($line_post_meta)) {
                    foreach ($line_post_meta as $key => $meta_data) {
                        if (!empty($meta_data[0])) {
                            $linedata['meta'][$key] = $meta_data[0];
                        }
                    }
                }

                $linedata['shop_url'] = get_option('siteurl');
            }

            $_orderlines[$orderline_key] = $linedata;
        }

        $the_order = new stdClass();

        $the_order->orderlines = $_orderlines;

        $the_order->discount = $order->get_total_discount();

        $tax_totals = $order->get_tax_totals();

        $rate = '';

        foreach ($tax_totals as $taxes) {
            $rate = WC_Tax::get_rate_percent($taxes->rate_id);
            $rate = str_replace('%', '', $rate);
        }

        $the_order->vatPct = $rate;

        // Set order ean, requisition and reference.
        $the_order->order_ean = '';
        $the_order->order_ean_requisition_number = '';
        $the_order->order_ean_reference = '';

        if (get_post_meta($order_id, 'EAN-number', true)) {
            $the_order->order_ean = get_post_meta($order_id, 'EAN-number', true);
        }

        if (get_post_meta($order_id, 'ean_requisition_number', true)) {
            $the_order->order_ean_requisition_number = get_post_meta($order_id, 'ean_requisition_number', true);
        }

        if (get_post_meta($order_id, 'ean_reference_person', true)) {
            $the_order->order_ean_reference = get_post_meta($order_id, 'ean_reference_person', true);
        }

        if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
            $the_order->order = $order;
        } else {
            $p_post = get_post($order->get_id());
            $the_order->order = $p_post;
            $the_order->order->id = $the_order->order->ID;
        }

        $the_order->order->order_number = $order->get_order_number();

        $the_fees = $order->get_fees();

        $orderfees = array();

        foreach ($the_fees as $key => $the_fee) {
            if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
                unset($the_fees[$key]['item_meta']);
                unset($the_fees[$key]['item_meta_array']);
                unset($the_fees[$key]['line_tax_data']);
                $the_fee['taxrate'] = round( number_format((float)str_replace(',','.',($orderline['line_tax'] / $orderline['line_total']) * 100), $woocommerce_price_num_decimals, '.', '') );

                $orderfees[$key] = $the_fee;
            } else {
                $feeinc = false;
                if ($the_fee->get_totaL_tax() > 0) {
                    $feeinc = true;
                }
                $orderfees[$key] = array(
                    'name' => $the_fee->get_name(),
                    'type' => 'fee',
                    'tax_class' => $the_fee->get_tax_class(),
                    'line_total' => $the_fee->get_total(),
                    'line_tax' => $the_fee->get_total_tax(),
                    'line_subtotal' => $the_fee->get_total(),
                    'line_subtotal_tax' => $the_fee->get_total_tax(),
                    'taxrate' => round( number_format((float)str_replace(',','.',($the_fee->get_total_tax() / $the_fee->get_total_tax()) * 100), $woocommerce_price_num_decimals, '.', '') ),
                    'line_total_inc_vat' => $the_fee->get_total() + $the_fee->get_total_tax(),
                    'feeinclvat' => $feeinc,
                );
            }
        }

        $the_order->order_fees = $orderfees;
        $the_order->payment_fee = array();

        if (is_array($the_order->order_fees) || is_object($the_order->order_fees)) {
            foreach ($the_order->order_fees as $fee_key => $fee) {
                $the_order->payment_fee = $fee;
            }
            if (is_array($the_order->payment_fee) && !empty($the_order->payment_fee)) {
                $the_order->payment_fee['line_total'] = str_replace(',', '.', $the_order->payment_fee['line_total']);
                $the_order->payment_fee['line_total_inc_vat'] = $the_order->payment_fee['line_total'] + $the_order->payment_fee['line_tax'];
                $the_order->payment_fee['feeinclvat'] = (isset($the_order->payment_fee['line_tax']) && $the_order->payment_fee['line_tax'] > 0 ? true : false);
            }
        }

        if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
            $the_order->order->order_subtotal = (float)number_format((float)str_replace(',','.',$order->order_total), $woocommerce_price_num_decimals, '.', '');
            $the_order->order->order_tax = (float)number_format((float)str_replace(',','.',$order->order_tax), $woocommerce_price_num_decimals, '.', '');
            $the_order->order->order_total = (float)number_format((float)str_replace(',','.',$order->order_total), $woocommerce_price_num_decimals, '.', '');

            //Make sure that order_date is in datetime format
            $the_order->order->order_date = date('Y-m-d\TH:i:s', strtotime($the_order->order->order_date));
            $the_order->order->modified_date = date('Y-m-d\TH:i:s', strtotime($the_order->order->modified_date));
            $the_order->order->completed_date = date('Y-m-d\TH:i:s', strtotime($order->completed_date));
            $the_order->currency = $order->get_order_currency();
        } else {
            $the_order->order->order_subtotal = (float)number_format((float)str_replace(',','.',$order->get_subtotal()), $woocommerce_price_num_decimals, '.', '');
            $the_order->order->order_tax = (float)number_format((float)str_replace(',','.',$order->get_cart_tax()), $woocommerce_price_num_decimals, '.', '');
            $the_order->order->order_total = (float)number_format((float)str_replace(',','.',$order->get_total()), $woocommerce_price_num_decimals, '.', '');

            //Make sure that order_date is in datetime format
            $the_order->order->order_date = date('Y-m-d\TH:i:s', strtotime($order->get_date_created()));
            $the_order->order->modified_date = date('Y-m-d\TH:i:s', strtotime($order->get_date_modified()));
            $the_order->currency = $order->get_currency();
        }

        $order_shipping_methods = $order->get_shipping_methods();
        $shipments = array();
        $shipping_taxrate = 0;
        $shipping_id = null;
        foreach ($order_shipping_methods as $ordershipping_key => $ordershippingmethods) {
            if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
                $ordershipping_taxes = maybe_unserialize($ordershippingmethods['taxes']);
                if (isset($ordershippingmethods['cost']) && $ordershippingmethods['cost'] > 0) {
                    reset($ordershipping_taxes);
                    $key = key($ordershipping_taxes);
                    if(isset($ordershipping_taxes[$key])){
                        $ordershippingmethods['taxrate'] =  round( number_format((float)str_replace(',','.',(($ordershipping_taxes[$key] / $ordershippingmethods['cost']) * 100)), $woocommerce_price_num_decimals, '.', '') );
                    }else{
                        $ordershippingmethods['taxrate'] = 0;
                    }
                } else {
                    $ordershippingmethods['taxrate'] = 0;
                }
                $ordershippingmethods['cost'] = (float)number_format((float)str_replace(',','.',$ordershippingmethods['cost']), $woocommerce_price_num_decimals, '.', '');
                unset($ordershippingmethods['line_tax_data']);
                unset($ordershippingmethods['item_meta']);
                unset($ordershippingmethods['taxes']);
                unset($ordershippingmethods['item_meta_array']);
                $shipments[$ordershipping_key] = $ordershippingmethods;
                $shipping_id = $ordershippingmethods['method_id'];
                $shipping_taxrate = $ordershippingmethods['taxrate'];
            } else {

                $shipping_method = array();
                $shipping_method['cost'] = $ordershippingmethods->get_total();
                $shipping_method['total'] = $ordershippingmethods->get_total();
                $shipping_method['taxrate'] = 0;
                $shipping_method['tax'] = $ordershippingmethods->get_total_tax();
                $shipping_method['method'] = $ordershippingmethods->get_method_title();
                $shipping_method['method_title'] = $ordershippingmethods->get_method_title();
                $shipping_method['method_id'] = $ordershippingmethods->get_method_id();

                if ($shipping_method['tax'] > 0) {
                    $shipping_method['taxrate'] = round( number_format((float)str_replace(',','.',(($shipping_method['tax'] / $shipping_method['cost']) * 100)), $woocommerce_price_num_decimals, '.', '') );
                    $shipping_taxrate = $shipping_method['taxrate'];
                }

                $shipping_method['cost'] = (float)number_format((float)str_replace(',','.',$shipping_method['cost']), $woocommerce_price_num_decimals, '.', '');

                $shipments[$ordershipping_key] = $shipping_method;
                $shipping_id = $shipping_method['method_id'];
            }
        }

        $the_order->shipping_lines = $shipments;

        if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
            $the_order->payment_method = $order->__get('payment_method');
        } else {
            $the_order->payment_method = $order->get_payment_method();
        }

        if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
            if($order->billing_address_2){
                $order->billing_address_1 = $order->billing_address_1."\n".$order->billing_address_2;
            }

            $customer_data = array(
                'customer_id' => $order->user_id,
                'first_name' => $order->billing_first_name,
                'last_name' => $order->billing_last_name,
                'fullname' => ($order->billing_last_name ? $order->billing_first_name . ' ' . $order->billing_last_name : $order->billing_first_name),
                'company_name' => $order->billing_company,
                'user_email' => $order->billing_email,
                'address_1' => $order->billing_address_1,
                'address_2' => $order->billing_address_2,
                'city' => $order->billing_city,
                'zip' => $order->billing_postcode,
                'state' => $order->billing_state,
                'phone_1' => $order->billing_phone,
                'country' => $order->billing_country,
                'country_name' => WC()->countries->countries[ $order->billing_country ],
            );

            $the_order->customer = $customer_data;

            if($shipping_taxrate > 0){
                $shipping_price_inc_vat = round( $order->order_shipping * (1 + ($shipping_taxrate / 100)) );
            }else{
                $shipping_price_inc_vat = $order->order_shipping;
            }

            if($order->shipping_address_2){
                $order->shipping_address_1 = $order->shipping_address_1."\n".$order->shipping_address_2;
            }

            $shipping_data = array(
                'shipping_first_name' => $order->shipping_first_name,
                'shipping_last_name' => $order->shipping_last_name,
                'shipping_fullname' => ($order->shipping_last_name ? $order->shipping_first_name . ' ' . $order->shipping_last_name : $order->shipping_first_name),
                'shipping_company' => $order->shipping_company,
                'shipping_address_1' => $order->shipping_address_1,
                'shipping_address_2' => $order->shipping_address_2,
                'shipping_city' => $order->shipping_city,
                'shipping_zip' => $order->shipping_postcode,
                'shipping_state' => $order->shipping_state,
                'shipping_country' => $order->shipping_country,
                'shipping_country_name' => WC()->countries->countries[ $order->shipping_country ],
                'shipping_method' => $order->get_shipping_method(),
                'shipping_method_id' => $shipping_id,
                'shipping_price' => (float)number_format(($order->order_shipping ? str_replace(',','.',$order->order_shipping) : '0'), $woocommerce_price_num_decimals, '.', ''),
                'shipping_price_inc_vat' => (float)number_format((float)str_replace(',','.',$shipping_price_inc_vat), $woocommerce_price_num_decimals, '.', ''),
                'shipping_rate' => (isset($ordershippingmethods['taxrate']) ? $ordershippingmethods['taxrate'] : 0),
                'shipping_taxable' => ((isset($ordershippingmethods['taxrate']) && $ordershippingmethods['taxrate']) > 0 ? true : false),
            );

            $the_order->shipping_info = $shipping_data;
        } else {
            $billing_address = $order->get_billing_address_1();
            if($order->get_billing_address_2()){
                $billing_address = $order->get_billing_address_1()."\n".$order->get_billing_address_2();
            }

            $customer_data = array(
                'customer_id' => $order->get_user_id(),
                'first_name' => $order->get_billing_first_name(),
                'last_name' => $order->get_billing_last_name(),
                'fullname' => ($order->get_billing_last_name() ? $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() : $order->get_billing_first_name()),
                'company_name' => $order->get_billing_company(),
                'user_email' => $order->get_billing_email(),
                'address_1' => $billing_address,
                'address_2' => $order->get_billing_address_2(),
                'city' => $order->get_billing_city(),
                'zip' => $order->get_billing_postcode(),
                'state' => $order->get_billing_state(),
                'phone_1' => $order->get_billing_phone(),
                'country' => $order->get_billing_country(),
                'country_name' => WC()->countries->countries[ $order->get_billing_country() ],
            );

            $the_order->customer = $customer_data;

            if($shipping_taxrate > 0){
                $shipping_price_inc_vat = round( $order->get_shipping_total() * (1 + ($shipping_taxrate / 100)) );
            }else{
                $shipping_price_inc_vat = $order->get_shipping_total();
            }

            $shipping_address = $order->get_shipping_address_1();
            if($order->get_shipping_address_2()){
                $shipping_address = $order->get_shipping_address_1()."\n".$order->get_shipping_address_2();
            }

            $shipping_data = array(
                'shipping_first_name' => $order->get_shipping_first_name(),
                'shipping_last_name' => $order->get_shipping_last_name(),
                'shipping_fullname' => ($order->get_shipping_last_name() ? $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name() : $order->get_shipping_first_name()),
                'shipping_company' => $order->get_shipping_company(),
                'shipping_address_1' => $shipping_address,
                'shipping_address_2' => $order->get_shipping_address_2(),
                'shipping_city' => $order->get_shipping_city(),
                'shipping_zip' => $order->get_shipping_postcode(),
                'shipping_state' => $order->get_shipping_state(),
                'shipping_country' => $order->get_shipping_country(),
                'shipping_country_name' => WC()->countries->countries[ $order->get_shipping_country() ],
                'shipping_method' => $order->get_shipping_method(),
                'shipping_method_id' => $shipping_id,
                'shipping_price' => (float)number_format(($order->get_shipping_total() ? str_replace(',','.',$order->get_shipping_total()) : '0'), $woocommerce_price_num_decimals, '.', ''),
                'shipping_price_inc_vat' => (float)number_format((float)str_replace(',','.',$shipping_price_inc_vat), $woocommerce_price_num_decimals, '.', ''),
                'shipping_rate' => (isset($shipping_method['taxrate']) ? $shipping_method['taxrate'] : 0),
                'shipping_taxable' => ((isset($shipping_method['taxrate']) && $shipping_method['taxrate']) > 0 ? true : false),
            );

            $the_order->shipping_info = $shipping_data;
        }

        $used_coupons = $order->get_used_coupons();
        $the_order->used_coupons = $used_coupons;

        $the_order->versions = $this->get_versions();

        $the_order->shop_url = get_option('siteurl');

        // Add order meta data
        if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
            $order_post_meta = get_post_meta($order->id, '', true);
        } else {
            $order_post_meta = get_post_meta($order->get_id(), '', true);
        }

        $the_order->meta = new stdClass();

        if(is_array($order_post_meta) && !empty($order_post_meta)) {
            foreach ($order_post_meta as $key => $meta_data) {
                if (!empty($meta_data[0])) {
                    $the_order->meta->$key = $meta_data[0];
                }
            }
        }

        if ( version_compare( WC_VERSION, '2.7', '>' ) ) {
            $the_order->user_meta = new stdClass();

            if ($the_order->customer['customer_id']) {
                // Get customer meta data
                $user_meta = get_user_meta($the_order->customer['customer_id'], '', true);

                if(is_array($user_meta) && !empty($user_meta))
                    foreach ($user_meta as $key => $user_meta_data) {
                        if (!empty($user_meta_data[0])) {
                            $the_order->user_meta->$key = $user_meta_data[0];
                        }
                    }
            }
        }

        return apply_filters('iex_order', $the_order);
        exit;
    }

    /********************************
     *       Product Contruct       *
     ********************************/

    function iex_product_construct($post_id) {

        $woocommerce_price_num_decimals = get_option('woocommerce_price_num_decimals');

        if(!$woocommerce_price_num_decimals || $woocommerce_price_num_decimals < 2){
            $woocommerce_price_num_decimals = 2;
        }

        if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
            $_product = get_product($post_id);
            $product_type = $_product->product_type;
        } else {
            $_product = wc_get_product($post_id);
            if(!$_product) {
                // The post id is not a product?
                return false;
            }
            $product_type = $_product->get_type();
        }

        switch ($product_type) {
            case 'variable':
                $products = array();

                // Make parent product before the variations
                $parent_product = $_product;

                $p_post = get_post($_product->get_id());

                $parent_product->variation_name = '';
                $parent_product->composite_name = $p_post->post_title;
                $parent_product->post = $p_post;

                $parent_product->is_taxable = $_product->is_taxable();
                $parent_product->is_shipping_taxable = $_product->is_shipping_taxable();
                $_tax = new WC_Tax(); //looking for appropriate vat for specific product
                $tax_classes = $_product->get_tax_class();
                $tax_rates = $_tax->get_rates($tax_classes);
                $rates = array_shift($tax_rates);
                $parent_product->tax_classes = $tax_classes;
                $parent_product->tax_rates = $tax_rates;
                $parent_product->tax_rate = ($rates['rate'] ? $rates['rate'] : 0);
                $parent_product->weight = (float)number_format((float)str_replace(',','.',$_product->get_weight()), $woocommerce_price_num_decimals, '.', '');
                $parent_product->price = (float)number_format((float)str_replace(',','.',$_product->get_price()), $woocommerce_price_num_decimals, '.', '');

                if(isset($_POST['_regular_price'])) {
                    $parent_product->price = (float)number_format((float)str_replace(',','.',$_POST['_regular_price']), $woocommerce_price_num_decimals, '.', '');
                    $sale_price = $_POST['_sale_price'];
                    if ($sale_price > 0) {
                        $parent_product->price = (float)number_format((float)str_replace(',','.',$sale_price), $woocommerce_price_num_decimals, '.', '');
                    }
                }

                $tax_enabled = get_option('woocommerce_calc_taxes');
                $prices_include_tax = get_option('woocommerce_prices_include_tax');

                // Remove tax from price if it exits
                $parent_product->price_exc_vat = $parent_product->price;
                if ($parent_product->is_taxable && $tax_enabled != 'no' && $parent_product->tax_rate > 0) {
                    if ($prices_include_tax != 'no') {
                        $parent_product->price_exc_vat = $parent_product->price / (1 + ($parent_product->tax_rate / 100));
                    } else {
                        $parent_product->price = $parent_product->price * (1 + ($parent_product->tax_rate / 100));
                    }
                }

                $parent_product->sku = $_product->get_sku();
                $parent_product->qty = $_product->get_stock_quantity();
                $parent_product->currency = get_option('woocommerce_currency');

                // Get product categories
                if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
                    $terms = get_the_terms( $_product->id, 'product_cat' );
                } else {
                    $terms = get_the_terms( $_product->get_id(), 'product_cat' );
                }

                // Loop through all product categories and attach them to the product
                if(is_array($terms) || is_object($terms)){
                    foreach ($terms as $term) {
                        $product_cat = $term;
                        $term_id = $term->term_id;
                        $termid = 'categoy_'.$term_id;
                        $parent_product->$termid = $product_cat;
                    }
                }

                $parent_product->has_variations = true;

                if(function_exists('wpml_get_language_information')){
                    $parent_product->wpml = wpml_get_language_information($p_post->ID);
                }

                $parent_product->versions = $this->get_versions();

                $parent_product->shop_url = get_option('siteurl');

                // Make sure all numbers are in the correct format and using the correct number of decimals
                $parent_product->tax_rate = (float)number_format((float)str_replace(',','.',$parent_product->tax_rate), $woocommerce_price_num_decimals, '.', '');
                $parent_product->price = (float)number_format((float)str_replace(',','.',$parent_product->price), $woocommerce_price_num_decimals, '.', '');
                $parent_product->price_exc_vat = (float)number_format((float)str_replace(',','.',$parent_product->price_exc_vat), $woocommerce_price_num_decimals, '.', '');

                // Add all product meta data
                $parent_product_post_meta = get_post_meta($_product->get_id(), '', true);

                if (!empty($parent_product_post_meta) && is_array($parent_product_post_meta)) {
                    foreach ($parent_product_post_meta as $key => $meta_data) {
                        if (!empty($meta_data[0])) {
                            if ($key == 'data') continue;
                            $parent_product->$key = $meta_data[0];
                        }
                    }
                }

                $products[] = $parent_product;

                // Get variation ids because $_product->get_available_variations() only returns enabled variations
                $args = array(
                    'post_parent' => $post_id,
                    'post_type'   => 'product_variation',
                    'orderby'     => 'menu_order',
                    'order'       => 'ASC',
                    'fields'      => 'ids',
                    'post_status' => 'all',
                    'numberposts' => -1
                );

                $available_variations = get_posts( $args );

                foreach ($available_variations as $key => $variant_id) {
                    $prod = array();

                    $_prod = new WC_Product_Variation($variant_id);
                    $variation_data = $_prod->get_variation_attributes();
                    $variation_detail = wc_get_formatted_variation($variation_data, true);

                    if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
                        $variation = get_product($variant_id);
                    } else {
                        $variation = wc_get_product($variant_id);
                    }

                    $real_name = implode(', ', $variation_data);

                    $prod['data'] = new stdClass();

                    // Add attributes label and values to product data
                    if(is_array($variation_data)){
                        foreach ($variation_data as $key => $v_data) {
                            $prod['data']->$key = $v_data;
                        }
                    }

                    $p_post = get_post($_prod->get_id());

                    $prod['data']->id = $variant_id;
                    $prod['data']->post = $p_post;
                    $prod['data']->product_type = 'variant';
                    $prod['data']->variation_name = $real_name;
                    $prod['data']->composite_name = $parent_product->composite_name . ' - ' . $real_name;

                    $prod['data']->product_type = 'variable';

                    $prod['data']->is_taxable = $_prod->is_taxable();

                    $prod['data']->is_shipping_taxable = $_prod->is_shipping_taxable();
                    $prod['data']->weight = (float)number_format((float)str_replace(',','.',$_prod->get_weight()), $woocommerce_price_num_decimals, '.', '');

                    $_tax = new WC_Tax(); //looking for appropriate vat for specific product
                    $tax_classes = $_prod->get_tax_class();
                    $tax_rates = $_tax->get_rates($tax_classes);
                    $prod['data']->tax_classes = $tax_classes;
                    $prod['data']->tax_rates = $tax_rates;
                    $rates = array_shift($tax_rates);
                    $prod['data']->tax_rate = ($rates['rate'] ? $rates['rate'] : 0);

                    $tax_enabled = get_option('woocommerce_calc_taxes');
                    $prices_include_tax = get_option('woocommerce_prices_include_tax');

                    $variable_product1 = new WC_Product_Variation($variant_id);
                    $regular_price = $variable_product1->get_regular_price();
                    $prod['data']->price = (float)number_format((float)str_replace(',','.',$regular_price), $woocommerce_price_num_decimals, '.', '');

                    // Remove tax from price if it exits
                    $prod['data']->price_exc_vat = $prod['data']->price;
                    if ($prod['data']->is_taxable && $tax_enabled != 'no' && $prod['data']->tax_rate > 0) {
                        if ($prices_include_tax != 'no') {
                            $prod['data']->price_exc_vat = $prod['data']->price / (1 + ($prod['data']->tax_rate / 100));
                        } else {
                            $prod['data']->price = $prod['data']->price * (1 + ($prod['data']->tax_rate / 100));
                        }
                    }

                    if ($_prod->get_sku() === $_product->get_sku()) {
                        // variant sku is same as parent sku.
                        $prod['data']->sku = '';
                    } else {
                        $prod['data']->sku = $_prod->get_sku();
                    }

                    $prod['data']->parent_sku = $_product->get_sku();
                    $prod['data']->manage_stock = $_prod->managing_stock();
                    $prod['data']->qty = $_prod->get_stock_quantity();
                    $prod['data']->currency = get_option('woocommerce_currency');

                    $terms = get_the_terms( $_product->get_id(), 'product_cat' );

                    if(is_array($terms) || is_object($terms)){
                        foreach ($terms as $term) {
                            $product_cat = $term;
                            $term_id = $term->term_id;
                            $termid = 'categoy_'.$term_id;
                            $prod['data']->$termid = $product_cat;
                        }
                    }

                    if(function_exists('wpml_get_language_information')){
                        $prod['data']->wpml = wpml_get_language_information($variant_id);
                    }

                    $prod['data']->versions = $this->get_versions();

                    $prod['data']->shop_url = get_option('siteurl');

                    // Make sure all numbers are in the correct format and using the correct number of decimals
                    $prod['data']->tax_rate = (float)number_format((float)str_replace(',','.',$prod['data']->tax_rate), $woocommerce_price_num_decimals, '.', '');
                    $prod['data']->price = (float)number_format((float)str_replace(',','.',$prod['data']->price), $woocommerce_price_num_decimals, '.', '');
                    $prod['data']->price_exc_vat = (float)number_format((float)str_replace(',','.',$prod['data']->price_exc_vat), $woocommerce_price_num_decimals, '.', '');

                    // Add all product meta data
                    $product_post_meta = get_post_meta($_prod->get_id(), '', true);

                    if (!empty($product_post_meta) && is_array($product_post_meta)) {
                        foreach ($product_post_meta as $key => $meta_data) {
                            if (!empty($meta_data[0])) {
                                if ($key == 'data') continue;
                                $prod['data']->$key = $meta_data[0];
                            }
                        }
                    }

                    // Add the product to the array
                    $products[] = $prod['data'];
                }

                // Lastly add our filters
                $products = apply_filters('iex_product_variant', $products);
                $products = apply_filters('iex_product', $products);
                return $products;

                break;

            default:
                $products = $_product;

                $p_post = get_post($products->get_id());
                $products->post = $p_post;
                $products->variation_name = '';
                $products->composite_name = $p_post->post_title;
                $products->product_type = $_product->get_type();

                $products->is_taxable = $_product->is_taxable();
                $products->is_shipping_taxable = $_product->is_shipping_taxable();
                $_tax = new WC_Tax(); //looking for appropriate vat for specific product
                $tax_classes = $_product->get_tax_class();
                $tax_rates = $_tax->get_rates($tax_classes);
                $rates = array_shift($tax_rates);
                $products->tax_classes = $tax_classes;
                $products->tax_rates = $tax_rates;
                $products->tax_rate = ($rates['rate'] ? $rates['rate'] : 0);
                $products->weight = (float)number_format((float)str_replace(',','.',$_product->get_weight()), $woocommerce_price_num_decimals, '.', '');
                $products->price = (float)number_format((float)str_replace(',','.',$_product->get_price()), $woocommerce_price_num_decimals, '.', '');

                if(isset($_POST['_regular_price'])) {
                    $products->price = (float)number_format((float)str_replace(',','.',$_POST['_regular_price']), $woocommerce_price_num_decimals, '.', '');
                    $sale_price = $_POST['_sale_price'];
                    if ($sale_price > 0) {
                        $products->price = (float)number_format((float)str_replace(',','.',$sale_price), $woocommerce_price_num_decimals, '.', '');
                    }
                }

                $tax_enabled = get_option('woocommerce_calc_taxes');
                $prices_include_tax = get_option('woocommerce_prices_include_tax');

                // Remove tax from price if it exits
                $products->price_exc_vat = $products->price;
                if ($products->is_taxable && $tax_enabled != 'no' && $products->tax_rate > 0) {
                    if ($prices_include_tax != 'no') {
                        $products->price_exc_vat = $products->price / (1 + ($products->tax_rate / 100));
                    } else {
                        $products->price = $products->price * (1 + ($products->tax_rate / 100));
                    }
                }

                $products->sku = $_product->get_sku();
                $products->qty = $_product->get_stock_quantity();
                $products->currency = get_option('woocommerce_currency');

                // Get product categories
                if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
                    $terms = get_the_terms( $_product->id, 'product_cat' );
                } else {
                    $terms = get_the_terms( $_product->get_id(), 'product_cat' );
                }

                // Loop through all product categories and attach them to the product
                if(is_array($terms) || is_object($terms)){
                    foreach ($terms as $term) {
                        $product_cat = $term;
                        $term_id = $term->term_id;
                        $termid = 'categoy_'.$term_id;
                        $products->$termid = $product_cat;
                    }
                }

                $products->has_variations = false;

                if(function_exists('wpml_get_language_information')) {
                    $products->wpml = wpml_get_language_information($p_post->ID);
                }

                $products->versions = $this->get_versions();

                $products->shop_url = get_option('siteurl');

                // Make sure all numbers are in the correct format and using the correct number of decimals
                $products->tax_rate = (float)number_format((float)str_replace(',','.',$products->tax_rate), $woocommerce_price_num_decimals, '.', '');
                $products->price = (float)number_format((float)str_replace(',','.',$products->price), $woocommerce_price_num_decimals, '.', '');
                $products->price_exc_vat = (float)number_format((float)str_replace(',','.',$products->price_exc_vat), $woocommerce_price_num_decimals, '.', '');

                // Add all product meta data
                $products_meta_data = get_post_meta($products->get_id(), '', true);

                if(is_array($products_meta_data) && !empty($products_meta_data)) {
                    foreach ($products_meta_data as $key => $meta_data) {
                        if (!empty($meta_data[0])) {
                            if ($key == 'data') continue;
                            $products->$key = $meta_data[0];
                        }
                    }
                }

                // Lastly add our filters
                $products = apply_filters('iex_product_simple', $products);
                $products = apply_filters('iex_product', $products);
                return $products;

                break;
        }
    }

    /********************************
     *         Get versions         *
     ********************************/

    function get_versions() {
        global $woocommerce;

        $blogversion = get_bloginfo('version');

        $versions['Wordpress'] = $blogversion;

        $wooverion = $woocommerce->version;

        $versions['WooCommerce'] = $wooverion;

        $versions['IEX2'] = $this->iex_plugin_current_version;

        return $versions;
    }

    /********************************
     * Make sure Query vars are set *
     ********************************/

    function iex_queryvars($query_vars) {
        if (isset($_GET['iex'])) {
            $query_vars[] = 'iex';
            $query_vars[] = 'token';
            $query_vars[] = 'type';
            $query_vars[] = 'action';
        }

        return $query_vars;
    }

    /********************************
     *      Check IEX request       *
     ********************************/

    function iex_request($query) {
        global $wpdb, $woocommerce;

        // Make sure we can talk to the shop
        // Save values for later reset if needed
        if(ini_get('error_reporting') != 0){
            $error_reporting = ini_get('error_reporting');
            ini_set('error_reporting', 0);
            error_reporting(0);
        }
        if(ini_get('display_errors') != 0){
            $display_errors = ini_get('display_errors');
            ini_set('display_errors', 0);
        }

        $api_key = get_option('iex_api_key');
        $api_key_2nd = get_option('iex_api_key_2nd');

        if (isset($query->query_vars['iex'])) {
            header('Content-Type: application/json');
            header('Access-Control-Allow-Origin: *');
            $key = $query->query_vars['token'];
            $type = $query->query_vars['type'];
            $action = $query->query_vars['action'];
            $postdata = file_get_contents("php://input");
            $postdata = json_decode($postdata);

            if ($key != $api_key && $key != $api_key_2nd) {
                echo json_encode(array('Message' => 'Wrong key!', 'Error' => 'ERR01'));
                exit();
            }

            switch ($type) {
                case 'getorders':

                    $post_states = (isset($_REQUEST['post_states']) ? $_REQUEST['post_states'] : 'wc-completed');
                    $post_per_page = (isset($_REQUEST['per_page']) ? $_REQUEST['per_page'] : 100);
                    $post_page = (isset($_REQUEST['post_page']) ? $_REQUEST['post_page'] : 1);

                    if ($this->woocommerce_version_check('2.2')) {
                        $args = array('post_type' => 'shop_order', 'posts_per_page' => $post_per_page, 'paged' => $post_page,'post_status' => array($post_states));
                    } else {

                        if ( version_compare( WC_VERSION, '2.5', '>' ) ) {
                            $args = array(
                                'post_type' => 'shop_order',
                                'post_status' => array($post_states),
                                'posts_per_page' => $post_per_page,
                                'paged' => $post_page,
                            );

                        } else {
                            // WC < 2.2
                            $args = array(
                                'post_type' => 'shop_order',
                                'post_status' => 'publish',
                                'posts_per_page' => $post_per_page,
                                'paged' => $post_page,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'shop_order_status',
                                        'field' => 'slug',
                                        'terms' => array($post_states),
                                    )
                                ));
                        }
                    }

                    if ($action == 'specific') {

                        $start_date = $_REQUEST['startdate'];
                        $end_date = $_REQUEST['enddate'];

                        if ($start_date) {
                            $args['date_query'] = array(
                                array(
                                    'after' => array(
                                        'year' => date('Y', strtotime($start_date)),
                                        'month' => date('n', strtotime($start_date)),
                                        'day' => date('j', strtotime($start_date)),
                                    ),
                                    'inclusive' => true,
                                ),
                            );
                            if($end_date){
                                $args['date_query'][0]['before'] = array(
                                    'year' => date('Y', strtotime($end_date)),
                                    'month' => date('n', strtotime($end_date)),
                                    'day' => date('j', strtotime($end_date)),
                                );
                            }
                        } else {
                            echo json_encode(array('Message' => 'Missing date argument', 'Error' => 'ERR02'));
                            exit;
                        }
                    } else if($action == 'yesterday'){
                        // Only get orders from yesterday!
                        $get_date = date('d-m-Y', strtotime('-1 day'));

                        $args['date_query'] = array(
                            array(
                                'after' => array(
                                    'year' => date('Y', strtotime($get_date)),
                                    'month' => date('n', strtotime($get_date)),
                                    'day' => date('j', strtotime($get_date)),
                                ),
                                'before' => array(
                                    'year' => date('Y', strtotime($get_date)),
                                    'month' => date('n', strtotime($get_date)),
                                    'day' => date('j', strtotime($get_date)),
                                ),
                                'inclusive' => true,
                            ),
                        );
                    } else {
                        // No date!
                    }

                    $wp_query = new WP_Query($args);

                    if(isset($_REQUEST['return']) && $_REQUEST['return'] == 'count'){
                        // Only return the count!
                        echo json_encode(array('count' => $wp_query->found_posts, 'pages' => $wp_query->max_num_pages));
                        break;
                    }

                    $orders = [];

                    while ($wp_query->have_posts()) {
                        $wp_query->the_post();
                        $order_id = $wp_query->post->ID;

                        $orders[] = $this->iex_order_construct($order_id);
                    }
                    if (!empty($orders)) {
                        echo json_encode($orders);
                    } else {
                        $out = array('Message' => 'No orders', 'Error' => 'ERR03');
                        echo json_encode($out);
                    }

                    break;

                case 'product':
                    if ($action == 'find') {
                        $sku = $postdata->data->sku;
                        if (!$sku){
                            $sku = $_REQUEST['data']['sku'];
                        }
                        if ($sku == '') {
                            echo json_encode(array('Message' => 'No SKU provided!', 'Error' => 'ERR04'));
                            break;
                        }

                        $results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE `meta_key` = '_sku' AND `meta_value` = '" . $sku . "'");

                        $products = array();

                        if($results){
                            foreach ($results as $result) {
                                $prods = $this->iex_product_construct($result->post_id);
                                if(!$prods) {
                                    // post is not a product, continue?
                                    continue;
                                }
                                if(is_array($prods)){
                                    foreach ($prods as $key => $prod) {
                                        $products[] = $prod;
                                    }
                                }else{
                                    $products[] = $prods;
                                }

                            }
                        } else {
                            echo json_encode(array('Message' => 'Could not find any products with sku: ' . $sku, 'Error' => 'ERR05'));
                            break;
                        }
                        //wp_reset_query();

                        echo json_encode($products);

                    } elseif ($action == 'update') {

                        $sku = $postdata->data->sku;

                        if (!$sku){
                            $sku = $_REQUEST['data']['sku'];
                        }

                        if ($sku == '') {
                            echo json_encode(array('Message' => 'No SKU provided!', 'Error' => 'ERR06'));
                            break;
                        }

                        $results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE `meta_key` = '_sku' AND `meta_value` = '" . $sku . "'");

                        if (!$results) {
                            echo json_encode(array('Message' => 'Could not find any products with sku: ' . $sku, 'Error' => 'ERR08'));
                            break;
                        }


                        foreach ($results as $result) {
                            $post = get_post($result->post_id);
                            $children = array();
                            $parentinfo = array();

                            if (!$post) {
                                continue;
                            }

                            if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
                                $productinfo = new WC_Product($post);
                                if(!$productinfo) {
                                    continue;
                                }
                                $stock_status = $productinfo->__get('stock');
                                $sku = $productinfo->__get('sku');
                                $available = $postdata->data->qty;
                                $p_post = get_post($result->post_id);
                            } else {
                                $productinfo = wc_get_product($result->post_id);
                                if(!$productinfo) {
                                    continue;
                                }
                                $stock_status = $productinfo->get_stock_quantity();
                                $sku = $productinfo->get_sku();
                                $available = $postdata->data->qty;
                                $p_post = get_post($result->post_id);
                                if ($p_post->post_parent) {
                                    $parentinfo = wc_get_product($p_post->post_parent);
                                    if(!$parentinfo) {
                                        $the_update[$result->post_id]['parent_error_message'] = 'could not find parent wc product';
                                        $the_update[$result->post_id]['parent_id'] = $p_post->post_parent;
                                        continue;
                                    }
                                    $children = $parentinfo->get_children();
                                }
                            }

                            // If new title is present, then update
                            if ($postdata->data->title != '') {
                                if ($p_post->post_parent) {
                                    $product_update = array('ID' => $p_post->post_parent, 'post_title' => $postdata->data->title);
                                    $returned = wp_update_post($product_update);
                                } else {
                                    $product_update = array('ID' => $result->post_id, 'post_title' => $postdata->data->title);
                                    // Set the new title
                                    $returned = wp_update_post($product_update);
                                }
                                if ($returned > 0) {
                                    $the_update[$result->post_id]['title'] = 'Title updated';
                                } else {
                                    $the_update[$result->post_id]['title'] = 'Could not update title';
                                }
                            }

                            // If new stock is present, then update
                            if ($postdata->data->stock != '') {
                                // Set new stock status
                                $stock = $postdata->data->stock;
                                // stock_mode: set, add, subtract
                                $mode = ($postdata->data->stock_mode ? $postdata->data->stock_mode : 'set');

                                $original_stock = get_post_meta($result->post_id, '_stock', true);

                                switch($mode) {
                                    case 'set':
                                        // $stock already set to the new value;
                                        break;
                                    case 'subtract':
                                        $stock = $original_stock - $stock;
                                        break;
                                    case 'add':
                                        $stock = $original_stock + $stock;
                                        break;
                                }

                                update_post_meta($result->post_id, '_stock', $stock);

                                if ($stock >= 1) {
                                    update_post_meta($result->post_id, '_stock_status', 'instock');
                                    if ($p_post->post_parent) {
                                        // Make sure parent product also have instock status
                                        update_post_meta($p_post->post_parent, '_stock_status', 'instock');

                                        // Stock fix for 3.0 and above
                                        if ( version_compare( WC_VERSION, '2.7', '>' ) ) {
                                            wp_remove_object_terms( $p_post->post_parent, array('outofstock'), 'product_visibility' );
                                        }

                                        // Fix transient issue
                                        if ( version_compare( WC_VERSION, '2.7', '>' ) ) {
                                            if (function_exists("wc_delete_product_transients")) {
                                                wc_delete_product_transients($p_post->post_parent);
                                            }
                                        }
                                    } else {
                                        // Stock fix for 3.0 and above for simple products
                                        if ( version_compare( WC_VERSION, '2.7', '>' ) ) {
                                            wp_remove_object_terms( $result->post_id, array('outofstock'), 'product_visibility' );
                                        }
                                    }
                                } else {
                                    // Check if allow backorder is enabled
                                    $allow_backorders = get_post_meta($result->post_id, '_backorders', true);

                                    if ($allow_backorders == 'no' || !$allow_backorders) {
                                        update_post_meta($result->post_id, '_stock_status', 'outofstock');
                                    }

                                    // Update stock status on parent if all other variants is out of stock
                                    if ($p_post->post_parent && count($children) >= 1) {
                                        // Check if any variants is on stock
                                        $stock_status = 'outofstock';
                                        foreach ($children as $child) {
                                            // Get stock status from each child
                                            $stock_count = get_post_meta($child, '_stock', true);
                                            if ($stock_count >= 1) {
                                                $stock_status = 'instock';
                                            }
                                        }
                                        // Update parent stock status
                                        update_post_meta($p_post->post_parent, '_stock_status', $stock_status);

                                        // Stock fix for 3.0 and above
                                        if ( version_compare( WC_VERSION, '2.7', '>' ) ) {
                                            if ($stock_status == 'instock') {
                                                wp_remove_object_terms( $p_post->post_parent, array('outofstock'), 'product_visibility' );
                                            } else {
                                                wp_set_object_terms( $p_post->post_parent, array('outofstock'), 'product_visibility', true);
                                            }
                                        }

                                        // Fix transient issue
                                        if ( version_compare( WC_VERSION, '2.7', '>' ) ) {
                                            if (function_exists("wc_delete_product_transients")) {
                                                wc_delete_product_transients($p_post->post_parent);
                                            }
                                        }
                                    } else {
                                        // Stock fix for 3.0 and above
                                        if ( version_compare( WC_VERSION, '2.7', '>' ) ) {
                                            wp_set_object_terms( $result->post_id, array('outofstock'), 'product_visibility', true);
                                        }
                                    }
                                }

                                $the_update[$result->post_id]['stock'] = 'Stock: ' . $mode . ' ' . $stock;
                            }

                            // Custom data for variant products
                            if (isset($postdata->data->custom)) {
                                foreach ($postdata->data->custom as $key => $custom_value) {
                                    update_post_meta($result->post_id, $key, $custom_value);
                                }
                            }

                            // Custom data for parent product
                            if (isset($postdata->data->custom_parent)) {
                                foreach ($postdata->data->custom_parent as $key => $custom_value) {
                                    update_post_meta($p_post->post_parent, $key, $custom_value);
                                }
                            }

                            // Set ean field if exists
                            if (isset($postdata->data->ean_field) && $postdata->data->ean) {
                                $meta = get_post_meta($result->post_id, $postdata->data->ean_field, true);
                                if ($meta) {
                                    $meta['gtin'] = $postdata->data->ean;
                                } else {
                                    $meta = array(
                                        'gtin' => $postdata->data->ean,
                                    );
                                }
                                update_post_meta($result->post_id, $postdata->data->ean_field, $meta);
                            }

                            if ($postdata->data->short_description != '') {
                                if ($p_post->post_parent) {
                                    $post_data = array(
                                        'ID' => $p_post->post_parent,
                                        'post_excerpt' => $postdata->data->short_description,
                                    );
                                } else {
                                    $post_data = array(
                                        'ID' => $result->post_id,
                                        'post_excerpt' => $postdata->data->short_description,
                                    );
                                }
                                wp_update_post($post_data);
                            }

                            // Set product category
                            if ($postdata->data->category != '') {
                                if ($p_post->post_parent) {
                                    wp_set_object_terms($p_post->post_parent, (int) $postdata->data->category, 'product_cat', true);
                                } else {
                                    wp_set_object_terms($result->post_id, (int) $postdata->data->category, 'product_cat', true);
                                }
                            }

                            // If new price is present, then update
                            if ($postdata->data->price != '') {
                                // Set new price
                                $price = $postdata->data->price;
                                //$returned = update_post_meta($result->post_id, '_price', $price);
                                $returned = update_post_meta($result->post_id, '_regular_price', $price);
                                if (!get_post_meta($result->post_id, '_sale_price', true)) { // TODO If sale price is expired. We do need to update _price.
                                    update_post_meta($result->post_id, '_price', $price);
                                }

                                // Load product and run update to trigger cascading changes/updates
                                if ( version_compare( WC_VERSION, '2.7', '>' ) ) {
                                    $productinfo = wc_get_product($result->post_id);
                                    //$productinfo = new WC_Product($result->post_id);

                                    // get product type, and update based on that.
                                    $product_type = $productinfo->get_type();
                                    switch ($product_type) {
                                        case 'variable':
                                            // product is a parent product, possibly with children.
                                            if($postdata->data->stock >= 1) {
                                                $productinfo->set_stock_status('instock');
                                                $productinfo->set_catalog_visibility('visible');
                                            }
                                            $productinfo->save();
                                            break;
                                        case 'variation':
                                            // product is a variant, update parent stock status as well.
                                            if($postdata->data->stock >= 1) {
                                                $productinfo->set_regular_price($price);
                                                $productinfo->set_stock_quantity($postdata->data->stock);
                                                $productinfo->set_stock_status('instock');
                                                // update stock status on parent product as well,
                                                $woo_parent_product = wc_get_product($productinfo->get_parent_id());
                                                $woo_parent_product->set_stock_status('instock');
                                                $woo_parent_product->set_catalog_visibility('visible');
                                                $woo_parent_product->save();

                                                // fix parent if type simple.
                                                if($woo_parent_product->get_type() == 'simple') {
                                                    wp_set_object_terms( $woo_parent_product->get_id(), 'variable', 'product_type' );
                                                    $the_update[$productinfo->get_id()]['Update parent (' . $woo_parent_product->get_id() . ') status'] = 'variable';
                                                }
                                            }
                                            $productinfo->set_status('publish'); // fixes trashed variants caused by the new WC_Product.
                                            $productinfo->save();
                                            break;
                                        case 'simple':
                                            // product is simple product.
                                            break;
                                    }
                                }

                                if ($returned) {
                                    $the_update[$result->post_id]['price'] = 'Price updated to ' . $price;
                                } else {
                                    $the_update[$result->post_id]['price'] = 'Price not updated, possibly the same';
                                }
                            }

                            if ($postdata->data->color_attr || $postdata->data->size_attr) {
                                // Check if product already got the attributes
                                $_product_attributes = get_post_meta($p_post->post_parent, '_product_attributes', true);
                                $modified = false;

                                if ($_product_attributes) {

                                    // Check if our attributes exists or add them
                                    if ($postdata->data->color) {
                                        if (!isset($_product_attributes[$postdata->data->color_attr])) {
                                            $_product_attributes[$postdata->data->color_attr] = array(
                                                'name' => $postdata->data->color_attr,
                                                'value' => '',
                                                'position' => 0,
                                                'is_visible' => 1,
                                                'is_variation' => 1,
                                                'is_taxonomy' => 1
                                            );

                                            $modified = true;
                                        }
                                    }

                                    if ($postdata->data->size) {
                                        if (!isset($_product_attributes[$postdata->data->color_attr])) {
                                            $_product_attributes[$postdata->data->size_attr] = array(
                                                'name' => $postdata->data->size_attr,
                                                'value' => '',
                                                'position' => 1,
                                                'is_visible' => 1,
                                                'is_variation' => 1,
                                                'is_taxonomy' => 1
                                            );

                                            $modified = true;
                                        }
                                    }

                                    if ($modified) {
                                        update_post_meta( $p_post->post_parent, '_product_attributes', $_product_attributes);
                                    }

                                } else { // We add the attributes to the main product
                                    $product_attributes = array();

                                    if ($postdata->data->color) {
                                        $product_attributes[$postdata->data->color_attr] = array(
                                            'name' => $postdata->data->color_attr,
                                            'value' => '',
                                            'position' => 0,
                                            'is_visible' => 1,
                                            'is_variation' => 1,
                                            'is_taxonomy' => 1
                                        );
                                    }

                                    if ($postdata->data->size) {
                                        $product_attributes[$postdata->data->size_attr] = array(
                                            'name' => $postdata->data->size_attr,
                                            'value' => '',
                                            'position' => 1,
                                            'is_visible' => 1,
                                            'is_variation' => 1,
                                            'is_taxonomy' => 1
                                        );
                                    }

                                    update_post_meta( $p_post->post_parent, '_product_attributes', $product_attributes);
                                }

                                wp_set_object_terms( $p_post->post_parent, $postdata->data->size, $postdata->data->size_attr, true);
                                wp_set_object_terms( $p_post->post_parent, $postdata->data->color, $postdata->data->color_attr, true);

                                if ($modified) { // Make sure we run it
                                    echo json_encode(array('parent_id' => $p_post->post_parent, 'run_again' => true));
                                    exit;
                                }
                            }

                            // Update size and color
                            if ($postdata->data->color_attr) {
                                $colors = wp_get_object_terms($p_post->post_parent, $postdata->data->color_attr);

                                if ($postdata->data->color) {
                                    $color_slug = '';
                                    foreach ($colors as $color) {
                                        if (strtolower($color->name) == strtolower($postdata->data->color)) {
                                            $color_slug = $color->slug;
                                        }
                                    }
                                    if ($color_slug) {
                                        $color_name = 'attribute_'.$postdata->data->color_attr;
                                        update_post_meta($result->post_id, $color_name, $color_slug);
                                    }
                                }
                            }

                            if ($postdata->data->size_attr) {
                                $sizes = wp_get_object_terms($p_post->post_parent, $postdata->data->size_attr);

                                if ($postdata->data->size) {
                                    $size_slug = '';
                                    foreach ($sizes as $size) {
                                        if (strtolower($size->name) == strtolower($postdata->data->size)) {
                                            $size_slug = $size->slug;
                                        }
                                    }
                                    if ($size_slug) {
                                        $size_name = 'attribute_'.$postdata->data->size_attr;
                                        update_post_meta($result->post_id, $size_name, $size_slug);
                                    }
                                }
                            }
                        }
                        echo json_encode($the_update);

                    } elseif ($action == 'create') {
                        $sku = $postdata->data->sku;
                        $type = $postdata->data->type;

                        // Set basic product data
                        $post = array(
                            'post_author' => 0,
                            'post_content' => '',
                            'post_status' => "draft",
                            'post_title' => $postdata->data->name,
                            'post_parent' => '',
                            'post_type' => "product",
                        );

                        if ($postdata->data->short_description) {
                            $post['post_excerpt'] = $postdata->data->short_description;
                        }

                        if ($type == 'simple') {
                            // Create product
                            $post_id = wp_insert_post( $post, $wp_error );

                            if ($post_id) {
                                wp_set_object_terms($post_id, 'simple', 'product_type');

                                $stock_status = 'outofstock';
                                if ($postdata->data->qty >= 1) {
                                    $stock_status = 'instock';
                                }

                                update_post_meta( $post_id, '_visibility', 'visible' );
                                update_post_meta( $post_id, '_stock_status', $stock_status);
                                update_post_meta( $post_id, 'total_sales', '0');
                                update_post_meta( $post_id, '_regular_price', $postdata->data->price );
                                update_post_meta( $post_id, '_sku', $postdata->data->sku);
                                update_post_meta( $post_id, '_product_attributes', array());
                                update_post_meta( $post_id, '_price', $postdata->data->price );
                                update_post_meta( $post_id, '_manage_stock', "yes" );
                                update_post_meta( $post_id, '_stock', $postdata->data->qty );

                                if ( version_compare( WC_VERSION, '2.7', '>' ) ) {
                                    if ($postdata->data->qty <= 0) {
                                        wp_set_object_terms( $post_id, array('outofstock'), 'product_visibility', true );
                                    }
                                }

                                // Set product category
                                if ($postdata->data->category != '') {
                                    wp_set_object_terms($post_id, (int) $postdata->data->category, 'product_cat', true);
                                }

                                // Custom data for product
                                if (isset($postdata->data->custom)) {
                                    foreach ($postdata->data->custom as $key => $custom_value) {
                                        update_post_meta($post_id, $key, $custom_value);
                                    }
                                }

                                // Set ean field if exists
                                if (isset($postdata->data->ean_field) && $postdata->data->ean) {
                                    $meta = get_post_meta($post_id, $postdata->data->ean_field, true);
                                    if ($meta) {
                                        $meta['gtin'] = $postdata->data->ean;
                                    } else {
                                        $meta = array(
                                            'gtin' => $postdata->data->ean,
                                        );
                                    }
                                    update_post_meta($post_id, $postdata->data->ean_field, $meta);
                                }

                                echo json_encode(array('Message' => 'Product '.$postdata->data->sku.' created'));
                                break;
                            } else {
                                echo json_encode(array('Message' => 'Could not create product', 'Error' => 'ERR17'));
                                break;
                            }

                        } elseif ($type == 'variation') {
                            // Check if parent exists or create this first then the variant
                            $post_id = NULL;
                            $args = array(
                                'post_type' => 'product',
                                'post_status' => array('publish','draft','pending'),
                                'meta_query' => array(
                                    array(
                                        'key' => '_sku',
                                        'value' => $postdata->data->parent_sku,
                                    )
                                )
                            );
                            $loop = new WP_Query($args);

                            while ($loop->have_posts()) : $loop->the_post();
                                global $product;
                                $post_id = $product->id;
                            endwhile;

                            wp_reset_query();

                            if (!$post_id) {
                                // Create parent
                                $post_id = wp_insert_post( $post, $wp_error );
                                wp_set_object_terms($post_id, 'variable', 'product_type');
                                update_post_meta( $post_id, '_visibility', 'visible' );
                                update_post_meta( $post_id, 'total_sales', '0');
                                update_post_meta( $post_id, '_sku', $postdata->data->parent_sku);
                                update_post_meta( $post_id, '_product_attributes', array());
                                update_post_meta( $post_id, '_manage_stock', "no" );
                                update_post_meta( $post_id, '_tax_status', 'taxable' );

                                $stock_status = 'outofstock';
                                if ($postdata->data->qty >= 1) {
                                    $stock_status = 'instock';
                                }

                                update_post_meta( $post_id, '_stock_status', $stock_status);

                                if ( version_compare( WC_VERSION, '2.7', '>' ) ) {
                                    if ($postdata->data->qty <= 0) {
                                        wp_set_object_terms( $post_id, array('outofstock'), 'product_visibility', true );
                                    }
                                }

                                // Set product category
                                if ($postdata->data->category != '') {
                                    wp_set_object_terms($post_id, (int) $postdata->data->category, 'product_cat', true);
                                }

                                $product_attributes = array();

                                if ($postdata->data->color) {
                                    $product_attributes[$postdata->data->color_attr] = array(
                                        'name' => $postdata->data->color_attr,
                                        'value' => '',
                                        'position' => 0,
                                        'is_visible' => 1,
                                        'is_variation' => 1,
                                        'is_taxonomy' => 1
                                    );
                                }

                                if ($postdata->data->size) {
                                    $product_attributes[$postdata->data->size_attr] = array(
                                        'name' => $postdata->data->size_attr,
                                        'value' => '',
                                        'position' => 1,
                                        'is_visible' => 1,
                                        'is_variation' => 1,
                                        'is_taxonomy' => 1
                                    );
                                }

                                // Custom data for parent product
                                if (isset($postdata->data->custom_parent)) {
                                    foreach ($postdata->data->custom_parent as $key => $custom_value) {
                                        update_post_meta($post_id, $key, $custom_value);
                                    }
                                }

                                // Set ean field if exists
                                if (isset($postdata->data->ean_field) && $postdata->data->ean) {
                                    $meta = get_post_meta($post_id, $postdata->data->ean_field, true);
                                    if ($meta) {
                                        $meta['gtin'] = $postdata->data->ean;
                                    } else {
                                        $meta = array(
                                            'gtin' => $postdata->data->ean,
                                        );
                                    }
                                    update_post_meta($post_id, $postdata->data->ean_field, $meta);
                                }

                                update_post_meta( $post_id, '_product_attributes', $product_attributes);

                                echo json_encode(array('parent_id' => $post_id, 'run_again' => true));
                                break;
                            }

                            if ($post_id) { // Create variation

                                // Check if variation types exists or add these
                                wp_set_object_terms( $post_id, $postdata->data->size, $postdata->data->size_attr, true);
                                wp_set_object_terms( $post_id, $postdata->data->color, $postdata->data->color_attr, true);

                                // Create variation
                                $variation = array(
                                    'post_title'=> '',
                                    'post_name' => 'product-' . $post_id . '-variation',
                                    'post_status' => 'publish',
                                    'post_parent' => $post_id,
                                    'post_type' => 'product_variation',
                                );

                                $variation_id = wp_insert_post( $variation );
                                update_post_meta($variation_id, 'post_title', 'Variation #' . $variation_id . ' of '. $post_id);

                                $colors = wp_get_object_terms($post_id, $postdata->data->color_attr);
                                $sizes = wp_get_object_terms($post_id, $postdata->data->size_attr);

                                if ($postdata->data->color) {
                                    wp_set_object_terms( $variation_id, $postdata->data->color, $postdata->data->color_attr );
                                    $color_slug = NULL;
                                    foreach ($colors as $color) {
                                        if (strtolower($color->name) == strtolower($postdata->data->color)) {
                                            $color_slug = $color->slug;
                                        }
                                    }
                                    $color_name = 'attribute_'.$postdata->data->color_attr;
                                    update_post_meta($variation_id, $color_name, $color_slug);
                                }

                                if ($postdata->data->size) {
                                    wp_set_object_terms( $variation_id, $postdata->data->size, $postdata->data->size_attr );
                                    $size_slug = NULL;
                                    foreach ($sizes as $size) {
                                        if (strtolower($size->name) == strtolower($postdata->data->size)) {
                                            $size_slug = $size->slug;
                                        }
                                    }
                                    $size_name = 'attribute_'.$postdata->data->size_attr;
                                    update_post_meta($variation_id, $size_name, $size_slug);
                                }

                                $stock_status = 'outofstock';
                                if ($postdata->data->qty >= 1) {
                                    $stock_status = 'instock';
                                    $p_post = get_post($variation_id);
                                    if($p_post->post_parent) {
                                        update_post_meta($p_post->post_parent, '_stock_status', $stock_status);

                                        if ( version_compare( WC_VERSION, '2.7', '>' ) ) {
                                            $productinfo = wc_get_product($variation_id);
                                            //$productinfo = new WC_Product($p_post->post_parent);
                                            $product_type = $productinfo->get_type();
                                            if($product_type == 'variation') {
                                                // update parent product too if stock changed:
                                                $woo_parent_product = wc_get_product($productinfo->get_parent_id());
                                                $woo_parent_product->set_stock_status('instock');
                                                $woo_parent_product->set_catalog_visibility('visible');
                                                $woo_parent_product->save();
                                            }
                                            $productinfo->save();
                                        }
                                    }
                                }

                                update_post_meta($variation_id, '_regular_price', $postdata->data->price);
                                update_post_meta($variation_id, '_stock_status', $stock_status);
                                update_post_meta($variation_id, '_manage_stock', 'yes');
                                update_post_meta($variation_id, '_backorders', 'no');
                                update_post_meta($variation_id, '_downloadable', 'no');
                                update_post_meta($variation_id, '_virtual', 'no');
                                update_post_meta($variation_id, '_thumbnail_id', 0);
                                update_post_meta($variation_id, '_sku', $postdata->data->sku);
                                update_post_meta($variation_id, '_price', $postdata->data->price);
                                update_post_meta($variation_id, '_stock', $postdata->data->qty);
                                update_post_meta($variation_id, '_tax_status', 'taxable');
                                update_post_meta($variation_id, '_tax_class', 'parent');
                                update_post_meta($variation_id, '_sold_individually', 'no');
                                update_post_meta($variation_id, '_default_attributes', array());
                                update_post_meta($variation_id, '_sale_price', '');
                                update_post_meta($variation_id, '_sale_price_dates_from', '');
                                update_post_meta($variation_id, '_sale_price_dates_to', '');
                                update_post_meta($variation_id, '_weight', '');
                                update_post_meta($variation_id, '_length', '');
                                update_post_meta($variation_id, '_width', '');
                                update_post_meta($variation_id, '_height', '');
                                update_post_meta($variation_id, '_wc_average_rating', 0);
                                update_post_meta($variation_id, '_wc_rating_count', array());
                                update_post_meta($variation_id, '_wc_review_count', 0);

                                // Custom data for variant product
                                if (isset($postdata->data->custom)) {
                                    foreach ($postdata->data->custom as $key => $custom_value) {
                                        update_post_meta($variation_id, $key, $custom_value);
                                    }
                                }

                                // Set ean field if exists
                                if (isset($postdata->data->ean_field) && $postdata->data->ean) {
                                    $meta = get_post_meta($variation_id, $postdata->data->ean_field, true);
                                    if ($meta) {
                                        $meta['gtin'] = $postdata->data->ean;
                                    } else {
                                        $meta = array(
                                            'gtin' => $postdata->data->ean,
                                        );
                                    }
                                    update_post_meta($variation_id, $postdata->data->ean_field, $meta);
                                }

                                if ( version_compare( WC_VERSION, '2.7', '>' ) ) {
                                    if ($postdata->data->qty <= 0) {
                                        wp_set_object_terms( $variation_id, array('outofstock'), 'product_visibility', true );
                                    } else {
                                        wp_remove_object_terms( $post_id, array('outofstock'), 'product_visibility' );
                                    }

                                    if (function_exists("wc_delete_product_transients")) {
                                        wc_delete_product_transients($post_id);
                                    }
                                }
                            }

                            echo json_encode(array('variation_id' => $variation_id));
                            break;

                        }

                        echo json_encode(array('Message' => 'Something went wrong', 'Error' => 'ERR18'));

                        break;

                    } elseif ($action == 'getall') {

                        $post_stati = ($_REQUEST['post_states'] ? $_REQUEST['post_states'] : 'publish');
                        $post_per_page = ($_REQUEST['per_page'] ? $_REQUEST['per_page'] : 100);
                        $post_page = ($_REQUEST['post_page'] ? $_REQUEST['post_page'] : 1);

                        $args = array('post_type' => 'product', 'posts_per_page' => $post_per_page, 'paged' => $post_page,'post_status' => $post_stati);

                        $loop = new WP_Query($args);

                        $products = array();

                        while ($loop->have_posts()) : $loop->the_post();
                            global $product;

                            if ($product->product_type == 'variable') {

                                $products_varis = $this->iex_product_construct($product->id);
                                foreach ($products_varis as $products_vari) {
                                    $products[] = $products_vari;
                                }
                            } else {
                                $products[] = $this->iex_product_construct($product->id);
                            }
                        endwhile;

                        wp_reset_query();

                        echo json_encode($products);
                    } elseif($action == 'count') {

                        $post_stati = ($_REQUEST['post_states'] ? $_REQUEST['post_states'] : 'publish');
                        $post_per_page = ($_REQUEST['per_page'] ? $_REQUEST['per_page'] : 100);
                        $post_page = ($_REQUEST['post_page'] ? $_REQUEST['post_page'] : 1);

                        $args = array('post_type' => 'product', 'posts_per_page' => $post_per_page, 'paged' => $post_page,'post_status' => $post_stati);

                        $count = new WP_Query($args);

                        wp_reset_query();

                        echo json_encode(array('count' => $count->found_posts, 'pages' => $count->max_num_pages));
                    } else {
                        echo json_encode(array('Message' => 'Unsupported action', 'Error' => 'ERR09'));
                    }
                    break;

                case 'product_stock':
                    echo json_encode(array('Message' => 'Use type=product instead', 'Error' => 'ERR10'));
                    break;

                case 'order':

                    $orderid = $postdata->data->orderid;

                    if(!$orderid){
                        $orderid = $_REQUEST['data']['orderid'];
                    }

                    if ($orderid == '') {
                        echo json_encode(array('Message' => 'No orderid provided!', 'Error' => 'ERR11'));
                        break;
                    }

                    $the_order = get_post($orderid);

                    if (!$the_order) {
                        echo json_encode(array('Message' => 'Not an order!', 'Error' => 'ERR12'));
                        break;
                    }

                    if ($action == 'find') {

                        $the_order = $this->iex_order_construct($orderid);

                        echo json_encode($the_order);
                    } elseif ($action == 'update') {

                        $new_status = $postdata->data->status;
                        /*
                         * Can be one of the following:
                         *
                         * completed
                         * processing
                         * on-hold
                         * cancelled
                         *
                         */

                        $note = $postdata->data->note;

                        if ($note) {
                            $note = $note . '<br />';
                        }

                        if (!$new_status) {
                            echo json_encode(array('Message' => 'No new status provided!', 'Error' => 'ERR13'));
                            break;
                        }

                        // Add track and trace
                        if (isset($postdata->data->trackandtrace_field) && isset($postdata->data->trackandtrace)) {
                            if (is_array($postdata->data->trackandtrace)) {
                                update_post_meta($orderid, $postdata->data->trackandtrace_field, $postdata->data->trackandtrace[0]);
                            } else {
                                update_post_meta($orderid, $postdata->data->trackandtrace_field, $postdata->data->trackandtrace);
                            }
                        }

                        if (isset($postdata->data->custom)) {
                            foreach ($postdata->data->custom as $key => $custom_value) {
                                update_post_meta($orderid, $key, $custom_value);
                            }
                        }

                        $the_order = new WC_Order($orderid);

                        $the_order->update_status($new_status, $note);

                        //Reload order to see the change!
                        $the_order = new WC_Order($orderid);

                        if ($the_order->post_status == 'wc-' . $new_status) {
                            echo json_encode(array('Message' => 'Updated or already has status', 'Status' => 'ok' ));
                        } else {
                            echo json_encode(array('Message' => 'Could not set status', 'Error' => 'ERR14'));
                        }
                    } else {
                        echo json_encode(array('Message' => 'Not an action!', 'Error' => 'ERR15'));
                    }

                    break;

                case 'customer':
                    if ($action == 'find') {
                        $id = $postdata->data->id;
                        if (!$id){
                            $id = $_REQUEST['data']['id'];
                        }
                        if ($id == '') {
                            echo json_encode(array('Message' => 'No Id provided!', 'Error' => 'ERR04'));
                            break;
                        }

                        // Try to get user
                        $userdata = get_userdata(8);

                        if ($userdata) {
                            echo json_encode($userdata);
                        } else {
                            echo json_encode(array());
                        }

                        break;

                    } elseif ($action == 'create') {
                        $user_id = username_exists( $postdata->data->email );
                        if ( !$user_id and email_exists($postdata->data->email) == false ) {
                            $random_password = wp_generate_password( 12, false );
                            $user_id = wp_create_user( $postdata->data->email, $random_password, $postdata->data->email );

                            // Add billing information
                            $name = explode(' ', $postdata->data->name);
                            $firstname = $name[0];
                            unset($name[0]);
                            $lastname = implode(' ', $name);
                            update_user_meta($user_id, 'billing_first_name', $firstname);
                            update_user_meta($user_id, 'billing_last_name', $lastname);
                            update_user_meta($user_id, 'billing_address_1', $postdata->data->address);
                            update_user_meta($user_id, 'billing_city', $postdata->data->city);
                            update_user_meta($user_id, 'billing_postcode', $postdata->data->zip);
                            update_user_meta($user_id, 'billing_country', $postdata->data->country);
                            update_user_meta($user_id, 'billing_email', $postdata->data->email);
                            echo json_encode(array('Message' => 'Customer with id '. $user_id.' created'));
                        } else {
                            echo json_encode(array('Message' => 'User already exists', 'Error' => 'ERR11'));
                        }
                        break;
                    } elseif ($action == 'update') {
                        // Update user
                        $userdata = get_userdata($postdata->data->id);
                        if ($userdata) {
                            $name = explode(' ', $postdata->data->name);
                            $firstname = $name[0];
                            unset($name[0]);
                            $lastname = implode(' ', $name);
                            update_user_meta(8, 'billing_first_name', $firstname);
                            update_user_meta(8, 'billing_last_name', $lastname);
                            update_user_meta(8, 'billing_address_1', $postdata->data->address);
                            update_user_meta(8, 'billing_city', $postdata->data->city);
                            update_user_meta(8, 'billing_postcode', $postdata->data->zip);
                            update_user_meta(8, 'billing_country', $postdata->data->country);
                            update_user_meta(8, 'billing_email', $postdata->data->email);
                            echo json_encode(array('Message' => 'Customer with id '. $postdata->data->id.' updated'));

                        } else {
                            echo json_encode(array('Message' => 'Could not update user', 'Error', 'ERR12'));
                        }
                        break;
                    } else {
                        echo json_encode(array('Message' => 'Missing action!', 'Error' => 'ERR10'));
                        break;
                    }
                    break;

                case 'categories':
                    $categories = $terms = get_terms( array(
                        'taxonomy' => 'product_cat',
                        'hide_empty' => false,
                        'orderby' => 'term_id'
                    ) );

                    echo json_encode($categories);
                    break;

                case 'orderstates':
                    $wc_statuses = wc_get_order_statuses();
                    if ($this->woocommerce_version_check('2.2')) {
                        foreach ($wc_statuses as $key => $value) {
                            $order_states[$key] = $value;
                        }
                    } else {
                        foreach ($wc_statuses as $key => $value) {
                            $order_states[$key] = $value;
                        }
                    }
                    echo json_encode($order_states);
                    break;

                case 'poststates':
                    $post_states = get_post_stati(array(), 'object');

                    foreach ($post_states as $key => $post_state) {
                        if ($post_state->label_count['domain'] != 'woocommerce') {
                            $poststates[$post_state->name] = $post_state->label;
                        }
                    }

                    echo json_encode($poststates);
                    break;

                case 'paymentmethods':
                    $gateways = new WC_Payment_Gateways;
                    $payment_gateways = $gateways->get_available_payment_gateways();

                    foreach ($payment_gateways as $key => $payment_gateway) {
                        $_gateways[$payment_gateway->id] = $payment_gateway->title;
                    }

                    echo json_encode($_gateways);
                    break;

                case 'shippingmethods':
                    $shippingmethods = new WC_Shipping;
                    $shippingmethods->load_shipping_methods();
                    $shipping_methods = $shippingmethods->get_shipping_methods();

                    foreach ($shipping_methods as $key => $shipping_method) {
                        if($shipping_method->enabled == 'yes') {
                            if ( version_compare( WC_VERSION, '2.7', '<' ) ) {
                                $_shippings[$shipping_method->id] = $shipping_method->title;
                            } else {
                                $_shippings[$shipping_method->id] = $shipping_method->method_title;
                            }
                        }
                    }
                    echo json_encode($_shippings);
                    break;


                case 'languages':
                    $langs = '';
                    if(function_exists('icl_get_languages')){
                        $langs = icl_get_languages('skip_missing=0&orderby=KEY&order=DIR&link_empty_to=str');

                        foreach ($langs as $key => $value) {
                            $_langs->$key = $value['native_name'];
                        }
                    }

                    echo json_encode($_langs);
                    break;

                case 'versions':
                    $versions = $this->get_versions();

                    echo json_encode($versions);
                    break;

                case 'updateplugin':
                    header('Content-Type: text/html');
                    //set_site_transient( 'update_plugins', null );

                    include ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
                    include ABSPATH . 'wp-admin/includes/misc.php';
                    include ABSPATH . 'wp-admin/includes/file.php';

                    $title = __('Update Plugin');
                    $nonce = 'upgrade-plugin_' . $plugin;
                    $url = 'update.php?action=upgrade-plugin&plugin=' . urlencode( $plugin );
                    $plugin = 'iex-integration/iex.php';

                    $upgrader = new Plugin_Upgrader( new Plugin_Upgrader_Skin( compact('title', 'nonce', 'url', 'plugin') ) );
                    $upgrade_status = $upgrader->upgrade($plugin);

                    if( ! function_exists('activate_plugin') ) {
                        require_once ABSPATH . 'wp-admin/includes/plugin.php';
                    }
                    if( ! is_plugin_active( $plugin ) ) {
                        activate_plugin( $plugin );
                    }

                    break;

                // Missing something \\
                default:
                    echo json_encode(array('Message' => 'Missing something!', 'Error' => 'ERR16'));
                    break;
            }
            exit;
        }
    }

    /********************************
     *      Woo Version Check       *
     ********************************/

    function woocommerce_version_check($version = '2.2') {
        if (function_exists('is_woocommerce_active') && is_woocommerce_active()) {
            global $woocommerce;
            if (version_compare($woocommerce->version, $version, ">=")) {
                return true;
            }
        }
        return false;
    }

}

// Make sure plugins class is loaded
add_action('plugins_loaded', 'load_iex', 0);

function load_iex() {
    $iexintegration = new IEX_Integration();
}
