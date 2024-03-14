<?php
/**
 * Industri theme default settings
 *
 * @package Industri WordPress plugin
 *
 * 
 */ 
if ( ! defined( 'ABSPATH' ) ) { exit; }

function amigo_industri_default_settings() {

	return array(		

		'header_layout' => 'one',
		'enable_theme_custom_color' => false,
		'predifine_theme_color' => '#f94b1a',		
		'theme_primary_color' => '#f94b1a',		
		'theme_secondary_color' => '#01013f',	

		'display_header_button' => true,
		'header_button_link' => esc_html__('#','amigo-extensions'),
		'header_button_text' => esc_html__('GET A QUOTE','amigo-extensions'),
		'header_button_icon' => esc_html__('fa-long-arrow-right','amigo-extensions'),
				
		'display_art' => true,		
		'clipart_image_1' => esc_url(AMIGO_PLUGIN_DIR_URL .'includes/industri/assets/images/elements/01.png'),
		'clipart_image_2' => esc_url(AMIGO_PLUGIN_DIR_URL .'includes/industri/assets/images/elements/02.png'),
		'clipart_image_3' => esc_url(AMIGO_PLUGIN_DIR_URL .'includes/industri/assets/images/elements/03.png'),
		'clipart_image_4' => esc_url(AMIGO_PLUGIN_DIR_URL .'includes/industri/assets/images/elements/04.png'),
		'clipart_image_5' => esc_url(AMIGO_PLUGIN_DIR_URL .'includes/industri/assets/images/elements/05.png'),
		'clipart_image_6' => esc_url(AMIGO_PLUGIN_DIR_URL .'includes/industri/assets/images/elements/06.png'),
		'clipart_image_7' => esc_url(AMIGO_PLUGIN_DIR_URL .'includes/industri/assets/images/elements/07.png'),
		'clipart_image_8' => esc_url(AMIGO_PLUGIN_DIR_URL .'includes/industri/assets/images/elements/08.png'),

		'display_preloader' => true,
		'sticky_header' => true,
		'cart_icon' => esc_html__('fa-shopping-basket','amigo-extensions'),
		'display_cart_button' => true,
		'display_navigation_search_button' => true,
		
		'primary_menu_search_button_icon' => esc_html__('fa fa-search','amigo-extensions'),
		'primary_menu_search_button_overlay_label' => esc_html__('Plesse Search Here','amigo-extensions'),
		'primary_menu_search_button_overlay_text' => esc_html__('Lorem Ipsum is simply dummy text of the printing. Lorem Ipsum has been when an unknown printer took.','amigo-extensions'),

		'is_header_top_bar' => true,		
		'header_above_text' => esc_html__(' Lorem ipsum dolor sit amt, consectetur.','amigo-extensions'),		
		'header_second_text_icon' => esc_html__('fa fa-clock','amigo-extensions'),
		'header_schedule_text' => esc_html__(' Mon to Sat: 10 am - 6 pm','amigo-extensions'),
		'display_social_icons' => true,
		'display_header_contact_detail' => true,

		// breadcrumb
		'display_breadcrumb' => true,
		'breadcrumb_min_height' => esc_html__('400', 'amigo-extensions'),
		'breadcrumb_bg_image' => esc_url(AMIGO_PLUGIN_DIR_URL .'includes/industri/assets/images/breadcrumb-bg.jpg'),
		//404 
		'404_title' => esc_html__( '404', 'amigo-extensions' ),
		'404_subtitle' => esc_html__( 'Oops! that page can not be found', 'amigo-extensions' ),
		'404_text' => esc_html__( 'Lorem Ipsum is simply dummy text of the printing. Lorem Ipsum has been when .', 'amigo-extensions' ),
		
		// info section
		'display_info_section' => true,
		'display_info_clm' => true,
		'info_clm_icon' => esc_html__('fa fa-phone','amigo-extensions'),
		'info_clm_title' => esc_html__('Free Advice','amigo-extensions'),
		'info_clm_subtitle' => esc_html__('452-643-3483','amigo-extensions'),
		'info_clm_text' => esc_html__('Mon Fri 7.00AM - 5.00AM','amigo-extensions'),

		// about section
		'display_about_section' => true,		
		'about_title' => esc_html__( 'About Company', 'amigo-extensions' ),
		'about_subtitle' => esc_html__( 'Your Partner For Future Innovation', 'amigo-extensions' ),
		'about_text' => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration,', 'amigo-extensions' ),	
		'about_image_first' => esc_url( AMIGO_PLUGIN_DIR_URL.'includes/industri/assets/images/about.jpg' ),
		'about_button_text' => esc_html__( 'Discover More', 'amigo-extensions' ),
		'about_button_link' => esc_html__( '#', 'amigo-extensions' ),
		'display_about_overlay' => true,
		'about_overlay_title' => esc_html__( '50+', 'amigo-extensions' ),
		'about_overlay_subtitle' => esc_html__( 'YEAR IN INDUSTRY', 'amigo-extensions' ),
		'about_overlay_video_text' => esc_html__( 'Play Video', 'amigo-extensions' ),
		'about_overlay_video_link' => esc_html__( 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', 'amigo-extensions' ),

		// services
		'display_service_section' => true,
		'service_title' => esc_html__( 'Our Services', 'amigo-extensions' ),
		'service_subtitle' => esc_html__( 'Service We Provider', 'amigo-extensions' ),
		'service_text' => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form,', 'amigo-extensions' ),	
		'service_button_more' => esc_html__( 'Load More', 'amigo-extensions' ),	
		'service_button_link' => esc_html__( '#', 'amigo-extensions' ),	

		// c2a
		'display_c2a_section' => true,
		'c2a_title' => esc_html__( 'Get In Touch, Or Create An Account', 'amigo-extensions' ),	
		'c2a_text' => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form,', 'amigo-extensions' ),	
		'c2a_button_text' => esc_html__( 'Get In Touch', 'amigo-extensions' ),	
		'c2a_button_link' => esc_html__( '#', 'amigo-extensions' ),	
		'c2a_bg_image' => AMIGO_PLUGIN_DIR_URL.'includes/industri/assets/images/c2a-bg.jpg',	
				

		// blog
		'display_blog_section' => true,
		'blog_title' => esc_html__( 'Our Blog', 'amigo-extensions' ),	
		'blog_subtitle' => esc_html__( 'Get The Latest Updates', 'amigo-extensions' ),	
		'blog_text' => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form,', 'amigo-extensions' ),

		
		// copyright
		'footer_copyright' => esc_html__('[site_title] [year] All Rights Reserved &copy; By [author]', 'amigo-extensions' ),
	);

}
 
/**
* customizer office detail items settings
*
* @since industri 1.0.0
*
* @return void
*/
function amigo_industri_default_office_contact_items(){

	$default = json_encode(array(
		array(			
			'icon_value'      => esc_html__('fa-envelope', 'amigo-extensions'),				
			'text'           =>esc_html__('info@yourcompany.com', 'amigo-extensions'),		
			'id'              => 'office_contact_item_01',
		),
		array(	
			'icon_value'      => esc_html__('fa-phone', 'amigo-extensions'),				
			'text'           =>esc_html__('+123-456-7890', 'amigo-extensions'),				
			'id'              => 'office_contact_item_02',
		),

		array(	
			'icon_value'      => esc_html__('fa-clock-o', 'amigo-extensions'),			
			'text'           =>esc_html__('Mon Fri 7.00AM - 5.00AM', 'amigo-extensions'),			
			'id'              => 'office_contact_item_03',
		),		
		
	));

	return $default;
}

/**
* customizer header social icons settings
*
* @since Industri 1.0.0
*
* @return void
*/
function amigo_industri_default_social_icons(){

	$default = json_encode(array(
		array(			
			'icon_value'      => esc_html__('fa-facebook', 'amigo-extensions'),
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),			
			'id'              => 'social_icon_01',
		),
		array(	
			'icon_value'      => esc_html__('fa-twitter', 'amigo-extensions'),		
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),					
			'id'              => 'social_con_02',
		),

		array(	
			'icon_value'      => esc_html__('fa-google', 'amigo-extensions'),		
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),			
			'id'              => 'social_con_03',
		),

		array(	
			'icon_value'      => esc_html__('fa-instagram', 'amigo-extensions'),		
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),			
			'id'              => 'social_con_04',
		),
		
	));

	return $default;
}

