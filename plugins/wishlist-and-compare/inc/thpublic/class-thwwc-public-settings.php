<?php
/**
 * The public wishlist settings functionality of the plugin.
 *
 * @package    Wishlist-and-compare
 * @subpackage Wishlist-and-compare/inc/thpublic
 *
 * @link  https://themehigh.com
 * @since 1.0.0.0
 */

namespace THWWC\thpublic;
use THWWC\thpublic\THWWC_Public_Wishlist;
use \THWWC\base\THWWC_Utils;

use WP_Session;

if (!class_exists('THWWC_Public_Settings')) :
    /**
     * Public wishlist settings class.
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Public_Settings
    {
        /**
         * Function to run hooks and filters.
         *
         * @return void
         */
        public function register()
        {
            add_action('wp', array($this, 'set_key'));
            add_filter('woocommerce_login_redirect', array($this, 'ts_redirect_login'), 10, 2);
            add_filter('woocommerce_registration_redirect', array($this, 'ts_redirect_register'), 10, 2);

            if (apply_filters('thwwc_wishlist_shortcode_position_first', false)) {
                add_action('woocommerce_before_shop_loop', array($this, 'shortcode_for_shop_page'), 10);
            } else {
                add_action('woocommerce_after_main_content', array($this, 'shortcode_for_shop_page'), 10);
            }

            add_action('wp_footer', array($this, 'thwwc_append_popups'));

            $shopoptions = THWWC_Utils::thwwc_get_shop_page_settings();
            $wish_position = isset($shopoptions['wishlist_position']) ? $shopoptions['wishlist_position'] : 'after';

            $product_options = THWWC_Utils::thwwc_get_product_page_settings();
            $wish_pstn_pdct = isset($product_options['button_pstn_pdct_page']) ? $product_options['button_pstn_pdct_page'] : 'after';

            $compare_options = THWWC_Utils::thwwc_get_compare_settings();
            $comp_position = isset($compare_options['shoppage_position']) ? $compare_options['shoppage_position'] : '';
            $comp_pdct_pstn = isset($compare_options['productpage_position']) ? $compare_options['productpage_position'] : '';
            $theme = wp_get_theme();
            $before_hook_priority_end_div = $theme->name == 'Avada' ? 11 : 9;

            // align buttons if compare and wishlist buttons are in same position
            // shop page
            if ($wish_position == 'after' && $comp_position == 'after') {
                add_action('woocommerce_after_shop_loop_item', array($this, 'start_div_element'),11);
                add_action('woocommerce_after_shop_loop_item', array($this, 'end_div_element'),13);
            } elseif ($wish_position == 'before' && $comp_position == 'before') {
                add_action('woocommerce_after_shop_loop_item', array($this, 'start_div_element'), 7);
                add_action('woocommerce_after_shop_loop_item', array($this, 'end_div_element'), $before_hook_priority_end_div);
            }
            //product page
            if ($wish_pstn_pdct == 'after' && $comp_pdct_pstn == 'after') {
                add_action('woocommerce_after_add_to_cart_form', array($this, 'single_start_div_element'),11);
                add_action('woocommerce_after_add_to_cart_form', array($this, 'end_div_element'),13);
            } elseif ($wish_pstn_pdct == 'before' && $comp_pdct_pstn == 'before') {
                add_action('woocommerce_before_add_to_cart_form', array($this, 'single_start_div_element'), 9);
                add_action('woocommerce_before_add_to_cart_form', array($this, 'end_div_element'), 13);
            }
        }

        public function start_div_element()
        {
            echo '<div class="thwwc-full-section">';
        }
        public function end_div_element()
        {
            echo '</div>';
        }
        public function single_start_div_element()
        {
            echo '<div class="thwwc-pdct-full-section">';
        }
        /**
         * Function to redirect to shop or product page
         * with adding product to wishlist on login.
         *
         * @param url $redirect is redirect url
         * @param int $user     is userid of login user
         *
         * @return url
         */
        public function ts_redirect_login($redirect, $user)
        {
            $referer = isset($redirect) ? esc_url_raw($redirect) : '';
            if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'redirect_nonce')) {
                $wishlist_products = get_user_meta($user->ID, 'thwwc_wishlist');
                $thwwac_products = !empty($wishlist_products) ? $wishlist_products[0] : array();
                $product_id = isset($_GET['id']) ? sanitize_key($_GET['id']) : '';
                if (!array_key_exists($product_id, $thwwac_products)) {
                    $thwwac_products = array($product_id => time()) + $thwwac_products;
                    update_user_meta($user->ID, 'thwwc_wishlist', $thwwac_products);
                }
                $referer = isset($_GET['url']) ? esc_url_raw($_GET['url']) : $referer;
            }
            return $referer;
        }

        /**
         * Function to redirect to shop or product page
         * with adding product to wishlist on registration.
         *
         * @param url $redirect is redirect url
         *
         * @return url
         */
        public function ts_redirect_register($redirect)
        {
            $referer = isset($redirect) ? esc_url_raw($redirect) : '';
            if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'redirect_nonce')) {
                $id = get_current_user_id();
                $wishlist_products = get_user_meta($id, 'thwwc_wishlist');
                $thwwac_products = !empty($wishlist_products) ? $wishlist_products[0] : array();
                $product_id = isset($_GET['id']) ? sanitize_key($_GET['id']) : '';
                if (! array_key_exists($product_id, $thwwac_products)) {
                    $thwwac_products = array($product_id => time()) + $thwwac_products;
                    update_user_meta($id, 'thwwc_wishlist', $thwwac_products);
                }
                $referer = isset($_GET['url']) ? esc_url_raw($_GET['url']) : '';
            }
            return $referer;
        }

        public function shortcode_for_shop_page(){
            $page_id = wc_get_page_id('shop');
            $options = THWWC_Utils::thwwc_get_general_settings();
            $wishlist_page = isset($options['wishlist_page']) ? $options['wishlist_page'] : false;
            if ($wishlist_page && $page_id && $page_id == $wishlist_page) {
                echo do_shortcode('[thwwac_wishlist]');
            }
            $compare = THWWC_Utils::thwwc_get_compare_table_settings();
            $compare_page = isset($compare['compare_page']) ? $compare['compare_page'] : false;
            if ($compare_page && $page_id && $page_id == $compare_page) {
                echo do_shortcode('[thwwac_compare_list]');
            }
        }

        /**
         * Function to show add to compare button.
         *
         * @return void
         */
        public function thwwc_append_popups()
        {
            if (is_shop() || is_product() || is_product_category() || is_archive() || apply_filters('thwwc_show_comparepopup', false)) {
                include THWWC_PATH . "/templates/thwwac-comparepopup.php";
            }
            if (is_shop() || is_product() || is_product_category() || is_archive() || apply_filters('thwwc_show_popups', false)) {
                include THWWC_PATH . "/templates/thwwac-wishlist-popups.php";
            }
        }

        /**
         * Function to set key.
         *
         * @return string key
         */
        public function set_key()
        {
            if(!is_user_logged_in()){
                $this->check_if_exists_in_options_table();
                $this->define_constants();

                $new_key = sanitize_key($this->generate_key());
                while ($this->exists_key($new_key)) {
                    $new_key = sanitize_key($this->generate_key());
                }
                if (isset($_COOKIE['thwwc_key'])) {
                    return sanitize_key($_COOKIE['thwwc_key']);
                } else {
                    $previousErrorLevel = error_reporting();
                    error_reporting(\E_ERROR);
                    setcookie('thwwc_key', $new_key, time() + 604800, COOKIEPATH, COOKIE_DOMAIN);
                    error_reporting($previousErrorLevel);
                }
                return $new_key;
            }
            
        }

        /**
         * Function to generate alphanumeric key
         *
         * @return string key
         */
        public function generate_key()
        {
            $key = wp_generate_uuid4();
            return $key;
        }

        /**
         * Function to check the key already exists or not
         *
         * @param string $key is created key
         *
         * @return boolean
         */
        public static function exists_key($key)
        {
            if (get_option('thwwc_wishlist_'.$key)) {
                return true;
            }
            return false;
        }

        public function check_if_exists_in_options_table()
        {
            if (is_user_logged_in() && (($user_id = get_current_user_id()) > 0)) {
                $thwwc_key = get_user_meta($user_id, 'thwwac_key', true);
                if ($thwwc_key) {
                    $thwwc_wishlist_products = get_option('thwwac_wishlist_'.$thwwc_key) ? get_option('thwwac_wishlist_'.$thwwc_key) : array();
                    if (!get_user_meta($user_id, 'thwwc_wishlist')) {
                        update_user_meta($user_id, 'thwwc_wishlist', $thwwc_wishlist_products);
                        delete_option('thwwac_wishlist_'.$thwwc_key);
                    }
                    $thwwc_compare_products = get_option('thwwac_compare_'.$thwwc_key) ? get_option('thwwac_compare_'.$thwwc_key) : array();
                    if (!get_user_meta($user_id, 'thwwc_compare')) {
                        update_user_meta($user_id, 'thwwc_compare', $thwwc_compare_products);
                        delete_option('thwwac_compare_'.$thwwc_key);
                    }
                    delete_user_meta($user_id, 'thwwac_key');
                }
            }
        }

        public static function update_wishlist_products(array $products)
        {
            if (is_user_logged_in() && (($user_id = get_current_user_id()) > 0)) {
                update_user_meta($user_id, 'thwwc_wishlist', $products);
            } else {
                $thwwc_key = isset($_COOKIE['thwwc_key']) ? sanitize_key($_COOKIE['thwwc_key']) : '#';
                update_option('thwwc_wishlist_'.$thwwc_key, $products);
            }
        }

        public static function get_wishlist_products()
        {
            $key = THWWC_Utils::get_key();
            if (isset($_GET['thwwc_id'])) {
                $key = sanitize_text_field(urldecode($_GET['thwwc_id']));
                $user_id = THWWC_Public_Wishlist::twoway_encrypt($key,'d');
                $thwwc_products = get_user_meta($user_id, 'thwwc_wishlist');
                $return_products = array();
                if (!empty($thwwc_products)) {
                    $return_products = $thwwc_products[0];
                } else if (get_option('thwwc_wishlist_'.$key)) {
                    $return_products = get_option('thwwc_wishlist_'.$key);
                }
                return $return_products;
            } else if (is_user_logged_in() && (($user_id = get_current_user_id()) > 0)) {
                $thwwc_products = get_user_meta($user_id, 'thwwc_wishlist');
                return !empty($thwwc_products) ? $thwwc_products[0] : array();
            } else {
                return get_option('thwwc_wishlist_'.$key) ? get_option('thwwc_wishlist_'.$key) : array();
            }
            return array();
        }

        public static function update_compare_products(array $products)
        {
            if (is_user_logged_in() && (($user_id = get_current_user_id()) > 0)) {
                update_user_meta($user_id, 'thwwc_compare', $products);
            } else {
                $thwwc_key = isset($_COOKIE['thwwc_key']) ? sanitize_key($_COOKIE['thwwc_key']) : '#';
                update_option('thwwc_compare_'.$thwwc_key, $products);
            }
        }

        public static function get_compare_products()
        {
            $key = THWWC_Utils::get_key();
            if (is_user_logged_in() && (($user_id = get_current_user_id()) > 0)) {
                $thwwc_products = get_user_meta($user_id, 'thwwc_compare');
                return !empty($thwwc_products) ? $thwwc_products[0] : array();
            } else {
                return get_option('thwwc_compare_'.$key) ? get_option('thwwc_compare_'.$key) : array();
            }
            return array();
        }

        private function define_constants()
        {
            !defined('THWWC_ASSETS_URL') && define('THWWC_ASSETS_URL', THWWC_URL . 'assets/libs/');
        }
    }
endif;