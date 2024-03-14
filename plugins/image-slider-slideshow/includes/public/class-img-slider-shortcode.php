<?php

/**
 *
 */
class Img_Slider_Shortcode {


	private $loader;

	function __construct() {

		$this->loader  = new Img_Slider_Template_Loader();

		add_shortcode( 'img-slider', array( $this, 'img_slider_shortcode_handler' ) );
		add_shortcode( 'Img-Slider', array( $this, 'img_slider_shortcode_handler' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'img_slider_scripts' ) );

	}

	public function img_slider_scripts() {

		wp_enqueue_style( 'img_slider_lightbox2_stylesheet', IMG_SLIDER_ASSETS . 'css/lightbox.min.css', null, IMG_SLIDER_CURRENT_VERSION );

		wp_enqueue_style( 'img-slider-css', IMG_SLIDER_ASSETS . 'css/portfolio.css', null, IMG_SLIDER_CURRENT_VERSION );

		wp_enqueue_script( 'img_slider_packery', IMG_SLIDER_ASSETS . 'js/packery.min.js', array( 'jquery' ), IMG_SLIDER_CURRENT_VERSION, true );
		

		wp_enqueue_style('rpg-font-awesome-5.0.8', IMG_SLIDER_ASSETS.'css/font-awesome-latest/css/fontawesome-all.min.css');
		
		/*carousel*/
		wp_enqueue_style( 'img-slider-bootstrap-css', IMG_SLIDER_ASSETS . 'css/bootstrap.css', null, IMG_SLIDER_CURRENT_VERSION );
		wp_enqueue_script( 'img-slider-bootstrap-js', IMG_SLIDER_ASSETS . 'js/bootstrap.min.js', array(), IMG_SLIDER_CURRENT_VERSION, true );

		/*owl-carousel*/
		wp_enqueue_style( 'owl-carousel-css', IMG_SLIDER_ASSETS . 'css/owl.carousel.min.css', null, IMG_SLIDER_CURRENT_VERSION );
		wp_enqueue_style( 'owl-theme-default-css', IMG_SLIDER_ASSETS . 'css/owl.theme.default.min.css', null, IMG_SLIDER_CURRENT_VERSION );
		wp_enqueue_style( 'owl-animate-css', IMG_SLIDER_ASSETS . 'css/animate.css', null, IMG_SLIDER_CURRENT_VERSION );
		wp_enqueue_script( 'owl-carousel-js', IMG_SLIDER_ASSETS . 'js/owl.carousel.min.js', array(), IMG_SLIDER_CURRENT_VERSION, false );
		

		/*Thumbnail Slider*/
		wp_enqueue_style( 'custom-slider-css', IMG_SLIDER_ASSETS . 'css/custom-slider.css', null, IMG_SLIDER_CURRENT_VERSION );
		wp_enqueue_script( 'gallery-js', IMG_SLIDER_ASSETS . 'js/gallery.js', array(), IMG_SLIDER_CURRENT_VERSION, true );

		/*Swiper Master*/
		wp_enqueue_style( 'swiper-master-css', IMG_SLIDER_ASSETS . 'css/swiper.min.css', null, IMG_SLIDER_CURRENT_VERSION );
		wp_enqueue_script( 'swiper-master-js', IMG_SLIDER_ASSETS . 'js/swiper.min.js', array(), IMG_SLIDER_CURRENT_VERSION, false );


	}


