<?php

/**
 * Class: LaStudioKit_Register
 * Name: Register Form
 * Slug: lakit-register-frm
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}


/**
 * Register Widget
 */
class LaStudioKit_Register extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_script_depends( 'lastudio-kit-base' );
		    if ( ! lastudio_kit()->is_optimized_css_mode() ) {
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/register.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/register.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/register.min.css' );
		return [
			'key' => $widget_name,
			'version' => lastudio_kit()->get_version(true),
			'file_path' => $file_path,
			'data' => [
				'file_url' => $file_url
			]
		];
	}

    public function get_name() {
        return 'lakit-register-frm';
    }

    protected function get_widget_title() {
        return esc_html__( 'Register Form', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'lastudio-kit-icon-register';
    }

    protected function register_controls() {
        $this->_start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Content', 'lastudio-kit' ),
            )
        );

	    $this->_add_control(
		    'label_email',
		    array(
			    'label'   => esc_html__( 'Email Label', 'lastudio-kit' ),
			    'type'    => Controls_Manager::TEXT,
			    'default' => esc_html__( 'Email', 'lastudio-kit' ),
		    )
	    );

	    $this->_add_control(
		    'placeholder_email',
		    array(
			    'label'   => esc_html__( 'Email Placeholder', 'lastudio-kit' ),
			    'type'    => Controls_Manager::TEXT,
			    'default' => esc_html__( 'Email', 'lastudio-kit' ),
		    )
	    );

	    $this->_add_control(
		    'show_username',
		    array(
			    'label'        => esc_html__( 'Show Username Field', 'lastudio-kit' ),
			    'type'         => Controls_Manager::SWITCHER,
			    'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
			    'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
			    'return_value' => 'yes',
			    'default'      => 'yes',
		    )
	    );

        $this->_add_control(
            'label_username',
            array(
                'label'   => esc_html__( 'Username Label', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Username', 'lastudio-kit' ),
                'condition' => array(
	                'show_username' => 'yes'
                )
            )
        );

        $this->_add_control(
            'placeholder_username',
            array(
                'label'   => esc_html__( 'Username Placeholder', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Username', 'lastudio-kit' ),
                'condition' => array(
	                'show_username' => 'yes'
                )
            )
        );

	    $this->_add_control(
		    'show_password',
		    array(
			    'label'        => esc_html__( 'Show Password Field', 'lastudio-kit' ),
			    'type'         => Controls_Manager::SWITCHER,
			    'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
			    'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
			    'return_value' => 'yes',
			    'default'      => 'yes',
		    )
	    );

        $this->_add_control(
            'label_pass',
            array(
                'label'   => esc_html__( 'Password Label', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Password', 'lastudio-kit' ),
                'condition' => array(
	                'show_password' => 'yes'
                )
            )
        );

        $this->_add_control(
            'placeholder_pass',
            array(
                'label'   => esc_html__( 'Password Placeholder', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Password', 'lastudio-kit' ),
                'condition' => array(
	                'show_password' => 'yes'
                )
            )
        );

        $this->_add_control(
            'confirm_password',
            array(
                'label'        => esc_html__( 'Show Confirm Password Field', 'lastudio-kit' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
                'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition' => array(
	                'show_password' => 'yes'
                )
            )
        );

        $this->_add_control(
            'label_pass_confirm',
            array(
                'label'     => esc_html__( 'Confirm Password Label', 'lastudio-kit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Please Confirm Password', 'lastudio-kit' ),
                'condition' => array(
                    'confirm_password' => 'yes',
                    'show_password' => 'yes'
                )
            )
        );

        $this->_add_control(
            'placeholder_pass_confirm',
            array(
                'label'     => esc_html__( 'Confirm Password Placeholder', 'lastudio-kit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Confirm Password', 'lastudio-kit' ),
                'condition' => array(
                    'confirm_password' => 'yes',
                    'show_password' => 'yes'
                )
            )
        );

        $this->_add_control(
            'label_submit',
            array(
                'label'   => esc_html__( 'Register Button Label', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Register', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'register_redirect',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Redirect After Register', 'lastudio-kit' ),
                'default'    => 'home',
                'options'    => array(
                    'home'   => esc_html__( 'Home page', 'lastudio-kit' ),
                    'left'   => esc_html__( 'Stay on the current page', 'lastudio-kit' ),
                    'custom' => esc_html__( 'Custom URL', 'lastudio-kit' ),
                ),
            )
        );

        $this->_add_control(
            'register_redirect_url',
            array(
                'label'     => esc_html__( 'Redirect URL', 'lastudio-kit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'condition' => array(
                    'register_redirect' => 'custom',
                ),
            )
        );

        $this->_add_control(
            'label_registered',
            array(
                'label'   => esc_html__( 'User Registered Message', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'You already registered', 'lastudio-kit' ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'register_fields_style',
            array(
                'label'      => esc_html__( 'Fields', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'input_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-register__input' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'input_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-register__input' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} .lakit-register__input',
            ),
            50
        );

        $this->_add_control(
            'placeholder_style',
            array(
                'label'     => esc_html__( 'Placeholder', 'lastudio-kit' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ),
            25
        );

        $this->_add_control(
            'input_placeholder_color',
            array(
                'label'  => esc_html__( 'Placeholder Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-register__input::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .lakit-register__input::-moz-placeholder'          => 'color: {{VALUE}}',
                    '{{WRAPPER}} .lakit-register__input:-ms-input-placeholder'      => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'input_placeholder_typography',
                'selector' => '{{WRAPPER}} .lakit-register__input::-webkit-input-placeholder',
            ),
            50
        );

        $this->_add_responsive_control(
            'input_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-register__input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'input_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-register__input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'input_border',
                'label'          => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .lakit-register__input',
            ),
            50
        );

        $this->_add_responsive_control(
            'input_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-register__input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'input_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-register__input',
            ),
            100
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'register_labels_style',
            array(
                'label'      => esc_html__( 'Labels', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'labels_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-register__label' => 'background-color: {{VALUE}}',
                ),
            ),
            50
        );

        $this->_add_control(
            'labels_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-register__label' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'labels_typography',
                'selector' => '{{WRAPPER}} .lakit-register__label',
            ),
            50
        );

        $this->_add_responsive_control(
            'labels_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-register__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'labels_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-register__label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'labels_border',
                'label'          => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .lakit-register__label',
            ),
            75
        );

        $this->_add_responsive_control(
            'labels_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-register__label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'labels_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-register__label',
            ),
            100
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'register_submit_style',
            array(
                'label'      => esc_html__( 'Submit', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );
        $this->add_responsive_control(
            'button_width',
            array(
                'label' => esc_html__( 'Button Width', 'lastudio-kit' ),
                'type'  => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'custom'],
                'selectors' => array(
                    '{{WRAPPER}} .lakit-register__submit' => 'width: {{SIZE}}{{UNIT}};'
                )
            )
        );
        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'register_submit_typography',
                'selector' => '{{WRAPPER}} .lakit-register__submit'
            ),
            50
        );

        $this->_start_controls_tabs( 'tabs_form_submit_style' );

        $this->_start_controls_tab(
            'register_form_submit_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'register_submit_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-register__submit' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'register_submit_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-register__submit' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'register_form_submit_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'register_submit_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-register__submit:hover' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'register_submit_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-register__submit:hover' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'register_submit_hover_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'register_submit_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .lakit-register__submit:hover' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'register_submit_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-register__submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'register_submit_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-register__submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'register_submit_border',
                'label'          => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .lakit-register__submit',
            ),
            75
        );

        $this->_add_responsive_control(
            'register_submit_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-register__submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'register_submit_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-register__submit',
            ),
            100
        );

        $this->_add_responsive_control(
            'register_submit_alignment',
            array(
                'label'   => esc_html__( 'Alignment', 'lastudio-kit' ),
                'type'    => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => array(
                    'left'    => array(
                        'title' => esc_html__( 'Left', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => esc_html__( 'Center', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'right' => array(
                        'title' => esc_html__( 'Right', 'lastudio-kit' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-register-submit' => 'text-align: {{VALUE}};',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'login_errors_style',
            array(
                'label'      => esc_html__( 'Errors', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            )
        );

        $this->_add_control(
            'errors_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-register-message' => 'background-color: {{VALUE}}',
                ),
            ),
            50
        );

        $this->_add_control(
            'errors_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-register-message' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'errors_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-register-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_responsive_control(
            'errors_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-register-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'errors_border',
                'label'          => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} .lakit-register-message',
            ),
            75
        );

        $this->_add_responsive_control(
            'errors_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-register-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'errors_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-register-message',
            ),
            100
        );

        $this->_end_controls_section();
    }

    protected function render() {

        $this->_context = 'render';

        $settings = $this->get_settings();

        if ( is_user_logged_in() && ! lastudio_kit_integration()->in_elementor() ) {

            $this->_open_wrap();
            echo $settings['label_registered'];
            $this->_close_wrap();

            return;
        }

        $registration_enabled = get_option( 'users_can_register' ) || ('yes' === get_option( 'woocommerce_enable_myaccount_registration' ));

        if ( ! $registration_enabled && ! lastudio_kit_integration()->in_elementor() ) {

            $this->_open_wrap();
            esc_html_e( 'Registration disabled', 'lastudio-kit' );
            $this->_close_wrap();

            return;
        }

        $this->_open_wrap();

        $redirect_url = site_url( $_SERVER['REQUEST_URI'] );

        switch ( $settings['register_redirect'] ) {

            case 'home':
                $redirect_url = esc_url( home_url( '/' ) );
                break;

            case 'custom':
                $redirect_url = $settings['register_redirect_url'];
                break;
        }

        if ( ! $registration_enabled ) {
            esc_html_e( 'Registration currently disabled and this form will not be visible for guest users. Please, enable registration in Settings/General or remove this widget from the page.', 'lastudio-kit' );
        }

        include $this->_get_global_template( 'index' );

        $this->_close_wrap();
    }
    
}