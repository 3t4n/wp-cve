<?php
/*
 * Elementor Education Addon Profile Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_profile'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Profile extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_profile';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Profile', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-preferences';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Profile widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$this->start_controls_section(
			'section_profile',
			[
				'label' => __( 'Profile Item', 'education-addon' ),
			]
		);
		$this->add_control(
			'profile_style',
			[
				'label' => esc_html__( 'Profile Style', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'education-addon' ),
					'two' => esc_html__( 'Style Two', 'education-addon' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select your style.', 'education-addon' ),
			]
		);
		$this->add_control(
			'profile_image',
			[
				'label' => esc_html__( 'Upload Icon', 'education-addon' ),
				'type' => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'description' => esc_html__( 'Set your icon image.', 'education-addon'),
			]
		);
		$this->add_control(
			'profile_title',
			[
				'label' => esc_html__( 'Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Shawn Michael', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'designation',
			[
				'label' => esc_html__( 'Designation', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Advanced Educator', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'education-addon' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as making it look like readable English.', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'contact_title',
			[
				'label' => esc_html__( 'Contact Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Contact:', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->start_controls_tabs(
			'contact_links',
			[
				'label' => esc_html__( 'Contact Links', 'education-addon' ),
				'separator' => 'before',
			]
		);
		$this->start_controls_tab(
			'linkone',
			[
				'label' => esc_html__( 'Phone', 'education-addon' ),
			]
		);
		$this->add_control(
			'phone_title',
			[
				'label' => esc_html__( 'Phone Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Phone number : ', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'phone_text',
			[
				'label' => esc_html__( 'Phone Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '(+88) - 1990 - 8668', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'phone_link',
			[
				'label' => esc_html__( 'Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->end_controls_tab();  // end:Normal tab
		$this->start_controls_tab(
			'linktwo',
			[
				'label' => esc_html__( 'Email', 'education-addon' ),
			]
		);
		$this->add_control(
			'email_title',
			[
				'label' => esc_html__( 'Email Title', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Email : ', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'email_text',
			[
				'label' => esc_html__( 'Email Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'yourmail@gmail.com', 'education-addon' ),
				'placeholder' => esc_html__( 'Type title text here', 'education-addon' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'email_link',
			[
				'label' => esc_html__( 'Link', 'education-addon' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://your-link.com',
				'default' => [
					'url' => '',
				],
				'label_block' => true,
			]
		);
		$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$repeater = new Repeater();
		$repeater->add_control(
			'social_icon',
			[
				'label' => esc_html__( 'Social Icon', 'education-addon' ),
				'type' => Controls_Manager::ICON,
				'options' => NAEDU_Controls_Helper_Output::get_include_icons(),
				'frontend_available' => true,
				'default' => 'fa fa-facebook-square',
			]
		);
		$repeater->add_control(
			'icon_link',
			[
				'label' => esc_html__( 'Icon Link', 'education-addon' ),
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
				'label' => esc_html__( 'Social Icons', 'education-addon' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ social_icon }}}',
				'prevent_empty' => false,
				'separator' => 'before',
			]
		);
		$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'section_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-profile' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-profile' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'section_bdr_rad',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-profile' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-profile' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-profile',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-profile',
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'title_padding',
				[
					'label' => __( 'Title Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-profile h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .naedu-profile h3',
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-profile h3, {{WRAPPER}} .naedu-profile h3 a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Designation
			$this->start_controls_section(
				'section_designation_style',
				[
					'label' => esc_html__( 'Designation', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'designation_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-profile h4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'designation_typography',
					'selector' => '{{WRAPPER}} .naedu-profile h4',
					'separator' => 'before',
				]
			);
			$this->add_control(
				'designation_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-profile h4' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'Content', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'content_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-profile p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .naedu-profile p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-profile p' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Contact
			$this->start_controls_section(
				'section_contact_style',
				[
					'label' => esc_html__( 'Contact', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'contact_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-profile h5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'contact_typography',
					'selector' => '{{WRAPPER}} .naedu-profile h5',
				]
			);
			$this->add_control(
				'contact_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-profile h5' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Contact Link
			$this->start_controls_section(
				'section_clink_style',
				[
					'label' => esc_html__( 'Contact Link', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'clink_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .contact-links' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'clink_typography',
					'selector' => '{{WRAPPER}} .contact-links, {{WRAPPER}} .contact-links a',
				]
			);
			$this->start_controls_tabs( 'clink_style' );
				$this->start_controls_tab(
					'clink_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'clink_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .contact-links, {{WRAPPER}} .contact-links a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'clink_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'clink_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .contact-links, {{WRAPPER}} .contact-links a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Icon
			$this->start_controls_section(
				'section_sicon_style',
				[
					'label' => esc_html__( 'Icon', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'social_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .social-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'sicon_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .social-link a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'sicon_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .social-link a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'sicon_size',
				[
					'label' => esc_html__( 'Icon Size', 'education-addon' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .social-link a' => 'font-size:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'sicon_style' );
				$this->start_controls_tab(
					'sicon_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'sicon_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .social-link a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'sicon_bg',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .social-link a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'sicon_border',
					[
						'label' => esc_html__( 'Border Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .social-link a' => 'border-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'sicon_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'sicon_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .social-link a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'sicon_bg_hov',
					[
						'label' => esc_html__( 'Background Hover Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .social-link a:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'sicon_hover_border',
					[
						'label' => esc_html__( 'Border Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .social-link a:hover' => 'border-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs			
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render Profile widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		// Profile query
		$settings = $this->get_settings_for_display();
		$profile_style = !empty( $settings['profile_style'] ) ? $settings['profile_style'] : '';
		$profile_image = !empty( $settings['profile_image']['id'] ) ? $settings['profile_image']['id'] : '';
		$profile_title = !empty( $settings['profile_title'] ) ? $settings['profile_title'] : '';
		$designation = !empty( $settings['designation'] ) ? $settings['designation'] : '';
		$content = !empty( $settings['content'] ) ? $settings['content'] : '';
		$contact_title = !empty( $settings['contact_title'] ) ? $settings['contact_title'] : '';

		$phone_title = !empty( $settings['phone_title'] ) ? $settings['phone_title'] : '';
		$phone_text = !empty( $settings['phone_text'] ) ? $settings['phone_text'] : '';
		$phone_link = !empty( $settings['phone_link']['url'] ) ? esc_url($settings['phone_link']['url']) : '';
		$phone_link_external = !empty( $phone_link['is_external'] ) ? 'target="_blank"' : '';
		$phone_link_nofollow = !empty( $phone_link['nofollow'] ) ? 'rel="nofollow"' : '';
		$phone_link_attr = !empty( $phone_link['url'] ) ?  $phone_link_external.' '.$phone_link_nofollow : '';

		$email_title = !empty( $settings['email_title'] ) ? $settings['email_title'] : '';
		$email_text = !empty( $settings['email_text'] ) ? $settings['email_text'] : '';
		$email_link = !empty( $settings['email_link']['url'] ) ? esc_url($settings['email_link']['url']) : '';
		$email_link_external = !empty( $email_link['is_external'] ) ? 'target="_blank"' : '';
		$email_link_nofollow = !empty( $email_link['nofollow'] ) ? 'rel="nofollow"' : '';
		$email_link_attr = !empty( $email_link['url'] ) ?  $email_link_external.' '.$email_link_nofollow : '';

		$listItems_groups = !empty( $settings['listItems_groups'] ) ? $settings['listItems_groups'] : '';

		$image_url = wp_get_attachment_url( $profile_image );
		$image = $image_url ? '<div class="naedu-image"><img src="'.$image_url.'" alt="Img"></div>' : '';
		$bg_img = $image_url ? ' style="background-image: url('.esc_url($image_url).');"' : '';

		$profile_title = $profile_title ? '<h3>'.$profile_title.'</h3>' : '';
		$designation = $designation ? '<h4>'.$designation.'</h4>' : '';
		$content = $content ? '<p>'.$content.'</p>' : '';
		$contact_title = $contact_title ? '<h5>'.$contact_title.'</h5>' : '';

		$phone_title = $phone_title ? '<strong>'.$phone_title.'</strong>' : '';
		$phone_link = $phone_link ? '<a href="'.esc_url($phone_link).'" '.$phone_link_attr.'>'.esc_html($phone_text).'</a>' : $phone_text;
		$phone = $phone_text ? $phone_link : '';

		$email_title = $email_title ? '<strong>'.$email_title.'</strong>' : '';
		$email_link = $email_link ? '<a href="'.esc_url($email_link).'" '.$email_link_attr.'>'.esc_html($email_text).'</a>' : $email_text;
		$email = $email_text ? $email_link : '';


		if ($profile_style === 'two') {
			$style_cls = ' profile-style-two';
		} else {
			$style_cls = '';
		}

		$output = '<div class="naedu-profile'.$style_cls.'">';
		if ($profile_style === 'two') {
	    $output .= '<div class="nich-row nich-no-gutters nich-align-items-center">
						        <div class="nich-col-lg-6">
						          <div class="naedu-bg"'.$bg_img.'></div>
						        </div>
						        <div class="nich-col-md-6">
						        	<div class="profile-info">
							        	'.$profile_title.$designation.$content.$contact_title.'
							          <div class="contact-links">'.$phone_title.$phone.'</div>
							          <div class="contact-links">'.$email_title.$email.'</div>
							          <div class="social-link">';
				                	// Group Param Output
													if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
													  foreach ( $listItems_groups as $each_list ) {
													  $icon_link = !empty( $each_list['icon_link'] ) ? $each_list['icon_link'] : '';
														$link_url = !empty( $icon_link['url'] ) ? esc_url($icon_link['url']) : '';
														$link_external = !empty( $icon_link['is_external'] ) ? 'target="_blank"' : '';
														$link_nofollow = !empty( $icon_link['nofollow'] ) ? 'rel="nofollow"' : '';
														$link_attr = !empty( $icon_link['url'] ) ?  $link_external.' '.$link_nofollow : '';
													  $social_icon = !empty( $each_list['social_icon'] ) ? $each_list['social_icon'] : '';
														$icon = $social_icon ? '<i class="'.esc_attr($social_icon).'"></i>' : '';

													  $output .= '<a href="'.$link_url.'" '.$link_attr.'>'.$icon.'</a>';
													} }
	          $output .= '</div>
						        	</div>
						        </div>
						      </div>';
	  } else {
	  	$output .= '<div class="nich-row nich-align-items-center">
						        <div class="nich-col-md-5">'.$image.'</div>
						        <div class="nich-col-md-7">
						        	'.$profile_title.$designation.$content.$contact_title.'
						          <div class="contact-links">'.$phone_title.$phone.'</div>
						          <div class="contact-links">'.$email_title.$email.'</div>
						          <div class="social-link">';
			                	// Group Param Output
												if ( is_array( $listItems_groups ) && !empty( $listItems_groups ) ){
												  foreach ( $listItems_groups as $each_list ) {
												  $icon_link = !empty( $each_list['icon_link'] ) ? $each_list['icon_link'] : '';
													$link_url = !empty( $icon_link['url'] ) ? esc_url($icon_link['url']) : '';
													$link_external = !empty( $icon_link['is_external'] ) ? 'target="_blank"' : '';
													$link_nofollow = !empty( $icon_link['nofollow'] ) ? 'rel="nofollow"' : '';
													$link_attr = !empty( $icon_link['url'] ) ?  $link_external.' '.$link_nofollow : '';
												  $social_icon = !empty( $each_list['social_icon'] ) ? $each_list['social_icon'] : '';
													$icon = $social_icon ? '<i class="'.esc_attr($social_icon).'"></i>' : '';

												  $output .= '<a href="'.$link_url.'" '.$link_attr.'>'.$icon.'</a>';
												} }
          $output .= '</div>
						        </div>
						      </div>';
	  }
	  $output .= '</div>';
		echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Profile() );

} // enable & disable
