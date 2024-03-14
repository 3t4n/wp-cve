<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\EmailStatus;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Invoice;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF;
/**
 * Invoice manual email class.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Email
 */
class EmailManualInvoice extends \WC_Email
{
    /**
     * @var string
     */
    protected $download_url;
    /**
     * @var string
     */
    private $document_name;
    public function __construct()
    {
        parent::__construct();
        $this->id = 'fi_invoice_manual';
        $this->title = \esc_html__('Invoice Manual (Flexible Invoices)', 'flexible-invoices');
        $this->description = \esc_html__('Email with invoice (Flexible Invoices).', 'flexible-invoices');
        $this->heading = \esc_html__('Email with invoice', 'flexible-invoices');
        $this->subject = \esc_html__('[{site_title}] {document_number}', 'flexible-invoices');
        $this->template_html = 'emails/invoice-manual.php';
        $this->template_plain = 'emails/plain/invoice-manual.php';
        $this->template_base = \trailingslashit(__DIR__) . 'templates/';
        $this->customer_email = \true;
        $this->manual = \true;
    }
    /**
     * Get content HTML.
     *
     * @return string
     */
    public function get_content_html() : string
    {
        return \wc_get_template_html($this->template_html, ['order' => $this->object, 'download_url' => $this->download_url, 'document_name' => $this->document_name, 'email_heading' => $this->get_heading(), 'sent_to_admin' => \false, 'plain_text' => \false, 'email' => $this->recipient], '', $this->template_base);
    }
    /**
     * @return string
     */
    public function get_content_plain() : string
    {
        return \wc_get_template_html($this->template_plain, ['order' => $this->object, 'download_url' => $this->download_url, 'document_name' => $this->document_name, 'email_heading' => $this->get_heading(), 'sent_to_admin' => \false, 'plain_text' => \true, 'email' => $this->recipient], '', $this->template_base);
    }
    /**
     * Initialise Settings Form Fields
     *
     * @return void
     */
    public function init_form_fields()
    {
        $this->form_fields = ['subject' => ['title' => \esc_html__('Subject', 'woocommerce'), 'type' => 'text', 'placeholder' => $this->subject, 'default' => ''], 'heading' => ['title' => \esc_html__('Email Heading', 'woocommerce'), 'type' => 'text', 'placeholder' => $this->heading, 'default' => ''], 'email_type' => ['title' => \esc_html__('Email type', 'woocommerce'), 'type' => 'select', 'description' => \esc_html__('Choose which format of email to send.', 'woocommerce'), 'default' => 'html', 'class' => 'email_type', 'options' => ['plain' => \esc_html__('Plain text', 'woocommerce'), 'html' => \esc_html__('HTML', 'woocommerce'), 'multipart' => \esc_html__('Multipart', 'woocommerce')]]];
    }
    /**
     * @param Document $document Document.
     * @param PDF      $pdf
     *
     * @throws \Exception Exception.
     */
    public function should_send_email(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PDF $pdf)
    {
        /**
         * Fire hook before email send.
         *
         * @param Document $document
         * @param PDF      $pdf
         */
        \do_action('fi/core/email/before/send', $document, $pdf);
        $this->recipient = $document->get_customer()->get_email();
        $this->download_url = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Invoice::generate_download_url($document);
        $this->document_name = $document->get_formatted_number();
        $this->placeholders['{document_number}'] = $this->document_name;
        if (!$this->get_recipient()) {
            return;
        }
        $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), []);
        \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\EmailStatus::save($document, \true);
        /**
         * Fire hook after email send.
         *
         * @param Document $document
         */
        \do_action('fi/core/email/after/send', $document);
    }
}
