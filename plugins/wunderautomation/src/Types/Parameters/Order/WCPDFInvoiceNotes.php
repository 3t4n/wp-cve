<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WPO\WC\PDF_Invoices\Documents\Invoice;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Number
 */
class WCPDFInvoiceNotes extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'wcpdf';
        $this->title       = 'wcpdf_notes';
        $this->description = __('WCPDF Invoice notes', 'wunderauto');
        $this->objects     = ['order'];

        $this->dataType = 'string';

        $this->usesDefault = false;
    }

    /**
     * @param WC_Order  $order
     * @param \stdClass $modifiers
     *
     * @return mixed
     */
    public function getValue($order, $modifiers)
    {
        $invoice = wcpdf_get_invoice($order);
        if (!$invoice instanceof Invoice) {
            return null;
        }

        return $this->formatField($invoice->get_notes(), $modifiers);
    }
}
