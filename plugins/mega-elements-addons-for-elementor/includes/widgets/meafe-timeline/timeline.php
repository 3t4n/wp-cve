<?php

namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

class MEAFE_Timeline extends Widget_Base {
    public function get_name()
    {
        return 'meafe-timeline';
    }

    public function get_title()
    {
        return esc_html__('Timeline', 'mega-elements-addons-for-elementor');
    }

    public function get_categories()
    {
        return ['meafe-elements'];
    }

    public function get_icon()
    {
        return 'meafe-timeline';
    }

    public function get_style_depends()
    {
        return ['meafe-timeline'];
    }

    public function get_script_depends()
    {
        return ['meafe-timeline'];
    }

    protected function register_controls()
    {
        /**
         * Timeline General Settings
         */
        $this->start_controls_section(
            'meafe_timeline_content_general_settings',
            [
                'label'     => __('General Settings', 'mega-elements-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'btccgs_timeline_layouts',
            [
                'label'         => esc_html__('Select Layout', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => '1',
                'label_block'   => false,
                'options'       => [
                    '1'       => esc_html__('Layout One', 'mega-elements-addons-for-elementor'),
                    '2'       => esc_html__('Layout Two', 'mega-elements-addons-for-elementor'),
                    '3'       => esc_html__('Layout Three', 'mega-elements-addons-for-elementor'),
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'btcsis_timeline_per_line',
            [
                'label'     => esc_html__('No. of items per slide', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SELECT,
                'default'   => '4',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'condition' => [
                    'btccgs_timeline_layouts' => '2'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btcsis_timeline_swiper_nav',
            [
                'label'     => esc_html__('Enable Navigation', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default'   => 'yes',
                'condition' => [
                    'btccgs_timeline_layouts' => '2'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btcsis_timeline_prev_icon',
            [
                'label' => __('Previous Icon', 'mega-elements-addons-for-elementor'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-chevron-left',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'btcsis_timeline_swiper_nav' => 'yes',
                    'btccgs_timeline_layouts' => '2'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btcsis_timeline_next_icon',
            [
                'label' => __('Next Icon', 'mega-elements-addons-for-elementor'),
                'label_block' => false,
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'btcsis_timeline_swiper_nav' => 'yes',
                    'btccgs_timeline_layouts' => '2'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'btcsis_timeline_swiper_dots',
            [
                'label' => esc_html__('Show Swiper Dots', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'mega-elements-addons-for-elementor'),
                'label_off' => esc_html__('Hide', 'mega-elements-addons-for-elementor'),
                'return_value' => 'yes',
                'default' => '',
                'condition' => [
                    'btccgs_timeline_layouts' => '2'
                ],
                'frontend_available' => true,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'btcgs_timeline_title_icon_new',
            [
                'label'         => esc_html__('Icon', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::ICONS,
                'fa4compatibility' => 'btcgs_timeline_title_icon',
                'default'       => [
                    'value'         => 'fa fa-caret-left',
                    'library'       => 'fa-solid',
                ],
            ]
        );

        $repeater->add_control(
            'btccgs_timeline_image',
            [
                'label'       => esc_html__('Image', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'btccgs_timeline_date',
            [
                'label'       => esc_html__('Date', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__('2017-1019', 'mega-elements-addons-for-elementor'),
            ]
        );

        $repeater->add_control(
            'btccgs_timeline_title',
            [
                'label'       => esc_html__('Title', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__('The standard Lorem Ipsum passage, used since the 1500s', 'mega-elements-addons-for-elementor'),
            ]
        );

        $repeater->add_control(
            'btccgs_timeline_content',
            [
                'label'       => esc_html__('Timeline Content', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default'     => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor'),
            ]
        );


        $repeater_two = new Repeater();

        $repeater_two->add_control(
            'btcgs_timeline_two_title_icon_new',
            [
                'label'         => esc_html__('Icon', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::ICONS,
                'fa4compatibility' => 'btcgs_timeline_title_icon',
                'default'       => [
                    'value'         => 'fa fa-caret-left',
                    'library'       => 'fa-solid',
                ],
            ]
        );

        $repeater_two->add_control(
            'btccgs_timeline_two_date',
            [
                'label'       => esc_html__('Date', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__('2017-1019', 'mega-elements-addons-for-elementor'),
            ]
        );

        $repeater_two->add_control(
            'btccgs_timeline_two_title_two',
            [
                'label'       => esc_html__('Title One', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__('Self-Employed', 'mega-elements-addons-for-elementor'),
            ]
        );

        $repeater_two->add_control(
            'btccgs_timeline_two_title',
            [
                'label'       => esc_html__('Title Two', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'default'     => esc_html__('The standard Lorem Ipsum passage, used since the 1500s', 'mega-elements-addons-for-elementor'),
            ]
        );

        $repeater_two->add_control(
            'btccgs_timeline_two_content',
            [
                'label'       => esc_html__('Timeline Content', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'default'     => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'btccgs_timeline',
            array(
                'label'       => esc_html__('Timeline Items', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'condition' => [
                    'btccgs_timeline_layouts!' => '3'
                ],
                'default'     => array(
                    array(
                        'btccgs_timeline_image'         => ['url' => Utils::get_placeholder_image_src()],
                        'btccgs_timeline_date'          => esc_html__('2017-1019', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_title'         => esc_html__('Title One', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_content'       => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor'),
                        'btcgs_timeline_title_icon_new' => ['value' => 'fa fa-caret-left'],
                    ),
                    array(
                        'btccgs_timeline_image'         => ['url' => Utils::get_placeholder_image_src()],
                        'btccgs_timeline_date'          => esc_html__('2017-1019', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_title'         => esc_html__('Title Two', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_content'       => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor'),
                        'btcgs_timeline_title_icon_new' => ['value' => 'fa fa-caret-right']
                    ),
                    array(
                        'btccgs_timeline_image'         => ['url' => Utils::get_placeholder_image_src()],
                        'btccgs_timeline_date'          => esc_html__('2017-1019', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_title'         => esc_html__('Title Three', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_content'       => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor'),
                        'btcgs_timeline_title_icon_new' => ['value' => 'fa fa-caret-left'],
                    ),
                    array(
                        'btccgs_timeline_image'         => ['url' => Utils::get_placeholder_image_src()],
                        'btccgs_timeline_date'          => esc_html__('2017-1019', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_title'         => esc_html__('Title Four', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_content'       => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor'),
                        'btcgs_timeline_title_icon_new' => ['value' => 'fa fa-caret-left'],
                    ),
                ),
                'title_field' => '{{{ btccgs_timeline_title }}}',
            )
        );

        $this->add_control(
            'btccgs_timeline_two',
            array(
                'label'       => esc_html__('Timeline Items', 'mega-elements-addons-for-elementor'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater_two->get_controls(),
                'condition' => [
                    'btccgs_timeline_layouts' => '3'
                ],
                'default'     => array(
                    array(
                        'btccgs_timeline_two_date'          => esc_html__('2017-1019', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_two_title_two'     => esc_html__('Self Employed', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_two_title'         => esc_html__('Title One', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_two_content'       => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor'),
                        'btcgs_timeline_two_title_icon_new' => ['value' => 'fa fa-caret-left'],
                    ),
                    array(
                        'btccgs_timeline_two_date'          => esc_html__('2017-1019', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_two_title_two'         => esc_html__('Self Employed', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_two_title'         => esc_html__('Title Two', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_two_content'       => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor'),
                        'btcgs_timeline_two_title_icon_new' => ['value' => 'fa fa-caret-right']
                    ),
                    array(
                        'btccgs_timeline_two_date'          => esc_html__('2017-1019', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_two_title_two'     => esc_html__('Self Employed', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_two_title'         => esc_html__('Title Three', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_two_content'       => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor'),
                        'btcgs_timeline_two_title_icon_new' => ['value' => 'fa fa-caret-left'],
                    ),
                    array(
                        'btccgs_timeline_two_date'          => esc_html__('2017-1019', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_two_title_two'     => esc_html__('Self Employed', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_two_title'         => esc_html__('Title Four', 'mega-elements-addons-for-elementor'),
                        'btccgs_timeline_two_content'       => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'mega-elements-addons-for-elementor'),
                        'btcgs_timeline_two_title_icon_new' => ['value' => 'fa fa-caret-left'],
                    ),
                ),
                'title_field' => '{{{ btccgs_timeline_two_title }}}',
            )
        );

        $this->add_control(
            'btccgs_timeline_date_tag',
            [
                'label'       => esc_html__('Date Tag', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1'    => esc_html__('H1', 'mega-elements-addons-for-elementor'),
                    'h2'    => esc_html__('H2', 'mega-elements-addons-for-elementor'),
                    'h3'    => esc_html__('H3', 'mega-elements-addons-for-elementor'),
                    'h4'    => esc_html__('H4', 'mega-elements-addons-for-elementor'),
                    'h5'    => esc_html__('H5', 'mega-elements-addons-for-elementor'),
                    'h6'    => esc_html__('H6', 'mega-elements-addons-for-elementor'),
                    'span'  => esc_html__('Span', 'mega-elements-addons-for-elementor'),
                    'p'     => esc_html__('P', 'mega-elements-addons-for-elementor'),
                    'div'   => esc_html__('Div', 'mega-elements-addons-for-elementor'),
                ],
                'default' => 'h3'
            ]
        );

        $this->add_control(
            'btccgs_timeline_title_tag',
            [
                'label'       => esc_html__('Title Tag', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1'    => esc_html__('H1', 'mega-elements-addons-for-elementor'),
                    'h2'    => esc_html__('H2', 'mega-elements-addons-for-elementor'),
                    'h3'    => esc_html__('H3', 'mega-elements-addons-for-elementor'),
                    'h4'    => esc_html__('H4', 'mega-elements-addons-for-elementor'),
                    'h5'    => esc_html__('H5', 'mega-elements-addons-for-elementor'),
                    'h6'    => esc_html__('H6', 'mega-elements-addons-for-elementor'),
                    'span'  => esc_html__('Span', 'mega-elements-addons-for-elementor'),
                    'p'     => esc_html__('P', 'mega-elements-addons-for-elementor'),
                    'div'   => esc_html__('Div', 'mega-elements-addons-for-elementor'),
                ],
                'default' => 'h2'
            ]
        );


        $this->end_controls_section();

        /**
         * Timeline General Style
         */
        $this->start_controls_section(
            'meafe_timeline_style_date_style',
            [
                'label'     => __('Timeline Date', 'mega-elements-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btcsgs_timeline_date_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__('Timeline Date', 'mega-elements-addons-for-elementor'),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btcsgs_timeline_date_padding',
            [
                'label'     => __('Padding', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsgs_timeline_date_spacing',
            [
                'label'     => __('Bottom Spacing', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-date' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btcsgs_timeline_date_color',
            [
                'label'     => __('Date Color', 'mega-elements-addons-for-elementor'),
                'default'   => '#5081F5',
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-date :where(h1, h2, h3, h4, h5, h6, div, p, span)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btcsgs_timeline_date_bg_color',
            [
                'label'     => __('Background Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-date' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-timeline-date:after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'btcsgs_timeline_date_typography',
                'label'     => __('Typography', 'mega-elements-addons-for-elementor'),
                'selector'  => '{{WRAPPER}} .meafe-timeline-date :where(h1, h2, h3, h4, h5, h6, div, p, span)',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'meafe_timeline_style_title_style',
            [
                'label'     => __('Timeline Title', 'mega-elements-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btcsgs_timeline_title_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__('Timeline Title', 'mega-elements-addons-for-elementor'),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btcsgs_timeline_title_padding',
            [
                'label'     => __('Padding', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsgs_timeline_title_spacing',
            [
                'label'     => __('Bottom Spacing', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btcsgs_timeline_title_color',
            [
                'label'     => __('Title Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#121212',
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-title :where(h1, h2, h3, h4, h5, h6, div, p, span)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btcsgs_timeline_title_bg_color',
            [
                'label'     => __('Background Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-title' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-timeline-title:after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'btcsgs_timeline_title_typography',
                'label'     => __('Typography', 'mega-elements-addons-for-elementor'),
                'selector'  => '{{WRAPPER}} .meafe-timeline-title :where(h1, h2, h3, h4, h5, h6, div, p, span)',
            ]
        );

        // Timeline date title

        $this->add_control(
            'btcsgs_timeline_title_heading_date_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__('Timeline Date Title', 'mega-elements-addons-for-elementor'),
                'separator' => 'before',
                'condition' => [
                    'btccgs_timeline_layouts' => '3'
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsgs_timeline_title_date_padding',
            [
                'label'     => __('Padding', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-date-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'condition' => [
                    'btccgs_timeline_layouts' => '3'
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsgs_timeline_title_date_spacing',
            [
                'label'     => __('Bottom Spacing', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-date-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'btccgs_timeline_layouts' => '3'
                ],
            ]
        );

        $this->add_control(
            'btcsgs_timeline_title_date_color',
            [
                'label'     => __('Title Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#121212',
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-date-title :where(h1, h2, h3, h4, h5, h6, div, p, span)' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'btccgs_timeline_layouts' => '3'
                ],
            ]
        );

        $this->add_control(
            'btcsgs_timeline_date_title_bg_color',
            [
                'label'     => __('Background Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-date-title' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-timeline-date-title:after' => 'color: {{VALUE}}',
                ],
                'condition' => [
                    'btccgs_timeline_layouts' => '3'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'btcsgs_timeline_title_date_typography',
                'label'     => __('Typography', 'mega-elements-addons-for-elementor'),
                'selector'  => '{{WRAPPER}} .meafe-timeline-date-title :where(h1, h2, h3, h4, h5, h6, div, p, span)',
                'condition' => [
                    'btccgs_timeline_layouts' => '3'
                ],
            ]
        );

        // Timeline date title end


        $this->end_controls_section();



        $this->start_controls_section(
            'meafe_timeline_style_contnet_style',
            [
                'label'     => __('Timeline Content', 'mega-elements-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btcsgs_timeline_content_heading_title',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__('Timeline Content', 'mega-elements-addons-for-elementor'),
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'btcsgs_timeline_content_padding',
            [
                'label'     => __('Padding', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsgs_timeline_content_spacing',
            [
                'label'     => __('Bottom Spacing', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-content' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'btcsgs_timeline_content_color',
            [
                'label'     => __('Text Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btcsgs_timeline_content_bg_color',
            [
                'label'     => __('Background Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-content' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-timeline-content:after' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'btcsgs_timeline_content_typography',
                'label'     => __('Typography', 'mega-elements-addons-for-elementor'),
                'selector'  => '{{WRAPPER}} .meafe-timeline-content',
            ]
        );

        $this->end_controls_section();

        /**
         * Timeline Divider
         */
        $this->start_controls_section(
            'meafe_timeline_style_divider_style',
            [
                'label'     => __('Divider', 'mega-elements-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'btcsis_timeline_divider_type',
                'selector'  => '{{WRAPPER}} .meafe-timeline-wrap::after',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '0',
                            'right' => '1',
                            'bottom' => '0',
                            'left' => '0',
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
                        'default' => '#F3F3F3',
                    ],
                ],
                'condition' => [
                    'btccgs_timeline_layouts' => ['1', '3']
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'btcsis_timeline_divider_type_lay_2',
                'selector'  => '{{WRAPPER}} .meafe-timeline-wrap::after',
                'fields_options' => [
                    'border' => [
                        'default' => 'solid',
                    ],
                    'width' => [
                        'default' => [
                            'top' => '1',
                            'right' => '0',
                            'bottom' => '0',
                            'left' => '0',
                            'isLinked' => false,
                        ],
                    ],
                    'color' => [
                        'default' => '#F3F3F3',
                    ],
                ],
                'condition' => [
                    'btccgs_timeline_layouts' => '2',
                ],
            ]
        );

        $this->end_controls_section();


        /**
         * Icon
         */
        $this->start_controls_section(
            'meafe_timeline_style_icon_style',
            [
                'label'     => __('Icon', 'mega-elements-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btcsgs_timeline_icon_color',
            [
                'label'     => __('Icon Color', 'mega-elements-addons-for-elementor'),
                'default'   => '#f3f3f3',
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-timeline-main.layout-1 .meafe-timeline-wrap::before' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .meafe-timeline-main.layout-1 .meafe-timeline-inner-wrap:last-child::after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'btcsgs_timeline_icon_bg_color',
            [
                'label'     => __('Icon Background Color', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-icon .meafe-timeline-icon-inner' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsgs_timeline_icon_padding',
            [
                'label'     => __('Padding', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-main .meafe-timeline-icon .meafe-timeline-icon-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bacsts_timeline_tab_icon_size',
            [
                'label'     => esc_html__('Icon Size', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range'      => [
                    'px'     => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-main .meafe-timeline-icon i' => 'font-size: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .meafe-timeline-main .meafe-timeline-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'btcsis_timeline_icon_border',
                'selector'  => '{{WRAPPER}} .meafe-timeline-icon .meafe-timeline-icon-inner',
            ]
        );

        $this->add_responsive_control(
            'btcsis_timeline_icon_border_radius',
            [
                'label'     => __('Icon Border Radius', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'  => ['top' => 100, 'right' => 100, 'bottom' => 100, 'left' => 100, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-icon .meafe-timeline-icon-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();


        /**
         * Timeline Image Style
         */
        $this->start_controls_section(
            'meafe_timeline_style_image_style',
            [
                'label'     => __('Image Style', 'mega-elements-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'btccgs_timeline_layouts' => ['1', '2']
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsis_timeline_image_width',
            [
                'label'     => __('Width', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default'   => [
                    'size' => 200
                ],
                'range'     => [
                    'px' => [
                        'min' => 150,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-reviewer-thumb img' => 'width: {{SIZE}}px',
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsis_timeline_image_height',
            [
                'label'     => __('Height', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default'   => [
                    'size' => 200
                ],
                'range'     => [
                    'px' => [
                        'min' => 150,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-reviewer-thumb img' => 'height: {{SIZE}}px',
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsis_timeline_object_fit',
            [
                'label' => esc_html__('Object Fit', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    ''        => esc_html__('Default', 'mega-elements-addons-for-elementor'),
                    'fill'    => esc_html__('Fill', 'mega-elements-addons-for-elementor'),
                    'cover'   => esc_html__('Cover', 'mega-elements-addons-for-elementor'),
                    'contain' => esc_html__('Contain', 'mega-elements-addons-for-elementor'),
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-reviewer-thumb img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'btcsis_timeline_image_border',
                'selector'  => '{{WRAPPER}} .meafe-timeline-reviewer-thumb img',
            ]
        );

        $this->add_responsive_control(
            'btcsis_timeline_image_border_radius',
            [
                'label'     => __('Border Radius', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'  => ['top' => 100, 'right' => 100, 'bottom' => 100, 'left' => 100, 'unit' => '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-timeline-reviewer-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'btcsis_timeline_image_box_shadow',
                'selector'  => '{{WRAPPER}} .meafe-timeline-reviewer-thumb img',
            ]
        );

        $this->end_controls_section();

        /**
         * Arrows
         */
        $this->start_controls_section(
            'meafe_timeline_swiper_style_nav_arrow',
            [
                'label' => __('Navigation :: Arrow', 'mega-elements-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'btccgs_timeline_layouts' => '2'
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsis_timeline_arrow_size',
            [
                'label' => __('Size', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsis_timeline_arrow_width',
            [
                'label' => __('Width', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-wrap .nav' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btcsis_timeline_arrow_border',
                'selector' => '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next',
            ]
        );

        $this->add_responsive_control(
            'btcsis_timeline_arrow_border_radius',
            [
                'label' => __('Border Radius', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->start_controls_tabs('btcsis_timeline_tabs_arrow');

        $this->start_controls_tab(
            'btcsis_timeline_tab_arrow_normal',
            [
                'label' => __('Normal', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'btcsis_timeline_arrow_color',
            [
                'label' => __('Text Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btcsis_timeline_arrow_bg_color',
            [
                'label' => __('Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev, {{WRAPPER}} .meafa-navigation-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'btcsis_timeline_tab_arrow_hover',
            [
                'label' => __('Hover', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'btcsis_timeline_arrow_hover_color',
            [
                'label' => __('Text Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev:hover, {{WRAPPER}} .meafa-navigation-next:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'btcsis_timeline_arrow_hover_bg_color',
            [
                'label' => __('Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-navigation-prev:hover, {{WRAPPER}} .meafa-navigation-next:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'meafe_timeline_swiper_style_nav_dots',
            [
                'label' => __('Navigation :: Dots', 'mega-elements-addons-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'btccgs_timeline_layouts' => '2'
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsis_timeline_dots_nav_spacing',
            [
                'label' => __('Spacing', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default'   => [
                    'size' => 10,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_responsive_control(
            'btcsis_timeline_dots_nav_align',
            [
                'label' => __('Alignment', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'mega-elements-addons-for-elementor'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'mega-elements-addons-for-elementor'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'mega-elements-addons-for-elementor'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => true,
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination' => 'text-align: {{VALUE}}'
                ]
            ]
        );

        $this->start_controls_tabs('btcsis_timeline_tabs_dots');
        $this->start_controls_tab(
            'btcsis_timeline_tab_dots_normal',
            [
                'label' => __('Normal', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'btcsis_timeline_dots_nav_size',
            [
                'label' => __('Size', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'default'   => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'btcsis_timeline_dots_nav_color',
            [
                'label' => __('Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'btcsis_timeline_tab_dots_active',
            [
                'label' => __('Active', 'mega-elements-addons-for-elementor'),
            ]
        );

        $this->add_control(
            'btcsis_timeline_dots_nav_active_color',
            [
                'label' => __('Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .meafa-swiper-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    public function get_nav_details()
    {
        $settings   = $this->get_settings_for_display();
        $nav        = $settings['btcsis_timeline_swiper_nav'];
        $nav_prev   = $settings['btcsis_timeline_prev_icon'];
        $nav_next   = $settings['btcsis_timeline_next_icon'];

        if ($nav === 'yes') {
            $return_all = ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'];
            $return_alls = ['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>'];
            $return_all_start = ['', '<i class="fa fa-angle-right" aria-hidden="true"></i>'];
            $return_all_end = ['<i class="fa fa-angle-left" aria-hidden="true"></i>', ''];

            if ($nav_prev['library'] != 'svg' && $nav_next['library'] != 'svg') {
                return (['<i class="' . esc_attr($nav_prev['value']) . '" aria-hidden="true"></i>', '<i class="' . esc_attr($nav_next['value']) . '" aria-hidden="true"></i>']);
            }

            if ($nav_prev['library'] == 'svg' && $nav_next['library'] == 'svg') {
                return (['<img src="' . esc_url($nav_prev['value']['url']) . '">', '<img src="' . esc_url($nav_next['value']['url']) . '">']);
            }

            if ($nav_prev['library'] == '' && $nav_next['library'] == 'svg') {
                array_pop($return_all_start);
                array_push($return_all_start, esc_url($nav_next['value']['url']));
                return (['', '<img src="' . $return_all_start[1] . '">']);
                // return return_all_start;
            }

            if ($nav_prev['library'] != 'svg' && $nav_next['library'] == 'svg') {
                array_pop($return_all);
                array_push($return_all, '<img src="' . esc_url($nav_next['value']['url']) . '">');
                return $return_all;
            }

            if ($nav_prev['library'] == 'svg' && $nav_next['library'] == '') {
                array_reverse($return_all_end);
                array_pop($return_all_end);
                array_push($return_all_end, esc_url($nav_prev['value']['url']));
                array_reverse($return_all_end);
                return (['<img src="' . $return_all_end[0] . '">', '']);
            }

            if ($nav_prev['library'] == 'svg' && $nav_next['library'] != 'svg') {
                array_reverse($return_alls);
                array_pop($return_alls);
                array_push($return_alls, '<img src="' . esc_url($nav_prev['value']['url']) . '">');
                array_reverse($return_alls);
                return $return_alls;
            }
        }

        return (['<i class="fa fa-angle-left" aria-hidden="true"></i>', '<i class="fa fa-angle-right" aria-hidden="true"></i>']);
    }

    protected function render()    {
        $settings  = $this->get_settings_for_display();
        $nav_icons = $this->get_nav_details();

        $this->add_render_attribute('icon', 'class', 'meafe-timeline-icon');
        $this->add_render_attribute('btccgs_timeline_date', 'class', 'meafe-timeline-date');
        $this->add_render_attribute('btccgs_timeline_title', 'class', 'meafe-timeline-title');
        $this->add_render_attribute('btccgs_timeline_content', 'class', 'meafe-timeline-content');

        $allowedOptions = ['1', '2', '3'];
        $layouts_safe = in_array($settings['btccgs_timeline_layouts'], $allowedOptions) ? $settings['btccgs_timeline_layouts'] : '1';
        ?>
        <div id="<?php echo esc_attr($this->get_id()); ?>" class="meafe-timeline-main layout-<?php echo esc_attr($layouts_safe); ?> center-aligned-content">
            <div class="meafe-timeline-wrap">
                <?php
                if ($layouts_safe === '1' || $layouts_safe === '2') {
                    if ($layouts_safe === '2') echo '<div class="swiper-container"><div class="swiper-wrapper">';
                    foreach ($settings['btccgs_timeline'] as $index => $timeline) { ?>
                        <div class="meafe-timeline-inner-wrap <?php if ($layouts_safe === '2') echo 'swiper-slide'; ?>">
                            <?php if ($layouts_safe != '3') { ?>
                                <div class="meafe-timeline-image-wrap">
                                    <?php if (($timeline['btccgs_timeline_image']['id'] || $timeline['btccgs_timeline_image']['url'])) { ?>
                                        <div class="meafe-timeline-reviewer-thumb">
                                            <?php echo Group_Control_Image_Size::get_attachment_image_html($timeline, 'full', 'btccgs_timeline_image'); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <div class="meafe-timeline-icon">
                                <div class="meafe-timeline-icon-inner">
                                    <?php Icons_Manager::render_icon($timeline['btcgs_timeline_title_icon_new']); ?>
                                </div>
                            </div>
                            <div class="meafe-timeline-meta-wrap">
                                <?php if ($layouts_safe === '1') {
                                    if ($timeline['btccgs_timeline_date']) : ?>
                                        <div <?php $this->print_render_attribute_string('btccgs_timeline_date'); ?>>
                                            <<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_date_tag'] ); ?>>
                                                <?php echo esc_html($timeline['btccgs_timeline_date']); ?>
                                            </<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_date_tag'] ); ?>>
                                        </div>
                                    <?php endif;

                                    if ($timeline['btccgs_timeline_title']) : ?>
                                        <div <?php $this->print_render_attribute_string('btccgs_timeline_title'); ?>>
                                            <<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_title_tag'] ); ?>>
                                                <?php echo esc_html($timeline['btccgs_timeline_title']); ?>
                                            </<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_title_tag'] ); ?>>
                                        </div>
                                    <?php endif;

                                    if ($timeline['btccgs_timeline_content']) : ?>
                                        <div <?php $this->print_render_attribute_string('btccgs_timeline_content'); ?>>
                                            <?php echo wp_kses_post($timeline['btccgs_timeline_content']); ?>
                                        </div>
                                    <?php endif;
                                } elseif ($layouts_safe === '2') {
                                    if ($timeline['btccgs_timeline_title']) : ?>
                                        <div <?php $this->print_render_attribute_string('btccgs_timeline_title'); ?>>
                                            <<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_title_tag'] ); ?>>
                                                <?php echo esc_html($timeline['btccgs_timeline_title']); ?>
                                            </<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_title_tag'] ); ?>>
                                        </div>
                                    <?php endif;

                                    if ($timeline['btccgs_timeline_content']) : ?>
                                        <div <?php $this->print_render_attribute_string('btccgs_timeline_content'); ?>>
                                            <?php echo wp_kses_post($timeline['btccgs_timeline_content']); ?>
                                        </div>
                                    <?php endif;

                                    if ($timeline['btccgs_timeline_date']) : ?>
                                        <div <?php $this->print_render_attribute_string('btccgs_timeline_date'); ?>>
                                            <<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_date_tag'] ); ?>>
                                                <?php echo esc_html($timeline['btccgs_timeline_date']); ?>
                                            </<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_date_tag'] ); ?>>
                                        </div>
                                <?php endif;
                                } ?>
                            </div>
                        </div>
                    <?php }
                    if ($layouts_safe === '2') echo '</div></div>';
                } else {
                    foreach ($settings['btccgs_timeline_two'] as $index => $timeline_two) { ?>
                        <div class="meafe-timeline-inner-wrap">
                            <?php if ($layouts_safe === '3') { ?>
                                <div class="meafe-timeline-date-wrap">
                                    <?php
                                    if ($timeline_two['btccgs_timeline_two_title_two']) : ?>
                                        <div class="meafe-timeline-date-title" <?php $this->print_render_attribute_string('btccgs_timeline_two_title_two'); ?>>
                                            <<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_title_tag'] ); ?>>
                                                <?php echo esc_html($timeline_two['btccgs_timeline_two_title_two']); ?>
                                            </<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_title_tag'] ); ?>>
                                        </div>
                                    <?php endif;

                                    if ($timeline_two['btccgs_timeline_two_date']) : ?>
                                        <div class="meafe-timeline-date" <?php $this->print_render_attribute_string('btccgs_timeline_two_date'); ?>>
                                            <<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_date_tag'] ); ?>>
                                                <?php echo esc_html($timeline_two['btccgs_timeline_two_date']); ?>
                                            </<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_date_tag'] ); ?>>
                                        </div>
                                    <?php endif;
                                    ?>
                                </div>
                            <?php } ?>
                            <div class="meafe-timeline-icon">
                                <div class="meafe-timeline-icon-inner"><?php Icons_Manager::render_icon($timeline_two['btcgs_timeline_two_title_icon_new']); ?></div>
                            </div>
                            <div class="meafe-timeline-meta-wrap">
                                <?php
                                if ($timeline_two['btccgs_timeline_two_title']) : ?>
                                    <div class="meafe-timeline-title" <?php $this->print_render_attribute_string('btccgs_timeline_two_title'); ?>>
                                        <<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_title_tag'] ); ?>>
                                            <?php echo esc_html($timeline_two['btccgs_timeline_two_title']); ?>
                                        </<?php Utils::print_validated_html_tag( $settings['btccgs_timeline_title_tag'] ); ?>>
                                    </div>
                                <?php endif;

                                if ($timeline_two['btccgs_timeline_two_content']) : ?>
                                    <div class="meafe-timeline-content" <?php $this->print_render_attribute_string('btccgs_timeline_two_content'); ?>>
                                        <?php echo wp_kses_post($timeline_two['btccgs_timeline_two_content']); ?>
                                    </div>
                                <?php endif;
                                ?>
                            </div>
                        </div>
                    <?php }
                }

                if ($settings['btccgs_timeline_layouts'] === '2' && $settings['btcsis_timeline_swiper_dots'] == 'yes') { ?>
                    <!-- If we need pagination -->
                    <div class="timeline meafa-swiper-pagination"></div>
                <?php }

                if ($settings['btccgs_timeline_layouts'] === '2' && $settings['btcsis_timeline_swiper_nav'] == 'yes') { ?>
                    <!-- If we need navigation buttons -->
                    <div class="meafa-navigation-wrap">
                        <div class="timeline meafa-navigation-prev nav">
                            <?php echo $nav_icons[0]; ?>
                        </div>
                        <div class="timeline meafa-navigation-next nav">
                            <?php echo $nav_icons[1]; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php 
    }

}