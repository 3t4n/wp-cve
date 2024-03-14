<?php

// Slider =============================
if( !function_exists('spawp_slider_section') ){
	function spawp_slider_section(){
		bc_spawp_get_template_part('sections/section','slider');
	}
}
if( function_exists('spawp_slider_section') ){
	$section_priority = apply_filters( 'spawp_section_priority', 5, 'spawp_slider_section' );
	add_action('spawp_sections','spawp_slider_section', absint($section_priority));
}

// Service =============================
if( !function_exists('spawp_service_section') ){
	function spawp_service_section(){
		bc_spawp_get_template_part('sections/section','service');
	}
}
if( function_exists('spawp_service_section') ){
	$section_priority = apply_filters( 'spawp_section_priority', 10, 'spawp_service_section' );
	add_action('spawp_sections','spawp_service_section', absint($section_priority));
}

// Feature =============================
if( !function_exists('spawp_feature_section') ){
	function spawp_feature_section(){
		bc_spawp_get_template_part('sections/section','feature');
	}
}
if( function_exists('spawp_feature_section') ){
	$section_priority = apply_filters( 'spawp_feature_section', 15, 'spawp_feature_section' );
	add_action('spawp_sections','spawp_feature_section', absint($section_priority));
}

// Testimonial =============================
if( !function_exists('spawp_testimonial_section') ){
	function spawp_testimonial_section(){
		bc_spawp_get_template_part('sections/section','testimonial');
	}
}
if( function_exists('spawp_testimonial_section') ){
	$section_priority = apply_filters( 'spawp_section_priority', 40, 'spawp_testimonial_section' );
	add_action('spawp_sections','spawp_testimonial_section', absint($section_priority));
}

// Team =============================
if( !function_exists('spawp_team_section') ){
	function spawp_team_section(){
		bc_spawp_get_template_part('sections/section','team');
	}
}
if( function_exists('spawp_team_section') ){
	$section_priority = apply_filters( 'spawp_section_priority', 45, 'spawp_team_section' );
	add_action('spawp_sections','spawp_team_section', absint($section_priority));
}