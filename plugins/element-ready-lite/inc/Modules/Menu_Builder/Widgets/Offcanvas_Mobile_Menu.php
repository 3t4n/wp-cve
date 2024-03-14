<?php

namespace Element_Ready\Modules\Menu_Builder\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Element_Ready\Modules\Menu_Builder\Base\Offcanvas_Mobile_Menu_Walker as Offcanvas_Nav_Walker;
use Element_Ready\Widget_Controls\Box_Style as Style_Box;
if ( ! defined( 'ABSPATH' ) ) exit;

require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/common/common.php' );
require_once( ELEMENT_READY_DIR_PATH . '/inc/style_controls/position/position.php' );

class Offcanvas_Mobile_Menu extends Widget_Base {

    use \Elementor\Element_Ready_Common_Style;
    use \Elementor\Element_Ready_Position_Style;
    use Style_Box;

    public $base;

    public function get_name() {
        return 'element-ready-mobile-offcanvas-menu';
    }
    public function get_keywords() {
		return ['element ready','mobile','offcanvas menu','mobile menu'];
	}
    public function get_title() {
        return esc_html__( 'ER Mobile Menu', 'element-ready-lite' );
    }

    public function get_icon() { 
        return 'eicon-menu-toggle';
    }

    public function get_style_depends() {
        return [
     
           'er-mobile-menu'
        ];
    }

    public function get_script_depends() {
        return [
            'element-ready-menu-frontend-script'
        ];
    }

    public function get_categories() {
        return [ 'element-ready-addons' ];
    }
    public function layout(){
        return[
            
            'style1' => esc_html__( 'Style 1', 'element-ready-lite' ),
         ];
    }

    public function menu_list(){

        $return_menus = [];
       
        $menus = wp_get_nav_menus(); 
      
        if(is_array($menus)){
           foreach($menus as $menu) {
            $return_menus[$menu->term_id] = esc_html($menu->name);  
           }

        }

        return $return_menus;
    }
   
