<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! class_exists( 'famiWccpMenuScriptsStyles' ) ) {
	class famiWccpMenuScriptsStyles {
		
		public function __construct() {
			add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 1000 );
			add_action( 'admin_menu', array( $this, 'menu_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 999 );
		}
		
		public function admin_bar_menu() {
			global $wp_admin_bar;
			if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
				return;
			}
			// Add Parent Menu
			$argsParent = array(
				'id'    => 'fami_wccp_option',
				'title' => esc_html__( 'Products Compare', 'fami-woocommerce-compare' ),
				'href'  => admin_url( 'admin.php?page=fami-wccp' ),
			);
			$wp_admin_bar->add_menu( $argsParent );
		}
		
		public function menu_page() {
			// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
			$menu_args = array(
				array(
					'page_title' => esc_html__( 'Products Compare', 'fami-woocommerce-compare' ),
					'menu_title' => esc_html__( 'Products Compare', 'fami-woocommerce-compare' ),
					'cap'        => 'manage_options',
					'menu_slug'  => 'fami-wccp',
					'function'   => array( $this, 'menu_page_callback' ),
					'icon'       => FAMI_WCP_URL . 'assets/images/logo.jpg',
					'parrent'    => '',
					'position'   => 4
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
		
		public function menu_page_callback() {
			$page = isset( $_REQUEST['page'] ) ? Fami_Woocompare_Helper::clean( $_REQUEST['page'] ) : '';
			if ( trim( $page ) != '' ) {
				$file_path = FAMI_WCP_PATH . 'includes/admin-pages/' . $page . '.php';
				if ( file_exists( $file_path ) ) {
					require_once FAMI_WCP_PATH . 'includes/admin-pages/' . $page . '.php';
				}
			}
		}
		
		function admin_scripts( $hook ) {
			
			$screen = get_current_screen();
			if ( $screen->id == 'toplevel_page_fami-wccp' ) {
				wp_enqueue_style( 'jquery-ui', FAMI_WCP_URL . 'assets/css/jquery-ui.css' );
				wp_enqueue_script( 'jquery-ui-tabs' );
				wp_enqueue_script( 'jquery-ui-sortable' );
				
				wp_enqueue_style( 'fami-wccp-backend', FAMI_WCP_URL . 'assets/css/backend.css' );
			}
			
			wp_enqueue_script( 'fami-wccp-backend', FAMI_WCP_URL . 'assets/js/backend.js', array(), null );
			$import_settings_url = fami_wccp_import_settings_action_link();
			wp_localize_script( 'fami-wccp-backend', 'fami_wccp',
			                    array(
				                    'ajaxurl'             => admin_url( 'admin-ajax.php' ),
				                    'security'            => wp_create_nonce( 'fami_wccp_backend_nonce' ),
				                    'import_settings_url' => $import_settings_url,
				                    'text'                => array(
					                    'confirm_import_settings' => esc_html__( 'All current settings will be overwritten and CAN NOT BE UNDONE! Are you sure you want to import settings?', 'fami-woocommerce-compare' )
				                    )
			                    )
			);
		}
	}
	
	new famiWccpMenuScriptsStyles();
}