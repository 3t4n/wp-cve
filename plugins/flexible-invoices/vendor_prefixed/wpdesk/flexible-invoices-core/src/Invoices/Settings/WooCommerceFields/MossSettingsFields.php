<?php

/**
 * Woocommerce Settings.
 *
 * @package WPDesk\FlexibleInvoicesWooCommerce
 */
namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields;

use WC_Tax;
use WPDeskFIVendor\WPDesk\Forms\Field;
use WPDeskFIVendor\WPDesk\Forms\Field\WooSelect;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableFieldProAdapter;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubEndField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubStartField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
use WPDeskFIVendor\WPDesk\Forms\Field\SelectField;
/**
 * Moss settings subpage.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields
 */
final class MossSettingsFields implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields\SubTabInterface
{
    /**
     * @return array
     */
    private function get_woocommerce_tax_classes() : array
    {
        $tax_classes = \WC_Tax::get_tax_classes();
        $classes_options['standard'] = \esc_html__('Standard', 'flexible-invoices');
        foreach ($tax_classes as $class) {
            $classes_options[\sanitize_title($class)] = \esc_html($class);
        }
        return $classes_options;
    }
    /**
     * @return string
     */
    private function get_moss_link() : string
    {
        return \esc_url(\get_locale() === 'pl_PL' ? 'https://wpde.sk/faktury-woocommerce-oss' : 'https://wpde.sk/flexible-invoices-oss', ['https']);
    }
    /**
     * @return string
     */
    private function get_doc_link() : string
    {
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_super()) {
            return \sprintf(\__('The EU OSS procedure is an extension of MOSS. From 07.2021 VAT on every transaction above €10.000 to other EU countries must be calculated based on the customer location, and you need to collect evidence of this (IP address and Billing Address). B2B transactions are subject to reverse charge. <a href="%s" target="_blank">Read this guide</a> for instructions on doing this.', 'flexible-invoices'), $this->get_moss_link());
        } else {
            return \sprintf('<a href="%1$s&utm_content=oss" target="_blank">%2$s</a>', \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Plugin::upgrade_to_pro_url(), \esc_html__('Upgrade to PRO and enable options below →', 'flexible-invoices'));
        }
    }
    /**
     * @return array|Field[]
     */
    public function get_fields() : array
    {
        $moss = 'MOSS';
        return [(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubStartField())->set_label(\esc_html__('OSS', 'flexible-invoices'))->set_name('moss'), (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\esc_html__('OSS Handling', 'flexible-invoices'))->set_description($this->get_doc_link()), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableFieldProAdapter('woocommerce_eu_vat_vies_validate', (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('')->set_label(\esc_html__('VIES Validation', 'flexible-invoices'))->set_sublabel(\esc_html__('Enable', 'flexible-invoices'))->add_class('hs-beacon-search woocommerce_eu_vat_vies_validate')->set_attribute('data-beacon_search', $moss)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableFieldProAdapter('woocommerce_eu_vat_failure_handling', (new \WPDeskFIVendor\WPDesk\Forms\Field\SelectField())->set_name('')->set_label(\esc_html__('Failed Validation Handling', 'flexible-invoices'))->set_options(['reject' => \esc_html__('Reject the order and show the customer an error message.', 'flexible-invoices'), 'accept_with_vat' => \esc_html__('Accept the order, but do not remove VAT.', 'flexible-invoices'), 'accept_without_vat' => \esc_html__('Accept the order and remove VAT.', 'flexible-invoices')])->add_class('vies-validation-fields hs-beacon-search')->set_attribute('data-beacon_search', $moss)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableFieldProAdapter('woocommerce_moss_tax_classes', (new \WPDeskFIVendor\WPDesk\Forms\Field\WooSelect())->set_name('')->set_label(\esc_html__('Tax class for OSS', 'flexible-invoices'))->set_description(\esc_html__('Select the tax classes that the plugin shall use to handling the OSS.', 'flexible-invoices'))->set_options($this->get_woocommerce_tax_classes())->set_attribute('multiple', 'multiple')->add_class('select2 vies-validation-fields hs-beacon-search')->set_attribute('data-beacon_search', $moss)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableFieldProAdapter('woocommerce_moss_validate_ip', (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('')->set_label(\esc_html__('Collect and Validate Evidence', 'flexible-invoices'))->set_sublabel(\esc_html__('Enable', 'flexible-invoices'))->set_description(\esc_html__('Option validates the customer IP address against their billing address, and prompts the customer to self-declare their address if they do not match.', 'flexible-invoices'))->add_class('vies-validation-fields hs-beacon-search')->set_attribute('data-beacon_search', $moss)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableFieldProAdapter('woocommerce_reverse_charge_description', (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('')->set_label(\esc_html__('Reverse charge description', 'flexible-invoices'))->set_default_value(\esc_html__('Reverse charge', 'flexible-invoices'))->add_class('vies-validation-fields hs-beacon-search')->set_attribute('data-beacon_search', $moss)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\DisableFieldProAdapter('woocommerce_vat_moss_description', (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('')->set_label(\esc_html__('VAT OSS rate description', 'flexible-invoices'))->add_class('vies-validation-fields hs-beacon-search')->set_attribute('data-beacon_search', $moss)))->get_field(), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubEndField())->set_label('')];
    }
    /**
     * @return string
     */
    public static function get_tab_slug() : string
    {
        return 'moss';
    }
    /**
     * @return string
     */
    public function get_tab_name() : string
    {
        return \esc_html__('OSS', 'flexible-invoices');
    }
}
