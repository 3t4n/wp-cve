<?php

namespace Element_Ready\Widgets\video;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Embed;
use Elementor\Plugin;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor video widget.
 *
 * Elementor widget that displays a video player.
 *
 * @since 1.0.0
 */
class Element_Ready_Widget_Video extends Widget_Base
{

	/**
	 * Get widget name.
	 *
	 * Retrieve video widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'Element_Ready_Widget_Video';
	}

	public function get_script_depends()
	{
		return [
			'element-ready-core',
		];
	}

	public function get_style_depends()
	{

		wp_register_style('eready-video-lite-button', ELEMENT_READY_ROOT_CSS . 'widgets/video-button.css');

		return [
			'eready-video-lite-button'
		];
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve video widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return esc_html__('ER Video', 'element-ready-lite');
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve video widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'eicon-youtube';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the video widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories()
	{
		return ['element-ready-addons'];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords()
	{
		return ['video', 'player', 'embed', 'youtube', 'vimeo', 'dailymotion'];
	}

	/**
	 * Register video widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls()
	{

		$this->start_controls_section(
			'section_video',
			[
				'label' => esc_html__('Video', 'element-ready-lite'),
			]
		);

		$this->add_control(
			'video_type',
			[
				'label'   => esc_html__('Source', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'default' => 'youtube',
				'options' => [
					'youtube'     => esc_html__('YouTube', 'element-ready-lite'),
					'vimeo'       => esc_html__('Vimeo', 'element-ready-lite'),
					'dailymotion' => esc_html__('Dailymotion', 'element-ready-lite'),
					'hosted'      => esc_html__('Self Hosted', 'element-ready-lite'),
				],
			]
		);

		$this->add_control(
			'youtube_url',
			[
				'label'   => esc_html__('Link', 'element-ready-lite'),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__('Enter your URL', 'element-ready-lite') . ' (YouTube)',
				'default'     => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
				'label_block' => true,
				'condition'   => [
					'video_type' => 'youtube',
				],
			]
		);

		$this->add_control(
			'vimeo_url',
			[
				'label'   => esc_html__('Link', 'element-ready-lite'),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__('Enter your URL', 'element-ready-lite') . ' (Vimeo)',
				'default'     => 'https://vimeo.com/235215203',
				'label_block' => true,
				'condition'   => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'dailymotion_url',
			[
				'label'   => esc_html__('Link', 'element-ready-lite'),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__('Enter your URL', 'element-ready-lite') . ' (Dailymotion)',
				'default'     => 'https://www.dailymotion.com/video/x6tqhqb',
				'label_block' => true,
				'condition'   => [
					'video_type' => 'dailymotion',
				],
			]
		);

		$this->add_control(
			'insert_url',
			[
				'label'     => esc_html__('External URL', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'video_type' => 'hosted',
				],
			]
		);

		$this->add_control(
			'hosted_url',
			[
				'label'   => esc_html__('Choose File', 'element-ready-lite'),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => [
					'active'     => true,
					'categories' => [
						TagsModule::MEDIA_CATEGORY,
					],
				],
				'media_type' => 'video',
				'condition'  => [
					'video_type' => 'hosted',
					'insert_url' => '',
				],
			]
		);

		$this->add_control(
			'external_url',
			[
				'label'         => esc_html__('URL', 'element-ready-lite'),
				'type'          => Controls_Manager::URL,
				'autocomplete'  => false,
				'show_external' => false,
				'label_block'   => true,
				'show_label'    => false,
				'dynamic'       => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'media_type'  => 'video',
				'placeholder' => esc_html__('Enter your URL', 'element-ready-lite'),
				'condition'   => [
					'video_type' => 'hosted',
					'insert_url' => 'yes',
				],
			]
		);

		$this->add_control(
			'start',
			[
				'label'       => esc_html__('Start Time', 'element-ready-lite'),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__('Specify a start time (in seconds)', 'element-ready-lite'),
				'condition'   => [
					'loop' => '',
				],
			]
		);

		$this->add_control(
			'end',
			[
				'label'       => esc_html__('End Time', 'element-ready-lite'),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__('Specify an end time (in seconds)', 'element-ready-lite'),
				'condition'   => [
					'loop'       => '',
					'video_type' => ['youtube', 'hosted'],
				],
			]
		);

		$this->add_control(
			'video_options',
			[
				'label'     => esc_html__('Video Options', 'element-ready-lite'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__('Autoplay', 'element-ready-lite'),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'mute',
			[
				'label' => esc_html__('Mute', 'element-ready-lite'),
				'type'  => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'loop',
			[
				'label'     => esc_html__('Loop', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'video_type!' => 'dailymotion',
				],
			]
		);

		$this->add_control(
			'controls',
			[
				'label'     => esc_html__('Player Controls', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__('Hide', 'element-ready-lite'),
				'label_on'  => esc_html__('Show', 'element-ready-lite'),
				'default'   => 'yes',
				'condition' => [
					'video_type!' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'showinfo',
			[
				'label'     => esc_html__('Video Info', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__('Hide', 'element-ready-lite'),
				'label_on'  => esc_html__('Show', 'element-ready-lite'),
				'default'   => 'yes',
				'condition' => [
					'video_type' => ['dailymotion'],
				],
			]
		);

		$this->add_control(
			'modestbranding',
			[
				'label'     => esc_html__('Modest Branding', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'video_type' => ['youtube'],
					'controls'   => 'yes',
				],
			]
		);

		$this->add_control(
			'logo',
			[
				'label'     => esc_html__('Logo', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__('Hide', 'element-ready-lite'),
				'label_on'  => esc_html__('Show', 'element-ready-lite'),
				'default'   => 'yes',
				'condition' => [
					'video_type' => ['dailymotion'],
				],
			]
		);

		$this->add_control(
			'color',
			[
				'label'     => esc_html__('Controls Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => [
					'video_type' => ['vimeo', 'dailymotion'],
				],
			]
		);

		// YouTube.
		$this->add_control(
			'yt_privacy',
			[
				'label'       => esc_html__('Privacy Mode', 'element-ready-lite'),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__('When you turn on privacy mode, YouTube won\'t store information about visitors on your website unless they play the video.', 'element-ready-lite'),
				'condition'   => [
					'video_type' => 'youtube',
				],
			]
		);

		$this->add_control(
			'rel',
			[
				'label'   => esc_html__('Suggested Videos', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''    => esc_html__('Current Video Channel', 'element-ready-lite'),
					'yes' => esc_html__('Any Video', 'element-ready-lite'),
				],
				'condition' => [
					'video_type' => 'youtube',
				],
			]
		);

		// Vimeo.
		$this->add_control(
			'vimeo_title',
			[
				'label'     => esc_html__('Intro Title', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__('Hide', 'element-ready-lite'),
				'label_on'  => esc_html__('Show', 'element-ready-lite'),
				'default'   => 'yes',
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'vimeo_portrait',
			[
				'label'     => esc_html__('Intro Portrait', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__('Hide', 'element-ready-lite'),
				'label_on'  => esc_html__('Show', 'element-ready-lite'),
				'default'   => 'yes',
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'vimeo_byline',
			[
				'label'     => esc_html__('Intro Byline', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__('Hide', 'element-ready-lite'),
				'label_on'  => esc_html__('Show', 'element-ready-lite'),
				'default'   => 'yes',
				'condition' => [
					'video_type' => 'vimeo',
				],
			]
		);

		$this->add_control(
			'download_button',
			[
				'label'     => esc_html__('Download Button', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__('Hide', 'element-ready-lite'),
				'label_on'  => esc_html__('Show', 'element-ready-lite'),
				'condition' => [
					'video_type' => 'hosted',
				],
			]
		);

		$this->add_control(
			'poster',
			[
				'label'     => esc_html__('Poster', 'element-ready-lite'),
				'type'      => Controls_Manager::MEDIA,
				'condition' => [
					'video_type' => 'hosted',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label'   => esc_html__('View', 'element-ready-lite'),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'youtube',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_overlay',
			[
				'label' => esc_html__('Image Overlay', 'element-ready-lite'),
			]
		);

		$this->add_control(
			'show_image_overlay',
			[
				'label'     => esc_html__('Image Overlay', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__('Hide', 'element-ready-lite'),
				'label_on'  => esc_html__('Show', 'element-ready-lite'),
			]
		);

		$this->add_control(
			'image_overlay',
			[
				'label'   => esc_html__('Choose Image', 'element-ready-lite'),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_image_hover_overlay',
			[
				'label'     => esc_html__('Hover Image', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__('Yes', 'element-ready-lite'),
				'label_on'  => esc_html__('No', 'element-ready-lite'),
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'image_hover_overlay',
			[
				'label'   => esc_html__('Choose Image', 'element-ready-lite'),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_image_hover_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'lazy_load',
			[
				'label'     => esc_html__('Lazy Load', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => [
					'show_image_overlay' => 'yes',
					'video_type!'        => 'hosted',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image_overlay',   // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_overlay_size` and `image_overlay_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
				'condition' => [
					'show_image_overlay' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_play_icon',
			[
				'label'     => esc_html__('Show Play Icon ?', 'element-ready-lite'),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => [
					'show_image_overlay'  => 'yes',
					'image_overlay[url]!' => '',
				],
			]
		);

		$this->add_control(
			'player_play_icon',
			[
				'label'     => esc_html__('SVG / Font Icon', 'element-ready-lite'),
				'type'      => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-play',
					'library' => 'solid',
				],
				'condition' => [
					'show_play_icon'  => 'yes',
				],
			]
		);

		$this->add_control(
			'lightbox',
			[
				'label'              => esc_html__('Lightbox', 'element-ready-lite'),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'label_off'          => esc_html__('Off', 'element-ready-lite'),
				'label_on'           => esc_html__('On', 'element-ready-lite'),
				'condition'          => [
					'show_image_overlay'  => 'yes',
					'image_overlay[url]!' => '',
				],
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_video_style',
			[
				'label' => esc_html__('Video', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'aspect_ratio',
			[
				'label'   => esc_html__('Aspect Ratio', 'element-ready-lite'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'169' => '16:9',
					'219' => '21:9',
					'43'  => '4:3',
					'32'  => '3:2',
					'11'  => '1:1',
				],
				'default'            => '169',
				'prefix_class'       => 'elementor-aspect-ratio-',
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .elementor-wrapper',
			]
		);
		// Video Height
		$this->add_responsive_control(
			'er_video_height',
			[
				'label'      => esc_html__('Height', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-custom-embed-image-overlay img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__('Icon', 'element-ready-lite'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'icon_style_tabs'
		);

		$this->start_controls_tab(
			'style_icon_normal_tab',
			[
				'label' => esc_html__('Normal', 'element-ready-lite'),
			]
		);

		$this->add_responsive_control(
			'play_icon_size',
			[
				'label' => esc_html__('Icon Font Size', 'element-ready-lite'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .video__play__button' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon'     => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'play_icon_line_height_size',
			[
				'label' => esc_html__('Line height', 'element-ready-lite'),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 400,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .video__play__button' => 'line-height: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_play_icon'     => 'yes',
				],
			]
		);

		$this->add_control(
			'play_icon_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .video__play__button' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon'     => 'yes',
				],
			]
		);

		// Width
		$this->add_responsive_control(
			'play_icon_width',
			[
				'label'      => esc_html__('Width', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .video__play__button' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		// Height
		$this->add_responsive_control(
			'play_icon_height',
			[
				'label'      => esc_html__('Height', 'element-ready-lite'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .video__play__button' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'play_icon_backgroundd',
				'label' => esc_html__('Background', 'element-ready-lite'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .video__play__button,{{WRAPPER}} .video__play__button:before',
			]
		);

		$this->add_responsive_control(
			'play_border_radius',
			[
				'label'      => esc_html__('Border Radius', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .video__play__button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'           => 'play_icon_box_shadow',
				'selector'       => '{{WRAPPER}} .video__play__button',
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon'     => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'play_icon_box_padding',
			[
				'label'      => esc_html__('Padding', 'element-ready-lite'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .video__play__button i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'style_icon_hover_tab',
			[
				'label' => esc_html__('Hover', 'element-ready-lite'),
			]
		);

		$this->add_control(
			'play_icon_hover_color',
			[
				'label'     => esc_html__('Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .video__play__button:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon'     => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'play_icon_hover_backgroundd',
				'label' => esc_html__('Background', 'element-ready-lite'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .video__play__button:hover,{{WRAPPER}} .video__play__button:hover:before',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'           => 'play_icon_hover_box_shadow',
				'selector'       => '{{WRAPPER}} .video__play__button:hover',
				'condition' => [
					'show_image_overlay' => 'yes',
					'show_play_icon'     => 'yes',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'section_lightbox_style',
			[
				'label'     => esc_html__('Lightbox', 'element-ready-lite'),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_image_overlay'  => 'yes',
					'image_overlay[url]!' => '',
					'lightbox'            => 'yes',
				],
			]
		);

		$this->add_control(
			'lightbox_color',
			[
				'label'     => esc_html__('Background Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-{{ID}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_color',
			[
				'label'     => esc_html__('UI Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'lightbox_ui_color_hover',
			[
				'label'     => esc_html__('UI Hover Color', 'element-ready-lite'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-{{ID}} .dialog-lightbox-close-button:hover' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'lightbox_video_width',
			[
				'label'   => esc_html__('Content Width', 'element-ready-lite'),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 50,
					],
				],
				'selectors' => [
					'(desktop+)#elementor-lightbox-{{ID}} .elementor-video-container' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'lightbox_content_position',
			[
				'label'              => esc_html__('Content Position', 'element-ready-lite'),
				'type'               => Controls_Manager::SELECT,
				'frontend_available' => true,
				'options'            => [
					''    => esc_html__('Center', 'element-ready-lite'),
					'top' => esc_html__('Top', 'element-ready-lite'),
				],
				'selectors' => [
					'#elementor-lightbox-{{ID}} .elementor-video-container' => '{{VALUE}}; transform: translateX(-50%);',
				],
				'selectors_dictionary' => [
					'top' => 'top: 60px',
				],
			]
		);

		$this->add_responsive_control(
			'lightbox_content_animation',
			[
				'label'              => esc_html__('Entrance Animation', 'element-ready-lite'),
				'type'               => Controls_Manager::ANIMATION,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render video widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();

		$video_url = $settings[$settings['video_type'] . '_url'];
		$embed_params = [];
		$embed_options = [];
		if ('hosted' === $settings['video_type']) {
			$video_url = $this->get_hosted_video_url();
		}

		if (empty($video_url)) {
			return;
		}

		$embed_params = $this->get_embed_params();

		$embed_options = $this->get_embed_options();

		if ('hosted' === $settings['video_type']) {
			ob_start();

			$this->render_hosted_video();

			$video_html = ob_get_clean();
		} else {

			$video_html = Embed::get_embed_html($video_url, $embed_params, $embed_options);
		}

		if (empty($video_html)) {
			echo esc_url($video_url);

			return;
		}

		$this->add_render_attribute('video-wrapper', 'class', 'elementor-wrapper');

		if (!$settings['lightbox']) {
			$this->add_render_attribute('video-wrapper', 'class', 'elementor-fit-aspect-ratio');
		}

		$this->add_render_attribute('video-wrapper', 'class', 'elementor-open-' . ($settings['lightbox'] ? 'lightbox' : 'inline'));
?>
		<div <?php echo $this->get_render_attribute_string('video-wrapper'); ?>>
			<?php
			if (!$settings['lightbox']) {
				echo Embed::get_embed_html($video_url, $embed_params, $embed_options);
			}

			if ($this->has_image_overlay()) {
				$this->add_render_attribute('image-overlay', 'class', 'elementor-custom-embed-image-overlay');
				if ($settings['lightbox']) {
					if ('hosted' === $settings['video_type']) {
						$lightbox_url = $video_url;
					} else {
						$lightbox_url = Embed::get_embed_url($video_url, $embed_params, $embed_options);
					}

					$lightbox_options = [
						'type'         => 'video',
						'videoType'    => $settings['video_type'],
						'url'          => $lightbox_url,
						'modalOptions' => [
							'id'                       => 'elementor-lightbox-' . $this->get_id(),
							'entranceAnimation'        => $settings['lightbox_content_animation'],
							'entranceAnimation_tablet' => $settings['lightbox_content_animation_tablet'],
							'entranceAnimation_mobile' => $settings['lightbox_content_animation_mobile'],
							'videoAspectRatio'         => $settings['aspect_ratio'],
						],
					];

					if ('hosted' === $settings['video_type']) {
						$lightbox_options['videoParams'] = $this->get_hosted_params();
					}

					$this->add_render_attribute('image-overlay', [
						'data-elementor-open-lightbox' => 'yes',
						'data-elementor-lightbox'      => wp_json_encode($lightbox_options),
					]);

					if (Plugin::$instance->editor->is_edit_mode()) {
						$this->add_render_attribute('image-overlay', [
							'class' => 'elementor-clickable',
						]);
					}
				} else {
					$this->add_render_attribute('image-overlay', 'style', 'background-image: url(' . Group_Control_Image_Size::get_attachment_image_src($settings['image_overlay']['id'], 'image_overlay', $settings) . ');');
				}
			?>
				<div <?php echo $this->get_render_attribute_string('image-overlay'); ?>>
					<?php if ($settings['lightbox']) : ?>
						<?php echo Group_Control_Image_Size::get_attachment_image_html($settings, 'image_overlay'); ?>
						<?php if (isset($settings['show_image_hover_overlay']) && $settings['show_image_hover_overlay'] == 'yes') : ?>
							<?php
							if (isset($settings['image_hover_overlay']['url']) && $settings['image_hover_overlay']['url'] != '') {
								echo Group_Control_Image_Size::get_attachment_image_html($settings, 'image_hover_overlay');
							}
							?>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ('yes' === $settings['show_play_icon']) : ?>
						<div class="video__play__button" role="button">
							<?php Icons_Manager::render_icon($settings['player_play_icon']); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php } ?>
		</div>
	<?php
	}

	/**
	 * Render video widget as plain content.
	 *
	 * Override the default behavior, by printing the video URL insted of rendering it.
	 *
	 * @since 1.4.5
	 * @access public
	 */
	public function render_plain_content()
	{
		$settings = $this->get_settings_for_display();

		if ('hosted' !== $settings['video_type']) {
			$url = $settings[$settings['video_type'] . '_url'];
		} else {
			$url = $this->get_hosted_video_url();
		}

		echo esc_url($url);
	}

