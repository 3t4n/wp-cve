<?php

namespace WunderAuto\Types\Filters\Order;

use WC_Order;
use WPO\WC\PDF_Invoices\Documents\Invoice;
use WunderAuto\Types\Filters\BaseFilter;

/**
 * Class Number
 */
class WCPDFInvoiceDate extends BaseFilter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->group       = __('Order', 'wunderauto');
        $this->title       = __('WCPDF Invoice date', 'wunderauto');
        $this->description = __('Filter orders based on WCPDF invoice date.', 'wunderauto');
        $this->objects     = ['order'];

        $this->operators = $this->dateOperators();
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

        $actualValue = $invoice->get_date();
        if (empty($actualValue)) {
            return false;
        }

        return $this->evaluateCompare($actualValue);
    }
}
