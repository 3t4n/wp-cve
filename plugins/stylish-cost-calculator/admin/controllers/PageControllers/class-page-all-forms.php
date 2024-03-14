<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once dirname( __FILE__ ) . '/class-pages-breadcrumbs.php';
/**
 * *This class loads all available calculators and preview
 * todo: must inherit PagesBreadcrumbs class
 */

class AllForms extends PagesBreadcrumbs {



	protected $forms;

	public function __construct() {
		 require dirname( __DIR__, 1 ) . '/formController.php';
		$formC = new formController();
		$forms = $formC->read();

		parent::__construct();
		require dirname( __DIR__, 2 ) . '/views/adminHeader.php';
		require dirname( __DIR__, 2 ) . '/views/allCalculators.php';
		require dirname( __DIR__, 2 ) . '/views/adminFooter.php';
	}
}
new AllForms();
