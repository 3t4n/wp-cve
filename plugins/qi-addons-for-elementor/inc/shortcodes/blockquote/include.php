<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/blockquote/class-qiaddonsforelementor-blockquote-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/blockquote/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
