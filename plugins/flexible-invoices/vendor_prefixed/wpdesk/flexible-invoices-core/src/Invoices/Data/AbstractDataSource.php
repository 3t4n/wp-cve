<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentRecipient;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentCustomer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentSeller;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
/**
 * Abstraction for data source.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Data
 */
abstract class AbstractDataSource implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\SourceData
{
    const ORDER_PAYMENT_PAID_STATUS = 'paid';
    const ORDER_PAYMENT_TO_PAY_STATUS = 'topay';
    const DOCUMENT_TYPE = 'invoice';
    /**
     * @var Settings
     */
    protected $settings;
    /**
     * @var string
     */
    protected $document_type = '';
    /**
     * @var string
     */
    protected $formatted_number = '';
    /**
     * @var float
     */
    protected $total_price = 0;
    /**
     * @var string
     */
    protected $currency = '';
    /**
     * @var string
     */
    protected $payment_method_name = '';
    /**
     * @var string
     */
    protected $payment_method = '';
    /**
     * @var string
     */
    protected $notes = '';
    /**
     * @var string
     */
    protected $user_lang = 'en';
    /**
     * @var int
     */
    protected $id = 0;
    /**
     * @var int
     */
    protected $order_id = 0;
    /**
     * @var int
     */
    protected $corrected_id = 0;
    /**
     * @var float
     */
    protected $total_paid = 0.0;
    /**
     * @var string
     */
    protected $payment_status = '';
    /**
     * @var array
     */
    protected $items = [];
    /**
     * @var int
     */
    protected $number = 0;
    /**
     * @var int
     */
    protected $date_of_sale = 0;
    /**
     * @var int
     */
    protected $date_of_issue = 0;
    /**
     * @var int
     */
    protected $date_of_pay = 0;
    /**
     * @var int
     */
    protected $paid_date = 0;
    /**
     * @var float
     */
    protected $total_tax = 0.0;
    /**
     * @var float
     */
    protected $total_net = 0.0;
    /**
     * @var float
     */
    protected $total_gross = 0.0;
    /**
     * @var float
     */
    protected $tax = 0.0;
    /**
     * @var Seller
     */
    protected $seller;
    /**
     * @var Customer
     */
    protected $customer;
    /**
     * @var Recipient
     */
    protected $recipient;
    /**
     * @var string
     */
    protected $customer_filtered_name = '';
    /**
     * @var float
     */
    protected $discount = 0.0;
    /**
     * @var int
     */
    protected $post_id = 0;
    /**
     * @param Settings $settings
     * @param string   $document_type
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings, string $document_type)
    {
        $this->settings = $settings;
        $this->document_type = $document_type;
        $this->set_wpml_user_lang(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::get_active_lang());
    }
    /**
     * @param string $value
     */
    public function set_wpml_user_lang(string $value)
    {
        $this->user_lang = $value;
    }
    /**
     * @return string
     */
    public function get_document_type() : string
    {
        return $this->document_type;
    }
    /**
     * @return int
     */
    public function get_number() : int
    {
        return $this->number;
    }
    /**
     * @return string
     */
    public function get_formatted_number() : string
    {
        return $this->formatted_number;
    }
    /**
     * @return string
     */
    public function get_currency() : string
    {
        return $this->currency;
    }
    /**
     * @return string
     */
    public function get_currency_symbol() : string
    {
        return $this->currency;
    }
    /**
     * @return string
     */
    public function get_payment_method() : string
    {
        return $this->payment_method;
    }
    /**
     * @return string
     */
    public function get_payment_method_name() : string
    {
        return $this->payment_method_name;
    }
    /**
     * @return string
     */
    public function get_notes() : string
    {
        return $this->settings->get($this->get_document_type() . '_notes', '');
    }
    /**
     * @return string
     */
    public function get_user_lang() : string
    {
        return $this->user_lang;
    }
    /**
     * @return int
     */
    public function get_id() : int
    {
        return $this->id;
    }
    /**
     * @return int
     */
    public function get_order_id() : int
    {
        return $this->order_id;
    }
    /**
     * @return float
     */
    public function get_total_paid() : float
    {
        return $this->total_paid;
    }
    /**
     * @return string
     */
    public function get_payment_status() : string
    {
        return $this->payment_status;
    }
    /**
     * @return int
     */
    public function get_date_of_sale() : int
    {
        return $this->date_of_sale;
    }
    /**
     * @return int
     */
    public function get_date_of_issue() : int
    {
        return $this->date_of_issue;
    }
    /**
     * @return int
     */
    public function get_date_of_pay() : int
    {
        return $this->date_of_pay;
    }
    /**
     * @return int
     */
    public function get_date_of_paid() : int
    {
        return $this->paid_date;
    }
    /**
     * @return float
     */
    public function get_total_tax() : float
    {
        return $this->total_tax;
    }
    /**
     * @return float
     */
    public function get_total_net() : float
    {
        return $this->total_net;
    }
    /**
     * @return float
     */
    public function get_total_gross() : float
    {
        return $this->total_gross;
    }
    /**
     * @return float
     */
    public function get_tax() : float
    {
        return $this->tax;
    }
    /**
     * @return float
     */
    public function get_discount() : float
    {
        return $this->discount;
    }
    /**
     * @return string
     */
    public function get_customer_filter_field() : string
    {
        return $this->customer_filtered_name;
    }
    /**
     * @return Customer
     */
    public function get_customer() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer
    {
        $id = $this->customer['id'] ?? '';
        $name = $this->customer['name'] ?? '';
        $street = $this->customer['street'] ?? '';
        $street2 = $this->customer['street2'] ?? '';
        $postcode = $this->customer['postcode'] ?? '';
        $city = $this->customer['city'] ?? '';
        $vat_number = $this->customer['nip'] ?? '';
        $country = $this->customer['country'] ?? '';
        $phone = $this->customer['phone'] ?? '';
        $email = $this->customer['email'] ?? '';
        $state = $this->customer['state'] ?? '';
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentCustomer($id, $name, $street, $postcode, $city, $vat_number, $country, $phone, $email, 'individual', $street2, $state);
    }
    /**
     * @return Recipient
     */
    public function get_recipient() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient
    {
        $name = $this->recipient['name'] ?? '';
        $street = $this->recipient['street'] ?? '';
        $street2 = $this->recipient['street2'] ?? '';
        $postcode = $this->recipient['postcode'] ?? '';
        $city = $this->recipient['city'] ?? '';
        $vat_number = $this->recipient['nip'] ?? '';
        $country = $this->recipient['country'] ?? '';
        $phone = $this->recipient['phone'] ?? '';
        $email = $this->recipient['email'] ?? '';
        $state = $this->recipient['state'] ?? '';
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentRecipient($name, $street, $postcode, $city, $vat_number, $country, $phone, $email, $street2, $state);
    }
    /**
     * @return Seller
     */
    public function get_seller() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller
    {
        $name = $this->settings->has('company_name') ? $this->settings->get('company_name') : '';
        $address = $this->settings->has('company_address') ? $this->settings->get('company_address') : '';
        $nip = $this->settings->has('company_nip') ? $this->settings->get('company_nip') : '';
        $bank_name = $this->settings->has('bank_name') ? $this->settings->get('bank_name') : '';
        $bank_account = $this->settings->has('account_number') ? $this->settings->get('account_number') : '';
        $logo = $this->settings->has('company_logo') ? $this->settings->get('company_logo') : '';
        $signature_user = $this->settings->has('signature_user') ? $this->settings->get('signature_user') : '';
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentSeller(0, $logo, $name, $address, $nip, $bank_name, $bank_account, $signature_user);
    }
    /**
     * @return array
     */
    public function get_items() : array
    {
        return $this->items;
    }
    /**
     * @return int
     */
    public function get_show_order_number() : int
    {
        return 0;
    }
    /**
     * @return int
     */
    public function get_corrected_id() : int
    {
        return $this->corrected_id;
    }
    /**
     * @return int
     */
    public function get_is_correction() : int
    {
        return 0;
    }
}
