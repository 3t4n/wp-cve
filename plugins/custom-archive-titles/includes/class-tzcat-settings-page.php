<?php
/**
 * TZCAT Settings Page Class
 *
 * Adds a new tab on the themezee plugins page and displays the settings page.
 *
 * @package ThemeZee Custom Archive Titles
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * TZCAT Settings Page Class
 */
class TZCAT_Settings_Page {

	/**
	 * Setup the Settings Page class
	 *
	 * @return void
	 */
	static function setup() {

		// Add settings page to admin menu.
		add_action( 'admin_menu', array( __CLASS__, 'add_settings_page' ) );

	}

	/**
	 * Add Plugins page to admin menu
	 *
	 * @return void
	 */
	static function add_settings_page() {

		add_options_page(
			esc_html__( 'Custom Archive Titles', 'custom-archive-titles' ),
			esc_html__( 'Custom Archive Titles', 'custom-archive-titles' ),
			'manage_options',
			'themezee-custom-archive-titles',
			array( __CLASS__, 'display_settings_page' )
		);

	}

	/**
	 * Display settings page
	 *
	 * @return void
	 */
	static function display_settings_page() {

		ob_start();
		?>

		<div id="tzcat-settings" class="tzcat-settings-wrap wrap">

			<h1><?php esc_html_e( 'Custom Archive Titles', 'custom-archive-titles' ); ?></h1>

			<form class="tzcat-settings-form" method="post" action="options.php">
				<?php
					settings_fields( 'tzcat_settings' );
					do_settings_sections( 'tzcat_settings' );
					submit_button();
				?>
			</form>

		</div>

		<?php
		echo ob_get_clean();
	}
}

// Run TZCAT Settings Page Class.
TZCAT_Settings_Page::setup();
