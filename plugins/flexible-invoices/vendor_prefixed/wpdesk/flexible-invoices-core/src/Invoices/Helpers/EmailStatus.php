<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WC_Order;
/**
 * Email helper.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Helpers
 */
class EmailStatus
{
    /**
     * @param Document $document
     * @param WC_Order $order
     *
     * @return void
     */
    public static function save(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document, $is_send = \false)
    {
        $status = $is_send ? 'yes' : 'no';
        \update_post_meta($document->get_id(), '_' . $document->get_type() . '_email_send', $status);
    }
    /**
     * @param Document $document
     * @param WC_Order $order
     *
     * @return string
     */
    public static function get(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document) : string
    {
        return (string) \get_post_meta($document->get_id(), '_' . $document->get_type() . '_email_send', \true);
    }
}
