<?php

namespace TotalContest\Admin\Modules\Templates;

use TotalContestVendors\TotalCore\Admin\Pages\Page as TotalCoreAdminPage;
use TotalContestVendors\TotalCore\Helpers\Tracking;

/**
 * Class Page
 * @package TotalContest\Admin\Modules\Templates
 */
class Page extends TotalCoreAdminPage {
	/**
	 * Page assets.
	 */
	public function assets() {
		/**
		 * @asset-script totalcontest-admin-modules
		 */
		wp_enqueue_script( 'totalcontest-admin-modules' );
		/**
		 * @asset-style totalcontest-admin-modules
		 */
		wp_enqueue_style( 'totalcontest-admin-modules' );
	}

	/**
	 * Page content.
	 */
	public function render() {
		include __DIR__ . '/views/index.php';
	}
}
