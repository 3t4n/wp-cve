<?php

namespace WPDeskFIVendor;

/**
 * File: parts/totals.php
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals;
?>
<table class="table-without-margin">
	<tbody>
	<tr>
		<td style="width:33.3%;text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>;"><?php 
\esc_html_e('Total', 'flexible-invoices');
?>: <strong><?php 
echo $helper->string_as_money($invoice->get_total_gross());
?></strong></td>
		<td style="width:33.3%;text-align: center;"><?php 
\esc_html_e('Paid', 'flexible-invoices');
?>: <strong><?php 
echo $helper->string_as_money($invoice->get_total_paid());
?></strong></td>
		<td style="width:33.3%;text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;"><?php 
\esc_html_e('Due', 'flexible-invoices');
?>: <strong><?php 
echo $helper->string_as_money(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::calculate_due_price($invoice->get_total_gross(), $invoice->get_total_paid()));
?></strong></td>
	</tr>
	</tbody>
</table>
<?php 
