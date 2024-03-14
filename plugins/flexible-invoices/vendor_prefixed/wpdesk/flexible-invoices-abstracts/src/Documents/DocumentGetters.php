<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
/**
 * Define document getters.
 */
interface DocumentGetters
{
    /**
     * @return int
     */
    public function get_number();
    /**
     * @return string
     */
    public function get_type();
    /**
     * @return string
     */
    public function get_formatted_number();
    /**
     * @return string
     */
    public function get_currency();
    /**
     * @return string
     */
    public function get_payment_method();
    /**
     * @return string
     */
    public function get_payment_method_name();
    /**
     * @return string
     */
    public function get_notes();
    /**
     * @return string
     */
    public function get_user_lang();
    /**
     * @return int
     */
    public function get_id();
    /**
     * @return float
     */
    public function get_total_paid();
    /**
     * @return string
     */
    public function get_payment_status();
    /**
     *
     * @return array
     */
    public function get_items();
    /**
     * @return int
     */
    public function get_date_of_sale();
    /**
     * @return int
     */
    public function get_date_of_issue();
    /**
     * @return int
     */
    public function get_date_of_pay();
    /**
     * @return int
     */
    public function get_date_of_paid();
    /**
     * @return float
     */
    public function get_total_tax();
    /**
     * @return float
     */
    public function get_total_net();
    /**
     * @return float
     */
    public function get_total_gross();
    /**
     * @return float
     */
    public function get_tax();
    /**
     * @return Seller
     */
    public function get_seller();
    /**
     * @return Customer
     */
    public function get_customer();
    /**
     * @return Recipient
     */
    public function get_recipient();
    /**
     * @return string
     */
    public function get_customer_filter_field();
    /**
     * @return float
     */
    public function get_discount();
    /**
     * @return int
     */
    public function get_show_order_number();
    /**
     * @return int
     */
    public function get_order_id();
    /**
     * @return int
     */
    public function get_corrected_id();
    /**
     * @return int
     */
    public function get_is_correction();
    /**
     * @return string
     */
    public function get_currency_symbol();
}
