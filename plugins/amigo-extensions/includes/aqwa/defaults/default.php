<?php 
/**
 * Amigo theme default settings
 *
 * 
 */ 
if ( ! defined( 'ABSPATH' ) ) { exit; }

function aqwa_default_settings( $filter = true ) {

	$default = array(
		'aqwa_site_header_style' => 'one',
		
		'aqwa_display_primary_menu_link_button' => true,
		'aqwa_primary_menu_link_button_link' => esc_html__('#','amigo-extensions'),
		'aqwa_primary_menu_link_button_text' => esc_html__(' GET A QUOTE','amigo-extensions'),

		'aqwa_display_primary_menu_search_button' => true,
		'aqwa_primary_menu_search_button_overlay_label' => esc_html__('Plesse Search Here','amigo-extensions'),
		'aqwa_primary_menu_search_button_overlay_text' => esc_html__('Lorem Ipsum is simply dummy text of the printing. Lorem Ipsum has been when an unknown printer took.','amigo-extensions'),

		'aqwa_is_header_top_bar' => true,		
		'aqwa_header_above_text' => esc_html__(' Lorem ipsum dolor sit amt, consectetur.','amigo-extensions'),		
		'aqwa_header_schedule_text' => esc_html__(' Mon to Sat: 10 am - 6 pm','amigo-extensions'),
		'aqwa_display_social_icons' => true,
		'aqwa_display_header_contact_detail' => true,
	);

	if( $filter ) {
		return apply_filters( 'aqwa_default_settings', $default );
	}

	return $default;
}

/**
* customizer header social icons settings
*
* @since Aqwa 1.0
*
* @return void
*/
function aqwa_default_social_icons(){

	$default = json_encode(array(
		array(			
			'icon_value'      => esc_html__('fab fa-facebook', 'amigo-extensions'),
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),			
			'id'              => 'aqwa_social_icon_01',
		),
		array(	
			'icon_value'      => esc_html__('fab fa-twitter', 'amigo-extensions'),		
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),					
			'id'              => 'aqwa_social_con_02',
		),

		array(	
			'icon_value'      => esc_html__('fab fa-google', 'amigo-extensions'),		
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),			
			'id'              => 'aqwa_social_con_03',
		),

		array(	
			'icon_value'      => esc_html__('fab fa-slack', 'amigo-extensions'),		
			'link'	  =>  esc_html__( '#', 'amigo-extensions' ),			
			'id'              => 'aqwa_social_con_04',
		),
		
	));

	return $default;
}

/**
* customizer header default settings
*
* @since Aqwa 1.0
*
* @return void
*/
function aqwa_default_header_contact_items(){

	$default = json_encode(array(
		array(			
			'title'           =>esc_html__('Address', 'amigo-extensions'),
			'text'           =>esc_html__('Park St, Lundon, N1 1px', 'amigo-extensions'),
			'icon_value'      => esc_html__('fa fa-map-marker-alt', 'amigo-extensions'),
			'id'              => 'header_contact_01',
		),
		array(			
			'title'           => esc_html__('Call US', 'amigo-extensions'),
			'text'           =>esc_html__('+1 0146735813', 'amigo-extensions'),
			'icon_value'      => esc_html__('fa fa-phone-volume', 'amigo-extensions'),
			'id'              => 'header_contact_02',
		),

		array(			
			'title'           =>  esc_html__('Send us', 'amigo-extensions'),
			'text'           =>esc_html__('demo@gmail.com', 'amigo-extensions'),
			'icon_value'      => esc_html__('fa fa-envelope', 'amigo-extensions'),
			'id'              => 'header_contact_03',
		),
		
	));

	return $default;
}

/**
* customizer footer default settings
*
* @since Aqwa 1.0
*
* @return void
*/
function aqwa_footer_section_default( $filter = true ) {

	$default = array(

		'aqwa_display_footer_contact_detail' => true,		

		'aqwa_display_footer_above_item' => true,

		'display_one_footer_above_item' => true,
		'one_footer_above_item_icon' => esc_html__('fas fa-bullhorn', 'amigo-extensions'),
		'one_footer_above_item_title' => esc_html__('Lorem Ipsum is simply dummy', 'amigo-extensions'),
		'one_footer_above_item_sub_title' => esc_html__('Some quick example text to build on the card title and make up the bulk of the cards content.', 'amigo-extensions'),

		'display_two_footer_above_item' => true,
		'two_footer_above_item_icon' => esc_html__('fas fa-chart-line', 'amigo-extensions'),
		'two_footer_above_item_title' => esc_html__('Lorem Ipsum is simply dummy', 'amigo-extensions'),
		'two_footer_above_item_sub_title' => esc_html__('Some quick example text to build on the card title and make up the bulk of the cards content.', 'amigo-extensions'),

	);

	if( $filter ) {
		return apply_filters( 'aqwa_footer_section_default', $default );
	}

	return $default;
}

