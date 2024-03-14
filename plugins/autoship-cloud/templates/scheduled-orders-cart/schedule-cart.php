<?php
/**
 * The Dynamic Schedule Cart Template
 *
 * This template can be overridden by copying it to yourtheme/autoship-cloud/templates/scheduled-orders-cart/schedule-cart.php
*/
?>
<div class="autoship-schedule-cart">
	<?php foreach ( $cart_items_grouped_by_frequency as $group ): ?>
		<?php if ( null == $group['frequency_type'] ): ?>
			<h3>
				<?php echo sprintf( __( 'Add to %s', 'autoship' ), autoship_translate_text( 'Autoship', false ) ); ?>
				<br />
				<a href="javascript:;" onclick="autoshipScheduleCartOpenDialog(null, null, <?php echo esc_attr( json_encode( array_keys( $group['items'] ) ) ); ?>, null);"><small><?php echo sprintf( __( 'Add to %s', 'autoship' ), autoship_translate_text( 'Autoship' ) );?></small></a>
			</h3>
			<table class="autoship-schedule-cart-items">
				<tbody>
				<?php foreach ( $group['items'] as $item ): ?>
					<tr>
						<td class="image-cell"><?php echo $item['data']->get_image(); ?></td>
						<td class="name-cell"><?php echo esc_html( autoship_get_product_display_name( $item['data'] ) ); ?></td>
						<td class="quantity-cell"><?php echo esc_html( $item['quantity'] ); ?></td>
						<td class="price-cell"><?php echo wc_price( $item['line_subtotal'] ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php else: ?>
			<div class="frequency-title">
				<h3>
					<?php echo esc_html( autoship_get_frequency_display_name( $group['frequency_type'], $group['frequency'] ) ); ?>
					<br />
					<a href="javascript:;" onclick="autoshipScheduleCartOpenDialog(<?php echo esc_attr( json_encode( $group['frequency_type'] ) ); ?>, <?php echo esc_attr( json_encode( $group['frequency'] ) ); ?>, <?php echo esc_attr( json_encode( array_keys( $group['items'] ) ) ); ?>, <?php echo esc_attr( json_encode( $group['next_occurrence'] ) ); ?>);"><small><?php echo __( 'Change Schedule', 'autoship' ); ?></small></a>
				</h3>
				<div class="next-occurrence">

					<?php if ( ! empty( $group['next_occurrence'] ) ): ?>
						<?php echo __( 'Next Order:', 'autoship' ); ?>
						<a href="javascript:;" onclick="autoshipScheduleCartOpenSelectNextOccurrenceDialog(<?php echo esc_attr( json_encode( date_i18n( 'c', $group['next_occurrence'] ) ) ); ?>, <?php echo esc_attr( json_encode( $group['frequency_type'] ) ); ?>, <?php echo esc_attr( json_encode( $group['frequency'] ) ); ?>, <?php echo esc_attr( json_encode( array_keys( $group['items'] ) ) ); ?>);">
							<?php echo __( date_i18n( get_option( 'date_format' ), $group['next_occurrence'] ), 'autoship' ); ?>
						</a>
					<?php else: ?>
						<a href="javascript:;" onclick="autoshipScheduleCartOpenSelectNextOccurrenceDialog(null, <?php echo esc_attr( json_encode( $group['frequency_type'] ) ); ?>, <?php echo esc_attr( json_encode( $group['frequency'] ) ); ?>, <?php echo esc_attr( json_encode( array_keys( $group['items'] ) ) ); ?>);">
							<?php echo __( 'Schedule next order', 'autoship' ); ?>
						</a>
					<?php endif; ?>
				</div>

			</div>
			<table class="autoship-schedule-cart-items">
				<tbody>
				<?php foreach ( $group['items'] as $item ): ?>
					<tr>
						<td class="image-cell"><?php echo $item['data']->get_image(); ?></td>
						<td class="name-cell"><?php echo esc_html( autoship_get_product_display_name( $item['data'] ) ); ?></td>
						<td class="quantity-cell"><?php echo esc_html( $item['quantity'] ); ?></td>
						<td class="price-cell"><?php echo wc_price( $item['line_subtotal'] ); ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	<?php endforeach; ?>
	<h3>
		Bulk Actions
		<br />
		<a href="javascript:;" onclick="autoshipScheduleCartOpenDialog(null, null, <?php echo esc_attr( json_encode( array_keys( WC()->cart->cart_contents ) ) ); ?>, null);"><small>Schedule all items</small></a>
	</h3>
</div>
