<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/separator/class-qiaddonsforelementor-separator-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/separator/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
