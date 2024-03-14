<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators;

use Exception;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email\DocumentEmail;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email\EmailInvoice;
/**
 * Invoice creator.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Creators
 */
class InvoiceCreator extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Creators\AbstractDocumentCreator
{
    /**
     * @return string
     */
    public function get_type() : string
    {
        return \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice::DOCUMENT_TYPE;
    }
    /**
     * @param int    $document_id
     * @param string $source_type
     *
     * @throws Exception
     */
    public function create_document_from_source($document_id, $source_type)
    {
        $this->assign_data_from_source(new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Documents\Invoice(), $document_id, $source_type);
    }
    /**
     * @return DocumentEmail
     */
    public function get_email_class() : \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email\DocumentEmail
    {
        return new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Email\EmailInvoice();
    }
}
