<?php

namespace Element_Ready\Widgets\accordion;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Plugin;

use \Element_Ready\Base\Controls\Widget_Control\Element_ready_common_control as Content_Style;
// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

class Element_Ready_Adv_Accordion extends Widget_Base{
    use Content_Style;
    
    public function get_name()
    {
        return 'Element_Ready_Adv_Accordion';
    }

    public function get_title()
    {
        return esc_html__('ER Advanced Accordion', 'element-ready-lite');
    }

    public function get_icon()
    {
        return 'eicon-accordion';
    }

    public function show_in_panel(){
       return element_ready_get_components_option('accordion');
    }

    public function get_categories()
    {
        return ['element-ready-addons'];
    }

    public function get_keywords() {
        return [ 'Advanced accordion', 'faqs' ];
    }

    public function get_script_depends() {

        return [
            'element-ready-core'
        ];
    }

    public function get_style_depends() {
        wp_register_style( 'eready-adv-accordian' , ELEMENT_READY_ROOT_CSS. 'widgets/accordion.css' );
        return [ 'eready-adv-accordian' ];
    }
    
    /*
     * Elementor Templates List
     * return array
     */
    public function element_ready_elementor_template() {

        $templates = Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
        $types     = array();
        if ( empty( $templates ) ) {
            $template_lists = [ '0' => esc_html__( 'Do not Saved Templates.', 'element-ready-lite' ) ];
        } else {
            $template_lists = [ '0' => esc_html__( 'Select Template', 'element-ready-lite' ) ];
            foreach ( $templates as $template ) {
                $template_lists[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
            }
        }
        return $template_lists;
    }

    protected function register_controls()
    {
     
        /*--------------------------------
            Advance Accordion Settings
        ---------------------------------*/
        $this->start_controls_section(
            'element_ready_accordion_settings_section',
            [
                'label' => esc_html__('Accordicon Settings', 'element-ready-lite'),
            ]
        );

            $this->add_control(
                'element_ready_accordion_type',
                [
                    'label'       => esc_html__('Accordion Type', 'element-ready-lite'),
                    'type'        => Controls_Manager::SELECT,
                    'default'     => 'accordion',
                    'label_block' => false,
                    'options'     => [
                        'accordion' => esc_html__('Accordion', 'element-ready-lite'),
                        'toggle'    => esc_html__('Toggle', 'element-ready-lite'),
                    ],
                ]
            );

            $this->add_control(
                'element_ready_accordion_show_icon',
                [
                    'label'        => esc_html__('Enable Toggle Icon', 'element-ready-lite'),
                    'type'         => Controls_Manager::SWITCHER,
                    'default'      => 'yes',
                    'return_value' => 'yes',
                ]
            );

            $this->add_control(
                'element_ready_adv_accordion_toggle_icon',
                [
                    'label'       => esc_html__('Toggle Icon', 'element-ready-lite'),
                    'type'        => Controls_Manager::ICON,
                    'default'     => 'fa fa-angle-right',
                    'label_block' => true,
                    'include'     => [
                        'fa fa-angle-right',
                        'fa fa-angle-double-right',
                        'fa fa-chevron-right',
                        'fa fa-chevron-circle-right',
                        'fa fa-arrow-right',
                        'fa fa-long-arrow-right',
                        'fa fa-plus',
                    ],
                    'condition' => [
                        'element_ready_accordion_show_icon' => 'yes',
                    ],
                ]
            );

            $this->add_control(
                'element_ready_accordion_toggle_speed',
                [
                    'label'       => esc_html__('Toggle Speed (ms)', 'element-ready-lite'),
                    'type'        => Controls_Manager::NUMBER,
                    'label_block' => false,
                    'default'     => 300,
                ]
            );

        $this->end_controls_section();
       
        /*--------------------------------------
            Advance Accordion Content Settings
        ----------------------------------------*/
        $this->start_controls_section(
            'element_ready_accordion_content_section',
            [
                'label' => esc_html__('Accordion Content', 'element-ready-lite'),
            ]
        );

     
        $repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'element_ready_adv_accordion_tab_default_active', [
                        
                        'label'        => esc_html__('Active as Default', 'element-ready-lite'),
                        'type'         => \Elementor\Controls_Manager::SWITCHER,
                        'default'      => 'no',
                        'return_value' => 'yes',
			]
        );
        
        $repeater->add_control(
			'element_ready_accordion_show_tab_icon', [
                        
                'label'        => esc_html__('Enable Tab Icon', 'element-ready-lite'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
			]
        );
        
        $repeater->add_control(
			'element_ready_accordion_tab_title_icon', [
                'label'     => esc_html__('Icon', 'element-ready-lite'),
                'type'      => \Elementor\Controls_Manager::ICON,
                'label_block' => true,
                'default'   => 'fa fa-plus',
                'condition' => [
                    'element_ready_accordion_show_tab_icon' => 'yes',
                ],
			]
        );
        
        $repeater->add_control(
			'element_ready_adv_accordion_tab_title', [
                'label'   => esc_html__('Tab Title', 'element-ready-lite'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Tab Title', 'element-ready-lite'),
                'dynamic' => ['active' => true],
			]
        );
        
        $repeater->add_control(
			'element_ready_accordion_text_type', [
                'label'   => esc_html__('Content Type', 'element-ready-lite'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'content'  => esc_html__('Content', 'element-ready-lite'),
                    'template' => esc_html__('Saved Templates', 'element-ready-lite'),
                ],
                'default' => 'content',
			]
        );
        
        
        $repeater->add_control(
			'element_ready_primary_templates', [
                'label'     => esc_html__('Choose Template', 'element-ready-lite'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'options'   => $this->element_ready_elementor_template(),
                'condition' => [
                    'element_ready_accordion_text_type' => 'template',
                ],
			]
        ); 
        
        $repeater->add_control(
			'element_ready_adv_accordion_tab_content', [
                'label'     => esc_html__('Tab Content', 'element-ready-lite'),
                'type'      => \Elementor\Controls_Manager::WYSIWYG,
                'default'   => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'element-ready-lite'),
                'dynamic'   => ['active' => true],
                'condition' => [
                    'element_ready_accordion_text_type' => 'content',
                ],
			]
		);

	

		$this->add_control(
			'element_ready_adv_accordion_tab',
			[
				'label' => esc_html__( 'Repeater List', 'element-ready-lite' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ element_ready_adv_accordion_tab_title }}}',
			]
		);
            

        $this->end_controls_section();
 
        /**
         * -------------------------------------------
         * Tab Style Advance Accordion Generel Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'element_ready_adv_accordion_style_section',
            [
                'label' => esc_html__('Accordion Area Style', 'element-ready-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $icon_opt = apply_filters( 'element_ready_accordion_area_pro_message', $this->pro_message('area_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_accordion_area_styles', $this );

        $this->end_controls_section();


        /**
         * -------------------------------------------
         * TAB ACCORDION ITEM STYLE
         * -------------------------------------------
         */
        $this->start_controls_section(
            'element_ready_adv_accordion_item_style_section',
            [
                'label' => esc_html__('Single Item Style', 'element-ready-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Background:: get_type(),
                [
                    'name'     => 'element_ready_adv_item_background',
                    'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .element__ready__accordion__list',
                ]
            );

            $icon_opt = apply_filters( 'element_ready_accordion_item_pro_message', $this->pro_message('item_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_accordion_item_styles', $this );

        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style Advance Accordion Content Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'element_ready_adv_accordions_tab_style_section',
            [
                'label' => esc_html__('Header Style', 'element-ready-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'iocn_hidding',
                [
                    'label'     => esc_html__( 'Icon', 'element-ready-lite' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $this->add_responsive_control(
                'element_ready_adv_accordion_tab_icon_size',
                [
                    'label'   => esc_html__('Icon Size', 'element-ready-lite'),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 16,
                        'unit' => 'px',
                    ],
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__adv__accordion .element__ready__accordion__list .element__ready__accordion__header .element__ready__accordion__icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'element_ready_adv_accordion_tab_icon_gap',
                [
                    'label'   => esc_html__('Icon Gap', 'element-ready-lite'),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                        'unit' => 'px',
                    ],
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__adv__accordion .element__ready__accordion__list .element__ready__accordion__header .element__ready__accordion__icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
            'title_hr',
                [
                    'type' => Controls_Manager::DIVIDER,
                ]
            );

            $this->add_control(
                'title_hidding',
                [
                    'label'     => esc_html__( 'Title Wrap', 'element-ready-lite' ),
                    'type'      => Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

            $icon_opt = apply_filters( 'element_ready_accordion_header_pro_message', $this->pro_message('header_pro_messagte'), false );
            $this->run_controls( $icon_opt );

            do_action( 'element_ready_accordion_header_styles', $this );

        $this->end_controls_section();

        /*-------------------------------------------
            Tab Style Advance Accordion Content Style
        * ------------------------------------------*/
        $this->start_controls_section(
            'element_ready_adv_accordion_tab_content_style_section',
            [
                'label' => esc_html__('Content Style', 'element-ready-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'adv_accordion_content_bg_color',
                [
                    'label'     => esc_html__('Background Color', 'element-ready-lite'),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__adv__accordion .element__ready__accordion__list .element__ready__accordion__content' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'adv_accordion_content_text_color',
                [
                    'label'     => esc_html__('Text Color', 'element-ready-lite'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__adv__accordion .element__ready__accordion__list .element__ready__accordion__content' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Typography:: get_type(),
                [
                    'name'     => 'element_ready_adv_accordion_content_typography',
                    'selector' => '{{WRAPPER}} .element__ready__adv__accordion .element__ready__accordion__list .element__ready__accordion__content',
                ]
            );

            $icon_opt = apply_filters( 'element_ready_accordion_content_pro_message', $this->pro_message('content_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            
            do_action( 'element_ready_accordion_content_styles', $this );

        $this->end_controls_section();

        /**
         * Advance Accordion Caret Settings
         */
        $this->start_controls_section(
            'element_ready_adv_accordion_caret_section',
            [
                'label' => esc_html__('Toggle Caret Style', 'element-ready-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_responsive_control(
                'element_ready_adv_accordion_tab_toggle_icon_size',
                [
                    'label'   => esc_html__('Icon Size', 'element-ready-lite'),
                    'type'    => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 16,
                        'unit' => 'px',
                    ],
                    'size_units' => ['px'],
                    'range'      => [
                        'px' => [
                            'min'  => 0,
                            'max'  => 100,
                            'step' => 1,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__adv__accordion .element__ready__accordion__list .element__ready__accordion__header .toggle__icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'element_ready_accordion_show_icon' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'element_ready_adv_tabs_tab_toggle_color',
                [
                    'label'     => esc_html__('Caret Color', 'element-ready-lite'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__adv__accordion .element__ready__accordion__list .element__ready__accordion__header .toggle__icon' => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'element_ready_accordion_show_icon' => 'yes',
                    ],
                ]
            );
            $this->add_control(
                'element_ready_adv_tabs_tab_toggle_active_color',
                [
                    'label'     => esc_html__('Caret Color (Active)', 'element-ready-lite'),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .element__ready__adv__accordion .element__ready__accordion__list .element__ready__accordion__header.active .toggle__icon' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .element__ready__adv__accordion .element__ready__accordion__list:hover .element__ready__accordion__header .toggle__icon'  => 'color: {{VALUE}};',
                    ],
                    'condition' => [
                        'element_ready_accordion_show_icon' => 'yes',
                    ],
                ]
            );
        $this->end_controls_section();
    }

    protected function render(){

        $settings = $this->get_settings_for_display();
        $id_int = substr($this->get_id_int(), 0, 3);
        
        include( element_ready_locate_template( 'content', 'accordion/output' ) );

    }

    protected function content_template(){}
}