<?php
/**
 * Public Controller Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers;

use RT\FoodMenu\Widgets as Widgets;
use RT\FoodMenu\Abstracts\Controller;
use RT\FoodMenu\Controllers\Hooks;
use RT\FoodMenu\Controllers\Frontend as Frontend;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Admin Controller Class.
 */
class FrontendController extends Controller {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Classes to include.
	 *
	 * @return array
	 */
	public function classes() {
		$classes = [];

		$classes[] = Hooks\ActionHooks::class;
		$classes[] = Hooks\FilterHooks::class;
		$classes[] = Widgets\Vc\VcAddon::class;
		$classes[] = Frontend\Shortcode::class;
		$classes[] = Frontend\Template::class;
		$classes[] = Frontend\Styles::class;
		$classes[] = Frontend\ElementorAddons::class;

		return $classes;
	}
}
