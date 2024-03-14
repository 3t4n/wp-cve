<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Post_Rel extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'post_rel';
	}

	/**
	 * @return string|null
	 */
	public static function get_label() {
		return __( 'Post relationship', 'custom-post-types' );
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
			array( //post_type
				'key'      => 'post_type',
				'label'    => __( 'Post type', 'custom-post-types' ),
				'info'     => false,
				'required' => false,
				'type'     => 'select',
				'extra'    => array(
					'placeholder' => __( 'Posts', 'custom-post-types' ) . ' - ' . __( 'Default', 'custom-post-types' ),
					'multiple'    => false,
					'options'     => cpt_utils()->get_post_types_options(),
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
			$post_ids = ! empty( $field_config['value'] ) ? ( is_array( $field_config['value'] ) ? $field_config['value'] : array( $field_config['value'] ) ) : array();
			foreach ( $post_ids as $post_id ) {
				$post = get_post( $post_id );
				if ( ! isset( $post->post_title ) ) {
					continue;
				}
				$options .= sprintf(
					'<option value="%s" selected="selected">%s</option>',
					$post_id,
					cpt_utils()->get_post_title_with_parents( $post_id )
				);
			}
		}

		return sprintf(
			'<select name="%s" id="%s" autocomplete="off" aria-autocomplete="none" style="width: 100%%;"%s%s data-type="%s"%s>%s</select>',
			$input_name . ( ! empty( $field_config['extra']['multiple'] ) && 'true' == $field_config['extra']['multiple'] ? '[]' : '' ), //phpcs:ignore Universal.Operators.StrictComparisons
			$input_id,
			! empty( $field_config['extra']['placeholder'] ) ? ' placeholder="' . $field_config['extra']['placeholder'] . '"' : '',
			! empty( $field_config['extra']['multiple'] ) && 'true' == $field_config['extra']['multiple'] ? ' multiple' : '', //phpcs:ignore Universal.Operators.StrictComparisons
			! empty( $field_config['extra']['post_type'] ) ? $field_config['extra']['post_type'] : 'post',
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
			$posts = array();
			foreach ( $meta_value as $post_id ) {
				if ( ! get_post( (int) $post_id ) ) {
					continue;
				}
				$posts[] = sprintf( '<a href="%1$s" title="%2$s" aria-label="%2$s">%2$s</a>', get_permalink( (int) $post_id ), get_the_title( (int) $post_id ) );
			}
			return implode( ', ', $posts );
		}
		if ( ! get_post( (int) $meta_value ) ) {
			return;
		}
		return sprintf( '<a href="%1$s" title="%2$s" aria-label="%2$s">%2$s</a>', get_permalink( (int) $meta_value ), get_the_title( (int) $meta_value ) );
	}
}

cpt_fields()->add_field_type( CPT_Field_Post_Rel::class );

add_filter(
	'cpt_ajax_actions_register',
	function ( $actions ) {
		$actions['cpt-get-post_rel-options'] = array(
			'required' => array( 'post_type' ),
			'callback' => function ( $params ) {
				$post_type = $params['post_type'];
				$search    = isset( $params['search'] ) ? $params['search'] : '';
				$posts     = get_posts(
					array(
						'post_type'   => $post_type,
						's'           => $search,
						'numberposts' => 10,
					)
				);
				$result    = array();
				foreach ( $posts as $post ) {
					$result[] = array(
						'id'   => $post->ID,
						'text' => cpt_utils()->get_post_title_with_parents( $post->ID ),
					);
				}
				return $result;
			},
		);
		return $actions;
	}
);
