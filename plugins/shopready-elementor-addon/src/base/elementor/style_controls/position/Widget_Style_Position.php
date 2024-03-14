<?php
/**
 * @package Shop Ready
 */
namespace Shop_Ready\base\elementor\style_controls\position;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;

trait Widget_Style_Position {

    public function position_css($atts) {
        
        $atts_variable = shortcode_atts(
            array(
                'title'        => esc_html__('Box Position','shopready-elementor-addon'),
                'slug'         => '_box_style',
                'element_name' => '__wooready__',
                'selector'     => '{{WRAPPER}} ',
                'condition'    => '',
            ), $atts );

        extract($atts_variable);    

        $widget = $this->get_name().'_'.shop_ready_heading_camelize($slug);
        
        $tab_start_section_args =  [
            'label' => $title,
            'tab'   => Controls_Manager::TAB_STYLE,
        ];

        if(is_array($condition)){
            $tab_start_section_args['condition'] = $condition;
        }
        
        /*----------------------------
            ELEMENT__STYLE
        -----------------------------*/
        $this->start_controls_section(
            $widget.'_style_section',
            $tab_start_section_args
        );
 
            $this->add_responsive_control(
                $widget.'_section__'.$element_name.'_position_type',
                [
                    'label' => esc_html__( 'Position', 'shopready-elementor-addon' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'fixed'    => esc_html__('Fixed','shopready-elementor-addon'),
                        'absolute' => esc_html__('Absolute','shopready-elementor-addon'),
                        'relative' => esc_html__('Relative','shopready-elementor-addon'),
                        'sticky'   => esc_html__('Sticky','shopready-elementor-addon'),
                        'static'   => esc_html__('Static','shopready-elementor-addon'),
                        'inherit'  => esc_html__('inherit','shopready-elementor-addon'),
                        ''         => esc_html__('none','shopready-elementor-addon'),
                    ],
                    'selectors' => [
                        $selector => 'position: {{VALUE}};',
                       
                    ],
                    
                ]
            );
    
            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'_position_left',
                [
                    'label' => esc_html__( 'Position Left', 'shopready-elementor-addon' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -3000,
                            'max' => 3000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        $selector => 'left: {{SIZE}}{{UNIT}};',
                       
                    ],
                ]
            );
    
            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'_r_position_top',
                [
                    'label' => esc_html__( 'Position Top', 'shopready-elementor-addon' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -3000,
                            'max' => 3000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        $selector => 'top: {{SIZE}}{{UNIT}};',
                      
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'_r_position_bottom',
                [
                    'label' => esc_html__( 'Position Bottom', 'shopready-elementor-addon' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -2100,
                            'max' => 3000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        $selector => 'bottom: {{SIZE}}{{UNIT}};',
                       
                    ],
                ]
            );
            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'_r_position_right',
                [
                    'label' => esc_html__( 'Position Right', 'shopready-elementor-addon' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 3000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        $selector => 'right: {{SIZE}}{{UNIT}};',
                       
                    ],
                ]
            );


        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }

}