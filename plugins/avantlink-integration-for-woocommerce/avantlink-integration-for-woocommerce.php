<?php
/**
* Plugin Name: AvantLink Integration For WooCommerce
* Author: AvantLink
* Description: This allows WooCommerce users to easily integrate AvantLink into their site.
* Version: 1.0.11
*/

if (! defined( 'ABSPATH' ) ) {
    exit;
}

 //======================SET UP======================\\
//====================================================\\

function avantlinkwoo_menu () {
    //Generate Admin Page
    add_menu_page('AvantLink Integration', 'AvantLink Tracking', 'manage_options', 'avantlinkwoo_merchant_menu', 'avantlinkwoo_merchant_page', plugin_dir_url( __FILE__ ) . 'optimised.svg');

    //The AvantLink Icon on the Admin Dashboard is a bit offcentered so this fixes the problem.
    wp_enqueue_style('avantlinkwoo_styles', plugin_dir_url( __FILE__ ) . 'avplugin.css');
    
    add_option('avantlinkwoo_settings');
}

//Initialize Admin Page
add_action('admin_menu', 'avantlinkwoo_menu');
//Activate Custom Settings
add_action('admin_init', 'avantlinkwoo_custom_settings');

function avantlinkwoo_custom_settings () {
    register_setting('avantlinkwoo_settings', 'avantlinkwoo_merchant_id', array('type' => 'integer', 'description' => 'Merchant ID supplied by AvantLink', 'sanitize_callback' => 'avantlinkwoo_sanitize_merchant_id'));
    add_settings_section('avantlinkwoo_sidebar_options', '', 'avantlinkwoo_sidebar_options', 'avantlinkwoo_merchant_menu');
    add_settings_field('merchant_id', 'Merchant ID', 'avantlinkwoo_input_merchant_ID', 'avantlinkwoo_merchant_menu', 'avantlinkwoo_sidebar_options');
}

//Admin Page Forms
function avantlinkwoo_merchant_page () {
    $merchantId = get_option('avantlinkwoo_merchant_id');
    if ($merchantId) {
        $merchantIdDisplay = "<p>You must have an advertiser/merchant account with AvantLink to use this plugin. If you do not have an account, please
                              <a href='https://avantlink.com/signup'>complete this application</a></p>
                              <p>If you are currently integrating with AvantLink, enter your Merchant ID and click save to install your tracking.</p>";
    }
    else {
        $merchantIdDisplay = '';
        global $wp_version;
        if (version_compare($wp_version, '5.3', '>=')) {
            $notificationType = 'warning';
        }
        else {
            $notificationType = 'error';
        }
        add_settings_error('noInput', 'settings_updated', 'You must have a valid Merchant ID configured for AvantLink tracking to work.', $notificationType);
    }
    ?>

    <div>
        <h1>AvantLink Merchant Integration For WooCommerce</h1>

        <?php settings_errors(); ?>
       
        <br>

        <?php avantlink_escape_string($merchantIdDisplay); ?>

        <form method="POST" action="options.php">
            <?php   settings_fields('avantlinkwoo_settings');
                    do_settings_sections('avantlinkwoo_merchant_menu');
                    submit_button(); ?>
        </form>
    </div>

    <?php
}

//Setting Input and Display
function avantlinkwoo_input_merchant_ID () {
    $merchantId = get_option('avantlinkwoo_merchant_id');
    avantlink_escape_string("<input    type='text' 
                    name='avantlinkwoo_merchant_id' 
                    value='$merchantId' 
                    placeholder='Merchant ID'/>");
}

//Settings Steps
function avantlinkwoo_sidebar_options () {
    avantlink_escape_string('');
}

//Sanitize & Validate Merchant ID
function avantlinkwoo_sanitize_merchant_id ($input) {
    $output = sanitize_text_field($input);
    if (is_numeric($output) && $output > 10000) {
        return $output;
    }
    add_settings_error('badInput', 'settings_updated', 'Please enter a valid Merchant ID.', 'error');
    return false;
}

//Escaping html and string output for page
function avantlink_escape_string($input) {
    echo wp_kses(
        $input,
        array(
            'p' => array(),
            'a' => array(),
            'input' => array(
                'name' => array(),
                'value' => array(),
                'placeholder' => array()
            )
        )
    );
}

 //=====================SITE WIDE====================\\
//====================================================\\

// This is the site wide script
function avantlinkwoo_sitewide( ) {
    $merchantId = get_option( 'avantlinkwoo_merchant_id' );
    wp_register_script('avantlinkwoo_sitewideScript', plugin_dir_url( __FILE__ ) . '/sitewide.js', array(), '', true);
    wp_localize_script('avantlinkwoo_sitewideScript', 'merchant', array('id' => __( $merchantId, 'plugin-domain')));
    wp_enqueue_script('avantlinkwoo_sitewideScript');
}

// This will fire when the site loads up
add_action('wp_enqueue_scripts', 'avantlinkwoo_sitewide');

 //=====================TRACKING=====================\\
//====================================================\\

// This will be hooked below to wc_thank_you
function avantlinkwoo_tracking($order_id) {

    // Get Order Object
    $order = wc_get_order($order_id);

    if ($order) {
        // Logic for New Customer based on login
        if ( $order->get_user_id() > 0 ) {
            $avlNewCustomer = 'N';
        } else {
            $avlNewCustomer = 'Y';
        }
    }

    $merchantId = get_option('avantlinkwoo_merchant_id');

    // Avantmetrics Script
    wp_register_script('avantlinkwoo_orderarray', plugin_dir_url( __FILE__ ) . '/orderarray.js', array(), '', true);

    if ($order) {
        $items = [];
        foreach ($order->get_items() as $item) {
            if (!is_object($item)) {
                continue;
            }

            $product = $item->get_product();
            if (!is_object($product)) {
                continue;
            }

            if ($product->get_type() == 'variation') {
                /** @var WC_Product_Variation $product */
                $parent_data = $product->get_parent_data();
                $parent_sku = $parent_data['sku'];
                $variant_sku = $product->get_sku();
            }
            else {
                /** @var WC_Product_Simple $product */
                $parent_sku = $product->get_sku();
                $variant_sku = '';
            }
            $items[] = [
                'parent_sku' => $parent_sku,
                'variant_sku' => $variant_sku,
                'total' => $product->get_price(),
                'quantity' => $item->get_quantity()
            ];
        }

        wp_localize_script('avantlinkwoo_orderarray', 'order', array(
            'order_id' => $order->get_order_number(),
            'order_subtotal' => $order->get_subtotal() - $order->get_total_discount(),
            'order_country' => $order->billing_country,
            'order_state' => $order->billing_state,
            'order_currency' => $order->currency,
            'merchant_id' => $merchantId,
            'coupons' => $order->get_used_coupons(),
            'new_customer' => $avlNewCustomer,
            'order_items' => json_encode($items)
        ));
    } else {
        wp_localize_script('avantlinkwoo_orderarray', 'order', array('merchant_id' => __( $merchantId, 'plugin-domain' )));
    }
    wp_enqueue_script('avantlinkwoo_orderarray');
    wp_enqueue_script('avantlinkwoo_sitewideScript');
}

// Hook to wc_thank you function
add_action('woocommerce_thankyou', 'avantlinkwoo_tracking');

