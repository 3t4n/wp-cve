<?php
/**
 * Renderer for Flickr slides.
 * 
 * @since 4.0.0
 */
class BQW_SP_Gallery_Slide_Renderer extends BQW_SP_Dynamic_Slide_Renderer {

	/**
	 * Initialize the renderer by declaring the supported tags.
	 *
	 * @since 4.0.0
	 */
	public function __construct() {
		parent::__construct();

		$this->registered_tags = array(
			'image' => array( $this, 'render_image' ),
			'image_src' => array( $this, 'render_image_src' ),
			'image_alt' => array( $this, 'render_image_alt' ),
			'image_title' => array( $this, 'render_image_title' ),
			'image_description' => array( $this, 'render_image_description' )
		);

		$this->registered_tags = apply_filters( 'sliderpro_gallery_tags', $this->registered_tags );
	}

	/**
	 * Return the final HTML markup of the slide.
	 *
	 * @since  1.0.0
	 * 
	 * @return string The slide HTML.
	 */
	public function render() {
		parent::render();
		
		$result = $this->get_gallery_images();
		$this->html_output = $this->replace_tags( $result );

		return $this->html_output;
	}

	/**
	 * Return all gallery images from the post.
	 *
	 * @since 4.0.0
	 * 
	 * @return array The array of images.
	 */
	protected function get_gallery_images() {
		global $post;
		$raw_ids = '';

		// Check if there is a classic gallery shortcode in the page and get the 'ids' attribute,
		// which contains the list of image ID's.
		// If a classic gallery was not found, check if there is a gallery block and get the ID's
		// from each gallery image.
		if ( preg_match( '/\[\s*gallery\s+ids=\W?([0-9,\s]+)\W?.*?\/?\][^\[\]]*\[sliderpro\s+id=\W' . $this->slider_id . '\W/m', $post->post_content, $matches ) ) {
			$raw_ids = $matches[ 1 ];
		} else if ( preg_match( '/wp:gallery[^\[\]]*\[sliderpro\s+id=\W' . $this->slider_id . '/ms', $post->post_content, $matches ) ) {
			if ( preg_match_all( '/wp:image.*?"id":([\d]+),.*?\/wp:image/ms', $matches[ 0 ], $id_matches ) ) {
				foreach ( $id_matches[ 1 ] as $id_match ) {
					$raw_ids .= ( $raw_ids !== '' ? ',' : '' ) . $id_match;
				}
			}
		}

		$images = array();

		if ( ! empty( $raw_ids ) ) {
			$ids = explode( ',', str_replace( ' ', '', $raw_ids ) );

			foreach ( $ids as $id ) {
				$image = get_post( $id );
				$image_alt = get_post_meta( $id, '_wp_attachment_image_alt' );
				$image->alt = ! empty( $image_alt ) ? $image_alt[0] : '';

				array_push( $images, $image );
			}
		}

		return $images;
	}

	/**
	 * Replace the registered tags with actual content
	 * and return the final HTML markup of the slide.
	 *
	 * @since 4.0.0
	 *
	 * @param  $photos The array of photos.
	 * @return string  The slide's HTML markup.
	 */
	protected function replace_tags( $images ) {
		$final_html = '';
		$tags = $this->get_slide_tags();

		foreach ( $images as $image ) {
			$content = $this->html_output;

			foreach ( $tags as $tag ) {
				$result = $this->render_tag( $tag['name'], $tag['arg'], $image );
				$content = str_replace( $tag['full'], $result, $content );
			}

			$final_html .= $content;
		}

		return $final_html;
	}

	/**
	 * Return the image as an HTML image element.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag. The image size.
	 * @param  object $photo   The current gallery image.
	 * @return string          The image HTML.
	 */
	protected function render_image( $tag_arg, $image ) {
		$image_size = $tag_arg !== false ? $tag_arg : 'full';
		$image_full = wp_get_attachment_image( $image->ID, $image_size );

		return $image_full;
	}

	/**
	 * Return the URL of the image.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag. The image size.
	 * @param  object $photo   The current gallery image.
	 * @return string          The image URL.
	 */
	protected function render_image_src( $tag_arg, $image ) {
		$image_size = $tag_arg !== false ? $tag_arg : 'full';
		$image_src = wp_get_attachment_image_src( $image->ID, $image_size );

		return $image_src[0];
	}

	/**
	 * Return the alt text of the image.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $photo   The current gallery image.
	 * @return string          The image alt.
	 */
	protected function render_image_alt( $tag_arg, $image ) {
		return $image->alt;
	}

	/**
	 * Return the title of the image.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $photo   The current gallery image.
	 * @return string          The image title.
	 */
	protected function render_image_title( $tag_arg, $image ) {
		return $image->post_title;
	}

	/**
	 * Return the description of the image.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $tag_arg The argument (optional) of the tag.
	 * @param  object $photo   The current gallery image.
	 * @return string          The image description.
	 */
	protected function render_image_description( $tag_arg, $image ) {
		return $image->post_content;
	}
}