	/**
	 * Get embed params.
	 *
	 * Retrieve video widget embed parameters.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @return array Video embed parameters.
	 */
	public function get_embed_params()
	{

		$settings = $this->get_settings_for_display();

		$params = [];

		if ($settings['autoplay'] && !$this->has_image_overlay()) {
			$params['autoplay'] = '1';
		}

		$params_dictionary = [];

		if ('youtube' === $settings['video_type']) {
			$params_dictionary = [
				'loop',
				'controls',
				'mute',
				'rel',
				'modestbranding',
			];

			if ($settings['loop']) {
				$video_properties = Embed::get_video_properties($settings['youtube_url']);

				$params['playlist'] = $video_properties['video_id'];
			}

			$params['start'] = $settings['start'];

			$params['end'] = $settings['end'];

			$params['wmode'] = 'opaque';
		} elseif ('vimeo' === $settings['video_type']) {
			$params_dictionary = [
				'loop',
				'mute'           => 'muted',
				'vimeo_title'    => 'title',
				'vimeo_portrait' => 'portrait',
				'vimeo_byline'   => 'byline',
			];

			$params['color'] = str_replace('#', '', $settings['color']);

			$params['autopause'] = '0';
		} elseif ('dailymotion' === $settings['video_type']) {
			$params_dictionary = [
				'controls',
				'mute',
				'showinfo' => 'ui-start-screen-info',
				'logo'     => 'ui-logo',
			];

			$params['ui-highlight'] = str_replace('#', '', $settings['color']);

			$params['start'] = $settings['start'];

			$params['endscreen-enable'] = '0';
		}

		foreach ($params_dictionary as $key => $param_name) {
			$setting_name = $param_name;

			if (is_string($key)) {
				$setting_name = $key;
			}

			$setting_value = $settings[$setting_name] ? '1' : '0';

			$params[$param_name] = $setting_value;
		}

		return is_array($params) ? $params : [];
	}

