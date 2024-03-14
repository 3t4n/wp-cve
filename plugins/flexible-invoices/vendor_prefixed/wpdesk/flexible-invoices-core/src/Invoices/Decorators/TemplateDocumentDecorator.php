<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators;

/**
 * Decorates document for templates.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Decorators
 */
class TemplateDocumentDecorator extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\DocumentDecorator
{
    /**
     * Returns array of totals [ total_net_sum, total_tax_sum, total_gross_sum ].
     *
     * @return array
     */
    public function get_totals() : array
    {
        $net_amount = 0;
        $tax_amount = 0;
        $gross_amount = 0;
        foreach ($this->get_items() as $item) {
            $net_amount += (float) $item['net_price_sum'];
            $tax_amount += (float) $item['vat_sum'];
            $gross_amount += (float) $item['total_price'];
        }
        return ['total_net_sum' => $net_amount, 'total_tax_sum' => $tax_amount, 'total_gross_sum' => $gross_amount];
    }
    /**
     * @return array
     */
    public function get_items_as_money() : array
    {
        $items = $this->document->get_items();
        foreach ($items as &$item) {
            $item['net_price'] = $this->currency_helper->string_as_money($item['net_price']);
            $item['net_price_sum'] = $this->currency_helper->string_as_money($item['net_price_sum']);
            $item['vat_sum'] = $this->currency_helper->string_as_money($item['vat_sum']);
            $item['total_price'] = $this->currency_helper->string_as_money($item['total_price']);
        }
        return $items;
    }
    /**
     * Returns array of tax totals [ [] => total_net_sum, total_vat_sum, total_gross_sum ].
     *
     * @return array
     */
    public function get_totals_by_taxes() : array
    {
        $tax_types = [];
        foreach ($this->get_items() as $item) {
            if (!isset($tax_types[$item['vat_type_name']]['total_gross_sum'])) {
                $tax_types[$item['vat_type_name']]['total_net_sum'] = 0;
                $tax_types[$item['vat_type_name']]['total_vat_sum'] = 0;
                $tax_types[$item['vat_type_name']]['total_gross_sum'] = 0;
            }
            $tax_types[$item['vat_type_name']]['total_net_sum'] += (float) $item['net_price_sum'];
            $tax_types[$item['vat_type_name']]['total_vat_sum'] += (float) $item['vat_sum'];
            $tax_types[$item['vat_type_name']]['total_gross_sum'] += (float) $item['total_price'];
        }
        return $tax_types;
    }
    /**
     * @param array $data
     *
     * @return array
     */
    public function array_to_string_as_money(array $data) : array
    {
        $new_data = [];
        foreach ($data as $key => $value) {
            if (!\is_array($value)) {
                $new_data[$key] = $this->currency_helper->string_as_money($value);
            } else {
                $new_data[$key] = $this->array_to_string_as_money($value);
            }
        }
        return $new_data;
    }
}
