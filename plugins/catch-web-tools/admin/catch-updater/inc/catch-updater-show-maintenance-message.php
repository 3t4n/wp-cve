<?php
/**
 * Show Maintainance Page
 *
 * @package catch-updater
 *
 * @since catch-updater 0.2
 */

if ( ! class_exists( 'CatchUpdaterShowMaintenanceMessage' ) ) {
	class CatchUpdaterShowMaintenanceMessage {

		/**
		 * CatchUpdaterShowMaintenanceMessage default constructor that handles displaying of maintainance message if transient value is set
		 */
		function __construct() {
			if ( false !== get_transient( 'catch_updater_in_maintenance_mode' ) ) {
				add_action( 'template_include', array( $this, 'show_message' ) );
			}
		}

		/**
		 * display the message via loading a template
		 */
		function show_message() {
			global $wp;

			load_template( CATCHUPDATER_PATH . 'template/template-maintainance-message.php' );

		}
	}

	new CatchUpdaterShowMaintenanceMessage();
}