<?php
namespace XLTab\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager; 
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Utils; 
use XLTab\xltab_helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

 
class thepack_accrnd_init extends Widget_Base {

    public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
		wp_enqueue_style( $this->get_name(), plugin_dir_url( __FILE__ ) . 'style.css' );
	}
    public function get_name() {
        return 'xlacrdn1';
    }

    public function get_title() {
        return   esc_html__('Accordion', 'xltab');
    } 

    public function get_categories() {
        return ['xltab'];
    }

    public function get_icon() {
        return 'dashicons dashicons-unlock';
    }


    protected function register_controls() {

        $this->start_controls_section(
            'section_heading',
            [
                'label' =>   esc_html__('Contents', 'xltab'),
            ]
        ); 

        $repeater = new \Elementor\Repeater();

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
                    ]
                ],
                'default' => 'content',
                'label_block' => true,                
            ]
        );

        $repeater->add_control(
            'title', [
                'type' => Controls_Manager::TEXT,
                'label' =>   esc_html__('Label', 'xltab'),
                'label_block' => true,
                'default' =>esc_html__('Car Insurance', 'xltab'),
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

        $this->add_control(
            'icon', [
                'type' => Controls_Manager::ICONS,
                'label' =>   esc_html__('Active icon', 'xltab'),
				'default' => [
					'value' => 'fas fa-minus',
					'library' => 'solid',
				],
            ]
        );

        $this->add_control(
            'iicon', [
                'type' => Controls_Manager::ICONS,
                'label' =>   esc_html__('Inactive icon', 'xltab'),
				'default' => [
					'value' => 'fas fa-plus',
					'library' => 'solid',
				],
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
            'tmpl',
            [
                'label' =>   esc_html__('Extra style', 'xltab'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'one' => [
                        'title' =>   esc_html__('One', 'xltab'),
                        'icon' => 'eicon-woo-cart',
                    ],

                    'two' => [
                        'title' =>   esc_html__('Two', 'xltab'),
                        'icon' => 'eicon-page-transition',
                    ],

                    'three' => [
                        'title' =>   esc_html__('Three', 'xltab'),
                        'icon' => 'eicon-woo-settings',
                    ],

                    'four' => [
                        'title' =>   esc_html__('Four', 'xltab'),
                        'icon' => 'eicon-hotspot',
                    ],

                ],
                'default' => 'one',               
            ]
        );

        $this->add_control(
            'tmplp',
            [
                'label' =>   esc_html__('Extra style position', 'xltab'),
                'type' => Controls_Manager::CHOOSE,
                'condition' => [
                    'tmpl' => ['one','two','three'],
                ],                
                'options' => [
                    'xlft' => [
                        'title' =>   esc_html__('Left', 'xltab'),
                        'icon' => 'eicon-featured-image',
                    ],

                    'xright' => [
                        'title' =>   esc_html__('Right', 'xltab'),
                        'icon' => 'eicon-site-search',
                    ],

                ],              
            ]
        );

        $this->add_control(
            'gctbg',
            [
                'label' =>   esc_html__( 'Background', 'xltab' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .accordion li' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'gctbdr',
                'label' =>   esc_html__( 'Border', 'xltab' ),
                'selector' => '{{WRAPPER}} .accordion li',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         =>   esc_html__('Box Shadow','xltab'),
                    'name'          => 'frtxsd',
                    'selector'      => '{{WRAPPER}} .xldacdn li',
                ]
        );                

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_1',
            [
                'label' =>   esc_html__('Style 1', 'xltab'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'tmpl' => 'one',
                ],                 
            ]
        );

        $this->add_responsive_control(
            'x1lh',
            [
                'label' =>   esc_html__( 'Bar height', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ]
                ],                
                'selectors' => [
                    '{{WRAPPER}} .xld-acdn1 .accordion:before' => 'height:{{SIZE}}%;',
                ],

            ]
        );

        $this->add_responsive_control(
            'x1lw',
            [
                'label' =>   esc_html__( 'Bar width', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,               
                'selectors' => [
                    '{{WRAPPER}} .xld-acdn1 .accordion:before' => 'width:{{SIZE}}px;',
                ],

            ]
        );

        $this->add_control(
            'x1lbg',
            [
                'label' =>   esc_html__('Bar background', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xld-acdn1 .accordion:before' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'x1ltp',
            [
                'label' =>   esc_html__( 'Bar top spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,               
                'selectors' => [
                    '{{WRAPPER}} .xld-acdn1 .accordion:before' => 'top:{{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'x1llrsp',
            [
                'label' =>   esc_html__( 'Bar left/right spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],                                
                'selectors' => [
                    '{{WRAPPER}} .xld-acdn1.xlft .accordion:before' => 'left:{{SIZE}}px;',
                    '{{WRAPPER}} .xld-acdn1.xright .accordion:before' => 'right:{{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'x1lcrsp',
            [
                'label' =>   esc_html__( 'Circle left/right spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],                                
                'selectors' => [
                    '{{WRAPPER}} .xld-acdn1.xlft .accordion .xltbhd:before' => 'left:{{SIZE}}px;',
                    '{{WRAPPER}} .xld-acdn1.xright .accordion .xltbhd:before' => 'right:{{SIZE}}px;',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_2',
            [
                'label' =>   esc_html__('Style 2', 'xltab'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'tmpl' => 'two',
                ],                 
            ]
        );

        $this->add_control(
            'x2lbg',
            [
                'label' =>   esc_html__('Bar background', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xld-acdn2 .accordion li:after' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'x2ltp',
            [
                'label' =>   esc_html__( 'Bar top spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,               
                'selectors' => [
                    '{{WRAPPER}} .xld-acdn2 .accordion li:after' => 'top:{{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'x2llrsp',
            [
                'label' =>   esc_html__( 'Bar left/right spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],                                
                'selectors' => [
                    '{{WRAPPER}} .xld-acdn2.xlft .accordion li:after' => 'left:{{SIZE}}px;',
                    '{{WRAPPER}} .xld-acdn2.xright .accordion li:after' => 'right:{{SIZE}}px;',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_3',
            [
                'label' =>   esc_html__('Style 3', 'xltab'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'tmpl' => 'three',
                ],                 
            ]
        );

        $this->add_control(
            'nvbtmclr',
            [
                'label' =>   esc_html__('Border color', 'xltab'),
                'type' => Controls_Manager::COLOR,              
                'selectors' => [
                    '{{WRAPPER}} .xld-acdn3 .accordion .xltbhd:before' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'fbdrclr',
            [
                'label' =>   esc_html__( 'Border width', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,                
                'selectors' => [
                    '{{WRAPPER}} .xld-acdn3 .accordion .xltbhd:before' => 'width: {{SIZE}}px;',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_navr',
            [
                'label' =>   esc_html__('Title', 'xltab'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'navlh',
            [
                'label' =>   esc_html__( 'Height', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ]
                ],                
                'selectors' => [
                    '{{WRAPPER}} .xltbhd' => 'height: {{SIZE}}px;line-height: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'itvsp',
            [
                'label' =>   esc_html__( 'Vertical space', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .xldacdn li' => 'margin-bottom: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
          'navpade',
          [
             'label' =>   esc_html__( 'Padding', 'xltab' ),
             'type' => Controls_Manager::DIMENSIONS,
             'allowed_dimensions' => array('right','left'),
             'size_units' => [ 'px','em'],
             'selectors' => [
                    '{{WRAPPER}} .xltbhd' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
             ],
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
                        'icon' => ' eicon-text-align-left',
                    ],
                    'center' => [
                        'title' =>   esc_html__('Center', 'xltab'),
                        'icon' => ' eicon-text-align-center',
                    ],
                    'right' => [
                        'title' =>   esc_html__('Right', 'xltab'),
                        'icon' => ' eicon-text-align-right',
                    ]
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .xltbhd' => 'text-align: {{VALUE}};',
                ],                 
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         =>   esc_html__('Box Shadow','xltab'),
                    'name'          => 'navbxsd',
                    'selector'      => '{{WRAPPER}} .xltbhd',
                ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tbtbdr',
                'label' =>   esc_html__( 'Border', 'xltab' ),
                'selector' => '{{WRAPPER}} .xltbhd',
            ]
        );

        $this->add_responsive_control(
            'tbtbdrad',
            [
                'label' =>   esc_html__( 'Border radius', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .xltbhd' => 'border-radius: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'nvtypd',
                'label' =>   esc_html__('Typography', 'xltab'),
                'selector' => '{{WRAPPER}} .accordion .xltbhd',
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
                'name' => 'cntbg',
                'label' =>   esc_html__( 'Background', 'elementor' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .xltbhd',
            ]
        );

        $this->add_control(
            'nvtclr',
            [
                'label' =>   esc_html__('Color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .accordion .xltbhd' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nvactbdcl',
            [
                'label' =>   esc_html__('Border color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xltbhd.active' => 'border-color: {{VALUE}};',
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
                'name' => 'akbgr',
                'label' =>   esc_html__( 'Background', 'elementor' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .xltbhd.active',
            ]
        );

        $this->add_control(
            'anvtclr',
            [
                'label' =>   esc_html__('Color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xltbhd.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'anvtvfclr',
            [
                'label' =>   esc_html__('Border color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xltbhd.active' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();


        $this->end_controls_section();


        $this->start_controls_section(
            'section_styl1',
            [
                'label' =>   esc_html__('Icon', 'xltab'),
                'tab' => Controls_Manager::TAB_STYLE,                
            ]
        );

        $this->add_control(
            'ipos',
            [
                'label' =>   esc_html__('Position', 'xltab'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'ilft' => [
                        'title' =>   esc_html__('Left', 'xltab'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'irght' => [
                        'title' =>   esc_html__('Right', 'xltab'),
                        'icon' => 'fa fa-align-right',
                    ]

                ],
                'default' => 'ilft',               
            ]
        ); 

        $this->add_responsive_control(
            'ipospx',
            [
                'label' =>   esc_html__( 'Icon position', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -400,
                        'max' => 400,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .ilft .tbxicon' => 'left: {{SIZE}}px;',
                    '{{WRAPPER}} .irght .tbxicon' => 'right: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'iwid',
            [
                'label' =>   esc_html__( 'Width & height', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .xldacdn .tbxicon' => 'width: {{SIZE}}px;height: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'ibmr',
            [
                'label' =>   esc_html__( 'Vertical spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .xldacdn .tbxicon' => 'line-height: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'ibdr',
            [
                'label' =>   esc_html__( 'Border radius', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .xldacdn .tbxicon' => 'border-radius: {{SIZE}}px',
                ],

            ]
        );

        $this->add_responsive_control(
            'ifs',
            [
                'label' =>   esc_html__( 'Font size', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .xldacdn .tbxicon' => 'font-size: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'iktbtbdr',
                'label' =>   esc_html__( 'Border', 'xltab' ),
                'selector' => '{{WRAPPER}} .tbxicon',
            ]
        );

        $this->start_controls_tabs('ikacnat');

        $this->start_controls_tab(
            'iknrmk',
            [
                'label' => __( 'Normal', 'elementor' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'ikbg',
                'label' =>   esc_html__( 'Background', 'elementor' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .xldacdn .tbxicon',
            ]
        );

        $this->add_control(
            'ikclr',
            [
                'label' =>   esc_html__('Color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xldacdn .tbxicon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'iknactv',
            [
                'label' => __( 'Active', 'elementor' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'ikbga',
                'label' =>   esc_html__( 'Background', 'elementor' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .xldacdn .active .tbxicon',
            ]
        );

        $this->add_control(
            'ikclra',
            [
                'label' =>   esc_html__('Color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xldacdn .active .tbxicon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ikactvbr',
            [
                'label' =>   esc_html__('Border color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xldacdn .active .tbxicon' => 'border-color: {{VALUE}};',
                ],
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
            'cntalign',
            [
                'label' =>   esc_html__('Alignment', 'xltab'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' =>   esc_html__('Left', 'xltab'),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' =>   esc_html__('Center', 'xltab'),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' =>   esc_html__('Right', 'xltab'),
                        'icon' => 'fa fa-align-right',
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion li .xltbc' => 'text-align: {{VALUE}};',
                ],                 
            ]
        );
        $this->add_responsive_control(
            'ctmwd',
            [
                'label' =>   esc_html__( 'Max width in %', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .accordion li .xltbc' => 'max-width: {{SIZE}}%;margin:0px auto;',
                ],

            ]
        );

        $this->add_responsive_control(
          'ctpad',
          [
             'label' =>   esc_html__( 'Padding', 'xltab' ),
             'type' => Controls_Manager::DIMENSIONS,
             'size_units' => [ 'px','em'],
             'selectors' => [
                    '{{WRAPPER}} .accordion li .xltbc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
             ],
          ]
        );

        $this->add_responsive_control(
          'ctmrg',
          [
             'label' =>   esc_html__( 'Margin', 'xltab' ),
             'type' => Controls_Manager::DIMENSIONS,
             'size_units' => [ 'px','em'],
             'selectors' => [
                    '{{WRAPPER}} .accordion li .xltbc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
             ],
          ]
        );

        $this->add_control(
            'ctbg',
            [
                'label' =>   esc_html__( 'Background', 'xltab' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .accordion li .xltbc' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'ctbgklr',
            [
                'label' =>   esc_html__( 'Color', 'xltab' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .accordion li .xltbc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ctbdr',
                'label' =>   esc_html__( 'Border', 'xltab' ),
                'selector' => '{{WRAPPER}} .accordion li .xltbc',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cntyopo',
                'selector' => '{{WRAPPER}} .accordion li .xltbc,{{WRAPPER}} .accordion li .xltbc>p',
                'label' =>   esc_html__( 'Typography', 'xltab' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'ctbclr',
                'label' =>   esc_html__( 'Color', 'xltab' ),
                'selector' => '{{WRAPPER}} .accordion li .xltbc,{{WRAPPER}} .accordion li .xltbc>p',
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
            return '<div class="xltbc">'.do_shortcode('[XLTAB_INSERT_TPL id="'.$icon['template'].'"]').'</div>';
        } else {
            return '<div class="xltbc">'.$icon['content'].'</div>';
        } 

    }

}

$widgets_manager->register_widget_type(new \XLTab\Widgets\thepack_accrnd_init());