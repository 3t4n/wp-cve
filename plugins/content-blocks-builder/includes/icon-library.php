<?php
/**
 * The icon library
 *
 * @package   BoldBlocks
 * @author    Phi Phan <mrphipv@gmail.com>
 * @copyright Copyright (c) 2022, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( IconLibrary::class ) ) :
	/**
	 * The controller class for icon library.
	 */
	class IconLibrary extends CoreComponent {
		/**
		 * Run main hooks
		 *
		 * @return void
		 */
		public function run() {
			// Add rest api endpoint to query icon library.
			add_action( 'rest_api_init', [ $this, 'register_icon_library_endpoint' ] );

			if ( apply_filters( 'cbb_allow_upload_svg_image', true ) ) {
				// Allow SVG upload.
				add_filter( 'upload_mimes', [ $this, 'mime_types_support_svg' ] );

				// Display svg images.
				add_action( 'admin_head', [ $this, 'display_svg_thumb' ] );

				// Add metadata to svg images.
				add_filter( 'wp_update_attachment_metadata', [ $this, 'update_svg_metadata' ], 10, 2 );
			}
		}

		/**
		 * Build a custom endpoint to query icon library.
		 *
		 * @return void
		 */
		public function register_icon_library_endpoint() {
			register_rest_route(
				'cbb/v1',
				'/getIconLibrary/',
				array(
					'methods'             => 'GET',
					'callback'            => [ $this, 'get_icon_library' ],
					'permission_callback' => function () {
						return current_user_can( 'publish_posts' );
					},
				)
			);
		}

			/**
			 * Get icon library.
			 *
			 * @param WP_REST_Request $request The request object.
			 * @return void
			 */
		public function get_icon_library( $request ) {
			// icons file path.
			$icons_file = $this->the_plugin_instance->get_file_path( 'data/icon-library/icons.json' );

			// Send the error if the icons file is not exists.
			if ( ! \file_exists( $icons_file ) ) {
				wp_send_json_error( __( 'The icons.json file is not exists.', 'content-blocks-builder' ), 500 );
			}

			// Parse json.
			$icons = wp_json_file_decode( $icons_file, [ 'associative' => true ] );

			// Query svg images from the media library.
			$media_svg_images = $this->query_svg_images();

			if ( $media_svg_images ) {
				$icons = $media_svg_images + $icons;
			}

			wp_send_json(
				[
					'data'    => $icons,
					'success' => true,
				]
			);
		}

		/**
		 * Query SVG images from the library
		 *
		 * @return array
		 */
		private function query_svg_images() {
			$media_svgs = [];
			$images     = get_posts(
				[
					'post_type'      => 'attachment',
					'post_mime_type' => [ 'image/svg+xml' ],
					'post_status'    => 'any',
					'posts_per_page' => 100,
				]
			);

			if ( $images ) {
				foreach ( $images as $image ) {
					$icon = file_get_contents( get_attached_file( $image->ID ) );
					if ( $icon ) {
						$media_svgs[] = [
							'name'       => $image->post_name,
							'title'      => $image->post_title,
							'icon'       => $icon,
							'categories' => [ 'Media Library' ],
							'provider'   => 'Media Library',
						];
					}
				}
			}

			return $media_svgs;
		}

		/**
		 * Add SVG mine types
		 *
		 * @param array $mimes
		 * @return array
		 */
		public function mime_types_support_svg( $mimes ) {
			$mimes['svg'] = 'image/svg+xml';

			return $mimes;
		}

		/**
		 * Display SVG images
		 *
		 * @return void
		 */
		public function display_svg_thumb() {
			echo '<style>
				.wp-list-table .media-icon img[src$=".svg"], .editor-post-featured-image img[src$=".svg"] {
					width: 100% !important;
					height: auto !important;
				}
			</style>';
		}

		/**
		 * Generate width/height for uploaded SVG images
		 * https://css-tricks.com/snippets/wordpress/allow-svg-through-wordpress-media-uploader/#comment-1606112
		 *
		 * @param array $data metadata for uploaded image
		 * @param int   $id the attachment id
		 * @return void
		 */
		public function update_svg_metadata( $data, $id ) {
			// Filter makes sure that the post is an attachment.
			$attachment = get_post( $id );

			// The attachment mime_type.
			$mime_type = $attachment->post_mime_type;

			// If the attachment is an svg.
			if ( 'image/svg+xml' === $mime_type ) {
				// If the svg metadata are empty or the width is empty or the height is empty, then get the attributes from xml.
				if ( empty( $data ) || empty( $data['width'] ) || empty( $data['height'] ) ) {
					$xml = simplexml_load_file( get_attached_file( $id ) );
					if ( $xml ) {
						$attr          = $xml->attributes();
						$viewbox       = explode( ' ', $attr->viewBox );
						$data['width'] = isset( $attr->width ) && preg_match( '/\d+/', $attr->width, $value ) ? (int) $value[0] : ( count( $viewbox ) == 4 ? (int) $viewbox[2] : null );
						if ( intval( $data['width'] ) === 0 ) {
							$data['width'] = 1;
						}
						$data['height'] = isset( $attr->height ) && preg_match( '/\d+/', $attr->height, $value ) ? (int) $value[0] : ( count( $viewbox ) == 4 ? (int) $viewbox[3] : null );
						if ( intval( $data['height'] ) === 0 ) {
							$data['height'] = 1;
						}
					}
				}
			}

			return $data;
		}
	}
endif;
