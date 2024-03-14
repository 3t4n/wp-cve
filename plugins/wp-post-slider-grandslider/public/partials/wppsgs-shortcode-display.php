<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://forhad.net
 * @since      1.0.0
 *
 * @package    Header_Footer_Customizer
 * @subpackage Header_Footer_Customizer/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Header_Footer_Customizer
 * @subpackage Header_Footer_Customizer/public
 * @author     Forhad <need@forhad.net>
 */
class WPPSGS_Shortcode {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	public function wppsgs_shortcode_execute( $atts ) {

		$post_id = intval( $atts['id'] );

		// Slider Layout Options.
		$wppsgs_meta_root                = get_post_meta( $post_id, '_prefix_slider_options', true );
		$wppsgs_meta_slider_layout       = isset( $wppsgs_meta_root['wppsgs-layout-select'] ) ? $wppsgs_meta_root['wppsgs-layout-select'] : '';
		$wppsgs_slider_layout_dimensions = isset( $wppsgs_meta_root['wppsgs-layout-dimensions'] ) ? $wppsgs_meta_root['wppsgs-layout-dimensions'] : '';
		$wppsgs_meta_post_image          = isset( $wppsgs_meta_root['wppsgs-has-post-image'] ) ? $wppsgs_meta_root['wppsgs-has-post-image'] : '';
		$wppsgs_meta_post_title          = isset( $wppsgs_meta_root['wppsgs-has-post-title'] ) ? $wppsgs_meta_root['wppsgs-has-post-title'] : '';
		$wppsgs_meta_post_excerpt        = isset( $wppsgs_meta_root['wppsgs-has-post-excerpt'] ) ? $wppsgs_meta_root['wppsgs-has-post-excerpt'] : '';
		$wppsgs_meta_readmore_btn        = isset( $wppsgs_meta_root['wppsgs-has-post-readmore-btn'] ) ? $wppsgs_meta_root['wppsgs-has-post-readmore-btn'] : '';
		$wppsgs_meta_post_cat            = isset( $wppsgs_meta_root['wppsgs-has-post-cat'] ) ? $wppsgs_meta_root['wppsgs-has-post-cat'] : '';
		$wppsgs_meta_post_type           = isset( $wppsgs_meta_root['wppsgs-post-types-select'] ) ? $wppsgs_meta_root['wppsgs-post-types-select'] : '';
		$wppsgs_meta_cat_select          = isset( $wppsgs_meta_root['wppsgs-category-select'] ) ? $wppsgs_meta_root['wppsgs-category-select'] : '';
		$wppsgs_meta_slider_layer        = isset( $wppsgs_meta_root['wppsgs-has-layer'] ) ? $wppsgs_meta_root['wppsgs-has-layer'] : '';
		$wppsgs_meta_post_from           = isset( $wppsgs_meta_root['wppsgs-post-from'] ) ? $wppsgs_meta_root['wppsgs-post-from'] : '';
		$wppsgs_meta_post_selected       = isset( $wppsgs_meta_root['wppsgs-post-selected'] ) ? $wppsgs_meta_root['wppsgs-post-selected'] : '';
		$wppsgs_meta_post_excluded       = isset( $wppsgs_meta_root['wppsgs-post-excluded'] ) ? $wppsgs_meta_root['wppsgs-post-excluded'] : '';
		$wppsgs_meta_post_orderby        = isset( $wppsgs_meta_root['wppsgs-post-orderby'] ) ? $wppsgs_meta_root['wppsgs-post-orderby'] : '';
		$wppsgs_meta_post_order          = isset( $wppsgs_meta_root['wppsgs-post-order'] ) ? $wppsgs_meta_root['wppsgs-post-order'] : '';
		$wppsgs_meta_post_title_tag      = isset( $wppsgs_meta_root['wppsgs-has-post-title-tag'] ) ? $wppsgs_meta_root['wppsgs-has-post-title-tag'] : '';

