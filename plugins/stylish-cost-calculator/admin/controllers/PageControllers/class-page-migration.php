<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once dirname( __FILE__ ) . '/class-pages-breadcrumbs.php';
class PageMigration extends PagesBreadcrumbs {

	function __construct() {
		parent::__construct();
		require dirname( __DIR__, 2 ) . '/views/adminHeader.php';
		require dirname( __DIR__, 2 ) . '/views/migration.php';
		require dirname( __DIR__, 2 ) . '/views/adminFooter.php';
	}
}
new PageMigration();
