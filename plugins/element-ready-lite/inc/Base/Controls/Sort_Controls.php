<?php 

namespace Element_Ready\Base\Controls;

use Elementor\Controls_Manager;
use Element_Ready\Base\BaseController;

class Sort_Controls extends BaseController
{
	public function register() 
	{
		add_action('element_ready_section_sort_tab' , array( $this, 'settings_section' ),10,2 );
	}

	public function settings_section( $ele,$widget ) 
	{
           $ele->start_controls_section(
            'section_sort_tab',
                [
                    'label' => esc_html__('Sort / order', 'element-ready-lite'),
                ]
            );
                
            $ele->add_control(
                'post_sortby',
                [
                    'label'     =>esc_html__( 'Post sort by', 'element-ready-lite' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'latestpost',
                    'options'   => [
                        'latestpost'    => esc_html__( 'Latest', 'element-ready-lite' ),
                        'popularposts'  => esc_html__( 'Popular / most view', 'element-ready-lite' ),
                        'mostdiscussed' => esc_html__( 'Most discussed', 'element-ready-lite' ),
                        'fb_share'      => esc_html__( 'Most fb share', 'element-ready-lite' ),
                        'tranding'      => esc_html__( 'Tranding', 'element-ready-lite' ),
                    ],
                ]
            );
    
            $ele->add_control(
                'post_order',
                [
                    'label'     =>esc_html__( 'Post order', 'element-ready-lite' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'DESC',
                    'options'   => [
                        'DESC'      =>esc_html__( 'Descending', 'element-ready-lite' ),
                        'ASC'       =>esc_html__( 'Ascending', 'element-ready-lite' ),
                    ],
                ]
            );
            
            do_action( 'element_ready_section_sort_tab_extra_control', $ele, $widget );    
            $ele->end_controls_section();	
	}
}