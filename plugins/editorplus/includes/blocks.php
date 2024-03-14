<?php
/**
 * Main file for block related utility functions
 *
 * @package EditorPlus
 */

/**
 * Will check if the given block is a valid reusable block.
 *
 * @param array $block - Block to test.
 * @return bool - True if reusable, otherwise false.
 */
function editorplus_is_valid_reusable_block( $block ) {

	if ( ! is_array( $block ) || ! isset( $block['attrs'] ) || ! isset( $block['blockName'] ) ) {
		return false;
	}

	return 'core/block' === $block['blockName'] && isset( $block['attrs']['ref'] ) && is_integer( $block['attrs']['ref'] );

}

/**
 * Will convert the given reusable block to regular block content.
 *
 * @param array $reusable_block - Re-usable block to convert.
 * @param bool  $parse_blocks - Will parse the reusable block content if true.
 * @return string|array - Reusable block content. Will provide parsed block list if $parsed_blocks param is set to true. Will return false on invalid block.
 */
function editorplus_convert_reusable_block( $reusable_block, $parse_blocks = false ) {

	$post = get_post( $reusable_block['attrs']['ref'] );

	/**
	 * Providing empty list or string if the post type is invalid.
	 *
	 * Check 1: Checking if the post exists.
	 * Check 2: Checking if the post_type is valid.
	 * Check 3: Checking if the post status is published.
	 * Check 4: Checking if the post is not password protected.
	 */
	if ( is_null( $post ) || 'wp_block' !== $post->post_type || 'publish' !== $post->post_status || ! empty( $post->post_password ) ) {
		return $parse_blocks ? array() : '';
	}

	$post_content = $post->post_content;

	if ( $parse_blocks ) {
		return parse_blocks( $post_content );
	}

	return $post_content;
}

/**
 * Will convert reusable blocks to regular block in the given block list recurively.
 *
 * @param array $blocks - Block list to convert.
 * @return array - Converted block list.
 */
function editorplus_convert_reusable_blocks( $blocks ) {

	$new_block_list = array();

	foreach ( $blocks as $block ) {

		$is_reusable_block = editorplus_is_valid_reusable_block( $block );

		if ( false === $is_reusable_block ) {

			$current_block = $block;

			// Recursively converting inner blocks.
			if ( isset( $current_block['innerBlocks'] ) && is_array( $current_block['innerBlocks'] ) ) {
				$current_block['innerBlocks'] = editorplus_convert_reusable_blocks( $current_block['innerBlocks'] );
			}

			array_push( $new_block_list, $current_block );

		} else {

			// All of the found parsed blocks in the reusable post type.
			$converted_post_blocks = editorplus_convert_reusable_block( $block, true );

			// Merging each found block in our list.
			foreach ( $converted_post_blocks as $converted_post_block ) {
				array_push( $new_block_list, $converted_post_block );
			}
		}
	}

	return $new_block_list;

}

/**
 * Checks if the given block is supported by editorplus.
 *
 * @param string $slug - Block slug.
 *
 * @return bool - True if supported, otherwise false.
 */
function editorplus_is_supported_block( $slug ) {

	if ( ! is_string( $slug ) ) {
		return false;
	}

	$unsupported_blocks = array(
		'core/nextpage',
		'core/calendar',
		'core/latest-comments',
		'core/tag-cloud',
		'core/archives',
		'core/block',
	);

	if ( in_array( $slug, $unsupported_blocks, true ) ) {
		return false;
	}

	return true;

}

/**
 * Checks if the given parsed block has editorplus styling in attributes.
 *
 * @param array $block - block to test.
 *
 * @return bool - True if editorplus styling found, otherwise false.
 */
function editorplus_has_block_styles( $block ) {

	if ( ! is_array( $block ) || ! isset( $block['attrs'] ) ) {
		return false;
	}

	$editorplus_attributes = editorplus_get_block_styling_attributes( $block );

	return count( $editorplus_attributes ) > 0;
}

/**
 * Will filter and provide styling attributes of the given block.
 *
 * @param array $block - Block to filter styles attributes from.
 *
 * @return array - Editorplus styling attributes.
 */
function editorplus_get_block_styling_attributes( $block ) {

	if ( ! is_array( $block ) || ! isset( $block['attrs'] ) ) {
		return array();
	}

	return array_filter(
		$block['attrs'],
		function( $attr_name ) {

			$extra_editorplus_attributes = array( 'epStylingOptions', 'editorPlusCustomCSS', 'epCustomAnimation' );

			return is_string( $attr_name ) && ( false !== stripos( $attr_name, 'epCustom' ) || in_array( $attr_name, $extra_editorplus_attributes, true ) );
		},
		ARRAY_FILTER_USE_KEY
	);
}
