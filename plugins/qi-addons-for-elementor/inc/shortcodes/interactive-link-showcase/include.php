<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/interactive-link-showcase/class-qiaddonsforelementor-interactive-link-showcase-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/interactive-link-showcase/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
