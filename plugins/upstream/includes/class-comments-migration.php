<?php
/**
 * Handle comments data migration.
 *
 * @package UpStream\Migrations
 */

namespace UpStream\Migrations;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class transform all existent project comments into WordPress Comments.
 *
 * @since   1.13.0
 */
final class Comments_Migration {

	/**
	 * Run the migration if needed.
	 *
	 * @since   1.13.0
	 * @static
	 */
	public static function run() {
		if ( ! self::is_migration_needed() ) {
			return;
		}

		$rowset = self::fetch_metas_rowset();
		if ( count( $rowset ) > 0 ) {
			global $wpdb;

			foreach ( $rowset as $project_id => $legacy_comments ) {
				foreach ( $legacy_comments as $legacy_comment ) {
					if ( ! isset( $legacy_comment['created_by'] )
						|| ! isset( $legacy_comment['created_time'] )
						|| ! isset( $legacy_comment['comment'] )
						|| empty( $legacy_comment['comment'] )
					) {
						continue;
					}

					$user = get_user_by( 'id', $legacy_comment['created_by'] );

					$date = \DateTime::createFromFormat( 'U', $legacy_comment['created_time'] );

					$new_comment_data = array(
						'comment_post_ID'      => $project_id,
						'comment_author'       => $user->display_name,
						'comment_author_email' => $user->user_email,
						'comment_date_gmt'     => $date->format( 'Y-m-d H:i:s' ),
						'comment_content'      => $legacy_comment['comment'],
						'user_id'              => $user->ID,
						'comment_approved'     => 1,
					);

					$new_comment_data['comment_date'] = $date->format( 'Y-m-d H:i:s' );

					$wpdb->insert( $wpdb->prefix . 'comments', $new_comment_data );

					update_comment_meta( $wpdb->insert_id, 'type', 'project' );
				}
			}
		}

		update_option( 'upstream:migration.comments', 'yes' );
	}

	/**
	 * Check if migration is needed.
	 *
	 * @since   1.13.0
	 * @access  private
	 * @static
	 */
	private static function is_migration_needed() {
		return (string) get_option( 'upstream:migration.comments' ) !== 'yes';
	}

	/**
	 * Fetch all discussion metas from all projects.
	 * It returns an associative array with project ID as keys and a list of comments as values.
	 *
	 * @since   1.13.0
	 * @access  private
	 * @static
	 *
	 * @global  $wpdb
	 *
	 * @return  array
	 */
	private static function fetch_metas_rowset() {
		global $wpdb;

		$data = array();

		// Fetch all existent discussion metas.
		$metas_rowset = $wpdb->get_results(
			'
            SELECT `post_id`, `meta_key`, `meta_value`
            FROM `' . $wpdb->prefix . 'postmeta`
            WHERE `meta_key` = "_upstream_project_discussion"'
		);

		if ( count( $metas_rowset ) > 0 ) {
			foreach ( $metas_rowset as $meta ) {
				$project_id = (int) $meta->post_id;
				$meta_value  = (array) maybe_unserialize( $meta->meta_value );

				if ( ! empty( $meta_value ) ) {
					$meta_value = isset( $meta_value[0] ) ? $meta_value[0] : $meta_value;
				}

				if ( ! empty( $meta_value ) && is_array( $meta_value ) ) {
					if ( ! isset( $data[ $project_id ] ) ) {
						$data[ $project_id ] = array();
					}

					$data[ $project_id ][] = $meta_value;
				}
			}
		}

		return $data;
	}
}
