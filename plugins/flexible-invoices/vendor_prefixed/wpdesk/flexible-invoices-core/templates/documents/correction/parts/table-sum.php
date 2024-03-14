<?php

namespace WPDeskFIVendor;

/**
 * File: parts/totals.php
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template;
$price_label = $hideVat ? \esc_html__('Price', 'flexible-invoices') : \esc_html__('Net price', 'flexible-invoices');
$amount_label = $hideVat ? \esc_html__('Amount', 'flexible-invoices') : \esc_html__('Net amount', 'flexible-invoices');
$table_sum_width = '300px';
$exchange_table = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::template_exchange_vertical_filter($correction, $products, $client);
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
					<td id="total_sum_net_price"
						class="number"><?php 
echo $helper->string_as_money($total_net_price);
?></td>
					<?php 
if (!$hideVat) {
    ?>
						<td class="number">X</td>
						<td id="total_sum_tax_price"
							class="number"><?php 
    echo $helper->string_as_money($total_tax_amount);
    ?></td>
						<td id="total_sum_gross_price"
							class="number"><?php 
    echo $helper->string_as_money($total_gross_price);
    ?></td>
					<?php 
}
?>
				</tr>

				<?php 
if (!$hideVat) {
    ?>
					<?php 
    foreach ($total_tax_net_price as $taxType => $price) {
        ?>
						<tr>
							<td class="sum-title"><?php 
        \esc_html_e('Including', 'flexible-invoices');
        ?></td>
							<td class="number"><?php 
        echo $helper->string_as_money($price);
        ?></td>
							<td class="number"><?php 
        echo $taxType;
        ?></td>
							<td class="number"><?php 
        echo $helper->string_as_money($total_tax_tax_amount[$taxType]);
        ?></td>
							<td class="number"><?php 
        echo $helper->string_as_money($total_tax_gross_price[$taxType]);
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
echo \esc_attr($col2_styles);
?>">
			<?php 
require __DIR__ . '/totals/' . $correction->get_type() . '-vertical.php';
?>
		</td>
	</tr>
</table>
<?php 
/**
 * Exchange table
 */
$exchange_table = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::template_exchange_vertical_filter($correction, $products, $client);
if (!empty($exchange_table)) {
    ?>
	<table class="table-without-margin" style="margin-top: 10px;">
		<tr>
			<td style="<?php 
    echo \esc_attr($col1_styles);
    ?>">
				<?php 
    echo $exchange_table;
    ?>
			</td>
			<td style="<?php 
    echo \esc_attr($col2_styles);
    ?>">
				&nbsp;&nbsp;
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
$note = $correction->get_notes();
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
			<p><?php 
\esc_html_e('Related to invoice:', 'flexible-invoices');
?> <strong><?php 
echo $corrected_invoice->get_formatted_number();
?></strong></p>
			<p><?php 
\esc_html_e('Invoice issue date:', 'flexible-invoices');
?> <strong><?php 
echo $corrected_invoice->get_date_of_issue();
?></strong></p>
            <?php 
\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::template_correction_after_notes($correction, $client_country, $hideVat, $hideVatNumber);
?>
		</td>
	</tr>
</table>

<?php 
