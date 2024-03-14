<?php

namespace Element_Ready\Widgets\popup;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Element_Ready\Widget_Controls\User_Style;

if ( ! defined( 'ABSPATH' ) ) exit;

class PopUp extends Widget_Base {

    use User_Style;
    public $base;

    public function get_name() {
        return 'element-ready-global-popup';
    }

    public function get_keywords() {
		return ['element ready','popup'];
	}

    public function get_title() {
        return esc_html__( 'ER PopUp', 'element-ready-lite' );
    }

    public function get_script_depends() {

        return [
            'element-ready-core'
        ];
    }
    public function get_style_depends() {

        wp_register_style( 'eready-popup' , ELEMENT_READY_ROOT_CSS. 'widgets/popup.css' );
        return [ 'eready-popup' ];
    }
    public function get_icon() { 

        return 'eicon-editor-external-link';
    }

    public function get_categories() {

        return [ 'element-ready-addons' ];
    }

    public function close_icon_css($title = 'icon style',$slug='icon_close_style',$element_name='ICON_CLose_ELEMENT_NAME') {
        
        
        $widget = $this->get_name().'_'.element_ready_camelize($slug);
        
        /*----------------------------
            ELEMENT__STYLE
        -----------------------------*/
        $this->start_controls_section(
            $widget.'_style_section',
            [
                'label' => $title,
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( $widget.'_tabs_style' );
                $this->start_controls_tab(
                    $widget.'_normal_tab',
                    [
                        'label' => esc_html__( 'Normal', 'element-ready-lite' ),
                    ]
                );

                    // Typgraphy
                    $this->add_group_control(
                        Group_Control_Typography:: get_type(),
                        [
                            'name'      => $widget.'_typography',
                            'selector'  => '{{WRAPPER}} .er-ready-count-close-btn i',
                        ]
                    );

                    // Icon Color
                    $this->add_control(
                        $widget.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'default'   => '',
                            'selectors' => [
                                '{{WRAPPER}} .er-ready-count-close-btn i' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => $widget.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} .er-ready-count-close-btn i',
                        ]
                    );

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => $widget.'_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} .er-ready-count-close-btn i',
                        ]
                    );

                    // Radius
                    $this->add_responsive_control(
                        $widget.'_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .er-ready-count-close-btn i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow::get_type(),
                        [
                            'name'     => $widget.'_shadow',
                            'selector' => '{{WRAPPER}} .er-ready-count-close-btn i',
                        ]
                    );

                    // Margin
                    $this->add_responsive_control(
                        $widget.'_margin',
                        [
                            'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .er-ready-count-close-btn i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    // Padding
                    $this->add_responsive_control(
                        $widget.'_padding',
                        [
                            'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} .er-ready-count-close-btn i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    $widget.'_hover_tab',
                    [
                        'label' => esc_html__( 'Hover', 'element-ready-lite' ),
                    ]
                );

                    //Hover Color
                    $this->add_control(
                        'hover_'.$element_name.'_color',
                        [
                            'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                            'type'      => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} :hover .er-ready-count-close-btn i, {{WRAPPER}} :focus .er-ready-count-close-btn i' => 'color: {{VALUE}};',
                            ],
                        ]
                    );

                    // Hover Background
                    $this->add_group_control(
                        Group_Control_Background:: get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_background',
                            'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                            'types'    => [ 'classic', 'gradient' ],
                            'selector' => '{{WRAPPER}} :hover .er-ready-count-close-btn i,{{WRAPPER}} :hover .er-ready-count-close-btn svg,{{WRAPPER}} :focus .er-ready-count-close-btn i',
                        ]
                    );	

                    // Border
                    $this->add_group_control(
                        Group_Control_Border:: get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_border',
                            'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                            'selector' => '{{WRAPPER}} :hover .er-ready-count-close-btn i,{{WRAPPER}} :hover .er-ready-count-close-btn i',
                        ]
                    );

