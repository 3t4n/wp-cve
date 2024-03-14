<?php
/**
 * This file contains the class for registering the plugin's settings page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/pages
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class that registers the plugin's settings page.
 */
class Nelio_Content_Settings_Page extends Nelio_Content_Abstract_Page {

	public function __construct() {

		parent::__construct(
			'nelio-content',
			'nelio-content-settings',
			_x( 'Settings', 'text', 'nelio-content' ),
			nc_can_current_user_manage_plugin()
		);

	}//end __construct()

	// @Implements
	// phpcs:ignore
	public function enqueue_assets() {

		$screen = get_current_screen();
		if ( 'nelio-content_page_nelio-content-settings' !== $screen->id ) {
			return;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		wp_enqueue_script(
			'nelio-content-settings-page',
			nelio_content()->plugin_url . '/assets/dist/js/settings-page.js',
			array( $settings->get_generic_script_name() ),
			nc_get_script_version( 'settings-page' ),
			true
		);

		$tab = 'social-profiles';
		if ( isset( $_GET['tab'] ) ) { // phpcs:ignore
			$tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) ); // phpcs:ignore
		}//end if

		switch ( $tab ) {

			case 'social-profiles':
				$this->enqueue_tab_assets( 'social-profile-settings', 'social-profiles-tab-content' );
				break;

			case 'automations':
				$this->enqueue_tab_assets( 'automations-settings', 'automations-tab-content' );
				break;

			case 'task-presets':
				$this->enqueue_tab_assets( 'task-presets-settings', 'task-presets-tab-content' );
				break;

			case 'external-calendars':
				$this->enqueue_tab_assets( 'external-calendar-settings', 'external-calendars-tab-content' );
				break;

			case 'feeds':
				$this->enqueue_tab_assets( 'feed-settings', 'feeds-tab-content' );
				break;

			case 'advanced':
				wp_add_inline_script(
					'nelio-content-settings-page',
					'document.getElementById( "nelio-content-settings-submit-button" ).style.display = "block";'
				);

		}//end switch

	}//end enqueue_assets()

	// @Overwrites
	// phpcs:ignore
	public function display() {

		echo '<div class="wrap">';

		printf(
			'<h1 class="wp-heading-inline">%s</h1><span id="nelio-content-settings-title"></span>',
			esc_html_x( 'Nelio Content - Settings', 'text', 'nelio-content' )
		);

		settings_errors();

		echo '<form id="nelio-content-settings-form" method="post" action="options.php">';

		$settings = Nelio_Content_Settings::instance();
		settings_fields( $settings->get_option_group() );
		do_settings_sections( $settings->get_settings_page_name() );
		echo '<div id="nelio-content-settings-submit-button" style="display:none;">';
		submit_button();
		echo '</div>';

		echo '</form>';

		echo '</div>';
	}//end display()

	private function enqueue_tab_assets( $script, $target_id ) {

		$handle    = 'nelio-content-' . $script;
		$target_id = 'nelio-content-' . $target_id;

		wp_enqueue_style(
			$handle,
			nelio_content()->plugin_url . '/assets/dist/css/' . $script . '.css',
			array( 'nelio-content-components' ),
			nelio_content()->plugin_version
		);

		nc_enqueue_script_with_auto_deps( $handle, $script, true );
		wp_add_inline_script(
			$handle,
			sprintf(
				'NelioContent.initPage( %s );',
				wp_json_encode( $target_id )
			)
		);

	}//end enqueue_tab_assets()

}//end class
