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

class thepack_vtkltb1 extends Widget_Base {

    public function __construct($data = [], $args = null) {
		parent::__construct($data, $args);
		wp_enqueue_style( $this->get_name(), plugin_dir_url( __FILE__ ) . 'style.css' );
	}

    public function get_name() {
        return 'xlvtab1';
    }

    public function get_title() {
        return   esc_html__('Vertical tab', 'xltab');
    } 
    
    public function get_icon() {
        return 'dashicons dashicons-analytics';
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

                    'five' => [
                        'title' =>   esc_html__('Five', 'xltab'),
                        'icon' => 'eicon-price-list',
                    ],

                ],
                'default' => 'one',               
            ]
        ); 

        $repeater = new \Elementor\Repeater();


        $repeater->add_control(
            'title', [
                'type' => Controls_Manager::TEXT,
                'label' =>   esc_html__('Label', 'xltab'),
                'label_block' => true,
                'default' =>'Car Insurance',
            ]
        );

        $repeater->add_control(
            'sub', [
                'type' => Controls_Manager::TEXTAREA,
                'label' =>   esc_html__('Sub title', 'xltab'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'icon', [
                'type' => Controls_Manager::ICONS,
                'label' =>   esc_html__('Title icon', 'xltab'),
                'label_block' => true,
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
                        'icon' => 'eicon-header',
                    ],
                    'image' => [
                        'title' =>   esc_html__('Image', 'xltab'),
                        'icon' => 'eicon-image-rollover',
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
            'tpos',
            [
                'label' =>   esc_html__('Tab position', 'xltab'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' =>   esc_html__('Left', 'xltab'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' =>   esc_html__('Right', 'xltab'),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .xldtab .tabs' => 'float: {{VALUE}};',
                ],                 
            ]
        ); 

        $this->add_responsive_control(
            'xpspc',
            [
                'label' =>   esc_html__( 'Nav to content space', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .xlvtab1.left >ul' => 'margin-right: {{SIZE}}px;',
                    '{{WRAPPER}} .xlvtab1.right >ul' => 'margin-left: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'ibinrsp',
            [
                'label' =>   esc_html__( 'Inter nav spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .xlvtab1 .tabs li' => 'margin-bottom: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'navwidj',
            [
                'label' =>   esc_html__( 'Nav width', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ]
                ],                
                'selectors' => [
                    '{{WRAPPER}} .xlvtab1 .tabs' => 'width: {{SIZE}}{{UNIT}};',
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
                'label' =>   esc_html__('Alignment','xltab'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' =>   esc_html__('Left','xltab'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' =>   esc_html__('Center','xltab'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' =>   esc_html__('Right','xltab'),
                        'icon' => 'eicon-text-align-right',
                    ]
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .tabs .tbinr' =>'text-align:{{VALUE}};',
                ],                 
            ]
        );

        $this->add_responsive_control(
          'rwnvsp',
          [
             'label' =>   esc_html__( 'Wrapper padding', 'xltab' ),
             'type' => Controls_Manager::DIMENSIONS,
             'size_units' => [ 'px','em'],
             'selectors' => [
                    '{{WRAPPER}} .xlvtab1 .tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
             ],
          ]
        );

        $this->add_responsive_control(
          'tbtbdrad',
          [
             'label' =>   esc_html__( 'Border radius', 'xltab' ),
             'type' => Controls_Manager::DIMENSIONS,
             'size_units' => [ 'px','em'],
             'selectors' => [
                    '{{WRAPPER}} .tabs li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .tabs li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
             ],
          ]
        );

        $this->start_controls_tabs('vnx');

        $this->start_controls_tab(
            'vnx1',
            [
                'label' => esc_html__( 'Title', 'xltab' ),               
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'nvtre',
                'selector' => '{{WRAPPER}} .tabs .title',
                'label' =>   esc_html__( 'Typography', 'xltab' ),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'vnx2',
            [
                'label' => esc_html__( 'Desc', 'xltab' ),                
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'snvtre',
                'selector' => '{{WRAPPER}} .tabs .sub',
                'label' =>   esc_html__( 'Typography', 'xltab' ),
            ]
        );

        $this->add_responsive_control(
            'snvsp',
            [
               'label' =>   esc_html__( 'Margin', 'xltab' ),
               'type' => Controls_Manager::DIMENSIONS,
               'size_units' => [ 'px','em'],
               'selectors' => [
                      '{{WRAPPER}} .tabs .sub' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
               ],
            ]
          );

        $this->end_controls_tab();

        $this->end_controls_tabs();

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
                'selector' => '{{WRAPPER}} .tabs li a',
            ]
        );

        $this->add_control(
            'nvtclr',
            [
                'label' =>   esc_html__('Title color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tabs li a' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'subclrt',
            [
                'label' =>   esc_html__('Sub title color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tabs .sub' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         =>   esc_html__('Box Shadow','xltab'),
                    'name'          => 'nveshdw',
                    'selector'      => '{{WRAPPER}} .tabs li a',                    
                ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'tbtbdr',
                'label' =>   esc_html__( 'Border', 'xltab' ),
                'selector' => '{{WRAPPER}} .tabs li a',
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
                'name' => 'acntbg',
                'label' =>   esc_html__( 'Background', 'elementor' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tabs li.current a',
            ]
        );

        $this->add_control(
            'anvtclr',
            [
                'label' =>   esc_html__('Title color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tabs li.current a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'asubclrt',
            [
                'label' =>   esc_html__('Sub title color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tabs .current.sub' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'anvactbdcl',
            [
                'label' =>   esc_html__('Border color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tabs li.current a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         =>   esc_html__('Box Shadow','xltab'),
                    'name'          => 'anveshdw',
                    'selector'      => '{{WRAPPER}} .tabs li.current a',                    
                ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'atbtbdr',
                'label' =>   esc_html__( 'Border', 'xltab' ),
                'selector' => '{{WRAPPER}} .tabs li.current a',
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
            'iknclr',
            [
                'label' =>   esc_html__('Color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tbicon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'iknclra',
            [
                'label' =>   esc_html__('Active Color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .current .tbicon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'iknspc',
            [
                'label' =>   esc_html__( 'Icon spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,               
                'selectors' => [
                    '{{WRAPPER}} .tbicon' => 'padding-right: {{SIZE}}px;',
                ],

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_content',
            [
                'label' =>   esc_html__('Content', 'xltab'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cnttypu',
                'selector' => '{{WRAPPER}} .tab_content',
                'label' =>   esc_html__( 'Typography', 'xltab' ),
            ]
        );

        $this->add_control(
            'cntklr',
            [
                'label' =>   esc_html__('Color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tab_content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'cnttbg',
                'label' =>   esc_html__( 'Background', 'elementor' ),
                'types' => [ 'none', 'classic','gradient' ],
                'selector' => '{{WRAPPER}} .tab_content',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         =>   esc_html__('Box Shadow','xltab'),
                    'name'          => 'cntbxsd',
                    'selector'      => '{{WRAPPER}} .tab_content',
                ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'cntbdr',
                'label' =>   esc_html__( 'Border', 'xltab' ),
                'selector' => '{{WRAPPER}} .tab_content',
            ]
        );

        $this->add_responsive_control(
            'cntbdrad',
            [
                'label' =>   esc_html__( 'Border radius', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tab_content' => 'border-radius: {{SIZE}}px;',
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
                    '{{WRAPPER}} .tab_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
             ],
          ]
        );

        $this->add_responsive_control(
            'cntimght',
            [
                'label' =>   esc_html__( 'Image height', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ]
                ],                
                'selectors' => [
                    '{{WRAPPER}} .tabs_item.xl-image' => 'height:{{SIZE}}{{UNIT}};',
                ],

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

$widgets_manager->register_widget_type(new \XLTab\Widgets\thepack_vtkltb1());