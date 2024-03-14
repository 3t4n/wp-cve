<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/accordion/class-qiaddonsforelementor-accordion-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/accordion/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
