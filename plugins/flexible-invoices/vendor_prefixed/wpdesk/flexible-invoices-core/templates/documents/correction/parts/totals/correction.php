<?php

namespace WPDeskFIVendor;

/**
 * File: parts/totals.php
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals;
$total_section = '<table class="totals">
    			<tbody>
    				<tr>
    					<td style="width:33.3%;">' . \esc_html__('Total', 'flexible-invoices') . ': <strong>' . $helper->string_as_money($correction->get_total_gross()) . '</strong></td>
						<td style="width:33.3%;text-align: center;">' . \esc_html__('Paid', 'flexible-invoices') . ': <strong>' . $helper->string_as_money($correction->get_total_paid()) . '</strong></td>
						<td style="width:33.3%;text-align: right;">' . \esc_html__('Due', 'flexible-invoices') . ': <strong>' . $helper->string_as_money(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::calculate_due_price($correction->get_total_gross(), $correction->get_total_paid())) . '</strong></td>
    				</tr>
    			</tbody>
    		</table>';
/**
 * Filters total section.
 *
 * @param string   $total_section Total section HTML.
 * @param Document $correction    Document object.
 * @param array    $products      Document products.
 * @param Customer $client        Customer.
 */
echo \apply_filters('flexible_invoices_total', $total_section, $correction, $products, $client);
