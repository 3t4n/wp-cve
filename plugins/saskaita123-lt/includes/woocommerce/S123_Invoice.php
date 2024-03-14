<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 *
 * Class Description: Create S123Invoice
 */

namespace S123\Includes\Woocommerce;

use S123\Includes\Base\S123_BaseController;

if (!defined('ABSPATH')) exit;

class S123_Invoice extends S123_BaseController
{
    /*
     * Order data object
     */
    protected $order;

    /**
     * Woocommerce settings from API
     *
     * @var array
     */
    protected $settings = array();

    /*
    * API
    */
    protected $api;


    /*
    * Woocommerce prices rounding option
    */
    protected $wooRoundingDecimals;

    /**
     * @param $order
     * @param $api
     */
    public function __construct($order, $api)
    {
        parent::__construct();
        $this->order = $order;
        $this->api = $api;
        $this->wooRoundingDecimals = absint(get_option('woocommerce_price_num_decimals', 2));
    }

    /*
    * Build array for API products
    */
    private function s123_invoiceProducts($item): array
    {
        $invoiceProduct = [];
        $invoiceProduct['title'] = $item->get_name();
        $invoiceProduct['price'] = $this->formatNumber($this->order->get_item_subtotal($item, false, false));
        $invoiceProduct['quantity'] = $item->get_quantity();
        $invoiceProduct['code'] = $item->get_product()->get_sku() ?? null;

        if (isset($this->settings['add_product_short_description']) && $this->settings['add_product_short_description']) {
            $invoiceProduct['description'] = str_replace(array("\r", "\n", "\t"), '', $item->get_product()->get_short_description());
            $invoiceProduct['description_show'] = true;
        }

        $fixedDiscount = $item->get_subtotal() - $item->get_total();
        $invoiceProduct['total_discount'] = '0';
        if ($item->get_subtotal() !== $item->get_total()) {
            $invoiceProduct['discount'] = wc_format_decimal($fixedDiscount, '');
            $invoiceProduct['total_discount'] = wc_format_decimal($fixedDiscount, '');
            $invoiceProduct['discount_type'] = 'fixed';
        }

        $invoiceProduct['total_vat'] = $item->get_total_tax();
        $invoiceProduct['total_vat_wo_discount'] = $item->get_total_tax();
        $invoiceProduct['total_wo_vat_and_discount'] = wc_format_decimal((float)$this->formatNumber($item->get_total()) + (float)$invoiceProduct['total_discount'], '');
        $invoiceProduct['total'] = wc_format_decimal($invoiceProduct['total_wo_vat_and_discount'] + $invoiceProduct['total_vat'] - $invoiceProduct['total_discount'], '');

        $invoiceProduct['unit_id'] = $this->settings['unit_id'];

        if ($tax_data = $item->get_taxes()) {
            foreach ($this->order->get_items('tax') as $item_tax) {
                if (((int)($item_tax->get_rate_percent()) === 0 && (int)$item->get_total_tax() === 0) || (isset($tax_data['total'][$item_tax->get_rate_id()]) && $tax_data['total'][$item_tax->get_rate_id()] !== '')) {
                    $tax_rate_id = $item_tax->get_rate_id();
                    global $wpdb;
                    $tableName = $wpdb->prefix . "woocommerce_tax_rates";
                    $tax = $wpdb->get_row("SELECT * FROM {$tableName} WHERE tax_rate_id={$tax_rate_id}");

                    $invoiceProduct['company_vat_id'] = $tax->s123_tax_id ?? null;
                    $invoiceProduct['tariff'] = $tax ? ($tax->tax_rate / 100) + 1 : null;
                }
            }
        }

        return $invoiceProduct;
    }

    /*
    * Add shipping data as product to API
    */
    private function s123_addShippingToPayment(): array
    {
        $invoiceShipping = [];
        $invoiceShipping['title'] = __('Shipping', 'woocommerce');
        $invoiceShipping['price'] = $this->formatNumber($this->order->get_shipping_total());
        $invoiceShipping['quantity'] = 1;
        $invoiceShipping['total'] = $this->formatNumber($this->order->get_shipping_total() + $this->order->get_shipping_tax());
        $invoiceShipping['unit_id'] = $this->settings['unit_id'];
        $invoiceShipping['total_vat'] = $this->order->get_shipping_tax();
        $invoiceShipping['total_vat_wo_discount'] = $this->order->get_shipping_tax();
        $invoiceShipping['total_wo_vat_and_discount'] = $this->order->get_shipping_total();

        $vat = $this->find_s123_tax_id($this->order->get_shipping_total(), $this->order->get_shipping_tax());
        $invoiceShipping['company_vat_id'] = $vat['id'] ?? null;
        $invoiceShipping['tariff'] = $vat['tariff'] ?? null;

        return $invoiceShipping;
    }

