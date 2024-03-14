<?php

namespace MyCustomizer\WooCommerce\Connector\Libs;

use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;

MczrAccess::isAuthorized();

class MczrTemplateOverrider {

	public static function addMczrTemplatePrecedence( $located, $template_name, $args, $template_path, $default_path ) {
		$product = ( \wc_get_product() );
		if ( 'mczr' == $product->product_type ) {
			$pluginFilePath = plugin_dir_path( __FILE__ ) . 'src/Resources/woocommerce/' . $template_name;
			if ( is_file( $pluginFilePath ) ) {
				return $pluginFilePath;
			}
		}
		return $located;
	}
}
