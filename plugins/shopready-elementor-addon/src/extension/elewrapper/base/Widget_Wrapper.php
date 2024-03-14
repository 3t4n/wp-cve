<?php 
namespace Shop_Ready\extension\elewrapper\base;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Element_Base;

class Widget_Wrapper {
 
    public function register(){
        
        add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'add_controls_section' ], 1 );
        add_action( 'elementor/frontend/widget/before_render', [ $this, 'before_render' ], 19 );
        add_action( 'elementor/frontend/widget/after_render', [ $this, 'after_render' ], 19 );

    }

    public function add_controls_section( Element_Base $element ){

      
        $element->start_controls_section(
            'woo_ready_widget_wrapper_link_section',
            [
                
            'label' => esc_html__( 'Shop Ready Wrapper Link', 'shopready-elementor-addon' ),
            'tab'   => Controls_Manager::TAB_ADVANCED,
            ]
          );

            $element->add_control(
                'woo_ready_pro_widget_wrapper_tag_active',
                [
                    'label'        => esc_html__('Enable', 'shopready-elementor-addon'),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => '',
                    'return_value' => 'yes',
                
                ]
            );

            $element->add_control(
                'woo_ready_pro_widget_wrapper_link',
                [
                    'label'         => esc_html__( 'Link', 'shopready-elementor-addon' ),
                    'type'          => \Elementor\Controls_Manager::URL,
                   
                ]
            );

        
        $element->end_controls_section();
    }

    public function before_render($element){
      
        $settings   = $element->get_settings_for_display();
        $active     = $settings['woo_ready_pro_widget_wrapper_tag_active'];
 
        if($active){
            $target = $settings['woo_ready_pro_widget_wrapper_link']['is_external'] ? ' target="_blank"' : '';
            $nofollow = $settings['woo_ready_pro_widget_wrapper_link']['nofollow'] ? ' rel="nofollow"' : '';
            echo wp_kses_post('<a class="woo-ready-widget-wrapper-link" href="' . esc_url($settings['woo_ready_pro_widget_wrapper_link']['url']) . '"' . $target . $nofollow . '>');
          
        }
        
    }
    public function after_render($element){

        $settings   = $element->get_settings_for_display();
        $active     = $settings['woo_ready_pro_widget_wrapper_tag_active'];

        if($active){
            echo wp_kses_post('</a>');  
        }
    }
}