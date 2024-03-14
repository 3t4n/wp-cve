<?php
/**
 * Attachment Trait.
 *
 * @package EverAccounting
 */

namespace EverAccounting\Traits;

defined( 'ABSPATH' ) || exit;

trait Attachment {
	/**
	 * Get attachment.
	 *
	 * @since 1.1.0
	 * @return false|\WP_Post
	 */
	public function get_attachment() {
		if ( is_callable( array( $this, 'get_attachment_id' ) ) ) {
			$attachment_id = $this->get_attachment_id();
		} elseif ( is_callable( array( $this, 'get_thumbnail_id' ) ) ) {
			$attachment_id = $this->get_thumbnail_id();
		} else {
			$attachment_id = false;
		}

		if ( ! empty( $attachment_id ) && 'attachment' === get_post_type( $attachment_id ) ) {
			return get_post( $attachment_id );
		}

		return false;
	}

	/**
	 * Get attachment image.
	 *
	 * @param string $size Image size.
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function get_attachment_image( $size = 'thumbnail' ) {
		if ( $this->get_attachment() ) {
			return wp_get_attachment_image( $this->get_attachment()->ID, $size );
		}

		return sprintf( '<img src="%s" alt="placeholder">', $this->get_attachment_url() );
	}

	/**
	 * Get attachment url.
	 *
	 * @param string $size Image size.
	 * @since 1.1.0
	 *
	 * @return false|string
	 */
	public function get_attachment_url( $size = 'thumbnail' ) {
		if ( $this->get_attachment() ) {
			return wp_get_attachment_image_url( $this->get_attachment()->ID, $size );
		}

		return $this->get_default_image_url();
	}

	/**
	 * Returns placeholder image url.
	 *
	 * @since 1.1.0
	 * @return string
	 */
	public function get_default_image_url() {
		return eaccounting()->plugin_url( '/dist/images/placeholder.png' );
	}
}
