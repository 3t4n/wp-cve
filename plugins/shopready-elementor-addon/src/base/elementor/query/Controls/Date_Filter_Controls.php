<?php 

namespace Shop_Ready\base\elementor\query\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

class Date_Filter_Controls
{
	public function register() 
	{
	
		add_action('shop_ready_section_date_filter_tab' , array( $this, 'settings_section' ),10,2 );
	}

	public function settings_section( $ele,$widget ) 
	{
           $ele->start_controls_section(
            'section_date_filter_tab',
                [
                    'label' => esc_html__('Date Filter', 'shopready-elementor-addon'),
                ]
            );

                $ele->add_control(
                    'date_post',
                    [
                        'label'   => esc_html__('Select date post', 'shopready-elementor-addon'),
                        'type'    => Controls_Manager::SELECT,
                        'default' => 'none',
                        'options' => [
                            'none'        => '---',
                            'today'       => esc_html__( 'Today', 'shopready-elementor-addon' ),
                            'this_week'   => esc_html__( 'This Week', 'shopready-elementor-addon' ),
                            'custom_date' => esc_html__( 'Custom date', 'shopready-elementor-addon' ),
                        ],
                        'label_block' => true,
                        'multiple'    => false,
                        
                    ]
                );

                $ele->add_control(
                    'date_after',
                    [
                        'label'          => esc_html__( 'Date After', 'shopready-elementor-addon' ),
                        'type'           => \Elementor\Controls_Manager::DATE_TIME,
                        'condition'      => [ 'date_post' => 'custom_date' ],
                        'picker_options' => [
                            'dateFormat' => 'Y-m-d',
                        ],
                    ]
                );

                $ele->add_control(
                    'date_before',
                    [
                        'label'          => esc_html__( 'Date Before', 'shopready-elementor-addon' ),
                        'type'           => \Elementor\Controls_Manager::DATE_TIME,
                        'condition'      => [ 'date_post' => 'custom_date' ],
                        'picker_options' => [
                            'dateFormat' => 'Y-m-d'
                        ],
                    ]
                );
                
            do_action( 'shop_ready_section_date_filter_tab_extra_control', $ele, $widget );
            
            $ele->end_controls_section();	
	}
}