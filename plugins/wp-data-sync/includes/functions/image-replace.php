<?php
/**
 * Image Replace
 *
 * Replace image URLs in post content and post excerpt.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

use DOMDocument;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( Settings::is_checked( 'wp_data_sync_replace_post_content_images' ) ) {
	add_filter( 'wp_data_sync_post_content', 'WP_DataSync\App\image_replace', 10, 3 );
}

if ( Settings::is_checked( 'wp_data_sync_replace_post_excerpt_images' ) ) {
	add_filter( 'wp_data_sync_post_excerpt', 'WP_DataSync\App\image_replace', 10, 3 );
}

/**
 * Image Replace.
 *
 * @param $content   string
 * @param $post_id   int
 * @param $data_sync DataSync
 *
 * @return mixed
 */

function image_replace( $content, $post_id, $data_sync ) {

	if ( ! empty( $content ) ) {

		$data_sync->set_post_id( $post_id );

		$html_dom = new DOMDocument;
		$html_dom->loadHTML( $content );

		$image_tags = $html_dom->getElementsByTagName( 'img' );

		foreach ( $image_tags as $image_tag ) {

			$image_url = $image_tag->getAttribute( 'src' );

			$data_sync->set_attachment( $image_url );

			if ( $attach_id = $data_sync->attachment() ) {

				if ( $attach_url = wp_get_attachment_url( $attach_id ) ) {
					$content = str_replace( $image_url, $attach_url, $content );
				}

			}

		}

	}

	return $content;

}