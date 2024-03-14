<?php
namespace SG_Email_Marketing\Integrations\Elementor;

use SG_Email_Marketing\Renderer\Renderer;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/**
 * Class managing Elementor Widget integrations.
 */
class Widget extends Widget_Base {

	/**
	 * Get the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @return string The Elementor Widget name.
	 */
	public function get_name() {
		return 'sg-email-marketing';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the widget categories.
	 *
	 * @since 1.0.10
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'basic' );
	}
	/**
	 * Get the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @return string The widget title.
	 */
	public function get_title() {
		return __( 'SG Email Marketing', 'siteground-email-marketing' );
	}

	/**
	 * Get the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @return string The widget icon.
	 */
	public function get_icon() {
		return 'eicon-sg-email-marketing-horizontal';
	}

	/**
	 * Prepare the keywords.
	 *
	 * @since 1.0.0
	 *
	 * @return array Keywords.
	 */
	public function get_keywords() {
		return array(
			'email',
			'marketing',
			'forms',
			'form',
			'contact',
			'contact form',
			'newsletter',
			'sg-email-marketing',
		);
	}

	/**
	 * Add the widgets controls.
	 *
	 * @since 1.0.0
	 */
	protected function register_controls() {
		$this->content_controls();
	}

	/**
	 * Add the forms to the elementor sidebar.
	 *
	 * @since 1.0.0
	 */
	protected function content_controls() {
		$this->start_controls_section(
			'section_form',
			array(
				'label' => esc_html__( 'Choose Form', 'siteground-email-marketing' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$forms = $this->get_forms();

		if ( empty( $forms ) ) {
			$this->add_control(
				'add_form_notice',
				array(
					'show_label' => false,
					'type'       => Controls_Manager::RAW_HTML,
					'raw'        => wp_kses(
						__( 'Create a newsletter contact form now.', 'siteground-email-marketing' ),
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				)
			);
		}

		$this->add_control(
			'formId',
			array(
				'label'       => esc_html__( 'Available Forms', 'siteground-email-marketing' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => $forms,
				'default'     => '0',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_display',
			array(
				'label'     => esc_html__( 'Form Styles', 'siteground-email-marketing' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'formId!'  => '0',
				),
			)
		);

		$this->add_control(
			'formSize',
			array(
				'label'        => esc_html__( 'Size', 'siteground-email-marketing' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'medium',
				'options'      => array(
					'small'  => esc_html__( 'Small', 'siteground-email-marketing' ),
					'medium' => esc_html__( 'Medium', 'siteground-email-marketing' ),
					'large'  => esc_html__( 'Large', 'siteground-email-marketing' ),
				),
				'return_value' => 'yes',
				'condition'    => array(
					'formId!' => '0',
				),
			)
		);

		$this->add_control(
			'formOrientation',
			array(
				'label'        => esc_html__( 'Orientation', 'siteground-email-marketing' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'column',
				'options'      => array(
					'column'  => esc_html__( 'Vertical', 'siteground-email-marketing' ),
					'row' => esc_html__( 'Horizontal', 'siteground-email-marketing' ),
				),
				'return_value' => 'yes',
				'condition'    => array(
					'formId!' => '0',
				),
			)
		);

		$this->add_control(
			'formAlignment',
			array(
				'label'        => esc_html__( 'Alignment', 'siteground-email-marketing' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'left',
				'options'      => array(
					'left'  => esc_html__( 'Left', 'siteground-email-marketing' ),
					'right' => esc_html__( 'Right', 'siteground-email-marketing' ),
					'center'  => esc_html__( 'Center', 'siteground-email-marketing' ),
				),
				'return_value' => 'yes',
				'condition'    => array(
					'formId!' => '0',
					'formOrientation' => 'column',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'field_display',
			array(
				'label'     => esc_html__( 'Field Styles', 'siteground-email-marketing' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'formId!'  => '0',
				),
			)
		);

		$this->add_control(
			'field_display_border_radius',
			array(
				'label' => esc_html__( 'Border Radius', 'siteground-email-marketing' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors' => array(
					'{{WRAPPER}} input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				)
			)
		);

		$this->add_control(
			'field_colors_title',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( '<strong>Colors</strong>', 'siteground-email-marketing' ),
				'separator'       => 'after',
			]
		);

		$this->add_control(
			'field_display_background_color',
			array(
				'label' => esc_html__( 'Background', 'siteground-email-marketing' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} input' => 'background-color: {{VALUE}}',
				),
				'default' => '#FFF',
			)
		);

		$this->add_control(
			'field_display_border_color',
			array(
				'label' => esc_html__( 'Border', 'siteground-email-marketing' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} input' => 'border-color: {{VALUE}}',
				),
				'default' => '#00000040',
			)
		);

		$this->add_control(
			'field_display_text_color',
			array(
				'label' => esc_html__( 'Text', 'siteground-email-marketing' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} input' => 'color: {{VALUE}}',
					'{{WRAPPER}} .sg-marketing-form .sg-marketing-form-submit_message.sg-marketing-form-submit_message--success' => 'color: {{VALUE}}',
				),
				'default' => '#000',
			)
		);

		$this->add_control(
			'field_display_placeholder_color',
			array(
				'label' => esc_html__( 'Placeholder', 'siteground-email-marketing' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} input::placeholder' => 'color: {{VALUE}}',
				),
				'default' => '#000',
			)
		);

		$this->add_control(
			'labelColor',
			array(
				'label' => esc_html__( 'Label', 'siteground-email-marketing' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} label' => 'color: {{VALUE}}',
					'{{WRAPPER}} .sg-marketing-form-container .sg-marketing-form-title-and-description-fields .sg-marketing-form-title,.sg-marketing-form-description'  => 'color: {{VALUE}}',
				),
				'default' => '#000',
			)
		);

		$this->add_control(
			'field_display_error_color',
			array(
				'label' => esc_html__( 'Field Errors', 'siteground-email-marketing' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} input.sg-marketing-form--error' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .sg-marketing-form-sublabel.sg-marketing-form--error' => 'color: {{VALUE}}',
					'{{WRAPPER}} .sg-marketing-form-container .sg-input-container .sg-marketing-form-required-label' => 'color: {{VALUE}}',
					'{{WRAPPER}} .sg-marketing-form .sg-marketing-form-submit_message.sg-marketing-form-submit_message--error'  => 'color: {{VALUE}}'
				),
				'default' => '#D41048',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_display',
			array(
				'label'     => esc_html__( 'Button Styles', 'siteground-email-marketing' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'formId!'  => '0',
				),
			)
		);

		$this->add_control(
			'button_display_border_radius',
			array(
				'label' => esc_html__( 'Border Radius', 'siteground-email-marketing' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors' => array(
					'{{WRAPPER}} button[type=submit]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				)			)
		);

		$this->add_control(
			'button_colors_title',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( '<strong>Colors</strong>', 'siteground-email-marketing' ),
				'separator'       => 'after',
			]
		);

		$this->add_control(
			'button_display_background_color',
			array(
				'label' => esc_html__( 'Background', 'siteground-email-marketing' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} button[type=submit]' => 'background-color: {{VALUE}}',
				),
				'default' => '#066AAB',
			)
		);

		$this->add_control(
			'button_display_text_color',
			array(
				'label' => esc_html__( 'Text', 'siteground-email-marketing' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} button[type=submit]' => 'color: {{VALUE}}',
				),
				'default' => '#FFFFFF',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Choose the render.
	 *
	 * @since 1.0.0
	 */
	protected function render() {
		$default_settings = $this->get_settings_for_display();

		$settings = array(
			'formId' => $default_settings['formId'],
			'formSize' => $default_settings['formSize'],
			'formAlignment' => $default_settings['formAlignment'],
			'formOrientation' => $default_settings['formOrientation'],
			'formBackgroundColor' => $default_settings['_background_color'],
		);

		if ( $settings['formId'] ) {
			echo Renderer::get_instance()->render( esc_attr( $settings['formId'] ), $settings );
		}
	}

	/**
	 * Get the forms created by the user
	 *
	 * @since 1.0.0
	 *
	 * @return array $forms_list Forms list.
	 */
	public function get_forms() {
		$forms_list = array(
			esc_html__( 'Select a form.', 'siteground-email-marketing' ),
		);

		$forms = get_posts(
			array(
				'post_type' => 'sg_form',
				'numberposts' => -1,
			)
		);

		// Return empty list if no forms found.
		if ( empty( $forms ) ) {
			return $forms_list;
		}

		// Loop trough available forms and add them to the form list.
		foreach ( $forms as $form ) {
			$form_content = json_decode( $form->post_content );

			$forms_list[ $form->ID ] = mb_strlen( $form_content->settings->form_title ) > 100 ? mb_substr( $form_content->settings->form_title, 0, 97 ) . '...' : $form_content->settings->form_title;
		}

		return $forms_list;
	}
}
