<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Pages;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Cpts\CountDownTimerCPT;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Pages\PagesBase\AdminPage;
use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\QuickCountDownTimerSettings;

/**
 * Quick CountDown Timer Page.
 */
class QuickCountdownTimerPage extends AdminPage {

	/**
	 * Singleton Instance.
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Page Settings.
	 *
	 * @var Settings
	 */
	public $settings;

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
		$this->page_props['menu_title']  = esc_html__( 'Quick Countdown Generator', 'simple-countdown' );
		$this->page_props['page_title']  = esc_html__( 'Quick Countdown Timer', 'simple-countdown' );
		$this->page_props['cap']         = 'manage_options';
		$this->page_props['menu_slug']   = self::$plugin_info['name'] . '-page';
		$this->page_props['parent_slug'] = 'edit.php?post_type=' . CountDownTimerCPT::get_cpt_key();
		$this->page_props['position']    = 3;
		$this->settings                    = QuickCountDownTimerSettings::init();
		$this->tabs                      = array(
			'general' => array(
				'title'    => '',
				'default'  => true,
				'template' => 'quick-countdown-timer-template.php',
			),
		);
		$this->assets                    = array(
			array(
				'type'   => 'css',
				'handle' => self::$plugin_info['name'] . '-admin-flipdown-css',
				'url'    => self::$plugin_info['url'] . 'assets/libs/flipdown.min.css',
			),
			array(
				'type'   => 'js',
				'handle' => 'jquery',
			),
			array(
				'type'   => 'css',
				'handle' => 'wp-color-picker',
			),
			array(
				'type'   => 'js',
				'handle' => 'wp-color-picker',
			),
			array(
				'type'       => 'js',
				'handle'     => self::$plugin_info['name'] . '-quick-countdown-timer',
				'url'        => self::$plugin_info['url'] . 'assets/dist/js/admin/back-quick-countdown-timer.min.js',
				'dependency' => array( 'jquery' ),
				'localized'  => array(
					'name' => str_replace( '-', '_', self::$plugin_info['name'] . '-localized-data' ),
					'data' => array(
						'prefix'         => self::$plugin_info['name'],
						'classes_prefix' => self::$plugin_info['classes_prefix'],
						'labels'         => array(
							'flipDownHeading' => array(
								'days'    => esc_html__( 'Days', 'simple-countdown' ),
								'hours'   => esc_html__( 'Hours', 'simple-countdown' ),
								'minutes' => esc_html__( 'Minutes', 'simple-countdown' ),
								'seconds' => esc_html__( 'Seconds', 'simple-countdown' ),
							),
						),
					),
				),
			),
		);
	}

	/**
	 * Print Quick Countdown TImer Fields.
	 *
	 * @return void
	 */
	public function print_fields() {
		$this->settings->print_fields();
	}
}
