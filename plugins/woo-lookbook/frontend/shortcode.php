<?php

/**
 * Class WOO_F_LOOKBOOK_Frontend_Shortcode
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOO_F_LOOKBOOK_Frontend_Shortcode {
	protected $settings;
	protected $data;
	protected $run_shortcode;

	public function __construct() {


		$this->settings = new WOO_F_LOOKBOOK_Data();
		/*Register scripts*/
		add_action( 'wp_enqueue_scripts', array( $this, 'shortcode_scripts' ) );
		/*Overlay*/
		add_action( 'wp_footer', array( $this, 'overlay' ) );

		/*Auto update Instagram*/

		/*Register shortcode*/
		add_shortcode( 'woocommerce_lookbook', array( $this, 'register_shortcode' ) );
		add_shortcode( 'woocommerce_lookbook_slide', array( $this, 'register_shortcode_slide' ) );
		add_shortcode( 'woocommerce_lookbook_instagram', array( $this, 'register_shortcode_instagram' ) );

		/*Show quick view*/
		add_action( 'wp_ajax_nopriv_wlb_get_product', array( $this, 'get_product' ) );
		add_action( 'wp_ajax_wlb_get_product', array( $this, 'get_product' ) );

		/*Show Instagram on quickview*/
		add_action( 'wp_ajax_nopriv_wlb_get_lookbook', array( $this, 'get_lookbook' ) );
		add_action( 'wp_ajax_wlb_get_lookbook', array( $this, 'get_lookbook' ) );

		/*Quick view*/
		add_action( 'woocommerce_lookbook_single_product_summary', array( $this, 'product_single_title' ), 5 );
		add_action( 'woocommerce_lookbook_single_product_summary', 'woocommerce_template_single_rating', 10 );
		add_action( 'woocommerce_lookbook_single_product_summary', array( $this, 'product_price' ), 10 );
		add_action( 'woocommerce_lookbook_single_product_summary', array( $this, 'product_short_desc' ), 20 );
		add_action( 'woocommerce_lookbook_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		add_action( 'woocommerce_lookbook_single_product_summary', array( $this, 'read_more' ), 40 );

		/*Product on quickview Gallery*/
		add_action( 'woocommerce_lookbook_single_product_gallery', array( $this, 'product_single_title' ), 5 );
		add_action( 'woocommerce_lookbook_single_product_gallery', array( $this, 'product_price' ), 10 );
		add_action( 'woocommerce_lookbook_single_product_gallery', 'woocommerce_template_single_add_to_cart', 30 );

		add_action( 'wlb_elementor_register_scripts', array( $this, 'shortcode_scripts' ) );
		add_action( 'wlb_elementor_register_scripts', array( $this, 'localize_script' ) );
		add_action( 'wlb_elementor_get_inline_style', array( $this, 'get_inline_style' ) );
	}

	public function product_short_desc() {
		global $post;

		$short_description = $post->post_excerpt;

		if ( ! $short_description ) {
			return;
		}

		?>
        <div class="wlb-product-short-description">
			<?php echo wp_kses_post( $short_description ); // WPCS: XSS ok. ?>
        </div>
	<?php }

	/**
	 * Show Product price
	 */
	public function product_price() {
		global $product;
		?>
        <div class="wlb-product-price">
			<?php echo wp_kses_post( $product->get_price_html() ); ?>
        </div>
	<?php }

	/**
	 * Show product title
	 */
	public function product_single_title() {
		$url = get_the_permalink();
		the_title( '<h3 class="wlb-product-title entry-title"><a target="_blank" href="' . esc_url( $url ) . '">', '</a></h3>' );
	}

	/**
	 * Add remove link
	 */
	public function read_more() {
		if ( $this->settings->see_more() ) {
			return false;
		}
		global $post; ?>
        <div class="wlb-read-more">
            <a class="wlb-read-more-button"
               href="<?php the_permalink( $post->ID ) ?>"><?php esc_html_e( 'See more', 'woo-lookbook' ) ?></a>
        </div>
	<?php }

	/**
	 * Quick view Instagram
	 */
	public function get_lookbook() {
		check_ajax_referer( 'viwlb-nonce', 'nonce' );

		global $product, $post;
		$lookbook_id = filter_input( INPUT_POST, 'lookbook_id', FILTER_SANITIZE_NUMBER_INT );
		if ( $lookbook_id ) {
			$products = $this->get_data( $lookbook_id, 'product_id', array() );
			?>
            <div class="wlb-left">
				<?php echo do_shortcode( '[woocommerce_lookbook id="' . $lookbook_id . '"]' ) ?>
            </div>
            <div class="wlb-right wlb-product-galleries">
                <div class="wlb-instagram-controls">
					<?php $likes = $this->get_data( $lookbook_id, 'likes' );
					if ( $likes ) {
						?>
                        <div class="wlb-instagram-controls-likes">
							<?php echo esc_html( $likes ) ?>
                        </div>
					<?php }
					$comments = $this->get_data( $lookbook_id, 'comments' );
					if ( $comments ) {
						?>
                        <div class="wlb-instagram-controls-comments">
							<?php echo esc_html( $comments ) ?>
                        </div>
					<?php }
					$code = $this->get_data( $lookbook_id, 'code' );
					if ( $code && $this->settings->ins_link() ) {
						?>
                        <div class="wlb-instagram-controls-link">
							<?php $ins_url = 'https://www.instagram.com/p/' . $code ?>
                            <a target="_blank"
                               href="<?php echo esc_url( $ins_url ) ?>"><?php esc_html_e( 'View on Instagram', 'woo-lookbook' ) ?></a>
                        </div>
					<?php } ?>
                </div>
                <div class="wlb-instagram-description">
					<?php echo esc_html( get_post_field( 'post_title', $lookbook_id ) ) ?>
                </div>
				<?php if ( count( $products ) ) { ?>
					<?php foreach ( $products as $product_id ) {

						$product = wc_get_product( $product_id );
						$post    = get_post( $product_id );
						/**
						 * woocommerce_single_product_summary hook.
						 *
						 * @hooked woocommerce_template_single_title - 5
						 * @hooked woocommerce_template_single_price - 10
						 * @hooked woocommerce_template_single_add_to_cart - 30
						 */
						?>

                        <div class="wlb-product-gallery">
							<?php
							do_action( 'woocommerce_lookbook_single_product_gallery' ); ?>
                        </div>
						<?php
					} ?>

					<?php
				} ?>

            </div>

		<?php }
		die;
	}

	/**
	 * Quick view
	 */
	public function get_product() {
		check_ajax_referer( 'viwlb-nonce', 'nonce' );

		global $product, $post;
		$prod_id = filter_input( INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT );
		$product = wc_get_product( $prod_id );
		$post    = get_post( $prod_id );
		?>
        <div class="wlb-left">
			<?php if ( has_post_thumbnail() ) {
				$image = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) );
				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '%s', $image ), $post->ID );
			} ?>
        </div>
        <div class="wlb-right">
			<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			do_action( 'woocommerce_lookbook_single_product_summary' ); ?>
        </div>
		<?php
		die;
	}

	/**
	 * Init Overlay
	 */
	public function overlay() {
		if ( ! $this->run_shortcode ) {
			return;
		}
		wp_enqueue_script( 'wc-add-to-cart-variation' );
		?>
        <div class="woo-lookbook-quickview" style="display: none">
            <div class="woo-lookbook-quickview-inner single-product">
                <div class="wlb-overlay"></div>
                <div class="wlb-product-wrapper">
					<?php echo wp_kses_post( $this->loading_html() ); ?>
                    <div class="wlb-product-frame" style="display: none">
                        <div class="wlb-product-data product"></div>
						<?php if ( ! $this->settings->enable_close_button() ) { ?>
                            <span class="wlb-close"></span>
						<?php } ?>
                        <div class="wlb-controls">
                            <span class="wlb-controls-next"></span>
                            <span class="wlb-controls-previous"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wlb-added">
			<?php echo esc_html__( 'Product added successful', 'woo-lookbook' ) ?>
        </div>
	<?php }

	/**
	 * Load loading html
	 * @return mixed
	 */
	private function loading_html() {
		ob_start(); ?>
        <div class="wlb-rotating-plane wlb-loading"></div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Shortcode scripts
	 */
	public function shortcode_scripts() {
		//libs
		wp_register_style( 'woocommerce-vi-flexslider', WOO_F_LOOKBOOK_CSS . 'vi_flexslider.min.css', array(), WOO_F_LOOKBOOK_VERSION );
		wp_register_script( 'jquery-slides', WOO_F_LOOKBOOK_JS . 'jquery.slides.min.js', array( 'jquery' ), '3.0.4' );
		wp_register_script( 'jquery-vi-flexslider', WOO_F_LOOKBOOK_JS . 'jquery.vi_flexslider.min.js', array( 'jquery' ), '2.7.0' );

		//exe
		wp_register_script( 'woo-lookbook', WOO_F_LOOKBOOK_JS . 'woo-lookbook.js', array( 'jquery' ), true, true );
		wp_register_style( 'woo-lookbook', WOO_F_LOOKBOOK_CSS . 'woo-lookbook.css', array(), WOO_F_LOOKBOOK_VERSION );
	}

	public function get_inline_style() {
		/*Node Custom CSS*/
		$icon_background_color  = $this->settings->get_icon_background_color();
		$icon_color             = $this->settings->get_icon_color();
		$icon_border_color      = $this->settings->get_icon_border_color();
		$title_color            = $this->settings->get_title_color();
		$title_background_color = $this->settings->get_title_background_color();
		$css                    = ".woo-lookbook .woo-lookbook-inner .wlb-item .wlb-pulse{
			background-color:{$icon_background_color};
			border-color:{$icon_border_color};
			color:{$icon_color};
		}
		.woo-lookbook .woo-lookbook-inner .wlb-item .wlb-dot{
			border-color:{$icon_border_color};
		}
		.woo-lookbook .woo-lookbook-inner .wlb-item.default{
			background-color:{$icon_background_color};
			color:{$icon_color};
		}
		.woo-lookbook .woo-lookbook-inner .wlb-item .wlb-pin:after{
			background-color:{$icon_color};
		}
		.woo-lookbook .woo-lookbook-inner .wlb-item .wlb-pin{
			background-color:{$icon_background_color};
		}
		.woo-lookbook .wlb-speech-bubble{
			background-color: {$title_background_color};
			color:{$title_color};
		}
		.woo-lookbook .wlb-speech-bubble:after{
			border-top-color: {$title_background_color};
		}
		";

		/*Quick view Custom CSS*/
		if ( ! $this->settings->link_redirect() ) {
			$text_color       = $this->settings->get_text_color();
			$background_color = $this->settings->get_background_color();
			$border_radius    = $this->settings->get_border_radius() . 'px';
			$css              .= ".woo-lookbook-quickview-inner .wlb-product-wrapper .wlb-product-frame{
				border-radius:{$border_radius};
				background-color:{$background_color};
				color:{$text_color};
			}
			
			";
		}
		$custom_css = $this->settings->get_custom_css();
		if ( $custom_css ) {
			$css .= $custom_css;
		}
		wp_add_inline_style( 'woo-lookbook', $css );
	}

	public function localize_script() {
		$data_slide = array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'nonce'      => wp_create_nonce( 'viwlb-nonce' ),
			'width'      => $this->settings->get_slide_width(),
			'height'     => $this->settings->get_slide_height(),
			'navigation' => $this->settings->slide_navigation() ? true : false,
			'effect'     => $this->settings->slide_effect() ? 'fade' : 'slide',
			'pagination' => $this->settings->slide_pagination() ? true : false,
			'auto'       => $this->settings->slide_auto_play() ? true : false,
			'time'       => $this->settings->get_slide_time(),
		);
		wp_localize_script( 'woo-lookbook', '_woocommerce_lookbook_params', $data_slide );
	}

	public function enqueue_scripts() {
//		wp_enqueue_script( 'flexslider' );
//		wp_enqueue_script( 'wc-single-product' );
		wp_enqueue_script( 'jquery-slides' );
		wp_enqueue_script( 'jquery-vi-flexslider' );
		wp_enqueue_style( 'woocommerce-vi-flexslider' );
//
		wp_enqueue_script( 'woo-lookbook' );
		wp_enqueue_style( 'woo-lookbook' );

		$this->get_inline_style();
		$this->localize_script();
//		add_action( 'wp_footer', array( $this, 'overlay' ) );
	}
