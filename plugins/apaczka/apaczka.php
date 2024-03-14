<?php
/*
	Plugin Name: Apaczka.pl WooCommerce
	Plugin URI: https://wordpress.org/plugins/apaczka
	Description: Zintegruj WooCommerce z Apaczka.pl. Dzięki integracji, możesz skorzystać z promocyjnej oferty na usługi UPS, DHL, K-EX, DPD, TNT, FedEx, InPost i Pocztex 24.
	Version: 1.4.8
	Author: Inspire Labs
	Author URI: https://inspirelabs.pl/
	Text Domain: apaczka
	Domain Path: /languages/
	Tested up to: 6.1.1
    WC tested up to: 7.3.0

	Copyright 2018 Inspire Labs sp. z o.o.

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

define ('APACZKA_PLUGIN_DIRPATH', __DIR__);

if (!defined('ABSPATH')) exit; // Exit if accessed directly


if (!class_exists('inspire_Plugin4')) {
    require_once('classes/inspire/plugin4.php');
}

if ( !function_exists( 'wpdesk_is_plugin_active' ) ) {
    function wpdesk_is_plugin_active( $plugin_file ) {

        $active_plugins = (array) get_option( 'active_plugins', array() );

        if ( is_multisite() ) {
            $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
        }

        return in_array( $plugin_file, $active_plugins ) || array_key_exists( $plugin_file, $active_plugins );
    }
}

if (wpdesk_is_plugin_active('woocommerce/woocommerce.php') ) {

	class WPDesk_Apaczka_Plugin extends inspire_Plugin4 {

		protected $_pluginNamespace = 'apaczka';

		protected $shipping_methods = array();

		private static $instance;

		private $shipx;

        /**
         * @return self|null
         */
        public static function get_instance()
        {
            return self::$instance;
        }

		public function __construct()
		{

			parent::__construct();
			add_action('plugins_loaded', array( $this, 'init_apaczka' ), 1000 );
		}

		public function init_apaczka() {
		    self::$instance = $this;
            add_filter('woocommerce_shipping_packages', array($this, 'removeApaczkaPackageFromCheckout'));

			require_once('classes/class-apaczka-fs-hooks.php');
			require_once('classes/class-apaczka-orders-table.php');
			$orders_table = new apaczkaOrdersTable();



			ApaczkaFSHooks::get_instance();
			require_once('classes/apaczka-api.php');
			require_once('classes/shipx-api.php');

			require_once('classes/shipping-method.php');

			require_once('classes/ajax.php');

			$this->shipping_methods['apaczka'] = new WPDesk_Apaczka_Shipping();
			$this->shipping_methods['apaczka_cod'] = new WPDesk_Apaczka_Shipping_COD();
			$this->shipping_methods['apaczka_cod']->set_title( $this->shipping_methods['apaczka']->title . __(' (Za pobraniem)', 'apaczka' ) );

			add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'), 75 );

			add_action( 'admin_notices', array( $this, 'admin_notices' ) );

			add_filter( 'woocommerce_shipping_methods', array( $this, 'woocommerce_shipping_methods' ), 20, 1 );

            add_action( 'woocommerce_settings_saved', array($this, 'save_post') );

			add_filter( 'woocommerce_order_formatted_shipping_address', array( $this, 'apaczka_shipping_address' ), 90, 2 );
			
			add_action( 'woocommerce_cart_totals_after_order_total',  array( $this, 'hide_inpost_shipping_in_card' ) );
			
			add_action( 'wp_ajax_save_parcel_machine_address_wc_session', [ $this, 'save_parcel_machine_address_wc_session' ] );
			add_action( 'wp_ajax_nopriv_save_parcel_machine_address_wc_session', [ $this, 'save_parcel_machine_address_wc_session' ] );

            $this->shipx = WPDesk_Apaczka_Plugin::get_shipx_api();
		}
		
		public function save_parcel_machine_address_wc_session()
        {
	        if ( ! isset( $_POST['nonce'] ) ||
		        ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nonce'] ) ), 'wc_apc_nonce_pma' ) ) {
		        echo wp_json_encode( [ 'error' => 'Error nonce data.' ] );
		        die();
	        }
	        
	        if ( isset( $_POST['parcel_machine_address'] ) && ! empty( $_POST['parcel_machine_address'] ) ) {
		        $parcel_machine_address = wp_unslash( $_POST['parcel_machine_address'] );
		
		        foreach ( $parcel_machine_address as $k => $v ) {
			        WC()->session->set( 'parcel_machine_address_' . sanitize_key( $k ), sanitize_text_field( $v ) );
		        }
            }
	
	        if ( isset( $_POST['parcel_machine_id'] ) && ! empty( $_POST['parcel_machine_id'] ) ) {
		        WC()->session->set( 'parcel_machine_id', sanitize_text_field( wp_unslash( $_POST['parcel_machine_id'] ) ) );
	        }
	        
	        die();
        }
		
		public function hide_inpost_shipping_in_card()
		{
			$selected_method_in_cart = flexible_shipping_method_selected_in_cart('apaczka');
			$selected_method_in_cart_cod = flexible_shipping_method_selected_in_cart('apaczka_cod');
			
			if ( false !== $selected_method_in_cart || false !== $selected_method_in_cart_cod ) {
				?>
                <script>
                    jQuery(document).ready(function(){
                        jQuery( '.woocommerce-shipping-destination, .woocommerce-shipping-calculator' ).hide();
                    });
                </script>
                <?php
			}
		}

		public function apaczka_shipping_address( $raw_address, $order )
		{
			$_parcel_machine_id = get_post_meta( $order->get_id(), '_parcel_machine_id', true );
			
			if ( ! empty( $_parcel_machine_id ) ) {
				$raw_address = array(
					'first_name' => 'Paczkomat',
					'last_name'  => $_parcel_machine_id,
				);
				
				$raw_address['address_1'] = null;
				$raw_address['address_2'] = null;
				$raw_address['city'] = null;
				$raw_address['state'] = null;
				$raw_address['postcode'] = null;
				$raw_address['country'] = null;
				
				$parcel_machine_address = get_post_meta( $order->get_id(), '_parcel_machine_address', true );
				
				if ( ! empty( $parcel_machine_address['street'] ) && ! empty( $parcel_machine_address['building_number'] ) ) {
					$raw_address['address_1'] = $parcel_machine_address['street'] . ' ' . $parcel_machine_address['building_number'];
				}
				
				if ( ! empty( $parcel_machine_address['post_code'] ) ) {
					$raw_address['city'] = $parcel_machine_address['post_code'] . ' ';
				}
				
				if ( ! empty( $parcel_machine_address['city'] ) ) {
					$raw_address['city'] .= $parcel_machine_address['city'];
				}
				
			}
			
			return $raw_address;
		}

        public function save_post()
        {
            update_option( 'apaczka_countries_cache', '');
        }

        /**
         * @return \shipxApi
         */
        public static function get_shipx_api()
        {
            return new shipxApi();
        }

        public function removeApaczkaPackageFromCheckout($fields) {
            if (isset($fields[0]['rates']['apaczka'])) {
                unset($fields[0]['rates']['apaczka']);
            }

            if (isset($fields[0]['rates']['apaczka_cod'])) {
                unset($fields[0]['rates']['apaczka_cod']);
            }
            return $fields;
        }

		public function woocommerce_shipping_methods( $methods ) {
			$methods['apaczka'] = $this->shipping_methods['apaczka'];
			$methods['apaczka_cod'] = $this->shipping_methods['apaczka_cod'];
			return $methods;
		}

		public function admin_notices() {
		}

		public function loadPluginTextDomain() {
			parent::loadPluginTextDomain();
			$ret = load_plugin_textdomain( 'apaczka', FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
		}

		public static function getTextDomain() {
			return 'apaczka';
		}

		function enqueue_admin_scripts() {
			wp_enqueue_style( 'woocommerce-apaczka-admin', $this->getPluginUrl() . 'assets/css/admin.css' );
		}

		function enqueue_scripts() {
		}

		function admin_footer() {
		}

		/**
		 * action_links function.
		 *
		 * @access public
		 * @param mixed $links
		 * @return void
		 */
		 public function linksFilter( $links ) {

		     $plugin_links = array(
		     		'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=wpdesk_apaczka_shipping') . '">' . __( 'Ustawienia', 'apaczka' ) . '</a>',
		     		'<a href="mailto:bok@apaczka.pl">' . __( 'Kontakt z BOK', 'apaczka' ) . '</a>',
		     );

		     return array_merge( $plugin_links, $links );
        }
	}

	function wpdesk_apaczka_init() {
        if (wpdesk_is_plugin_active('flexible-shipping/flexible-shipping.php')) {
            $_GLOBALS['woocommerce_apaczka'] = new WPDesk_Apaczka_Plugin();
        } else {
            add_action('admin_notices', 'flexible_shipping_not_found');
        }
	}
	add_action( 'plugins_loaded', 'wpdesk_apaczka_init' );
}


