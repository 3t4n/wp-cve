<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2023 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/COPYING
 *
 * This file is part of Football pool.
 *
 * Football pool is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * Football pool is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with Football pool.
 * If not, see <https://www.gnu.org/licenses/>.
 */

class Football_Pool_Pool {
	public array $leagues;
	public bool $has_bonus_questions = false;
	public bool $has_matches = false;
	public Football_Pool_Matches $matches;
	public bool $has_leagues;
	public bool $always_show_predictions = false;
	public bool $has_jokers;
	public int $num_jokers;
	public int $jokers_per;
	public int $pool_id = 1;
	public array $pool_users; // array of users in a pool
	public int $season = FOOTBALLPOOL_DEFAULT_SEASON;
	public int $match_table_layout;

	private bool $force_lock_time = false;
	private int $lock_timestamp;
	private string $lock_datestring;
	private string $scorehistory;
	private bool $simple_calculation_method;

	/**
	 * Football_Pool_Pool constructor.
	 *
	 * @param int $season
	 *
	 * @throws Exception
	 */
	public function __construct( $season = 0 ) {
		$this->simple_calculation_method =
			( Football_Pool_Utils::get_fp_option( 'simple_calculation_method', 0, 'int' ) === 1 );
		if ( $season > 0 ) $this->season = $season;
		$this->scorehistory = $this->get_score_table();
		$this->num_jokers = $this->get_amount_of_jokers_allowed();
		$this->has_jokers = ( $this->num_jokers > 0 );
		$this->jokers_per = Football_Pool_Utils::get_fp_option( 'jokers_per', 1, 'int' );
		
		$this->leagues = $this->get_leagues();
		$this->has_leagues = ( Football_Pool_Utils::get_fp_option( 'use_leagues', 0, 'int' ) === 1 )
								&& ( count( $this->leagues ) > 1 );
		
		$this->lock_datestring = Football_Pool_Utils::get_fp_option( 'bonus_question_locktime', '' );
		$this->force_lock_time = 
			( Football_Pool_Utils::get_fp_option( 'stop_time_method_questions', 0, 'int' ) === 1 )
			&& ( $this->lock_datestring !== '' );
		if ( $this->force_lock_time ) {
			$date = new DateTime( Football_Pool_Utils::date_from_gmt( $this->lock_datestring ) );
			$this->lock_timestamp = (int) $date->format( 'U' );
		} else {
			$this->lock_timestamp = 0; // bonus questions have no time threshold
		}
		
		// override hiding of predictions or editable questions?
		$this->always_show_predictions =
			( Football_Pool_Utils::get_fp_option( 'always_show_predictions', 0, 'int' ) === 1 );
		
		$this->matches = new Football_Pool_Matches();
		$this->has_matches = $this->matches->has_matches;
		$this->match_table_layout = $this->matches->match_table_layout;
		$this->has_bonus_questions = ( $this->get_number_of_bonusquestions() > 0 );
		
		$this->pool_users = $this->get_pool_user_info( $this->pool_id );
	}

	/**
	 * Returns true if the predictions of a player matches the outcome of a match but without looking at
	 * the actual results. Only the winning team or if it is a draw matter.
	 *
	 * @param $home
	 * @param $away
	 * @param $user_home
	 * @param $user_away
	 *
	 * @return bool
	 */
	private function is_toto_result( $home, $away, $user_home, $user_away ): bool
	{
		return $this->toto( $home, $away ) === $this->toto( $user_home, $user_away );
	}

	/**
	 * Returns 1 when the home team won, 2 when the away team won or 3 in case of a draw.
	 *
	 * @param $home
	 * @param $away
	 *
	 * @return int
	 */
	private function toto( $home, $away ): int
	{
		if ( $home === $away ) return 3;
		if ( $home < $away ) return 2;
		return 1;
	}

	/**
	 * Calculates the score for a given match outcome with the given predictions.
	 *
	 * @param $home
	 * @param $away
	 * @param $user_home
	 * @param $user_away
	 * @param $joker
	 * @param $match_id
	 * @param null $user_id
	 *
	 * @return array
	 */
	public function calc_score( $home, $away, $user_home, $user_away, $joker, $match_id, $user_id = null ): array
	{
		$score = -1;
		$joker = (int) $joker;

		//todo: should this be changed to is_numeric or another check?
		if ( ! is_int( $home ) || ! is_int( $away ) ) {
			// Match result not (yet) available
			$score = '';
		}

		// Is option to default to 0 for a prediction set in the plugin options?
		$default_prediction_0 = Football_Pool_Utils::get_fp_option( 'default_prediction_0', 1, 'int' ) === 1;

		$user_home_is_valid = is_numeric( $user_home );
		$user_away_is_valid = is_numeric( $user_away );

		if (
			// fix predictions option is set, so either one of the predictions should be valid
			( $default_prediction_0 && ( $user_home_is_valid || $user_away_is_valid ) )
			|| // or, if option is not set then both predictions should be valid to be considered a complete prediction
			( $user_home_is_valid && $user_away_is_valid ) ) {
			$user_home = $user_home_is_valid ? (int) $user_home : 0;
			$user_away = $user_away_is_valid ? (int) $user_away : 0;
		} else {
			// User prediction not complete
			if ( $score !== '' ) $score = 0;
		}
		
		$full_score = 0;
		$toto_score = 0;
		$goal_bonus = 0;
		$goal_diff_bonus = 0;
		$full = Football_Pool_Utils::get_fp_option( 'fullpoints', FOOTBALLPOOL_FULLPOINTS, 'int' );
		$toto = Football_Pool_Utils::get_fp_option( 'totopoints', FOOTBALLPOOL_TOTOPOINTS, 'int' );
		$goal = Football_Pool_Utils::get_fp_option( 'goalpoints', FOOTBALLPOOL_GOALPOINTS, 'int' );
		$diff = Football_Pool_Utils::get_fp_option( 'diffpoints', FOOTBALLPOOL_DIFFPOINTS, 'int' );
		$joker_multiplier = Football_Pool_Utils::get_fp_option( 'joker_multiplier', FOOTBALLPOOL_JOKERMULTIPLIER, 'int' );
		
		$do_calc = ( $score === -1 );
		
		$match_draw = $match_home_wins = $match_away_wins = null;
		if ( $do_calc ) {
			$match_draw = ( $home === $away );
			$match_home_wins = ( $home > $away );
			$match_away_wins = ( $home < $away );
		}
		
		$scoring_vars = array(
			'do_calc' => $do_calc,
			'match_id' => $match_id,
			'user_id' => $user_id,
			'home' => $home,
			'away' => $away,
			'user_home' => $user_home,
			'user_away' => $user_away,
			'joker' => $joker,
			'joker_multiplier' => $joker_multiplier,
			'has_jokers' => $this->has_jokers,
			'full' => $full,
			'toto' => $toto,
			'goal' => $goal,
			'diff' => $diff,
			'score' => $score,
			'match_draw' => $match_draw,
			'match_home_wins' => $match_home_wins,
			'match_away_wins' => $match_away_wins,
		);
		
		$score = apply_filters( 'footballpool_score_calc_function_pre', $score, $scoring_vars );
		
		if ( $do_calc ) {
			// no special behavior (like score = '' or score = 0) so we can now calc the score
			$score = 0;
			// check for toto result
			if ( $this->is_toto_result( $home, $away, $user_home, $user_away ) === true ) {
				// check for exact match
				if ( $home === $user_home && $away === $user_away ) {
					$full_score = 1;
					$score = $full;
				} else {
					$toto_score = 1;
					$score = $toto;
				}
			}
			// check for goal bonus
			if ( $home === $user_home ) {
				$score += $goal;
				$goal_bonus++;
			}
			if ( $away === $user_away ) {
				$score += $goal;
				$goal_bonus++;
			}
			// check for goal diff bonus (only awarded when not a full score and not a draw)
			if ( ! $full_score && $match_draw === false && ( $home - $user_home ) === ( $away - $user_away ) ) {
				$goal_diff_bonus = 1;
				$score += $diff;
			}
			
			if ( $joker === 1 && $this->has_jokers ) $score *= $joker_multiplier;
		}
		
		$score = array( 
						'score' => $score,
						'full' => $full_score,
						'toto' => $toto_score,
						'goal_bonus' => $goal_bonus,
						'goal_diff_bonus' => $goal_diff_bonus
					);

		return apply_filters( 'footballpool_score_calc_function_post', $score, $scoring_vars );
	}

	/**
	 * @return string
	 */
	private function get_score_table_option_name(): string
	{
		return sprintf( 'scorehistory_table_s%s', $this->season );
	}

	/**
	 * @param $score_table
	 *
	 * @return string
	 */
	private function format_score_table( $score_table ) {
		return sprintf( $score_table, $this->season );
	}

	/**
	 * @param int $nr
	 * @return void
	 */
	public function set_score_table_by_nr( int $nr ) {
		$table_name = ( $nr == 1 ) ? FOOTBALLPOOL_SCORE_TABLE1_FORMAT : FOOTBALLPOOL_SCORE_TABLE2_FORMAT;
		$table_name = $this->format_score_table( $table_name );
		$this->set_score_table( $table_name );
	}

	/**
	 * @param string $table_name
	 * @return void
	 */
	public function set_score_table( string $table_name ) {
		Football_Pool_Utils::set_fp_option( $this->get_score_table_option_name(), $table_name );
	}

	/**
	 * @param bool $get_current
	 *
	 * @return string
	 */
	public function get_score_table( bool $get_current = true ): string
	{
		$score_table = Football_Pool_Utils::get_fp_option( $this->get_score_table_option_name() );
		if ( ! $get_current ) {
			// If we need the non-active table, then determine which is the active one and return the other.
			if ( $score_table === $this->format_score_table( FOOTBALLPOOL_SCORE_TABLE1_FORMAT ) ) {
				$score_table = $this->format_score_table( FOOTBALLPOOL_SCORE_TABLE2_FORMAT );
			} else {
				$score_table = $this->format_score_table( FOOTBALLPOOL_SCORE_TABLE1_FORMAT );
			}
		}
		return $score_table;
	}

	/**
	 * @param int $user_id
	 * @return string|null
	 */
	public function user_name( int $user_id ): ?string
	{
		return $this->user_info( $user_id, 'display_name' );
	}

	/**
	 * @param int $user_id
	 * @return string|null
	 */
	public function user_email( int $user_id ): ?string
	{
		return $this->user_info( $user_id, 'email' );
	}

	/**
	 * @param int $user_id
	 * @param string $info
	 * @return mixed|null
	 */
	private function user_info( int $user_id, string $info ) {
		if ( array_key_exists( $user_id, $this->pool_users ) ) {
			$user_info = $this->pool_users[$user_id][$info];
		} else {
			$user_info = __( 'unknown', 'football-pool' );
		}
		return apply_filters( "footballpool_user_info_{$info}", $user_info, $user_id );
	}

	/**
	 * @param int $pool_id
	 * @return array
	 */
	private function get_pool_user_info( int $pool_id ): array
	{
		$cache_key = "fp_user_info_pool_{$pool_id}";
		$user_info = wp_cache_get( $cache_key, FOOTBALLPOOL_WPCACHE_PERSISTENT );
		if ( $user_info === false ) {
			if ( defined( 'FOOTBALLPOOL_ALL_WP_USERS' ) ) {
				// all users
				$users = get_users();
				$user_info = [];
				foreach ( $users as $user ) {
					$user_info[$user->ID] = [
						'user_id' => $user->ID,
						'display_name' => Football_Pool_Utils::xssafe( $user->display_name ),
						'user_email' => $user->user_email,
					];
				}
			} else {
				// only active pool users
				$rows = $this->get_users( 0 );
				$user_info = [];
				foreach ( $rows as $row ) {
					$user_info[$row['user_id']] = [
						'user_id' => $row['user_id'],
						'display_name' => Football_Pool_Utils::xssafe( $row['user_name'] ),
						'user_email' => $row['email'],
					];
				}
			}
			wp_cache_set( $cache_key, $user_info, FOOTBALLPOOL_WPCACHE_PERSISTENT );
		}
		
		return $user_info;
	}

