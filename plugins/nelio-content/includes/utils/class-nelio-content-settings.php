<?php
/**
 * This file has the Settings class, which defines and registers Nelio Content's Settings.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * The Settings class, responsible of defining, registering, and providing access to all Nelio Content's settings.
 */
class Nelio_Content_Settings extends Nelio_Content_Abstract_Settings {

	private static $instance;

	/**
	 * Initialize the class, set its properties, and add the proper hooks.
	 *
	 * @since  1.0.0
	 * @access protected
	 */
	protected function __construct() {

		parent::__construct( 'nelio-content' );

	}//end __construct()

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Settings the single instance of this class.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if
		return self::$instance;

	}//end instance()

	/** . @Implements */
	public function set_tabs() { // phpcs:ignore

		if ( nc_use_editorial_calendar_only() ) {

			$this->do_set_tabs(
				array(
					array(
						'name'   => 'editorial-calendar',
						'label'  => _x( 'Editorial Calendar', 'text (settings tab)', 'nelio-content' ),
						'fields' => include nelio_content()->plugin_path . '/includes/data/editorial-calendar-settings.php',
					),
				)
			);

			return;

		}//end if

		// Add as many tabs as you want. If you have one tab only, no tabs will be shown at all.
		$tabs = array(

			array(
				'name'   => 'social-profiles',
				'label'  => _x( 'Social Profiles', 'text (settings tab)', 'nelio-content' ),
				'custom' => true,
			),

			array(
				'name'   => 'automations',
				'label'  => _x( 'Automations', 'text (settings tab)', 'nelio-content' ),
				'custom' => true,
			),

			array(
				'name'   => 'task-presets',
				'label'  => _x( 'Task Presets', 'text (settings tab)', 'nelio-content' ),
				'custom' => true,
			),

			array(
				'name'   => 'external-calendars',
				'label'  => _x( 'Calendars', 'text (settings tab)', 'nelio-content' ),
				'custom' => true,
			),

			array(
				'name'   => 'feeds',
				'label'  => _x( 'Feeds', 'text (settings tab)', 'nelio-content' ),
				'custom' => true,
			),

			array(
				'name'   => 'advanced',
				'label'  => _x( 'Advanced', 'text (settings tab)', 'nelio-content' ),
				'fields' => include nelio_content()->plugin_path . '/includes/data/advanced-tab.php',
			),

		);

		$this->do_set_tabs( $tabs );

	}//end set_tabs()

}//end class
