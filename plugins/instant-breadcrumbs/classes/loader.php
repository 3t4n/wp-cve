<?php
require_once( dirname( __FILE__ ) . '/class-instant-breadcrumbs.php' );
require_once( dirname( __FILE__ ) . '/class-instant-breadcrumbs-settings.php' );
require_once( dirname( __FILE__ ) . '/class-instant-breadcrumbs-widget.php' );

function instant_breadcrumb( $padding = '', $ret = false ) {
	$result = Instant_Breadcrumbs::do_crumbs( "<div class='ib-trail'></div>", $padding );
	if ( $ret ) {
		return $result;
	}
	// @codingStandardsIgnoreStart
	echo $result;
	// @codingStandardsIgnoreEnd
}
