<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs;

use WPDeskFIVendor\WPDesk\Forms\Field;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\ImageInputField;
use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
use WPDeskFIVendor\WPDesk\Forms\Field\NoOnceField;
use WPDeskFIVendor\WPDesk\Forms\Field\SelectField;
use WPDeskFIVendor\WPDesk\Forms\Field\SubmitField;
use WPDeskFIVendor\WPDesk\Forms\Field\TextAreaField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\SettingsForm;
/**
 * General Settings Tab Page.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs
 */
final class GeneralSettings extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Tabs\FieldSettingsTab
{
    /** @var string slug od administrator role */
    const ADMIN_ROLE = 'administrator';
    const CUSTOMER_ROLE = 'customer';
    const SUBSCRIBER_ROLE = 'subscriber';
    const SHOP_MANAGER_ROLE = 'shop_manager';
    /**
     * @return array
     */
    public function get_roles() : array
    {
        $roles = \wp_roles()->get_names();
        unset($roles[self::ADMIN_ROLE], $roles[self::CUSTOMER_ROLE], $roles[self::SUBSCRIBER_ROLE]);
        return $roles;
    }
    /**
     * @return string
     */
    private function get_default_payment_methods() : string
    {
        $payment_methods = ['bank-transfer' => \esc_html__('Bank transfer', 'flexible-invoices'), 'cash' => \esc_html__('Cash', 'flexible-invoices'), 'other' => \esc_html__('Other', 'flexible-invoices')];
        return \implode("\n", $payment_methods);
    }
    /**
     * @return string[]
     */
    private function get_beacon_translations() : array
    {
        return ['company' => 'Company', 'main' => 'Main Settings'];
    }
    /**
     * @return array|Field[]
     */
    protected function get_fields() : array
    {
        $beacon = $this->get_beacon_translations();
        $docs_link = 'https://docs.flexibleinvoices.com/category/806-general-settings?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link&utm_content=general-settings';
        if (\get_locale() === 'pl_PL') {
            $docs_link = 'https://www.wpdesk.pl/docs/faktury-woocommerce-docs/?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-invoices-docs-link&utm_content=general-settings#ustawienia';
        }
        return [(new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\esc_html__('Company', 'flexible-invoices'))->set_description(\sprintf('<a href="%s" target="_blank">' . \esc_html__('Read user\'s manual →', 'flexible-invoices') . '</a>', $docs_link)), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('company_name')->set_label(\esc_html__('Company Name', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\TextAreaField())->set_name('company_address')->set_label(\esc_html__('Company Address', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('large-text hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('company_nip')->set_label(\esc_html__('VAT Number', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('bank_name')->set_label(\esc_html__('Bank Name', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('account_number')->set_label(\esc_html__('Bank Account Number', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\ImageInputField())->set_name('company_logo')->set_label(\esc_html__('Logo', 'flexible-invoices'))->set_attribute('data-beacon_search', $beacon['company'])->add_class('regular-text hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\esc_html__('General Settings', 'flexible-invoices')), (new \WPDeskFIVendor\WPDesk\Forms\Field\TextAreaField())->set_name('payment_methods')->set_label(\esc_html__('Payment Methods', 'flexible-invoices'))->set_default_value($this->get_default_payment_methods())->add_class('input-text wide-input hs-beacon-search')->set_attribute('data-beacon_search', $beacon['main']), (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('roles')->set_label(\esc_html__('Roles', 'flexible-invoices'))->set_description(\esc_html__('Select the User Roles that will be given permission to manage Invoices. The administrator has unlimited permissions.', 'flexible-invoices'))->set_options($this->get_roles())->add_class('select2')->set_multiple()->set_attribute('data-beacon_search', $beacon['main'])->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\NoOnceField(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\SettingsForm::NONCE_ACTION))->set_name(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\SettingsForm::NONCE_NAME), (new \WPDeskFIVendor\WPDesk\Forms\Field\SubmitField())->set_name('save')->set_label(\esc_html__('Save changes', 'flexible-invoices'))->add_class('button-primary')];
    }
    /**
     * @return string
     */
    public static function get_tab_slug() : string
    {
        return 'general';
    }
    /**
     * @return string
     */
    public function get_tab_name() : string
    {
        return \esc_html__('General', 'flexible-invoices');
    }
}
