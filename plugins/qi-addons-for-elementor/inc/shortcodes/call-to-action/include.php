<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/call-to-action/class-qiaddonsforelementor-call-to-action-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/call-to-action/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
