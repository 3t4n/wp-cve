<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

class MEAFE_Tabs extends Widget_Base
{
    public function get_name(){
        return 'meafe-tabs';
    }

    public function get_title(){
        return esc_html__('Tabs', 'mega-elements-addons-for-elementor');
    }

    public function get_icon() {
        return 'meafe-tab';
    }

    public function get_categories(){
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-tabs'];
    }

    public function get_script_depends() {
        return ['meafe-tabs'];
    }

    protected function register_controls()
    {
        /**
         * Advance Tabs Settings
         */
        $this->start_controls_section(
            'meafe_tabs_content_general_settings',
            [
                'label'     => esc_html__('General Settings', 'mega-elements-addons-for-elementor'),
            ]
        );
        $this->add_control(
            'btcgs_tabs_layout',
            [
                'label'     => esc_html__('Layout', 'mega-elements-addons-for-elementor'),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'label_block' => false,
                'options'   => [
                    '1'     => esc_html__('Layout 1', 'mega-elements-addons-for-elementor'),
                    '2'     => esc_html__('Layout 2', 'mega-elements-addons-for-elementor'),
                    '3'     => esc_html__('Layout 3', 'mega-elements-addons-for-elementor'),
                ],
            ]
        );
        $this->add_control(
            'btcgs_tabs_icon_show',
            [
                'label'         => esc_html__('Enable Icon', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes',
                'return_value'  => 'yes',
            ]
        );
        $this->add_control(
            'btcgs_tabs_icon_position',
            [
                'label'         => esc_html__('Icon Position', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'default'       => 'place-left',
                'label_block'   => false,
                'options'       => [
                    'place-left'    => esc_html__('Left', 'mega-elements-addons-for-elementor'),
                    'place-right'   => esc_html__('Right', 'mega-elements-addons-for-elementor'),
                    'place-top'     => esc_html__('Top', 'mega-elements-addons-for-elementor'),
                    'place-bottom'  => esc_html__('Bottom', 'mega-elements-addons-for-elementor'),
                ],
                'condition'     => [
                    'btcgs_tabs_icon_show'  => 'yes',
                ],
            ]
        );

        $tabs_repeater = new Repeater();

        $tabs_repeater->add_control(
            'btcgs_tabs_icon_type',
            [
                'label'         => esc_html__('Icon Type', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::CHOOSE,
                'label_block'   => false,
                'options'       => [
                    'none'      => [
                        'title'     => esc_html__('None', 'mega-elements-addons-for-elementor'),
                        'icon'      => 'fa fa-ban',
                    ],
                    'icon'      => [
                        'title'     => esc_html__('Icon', 'mega-elements-addons-for-elementor'),
                        'icon'      => 'fa fa-gear',
                    ],
                ],
                'default' => 'icon',
            ]
        );

        $tabs_repeater->add_control(
            'btcgs_tabs_title_icon_new',
            [
                'label'         => esc_html__('Icon', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::ICONS,
                'fa4compatibility' => 'btcgs_tabs_title_icon',
                'default'       => [
                    'value'         => 'fas fa-home',
                    'library'       => 'fa-solid',
                ],
                'condition'     => [
                    'btcgs_tabs_icon_type' => 'icon',
                ],
            ]
        );

        $tabs_repeater->add_control(
            'btcgs_tabs_title_image',
            [
                'label'         => esc_html__('Image', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::MEDIA,
                'default'       => [
                    'url'           => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $tabs_repeater->add_control(
            'btcgs_tabs_title',
            [
                'name'          => 'btcgs_tabs_title',
                'label'         => esc_html__('Tab Title', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::TEXT,
                'default'       => esc_html__('Tab Title', 'mega-elements-addons-for-elementor'),
                'dynamic'       => ['active' => true],
            ]
        );

        $tabs_repeater->add_control(
            'btcgs_tabs_tab_content',
            [
                'label'         => esc_html__('Tab Content', 'mega-elements-addons-for-elementor'),
                'type'          => Controls_Manager::WYSIWYG,
                'default'       => esc_html__('Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'mega-elements-addons-for-elementor'),
                'dynamic'       => ['active' => true],
            ]
        );

        $this->add_control(
            'btcgs_tabs_tab',
            [
                'type'          => Controls_Manager::REPEATER,
                'seperator'     => 'before',
                'default'       => [
                    ['btcgs_tabs_title' => esc_html__('Tab Title 1', 'mega-elements-addons-for-elementor')],
                    ['btcgs_tabs_title' => esc_html__('Tab Title 2', 'mega-elements-addons-for-elementor')],
                    ['btcgs_tabs_title' => esc_html__('Tab Title 3', 'mega-elements-addons-for-elementor')],
                ],
                'fields'        => $tabs_repeater->get_controls(),
                'title_field'   => '{{btcgs_tabs_title}}',
            ]
        );
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style Advance Tabs General Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'meafe_tabs_style_general_settings',
            [
                'label' => esc_html__('General', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'btsgs_tabs_padding',
            [
                'label' => esc_html__('Padding', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btsgs_tabs_margin',
            [
                'label' => esc_html__('Margin', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btsgs_tabs_border',
                'label' => esc_html__('Border', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper',
            ]
        );

        $this->add_responsive_control(
            'btsgs_tabs_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btsgs_tabs_box_shadow',
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper',
            ]
        );
        $this->end_controls_section();
        /**
         * -------------------------------------------
         * Tab Style Advance Tabs Content Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'meafe_tabs_style_title_settings',
            [
                'label' => esc_html__('Tab Title', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btsts_tabs_title_typography',
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button',
            ]
        );
        
        $this->add_responsive_control(
            'btsts_tabs_icon_size',
            [
                'label' => __('Icon Size', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button svg' => 'font-size: {{SIZE}}{{UNIT}};',

                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button i' => 'font-size: {{SIZE}}{{UNIT}};', 

                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'btsts_tabs_icon_margin',
            [
                'label' => esc_html__('Icon Margin', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button svg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        ); 

        $this->add_responsive_control(
            'btsts_tabs_padding',
            [
                'label' => esc_html__('Padding', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btsts_tabs_margin',
            [
                'label' => esc_html__('Margin', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('eael_adv_tabs_header_tabs');
        // Normal State Tab
        $this->start_controls_tab('btsts_tabs_header_normal', ['label' => esc_html__('Normal', 'mega-elements-addons-for-elementor')]);
        $this->add_control(
            'btsts_tabs_color',
            [
                'label' => esc_html__('Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f1f1f1',
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btsts_tabs_bgtype',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button',
            ]
        );
        $this->add_control(
            'btsts_tabs_text_color',
            [
                'label' => esc_html__('Text Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btsts_tabs_icon_color',
            [
                'label' => esc_html__('Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button svg path' => 'fill: {{VALUE}};',
                     
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button i' => 'color: {{VALUE}};',
                    
                ],
                'condition' => [
                    'btcgs_tabs_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btsts_tabs_border',
                'label' => esc_html__('Border', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button',
            ]
        );
        $this->add_responsive_control(
            'btsts_tabs_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        // Hover State Tab
        $this->start_controls_tab('btsts_tabs_header_hover', ['label' => esc_html__('Hover', 'mega-elements-addons-for-elementor')]);
        $this->add_control(
            'btsts_tabs_color_hover',
            [
                'label' => esc_html__('Tab Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btsts_tabs_bgtype_hover',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button:hover',
            ]
        );
        $this->add_control(
            'btsts_tabs_text_color_hover',
            [
                'label' => esc_html__('Text Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btsts_tabs_icon_color_hover',
            [
                'label' => esc_html__('Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button:hover > i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'btcgs_tabs_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btsts_tabs_border_hover',
                'label' => esc_html__('Border', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button:hover',
            ]
        );
        $this->add_responsive_control(
            'btsts_tabs_border_radius_hover',
            [
                'label' => esc_html__('Border Radius', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        // Active State Tab
        $this->start_controls_tab('btsts_tabs_header_active', ['label' => esc_html__('Active', 'mega-elements-addons-for-elementor')]);
        $this->add_control(
            'btsts_tabs_color_active',
            [
                'label' => esc_html__('Tab Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#444',
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btsts_tabs_bgtype_active',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button.active',
            ]
        );
        $this->add_control(
            'btsts_tabs_text_color_active',
            [
                'label' => esc_html__('Text Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button.active' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'btsts_tabs_icon_color_active',
            [
                'label' => esc_html__('Icon Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button.active > i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'btcgs_tabs_icon_show' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btsts_tabs_border_active',
                'label' => esc_html__('Border', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button.active',
            ]
        );
        $this->add_responsive_control(
            'btsts_tabs_border_radius_active',
            [
                'label' => esc_html__('Border Radius', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe-tabs-button-wrapper .tab-button.active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        /**
         * -------------------------------------------
         * Tab Style Advance Tabs Content Style
         * -------------------------------------------
         */
        $this->start_controls_section(
            'meafe_tabs_style_content_settings',
            [
                'label' => esc_html__('Content', 'mega-elements-addons-for-elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'btscs_tabs_content_wrapper',
            array(
                'label'     => __( 'Content Wrapper', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'btscs_tabs_content_wrapper_padding',
            [
                'label' => esc_html__('Padding', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .nacc--wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btscs_tabs_content_wrapper_border',
                'label' => esc_html__('Border', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper .nacc--wrapper',
            ]
        );

        $this->add_responsive_control(
            'btscs_tabs_content_wrapper_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .nacc--wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'btscs_tabs_content_only',
            array(
                'label'     => __( 'Content Only', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'btscs_tabs_content_bg_color',
            [
                'label' => esc_html__('Background Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe--content--wrapper' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btscs_tabs_content_bgtype',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper .meafe--content--wrapper',
            ]
        );
        $this->add_control(
            'btscs_tabs_content_text_color',
            [
                'label' => esc_html__('Text Color', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe--content--wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btscs_tabs_content_typography',
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper .meafe--content--wrapper',
            ]
        );
        $this->add_responsive_control(
            'btscs_tabs_content_padding',
            [
                'label' => esc_html__('Padding', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe--content--wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'btscs_tabs_content_margin',
            [
                'label' => esc_html__('Margin', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--tabs--wrapper .meafe--content--wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btscs_tabs_content_border',
                'label' => esc_html__('Border', 'mega-elements-addons-for-elementor'),
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper .meafe--content--wrapper',
            ]
        );

        $this->add_responsive_control(
            'btscs_tabs_content_border_radius',
            [
                'label' => esc_html__('Border Radius', 'mega-elements-addons-for-elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .meafe--content--wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'btscs_tabs_content_shadow',
                'selector' => '{{WRAPPER}} .meafe--tabs--wrapper .meafe--content--wrapper',
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $tab_title_index = 0;
        $tab_content_index = 0;
        $tab_icon_migrated = isset($settings['__fa4_migrated']['btcgs_tabs_title_icon_new']);
        $tab_icon_is_new = empty($settings['btcgs_tabs_title_icon']);

        $this->add_render_attribute(
            'meafe_tab_wrapper',
            [
                'id' => "meafe-tabs-{$this->get_id()}",
                'class' => ['meafe--tabs--wrapper', 'layout-' . esc_attr($settings['btcgs_tabs_layout'])],
                'data-tabid' => $this->get_id(),
            ]
        );

        $this->add_render_attribute('meafe_tab_icon_position', 'class', [ 'menu button-group', esc_attr( $settings['btcgs_tabs_icon_position'] ) ] ); ?>
        <div <?php echo $this->get_render_attribute_string('meafe_tab_wrapper'); ?>>
            <div class="meafe-tabs-button-wrapper">
                <div <?php echo $this->get_render_attribute_string('meafe_tab_icon_position'); ?>>
                    <?php foreach ( $settings['btcgs_tabs_tab'] as $tab ) : ?>
                        <div class="tab-button<?php if( $tab_title_index == 0 ) echo ' active'; ?>">
                            <?php if ( $settings['btcgs_tabs_icon_show'] === 'yes' ) :
                                if ( $tab['btcgs_tabs_icon_type'] === 'icon' ) : ?>
                                    <?php if ( $tab_icon_is_new || $tab_icon_migrated ) {
                                        if ( isset( $tab['btcgs_tabs_title_icon_new']['value']['url'] ) ) {
                                            echo '<img src="' . esc_url( $tab['btcgs_tabs_title_icon_new']['value']['url']) . '"/>';
                                        } else {
                                            echo '<i class="' . esc_attr($tab['btcgs_tabs_title_icon_new']['value']) . '"></i>';
                                        }
                                    } else {
                                        echo '<i class="' . esc_attr($tab['btcgs_tabs_title_icon']) . '"></i>';
                                    } ?>
                                <?php endif; ?>
                            <?php endif; ?> <span><?php echo esc_html($tab['btcgs_tabs_title']); ?></span>
                        </div>
                        <?php 
                        $tab_title_index++;
                    endforeach; ?>
                </div>
            </div>
            <div class="meafe-content--wrapper">
                <div class="nacc">
                    <?php foreach ( $settings['btcgs_tabs_tab'] as $tab ) : ?>
                        <div class="nacc--wrapper<?php if( $tab_content_index == 0 ) echo ' active'; ?>">
                            <div class="meafe-tabs-content">
                                <?php if ( $tab['btcgs_tabs_tab_content'] ) : ?>
                                <div class="meafe--content--wrapper">
                                    <?php echo wp_kses_post( wpautop( $tab['btcgs_tabs_tab_content'] ) ); ?>
                                </div>
                                <?php endif; ?>
                                <?php if ( $tab['btcgs_tabs_title_image']['url'] || $tab['btcgs_tabs_title_image']['id'] ) : ?>
                                    <div class="image-wrapper">
                                        <img src="<?php echo esc_attr( $tab['btcgs_tabs_title_image']['url'] ); ?>" alt="<?php echo esc_attr( get_post_meta( $tab['btcgs_tabs_title_image']['id'], '_wp_attachment_image_alt', true ) ); ?>">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php 
                        $tab_content_index++;
                    endforeach; ?>
                </div>
            </div>
        </div>
        <?php 
    }

    protected function content_template() {
    }
}