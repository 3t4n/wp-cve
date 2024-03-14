<?php
/**
 * Renderer class for custom slides and base class for dynamic slide renderers.
 *
 * @since  1.0.0
 */
class BQW_SP_Slide_Renderer {

	/**
	 * Data of the slide.
	 *
	 * @since 4.0.0
	 * 
	 * @var array
	 */
	protected $data = null;

	/**
	 * ID of the slider to which the slide belongs.
	 *
	 * @since 4.0.0
	 * 
	 * @var int
	 */
	protected $slider_id = null;

	/**
	 * index of the slide.
	 *
	 * @since 4.0.0
	 * 
	 * @var int
	 */
	protected $slide_index = null;

	/**
	 * Indicates whether the slide's images will be lazy loaded.
	 *
	 * @since 4.0.0
	 * 
	 * @var bool
	 */
	protected $lazy_loading = null;

	/**
	 * Indicates whether the slide's image or link can be opened in a lightbox.
	 *
	 * @since 4.0.0
	 * 
	 * @var bool
	 */
	protected $lightbox = null;

	/**
	 * Indicates the target of the slide links.
	 *
	 * @since 4.1
	 * 
	 * @var bool
	 */
	protected $link_target = null;

	/**
	 * Indicates whether the thumbnail images will be generated automatically based on the slide image.
	 *
	 * @since 4.0.0
	 * 
	 * @var bool
	 */
	protected $auto_thumbnail_images = null;

	/**
	 * Indicates the image size that will be used for thumbnails.
	 *
	 * @since 4.0.0
	 * 
	 * @var bool
	 */
	protected $thumbnail_image_size = null;

	/**
	 * HTML markup of the slide.
	 *
	 * @since 4.0.0
	 * 
	 * @var string
	 */
	protected $html_output = '';

	/**
	 * No implementation yet
	 * .
	 * @since 4.0.0
	 */
	public function __construct() {
		
	}

	/**
	 * Set the data of the slide.
	 *
	 * @since 4.0.0
	 * 
	 * @param array $data        The data of the slide.
	 * @param int   $slider_id   The id of the slider.
	 * @param int   $slide_index The index of the slide.
	 * @param bool  $extra_data  Extra settings data for the slider.
	 */
	public function set_data( $data, $slider_id, $slide_index, $extra_data ) {
		$this->data = $data;
		$this->slider_id = $slider_id;
		$this->slide_index = $slide_index;
		$this->lazy_loading = $extra_data->lazy_loading;
		$this->lightbox = $extra_data->lightbox;
		$this->hide_image_title = $extra_data->hide_image_title;
		$this->link_target = $extra_data->link_target;
		$this->auto_thumbnail_images = $extra_data->auto_thumbnail_images;
		$this->thumbnail_image_size = $extra_data->thumbnail_image_size;
	}

	/**
	 * Create the main image(s), link, inline HTML and layers, and return the HTML markup of the slide.
	 *
	 * @since  1.0.0
	 *
	  * @return string the HTML markup of the slide.
	 */
	public function render() {
		global $allowedposttags;

		$allowed_html = array_merge(
			$allowedposttags,
			array(
				'iframe' => array(
					'src' => true,
					'width' => true,
					'height' => true,
					'allow' => true,
					'allowfullscreen' => true,
					'class' => true,
					'id' => true
				),
				'source' => array(
					'src' => true,
					'type' => true
				)
			)
		);

		$allowed_html = apply_filters( 'sliderpro_allowed_html', $allowed_html );

		$classes = 'sp-slide';
		$classes = apply_filters( 'sliderpro_slide_classes' , $classes, $this->slider_id, $this->slide_index );

		$this->html_output = "\r\n" . '		<div class="' . esc_attr( $classes ) . '">';

		if ( $this->has_main_image() ) {
			$this->html_output .= "\r\n" . '			' . ( $this->has_main_image_link() ? $this->add_link_to_main_image( $this->create_main_image() ) : $this->create_main_image() );
		}

		$thumbnail_image = '';

		if ( $this->has_thumbnail_image() ) {
			$thumbnail_image = $this->has_thumbnail_link() ? $this->add_link_to_thumbnail( $this->create_thumbnail_image() ) : $this->create_thumbnail_image();
		}

		if ( $this->has_thumbnail_content() ) {
			$thumbnail_content = $this->data['thumbnail_content'];

			if ( strpos( $thumbnail_content, '[sp_thumbnail_image]' ) !== false ) {
				$thumbnail_content = str_replace( '[sp_thumbnail_image]', $thumbnail_image, $thumbnail_content );
			}

			$classes = "sp-thumbnail";
			$classes = apply_filters( 'sliderpro_thumbnail_classes', $classes, $this->slider_id, $this->slide_index );

			$this->html_output .= "\r\n" . '			' . '<div class="' . esc_attr( $classes ) . '">' . wp_kses( $thumbnail_content, $allowed_html ) . "\r\n" . '			' . '</div>';
		} else {
			$this->html_output .= "\r\n" . '			' . $thumbnail_image;
		}

		if ( $this->has_caption() ) {
			$classes = "sp-caption";
			$classes = apply_filters( 'sliderpro_caption_classes', $classes, $this->slider_id, $this->slide_index );
			
			$this->html_output .= "\r\n" . '			<div class="' . esc_attr( $classes ) . '">' . wp_kses( $this->create_caption(), $allowed_html ) . '</div>';
		}

		if ( $this->has_html() ) {
			$this->html_output .= "\r\n" . '			' . wp_kses( $this->create_html(), $allowed_html );
		}

		if ( $this->has_layers() ) {
			$this->html_output .= "\r\n" . '			' . $this->create_layers();
		}

		$this->html_output .= "\r\n" . '		</div>';

		$this->html_output = apply_filters( 'sliderpro_slide_markup', $this->html_output, $this->slider_id, $this->slide_index );

		return $this->html_output;
	}