/**
* customizer header default settings
*
* @since Aqwa 1.0
*
* @return void
*/
function aqwa_default_footer_contact_items(){

	$default = json_encode(array(
		array(			
			'title'           =>esc_html__('Get Direction', 'amigo-extensions'),
			'text'           =>esc_html__('Park St, Lundon, N1 1px', 'amigo-extensions'),
			'icon_value'      => esc_html__('fa fa-map-marker-alt', 'amigo-extensions'),
			'id'              => 'footer_contact_01',
		),
		array(			
			'title'           => esc_html__('Call Us now', 'amigo-extensions'),
			'text'           =>esc_html__('+1 0146735813', 'amigo-extensions'),
			'icon_value'      => esc_html__('fa fa-phone-volume', 'amigo-extensions'),
			'id'              => 'footer_contact_02',
		),

		array(			
			'title'           =>  esc_html__('Droup Us a line', 'amigo-extensions'),
			'text'           =>esc_html__('demo@gmail.com', 'amigo-extensions'),
			'icon_value'      => esc_html__('fa fa-envelope', 'amigo-extensions'),
			'id'              => 'footer_contact_03',
		),
		
	));

	return $default;
}


/**
 * default slider settings
 *
 * 
 */ 
function aqwa_slider_section_default( $filter = true ) {

	return json_encode(array(
		array(
			'image_url'       => AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/images/slider/slider-01.jpg',
			'title'           => esc_html__( 'We Provide a Solution for Good Business', 'amigo-extensions' ),			
			'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis , nascetur ridi culus mus', 'amigo-extensions' ),
			'text2'	  =>  esc_html__( 'Explore More', 'amigo-extensions' ),			
			'link2'	  =>  esc_html__( '#', 'amigo-extensions' ),
			"slide_align" => "left", 
			'id'              => 'home_slider_01',
		),

		array(
			'image_url'       => AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/images/slider/slider-02.jpg',
			'title'           => esc_html__( 'The Best Solutions to Start Your Own Business', 'amigo-extensions' ),			
			'text'            => esc_html__( 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis , nascetur ridi culus mus', 'amigo-extensions' ),
			'text2'	  =>  esc_html__( 'Explore More', 'amigo-extensions' ),					
			'link2'	  =>  esc_html__( '#', 'amigo-extensions' ),
			"slide_align" => "right", 
			'id'              => 'home_slider_02',
		),		
	)
);

}

