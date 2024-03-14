<?php
/**
 * Rest API class.
 *
 * @since      2.0.0
 * @package    FAL
 * @subpackage FAL\RestAPI
 * @author     FAL <support@surror.com>
 */

namespace FAL;

defined( 'ABSPATH' ) || exit;

/**
 * Shortcode class.
 */
class RestAPI {

	/**
	 * The single instance of the class.
	 */
	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_rest_api_endpoint' ] );
	}

	/**
	 * Register REST API endpoint.
	 */
	public function register_rest_api_endpoint() {
		register_rest_route(
			'fml/v1',
			'/download',
			[
				'methods'  => 'POST',
				'callback' => [ $this, 'download_rest_api_endpoint' ],
				'permission_callback' => function() {
					return current_user_can( 'upload_files' );
				},
			]
		);
	}

	/**
	 * Download REST API endpoint.
	 * 
	 * @param  WP_REST_Request $request
	 */
	public function download_rest_api_endpoint( $request ) {
		$data = $request->get_json_params();

		$title = isset( $data['title'] ) ? sanitize_text_field( $data['title'] ) : '';
		$alt = isset( $data['alt'] ) ? sanitize_text_field( $data['alt'] ) : '';
		$description = isset( $data['description'] ) ? wp_kses_post( $data['description'] ) : '';
		$caption = isset( $data['caption'] ) ? wp_kses_post( $data['caption'] ) : '';
		$image_name = isset( $data['imageName'] ) ? sanitize_text_field( $data['imageName'] ) : '';
		$image_extension = isset( $data['imageExtension'] ) ? sanitize_text_field( $data['imageExtension'] ) : '';
		$image_url = isset( $data['imageUrl'] ) ? sanitize_text_field( $data['imageUrl'] ) : '';
		$source_id = isset( $data['sourceId'] ) ? sanitize_text_field( $data['sourceId'] ) : '';

		if( empty( $title ) || empty( $image_name ) || empty( $image_extension ) || empty( $image_url ) ) {
			return new \WP_Error( 'missing_data', 'Missing data', array( 'status' => 400 ) );
		}

		// $filename should be the path to a file in the upload directory.
		$file = $this->download_file( $image_url, $image_name );
		if ( false === $file['success'] ) {
			return rest_ensure_response(
				[
					'success' => false,
					'data'    => $file['data'],
				]
			);
		}

		$file_abs_url = $file['data']['file'];
		$file_url     = $file['data']['file'];
		$file_type    = $file['data']['type'];

		// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();

		// Prepare an array of post data for the attachment.
		$attachment = [
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $file_abs_url ),
			'post_mime_type' => $file_type,
			'post_title'     => $title,
			'post_status'    => 'inherit',
			'post_excerpt'   => $caption,
			'post_content'   => $description,
			'meta_input'     => [
				'fal_source_id' => $source_id,
				'_wp_attachment_image_alt' => $alt,
			],
		];

		// Insert the attachment.
		$attach_id = wp_insert_attachment( $attachment, $file_abs_url );

		// Include image.php.
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Define attachment metadata.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file_abs_url );

		// Assign metadata to attachment.
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return rest_ensure_response( [
			'success' => true,
			'data'    => $attach_data,
			'meta' => wp_prepare_attachment_for_js( $attach_id ),
		] );
	}

	/**
	 * Download File Into Uploads Directory
	 *
	 * @since 1.0.0
	 *
	 * @param  string $file Download File URL.
	 * @param  string $image_name Image name.
	 * @return array        Downloaded file data.
	 */
	private function download_file( $file = '', $image_name = '' ) {

		// Gives us access to the download_url() and wp_handle_sideload() functions.
		require_once ABSPATH . 'wp-admin/includes/file.php';

		$timeout_seconds = 5;

		// Download file to temp dir.
		$temp_file = download_url( $file, $timeout_seconds );

		// WP Error.
		if ( is_wp_error( $temp_file ) ) {
			return [
				'success' => false,
				'data'    => $temp_file->get_error_message(),
			];
		}

		// Array based on $_FILE as seen in PHP file uploads.
		if ( ! empty( $image_name ) ) {
			$file       = wp_parse_url( $file );
			$file_path  = $file['path'];
			$image_name = $image_name . '.' . pathinfo( $file_path, PATHINFO_EXTENSION );
		} else {
			$image_name = basename( $file );
		}
		$file_args = [
			'name'     => $image_name,
			'tmp_name' => $temp_file,
			'error'    => 0,
			'size'     => filesize( $temp_file ),
		];

		$overrides = [

			// Tells WordPress to not look for the POST form
			// fields that would normally be present as
			// we downloaded the file from a remote server, so there
			// will be no form fields
			// Default is true.
			'test_form'   => false,

			// Setting this to false lets WordPress allow empty files, not recommended.
			// Default is true.
			'test_size'   => true,

			// A properly uploaded file will pass this test. There should be no reason to override this one.
			'test_upload' => true,

		];

		// Move the temporary file into the uploads directory.
		$results = wp_handle_sideload( $file_args, $overrides );

		if ( isset( $results['error'] ) ) {
			return [
				'success' => false,
				'data'    => $results,
			];
		}

		// Success!
		return [
			'success' => true,
			'data'    => $results,
		];
	}
}
