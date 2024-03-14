<?php

namespace Elementor;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Thim_Ekit_Widget_Social_Share extends Thim_Ekit_Widget_Social {

	public function get_name() {
		return 'thim-ekits-social-share';
	}

	public function get_title() {
		return esc_html__( 'Social Share', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-share';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return [
			'thim',
			'social',
			'social-share'
		];
	}

	protected function register_controls() {
		// start content section for social media
		$this->start_controls_section(
			'social_icon_section_tab_content',
			array(
				'label' => esc_html__( 'Social Icons', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'social_icon_style',
			array(
				'label'   => esc_html__( 'Choose Style', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => array(
					'icon'   => esc_html__( 'Icon', 'thim-elementor-kit' ),
					'text'   => esc_html__( 'Text', 'thim-elementor-kit' ),
					'both'   => esc_html__( 'Icon & Both', 'thim-elementor-kit' ),
					'toggle' => esc_html__( 'Toggle', 'thim-elementor-kit' ),
				),
			)
		);

		$this->add_control(
			'social_icon_style_icon_position',
			array(
				'label'     => esc_html__( 'Icon Position', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'before',
				'options'   => array(
					'before' => esc_html__( 'Before', 'thim-elementor-kit' ),
					'after'  => esc_html__( 'After', 'thim-elementor-kit' ),
				),
				'condition' => array(
					'social_icon_style' => 'both',
				),
			)
		);

		$this->add_responsive_control(
			'social_icon_icon_padding_right',
			array(
				'label'      => esc_html__( 'Spacing Right', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 5,
				),
				'selectors'  => array(
					'{{WRAPPER}} a > i' => 'padding-right: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'social_icon_style'               => 'both',
					'social_icon_style_icon_position' => 'before',
				),
			)
		);

		$this->add_responsive_control(
			'social_icon_icon_padding_left',
			array(
				'label'      => esc_html__( 'Spacing Left', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 5,
				),
				'selectors'  => array(
					'{{WRAPPER}} a > i' => 'padding-left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'social_icon_style'               => 'both',
					'social_icon_style_icon_position' => 'after',
				),
			)
		);

		$this->add_responsive_control(
			'socialicon_list_align',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .thim-social-media' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'social_icon_style!' => 'toggle',
				),
			)
		);

		$this->add_control(
			'share_label',
			array(
				'label'     => esc_html__( 'Label', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Share',
				'condition' => array(
					'social_icon_style' => 'toggle',
				),
			)
		);
		$this->add_control(
			'share_icon',
			array(
				'label'       => esc_html__( 'Choose Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => array(
					'social_icon_style' => 'toggle',
				),
			)
		);
		$this->add_control(
			'show_url_copy',
			array(
				'label'        => esc_html__( 'Copy Url', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'thim-elementor-kit' ),
				'label_off'    => esc_html__( 'Hide', 'thim-elementor-kit' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'social_icon_style' => 'toggle',
				),
			)
		);

		$this->register_social_repeater_controls();

		$this->end_controls_section();
		// Style for Toggle
		$this->register_style_social_toggle();
		$this->_register_style_social_form_toggle();
		$this->register_style_controls();
		$this->_register_style_social_form_toggle_copy_url();
	}

	protected function register_style_social_toggle() {
		$this->start_controls_section(
			'section_toggle_content',
			array(
				'label'     => esc_html__( 'Toggle Lable', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'social_icon_style' => 'toggle',
				),
			)
		);
		$this->add_responsive_control(
			'toggle_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 5,
					),
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .share-toggle-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .share-toggle-icon svg' => 'max-width: {{SIZE}}{{UNIT}};',
				),

			)
		);
		$this->add_responsive_control(
			'toggle_icon_space',
			array(
				'label'      => esc_html__( 'Icon Space', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 5,
					),
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .share-toggle-icon i'   => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .share-toggle-icon svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				),

			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toggle_label_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .share-label',
			]
		);
		$this->add_responsive_control(
			'toggle_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .share-toggle-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border_toggle',
				'selector' => '{{WRAPPER}} .share-toggle-icon',
				'exclude'  => [ 'color' ],
			)
		);
		$this->add_responsive_control(
			'toggle_border_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .share-toggle-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->start_controls_tabs( 'toggle_label_tabs_colors' );

		$this->start_controls_tab(
			'toggle_label_tabs_normal_colors',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' )
			)
		);
		$this->add_control(
			'toggle_label_color',
			[
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .share-toggle-icon' => 'color: {{VALUE}};'
				],
			]
		);
		$this->add_control(
			'toggle_label_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .share-toggle-icon' => 'background: {{VALUE}};'
				],
			]
		);
		$this->add_control(
			'toggle_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3b5998',
				'selectors' => array(
					'{{WRAPPER}} .share-toggle-icon i'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .share-toggle-icon svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'toggle_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'border_toggle_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .share-toggle-icon' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_label_tabs_hover_colors',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' )
			)
		);
		$this->add_control(
			'toggle_label_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .share-toggle-icon:hover' => 'color: {{VALUE}};'
				],
			]
		);
		$this->add_control(
			'toggle_label_bg_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .share-toggle-icon:hover' => 'background: {{VALUE}};'
				],
			]
		);
		$this->add_control(
			'toggle_icon_color_hover',
			array(
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#3b5998',
				'selectors' => array(
					'{{WRAPPER}} .share-toggle-icon:hover i'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .share-toggle-icon:hover svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'toggle_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'border_toggle_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .share-toggle-icon:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function _register_style_social_form_toggle() {
		$this->start_controls_section(
			'section_toggle_form',
			array(
				'label'     => esc_html__( 'Toggle Form', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'social_icon_style' => 'toggle',
				),
			)
		);
		$this->add_control(
			'toggle_form_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .content-widget-social-share' => 'background: {{VALUE}};'
				],
			]
		);
		$this->add_responsive_control(
			'toggle_form_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .content-widget-social-share' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border_toggle_form',
				'selector' => '{{WRAPPER}} .content-widget-social-share',
				// 'exclude'  => [ 'color' ],
			)
		);
		$this->add_responsive_control(
			'toggle_form_border_radius',
			array(
				'label'      => esc_html__( 'Border radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'    => '0',
					'right'  => '0',
					'bottom' => '0',
					'left'   => '0',
					'unit'   => 'px',
				),
				'selectors'  => array(
					'{{WRAPPER}} .content-widget-social-share' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'toggle_form_box_shadow',
				'selector' => '{{WRAPPER}} .content-widget-social-share',
			]
		);
		$this->add_control(
			'toggle_form_header',
			[
				'label'     => esc_html__( 'Header', 'thim-elementor-kit' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toggle_form_header_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .title-share',
			]
		);
		$this->add_control(
			'toggle_form_header_color',
			[
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .title-share' => 'color: {{VALUE}};'
				],
			]
		);
		$this->add_responsive_control(
			'toggle_form_header_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .title-share' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'toggle_form_divider_heading_style',
			array(
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Divider', 'thim-elementor-kit' ),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'toggle_form_divider_style',
			array(
				'label'     => esc_html__( 'Style', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					''       => esc_html__( 'None', 'thim-elementor-kit' ),
					'solid'  => esc_html__( 'Solid', 'thim-elementor-kit' ),
					'double' => esc_html__( 'Double', 'thim-elementor-kit' ),
					'dotted' => esc_html__( 'Dotted', 'thim-elementor-kit' ),
					'dashed' => esc_html__( 'Dashed', 'thim-elementor-kit' ),
					'groove' => esc_html__( 'Groove', 'thim-elementor-kit' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .title-share' => 'border-bottom-style: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'toggle_form_divider_style_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .title-share' => 'border-bottom-color: {{VALUE}};',
				),
				'condition' => array(
					'toggle_form_divider_style!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'toggle_form_divider_style_height',
			array(
				'label'     => esc_html__( 'Height', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'default'   => array(
					'size' => 1,
				),
				'condition' => array(
					'toggle_form_divider_style!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .title-share' => 'border-bottom-width: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'toggle_form_divider_style_gap',
			array(
				'label'      => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'size_units' => array( '%', 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .title-share' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function _register_style_social_form_toggle_copy_url() {
		$this->start_controls_section(
			'section_toggle_form_copy_url',
			array(
				'label'     => esc_html__( 'Toggle Copy Url', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'social_icon_style' => 'toggle',
					'show_url_copy'     => 'yes'
				),
			)
		);
		$this->add_responsive_control(
			'toggle_copy_url_space',
			array(
				'label'      => esc_html__( 'Space', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 5,
					),
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .clipboard-post' => 'margin-top: {{SIZE}}{{UNIT}};',
				),

			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toggle_copy_url_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .clipboard-post',
			]
		);
		$this->add_control(
			'toggle_copy_url_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .clipboard-post' => 'color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			'toggle_copy_url_bg',
			array(
				'label'     => esc_html__( 'Background', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .clipboard-post' => 'background: {{VALUE}}',
				),
			)
		);
		$this->add_responsive_control(
			'toggle_copy_url_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}  .clipboard-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border_toggle_copy_url_',
				'selector' => '{{WRAPPER}} .clipboard-post',
			)
		);
		$this->add_control(
			'border_radius_toggle_copy_url_',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .clipboard-post' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'toggle_copy_url_button_heading',
			[
				'label'     => esc_html__( 'Button', 'thim-elementor-kit' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'after',
			]
		);
		$this->add_responsive_control(
			'toggle_copy_url_button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .btn-clipboard' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'toggle_copy_url_button_space',
			array(
				'label'      => esc_html__( 'Space', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min' => 50,
						'max' => 500,
					),
				),
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .btn-clipboard' => 'margin-left: {{SIZE}}{{UNIT}} !important;',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'border_toggle_copy_url_button_',
				'selector' => '{{WRAPPER}} .btn-clipboard',
			)
		);
		$this->add_control(
			'border_radius_toggle_copy_url_button_',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .btn-clipboard' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'toggle_toggle_copy_url_button_',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .btn-clipboard',
			]
		);
		$this->start_controls_tabs( 'toggle_copy_url_button_tabs_colors' );

		$this->start_controls_tab(
			'toggle_copy_url_button_tabs_normal_colors',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' )
			)
		);
		$this->add_control(
			'toggle_copy_url_button__color',
			[
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .btn-clipboard' => 'color: {{VALUE}};'
				],
			]
		);
		$this->add_control(
			'toggle_copy_url_button_bg_color',
			[
				'label'     => esc_html__( 'Background', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .btn-clipboard' => 'background: {{VALUE}};'
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'toggle_copy_url_button_tabs_hover_colors',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' )
			)
		);
		$this->add_control(
			'toggle_copy_url_button_color_hover',
			[
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .btn-clipboard:hover' => 'color: {{VALUE}};'
				],
			]
		);
		$this->add_control(
			'toggle_copy_url_button_bgcolor_hover',
			[
				'label'     => esc_html__( 'Background', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .btn-clipboard:hover' => 'background: {{VALUE}};'
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	protected function social_icon_link_type( $social_repeater ) {
		$social_repeater->add_control(
			'social_key',
			array(
				'label'   => esc_html__( 'Type', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => array(
					'facebook'  => esc_html__( 'Facebook', 'thim-elementor-kit' ),
					'twitter'   => esc_html__( 'Twitter', 'thim-elementor-kit' ),
					'linkedin'  => esc_html__( 'Linkedin', 'thim-elementor-kit' ),
					'pinterest' => esc_html__( 'Pinterest', 'thim-elementor-kit' ),
					'instagram' => esc_html__( 'Instagram', 'thim-elementor-kit' ),
				),
			)
		);
	}

	function thim_ekit_social_value_default() {
		return array(
			array(
				'social_icon_icons'            => array(
					'value'   => 'fab fa-facebook-f',
					'library' => 'Font Awesome 5 Brands',
				),
				'social_icon_label'            => 'Facebook',
				'social_icon_icon_hover_color' => '#3b5998',
				'social_key'                   => 'facebook',
			),
			array(
				'social_icon_icons'            => array(
					'value'   => 'fab fa-twitter',
					'library' => 'Font Awesome 5 Brands',
				),
				'social_icon_label'            => 'Twitter',
				'social_icon_icon_hover_color' => '#1da1f2',
				'social_key'                   => 'twitter',
			),
			array(
				'social_icon_icons'            => array(
					'value'   => 'fab fa-linkedin-in',
					'library' => 'Font Awesome 5 Brands',
				),
				'social_icon_label'            => 'LinkedIn',
				'social_icon_icon_hover_color' => '#0077b5',
				'social_key'                   => 'linkedin',
			),
		);
	}

	protected function render() {
		$settings = $this->get_settings();
		?>
		<div class="social-swapper social-share-<?php echo esc_attr( $settings['social_icon_style'] ); ?>">
			<?php
			if ( $settings['social_icon_style'] == 'toggle' ) {
				echo '<div class="share-toggle-icon">';
				Icons_Manager::render_icon( $settings['share_icon'], array( 'aria-hidden' => 'true' ) );
				echo '<label class="share-label">' . $settings['share_label'] . '</label>';
				echo '</div>';
				echo '<div class="wrapper-content-widget"><div class="content-widget-social-share">';
				if ( $settings['share_label'] ) {
					echo '<h4 class="title-share">' . $settings['share_label'] . '</h4>';
				}
				$this->render_raw();
				if ( $settings['show_url_copy'] == 'yes' ) {
					echo '<div class="clipboard-post"><input class="clipboard-value" type="text" value="' . get_permalink() . '">';
					echo '<button class="btn-clipboard" data-copied="' . esc_html__( 'Copied!', 'thim-elementor-kit' ) . '">' . esc_html__( 'Copy', 'thim-elementor-kit' ) . '
							<span class="tooltip">' . esc_html__( 'Copy to Clipboard', 'thim-elementor-kit' ) . '</span>
						</button>';
					echo '</div>';
				}
				echo '</div></div>';
			} else {
				$this->render_raw();
			}
			?>
		</div>
		<?php
	}

	protected function render_raw() {
		$settings = $this->get_settings();
		?>
		<ul class="thim-social-media">
			<?php foreach ( $settings['social_icon_add_icons'] as $key => $icon ) : ?>
				<?php
				if ( $icon['social_icon_icons'] != '' ) :

					switch ( $icon['social_key'] ) {
						case 'facebook':
							$link_share = 'https://www.facebook.com/sharer.php?u=' . urlencode( get_permalink() );
							break;
						case'twitter':
							$link_share = 'https://twitter.com/share?url=' . urlencode( get_permalink() ) . '&amp;text=' . rawurlencode( esc_attr( get_the_title() ) );
							break;
						case'pinterest':
							$link_share = 'http://pinterest.com/pin/create/button/?url=' . urlencode( get_permalink() ) . '&amp;description=' . rawurlencode( esc_attr( get_the_excerpt() ) ) . '&amp;media=' . urlencode( wp_get_attachment_url( get_post_thumbnail_id() ) ) . ' onclick="window.open(this.href); return false;"';
							break;
						case'linkedin':
							$link_share = 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( get_permalink() ) . '&title=' . rawurlencode( esc_attr( get_the_title() ) ) . '&summary=&source=' . rawurlencode( esc_attr( get_the_excerpt() ) );
							break;
					}
					?>
					<li class="elementor-repeater-item-<?php echo esc_attr( $icon['_id'] ); ?>">
						<a target="_blank" href="<?php echo $link_share; ?>"
						   title="<?php echo esc_html( $icon['social_icon_label'] ); ?>">
							<?php if ( $settings['social_icon_style'] != 'text' && $settings['social_icon_style_icon_position'] == 'before' ) : ?>
								<?php Icons_Manager::render_icon( $icon['social_icon_icons'], array( 'aria-hidden' => 'true' ) ); ?>
							<?php endif; ?>

							<?php if ( $settings['social_icon_style'] == 'text' || $settings['social_icon_style'] == 'both' ) : ?>
								<?php echo esc_html( $icon['social_icon_label'] ); ?>
							<?php endif; ?>

							<?php if ( $settings['social_icon_style'] != 'text' && $settings['social_icon_style_icon_position'] == 'after' ) : ?>
								<?php Icons_Manager::render_icon( $icon['social_icon_icons'], array( 'aria-hidden' => 'true' ) ); ?>
							<?php endif; ?>
						</a>
						<?php
						if ( $settings['social_icon_style'] == 'toggle' && $icon['social_icon_label'] ) {
							echo '<span class="text-label">' . esc_html( $icon['social_icon_label'] ) . '</span>';
						}
						?>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		<?php
	}
}
