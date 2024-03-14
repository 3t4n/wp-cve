<?php

namespace EazyGrid\Elementor\Base;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Embed;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

abstract class Photo_Grid extends EazyGrid_Base {

	/**
	 * Hover style
	 */
	public function hover_styles() {
		$hover_styles = [
			'slide-up' => __( 'Slide Up', 'eazygrid-elementor' ),
			'fade-in'  => __( 'Fade In', 'eazygrid-elementor' ),
			'zoom-in'  => __( 'Zoom In', 'eazygrid-elementor' ),
			'lilly'    => __( 'Lilly', 'eazygrid-elementor' ),
			'kindred'  => __( 'Kindred', 'eazygrid-elementor' ),
		];
		$hover_styles = apply_filters( 'eazygridElementor/hover/styles', $hover_styles );
		return $hover_styles;
	}

	public static function get_placeholder_image_src() {
		return EAZYGRIDELEMENTOR_URL . 'assets/img/placeholder.jpg';
	}

	public function __repeater_controls_image( $repeater ) {
		$repeater->add_control(
			'image',
			[
				'label'     => __( 'Image', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => self::get_placeholder_image_src(),
				],
				'dynamic'   => [
					'active' => false,
				],
				'condition' => [
					'media_type' => 'image',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail',
				'default'   => 'large',
				'exclude'   => [
					'custom',
				],
				'condition' => [
					'media_type' => 'image',
				],
			]
		);
	}

	public function __repeater_controls_video( $repeater ) {
		$repeater->add_control(
			'video_type',
			[
				'label'              => esc_html__( 'Source', 'eazygrid-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'youtube',
				'options'            => [
					'youtube'     => esc_html__( 'YouTube', 'eazygrid-elementor' ),
					'vimeo'       => esc_html__( 'Vimeo', 'eazygrid-elementor' ),
					'dailymotion' => esc_html__( 'Dailymotion', 'eazygrid-elementor' ),
					'hosted'      => esc_html__( 'Self Hosted', 'eazygrid-elementor' ),
				],
				'frontend_available' => true,
				'condition'          => [
					'media_type' => 'video',
				],
			]
		);

		$repeater->add_control(
			'youtube_url',
			[
				'label'              => esc_html__( 'Link', 'eazygrid-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'dynamic'            => [
					'active'     => false,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder'        => esc_html__( 'Enter your URL', 'eazygrid-elementor' ) . ' (YouTube)',
				'default'            => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
				'label_block'        => true,
				'condition'          => [
					'media_type' => 'video',
					'video_type' => 'youtube',
				],
				'frontend_available' => true,
			]
		);

		$repeater->add_control(
			'vimeo_url',
			[
				'label'       => esc_html__( 'Link', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active'     => false,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'eazygrid-elementor' ) . ' (Vimeo)',
				'default'     => 'https://vimeo.com/235215203',
				'label_block' => true,
				'condition'   => [
					'media_type' => 'video',
					'video_type' => 'vimeo',
				],
			]
		);

		$repeater->add_control(
			'dailymotion_url',
			[
				'label'       => esc_html__( 'Link', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active'     => false,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'eazygrid-elementor' ) . ' (Dailymotion)',
				'default'     => 'https://www.dailymotion.com/video/x6tqhqb',
				'label_block' => true,
				'condition'   => [
					'media_type' => 'video',
					'video_type' => 'dailymotion',
				],
			]
		);

		$repeater->add_control(
			'insert_url',
			[
				'label'     => esc_html__( 'External URL', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'media_type' => 'video',
					'video_type' => 'hosted',
				],
			]
		);

		$repeater->add_control(
			'hosted_url',
			[
				'label'      => esc_html__( 'Choose File', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => [ 'video' ],
				'dynamic'    => [
					'active'     => false,
					'categories' => [
						TagsModule::MEDIA_CATEGORY,
					],
				],
				'condition'  => [
					'media_type' => 'video',
					'video_type' => 'hosted',
					'insert_url' => '',
				],
			]
		);

		$repeater->add_control(
			'external_url',
			[
				'label'        => esc_html__( 'URL', 'eazygrid-elementor' ),
				'type'         => Controls_Manager::URL,
				'autocomplete' => false,
				'options'      => false,
				'label_block'  => true,
				'show_label'   => false,
				'dynamic'      => [
					'active'     => false,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'media_type'   => 'video',
				'placeholder'  => esc_html__( 'Enter your URL', 'eazygrid-elementor' ),
				'condition'    => [
					'media_type' => 'video',
					'video_type' => 'hosted',
					'insert_url' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'image_overlay',
			[
				'label'              => esc_html__( 'Custom Video Poster Image', 'eazygrid-elementor' ),
				'type'               => Controls_Manager::MEDIA,
				'default'            => [
					'url' => '',
				],
				'dynamic'            => [
					'active' => false,
				],
				'condition'          => [
					'media_type' => 'video',
				],
				'frontend_available' => true,
			]
		);

		// YouTube.
		$repeater->add_control(
			'yt_privacy',
			[
				'label'              => esc_html__( 'Privacy Mode', 'eazygrid-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'description'        => esc_html__( 'When you turn on privacy mode, YouTube won\'t store information about visitors on your website unless they play the video.', 'eazygrid-elementor' ),
				'condition'          => [
					'media_type' => 'video',
					'video_type' => 'youtube',
				],
				'frontend_available' => true,
			]
		);

		$repeater->add_control(
			'start',
			[
				'label'              => esc_html__( 'Start Time', 'eazygrid-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'description'        => esc_html__( 'Specify a start time (in seconds)', 'eazygrid-elementor' ),
				'frontend_available' => true,
				'condition'          => [
					'media_type' => 'video',
				],
			]
		);

		$repeater->add_control(
			'end',
			[
				'label'              => esc_html__( 'End Time', 'eazygrid-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'description'        => esc_html__( 'Specify an end time (in seconds)', 'eazygrid-elementor' ),
				'condition'          => [
					'media_type' => 'video',
					'video_type' => [ 'youtube', 'hosted' ],
				],
				'frontend_available' => true,
			]
		);
	}

	public function __repeater_controls_title_subtitle( $repeater ) {
		$repeater->add_control(
			'pull_meta',
			[
				'label' => esc_html__( 'Image Meta', 'eazygrid-elementor' ),
				'type' => \Elementor\Controls_Manager::BUTTON,
				'separator' => 'before',
				// 'button_type' => 'success',
				'text' => esc_html__( 'Pull Image Meta', 'eazygrid-elementor' ),
				'event' => 'ezgrid.editor.pull_meta',
				'condition'   => [
					'media_type' => 'image',
				],
			]
			
		);
		$repeater->add_control(
			'title',
			[
				'label'       => __( 'Title', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'separator' => 'before',
				'placeholder' => __( 'Type your title here', 'eazygrid-elementor' ),
			]
		);

		$repeater->add_control(
			'subtitle',
			[
				'label'       => __( 'Subtitle', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default'     => '',
				'placeholder' => __( 'Type your subtitle here', 'eazygrid-elementor' ),
			]
		);

		$repeater->add_control(
			'url',
			[
				'label'       => __( 'Link URL', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'show_label'  => false,
				'input_type'  => 'url',
				'description' => __( 'Works only when "On Click" is set to "Open Link"', 'eazygrid-elementor' ),
				'default'     => '',
				'placeholder' => __( 'Link URL', 'eazygrid-elementor' ),
				'condition'   => [
					'media_type' => 'image',
				],
			]
		);
	}

	public function __hover_1_style_controls() {
		$this->start_controls_section(
			'grid_hover_style',
			[
				'label'     => __( 'Hover', 'eazygrid-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_hover' => 'yes',
					'hover_style'  => 'slide-up',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_margin',
			[
				'label'      => __( 'Overlay Margin', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-grid--overlay' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_padding',
			[
				'label'      => __( 'Overlay Padding', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'hover_title_typography',
				'label'          => __( 'Title Typography', 'eazygrid-elementor' ),
				'scheme'         => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography'  => [
						'default' => 'yes',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_weight' => [
						'default' => '600',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px',
							'size' => '20',
						],
					],
				],
				'selector'       => '{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-title',
			]
		);

		$this->add_responsive_control(
			'title_btm_space',
			[
				'label'       => __( 'Bottom Space', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'devices'     => ['desktop', 'tablet', 'mobile'],
				'size_units'  => ['px'],
				'range'       => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '0',
				],
				'selectors'   => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'hover_desc_typography',
				'label'          => __( 'Description Typography', 'eazygrid-elementor' ),
				'scheme'         => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography'  => [
						'default' => 'yes',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px',
							'size' => '14',
						],
					],
				],
				'selector'       => '{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-desc',
			]
		);

		$this->add_control(
			'hover_background_color',
			[
				'label'     => __( 'Background Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--hover-slide-up .ezg-ele-grid--overlay-inner' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'hover_style' => 'slide-up',
				],
			]
		);

		$this->add_control(
			'hover_overlay_color',
			[
				'label'     => __( 'Overlay Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_text_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ezg-ele-grid--overlay-title:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_description_color',
			[
				'label'     => __( 'Description Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function __hover_2_style_controls() {
		$this->start_controls_section(
			'grid_hover_style_2',
			[
				'label'     => __( 'Hover', 'eazygrid-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_hover' => 'yes',
					'hover_style'  => 'fade-in',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_margin_2',
			[
				'label'      => __( 'Overlay Margin', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-grid--overlay' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_padding_2',
			[
				'label'      => __( 'Overlay Padding', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'hover_title_typography_2',
				'label'          => __( 'Title Typography', 'eazygrid-elementor' ),
				'scheme'         => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography'  => [
						'default' => 'yes',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_weight' => [
						'default' => '600',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px',
							'size' => '20',
						],
					],
				],
				'selector'       => '{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-title',
			]
		);

		$this->add_responsive_control(
			'title_btm_space_2',
			[
				'label'       => __( 'Bottom Space', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'devices'     => ['desktop', 'tablet', 'mobile'],
				'size_units'  => ['px'],
				'range'       => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '0',
				],
				'selectors'   => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'hover_desc_typography_2',
				'label'          => __( 'Description Typography', 'eazygrid-elementor' ),
				'scheme'         => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography'  => [
						'default' => 'yes',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px',
							'size' => '14',
						],
					],
				],
				'selector'       => '{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-desc',
			]
		);

		$this->add_control(
			'hover_background_color_2',
			[
				'label'     => __( 'Background Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--hover-slide-up .ezg-ele-grid--overlay-inner' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'hover_style' => 'slide-up',
				],
			]
		);

		$this->add_control(
			'hover_overlay_color_2',
			[
				'label'     => __( 'Overlay Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_text_color_2',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--overlay-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ezg-ele-grid--overlay-title:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_description_color_2',
			[
				'label'     => __( 'Description Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--overlay-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function __hover_3_style_controls() {
		$this->start_controls_section(
			'grid_hover_style_3',
			[
				'label'     => __( 'Hover', 'eazygrid-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_hover' => 'yes',
					'hover_style'  => 'zoom-in',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_margin_3',
			[
				'label'      => __( 'Overlay Margin', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-grid--overlay' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_padding_3',
			[
				'label'      => __( 'Overlay Padding', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'hover_title_typography_3',
				'label'          => __( 'Title Typography', 'eazygrid-elementor' ),
				'scheme'         => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography'  => [
						'default' => 'yes',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_weight' => [
						'default' => '600',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px',
							'size' => '20',
						],
					],
				],
				'selector'       => '{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-title',
			]
		);

		$this->add_responsive_control(
			'title_btm_space_3',
			[
				'label'       => __( 'Bottom Space', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'devices'     => ['desktop', 'tablet', 'mobile'],
				'size_units'  => ['px'],
				'range'       => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '0',
				],
				'selectors'   => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'hover_desc_typography_3',
				'label'          => __( 'Description Typography', 'eazygrid-elementor' ),
				'scheme'         => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography'  => [
						'default' => 'yes',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px',
							'size' => '14',
						],
					],
				],
				'selector'       => '{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-desc',
			]
		);

		$this->add_control(
			'hover_background_color_3',
			[
				'label'     => __( 'Background Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--hover-slide-up .ezg-ele-grid--overlay-inner' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'hover_style' => 'slide-up',
				],
			]
		);

		$this->add_control(
			'hover_overlay_color_3',
			[
				'label'     => __( 'Overlay Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_text_color_3',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--overlay-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ezg-ele-grid--overlay-title:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_description_color_3',
			[
				'label'     => __( 'Description Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--overlay-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function __hover_4_style_controls() {
		$this->start_controls_section(
			'grid_hover_style_4',
			[
				'label'     => __( 'Hover', 'eazygrid-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_hover' => 'yes',
					'hover_style'  => 'lilly',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_padding_4',
			[
				'label'      => __( 'Overlay Padding', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'hover_title_typography_4',
				'label'          => __( 'Title Typography', 'eazygrid-elementor' ),
				'scheme'         => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography'  => [
						'default' => 'yes',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_weight' => [
						'default' => '600',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px',
							'size' => '20',
						],
					],
				],
				'selector'       => '{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-title',
			]
		);

		$this->add_responsive_control(
			'title_btm_space_4',
			[
				'label'       => __( 'Bottom Space', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'devices'     => ['desktop', 'tablet', 'mobile'],
				'size_units'  => ['px'],
				'range'       => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '0',
				],
				'selectors'   => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'hover_desc_typography_4',
				'label'          => __( 'Description Typography', 'eazygrid-elementor' ),
				'scheme'         => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography'  => [
						'default' => 'yes',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px',
							'size' => '14',
						],
					],
				],
				'selector'       => '{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-desc',
			]
		);

		$this->add_control(
			'hover_overlay_color_4',
			[
				'label'     => __( 'Overlay Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_text_color_4',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--overlay-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ezg-ele-grid--overlay-title:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_description_color_4',
			[
				'label'     => __( 'Description Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--overlay-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function __hover_5_style_controls() {
		$this->start_controls_section(
			'grid_hover_style_5',
			[
				'label'     => __( 'Hover', 'eazygrid-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_hover' => 'yes',
					'hover_style'  => 'kindred',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_padding_5',
			[
				'label'      => __( 'Overlay Padding', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'hover_title_typography_5',
				'label'          => __( 'Title Typography', 'eazygrid-elementor' ),
				'scheme'         => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography'  => [
						'default' => 'yes',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_weight' => [
						'default' => '600',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px',
							'size' => '20',
						],
					],
				],
				'selector'       => '{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-title',
			]
		);

		$this->add_responsive_control(
			'title_btm_space_5',
			[
				'label'       => __( 'Bottom Space', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'devices'     => ['desktop', 'tablet', 'mobile'],
				'size_units'  => ['px'],
				'range'       => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '0',
				],
				'selectors'   => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'hover_desc_typography_5',
				'label'          => __( 'Description Typography', 'eazygrid-elementor' ),
				'scheme'         => Typography::TYPOGRAPHY_1,
				'fields_options' => [
					'typography'  => [
						'default' => 'yes',
					],
					'font_family' => [
						'default' => 'Roboto',
					],
					'font_weight' => [
						'default' => '400',
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px',
							'size' => '14',
						],
					],
				],
				'selector'       => '{{WRAPPER}} .ezg-ele-grid--overlay-inner .ezg-ele-grid--overlay-desc',
			]
		);

		$this->add_control(
			'hover_overlay_color_5',
			[
				'label'     => __( 'Overlay Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--hover-kindred:hover .ezg-ele-grid--overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_text_color_5',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [

					'{{WRAPPER}} .ezg-ele-grid--overlay-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ezg-ele-grid--overlay-title:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_description_color_5',
			[
				'label'     => __( 'Description Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_overlay_border_color_5',
			[
				'label'     => __( 'Border Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-grid--hover-kindred .ezg-ele-grid--overlay::before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .ezg-ele-grid--hover-kindred .ezg-ele-grid--overlay::after' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function __video_style_controls() {
		$this->start_controls_section(
			'section_video_style',
			[
				'label' => esc_html__( 'Video', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'aspect_ratio',
			[
				'label'              => esc_html__( 'Aspect Ratio', 'eazygrid-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'169' => '16:9',
					'219' => '21:9',
					'43'  => '4:3',
					'32'  => '3:2',
					'11'  => '1:1',
					'916' => '9:16',
				],
				'default'            => '169',
				'prefix_class'       => 'elementor-aspect-ratio-',
				'frontend_available' => true,
			]
		);

		// $this->add_group_control(
		// 	Group_Control_Css_Filter::get_type(),
		// 	[
		// 		'name' => 'css_filters',
		// 		'selector' => '{{WRAPPER}} .elementor-wrapper',
		// 	]
		// );

		$this->add_control(
			'play_icon_title',
			[
				'label'     => esc_html__( 'Play Icon', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'vid_play_icon',
			[
				'label'                  => __( 'Icon', 'eazygrid-elementor' ),
				'type'                   => \Elementor\Controls_Manager::ICONS,
				'default'                => [
					'value'   => 'ezicon ezicon-play-4',
					'library' => 'eazy-icons',
				],
				'recommended'            => [
					'eazy-icons' => [
						'play-1',
						'play-1-alt',
						'play-2',
						'play-3',
						'play-4',
						'play-5',
						'play-6',
						'play-7',
						'play-8',
					],
					'fa-solid'   => [
						'play',
						'play-circle',
					],
					'fa-regular' => [
						'play-circle',
					],
				],
				'skin'                   => 'inline',
				'label_block'            => false,
				'exclude_inline_options' => ['svg'],
			]
		);

		$this->add_control(
			'play_icon_color',
			[
				'label'     => esc_html__( 'Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-custom-embed-play i' => 'color: {{VALUE}}',
				],
				'default'=>'#DBDDDE'
			]
		);

		$this->add_control(
			'play_icon_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-custom-embed-play:hover i' => 'color: {{VALUE}}',
				],
				'default'=>'#DBDDDE'
			]
		);

		$this->add_control(
			'vid_enable_overlay',
			[
				'label'     => esc_html__( 'Enable Hover Overlay', 'eazygrid-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'eazygrid-elementor' ),
				'label_off'    => __( 'No', 'eazygrid-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_responsive_control(
			'play_icon_size',
			[
				'label'     => esc_html__( 'Size', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-custom-embed-play i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'           => 'play_icon_text_shadow',
				'selector'       => '{{WRAPPER}} .elementor-custom-embed-play i',
				'fields_options' => [
					'text_shadow_type' => [
						'label' => _x( 'Shadow', 'Text Shadow Control', 'eazygrid-elementor' ),
					],
				],
			]
		);

		$this->end_controls_section();
	}

	public function __lightbox_style_controls() {
		$this->start_controls_section(
			'section_lightbox_style',
			[
				'label' => esc_html__( 'Lightbox', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label'     => esc_html__( 'Background Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[id^="elementor-lightbox-"]' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_color',
			[
				'label'     => esc_html__( 'UI Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[id^="elementor-lightbox-"] .dialog-lightbox-close-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_color_hover',
			[
				'label'     => esc_html__( 'UI Hover Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'[id^="elementor-lightbox-"] .dialog-lightbox-close-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'more_options',
			[
				'label'       => __( 'Video Specific Options', 'eazygrid-elementor' ),
				'description' => esc_html__( 'This settings are only for video lightbox.', 'eazygrid-elementor' ),
				'type'        => \Elementor\Controls_Manager::HEADING,
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'lightbox_video_width',
			[
				'label'     => esc_html__( 'Content Width', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => '%',
				],
				'range'     => [
					'%' => [
						'min' => 30,
					],
				],
				'selectors' => [
					'(desktop+)[id^="elementor-lightbox-"] .elementor-video-container' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'lightbox_content_position',
			[
				'label'                => esc_html__( 'Content Position', 'eazygrid-elementor' ),
				'type'                 => Controls_Manager::SELECT,
				'frontend_available'   => true,
				'options'              => [
					''    => esc_html__( 'Center', 'eazygrid-elementor' ),
					'top' => esc_html__( 'Top', 'eazygrid-elementor' ),
				],
				'selectors'            => [
					'[id^="elementor-lightbox-"] .elementor-video-container' => '{{VALUE}}; transform: translateX(-50%);',
				],
				'selectors_dictionary' => [
					'top' => 'top: 60px',
				],
			]
		);

		$this->add_responsive_control(
			'lightbox_content_animation',
			[
				'label'              => esc_html__( 'Entrance Animation', 'eazygrid-elementor' ),
				'type'               => Controls_Manager::ANIMATION,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get embed params.
	 *
	 * Retrieve video widget embed parameters.
	 *
	 * @since 1.0.1
	 * @access public
	 *
	 * @return array Video embed parameters.
	 */
	public function get_embed_params( $image ) {

		$params = [];

		if ( 'youtube' === $image['video_type'] ) {
			$params['controls']       = 1;
			$params['mute']           = 1;
			$params['rel']            = 0;
			$params['modestbranding'] = 1;
			$params['start']          = $image['start'];
			$params['end']            = $image['end'];
			$params['wmode']          = 'opaque';
		} elseif ( 'vimeo' === $image['video_type'] ) {
			$params['muted']     = true;
			$params['title']     = false;
			$params['portrait']  = false;
			$params['autopause'] = '0';
		} elseif ( 'dailymotion' === $image['video_type'] ) {
			$params['controls']         = true;
			$params['mute']             = true;
			$params['showinfo']         = 'ui-start-screen-info';
			$params['logo']             = 'ui-logo';
			$params['start']            = $image['start'];
			$params['endscreen-enable'] = '0';
		}

		return $params;
	}

	/**
	 * @since 1.0.1
	 * @access private
	 */
	public function get_embed_options( $image ) {
		$embed_options = [];

		if ( 'youtube' === $image['video_type'] ) {
			$embed_options['privacy'] = $image['yt_privacy'];
		} elseif ( 'vimeo' === $image['video_type'] ) {
			$embed_options['start'] = $image['start'];
		}

		$embed_options['lazy_load'] = ! empty( $image['lazy_load'] );

		return $embed_options;
	}

	/**
	 * @since 1.0.1
	 * @access private
	 */
	public function get_hosted_params( $image ) {

		$video_params                 = [];
		$video_params['controls']     = true;
		$video_params['muted']        = 'muted';
		$video_params['playsinline']  = '';
		$video_params['controlsList'] = 'nodownload';
		$video_params['poster']       = $image['image_overlay']['url'];

		return $video_params;
	}

	/**
	 * @param bool $from_media
	 *
	 * @return string
	 * @since 1.0.1
	 * @access private
	 */
	public function get_hosted_video_url( $image ) {

		if ( ! empty( $image['insert_url'] ) ) {
			$video_url = $image['external_url']['url'];
		} else {
			$video_url = $image['hosted_url']['url'];
		}

		if ( empty( $video_url ) ) {
			return '';
		}

		if ( $image['start'] || $image['end'] ) {
			$video_url .= '#t=';
		}

		if ( $image['start'] ) {
			$video_url .= $image['start'];
		}

		if ( $image['end'] ) {
			$video_url .= ',' . $image['end'];
		}
		return $video_url;
	}

	public function get_hover_overlay_markup( $title, $subtitle ) {
		?>
			<div class="ezg-ele-grid--overlay">
				<div class="ezg-ele-grid--overlay-inner">
					<?php do_action( 'eazygridElementor/before/overlay/content' ); ?>
					<h2 class="ezg-ele-grid--overlay-title">
						<?php echo esc_html( $title ); ?>
					</h2>
					<p class="ezg-ele-grid--overlay-desc">
						<?php echo esc_html( $subtitle ); ?>
					</p>
					<?php do_action( 'eazygridElementor/after/overlay/content' ); ?>
				</div>
			</div>
		<?php
	}

	public function get_video_item_options( $image, $settings ) {
		$options   = [];
		$video_url = $image[ $image['video_type'] . '_url' ];
		if ( 'hosted' === $image['video_type'] ) {
			$video_url = $this->get_hosted_video_url( $image );
		} elseif ( 'dailymotion' === $image['video_type'] ) {
			$video_url = remove_query_arg( 'playlist', $video_url );
		}

		if ( 'hosted' === $image['video_type'] ) {
			$lightbox_url = $video_url;
		} else {
			$embed_params  = $this->get_embed_params( $image );
			$embed_options = $this->get_embed_options( $image );
			$lightbox_url  = Embed::get_embed_url( $video_url, $embed_params, $embed_options );
		}

		$lightbox_options = [
			'type'         => 'video',
			'videoType'    => $image['video_type'],
			'url'          => $lightbox_url,
			'modalOptions' => [
				'id'                       => 'elementor-lightbox-' . $image['_id'],
				'entranceAnimation'        => $settings['lightbox_content_animation'],
				'entranceAnimation_tablet' => $settings['lightbox_content_animation_tablet'],
				'entranceAnimation_mobile' => $settings['lightbox_content_animation_mobile'],
				'videoAspectRatio'         => $settings['aspect_ratio'],
			],
		];

		if ( 'hosted' === $image['video_type'] ) {
			$lightbox_options['videoParams'] = $this->get_hosted_params( $image );
		}

		$options = [
			'video_url'        => $video_url,
			'lightbox_options' => $lightbox_options,
		];

		return $options;
	}

	public function get_image_item_options( $image, $settings ) {
		$options = [];
		if ( empty( $image['url'] ) ) {
			$url = $image['image']['id'] ? wp_get_attachment_image_src( $image['image']['id'], 'full' )[0] : $image['image']['url'];
		} else {
			$url = $image['url'];
		}

		$lightbox_image_url = ( 'lightbox' === $settings['on_click'] && $image['image']['id'] ) ? wp_get_attachment_image_src( $image['image']['id'], 'full' )[0] : $url;
		$lightbox_image_url = apply_filters( 'eazygridElementor/content/url', $lightbox_image_url, $image );

		if ( ! empty( $image['image']['id'] ) ) {
			$image_url = wp_get_attachment_image_src( $image['image']['id'], $image['thumbnail_size'] )[0];
			$image_url = apply_filters( 'eazygridElementor/image/url', $image_url, $image );
		} else {
			$image_url = $image['image']['url'];
		}

		$options = [
			'image_url'          => $image_url,
			'lightbox_image_url' => $lightbox_image_url,
		];

		return $options;
	}

	public function get_attachment_image_html( $src, $alt = '', $class = '' ) {
		if ( $src ) {
			echo sprintf( '<img class="%s" src="%s" alt="%s">', esc_attr( $class ), esc_url( $src ), esc_attr( $alt ) );
		}
	}
}
