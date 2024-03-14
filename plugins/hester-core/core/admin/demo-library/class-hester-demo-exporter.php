<?php
/**
 * Hester Demo Library. Install a copy of a Hester demo to your website.
 *
 * @package Hester Core
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hester Demo Exporter Class.
 *
 * @since 1.0.0
 * @package Hester Core
 */
final class Hester_Demo_Exporter {

	/**
	 * Singleton instance of the class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	private static $instance;

	/**
	 * Demo ID.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $demo_id;

	/**
	 * Main Hester Demo Exporter Instance.
	 *
	 * @since 1.0.0
	 * @return Hester_Demo_Exporter
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Hester_Demo_Exporter ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add export listeners.
		add_action( 'init', array( $this, 'export' ) );
	}

	/**
	 * Export.
	 *
	 * @since 1.0.0
	 */
	public function export() {

		// Check if user has permission for this.
		/*
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}*/

		// Export Customizer.
		if ( isset( $_REQUEST['hester-core-customizer-export'] ) ) {
			// phpcs:ignore

			if ( ! class_exists( 'Hester_Customizer_Import_Export' ) ) {

				$class_customizer_import = plugin_dir_path( __FILE__ ) . 'importers/class-customizer-import-export.php';

				if ( file_exists( $class_customizer_import ) ) {
					require_once $class_customizer_import;

					Hester_Customizer_Import_Export::export();
				}
			}
		}

		// Export Widgets.
		if ( isset( $_REQUEST['hester-core-widgets-export'] ) ) {
			// phpcs:ignore

			if ( ! class_exists( 'Hester_Widgets_Import_Export' ) ) {

				$class_widgets_import = plugin_dir_path( __FILE__ ) . 'importers/class-widgets-import-export.php';

				if ( file_exists( $class_widgets_import ) ) {
					require_once $class_widgets_import;

					Hester_Widgets_Import_Export::export();
				}
			}
		}

		// Export Options.
		if ( isset( $_REQUEST['hester-core-options-export'] ) ) {
			// phpcs:ignore

			if ( ! class_exists( 'Hester_Options_Import_Export' ) ) {

				$class_options_import = plugin_dir_path( __FILE__ ) . 'importers/class-options-import-export.php';

				if ( file_exists( $class_options_import ) ) {
					require_once $class_options_import;

					Hester_Options_Import_Export::export();
				}
			}
		}
	}
}

/**
 * The function which returns the one Hester_Demo_Exporter instance.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $hester_demo_exporter = hester_demo_exporter(); ?>
 *
 * @since 1.0.0
 * @return object
 */
function hester_demo_exporter() {
	return Hester_Demo_Exporter::instance();
}

hester_demo_exporter();
