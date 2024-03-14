<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Css_Filter;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Lottie extends Widget_Base {


	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

		wp_register_script(
			'xpro-lottie-init',
			XPRO_ELEMENTOR_ADDONS_DIR_URL . 'widgets/lottie/js/lottie.init.js',
			array(
				'lottie',
				'elementor-frontend',
			),
			XPRO_ELEMENTOR_ADDONS_VERSION,
			true
		);
	}

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
		return 'xpro-lottie';
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
		return __( 'Lottie', 'xpro-elementor-addons' );
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
		return 'xi-lottie xpro-widget-label';
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
		return array( 'lottie', 'animated', 'icon' );
	}

	public function wp_init() {
		include XPRO_ELEMENTOR_ADDONS_WIDGET . 'lottie/json-handler.php';
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_script_depends() {
		return array( 'lottie', 'xpro-lottie-init' );
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
			'xpro_lottie_section',
			array(
				'label' => esc_html__( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'xpro_lottie_type',
			array(
				'label'   => __( 'Source', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'file',
				'options' => array(
					'file' => __( 'Media File', 'xpro-elementor-addons' ),
					'url'  => __( 'External URL', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'xpro_lottie_json',
			array(
				'label'      => __( 'Upload JSON File', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => 'application/json',
				'condition'  => array(
					'xpro_lottie_type' => 'file',
				),
			)
		);

		$this->add_control(
			'xpro_lottie_url',
			array(
				'label'         => __( 'External URL', 'xpro-elementor-addons' ),
				'label_block'   => true,
				'type'          => Controls_Manager::TEXT,
				'placeholder'   => esc_html__( 'https://example.com/file.json', 'xpro-elementor-addons' ),
				'show_external' => false,
				'condition'     => array(
					'xpro_lottie_type' => 'url',
				),
			)
		);

		$this->add_control(
			'xpro_lottie_link_check',
			array(
				'label' => esc_html__( 'Link', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'xpro_lottie_link',
			array(
				'show_label' => false,
				'type'       => Controls_Manager::URL,
				'dynamic'    => array(
					'active' => true,
				),
				'condition'  => array(
					'xpro_lottie_link_check' => 'yes',
				),
			)
		);

		$this->add_control(
			'xpro_lottie_options',
			array(
				'label'     => esc_html__( 'Animation Options', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'xpro_lottie_reverse',
			array(
				'label' => esc_html__( 'Reverse', 'xpro-elementor-addons' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$this->add_control(
			'xpro_lottie_autoplay',
			array(
				'label'        => esc_html__( 'Autoplay', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'xpro_lottie_on_scroll',
			array(
				'label'     => esc_html__( 'Start when visible', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'xpro_lottie_autoplay' => '',
				),
			)
		);

		$this->add_control(
			'xpro_lottie_loop',
			array(
				'label'        => esc_html__( 'Loop', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'xpro_lottie_loop_count',
			array(
				'label'     => esc_html__( 'Loop Count', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 10,
					),
				),
				'condition' => array(
					'xpro_lottie_loop' => 'true',
				),
			)
		);

		$this->add_control(
			'xpro_lottie_speed',
			array(
				'label'   => esc_html__( 'Speed', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'px' => array(
						'max'  => 10,
						'step' => 0.2,
					),
				),
				'default' => array(
					'size' => 1,
				),
			)
		);

		$this->add_control(
			'xpro_lottie_renderer',
			array(
				'label'   => esc_html__( 'Render Type', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'svg',
				'options' => array(
					'svg'    => array(
						'title' => esc_html__( 'SVG', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-circle',
					),
					'canvas' => array(
						'title' => esc_html__( 'Canvas', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-square',
					),
				),
			)
		);

		$this->add_control(
			'xpro_lottie_action',
			array(
				'label'   => esc_html__( 'On Hover', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					''        => esc_html__( 'None', 'xpro-elementor-addons' ),
					'play'    => esc_html__( 'Play', 'xpro-elementor-addons' ),
					'pause'   => esc_html__( 'Pause', 'xpro-elementor-addons' ),
					'reverse' => esc_html__( 'Reverse', 'xpro-elementor-addons' ),
				),
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'xpro_lottie_styles',
			array(
				'label' => esc_html__( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
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
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'          => __( 'Width', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( '%', 'px', 'vw' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .xpro_lottie' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'space',
			array(
				'label'          => __( 'Max Width', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => '%',
				),
				'tablet_default' => array(
					'unit' => '%',
				),
				'mobile_default' => array(
					'unit' => '%',
				),
				'size_units'     => array( '%', 'px', 'vw' ),
				'range'          => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
					'vw' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .xpro_lottie' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'xpro_lottie_state'
		);

		$this->start_controls_tab(
			'xpro_lottie_normal',
			array(
				'label' => esc_html__( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'xpro_lottie_opacity',
			array(
				'label'     => esc_html__( 'Opacity', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'xpro_lottie_filter',
				'selector' => '{{WRAPPER}}',
			)
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'xpro_lottie_hover',
			array(
				'label' => esc_html__( 'Hover', 'xpro-elementor-addons' ),
			)
		);
		$this->add_control(
			'xpro_lottie_opacity_hover',
			array(
				'label'     => esc_html__( 'Opacity', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}:hover' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'xpro_lottie_filter_hover',
				'selector' => '{{WRAPPER}}',
			)
		);

		$this->add_control(
			'xpro_lottie_transition',
			array(
				'label'     => esc_html__( 'Transition', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 10,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'transition: all {{SIZE}}s ease;',
				),
			)
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

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

		$this->add_render_attribute(
			'wrapper',
			array(
				'id'             => 'xpro_lottie_' . $this->get_id(),
				'class'          => 'xpro_lottie',
				'data-autoplay'  => $settings['xpro_lottie_autoplay'],
				'data-on-scroll' => $settings['xpro_lottie_on_scroll'],
				'data-speed'     => $settings['xpro_lottie_speed']['size'],
				'data-direction' => $settings['xpro_lottie_reverse'],
				'data-action'    => $settings['xpro_lottie_action'],
				'data-renderer'  => $settings['xpro_lottie_renderer'],
			)
		);

		if ( ! empty( $settings['xpro_lottie_json']['url'] ) ) :
			$this->add_render_attribute( 'wrapper', 'data-path', $settings['xpro_lottie_json']['url'] );
		else :
			$this->add_render_attribute( 'wrapper', 'data-path', $settings['xpro_lottie_url'] );
		endif;

		if ( $settings['xpro_lottie_loop_count']['size'] ) :
			$this->add_render_attribute( 'wrapper', 'data-loop', ( $settings['xpro_lottie_loop_count']['size'] - 1 ) );
		else :
			$this->add_render_attribute( 'wrapper', 'data-loop', $settings['xpro_lottie_loop'] );
		endif;

		if ( ! empty( $settings['xpro_lottie_link']['url'] ) && $settings['xpro_lottie_link']['url'] ) :
			$this->add_render_attribute( 'wrapper', 'class', 'met_d--block' );
			$this->add_link_attributes( 'link', $settings['xpro_lottie_link'] );

			echo '<a ' . $this->get_render_attribute_string( 'link' ) . ' ' . $this->get_render_attribute_string( 'wrapper' ) . '>&nbsp;</a>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		else :
			echo '<div ' . $this->get_render_attribute_string( 'wrapper' ) . '>&nbsp;</div>'; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		endif;
	}
}
