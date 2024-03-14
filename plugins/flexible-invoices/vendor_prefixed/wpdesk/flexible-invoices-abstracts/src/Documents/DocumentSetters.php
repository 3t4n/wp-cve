<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
/**
 * Define document setters.
 */
interface DocumentSetters
{
    /**
     *
     * @param int $number
     */
    public function set_number($number);
    /**
     * @param string $value
     */
    public function set_formatted_number($value);
    /**
     * @param string $value
     */
    public function set_currency($value);
    /**
     * @param string $value
     */
    public function set_payment_method($value);
    /**
     * @param string $value
     */
    public function set_payment_method_name($value);
    /**
     * @param $value
     */
    public function set_notes($value);
    /**
     * @param $value
     */
    public function set_user_lang($value);
    /**
     * @param int $id
     */
    public function set_id($id);
    /**
     * @param float $value
     */
    public function set_total_paid($value);
    /**
     * @param string $value
     */
    public function set_payment_status($value);
    /**
     * @param array $items
     */
    public function set_items($items);
    /**
     * @param int $value
     */
    public function set_date_of_sale($value);
    /**
     * @param int $value
     */
    public function set_date_of_issue($value);
    /**
     * @param int $value
     */
    public function set_date_of_pay($value);
    /**
     * @param int $value
     */
    public function set_date_of_paid($value);
    /**
     * @param float $value
     */
    public function set_total_tax($value);
    /**
     * @param float $value
     */
    public function set_total_net($value);
    /**
     * @param float $value
     */
    public function set_total_gross($value);
    /**
     * @param string $value
     */
    public function set_tax($value);
    /**
     * @param Seller $seller
     */
    public function set_seller(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller $seller);
    /**
     * @param Customer $customer
     */
    public function set_customer(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer $customer);
    /**
     * @param Recipient $recipient
     */
    public function set_recipient(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient $recipient);
    /**
     * @param string $customer_name
     */
    public function set_customer_filter_field($customer_name);
    /**
     * @param float $value
     */
    public function set_discount($value);
    /**
     * @param int $value
     */
    public function set_show_order_number($value);
    /**
     * @param int $id
     */
    public function set_order_id($id);
    /**
     * @param int $id
     */
    public function set_corrected_id($id);
    /**
     * @param bool $is_correction
     */
    public function set_is_correction($is_correction);
    /**
     * @param string $value
     */
    public function set_currency_symbol($value);
}