	/**
	 * Check if the slide has a main image.
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean
	 */
	protected function has_main_image() {
		if ( isset( $this->data['main_image_source'] ) && $this->data['main_image_source'] !== '' ) {
			return true;
		}

		return false;
	}

	/**
	 * Create the HTML markup for the main image.
	 *
	 * @since  1.0.0
	 * 
	 * @return string HTML markup
	 */
	protected function create_main_image() {
		$main_image_source = $this->lazy_loading === true ? ' src="' . plugins_url( 'public/assets/css/images/blank.gif', dirname( __FILE__ ) ) . '" data-src="' . esc_attr( $this->data['main_image_source'] ) . '"' : ' src="' . esc_attr( $this->data['main_image_source'] ) . '"';
		$main_image_alt = isset( $this->data['main_image_alt'] ) && $this->data['main_image_alt'] !== '' ? ' alt="' . esc_attr( $this->data['main_image_alt'] ) . '"' : '';
		$main_image_title = isset( $this->data['main_image_title'] ) && $this->data['main_image_title'] !== '' && $this->hide_image_title === false ? ' title="' . esc_attr( $this->data['main_image_title'] ) . '"' : '';
		$main_image_retina_source = isset( $this->data['main_image_retina_source'] ) && $this->data['main_image_retina_source'] !== '' ? ' data-retina="' . esc_attr( $this->data['main_image_retina_source'] ) . '"' : '';
		$main_image_small_source = isset( $this->data['main_image_small_source'] ) && $this->data['main_image_small_source'] !== '' ? ' data-small="' . esc_attr( $this->data['main_image_small_source'] ) . '"' : '';
		$main_image_medium_source = isset( $this->data['main_image_medium_source'] ) && $this->data['main_image_medium_source'] !== '' ? ' data-medium="' . esc_attr( $this->data['main_image_medium_source'] ) . '"' : '';
		$main_image_large_source = isset( $this->data['main_image_large_source'] ) && $this->data['main_image_large_source'] !== '' ? ' data-large="' . esc_attr( $this->data['main_image_large_source'] ) . '"' : '';
		$main_image_retina_small_source = isset( $this->data['main_image_retina_small_source'] ) && $this->data['main_image_retina_small_source'] !== '' ? ' data-retinasmall="' . esc_attr( $this->data['main_image_retina_small_source'] ) . '"' : '';
		$main_image_retina_medium_source = isset( $this->data['main_image_retina_medium_source'] ) && $this->data['main_image_retina_medium_source'] !== '' ? ' data-retinamedium="' . esc_attr( $this->data['main_image_retina_medium_source'] ) . '"' : '';
		$main_image_retina_large_source = isset( $this->data['main_image_retina_large_source'] ) && $this->data['main_image_retina_large_source'] !== '' ? ' data-retinalarge="' . esc_attr( $this->data['main_image_retina_large_source'] ) . '"' : '';
		
		$main_image_width = '';
		$main_image_height = '';

		if ( $main_image_small_source === '' &&  $main_image_medium_source === '' && $main_image_large_source === '' && $main_image_retina_small_source === '' && $main_image_retina_medium_source === '' && $main_image_retina_large_source === '' ) {
			$main_image_width = isset( $this->data['main_image_width'] ) && $this->data['main_image_width'] != 0 ? ' width="' . esc_attr( $this->data['main_image_width'] ) . '"' : '';
			$main_image_height = isset( $this->data['main_image_height'] ) && $this->data['main_image_height'] != 0 ? ' height="' . esc_attr( $this->data['main_image_height'] ) . '"' : '';
		}

		$classes = "sp-image";

		$classes = apply_filters( 'sliderpro_main_image_classes', $classes, $this->slider_id, $this->slide_index );
		$main_image = '<img class="' . esc_attr( $classes ) . '"' . $main_image_source . $main_image_retina_source . $main_image_small_source . $main_image_medium_source . $main_image_large_source . $main_image_retina_small_source . $main_image_retina_medium_source . $main_image_retina_large_source . $main_image_alt . $main_image_title . $main_image_width . $main_image_height . ' />';

		return $main_image;
	}

