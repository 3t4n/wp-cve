<?php
/**
 * Add Account Modal.
 *
 * @since       1.0.2
 * @subpackage  Admin/Js Templates
 * @package     EverAccounting
 */

defined( 'ABSPATH' ) || exit();
?>
<script type="text/template" id="modal-add-line-item" data-title="<?php esc_html_e( 'Add Line Item', 'wp-ever-accounting' ); ?>">
	<form action="" method="post">
		<table class="widefat">
			<thead>
			<tr>
				<th style="width: 90%;"><?php esc_html_e( 'Item', 'wp-ever-accounting' ); ?></th>
				<th style="width: 10%;"><?php esc_html_e( 'Quantity', 'wp-ever-accounting' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td style="width: 90%;">
					<?php
					eaccounting_item_dropdown(
						array(
							'wrapper_class' => 'ea-col-9',
							'name'          => 'line_items[1][item_id]',
							'value'         => '',
							'placeholder'   => __( 'Select Item', 'wp-ever-accounting' ),
							'required'      => false,
							'ajax'          => true,
							'creatable'     => true,
						)
					);
					?>
				</td>
				<td style="width: 10%;">
					<?php
					eaccounting_text_input(
						array(
							'name'     => 'line_items[1][quantity]',
							'value'    => '1',
							'default'  => '1',
							'required' => false,
						)
					);
					?>
				</td>
			</tr>
			</tbody>
		</table>
	</form>
</script>
