<?php

/**
 * ScormController Class.
 *
 * Handles the migration of data from other WordPress LMS plugins to Masteriyo.
 *
 * @since 1.8.3
 * @package Masteriyo\Addons\Scorm\Controllers
 */

namespace Masteriyo\Addons\Scorm\Controllers;

use Masteriyo\Enums\CourseProgressStatus;
use Masteriyo\Helper\Permission;
use Masteriyo\PostType\PostType;
use Masteriyo\Query\CourseProgressQuery;
use Masteriyo\RestApi\Controllers\Version1\RestController;
use WP_Error;
use ZipArchive;

defined( 'ABSPATH' ) || exit;

/**
 * ScormController class.
 *
 * This class provides REST endpoints for migrating data from other LMS plugins to Masteriyo.
 */
class ScormController extends RestController {

	/**
	 * Endpoint namespace.
	 *
	 * @since 1.8.3
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 1.8.3
	 *
	 * @var string
	 */
	protected $rest_base = 'scorm';

	/**
	 * Permission class.
	 *
	 * @since 1.8.3
	 *
	 * @var \Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.8.3
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.8.3
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/import',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'import' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/delete/(?P<id>[\d]+)',
			array(
				'args' => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => \WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete' ),
					'permission_callback' => array( $this, 'delete_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::DELETABLE ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/course_progress/(?P<course_id>[\d-]+)/',
			array(
				'args' => array(
					'course_id' => array(
						'description' => __( 'Unique identifier for the resource.', 'masteriyo' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => 'GET',
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
					'callback'            => array( $this, 'get_course_progress' ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/course_progress/(?P<course_id>[\d-]+)/',
			array(
				'methods'             => 'POST',
				'permission_callback' => array( $this, 'update_item_permissions_check' ),
				'callback'            => array( $this, 'update_course_progress' ),
			)
		);
	}

	/**
	 * Check if a given request has access to read item.
	 *
	 * @since 1.8.3
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		if ( masteriyo_is_current_user_admin() || masteriyo_is_current_user_manager() ) {
			return true;
		}

		$user_course = masteriyo_get_user_course_by_user_and_course( get_current_user_id(), absint( $request['course_id'] ) );

		if ( ! $user_course ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_get',
				__( 'Sorry, you are not allowed to get resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to create/update an item.
	 *
	 * @since 1.8.3
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function update_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		$user_course = masteriyo_get_user_course_by_user_and_course( get_current_user_id(), absint( $request['course_id'] ) );

		if ( ! $user_course ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_update',
				__( 'Sorry, you are not allowed to update resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Get the progress of the course.
	 *
	 * @since 1.8.3
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 *
	 * @return array|boolean
	 */
	public function get_course_progress( $request ) {
		$user_id = get_current_user_id();

		if ( 1 > $user_id ) {
			return false;
		}

		global $wpdb;

		$table       = "{$wpdb->prefix}masteriyo_user_scorm_course";
		$user_course = masteriyo_get_user_course_by_user_and_course( get_current_user_id(), absint( $request['course_id'] ) );

		if ( ! $user_course ) {
			return false;
		}

		$results = $wpdb->get_results(
			$wpdb->prepare( 'SELECT * FROM ' . $table . ' WHERE user_course_id=%d', array( $user_course->get_id() ) ), // phpcs:ignore
			ARRAY_A
		);

		if ( empty( $results ) ) {
			return false;
		}

		$parameters = array();

		foreach ( $results as $row ) {
			$parameters[ $row['parameter'] ] = $row['value'];
		}

		return $parameters;
	}

