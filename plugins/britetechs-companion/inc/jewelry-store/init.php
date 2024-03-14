<?php

// Updating Default contents
function bc_jewelry_store_reset_data( $data ){

	$new_data = array(
		'header_phone' => '+235 333 656',
		'header_email' => 'info@example.com',
		'facebook_link' => '#',
		'twitter_link' => '#',
		'linkedin_link' => '#',

		'service_title' => 'Our Services',		
		'service_desc' => 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet.',

		'shop_title' => 'Latest Products',
		'shop_desc' => 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet.',

		'testimonial_title' => 'Customers Reviews',
		'testimonial_desc' => 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet.',

		'team_title' => 'Our Staffs',
		'team_desc' => 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet.',

		'blog_title' => 'Latest News',
		'blog_desc' => 'Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet.',

		'contact_address_title' => 'Address',
		'contact_address' => '135 West Joy Ridge St CA 94110',
		'contact_phone_title' => 'Phone',
		'contact_phone' => '+235 333 656',
		'contact_email_title' => 'Email',
		'contact_email' => 'info@example.com',

		'footer_copyright_text' => 'Copyright [current_year], All Rights Reserved | Powered by [theme_author]',
	);

	return array_merge($data, $new_data);
}
add_filter('jewelry_store_reset_data','bc_jewelry_store_reset_data');

function bc_slider_default_contents(){
	return array(
            array(
                'image'=> array(
                    'url' => plugin_dir_url( __FILE__ ) . 'images/slide-1.jpg',
                    'id' => ''
                ),
                'subtitle'=> __('Save Upto <span>50%</span> OFF','britetechs-companion'),
                'large_text'=> __('Sale On Jewellery Diamonds','britetechs-companion'),
                'small_text'=> __('Lorem ipsum dolor sit amet consectetur adipiscing elited do niam.','britetechs-companion'),
                'btn_text'=> __('Buy Now','britetechs-companion'),
                'btn_link'=> '#',
                'btn_target'=> true,
                'btn_text2'=> '',
                'btn_link2'=> '',
                'btn_target2'=> true,
                'content_align' => 'left'
            ),
            array(
                'image'=> array(
                    'url' => plugin_dir_url( __FILE__ ) . 'images/slide-2.jpg',
                    'id' => ''
                ),
                'subtitle'=> __('Save Upto <span>70%</span> OFF','britetechs-companion'),
                'large_text'=> __('Grab Discount On Necklaces','britetechs-companion'),
                'small_text'=> __('Lorem ipsum dolor sit amet consectetur adipiscing elited do niam.','britetechs-companion'),
                'btn_text'=> __('Buy Now','britetechs-companion'),
                'btn_link'=> '#',
                'btn_target'=> true,
                'btn_text2'=> '',
                'btn_link2'=> '',
                'btn_target2'=> true,
                'content_align' => 'left'
            ),
        );
}

function bc_service_default_contents(){
	return array(
            array(
            'icon'=> 'fa fa-money ',
			'title'=> esc_html__('Money Back', 'britetechs-companion'),
			'desc'=> esc_html__('30 days, money back guarantee', 'britetechs-companion'),
			'link'=> '#',
            ),
            array(
            'icon'=> 'fa fa-truck',
			'title'=> esc_html__('Free Shipping', 'britetechs-companion'),
			'desc'=> esc_html__('Shipping on orders $99', 'britetechs-companion'),
			'link'=> '#',
            ),
            array(
            'icon'=> 'fa fa-diamond',
			'title'=> esc_html__('Special Sale', 'britetechs-companion'),
			'desc'=> esc_html__('Extra 5% off on all items', 'britetechs-companion'),
			'link'=> '#',
            ),
            array(
            'icon'=> 'fa fa-phone',
			'title'=> esc_html__('Support 24 / 7', 'britetechs-companion'),
			'desc'=> esc_html__('Customer Supports', 'britetechs-companion'),
			'link'=> '#',
            ),
        );
}

function bc_team_default_contents(){
	return array(
            array(
            'image'=> array(
					'url' => plugin_dir_url( __FILE__ ) . 'images/team-1.jpg',
					'id' => '51'
				),
			'title'=> 'William Johnson',
			'position'=> 'Co-Founder',
			'facebook_url'=> '#',
			'twitter_url'=> '#',
			'linkedin_url'=> '#',
			'googleplus_url'=> '#',
			'link' => '#',
            ),
            array(
            'image'=> array(
					'url' => plugin_dir_url( __FILE__ ) . 'images/team-2.jpg',
					'id' => ''
				),
			'title'=> 'Madison Davis',
			'position'=> 'Developer',
			'facebook_url'=> '#',
			'twitter_url'=> '#',
			'linkedin_url'=> '#',
			'googleplus_url'=> '#',
			'link' => '#',
            ),
            array(
            'image'=> array(
					'url' => plugin_dir_url( __FILE__ ) . 'images/team-3.jpg',
					'id' => ''
				),
			'title'=> 'James Smith',
			'position'=> 'Co-Founder',
			'facebook_url'=> '#',
			'twitter_url'=> '#',
			'linkedin_url'=> '#',
			'googleplus_url'=> '#',
			'link' => '#',
            ),
            array(
            'image'=> array(
					'url' => plugin_dir_url( __FILE__ ) . 'images/team-4.jpg',
					'id' => ''
				),
			'title'=> 'Daniel Jones',
			'position'=> 'UI Designer',
			'facebook_url'=> '#',
			'twitter_url'=> '#',
			'linkedin_url'=> '#',
			'googleplus_url'=> '#',
			'link' => '#',
            ),
        );
}

function bc_testimonial_default_contents(){
	return array(
            array(
            'image'=> array(
					'url' => plugin_dir_url( __FILE__ ) . 'images/testi-1.jpg',
					'id' => ''
				),
			'title'=> 'Ralph Earl',
			'position'=> 'Co-Founder',
			'desc'=> __('Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet.','britetechs-companion'),
			'link'=> '#',
            ),
            array(
            'image'=> array(
					'url' => plugin_dir_url( __FILE__ ) . 'images/testi-2.jpg',
					'id' => ''
				),
			'title'=> 'Margaret Mead',
			'position'=> 'Co-Founder',
			'desc'=> __('Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet.','britetechs-companion'),
			'link'=> '#',
            ),
            array(
            'image'=> array(
					'url' => plugin_dir_url( __FILE__ ) . 'images/testi-3.jpg',
					'id' => ''
				),
			'title'=> 'Sarah Josepha',
			'position'=> 'Co-Founder',
			'desc'=> __('Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet Lorem ipsum dolor sit amet.','britetechs-companion'),
			'link'=> '#',
            )
        );
}

include('section-all/section-slider.php');
include('section-all/section-service.php');
include('section-all/section-shop.php');
include('section-all/section-testimonial.php');
include('section-all/section-team.php');

function bc_jewelry_store_theme_init(){
	include('customizer/customizer-slider.php');
	include('customizer/customizer-service.php');
	include('customizer/customizer-shop.php');
	include('customizer/customizer-testimonial.php');
	include('customizer/customizer-team.php');
}
add_action('init','bc_jewelry_store_theme_init');

// Recommanded plugins
if( file_exists( bc_plugin_dir . "inc/jewelry-store/required-plugin/index.php") ){

	require(bc_plugin_dir . "inc/jewelry-store/required-plugin/index.php");

}