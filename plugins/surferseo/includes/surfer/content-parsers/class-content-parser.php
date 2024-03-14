<?php
/**
 *  General Parser object, that handles all general parsing function.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Surfer\Content_Parsers;

/**
 * Object that imports data from different sources into WordPress.
 */
class Content_Parser {

	/**
	 * Title of the imported post.
	 *
	 * @var string
	 */
	protected $title;


	/**
	 * Returns content for chosen editor.
	 *
	 * @param string $content - Content from Surfer.
	 * @return string
	 */
	public function parse_content( $content ) {

		$this->title = wp_strip_all_tags( $this->get_title_from_content( $content ) );

		return $content;
	}

	/**
	 * Returns title of the imported post.
	 *
	 * @param string $content - Content from Surfer.
	 * @return string
	 */
	public function parse_title( $content ) {
		return $this->get_title_from_content( $content );
	}

	/**
	 * Returns title of the imported post.
	 *
	 * @return string
	 */
	public function return_title() {

		return $this->title;
	}

	/**
	 * Runs actions that require post ID
	 *
	 * @param int $post_id - ID of the post.
	 * @return void
	 */
	public function run_after_post_insert_actions( $post_id ) {
	}


	/**
	 * Extract h1 from content, to use it as post title.
	 *
	 * @param string $content - Content from Surfer.
	 * @return string
	 */
	protected function get_title_from_content( $content ) {

		preg_match( '~<h1[^>]*>(.*?)</h1>~i', $content, $match );
		$title = $match[1];

		return $title;
	}

	/**
	 * Saves image from provided URL into WordPress media library
	 *
	 * @param string $image_url - URL to the image.
	 * @param string $image_alt - Alternative text for the image.
	 * @param bool   $url_only  - if true, returns only URL to image, if false, returns image ID.
	 * @return string URL to image in media library.
	 */
	protected function download_img_to_media_library( $image_url, $image_alt = '', $url_only = true ) {

		$file_name = basename( $image_url );
		$image_id  = $this->find_image_by_name( $file_name );
		if ( 0 === $image_id ) {
			$image_id = $this->upload_images_to_wp( $image_url );
		}

		$this->update_image_alt( $image_id, $image_alt );
		$media_library_image_url = wp_get_attachment_url( $image_id );

		if ( $url_only ) {
			return $media_library_image_url;
		}

		return array(
			'id'  => $image_id,
			'url' => $media_library_image_url,
		);
	}

	/**
	 * Upload image from Surfer to WordPress and replace src to local one.
	 *
	 * @param string $image_url - URL to the image.
	 * @return int
	 */
	private function upload_images_to_wp( $image_url ) {

		if ( ! isset( $image_url ) || empty( $image_url ) || ! wp_http_validate_url( $image_url ) ) {
			return 0;
		}

		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$file_name     = basename( $image_url );
		$tmp_directory = download_url( $image_url );

		$extension = pathinfo( $image_url, PATHINFO_EXTENSION );
		if ( ! isset( $extension ) || empty( $extension ) || '' === $extension ) {
			$headers = get_headers( $image_url );
			foreach ( $headers as $header ) {
				if ( false !== strpos( $header, 'Content-Disposition' ) ) {
					preg_match( '~filename="(.*?)\.(.*?)"~i', $header, $match );
					$file_name .= '.' . $match[2];
					break;
				}
			}
		}

		$file_array = array(
			'tmp_name' => $tmp_directory,
			'name'     => $file_name,
		);

		$attachment_id = media_handle_sideload( $file_array );
		update_post_meta( $attachment_id, 'surfer_file_name', $file_name );
		@unlink( $tmp_directory ); // phpcs:ignore

		return $attachment_id;
	}

	/**
	 * Search for image by name and return it's URL.
	 *
	 * @param string $file_name - name of the file.
	 * @return int
	 */
	private function find_image_by_name( $file_name ) {

		$image_id = 0;

		$file_name = explode( '.', $file_name );
		$file_name = $file_name[0];

		$args = array(
			'post_type'      => 'attachment',
			'name'           => sanitize_title( $file_name ),
			'posts_per_page' => 1,
			'post_status'    => 'inherit',
		);

		$matching_images = get_posts( $args );

		if ( $matching_images ) {
			$image    = array_pop( $matching_images );
			$image_id = $image->ID;
		}

		return $image_id;
	}

	/**
	 * Updates alt param for image.
	 *
	 * @param int    $image_id - ID of the attachment to update.
	 * @param string $image_alt - possible alt attribute for image.
	 * @return void
	 */
	private function update_image_alt( $image_id, $image_alt ) {

		if ( isset( $image_alt ) ) {
			update_post_meta( $image_id, '_wp_attachment_image_alt', trim( $image_alt ) );
		}
	}

	/**
	 * Turns attributes array into HTML string.
	 *
	 * @param array $attributes_array - array of attributes.
	 * @return string
	 */
	protected function glue_attributes( $attributes_array ) {

		$attributes = ' ';

		foreach ( $attributes_array as $key => $value ) {
			$attributes .= $key . '="' . $value . '" ';
		}
		$attributes = rtrim( $attributes );

		return $attributes;
	}

	/**
	 * Extract inner HTML for provided node.
	 *
	 * @param DOMElement $node - node element to parse.
	 * @return string
	 */
	protected function get_inner_html( $node ) {
		$inner_html = '';

		// @codingStandardsIgnoreLine
		foreach ( $node->childNodes as $child ) {

			// @codingStandardsIgnoreLine
			$content = $child->ownerDocument->saveXML( $child );

			if ( '<li/>' !== $content ) {
				$inner_html .= $content;
			}
		}

		return $inner_html;
	}
}
