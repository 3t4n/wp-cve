<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
/**
 * Invoice helpers functions.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Helpers
 */
class Invoice
{
    const META_DOWNLOAD_HASH = '_download_hash';
    /**
     * @param Document $document
     *
     * @return string
     */
    public static function document_hash(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document) : string
    {
        $hash = \get_post_meta($document->get_id(), self::META_DOWNLOAD_HASH, \true);
        if (!$hash) {
            $hash = \uniqid('', \true);
            \update_post_meta($document->get_id(), self::META_DOWNLOAD_HASH, $hash);
        }
        return $hash;
    }
    /**
     * @param Document $document
     *
     * @return string
     */
    public static function generate_download_url(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document) : string
    {
        $hash = self::document_hash($document);
        return \admin_url('admin-ajax.php?action=fiw_get_document&id=' . $document->get_id() . '&hash=' . $hash . '&save_file=1');
    }
    /**
     * @param int    $order_id
     * @param string $type
     *
     * @return string|null
     */
    public static function get_document_url(int $order_id, string $type = 'invoice')
    {
        $invoice_id = \get_post_meta($order_id, '_' . $type . '_generated', \true);
        if (!$invoice_id) {
            return null;
        }
        $download_hash = \get_post_meta($invoice_id, self::META_DOWNLOAD_HASH, \true);
        return \admin_url(\sprintf('admin-ajax.php?action=fiw_get_document&id=%1$s&hash=%2$s&save_file=1', $invoice_id, $download_hash));
    }
}
