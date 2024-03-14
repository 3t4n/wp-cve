<?php
/*****************************************************************

  Page template for IDX Search Widget shortcode generator form.

******************************************************************/
?>
<div class="flexmls-v2-widget-wrapper">
<?php $fields_to_catch = []; ?>
<?php foreach ( $settings_fields as $field_id => $field_attr ) : ?>
<?php if ( array_key_exists( 'disabled', $field_attr ) ) { continue; } ?>
<?php $wrapper_class = ''; ?>
<?php
	if ( array_key_exists( 'field_grouping', $field_attr ) ) {
		$wrapper_class .= ' ' . 'flexmls_connect__disable_group_' . $field_attr['field_grouping'];
	}
	if ( array_key_exists( 'after_input', $field_attr ) ) {
		$wrapper_class .= ' ' . 'flexmls-has-after-input';
	}
?>

<?php switch ( $field_attr['type'] ) : ?>
<?php case 'section-title': ?>
	<?php if ( array_key_exists( 'skip_prev_section_close', $field_attr ) && ! $field_attr['skip_prev_section_close'] ) : ?>
		</div><!-- end section -->
	<?php endif; ?>
	<div class="flexmls-section-wrapper"> <!-- start section -->
		<div class="flexmls-shortcode-section-title">
			<?php echo esc_html( $field_attr['text'] ); ?>
			<?php if ( array_key_exists( 'description', $field_attr ) ) : ?>
				<?php $this->info_icon( $field_attr['description'] ); ?>
			<?php endif; ?>
		</div>

	<?php break; ?>
<?php case 'enabler': ?>
	<div class="flexmls-admin-field-row flexmls-admin-field-enabler <?php echo esc_attr( $wrapper_class ); ?>">
		<div class="label-wrapper">
			<?php $this->label_tag( $field_id, $field_attr['label'] ); ?>
			<?php if ( array_key_exists( 'description', $field_attr ) ) : ?>
				<?php $this->info_icon( $field_attr['description'] ); ?>
			<?php endif; ?>
		</div>
		<?php $this->select_tag( array(
			'fmc_field' => $field_id,
			'collection' => $on_off_options,
			'option_value_attr' => 'value',
			'option_display_attr' => 'display_text',
			'class' => 'flexmls_connect__setting_enabler_' . $field_id
		) ); ?>
	</div>
	<?php break; ?>
<?php case 'list': ?>
	<div class="flexmls-admin-field-row flexmls-list-field <?php echo esc_attr( $wrapper_class ); ?>">
		<?php $this->label_tag( $field_id, $field_attr['label'] ); ?>

	  <div class="flexmls_connect__sortable_wrapper">
	    <input fmc-field="<?php echo esc_attr( $field_id ); ?>" fmc-type="text" type="hidden"
	      name='<?php echo $this->get_field_name( $field_id ); ?>'
	      class="flexmls_connect__list_values" value="<?php echo $this->get_field_value( $field_id ); ?>" data-choices='<?php echo json_encode( $field_attr['collection'] ); ?>'>

	    <?php $this->sortable_list( $field_attr['selected'] ); ?>

	    <div class="flexmls_connect__admin_layout_flex">
	      <select name="available_<?php echo esc_attr( $field_id ); ?>" class="flexmls_connect__available">
	        <?php foreach ( $field_attr['collection'] as $field ): ?>
	          <option value="<?php echo esc_attr( $field['value'] ); ?>"><?php echo esc_html( $field['display_text'] ); ?></option>
	        <?php endforeach; ?>
	      </select>

	      <input type="button" value="Add" title="Add this to the search" class="flexmls_connect__add_<?php echo esc_attr( $field_id ); ?> button"></input>
	      <img src="" class="flexmls_connect__bootloader" onerror="if ( typeof flexmls_connect !== 'undefined' ) { flexmls_connect.sortable_setup(this); }">
	    </div>
	  </div>
	</div>
	<?php break; ?>
<?php case 'select': ?>
	<div class="flexmls-admin-field-row flexmls-select-field <?php echo esc_attr( $wrapper_class ); ?>">
		<div class="label-wrapper">
			<?php $this->label_tag( $field_id, $field_attr['label'] ); ?>
			<?php if ( array_key_exists( 'description', $field_attr ) ) : ?>
				<?php $this->info_icon( $field_attr['description'] ); ?>
			<?php endif; ?>
		</div>
		<?php $this->select_tag( array(
			'fmc_field' => $field_id,
			'collection' => $field_attr['collection'],
			'option_value_attr' => 'value',
			'option_display_attr' => 'display_text'
		) ); ?>
	</div>
	<?php break; ?>

<?php case 'font': ?>
	<div class="flexmls-admin-field-row flexmls-font-field <?php echo esc_attr( $wrapper_class ); ?>">
		<div class="label-wrapper">
			<?php $this->label_tag( $field_id, $field_attr['label'] ); ?>
			<?php if ( array_key_exists( 'description', $field_attr ) ) : ?>
				<?php $this->info_icon( $field_attr['description'] ); ?>
			<?php endif; ?>
		</div>
		<?php $this->font_field_tag( $field_id, ['fonts' => $field_attr['collection'], 'default' => $field_attr['default'] ] ) ?>
		<img src="" class="flexmls_connect__bootloader" onerror="if ( typeof flexmls_connect !== 'undefined' ) { flexmls_connect.font_picker_setup_for_gutenberg(this); }">

	</div>
	<?php break; ?>

