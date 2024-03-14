<?php

namespace XproElementorAddons\Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

/**
 * SlideEx
 *
 * Elementor widget.
 *
 * @since 0.1.8
 */
class Hero_Slider extends Widget_Base {


	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_style( 'swiper', XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/css/swiper.min.css', null, '8.4.5' );
		wp_register_script( 'swiper', XPRO_ELEMENTOR_ADDONS_ASSETS . 'vendor/js/swiper.min.js', array( 'jquery' ), '8.4.5', true );
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-hero-slider';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Hero Slider', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-banner widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 0.1.8
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
	 * @since 0.1.8
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'slider', 'hero', 'banner' );
	}

	/**
	 * Retrieve the list of style the widget depended on.
	 *
	 * Used to set style dependencies required to run the widget.
	 *
	 * @return array Widget style dependencies.
	 * @since 0.1.8
	 *
	 * @access public
	 *
	 */
	public function get_style_depends() {

		return array( 'swiper', 'animate' );

	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 0.1.8
	 *
	 * @access public
	 *
	 */

	public function get_script_depends() {

		return array( 'swiper' );

	}

	public function get_animation_effects( $exclude = array() ) {

		$effects = array(
			'none'              => 'None',
			'fadeIn'            => 'Fade In',
			'fadeInDown'        => 'Fade In Down',
			'fadeInLeft'        => 'Fade In Left',
			'fadeInRight'       => 'Fade In Right',
			'fadeInUp'          => 'Fade In Up',
			'x-mask-top'        => 'Mask From Up',
			'x-mask-right'      => 'Mask From Right',
			'x-mask-bottom'     => 'Mask From Bottom',
			'x-mask-left'       => 'Mask From Left',
			'x-reveal-top'      => 'Reveal From Up',
			'x-reveal-right'    => 'Reveal From Right',
			'x-reveal-bottom'   => 'Reveal From Bottom',
			'x-reveal-left'     => 'Reveal From Left',
			'x-split-top'       => 'Splitting Text Top',
			'x-split-right'     => 'Splitting Text Right',
			'x-split-bottom'    => 'Splitting Text Bottom',
			'x-split-left'      => 'Splitting Text Left',
			'x-split-expand'    => 'Splitting Text Expand',
			'x-blurIn'          => 'Blur In',
			'x-blurInLeft'      => 'Blur In Left',
			'x-blurInRight'     => 'Blur In Right',
			'x-blurInTop'       => 'Blur In Top',
			'x-blurInBottom'    => 'Blur In Bottom',
			'zoomIn'            => 'Zoom In',
			'zoomInDown'        => 'Zoom In Down',
			'zoomInLeft'        => 'Zoom In Left',
			'zoomInRight'       => 'Zoom In Right',
			'zoomInUp'          => 'Zoom In Up',
			'bounceIn'          => 'Bounce In',
			'bounceInDown'      => 'Bounce In Down',
			'bounceInLeft'      => 'Bounce In Left',
			'bounceInRight'     => 'Bounce In Right',
			'bounceInUp'        => 'Bounce In Up',
			'slideInDown'       => 'Slide In Down',
			'slideInLeft'       => 'Slide In Left',
			'slideInRight'      => 'Slide In Right',
			'slideInUp'         => 'Slide In Up',
			'rotateIn'          => 'Rotate In',
			'rotateInDownLeft'  => 'Rotate In Down Left',
			'rotateInDownRight' => 'Rotate In Down Right',
			'rotateInUpLeft'    => 'Rotate In Up Left',
			'rotateInUpRight'   => 'Rotate In Up Right',
			'bounce'            => 'Bounce',
			'flash'             => 'Flash',
			'pulse'             => 'Pulse',
			'rubberBand'        => 'Rubber Band',
			'shake'             => 'Shake',
			'headShake'         => 'Head Shake',
			'swing'             => 'Swing',
			'tada'              => 'Tada',
			'wobble'            => 'Wobble',
			'jello'             => 'Jello',
		);

		if ( ! empty( $exclude ) ) {
			foreach ( $exclude as $key ) {
				if ( isset( $effects[ $key ] ) && $effects[ $key ] ) {
					unset( $effects[ $key ] );
				}
			}
		}

		return $effects;
	}

	public function render_title( $settings ) {

		$target   = $settings['title_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['title_link']['nofollow'] ? ' rel="nofollow"' : '';

		$html = '';

		if ( 'yes' === $settings['enable_title'] && ! empty( $settings['title'] ) ) {

			$title = $settings['title'];

			$html .= '<div class="xpro-hero-slider-title-wrapper">';

			if ( ! empty( $settings['title_link']['url'] ) ) {
				$html .= '<a href="' . esc_url( $settings['title_link']['url'] ) . '"' . $target . $nofollow . '>';
			}

			if ( str_contains( $settings['title_animation_effect'], 'split' ) ) {
				$split_text = '';

				foreach ( str_split( $settings['title'] ) as $i => $char ) {
					$split_text .= '<span class="char" data-char="' . $char . '" style="--char-index: ' . $i . ';">' . $char . '</span>';
				}
				$title = $split_text;
			}

			$html .= '<' . esc_attr( $settings['title_tag'] ) . ' class="xpro-hero-slider-title" data-animation="' . $settings['title_animation_effect'] . '">';
			$html .= $title;
			$html .= '</' . esc_attr( $settings['title_tag'] ) . '>';

			if ( ! empty( $settings['title_link']['url'] ) ) {
				$html .= '</a>';
			}
			$html .= '</div>';
		}

		return $html;
	}

	public function render_subtitle( $settings ) {

		$html = '';

		if ( 'yes' === $settings['enable_subtitle'] && ! empty( $settings['subtitle'] ) ) {

			$subtitle = $settings['subtitle'];

			if ( str_contains( $settings['subtitle_animation_effect'], 'split' ) ) {
				$split_text = '';

				foreach ( str_split( $settings['subtitle'] ) as $i => $char ) {
					$split_text .= '<span class="char" data-char="' . $char . '" style="--char-index: ' . $i . ';">' . $char . '</span>';
				}
				$subtitle = $split_text;
			}
			$html .= '<div class="xpro-hero-slider-subtitle-wrapper">';
			$html .= '<' . esc_attr( $settings['subtitle_tag'] ) . ' class="xpro-hero-slider-subtitle" data-animation="' . $settings['subtitle_animation_effect'] . '">';
			$html .= $subtitle;
			$html .= '</' . esc_attr( $settings['subtitle_tag'] ) . '>';
			$html .= '</div>';
		}

		return $html;
	}


	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 0.1.8
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_slides',
			array(
				'label' => __( 'Slides', 'xpro-elementor-addons' ),
			)
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'slide_tab' );

		$repeater->start_controls_tab(
			'slide_bg',
			array(
				'label' => esc_html__( 'Background', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'slide_bg',
				'label'    => esc_html__( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-slide-bg',
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'slide_overlay',
			array(
				'label' => esc_html__( 'Overlay', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'slide_overlay',
				'label'    => esc_html__( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-slide-bg::before',
			)
		);

		$repeater->add_control(
			'slide_overly_blend_mode',
			array(
				'label'     => esc_html__( 'Blend Mode', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'none',
				'options'   => array(
					'inherit'     => 'Normal',
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'difference'  => 'Difference',
					'exclusion'   => 'Exclusion',
					'hue'         => 'Hue',
					'luminosity'  => 'Luminosity',
				),
				'default'   => 'inherit',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-slide-bg::before' => 'mix-blend-mode: {{VALUE}}',
				),
			)
		);

		$repeater->add_responsive_control(
			'slide_overlay_opacity',
			array(
				'label'      => esc_html__( 'Opacity', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 0.5,
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-slide-bg::before' => 'opacity: {{SIZE}};',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$repeater->add_responsive_control(
			'horizontal_align',
			array(
				'label'                => __( 'Horizontal Align', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'separator'            => 'before',
				'default'              => 'center',
				'options'              => array(
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
				'selectors_dictionary' => array(
					'left'   => 'justify-content: left; text-align: left;',
					'center' => 'justify-content: center; text-align: center;',
					'right'  => 'justify-content: right; text-align: left;',
				),
				'selectors'            => array(
					'{{WRAPPER}} .xpro-hero-slider {{CURRENT_ITEM}} .xpro-hero-slider-slide-content-wrapper' => '{{VALUE}};',
				),
			)
		);

		$repeater->add_responsive_control(
			'vertical_align',
			array(
				'label'                => __( 'Vertical Align', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'middle',
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors_dictionary' => array(
					'top'    => 'align-items: flex-start;',
					'middle' => 'align-items: center;',
					'bottom' => 'align-items: flex-end;',
				),
				'selectors'            => array(
					'{{WRAPPER}} .xpro-hero-slider {{CURRENT_ITEM}} .xpro-hero-slider-slide-content-wrapper' => '{{VALUE}};',
				),
			)
		);

		//Subtitle
		$repeater->add_control(
			'enable_subtitle',
			array(
				'label'     => esc_html__( 'Subtitle', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$repeater->start_controls_tabs(
			'subtitle_tab',
			array(
				'condition' => array(
					'enable_subtitle' => 'yes',
				),
			)
		);

		$repeater->start_controls_tab(
			'subtitle_content',
			array(
				'label' => esc_html__( 'Content', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'subtitle',
			array(
				'type'       => Controls_Manager::TEXTAREA,
				'default'    => __( 'Subtitle', 'xpro-elementor-addons' ),
				'show_label' => false,
				'dynamic'    => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'subtitle_position',
			array(
				'label'     => __( 'Position', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'before_title',
				'options'   => array(
					'before_title' => __( 'Before Title', 'xpro-elementor-addons' ),
					'after_title'  => __( 'After Title', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'show_subtitle' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'subtitle_tag',
			array(
				'label'   => __( 'HTML Tag', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'h1' => array(
						'subtitle' => __( 'H1', 'xpro-elementor-addons' ),
						'icon'     => 'eicon-editor-h1',
					),
					'h2' => array(
						'subtitle' => __( 'H2', 'xpro-elementor-addons' ),
						'icon'     => 'eicon-editor-h2',
					),
					'h3' => array(
						'subtitle' => __( 'H3', 'xpro-elementor-addons' ),
						'icon'     => 'eicon-editor-h3',
					),
					'h4' => array(
						'subtitle' => __( 'H4', 'xpro-elementor-addons' ),
						'icon'     => 'eicon-editor-h4',
					),
					'h5' => array(
						'subtitle' => __( 'H5', 'xpro-elementor-addons' ),
						'icon'     => 'eicon-editor-h5',
					),
					'h6' => array(
						'subtitle' => __( 'H6', 'xpro-elementor-addons' ),
						'icon'     => 'eicon-editor-h6',
					),
				),
				'default' => 'h4',
				'toggle'  => false,
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'subtitle_animation',
			array(
				'label' => esc_html__( 'Animation', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'subtitle_animation_effect',
			array(
				'label'   => esc_html__( 'Effect', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => $this->get_animation_effects(),
			)
		);

		$repeater->add_control(
			'subtitle_animation_reveal_color',
			array(
				'label'     => __( 'Reveal Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-subtitle::after'   => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'subtitle_animation_effect' => array( 'x-reveal-top', 'x-reveal-bottom', 'x-reveal-left', 'x-reveal-right' ),
				),
			)
		);

		$repeater->add_control(
			'subtitle_animation_duration',
			array(
				'label'      => esc_html__( 'Duration(s)', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 1,
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-subtitle,{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-subtitle span.char' => 'animation-duration: {{SIZE}}s;',
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-subtitle::after' => 'animation-duration: {{SIZE}}s;',
				),
			)
		);

		$repeater->add_control(
			'subtitle_animation_delay',
			array(
				'label'      => esc_html__( 'Delay(s)', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 0,
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-subtitle' => 'animation-delay: {{SIZE}}s; -webkit-animation-delay: {{SIZE}}s',
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-subtitle::after' => 'animation-delay: {{SIZE}}s; -webkit-animation-delay: {{SIZE}}s',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		//Title
		$repeater->add_control(
			'enable_title',
			array(
				'label'     => esc_html__( 'Title', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$repeater->start_controls_tabs(
			'title_tab',
			array(
				'condition' => array(
					'enable_title' => 'yes',
				),
			)
		);

		$repeater->start_controls_tab(
			'title_content',
			array(
				'label' => esc_html__( 'Content', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'type'       => Controls_Manager::TEXTAREA,
				'default'    => __( 'Title Caption', 'xpro-elementor-addons' ),
				'show_label' => false,
				'dynamic'    => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'title_link',
			array(
				'label'       => __( 'Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'title_tag',
			array(
				'label'   => __( 'HTML Tag', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'h1' => array(
						'title' => __( 'H1', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-editor-h1',
					),
					'h2' => array(
						'title' => __( 'H2', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-editor-h2',
					),
					'h3' => array(
						'title' => __( 'H3', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-editor-h3',
					),
					'h4' => array(
						'title' => __( 'H4', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-editor-h4',
					),
					'h5' => array(
						'title' => __( 'H5', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-editor-h5',
					),
					'h6' => array(
						'title' => __( 'H6', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-editor-h6',
					),
				),
				'default' => 'h2',
				'toggle'  => false,
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'title_animation',
			array(
				'label' => esc_html__( 'Animation', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'title_animation_effect',
			array(
				'label'   => esc_html__( 'Effect', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => $this->get_animation_effects(),
			)
		);

		$repeater->add_control(
			'title_animation_reveal_color',
			array(
				'label'     => __( 'Reveal Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-title::after'   => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'title_animation_effect' => array( 'x-reveal-top', 'x-reveal-bottom', 'x-reveal-left', 'x-reveal-right' ),
				),
			)
		);

		$repeater->add_control(
			'title_animation_duration',
			array(
				'label'      => esc_html__( 'Duration(s)', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 1,
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-title,{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-title span.char' => 'animation-duration: {{SIZE}}s;',
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-title::after' => 'animation-duration: {{SIZE}}s;',
				),
			)
		);

		$repeater->add_control(
			'title_animation_delay',
			array(
				'label'      => esc_html__( 'Delay(s)', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 0,
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-description' => 'animation-delay: {{SIZE}}s; -webkit-animation-delay: {{SIZE}}s',
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-description::after' => 'animation-delay: {{SIZE}}s; -webkit-animation-delay: {{SIZE}}s',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		//Description
		$repeater->add_control(
			'enable_description',
			array(
				'label'     => esc_html__( 'Description', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$repeater->start_controls_tabs(
			'description_tab',
			array(
				'condition' => array(
					'enable_description' => 'yes',
				),
			)
		);

		$repeater->start_controls_tab(
			'description_content',
			array(
				'label' => esc_html__( 'Content', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'description',
			array(
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => __( 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout normal distribution of letters.', 'xpro-elementor-addons' ),
				'show_label' => false,
				'dynamic'    => array(
					'active' => true,
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'description_animation',
			array(
				'label' => esc_html__( 'Animation', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'description_animation_effect',
			array(
				'label'   => esc_html__( 'Effect', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => $this->get_animation_effects( array( 'x-split-top', 'x-split-right', 'x-split-bottom', 'x-split-left', 'x-split-expand' ) ),
			)
		);

		$repeater->add_control(
			'description_animation_reveal_color',
			array(
				'label'     => __( 'Reveal Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-description::after'   => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'description_animation_effect' => array( 'x-reveal-top', 'x-reveal-bottom', 'x-reveal-left', 'x-reveal-right' ),
				),
			)
		);

		$repeater->add_control(
			'description_animation_duration',
			array(
				'label'      => esc_html__( 'Duration(s)', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 1,
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-description' => 'animation-duration: {{SIZE}}s;',
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-description::after' => 'animation-duration: {{SIZE}}s;',
				),
			)
		);

		$repeater->add_control(
			'description_animation_delay',
			array(
				'label'      => esc_html__( 'Delay(s)', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 0,
				),
				'selectors'  => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-description' => 'animation-delay: {{SIZE}}s; -webkit-animation-delay: {{SIZE}}s',
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-hero-slider-description::after' => 'animation-delay: {{SIZE}}s; -webkit-animation-delay: {{SIZE}}s',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		//Primary Button
		$repeater->add_control(
			'enable_primary_button',
			array(
				'label'     => esc_html__( 'Primary Button', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$repeater->start_controls_tabs(
			'primary_button_tab',
			array(
				'condition' => array(
					'enable_primary_button' => 'yes',
				),
			)
		);

		$repeater->start_controls_tab(
			'primary_button_content',
			array(
				'label' => esc_html__( 'Content', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'primary_button_title',
			array(
				'label'       => __( 'Title', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Get Started', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'primary_button_link',
			array(
				'label'       => __( 'Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => 'https://yoursite.com/',
				'default'     => array(
					'url' => '#',
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'primary_button_icon',
			array(
				'label'       => __( 'Icon', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$repeater->add_control(
			'primary_button_css_id',
			array(
				'label'       => __( 'Button ID', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => '',
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'primary_button_animation',
			array(
				'label' => esc_html__( 'Animation', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'primary_button_animation_effect',
			array(
				'label'   => esc_html__( 'Effect', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => $this->get_animation_effects( array( 'x-split-top', 'x-split-right', 'x-split-bottom', 'x-split-left', 'x-split-expand' ) ),
			)
		);

		$repeater->add_control(
			'primary_button_animation_reveal_color',
			array(
				'label'     => __( 'Reveal Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-button-primary::after'   => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'primary_button_animation_effect' => array( 'x-reveal-top', 'x-reveal-bottom', 'x-reveal-left', 'x-reveal-right' ),
				),
			)
		);

		$repeater->add_control(
			'primary_button_animation_duration',
			array(
				'label'      => esc_html__( 'Duration(s)', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 1,
				),
			)
		);

		$repeater->add_control(
			'primary_button_animation_delay',
			array(
				'label'      => esc_html__( 'Delay(s)', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 0,
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		//Secondary Button
		$repeater->add_control(
			'enable_secondary_button',
			array(
				'label'     => esc_html__( 'Secondary Button', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$repeater->start_controls_tabs(
			'secondary_button_tab',
			array(
				'condition' => array(
					'enable_secondary_button' => 'yes',
				),
			)
		);

		$repeater->start_controls_tab(
			'secondary_button_content',
			array(
				'label' => esc_html__( 'Content', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'secondary_button_title',
			array(
				'label'       => __( 'Title', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Read More', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'secondary_button_link',
			array(
				'label'       => __( 'Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => 'https://yoursite.com/',
				'default'     => array(
					'url' => '#',
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'secondary_button_icon',
			array(
				'label'       => __( 'Icon', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$repeater->add_control(
			'secondary_button_css_id',
			array(
				'label'       => __( 'Button ID', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => '',
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'secondary_button_animation',
			array(
				'label' => esc_html__( 'Animation', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'secondary_button_animation_effect',
			array(
				'label'   => esc_html__( 'Effect', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => $this->get_animation_effects( array( 'x-split-top', 'x-split-right', 'x-split-bottom', 'x-split-left', 'x-split-expand' ) ),
			)
		);

		$repeater->add_control(
			'secondary_button_animation_reveal_color',
			array(
				'label'     => __( 'Reveal Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-button-primary::after'   => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'secondary_button_animation_effect' => array( 'x-reveal-top', 'x-reveal-bottom', 'x-reveal-left', 'x-reveal-right' ),
				),
			)
		);

		$repeater->add_control(
			'secondary_button_animation_duration',
			array(
				'label'      => esc_html__( 'Duration(s)', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 1,
				),
			)
		);

		$repeater->add_control(
			'secondary_button_animation_delay',
			array(
				'label'      => esc_html__( 'Delay(s)', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'size' => 0,
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		//Items
		$this->add_control(
			'slide_items',
			array(
				'label'      => esc_html__( 'Slides', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::REPEATER,
				'fields'     => $repeater->get_controls(),
				'show_label' => false,
				'default'    => array(
					array(
						'subtitle'         => __( 'Subtitle Caption #1', 'xpro-elementor-addons' ),
						'title'            => __( 'Title Caption #1', 'xpro-elementor-addons' ),
						'horizontal_align' => 'left',
					),
					array(
						'subtitle'         => __( 'Subtitle Caption #2', 'xpro-elementor-addons' ),
						'title'            => __( 'Title Caption #2', 'xpro-elementor-addons' ),
						'horizontal_align' => 'center',
					),
					array(
						'subtitle'         => __( 'Subtitle Caption #3', 'xpro-elementor-addons' ),
						'title'            => __( 'Title Caption #3', 'xpro-elementor-addons' ),
						'horizontal_align' => 'right',
					),
				),
			)
		);

		$this->end_controls_section();

		//Carousel Settings Tab
		$this->start_controls_section(
			'section_carousel',
			array(
				'label' => __( 'Settings', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'slide_animation',
			array(
				'label'       => __( 'Slide Animation', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'slide',
				'options'     => array(
					'fade'  => __( 'Fade', 'xpro-elementor-addons' ),
					'slide' => __( 'Slide', 'xpro-elementor-addons' ),
				),
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'slide_speed',
			array(
				'label'       => __( 'Slide Speed', 'xpro-elementor-addons' ),
				'description' => __( 'Duration of transition between slides in seconds(s).', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'size' => 3,
				),
				'range'       => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'render_type' => 'template',
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'        => __( 'Loop', 'xpro-elementor-addons' ),
				'description'  => __( 'Duplicate last and first items to get loop illusion.', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'mouse_drag',
			array(
				'label'        => __( 'Mouse Drag', 'xpro-elementor-addons' ),
				'description'  => __( 'To enable mouse drag.', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => __( 'Autoplay', 'xpro-elementor-addons' ),
				'description'  => __( 'To enable autoplay behaviour.', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'render_type'  => 'template',
			)
		);

		$this->add_control(
			'autoplay_timeout',
			array(
				'label'       => __( 'Autoplay Timeout', 'xpro-elementor-addons' ),
				'description' => __( 'Autoplay interval timeout in seconds(s).', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'size' => 3,
				),
				'range'       => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'render_type' => 'template',
				'condition'   => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'nav',
			array(
				'label'        => __( 'Show Nav', 'xpro-elementor-addons' ),
				'description'  => __( 'Show next/prev buttons.', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'        => __( 'Show Dots', 'xpro-elementor-addons' ),
				'description'  => __( 'Show dots navigation.', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();

		//Styling
		$this->start_controls_section(
			'section_general_styling',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'slider_height',
			array(
				'label'      => esc_html__( 'Slider Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 600,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-slide-content-wrapper' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'container_width',
			array(
				'label'          => esc_html__( 'Container Width', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'vw', 'custom' ),
				'range'          => array(
					'px' => array(
						'min'  => 0,
						'max'  => 2400,
						'step' => 5,
					),
				),
				'default'        => array(
					'size' => 1140,
					'unit' => 'px',
				),
				'tablet_default' => array(
					'size' => 1025,
					'unit' => 'px',
				),
				'mobile_default' => array(
					'size' => 768,
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .xpro-hero-slider-slide-content-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_width',
			array(
				'label'      => esc_html__( 'Content Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1360,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-slide-content-area' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => esc_html__( 'Content Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-slide-content-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Sub Title Style
		$this->start_controls_section(
			'section_style_subtitle',
			array(
				'label' => __( 'Subtitle', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtitle_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-hero-slider-subtitle',
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-subtitle' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'subtitle_shadow',
				'label'    => __( 'Text Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-hero-slider-subtitle',
			)
		);

		$this->add_responsive_control(
			'subtitle_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Title Style
		$this->start_controls_section(
			'section_style_title',
			array(
				'label' => __( 'Title', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-hero-slider-title',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'title_shadow',
				'label'    => __( 'Text Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-hero-slider-title',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Title Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Description Style
		$this->start_controls_section(
			'section_style_description',
			array(
				'label' => __( 'Description', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-hero-slider-description',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'description_shadow',
				'label'    => __( 'Text Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-hero-slider-description',
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Description Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-description' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'description_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Primary Button
		$this->start_controls_section(
			'section_primary_btn',
			array(
				'label' => __( 'Primary Button', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'primary_btn_width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw', '%' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 800,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-button-primary' => 'width: {{SIZE}}{{UNIT}}; max-width:100%;',
				),
			)
		);

		$this->add_control(
			'primary_btn_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 14,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-button-primary i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-hero-slider-button-primary svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				),
			)
		);

		$this->add_control(
			'primary_btn_space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 14,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-button-primary'   => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'section_primary_btn_typo',
				'selector' => '{{WRAPPER}} .xpro-hero-slider-button-primary',
			)
		);

		$this->start_controls_tabs(
			'primary_btn_normal_tabs'
		);

		$this->start_controls_tab(
			'primary_btn_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'primary_btn_normal_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-button-primary' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'primary_btn_normal_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-button-primary' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'primary_btn_hover_tab',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'primary_btn_hover_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-button-primary:hover,{{WRAPPER}} .xpro-hero-slider-button-primary:focus' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'primary_btn_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-button-primary:hover,{{WRAPPER}} .xpro-hero-slider-button-primary:focus' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'primary_btn_normal_bcolor',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-button-primary:hover,{{WRAPPER}} .xpro-hero-slider-button-primary:focus' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'primary_btn_border',
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-hero-slider-button-primary',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'primary_btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-button-primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'primary_btn_border_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-button-primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'primary_btn_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-button-primary' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Secondary Button
		$this->start_controls_section(
			'section_secondary_btn',
			array(
				'label' => __( 'Secondary Button', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'secondary_btn_width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vw', '%' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 800,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-button-secondary' => 'width: {{SIZE}}{{UNIT}}; max-width:100%;',
				),
			)
		);

		$this->add_control(
			'secondary_btn_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 14,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-button-secondary i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-hero-slider-button-secondary svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				),
			)
		);

		$this->add_control(
			'secondary_btn_space_between',
			array(
				'label'      => esc_html__( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 14,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-button-secondary'   => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'section_secondary_btn_typo',
				'selector' => '{{WRAPPER}} .xpro-hero-slider-button-secondary',
			)
		);

		$this->start_controls_tabs(
			'secondary_btn_normal_tabs'
		);

		$this->start_controls_tab(
			'secondary_btn_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'secondary_btn_normal_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-button-secondary' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'secondary_btn_normal_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-button-secondary' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'secondary_btn_hover_tab',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'secondary_btn_hover_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-button-secondary:hover,{{WRAPPER}} .xpro-hero-slider-button-secondary:focus' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'secondary_btn_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-button-secondary:hover,{{WRAPPER}} .xpro-hero-slider-button-secondary:focus' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'secondary_btn_normal_bcolor',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-hero-slider-button-secondary:hover,{{WRAPPER}} .xpro-hero-slider-button-secondary:focus' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'secondary_btn_border',
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-hero-slider-button-secondary',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'secondary_btn_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-button-secondary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'secondary_btn_border_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-button-secondary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'secondary_btn_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-hero-slider-button-secondary' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Nav
		$this->start_controls_section(
			'section_nav_style',
			array(
				'label'     => __( 'Nav', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'nav' => 'yes',
				),
			)
		);

		$this->add_control(
			'nav_layout',
			array(
				'label'   => __( 'Layout', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'style-1' => __( 'Style 1', 'xpro-elementor-addons' ),
					'style-2' => __( 'Style 2', 'xpro-elementor-addons' ),
					'style-3' => __( 'Style 3', 'xpro-elementor-addons' ),
					'style-4' => __( 'Style 4', 'xpro-elementor-addons' ),
					'style-5' => __( 'Style 5', 'xpro-elementor-addons' ),
					'style-6' => __( 'Style 6', 'xpro-elementor-addons' ),
					'style-7' => __( 'Style 7', 'xpro-elementor-addons' ),
				),
				'default' => 'style-1',
			)
		);

		$this->add_responsive_control(
			'nav_size',
			array(
				'label'      => __( 'Icon Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 25,
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-button-prev,{{WRAPPER}} .swiper-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'nav_bg_size',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-button-prev,{{WRAPPER}} .swiper-button-next' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'nav_horizontal_position',
			array(
				'label'       => __( 'Position', 'xpro-elementor-addons' ),
				'description' => __( 'Next/Prev buttons horziontal position.', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'default'     => array(
					'size' => - 25,
				),
				'range'       => array(
					'px' => array(
						'min' => - 100,
						'max' => 100,
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .swiper-button-prev,{{WRAPPER}} .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-button-prev,{{WRAPPER}} .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'nav_style_tabs'
		);

		$this->start_controls_tab(
			'nav_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'nav_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-button-prev,{{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_bg',
			array(
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-button-prev,{{WRAPPER}} .swiper-button-next' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'nav_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .swiper-button-prev,{{WRAPPER}} .swiper-button-next',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'nav_hover_tab_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'nav_hcolor',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-button-prev:hover,{{WRAPPER}} .swiper-button-next:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_hbg',
			array(
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-button-prev:hover,{{WRAPPER}} .swiper-button-next:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_hborder',
			array(
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-button-prev:hover,{{WRAPPER}} .swiper-button-next:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'nav_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-button-prev,{{WRAPPER}} .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Dots
		$this->start_controls_section(
			'section_dots_style',
			array(
				'label'     => __( 'Dots', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'dots_layout',
			array(
				'label'   => __( 'Layout', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'style-1' => __( 'Style 1', 'xpro-elementor-addons' ),
					'style-2' => __( 'Style 2', 'xpro-elementor-addons' ),
					'style-3' => __( 'Style 3', 'xpro-elementor-addons' ),
				),
				'default' => 'style-1',
			)
		);

		$this->add_control(
			'dots_bg_height',
			array(
				'label'      => __( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dots_bg_width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .swiper-dots-horizontal-style-2 .swiper-pagination .swiper-pagination-bullet-active' => 'width: calc({{SIZE}}{{UNIT}} * 2);',
				),
			)
		);

		$this->add_control(
			'dots_space_between',
			array(
				'label'      => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 3,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'dots_spacing',
			array(
				'label'      => __( 'Spacing', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 10,
				),
				'range'      => array(
					'px' => array(
						'min' => -50,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-slider-theme .swiper-pagination.swiper-pagination-horizontal ' => 'bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'dots_style_tabs'
		);

		$this->start_controls_tab(
			'dots_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'dots_bg',
			array(
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'dots_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'dots_active_tab_style',
			array(
				'label' => __( 'Active', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'dots_abg',
			array(
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dots_aborder',
			array(
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet-active' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'dots_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-pagination .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	 * @since 0.1.8
	 * @access protected
	 */
	protected function render() {

		include_once XPRO_ELEMENTOR_ADDONS_WIDGET . '/hero-slider/layout/frontend.php';
	}

}
