<?php

/**
 * Divi Importer
 *
 * @since 1.0.0
 */


if ( ! class_exists( 'WFFN_Divi_Importer' ) ) {
	class WFFN_Divi_Importer implements WFFN_Import_Export {

		public function __construct() {
			add_action( 'wffn_design_saved', array( $this, 'set_builder' ), 10, 2 );
			add_action( 'woofunnels_module_template_removed', array( $this, 'delete_divi_data' ) );
			//Dont Need To call Parent Constructor because of some time other divi addon created fatal error Like Monarch Plugin.
		}

		protected function set_filesystem() {
			global $wp_filesystem;

			add_filter( 'filesystem_method', array( $this, 'replace_filesystem_method' ) );
			WP_Filesystem();

			return $wp_filesystem;
		}

		public function replace_filesystem_method() {
			return 'direct';
		}

		/**
		 * Proxy method for set_filesystem() to avoid calling it multiple times.
		 *
		 * @return WP_Filesystem_Direct
		 * @since 4.0
		 *
		 */
		protected function get_filesystem() {
			static $filesystem = null;

			if ( null === $filesystem ) {
				$filesystem = $this->set_filesystem();
			}

			return $filesystem;
		}

		/**
		 * Get timestamp or create one if it isn't set.
		 *
		 * @since 2.7.0
		 */
		public function get_timestamp() {
			et_core_nonce_verified_previously();

			return isset( $_POST['timestamp'] ) && ! empty( $_POST['timestamp'] ) ? sanitize_text_field( $_POST['timestamp'] ) : current_time( 'timestamp' ); //phpcs:ignore
		}

		public function import( $module_id, $export_content = '' ) {
			$status = $this->import_template_single( $module_id, $export_content );

			return $status;
		}

		public function import_template_single( $post_id, $content ) {
			wp_update_post( [ 'ID' => $post_id, 'post_content' => '' ] );

			delete_post_meta( $post_id, '_elementor_edit_mode' );
			delete_post_meta( $post_id, '_fl_builder_enabled' );
			update_post_meta( $post_id, '_et_pb_use_builder', 'on' );

			if ( ! is_array( $content ) && is_string( $content ) ) {
				try {
					$content = json_decode( $content, true );
				} catch ( Exception $error ) {
					return false;
				}
			}


			$data = $content['data'];
			// Pass the post content and let js save the post.

			$data    = reset( $data );
			$success = true;
			$result  = wp_update_post( [ 'ID' => $post_id, 'post_content' => $data ] );

			if ( $result instanceof WP_Error ) {
				$success = false;
			}

			return $success;
		}

		public function set_builder( $post_id, $selected_type ) {
			if ( 'divi' === $selected_type ) {
				update_post_meta( $post_id, '_et_pb_use_builder', 'on' );
			}

		}

		public function maybe_paginate_images( $images, $method, $timestamp ) {

			if ( ! function_exists( 'et_core_portability_load' ) ) {
				return $images;
			}
			et_core_nonce_verified_previously();

			$page   = isset( $_POST['page'] ) ? (int) $_POST['page'] : 1; //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$result = $this->chunk_images( $images, $method, $timestamp, max( $page - 1, 0 ) );

			if ( ! $result['ready'] ) {
				wp_send_json( array(
					'page'        => $page,
					'total_pages' => $result['chunks'],
					'timestamp'   => $timestamp,
				) );
			}

			return $result['images'];
		}

		/**
		 * Serialize images in chunks.
		 *
		 * @param array $images
		 * @param string $method Method applied on images.
		 * @param string $id Unique ID to use for temporary files.
		 * @param integer $chunk
		 *
		 * @return array
		 * @since 4.0
		 *
		 */
		protected function chunk_images( $images, $method, $id, $chunk = 0 ) {
			$images_per_chunk = 100;
			$chunks           = 1;

			/**
			 * Filters whether or not images in the file being imported should be paginated.
			 *
			 * @param bool $paginate_images Default `true`.
			 *
			 * @since 3.0.99
			 *
			 */
			$paginate_images = apply_filters( 'et_core_portability_paginate_images', true );
			$et_obj          = et_core_portability_load( 'et_builder' );

			if ( $paginate_images && count( $images ) > $images_per_chunk ) {
				$chunks       = ceil( count( $images ) / $images_per_chunk );
				$slice        = $images_per_chunk * $chunk;
				$images       = array_slice( $images, $slice, $images_per_chunk );
				$images       = $et_obj->$method( $images );
				$filesystem   = $this->get_filesystem();
				$temp_file_id = sanitize_file_name( "images_{$id}" );
				$temp_file    = $et_obj->temp_file( $temp_file_id, 'et_core_export' );
				$temp_images  = json_decode( $filesystem->get_contents( $temp_file ), true );

				if ( is_array( $temp_images ) ) {
					$images = array_merge( $temp_images, $images );
				}

				if ( $chunk + 1 < $chunks ) {
					$filesystem->put_contents( $temp_file, wp_json_encode( (array) $images ) );
				} else {
					$et_obj->delete_temp_files( 'et_core_export', array( $temp_file_id => $temp_file ) );
				}
			} else {
				$images = $this->$method( $images );
			}

			return array(
				'ready'  => $chunk + 1 >= $chunks,
				'chunks' => $chunks,
				'images' => $images,
			);
		}

		/**
		 * Decode base64 formatted image and upload it to WP media.
		 *
		 * @param array $images Array of encoded images which needs to be uploaded.
		 *
		 * @return array
		 * @since 2.7.0
		 *
		 */
		protected function upload_images( $images ) {
			$filesystem = $this->set_filesystem();

			foreach ( $images as $key => $image ) {
				$basename    = sanitize_file_name( wp_basename( $image['url'] ) );
				$attachments = get_posts( array( //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_posts
					'posts_per_page' => - 1,
					'post_type'      => 'attachment',
					'meta_key'       => '_wp_attached_file',
					'meta_value'     => pathinfo( $basename, PATHINFO_FILENAME ),
					'meta_compare'   => 'LIKE',
				) );
				$id          = 0;
				$url         = '';

				// Avoid duplicates.
				if ( ! is_wp_error( $attachments ) && ! empty( $attachments ) ) {
					foreach ( $attachments as $attachment ) {
						$attachment_url = wp_get_attachment_url( $attachment->ID );
						$file           = get_attached_file( $attachment->ID );
						$filename       = sanitize_file_name( wp_basename( $file ) );

						// Use existing image only if the content matches.
						if ( $filesystem->get_contents( $file ) === base64_decode( $image['encoded'] ) ) {
							$id  = isset( $image['id'] ) ? $attachment->ID : 0;
							$url = $attachment_url;

							break;
						}
					}
				}

				// Create new image.
				if ( empty( $url ) ) {
					$temp_file = wp_tempnam();
					$filesystem->put_contents( $temp_file, base64_decode( $image['encoded'] ) );
					$filetype = wp_check_filetype_and_ext( $temp_file, $basename );

					// Avoid further duplicates if the proper_file name match an existing image.
					if ( isset( $filetype['proper_filename'] ) && $filetype['proper_filename'] !== $basename ) {
						if ( isset( $filename ) && $filename === $filetype['proper_filename'] ) {
							// Use existing image only if the basename and content match.
							if ( $filesystem->get_contents( $file ) === $filesystem->get_contents( $temp_file ) ) {
								$filesystem->delete( $temp_file );
								continue;
							}
						}
					}

					$file   = array(
						'name'     => $basename,
						'tmp_name' => $temp_file,
					);
					$upload = media_handle_sideload( $file, 0 );

					if ( ! is_wp_error( $upload ) ) {
						// Set the replacement as an id if the original image was set as an id (for gallery).
						$id  = isset( $image['id'] ) ? $upload : 0;
						$url = wp_get_attachment_url( $upload );
					} else {
						// Make sure the temporary file is removed if media_handle_sideload didn't take care of it.
						$filesystem->delete( $temp_file );
					}
				}

				// Only declare the replace if a url is set.
				if ( $id > 0 ) {
					$images[ $key ]['replacement_id'] = $id;
				}

				if ( ! empty( $url ) ) {
					$images[ $key ]['replacement_url'] = $url;
				}

				unset( $url );
			}

			return $images;
		}

		/**
		 * Replace image urls with newly uploaded images.
		 *
		 * @param array $images Array of new images uploaded.
		 * @param array $data Array of for which images url needs to be replaced.
		 *
		 * @return array|mixed|object
		 * @since 2.7.0
		 *
		 */
		protected function replace_images_urls( $images, $data ) {
			foreach ( $data as $post_id => &$post_data ) {
				foreach ( $images as $image ) {
					if ( is_array( $post_data ) ) {
						foreach ( $post_data as $post_param => &$param_value ) {
							if ( ! is_array( $param_value ) ) {
								$data[ $post_id ][ $post_param ] = $this->replace_image_url( $param_value, $image );
							}
						}
						unset( $param_value );
					} else {
						$data[ $post_id ] = $this->replace_image_url( $post_data, $image );
					}
				}
			}
			unset( $post_data );

			return $data;
		}

		/**
		 * Replace encoded image url with a real url
		 *
		 * @param $subject - The string to perform replacing for
		 * @param array $image - The image settings
		 *
		 * @return string|string[]|null
		 */
		protected function replace_image_url( $subject, $image ) {
			if ( isset( $image['replacement_id'] ) && isset( $image['id'] ) ) {
				$search      = $image['id'];
				$replacement = $image['replacement_id'];
				$subject     = preg_replace( "/(gallery_ids=.*){$search}(.*\")/", "\${1}{$replacement}\${2}", $subject );
			}

			if ( isset( $image['url'] ) && isset( $image['replacement_url'] ) && $image['url'] !== $image['replacement_url'] ) {
				$search      = $image['url'];
				$replacement = $image['replacement_url'];
				$subject     = str_replace( $search, $replacement, $subject );
			}

			return $subject;
		}

		public function export( $module_id, $slug ) { //phpcs:ignore
			$post = get_post( $module_id );

			return $post->post_content;
		}

		public function delete_divi_data( $post_id ) {
			wp_update_post( [ 'ID' => $post_id, 'post_content' => '' ] );
			delete_post_meta( $post_id, 'et_enqueued_post_fonts' );
		}

	}

	if ( class_exists( 'WFFN_Template_Importer' ) ) {
		WFFN_Template_Importer::register( 'divi', new WFFN_Divi_Importer() );
	}
}