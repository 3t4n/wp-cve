<?php
/**
 * Admin Controller Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers;

use RT\FoodMenu\Controllers\Admin as Admin;
use RT\FoodMenu\Abstracts\Controller;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Admin Controller Class.
 */
class AdminController extends Controller {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Admin.
	 *
	 * @var array
	 */
	private $admin = [];

	/**
	 * Classes to include.
	 *
	 * @return array
	 */
	public function classes() {
		$this
			->notices()
			->settings()
			->metabox();

		return $this->admin;
	}

	/**
	 * Notices.
	 *
	 * @return object
	 */
	private function notices() {
		//$this->admin[] = Admin\Notices\Review::class;
		if ( !TLPFoodMenu()->has_pro() ){
			$this->admin[] = Admin\Notices\BlackFriday::class;
		}

		return $this;
	}

	/**
	 * Settings.
	 *
	 * @return object
	 */
	private function settings() {
		$this->admin[] = Admin\Upgrade::class;
		$this->admin[] = Admin\Settings::class;
		$this->admin[] = Admin\AdminColumns::class;
		$this->admin[] = Admin\ShortcodeButton::class;

		return $this;
	}

	/**
	 * Metabox.
	 *
	 * @return object
	 */
	private function metabox() {
		$this->admin[] = Admin\Metabox\PostMeta::class;
		$this->admin[] = Admin\Metabox\ShortcodeMeta::class;

		return $this;
	}
}
