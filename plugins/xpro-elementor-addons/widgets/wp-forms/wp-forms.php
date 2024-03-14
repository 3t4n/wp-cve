<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class WP_Forms extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'wpforms';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'WpForms', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-wp-forms xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'form', 'forms', 'contact', 'wpforms', 'wpforms' );
	}

	/**
	 * Render widget as plain content.
	 *
	 * @since 1.0.0
	 */
	public function render_plain_content() {

		echo $this->render_shortcode(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

	/**
	 * Render shortcode.
	 *
	 * @since 1.0.0
	 */
	public function render_shortcode() {

		return sprintf(
			'[wpforms id="%1$d" title="%2$s" description="%3$s"]',
			absint( $this->get_settings_for_display( 'form_id' ) ),
			false,
			false
		);
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_form',
			array(
				'label' => esc_html__( 'Form', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		if ( ! class_exists( '\WPForms\WPForms' ) ) {
			$this->add_control(
				'wpforms_missing_notice',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf(
					/* translators: %s: Title */
						__( 'Looks like %1$s is missing in your site. Please click on the link below and install/activate %1$s. Make sure to refresh this page after installation or activation.', 'xpro-elementor-addons' ),
						'<a href="' . esc_url( admin_url( 'plugin-install.php?s=WPForms&tab=search&type=term' ) ) . '" target="_blank" rel="noopener">WPForms</a>'
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
				)
			);

			$this->add_control(
				'wpforms_install',
				array(
					'type' => Controls_Manager::RAW_HTML,
					'raw'  => '<a href="' . esc_url( admin_url( 'plugin-install.php?s=WPForms&tab=search&type=term' ) ) . '" target="_blank" rel="noopener">Click to install or activate WPForms</a>',
				)
			);

			$this->end_controls_section();

			return;
		}

		$forms = $this->get_forms();

		if ( empty( $forms ) ) {
			$this->add_control(
				'add_form_notice',
				array(
					'show_label'      => false,
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => wp_kses(
						__( '<b>You haven\'t created a form yet.</b><br> What are you waiting for?', 'xpro-elementor-addons' ),
						array(
							'b'  => array(),
							'br' => array(),
						)
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		}

		$this->add_control(
			'form_id',
			array(
				'label'       => esc_html__( 'Form', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => $forms,
				'default'     => '0',
			)
		);

		$this->add_control(
			'edit_form',
			array(
				'show_label' => false,
				'type'       => Controls_Manager::RAW_HTML,
				'raw'        => wp_kses( /* translators: %s - WPForms documentation link. */
					__( 'Need to make changes? <a href="#">Edit the selected form.</a>', 'xpro-elementor-addons' ),
					array( 'a' => array() )
				),
				'condition'  => array(
					'form_id!' => '0',
				),
			)
		);

		$this->add_control(
			'test_form_notice',
			array(
				'show_label'      => false,
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => sprintf(
					wp_kses( /* translators: %s - WPForms documentation link. */
						__( '<b>Heads up!</b> Don\'t forget to test your form. <a href="%s" target="_blank" rel="noopener noreferrer">Check out our complete guide!</a>', 'xpro-elementor-addons' ),
						array(
							'b'  => array(),
							'br' => array(),
							'a'  => array(
								'href'   => array(),
								'rel'    => array(),
								'target' => array(),
							),
						)
					),
					'https://wpforms.com/docs/how-to-properly-test-your-wordpress-forms-before-launching-checklist/'
				),
				'condition'       => array(
					'form_id!' => '0',
				),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			)
		);

		$this->add_control(
			'add_form_btn',
			array(
				'show_label'  => false,
				'label_block' => false,
				'type'        => Controls_Manager::BUTTON,
				'button_type' => 'default',
				'separator'   => 'before',
				'text'        => '<b>+</b>' . esc_html__( 'New form', 'xpro-elementor-addons' ),
				'event'       => 'elementorWPFormsAddFormBtnClick',
			)
		);

		$this->end_controls_section();

		//Style
		$this->start_controls_section(
			'section_fields_style',
			array(
				'label' => __( 'Field', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'field_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .wpforms-field input, {{WRAPPER}} .wpforms-field-textarea textarea',
			)
		);

		$this->add_control(
			'field_placeholder_color',
			array(
				'label'     => __( 'Placeholder Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ::-webkit-input-placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} ::-moz-placeholder'      => 'color: {{VALUE}};',
					'{{WRAPPER}} ::-ms-input-placeholder' => 'color: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_field_state' );

		$this->start_controls_tab(
			'tab_field_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'field_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpforms-field input, {{WRAPPER}} .wpforms-field-textarea textarea, {{WRAPPER}} .wpforms-form select' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'field_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpforms-field input, {{WRAPPER}} .wpforms-field-textarea textarea, {{WRAPPER}} .wpforms-form select' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_field_focus',
			array(
				'label' => __( 'Focus', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'field_focus_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpforms-field input:focus, {{WRAPPER}} .wpforms-field-textarea textarea:focus, {{WRAPPER}} .wpforms-form select:focus' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'field_focus_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpforms-field input:focus, {{WRAPPER}} .wpforms-field-textarea textarea:focus,{{WRAPPER}} .wpforms-form focus' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'field_focus_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpforms-field input:focus, {{WRAPPER}} .wpforms-field-textarea textarea:focus,{{WRAPPER}} .wpforms-form select:focus' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'field_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'field_border',
				'selector'  => '{{WRAPPER}} .wpforms-field input, {{WRAPPER}} .wpforms-field-textarea textarea,{{WRAPPER}} .wpforms-form select',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'field_box_shadow',
				'selector' => '{{WRAPPER}} .wpforms-field input, {{WRAPPER}} .wpforms-field-textarea textarea,{{WRAPPER}} .wpforms-form select',
			)
		);

		$this->add_responsive_control(
			'field_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpforms-field input'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpforms-field textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpforms-form select'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'field_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpforms-field input'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpforms-field textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpforms-form select'    => 'height:auto; padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'field_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpforms-field:not(.wpforms-submit), .wpforms-field-required:not(.wpforms-submit)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wpf-form-label',
			array(
				'label' => __( 'Label', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .wpforms-field-container label.wpforms-field-label',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpforms-field-container label.wpforms-field-label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'required_label',
			array(
				'label'     => __( 'Required Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpforms-required-label' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'label_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpforms-field-container label.wpforms-field-label' => 'display: inline-block; padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'label_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpforms-field-container label.wpforms-field-label' => 'display: inline-block; margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wpf-form-sublabel',
			array(
				'label' => __( 'Sub Label', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sublabel_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .wpforms-field-sublabel,{{WRAPPER}} .wpforms-field-label-inline,{{WRAPPER}} .wpforms-field-number-slider-hint',
			)
		);

		$this->add_control(
			'sublabel_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpforms-field-sublabel,{{WRAPPER}} .wpforms-field-label-inline,{{WRAPPER}} .wpforms-field-number-slider-hint' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'sublabel_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpforms-field-sublabel,{{WRAPPER}} .wpforms-field-label-inline,{{WRAPPER}} .wpforms-field-number-slider-hint' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wpf-form-description',
			array(
				'label' => __( 'Description', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'desc_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .wpforms-field-description',
			)
		);

		$this->add_control(
			'desc_label_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpforms-field-description' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'desc_label_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpforms-field-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'submit',
			array(
				'label' => __( 'Button', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'submit_typography',
				'selector' => '{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]',
			)
		);

		$this->add_responsive_control(
			'submit_btn_position',
			array(
				'label'           => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'            => Controls_Manager::CHOOSE,
				'options'         => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'desktop_default' => 'left',
				'toggle'          => false,
				'prefix_class'    => 'xpro-form-btn--%s',
				'selectors'       => array(
					'{{WRAPPER}} .wpforms-submit-container' => 'text-align: {{Value}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_width',
			array(
				'label'      => __( 'Button Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw', '%' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 800,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]' => 'width: {{SIZE}}{{UNIT}}; min-width:max-content;',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'submit_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'submit_bg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'submit_hover_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]:hover,{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'submit_hbg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]:hover,{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]:focus',
			)
		);

		$this->add_control(
			'submit_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]:hover,{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]:focus' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'submit_border_border!' => '',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'submit_border',
				'selector'  => '{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'submit_box_shadow',
				'selector' => '{{WRAPPER}} .wpforms-submit',
			)
		);

		$this->add_control(
			'submit_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'submit_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'submit_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpforms-form .wpforms-submit-container button[type=submit]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get forms list.
	 *
	 * @since 1.0.0
	 *
	 * @returns array Array of forms.
	 */
	public function get_forms() {

		static $forms_list = array();

		if ( empty( $forms_list ) ) {
			$forms = wpforms()->form->get();

			if ( ! empty( $forms ) ) {
				$forms_list[0] = esc_html__( 'Select a form', 'xpro-elementor-addons' );
				foreach ( $forms as $form ) {
					$forms_list[ $form->ID ] = mb_strlen( $form->post_title ) > 100 ? mb_substr( $form->post_title, 0, 97 ) . '...' : $form->post_title;
				}
			}
		}

		return $forms_list;
	}

	/**
	 * Render widget output.
	 *
	 * @since 1.0.0
	 */
	protected function render() {

		if ( ! class_exists( '\WPForms\WPForms' ) ) {
			return;
		}

		if ( Plugin::$instance->editor->is_edit_mode() ) {
			$this->render_edit_mode();
		} else {
			$this->render_frontend();
		}
	}

	/**
	 * Render widget output in edit mode.
	 *
	 * @since 1.0.0
	 */
	protected function render_edit_mode() {

		$form_id = $this->get_settings_for_display( 'form_id' );

		// Popup markup template.
		echo wpforms_render( 'integrations/elementor/popup' ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( count( $this->get_forms() ) < 2 ) {
			// No forms block.
			echo wpforms_render( 'integrations/elementor/no-forms' ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			return;
		}

		if ( empty( $form_id ) ) {
			//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo wpforms_render(
				'integrations/elementor/form-selector',
				array(
					'forms' => $this->get_form_selector_options(),
				),
				true
			);

			return;
		}

		// Finally, render selected form.
		$this->render_frontend();
	}

	/**
	 * Get form selector options.
	 *
	 * @since 1.0.0
	 *
	 * @returns string Rendered options for the select tag.
	 */
	public function get_form_selector_options() {

		$forms   = $this->get_forms();
		$options = '';

		foreach ( $forms as $form_id => $form ) {
			$options .= sprintf(
				'<option value="%d">%s</option>',
				(int) $form_id,
				esc_html( $form )
			);
		}

		return $options;
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since 1.0.0
	 */
	protected function render_frontend() {

		// Render selected form.
		echo do_shortcode( $this->render_shortcode() );
	}
}
