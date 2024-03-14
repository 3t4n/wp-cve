<?php
/*
 * Plugin Name: Fish and Ships
 * Plugin URI: https://www.wp-centrics.com/
 * Description: A WooCommerce conditional table rate shipping method. Easy to understand and easy to use, it gives you an incredible flexibility.
 * Version: 1.5
 * Author: wpcentrics
 * Author URI: https://www.wp-centrics.com
 * Text Domain: fish-and-ships
 * Domain Path: /languages
 * Requires at least: 4.7
 * Tested up to: 6.4.3
 * WC requires at least: 3.0
 * WC tested up to: 8.6.1
 * Requires PHP: 7.0
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package Fish and Ships
*/

defined( 'ABSPATH' ) || exit;

// Prevent double plugin installation
if ( defined('WC_FNS_VERSION') || class_exists( 'Fish_n_Ships' ) ) {
	
	include_once dirname(__FILE__) . '/includes/double-installation.php';

} else {

	define ('WC_FNS_VERSION', '1.5' );
	define ('WC_FNS_PATH', dirname(__FILE__) . '/' );
	define ('WC_FNS_URL', plugin_dir_url( __FILE__ ) );

	/**
	 * The main Fish n Ships class (one instance).
	 *
	 */

	class Fish_n_Ships {
			
		public  $id             		  = 'fish_n_ships';
		private $terms_cached           = array();
		private $options                = array();
		private $im_pro                 = false;
		private $is_wpml                = false; // WPML Multilingual

		private $is_wpml_mc             = false; // WPML Multicurrency
		private $is_woo_mc              = false; // Official WooCommerce Multicurrency
		private $is_woocs				= false; // WOOCS Multicurrency freemium plugin
		private $is_aelia_mc			= false; // Aelia Multicurrency premium plugin
		private $is_wmc_f				= false; // VillaTheme Multicurrency freemium plugin
		private $is_alg_cs				= false; // WP Wham Multicurrency freemium plugin
		
		private $user_texts_translated  = NULL;
		
		public  $shipping_calculated    = false;   // Shipping rates calculated?
		
		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 * @version 1.5
		 */
		public function __construct() {

			// $this->load_options();

			// The selection methods
			require WC_FNS_PATH . 'includes/settings-form-fns.php';
			require WC_FNS_PATH . 'includes/date-settings-form-fns.php';
			require WC_FNS_PATH . 'includes/address-settings-form-fns.php';
			require WC_FNS_PATH . 'includes/boxes-settings-form-fns.php';
			
			// Add Fish n Ships method to the shipping methods list
			add_filter( 'woocommerce_shipping_methods', array($this, 'add_fish_n_ships_method') );

			// Styles and scripts
			add_action( 'admin_enqueue_scripts',  array( $this, 'admin_load_styles_and_scripts' ) );

			// Generates the HTML for a table row
			add_filter('wc_fns_shipping_rules_table_row_html', array($this, 'get_shipping_rules_table_row_html'));

			// Ajax
			add_action( 'wp_ajax_wc_fns_help',         array($this, 'wc_fns_help') );
			add_action( 'wp_ajax_wc_fns_logs',         array($this, 'wc_fns_logs') );
			add_action( 'wp_ajax_wc_fns_logs_pane',    array($this, 'wc_fns_logs_pane') );
			add_action( 'wp_ajax_wc_fns_fields',       array($this, 'wc_fns_fields') );
			add_action( 'wp_ajax_wc_fns_freemium',     array($this, 'wc_fns_freemium') );
			
			if ( $this->im_pro() ) 
			add_action( 'wp_ajax_wc_fns_request_news', array($this, 'wc_fns_request_news') );
			
			// Link to re-start wizard
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'add_plugin_action_link' ) );

			// Link to website product
			add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta' ), 10, 2 );

			// Add help tab
			add_action( 'current_screen', array( $this, 'add_tabs' ), 100 );

			// extra parameters on WC_Shipping_Rate / for 3rd party plugins
			add_filter( 'woocommerce_shipping_method_add_rate', array( $this, 'extra_params_shipping_rate'), 10, 3 );
			add_filter( 'wpw_currency_switcher_adjust_package_rate', array( $this, 'alg_cs_maybe_disable_conversion_package_rate' ), 10, 2 ); 
		}
		
		/**
		 * Load options at plugin initialization, and maybe first install.
		 *
		 * Since 1.5, the wizard, 5-stars and news & pointer settings are per each user saved separately
		 *
		 * @since 1.0.0
		 * @version 1.5
		 */
		public function load_options() {
			
			$should_update = false;
			
			// Set default options, for first installation (common for all users)
			$common_options = array(
				'first_version'    => WC_FNS_VERSION,
				'first_install'    => time(),
				'current_version'  => '',
				'anytime_pro'      => 0,
				'serial'           => '',
				'close_freemium'   => 0,
				'next_read_news'     => 0,
				'boxes'              => array(),
				//'show_wizard'      => time() - 1, // now
				//'closed_news'      => array(),
				//'five_stars'       => time() + (60 * 60 * 24 * 5), // five days
				'user_opts_default'  => date( 'Y-m-d H:i:s' ), // Switching time to new user notices system will be saved
			);

			$user_options_default = array(
				'show_wizard'      => time() - 1, // now
				'five_stars'       => time() + (DAY_IN_SECONDS * 5), // five days
				'closed_news'      => array(),
			);
			
			// Load common options from DB and overwrite defaults
			// For legacy, new user-related settings will be kept, and saved as default
			$opt_db = get_option( 'fish-and-ships-woocommerce', array() );
			if( is_array($opt_db) ) {
				foreach( $opt_db as $key=>$value ) {
					if( in_array( $key, array( 'show_wizard', 'five_stars', 'closed_news' ) ) )
					{
						$user_options_default[$key] = $value;
						$should_update = true;
					}
					else
					{
						$common_options[$key] = $value;
					}
				}
			}

			// For legacy, new user-related settings will be kept, and saved as default
			if ($should_update) {
				update_option( 'fish-and-ships-woocommerce-user-default', $user_options_default, false );
			}
			
			$user_options = false;
			
			if( current_user_can( 'manage_options' ) || current_user_can( 'manage_woocommerce' ) )
			{
				// We will look for saved user options
				$user_options = get_user_meta( get_current_user_id(), 'fish-and-ships-woocommerce', true );

				if( ! is_array( $user_options ) )
				{
					// User registered before 1.5 new user message system? get previously common settings
					$user = wp_get_current_user();
					if( $user->user_registered < $common_options[ 'user_opts_default' ] )
					{
						$user_options = get_option( 'fish-and-ships-woocommerce-user-default', $user_options_default );
					}
					$should_update = true; // Save in any case
				}
			}

			// We will set as defaults as fallback: can't manage options, not logged or new user
			if( ! is_array( $user_options ) )
				$user_options = $user_options_default;

			// Now we will mix the common and user related settings. Later will be saved separately if needed
			$options = array_merge( $common_options, $user_options );

			// First install?
			if ($options['current_version'] == '') {
				$options['current_version'] = WC_FNS_VERSION;
				$should_update = true;
			}
			
			// Plugin Update?
			if (version_compare($options['current_version'], WC_FNS_VERSION, '<') ) {
				$options['current_version'] = WC_FNS_VERSION;
				$should_update = true;
			}
			
			// Welcome to Pro?
			if ($this->im_pro() && $options['anytime_pro']==0) {
				$options['anytime_pro'] = time();
				$should_update = true;
			}
			
			// Five stars Remind later bug (previous releases)
			if ( $options['five_stars'] > time() * 2 ) {
				$options['five_stars'] = time() + DAY_IN_SECONDS;
				$should_update = true;
			}
			
			$this->options = $options;
			
			if ($should_update) {
				
				if ( $this->im_pro() ) {
					// reset news
					$options['next_read_news']  = time() + 1; // next load
					update_option( 'fish-and-ships-woocommerce-news', array(), false );
				}			
				$this->set_options($options);
			}
		}
		
		/**
		 * Get all options. Memcached
		 *
		 * @since 1.0.0
		 * @version 1.5
		 */
		public function get_options() {
			
			//First time? Let's load it from DDBB
			if( count( $this->options ) == 0 )
				$this->load_options();
			
			return $this->options;
		}

		/**
		 * Get one option.
		 *
		 * @version 1.5
		 */
		public function get_option( $opt ) {
			
			$options = $this->get_options();
			
			return isset($options[$opt]) ? $options[$opt] : false;
		}

		/**
		 * Set all options
		 *
		 * Since 1.5, the wizard, 5-stars and news & pointer settings are per each user saved separately
		 *
		 * @since 1.0.0
		 * @version 1.5
		 */
		public function set_options($options) {

			// Store in memory all options together: common + user
			$this->options = $options;
			
			// Save the user-specific settings
			$user_settings = array();
			$user_settings['closed_news'] = isset( $options['closed_news'] ) ? $options['closed_news'] : array();
			$user_settings['show_wizard'] = isset( $options['show_wizard'] ) ? $options['show_wizard'] : 0;
			$user_settings['five_stars']  = isset( $options['five_stars'] )  ? $options['five_stars']  : 0;
			update_user_meta( get_current_user_id(), 'fish-and-ships-woocommerce', $user_settings );
						
			// Remove user-specific settings and save common ones
			if( isset( $options['closed_news'] ) ) unset( $options['closed_news'] );
			if( isset( $options['show_wizard'] ) ) unset( $options['show_wizard'] );
			if( isset( $options['five_stars'] ) )  unset( $options['five_stars'] );
			update_option( 'fish-and-ships-woocommerce', $options, true );
		}

		/**
		 * Set an unique option.
		 *
		 * @version 1.5
		 */
		public function set_option( $opt, $value ) {
			
			$options = $this->get_options();
			
			if ( !isset( $options[$opt] ) ) return false;

			$options[$opt] = $value;
			$this->set_options( $options );
			
			return true;
		}

		/**
		 * 32 bits webservers can't allow big integers
		 *
		 * @since 1.3
		 */
		public function check_64_bits() {
			
			return PHP_INT_MAX > 3 * pow( 10, 9 );
		}

		/**
		 * Add Fish n Ships method to the shipping methods list
		 *
		 */
		public function add_fish_n_ships_method( $methods ) {
			if ( $this->is_wc() ) $methods[ $this->id ] = 'WC_Fish_n_Ships';
			return $methods;
		}

		/**
		 * Admin-side styles and scripts
		 * Since 1.5 the CSS and JS will be loaded minfied
		 * @version 1.5
		 */
		public function admin_load_styles_and_scripts () {

			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_register_script( 'wcfns_admin_script_light',  WC_FNS_URL . 'assets/js/admin-fns-light' . $min . '.js', array( 'jquery' ), WC_FNS_VERSION );
			wp_register_style( 'wcfns_admin_style', WC_FNS_URL . 'assets/css/admin-fns.css', array(), WC_FNS_VERSION );

			// Only in WC settings > shipping tab we will load the admin script, for performance reasons
			if ( isset($_GET['page'] ) && $_GET['page'] == 'wc-settings' && isset( $_GET['tab'] ) &&  $_GET['tab'] == 'shipping' )
			{				
				wp_register_script( 'wcfns_admin_script',         WC_FNS_URL . 'assets/js/admin-fns' . $min . '.js', array( 'jquery-ui-dialog', 'jquery-ui-sortable' ), WC_FNS_VERSION );
				wp_register_script( 'wcfns_admin_dropdown',       WC_FNS_URL . 'assets/js/dropdown-submenu/dropdown-submenu.min.js', array( 'jquery' ), WC_FNS_VERSION );
				wp_register_style(  'wcfns_admin_dropdown_style', WC_FNS_URL . 'assets/js/dropdown-submenu/dropdown-submenu-dist.css', array(), WC_FNS_VERSION );

				$data = require WC_FNS_PATH . 'includes/shipping_rules-data-js.php';

				wp_localize_script( 'wcfns_admin_script', 'wcfns_data', $data );

				//3rd party CSS and JS stuff
				do_action('wc-fns-styles-scripts-enqueue');

				wp_enqueue_script ( 'wcfns_admin_script' );
				wp_enqueue_script ( 'wcfns_admin_dropdown' );
				wp_enqueue_style  ( 'wcfns_admin_dropdown_style' );
			}
			wp_enqueue_script ( 'wcfns_admin_script_light' );
			wp_enqueue_style  ( 'wcfns_admin_style' );

		}

		/**
		 * Not, my friend, change this will not help you to get the premium features ;) 
		 *
		 */
		public function im_pro() {
			return $this->im_pro === true;
		}
		
		/**
		 * Check if method is known
		 *
		 * @since 1.0.0
		 * @version 1.1.9
		 *
		 * @param $type (string)
		 * @param $method_id (string)
		 *
		 * return: 
		 
				true (boolean)
				or
				error text message
		 */
		public function is_known($type, $method_id) {

			switch ($type) {

				case 'selection' :
					// Get selectors
					$selectors = apply_filters( 'wc_fns_get_selection_methods', array () );
					
					if ( isset($selectors[$method_id]) ) {
						if ( $this->im_pro() || !$selectors[$method_id]['onlypro'] ) return true;
						
						return sprintf('Warning: The %s method [%s]: only is supported in the Fish and Ships Pro version', $type, $method_id);
					}
					
					return sprintf('Error: Unknown %s method [%s]: maybe you are downgroaded Fish n Ships?', $type, $method_id);
					
					break;

				case 'cost' :
					// Get costs
					$selectors = apply_filters( 'wc_fns_get_cost_methods', array () );
					
					if ( isset($selectors[$method_id]) ) {
						if ( $this->im_pro() || (!isset($selectors[$method_id]['onlypro']) || !$selectors[$method_id]['onlypro']) ) return true;
						
						return sprintf('Warning: The %s method [%s]: only is supported in the Fish and Ships Pro version', $type, $method_id);
					}
					
					return sprintf('Error: Unknown %s method [%s]: maybe you are downgroaded Fish n Ships?', $type, $method_id);
					
					break;

				case 'action' :
					// Get actions
					$selectors = apply_filters( 'wc_fns_get_actions', array () );
					
					if ( isset($selectors[$method_id]) ) {
						if ( $this->im_pro() || !$selectors[$method_id]['onlypro'] ) return true;
						
						return sprintf('Warning: The %s method [%s]: only is supported in the Fish and Ships Pro version', $type, $method_id);
					}
					
					return sprintf('Error: Unknown %s method [%s]: maybe you are downgroaded Fish n Ships?', $type, $method_id);
					
					break;

				case 'logical operator' :

					if ( in_array($method_id, array('or', 'and') ) ) {
						if ( $this->im_pro() || $method_id == 'and' ) return true;
						
						return sprintf('Warning: The %s method [%s]: only is supported in the Fish and Ships Pro version', $type, $method_id);
					}
					
					return sprintf('Error: Unknown %s method [%s]: maybe you are downgroaded Fish n Ships?', $type, $method_id);

					break;
			}
			
			return 'Error: unknown ' . $type;
		}
		

		/**
		 * Gives the columns for a rule table row
		 *
		 * @since 1.0.0
		 * @version 1.4.0
		 *
		 * return an array with the indexes: 
		 
				tag: td or th. td by defalult.
				class: the tag class or classes
				content: the content, by default empty. The order-number will be replaced on rendering time
		 */
		
		public function shipping_rules_table_cells() {
			$cells = array();
			
			$cells['check-column'] = array('tag' => 'th', 'class' => 'check-column', 'content' => '<input type="checkbox" name="select">[rule_type_selector]');
			$cells['order-number'] = array('class' => 'order-number', 'content' => '#');
			$cells['selection-rules-column'] = array('class' => 'selection-rules-column');
			$cells['shipping-costs-column'] = array('class' => 'shipping-costs-column');
			$cells['special-actions-column'] = array('class' => 'special-actions-column');
			$cells['column-handle'] = array('class' => 'handle column-handle', 'content');
		
			$cells['selection-rules-column']['content'] = '<div class="selectors">[selectors]</div><div class="add-selector"><a href="#" class="button button-small add_selector_bt"><span class="dashicons dashicons-plus"></span> ' . esc_html__('Add a selector', 'fish-and-ships') . '</a>[logical_operators]</div>';

			$cells['shipping-costs-column']['content'] = '<p class="fns-extra-rule-helper">' . esc_html__('Extra Rule, will be parsed after shipping rate calculation. It does not use cost fields, please use the special actions', 'fish-and-ships') . '&nbsp;<span class="dashicons dashicons-arrow-right-alt"></span></p>' . 
														  '[cost_input_fields] [cost_method_field]';

			$cells['special-actions-column']['content'] = '<div class="actions">[actions]</div><div class="add-action"><a href="#" class="button button-small"><span class="dashicons dashicons-plus"></span> ' .esc_html__('Add an action', 'fish-and-ships') . '</a></div>';

			return apply_filters('wc_fns_shipping_rules_table_cells', $cells );
		}
		
		/*****************************************************************
			WC getters & Cross WC-version safe functions
		 *****************************************************************/

		/**
		 * Check PHP version and WooCommerce
		 *
		 * @since 1.0.0
		 * @version 1.5
		 */
		function is_wc() {
			if ( version_compare( phpversion(), '7', '<') ) return false;
			if ( !function_exists('WC') || version_compare( WC()->version, '3.0.0', '<') ) return false;
			return true;
		}

		/**
		 * Get product name
		 *
		 * @since 1.0.0
		 * @version 1.3
		 */
		
		function get_name($product) {

			if ( version_compare( WC()->version, '2.0.0', '<' ) ) {

				if (isset($product->name)) return $product->name;
				if (isset($product['data']) && isset($product['data']->post) && isset($product['data']->post->post_title)) return $product['data']->post->post_title;

			} else {
				if ( isset($product['data']) ) return $product['data']->get_name();
			}
			return 'unknown name';
		}

		/**
		 * Get product quantity
		 *
		 * @since 1.0.0
		 */
		public function get_quantity($product) {

			//get product quantity
			if ( 'wdm_bundle_product' === $product['data']->get_type() ) {
				// Support quantity for product bundle	
				$qty = $product['items_quantity'];
			} else {
				$qty = $product['quantity'];
			}
			return $qty;
		}

		/**
		 * Get product weight
		 * Prevent prior PHP 5.5 parse error
		 *
		 * @since 1.0.1
		 */
		function get_weight($product) {
			
			$weight = method_exists ($product[ 'data' ], 'get_weight') ? $product[ 'data' ]->get_weight() : 0;
			if (is_null($weight) || $weight == false || $weight == '') $weight = 0;
			
			return $weight;
		}
		
		/**
		 * This function will generate an unique value if the SKU product are unset
		 *
		 * @since 1.0.0
		 */
		public function get_sku_safe($product) {

			if ($product['data']->get_sku() != '') return $product['data']->get_sku();

			if ($product['data']->get_type() == 'variation') {
				// every variation has his own ID, and parent_id value will group it
				return $product['data']->get_parent_id() . '-wc-fns-sku-' . $product['data']->get_id();
			} else {
				return 'wc-fns-sku-' . $product['data']->get_id();
			}
		}

		/**
		 * This function will return the product ID. On variations, will return the parent product ID.
		 *
		 * @since 1.0.0
		 */
		public function get_real_id($product) {
			if ($product['data']->get_type() == 'variation') {
				// every variation has his own ID, and parent_id value will group it
				return $product['data']->get_parent_id();
			} else {
				return $product['data']->get_id();
			}
		}

		/**
		 * This function will return the product or variation ID
		 *
		 * @since 1.0.4
		 */
		public function get_prod_or_variation_id($product) {
			return $product['data']->get_id();
		}

		/**
		 * Returns product dimensions 
		 *
		 * @since 1.0.0
		 * @version 1.3
		 *
		 * return an array on integers with ordered from big to small dimensions and as is introduced: 
		 
				0:      biggest dimension
				1:      mid dimension
				2:      smallest dimension
				length: the length dimension
				width:  the width dimension
				height: the height dimension
		 */
		function get_dimensions_ordered($product) {
			
			$dimensions = array();
			$dimensions['length'] = floatval( $product[ 'data' ]->get_length() );
			$dimensions['width']  = floatval( $product[ 'data' ]->get_width()  );
			$dimensions['height'] = floatval( $product[ 'data' ]->get_height() );

			$ordered = array($dimensions['length'], $dimensions['width'], $dimensions['height']);

			/* We will order by default, from big to small [I can't imagine why you can need to deactivate, however... ;) ] 
			   Deactivate this will assume length >= width >= height always in product dimensions, 
			   otherwise min/mid/max dimension comparison will make strange things, only if you know what you are doing */
			   if (defined('WC_FNS_SORT_DIMENSIONS') && 'WC_FNS_SORT_DIMENSIONS' == false) {} else { rsort($ordered); }
			
			return array_merge($ordered, $dimensions);
		}
	
		/**
		 * Sanitize the cost fields [max_shipping_price and min_shipping_price] in a non-crashing way with old WC releases
		 *
		 */
		public function sanitize_cost( $value ) {

			return $this->sanitize_number($value, 'decimal');
		}

		/*****************************************************************
			3rd party plugins compatibility
		 *****************************************************************/

		/**
		 * Check if WPML and WC Multilingual are active. Then, if we're on WCML multi-currency
		 *
		 * @since 1.0.0
		 * @version 1.1.0
		 *
		 */
		function check_wpml() {

			// Check if WPML + WCML is actived
			if ( defined('ICL_SITEPRESS_VERSION') && defined('WCML_VERSION') ) {
				$this->is_wpml = true;
			}
		}

		/**
		 * Check 3rd party multi-currency plugins
		 *
		 * @since 1.1.0
		 * @version 1.2.5
		 *
		 */
		function check_multicurrency() {

			global $woocommerce_wpml;
			if ($this->is_wpml && $woocommerce_wpml->settings['enable_multi_currency'] == WCML_MULTI_CURRENCIES_INDEPENDENT) {

				$this->is_wpml_mc = true;
				return;
			}
			
			if ( defined ('WOOCOMMERCE_MULTICURRENCY_VERSION') && version_compare( WOOCOMMERCE_MULTICURRENCY_VERSION, '1.10.0', '>=' ) ) {

				$this->is_woo_mc = true;
				return;
			}
			
			if ( isset( $GLOBALS['WOOCS'] ) ) {
				
				$this->is_woocs = true;
				return;
			}
			
			if ( class_exists( 'WC_Aelia_CurrencySwitcher' ) ) {
				
				$this->is_aelia_mc = true;
				return;
			}
			
			if ( defined ('WOOMULTI_CURRENCY_F_VERSION') ) {

				$this->is_wmc_f = true;
				return;
			}
			
			if ( class_exists( 'Alg_WC_Currency_Switcher' ) ) { 
				$this->is_alg_cs = true;
				return;
			}
		}

		public function is_wpml() {
			return $this->is_wpml;
		}

		public function is_wpml_mc_fn() {
			return $this->is_wpml_mc;
		}

		/**
		 * Get multilingual info, for log
		 *
		 * @since 1.1.0
		 *
		 */
		public function get_multilingual_info() {

			if ( $this->is_wpml() ) return '[WPML], WPML: ['.ICL_SITEPRESS_VERSION.'], WCML: ['.WCML_VERSION.']';
			
			return '[NO]';
		}

		/**
		 * Get multicurrency info, for log
		 *
		 * @since 1.1.0
		 * @version 1.2.5
		 *
		 */
		public function get_multicurrency_info( $shipping_class ) {
			
			$info = '';

			if ( $this->is_wpml_mc ) $info = '[WCML]';
			
			if ( $this->is_woo_mc )  {
				$info = '[WOOMC], WOOMC: [' . WOOCOMMERCE_MULTICURRENCY_VERSION . ']';
			} elseif ( defined('WOOCOMMERCE_MULTICURRENCY_VERSION') ) {
				$info = '[NO], WOOMC: [' . WOOCOMMERCE_MULTICURRENCY_VERSION . ' UNSUPPORTED, min: 1.10.0]';
			}
			if ( $this->is_woocs ) 	 $info = '[WOOCS], WOOCS: [' . WOOCS_VERSION . ']';
			if ( $this->is_aelia_mc )  $info = '[AELIA], AELIA: [' . WC_Aelia_CurrencySwitcher::$version . ']';
			if ( $this->is_wmc_f ) 	 $info = '[WMC_F], WMC_F: ['	. WOOMULTI_CURRENCY_F_VERSION . ']';
			if ( $this->is_alg_cs ) {
						$currency_switcher = Alg_WC_Currency_Switcher::instance();
						$info = '[ALG_CS], ALG_CS: ['	. $version = $currency_switcher->version . ']';
			}
			
			if ( $info != '' ) return $info . ', MAIN: [' . get_option('woocommerce_currency') . '], CURRENT: [' . get_woocommerce_currency() . '], MANUAL RATES PER CURRENCY: [' . mb_strtoupper($shipping_class->multiple_currency) . ']';

			return '[NO]';
		}
		
		/**
		 * Get the main language code: en, es, etc. (not locale like: en_US)
		 *
		 * @since 1.0.0
		 *
		 */
		function get_main_lang() {
			
			// Not multilingual?
			if ( !$this->is_wpml() ) return substr(get_locale(), 0, 2);

			global $sitepress;
			return $sitepress->get_default_language();
		}

		/**
		 * Try to translate text through WPML options way
		 *
		 * @since 1.0.0
		 * @version 1.4.8
		 *
		 */
		public function maybe_translated( $text ) {
			
			if ( !is_array($this->user_texts_translated) ) {
				$this->user_texts_translated = get_option( 'wc-fns-translatable', array() );
			}
			$key = md5( trim( $text ) );
			if (isset($this->user_texts_translated[$key])) return $this->user_texts_translated[$key];

			// Not translated?
			return $text;
		}


		/**
		 * Set the translatable texts into a common option, for WPML user texts translation
		 *
		 * @since 1.0.0
		 *
		 */
		function save_translatables($shipping_rules) {
			
			$translatables = get_option('wc-fns-translatable', array() );

			foreach ($shipping_rules as $rule) {
				foreach ($rule['actions'] as $action) {

					if (isset($action['method'])) {
						$trans_fields = apply_filters( 'wc_fns_get_translatable_action', array(), $action['method'] );

						foreach ($trans_fields as $field) {
							if (isset($action['values']) && isset($action['values'][$field]) && trim($action['values'][$field]) != '') {

								$string_translatable = trim($action['values'][$field]);
								$translatables[ md5($string_translatable) ] = $string_translatable;
							}
						}
					}
				}
			}
			update_option('wc-fns-translatable', $translatables, false );
		}

		/**
		 * Get AJAX URL with the main lang site param (only if needed)
		 *
		 * @since 1.0.0
		 *
		 */
		function get_unlocalised_ajax_url() {
		
			global $sitepress;
		
			$ajax_url = admin_url('admin-ajax.php');
		
			if ( $this->is_wpml() ) {
				$ajax_url = add_query_arg('lang', $this->get_main_lang(), $ajax_url);
			}
			return $ajax_url;
		}
		 
		/**
		 * Check if a product (maybe a variation) are in a term (any taxonomy)
		 *
		 * @since 1.0.0
		 * @version 1.4.14
		 *
		 * @param $product (array, info from the cart, language info aded by FnS)
		 * @param $taxonomy the taxonomy terms to look in for
		 * @param $terms (array of the terms ID (term_id)
		 * @param $shipping_class the instance who calls the function
		 *
		 * @return boolean
		 */
		function product_in_term($product, $taxonomy, $terms, $shipping_class) {

			global $Fish_n_Ships;
			
			$product_terms_id = array();
			
			if ( $taxonomy == 'product_shipping_class' ) {
				
				// Variations can be assigned to distinct shipping class than his parent
				// We will initialise the product object to get his shipping class trough WC
				// And store the only one ID into array in the same way as the other taxonomies
				$product_id = $Fish_n_Ships->get_prod_or_variation_id($product);
				$prod_object = wc_get_product($product_id);
				$product_terms_id = array($prod_object->get_shipping_class_id());

			} elseif ( $taxonomy == 'product_cat' ) {

				// The category taxonomies are assigned to parent products on variations
				$product_id = $Fish_n_Ships->get_real_id($product);
				$prod_object = wc_get_product($product_id);
				$product_terms_id = $prod_object->get_category_ids();

			} elseif ( $taxonomy == 'product_tag' ) {

				// The tag taxonomies are assigned to parent products on variations
				$product_id = $Fish_n_Ships->get_real_id($product);
				$prod_object = wc_get_product($product_id);
				$product_terms_id = $prod_object->get_tag_ids();

			// Fallback, unused after HPOS compatibility
			} else {
				// The other taxonomies are assigned to parent products on variations
				$product_id = $Fish_n_Ships->get_real_id($product);
				$product_terms = get_the_terms($product_id, $taxonomy);
				if ( is_array( $product_terms ) ) {
					foreach ($product_terms as $t) {
						$product_terms_id[] = $t->term_id;
					}
				}
			}
			
			// We will work with lang codes: (en, es, de) etc. NOT locale codes (en_US, es_ES, de_DE) etc.
			if ( $Fish_n_Ships->is_wpml() ) {
				
				// Let's translate (if they are'nt) the product terms into the product language
				$product_terms_id = $this->translate_terms($product_terms_id, $taxonomy, $product['lang']['language_code']);

				// The product isn't on the main lang? Let's translate (if they aren't) the terms to seek
				if ( $product['lang']['language_code'] != $this->get_main_lang() ) {

					$terms = $this->translate_terms($terms, $taxonomy, $product['lang']['language_code']);
				
					$shipping_class->debug_log('. &gt; Untranslated product: #' . $product['data']->get_id() . ' ' . $Fish_n_Ships->get_name($product) . ', language: [' . $product['lang']['display_name'] . ']' , 3 );
					$shipping_class->debug_log('. &gt; so we will turn this terms into ' . $product['lang']['display_name'] . ' to match with it: [' . implode(', ', $terms) . ']', 3);
													
					$shipping_class->debug_log('. &gt; product terms turned to ' . $product['lang']['display_name'] . ': [' . implode(', ', $product_terms_id) . ']', 3);
				}
			}
			
			foreach ($terms as $term_id) {
				if ( in_array($term_id, $product_terms_id) ) return true;
			}
			
			return false;
		}
			
		/**
		 * Get language info of product
		 *
		 */
		function get_lang_info( $product ) {
			return apply_filters( 'wpml_post_language_details', array(), $this->get_real_id($product) );
		}
		
		/**
		 * Translate an array of terms_id from main language to other one (WPML)
		 *
		 * @since 1.0.0
		 *
		 */
		function translate_terms($terms, $taxonomy, $lang) {

			// The key to store into cache
		//	$index_cached = $taxonomy . ($hide_empty ? '-1' : '-0');
		//	if (isset($this->terms_cached[$index_cached])) return $this->terms_cached[$index_cached];

			foreach ($terms as $key=>$term_id) {
				$terms[$key] = apply_filters( 'wpml_object_id', $term_id, $taxonomy, TRUE, $lang );
			}
			return $terms;
		}

		/**
		 * Currency exchange rate abstraction, will convert a price on multicurrency sites,
		 * if needed: from main/cart currency to needed: main/cart currency.
		 *
		 * WOOCS works in main currency by default, but in the cart currency when "multiple allowed" option is set
		 * WMC_F works in main currency and does a currency conversion if needed at the end itself
		 * WPML MC, WOOMC, ALG_WC and Aelia works in the cart currency
		 *
		 * Be aware! Before Fish and Ships 1.1.6 we work in main currency with WPML MC, now in cart currency!
		 *
		 * @param currency_origin 	mixed, possible values: main-currency | cart-currency
		 * @param value 			float, numerical price, currency not explicit
		 *
		 * @since 1.0.5
		 * @version 1.4.3
		 */
		function currency_abstraction ($currency_origin, $value ) {

			/* Before 1.1.6
			if ( $this->is_wpml_mc  && $currency_origin == 'cart-currency' ) {
				// WPML Multicurrency needs to work in the main currency, unconversion is needed
				global $woocommerce_wpml;
				$value = $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( $value );
			}*/

			if ( $this->is_wpml_mc  && $currency_origin == 'main-currency' ) {
				// With WPML Multicurrency, we work since 1.1.6 in cart currency
				return apply_filters( 'wcml_raw_price_amount', $value, get_woocommerce_currency() );
			}

			if ( $this->is_woo_mc && $currency_origin == 'main-currency' ) {

				if ( version_compare( WOOCOMMERCE_MULTICURRENCY_VERSION, '2.0.0', '<' ) ) {
					$rate_storage = new \WOOMC\MultiCurrency\Rate\Storage();
					$price_rounder = new \WOOMC\MultiCurrency\Price\Rounder();
					$currency_detector = new \WOOMC\MultiCurrency\Currency\Detector();
					$price_calculator = new \WOOMC\MultiCurrency\Price\Calculator($rate_storage, $price_rounder);
					$price_controller = new \WOOMC\MultiCurrency\Price\Controller($price_calculator, $currency_detector);
					return $price_controller->convert($value);
				
				} else if ( class_exists('\WOOMC\Rate\Storage') ) {
					$rate_storage = new \WOOMC\Rate\Storage();
					$price_rounder = new \WOOMC\Price\Rounder();
					$currency_detector = new \WOOMC\Currency\Detector();
					$price_calculator = new \WOOMC\Price\Calculator($rate_storage, $price_rounder);
					$price_controller = new \WOOMC\Price\Controller($price_calculator, $currency_detector);
					return $price_controller->convert($value);
				}
			}
			
			if ( $this->is_aelia_mc && $currency_origin == 'main-currency' ) {
				$shop_base_currency = get_option('woocommerce_currency');
				return apply_filters( 'wc_aelia_cs_convert', $value, $shop_base_currency, get_woocommerce_currency() );
			}
					
			if ( $this->is_alg_cs && $currency_origin == 'main-currency' ) {
				if ( function_exists('alg_get_product_price_by_currency') && function_exists('alg_get_current_currency_code') ) {
					return alg_get_product_price_by_currency( $value, alg_get_current_currency_code() );
				}
			}
				

			if ( $this->is_woocs && class_exists('WOOCS') && $currency_origin == 'main-currency' ) {

				global $WOOCS;
				
				if ( $WOOCS->is_multiple_allowed && $WOOCS->default_currency != $WOOCS->current_currency ) {
					/*
					inspired from: classes/compatibility/compatibility.php:
					$booking_price = $WOOCS->back_convert( $booking_price, $currencies[$WOOCS->current_currency]['rate'] );
					*/
					$currencies = $WOOCS->get_currencies();
					return floatval( $currencies[$WOOCS->current_currency]['rate'] ) * floatval( $value );
				}
			}

			return $value;
		}
		
		/**
		 * Gives HTML currency un-abstracted price for the log
		 *
		 * @since 1.1.2
		 * @version 1.1.6
		 *
		 * @param price 			abstracted currency price
		 *
		 * @return $price 			raw html price into cart currency
		 */
		function unabstracted_price( $price ) {
			
			// For WMC_F we calculate in main shop currency
			if ( $this->is_wmc_f ) {
				$price = wmc_get_price($price);
			}
			/* since 1.1.6 we work in cart currency with WPML MC
			if ( $this->is_wpml_mc ) {
				$price = apply_filters( 'wcml_raw_price_amount', $price, get_woocommerce_currency() );
			}
			*/
			
			// For WOOCS we calculate in the main shop currency but it translates itself the price through wc_price() catch
			
			// The other MC plugins works in the cart currency
			return strip_tags( wc_price( $price, array('currency' => get_woocommerce_currency() ) ) );
		}

		/**
		 * Check manual costs for every currency support
		 *
		 * @since 1.1.6
		 * @version 1.4.3
		 *
		 * @return boolean
		 */
		function can_manually_costs_every_currency() {
			
			if ( $this->is_woo_mc || $this->is_aelia_mc || $this->is_wpml_mc || $this->is_alg_cs || $this->is_woocs ) return true;
			
			return false;
		}

		/**
		 * Get currencies on multi-currency sites.
		 *
		 * It will return always an array, like: Array ( [USD] => $ [GBP] => £ [EUR] => € ) 
		 * On mono-currency sites, will return: Array ( [USD] => $ )
		 * The main currency will be returned always on first position
		 *
		 * @since 1.1.6
		 * @version 1.4.3
		 *
		 * @return array
		 */
		function get_currencies() {
			
			$main_currency  = get_option('woocommerce_currency');
			$currencies     = array ( $main_currency );
			
			if ($this->is_aelia_mc) {
				$currencies = apply_filters( 'wc_aelia_cs_enabled_currencies', array( $main_currency ) );
			}

			if ($this->is_wpml_mc) {
				// A stronger way to get the currencies from WCML, supports early Flexible Shipping triggering
				global $woocommerce_wpml;
				$wpml_mc = $woocommerce_wpml->get_multi_currency();
				$currencies = $wpml_mc->get_currencies('include_default = true');
				if ( is_array($currencies) ) $currencies = array_keys($currencies);
			}
			
			if ($this->is_woo_mc) {
				if ( version_compare( WOOCOMMERCE_MULTICURRENCY_VERSION, '2.0.0', '<' ) ) {
					if ( class_exists('\WOOMC\MultiCurrency\DAO\WP') ) {
						$dao = new \WOOMC\MultiCurrency\DAO\WP();
						$currencies = $dao->getEnabledCurrencies();
					}
				} else {
					if ( class_exists('\WOOMC\DAO\WP') ) {
						$dao = new \WOOMC\DAO\WP();
						$currencies = $dao->getEnabledCurrencies();
					}
				}
			}
			
			if ($this->is_alg_cs && function_exists ('alg_get_enabled_currencies') ) {
				$currencies = alg_get_enabled_currencies();
			}
			
			if ( $this->is_woocs && class_exists('WOOCS') ) {
			
				global $WOOCS;
				$woocs_currencies = $WOOCS->get_currencies();

				$result = array ( $main_currency => get_woocommerce_currency_symbol( $main_currency ) );
				
				// WOOCS always return main symbol using get_woocommerce_currency_symbol( 'ANYCURRENCY' ), 
				// so we solve it gettin symbols from his currencies array:
				if ( is_array( $woocs_currencies ) ) {
					foreach ( $woocs_currencies as $currency => $currency_data ) {
						if ( $currency != $main_currency) $result[ $currency ] = $currency_data['symbol'];
					}
				}
				return $result;
			}
			
			// Let's to put the currency/symbol pairs on array
			$result = array ( $main_currency => get_woocommerce_currency_symbol( $main_currency ) );
			
			if ( is_array($currencies) ) {
				foreach ($currencies as $currency) {
					if ( $currency != $main_currency) $result[ $currency ] = get_woocommerce_currency_symbol( $currency );
				}
			}
			return $result;
		}

		/**
		 * Filter on WC_Shipping_Rate class creation
		 * Preventing re-conversion to cart currency when we the calculated rate is on this yet
		 *
		 * @since 1.1.1
		 * @version 1.4.3
		 *
		 * @param $rate 			object of type WC_Shipping_Rate (new created)
		 * @param $args 			array of params
		 * @param shipping_method 	object reference
		 *
		 * @return boolean
		 */
		function extra_params_shipping_rate ( $rate, $args, $shipping_method ) {
			
			// Only on Fish and Ships shipping methods:
			if ( $rate->method_id == $this->id ) {
				
				// Aelia: Add information about our shipping cost currency. It will prevent re-conversion.
				if ($this->is_aelia_mc) {
					$rate->shipping_prices_in_currency = true;
					$rate->currency = get_woocommerce_currency();
				}

				/**********
				  Ugly solution, divide per ratio exchange because can't stop post re-conversion
				 **********/

				// WPML MC
				if ( $this->is_wpml_mc ) {
					if ( get_option('woocommerce_currency') != get_woocommerce_currency() ) {
						global $woocommerce_wpml;
						// Getting ratio exchange: ask in the main currency the 1(cart currency) value
						$ratio = 1 / $woocommerce_wpml->multi_currency->prices->unconvert_price_amount( 1 );
						$rate->cost = $rate->cost / $ratio;
					}
				}

				// WOOCS, only when multiple allowed option is set:
				if ( $this->is_woocs && class_exists('WOOCS') ) {
					global $WOOCS;
					if ( $WOOCS->is_multiple_allowed && $WOOCS->default_currency != $WOOCS->current_currency ) {
						
						$currencies = $WOOCS->get_currencies();
						$ratio = floatval( $currencies[$WOOCS->current_currency]['rate'] );
						$rate->cost = $rate->cost / $ratio;
					}
				}

				// Old releases of WP Wham Multicurrency: 
				if ( $this->is_alg_cs ) {
					
					$currency_switcher = Alg_WC_Currency_Switcher::instance();
					if ( 
						version_compare( $currency_switcher->version, '2.14.0', '<' )
						&& function_exists('alg_get_current_currency_code') 
						&& function_exists('alg_wc_cs_get_currency_exchange_rate') ) {
							
							$currency_code = alg_get_current_currency_code();
							if ( get_option('woocommerce_currency') != $currency_code ) {
								$ratio = alg_wc_cs_get_currency_exchange_rate( $currency_code );
								$rate->cost = $rate->cost / $ratio;
							}
					}
				}
			}
			return $rate;
		}

		/**
		 * Filter to prevent double currency conversion for Wham Multicurrency v2.14.0 and newer
		 *
		 * @since 1.2.6
		 *
		 * @return boolean
		 */
		function alg_cs_maybe_disable_conversion_package_rate ( $is_enabled, $package_rate ) {
			if ( $package_rate->method_id === $this->id ) {
				$is_enabled = false;
			} 
		}
		
		/**
		 * Get price product / 3rd party plugins abstraction
		 *
		 * We will add here the information about our shipping cost currency 
		 * (for 3rd party plugins)
		 *
		 * @since 1.2.5
		 *
		 * - compatible with WPC Product Bundles 
		 *
		 * @param $product 			object of type WC_Product
		 *
		 * @return float			price currency abstracted
		 */
		function get_price ($product) {
			
			// WC products way
			if ( $this->is_wmc_f ) {
				
				// WMC_F needs to work in the main currency and we can get it directly
				$price = $product[ 'data' ]->get_price( 'edit' );
				$price = $this->currency_abstraction('main-currency', $price);

			} else if ( $this->is_alg_cs && function_exists('alg_get_current_currency_code') && function_exists('alg_get_product_price_by_currency') ) {
				
				// ALG_CS prices can be set manually on every currency
				$currency_code  = alg_get_current_currency_code();
				$price   = $product[ 'data' ]->get_price();
				/*
				$alg_way_price = alg_get_product_price_by_currency( 0, $currency_code, $product['data'], true ); 
				$price = $alg_way_price != 0 ? $alg_way_price : $price;
				*/
				$price = $this->currency_abstraction('cart-currency', $price);

			} else {
				// it gives the price in the CART currency
				$price = $product[ 'data' ]->get_price();
				$price = $this->currency_abstraction('cart-currency', $price);

			}
			
			// WPC Product Bundles
			if ( isset( $product['woosb_price'] ) && $product['data']->is_type( 'woosb' ) && ! $product['data']->is_fixed_price() ) {
				$price = $product['woosb_price'];
				// it gives always the price in the main shop currency
				$price = $this->currency_abstraction('main-currency', $price);
			}

			
			return $price;
		}

		/*****************************************************************
			Sanitization
		 *****************************************************************/
		 
		/**
		 * Check the name of the log, comming from request
		 *
		 * @since 1.0.0
		 *
		 * @param $name raw
		 *
		 * @return boolean
		 */
		 function is_log_name($name) {
			
			if ($name !== sanitize_key($name)) return false;

			// The name of the log should start with wc_fns_log_
			if (strpos($name, 'wc_fns_log_') !== 0) return false;
			
			return true;
		 }

		/**
		 * Check if the value is 1 or 0.
		 *
		 */
		 function is_one_or_zero($what) {

			$what = sanitize_key($what);

			if ($what === '1' || $what === '0') return true;
			return false;
		 }

		/**
		 * Check if the method selector is valid
		 *
		 */
		public function is_valid_selector($method_id) {
			
			$method_id = sanitize_key($method_id);
			
			return $this->is_known('selection', $method_id) === true;
		}

		/**
		 * Sanitize the shipping rules from the admin options form (save)
		 *
		 * @since 1.0.0
		 * @version 1.5
		 *
		 * @param $raw_shipping_rules raw stuff from the $_POST object
		 *
		 * @return sanitizied info (array)
		 */
		function sanitize_shipping_rules ($raw_shipping_rules) {
			
			$shipping_rules = array();
			
			foreach ($raw_shipping_rules as $raw_rule) {
				
				if (is_array($raw_rule) && isset($raw_rule['sel'])) {
					
					$rule_type = 'normal'; // fallback
					if ( isset($raw_rule['type']) ) {
						$rule_type = $this->sanitize_allowed( $raw_rule['type'], array('normal', 'extra') );
					}

					/*************** Selection rules ****************/
					$rule_sel  = array();
					$sel_nr    = 0;

					foreach ($raw_rule['sel'] as $key=>$sel) {
						
						$values = array();

						// Only key numbers are really selectors
						if ($key === intval($key)) {
							
							$sel = sanitize_key( $sel );
							
							if (isset($raw_rule['sel'][$sel]) && is_array($raw_rule['sel'][$sel])) {
								foreach ($raw_rule['sel'][$sel] as $field=>$array_val) {

									$field = sanitize_key( $field );
									
									if (isset($array_val[$sel_nr])) $values[$field] = $array_val[$sel_nr];
								}
							}
							
							//Sanitize the selector auxiliary fields
							$sanitized = apply_filters('wc_fns_sanitize_selection_fields', array('method' => $sel, 'values' => $values) );
							if (false !== $sanitized) $rule_sel[] = $sanitized;

							$sel_nr++; //Start counting in 0
						
						} else {
						
							// Also the new logical_operator field is valid (since 1.1.9)
							$key = sanitize_key( $key );
							$sanitized = apply_filters('wc_fns_sanitize_selection_operators', array('method' => $key, 'values' => $sel) );
							if (false !== $sanitized) {
								if ( !isset($rule_sel['operators']) ) $rule_sel['operators'] = array();
								$rule_sel['operators'][] = $sanitized;
							}
						}
					}
					
					/*************** Shipping costs ****************/
					
					$rule_costs = array();

					$cost_values = array();
					foreach ($raw_rule['cost'] as $key => $value) {
						$cost_values[sanitize_key($key)] = (is_array($value) && isset($value[0]) ? $value[0] : 0);
					}

					$cost_method = 'once';
					if (isset($raw_rule['cost_method']) && is_array($raw_rule['cost_method']) && isset($raw_rule['cost_method'][0]) ) 
						$cost_method = sanitize_key($raw_rule['cost_method'][0]);
					
					//Sanitize the cost fields
					$sanitized = apply_filters('wc_fns_sanitize_cost', array ('method' => $cost_method, 'values' => $cost_values) );
					if (false !== $sanitized) $rule_costs[] = $sanitized;
						

					/***************Special actions ***************/
					$rule_actions  = array();
					$action_nr     = 0;
					
					if (isset($raw_rule['actions']) && is_array($raw_rule['actions'])  ) {
						foreach ($raw_rule['actions'] as $key=>$action) {
							
							$values = array();

							// Only key numbers are really actions
							if ($key === intval($key)) {

								$action = sanitize_key( $action );
								
								if (isset($raw_rule['actions'][$action]) && is_array($raw_rule['actions'][$action])) {
									foreach ($raw_rule['actions'][$action] as $field=>$array_val) {

										$field = sanitize_key( $field );

										if (isset($array_val[$action_nr])) $values[$field] = $array_val[$action_nr];
									}
								}
								// Sanitize the action auxiliary fields
								$sanitized = apply_filters('wc_fns_sanitize_action', array('method' => $action, 'values' => $values));
								if (false !== $sanitized) $rule_actions[] = $sanitized;
								
								$action_nr++;
							}
						}
					}

					$shipping_rules[] = array('type' => $rule_type, 'sel' => $rule_sel, 'cost' => $rule_costs, 'actions' => $rule_actions);
				}
			}

			// Ensure that any extra rule it's under any normal rule (required for new snippets wizard)
			usort($shipping_rules, function ($a, $b) {
				if( $a['type'] == $b['type'] ) return 0;
				if( $a['type'] == 'normal' ) return -1;
				return 1;
			});

			return $shipping_rules;
		}

		/**
		 * Sanitize the field, should be in the array of allowed values
		 *
		 * @since 1.0.0
		 *
		 * @param $field (raw) 
		 * @param $allowed (array)
		 *
		 * @return sanitizied field (mixed)
		 *
		 */
		 
		 public function sanitize_allowed($field, $allowed) {
			 
			 if (in_array($field, $allowed, true)) return $field;
			 
			 //fallback, we will return the first allowed value
			 return reset($allowed);
		 }

		/**
		 * Sanitize HTML before save into database
		 *
		 * @since 1.0.0
		 *
		 * @param $field (raw) 
		 *
		 * @return sanitizied field (html)
		 *
		 */
		 
		 public function sanitize_html($field) {
			 
			 return wp_kses_post( wp_unslash( $field ) );
		 }

		/**
		 * Sanitize text before save into database
		 *
		 * @since 1.0.0
		 *
		 * @param $field (raw) 
		 *
		 * @return sanitizied field (html)
		 *
		 */
		 
		 public function sanitize_text($field) {
			 
			 return sanitize_text_field( wp_unslash( $field ) );
		 }

		/**
		 * Sanitize textarea before save into database
		 *
		 * @since 1.2.9
		 *
		 * @param $field (raw) 
		 *
		 * @return sanitizied field (html)
		 *
		 */
		 
		 public function sanitize_textarea($field) {
			 
			 return sanitize_textarea_field( wp_unslash( $field ) );
		 }

		/**
		 * Sanitize array of keys
		 *
		 * @since 1.5
		 *
		 * @param $array (array) 
		 *
		 * @return sanitizied array of keys (array)
		 *
		 */
		 
		 public function sanitize_array_of_keys( $array ) {
			 
			 if( ! is_array( $array ) )
				 return array();
			 
			 return array_map( 'sanitize_key', $array );
		 }

		/**
		 * Sanitize the numbers from form fields in the same way as WC does prior to database storage
		 *
		 * integers haven't decimals
		 * support for WC decimal separator (comma, point, whatever) 
		 *
		 * @since 1.0.0
		 * @version 1.4.9
		 *
		 * @param $number (raw) 
		 * @param $type (string) can be: integer | positive-integer | decimal | positive-decimal
		 *
		 * @return sanitizied number (integer)
		 *
		 */

		public function sanitize_number($number, $type = 'unknown') {
			
			if (is_array($number)) {
				trigger_error('Fish n Ships -> sanitize_number(): expects number, not array: ' . print_r($number, true) );
			
				return 0;
			}
			
			$number = wc_clean( wp_unslash( $number ) );

			// (float) prevents PHP8 fatal error on text value ''
			
			switch ($type) {
				
				case 'integer':
					$number = intval(wc_format_decimal ($number, 0)); // decimals will be removed
					break;

				case 'positive-integer':
					$number = intval(wc_format_decimal ($number, 0)); // decimals will be removed
					if ($number < 0) $number = $number * -1;
					break;

				//case 'price':
				case 'decimal':
					$number = (float) wc_format_decimal ($number); // decimals will be kept
					break;
				
				//case 'positive-price':
				case 'positive-decimal':
					$number = (float) wc_format_decimal ($number); // decimals will be kept
					if ($number < 0) $number = $number * -1;
					break;
				
				case 'id':
					$number = (float) $number;
					$number = trim($number) == intval($number) ? intval($number) : 0;
					if ($number < 0) $number = 0;
					break;

				default:
					trigger_error('Fish n Ships -> sanitize_number(): expects a known type of number: ' . $type);
			}
			
			return $number;
		}
		
		/**
		 * Sanitize string as key. Unlike sanitize_key(), it allow upper case letters
		 *
		 * This is need for example for user roles, because WP allow upper case letters in the role ID
		 *
		 * @since 1.4.16
		 *
		 * @param $key (string) 
		 *
		 * @return sanitizied camelcase key (string)
		 *
		 */

		function sanitize_camelcase( $key ) {
			
			$sanitized = '';

			if ( is_scalar( $key ) ) {
				$sanitized = $key;
				$sanitized = preg_replace( '/[^A-Za-z0-9_\-]/', '', $sanitized );
			}

			return $sanitized;
		}

		/**
		 * Format the numbers from database to form fields in the same way as WC does
		 *
		 * integers haven't decimals
		 * support for WC decimal separator (comma, point, whatever) 
		 *
		 * @since 1.0.0
		 * @version 1.4.9
		 *
		 * @param $number (raw) 
		 * @param $type (string) can be: integer | positive-integer | decimal | positive-decimal
		 *
		 * @return localized and maybe escaped number (html ready string)
		 *
		 */

		public function format_number($number, $type = '[empty]') {
			
			switch ($type) {
				
				case 'integer':
				case 'positive-integer':
					$number = intval ($number);
					break;

				case 'decimal':
				case 'positive-decimal':
					$number = wc_format_localized_decimal ($number);
					break;
				
				default:
					trigger_error('Fish n Ships -> format_number(): expects a known type of number: ' . $type);
			}
			
			return esc_attr($number);
		}

		/*****************************************************************
			Groups
		 *****************************************************************/

		/**
		 * Get the options to populate the select group-by
		 *
		 * @since 1.0.0
		 *
		 * @return options (array)
		 */
		function get_group_by_options () {
			
			$options = array(
								// Will be HTML escaped later
				'none'       => _x( 'None [no grouping]', 'cart objects group-by option', 'fish-and-ships' ),
				'id_sku'     => _x( 'Per ID / SKU', 'cart objects group-by option', 'fish-and-ships' ),
				'product_id' => _x( 'Per product [group variations]', 'cart objects group-by option', 'fish-and-ships' ),
				'class'      => _x( 'Per shipping class', 'cart objects group-by option', 'fish-and-ships' ),
				'all'        => _x( 'All grouped together', 'cart objects group-by option', 'fish-and-ships' ),
			);
			
			return apply_filters('wc_fns_get_group_by_options', $options );
		}

		/**
		 * Unmatch group and his elements recursively into all groups
		 *
		 * @since 1.0.0
		 *
		 * @param $what_group  (reference) group reference
		 * @param $rule_groups (array) group reference set
		 *
		 * @return nothing 
		 *
		 */
		public function unmatch_group($what_group, $rule_groups) {

			// unmatch this group
			$unmatched = $what_group->get_elements();
			$what_group->unmatch_this_group();
					
			foreach ($rule_groups as $group_by=>$groups_of_groups) {
				foreach ($groups_of_groups as $subindex=>$group) {
					$group->unmatch_elements($unmatched);
				}
			}
		}

		/**
		 * Check if some group has changed
		 *
		 * @since 1.0.0
		 *
		 * @param $rule_groups (array) group set
		 *
		 * @return boolean 
		 *
		 */
		public function somegroup_changed($rule_groups) {
			
			$reply = false;
					
			foreach ($rule_groups as $group_by=>$groups_of_groups) {
				foreach ($groups_of_groups as $subindex=>$group) {
					if ($group->is_changed()) $reply = true;
				}
			}
			return $reply;
		}

		/**
		 * Check if some group matching
		 *
		 * @since 1.0.0
		 *
		 * @param $rule_groups (array) group set
		 *
		 * @return boolean 
		 *
		 */
		public function somegroup_matching($rule_groups) {
			
			$reply = false;
					
			foreach ($rule_groups as $group_by=>$groups_of_groups) {
				foreach ($groups_of_groups as $subindex=>$group) {
					if ($group->is_match()) $reply = true;
				}
			}
			return $reply;
		}

		/**
		 * Reset the groups if it has been changed (before reevaluation)
		 *
		 * @since 1.1.5
		 *
		 * @param $rule_groups (array) group set
		 *
		 */
		public function reset_groups($rule_groups) {
							
			foreach ($rule_groups as $group_by=>$groups_of_groups) {
				foreach ($groups_of_groups as $subindex=>$group) {
					$group->reset_if_changed();
				}
			}
		}

		/**
		 * Collect all non-unmatched products from all groups
		 *
		 * @since 1.0.0
		 * @version 1.1.9
		 *
		 * @param $rule_groups (array) group set
		 * @param $shipping_class (class reference)
		 * @param $logical_operator and | or
		 * @param $mute_log (boolean)
		 *
		 * @return collected products 
		 *
		 */
		public function get_selected_contents($rule_groups, $shipping_class, $logical_operator = 'and', $mute_log = false) {
			
			$elements = array();
			
			foreach ($rule_groups as $group_by=>$groups_of_groups) {

				if ($mute_log || $shipping_class->write_logs !== true) {
					// No writing log? We will save some resources here

					foreach ($groups_of_groups as $subindex=>$group) {
						
						// on OR logic, the matching groups has been flagged:
						if ( $logical_operator == 'or' && $group->or_flag !== true ) continue;
						
						// we use the overwrite feature of array_merge on coincident keys to avoid duplications
						$elements = array_merge($elements, $group->get_elements() );
					}
				
				} else if ($group_by == 'none') {
					// Let's show non grouped

					foreach ($groups_of_groups as $subindex=>$group) {

						// on OR logic, the matching groups has been flagged:
						if ( $logical_operator == 'or' && $group->or_flag !== true ) continue;

						// we use the overwrite feature of array_merge on coincident keys to avoid duplications
						$elements = array_merge($elements, $group->get_elements() );
					}

					$shipping_class->debug_log('Non-grouped > items: ' . count($elements), 3);

					foreach ($elements as $p) {
						$shipping_class->debug_log ('. ' . $this->get_name($p) . ' (' . $this->get_quantity($p) . ')', 4);
					}
				
				} else {
					// Show grouped
				
					foreach ($groups_of_groups as $subindex=>$group) {

						// on OR logic, the matching groups has been flagged:
						if ( $logical_operator == 'or' && $group->or_flag !== true ) continue;
						
						$i = $group->get_elements();
						// we use the overwrite feature of array_merge on coincident keys to avoid duplications
						$elements = array_merge($elements, $i );
						
						$shipping_class->debug_log($group_by . ' > ' . $subindex . ' > items: ' . count($i), 3);
						
						foreach ($i as $p) {
							$shipping_class->debug_log ('. ' . $this->get_name($p) . ' (' . $this->get_quantity($p). ')', 4);
						}
					}
				}
			}
			return $elements;
		}

		/**
		 * Count all matched groups
		 *
		 * @since 1.0.0
		 *
		 * @param $rule_groups (array) group set
		 *
		 * @return integer groups count 
		 *
		 */
		public function get_matched_groups($rule_groups) {
			
			$matched_groups = 0;

			foreach ($rule_groups as $group_by=>$groups_of_groups) {
				foreach ($groups_of_groups as $subindex=>$group) {
					if ( count($group->elements) > 0) {
						
						if ($group->group_by == 'none') {

							/* If there is more than one qty of same product and we select non-grouped, 
								we should considere the quantity as groups count */
							foreach ($group->elements as $el) {
								$matched_groups += $this->get_quantity($el);
							}

						} else {
							$matched_groups ++;
						}
					}
				}
			}
			return $matched_groups;
		}


		/*****************************************************************
			HTML Helpers
		 *****************************************************************/
		/**
		 * Get (currency/multicurrency) field HTML code
		 *
		 * @since 1.4.0
		 * @version 1.4.6
		 *
		 * @param $field_name (string) the field name
		 * @param $rule_nr (integer) rule ordinal (starting 0)
		 * @param $sel_nr (integer) selector ordinal inside rule (starting 0)
		 * @param $method_id (mixed) method id
		 * @param $values (array) for populate fields
		 * @param $ambit_field (mixed) for class reference only
		 * @param $ambit(mixed) for class reference only
		 * @param $tips (string) field related helper tip
		 * @param $positive_only (boolean) true by default
		 *
		 * @return $html (HTML code) form code for the currency/multicurrency input field
		 *
		 */
		public function get_multicurrency_field_html($field_name, $rule_nr, $sel_nr, $method_id, $values, $ambit_field='sel', $ambit='selection', $tips = 'val_info', $positive_only = true) {
			
			// Securing output
			$field_name    = esc_attr($field_name);
			$rule_nr       = intval($rule_nr);
			$sel_nr        = intval($sel_nr);
			$method_id     = esc_attr($method_id);
			$ambit_field   = esc_attr($ambit_field);
			$ambit         = esc_attr($ambit);
			$tips          = esc_attr($tips);

			$currencies = $this->get_currencies();

			$html = '<span class="currency-switcher-fns-wrapper">';
			$n = 0;
			foreach ( $currencies as $currency=>$symbol ) {

				$n++;
				// Main currency haven't sufix, it brings legacy with previous releases
				$lang_sufix = ''; if ( $n > 1 ) $lang_sufix = $currency;
				
				$html .= '<span class="currency-fns-field currency-' . $currency . ($n==1 ? ' currency-main' : '') . '">';
				$html .= $this->get_decimal_field( $field_name, $rule_nr, $sel_nr, $method_id, $lang_sufix, $symbol, $values, $ambit_field, $ambit, $tips, '', $positive_only );
				$html .= '</span>';
			}
			$html .= '</span>';
										
			return $html;
		}

		/**
		 * Get the min - max HTML code (without LE, LESS, GREATER, GE comparison)
		 *
		 * @since 1.0.0
		 * @version 1.1.6
		 *
		 * @param $rule_nr (integer) rule ordinal (starting 0)
		 * @param $sel_nr (integer) selector ordinal inside rule (starting 0)
		 * @param $method_id (mixed) method id
		 * @param $units for the input (mixed)
		 * @param $values (array) for populate fields
		 * @param $ambit_field (mixed) for class reference only
		 * @param $ambit(mixed) for class reference only
		 * @param $tips (string) field related helper tip
		 * @param currency_fields(boolean) can be: false (default value) | true. It's a currency fields (maybe multicurrency)
		 *
		 * @return $html (HTML code) form code for the fields min / max
		 *
		 */
		public function get_min_max_html($rule_nr, $sel_nr, $method_id, $units, $values, $ambit_field='sel', $ambit='selection', $tips = 'val_info', $currency_fields = false) {
			
			// Securing output
			$rule_nr       = intval($rule_nr);
			$sel_nr        = intval($sel_nr);
			$method_id     = esc_attr($method_id);
			$units         = strip_tags($units, 'sup, sub');
			$ambit_field   = esc_attr($ambit_field);
			$ambit         = esc_attr($ambit);
			$tips          = esc_attr($tips);

			if ($currency_fields) $currencies = $this->get_currencies();

			$html =   '<span class="envelope-fields">'
					. '<span class="field field-min '.$ambit.'-' . $method_id . ' '.$ambit.'-' . $method_id . '-min" data-input-name="min">' . esc_html_x('Min:', 'label, shorted, for minimum input number', 'fish-and-ships') . ' ';
				
					/*
					. '<input type="text" name="shipping_rules[' . $rule_nr . ']['.$ambit_field.']['.$method_id.'][min]['.$sel_nr.']" size="4"'
					. (isset($values['min']) ? (' value="' . esc_attr($values['min']) . '"') : '') . ' data-wc-fns-tip="i18n_min_' . $tips . '"'
					. ' class="wc_fns_input_decimal wc_fns_input_tip" placeholder="0" autocomplete="off"><span class="units">'.$units.'</span>'*/

			if ($currency_fields) {
				$html .= '<span class="currency-switcher-fns-wrapper">';
				$n = 0;
				foreach ( $currencies as $currency=>$symbol ) {

					$n++;
					// Main currency haven't sufix, it brings legacy with previous releases
					$lang_sufix = ''; if ( $n > 1 ) $lang_sufix = $currency;
					
					$html .= '<span class="currency-fns-field currency-' . $currency . ($n==1 ? ' currency-main' : '') . '">';
					$html .= $this->get_decimal_field('min', $rule_nr, $sel_nr, $method_id, $lang_sufix, $symbol, $values, $ambit_field, $ambit, $tips, '');
					$html .= '</span>';
				}
				$html .= '</span>';
			} else {

				$html .= $this->get_decimal_field('min', $rule_nr, $sel_nr, $method_id, '', $units, $values, $ambit_field, $ambit, $tips, '');
			}
			
			$html .= '</span><span class="field field-max '.$ambit.'-' . $method_id . ' '.$ambit.'-' . $method_id . '-max" data-input-name="max">' . esc_html_x('Max:', 'label, shorted, for maximum input number', 'fish-and-ships') . ' ';
			
					/*
					. '<input type="text" name="shipping_rules[' . $rule_nr . ']['.$ambit_field.']['.$method_id.'][max]['.$sel_nr.']" size="4"'
					. (isset($values['max']) ? (' value="' . esc_attr($values['max']) . '"') : '') . ' data-wc-fns-tip="i18n_max_' . $tips . '"'
					. ' class="wc_fns_input_decimal wc_fns_input_tip" placeholder="[no max]" autocomplete="off"><span class="units">'.$units.'</span>' */
					
			if ($currency_fields) {
				$html .= '<span class="currency-switcher-fns-wrapper">';
				$n = 0;
				foreach ( $currencies as $currency=>$symbol ) {

					$n++;
					$lang_sufix = ''; if ( $n > 1 ) $lang_sufix = $currency;
					
					$html .= '<span class="currency-fns-field currency-' . $currency . ($n==1 ? ' currency-main' : '') . '">';
					$html .= $this->get_decimal_field('max', $rule_nr, $sel_nr, $method_id, $lang_sufix, $symbol, $values, $ambit_field, $ambit, $tips, '');
					$html .= '</span>';
				}
				$html .= '</span>';
			} else {
				$html .= $this->get_decimal_field('max', $rule_nr, $sel_nr, $method_id, '', $units, $values, $ambit_field, $ambit, $tips, '');
			}
			
			$html .= '</span></span>';
				
			return $html;
		}

		/**
		 * Get the min - max HTML code used in most selection methods detail
		 * With LE, LESS, GREATER, GE comparison
		 *
		 * @since 1.1.4
		 * @version 1.1.6
		 *
		 * @param $rule_nr (integer) rule ordinal (starting 0)
		 * @param $sel_nr (integer) selector ordinal inside rule (starting 0)
		 * @param $method_id (mixed) method id
		 * @param $units for the input (mixed)
		 * @param $values (array) for populate fields
		 * @param $ambit_field (mixed) for class reference only
		 * @param $ambit(mixed) for class reference only
		 * @param $tips (string) field related helper tip
		 * @param $min_comp_default(string) can be: ge (default value) | greater
		 * @param $max_comp_default(string) can be: less (default value) | le
		 * @param currency_fields(boolean) can be: false (default value) | true. It's a currency fields (maybe multicurrency)
		 *
		 * @return $html (HTML code) form code for the fields min / max
		 *
		 */
		public function get_min_max_comp_html($rule_nr, $sel_nr, $method_id, $units, $values, $ambit_field='sel', $ambit='selection', $tips = 'val_info', $min_comp_default = 'ge', $max_comp_default = 'less', $currency_fields = false) {
			
			// Securing output
			$rule_nr       = intval($rule_nr);
			$sel_nr        = intval($sel_nr);
			$method_id     = esc_attr($method_id);
			$units         = strip_tags($units, 'sup, sub');
			$ambit_field   = esc_attr($ambit_field);
			$ambit         = esc_attr($ambit);
			$tips          = esc_attr($tips);
			
			if ($currency_fields) $currencies = $this->get_currencies();

			// Comparison values
			$min_comp      = esc_attr ( isset( $values['min_comp'] ) ? $values['min_comp'] : $min_comp_default );
			$max_comp      = esc_attr ( isset( $values['max_comp'] ) ? $values['max_comp'] : $max_comp_default );

			$html =   '<span class="envelope-fields">'
					. '<span class="field field-min '.$ambit.'-' . $method_id . ' '.$ambit.'-' . $method_id . '-min" data-input-name="min">'
					. '<span class="with_icons_label_wrap">'
					. '<span class="label">' . esc_html_x('Min:', 'label, shorted, for minimum input number', 'fish-and-ships') . '</span>'
					. '<span class="comp_icon icon_ge ' . ($min_comp=='ge' ? 'on' : '') . ' woocommerce-help-tip" data-tip="'.esc_html__('Set GREATER THAN OR EQUAL TO comparison', 'fish-and-ships').'"></span>'
					. '<span class="comp_icon icon_greater ' . ($min_comp=='greater' ? 'on' : '') . '  woocommerce-help-tip" data-tip="'.esc_html__('Set GREATER THAN comparison', 'fish-and-ships').'"></span>'
					. '</span>'
					. '<input type="hidden" name="shipping_rules[' . $rule_nr . ']['.$ambit_field.']['.$method_id.'][min_comp]['.$sel_nr.']" value="' . esc_attr($min_comp) . '" class="comparison_way" />';
					/*. '<input type="text" name="shipping_rules[' . $rule_nr . ']['.$ambit_field.']['.$method_id.'][min]['.$sel_nr.']" size="4"'
					. (isset($values['min']) ? (' value="' . esc_attr($values['min']) . '"') : '') . ' data-wc-fns-tip="i18n_' . $tips . '_' . esc_attr($min_comp) . '"'
					. ' class="wc_fns_input_decimal wc_fns_input_tip" placeholder="0" autocomplete="off">';*/
			
			if ($currency_fields) {
				$html .= '<span class="currency-switcher-fns-wrapper">';
				$n = 0;
				foreach ( $currencies as $currency=>$symbol ) {

					$n++;
					// Main currency haven't sufix, it brings legacy with previous releases
					$lang_sufix = ''; if ( $n > 1 ) $lang_sufix = $currency;

					$html .= '<span class="currency-fns-field currency-' . $currency . ($n==1 ? ' currency-main' : '') . '">';
					$html .= $this->get_decimal_field('min', $rule_nr, $sel_nr, $method_id, $lang_sufix, $symbol, $values, $ambit_field, $ambit, $tips, $min_comp);
					$html .= '</span>';
				}
				$html .= '</span>';
			} else {
				$html .= $this->get_decimal_field('min', $rule_nr, $sel_nr, $method_id, '', $units, $values, $ambit_field, $ambit, $tips, $min_comp);
			}
			
			$html .= '</span><span class="field field-max '.$ambit.'-' . $method_id . ' '.$ambit.'-' . $method_id . '-max" data-input-name="max">'
					. '<span class="with_icons_label_wrap">'
					. '<span class="label">' . esc_html_x('Max:', 'label, shorted, for maximum input number', 'fish-and-ships') . '</span>'
					. '<span class="comp_icon icon_less ' . ($max_comp=='less' ? 'on' : '') . ' woocommerce-help-tip" data-tip="'.esc_html__('Set LESS THAN comparison', 'fish-and-ships').'"></span>'
					. '<span class="comp_icon icon_le ' . ($max_comp=='le' ? 'on' : '') . ' woocommerce-help-tip" data-tip="'.esc_html__('Set LESS THAN OR EQUAL TO comparison', 'fish-and-ships').'"></span>'
					. '</span>'
					. '<input type="hidden" name="shipping_rules[' . $rule_nr . ']['.$ambit_field.']['.$method_id.'][max_comp]['.$sel_nr.']" value="' . esc_attr($max_comp) . '" class="comparison_way" />';
					/*. '<input type="text" name="shipping_rules[' . $rule_nr . ']['.$ambit_field.']['.$method_id.'][max]['.$sel_nr.']" size="4"'
					. (isset($values['max']) ? (' value="' . esc_attr($values['max']) . '"') : '') . ' data-wc-fns-tip="i18n_' . $tips . '_' . esc_attr($max_comp) . '"'
					. ' class="wc_fns_input_decimal wc_fns_input_tip" placeholder="[no max]" autocomplete="off"><span class="units">'.$units.'</span>'; */

			if ($currency_fields) {
				$html .= '<span class="currency-switcher-fns-wrapper">';
				$n=0;
				foreach ( $currencies as $currency=>$symbol ) {
					
					$n++;
					$lang_sufix = ''; if ( $n > 1 ) $lang_sufix = $currency;
					
					$html .= '<span class="currency-fns-field currency-' . $currency . ($n==1 ? ' currency-main' : '') . '">';
					$html .= $this->get_decimal_field('max', $rule_nr, $sel_nr, $method_id, $lang_sufix, $symbol, $values, $ambit_field, $ambit, $tips, $max_comp);
					$html .= '</span>';
				}
				$html .= '</span>';
			} else {
				$html .= $this->get_decimal_field('max', $rule_nr, $sel_nr, $method_id, '', $units, $values, $ambit_field, $ambit, $tips, $max_comp);
			}
			
			$html .= '</span></span>';
				
			return $html;
		}
		
		/**
		 * Get a decimal field (min or max field), with or without currency especific for duplication
		 * With the unit
		 *
		 * @since 1.1.6
		 * @version 1.4.9
		 *
		 * @param $field_name (string) the field name
		 * @param $rule_nr (integer) rule ordinal (starting 0)
		 * @param $sel_nr (integer) selector ordinal inside rule (starting 0)
		 * @param $method_id (mixed) method id
		 * @param lang_sufix used on multicurrency: EUR, USD, etc. Be aware! main currency is empty for legacy
		 * @param $units for the input (mixed)
		 * @param $values (array) for populate fields
		 * @param $ambit_field (mixed) for class reference only
		 * @param $ambit(mixed) for class reference only
		 * @param $tips (string) field related helper tip
		 * @param $comp (string) in comparison fields with LE/E/GE/G helper variation tip
		 * @param $positive_only (boolean) true by default
		 *
		 * @return $html (HTML code) form code for the fields min / max
		 *
		 */
		function get_decimal_field( $field_name, $rule_nr, $sel_nr, $method_id, $lang_sufix, $units, $values, $ambit_field, $ambit, $tips, $comp, $positive_only=true ) {
			
			if ($lang_sufix != '' ) $lang_sufix = '-' . $lang_sufix;
			if ($comp != '') $comp = '_' . $comp;
			
			$value = '';
			if ( isset($values[$field_name . $lang_sufix]) ) $value = $values[$field_name . $lang_sufix];
			/*} else {
				// For legacy, if we're on main currency field, and there is a previous saved 
				// non-multiplied field value, let's get it
				if ( $lang_sufix == '-' . get_woocommerce_currency() && isset( $values[$field_name] ) ) {
					$value = $values[$field_name];
				} else {
					// Or maybe the shipping method was saved on multicurrency way and now it has been dissabled
					if ( $lang_sufix == '' && isset( $values[ $field_name . '-' . get_woocommerce_currency() ] ) ) {
						$value = $values[ $field_name . '-' . get_woocommerce_currency() ];
					}
				}			
			}*/

			$html = '<input type="text" name="shipping_rules[' . $rule_nr . ']['.$ambit_field.']['.$method_id.']['.$field_name.$lang_sufix.']['.$sel_nr.']" size="4" value="' . esc_attr( $this->format_number( $value, $positive_only ? 'positive-decimal' : 'decimal' ) ) . '" data-wc-fns-tip="i18n_'.$field_name.'_' . $tips . esc_attr($comp) . '"'
					. ' class="' . ( $positive_only ? 'wc_fns_input_positive_decimal' : 'wc_fns_input_decimal' ) . ' wc_fns_input_tip" placeholder="0" autocomplete="off">';
			
			$html .=  '<span class="units">'.$units.'</span>';
			
			return $html;
		}

		/**
		 * Get textarea field
		 *
		 * @since 1.4.13
		 *
		 * @param $field_name (string) the field name
		 * @param $rule_nr (integer) rule ordinal (starting 0)
		 * @param $sel_nr (integer) selector ordinal inside rule (starting 0)
		 * @param $method_id (mixed) method id
		 * @param $value for populate the field
		 * @param $ambit_field (mixed) for class reference only
		 * @param $tips (string) field related helper tip
		 * @param $placeholder (string) placeholder text
		 *
		 * @return $html (HTML code) form code for the fields min / max
		 *
		 */
		function get_textarea_field( $field_name, $rule_nr, $sel_nr, $method_id, $value, $ambit_field, $tips, $placeholder='')
		{
			if( is_array( $value ) )
				$value = reset($value);

			$html = '<textarea name="shipping_rules[' . $rule_nr . ']['.$ambit_field.']['.$method_id.']['.$field_name.']['.$sel_nr.']" 
						data-wc-fns-tip="i18n_'.$field_name.'_' . $tips . '"' . ' class="wc_fns_input_tip" placeholder="' . esc_attr($placeholder) . '" autocomplete="off">';
			
			$html .= print_r($value, true) . '</textarea>';
				
			return $html;
		}

		/**
		 * Get a multiple selector field
		 *
		 * @since 1.0.0
		 * @version 1.4.13
		 *
		 * @param $rule_nr (integer) rule ordinal (starting 0)
		 * @param $sel_nr (integer) selector ordinal inside rule (starting 0)
		 * @param $method_id (mixed) method id
		 * @param $datatype (mixed) the data type which we will offer values ( user_roles or taxonomy )
		 * @param $values (array) for populate fields
		 * @param $field_name (mixed) select name field
		 * @param $ambit_field (mixed) for class reference only
		 * @param $ambit(mixed) for class reference only
		 *
		 * @return $html (HTML code) form code for the fields min / max
		 *
		 */
		public function get_multiple_html($rule_nr, $sel_nr, $method_id, $datatype, $values, $field_name, $ambit_field='sel', $ambit='selection') {
			
			global $Fish_n_Ships, $Fish_n_Ships_Shipping;

			// Securing output
			$rule_nr       = intval($rule_nr);
			$sel_nr        = intval($sel_nr);
			$method_id     = esc_attr($method_id);
			$field_name    = esc_attr($field_name);
			$ambit_field   = esc_attr($ambit_field);
			$ambit         = esc_attr($ambit);

			$html = '<span class="field field-multiple '.$ambit.'-'.$method_id.' '.$ambit.'-'.$method_id.'-'.$field_name.'">
					<select multiple="multiple" class="multiselect chosen_select" autocomplete="off" required  
					name="shipping_rules['.$rule_nr.']['.$ambit_field.']['.$method_id.']['.$field_name.']['.$sel_nr.'][]">';

			if ( $datatype == 'user_roles' ) {
				
				$options = $Fish_n_Ships->get_wp_user_roles();
			
			} elseif ( $datatype == 'date_weekday' ) {
				
				$options = array ( __('Sunday'), __('Monday'), __('Tuesday'), __('Wednesday'), __('Thursday'), __('Friday'), __('Saturday') );
				
			} elseif ( $datatype == 'date_month' ) {
				
				// We want to January starts in 1, not 0
				$options = array ( '', __('January'), __('February'), __('March'), __('April'), __('May'), __('June'),
								   __('July'), __('August'), __('September'), __('October'), __('November'), __('December') );

				unset ($options[0]);

			} elseif ( $datatype == 'zone_regions' && $this->im_pro() ) {
								
				$options = $Fish_n_Ships_Shipping->get_zone_regions();

			} else {
				
				// It's a taxonomy
				$options = $Fish_n_Ships->get_terms($datatype);
			}
			
			foreach ($options as $id => $caption) {

				$selected = (in_array($id, $values)) ? ' selected ' : '';

				$html .= '<option value="' . esc_attr($id) . '"'.$selected .'>' . esc_html($caption) . '</option>';
			}
			$html .= '</select></span>';

			return $html;
		}

		/**
		 * Get the rule type selector HTML 
		 *
		 * @since 1.4.0	
		 *
		 * @param $rule_nr (integer) rule number
		 * @param $rule_type (string) allwed normal (default & fallback for previous releases) | extra
		 *
		 * @return $html (HTML code) form code for the type selector
		 *
		 */
		function get_rule_type_selector_html($rule_nr, $rule_type = 'normal') {

			// Securing output
			$rule_nr = intval($rule_nr);
			
			$rule_type = $this->sanitize_allowed( $rule_type, array('normal', 'extra') );
			
			$html  = '<input type="hidden" name="shipping_rules['.$rule_nr.'][type]" value="' . esc_attr($rule_type) . '" class="rule_type_selector" />';
			
			return $html;
		}

		/**
		 * Get the selector method HTML 
		 *
		 * @since 1.0.0
		 * @version 1.4.4
		 *
		 * @param $rule_nr (integer) rule number
		 * @param $selection_methods who will populate it (array)
		 * @param $sel_method_id (string) the option selected
		 *
		 * @return $html (HTML code) form code for the selector
		 *
		 */
		function get_selector_method_html($rule_nr, $selection_methods, $sel_method_id = '') {

			// Securing output
			$rule_nr = intval($rule_nr);
					
			$html = '<div class="selection_wrapper"><span class="helper"></span>
						<select name="shipping_rules[' . $rule_nr . '][sel][]" class="wc-fns-selection-method" required>
							<option value="">' . esc_html__('Select one criterion', 'fish-and-ships') . '</option>';
			
			// Now we need to group the selector methods
			$groups_index     = array();
			$grouped_methods  = array();
			$ungrouped_count  = 0;
			
			foreach ($selection_methods as $method_id=>$method) 
			{
								
				if ( isset($method['group']) ) {
					$group_name = $method['group'];
				} else {
					// Fallback & ungrouped
					$ungrouped_count++;
					$group_name = 'ungrouped_' . $ungrouped_count;
				}
				
				if ( !in_array( $group_name, $groups_index ) ) {
					$groups_index[]     = $group_name;
					$grouped_methods[]  = array();
				}

				$pos = array_search( $group_name, $groups_index );
				
				$method['method_id'] = $method_id;
				$grouped_methods[$pos][] = $method;
			}
			
			foreach ($grouped_methods as $key=>$group)
			{
				if (count($group) > 1) {
					$html .= '<optgroup label="' . esc_attr( $groups_index[$key] ) . '">';
				}
				
				foreach ( $group as $method ) 
				{				

					$class = array( 'normal' ); // fallback scope for previous version methods
					if ( isset($method['scope']) ) $class = $method['scope'];
					
					if (!$this->im_pro() && $method['onlypro']) {
	
						$class[] = 'only_pro';
	
						$html .= '<option value="pro" ';
							if ( $sel_method_id == $method['method_id'] ) $html .= 'selected ';
						$html .= 'class="' . esc_attr( implode( ' ', $class) ) . '">' . esc_html($method['label'] . ' [PRO]') . '</option>';
	
					} else {
							$html .= '<option value="' . esc_attr( $method['method_id'] ) . '" ';
							if ($sel_method_id == $method['method_id']) $html .= 'selected ';
						$html .= 'class="' . esc_attr( implode( ' ', $class) ) . '">' . esc_html($method['label']) . '</option>';
					}
				}
				if (count($group) > 1) {
					$html .= '</optgroup>';
				}
			}
		
			$html .= '	</select>
						<div class="selection_details">[selection_details]</div>
						<a href="#" class="delete" title="' . esc_attr_x('Remove selector', 'button caption', 'fish-and-ships') . '"><span class="dashicons dashicons-dismiss"></span></a>
					</div>';
				
			return $html;
		}

		/**
		 * Get the group-by method HTML 
		 *
		 * @since 1.0.0
		 *
		 * @param $rule_nr (integer) rule ordinal (starting 0)
		 * @param $sel_nr (integer) selector ordinal inside rule (starting 0)
		 * @param $method_id (string) the parent method_id
		 * @param $values (array) the selected values
		 *
		 * @return $html (HTML code) form code for the selector
		 *
		 */
		function get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values) {

			// Securing output
			$rule_nr       = intval($rule_nr);
			$sel_nr        = intval($sel_nr);
			$method_id     = esc_attr($method_id);
			
			$sel_opt = isset($values['group_by']) ? $values['group_by'] : '';

			$html =   '<span class="field field-group_by selection-' . $method_id . ' selection-' . $method_id . '-group_by">' . esc_html_x('Group by:', 'shorted, label for options field', 'fish-and-ships') . ' <a class="woocommerce-help-tip woocommerce-fns-help-popup" data-fns-tip="group_by" data-tip="' . esc_attr__('It will determine how the cart products should be grouped (or not) before analyzing if they match the selection conditions.', 'fish-and-ships') . ' ' . esc_attr('Click to open detailed help about Group by.', 'fish-and-ships') . '"></a> '
					. '<select name="shipping_rules[' . $rule_nr . '][sel]['.$method_id.'][group_by]['.$sel_nr.']">';
			
			foreach ($this->get_group_by_options() as $key=>$caption) {
				
				$html .= '<option value="' . esc_attr($key) . '"' . ($key == $sel_opt ? ' selected' : '') . '>' . esc_html($caption) . '</option>';
			}
			$html .=  '</select></span>';
					
			return $html;
		}

		/**
		 * Get the logical operator AND / OR.
		 *
		 * The operator can be AND or OR. AND for legacy before 1.1.9
		 *
		 * @since 1.1.9
		 *
		 * @param array $shipping rule
		 *
		 * @return and | or
		 */
		public function get_logical_operator($shipping_rule) {
			$logical_operator = 'and';
			if ( isset($shipping_rule['sel']['operators']) && is_array($shipping_rule['sel']['operators']) ) {
				foreach ($shipping_rule['sel']['operators'] as $operator) {
					if ( isset($operator['method']) && $operator['method'] == 'logical_operator' ) {
						if ( isset($operator['values']) && $operator['values'] === array('or') ) {
							$logical_operator = 'or';
							break;
						}
					}
				}
			} 
			return $logical_operator;
		}

		/**
		 * Get the logical operator HTML interface
		 *
		 * @since 1.1.9
		 *
		 * @param $rule_nr (integer) rule ordinal (starting 0)
		 * @param $values expected and | or
		 * @param $shipping_method_class (class)
		 *
		 * @return $html (HTML code) form code for the selector
		 *
		 */
		function get_logical_operator_html($rule_nr, $shipping_rule ) {
			
			// Securing output
			$rule_nr       = intval($rule_nr);
			
			// Since 1.1.9, the operator can be AND or OR. AND for legacy
			$logical_operator = $this->get_logical_operator($shipping_rule);

			// For legacy: before 1.1.9, always be AND
			//$value = $values === array('or') ? 'or' : 'and';

			$html =   '<span class="field field-logical_operator selection-logical_operator">' . esc_html_x('Logic:', 'shorted, label for logical operator AND/OR', 'fish-and-ships') . ' <a class="woocommerce-help-tip" data-tip="' . esc_attr__('Logical operator: Products on cart that match with all (AND logic) or at least one (OR logic) criteria.', 'fish-and-ships') . ' "></a> <span class="logical_operator_wrapper">'
					. '<input type="radio" name="shipping_rules[' . $rule_nr . '][sel][logical_operator][]" value="and" data-save=' . ($logical_operator == 'and' ? '"1" checked' : '"0"') . ' class="logical_operator_radio"> ' 
					. _x('AND', 'VERY shorted, logic operator (maybe better leave in english)', 'fish-and-ships')

					. '<input type="radio" name="shipping_rules[' . $rule_nr . '][sel][logical_operator][]" value="or" data-save=' . ($logical_operator == 'or' ? '"1" checked' : '"0"') . ' class="logical_operator_radio ' . ( !$this->im_pro() ? ' disabled' : '') . '"' . ( !$this->im_pro() ? ' readonly' : '') . '> ' 
					. _x('OR', 'VERY shorted, logic operator (maybe better leave in english)', 'fish-and-ships') . ( !$this->im_pro() ? ' [PRO]' : '')
					. '</span></span>';
					
			return $html;
		}

		function cant_get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values) {
			
			$html =   '<span class="' . esc_attr('field field-group_by field-cant-group_by selection-' . $method_id . ' selection-' . $method_id . '-group_by') . '">'
					. '[' . esc_html__('This method can\'t group cart items and they will be compared one by one', 'fish-and-ships') . '] <a class="woocommerce-help-tip woocommerce-fns-help-popup" data-fns-tip="group_by" data-tip="' . esc_attr__('It will determine how the cart products should be grouped (or not) before analyzing if they match the selection conditions.', 'fish-and-ships'). ' ' . esc_attr('Click to open detailed help about Group by.', 'fish-and-ships') . '"></a></span>';
					
			return $html;
		}

		/**
		 * Get the cost method HTML 
		 *
		 * @since 1.0.0
		 *
		 * @param $rule_nr (integer) rule number
		 * @param $sel_method_id (string) the option selected
		 *
		 * @return $html (HTML code) form code for the selector
		 *
		 */
		function get_cost_method_html($rule_nr, $sel_method_id = '') {
			
			$html = '<span class="field"><select name="shipping_rules[' . intval($rule_nr) . '][cost_method][]" class="wc-fns-cost-method">';
			
			$cost_methods = apply_filters( 'wc_fns_get_cost_methods', array() );			
			
			foreach ($cost_methods as $method_id=>$method) {
				
				$html .= '<option value="' . esc_attr($method_id) . '" ';
				if ($sel_method_id == $method_id) $html .= 'selected ';
				$html .= '>' . esc_html($method['label']) . '</option>';
			}
		
			$html .= '	</select></span>';
				
			return $html;
		}

		/**
		 * Get the action method HTML 
		 *
		 * @since 1.0.0
		 * @version 1.4.0
		 *
		 * @param $rule_nr (integer) rule number
		 * @param $actions (array)
		 * @param $sel_action_id (string) the option selected
		 *
		 * @return $html (HTML code) form code for the selector
		 *
		 */
		function get_action_method_html($rule_nr, $actions, $sel_action_id = '') {

			$html = '<div class="action_wrapper">
						<span class="field"><select name="shipping_rules[' . intval($rule_nr) . '][actions][]" class="wc-fns-actions" required>
							<option value="">' . esc_attr__('Select one action', 'fish-and-ships') . '</option>';
			
			foreach ($actions as $action_id=>$action) {

				$class = array();
				if ( isset($action['scope']) ) $class = $action['scope'];

				if (!$this->im_pro() && $action['onlypro']) {
				
					$class[] = 'only_pro';

					$html .= '<option value="pro" ';
					if ($sel_action_id == $action_id) $html .= 'selected ';
					$html .= 'class="' . esc_attr( implode( ' ', $class) ) . '">' . esc_html($action['label'] . ' [PRO]').'</option>';

				} else {

					$html .= '<option value="' . esc_attr($action_id) . '" ';
					if ($sel_action_id == $action_id) $html .= 'selected ';
					$html .= 'class="' . esc_attr( implode( ' ', $class) ) . '">' . esc_html($action['label']) . '</option>';
				}
			}
		
			$html .= '	</select></span>
						<div class="action_details">[action_details]</div>
						<a href="#" class="delete" title="' . esc_attr_x('Remove action', 'button caption', 'fish-and-ships') . '"><span class="dashicons dashicons-dismiss"></span></a>
					</div>';
				
			return $html;
		}

		/**
		 * FILTER. NOT ACCESSED DIRECTLY
		 * 
		 * Generates the HTML for a table row
		 *
		 * @since 1.0.0
		 * @version 1.4.0
		 *
		 * Since 1.4.0, $data can get cells and wrapper class, on only cells array
		 * @param array $data: the wrapper & cells indexed / only cells array.			 
		 * @param array $tokens_to_replace: a pair keys/value to replace in the content.
		 *
		 */
		public function get_shipping_rules_table_row_html( $data = array(), $tokens_to_replace = array() ) {
			
			$cells = isset ($data['cells'] ) ? $data['cells'] : $data;
			
			$html = '';
			foreach ($cells as $cell) {
				
				// Tag must be td or th, td by default
				$tag = 'td'; if (isset($cell['tag']) && $cell['tag']=='th') $tag = 'th';
		
				// Class is optional
				$class = ''; if (isset($cell['class'])) $class = $cell['class'];
		
				// Content is optional
				$content = ''; if (isset($cell['content'])) $content = $cell['content'];
				
				// Maybe there is some token to replace
				foreach ($tokens_to_replace as $token=>$value) {
					$content = str_replace($token, $value, $content);	
				}
				
				// Securing output
				$class    = esc_attr($class);
				
				$html .= "<$tag class=\"$class\">$content</$tag>";
			}
			
			$wrapper_class = isset( $data['wrapper']['class'] ) ? $data['wrapper']['class'] : '';

			return '<tr class="' . esc_attr($wrapper_class) . '">' . $html . '</tr>';
		}

		/*****************************************************************
			Getting terms and nesting it
		 *****************************************************************/

		/**
		 * Get terms of some taxonomy (cached) 
		 * 
		 * We let store into array, for performance. Only once will be queried, ordered, and human readable rendered
		 *
		 * @since 1.0.0
		 * @version 1.2.3
		 *
		 * @param $taxonomy (string) 
		 *
		 * @return ordered array of terms
		 *
		 */
		function get_terms($taxonomy, $hide_empty = false) {
			
			// The key to store into cache
			$index_cached = $taxonomy . ($hide_empty ? '-1' : '-0');
			if (isset($this->terms_cached[$index_cached])) return $this->terms_cached[$index_cached];
				
			// We want the terms into the main language
			if ( $this->is_wpml ) {
				global $sitepress;
				$current_lang = $sitepress->get_current_language(); //save current language
				$sitepress->switch_lang( $this->get_main_lang() );
			}
			
			$terms = get_terms( array(
				'taxonomy'     => $taxonomy,
				'orderby'      => 'name',
				'pad_counts'   => false,
				'hierarchical' => 1,
				'hide_empty'   => $hide_empty,
			));
			
			if ( $this->is_wpml ) $sitepress->switch_lang( $current_lang );

			//Prevent no taxonomy (earlier WC versions)
			if (!is_array($terms)) return array();
			
			$hierarchical = $this->order_terms_recursive($taxonomy, $terms, 0);
			
			$this->terms_cached[$index_cached] = $this->walk_terms_recursive($hierarchical, 0);
			
			return $this->terms_cached[$index_cached];
		}

		/**
		 * Auxiliary get_terms function: Recursive to order the terms 
		 *
		 * get_terms() use it
		 *
		 * @since 1.0.0
		 *
		 */
		function order_terms_recursive($taxonomy, $terms, $parent = 0) {
			$childs = array();
			foreach ($terms as $i=>$term) {
				if ($term->parent == $parent) {
					$childs[$term->term_id] = array('term' => $term, 'childs' => $this->order_terms_recursive($taxonomy, $terms, $term->term_id) );
				}
			}
			return $childs;
		}

		/**
		 * Auxiliary get_terms function: Recursive to walk the ordered terms and tab it into human readable format 
		 *
		 * get_terms() use it
		 *
		 * @since 1.0.0
		 *
		 */
		function walk_terms_recursive($terms, $indent = 0) {
			$walk = array();
			foreach ($terms as $i=>$term) {
				$walk[$term['term']->term_id] = str_repeat('- ', $indent) . $term['term']->name;
				$walk += $this->walk_terms_recursive($term['childs'], $indent + 1);
			}
			return $walk;
		}

		/*****************************************************************
			AJAX
		 *****************************************************************/

		/**
		 * Reply help HTML file. Will try user language and, if the file doesn't exists, they take the english version
		 *
		 * @since 1.0.0
		 * @version 1.0.2
		 */
		function wc_fns_help() {
			$log = '';
			
			$what = isset($_GET['what']) ? sanitize_key($_GET['what']) : '';

			$lang = isset($_GET['lang']) ? sanitize_key($_GET['lang']) : 'en';

			//Not in user locale? let's try global lang or fallback to english
			if ($lang != 'en' && !is_file(WC_FNS_PATH . 'help/' . $lang . '/' . $what . '.html')) {
				
				$log = '<p>' . WC_FNS_URL . 'help/' . $lang . '/' . $what . '.html</p>';

				if (strlen($lang) > 2) {
					$lang = substr($lang, 0, 2);
		
					if ($lang != 'en' && !is_file(WC_FNS_PATH . 'help/' . $lang . '/' . $what . '.html')) {
		
						$log .= '<p>' . WC_FNS_URL . 'help/' . $lang . '/' . $what . '.html</p>';
						$lang = 'en';
					}
				} else {
					$lang = 'en';
				}
			}

			if (!is_file(WC_FNS_PATH . 'help/' . ($lang == 'en' ? '' : ($lang . '/') ) . $what . '.html')) {

				$log .= '<p>' . WC_FNS_URL . 'help/' . ($lang == 'en' ? '' : ($lang . '/') ) . $what . '.html</p>';
				echo '<html><head><title>Error</title></head><body><h1>Error</h1><div id="content">'
						. '<p>Help file(s) not found:</p>' . $log . '</div></body></html>';
				exit();
			}
			
			$help = file_get_contents(WC_FNS_PATH . 'help/' . ($lang == 'en' ? '' : ($lang . '/') ) . $what . '.html');
			
			$help = str_replace('<img src="img/', '<img src="' . WC_FNS_URL . 'help/img/', $help);
			$help = str_replace('<img src="../img/', '<img src="' . WC_FNS_URL . 'help/img/', $help);
			$help = str_replace('href="img/', 'href="' . WC_FNS_URL . 'help/img/', $help);
			$help = str_replace('href="../img/', 'href="' . WC_FNS_URL . 'help/img/', $help);
			echo $help;
			exit();
		}

		/**
		 * Reply the log file.
		 *
		 */
		function wc_fns_logs() {

			if ( isset($_GET['name']) && $this->is_log_name($_GET['name']) ) {
				
				// validated
				$name = sanitize_key($_GET['name']);
			
				$log_details = get_transient($name);
		
				if ($log_details === false) {
		
					echo 'Error' . "\r\n" . '&nbsp;&nbsp;Log not found. Maybe deleted?';
		
				} elseif (!is_array($log_details) || count($log_details) == 0) {
		
					echo 'Error' . "\r\n" . '&nbsp;&nbsp;Invalid log. Maybe corrupt?';
					
				} else {
		
					foreach ($log_details as $line) {
						$tab = strlen($line) - strlen(ltrim($line));
						$line = ltrim($line);
						$strong = false;
						if (substr($line, 0, 1) == '*') {
							$strong = true;
							$line = substr($line, 1);
						}
						if ($line == '#') {
							echo '<p>&nbsp;</p>';
						} else {
							echo apply_filters('the_content', str_repeat('&nbsp;', $tab) . ($strong ? '<strong>' : '') . $line . ($strong ? '</strong>' : '') );
						}
					}
				}
				exit();

			} else {
				
				// fail validation
				echo 0;
				exit();
			}
		}
		
		/**
		 * Ajax logs pane
		 *
		 * @since 1.2.6
		 */
		function wc_fns_logs_pane() {
			
			if ( !isset($_REQUEST['instance_id']) ) {
				echo '0';
				exit();
			}

			$instance_id = intval($_REQUEST['instance_id']);
			
			$html = '';
			require WC_FNS_PATH . 'includes/logs-panel.php';
			
			echo $html;
			exit();
		}


		/**
		 * Ajax freemium: open / close panel
		 *
		 * @version 1.5
		 */
		function wc_fns_freemium() {

			// Only 1 and 0 are allowed
			if ( isset($_GET['opened']) && $this->is_one_or_zero($_GET['opened']) ) {

				$opened  = sanitize_key($_GET['opened']);
				
				if ($opened === '1') {

					$this->set_option('close_freemium', time()-1 );
				
				} elseif ($opened === '0') {
		
					$days_delay = 31; // 1 month
					if ( $this->im_pro() ) $days_delay = $days_delay * 11; // 11 months
					$this->set_option('close_freemium', time() + DAY_IN_SECONDS * $days_delay );
				}

				echo '1';
				exit();

			}
				
			// Unexpected parameter
			echo '0';
			exit();
		}

		/**
		 * Ajaxified table rules fields.
		 *
		 */
		function wc_fns_fields() {
			
			if ( isset($_GET['type']) && $_GET['type'] == 'selector' && isset($_GET['method_id']) && $this->is_valid_selector($_GET['method_id']) ) {
			
				$method_id = sanitize_key( $_GET['method_id'] );

				echo apply_filters('wc_fns_get_html_details_method', '', 0, 0, $method_id, array(), false );
				exit();
			}
			echo 'not supported.';
			exit();
		}

		
		/*****************************************************************
			Admin nav small things
		 *****************************************************************/

		/**
		* Add link on the plugin list, to re-start the wizard
		*
		* @version: 1.5
		*/
		public static function add_plugin_action_link( $links ){
		
			$start_link = array(
				'<a href="'. admin_url( 'admin.php?page=wc-settings&tab=shipping&wc-fns-wizard=restart' )
				 .'" style="color: #a16696; font-weight: bold;">'. esc_html__( 'Start: run wizard', 'fish-and-ships') .'</a>',
			);
		
			return array_merge( $start_link, $links );
		}	

		/**
		* Add link on the plugins page
		*
		*/
		public static function add_plugin_row_meta( $links, $file ) {

			/*if ( strpos( $file, 'fish-and-ships-pro' ) !== false ) {

				$links[] = '<a href="https://www.wp-centrics.com/" target="_blank">
							<strong>'. esc_html__( 'Visit plugin site' ) .'</strong></a>';
			}*/

			if ( strpos( $file, 'fish-and-ships' ) !== false ) {
				$links[] = '<a href="https://www.wp-centrics.com/help/fish-and-ships/" target="_blank">
							<strong style="color:#a16696;">'. esc_html__( 'Plugin help' ) .'</strong></a>';
				
				$links[] = '<a href="https://www.youtube.com/watch?v=sjQKbt2Nn7k" target="_blank" title="' . esc_html__('Watch 7 minutes video introduction on YouTube', 'fish-and-ships') . '" target="_blank">
							<span class="dashicons-before dashicons-video-alt3" style="color:#ff0000;"></span> <strong>' . esc_html__('Introduction', 'fish-and-ships') . '</strong></a>';
			}
			return $links;
		}

		/**
		 * Add help tabs (in the same way as WC does).
		 */
		public function add_tabs()
		{	
			// WC old versions haven't this
			if( function_exists('wc_get_screen_ids') )
			{
			$screen = get_current_screen();

			if ( ! $screen || ! in_array( $screen->id, wc_get_screen_ids() ) ) {
				return;
			}

			$screen->add_help_tab(
				array(
					'id'      => 'wc_fish_n_ships_support_tab',
					'title'   => 'WC Fish and Ships',
					'content' =>
						'<h2>Fish and Ships for WooCommerce</h2>' .
						'<p>' . esc_html__('A WooCommerce shipping method. Easy to understand and easy to use, it gives you an incredible flexibility.', 'fish-and-ships') . '</p>' .
						'<p>&gt; <a href="https://www.wp-centrics.com/help/fish-and-ships/" target="_blank">' . esc_html__('Go to online help documentation', 'fish-and-ships') . '</a></p>' .
						'<p>&gt; <a href="https://wordpress.org/support/plugin/fish-and-ships/" target="_blank">' . esc_html__('Get support on WordPress repository', 'fish-and-ships') . '</a></p>' .
						
							'<p style="padding-top:1em;"><a href="' . admin_url('admin.php?page=wc-settings&tab=shipping&wc-fns-wizard=now') . '" class="button button-wc-fns-colors">' . esc_html__('Restart wizard', 'fish-and-ships') . '</a> &nbsp;<a href="https://www.wp-centrics.com/contact-support/" class="button" target="_blank">' . esc_html__('Get support about Fish and Ships Pro', 'fish-and-ships') . '</a></p>',
				)
			);
			}
		}
	}
	
	global $Fish_n_Ships; // Fixed "Uncaught Error: Call to a member function check_wpml() on null", since 1.4.12
	$Fish_n_Ships = new Fish_n_Ships();

	// Load auxiliary group class
	require WC_FNS_PATH . 'includes/group-class.php';

	// Load Shipping Boxes (since 1.3)
	require WC_FNS_PATH . 'includes/shipping-boxes.php';

	// Load pro actions helper (since 1.2.5)
	if ( $Fish_n_Ships->im_pro() ) require WC_FNS_PATH . 'includes/pro-actions-helper.php';

	// Load the wizard and wordpress repository rate
	if ( is_admin() ) require WC_FNS_PATH . 'includes/wizard.php';

	/**
	 * After all plugins are loaded, we will initialise everything
	 *
	 * @since 1.0.0
	 * @version 1.4.14
	 *
	 */
	 if (!function_exists('wocommerce_fish_n_ships_init')) {

		// Declare WooCommerce HPOS compatibility
		add_action( 'before_woocommerce_init', function() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		} );
		
		add_action( 'init', 'wocommerce_fish_n_ships_init' );
		function wocommerce_fish_n_ships_init() {

			global $Fish_n_Ships;
					
			// Skyverge Measurement Price Calculator (MPC), since v1.3
			if ( class_exists( 'WC_Measurement_Price_Calculator' ) ) {
				require WC_FNS_PATH . '3rd-party/fns-measurement-pc.php';
			}
					
			// Register plugin text domain for translations files
			load_plugin_textdomain( 'fish-and-ships', false, basename( dirname( __FILE__ ) ) . '/languages' );
			
			// PHP prior to 5.5 or WooCommerce not active / old version?
			if ( is_admin() && !$Fish_n_Ships->is_wc() ) {
				require WC_FNS_PATH . 'includes/woocommerce-required.php';
			}
		}

		add_action( 'woocommerce_shipping_init', 'wocommerce_fish_n_ships_shipping_init' );
		function wocommerce_fish_n_ships_shipping_init() {

			global $Fish_n_Ships;
			
			// Check if we're on multilingual website
			$Fish_n_Ships->check_wpml();

			// Check if we're on multi-currency website
			$Fish_n_Ships->check_multicurrency();

			// Load the shipping FnS class (multiple instances).
			if (class_exists('WC_Shipping_Method') && !class_exists('WC_Fish_n_Ships')) {
		
				require WC_FNS_PATH . 'includes/shipping-class.php';
			}
			
		}
	 }
}