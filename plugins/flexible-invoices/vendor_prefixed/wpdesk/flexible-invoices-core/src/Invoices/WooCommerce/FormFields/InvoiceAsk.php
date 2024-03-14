<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields;

/**
 * Define invoice ask billing field.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields
 */
class InvoiceAsk extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields\FormField
{
    const CHECKBOX_CHECKED_VALUE = '1';
    const CHECKBOX_UNCHECKED_VALUE = '0';
    /**
     * Field label.
     *
     * @var string
     */
    protected $label;
    /**
     * @param string $field_id Field ID.
     * @param string $label    Label.
     */
    public function __construct(string $field_id, string $label)
    {
        parent::__construct($field_id);
        $this->label = $label;
    }
    /**
     * @return string
     */
    public function get_label() : string
    {
        return $this->label;
    }
    /**
     * @param array $args
     *
     * @return bool
     */
    private function is_field_checked_from_args(array $args) : bool
    {
        return !empty($args[$this->get_field_id()]) && (string) $args[$this->get_field_id()] === self::CHECKBOX_CHECKED_VALUE;
    }
    /**
     * @param array $fields
     * @param array $args
     *
     * @return array
     */
    public function add_address_replacements(array $fields, array $args) : array
    {
        $value = isset($args[$this->get_field_id()]) && $args[$this->get_field_id()] ? \esc_html__('yes', 'flexible-invoices') : \esc_html__('no', 'flexible-invoices');
        if ($this->is_field_checked_from_args($args)) {
            $fields['{' . $this->get_field_id() . '}'] = $this->label . ': ' . $value;
        } else {
            $fields['{' . $this->get_field_id() . '}'] = '';
        }
        return $fields;
    }
    /**
     * Prepare checkout field.
     *
     * @param int|null $field_priority Field priority
     *
     * @return array
     */
    protected function prepare_checkout_field($field_priority = null) : array
    {
        return ['label' => $this->label, 'required' => $this->get_required(), 'class' => ['form-row-wide'], 'type' => 'checkbox', 'clear' => \true, 'priority' => $field_priority];
    }
    /**
     * Prepare admin field.
     *
     * @return array
     */
    protected function prepare_admin_field() : array
    {
        return ['label' => $this->label, 'required' => $this->get_required(), 'class' => 'form-row-wide', 'type' => 'select', 'clear' => \true, 'options' => [self::CHECKBOX_UNCHECKED_VALUE => \esc_html__('no', 'flexible-invoices'), self::CHECKBOX_CHECKED_VALUE => \esc_html__('yes', 'flexible-invoices')], 'show' => \false];
    }
}
