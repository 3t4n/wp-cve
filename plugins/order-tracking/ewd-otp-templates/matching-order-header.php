<tr>

	<?php if ( in_array( 'order_number', $this->get_option( 'order-information' ) ) ) { ?>
		<th><?php echo esc_html( $this->get_label( 'label-order-number' ) ); ?>:</th>
	<?php } ?>

	<?php if ( in_array( 'order_name', $this->get_option( 'order-information' ) ) ) { ?>
		<th><?php echo esc_html( $this->get_label( 'label-order-name' ) ); ?>:</th>
	<?php } ?>

	<?php if ( in_array( 'order_notes', $this->get_option( 'order-information' ) ) ) { ?>
		<th><?php echo esc_html( $this->get_label( 'label-order-notes' ) ); ?>:</th>
	<?php } ?>

	<?php foreach ( $this->get_order_fields() as $custom_field ) { ?>

		<?php if ( $custom_field->front_end_display ) { ?>
			<th><?php echo esc_html( $custom_field->name ); ?>:</th>
		<?php } ?>

	<?php } ?>

	<?php if ( in_array( 'order_status', $this->get_option( 'order-information' ) ) ) { ?>
		<th><?php echo esc_html( $this->get_label( 'label-order-status' ) ); ?>:</th>
	<?php } ?>

	<?php if ( in_array( 'order_location', $this->get_option( 'order-information' ) ) ) { ?>
		<th><?php echo esc_html( $this->get_label( 'label-order-location' ) ); ?>:</th>
	<?php } ?>

	<?php if ( in_array( 'order_updated', $this->get_option( 'order-information' ) ) ) { ?>
		<th><?php echo esc_html( $this->get_label( 'label-order-updated' ) ); ?>:</th>
	<?php } ?>

</tr>