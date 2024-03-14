<?php

if( ! function_exists('bc_bizcor_theme_default_data') ){
	function bc_bizcor_theme_default_data( $old_data ){
		$data = array(
			'bizcor_topbar_content' => bizcor_header_topbar_default_data(),
            'bizcor_topbar_icons' => bizcor_header_topbar_icons_default_data(),
			'bizcor_nav_btn_title' => __('Get A Qoute','bizcor'),
			'bizcor_nav_btn_link' => '#',
			'bizcor_h_left_title' => __('001-1234-88888','bizcor'),
            'bizcor_h_left_desc' => __('<a href="mailto:info@example.com">info@example.com</a>','bizcor'),
            'bizcor_h_right_title' => __('40 barla sreet 133/2','bizcor'),
            'bizcor_h_right_desc' => __('<a href="#">NewYork City, US</a>','bizcor'),
            'bizcor_footer_above_info_title' => __('Call Us Now','bizcor'),
            'bizcor_footer_above_info_desc' => sprintf(__('<a href="tel:+(90) 207 689 7880">+(90) 207 689 7880</a>','bizcor')),
            'bizcor_footer_above_newsletter_title' => __('Subscribe Now','bizcor'),
            'bizcor_footer_bottom_copyright' => sprintf(__('Copyright 2023, Design by <a href="#">Britetechs</a> All Rights Reserved.','bizcor')),
            'bizcor_slider_content' => bizcor_homepage_slider_default_data(),
            'bizcor_info_content' => bizcor_homepage_info_default_data(),
            'bizcor_service_subtitle' => __('What We Do','bizcor'),
            'bizcor_service_title' => __('Service That Help You Grow','bizcor'),
            'bizcor_service_content' => bizcor_homepage_service_default_data(),
            'bizcor_testimonial_subtitle' => __('Reviews','bizcor'),
            'bizcor_testimonial_title' => __('What Our Client Say About Us','bizcor'),
            'bizcor_testimonial_content' => bizcor_homepage_testimonial_default_data(),
            'bizcor_blog_subtitle' => __('Our Events','bizcor'),
            'bizcor_blog_title' => __('Our Latest News & Events','bizcor'),
            'bizcor_footer_bottom_content' => bizcor_footer_bottom_links_default_data(),
		);

        $data = array_merge( $old_data, $data );
		return $data;
	}
    add_filter('bizcor_default_data','bc_bizcor_theme_default_data', 20);
}

function bizcor_header_topbar_default_data(){
	return  array(
                array(
                    'text' => esc_html__('Welcome To Bizcor','bizcor'),
                ),
                array(
                    'text' => esc_html__('Free Shipping For All Orders','bizcor'),
                )
            );
}

function bizcor_header_topbar_icons_default_data(){
	return  array(
	            array(
	                'icon' => 'fab fa-facebook-f',
	                'link' => '#'
	            ),
	            array(
	                'icon' => 'fab fa-twitter',
	                'link' => '#'
	            ),
	            array(
	                'icon' => 'fab fa-instagram',
	                'link' => '#'
	            ),
	        );
}

function bizcor_footer_bottom_links_default_data(){
    return array(
                array(
                    'title' => __('Tearm & Conditions','bizcor'),                            
                    'link' => '#',          
                    'target' => true,          
                ),
                array(
                    'title' => __('Privacy Policy','bizcor'),                            
                    'link' => '#',
                    'target' => true,          
                ),
            );
}

