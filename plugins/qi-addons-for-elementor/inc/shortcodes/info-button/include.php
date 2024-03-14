<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/info-button/class-qiaddonsforelementor-info-button-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/info-button/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
