<?php
/**
 * Ajax Controller Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers;

use RT\FoodMenu\Controllers\Admin\Ajax as AdminAjax;
use RT\FoodMenu\Controllers\Frontend\Ajax as FrontendAjax;
use RT\FoodMenu\Abstracts\Controller;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Ajax Controller Class.
 */
class AjaxController extends Controller {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Ajax.
	 *
	 * @var array
	 */
	private $ajax = [];

	/**
	 * Classes to include.
	 *
	 * @return array
	 */
	public function classes() {
		$this
			->admin_ajax()
			->frontend_ajax();

		return $this->ajax;
	}

	/**
	 * Admin Ajax
	 *
	 * @return Object
	 */
	private function admin_ajax() {
		$this->ajax[] = AdminAjax\Preview::class;
		$this->ajax[] = AdminAjax\Settings::class;
		$this->ajax[] = AdminAjax\ShortcodeSource::class;
		$this->ajax[] = AdminAjax\Shortcode::class;

		return $this;
	}

	/**
	 * Frontend Ajax
	 *
	 * @return Object
	 */
	private function frontend_ajax() {
		return $this;
	}
}