<?php case 'text': ?>
	<div class="flexmls-admin-field-row flexmls-text-field <?php echo esc_attr( $wrapper_class ); ?>">
		<div class="label-wrapper">
			<?php $this->label_tag( $field_id, $field_attr['label'] ); ?>
			<?php if ( array_key_exists( 'description', $field_attr ) ) : ?>
				<?php $this->info_icon( $field_attr['description'] ); ?>
			<?php endif; ?>
		</div>
		<?php $this->text_field_tag( $field_id ) ?>

		<?php if ( array_key_exists( 'after_input', $field_attr ) ) : ?>
			<span class="after-input"><?php echo esc_html( $field_attr['after_input' ] ); ?></span>
		<?php endif; ?>
	</div>
	<?php break; ?>
<?php case 'color': ?>
	<div class="flexmls-admin-field-row flexmls-color-field <?php echo esc_attr( $wrapper_class ); ?>">
		<div class="label-wrapper">
			<?php $this->label_tag( $field_id, $field_attr['label'] ); ?>
			<?php if ( array_key_exists( 'description', $field_attr ) ) : ?>
				<?php $this->info_icon( $field_attr['description'] ); ?>
			<?php endif; ?>
		</div>
		<?php $this->color_field_tag( $field_id, "#{$field_attr['default']}" ) ?>
	</div>
	<?php break; ?>
<?php case 'location': ?>
	<div class="flexmls-admin-field-row flexmls-location-field <?php echo esc_attr( $wrapper_class ); ?>">
		<div class="label-wrapper">
			<?php $this->label_tag( $field_id, $field_attr['label'] ); ?>
			<?php if ( array_key_exists( 'description', $field_attr ) ) : ?>
				<?php $this->info_icon( $field_attr['description'] ); ?>
			<?php endif; ?>
		</div>

		<select class='flexmlsAdminLocationSearch' type='hidden' multiple="true"
			id="fmc_shortcode_field_location" name="fmc_shortcode_field_location" data-portal-slug="<?= $portal_slug; ?>">
		</select>

		<?php $this->hidden_field_tag( $field_id, $field_attr ); ?>
	</div>
	<?php break; ?>
<?php case 'toggled_inputs': ?>
	<div class="flexmls-admin-field-row flexmls-toggled-inputs-field <?php echo esc_attr( $wrapper_class ); ?>" data-toggled-input-parent-name="<?php echo esc_attr( $field_attr['parent_input_name'] ); ?>">
		<div class="label-wrapper">
			<?php $this->label_tag( $field_id, $field_attr['label'] ); ?>
			<?php if ( array_key_exists( 'description', $field_attr ) ) : ?>
				<?php $this->info_icon( $field_attr['description'] ); ?>
			<?php endif; ?>
		</div>

		<div class="toggled-inner-wrapper">
			<?php foreach ( $field_attr['inputs'] as $input ) : ?>
				<?php if ( 'select' == $input['type'] ) : ?>
					<?php $this->select_tag( array(
						'fmc_field' => $field_id,
						'collection' => $input['collection'],
						'option_value_attr' => 'value',
						'option_display_attr' => 'display_text',
						'parent_input_value' => $input['parent_input_value']
					) ); ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
	<?php break; ?>
<?php case 'hidden': ?>
	<?php $this->hidden_field_tag( $field_id, $field_attr ); ?>
	<?php break; ?>
<?php endswitch; ?>
<?php $fields_to_catch []= $field_id; ?>
<?php endforeach; ?>
	</div><!-- end last section -->
	<input type='hidden' name='shortcode_fields_to_catch' value='<?php echo esc_attr( implode( ',', $fields_to_catch ) ); ?>' />
	<input type='hidden' name='widget' value='<?php echo esc_attr( $class_name ); ?>' />

	<script type='text/javascript'>
	  // set up the color picker for the search widget
	  jQuery( '.color-picker' ).wpColorPicker();
	  jQuery(".wp-picker-container").each(function(){
	    var input = jQuery(this).find(".wp-color-picker").clone();
	    var parent = jQuery(this).parent();
	    jQuery(this).remove();
	    parent.append(input);
	  });
	  jQuery('.wp-color-picker').wpColorPicker();

		if ( typeof window.flexmls_connect !== 'undefined' ) {
			jQuery( '.fmc_shortcode_window_content .flexmls-v2-widget-wrapper [fmc-field]' ).on( 'change', function () {
				if ( jQuery( '[data-toggled-input-parent-name="' + jQuery( this ).attr( 'fmc-field' ) + '"]').length > 0 ) {
					window.flexmls_connect.toggledInputsInit();
				}
			} );
			window.flexmls_connect.toggledInputsInit();

			jQuery( '.flexmls-v2-widget-wrapper [data-fonts]' ).each( function () {
				window.flexmls_connect.load_font_picker( jQuery( this ) );
			} );
		}
	</script>
</div><!-- end .flexmls-v2-widget-wrapper -->
