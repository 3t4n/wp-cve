<?php

/**
 * Plugin Name:       Donation Button For PayPal
 * Plugin URI:        http://www.wpcodelibrary.com
 * Description:       Create your own PayPal buttons as many as you want as per your need in simple way.
 * Version:           1.1.1
 * Author:            WPCodelibrary
 * Author URI:        http://www.wpcodelibrary.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pal-donation-button
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}


if (!class_exists('Pal_Donation_Button')) {

    /**
     * Plugin main class.
     *
     * @package Pal_Donation_Button
     */
    class Pal_Donation_Button {

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
            add_action('init', array($this, 'pdb_load_plugin_textdomain'));
            add_shortcode('wpcpaypal_button', array($this, 'create_button_shortcode'));
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
        public function pdb_load_plugin_textdomain() {
            load_plugin_textdomain('pal-donation-button', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }

        public function create_button_shortcode($atts) {
            $admin_email = get_option('admin_email');
            $atts = shortcode_atts(
                    array(
                'email' => $admin_email,
                'currency' => 'USD',
                'purpose' => '',
                'amount' => '',
                'size' => 'large'
                    ), $atts, 'bartag');


            switch ($atts['size']) {
                case 'small':
                    $imgurl = 'https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif';
                    break;
                case 'medium':
                    $imgurl = 'https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif';
                    break;
                case 'large':
                    $imgurl = 'https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif';
                    break;
                default:
                    $imgurl = 'https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif';
            }

            return '<form  target="_blank" action="https://www.paypal.com/cgi-bin/webscr" method="post">
    			<div class="pal_donation_button">
			        <input type="hidden" name="cmd" value="_donations">
                                <input type="hidden" name="item_name" value="' . $atts['purpose'] . '">
                                <input type="hidden" name="amount" value="' . $atts['amount'] . '">
			        <input type="hidden" name="business" value="' . $atts['email'] . '">
			        <input type="hidden" name="rm" value="0">
			        <input type="hidden" name="currency_code" value="' . $atts['currency'] . '">
					<input name="bn" value="WPCodelibrary_SP_EC_PRO" type="hidden">
			        <input type="image" src="' . $imgurl . '" name="submit" alt="PayPal - The safer, easier way to pay online.">
			        <img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			    </div>
			</form>';
        }

    }

    add_action('plugins_loaded', array('Pal_Donation_Button', 'get_instance'));
}