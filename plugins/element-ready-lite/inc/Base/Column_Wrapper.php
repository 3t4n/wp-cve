<?php 

namespace Element_Ready\Base;

use Elementor\Controls_Manager;
use Elementor\Element_Base;

class Column_Wrapper {

    public function register(){

        if( element_ready_get_modules_option( 'column_wrapper_link' ) ) { 
          add_action( 'elementor/element/column/layout/after_section_end', [ $this, 'add_controls_section' ], 1 );
          add_action( 'elementor/frontend/column/before_render', [ $this, 'before_render' ],19 );
        }
       
    }

    public function add_controls_section( Element_Base $element ){
  
        $element->start_controls_section(
            'element_ready_widget_wrapper_link_section',
            [
                'tab'   => \Elementor\Controls_Manager::TAB_LAYOUT,
                'label' => esc_html__( 'Wrapper Link', 'element-ready-lite' ),
            ]
          );

                $element->add_control(
                  'element_ready_pro_col_wrapper_tag_active',
                  [
                      'label'        => esc_html__('Enable', 'element-ready-lite'),
                      'type'         => Controls_Manager::SWITCHER,
                      'default'      => '',
                      'return_value' => 'yes',
                  
                  ]
                );

                $element->add_control(
                    'element_ready_pro_col_wrapper_link',
                    [
                        'label'         => esc_html__( 'Link', 'element-ready-lite' ),
                        'type'          => \Elementor\Controls_Manager::URL,
                        'placeholder'   => esc_html__( 'https://your-link.com', 'element-ready-lite' ),
                        'show_external' => true,
                    
                    ]
                );
     
        $element->end_controls_section();
    }

    public function before_render($element){
      
        $settings   = $element->get_settings_for_display();
        
        if ( ! $element->get_settings( 'element_ready_pro_col_wrapper_link' ) ) {
            return;
        }

        $active = $element->get_settings( 'element_ready_pro_col_wrapper_tag_active' );
       
        if($active !='yes' ){
            return;
        }
        
        $element->add_render_attribute(
            '_wrapper',
            [
                'class' => 'er-column-link',
                'data-link_active' => esc_attr( $active ),
                'data-url' => esc_url($settings['element_ready_pro_col_wrapper_link']['url']),
                'data-is_external' => esc_attr($settings['element_ready_pro_col_wrapper_link']['is_external']),
            ]
        );
    }
   
}