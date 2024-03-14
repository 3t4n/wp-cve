<?php

namespace TotalContest\Shortcode;

/**
 * Image shortcode class
 *
 * @package TotalContest\Shortcode
 * @since   1.0.0
 */
class Image extends Base {

	/**
	 * Handle shortcode.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function handle() {
		$image = $this->getAttribute( 'src' );

		if ( empty( $image ) && $this->getAttribute( 'id' ) ):

			$image = wp_get_attachment_image_url( $this->getAttribute( 'id' ),
			                                      $this->getAttribute( 'size', 'thumbnail' ) );
		endif;

		return sprintf( '<img loading="lazy" decoding="auto" src="%s" style="max-width: 100%%">', esc_attr( $image ) );
	}

}
