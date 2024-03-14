<?php

/**
 * Plugin Name:       Simple Buy Now Button for PayPal
 * Plugin URI:        http://www.wpcodelibrary.com
 * Description:       This plugin allows you to add PayPal Buy Now button to your site using shortcode.
 * Version:           1.1.1
 * Author:            WPCodelibrary
 * Author URI:        #
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-buynow-button-for-paypal
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WPCBNB_VERSION', '1.0.0' );

if (!class_exists('Simple_Paypal_Buynow_Button')) {

    /**
     * Plugin main class.
     *
     * @package Simple_Paypal_Buynow_Button
     */
    class Simple_Paypal_Buynow_Button {

        /**
         * Plugin version.
         *
         * @var string
         */
        const VERSION = '1.0.0';

        /**
         * Instance of this class.
         *
         * @var object
         */
        protected static $instance = null;

        /**
         * Initialize the plugin public actions.
         */
        private function __construct() {
            add_action('init', array($this, 'wpcpbnb_load_plugin_textdomain'));
            add_shortcode('wpcsimple_button', array($this, 'wpcpbnb_create_button_shortcode'));
            add_filter('widget_text', 'do_shortcode');
        }

        /**
         * Return an instance of this class.
         *
         * @return object A single instance of this class.
         */
        public static function get_instance() {
            // If the single instance hasn't been set, set it now.
            if (null == self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Load the plugin text domain for translation.
         */
        public function wpcpbnb_load_plugin_textdomain() {
            load_plugin_textdomain('simple-paypal-buynow-button', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }

        public function wpcpbnb_create_button_shortcode($atts) {
            $admin_email = get_option('admin_email');
            
            $atts = shortcode_atts(
                    array('email' => $admin_email,'name' => 'Dummy Item','amount' => '0.00','size' => 'large', 'currency_code' =>'USD','lc' => 'EN_US','return' => '','cancel_return' => ''), $atts, 'bartag');
	
            $lng = !empty( $atts['lc'] ) ? $atts['lc'] : 'EN_US';
            switch ($atts['size']) {
                case 'small':
                    $imgurl = 'https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif';
                    break;
                case 'medium':
                    $imgurl = 'https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif';
                    break;
                case 'large':
                    $imgurl = 'https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif';
                    break;
                default:
                    $imgurl = 'https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif';
            }
            
            switch ($atts['paymentaction']) {
                case 'sale':
                    $action = 'sale';
                    break;
                case 'authorization':
                    $action = 'authorization';
                    break;
               
                default:
                    $action = 'sale';
            }
            $email = !empty( $atts['email'] ) ? $atts['email'] : $admin_email;
            $lng = !empty( $atts['lc'] ) ? $atts['lc'] : 'EN_US';
            $atts['no_note'] = !empty( $atts['no_note'] ) ? $atts['no_note'] : '';
            

            return '<form target="_blank" action="https://www.paypal.com/cgi-bin/webscr" method="post">
    			<div class="wpc_buynow_button">
			        <input type="hidden" name="cmd" value="_xclick">
                                <input type="hidden" name="business" value="'.$email.'">
                                <input type="hidden" name="item_name" value="'. $atts['name'].'">
                                <input type="hidden" name="amount" value="' . $atts['amount'] . '">
                                <input type="hidden" name="currency_code" value="' . $atts['currency_code'] . '">
                                <input type="hidden" name="lc" value="' . $lng . '">
                                <input type="hidden" name="paymentaction" value="' . $action . '">
                                <input type="hidden" name="no_note" value="'.$atts['no_note'].'">
			        <input type="hidden" name="rm" value="0">
                                <input type="hidden" name="return" value = "' . $atts['return'] . '" />
                                <input type="hidden" name="cancel_return" value = "' . $atts['cancel_return'] . '" />
			        <input type="image" src="' . $imgurl . '" name="submit" alt="PayPal - The safer, easier way to pay online.">
			        <img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			    </div>
			</form>';
        }

    }

    add_action('plugins_loaded', array('Simple_Paypal_Buynow_Button', 'get_instance'));
}