<?php
if (!defined( 'ABSPATH')) exit;
if(!class_exists( 'PIECFW_Importer')){
	class PIECFW_Importer {
		/**
		* Product Exporter Tool
		*/
		public static function load_wp_importer() {
			// Load Importer API
			require_once ABSPATH . 'wp-admin/includes/import.php';

			if ( ! class_exists( 'WP_Importer' ) ) {
				$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
				if ( file_exists( $class_wp_importer ) ) {
					require $class_wp_importer;
				}
			}
		}

		/**
		* Product Importer Tool
		*/
		public static function product_importer() {
			
			if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
				return;
			}

			self::load_wp_importer();

			// includes
			if ( !defined('DOING_AJAX') ) {
				$tab = ! empty( $_GET['tab'] ) ? sanitize_text_field($_GET['tab']) : 'import';
				_e('<div class="wrap woocommerce">');
				require PIECFW_PLUGIN_DIR_PATH.'includes/views/html-admin-tabs.php';
			}

				require 'class-piecfw-product-import.php';
				require 'class-piecfw-product_variation-import.php';
				require 'class-piecfw-parser.php';

				// Dispatch
				$GLOBALS['PIECFW_Product_Import'] = new PIECFW_Product_Import();
				$GLOBALS['PIECFW_Product_Import'] ->dispatch();
				

			if ( !defined('DOING_AJAX') ) {	
				_e('</div>');
			}	
		}

		/**
		* Variation Importer Tool
		*/
		public static function variation_importer() {
			
			if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
				return;
			}

			self::load_wp_importer();

			// includes
			if ( !defined('DOING_AJAX') ) {
				$tab = ! empty( $_GET['tab'] ) ? sanitize_text_field($_GET['tab']) : 'import';
				_e('<div class="wrap woocommerce">');
				require PIECFW_PLUGIN_DIR_PATH.'includes/views/html-admin-tabs.php';
			}

				require 'class-piecfw-product-import.php';
				require 'class-piecfw-product_variation-import.php';
				require 'class-piecfw-parser.php';

				// Dispatch
				$GLOBALS['PIECFW_Product_Import'] = new PIECFW_Product_Variation_Import();
				$GLOBALS['PIECFW_Product_Import'] ->dispatch();


			if ( !defined('DOING_AJAX') ) {	
				_e('</div>');
			}
		}
	}
}