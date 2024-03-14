<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Tax_Rel extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'tax_rel';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'Taxonomy relationship', 'custom-post-types' );
	}

	/**
	 * @return array
	 */
	public static function get_extra() {
		return array(
			cpt_utils()->get_ui_placeholder_field( 50 ),
			cpt_utils()->get_ui_yesno_field(
				'multiple',
				__( 'Multiple', 'custom-post-types' ),
				false,
				'NO',
				'',
				'25',
				''
			),
			array( //taxonomy
				'key'      => 'taxonomy',
				'label'    => __( 'Taxonomy', 'custom-post-types' ),
				'info'     => false,
				'required' => false,
				'type'     => 'select',
				'extra'    => array(
					'placeholder' => __( 'Categories', 'custom-post-types' ) . ' - ' . __( 'Default', 'custom-post-types' ),
					'multiple'    => false,
					'options'     => cpt_utils()->get_taxonomies_options(),
				),
				'wrap'     => array(
					'width'  => '25',
					'class'  => '',
					'id'     => '',
					'layout' => '',
				),
			),
		);
	}

	/**
	 * @param $input_name
	 * @param $input_id
	 * @param $field_config
	 *
	 * @return string
	 */
	public static function render( $input_name, $input_id, $field_config ) {
		$options = '<option value=""></option>';

		if ( isset( $field_config['value'] ) ) {
			$term_ids = ! empty( $field_config['value'] ) ? ( is_array( $field_config['value'] ) ? $field_config['value'] : array( $field_config['value'] ) ) : array();
			foreach ( $term_ids as $term_id ) {
				$term = get_term( $term_id );
				if ( ! isset( $term->name ) ) {
					continue;
				}
				$options .= sprintf(
					'<option value="%s" selected="selected">%s</option>',
					$term_id,
					cpt_utils()->get_term_title_with_parents( $term_id )
				);
			}
		}

		return sprintf(
			'<select name="%s" id="%s" autocomplete="off" aria-autocomplete="none" style="width: 100%%;"%s%s data-type="%s"%s>%s</select>',
			$input_name . ( ! empty( $field_config['extra']['multiple'] ) && 'true' == $field_config['extra']['multiple'] ? '[]' : '' ), //phpcs:ignore Universal.Operators.StrictComparisons
			$input_id,
			! empty( $field_config['extra']['placeholder'] ) ? ' placeholder="' . $field_config['extra']['placeholder'] . '"' : '',
			! empty( $field_config['extra']['multiple'] ) && 'true' == $field_config['extra']['multiple'] ? ' multiple' : '', //phpcs:ignore Universal.Operators.StrictComparisons
			! empty( $field_config['extra']['taxonomy'] ) ? $field_config['extra']['taxonomy'] : 'category',
			! empty( $field_config['required'] ) ? ' required' : '',
			$options
		);
	}

	/**
	 * @param $meta_value
	 *
	 * @return string|void
	 */
	public static function get( $meta_value ) {
		if ( empty( $meta_value ) ) {
			return;
		}
		if ( is_array( $meta_value ) ) {
			$terms = array();
			foreach ( $meta_value as $term_id ) {
				if ( ! get_term( (int) $term_id ) ) {
					continue;
				}
				$terms[] = sprintf( '<a href="%1$s" title="%2$s" aria-label="%2$s">%2$s</a>', get_term_link( (int) $term_id ), get_term( (int) $term_id )->name );
			}
			return implode( ', ', $terms );
		}
		if ( ! get_term( (int) $meta_value ) ) {
			return;
		}
		return sprintf( '<a href="%1$s" title="%2$s" aria-label="%2$s">%2$s</a>', get_term_link( (int) $meta_value ), get_term( (int) $meta_value )->name );
	}
}

cpt_fields()->add_field_type( CPT_Field_Tax_Rel::class );

add_filter(
	'cpt_ajax_actions_register',
	function ( $actions ) {
		$actions['cpt-get-tax_rel-options'] = array(
			'requiredParams' => array( 'taxonomy' ),
			'callback'       => function ( $params ) {
				$taxonomy = $params['taxonomy'];
				$search   = isset( $params['search'] ) ? $params['search'] : '';
				$terms    = get_terms(
					array(
						'taxonomy'   => $taxonomy,
						'name__like' => $search,
						'hide_empty' => false,
						'number'     => 10,
					)
				);
				$result   = array();
				foreach ( $terms as $term ) {
					$result[] = array(
						'id'   => $term->term_id,
						'text' => cpt_utils()->get_term_title_with_parents( $term->term_id ),
					);
				}
				return $result;
			},
		);
		return $actions;
	}
);