function posts_2_posts_required()
{
    $url = network_admin_url('plugin-install.php?tab=search&type=term&s=flexible+shipping&plugin-search-input=Search+Plugins');
    echo '
    <div class="error">
        <p>The <a href="' . $url . '">Flexible Shipping</a> is required.</p>
    </div>
    ';
}


function flexible_shipping_not_found(){

    if ( current_user_can( 'activate_plugins' ) ) {
        //add_action('admin_notices', 'posts_2_posts_required');

        $url = network_admin_url('plugin-install.php?tab=search&type=term&s=flexible+shipping&plugin-search-input=Search+Plugins');
        echo '
    <div class="error">
        <p>Apaczka wymaga do prawidłowego działania wymaga wtyczki: <a href="' . $url . '">Flexible Shipping dla WooCommerce</a></p>
        <p><a href="https://wordpress.org/plugins/flexible-shipping/" target="_blank">Link bezpośredni</a></p>
    </div>
    ';

    }
}

if ( !function_exists( 'wpdesk_is_plugin_active' ) ) {
    function wpdesk_is_plugin_active( $plugin_file ) {

        $active_plugins = (array) get_option( 'active_plugins', array() );

        if ( is_multisite() ) {
            $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
        }

        return in_array( $plugin_file, $active_plugins ) || array_key_exists( $plugin_file, $active_plugins );
    }
}
