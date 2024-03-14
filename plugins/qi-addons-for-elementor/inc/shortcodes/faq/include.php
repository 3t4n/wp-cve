<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/faq/class-qiaddonsforelementor-faq-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/faq/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
