<?php
/**
 * Site Title widget class
 *
 * @package Skt_Addons
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

defined( 'ABSPATH' ) || die();

class Site_Title extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Site Title', 'skt-addons-elementor' );
	}

	public function get_custom_help_url() {
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-tb-site-title';
	}

	public function get_keywords() {
		return [ 'site title', 'Title', 'text' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__site_title_content_control();
	}

	protected function __site_title_content_control(){
		$this->start_controls_section(
			'_section_site_title',
			[
				'label' => __( 'Site Title', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'site_title_tag',
			[
				'label' => __( 'Title HTML Tag', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'h1'  => [
						'title' => __( 'H1', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h1'
					],
					'h2'  => [
						'title' => __( 'H2', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h2'
					],
					'h3'  => [
						'title' => __( 'H3', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h3'
					],
					'h4'  => [
						'title' => __( 'H4', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h4'
					],
					'h5'  => [
						'title' => __( 'H5', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h5'
					],
					'h6'  => [
						'title' => __( 'H6', 'skt-addons-elementor' ),
						'icon' => 'eicon-editor-h6'
					]
				],
				'default' => 'h2',
				'toggle' => false,
			]
		);
        $this->add_control(
			'size',
			[
				'label' => esc_html__( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'skt-addons-elementor' ),
					'small' => esc_html__( 'Small', 'skt-addons-elementor' ),
					'medium' => esc_html__( 'Medium', 'skt-addons-elementor' ),
					'large' => esc_html__( 'Large', 'skt-addons-elementor' ),
					'xl' => esc_html__( 'XL', 'skt-addons-elementor' ),
					'xxl' => esc_html__( 'XXL', 'skt-addons-elementor' ),
				],
				'default' => 'default',
			]
		);

        $this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};'
				]
			]
		);

        $this->end_controls_section();
	}

	/**
	 * Register styles related controls
	 */
	protected function register_style_controls() {
		$this->__page_title_style_controls();
	}


	protected function __page_title_style_controls() {

        $this->start_controls_section(
            '_section_style_site_title',
            [
                'label' => __( 'Site Title', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'st_color',
			[
				'label' => esc_html__( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-site-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'site_title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-site-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'site_text_shadow',
				'selector' => '{{WRAPPER}} .skt-site-title',
			]
		);


        $this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute('title', 'class', 'skt-site-title');

        if ( ! empty( $settings['size'] ) ) {
            $this->add_render_attribute('title', 'class', 'skt-site-title-' . $settings['size']);
        }

		echo '<a href="' . esc_url(home_url('/')) . '">';
        printf('<%1$s %2$s>%3$s</%1$s>', $settings['site_title_tag'], $this->get_render_attribute_string('title'), get_bloginfo('name') );
		echo '</a>';
	}
}
