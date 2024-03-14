<?php

/**
 *
 */
class Photo_Gallery_Shortcode {


	private $loader;

	function __construct() {

		$this->loader  = new Photo_Gallery_Template_Loader();

		add_shortcode( 'photo-gallery', array( $this, 'gallery_shortcode_handler' ) );
		add_shortcode( 'Photo-gallery', array( $this, 'gallery_shortcode_handler' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'pgb_gallery_scripts' ) );

		// Add shortcode related hooks
		add_filter( 'photo_gallery_shortcode_item_data', 'photo_gallery_generate_image_links', 10, 3 );
	}

	public function pgb_gallery_scripts() {

		wp_enqueue_style( 'photo_gallery_lightbox2_stylesheet', PHOTO_GALLERY_BUILDER_ASSETS . 'css/lightbox.min.css', null, PHOTO_GALLERY_BUILDER_CURRENT_VERSION );
	
		// Scripts necessary for some galleries
		wp_enqueue_script( 'photo_gallery_lightbox2_script', PHOTO_GALLERY_BUILDER_ASSETS . 'js/lightbox.min.js', array( 'jquery' ), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );
		wp_enqueue_script( 'photo_gallery_packery', PHOTO_GALLERY_BUILDER_ASSETS . 'js/packery.min.js', array( 'jquery' ), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );
		wp_enqueue_script( 'photo_gallery_isotope', PHOTO_GALLERY_BUILDER_ASSETS . 'js/isotope.pkgd.js', array( 'jquery' ), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );
		wp_enqueue_script( 'photo_gallery_imagesloaded', PHOTO_GALLERY_BUILDER_ASSETS . 'js/imagesloaded.pkgd.min.js', array( 'jquery' ), PHOTO_GALLERY_BUILDER_CURRENT_VERSION, true );
		
		wp_enqueue_style( 'bootstrap-front-css', PHOTO_GALLERY_BUILDER_ASSETS . 'css/bootstrap-front.css', null, PHOTO_GALLERY_BUILDER_CURRENT_VERSION );
		wp_enqueue_style('pgb-font-awesome-5.0.8', PHOTO_GALLERY_BUILDER_ASSETS.'css/font-awesome-latest/css/fontawesome-all.min.css');

	}


	public function gallery_shortcode_handler( $atts ) {

		$default_atts = array(
			'id' => false,
			'align' => '',
		);

		$atts = wp_parse_args( $atts, $default_atts );

		if ( ! $atts['id'] ) {
			return esc_html__( 'Gallery not found.', 'photo-gallery-builder' );
		}

		/* Generate uniq id for this gallery */
		$gallery_id = 'pgb-' . $atts['id'];

		// Check if is an old Photo Gallery post or new.
		$gallery = get_post( $atts['id'] );
		if ( 'pg_builder' != get_post_type( $gallery ) ) {
			$gallery_posts = get_posts( array(
				'post_type' => 'pg_builder',
				'post_status' => 'publish',
				'meta_query' => array(
					array(
						'key'     => 'photo-gallery-id',
						'value'   => $atts['id'],
						'compare' => '=',
					),
				),
			) );

			if ( empty( $gallery_posts ) ) {
				return esc_html__( 'Gallery not found.', 'photo-gallery-builder' );
			}

			$atts['id'] = $gallery_posts[0]->ID;

		}

		/* Get gallery settings */
		$settings = get_post_meta( $atts['id'], 'photo-gallery-settings', true );
		$default  = Photo_Gallery_CPT_Fields_Helper::get_defaults();
		$settings = wp_parse_args( $settings, $default );

		$type = 'creative-gallery';
		if ( isset( $settings['type'] ) ) {
			$type = $settings['type'];
		}else{
			$settings['type'] = 'creative-gallery';
		}
		
		$pre_gallery_html = apply_filters( 'photo_gallery_pre_output_filter_check', false, $settings, $gallery );

		if ( false !== $pre_gallery_html ) {

			// If there is HTML, then we stop trying to display the gallery and return THAT HTML.
			$pre_output =  apply_filters( 'photo_gallery_pre_output_filter','', $settings, $gallery );
			return $pre_output;

		}


		/* Get gallery images */
		$images = apply_filters( 'photo_gallery_before_shuffle_images', get_post_meta( $atts['id'], 'photo-gallery-images', true ), $settings );
		
		//$images = apply_filters( 'photo_gallery_images', $images, $settings );

		if ( empty( $settings ) || empty( $images ) ) {
			return esc_html__( 'Gallery not found.', 'photo-gallery-builder' );
		}

		$template_data = array(
			'gallery_id' => $gallery_id,
			'settings'   => $settings,
			'images'     => $images,
			'loader'     => $this->loader,
		);

		ob_start();

		/* Config for gallery script */
		$js_config = array(
			"margin"          => absint( $settings['margin'] ),
			'type'            => $type,
			'gutter'          => isset( $settings['gutter'] ) ? absint($settings['gutter']) : 10,
		);
		
		$template_data['js_config'] = apply_filters( 'pgb_photo_gallery_settings', $js_config, $settings );
		
		
		echo $this->generate_gallery_css( $gallery_id, $settings );
				
		
		$this->loader->set_template_data( $template_data );


    	$this->loader->get_template_part( 'photo', 'gallery' ); //load photo-gallery.php

    	$html = ob_get_clean();
    	return $html;
	}
	
