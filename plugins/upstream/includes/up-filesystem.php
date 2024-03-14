<?php
/**
 * Handle filesystem functions.
 *
 * @package UpStream
 */

/**
 * Load WordPress upgrade API.
 */
require_once ABSPATH . 'wp-admin/includes/upgrade.php';

/**
 * Upstream_upfs_get_file_url
 *
 * @param string|mixed $value Download file (upfsid) query string.
 */
function upstream_upfs_get_file_url( $value ) {
	if ( ! is_string( $value ) ) {
		return $value;
	}

	if ( substr( $value, 0, 7 ) === '_upfs__' ) {
		// upfs file.
		return add_query_arg( 'download', $value, get_post_type_archive_link( 'project' ) );
	} elseif ( filter_var( $value, FILTER_VALIDATE_INT ) ) {
		// an fid.
		return wp_get_attachment_url( $value );
	} else {
		return $value;
	}
}

/**
 * Upstream_upfs_info
 *
 * @param string|mixed $value Download file (upfsid) query string.
 */
function upstream_upfs_info( $value ) {
	global $wpdb;
	$res = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'upfs_files WHERE upfsid=%s', $value ) );

	if ( ! empty( $res ) ) {
		foreach ( $res as $row ) {
			return $row;
		}
	}

	return null;
}

/**
 * Upstream_upfs_download
 *
 * @param string|mixed $value Download file (upfsid) query string.
 */
function upstream_upfs_download( $value ) {
	global $wpdb;
	$res = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'upfs_files WHERE upfsid=%s', $value ) );

	if ( ! empty( $res ) ) {
		foreach ( $res as $row ) {
			$path = upstream_filesystem_path() . '/' . $row->saved_filename;

			if ( file_exists( $path ) ) {
				header( 'Content-Description: File Transfer' );
				header( 'Content-Disposition: attachment; filename=' . $row->orig_filename );
				header( 'Expires: 0' );
				header( 'Cache-Control: must-revalidate' );
				header( 'Pragma: public' );
				header( 'Content-Length: ' . filesize( $path ) );
				header( 'Content-Type: ' . $row->mime_type );
				readfile( $path );
			}

			exit();
		}
	}

	exit();
}

/**
 * Upstream_upfs_upload
 *
 * @param array|mixed $file File data.
 */
function upstream_upfs_upload( $file ) {
	$folder = upstream_filesystem_path();
	$info   = array();

	if ( ! empty( $file ) ) {
		if ( ! empty( $file['tmp_name'] ) ) {
			// Checks the size of the the image.
			if ( filesize( $file['tmp_name'] ) < upstream_filesystem_max_size() ) {

				if ( is_dir( $folder ) ) {
					$upfsid = '_upfs__' . upstream_generate_random_string( 50 );

					// Moves the image to the created file.
					if ( move_uploaded_file( $file['tmp_name'], $folder . '/' . $upfsid ) ) {
						global $wpdb;

						$sql = 'CREATE TABLE ' . $wpdb->prefix . 'upfs_files ( upfsid VARCHAR(60), orig_filename VARCHAR(255), saved_filename VARCHAR(255), mime_type VARCHAR(255), file_size INT, access_rules TEXT, PRIMARY KEY (upfsid) )';
						$r   = maybe_create_table( $wpdb->prefix . 'upfs_files', $sql );
						$r   = $wpdb->insert(
							$wpdb->prefix . 'upfs_files',
							array(
								'upfsid'         => $upfsid,
								'orig_filename'  => $file['name'],
								'saved_filename' => $upfsid,
								'mime_type'      => $file['type'],
								'file_size'      => $file['size'],
								'access_rules'   => '',
							)
						);

						return $upfsid;
					} else {
						unlink( $file['tmp_name'] );
						array_push( $info, __( 'Unable to move file to target folder', 'upstream' ) );
					}
				} else {
					unlink( $file['tmp_name'] );
					array_push( $info, __( 'Unable to move file. The target folder does not exist.', 'upstream' ) );
				}
			} else {
				array_push( $info, __( 'File exceeds the maximum file size', 'upstream' ) );
			}
		} else {
			array_push( $info, __( 'File exceeds the maximum file size', 'upstream' ) );
		}
	}

	return $info;
}
