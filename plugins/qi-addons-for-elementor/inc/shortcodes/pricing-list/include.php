<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/pricing-list/class-qiaddonsforelementor-pricing-list-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/pricing-list/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
