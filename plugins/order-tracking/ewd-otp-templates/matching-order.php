<tr class='ewd-otp-tracking-table-order' data-order_number='<?php echo esc_attr( $this->current_order->number ); ?>' data-order_email='<?php echo esc_attr( $this->current_order->email ); ?>'>

	<?php if ( in_array( 'order_number', $this->get_option( 'order-information' ) ) ) { ?>

		<td>

			<?php if ( $this->include_separate_tracking_link() ) { ?> <a href='<?php echo esc_url( add_query_arg( 'tracking_number', $this->current_order->number, $this->get_option( 'tracking-page-url' ) ) ); ?>'> <?php  } ?>
				<?php echo esc_html( $this->current_order->number ); ?>
			<?php if ( $this->include_separate_tracking_link() ) { ?> </a> <?php  } ?>

		</td>

	<?php } ?>

	<?php if ( in_array( 'order_name', $this->get_option( 'order-information' ) ) ) { ?>
		<td><?php echo esc_html( $this->current_order->name ); ?></td>
	<?php } ?>

	<?php if ( in_array( 'order_notes', $this->get_option( 'order-information' ) ) ) { ?>
		<td><?php echo esc_html( $this->current_order->notes_public ); ?></td>
	<?php } ?>

	<?php foreach ( $this->get_order_fields() as $custom_field ) { ?>

		<?php if ( $custom_field->front_end_display ) { ?>
			<td><?php echo esc_html( $this->current_order->custom_fields[ $custom_field->id ] ); ?></td>
		<?php } ?>

	<?php } ?>

	<?php if ( in_array( 'order_status', $this->get_option( 'order-information' ) ) ) { ?>
		<td><?php echo esc_html( $this->current_order->external_status ); ?></td>
	<?php } ?>

	<?php if ( in_array( 'order_location', $this->get_option( 'order-information' ) ) ) { ?>
		<td><?php echo esc_html( $this->current_order->location ); ?></td>
	<?php } ?>

	<?php if ( in_array( 'order_updated', $this->get_option( 'order-information' ) ) ) { ?>
		<td><?php echo date( $this->get_option( 'date-format' ), strtotime( $this->current_order->status_updated_fmtd ) ); ?></td>
	<?php } ?>

</tr>