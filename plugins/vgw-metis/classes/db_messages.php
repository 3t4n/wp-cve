<?php

namespace WP_VGWORT;

/**
 * Class for handling all message related plugin db interaction
 *
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 * @package     vgw-metis
 */
class Db_Messages {
	// table names
	const TABLE_PIXELS = 'metis_pixels';
	const TABLE_PIXEL_POSTS = 'metis_pixel_posts';
	const TABLE_POSTS = 'posts';
	const TABLE_POST_META = 'postmeta';

	/**
	 * Inserts pixels to table metis_pixels
	 *
	 * @return array | null returns array of posts with pixels (incl. inactive pixels and pixels with no ownership),
	 *                      null on error
	 */
	public static function get_all_posts_with_pixel(): array|null {
		global $wpdb;

		$table_pixels      = $wpdb->prefix . self::TABLE_PIXELS;
		$table_pixel_posts = $wpdb->prefix . self::TABLE_PIXEL_POSTS;
		$table_posts       = $wpdb->prefix . self::TABLE_POSTS;
		$table_post_meta   = $wpdb->prefix . self::TABLE_POST_META;

		$query = "
			SELECT
				posts.ID AS post_id,
				posts.post_title AS post_title,
				posts.post_name AS post_name,
			    pixels.*,
			    pixelposts.active,
			    pm1.meta_value AS text_length,
			    pm2.meta_value AS text_type
			FROM
				$table_posts AS posts
			RIGHT JOIN
				$table_pixel_posts AS pixelposts ON pixelposts.post_id = posts.ID
			RIGHT JOIN
				$table_pixels AS pixels ON pixels.public_identification_id = pixelposts.public_identification_id
			LEFT JOIN
				$table_post_meta AS pm1 ON (pm1.meta_key = '_metis_text_length' AND pm1.post_id = posts.ID )
			LEFT JOIN
				$table_post_meta AS pm2 ON (pm2.meta_key = '_metis_text_type' AND pm2.post_id = posts.ID)
			WHERE
				posts.ID IS NOT NULL
			AND
				pixels.disabled = 0
		";

		return $wpdb->get_results( $query, ARRAY_A );

	}

	/**
	 * sets the message created at date of a given pixel
	 *
	 * @param $private_identification_id
	 * @param $message_created_at
	 *
	 * @return bool | int false on error, number of affected rows else
	 */
	public static function set_message_created_date( $private_identification_id, $message_created_at ): bool|int {
		global $wpdb;

		$table = $wpdb->prefix . self::TABLE_PIXELS;

		if ( empty ( $private_identification_id ) || strlen( $message_created_at ) < 10 ) {
			return false;
		}

		$update_array = array(
			'message_created_at' => $message_created_at,
		);

		return $wpdb->update( $table,
			$update_array,
			array( 'private_identification_id' => $private_identification_id )
		);
	}
}