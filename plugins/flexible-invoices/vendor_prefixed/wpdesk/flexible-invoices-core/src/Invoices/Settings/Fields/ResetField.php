<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field\SubmitField;
/**
 * Reset settings field.
 *
 * @package WPDesk\FIT\Settings\Fields
 */
class ResetField extends \WPDeskFIVendor\WPDesk\Forms\Field\SubmitField
{
    /**
     * @return string
     */
    public function get_type()
    {
        return 'button';
    }
}
