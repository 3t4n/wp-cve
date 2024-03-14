<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Absp_Slider_Controller;
use AbsoluteAddons\Absp_Widget;
use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Image_Carousel extends Absp_Widget {

	use Absp_Slider_Controller;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'absolute-image-carousel';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Image Carousel', 'absolute-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'absp eicon-carousel';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'absolute-addons-custom',
			'absp-image-carousel',
			'absp-pro-image-carousel',
		];
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'absp-widgets' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function register_controls() {

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Image_Carousel $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_controls_section( 'tempalate_layout', [ 'label' => esc_html__( 'Template Style', 'absolute-addons' ) ] );

		$styles = apply_filters( 'absp/widgets/image-carousel/styles', [
			'one'      => esc_html__( 'One', 'absolute-addons' ),
			'two-pro'  => esc_html__( 'Two (Pro)', 'absolute-addons' ),
			'three'    => esc_html__( 'Three', 'absolute-addons' ),
			'four-pro' => esc_html__( 'Four (Pro)', 'absolute-addons' ),
			'five'     => esc_html__( 'Five', 'absolute-addons' ),
			'six-pro'  => esc_html__( 'Six (Pro)', 'absolute-addons' ),
		] );

		$pro_styles = [
			'two-pro',
			'four-pro',
			'six-pro',
		];

		$this->add_control(
			'absolute_image_carousel',
			[
				'label'   => esc_html__( 'Image Carousel Style', 'absolute-addons' ),
				'type'    => Absp_Control_Styles::TYPE,
				'options' => $styles,
				'default' => 'one',
			]
		);
		$this->init_pro_alert( $pro_styles );
		$this->end_controls_section();

		$this->start_controls_section( 'section_content', [ 'label' => esc_html__( 'Content', 'absolute-addons' ) ] );
		$this->add_control(
			'img_carousel_gallery',
			[
				'label'   => esc_html__( 'Add SliderImages', 'absolute-addons' ),
				'type'    => Controls_Manager::GALLERY,
				'default' => [],
			]
		);
		$this->add_control(
			'img_carousel_img_hover_animation',
			[
				'label'        => esc_html__( 'Image Hover Animation', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'enable'       => esc_html__( 'Yes', 'absolute-addons' ),
				'disable'      => esc_html__( 'No', 'absolute-addons' ),
				'return_value' => 'enable',
				'default'      => 'enable',
			]
		);
		$this->end_controls_section();
		$this->render_controller( 'style-controller-image-carousel-item-settings' );
		$this->render_controller( 'style-controller-image-carousel-item-images' );
		$this->render_controller( 'style-controller-image-carousel-item-image-overlay' );

		$this->render_slider_controller( [
			'arrows_color'             => [
				'selectors' => [
					'{{WRAPPER}} .absp-image-carousel-item.element-one .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .absp-image-carousel-item.element-one .elementor-swiper-button.elementor-swiper-button-next'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-two .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .absp-image-carousel-item.element-two .elementor-swiper-button.elementor-swiper-button-next'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-three .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .absp-image-carousel-item.element-three .elementor-swiper-button.elementor-swiper-button-next' => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-four .elementor-swiper-button.elementor-swiper-button-prev::after, {{WRAPPER}} .absp-image-carousel-item.element-four .elementor-swiper-button.elementor-swiper-button-next::after'  => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-five .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .absp-image-carousel-item.element-five .elementor-swiper-button.elementor-swiper-button-next' => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-six .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .absp-image-carousel-item.element-six .elementor-swiper-button.elementor-swiper-button-next' => 'color: {{VALUE}};',
				],
			],
			'arrows_color_hover'       => [
				'selectors' => [
					'{{WRAPPER}} .absp-image-carousel-item.element-one .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .absp-image-carousel-item.element-one .elementor-swiper-button.elementor-swiper-button-next:hover'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-two .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .absp-image-carousel-item.element-two .elementor-swiper-button.elementor-swiper-button-next:hover'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-three .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .absp-image-carousel-item.element-three .elementor-swiper-button.elementor-swiper-button-next:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-four .elementor-swiper-button.elementor-swiper-button-prev:hover::after, {{WRAPPER}} .absp-image-carousel-item.element-four .elementor-swiper-button.elementor-swiper-button-next:hover::after'  => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-five .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .absp-image-carousel-item.element-five .elementor-swiper-button.elementor-swiper-button-next:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-six .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .absp-image-carousel-item.element-six .elementor-swiper-button.elementor-swiper-button-next:hover' => 'color: {{VALUE}};',
				],
			],
			'dots_color'               => [
				'selectors' => [
					'{{WRAPPER}} .absp-image-carousel-item.element-one .swiper-pagination-bullet'   => 'background: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-two .swiper-pagination-bullet'   => 'background: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-three .swiper-pagination-bullet' => 'background: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-four .swiper-pagination-bullet'  => 'background: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-five .swiper-pagination-bullet'  => 'background: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-six .swiper-pagination-bullet'   => 'background: {{VALUE}};',
				],
			],
			'dots_border_color'        => [
				'selectors' => [
					'{{WRAPPER}} .absp-image-carousel-item.element-one .swiper-pagination-bullet'   => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-two .swiper-pagination-bullet'   => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-three .swiper-pagination-bullet' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-four .swiper-pagination-bullet'  => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-five .swiper-pagination-bullet'  => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-six .swiper-pagination-bullet'   => 'border-color: {{VALUE}};',
				],
			],
			'dots_active_color'        => [
				'selectors' => [
					'{{WRAPPER}} .absp-image-carousel-item.element-one .swiper-pagination-bullet.swiper-pagination-bullet-active'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-two .swiper-pagination-bullet.swiper-pagination-bullet-active'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-three .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-four .swiper-pagination-bullet.swiper-pagination-bullet-active'  => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-five .swiper-pagination-bullet.swiper-pagination-bullet-active'  => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-six .swiper-pagination-bullet.swiper-pagination-bullet-active'   => 'background-color: {{VALUE}};',
				],
			],
			'dots_active_border_color' => [
				'selectors' => [
					'{{WRAPPER}} .absp-image-carousel-item.element-one .swiper-pagination-bullet.swiper-pagination-bullet-active'   => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-two .swiper-pagination-bullet.swiper-pagination-bullet-active'   => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-three .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-four .swiper-pagination-bullet.swiper-pagination-bullet-active'  => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-five .swiper-pagination-bullet.swiper-pagination-bullet-active'  => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-six .swiper-pagination-bullet.swiper-pagination-bullet-active'   => 'border-color: {{VALUE}};',
				],
			],
			'dots_size'                => [
				'selectors' => [
					'{{WRAPPER}} .absp-image-carousel-item.element-one .swiper-pagination-bullet'   => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-two .swiper-pagination-bullet'   => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-three .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-four .swiper-pagination-bullet'  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-five .swiper-pagination-bullet'  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-image-carousel-item.element-six .swiper-pagination-bullet'   => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			],
		] );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Image_Carousel $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( [
			'absp_slider' => [
				'class' => 'absp-image-carousel-slider-wrapper absp-swiper-wrapper swiper-container',
			],
		] );
		$this->add_render_attribute( [ 'absp_slider' => $this->get_slider_attributes( $settings ) ] );
		$item_class = 'absp-image-carousel-slider swiper-slide';
		if ( 'enable' === $settings['img_carousel_img_hover_animation'] ) {
			$item_class .= ' image-carousel-img-hover-animation';
		}
		$this->add_render_attribute( [
			'absp_slider_item' => [
				'class' => $item_class,
			],
		] );
		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-image-carousel-item -->
					<div class="absp-image-carousel-item element-<?php echo esc_attr( $settings['absolute_image_carousel'] ); ?>">
						<div class="image-carousel-item-wrapper">
							<div <?php $this->print_render_attribute_string( 'absp_slider' ); ?>>
								<?php if ( $settings['img_carousel_gallery'] ) : ?>
									<div class="swiper-wrapper">
										<?php foreach ( $settings['img_carousel_gallery'] as $img ) { ?>
											<div <?php $this->print_render_attribute_string( 'absp_slider_item' ); ?>>
												<img src="<?php echo esc_url( $img['url'] ) ?>">
											</div>
										<?php } ?>
									</div>
								<?php else : ?>
									<div class="swiper-wrapper">
										<div <?php $this->print_render_attribute_string( 'absp_slider_item' ); ?>>
											<img src="<?php absp_default_placeholder_src() ?>">
										</div>
										<div <?php $this->print_render_attribute_string( 'absp_slider_item' ); ?>>
											<img src="<?php absp_default_placeholder_src() ?>">
										</div>
										<div <?php $this->print_render_attribute_string( 'absp_slider_item' ); ?>>
											<img src="<?php absp_default_placeholder_src() ?>">
										</div>
										<div <?php $this->print_render_attribute_string( 'absp_slider_item' ); ?>>
											<img src="<?php absp_default_placeholder_src() ?>">
										</div>
									</div>
								<?php endif; ?>
							</div>
							<?php $this->slider_nav( $settings ); ?>
						</div>
					</div>
					<!-- absp-image-carousel-item -->
				</div>
			</div>
		</div>
		<?php
	}
}
