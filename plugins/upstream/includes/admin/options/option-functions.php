<?php
/**
 * Handle functions for options
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns colorpicker default colors.
 */
function upstream_colorpicker_default_colors() {
	$array = array(
		'#D15C9C',
		'#9A5CD1',
		'#5C75D1',
		'#5CBFD1',
		'#5CD165',
		'#D1D15C',
		'#D1A65C',
		'#D17F5C',
		'#D15D5C',
	);

	return apply_filters( 'upstream_colorpicker_default_colors', $array );
}

/**
 * Upstream Render Labels Field Callback
 *
 * @param  mixed $field Field.
 * @param  mixed $value Value.
 * @param  mixed $object_id Object Id.
 * @param  mixed $object_type Object Type.
 * @param  mixed $field_type Field Type.
 * @return void
 */
function upstream_render_labels_field_callback( $field, $value, $object_id, $object_type, $field_type ) {
	// make sure we specify each part of the value we need.
	$value = wp_parse_args(
		$value,
		array(
			'single' => '',
			'plural' => '',
		)
	);

	// allowed html tags for wp_kses input form.
	$allowed_html_tags = array(
		'input' => array(
			'type'  => array(),
			'class' => array(),
			'name'  => array(),
			'id'    => array(),
			'value' => array(),
			'desc'  => array(),
		),
	);
	?>
	<div class="alignleft"><p>
		<label for="<?php echo esc_attr( $field_type->_id( '_single' ) ); ?>'">
			<?php
			esc_html_e(
				'Single',
				'upstream'
			);
			?>
		</label></p>
		<?php
		echo wp_kses(
			$field_type->input(
				array(
					'name'  => $field_type->_name( '[single]' ),
					'id'    => $field_type->_id( '_single' ),
					'value' => $value['single'],
					'desc'  => '',
				)
			),
			$allowed_html_tags
		);
		?>
	</div>
	<div class="alignleft"><p>
		<label for="<?php echo esc_attr( $field_type->_id( '_plural' ) ); ?>'">
			<?php
			esc_html_e(
				'Plural',
				'upstream'
			);
			?>
		</label></p>
		<?php
		echo wp_kses(
			$field_type->input(
				array(
					'name'  => $field_type->_name( '[plural]' ),
					'id'    => $field_type->_id( '_plural' ),
					'value' => $value['plural'],
					'desc'  => '',
				)
			),
			$allowed_html_tags
		)
		?>
	</div>
	<br class="clear">
	<?php
	echo esc_html( $field_type->_desc( true ) );
}
add_filter( 'cmb2_render_labels', 'upstream_render_labels_field_callback', 10, 5 );
