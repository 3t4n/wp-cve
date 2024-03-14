<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs;
/**
 * Define currency settings for PDF printing.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Helpers
 */
class Currency
{
    /**
     * @var array
     */
    private $currencies = [];
    /**
     * @var string
     */
    private $currency_slug;
    /**
     * @param string $currency_slug
     */
    public function __construct(string $currency_slug)
    {
        $this->currency_slug = $currency_slug;
        $this->set_currencies_locale_from_woocommerce();
        $this->set_currencies_locale_from_wordpress();
    }
    /**
     * Prepare currencies settings from WooCommerce.
     */
    private function set_currencies_locale_from_woocommerce()
    {
        if (\function_exists('WC')) {
            $locales = (include \WC()->plugin_path() . '/i18n/locale-info.php');
            foreach ($locales as $locale) {
                $currency_slug = $locale['currency_code'];
                $decimal_separator = $locale['decimal_sep'];
                $thousand_separator = $locale['thousand_sep'];
                $currency_position = $locale['currency_pos'];
                $this->currencies[$currency_slug] = ['decimal_separator' => $decimal_separator, 'thousand_separator' => $thousand_separator, 'currency_position' => $currency_position, 'currency_symbol' => \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs\Currency::get_currency_symbol($currency_slug), 'currency' => $currency_slug];
            }
        }
    }
    /**
     * Prepare currencies settings from WordPress.
     */
    private function set_currencies_locale_from_wordpress()
    {
        $option = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings();
        $currency_options = $option->get('currency');
        foreach ($currency_options as $currency) {
            $this->currencies[$currency['currency']]['decimal_separator'] = $currency['decimal_separator'];
            $this->currencies[$currency['currency']]['thousand_separator'] = $currency['thousand_separator'];
            $this->currencies[$currency['currency']]['currency_position'] = $currency['currency_position'];
            $this->currencies[$currency['currency']]['currency_symbol'] = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs\Currency::get_currency_symbol($currency['currency']);
            $this->currencies[$currency['currency']]['currency'] = $currency['currency'];
        }
    }
    /**
     * @return array
     */
    private function get_currency_settings() : array
    {
        $defaults = ['decimal_separator' => '.', 'thousand_separator' => '', 'currency_position' => 'right_space', 'currency_symbol' => \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs\Currency::get_currency_symbol($this->currency_slug), 'currency' => $this->currency_slug];
        if (isset($this->currencies[$this->currency_slug])) {
            return \wp_parse_args($this->currencies[$this->currency_slug], $defaults);
        }
        return $defaults;
    }
    /**
     * @param float|string $amount
     *
     * @return string
     */
    public function string_as_money($amount = 0.0) : string
    {
        $sign = '';
        $amount = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::price_to_float($amount);
        if ($amount < 0) {
            $sign = '-';
        }
        $currency_option = $this->get_currency_settings();
        $ret = \number_format(\abs($amount), 2, $currency_option['decimal_separator'], $currency_option['thousand_separator']);
        $symbol = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Configs\Currency::get_currency_symbol($currency_option['currency']);
        $currency_symbol = \apply_filters('woocommerce_currency_symbol', $symbol, $currency_option['currency']);
        switch ($currency_option['currency_position']) {
            case 'left':
                $ret = $currency_symbol . $ret;
                break;
            case 'right':
                $ret .= $currency_symbol;
                break;
            case 'left_space':
                $ret = $currency_symbol . ' ' . $ret;
                break;
            case 'right_space':
                $ret .= ' ' . $currency_symbol;
                break;
        }
        return $sign . $ret;
    }
    /**
     * @param array $item
     *
     * @return string
     */
    public function discount_price(array $item) : string
    {
        if (isset($item['discount']) && (float) $item['discount'] > 0.0) {
            return '-' . $this->string_as_money($item['discount']);
        }
        return $this->string_as_money($item['discount']);
    }
    /**
     * @param string|float $value
     *
     * @return float
     */
    public function number_format($value) : float
    {
        $value = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::price_to_float($value);
        return (float) \number_format($value, 2, '.', '');
    }
}
