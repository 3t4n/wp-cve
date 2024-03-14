<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Repeater extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'repeater';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'Repeater', 'custom-post-types' );
	}

	/**
	 * @return array
	 */
	public static function get_extra() {
		return array( cpt_utils()->get_args( 'fields-repeater' ) );
	}

	/**
	 * @param $input_name
	 * @param $input_id
	 * @param $field_config
	 *
	 * @return false|string
	 */
	public static function render( $input_name, $input_id, $field_config ) {
		$fields          = ! empty( $field_config['extra']['fields'] ) && is_array( $field_config['extra']['fields'] ) ? $field_config['extra']['fields'] : array();
		$values          = ! empty( $field_config['value'] ) && is_array( $field_config['value'] ) ? $field_config['value'] : array();
		$parent_base     = ( ! empty( $field_config['parent'] ) ? $field_config['parent'] : '' ) . '[' . $field_config['key'] . ']';
		$fields_group_id = $field_config['fields_group_id'];
		ob_start();
		?>
		<div class="cpt-repeater-section"
			data-fields="<?php echo htmlspecialchars( wp_json_encode( $fields ), ENT_QUOTES, 'UTF-8' ); ?>"
			data-parent="<?php echo $parent_base; ?>"
			data-fields-group="<?php echo $fields_group_id; ?>"
		>
			<?php
			foreach ( $values as $i => $value ) {
				$parent = $parent_base . '[' . $i . ']';
				echo self::render_group( $fields, $parent, $fields_group_id, $value );
			}
			?>
		</div>
		<button class="cpt-repeater-add" title="<?php _e( 'Add', 'custom-post-types' ); ?>">
			<span class="dashicons dashicons-insert"></span>
		</button>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param $fields
	 * @param $parent_field
	 * @param $fields_group_id
	 * @param $value
	 *
	 * @return false|string
	 */
	public static function render_group( $fields, $parent_field, $fields_group_id, $value = array() ) {
		ob_start();
		?>
		<div class="cpt-repeater-group">
			<div class="cpt-repeater-buttons">
				<div class="order"></div>
				<button class="button cpt-repeater-button button-secondary move"
						title="<?php _e( 'Reorder', 'custom-post-types' ); ?>"
						aria-label="<?php _e( 'Reorder', 'custom-post-types' ); ?>">
					<span class="dashicons dashicons-move"></span>
				</button>
				<button class="button cpt-repeater-button button-secondary remove"
						title="<?php _e( 'Remove', 'custom-post-types' ); ?>"
						aria-label="<?php _e( 'Remove', 'custom-post-types' ); ?>">
					<span class="dashicons dashicons-remove"></span>
				</button>
			</div>
			<div class="cpt-repeater-fields">
				<?php
				foreach ( $fields as $i => $field ) {
					if ( CPT_UI_PREFIX . '_field' == $fields_group_id && 5 == $i ) { //phpcs:ignore Universal.Operators.StrictComparisons
						?>
						<div class="cpt-repeater-extra">
							<?php
							$field_type = ! empty( $value['type'] ) ? $value['type'] : false;
							if ( $field_type || ! empty( cpt_fields()->get_field( $field_type ) ) ) {
								foreach ( cpt_fields()->get_field( $field_type )::get_extra() as $extra_field ) {
									$extra_field['value']           = isset( $value['extra'][ $extra_field['key'] ] ) ? $value['extra'][ $extra_field['key'] ] : '';
									$extra_field['parent']          = $parent_field . '[extra]';
									$extra_field['fields_group_id'] = $fields_group_id;
									echo cpt_fields()->get_field_template( $extra_field );
								}
							}
							?>
						</div>
						<?php
					}
					$field['value']           = isset( $value[ $field['key'] ] ) ? $value[ $field['key'] ] : '';
					$field['parent']          = $parent_field;
					$field['fields_group_id'] = $fields_group_id;
					echo cpt_fields()->get_field_template( $field );
				}
				?>
			</div>
			<div class="cpt-repeater-remove" aria-hidden="true">
				<button class="button button-secondary abort"
						title="<?php _e( 'Cancel', 'custom-post-types' ); ?>"
						aria-label="<?php _e( 'Cancel', 'custom-post-types' ); ?>">
					<?php _e( 'Cancel', 'custom-post-types' ); ?>
				</button>
				<button class="button button-primary confirm"
						title="<?php _e( 'Confirm', 'custom-post-types' ); ?>"
						aria-label="<?php _e( 'Confirm', 'custom-post-types' ); ?>">
					<?php _e( 'Confirm', 'custom-post-types' ); ?>
				</button>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param $fields
	 *
	 * @return array
	 */
	public static function get_repeater_fields_map( $fields ) {
		$result = array();

		foreach ( $fields as $field ) {
			$result[ $field['key'] ] = array( 'type' => $field['type'] );
			if ( 'repeater' == $field['type'] ) {
				$result[ $field['key'] ]['fields'] = self::get_repeater_fields_map( $field['extra']['fields'] );
			}
		}

		return $result;
	}

	/**
	 * @param $meta_value
	 * @param $meta_key
	 * @param $meta_type
	 * @param $field_group
	 * @param $content_type
	 * @param $content_id
	 * @param $fields
	 *
	 * @return array|mixed
	 */
	public static function sanitize_recursive( $meta_value, $meta_key, $meta_type, $field_group, $content_type, $content_id, $fields ) {
		if ( empty( $meta_value ) ) {
			return $meta_value;
		}

		if ( 'extra' == $meta_key && CPT_UI_PREFIX . '_field' == $field_group['id'] ) {
			$fields     = self::get_repeater_fields_map( cpt_fields()->get_field( $meta_type )::get_extra() );
			$meta_value = array( $meta_value );
		}

		foreach ( $meta_value as $i => $meta_group ) {
			foreach ( $meta_group as $key => $value ) {
				if ( 'extra' == $key && CPT_UI_PREFIX . '_field' == $field_group['id'] ) {
					$meta_value[ $i ][ $key ] = self::sanitize_recursive( $value, $key, $meta_group['type'], $field_group, $content_type, $content_id, $fields );
				} elseif ( 'repeater' == $fields[ $key ]['type'] ) {
					$meta_value[ $i ][ $key ] = self::sanitize_recursive( $value, $key, 'repeater', $field_group, $content_type, $content_id, $fields[ $key ]['fields'] );
				} else {
					$meta_value[ $i ][ $key ] = apply_filters( 'cpt_field_sanitize', $value, $key, $fields[ $key ]['type'], $field_group, $content_type, $content_id );
				}
			}
		}

		return ( 'extra' == $meta_key && CPT_UI_PREFIX . '_field' == $field_group['id'] ) ? $meta_value[0] : $meta_value;
	}
}

cpt_fields()->add_field_type( CPT_Field_Repeater::class );

add_filter(
	'cpt_ajax_actions_register',
	function ( $actions ) {
		$actions['cpt-get-repeater-group']        = array(
			'required' => array( 'fields', 'fields-group-id' ),
			'callback' => function ( $params ) {
				$fields_group_id = $params['fields-group-id'];
				$fields          = is_array( json_decode( stripslashes( $params['fields'] ), true ) ) ? json_decode( stripslashes( $params['fields'] ), true ) : array();
				if ( empty( $fields ) ) {
					wp_send_json_error();
				}
				$parent = ! empty( $params['parent'] ) ? $params['parent'] : '';

				return CPT_Field_Repeater::render_group( $fields, $parent, $fields_group_id );
			},
		);
		$actions['cpt-get-repeater-extra-fields'] = array(
			'required' => array( 'field-type', 'fields-group-id' ),
			'callback' => function ( $params ) {
				$field_type      = $params['field-type'];
				$fields_group_id = $params['fields-group-id'];
				$parent          = ! empty( $params['parent'] ) ? $params['parent'] : '';
				$fields          = cpt_fields()->get_field( $field_type )::get_extra();
				ob_start();
				foreach ( $fields as $field ) {
					$field['value']           = '';
					$field['parent']          = $parent . '[extra]';
					$field['fields_group_id'] = $fields_group_id;
					echo cpt_fields()->get_field_template( $field );
				}

				return ob_get_clean();
			},
		);

		return $actions;
	}
);

add_filter(
	'cpt_field_sanitize',
	function ( $meta_value, $meta_key, $meta_type, $field_group, $content_type, $content_id ) {
		$field_group_id = $field_group['id'];
		if (
			'fields' == $meta_key && //phpcs:ignore Universal.Operators.StrictComparisons
			CPT_UI_PREFIX . '_field' == $field_group_id && //phpcs:ignore Universal.Operators.StrictComparisons
			! empty( $meta_value )
		) {
			foreach ( $meta_value as $i => $meta_args ) {
				$meta_value[ $i ]['key']     = sanitize_title( $meta_args['key'] );
				$meta_value[ $i ]['wrap_id'] = sanitize_title( $meta_args['wrap_id'] );
			}
		}

		if ( 'repeater' == $meta_type ) {
			$fields     = CPT_Field_Repeater::get_repeater_fields_map( $field_group['fields'] )[ $meta_key ]['fields'];
			$meta_value = CPT_Field_Repeater::sanitize_recursive( $meta_value, $meta_key, $meta_type, $field_group, $content_type, $content_id, $fields );
		}

		return $meta_value;
	},
	10,
	6
);