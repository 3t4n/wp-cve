<?php

namespace WPDeskFIVendor;

/**
 * @var array $params
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration;
$params = isset($params) ? $params : [];
$is_woocommerce_active = \defined('WC_VERSION');
/**
 * @var WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $invoice
 */
$invoice = $params['invoice'];
$show_discount = $params['show_discount'];
$items = $invoice->get_items();
$document_issuing = 'Manual Issuing Proforma and Invoices';
?>
<div class="form-wrap products_metabox">
	<table class="wp-list-table widefat fixed products">
		<thead>
		<tr>
			<th class="product-title"><?php 
\esc_html_e('Product', 'flexible-invoices');
?></th>
			<th class="sku-label"><?php 
\esc_html_e('SKU', 'flexible-invoices');
?></th>
			<th class="unit-label"><?php 
\esc_html_e('Unit', 'flexible-invoices');
?></th>
			<th class="qty-label"><?php 
\esc_html_e('Quantity', 'flexible-invoices');
?></th>
			<th class="net-price-label"><?php 
\esc_html_e('Net price', 'flexible-invoices');
?></th>
			<?php 
if ($show_discount && \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_super()) {
    ?>
				<th class="discount-label"><?php 
    \esc_html_e('Discount', 'flexible-invoices');
    ?></th>
			<?php 
}
?>
			<th class="net-price-label"><?php 
\esc_html_e('Net amount', 'flexible-invoices');
?></th>
			<th class="tax-rate-label"><?php 
\esc_html_e('Tax rate', 'flexible-invoices');
?></th>
			<th class="tax-price-label"><?php 
\esc_html_e('Tax amount', 'flexible-invoices');
?></th>
			<th class="gross-price-label"><?php 
\esc_html_e('Gross amount', 'flexible-invoices');
?></th>
			<th class="product-actions"></th>
		</tr>
		</thead>
		<?php 
$vat_types = $params['vat_types'];
?>
		<tbody class="products_container">
		<?php 
if (!empty($items)) {
    ?>
			<?php 
    foreach ($items as $index => $product) {
        ?>
				<?php 
        $item_name = $product['name'] ?? '';
        $item_sku = $product['sku'] ?? '';
        $item_unit = $product['unit'] ?? \esc_html_x('item', 'Units Of Measure For Items In Inventory', 'flexible-invoices');
        $item_qty = $product['quantity'] ?? 1;
        $item_net_price = $product['net_price'] ?? 0.0;
        $item_discount = $product['discount'] ?? 0.0;
        $item_net_price_sum = $product['net_price_sum'] ?? 0.0;
        $item_vat_sum = $product['vat_sum'] ?? 0.0;
        $item_total_price = $product['total_price'] ?? 0.0;
        ?>
				<tr class="product_row">
				<td>
						<div class="product_select_name" style="width: 90%; float: left;">
							<?php 
        if ($is_woocommerce_active) {
            ?>
							<div class="select-product">
								<select name="product[name][]" class="refresh_product wide-input">
									<option value="<?php 
            echo \esc_attr($item_name);
            ?>"><?php 
            echo \esc_html($item_name);
            ?></option>
								</select>
							</div>
							<?php 
        } else {
            ?>
								<input type="text" class="item_input_name" name="product[name][]" value="<?php 
            echo \esc_html($item_name);
            ?>">
							<?php 
        }
        ?>
						</div>
						<a style="float:right; margin-top: 5px; <?php 
        echo $is_woocommerce_active ? '' : 'display:none;';
        ?>" href="#" class="edit_item_name" title="<?php 
        \esc_attr_e('Click to enter item name manually', 'flexible-invoices');
        ?>">
							<span class="dashicons dashicons-edit"></span>
						</a>
					</td>
					<td>
						<label>
							<input
									type="text"
									name="product[sku][]"
									class="sku hs-beacon-search"
									value="<?php 
        echo \esc_attr($item_sku);
        ?>"
							/>
						</label>
					</td>
					<td>
						<label>
							<input
									type="text"
									name="product[unit][]"
									class="unit hs-beacon-search"
									value="<?php 
        echo \esc_attr($item_unit);
        ?>"
							/>
						</label>
					</td>
					<td>
						<label>
							<input
									type="text"
									name="product[quantity][]"
									value="<?php 
        echo \esc_attr($item_qty);
        ?>"
									class="quantity hs-beacon-search refresh_net_price_sum"
							/>
						</label>
					</td>
					<td>
						<label>
							<input
									type="text"
									name="product[net_price][]"
									value="<?php 
        echo \esc_attr($item_net_price);
        ?>"
									class="net_price hs-beacon-search refresh_net_price_sum"
							/>
						</label>
					</td>
					<?php 
        if ($show_discount && \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_super()) {
            ?>
						<td class="discount">
							<label>
								<input
										type="text"
										name="product[discount][]"
										class="hs-beacon-search refresh_vat_sum discount"
										value="<?php 
            echo \esc_attr($item_discount);
            ?>"
								/>
							</label>
						</td>
					<?php 
        }
        ?>
					<td>
						<label>
							<input
									type="text"
									name="product[net_price_sum][]"
									value="<?php 
        echo \esc_attr($item_net_price_sum);
        ?>"
									class="hs-beacon-search refresh_vat_sum net_price_sum"
							/>
						</label>
					</td>
					<td>
						<?php 
        $vat_type_options = array();
        ?>
						<?php 
        $selected_key = \false;
        ?>
						<?php 
        /* tax with same name and rate? */
        ?>
						<?php 
        foreach ($vat_types as $vat_key => $vat_type) {
            ?>
							<?php 
            $vat_type_options[\implode('|', $vat_type)] = $vat_type['name'];
            ?>
							<?php 
            if (!$selected_key && $vat_type['name'] === $product['vat_type_name'] && \floatval($vat_type['rate']) == \floatval($product['vat_type'])) {
                ?>
								<?php 
                $selected_key = \implode('|', $vat_type);
                ?>
							<?php 
            }
            ?>
						<?php 
        }
        ?>
						<?php 
        if (!$selected_key) {
            ?>
							<?php 
            $selected_key = '-1|' . $product['vat_type'] . '|' . $product['vat_type_name'];
            ?>
							<?php 
            $vat_type_options[$selected_key] = $product['vat_type_name'];
            ?>
						<?php 
        }
        ?>
						<label>
							<select name="product[vat_type][]" class="refresh_vat_sum vat_type">
								<?php 
        foreach ($vat_type_options as $key => $vat_type_option) {
            ?>
									<option value="<?php 
            echo \esc_attr($key);
            ?>"
											<?php 
            if ($key === $selected_key) {
                ?>selected="selected"<?php 
            }
            ?>><?php 
            echo \esc_html($vat_type_option);
            ?></option>
								<?php 
        }
        ?>
							</select>
						</label>
					</td>
					<td>
						<label>
							<input
									type="text"
									name="product[vat_sum][]"
									value="<?php 
        echo \esc_attr($item_vat_sum);
        ?>"
									class="vat_sum hs-beacon-search refresh_total_price"
							/>
						</label>
					</td>
					<td>
						<label>
							<input
									type="text"
									name="product[total_price][]"
									value="<?php 
        echo \esc_attr($item_total_price);
        ?>"
									class="total_price hs-beacon-search refresh_total"
							/>
						</label>
					</td>
					<td>
						<a class="remove_product" href="#" title="<?php 
        \esc_html_e('Delete product', 'flexible-invoices');
        ?>"><span class="dashicons dashicons-no"></span></a>
					</td>
				</tr>
			<?php 
    }
    ?>
		<?php 
}
?>

		</tbody>
	</table>

	<div class="product-actions">
		<button class="button add_product" data-type="product"><?php 
