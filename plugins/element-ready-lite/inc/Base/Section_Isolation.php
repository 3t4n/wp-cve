<?php 

namespace Element_Ready\Base;
use Elementor\Controls_Manager;

class Section_Isolation {

    public function register(){
       add_action( 'elementor/element/after_section_end', [ $this, 'add_controls_section' ] ,15 , 3 );
    }

    public function add_controls_section( $element, $section_id, $args ){

        if ($section_id == 'section_advanced') {

            $element->start_controls_section(
                '_section_element_ready_pro_isolation_sec',
                [
                    'label' => __( 'Er Isolation', 'element-ready-lite' ),
                    'tab'   => Controls_Manager::TAB_ADVANCED,
                ]
            );

                $element->add_control(
                    'element_ready_pro_isolation_type',
                    [
                        'label' => esc_html__( 'Isolation', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [

                            'isolate'      => esc_html__( 'Isolate', 'element-ready-lite' ),
                            'revert'       => esc_html__( 'Revert', 'element-ready-lite' ),
                            'revert-layer' => esc_html__( 'Revert layer', 'element-ready-lite' ),
                            'auto'         => esc_html__( 'Auto', 'element-ready-lite' ),
                            'initial'             => esc_html__( 'Initial', 'element-ready-lite' ),
                            ''             => esc_html__( 'None', 'element-ready-lite' ),
                    
                        ],
                        'selectors' => [
                            '{{WRAPPER}}' => 'isolation: {{VALUE}}',
                        ],
                    ]
                );
 
            $element->end_controls_section();
        }
    }

   
}