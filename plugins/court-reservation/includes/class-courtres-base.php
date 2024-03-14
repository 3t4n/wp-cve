<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://webmuehle.at
 * @since      1.4.1
 *
 * @package    Courtres
 * @subpackage Courtres/includes
 */

/**
 * The core plugin class.
 *
 * @since      1.4.1
 * @package    Courtres
 * @subpackage Courtres/includes
 * @author     Webmühle e.U. <office@webmuehle.at>
 */
class Courtres_Base {


	const CSV_DELIMITER     = ';';
	const DEFAULT_MAX_HOURS = 3;
	const DEFAULT_COLOUR = "default";

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.3
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.3
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.4.1
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	protected function getTable( $table ) {
		global $wpdb;
		return "{$wpdb->prefix}courtres_{$table}";
	}

	public function get_version() {
		 return $this->version;
	}


	/**
	 * Get option data by name from db
	 *
	 * @param string option name
	 * @return object of the option with fields option_id, option_name, option_value || false if not exists
	 */
	protected function getOption( $name ) {
		global $wpdb;
		$table_name = $this->getTable( 'settings' );
		$res        = $wpdb->get_row( "SELECT * FROM $table_name WHERE option_name = '" . $name . "'" );
		return $res;
	}

	/**
	 * Get option data by name from db
	 *
	 * @param string option name
	 * @return string option_value || false if not exists
	 */
	protected function getOptionValue( $name ) {
		return $this->getOption( $name )->option_value;
	}

	/**
	 * count future reservations for user as author or parthner
	 */
	protected function countUpcomingUserReservations( $userID ) {
		if ( ! $userID ) {
			return false;
		}

		global $wpdb;
		$theTime = getCurrentDateTime();

		// $res = $wpdb->get_var($wpdb->prepare(
		// "SELECT COUNT(id) AS userReservations FROM {$this->getTable('reservations')}
		// WHERE ( userid = %d OR partnerid = %d OR partnerid2 = %d OR partnerid3 = %d )
		// AND ( date > %s OR (date = %s AND time >= %d AND minute >= %d) )",
		// $userID, $userID, $userID, $userID, $theTime["date"], $theTime["date"], $theTime["hour"], 0
		// ));

		// From 1.5.0 >
		$sql_join        = sprintf( ' LEFT JOIN %1$s ON %1$s.reservation_gid = %2$s.gid', $this->getTable( 'reserv_players' ), $this->getTable( 'reservations' ) );
		$sql_select_more = sprintf( ', GROUP_CONCAT(%1$s.player_id) AS players, GROUP_CONCAT(%1$s.is_author) AS is_author', $this->getTable( 'reserv_players' ) );
		$group_by        = ' GROUP BY ' . $this->getTable( 'reservations' ) . '.id';
				$res     = $wpdb->get_var(
					$wpdb->prepare(
						"SELECT COUNT({$this->getTable('reservations')}.id) AS userReservations 
			FROM {$this->getTable('reservations')}
			{$sql_join}
			WHERE ( {$this->getTable('reservations')}.`gid` = {$this->getTable('reserv_players')}.`reservation_gid` AND {$this->getTable('reserv_players')}.`player_id` = %d )
				AND ( {$this->getTable('reservations')}.`date` > %s OR ({$this->getTable('reservations')}.`date` = %s AND {$this->getTable('reservations')}.`time` >= %d AND {$this->getTable('reservations')}.`minute` >= %d) )
			{$group_by}",
						$userID,
						$theTime['date'],
						$theTime['date'],
						$theTime['hour'],
						0
					)
				);
		// fppr($wpdb->last_query, __FILE__.' last_query');
		// fppr($res , __FILE__.' countUpcomingUserReservations $res ');
		// < From 1.5.0
		return $res;
	}

