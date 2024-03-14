<?php

namespace WPDeskFIVendor\WPDesk\Library\WPDeskOrder;

use WC_Tax;
/**
 * Get tax rate form item order.
 *
 * @package WPDesk\Library\WPDeskOrder
 */
class GetRateFromTaxTotal
{
    /**
     * @var float
     */
    private $rate = 0.0;
    /**
     * @var string
     */
    private $rate_class = '';
    /**
     * @var int
     */
    private $rate_id = 0;
    /**
     * @param array $taxes Taxes.
     */
    public function __construct(array $taxes)
    {
        $this->set_rate($taxes);
    }
    /**
     * @param array $taxes
     */
    private function set_rate(array $taxes)
    {
        $rates = $this->get_rates_data_from_totals($taxes);
        $has_real_rate = \false;
        foreach ($rates as $rate) {
            if ($rate['rate_amount'] > 0) {
                $this->rate_class = $rate['rate_class'];
                $this->rate = $rate['rate_amount'];
                $this->rate_id = $rate['rate_id'];
                $has_real_rate = \true;
            }
        }
        if (!$has_real_rate) {
            foreach ($rates as $rate) {
                $this->rate_class = $rate['rate_class'];
                $this->rate = $rate['rate_amount'];
                $this->rate_id = $rate['rate_id'];
            }
        }
    }
    /**
     * @param array $taxes
     *
     * @return array
     */
    private function get_rates_data_from_totals(array $taxes)
    {
        $rates = [];
        if (isset($taxes['total'])) {
            $total = $this->remove_empty_rates($taxes['total']);
            foreach ($total as $tax_id => $tax_value) {
                $tax_rate = \WC_Tax::get_rate_percent($tax_id);
                $rate = (float) \str_replace('%', '', $tax_rate);
                $rate_class = \wc_get_tax_class_by_tax_id($tax_id);
                if (!$this->rate_class) {
                    $this->rate_class = '';
                }
                $rates[] = ['rate_id' => $tax_id, 'rate_class' => $rate_class, 'rate_amount' => $rate];
            }
        }
        return $rates;
    }
    /**
     * @return string
     */
    public function get_class() : string
    {
        return (string) $this->rate_class;
    }
    /**
     * @return float
     */
    public function get_rate() : float
    {
        return $this->rate;
    }
    /**
     * @return int
     */
    public function get_rate_id() : int
    {
        return $this->rate_id;
    }
    /**
     * @param array $total
     *
     * @return array
     */
    private function remove_empty_rates(array $total) : array
    {
        foreach ($total as $tax_rate_id => $tax_rate) {
            if ($tax_rate === '') {
                unset($total[$tax_rate_id]);
            }
        }
        return $total;
    }
}
