<?php
if( ! function_exists('spawp_premium_theme_default_data') ){

	function spawp_premium_theme_default_data( $old_data ){

		$data = array(
            'slider_show' => true,
      		'slider_nav_show' => true,
      		'slider_pagination_show' => true,
      		'slider_mouse_drag' => true,
      		'slider_smart_speed' => 1000,
      		'slider_scroll_speed' => 2500,
      		'slider_animatein' => '',
      		'slider_animateout' => '',
      		'slider_container_width' => 'container',
      		'slider_content' => '',
                  'slider_subtitle_color' => '',
                  'slider_title_color' => '#ffffff',
                  'slider_desc_color' => '#ffffff',
                  'slider_overlay_show' => true,
                  'slider_overlay_color' => '',
                  'service_content' => '',
		      'feature_content' => '',
		      'team_content' => '',
		      'testimonial_content' => '',
			);

			$section_names = array(
					'service',
					'feature',
					'team',
					'testimonial',
				);

			foreach ($section_names as $key => $name) {
				$data[$name.'_show'] = true;
				$data[$name.'_subtitle'] = __('Section Subtitle','spawp');
	                  $data[$name.'_subtitle_color'] = '';
				$data[$name.'_title'] = __('Section Title Here','spawp');
				$data[$name.'_title_color'] = '';
				$data[$name.'_desc'] = __('Section Description Here','spawp');
				$data[$name.'_desc_color'] = '';
				$data[$name.'_bg_color'] = '';				
				$data[$name.'_container_width'] = 'container';
				$data[$name.'_divider_show'] = true;
				$data[$name.'_divider_type'] = 'div-dot';
				$data[$name.'_divider_width'] = 'w-10';
	                  $data[$name.'_item_bg_color'] = '';
	                  $data[$name.'_item_title_color'] = '';
	                  $data[$name.'_item_text_color'] = '';
	                  $data[$name.'_overlay_show'] = true;
	                  $data[$name.'_overlay_color'] = '';
			}

            $data['testimonial_bg_image'] = bc_plugin_url .'inc/spawp/img/testi-bg2.jpg';

            $data = array_merge( $old_data, $data );
		return $data;
	}
      add_filter('spawp_theme_default_data','spawp_premium_theme_default_data', 20);
}

// Front Page Slider Defauld Data
function spwp_slider_default_contents(){
	return json_encode( array(
            array(
            'title'      => esc_html__( 'Welcome To Spawp Theme', 'spawp' ),
            'subtitle'      => esc_html__( 'Spa Business Template', 'spawp' ),
            'text'       => esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing dolore magna aliqua.', 'spawp' ),
            'button_text'      => __('Book An Appointment','spawp'),
            'link'       => '#',
            'image_url'  => bc_plugin_url .'/inc/spawp/img/slide01.jpg',
            'checkbox_val' => false,
            'content_align' => 'center',
            'id'         => 'customizer_repeater_58d7gh7f20b10',
            ),
        ) );
}

// Front Page Service Defauld Data
function spwp_service_default_contents(){
	return json_encode( array(
            array(
            'title'      => esc_html__( 'Beauty', 'spawp' ),
            'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'spawp' ),
            'button_text'      => __('Read More','spawp'),
            'link'       => '#',
            'image_url'  => bc_plugin_url .'/inc/spawp/img/service1.jpg',
            'currency'  => '$',
            'price'  => '30',
            'checkbox_val' => false,
            'id'         => 'customizer_repeater_58d7gh7f20b10',
            ),
            array(
            'title'      => esc_html__( 'Wellness', 'spawp' ),
            'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'spawp' ),
            'button_text'      => __('Read More','spawp'),
            'link'       => '#',
            'image_url'  => bc_plugin_url .'/inc/spawp/img/service2.jpg',
            'currency'  => '$',
            'price'  => '30',
            'checkbox_val' => false,
            'id'         => 'customizer_repeater_58d7gh7f20b20',
            ),
            array(
            'title'      => esc_html__( 'Massage', 'spawp' ),
            'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'spawp' ),
            'button_text'      => __('Read More','spawp'),
            'link'       => '#',
            'image_url'  => bc_plugin_url .'/inc/spawp/img/service3.jpg',
            'currency'  => '$',
            'price'  => '30',
            'checkbox_val' => false,
            'id'         => 'customizer_repeater_58d7gh7f20b30',
            ),
            array(
            'title'      => esc_html__( 'Hair Care', 'spawp' ),
            'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'spawp' ),
            'button_text'      => __('Read More','spawp'),
            'link'       => '#',
            'image_url'  => bc_plugin_url .'/inc/spawp/img/service4.jpg',
            'currency'  => '$',
            'price'  => '30',
            'checkbox_val' => false,
            'id'         => 'customizer_repeater_58d7gh7f20b40',
            )
        ) );
}

// Front Page Feature Defauld Data
function spwp_feature_default_contents(){
	return json_encode( array(
            array(
            'title'      => esc_html__( 'Wellness Experts', 'spawp' ),
            'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'spawp' ),
            'icon_value'  => 'fa-user',
            'id'         => 'customizer_repeater_58d7gh7f20b10',
            ),
            array(
            'title'      => esc_html__( 'Massage Experts', 'spawp' ),
            'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'spawp' ),
            'icon_value'  => 'fa-hotel',
            'id'         => 'customizer_repeater_58d7gh7f20b20',
            ),
            array(
            'title'      => esc_html__( 'Ayuraveda Yoga', 'spawp' ),
            'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'spawp' ),
            'icon_value'  => 'fa-child',
            'id'         => 'customizer_repeater_58d7gh7f20b30',
            ),
        ) );
}


// Front Page Testimonial Defauld Data
function spwp_testimonial_default_contents(){
	return json_encode( array(
            array(
            'title'      => esc_html__( 'Laura Michelle', 'spawp' ),
            'designation'      => esc_html__( 'CEO', 'spawp' ),
            'text'       => esc_html__( 'Spa center whose team has high rosemary and health standards', 'spawp' ),
            'image_url'  => bc_plugin_url .'/inc/spawp/img/team1.jpg',
            'id'         => 'customizer_repeater_58d7gh7f20b10',
            ),
        ) );
}

// Front Page Team Defauld Data
function spwp_team_default_contents(){
	return json_encode( array(
            array(
            'title'      => esc_html__( 'Denial', 'spawp' ),
            'designation'      => esc_html__( 'CEO and Co-Founder', 'spawp' ),
            'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'spawp' ),
            'link'       => '#',
            'image_url'  => bc_plugin_url .'/inc/spawp/img/team1.jpg',
            'id'         => 'customizer_repeater_58d7gh7f20b10',
            ),
            array(
            'title'      => esc_html__( 'Denial', 'spawp' ),
            'designation'      => esc_html__( 'CEO and Co-Founder', 'spawp' ),
            'text'       => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting.', 'spawp' ),
            'link'       => '#',
            'image_url'  => bc_plugin_url .'/inc/spawp/img/team2.jpg',
            'id'         => 'customizer_repeater_58d7gh7f20b20',
            ),
        ) );
}