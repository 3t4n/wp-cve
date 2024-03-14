<?php 
namespace Shop_Ready\base\elementor\query\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

class Slider_Controls
{
	public function register() 
	{
	
		add_action('shop_ready_section_slider_tab' , array( $this, 'settings_section' ), 10 , 2 );
	}

	public function settings_section( $ele,$widget ) 
	{
        
           $ele->start_controls_section(
            'section_slider_tab',
                [
                    'label' => esc_html__('Slider Controls', 'shopready-elementor-addon'),
                ]
            );
            
            $ele->add_responsive_control(
                'slider_items',
                [
                    'label'   => esc_html__( 'Items', 'shopready-elementor-addon' ),
                    'type'    => \Elementor\Controls_Manager::NUMBER,
                    'min'     => 1,
                    'max'     => 40,
                    'step'    => 1,
                    'default' => 1
                   
                ]
            );

            $ele->add_control(
                'slider_loop',
                    [
                    'label'        => esc_html__( 'Loop', 'shopready-elementor-addon' ),
                    'type'         => \Elementor\Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Yes', 'shopready-elementor-addon' ),
                    'label_off'    => esc_html__( 'No', 'shopready-elementor-addon' ),
                    'return_value' => 'yes',
                    'default'      => 'no'
                    ]
            );

            $ele->add_control(
                'slider_autoplay',
                    [
                    'label'        => esc_html__( 'Autoplay', 'shopready-elementor-addon' ),
                    'type'         => \Elementor\Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Yes', 'shopready-elementor-addon' ),
                    'label_off'    => esc_html__( 'No', 'shopready-elementor-addon' ),
                    'return_value' => 'yes',
                    'default'      => 'no'
                    ]
            );

            $ele->add_control(
                'slider_autoplay_hover_pause',
                    [
                    'label'        => esc_html__( 'Autoplay Hover Pause', 'shopready-elementor-addon' ),
                    'type'         => \Elementor\Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Yes', 'shopready-elementor-addon' ),
                    'label_off'    => esc_html__( 'No', 'shopready-elementor-addon' ),
                    'return_value' => 'yes',
                    'default'      => 'no'
                    ]
            ); 
            
            $ele->add_control(
                'slider_autoplay_timeout',
                [
                    'label'   => esc_html__( 'Autoplay timeout', 'shopready-elementor-addon' ),
                    'type'    => \Elementor\Controls_Manager::NUMBER,
                    'min'     => 0,
                    'max'     => 20000,
                    'step'    => 1,
                   
                ]
            );
        
            $ele->add_control(
                'slider_smart_speed',
                [
                    'label'   => esc_html__( 'Smart Speed', 'shopready-elementor-addon' ),
                    'type'    => \Elementor\Controls_Manager::NUMBER,
                    'min'     => 0,
                    'max'     => 20000,
                    'step'    => 1,
                   
                ]
            );
             
            $ele->add_control(
                'slider_nav_show',
                    [
                    'label'        => esc_html__( 'Nav', 'shopready-elementor-addon' ),
                    'type'         => \Elementor\Controls_Manager::SWITCHER,
                    'label_on'     => esc_html__( 'Yes', 'shopready-elementor-addon' ),
                    'label_off'    => esc_html__( 'No', 'shopready-elementor-addon' ),
                    'return_value' => 'yes',
                    'default'      => 'yes'
                    ]
            );

            $ele->add_control(
                'slider_margin',
                [
                    'label'   => esc_html__( 'Margin', 'shopready-elementor-addon' ),
                    'type'    => \Elementor\Controls_Manager::NUMBER,
                    'min'     => 0,
                    'max'     => 300,
                    'step'    => 1,
                   
                ]
            );
    
            
            $ele->end_controls_section();	
	}
}