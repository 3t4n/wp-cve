<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field\BasicField;
/**
 * Template col.
 *
 * @package WPDesk\FIT\Settings\Fields
 */
class Col extends \WPDeskFIVendor\WPDesk\Forms\Field\BasicField
{
    /**
     * @return string
     */
    public function get_template_name()
    {
        return 'col';
    }
}
