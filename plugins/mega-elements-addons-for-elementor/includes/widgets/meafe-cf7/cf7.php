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
use Elementor\Widget_Base;

class MEAFE_CF7 extends Widget_Base
{

    public function get_name() {
        return 'meafe-cf7';
    }

    public function get_title() {
        return esc_html__( 'Contact Form 7', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-contact';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-cf7'];
    }

    public function is_cf7_activated() {
        return class_exists( 'WPCF7' );
    }

    public function get_cf7_forms() {
        $forms = [ '' => esc_html__( 'None', 'mega-elements-addons-for-elementor' ) ];
        if ( $this->is_cf7_activated() ) {
            $cf7_forms = get_posts( [
                'post_type'      => 'wpcf7_contact_form',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ] );

            if ( ! empty( $cf7_forms ) ) {
                $forms = wp_list_pluck( $cf7_forms, 'post_title', 'ID' );
            }
        }
        return $forms;
    }

    public function do_shortcode( $tag, array $atts = array(), $content = null ) {
        global $shortcode_tags;
        if ( ! isset( $shortcode_tags[ $tag ] ) ) {
            return false;
        }
        return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
    }

    public function sanitize_html_class_param( $class ) {
        $classes = ! empty( $class ) ? explode( ' ', $class ) : [];
        $sanitized = [];
        if ( ! empty( $classes ) ) {
            $sanitized = array_map( function( $cls ) {
                return sanitize_html_class( $cls );
            }, $classes );
        }
        return implode( ' ', $sanitized );
    }

