<?php
/**
 * EverAccounting Form Functions
 *
 * General form functions available on both the front-end and admin.
 *
 * @since   1.0.2
 * @package EverAccounting
 */

defined( 'ABSPATH' ) || exit();

/**
 * Return the html selected attribute if stringfied $value is found in array of stringified $options
 * or if stringified $value is the same as scalar stringified $options.
 *
 * @param string|int       $value Value to find within options.
 * @param string|int|array $options Options to go through when looking for value.
 *
 * @since 1.0.2
 *
 * @return string
 */
function eaccounting_selected( $value, $options ) {
	if ( is_array( $options ) ) {
		$options = array_map( 'strval', $options );

		return selected( in_array( (string) $value, $options, true ), true, false );
	}

	return selected( $value, $options, false );
}

/**
 * Display help tip.
 *
 * @param string $tip Help tip text.
 * @param bool   $allow_html Allow sanitized HTML if true or escape.
 *
 * @since  1.0.2
 * @return string
 */
function eaccounting_help_tip( $tip, $allow_html = false ) {
	if ( $allow_html ) {
		$tip = eaccounting_sanitize_tooltip( $tip );
	} else {
		$tip = esc_attr( $tip );
	}

	return '<span class="ea-help-tip" title="' . wp_kses_post( $tip ) . '"></span>';
}

/**
 * Output a hidden input box.
 *
 * @param array|string $field Field data.
 * @param mixed        ...$args Optional further parameters.
 *
 * @since 1.0.2
 */
function eaccounting_hidden_input( $field, ...$args ) {
	if ( is_string( $field ) ) {
		$field = array(
			'name'  => $field,
			'value' => $args[0],
		);
	}
	$field['value'] = isset( $field['value'] ) ? $field['value'] : '';
	$field['class'] = isset( $field['class'] ) ? $field['class'] : '';
	$field['id']    = empty( $field['id'] ) ? $field['name'] : $field['id'];

	echo '<input type="hidden" class="' . esc_attr( $field['class'] ) . '" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" /> ';
}

/**
 * Output a text input box.
 *
 * @param array $field Field data.
 *
 * @since 1.0.2
 */
function eaccounting_text_input( $field = array() ) {
	$field = (array) wp_parse_args(
		$field,
		array(
			'label'         => '',
			'class'         => 'short',
			'style'         => '',
			'wrapper_class' => '',
			'default'       => '',
			'value'         => '',
			'name'          => '',
			'placeholder'   => '',
			'type'          => 'text',
			'data_type'     => '',
			'after'         => '',
			'tooltip'       => '',
			'desc'          => '',
			'required'      => false,
			'disabled'      => false,
			'readonly'      => false,
			'attr'          => array(),
		)
	);

	$field['id']               = empty( $field['id'] ) ? $field['name'] : $field['id'];
	$field['value']            = ! isset( $field['value'] ) ? $field['default'] : $field['value'];
	$field['attr']['required'] = ( true === $field['required'] ) ? ' required ' : '';
	$field['attr']['readonly'] = ( true === $field['readonly'] ) ? ' readonly ' : '';
	$field['attr']['disabled'] = ( true === $field['disabled'] ) ? ' disabled ' : '';
	$field['wrapper_class']   .= ( true === $field['required'] ) ? ' required ' : '';
	$data_type                 = empty( $field['data_type'] ) ? '' : $field['data_type'];

	switch ( $data_type ) {
		case 'price':
			$field['class'] .= ' ea-input-price';
			break;
		case 'decimal':
			$field['class'] .= ' ea-input-decimal';
			break;
		case 'date':
			$field['class'] .= ' ea-input-date';
			break;
		case 'color':
			$field['class']         .= ' ea-input-color';
			$field['wrapper_class'] .= ' ea-color-field';
			break;
		case 'url':
			$field['class'] .= ' ea-input-url';
			$field['value']  = esc_url( $field['value'] );
			break;
		default:
			break;
	}

	// Custom attribute handling.
	$attributes = eaccounting_implode_html_attributes( $field['attr'] );
	$tooltip    = ! empty( $field['tooltip'] ) ? eaccounting_help_tip( $field['tooltip'] ) : '';
	$desc       = ! empty( $field['desc'] ) ? sprintf( '<span class="desc">%s</span>', wp_kses_post( $field['desc'] ) ) : '';

	if ( ! empty( $field['label'] ) ) {
		echo sprintf(
			'<div class="ea-form-field %s_field %s"><label class="ea-label" for="%s">%s %s</label>',
			esc_attr( $field['id'] ),
			esc_attr( $field['wrapper_class'] ),
			esc_attr( $field['id'] ),
			wp_kses_post( $field['label'] ),
			wp_kses_post( $tooltip )
		);
	}

	if ( 'color' === $field['data_type'] ) {
		echo sprintf( '<span class="colorpickpreview" style="background: %s">&nbsp;</span>', esc_attr( $field['value'] ) );
	}

	echo sprintf(
		'<input type="%s" class="ea-input-control %s" style="%s" name="%s" id="%s" value="%s" placeholder="%s" %s/>',
		esc_attr( $field['type'] ),
		esc_attr( $field['class'] ),
		esc_attr( $field['style'] ),
		esc_attr( $field['name'] ),
		esc_attr( $field['id'] ),
		esc_attr( $field['value'] ),
		esc_attr( $field['placeholder'] ),
		wp_kses_post( $attributes )
	);
	if ( 'color' === $field['data_type'] ) {
		echo sprintf( '<div id="colorPickerDiv_%s" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>', esc_attr( $field['id'] ) );
	}
	if ( ! empty( $field['label'] ) ) {
		echo wp_kses_post( $desc );

		echo '</div>';
	}
}

