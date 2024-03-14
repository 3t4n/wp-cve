<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once dirname( __FILE__ ) . '/class-pages-breadcrumbs.php';

/**
 * *This loads the diagnostic page
 * todo: must inherit PagesBreadcrumbs class
 */
class PageDiagnostic extends PagesBreadcrumbs {


	public function __construct() {
		parent::__construct();
		require dirname( __DIR__, 2 ) . '/views/adminHeader.php';
		require dirname( __DIR__, 2 ) . '/views/diagnostic.php';
		$scc_diagnostics = new Stylish_Cost_Calculator_Diagnostic();
		$scc_diagnostics->diagnostic_page();
		require dirname( __DIR__, 2 ) . '/views/adminFooter.php';
	}
}
new PageDiagnostic();
