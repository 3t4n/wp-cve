<?php

/**
 * Helper function of SCORM Integration.
 *
 * @since 1.8.3
 */

use Masteriyo\Enums\CourseProgressStatus;
use Masteriyo\Query\CourseProgressQuery;

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'masteriyo_get_scorm_meta' ) ) {

	/**
	 * Get scorm meta.
	 *
	 * @since 1.8.3
	 *
	 * @param int|\Masteriyo\Models\Course $course Course id or Course Model.
	 * @param bool $for_delete Whether to get for delete.
	 *
	 * @return null|array  Null or array of scorm meta.
	 */
	function masteriyo_get_scorm_meta( $course, $for_delete = false ) {
		$course = masteriyo_get_course( $course );

		if ( ! $course ) {
			return null;
		}

		$scorm_package_meta = get_post_meta( $course->get_id(), '_scorm_package', true );

		if ( ! $scorm_package_meta ) {
			return null;
		}

		$scorm_package = json_decode( $scorm_package_meta, true );

		if ( empty( $scorm_package ) || ! isset( $scorm_package['file_name'] ) || ! isset( $scorm_package['scorm_dir_name'] ) || ! isset( $scorm_package['scorm_version'] ) ) {
			return null;
		}

		$upload_dir = masteriyo_scorm_upload_dir();
		$upload_url = masteriyo_scorm_upload_url();

		$scorm_package['path'] = trailingslashit( $upload_dir ) . "{$scorm_package['scorm_dir_name']}/";

		if ( ! $for_delete ) {
			$scorm_package['path'] = trailingslashit( $upload_dir ) . "{$scorm_package['scorm_dir_name']}/" . $scorm_package['file_name'];
		}

		$scorm_package['url'] = trailingslashit( $upload_url ) . "{$scorm_package['scorm_dir_name']}/" . $scorm_package['file_name'];

		return $scorm_package;
	}
}

if ( ! function_exists( 'masteriyo_is_scorm_course' ) ) {
	/**
	 * Check if a course is a SCORM course.
	 *
	 * @since 1.8.3
	 *
	 * @param mixed $course_id Course id.
	 *
	 * @return bool  True if it's a SCORM course, false otherwise.
	 */
	function masteriyo_is_scorm_course( $course_id ) {
		$scorm_package = masteriyo_get_scorm_meta( $course_id );

		if ( empty( $scorm_package ) ) {
			return false;
		}

		return ! empty( $scorm_package['path'] ) && ! empty( $scorm_package['url'] );
	}
}


