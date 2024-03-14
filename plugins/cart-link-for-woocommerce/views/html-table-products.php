<?php
/**
 * @var ProductsTable   $this          .
 * @var array           $table_classes .
 * @var string          $singular      .
 * @var int             $column_count  .
 * @var CampaignProduct $default       .
 */

use IC\Plugin\CartLinkWooCommerce\Campaign\CampaignProduct;
use IC\Plugin\CartLinkWooCommerce\Campaign\Metabox\Products\ProductsTable;

?>

<table class="wp-list-table <?php echo esc_attr( implode( ' ', $table_classes ) ); ?>">
	<thead>
	<tr>
		<?php $this->print_column_headers(); ?>
	</tr>
	</thead>

	<tbody id="the-list">
	<?php $this->display_rows_or_placeholder(); ?>
	</tbody>

	<tfoot>
	<tr>
		<td colspan="<?php echo esc_attr( $column_count ); ?>">
			<a href="#0"
			   class="button js--add-product-button"><span class="dashicons dashicons-plus"></span> <?php _e( 'Add product', 'cart-link-for-woocommerce' ); ?></a>
			<a href="#0" class="button js--duplicate-selected-products-button js--action-field"
			   disabled="disabled"><span class="dashicons dashicons-admin-page"></span> <?php _e( 'Duplicate selected products', 'cart-link-for-woocommerce' ); ?></a>
			<a href="#0" class="button js--delete-selected-products-button js--action-field"
			   disabled="disabled"><span class="dashicons dashicons-trash"></span> <?php _e( 'Delete selected products', 'cart-link-for-woocommerce' ); ?></a>
		</td>
	</tr>
	</tfoot>
</table>

<script type="text/html" id="tmpl-table-row-item">
	<tr>
		<td><input class="js--cb-field js--action-field-trigger" type="checkbox"/></td>
		<td><?php echo $this->column_name( $default ); ?></td>
		<td><?php echo $this->column_qty( $default ); ?></td>
		<td><?php echo $this->column_price( $default ); ?></td>
	</tr>
</script>

<script type="text/html" id="tmpl-no-items">
	<tr class="no-items">
		<td class="colspanchange" colspan="<?php echo esc_attr( $column_count ); ?>">
			<?php $this->no_items(); ?>
		</td>
	</tr>
</script>