		// Slider Settings.
		$wppsgs_meta_slider_total             = isset( $wppsgs_meta_root['wppsgs-slider-total'] ) ? $wppsgs_meta_root['wppsgs-slider-total'] : '';
		$wppsgs_meta_slider_type              = isset( $wppsgs_meta_root['wppsgs-slider-type'] ) ? $wppsgs_meta_root['wppsgs-slider-type'] : 'slide';
		$wppsgs_meta_slider_speed             = isset( $wppsgs_meta_root['wppsgs-slider-speed'] ) ? $wppsgs_meta_root['wppsgs-slider-speed'] : '400';
		$wppsgs_meta_slider_per_page          = isset( $wppsgs_meta_root['wppsgs-slider-per-page'] ) ? $wppsgs_meta_root['wppsgs-slider-per-page'] : '';
		$wppsgs_meta_carousel_gap             = isset( $wppsgs_meta_root['wppsgs-carousel-gap'] ) ? $wppsgs_meta_root['wppsgs-carousel-gap'] : '';
		$wppsgs_meta_slider_per_move          = isset( $wppsgs_meta_root['wppsgs-slider-per-move'] ) ? $wppsgs_meta_root['wppsgs-slider-per-move'] : '1';
		$wppsgs_meta_slider_arrows            = isset( $wppsgs_meta_root['wppsgs-slider-arrows'] ) ? $wppsgs_meta_root['wppsgs-slider-arrows'] : '';
		$wppsgs_meta_slider_pagination        = isset( $wppsgs_meta_root['wppsgs-slider-pagination'] ) ? $wppsgs_meta_root['wppsgs-slider-pagination'] : '';
		$wppsgs_meta_slider_autoplay          = isset( $wppsgs_meta_root['wppsgs-slider-autoplay'] ) ? $wppsgs_meta_root['wppsgs-slider-autoplay'] : '';
		$wppsgs_meta_slider_autoplay_interval = isset( $wppsgs_meta_root['wppsgs-slider-autoplay-interval'] ) ? $wppsgs_meta_root['wppsgs-slider-autoplay-interval'] : '';
		$wppsgs_meta_slider_pauseonhover      = isset( $wppsgs_meta_root['wppsgs-slider-pauseonhover'] ) ? $wppsgs_meta_root['wppsgs-slider-pauseonhover'] : '';
		$wppsgs_meta_slider_lazyload          = isset( $wppsgs_meta_root['wppsgs-slider-lazyLoad'] ) ? $wppsgs_meta_root['wppsgs-slider-lazyLoad'] : '';

		// Color Settings.
		$wppsgs_color_slider_bg         = isset( $wppsgs_meta_root['wppsgs-color-slider-bg'] ) ? $wppsgs_meta_root['wppsgs-color-slider-bg'] : '';
		$wppsgs_color_post_category     = isset( $wppsgs_meta_root['wppsgs-color-post-category'] ) ? $wppsgs_meta_root['wppsgs-color-post-category'] : '';
		$wppsgs_color_post_title        = isset( $wppsgs_meta_root['wppsgs-color-post-title'] ) ? $wppsgs_meta_root['wppsgs-color-post-title'] : '';
		$wppsgs_color_post_excerpt      = isset( $wppsgs_meta_root['wppsgs-color-post-excerpt'] ) ? $wppsgs_meta_root['wppsgs-color-post-excerpt'] : '';
		$wppsgs_color_readmore_btn      = isset( $wppsgs_meta_root['wppsgs-color-readmore-btn'] ) ? $wppsgs_meta_root['wppsgs-color-readmore-btn'] : '';
		$wppsgs_color_slider_arrow      = isset( $wppsgs_meta_root['wppsgs-color-slider-arrow'] ) ? $wppsgs_meta_root['wppsgs-color-slider-arrow'] : '';
		$wppsgs_color_slider_pagination = isset( $wppsgs_meta_root['wppsgs-color-slider-pagination'] ) ? $wppsgs_meta_root['wppsgs-color-slider-pagination'] : '';

		wp_enqueue_style( $this->plugin_name . '-shorcode-essentials' );
		wp_enqueue_script( $this->plugin_name . '-shorcode-essentials' );

		wp_enqueue_style( 'wppsgs-slider-css' );
		wp_enqueue_script( 'wppsgs-slider-js' );

		if ( 'selected' !== $wppsgs_meta_post_from ) {

			$wppsgs_query_args = array(
				'post_type'      => 'post',
				'posts_per_page' => $wppsgs_meta_slider_total,
				'cat'            => $wppsgs_meta_cat_select,
				'post__not_in'   => $wppsgs_meta_post_excluded,
				'orderby'        => $wppsgs_meta_post_orderby,
				'order'          => $wppsgs_meta_post_order,
				'post_status'    => 'publish',
			);

		} else {

			$wppsgs_query_args = array(
				'post_type'      => 'post',
				'posts_per_page' => 10,
				'post__in'       => $wppsgs_meta_post_selected,
				'orderby'        => $wppsgs_meta_post_orderby,
				'order'          => $wppsgs_meta_post_order,
				'post_status'    => 'publish',
			);
		}

