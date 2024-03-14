<?php

include_once QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/woocommerce/shortcodes/product-slider/class-qiaddonsforelementor-product-slider-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/woocommerce/shortcodes/product-slider/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
