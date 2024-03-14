<?php

namespace TotalContest\Admin\Modules;

use TotalContestVendors\TotalCore\Admin\Pages\Page as AdminPageContract;

/**
 * Class Page
 * @package TotalContest\Admin\Modules
 */
class Page extends AdminPageContract {
	public function assets() {
		// TotalContest
		wp_enqueue_script( 'totalcontest-admin-modules');
		wp_enqueue_style( 'totalcontest-admin-modules');
	}

	public function render() {
		include_once __DIR__ . '/views/index.php';
	}
}