/**
 * Output a text input box.
 *
 * @param array $field Field data.
 *
 * @since 1.0.2
 */
function eaccounting_textarea( $field ) {
	$field                     = (array) wp_parse_args(
		$field,
		array(
			'label'         => '',
			'class'         => 'short',
			'style'         => '',
			'wrapper_class' => '',
			'default'       => '',
			'value'         => '',
			'name'          => '',
			'placeholder'   => '',
			'rows'          => 2,
			'cols'          => 20,
			'tooltip'       => '',
			'desc'          => '',
			'required'      => false,
			'disabled'      => false,
			'readonly'      => false,
			'attr'          => array(),
		)
	);
	$field['id']               = empty( $field['id'] ) ? $field['name'] : $field['id'];
	$field['value']            = empty( $field['value'] ) ? $field['default'] : $field['value'];
	$field['attr']['required'] = ( true === $field['required'] ) ? ' required ' : '';
	$field['attr']['readonly'] = ( true === $field['readonly'] ) ? ' readonly ' : '';
	$field['attr']['disabled'] = ( true === $field['disabled'] ) ? ' disabled ' : '';
	$field['attr']['rows']     = $field['rows'];
	$field['attr']['cols']     = $field['cols'];
	$field['wrapper_class']   .= ( true === $field['required'] ) ? ' required ' : '';

	// Custom attribute handling.
	$attributes = eaccounting_implode_html_attributes( $field['attr'] );
	$tooltip    = ! empty( $field['tooltip'] ) ? eaccounting_help_tip( $field['tooltip'] ) : '';
	$desc       = ! empty( $field['desc'] ) ? sprintf( '<span class="desc">%s</span>', wp_kses_post( $field['desc'] ) ) : '';

	echo sprintf(
		'<div class="ea-form-field %s_field %s"><label class="ea-label" for="%s">%s %s</label>',
		esc_attr( $field['id'] ),
		esc_attr( $field['wrapper_class'] ),
		esc_attr( $field['id'] ),
		wp_kses_post( $field['label'] ),
		wp_kses_post( $tooltip )
	);

	echo sprintf(
		'<textarea class="ea-input-control %s" style="%s" name="%s" id="%s" placeholder="%s" %s>%s</textarea>',
		esc_attr( $field['class'] ),
		esc_attr( $field['style'] ),
		esc_attr( $field['name'] ),
		esc_attr( $field['id'] ),
		esc_attr( $field['placeholder'] ),
		wp_kses_post( $attributes ),
		esc_attr( $field['value'] )
	);
	echo wp_kses_post( $desc );

	echo '</div>';

}


/**
 * Output a radio input box.
 *
 * @param array $field Field data.
 *
 * @since 1.0.2
 */
