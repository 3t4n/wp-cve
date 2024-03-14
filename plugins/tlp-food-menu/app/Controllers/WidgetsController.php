<?php
/**
 * Widget Controller Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers;

use RT\FoodMenu\Widgets as Widgets;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Widget Controller Class.
 */
class WidgetsController {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_action( 'widgets_init', [ $this, 'load_widgets' ] );
	}

	/**
	 * Load widgets.
	 *
	 * @return void
	 */
	public function load_widgets() {
		$widgets = [
			Widgets\FmWidget::class,
		];

		foreach ( $widgets as $widget ) {
			register_widget( $widget );
		}
	}
}
