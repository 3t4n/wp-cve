<?php

include_once QI_ADDONS_FOR_ELEMENTOR_INC_PATH . '/blog/shortcodes/blog-list/class-qiaddonsforelementor-blog-list-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_INC_PATH . '/blog/shortcodes/blog-list/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
