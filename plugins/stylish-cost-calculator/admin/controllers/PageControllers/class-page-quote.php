<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once dirname( __FILE__ ) . '/class-pages-breadcrumbs.php';

/**
 * *This class is to load add new calculators page inherits the main class
 * todo: must inherit PagesBreadcrumbs class
 */

/**
 * *This calss is to load calculator quotes
 */
class PageQuote extends PagesBreadcrumbs {


	function __construct() {
		parent::__construct();
		require dirname( __DIR__, 2 ) . '/views/adminHeader.php';
		require dirname( __DIR__, 2 ) . '/views/calculatorQuotes.php';
		require dirname( __DIR__, 2 ) . '/views/adminFooter.php';
	}
}
new PageQuote();