/**
 * default slider settings
 *
 * @package Industri WordPress plugin
 *
 * 
 */ 
function amigo_industri_slider_section_default() {

	return json_encode(array(
		array(
			'image_url'       => AMIGO_PLUGIN_DIR_URL . 'includes/industri/assets/images/slider/slider-01.png',
			'title'           => esc_html__( 'best industry company', 'amigo-extensions' ),
			'subtitle'         => esc_html__( 'Make dreams come to life', 'amigo-extensions' ),
			'text'            => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form,', 'amigo-extensions' ),
			'text2'	  =>  esc_html__( 'Learn More', 'amigo-extensions' ),
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),
			'button_second'	  =>  esc_html__( 'How It Works', 'amigo-extensions' ),
			'link2'	  =>  esc_html__( 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', 'amigo-extensions' ),
			'icon_value'	  =>  esc_html__( 'fa-bell', 'amigo-extensions' ),
			"slide_align" => "left", 
			'id'              => 'home_slider_01',
		),

		array(
			'image_url'       => AMIGO_PLUGIN_DIR_URL . 'includes/industri/assets/images/slider/slider-02.png',
			'title'           => esc_html__( 'best industry company', 'amigo-extensions' ),
			'subtitle'         => esc_html__( 'Make dreams come to life', 'amigo-extensions' ),
			'text'            => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form,', 'amigo-extensions' ),
			'text2'	  =>  esc_html__( 'Learn More', 'amigo-extensions' ),
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),
			'button_second'	  =>  esc_html__( 'How It Works', 'amigo-extensions' ),
			'link2'	  =>  esc_html__( 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', 'amigo-extensions' ),
			'icon_value'	  =>  esc_html__( 'fa-bell', 'amigo-extensions' ),
			"slide_align" => "left", 
			'id'              => 'home_slider_02',
		),

		array(
			'image_url'       => AMIGO_PLUGIN_DIR_URL . 'includes/industri/assets/images/slider/slider-03.png',
			'title'           => esc_html__( 'best industry company', 'amigo-extensions' ),
			'subtitle'         => esc_html__( 'Make dreams come to life', 'amigo-extensions' ),
			'text'            => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form,', 'amigo-extensions' ),
			'text2'	  =>  esc_html__( 'Learn More', 'amigo-extensions' ),
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),
			'button_second'	  =>  esc_html__( 'How It Works', 'amigo-extensions' ),
			'link2'	  =>  esc_html__( 'https://www.youtube.com/watch?v=a3ICNMQW7Ok', 'amigo-extensions' ),
			'icon_value'	  =>  esc_html__( 'fa-bell', 'amigo-extensions' ),
			"slide_align" => "left", 
			'id'              => 'home_slider_03',
		),


	)
);

}

