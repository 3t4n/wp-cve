<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration;

use Exception;
use WPDeskFIVendor\Psr\Log\LoggerInterface;
use RuntimeException;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentExceptions\UnknownDocumentTypeException;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators\AbstractDocumentCreator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator\DocumentCreator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\PostMetaDocumentDecorator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\EmailStatus;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType;
use WPDeskFIVendor\WPDesk\Mutex\WordpressMySQLLockMutex;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Save document as custom post type.
 *
 * This class creates document as custom post type and saves post meta.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class SaveDocument implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var SettingsStrategy
     */
    private $strategy;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $plugin_version;
    /**
     * @param DocumentFactory  $document_factory
     * @param Settings         $settings
     * @param SettingsStrategy $strategy
     * @param LoggerInterface  $logger
     * @param string           $plugin_version
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\SettingsStrategy\SettingsStrategy $strategy, \WPDeskFIVendor\Psr\Log\LoggerInterface $logger, string $plugin_version)
    {
        $this->document_factory = $document_factory;
        $this->settings = $settings;
        $this->strategy = $strategy;
        $this->logger = $logger;
        $this->plugin_version = $plugin_version;
    }
    /**
     * Fire hooks.
     */
    public function hooks()
    {
        \add_action('save_post', [$this, 'save_custom_fields_action'], 2, 2);
    }
    /**
     * @param int      $post_id
     * @param \WP_Post $post
     *
     * @return false|int
     */
    public function save_custom_fields_action($post_id, $post)
    {
        if (!isset($_POST['flexible_invoices_nonce'])) {
            return \false;
        }
        if (!\wp_verify_nonce(\wp_unslash(\sanitize_key($_POST['flexible_invoices_nonce'])), 'flexible_invoices_nonce')) {
            return \false;
        }
        if (\defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return \false;
        }
        if ($post->post_status === 'auto-draft') {
            return \false;
        }
        try {
            $type = $_REQUEST['document_type'] ?? \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::DOCUMENT_TYPE;
            $creators = $this->document_factory->get_creators();
            if (isset($creators[$type])) {
                $this->document_factory->set_document_type($type);
            } else {
                throw new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentExceptions\UnknownDocumentTypeException('Unknown document type: ' . $type);
            }
            $creator = $this->document_factory->get_document_creator($post_id, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory::POST_SOURCE);
            $this->save($creator);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $post_id;
    }
    /**
     * @param DocumentCreator $document_creator
     * @param bool            $should_insert_post
     *
     * @return int
     * @throws RuntimeException Throw exception for mutex lock.
     */
    public function save(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Creator\DocumentCreator $document_creator, $should_insert_post = \false)
    {
        $document_id = 0;
        try {
            $document = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\PostMetaDocumentDecorator($document_creator->get_document(), $this->strategy);
            $mutex = new \WPDeskFIVendor\WPDesk\Mutex\WordpressMySQLLockMutex('_fiw_mutex', 30);
            if (!$mutex->acquireLock()) {
                throw new \RuntimeException('Cannot acquire lock');
            }
            try {
                $numbering = $document_creator->get_document_numbering($document);
                $formatted_number = $numbering->get_formatted_number();
                if ($should_insert_post) {
                    $document_id = $this->should_insert_post($formatted_number);
                    if ($document_id === 0) {
                        throw new \RuntimeException('Cannot insert Invoice post');
                    }
                    $document->set_id($document_id);
                } else {
                    $document_id = $document->get_id();
                }
                $meta = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer($document_id);
                if (empty($meta->get('_formatted_number'))) {
                    $numbering->increase_number();
                }
                $meta->set('_formatted_number', $formatted_number);
                $meta->set('_number', $numbering->get_number());
                unset($numbering);
            } finally {
                $mutex->releaseLock();
            }
            if (isset($meta)) {
                $meta->set('_date_issue', $document->get_date_of_issue());
                $meta->set('_date_sale', $document->get_date_of_sale());
                $meta->set('_date_pay', $document->get_date_of_pay());
                $meta->set('_date_paid', $document->get_date_of_paid());
                $meta->set('_products', $document->get_items());
                $this->save_client_meta($meta, $document->get_customer_as_array());
                $meta->set('_recipient', $document->get_recipient_as_array());
                $meta->set('_owner', $document->get_seller_as_array());
                $meta->set('_total_price', \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::calculate_total_gross($document->get_items()));
                $meta->set('_total_net', \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::calculate_total_net($document->get_items()));
                $meta->set('_total_tax', \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::calculate_total_vat($document->get_items()));
                $meta->set('_total_paid', $document->get_total_paid());
                $meta->set('_discount', $document->get_discount());
                $meta->set('_currency', $document->get_currency());
                $meta->set('_type', $document->get_type());
                $meta->set('_payment_status', $document->get_payment_status());
                $meta->set('_payment_method', $document->get_payment_method());
                $meta->set('_payment_method_name', $document->get_payment_method_name());
                $meta->set('_notes', \sanitize_textarea_field($document->get_notes()));
                $meta->set('wpml_user_lang', \sanitize_text_field($document->get_user_lang()));
                $meta->set('_add_order_id', $document->get_show_order_number());
                $meta->set('_wc_order_id', $document->get_order_id());
                $meta->set('_version', $this->plugin_version);
                $this->save_tax_items($meta, $document->get_items());
                \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\EmailStatus::save($document);
                $document_creator->custom_meta($document, $meta)->save();
                /**
                 * Fires after document save.
                 *
                 * @param Document          $document    Document type.
                 * @param MetaPostContainer $meta        Meta Container.
                 * @param int               $document_id Document ID.
                 *
                 * @since 3.0.0
                 */
                \do_action('fi/core/document/save', $document, $meta, $document_id);
                \sleep(1);
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $document_id;
    }
    /**
     * @param $title
     *
     * @return int
     */
    private function should_insert_post($title) : int
    {
        $invoice_post = ['post_title' => $title, 'post_content' => '', 'post_status' => 'publish', 'post_type' => \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\RegisterPostType::POST_TYPE_NAME, 'post_date' => \current_time('mysql')];
        return (int) \wp_insert_post($invoice_post);
    }
    /**
     * @param MetaPostContainer $meta
     * @param array             $customer
     *
     * @return void
     */
    private function save_client_meta(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer $meta, array $customer)
    {
        foreach ($customer as $key => $value) {
            if ($key === 'nip') {
                $meta->set('_client_vat_number', \sanitize_text_field($value));
            } else {
                $meta->set('_client_' . \sanitize_key($key), \sanitize_text_field($value));
            }
        }
        $meta->set('_client_filter_field', \sanitize_text_field($customer['name']));
        $meta->set('_client', $customer);
    }
    /**
     * @param MetaPostContainer $meta
     * @param array             $products
     *
     * @return void
     */
    private function save_tax_items(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer $meta, array $products)
    {
        if (!empty($products)) {
            $total_taxes = $this->create_tax_totals($products);
            $meta->delete('_tax_rates');
            $meta->delete('_tax_ids');
            foreach ($total_taxes as $tax_id => $tax) {
                $meta->set('_tax_rates', $tax_id, \true);
                $meta->set('_tax_ids', $tax['vat_index'], \true);
                $meta->set('_total_vat_sum_' . $tax_id, $tax['total_vat_sum']);
            }
        }
    }
    /**
     * @param array $products
     *
     * @return array
     */
    private function create_tax_totals(array $products) : array
    {
        $tax_types = [];
        foreach ($products as $product) {
            if (!isset($tax_types[$product['vat_type']]['total_vat_sum'])) {
                $tax_types[$product['vat_type']]['vat_type'] = 0;
                $tax_types[$product['vat_type']]['vat_index'] = 0;
                $tax_types[$product['vat_type']]['qty'] = 0;
                $tax_types[$product['vat_type']]['total_vat_sum'] = 0;
            }
            $tax_types[$product['vat_type']]['vat_type'] = $product['vat_type'];
            $tax_types[$product['vat_type']]['vat_index'] = $product['vat_type_index'];
            $tax_types[$product['vat_type']]['qty'] += $product['quantity'];
            $tax_types[$product['vat_type']]['total_vat_sum'] += $product['vat_sum'];
        }
        return $tax_types;
    }
}
