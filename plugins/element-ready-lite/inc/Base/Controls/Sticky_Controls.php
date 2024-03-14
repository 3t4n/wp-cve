<?php 

namespace Element_Ready\Base\Controls;

use Elementor\Controls_Manager;
use Element_Ready\Base\BaseController;

class Sticky_Controls extends BaseController
{
	public function register() 
	{
		add_action('element_ready_section_sticky_tab' , array( $this, 'settings_section' ),10,2 );
	}

	public function settings_section( $ele ,$widget) 
	{
        $ele->start_controls_section(
            'section_sticky_source_tab',
            [
                'label' => esc_html__('Sticky / Features ', 'element-ready-lite'),
            ]
        );
    
        
        $ele->add_control(
            'sticky_post',
            [
                'label'       => esc_html__('Show Feature post', 'element-ready-lite'),
                'type'        => Controls_Manager::SWITCHER,
                'label_on'    => esc_html__('Yes', 'element-ready-lite'),
                'label_off'   => esc_html__('No', 'element-ready-lite'),
                'default'     => 'no',
                'description' => esc_html__('Use Sticky option to feature posts', 'element-ready-lite'),
            ]
        );
   
        do_action( 'element_ready_section_sticky_tab_extra_control', $ele, $widget );
        
    $ele->end_controls_section();

	}
}