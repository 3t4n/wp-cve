<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once dirname( __FILE__ ) . '/class-pages-breadcrumbs.php';
/**
 * *This loads the Licence page
 * todo: must inherit PagesBreadcrumbs class
 */
class pageLicence extends PagesBreadcrumbs {

	public function __construct() {
		parent::__construct();
		require dirname( __DIR__, 2 ) . '/views/licence.php';
	}
}
new pageLicence();
