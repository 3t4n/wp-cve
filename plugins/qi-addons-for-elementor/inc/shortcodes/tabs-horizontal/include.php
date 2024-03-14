<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/tabs-horizontal/class-qiaddonsforelementor-tabs-horizontal-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/tabs-horizontal/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
