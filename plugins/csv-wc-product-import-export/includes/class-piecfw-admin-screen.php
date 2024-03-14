<?php
if (!defined( 'ABSPATH')) exit;
if(!class_exists( 'PIECFW_Admin_Screen')){
	class PIECFW_Admin_Screen {
		/**
		* Constructor
		*/
		public function __construct() {
			add_filter( 'woocommerce_screen_ids', array( $this, 'screen_id' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_print_styles', array( $this, 'admin_scripts' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		}

		/**
		* Add screen id
		*/
		public function screen_id( $ids ) {
			$wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
			$ids[]        = $wc_screen_id . '_page_piecfw_import_export';
			return $ids;
		}

		/**
		* Notices in admin
		*/
		public function admin_notices() {
			if ( ! function_exists( 'mb_detect_encoding' ) ) {
				_e('<div class="error"><p>' . __( 'CSV Import/Export requires the function <code>mb_detect_encoding</code> to import and export CSV files. Please ask your hosting provider to enable this function.', PIECFW_TRANSLATE_NAME ) . '</p></div>');
			}
		}

		/**
		* Admin Menu
		*/
		public function admin_menu() {
			$page = add_submenu_page( 'woocommerce', __( 'Product Import/Export', PIECFW_TRANSLATE_NAME ), __( 'Product Import/Export', PIECFW_TRANSLATE_NAME ), apply_filters( 'piecfw_product_role', 'manage_woocommerce' ), 'piecfw_import_export', array( $this, 'output' ));
		}

		/**
		* Admin Scripts
		*/
		public function admin_scripts() {
			wp_enqueue_style( 'piecfw-product-importer', PIECFW_PLUGIN_DIR_URL . 'assets/css/style.css', '', PIECFW_VERSION, 'screen' );
			wp_enqueue_style( 'piecfw-product-loader', PIECFW_PLUGIN_DIR_URL . 'assets/css/loader.css', '', PIECFW_VERSION, 'screen' );
			wp_enqueue_style( 'piecfw-product-datetimepicker', PIECFW_PLUGIN_DIR_URL . 'assets/css/jquery.datetimepicker.min.css', '', PIECFW_VERSION, 'screen' );

			wp_enqueue_script( 'jquery-validate-min', PIECFW_PLUGIN_DIR_URL . 'assets/js/jquery.validate.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'additional-methods-min', PIECFW_PLUGIN_DIR_URL . 'assets/js/additional-methods.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'jquery-datetimepicker', PIECFW_PLUGIN_DIR_URL . 'assets/js/jquery.datetimepicker.full.js', array( 'jquery' ) );		
			wp_enqueue_script( 'import-custom', PIECFW_PLUGIN_DIR_URL . 'assets/js/import-custom.js', array( 'jquery' ), '', true );
			wp_localize_script( 'import-custom', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );		
		}

		/**
		* Admin Screen output
		*/
		public function output() {
			$tab = ! empty( $_GET['tab'] ) ? sanitize_text_field($_GET['tab']) : 'import';
			$log_file = ! empty( $_GET['log_file'] ) ? sanitize_text_field($_GET['log_file']) : '';
			include( 'views/html-admin-screen.php' );
		}

		/**
		* Admin page for importing
		*/
		public function admin_import_page() {
			include( 'views/html-getting-started.php' );
			include( 'views/import/html-import-products.php' );
		}

		/**
		* Admin Page for exporting
		*/
		public function admin_export_page() {
			include( 'views/export/html-export-products.php' );
		}

		/**
		* Admin page for log
		*/
		public function admin_log_page($log_file='') {
			include( 'views/import/html-import-logs.php' );
		}

		/**
		* Admin page for cron
		*/
		public function admin_cron_page() {
			include( 'views/import/html-import-cron.php' );
		}
	}
}
new PIECFW_Admin_Screen();
