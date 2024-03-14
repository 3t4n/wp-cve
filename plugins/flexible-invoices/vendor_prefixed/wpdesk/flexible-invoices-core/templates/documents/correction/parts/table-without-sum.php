<?php

namespace WPDeskFIVendor;

/**
 * File: parts/table.php
 */
$price_label = $hideVat ? \esc_html__('Price', 'flexible-invoices') : \esc_html__('Net price', 'flexible-invoices');
$amount_label = $hideVat ? \esc_html__('Amount', 'flexible-invoices') : \esc_html__('Net amount', 'flexible-invoices');
$correction_colspan = 6;
$product_name_style = \true === $hideVat ? 'width: 50%' : 'width: 30%';
?>
<table class="item-table">
	<thead>
	<tr>
		<th><?php 
\esc_html_e('#', 'flexible-invoices');
?></th>
		<th class="item-title" style="<?php 
echo \esc_html($product_name_style);
?>"><?php 
\esc_html_e('Name', 'flexible-invoices');
?></th>
		<?php 
if (!$pkwiuEmpty) {
    ?>
			<?php 
    $correction_colspan = $correction_colspan + 1;
    ?>
			<th><?php 
    \esc_html_e('SKU', 'flexible-invoices');
    ?></th>
		<?php 
}
?>
		<th><?php 
\esc_html_e('Quantity', 'flexible-invoices');
?></th>
		<th><?php 
\esc_html_e('Unit', 'flexible-invoices');
?></th>
		<th><?php 
\esc_html_e('Net price', 'flexible-invoices');
?></th>
		<?php 
if (!$discountEmpty) {
    ?>
			<?php 
    $correction_colspan = $correction_colspan + 1;
    ?>
			<th><h3><?php 
    \esc_html_e('Discount', 'flexible-invoices');
    ?></h3></th>
		<?php 
}
?>
		<th><?php 
\esc_html_e('Net amount', 'flexible-invoices');
?></th>
		<?php 
if (!$hideVat) {
    ?>
			<?php 
    $correction_colspan = $correction_colspan + 3;
    ?>
			<th><?php 
    \esc_html_e('Tax rate', 'flexible-invoices');
    ?></th>
			<th><?php 
    \esc_html_e('Tax amount', 'flexible-invoices');
    ?></th>
			<th><?php 
    \esc_html_e('Gross amount', 'flexible-invoices');
    ?></th>
		<?php 
}
?>
	</tr>
	</thead>

	<tbody>
	<tr>
		<td colspan="<?php 
echo (int) $correction_colspan;
?>"><?php 
\esc_html_e('Before correction', 'flexible-invoices');
?></td>
	</tr>
	<?php 
$index = 0;
$total_tax_amount = 0;
$total_net_price = 0;
$total_gross_price = 0;
$total_tax_net_price = array();
$total_tax_tax_amount = array();
$total_tax_gross_price = array();
?>
	<?php 
foreach ($products as $item) {
    ?>
		<?php 
    if (isset($item['before_correction']) && (int) $item['before_correction'] === 1) {
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
            if (!isset($item['sku'])) {
                echo \wordwrap(\esc_html($item['sku']), 6, "\n", \true);
            }
            ?></td>
				<?php 
        }
        ?>
				<td class="quantity number"><?php 
        echo -1 * $item['quantity'];
        ?></td>
				<td class="unit center"><?php 
        echo \esc_html($item['unit']);
        ?></td>
				<td class="net-price number"><?php 
        echo \esc_html($currency_helper->string_as_money($item['net_price']));
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
        echo \esc_html($currency_helper->string_as_money(-1 * $item['net_price_sum']));
        ?></td>
				<?php 
        if (!$hideVat) {
            ?>
					<td class="tax-rate number"><?php 
            echo \esc_html($item['vat_type_name']);
            ?></td>
					<td class="tax-amount number"><?php 
            echo \esc_html($currency_helper->string_as_money(-1 * $item['vat_sum']));
            ?></td>
					<td class="total-gross-price number"><?php 
            echo \esc_html($currency_helper->string_as_money(-1 * $item['total_price']));
            ?></td>
				<?php 
        }
        ?>

				<?php 
        $total_net_price += $item['net_price_sum'];
        $total_tax_amount += $item['vat_sum'];
        $total_gross_price += $item['total_price'];
        if (!empty($item['vat_type_name'])) {
            $total_tax_net_price[$item['vat_type_name']] = @(float) $total_tax_net_price[$item['vat_type_name']] + $item['net_price_sum'];
            $total_tax_tax_amount[$item['vat_type_name']] = @(float) $total_tax_tax_amount[$item['vat_type_name']] + $item['vat_sum'];
            $total_tax_gross_price[$item['vat_type_name']] = @(float) $total_tax_gross_price[$item['vat_type_name']] + $item['total_price'];
        }
        ?>
			</tr>
			<?php 
    }
    ?>
	<?php 
}
?>
	<tr>
		<td colspan="<?php 
echo $correction_colspan;
?>"><?php 
\esc_html_e('After correction', 'flexible-invoices');
?></td>
	</tr>
	<?php 
$index = 0;
?>
	<?php 
foreach ($products as $item) {
    ?>
		<?php 
    if (!isset($item['before_correction'])) {
        $index++;
        ?>
			<tr>
				<td class="center"><?php 
        echo $index;
        ?></td>
				<td><?php 
        echo \esc_html($item['name']);
        ?></td>
				<?php 
        if (!$pkwiuEmpty) {
            ?>
					<td><?php 
            echo $item['sku'] ?? '';
            ?></td>
				<?php 
        }
        ?>
				<td class="quantity number"><?php 
        echo $item['quantity'];
        ?></td>
				<td class="unit center"><?php 
        echo \esc_html($item['unit']);
        ?></td>
				<td class="net-price number"><?php 
        echo $currency_helper->string_as_money($item['net_price']);
        ?></td>
				<?php 
        if (!$discountEmpty) {
            ?>
					<td class="discount number"><?php 
            if (isset($item['discount'])) {
                echo $helper->discount_price($item);
            }
            ?></td>
				<?php 
        }
        ?>
				<td class="total-net-price number"><?php 
        echo $currency_helper->string_as_money($item['net_price_sum']);
        ?></td>
				<?php 
        if (!$hideVat) {
            ?>
					<td class="tax-rate number"><?php 
            echo $item['vat_type_name'];
            ?></td>
					<td class="tax-amount number"><?php 
            echo $currency_helper->string_as_money($item['vat_sum']);
            ?></td>
					<td class="total-gross-price number"><?php 
            echo $currency_helper->string_as_money($item['total_price']);
            ?></td>
				<?php 
        }
        ?>


				<?php 
        $total_net_price += $item['net_price_sum'];
        $total_tax_amount += $item['vat_sum'];
        $total_gross_price += $item['total_price'];
        if (!empty($item['vat_type_name'])) {
            $total_tax_net_price[$item['vat_type_name']] = @(float) $total_tax_net_price[$item['vat_type_name']] + $item['net_price_sum'];
            $total_tax_tax_amount[$item['vat_type_name']] = @(float) $total_tax_tax_amount[$item['vat_type_name']] + $item['vat_sum'];
            $total_tax_gross_price[$item['vat_type_name']] = @(float) $total_tax_gross_price[$item['vat_type_name']] + $item['total_price'];
        }
        ?>
			</tr>
			<?php 
    }
    ?>
	<?php 
}
?>

	</tbody>
</table>
<?php 
if ($index >= 8) {
    ?>
<pagebreak>
<?php 
}
