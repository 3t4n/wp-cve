<?php

namespace WP_VGWORT;

/**
 * Class for handling all pixel related db interaction
 *
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 * @package     vgw-metis
 */
class Db_Pixels {
	// table names
	const TABLE_PIXELS = 'metis_pixels';
	const TABLE_PIXEL_POSTS = 'metis_pixel_posts';
	const TABLE_POSTS = 'posts';

	/**
	 * Inserts pixels to table metis_pixels
	 *
	 * @param array $pixels              an array of pixels of type Pixel
	 * @param string|null $force_source  force a source, overwrite pixels source
	 * @param bool|null $force_ownership force ownership, overwrite pixels source
	 *
	 * @return int                      returns positive number for inserted rows count or 0 for error
	 */
	public static function insert_pixels( array $pixels, string $force_source = null, bool|null $force_ownership = null ): int {
		global $wpdb;

		$table = $wpdb->prefix . self::TABLE_PIXELS;

		$insert_count = 0;
		// TODO: use Pixel class here
		try {
			foreach ( $pixels as $pixel ) {
				$insert_array = array(
					'public_identification_id'  => $pixel->public_identification_id,
					'private_identification_id' => $pixel->private_identification_id,
					'count_started'             => 0,
					'domain'                    => $pixel->domain,
					'ordered_at'                => date( 'Y-m-d H:i:s' ),
					'source'                    => $force_source ?: $pixel->source,
				);

				$add_ownership =
					( $force_ownership !== null ) ? $force_ownership :
						( property_exists( $pixel, 'ownership' ) ? $pixel->ownership : null );

				if ( $add_ownership !== null ) {
					$insert_array = array_merge( $insert_array, [ 'ownership' => (int) $add_ownership ] );
				}

				if ( ! empty( $pixel->min_hits ) ) {
					if ( is_array( $pixel->min_hits ) ) {
						$insert_array = array_merge( $insert_array, [ 'min_hits' => json_encode( $pixel->min_hits ) ] );
					} else if ( is_string( $pixel->min_hits ) ) {
						$insert_array = array_merge( $insert_array, [ 'min_hits' => $pixel->min_hits ] );
					}
				};

				if ( ! empty( $pixel->message_created_at ) ) {
					if ( is_string( $pixel->message_created_at ) ) {
						$insert_array = array_merge( $insert_array, [ 'message_created_at' => $pixel->message_created_at ] );
					}
				}

				$wpdb->insert( $table, $insert_array );

				if ( $wpdb->rows_affected ) {
					$insert_count ++;
				}
			}
		} catch ( \Exception $e ) {
			return 0;
		}

		return $insert_count;
	}

	/**
	 * Updates the given pixel with given id to database - false if no id was given
	 *
	 * @param object $pixel pixel data
	 *
	 * @return bool | int return false if update didn't work, return 0 if nothing was updated, return positive int with
	 *              number of affected rows
	 */
	public static function update_pixel( object $pixel ): bool|int {
		global $wpdb;

		$table = $wpdb->prefix . self::TABLE_PIXELS;

		if ( ! isset ( $pixel->public_identification_id ) || ! $pixel->public_identification_id ) {
			return false;
		}

		$update_array = array(
			'public_identification_id'  => $pixel->public_identification_id,
			'private_identification_id' => $pixel->private_identification_id,
			'count_started'             => $pixel->count_started,
			'domain'                    => $pixel->domain,
			'ordered_at'                => $pixel->ordered_at,
			'min_hits'                  => $pixel->min_hits,
			'source'                    => $pixel->source
		);

		if ( property_exists( $pixel, 'ownership' ) ) {
			$update_array = array_merge( $update_array, [ 'ownership' => $pixel->ownership ] );
		}

		if ( property_exists( $pixel, 'message_created_at' ) ) {
			$update_array = array_merge( $update_array, [ 'message_created_at' => $pixel->message_created_at ] );
		}

		return $wpdb->update( $table,
			$update_array,
			array( 'public_identification_id' => $pixel->public_identification_id )
		);
	}

