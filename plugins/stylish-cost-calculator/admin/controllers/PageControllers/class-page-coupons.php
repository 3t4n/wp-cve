<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once dirname( __FILE__ ) . '/class-pages-breadcrumbs.php';

/**
 * *This loads coupon page
 * todo: must inherit PagesBreadcrumbs class
 */
class PageCoupons extends PagesBreadcrumbs {

	public function __construct() {

		parent::__construct();
		require dirname( __DIR__, 1 ) . '/couponController.php';

		require dirname( __DIR__, 2 ) . '/views/adminHeader.php';
		require dirname( __DIR__, 2 ) . '/views/coupons.php';
		require dirname( __DIR__, 2 ) . '/views/adminFooter.php';
	}
}
new PageCoupons();
