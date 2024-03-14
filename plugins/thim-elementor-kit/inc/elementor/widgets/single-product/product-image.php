<?php

namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Product_Image extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-product-image';
	}

	public function get_title() {
		return esc_html__( 'Product Image', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-product-images';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_PRODUCT );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_layout_style',
			array(
				'label' => esc_html__( 'General', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'thumb_style',
			array(
				'label'   => esc_html__( 'Thumbnails Setting', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'slides',
				'options' => array(
					'slides'  => esc_html__( 'Sliders', 'thim-elementor-kit' ),
					'columns' => esc_html__( 'Columns', 'thim-elementor-kit' ),
				),
			)
		);
		$this->add_control(
			'slides_options',
			array(
				'label'     => esc_html__( 'Slider Setting', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'horizontal',
				'options'   => array(
					'horizontal' => esc_html__( 'Horizontal', 'thim-elementor-kit' ),
					'vertical'   => esc_html__( 'Vertical', 'thim-elementor-kit' ),
					//					'carousel'       => esc_html__( 'Carousel', 'thim-elementor-kit' ),
				),
				'condition' => array(
					'thumb_style' => 'slides'
				),
			)
		);

		$this->add_responsive_control(
			'columns_options',
			array(
				'label'     => esc_html__( 'Select Columns', 'thim-elementor-kit' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 5,
				'step'      => 1,
				'default'   => 3,
				'selectors' => array(
					'{{WRAPPER}}' => '--ekits-product-image-column: {{VALUE}}',
				),
				'condition' => array(
					'thumb_style'    => [ 'columns', 'slides' ],
					'slides_options' => 'horizontal'
				)
			)
		);
		$this->add_responsive_control(
			'thumbnail_v_width',
			array(
				'label'      => esc_html__( 'Thumbnail Width', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 120,
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--ekits-thumbnail-vertical-width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'thumb_style'    => 'slides',
					'slides_options' => 'vertical'
				),
			)
		);
		$this->add_control(
			'thumbnail_v_pos',
			array(
				'label'       => esc_html__( 'Thumbnail Position', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'row-reverse',
				'options'     => array(
					'row-reverse' => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'row'         => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}} .ekits-product-slides__vertical' => 'flex-direction: {{VALUE}}',
				),
				'condition'   => array(
					'thumb_style'    => 'slides',
					'slides_options' => 'vertical'
				),
			)
		);
		$this->end_controls_section();

		$this->_register_setting_thumb_slider_nav_style(
			esc_html__( 'Nav Feature', 'thim-elementor-kit' ), 'feature', '.ekits-product-slides__wrapper'
		);

		$this->_register_setting_thumb_slider_nav_style(
			esc_html__( 'Nav Thumbnail', 'thim-elementor-kit' ), 'thumbnail', '.ekits-product-thumbnails__wrapper'
		);

		$this->_register_style_image();
		$this->_register_style_thumbnail();
	}

	protected function _register_style_image() {
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => esc_html__( 'Image', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name'    => 'image_size',
				'include' => [],
				'default' => 'woocommerce_single'
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} .ekits-product-columns__wrapper li:first-child img,{{WRAPPER}} .ekits-product-slides__wrapper .woocommerce-product-gallery__image img',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ekits-product-columns__wrapper li:first-child img,{{WRAPPER}} .ekits-product-slides__wrapper .woocommerce-product-gallery__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'image_slider_spacing',
			array(
				'label'       => esc_html__( 'Spacing (px)', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'default'     => '10',
				'selectors'   => array(
					'{{WRAPPER}} .woocommerce-product-gallery' => '--ekit-image-slider-spacing: {{SIZE}}px;',
				),
				'condition'   => array(
					'thumb_style' => 'slides'
				),
			)
		);
		$this->end_controls_section();
	}

	protected function _register_style_thumbnail() {
		$this->start_controls_section(
			'section_thumbnail_style',
			array(
				'label' => esc_html__( 'Thumbnail', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name'    => 'thumbnail_size',
				'include' => [],
				'default' => 'thumbnail'
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'thumbnail_border',
				'selector'  => '{{WRAPPER}} .ekits-product-columns__wrapper img,{{WRAPPER}} .ekits-product-thumbnails__wrapper img',
				'separator' => 'before',
			)
		);
		$this->add_control(
			'thumbnail_border_active',
			array(
				'label'     => esc_html__( 'Border Color Active', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'thumbnail_border_border!' => 'none',
					'thumb_style'              => 'slides'
				),
				'selectors' => array(
					'{{WRAPPER}} .ekits-product-thumbnails__wrapper .flex-active-slide img' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnail_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ekits-product-columns__wrapper img,{{WRAPPER}} .ekits-product-thumbnails__wrapper img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnail_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--ekits-thumbnail-spacing: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function _register_setting_thumb_slider_nav_style( $label, $key, $class_name, $condition = null ) {
		$section_args = [
			'label'     => $label,
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => array(
				'thumb_style' => 'slides'
			),
		];

		if ( is_array( $condition ) ) {
			$section_args['condition'] = $condition;
		}

		$this->start_controls_section(
			$key . '_nav_style_tab', $section_args
		);

		$this->add_responsive_control(
			$key . '_nav_size',
			array(
				'label'       => esc_html__( 'Icon Size (px)', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-font-size: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			$key . '_nav_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			$key . '_nav_width',
			array(
				'label'       => esc_html__( 'Width (px)', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-width: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			$key . '_nav_height',
			array(
				'label'       => esc_html__( 'Height (px)', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-height: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			$key . '_nav_border',
			array(
				'label'     => esc_html_x( 'Border Type', 'Border Control', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'none'   => esc_html__( 'None', 'thim-elementor-kit' ),
					'solid'  => esc_html_x( 'Solid', 'Border Control', 'thim-elementor-kit' ),
					'double' => esc_html_x( 'Double', 'Border Control', 'thim-elementor-kit' ),
					'dotted' => esc_html_x( 'Dotted', 'Border Control', 'thim-elementor-kit' ),
					'dashed' => esc_html_x( 'Dashed', 'Border Control', 'thim-elementor-kit' ),
					'groove' => esc_html_x( 'Groove', 'Border Control', 'thim-elementor-kit' ),
				),
				'default'   => 'none',
				'selectors' => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-border-style: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			$key . '_nav_border_dimensions',
			array(
				'label'     => esc_html_x( 'Width', 'Border Control', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'condition' => array(
					$key . '_nav_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			$key . '_offset_h',
			array(
				'label'       => esc_html__( 'Horizontal Orientation (px)', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => - 500,
				'step'        => 1,
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-offset-h:{{VALUE}}px;',
				),
			)
		);
		$this->add_responsive_control(
			$key . '_offset_v',
			array(
				'label'       => esc_html__( 'Vertical Position (px)', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => - 500,
				'step'        => 1,
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-offset-v:{{VALUE}}px;',
				),
			)
		);

		$this->start_controls_tabs(
			$key . '_nav_color_tabs'
		);

		$this->start_controls_tab(
			$key . '_nav_color_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			$key . '_nav_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			$key . '_nav_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-bg-color: {{VALUE}}',
				),
			)
		);
		$this->add_control(
			$key . '_nav_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					$key . '_nav_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			$key . '_nav_tab_color_hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			$key . '_nav_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-color-hover: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			$key . '_nav_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-bg-color-hover: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			$key . '_nav_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					$key . '_nav_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} ' . $class_name . ' .flex-direction-nav' => '--ekits-nav-slider-border-color-hover: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function render() {
		do_action( 'thim-ekit/modules/single-product/before-preview-query' );
		$product = wc_get_product();

		if ( ! $product ) {
			return;
		}
		$this->ekits_get_gallery_image( $product );
		do_action( 'thim-ekit/modules/single-product/after-preview-query' );
	}

	function ekits_get_gallery_image_html( $attachment_id, $main_image = false ) {
		$settings       = $this->get_settings_for_display();
		$crop_thumbnail = true;

		if ( $settings['thumb_style'] == 'columns' ) {
			$crop_thumbnail = false;
		}

		$thumbnail_size = $settings['thumbnail_size_size'];

		if ( $thumbnail_size == 'custom' ) {
			$gallery_thumbnail = $settings['thumbnail_size_custom_dimension'];
			$thumbnail_size    = array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] );
		}
		$custom_image_size = $settings['image_size_size'];
		if ( $custom_image_size == 'custom' ) {
			$image_size_custom_dis = $settings['image_size_custom_dimension'];
			$custom_image_size     = array( $image_size_custom_dis['width'], $image_size_custom_dis['height'] );
		}

		$image_size    = apply_filters( 'woocommerce_gallery_image_size', $crop_thumbnail || $main_image ? $custom_image_size : $thumbnail_size );
		$full_size     = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
		$thumbnail_src = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
		$full_src      = wp_get_attachment_image_src( $attachment_id, $full_size );
		$alt_text      = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
		$image         = wp_get_attachment_image(
			$attachment_id,
			$image_size,
			false,
			apply_filters(
				'woocommerce_gallery_image_html_attachment_image_params',
				array(
					'title'                   => _wp_specialchars( get_post_field( 'post_title', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
					'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attachment_id ), ENT_QUOTES, 'UTF-8', true ),
					'data-src'                => esc_url( $full_src[0] ),
					'data-large_image'        => esc_url( $full_src[0] ),
					'data-large_image_width'  => esc_attr( $full_src[1] ),
					'data-large_image_height' => esc_attr( $full_src[2] ),
					'class'                   => esc_attr( $main_image ? 'wp-post-image' : '' ),
				),
				$attachment_id,
				$image_size,
				$main_image
			)
		);

		return '<li data-thumb="' . esc_url( $thumbnail_src[0] ) . '" data-thumb-alt="' . esc_attr( $alt_text ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_src[0] ) . '">' . $image . '</a></li>';
	}

	function ekits_get_gallery_image( $product ) {
		$settings = $this->get_settings_for_display();

		$post_thumbnail_id = $product->get_image_id();

		$wrapper_classes = apply_filters(
			'woocommerce_single_product_image_gallery_classes',
			array(
				'woocommerce-product-gallery',
				'ekits-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
				'ekits-product-' . $settings['thumb_style'] . '__' . $settings[$settings['thumb_style'] . '_options'],
				'images',
			)
		);
		?>
		<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>">
			<div
				class="ekits-product-<?php echo esc_attr( $settings['thumb_style'] ) ?>__wrapper">
				<ul class="<?php echo esc_attr( $settings['thumb_style'] ) ?>">
					<?php
					if ( $post_thumbnail_id ) {
						$html = $this->ekits_get_gallery_image_html( $post_thumbnail_id, true );
					} else {
						$html = '<li class="woocommerce-product-gallery__image--placeholder">';
						$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
						$html .= '</li>';
					}

					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );

					$this->ekits_get_thumbnail_image( $product );
					?>
				</ul>
			</div>
			<?php
			if ( $settings['thumb_style'] == 'slides' ) {
				$data_slider = 'data-direction = "' . $settings['slides_options'] . '"';
				if ( $settings['slides_options'] == 'horizontal' ) {
					$data_slider .= ' data-marginitem="' . $settings['thumbnail_spacing']['size'] . '"';
					$data_slider .= ' data-itemshow="' . $settings['columns_options'] . '"';
					if ( isset( $settings['columns_options_tablet'] ) && $settings['columns_options_tablet'] ) {
						$data_slider .= ' data-itemshowtablet="' . $settings['columns_options_tablet'] . '"';
					}
					if ( isset( $settings['columns_options_mobile'] ) && $settings['columns_options_mobile'] ) {
						$data_slider .= ' data-itemshowmobile="' . $settings['columns_options_mobile'] . '"';
					}
				}
				echo '<div class="ekits-product-thumbnails__wrapper"' . $data_slider . '></div>';
			}
			?>
		</div>
		<?php
		if ( $settings['thumb_style'] == 'slides' ) {
			// js for flexslider
			wp_enqueue_script( 'flexslider' );
			$this->ekits_js_slider();
		}
	}
	
	function ekits_get_thumbnail_image( $product ) {
		$attachment_ids = $product->get_gallery_image_ids();
		if ( $attachment_ids && $product->get_image_id() ) {
			foreach ( $attachment_ids as $attachment_id ) {
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $this->ekits_get_gallery_image_html( $attachment_id ), $attachment_id );
			}
		}
	}

	function ekits_js_slider() { ?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				jQuery('.ekits-product-gallery--with-images').each(function () {
					var $galleryWrapper = jQuery(this);
					var $gallery = $galleryWrapper.find('.ekits-product-slides__wrapper');
					var $thumbnails = $galleryWrapper.find('.ekits-product-thumbnails__wrapper');

					var direction = 'horizontal';
					if (jQuery().flexslider) {
						if ($thumbnails.length !== 0) {
							direction = $thumbnails.data('direction');
							var marginItem = 0, width_item = $thumbnails.innerWidth();
							if (direction === 'vertical') {
								$thumbnails.css("height", $gallery.find('li').height());
							} else {
								var itemShow = $thumbnails.data('itemshow'),
									itemshowtablet = $thumbnails.data('itemshowtablet'),
									itemshowmobile = $thumbnails.data('itemshowmobile');

								if (jQuery(window).outerWidth() < 1024 && itemshowtablet !== undefined) {
									itemShow = itemshowtablet;
								}
								if (jQuery(window).outerWidth() < 767 && itemshowmobile !== undefined) {
									itemShow = itemshowmobile;
								}

								marginItem = $thumbnails.data('marginitem'),
									width_item = (width_item - (marginItem * (itemShow - 1))) / itemShow;
							}
							createThumbnails();
							ThumbnailsSlider();
						}

						$gallery.flexslider({
							animation    : "slide",
							animationLoop: false,
							controlNav   : false,
							slideshow    : false,
							prevText     : "",
							nextText     : "",
							touch        : true,
							sync         : $thumbnails
						});
					}

					function createThumbnails() {
						var html = '<ul class="slides">';
						$gallery.find('.woocommerce-product-gallery__image').each(function () {
							var $this = jQuery(this);
							var image = $this.data('thumb'),
								alt = $this.find('a img').attr('alt'),
								title = $this.find('a img').attr('title');

							if (!title) {
								title = $this.find('a picture').attr('title');
							}

							html += '<li class="product-image-thumbnail"><img alt="' + alt + '" title="' + title + '" src="' + image + '" /></li>';
						});
						html += '</ul>';
						$thumbnails.empty();
						$thumbnails.append(html);
					}

					function ThumbnailsSlider() {
						$thumbnails.flexslider({
							animation    : "slide",
							direction    : direction,
							controlNav   : false,
							animationLoop: true,
							slideshow    : false,
							itemWidth    : width_item,
							itemMargin   : marginItem,
							asNavFor     : $gallery,
							prevText     : "",
							nextText     : "",
							// useCSS       : true,
						});
					}
				});
			})
		</script>
	<?php }
}
