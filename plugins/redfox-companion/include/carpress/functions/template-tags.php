<?php
function btc_carpress_default_data($data){
	$new_data = array(
		'topbar' => true,
		'topbar_heading' => __('Have any questions?','bangalorethemes-companion'),
		'topbar_icon1' => 'fa fa-envelope',
		'topbar_text1' => 'info@example.com',
		'topbar_icon2' => 'fa fa-phone',
		'topbar_text2' => '+433-8583-0868',
		'facebook_url' => '#',
		'twitter_url' => '#',
		'googleplus_url' => '#',
		'linkedin_url' => '#',

		'site_layout' => false,
		'theme_color' => '#F72E2E',
		'theme_color_custom_show' => false,
		'theme_color_custom_color' => '#F72E2E',
		'primary_sidebar' => 'right',
		'btt_disable' => false,
		'animation_effect' => true,
		'googlefonts' => true,
		'single_post_meta' => true,
		'single_post_image' => true,
		
		'slider_enable' => true,
		'slider_effect' => 'slide',
		'slider_speed' => '1500',
		'slider_duration' => '3000',
		'slider_largetext' => __('BMW<span class="slider-caption-title-no">7</span>series<span class="slider-caption-label">model 2018</span>','bangalorethemes-companion'),
		'slider_smalltext' => __('<span class="slider-caption-price-currency">$</span>
                        <span class="slider-caption-price-number">375</span>

                        <span class="slider-caption-price-inner">
                        	<span class="slider-caption-price-title">Monthly</span>
                        	<span class="slider-caption-price-subtitle">Lowest Markup</span>
                        </span>','bangalorethemes-companion'),
		'slider_button_text' => esc_html__('Read More','bangalorethemes-companion'),
		'slider_button_link' => '',
		'slider_button_target' => false,
		'slider_media' => json_encode( array(
						array(
							'image'=> array(
								'url' => get_template_directory_uri().'/images/slide1.jpg',
								'id' => ''
							)
						)
					) ),
		
		'service_enable' => true,
		'service_title' => __('Our Services','bangalorethemes-companion'),
		'service_subtitle' => '',
		'service_layout' => '4',
		'services' => '',
		
		'blog_enable' => true,
		'blog_title' => __('Latest Blogs','bangalorethemes-companion'),
		'blog_subtitle' => '',
		'news_layout' => '4',
		'news_no' => '3',
		'news_cat' => 0,
		'news_orderby' => 0,
		'news_order' => 'desc',
		
		'p_fontsize' => 15,	
		'm_fontsize' => 15,		
		'h1_fontsize' => 36,
		'h2_fontsize' => 28,
		'h3_fontsize' => 24,
		'h4_fontsize' => 22,
		'h5_fontsize' => 18,
		'h6_fontsize' => 16,
	);

	$data = array_merge($data,$new_data);

	return $data;
}
add_filter('carpress_default_data','btc_carpress_default_data');
?>