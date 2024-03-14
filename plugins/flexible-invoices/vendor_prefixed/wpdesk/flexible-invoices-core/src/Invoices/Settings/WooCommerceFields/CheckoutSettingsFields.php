<?php

/**
 * Woocommerce Settings.
 *
 * @package WPDesk\FlexibleInvoicesWooCommerce
 */
namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubEndField;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubStartField;
use WPDeskFIVendor\WPDesk\Forms\Field\Header;
use WPDeskFIVendor\WPDesk\Forms\Field\InputTextField;
/**
 * Checkout settings subpage.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields
 */
final class CheckoutSettingsFields implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\WooCommerceFields\SubTabInterface
{
    /**
     * @inheritDoc
     */
    public function get_fields() : array
    {
        $plugin_url = \get_locale() === 'pl_PL' ? 'https://www.wpdesk.pl/sklep/woocommerce-checkout-fields/' : 'https://www.wpdesk.net/products/flexible-checkout-fields-pro/';
        $plugin_url .= '?utm_source=flexible-invoices-settings&utm_medium=link&utm_campaign=flexible-checkout-fields';
        $checkout = 'Checkout form';
        return [(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubStartField())->set_label(\esc_html__('Checkout', 'flexible-invoices'))->set_name('checkout'), (new \WPDeskFIVendor\WPDesk\Forms\Field\Header())->set_label(\esc_html__('Checkout', 'flexible-invoices'))->set_description(\sprintf(\__('Warning. If you use a plugin for editing <a href="%s">checkout fields</a> it may override the following settings.', 'flexible-invoices'), $plugin_url)), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('woocommerce_add_invoice_ask_field')->set_label(\esc_html__('Ask the customer if he wants an invoice', 'flexible-invoices'))->set_sublabel(\esc_html__('Enable', 'flexible-invoices'))->set_description(\esc_html__('If enabled the customer can choose to get an invoice. If automatic sending is enabled invoices will be issued only for these orders.', 'flexible-invoices'))->set_attribute('data-beacon_search', $checkout)->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('woocommerce_add_nip_field')->set_label(\esc_html__('Add VAT Number field to checkout', 'flexible-invoices'))->set_sublabel(\esc_html__('Enable', 'flexible-invoices'))->set_attribute('data-beacon_search', $checkout)->add_class('hs-beacon-search'), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('woocommerce_nip_label')->set_label(\esc_html__('Label', 'flexible-invoices'))->set_default_value(\esc_html__('VAT Number', 'flexible-invoices'))->add_class('nip-additional-fields hs-beacon-search')->set_attribute('data-beacon_search', $checkout), (new \WPDeskFIVendor\WPDesk\Forms\Field\InputTextField())->set_name('woocommerce_nip_placeholder')->set_label(\esc_html__('Placeholder', 'flexible-invoices'))->set_placeholder(\esc_html__('VAT Number', 'flexible-invoices'))->add_class('nip-additional-fields hs-beacon-search')->set_attribute('data-beacon_search', $checkout), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('woocommerce_nip_required')->set_label(\esc_html__('VAT Number field required', 'flexible-invoices'))->set_sublabel(\esc_html__('Enable', 'flexible-invoices'))->add_class('nip-additional-fields hs-beacon-search')->set_attribute('data-beacon_search', $checkout), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\FICheckboxField())->set_name('woocommerce_validate_nip')->set_label(\esc_html__('Validate VAT Number', 'flexible-invoices'))->set_sublabel(\esc_html__('Enable', 'flexible-invoices'))->set_description(\esc_html__('VAT Number will have to be entered without hyphens, spaces and optionally can be prefixed with country code.', 'flexible-invoices'))->add_class('nip-additional-fields hs-beacon-search')->set_attribute('data-beacon_search', $checkout), (new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Fields\SubEndField())->set_label('')];
    }
    /**
     * @return string
     */
    public static function get_tab_slug() : string
    {
        return 'checkout';
    }
    /**
     * @return string
     */
    public function get_tab_name() : string
    {
        return \esc_html__('Checkout', 'flexible-invoices');
    }
}
