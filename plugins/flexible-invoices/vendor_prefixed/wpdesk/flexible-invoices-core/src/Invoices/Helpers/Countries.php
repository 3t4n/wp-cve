<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;

/**
 * Get country label from iso slug.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Helpers
 */
class Countries
{
    /**
     * @param string $slug
     *
     * @return string
     */
    public static function get_country_label(string $slug) : string
    {
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active() && \strlen($slug) <= 3) {
            $countries = \WC()->countries->get_countries();
            if (isset($countries)) {
                return $countries[$slug] ?? $slug;
            }
        }
        return $slug;
    }
    /**
     * @param string $slug
     * @param string $country
     *
     * @return string
     */
    public static function get_country_state_label(string $slug, string $country) : string
    {
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active() && \strlen($slug) <= 3) {
            $states = \WC()->countries->get_states($country);
            if (isset($states)) {
                return $states[$slug] ?? $slug;
            }
        }
        return $slug;
    }
    /**
     * @param string $name
     * @param string $value
     * @param string $country
     *
     * @return string
     */
    public static function generate_states_select(string $name, string $value = '', string $country = '') : string
    {
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            $states = \WC()->countries->get_states($country);
            $output = '<select class="state-select2 medium hs-beacon-search" name="' . $name . '" id="customer_state" >';
            foreach ($states as $state_code => $state_name) {
                $output .= '<option ' . \selected($state_code, $value) . ' value="' . $state_code . '">' . $state_name . '</option>' . \PHP_EOL;
            }
            $output .= '</select>' . \PHP_EOL;
        } else {
            $output = '<input type="text" class="medium hs-beacon-search" name="' . $name . '" id="customer_state" />';
        }
        return $output;
    }
    /**
     * Get filtered states.
     *
     * @return array
     */
    public static function get_states() : array
    {
        $states = [];
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            $countries_states = \WC()->countries->get_states();
            foreach ($countries_states as $country_id => $country_states) {
                if (!empty($country_states)) {
                    $states[$country_id] = $country_states;
                }
            }
        }
        return $states;
    }
}
