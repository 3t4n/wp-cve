<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
/**
 * @package WPDesk\Library\FlexibleInvoicesCore\Strategy
 */
abstract class AbstractSettingsStrategy implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy
{
    /**
     * @var array
     */
    protected $taxes = [];
    /**
     * @var array
     */
    protected $currencies = [];
    /**
     * @var array
     */
    protected $payment_statuses = [];
    /**
     * @var array
     */
    protected $payment_methods = [];
    /**
     * @var Settings
     */
    protected $settings;
    /**
     * @param Settings $settings
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings)
    {
        $this->settings = $settings;
    }
    /**
     * Get currencies from option
     */
    public function get_currencies() : array
    {
        if ($this->settings->has('currency')) {
            $currencies_options = $this->settings->get('currency');
            $currencies = [];
            if (\is_array($currencies_options)) {
                foreach ($currencies_options as $currency) {
                    $currencies[$currency['currency']] = $currency['currency'];
                }
            }
            return $currencies;
        }
        return [];
    }
    /**
     * Get taxes from option
     */
    public function get_taxes() : array
    {
        $taxes = $this->settings->get('tax');
        $tax_rates = [];
        $index = 0;
        foreach ($taxes as $tax) {
            $tax_rates[] = ['index' => $index, 'rate' => $tax['rate'], 'name' => $tax['name']];
            $index++;
        }
        /**
         * Filters vat types.
         *
         * @param array $rates Array of rares.
         *
         * @return array
         *
         * @since 1.3.0
         */
        $rates = (array) \apply_filters('inspire_invoices_vat_types', $tax_rates);
        if (empty($rates) || !\is_array($rates)) {
            return [['index' => 0, 'rate' => 0, 'name' => '0%']];
        }
        return $rates;
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
     * @return array
     */
    public function get_payment_methods() : array
    {
        $payment_methods_option = \explode("\n", $this->settings->get('payment_methods', $this->get_default_payment_methods()));
        $payment_methods = [];
        foreach ($payment_methods_option as $payment_method) {
            $payment_methods[\sanitize_title($payment_method)] = $payment_method;
        }
        return ['standard' => $payment_methods];
    }
    /**
     * @return array
     */
    public function get_payment_statuses() : array
    {
        /**
         * Filters payment statuses.
         *
         * @param array $user_data Payment statuses.
         *
         * @return array
         *
         * @since    1.3.0
         */
        return (array) \apply_filters('inspire_invoices_payment_statuses', ['topay' => \esc_html__('Due', 'flexible-invoices'), 'paid' => \esc_html__('Paid', 'flexible-invoices')]);
    }
    /**
     * @param string $value
     *
     * @return array
     */
    public function get_tax_value(string $value) : array
    {
        foreach ($this->get_taxes() as $tax) {
            if ((string) $tax['rate'] === $value) {
                return (array) $tax;
            }
        }
        return ['rate' => 0, 'name' => 0, '' => ''];
    }
    /**
     * @return Settings
     */
    public function get_settings() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings
    {
        return $this->settings;
    }
}
