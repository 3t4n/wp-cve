<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/interactive-banner/class-qiaddonsforelementor-interactive-banner-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/interactive-banner/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
