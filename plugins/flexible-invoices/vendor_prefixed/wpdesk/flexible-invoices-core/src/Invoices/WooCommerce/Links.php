<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\EmailStatus;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Invoice;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
/**
 * Define order actions on Woocommerce Order List.
 */
class Links
{
    /**
     * @param Document $document
     *
     * @return string
     */
    public static function view_link(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, $without_url = \false) : string
    {
        $url = \wp_nonce_url(\admin_url('post.php?post=' . $document->get_id() . '&action=edit'));
        if ($without_url) {
            return '<p>' . \esc_html($document->get_formatted_number()) . '</p>';
        }
        return '<p><a class="view-document" href="' . \esc_url($url) . '" title="' . \esc_attr($document->get_formatted_number()) . '">' . \esc_html($document->get_formatted_number()) . '</a></p>';
    }
    /**
     * @param int    $order_id
     * @param string $type
     * @param string $label
     *
     * @return string
     */
    public static function generate_link(int $order_id, string $type, string $label) : string
    {
        $url = \wp_nonce_url(\admin_url('admin-ajax.php?action=fi_generate_document&issue_type=action&type=' . $type . '&order_id=' . $order_id));
        if (empty($label)) {
            $label = \esc_html__('Issue Invoice', 'flexible-invoices');
        }
        return '<p><a class="button generate-document generate-' . $type . '" href="' . \esc_url($url) . '" title="' . \esc_attr($label) . '">' . \esc_html($label) . '</a></p>';
    }
    /**
     * @param Document $document
     *
     * @return string
     */
    public static function download_link(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document) : string
    {
        $document_id = $document->get_id();
        $download_url = \wp_nonce_url(\admin_url('admin-ajax.php?action=fi_download_pdf&hash=' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Invoice::document_hash($document) . '&id=' . $document_id . '&save_file=1'));
        return '<p><a class="button get-document" href="' . \esc_url($download_url) . '">' . \esc_html__('Download', 'flexible-invoices') . '</a></p>';
    }
    /**
     * @param Document $document
     *
     * @return string
     */
    public static function email_link(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document) : string
    {
        $output = '';
        if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
            $output .= '<p>';
            $email_url = \wp_nonce_url(\admin_url('admin-ajax.php?action=fi_send_email&document_id=' . $document->get_id()));
            $email_status = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\EmailStatus::get($document);
            $data_attr = ' data-status="' . \esc_attr($email_status) . '"';
            $output .= '<a ' . $data_attr . ' class="button send_document ' . self::email_status_class($email_status) . '" href="' . \esc_url($email_url) . '" title="' . self::get_email_tooltip_attr($email_status) . '" >';
            $output .= \esc_html__('Send email', 'flexible-invoices');
            $output .= '</a>';
            $output .= '</p>';
        }
        return $output;
    }
    public static function download_email_links(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document) : string
    {
        $output = '<div class="fi-download-email-links">';
        $output .= self::download_link($document);
        $output .= self::email_link($document);
        $output .= '</div>';
        return $output;
    }
    private static function email_status_class($status) : string
    {
        if ($status === 'yes') {
            return 'email-send';
        } elseif ($status === 'no') {
            return 'email-not-send';
        }
        return 'email-unknown';
    }
    private static function get_email_tooltip_attr($status) : string
    {
        if ($status === 'yes') {
            return \esc_html__('Click to resend the e-mail', 'flexible-invoices');
        } elseif ($status === 'no') {
            return \esc_html__('Click to send the e-mail', 'flexible-invoices');
        }
        return \esc_html__('Click to send the e-mail', 'flexible-invoices');
    }
}
