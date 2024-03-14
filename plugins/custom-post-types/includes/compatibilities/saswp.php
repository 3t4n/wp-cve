<?php
// Add fields to dropdown
add_filter(
	'saswp_modify_post_meta_list',
	function ( $options ) {

		$field_groups = cpt_field_groups()->get_registered_groups();

		foreach ( $field_groups as $field_group ) {
			$id       = ! empty( $field_group['id'] ) ? $field_group['id'] : false;
			$label    = ! empty( $field_group['label'] ) ? $field_group['label'] : CPT_NAME;
			$supports = ! empty( $field_group['supports'] ) && is_array( $field_group['supports'] ) ? $field_group['supports'] : false;
			$fields   = ! empty( $field_group['fields'] ) ? $field_group['fields'] : array();

			if ( ! $id || in_array( $id, array_keys( cpt_pro_ui()->post_types ), true ) ) {
				continue;
			}

			$meta_text  = array();
			$meta_image = array();

			foreach ( $fields as $field_config ) {
				if (
					'file' == $field_config['type'] &&
					2 > count( $field_config['extra']['types'] ) &&
				(
					empty( $field_config['extra']['types'][0] ) ||
					'image' == $field_config['extra']['types'][0]
				)
				) {
					$meta_image[ $field_config['key'] ] = $field_config['label'];
					continue;
				}
				$meta_text[ $field_config['key'] ] = $field_config['label'];
			}

			if ( ! empty( $meta_text ) ) {
				$options['text'][] = array(
					'label'     => $label . ' [' . implode(
						', ',
						array_map(
							function ( $item ) {
								return $item['id'];
							},
							$supports
						)
					) . ']',
					'meta-list' => $meta_text,
				);
			}

			if ( ! empty( $meta_image ) ) {
				$options['image'][] = array(
					'label'     => $label . ' [' . implode(
						', ',
						array_map(
							function ( $item ) {
								return $item['id'];
							},
							$supports
						)
					) . ']',
					'meta-list' => $meta_image,
				);
			}
		}

		return $options;
	}
);

// My test to override ACF function, please adapt without override
if ( ! function_exists( 'get_field_object' ) ) {
	function get_field_object( $field ) {
		global $post;
		$field_object = cpt_fields()->get_field_object( $field, \CPT_Field_Groups::SUPPORT_TYPE_CPT, $post->post_type );
		if (
			'file' == $field_object['type'] &&
			2 > count( $field_object['extra']['types'] ) &&
			(
				empty( $field_object['extra']['types'][0] ) ||
				'image' == $field_object['extra']['types'][0]
			)
		) {
			$field_object['type'] = 'image';
		}
		return $field_object;
	}
}
