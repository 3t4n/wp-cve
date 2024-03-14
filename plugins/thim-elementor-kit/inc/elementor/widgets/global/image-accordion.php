<?php

namespace Elementor;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Widget_Base;
use \Elementor\Repeater;


class Thim_Ekit_Widget_Image_Accordion extends Widget_Base {
	public function get_name() {
		return 'thim-ekits-image-accordion';
	}

	public function get_title() {
		return esc_html__( 'Image Accordion', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-image-rollover';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_base() {
		return basename( __FILE__, '.php' );
	}

	public function get_keywords() {
		return [
			'thim',
			'image accordion',
			'accordion',
			'image',
		];
	}

	protected function register_controls() {
		/**
		 * Image accordion Content Settings
		 */
		$this->start_controls_section(
			'general',
			[
				'label' => esc_html__( 'General', 'thim-elementor-kit' ),
			]
		);

		$this->add_control(
			'content_horizontal_align',
			[
				'label'     => __( 'Horizontal Alignment', 'thim-elementor-kit' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-image-accordion__item .overlay' => 'justify-content: {{VALUE}}; text-align: {{VALUE}}',
				),
			]
		);

		$this->add_control(
			'content_vertical_align',
			[
				'label'     => __( 'Vertical Alignment', 'thim-elementor-kit' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => array(
						'title' => esc_html__( 'Top', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => [
						'title' => __( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-middle',
					],

					'flex-end' => array(
						'title' => esc_html__( 'Bottom', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-bottom',
					),

				],
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-image-accordion__item .overlay' => 'align-items: {{VALUE}};',
				),
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => __( 'Select Title Tag', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1'   => __( 'H1', 'thim-elementor-kit' ),
					'h2'   => __( 'H2', 'thim-elementor-kit' ),
					'h3'   => __( 'H3', 'thim-elementor-kit' ),
					'h4'   => __( 'H4', 'thim-elementor-kit' ),
					'h5'   => __( 'H5', 'thim-elementor-kit' ),
					'h6'   => __( 'H6', 'thim-elementor-kit' ),
					'span' => __( 'Span', 'thim-elementor-kit' ),
					'p'    => __( 'P', 'thim-elementor-kit' ),
					'div'  => __( 'Div', 'thim-elementor-kit' ),
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'is_active',
			[
				'label'        => __( 'Make it active?', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'thim-elementor-kit' ),
				'label_off'    => __( 'No', 'thim-elementor-kit' ),
				'return_value' => 'yes',
			]
		);

		$repeater->add_control(
			'background_img',
			[
				'label'       => esc_html__( 'Background Image', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::MEDIA,
				'label_block' => true,
				'default'     => [
					'url' => Utils::get_placeholder_image_src(),
					'id'  => - 1
				],
			]
		);

		$repeater->add_control(
			'tittle',
			[
				'label'       => esc_html__( 'Title', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'Accordion item title', 'thim-elementor-kit' ),
				'dynamic'     => [ 'active' => true ],
			]
		);

		$repeater->add_control(
			'desc',
			[
				'label'       => esc_html__( 'Content', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default'     => esc_html__( 'Accordion content goes here!', 'thim-elementor-kit' ),
			]
		);

		$repeater->add_control(
			'show_link',
			[
				'label'   => esc_html__( 'Enable Link', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''          => __( 'None', 'thim-elementor-kit' ),
					'box'       => __( 'Content', 'thim-elementor-kit' ),
					'read_more' => __( 'Button Read More', 'thim-elementor-kit' ),
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'         => esc_html__( 'Link', 'thim-elementor-kit' ),
				'type'          => Controls_Manager::URL,
				'dynamic'       => [ 'active' => true ],
				'label_block'   => true,
				'default'       => [
					'url'         => '#',
					'is_external' => '',
				],
				'show_external' => true,
				'condition'     => [
					'show_link!' => '',
				],
			]
		);
		$repeater->add_control(
			'label_link',
			[
				'label'       => esc_html__( 'Button Label', 'elementskit-lite' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'label_block' => true,
				'default'     => esc_html__( 'Read More', 'elementskit-lite' ),
				'condition'   => [
					'show_link' => 'read_more',
				],
			]
		);
		$this->add_control(
			'img_accordions',
			[
				'type'        => Controls_Manager::REPEATER,
				'seperator'   => 'before',
				'default'     => [
					[
						'tittle'         => esc_html__( 'Image Accordion #1', 'thim-elementor-kit' ),
						'desc'           => esc_html__( 'Image Accordion content goes here!', 'thim-elementor-kit' ),
						'background_img' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'tittle'         => esc_html__( 'Image Accordion #2', 'thim-elementor-kit' ),
						'desc'           => esc_html__( 'Image Accordion content goes here!', 'thim-elementor-kit' ),
						'background_img' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'tittle'         => esc_html__( 'Image Accordion #3', 'thim-elementor-kit' ),
						'desc'           => esc_html__( 'Image Accordion content goes here!', 'thim-elementor-kit' ),
						'background_img' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'tittle'         => esc_html__( 'Image Accordion #4', 'thim-elementor-kit' ),
						'desc'           => esc_html__( 'Image Accordion content goes here!', 'thim-elementor-kit' ),
						'background_img' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
				],
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{tittle}}',
			]
		);

		$this->end_controls_section();

		$this->register_controls_thumbnail_style();

		$this->register_controls_content_style();
		$this->register_controls_read_more();
	}

	protected function register_controls_thumbnail_style() {

		$this->start_controls_section(
			'thumbnail_settings',
			[
				'label' => esc_html__( 'Thumbnail', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'accordion_height',
			[
				'label'     => esc_html__( 'Height (Unit: px)', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '350',
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-image-accordion' => 'min-height: {{VALUE}}px;',
				],
			]
		);

		$this->add_control(
			'thumbnail_margin',
			[
				'label'      => __( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'thumbnail_radius',
			[
				'label'      => __( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important;',
				],
			]
		);
		$this->add_control(
			'img_overlay_color',
			[
				'label'     => esc_html__( 'Overlay Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, .3)',
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'img_hover_color',
			[
				'label'     => esc_html__( 'Hover Overlay Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, .5)',
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item:hover::before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'thumbnail_border',
				'label'    => __( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .thim-ekits-image-accordion__item',
			]
		);


		$this->end_controls_section();
	}

	protected function register_controls_content_style() {

		$this->start_controls_section(
			'typography_settings',
			[
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'thumbnail_padding',
			[
				'label'      => __( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'title_text',
			[
				'label'     => esc_html__( 'Title', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_space',
			[
				'label'      => esc_html__( 'Margin Bottom', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-image-accordion__item .title',
			]
		);

		$this->add_control(
			'content_text',
			[
				'label'     => esc_html__( 'Content', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-image-accordion__item .desc',
			]
		);
		$this->add_responsive_control(
			'content_space',
			[
				'label'      => esc_html__( 'Margin Bottom', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range'      => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function register_controls_read_more() {
		$this->start_controls_section(
			'read_more_style',
			[
				'label' => esc_html__( 'Read More', 'eduma' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'more_padding',
			[
				'label'      => esc_html__( 'Padding', 'eduma' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .button-read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'more_typography',
				'label'    => esc_html__( 'Typography', 'eduma' ),
				'selector' => '{{WRAPPER}} .thim-ekits-image-accordion__item .button-read-more',
			]
		);

		$this->add_responsive_control(
			'btn_border_style',
			[
				'label'     => esc_html_x( 'Border Type', 'Border Control', 'eduma' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'none'   => esc_html__( 'None', 'eduma' ),
					'solid'  => esc_html_x( 'Solid', 'Border Control', 'eduma' ),
					'double' => esc_html_x( 'Double', 'Border Control', 'eduma' ),
					'dotted' => esc_html_x( 'Dotted', 'Border Control', 'eduma' ),
					'dashed' => esc_html_x( 'Dashed', 'Border Control', 'eduma' ),
					'groove' => esc_html_x( 'Groove', 'Border Control', 'eduma' ),
				],
				'default'   => 'none',
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .button-read-more' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_dimensions',
			[
				'label'     => esc_html_x( 'Width', 'Border Control', 'eduma' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'condition' => [
					'btn_border_style!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .button-read-more' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style' );
		$this->start_controls_tab(
			'tab_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'eduma' ),
			]
		);
		$this->add_control(
			'btn_text_color',
			[
				'label'     => __( 'Text Color', 'eduma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .button-read-more' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label'     => __( 'Border Color', 'eduma' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'btn_border_style!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .button-read-more' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'label'     => __( 'Background Color', 'eduma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .button-read-more' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'eduma' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .button-read-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'eduma' ),
			]
		);
		$this->add_control(
			'btn_text_color_hover',
			[
				'label'     => __( 'Text Color', 'eduma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .button-read-more:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_border_color_hover',
			[
				'label'     => __( 'Border Color', 'eduma' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'btn_border_style!' => 'none'
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .button-read-more:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'btn_bg_color_hover',
			[
				'label'     => __( 'Background Color', 'eduma' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .thim-ekits-image-accordion__item .button-read-more:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['img_accordions'] ) ) {
			return;
		}
		?>
		<div class="thim-ekits-image-accordion">
			<?php foreach ( $settings['img_accordions'] as $key => $img_accordion ): ?>
				<?php
				$this->add_render_attribute(
					'image-accordion-item-' . $key,
					[
						'class' => 'thim-ekits-image-accordion__item',
						'style' => "background-image: url(" . esc_url( $img_accordion['background_img']['url'] ) . ")",
					]
				);
				if ( $img_accordion['is_active'] === 'yes' ) {
					$this->add_render_attribute( 'image-accordion-item-' . $key, 'class', 'overlay-active' );
				}
				?>
				<div <?php echo $this->get_render_attribute_string( 'image-accordion-item-' . $key ) ?>>
					<div class="overlay">
						<div class="overlay-inner">
							<?php
							if ( $img_accordion['tittle'] ) {
								printf( '<%1$s class="title">%2$s</%1$s>', $settings['title_tag'], $img_accordion['tittle'] );
							}
							if ( $img_accordion['desc'] ) {
								printf( '<div class="desc">%1$s</div>', $img_accordion['desc'] );
							}

							if ( $img_accordion['show_link'] != '' ) {
								$label_link = '';
								if ( ! empty( $img_accordion['link']['url'] ) ) {
									$this->add_link_attributes( 'link-' . $key, $img_accordion['link'] );
								}

								if ( $img_accordion['show_link'] == 'read_more' ) {
									$this->add_render_attribute( 'link-' . $key, 'class', 'button-read-more' );
									$label_link = $img_accordion['label_link'];
								} else {
									$this->add_render_attribute( 'link-' . $key, 'class', 'read-more' );
								}
								?>
								<a <?php Utils::print_unescaped_internal_string( $this->get_render_attribute_string( 'link-' . esc_attr( $key ) ) ); ?>><?php echo esc_attr( $label_link ); ?></a>
							<?php } ?>
						</div>

					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
