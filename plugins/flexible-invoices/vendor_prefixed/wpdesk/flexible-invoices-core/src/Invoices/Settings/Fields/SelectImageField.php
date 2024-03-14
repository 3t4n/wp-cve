<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field\BasicField;
/**
 * Select image field.
 *
 * @package WPDesk\FIT\Settings\Fields
 */
class SelectImageField extends \WPDeskFIVendor\WPDesk\Forms\Field\BasicField
{
    public function get_template_name()
    {
        return 'select-image-field';
    }
    public function set_options($options)
    {
        $this->meta['possible_values'] = $options;
        return $this;
    }
    public function set_multiple()
    {
        $this->attributes['multiple'] = \true;
        return $this;
    }
}