	/**
	 * Update the progress of the course.
	 *
	 * @since 1.8.3
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 *
	 * @return array|boolean
	 */
	public function update_course_progress( $request ) {
		$user_id   = get_current_user_id();
		$course_id = absint( $request['course_id'] );

		if ( 1 > $user_id ) {
			return false;
		}

		global $wpdb;

		$table = "{$wpdb->prefix}masteriyo_user_scorm_course";

		$user_course = masteriyo_get_user_course_by_user_and_course( get_current_user_id(), $course_id );

		if ( ! $user_course ) {
			return false;
		}

		$query = new CourseProgressQuery(
			array(
				'course_id' => $course_id,
				'user_id'   => $user_id,
				'status'    => array( CourseProgressStatus::COMPLETED ),
			)
		);

		$activity = current( $query->get_course_progress() );

		if ( $activity ) { // If user enrolled and completed course then return.
			return false;
		}

		$scorm_data = $request->get_json_params();
		$scorm_url  = $request->get_url_params();

		if ( isset( $scorm_url['course_id'] ) && $scorm_url['course_id'] > 0 ) {
			$last_progress_time = get_user_meta( $user_id, 'last_progress_time', true );
			$last_progress_time = empty( $last_progress_time ) ? array() : $last_progress_time;

			$last_progress_time[ $scorm_url['course_id'] ] = time();
			update_user_meta( $user_id, 'last_progress_time', $last_progress_time );
		}

		if ( ! empty( $scorm_data ) ) {
			$wpdb->query(
				$wpdb->prepare( 'DELETE FROM ' . $table . ' WHERE user_course_id=%d', array( $user_course->get_id() ) ) // phpcs:ignore
			);

			$is_progress_updated = false;
			foreach ( $scorm_data as $parameter => $value ) {

				$wpdb->query(
					$wpdb->prepare(
						'INSERT INTO ' . $table . ' SET user_course_id = %d, parameter = %s, value = %s ON DUPLICATE KEY UPDATE value = %s', // phpcs:ignore
						array(
							$user_course->get_id(),
							$parameter,
							$value,
							$value,
						)
					)
				);

				if ( false === $is_progress_updated && ( 'cmi.core.score.raw' === $parameter || 'cmi.score.raw' === $parameter ) ) {
					masteriyo_update_user_scorm_course_progress( $course_id, $user_id, absint( $value ) );
					$is_progress_updated = true;
				} elseif ( false === $is_progress_updated && ( 'cmi.core.lesson_status' === $parameter || 'cmi.lesson_status' === $parameter ) ) {

					$activity_status = 'incomplete' === $value ? CourseProgressStatus::STARTED : CourseProgressStatus::COMPLETED;

					masteriyo_update_user_scorm_course_progress( $course_id, $user_id, $activity_status );
					$is_progress_updated = CourseProgressStatus::COMPLETED === $activity_status ? true : false;
				}
			}
		}

		return false;
	}