function eaccounting_wp_radio( $field ) {
	$field = (array) wp_parse_args(
		$field,
		array(
			'label'         => '',
			'class'         => '',
			'style'         => '',
			'wrapper_class' => '',
			'default'       => '',
			'value'         => '',
			'name'          => '',
			'tooltip'       => '',
			'desc'          => '',
			'options'       => array(),
			'attr'          => array(),
		)
	);

	$field['id']    = empty( $field['id'] ) ? $field['name'] : $field['id'];
	$field['value'] = empty( $field['value'] ) ? $field['default'] : $field['value'];

	// Custom attribute handling.
	$attributes = eaccounting_implode_html_attributes( $field['attr'] );
	$tooltip    = ! empty( $field['tooltip'] ) ? eaccounting_help_tip( $field['tooltip'] ) : '';
	$desc       = ! empty( $field['desc'] ) ? sprintf( '<span class="desc">%s</span>', wp_kses_post( $field['desc'] ) ) : '';

	echo sprintf(
		'<div class="ea-form-field %s_field %s"><label class="ea-label" for="%s">%s %s</label>',
		esc_attr( $field['id'] ),
		esc_attr( $field['wrapper_class'] ),
		esc_attr( $field['id'] ),
		wp_kses_post( $field['label'] ),
		wp_kses_post( $tooltip )
	);

	echo '<ul class="ea-radios">';

	foreach ( $field['options'] as $key => $value ) {
		echo sprintf(
			'<li><label><input type="radio" name="%s" value="%s" class="%s" style="%s" %s %s/>%s</label></li>',
			esc_attr( $field['name'] ),
			esc_attr( $key ),
			esc_attr( $field['class'] ),
			esc_attr( $field['style'] ),
			esc_html( $value ),
			wp_kses_post( $attributes ),
			checked( esc_attr( $field['value'] ), esc_attr( $key ), false )
		);
	}

	echo '</ul>';

	echo wp_kses_post( $desc );

	echo '</fieldset>';
}


/**
 * Output a checkbox input box.
 *
 * @param array $field Field data.
 *
 * @since 1.0.2
 */
function eaccounting_checkbox( $field ) {
	$field = (array) wp_parse_args(
		$field,
		array(
			'label'         => '',
			'class'         => '',
			'style'         => '',
			'wrapper_class' => '',
			'default'       => '',
			'cbvalue'       => 'yes',
			'value'         => '',
			'name'          => '',
			'tooltip'       => '',
			'desc'          => '',
			'attr'          => array(),
		)
	);

	$field['id']    = empty( $field['id'] ) ? $field['name'] : $field['id'];
	$field['value'] = empty( $field['value'] ) ? $field['default'] : $field['value'];

	// Custom attribute handling.
	$attributes = eaccounting_implode_html_attributes( $field['attr'] );
	$tooltip    = ! empty( $field['tooltip'] ) ? eaccounting_help_tip( $field['tooltip'] ) : '';
	$desc       = ! empty( $field['desc'] ) ? sprintf( '<span class="desc">%s</span>', wp_kses_post( $field['desc'] ) ) : '';

	echo sprintf(
		'<div class="ea-form-field %s_field %s"><label class="ea-label" for="%s">%s %s</label>',
		esc_attr( $field['id'] ),
		esc_attr( $field['wrapper_class'] ),
		esc_attr( $field['id'] ),
		wp_kses_post( $field['label'] ),
		wp_kses_post( $tooltip )
	);

	echo sprintf(
		'<input type="checkbox" class="%s" style="%s" name="%s" id="%s" value="%s" %s %s/>',
		esc_attr( $field['class'] ),
		esc_attr( $field['style'] ),
		esc_attr( $field['name'] ),
		esc_attr( $field['id'] ),
		esc_attr( $field['cbvalue'] ),
		wp_kses_post( $attributes ),
		checked( $field['value'], $field['cbvalue'], false )
	);

	echo wp_kses_post( $desc );

	echo '</div>';
}


/**
 * Output a select input box.
 *
 * @param array $field Data about the field to render.
 *
 * @since 1.0.2
 */
