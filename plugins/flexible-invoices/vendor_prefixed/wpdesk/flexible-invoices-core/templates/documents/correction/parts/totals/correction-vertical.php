<?php

namespace WPDeskFIVendor;

/**
 * File: parts/totals.php
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals;
?>
<table style="float: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
	<tbody>
	<tr>
		<td>
			<?php 
\esc_html_e('Total', 'flexible-invoices');
?>:
		</td>
		<td style="text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>">
			<strong><?php 
echo $helper->string_as_money($correction->get_total_gross());
?></strong>
		</td>
	</tr>
	<tr>
		<td>
			<?php 
\esc_html_e('Paid', 'flexible-invoices');
?>:
		</td>
		<td style="text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>">
			<strong><?php 
echo $helper->string_as_money($invoice->get_total_paid());
?></strong>
		</td>
	</tr>
	<tr>
		<td>
			<?php 
\esc_html_e('Due', 'flexible-invoices');
?>:
		</td>
		<td style="text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>">
			<strong><?php 
echo $helper->string_as_money(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals::calculate_due_price($invoice->get_total_gross(), $invoice->get_total_paid()));
?></strong>
		</td>
	</tr>
	</tbody>
</table>
<?php 
