<?php

namespace WPDeskFIVendor;

/**
 * File: parts/totals.php
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
$price_label = $hideVat ? \esc_html__('Price', 'flexible-invoices') : \esc_html__('Net price', 'flexible-invoices');
$amount_label = $hideVat ? \esc_html__('Amount', 'flexible-invoices') : \esc_html__('Net amount', 'flexible-invoices');
$table_sum_width = '300px';
$exchange_table = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::template_exchange_vertical_filter($invoice, $products, $client);
if (empty($exchange_table)) {
    $table_sum_width = 'auto';
}
$col1_styles = 'width:78%;text-align:' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left') . ';';
$col2_styles = 'width:22%;text-align:' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right') . ';';
$table_sum_styles = 'width:' . $table_sum_width . ';text-align:' . \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right') . ';';
?>
<table class="table-without-margin">
	<tr>
		<td style="<?php 
echo \esc_attr($col1_styles);
?>">
			<table class="item-table table-without-margin" style="<?php 
echo isset($table_sum_styles) ? $table_sum_styles : '';
?>">
				<thead>
				<tr>
					<th></th>
					<th><h3><?php 
echo \esc_html($amount_label);
?></h3></th>
					<?php 
if (!$hideVat) {
    ?>
						<th><h3><?php 
    \esc_html_e('Tax rate', 'flexible-invoices');
    ?></h3></th>
						<th><h3><?php 
    \esc_html_e('Tax amount', 'flexible-invoices');
    ?></h3></th>
						<th><h3><?php 
    \esc_html_e('Gross amount', 'flexible-invoices');
    ?></h3></th>
					<?php 
}
?>
				</tr>
				</thead>

				<tbody>
				<tr>
					<td class="sum-title"><?php 
\esc_html_e('Total', 'flexible-invoices');
?></td>
					<td id="total_sum_net_price" class="number"><?php 
echo \esc_html($totals['total_net_sum']);
?></td>
					<?php 
if (!$hideVat) {
    ?>
						<td class="number">X</td>
						<td id="total_sum_tax_price" class="number"><?php 
    echo \esc_html($totals['total_tax_sum']);
    ?></td>
						<td id="total_sum_gross_price" class="number"><?php 
    echo \esc_html($totals['total_gross_sum']);
    ?></td>
					<?php 
}
?>
				</tr>

				<?php 
if (!$hideVat) {
    ?>
					<?php 
    foreach ($totals_taxes as $tax_name => $total_tax) {
        ?>
						<tr>
							<td class="sum-title"><?php 
        \esc_html_e('Including', 'flexible-invoices');
        ?></td>
							<td class="number"><?php 
        echo \esc_html($total_tax['total_net_sum']);
        ?></td>
							<td class="number"><?php 
        echo \esc_html($tax_name);
        ?></td>
							<td class="number"><?php 
        echo \esc_html($total_tax['total_vat_sum']);
        ?></td>
							<td class="number"><?php 
        echo \esc_html($total_tax['total_gross_sum']);
        ?></td>
						</tr>
					<?php 
    }
    ?>
				<?php 
}
?>

				</tbody>
			</table>
		</td>
		<td style="<?php 
echo $col2_styles;
?>">
			<?php 
require \dirname(__DIR__, 2) . '/parts/totals/vertical.php';
?>
		</td>
	</tr>
</table>
<?php 
/**
 * Exchange table
 */
if (!empty($exchange_table)) {
    ?>
	<table class="table-without-margin" style="margin-top: 10px;">
		<tr>
			<td style="width:70%">
				<?php 
    echo $exchange_table;
    ?>
			</td>
			<td style="width:30%; padding-left: 10px;">
				&nbsp;
			</td>
		</tr>
	</table>
<?php 
}
?>
<table class="table-without-margin" style="margin-top: 10px;">
	<tr>
		<td>
			<?php 
$note = $invoice->get_notes();
?>
			<?php 
if (!empty($note)) {
    ?>
				<p><strong><?php 
    \esc_html_e('Notes', 'flexible-invoices');
    ?></strong></p>
				<p><?php 
    echo \str_replace(\PHP_EOL, '<br/>', $note);
    ?></p>
			<?php 
}
?>

            <?php 
\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::template_invoice_after_notes($invoice, $client_country, $hideVat, $hideVatNumber);
?>

			<?php 
if ($invoice->get_show_order_number()) {
    ?>
				<?php 
    $order = $invoice->get_order_number();
    ?>
				<p><?php 
    \esc_html_e('Order number', 'flexible-invoices');
    ?>: <?php 
    echo $invoice->get_order_number();
    ?></p>
			<?php 
}
?>
		</td>
	</tr>
</table>

<?php 
