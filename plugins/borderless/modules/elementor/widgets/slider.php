<?php

namespace Borderless\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use Elementor\Widget_Base;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use \Elementor\Core\Schemes\Typography;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Css_Filter;
use \Elementor\Core\Schemes\Color;
use \Elementor\Repeater;
use Elementor\Utils;

class Slider extends Widget_Base {
	
	public function get_name() {
		return 'borderless-elementor-slider';
	}
	
	public function get_title() {
		return esc_html__('Slider', 'borderless');
	}
	
	public function get_icon() {
		return 'borderless-icon-slider';
	}
	
	public function get_categories() {
		return [ 'borderless' ];
	}
	
	public function get_keywords()
	{
		return [
			'slide',
			'slider',
			'slides',
			'borderless slide',
			'borderless slider',
			'borderless slides',
			'borderless'
		];
	}
	
	public function get_style_depends() {
		return 
			[ 
				'elementor-widget-slider',
				'borderless-elementor-flickity-style',
				'borderless-elementor-flickity-fullscreen-style',
				'borderless-elementor-flickity-fade-style' 
			];
	}
	
	public function get_script_depends() {
		return 
			[ 
				'borderless-elementor-flickity-script',
				'borderless-elementor-flickity-fullscreen-script',
				'borderless-elementor-flickity-fade-script',
				'borderless-elementor-flickity-as-nav-for-script'
			];
	}
	
