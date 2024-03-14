<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields;

use WPDeskFIVendor\WPDesk\Forms\Field;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
/**
 * Disable field adapter.
 *
 * @package WPDesk\FIT\Settings\Fields
 */
class DisableFieldProAdapter
{
    /**
     * @var Field\BasicField
     */
    private $field;
    /**
     * @var string
     */
    private $name;
    /**
     * @var bool
     */
    private $show_link;
    /**
     * @param Field $field
     */
    public function __construct(string $name, \WPDeskFIVendor\WPDesk\Forms\Field $field, bool $show_pro_link = \false)
    {
        $this->name = $name;
        $this->show_link = $show_pro_link;
        $this->field = $field;
    }
    public function get_field()
    {
        $field_description = '';
        if ($this->field->has_description()) {
            $field_description = $this->field->get_description();
        }
        if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_super()) {
            $this->field->set_disabled();
            $upgrade_link = '';
            $upgrade_link_id = $this->field->get_ID();
            if ($this->show_link) {
                $upgrade_pro_url = \get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/faktury-woocommerce/?utm_source=wp-admin-plugins&utm_medium=quick-link&utm_campaign=flexible-invoices-plugins-upgrade-link&utm_content=' . $upgrade_link_id : 'https://www.flexibleinvoices.com/products/flexible-invoices-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=flexible-invoices-plugins-upgrade-link&utm_content=' . $upgrade_link_id;
                $upgrade_link = '<span class="pro-url"><a href="' . \esc_url($upgrade_pro_url) . '" target="_blank">' . \esc_html__('Upgrade to PRO &rarr;', 'flexible-invoices') . '</a></span>';
            }
            if ($field_description) {
                $this->field->set_description($field_description . '<br/>' . $upgrade_link);
            } else {
                $this->field->set_description($field_description . $upgrade_link);
            }
            return $this->field;
        }
        $this->field->set_name($this->name);
        if ($field_description) {
            $this->field->set_description($field_description);
        }
        return $this->field;
    }
}
