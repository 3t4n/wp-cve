<?php

// Slider
if( ! function_exists('bizcor_slider_section') ){
	function bizcor_slider_section(){
		bc_bizcor_get_template_part('template-parts/sections-homepage/section','slider');
	}

	$section_priority = apply_filters( 'bizcor_section_priority', 5, 'bizcor_slider_section' );
	if(isset($section_priority) && $section_priority != '' ){
		add_action('bizcor_sections','bizcor_slider_section', absint($section_priority));
	}
}

// Info
if( ! function_exists('bizcor_info_section') ){
	function bizcor_info_section(){
		bc_bizcor_get_template_part('template-parts/sections-homepage/section','info');
	}
	$section_priority = apply_filters( 'bizcor_section_priority', 10, 'bizcor_info_section' );
	if(isset($section_priority) && $section_priority != '' ){
		add_action('bizcor_sections','bizcor_info_section', absint($section_priority));
	}
}

// Service
if( ! function_exists('bizcor_service_section') ){
	function bizcor_service_section(){
		bc_bizcor_get_template_part('template-parts/sections-homepage/section','service');
	}
	$section_priority = apply_filters( 'bizcor_section_priority', 15, 'bizcor_service_section' );
	if(isset($section_priority) && $section_priority != '' ){
		add_action('bizcor_sections','bizcor_service_section', absint($section_priority));
	}
}

// Testimonial
if( ! function_exists('bizcor_testimonial_section') ){
	function bizcor_testimonial_section(){
		bc_bizcor_get_template_part('template-parts/sections-homepage/section','testimonial');
	}
	$section_priority = apply_filters( 'bizcor_section_priority', 40, 'bizcor_testimonial_section' );
	if(isset($section_priority) && $section_priority != '' ){
		add_action('bizcor_sections','bizcor_testimonial_section', absint($section_priority));
	}
}