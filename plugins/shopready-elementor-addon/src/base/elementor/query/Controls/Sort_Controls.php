<?php 
namespace Shop_Ready\base\elementor\query\Controls;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;


class Sort_Controls
{
	public function register() 
	{
	
		add_action('shop_ready_section_sort_tab' , array( $this, 'settings_section' ),10,2 );
	}

	public function settings_section( $ele,$widget ) 
	{
           $ele->start_controls_section(
            'section_sort_tab',
                [
                    'label' => esc_html__('Sort / order', 'shopready-elementor-addon'),
                ]
            );
                
            $ele->add_control(
                'post_sortby',
                [
                    'label'     =>esc_html__( 'Post sort by', 'shopready-elementor-addon' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'latestpost',
                    'options'   => [
                        'latestpost'    => esc_html__( 'Latest', 'shopready-elementor-addon' ),
                        'mostdiscussed' => esc_html__( 'Most discussed', 'shopready-elementor-addon' ),
                        'recent_view'   => esc_html__( 'Recent View', 'shopready-elementor-addon' ),      // cookie
                    ],
                ]
            );
    
            $ele->add_control(
                'post_order',
                [
                    'label'     =>esc_html__( 'Post order', 'shopready-elementor-addon' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => 'DESC',
                    'options'   => [
                        'DESC'      =>esc_html__( 'Descending', 'shopready-elementor-addon' ),
                        'ASC'       =>esc_html__( 'Ascending', 'shopready-elementor-addon' ),
                    ],
                ]
            );
            
            do_action( 'shop_ready_section_sort_tab_extra_control', $ele, $widget );    
            $ele->end_controls_section();	
	}
}