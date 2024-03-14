<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Cpts\CptsBase;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Post Type Abstract Class.
 */
abstract class Cpt extends Base {

	/**
	 * Custom Post Type arguments.
	 *
	 * @var array
	 */
	protected $cpt_args = array();

	/**
	 * Base CPT constructor.
	 *
	 */
	public function __construct() {
		$this->register_the_cpt();
		$this->hooks();
	}

	/**
	 * Add Custom Post Type.
	 *
	 * @return void
	 */
	public function add_cpt() {
		register_post_type(
			$this->_get_cpt_key(),
			$this->cpt_args
		);
	}

	/**
	 * Register the custom post type.
	 *
	 * @return void
	 */
	protected function register_the_cpt() {
		$this->setup_cpt_args();
		add_action( 'init', array( $this, 'add_cpt' ), 100 );
	}

	/**
	 * Hooks function.
	 *
	 * @return void
	 */
	abstract protected function hooks();

	/**
	 * Setup CPT arguments.
	 *
	 * @return void
	 */
	abstract protected function setup_cpt_args();

	/**
	 * Create the CPT Key and return it.
	 *
	 * @return void
	 */
	abstract public static function _get_cpt_key();

	/**
	 * Get CPT Key.
	 *
	 * @return string|null
	 */
	public static function get_cpt_key() {
		return static::_get_cpt_key();
	}

	/**
	 * Check if current CPT Page.
	 *
	 * @return boolean
	 */
	protected function is_cpt_page() {
		$screen = get_current_screen();
		return ( is_object( $screen ) && ! is_wp_error( $screen ) && ! empty( $screen->post_type ) && ( 'post' === $screen->base ) && ( static::_get_cpt_key() === $screen->post_type ) );
	}

}
