<?php
/**
 * The core plugin class.
 *
 * @since      1.5.0
 * @package    Piramid
 * @subpackage Courtres/includes
 * @author
 */

class Courtres_Entity_Challenges extends Courtres_Entity_Base {

	public static $table_name = 'courtres_piramid_challenges';

	static function get_db_fields() {
		$db_fields = array(
			'id'            => array(
				'code'          => 'id',
				'title'         => 'id',
				'show_in_admin' => true,
				'default_value' => false,
			),
			'name'          => array(
				'code'          => 'name',
				'title'         => __( 'Name', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => 'New challenge',
			),
			'court_id'      => array(
				'code'          => 'court_id',
				'title'         => __( 'Court Id', 'courtres' ),
				'show_in_admin' => false,
				'default_value' => false,
			),
			'piramid_id'    => array(
				'code'          => 'piramid_id',
				'title'         => __( 'Piramid Id', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => false,
			),
			'challenger_id' => array(
				'code'          => 'challenger_id',
				'title'         => __( 'Challenger', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => false,
			),
			'challenged_id' => array(
				'code'          => 'challenged_id',
				'title'         => __( 'Challenged', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => false,
			),
			'winner_id'     => array(
				'code'          => 'winner_id',
				'title'         => __( 'Winner', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => false,
			),
			'results'       => array(
				'code'          => 'winner_id',
				'title'         => __( 'Results', 'courtres' ),
				'show_in_admin' => false,
				'default_value' => false,
			),
			'event_id'      => array(
				'code'          => 'event_id',
				'title'         => __( 'Event', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => false,
			),
			'status'        => array(
				'code'          => 'status',
				'title'         => __( 'Status', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => 'created',
			),
			'start_ts'      => array(
				'code'          => 'start_ts',
				'title'         => __( 'Start', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => 0,
			),
			'end_ts'        => array(
				'code'          => 'end_ts',
				'title'         => __( 'End', 'courtres' ),
				'show_in_admin' => true,
				'default_value' => 0,
			),
			'is_active'     => array(
				'code'          => 'is_active',
				'title'         => __( 'Active', 'courtres' ),
				'show_in_admin' => false,
				'default_value' => true,
			),
			'created_dt'    => array(
				'code'          => 'created_dt',
				'title'         => __( 'Created date and time', 'courtres' ),
				'show_in_admin' => false,
				'default_value' => false,
			),
			'accepted_dt'   => array(
				'code'          => 'accepted_dt',
				'title'         => __( 'Accepted date and time', 'courtres' ),
				'show_in_admin' => false,
				'default_value' => false,
			),
			'closed_dt'     => array(
				'code'          => 'closed_dt',
				'title'         => __( 'Closed date and time', 'courtres' ),
				'show_in_admin' => false,
				'default_value' => false,
			),
			'modified_dt'   => array(
				'code'          => 'modified_dt',
				'title'         => __( 'Modified date', 'courtres' ),
				'show_in_admin' => false,
				'default_value' => false,
			),
		);
		return $db_fields;
	}


	static function create_table() {
		global $wpdb;
		$db_fields = self::get_db_fields();
		$sql       = sprintf(
			"CREATE TABLE IF NOT EXISTS `%1\$s` (
				`id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`name` varchar(128) NOT NULL DEFAULT '%2\$s',
				`piramid_id` int unsigned NOT NULL,
				`court_id` mediumint(9),
				`event_id` mediumint(9),
				`challenger_id` bigint(20) NOT NULL,
				`challenged_id` bigint(20) NOT NULL,
				`winner_id` bigint(20),
				`results` text,
				`status` ENUM ('created', 'accepted', 'scheduled', 'played', 'closed') NOT NULL DEFAULT 'created',
				`is_active` boolean  DEFAULT 1,
				`start_ts` int unsigned,
				`end_ts` int unsigned,
				`created_dt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`accepted_dt` datetime,
				`closed_dt` datetime,
				`modified_dt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				FOREIGN KEY (`piramid_id`) REFERENCES `%3\$s` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
				) ENGINE='InnoDB' %6\$s AUTO_INCREMENT=1;",
			self::get_table_name(),
			$db_fields['name']['default_value'],
			Courtres_Entity_Piramid::get_table_name(),
			$wpdb->prefix . 'courtres_courts',
			$wpdb->prefix . 'courtres_events',
			self::get_charset_collate()
		);
		$wpdb->query( $sql );
	}


	/**
	 * @return array $items with players data or empty array
	 */
	static function get_challenges( array $args = array() ) {
		$challenges = self::get_list( $args );
		if ( $challenges ) {
			$challenger_ids = wp_list_pluck( $challenges, 'challenger_id' );
			$challenged_ids = wp_list_pluck( $challenges, 'challenged_id' );
			$winner_ids     = wp_list_pluck( $challenges, 'winner_id' );
			$winner_ids     = $winner_ids ? $winner_ids : array();

			$user_ids = array_merge( $challenger_ids, $challenged_ids, $winner_ids );
			$user_ids = array_unique( $user_ids ); // to delete duplicates
			$user_ids = array_filter( $user_ids ); // to delete empty elements

			$wp_users = get_users(
				array(
					'include' => $user_ids,
					'fields'  => array(
						'ID',
						'display_name',
						'user_email',
						'user_nicename',
					),
				)
			);

			$piramid_players = Courtres_Entity_Piramids_Players::get_list(
				array(
					'where' => array(
						'conditions' => array(
							'`piramid_id` = ' . $challenges[0]['piramid_id'],
							'`player_id` IN (' . implode( ',', $user_ids ) . ')',
						),
					),
				)
			);
			// function_exists("fppr") ? fppr($challenges, __FILE__.' $challenges') : false;
			foreach ( $challenges as &$challenge ) {

				if ( $challenge['challenger_id'] ) {
					if ( $piramid_players ) {
						$found_key               = array_search( $challenge['challenger_id'], array_column( $piramid_players, 'player_id' ) );
						$challenge['challenger'] = ( $found_key === false ) ? array() : $piramid_players[ $found_key ];
					}
					if ( $wp_users ) {
						$found_key                          = array_search( $challenge['challenger_id'], wp_list_pluck( $wp_users, 'ID' ) );
						$challenge['challenger']['wp_user'] = ( $found_key === false ) ? array() : $wp_users[ $found_key ];
					}
				}
				if ( $challenge['challenged_id'] ) {
					if ( $piramid_players ) {
						$found_key               = array_search( $challenge['challenged_id'], array_column( $piramid_players, 'player_id' ) );
						$challenge['challenged'] = ( $found_key === false ) ? array() : $piramid_players[ $found_key ];
					}
					if ( $wp_users ) {
						$found_key                          = array_search( $challenge['challenged_id'], wp_list_pluck( $wp_users, 'ID' ) );
						$challenge['challenged']['wp_user'] = ( $found_key === false ) ? array() : $wp_users[ $found_key ];
					}
				}
				if ( $challenge['winner_id'] ) {
					if ( $piramid_players ) {
						$found_key           = array_search( $challenge['winner_id'], array_column( $piramid_players, 'player_id' ) );
						$challenge['winner'] = ( $found_key === false ) ? array() : $piramid_players[ $found_key ];
					}
					if ( $wp_users ) {
						$found_key                      = array_search( $challenge['winner_id'], wp_list_pluck( $wp_users, 'ID' ) );
						$challenge['winner']['wp_user'] = ( $found_key === false ) ? array() : $wp_users[ $found_key ];
					}
				}
			}
		}
		// function_exists("fppr") ? fppr($challenges, __FILE__.' $challenges') : false;
		return $challenges;
	}


	/**
	 * Get challenge by id
	 *
	 * @return array with challenge data or empty array
	 */
	static function get_challenge_by_id( int $id ) {
		$args       = array(
			'where' => array(
				'conditions' => array( '`id` = ' . $id ),
			),
		);
		$challenges = self::get_challenges( $args );
		return $challenges && is_array( $challenges ) ? $challenges[0] : array();
	}


	/**
	 * Get not closed challenges by challenger
	 *
	 * @param int piramid_id
	 * @param int challenged_id
	 * @return array with challenges data or empty array
	 */
	static function get_challenges_by_challenger( int $piramid_id, int $challenger_id ) {
		$args       = array(
			'where' => array(
				'conditions' => array( '`piramid_id` = ' . $piramid_id, '`challenger_id` = ' . $challenger_id, '`status` != "closed"' ),
			),
		);
		$challenges = self::get_challenges( $args );
		return $challenges && is_array( $challenges ) ? $challenges : array();
	}


	/**
	 * get id of challenged wp user
	 *
	 * @return int id or false
	 */
	public function get_full_data() {
		$challenge = self::get_challenge_by_id( $this->get_id() );
		return $challenge;
	}


	/**
	 * Get challenges by one or more statuses
	 *
	 * @param array some of statuses ('created', 'accepted', 'played', 'closed')
	 * @return array with challenges data or empty array
	 */
	static function get_by_statuses( int $piramid_id, array $statuses ) {
		foreach ( $statuses as $key => $status ) {
			$conditions[] = sprintf( "status = '%s'", $status );
		}
		$args       = array(
			'sql_where' => sprintf( ' WHERE (`piramid_id` = %d) AND (%s)', $piramid_id, implode( ' OR ', $conditions ) ),
		);
		$challenges = self::get_challenges( $args );
		return $challenges;
	}


	/**
	 * Get expired challenges
	 *
	 * @return array with challenges data or empty array
	 */
	static function get_expired() {
		$args       = array(
			'where' => array(
				'conditions' => array( '`end_ts` < ' . (int) current_time( 'timestamp' ) ),
			),
			'sort'  => self::get_table_name() . '.`end_ts` DESC',
		);
		$challenges = self::get_challenges( $args );
		return $challenges && is_array( $challenges ) ? $challenges : array();
	}


	/**
	 * Check in the piramid if player has not closed challenge as challenger
	 *
	 * @param int piramid_id
	 * @param int challenged_id
	 * @return true | false
	 */
	static function is_player_already_challenger( int $piramid_id, int $challenger_id ) {
		$cnt = self::count(
			array(
				'sql_where' => sprintf( ' WHERE `piramid_id` = %d AND `challenger_id` = %d AND `status` != "closed"', $piramid_id, $challenger_id ),
			)
		);
		return boolval( $cnt );
	}



	/**
	 * Check in the piramid if challenged player is already challenged and the challenge is not closed
	 *
	 * @param int piramid_id
	 * @param int challenged_id
	 * @return true | false
	 */
	static function is_player_already_challenged( int $piramid_id, int $challenged_id ) {
		$cnt = self::count(
			array(
				'sql_where' => sprintf( ' WHERE `piramid_id` = %d AND `challenged_id` = %d AND `status` != "closed"', $piramid_id, $challenged_id ),
			)
		);
		return boolval( $cnt );
	}


	/**
	 * Check in the piramid if challenger or challenged players can re-challeging
	 * It's mean that they have closed challenges and time from closed less than locktime_ts from piramid settings.
	 *
	 * @param int piramid_id
	 * @param int player_id of player who wants to create a challeng
	 * @return array of challenges with players data or empty array
	 */
	static function get_closed_challenges_for_player( int $piramid_id, int $challenger_id, int $challenged_id ) {
		$piramid = Courtres_Entity_Piramid::get_by_id( $piramid_id );

		$challenges = self::get_challenges(
			array(
				'sql_where' => sprintf(
					' WHERE `piramid_id` = %1$d AND (`challenger_id` = %2$d OR `challenged_id` = %2$d) AND (`challenger_id` = %3$d OR `challenged_id` = %3$d) AND `status` = "closed" AND `closed_dt` > CURRENT_TIMESTAMP() - INTERVAL %4$d SECOND',
					$piramid_id,
					$challenger_id,
					$challenged_id,
					$piramid['locktime_ts']
				),
			)
		);
		return $challenges;
	}

	/**
	 * Create challenge
	 *
	 * @param $args["ASSISTANT_ID"] - wp user ID
	 * return id of added challange, -1 if challenge with same piramid_id, challenger_id, challenged_id already exists
	 * or false if db query error
	 */
	static function create( array $args ) {
		global $wpdb;
		$res = false;

		$defaults = array(
			'name'             => null,
			'piramid_id'       => null,
			'challenger_id'    => null,
			'challenged_id'    => null,
			'piramid_url'      => null,
			'_wp_http_referer' => null,
		);
		$args     = wp_parse_args( $args, $defaults );

		$piramid_url = $args['piramid_url'];

		if ( ! $args['piramid_id'] || ! $args['challenger_id'] || ! $args['challenged_id'] ) {
			return false;
		}

		// check for existing challenged player
		if ( self::is_player_already_challenged( intval( $args['piramid_id'] ), intval( $args['challenged_id'] ) ) ) {
			return -1;
		}

		// check if player has challenges as challenger
		if ( self::is_player_already_challenger( intval( $args['piramid_id'] ), intval( $args['challenger_id'] ) ) ) {
			return -2;
		}

		// check for existing challenges and return them if exists
		if ( $challenges = self::get_closed_challenges_for_player( intval( $args['piramid_id'] ), intval( $args['challenger_id'] ), intval( $args['challenged_id'] ) ) ) {
			return array( 'closed_challenges' => $challenges );
		}
				unset( $args['_wp_http_referer'] );
		unset( $args['piramid_url'] );
		$res = $wpdb->insert( self::get_table_name(), $args );
		if ( $res ) {
			$challenge_id = $wpdb->insert_id;
			do_action( 'after_challenge_created', $challenge_id, $piramid_url );
			return $challenge_id;
		} else {
			return false;
		}
	}


	/**
	 * get id of event linked with the challenge
	 *
	 * @return int id or false
	 */
	public function get_event_id() {
		$challenge_data = $this->get_db_data();
		return $challenge_data['event_id'] ? $challenge_data['event_id'] : false;
	}


	/**
	 * get the challenge status
	 *
	 * @return string status or false
	 */
	public function get_status() {
		$challenge_data = $this->get_db_data();
		return $challenge_data['status'] ? $challenge_data['status'] : false;
	}


	/**
	 * Set status "accepted" to challenges with status "created"
	 *
	 * @return int number of updated rows or false
	 */
	public function set_accepted() {
		global $wpdb;
		$table = self::get_table_name();
		$res   = $wpdb->query(
			$wpdb->prepare(
				'UPDATE `' . self::get_table_name() . "` SET `status` = '%s', `accepted_dt` = '%s' WHERE `id` = %d AND `status` = '%s'",
				'accepted',
				date_i18n( 'Y-m-d H:i:s' ),
				$this->get_id(),
				'created'
			)
		);
		do_action( 'after_challenge_accepted', $this->get_id() );
		return $res;
	}


	/**
	 * Set status "played" to challenges with status "accepted" and current timestamp > challenge["end_ts"]
	 *
	 * @return int number of updated rows or false
	 */
	static function set_played() {
		global $wpdb;
		$table = self::get_table_name();
		$res   = $wpdb->query(
			$wpdb->prepare(
				'UPDATE `' . self::get_table_name() . "` SET `status` = '%s' WHERE `status` = %s AND `end_ts` < %d",
				'played',
				'scheduled',
				current_time( 'timestamp' )
			)
		);
		return $res;
	}


	/**
	 * delete accepted by id and status
	 *
	 * @return bool true or false
	 */
	public function delete_by_id_and_status( $status ) {
		$res = self::delete(
			array(
				'id'     => $this->get_id(),
				'status' => $status,
			),
			'AND'
		);
		return $res;
	}


	/**
	 * delete expired challenges in status "created", if current timestamp > timestamp of created_dt + $lifetime_ts
	 *
	 * @param int $lifetime_ts Time after which the challenge should be deleted
	 * @return bool true or false
	 */
	static function delete_created_expired( int $lifetime_ts ) {
		$res = self::delete(
			array(
				'where' => array(
					'conditions' => array(
						"`status` = 'created'",
						'`created_dt` < CURRENT_TIMESTAMP() - INTERVAL ' . $lifetime_ts . ' SECOND',
					),
					'logic'      => 'AND',
				),
			)
		);
		return $res;
	}


	/**
	 * delete expired challenges in status "accepted", if current timestamp > timestamp of created_dt + $lifetime_ts
	 *
	 * @param int $lifetime_ts Time after which the challenge should be deleted
	 * @return bool true or false
	 */
	static function delete_accepted_expired( int $lifetime_ts ) {
		$res = self::delete(
			array(
				'where' => array(
					'conditions' => array(
						"`status` = 'accepted'",
						'`accepted_dt` < CURRENT_TIMESTAMP() - INTERVAL ' . $lifetime_ts . ' SECOND',
					),
					'logic'      => 'AND',
				),
			)
		);
		return $res;
	}


	/**
	 * check if user have been challenged
	 *
	 * @param int $id wp_user ID
	 * @return int $cnt or 0
	 */
	static function user_can_accept( int $id ) {
		$args = array(
			'conditions' => array( "challenged_id = $id", "status = 'created'", 'is_active = 1' ),
		);
		$cnt  = self::count( $args );
		return $cnt;
	}


}