// about section default settings
function aqwa_about_section_default( $filter = true ) {

	$default = array(
		'aqwa_display_about_section' => true,		
		'aqwa_about_section_title' => esc_html__( 'About Us Company', 'amigo-extensions' ),
		'aqwa_about_section_subtitle' => esc_html__( 'More Than 35+ Years, We Provide Business Solutions', 'amigo-extensions' ),
		'aqwa_about_section_text' => esc_html__( 'Lorem Ipsum is simply dummy text of the printing. Lorem Ipsum has been when an unknown printer took a galley of type and scrambled it to make a type specimen book.', 'amigo-extensions' ),	
		'aqwa_about_section_image_one' => esc_url( AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/images/about/about-01.jpg' ),
		'aqwa_about_section_image_two' => esc_url( AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/images/about/about-02.jpg' ),
		'aqwa_display_about_section_youtube' => true,
		'aqwa_about_section_youtube_link' => esc_url( 'https://www.youtube.com/watch?v=9xwazD5SyVg&feature=youtu.be' ),
	);

	if( $filter ) {
		return apply_filters( 'aqwa_about_section_default', $default );
	}

	return $default;
}

function aqwa_default_about_items(){

	$default = json_encode(array(
		array(			
			'title'           => esc_html__( 'Financial Planning', 'amigo-extensions' ),
			'icon_value'      => esc_html__( 'fa fa-university', 'amigo-extensions' ),
			'id'              => 'aqwa_about_01',
		),
		array(			
			'title'           => esc_html__( 'Business Growth', 'amigo-extensions' ),
			'icon_value'      => esc_html__( 'fa fa-building ', 'amigo-extensions' ),
			'id'              => 'aqwa_about_02',
		),

		array(			
			'title'           => esc_html__( 'Business Strategy', 'amigo-extensions' ),
			'icon_value'      => esc_html__( 'fa fa-sign-out-alt', 'amigo-extensions' ),
			'id'              => 'aqwa_about_03',
		),

		array(			
			'title'           => esc_html__( 'Employment Services', 'amigo-extensions' ),
			'icon_value'      => esc_html__( 'fa fa-laptop', 'amigo-extensions' ),
			'id'              => 'aqwa_about_04',
		),
	));

	return apply_filters('aqwa_default_about_items', $default);
}

/**
* customizer service section header default settings
*
* @since Aqwa 1.0
*
* @return void
*/
function aqwa_service_section_default( $filter = true ) {

	$default = array(
		'aqwa_display_service_section' => true,		
		'aqwa_service_section_title' => esc_html__( 'our dedicated services', 'amigo-extensions' ),
		'aqwa_service_section_subtitle' => esc_html__( 'Our Exclusive Services', 'amigo-extensions' ),
		'aqwa_service_section_text' => esc_html__( 'Lorem Ipsum is simply dummy text of the printing. Lorem Ipsum has been when an unknown printer took a galley of type and scrambled it to make a type specimen book', 'amigo-extensions' ),		
		'aqwa_service_load_button_text' => esc_html__( 'Load More', 'amigo-extensions' ),	
		'aqwa_service_section_column' => esc_attr('col-lg-4'),		
	);

	if( $filter ) {
		return apply_filters( 'aqwa_service_section_default', $default );
	}

	return $default;
}

/**
* customizer service section item default settings
*
* @since Aqwa 1.0
*
* @return void
*/
function aqwa_default_service_items(){

	$default = json_encode(array(
		array(			
			'title'           => esc_html__( 'Financial Planning', 'amigo-extensions' ),
			'text'           => esc_html__( 'Some quick example text to build on the card title and make up the bulk of the cards content.', 'amigo-extensions' ),
			'text2'	  =>  esc_html__( 'Read More', 'amigo-extensions' ),			
			'link2'	  =>  esc_html__( '#', 'amigo-extensions' ),
			'icon_value'      => esc_html__( 'fa fa-university' , 'amigo-extensions' ),
			'image_url'       => AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/images/service/service-01.jpg',
			'id'              => 'service_item_01',
		),
		array(			
			'title'           => esc_html__( 'Business Growth', 'amigo-extensions' ),
			'text'           => esc_html__( 'Some quick example text to build on the card title and make up the bulk of the cards content.', 'amigo-extensions' ),
			'text2'	  =>  esc_html__( 'Read More', 'amigo-extensions' ),			
			'link2'	  =>  esc_html__( '#', 'amigo-extensions' ),
			'icon_value'      => esc_html__( 'fa fa-gem' , 'amigo-extensions' ),
			'image_url'       => AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/images/service/service-02.jpg',
			'id'              => 'service_item_02',
		),

		array(			
			'title'           => esc_html__( 'Business Strategy', 'amigo-extensions' ),
			'text'           => esc_html__( 'Some quick example text to build on the card title and make up the bulk of the cards content.', 'amigo-extensions' ),
			'text2'	  =>  esc_html__( 'Read More', 'amigo-extensions' ),			
			'link2'	  =>  esc_html__( '#', 'amigo-extensions' ),
			'icon_value'      => esc_html__( 'fa fa-sign-out-alt' , 'amigo-extensions' ),
			'image_url'       => AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/images/service/service-03.jpg',
			'id'              => 'service_item_03',
		),
		
	));

	return $default;
}


/**
* customizer info section item default settings
*
* @since Aqwa 1.0
*
* @return void
*/
function aqwa_info_section_default( $filter = true ){

	$default = array(
		'aqwa_display_info_section' => true,		
		'aqwa_info_section_style' => 'one',			
	);

	if( $filter ) {
		return apply_filters( 'aqwa_info_section_default', $default );
	}

	return $default;
}


/**
* customizer info section item default settings
*
* @since Aqwa 1.0
*
* @return void
*/
function aqwa_default_info_items(){

	$default = json_encode(array(
		array(			
			'title'           => esc_html__( 'Business Consulting', 'aqwa-pro' ),
			'text'           => esc_html__( 'Some quick example text to build on the card title and make up the bulk of the cards content.', 'aqwa-pro' ),
			'icon_value'      => esc_html__( 'fab fa-accusoft' , 'aqwa-pro' ),
			'text2'	  =>  esc_html__( 'Read More', 'aqwa-pro' ),			
			'link2'	  =>  esc_html__( '#', 'aqwa-pro' ),
			
			'image_url'       => AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/images/slider/slider-02.jpg',
			'id'              => 'info_item_01',
		),

		array(			
			'title'           => esc_html__( 'Finance Management', 'aqwa-pro' ),
			'text'           => esc_html__( 'Some quick example text to build on the card title and make up the bulk of the cards content.', 'aqwa-pro' ),
			'icon_value'      => esc_html__( 'fas fa-dolly' , 'aqwa-pro' ),
			'text2'	  =>  esc_html__( 'Read More', 'aqwa-pro' ),			
			'link2'	  =>  esc_html__( '#', 'aqwa-pro' ),
			
			'image_url'       => AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/images/slider/slider-02.jpg',
			'id'              => 'info_item_02',
		),

		array(			
			'title'           => esc_html__( 'Business Management ', 'aqwa-pro' ),
			'text'           => esc_html__( 'Some quick example text to build on the card title and make up the bulk of the cards content.', 'aqwa-pro' ),
			'icon_value'      => esc_html__( 'fas fa-briefcase' , 'aqwa-pro' ),
			'text2'	  =>  esc_html__( 'Read More', 'aqwa-pro' ),			
			'link2'	  =>  esc_html__( '#', 'aqwa-pro' ),
			
			'image_url'       => AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/images/slider/slider-02.jpg',
			'id'              => 'info_item_03',
		),				
		
	));

	return $default;
}