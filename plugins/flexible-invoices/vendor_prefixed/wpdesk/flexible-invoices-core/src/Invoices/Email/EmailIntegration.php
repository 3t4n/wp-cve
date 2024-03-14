<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email;

use Exception;
use WC_Order;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\EmailStatus;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Invoice;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Infrastructure\Request;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\OrderNote;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use function WC;
/**
 * @package WPDesk\Library\FlexibleInvoicesCore\Email
 */
class EmailIntegration implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    /**
     * @var DocumentFactory
     */
    private $document_factory;
    /**
     * @var PDF
     */
    private $pdf;
    /**
     * @var OrderNote
     */
    private $order_note;
    /**
     * @param DocumentFactory $document_factory
     * @param PDF             $pdf
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\DocumentFactory $document_factory, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF $pdf, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\OrderNote $order_note)
    {
        $this->document_factory = $document_factory;
        $this->pdf = $pdf;
        $this->order_note = $order_note;
    }
    /**
     * Fire hooks.
     */
    public function hooks()
    {
        \add_action('wp_ajax_fi_send_email', [$this, 'send_document']);
    }
    /**
     * @return void
     * @internal You should not use this directly from another application
     */
    public function send_document()
    {
        $request = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Infrastructure\Request();
        $id = (int) $request->param('get.document_id')->get();
        $nonce = $request->param('get._wpnonce')->get();
        if ($id && ($nonce && \wp_verify_nonce($nonce))) {
            try {
                $creator = $this->document_factory->get_document_creator($id);
                $document = $creator->get_document();
                $client = $document->get_customer();
                $order_id = $document->get_order_id();
                if (empty($client->get_email()) || !\is_email($client->get_email())) {
                    \wp_send_json_error(['invoice_number' => '', 'msg' => \esc_html__('Email address is blank or invalid!', 'flexible-invoices')]);
                }
                $note = '';
                if ($order_id) {
                    $order = \wc_get_order($order_id);
                    if ($order) {
                        $send = $this->send_email($order, $document, 'fi_' . $document->get_type());
                    } else {
                        $send = $this->send_manual_email($document);
                    }
                    $note = \sprintf(\esc_html__('%s was send to the customer', 'flexible-invoices'), $creator->get_name());
                    $this->order_note->add_note($order, $note);
                } else {
                    $send = $this->send_manual_email($document);
                }
                if ($send) {
                    \wp_send_json_success(['invoice_number' => $document->get_formatted_number(), 'msg' => \esc_html__('Email was sent!', 'flexible-invoices'), 'email' => $client->get_email(), 'note' => $note]);
                }
            } catch (\Exception $e) {
                \wp_send_json_error(['invoice_number' => '', 'msg' => $e->getMessage(), 'email' => '']);
            }
        }
        \wp_send_json_error(['invoice_number' => '', 'status' => \false, 'msg' => \esc_html__('Invalid nonce or document ID', 'flexible-invoices'), 'email' => '']);
    }
    /**
     * @param WC_Order $order
     * @param Document $document
     * @param string   $email_class
     *
     * @return bool
     */
    public function send_email(\WC_Order $order, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, string $email_class) : bool
    {
        \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::switch_lang($document->get_user_lang());
        \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::set_translate_lang($document->get_user_lang());
        $mailer = \WC()->mailer();
        $emails = $mailer->get_emails();
        $client = $document->get_customer();
        if (!empty($emails[$email_class]) && !empty($client->get_email())) {
            if ($emails[$email_class] instanceof \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email\DocumentEmail) {
                $emails[$email_class]->should_send_email($order, $document, $this->pdf);
            }
            return \true;
        }
        return \false;
    }
    /**
     * @param Document $document
     *
     * @return bool
     */
    public function send_manual_email(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document) : bool
    {
        \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::switch_lang($document->get_user_lang());
        \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator::set_translate_lang($document->get_user_lang());
        $mailer = \WC()->mailer();
        $emails = $mailer->get_emails();
        $client = $document->get_customer();
        if (!empty($client->get_email())) {
            $emails['fi_invoice_manual']->should_send_email($document, $this->pdf);
            return \true;
        }
        return \false;
    }
}