    private function s123_addOrderFee($fee): array
    {
        $orderFee = [];
        $orderFee['title'] = $fee->get_name();
        $orderFee['price'] = $fee->get_total();
        $orderFee['quantity'] = 1;
        $orderFee['total'] = $this->formatNumber((float)$fee->get_total() + (float)$fee->get_total_tax());
        $orderFee['unit_id'] = $this->settings['unit_id'];
        $orderFee['total_vat'] = $fee->get_total_tax();
        $orderFee['total_vat_wo_discount'] = $fee->get_total_tax();
        $orderFee['total_wo_vat_and_discount'] = $fee->get_total();

        $vat = $this->find_s123_tax_id($fee->get_total(), $fee->get_total_tax());
        $orderFee['company_vat_id'] = $vat['id'] ?? null;
        $orderFee['tariff'] = $vat['tariff'] ?? null;

        return $orderFee;
    }

    /*
    * Build array for API client data
    */
    private function s123_invoiceClient(): array
    {
        $client = [];
        $clientCode = get_post_meta($this->order->get_id(), '_billing_company_code', true);

        // check if order has set company name and company code
        if ($this->order->get_billing_company()) {
            $client["name"] = $this->order->get_billing_company();
        } else if (get_post_meta($this->order->get_id(), '_billing_company_name', true)) {
            $client["name"] = get_post_meta($this->order->get_id(), '_billing_company_name', true);
        } else {
            $client["name"] = $this->order->get_billing_first_name() . ' ' . $this->order->get_billing_last_name();
        }

        // check if order has set company code
        if ($clientCode) {
            $client["code"] = $clientCode;
            $client["code_type"] = 'company';
        } else {
            $client["code_type"] = 'personal';
        }

        $clientVat = get_post_meta($this->order->get_id(), '_billing_company_vat_code', true);
        if ($clientVat) {
            $client["vat_code"] = $clientVat;
        }

        $client["address"] = $this->order->get_billing_address_1() ?: $this->order->get_billing_address_2();

        if ($this->order->get_billing_city()) {
            $client["address"] = $this->order->get_billing_city() . ', ' . $client["address"];
        }

        $client["email"] = $this->order->get_billing_email();
        $client["phone"] = $this->order->get_billing_phone();

        if ($this->order->get_billing_country()) {
            $client['country_code'] = $this->order->get_billing_country();
        }

        return $client;
    }

    /*
    * Build array for payments
    */
    private function s123_invoicePayments(): array
    {
        $invoicePayments = [];

        if ($this->order->get_date_paid()) {
            $invoicePayments["total"] = $this->formatNumber($this->order->get_total());
            $invoicePayments["type"] = 'transfer';
            $invoicePayments["date"] = $this->order->get_date_paid()->date('Y-m-d');

            return [$invoicePayments];
        }

       return $invoicePayments;
    }

    /*
    * Build API invoice data
    */
    public function s123_buildInvoice(): ?array
    {
        // get woocommerce settings from app.invoice123.com
        $response = $this->api->s123_makeGetRequest($this->api->getApiUrl('woocommerce_settings'));

        if ($response['code'] === 200) {
            $this->setSettings($response['body']['data']);
            return $this->checkIfToGenerateInvoice();
        } else if ($response['code'] === 404) {
            $this->order->add_order_note(__('API key do not have any mapped settings at app.invoice123.com WooCommerce module.', 's123-invoices'));
            return null;
        } else {
            if (isset($response['body']['data'])) {
                $this->order->add_order_note($response['body']['data'] . ' - app.invoice123.com');
            } else {
                $this->order->add_order_note(__('Something went wrong try again or contact app.invoice123.com support.', 's123-invoices'));
            }
            return null;
        }
    }