	/**
	 * @param int $user_id
	 * @param string $size
	 * @param bool $wrap
	 * @return string|false
	 */
	public function get_avatar( int $user_id, string $size = 'small', bool $wrap = true ) {
		if ( FOOTBALLPOOL_NO_AVATAR ) return '';
		
		if ( ! is_int( $size ) ) {
			switch ( $size ) {
				case 'large':
					$size = FOOTBALLPOOL_LARGE_AVATAR;
					break;
				case 'medium':
					$size = FOOTBALLPOOL_MEDIUM_AVATAR;
					break;
				case 'small':
				default:
					$size = FOOTBALLPOOL_SMALL_AVATAR;
			}
		}
		
		$avatar = get_avatar( $user_id, $size );
		if ( $wrap && $avatar !== false )
			return sprintf( '<span class="fp-avatar">%s</span>', $avatar );
		else
			return $avatar;
	}

	/**
	 * @param int $league $league = 0 for all users
	 * @param string $order_by
	 * @return array|null
	 */
	public function get_users( int $league, string $order_by = 'user_name ASC' ): ?array
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = "SELECT u.ID AS user_id, u.display_name AS user_name, u.user_email AS email, ";
		$sql .= ( $this->has_leagues ? "lu.league_id, " : "" );
		$sql .= "0 AS `points`, 0 AS `full`, 0 AS `toto`, 0 AS `bonus` FROM {$wpdb->users} u ";
		if ( $this->has_leagues ) {
			$sql .= "INNER JOIN {$prefix}league_users lu 
						ON ( u.ID = lu.user_id" . ( $league > 1 ? ' AND lu.league_id = ' . $league : '' ) . " ) ";
			$sql .= "INNER JOIN {$prefix}leagues l ON ( lu.league_id = l.id ) ";
		} else {
			$sql .= "LEFT OUTER JOIN {$prefix}league_users lu ON ( lu.user_id = u.ID ) ";
			$sql .= "WHERE ( lu.league_id <> 0 OR lu.league_id IS NULL ) ";
		}
		$sql .= "ORDER BY {$order_by}";
		return $wpdb->get_results( $sql, ARRAY_A );
	}

	/**
	 * @param int $user_id
	 * @return bool
	 */
	public function user_is_player( int $user_id ): bool
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		if ( $this->has_leagues ) {
			$sql = $wpdb->prepare( "SELECT COUNT( * ) FROM {$prefix}league_users lu
									INNER JOIN {$wpdb->users} u ON ( u.ID = lu.user_id )
									WHERE u.ID = %d AND lu.league_id <> 0"
									, $user_id );
		} else {
			$sql = $wpdb->prepare( "SELECT COUNT( * ) FROM {$prefix}league_users lu
									RIGHT OUTER JOIN {$wpdb->users} u ON ( u.ID = lu.user_id )
									WHERE u.ID = %d AND ( lu.league_id <> 0 OR lu.league_id IS NULL )"
									, $user_id );
		}
		
		return ( $wpdb->get_var( $sql ) == 1 );
	}

	/**
	 * Returns 0 if no leagues are available or user does not exist.
	 *
	 * @param int $user_id
	 * @return int
	 */
	public function get_league_for_user( int $user_id ): int
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		if ( $this->has_leagues ) {
			$sql = $wpdb->prepare( "SELECT league_id FROM {$prefix}league_users WHERE user_id = %d", $user_id );
			$league = $wpdb->get_var( $sql );
			if ( $league === null ) $league = 0;
		} else {
			$league = 0;
		}
		
		return (int) $league;
	}

	/**
	 * @param int $user
	 * @param int $ranking_id
	 * @param string $score_date
	 * @return string|null
	 */
	public function get_user_score( int    $user, int $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT,
	                                string $score_date = '' ): ?string
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		// always show last value when simple calculation is active
		if ( $this->simple_calculation_method ) $score_date = '';
				
		if ( $score_date !== '' ) {
			$sql = "SELECT total_score FROM {$prefix}{$this->scorehistory} 
					WHERE user_id = %d AND ranking_id = %d AND score_date <= %s
					ORDER BY score_order DESC LIMIT 1";
			$sql = $wpdb->prepare( $sql , $user, $ranking_id, $score_date );
		} else {
			$sql = "SELECT total_score FROM {$prefix}{$this->scorehistory} 
					WHERE user_id = %d AND ranking_id = %d 
					ORDER BY score_order DESC LIMIT 1";
			$sql = $wpdb->prepare( $sql , $user, $ranking_id );
		}
		return $wpdb->get_var( $sql ); // return null if nothing found
	}

	/**
	 * @param int $user
	 * @param int $ranking_id
	 * @param string $score_date
	 * @return string|null
	 */
	public function get_user_rank( int    $user, int $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT,
	                               string $score_date = '' ): ?string
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		// always show last value when simple calculation is active
		if ( $this->simple_calculation_method ) $score_date = '';
		
		if ( $score_date !== '' ) {
			$sql = "SELECT ranking FROM {$prefix}{$this->scorehistory} 
					WHERE user_id = %d AND ranking_id = %d AND ( score_date <= %s ) 
					ORDER BY score_order DESC LIMIT 1";
			$sql = $wpdb->prepare( $sql, $user, $ranking_id, $score_date );
		} else {
			$sql = "SELECT ranking FROM {$prefix}{$this->scorehistory} 
					WHERE user_id = %d AND ranking_id = %d 
					ORDER BY score_order DESC LIMIT 1";
			$sql = $wpdb->prepare( $sql, $user, $ranking_id );
		}
		return $wpdb->get_var( $sql ); // return null if nothing found
	}
	
	/**
	 * @param int $league use league=0 to include all users
	 * @param int $ranking_id
	 * @param string $score_date
	 * @return string
	 */
	public function get_ranking_from_score_history( int    $league, int $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT,
	                                                string $score_date = '' ): string
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		// always show last value when simple calculation is active
		if ( $this->simple_calculation_method ) $score_date = '';

		$sql = "SELECT 
					s.ranking
					, u.ID AS user_id, u.display_name AS user_name, u.user_email AS email
					, s.total_score AS points, s.score AS last_score ";
		if ( $this->has_leagues ) $sql .= ", lu.league_id ";
		$sql .= "FROM {$prefix}{$this->scorehistory} AS s
				JOIN (
					SELECT s1.user_id, MAX( s1.score_order ) AS last_row
					FROM {$prefix}{$this->scorehistory} AS s1 ";
		if ( $this->has_leagues ) {
			$sql .= "INNER JOIN {$prefix}league_users lu ON ( s1.user_id = lu.user_id ";
			if ( $league > FOOTBALLPOOL_LEAGUE_ALL ) $sql .= "AND lu.league_id = %d ";
			$sql .= ") ";
		} else {
			$sql .= "LEFT OUTER JOIN {$prefix}league_users lu ON ( lu.user_id = s1.user_id ) ";
		}
		$sql .= "WHERE s1.ranking_id = %d ";
		if ( $score_date !== '' ) $sql .= "AND ( s1.score_date <= %s ) ";
		$sql .= "GROUP BY s1.user_id
				) AS s2 ON ( s2.user_id = s.user_id AND s2.last_row = s.score_order )
				JOIN {$wpdb->users} u ON ( u.ID = s.user_id ) ";
		if ( $this->has_leagues ) {
			$sql .= "INNER JOIN {$prefix}league_users lu ON ( u.ID = lu.user_id ";
			if ( $league > FOOTBALLPOOL_LEAGUE_ALL ) $sql .= "AND lu.league_id = %d ";
			$sql .= ") INNER JOIN {$prefix}leagues l ON ( lu.league_id = l.id ) ";
		} else {
			$sql .= "LEFT OUTER JOIN {$prefix}league_users lu ON ( lu.user_id = u.ID ) ";
		}
		$sql .= "WHERE s.ranking_id = %d ";
		if ( $score_date !== '' ) $sql .= "AND ( score_date <= %s ) ";
		$sql .= "ORDER BY s.ranking ASC";
		if ( $this->has_leagues ) {
			if ( $score_date !== '' ) {
				if ( $league > FOOTBALLPOOL_LEAGUE_ALL ) {
					$sql = $wpdb->prepare( $sql, $league, $ranking_id, $score_date, $league, $ranking_id, $score_date );
				} else {
					$sql = $wpdb->prepare( $sql, $ranking_id, $score_date, $ranking_id, $score_date );
				}
			} else {
				if ( $league > FOOTBALLPOOL_LEAGUE_ALL ) {
					$sql = $wpdb->prepare( $sql, $league, $ranking_id, $league, $ranking_id );
				} else {
					$sql = $wpdb->prepare( $sql, $ranking_id, $ranking_id );
				}
			}
		} else {
			if ( $score_date !== '' ) {
				$sql = $wpdb->prepare( $sql, $ranking_id, $score_date, $ranking_id, $score_date );
			} else {
				$sql = $wpdb->prepare( $sql, $ranking_id, $ranking_id );
			}
		}

		return $sql;
	}

	/**
	 * @param int $league
	 * @param int $num_users
	 * @param int $ranking_id
	 * @param string $score_date
	 * @return array
	 */
	public function get_pool_ranking_limited( int $league, int $num_users,
	                                          int $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT,
	                                          string $score_date = '' ): array
	{
		// always show last value when simple calculation is active
		if ( $this->simple_calculation_method ) $score_date = '';
		
		// if score_date is empty we can get the data from the WP cache
		if ( $score_date === '' ) {
			$ranking = array_slice( $this->get_pool_ranking( $league, $ranking_id ), 0, $num_users );
		} else {
			global $wpdb;
			$sql = $this->get_ranking_from_score_history( $league, $ranking_id, $score_date );
			$sql = $wpdb->prepare( "{$sql} LIMIT %d", $num_users );
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			$ranking = [];
			$i = 1;
			foreach( $rows as $row ) {
				$row['ranking'] = $i++;
				$ranking[] = $row;
			}
		}
		
		return apply_filters( 'footballpool_get_ranking_limited', $ranking );
	}

	/**
	 * @param int $league_id
	 * @param int $ranking_id
	 * @return array
	 */
	public function get_pool_ranking( int $league_id, int $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT ): array
	{
		$cache_key = "fp_get_pool_ranking_r{$ranking_id}_l{$league_id}";
		$rows = wp_cache_get( $cache_key, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		
		if ( $rows === false ) {
			global $wpdb;
			$sql = $this->get_ranking_from_score_history( $league_id, $ranking_id );
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			$ranking = [];
			$i = 1;
			foreach( $rows as $row ) {
				$row['ranking'] = $i++;
				$ranking[] = $row;
			}
			$rows = apply_filters( 'footballpool_get_ranking', $ranking );
			wp_cache_set( $cache_key, $rows, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		}
		
		return $rows;
	}

	/**
	 * Returns an array of (a subset of) users with their amount of predictions.
	 *
	 * @param array $users
	 * @param int $ranking_id
	 *
	 * @return array
	 */
	public function get_prediction_count_per_user( array $users,
	                                               int $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT ): array
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$num_predictions = array();
		
		if ( is_array( $users ) && count( $users ) > 0 ) {
			$users = implode( ',', $users );
			$users = "AND p.user_id IN ( {$users} )";
		} else {
			$users = '';
		}
		
		// matches
		if ( $ranking_id === FOOTBALLPOOL_RANKING_DEFAULT ) {
			$sql = "SELECT p.user_id, COUNT( * ) AS num_predictions 
					FROM {$prefix}predictions p
					WHERE p.home_score IS NOT NULL AND p.away_score IS NOT NULL {$users}
					GROUP BY p.user_id";
		} else {
			$sql = "SELECT p.user_id, COUNT( * ) AS num_predictions 
					FROM {$prefix}predictions p
					JOIN {$prefix}rankings_matches r ON 
						( r.match_id = p.match_id AND r.ranking_id = {$ranking_id} ) 
					WHERE p.home_score IS NOT NULL AND p.away_score IS NOT NULL {$users} 
					GROUP BY p.user_id";
		}
		$rows = $wpdb->get_results( $sql, ARRAY_A );
		
		foreach ( $rows as $row ) {
			$num_predictions[$row['user_id']] = (int) $row['num_predictions'];
		}
		
		// questions
		if ( $ranking_id === FOOTBALLPOOL_RANKING_DEFAULT ) {
			$sql = "SELECT p.user_id, COUNT( * ) AS num_predictions 
					FROM {$prefix}bonusquestions_useranswers p
					WHERE p.answer <> '' {$users} 
					GROUP BY p.user_id";
		} else {
			$sql = "SELECT p.user_id, COUNT( * ) AS num_predictions 
					FROM {$prefix}bonusquestions_useranswers p
					JOIN {$prefix}rankings_bonusquestions r ON 
						( r.question_id = p.question_id AND r.ranking_id = {$ranking_id} ) 
					WHERE p.answer <> '' {$users} 
					GROUP BY p.user_id";
		}
		$rows = $wpdb->get_results( $sql, ARRAY_A );
		
		foreach ( $rows as $row ) {
			if ( array_key_exists( $row['user_id'], $num_predictions) ) {
				$num_predictions[$row['user_id']] += (int) $row['num_predictions'];
			} else {
				$num_predictions[$row['user_id']] = (int) $row['num_predictions'];
			}
		}
		
		// return resulting array of user ids with their total number of predictions
		return $num_predictions;
	}

	/**
	 * Returns an array of (a subset of) users with their amount of used jokers.
	 *
	 * @param array $users
	 * @param int $ranking_id
	 *
	 * @return array
	 */
	public function get_used_joker_count_per_user( array $users,
	                                               int $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT ): array
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;

		$num_jokers = array();

		if ( is_array( $users ) && count( $users ) > 0 ) {
			$users = implode( ',', $users );
			$users = "AND user_id IN ( {$users} )";
		} else {
			$users = '';
		}

		$sql = $wpdb->prepare( "SELECT user_id, SUM( joker_used ) AS num_jokers 
								FROM {$prefix}{$this->scorehistory}
								WHERE ranking_id = %d {$users} GROUP BY user_id"
							, $ranking_id );
		$rows = $wpdb->get_results( $sql, ARRAY_A );

		foreach ( $rows as $row ) {
			$num_jokers[$row['user_id']] = (int) $row['num_jokers'];
		}

		// return resulting array of user ids with their total number of used jokers
		return $num_jokers;
	}

	/**
	 * Prints a pool ranking.
	 *
	 * @param $league_id   int         League ID.
	 * @param $user_id     int         User ID of the logged in user that is viewing the ranking (if applicable).
	 * @param $users       array       Array of users that is used to get some extra information for the ranking.
	 * @param $ranking     array       The ranking that you want to print.
	 * @param $ranking_id  int         The ranking ID.
	 * @param $type        string|null Specify if it is the 'page' or a 'widget' or 'shortcode'.
	 * @param $num_users   int|null    Amount of users to display in the ranking (if you want to limit it).
	 * @return string
	 */
	public function print_pool_ranking( int $league_id, int $user_id, array $users, array $ranking,
	                                    int $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT, ?string $type = 'page',
	                                    ?int $num_users = null ): string
	{
		$output = '';
		
		// get number of predictions per user
		$predictions = $this->get_prediction_count_per_user( $users, $ranking_id );
		// and the number of jokers used per user
		$jokers_used = $this->get_used_joker_count_per_user( $users, $ranking_id );
		
		$user_page = Football_Pool::get_page_link( 'user' );
		$all_user_view = ( $type === 'page' ) && ( $league_id == FOOTBALLPOOL_LEAGUE_ALL ) && $this->has_leagues;
		$i = 1;
		
		// define templates
		$template_start = sprintf( '<table class="pool-ranking ranking-%s">', $type );
		$template_start = apply_filters( 'footballpool_ranking_template_start',
			$template_start, $league_id, $user_id, $ranking_id, $all_user_view, $type
		);
		
		$template_end = '</table>';
		$template_end = apply_filters( 'footballpool_ranking_template_end',
			$template_end, $league_id, $user_id, $ranking_id, $all_user_view, $type
		);

		// todo: at some point we should remove references to the name 'joker'
		if ( $all_user_view === true ) {
			$ranking_template =
				'<tr class="%css_class% jokers-used-%jokers_used% multipliers-used-%multipliers_used%">
				<td class="user-rank">%rank%.</td>
				<td class="user-name"><a href="%user_link%">%user_name%</a></td>
				<td class="user-score ranking score">%points%</td>
				<td class="user-league">%league_image%</td>
				</tr>';
		} else {
			$ranking_template =
				'<tr class="%css_class% jokers-used-%jokers_used% multipliers-used-%multipliers_used%">
				<td class="user-rank">%rank%.</td>
				<td class="user-name"><a href="%user_link%">%user_name%</a></td>
				<td class="user-score ranking score">%points%</td>
				</tr>';
		}
		$ranking_template = apply_filters( 'footballpool_ranking_ranking_row_template',
			$ranking_template, $all_user_view, $type
		);
		
		// define the start and end template params
		$template_params = [];
		$template_params = apply_filters( 'footballpool_ranking_template_params',
			$template_params, $league_id, $user_id, $ranking_id, $type
		);
		
		$output .= Football_Pool_Utils::placeholder_replace( $template_start, $template_params );

		// add users to ranking output that are not yet in the scorehistory table
		if ( $num_users === null || ( is_int( $num_users ) && count( $ranking ) < $num_users ) ) {
			$l_users = $r_users = [];
			$rank_nr = 1;
			$league_users = $this->get_users( $league_id );
			foreach ( $league_users as $l_user ) {
				$l_users[] = $l_user['user_id'];
			}
			foreach ( $ranking as $rank ) {
				$r_users[] = $rank['user_id'];
				$rank_nr++;
			}

			$extra_users = array_diff( $l_users, $r_users );
			// $extra_users now contains all users that are not in the ranking ($r_users) but are in the
			// league ($l_users)

			foreach ( $league_users as $l_user ) {
				// get the complete user info from the $league_users array and add to the ranking if the user id
				// is in the $extra_users array.
				if ( in_array( $l_user['user_id'], $extra_users ) ) {
					if ( $this->has_leagues && isset ( $l_user['league_id'] ) ) {
						$user_league_id = $l_user['league_id'];
					} else {
						$user_league_id = FOOTBALLPOOL_LEAGUE_ALL;
					}

					$ranking[] = array(
						'ranking' => $rank_nr++,
						'user_id' => $l_user['user_id'],
						'user_name' => $l_user['user_name'],
						'email' => $l_user['email'],
						'points' => 0,
						'last_score' => 0,
						'league_id' => $user_league_id,
					);
				}
			}
			
			if ( $num_users !== null ) $ranking = array_slice( $ranking, 0, $num_users );
		}
		
		// final chance to change the ranking before printing it
		$ranking = apply_filters( 'footballpool_print_ranking_ranking', $ranking, $ranking_id );
		
		$ranking_count = count( $ranking );
		foreach ( $ranking as $row ) {
			$class = ( $i % 2 !== 0 ? 'odd' : 'even' );
			if ( $all_user_view === true ) $class .= ' league-' . $row['league_id'];
			if ( $row['user_id'] == $user_id ) $class .= ' currentuser';
			
			// define the template param values
			$ranking_template_params = array(
				'rank' => $row['ranking'],
				'user_id' => $row['user_id'],
				'user_name' => $this->user_name( $row['user_id'] ),
				'user_link' => esc_url( add_query_arg( array( 'user' => $row['user_id'] ), $user_page ) ),
				'user_avatar' => $this->get_avatar( $row['user_id'], 'medium' ),
				'num_predictions' => $predictions[$row['user_id']] ?? 0,
				'jokers_used' => $jokers_used[$row['user_id']] ?? 0,
				'multipliers_used' => $jokers_used[$row['user_id']] ?? 0,
				'points' => $row['points'],
				'league_image' => ( isset( $row['league_id'] ) ? $this->league_image( $row['league_id'] ) : '' ),
				'league_name' => ( isset( $row['league_id'] ) ? $this->league_name( $row['league_id'] ) : '' ),
				'css_class' => $class,
				'last_score' => ( $row['last_score'] ?? '' ),
				'ranking_row_number' => $i++,
				'ranking_count' => $ranking_count,
				'league_id' => $league_id,
				'ranking_id' => $ranking_id,
			);
			$ranking_template_params = apply_filters( 'footballpool_ranking_ranking_row_params',
				$ranking_template_params, $league_id, $user_id, $ranking_id, $all_user_view, $type, $row
			);
			
			$output .= Football_Pool_Utils::placeholder_replace( $ranking_template, $ranking_template_params );
		}
		$output .= Football_Pool_Utils::placeholder_replace( $template_end, $template_params );
		
		return $output;
	}

	/**
	 * @param string $which
	 * @return array
	 */
	public function get_rankings( string $which = 'all' ): array
	{
		$only_user_defined = ( $which === 'user defined' || $which === 'user_defined' );
		if ( $only_user_defined === true ) {
			$cache_key = FOOTBALLPOOL_CACHE_RANKINGS_USERDEFINED;
		} else {
			$cache_key = FOOTBALLPOOL_CACHE_RANKINGS_ALL;
		}
		
		$rankings = wp_cache_get( $cache_key, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		
		if ( $rankings === false ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			
			$filter = $only_user_defined ? 'WHERE user_defined = 1' : '';
			
			$sql = "SELECT `id`, `name`, `user_defined`, `calculate` 
					FROM `{$prefix}rankings` {$filter} ORDER BY `user_defined` ASC, `name` ASC";
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			
			$rankings = [];
			foreach ( $rows as $row ) {
				$rankings[$row['id']] = $row;
			}
			wp_cache_set( $cache_key, $rankings, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		}
		
		return $rankings;
	}

	/**
	 * @param int $id
	 * @return array|null
	 */
	public function get_ranking_by_id( int $id ): ?array
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = $wpdb->prepare( "SELECT `name`, `user_defined`, `calculate` FROM `{$prefix}rankings` WHERE `id` = %d", $id );
		return $wpdb->get_row( $sql, ARRAY_A ); // returns null if no ranking found
	}

	/**
	 * @param int $id
	 * @return array|null
	 */
	public function get_ranking_matches( int $id ): ?array
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = $wpdb->prepare( "SELECT `match_id` FROM `{$prefix}rankings_matches` 
								WHERE `ranking_id` = %d", $id );
		return $wpdb->get_results( $sql, ARRAY_A ); // returns null if no ranking found
	}

	/**
	 * @param int $id
	 * @return array|null
	 */
	public function get_ranking_questions( int $id ): ?array
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = $wpdb->prepare( "SELECT question_id FROM {$prefix}rankings_bonusquestions 
								WHERE ranking_id = %d", $id );
		return $wpdb->get_results( $sql, ARRAY_A ); // returns null if no ranking found
	}

	/**
	 * @param int $id
	 * @return string
	 */
	public function league_name( int $id ): string
	{
		if ( $this->has_leagues && ! empty( $this->leagues[$id]['league_name'] ) ) {
			return Football_Pool_Utils::xssafe( $this->leagues[$id]['league_name'] );
 		} else {
			return '';
		}
	}

	/**
	 * @param int $id
	 * @return string
	 */
	public function league_image( int $id ): string
	{
		if ( $this->has_leagues && ! empty( $this->leagues[$id]['image'] ) ) {
			$path = '';
			$img = $this->leagues[$id]['image'];
			if ( stripos( $img, 'http://' ) !== 0 && stripos( $img, 'https://' ) !== 0 && stripos( $img, '//' ) !== 0 ) {
				$path = trailingslashit( FOOTBALLPOOL_UPLOAD_URL . 'leagues' );
			}
			$img = sprintf( '<img src="%s" alt="%s" title="%s">'
							, $path . $img
							, Football_Pool_Utils::xssafe( $this->leagues[$id]['league_name'] )
							, Football_Pool_Utils::xssafe( $this->leagues[$id]['league_name'] )
						);
		} else {
			$img = '';
		}
		return $img;
	}

	/**
	 * @param bool $only_user_defined
	 * @return array
	 */
	public function get_leagues( bool $only_user_defined = false ): array
	{
		if ( $only_user_defined === true ) {
			$cache_key = FOOTBALLPOOL_CACHE_LEAGUES_USERDEFINED;
		} else {
			$cache_key = FOOTBALLPOOL_CACHE_LEAGUES_ALL;
		}
		$leagues = wp_cache_get( $cache_key, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		
		if ( $leagues === false ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			
			$filter = $only_user_defined ? 'WHERE user_defined = 1' : '';
			
			$sql = "SELECT id AS league_id, name AS league_name, user_defined, image 
					FROM {$prefix}leagues {$filter} ORDER BY user_defined ASC, name ASC";
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			
			$leagues = [];
			foreach ( $rows as $row ) {
				$leagues[(int)$row['league_id']] = $row;
			}
			wp_cache_set( $cache_key, $leagues, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		}
		
		return $leagues;
	}

	/**
	 * @param int $league
	 * @param string $select
	 * @return string
	 */
	public function league_filter( int $league = 0, string $select = 'league' ): string
	{
		$output = '';
		
		if ( $this->has_leagues ) {
			$options = array();
			foreach ( $this->leagues as $row ) {
				$options[$row['league_id']] = Football_Pool_Utils::xssafe( $row['league_name'] );
			}
			$output .= Football_Pool_Utils::select( $select, $options, $league, '', 'league-select' );
		}
		
		return $output;
	}

	/**
	 * @param int $league
	 * @param string $input_name Name and ID for the <select> tag
	 * @return string
	 */
	public function league_select( int $league = 0, string $input_name = 'league' ): string
	{
		$output = '';
		
		if ( $this->has_leagues ) {
			$output .= sprintf('<select name="%s" id="%s">', $input_name, $input_name);
			$output .= '<option value="0"></option>';
			foreach ( $this->leagues as $row ) {
				if ( $row['user_defined'] == 1 ) {
					$output .= sprintf( '<option value="%d"%s>%s</option>',
						$row['league_id'],
						( (int)$row['league_id'] === $league ? ' selected="selected"' : '' ),
						Football_Pool_Utils::xssafe( $row['league_name'] )
					);
				}
			}
			$output .= '</select>';
		}
		return $output;
	}

	/**
	 * @param int $user_id
	 * @param int $new_league_id
	 * @param string $old_league
	 * @return void
	 */
	public function update_league_for_user( int $user_id, int $new_league_id,
	                                        string $old_league = 'update league' )
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		if ( $old_league === 'no update' ) {
			$sql = $wpdb->prepare( "INSERT INTO {$prefix}league_users ( user_id, league_id ) 
									VALUES ( %d, %d )
									ON DUPLICATE KEY UPDATE league_id = league_id", 
									$user_id, $new_league_id
								);
		} else {
			$sql = $wpdb->prepare( "INSERT INTO {$prefix}league_users ( user_id, league_id ) 
									VALUES ( %d, %d )
									ON DUPLICATE KEY UPDATE league_id = %d", 
									$user_id, $new_league_id, $new_league_id
								);
		}

		$wpdb->query( $sql );
	}

	/**
	 * @return int
	 */
	private function get_number_of_bonusquestions(): int
	{
		$cache_key = 'fp_num_questions';
		$num_questions = wp_cache_get( $cache_key, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		if ( $num_questions === false ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			$sql = "SELECT COUNT( * ) FROM {$prefix}bonusquestions";
			$num_questions = $wpdb->get_var( $sql );
			wp_cache_set( $cache_key, $num_questions, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		}

		return (int) $num_questions;
	}

	/**
	 * @return string
	 */
	public function get_bonus_question_sorting_method(): string
	{
		switch ( Football_Pool_Utils::get_fp_option( 'question_sort_method', FOOTBALLPOOL_QUESTION_SORT, 'int' ) ) {
			case 3:
				$order = 'q.answer_before_date DESC, q.question_order DESC';
				break;
			case 2:
				$order = 'q.answer_before_date ASC, q.question_order ASC';
				break;
			case 1:
				$order = 'q.question_order DESC, q.answer_before_date ASC';
				break;
			case 0:
			default:
				$order = 'q.question_order ASC, q.answer_before_date ASC';
		}

		return apply_filters( 'footballpool_bonus_question_sorting_method', $order );
	}

	/**
	 * @param int $user_id
	 * @param array|null $question_ids
	 * @param bool $only_non_linked
	 * @return array
	 * @throws Exception
	 */
	public function get_bonus_questions_for_user( int  $user_id = 0, ?array $question_ids = [],
	                                              bool $only_non_linked = false ): array
	{
		if ( $user_id === 0 ) return [];
		
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$ids = $non_linked = '';
		if ( is_array( $question_ids ) && count( $question_ids ) > 0 ) {
			$ids = ' AND q.id IN ( ' . implode( ',', $question_ids ) . ' ) ';
		}
		if ( $only_non_linked ) {
			$non_linked = 'WHERE q.match_id = 0';
		}
		$sorting = $this->get_bonus_question_sorting_method();
		// also include user answers
		$sql = $wpdb->prepare( "SELECT 
									q.id, q.question, a.answer AS user_answer, 
									q.points, a.points AS user_points, 
									q.answer_before_date AS question_date, 
									DATE_FORMAT( q.score_date, '%%Y-%%m-%%d %%H:%%i' ) AS score_date, 
									DATE_FORMAT( q.answer_before_date, '%%Y-%%m-%%d %%H:%%i' ) AS answer_before_date, 
									q.match_id, a.correct,
									qt.type, qt.options, qt.image, qt.max_answers,
									q.answer AS question_answer,
									q.question_order
								FROM {$prefix}bonusquestions q 
								INNER JOIN {$prefix}bonusquestions_type qt
									ON ( q.id = qt.question_id {$ids})
								LEFT OUTER JOIN {$prefix}bonusquestions_useranswers a
									ON ( a.question_id = q.id AND a.user_id = %d )
								{$non_linked}
								ORDER BY {$sorting}",
							$user_id
						);
		
		$rows = $wpdb->get_results( $sql, ARRAY_A );
		$questions = [];
		
		$this->has_bonus_questions = ( count( $rows ) > 0 );
		
		if ( $this->has_bonus_questions ) {
			foreach ( $rows as $row ) {
				$i = $row['id'];
				$questions[$i] = $row;
				$ts = new DateTime( $row['question_date'] );
				$ts = $ts->format( 'U' );
				$questions[$i]['has_answer'] = ! is_null( $row['user_answer'] );
				$questions[$i]['question_timestamp'] = $ts;
				$questions[$i]['question_is_editable'] =
					$this->question_is_editable( $questions[$i]['question_timestamp'] );
			}
		}
		
		return apply_filters( 'footballpool_bonusquestions_for_user', $questions, $user_id );
	}
	
	/**
	 * Returns array of questions.
	 *
	 * @return array
	 * @throws Exception
	 */
	public function get_bonus_questions(): array
	{
		$question_info = wp_cache_get( FOOTBALLPOOL_CACHE_QUESTIONS, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		
		if ( $question_info === false ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;

			$sorting = $this->get_bonus_question_sorting_method();
		
			$sql = "SELECT 
						q.id, q.question, q.answer, q.points, q.answer_before_date AS question_date, 
						DATE_FORMAT( q.score_date, '%Y-%m-%d %H:%i' ) AS score_date, 
						DATE_FORMAT( q.answer_before_date, '%Y-%m-%d %H:%i' ) AS answer_before_date, q.match_id,
						qt.type, qt.options, qt.image, qt.max_answers, q.question_order
					FROM {$prefix}bonusquestions q 
					INNER JOIN {$prefix}bonusquestions_type qt
						ON ( q.id = qt.question_id )
					ORDER BY {$sorting}";
		
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			$this->has_bonus_questions = ( count( $rows ) > 0 );
			
			$question_info = [];
			foreach ( $rows as $row ) {
				$i = (int) $row['id'];
				$question_date = new DateTime( $row['question_date'] );
				$ts = $question_date->format( 'U' );
				
				$question_info[$i] = [];
				$question_info[$i]['id'] = $i;
				$question_info[$i]['question'] = $row['question'];
				$question_info[$i]['answer'] = $row['answer'];
				$question_info[$i]['points'] = $row['points'];
				// $question_info[$i]['question_date'] = $ts;
				$question_info[$i]['question_timestamp'] = $ts;
				$question_info[$i]['score_date'] = $row['score_date'];
				$question_info[$i]['answer_before_date'] = $row['answer_before_date'];
				$question_info[$i]['match_id'] = (int) $row['match_id'];
				$question_info[$i]['type'] = (int) $row['type'];
				$question_info[$i]['options'] = $row['options'];
				$question_info[$i]['image'] = $row['image'];
				$question_info[$i]['max_answers'] = $row['max_answers'];
				$question_info[$i]['question_order'] = $row['question_order'];
			}
			
			$question_info = apply_filters( 'footballpool_questions', $question_info );
			wp_cache_set( FOOTBALLPOOL_CACHE_QUESTIONS, $question_info, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		}
		
		return $question_info;
	}

	/**
	 * @param int $id
	 * @return array|mixed
	 */
	public function get_bonus_question( int $id ) {
		return $this->get_bonus_question_info( $id );
	}

	/**
	 * @param int $id
	 * @return array|mixed
	 */
	public function get_bonus_question_info( int $id ) {
		$info = false;
		$questions = $this->get_bonus_questions();
		if ( is_array( $questions ) && array_key_exists( $id, $questions ) ) {
			$info = $questions[$id];
			$info['question_is_editable'] = $this->question_is_editable( $info['question_timestamp'] );
		}
		return $info;
	}

	/**
	 * @param array $question
	 * @return string
	 */
	private function bonus_question_form_input( array $question ) {
		switch ( $question['type'] ) {
			case 2: // multiple 1, radio
				return $this->bonus_question_multiple( $question, 'radio' );
			case 5: // multiple 1, select
				return $this->bonus_question_multiple( $question, 'select' );
			case 3: // multiple n
				return $this->bonus_question_multiple( $question, 'checkbox' );
			case 4: // multiline text
				return $this->bonus_question_single( $question, 'multiline' );
			case 1: // text
			default:
				return $this->bonus_question_single( $question );
		}
	}

	/**
	 * @param array $question
	 * @param string|bool $multiline
	 * @return string
	 */
	private function bonus_question_single( array $question, $multiline = false ): string
	{
		if ( $multiline === 'multiline' ) {
			return sprintf(
				'<textarea class="bonus multiline" name="_bonus_%1$d" id="_bonus_%1$d">%2$s</textarea>'
				, esc_attr( $question['id'] )
				, esc_attr( $question['user_answer'] )
			);
		} else {
			return sprintf(
				'<input maxlength="200" class="bonus" name="_bonus_%1$d" id="_bonus_%1$d" type="text" value="%2$s">'
				, esc_attr( $question['id'] )
				, esc_attr( $question['user_answer'] )
			);
		}
	}
	
	/**
	 * @param array $question
	 * @param string $type radio, checkbox or select
	 * @return string
	 */
	private function bonus_question_multiple( array $question, string $type = 'radio' ): string
	{
		$options = explode( ';', $question['options'] );
		// strip out any empty options
		// $options = array_filter( $options, function( $option ) { 
						// return ( str_replace( array( ' ', "\t", "\r", "\n" ), '', $option ) != '' ); 
					// } );
		$temp = array();
		foreach ( $options as $option ) {
			if ( str_replace( array( ' ', "\t", "\r", "\n" ), '', $option ) !== '' ) $temp[] = $option;
		}
		$options = $temp;
		// bail out if there are no options
		if ( count( $options ) === 0 ) return '';

		$output = '';
		if ( $type === 'select' || $type === 'dropdown' ) {
			// dropdown
			array_unshift( $options, '' );
			if ( $question['user_answer'] !== '' ) array_shift( $options );
			$options = array_combine( $options, $options );
			$output .= '<div class="multi-select dropdown">';
			$output .= Football_Pool_Utils::select(
				'_bonus_' . esc_attr( $question['id'] ),
				$options,
				Football_Pool_Utils::xssafe( $question['user_answer'] ),
				null,
				'bonus'
			);
			$output .= '</div>';
		} else {
			// radio or checkbox
			if ( $type === 'checkbox' && $question['max_answers'] > 0 ) {
				// add some javascript for the max number of answers a user may give
				$output .= "<script>
							jQuery( document ).ready( function() { 
								FootballPool.set_max_answers( {$question['id']}, {$question['max_answers']} );
							} );
							</script>";
			}
			
			$i = 1;
			$output .= '<ul class="multi-select">';
			foreach ( $options as $option ) {
				$js = sprintf( 'onclick="jQuery( \'#_bonus_%d_userinput\' ).val( \'\' )"', $question['id'] );
				
				if ( $type === 'checkbox' ) {
					$checked = in_array( $option, explode( ';', $question['user_answer'] ) ) ? 'checked="checked" ' : '';
					$brackets = '[]';
					$user_input = '';
				} else {
					// TODO: change this very hacky (and therefore undocumented) feature of adding a text input
					//       after a radio input
					if ( substr( $option, -2 ) === '[]' ) {
						$js = '';
						
						$option = substr( $option, 0, -2 );
						$len = strlen( $option );
						$checked = substr( $question['user_answer'], 0, $len ) == $option ? 'checked="checked" ' : '';
						
						$user_input_name = sprintf( '_bonus_%d_userinput', esc_attr( $question['id'] ) );
						$user_input_value = ( $checked ) ? substr( $question['user_answer'], $len + 1 ) : '';
						$user_input = sprintf(
							'<span> <input type="text" id="%1$s" name="%1$s" value="%2$s" 
								class="radio_userinput bonus" 
								onclick="jQuery( \'#_bonus_%3$d_%4$d\' ).attr( \'checked\', \'checked\' )"></span>'
							, $user_input_name
							, $user_input_value
							, $question['id']
							, $i
						);
					} else {
						$user_input = '';
						$checked = ( $question['user_answer'] == $option ) ? 'checked="checked"' : '';
					}
					$brackets = '';
				}

				$css_class = 'bonus';
				if ( $user_input !== '' ) $css_class .= ' user-input';

				$output .= sprintf(
					'<li>
						<input class="%9$s" %8$s id="_bonus_%2$d_%7$d" type="%1$s" name="_bonus_%2$d%5$s" value="%3$s" %4$s>
						<label for="_bonus_%2$d_%7$d" %9$s><span class="multi-option"> %3$s</span></label>%6$s</li>'
					, $type
					, esc_attr( $question['id'] )
					, esc_attr( $option )
					, $checked
					, $brackets
					, $user_input
					, $i++
					, $js
					, $css_class
				);
			}
			
			$output .= '</ul>';
		}
		
		return $output;
	}

	/**
	 * @param array $question
	 * @param int|string $nr
	 * @param bool $is_user_page
	 * @return string|string[]
	 */
	public function print_bonus_question( array $question, $nr, bool $is_user_page = false ) {
		$question_is_editable = $this->question_is_editable( $question['question_timestamp'] );
		$show_admin_answer = Football_Pool_Utils::get_fp_option( 'user_page_show_correct_question_answer', false );

		$status_css_class = $question_is_editable ? 'open' : 'closed';
		if ( $is_user_page === true ) {
			$status_css_class .= ' userview';
		}

		if ( is_int( $nr ) ) {
			$nr = sprintf( '<span class="nr">%d.</span> ', $nr );
		}

		if ( $question['image'] !== '' ) {
			$question_img = sprintf( '<p class="bonus image"><img src="%s" alt="%s"></p>'
				, Football_Pool_Utils::xssafe( $question['image'] )
				, __( 'photo question', 'football-pool' )
			);
		} else {
			$question_img = '';
		}

		// get the date format
		if ( defined( 'FOOTBALLPOOL_QUESTIONDATETIME_FORMAT' ) ) {
			$question_date_format = FOOTBALLPOOL_QUESTIONDATETIME_FORMAT;
		} else {
			$question_date_format = sprintf( '%s %s'
				, get_option( 'date_format', FOOTBALLPOOL_DATE_FORMAT )
				, get_option( 'time_format', FOOTBALLPOOL_TIME_FORMAT )
			);
		}
		// get the date (in UTC)
		$lock_time = ( $this->force_lock_time ) ? $this->lock_datestring : $question['answer_before_date'];
		// convert to timezone
		$lock_time = Football_Pool_Utils::date_from_gmt( $lock_time );
		// to localized date string
		$lock_time = date_i18n( $question_date_format,
			DateTime::createFromFormat( 'Y-m-d H:i', $lock_time )->format( 'U' ) );

		if ( $question_is_editable ) {
			if ( ! $is_user_page ) {
				$user_answer = sprintf('<p>%s</p>', $this->bonus_question_form_input( $question ) );
			} else {
				$user_answer = '';
			}
		} else {
			$user_answer = sprintf( '<p class="answer" id="bonus-%d">%s: '
				, $question['id']
				, _x( 'my answer', 'label before the user answer on the Predictions page', 'football-pool' )
			);
			if ( $question['type'] == 4 ) $user_answer .= '<br>';
			if ( $question['user_answer'] === '' || $question['user_answer'] === null ) {
				$user_answer .= '<span class="no-answer"></span>';
			} else {
				$user_answer .= nl2br( Football_Pool_Utils::xssafe( $question['user_answer'], null, false ) );
			}
			$user_answer .= '</p>';
		}

		// remind a player if there is only 1 day left to answer the question.
		$timestamp = ( $this->force_lock_time ? $this->lock_timestamp : $question['question_timestamp'] );
		$less_than_one_day_left = ( $timestamp - current_time( 'timestamp' ) ) <= ( DAY_IN_SECONDS );
		if ( ! $is_user_page && $question_is_editable && $less_than_one_day_left ) {
			$notice = sprintf( '<div class="reminder">%s </div>', __( 'Important:', 'football-pool' ) );
		} else {
			$notice = '';
		}

		if ( $question_is_editable ) {
			$closing_time = sprintf( '<div class="closing-time" title="%s">%s %s</div>'
				, __( 'answer this question before this date', 'football-pool' )
				, __( 'answer before', 'football-pool' )
				, $lock_time
			);
		} else {
			$closing_time = sprintf( '<div class="closing-time" title="%s">%s %s</div>'
				, __( "it's is no longer possible to answer this question, or change your answer", 'football-pool' )
				, __( 'closed on', 'football-pool' )
				, $lock_time
			);
		}

		if ( $show_admin_answer && $is_user_page ) {
			$admin_answer = sprintf( '<p class="answer correct-answer" id="bonus-%d-correct">%s: '
				, $question['id']
				, _x( 'correct answer', 'label before the correct answer on the Predictions page', 'football-pool' )
			);
			if ( $question['type'] == 4 ) {
				$admin_answer .= '<br>';
			}
			if ( $question['question_answer'] !== '' ) {
				$admin_answer .= nl2br( Football_Pool_Utils::xssafe( $question['question_answer'], null, false ) );
			} else {
				$admin_answer .= '<span class="no-answer"></span>';
			}
			$admin_answer .= '</p>';
		} else {
			$admin_answer = '';
		}

		$points = ( $question['points'] == 0 ) ? __( 'variable', 'football-pool' ) : $question['points'];

		$question_value = sprintf( '%s: %s %s'
			, __( 'question value', 'football-pool' )
			, $points
			, _n( 'point', 'points', $points, 'football-pool' )
		);

		if ( $question_is_editable ) {
			$scored_points = $stats_link = '';
		} else {
			if ( $question['correct'] == 1 ) {
				$points = ( $question['user_points'] != 0 ) ? $question['user_points'] : $question['points'];
			} else {
				$points = 0;
			}
			$scored_points = sprintf( '%s: %s %s'
				, __( 'points awarded', 'football-pool' )
				, $points
				, _n( 'point', 'points', $points, 'football-pool' )
			);

			$stats_link = sprintf( '<div class="question-stats-link"><a title="%s" href="%s">',
				__( 'view other users answers', 'football-pool' )
				, esc_url(
					add_query_arg(
						array( 'view' => 'bonusquestion', 'question' => $question['id'] ),
						Football_Pool::get_page_link( 'statistics' )
					)
				)
			);
			$stats_link .= sprintf( '<img class="pie-chart-icon" alt="%s" src="%sassets/images/pie-chart.svg">',
				__( 'view other users answers', 'football-pool' ), FOOTBALLPOOL_PLUGIN_URL );
			$stats_link .= '</a></div>';
		}

		$template = '<div class="bonus %status_css%" id="q%id%">
						<p>%nr%%question%</p>
						%image%
						%user_answer%
						%admin_answer%
						<div class="bonus-card-footer">
							%notice% %closing_time%
							<div class="points">
								%question_value%<br>
								%scored_points%
							</div>
							%stats_link%
						</div>
						<div class="bonus-ajax-loader"></div>
					</div>';
		$template = apply_filters( 'footballpool_print_bonus_question_template', $template, $is_user_page );

		$params = array(
			'id' => $question['id'],
			'nr' => $nr,
			'question' => Football_Pool_Utils::xssafe( $question['question'] ),
			'status_css' => $status_css_class,
			'image' => $question_img,
			'user_answer' => $user_answer,
			'notice' => $notice,
			'closing_time' => $closing_time,
			'admin_answer' => $admin_answer,
			'question_value' => $question_value,
			'scored_points' => $scored_points,
			'stats_link' => $stats_link,
			'state' => ( $question_is_editable ? 'open' : 'closed' ),
		);

		$params = apply_filters( 'footballpool_print_bonus_question_params'
			, $params, $question, $nr, $is_user_page );

		return Football_Pool_Utils::placeholder_replace( $template, $params );
	}

	/**
	 * @param array $questions
	 * @return string
	 */
	public function print_bonus_question_for_user( array $questions ): string
	{
		$output = '';
		if ( count( $questions ) === 0 ) return $output;

		$nr = 1;
		foreach ( $questions as $question ) {
			$question_is_editable = $this->question_is_editable( $question['question_timestamp'] );
			if ( ! $question_is_editable ) {
				$output .= $this->print_bonus_question( $question, $nr++, true );
			}
		}

		return $output;
	}

	// updates the predictions for a submitted prediction form
	public function prediction_form_update( $id = null ): string
	{
		$user_id = get_current_user_id();
		
		$msg = '';
		
		if ( $id === null || Football_Pool_Utils::post_int( '_fp_form_id' ) === $id ) {
			$user_is_player = $this->user_is_player( $user_id );
			
			if ( $user_id !== 0 && $user_is_player
			        && Football_Pool_Utils::post_string( '_fp_action' ) === 'update' ) {
				$nonce = Football_Pool_Utils::post_string( FOOTBALLPOOL_NONCE_BLOG_INPUT_NAME );
				$success = ( wp_verify_nonce( $nonce, FOOTBALLPOOL_NONCE_BLOG ) !== false );
				if ( $success ) {
					$success = $this->update_predictions( $user_id );
				}
				if ( $success ) {
					// TODO: differentiate in messages (was there actually a save?)
					$msg = sprintf(
						'<p class="fp-notice updated">%s</p>', __( 'Changes saved.', 'football-pool' )
					);
				} else {
					$msg = sprintf(
						'<p class="fp-notice error">%s</p>'
						, __( 'Something went wrong during the save. Check if you are still logged in. If the problems persist, then contact your webmaster.', 'football-pool' )
					);
				}
			}
		}
		
		return $msg;
	}

	/**
	 * @param array $questions
	 * @param array $answers
	 * @param int $user
	 * @return void
	 */
	private function update_bonus_user_answers( array $questions, array $answers, int $user ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		// first get the user's previous answers to questions
		$previous_answers = [];
		$sql = $wpdb->prepare( "SELECT question_id, answer FROM {$prefix}bonusquestions_useranswers 
								WHERE user_id = %d ORDER BY question_id ASC", $user );
		$rows = $wpdb->get_results( $sql, ARRAY_A );
		foreach ( $rows as $row ) {
			$previous_answers[$row['question_id']] = $row['answer'];
		}
		
		foreach ( $questions as $question ) {
			$do_update = true;
			$question_id = $question['id'];
			$answer = $answers[$question_id];
			
			if ( $this->question_is_editable( $question['question_timestamp'] ) && $answer !== '' ) {
				$do_update = apply_filters( 'footballpool_prediction_update_question', $do_update, $user, $question_id, $answer );
				if ( $do_update ) {
					$question_save_needed = $result = false;
					if ( array_key_exists( $question_id, $previous_answers ) ) {
						// question exists in previous answers, check if user wants to change the answer
						if ( $previous_answers[$question_id] !== $answer ) {
							$question_save_needed = true;
							$sql = $wpdb->prepare( "UPDATE {$prefix}bonusquestions_useranswers 
													SET answer = %s, points = 0
													WHERE user_id = %d AND question_id = %d"
													, $answer, $user, $question_id );
							$result = $wpdb->query( $sql );
						}
					} else {
						// no answer yet, insert it
						$question_save_needed = true;
						$sql = $wpdb->prepare( "INSERT INTO {$prefix}bonusquestions_useranswers 
													( user_id, question_id, answer )
												VALUES ( %d, %d, %s )" 
												, $user, $question_id, $answer );
						$result = $wpdb->query( $sql );
					}

					if ( $question_save_needed === true ) {
						if ( $result !== false ) {
							Football_Pool_Utils::log_message( $user, FOOTBALLPOOL_TYPE_QUESTION, $question_id, 1,
								"Question {$question_id}: value '{$answer}' saved."
							);
						} else {
							Football_Pool_Utils::log_error_message( $user, FOOTBALLPOOL_TYPE_QUESTION, $question_id );
						}
					}
				}
			}
		}
	}

	/**
	 * This function saves the entire prediction form. This function is used when a user saved via a
	 * form submit on the frontend. The individual saves via AJAX have their own handler.
	 *
	 * @param int $user_id
	 * @return bool
	 * @throws Exception
	 */
	private function update_predictions( int $user_id ): bool
	{
		do_action( 'footballpool_prediction_save_before', $user_id );
		
		// Only allow logged-in users and players in the pool to update their predictions
		if ( $user_id <= 0 || ! $this->user_is_player( $user_id ) ) return false;
		
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;

		$joker = $this->get_joker();
		$user_match_info = $this->matches->get_match_info_for_user_unfiltered( $user_id );

		// Update predictions for all matches.
		$jokers = explode( ',', $joker );
		foreach ( $this->matches->all_matches as $row ) {
			$match_id = $row['id'];
			$home = Football_Pool_Utils::post_integer( '_home_' . $match_id, -1 );
			$away = Football_Pool_Utils::post_integer( '_away_' . $match_id, -1 );
			$do_update = true;
			
			if ( $row['match_is_editable'] ) {
				$do_update = apply_filters( 'footballpool_prediction_update_match'
											, $do_update, $user_id, $match_id, $home, $away );
				
				if ( $do_update ) {
					// todo: change this to also accept partial (null value) saves as we also do with AJAX saves?
					if ( $home >= 0 && $away >= 0 ) {
						if ( $user_match_info[$match_id]['has_prediction'] ) {
							// match exists in predictions, check if user wants to change the prediction
							if ( $user_match_info[$match_id]['home_score'] != $home
								|| $user_match_info[$match_id]['away_score'] != $away ) {
								$sql = $wpdb->prepare(
									"UPDATE {$prefix}predictions SET
										home_score = %d, away_score = %d
									WHERE user_id = %d AND match_id = %d",
									$home, $away, $user_id, $match_id
								);
								$result = $wpdb->query( $sql );
								if ( $result !== false ) {
									$user_match_info[$match_id]['home_score'] = $home;
									$user_match_info[$match_id]['away_score'] = $away;
									$match = $this->matches->all_matches[$match_id];
									$match = "{$match['home_team']}-{$match['away_team']}";
									Football_Pool_Utils::log_message( $user_id, FOOTBALLPOOL_TYPE_MATCH, $match_id, 1,
										"Match {$match_id}: {$match} value '{$home}-{$away}' saved."
									);
								} else {
									Football_Pool_Utils::log_error_message( $user_id, FOOTBALLPOOL_TYPE_MATCH, $match_id );
								}
							}
						} else {
							// no prediction yet, insert the prediction
							$sql = $wpdb->prepare(
								"INSERT INTO {$prefix}predictions
									( user_id, match_id, home_score, away_score, has_joker )
								VALUES ( %d, %d, %d, %d, %d )",
								$user_id, $match_id, $home, $away, 0
							);
							$result = $wpdb->query( $sql );

							if ( $result !== false ) {
								$match = $this->matches->all_matches[$match_id];
								$match = "{$match['home_team']}-{$match['away_team']}";
								Football_Pool_Utils::log_message( $user_id, FOOTBALLPOOL_TYPE_MATCH, $match_id, 1,
									"Match {$match_id}: {$match} value '{$home}-{$away}' saved."
								);
							} else {
								Football_Pool_Utils::log_error_message( $user_id, FOOTBALLPOOL_TYPE_MATCH, $match_id );
							}
						}
					}
				}
			}
		}

		// Process the multipliers.
		foreach ( $user_match_info as $match ) {
			// We only touch matches that are editable.
			if ( $match['match_is_editable'] === true ) {
				$match_id = (int)$match['id'];
				$match_name = "{$match['home_team']}-{$match['away_team']}";

				if ( in_array( $match_id, $jokers ) ) {
					// The match ID is in the list of multipliers. Check if multiplier is allowed.
					if ( $this->joker_allowed( $match_id, $user_match_info, $user_id ) ) {
						// We may set the multiplier.
						$sql = $wpdb->prepare(
							"UPDATE {$prefix}predictions SET has_joker = 1 WHERE user_id = %d AND match_id = %d"
							, $user_id, $match_id
						);
						$result = $wpdb->query( $sql );

						if ( $result !== false && $result !== 0 ) {
							$user_match_info[$match_id]['has_joker'] = 1;
							Football_Pool_Utils::log_message(
								$user_id, FOOTBALLPOOL_TYPE_MATCH, $match_id, 1,
								"Match {$match_id}: {$match_name} multiplier action: set"
							);
						} else {
							Football_Pool_Utils::log_message( $user_id, FOOTBALLPOOL_TYPE_MATCH, $match_id, 0,
								"Match {$match_id}: {$match_name} multiplier action failed."
							);
						}
					}
				} else {
					// The match ID is not in the list of multipliers. Check if we need to remove it.
					if ( $match['has_joker'] === 1 ) {
						// We should remove the multiplier.
						$sql = $wpdb->prepare(
							"UPDATE {$prefix}predictions SET has_joker = 0 WHERE user_id = %d AND match_id = %d"
							, $user_id, $match_id
						);
						$result = $wpdb->query( $sql );
						if ( $result !== false && $result !== 0 ) {
							$user_match_info[$match_id]['has_joker'] = 0;
							Football_Pool_Utils::log_message(
								$user_id, FOOTBALLPOOL_TYPE_MATCH, $match_id, 1,
								"Match {$match_id}: {$match_name} multiplier action: clear"
							);
						} else {
							Football_Pool_Utils::log_message( $user_id, FOOTBALLPOOL_TYPE_MATCH, $match_id, 0,
								"Match {$match_id}: {$match_name} multiplier action failed."
							);
						}
					}
				}
			}
		}

		// We reset the cached match info for this user because some predictions may have changed.
		$cache_key = "match_info_for_user_{$user_id}";
		wp_cache_delete( $cache_key, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );

		// Prepare the answers for the bonus questions update
		// (get_bonus_questions also sets the has_bonus_questions var)
		$questions = $this->get_bonus_questions();
		if ( $this->has_bonus_questions ) {
			$answers = [];
			foreach ( $questions as $question ) {
				switch ( $question['type'] ) {
					case 3: // multiple n (checkbox)
						$user_answers = Football_Pool_Utils::post_string_array( '_bonus_' . $question['id'] );
						if ( $question['max_answers'] > 0 && count( $user_answers ) > $question['max_answers'] ) {
							// Remove answers from the end of the array
							// (user is cheating or admin changed the max possible answers).
							while ( count( $user_answers ) > $question['max_answers'] ) {
								array_pop( $user_answers );
							}
						}
						$answers[$question['id']] = implode( ';', $user_answers );
						break;
					case 1: // text (input)
					case 4: // text (textarea)
					case 2: // multiple 1 (radio)
					case 5: // multiple 1 (dropdown)
					default:
						$answers[$question['id']] = Football_Pool_Utils::post_string( '_bonus_' . $question['id'] );
				}
				
				// Add user input to answer (for multiple choice questions) if there is some input.
				$user_input = Football_Pool_Utils::post_string( '_bonus_' . $question['id'] . '_userinput' );
				if ( $user_input !== '' ) $answers[$question['id']] .= " {$user_input}";
			}
			
			// Update bonus questions.
			$this->update_bonus_user_answers( $questions, $answers, $user_id );
		}
		
		do_action( 'footballpool_prediction_save_after', $user_id );
		return true;
	}

	/**
	 * Returns the amount of jokers that a user may set.
	 * The returned value can be absolute for the entire pool, or is valid per match type (depending on the setting).
	 *
	 * @return int
	 */
	public function get_amount_of_jokers_allowed(): int
	{
		if ( Football_Pool_Utils::get_fp_option( 'jokers_enabled', 0, 'int') !== 0 ) {
			$num_jokers = Football_Pool_Utils::get_fp_option( 'number_of_jokers', FOOTBALLPOOL_DEFAULT_JOKERS, 'int' );
		} else {
			$num_jokers = 0;
		}
		return (int) apply_filters( 'footballpool_get_jokers', $num_jokers );
	}

	/**
	 * Returns the (comma-separated) value from the _joker hidden input field.
	 *
	 * @return string
	 */
	private function get_joker(): string
	{
		return Football_Pool_Utils::post_string( '_joker', '0' );
	}

	/**
	 * Checks if a user may set the joker on a given match.
	 *
	 * @param $joker            int   contains the match ID on which we would like to set the joker
	 * @param $user_match_info  array the match info for this user (predictions)
	 * @param $user_id          int   user ID
	 *
	 * @return bool
	 */
	private function joker_allowed( $joker, $user_match_info, $user_id ) {
		$allowed = $match_is_editable = false;
		$match_type_for_joker = -1;

		// Check if match is editable and get the match type of the match.
		if ( array_key_exists( $joker, $user_match_info ) ) {
			$match_type_for_joker = $user_match_info[$joker]['match_type_id'];
			$match_is_editable = $user_match_info[$joker]['match_is_editable'];
		}

		// When jokers are disabled in the settings or the match is not editable we should return false.
		if ( $match_is_editable && Football_Pool_Utils::get_fp_option( 'jokers_enabled', 0, 'int') !== 0 ) {
			// number_of_jokers
			$number_of_jokers = $this->get_amount_of_jokers_allowed();

			// loop through the user match info and count all jokers; totals and per match type
			$joker_count = array(
				// 0:       total of all jokers
				// 1..x:    total for match type id
			);
			$joker_count[0] = 0;

			foreach ( $user_match_info as $prediction ) {
				if ( $prediction['has_joker'] === 1 ) {
					$joker_count[0]++;

					$id = (int) $prediction['match_type_id'];
					if ( array_key_exists( $id, $joker_count ) ) {
						$joker_count[$id]++;
					} else {
						$joker_count[$id] = 1;
					}
				}
			}

			// jokers_per: 1 = pool / 2 = match type
			if ( $this->jokers_per === 1 ) {
				$allowed = $joker_count[0] < $number_of_jokers;
			} elseif ( $this->jokers_per === 2 ) {
				$match_type_counted = array_key_exists( $match_type_for_joker, $joker_count );
				$allowed = $match_type_counted === false || $joker_count[$match_type_for_joker] < $number_of_jokers;
			}
		}

		return apply_filters( 'footballpool_joker_allowed', $allowed, $joker, $user_match_info, $user_id );
	}

	/**
	 * @param string $jokers
	 * @param int $user_id
	 * @return bool
	 */
	private function save_jokers_in_predictions( string $jokers, int $user_id ): bool
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;

		if ( strlen( $jokers ) === 0 ) $jokers = '0';

		// Remove disabled jokers
		$sql = $wpdb->prepare( "UPDATE {$prefix}predictions SET has_joker = 0
								WHERE match_id NOT IN ( {$jokers} ) AND user_id = %d AND has_joker = 1",
				$user_id
			);
		$result = ( $wpdb->query( $sql ) !== false );

		if ( $result === true ) {
			// Add the new jokers for existing rows.
			$sql = $wpdb->prepare( "UPDATE {$prefix}predictions SET has_joker = 1
									WHERE match_id IN ( {$jokers} ) AND user_id = %d AND has_joker = 0",
					$user_id
				);
			$result = ( $wpdb->query( $sql ) !== false );

			// Add new jokers for non-existing rows in the prediction table.
			$sql = $wpdb->prepare( "SELECT match_id FROM {$prefix}predictions 
									WHERE match_id IN ( {$jokers} ) AND user_id = %d", $user_id );
			$existing_rows = $wpdb->get_col( $sql );
			$joker_match_ids = explode( ',', $jokers );
			$new_rows = array_diff( $joker_match_ids, $existing_rows );
			foreach ( $new_rows as $match_id ) {
				$sql = $wpdb->prepare(
					"INSERT INTO {$prefix}predictions ( user_id, match_id, home_score, away_score, has_joker )
					VALUES ( %d, %d, NULL, NULL, 1 )", $user_id, $match_id
				);
				$result = $result && ( $wpdb->query( $sql ) !== false );
			}
		}

		return $result;
	}

	/**
	 * This function is the handler for the AJAX calls in the frontend when a user changes a question.
	 */
	// todo: fix ajax save of custom user input for radio []
	public static function update_question() {
		check_ajax_referer( FOOTBALLPOOL_NONCE_PREDICTION_SAVE, 'fp_question_nonce' );

		$user_id = get_current_user_id();
		$question_id = Football_Pool_Utils::post_int( 'question' );
		$allowed_types = ['text','textarea','radio','checkbox','select-one'];
		$type = Football_Pool_Utils::post_enum( 'type', $allowed_types, $allowed_types[0] );
		$answer = Football_Pool_Utils::post_string( 'answer' );

		// get question info for this user
		$pool = new Football_Pool_Pool( FOOTBALLPOOL_DEFAULT_SEASON );
		$user_question_info = $pool->get_bonus_questions_for_user( $user_id );

		$params = array();
		$params['return_code'] = true;
		$params['msg'] = '';
		$params['prev_answer'] = isset( $question['user_answer'] ) ? $question['answer'] : null;

		do_action( 'footballpool_question_answer_update_before', $params );

		if ( $user_id <= 0 || ! $pool->user_is_player( $user_id ) ) {
			$params['return_code'] = false;
			$params['msg'] = __( 'Permission denied!', 'football-pool' );
			Football_Pool_Utils::log_message( $user_id, FOOTBALLPOOL_TYPE_QUESTION, $question_id, 0, $params['msg'] );
		} else {
			$question = array_key_exists( $question_id, $user_question_info ) ?
				$user_question_info[$question_id] : null;

			if ( $question === null || ! $question['question_is_editable'] ) {
				$params['return_code'] = false;
				$params['msg'] = __( 'Changing this prediction is not allowed.', 'football-pool' );
				Football_Pool_Utils::log_message(
					$user_id, FOOTBALLPOOL_TYPE_QUESTION, $question_id, 0, $params['msg']
				);
			} else {
				// good to go, let's save the prediction
				global $wpdb;
				$prefix = FOOTBALLPOOL_DB_PREFIX;

				// for checkboxes with a max number of answers we have to check the saved answer
				if ( $type === 'checkbox' && $question['max_answers'] > 0 ) {
					$user_answers = explode( ';', $answer );
					if ( count( $user_answers ) > $question['max_answers'] ) {
						// remove answers from the end of the array
						// (because user is cheating or admin changed the max possible answers)
						while ( count( $user_answers ) > $question['max_answers'] ) {
							array_pop( $user_answers );
						}
						$answer = implode( ';', $user_answers );
					}
				}

				if ( array_key_exists( $question_id, $user_question_info )
					&& $user_question_info[$question_id]['user_answer'] !== null ) {
					$sql = $wpdb->prepare( "UPDATE {$prefix}bonusquestions_useranswers 
											SET answer = %s WHERE user_id = %d AND question_id = %d",
						$answer, $user_id, $question_id );
				} else {
					$sql = $wpdb->prepare( "INSERT INTO {$prefix}bonusquestions_useranswers 
											( user_id, question_id, answer ) VALUES ( %d, %d, %s )",
						$user_id, $question_id, $answer );
				}

				$result = $wpdb->query( $sql );
				if ( $result === false ) {
					$params['return_code'] = false;
					$params['msg'] = __( 'Something went wrong while saving the prediction.', 'football-pool' );
					Football_Pool_Utils::log_error_message( $user_id, FOOTBALLPOOL_TYPE_QUESTION, $question_id );
				} else {
					Football_Pool_Utils::log_message( $user_id, FOOTBALLPOOL_TYPE_QUESTION, $question_id, 1,
						"Question {$question_id}: value '{$answer}' saved."
					);
				}
			}
		}

		$params = apply_filters( 'footballpool_prediction_params', $params );
		do_action( 'footballpool_question_answer_update_after', $params );

		// return the result
		Football_Pool_Utils::ajax_response( $params );
	}

	/**
	 * This function is the handler for the AJAX calls in the frontend when a user changes a prediction.
	 */
	public static function update_prediction() {
		check_ajax_referer( FOOTBALLPOOL_NONCE_PREDICTION_SAVE, 'fp_match_nonce' );

		$user_id = get_current_user_id();
		$match_id = Football_Pool_Utils::post_int( 'match' );
		$type = Football_Pool_Utils::post_enum( 'type', ['home','away'], 'home' ); // home or away
		$prediction = Football_Pool_Utils::post_string( 'prediction' );

		// get predictions for this user
		$pool = new Football_Pool_Pool( FOOTBALLPOOL_DEFAULT_SEASON );
		$matches = $pool->matches;
		$user_match_info = $matches->get_match_info_for_user_unfiltered( $user_id );

		$params = array();
		$params['return_code'] = true;
		$params['msg'] = '';
		$params['prev_prediction'] = $user_match_info[$match_id]["{$type}_score"] ?? null;

		do_action( 'footballpool_prediction_update_before', $params );

		if ( $user_id <= 0 || ! $pool->user_is_player( $user_id ) ) {
			$params['return_code'] = false;
			$params['msg'] = __( 'Permission denied!', 'football-pool' );
			Football_Pool_Utils::log_message( $user_id, FOOTBALLPOOL_TYPE_MATCH, $match_id, 0, $params['msg'] );
		} else {
			if ( ! isset( $user_match_info[$match_id] ) || ! $user_match_info[$match_id]['match_is_editable'] ) {
				$params['return_code'] = false;
				$params['msg'] = __( 'Changing this prediction is not allowed.', 'football-pool' );
				Football_Pool_Utils::log_message( $user_id, FOOTBALLPOOL_TYPE_MATCH, $match_id, 0, $params['msg'] );
			} else {
				// Good to go, let's save the prediction.
				global $wpdb;
				$prefix = FOOTBALLPOOL_DB_PREFIX;

				if ( $prediction === '' || ! is_numeric( $prediction ) ) {
					if ( $user_match_info[$match_id]['has_prediction'] === true ) {
						// prediction exists, so update it
						$sql = $wpdb->prepare(
							"UPDATE {$prefix}predictions SET {$type}_score = NULL 
							WHERE user_id = %d AND match_id = %d"
							, $user_id, $match_id
						);
					} else {
						// no values yet, so we can safely insert null for both scores
						$sql = $wpdb->prepare(
							"INSERT INTO {$prefix}predictions ( user_id, match_id, home_score, away_score, has_joker ) 
							VALUES ( %d, %d, NULL, NULL, 0 )"
							, $user_id, $match_id
						);
					}
				} else {
					$prediction = (int)$prediction;

					if ( $user_match_info[$match_id]['has_prediction'] === true ) {
						$sql = $wpdb->prepare(
							"UPDATE {$prefix}predictions SET {$type}_score = %d
							WHERE user_id = %d AND match_id = %d"
							, $prediction, $user_id, $match_id
						);
					} else {
						// determine which value to set
						if ( $type === 'home' ) {
							$values = "{$prediction}, NULL";
						} else {
							$values = "NULL, {$prediction}";
						}
						$sql = $wpdb->prepare(
							"INSERT INTO {$prefix}predictions ( user_id, match_id, home_score, away_score, has_joker ) 
							VALUES ( %d, %d, {$values}, 0 )"
							, $user_id, $match_id
						);
					}
				}

				$result = $wpdb->query( $sql );
				if ( $result === false ) {
					$params['return_code'] = false;
					$params['msg'] = __( 'Something went wrong while saving the prediction.', 'football-pool' );
					Football_Pool_Utils::log_error_message( $user_id, FOOTBALLPOOL_TYPE_MATCH, $match_id );
				} else {
					$match = $pool->matches->all_matches[$match_id];
					$match = "{$match['home_team']}-{$match['away_team']}";
					Football_Pool_Utils::log_message( $user_id, FOOTBALLPOOL_TYPE_MATCH, $match_id, 1,
						"Match {$match_id}: {$match} {$type} value '{$prediction}' saved."
					);
				}
			}
		}

		$params = apply_filters( 'footballpool_prediction_params', $params, $user_match_info );
		do_action( 'footballpool_prediction_update_after', $params );

		// return the result
		Football_Pool_Utils::ajax_response( $params );
	}

	/**
	 * This function is the handler for the AJAX calls in the frontend when a user clicks a joker.
	 */
	public static function update_joker() {
		check_ajax_referer( FOOTBALLPOOL_NONCE_PREDICTION_SAVE, 'fp_joker_nonce' );

		$pool = new Football_Pool_Pool( FOOTBALLPOOL_DEFAULT_SEASON );

		$user_id = get_current_user_id();
		$joker = Football_Pool_Utils::post_int( 'joker' );
		$user_match_info = array();

		$params = [];
		$params['return_code'] = true;
		$params['msg'] = '';
		$params['user_id'] = $user_id;
		$params['joker'] = $joker;

		do_action( 'footballpool_joker_update_before', $params );

		if ( ! $pool->has_jokers ) {
			$params['return_code'] = false;
			$params['msg'] = __( 'Multipliers are not enabled in the plugin options.', 'football-pool' );
		} else {
			if ( $user_id <= 0 || ! $pool->user_is_player( $user_id ) ) {
				$params['return_code'] = false;
				$params['msg'] = __( 'Permission denied!', 'football-pool' );
			} else {
				// get predictions for this user
				$matches = $pool->matches;
				$user_match_info = $matches->get_match_info_for_user_unfiltered( $user_id );

				$home = $matches->all_matches[$joker]['home_team'] ?? '?';
				$away = $matches->all_matches[$joker]['away_team'] ?? '?';

				// now check if editing the prediction is allowed, if not, we cancel the action
				if ( ! isset( $user_match_info[$joker] ) || ! $user_match_info[$joker]['match_is_editable']  ) {
					$params['return_code'] = false;
					$params['msg'] = __( 'Changing this prediction is not allowed.', 'football-pool' );
					Football_Pool_Utils::log_message( $user_id, FOOTBALLPOOL_TYPE_MATCH, $joker, 0,
						"Match {$joker}: {$home}-{$away} multiplier update failed. " . $params['msg']
					);
				} else {
					// get array of jokers from the predictions
					$jokers = [];
					foreach ( $user_match_info as $match ) {
						if ( (int)$match['has_joker'] === 1 ) {
							$jokers[] = $match['id'];
						}
					}

					// store the current state of the jokers
					if ( count( $jokers ) === 0 ) $jokers = [0];
					$params['joker'] = implode( ',', $jokers );

					// determine if user wants to set or clear the joker
					$params['action'] = [];
					if ( in_array( $joker, $jokers, true ) ) {
						// remove the joker from the set
						$params['action'][$joker] = 'clear';
						foreach ( array_keys( $jokers, $joker, true ) as $key ) {
							unset( $jokers[$key] );
						}
					} else {
						// check if setting of this joker is allowed
						$joker_allowed = $pool->joker_allowed( $joker, $user_match_info, $user_id );
						$joker_allowed = apply_filters( 'footballpool_joker_check_if_allowed'
							, $joker_allowed, $params, $user_match_info );

						if ( $joker_allowed === false ) {
							$params['return_code'] = false;
							// cannot use $this->jokers_per in a non-object context, so directly using the option value
							if ( Football_Pool_Utils::get_fp_option( 'jokers_per', 1, 'int' ) === 1 ) {
								$params['msg'] = __( 'Available multiplier(s) already used.', 'football-pool' );
							} else {
								$params['msg'] = __(
									'Available multiplier(s) for this set of matches already used.',
									'football-pool'
								);
							}
							Football_Pool_Utils::log_message(
								$user_id, FOOTBALLPOOL_TYPE_MATCH, $joker, 0,
								"Match {$joker}: {$home}-{$away} multiplier update failed. " . $params['msg']
							);
						} else {
							// add the joker to the set
							$params['action'][$joker] = 'set';
							$jokers[] = $joker;
						}
					}

					if ( count( $jokers ) === 0 ) $jokers = [0];

					// Save in the prediction table.
					$result = $pool->save_jokers_in_predictions( implode( ',', $jokers ), $user_id );

					if ( $result === false ) {
						// $params['joker'] stays in previous state and we return an error message.
						$params['return_code'] = false;
						$params['msg'] = __( 'Something went wrong while saving the multiplier.', 'football-pool' );
						Football_Pool_Utils::log_message(
							$user_id, FOOTBALLPOOL_TYPE_MATCH, $joker, 0,
							"Match {$joker}: {$home}-{$away} multiplier update failed. " . $params['msg']
						);
					} else {
						// Return the new comma-separated string with all match ids with a joker.
						$params['joker'] = implode( ',', $jokers );
						if ( $params['return_code'] === true ) {
							$action = $params['action'][$joker] ?? '??';
							Football_Pool_Utils::log_message(
								$user_id, FOOTBALLPOOL_TYPE_MATCH, $joker, 1,
								"Match {$joker}: {$home}-{$away} multiplier action: {$action}"
							);
						}					}
				}
			}
		}

		$params = apply_filters( 'footballpool_joker_params', $params, $user_match_info );
		do_action( 'footballpool_joker_update_after', $params, $user_match_info );

		// return the result
		Football_Pool_Utils::ajax_response( $params );
	}

	/**
	 * Outputs a prediction form for bonus questions.
	 *
	 * @param array $questions
	 * @param bool $wrap        (optional) if true, wrap the questions in its own form
	 * @param int $id
	 * @param int $start_at_nr  (optional) the bonus question numbering will start at that number
	 * @param string $type      used as class for the save button function
	 *
	 * @return string           an HTML formatted prediction form
	 */
	public function prediction_form_questions( array $questions, bool $wrap = false, int $id = 1,
	                                           int $start_at_nr = 1, string $type = 'questions' ): string
	{
		$output = '';
		if ( $this->has_bonus_questions ) {
			if ( $wrap ) $output .= $this->prediction_form_start( $id );
			
			$nr = $start_at_nr;

			if ( count( $questions ) > 0 ) {
				$output .= '<div class="questions-block">';
				foreach ( $questions as $question ) {
					if ( $question['match_id'] == 0 ) {
						$output .= $this->print_bonus_question( $question, $nr++ );
					}
				}
				$output .= '</div>';
			}

			if ( $nr > $start_at_nr ) {
				$output .= $this->save_button( $type, $id );
			}
			
			if ( $wrap ) $output .= $this->prediction_form_end( $id );
		}
		
		return apply_filters( 'footballpool_predictionform_questions_html', $output, $questions );
	}
	
	/**
	 * Outputs a prediction form for matches. Also includes linked questions (if any).
	 *
	 * @param array $matches
	 * @param bool $wrap       (optional) if true, wrap the matches in its own form
	 * @param int $id          unique form id
	 * @param string $type
	 *
	 * @return string          an HTML formatted prediction form
	 */
	public function prediction_form_matches( array $matches, bool $wrap = false, int $id = 1,
	                                         string $type = 'matches' ): string
	{
		$output = '';
		if ( $this->has_matches ) {
			
			if ( $wrap ) $output .= $this->prediction_form_start( $id );
			
			$user_id = get_current_user_id();

			$show_actual = Football_Pool_Utils::get_fp_option( 'prediction_page_show_actual_result', false );
			$is_user_page = false;
			$output .= $this->matches->print_matches_for_input( $matches, $id, $user_id, $is_user_page, $show_actual );
			
			if ( $this->has_jokers ) {
				$joker = implode( ',', $this->matches->joker_value );
				$output .= sprintf( '<input type="hidden" id="_joker_%d" name="_joker" value="%s">', $id, $joker );
			}
			
			if ( count( $matches ) > 0 ) {
				$output .= $this->save_button( $type, $id );
			}
			
			if ( $wrap ) $output .= $this->prediction_form_end( $id );
		}
		
		return $output;
	}

	/**
	 * Returns the start of the prediction form.
	 *
	 * @param int $id
	 * @return string
	 */
	public function prediction_form_start( int $id = 1): string
	{
		$action_url = '';//( is_page() ? get_page_link() : get_permalink() );
		$output = sprintf(
			'<form id="predictionform-%d" class="fp-form fp-prediction-form" action="%s" method="post">',
			$id,
			$action_url
		);
		$nonce_field = wp_nonce_field( FOOTBALLPOOL_NONCE_BLOG, FOOTBALLPOOL_NONCE_BLOG_INPUT_NAME, true, false );
		// remove the id from the input to prevent warnings for duplicate ID's
		// when you have more than one form on the page
		$output .= str_ireplace( ' id="' . FOOTBALLPOOL_NONCE_BLOG_INPUT_NAME . '"', '', $nonce_field );
		$output .= sprintf( '<input type="hidden" name="_fp_form_id" value="%d">', $id );
		return $output;
	}

	/**
	 * Returns the end HTML for a prediction form.
	 *
	 * @param int $id
	 * @return string
	 */
	public function prediction_form_end( int $id = 1 ): string
	{
		return sprintf( '<input type="hidden" id="_action_%d" name="_fp_action" value="update"></form>', $id );
	}

	/**
	 * Returns true if a question can still be edited by a player. False if the question is blocked.
	 *
	 * @param int $ts
	 * @return bool
	 */
	public function question_is_editable( int $ts ): bool
	{
		if ( $this->force_lock_time ) {
			$editable = ( current_time( 'timestamp' ) < $this->lock_timestamp );
		} else {
			$diff = $ts - time();
			$editable = ( $diff > $this->lock_timestamp );
		}
		
		return $editable;
	}

	/**
	 * @param int $question_id
	 * @return array
	 */
	public function get_bonus_question_answers_for_users( int $question_id = 0 ): array
	{
		if ( $question_id == 0 ) return [];
		
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = "SELECT u.ID AS user_id, u.display_name AS name, a.answer, a.correct, a.points
				FROM {$prefix}bonusquestions_useranswers a 
				RIGHT OUTER JOIN {$wpdb->users} u
					ON ( a.question_id = %d AND a.user_id = u.ID ) ";
		if ( $this->has_leagues ) {
			$sql .= "INNER JOIN {$prefix}league_users lu ON ( u.ID = lu.user_id ) ";
			$sql .= "INNER JOIN {$prefix}leagues l ON ( lu.league_id = l.id ) ";
		} else {
			$sql .= "LEFT OUTER JOIN {$prefix}league_users lu ON ( lu.user_id = u.ID ) ";
			$sql .= "WHERE ( lu.league_id <> 0 OR lu.league_id IS NULL ) ";
		}
		$sql .= "ORDER BY u.display_name ASC";
		$sql = $wpdb->prepare( $sql, $question_id );

		return $wpdb->get_results( $sql, ARRAY_A );
	}

	/**
	 * Returns the HTML for the save button on the prediction form.
	 *
	 * @param string $type
	 * @param int $id
	 * @return string
	 */
	public function save_button( string $type = 'matches', int $id = 0 ): string
	{
		if ( Football_Pool_Utils::get_fp_option( 'hide_save_button', 0, 'int' ) === 1 ) {
			$button = '';
		} else {
			$button = sprintf(
				'<div class="buttonblock button-%s form-%d"><input type="submit" name="_submit" value="%s"></div>'
				, $type
				, $id
				, __( 'Save', 'football-pool' )
			);
		}

		return $button;
	}

}