                    // Radius
                    $this->add_responsive_control(
                        'hover_'.$element_name.'_radius',
                        [
                            'label'      => esc_html__( 'Border Radius', 'element-ready-lite' ),
                            'type'       => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', '%', 'em' ],
                            'selectors'  => [
                                '{{WRAPPER}} :hover .er-ready-count-close-btn i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                    // Shadow
                    $this->add_group_control(
                        Group_Control_Box_Shadow:: get_type(),
                        [
                            'name'     => 'hover_'.$element_name.'_shadow',
                            'selector' => '{{WRAPPER}} :hover .er-ready-count-close-btn i',
                        ]
                    );
                $this->end_controls_tab();
            $this->end_controls_tabs();

            $this->add_responsive_control(
                $widget.'_section___section_show_hide_'.$element_name.'_display',
                [
                    'label' => esc_html__( 'Display', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'flex'         => esc_html__( 'Flex', 'element-ready-lite' ),
                        'block'        => esc_html__( 'Block', 'element-ready-lite' ),
                        'inline-block' => esc_html__( 'Inline Block', 'element-ready-lite' ),
                        'grid'         => esc_html__( 'Grid', 'element-ready-lite' ),
                        'none'         => esc_html__( 'None', 'element-ready-lite' ),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .er-ready-count-close-btn' => 'display: {{VALUE}};',
                    ],
                ]
            );
            $this->add_control(
                $widget.'_section___section_popover_'.$element_name.'_position',
                [
                    'label' => esc_html__( 'Position', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                    'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                    'return_value' => 'yes',
                ]
            );
    
            $this->start_popover();
            $this->add_responsive_control(
                $widget.'_section__'.$element_name.'_position_type',
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
                        '{{WRAPPER}} .er-ready-count-close-btn' => 'position: {{VALUE}};',
                    ],
                  
                ]
            );
    
            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'_position_left',
                [
                    'label' => esc_html__( 'Position Left', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
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
                        '{{WRAPPER}} .er-ready-count-close-btn' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
    
            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'_r_position_top',
                [
                    'label' => esc_html__( 'Position Top', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
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
                        '{{WRAPPER}} .er-ready-count-close-btn' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'_r_position_bottom',
                [
                    'label' => esc_html__( 'Position Bottom', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
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
                        '{{WRAPPER}} .er-ready-count-close-btn' => 'bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                $widget.'main_section_'.$element_name.'_r_position_right',
                [
                    'label' => esc_html__( 'Position Right', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
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
                        '{{WRAPPER}} .er-ready-count-close-btn' => 'right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->end_popover();
        $this->end_controls_section();
        /*----------------------------
            ELEMENT__STYLE END
        -----------------------------*/
    }

    public function layout(){

        return[
            'style1'   => esc_html__( 'style1', 'element-ready-lite' ),
        ];
    }
 
    protected function register_controls() {

        $this->start_controls_section(
			'menu_layout',
			[
				'label' => esc_html__( 'Layout', 'element-ready-lite' ),
			]
        );

            $this->add_control(
                '_style',
                [
                    'label' => esc_html__( 'Style', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'style1',
                    'options' => $this->layout()
                ]
            );

            $this->add_control(
                'modal_template_id',
                [
                    'label'     => esc_html__( 'Select Content Template', 'element-ready-lite' ),
                    'type'      => Controls_Manager::SELECT,
                    'default'   => '0',
                    'options'   => element_ready_elementor_template(),
                    'description' => esc_html__( 'Please select elementor templete from here, if not create elementor template from menu', 'element-ready-lite' )
                   
                ]
            );

            $this->add_control(
                'close_icon',
                [
                    'label' => esc_html__( 'Close Icon', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-times',
                        'library' => 'solid',
                    ],
                ]
            );

   
        $this->end_controls_section();

        $this->start_controls_section(
            'section_interface_fields',
            [
                'label' => esc_html__('Interface', 'element-ready-lite'),
            ]
        );

            
            $this->add_control(
                'interface_icon',
                [
                    'label' => esc_html__( 'Icon', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::ICONS,
                    'default' => [
                        'value' => 'fas fa-user',
                        'library' => 'solid',
                    ],
                ]
            );

            $this->add_control(
                'interface_text',
                [
    
                    'label' => esc_html__( 'Text', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__( 'Login', 'element-ready-lite' ),
                    'default' => esc_html__('login','element-ready-lite')
                    
                ]
            );

        $this->end_controls_section();

       $this->close_icon_css(esc_html__('Close Icon','element-ready-lite'));
       $this->icon_css(esc_html__('Interface Icon','element-ready-lite'));
       $this->interface_text_css(esc_html__('Interface Text','element-ready-lite'),'interface_text');
       $this->popup_css(esc_html__('PopUp box','element-ready-lite'),'popup_box_cont','pop_box_element');
       
    } //Register control end

    protected function render() { 

        $settings     = $this->get_settings();
        $widget_id    = 'element-ready-'.$this->get_id().'-';
        include('popup/style1.php');
 
    }
    
    protected function content_template() {}
}