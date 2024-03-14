<?php
add_action( 'customize_register', 'ecommerce_extra_customizer' );

function ecommerce_extra_customizer( $wp_customize ) {

		// Add panel for front page theme options.
		$wp_customize->add_panel( 'ecommerce_extra_front_page_panel' , array(
			'title'      => esc_html__( 'Front Page Sections',	'ecommerce-plus' ),
			'description'=> esc_html__( 'Front Page Theme Options.',	'ecommerce-plus' ),
			'priority'   => 6,
		));
	
	
		$wp_customize->add_section( 'ecommerce_extra_front_page_section', array(
			'title'             => esc_html__( 'Product Category Section 1',	'ecommerce-extra' ),
			'description'       => esc_html__( 'Product Section 1 options.',	'ecommerce-extra' ),
			'panel'             => 'ecommerce_extra_front_page_panel',
		));
		
		require_once ('customizer/common.php');	
}


/**
 * List of pages for page choices.
 * @return Array Array of page ids and name.
 */
function ecommerce_extra_page_choices() {
    $pages = get_pages();
    $choices = array();
    $choices[0] = esc_html__( '--Select--', 'ecommerce-plus' );
    foreach ( $pages as $page ) {
        $choices[ $page->ID ] = $page->post_title;
    }
    return  $choices;
}

/*
 * List of pages for page choices.
 * @return Array Array of page ids and name.
 */
function ecommerce_extra_get_product_categories(){

	$args = array(
			'taxonomy' => 'product_cat',
			'orderby' => 'name',
			'order' => 'ASC',
			'show_count' => 1,
			'pad_counts' => 0,
			'hierarchical' => 0,
			'title_li' => '',
			'hide_empty' => 1,
	);

	$cats = get_categories($args);

	$arr = array();
	$arr['0'] = esc_html__('All', 'ecommerce-plus');
	foreach($cats as $cat){
		$arr[$cat->term_id] = $cat->name;
	}
	return $arr;
}
