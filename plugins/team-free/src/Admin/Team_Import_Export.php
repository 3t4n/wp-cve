<?php
/**
 * The plugin export import page.
 *
 * @link       https://shapedplugin.com/
 * @since      3.2.4
 *
 * @package    WP_Team_free
 * @subpackage WP_Team_free/Admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Team_Import_Export' ) ) {

	/**
	 * Custom import export.
	 */
	class Team_Import_Export {

		/**
		 * Export
		 *
		 * @param  mixed $shortcode_ids Export member and shortcode ids.
		 * @return object
		 */
		public function export( $shortcode_ids ) {
			$export = array();
			if ( ! empty( $shortcode_ids ) ) {
				$post_type  = 'all_members' === $shortcode_ids ? 'sptp_member' : 'sptp_generator';
				$post_in    = 'all_members' === $shortcode_ids || 'all_shortcodes' === $shortcode_ids ? '' : $shortcode_ids;
				$args       = array(
					'post_type'        => $post_type,
					'post_status'      => array( 'inherit', 'publish' ),
					'orderby'          => 'modified',
					'suppress_filters' => 1, // wpml, ignore language filter.
					'posts_per_page'   => -1,
					'post__in'         => $post_in,
				);
				$shortcodes = get_posts( $args );
				if ( ! empty( $shortcodes ) ) {
					foreach ( $shortcodes as $shortcode ) {
						if ( 'all_members' !== $shortcode_ids ) {
							$shortcode_export = array(
								'title'       => $shortcode->post_title,
								'original_id' => $shortcode->ID,
								'meta'        => array(),
							);
						}
						if ( 'all_members' === $shortcode_ids ) {
							$terms            = get_the_terms( $shortcode->ID, 'sptp_group' );
							$shortcode_export = array(
								'title'       => $shortcode->post_title,
								'original_id' => $shortcode->ID,
								'content'     => $shortcode->post_content,
								'image'       => get_the_post_thumbnail_url( $shortcode->ID, 'single-post-thumbnail' ),
								'all_members' => 'all_members',
								'meta'        => array(),
							);
						}

						foreach ( get_post_meta( $shortcode->ID ) as $metakey => $value ) {
							$shortcode_export['meta'][ $metakey ] = $value[0];
						}
						$export['shortcode'][] = $shortcode_export;
						unset( $shortcode_export );
					}
					$export['metadata'] = array(
						'version' => SPT_PLUGIN_VERSION,
						'date'    => gmdate( 'Y/m/d' ),
					);
				}
				return $export;
			}
		}

		/**
		 * Export Team by ajax.
		 *
		 * @return void
		 */
		public function export_shortcodes() {
			$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce, 'spf_options_nonce' ) ) {
				die();
			}
			$shortcode_ids = isset( $_POST['sptp_ids'] ) ? $_POST['sptp_ids'] : ''; // phpcs:ignore

			$export = $this->export( $shortcode_ids );

			if ( is_wp_error( $export ) ) {
				wp_send_json_error(
					array(
						'message' => $export->get_error_message(),
					),
					400
				);
			}

			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				// @codingStandardsIgnoreLine
				echo wp_json_encode($export, JSON_PRETTY_PRINT);
				die;
			}

			wp_send_json( $export, 200 );
		}

		/**
		 * Get page by title
		 *
		 * @param string $page_title Page title.
		 * @param string $output Optional.
		 * @param string $post_type Post type.
		 * @return obj.
		 */
		public function sp_team_get_page_by_title( $page_title, $output = OBJECT, $post_type = 'page' ) {
			global $wpdb;
			$sql  = $wpdb->prepare(
				"
			SELECT ID
			FROM $wpdb->posts
			WHERE post_title = %s
			AND post_type = %s
		",
				$page_title,
				$post_type
			);
			$page = $wpdb->get_var( $sql );
			if ( $page ) {
				return get_post( $page, $output );
			}
			return null;
		}

		/**
		 * Insert an attachment from an URL address.
		 *
		 * @param  String $url remote url.
		 * @param  Int    $parent_post_id parent post id.
		 * @return Int    Attachment ID
		 */
		public function insert_attachment_from_url( $url, $parent_post_id = null ) {

			if ( ! class_exists( 'WP_Http' ) ) {
				include_once ABSPATH . WPINC . '/class-http.php';
			}
			$attachment_title = sanitize_file_name( pathinfo( $url, PATHINFO_FILENAME ) );
			// Does the attachment already exist ?
			if ( post_exists( $attachment_title, '', '', 'attachment' ) ) {
				$attachment = $this->sp_team_get_page_by_title( $attachment_title, OBJECT, 'attachment' );

				if ( ! empty( $attachment ) ) {
					$attachment_id = $attachment->ID;
					return $attachment_id;
				}
			}
			$http     = new \WP_Http();
			$response = $http->request( $url );
			if ( is_wp_error( $response ) || 200 !== $response['response']['code'] ) {
				return false;
			}
			$upload = wp_upload_bits( basename( $url ), null, $response['body'] );
			if ( ! empty( $upload['error'] ) ) {
				return false;
			}

			$file_path     = $upload['file'];
			$file_name     = basename( $file_path );
			$file_type     = wp_check_filetype( $file_name, null );
			$wp_upload_dir = wp_upload_dir();

			$post_info = array(
				'guid'           => $wp_upload_dir['url'] . '/' . $file_name,
				'post_mime_type' => $file_type['type'],
				'post_title'     => $attachment_title,
				'post_content'   => '',
				'post_status'    => 'inherit',
			);

			// Create the attachment.
			$attach_id = wp_insert_attachment( $post_info, $file_path, $parent_post_id );

			// Include image.php.
			require_once ABSPATH . 'wp-admin/includes/image.php';

			// Define attachment metadata.
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

			// Assign metadata to attachment.
			wp_update_attachment_metadata( $attach_id, $attach_data );

			return $attach_id;

		}

		/**
		 * Import logo ans shortcode.
		 *
		 * @param  array $shortcodes Import team-free shortcode array.
		 * @throws \Exception Get errors message.
		 * @return object
		 */
		public function import( $shortcodes ) {
			$errors = array();

			$sptp_post_type = 'sptp_member';
			foreach ( $shortcodes as $index => $shortcode ) {
				$errors[ $index ] = array();
				$new_shortcode_id = 0;
				$sptp_post_type   = isset( $shortcode['all_members'] ) ? 'sptp_member' : 'sptp_generator';
				try {
					$new_shortcode_id = wp_insert_post(
						array(
							'post_title'   => isset( $shortcode['title'] ) ? $shortcode['title'] : '',
							'post_content' => isset( $shortcode['content'] ) ? $shortcode['content'] : '',
							'post_status'  => 'publish',
							'post_type'    => $sptp_post_type,
						),
						true
					);
					if ( isset( $shortcode['all_members'] ) ) {
						$url = isset( $shortcode['image'] ) && ! empty( $shortcode['image'] ) ? $shortcode['image'] : '';
						// Insert attachment id.
						$thumb_id = $this->insert_attachment_from_url( $url, $new_shortcode_id );

						if ( $thumb_id ) {
							$shortcode['meta']['_thumbnail_id'] = $thumb_id;
						}
					}
					if ( is_wp_error( $new_shortcode_id ) ) {
						throw new \Exception( $new_shortcode_id->get_error_message() );
					}

					if ( isset( $shortcode['meta'] ) && is_array( $shortcode['meta'] ) ) {
						foreach ( $shortcode['meta'] as $key => $value ) {
							update_post_meta(
								$new_shortcode_id,
								$key,
								maybe_unserialize( str_replace( '{#ID#}', $new_shortcode_id, $value ) )
							);
						}
					}
				} catch ( \Exception $e ) {
					array_push( $errors[ $index ], $e->getMessage() );

					// If there was a failure somewhere, clean up.
					wp_trash_post( $new_shortcode_id );
				}

				// If no errors, remove the index.
				if ( ! count( $errors[ $index ] ) ) {
					unset( $errors[ $index ] );
				}

				// External modules manipulate data here.
				do_action( 'sp_wp_team_shortcode_imported', $new_shortcode_id );
			}

			$errors = reset( $errors );
			return isset( $errors[0] ) ? new \WP_Error( 'import_shortcode_error', $errors[0] ) : $sptp_post_type;
		}

		/**
		 * Import Accordions by ajax.
		 *
		 * @return void
		 */
		public function import_shortcodes() {
			$nonce = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce, 'spf_options_nonce' ) ) {
				die();
			}
			$data = isset( $_POST['shortcode'] ) ? wp_kses_data( wp_unslash( $_POST['shortcode'] ) ) : '';

			$data       = json_decode( $data );
			$data       = json_decode( $data, true );
			$shortcodes = $data['shortcode'];
			if ( ! $data ) {
				wp_send_json_error(
					array(
						'message' => __( 'Nothing to import.', 'team-free' ),
					),
					400
				);
			}

			$status = $this->import( $shortcodes );

			if ( is_wp_error( $status ) ) {
				wp_send_json_error(
					array(
						'message' => $status->get_error_message(),
					),
					400
				);
			}

			wp_send_json_success( $status, 200 );
		}
	}
}
