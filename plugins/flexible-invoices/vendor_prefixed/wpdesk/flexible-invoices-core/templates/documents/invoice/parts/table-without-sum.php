<?php

namespace WPDeskFIVendor;

/**
 * File: parts/table.php
 */
$price_label = $hideVat ? \esc_html__('Price', 'flexible-invoices') : \esc_html__('Net price', 'flexible-invoices');
$amount_label = $hideVat ? \esc_html__('Amount', 'flexible-invoices') : \esc_html__('Net amount', 'flexible-invoices');
$product_name_style = \true === $hideVat ? 'width: 50%' : 'width: 30%';
?>
<table class="item-table">
	<thead>
	<tr>
		<th><h3><?php 
\esc_html_e('#', 'flexible-invoices');
?></h3></th>
		<th class="item-title" style="<?php 
echo \esc_html($product_name_style);
?>"><h3><?php 
\esc_html_e('Name', 'flexible-invoices');
?></h3></th>
		<?php 
if (!$pkwiuEmpty) {
    ?>
			<th><h3><?php 
    \esc_html_e('SKU', 'flexible-invoices');
    ?></h3></th>
		<?php 
}
?>
		<th><h3><?php 
\esc_html_e('Quantity', 'flexible-invoices');
?></h3></th>
		<th><h3><?php 
\esc_html_e('Unit', 'flexible-invoices');
?></h3></th>
		<th><h3><?php 
echo \esc_html($price_label);
?></h3></th>
		<?php 
if (!$discountEmpty) {
    ?>
			<th><h3><?php 
    \esc_html_e('Discount', 'flexible-invoices');
    ?></h3></th>
		<?php 
}
?>
		<th><h3><?php 
echo $amount_label;
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
	<?php 
$totals = $invoice->array_to_string_as_money($invoice->get_totals());
$totals_taxes = $invoice->array_to_string_as_money($invoice->get_totals_by_taxes());
$totals = $invoice->array_to_string_as_money($invoice->get_totals());
$items = $invoice->get_items_as_money();
foreach ($items as $index => $item) {
    $index++;
    ?>
		<tr>
			<td class="center"><?php 
    echo $index;
    ?></td>
			<td class="left"><?php 
    echo \esc_html($item['name']);
    ?></td>
			<?php 
    if (!$pkwiuEmpty) {
        ?>
				<td><?php 
        if (isset($item['sku'])) {
            echo \wordwrap($item['sku'], 6, "\n", \true);
        }
        ?></td>
			<?php 
    }
    ?>
			<td class="quantity number"><?php 
    echo \esc_html($item['quantity']);
    ?></td>
			<td class="unit center"><?php 
    echo \esc_html($item['unit']);
    ?></td>
			<td class="net-price number"><?php 
    echo \esc_html($item['net_price']);
    ?></td>
			<?php 
    if (!$discountEmpty) {
        ?>
				<td class="discount number"><?php 
        if (isset($item['discount'])) {
            echo \esc_html($helper->discount_price($item));
        }
        ?></td>
			<?php 
    }
    ?>

			<td class="total-net-price number"><?php 
    echo \esc_html($item['net_price_sum']);
    ?></td>
			<?php 
    if (!$hideVat) {
        ?>
				<td class="tax-rate number"><?php 
        echo \esc_html($item['vat_type_name']);
        ?></td>
				<td class="tax-amount number"><?php 
        echo \esc_html($item['vat_sum']);
        ?></td>
				<td class="total-gross-price number"><?php 
        echo \esc_html($item['total_price']);
        ?></td>
			<?php 
    }
    ?>
		</tr>
	<?php 
}
?>
	</tbody>
</table>
<?php 
