<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
/**
 * Base decorator for document.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Decorators
 */
class BaseDecorator implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document
{
    /**
     * @var Document
     */
    protected $document;
    /**
     * @var SettingsStrategy
     */
    protected $strategy;
    /**
     * @var Helpers\Currency
     */
    protected $currency_helper;
    /**
     * @param Document         $document
     * @param SettingsStrategy $strategy
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy)
    {
        $this->document = $document;
        $this->strategy = $strategy;
        $this->currency_helper = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Currency($document->get_currency());
    }
    /**
     * @param string $value
     */
    public function set_date_of_paid($value)
    {
        $this->document->set_date_of_paid($value);
    }
    /**
     * @return string
     */
    public function get_date_of_paid() : string
    {
        return $this->document->get_date_of_paid();
    }
    /**
     * @param string $value
     */
    public function set_date_of_issue($value)
    {
        $this->document->set_date_of_issue($value);
    }
    /**
     * @return string
     */
    public function get_date_of_issue() : string
    {
        return $this->document->get_date_of_issue();
    }
    /**
     * @param string $value
     */
    public function set_date_of_sale($value)
    {
        $this->document->set_date_of_sale($value);
    }
    /**
     * @return string
     */
    public function get_date_of_sale() : string
    {
        return $this->document->get_date_of_sale();
    }
    /**
     * @param string $value
     */
    public function set_date_of_pay($value)
    {
        $this->document->set_date_of_pay($value);
    }
    /**
     * @return string
     */
    public function get_date_of_pay() : string
    {
        return $this->document->get_date_of_pay();
    }
    /**
     * @return string
     */
    public function get_type() : string
    {
        return $this->document->get_type();
    }
    /**
     * @param string $value
     */
    public function set_formatted_number($value)
    {
        $this->document->set_formatted_number($value);
    }
    /**
     * @return string
     */
    public function get_formatted_number() : string
    {
        return $this->document->get_formatted_number();
    }
    /**
     * @param string $value
     */
    public function set_total_gross($value)
    {
        $this->document->set_total_gross($value);
    }
    /**
     * @param string $value
     */
    public function set_currency($value)
    {
        $this->document->set_currency($value);
    }
    /**
     * @return string
     */
    public function get_currency() : string
    {
        return $this->document->get_currency();
    }
    /**
     * @return string
     */
    public function get_currency_symbol() : string
    {
        return $this->document->get_currency_symbol();
    }
    /**
     * @param string $value
     */
    public function set_currency_symbol($value)
    {
        $this->document->set_currency_symbol($value);
    }
    /**
     * @param string $value
     */
    public function set_payment_method($value)
    {
        $this->document->set_payment_method($value);
    }
    /**
     * @return string
     */
    public function get_payment_method() : string
    {
        return $this->document->get_payment_method();
    }
    /**
     * @param string $value
     */
    public function set_payment_method_name($value)
    {
        $this->document->set_payment_method_name($value);
    }
    /**
     * @param $value
     */
    public function set_notes($value)
    {
        $this->document->set_notes(\esc_html($this->strategy->get_settings()->get('invoices_notice')));
    }
    /**
     * @return string
     */
    public function get_notes() : string
    {
        return $this->document->get_notes();
    }
    /**
     * @param $value
     */
    public function set_user_lang($value)
    {
        $this->document->set_user_lang($value);
    }
    public function get_user_lang() : string
    {
        return $this->document->get_user_lang();
    }
    /**
     * @param int $id
     */
    public function set_id($id)
    {
        $this->document->set_id($id);
    }
    /**
     * @return int
     */
    public function get_id() : int
    {
        return $this->document->get_id();
    }
    /**
     * @param float $value
     */
    public function set_total_paid($value)
    {
        $this->document->set_total_paid($value);
    }
    /**
     * @return float
     */
    public function get_total_paid() : float
    {
        return $this->currency_helper->number_format($this->document->get_total_paid());
    }
    /**
     * @param string $value
     */
    public function set_payment_status($value)
    {
        $this->document->set_payment_status($value);
    }
    /**
     * @return string
     */
    public function get_payment_status() : string
    {
        return $this->document->get_payment_status();
    }
    /**
     * @return string
     */
    public function get_payment_status_name() : string
    {
        return $this->document->get_payment_status();
    }
    /**
     * @param array $items
     */
    public function set_items($items)
    {
        $this->document->set_items($items);
    }
    /**
     * @return array
     */
    public function get_items() : array
    {
        return $this->document->get_items();
    }
    /**
     * @param int $number
     */
    public function set_number($number)
    {
        $this->document->set_number($number);
    }
    /**
     * @return int
     */
    public function get_number() : int
    {
        return $this->document->get_number();
    }
    /**
     * @param float $value
     */
    public function set_total_tax($value)
    {
        $this->document->set_total_tax($value);
    }
    /**
     * @return float
     */
    public function get_total_tax() : float
    {
        return $this->document->get_total_tax();
    }
    /**
     * @return float
     */
    public function get_total_net() : float
    {
        return $this->document->get_total_net();
    }
    /**
     * @param float $value
     */
    public function set_total_net($value)
    {
        $this->document->set_total_net($value);
    }
    /**
     * @return float
     */
    public function get_total_gross() : float
    {
        return $this->document->get_total_gross();
    }
    /**
     * @param string $value
     */
    public function set_tax($value)
    {
        $this->document->set_tax($value);
    }
    /**
     * @return float
     */
    public function get_tax() : float
    {
        return $this->document->get_tax();
    }
    /**
     * @param Seller $seller
     */
    public function set_seller(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller $seller)
    {
        $this->document->set_seller($seller);
    }
    /**
     * @return Seller
     */
    public function get_seller() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller
    {
        return $this->document->get_seller();
    }
    /**
     * @param Customer $customer
     */
    public function set_customer(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer $customer)
    {
        $this->document->set_customer($customer);
    }
    public function get_customer() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer
    {
        return $this->document->get_customer();
    }
    /**
     * @param Recipient $recipient
     */
    public function set_recipient(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient $recipient)
    {
        $this->document->set_recipient($recipient);
    }
    /**
     * @return Recipient
     */
    public function get_recipient() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient
    {
        return $this->document->get_recipient();
    }
    /**
     * @param string $customer_name
     */
    public function set_customer_filter_field($customer_name)
    {
        $this->document->set_customer_filter_field($customer_name);
    }
    /**
     * @return string
     */
    public function get_customer_filter_field() : string
    {
        return $this->document->get_customer_filter_field();
    }
    /**
     * @param float $value
     */
    public function set_discount($value)
    {
        $this->document->set_discount($value);
    }
    /**
     * @return float
     */
    public function get_discount() : float
    {
        return $this->document->get_discount();
    }
    /**
     * @param int $id
     */
    public function set_order_id($id)
    {
        $this->document->set_order_id($id);
    }
    /**
     * @return int
     */
    public function get_order_id() : int
    {
        return $this->document->get_order_id();
    }
    /**
     * @return string
     */
    public function get_order_number() : string
    {
        $order_id = $this->document->get_order_id();
        $order_number = $order_id;
        if ($order_id && \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            $order = \wc_get_order($order_id);
            if ($order) {
                $order_number = $order->get_order_number();
            }
        }
        return (string) $order_number;
    }
    /**
     * @param int $id
     */
    public function set_corrected_id($id)
    {
        $this->document->set_corrected_id($id);
    }
    /**
     * @return int
     */
    public function get_corrected_id() : int
    {
        return $this->document->get_corrected_id();
    }
    /**
     * @param string $value
     */
    public function set_show_order_number($value)
    {
        $this->document->set_show_order_number($value);
    }
    /**
     * @return bool
     */
    public function get_show_order_number() : bool
    {
        return $this->document->get_show_order_number();
    }
    /**
     * @param bool $is_correction
     */
    public function set_is_correction($is_correction)
    {
        $this->document->set_is_correction($is_correction);
    }
    /**
     * @return int
     */
    public function get_is_correction() : int
    {
        return $this->document->get_is_correction();
    }
    /**
     * @return string
     */
    public function get_payment_method_name() : string
    {
        $payment_method_name = $this->document->get_payment_method_name();
        if (!empty($payment_method_name)) {
            return $payment_method_name;
        }
        $payment_method_slug = $this->document->get_payment_method();
        $payment_methods = $this->strategy->get_payment_methods();
        foreach ($payment_methods as $methods_source) {
            foreach ($methods_source as $payment_method_key => $payment_method_title) {
                $methods[$payment_method_key] = $payment_method_title;
            }
        }
        return $methods[$payment_method_slug] ?? $payment_method_slug;
    }
}