function bizcor_homepage_slider_default_data(){
	return  array(
                array(
                    'image' => array(
                    	'url'=> bc_plugin_url .'inc/bizcor/img/slider-1.jpg',
                    ),
                    'title' => sprintf(__('We Provide Quality<span><br></span><span class="bg-primary">business <mask id="myMask">service</mask></span>','bizcor')),
                    'desc' => __('Lorem ipsum dolor sit amet elit sed do incit ut let dolore qut sunt in culpa qui officia deserunt mollit anim id est laborum.','bizcor'),
                    'button1_label' => __('Shop Now','bizcor'),
                    'button1_link' => '#',
                    'button1_target' => false,
                    'button2_label' => __('Explore More','bizcor'),
                    'button2_link' => '#',
                    'button2_target' => false,
                ),
                array(
                    'image' => array(
                    	'url'=> bc_plugin_url .'inc/bizcor/img/slider-2.jpg',
                    ),
                    'title' => sprintf(__('We Provide Quality<span><br></span><span class="bg-primary">business <mask id="myMask">service</mask></span>','bizcor')),
                    'desc' => __('Lorem ipsum dolor sit amet elit sed do incit ut let dolore qut sunt in culpa qui officia deserunt mollit anim id est laborum.','bizcor'),
                    'button1_label' => __('Shop Now','bizcor'),
                    'button1_link' => '#',
                    'button1_target' => false,
                    'button2_label' => __('Explore More','bizcor'),
                    'button2_link' => '#',
                    'button2_target' => false,
                ),
            );
}

function bizcor_homepage_info_default_data(){
	return array(
                array(
                    'icon' => 'far fa-lightbulb',
                    'title' => __('Business Planing','bizcor'),                            
                    'desc' => __('Lorem ipsum dolor amet sed do labore et dolore elit','bizcor'),                    
                ),
                array(
                    'icon' => 'fas fa-search',
                    'title' => __('Markets Research','bizcor'),                            
                    'desc' => __('Lorem ipsum dolor amet sed do labore et dolore elit','bizcor'),
                ),
                array(
                    'icon' => 'fas fa-dollar-sign',
                    'title' => __('Investment Planing','bizcor'),                            
                    'desc' => __('Lorem ipsum dolor amet sed do labore et dolore elit','bizcor'),
                ),
            );
}

function bizcor_homepage_service_default_data(){
	return array(
                array(
                    'icon' => 'fa fa-business-time',
                    'image' => array(
                    	'url'=> bc_plugin_url .'inc/bizcor/img/service-1.jpg',
                    ),
                    'title' => __('Strategic Planing','bizcor'),                            
                    'desc' => __('At vero eos et iusto odio atque quos dolores et quas.','bizcor'),                    
                ),
                array(
                    'icon' => 'fa fa-business-time',
                    'image' => array(
                    	'url'=> bc_plugin_url .'inc/bizcor/img/service-2.jpg',
                    ),
                    'title' => __('Business Planing','bizcor'),                            
                    'desc' => __('At vero eos et iusto odio atque quos dolores et quas.','bizcor'),
                ),
                array(
                    'icon' => 'fa fa-business-time',
                    'image' => array(
                    	'url'=> bc_plugin_url .'inc/bizcor/img/service-3.jpg',
                    ),
                    'title' => __('Business Planing','bizcor'),                            
                    'desc' => __('At vero eos et iusto odio atque quos dolores et quas.','bizcor'),
                ),
            );
}

function bizcor_homepage_testimonial_default_data(){
	return array(
                array(
                    'image' => array(
                    	'url'=> bc_plugin_url .'inc/bizcor/img/testi-1.jpg',
                    ),
                    'title' => __('Donald Salvor','bizcor'),                            
                    'designation' => __('CEO & Founder','bizcor'),                
                    'desc' => __('Lorem arcu sit amet accums and eriat aliquam sapien in posuer vinar elit sapin non aen m euIi you are going to  you need to be sure there in the middle  ome form, by injected humof text.','bizcor'),
                    'rating' => '5',           
                ),
                array(
                    'image' => array(
                    	'url'=> bc_plugin_url .'inc/bizcor/img/testi-2.jpg',
                    ),
                    'title' => __('Donald Salvor','bizcor'),                            
                    'designation' => __('CEO & Founder','bizcor'),                
                    'desc' => __('Lorem arcu sit amet accums and eriat aliquam sapien in posuer vinar elit sapin non aen m euIi you are going to  you need to be sure there in the middle  ome form, by injected humof text.','bizcor'),
                    'rating' => '5',
                ),
            );
}