function eaccounting_select( $field ) {
	$field                        = (array) wp_parse_args(
		$field,
		array(
			'label'         => '',
			'class'         => '',
			'style'         => '',
			'wrapper_class' => '',
			'default'       => '',
			'value'         => '',
			'name'          => '',
			'placeholder'   => '',
			'options'       => array(),
			'multiple'      => false,
			'tooltip'       => '',
			'desc'          => '',
			'required'      => false,
			'disabled'      => false,
			'readonly'      => false,
			'attr'          => array(),
		)
	);
	static $instance              = 1;
	$field['id']                  = empty( $field['id'] ) ? $field['name'] . '-' . $instance : $field['id'];
	$field['value']               = empty( $field['value'] ) ? $field['default'] : $field['value'];
	$field['wrapper_class']      .= ( true === $field['required'] ) ? ' required ' : '';
	$field['attr']['required']    = ( true === $field['required'] ) ? ' required ' : '';
	$field['attr']['readonly']    = ( true === $field['readonly'] ) ? ' readonly ' : '';
	$field['attr']['disabled']    = ( true === $field['disabled'] ) ? ' disabled ' : '';
	$field['attr']['multiple']    = ( true === $field['multiple'] ) ? ' multiple ' : '';
	$field['attr']['placeholder'] = $field['placeholder'];
	// Custom attribute handling.
	$attributes = eaccounting_implode_html_attributes( $field['attr'] );
	$tooltip    = ! empty( $field['tooltip'] ) ? eaccounting_help_tip( $field['tooltip'] ) : '';
	$desc       = ! empty( $field['desc'] ) ? sprintf( '<span class="desc">%s</span>', wp_kses_post( $field['desc'] ) ) : '';
	if ( ! empty( $field['label'] ) ) {
		echo sprintf(
			'<div class="ea-form-field %s_field %s"><label class="ea-label" for="%s">%s %s</label>',
			esc_attr( $field['id'] ),
			esc_attr( $field['wrapper_class'] ),
			esc_attr( $field['id'] ),
			wp_kses_post( $field['label'] ),
			wp_kses_post( $tooltip )
		);
	}
	echo sprintf(
		'<select class="ea-input-control select %s" style="%s" name="%s" id="%s" %s>',
		esc_attr( $field['class'] ),
		esc_attr( $field['style'] ),
		esc_attr( $field['name'] ),
		esc_attr( $field['id'] ),
		wp_kses_post( $attributes )
	);
	foreach ( $field['options'] as $key => $value ) {
		echo sprintf( '<option value="%s" %s>%s</option>', esc_attr( $key ), wp_kses_post( eaccounting_selected( $key, $field['value'] ) ), esc_html( $value ) );
	}
	echo '</select>';

	if ( ! empty( $field['label'] ) ) {
		echo wp_kses_post( $desc );

		echo '</div>';
	}
	++ $instance;
}

/**
 * File input field.
 *
 * @param array $field Field data.
 *
 * @since 1.1.0
 */
function eaccounting_file_input( $field ) {
	$field          = (array) wp_parse_args(
		$field,
		array(
			'label'         => '',
			'wrapper_class' => '',
			'value'         => false,
			'name'          => '',
			'desc'          => '',
			'allowed-types' => 'image',
			'attr'          => array(),
		)
	);
	$field['id']    = empty( $field['id'] ) ? $field['name'] : $field['id'];
	$field['value'] = ! isset( $field['value'] ) ? '' : $field['value'];
	$tooltip        = ! empty( $field['tooltip'] ) ? eaccounting_help_tip( $field['tooltip'] ) : '';
	$desc           = ! empty( $field['desc'] ) ? sprintf( '<span class="desc">%s</span>', wp_kses_post( $field['desc'] ) ) : '';
	$attachment     = new stdClass();
	if ( ! empty( $field['value'] ) && 'attachment' === get_post_type( $field['value'] ) ) {
		$attachment = get_post( $field['value'] );
	}

	$icon = isset( $attachment->ID ) && in_array( $attachment->post_mime_type, array( 'image/jpeg', 'image/png' ), true ) ? false : true;
	$src  = ! isset( $attachment->ID ) ? '' : wp_get_attachment_image_url( $attachment->ID, 'thumbnail', $icon );
	$link = ! isset( $attachment->ID ) ? '' : wp_get_attachment_url( $attachment->ID );
	$name = ! isset( $attachment->post_title ) ? '' : $attachment->post_title;
	$id   = ! isset( $attachment->ID ) ? '' : $attachment->ID;
	if ( ! empty( $field['label'] ) ) {
		echo sprintf(
			'<div class="ea-form-field ea-file-field %s_field %s"><label class="ea-label" for="%s">%s %s</label>',
			esc_attr( $field['id'] ),
			esc_attr( $field['wrapper_class'] ),
			esc_attr( $field['id'] ),
			wp_kses_post( $field['label'] ),
			wp_kses_post( $tooltip )
		);
	}
	?>
	<div class="ea-attachment <?php echo ! empty( $id ) ? 'has--image' : ''; ?>">
		<div class="ea-attachment__preview">
			<a class="ea-attachment__link" href="<?php echo esc_attr( $link ); ?>">
				<img class="ea-attachment__image" src="<?php echo esc_attr( $src ); ?>" alt="<?php echo esc_attr( $name ); ?>">
			</a>
		</div>
		<button type="button" class="button-link ea-attachment__remove"><?php echo esc_html__( 'Remove', 'wp-ever-accounting' ); ?></button>
		<button type="button" class="button-secondary ea-attachment__upload" data-allowed-types="<?php echo esc_js( $field['allowed-types'] ); ?>">
			<?php echo esc_html__( 'Upload', 'wp-ever-accounting' ); ?>
		</button>
		<?php
		echo sprintf(
			'<input type="hidden" name="%s" class="ea-attachment__input" id="%s" value="%s"/>',
			esc_attr( $field['name'] ),
			esc_attr( $field['id'] ),
			absint( $id )
		);
		?>
	</div>
	<?php

	if ( ! empty( $field['label'] ) ) {
		echo wp_kses_post( $desc );
		echo '</div>';
	}
}

