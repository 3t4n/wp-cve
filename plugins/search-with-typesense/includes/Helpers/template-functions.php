<?php
if ( ! function_exists( 'cm_swt_get_template' ) ) {
	function cm_swt_get_template( $file, $args = [] ) {
		\Codemanas\Typesense\Helpers\Templates::getInstance()->include_file( $file, $args );
	}
}

/**
 * Get label of the indexed post types/taxonomy
 *
 * @param $post_type string Slug of the indexed posttype/tax
 * @param $post_type array Array of configs
 *
 * @return $label string lable of the indexed
 */
if ( ! function_exists( 'cm_swt_get_label' ) ) {
	function cm_swt_get_label( $post_type, $config ) {
		$label = ( isset( $config['config']['post_type'][ $post_type ]['label'] ) && $config['config']['post_type'][ $post_type ]['label'] != '' ) ? $config['config']['post_type'][ $post_type ]['label'] : $config['available_post_types'][ $post_type ]['label'];

		return $label;
	}
}

