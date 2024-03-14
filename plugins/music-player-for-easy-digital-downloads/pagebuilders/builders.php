<?php
/**
 * Main class to interace with the different Content Editors: EDDMP_BUILDERS class
 */
if ( ! class_exists( 'EDDMP_BUILDERS' ) ) {
	class EDDMP_BUILDERS {

		private static $_instance;

		private function __construct(){}
		private static function instance() {
			if ( ! isset( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		} // End instance

		public static function run() {
			$instance = self::instance();
			add_action( 'init', array( $instance, 'init' ) );
			add_action( 'after_setup_theme', array( $instance, 'after_setup_theme' ) );
		}

		public function init() {
			$instance = self::instance();

			// Gutenberg
			add_action( 'enqueue_block_editor_assets', array( $instance, 'gutenberg_editor' ) );

			// Elementor
			add_action( 'elementor/widgets/register', array( $instance, 'elementor_editor' ) );
			add_action( 'elementor/elements/categories_registered', array( $instance, 'elementor_editor_category' ) );

			// Beaver builder
			if ( class_exists( 'FLBuilder' ) ) {
				include_once dirname( __FILE__ ) . '/beaverbuilder/eddmp.inc.php';
			}

			// DIVI
			add_action( 'et_builder_ready', array( $instance, 'divi_editor' ) );

		} // End init

		public function after_setup_theme() {
			$instance = $instance = self::instance(); // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments

			// SiteOrigin
			add_filter( 'siteorigin_widgets_widget_folders', array( $instance, 'siteorigin_widgets_collection' ) );
			add_filter( 'siteorigin_panels_widget_dialog_tabs', array( $instance, 'siteorigin_panels_widget_dialog_tabs' ) );

			// Visual Composer
			add_action( 'vcv:api', array( $instance, 'visualcomposer_editor' ) );
		} // End after_setup_theme

		/**************************** DIVI ****************************/

		public function divi_editor() {
			if ( class_exists( 'ET_Builder_Module' ) ) {
				if ( isset( $_GET['et_fb'] ) ) {
					wp_enqueue_script( 'eddmp-admin-gutenberg-editor', plugin_dir_url( __FILE__ ) . 'divi/divi.js', array( 'react' ), EDDMP_PLUGIN_VERSION, true );
				}
				require_once dirname( __FILE__ ) . '/divi/divi.pb.php';
			}
		} // End divi_editor

		/**************************** GUTENBERG ****************************/

		/**
		 * Loads the javascript resources to integrate the plugin with the Gutenberg editor
		 */
		public function gutenberg_editor() {
			wp_enqueue_style( 'eddmp-gutenberg-editor-css', plugin_dir_url( __FILE__ ) . 'gutenberg/gutenberg.css', array(), EDDMP_PLUGIN_VERSION );

			$url  = EDDMP_WEBSITE_URL;
			$url .= ( ( strpos( $url, '?' ) === false ) ? '?' : '&' ) . 'eddmp-preview=';

			wp_enqueue_script( 'eddmp-admin-gutenberg-editor', plugin_dir_url( __FILE__ ) . 'gutenberg/gutenberg.js', array( 'wp-blocks', 'wp-element' ), EDDMP_PLUGIN_VERSION, true );

			wp_localize_script( 'eddmp-admin-gutenberg-editor', 'eddmp_gutenberg_editor_config', array( 'url' => $url ) );
		} // End gutenberg_editor

		/**************************** ELEMENTOR ****************************/

		public function elementor_editor_category() {
			require_once dirname( __FILE__ ) . '/elementor/elementor_category.pb.php';
		} // End elementor_editor

		public function elementor_editor() {
			require_once dirname( __FILE__ ) . '/elementor/elementor.pb.php';
		} // End elementor_editor

		/**************************** SITEORIGIN ****************************/

		public function siteorigin_widgets_collection( $folders ) {
			 $folders[] = dirname( __FILE__ ) . '/siteorigin/';
			return $folders;
		} // End siteorigin_widgets_collection

		public function siteorigin_panels_widget_dialog_tabs( $tabs ) {
			 $tabs[] = array(
				 'title'  => __( 'Music Player for Easy Digital Downloads', 'music-player-for-easy-digital-downloads' ),
				 'filter' => array(
					 'groups' => array( 'music-player-for-easy-digital-downloads' ),
				 ),
			 );

			 return $tabs;
		} // End siteorigin_panels_widget_dialog_tabs

		/**************************** VISUAL COMPOSER ****************************/

		public function visualcomposer_editor( $api ) {
			 $elementsToRegister = array( 'EDDMPplaylist' );
			$pluginBaseUrl       = rtrim( plugins_url( 'visualcomposer/', __FILE__ ), '\\/' );
			$elementsApi         = $api->elements;
			foreach ( $elementsToRegister as $tag ) {
				$manifestPath   = dirname( __FILE__ ) . '/visualcomposer/' . $tag . '/manifest.json';
				$elementBaseUrl = $pluginBaseUrl . '/' . $tag;
				$elementsApi->add( $manifestPath, $elementBaseUrl );
			}
		} // End visualcomposer_editor
	} // End EDDMP_BUILDERS
}
