<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data;

use WP_Post;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\PriceFormatter;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentSeller;
use WPDeskFIVendor\WPDesk\Persistence\PersistentContainer;
/**
 * Get document data form post meta.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Data
 */
class PostMetaDocumentDataSource extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\AbstractDataSource
{
    /**
     * @var PersistentContainer
     */
    public $meta;
    /**
     * @var WP_Post
     */
    public $post;
    /**
     * @param int      $post_id
     * @param Settings $options_container
     * @param string   $document_type
     */
    public function __construct($post_id, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $options_container, string $document_type)
    {
        parent::__construct($options_container, $document_type);
        $this->post_id = (int) $post_id;
        $this->meta = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer($post_id);
        $this->post = \get_post($this->post_id);
        $this->customer = $this->meta->get_fallback('_client', []);
        $this->recipient = $this->meta->get_fallback('_recipient', []);
        $this->seller = $this->meta->get_fallback('_owner', []);
    }
    /**
     * @return int
     */
    public function get_id() : int
    {
        return $this->post_id;
    }
    /**
     * @return int
     */
    public function get_number() : int
    {
        return (int) $this->meta->get_fallback('_number', 1);
    }
    /**
     * @return string
     */
    public function get_formatted_number() : string
    {
        return $this->post->post_title ?? $this->meta->get_fallback('_formatted_number', '');
    }
    /**
     * @return int
     */
    public function get_date_of_sale() : int
    {
        return (int) $this->meta->get_fallback('_date_sale', \strtotime(\current_time('mysql')));
    }
    /**
     * @return int
     */
    public function get_date_of_pay() : int
    {
        return (int) $this->meta->get_fallback('_date_pay', $this->get_date_of_issue() + 60 * 60 * 24 * \intval($this->settings->get($this->get_document_type() . '_default_due_time'), 0));
    }
    /**
     * @return int
     */
    public function get_date_of_issue() : int
    {
        return (int) $this->meta->get_fallback('_date_issue', \strtotime(\current_time('mysql')));
    }
    /**
     * @return Seller
     */
    public function get_seller() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Seller
    {
        if (empty($this->seller)) {
            return parent::get_seller();
        }
        $name = $this->seller['name'] ?? '';
        $address = $this->seller['address'] ?? '';
        $nip = $this->seller['nip'] ?? '';
        $bank_name = $this->seller['bank'] ?? '';
        $bank_account = $this->seller['account'] ?? '';
        $logo = $this->seller['logo'] ?? '';
        $signature_user = $this->seller['signature_user'] ?? '';
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\ValueObjects\DocumentSeller(0, $logo, $name, $address, $nip, $bank_name, $bank_account, $signature_user);
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
        return $this->meta->get('_currency');
    }
    /**
     * @return float
     */
    public function get_discount() : float
    {
        return \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\PriceFormatter::string_to_float($this->meta->get('_discount'));
    }
    /**
     * @return int
     */
    public function get_order_id() : int
    {
        return (int) $this->meta->get_fallback('_wc_order_id', 0);
    }
    /**
     * @return array
     */
    public function get_items() : array
    {
        $products = $this->meta->get_fallback('_products', []);
        $shipping = $this->meta->get_fallback('_shipping', []);
        if (\is_array($products) && \is_array($shipping)) {
            return \array_merge($products, $shipping);
        }
        return [];
    }
    /**
     * @return string
     */
    public function get_payment_method() : string
    {
        return $this->meta->get('_payment_method');
    }
    /**
     * @return string
     */
    public function get_payment_status() : string
    {
        return $this->meta->get_fallback('_payment_status', 'due');
    }
    /**
     * @return string
     */
    public function get_payment_method_name() : string
    {
        return $this->meta->get('_payment_method_name');
    }
    /**
     * @return string
     */
    public function get_notes() : string
    {
        return $this->meta->get('_notes');
    }
    /**
     * @return float
     */
    public function get_total_gross() : float
    {
        return \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\PriceFormatter::string_to_float($this->meta->get_fallback('_total_price', 0.0));
    }
    /**
     * @return float
     */
    public function get_total_net() : float
    {
        return \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\PriceFormatter::string_to_float($this->meta->get_fallback('_total_net', \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::calculate_total_net($this->get_items())));
    }
    /**
     * @return float
     */
    public function get_total_paid() : float
    {
        return \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\PriceFormatter::string_to_float($this->meta->get_fallback('_total_paid', 0.0));
    }
    /**
     * @return float
     */
    public function get_total_tax() : float
    {
        return \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\PriceFormatter::string_to_float($this->meta->get_fallback('_total_tax', \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::calculate_total_vat($this->get_items())));
    }
    /**
     * @return string
     */
    public function get_user_lang() : string
    {
        $user_lang = $this->meta->get('wpml_user_lang');
        if (empty($user_lang)) {
            return \strtolower($this->get_customer()->get_country());
        }
        return $user_lang;
    }
    /**
     * @return int
     */
    public function get_corrected_id() : int
    {
        return (int) $this->meta->get_fallback('_corrected_invoice_id', 0);
    }
    /**
     * @return int
     */
    public function get_is_correction() : int
    {
        return (int) $this->meta->get_fallback('_correction', 0);
    }
    /**
     * @return int
     */
    public function get_show_order_number() : int
    {
        return (int) $this->meta->get_fallback('_add_order_id', 0);
    }
}
