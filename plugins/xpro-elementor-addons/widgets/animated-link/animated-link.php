<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
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
class Animated_Link extends Widget_Base {

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
		return 'xpro-animated-link';
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
		return __( 'Animated Link', 'xpro-elementor-addons' );
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
		return 'xi-link xpro-widget-label';
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
		return array( 'animated', 'link', 'cta' );
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
			'section_general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'layouts',
			array(
				'label'   => esc_html__( 'Layouts', 'xpro-elementor-addons' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => array(
					'style-1'  => esc_html__( 'Style 1', 'xpro-elementor-addons' ),
					'style-2'  => esc_html__( 'Style 2', 'xpro-elementor-addons' ),
					'style-3'  => esc_html__( 'Style 3', 'xpro-elementor-addons' ),
					'style-4'  => esc_html__( 'Style 4', 'xpro-elementor-addons' ),
					'style-5'  => esc_html__( 'Style 5', 'xpro-elementor-addons' ),
					'style-6'  => esc_html__( 'Style 6', 'xpro-elementor-addons' ),
					'style-7'  => esc_html__( 'Style 7', 'xpro-elementor-addons' ),
					'style-8'  => esc_html__( 'Style 8', 'xpro-elementor-addons' ),
					'style-9'  => esc_html__( 'Style 9', 'xpro-elementor-addons' ),
					'style-10' => esc_html__( 'Style 10', 'xpro-elementor-addons' ),
					'style-11' => esc_html__( 'Style 11', 'xpro-elementor-addons' ),
					'style-12' => esc_html__( 'Style 12', 'xpro-elementor-addons' ),
					'style-13' => esc_html__( 'Style 13', 'xpro-elementor-addons' ),
					'style-14' => esc_html__( 'Style 14', 'xpro-elementor-addons' ),
					'style-15' => esc_html__( 'Style 15', 'xpro-elementor-addons' ),
					'style-16' => esc_html__( 'Style 16', 'xpro-elementor-addons' ),
					'style-17' => esc_html__( 'Style 17', 'xpro-elementor-addons' ),
					'style-18' => esc_html__( 'Style 18', 'xpro-elementor-addons' ),
					'style-19' => esc_html__( 'Style 19', 'xpro-elementor-addons' ),
					'style-20' => esc_html__( 'Style 20', 'xpro-elementor-addons' ),
					'style-21' => esc_html__( 'Style 21', 'xpro-elementor-addons' ),
					'style-22' => esc_html__( 'Style 22', 'xpro-elementor-addons' ),
					'style-23' => esc_html__( 'Style 23', 'xpro-elementor-addons' ),
					'style-24' => esc_html__( 'Style 24', 'xpro-elementor-addons' ),
					'style-25' => esc_html__( 'Style 25', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'       => __( 'Text', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => __( 'Click here', 'xpro-elementor-addons' ),
				'placeholder' => __( 'Click here', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => __( 'https://your-link.com', 'xpro-elementor-addons' ),
				'default'     => array(
					'url' => '#',
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'        => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'    => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
					'justify' => array(
						'title' => __( 'Justified', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'prefix_class' => 'elementor%s-align-',
				'default'      => 'center',
			)
		);

		$this->add_control(
			'link_css_id',
			array(
				'label'       => __( 'Link ID', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => '',
				'title'       => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'xpro-elementor-addons' ),
				'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'xpro-elementor-addons' ),
				'separator'   => 'before',

			)
		);

		$this->add_control(
			'onclick_event',
			array(
				'label'       => esc_html__( 'onClick Event', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => 'myFunction()',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .xpro-animated-link',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .animated-link-text',
			)
		);

		$this->start_controls_tabs( 'tabs_link_style' );

		$this->start_controls_tab(
			'tab_link_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'link_text_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-animated-link' => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-animated-link-style-25' => 'background-image: linear-gradient(to right, #54b3d6, #54b3d6 50%, {{VALUE}} 50%);',
				),
			)
		);

		$this->add_control(
			'line_color',
			array(
				'label'     => __( 'Line Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-animated-link-style-23 .xpro-animated-link-graphic' => 'stroke: {{VALUE}};',
					'{{WRAPPER}} .xpro-animated-link-style-18::before' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'layouts' => array( 'style-18', 'style-23' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_link_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'hover_color',
			array(
				'label'     => __( 'Text Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-animated-link:hover,{{WRAPPER}} .xpro-animated-link-style-10:hover span,{{WRAPPER}} .xpro-animated-link-style-12::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-animated-link-style-25' => 'background-image: linear-gradient(to right, {{VALUE}}, {{VALUE}} 50%, {{link_text_color.VALUE}} 50%);',
				),
			)
		);
		$this->add_control(
			'line_hover_color',
			array(
				'label'     => __( 'Line Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-animated-link:before,{{WRAPPER}} .xpro-animated-link::after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .xpro-animated-link-style-20::after' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .xpro-animated-link-style-21 .xpro-animated-link-graphic,{{WRAPPER}} .xpro-animated-link-style-22 .xpro-animated-link-graphic,
					{{WRAPPER}} .xpro-animated-link-style-23:hover .xpro-animated-link-graphic' => 'stroke: {{VALUE}};',
				),
				'condition' => array(
					'layouts!' => array( 'style-10', 'style-14', 'style-15', 'style-17', 'style-18' ),
				),
			)
		);

		$this->add_control(
			'dots_hover_color',
			array(
				'label'     => __( 'Dots Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-animated-link-style-17:hover::before,{{WRAPPER}} .xpro-animated-link-style-17:focus::before' => 'color: {{VALUE}}; text-shadow: 10px 0 {{VALUE}}, -10px 0 {{VALUE}}',
				),
				'condition' => array(
					'layouts' => array( 'style-17' ),
				),
			)
		);

		$this->end_controls_tab();

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

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'animated-link/layout/frontend.php';
	}
}
