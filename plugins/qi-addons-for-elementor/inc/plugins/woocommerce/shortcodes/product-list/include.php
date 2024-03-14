<?php

include_once QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/woocommerce/shortcodes/product-list/class-qiaddonsforelementor-product-list-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/woocommerce/shortcodes/product-list/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
