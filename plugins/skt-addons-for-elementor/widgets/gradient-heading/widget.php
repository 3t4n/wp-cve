<?php
/**
 * Gradient Heading widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Skt_Addons_Elementor\Elementor\Controls\Group_Control_Foreground;

defined( 'ABSPATH' ) || die();

class Gradient_Heading extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Gradient Heading', 'skt-addons-elementor' );
	}

	public function get_custom_help_url() {
		return '#';
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-drag';
	}

	public function get_keywords() {
		return [ 'gradient', 'advanced', 'heading', 'title', 'colorful' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {

		$this->start_controls_section(
			'_section_title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => 'SKT Gradient Heading',
				'placeholder' => __( 'Type Gradient Heading Text', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'separator' => 'before',
				'placeholder' => 'https://example.com',
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'title_tag',
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
					'{{WRAPPER}}' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {

		$this->start_controls_section(
			'_section_style_title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Foreground::get_type(),
			[
				'name' => 'title',
				'selector' => '{{WRAPPER}} .skt-gradient-heading',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title',
				'selector' => '{{WRAPPER}} .skt-gradient-heading',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
			],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'title',
				'label' => __( 'Text Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-gradient-heading',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label' => __( 'Blend Mode', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Normal', 'skt-addons-elementor' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'difference' => 'Difference',
					'exclusion' => 'Exclusion',
					'hue' => 'Hue',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-gradient-heading' => 'mix-blend-mode: {{VALUE}};',
				],
				'separator' => 'none',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'title', 'basic' );
		$this->add_render_attribute( 'title', 'class', 'skt-gradient-heading' );

		$title = skt_addons_elementor_kses_basic( $settings['title' ] );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'link', $settings['link'] );

			$title = sprintf( '<a %1$s>%2$s</a>',
				$this->get_render_attribute_string( 'link' ),
				$title
				);
		}

		printf( '<%1$s %2$s>%3$s</%1$s>',
			skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' ),
			$this->get_render_attribute_string( 'title' ),
			$title
		);
	}

	public function content_template() {
		?>
		<#
		view.addInlineEditingAttributes( 'title', 'basic' );
		view.addRenderAttribute( 'title', 'class', 'skt-gradient-heading' );

		var title = _.isEmpty(settings.link.url) ? settings.title : '<a href="'+settings.link.url+'">'+settings.title+'</a>';
		#>
		<{{ settings.title_tag }} {{{ view.getRenderAttributeString( 'title' ) }}}>{{{ title }}}</{{ settings.title_tag }}>
		<?php
	}
}