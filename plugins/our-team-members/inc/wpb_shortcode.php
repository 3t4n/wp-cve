<?php

/**
 * WPB Post Sliderby WpBean
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 



/**
 * Adding the shortcode
 */
add_shortcode( 'wpb-our-team-members', 'wpb_our_team_members_shortcode' );

function wpb_our_team_members_shortcode( $atts ){
	
	$shortcode_atts = array(
		'skin'						=> 'default', // one, two, three, four
		'number_of_member'		    => 4,
		'column'					=> 4, // 1,2,3,4,6
		'excerpt_length'			=> 20,
		'order' 					=> 'DESC',
		'orderby' 					=> 'date',
		'member_categories'			=> '', // comma separated categories id
		'x_class'					=> '',
		'type'						=> 'default',
	);

	extract( shortcode_atts( $shortcode_atts, $atts ) );

	global $post;


	$args = array(
		'post_type'      => 'wpb_team_members',
		'posts_per_page' => $number_of_member,
		'orderby'        => $orderby,
		'order'          => $order,
	);

	// only form selected team member categories
	if( $member_categories ){
		$member_categories = explode(',', $member_categories);
		$args['tax_query'][] = array(
			'taxonomy' 	=> 'wpb_team_member_category',
	        'field'    	=> 'id',
			'terms'    	=> $member_categories,
	        'operator' 	=> 'IN'
		);
	}

	$slider_classes = apply_filters( 'wpb_our_team_members_classes', array( 'wpb-our-team-members wpb-our-team-members-skin-' . $skin, $x_class ) );

	$wp_query = new WP_Query( $args );

	ob_start();
    wpb_otm_get_template( 'shortcode.php', array(
        'shortcode_atts'  	=> $shortcode_atts,
        'atts'     			=> $atts,
        'slider_classes'  	=> $slider_classes,
        'wp_query'  		=> $wp_query
    ) );

	wp_reset_query();
	wpb_otm_get_scripts( $atts );
	return ob_get_clean();
}



/**
 * Registering the Generated Shortcode
 */
add_shortcode( 'wpb-otm-shortcode','wpb_otm_generated_shortcode_function' );

if( !function_exists('wpb_otm_generated_shortcode_function') ):
	function wpb_otm_generated_shortcode_function( $atts ){
		extract(shortcode_atts(array(
			'id'				=> '',
			'title'				=> ''
		), $atts));

		global $post;

		$skin              = wpb_otm_get_post_meta( '_wpb_team_members_shortcode', 'skin',  $id );
		$skin              = $skin ? 'skin="'.$skin.'"' : '';

		$number_of_member  = wpb_otm_get_post_meta( '_wpb_team_members_shortcode', 'number_of_member',  $id );
		$number_of_member  = $number_of_member ? 'number_of_member="'.$number_of_member.'"' : '';
		
		$column            = wpb_otm_get_post_meta( '_wpb_team_members_shortcode', 'column',  $id );
		$column            = $column ? 'column="'.$column.'"' : '';
		
		$excerpt_length     = wpb_otm_get_post_meta( '_wpb_team_members_shortcode', 'excerpt_length',  $id );
		$excerpt_length     = $excerpt_length ? 'excerpt_length="'.$excerpt_length.'"' : '';
		
		$order             = wpb_otm_get_post_meta( '_wpb_team_members_shortcode', 'order',  $id );
		$order             = $order ? 'order="'.$order.'"' : '';
		
		$orderby           = wpb_otm_get_post_meta( '_wpb_team_members_shortcode', 'orderby',  $id );
		$orderby           = $orderby ? 'orderby="'.$orderby.'"' : '';
		
		
		$member_categories = wpb_otm_get_post_meta( '_wpb_team_members_shortcode', 'member_categories', $id );
		$member_categories = $member_categories ? 'member_categories="'.$member_categories.'"' : '';		
		
		$x_class           = wpb_otm_get_post_meta( '_wpb_team_members_shortcode', 'x_class',  $id );
		$x_class           = $x_class ? 'x_class="'.$x_class.'"' : '';

	   	ob_start();

		echo do_shortcode( '[wpb-our-team-members '.$number_of_member.' '.$column.' '.$order.' '.$orderby.' '.$skin.' '.$excerpt_length.' '.$member_categories.' '.$x_class.']' );

		edit_post_link( esc_html__( 'Edit ShortCode', 'our-team-members' ), '', '', $id, 'wpb-fp-shortcode-edit' );

		return ob_get_clean();
	
	}
endif;
