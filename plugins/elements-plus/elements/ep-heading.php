<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Schemes;

/**
 * Elements Plus heading widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.0.0
 */
class Widget_EP_Heading extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve heading widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'ep-heading';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve heading widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Heading Plus!', 'elements-plus' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve heading widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'ep-icon ep-icon-heading';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the heading widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'elements-plus' ];
	}

	/**
	 * Register heading widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Heading Plus!', 'elements-plus' ),
			]
		);

		$this->add_control(
			'heading_part_1',
			[
				'label'       => __( 'Heading part 1', 'elements-plus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Heading part 1', 'elements-plus' ),
			]
		);

		$this->add_control(
			'heading_part_2',
			[
				'label'       => __( 'Heading part 2', 'elements-plus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Heading part 2', 'elements-plus' ),
			]
		);

		$this->add_control(
			'heading_part_3',
			[
				'label'       => __( 'Heading part 3', 'elements-plus' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Heading part 3', 'elements-plus' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label'     => __( 'Link', 'elements-plus' ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => [
					'url' => '',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'header_size',
			[
				'label'   => __( 'HTML Tag', 'elements-plus' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'align',
			[
				'label'     => __( 'Alignment', 'elements-plus' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'elements-plus' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elements-plus' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'elements-plus' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view',
			[
				'label'   => __( 'View', 'elements-plus' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$heading_parts = array(
			[
				'name'  => 'heading_part_1',
				'label' => __( 'Heading Part 1', 'elements-plus' ),
				'class' => '.ep-heading-part-1',
			],
			[
				'name'  => 'heading_part_2',
				'label' => __( 'Heading Part 2', 'elements-plus' ),
				'class' => '.ep-heading-part-2',
			],
			[
				'name'  => 'heading_part_3',
				'label' => __( 'Heading Part 3', 'elements-plus' ),
				'class' => '.ep-heading-part-3',
			],
		);

		foreach ( $heading_parts as $heading_part ) {
			$this->start_controls_section(
				'section_' . $heading_part['name'],
				[
					'label' => $heading_part['label'],
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'title_' . $heading_part['name'],
				[
					'label'     => __( 'Text Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000',
					'scheme'    => [
						'type'  => Schemes\Color::get_type(),
						'value' => Schemes\Color::COLOR_1,
					],
					'selectors' => [
						// Stronger selector to avoid section style from overwriting
						'{{WRAPPER}} ' . $heading_part['class'] => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'title_bg_' . $heading_part['name'],
				[
					'label'     => __( 'Background Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => 'transparent',
					'scheme'    => [
						'type'  => Schemes\Color::get_type(),
						'value' => Schemes\Color::COLOR_1,
					],
					'selectors' => [
						// Stronger selector to avoid section style from overwriting
						'{{WRAPPER}} ' . $heading_part['class'] => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'typography_' . $heading_part['name'],
					'scheme'   => Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} ' . $heading_part['class'],
				]
			);

			$this->add_group_control(
				Group_Control_Text_Shadow::get_type(),
				[
					'name'     => 'text_shadow_' . $heading_part['name'],
					'selector' => '{{WRAPPER}} ' . $heading_part['class'],
				]
			);

			$this->add_control(
				'blend_mode_' . $heading_part['name'],
				[
					'label'     => __( 'Blend Mode', 'elements-plus' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => [
						''            => __( 'Normal', 'elements-plus' ),
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
					],
					'selectors' => [
						'{{WRAPPER}} ' . $heading_part['class'] => 'mix-blend-mode: {{VALUE}}',
					],
					'separator' => 'none',
				]
			);

			$this->add_control(
				'padding_' . $heading_part['name'],
				[
					'label'      => __( 'Padding', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} ' . $heading_part['class'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator'  => 'before',
				]
			);

			$this->add_control(
				'margin_' . $heading_part['name'],
				[
					'label'      => __( 'Margin', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} ' . $heading_part['class'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_section();
		}
	}

	/**
	 * Render heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! $settings['heading_part_1'] && ! $settings['heading_part_2'] && ! $settings['heading_part_3'] ) {
			return;
		}

		$title = '<span class="ep-heading-part-1">' . $settings['heading_part_1'] . '</span>
		<span class="ep-heading-part-2">' . $settings['heading_part_2'] . '</span>
		<span class="ep-heading-part-3">' . $settings['heading_part_3'] . '</span>';

		$this->add_render_attribute( 'title', 'class', 'elementor-heading-title' );

		if ( ! empty( $settings['size'] ) ) {
			$this->add_render_attribute( 'title', 'class', 'elementor-size-' . $settings['size'] );
		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'url', 'href', $settings['link']['url'] );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'url', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'url', 'rel', 'nofollow' );
			}

			$title = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
		}

		$title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['header_size'], $this->get_render_attribute_string( 'title' ), $title );

		echo $title_html;
	}
}

add_action(
	'elementor/widgets/register',
	function ( $widgets_manager ) {
		$widgets_manager->register( new Widget_EP_Heading() );
	}
);

