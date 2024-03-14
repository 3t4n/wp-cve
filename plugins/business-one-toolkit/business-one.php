<?php
/**
 * Plugin Name: Business One Toolkit
 * Description: Custom post type and Taxonomy functionality
 * Version: 1.0.0
 * Author: Burak Aydin
 * Author URI: http://burak-aydin.com
 * Text Domain: business-one
 * License: GPLv2 or later
 */


function business_one_post_type(){

	register_post_type('about',array(
		'labels' => array(
			'name' => __('About','business-one'),
			),
		'description' => __('Add what you do, skills and history','business-one'),		
		'public' => true,
		'menu_position' => 100,
		'menu_icon' => 'dashicons-nametag',
		'supports' => array('title'),
		));


	register_post_type('skill',array(
		'labels' => array(
			'name' => __('Skill','business-one'),
			),
		'description' => __('Add Skill','business-one'),		
		'public' => true,
		'menu_position' => 100,
		'menu_icon' => 'dashicons-id',
		'supports' => array('title'),
		));



	register_post_type('portfolio',array(
		'labels' => array(
			'name' => __('Portfolio','business-one'),
			'all_items' => __('All Portfolios','business-one'),
			),
		'description' => __('Add your works','business-one'),		
		'public' => true,
		'menu_position' => 100,
		'menu_icon' => 'dashicons-portfolio',
		'supports' => array('title','editor','thumbnail'),
		));
	

	/* Taxonomy for Portfolio */
	register_taxonomy('port_post','portfolio',array(
		'hierarchical' => true,
		'label' => 'Category',
		'query_var' => true,
		'rewrite' => true
	));	



	register_post_type('services',array(
		'labels' => array(
			'name' => __('Services','business-one'),
			),
		'description' => __('Add Service','business-one'),		
		'public' => true,
		'menu_position' => 100,
		'menu_icon' => 'dashicons-hammer',
		'supports' => array('title'),
		));


	register_post_type('team',array(
		'labels' => array(
			'name' => __('Team','business-one'),
			),
		'description' => __('Add Team','business-one'),		
		'public' => true,
		'menu_position' => 100,
		'menu_icon' => 'dashicons-groups',
		'supports' => array('title'),
		));


	register_post_type('clients',array(
		'labels' => array(
			'name' => __('Clients','business-one'),
			),
		'description' => __('Add Client','business-one'),		
		'public' => true,
		'menu_position' => 100,
		'menu_icon' => 'dashicons-businessman',
		'supports' => array('title'),
		));


}

add_action('init','business_one_post_type');