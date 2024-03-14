<?php

namespace WPDesk\DropshippingXmlFree\Service\Mapper\Product;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductImageMapperService as ProductImageMapperServiceCore;

/**
 * Class ProductCreatorService, creates woocommerce product.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Service\Creator
 */
class ProductImageMapperService extends ProductImageMapperServiceCore {


	protected function get_images(): array {
		$result = parent::get_images();

		if ( \is_array( $result ) && ! empty( $result ) ) {
			$result = [ \reset( $result ) ];        }

		return $result;
	}
}
