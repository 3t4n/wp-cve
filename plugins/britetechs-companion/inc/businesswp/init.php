<?php

function bc_businesswp_theme_init(){

	if( class_exists('Businesswp_Premium_Setup') ){
	    return;
	  }

	// default contents
	include('template-tags.php');

	// customizer settings
	include('customizer/businesswp-theme-front-page-common-settings.php');
	include('customizer/businesswp-theme-front-page-contents-settings.php');
	include('customizer/businesswp-theme-multiple-layout-settings.php');
	include('customizer/businesswp-theme-slider-settings.php');

	// show in front page
	include('sections/section-slider.php');
	include('sections/section-service.php');
	include('sections/section-portfolio.php');
	include('sections/section-testimonial.php');
	include('sections/section-team.php');
	include('sections/section-contact.php');
}
add_action('init','bc_businesswp_theme_init', 20 );