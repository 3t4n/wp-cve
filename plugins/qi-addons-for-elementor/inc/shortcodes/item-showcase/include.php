<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/item-showcase/class-qiaddonsforelementor-item-showcase-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/item-showcase/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
