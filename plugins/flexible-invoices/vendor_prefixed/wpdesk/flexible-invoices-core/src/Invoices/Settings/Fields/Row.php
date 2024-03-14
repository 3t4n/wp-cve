<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field\BasicField;
/**
 * Template row.
 *
 * @package WPDesk\FIT\Settings\Fields
 */
class Row extends \WPDeskFIVendor\WPDesk\Forms\Field\BasicField
{
    /**
     * @var string
     */
    private $row_type;
    public function __construct($is_open = \true)
    {
        if ($is_open) {
            $row_type = 'open';
        } else {
            $row_type = 'close';
        }
        $this->row_type = $row_type;
        parent::__construct();
    }
    /**
     * @return string
     */
    public function get_template_name()
    {
        return 'row-' . $this->row_type;
    }
}
