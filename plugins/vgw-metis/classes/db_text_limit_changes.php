<?php

namespace WP_VGWORT;

/**
 * Class for handling all text limit change related issues
 *
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 * @package     vgw-metis
 */
class Db_Text_Limit_Changes {
	// table names
	// Todo Db Base class with constants
	const TABLE_PIXELS = 'metis_pixels';
	const TABLE_PIXEL_POSTS = 'metis_pixel_posts';
	const TABLE_TEXT_LIMIT_CHANGES = 'metis_text_limit_changes';
	const TABLE_POSTS = 'posts';

	/**
	 * get all text limit changes by post id
	 *
	 * @param int $post_id
	 *
	 * @return array|bool
	 */
	public static function get_all_text_limit_changes_by_post_id( int $post_id ): array|bool {
		global $wpdb;

		if ( (int) $post_id <= 0 ) {
			return false;
		}

		$table_text_limit_changes = $wpdb->prefix . self::TABLE_TEXT_LIMIT_CHANGES;

		$query = $wpdb->prepare( "
			SELECT
			    tlc.public_identification_id,
				tlc.changed_at,
				tlc.text_length
			FROM
				$table_text_limit_changes AS tlc
			WHERE
				tlc.post_id = %d
		", $post_id );

		return $wpdb->get_results( $query, ARRAY_A );

	}

	/**
	 * get the latest text limit change of a post
	 *
	 * @param int $post_id post id of records to be searched through
	 *
	 * @return object | null | bool returns false on error, null if no text limit change exists, object else
	 */
	public static function get_latest_text_limit_change_by_post_id( int $post_id ): object|null|bool {
		global $wpdb;

		if ( (int) $post_id <= 0 ) {
			return false;
		}

		$table_text_limit_changes = $wpdb->prefix . self::TABLE_TEXT_LIMIT_CHANGES;

		$query = $wpdb->prepare( "
			SELECT
			    tlc.public_identification_id,
				tlc.changed_at,
				tlc.text_length
			FROM
				$table_text_limit_changes AS tlc
			WHERE
				tlc.post_id = %d
			ORDER BY
			    tlc.changed_at DESC
			LIMIT 1
		", $post_id );

		return $wpdb->get_row( $query, OBJECT );

	}

	/**
	 * adds a row to the text limit change table
	 *
	 * @param int $post_id
	 * @param string $public_identification_id
	 * @param int $text_length
	 *
	 * @return bool returns false on error, true on success
	 */
	public static function add_text_limit_change( int $post_id, string $public_identification_id, int $text_length ): bool {
		global $wpdb;

		if ( (int) $post_id <= 0 ) {
			return false;
		}

		$table = $wpdb->prefix . self::TABLE_TEXT_LIMIT_CHANGES;

		$insert_count = 0;

		$date_obj   = new \DateTime();
		$changed_at = $date_obj->format( 'Y-m-d H:i:s' );

		$insert_array = array(
			'post_id'                  => (int) $post_id,
			'changed_at'               => $changed_at,
			'public_identification_id' => $public_identification_id,
			'text_length'              => (int) $text_length
		);

		return $wpdb->insert( $table, $insert_array );
	}

	/**
	 * return all records for given post_id and latest set public identification id
	 *
	 * @param int $post_id
	 *
	 * @return array|bool|null
	 */
	public static function get_text_limit_changes_with_lastest_pid_by_post_id( int $post_id ): array|null|bool {
		global $wpdb;

		if ( empty( $post_id ) || (int) $post_id <= 0 ) {
			return false;
		}

		$table = $wpdb->prefix . self::TABLE_TEXT_LIMIT_CHANGES;

		// get the latest public identification id for post
		$query = "
			SELECT
				public_identification_id
			FROM
				$table
			WHERE
				post_id = %d
			AND
				public_identification_id != ''
			ORDER BY
				changed_at DESC
            LIMIT 1
		";

		$public_identification_id = $wpdb->get_var( $wpdb->prepare( $query, (int) $post_id ) );

		if ( ! $public_identification_id ) {
			return false;
		}

		$query = "
			SELECT
				*
			FROM
			    $table
			WHERE
			    post_id = %d
			AND
			    public_identification_id = %s
			ORDER BY
			    changed_at ASC
		";

		return $wpdb->get_results( $wpdb->prepare( $query, $post_id, $public_identification_id ), ARRAY_A );
	}

	/**
	 * remove all text limit changes by post id
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public static function remove_text_limit_changes_by_post_id( int $post_id ): bool {
		global $wpdb;

		if ( empty( $post_id ) || (int) $post_id <= 0 ) {
			return false;
		}

		$table = $wpdb->prefix . self::TABLE_TEXT_LIMIT_CHANGES;

		$query = "
			DELETE FROM
			    $table
			WHERE
			    post_id = %d
		";

		return $wpdb->query( $wpdb->prepare( $query, $post_id ) );
	}
}
