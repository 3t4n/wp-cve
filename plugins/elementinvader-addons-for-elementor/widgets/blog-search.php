<?php

namespace ElementinvaderAddonsForElementor\Widgets;

use ElementinvaderAddonsForElementor\Core\Elementinvader_Base;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Typography;
use Elementor\Editor;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Core\Schemes;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use ElementinvaderAddonsForElementor\Modules\Forms\Ajax_Handler;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class EliBlog_Search extends Elementinvader_Base {

    // Default widget settings
    public $defaults = array();
    public $view_folder = 'search_form';
    public $inline_css = '';
    public $inline_css_tablet = '';
    public $inline_css_mobile = '';
    public $items_num = 0;

    public function __construct($data = array(), $args = null) {
        wp_enqueue_style('eli-main', plugins_url('/assets/css/main.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__));
        parent::__construct($data, $args);
    }

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'eli-blog-search';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__('Eli Blog Search', 'elementinvader-addons-for-elementor');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-search';
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function register_controls() {

        /* START Submit Button */

        $this->start_controls_section(
                'section_submit_button',
                [
                    'label' => esc_html__('Submit Button', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'special_result_page',
                [
                    'label' => esc_html__('Special Result Page', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__('put url if you want set special page for results', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'button_text',
                [
                    'label' => esc_html__('Text', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__('Send', 'elementinvader-addons-for-elementor'),
                    'placeholder' => esc_html__('Send', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_responsive_control(
                'button_width',
                [
                    'label' => esc_html__('Column Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'elementinvader-addons-for-elementor'),
                        '100' => '100%',
                        '80' => '80%',
                        '75' => '75%',
                        '66' => '66%',
                        '60' => '60%',
                        '50' => '50%',
                        '40' => '40%',
                        '33' => 'calc(100% / 3)',
                        '25' => '25%',
                        '20' => '20%',
                        'auto' => 'auto',
                        'auto_flexible' => 'auto flexible',
                    ],
                    'default' => '100',
                ]
        );

        $this->add_responsive_control(
                'button_align',
                [
                    'label' => esc_html__('Alignment', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'start' => [
                            'title' => esc_html__('Left', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => esc_html__('Center', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-text-align-center',
                        ],
                        'end' => [
                            'title' => esc_html__('Right', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-text-align-right',
                        ],
                        'stretch' => [
                            'title' => esc_html__('Justified', 'elementinvader-addons-for-elementor'),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'render_type' => 'template',
                    'default' => 'stretch',
                    'prefix_class' => 'elementor%s-button-align-',
                ]
        );

        $this->add_control(
                'selected_button_icon',
                [
                    'label' => esc_html__('Icon', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::ICONS,
                    'fa4compatibility' => 'button_icon',
                    'label_block' => true,
                ]
        );

        $this->add_control(
                'button_icon_align',
                [
                    'label' => esc_html__('Icon Position', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'left',
                    'options' => [
                        'left' => esc_html__('Before', 'elementinvader-addons-for-elementor'),
                        'right' => esc_html__('After', 'elementinvader-addons-for-elementor'),
                    ],
                    'condition' => [
                        'selected_button_icon[value]!' => '',
                    ],
                ]
        );

        $this->add_control(
                'button_icon_indent',
                [
                    'label' => esc_html__('Icon Spacing', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'range' => [
                        'px' => [
                            'max' => 50,
                        ],
                    ],
                    'condition' => [
                        'selected_button_icon[value]!' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                        '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'button_css_id',
                [
                    'label' => esc_html__('Button ID', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::TEXT,
                    'default' => '',
                    'title' => esc_html__('Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementinvader-addons-for-elementor'),
                    'label_block' => false,
                    'description' => esc_html__('Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elementinvader-addons-for-elementor'),
                    'separator' => 'before',
                ]
        );

        $this->end_controls_section();
        /* end */

        /* FIELD_SEARCH */
        $this->start_controls_section(
                'field_search_',
                [
                    'label' => esc_html__('Field Search', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );
        
        $this->add_control(
                'field_search_label_text',[
                        'label'=>esc_html__('Label Text', 'elementinvader-addons-for-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'default' => 'Search',
                    ]  
                );
        
        $this->add_control(
                'field_search_placeholder_text',[
                        'label'=>esc_html__('Placeholder Text', 'elementinvader-addons-for-elementor'),
                        'type' => Controls_Manager::TEXT,
                        'default' => 'Search',
                    ]  
                );
        
        $this->end_controls_section();
        /* END FIELD_SEARCH */
        
        /* TAB_STYLE */
        $this->start_controls_section(
                'section_form_style',
                [
                    'label' => esc_html__('Search Form', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->add_control(
                'column_gap',
                [
                    'label' => esc_html__('Columns Gap', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};;',
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_container' => 'margin-left: -{{SIZE}}{{UNIT}};margin-right: -{{SIZE}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'row_gap',
                [
                    'label' => esc_html__('Rows Gap', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 10,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                ]
        );
        
        
        $this->add_responsive_control(
                'width',
                [
                    'label' => esc_html__('Field Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        '' => esc_html__('Default', 'elementinvader-addons-for-elementor'),
                        'auto' => esc_html__('Auto', 'elementinvader-addons-for-elementor'),
                        '100%' => '100%',
                        '80%' => '80%',
                        '75%' => '75%',
                        '66%' => '66%',
                        '60%' => '60%',
                        '50%' => '50%',
                        '40%' => '40%',
                        '33%' => '33%',
                        '25%' => '25%',
                        '20%' => '20%',
                        'auto' => 'auto',
                        'auto_flexible' => 'auto flexible',
                    ],
                    'selectors_dictionary' => [
                        'auto' => 'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        '100%' =>  'width:100%;-webkit-flex:0 0 100%;flex:0 0 100%',
                        '80%' =>  'width:80%;-webkit-flex:0 0 80%;flex:0 0 80%',
                        '75%' =>  'width:75%;-webkit-flex:0 0 75%;flex:0 0 75%',
                        '66%' =>  'width:66%;-webkit-flex:0 0 66%;flex:0 0 66%',
                        '60%' =>  'width60:%;-webkit-flex:0 0 60%;flex:0 0 60%',
                        '50%' =>  'width:50%;-webkit-flex:0 0 50%;flex:0 0 50%',
                        '40%' =>  'width:40%;-webkit-flex:0 0 40%;flex:0 0 40%',
                        '33%' =>  'width:33%;-webkit-flex:0 0 calc(100% / 3);flex:0 0 calc(100% / 3)',
                        '25%' =>  'width:25%;-webkit-flex:0 0 25%;flex:0 0 25%',
                        '20%' =>  'width:20%;-webkit-flex:0 0 20%;flex:0 0 20%',
                        'auto' =>  'width:auto;-webkit-flex:0 0 auto;flex:0 0 auto',
                        'auto_flexible' =>  'width:auto;-webkit-flex:1 2 auto;flex:1 2 auto',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search .eli_f .eli_f_group' => '{{UNIT}}',
                    ],
                    'default' => '100',
                ]
        );
                            
        $this->add_control(
                'heading_label',
                [
                    'label' => esc_html__('Label', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
        );
        
        $this->add_control(
                'label_hide',
                [
                        'label' => esc_html__( 'Hide Element', 'eli-blocks' ),
                        'type' => Controls_Manager::SWITCHER,
                        'none' => esc_html__( 'Hide', 'eli-blocks' ),
                        'block' => esc_html__( 'Show', 'eli-blocks' ),
                        'return_value' => 'none',
                        'default' => '',
                        'selectors' => [
                            '{{WRAPPER}} .eli-blog-search .eli_f .eli_f_group label' => 'display: {{VALUE}};',
                        ],
                ]
        );

        $this->add_control(
                'label_spacing',
                [
                    'label' => esc_html__('Spacing', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'default' => [
                        'size' => 0,
                    ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 60,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'label_hide' => '',
                    ],
                ]
        );

        $this->start_controls_tabs('tabs_label_style');
        /* START Label Normal */
        $this->start_controls_tab(
                'tab_label_normal',
                [
                    'label' => esc_html__('Normal', 'elementinvader-addons-for-elementor'),
                    'condition' => [
                        'label_hide' => '',
                    ],
                ]
        );
        $this->add_control(
                'label_color',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group label' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'label_typography',
                    'selector' => '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group label',
                ]
        );
        $this->end_controls_tab();
        /* END Label Normal */
        /* START Label Hover */
        $this->start_controls_tab(
                'tab_label_hover',
                [
                    'label' => esc_html__('Hover', 'elementinvader-addons-for-elementor'),
                    'condition' => [
                        'label_hide' => '',
                    ],
                ]
        );

        $this->add_control(
                'label_color_hover',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group:hover label' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'label_typography_hover',
                    'selector' => '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group:hover label',
                ]
        );

        $this->add_control(
                'label_effect_duration',
                [
                    'label' => esc_html__('Transition Duration', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search .eli_f .eli_f_group label' => 'transition-duration: {{SIZE}}ms',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->end_controls_tab();


        /* END Label Hover */

        $this->end_controls_tabs();
        $this->end_controls_section();
        /* END FORM */

        /* START FIELD */
        $this->start_controls_section(
                'section_field_style',
                [
                    'label' => esc_html__('Field', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->start_controls_tabs('tabs_field_style');
        /* START FIELD Normal */
        $this->start_controls_tab(
                'tab_field_normal',
                [
                    'label' => esc_html__('Normal', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'field_text_color',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'field_typography',
                    'selector' => '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field',
                ]
        );

        $this->add_control(
                'field_height',
                [
                    'label' => esc_html__('Field Height', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 15,
                            'max' => 400,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group input.eli_f_field:not([type="radio"]):not([type="checkbox"])' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $object = [
            'normal' => '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group input.eli_f_field:not([type="radio"]):not([type="checkbox"])',
        ];
        $this->generate_renders_tabs($object, 'field_padding', ['padding']);

        $this->add_control(
                'field_background_color',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '#ffffff',
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field' => 'background-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'field_border_color',
                [
                    'label' => esc_html__('Border Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field' => 'border-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'field_border_width',
                [
                    'label' => esc_html__('Border Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'placeholder' => '1',
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'field_border_radius',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'box_box_shadow',
                    'exclude' => [
                        'box_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field',
                ]
        );
        $this->end_controls_tab();
        /* END FIELD Normal */

        /* START FIELD Focus */
        $this->start_controls_tab(
                'tab_field_focus',
                [
                    'label' => esc_html__('Focus', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'field_text_color_focus',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field:focus' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'field_typography_focus',
                    'selector' => '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field:focus',
                ]
        );

        $this->add_control(
                'field_background_color_focus',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field:focus' => 'background-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'field_border_color_focus',
                [
                    'label' => esc_html__('Border Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field:focus' => 'border-color: {{VALUE}};',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->add_control(
                'field_border_width_focus',
                [
                    'label' => esc_html__('Border Width', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'placeholder' => '1',
                    'size_units' => ['px'],
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field:focus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'field_border_radius_focus',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'field_box_shadow_focus',
                    'exclude' => [
                        'box_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field:focus',
                ]
        );

        $this->add_control(
                'field_duration',
                [
                    'label' => esc_html__('Transition Duration', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group .eli_f_field' => 'transition-duration: {{SIZE}}ms',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->end_controls_tab();

        /* END FIELD Focus */
        $this->end_controls_tabs();
        $this->end_controls_section();

        /* END FIELD */

        $this->start_controls_section(
                'section_button_style',
                [
                    'label' => esc_html__('Button', 'elementinvader-addons-for-elementor'),
                    'tab' => Controls_Manager::TAB_STYLE,
                ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
                'tab_button_normal',
                [
                    'label' => esc_html__('Normal', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'button_background_color',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_button button' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_text_color',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_button button' => 'color: {{VALUE}};',
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_button button svg' => 'fill: {{VALUE}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name' => 'button_typography',
                    'selector' => '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_button button',
                ]
        );

        $this->add_group_control(
                Group_Control_Border::get_type(), [
            'name' => 'button_border',
            'selector' => '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_button button',
                ]
        );

        $this->add_control(
                'button_border_radius',
                [
                    'label' => esc_html__('Border Radius', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', '%'],
                    'selectors' => [
                        '{{WRAPPER}}  .eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_button button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_control(
                'button_text_padding',
                [
                    'label' => esc_html__('Text Padding', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_button button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'button_box_shadow',
                    'exclude' => [
                        'button_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_button button',
                ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
                'tab_button_hover',
                [
                    'label' => esc_html__('Hover', 'elementinvader-addons-for-elementor'),
                ]
        );

        $this->add_control(
                'button_background_hover_color',
                [
                    'label' => esc_html__('Background Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}  .eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_button button:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_hover_color',
                [
                    'label' => esc_html__('Text Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}  .eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_button button:hover' => 'color: {{VALUE}};',
                    ],
                ]
        );

        $this->add_control(
                'button_hover_border_color',
                [
                    'label' => esc_html__('Border Color', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}}  .eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_button button:hover' => 'border-color: {{VALUE}};',
                    ],
                    'condition' => [
                        'button_border_border!' => '',
                    ],
                ]
        );


        $this->add_group_control(
                Group_Control_Box_Shadow::get_type(),
                [
                    'name' => 'button_box_shadow_hover',
                    'exclude' => [
                        'button_shadow_position',
                    ],
                    'selector' => '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_button button:hover',
                ]
        );


        $this->add_control(
                'button_hover_animation',
                [
                    'label' => esc_html__('Animation', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::HOVER_ANIMATION,
                ]
        );

        $this->add_control(
                'button_duration',
                [
                    'label' => esc_html__('Transition Duration', 'elementinvader-addons-for-elementor'),
                    'type' => Controls_Manager::SLIDER,
                    'render_type' => 'template',
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 3000,
                        ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_button button' => 'transition-duration: {{SIZE}}ms',
                    ],
                    'separator' => 'before',
                ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        parent::register_controls();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function render() {
        $id_int = substr($this->get_id_int(), 0, 3);

        $settings = $this->get_settings();

        $this->add_inline_editing_attributes( 'field_search_label_text', 'basic' );
        $this->add_render_attribute( 'field_search_label_text', [
                'class' => 'elementor-inline-editing'
        ] );
        
        
        $content = [];
        $this->add_field_css($settings, $prefix = 'button_width', 'button');
        $this->_generate_layout($settings, $content);
        $this->add_page_settings_css();
    }

    protected function _generate_layout($settings, $smart_data) {
        wp_enqueue_script('eli-main');

        $output = $this->view('widget_layout', ['settings' => $settings, 'smart_data' => $smart_data], true);

        $this->generate_css();
    }

    public function add_field_css($element, $prefix = 'width', $index = '') {
        if (empty($index) && isset($element['_id']))
            $index = $element['_id'];

        if (isset($element[$prefix]) && $element[$prefix])
            if ($element[$prefix] == 'auto_flexible')
                $this->inline_css .= "
                        #eli_" . $this->get_id_int() . ".eli-blog-search .eli_f .eli_f_group.eli_f_group_el_{$index} {
                            width:auto;-webkit-flex: 1 2 auto;
                            flex: 1 2 auto;
                        }
                    ";
            elseif ($element[$prefix] == 'auto')
                $this->inline_css .= "
                        #eli_" . $this->get_id_int() . ".eli-blog-search .eli_f .eli_f_group.eli_f_group_el_{$index} {
                            
                            width:auto;-webkit-flex: 0 0 auto;
                            flex: 0 0 auto;
                        }
                    ";
            else
                $this->inline_css .= "
                        #eli_" . $this->get_id_int() . ".eli-blog-search .eli_f .eli_f_group.eli_f_group_el_{$index} {
                            width: {$element[$prefix]}%;
                            -webkit-flex: 0 0 {$element[$prefix]}%;
                            flex: 0 0 {$element[$prefix]}%;
                        }
                    ";


        if (isset($element[$prefix . '_tablet']) && $element[$prefix . '_tablet'])
            if ($element[$prefix . '_tablet'] == 'auto_flexible')
                $this->inline_css_tablet .= "
                        #eli_" . $this->get_id_int() . ".eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_{$index} {
                            width:auto;-webkit-flex: 1 2 auto;
                            flex: 1 2 auto;
                        }
                    ";
            elseif ($element[$prefix . '_tablet'] == 'auto')
                $this->inline_css_tablet .= "
                        #eli_" . $this->get_id_int() . ".eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_{$index} {
                            width:auto;-webkit-flex: 0 0 auto;
                            flex: 0 0 auto;
                        }
                    ";
            else
                $this->inline_css_tablet .= "
                        #eli_" . $this->get_id_int() . ".eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_{$index} {
                            width: {$element[$prefix . '_tablet']}%;
                            -webkit-flex: 0 0 {$element[$prefix . '_tablet']}%;
                            flex: 0 0 {$element[$prefix . '_tablet']}%;
                        }
                    ";

        if (isset($element[$prefix . '_mobile']) && $element[$prefix . '_mobile'])
            if ($element[$prefix . '_mobile'] == 'auto')
                $this->inline_css_mobile .= "
                        #eli_" . $this->get_id_int() . ".eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_{$index} {
                            width:auto;-webkit-flex: 0 0 auto;
                            flex: 0 0 auto;
                        }
                    ";
            elseif ($element[$prefix . '_mobile'] == 'auto')
                $this->inline_css_mobile .= "
                        #eli_" . $this->get_id_int() . ".eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_{$index} {
                            width:auto;-webkit-flex: 1 2 auto;
                            flex: 1 2 auto;
                        }
                    ";
            else
                $this->inline_css_mobile .= "
                        #eli_" . $this->get_id_int() . ".eli-blog-search  .eli_f .eli_f_group.eli_f_group_el_{$index} {
                            width: {$element[$prefix . '_mobile']}%;
                            -webkit-flex: 0 0 {$element[$prefix . '_mobile']}%;
                            flex: 0 0 {$element[$prefix . '_mobile']}%;
                        }
                    ";
    }

    public function generate_css() {
        $output_css = '';
        $output_css .= $this->inline_css;
        $output_css .= sprintf('@media(max-width:%1$s){%2$s}', '991px', $this->inline_css_tablet);
        $output_css .= sprintf('@media(max-width:%1$s){%2$s}', '768px', $this->inline_css_mobile);
        
        wp_enqueue_style('eli-custom-inline', plugins_url( '/assets/css/custom-inline.css', ELEMENTINVADER_ADDONS_FOR_ELEMENTOR__FILE__ ));
      
        /* only for edit mode */
        if (Plugin::$instance->editor->is_edit_mode()) {
            echo '<style>'.sanitize_text_field($output_css).'</style>';
        } else {
            wp_add_inline_style( 'eli-custom-inline', $output_css );
        }
    }

    public function get_align_class($setting_data = '', $prefix = '') {
        $class = '';
        switch ($setting_data) {
            case 'start': $class = $prefix . 'left';
                break;
            case 'center': $class = $prefix . 'center';
                break;
            case 'end': $class = $prefix . 'right';
                break;
            case 'stretch': $class = $prefix . 'justify';
                break;
            default:
                break;
        }

        return $class;
    }

    public function el_icon_with_fallback($settings) {
        $migrated = isset($settings['__fa4_migrated']['selected_button_icon']);
        $is_new = empty($settings['button_icon']) && Icons_Manager::is_migration_allowed();

        if ($is_new || $migrated) {
            Icons_Manager::render_icon($settings['selected_button_icon'], ['aria-hidden' => 'true']);
        } else {
            ?><i class="<?php echo esc_attr($settings['button_icon']); ?>" aria-hidden="true"></i><?php
        }
    }

}
