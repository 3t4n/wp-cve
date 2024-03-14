<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/parallax-images/class-qiaddonsforelementor-parallax-images-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/parallax-images/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
