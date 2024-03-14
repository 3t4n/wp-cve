<?php

namespace Element_Ready\section_control;

class Element_Ready_Sticky {

    private static $instance = null;
    public function __construct() {

        add_action( 'wp_head', [$this, 'inline_script']);
        add_action( 'elementor/element/before_section_start', [ $this, 'sticky_transparent_option' ],15,3 );
        add_action( 'elementor/frontend/section/after_render', array($this, 'after_section_render'), 10, 2);
        add_action( 'wp_enqueue_scripts', array($this, 'add_css'), 10);
    }
    
    public function add_css(){

        if(element_ready_get_modules_option('sticky_section')){
            wp_enqueue_style( 'element-ready-sticky-section' );
            wp_enqueue_script( 'element-ready-sticky-section' );
        }
       
    }

    function sticky_transparent_option($element, $section_id, $args){

        if( 'section' === $element->get_name() && 'section_background' === $section_id ) {

            $element->start_controls_section(
                'element_ready_sticky_custom_sticky_section',
                [
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'label' => esc_html__( 'ElementsReady Sticky', 'element-ready-lite' ),
                ]
            );

                $element->add_control(
                    'element_ready_global_sticky',
                    [
                        'label' => esc_html__( 'Sticky', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => esc_html__( 'Yes', 'element-ready-lite' ),
                        'label_off' => esc_html__( 'No', 'element-ready-lite' ),
                        'return_value' => 'yes',
                        'default' => '',
                    ]
                );

                $element->add_responsive_control(
                    'element_ready_sticky_type',
                    [
                        'label' => esc_html__( 'Sticky Type', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [

                            'top'    => esc_html__('Top','element-ready-lite'),
                            ''       => esc_html__('none','element-ready-lite'),

                        ],
                        'condition' => [
                            'element_ready_global_sticky' => ['yes']
                        ],
                        
                    ]
                );

                $element->add_responsive_control(
                    'element_ready_main_section__sticky_height',
                    [
                        'label' => esc_html__( 'Height', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 1000,
                                'step' => 5,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        'condition' => [
                            'element_ready_global_sticky' => ['yes']
                        ],
                        'selectors' => [
                            '{{WRAPPER}}.element-ready-sticky.element-ready-sticky-container' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );

                $element->add_control(
                    'element_ready_sticky_offset',
                    [
                        'label' => esc_html__( 'Sticky Offset', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'min' => 0,
                        'max' => 900,
                        'step' => 5,
                        'default' => 110,
                        'condition' => [
                            'element_ready_global_sticky' => ['yes']
                        ],

                        
                    ]
                );

                $element->add_control(
                    'element_ready_sticky_offset_z_index',
                    [
                        'label' => esc_html__( 'Z-index', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'min' => -2000,
                        'max' => 2000,
                        'step' => 5,
                        'condition' => [
                            'element_ready_global_sticky' => ['yes']
                        ],
                        'selectors' => [
                            '{{WRAPPER}}' => 'z-index: {{VALUE}};',
                        ],
                    ]
                );

                $element->add_control(
                    'element_ready_sticky_popup_wqeiuty_position',
                    [
                        'label'        => esc_html__( 'Position', 'element-ready-lite' ),
                        'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                        'label_off'    => esc_html__( 'Default', 'element-ready-lite' ),
                        'label_on'     => esc_html__( 'Custom', 'element-ready-lite' ),
                        'return_value' => 'yes',
                        'condition'    => [
                            'element_ready_global_sticky' => ['yes']
                        ],
                    ]
                );
        
                $element->start_popover();

                $element->add_responsive_control(
                    'element_ready_main_sectionwrsw_sticky_position_type',
                    [
                        'label'   => esc_html__( 'Position', 'element-ready-lite' ),
                        'type'    => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [
                            'fixed'    => esc_html__('Fixed','element-ready-lite'),
                            'absolute' => esc_html__('Absolute','element-ready-lite'),
                            'relative' => esc_html__('Relative','element-ready-lite'),
                            'sticky'   => esc_html__('Sticky','element-ready-lite'),
                            'static'   => esc_html__('Static','element-ready-lite'),
                            'inherit'  => esc_html__('inherit','element-ready-lite'),
                            ''         => esc_html__('none','element-ready-lite'),
                        ],
                        'selectors' => [
                            '{{WRAPPER}}.element-ready-sticky.element-ready-sticky-container' => 'position: {{VALUE}};',
                        ],
                    ]
                );
        
                $element->add_responsive_control(
                    'element_ready_main_section__ws_sticky_position_left',
                    [
                        'label' => esc_html__( 'Left', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => -1600,
                                'max' => 2100,
                                'step' => 5,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                       
                        'selectors' => [
                            '{{WRAPPER}}.element-ready-sticky.element-ready-sticky-container' => 'left: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
        
                $element->add_responsive_control(
                    'element_ready_main_global_sticky_position_top',
                    [
                        'label' => esc_html__( 'Top', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => -1600,
                                'max' => 2100,
                                'step' => 5,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                       
                        'selectors' => [
                            '{{WRAPPER}}.element-ready-sticky.element-ready-sticky-container' => 'top: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
    
                $element->add_responsive_control(
                    'element_ready_main_global_sticky_position_right',
                    [
                        'label' => esc_html__( 'Right', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => -1600,
                                'max' => 2100,
                                'step' => 5,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                       
                        'selectors' => [
                            '{{WRAPPER}}.element-ready-sticky.element-ready-sticky-container' => 'right: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
    
                $element->add_responsive_control(
                    'element_ready_main_global_sticky_position_bottom',
                    [
                        'label' => esc_html__( 'Bottom', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => -1600,
                                'max' => 1600,
                                'step' => 5,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                       
                        'selectors' => [
                            '{{WRAPPER}}.element-ready-sticky.element-ready-sticky-container' => 'bottom: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
    
                $element->end_popover();
    

                $element->add_control(
                    'element_ready_sticky_heading',
                    [
                        'label' => esc_html__( 'Sticky Background', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::HEADING,
                        'separator' => 'before',
                        'condition' => [
                            'element_ready_global_sticky' => ['yes']
                        ],
                        
                    ]
                );

                $element->add_group_control(
                    \Elementor\Group_Control_Background::get_type(),
                    [
                        'name' => 'element_ready_sticky_offset_element_ready_sticky_offset_background',
                        'label' => esc_html__( 'Background', 'element-ready-lite' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}}.element-ready-sticky.element-ready-sticky-container',
                        'condition' => [
                            'element_ready_global_sticky' => ['yes']
                        ],
                    ]
                );

                $element->add_group_control(
                    \Elementor\Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'element_ready_sticky_offset__box_shadow',
                        'label' => esc_html__( 'Box Shadow', 'element-ready-lite' ),
                        'selector' => '{{WRAPPER}}.element-ready-sticky.element-ready-sticky-container',
                        'condition' => [
                            'element_ready_global_sticky' => ['yes']
                        ],
                    ]
                );

            $element->end_controls_section();

            $element->start_controls_section(
                'element_ready_uiuicky_custom_pos_section',
                [
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'label' => esc_html__( 'Element Ready Position', 'element-ready-lite' ),
                ]
            );

            $element->add_control(
                'element_ready_sticky_popup_iuty_position',
                [
                    'label' => esc_html__( 'Position', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                    'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                    'return_value' => 'yes',
                ]
            );
    
            $element->start_popover();
            $element->add_responsive_control(
                'element_ready_main_section_sticky_position_type',
                [
                    'label' => esc_html__( 'Position', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'fixed'    => esc_html__('Fixed','element-ready-lite'),
                        'absolute' => esc_html__('Absolute','element-ready-lite'),
                        'relative' => esc_html__('Relative','element-ready-lite'),
                        'sticky'   => esc_html__('Sticky','element-ready-lite'),
                        'static'   => esc_html__('Static','element-ready-lite'),
                        'inherit'  => esc_html__('inherit','element-ready-lite'),
                        ''         => esc_html__('none','element-ready-lite'),
                    ],
                    'selectors' => [
                        '{{WRAPPER}}' => 'position: {{VALUE}};',
                    ],
                ]
            );
    
            $element->add_responsive_control(
                'element_ready_main_section_sticky_position_left',
                [
                    'label' => esc_html__( 'Position Left', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 2100,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}}' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
    
            $element->add_responsive_control(
                'element_ready_main_global_r_position_top',
                [
                    'label' => esc_html__( 'Position Top', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 2100,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}}' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $element->add_responsive_control(
                'element_ready_main_global_r_position_right',
                [
                    'label' => esc_html__( 'Position Right', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 2100,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}}' => 'right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $element->add_responsive_control(
                'element_ready_main_global_r_position_bottom',
                [
                    'label' => esc_html__( 'Position Bottom', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -1600,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}}' => 'bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $element->end_popover();

            $element->add_control(
                'element_ready_custom_popover_section_sizen',
                [
                    'label' => esc_html__( 'Box Size', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                    'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                    'return_value' => 'yes',
                ]
            );
    
            $element->start_popover();
    
            $element->add_responsive_control(
                'element_ready_custom_global_section__width',
                [
                    'label' => esc_html__( 'Width', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 2100,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
    
            $element->add_responsive_control(
                'element_ready_custom_globaln_container_height',
                [
                    'label' => esc_html__( 'Height', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 2100,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}}' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
    
           
            $element->end_popover();
            
          
            $element->end_controls_section();
            $element->start_controls_section(
                'element_ready_menu_advance_section',
                [
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'label' => esc_html__( 'Element Ready Advanced', 'element-ready-lite' ),
                ]
            );

            $element->add_responsive_control(
                'active_men_sectiob_adv_pmadding',
                [
                    'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .elementor-column-wrap.elementor-element-populated' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        
                    ],
                    'separator' => 'before',
                ]
            );

            $element->add_responsive_control(
                'active_men_sectiob_adv_pm_margin',
                [
                    'label'      => esc_html__( 'margin', 'element-ready-lite' ),
                    'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .elementor-column-wrap.elementor-element-populated' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        
                    ],
                    'separator' => 'before',
                ]
            );

            $element->end_controls_section();
        }
    }
  
    public function after_section_render(\Elementor\Element_Base $element)
    {
        $data     = $element->get_data();
        $settings = $data['settings'];
        if(!element_ready_get_modules_option('sticky_section')){
         return;
        }
        
        if  (
                (isset($settings['element_ready_global_sticky']) && $settings['element_ready_global_sticky'] == 'yes') || 
                (isset($settings['element_ready_sticky_type']) && $settings['element_ready_sticky_type'] != '')
            ){

            echo "
            <script>
                window.element_ready_section_sticky_data.section".esc_attr($data['id'])." = JSON.parse('".json_encode($settings)."');
            </script>
            ";
        }
       
    }
    public function inline_script(){


		echo '
			<script type="text/javascript">
				var element_ready_section_sticky_data = {};
				var element_ready_section_sticky_data_url = "";
			</script>
		';
	}
   
  // The object is created from within the class itself
  // only if the class has no instance.
  public static function getInstance(){
    if (self::$instance == null){
      self::$instance = new self();
    }
    return self:: $instance;
    }
  }

  if(element_ready_get_modules_option('sticky_section')){
    Element_Ready_Sticky::getInstance();
  }
