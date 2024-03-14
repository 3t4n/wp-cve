<?php

function br_recipe_shortcode( $atts = '' ) {
	ob_start();
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts,
		'recipe-maker'
	);

	$post_id = $atts['id'];
	do_action( 'br_recipe_category_links_action', $post_id );
	do_action( 'br_recipe_gallery_action', $post_id );
	do_action( 'br_recipe_details_action', $post_id );
	do_action( 'br_recipe_description_action', $post_id );
	do_action( 'br_recipe_call_to_action', $post_id );
	do_action( 'br_recipe_ingredients_action', $post_id );
	do_action( 'br_recipe_instructions_action', $post_id );
	do_action( 'br_recipe_notes_action', $post_id );
	do_action( 'br_recipe_post_tags_action', $post_id );

	$output = ob_get_contents();
	wpautop( $output, true );

	ob_end_clean();

	return $output;
}

add_shortcode( 'recipe-maker', 'br_recipe_shortcode' );
