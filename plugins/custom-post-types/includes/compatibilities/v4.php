<?php

// Old version post types hooks
add_filter(
	'cpt_post_types_register',
	function ( $items ) {
		return apply_filters( 'cpt_register_post_types', $items );
	},
	PHP_INT_MAX
);
add_filter(
	'cpt_post_types_register_labels',
	function ( $registration_labels, $id ) {
		return apply_filters( 'cpt_register_labels_' . $id, $registration_labels );
	},
	PHP_INT_MAX,
	2
);
add_filter(
	'cpt_post_types_register_args',
	function ( $registration_args, $id ) {
		return apply_filters( 'cpt_register_args_' . $id, $registration_args );
	},
	PHP_INT_MAX,
	2
);

// Old version taxonomies hooks
add_filter(
	'cpt_taxonomies_register',
	function ( $items ) {
		return apply_filters( 'cpt_register_taxonomies', $items );
	},
	PHP_INT_MAX
);
add_filter(
	'cpt_taxonomies_register_labels',
	function ( $registration_labels, $id ) {
		return apply_filters( 'cpt_register_tax_labels_' . $id, $registration_labels );
	},
	PHP_INT_MAX,
	2
);
add_filter(
	'cpt_taxonomies_register_args',
	function ( $registration_args, $id ) {
		return apply_filters( 'cpt_register_tax_args_' . $id, $registration_args );
	},
	PHP_INT_MAX,
	2
);

// Old version field groups hooks
add_filter(
	'cpt_field_groups_register',
	function ( $items ) {
		return apply_filters( 'cpt_register_fields', $items );
	},
	PHP_INT_MAX
);
add_filter(
	'cpt_field_args',
	function ( $registration_args, $id ) {
		return apply_filters( 'cpt_' . $id . '_field_args', $registration_args );
	},
	PHP_INT_MAX,
	2
);
add_filter(
	'cpt_field_sanitize',
	function ( $meta_value, $meta_key, $meta_type, $field_group, $content_type, $content_id ) {
		$meta_value = apply_filters( 'cpt_sanitize_field_' . $meta_key, $meta_value );
		$meta_value = apply_filters( 'cpt_sanitize_' . $content_id, $meta_value );
		$meta_value = apply_filters( 'cpt_sanitize_' . $content_id . '_field_' . $meta_key, $meta_value );
		return $meta_value;
	},
	PHP_INT_MAX,
	6
);
add_filter(
	'cpt_field_get',
	function ( $meta_value, $meta_key, $meta_type ) {
		$meta_value = apply_filters( 'cpt_get_field_type_' . $meta_type, $meta_value );
		$meta_value = apply_filters( 'cpt_get_field_' . $meta_key, $meta_value );
		return $meta_value;
	},
	PHP_INT_MAX,
	3
);

// Old version templates hooks
add_filter(
	'cpt_templates_register',
	function ( $items ) {
		return apply_filters( 'cpt_register_templates', $items );
	},
	PHP_INT_MAX
);

// Old version admin pages hooks
add_filter(
	'cpt_admin_pages_register',
	function ( $items ) {
		return apply_filters( 'cpt_register_admin_pages', $items );
	},
	PHP_INT_MAX
);

// Old version admin notices hooks
add_filter(
	'cpt_admin_notices_register',
	function ( $items ) {
		return apply_filters( 'cpt_register_notices', $items );
	},
	PHP_INT_MAX
);
