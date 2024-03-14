<?php
/**
 * GravityForms widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

defined( 'ABSPATH' ) || die();

class GravityForms extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Gravity Forms', 'skt-addons-elementor' );
	}

	public function get_custom_help_url() {
		return '#';
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-form';
	}

	public function get_keywords() {
		return [ 'gravity forms', 'form', 'contact', 'advanced', 'ninja' ];
	}

	// Whether the reload preview is required or not.
	public function is_reload_preview_required() {
		return true;
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {

		$this->start_controls_section(
			'_section_gravityforms',
			[
				'label' => skt_addons_elementor_is_gravityforms_activated() ? __( 'Gravity Forms', 'skt-addons-elementor' ) : __( 'Missing Notice',
					'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		if ( ! skt_addons_elementor_is_gravityforms_activated() ) {

			$this->add_control(
				'_gravityforms_missing_notice',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => sprintf(
						__( 'Hello %1$s, looks like Gravity Forms is missing in your site. Please install/activate Gravity Forms. Make sure to refresh this page after installation or activation.', 'skt-addons-elementor' ),
						skt_addons_elementor_get_current_user_display_name()
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
				]
			);

		}else{

			$this->add_control(
				'form_id',
				[
					'label' => __( 'Select Your Form', 'skt-addons-elementor' ),
					'type' => Controls_Manager::SELECT,
					'label_block' => true,
					'options' => ['' => __( '', 'skt-addons-elementor' ) ] + \skt_addons_elementor_get_gravity_forms(),
				]
			);

			$this->add_control(
				'form_title_show',
				[
					'label' => __( 'Form Title', 'skt-addons-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'separator' => 'before',
					'label_on' => __( 'Show', 'skt-addons-elementor' ),
					'label_off' => __( 'Hide', 'skt-addons-elementor' ),
					'return_value' => 'yes',
					'default' => 'yes',
				]
			);

			$this->add_control(
				'ajax',
				[
					'label' => __( 'Enable Ajax Submit', 'skt-addons-elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'Yes', 'skt-addons-elementor' ),
					'label_off' => __( 'No', 'skt-addons-elementor' ),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);

		}

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__form_fields_style_controls();
		$this->__form_fields_label_style_controls();
		$this->__form_fields_submit_style_controls();
		$this->__form_fields_break_style_controls();
		$this->__form_fields_list_style_controls();
	}

	protected function __form_fields_style_controls() {

		$this->start_controls_section(
			'_section_fields_style',
			[
				'label' => __( 'Form Fields', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'large_field_width',
			[
				'label' => __( 'Large Field Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 800,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gform_body .gfield input.large' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gform_body .gfield  textarea.large' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'field_margin',
			[
				'label' => __( 'Field Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gform_body .gform_fields .gfield' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'field_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gform_body .gfield .ginput_container:not(.ginput_container_fileupload) > input:not(.ginput_quantity)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_body .gfield .ginput_container.ginput_complex input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_body .gfield .ginput_container.ginput_complex input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_body .gfield textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'field_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gfield .ginput_container:not(.ginput_container_fileupload) > input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gfield .ginput_container.ginput_complex input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_body .gfield textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'field_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .gfield .ginput_container > input, {{WRAPPER}} .gform_body .gfield textarea, {{WRAPPER}} .gfield .ginput_container.ginput_complex input',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_control(
			'field_textcolor',
			[
				'label' => __( 'Field Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gfield .ginput_container > input' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gfield .ginput_container.ginput_complex input' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_body .gfield textarea' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_body .gfield select' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gfield_list tbody td input' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ginput_container_address input' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'field_placeholder_color',
			[
				'label' => __( 'Field Placeholder Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ::-webkit-input-placeholder'	=> 'color: {{VALUE}};',
					'{{WRAPPER}} ::-moz-placeholder'			=> 'color: {{VALUE}};',
					'{{WRAPPER}} ::-ms-input-placeholder'		=> 'color: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_field_state' );

		$this->start_controls_tab(
			'tab_field_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'field_border',
				'selector' => '{{WRAPPER}} .gfield .ginput_container:not(.ginput_container_fileupload) > input,
				{{WRAPPER}} .gfield .ginput_complex input,
				{{WRAPPER}} .gfield .ginput_container_address input,
				{{WRAPPER}} .gfield_list_cell input,
				{{WRAPPER}} .gfield .ginput_container select,
				{{WRAPPER}} .gform_body .gfield textarea',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'field_box_shadow',
				'selector' => '{{WRAPPER}} .gfield .ginput_container:not(.ginput_container_fileupload) > input,
				{{WRAPPER}} .gfield .ginput_complex input,
				{{WRAPPER}} .gfield .ginput_container_address input,
				{{WRAPPER}} .gform_body .gfield textarea',
			]
		);

		$this->add_control(
			'field_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gfield .ginput_container:not(.ginput_container_fileupload) > input' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gfield .ginput_complex input' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gfield .ginput_container_address input' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gfield .ginput_container_list input' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gform_body .gfield textarea' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gform_body .gfield select' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_field_focus',
			[
				'label' => __( 'Focus', 'skt-addons-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'field_focus_border',
				'selector' => '{{WRAPPER}} .gfield .ginput_container > input:focus,
				{{WRAPPER}} .gfield .ginput_complex input:focus,
				{{WRAPPER}} .gfield .ginput_container_address input:focus,
				{{WRAPPER}} .gfield_list_cell input:focus,
				{{WRAPPER}} .gform_body .gfield textarea:focus'
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'field_focus_box_shadow',
				'selector' => '{{WRAPPER}} .gfield .ginput_container > input:focus,
				{{WRAPPER}} .gfield .ginput_complex input:focus,
				{{WRAPPER}} .gfield .ginput_container_address input:focus,
				{{WRAPPER}} .gform_body .gfield textarea:focus',
			]
		);

		$this->add_control(
			'field_focus_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gfield .ginput_container > input:focus' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gfield .ginput_complex input:focus' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gform_body .gfield textarea:focus' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __form_fields_label_style_controls() {

		$this->start_controls_section(
			'form_fields_label_section',
			[
				'label' => __( 'Form Fields Label', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'label_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gform_body .gfield .gfield_label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'label_padding',
			[
				'label' => __( 'Label Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gform_body .gfield .gfield_label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.gfield_list thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sub_label_margin',
			[
				'label' => __( 'Sub Label Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .gform_body .gfield .gfield_description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Label Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .gform_body .gfield .gfield_label, {{WRAPPER}} table.gfield_list thead th',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_label_typography',
				'label' => __( 'Sub Label Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .gform_body .gfield .gfield_description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
			'label_color',
			[
				'label' => __( 'Label Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_body .gfield .gfield_label' => 'color: {{VALUE}}',
					'{{WRAPPER}} .gform_body .gfield .ginput_complex label' => 'color: {{VALUE}}',
					'{{WRAPPER}} table.gfield_list thead th' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sub_label_color',
			[
				'label' => __( 'Sub Label Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_body .gfield .gfield_description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'requered_label',
			[
				'label' => __( 'Required Label Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_body .gfield .gfield_label .gfield_required' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __form_fields_submit_style_controls() {

		$this->start_controls_section(
			'form_fields_submit_sectionsubmit',
			[
				'label' => __( 'Submit Button', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'submit_btn_width',
			[
				'label' => __( 'Button Full Width?', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => '',
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label' => __( 'Button Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'condition' => [
					'submit_btn_width' => 'yes'
				],
				'default' => [
					'unit' => '%',
					'size' => 100
				],
				'range' => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 800,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gform_button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'submit_btn_position',
			[
				'label' => __( 'Button Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'condition' => [
					'submit_btn_width' => ''
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gform_footer' => 'text-align: {{Value}};',
				],
			]
		);

		$this->add_responsive_control(
			'submit_margin',
			[
				'label' => __( 'Margin', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gform_footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'submit_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gform_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'submit_typography',
				'selector' => '{{WRAPPER}} .gform_wrapper .gform_button',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'submit_border',
				'selector' => '{{WRAPPER}} .gform_wrapper .gform_button',
			]
		);

		$this->add_control(
			'submit_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gform_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'submit_box_shadow',
				'selector' => '{{WRAPPER}} .gform_wrapper .gform_button',
				'separator' => 'after'
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'submit_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gform_button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'submit_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gform_button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'submit_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gform_button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_wrapper .gform_button:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'submit_hover_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gform_button:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gform_wrapper .gform_button:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'submit_hover_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .gform_button:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .gform_wrapper .gform_button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __form_fields_break_style_controls() {

		$this->start_controls_section(
			'form_fields_break_section',
			[
				'label' => __( 'Break', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'section_break',
			[
				'label' => __( 'Section Break', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'section_break_title_typography',
				'label' => __( 'Title Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .gsection .gsection_title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'section_break_description_typography',
				'label' => __( 'Description Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .gsection .gsection_description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
			]
		);

		$this->start_controls_tabs( 'tabs_section_break_style' );
		$this->start_controls_tab(
			'section_break__title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'section_break_title_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsection .gsection_title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'section_break_tab_description',
			[
				'label' => __( 'Description', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'section_break_description_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gsection .gsection_description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'page_break',
			[
				'label' => __( 'Page Break', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'page_break_progress_bar_color',
			[
				'label' => __( 'Progress bar background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_wrapper .percentbar_blue' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'page_break_button_paddding',
			[
				'label' => __( 'Button Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gform_next_button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_previous_button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'page_break_button_box_shadow',
				'label' => __( 'Button Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .gform_next_button.button, {{WRAPPER}} .gform_previous_button.button',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'page_break_button_border',
				'selector' => '{{WRAPPER}} .gform_next_button.button, {{WRAPPER}} .gform_previous_button.button',
			]
		);

		$this->add_control(
			'page_break_button_border_radius',
			[
				'label' => __( 'Button Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .gform_next_button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_previous_button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'page_break_button_typography',
				'label' => __( 'Button Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .gform_next_button.button, {{WRAPPER}} .gform_previous_button.button',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
			]
		);

		$this->start_controls_tabs( 'page_break_tabs_button_style' );

		$this->start_controls_tab(
			'page_break_tab_button_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'page_break_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .gform_next_button.button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'page_break_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_next_button.button' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'page_break_tab_button_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'page_break_hover_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_next_button.button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_next_button.button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'page_break_hover_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_next_button.button:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gform_next_button.button:focus' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'page_break_hover_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gform_next_button.button:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .gform_next_button.button:focus' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __form_fields_list_style_controls() {

		$this->start_controls_section(
			'form_fields_list_section',
			[
				'label' => __( 'List', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'list_button_size',
			[
				'label' => __( 'Button Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					]
				],
				'selectors' => [
					'{{WRAPPER}} .gfield_list .gfield_list_icons img' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'list_even_background_color',
			[
				'label' => __( 'Background Color (Even)', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gfield_list .gfield_list_row_even td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'list_odd_background_color',
			[
				'label' => __( 'Background Color (Odd)', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .gfield_list .gfield_list_row_odd td' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! skt_addons_elementor_is_gravityforms_activated() ) {
			skt_addons_elementor_show_plugin_missing_alert( __( 'Gravity Forms', 'skt-addons-elementor' ) );
			return;
		}

		$settings = $this->get_settings_for_display();
		$ajax = false;
		if( 'yes' === $settings['ajax'] ){
			$ajax = true;
		}
		if ( ! empty( $settings['form_id'] ) ) {
			gravity_form( $settings['form_id'], $settings['form_title_show'], true, false, null, $ajax );
		}
	}
}