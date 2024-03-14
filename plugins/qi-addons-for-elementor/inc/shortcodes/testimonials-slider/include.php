<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/testimonials-slider/class-qiaddonsforelementor-testimonials-slider-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/testimonials-slider/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
