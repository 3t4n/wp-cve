<?php

function br_search_shortcode( $atts ) {
	ob_start();

	do_action( 'show_recipe_search_form_action' );

	$output = ob_get_contents();

	ob_end_clean();

	return $output;
}

add_shortcode( 'Blossom_Recipe_Maker_Search', 'br_search_shortcode' );

function br_search_results_shortcode( $atts ) {
	$submitted_get_data = blossom_recipe_maker_get_submitted_data( 'get' );

	global $post;
	$recipe = get_option( 'br_recipe_settings', array() );

	if ( ! isset( $recipe['pages']['recipe_search'] ) ) {
		return;
	}

	$pageID = $recipe['pages']['recipe_search'];

	if ( ! is_object( $post ) ) {
		return;
	}

	if ( $post->ID != $pageID ) {
		return;
	}

	ob_start();
	$obj = new Blossom_Recipe_Maker_Search_Template();
	$obj->recipe_search_form_template( $submitted_get_data );

	do_action( 'show_recipe_search_results_action' );

	$output = ob_get_contents();

	ob_end_clean();

	return $output;
}

add_shortcode( 'BLOSSOM_RECIPE_MAKER_SEARCH_RESULTS', 'br_search_results_shortcode' );
