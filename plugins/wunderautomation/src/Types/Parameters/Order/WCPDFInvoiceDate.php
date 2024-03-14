<?php

namespace WunderAuto\Types\Parameters\Order;

use WC_Order;
use WPO\WC\PDF_Invoices\Documents\Invoice;
use WPO\WC\PDF_Invoices\Compatibility\WC_DateTime;
use WunderAuto\Types\Parameters\BaseParameter;

/**
 * Class Number
 */
class WCPDFInvoiceDate extends BaseParameter
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->group       = 'wcpdf';
        $this->title       = 'wcpdf_date';
        $this->description = __('WCPDF Invoice date', 'wunderauto');
        $this->objects     = ['order'];

        $this->usesDefault    = false;
        $this->usesDateFormat = true;
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

        /** var WC_DateTime $invoiceDate */
        $invoiceDate = $invoice->get_date();
        if (empty($invoiceDate)) {
            return null;
        }

        return $this->formatDate($invoiceDate, $modifiers);
    }
}