	/**
	 * Whether the video widget has an overlay image or not.
	 *
	 * Used to determine whether an overlay image was set for the video.
	 *
	 * @since 1.0.0
	 * @access protected
	 *
	 * @return bool Whether an image overlay was set for the video.
	 */
	protected function has_image_overlay()
	{
		$settings = $this->get_settings_for_display();

		return !empty($settings['image_overlay']['url']) && 'yes' === $settings['show_image_overlay'];
	}

	/**
	 * @since 2.1.0
	 * @access private
	 */
	private function get_embed_options()
	{
		$settings = $this->get_settings_for_display();

		$embed_options = [];

		if ('youtube' === $settings['video_type']) {
			$embed_options['privacy'] = $settings['yt_privacy'];
		} elseif ('vimeo' === $settings['video_type']) {
			$embed_options['start'] = $settings['start'];
		}

		$embed_options['lazy_load'] = !empty($settings['lazy_load']);

		return $embed_options;
	}

	/**
	 * @since 2.1.0
	 * @access private
	 */
	private function get_hosted_params()
	{
		$settings = $this->get_settings_for_display();

		$video_params = [];

		foreach (['autoplay', 'loop', 'controls'] as $option_name) {
			if ($settings[$option_name]) {
				$video_params[$option_name] = '';
			}
		}

		if ($settings['mute']) {
			$video_params['muted'] = 'muted';
		}

		if (!$settings['download_button']) {
			$video_params['controlsList'] = 'nodownload';
		}

		if ($settings['poster']['url']) {
			$video_params['poster'] = $settings['poster']['url'];
		}

		return $video_params;
	}

	/**
	 * @param bool $from_media
	 *
	 * @return string
	 * @since 2.1.0
	 * @access private
	 */
	private function get_hosted_video_url()
	{
		$settings = $this->get_settings_for_display();

		if (!empty($settings['insert_url'])) {
			$video_url = $settings['external_url']['url'];
		} else {
			$video_url = $settings['hosted_url']['url'];
		}

		if (empty($video_url)) {
			return '';
		}

		if ($settings['start'] || $settings['end']) {
			$video_url .= '#t=';
		}

		if ($settings['start']) {
			$video_url .= $settings['start'];
		}

		if ($settings['end']) {
			$video_url .= ',' . $settings['end'];
		}

		return $video_url;
	}

	/**
	 *
	 * @since 2.1.0
	 * @access private
	 */
	private function render_hosted_video()
	{
		$video_url = $this->get_hosted_video_url();
		if (empty($video_url)) {
			return;
		}
		$video_params = $this->get_hosted_params();
	?>
		<video class="elementor-video" src="<?php echo esc_url($video_url); ?>" <?php echo Utils::render_html_attributes($video_params); ?>></video>
<?php
	}
}