	protected function _register_controls() {


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Slider/Slides - Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_slider_slides',
			[
				'label' => esc_html__( 'Slides', 'borderless' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

			$repeater = new Repeater();

			$repeater->start_controls_tabs( 'slides_repeater' );

				$repeater->start_controls_tab( 'borderless_elementor_slider_background_tab', [ 'label' => __( 'Background', 'borderless' ) ] );

					$repeater->add_responsive_control(
						'borderless_elementor_slider_slide_background_color',
						[
							'label' => __( 'Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'default' => '#0000FF',
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
							],
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_slider_slide_background_image',
						[
							'label' => __( 'Image', 'Background Control', 'borderless' ),
							'type' => Controls_Manager::MEDIA,
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-image: url({{URL}})',
							],
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_slider_slide_background_image_position',
						[
							'label' => __( 'Position', 'Background Control', 'borderless' ),
							'type' => Controls_Manager::SELECT,
							'default' => 'center center',
							'options' => [
								'' => __( 'Default', 'Background Control', 'borderless' ),
								'center top' => __( 'Center Top', 'Background Control', 'borderless' ),
								'center center' => __( 'Center Center', 'Background Control', 'borderless' ),
								'center bottom' => __( 'Center Bottom', 'Background Control', 'borderless' ),
								'left top' => __( 'Left Top', 'Background Control', 'borderless' ),
								'left center' => __( 'Left Center', 'Background Control', 'borderless' ),
								'left bottom' => __( 'Left Bottom', 'Background Control', 'borderless' ),
								'right top' => __( 'Right Top', 'Background Control', 'borderless' ),
								'right center' => __( 'Right Center', 'Background Control', 'borderless' ),
								'right bottom' => __( 'Right Bottom', 'Background Control', 'borderless' ),
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-position: {{VALUE}}',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image[url]',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_slider_slide_background_image_attachment',
						[
							'label' => __( 'Attachment', 'Background Control', 'borderless' ),
							'type' => Controls_Manager::SELECT,
							'default' => 'no-repeat',
							'options' => [
								'' => __( 'Default', 'Background Control', 'borderless' ),
								'no-repeat' => __( 'No Repeat', 'Background Control', 'borderless' ),
								'repeat' => __( 'Default', 'Background Control', 'borderless' ),
								'scroll' => __( 'Scroll', 'Background Control', 'borderless' ),
								'fixed' => __( 'Fixed', 'Background Control', 'borderless' ),
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-attachment: {{VALUE}}',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image[url]',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_slider_slide_background_image_repeat',
						[
							'label' => __( 'Repeat', 'Background Control', 'borderless' ),
							'type' => Controls_Manager::SELECT,
							'default' => 'no-repeat',
							'options' => [
								'' => __( 'Default', 'Background Control', 'borderless' ),
								'no-repeat' => __( 'No Repeat', 'Background Control', 'borderless' ),
								'repeat' => __( 'Repeat', 'Background Control', 'borderless' ),
								'repeat-x' => __( 'Repeat-x', 'Background Control', 'borderless' ),
								'repeat-y' => __( 'Repeat-y', 'Background Control', 'borderless' ),
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-repeat: {{VALUE}}',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image[url]',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_slider_slide_background_image_size',
						[
							'label' => __( 'Size', 'Background Control', 'borderless' ),
							'type' => Controls_Manager::SELECT,
							'default' => 'cover',
							'options' => [
								'' => __( 'Default', 'Background Control', 'borderless' ),
								'cover' => __( 'Cover', 'Background Control', 'borderless' ),
								'contain' => __( 'Contain', 'Background Control', 'borderless' ),
								'auto' => __( 'Auto', 'Background Control', 'borderless' ),
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-size: {{VALUE}}',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image[url]',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
						]
					);

					$repeater->add_group_control(
						Group_Control_Css_Filter::get_type(),
						[
							'name' => 'borderless_elementor_slider_slide_background_image_css_filters',
							'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image[url]',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'borderless_elementor_slider_slide_background_image_ken_burns',
						[
							'label' => __( 'Ken Burns Effect', 'borderless' ),
							'type' => Controls_Manager::SWITCHER,
							'default' => '',
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image[url]',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'borderless_elementor_slider_slide_background_image_zoom_direction',
						[
							'label' => __( 'Zoom Direction', 'borderless' ),
							'type' => Controls_Manager::SELECT,
							'default' => 'in',
							'options' => [
								'in' => __( 'In', 'borderless' ),
								'out' => __( 'Out', 'borderless' ),
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image_ken_burns',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
						]
					);

					$repeater->add_control(
						'borderless_elementor_slider_slide_background_overlay',
						[
							'label' => __( 'Background Overlay', 'borderless' ),
							'type' => Controls_Manager::SWITCHER,
							'default' => '',
							'separator' => 'before',
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_slider_slide_background_color_overlay',
						[
							'label' => __( 'Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'default' => '#00000030',
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-slide-background-overlay' => 'background-color: {{VALUE}}',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_overlay',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_slider_slide_background_image_overlay',
						[
							'label' => __( 'Image', 'Background Control', 'borderless' ),
							'type' => Controls_Manager::MEDIA,
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-slide-background-overlay' => 'background-image: url({{URL}})',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_overlay',
										'value' => 'yes',
									],
								],
							],
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_slider_slide_background_image_overlay_opacity',
						[
							'label' => esc_html__( 'Opacity', 'borderless' ),
							'type' => Controls_Manager::SLIDER,
							'default' => [
								'size' => .5,
							],
							'range' => [
								'px' => [
									'max' => 1,
									'step' => 0.01,
								],
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-slide-background-overlay' => 'opacity: {{SIZE}};',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image_overlay[url]',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_slider_slide_background_image_position_overlay',
						[
							'label' => __( 'Position', 'Background Control', 'borderless' ),
							'type' => Controls_Manager::SELECT,
							'default' => 'center center',
							'options' => [
								'' => __( 'Default', 'Background Control', 'borderless' ),
								'center top' => __( 'Center Top', 'Background Control', 'borderless' ),
								'center center' => __( 'Center Center', 'Background Control', 'borderless' ),
								'center bottom' => __( 'Center Bottom', 'Background Control', 'borderless' ),
								'left top' => __( 'Left Top', 'Background Control', 'borderless' ),
								'left center' => __( 'Left Center', 'Background Control', 'borderless' ),
								'left bottom' => __( 'Left Bottom', 'Background Control', 'borderless' ),
								'right top' => __( 'Right Top', 'Background Control', 'borderless' ),
								'right center' => __( 'Right Center', 'Background Control', 'borderless' ),
								'right bottom' => __( 'Right Bottom', 'Background Control', 'borderless' ),
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-slide-background-overlay' => 'background-position: {{VALUE}}',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image_overlay[url]',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_slider_slide_background_image_attachment_overlay',
						[
							'label' => __( 'Attachment', 'Background Control', 'borderless' ),
							'type' => Controls_Manager::SELECT,
							'default' => 'no-repeat',
							'options' => [
								'' => __( 'Default', 'Background Control', 'borderless' ),
								'no-repeat' => __( 'No Repeat', 'Background Control', 'borderless' ),
								'repeat' => __( 'Default', 'Background Control', 'borderless' ),
								'scroll' => __( 'Scroll', 'Background Control', 'borderless' ),
								'fixed' => __( 'Fixed', 'Background Control', 'borderless' ),
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-slide-background-overlay' => 'background-attachment: {{VALUE}}',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image_overlay[url]',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_slider_slide_background_image_repeat_overlay',
						[
							'label' => __( 'Repeat', 'Background Control', 'borderless' ),
							'type' => Controls_Manager::SELECT,
							'default' => 'no-repeat',
							'options' => [
								'' => __( 'Default', 'Background Control', 'borderless' ),
								'no-repeat' => __( 'No Repeat', 'Background Control', 'borderless' ),
								'repeat' => __( 'Repeat', 'Background Control', 'borderless' ),
								'repeat-x' => __( 'Repeat-x', 'Background Control', 'borderless' ),
								'repeat-y' => __( 'Repeat-y', 'Background Control', 'borderless' ),
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-slide-background-overlay' => 'background-repeat: {{VALUE}}',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image_overlay[url]',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
						]
					);

					$repeater->add_responsive_control(
						'borderless_elementor_slider_slide_background_image_size_overlay',
						[
							'label' => __( 'Size', 'Background Control', 'borderless' ),
							'type' => Controls_Manager::SELECT,
							'default' => 'cover',
							'options' => [
								'' => __( 'Default', 'Background Control', 'borderless' ),
								'cover' => __( 'Cover', 'Background Control', 'borderless' ),
								'contain' => __( 'Contain', 'Background Control', 'borderless' ),
								'auto' => __( 'Auto', 'Background Control', 'borderless' ),
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-slide-background-overlay' => 'background-size: {{VALUE}}',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image_overlay[url]',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
						]
					);

					$repeater->add_group_control(
						Group_Control_Css_Filter::get_type(),
						[
							'name' => 'borderless_elementor_slider_slide_background_image_css_filters_overlay',
							'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-slide-background-overlay',
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image_overlay[url]',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
						]
					);
			
					$repeater->add_control(
						'borderless_elementor_slider_slide_background_image_overlay_blend_mode',
						[
							'label' => __( 'Blend Mode', 'borderless' ),
							'type' => Controls_Manager::SELECT,
							'options' => [
								'' => __( 'Normal', 'borderless' ),
								'multiply' => 'Multiply',
								'screen' => 'Screen',
								'overlay' => 'Overlay',
								'darken' => 'Darken',
								'lighten' => 'Lighten',
								'color-dodge' => 'Color Dodge',
								'color-burn' => 'Color Burn',
								'hue' => 'Hue',
								'saturation' => 'Saturation',
								'color' => 'Color',
								'exclusion' => 'Exclusion',
								'luminosity' => 'Luminosity',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_background_image_overlay[url]',
										'operator' => '!=',
										'value' => '',
									],
								],
							],
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-slide-background-overlay' => 'mix-blend-mode: {{VALUE}}',
							],
						]
					);

				$repeater->end_controls_tab();

				$repeater->start_controls_tab( 'borderless_elementor_slider_content_tab', [ 'label' => __( 'Content', 'borderless' ) ] );

					$repeater->add_control(
						'borderless_elementor_slider_slide_title',
						[
							'label' => __( 'Title', 'borderless' ),
							'type' => Controls_Manager::TEXT,
							'default' => __( 'Slide', 'borderless' ),
						]
					);
					
					$repeater->add_control(
						'borderless_elementor_slider_slide_description',
						[
							'label' => __( 'Description', 'borderless' ),
							'type' => Controls_Manager::TEXTAREA,
							'default' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'borderless' ),
						]
					);

					$repeater->add_control(
						'borderless_elementor_slider_slide_button_text',
						[
							'label'			=> esc_html__( 'Button Text', 'borderless'),
							'type'			=> Controls_Manager::TEXT,
							'default' => esc_html__( 'View More', 'borderless' ),
							'dynamic'		=> [ 'active' => true ],
							'separator' => 'before',
						]
					);
			
					$repeater->add_control(
						'borderless_elementor_slider_slide_button_link',
						array(
							'label'       => esc_html__( 'Button Link', 'borderless' ),
							'type'        => Controls_Manager::URL,
							'placeholder' => 'https://your-link.com',
							'default' => array(
								'url' => '',
							),
							'dynamic' => array( 'active' => true ),
						)
					);

					$repeater->add_control(
						'borderless_elementor_slider_slide_id',
						[
							'label' => __( 'CSS ID', 'borderless' ),
							'type' => Controls_Manager::TEXT,
							'dynamic' => [ 'active' => true ],
							'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'borderless' ),
							'style_transfer' => false,
							'separator' => 'before',
						]
					);

					$repeater->add_control(
						'borderless_elementor_slider_slide_classes',
						[
							'label' => __( 'CSS Classes', 'borderless' ),
							'type' => Controls_Manager::TEXT,
							'dynamic' => [ 'active' => true ],
							'title' => esc_html__( 'Add your custom class WITHOUT the dot. e.g: my-class', 'borderless' ),
						]
					);

				$repeater->end_controls_tab();

				$repeater->start_controls_tab( 'borderless_elementor_slider_style_tab', [ 'label' => __( 'Style', 'borderless' ) ] );

					$repeater->add_control(
						'borderless_elementor_slider_slide_custom_style',
						[
							'label' => __( 'Custom', 'borderless' ),
							'type' => Controls_Manager::SWITCHER,
							'description' => __( 'Set custom style that will only affect this specific slide.', 'borderless' ),
						]
					);

					$repeater->add_control(
						'borderless_elementor_slider_slides_horizontal_position_custom',
						[
							'label' => __( 'Horizontal Position', 'borderless' ),
							'type' => Controls_Manager::CHOOSE,
							'default' => 'center',
							'options' => [
								'flex-start' => [
									'title' => __( 'Left', 'borderless' ),
									'icon' => 'eicon-h-align-left',
								],
								'center' => [
									'title' => __( 'Center', 'borderless' ),
									'icon' => 'eicon-h-align-center',
								],
								'flex-end' => [
									'title' => __( 'Right', 'borderless' ),
									'icon' => 'eicon-h-align-right',
								],
							],
							'default' => 'center',
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-slide-content' => 'align-items: {{VALUE}}',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);
		
					$repeater->add_control(
						'borderless_elementor_slider_slides_vertical_position_custom',
						[
							'label' => __( 'Vertical Position', 'borderless' ),
							'type' => Controls_Manager::CHOOSE,
							'default' => 'middle',
							'options' => [
								'flex-start' => [
									'title' => __( 'Top', 'borderless' ),
									'icon' => 'eicon-v-align-top',
								],
								'center' => [
									'title' => __( 'Middle', 'borderless' ),
									'icon' => 'eicon-v-align-middle',
								],
								'flex-end' => [
									'title' => __( 'Bottom', 'borderless' ),
									'icon' => 'eicon-v-align-bottom',
								],
							],
							'default' => 'center',
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-slide-content' => 'justify-content: {{VALUE}}',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);
		
					$repeater->add_control(
						'borderless_elementor_slider_slides_text_align_custom',
						[
							'label' => __( 'Text Align', 'borderless' ),
							'type' => Controls_Manager::CHOOSE,
							'options' => [
								'left' => [
									'title' => __( 'Left', 'borderless' ),
									'icon' => 'eicon-text-align-left',
								],
								'center' => [
									'title' => __( 'Center', 'borderless' ),
									'icon' => 'eicon-text-align-center',
								],
								'right' => [
									'title' => __( 'Right', 'borderless' ),
									'icon' => 'eicon-text-align-right',
								],
							],
							'default' => 'center',
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-slide-content' => 'text-align: {{VALUE}}',
							],
							'conditions' => [
								'terms' => [
									[
										'name' => 'borderless_elementor_slider_slide_custom_style',
										'value' => 'yes',
									],
								],
							],
						]
					);

				$repeater->end_controls_tab();

			$repeater->end_controls_tabs();

			$this->add_control(
				'borderless_elementor_slider_slide_strings',
				[
					'label' => __( 'Slides', 'borderless' ),
					'type' => Controls_Manager::REPEATER,
					'show_label' => true,
					'fields' => $repeater->get_controls(),
					'default' => [
						[
							'borderless_elementor_slider_slide_title' => __( 'Slide #1', 'borderless' ),
							'description' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'borderless' ),
							'button_text' => __( 'Click Here', 'borderless' ),
							'background_color' => '#833ca3',
						],
						[
							'borderless_elementor_slider_slide_title' => __( 'Slide #2', 'borderless' ),
							'description' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'borderless' ),
							'button_text' => __( 'Click Here', 'borderless' ),
							'background_color' => '#4054b2',
						],
						[
							'borderless_elementor_slider_slide_title' => __( 'Slide #3', 'borderless' ),
							'description' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'borderless' ),
							'button_text' => __( 'Click Here', 'borderless' ),
							'background_color' => '#1abc9c',
						],
					],
					'title_field' => '{{{ borderless_elementor_slider_slide_title }}}',
				]
			);

		$this->end_controls_section();

		
		/*-----------------------------------------------------------------------------------*/
		/*  *.  Slider/Slider Layout - Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_slider_layout',
			[
				'label' => esc_html__( 'Slider Layout', 'borderless' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_responsive_control(
				'borderless_elementor_slider_height',
				[
					'label' => __( 'Height', 'borderless' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'vh', 'em' ],
					'range' => [
						'px' => [
							'min' => 300,
							'max' => 9999,
							'step' => 1,
						],
						'vh' => [
							'min' => 10,
							'max' => 100,
						],
						'em' => [
							'min' => 10,
							'max' => 999,
						],
					],
					'default' => [
						'unit' => 'px',
						'size' => 600,
					],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-background-overlay' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_slider_options_show_title',
				[
					'label' => __( 'Show Title', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				]
			);

			$this->add_control(
				'borderless_elementor_slider_options_show_description',
				[
					'label' => __( 'Show Description', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				]
			);

			$this->add_control(
				'borderless_elementor_slider_options_show_button',
				[
					'label' => __( 'Show Button', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				]
			);
			
			$this->add_control(
				'borderless_elementor_slider_options_title_html_tag',
				[
					'label' => esc_html__( 'Title HTML Tag', 'borderless' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
						'div' => 'div',
						'span' => 'span',
						'p' => 'p',
					],
					'default' => 'h2',
					'conditions' => [
						'terms' => [
							[
								'name' => 'borderless_elementor_slider_options_show_title',
								'operator' => '!=',
								'value' => '',
							],
						],
					],
				]
			);

			$this->add_control(
				'borderless_elementor_slider_options_description_html_tag',
				[
					'label' => esc_html__( 'Description HTML Tag', 'borderless' ),
					'type' => Controls_Manager::SELECT,
					'options' => [
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
						'h4' => 'H4',
						'h5' => 'H5',
						'h6' => 'H6',
						'div' => 'div',
						'span' => 'span',
						'p' => 'p',
					],
					'default' => 'span',
					'conditions' => [
						'terms' => [
							[
								'name' => 'borderless_elementor_slider_options_show_description',
								'operator' => '!=',
								'value' => '',
							],
						],
					],
				]
			);

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Slider/Slider Options - Content
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_slider_options',
			[
				'label' => esc_html__( 'Slider Options', 'borderless' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
			$this->add_control(
				'borderless_elementor_slider_options_autoplay',
				[
					'label' => __( 'Autoplay', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				]
			);

			$this->add_control(
				'borderless_elementor_slider_options_autoplay_pause_on_hover',
				[
					'label' => __( 'Pause On Hover', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'condition' => [
						'borderless_elementor_slider_options_autoplay' => 'yes',
					],
					'default' => 'yes',
				]
			);

			$this->add_control(
				'borderless_elementor_slider_options_autoplay_speed',
				[
					'label' => __( 'Autoplay Speed', 'borderless' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 3000,
					'condition' => [
						'borderless_elementor_slider_options_autoplay' => 'yes',
					],
					'render_type' => 'none',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'borderless_elementor_slider_options_draggable',
				[
					'label' => __( 'Draggable', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				]
			);

			$this->add_control(
				'borderless_elementor_slider_options_infinite_scroll',
				[
					'label' => __( 'Infinite Scroll', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
				]
			);

			$this->add_control(
				'borderless_elementor_slider_options_adaptive_height',
				[
					'label' => __( 'Adaptive Height', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
				]
			);

			$this->add_control(
				'borderless_elementor_slider_options_fullscreen',
				[
					'label' => __( 'Fullscreen', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
				]
			);

			$this->add_control(
				'borderless_elementor_slider_options_fade',
				[
					'label' => __( 'Fade', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
				]
			);

			$this->add_control(
				'borderless_elementor_slider_options_thumbs_navigation',
				[
					'label' => __( 'Thumbs Navigation', 'borderless' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
				]
			);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Slider/Slides - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_slider_slides_style',
			[
				'label' => esc_html__( 'Slides', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_responsive_control(
				'borderless_elementor_slider_slides_max_width',
				[
					'label' => __( 'Content Width', 'borderless' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ '%', 'px' ],
					'default' => [
						'size' => '66',
						'unit' => '%',
					],
					'tablet_default' => [
						'unit' => '%',
					],
					'mobile_default' => [
						'unit' => '%',
					],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-content-inner' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_slider_slides_padding',
				[
					'label' => __( 'Padding', 'borderless' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_slider_slides_horizontal_position',
				[
					'label' => __( 'Horizontal Position', 'borderless' ),
					'type' => Controls_Manager::CHOOSE,
					'default' => 'center',
					'options' => [
						'flex-start' => [
							'title' => __( 'Left', 'borderless' ),
							'icon' => 'eicon-h-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'borderless' ),
							'icon' => 'eicon-h-align-center',
						],
						'flex-end' => [
							'title' => __( 'Right', 'borderless' ),
							'icon' => 'eicon-h-align-right',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-content' => 'align-items: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_slider_slides_vertical_position',
				[
					'label' => __( 'Vertical Position', 'borderless' ),
					'type' => Controls_Manager::CHOOSE,
					'default' => 'middle',
					'options' => [
						'flex-start' => [
							'title' => __( 'Top', 'borderless' ),
							'icon' => 'eicon-v-align-top',
						],
						'center' => [
							'title' => __( 'Middle', 'borderless' ),
							'icon' => 'eicon-v-align-middle',
						],
						'flex-end' => [
							'title' => __( 'Bottom', 'borderless' ),
							'icon' => 'eicon-v-align-bottom',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-content' => 'justify-content: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'borderless_elementor_slider_slides_text_align',
				[
					'label' => __( 'Text Align', 'borderless' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => __( 'Left', 'borderless' ),
							'icon' => 'eicon-text-align-left',
						],
						'center' => [
							'title' => __( 'Center', 'borderless' ),
							'icon' => 'eicon-text-align-center',
						],
						'right' => [
							'title' => __( 'Right', 'borderless' ),
							'icon' => 'eicon-text-align-right',
						],
					],
					'default' => 'center',
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-content' => 'text-align: {{VALUE}}',
					],
				]
			);

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Slider/Title - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_slider_title_style',
			[
				'label' => esc_html__( 'Title', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_control(
				'borderless_elementor_section_slider_title_color',
				[
					'label' => __( 'Text Color', 'borderless' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-title' => 'color: {{VALUE}}',

					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'borderless_elementor_section_slider_title_typography',
					'global' => [
						'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' => '{{WRAPPER}} .borderless-elementor-slide-title',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_section_slider_title_padding',
				[
					'label' => __( 'Padding', 'borderless' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_section_slider_title_margin',
				[
					'label' => __( 'Margin', 'borderless' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Slider/Description - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_slider_description_style',
			[
				'label' => esc_html__( 'Description', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_control(
				'borderless_elementor_section_slider_description_color',
				[
					'label' => __( 'Text Color', 'borderless' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-description' => 'color: {{VALUE}}',

					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'borderless_elementor_section_slider_description_typography',
					'global' => [
						'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
					],
					'selector' => '{{WRAPPER}} .borderless-elementor-slide-description',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_section_slider_description_padding',
				[
					'label' => __( 'Padding', 'borderless' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_section_slider_description_margin',
				[
					'label' => __( 'Margin', 'borderless' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();


		/*-----------------------------------------------------------------------------------*/
		/*  *.  Slider/Button - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_slider_button_style',
			[
				'label' => esc_html__( 'Button', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'borderless_elementor_slider_button_typography',
					'global' => [
						'default' => Global_Typography::TYPOGRAPHY_ACCENT,
					],
					'selector' => '{{WRAPPER}} .borderless-elementor-slide-button',
				]
			);

			$this->start_controls_tabs( 'borderless_elementor_slider_button_tabs' );

				$this->start_controls_tab( 'borderless_elementor_slider_button_tab_normal', [ 'label' => __( 'Normal', 'borderless' ) ] );

					$this->add_control(
						'borderless_elementor_slider_button_text_color',
						[
							'label' => __( 'Text Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-slide-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_slider_button_background',
							'label' => esc_html__( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'exclude' => [ 'image' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-slide-button',
							'fields_options' => [
								'background' => [
									'default' => 'classic',
								],
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'borderless_elementor_slider_button_tab_hover', [ 'label' => __( 'Hover', 'borderless' ) ] );

					$this->add_control(
						'borderless_elementor_slider_button_text_color_hover',
						[
							'label' => __( 'Text Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'default' => '',
							'selectors' => [
								'{{WRAPPER}} .borderless-elementor-slide-button:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_slider_button_background_hover',
							'label' => esc_html__( 'Background', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'exclude' => [ 'image' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-slide-button:hover',
							'fields_options' => [
								'background' => [
									'default' => 'classic',
								],
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'borderless_elementor_slider_button_border',
					'selector' => '{{WRAPPER}} .borderless-elementor-slide-button',
					'separator' => 'before',
				]
			);
		
			$this->add_responsive_control(
				'borderless_elementor_slider_button_border_radius',
				[
					'label' => __( 'Border Radius', 'borderless' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_slider_button_box_shadow',
					'exclude' => [
						'box_shadow_position',
					],
					'selector' => '{{WRAPPER}} .borderless-elementor-slide-button',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_slider_button_padding',
				[
					'label' => __( 'Padding', 'borderless' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'separator' => 'before',
					'selectors' => [
						'{{WRAPPER}} .borderless-elementor-slide-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Slider/Arrows - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_slider_arrows_style',
			[
				'label' => esc_html__( 'Arrows', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_control(
				'borderless_elementor_slider_navigation_arrows_position',
				[
					'label' => __( 'Arrows Position', 'borderless' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'borderless-elementor-arrows-position-inside',
					'options' => [
						'borderless-elementor-arrows-position-inside' => __( 'Inside', 'borderless' ),
						'borderless-elementor-arrows-position-outside' => __( 'Outside', 'borderless' ),
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_slider_navigation_arrows_size',
				[
					'label' => __( 'Arrows Size', 'borderless' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .flickity-prev-next-button' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
						'{{WRAPPER}} .flickity-prev-next-button .flickity-button-icon' => 'height: calc(  {{SIZE}}{{UNIT}} - 20px ); width: calc(  {{SIZE}}{{UNIT}} - 20px )',
						'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-arrows-position-outside:not(.is-fullscreen)' => 'width: calc( 100% - 20px - ( {{SIZE}}{{UNIT}} * 2 ) )',
						'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-arrows-position-outside:not(.is-fullscreen) .flickity-prev-next-button.previous' => 'left: calc( -1 * ({{SIZE}}{{UNIT}} + 10px) )',
						'{{WRAPPER}} {{CURRENT_ITEM}} .borderless-elementor-arrows-position-outside:not(.is-fullscreen) .flickity-prev-next-button.next' => 'right: calc( -1 * ({{SIZE}}{{UNIT}} + 10px) )',

						
					],
				]
			);

			$this->start_controls_tabs( 'borderless_elementor_slider_navigation_arrows_tabs' );

				$this->start_controls_tab( 'borderless_elementor_slider_navigation_arrows_tab_normal', [ 'label' => __( 'Normal', 'borderless' ) ] );

					$this->add_control(
						'borderless_elementor_slider_navigation_arrows_color',
						[
							'label' => __( 'Arrows Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .flickity-prev-next-button .flickity-button-icon' => 'fill: {{VALUE}}',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_slider_navigation_arrows_background_color',
							'label' => esc_html__( 'Arrows Background Color', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'exclude' => [ 'image' ],
							'selector' => '{{WRAPPER}} .flickity-prev-next-button',
							'fields_options' => [
								'background' => [
									'default' => 'classic',
								],
								'color' => [
									'default' => '#02010130',
								],
							],
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'borderless_elementor_slider_navigation_arrows_tab_hover', [ 'label' => __( 'Hover', 'borderless' ) ] );

					$this->add_control(
						'borderless_elementor_slider_navigation_arrows_color_hover',
						[
							'label' => __( 'Arrows Color', 'borderless' ),
							'type' => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} {{CURRENT_ITEM}} .flickity-prev-next-button .flickity-button-icon:hover' => 'fill: {{VALUE}}',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_slider_navigation_arrows_background_color_hover',
							'label' => esc_html__( 'Arrows Background Color', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'exclude' => [ 'image' ],
							'selector' => '{{WRAPPER}} .flickity-prev-next-button:hover',
							'fields_options' => [
								'background' => [
									'default' => 'classic',
								],
								'color' => [
									'default' => '#000000',
								],
							],
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'borderless_elementor_slider_navigation_arrows_border',
					'selector' => '{{WRAPPER}} .flickity-prev-next-button',
					'separator' => 'before',
				]
			);
		
			$this->add_responsive_control(
				'borderless_elementor_slider_navigation_arrows_border_radius',
				[
					'label' => __( 'Border Radius', 'borderless' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .flickity-prev-next-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
		
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'borderless_elementor_slider_navigation_arrows_box_shadow',
					'exclude' => [
						'box_shadow_position',
					],
					'selector' => '{{WRAPPER}} .flickity-prev-next-button',
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_slider_navigation_arrows_padding',
				[
					'label' => __( 'Padding', 'borderless' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'separator' => 'before',
					'selectors' => [
						'{{WRAPPER}} .flickity-prev-next-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*  *.  Slider/Dots - Style
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'borderless_elementor_section_slider_dots_style',
			[
				'label' => esc_html__( 'Dots', 'borderless'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

			$this->add_control(
				'borderless_elementor_slider_navigation_dots_position',
				[
					'label' => __( 'Dots Position', 'borderless' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'borderless-elementor-dots-position-inside',
					'options' => [
						'borderless-elementor-dots-position-inside' => __( 'Inside', 'borderless' ),
						'borderless-elementor-dots-position-outside' => __( 'Outside', 'borderless' ),
					],
				]
			);

			$this->add_responsive_control(
				'borderless_elementor_slider_navigation_dots_size',
				[
					'label' => __( 'Dots Size', 'borderless' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .flickity-page-dots .dot' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',

						
					],
				]
			);

			$this->start_controls_tabs( 'borderless_elementor_slider_dots_tabs' );

				$this->start_controls_tab( 'borderless_elementor_slider_navigation_dots_tab_normal', [ 'label' => __( 'Normal', 'borderless' ) ] );

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_slider_navigation_dots_background_color_normal',
							'label' => esc_html__( 'Dots Color', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'exclude' => [ 'image' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-slider .flickity-page-dots .dot',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'borderless_elementor_slider_navigation_dots_tab_hover', [ 'label' => __( 'Hover', 'borderless' ) ] );

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_slider_navigation_dots_background_color_hover',
							'label' => esc_html__( 'Dots Color', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'exclude' => [ 'image' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-slider .flickity-page-dots .dot:hover',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab( 'borderless_elementor_slider_navigation_dots_tab_active', [ 'label' => __( 'Active', 'borderless' ) ] );

					$this->add_group_control(
						Group_Control_Background::get_type(),
						[
							'name' => 'borderless_elementor_slider_navigation_dots_background_color_active',
							'label' => esc_html__( 'Dots Color', 'borderless' ),
							'types' => [ 'classic', 'gradient' ],
							'exclude' => [ 'image' ],
							'selector' => '{{WRAPPER}} .borderless-elementor-slider .flickity-page-dots .dot.is-selected',
						]
					);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
			
	}
		
	/*-----------------------------------------------------------------------------------*/
	/*  *.  Render
	/*-----------------------------------------------------------------------------------*/
	
	protected function render() {
		
		$settings = $this->get_settings_for_display();

		$target = $settings['borderless_elementor_slider_slide_button_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['borderless_elementor_slider_slide_button_link']['nofollow'] ? ' rel="nofollow"' : '';
		
		$this->add_render_attribute( 'slider', 'data-slider-autoplay', $settings['borderless_elementor_slider_options_autoplay'] );
		$this->add_render_attribute( 'slider', 'data-slider-autoplay-pause-on-hover', $settings['borderless_elementor_slider_options_autoplay_pause_on_hover'] );
		$this->add_render_attribute( 'slider', 'data-slider-autoplay-speed', $settings['borderless_elementor_slider_options_autoplay_speed'] );
		$this->add_render_attribute( 'slider', 'data-slider-draggable', $settings['borderless_elementor_slider_options_draggable'] );
		$this->add_render_attribute( 'slider', 'data-slider-infinite-scroll', $settings['borderless_elementor_slider_options_infinite_scroll'] );
		$this->add_render_attribute( 'slider', 'data-slider-adaptative-height', $settings['borderless_elementor_slider_options_adaptive_height'] );
		$this->add_render_attribute( 'slider', 'data-slider-fullscreen', $settings['borderless_elementor_slider_options_fullscreen'] );
		$this->add_render_attribute( 'slider', 'data-slider-fade', $settings['borderless_elementor_slider_options_fade'] );
		$this->add_render_attribute( 'slider', 'data-slider-as-nav-for', $settings['borderless_elementor_slider_options_thumbs_navigation'] );

		?>

		<div class="borderless-elementor-slider-widget" <?php echo $this->get_render_attribute_string( 'slider' ) ?>>

			<div class="borderless-elementor-slider <?php echo wp_kses( ( $settings['borderless_elementor_slider_navigation_arrows_position'] ), true ) .' '. wp_kses( ( $settings['borderless_elementor_slider_navigation_dots_position'] ), true ); ?>">

				<?php if ( $settings['borderless_elementor_slider_slide_strings'] ) {
					foreach (  $settings['borderless_elementor_slider_slide_strings'] as $slider_string ) { 

						$ken_burns = '';

						if ( $slider_string['borderless_elementor_slider_slide_background_image_ken_burns'] ) {
						$ken_burns = ' elementor-ken-burns elementor-ken-burns--' . $slider_string['borderless_elementor_slider_slide_background_image_zoom_direction'];
						}

						?>

						<div class="borderless-elementor-slide <?php echo $ken_burns; ?> elementor-repeater-item-<?php echo $slider_string['_id']; ?> ">

							<div class="borderless-elementor-slide-background-overlay">

								<div class="borderless-elementor-slide-content">

									<div class="borderless-elementor-slide-content-inner">

										<?php if ( $settings['borderless_elementor_slider_options_show_title'] ) { ?> 
											<<?php echo wp_kses( ( $settings['borderless_elementor_slider_options_title_html_tag'] ), true ); ?> class="borderless-elementor-slide-title"><?php echo wp_kses( ( $slider_string['borderless_elementor_slider_slide_title'] ), true ); ?></<?php echo wp_kses( ( $settings['borderless_elementor_slider_options_title_html_tag'] ), true ); ?>>
										<?php } ?>

										<?php if ( $settings['borderless_elementor_slider_options_show_description'] ) { ?> 
											<<?php echo wp_kses( ( $settings['borderless_elementor_slider_options_description_html_tag'] ), true ); ?> class="borderless-elementor-slide-description"><?php echo wp_kses( ( $slider_string['borderless_elementor_slider_slide_description'] ), true ); ?></<?php echo wp_kses( ( $settings['borderless_elementor_slider_options_description_html_tag'] ), true ); ?>>
										<?php } ?>
										
										<?php if ( $settings['borderless_elementor_slider_options_show_button'] ) { ?> 
											<div class="borderless-elementor-slide-button-container">
												<a class="borderless-elementor-slide-button borderless-btn borderless-btn--primary" href="<?php echo wp_kses( ( $slider_string['borderless_elementor_slider_slide_button_link']['url'] ), true ); ?>" <?php echo $target . $nofollow; ?>><?php echo wp_kses( ( $slider_string['borderless_elementor_slider_slide_button_text'] ), true ); ?></a>
											</div>
										<?php } ?>

									</div>

								</div>
								
							</div>

						</div>

					<?php } ?>

				<?php } ?>

			</div>

			<?php if( $settings['borderless_elementor_slider_options_thumbs_navigation'] ) { ?>

				<div class="borderless-elementor-slider-nav">

					<?php if ( $settings['borderless_elementor_slider_slide_strings'] ) {
						foreach (  $settings['borderless_elementor_slider_slide_strings'] as $slider_string ) { 

							?>

							<div class="borderless-elementor-slide elementor-repeater-item-<?php echo $slider_string['_id']; ?> ">

								<div class="borderless-elementor-slide-background-overlay">
								</div>

							</div>

						<?php } ?>

					<?php } ?>

				</div>

			<?php } ?>

		</div>

		<?php
		
	}		
		
}