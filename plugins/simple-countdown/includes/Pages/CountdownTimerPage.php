<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\pages;

use GPLSCore\GPLS_PLUGIN_WPSCTR\pages\AdminPage;

/**
 * CountDown Timer Page.
 */
class CountdownTimerPage extends AdminPage {

	/**
	 * Singleton Instance.
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Singleton Init.
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		parent::__construct();
	}

	/**
	 * Actions and Filters Hooks.
	 *
	 * @return void
	 */
	protected function hooks() {

	}

	/**
	 * Prepare page.
	 *
	 * @return void
	 */
	protected function prepare() {
		$this->page_props['page_title'] = esc_html__( 'Simple Countdown Timers', 'simple-countdown' );
		$this->page_props['menu_title'] = esc_html__( 'Simple Countdown', 'simple-countdown' );
		$this->page_props['cap']        = 'manage_options';
		$this->page_props['menu_slug']  = self::$plugin_info['name'] . '-page';
		$this->page_props['position']   = 10;
		$this->page_props['icon_url']   = 'dashicons-clock';
	}
}