	/**
	 * Get all Pixels from DB, with optional filter
	 *
	 * @param bool | null $assigned                   true = return only assigned, false = return only unassigned, null
	 *                                                = assignment state is of no concern
	 * @param bool | null $active                     true = return only active, false = return only inactive, null =
	 *                                                active state is of no concern
	 * @param bool | null $disabled                   true = pixel is disabled, false = pixel is valid, null = disabled
	 *                                                status of no concern
	 * @param string | null $public_identification_id search for all posts of a certain public identification id
	 *
	 * @param bool | null $ownership                  true = return only pixels with ownership, false = return only
	 *                                                pixels without ownership
	 *
	 * @param array | null $orderby                   assoc array with ordering information ( key => order, title =>
	 *                                                asc)
	 *
	 * @return array | null                           array of pixels or null if no result
	 */
	public static function get_all_pixels(
		bool|null $assigned = null,
		bool|null $active = null,
		bool|null $disabled = null,
		string|null $public_identification_id = null,
		bool|null $ownership = null,
		array|null $orderby = null
	): array|null {
		global $wpdb;

		$table_pixels       = $wpdb->prefix . self::TABLE_PIXELS;
		$table_pixels_posts = $wpdb->prefix . self::TABLE_PIXEL_POSTS;
		$table_posts        = $wpdb->prefix . self::TABLE_POSTS;

		$orderby_add = '';

		if ( is_array( $orderby ) && count( $orderby ) ) {
			$parts = array();
			foreach ( $orderby as $key => $value ) {
				$parts[] = sanitize_sql_orderby( $key . ' ' . $value );
			}

			$orderby_add = ' ORDER BY ' . implode( ', ', $parts );
		}

		$where_add = '';

		// unassigned pixels cannot have active status
		if ( $assigned === false && ( $active === true || $active === false ) ) {
			return null;
		}

		if ( $assigned === true ) {
			$where_add .= " AND pixelposts.public_identification_id IS NOT NULL ";
		} else if ( $assigned === false ) {
			$where_add .= " AND pixelposts.public_identification_id IS NULL ";
		}

		if ( $active === true ) {
			$where_add .= " AND pixelposts.active = true ";
		} else if ( $active === false ) {
			$where_add .= " AND pixelposts.active = false ";
		}

		if ( $disabled === true ) {
			$where_add .= " AND pixels.disabled = true ";
		}

		if ( $disabled === false ) {
			$where_add .= " AND pixels.disabled = false ";
		}

		if ( $ownership === true ) {
			$where_add .= " AND pixels.ownership = true";
		}

		if ( $ownership === false ) {
			$where_add .= " AND pixels.ownership = false";
		}


		if ( $public_identification_id ) {
			$where_add .= " AND pixelposts.public_identification_id = '" . $public_identification_id . "'";
		}

		return $wpdb->get_results( "
				SELECT 
				    pixels.*,
				    IF(pixelposts.public_identification_id IS NULL, 0, 1 ) as assigned,
				    pixelposts.active as active,
				    posts.ID as post_id,
				    posts.post_title as post_title,
				    posts.post_name as post_name
				FROM 
				    $table_pixels AS pixels
				LEFT JOIN
				    $table_pixels_posts AS pixelposts ON pixelposts.public_identification_id = pixels.public_identification_id
				LEFT JOIN
				    $table_posts as posts ON posts.ID = pixelposts.post_id
				WHERE
				    NOT (pixelposts.public_identification_id IS NOT NULL && posts.ID IS NULL)
				AND 
				    (posts.post_type != 'revision' || posts.ID	 IS NULL)
				$where_add
				$orderby_add
		", ARRAY_A );
	}

	/**
	 * Get pixel information via post_id
	 *
	 * @param int $post_id the id of the post connected to the pixel
	 *
	 * @return object | null returns the pixel or null if not found
	 */
	public static function get_pixel_by_post_id( int $post_id ): object|null {
		global $wpdb;

		if ( ! $post_id ) {
			return null;
		}

		$table_pixels      = $wpdb->prefix . self::TABLE_PIXELS;
		$table_pixel_posts = $wpdb->prefix . self::TABLE_PIXEL_POSTS;

		return $wpdb->get_row( $wpdb->prepare( "
				SELECT 
				    pixels.*,
				    pixelposts.active,
				    pixelposts.post_id
				FROM
					$table_pixels AS pixels
				INNER JOIN
					$table_pixel_posts AS pixelposts ON pixelposts.public_identification_id = pixels.public_identification_id
				WHERE
				    pixelposts.post_id  = %d;
			", $post_id )
		);
	}

	/**
	 * Get a pixel via the public ID
	 *
	 * @param string $public_identification_id the public identification id to search for
	 *
	 * @return object | null
	 */
	public static function get_pixel_by_public_identification_id( string $public_identification_id ): object|null {
		global $wpdb;

		if ( ! $public_identification_id ) {
			return null;
		}

		$table_pixels      = $wpdb->prefix . self::TABLE_PIXELS;
		$table_pixel_posts = $wpdb->prefix . self::TABLE_PIXEL_POSTS;

		return $wpdb->get_row( $wpdb->prepare( "
				SELECT 
				    pixels.*,
				    pixelposts.active,
				    pixelposts.post_id
				FROM
					$table_pixels AS pixels
				LEFT JOIN
					$table_pixel_posts AS pixelposts ON pixelposts.public_identification_id = pixels.public_identification_id
				WHERE
				    pixels.public_identification_id = %s
				LIMIT 
					1;
			", $public_identification_id )
		);

	}


	/**
	 * Get a pixel via the private ID
	 *
	 * @param string $private_identification_id the private identification id to search for
	 *
	 * @return object | null
	 */
	public static function get_pixel_by_private_identification_id( string $private_identification_id ): object|null {
		global $wpdb;

		if ( ! $private_identification_id ) {
			return null;
		}

		$table_pixels      = $wpdb->prefix . self::TABLE_PIXELS;
		$table_pixel_posts = $wpdb->prefix . self::TABLE_PIXEL_POSTS;

		return $wpdb->get_row( $wpdb->prepare( "
				SELECT 
				    pixels.*,
				    pixelposts.active,
				    pixelposts.post_id
				FROM
					$table_pixels AS pixels
				LEFT JOIN
					$table_pixel_posts AS pixelposts ON pixelposts.public_identification_id = pixels.public_identification_id
				WHERE
				    pixels.private_identification_id = %s
				LIMIT 
					1;
			", $private_identification_id )
		);
	}

	/**
	 * Get the count of available pixels
	 *
	 * @return int number of available pixels
	 */
	public static function get_available_pixel_count(): int {
		global $wpdb;

		$table_pixel       = $wpdb->prefix . self::TABLE_PIXELS;
		$table_pixel_posts = $wpdb->prefix . self::TABLE_PIXEL_POSTS;

		return (int) $wpdb->get_var( "
			SELECT
				COUNT(*) 
			FROM 
			    $table_pixel AS pixels
			LEFT JOIN
			    $table_pixel_posts AS pixelposts ON pixelposts.public_identification_id = pixels.public_identification_id
			WHERE
			    pixelposts.public_identification_id IS NULL
			AND
			    pixels.ownership = 1;
			"
		);
	}

	/**
	 * remove a pixel < > post relation
	 *
	 * @param string $public_identification_id
	 * @param int $post_id
	 *
	 * @return bool
	 */
	static function remove_pixel_from_post( string $public_identification_id, int $post_id ): bool {
		global $wpdb;

		if ( ! $public_identification_id || ! $post_id ) {
			return false;
		}

		$table_pixel_posts = $wpdb->prefix . self::TABLE_PIXEL_POSTS;

		return $wpdb->delete( $table_pixel_posts, [
			'post_id'                  => $post_id,
			'public_identification_id' => $public_identification_id
		] );

	}

	/**
	 * disable a pixel so it cannot be used again
	 *
	 * @param string $public_identification_id
	 *
	 * @return bool | int
	 */
	static function disable_pixel( string $public_identification_id ): bool|int {
		global $wpdb;

		if ( ! $public_identification_id ) {
			return false;
		}

		$table_pixels = $wpdb->prefix . self::TABLE_PIXELS;

		return $wpdb->update( $table_pixels,
			array( 'disabled' => true ),
			array( 'public_identification_id' => $public_identification_id )
		);
	}

	/**
	 * Get the next free pixel
	 *
	 * @return object | null
	 */
	public static function get_next_free_pixel(): object|null {
		global $wpdb;

		$table_pixel       = $wpdb->prefix . self::TABLE_PIXELS;
		$table_pixel_posts = $wpdb->prefix . self::TABLE_PIXEL_POSTS;

		return $wpdb->get_row( "
			SELECT
				pixels.*
			FROM 
			    $table_pixel AS pixels
			LEFT JOIN
			    $table_pixel_posts AS pixelposts ON pixelposts.public_identification_id = pixels.public_identification_id
			WHERE
			    pixelposts.public_identification_id IS NULL
			AND
			    pixels.disabled = 0
			AND
			    pixels.ownership = 1
			LIMIT 1;"
		);
	}


	/**
	 * Assign a pixel to a post ( no test if pixel pid or post id exist)
	 *
	 * @param string $public_identification_id the pixels public identification id
	 * @param int $post_id                     the post id to connect to
	 *
	 * @return int | bool error > false, else number of rows inserted (can also be 0)
	 */
	public static function assign_pixel_to_post( string $public_identification_id, int $post_id ): int|bool {
		global $wpdb;

		if ( ! $public_identification_id || ! $post_id ) {
			return false;
		}

		$table_pixel_posts = $wpdb->prefix . self::TABLE_PIXEL_POSTS;

		return $wpdb->insert( $table_pixel_posts,
			array(
				'public_identification_id' => $public_identification_id,
				'post_id'                  => $post_id,
				'active'                   => true,
			)
		);
	}

	/**
	 * Activate or deactivate a pixel
	 *
	 * @param string $public_identification_id pixels public identification id
	 * @param bool $activated                  the activation status to set
	 * @param int|null $post_id                limit to post id
	 *
	 * @return bool boolean, true = it worked, false = error
	 */
	public static function set_pixel_activation_status( string $public_identification_id, bool $activated = true, int|null $post_id = null ): bool {
		global $wpdb;

		if ( ! $public_identification_id ) {
			return false;
		}

		$table_pixel_posts = $wpdb->prefix . self::TABLE_PIXEL_POSTS;

		// condition to identify pixel
		$condition = array( 'public_identification_id' => $public_identification_id );
		// add post id if given
		if ( $post_id ) {
			$condition = array_merge( $condition, array( 'post_id' => $post_id ) );
		}

		return $wpdb->update( $table_pixel_posts,
			array( 'active' => $activated ),
			$condition
		);
	}

	/**
	 * return the number of assigned pixels
	 *
	 * @return int assigned pixel count
	 */
	public static function get_assigned_pixel_count(): int {
		global $wpdb;

		$table_pixel       = $wpdb->prefix . self::TABLE_PIXELS;
		$table_pixel_posts = $wpdb->prefix . self::TABLE_PIXEL_POSTS;
		$table_posts       = $wpdb->prefix . 'posts';

		return (int) $wpdb->get_var( "
			SELECT
				COUNT(DISTINCT pixels.public_identification_id) 
			FROM 
			    $table_pixel AS pixels
			LEFT JOIN
			    $table_pixel_posts AS pixelposts ON pixelposts.public_identification_id = pixels.public_identification_id
			LEFT JOIN
			    $table_posts AS posts ON posts.ID = pixelposts.post_id
			WHERE
			    pixelposts.public_identification_id IS NOT NULL
			AND
			    posts.ID IS NOT NULL;"
		);
	}

	/**
	 * insert a list of pixels from csv import to db
	 *
	 * @param array $pixels array of pixels in api format
	 *
	 * @return object
	 */
	public static function upsert_pixels_from_csv( array $pixels ): object {
		global $wpdb;

		if ( ! $pixels || ! count( $pixels ) ) {
			$result          = new \stdClass;
			$result->success = false;

			return $result;
		}

		$table_pixels = $wpdb->prefix . self::TABLE_PIXELS;

		$upsert_sql = "INSERT IGNORE INTO $table_pixels 
			(public_identification_id, private_identification_id, count_started, domain, ordered_at, min_hits, source, message_created_at) VALUES ";

		$values_parts = [];

		$valid_pixels = 0;

		foreach ( $pixels as $pixel ) {
			// skip invalid pixels
			if ( ! empty( $pixel->public_identification_id ) &&
			     ! empty( $pixel->private_identification_id ) &&
			     ! empty( $pixel->state ) &&
			     $pixel->state == Common::API_STATE_VALID
			) {
				$valid_pixels ++;
				$values_parts[] = $wpdb->prepare( "(%s, %s, %d, %s, %s, %s, %s, %s)",
					$pixel->public_identification_id,
					$pixel->private_identification_id,
					$pixel->count_started,
					$pixel->domain,
					$pixel->get_ordered_at_as_string(),
					json_encode( $pixel->min_hits ),
					Common::SOURCE_CSVIMPORT,
					$pixel->get_message_created_at_as_string() ?? null
				);
			}
		}

		$result = new \stdClass;

		if ( $valid_pixels > 0 ) {
			$result->success       = $wpdb->query( $upsert_sql . implode( ', ', $values_parts ) );
			$result->affected_rows = $wpdb->rows_affected;
		} else {
			$result->success       = true;
			$result->affected_rows = 0;
		}

		return $result;
	}

	/**
	 * return a pixels assigned posts count
	 *
	 * @param string $public_identification_id
	 *
	 * @return int|bool
	 */
	public static function get_assigned_posts_count( string $public_identification_id ): int|bool {
		global $wpdb;

		$table_pixel_posts = $wpdb->prefix . self::TABLE_PIXEL_POSTS;

		return (int) $wpdb->get_var( $wpdb->prepare( "
			SELECT
				COUNT(*) 
			FROM 
			    $table_pixel_posts AS pixelposts
			WHERE
			    pixelposts.public_identification_id = %s
			", $public_identification_id )
		);
	}


	/**
	 * removes the relation pixel to posts with given public_id and post
	 *
	 * @param string $public_identification_id
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public static function remove_pixel_posts_relation( string $public_identification_id, int $post_id ): bool {
		global $wpdb;

		if ( $post_id <= 0 || $public_identification_id == "" ) {
			return false;
		}
		$table_pixel_posts = $wpdb->prefix . self::TABLE_PIXEL_POSTS;

		return $wpdb->delete( $table_pixel_posts, array(
			'public_identification_id' => $public_identification_id,
			'post_id'                  => $post_id
		) );
	}

}