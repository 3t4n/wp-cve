<?php

/**
 * Class: LaStudioKit_Login
 * Name: Login Form
 * Slug: lakit-login-frm
 */

namespace Elementor;

if (!defined('WPINC')) {
    die;
}


/**
 * Login Widget
 */
class LaStudioKit_Login extends LaStudioKit_Base {

    protected function enqueue_addon_resources(){
	    if(!lastudio_kit_settings()->is_combine_js_css()) {
		    $this->add_script_depends( 'lastudio-kit-base' );
		    if(!lastudio_kit()->is_optimized_css_mode()) {
			    wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/login.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
			    $this->add_style_depends( $this->get_name() );
		    }
	    }
    }

	public function get_widget_css_config($widget_name){
		$file_url = lastudio_kit()->plugin_url(  'assets/css/addons/login.min.css' );
		$file_path = lastudio_kit()->plugin_path( 'assets/css/addons/login.min.css' );
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
        return 'lakit-login-frm';
    }

    protected function get_widget_title() {
        return esc_html__( 'Login Form', 'lastudio-kit' );
    }

    public function get_icon() {
        return 'lastudio-kit-icon-login';
    }

    protected function register_controls() {
        $this->_start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Content', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'label_username',
            array(
                'label'   => esc_html__( 'Username Label', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Username', 'lastudio-kit' ),
            )
        );
		$this->_add_control(
            'username_placeholder',
            array(
                'label'   => esc_html__( 'Username Placeholder', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Username or Email', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'label_password',
            array(
                'label'   => esc_html__( 'Password Label', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Password', 'lastudio-kit' ),
            )
        );

		$this->_add_control(
            'password_placeholder',
            array(
                'label'   => esc_html__( 'Password Placeholder', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Password', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'label_remember',
            array(
                'label'   => esc_html__( 'Remember Me Label', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Remember Me', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'label_log_in',
            array(
                'label'   => esc_html__( 'Log In Button Label', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Log In', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'login_redirect',
            array(
                'type'       => 'select',
                'label'      => esc_html__( 'Redirect After Login', 'lastudio-kit' ),
                'default'    => 'home',
                'options'    => array(
                    'home'   => esc_html__( 'Home page', 'lastudio-kit' ),
                    'left'   => esc_html__( 'Stay on the current page', 'lastudio-kit' ),
                    'custom' => esc_html__( 'Custom URL', 'lastudio-kit' ),
                ),
            )
        );

        $this->_add_control(
            'login_redirect_url',
            array(
                'label'     => esc_html__( 'Redirect URL', 'lastudio-kit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => '',
                'dynamic'   => array(
                    'active' => true,
                ),
                'condition' => array(
                    'login_redirect' => 'custom',
                ),
            )
        );

        $this->_add_control(
            'label_logged_in',
            array(
                'label'   => esc_html__( 'Logged in message', 'lastudio-kit' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'You already logged in', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'lost_password_link',
            array(
                'label'   => esc_html__( 'Lost Password link', 'lastudio-kit' ),
                'type'    => Controls_Manager::SWITCHER,
                'default' => '',
            )
        );

        $this->_add_control(
            'lost_password_link_text',
            array(
                'label'     => esc_html__( 'Lost Password link text', 'lastudio-kit' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__( 'Lost your password?', 'lastudio-kit' ),
                'condition' => array(
                    'lost_password_link' => 'yes',
                ),
            )
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'login_fields_style',
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
                    '{{WRAPPER}} .lakit-login input.input' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .lakit-login input.input' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} .lakit-login input.input',
            ),
            50
        );

        $this->_add_responsive_control(
            'input_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-login input.input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .lakit-login input.input' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector'       => '{{WRAPPER}} .lakit-login input.input',
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
                    '{{WRAPPER}} .lakit-login input.input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            50
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'input_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-login input.input',
            ),
            100
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'login_labels_style',
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
                    '{{WRAPPER}} .lakit-login label' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .lakit-login label' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'labels_typography',
                'selector' => '{{WRAPPER}} .lakit-login label',
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
                    '{{WRAPPER}} .lakit-login label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .lakit-login label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector'       => '{{WRAPPER}} .lakit-login label',
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
                    '{{WRAPPER}} .lakit-login label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'labels_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-login label',
            ),
            100
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'login_submit_style',
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
                    '{{WRAPPER}} input[type="submit"]' => 'width: {{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'login_submit_typography',
                'selector'  => '{{WRAPPER}} input[type="submit"]',
            ),
            50
        );

        $this->_start_controls_tabs( 'tabs_form_submit_style' );

        $this->_start_controls_tab(
            'login_form_submit_normal',
            array(
                'label' => esc_html__( 'Normal', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'login_submit_bg_color',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} input[type="submit"]' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'login_submit_color',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} input[type="submit"]' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_end_controls_tab();

        $this->_start_controls_tab(
            'login_form_submit_hover',
            array(
                'label' => esc_html__( 'Hover', 'lastudio-kit' ),
            )
        );

        $this->_add_control(
            'login_submit_bg_color_hover',
            array(
                'label'  => esc_html__( 'Background Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} input[type="submit"]:hover' => 'background-color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'login_submit_color_hover',
            array(
                'label'  => esc_html__( 'Text Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} input[type="submit"]:hover' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'login_submit_hover_border_color',
            array(
                'label' => esc_html__( 'Border Color', 'lastudio-kit' ),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'login_submit_border_border!' => '',
                ),
                'selectors' => array(
                    '{{WRAPPER}} input[type="submit"]:hover' => 'border-color: {{VALUE}};',
                ),
            ),
            75
        );

        $this->_end_controls_tab();

        $this->_end_controls_tabs();

        $this->_add_responsive_control(
            'login_submit_padding',
            array(
                'label'      => esc_html__( 'Padding', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'separator' => 'before',
            ),
            25
        );

        $this->_add_responsive_control(
            'login_submit_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
        );

        $this->_add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'           => 'login_submit_border',
                'label'          => esc_html__( 'Border', 'lastudio-kit' ),
                'placeholder'    => '1px',
                'selector'       => '{{WRAPPER}} input[type="submit"]',
            ),
            75
        );

        $this->_add_responsive_control(
            'login_submit_border_radius',
            array(
                'label'      => esc_html__( 'Border Radius', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'login_submit_box_shadow',
                'selector' => '{{WRAPPER}} input[type="submit"]',
            ),
            100
        );

        $this->_add_responsive_control(
            'login_submit_alignment',
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
                    '{{WRAPPER}} .login-submit' => 'text-align: {{VALUE}};',
                ),
            ),
            50
        );

        $this->_end_controls_section();

        $this->_start_controls_section(
            'lost_password_link_style',
            array(
                'label'      => esc_html__( 'Lost Password Link', 'lastudio-kit' ),
                'tab'        => Controls_Manager::TAB_STYLE,
                'show_label' => false,
                'condition'  => array(
                    'lost_password_link' => 'yes',
                ),
            )
        );

        $this->_add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'lost_password_link_typography',
                'selector' => '{{WRAPPER}} .lakit-login-lost-password-link',
            ),
            50
        );

        $this->_add_control(
            'lost_password_link_color',
            array(
                'label'     => esc_html__( 'Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-login-lost-password-link' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'lost_password_link_hover_color',
            array(
                'label'     => esc_html__( 'Hover Color', 'lastudio-kit' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-login-lost-password-link:hover' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_responsive_control(
            'lost_password_link_margin',
            array(
                'label'      => esc_html__( 'Margin', 'lastudio-kit' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} .lakit-login-lost-password-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            25
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
                    '{{WRAPPER}} .lakit-login-message' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .lakit-login-message' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'errors_link_color',
            array(
                'label'  => esc_html__( 'Link Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-login-message a' => 'color: {{VALUE}}',
                ),
            ),
            25
        );

        $this->_add_control(
            'errors_link_hover_color',
            array(
                'label'  => esc_html__( 'Link Hover Color', 'lastudio-kit' ),
                'type'   => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .lakit-login-message a:hover' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .lakit-login-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .lakit-login-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'selector'       => '{{WRAPPER}} .lakit-login-message',
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
                    '{{WRAPPER}} .lakit-login-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ),
            75
        );

        $this->_add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'errors_box_shadow',
                'selector' => '{{WRAPPER}} .lakit-login-message',
            ),
            100
        );

        $this->_end_controls_section();

    }

    protected function render() {

        $this->_context = 'render';

        $settings = $this->get_settings_for_display();

        if ( is_user_logged_in() && ! lastudio_kit_integration()->in_elementor() ) {

            $this->_open_wrap();
            echo $settings['label_logged_in'];
            $this->_close_wrap();

            return;
        }

        $this->_open_wrap();

        $redirect_url = site_url( $_SERVER['REQUEST_URI'] );

        switch ( $settings['login_redirect'] ) {

            case 'home':
                $redirect_url = esc_url( home_url( '/' ) );
                break;

            case 'custom':
                $redirect_url = esc_url( do_shortcode( $settings['login_redirect_url'] ) );
                break;
        }

        add_filter( 'login_form_bottom', array( $this, 'add_login_fields' ) );
        add_filter( 'login_form_middle', array( $this, 'login_form_middle' ) );

        $login_form = wp_login_form( array(
            'echo'           => false,
            'redirect'       => $redirect_url,
            'remember'       => true,
            'label_username' => $settings['label_username'],
            'label_password' => $settings['label_password'],
            'label_remember' => $settings['label_remember'],
            'label_log_in'   => $settings['label_log_in']
        ) );

        remove_filter( 'login_form_bottom', array( $this, 'add_login_fields' ) );
        remove_filter( 'login_form_middle', array( $this, 'login_form_middle' ) );

        $login_form = preg_replace( '/action=[\'\"].*?[\'\"]/', '', $login_form );

		$username_placeholder = $this->get_settings_for_display('username_placeholder');
	    $password_placeholder = $this->get_settings_for_display('password_placeholder');
		if(empty($username_placeholder)){
			$username_placeholder = $settings['label_username'];
		}
		if(empty($password_placeholder)){
			$password_placeholder = $settings['label_password'];
		}

		$login_form = str_replace(['id="user_login"', 'id="user_pass"'], ['id="user_login" placeholder="'.$username_placeholder.'"', 'id="user_pass" placeholder="'.$password_placeholder.'"'], $login_form);

        if(filter_var($this->get_settings_for_display('lost_password_link'), FILTER_VALIDATE_BOOLEAN)){
            $lost_password_link = sprintf('<a class="lakit-login-lost-password-link" href="%1$s">%2$s</a>', esc_url(wp_lostpassword_url()), $this->get_settings_for_display('lost_password_link_text'));
            $login_form = str_replace('<p class="login-remember">', '<p class="login-remember">' . $lost_password_link, $login_form);
        }

        echo '<div class="lakit-login">';
        echo $login_form;
        include $this->_get_global_template( 'messages' );
        echo '</div>';

        $this->_close_wrap();
    }

    /**
     * Add form fields
     *
     * @param  string $content
     * @return string
     */
    public function add_login_fields( $content ) {
        $content .= '<input type="hidden" name="lakit_login" value="1">';
	    if( lastudio_kit_integration()->is_active_recaptchav3() ) {
		    $content .= '<input type="hidden" name="lakit_recaptcha_response" value=""/>';
	    }
        return $content;
    }

	public function login_form_middle( $content ){
		$this->_context = 'render';
		ob_start();
		if(shortcode_exists('Heateor_Social_Login')){
			echo do_shortcode('[Heateor_Social_Login]');
		}
		elseif (shortcode_exists('TheChamp-Login')){
			echo do_shortcode('[TheChamp-Login]');
		}
		else{
			do_action('lastudio-kit/widget/login/after_form');
		}
		$content .= ob_get_clean();

		return $content;
	}
    
}