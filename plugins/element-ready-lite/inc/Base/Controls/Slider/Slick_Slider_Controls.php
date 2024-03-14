<?php

namespace Element_Ready\Base\Controls\Slider;

use Elementor\Controls_Manager;
use Element_Ready\Base\BaseController;

class Slick_Slider_Controls extends BaseController
{
	public function register() 
	{
	    add_action('element_ready_section_slick_slider_tab' , array( $this, 'settings_section' ), 10 , 2 );
	}

	public function settings_section( $ele,$widget ) 
	{
           $ele->start_controls_section(
            'section_slider_tab',
                [
                    'label' => esc_html__('Slider Controls', 'element-ready-lite'),
                ]
            );
  
                $ele->add_responsive_control(
                    'element_ready_slider_show_in_nav',
                    [
                        'label'   => esc_html__( 'Items in nav ', 'element-ready-lite' ),
                        'type'    => \Elementor\Controls_Manager::NUMBER,
                        'min'     => 1,
                        'max'     => 15,
                        'step'    => 1,
                        'default' => 4
                    
                    ]
                );
     
                $ele->add_control(
                    'show_nav',
                    [
                        'label'     => esc_html__('Show Nav', 'element-ready-lite'),
                        'type'      => Controls_Manager::SWITCHER,
                        'label_on'  => esc_html__('Yes', 'element-ready-lite'),
                        'label_off' => esc_html__('No', 'element-ready-lite'),
                        'default'   => 'yes',
                    
                    ]
                );

                $ele->add_control(
                    'autoplay',
                    [
                        'label'     => esc_html__('Autoplay', 'element-ready-lite'),
                        'type'      => Controls_Manager::SWITCHER,
                        'label_on'  => esc_html__('Yes', 'element-ready-lite'),
                        'label_off' => esc_html__('No', 'element-ready-lite'),
                        'default'   => 'yes',
                    
                    ]
                );
      
            do_action( 'element_ready_section_slick_slider_tab_extra_control', $ele, $widget );    
            $ele->end_controls_section();	
	}
}