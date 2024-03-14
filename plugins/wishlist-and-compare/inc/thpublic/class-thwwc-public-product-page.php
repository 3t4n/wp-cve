<?php
/**
 * The public wishlist product page functionality of the plugin.
 *
 * @package    Wishlist-and-compare
 * @subpackage Wishlist-and-compare/inc/thpublic
 *
 * @link  https://themehigh.com
 * @since 1.0.0.0
 */

namespace THWWC\thpublic;

use \THWWC\base\THWWC_Base_Controller;
use \THWWC\base\THWWC_Utils;

if (!class_exists('THWWC_Public_Product_Page')) :
    /**
     * Public product page class.
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Public_Product_Page extends THWWC_Base_Controller
    {
        /**
         * Function to run hooks and filters.
         *
         * @return void
         */
        public function register()
        {
            $this->controller = new THWWC_Base_Controller();
            $product_options = THWWC_Utils::thwwc_get_product_page_settings();
            $position = isset($product_options['button_pstn_pdct_page']) ? $product_options['button_pstn_pdct_page'] : 'after';

            if ($position == 'after') {
                add_action('woocommerce_after_add_to_cart_form', array($this, 'add_button_single_page'), 11);
            } elseif ($position == 'before') {
                add_action('woocommerce_before_add_to_cart_form', array($this, 'add_button_single_page'), 11);
            } elseif ($position == 'above_thumb') {
                add_action('woocommerce_before_single_product_summary', array($this, 'add_button_single_page'), 8);
            } else {
                add_shortcode('thwwac_addtowishlist', array($this, 'add_button_single_page'));
            }
        }

        /**
         * Function to show add to wishlist button in product single page.
         *
         * @return void
         */
        public function add_button_single_page()
        {
            global $product;
            if ($product) {
                include THWWC_PATH . "/templates/thwwac-addtowishlist-single.php";
            }
        }

        /**
         * Function to show add to wishlist button in product single page 
         * For out of stock and product with no price.
         *
         * @return void
         */
        public function add_button_single_page_summary()
        {
            global $product;
            if ($product) {
                $product_id = $product->get_id();
                if( !$product->is_in_stock() || $product->get_price_html() == null ) {
                    include THWWC_PATH . "/templates/thwwac-addtowishlist-single.php";
                }
            }
        }
    }
endif;