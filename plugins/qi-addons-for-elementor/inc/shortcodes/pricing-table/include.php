<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/pricing-table/class-qiaddonsforelementor-pricing-table-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/pricing-table/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