	/**
	 * Check if a given request has access to delete an item.
	 *
	 * @since 1.8.3
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 * @return \WP_Error|boolean
	 */
	public function delete_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		$instructor = masteriyo_get_current_instructor();
		if ( $instructor && ! $instructor->is_active() ) {
			return new \WP_Error(
				'masteriyo_rest_user_not_approved',
				__( 'Sorry, you are not approved by the manager.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		$post = get_post( (int) $request['id'] );

		if ( $post && ! $this->permission->rest_check_post_permissions( PostType::COURSE, 'delete', $post->ID ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_delete',
				__( 'Sorry, you are not allowed to delete resources.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Checks if the user has permission to import items.
	 *
	 * @since 1.8.3
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 * @return \WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		$instructor = masteriyo_get_current_instructor();
		if ( $instructor && ! $instructor->is_active() ) {
			return new \WP_Error(
				'masteriyo_rest_user_not_approved',
				__( 'Sorry, you are not approved by the manager.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! $this->permission->rest_check_post_permissions( PostType::COURSE, 'create' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to import courses.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Delete the SCORM course.
	 *
	 * @since 1.8.3
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response The response or WP_Error on failure.
	 */
	public function delete( $request ) {
		$course_id = absint( $request['id'] ?? 0 );

		$course = masteriyo_get_course( $course_id );

		if ( ! $course ) {
			return new \WP_Error(
				'invalid_course_id',
				__( 'Invalid course id.', 'masteriyo' )
			);
		}

		$scorm_package = masteriyo_get_scorm_meta( $course_id, true );

		if ( ! $scorm_package ) {
			return new \WP_Error(
				'invalid_scorm_package',
				__( 'Invalid SCORM package.', 'masteriyo' )
			);
		}

		$path = $scorm_package['path'];

		if ( file_exists( $path ) ) {
			$delete_result = masteriyo_scorm_directory_delete( $path );

			if ( is_wp_error( $delete_result ) ) {
				return $delete_result;
			}
		}

		delete_post_meta( $course_id, '_scorm_package' );

		return rest_ensure_response(
			array(
				'message' => __( 'Deleted successfully.', 'masteriyo' ),
			)
		);
	}

	/**
	 * Handles the migration of LMS data based on the request.
	 *
	 * @since 1.8.3
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_Error|WP_REST_Response The response or WP_Error on failure.
	 */
	public function import( $request ) {
		$files     = $request->get_file_params();
		$course_id = absint( $request->get_param( 'course_id' ) ?? 0 );

		$course = masteriyo_get_course( $course_id );

		if ( ! $course ) {
			return new \WP_Error(
				'invalid_course_id',
				__( 'Invalid course id.', 'masteriyo' )
			);
		}

		$file = $this->get_import_file( $files );

		if ( is_wp_error( $file ) ) {
			return $file;
		}

		if ( ! class_exists( 'ZipArchive' ) ) {
			return new \WP_Error(
				'missing_zip_package',
				__( 'Zip Extraction not supported.', 'masteriyo' )
			);
		}

		$original_file_name = $files['file']['name'];
		$original_file_name = pathinfo( $original_file_name, PATHINFO_FILENAME );

		$unzipped = $this->unzip_scorm_file( $course_id, $file, $original_file_name );

		if ( is_wp_error( $unzipped ) ) {
			return $unzipped;
		}

		return rest_ensure_response(
			array(
				'message' => __( 'Imported successfully.', 'masteriyo' ),
				'data'    => $unzipped,
			)
		);
	}

	/**
	 * Parse Import file.
	 *
	 * @since 1.8.3
	 *
	 * @param array $files $_FILES array for a given file.
	 *
	 * @return string|\WP_Error File path on success and WP_Error on failure.
	 */
	protected function get_import_file( $files ) {
		if ( ! isset( $files['file']['tmp_name'] ) ) {
			return new \WP_Error(
				'rest_upload_no_data',
				__( 'No data supplied.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		if (
			! isset( $files['file']['name'] ) ||
			'zip' !== pathinfo( $files['file']['name'], PATHINFO_EXTENSION )
		) {
			return new \WP_Error(
				'invalid_file_ext',
				__( 'Invalid file type for import.', 'masteriyo' ),
				array( 'status' => 400 )
			);
		}

		return $files['file']['tmp_name'];
	}

	/**
	 * Unzips the SCORM file.
	 *
	 * @since 1.8.3
	 *
	 * @param int $course_id Course ID.
	 * @param string $file Path to the SCORM zip file.
	 * @param string $original_file_name The original file name.
	 *
	 * @return array|WP_Error Unzipped data or WP_Error on failure.
	 */
	public static function unzip_scorm_file( $course_id, $file, $original_file_name ) {
		$zip = new ZipArchive();
		$res = $zip->open( $file );

		if ( true !== $res ) {
			return new \WP_Error(
				'failed_unzip',
				__( 'Could not extract zip contents. Try another SCORM package', 'masteriyo' )
			);
		}

		$scorm_dir_name = time() . wp_rand( 1, 9999 );
		$upload_dir     = masteriyo_scorm_upload_dir() . '/' . $scorm_dir_name;
		$upload_url     = masteriyo_scorm_upload_url();
		$filename       = sanitize_file_name( $original_file_name );
		$archive_path   = "{$upload_dir}/{$filename}";

		$allowed = array(
			'',
			'css',
			'js',
			'woff',
			'ttf',
			'otf',
			'jpg',
			'jpeg',
			'png',
			'gif',
			'html',
			'json',
			'xml',
			'pdf',
			'mp3',
			'mp4',
			'xsd',
			'dtd',
			'ico',
			'swf',
		);

		/**
		 * Filters the allowed SCORM file extensions.
		 *
		 * @param array $allowed_extensions Allowed SCORM file extensions.
		 *
		 * @since 1.8.3
		 */
		$allowed_extensions = apply_filters( 'masteriyo_scorm_allowed_files_ext', $allowed );

		$manifest_exists = false;
		$macosx          = array();

		for ( $i = 0; $i < $zip->numFiles; $i++ ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$r_file    = $zip->statIndex( $i );
			$item_name = $r_file['name'];
			$item_ext  = strtolower( pathinfo( $item_name, PATHINFO_EXTENSION ) );

			if ( substr( $item_name, 0, 9 ) === '__MACOSX/' ) {
				$macosx[] = $item_name;
				continue;
			}

			if ( 'imsmanifest.xml' === $item_name ) {
				$manifest_exists = true;
			}

			if ( ! in_array( $item_ext, $allowed_extensions, true ) ) {
				$zip->close();
				unlink( $file );
				return new WP_Error(
					'unacceptable_files',
					__( 'Unacceptable files in package', 'masteriyo' )
				);
			}
		}

		if ( ! $manifest_exists ) {
			$zip->close();
			unlink( $file );
			return new WP_Error(
				'missing_manifest',
				__( 'SCORM Package should contain file imsmanifest.xml', 'masteriyo' )
			);
		}

		if ( ! $zip->extractTo( $archive_path ) ) {
			$zip->close();
			unlink( $file );
			return new WP_Error(
				'failed_unzip',
				__( 'Failed to extract zip contents', 'masteriyo' )
			);
		}

		$zip->close();
		unlink( $file );

		// Remove MACOSX Files.
		if ( ! empty( $macosx ) ) {
			foreach ( $macosx as $macosx_folder ) {
				$delete_result = masteriyo_scorm_directory_delete( "{$archive_path}/{$macosx_folder}" );
				if ( is_wp_error( $delete_result ) ) {
					return $delete_result;
				}
			}
		}

		$scorm_package['path'] = "{$upload_dir}/{$filename}";
		$scorm_package['url']  = "{$upload_url}/{$filename}";

		$scorm_version = masteriyo_get_manifest_scorm_version( $scorm_package );

		$scorm_package['scorm_version'] = $scorm_version;
		$scorm_package['file_name']     = $filename;

		update_post_meta(
			$course_id,
			'_scorm_package',
			wp_json_encode(
				array(
					'scorm_version'  => $scorm_version,
					'file_name'      => $filename,
					'scorm_dir_name' => $scorm_dir_name,
				)
			)
		);

		return $scorm_package;
	}
}
