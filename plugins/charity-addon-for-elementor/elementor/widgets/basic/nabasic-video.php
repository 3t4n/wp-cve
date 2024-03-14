<?php
/*
 * Elementor Charity Addon for Elementor Video Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Charity_Elementor_Addon_Video extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'nacharity_basic_video';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Video', 'charity-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-play';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['nacharity-basic-category'];
	}

	/**
	 * Register Charity Addon for Elementor Video widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_video',
			[
				'label' => esc_html__( 'Video Options', 'charity-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'need_title',
			[
				'label' => esc_html__( 'Need Title?', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$this->add_control(
			'btn_animation',
			[
				'label' => esc_html__( 'Need Button Animation?', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'charity-addon-for-elementor' ),
				'label_off' => esc_html__( 'No', 'charity-addon-for-elementor' ),
				'return_value' => 'true',
			]
		);
		$this->add_control(
			'bg_image',
			[
				'label' => esc_html__( 'Video Image', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::MEDIA,
			]
		);
		$this->add_control(
			'video_title',
			[
				'label' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Watch the Demo', 'charity-addon-for-elementor' ),
				'label_block' => true,
				'condition' => [
					'need_title' => 'true',
				],
			]
		);
		$this->add_control(
			'video_link',
			[
				'label' => esc_html__( 'Video Link', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'placeholder' => esc_html__( 'Enter your link here', 'charity-addon-for-elementor' ),
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'sectn_style',
			[
				'label' => esc_html__( 'Section', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'section_padding',
			[
				'label' => __( 'Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-video-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'secn_style' );
			$this->start_controls_tab(
				'secn_normal',
				[
					'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'secn_border_radius',
				[
					'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .nacep-video-wrap .nacep-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Overlay Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-video-wrap .nacep-image:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-video-wrap .nacep-image',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-video-wrap .nacep-image',
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'secn_hover',
				[
					'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'secn_hov_border_radius',
				[
					'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .nacep-video-wrap.nacep-hover .nacep-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_hover_color',
				[
					'label' => esc_html__( 'Overlay Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-video-wrap.nacep-hover .nacep-image:after' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_hover_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-video-wrap.nacep-hover .nacep-image',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_hover_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-video-wrap.nacep-hover .nacep-image',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Button
		$this->start_controls_section(
			'section_video_style',
			[
				'label' => esc_html__( 'Button Style', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'btn_width',
			[
				'label' => esc_html__( 'Button Width', 'charity-addon-for-elementor' ),
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
					'{{WRAPPER}} .nacep-video-btn, {{WRAPPER}} .nacep-ripple, {{WRAPPER}} .nacep-ripple:before, {{WRAPPER}} .nacep-ripple:after' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'icon_style' );
			$this->start_controls_tab(
				'icon_normal',
				[
					'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'btn_border_radius',
				[
					'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .nacep-video-btn, {{WRAPPER}} .nacep-ripple, {{WRAPPER}} .nacep-ripple:before, {{WRAPPER}} .nacep-ripple:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-video-btn i' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_ripple_color',
				[
					'label' => esc_html__( 'Ripple Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-ripple, {{WRAPPER}} .nacep-ripple:before, {{WRAPPER}} .nacep-ripple:after' => 'box-shadow: 0 0 0 0 {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-video-btn' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'icon_border',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-video-btn',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'btn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-video-btn',
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'icon_hover',
				[
					'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'btn_hov_border_radius',
				[
					'label' => __( 'Border Radius', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .nacep-video-btn:hover,
						{{WRAPPER}} .nacep-video-wrap a:hover .nacep-video-btn,
						{{WRAPPER}} .nacep-video-btn:hover .nacep-ripple,
						{{WRAPPER}} .nacep-video-btn:hover .nacep-ripple:before,
						{{WRAPPER}} .nacep-video-btn:hover .nacep-ripple:after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'icon_hover_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-video-btn:hover i, {{WRAPPER}} .nacep-video-wrap a:hover .nacep-video-btn i' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_hover_ripple_color',
				[
					'label' => esc_html__( 'Ripple Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-video-btn:hover .nacep-ripple, {{WRAPPER}} .nacep-video-btn:hover .nacep-ripple:before, {{WRAPPER}} .nacep-video-btn:hover .nacep-ripple:after' => 'box-shadow: 0 0 0 0 {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'icon_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-video-btn:hover, {{WRAPPER}} .nacep-video-wrap a:hover .nacep-video-btn' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'icon_border_hover',
					'label' => esc_html__( 'Border', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-video-btn:hover, {{WRAPPER}} .nacep-video-wrap a:hover .nacep-video-btn',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'btn_hov_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'charity-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .nacep-video-btn:hover, {{WRAPPER}} .nacep-video-wrap a:hover .nacep-video-btn',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Title Style
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'charity-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'need_title' => 'true',
				],
			]
		);
		$this->add_control(
			'title_padding',
			[
				'label' => __( 'Title Padding', 'charity-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .nacep-video-wrap .video-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sasvid_title_typography',
				'selector' => '{{WRAPPER}} .nacep-video-wrap .video-label',
			]
		);
		$this->start_controls_tabs( 'testimonials_title_style' );
			$this->start_controls_tab(
				'title_normal',
				[
					'label' => esc_html__( 'Normal', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-video-wrap .video-label' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'title_hover',
				[
					'label' => esc_html__( 'Hover', 'charity-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'title_hov_color',
				[
					'label' => esc_html__( 'Color', 'charity-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .nacep-video-wrap a:hover .video-label' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

	}

	/**
	 * Render Video widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();
		$need_title = !empty( $settings['need_title'] ) ? $settings['need_title'] : '';
		$btn_animation = !empty( $settings['btn_animation'] ) ? $settings['btn_animation'] : '';
		$bg_image = !empty( $settings['bg_image']['id'] ) ? $settings['bg_image']['id'] : '';
		$video_link = !empty( $settings['video_link'] ) ? $settings['video_link'] : '';
		$video_title = !empty( $settings['video_title'] ) ? $settings['video_title'] : '';

		// Video
		$image_url = wp_get_attachment_url( $bg_image );

		$image = $image_url ? '<img src="'.esc_url($image_url).'" alt="Video">' : '';

		$title = $video_title ? '<span class="video-label">'.esc_html($video_title).'</span>' : '';
		if ($btn_animation) {
			$animation = '<span class="nacep-ripple"></span>';
		} else {
			$animation = '';
		}

		if ($need_title) {
			$video = $video_link ? '<a href="'.esc_url($video_link).'" class="nacep-popup-video"><span class="nacep-video-btn-wrap"><span class="nacep-video-btn"><i class="fa fa-play" aria-hidden="true"></i>'.$animation.'</span>'.$title.'</span></a>' : '';
		} else {
			$video = $video_link ? '<a href="'.esc_url($video_link).'" class="nacep-video-btn nacep-popup-video"><i class="fa fa-play" aria-hidden="true"></i>'.$animation.'</a>' : '';
		}

  	$output = '<div class="nacep-video-wrap"><div class="nacep-image" style="background-image: url('.$image_url.');">'.$image.$video.'</div></div>';

	  echo $output;

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Charity_Elementor_Addon_Video() );
