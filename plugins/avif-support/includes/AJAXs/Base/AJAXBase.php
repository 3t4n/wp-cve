<?php
namespace GPLSCore\GPLS_PLUGIN_AVFSTW\AJAXs\Base;

defined( 'ABSPATH' ) || exit;

use GPLSCore\GPLS_PLUGIN_AVFSTW\Base;
use GPLSCore\GPLS_PLUGIN_AVFSTW\Utils\NoticeUtilsTrait;

/**
 * AJAX Base Class.
 */
abstract class AJAXBase extends Base {

	use NoticeUtilsTrait;


	/**
	 * AJAXs.
	 *
	 * array(
	 *      'ajax_key' => array(
	 *          'action' => 'ajax action path',
	 *          'func'   => 'ajax_function_name',
	 *          'nopriv' => true|false
	 *      )
	 * )
	 *
	 * @var array
	 */
	protected $ajaxs = array();

	/**
	 * Singular Init.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->setup();
		$this->main_hooks();
		if ( method_exists( $this, 'hooks' ) ) {
			$this->hooks();
		}
	}

	/**
	 * Setup.
	 *
	 * @return void
	 */
	private function setup() {
		$this->setup_ajaxs();
	}

	/**
	 * Setup front and admin ajaxs.
	 *
	 * $this->admin_ajaxs
	 * $this->front_ajaxs
	 *
	 * @return void
	 */
	abstract protected function setup_ajaxs();

	/**
	 * Register AJAXs.
	 *
	 * @return void
	 */
	private function register_ajaxs() {
		foreach ( $this->ajaxs as $ajax_key => $ajax_action_arr ) {
			if ( ! empty( $ajax_action_arr['nopriv'] ) ) {
				if ( 'only' === $ajax_action_arr['nopriv'] ) {
					add_action( 'wp_ajax_nopriv_' . $ajax_action_arr['action'], array( $this, $ajax_action_arr['func'] ) );
					continue;
				} elseif ( $ajax_action_arr['nopriv'] ) {
					add_action( 'wp_ajax_nopriv_' . $ajax_action_arr['action'], array( $this, $ajax_action_arr['func'] ) );
				}
			}
			add_action( 'wp_ajax_' . $ajax_action_arr['action'], array( $this, $ajax_action_arr['func'] ) );
		}
	}

	/**
	 * Hooks.
	 *
	 * @return void
	 */
	private function main_hooks() {
		if ( ! wp_doing_ajax() ) {
			return;
		}
		$this->register_ajaxs();
	}

	/**
	 * Get AJAX Prop.
	 *
	 * @param string $ajax_key
	 * @param string $type
	 * @param string $key
	 * @return mixed
	 */
	public function get_ajax_prop( $ajax_key, $key ) {
		return $this->ajaxs[ $ajax_key ][ $key ];
	}
}
