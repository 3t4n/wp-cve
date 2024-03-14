<?php
 
namespace XLTab\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use XLTab\xltab_helper;

if (!defined('ABSPATH'))
    exit; 

class thepack_tab1_init extends Widget_Base {

    public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
		wp_enqueue_style( $this->get_name(), plugin_dir_url( __FILE__ ) . 'style.css' );
	}

    public function get_name() {
        return 'xltab1';
    }

    public function get_title() {
        return   esc_html__('Tab', 'xltab');
    } 
    
    public function get_icon() {
        return 'dashicons dashicons-index-card';
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_heading',
            [
                'label' =>   esc_html__('Content', 'xltab'),
            ]
        );

        $this->add_control(
            'tmpl',
            [
                'label' =>   esc_html__('Template', 'xltab'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'styleone' => [
                        'title' =>   esc_html__('One', 'xltab'),
                        'icon' => 'eicon-woo-cart',
                    ],
                    'styletwo' => [
                        'title' =>   esc_html__('Two', 'xltab'),
                        'icon' => 'eicon-page-transition',
                    ],
                    'stylethree' => [
                        'title' =>   esc_html__('Three', 'xltab'),
                        'icon' => 'eicon-woo-settings',
                    ],
                    'stylefour' => [
                        'title' =>   esc_html__('Four', 'xltab'),
                        'icon' => 'eicon-hotspot',
                    ],
                    'stylefive' => [
                        'title' =>   esc_html__('Five', 'xltab'),
                        'icon' => 'eicon-slideshow',
                    ],
                ],
                'default' => 'styleone',               
            ]
        ); 

        $repeater = new \Elementor\Repeater();
 
        $repeater->add_control(
            'title', [
                'type' => Controls_Manager::TEXT,
                'label' =>   esc_html__('Label', 'xltab'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'icon', [
                'type' => Controls_Manager::ICONS,
                'label' =>   esc_html__('Title icon', 'xltab'),
            ]
        );

        $repeater->add_control(
            'type', [
                'label' =>   esc_html__('Population', 'xltab'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'content' => [
                        'title' =>   esc_html__('Content', 'xltab'),
                        'icon' => ' eicon-document-file',
                    ],
                    'template' => [
                        'title' =>   esc_html__('Template', 'xltab'),
                        'icon' => 'eicon-image-rollover',
                    ],
                    'image' => [
                        'title' =>   esc_html__('Image', 'xltab'),
                        'icon' => 'eicon-header',
                    ]                    
                ],
                'default' => 'content',               
            ]
        );

        $repeater->add_control(
            'content', [
                'type' => Controls_Manager::WYSIWYG,
                'label' =>   esc_html__('Content', 'xltab'),
                'label_block' => true,
                'condition' => [
                    'type' => 'content',
                ],
            ]
        );

        $repeater->add_control(
            'img', [
                'label' =>   esc_html__( 'Image', 'xltab' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'type' => 'image',
                ],
            ]
        ); 

        $repeater->add_control(
            'template', [
                'type' => Controls_Manager::SELECT2,
                'options' => xltab_helper::xltab_drop_posts('elementor_library'),
                'multiple' => false,
                'label' =>   esc_html__('Template', 'xltab'),
                'label_block' => true,
                'condition' => [
                    'type' => 'template',
                ],
            ]
        );

                                
        $this->add_control(
            'tabs',
            [
                'type' => Controls_Manager::REPEATER,
                'prevent_empty' => false,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'title' =>   esc_html__( 'Finance', 'xltab' ),
                    ]
                ],
                'title_field' => '{{{ title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_xgnr',
            [
                'label' =>   esc_html__('General', 'xltab'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control( 
            'inpalign',
            [
                'label' =>   esc_html__('Alignment', 'xltab'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'nvleft' => [
                        'title' =>   esc_html__('Left', 'xltab'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'nvcenter' => [
                        'title' =>   esc_html__('Center', 'xltab'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'nvright' => [
                        'title' =>   esc_html__('Right', 'xltab'),
                        'icon' => 'eicon-text-align-right',
                    ]
                ],
                'default' => 'nvcenter',               
            ]
        ); 

        $this->add_control(
            'scroll',
            [
                'label' =>   esc_html__( 'Scroll to content', 'xltab' ),
                'type' =>  Controls_Manager::SWITCHER,   
                'return_value' => 'has-scroll',             

            ]
        );

        $this->add_responsive_control(
            'gmawid',
            [
                'label' =>   esc_html__( 'Nav wrapper width', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'size_units' => ['px','%'],
                'condition' => [
                    'inpalign' => 'nvcenter',
                ],                
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .tab-area' => 'width: {{SIZE}}{{UNIT}};margin:0px auto;',
                ],

            ]
        );

        $this->add_responsive_control(
            'nvspr',
            [
                'label' =>   esc_html__( 'Nav wrapper spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tab-area li' => 'padding:0px {{SIZE}}px;',
                    '{{WRAPPER}} .nvleft .tab-area li' => 'margin-left:-{{SIZE}}px;',
                    '{{WRAPPER}} .nvright .tab-area li' => 'margin-right:-{{SIZE}}px;',
                ],

            ]
        );                

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'vnfbdr',
                'label' =>   esc_html__( 'Nav border', 'xltab' ),
                'selector' => '{{WRAPPER}} .tab-area',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'nvdrtbg',
                'condition' => [
                    'tmpl' => ['stylefour','stylefive'],
                ],                 
                'label' =>   esc_html__( 'Background', 'elementor' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tab-area',
            ]
        );

        $this->add_responsive_control(
            'navpde',
            [
                'label' =>   esc_html__( 'Nav wrapper padding', 'xltab' ),
                'type' =>  Controls_Manager::DIMENSIONS, 
                'condition' => [
                    'tmpl' => 'stylefour',
                ],                
                'selectors' => [
                    '{{WRAPPER}} .tab-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'navpbdre',
            [
                'label' =>   esc_html__( 'Nav wrapper border radius', 'xltab' ),
                'type' =>  Controls_Manager::DIMENSIONS, 
                'condition' => [
                    'tmpl' => 'stylefour',
                ],                
                'selectors' => [
                    '{{WRAPPER}} .tab-area' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_gnrl',
            [
                'label' =>   esc_html__('Nav', 'xltab'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control( 
            'talign',
            [
                'label' =>   esc_html__('Text alignment', 'xltab'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' =>   esc_html__('Left', 'xltab'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' =>   esc_html__('Center', 'xltab'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' =>   esc_html__('Right', 'xltab'),
                        'icon' => 'eicon-text-align-right',
                    ]
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .tab-area li' => 'text-align: {{VALUE}};',
                ],                 
            ]
        );

        $this->add_responsive_control(
            'nbdra',
            [
                'label' =>   esc_html__( 'Border radius', 'xltab' ),
                'type' =>  Controls_Manager::DIMENSIONS, 
                'selectors' => [
                    '{{WRAPPER}} .tab-area li .inrtab' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'nmwid',
            [
                'label' =>   esc_html__( 'Width', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER, 
                'size_units' => ['px','%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 800,
                        'step' => 1,
                    ],

                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => .1,
                    ],

                ],
                'selectors' => [
                    '{{WRAPPER}} .tab-area li' => 'min-width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
          'nvsp',
          [
             'label' =>   esc_html__( 'Padding', 'xltab' ),
             'type' => Controls_Manager::DIMENSIONS,
             'size_units' => [ 'px','em'],
             'selectors' => [
                    '{{WRAPPER}} .tab-area li .inrtab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
             ],
          ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'nvtre',
                'selector' => '{{WRAPPER}} .inrtab',
                'label' =>   esc_html__( 'Typography', 'xltab' ),
            ]
        );

        $this->start_controls_tabs('navactive');

        $this->start_controls_tab(
            'actstl',
            [
                'label' => __( 'Normal', 'elementor' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'nvtbg',
                'label' =>   esc_html__( 'Background', 'elementor' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}}  li .inrtab',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'nvbdr',
                'label' =>   esc_html__( 'Border', 'xltab' ),
                'selector' => '{{WRAPPER}}  li .inrtab',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         =>   esc_html__('Box Shadow','xltab'),
                    'name'          => 'nvshdw',
                    'selector'      => '{{WRAPPER}}  li .inrtab',
                ]
        );

        $this->add_control(
            'nvfclr',
            [
                'label' =>   esc_html__('Color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  li .inrtab' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nvikclr',
            [
                'label' =>   esc_html__('Icon color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .inrtab .tbicon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'n2botborclr',
            [
                'label' =>   esc_html__('Bottom border color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'tmpl' => 'styletwo',
                ],                
                'selectors' => [
                    '{{WRAPPER}} .styletwo .resp-tab-item span:before' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'n2botborwid',
            [
                'label' =>   esc_html__( 'Bottom border width', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER, 
                'condition' => [
                    'tmpl' => 'styletwo',
                ],                
                'selectors' => [
                    '{{WRAPPER}} .styletwo .resp-tab-item span:before' => 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'actnrml',
            [
                'label' => __( 'Active', 'elementor' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'anvtbg',
                'label' =>   esc_html__( 'Background', 'elementor' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}}  li.active .inrtab',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         =>   esc_html__('Box Shadow','xltab'),
                    'name'          => 'anvshdw',
                    'selector'      => '{{WRAPPER}}  li.active .inrtab',
                ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'naevbdr',
                'label' =>   esc_html__( 'Border', 'xltab' ),
                'selector' => '{{WRAPPER}}  li.active .inrtab',
            ]
        );

        $this->add_control(
            'anvfclr',
            [
                'label' =>   esc_html__('Color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  li.active .inrtab' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nvdfikclr',
            [
                'label' =>   esc_html__('Icon color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .active .inrtab .tbicon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'n1apad',
            [
                'label' =>   esc_html__( 'Padding', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER, 
                'condition' => [
                    'tmpl' => 'styleone',
                ],                
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .styleone .tab-area li.active .inrtab' => 'padding-top: {{SIZE}}{{UNIT}};padding-bottom: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'n1btmbdrtm',
            [
                'label' =>   esc_html__( 'Bottom border top spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER, 
                'condition' => [
                    'tmpl' => 'styleone',
                ],                
                'range' => [
                    'px' => [
                        'min' => -800,
                        'max' => 800,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}  li.active .inrtab' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'n1btmbdrwd',
            [
                'label' =>   esc_html__( 'Bottom border height', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER, 
                'condition' => [
                    'tmpl' => 'styleone',
                ],                
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 800,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tab-area li.active .inrtab div:before' => 'height: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'n1btmbdrps',
            [
                'label' =>   esc_html__( 'Bottom border position', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER, 
                'condition' => [
                    'tmpl' => ['styletwo','stylethree'],
                ],                
                'range' => [
                    'px' => [
                        'min' => -800,
                        'max' => 800,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .tab-area li.active .inrtab div:before' => 'bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .stylethree .resp-tab-item span:after' => 'bottom: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'n1btmbdrclr',
            [
                'label' =>   esc_html__('Bottom border background', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'tmpl' => 'styleone',
                ],                
                'selectors' => [
                    '{{WRAPPER}} .tab-area li.active .inrtab div:before' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'n2abotborclr',
            [
                'label' =>   esc_html__('Bottom border color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'tmpl' => ['styletwo','stylethree'],
                ],                
                'selectors' => [
                    '{{WRAPPER}} .styletwo .resp-tab-item.active span:after' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .stylethree .resp-tab-item span:after' => 'border-top-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'n2adebotborwid',
            [
                'label' =>   esc_html__( 'Bottom border width', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER, 
                'condition' => [
                    'tmpl' => ['styletwo','stylethree'],
                ],                
                'selectors' => [
                    '{{WRAPPER}} .styletwo .resp-tab-item span:after' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .stylethree .resp-tab-item span:after' => 'border-top-width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_fve',
            [
                'label' =>   esc_html__('Templae Five', 'xltab'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'tmpl' => 'stylefive',
                ],                
            ]
        );

        $this->add_control(
            'fvrfclr',
            [
                'label' =>   esc_html__('Primary color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .stylefive .inrtab:before' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'fvrsclr',
            [
                'label' =>   esc_html__('Secondary color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .stylefive .active .inrtab:before,{{WRAPPER}} .stylefive .active .inrtab:after,{{WRAPPER}} .stylefive .inrtab div:after' => 'border-left-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'fvvsp',
            [
                'label' =>   esc_html__( 'Vertical arrow position', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,                
                'selectors' => [
                    '{{WRAPPER}} .stylefive .inrtab:before' => 'top: {{SIZE}}px;',
                    '{{WRAPPER}} .stylefive .active .inrtab:after' => 'top: {{SIZE}}px;',
                    '{{WRAPPER}} .stylefive .inrtab div:after' => 'top: {{SIZE}}px;',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_ikn',
            [
                'label' =>   esc_html__('Icon', 'xltab'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'iksze',
            [
                'label' =>   esc_html__( 'Icon size', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER, 
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 800,
                        'step' => 1,
                    ]

                ],
                'selectors' => [
                    '{{WRAPPER}} .inrtab .tbicon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_responsive_control(
            'iklpdg',
            [
                'label' =>   esc_html__( 'Icon padding', 'xltab' ),
                'type' =>  Controls_Manager::DIMENSIONS, 
                'selectors' => [
                    '{{WRAPPER}} .inrtab .tbicon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'idisp',
            [
                'label' =>   esc_html__('Display block', 'xltab'),
                'type' => Controls_Manager::SWITCHER, 
                'selectors' => [
                    '{{WRAPPER}} .inrtab .tbicon' => 'display: block;',
                ],                            
            ]
        );

        $this->start_controls_tabs('ikny');

        $this->start_controls_tab(
            'mikn',
            [
                'label' => __( 'Normal', 'elementor' ),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'miknt',
            [
                'label' => __( 'Active', 'elementor' ),
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content',
            [
                'label' =>   esc_html__('Content', 'xltab'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'cntpmr',
            [
                'label' =>   esc_html__( 'Top margin', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,               
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .tabs_item.resp-tab-content' => 'margin-top: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'bxcyd',
            [
                'label' =>   esc_html__( 'Boxed wrapper width', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .resp-tabs-container' => 'max-width: {{SIZE}}px;margin:0px auto;',
                ],
                'condition' => [
                    'bxcy' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'ibxcyd',
            [
                'label' =>   esc_html__( 'Inner wrapper width', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .tabs_item' => 'max-width: {{SIZE}}px;margin:0px auto;',
                ],
            ]
        );

        $this->add_control(
          'ctpad',
          [
             'label' =>   esc_html__( 'Padding', 'xltab' ),
             'type' => Controls_Manager::DIMENSIONS,
             'size_units' => [ 'px','em'],            
             'selectors' => [
                    '{{WRAPPER}} .tabs_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
             ],
          ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         =>   esc_html__('Box Shadow','xltab'),
                    'name'          => 'frtxsd',
                    'selector'      => '{{WRAPPER}} .tabs_item',
                ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'akbgr',
                'label' =>   esc_html__( 'Background', 'elementor' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tabs_item',
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {

        $settings = $this->get_settings();
        require dirname(__FILE__) .'/view.php';
    }

    private function icon_image($icon) { 

        $type = $icon['type'];
        if ($type == 'template'){
            return '<div class="tabs_item">'.do_shortcode('[XLTAB_INSERT_TPL id="'.$icon['template'].'"]').'</div>';
        } elseif ($type == 'content') {
            return '<div class="tabs_item">'.$icon['content'].'</div>';
        } else {
            return '<div class="tabs_item xl-image">'.wp_get_attachment_image($icon['img']['id'],'full').'</div>';
        }

    }

}

$widgets_manager->register_widget_type(new \XLTab\Widgets\thepack_tab1_init());