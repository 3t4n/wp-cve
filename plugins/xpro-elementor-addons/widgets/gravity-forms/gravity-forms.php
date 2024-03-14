<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use function xpro_elementor_get_gravity_forms;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Gravity_Forms extends Widget_Base {

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
		return 'xpro-gravity-from';
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
		return __( 'Gravity Form', 'xpro-elementor-addons' );
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
		return array( 'form', 'forms', 'contact', 'gravity', 'contact form' );
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
			'section_general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		if ( ! class_exists( '\GFForms' ) ) {
			$this->add_control(
				'_gravity_missing_notice',
				array(
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => sprintf(
					/* translators: %s: Name. */
						__( 'Looks like %1$s is missing in your site. Please install/activate Gravity Forms. Make sure to refresh this page after installation or activation.', 'xpro-elementor-addons' ),
						'Gravity Form'
					),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-danger',
				)
			);

			$this->end_controls_section();

			return;
		}

		$this->add_control(
			'form_id',
			array(
				'label'       => __( 'Select Your Form', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => array( '' => __( 'Select Form', 'xpro-elementor-addons' ) ) + xpro_elementor_get_gravity_forms(),
			)
		);

		$this->add_control(
			'form_title_show',
			array(
				'label'        => __( 'Show Title', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'separator'    => 'before',
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'form_desc_show',
			array(
				'label'        => __( 'Show Description', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'ajax',
			array(
				'label'        => __( 'Ajax Submit', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'xpro-elementor-addons' ),
				'label_off'    => __( 'No', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->end_controls_section();

		//Fields
		$this->start_controls_section(
			'section_fields_style',
			array(
				'label' => __( 'Field', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'large_field_width',
			array(
				'label'      => __( 'Large Field Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .gform_body .gfield input.large'     => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gform_body .gfield  textarea.large' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .gform_body .gfield  select.large'   => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'field_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .gfield .ginput_container > input, {{WRAPPER}} .gform_body .gfield textarea, {{WRAPPER}} .gfield .ginput_container.ginput_complex input',
				'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_TEXT
                ],
			)
		);

		$this->add_control(
			'field_textcolor',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gfield .ginput_container > input'              => 'color: {{VALUE}};',
					'{{WRAPPER}} .gfield .ginput_container.ginput_complex input' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_body .gfield textarea'                   => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_body .gfield select'                     => 'color: {{VALUE}};',
					'{{WRAPPER}} .gfield_list tbody td input'                    => 'color: {{VALUE}};',
					'{{WRAPPER}} .ginput_container_address input'                => 'color: {{VALUE}};',
				),
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
			'field_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gfield .ginput_container:not(.ginput_container_fileupload) > input' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gfield .ginput_complex input'                                       => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gfield .ginput_container_address input'                             => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gfield .ginput_container_list input'                                => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gform_body .gfield textarea'                                        => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gform_body .gfield select'                                          => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'field_box_shadow',
				'selector' => '{{WRAPPER}} .gfield .ginput_container:not(.ginput_container_fileupload) > input,
				{{WRAPPER}} .gfield .ginput_complex input,
				{{WRAPPER}} .gfield .ginput_container_address input,
				{{WRAPPER}} .gform_body .gfield textarea',
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
			'field_focus_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gfield .ginput_container > input:focus' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gfield .ginput_complex input:focus'     => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .gform_body .gfield textarea:focus'      => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'field_focus_box_shadow',
				'selector' => '{{WRAPPER}} .gfield .ginput_container > input:focus,
				{{WRAPPER}} .gfield .ginput_complex input:focus,
				{{WRAPPER}} .gfield .ginput_container_address input:focus,
				{{WRAPPER}} .gform_body .gfield textarea:focus',
			)
		);

		$this->add_control(
			'field_focus_border',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gfield .ginput_container > input:focus,
				{{WRAPPER}} .gfield .ginput_complex input:focus,
				{{WRAPPER}} .gfield .ginput_container_address input:focus,
				{{WRAPPER}} .gfield_list_cell input:focus,
				{{WRAPPER}} .gform_body .gfield textarea:focus' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'field_border',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .gfield .ginput_container:not(.ginput_container_fileupload) > input,
				{{WRAPPER}} .gfield .ginput_complex input,
				{{WRAPPER}} .gfield .ginput_container_address input,
				{{WRAPPER}} .gfield_list_cell input,
				{{WRAPPER}} .gfield .ginput_container select,
				{{WRAPPER}} .gform_body .gfield textarea',
			)
		);

		$this->add_responsive_control(
			'field_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .gfield .ginput_container:not(.ginput_container_fileupload) > input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gfield .ginput_container.ginput_complex input'                      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_body .gfield textarea'                                        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'field_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .gform_body .gfield .ginput_container:not(.ginput_container_fileupload) > input:not(.ginput_quantity)'                                                                      => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_body .gfield .ginput_container.ginput_complex input'                                                                                                                 => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_body .gfield .ginput_container.ginput_complex input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_body .gfield textarea'                                                                                                                                               => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .gform_body .gform_fields .gfield' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Label
		$this->start_controls_section(
			'form_fields_label_section',
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
				'selector' => '{{WRAPPER}} .gform_body .gfield .gfield_label, {{WRAPPER}} table.gfield_list thead th',
				'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_TEXT
                ],
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gform_body .gfield .gfield_label'         => 'color: {{VALUE}}',
					'{{WRAPPER}} .gform_body .gfield .ginput_complex label' => 'color: {{VALUE}}',
					'{{WRAPPER}} table.gfield_list thead th'                => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'required_label',
			array(
				'label'     => __( 'Required Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gform_body .gfield .gfield_label .gfield_required' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'label_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .gform_body .gfield .gfield_label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.gfield_list thead th'        => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .gform_body .gfield .gfield_label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'sub_label_heading',
			array(
				'label'     => __( 'Sub Label', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'sub_label_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .gform_body .gfield .gfield_description',
				'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_TEXT
                ],
			)
		);

		$this->add_control(
			'sub_label_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gform_body .gfield .gfield_description' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'sub_label_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'separator'  => 'after',
				'selectors'  => array(
					'{{WRAPPER}} .gform_body .gfield .gfield_description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Submit
		$this->start_controls_section(
			'form_fields_submit_style',
			array(
				'label' => __( 'Submit', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'submit_typography',
				'selector' => '{{WRAPPER}} .gform_wrapper .gform_button',
				'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_ACCENT
                ],
			)
		);

		$this->add_control(
			'submit_btn_position',
			array(
				'label'     => __( 'Button Position', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
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
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .gform_wrapper .gform_footer' => 'text-align: {{Value}};',
				),
			)
		);

		$this->add_responsive_control(
			'button_width',
			array(
				'label'      => __( 'Button Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .gform_wrapper .gform_button' => 'width: {{SIZE}}{{UNIT}};',
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
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .gform_wrapper .gform_button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'submit_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gform_wrapper .gform_button' => 'background-color: {{VALUE}};',
				),
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
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gform_wrapper .gform_button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_wrapper .gform_button:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'submit_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gform_wrapper .gform_button:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gform_wrapper .gform_button:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'submit_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gform_wrapper .gform_button:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .gform_wrapper .gform_button:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'submit_border',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .gform_wrapper .gform_button',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'submit_box_shadow',
				'selector' => '{{WRAPPER}} .gform_wrapper .gform_button',
			)
		);

		$this->add_control(
			'submit_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .gform_wrapper .gform_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'submit_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .gform_wrapper .gform_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .gform_wrapper .gform_footer' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//List
		$this->start_controls_section(
			'form_fields_list_section',
			array(
				'label' => __( 'List', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'list_button_size',
			array(
				'label'      => __( 'Button Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 16,
				),
				'range'      => array(
					'px' => array(
						'min' => 5,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .gfield_list .gfield_list_icons img' => 'width: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);

		$this->add_control(
			'list_even_background_color',
			array(
				'label'     => __( 'Background Color (Even)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gfield_list .gfield_list_row_even td' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'list_odd_background_color',
			array(
				'label'     => __( 'Background Color (Odd)', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gfield_list .gfield_list_row_odd td' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		//Section
		$this->start_controls_section(
			'form_fields_break_section',
			array(
				'label' => __( 'Section', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'section_break_title_typography',
				'label'    => __( 'Title Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .gsection .gsection_title',
				'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_SECONDARY
                ],
			)
		);

		$this->add_control(
			'section_break_title_color',
			array(
				'label'     => __( 'Title Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gsection .gsection_title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'section_break_description_typography',
				'label'    => __( 'Description Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .gsection .gsection_description',
				'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_ACCENT
                ],
			)
		);

		$this->add_control(
			'section_break_description_color',
			array(
				'label'     => __( 'Description Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gsection .gsection_description' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		//Page
		$this->start_controls_section(
			'form_fields_break_page',
			array(
				'label' => __( 'Page', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'page_break_progress_bar_color',
			array(
				'label'     => __( 'Progress bar Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gform_wrapper .percentbar_blue' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'page_break_button_typography',
				'label'    => __( 'Button Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .gform_next_button.button, {{WRAPPER}} .gform_previous_button.button',
				'global' => [
	                'default' => Global_Typography::TYPOGRAPHY_ACCENT
                ],
			)
		);

		$this->start_controls_tabs( 'page_break_tabs_button_style' );

		$this->start_controls_tab(
			'page_break_tab_button_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'page_break_color',
			array(
				'label'     => __( 'Button Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .gform_next_button.button'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'page_break_bg_color',
			array(
				'label'     => __( 'Button Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gform_next_button.button'     => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'page_break_tab_button_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'page_break_hover_color',
			array(
				'label'     => __( 'Button Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gform_next_button.button:hover'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_next_button.button:focus'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button:focus' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'page_break_hover_bg_color',
			array(
				'label'     => __( 'Button Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gform_next_button.button:hover'     => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gform_next_button.button:focus'     => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'page_break_hover_border_color',
			array(
				'label'     => __( 'Button Border', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .gform_next_button.button:hover'     => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .gform_next_button.button:focus'     => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .gform_previous_button.button:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'page_break_button_border',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .gform_next_button.button, {{WRAPPER}} .gform_previous_button.button',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'page_break_button_box_shadow',
				'label'    => __( 'Button Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .gform_next_button.button, {{WRAPPER}} .gform_previous_button.button',
			)
		);

		$this->add_control(
			'page_break_button_border_radius',
			array(
				'label'      => __( 'Button Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .gform_next_button.button'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_previous_button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'page_break_button_padding',
			array(
				'label'      => __( 'Button Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .gform_next_button.button'     => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_previous_button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'page_break_button_margin',
			array(
				'label'      => __( 'Button Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .gform_next_button.button'     => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .gform_previous_button.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
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

		$settings = $this->get_settings_for_display();

		if ( ! class_exists( '\GFForms' ) ) {
			return;
		}

		$ajax = false;
		if ( 'yes' === $settings['ajax'] ) {
			$ajax = true;
		}
		if ( ! empty( $settings['form_id'] ) ) {
			gravity_form( $settings['form_id'], $settings['form_title_show'], $settings['form_desc_show'], false, null, $ajax );
		}
	}
}
