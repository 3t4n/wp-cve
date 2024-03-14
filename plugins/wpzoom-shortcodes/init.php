<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WPZOOM_Shortcodes_Plugin_Init' ) ) {
	/**
	 * Shortcodes generator initializator.
	 */
	class WPZOOM_Shortcodes_Plugin_Init {

		/**
		 * @var string Path to plugin file.
		 */
		public $public_file;

		/**
		 * @var string for assets path.
		 */
		public static $assets_path;

		public function __construct() {

			$this->plugin_file                          = dirname( __FILE__ ) . '/wpzoom-shortcodes.php';
			WPZOOM_Shortcodes_Plugin_Init::$assets_path = plugin_dir_url( $this->plugin_file ) . 'assets';

			// Register the necessary actions on `admin_init`.
			add_action( 'admin_init', array( &$this, 'init' ) );

			// `wp_ajax_*` is only run for logged users.
			add_action( 'wp_ajax_wpz_check_url_action', array( &$this, 'ajax_action_check_url' ) );
		}

		public function init() {
			if ( ( current_user_can( 'edit_posts' ) || current_user_can( 'edit_pages' ) ) && get_user_option( 'rich_editing' ) == 'true' ) {
				// Add the tinyMCE buttons and plugins.
				add_filter( 'mce_buttons', array( &$this, 'filter_mce_buttons' ) );
				add_filter( 'mce_external_plugins', array( &$this, 'filter_mce_external_plugins' ) );

				// enqueue for gutenberg
				add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_thickbox' ) );

				// Register Shortcodes dialog for AJAX requests.
				add_action( 'wp_ajax_zoom_shortcodes_ajax_dialog', array( $this, 'ajax_dialog' ) );

				// Register Shortcodes preview frame for AJAX requests.
				add_action( 'wp_ajax_zoom_shortcodes_ajax_preview', array( $this, 'ajax_preview' ) );

				// Register the colourpicker JavaScript.
				wp_register_script( 'wpz-colourpicker', WPZOOM_Shortcodes_Plugin_Init::$assets_path . '/js/colorpicker.js', array( 'jquery' ), '3.6', true ); // Loaded into the footer.
				wp_enqueue_script( 'wpz-colourpicker' );

				// Register the colourpicker CSS.
				wp_register_style( 'wpz-colourpicker', WPZOOM_Shortcodes_Plugin_Init::$assets_path . '/css/colorpicker.css' );
				wp_enqueue_style( 'wpz-colourpicker' );

				// Register the custom CSS styles.
				wp_register_style( 'wpz-shortcode-generator', WPZOOM_Shortcodes_Plugin_Init::$assets_path . '/css/shortcode-generator.css' );
				wp_enqueue_style( 'wpz-shortcode-generator' );

				wp_enqueue_style( 'zoom-font-awesome', WPZOOM_Shortcodes_Plugin_Init::$assets_path . '/css/font-awesome.min.css' );
			}
		}

		/**
		 * Enqueue thickbox
		 */

		public function enqueue_thickbox() {
			wp_enqueue_style( 'thickbox' );
		}

		/**
		 * Shortcodes preview for dialog window.
		 */
		public function ajax_preview() {
			require_once dirname( __FILE__ ) . '/dialog/dialog-preview-shortcode.php';
			die();
		}

		/**
		 * Shortcodes dialog window.
		 */
		public function ajax_dialog() {
			require_once dirname( __FILE__ ) . '/dialog/dialog.php';
			die();
		}

		/**
		 * Add new button to the tinyMCE editor.
		 */
		public function filter_mce_buttons( $buttons ) {
			array_push( $buttons, '|', 'wpzoom_shortcodes_button' );

			return $buttons;
		}

		/**
		 * Add functionality to the tinyMCE editor as an external plugin.
		 */
		public function filter_mce_external_plugins( $plugins ) {
			global $wp_version;
			$suffix = '';

			if ( version_compare( $wp_version, '3.9', '<' ) ) {
				$suffix = '.3.8';
			}

			$plugins['wpzoomShortcodes'] = WPZOOM_Shortcodes_Plugin_Init::$assets_path . '/js/shortcode-generator/editor-plugin' . $suffix . '.js';

			return $plugins;
		}

		/**
		 * Checks if a given url (via GET or POST) exists.
		 * Returns JSON.
		 *
		 * NOTE: For users that are not logged in this is not called.
		 * The client recieves <code>-1</code> in that case.
		 */
		public function ajax_action_check_url() {
			$hadError = true;

			$url = isset( $_REQUEST['url'] ) ? $_REQUEST['url'] : '';

			if ( strlen( $url ) > 0 && function_exists( 'get_headers' ) ) {
				$file_headers = @get_headers( $url );
				$exists       = $file_headers && $file_headers[0] != 'HTTP/1.1 404 Not Found';
				$hadError     = false;
			}

			echo '{ "exists": ' . ( $exists ? '1' : '0' ) . ( $hadError ? ', "error" : 1 ' : '' ) . ' }';

			die();
		}
	}

	/**
	 * Fire ZOOM Shortcode Generator.
	 */
	$zoom_shortcode_generator = new WPZOOM_Shortcodes_Plugin_Init();
}