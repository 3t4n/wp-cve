<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/counter/class-qiaddonsforelementor-counter-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/counter/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
