<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
/**
 * Decorates document for editing && pdf.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Decorators
 */
class DocumentDecorator extends \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\BaseDecorator
{
    /**
     * @return string
     */
    public function get_date_of_paid() : string
    {
        $date_format = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::document_date_paid_format_filter(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::document_date_general_format_filter());
        return \date($date_format, $this->document->get_date_of_paid());
    }
    /**
     * @return string
     */
    public function get_date_of_issue() : string
    {
        $date_format = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::document_date_issue_format_filter(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::document_date_general_format_filter());
        return \date($date_format, $this->document->get_date_of_issue());
    }
    /**
     * @return string
     */
    public function get_date_of_sale() : string
    {
        $date_format = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::document_date_sale_format_filter(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::document_date_general_format_filter());
        return \date($date_format, $this->document->get_date_of_sale());
    }
    /**
     * @return string
     */
    public function get_date_of_pay() : string
    {
        $date_format = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::document_date_pay_format_filter(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::document_date_general_format_filter());
        return \date($date_format, $this->document->get_date_of_pay());
    }
    /**
     * @return string
     */
    public function get_payment_status_name() : string
    {
        foreach ($this->strategy->get_payment_statuses() as $method_key => $method_name) {
            if ($method_key === $this->document->get_payment_status()) {
                return $method_name;
            }
        }
        return $this->document->get_payment_status();
    }
    /**
     * @return float
     */
    public function get_total_tax() : float
    {
        return $this->currency_helper->number_format($this->document->get_total_tax());
    }
    /**
     * @return float
     */
    public function get_total_net() : float
    {
        return $this->currency_helper->number_format($this->document->get_total_net());
    }
    /**
     * @return float
     */
    public function get_total_gross() : float
    {
        return $this->currency_helper->number_format($this->document->get_total_gross());
    }
}
