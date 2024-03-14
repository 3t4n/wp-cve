<?php

/**
 * This class handles the admin interface for WooCommerce
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2020 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!class_exists('WC_iZettle_Integration_Product_Handler_Admin', false)) {

    class WC_iZettle_Integration_Product_Handler_Admin
    {
        public function __construct()
        {
            /**
             * WooCommerce actions and filters for the gui functions
             */

            add_action('woocommerce_product_data_panels', array($this, 'show_izettle_fields'), 10);
            add_action('woocommerce_product_after_variable_attributes', array($this, 'show_izettle_fields_variable'), 30, 3);
            add_action('woocommerce_admin_process_product_object', array($this, 'save_product'));
            add_action('woocommerce_admin_process_variation_object', array($this, 'save_product_variation'), 10, 2);
            add_filter('woocommerce_product_data_tabs', array($this, 'product_data_tab'), 50, 1);
            add_action('wp_ajax_izettle_generate_barcode', array($this, 'ajax_izettle_generate_barcode'));

        }

        public function show_izettle_fields()
        {

            global $post, $thepostid, $product_object;
            include 'views/html-product-data-inventory.php';

        }

        public function show_izettle_fields_variable($loop, $variation_data, $variation)
        {
            global $thepostid;
            if ($this->is_default_language($thepostid)) {
                include 'views/html-product-data-inventory-variable.php';
            }
        }

        public function save_product($product)
        {

            $current_barcode = isset($_POST['_izettle_barcode']) ? wc_clean(wp_unslash($_POST['_izettle_barcode'])) : '';

            $product->update_meta_data('_izettle_barcode', apply_filters('izettle_barcode_ean13_automatic', $current_barcode, $product->get_id()));
            $product->update_meta_data('_izettle_nosync', isset($_POST['_izettle_nosync']) ? wc_clean(wp_unslash($_POST['_izettle_nosync'])) : '');
            $product->update_meta_data('_izettle_cost_price', isset($_POST['_izettle_cost_price']) ? wc_clean(wp_unslash($_POST['_izettle_cost_price'])) : '');
            $product->update_meta_data('_izettle_special_price', isset($_POST['_izettle_special_price']) ? wc_clean(wp_unslash($_POST['_izettle_special_price'])) : '');
            $product->update_meta_data('_izettle_product_name', isset($_POST['_izettle_product_name']) ? wc_clean(wp_unslash($_POST['_izettle_product_name'])) : '');
            $product->update_meta_data('_zettle_product_cat_id', isset($_POST['_zettle_product_cat_id']) ? wc_clean(wp_unslash($_POST['_zettle_product_cat_id'])) : '');
            if ('yes' == get_option('zettle_enable_uuid_edit')) {
                $product->update_meta_data('woocommerce_izettle_product_uuid', isset($_POST['_zettle_product_uuid']) ? wc_clean(wp_unslash($_POST['_zettle_product_uuid'])) : '');
                $product->update_meta_data('woocommerce_izettle_variant_uuid', isset($_POST['_zettle_variant_uuid']) ? wc_clean(wp_unslash($_POST['_zettle_variant_uuid'])) : '');
            }

        }

        public function save_product_variation($variation, $i)
        {

            $current_barcode = isset($_POST["_izettle_barcode_{$i}"]) ? $_POST["_izettle_barcode_{$i}"] : '';

            $variation->update_meta_data('_izettle_barcode', apply_filters('izettle_barcode_ean13_automatic', $current_barcode, $variation->get_id()));
            $variation->update_meta_data('_izettle_nosync', isset($_POST["_izettle_nosync_{$i}"]) ? $_POST["_izettle_nosync_{$i}"] : '');
            $variation->update_meta_data('_izettle_special_price', isset($_POST["_izettle_price_{$i}"]) ? $_POST["_izettle_price_{$i}"] : '');
            $variation->update_meta_data('_izettle_cost_price', isset($_POST["_izettle_cost_price_{$i}"]) ? $_POST["_izettle_cost_price_{$i}"] : '');
            if ('yes' == get_option('zettle_enable_uuid_edit')) {
                $variation->update_meta_data('woocommerce_izettle_product_uuid', isset($_POST["_zettle_product_uuid_{$i}"]) ? $_POST["_zettle_product_uuid_{$i}"] : '');
                $variation->update_meta_data('woocommerce_izettle_variant_uuid', isset($_POST["_zettle_variant_uuid_{$i}"]) ? $_POST["_zettle_variant_uuid_{$i}"] : '');
            }

        }

        public function product_data_tab($tabs)
        {

            global $thepostid;

            if ($this->is_default_language($thepostid)) {
                $tabs['izettle'] = array(
                    'label' => __('Zettle', 'woo-izettle-integration'),
                    'target' => 'izettle_product_data',
                    'class' => array('show_if_simple', 'show_if_variable'),
                );
            }

            return $tabs;
        }

        public function ajax_izettle_generate_barcode()
        {
            if (!wp_verify_nonce($_POST['nonce'], 'ajax-izettle')) {
                wp_die();
            }

            $barcode = apply_filters('izettle_barcode_ean13_manual', null, $_POST['product_id']);

            wp_send_json($barcode);
        }

        private function is_default_language($product_id)
        {

            $language = WC_Zettle_Helper::product_language($product_id);
            $wpml_default_language = get_option('zettle_wpml_default_language', apply_filters('wpml_default_language', null));
            return $language == $wpml_default_language;

        }

    }

    new WC_iZettle_Integration_Product_Handler_Admin;
}
