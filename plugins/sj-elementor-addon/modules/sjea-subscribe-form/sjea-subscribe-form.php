<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Widget_SJEaSubscribeForm extends Widget_Base {

	public function get_name() {
		return 'sjea-subscribe-form';
	}

	public function get_title() {
		return __( 'SJEA - Subscribe Form', 'sjea' );
	}

	public function get_categories() {
		return [ 'sjea-elements' ];
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public static function get_button_sizes() {
		return [
			'xs' => __( 'Extra Small', 'sjea' ),
			'sm' => __( 'Small', 'sjea' ),
			'md' => __( 'Medium', 'sjea' ),
			'lg' => __( 'Large', 'sjea' ),
			'xl' => __( 'Extra Large', 'sjea' ),
		];
	}

	public static function get_campaigns() {

		$campaigns = \SJEaModelHelper::get_campaigns();
		
		if ( count( $campaigns ) > 0 ) {
			
			$options = [ '' => __( 'Choose', 'sjea' ) ];

			foreach ($campaigns as $key => $data) {
				$options[$key] = $key;
			}
		}else{
			$options = [ '' => __( 'Create Campaign First', 'sjea' ) ];
		}

		return $options;
	}

	protected function _register_controls() {
		$repeater = new Repeater();

		$field_types = [
			'text' => __( 'Text', 'sjea' ),
			'tel' => __( 'Tel', 'sjea' ),
			'email' => __( 'Email', 'sjea' ),
			'textarea' => __( 'Textarea', 'sjea' ),
			'number' => __( 'Number', 'sjea' ),
			'select' => __( 'Select', 'sjea' ),
			'url' => __( 'URL', 'sjea' ),
		];

		$repeater->add_control(
			'field_type',
			[
				'label' => __( 'Type', 'sjea' ),
				'type' => Controls_Manager::SELECT,
				'options' => $field_types,
				'default' => 'text',
			]
		);

		$repeater->add_control(
			'field_label',
			[
				'label' => __( 'Label', 'sjea' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'recaptcha',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'placeholder',
			[
				'label' => __( 'Placeholder', 'sjea' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'tel',
								'text',
								'email',
								'textarea',
								'number',
								'url',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'map_value',
			[
				'label' => __( 'Map Value', 'sjea' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'email',
								'recaptcha',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'required',
			[
				'label' => __( 'Required', 'sjea' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'sjea' ),
				'label_off' => __( 'No', 'sjea' ),
				'return_value' => true,
				'default' => '',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'recaptcha',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'field_options',
			[
				'label' => __( 'Options', 'sjea' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				'description' => __( 'Enter each option in a separate line', 'sjea' ),
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => 'in',
							'value' => [
								'select'
							],
						],
					],
				],
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label' => __( 'Column Width', 'sjea' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'sjea' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'25' => '25%',
					'20' => '20%',
				],
				'desktop_default' => '100',
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'operator' => '!in',
							'value' => [
								'recaptcha',
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'rows',
			[
				'label' => __( 'Rows', 'sjea' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'textarea',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_size',
			[
				'label' => __( 'Size', 'sjea' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => __( 'Normal', 'sjea' ),
					'compact' => __( 'Compact', 'sjea' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'recaptcha_style',
			[
				'label' => __( 'Style', 'sjea' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => __( 'Light', 'sjea' ),
					'dark' => __( 'Dark', 'sjea' ),
				],
				'conditions' => [
					'terms' => [
						[
							'name' => 'field_type',
							'value' => 'recaptcha',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'css_classes',
			[
				'label' => __( 'CSS Classes', 'sjea' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
				'title' => __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'sjea' ),
			]
		);

		$this->start_controls_section(
			'section_form_fields',
			[
				'label' => __( 'Form Fields', 'sjea' ),
			]
		);

		$this->add_control(
			'form_name',
			[
				'label' => __( 'Form Name', 'sjea' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'New Form', 'sjea' ),
				'placeholder' => __( 'Form Name', 'sjea' ),
			]
		);

		$this->add_control(
			'form_campaign',
			[
					'label' => __( 'Form Campaign', 'sjea' ),
					'type' => Controls_Manager::SELECT,
					'options' => self::get_campaigns(),
					'default' => '',
			]
		);

		$this->add_control(
			'form_fields',
			[
				'label' => __( 'Form Fields', 'sjea' ),
				'type' => Controls_Manager::REPEATER,
				'show_label' => false,
				'separator' => 'before',
				'fields' => array_values( $repeater->get_controls() ),
				'default' => [
					[
						'field_type' => 'text',
						'field_label' => __( 'First Name', 'sjea' ),
						'placeholder' => __( 'First Name', 'sjea' ),
						'width' => '50',
					],
					[
						'field_type' => 'text',
						'field_label' => __( 'Last Name', 'sjea' ),
						'placeholder' => __( 'Last Name', 'sjea' ),
						'width' => '50',
					],
					[
						'field_type' => 'email',
						'required' => true,
						'field_label' => __( 'Email', 'sjea' ),
						'placeholder' => __( 'Email', 'sjea' ),
						'width' => '100',
					],
				],
				'title_field' => '{{{ field_label }}}',
			]
		);

		$this->add_control(
			'input_size',
			[
				'label' => __( 'Input Size', 'sjea' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'xs' => __( 'Extra Small', 'sjea' ),
					'sm' => __( 'Small', 'sjea' ),
					'md' => __( 'Medium', 'sjea' ),
					'lg' => __( 'Large', 'sjea' ),
					'xl' => __( 'Extra Large', 'sjea' ),
				],
				'default' => 'sm',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'show_labels',
			[
				'label' => __( 'Labels', 'sjea' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'sjea' ),
				'label_off' => __( 'Hide', 'sjea' ),
				'return_value' => 'yes',
				'default' => '',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'mark_required',
			[
				'label' => __( 'Required Mark', 'sjea' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'sjea' ),
				'label_off' => __( 'Hide', 'sjea' ),
				'return_value' => 'yes',
				'default' => '',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_position',
			[
				'label' => __( 'Label Position', 'sjea' ),
				'type' => Controls_Manager::HIDDEN,
				'options' => [
					'above' => __( 'Above', 'sjea' ),
					'inline' => __( 'Inline', 'sjea' ),
				],
				'default' => 'above',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_submit_button',
			[
				'label' => __( 'Submit Button', 'sjea' ),
			]
		);

		$this->add_control(
			'button_text',
			[
				'label' => __( 'Text', 'sjea' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Send', 'sjea' ),
				'placeholder' => __( 'Send', 'sjea' ),
			]
		);

		$this->add_control(
			'button_size',
			[
				'label' => __( 'Size', 'sjea' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => self::get_button_sizes(),
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => __( 'Column Width', 'sjea' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'sjea' ),
					'100' => '100%',
					'80' => '80%',
					'75' => '75%',
					'66' => '66%',
					'60' => '60%',
					'50' => '50%',
					'40' => '40%',
					'33' => '33%',
					'25' => '25%',
					'20' => '20%',
				],
				'desktop_default' => '100',
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => __( 'Alignment', 'sjea' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => __( 'Left', 'sjea' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'sjea' ),
						'icon' => 'fa fa-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'sjea' ),
						'icon' => 'fa fa-align-right',
					],
					'stretch' => [
						'title' => __( 'Justified', 'sjea' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'desktop_default' => 'stretch',
				'prefix_class' => 'elementor%s-button-align-',
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label' => __( 'Icon', 'sjea' ),
				'type' => Controls_Manager::ICON,
				'label_block' => true,
				'default' => '',
			]
		);

		$this->add_control(
			'button_icon_align',
			[
				'label' => __( 'Icon Position', 'sjea' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => __( 'Before', 'sjea' ),
					'right' => __( 'After', 'sjea' ),
				],
				'condition' => [
					'button_icon!' => '',
				],
			]
		);

		$this->add_control(
			'button_icon_indent',
			[
				'label' => __( 'Icon Spacing', 'sjea' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'condition' => [
					'button_icon!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_options',
			[
				'label' => __( 'Emails & Options', 'sjea' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		// $this->add_control(
		// 	'redirect_to',
		// 	[
		// 		'label' => __( 'Redirect To', 'sjea' ),
		// 		'type' => Controls_Manager::TEXT,
		// 		'placeholder' => home_url( '/thank-you' ),
		// 		'label_block' => true,
		// 		'render_type' => 'none',
		// 	]
		// );

		$this->add_control(
			'success_message',
			[
				'label' => __( 'Success Message', 'sjea' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Thank You for Subscribing', 'sjea'),
				'placeholder' => __('Thank You for Subscribing', 'sjea'),
				'label_block' => true,
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'error_message',
			[
				'label' => __( 'Error Message', 'sjea' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Something went wrong', 'sjea'),
				'placeholder' => __('Something went wrong', 'sjea'),
				'label_block' => true,
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'required_field_message',
			[
				'label' => __( 'Required field Message', 'sjea' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Required', 'sjea'),
				'placeholder' => __('Required', 'sjea'),
				'label_block' => true,
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'invalid_message',
			[
				'label' => __( 'Invalid Message', 'sjea' ),
				'type' => Controls_Manager::TEXT,
				'default' => __('Something went wrong... The form is invalid.', 'sjea'),
				'placeholder' => __('Something went wrong... The form is invalid.', 'sjea'),
				'label_block' => true,
				'render_type' => 'none',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_form_style',
			[
				'label' => __( 'Form', 'sjea' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'column_gap',
			[
				'label' => __( 'Columns Gap', 'sjea' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sjea-el-field-group' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .sjea-el-form-fields-wrapper' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'sjea' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sjea-el-field-group' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .sjea-el-form-fields-wrapper' => 'margin-bottom: -{{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_label',
			[
				'label' => __( 'Label', 'sjea' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_spacing',
			[
				'label' => __( 'Spacing', 'sjea' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
					],
				],
				'selectors' => [
					'body.rtl {{WRAPPER}} .elementor-labels-inline .sjea-el-field-group > label' => 'padding-left: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body:not(.rtl) {{WRAPPER}} .elementor-labels-inline .sjea-el-field-group > label' => 'padding-right: {{SIZE}}{{UNIT}};',
					// for the label position = inline option
					'body {{WRAPPER}} .elementor-labels-above .sjea-el-field-group > label' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					// for the label position = above option
				],
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Text Color', 'sjea' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sjea-el-field-group > label, {{WRAPPER}} .sjea-el-field-subgroup label' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->add_control(
			'mark_required_color',
			[
				'label' => __( 'Mark Color', 'sjea' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-mark-required .elementor-field-label:after' => 'color: {{COLOR}};',
				],
				'condition' => [
					'show_labels!' => '',
					'mark_required' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .sjea-el-field-group > label',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'condition' => [
					'show_labels!' => '',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_field_style',
			[
				'label' => __( 'Field', 'sjea' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'field_text_color',
			[
				'label' => __( 'Text Color', 'sjea' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sjea-el-field-group .elementor-field' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'selector' => '{{WRAPPER}} .sjea-el-field-group .elementor-field, {{WRAPPER}} .sjea-el-field-subgroup label',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'field_background_color',
			[
				'label' => __( 'Background Color', 'sjea' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .sjea-el-field-group .elementor-field:not(.elementor-select-wrapper)' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .sjea-el-field-group .elementor-select-wrapper select' => 'background-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_color',
			[
				'label' => __( 'Border Color', 'sjea' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sjea-el-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .sjea-el-field-group .elementor-select-wrapper select' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .sjea-el-field-group .elementor-select-wrapper::before' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'field_border_width',
			[
				'label' => __( 'Border  Width', 'sjea' ),
				'type' => Controls_Manager::DIMENSIONS,
				'placeholder' => '1',
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .sjea-el-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .sjea-el-field-group .elementor-select-wrapper select' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'field_border_radius',
			[
				'label' => __( 'Border Radius', 'sjea' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .sjea-el-field-group .elementor-field:not(.elementor-select-wrapper)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .sjea-el-field-group .elementor-select-wrapper select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'sjea' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'sjea' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'sjea' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Typography', 'sjea' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'sjea' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'button_border',
				'label' => __( 'Border', 'sjea' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .elementor-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'sjea' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_text_padding',
			[
				'label' => __( 'Text Padding', 'sjea' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'sjea' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Text Color', 'sjea' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'sjea' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'sjea' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'button_border_border!' => '',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label' => __( 'Animation', 'sjea' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
		
	}
	private function make_textarea_field( $item, $item_index ) {
		$this->add_render_attribute( 'textarea' . $item_index, [
			'class' => [
				'elementor-field-textual',
				'elementor-field',
				esc_attr( $item['css_classes'] ),
				'elementor-size-' . $item['input_size'],
			],
			'name' => $this->get_attribute_name( $item_index ),
			'id' => $this->get_attribute_id( $item_index ),
			'rows' => $item['rows'],
			'data-map-value' => $item['map_value'],
		] );

		if ( $item['placeholder'] ) {
			$this->add_render_attribute( 'textarea' . $item_index , 'placeholder', $item['placeholder'] );
		}

		if ( $item['required'] ) {
			$this->add_render_attribute( 'textarea' . $item_index , 'required', true );
			$this->add_render_attribute( 'textarea' . $item_index , 'aria-required', 'true' );
		}

		return '<textarea ' . $this->get_render_attribute_string( 'textarea' . $item_index ) . '></textarea>';
	}

	private function make_select_field( $item, $i ) {
		$this->add_render_attribute(
			[
				'select-wrapper' . $i => [
					'class' => [
						'elementor-field',
						'elementor-select-wrapper',
						esc_attr( $item['css_classes'] ),
					],
				],
				'select' . $i => [
					'name' => $this->get_attribute_name( $i ),
					'id' => $this->get_attribute_id( $i ),
					'class' => [
						'elementor-field-textual',
						'elementor-size-' . $item['input_size'],
					],
					'data-map-value' => $item['map_value'],
				],
			]
		);

		if ( $item['required'] ) {
			$this->add_render_attribute( 'select' . $i , 'required', true );
			$this->add_render_attribute( 'select' . $i , 'aria-required', 'true' );
		}

		$options = preg_split( "/\\r\\n|\\r|\\n/", $item['field_options'] );

		if ( ! $options ) {
			return '';
		}

		ob_start();
		?>
		<div <?php echo $this->get_render_attribute_string( 'select-wrapper' . $i ); ?>>
			<select <?php echo $this->get_render_attribute_string( 'select' . $i ); ?>>
				<?php
				foreach ( $options as $option ) : ?>
					<option value="<?php echo esc_attr( $option ); ?>"><?php echo $option; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php

		return ob_get_clean();
	}

	private function form_fields_render_attributes( $i, $instance, $item ) {
		
		if ( $item['field_type'] == 'email' ) {
			$item['map_value'] = 'email';	
		}

		$this->add_render_attribute(
			[
				'field-group' . $i => [
					'class' => [
						'elementor-field-type-' . $item['field_type'],
						'sjea-el-field-group',
						'elementor-column',
					],
				],
				'input' . $i => [
					'type' => $item['field_type'],
					'name' => $this->get_attribute_name( $i ),
					'id' => $this->get_attribute_id( $i ),
					'class' => [
						'elementor-field',
						'elementor-size-' . $item['input_size'],
						esc_attr( $item['css_classes'] ),
					],
					'data-map-value' => $item['map_value'],
				],
				'label' . $i => [
					'for' => $this->get_attribute_id( $i ),
					'class' => 'elementor-field-label',
				],
			]
		);

		if ( empty( $item['width'] ) ) {
			$item['width'] = '100';
		}

		$this->add_render_attribute( 'field-group' . $i, 'class', 'elementor-col-' . $item['width'] );

		if ( $item['width_tablet'] ) {
			$this->add_render_attribute( 'field-group' . $i , 'class' , 'elementor-md-' . $item['width_tablet'] );
		}

		if ( $item['width_mobile'] ) {
			$this->add_render_attribute( 'field-group' . $i , 'class' , 'elementor-sm-' . $item['width_mobile'] );
		}

		if ( $item['placeholder'] ) {
			$this->add_render_attribute( 'input' . $i , 'placeholder', $item['placeholder'] );
		}

		if ( ! $instance['show_labels'] ) {
			$this->add_render_attribute( 'label' . $i, 'class', 'elementor-screen-only' );
		}

		if ( $item['required'] ) {
			$class = 'elementor-field-required';
			if ( $instance['mark_required'] ) {
				$class .= ' elementor-mark-required';
			}
			$this->add_render_attribute( 'field-group' . $i , 'class', $class )
				 ->add_render_attribute( 'input' . $i , 'required', true )
				 ->add_render_attribute( 'input' . $i , 'aria-required', 'true' );
		}
	}

	protected function render() {
		$instance = $this->get_settings();

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'sjea-el-form-fields-wrapper',
						'elementor-labels-' . $instance['label_position'],
					],
				],
				'submit-group' => [
					'class' => [
						'sjea-el-field-group',
						'elementor-column',
						'elementor-field-type-submit',
					],
				],
				'button' => [
					'class' => 'elementor-button',
				],
				'icon-align' => [
					'class' => [
						'elementor-align-icon-' . $instance['button_icon_align'],
						'elementor-button-icon',
					],
				],
			]
		);

		if ( empty( $instance['button_width'] ) ) {
			$instance['button_width'] = '100';
		}

		$this->add_render_attribute( 'submit-group', 'class', 'elementor-col-' . $instance['button_width'] );

		if ( ! empty( $instance['button_width_tablet'] ) ) {
			$this->add_render_attribute( 'submit-group', 'class', 'elementor-md-' . $instance['button_width_tablet'] );
		}

		if ( ! empty( $instance['button_width_mobile'] ) ) {
			$this->add_render_attribute( 'submit-group', 'class', 'elementor-sm-' . $instance['button_width_mobile'] );
		}

		if ( ! empty( $instance['button_size'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $instance['button_size'] );
		}

		if ( ! empty( $instance['button_type'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-button-' . $instance['button_type'] );
		}

		if ( $instance['button_hover_animation'] ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $instance['button_hover_animation'] );
		}

		?>
		<form class="<?php echo $this->get_name(); ?> sjea-elementor-form" method="post">
			
			<input type="hidden" name="action" value="sjea_add_subscriber" />
			<input type="hidden" name="post_id" value="<?php echo get_the_ID() ?>" />
			<input type="hidden" name="form_id" value="<?php echo $this->get_id() ?>" />
			<input type="hidden" name="form_campaign" value="<?php echo $instance['form_campaign'] ?>" />

			<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
				<?php
				foreach ( $instance['form_fields'] as $item_index => $item ) :
					$item['input_size'] = $instance['input_size'];
					$this->form_fields_render_attributes( $item_index, $instance, $item );

					$item = apply_filters( 'elementor_pro/forms/render/item', $item, $item_index, $this );
				?>
				<div <?php echo $this->get_render_attribute_string( 'field-group' . $item_index ); ?>>
					<?php
					if ( $item['field_label'] ) {
						echo '<label ' . $this->get_render_attribute_string( 'label' . $item_index ) . '>' . $item['field_label'] . '</label>';
					}

					switch ( $item['field_type'] ) :
						case 'textarea':
							echo $this->make_textarea_field( $item, $item_index );
							break;

						case 'select':
							echo $this->make_select_field( $item, $item_index );
							break;

						case 'text':
						case 'email':
						case 'url':
						case 'password':
						case 'tel':
						case 'number':
						case 'search':
							$this->add_render_attribute( 'input' . $item_index, 'class', 'elementor-field-textual' );
							echo '<input size="1" ' . $this->get_render_attribute_string( 'input' . $item_index ) . '>';
							break;
						default:
							do_action( 'elementor_pro/forms/render_field/' . $item['field_type'],  $item, $item_index, $this );
					endswitch;
					?>
				</div>
				<?php endforeach; ?>
				<div <?php echo $this->get_render_attribute_string( 'submit-group' ); ?>>
					<button type="submit" <?php echo $this->get_render_attribute_string( 'button' ); ?>>
						<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); // TODO: what to do about content-wrapper ?>>
							<?php if ( ! empty( $instance['button_icon'] ) ) : ?>
								<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
									<i class="<?php echo esc_attr( $instance['button_icon'] ); ?>"></i>
								</span>
							<?php endif;
							if ( ! empty( $instance['button_text'] ) ) : ?>
								<span class="elementor-button-text"><?php echo $instance['button_text']; ?></span>
							<?php endif; ?>
						</span>
					</button>
				</div>
			</div>
		</form>
	<?php
	}

	protected function _content_template() {
		?>
		<form class="sjea-elementor-form">
			<div class="sjea-el-form-fields-wrapper elementor-labels-{{settings.label_position}}">
				<#
					for ( var i in settings.form_fields ) {
						var item = settings.form_fields[ i ];
						item = elementor.hooks.applyFilters( 'elementor_pro/forms/content_template/item', item, i, settings );

						var options = item.field_options ? item.field_options.split( '\n' ) : [],
							itemClasses = _.escape( item.css_classes ),
							labelVisibility = '',
							placeholder = '',
							required = '',
							inputField = '',
							fieldGroupClasses = 'sjea-el-field-group elementor-column elementor-field-type-' + item.field_type;

						fieldGroupClasses += ' elementor-col-' + ( ( '' !== item.width ) ? item.width : '100' );

						if ( item.width_tablet ) {
							fieldGroupClasses += ' elementor-md-' + item.width_tablet;
						}

						if ( item.width_mobile ) {
							fieldGroupClasses += ' elementor-sm-' + item.width_mobile;
						}

						if ( ! settings.show_labels ) {
							item.field_label = false;
						}

						if ( item.required ) {
							required = 'required';
							fieldGroupClasses += ' elementor-field-required';

							if ( settings.mark_required ) {
								fieldGroupClasses += ' elementor-mark-required';
							}
						}

						if ( item.placeholder ) {
							placeholder = 'placeholder="' + _.escape( item.placeholder ) + '"';
						}

						switch ( item.field_type ) {
							case 'textarea':
								inputField = '<textarea class="elementor-field elementor-field-textual elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" rows="' + item.rows + '" ' + required + ' ' + placeholder + '></textarea>';
								break;

							case 'select':
								if ( options ) {
									inputField = '<div class="elementor-field elementor-select-wrapper ' + itemClasses + '">';
									inputField += '<select class="elementor-field-textual elementor-size-' + settings.input_size + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' >';
									for ( var x in options ) {
										inputField += '<option value="' + options[x] + '">' + options[x] + '</option>';
									}
									inputField += '</select></div>';
								}
								break;

								<?php /*
								case 'recaptcha':
									inputField += '<div class="elementor-field">';
									<?php if ( Recaptcha_Handler::is_enabled() ) {  ?>
										inputField += '<div class="elementor-g-recaptcha' + itemClasses + '" data-sitekey="<?php echo Recaptcha_Handler::get_site_key() ?>" data-theme="' + item.recaptcha_style + '" data-size="' + item.recaptcha_size + '"></div>';
									<?php } else { ?>
										inputField += '<div class="elementor-alert"><?php echo esc_attr( Recaptcha_Handler::get_setup_message() ); ?></div>';
									<?php } ?>
									inputField += '</div>';
								break;*/ ?>
							case 'text':
							case 'email':
							case 'url':
							case 'password':
							case 'tel':
							case 'number':
							case 'search':
								itemClasses = 'elementor-field-textual ' + itemClasses;
								inputField = '<input size="1" type="' + item.field_type + '" class="elementor-field elementor-size-' + settings.input_size + ' ' + itemClasses + '" name="form_field_' + i + '" id="form_field_' + i + '" ' + required + ' ' + placeholder + ' >';
								break;
							default:
								inputField = elementor.hooks.applyFilters( 'elementor_pro/forms/content_template/field/' + item.field_type, '', item, i, settings );
						}

						if ( inputField ) {
							#>
							<div class="{{ fieldGroupClasses }}">

								<# if ( item.field_label ) { #>
									<label class="elementor-field-label" for="form_field_{{ i }}" {{{ labelVisibility }}}>{{{ item.field_label }}}</label>
								<# } #>

								{{{ inputField }}}
							</div>
							<#
						}
					}


					var buttonClasses = 'sjea-el-field-group elementor-column elementor-field-type-submit';

					buttonClasses += ' elementor-col-' + ( ( '' !== settings.button_width ) ? settings.button_width : '100' );

					if ( settings.button_width_tablet ) {
						buttonClasses += ' elementor-md-' + settings.button_width_tablet;
					}

					if ( settings.button_width_mobile ) {
						buttonClasses += ' elementor-sm-' + settings.button_width_mobile;
					}

					#>

					<div class="{{ buttonClasses }}">
						<button type="submit" class="elementor-button elementor-size-{{ settings.button_size }} elementor-button-{{ settings.button_type }} elementor-animation-{{ settings.button_hover_animation }}">
							<span>
								<# if ( settings.button_icon ) { #>
									<span class="elementor-button-icon elementor-align-icon-{{ settings.button_icon_align }}">
										<i class="{{ settings.button_icon }}"></i>
									</span>
								<# } #>

								<# if ( settings.button_text ) { #>
									<span class="elementor-button-text">{{{ settings.button_text }}}</span>
								<# } #>
							</span>
						</button>
					</div>
			</div>
		</form>
		<?php
	}

	public function render_plain_content() {}

	private function get_attribute_name( $item_index ) {
		return "form_fields[{$item_index}]";
	}

	private function get_attribute_id( $item_index ) {
		return 'form-field-' . $item_index;
	}

	// protected function render() {
	// 	$node_id = $this->get_id();
	// 	$name = $this->get_name();
 //        $settings = $this->get_settings();
 //        echo "Subscribe Form";
	// 	// SJEaModuleScripts::sjea_image_separator();
		
 //  //       if ( Plugin::instance()->editor->is_edit_mode() ) {
			
	// 	// 	SJEaModuleScripts::sjea_image_separator_dynamic( $node_id, $settings, true );
			
	// 	// 	echo "<div style='text-align:center;'><span>Click here to edit image-separator-".$node_id." module.</span>";
	// 	// 	echo "<br><span>This message will not show in frontend.</span></div>";
	// 	// }
 //  //       //var_dump( Plugin::instance()->preview->is_preview_mode() );
		
	// 	// include SJ_EA_DIR . 'modules/sjea-subscribe-form/includes/frontend.php';
	// }

	
}
Plugin::instance()->widgets_manager->register_widget_type( new Widget_SJEaSubscribeForm() );