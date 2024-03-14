<?php
/**
 *
 * TZWB Settings Page Class
 *
 * Adds the menu link in the backend and displays the settings page.
 *
 * @package ThemeZee Widget Bundle
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Use class to avoid namespace collisions.
if ( ! class_exists( 'TZWB_Settings_Page' ) ) :
	/**
	 * Settings Page Class
	 */
	class TZWB_Settings_Page {

		/**
		 * Setup the Settings Page class
		 *
		 * @return void
		 */
		static function setup() {

			// Add settings page to plugin tabs.
			add_filter( 'themezee_plugins_settings_tabs', array( __CLASS__, 'add_settings_page' ) );

			// Hook settings page to plugin page.
			add_action( 'themezee_plugins_page_widgets', array( __CLASS__, 'display_settings_page' ) );
		}

		/**
		 * Add settings page to tabs list on themezee plugin page
		 *
		 * @param array $tabs  Settings Tabs.
		 * @return array $tabs Settings Tabs
		 */
		static function add_settings_page( $tabs ) {

			// Add Boilerplate Settings Page to Tabs List.
			$tabs['widgets'] = esc_html__( 'Widget Bundle', 'themezee-widget-bundle' );

			return $tabs;
		}

		/**
		 * Display settings page
		 *
		 * @return void
		 */
		static function display_settings_page() {

			$plugin_data = get_plugin_data( TZWB_PLUGIN_FILE );

			ob_start();
			?>

			<div id="tzwb-settings" class="tzwb-settings-wrap">

				<h1><?php esc_html_e( 'Widget Bundle', 'themezee-widget-bundle' ); ?></h1>

				<form class="tzwb-settings-form" method="post" action="options.php">
					<?php
						settings_fields( 'tzwb_settings' );
						do_settings_sections( 'tzwb_settings' );
						submit_button();
					?>
					</form>

			</div>

			<?php
			echo ob_get_clean();
		}
	}

	// Run Settings Page Class.
	TZWB_Settings_Page::setup();

endif;