/**
 * Output a toggle field
 *
 * @param array $field Data about the field to render.
 *
 * @since 1.0.2
 */
function eaccounting_toggle( $field ) {
	$field = (array) wp_parse_args(
		$field,
		array(
			'label'         => '',
			'class'         => '',
			'style'         => '',
			'wrapper_class' => '',
			'default'       => '',
			'value'         => '',
			'name'          => '',
			'cbvalue'       => '1',
			'options'       => array(),
			'multiple'      => false,
			'naked'         => false,
			'tooltip'       => '',
			'desc'          => '',
			'required'      => false,
			'disabled'      => false,
			'readonly'      => false,
			'attr'          => array(),
		)
	);

	$field['id']               = empty( $field['id'] ) ? $field['name'] : $field['id'];
	$field['value']            = empty( $field['value'] ) ? $field['default'] : $field['value'];
	$field['attr']['readonly'] = ( true === $field['readonly'] ) ? ' readonly ' : '';
	$field['attr']['disabled'] = ( true === $field['disabled'] ) ? ' disabled ' : '';

	// Custom attribute handling.
	$attributes = eaccounting_implode_html_attributes( $field['attr'] );
	$tooltip    = ! empty( $field['tooltip'] ) ? eaccounting_help_tip( $field['tooltip'] ) : '';
	$desc       = ! empty( $field['desc'] ) ? sprintf( '<span class="desc">%s</span>', wp_kses_post( $field['desc'] ) ) : '';

	if ( ! $field['naked'] ) {
		echo sprintf(
			'<div class="ea-form-field %s_field %s"><label class="ea-label" for="%s">%s</label>%s',
			esc_attr( $field['id'] ),
			esc_attr( $field['wrapper_class'] ),
			esc_attr( $field['id'] ),
			wp_kses_post( $field['label'] ),
			wp_kses_post( $tooltip )
		);
	}

	echo sprintf(
		'<label class="ea-toggle"><input type="checkbox" name="%s" id="%s" class="%s"  style="%s" value="%s" %s %s><span data-label-off="%s" data-label-on="%s" class="ea-toggle-slider"></span></label>',
		esc_attr( $field['name'] ),
		esc_attr( $field['id'] ),
		esc_attr( $field['class'] ),
		esc_attr( $field['style'] ),
		esc_attr( $field['cbvalue'] ),
		wp_kses_post( $attributes ),
		checked( $field['value'], $field['cbvalue'], false ),
		esc_html__( 'No', 'wp-ever-accounting' ),
		esc_html__( 'Yes', 'wp-ever-accounting' )
	);
	if ( ! $field['naked'] ) {
		echo wp_kses_post( $desc );

		echo '</div>';
	}

}

/**
 * Select field wrapper for ajax select 2 and new item creatable.
 *
 * @param array $field field properties.
 *
 * @since 1.0.2
 */
