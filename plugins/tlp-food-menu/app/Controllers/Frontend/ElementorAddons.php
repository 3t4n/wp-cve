<?php
/**
 * Elementor Addons Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Frontend;

use Elementor\Plugin as Elementor;
use RT\FoodMenu\Widgets\Elementor as Widgets;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Elementor Addons Class.
 */
class ElementorAddons {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		if ( did_action( 'elementor/loaded' ) ) {
			add_action( 'elementor/widgets/register', [ $this, 'registerWidgets' ] );
		}
	}

	/**
	 * Registers Elementor Widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	public function registerWidgets( $widgets_manager ) {
		$widgets = apply_filters(
			'rtfm_elementor_widgets',
			[
				Widgets\Shortcodes::class,
			]
		);

		foreach ( $widgets as $widget ) {
			$widgets_manager->register( new $widget() );
		}
	}
}