	/**
	 * Check if the slide has a link for the main image(s).
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean
	 */
	protected function has_main_image_link() {
		if ( ( isset( $this->data['main_image_link'] ) && $this->data['main_image_link'] !== '' ) || $this->lightbox === true ) {
			return true;
		} 

		return false;
	}

	/**
	 * Create a link for the main image(s).
	 *
	 * If the lightbox is enabled and a link was not specified,
	 * add the main image URL as a link.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string  $image The image markup.
	 * @return string         The link markup.
	 */
	protected function add_link_to_main_image( $image ) {
		$main_image_link_href = '';

		if ( isset( $this->data['main_image_link'] ) && $this->data['main_image_link'] !== '' ) {
			$main_image_link_href = $this->data['main_image_link'];
		} else if ( $this->lightbox === true ) {
			$main_image_link_href = $this->data['main_image_source'];
		}

		$main_image_link_href = apply_filters( 'sliderpro_slide_link_url', $main_image_link_href, $this->slider_id, $this->slide_index );

		$classes = "";
		$classes = apply_filters( 'sliderpro_slide_link_classes', $classes, $this->slider_id, $this->slide_index );

		$main_image_link_title = isset( $this->data['main_image_link_title'] ) && $this->data['main_image_link_title'] !== '' ? ' title="' . esc_attr( $this->data['main_image_link_title'] ) . '"' : '';
		$main_image_link = 
			'<a class="' . esc_attr( $classes ) . '" href="' . esc_attr( $main_image_link_href ) . '"' . $main_image_link_title . ' target="' . esc_attr( $this->link_target ) . '">' . 
				"\r\n" . '				' . $image . 
			"\r\n" . '			' . '</a>';
		
		return $main_image_link;
	}

