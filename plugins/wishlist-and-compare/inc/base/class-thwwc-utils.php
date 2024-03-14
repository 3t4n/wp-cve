<?php
/**
 * The activation of the plugin.
 *
 * @package    Wishlist-and-compare
 * @subpackage Wishlist-and-compare/inc/base
 *
 * @link  https://themehigh.com
 * @since 1.0.0.0
 */

namespace THWWC\base;

use \THWWC\thpublic\THWWC_Public_Wishlist;
use \THWWC\thpublic\THWWC_Public_Settings;

if (!class_exists('THWWC_Utils')) :
    /**
     * Utils class
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Utils
    {
        const OPTION_KEY_GENERAL_SETTINGS = 'thwwac_general_settings';

        const OPTION_KEY_SHOP_PAGE_SETTINGS = 'thwwac_shop_page_settings';

        const OPTION_KEY_PRODUCT_PAGE_SETTINGS = 'thwwac_product_page_settings';

        const OPTION_KEY_WISHLIST_PAGE_SETTINGS = 'thwwac_wishlist_page_settings';

        const OPTION_KEY_COUNTER_SETTINGS = 'thwwac_wishlist_counter_settings';

        const OPTION_KEY_SOCIALMEDIA_SETTINGS = 'thwwac_socialmedia_settings';

        const OPTION_KEY_COMPARE_SETTINGS = 'thwwac_compare_settings';

        const OPTION_KEY_COMPARE_TABLE_SETTINGS = 'thwwac_compare_table_settings';


        public static function wwc_capability() {
            $allowed = array('manage_woocommerce', 'manage_options');
            $capability = apply_filters('thwwc_required_capability', 'manage_woocommerce');

            if(!in_array($capability, $allowed)){
                $capability = 'manage_woocommerce';
            }
            return $capability;
        }

        /**
         * Function to get key.
         *
         * @return string key
         */
        public static function get_key()
        {
            $thwwc_key = '#';
            if (is_user_logged_in() && (($user_id = get_current_user_id()) > 0)) {
                $thwwc_key = THWWC_Public_Wishlist::twoway_encrypt($user_id,'e');
            } elseif (isset($_COOKIE['thwwc_key'])) {
                $thwwc_key = sanitize_key($_COOKIE['thwwc_key']);
            }
            return $thwwc_key;
        }

         /**
         * Function to get select options text if wepo is active.
         *
         * @return string
         */
        public static function is_wepo_active()
        {
            $select_option_text['simple'] = 'Select options';
            $select_option_text['variable'] = 'Select options';
            $active_plugins = (array) get_option('active_plugins', array());
            if (in_array('woocommerce-extra-product-options-pro/woocommerce-extra-product-options-pro.php', $active_plugins)) {
                $wepo_option = get_option('thwepo_advanced_settings');
                $wepo_add_text = isset($wepo_option) ? (isset($wepo_option['add_to_cart_text_addon_simple']) ? $wepo_option['add_to_cart_text_addon_simple'] : '') : '';
                $select_option_text['simple'] = empty($wepo_add_text) ? 'Select options' : $wepo_add_text;
                $wepo_add_text_variable = isset($wepo_option) ? (isset($wepo_option['add_to_cart_text_addon_variable']) ? $wepo_option['add_to_cart_text_addon_variable'] : '') : '';
                $select_option_text['variable'] = empty($wepo_add_text_variable) ? 'Select options' : $wepo_add_text_variable;
            } elseif (in_array('woo-extra-product-options/woo-extra-product-options.php', $active_plugins)) {
                $wepo_option = get_option('thwepof_advanced_settings');
                $wepo_add_text = isset($wepo_option) ? (isset($wepo_option['add_to_cart_text_addon']) ? $wepo_option['add_to_cart_text_addon'] : '') : '';
                $select_option_text['simple'] = empty($wepo_add_text) ? 'Select options' : $wepo_add_text;         
            }
            return $select_option_text;
        }

        public static function check_product_exists(array $thwwac_products)
        {
            foreach ($thwwac_products as $pkey => $value) {
                if(get_post_type($pkey) === 'product' || get_post_type($pkey) === 'product_variation'){
                } else {
                    unset($thwwac_products[$pkey]);
                    THWWC_Public_Settings::update_wishlist_products($thwwac_products);
                }
            }
        }

        public static function thwwc_get_attribute_label_from_name($key)
        {
            $attribute_taxonomies = wc_get_attribute_taxonomies(); 
            $attribute_terms = array();
            if ($attribute_taxonomies) {
                foreach ($attribute_taxonomies as $tax) {
                    if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) {
                        $attribute_label = isset($tax->attribute_label) ? $tax->attribute_label : false;
                        $attribute_name = isset($tax->attribute_name) ? $tax->attribute_name : false;
                        if ($attribute_name == $key) {
                            return $attribute_label;
                        }
                    }
                }
            }
            return '';
        }
    
        public static function thwwc_get_general_settings()
        {
            $settings = get_option(self::OPTION_KEY_GENERAL_SETTINGS);   
            return empty($settings) ? false : $settings;
        }

        public static function thwwc_get_shop_page_settings()
        {
            $settings = get_option(self::OPTION_KEY_SHOP_PAGE_SETTINGS);   
            return empty($settings) ? false : $settings;
        }

        public static function thwwc_get_product_page_settings()
        {
            $settings = get_option(self::OPTION_KEY_PRODUCT_PAGE_SETTINGS);   
            return empty($settings) ? false : $settings;
        }

        public static function thwwc_get_wishlist_page_settings()
        {
            $settings = get_option(self::OPTION_KEY_WISHLIST_PAGE_SETTINGS);   
            return empty($settings) ? false : $settings;
        }

        public static function thwwc_get_counter_settings()
        {
            $settings = get_option(self::OPTION_KEY_COUNTER_SETTINGS);   
            return empty($settings) ? false : $settings;
        }

        public static function thwwc_get_socialmedia_settings()
        {
            $settings = get_option(self::OPTION_KEY_SOCIALMEDIA_SETTINGS);   
            return empty($settings) ? false : $settings;
        }

        public static function thwwc_get_compare_settings()
        {
            $settings = get_option(self::OPTION_KEY_COMPARE_SETTINGS);   
            return empty($settings) ? false : $settings;
        }

        public static function thwwc_get_compare_table_settings()
        {
            $settings = get_option(self::OPTION_KEY_COMPARE_TABLE_SETTINGS);   
            return empty($settings) ? false : $settings;
        }
        
        public static function write_log ( $log )  {
            if ( true === WP_DEBUG ) {
                if ( is_array( $log ) || is_object( $log ) ) {
                    error_log( print_r( $log, true ) );
                } else {
                    error_log( $log );
                }
            }
        }
    }
endif;