<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 */

namespace S123\Includes\Woocommerce;

use S123\Includes\Base\S123_BaseController;
use S123\Includes\Requests\S123_ApiRequest;

if (!defined('ABSPATH')) exit;

class I123_OrderEmail extends S123_BaseController
{
    /**
     * API request object
     *
     */
    private $apiRequest;

    public function __construct(S123_ApiRequest $api = null)
    {
        parent::__construct();
        $this->apiRequest = $api ?: new S123_ApiRequest();
    }

    public function s123_register()
    {
        add_action('woocommerce_email_sent', array($this, 'i123_delete_pdf'));
        add_action('woocommerce_email_attachments', array($this, 'i123_attach_invoice'), 99, 3);
        add_action('woocommerce_order_actions', array($this, 'i123_send_invoice'));
        add_action('woocommerce_order_action_send_i123_invoice', array($this, 'i123_trigger_action_send_invoice'));
        add_filter('woocommerce_email_classes', array($this, 'i123_send_invoice_woocommerce_email'));
    }

    public function i123_send_invoice_woocommerce_email($email_classes)
    {
        $email_classes['WC_Invoice123_Generated_Email'] = new WC_Invoice123_Generated_Email();

        return $email_classes;
    }

    public function i123_send_invoice($actions)
    {
        $wc_emails = WC()->mailer()->get_emails();

        $class_name = 'WC_Invoice123_Generated_Email';

        if (isset($wc_emails[$class_name]) && $wc_emails[$class_name]->settings['enabled'] === 'no') {
            return $actions;
        }

        $actions['send_i123_invoice'] = __('Send invoice generated from Invoice123', 's123-invoices');

        return $actions;
    }

    public function i123_trigger_action_send_invoice($order)
    {
        $wc_emails = WC()->mailer()->get_emails();

        $class_name = 'WC_Invoice123_Generated_Email';

        // Send custom email
        $wc_emails[$class_name]->trigger($order->get_id());
    }

    /*
    * Attaches invoice to email
    */
    public function i123_attach_invoice($attachments, $email_id, $order)
    {
        // Avoiding errors and problems
        if (!is_a($order, 'WC_Order') || !isset($email_id)) {
            return $attachments;
        }

        $orderId = $order->get_meta('_generated_invoice_id');

        if (!$orderId) {
            return $attachments;
        }

        if ($email_id !== 'i123_generated_invoice') {
            return $attachments;
        }

        $lang = get_locale() === 'lt_LT' ? 'lt' : 'en';

        $apiPdfUrl = str_replace(
            ['{id}', '{language}'],
            [$orderId, $lang],
            $this->apiRequest->getApiUrl('invoice_pdf')
        );

        $pdfContent = $this->apiRequest->i123_plainGetRequest($apiPdfUrl);

        $wp_upload_dir = wp_upload_dir();

        $upload_dir = $wp_upload_dir['basedir'] . '/invoice123/';

        $name = 'invoice-' . $orderId . '.pdf';

        if (!file_exists($upload_dir)) {
            wp_mkdir_p($upload_dir);
        }

        $file_path = $upload_dir . $name;

        file_put_contents($file_path, $pdfContent['body']);

        $attachments[] = $file_path;

        return $attachments;
    }

    public function i123_delete_pdf()
    {
        $wp_upload_dir = wp_upload_dir();

        $upload_dir = $wp_upload_dir['basedir'] . '/invoice123/';

        if (!file_exists($upload_dir)) {
            return;
        }

        $files = glob($upload_dir . '*');

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}