if ( ! function_exists( 'masteriyo_get_iframe_url' ) ) {
	/**
	 * Get the iframe URL for a SCORM course.
	 *
	 * @since 1.8.3
	 *
	 * @param mixed $course_id Course id.
	 *
	 * @return string|false  The iframe URL if available, false otherwise.
	 */
	function masteriyo_get_iframe_url( $course_id ) {

		$scorm    = masteriyo_get_scorm_meta( $course_id );
		$manifest = \simplexml_load_file( masteriyo_get_course_scorm_manifest( $scorm ) );

		if (
			! empty( $manifest )
			&& ! empty( $manifest->resources )
			&& ! empty( $manifest->resources->resource )
			&& ! empty( $manifest->resources->resource->attributes() )
		) {
			$atts = $manifest->resources->resource->attributes();
			if ( ! empty( $atts->href ) ) {
				return (string) "{$scorm['url']}/" . $atts->href;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'masteriyo_get_course_scorm_manifest' ) ) {
	/**
	 * Get the path to the SCORM course manifest file.
	 *
	 * @since 1.8.3
	 *
	 * @param array $scorm SCORM package information.
	 *
	 * @return string|false  The path to the manifest file if found, false otherwise.
	 */
	function masteriyo_get_course_scorm_manifest( $scorm ) {
		$path          = $scorm['path'];
		$manifest_path = "{$path}/imsmanifest.xml";

		return ( file_exists( $manifest_path ) ) ? $manifest_path : false;
	}
}

if ( ! function_exists( 'masteriyo_get_manifest_scorm_version' ) ) {
	/**
	 * Get the SCORM version from the SCORM course manifest.
	 *
	 * @since 1.8.3
	 *
	 * @param array $scorm SCORM package information.
	 *
	 * @return string  The SCORM version (either '1.2' or '2004').
	 */
	function masteriyo_get_manifest_scorm_version( $scorm ) {
		$xml_file = \simplexml_load_file( masteriyo_get_course_scorm_manifest( $scorm ) );

		$scorm_version = '1.2';

		if ( ! empty( $xml_file->metadata ) && count( $xml_file->metadata ) >= 1 ) {
			$schema_version = (string) $xml_file->metadata->schemaversion;

			if ( ! empty( $schema_version ) && '1.2' !== $schema_version ) {
				$scorm_version = '2004';
			}
		} elseif ( ! empty( $xml_file['version'] ) ) {
			$scorm_version = (string) $xml_file['version'];
		}

		return $scorm_version;
	}
}

if ( ! function_exists( 'masteriyo_update_user_scorm_course_progress' ) ) {
	/**
	 * Update user progress for a SCORM course.
	 *
	 * @since 1.8.3
	 *
	 * @param int $course_id Course id.
	 * @param int $user_id User id.
	 * @param int $progress Progress percentage.
	 *
	 * @return void
	 */
	function masteriyo_update_user_scorm_course_progress( $course_id, $user_id, $progress ) {

		global $wpdb;
		$table = "{$wpdb->prefix}masteriyo_user_activities";

		$progress_status = CourseProgressStatus::STARTED;

		if ( is_string( $progress ) ) {
			$progress_status = $progress;
		} else {
			if ( $progress < 100 ) {
				$progress_status = CourseProgressStatus::STARTED;
			} else {
				$progress_status = CourseProgressStatus::COMPLETED;
			}
		}

		$progress_args = array( 'activity_status' => $progress_status );

		if ( CourseProgressStatus::COMPLETED === $progress_status ) {
			$progress_args['completed_at'] = current_time( 'mysql' );
		}

		$query = new CourseProgressQuery(
			array(
				'course_id' => $course_id,
				'user_id'   => $user_id,
			)
		);

		$activity = current( $query->get_course_progress() );

		if ( $activity && CourseProgressStatus::COMPLETED === $activity->get_status() ) {
			return;
		}

		if ( ! $activity ) {
			$activity_data = array(
				'user_id'       => $user_id,
				'item_id'       => $course_id,
				'activity_type' => 'course_progress',
				'created_at'    => current_time( 'mysql' ),
			);

			$activity_data = array_merge( $activity_data, $progress_args );
			$wpdb->insert(
				$table,
				$activity_data
			);
		} else {
			$wpdb->update(
				$table,
				$progress_args,
				array(
					'id' => $activity->get_id(),
				)
			);
		}
	}
}

if ( ! function_exists( 'masteriyo_scorm_upload_dir' ) ) {
	/**
	 * Get the upload directory path for SCORM packages.
	 *
	 * @since 1.8.3
	 *
	 * @return string The directory path for SCORM uploads.
	 */
	function masteriyo_scorm_upload_dir() {
		 $upload        = wp_upload_dir();
		$base_scorm_dir = masteriyo_scorm_base_dir();

		return trailingslashit( $upload['basedir'] ) . $base_scorm_dir;
	}
}

if ( ! function_exists( 'masteriyo_scorm_upload_url' ) ) {
	/**
	 * Get the upload URL for SCORM packages.
	 *
	 * @since 1.8.3
	 *
	 * @return string The URL for SCORM uploads.
	 */
	function masteriyo_scorm_upload_url() {
		 $upload        = wp_upload_dir();
		$base_scorm_dir = masteriyo_scorm_base_dir();
		$upload_url     = trailingslashit( $upload['baseurl'] ) . $base_scorm_dir;

		return $upload_url;
	}
}

if ( ! function_exists( 'masteriyo_scorm_base_dir' ) ) {
	/**
	 * Get the base directory path for SCORM packages relative to the WordPress upload directory.
	 *
	 * This function returns the path suffix used by the SCORM functionalities within the 'masteriyo' project.
	 *
	 * @since 1.8.3
	 *
	 * @return string The relative directory path for SCORM packages.
	 */
	function masteriyo_scorm_base_dir() {
		return 'masteriyo/scorm';
	}
}

if ( ! function_exists( 'masteriyo_scorm_directory_delete' ) ) {
	/**
	 * Delete a directory and its contents recursively.
	 *
	 * @since 1.8.3
	 *
	 * @param string $dir_path The directory path to delete.
	 *
	 * @return void
	 */
	function masteriyo_scorm_directory_delete( $dir_path ) {
		if ( ! is_dir( $dir_path ) ) {
			/* translators: %s: directory path */
			$error_message = sprintf( __( 'The path provided is not a directory: %s', 'masteriyo' ), $dir_path );
			return new WP_Error( 'not_a_directory', $error_message );
		}

		$dir_path = trailingslashit( $dir_path );
		$files    = glob( $dir_path . '*', GLOB_MARK );

		foreach ( $files as $file ) {
			if ( is_dir( $file ) ) {
				$result = masteriyo_scorm_directory_delete( $file );
				if ( is_wp_error( $result ) ) {
					return $result;
				}
			} else {
				if ( ! unlink( $file ) ) {
					/* translators: %s file */
					$error_message = sprintf( __( 'Failed to delete file: %s', 'masteriyo' ), $file );
					return new WP_Error( 'file_delete_failed', $error_message );
				}
			}
		}

		if ( ! rmdir( $dir_path ) ) {
			/* translators: %s: directory path */
			$error_message = sprintf( __( 'Failed to delete directory: %s', 'masteriyo' ), $dir_path );
			return new WP_Error( 'directory_delete_failed', $error_message );
		}

		return true;
	}
}
