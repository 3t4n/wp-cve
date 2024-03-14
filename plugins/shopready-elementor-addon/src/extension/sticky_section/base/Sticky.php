<?php
namespace Shop_Ready\extension\sticky_section\base;

class Sticky {

    /**
     * Service initializer
     * @since 1.0
     */
    public function register() {

        add_action( 'wp_head', [$this, 'inline_script']);
        add_action( 'elementor/element/before_section_start', [ $this, 'sticky_transparent_option' ],15,3 );
        add_action( 'elementor/frontend/section/after_render', array($this, 'after_section_render'), 10, 2);
        add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
    }
     
    public function enqueue_frontend_scripts(){

        $src = SHOP_READY_URL.'src/extension/sticky_section/assets/js/sticky.js';
        wp_enqueue_script( 'shop-ready-sticky-section', $src, array('jquery','wp-util'), 1.0, true );
    }

    function sticky_transparent_option($element, $section_id, $args){

        if( 'section' === $element->get_name() && 'section_background' === $section_id ) {

            $element->start_controls_section(
                'sr_shop_ready_sticky_custom_sticky_section',
                [
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    'label' => esc_html__( 'Shop Ready Sticky', 'shopready-elementor-addon' ),
                ]
            );

                $element->add_control(
                    'shop_ready_global_sticky',
                    [
                        'label' => esc_html__( 'Sticky', 'shopready-elementor-addon' ),
                        'type' => \Elementor\Controls_Manager::SWITCHER,
                        'label_on' => esc_html__( 'Enable', 'shopready-elementor-addon' ),
                        'label_off' => esc_html__( 'Disable', 'shopready-elementor-addon' ),
                        'return_value' => 'yes',
                        'default' => '',
                    ]
                );

                $element->add_responsive_control(
                    'shop_ready_sticky_type',
                    [
                        'label' => esc_html__( 'Sticky Type', 'shopready-elementor-addon' ),
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => '',
                        'options' => [

                            'top'    => esc_html__('Top','shopready-elementor-addon'),
                            ''       => esc_html__('none','shopready-elementor-addon'),

                        ],
                        'condition' => [
                            'shop_ready_global_sticky' => ['yes']
                        ],
                        
                    ]
                );

                $element->add_responsive_control(
                    'shop_ready_main_section__sticky_height',
                    [
                        'label' => esc_html__( 'Height', 'shopready-elementor-addon' ),
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
                            'shop_ready_global_sticky' => ['yes']
                        ],
                        'selectors' => [
                            '{{WRAPPER}}.shop-ready-sticky.shop-ready-sticky-container' => 'height: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );

                $element->add_control(
                    'shop_ready_sticky_offset',
                    [
                        'label' => esc_html__( 'Sticky Offset', 'shopready-elementor-addon' ),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'min' => 0,
                        'max' => 900,
                        'step' => 5,
                        'default' => 110,
                        'condition' => [
                            'shop_ready_global_sticky' => ['yes']
                        ],

                        
                    ]
                );

                

                $element->add_control(
                    'shop_ready_sticky_offset_z_index',
                    [
                        'label' => esc_html__( 'Z-index', 'shopready-elementor-addon' ),
                        'type' => \Elementor\Controls_Manager::NUMBER,
                        'min' => -2000,
                        'max' => 2000,
                        'step' => 5,
                        'condition' => [
                            'shop_ready_global_sticky' => ['yes']
                        ],
                        'selectors' => [
                            '{{WRAPPER}}.shop-ready-sticky.shop-ready-sticky-container' => 'z-index: {{VALUE}};',
                        ],
                    ]
                );

                $element->add_control(
                    'shop_ready_sticky_sec_margin',
                    [
                        'label' => __( 'Margin', 'shopready-elementor-addon' ),
                        'type' => \Elementor\Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}}.shop-ready-sticky.shop-ready-sticky-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                $element->add_control(
                    'shop_ready_sticky_sec_padding',
                    [
                        'label' => __( 'Padding', 'shopready-elementor-addon' ),
                        'type' => \Elementor\Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', '%', 'em' ],
                        'selectors' => [
                            '{{WRAPPER}}.shop-ready-sticky.shop-ready-sticky-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
                );

                $element->add_group_control(
                    \Elementor\Group_Control_Border::get_type(),
                    [
                        'name' => 'shop_ready_sticky_border',
                        'label' => __( 'Border', 'shopready-elementor-addon' ),
                        'selector' => '{{WRAPPER}}.shop-ready-sticky.shop-ready-sticky-container',
                    ]
                );

                $element->add_control(
                    'shop_ready_sticky_popup_wqeiuty_position',
                    [
                        'label' => esc_html__( 'Position', 'shopready-elementor-addon' ),
                        'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                        'label_off' => esc_html__( 'Default', 'shopready-elementor-addon' ),
                        'label_on' => esc_html__( 'Custom', 'shopready-elementor-addon' ),
                        'return_value' => 'yes',
                        'condition' => [
                            'shop_ready_global_sticky' => ['yes']
                        ],
                    ]
                );
        
                $element->start_popover();
                $element->add_responsive_control(
                    'shop_ready_main_sectionwrsw_sticky_position_type',
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
                            '{{WRAPPER}}.shop-ready-sticky.shop-ready-sticky-container' => 'position: {{VALUE}};',
                        ],
                    ]
                );
        
                $element->add_responsive_control(
                    'shop_ready_main_section__ws_sticky_position_left',
                    [
                        'label' => esc_html__( 'Position Left', 'shopready-elementor-addon' ),
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
                            '{{WRAPPER}}.shop-ready-sticky.shop-ready-sticky-container' => 'left: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
        
                $element->add_responsive_control(
                    'shop_ready_main_global_sticky_position_top',
                    [
                        'label' => esc_html__( 'Position Top', 'shopready-elementor-addon' ),
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
                            '{{WRAPPER}}.shop-ready-sticky.shop-ready-sticky-container' => 'top: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
    
                $element->add_responsive_control(
                    'shop_ready_main_global_sticky_position_right',
                    [
                        'label' => esc_html__( 'Position Right', 'shopready-elementor-addon' ),
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
                            '{{WRAPPER}}.shop-ready-sticky.shop-ready-sticky-container' => 'right: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
    
                $element->add_responsive_control(
                    'shop_ready_main_global_sticky_position_bottom',
                    [
                        'label' => esc_html__( 'Position Bottom', 'shopready-elementor-addon' ),
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
                            '{{WRAPPER}}.shop-ready-sticky.shop-ready-sticky-container' => 'bottom: {{SIZE}}{{UNIT}};',
                        ],
                    ]
                );
    
                $element->end_popover();
    

                $element->add_control(
                    'shop_ready_sticky_heading',
                    [
                        'label' => esc_html__( 'Sticky Background', 'shopready-elementor-addon' ),
                        'type' => \Elementor\Controls_Manager::HEADING,
                        'separator' => 'before',
                        'condition' => [
                            'shop_ready_global_sticky' => ['yes']
                        ],
                        
                    ]
                );

                $element->add_group_control(
                    \Elementor\Group_Control_Background::get_type(),
                    [
                        'name' => 'shop_ready_sticky_offset_element_ready_sticky_offset_background',
                        'label' => esc_html__( 'Background', 'shopready-elementor-addon' ),
                        'types' => [ 'classic', 'gradient' ],
                        'selector' => '{{WRAPPER}}.shop-ready-sticky.shop-ready-sticky-container',
                        'condition' => [
                            'shop_ready_global_sticky' => ['yes']
                        ],
                    ]
                );

                $element->add_group_control(
                    \Elementor\Group_Control_Box_Shadow::get_type(),
                    [
                        'name' => 'shop_ready_sticky_offset__box_shadow',
                        'label' => esc_html__( 'Box Shadow', 'shopready-elementor-addon' ),
                        'selector' => '{{WRAPPER}}.shop-ready-sticky.shop-ready-sticky-container',
                        'condition' => [
                            'shop_ready_global_sticky' => ['yes']
                        ],
                    ]
                );

                

            $element->end_controls_section();

          
           
        }
    }
  
    public function after_section_render(\Elementor\Element_Base $element)
    {
        $data     = $element->get_data();
        $settings = $data['settings'];
      
        
        if  (
                (isset($settings['shop_ready_global_sticky']) && $settings['shop_ready_global_sticky'] == 'yes') || 
                (isset($settings['shop_ready_sticky_type']) && $settings['shop_ready_sticky_type'] != '')
            ){

            echo "
            <script>
                window.shop_ready_section_sticky_data.section".esc_js($data['id'])." = JSON.parse('".esc_js(json_encode($settings))."');
            </script>
            ";

             
       
    
        }
       
    }
    public function inline_script(){
        
		echo wp_kses('
			<script type="text/javascript">
				var shop_ready_section_sticky_data = {};
				var shop_ready_section_sticky_data_url = "";
			</script>
		',
        array(
            "script" => array(
            )
        )
    );
	}
   
  }