	private function generate_gallery_css( $gallery_id, $settings ) {

		$css = "<style>";

		if ( $settings['borderSize'] ) {
			$css .= "#{$gallery_id} img{ border: " . absint($settings['borderSize']) . "px solid " . sanitize_hex_color($settings['borderColor']) . "; }";
		}

		if ( $settings['borderRadius'] ) {
			$css .= "#{$gallery_id} img { border-radius: " . absint($settings['borderRadius']) . "px; }";
		}
		
		if( $settings['layout'] == 1 || $settings['layout'] == 4 ){
			if ( $settings['shadowSize'] ) {
				$css .= "#{$gallery_id} img { box-shadow: " . sanitize_hex_color($settings['shadowColor']) . " 0px 0px " . absint($settings['shadowSize']) . "px; }";
			}
		}

	
		$css .= "#{$gallery_id} .photo-gallery-item .caption { background-color: " . sanitize_hex_color($settings['captionColor']) . ";  }";
		if ( '' != $settings['captionColor'] || '' != $settings['captionFontSize'] ) {
			$css .= "#{$gallery_id} .photo-gallery-item .figc {";
			if ( '' != $settings['captionColor'] ) {
				$css .= 'color:' . sanitize_hex_color($settings['captionColor']) . ';';
			}
			$css .= '}';
		}

		if ( '' != $settings['titleFontSize'] && 0 != $settings['titleFontSize'] ) {
			$css .= "#{$gallery_id} .pgb-title {  font-size: " . absint($settings['titleFontSize']) . "px; }";
		}


		$css .= "#{$gallery_id}  p.description { color:" . sanitize_hex_color($settings['captionColor']) . ";font-size:" . absint($settings['captionFontSize']) . "px; }";

		if ( '' != $settings['titleColor'] ) {
			$css .= "#{$gallery_id}  .pgb-title { color:" . sanitize_hex_color($settings['titleColor']) . "; }";
		}else{
			$css .= "#{$gallery_id}  .pgb-title { color:" . sanitize_hex_color($settings['captionColor']) . "; }";
		}

		$css = apply_filters( 'photo_gallery_shortcode_css', $css, $gallery_id, $settings );


		if ( strlen( $settings['style'] ) ) {
			$css .= esc_html($settings['style']);
		}

		$css .= "</style>\n";

		return $css;

	}
}

new Photo_Gallery_Shortcode();