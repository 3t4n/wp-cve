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
    exit; // Exit if accessed directly

class thepack_tab_accordion extends Widget_Base {

    public function get_name() {
        return 'aetabswitch';
    }

    public function get_title() {
        return   esc_html__('Tab switch', 'xltab');
    } 
    
    public function get_icon() {
        return 'dashicons dashicons-paperclip';
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
                        'title' =>   esc_html__('Bordered', 'xltab'),
                        'icon' => 'eicon-folder-o',
                    ],
                    'two' => [
                        'title' =>   esc_html__('Background', 'xltab'),
                        'icon' => 'eicon-folder',
                    ]

                ],
                'default' => 'one',               
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
            ]
        );

        $repeater->add_control(
            'title', [
                'type' => Controls_Manager::TEXT,
                'label' =>   esc_html__('Label', 'xltab'),
                'label_block' => true,
                'default' =>'Car Insurance',
            ]
        );

        $repeater->add_control(
            'icon', [
                'type' => Controls_Manager::ICONS,
                'label' =>   esc_html__('Icon', 'xltab'),
                'label_block' => true,
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
                'options' =>  xltab_helper::xltab_drop_posts('elementor_library'), 
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
                'title_field' => '{{{ elementor.helpers.renderIcon( this, icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_gnrl',
            [
                'label' =>   esc_html__('General', 'xltab'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control( 
            'talign',
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
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .xldswitcher ul' => 'text-align: {{VALUE}};',
                ],                 
            ]
        );

        $this->add_responsive_control(
            'switspr',
            [
                'label' =>   esc_html__( 'Nav spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .xldswitcher ul>li' => 'padding:0px {{SIZE}}px;',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'nvtyp',
                'label' =>   esc_html__('Typography', 'xltab'),
                'selector' => '{{WRAPPER}} .xhndlr',
            ]
        );

        $this->add_control(
            'gtxtclr',
            [
                'label' =>   esc_html__('Color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xldswitcher ul li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gtaxtclr',
            [
                'label' =>   esc_html__('Active color', 'xltab'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .xldswitcher ul li.active a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_swit',
            [
                'label' =>   esc_html__('Switcher', 'xltab'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'swidth',
            [
                'label' =>   esc_html__( 'Width', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .xldswitcher ul .switch' => 'width: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'sheigt',
            [
                'label' =>   esc_html__( 'Height', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .xldswitcher ul .switch' => 'height: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'sbdr',
            [
                'label' =>   esc_html__( 'Border radius', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .xldswitcher ul .slider' => 'border-radius: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'sbwih',
            [
                'label' =>   esc_html__( 'Ball width and height', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .xldswitcher .slider:before' => 'height: {{SIZE}}px;width: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'sbbsp',
            [
                'label' =>   esc_html__( 'Ball bottom spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .xldswitcher .slider:before' => 'bottom: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'sbblsp',
            [
                'label' =>   esc_html__( 'Ball left spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .xldswitcher .slider:before' => 'left: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'sbvfdr',
            [
                'label' =>   esc_html__( 'Ball border radius', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .xldswitcher .slider:before' => 'border-radius: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'sbbolsp',
            [
                'label' =>   esc_html__( 'Ball on left spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    
                ],
                'selectors' => [
                    '{{WRAPPER}} .xldswitcher .switch.on .slider:before' => 'left: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
            'sbdrwid',
            [
                'label' =>   esc_html__( 'Border width', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1,
                    ],
                    
                ],
                'condition' => [
                    'tmpl' => 'two',
                ],                 
                'selectors' => [
                    '{{WRAPPER}} .xld-tab1 .slider:before,.xld-tab1 .switch .slider' => 'border-width: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'sw2pcbgr',
                'label' =>   esc_html__( 'Color', 'xltab' ),
                'types' => [ 'none','classic','gradient' ],
                'selector' => '{{WRAPPER}} .xldswitcher .switch.on .slider',
            ]
        );

        $this->add_control(
            'sw2pc',
            [
                'label' =>   esc_html__( 'Border color', 'xltab' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'tmpl' => 'one',
                ],                 
                'selectors' => [
                    '{{WRAPPER}} .xld-tab1 .slider:before,{{WRAPPER}} .xld-tab1 .switch .slider' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'sw1pc',
            [
                'label' =>   esc_html__( 'Secondary color', 'xltab' ),
                'type' => Controls_Manager::COLOR,                 
                'selectors' => [
                    '{{WRAPPER}} .xld-tab1 .switch.on .slider:before' => 'background: {{VALUE}};',
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

        $this->add_control(
            'mar_b',
            [
                'label' =>   esc_html__( 'Max wrapper width', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 1500,
                        'step' => 1,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .tabed-content' => 'max-width: {{SIZE}}px;margin:0px auto;',
                ],

            ]
        );

        $this->add_control(
            'cntmrtp',
            [
                'label' =>   esc_html__( 'Top spacing', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tabed-content' => 'margin-top: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_responsive_control(
          'd-pad',
          [
             'label' =>   esc_html__( 'Padding', 'xltab' ),
             'type' => Controls_Manager::DIMENSIONS,
             'size_units' => [ 'em','px'],
             
             'selectors' => [
                    '{{WRAPPER}} .tabed-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
             ],
          ]
        );

        $this->add_control(
            'content_bg',
            [
                'label' =>   esc_html__( 'Background', 'xltab' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .tabed-content' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dbdr',
            [
                'label' =>   esc_html__( 'Border radius', 'xltab' ),
                'type' =>  Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .tabed-content' => 'border-radius: {{SIZE}}px;',
                ],

            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         =>   esc_html__('Box Shadow','xltab'),
                    'name'          => 'cntshdw',
                    'selector'      => '{{WRAPPER}} .tabed-content',                    
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
            return do_shortcode('[XLTAB_INSERT_TPL id="'.$icon['template'].'"]');
        } else {
            return $icon['content'];
        } 

    }

}

$widgets_manager->register_widget_type(new \XLTab\Widgets\thepack_tab_accordion());