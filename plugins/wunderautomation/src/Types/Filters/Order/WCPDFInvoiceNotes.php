<?php

namespace WunderAuto\Types\Filters\Order;

use WC_Order;
use WPO\WC\PDF_Invoices\Documents\Invoice;
use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Number
 */
class WCPDFInvoiceNotes extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('WCPDF Invoice notes', 'wunderauto');
        $this->description = __('Filter orders based on WCPDF invoice notes.', 'wunderauto');
        $this->objects     = ['order'];

        $this->operators = $this->stringOperators();
        $this->inputType = 'scalar';
        $this->valueType = 'text';
    }

    /**
     * Evaluate filter
     *
     * @return bool
     */
    public function evaluate()
    {
        $order = $this->getObject();
        if (!($order instanceof WC_Order)) {
            return false;
        }

        $invoice = wcpdf_get_invoice($order);
        if (!$invoice instanceof Invoice) {
            return false;
        }

        $actualValue = $invoice->get_notes();
        return $this->evaluateCompare($actualValue);
    }
}
