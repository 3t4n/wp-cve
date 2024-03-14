<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/button/class-qiaddonsforelementor-button-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/button/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
