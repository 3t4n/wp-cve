<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
/**
 * Class ProductEmbeddedImageMapperService, creates woocommerce product.
 */
class ProductEmbeddedImageMapperService extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductImageMapperService
{
    protected function get_img_field() : string
    {
        $img = $this->mapper->map(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_IMAGES, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
        return $img;
    }
}
