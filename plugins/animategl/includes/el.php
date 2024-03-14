<?php

use Elementor\Controls_Manager;
use Elementor\PageSettings\Page;
use Elementor\Utils;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/*plugin class*/
class AnimateGL_El
{

	public $version;
	public $path;
	public $plugin_dir_path;
	public $plugin_dir_url;

	// Singleton
	private static $instance = null;

	public static function get_instance()
	{

		if (null == self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	protected function __construct()
	{
		$this->version = ANIMATE_GL_VERSION;
		$this->path = ANIMATE_GL_FILE;
		$this->plugin_dir_path = plugin_dir_path($this->path);
		$this->plugin_dir_url = plugin_dir_url($this->path);

		$this->init();
	}

	public function init()
	{
		add_action('elementor/element/common/section_effects/after_section_end',  [$this, 'add_section']);
		add_action('elementor/element/section/section_effects/after_section_end',  [$this, 'add_section']);
		add_action('elementor/element/column/section_effects/after_section_end',  [$this, 'add_section']);
		add_action('elementor/element/container/section_effects/after_section_end',  [$this, 'add_section']);

		add_action('elementor/element/after_add_attributes', function ($element) {

			$agl_in_name = $element->get_settings('agl_in_name');


			if ($agl_in_name) {
				$classes = $agl_in_name == 'custom' ? 'agl' : 'agl agl-' . $agl_in_name . $element->get_settings('agl_in_direction');
				$duration = $element->get_settings('agl_in_duration')['size'];
				$delay = $element->get_settings('agl_in_delay')['size'];
				$distance = $element->get_settings('agl_in_distance')['size'];
				$classes .= ' agl-in-duration-' . $duration;
				$classes .= ' agl-in-delay-' . $delay;
				$classes .= ' agl-in-distance-' . $distance;
				$easing = $element->get_settings('agl_in_easing');
				if ($easing)
					$classes .= ' agl-in-easing-' . $easing;
				$repeat = $element->get_settings('agl_in_repeat');
				if ($repeat == 'yes')
					$classes .= ' agl-in-repeat';
				$loctToScrollbar = $element->get_settings('agl_in_lockToScrollbar');
					if ($loctToScrollbar == 'yes')
						$classes .= ' agl-in-lockToScrollbar';
				$atts = array();
				$atts['class'] = $classes;

				$element->add_render_attribute(
					'_wrapper',
					$atts
				);
			}
		});

		wp_enqueue_script('agl-el', $this->plugin_dir_url . 'js/el.js', array('jquery'), $this->version);

		add_action('elementor/editor/after_enqueue_styles', function () {
			wp_register_style('agl-el', $this->plugin_dir_url . 'css/el.css', array(), $this->version);
			wp_enqueue_style("agl-el");
		});

		add_action('elementor/editor/after_enqueue_scripts', function () {
			wp_enqueue_script('agl-el-editor', $this->plugin_dir_url . 'js/el-editor.js', array('jquery'), $this->version);
		});
	}

	public function add_section($element)
	{

		$name = $element->get_name();


		$element->start_controls_section(
			'agl_section',
			[
				'label' => 'A n i m a t e  G L',
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'agl_in_name',
			[
				'label' => __('Entrance Animation', 'animate-gl'),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'options' => [
					// '' => __('None', 'animate-gl'),
					// 'fadeCSS' => __('Fade', 'animate-gl'),
					// 'zoomInCSS' => __('Zoom In', 'animate-gl'),
					// 'zoomOutCSS' => __('Zoom Out', 'animate-gl'),
					// 'wipeCSS' => __('Wipe', 'animate-gl'),
					// 'slideCSS' => __('Slide', 'animate-gl'),
					// 'fade' => __('Fade WebGL', 'animate-gl'),
					// 'flip' => __('Flip WebGL', 'animate-gl'),
					// 'slide' => __('Slide WebGL', 'animate-gl'),
					// 'stretch' => __('Stretch WebGL', 'animate-gl'),
					// 'bend' => __('Bend WebGL', 'animate-gl'),
					// 'peel' => __('Peel WebGL', 'animate-gl'),
					// 'wipe' => __('Wipe WebGL', 'animate-gl'),
					// 'zoomIn' => __('Zoom In WebGL', 'animate-gl'),
					// 'custom' => __('Custom', 'animate-gl'),

				],
				'default' => '',
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'agl_in_direction',
			[
				'label' => __('Direction', 'animate-gl'),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'options' => [
					'' => __('Default', 'animate-gl'),
					'Left' => __('Left', 'animate-gl'),
					'Right' => __('Right', 'animate-gl'),
					'Up' => __('Up', 'animate-gl'),
					'Down' => __('Down', 'animate-gl'),

				],
				'default' => '',
				'render_type'        => 'none',
				'frontend_available' => true,
				'condition' => [
					'agl_in_name!' => '',
				],
			]
		);

		$element->add_control(
			'agl_in_duration',
			[
				'label' => esc_html__('Duration', 'animate-gl'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'ms' => [
						'max' => 3000,
						'min' => 0,
						'step' => 100,
					],
				],
				'size_units' => ['ms'],
				'default' => [
					'unit' => 'ms',
					'size' => 1000,
				],
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition' => [
					'agl_in_name!' => '',
				],
			]
		);

		$element->add_control(
			'agl_in_delay',
			[
				'label' => esc_html__('Delay', 'animate-gl'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'ms' => [
						'max' => 5000,
						'min' => 0,
						'step' => 100,
					],
				],
				'size_units' => ['ms'],
				'default' => [
					'unit' => 'ms',
					'size' => 0,
				],
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition' => [
					'agl_in_name!' => '',
				],

			]
		);

		$element->add_control(
			'agl_in_distance',
			[
				'label' => esc_html__('Distance', 'animate-gl'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'ms' => [
						'max' => 5,
						'min' => 0,
						'step' => 0.01,
					],
				],
				'size_units' => [],
				'default' => [
					'unit' => 'ms',
					'size' => 1,
				],
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition' => [
					'agl_in_name!' => '',
				],

			]
		);

		$element->add_control(
			'agl_in_repeat',
			[
				'label' => esc_html__('Repeat', 'animate-gl'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'render_type'        => 'none',
				'frontend_available' => true,
				'condition' => [
					'agl_in_name!' => '',
				],
			]
		);

		$element->add_control(
			'agl_in_lockToScrollbar',
			[
				'label' => esc_html__('Lock to Scrollbar', 'animate-gl'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'render_type'        => 'none',
				'frontend_available' => true,
				'condition' => [
					'agl_in_name!' => '',
				],
			]
		);

		$element->add_control(
			'agl_in_easing',
			[
				'label' => __('Easing', 'animate-gl'),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'options' => [
					'' => __('Default', 'animate-gl'),
					'linear' => __('Linear', 'animate-gl'),
					'easeOutSine' => __('easeOutSine', 'animate-gl'),
					'easeInSine' => __('easeInSine', 'animate-gl'),
					'easeInOutSine' => __('easeInOutSine', 'animate-gl'),
					'easeOutQuad' => __('easeOutQuad', 'animate-gl'),
					'easeInQuad' => __('easeInQuad', 'animate-gl'),
					'easeInOutQuad' => __('easeInOutQuad', 'animate-gl'),
					'easeOutCubic' => __('easeOutCubic', 'animate-gl'),
					'easeInCubic' => __('easeInCubic', 'animate-gl'),
					'easeInOutCubic' => __('easeInOutCubic', 'animate-gl'),
					'easeOutExpo' => __('easeOutExpo', 'animate-gl'),
					'easeInExpo' => __('easeInExpo', 'animate-gl'),
					'easeInOutExpo' => __('easeInOutExpo', 'animate-gl'),
					'easeOutBack' => __('easeOutBack', 'animate-gl'),
					'easeInOutBack' => __('easeInOutBack', 'animate-gl'),
					'easeInBack' => __('easeInBack', 'animate-gl'),

				],
				'default' => '',
				'render_type'        => 'none',
				'frontend_available' => true,
				'condition' => [
					'agl_in_name!' => '',
				],
			]
		);

		$element->end_controls_section(); // END ENTRANCE SECTION / PANEL

	}
}