function eaccounting_select2( $field ) {
	$field           = (array) wp_parse_args(
		$field,
		array(
			'class'        => '',
			'map'          => 'return {text: option.name, id:option.id, item:option}',
			'add_text'     => __( 'Add New', 'wp-ever-accounting' ),
			'ajax_action'  => false,
			'nonce_action' => 'ea_get_items',
			'modal_id'     => false,
			'creatable'    => false,
			'attr'         => array(),
		)
	);
	$field['class'] .= ' ea-select2 ';

	if ( ! empty( $field['ajax_action'] ) ) {
		$field['attr']['data-url']         = eaccounting()->ajax_url();
		$field['attr']['data-ajax_action'] = eaccounting_clean( $field['ajax_action'] );
		$field['attr']['data-nonce']       = wp_create_nonce( $field['nonce_action'] );
		$field['attr']['data-map']         = esc_js( $field['map'] );
	}

	if ( $field['creatable'] && ! empty( $field['modal_id'] ) ) {
		$field['attr']['data-modal_id'] = esc_attr( $field['modal_id'] );
		$field['attr']['data-add_text'] = esc_attr( $field['add_text'] );
	}

	if ( ! empty( $field['placeholder'] ) ) {
		$field['options'] = array( '' => esc_html( $field['placeholder'] ) ) + $field['options'];
	}
	if ( ! empty( $field['clearable'] ) ) {
		$field['attr']['data-allow-clear'] = true;
	}
	eaccounting_select( $field );
}

/**
 * Get customer dropdown.
 *
 * @param array $field field properties.
 *
 * @since 1.1.0
 */
function eaccounting_customer_dropdown( $field ) {
	$field    = wp_parse_args(
		$field,
		array(
			'value'       => '',
			'ajax_action' => '',
			'modal_id'    => '',
			'creatable'   => true,
		)
	);
	$include  = ! empty( $field['value'] ) ? wp_parse_id_list( $field['value'] ) : array();
	$contacts = eaccounting_get_customers(
		array(
			'include' => $include,
			'fields'  => array( 'id', 'name' ),
			'return'  => 'raw',
		)
	);
	$field    = wp_parse_args(
		array(
			'value'        => $include,
			'options'      => wp_list_pluck( $contacts, 'name', 'id' ),
			'ajax_action'  => 'eaccounting_get_customers',
			'nonce_action' => 'ea_get_customers',
			'modal_id'     => '#ea-modal-add-customer',
		),
		$field
	);
	eaccounting_select2( apply_filters( 'eaccounting_customer_dropdown', $field ) );
}

/**
 * Get vendor dropdown.
 *
 * @param array $field field properties.
 *
 * @since 1.1.0
 */
function eaccounting_vendor_dropdown( $field ) {
	$field    = wp_parse_args(
		$field,
		array(
			'value'       => '',
			'ajax_action' => '',
			'modal_id'    => '',
			'creatable'   => true,
		)
	);
	$include  = ! empty( $field['value'] ) ? wp_parse_id_list( $field['value'] ) : array();
	$contacts = eaccounting_get_vendors(
		array(
			'include' => $include,
			'fields'  => array( 'id', 'name' ),
			'return'  => 'raw',
		)
	);

	$field = wp_parse_args(
		array(
			'value'        => $include,
			'options'      => wp_list_pluck( $contacts, 'name', 'id' ),
			'ajax_action'  => 'eaccounting_get_vendors',
			'nonce_action' => 'ea_get_vendors',
			'modal_id'     => '#ea-modal-add-vendor',
		),
		$field
	);
	eaccounting_select2( apply_filters( 'eaccounting_vendor_dropdown', $field ) );
}

/***
 * Dropdown field for selecting contacts.
 *
 * @param array $field field properties.
 *
 * @since 1.0.2
 */
