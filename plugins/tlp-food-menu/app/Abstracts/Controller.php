<?php
/**
 * Abstract Class for Controller.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Abstracts;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Abstract Class for Controller.
 */
abstract class Controller {
	/**
	 * Classes to include.
	 *
	 * @return array
	 */
	abstract public function classes();

	/**
	 * Init Classes.
	 *
	 * @return void
	 */
	protected function init() {
		foreach ( $this->classes() as $class ) {
			$class::get_instance();
		}
	}
}
