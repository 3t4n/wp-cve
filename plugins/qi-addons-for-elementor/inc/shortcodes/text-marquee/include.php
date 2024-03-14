<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/text-marquee/class-qiaddonsforelementor-text-marquee-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/text-marquee/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
