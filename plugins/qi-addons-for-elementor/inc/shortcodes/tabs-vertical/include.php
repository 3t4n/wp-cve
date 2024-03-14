<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/tabs-vertical/class-qiaddonsforelementor-tabs-vertical-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/tabs-vertical/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
