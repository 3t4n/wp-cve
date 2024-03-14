<?php
/**
 * Defines the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0
 * @package    BBPS
 * @subpackage BBPS/includes
 * @author     Free WPTP <mozillavvd@gmail.com>
 */

if ( ! class_exists( 'BBPS_i18n' ) ) {

	class BBPS_i18n {

		/**
		 * Core singleton class
		 * @var self
		 */
		private static $_instance;

		/**
		 * Gets the instance of this class.
		 *
		 * @return self
		 */
		public static function getInstance() {
			if ( ! ( self::$_instance instanceof self ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Loads the plugin text domain for translation.
		 *
		 * @since    1.0
		 */
		public function load_plugin_textdomain() {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'bbpress-shortcodes' );

			load_textdomain( 'bbpress-shortcodes', trailingslashit( WP_LANG_DIR ) . 'bbpress-shortcodes/bbpress-shortcodes-' . $locale . '.mo' );
			load_plugin_textdomain( 'bbpress-shortcodes', false, dirname( dirname( plugin_basename( BBPS_PLUGIN_FILE ) ) ) . '/languages/' );
		}


		/**
		 * TinyMCE locales function.
		 */
		function add_tinymce_locales( $locales ) {
			$locales['bbpress_shortcodes'] = plugin_dir_path( __FILE__ ) . 'bbp-shortcodes-editor-i18n.php';

			return $locales;
		}
	}
}