//		public function shortcode_scripts() {
//
////		wp_enqueue_style( 'woocommerce-vi-flexslider', WOO_F_LOOKBOOK_CSS . 'vi_flexslider.min.css', array(), WOO_F_LOOKBOOK_VERSION );
////		wp_enqueue_style( 'woo-lookbook', WOO_F_LOOKBOOK_CSS . 'woo-lookbook.css', array(), WOO_F_LOOKBOOK_VERSION );
//
//
//
//		/*Init Data look book*/
//
//		$suffix     = WP_DEBUG ? '.js' : '.min.js';
//		wp_enqueue_script( 'jquery-slides', WOO_F_LOOKBOOK_JS . 'jquery.slides' . $suffix, array( 'jquery' ), '3.0.4' );
//		wp_enqueue_script( 'jquery-vi-flexslider', WOO_F_LOOKBOOK_JS . 'jquery.vi_flexslider.min.js', array( 'jquery' ), '2.7.0' );
//		wp_enqueue_script( 'woo-lookbook', WOO_F_LOOKBOOK_JS . 'woo-lookbook.js', array( 'jquery' ), WOO_F_LOOKBOOK_VERSION );
//
//
//	}

	/**
	 * @param $atts
	 */
	public function register_shortcode_instagram( $atts ) {
		$this->enqueue_scripts();
		$this->run_shortcode = true;

		$atts      = shortcode_atts(
			array(
				'style' => $this->settings->get_ins_display() ? 'carousel' : 'gallery',
				'row'   => $this->settings->get_ins_items_per_row(),
				'limit' => $this->settings->get_ins_display_limit(),
			), $atts
		);
		$args      = array(
			'post_type'      => 'woocommerce-lookbook',
			'post_status'    => array( 'publish' ),
			'posts_per_page' => $atts['limit'],
			'order'          => 'DESC',
			'orderby'        => 'date',
			'meta_query'     => array(
				array(
					'key'     => 'wlb_params',
					'value'   => 's:9:"instagram";s:1',
					'compare' => 'LIKE',
				),
			)
		);
		$the_query = new WP_Query( $args );


		ob_start(); ?>
        <div class="woo-lookbook <?php echo $atts['style'] == 'carousel' ? 'wlb-lookbook-carousel' : 'wlb-lookbook-gallery' ?>"
             data-col="<?php echo esc_attr( $atts['row'] ) ?>">
            <div class="woo-lookbook-inner">
				<?php if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						$id           = get_the_ID();
						$attachmen_id = $this->get_data( $id, 'image' );
						$pos_x        = $this->get_data( $id, 'x' );
						$pos_y        = $this->get_data( $id, 'y' );
						$src          = wp_get_attachment_image_url( $attachmen_id, 'lookbook' );
						$products     = $this->get_data( $id, 'product_id' );
						if ( $src ) {
							?>
                            <div class="wlb-lookbook-item-wrapper wlb-lookbook-instagram-item wlb-col-<?php echo esc_attr( $atts['row'] ) ?>">
                                <div class="wlb-lookbook-instagram-item-inner">
                                    <img src="<?php echo esc_url( $src ) ?>" class=""/>
									<?php
									if ( is_array( $products ) && count( $products ) ) {
										foreach ( $products as $k => $product ) {
											if ( ! $product ) {
												continue;
											}
											echo $this->get_node( $product, $pos_x[ $k ], $pos_y[ $k ] );
										}
									} ?>
                                    <div class="wlb-zoom" data-id="<?php echo esc_attr( $id ) ?>"></div>
                                </div>
                            </div>
						<?php }
					}
				}
				wp_reset_postdata();
				?>
            </div>
        </div>
		<?php $html = ob_get_clean();

		return $html;
	}

	/**
	 * @param $atts
	 */
	public
	function register_shortcode_slide(
		$atts
	) {
		$this->enqueue_scripts();
		$this->run_shortcode = true;

		$atts = shortcode_atts(
			array(
				'id' => '',
			), $atts
		);

		$ids = $atts['id'];
		if ( ! $ids ) {
			return false;
		}

		$ids = array_filter( explode( ',', trim( $ids ) ) );
		if ( count( $ids ) < 1 ) {
			return false;
		}

		ob_start(); ?>
        <div class="wlb-lookbook-slide">
			<?php foreach ( $ids as $id ) {
				$image_id = $this->get_data( $id, 'image' );

				$products = $this->get_data( $id, 'product_id' );
				$pos_x    = $this->get_data( $id, 'x' );
				$pos_y    = $this->get_data( $id, 'y' );

				$img_url = wp_get_attachment_url( $image_id );

				if ( ! $img_url ) {
					continue;
				} ?>
                <div class="woo-lookbook wlb-lookbook-item-wrapper">
                    <div class="woo-lookbook-inner">
                        <img src="<?php echo esc_url( $img_url ) ?>" class="wlb-image"/>
						<?php if ( count( $products ) && is_array( $products ) ) {
							foreach ( $products as $k => $product ) {
								if ( ! $product ) {
									continue;
								}
								echo $this->get_node( $product, $pos_x[ $k ], $pos_y[ $k ] );
								?>
							<?php }
						} ?>
                    </div>
                </div>
			<?php } ?>
        </div>
		<?php $html = ob_get_clean();

		return $html;
	}

	/*
	 *
	 */
	/**
	 * Shortcode HTML
	 *
	 * @param $atts
	 *
	 * @return bool|string
	 */
	public function register_shortcode( $atts ) {
		$this->enqueue_scripts();
		$this->run_shortcode = true;
		$atts                = shortcode_atts( array( 'id' => '', ), $atts );

		$ids = $atts['id'];
		if ( ! $ids ) {
			return false;
		}

		$ids = array_filter( explode( ',', trim( $ids ) ) );
		if ( count( $ids ) < 1 ) {
			return false;
		}

		ob_start();

		foreach ( $ids as $id ) {
			$image_id = $this->get_data( $id, 'image' );

			$products = $this->get_data( $id, 'product_id' );
			$pos_x    = $this->get_data( $id, 'x' );
			$pos_y    = $this->get_data( $id, 'y' );

			$img_url = wp_get_attachment_url( $image_id );

			if ( ! $img_url ) {
				continue;
			} ?>
            <div class="woo-lookbook wlb-lookbook-item-wrapper">
                <div class="woo-lookbook-inner">
                    <img src="<?php echo esc_url( $img_url ) ?>" class="wlb-image"/>
					<?php if ( count( $products ) && is_array( $products ) ) {
						foreach ( $products as $k => $product ) {
							if ( ! $product ) {
								continue;
							}
							echo $this->get_node( $product, $pos_x[ $k ], $pos_y[ $k ] );
							?>
						<?php }
					} ?>
                </div>
            </div>
            <div class="wlb-clearfix"></div>
		<?php }
		$html = ob_get_clean();

		return $html;
	}

	/**
	 * Make mark on image
	 *
	 * @param $product_id
	 * @param $pos_x
	 * @param $pos_y
	 *
	 * @return mixed
	 */
	private function get_node( $product_id, $pos_x, $pos_y ) {
		ob_start();
		$link = '';
		if ( $this->settings->link_redirect() ) {
			$product = wc_get_product( $product_id );
			if ( ! is_object( $product ) || $product->get_status() != 'publish' ) {
				return ob_get_clean();
			}
			if ( $product->is_type( 'external' ) && $this->settings->external_product() ) {
				$url = get_post_meta( $product->get_id(), '_product_url', '#' );
				if ( ! $url ) {
					$url = get_permalink( $product_id );
				}
				$link = '<a target="_blank" class="wlb-link" href="' . esc_url( $url ) . '"></a>';
			} else {
				$link = '<a target="_blank" class="wlb-link" href="' . $product->get_permalink() . '"></a>';

			}
			$class = 'wlb-redirect';
		} else {
			$class = 'wlb-ajax';
		}

		/*Make title HTML*/
		$title_html = '';
		if ( ! $this->settings->hide_title() ) {
			$title = get_post_field( 'post_title', $product_id );
			if ( $title ) {
				$title_html = '<div class="wlb-speech"><div class="wlb-speech-bubble">' . esc_html( $title ) . '</div></div>';
			}
		}

		?>
        <div class="wlb-item default <?php echo esc_attr( $class ) ?>" data-pid="<?php echo esc_attr( $product_id ) ?>"
             style="left: <?php echo esc_attr( $pos_x ); ?>%;top:<?php echo esc_attr( $pos_y ) ?>%;"><?php echo wp_kses_post( $link ) ?>
            +<?php echo wp_kses_post( $title_html ) ?></div>
		<?php


		return ob_get_clean();
	}

	/**
	 * Get Post Meta
	 *
	 * @param $field
	 *
	 * @return bool
	 */
	private function get_data( $post_id, $field, $default = '' ) {
		if ( isset( $this->data[ $post_id ] ) && $this->data[ $post_id ] ) {
			$params = $this->data[ $post_id ];
		} else {
			$this->data[ $post_id ] = get_post_meta( $post_id, 'wlb_params', true );
			$params                 = $this->data[ $post_id ];
		}

		if ( isset( $params[ $field ] ) && $field ) {
			return $params[ $field ];
		} else {
			return $default;
		}
	}

}