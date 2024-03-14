<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 */

namespace S123\Includes\Woocommerce;

use WC_Email;

if (!defined('ABSPATH')) exit;

class WC_Invoice123_Generated_Email extends WC_Email
{
    public function __construct()
    {
        $this->id = 'i123_generated_invoice';
        $this->customer_email = true;
        $this->title = __('Invoice generated', 's123-invoices');
        $this->description = __('Effortlessly send professionally generated PDF invoices from app.invoice123.com to customers with this email notifications', 's123-invoices');
        $this->template_html = 'emails/customer-invoice.php';
        $this->template_plain = 'emails/plain/customer-invoice.php';
        $this->placeholders = array(
            '{order_date}' => '',
            '{order_number}' => '',
        );

        parent::__construct();

        $this->manual = true;
    }

    public function trigger($order_id, $order = false)
    {
        if ($order_id && !is_a($order, 'WC_Order')) {
            $order = wc_get_order($order_id);
        }

        if (is_a($order, 'WC_Order')) {
            $this->object = $order;
            $this->recipient = $this->object->get_billing_email();
            $this->placeholders['{order_date}'] = wc_format_datetime($this->object->get_date_created());
            $this->placeholders['{order_number}'] = $this->object->get_order_number();
        }

        if (!$this->object->get_meta('_generated_invoice_id')) {
            return;
        }

        if (!$this->is_enabled() || !$this->get_recipient()) {
            return;
        }

        $this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());

        $this->object->add_order_note(__('Invoice was sent to user', 's123-invoices'), false, true);
    }

    public function get_content_html()
    {
        return wc_get_template_html(
            $this->template_html,
            array(
                'order' => $this->object,
                'email_heading' => $this->get_heading(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin' => false,
                'plain_text' => false,
                'email' => $this,
            )
        );
    }

    public function get_content_plain()
    {
        return wc_get_template_html(
            $this->template_plain,
            array(
                'order' => $this->object,
                'email_heading' => $this->get_heading(),
                'additional_content' => $this->get_additional_content(),
                'sent_to_admin' => false,
                'plain_text' => true,
                'email' => $this,
            )
        );
    }

    public function get_default_subject()
    {
        return __('[{site_title}]: New Invoice for Order #{order_number}', 's123-invoices');
    }

    public function get_default_heading()
    {
        return __('New Invoice for Order: #{order_number}', 's123-invoices');
    }

    public function get_default_additional_content()
    {
        return __('Invoice is attached to this email.', 's123-invoices');
    }
}