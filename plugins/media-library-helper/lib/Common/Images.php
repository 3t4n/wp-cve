<?php
/**
 * Settings Page functionality of the plugin.
 *
 * @link       http://codexin.com
 * @since      1.0.0
 *
 * @package    Codexin\ImageMetadataSettings
 * @subpackage Codexin\ImageMetadataSettings/Common
 */

namespace Codexin\ImageMetadataSettings\Common;

/**
 * Settings Page functionality of the plugin.
 */
class Images {

	/**
	 * Image attachment details
	 *
	 * @param init $attachment_id image id.
	 * @return array
	 */
	public static function wp_get_attachment( $attachment_id ) {
		$attachment = get_post( $attachment_id );
		return array(
			'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
			'caption'     => $attachment->post_excerpt,
			'description' => $attachment->post_content,
			'title'       => $attachment->post_title,
		);
	}

}
