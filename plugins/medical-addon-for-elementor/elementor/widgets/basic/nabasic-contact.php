<?php
/*
 * Elementor Medical Addon for Elementor Contact Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_Contact extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_basic_contact';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Contact', 'medical-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-mail';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['namedical-basic-category'];
	}

	/**
	 * Register Medical Addon for Elementor Contact widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_contact',
			[
				'label' => esc_html__( 'Contact Options', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'upload_type',
			[
				'label' => __( 'Icon Type', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'image' => esc_html__( 'Image', 'medical-addon-for-elementor' ),
					'icon' => esc_html__( 'Icon', 'medical-addon-for-elementor' ),
				],
				'default' => 'icon',
			]
		);
		$this->add_control(
			'contact_image',
			[
				'label' => esc_html__( 'Upload Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'upload_type' => 'image',
				],
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your icon image.', 'medical-addon-for-elementor'),
			]
		);
		$this->add_control(
			'contact_icon',
			[
				'label' => esc_html__( 'Select Icon', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::ICON,
				'options' => NAMEP_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-phone',
				'condition' => [
					'upload_type' => 'icon',
				],
			]
		);
		$this->add_control(
			'contact_title',
			[
				'label' => esc_html__( 'Title Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Office Address', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type title text here', 'medical-addon-for-elementor' ),
				'label_block' => true,
			]
		);
		$repeater = new Repeater();

		$repeater->add_control(
			'list_title',
			[
				'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'list_text',
			[
				'label' => esc_html__( 'Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'list_link',
			[
				'label' => esc_html__( 'Text Link', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'listItems_groups',
			[
				'label' => esc_html__( 'Contacts', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'list_text' => esc_html__( 'Item #1', 'medical-addon-for-elementor' ),
					],

				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ list_text }}}',
			]
		);
		$this->add_responsive_control(
			'section_alignment',
			[
				'label' => esc_html__( 'Icon Alignment', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-left',
					],
					'center' => [
						'title' => esc_html__( 'Top', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-up',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-arrow-circle-right',
					],
				],
				'default' => 'left',
			]
		);
		$this->add_responsive_control(
			'text_alignment',
			[
				'label' => esc_html__( 'Text Alignment', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item' => 'text-align: {{VALUE}};',
				],
				'condition' => [
					'section_alignment' => 'center',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Section
		$this->start_controls_section(
			'sectn_style',
			[
				'label' => esc_html__( 'Section', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'info_padding',
			[
				'label' => __( 'Section Spacing', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'secn_style' );
			$this->start_controls_tab(
				'secn_normal',
				[
					'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Overlay Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-contact-item' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-contact-item',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-contact-item',
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'secn_hover',
				[
					'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'secn_bg_hover_color',
				[
					'label' => esc_html__( 'Overlay Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-contact-item.namep-hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_hover_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-contact-item.namep-hover',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_hover_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-contact-item.namep-hover',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$this->end_controls_section();// end: Section

		// Image
		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'upload_type' => 'image',
				],
			]
		);
		$this->add_responsive_control(
			'image_margin',
			[
				'label' => __( 'Icon Outer Spacing', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item .namep-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item .namep-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'image_width',
			[
				'label' => esc_html__( 'Image Width', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item .namep-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'image_height',
			[
				'label' => esc_html__( 'Image Height', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item .namep-image img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Icon
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'upload_type' => 'icon',
				],
			]
		);
		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => __( 'Icon Outer Spacing', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item .namep-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item .namep-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_width',
			[
				'label' => esc_html__( 'Icon Width', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item .namep-icon' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_height',
			[
				'label' => esc_html__( 'Icon Height', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item .namep-icon' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_line_height',
			[
				'label' => esc_html__( 'Icon Line Height', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item .namep-icon' => 'line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item .namep-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'icon_style' );
			$this->start_controls_tab(
				'ico_normal',
				[
					'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-contact-item .namep-icon' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'ico_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-contact-item .namep-icon',
				]
			);
			$this->add_control(
				'icon_bgcolor',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-contact-item .namep-icon' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'ico_hover',
				[
					'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'icon_hover_color',
				[
					'label' => esc_html__( 'Icon Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-contact-item.namep-hover .namep-icon' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'ico_hover_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-contact-item.namep-hover .namep-icon',
				]
			);
			$this->add_control(
				'icon_hover_bgcolor',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-contact-item.namep-hover .namep-icon' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'title_padding',
			[
				'label' => __( 'Title Spacing', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .contact-info h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'gallery_title_typography',
				'selector' => '{{WRAPPER}} .contact-info h2',
			]
		);
		$this->add_control(
			'gallery_title_color',
			[
				'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .contact-info h2' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_contact_title_style',
			[
				'label' => esc_html__( 'Contact Title', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'gallery_contact_title_typography',
				'selector' => '{{WRAPPER}} .namep-contact-item .contact-info ul li span',
			]
		);
		$this->add_control(
			'gallery_contact_title_color',
			[
				'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item .contact-info ul li span' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Content
		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__( 'Contact Text', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'text_padding',
			[
				'label' => __( 'Text Spacing', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item .contact-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'list_padding',
			[
				'label' => __( 'List Spacing', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .namep-contact-item .contact-info ul li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
				'name' => 'sasstp_text_typography',
				'selector' => '{{WRAPPER}} .namep-contact-item .contact-info ul li',
			]
		);
		$this->start_controls_tabs( 'text_style' );
			$this->start_controls_tab(
				'text_normal',
				[
					'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'text_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-contact-item .contact-info ul li, {{WRAPPER}} .namep-contact-item .contact-info ul li a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'text_hover',
				[
					'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'text_hover_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-contact-item .contact-info ul li a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render Contact widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : [];
		$upload_type = !empty( $settings['upload_type'] ) ? $settings['upload_type'] : '';
		$contact_image = !empty( $settings['contact_image']['id'] ) ? $settings['contact_image']['id'] : '';
		$contact_icon = !empty( $settings['contact_icon'] ) ? $settings['contact_icon'] : '';
		$contact_title = !empty( $settings['contact_title'] ) ? $settings['contact_title'] : '';
		$section_alignment = !empty( $settings['section_alignment'] ) ? $settings['section_alignment'] : '';

		// Image
		$image_url = wp_get_attachment_url( $contact_image );

		$contact_image = $image_url ? '<div class="namep-image"><img src="'.esc_url($image_url).'" alt="'.esc_html( 'Contact', 'medical-addon-for-elementor' ).'"></div>' : '';
		$contact_icon = $contact_icon ? '<div class="namep-icon"><i class="'.esc_attr($contact_icon).'"></i></div>' : '';
		$contact_title = $contact_title ? '<h2>'.esc_html($contact_title).'</h2>' : '';

		if ($upload_type === 'icon'){
		  $icon_main = $contact_icon;
		} else {
		  $icon_main = $contact_image;
		}

		if ($section_alignment === 'center'){
		  $align_cls = ' contact-center';
		} elseif ($section_alignment === 'right'){
		  $align_cls = ' contact-right';
		} else {
		  $align_cls = '';
		}

	  $output = '<div class="namep-contact-item'.esc_attr($align_cls).'">'.$icon_main.'<div class="contact-info">'.$contact_title.'<ul>';

		// Group Param Output
		if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
		  foreach ( $listItems_groups as $each_contact ) {
				$list_title = $each_contact['list_title'] ? $each_contact['list_title'] : '';
				$list_text = $each_contact['list_text'] ? $each_contact['list_text'] : '';

				$list_link = !empty( $each_contact['list_link']['url'] ) ? $each_contact['list_link']['url'] : '';
				$list_link_external = !empty( $each_contact['list_link']['is_external'] ) ? 'target="_blank"' : '';
				$list_link_nofollow = !empty( $each_contact['list_link']['nofollow'] ) ? 'rel="nofollow"' : '';
				$list_link_attr = !empty( $list_link ) ?  $list_link_external.' '.$list_link_nofollow : '';

				$list_title = $list_title ? '<span>'.esc_html($list_title).'</span>' : '';
				$link = $list_link ? '<a href="'.esc_url($list_link).'" '.$list_link_attr.'>'.esc_html($list_text).'</a>' : esc_html($list_text);

			  $output .= '<li>'.$list_title.' '.$link.'</li>';
		  }
		}

		$output .= '</ul></div></div>';

		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Contact() );