	/**
	 * Check if the slide has thumbnail content.
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean
	 */
	protected function has_thumbnail_content() {
		if ( isset( $this->data['thumbnail_content'] ) && $this->data['thumbnail_content'] !== '' ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if the slide has a thumbnail image.
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean
	 */
	protected function has_thumbnail_image() {
		if ( ( isset( $this->data['thumbnail_source'] ) && $this->data['thumbnail_source'] !== '' ) || $this->auto_thumbnail_images === true ) {
			return true;
		}

		return false;
	}

	/**
	 * Create the HTML markup for the thumbnail image.
	 *
	 * @since  1.0.0
	 * 
	 * @return string HTML markup
	 */
	protected function create_thumbnail_image() {
		$thumbnail_image = '';
		$thumbnail_source = '';

		if ( isset( $this->data['thumbnail_source'] ) !== false && $this->data['thumbnail_source'] !== '' ) {
			$thumbnail_source = $this->data['thumbnail_source'];
		} else if ( $this->auto_thumbnail_images === true ) {
			if ( isset( $this->data['main_image_id'] ) !== false && $this->data['main_image_id'] !== '' && $this->data['main_image_id'] !== 0 ) {
				$image_id = $this->data['main_image_id'];
				$attachment_image = wp_get_attachment_image_src( $image_id, $this->thumbnail_image_size );

				if ( $attachment_image !== false ) {
					$thumbnail_source = $attachment_image[0];
				}
			} else if ( isset( $this->data['main_image_source'] ) && $this->data['main_image_source'] !== '' ) {
				$thumbnail_source = $this->data['main_image_source'];
			}
		}

		$thumbnail_source = $this->lazy_loading === true ? ' src="' . plugins_url( 'public/assets/css/images/blank.gif', dirname( __FILE__ ) ) . '" data-src="' . esc_attr( $thumbnail_source ) . '"' : ' src="' . esc_attr( $thumbnail_source ) . '"';
		$thumbnail_alt = isset( $this->data['thumbnail_alt'] ) && $this->data['thumbnail_alt'] !== '' ? ' alt="' . esc_attr( $this->data['thumbnail_alt'] ) . '"' : '';
		$thumbnail_title = isset( $this->data['thumbnail_title'] ) && $this->data['thumbnail_title'] !== '' && $this->hide_image_title === false ? ' title="' . esc_attr( $this->data['thumbnail_title'] ) . '"' : '';
		$thumbnail_retina_source = isset( $this->data['thumbnail_retina_source'] ) && $this->data['thumbnail_retina_source'] !== '' ? ' data-retina="' . esc_attr( $this->data['thumbnail_retina_source'] ) . '"' : '';

		if ( $this->has_thumbnail_content() ) {
			$thumbnail_image = '<img ' . $thumbnail_source . $thumbnail_retina_source . $thumbnail_alt . $thumbnail_title . ' />';
		} else {
			$classes = "sp-thumbnail";
			$classes = apply_filters( 'sliderpro_thumbnail_classes', $classes, $this->slider_id, $this->slide_index );

			$thumbnail_image = '<img class="' . esc_attr( $classes ) . '"' . $thumbnail_source . $thumbnail_retina_source . $thumbnail_alt . $thumbnail_title . ' />';
		}

		return $thumbnail_image;
	}

	/**
	 * Check if the slide has a link for the thumbnail.
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean
	 */
	protected function has_thumbnail_link() {
		if ( ( isset( $this->data['thumbnail_link'] ) && $this->data['thumbnail_link'] !== '' ) ) {
			return true;
		} 

		return false;
	}

	/**
	 * Create a link for the thumbnail.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string  $image The thumbnail image markup.
	 * @return string         The thumbnail link markup.
	 */
	protected function add_link_to_thumbnail( $image ) {
		$thumbnail_link_href = '';

		if ( isset( $this->data['thumbnail_link'] ) && $this->data['thumbnail_link'] !== '' ) {
			$thumbnail_link_href = $this->data['thumbnail_link'];
		}

		$thumbnail_link_href = apply_filters( 'sliderpro_thumbnail_link_url', $thumbnail_link_href, $this->slider_id, $this->slide_index );

		$classes = "";
		$classes = apply_filters( 'sliderpro_thumbnail_link_classes', $classes, $this->slider_id, $this->slide_index );

		$thumbnail_link_title = isset( $this->data['thumbnail_link_title'] ) && $this->data['thumbnail_link_title'] !== '' ? ' title="' . esc_attr( $this->data['thumbnail_link_title'] ) . '"' : '';
		$thumbnail_link = 
			'<a class="' . esc_attr( $classes ) . '" href="' . esc_attr( $thumbnail_link_href ) . '"' . $thumbnail_link_title . '>' . 
				"\r\n" . '				' . $image . 
			"\r\n" . '			' . '</a>';
		
		return $thumbnail_link;
	}

	/**
	 * Check if the slide has a caption.
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean
	 */
	protected function has_caption() {
		if ( isset( $this->data['caption'] ) && $this->data['caption'] !== '' ) {
			return true;
		} 

		return false;
	}

	/**
	 * Create caption for the slide.
	 *
	 * @since 4.0.0
	 * 
	 * @return string The caption.
	 */
	protected function create_caption() {
		$caption = $this->data['caption'];
		$caption = do_shortcode( $caption );
		$caption = apply_filters( 'sliderpro_slide_caption', $caption, $this->slider_id, $this->slide_index );

		return $caption;
	}

	/**
	 * Check if the slide has inline HTML.
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean
	 */
	protected function has_html() {
		if ( isset( $this->data['html'] ) && $this->data['html'] !== '' ) {
			return true;
		} 

		return false;
	}

	/**
	 * Create inline HTML for the slide.
	 *
	 * @since 4.0.0
	 * 
	 * @return string The inline HTML.
	 */
	protected function create_html() {
		$html = $this->data['html'];
		$html = do_shortcode( $html );
		$html = apply_filters( 'sliderpro_slide_html', $html, $this->slider_id, $this->slide_index );

		return $html;
	}

	/**
	 * Check if the slide has layers.
	 *
	 * @since  1.0.0
	 * 
	 * @return boolean
	 */
	protected function has_layers() {
		if ( isset( $this->data['layers'] ) && ! empty( $this->data['layers'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Create layers for the slide and return the HTML markup.
	 *
	 * @since 4.0.0
	 * 
	 * @return string The HTML output for the layers.
	 */
	protected function create_layers() {
		$layers_output = '';
		$layers = array_reverse( $this->data['layers'] );

		foreach ( $layers as $layer ) {
			$layers_output .= $this->create_layer( $layer );
		}

		return $layers_output;
	}

	/**
	 * Create a layer.
	 *
	 * @since  1.0.0
	 * 
	 * @param  array  $data The data of the layer.
	 * @return string       The HTML output of the layer.
	 */
	protected function create_layer( $data ) {
		$layer = BQW_SP_Layer_Renderer_Factory::create_layer( $data );
		$layer->set_data( $data, $this->slider_id, $this->slide_index, $this->lazy_loading );
		
		return $layer->render();
	}
}