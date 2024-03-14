<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/business-hours/class-qiaddonsforelementor-business-hours-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/business-hours/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
