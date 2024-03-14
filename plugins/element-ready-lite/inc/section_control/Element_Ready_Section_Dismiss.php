<?php

namespace Element_Ready\section_control;

class Element_Ready_Section_Dismiss {

    private static $instance = null;
    public function __construct() {

        add_action( 'wp_head', [$this, 'inline_script']);
        add_action( 'elementor/element/before_section_start', [ $this, 'dismiss_transparent_option' ],16,3 );
        add_action( 'elementor/frontend/section/after_render', array($this, 'after_section_render'), 11, 2);
  
    }
 
    function dismiss_transparent_option($element, $section_id, $args){

        if( 'section' === $element->get_name() && 'section_background' === $section_id ) {

            $element->start_controls_section(
                'element_ready_dissmiss_custom_dismiss_section',
                [
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'label' => esc_html__( 'ER Dismissable', 'element-ready-lite' ),
                ]
            );

                    $element->add_control(
                        'element_ready_section_dissmis',
                        [
                            'label'        => esc_html__( 'Dismissable', 'element-ready-lite' ),
                            'type'         => \Elementor\Controls_Manager::SWITCHER,
                            'label_on'     => esc_html__( 'Yes', 'element-ready-lite' ),
                            'label_off'    => esc_html__( 'No', 'element-ready-lite' ),
                            'return_value' => 'yes',
                            'default'      => '',
                        ]
                    );

                    $element->add_control(
                        'element_ready_section_dissmis_type',
                        [
                            'label' => __( 'Dismiss Type', 'element-ready-lite' ),
                            'type' => \Elementor\Controls_Manager::SELECT,
                            'default' => 'fadeOut',
                            'condition' => [
                                'element_ready_section_dissmis' => ['yes']
                            ],
                            'options' => [

                                'fadeOut' => __( 'fadeOut', 'element-ready-lite' ),
                                'slideUp' => __( 'slideUp', 'element-ready-lite' ),
                           
                            ],
                        ]
                    );

                    $element->add_control(
                        'element_ready_section_dissmis_timeout',
                        [
                            'label' => __( 'Close TimeOut', 'element-ready-lite' ),
                            'type' => \Elementor\Controls_Manager::SLIDER,
                            'condition' => [
                                'element_ready_section_dissmis' => ['yes']
                            ],
                            'size_units' => [ 'px', ],
                            'range' => [
                                'px' => [
                                    'min' => 100,
                                    'max' => 10000,
                                    'step' => 5,
                                ],
                                
                            ],
                            'default' => [
                                'size' => 1000,
                            ],
                           
                        ]
                    );
            

                    $element->add_control(
                        'element_ready_main_section__dismissabley_close_icon',
                        [
                            'label' => __( 'Close Icon', 'element-ready-lite' ),
                            'type' => \Elementor\Controls_Manager::ICONS,
                            'condition' => [
                                'element_ready_section_dissmis' => ['yes']
                            ],
                            'default' => [
                                'value' => 'fas fa-times',
                                'library' => 'solid',
                            ],
                        ]
                    );

                    $element->add_control(
                        'element_ready_dismissable_dismissable_section_padding',
                        [
                            'label' => __( 'Icon Padding', 'element-ready-lite' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'condition' => [
                                'element_ready_section_dissmis' => ['yes']
                            ],
                            'selectors' => [
                                '{{WRAPPER}}.element-ready-dismissable-container .element-ready-section--dismissable-html i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $element->add_control(
                        'element_ready_dismissable_dismissable_section_ bordre_radious',
                        [
                            'label' => __( 'Icon Border radious', 'element-ready-lite' ),
                            'type' => \Elementor\Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'condition' => [
                                'element_ready_section_dissmis' => ['yes']
                            ],
                            'selectors' => [
                                '{{WRAPPER}}.element-ready-dismissable-container .element-ready-section--dismissable-html' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    $element->add_control(
                        'element_ready_dismissable_dismissable_section_color',
                        [
                            'label' => __( 'Icon Color', 'element-ready-lite' ),
                            'condition' => [
                                'element_ready_section_dissmis' => ['yes']
                            ],
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}}.element-ready-dismissable-container .element-ready-section--dismissable-html i' => 'color: {{VALUE}}',
                                '{{WRAPPER}}.element-ready-dismissable-container .element-ready-section--dismissable-html' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $element->add_group_control(
                        \Elementor\Group_Control_Typography::get_type(),
                        [
                            'name' => 'element_ready_dismissable_dismissable_section_content_typography',
                            'label' => __( 'Typography', 'element-ready-lite' ),
                            'condition' => [
                                'element_ready_section_dissmis' => ['yes']
                            ],
                            'selector' => '{{WRAPPER}}.element-ready-dismissable-container .element-ready-section--dismissable-html',
                        ]
                    );

                    $element->add_group_control(
                        \Elementor\Group_Control_Background::get_type(),
                        [
                            'name' => 'element_ready_dismissable_dismissable_section_color__background',
                            'label' => __( 'Background', 'element-ready-lite' ),
                            'types' => [ 'classic', 'gradient' ],
                            'condition' => [
                                'element_ready_section_dissmis' => ['yes']
                            ],
                            'selector' => '{{WRAPPER}}.element-ready-dismissable-container .element-ready-section--dismissable-html',
                        ]
                    );
                

                    $element->add_control(
                        'element_ready_dismissable_dismissable_z_index',
                        [
                            'label' => esc_html__( 'Z-index', 'element-ready-lite' ),
                            'type' => \Elementor\Controls_Manager::NUMBER,
                            'min' => -2000,
                            'max' => 2000,
                            'step' => 5,
                            'condition' => [
                                'element_ready_section_dissmis' => ['yes']
                            ],
                            'selectors' => [
                                '{{WRAPPER}}.element-ready-dismissable-container .element-ready-section--dismissable-html' => 'z-index: {{VALUE}};',
                            ],
                        ]
                    );

                $element->add_control(
                    'element_ready_sticky_popup_dismissable_position',
                    [
                        'label' => esc_html__( 'Position', 'element-ready-lite' ),
                        'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                        'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                        'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                        'return_value' => 'yes',
                        'condition' => [
                            'element_ready_section_dissmis' => ['yes']
                        ],
                    ]
                );
        
                $element->start_popover();
                $element->add_responsive_control(
                    'element_ready_main_sectionwrsw_dismissable_position_type',
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
                            '{{WRAPPER}}.element-ready-dismissable-container .element-ready-section--dismissable-html' => 'position: {{VALUE}};',
                        ],
                    ]
                );
        
                $element->add_responsive_control(
                    'element_ready_main_section__ws_dismissable_position_left',
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
                            '{{WRAPPER}}.element-ready-dismissable-container .element-ready-section--dismissable-html' => 'left: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
        
                $element->add_responsive_control(
                    'element_ready_main_global_dismissable_position_top',
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
                            '{{WRAPPER}}.element-ready-dismissable-container .element-ready-section--dismissable-html' => 'top: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
    
                $element->add_responsive_control(
                    'element_ready_main_global_dismissable_position_right',
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
                            '{{WRAPPER}}.element-ready-dismissable-container .element-ready-section--dismissable-html' => 'right: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
    
                $element->add_responsive_control(
                    'element_ready_main_global_dismissable_position_bottom',
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
                            '{{WRAPPER}}.element-ready-dismissable-container .element-ready-section--dismissable-html' => 'bottom: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
    
                $element->end_popover();
       

            $element->end_controls_section();
     

        }
    }
 
    public function after_section_render(\Elementor\Element_Base $element)
    {
        $data     = $element->get_data();
        $settings = $data['settings'];
        if(!element_ready_get_modules_option('section_dismiss')){
            return;
        }
        
        if  (   (isset($settings['element_ready_section_dissmis']) && $settings['element_ready_section_dissmis'] == 'yes') || 
                (isset($settings['element_ready_section_dissmis']) && $settings['element_ready_section_dissmis'] != '')
            ){

            echo "
            <script>
                window.element_ready_section_dismiss_data.section".esc_attr($data['id'])." = JSON.parse('".json_encode($settings)."');
            </script>
            ";

        }
       
    }
    public function inline_script(){
		echo '
			<script type="text/javascript">
				var element_ready_section_dismiss_data = {};
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
  if(element_ready_get_modules_option('section_dismiss')){
    Element_Ready_Section_Dismiss::getInstance();
  }
