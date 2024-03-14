<?php
/**
 * Shows an Invoice item
 *
 * @package EverAccounting\Admin
 * @var Invoice      $invoice The item being displayed
 * @var Document_Item $item    The item being displayed
 * @var int          $item_id The id of the item being displayed
 */

use EverAccounting\Models\Document_Item;
use EverAccounting\Models\Invoice;

defined( 'ABSPATH' ) || exit;
?>
<tr class="ea-document__line" data-item_id="<?php echo esc_attr( $item_id ); ?>">
	<td class="ea-document__line-actions" width="1%">
		<a class="save-line tips" href="#" data-tip="<?php esc_attr_e( 'Save item', 'wp-ever-accounting' ); ?>"><span class="dashicons dashicons-yes">&nbsp;</span></a>
		<a class="edit-line tips" href="#" data-tip="<?php esc_attr_e( 'Edit item', 'wp-ever-accounting' ); ?>"><span class="dashicons dashicons-edit">&nbsp;</span></a>
		<a class="delete-line tips" href="#" data-tip="<?php esc_attr_e( 'Delete item', 'wp-ever-accounting' ); ?>"><span class="dashicons dashicons-no">&nbsp;</span></a>
	</td>

	<td class="ea-document__line-name" colspan="2">
		<input type="hidden" class="line_id" name="items[<?php echo esc_attr( $item_id ); ?>][line_id]" value="<?php echo esc_attr( $item->get_id() ); ?>"/>
		<input type="hidden" class="line_item_id" name="items[<?php echo esc_attr( $item_id ); ?>][item_id]" value="<?php echo esc_attr( $item->get_item_id() ); ?>"/>
		<input type="hidden" class="line_item_currency" name="items[<?php echo esc_attr( $item_id ); ?>][currency_code]" value="<?php echo esc_attr( $invoice->get_currency_code() ); ?>"/>
		<div class="view">
			<?php echo esc_html( $item->get_item_name( 'view' ) ); ?>
		</div>
		<div class="edit" style="display: none;">
			<input type="text" class="line_item_name" name="items[<?php echo esc_attr( $item_id ); ?>][item_name]" value="<?php echo esc_attr( $item->get_item_name() ); ?>"/>
		</div>
	</td>
	<?php do_action( 'eaccounting_invoice_item_values', $item_id, $item, $invoice ); ?>

	<td class="ea-document__line-price" width="1%" data-value="<?php echo esc_attr( $item->get_price() ); ?>">
		<div class="view">
			<?php echo esc_html( eaccounting_price( $item->get_price(), $invoice->get_currency_code() ) ); ?>
		</div>
		<div class="edit" style="display: none;">
			<input type="number" step="0.0001" min="0" class="line_item_price" name="items[<?php echo esc_attr( $item_id ); ?>][price]" value="<?php echo esc_attr( $item->get_price() ); ?>"/>
		</div>
	</td>

	<td class="ea-document__line-quantity" width="1%" data-value="">
		<div class="view">
			<?php echo '<small class="times">&times;</small> ' . esc_html( $item->get_quantity() ); ?>
		</div>
		<div class="edit" style="display: none;">
			<input type="number" step="0.01" min="1"  autocomplete="off" name="items[<?php echo esc_attr( $item_id ); ?>][quantity]" placeholder="0" value="<?php echo esc_attr( $item->get_quantity() ); ?>" size="4" class="line_item_quantity"/>
		</div>
	</td>
	<?php if ( eaccounting_tax_enabled() ) : ?>
		<td class="ea-document__line-tax" width="1%">
			<div class="view">
				<abbr title="<?php echo esc_html( eaccounting_price( $item->get_tax(), $invoice->get_currency_code() ) ); ?>"><?php echo esc_html( number_format_i18n( $item->get_tax_rate(), 4 ) ); ?><small>%</small></abbr>
			</div>
			<div class="edit" style="display: none;">
				<input type="number" step="0.0001" min="0" max="1000" class="line_item_tax" name="items[<?php echo esc_attr( $item_id ); ?>][tax_rate]" value="<?php echo esc_attr( $item->get_tax_rate() ); ?>">
			</div>
		</td>
	<?php endif; ?>

	<td class="ea-document__line-subtotal" width="1%">
		<div class="view">
			<span class="line_item_subtotal"><?php echo esc_html( eaccounting_format_price( $item->get_subtotal(), $invoice->get_currency_code() ) ); ?></span>
		</div>
	</td>

</tr>
