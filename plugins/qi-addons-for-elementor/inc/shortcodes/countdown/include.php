<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/countdown/class-qiaddonsforelementor-countdown-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/countdown/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