\esc_html_e('Add product', 'flexible-invoices');
?></button>
	</div>
</div>

<script id="product_prototype" type="text/template">
	<tr class="product_row">
		<td>
			<div class="product_select_name" style="width: 90%; float: left;">
				<?php 
if ($is_woocommerce_active) {
    ?>
				<div class="select-product">
					<label>
						<select name="product[name][]" class="refresh_product wide-input">
							<option value=""></option>
						</select>
					</label>
				</div>
				<?php 
} else {
    ?>
					<input type="text" class="item_input_name" name="product[name][]" value="">
				<?php 
}
?>
			</div>
			<a style="float:right; margin-top: 5px; <?php 
echo $is_woocommerce_active ? '' : 'display:none;';
?>" href="#" class="edit_item_name" title="<?php 
\esc_html_e('Click to enter item name manually', 'flexible-invoices');
?>">
				<span class="dashicons dashicons-edit"></span>
			</a>
		</td>
		<td>
			<label>
				<input

						class="hs-beacon-search"
						type="text"
						name="product[sku][]"
						value=""
				/>
			</label>
		</td>
		<td>
			<label>
				<input
						class="hs-beacon-search"
						type="text"
						name="product[unit][]"
						value="<?php 
echo \esc_attr_x('item', 'Units Of Measure For Items In Inventory', 'flexible-invoices');
?>"
				/>
			</label>
		</td>
		<td>
			<label>
				<input

						name="product[quantity][]"
						type="text"
						value="1"
						class="refresh_net_price_sum hs-beacon-search"
				/>
			</label>
		</td>
		<td>
			<label>
				<input

						type="text"
						name="product[net_price][]"
						value="0.0"
						class="hs-beacon-search refresh_net_price_sum"
				/>
			</label>
		</td>
		<?php 
if ($show_discount && \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\InvoicesIntegration::is_super()) {
    ?>
			<td class="discount">
				<label>
					<input

							class="hs-beacon-search refresh_vat_sum"
							type="text"
							name="product[discount][]"
							value="0.0"
					/>
				</label>
			</td>
		<?php 
}
?>
		<td>
			<label>
				<input

						type="text"
						name="product[net_price_sum][]"
						value="0.0"
						class="hs-beacon-search refresh_vat_sum"
				/>
			</label>
		</td>
		<td>
			<label>
				<select
						name="product[vat_type][]"
						class="refresh_vat_sum"

						class="hs-beacon-search"
				>
					<?php 
foreach ($vat_types as $index => $vatType) {
    ?>
						<option value="<?php 
    echo \implode('|', $vatType);
    ?>"><?php 
    echo $vatType['name'];
    ?></option>
					<?php 
}
?>
				</select>
			</label>
		</td>
		<td><label>
				<input

						type="text"
						name="product[vat_sum][]"
						value="0.0"
						class="hs-beacon-search refresh_total_price"
				/>
			</label></td>
		<td><label>
				<input

						type="text"
						name="product[total_price][]"
						value="0.0"
						class="hs-beacon-search refresh_total"
				/>
			</label></td>
		<td>
			<a class="remove_product" href="#" title="<?php 
\esc_html_e('Delete product', 'flexible-invoices');
?>">
				<span class="dashicons dashicons-no"></span>
			</a>
		</td>
	</tr>
</script>
<?php 
