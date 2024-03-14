<?php 
if( !defined('ABSPATH') ) {
	echo "Well done! Try Again";
	die();
}
/*
* This files adding customizer settings to the Customizer.
*/
add_action( 'customize_register', 'related_posts_plugin_panel' );
function related_posts_plugin_panel ($wp_customize) {

	$wp_customize->add_panel('related_posts_panel', array(

		'priority' 			=> 40,
		'title' 			=> __('Related Posts For Genesis', 'related-posts-for-genesis'),
		'description' 		=> __('Configure Related Posts plugin provided by WebsiteGuider'),

	));


	// Related Posts Title Section
	$wp_customize->add_section ('related_posts_title_front', array(

		'title' 			=> __('Related Post Title', 'related-posts-for-genesis'),
		'description' 		=> __('You can change Related Posts title here else default will be used.', 'related-posts-for-genesis'),
		'panel' 			=> 'related_posts_panel',

	));

	$wp_customize->add_setting('related_posts_title');

	$wp_customize->add_control ('related_posts_title', array(

		'label' 			=> 'Related Posts Title',
		'description' 		=> 'Enter text below to change the title',
		'type' 				=> 'text',
		'section' 			=> 'related_posts_title_front',
		'settings' 			=> 'related_posts_title',

	));


	//Related Post number

	$wp_customize->add_section ('related_posts_number_front', array(
		'title' 			=> __('Related Posts Number', 'related-posts-for-genesis'),
		'description'		=> __('You can change Related Posts Number here else default will be used.', 'related-posts-for-genesis'),
		'panel'				=> 'related_posts_panel',
	));

	$wp_customize->add_setting('related_posts_number', array(

		'capability'		=> 'edit_theme_options', 
		'sanitize_callback'	=> 'rpfg_sanitize_number',
		'default'			=> 3,

	));

	$wp_customize->add_control ( 'related_posts_number', array(
		'label' 		=> 'Related Posts Number',
		'description' 	=> 'Enter number below to change the number of related posts shown',
		'type' 			=> 'number',
		'section' 		=> 'related_posts_number_front',
	));

	//Related Posts Category and tag check

	$wp_customize->add_section('rpfg_category_and_tag_check', array(

			'title'			=> __( 'Category and Tags', 'related-posts-for-genesis' ),
			'description'	=> __( 'Manage Category and Tags text here.', 'related-posts-for-genesis' ),
			'panel'			=> 'related_posts_panel',

	));


	/*==== Category Check ====*/

	$wp_customize->add_setting('rpfg_cat_setting', array(

		'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'check_cat_status',
		'default'			=> true,

	));

	$wp_customize->add_control('rpfg_cat_setting', array(

		'type'				=> 'checkbox',
		'section'			=> 'rpfg_category_and_tag_check',
		'label'				=> __('Show Categories', 'related-posts-for-genesis'),
		'description'		=> __('Check this box if you want to show categories text', 'related-posts-for-genesis'),

	));

	/*==== Tag Check ====*/

		$wp_customize->add_setting('rpfg_tag_setting', array(

		'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'check_tag_status',
		'default'			=> true,

	));

	$wp_customize->add_control('rpfg_tag_setting', array(

		'type'				=> 'checkbox',
		'section'			=> 'rpfg_category_and_tag_check',
		'label'				=> __('Show Tags', 'related-posts-for-genesis'),
		'description'		=> __('Check this box if you want to show tags text', 'related-posts-for-genesis'),

	));

	//Related Posts Category and tag check

	$wp_customize->add_section('rpfg_date_check', array(

			'title'			=> __( 'Date', 'related-posts-for-genesis' ),
			'description'	=> __( 'Manage Date text here.', 'related-posts-for-genesis' ),
			'panel'			=> 'related_posts_panel',

	));

	/*==== Date Check ====*/

	$wp_customize->add_setting('rpfg_date_setting', array(

		'capability'		=> 'edit_theme_options',
		'sanitize_callback'	=> 'check_date_status',
		'default'			=> true,

	));

	$wp_customize->add_control('rpfg_date_setting', array(

		'type'				=> 'checkbox',
		'section'			=> 'rpfg_date_check',
		'label'				=> __('Show Date', 'related-posts-for-genesis'),
		'description'		=> __('Check this box if you want to show Date text', 'related-posts-for-genesis'),

	));
}
