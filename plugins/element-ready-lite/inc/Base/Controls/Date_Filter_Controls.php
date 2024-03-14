<?php 

namespace Element_Ready\Base\Controls;

use Elementor\Controls_Manager;
use Element_Ready\Base\BaseController;

class Date_Filter_Controls extends BaseController
{
	public function register() 
	{
	   add_action('element_ready_section_date_filter_tab' , array( $this, 'settings_section' ),10,2 );
	}

	public function settings_section( $ele,$widget ) 
	{
           $ele->start_controls_section(
            'section_date_filter_tab',
                [
                    'label' => esc_html__('Date Filter', 'element-ready-lite'),
                ]
            );

                $ele->add_control(
                    'date_post',
                    [
                        'label'   => esc_html__('Select date post', 'element-ready-lite'),
                        'type'    => Controls_Manager::SELECT,
                        'default' => 'none',
                        'options' => [
                            'none'        => '---',
                            'today'       => esc_html__( 'Today', 'element-ready-lite' ),
                            'this_week'   => esc_html__( 'This Week', 'element-ready-lite' ),
                            'custom_date' => esc_html__( 'Custom date', 'element-ready-lite' ),
                        ],
                        'label_block' => true,
                        'multiple'    => false,
                        
                    ]
                );

                $ele->add_control(
                    'date_after',
                    [
                        'label'          => esc_html__( 'Date After', 'element-ready-lite' ),
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
                        'label'          => esc_html__( 'Date Before', 'element-ready-lite' ),
                        'type'           => \Elementor\Controls_Manager::DATE_TIME,
                        'condition'      => [ 'date_post' => 'custom_date' ],
                        'picker_options' => [
                            'dateFormat' => 'Y-m-d'
                        ],
                    ]
                );
                
            do_action( 'element_ready_section_date_filter_tab_extra_control', $ele, $widget );
            $ele->end_controls_section();	
	}
}