    public function s123_generateInvoice(): array
    {
        $products = [];
        foreach ($this->order->get_items() as $item) {
            $products[] = $this->s123_invoiceProducts($item);
        }

        if ($this->order->get_shipping_total() !== '0') {
            $products[] = $this->s123_addShippingToPayment();
        }

        foreach ($this->order->get_items('fee') as $item_fee) {
            $products[] = $this->s123_addOrderFee($item_fee);
        }

        $client = $this->s123_invoiceClient();

        $payments = $this->s123_invoicePayments();

        return [
            'type' => 'simple',
            'series_id' => $this->settings['series_id'],
            'activity_id' => $this->settings['activity_id'] ?: null,
            'date' => $this->getInvoiceDate(),
            'date_due_show' => $this->settings['date_due_show'],
            'total' => $this->formatNumber($this->order->get_total()),
            'issued_by' => $this->settings['issued_by'],
            'issued_to' => $client["name"],
            'note_enabled' => true,
            'note' => sprintf(__('Order No. %s', 's123-invoices'), $this->order->get_order_number()),
            'products' => $products,
            'client' => $client,
            'banks' => $this->settings['bank_id'] ? [$this->settings['bank_id']] : [],
            'payments' => $payments,
            'send_email' => $this->checkIfToSendEmail($client['code_type']),
            'template_id' => $this->settings['template_id'],
            'use_warehouse_supplies' => $this->settings['sync_warehouse'] ?? false,
            // for validating versions
            'versions' => $this->versions(),
        ];
    }

    public function setSettings($settings)
    {
        $this->settings = $settings;
    }

    private function formatNumber($value): string
    {
        return number_format($value, $this->wooRoundingDecimals, '.', '');
    }

    private function find_s123_tax_id($item_total, $item_total_tax): ?array
    {
        $taxes = $this->order->get_taxes();

        if (count($taxes) === 0) {
            return null;
        }

        if (count($taxes) === 1) {
            $tax_rate_id = reset($taxes)->get_rate_id(); // reset() returns the first element of an array, need to use this because don't know the key
            global $wpdb;
            $tableName = $wpdb->prefix . "woocommerce_tax_rates";
            $row = $wpdb->get_row("SELECT * FROM {$tableName} WHERE tax_rate_id={$tax_rate_id}");

            if (!$row) {
                return null;
            }

            return [
                'id' => $row->s123_tax_id,
                'tariff' => ($row->tax_rate / 100) + 1,
            ];
        }

        foreach ($taxes as $item_tax) {
            $tax_rate_id = $item_tax->get_rate_id();

            global $wpdb;
            $tableName = $wpdb->prefix . "woocommerce_tax_rates";
            $tax_rate = $wpdb->get_var("SELECT tax_rate FROM {$tableName} WHERE tax_rate_id={$tax_rate_id}");

            $tariff = 0;

            if ($tax_rate != 0) {
                $tariff = $tax_rate / 100;
            }

            $vat = round((float)$item_total * $tariff, 2);

            // check if difference is less than 0.01 and assign shipping tax id
            if (abs($vat - round((float)$item_total_tax, 2)) < 0.01) {
                $row = $wpdb->get_row("SELECT * FROM {$tableName} WHERE tax_rate_id={$tax_rate_id}");

                if ($row) {
                    return [
                        'id' => $row->s123_tax_id,
                        'tariff' => ($row->tax_rate / 100) + 1,
                    ];
                }
            }
        }

        return null;
    }

    private function getInvoiceDate(): string
    {
        if (isset($this->settings['invoice_date_by_completed']) && $this->settings['invoice_date_by_completed'] === true) {
            return date('Y-m-d');
        }

        return $this->order->get_date_created()->date('Y-m-d');
    }

    private function checkIfToGenerateInvoice(): ?array
    {
        if (
            $this->formatNumber($this->order->get_total()) === $this->formatNumber(0) &&
            isset($this->settings['skip_zero_sum_invoices']) &&
            $this->settings['skip_zero_sum_invoices'] === true
        ) {
            return null;
        }

        return $this->s123_generateInvoice();
    }

    private function checkIfToSendEmail($clientCode): bool
    {
        if ($this->settings['send_email'] === false) {
            return false;
        }

        if ($clientCode === 'personal' && isset($this->settings['send_to_personal_client']) && $this->settings['send_to_personal_client']) {
            return true;
        }

        if ($clientCode === 'company') {
            return true;
        }

        return false;
    }
}