/**
* customizer info section item default settings
*
* @since Industri 1.0.0
*
* @return void
*/
function amigo_industri_default_info_items(){

	$default = json_encode(array(
		array(			
			'title'           => esc_html__( 'Mechanical Engineering', 'amigo-extensions' ),
			'subtitle'           => esc_html__( '1', 'amigo-extensions' ),
			'text'           => esc_html__( 'Lorem ipsum dolor sit amet', 'amigo-extensions' ),
			'icon_value'      => esc_html__( 'fa-random' , 'amigo-extensions' ),					
			'id'              => 'info_item_01',
		),

		array(			
			'title'           => esc_html__( 'Rules Of Business', 'amigo-extensions' ),
			'subtitle'           => esc_html__( '2', 'amigo-extensions' ),
			'text'           => esc_html__( 'Lorem ipsum dolor sit amet', 'amigo-extensions' ),
			'icon_value'      => esc_html__( 'fa-cog' , 'amigo-extensions' ),			
			'id'              => 'info_item_02',
		),		
		
	));

	return $default;
}

/**
* customizer about section item default settings
*
* @since Industri 1.0
*
* @return void
*/
function amigo_industri_default_about_items(){

	$default = json_encode(array(
		array(			
			'title'           => esc_html__( 'Save Your money', 'amigo-extensions' ),
			'icon_value'      => esc_html__( 'fa-money' , 'industri-pro' ),
			'text'           => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration', 'amigo-extensions' ),			
			'id'              => 'about_item_01',
		),

		array(			
			'title'           => esc_html__( 'Speed in work process', 'amigo-extensions' ),
			'icon_value'      => esc_html__( 'fa-bar-chart' , 'industri-pro' ),
			'text'           => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration', 'amigo-extensions' ),			
			'id'              => 'about_item_02',
		),
		
	));

	return apply_filters('industri_default_about_items', $default);
}


