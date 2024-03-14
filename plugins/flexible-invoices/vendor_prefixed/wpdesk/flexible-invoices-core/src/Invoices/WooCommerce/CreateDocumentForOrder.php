<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use Exception;
use WC_Order;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators\Creator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\EmailStatus;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\SaveDocument;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Order\ConditionalLogic;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDeskFIVendor\WPDesk\View\Renderer\Renderer;
use function WC;
/**
 * Creates documents delivered from the order and their statuses.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\WooCommerce
 */
class CreateDocumentForOrder implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const STATUS_COMPLETED = 'completed';
    const LOCKED_PREFIX = '_fi_generate_type_';
    const LOCKED_VALUE = 1;
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var SaveDocument
     */
    private $save_document;
    /**
     * @var Renderer
     */
    private $renderer;
    /**
     * @var PDF
     */
    private $pdf;
    /**
     * @var ConditionalLogic
     */
    private $conditional_logic;
    /**
     * @param DocumentFactory $document_factory
     * @param Settings        $settings
     * @param SaveDocument    $save_document
     * @param Renderer        $renderer
     * @param PDF             $pdf
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings $settings, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\SaveDocument $save_document, \WPDeskFIVendor\WPDesk\View\Renderer\Renderer $renderer, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF $pdf)
    {
        $this->document_factory = $document_factory;
        $this->settings = $settings;
        $this->save_document = $save_document;
        $this->renderer = $renderer;
        $this->pdf = $pdf;
        $this->conditional_logic = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Order\ConditionalLogic($settings);
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('wp_ajax_fi_generate_document', [$this, 'generate_document_action']);
        \add_action('woocommerce_order_status_processing', [$this, 'update_invoice_for_processing_status'], 85, 2);
        \add_action('woocommerce_order_status_completed', [$this, 'update_invoice_for_completed_status'], 85, 2);
        $this->fire_order_status_hooks();
    }
    /**
     * Fire hooks for creating documents for selected order statuses.
     */
    private function fire_order_status_hooks()
    {
        foreach ($this->document_factory->get_creators() as $creator) {
            $statuses = $creator->get_auto_create_statuses();
            if (empty($statuses)) {
                continue;
            }
            foreach ($statuses as $status) {
                \add_action('woocommerce_order_status_' . $status, [$this, 'generate_for_order_status'], 10, 2);
            }
        }
    }
    /**
     * @param int      $id
     * @param WC_Order $order
     *
     * @return bool
     * @throws Exception
     * @internal You should not use this directly from another application
     */
    public function generate_for_order_status($id, \WC_Order $order) : bool
    {
        $order_status = $order->get_status();
        $creators = $this->document_factory->get_creators();
        if (!\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_super()) {
            return \false;
        }
        foreach ($creators as $creator) {
            if ($this->lock_process($creator->get_type(), $order->get_id())) {
                $auto_create_status = $creator->get_auto_create_statuses();
                $creator->set_order_id($order->get_id());
                if (\in_array($order_status, $auto_create_status, \true) && $creator->is_allowed_for_auto_create()) {
                    if ($order_status === self::STATUS_COMPLETED) {
                        $_completed_date = $order->get_date_completed();
                        if ($_completed_date === '') {
                            $order->set_date_completed(\current_time('mysql'));
                            $order->save();
                        }
                    }
                    if ($this->conditional_logic->is_invoice_ask($order, $creator) && $this->conditional_logic->is_zero_invoice_ask($order, $creator)) {
                        $this->should_auto_generate_document_and_send_email($order, $creator);
                    }
                }
                $this->release_lock($creator->get_type(), $order->get_id());
            }
        }
        return \true;
    }
    /**
     * @param WC_Order $order Order.
     * @param string   $document_type
     *
     * @return int
     * @throws Exception Document exists.
     * @internal You should not use this directly from another application
     */
    public function generate_document_for_order(\WC_Order $order, string $document_type) : int
    {
        $wpml_user_lang = $order->get_meta('wpml_user_lang', \true);
        $is_generated = (int) $order->get_meta('_' . $document_type . '_generated', \true);
        if (!$is_generated) {
            if (\class_exists('WPDeskFIVendor\\Translator') && !empty($wpml_user_lang)) {
                \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::set_translate_lang($wpml_user_lang);
            }
            $this->document_factory->set_document_type($document_type);
            $creator = $this->document_factory->get_document_creator($order->get_id(), \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Data\DataSourceFactory::ORDER_SOURCE);
            $document = $creator->get_document();
            $creator->set_order_id($order->get_id());
            if ($creator->is_allowed_for_create()) {
                try {
                    $document_id = $this->save_document->save($creator, \true);
                    if ($document_id) {
                        $order->update_meta_data('_' . $document_type . '_generated', $document_id);
                        $this->set_paid_for_order_status($document_id, $document, $order);
                        $order->save_meta_data();
                    }
                } catch (\Exception $e) {
                    throw new \Exception(\esc_html__('Document cannot be created', 'flexible-invoices'));
                }
            } else {
                throw new \Exception(\esc_html__('Document cannot be created', 'flexible-invoices'));
            }
            return $document_id;
        }
        return $is_generated;
    }
    /**
     * @param          $order_id
     * @param WC_Order $order
     *
     * @return void
     */
    public function update_invoice_for_completed_status($order_id, \WC_Order $order)
    {
        if ('yes' === $this->settings->get('invoice_auto_paid_status') && \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_super()) {
            $this->update_invoice_for_processing_status($order_id, $order);
        }
    }
    /**
     * @param          $order_id
     * @param WC_Order $order
     *
     * @return void
     */
    public function update_invoice_for_processing_status($order_id, \WC_Order $order)
    {
        $invoice_id = (int) $order->get_meta(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::META_GENERATED, \true);
        $document = $this->document_factory->get_document_creator($invoice_id)->get_document();
        $this->set_paid_for_order_status($invoice_id, $document, $order);
    }
    /**
     * @param int      $document_id
     * @param Document $document
     * @param WC_Order $order
     */
    private function set_paid_for_order_status(int $document_id, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, \WC_Order $order)
    {
        $order_status = $order->get_status();
        if ($order_status === 'processing' || $order->get_status() === 'completed') {
            $payment_method = $order->get_payment_method();
            if ($document->get_type() === \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::DOCUMENT_TYPE) {
                if ('cod' === $payment_method && $order->get_status() !== 'completed') {
                    \update_post_meta($document_id, '_total_paid', 0);
                    \update_post_meta($document_id, '_payment_status', 'topay');
                } else {
                    \update_post_meta($document_id, '_total_paid', $document->get_total_gross());
                    \update_post_meta($document_id, '_payment_status', 'paid');
                }
            }
        }
    }
    /**
     * Fire ajax action for create document.
     *
     * @internal You should not use this directly from another application
     */
    public function generate_document_action()
    {
        if (isset($_REQUEST['_wpnonce']) && \wp_verify_nonce(\sanitize_key(\wp_unslash($_REQUEST['_wpnonce'])))) {
            $request = \wp_unslash($_REQUEST);
            $type = $request['type'] ?? '';
            $order_id = $request['order_id'] ?? 0;
            $order = \wc_get_order($order_id);
            if (!$type || !$order) {
                \wp_send_json_error(['invoice_number' => '', 'html' => 'Empty type or Order ID']);
            }
            try {
                $document_id = $this->generate_document_for_order($order, $type);
                $creator = $this->document_factory->get_document_creator($document_id);
                $document = $creator->get_document();
                $html = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Links::view_link($document, !$creator->is_allowed_for_edit());
                $email_url = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Links::download_email_links($document);
                \wp_send_json_success(['invoice_number' => $document->get_formatted_number(), 'html' => $html, 'email_url' => $email_url]);
            } catch (\Exception $e) {
                \wp_send_json_error(['invoice_number' => '', 'html' => $e->getMessage()]);
            }
        }
    }
    /**
     * @param WC_Order $order
     * @param Creator  $creator
     *
     * @throws Exception
     */
    private function should_auto_generate_document_and_send_email(\WC_Order $order, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators\Creator $creator)
    {
        $is_generated = (int) $order->get_meta($creator->get_type() . '_generated');
        $document_id = $this->generate_document_for_order($order, $creator->get_type());
        if (!$is_generated && $this->conditional_logic->should_send_email_to_customer()) {
            $document = $this->document_factory->get_document_creator($document_id)->get_document();
            \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::switch_lang($document->get_user_lang());
            \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::set_translate_lang($document->get_user_lang());
            $mailer = \WC()->mailer();
            $emails = $mailer->get_emails();
            $client = $document->get_customer();
            $email_class = 'fi_' . $document->get_type();
            if (!$document->get_order_id()) {
                $emails['fi_invoice_manual']->should_send_email($document, $this->pdf);
            } else {
                if (!empty($emails[$email_class]) && !empty($client->get_email())) {
                    $emails[$email_class]->should_send_email($order, $document, $this->pdf);
                }
            }
        }
    }
    /**
     * @param string $document_type
     * @param        $order_id
     *
     * @return bool
     */
    private function lock_process(string $document_type, $order_id) : bool
    {
        $name = self::LOCKED_PREFIX . $document_type . $order_id;
        $is_locked = self::LOCKED_VALUE === (int) \get_transient($name);
        if (\false === $is_locked) {
            \set_transient($name, self::LOCKED_VALUE, 30);
        }
        return !$is_locked;
    }
    /**
     * @param string $document_type
     * @param        $order_id
     *
     * @return void
     */
    private function release_lock(string $document_type, $order_id)
    {
        $name = self::LOCKED_PREFIX . $document_type . $order_id;
        \delete_transient($name);
    }
}
