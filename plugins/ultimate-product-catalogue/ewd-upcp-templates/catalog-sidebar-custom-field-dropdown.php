<div class='ewd-upcp-catalog-sidebar-custom-field-div' data-custom_field_id='<?php echo esc_attr( $this->custom_field->id ); ?>'>

	<div class='ewd-upcp-catalog-sidebar-title <?php echo ( $this->get_option( 'styling-sidebar-title-collapse' ) ? 'ewd-upcp-catalog-sidebar-collapsible' : '' ); ?> <?php echo ( $this->get_option( 'styling-sidebar-start-collapsed' ) ? 'ewd-upcp-sidebar-content-hidden' : '' ); ?>'>
		<?php echo esc_html( $this->custom_field->name ); ?>
	</div>

	<select name='<?php echo esc_attr( $this->custom_field->id ); ?>'>

		<option value='all'>
			<?php _e( 'All', 'ultimate-product-catalogue' ); ?>
		</option>

		<?php foreach ( $this->sidebar_custom_fields[ $this->custom_field->id ] as $field_value => $field_count ) { ?>

			<option value='<?php echo esc_attr( $field_value ); ?>' <?php echo ( $this->is_custom_field_value_selected( $field_value ) ? 'selected' : '' ); ?>>
					
				<?php echo esc_html( $field_value ); ?> <span class='ewd-upcp-catalog-sidebar-custom-field-count'> (<?php echo esc_html( $field_count ); ?>)</span>

			</option>

		<?php } ?>

	</select>

</div>