/**
* customizer service section item default settings
*
* @since Industri 1.0
*
* @return void
*/
function amigo_industri_default_service_items(){

	$default = json_encode(array(
		array(			
			'title'           => esc_html__( 'Metal Industri', 'amigo-extensions' ),
			'text'           => esc_html__( 'Some quick example text to build on the card title and make up the bulk of the cards content.', 'amigo-extensions' ),
			'text2'	  =>  esc_html__( 'Read More', 'amigo-extensions' ),			
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),
			'icon_value'      => esc_html__( 'fa fa-home' , 'amigo-extensions' ),			
			'open_new_tab'      => true,			
			'id'              => 'service_item_01',
		),

		array(			
			'title'           => esc_html__( 'Petrol & Gas', 'amigo-extensions' ),
			'text'           => esc_html__( 'Some quick example text to build on the card title and make up the bulk of the cards content.', 'amigo-extensions' ),
			'text2'	  =>  esc_html__( 'Read More', 'amigo-extensions' ),			
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),
			'open_new_tab'      => true,
			'icon_value'      => esc_html__( 'fa fa-cogs' , 'amigo-extensions' ),			
			'id'              => 'service_item_02',
		),

		array(			
			'title'           => esc_html__( 'Power & energy
				', 'amigo-extensions' ),
			'text'           => esc_html__( 'Some quick example text to build on the card title and make up the bulk of the cards content.', 'amigo-extensions' ),
			'text2'	  =>  esc_html__( 'Read More', 'amigo-extensions' ),			
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),
			'open_new_tab'      => true,
			'icon_value'      => esc_html__( 'fa fa-eye-slash' , 'amigo-extensions' ),			
			'id'              => 'service_item_03',
		),
		
		
	));

	return $default;
}


/**
* customizer footer above default settings
*
* @since Industri 1.0
*
* @return void
*/
function amigo_industri_default_footer_above(){

	$default = json_encode(array(
		array(			
			'title'           =>esc_html__('203 Fak, California, USA', 'amigo-extensions'),			
			'icon_value'      => esc_html__('fa-map', 'amigo-extensions'),
			'id'              => 'footer_top_item_01',
		),

		array(			
			'title'           =>esc_html__('+2 392 3929 210', 'amigo-extensions'),			
			'icon_value'      => esc_html__('fa-phone', 'amigo-extensions'),
			'id'              => 'footer_top_item_02',
		),

		array(			
			'title'           =>esc_html__('email@domain.com', 'amigo-extensions'),			
			'icon_value'      => esc_html__('fa-envelope', 'amigo-extensions'),
			'id'              => 'footer_top_item_03',
		),		
		
	));

	return $default;
}


 ?>