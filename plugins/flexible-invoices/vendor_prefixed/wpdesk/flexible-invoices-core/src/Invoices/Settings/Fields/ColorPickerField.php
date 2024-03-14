<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
/**
 * Color picker field.
 *
 * @package WPDesk\FIT\Settings\Fields
 */
class ColorPickerField extends \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField
{
    /**
     * @return string
     */
    public function get_template_name()
    {
        return 'color-picker-input';
    }
}
