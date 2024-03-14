<?php
/**
 * Archive Title widget class
 *
 * @package Skt_Addons
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

defined( 'ABSPATH' ) || die();

class Archive_Title extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Archive Title', 'skt-addons-elementor' );
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
		return 'skti skti-tb-archieve-title';
	}

	public function get_keywords() {
		return [ 'archive title', 'Title', 'text' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__archive_title_controls();
	}

	protected function __archive_title_controls(){
		$this->start_controls_section(
			'_section_archive_title',
			[
				'label' => __( 'Archive Title', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'archive_title_tag',
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
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'skt-addons-elementor' ),
					'small' => esc_html__( 'Small', 'skt-addons-elementor' ),
					'medium' => esc_html__( 'Medium', 'skt-addons-elementor' ),
					'large' => esc_html__( 'Large', 'skt-addons-elementor' ),
					'xl' => esc_html__( 'XL', 'skt-addons-elementor' ),
					'xxl' => esc_html__( 'XXL', 'skt-addons-elementor' ),
				],
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
		$this->__archive_title_style_controls();
	}


	protected function __archive_title_style_controls() {

        $this->start_controls_section(
            '_section_style_archive',
            [
                'label' => __( 'Text', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
			'archive_color',
			[
				'label' => esc_html__( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-archive-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'archive_title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-archive-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'archive_text_shadow',
				'selector' => '{{WRAPPER}} .skt-archive-title',
			]
		);


        $this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute('title', 'class', 'skt-archive-title');

        if ( ! empty( $settings['size'] ) ) {
            $this->add_render_attribute('title', 'class', 'elementor-size-' . $settings['size']);
        }

        printf('<%1$s %2$s>%3$s</%1$s>', $settings['archive_title_tag'], $this->get_render_attribute_string('title'), get_the_archive_title() );
	}
}