    protected function register_controls() {

        $this->start_controls_section(
			'menu_layout',
			[
				'label' => esc_html__( 'Layout', 'element-ready-lite' ),
			]
        );

            $this->add_control(
                'menu_style',
                [
                    'label' => esc_html__( 'Style', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => 'style1',
                    'options' => $this->layout()
                ]
            );

        $this->end_controls_section();
    
        
           
        $this->start_controls_section(
            'section_tab',
            [
                'label' => esc_html__('Settings', 'element-ready-lite'),
            ]
        );

        $this->start_controls_tabs(
			'menu_type_tabs'
		);

     
		$this->start_controls_tab(
			'style_mobile_menu_tab',
			[
                'label' => esc_html__( 'Mobile Menu', 'element-ready-lite' ),
                'condition' => [
                    'menu_style' => ['style1']
                ],
			]
        );
        
     

        $this->add_control(
            'mobile_menu_selected',
            [
                'label' => esc_html__( 'Menu', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => $this->menu_list()
            ]
        );
   
        $this->add_control(
			'mobile_menu_icon_indicator',
			[
				'label' => esc_html__( 'Icon', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::ICON,
			]
        );
        
        $this->add_control(
            'mobile_menu_depth',
            [
                'label' => esc_html__( 'Nested Depth', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'default' => 3,
            ]
        );
        $this->add_control(
            'mobile_custom_element_main_popover-toggle',
            [
                'label' => esc_html__( 'Extra option', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_control(
            'mobile_wrapper_tag_type',
                [
                    'label'   => esc_html__( 'Container Tag', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''  => esc_html__( 'none', 'element-ready-lite' ),
                        'div'  => esc_html__( 'div', 'element-ready-lite' ),
                        'p'    => esc_html__( 'p', 'element-ready-lite' ),
                        'span' => esc_html__( 'span', 'element-ready-lite' ),
                        'i'    => esc_html__( 'i', 'element-ready-lite' ),
                        's'    => esc_html__( 's', 'element-ready-lite' ),
                        'b'    => esc_html__( 'b', 'element-ready-lite' ),
                        'p'    => esc_html__( 'P', 'element-ready-lite' ),
                        'ul'    => esc_html__( 'ul', 'element-ready-lite' ),
                    ],
                ]
        );

        $this->add_control(
            'mobile_anchore_wrapper_tag_before_type',
                [
                    'label'   => esc_html__( 'Link Wrapper Before', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''  => esc_html__( 'none', 'element-ready-lite' ),
                        '<div>'  => esc_html__( 'div', 'element-ready-lite' ),
                        '<p>'    => esc_html__( 'p', 'element-ready-lite' ),
                        '<span>' => esc_html__( 'span', 'element-ready-lite' ),
                        '<i>'    => esc_html__( 'i', 'element-ready-lite' ),
                        '<s>'    => esc_html__( 's', 'element-ready-lite' ),
                        '<b>'    => esc_html__( 'b', 'element-ready-lite' ),
                        '<p>'    => esc_html__( 'P', 'element-ready-lite' ),
                    ],
                ]
        );

        $this->add_control(
            'mobile_anchore_wrapper_tag_after_type',
                [
                    'label'   => esc_html__( 'Link Wrapper After', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''  => esc_html__( 'none', 'element-ready-lite' ),
                        '</div>'  => esc_html__( 'div', 'element-ready-lite' ),
                        '</p>'    => esc_html__( 'p', 'element-ready-lite' ),
                        '</span>' => esc_html__( 'span', 'element-ready-lite' ),
                        '</i>'    => esc_html__( 'i', 'element-ready-lite' ),
                        '/<s>'    => esc_html__( 's', 'element-ready-lite' ),
                        '</b>'    => esc_html__( 'b', 'element-ready-lite' ),
                        '</p>'    => esc_html__( 'P', 'element-ready-lite' ),
                    ],
                ]
        );

        $this->add_control(
            'mobile_anchore_text_before_tag_type',
                [
                    'label'   => esc_html__( ' Link Text before', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''  => esc_html__( 'none', 'element-ready-lite' ),
                        '<div>'  => esc_html__( 'div', 'element-ready-lite' ),
                        '<p>'    => esc_html__( 'p', 'element-ready-lite' ),
                        '<span>' => esc_html__( 'span', 'element-ready-lite' ),
                        '<i>'    => esc_html__( 'i', 'element-ready-lite' ),
                        '<s>'    => esc_html__( 's', 'element-ready-lite' ),
                        '<b>'    => esc_html__( 'b', 'element-ready-lite' ),
                        '<p>'    => esc_html__( 'P', 'element-ready-lite' ),
                    ],
                ]
        );

        $this->add_control(
            'mobile_anchore_text_after_tag_type',
                [
                    'label'   => esc_html__( 'Link Text after', 'element-ready-lite' ),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        ''  => esc_html__( 'none', 'element-ready-lite' ),
                        '</div>'  => esc_html__( 'div', 'element-ready-lite' ),
                        '</p>'    => esc_html__( 'p', 'element-ready-lite' ),
                        '</span>' => esc_html__( 'span', 'element-ready-lite' ),
                        '</i>'    => esc_html__( 'i', 'element-ready-lite' ),
                        '</s>'    => esc_html__( 's', 'element-ready-lite' ),
                        '</b>'    => esc_html__( 'b', 'element-ready-lite' ),
                        '</p>'    => esc_html__( 'P', 'element-ready-lite' ),
                    ],
                ]
        );

        $this->end_popover();

        $this->add_control(
            'mobile_custom_element_popover-toggle',
            [
                'label' => esc_html__( 'Extra Element', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

            $this->add_control(
                'mobile_menu_container_custom_class',
                [
                    'label' => esc_html__( 'Container Class', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => esc_html__( '.prefix-custom-container', 'element-ready-lite' ),
                ]
            );

            $this->add_control(
                'mobile_menu_container_custom_id',
                [
                    'label' => esc_html__( 'Container Id', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => esc_html__( 'custom-id', 'element-ready-lite' ),
                ]
            );

            $this->add_control(
                'mobile_menu_custom_class',
                [
                    'label' => esc_html__( 'Menu Class', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => esc_html__( '.custom-menu-cls .type', 'element-ready-lite' ),
                ]
            );

            $this->add_control(
                'mobile_menu_custom_id',
                [
                    'label' => esc_html__( 'Menu Id', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => '',
                    'placeholder' => esc_html__( 'custom-id', 'element-ready-lite' ),
                ]
            );

        $this->end_popover();

        $this->add_responsive_control(
            'mobile_menu_align', [
                'label'   => esc_html__( 'Alignment', 'element-ready-lite' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [

            'left'		 => [
                
                'title' => esc_html__( 'Left', 'element-ready-lite' ),
                'icon'  => 'fa fa-align-left',
            
            ],
                'center'	     => [
                
                'title' => esc_html__( 'Center', 'element-ready-lite' ),
                'icon'  => 'fa fa-align-center',
            
            ],
            'right'	 => [

                'title' => esc_html__( 'Right', 'element-ready-lite' ),
                'icon'  => 'fa fa-align-right',
                
            ],
            
            'justify'	 => [

            'title' => esc_html__( 'Justified', 'element-ready-lite' ),
            'icon'  => 'fa fa-align-justify',
            
                    ],
            ],
            
            'selectors' => [
                    '{{WRAPPER}} ul li'   => 'text-align: {{VALUE}};',
                ],
            ]
        );//Responsive control end


		$this->end_controls_tab();

		$this->end_controls_tabs();
      
        $this->end_controls_section();
      
        $this->box_css(['selector'=>'{{WRAPPER}} .element-ready-mobile-menu-wr','slug'=>'mobile_menu_container','title'=>'Menu Container']);
        $this->position_css(['selector'=>'{{WRAPPER}} .element-ready-menu-expand','slug'=>'mobile_menu_indicator','title'=>'Icon Indicator Pos']);
    $this->start_controls_section('menu_item_lis_section',
        [
            'label' => esc_html__( 'Nav Item', 'element-ready-lite' ),
            'tab'   => Controls_Manager::TAB_STYLE,
       
        ]
    );


        $this->start_controls_tabs(
            'menu_drop_down_sec_type_tabs'
        );

           $this->start_controls_tab(
                    'menu_drop_down_sec_menu_tab',
                    [
                        'label' => esc_html__( 'After icon', 'element-ready-lite' ),
                    ]
                );
        
                        $this->add_control(
                            'menu_item_li_dropdown_after_icon_heading',
                            [
                                'label' => esc_html__( 'Indicator Icon', 'element-ready-lite' ),
                                'type' => \Elementor\Controls_Manager::HEADING,
                                'separator' => 'before',
                            ]
                        );
        
                        $this->add_control(
                            'menu_item_li_dropdown_after_icon_color', [
        
                                'label'		 => esc_html__( 'Icon Color', 'element-ready-lite' ),
                                'type'		 => Controls_Manager::COLOR,
                                'selectors'	 => [
        
                                    '{{WRAPPER}} .element-ready-menu-expand i' => 'color: {{VALUE}};',
                                
                                ],
                            ]
                        );
        
                        $this->add_group_control(
                            Group_Control_Typography:: get_type(),
                            [
                                'name'     => 'menu_item_li_after_dropdown_typho',
                                'label'    => esc_html__( 'Dropdown Arrow', 'element-ready-lite' ),
                                'selector' => '{{WRAPPER}} .element-ready-menu-expand i',
                            ]
                        );
        
                        $this->add_responsive_control(
                            'menu_item_li_after_dropdown_padding',
                            [
                                'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                                'type'       => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%', 'em' ],
                                'selectors'  => [
                                    '{{WRAPPER}} .element-ready-menu-expand i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                  
                                    
                                ],
                                'separator' => 'before',
                            ]
                        );
        
                        $this->add_responsive_control(
                            'menu_item_li_after_dropdown_margin',
                            [
                                'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                                'type'       => Controls_Manager::DIMENSIONS,
                                'size_units' => [ 'px', '%', 'em' ],
                                'selectors'  => [
                                   
                                    '{{WRAPPER}}  .element-ready-menu-expand ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                  
                                
                                    
                                ],
                                'separator' => 'before',
                            ]
                        );
        
                        $this->end_controls_tab();
            $this->end_controls_tabs();
            $this->add_control(
                'menu_item_li_dropdown_item_heading',
                [
                    'label' => esc_html__( 'Item text', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );
           
        $this->start_controls_tabs(
            'menu_items_tabs'
        );


        $this->start_controls_tab(
			'style_main_menu_item_tab',
			[
				'label' => esc_html__( 'Normal', 'element-ready-lite' ),
			]
        );
        
        $this->add_control(
            '_custom_continer_element_popover-toggle',
            [
                'label' => esc_html__( 'Menu Pointer', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();
        $this->add_control(
            'menu_item_li_menu_pointer',
            [
                'label' => esc_html__( 'Menu Pointer', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                   
                    'element-ready-underline' => esc_html__( 'Underline', 'element-ready-lite' ),
                    'element-ready-doubleline' => esc_html__( 'Doubleline', 'element-ready-lite' ),
                    'element-ready-background' => esc_html__( 'Background', 'element-ready-lite' ),
                    '' => esc_html__( 'None', 'element-ready-lite' ),
                ],
            ]
        );  

        $this->add_control(
            'menu_item_li_menu_hover_pointer',
            [
                'label' => esc_html__( 'Hover Pointer Effect', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                   
                    'grow-hover' => esc_html__( 'Grow', 'element-ready-lite' ),
                    'fade-hover' => esc_html__( 'Fade', 'element-ready-lite' ),
                    'slide-hover' => esc_html__( 'Slide', 'element-ready-lite' ),
                    '' => esc_html__( 'None', 'element-ready-lite' ),
                ],
            ]
        ); 

        $this->add_control(
            '_menu_item_li_popup_iuty_position',
            [
                'label' => esc_html__( 'Advanced', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                'return_value' => 'yes',
                'condition' => [
                    'menu_item_li_menu_pointer!' => ''
                ],
            ]
        );

        $this->start_popover();

            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'menu_item_hover_animqwer_li_background',
                    'label' => esc_html__( 'Background', 'element-ready-lite' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .element-ready-underline > a::before,{{WRAPPER}} .element-ready-doubleline > a::before,{{WRAPPER}} .element-ready-doubleline > a::after,{{WRAPPER}} .element-ready-background > a::before',
                   
                ]
            );
    
            $this->add_responsive_control(
                'menu_item_hover_aminoiur_height',
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
                        '{{WRAPPER}} .element-ready-underline > a::before,{{WRAPPER}} .element-ready-doubleline > a::before,{{WRAPPER}} .element-ready-doubleline > a::after,{{WRAPPER}} .element-ready-background > a::before' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'menu_item_hover_amin_position_bottom',
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
                        '{{WRAPPER}} .element-ready-underline > a::before,{{WRAPPER}} .element-ready-doubleline > a::before,{{WRAPPER}} .element-ready-doubleline > a::after,{{WRAPPER}} .element-ready-background > a::before' => 'bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'menu_item_hover_amin_position_left',
                [
                    'label' => esc_html__( 'Position Left', 'element-ready-lite' ),
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
                        '{{WRAPPER}} .element-ready-underline > a::before,{{WRAPPER}} .element-ready-doubleline > a::before,{{WRAPPER}} .element-ready-doubleline > a::after,{{WRAPPER}} .element-ready-background > a::before' => 'left: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'menu_item_hover_amin_position_top',
                [
                    'label' => esc_html__( 'Position top', 'element-ready-lite' ),
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
                        '{{WRAPPER}} .element-ready-underline > a::before,{{WRAPPER}} .element-ready-doubleline > a::before,{{WRAPPER}} .element-ready-doubleline > a::after,{{WRAPPER}} .element-ready-background > a::before' => 'top: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_popover();
        $this->end_popover();

        $this->add_control(
            'menu_item_li_color', [

                'label'		 => esc_html__( 'Color', 'element-ready-lite' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [
  
                    '{{WRAPPER}} ul > li ' => 'color: {{VALUE}};',
                    '{{WRAPPER}} ul > li a' => 'color: {{VALUE}}',
              
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography:: get_type(),
            [
                'name'     => 'menu_item_li_typho',
                'label'    => esc_html__( 'Text Typography', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} ul > li,{{WRAPPER}} ul > li a',
            ]
        );


        $this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'menu_item_lisdsd_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
				'selector' => '{{WRAPPER}} ul > li a,{{WRAPPER}} ul > li',
			]
		);

        $this->add_control(
            'menu_item_li_bgcolor', [

                'label'		 => esc_html__( 'Background', 'element-ready-lite' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [
                '{{WRAPPER}} ul > li' => 'background: {{VALUE}};',
                ],
              
            ]
        );
 

        $this->add_responsive_control(
            'menu_itemasdasd_li_padding',
            [
                'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                  
                    
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li_section_margin',
            [
                'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} ul > li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li__border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul > li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'menu_item_li__section_border',
                'label' => esc_html__( 'Border', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} ul > li a',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_item_li__section_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} ul > li a',
            ]
        );

        $this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_menu_item_tab',
			[
				'label' => esc_html__( 'Hover', 'element-ready-lite' ),
			]
        );

        $this->add_control(
            'menu_item_li_hover_color', [

                'label'		 => esc_html__( 'Color', 'element-ready-lite' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [

               
                '{{WRAPPER}} ul > li:hover' => 'color: {{VALUE}};',
                '{{WRAPPER}} ul > li:hover a' => 'color: {{VALUE}};',
               
                
                ],
            ]
        );

       
        $this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'menu_item_li_hover_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
				'selector' => '{{WRAPPER}} ul li:hover a ,{{WRAPPER}} ul > li:hover a',
			]
        );
        
        $this->add_control(
            'menu_item_li_hover_bgcolor', [

                'label'		 => esc_html__( 'Background', 'element-ready-lite' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [
                    '{{WRAPPER}} ul > li:hover' => 'background: {{VALUE}};',
                ],
              
            ]
        );


        $this->add_group_control(
            Group_Control_Typography:: get_type(),
            [
                'name'     => 'menu_item_li_hover_typho',
                'label'    => esc_html__( 'Typography', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} ul li:hover a',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li_hover_padding',
            [
                'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [

                   
                    '{{WRAPPER}} ul > li:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                  
                   
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li_hover_section_margin',
            [
                'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                  
                    '{{WRAPPER}} ul > li:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 
                
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_item_li_hover_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul > li:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                   
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'menu_item_li_hover_section_border',
                'label' => esc_html__( 'Border', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} ul > li:hover',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_item_li_hover_section_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} ul > li:hover',
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
      
    $this->end_controls_section();

    $this->start_controls_section('menu_dropdown_sub_item_lis_section',
        [
        'label' => esc_html__( 'Sub menu Item', 'element-ready-lite' ),
        'tab'   => Controls_Manager::TAB_STYLE,
       
        ]
    );

            $this->add_control(
                'menu_dropdown_item_li_dropdown_icon_heading',
                [
                    'label' => esc_html__( 'Dropdown Icon', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );


            $this->add_control(
                'menu_dropdown_item_li_dropdown_icon_color', [

                    'label'		 => esc_html__( 'Dropdown icon Color', 'element-ready-lite' ),
                    'type'		 => Controls_Manager::COLOR,
                    'selectors'	 => [

                    '{{WRAPPER}} ul  li .sub-menu i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} ul li .sub-menu a::after' => 'color: {{VALUE}};',

                    
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography:: get_type(),
                [
                    'name'     => 'menu_dropdown_item_li_dropdown_typho',
                    'label'    => esc_html__( 'Dropdown Arrow', 'element-ready-lite' ),
                    'selector' => '{{WRAPPER}} ul li .sub-menu i ,{{WRAPPER}} ul li .sub-menu a::after',
                ]
            );

            $this->add_responsive_control(
                'menu_dropdown_item_li_dropdown_padding',
                [
                    'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} ul li .sub-menu a::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} ul li .sub-menu i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    
                        
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'menu_dropdown_item_li_dropdown_margin',
                [
                    'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} ul li .sub-menu a::after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        '{{WRAPPER}} ul li .sub-menu i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    
                        
                    ],
                    'separator' => 'before',
                ]
            );

          

            $this->add_control(
                '_sub_menu_box_popover_section_sizen',
                [
                    'label' => esc_html__( 'Advanced', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
                    'label_off' => esc_html__( 'Default', 'element-ready-lite' ),
                    'label_on' => esc_html__( 'Custom', 'element-ready-lite' ),
                    'return_value' => 'yes',
                ]
            );

            $this->start_popover();

            $this->add_control(
                '_sub_menumain_sectiona_auto_width',
                [
                    'label' => esc_html__( 'Auto Width', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' =>[
                        'auto' => esc_html('Yes','element-ready-lite'),
                        '' => esc_html('No','element-ready-lite')
                    ],
                    
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-sub-menu' => 'width: {{VALUE}};',
                        '{{WRAPPER}} ul li .sub-menu' => 'width: {{VALUE}};',
                    ],
                ]
            );
    
            $this->add_responsive_control(
                '_sub_menumain_section_min_width',
                [
                    'label' => esc_html__( 'min Width', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%','vw' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1600,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-sub-menu' => 'min-width: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} ul li .sub-menu' => 'min-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
    
            
            $this->add_control(
                'er_sub_menu__dropdown_yui_heading',
                [
                    'label' => esc_html__( 'Background', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'er_sub_menu_main_section_background',
                    'label' => esc_html__( 'Background', 'element-ready-lite' ),
                    'types' => [ 'classic', 'gradient','video' ],
                    'selector' => '{{WRAPPER}} .element-ready-sub-menu,{{WRAPPER}} ul li .sub-menu',
                ]
            );

            $this->add_responsive_control(
                'er_sub_menu_uiy_main_section_padding',
                [
                    'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .element-ready-sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                      
                       
                    ],
                    'separator' => 'before',
                ]
            );
    
            $this->add_responsive_control(
                'er_sub_menu_uiy_main_section_margin',
                [
                    'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                    'type'       => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors'  => [
                        '{{WRAPPER}} .element-ready-sub-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                       
                       
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_responsive_control(
                'er_sub_menu_uiy_main_sectionewrt__border_radius',
                [
                    'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                    'type'      => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', '%', 'em' ],
                    'selectors' => [
                        '{{WRAPPER}} ul li .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                      
                    ],
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'element_ready_sub_menu_uiy_main_sectionewrts_box_shadow',
                    'label' => esc_html__( 'Box Shadow', 'element-ready-lite' ),
                    'selector' => '{{WRAPPER}} ul li .element-ready-sub-menu',
                ]
            );
    
           
            $this->end_popover();

            $this->add_control(
                'element_ready_sub_menu_uyi_section_popover_container_position',
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
                'element_ready_sub_menu_uyi_section__container_t_position_type',
                [
                    'label' => esc_html__( 'Position', 'element-ready-lite' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '',
                    'options' => [
                        'fixed' => esc_html__('Fixed','element-ready-lite'),
                        'absolute' => esc_html__('Absolute','element-ready-lite'),
                        'relative' => esc_html__('Relative','element-ready-lite'),
                        'sticky' => esc_html__('Sticky','element-ready-lite'),
                        'static' => esc_html__('Static','element-ready-lite'),
                        'inherit' => esc_html__('inherit','element-ready-lite'),
                        '' => esc_html__('none','element-ready-lite'),
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-sub-menu' => 'position: {{VALUE}};',
                       
                    ],
                ]
            );
    
            $this->add_responsive_control(
                'element_ready_sub_menu_uyi_section_container_r_position_left',
                [
                    'label' => esc_html__( 'Position Left', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -2100,
                            'max' => 2100,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-sub-menu' => 'left: {{SIZE}}{{UNIT}};',
                       
                    ],
                ]
            );
    
            $this->add_responsive_control(
                'element_ready_sub_menu_uyi_conainer_r_position_top',
                [
                    'label' => esc_html__( 'Position Top', 'element-ready-lite' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => -2100,
                            'max' => 2100,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => -100,
                            'max' => 100,
                        ],
                    ],
                   
                    'selectors' => [
                        '{{WRAPPER}} .element-ready-sub-menu' => 'top: {{SIZE}}{{UNIT}};',
                       
                    ],
                ]
            );
            $this->end_popover();
    
        $this->add_control(
            'menu_dropdown_item_li_dropdown_item_heading',
            [
                'label' => esc_html__( 'Item text', 'element-ready-lite' ),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->start_controls_tabs(
            'sub_menu_items_tabs'
        );

        $this->start_controls_tab(
			'style_main_sub_menu_dropdown_item_tab',
			[
				'label' => esc_html__( 'Normal', 'element-ready-lite' ),
			]
		);

        $this->add_control(
            'menu_dropdown_item_li_color', [

                'label'		 => esc_html__( 'Color', 'element-ready-lite' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [

                    '{{WRAPPER}} ul li .sub-menu li' => 'color: {{VALUE}};',
                    '{{WRAPPER}} ul li .sub-menu li > a' => 'color: {{VALUE}};',
            
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography:: get_type(),
            [
                'name'     => 'menu_dropdown_item_li_typho',
                'label'    => esc_html__( 'Text Typography', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} ul li .sub-menu li a,{{WRAPPER}} ul li .sub-menu li > a',
            ]
        );


        $this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'menu_dropdown_item_li_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
				'selector' => '{{WRAPPER}} ul li .sub-menu li > a,{{WRAPPER}} ul li .sub-menu li',
			]
		);

        $this->add_control(
            'menu_dropdown_item_li_ho_bgcolor', [

                'label'		 => esc_html__( 'Background', 'element-ready-lite' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [
                    '{{WRAPPER}} ul li .sub-menu li' => 'background: {{VALUE}};',
                    '{{WRAPPER}} ul li .element-ready-sub-menu li' => 'background: {{VALUE}};',
                ],
            ]
        );

      

        $this->add_responsive_control(
            'menu_dropdown_item_li_padding',
            [
                'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [

                    '{{WRAPPER}} ul li .sub-menu li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ul li .element-ready-sub-menu li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
               
                    
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_section_margin',
            [
                'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [

                    '{{WRAPPER}} ul li .sub-menu li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li__border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul li .sub-menu li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'menu_dropdown_item_li__section_border',
                'label' => esc_html__( 'Border', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} ul li .sub-menu li,{{WRAPPER}} ul li .element-ready-sub-menu li',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_dropdown_item_li__section_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'element-ready-lite' ),
                'selector' => ' {{WRAPPER}} ul li .sub-menu li a',
            ]
        );

        $this->end_controls_tab();

		$this->start_controls_tab(
			'style_hover_menu_dropdown_item_tab',
			[
				'label' => esc_html__( 'Hover', 'element-ready-lite' ),
			]
        );

        $this->add_control(
            'menu_dropdown_item_li_hover_color', [

                'label'		 => esc_html__( 'Color', 'element-ready-lite' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [

                    '{{WRAPPER}} ul li .sub-menu li:hover ' => 'color: {{VALUE}};',
                    '{{WRAPPER}} ul .sub-menu li:hover a' => 'color: {{VALUE}};',
                   
                ],
            ]
        );

       
        $this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'menu_dropdown_item_li_hover_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'element-ready-lite' ),
				'selector' => '{{WRAPPER}} ul .sub-menu li:hover a, {{WRAPPER}} ul .sub-menu li:hover a',
			]
        );
        
        $this->add_control(
            'menu_dropdown_item_li_hover_bgcolor', [

                'label'		 => esc_html__( 'Background', 'element-ready-lite' ),
                'type'		 => Controls_Manager::COLOR,
                'selectors'	 => [

                    '{{WRAPPER}} ul .sub-menu li:hover > a' => 'background: {{VALUE}};',
                    '{{WRAPPER}} ul li .element-ready-sub-menu li:hover' => 'background: {{VALUE}};',
                   
                ],
            ]
        );

       
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'menu_dropdown_item_hli_border',
                'label'    => esc_html__( 'Border', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} ul li .sub-menu li:hover, {{WRAPPER}} .sub-menu li:hover',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography:: get_type(),
            [
                'name'     => 'menu_dropdown_item_li_hover_typho',
                'label'    => esc_html__( 'Typography', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} ul .sub-menu li:hover > a',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_hover_padding',
            [
                'label'      => esc_html__( 'Padding', 'element-ready-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [

                    '{{WRAPPER}} ul .sub-menu li:hover > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} ul li .element-ready-sub-menu li:hover a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                
                
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_hover_section_margin',
            [
                'label'      => esc_html__( 'Margin', 'element-ready-lite' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} ul .sub-menu li:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                  
                 
                
                
                ],
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'menu_dropdown_item_li_hover_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'element-ready-lite' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} ul .element-ready-sub-menu li:hover > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    
                ],
                'separator' => 'before',
            ]
        );

      
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'menu_dropdown_item_li_hover_section_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'element-ready-lite' ),
                'selector' => '{{WRAPPER}} ul .sub-menu li:hover,{{WRAPPER}} ul li .element-ready-sub-menu li:hover',
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();

    $this->end_controls_section();
    
     
    } //Register control end
  
    protected function render( ) { 

        $widget_id      = 'element-ready-'.$this->get_id().'-';
        $settings       = $this->get_settings();
        $mobile_menu_id = $settings[ 'mobile_menu_selected' ];
        $mobile_menu    = [];

        $nav_walker_default = [];
        $nav_walker_default[ 'first_li_menu_pointer' ]       = $settings['menu_item_li_menu_pointer'];
        $nav_walker_default[ 'first_li_menu_hover_pointer' ] = $settings['menu_item_li_menu_hover_pointer'];

        $nav_walker_default[ 'sub_menu_ul_cls' ] = 'sub-menu element-ready-sub-menu'; 
        $args = [
            'menu'            => $mobile_menu_id,
            'container'       => $settings[ 'mobile_wrapper_tag_type' ] !=''?$settings['mobile_wrapper_tag_type']:false,
            'container_id'    => $settings[ 'mobile_menu_container_custom_id' ] !=''?$settings['mobile_menu_container_custom_id']:false,
            'container_class' => $settings[ 'mobile_menu_container_custom_class' ] !=''?$settings['mobile_menu_container_custom_class']:false,
            'menu_class'      => $settings[ 'mobile_menu_custom_class' ]==''?'element_ready_offcanvas_main_menu ':$settings['mobile_menu_custom_class'].' element_ready_offcanvas_main_menu',
            'depth'           => $settings[ 'mobile_menu_depth' ],
            'walker'          => new Offcanvas_Nav_Walker( $nav_walker_default ),
            
        ]; 
 
        if($settings[ 'mobile_menu_custom_id' ] !=''){
            $args[ 'menu_id' ] = $settings[ 'mobile_menu_custom_id' ];
        }
        $link_before_after = element_ready_menu_html_tag_validate($settings[ 'mobile_anchore_text_before_tag_type' ],$settings['mobile_anchore_text_after_tag_type']);    
        if( $link_before_after){
            $args[ 'link_before' ] = $link_before_after[ 'start' ];
            $args[ 'link_after' ] = $link_before_after[ 'end' ];
        }
        $before_after = element_ready_menu_html_tag_validate($settings[ 'mobile_anchore_wrapper_tag_before_type' ],$settings['mobile_anchore_wrapper_tag_after_type']);    
        if($before_after){
            $args[ 'before' ] = $before_after[ 'start' ];
            $args[ 'after' ] = $before_after[ 'end' ];
        } 
  
    ?>    
        <!--====== Header START ======-->
        <?php if($settings['menu_style'] == 'style1'): ?>
           <?php include('layout/mobile-menu/style1.php'); ?>   
        <?php endif; ?>
        <!--====== PART ENDS ======-->
    <?php  

    }
    
    protected function content_template() { }

}