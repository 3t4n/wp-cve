<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Widget_Base;
class MEAFE_BTEN extends Widget_Base
{

    public function get_name() {
        return 'meafe-bten';
    }

    public function get_title() {
        return esc_html__( 'BlossomThemes Email Newsletter', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-newsletter';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-bten'];
    }

    public function is_bten_activated() {
        return class_exists( 'Blossomthemes_Email_Newsletter' );
    }

    public function get_bten_forms() {
        $forms = [ '' => esc_html__( 'None', 'mega-elements-addons-for-elementor' ) ];
        if ( $this->is_bten_activated() ) {
            $bten_forms = get_posts( [
                'post_type'      => 'subscribe-form',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ] );

            if ( ! empty( $bten_forms ) ) {
                $forms = wp_list_pluck( $bten_forms, 'post_title', 'ID' );
            }
        }
        return $forms;
    }

    protected function register_controls()
    {
        /**
         * BTEN General Settings
        */ 
        $this->start_controls_section(
            'meafe_bten_content_general_settings',
            [
                'label'     => $this->is_bten_activated() ? esc_html__( 'General Settings', 'mega-elements-addons-for-elementor' ) : esc_html__( 'Missing Notice', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        if ( ! $this->is_bten_activated() ) {
            $this->add_control(
                'bbcgs_bten_missing_notice',
                [
                    'type'  => Controls_Manager::RAW_HTML,
                    'raw'   => sprintf(
                        __( 'Hello, looks like %1$s is missing in your site. Please click on the link below and install/activate %1$s. Make sure to refresh this page after installation or activation.', 'mega-elements-addons-for-elementor' ),
                        '<a href="'.esc_url( admin_url( 'plugin-install.php?s=BlossomThemes+Email+Newsletter&tab=search&type=term' ) )
                        .'" target="_blank" rel="noopener">BlossomThemes Email Newsletter</a>'
                    ),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
                ]
            );

            $this->add_control(
                'bbcgs_bten_install',
                [
                    'type'  => Controls_Manager::RAW_HTML,
                    'raw'   => '<a href="' . esc_url( admin_url( 'plugin-install.php?s=BlossomThemes+Email+Newsletter&tab=search&type=term' ) ).'" target="_blank" rel="noopener">Click to install or activate BlossomThemes Email Newsletter</a>',
                ]
            );
            $this->end_controls_section();
            return;
        }

        $this->add_control(
            'bbcgs_bten_form_id',
            [
                'label'     => esc_html__( 'Select Your Form', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'label_block' => true,
                'options'   => $this->get_bten_forms(),
            ]
        );

        $this->add_control(
            'bbcgs_bten_layouts',
            [
                'label'         => esc_html__( 'Select Layout', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT,
                'default'       => '1',
                'label_block'   => false,
                'options'       => [
                    '1'       => esc_html__( 'Layout One', 'mega-elements-addons-for-elementor' ),
                    '2'       => esc_html__( 'Layout Two', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * BTEN Form Fields Label Style
        */
        $this->start_controls_section(
            'meafe_bten_style_general_style',
            [
                'label'     => esc_html__( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'bbsgs_bten_general_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bbsgs_bten_general_margin',
            [
                'label'     => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'bbsgs_bten_general_background',
                'label'     => esc_html__( 'Background', 'mega-elements-addons-for-elementor' ),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper',
                'exclude'   => [
                    'image',
                ],
            ]
        );

        $this->add_control(
            'bbsgs_bten_general_text_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper' => 'color: {{VALUE}} !important',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * BTEN Newsletter Label Style
        */
        $this->start_controls_section(
            'meafe_bten_style_newsletter_title_style',
            [
                'label'     => esc_html__( 'Form Label Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'bbsnts_bten_label_margin',
            [
                'label'     => esc_html__( 'Spacing Bottom', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper .text-holder' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'hr3',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bbsnts_bten_label_typography',
                'label'     => esc_html__( 'Title Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper .text-holder h3',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bbsnts_bten_desc_typography',
                'label'     => esc_html__( 'Description Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper .text-holder span',
            ]
        );

        $this->add_responsive_control(
            'bbsnts_bten_description_margin',
            [
                'label'     => esc_html__( 'Description Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper .text-holder span' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * BTEN GDPR Style
        */
        $this->start_controls_section(
            'meafe_bten_style_gdpr_style',
            [
                'label'     => esc_html__( 'GDPR Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bbsgdprs_bten_label_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form .subscribe-inner-wrap',
            ]
        );

        $this->add_responsive_control(
            'bbsgdprs_bten_gdpr_margin',
            [
                'label'     => esc_html__( 'Top Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form > label' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * BTEN Form Fields Style
        */ 
        $this->start_controls_section(
            'meafe_bten_style_form_field_style',
            [
                'label'     => esc_html__( 'Form Fields Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'bbsffs_bten_field_width',
            [
                'label'     => esc_html__( 'Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="text"]' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bbsffs_bten_field_height',
            [
                'label'     => esc_html__( 'Height', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="text"]' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bbsffs_bten_field_margin',
            [
                'label'     => esc_html__( 'Top Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="text"]' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bbsffs_bten_field_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="text"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bbsffs_bten_field_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="text"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'hr',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bbsffs_bten_field_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="text"]',
            ]
        );

        $this->add_control(
            'bbsffs_bten_field_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="text"]' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'bbsffs_bten_tabs_form_field' );

        $this->start_controls_tab(
            'bbsffs_bten_tab_form_field_normal',
            [
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'bbsffs_bten_field_border',
                'selector'  => '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="text"]',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'bbsffs_bten_field_box_shadow',
                'selector'  => '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="text"]',
            ]
        );

        $this->add_control(
            'bbsffs_bten_field_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="text"]' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'bbsffs_bten_tab_form_field_focus',
            [
                'label'     => esc_html__( 'Focus', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'bbsffs_bten_field_focus_border',
                'selector'  => '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="text"]:focus',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'bbsffs_bten_field_focus_box_shadow',
                'exclude'   => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="text"]:focus',
            ]
        );

        $this->add_control(
            'bbsffs_bten_field_focus_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="text"]:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * BTEN Submit Button Style
        */
        $this->start_controls_section(
            'meafe_bten_style_sumbit_button_style',
            [
                'label'     => esc_html__( 'Submit Button Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'bbssbs_bten_submit_margin',
            [
                'label'     => esc_html__( 'Top Spacing', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bbssbs_bten_submit_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bbssbs_bten_submit_width',
            [
                'label'     => esc_html__( 'Width', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units' => [ '%', 'px' ],
                'range' => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bbssbs_bten_submit_height',
            [
                'label'     => esc_html__( 'Height', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'unit' => 'px',
                ],
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bbssbs_bten_submit_typography',
                'selector'  => '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'bbssbs_bten_submit_border',
                'selector'  => '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]',
            ]
        );

        $this->add_control(
            'bbssbs_bten_submit_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'bbssbs_bten_submit_box_shadow',
                'selector'  => '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]',
            ]
        );

        $this->add_control(
            'hr4',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->start_controls_tabs( 'bbssbs_bten_tabs_button_style' );

        $this->start_controls_tab(
            'bbssbs_bten_tab_button_normal',
            [
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bbssbs_bten_submit_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'bbssbs_bten_submit_border_colors',
            [
                'label'     => esc_html__( 'Border Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'bbssbs_bten_submit_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'bbssbs_bten_tab_button_hover',
            [
                'label'     => esc_html__( 'Hover', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'submit_hover_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]:hover, {{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]:focus' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'bbssbs_bten_submit_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]:hover, {{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'bbssbs_bten_submit_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]:hover, {{WRAPPER}} .blossomthemes-email-newsletter-wrapper form input[type="submit"]:focus' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        if ( ! $this->is_bten_activated() ) {
            return;
        }

        $settings = $this->get_settings_for_display();

        if ( ! empty( $settings['bbcgs_bten_form_id'] ) ) {
            echo do_shortcode( '[BTEN id=' . $settings['bbcgs_bten_form_id'] . ' html_class=layout-' . esc_attr( $settings['bbcgs_bten_layouts'] ) . ']');
        }
    }

    protected function content_template() {
    }
}
