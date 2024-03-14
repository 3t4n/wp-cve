<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;
use XproElementorAddons\Libs\Dashboard\Classes\Xpro_Elementor_Dashboard_Utils;

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
class Contact_Form extends Widget_Base {

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
		return 'xpro-contact-form';
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
		return __( 'Contact Form', 'xpro-elementor-addons' );
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
		return 'xi-email xpro-widget-label';
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
		return array( 'contact', 'form', 'forms', 'email', 'mail' );
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */

	public function get_script_depends() {
		return array( 'recaptcha' );
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
			'section_content-general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		$repeater = new Repeater();

		$field_types = array(
			'text'     => __( 'Text', 'xpro-elementor-addons' ),
			'email'    => __( 'Email', 'xpro-elementor-addons' ),
			'textarea' => __( 'Textarea', 'xpro-elementor-addons' ),
			'url'      => __( 'URL', 'xpro-elementor-addons' ),
			'tel'      => __( 'Tel', 'xpro-elementor-addons' ),
			'radio'    => __( 'Radio', 'xpro-elementor-addons' ),
			'select'   => __( 'Select', 'xpro-elementor-addons' ),
			'checkbox' => __( 'Checkbox', 'xpro-elementor-addons' ),
			'number'   => __( 'Number', 'xpro-elementor-addons' ),
			'date'     => __( 'Date', 'xpro-elementor-addons' ),
			'time'     => __( 'Time', 'xpro-elementor-addons' ),
			'html'     => __( 'HTML', 'xpro-elementor-addons' )
		);

		$repeater->start_controls_tabs( 'form_fields_tabs' );

		$repeater->start_controls_tab(
			'form_fields_content_tab',
			array(
				'label' => __( 'Content', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'field_type',
			array(
				'label'   => __( 'Type', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			)
		);

		$repeater->add_control(
			'field_label',
			array(
				'label'   => __( 'Label', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'placeholder',
			array(
				'label'      => __( 'Placeholder', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::TEXT,
				'default'    => '',
				'dynamic'    => array(
					'active' => true,
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'tel',
								'text',
								'email',
								'textarea',
								'number',
								'url',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'required',
			array(
				'label'        => __( 'Required', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => '',
				'conditions'   => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => '!in',
							'value'    => array(
//								'checkbox',
								'html',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'field_options',
			array(
				'label'       => __( 'Options', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'dynamic'     => array(
					'active' => true,
				),
				'description' => __( 'Enter each option in a separate line. To differentiate between label and value, separate them with a pipe char ("|"). For example: First Name|f_name', 'xpro-elementor-addons' ),
				'conditions'  => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'select',
								'checkbox',
								'radio',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'inline_list',
			array(
				'label'        => __( 'Inline List', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'xpro-subgroup-inline',
				'default'      => '',
				'conditions'   => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'checkbox',
								'radio',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'field_html',
			array(
				'label'      => __( 'HTML', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::TEXTAREA,
				'dynamic'    => array(
					'active' => true,
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'field_type',
							'value' => 'html',
						),
					),
				),
			)
		);

		$repeater->add_responsive_control(
			'column',
			array(
				'label'     => __( 'Columns', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'1'  => __( '1 Column', 'xpro-elementor-addons' ),
					'2'  => __( '2 Column', 'xpro-elementor-addons' ),
					'3'  => __( '3 Column', 'xpro-elementor-addons' ),
					'4'  => __( '4 Column', 'xpro-elementor-addons' ),
					'5'  => __( '5 Column', 'xpro-elementor-addons' ),
					'6'  => __( '6 Column', 'xpro-elementor-addons' ),
					'7'  => __( '7 Column', 'xpro-elementor-addons' ),
					'8'  => __( '8 Column', 'xpro-elementor-addons' ),
					'9'  => __( '9 Column', 'xpro-elementor-addons' ),
					'10' => __( '10 Column', 'xpro-elementor-addons' ),
					'11' => __( '11 Column', 'xpro-elementor-addons' ),
					'12' => __( '12 Column', 'xpro-elementor-addons' ),
				),
				'default'   => '12',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.xpro-contact-form-item' => 'max-width:calc( 100% / 12 * {{VALUE}});',
				),
			)
		);

		$repeater->add_control(
			'rows',
			array(
				'label'      => __( 'Rows', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::NUMBER,
				'default'    => 4,
				'conditions' => array(
					'terms' => array(
						array(
							'name'  => 'field_type',
							'value' => 'textarea',
						),
					),
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'form_fields_advanced_tab',
			array(
				'label' => __( 'Advanced', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'field_value',
			array(
				'label'      => __( 'Default Value', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::TEXT,
				'default'    => '',
				'dynamic'    => array(
					'active' => true,
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'field_type',
							'operator' => 'in',
							'value'    => array(
								'text',
								'email',
								'textarea',
								'url',
								'tel',
								'radio',
								'select',
								'number',
							),
						),
					),
				),
			)
		);

		$repeater->add_control(
			'css_classes',
			array(
				'label'   => __( 'CSS Class', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'title'   => __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'xpro-elementor-addons' ),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'form_fields',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'field_type'  => 'text',
						'field_label' => __( 'Name', 'xpro-elementor-addons' ),
						'placeholder' => __( 'Name', 'xpro-elementor-addons' ),
						'column'      => '6',
					),
					array(
						'field_type'  => 'email',
						'required'    => 'true',
						'field_label' => __( 'Email', 'xpro-elementor-addons' ),
						'placeholder' => __( 'Email', 'xpro-elementor-addons' ),
						'column'      => '6',
					),
					array(
						'field_type'  => 'textarea',
						'field_label' => __( 'Message', 'xpro-elementor-addons' ),
						'placeholder' => __( 'Message', 'xpro-elementor-addons' ),
						'width'       => '12',
					),
				),
				'title_field' => '{{{ field_label }}}',
			)
		);

		$this->add_control(
			'show_labels',
			array(
				'label'        => __( 'Show Label', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'separator'    => 'before',
				'selectors'    => array(
					'{{WRAPPER}} .xpro-contact-form-item-label' => 'display: block',
				),
			)
		);

		$this->add_control(
			'mark_required',
			array(
				'label'        => __( 'Required Mark', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'selectors'    => array(
					'{{WRAPPER}} .xpro-contact-form-require > label:after' => 'content: "*"',
				),
				'condition'    => array(
					'show_labels!' => '',
				),
			)
		);

		$this->add_control(
			'recaptcha',
			array(
				'label'              => __( 'reCaptcha', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'true',
				'label_on'           => esc_html__( 'Show', 'xpro-elementor-addons' ),
				'label_off'          => esc_html__( 'Hide', 'xpro-elementor-addons' ),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'missing_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'reCaptcha not show in editor screen.', 'xpro-elementor-addons' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					'recaptcha' => 'true',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_options',
			array(
				'label' => __( 'Settings', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'form_name',
			array(
				'label'              => __( 'Form Name', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => __( 'Contact Form', 'xpro-elementor-addons' ),
				'label_block'        => true,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'form_subject',
			array(
				'label'              => __( 'Subject', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => __( 'Enquiry Form', 'xpro-elementor-addons' ),
				'label_block'        => true,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'       => __( 'Button Text', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Send', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'success_message',
			array(
				'label'              => __( 'Success Notice', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => __( 'Form submit successfully!', 'xpro-elementor-addons' ),
				'label_block'        => true,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'error_message',
			array(
				'label'              => __( 'Error Notice', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => __( 'Something went wrong please check!', 'xpro-elementor-addons' ),
				'label_block'        => true,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'required_field_message',
			array(
				'label'              => __( 'Required Notice', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => __( 'Please provide the missing fields!', 'xpro-elementor-addons' ),
				'label_block'        => true,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'captcha_message',
			array(
				'label'              => __( 'reCaptcha Notice', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => __( 'reCaptcha not valid!', 'xpro-elementor-addons' ),
				'label_block'        => true,
				'frontend_available' => true,
				'condition'          => array(
					'recaptcha' => 'true',
				),
			)
		);

		$this->end_controls_section();

		//Style
		$this->start_controls_section(
			'section_general_style',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'     => __( 'Columns Gap', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-item' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'     => __( 'Rows Gap', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-contact-form'      => 'margin-bottom: -{{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'box_bg',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-contact-form',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'box_border',
				'selector' => '{{WRAPPER}} .xpro-contact-form',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} .xpro-contact-form',
			)
		);

		$this->add_responsive_control(
			'box_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-contact-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-contact-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-contact-form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_label',
			array(
				'label'     => __( 'Label', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'label_typography',
				'selector' => '{{WRAPPER}} .xpro-contact-form label',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'mark_required_color',
			array(
				'label'     => __( 'Mark Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-require > label:after' => 'color: {{COLOR}};',
				),
				'condition' => array(
					'mark_required' => 'true',
				),
			)
		);

		$this->add_control(
			'label_spacing',
			array(
				'label'     => __( 'Spacing', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-item > label' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_subfield',
			array(
				'label'     => __( 'Checkbox/Radio', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subfield_typography',
				'selector' => '{{WRAPPER}} .xpro-contact-form-field-subgroup label',
			)
		);

		$this->add_control(
			'subfield_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-field-subgroup label' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'subfield_spacing',
			array(
				'label'     => __( 'Spacing', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 0,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-field-subgroup' => 'grid-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_html',
			array(
				'label'     => __( 'HTML Field', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'html_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-item-type-html' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'html_typography',
				'selector' => '{{WRAPPER}} .xpro-contact-form-item-type-html',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_fields_style',
			array(
				'label' => __( 'Fields', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'field_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-contact-form-field-textual',
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
					'{{WRAPPER}} .xpro-contact-form-field-textual' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'field_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-field-textual' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .xpro-contact-form-field-textual:focus' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'field_focus_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-field-textual:focus' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'field_focus_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-field-textual:focus' => 'border-color: {{VALUE}}',
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
				'selector'  => '{{WRAPPER}} .xpro-contact-form-field-textual',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'field_box_shadow',
				'selector' => '{{WRAPPER}} .xpro-contact-form-field-textual',
			)
		);

		$this->add_responsive_control(
			'field_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-contact-form-field-textual' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .xpro-contact-form-field-textual' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'message_style',
			array(
				'label' => __( 'Notices', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'message_alignment',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Above', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-alert' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'message_typography',
				'selector' => '{{WRAPPER}} .xpro-alert',
			)
		);

		$this->add_control(
			'message_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-alert' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'message_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-alert' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'message_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-alert' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'button_style',
			array(
				'label' => __( 'Button', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'button_column',
			array(
				'label'     => __( 'Columns', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'1'  => __( '1 Column', 'xpro-elementor-addons' ),
					'2'  => __( '2 Column', 'xpro-elementor-addons' ),
					'3'  => __( '3 Column', 'xpro-elementor-addons' ),
					'4'  => __( '4 Column', 'xpro-elementor-addons' ),
					'5'  => __( '5 Column', 'xpro-elementor-addons' ),
					'6'  => __( '6 Column', 'xpro-elementor-addons' ),
					'7'  => __( '7 Column', 'xpro-elementor-addons' ),
					'8'  => __( '8 Column', 'xpro-elementor-addons' ),
					'9'  => __( '9 Column', 'xpro-elementor-addons' ),
					'10' => __( '10 Column', 'xpro-elementor-addons' ),
					'11' => __( '11 Column', 'xpro-elementor-addons' ),
					'12' => __( '12 Column', 'xpro-elementor-addons' ),
				),
				'default'   => '12',
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-item-type-submit' => 'max-width:calc( 100% / 12 * {{VALUE}});',
				),
			)
		);

		$this->add_responsive_control(
			'button_alignment',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Above', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-item-type-submit' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .xpro-contact-form-submit-button',
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
					'{{WRAPPER}} .xpro-contact-form-submit-button' => 'width: {{SIZE}}{{UNIT}}; min-width:max-content;',
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
					'{{WRAPPER}} .xpro-contact-form-submit-button' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .xpro-contact-form-submit-button',
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
					'{{WRAPPER}} .xpro-contact-form-submit-button:hover, {{WRAPPER}} .xpro-contact-form-submit-button:focus' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .xpro-contact-form-submit-button:hover, {{WRAPPER}} .xpro-contact-form-submit-button:focus',
			)
		);

		$this->add_control(
			'submit_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-submit-button:hover, {{WRAPPER}} .xpro-contact-form-submit-button:focus' => 'border-color: {{VALUE}};',
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
				'selector'  => '{{WRAPPER}} .xpro-contact-form-submit-button',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'submit_box_shadow',
				'selector' => '{{WRAPPER}} .xpro-contact-form-submit-button',
			)
		);

		$this->add_control(
			'submit_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-contact-form-submit-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .xpro-contact-form-submit-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .xpro-contact-form-submit-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_captcha_style',
			array(
				'label'     => __( 'reCaptcha', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'recaptcha' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'recaptcha_column',
			array(
				'label'     => __( 'Columns', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'1' => __( '1 Column', 'xpro-elementor-addons' ),
					'2' => __( '2 Column', 'xpro-elementor-addons' ),
					'3' => __( '3 Column', 'xpro-elementor-addons' ),
					'4' => __( '4 Column', 'xpro-elementor-addons' ),
					'5' => __( '5 Column', 'xpro-elementor-addons' ),
					'6' => __( '6 Column', 'xpro-elementor-addons' ),
				),
				'default'   => '1',
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-item-type-captcha' => 'max-width: calc(100% / {{VALUE}});',
				),
			)
		);

		$this->add_responsive_control(
			'recaptcha_alignment',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Above', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-contact-form-item-type-captcha' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'recaptcha_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-contact-form-item-type-captcha > div' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function textarea_field( $item, $i ) {
		$this->add_render_attribute(
			'textarea' . $i,
			array(
				'class' => array(
					'xpro-contact-form-field-textual',
				),
				'name'  => 'form-field-' . $item['_id'],
				'id'    => 'form-field-' . $item['_id'],
				'rows'  => $item['rows'],
			)
		);

		if ( $item['placeholder'] ) {
			$this->add_render_attribute( 'textarea' . $i, 'placeholder', $item['placeholder'] );
		}

		if ( $item['required'] ) {
			$this->add_render_attribute( 'textarea' . $i, 'required', 'required' );
			$this->add_render_attribute( 'textarea' . $i, 'aria-required', 'true' );
		}

		$value = empty( $item['field_value'] ) ? '' : $item['field_value'];

		return '<textarea ' . $this->get_render_attribute_string( 'textarea' . $i ) . '>' . $value . '</textarea>';
	}

	protected function select_field( $item, $i ) {
		$this->add_render_attribute(
			array(
				'select' . $i => array(
					'name'  => 'form-field-' . $item['_id'],
					'id'    => 'form-field-' . $item['_id'],
					'class' => array(
						'xpro-contact-form-field-textual',
					),
				),
			)
		);

		if ( $item['required'] ) {
			$this->add_render_attribute( 'select' . $i, 'required', 'required' );
			$this->add_render_attribute( 'select' . $i, 'aria-required', 'true' );
		}

		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );

		if ( ! $options ) {
			return '';
		}

		ob_start();
		?>
		<select <?php $this->print_render_attribute_string( 'select' . $i ); ?>>
			<?php
			foreach ( $options as $key => $option ) {
				$option_id    = $item['_id'] . $key;
				$option_value = esc_attr( $option );
				$option_label = esc_html( $option );

				if ( false !== strpos( $option, '|' ) ) {
					list( $label, $value ) = explode( '|', $option );
					$option_value          = esc_attr( $value );
					$option_label          = esc_html( $label );
				}

				$this->add_render_attribute( $option_id, 'value', $option_value );

				// Support multiple selected values
				if ( ! empty( $item['field_value'] ) && in_array( $option_value, explode( ',', $item['field_value'] ), true ) ) {
					$this->add_render_attribute( $option_id, 'selected', 'selected' );
				}
				echo '<option ' . $this->get_render_attribute_string( $option_id ) . '>' . esc_html( $option_label ) . '</option>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</select>
		<?php

		$select = ob_get_clean();

		return $select;
	}

	protected function radio_checkbox_field( $item, $item_index, $type ) {
		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );
		$html    = '';
		if ( $options ) {
			$html .= '<div class="xpro-contact-form-field-subgroup ' . $item['inline_list'] . '">';
			foreach ( $options as $key => $option ) {
				$element_id   = $item['_id'] . $key;
				$html_id      = 'form-field-' . $item['_id'] . '-' . $key;
				$option_label = $option;
				$option_value = $option;
				if ( false !== strpos( $option, '|' ) ) {
					list( $option_label, $option_value ) = explode( '|', $option );
				}

				$this->add_render_attribute(
					$element_id,
					array(
						'type'  => $type,
						'value' => $option_value,
						'id'    => $html_id,
						'name'  => 'form-field-' . $item['_id'] . ( ( 'checkbox' === $type && count( $options ) > 1 ) ? '[]' : '' ),
					)
				);

				if ( ! empty( $item['field_value'] ) && $option_value === $item['field_value'] ) {
					$this->add_render_attribute( $element_id, 'checked', 'checked' );
				}

				if ( $item['required'] ) {
					$this->add_render_attribute( $element_id, 'required', 'required' );
					$this->add_render_attribute( $element_id, 'aria-required', 'true' );
				}

				$html .= '<span class="xpro-contact-form-field-option"><input ' . $this->get_render_attribute_string( $element_id ) . '> <label for="' . $html_id . '">' . $option_label . '</label></span>';
			}
			$html .= '</div>';
		}

		return $html;
	}

	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings      = $this->get_settings_for_display();
		$user_settings = Xpro_Elementor_Dashboard_Utils::instance()->get_option( 'xpro_elementor_user_data', array() );

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'contact-form/layout/frontend.php';
	}
}
