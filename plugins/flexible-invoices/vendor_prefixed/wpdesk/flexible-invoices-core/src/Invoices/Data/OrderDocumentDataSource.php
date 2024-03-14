<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data;

use Exception;
use WC_Order;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentRecipient;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\OrderItems;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentCustomer;
use function strip_tags;
/**
 * Get document data from order.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Data
 */
class OrderDocumentDataSource extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\AbstractDataSource
{
    const ORDER_PAYMENT_STATUSES = ['processing', 'completed'];
    /**
     * @var WC_Order
     */
    public $order;
    /**
     * @param int      $order_id
     * @param Settings $options_container
     * @param string   $document_type
     *
     * @throws Exception Throw exception if WooCommerce is not active.
     */
    public function __construct(int $order_id, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $options_container, string $document_type)
    {
        if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            throw new \Exception('Order source cannot be used without WooCommerce!');
        }
        parent::__construct($options_container, $document_type);
        $this->order = new \WC_Order($order_id);
        $wpml_user_lang = $this->order->get_meta('wpml_user_lang', \true);
        if (!empty($wpml_user_lang)) {
            $this->set_wpml_user_lang($wpml_user_lang);
        }
        \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::wpml_switch_language_hook($wpml_user_lang);
    }
    /**
     * @return int
     */
    public function get_date_of_sale() : int
    {
        $_date_sale = \time();
        if ($this->order->get_date_created()) {
            $_date_sale = $this->order->get_date_created()->getOffsetTimestamp();
        }
        if ($this->settings->get('woocommerce_date_of_sale', 'order_date') === 'order_completed') {
            $completed_date = $this->order->get_date_completed();
            if ($completed_date) {
                $_date_sale = $completed_date->getOffsetTimestamp();
            }
        }
        return $_date_sale;
    }
    /**
     * @return int
     */
    public function get_date_of_pay() : int
    {
        $pay_date = $this->get_date_of_issue() + 60 * 60 * 24 * \intval($this->settings->get($this->get_document_type() . '_default_due_time'), 0);
        return (int) $pay_date;
    }
    /**
     * @return int
     */
    public function get_date_of_paid() : int
    {
        $paid_date = $this->order->get_meta('_paid_date', \true);
        if ($paid_date) {
            return \strtotime($paid_date);
        }
        return \strtotime(\current_time('mysql'));
    }
    /**
     * @return int
     */
    public function get_date_of_issue() : int
    {
        return \strtotime(\current_time('mysql'));
    }
    /**
     * @return Customer
     */
    public function get_customer() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer
    {
        $billing_company = $this->order->get_billing_company();
        if (empty($billing_company)) {
            $type = 'individual';
            $name = \strip_tags($this->order->get_formatted_billing_full_name());
        } else {
            $type = 'company';
            $name = $billing_company;
        }
        $vat_number = $this->order->get_meta('_billing_vat_number', \true);
        $id = $this->order->get_customer_id();
        $street = $this->order->get_billing_address_1();
        $street2 = $this->order->get_billing_address_2();
        $postcode = $this->order->get_billing_postcode();
        $city = $this->order->get_billing_city();
        $nip = $vat_number;
        $country = $this->order->get_billing_country();
        $phone = $this->order->get_billing_phone();
        $email = $this->order->get_billing_email();
        $state = $this->order->get_billing_state();
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentCustomer($id, $name, $street, $postcode, $city, $nip, $country, $phone, $email, $type, $street2, $state);
    }
    /**
     * @return DocumentRecipient
     */
    public function get_recipient() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient
    {
        $show_recipient_type = $this->settings->get('woocommerce_shipping_address', 'none');
        $billing_company = $this->order->get_billing_company();
        if (empty($billing_company)) {
            $billing_name = \strip_tags($this->order->get_formatted_billing_full_name());
        } else {
            $billing_name = $billing_company;
        }
        $shipping_company = $this->order->get_shipping_company();
        if (empty($shipping_company)) {
            $shipping_name = \strip_tags($this->order->get_formatted_shipping_full_name());
        } else {
            $shipping_name = $shipping_company;
        }
        if ($show_recipient_type === 'ifempty' && $this->has_different_address()) {
            return $this->get_recipient_from_shipping($shipping_name);
        }
        if ($show_recipient_type === 'always' && !$this->has_different_address()) {
            return $this->get_recipient_from_billing($billing_name);
        }
        if ($show_recipient_type === 'always') {
            return $this->get_recipient_from_shipping($shipping_name);
        }
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentRecipient('', '', '', '', '', '', '', '', '', '');
    }
    /**
     * @return bool
     */
    private function has_different_address() : bool
    {
        $billing_address = $this->order->get_billing_address_1() . $this->order->get_billing_country() . $this->order->get_billing_city() . $this->order->get_billing_postcode();
        $shipping_address = $this->order->get_shipping_address_1() . $this->order->get_shipping_country() . $this->order->get_shipping_city() . $this->order->get_shipping_postcode();
        return $billing_address !== $shipping_address;
    }
    /**
     * @param string $name
     *
     * @return Recipient
     */
    private function get_recipient_from_billing(string $name) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient
    {
        $vat_number = $this->order->get_meta('_billing_vat_number', \true);
        $street = $this->order->get_billing_address_1();
        $street2 = $this->order->get_billing_address_2();
        $postcode = $this->order->get_billing_postcode();
        $city = $this->order->get_billing_city();
        $nip = $vat_number;
        $country = $this->order->get_billing_country();
        $state = $this->order->get_billing_state();
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentRecipient($name, $street, $postcode, $city, $nip, $country, '', '', $street2, $state);
    }
    /**
     * @param string $name
     *
     * @return Recipient
     */
    private function get_recipient_from_shipping(string $name) : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient
    {
        $vat_number = $this->order->get_meta('_shipping_vat_number', \true);
        $street = $this->order->get_shipping_address_1();
        $street2 = $this->order->get_shipping_address_2();
        $postcode = $this->order->get_shipping_postcode();
        $city = $this->order->get_shipping_city();
        $nip = $vat_number;
        $country = $this->order->get_shipping_country();
        $state = $this->order->get_shipping_state();
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentRecipient($name, $street, $postcode, $city, $nip, $country, '', '', $street2, $state);
    }
    /**
     * @return string
     */
    public function get_customer_filter_field() : string
    {
        return $this->get_customer()->get_name();
    }
    /**
     * @return string
     */
    public function get_currency() : string
    {
        return $this->order->get_currency();
    }
    /**
     * @return float
     */
    public function get_discount() : float
    {
        return $this->order->get_total_discount();
    }
    /**
     * @return int
     */
    public function get_order_id() : int
    {
        return $this->order->get_id();
    }
    /**
     * @return array
     */
    public function get_items() : array
    {
        $order_items = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\OrderItems($this->order);
        return $order_items->get_items();
    }
    /**
     * @return string
     */
    public function get_payment_method() : string
    {
        return $this->order->get_payment_method();
    }
    /**
     * @return string
     */
    public function get_payment_method_name() : string
    {
        return $this->order->get_payment_method_title();
    }
    /**
     * @return float
     */
    public function get_total_gross() : float
    {
        return $this->order->get_total();
    }
    /**
     * @return float
     */
    public function get_total_net() : float
    {
        return $this->order->get_total() - $this->order->get_total_tax();
    }
    /**
     * @return float
     */
    public function get_total_paid() : float
    {
        if ($this->get_payment_status() === self::ORDER_PAYMENT_PAID_STATUS) {
            return $this->order->get_total();
        }
        return $this->total_paid;
    }
    /**
     * @return float
     */
    public function get_total_tax() : float
    {
        return $this->order->get_total_tax();
    }
    /**
     * @return string
     */
    public function get_payment_status() : string
    {
        $payment_method = $this->order->get_payment_method();
        if ($payment_method !== 'cod' && \in_array($this->order->get_status(), self::ORDER_PAYMENT_STATUSES, \true) && $this->settings->get('woocommerce_auto_paid_status') === 'yes') {
            return self::ORDER_PAYMENT_PAID_STATUS;
        }
        if ($payment_method === 'cod' && $this->order->get_status() === 'completed' && $this->settings->get('invoice_auto_paid_status') === 'yes') {
            return self::ORDER_PAYMENT_PAID_STATUS;
        }
        return self::ORDER_PAYMENT_TO_PAY_STATUS;
    }
    /**
     * @return int
     */
    public function get_show_order_number() : int
    {
        if ($this->settings->get('woocommerce_add_order_id', 'no') === 'yes') {
            return 1;
        }
        return 0;
    }
    /**
     * @return string
     */
    public function get_user_lang() : string
    {
        return \strtolower($this->user_lang);
    }
}