function eaccounting_contact_dropdown( $field ) {
	$type       = ! empty( $field['type'] ) && array_key_exists( $field['type'], eaccounting_get_contact_types() ) ? eaccounting_clean( $field['type'] ) : false;
	$value      = ! empty( $field['value'] ) ? eaccounting_clean( $field['value'] ) : '';
	$query_args = array( 'return' => 'raw' );
	if ( ! empty( $value ) ) {
		$query_args['include'] = $value;
	}

	$function = 'customer' === $type ? 'eaccounting_get_customers' : 'eaccounting_get_vendors';

	$contacts = call_user_func_array( $function, array( $query_args ) );

	$field = wp_parse_args(
		array(
			'value'    => $value ? absint( $value ) : '',
			'options'  => wp_list_pluck( $contacts, 'name', 'id' ),
			'type'     => $type,
			'ajax'     => true,
			'template' => 'add-' . $type,
		),
		$field
	);
	eaccounting_select2( apply_filters( 'eaccounting_contact_dropdown', $field ) );
}

/***
 * Dropdown field for selecting account.
 *
 * @param array $field field properties.
 *
 * @since 1.0.2
 */
function eaccounting_account_dropdown( $field ) {
	$field   = wp_parse_args(
		$field,
		array(
			'value'       => '',
			'ajax_action' => '',
			'modal_id'    => '',
			'creatable'   => true,
		)
	);
	$include = ! empty( $field['value'] ) ? wp_parse_id_list( $field['value'] ) : array();
	$result  = eaccounting_get_accounts(
		array(
			'include' => $include,
			'fields'  => array( 'id', 'name', 'currency_code' ),
			'return'  => 'raw',
		)
	);

	$options = array();
	foreach ( $result as $item ) {
		$options[ $item->id ] = $item->name . '(' . $item->currency_code . ')';
	}

	$field = wp_parse_args(
		array(
			'value'        => $include,
			'options'      => $options,
			'placeholder'  => __( 'Select Account', 'wp-ever-accounting' ),
			'map'          => 'return {text: option.name + " (" + option.currency_code +")"  , id:option.id}',
			'ajax_action'  => 'eaccounting_get_accounts',
			'nonce_action' => 'ea_get_accounts',
			'modal_id'     => '#ea-modal-add-account',
		),
		$field
	);

	eaccounting_select2( apply_filters( 'eaccounting_account_dropdown', $field ) );
}

/***
 * Dropdown field for selecting category.
 *
 * @param array $field field properties.
 *
 * @since 1.0.2
 */
function eaccounting_category_dropdown( $field ) {
	$field       = wp_parse_args(
		$field,
		array(
			'value'       => '',
			'type'        => '',
			'ajax_action' => '',
			'modal_id'    => '',
			'creatable'   => true,
		)
	);
	$type        = ! empty( $field['type'] ) ? wp_parse_list( $field['type'] ) : array( 'income' );
	$include     = ! empty( $field['value'] ) ? wp_parse_id_list( $field['value'] ) : false;
	$ajax_action = ! empty( $field['ajax_action'] ) ? $field['ajax_action'] : 'eaccounting_get_income_categories';
	$modal_id    = ! empty( $field['modal_id'] ) ? '#' . $field['modal_id'] : '#ea-modal-add-income-category';
	$categories  = eaccounting_get_categories(
		array(
			'return'  => 'raw',
			'include' => $include,
			'type'    => $type,
		)
	);

	$field = wp_parse_args(
		array(
			'value'        => $include,
			'options'      => wp_list_pluck( $categories, 'name', 'id' ),
			'ajax'         => true,
			'placeholder'  => __( 'Select Category', 'wp-ever-accounting' ),
			'nonce_action' => 'ea_categories',
			'ajax_action'  => esc_attr( $ajax_action ),
			'modal_id'     => esc_attr( $modal_id ),
		),
		$field
	);
	eaccounting_select2( apply_filters( 'eaccounting_category_dropdown', $field ) );
}


/**
 * Dropdown field for selecting currency.
 *
 * @param array $field field properties.
 *
 * @since 1.0.2
 *
 * @return void
 */