    protected function register_controls()
    {
        /**
         * Contact Form 7 General Settings
        */ 
        $this->start_controls_section(
            'meafe_cf7_content_general_settings',
            [
                'label'     => $this->is_cf7_activated() ? esc_html__( 'General Settings', 'mega-elements-addons-for-elementor' ) : esc_html__( 'Missing Notice', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_CONTENT,
            ]
        );

        if ( ! $this->is_cf7_activated() ) {
            $this->add_control(
                'bccgs_cf7_missing_notice',
                [
                    'type'  => Controls_Manager::RAW_HTML,
                    'raw'   => sprintf(
                        __( 'Hello, looks like %1$s is missing in your site. Please click on the link below and install/activate %1$s. Make sure to refresh this page after installation or activation.', 'mega-elements-addons-for-elementor' ),
                        '<a href="'.esc_url( admin_url( 'plugin-install.php?s=Contact+Form+7&tab=search&type=term' ) )
                        .'" target="_blank" rel="noopener">Contact Form 7</a>'
                    ),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
                ]
            );

            $this->add_control(
                'bccgs_cf7_install',
                [
                    'type'  => Controls_Manager::RAW_HTML,
                    'raw'   => '<a href="' . esc_url( admin_url( 'plugin-install.php?s=Contact+Form+7&tab=search&type=term' ) ).'" target="_blank" rel="noopener">Click to install or activate Contact Form 7</a>',
                ]
            );
            $this->end_controls_section();
            return;
        }

        $this->add_control(
            'bccgs_cf7_form_id',
            [
                'label'     => esc_html__( 'Select Your Form', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'label_block' => true,
                'options'   => $this->get_cf7_forms(),
            ]
        );

        $this->add_control(
            'bccgs_cf7_html_class',
            [
                'label'         => esc_html__( 'HTML Class', 'mega-elements-addons-for-elementor' ),
                'type'          => Controls_Manager::TEXT,
                'label_block'   => true,
                'description'   => esc_html__( 'Add "break-column" custom class to break the input fields.', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->end_controls_section();

        /**
         * Contact Form 7 Form Fields Style
        */ 
        $this->start_controls_section(
            'meafe_cf7_style_form_field_style',
            [
                'label'     => esc_html__( 'Form Fields Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'bcsffs_cf7_field_width',
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
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit)' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bcsffs_cf7_field_margin',
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
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bcsffs_cf7_field_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bcsffs_cf7_field_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
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
                'name'      => 'bcsffs_cf7_field_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit)',
            ]
        );

        $this->add_control(
            'bcsffs_cf7_field_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'bcsffs_cf7_field_placeholder_color',
            [
                'label'     => esc_html__( 'Placeholder Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} ::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ::-moz-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ::-ms-input-placeholder' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'bcsffs_cf7_tabs_form_field' );

        $this->start_controls_tab(
            'bcsffs_cf7_tab_form_field_normal',
            [
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'bcsffs_cf7_field_border',
                'selector'  => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit)',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'bcsffs_cf7_field_box_shadow',
                'selector'  => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit)',
            ]
        );

        $this->add_control(
            'bcsffs_cf7_field_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit)' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'bcsffs_cf7_tab_form_field_focus',
            [
                'label'     => esc_html__( 'Focus', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'bcsffs_cf7_field_focus_border',
                'selector'  => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):focus',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'bcsffs_cf7_field_focus_box_shadow',
                'exclude'   => [
                    'box_shadow_position',
                ],
                'selector' => '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):focus',
            ]
        );

        $this->add_control(
            'bcsffs_cf7_field_focus_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit):focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Contact Form 7 Form Fields Label Style
        */
        $this->start_controls_section(
            'meafe_cf7_style_form_field_label_style',
            [
                'label'     => esc_html__( 'Form Fields Label Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'bcsffls_cf7_label_margin',
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
                    '{{WRAPPER}} .wpcf7-form-control:not(.wpcf7-submit)' => 'margin-top: {{SIZE}}{{UNIT}}',
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
                'name'      => 'bcsffls_cf7_label_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} label',
            ]
        );

        $this->add_control(
            'bcsffls_cf7_label_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Contact Form 7 Submit Button Style
        */
        $this->start_controls_section(
            'meafe_cf7_style_sumbit_button_style',
            [
                'label'     => esc_html__( 'Submit Button Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'bcssbs_cf7_submit_margin',
            [
                'label'     => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bcssbs_cf7_submit_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bcssbs_cf7_submit_typography',
                'selector'  => '{{WRAPPER}} .wpcf7-submit',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'bcssbs_cf7_submit_border',
                'selector'  => '{{WRAPPER}} .wpcf7-submit',
            ]
        );

        $this->add_control(
            'bcssbs_cf7_submit_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'bcssbs_cf7_submit_box_shadow',
                'selector'  => '{{WRAPPER}} .wpcf7-submit',
            ]
        );

        $this->add_control(
            'hr4',
            [
                'type' => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ]
        );

        $this->start_controls_tabs( 'bcssbs_cf7_tabs_button_style' );

        $this->start_controls_tab(
            'bcssbs_cf7_tab_button_normal',
            [
                'label'     => esc_html__( 'Normal', 'mega-elements-addons-for-elementor' ),
            ]
        );

        $this->add_control(
            'bcssbs_cf7_submit_color',
            [
                'label'     => esc_html__( 'Text Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'bcssbs_cf7_submit_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'bcssbs_cf7_tab_button_hover',
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
                    '{{WRAPPER}} .wpcf7-submit:hover, {{WRAPPER}} .wpcf7-submit:focus' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'bcssbs_cf7_submit_hover_bg_color',
            [
                'label'     => esc_html__( 'Background Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit:hover, {{WRAPPER}} .wpcf7-submit:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'bcssbs_cf7_submit_hover_border_color',
            [
                'label' => esc_html__( 'Border Color', 'mega-elements-addons-for-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpcf7-submit:hover, {{WRAPPER}} .wpcf7-submit:focus' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render() {
        if ( ! $this->is_cf7_activated() ) {
            return;
        }

        $settings = $this->get_settings_for_display();

        if ( ! empty( $settings['bccgs_cf7_form_id'] ) ) {
            echo $this->do_shortcode( 'contact-form-7', [
                'id' => $settings['bccgs_cf7_form_id'],
                'html_class' => 'meafe-cf7-form ' . $this->sanitize_html_class_param( $settings['bccgs_cf7_html_class'] ),
            ] );
        }
    }

    protected function content_template() {
    }
}
