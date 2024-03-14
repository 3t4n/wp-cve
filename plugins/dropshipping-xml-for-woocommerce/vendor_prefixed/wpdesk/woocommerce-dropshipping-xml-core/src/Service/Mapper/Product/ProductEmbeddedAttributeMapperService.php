<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product;

use DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent;
use WC_Product_Variable;
use WC_Product_Variation;
use WC_Product;
/**
 * Class ProductEmbeddedAttributeMapperService, creates attributes for embedded variations.
 */
class ProductEmbeddedAttributeMapperService extends \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Mapper\Product\ProductAttributeMapperService
{
    protected function get_attribute_name() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_ATTRIBUTE_NAME;
    }
    protected function get_attribute_value() : string
    {
        return \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_ATTRIBUTE_VALUE;
    }
    protected function get_attributes_from_field() : array
    {
        return $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_ATTRIBUTE, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
    }
    protected function is_attribute_taxonomy() : bool
    {
        return \DropshippingXmlFreeVendor\WPDesk\Forms\Field\CheckboxField::VALUE_TRUE === $this->mapper->get_raw_value(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component\VariationComponent::PRODUCT_ATTRIBUTE_AS_TAXONOMY, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportMapperFormFields::VARIATION_EMBEDDED);
    }
}
