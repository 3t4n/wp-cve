<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\Component;

/**
 * Class MappedTaxClassComponent, mapped tax class form field component.
 * @package WPDesk\Library\DropshippingXmlCore\Form\Fields\Component
 */
class MappedTaxClassComponent extends \DropshippingXmlFreeVendor\WPDesk\Forms\Field\BasicField
{
    public function __construct()
    {
        parent::__construct();
        $this->attributes['multiple'] = \true;
    }
    public function set_items(array $items) : self
    {
        $this->meta['items'] = $items;
        return $this;
    }
    public function get_items() : array
    {
        return isset($this->meta['items']) && \is_array($this->meta['items']) ? $this->meta['items'] : array();
    }
    public function get_template_name() : string
    {
        return 'mapped-tax-class-component';
    }
}
