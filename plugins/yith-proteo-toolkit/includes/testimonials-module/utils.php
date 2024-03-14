<?php
/**
 * Testimonials utils
 *
 * @since 1.1.0
 * @package YITH_Proteo_tookit
 */

/**
 * Return a list of testimonial elements that can be showed
 *
 * @param string $return_type The type of return.
 *
 * @return mixed
 */
function yith_proteo_toolkit_testimonials_get_list_of_elements_to_show( $return_type = 'string' ) {
	$elements = array(
		'picture',
		'name',
		'subtitle',
		'review',
		'quote',
		'website',
		'facebook',
		'twitter',
		'youtube',
		'instagram',
		'tiktok',
		'linkedin',
		'skype',
		'categories',
	);
	if ( 'array' === $return_type ) {
		return $elements;
	}
	return implode( ',', $elements );
}

/**
 * Return a list of testimonial taxonomies
 *
 * @return mixed
 */
function yith_proteo_testimonials_get_taxonomies_list() {
	$all_terms = get_terms(
		array(
			'taxonomy'   => 'proteo_testimonials_tax',
			'hide_empty' => false,
		)
	);
	return wp_list_pluck( $all_terms, 'name', 'slug' );
}

/**
 * Return a list of testimonials names
 *
 * @return array
 */
function yith_proteo_testimonials_get_testimonials_names_list() {
	$args = array(
		'numberposts' => -1,
		'post_type'   => 'proteo_testimonials',
		'orderby'     => 'title',
		'order'       => 'ASC',
		'post_status' => 'publish',
	);

	$testimonials = get_posts( $args );

	$testimonials_array = array();

	foreach ( $testimonials as $testimonial ) {
		$testimonials_array[] = array(
			'label' => esc_html( $testimonial->post_title ),
			'value' => esc_html( $testimonial->post_title ),
		);
	}

	return $testimonials_array;

}
