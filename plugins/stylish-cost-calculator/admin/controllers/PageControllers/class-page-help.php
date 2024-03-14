<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once dirname( __FILE__ ) . '/class-pages-breadcrumbs.php';

/**
 * *This loads the help users page
 * todo: must inherit PagesBreadcrumbs class
 */
class PageHelp extends PagesBreadcrumbs {


	public function __construct() {
		parent::__construct();
		require dirname( __DIR__, 2 ) . '/views/adminHeader.php';
		require dirname( __DIR__, 2 ) . '/views/helpVideos.php';
		require dirname( __DIR__, 2 ) . '/views/adminFooter.php';
	}
}
new PageHelp();
