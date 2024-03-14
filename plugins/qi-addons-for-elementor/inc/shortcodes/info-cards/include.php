<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/info-cards/class-qiaddonsforelementor-info-cards-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/info-cards/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
