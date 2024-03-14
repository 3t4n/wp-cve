<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
/**
 * This interface defines methods for different data sources.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Data
 */
interface SourceData
{
    /**
     * @return string
     */
    public function get_document_type() : string;
    /**
     * @return array
     */
    public function get_items() : array;
    /**
     * @return Seller
     */
    public function get_seller() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
    /**
     * @return Customer
     */
    public function get_customer() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
    /**
     * @return Recipient
     */
    public function get_recipient() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient;
    /**
     * @return string
     */
    public function get_formatted_number() : string;
    /**
     * @return string
     */
    public function get_currency() : string;
    /**
     * @return string
     */
    public function get_currency_symbol() : string;
    /**
     * @return string
     */
    public function get_payment_method() : string;
    /**
     * @return string
     */
    public function get_payment_method_name() : string;
    /**
     * @return string
     */
    public function get_notes() : string;
    /**
     * @return string
     */
    public function get_user_lang() : string;
    /**
     * @return int
     */
    public function get_id() : int;
    /**
     * @return int
     */
    public function get_order_id() : int;
    /**
     * @return float
     */
    public function get_total_paid() : float;
    /**
     * @return string
     */
    public function get_payment_status() : string;
    /**
     * @return int
     */
    public function get_number() : int;
    /**
     * @return int
     */
    public function get_date_of_sale() : int;
    /**
     * @return int
     */
    public function get_date_of_issue() : int;
    /**
     * @return int
     */
    public function get_date_of_pay() : int;
    /**
     * @return int
     */
    public function get_date_of_paid() : int;
    /**
     * @return float
     */
    public function get_total_tax() : float;
    /**
     * @return float
     */
    public function get_total_net() : float;
    /**
     * @return float
     */
    public function get_total_gross() : float;
    /**
     * @return float
     */
    public function get_tax() : float;
    /**
     * @return float
     */
    public function get_discount() : float;
    /**
     * @return string
     */
    public function get_customer_filter_field() : string;
    /**
     * @return int
     */
    public function get_show_order_number() : int;
    /**
     * @return int
     */
    public function get_is_correction() : int;
    /**
     * @return int
     */
    public function get_corrected_id() : int;
}
