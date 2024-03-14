<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/process/class-qiaddonsforelementor-process-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/process/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
