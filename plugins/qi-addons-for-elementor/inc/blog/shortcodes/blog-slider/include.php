<?php

include_once QI_ADDONS_FOR_ELEMENTOR_INC_PATH . '/blog/shortcodes/blog-slider/class-qiaddonsforelementor-blog-slider-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_INC_PATH . '/blog/shortcodes/blog-slider/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