	public function img_slider_shortcode_handler( $atts ) {

		$default_atts = array(
			'id' => false,
			'align' => '',
		);

		$atts = wp_parse_args( $atts, $default_atts );


		if ( ! $atts['id'] ) {
			return esc_html__( 'Gallery not found11', 'img-slider' );
		}

		/* Generate uniq id for this gallery */
		$gallery_id = 'rpg-' . $atts['id'];
		
		// Check if is an old image slider post or new.
		$gallery = get_post( $atts['id'] );
		if ( 'img_slider' != get_post_type( $gallery ) ) {
			$gallery_posts = get_posts( array(
				'post_type' => 'img_slider',
				'post_status' => 'publish',
				'meta_query' => array(
					array(
						'key'     => 'slider-id',
						'value'   => $atts['id'],
						'compare' => '=',
					),
				),
			) );

			if ( empty( $gallery_posts ) ) {
				return esc_html__( 'Gallery not found12', 'img-slider' );
			}

			$atts['id'] = $gallery_posts[0]->ID;

		}

		/* Get gallery settings */
		$settings = get_post_meta( $atts['id'], 'img-slider-settings', true );
		$default  = Img_Slider_WP_CPT_Fields_Helper::get_defaults();
		$settings = wp_parse_args( $settings, $default );

	
		$pre_gallery_html = apply_filters( 'img_slider_pre_output_filter_check', false, $settings, $gallery );

		if ( false !== $pre_gallery_html ) {

			// If there is HTML, then we stop trying to display the gallery and return THAT HTML.
			$pre_output =  apply_filters( 'img_slider_pre_output_filter','', $settings, $gallery );
			return $pre_output;

		}


		/* Get gallery images */
		$images = apply_filters( 'img_slider_before_shuffle_images', get_post_meta( $atts['id'], 'slider-images', true ), $settings );
		
		$images = apply_filters( 'img_slider_images', $images, $settings );

		if ( empty( $settings ) || empty( $images ) ) {
			return esc_html__( 'Gallery not found13', 'img-slider' );
		}

		
		do_action('portfolio_extra_scripts',$settings);

		// Main CSS & JS
		$necessary_scripts = apply_filters( 'img_slider_necessary_scripts', array( 'img-slider' ),$settings );
		$necessary_styles  = apply_filters( 'img_slider_necessary_styles', array( 'img-slider' ), $settings );

		if ( ! empty( $necessary_scripts ) ) {
			foreach ( $necessary_scripts as $script ) {
				wp_enqueue_script( $script );
			}
		}

		if ( ! empty( $necessary_styles ) ) {
			foreach ( $necessary_styles as $style ) {
				wp_enqueue_style( $style );
			}
		}


		$settings['gallery_id'] = $gallery_id;
		$settings['align']      = $atts['align'];


		$template_data = array(
			'gallery_id' => $gallery_id,
			'settings'   => $settings,
			'images'     => $images,
			'loader'     => $this->loader,
		);

		ob_start();

		/* Config for gallery script */
		$js_config = array(
			/*"margin"          => absint( $settings['margin'] ),*/
			/*'type'            => $type,*/
			'columns'         => 12,
			'gutter'          => isset( $settings['gutter'] ) ? absint($settings['gutter']) : 10,
		);
		

		$template_data['js_config'] = apply_filters( 'img_slider_settings', $js_config, $settings );
		$template_data              = apply_filters( 'img_slider_template_data', $template_data );

		
		
		echo $this->generate_gallery_css( $gallery_id, $settings );
				
		
		$this->loader->set_template_data( $template_data );


    	$this->loader->get_template_part( 'image', 'slider' ); //load image-slider.php

    	$html = ob_get_clean();
    	return $html;
	}
	
	private function generate_gallery_css( $gallery_id, $settings ) {

		$css = "<style>";


		$css .= "#{$gallery_id} .img-slider-item .caption { background-color: " . sanitize_hex_color($settings['captionColor']) . ";  }";
		if ( '' != $settings['captionColor'] || '' != $settings['captionFontSize'] ) {
			$css .= "#{$gallery_id} .img-slider-item .figc {";
			if ( '' != $settings['captionColor'] ) {
				$css .= 'color:' . sanitize_hex_color($settings['captionColor']) . ';';
			}
			$css .= '}';
		}

		if ( '' != $settings['titleFontSize'] && 0 != $settings['titleFontSize'] ) {
			$css .= "#{$gallery_id} .rpg-title {  font-size: " . absint($settings['titleFontSize']) . "px; }";
		}

		$css .= "#{$gallery_id} { width:" . esc_attr($settings['width']) . ";}";
		

		$css .= "#{$gallery_id}  p.description { color:" . sanitize_hex_color($settings['captionColor']) . ";font-size:" . absint($settings['captionFontSize']) . "px; }";

		if ( '' != $settings['titleColor'] ) {
			$css .= "#{$gallery_id}  .rpg-title { color:" . sanitize_hex_color($settings['titleColor']) . "; }";
		}else{
			$css .= "#{$gallery_id}  .rpg-title { color:" . sanitize_hex_color($settings['captionColor']) . "; }";
		}

		$css = apply_filters( 'img_slider_shortcode_css', $css, $gallery_id, $settings );


		if ( strlen( $settings['style'] ) ) {
			$css .= esc_html($settings['style']);
		}

		$css .= "</style>\n";

		return $css;

	}
}

new Img_Slider_Shortcode();