	/**
	 * get future users reservations
	 *
	 * @return array of reservations or empty array or NULL if error
	 */
	protected function getUpcomingReservations() {
		global $wpdb;
		$theTime = getCurrentDateTime();
		// $res = $wpdb->get_results($wpdb->prepare(
		// "SELECT *  FROM {$this->getTable('reservations')}
		// WHERE ( date > %s OR (date = %s AND time >= %d AND minute >= %d) )",
		// $theTime["date"], $theTime["date"], $theTime["hour"], 0
		// ));

		// From 1.5.0 >
		$sql_join        = sprintf( ' LEFT JOIN %1$s ON %1$s.reservation_gid = %2$s.gid', $this->getTable( 'reserv_players' ), $this->getTable( 'reservations' ) );
		$sql_select_more = sprintf( ', GROUP_CONCAT(%1$s.player_id) AS players', $this->getTable( 'reserv_players' ) );
		$group_by        = ' GROUP BY ' . $this->getTable( 'reservations' ) . '.id';

		$res = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT {$this->getTable('reservations')}.*{$sql_select_more}
			FROM {$this->getTable('reservations')}
			{$sql_join}
			WHERE ( date > %s OR (date = %s AND time >= %d AND minute >= %d) )
			{$group_by}",
				$theTime['date'],
				$theTime['date'],
				$theTime['hour'],
				0
			)
		);
		// < From 1.5.0

		return $res;
	}

	/**
	 * get available players
	 * player must be not author or partner of future user reservations if several_reserve_person option disabled
	 */
	function getAvailablePlayers() {
		$userExcludes = array();
		if ( ! $this->getOptionValue( 'several_reserve_person' ) ) {
			$upcomingReservations = $this->getUpcomingReservations();
			if ( $upcomingReservations ) {
				foreach ( $upcomingReservations as $item ) {
					if ( property_exists( $item, 'players' ) ) {
						$userExcludes = array_merge( $userExcludes, explode( ',', $item->players ) );
					}
				}
			}
		}
		if ( is_user_logged_in() ) {
			$userExcludes[] = wp_get_current_user()->id;
		}
		$userExcludes = array_unique( $userExcludes );

		$players = get_users(
			array(
				'role__in' => array( 'Player', 'Administrator' ),
				'fields'   => array( 'display_name', 'id', 'user_login' ),
				'exclude'  => $userExcludes,
			)
		);
		return $players;
	}


	/**
	 * save reservation's players
	 * author saving first
	 *
	 * @return true if at least one player saved or false
	 */
	function saveReservPlayers( $reservId, array $players ) {
		global $wpdb;
		$this->getTable( 'reserv_players' );
		$result = false;

		foreach ( $players as $key => $playerId ) {
			if ( $playerId ) {
				// to avoid duplicates
				$sql   = sprintf(
					"SELECT COUNT(*) as cnt FROM %s WHERE `reservation_gid`='%s' AND `player_id`=%d",
					$this->getTable( 'reserv_players' ),
					$reservId,
					intval( $playerId )
				);
				$count = $wpdb->get_var( $sql );
				if ( ! $count ) {
					$res = $wpdb->insert(
						$this->getTable( 'reserv_players' ),
						array(
							'reservation_gid' => $reservId,
							'player_id'       => intval( $playerId ),
							'is_author'       => ( $key === 0 ),
						),
						array( '%s', '%d' )
					);
					if ( $res && ! $result ) {
						$result = true;
					}
				}
			}
		}
		return $result;
	}


	/**
	 * Migrate partnerid, partnerid2, partnerid3 columns from courtres_reservations table
	 * to new courtres_reserv_players table
	 *
	 * reservations.id -> reserv_players.reservation_gid
	 * reservations.userid -> reserv_players.player_id, reserv_players.is_author = 1
	 * reservations.partnerid -> reserv_players.player_id, reserv_players.is_author = 0
	 * reservations.partnerid2 -> reserv_players.player_id, reserv_players.is_author = 0
	 * reservations.partnerid3 -> reserv_players.player_id, reserv_players.is_author = 0
	 *
	 * @since    1.5.0
	 */
	public static function migrate_partners() {
		global $wpdb;
		// get all users reservations
		$table_reservations   = $wpdb->prefix . 'courtres_reservations';
		$table_reserv_players = $wpdb->prefix . 'courtres_reserv_players';
		$reservations         = $wpdb->get_results(
			"SELECT id, gid, userid, partnerid, partnerid2, partnerid3 
			FROM {$table_reservations}
			GROUP BY gid"
		);
		// fppr($reservations, __FILE__.' $reservations');

		$rows_to_insert = array();
		foreach ( $reservations as $reserv ) {
			// check if reservation not already exists in courtres_reserv_players table
			$sql   = sprintf(
				"SELECT COUNT(*) as cnt FROM %s WHERE `reservation_gid`='%s'",
				$table_reserv_players,
				$reserv->gid
			);
			$count = $wpdb->get_var( $sql );
			if ( ! $count ) {
				$rows_to_insert[] = sprintf( "('%s', %d, %d)", $reserv->gid, $reserv->userid, 1 );
				if ( $reserv->partnerid ) {
					$rows_to_insert[] = sprintf( "('%s', %d, %d)", $reserv->gid, $reserv->partnerid, 0 );
				}
				if ( $reserv->partnerid2 ) {
					$rows_to_insert[] = sprintf( "('%s', %d, %d)", $reserv->gid, $reserv->partnerid2, 0 );
				}
				if ( $reserv->partnerid3 ) {
					$rows_to_insert[] = sprintf( "('%s', %d, %d)", $reserv->gid, $reserv->partnerid3, 0 );
				}
			}
		}
		if ( $rows_to_insert ) {
			$sql = sprintf(
				'INSERT INTO %s(`reservation_gid`, `player_id`, `is_author`) 
				VALUES %s',
				$table_reserv_players,
				implode( ',', $rows_to_insert )
			);
			$res = $wpdb->query( $sql );
		}
	}


	/**
	 * Check time period for free to new event reservation
	 *
	 * @param array with keys as in $defaults
	 * @return array with error messages or empty array if period is available to reservation
	 */
	public function check_period( $params ) {
		$defaults = array(
			'court_id'               => false,
			'event_date'             => false, // date of the checked period in d-m-Y
			'start'                  => array(
				'h' => false,
				'm' => 0,
			), // start time of the checked period
			'end'                    => array(
				'h' => false,
				'm' => 0,
			), // end time of the checked period in timestamp
			'event_id'               => false, // exists if event was edited
			'is_event_weekly_repeat' => false,
			'event_date_week'        => 0,
			'check_all'              => true, // true - find all intersected events or reservations, true - finish check if one intersected event or reservation found
		);
		$params   = wp_parse_args( $params, $defaults );

				global $wpdb;
		$event_table              = $this->getTable( 'events' );
		$errors                   = array();
		$is_insert_update         = true;
		$is_half_hour_reservation = $this->getOptionValue( 'half_hour_reservation' );

		if ( $params['is_event_weekly_repeat'] == 1 ) {
			if ( ! $params['court_id'] || ! $params['start']['h'] || ! $params['end']['h'] ) {
				return $errors;
			}
		} else {
			if ( ! $params['court_id'] || ! $params['event_date'] || ! $params['start']['h'] || ! $params['end']['h'] ) {
				return $errors;
			}
		}

		$posted_start_time = (int) $params['start']['h'] + (int) $params['start']['m'] / 60;
		$posted_end_time   = (int) $params['end']['h'] + (int) $params['end']['m'] / 60;
		$event_timestamp   = $params['event_date'] ? strtotime( $params['event_date'] ) : false;
		$event_start_ts    = $event_timestamp + $params['start']['h'] * 3600 + $params['start']['m'] * 60;

		$sql_where  = $params['court_id'] ? sprintf( ' WHERE events.courtid = %d', $params['court_id'] ) : '';
		$sql_where .= $params['event_id'] ? sprintf( ' AND events.id != %d', $params['event_id'] ) : '';
		$events     = $wpdb->get_results( sprintf( 'SELECT events.* FROM %s as events%s', $event_table, $sql_where ) );

		if ( $params['is_event_weekly_repeat'] == 1 ) {
			$curEventDateWeek = $params['event_date_week'];
			$last_sundy       = date( 'Y-m-d', strtotime( 'last sunday' ) );
			$event_timestamp  = strtotime( $last_sundy . '+ ' . $curEventDateWeek . ' days' );
			$curEventDate     = new DateTime( date_i18n( 'Y-m-d', $event_timestamp ) );
		} else {
			if ( $event_start_ts < current_time( 'timestamp' ) ) {
				$errors[] = __( "You can't make an event in the past", 'court-reservation' );
				return $errors;
			}
			$curEventDate = new DateTime( $params['event_date'] );
		}
		if ( $events ) {
			foreach ( $events as $event ) {
				$is_day = false; // should be here initilized
				// check days
				if ( $event->weekly_repeat ) {
					$eventDate  = new DateTime( $event->event_date );
					$eeInterval = $curEventDate->diff( $eventDate );
					$eventTab   = 1;
					if ( $eeInterval->days % 7 === 0 ) {
						$is_day = true;
					}
				} else {
					$eventTab = 0;
					if ( $params['is_event_weekly_repeat'] == 1 ) {
						if ( $params['event_date_week'] == date_i18n( 'w', strtotime( $event->event_date ) ) && strtotime( date_i18n( 'Y-m-d' ) ) < strtotime( $event->event_date ) ) {
							$is_day = true;
						}
					} else {
						if ( $event->event_date == date_i18n( 'Y-m-d', $event_timestamp ) ) {
							$is_day = true;
						}
					}
				}

				// check hours and minutes
				if ( $is_day ) {
					$event_start_m    = property_exists( $event, 'start_ts' ) && $event->start_ts ? date_i18n( 'i', $event->start_ts ) : 0;
					$event_start_time = (int) $event->start + (int) $event_start_m / 60;

					$event_end_m    = property_exists( $event, 'end_ts' ) && $event->end_ts ? date_i18n( 'i', $event->end_ts ) : 0;
					$event_end_time = (int) $event->end + (int) $event_end_m / 60;

					if (
						! ( $posted_start_time < $event_start_time && $posted_end_time <= $event_start_time ||
						$posted_start_time >= $event_end_time && $posted_end_time > $event_end_time )
					) {
						$message_error        = __( "You can't make an event that overlaps in time with", 'court-reservation' );
						$message_error       .= ' <a href="' . admin_url( 'admin.php?page=courtres-event&eventID=' . $event->id . '&tab=' . $eventTab ) . '" target="_blank">' . __( 'another one', 'court-reservation' ) . '</a>';
						$errors['overlaps'][] = $message_error;

						if ( ! $params['check_all'] ) {
							break;
						}
					}
				}
			}
		}

		/* Check reservations time slots  */
		$theTime = getCurrentDateTime();
		// 2020-07-23 as - for differt UTC is very important, db could have another time_zone
		// $wpdb->get_results('SET @@time_zone = "'.$theTime["offset"].'";');

		if ( $params['is_event_weekly_repeat'] == 1 ) {
			// weekly repeat event
			$sql_and_where = ' AND `date` >= CURDATE()';
		} else {
			// individual event
			if ( $params['event_date'] ) {
				$sql_and_where = " AND `date` = '" . date_i18n( 'Y-m-d', $event_timestamp ) . " 00:00:00'";
			} else {
				$sql_and_where = ' AND `date` >= CURDATE()';
			}
		}

		// get reservations in the posted event date only
		$sql          = sprintf(
			'SELECT * FROM %s 
			WHERE `courtid` = %d%s 
			ORDER BY date, time',
			$this->getTable( 'reservations' ),
			$params['court_id'],
			$sql_and_where
		);
		$reservations = $wpdb->get_results( $sql );

		if ( $reservations ) {
			// needs to find start and end of the reservation
			// and remove reservations for another day or another day of week (dow)
			$temp_reserv = array();
			foreach ( $reservations as $reserv ) {
				if ( $params['is_event_weekly_repeat'] == 1 ) {
					$reservDate = new DateTime( $reserv->date );
					$dowReserv  = $reservDate->format( 'w' ); // 0 (Sunday) -  6 (Saturday) as in $dow = $_POST['event_date_week']
					if ( $params['event_date_week'] != $dowReserv ) {
						continue;
					}
				}
				$temp_reserv[ $reserv->gid ][] = $reserv;
			}

			foreach ( $temp_reserv as $reservation ) {
				$r_start_h    = $reservation[0]->time;
				$r_start_m    = $reservation[0]->minute;
				$r_start_time = (int) $r_start_h + (int) $r_start_m / 60;

				$r_end_h = $reservation[ count( $reservation ) - 1 ]->time;
				$r_end_m = $reservation[ count( $reservation ) - 1 ]->minute;

				// fo fix problem is that reservation record has only hour ("time" column ) and minute of STARTING time slot!
				if ( $is_half_hour_reservation ) {
					$r_end_time = (int) $r_end_h + (int) $r_end_m / 60 + .5;
				} else {
					$r_end_time = (int) $r_end_h + 1;
				}

				if ( ! ( $posted_start_time < $r_start_time && $posted_end_time <= $r_start_time ||
				$posted_start_time >= $r_end_time && $posted_end_time > $r_end_time )
				) {
					$message_error        = __( "You can't make a event that overlaps in time with", 'court-reservation' );
					$message_error       .= ' <a href="' . admin_url( 'admin.php?page=courtres-reservations&gid=' . $reservation[0]->gid ) . '" target="_blank">' . __( 'another reservation', 'court-reservation' ) . '</a>';
					$errors['overlaps'][] = $message_error;

					if ( ! $params['check_all'] ) {
						break;
					}
				}
			}
		}

		return $errors;
	}


	/*
	* Join user reservation

	 * @return array of reservations or empty array or NULL if error
	 */

	/**
	 * Join user reservations with one gid
	 *
	 * @param array with keys as in $defaults
	 * @return array of reservation objects jointed by guid and with
	 * starting (start_ts) and ending (end_ts) in unix timestamp
	 * or empty array
	 */
	protected function joinReservations( array $reservations ) {
		$reservations_j = array();
		foreach ( $reservations as $reservation ) {
			$minutes[ $reservation->gid ][] = $reservation->time * 60 + $reservation->minute;
			$seconds[ $reservation->gid ][] = $reservation->time * 3600 + $reservation->minute * 60;
			$ids[ $reservation->gid ][]     = $reservation->id;
			if ( ! array_key_exists( $reservation->gid, $reservations_j ) ) {
				$reservations_j[ $reservation->gid ] = $reservation;
			}
		}
		$gids = array_unique( wp_list_pluck( $reservations, 'gid' ) );

		foreach ( $gids as $gid ) {
			$reservations_j[ $gid ]->ids = $ids[ $gid ];
			sort( $minutes[ $gid ] );
			sort( $seconds[ $gid ] );
			$reservations_j[ $gid ]->start_ts = strtotime( $reservations_j[ $gid ]->date ) + $seconds[ $gid ][0];
			$reservations_j[ $gid ]->end_ts   = strtotime( $reservations_j[ $gid ]->date ) + $seconds[ $gid ][ count( $minutes[ $gid ] ) - 1 ] + 1800;
		}
		return $reservations_j;
	}

}
