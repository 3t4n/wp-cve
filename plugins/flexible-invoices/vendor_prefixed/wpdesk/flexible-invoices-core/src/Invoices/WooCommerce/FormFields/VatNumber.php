<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields;

/**
 * Define vat number field.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields
 */
class VatNumber extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\FormFields\FormField
{
    /**
     * @var string
     */
    protected $field_id;
    /**
     * @var string
     */
    protected $label;
    /**
     * @var string
     */
    protected $placeholder;
    /**
     * @param string $field_id    Field ID.
     * @param string $label       Label.
     * @param string $placeholder Placeholder.
     */
    public function __construct(string $field_id, string $label, string $placeholder)
    {
        parent::__construct($field_id);
        $this->field_id = $field_id;
        $this->label = $label;
        $this->placeholder = $placeholder;
    }
    /**
     * @param array $fields
     * @param array $args
     *
     * @return array
     */
    public function add_address_replacements(array $fields, array $args) : array
    {
        if (!empty($args[$this->get_field_id()])) {
            $fields['{' . $this->get_field_id() . '}'] = $this->label . ': ' . $args[$this->get_field_id()];
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
        $user_id = \get_current_user_id();
        $default = '';
        if ($user_id) {
            $default = \get_user_meta($user_id, $this->field_id, \true);
        }
        return ['label' => $this->label, 'placeholder' => $this->placeholder, 'required' => $this->get_required(), 'class' => \is_admin() ? '' : ['form-row-wide woocommerce-form__label woocommerce-form__label-for-checkbox'], 'clear' => \true, 'priority' => $field_priority, 'default' => $default ?? ''];
    }
    /**
     * Prepare admin field.
     *
     * @return array
     */
    protected function prepare_admin_field() : array
    {
        $field = $this->prepare_checkout_field();
        $field['show'] = \false;
        return $field;
    }
}
