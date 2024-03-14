<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/icon-with-text/class-qiaddonsforelementor-icon-with-text-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/icon-with-text/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