		$wppsgs_post_query = new WP_Query( $wppsgs_query_args );

		if ( $wppsgs_post_query->have_posts() ) {

			ob_start();

			switch ( $wppsgs_meta_slider_layout ) {

				case 'slider-just':
					require plugin_dir_path( __FILE__ ) . '/templates/slider-just.php';
					break;

				case 'carousel-just':
					require plugin_dir_path( __FILE__ ) . '/templates/carousel-just.php';
					break;
			}
		} else {

			return '<p>No posts found!</p>';
		}

		return ob_get_clean();

	}

	/**
	 * Testimonial Shortcode output.
	 *
	 * @param String $atts Post ID
	 * @return Mix Shortcode Content.
	 */
	public function wppsgs_shortcode_tmonial_execute( $atts ) {

		$post_id = intval( $atts['id'] );

		// Loading Scripts.
		wp_enqueue_style( 'wppsgs-tmonial-style', WPPSGS_URL . 'public/css/wppsgs-tmonial.css', array(), '1.0.0' );
		wp_enqueue_script( 'wppsgs-tmonial-tweenmax', WPPSGS_URL . 'public/js/tweenmax.min.js', array( 'jquery' ), '1.19.1', true );
		wp_enqueue_script( 'wppsgs-tmonial-script', WPPSGS_URL . 'public/js/wppsgs-tmonial.js', array( 'jquery' ), '1.0.0', true );

		// Testimonial Options.
		$wppsgs_meta_tmonial_root         = get_post_meta( $post_id, '_wppsgs_tmonial_options', true );
		$wppsgs_meta_tmonial_slide_number = isset( $wppsgs_meta_tmonial_root['wppsgs-tmonial-slide-number'] ) ? $wppsgs_meta_tmonial_root['wppsgs-tmonial-slide-number'] : '';
		$wppsgs_meta_tmonial_slide_speed  = isset( $wppsgs_meta_tmonial_root['wppsgs-tmonial-slide-speed'] ) ? $wppsgs_meta_tmonial_root['wppsgs-tmonial-slide-speed'] : '';

		ob_start();

		if ( isset( $wppsgs_meta_tmonial_root['wppsgs-testimonial-group'] ) ) {

			echo '<div class="slider-wrap" data-slide="' . esc_attr( $wppsgs_meta_tmonial_slide_number ) . '" data-speed="' . esc_attr( $wppsgs_meta_tmonial_slide_speed ) . '">
			<div id="card-slider" class="slider">';

			foreach ( $wppsgs_meta_tmonial_root['wppsgs-testimonial-group'] as $tmkey => $tmvalue ) {

				$wppsgs_meta_tmonial_name  = isset( $tmvalue['wppsgs-tmonial-client-name'] ) ? $tmvalue['wppsgs-tmonial-client-name'] : '';
				$wppsgs_meta_tmonial_photo = isset( $tmvalue['wppsgs-tmonial-client-photo']['url'] ) ? $tmvalue['wppsgs-tmonial-client-photo']['url'] : '';
				$wppsgs_meta_tmonial_desig = isset( $tmvalue['wppsgs-tmonial-client-desig'] ) ? $tmvalue['wppsgs-tmonial-client-desig'] : '';
				$wppsgs_meta_tmonial_say   = isset( $tmvalue['wppsgs-tmonial-client-say'] ) ? $tmvalue['wppsgs-tmonial-client-say'] : '';

				echo '<div class="slider-item">
						<div class="animation-card_content">
							<p class="animation-card_content_description">' . wp_kses_post( $wppsgs_meta_tmonial_say ) . '</p>
						</div>
						<div class="animation-card_meta">
							<div class="animation-card_image">
								<img src="' . esc_url( $wppsgs_meta_tmonial_photo ) . '">
							</div>
							<div class="animation-card_profile">
								<h4 class="animation-card_content_title">' . esc_html( $wppsgs_meta_tmonial_name ) . '</h4>
								<p class="animation-card_content_desig">' . esc_html( $wppsgs_meta_tmonial_desig ) . '</p>
							</div>
							<div class="animation-card_stars"></div>
						</div>
			  		</div>';
			}

			echo '</div>
		  	</div>';
		}

		return ob_get_clean();
	}

}
