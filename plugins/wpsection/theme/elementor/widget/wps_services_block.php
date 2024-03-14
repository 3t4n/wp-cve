<?php



use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;




class wpsection_wps_services_block_Widget extends \Elementor\Widget_Base
{


    public function get_name()
    {
        return 'wpsection_wps_services_block';
    }

    public function get_title()
    {
        return __('Services Block', 'wpsection');
    }

    public function get_icon()
    {
        return 'eicon-global-settings';
    }

    public function get_keywords()
    {
        return ['wpsection', 'services_block'];
    }

    public function get_categories()
    {
        return ['wpsection_category'];
    }


    protected function _register_controls()
    {
        $this->start_controls_section(
            'h1_features_block',
            [
                'label' => esc_html__('Block Icon', 'wpsection'),
            ]
        );
        $this->add_control(
            'show_area',
            array(
                'label' => esc_html__('Show/Hide Section', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .mr_featured_section' => 'display: {{VALUE}} !important',
                ),
            )
        );

        $this->add_control(
            'btnlink',
            [
                'label' => __('Set a Link', 'rashid'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'rashid'),
                'show_external' => true,
                'default' => [
                    'url' => '',
                    'is_external' => true,
                    'nofollow' => true,
                ],

            ]
        );

        $this->end_controls_section();

        // New Tab#1

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Features Icon', 'rashid'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'show_icon',
            array(
                'label' => esc_html__('Show Icon Area', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_icon' => 'display: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'icons',
            [
                'label' => esc_html__('Enter The icons', 'wpsection'),
                'condition'    => array('show_icon' => 'show'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'solid',
                ],

            ]
        );

        $this->add_control(
            'icon_postions',
            [
                'label' => esc_html__('Icons Postions Style', 'plugin-name'),
                'condition'    => array('show_icon' => 'show'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'relative'  => esc_html__('Top', 'plugin-name'),
                    'absolute' => esc_html__('Left', 'plugin-name'),
                ],
            ]
        );
        $this->add_control(
            'icon_color',
            array(
                'label'     => __(' Icon Color', 'wpsection'),
                'condition'    => array('show_icon' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_icon i' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'icon_hover_color',
            array(
                'label'     => __(' Icon Hover Color', 'wpsection'),
                'condition'    => array('show_icon' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_featured_block:hover i' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'icon_padding',
            array(
                'label'     => __('Icon Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array('show_icon' => 'show'),
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .mr_block_icon i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'icon_typography',
                'condition'    => array('show_icon' => 'show'),
                'label'    => __('Typography', 'wpsection'),
                'selector' => '{{WRAPPER}}  .mr_block_icon i',
            )
        );

        $this->add_control(
            'icon_alingment',
            array(
                'label' => esc_html__('Icon Alignment', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'condition'    => array('show_icon' => 'show'),
                'separator' => 'after',
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'wpsection'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wpsection'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'wpsection'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_icon ' => 'text-align: {{VALUE}} !important',
                ),
            )
        );




        $this->add_control(
            'icon_bg_width',
            [
                'label' => esc_html__('Icon Backgorun Width', 'wpsection'), 'condition'    => array('show_icon' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
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
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_block_icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'icon_bg_height',
            [
                'label' => esc_html__('Icon Backgorun Height', 'wpsection'), 'condition'    => array('show_icon' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
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
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_block_icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'icon_bg_padding',
            array(
                'label'     => __('Background Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array('show_icon' => 'show'),
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .mr_block_icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'icon_bg_margin',
            array(
                'label'     => __('Background Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array('show_icon' => 'show'),
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}}  .mr_block_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_control(
            'icon_bg_color',
            array(
                'label'     => __('Background Color', 'wpsection'),
                'condition'    => array('show_icon' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .mr_block_icon' => 'background: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'icon_bg_hover_color',
            array(
                'label'     => __('Hover Background Color', 'wpsection'),
                'condition'    => array('show_icon' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .mr_featured_block:hover .mr_block_icon' => 'background: {{VALUE}} !important',

                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'icon_bg_border',
                'condition'    => array('show_icon' => 'show'),
                'selector' => '{{WRAPPER}}  .mr_block_icon',
            )
        );
        $this->add_control(
            'icon_bg_border_radius',
            array(
                'label' => esc_html__('Border Radius', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'condition'    => array('show_icon' => 'show'),
                'size_units' => ['px', '%'],
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
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_block_icon ' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'label' => esc_html__('Icon Bg Box Shadow', 'wpsection'),
                'condition'    => array('show_icon' => 'show'),
                'selector' => '{{WRAPPER}} .mr_block_icon',
            ]
        );



        $this->end_controls_section();


        // New Tab#2 Title

        $this->start_controls_section(
            'content_section_two',
            [
                'label' => __('Features Title', 'rashid'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );



        $this->add_control(
            'title',
            [
                'label'       => __('Title', 'wpsection'),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Enter your title', 'wpsection'),
                'default' => 'Residents',
            ]
        );
        $this->add_control(
            'show_title',
            array(
                'label' => esc_html__('Show Title', 'wpsectione'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_title' => 'display: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'title_alingment',
            array(
                'label' => esc_html__('Alignment', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'wpsection'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wpsection'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'wpsection'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'condition'    => array('show_title' => 'show'),
                'toggle' => true,
                'selectors' => array(

                    '{{WRAPPER}} .mr_block_title' => 'text-align: {{VALUE}} !important',
                ),
            )
        );


        $this->add_control(
            'title_padding',
            array(
                'label'     => __('Padding', 'wpsection'),
                'condition'    => array('show_title' => 'show'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .mr_block_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'condition'    => array('show_title' => 'show'),
                'label'    => __('Typography', 'wpsection'),
                'selector' => '{{WRAPPER}} .mr_block_title',
            )
        );
        $this->add_control(
            'title_color',
            array(
                'label'     => __('Color', 'wpsection'),
                'condition'    => array('show_title' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_title a' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'title_hover_color',
            array(
                'label'     => __('Color Hover', 'wpsection'),
                'condition'    => array('show_title' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_featured_block:hover a' => 'color: {{VALUE}} !important',

                ),
            )
        );




        $this->end_controls_section();

        // New Tab#3 Text

        $this->start_controls_section(
            'content_section_three',
            [
                'label' => __('Features Text', 'rashid'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'text',
            [
                'label'       => __('Description Text', 'wpsection'),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Enter your Description', 'wpsection'),
                'default' => 'The wise man therefore always holds in matters too principle of selection.',
            ]
        );
        $this->add_control(
            'show_text',
            array(
                'label' => esc_html__('Show Text', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .mr_f_block_text' => 'display: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'text_alingment',
            array(
                'label' => esc_html__('Alignment', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'wpsection'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wpsection'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'wpsection'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'condition'    => array('show_text' => 'show'),
                'toggle' => true,
                'selectors' => array(
                    '{{WRAPPER}} .mr_f_block_text' => 'text-align: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'text_padding',
            array(
                'label'     => __('Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'condition'    => array('show_text' => 'show'),
                'selectors' => array(
                    '{{WRAPPER}} .mr_f_block_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'text_typography',
                'condition'    => array('show_text' => 'show'),
                'label'    => __('Typography', 'wpsection'),
                'selector' => '{{WRAPPER}} .mr_f_block_text',
            )
        );
        $this->add_control(
            'text_color',
            array(
                'label'     => __('Color', 'wpsection'),
                'condition'    => array('show_text' => 'show'),
                'separator' => 'after',
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_f_block_text' => 'color: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'text_hover_color',
            array(
                'label'     => __('Hover Color', 'wpsection'),
                'condition'    => array('show_text' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_featured_block:hover .mr_f_block_text ' => 'color: {{VALUE}} !important',
                ),
            )
        );


        $this->end_controls_section();

        // New Tab#  Button

        $this->start_controls_section(
            'content_button',
            [
                'label' => __('Button Settings', 'rashid'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_button',
            array(
                'label' => esc_html__('Show Button', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_bttn' => 'display: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'bttn',
            [
                'label'       => __('Button Text', 'wpsection'),
                'condition'    => array('show_button' => 'show'),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('View More', 'wpsection'),
                'default' => 'View More',
            ]
        );
        $this->add_control(
            'button_alingment',
            array(
                'label' => esc_html__('Alignment', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'condition'    => array('show_button' => 'show'),
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'wpsection'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wpsection'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'wpsection'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_bttn' => 'text-align: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'button_color',
            array(
                'label'     => __('Button Text Color', 'wpsection'),
                'condition'    => array('show_button' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_bttn .mr_btton_a' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'button_hover_color',
            array(
                'label'     => __('Button Text Hover Color', 'wpsection'),
                'condition'    => array('show_button' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_bttn:hover .mr_btton_a ' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'button_bg_color',
            array(
                'label'     => __('Background Color', 'wpsection'),
                'condition'    => array('show_button' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_bttn .mr_btton_a' => 'background: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'button_hover_bg_color',
            array(
                'label'     => __('Hover Color', 'wpsection'),
                'condition'    => array('show_button' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_bttn .mr_btton_a:before' => 'background: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'button_padding',
            array(
                'label'     => __('Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array('show_button' => 'show'),
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .mr_block_bttn .mr_btton_a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'button_margin',
            array(
                'label'     => __('Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array('show_button' => 'show'),
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_bttn .mr_btton_a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_typography',
                'condition'    => array('show_button' => 'show'),
                'label'    => __('Typography', 'wpsection'),
                'selector' => '{{WRAPPER}} .mr_block_bttn .mr_btton_a',
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'button_border',
                'condition'    => array('show_button' => 'show'),
                'selector' => '{{WRAPPER}} .mr_block_bttn .mr_btton_a',
            )
        );
        $this->add_control(
            'button_border_radius',
            array(
                'label' => esc_html__('Border Radius', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'condition'    => array('show_button' => 'show'),
                'size_units' => ['px', '%'],
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
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_block_bttn .mr_btton_a' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_shadow',
                'condition'    => array('show_button' => 'show'),
                'label' => esc_html__('Button Shadow', 'wpsection'),
                'selector' => '{{WRAPPER}} .mr_block_bttn .mr_btton_a',
            ]
        );
        $this->end_controls_section();


        // New Tab#4 Block 

        $this->start_controls_section(
            'content_section_four',
            [
                'label' => __('Block Setting', 'rashid'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'block_width',
            [
                'label' => esc_html__('Block Width', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
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
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_featured_block' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'block_height',
            [
                'label' => esc_html__('Block Height', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
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
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_featured_block' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );



        $this->add_control(
            'show_block',
            array(
                'label' => esc_html__('Show Block', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .mr_featured_block' => 'display: {{VALUE}} !important',
                ),
            )
        );




        $this->add_control(
            'block_color',
            array(
                'label'     => __('Background Color', 'wpsection'),
                'condition'    => array('show_block' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_featured_block' => 'background: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'block_hover_color',
            array(
                'label'     => __('Hover Color', 'wpsection'),
                'condition'    => array('show_block' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_featured_block:hover' => 'background: {{VALUE}} !important',
                ),
            )
        );





        $this->add_control(
            'block_margin',
            array(
                'label'     => __('Block Margin', 'wpsection'),
                'condition'    => array('show_block' => 'show'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .mr_featured_block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'block_padding',
            array(
                'label'     => __('Block Padding', 'wpsection'),
                'condition'    => array('show_block' => 'show'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}}  .mr_featured_block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'block_shadow',
                'condition'    => array('show_block' => 'show'),
                'label' => esc_html__('Box Shadow', 'wpsection'),
                'selector' => '{{WRAPPER}} .mr_featured_block',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'block_border',
                'condition'    => array('show_block' => 'show'),
                'label' => esc_html__('Box Border', 'wpsection'),
                'selector' => '{{WRAPPER}} .mr_featured_block',
            ]
        );
        $this->add_control(
            'block_border_radius',
            array(
                'label' => esc_html__('Border Radius', 'wpsection'),
                'condition'    => array('show_block' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'condition'    => array('show_button' => 'show'),
                'size_units' => ['px', '%'],
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
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_featured_block' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            )
        );

        $this->add_control(
            'sec_class',
            [
                'label'       => __('Custom Class', 'rashid'),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active' => true,
                ],
                'placeholder' => __('Set a personal CSS class', 'rashid'),
            ]
        );

        $this->add_control(
            'block_transiotn',
            [
                'label' => esc_html__('Hover Transition', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['Second', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 99,
                        'step' => 5,
                    ],

                ],
                'default' => [
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mr_featured_block,.mr_f_block_text,.mr_block_icon,.mr_block_title a,.mr_featured_block:hover .mr_block_icon,.mr_block_icon i' => 'transition: .{{SIZE}}s;',

                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $allowed_tags = wp_kses_allowed_html('post');
?>


        <?php


        ?>


        <div class="feature-block mr_featured_section <?php echo esc_attr($settings['sec_class']); ?> ">

            <div class="mr_featured_block inner-box hvr-float-shadow wow fadeInUp" data-wow-duration="1500ms">

                <div class="mr_block_icon " style="position: <?php echo esc_attr($settings['icon_postions']); ?>">
                    <a href="<?php echo esc_url($settings['btnlink']['url']); ?>"><i class="<?php echo esc_attr($settings['icons']['value']); ?>"></i></a>
                </div>
                <h4 class="mr_block_title"><a href="<?php echo esc_url($settings['btnlink']['url']); ?>"><?php echo $settings['title']; ?></a></h4>

                <p class="mr_f_block_text "><?php echo $settings['text']; ?></p>

                <div class="mr_block_bttn btn-box clearfix">
                    <a href="<?php echo esc_url($settings['btnlink']['url']); ?>" class="mr_btton_a theme-btn btn-style-one"><?php echo $settings['bttn']; ?><i class="flaticon-right-arrow"></i></a>
                </div>

            </div>

        </div>


<?php
    }
}


Plugin::instance()->widgets_manager->register(new \wpsection_wps_services_block_Widget());
