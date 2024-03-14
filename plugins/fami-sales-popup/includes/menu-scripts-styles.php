<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'famispMenuScriptsStyles' ) ) {
	class famispMenuScriptsStyles {
		public $version = '1.0.0';
		
		public function __construct() {
			add_action( 'admin_bar_menu', array( $this, 'famisp_admin_bar_menu' ), 1000 );
			add_action( 'admin_menu', array( $this, 'famisp_menu_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 999 );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		}
		
		public function famisp_admin_bar_menu() {
			global $wp_admin_bar;
			if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
				return;
			}
			// Add Parent Menu
			$argsParent = array(
				'id'    => 'famisp_option',
				'title' => esc_html__( 'Sales Popup', 'famisp' ),
				'href'  => admin_url( 'admin.php?page=famisp' ),
			);
			$wp_admin_bar->add_menu( $argsParent );
		}
		
		public function famisp_menu_page() {
			// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
			$menu_args = array(
				array(
					'page_title' => esc_html__( 'Sales Popup', 'famisp' ),
					'menu_title' => esc_html__( 'Sales Popup', 'famisp' ),
					'cap'        => 'manage_options',
					'menu_slug'  => 'famisp',
					'function'   => array( $this, 'famisp_menu_page_callback' ),
					'icon'       => FAMISP_URI . 'assets/images/logo.png',
					'parrent'    => '',
					'position'   => 3
				)
			);
			foreach ( $menu_args as $menu_arg ) {
				if ( $menu_arg['parrent'] == '' ) {
					add_menu_page( $menu_arg['page_title'], $menu_arg['menu_title'], $menu_arg['cap'], $menu_arg['menu_slug'], $menu_arg['function'], $menu_arg['icon'], $menu_arg['position'] );
				} else {
					add_submenu_page( $menu_arg['parrent'], $menu_arg['page_title'], $menu_arg['menu_title'], $menu_arg['cap'], $menu_arg['menu_slug'], $menu_arg['function'] );
				}
			}
		}
		
		public function famisp_menu_page_callback() {
			$page = isset( $_REQUEST['page'] ) ? famisp_clean( $_REQUEST['page'] ) : '';
			if ( trim( $page ) != '' ) {
				$file_path = FAMISP_PATH . 'includes/admin-pages/' . $page . '.php';
				if ( file_exists( $file_path ) ) {
					require_once FAMISP_PATH . 'includes/admin-pages/' . $page . '.php';
				}
			}
		}
		
		function admin_scripts( $hook ) {
			
			$screen = get_current_screen();
			if ( $screen->id == 'toplevel_page_famisp' ) {
				// wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_style( 'jquery-ui', FAMISP_URI . 'assets/css/jquery-ui.css' );
				wp_enqueue_script( 'jquery-ui-tabs' );
			}
			
			wp_enqueue_style( 'famisp-backend', FAMISP_URI . 'assets/css/backend.css' );
			
			wp_enqueue_script( 'famisp-backend', FAMISP_URI . 'assets/js/backend.js', array(), null );
			wp_localize_script( 'famisp-backend', 'famisp',
			                    array(
				                    'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				                    'security' => wp_create_nonce( 'famisp_backend_nonce' )
			                    )
			);
		}
		
		function frontend_scripts( $hook ) {
			wp_enqueue_style( 'famisp-frontend', FAMISP_URI . 'assets/css/frontend.css' );
			wp_enqueue_script( 'famisp-frontend', FAMISP_URI . 'assets/js/frontend.js', array( 'jquery' ), null );
			
			$sales_popup_data = famisp_get_sales_popup_data();
			
			$famisp_args = array(
				'ajaxurl'          => admin_url( 'admin-ajax.php' ),
				'security'         => wp_create_nonce( 'famisp_nonce' ),
				'sales_popup_data' => $sales_popup_data,
				'text'             => array(
					'second'  => esc_html__( 'second', 'famisp' ),
					'seconds' => esc_html__( 'seconds', 'famisp' ),
					'minute'  => esc_html__( 'minute', 'famisp' ),
					'minutes' => esc_html__( 'minutes', 'famisp' ),
					'hour'    => esc_html__( 'hour', 'famisp' ),
					'hours'   => esc_html__( 'hours', 'famisp' ),
					'day'     => esc_html__( 'day', 'famisp' ),
					'days'    => esc_html__( 'days', 'famisp' ),
				)
			);
			if ( class_exists( 'WooCommerce' ) ) {
				$famisp_args['price_format']             = get_woocommerce_price_format();
				$famisp_args['price_decimals']           = wc_get_price_decimals();
				$famisp_args['price_thousand_separator'] = wc_get_price_thousand_separator();
				$famisp_args['price_decimal_separator']  = wc_get_price_decimal_separator();
				$famisp_args['currency_symbol']          = get_woocommerce_currency_symbol();
				$famisp_args['wc_tax_enabled']           = wc_tax_enabled();
				$famisp_args['cart_url']                 = wc_get_cart_url();
			}
			wp_localize_script( 'famisp-frontend', 'famisp', $famisp_args );
		}
	}
	
	new famispMenuScriptsStyles();
}