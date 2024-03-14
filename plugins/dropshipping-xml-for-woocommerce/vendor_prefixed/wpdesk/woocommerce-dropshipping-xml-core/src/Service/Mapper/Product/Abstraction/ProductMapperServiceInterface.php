<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction;

use WC_Product;
/**
 * Interface ProductMapperServiceInterface, add interface to map wc_product;
 * @package WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\Abstraction
 */
interface ProductMapperServiceInterface
{
    public function update_product(\WC_Product $wc_product) : \WC_Product;
}
