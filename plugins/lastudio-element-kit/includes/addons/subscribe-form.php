<?php

/**
 * Class: LaStudioKit_Subscribe_Form
 * Name: Subscribe Form
 * Slug: lakit-subscribe-form
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}

/**
 * Subscribe_Form Widget
 */
class LaStudioKit_Subscribe_Form extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if ( ! lastudio_kit_settings()->is_combine_js_css() ) {
		    wp_register_script( $this->get_name(), lastudio_kit()->plugin_url( 'assets/js/addons/subscribe-form.js' ), [], lastudio_kit()->get_version(), true );
            $this->add_script_depends($this->get_name());
		    if ( ! lastudio_kit()->is_optimized_css_mode() ) {
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/subscribe-form.css' ), [], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_widget_css_config( $widget_name ) {
		$file_url  = lastudio_kit()->plugin_url( 'assets/css/addons/subscribe-form.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/subscribe-form.min.css' );

		return [
			'key'       => $widget_name,
			'version'   => lastudio_kit()->get_version( true ),
			'file_path' => $file_path,
			'data'      => [
				'file_url' => $file_url
			]
		];
	}

    public function get_name() {
        return 'lakit-subscribe-form';
    }

    protected function get_widget_title() {
        return esc_html__( 'Subscribe', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_keywords() {
        return [ 'subscribe', 'newsletter', 'form' ];
    }

    protected function register_controls() {

        $css_scheme = apply_filters(
            'lastudio-kit/subscribe-form/css-schema',
            array(
                'instance'    => '.lakit-subscribe-form',
                'input'       => '.lakit-subscribe-form__input',
                'submit'      => '.lakit-subscribe-form__submit',
                'submit_icon' => '.lakit-subscribe-form__submit-icon',
                'message'     => '.lakit-subscribe-form__message',
            )
        );

        $this->start_controls_section(
            'section_subscribe_fields',
            array(
                'label' => esc_html__( 'Fields', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'submit_button_text',
            array(
                'label'       => esc_html__( 'Submit Text', 'lastudio-kit' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter Submit Button Text', 'lastudio-kit' ),
                'default'     => esc_html__( 'Subscribe', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'submit_placeholder',
            array(
                'label'       => esc_html__( 'Input Placeholder', 'lastudio-kit' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__( 'Enter your email address...', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'use_additional_fields',
            array(
                'label'        => esc_html__( 'Use Additional Fields', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'false',
                'separator'    => 'before',
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'type',
            [
                'label'   => esc_html__( 'Field Type', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'fname',
                'options' => [
                    'fname'   => esc_html__( 'First Name', 'lastudio-kit' ),
                    'lname'   => esc_html__( 'Last Name', 'lastudio-kit' ),
                    'address' => esc_html__( 'Address', 'lastudio-kit' ),
                    'phone'   => esc_html__( 'Phone Number', 'lastudio-kit' ),
                ]
            ]
        );

        $repeater->add_control(
            'placeholder',
            array(
                'label'   => esc_html__( 'Field Placeholder', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Enter Value', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'additional_fields',
            [
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [
                    [
                        'type'        => 'fname',
                        'placeholder' => esc_html__( 'First Name', 'lastudio-kit' ),
                    ],
                    [
                        'type'        => 'lname',
                        'placeholder' => esc_html__( 'Last Name', 'lastudio-kit' ),
                    ],
                    [
                        'type'        => 'address',
                        'placeholder' => esc_html__( 'Address', 'lastudio-kit' ),
                    ],
                    [
                        'type'        => 'phone',
                        'placeholder' => esc_html__( 'Phone Number', 'lastudio-kit' ),
                    ],
                ],
                'title_field' => '{{{ placeholder }}}',
                'condition' => [
                    'use_additional_fields' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_subscribe_settings',
            array(
                'label' => esc_html__( 'Settings', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'use_redirect_url',
            array(
                'label'        => esc_html__( 'Use Redirect Url?', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'false',
            )
        );

        $this->add_control(
            'redirect_url',
            array(
                'label'       => esc_html__( 'Redirect Url', 'lastudio-kit' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Enter Redirect Url', 'lastudio-kit' ),
                'default'     => '#',
                'condition'   => array(
                    'use_redirect_url' => 'yes',
                ),
            )
        );

        $this->add_control(
            'use_target_list_id',
            array(
                'label'        => esc_html__( 'Use Target List Id?', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'no',
            )
        );

        $this->add_control(
            'target_list_id',
            array(
                'label'     => esc_html__( 'Mailchimp list id', 'lastudio-kit' ),
                'type'      => Controls_Manager::TEXT,
                'condition' => [
                    'use_target_list_id' => 'yes',
                ]
            )
        );

        $this->end_controls_section();

        /**
         * General Style Section
         */
        $this->start_controls_section(
            'section_general_style',
            array(
                'label'      => esc_html__( 'General', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'layout',
            array(
                'label'   => esc_html__( 'Layout', 'lastudio-kit' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => array(
                    'inline' => esc_html__( 'Inline', 'lastudio-kit' ),
                    'block'  => esc_html__( 'Block', 'lastudio-kit' ),
                )
            )
        );

        $this->add_responsive_control(
            'container_width',
            array(
                'label'      => esc_html__( 'Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['instance'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'container_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['instance'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'container_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['instance'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'container_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['instance'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_container_style' );

        $this->start_controls_tab(
            'tab_container',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'container_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['instance'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'container_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'],
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'container_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['instance'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_container_error',
            array(
                'label' => esc_html__( 'Error', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'container_error_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['instance'] . '.lakit-subscribe-form--response-error' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'container_error_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['instance'] . '.lakit-subscribe-form--response-error',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'container_error_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['instance'] . '.lakit-subscribe-form--response-error',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Input Style Section
         */
        $this->start_controls_section(
            'section_input_style',
            array(
                'label'      => esc_html__( 'Input', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_responsive_control(
            'input_width',
            array(
                'label'      => esc_html__( 'Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 100,
                        'max' => 1000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['input'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'input_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-arrow-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-arrow-right',
                    ),
                ),
                'condition' => array(
                    'layout' => 'block',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['input']  => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'input_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['input'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'input_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['input'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'input_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['input'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_input_style' );

        $this->start_controls_tab(
            'tab_input',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'input_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['input'] => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'input_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['input'] => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['input'] . '::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['input'] . '::-moz-input-placeholder' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['input'],
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'input_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['input'],
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'input_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['input'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_input_focus',
            array(
                'label' => esc_html__( 'Focus', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'input_focus_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['input'] . ':focus' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'input_focus_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['input'] . ':focus' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['input'] . ':focus::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['input'] . ':focus::-moz-input-placeholder' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'input_focus_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['input'] . ':focus',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'input_focus_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['input'] . ':focus',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'input_focus_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['input'] . ':focus',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_input_error',
            array(
                'label' => esc_html__( 'Error', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'input_error_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['input'] . '.mail-invalid' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'input_error_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['input'] . '.mail-invalid' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['input'] . '.mail-invalid::-webkit-input-placeholder' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['input'] . '.mail-invalid::-moz-input-placeholder' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'input_error_typography',
                'selector' => '{{WRAPPER}} ' . $css_scheme['input'] . '.mail-invalid',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'input_error_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['input'] . '.mail-invalid',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'input_error_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['input'] . '.mail-invalid',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Submit Button Style Section
         */
        $this->start_controls_section(
            'section_submit_button_style',
            array(
                'label'      => esc_html__( 'Submit Button', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'add_button_icon',
            array(
                'label'        => esc_html__( 'Add Icon', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'false',
                'render_type'  => 'template',
            )
        );

	    $this->add_control(
		    'button_icon',
		    [
			    'label' => __( 'Icon', 'lastudio-kit' ),
			    'type' => Controls_Manager::ICONS,
			    'fa4compatibility' => 'icon',
			    'default' => [
				    'value' => 'fas fa-star',
				    'library' => 'fa-solid',
			    ],
			    'condition' => [
				    'add_button_icon' => 'yes'
			    ]
		    ]
	    );

        $this->add_responsive_control(
            'button_icon_size',
            array(
                'label' => esc_html__( 'Icon Size', 'lastudio-kit' ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 7,
                        'max' => 90,
                    ),
                ),
                'condition' => array(
                    'add_button_icon' => 'yes',
                ),
                'selectors' => array(
	                '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};'
                ),
            )
        );

        $this->add_control(
            'button_icon_color',
            array(
                'label'     => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'condition' => array(
                    'add_button_icon' => 'yes',
                ),
                'selectors' => array(
	                '{{WRAPPER}} .elementor-icon' => 'color: {{VALUE}};',
	                '{{WRAPPER}} .elementor-icon svg' => 'fill: {{VALUE}};'
                ),
            )
        );

        $this->add_responsive_control(
            'button_icon_margin',
            array(
                'label'      => esc_html__( 'Icon Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'condition' => array(
                    'add_button_icon' => 'yes',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .elementor-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_width',
            array(
                'label'      => esc_html__( 'Width', 'lastudio-kit' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array(
                    'px', 'em', '%',
                ),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 1000,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['submit'] => 'width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-arrow-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-arrow-right',
                    ),
                ),
                'condition' => array(
                    'layout' => 'block',
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['submit']  => 'align-self: {{VALUE}};',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'button_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['submit'],
                'fields_options' => array(
                    'background' => array(
                        'default' => 'classic',
                    ),
                    'color' => array(
                        'label'  => _x( 'Background Color', 'Background Control', 'lastudio-kit' )
                    ),
                    'color_b' => array(
                        'label' => _x( 'Second Background Color', 'Background Control', 'lastudio-kit' ),
                    ),
                ),
                'exclude' => array(
                    'image',
                    'position',
                    'attachment',
                    'attachment_alert',
                    'repeat',
                    'size',
                ),
            )
        );

        $this->add_control(
            'button_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['submit'] => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['submit'],
            )
        );

        $this->add_responsive_control(
            'button_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['submit'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['submit'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['submit'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['submit'],
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['submit'],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'button_hover_bg',
                'selector' => '{{WRAPPER}} ' . $css_scheme['submit'] . ':hover',
                'fields_options' => array(
                    'background' => array(
                        'default' => 'classic',
                    ),
                    'color' => array(
                        'label' => _x( 'Background Color', 'Background Control', 'lastudio-kit' ),
                    ),
                    'color_b' => array(
                        'label' => _x( 'Second Background Color', 'Background Control', 'lastudio-kit' ),
                    ),
                ),
                'exclude' => array(
                    'image',
                    'position',
                    'attachment',
                    'attachment_alert',
                    'repeat',
                    'size',
                ),
            )
        );

        $this->add_control(
            'button_hover_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['submit'] . ':hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'button_icon_hover_color',
            array(
                'label'     => esc_html__( 'Icon Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} ' . $css_scheme['submit'] . ':hover .elementor-icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}} ' . $css_scheme['submit'] .':hover .elementor-icon svg' => 'fill: {{VALUE}};'
                ),
                'condition' => array(
                    'add_button_icon' => 'yes',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'button_hover_typography',
                'selector' => '{{WRAPPER}}  ' . $css_scheme['submit'] . ':hover',
            )
        );

        $this->add_responsive_control(
            'button_hover_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['submit'] . ':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_hover_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['submit'] . ':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'button_hover_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['submit'] . ':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'button_hover_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} ' . $css_scheme['submit'] . ':hover',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'button_hover_box_shadow',
                'selector' => '{{WRAPPER}} ' . $css_scheme['submit'] . ':hover',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Message Style Section
         */
        $this->start_controls_section(
            'section_message_style',
            array(
                'label'      => esc_html__( 'Message', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->add_control(
            'message_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => array(
                    'flex-start' => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-arrow-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'flex-end' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-arrow-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} ' . $css_scheme['message'] . ' .lakit-subscribe-form__message-inner' => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->start_controls_tabs( 'tabs_message_style' );

        $this->start_controls_tab(
            'tab_message_success',
            array(
                'label' => esc_html__( 'Success', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'message_success_bg_color',
            array(
                'label' => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-subscribe-form--response-success ' . $css_scheme['message'] . ' span' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'message_success_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-subscribe-form--response-success ' . $css_scheme['message'] . ' span' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'message_success_typography',
                'selector' => '{{WRAPPER}} .lakit-subscribe-form--response-success ' . $css_scheme['message'] . ' span',
            )
        );

        $this->add_responsive_control(
            'message_success_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-subscribe-form--response-success ' . $css_scheme['message'] . ' span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'message_success_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-subscribe-form--response-success ' . $css_scheme['message'] . ' span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'message_success_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-subscribe-form--response-success ' . $css_scheme['message'] . ' span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'message_success_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .lakit-subscribe-form--response-success ' . $css_scheme['message'] . ' span',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'message_success_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-subscribe-form--response-success ' . $css_scheme['message'] . ' span',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_message_error',
            array(
                'label' => esc_html__( 'Error', 'lastudio-kit' ),
            )
        );

        $this->add_control(
            'message_error_bg_color',
            array(
                'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-subscribe-form--response-error ' . $css_scheme['message'] . ' span' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'message_error_color',
            array(
                'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-subscribe-form--response-error ' . $css_scheme['message'] . ' span' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'message_error_typography',
                'selector' => '{{WRAPPER}} .lakit-subscribe-form--response-error ' . $css_scheme['message'] . ' span',
            )
        );

        $this->add_responsive_control(
            'message_error_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-subscribe-form--response-error ' . $css_scheme['message'] . ' span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'message_error_margin',
            array(
                'label'      => __( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-subscribe-form--response-error ' . $css_scheme['message'] . ' span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'message_error_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-subscribe-form--response-error ' . $css_scheme['message'] . ' span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'        => 'message_error_border',
                'label'       => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .lakit-subscribe-form--response-error ' . $css_scheme['message'] . ' span',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'message_error_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-subscribe-form--response-error ' . $css_scheme['message'] . ' span',
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    /**
     * Generate setting json
     *
     * @return string
     */
    public function generate_setting_json() {
        $module_settings = $this->get_settings();

        $settings = array(
            'redirect'           => filter_var( $module_settings['use_redirect_url'], FILTER_VALIDATE_BOOLEAN ),
            'redirect_url'       => $module_settings['redirect_url'],
            'use_target_list_id' => filter_var( $module_settings['use_target_list_id'], FILTER_VALIDATE_BOOLEAN ),
            'target_list_id'     => $module_settings['target_list_id'],
        );

        $settings = json_encode( $settings );

        return htmlspecialchars( $settings );
    }

    /**
     * [generate_additional_fields description]
     * @return [type] [description]
     */
    public function generate_additional_fields() {
        $module_settings = $this->get_settings();

        $additional_filds = $module_settings['additional_fields'];

        if ( ! filter_var( $module_settings['use_additional_fields'], FILTER_VALIDATE_BOOLEAN ) || empty( $additional_filds ) ) {
            return false;
        }

        $default_fields_data = [
            'fname' => [
                'class'       => [
                    'lakit-subscribe-form__input lakit-subscribe-form__fname-field',
                ],
                'type'        => 'text',
                'name'        => 'fname',
                'placeholder' => esc_html__( 'First Name', 'lastudio-kit' ),
            ],
            'lname' => [
                'class'       => [
                    'lakit-subscribe-form__input lakit-subscribe-form__fname-field',
                ],
                'type'        => 'text',
                'name'        => 'lname',
                'placeholder' => esc_html__( 'Last Name', 'lastudio-kit' )
            ],
            'address' => [
                'class'       => [
                    'lakit-subscribe-form__input lakit-subscribe-form__address-field',
                ],
                'type'        => 'text',
                'name'        => 'address',
                'placeholder' => esc_html__( 'Address', 'lastudio-kit' )
            ],
            'phone' => [
                'class'       => [
                    'lakit-subscribe-form__input lakit-subscribe-form__phone-field',
                ],
                'type'        => 'tel',
                'name'        => 'phone',
                'placeholder' => esc_html__( 'Phone Number', 'lastudio-kit' )
            ],
        ];

        foreach ( $additional_filds as $key => $data ) {

            $type        = $data['type'];
            $placeholder = $data['placeholder'];

            $data = $default_fields_data[ $type ];

            if ( ! empty( $placeholder ) ) {
                $data['placeholder'] = $placeholder;
            }

            $this->add_render_attribute( $key, $data );?>
            <input <?php echo $this->get_render_attribute_string( $key ); ?>><?php
        }

    }

    /**
     * [render description]
     * @return [type] [description]
     */
    protected function render() {

        $this->_context = 'render';

        $this->_open_wrap();
        include $this->_get_global_template( 'index' );
        $this->_close_wrap();
    }

}