<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/testimonials-list/class-qiaddonsforelementor-testimonials-list-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/testimonials-list/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
