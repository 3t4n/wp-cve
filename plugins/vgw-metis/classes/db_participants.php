<?php

namespace WP_VGWORT;

/**
 * Class for handling all participant related db interaction
 *
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 * @package     vgw-metis
 */
class Db_Participants {
	// table names
	const TABLE_PARTICIPANT = "metis_participants";
	const TABLE_WPUSERS = "users";

	/**
	 * Get all participants
	 *
	 * @return object | null returns all participants
	 */
	public static function get_all_participants(): array|null {
		global $wpdb;

		$table_participants = $wpdb->prefix . self::TABLE_PARTICIPANT;
		$table_wpusers      = $wpdb->prefix . self::TABLE_WPUSERS;

		return $wpdb->get_results( "
				SELECT *
				FROM
					$table_participants AS participant
				LEFT JOIN
				    $table_wpusers AS user ON user.user_login = participant.wp_user"
			, ARRAY_A );
	}


	/**
	 * Inserts or updates a participant
	 *
	 * @return int|null
	 *
	 * @var object $participant
	 */
	public static function upsert_participant( object $participant ): int|null {
		global $wpdb;
		$table = $wpdb->prefix . self::TABLE_PARTICIPANT;
		if ( ! $participant ) {
			return null;
		}

		$upsert_array = array(
			'first_name'  => sanitize_text_field( $participant->first_name ),
			'last_name'   => sanitize_text_field( $participant->last_name ),
			'file_number' => sanitize_text_field( $participant->file_number ),
			'involvement' => sanitize_text_field( $participant->involvement ),
			'wp_user'     => sanitize_text_field( $participant->wp_user ),
		);

		if ( isset( $participant->id ) && $participant->id > 0 ) {
			// update
			return $wpdb->update( $table, $upsert_array,
				array( 'id' => sanitize_text_field( $participant->id ) )
			);

		} else {
			return $wpdb->insert( $table, $upsert_array );
		}
	}

	/**
	 * delete a participant
	 *
	 * @return int|bool
	 *
	 * @var string $id
	 *
	 */
	public static function delete_participant( string $id ): int|bool {
		global $wpdb;

		if ( $id <= 0 || $id == "" ) {
			return false;
		}
		$table = $wpdb->prefix . self::TABLE_PARTICIPANT;

		return $wpdb->delete( $table, array(
			'id' => $id,
		) );
	}

	/**
	 * Get a participant by given wordpress username
	 *
	 * @return object|null
	 *
	 * @var string $wp_user
	 *
	 */
	public static function get_participant_by_wp_username( string $wp_user ): object|null {

		global $wpdb;
		if ( ! $wp_user ) {
			return null;
		}
		$table = $wpdb->prefix . self::TABLE_PARTICIPANT;

		return $wpdb->get_row( $wpdb->prepare( "
				SELECT * FROM
					$table 
				WHERE
				    wp_user  = %s;
			", $wp_user )
		);
	}


	/**
	 * Get count of all participants which has no last_name
	 *
	 * @return int
	 */
	public static function get_participants_with_no_last_name(): int {
		global $wpdb;

		$table = $wpdb->prefix . self::TABLE_PARTICIPANT;
		$query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table ) );

		if ( $wpdb->get_var( $query ) == $table ) {
			return (int) $wpdb->get_var( "
			SELECT
				COUNT(*) As count
			FROM 
			    $table
			WHERE
			    last_name =''
			"
			);
		}

		return true;

	}


	/**
	 * Get participant with the given id
	 *
	 * @return object|null participant
	 */
	public static function get_participant_by_id( int $id ): object|null {
		global $wpdb;
		if ( ! $id ) {
			return null;
		}
		$table = $wpdb->prefix . self::TABLE_PARTICIPANT;

		return $wpdb->get_row( $wpdb->prepare( "
				SELECT * FROM
					$table 
				WHERE
				    id  = %d;
			", $id )
		);
	}


}