function eaccounting_currency_dropdown( $field ) {
	$field         = wp_parse_args(
		$field,
		array(
			'value'       => '',
			'type'        => '',
			'ajax_action' => '',
			'modal_id'    => '',
			'creatable'   => true,
		)
	);
	$default_code  = (string) eaccounting()->settings->get( 'default_currency' );
	$currency_code = ! empty( $field['value'] ) ? wp_parse_list( $field['value'] ) : array();
	$results       = eaccounting_get_currencies(
		array(
			'return' => 'raw',
			'number' => - 1,
		)
	);
	$options       = array();
	foreach ( $results as $item ) {
		$options[ $item->code ] = $item->name . '(' . $item->symbol . ')';
	}
	$field = wp_parse_args(
		array(
			'value'        => $currency_code,
			'default'      => $default_code,
			'options'      => $options,
			'placeholder'  => __( 'Select Currency', 'wp-ever-accounting' ),
			'map'          => 'return {text: option.name + " (" + option.symbol +")"  , id:option.code, item:option}',
			'modal_id'     => '#ea-modal-add-currency',
			'ajax'         => true,
			'ajax_action'  => 'eaccounting_get_currencies',
			'nonce_action' => 'ea_get_currencies',
		),
		$field
	);

	eaccounting_select2( apply_filters( 'eaccounting_currency_dropdown', $field ) );
}


/**
 * Dropdown field for selecting currency.
 *
 * @param array $field Field arguments.
 *
 * @since 1.0.2
 *
 * @return void
 */
function eaccounting_item_dropdown( $field ) {
	$field = wp_parse_args(
		$field,
		array(
			'value'     => '',
			'creatable' => true,
		)
	);

	$items = ! empty( $field['value'] ) ? wp_parse_id_list( $field['value'] ) : array();

	$options = array();
	if ( ! empty( $items ) ) {
		$options = eaccounting_get_items(
			array(
				'return'  => 'raw',
				'include' => $items,
			)
		);
	}
	$field = wp_parse_args(
		$field,
		array(
			'value'        => $items,
			'options'      => wp_list_pluck( $options, 'name', 'id' ),
			'placeholder'  => __( 'Select Item', 'wp-ever-accounting' ),
			'modal_id'     => '#ea-modal-add-item',
			'ajax'         => true,
			'ajax_action'  => 'eaccounting_get_items',
			'nonce_action' => 'ea_get_items',
		)
	);

	eaccounting_select2( apply_filters( 'eaccounting_item_dropdown', $field ) );
}


/**
 * Dropdown field for selecting payment method.
 *
 * @param array $field Field arguments.
 *
 * @since 1.0.2
 *
 * @return void
 */
function eaccounting_payment_method_dropdown( $field ) {
	$default = '';
	if ( ! isset( $field['default'] ) ) {
		$default = eaccounting()->settings->get( 'default_payment_method' );
	}

	$field = wp_parse_args(
		array(
			'placeholder' => __( 'Select payment method', 'wp-ever-accounting' ),
			'default'     => $default,
			'options'     => eaccounting_get_payment_methods(),
		),
		$field
	);

	eaccounting_select2( apply_filters( 'eaccounting_payment_method_dropdown', $field ) );
}

/**
 * Dropdown field for selecting country.
 *
 * @param array $field Field arguments.
 *
 * @since 1.0.2
 *
 * @return void
 */
function eaccounting_country_dropdown( $field ) {
	$default = eaccounting()->settings->get( 'company_country' );
	$field   = wp_parse_args(
		$field,
		array(
			'default'     => $default,
			'options'     => eaccounting_get_countries(),
			'placeholder' => __( 'Select Country', 'wp-ever-accounting' ),
		)
	);

	eaccounting_select2( apply_filters( 'eaccounting_country_dropdown', $field ) );
}

/**
 * Render date range field.
 *
 * @param array $field Field attributes.
 *
 * @since 1.0.2
 */
function eaccounting_input_date_range( $field ) {
	$field = (array) wp_parse_args(
		$field,
		array(
			'start_date'  => '',
			'end_date'    => '',
			'name'        => '',
			'placeholder' => '',
		)
	);
	$value = esc_html__( 'Date range', 'wp-ever-accounting' );
	if ( ! empty( $field['start_date'] ) && ! empty( $field['end_date'] ) ) {
		$value = sprintf( '%s >> %s', eaccounting_date( $field['start_date'], 'd M y' ), eaccounting_date( $field['start_date'], 'd M y' ) );
	}
	?>
	<div class="ea-date-range-picker">
		<span><?php echo esc_html( $value ); ?></span>
		<input type="hidden" class="ea-date-range-picker-start-date" name="start_date" value="<?php echo esc_attr( $field['start_date'] ); ?>" />
		<input type="hidden" class="ea-date-range-picker-end-date" name="end_date" value="<?php echo esc_attr( $field['end_date'] ); ?>" />
	</div>
	<?php
}
