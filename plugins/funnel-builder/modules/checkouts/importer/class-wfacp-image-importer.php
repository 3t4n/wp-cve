<?php

/**
 * Image Importer
 */
if ( ! class_exists( 'WFACP_Image_Importer' ) ) {
	#[AllowDynamicProperties]

  class WFACP_Image_Importer {

		/**
		 * Instance
		 *
		 * @var object Class object.
		 * @access private
		 */
		private static $instance;

		/**
		 * Images IDs
		 *
		 * @var array   The Array of already image IDs.
		 */
		private $already_imported_ids = array();

		/**
		 * Constructor
		 *
		 */
		public function __construct() {

			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}

			WP_Filesystem();

			//@todo: Warning! For Development Purpose Only. Delete it when going to Production level
			//remove flag that prevent local WP sites (Same IP, Hosts) to download images from each other
			add_filter( 'http_request_host_is_external', '__return_true' );
		}

		/**
		 * Initiator
		 *
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Process Image Download
		 *
		 * @param array $attachments Attachment array.
		 *
		 * @return array              Attachment array.
		 */
		public function process( $attachments ) {

			$downloaded_images = array();

			foreach ( $attachments as $attachment ) {
				$downloaded_images[] = $this->import( $attachment );
			}

			return $downloaded_images;
		}

		/**
		 * Import Image
		 *
		 * @param array $attachment Attachment array.
		 *
		 * @return array              Attachment array.
		 * @since 1.1.1
		 */
		public function import( $attachment ) {

			$saved_image = $this->get_saved_image( $attachment );

			if ( $saved_image ) {
				return $saved_image;
			}

			$file_content = wp_remote_retrieve_body( wp_safe_remote_get( $attachment['url'], array(
				'timeout'   => '60', //phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
				'sslverify' => false
			) ) ); //phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout


			// Empty file content?
			if ( empty( $file_content ) ) {
				return $attachment;
			}

			// Extract the file name and extension from the URL.
			$filename = basename( $attachment['url'] );

			$upload = wp_upload_bits( $filename, null, $file_content );

			$post = array(
				'post_title' => $filename,
				'guid'       => $upload['url'],
			);

			$info = wp_check_filetype( $upload['file'] );
			if ( $info ) {
				$post['post_mime_type'] = $info['type'];
			} else {
				return $attachment;
			}

			if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';
			}

			$post_id = wp_insert_attachment( $post, $upload['file'] );
			wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );
			update_post_meta( $post_id, '_wffn_image_hash', $this->get_hash_image( $attachment['url'] ) );

			$new_attachment = array(
				'id'  => $post_id,
				'url' => $upload['url'],
			);

			$this->already_imported_ids[ $post_id ] = $new_attachment;

			return $new_attachment;
		}

		/**
		 * Get Saved Image.
		 *
		 * @param string $attachment Attachment Data.
		 *
		 * @return string                 Hash string.
		 */
		private function get_saved_image( $attachment ) {

			global $wpdb;

			if ( isset( $attachment['id'] ) && isset( $this->already_imported_ids[ $attachment['id'] ] ) ) {

				return $this->already_imported_ids[ $attachment['id'] ];
			}

			$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wffn_image_hash' AND meta_value = %s", $this->get_hash_image( $attachment['url'] ) ) ); //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching

			// 2. Is image already imported though XML?
			if ( empty( $post_id ) ) {

				// Get file name without extension.
				// To check it exist in attachment.
				$filename = basename( $attachment['url'] );

				$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s ", '%/' . $filename . '%' ) );  //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			}

			if ( $post_id ) {
				$new_attachment                         = array(
					'id'  => $post_id,
					'url' => wp_get_attachment_url( $post_id ),
				);
				$this->already_imported_ids[ $post_id ] = $new_attachment;

				return $new_attachment;
			}

			return false;
		}

		/**
		 * Get Hash Image.
		 *
		 * @param string $attachment_url Attachment URL.
		 *
		 * @return string                 Hash string.
		 */
		private function get_hash_image( $attachment_url ) {
			return sha1( $attachment_url );
		}

	}
}