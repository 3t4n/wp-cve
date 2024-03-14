<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

class Football_Pool_Matches {
	public $joker_value;
	public $match_table_layout;
	public $matches;
	public $all_matches;
	public $always_show_predictions = false;
	public $has_matches = false;

	private $joker_blocked = [false];
	private $matches_are_editable;
	private $force_lock_time = false;
	private $lock;
	private $use_spin_controls = true;
	private $time_format;
	private $date_format;

	public function __construct() {
		$this->enable_edits();
		
		$datetime = Football_Pool_Utils::get_fp_option( 'matches_locktime', '' );
		$this->force_lock_time = 
			( Football_Pool_Utils::get_fp_option( 'stop_time_method_matches', 0, 'int' ) === 1 )
			&& ( $datetime !== '' );
		if ( $this->force_lock_time ) {
			//$date = DateTime::createFromFormat( 'Y-m-d H:i', $datetime );
			$date = new DateTime( Football_Pool_Utils::date_from_gmt( $datetime ) );
			$this->lock = $date->format( 'U' );
		} else {
			// If you plan to use the following filter, make sure you do not invoke the matches or pool class
			// in the callback, because it will result in a "Maximum function nesting level" error.
			$this->lock = apply_filters( 'footballpool_matches_lock_maxperiod',
				Football_Pool_Utils::get_fp_option( 'maxperiod', FOOTBALLPOOL_MAXPERIOD, 'int' ) );
		}

		// layout options (classic = 0; flex = 1)
		$this->match_table_layout =
			Football_Pool_Utils::get_fp_option( 'match_table_layout', MATCH_TABLE_LAYOUT, 'int' );
		$this->always_show_predictions =
			( Football_Pool_Utils::get_fp_option( 'always_show_predictions', 0, 'int' ) === 1 );
		$this->use_spin_controls = ( Football_Pool_Utils::get_fp_option( 'use_spin_controls', 1, 'int' ) === 1 );
		$this->time_format = get_option( 'time_format', FOOTBALLPOOL_TIME_FORMAT );
		$this->date_format = get_option( 'date_format', FOOTBALLPOOL_DATE_FORMAT );
		
		// cache match info
		$this->all_matches = $this->all_matches_info();
		$this->matches = $this->match_info();
		$this->has_matches = ( count( $this->matches ) > 0 );
	}
	
	public function disable_edits() {
		$this->matches_are_editable = false;
		$this->joker_blocked = array_map( function() { return true; }, $this->joker_blocked );
	}
	
	private function enable_edits() {
		$this->matches_are_editable = true;
	}

	public function sort_matches_array_by_date_asc( $a, $b ) {
		return (int) $a['match_timestamp'] - (int) $b['match_timestamp'];
	}

	public function sort_matches_array_by_date_desc( $a, $b ) {
		return (int) $b['match_timestamp'] - (int) $a['match_timestamp'];
	}

	public function get_next_match( $ts = null, $team_id = null ) {
		if ( $ts === null ) $ts = time();
		
		$next_match = [];
		$prev_ts = null;
		
		// sort the matches array by date asc
		// (for situations where a date desc sorting method is chosen in the plugin options)
		$matches = $this->all_matches;
		usort( $matches, array( $this, 'sort_matches_array_by_date_asc' ) );
		
		foreach ( $matches as $match ) {
			if ( $match['match_timestamp'] > $ts
				&& ( $team_id === null || $team_id == $match['home_team_id'] || $team_id == $match['away_team_id'] ) ) {

				if ( $prev_ts !== null && $prev_ts != $match['match_timestamp'] ) break;

				$next_match[] = $match;
				$prev_ts = $match['match_timestamp'];
			}
		}
		
		return count( $next_match ) > 0 ? $next_match : false; // false if no match is found
	}
	
	public function get_last_games( $num_games = 4 ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$sql = $wpdb->prepare( "SELECT id, home_team_id, away_team_id, home_score, away_score 
								FROM {$prefix}matches 
								WHERE play_date <= NOW() AND home_score IS NOT NULL AND away_score IS NOT NULL 
								ORDER BY play_date DESC, id DESC 
								LIMIT %d", $num_games
					);
		return $wpdb->get_results( $sql, ARRAY_A );
	}
	
	public function get_match_sorting_method() {
		$order = Football_Pool_Utils::get_fp_option( 'match_sort_method', FOOTBALLPOOL_MATCH_SORT, 'int' );
		switch ( $order ) {
			case 3:
				$order = 'matchtype ASC, m.play_date DESC, m.id DESC';
				break;
			case 2:
				$order = 'matchtype DESC, m.play_date ASC, m.id ASC';
				break;
			case 1:
				$order = 'm.play_date DESC, m.id DESC';
				break;
			case 0:
			default:
				$order = 'm.play_date ASC, m.id ASC';
		}
		
		return apply_filters( 'footballpool_match_sorting_method', $order );
	}
	
	private function matches_query( $extra = '', $all_matches = false ) {
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$sorting = $this->get_match_sorting_method();

		$only_visible = ( $all_matches === false ) ? 'AND t.visibility = 1' : '';

		return "SELECT 
					m.id, 
					m.play_date,
					m.home_team_id, m.away_team_id, 
					m.home_score, m.away_score, 
					s.name AS stadium_name, s.id AS stadium_id,
					t.name AS matchtype, t.id AS type_id, t.id AS match_type_id, t.visibility AS match_is_visible
				FROM {$prefix}matches m
				JOIN {$prefix}stadiums s ON ( m.stadium_id = s.id )
				JOIN {$prefix}matchtypes t ON ( m.matchtype_id = t.id {$only_visible} )
				{$extra}
				ORDER BY {$sorting}";
	}
	
	public function get_first_match_info() {
		return reset( $this->matches );
	}
	
	public function get_info( $types = null ) {
		global $wpdb;
		if ( is_array( $types ) && count( $types ) > 0 ) {
			$types = implode( ',', $types );
			$sql = $this->matches_query( "WHERE t.id IN ( {$types} ) " );
		} else {
			$sql = $this->matches_query();
		}
		return $wpdb->get_results( $sql, ARRAY_A );
	}

	private function match_info() {
		$match_info = wp_cache_get( FOOTBALLPOOL_CACHE_MATCHES, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );

		if ( $match_info === false ) {
			$match_info = array_filter( $this->all_matches, function( $v, $k ) {
				return $v['match_is_visible'] === 1;
			}, ARRAY_FILTER_USE_BOTH );

			wp_cache_set( FOOTBALLPOOL_CACHE_MATCHES, $match_info, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		}

		return $match_info;
	}

	private function all_matches_info() {
		$match_info = wp_cache_get( FOOTBALLPOOL_CACHE_ALL_MATCHES, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		
		if ( $match_info === false ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			
			$match_info = array();
			$teams = new Football_Pool_Teams();
			
			$rows = $wpdb->get_results( $this->matches_query( '', 'all matches' ), ARRAY_A );
			
			foreach ( $rows as $row ) {
				$i = (int)$row['id'];
				$matchdate = new DateTime( $row['play_date'] );
				$ts = $matchdate->format( 'U' );
				
				$match_info[$i] = array();
				$match_info[$i]['id'] = (int)$row['id'];
				$match_info[$i]['match_datetime'] = $matchdate->format( 'd M Y H:i' );
				$match_info[$i]['match_timestamp'] = $ts;
				$match_info[$i]['play_date'] = $row['play_date'];
				$match_info[$i]['date'] = $row['play_date'];
				$match_info[$i]['home_score'] =
					is_numeric( $row['home_score'] ) ? (int)$row['home_score'] : $row['home_score'];
				$match_info[$i]['away_score'] =
					is_numeric( $row['away_score'] ) ? (int)$row['away_score'] : $row['away_score'];
				// https://www.php.net/manual/en/migration70.new-features.php#migration70.new-features.null-coalesce-op
				$match_info[$i]['home_team'] = $teams->team_info[(int)$row['home_team_id']]['team_name'] ?? '';
				$match_info[$i]['away_team'] = $teams->team_info[(int)$row['away_team_id']]['team_name'] ?? '';
				$match_info[$i]['home_team_id'] = (int)$row['home_team_id'];
				$match_info[$i]['away_team_id'] = (int)$row['away_team_id'];
				$match_info[$i]['match_is_editable'] = $this->match_is_editable( $ts );
				$match_info[$i]['match_is_visible'] = (int)$row['match_is_visible'];
				$match_info[$i]['match_has_finished'] =
					is_numeric( $row['home_score'] ) && is_numeric( $row['away_score'] )
					&& $match_info[$i]['match_timestamp'] < time();
				$match_info[$i]['stadium_id'] = (int)$row['stadium_id'];
				$match_info[$i]['stadium_name'] = $row['stadium_name'];
				$match_info[$i]['match_type_id'] = (int)$row['match_type_id'];
				$match_info[$i]['match_type'] = $row['matchtype'];
				$match_info[$i]['matchtype'] = $row['matchtype'];
				$match_info[$i]['linked_questions'] = null;
				// get group info for home team
				$match_info[$i]['group_id'] = (int) ( $teams->team_info[(int)$row['home_team_id']]['group_id'] ?? 0 );
				$match_info[$i]['group_name'] = $teams->team_info[(int)$row['home_team_id']]['group_name'] ?? '';
			}
			
			// get linked questions (include 'ORDER BY match_id' to fix bug with linked questions not showing)
			$sql = "SELECT id, match_id FROM {$prefix}bonusquestions WHERE match_id > 0
					ORDER BY match_id ASC, question_order ASC, answer_before_date ASC";
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			if ( $rows ) {
				$question_ids = array();
				$match_id = 0;
				foreach ( $rows as $row ) {
					if ( (int)$row['match_id'] !== $match_id ) {
						if ( $match_id > 0 && array_key_exists( $match_id, $match_info ) ) {
							$match_info[$match_id]['linked_questions'] = $question_ids;
						}
						$question_ids = array();
						$match_id = (int)$row['match_id'];
					}
					$question_ids[] = (int)$row['id'];
				}
				if ( array_key_exists( $match_id, $match_info ) ) {
					$match_info[$match_id]['linked_questions'] = $question_ids;
				}
			}
			
			$match_info = apply_filters( 'footballpool_matches', $match_info );
			wp_cache_set( FOOTBALLPOOL_CACHE_ALL_MATCHES, $match_info, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		}
		
		return $match_info;
	}
	
	public function get_match_info( $match ) {
		if ( is_int( $match ) && array_key_exists( $match, $this->all_matches ) ) {
			return $this->all_matches[ $match ];
		} else {
			return array();
		}
	}

	private function get_match_info_array_for_user( $user_id ) {
		global $wpdb, $pool;

		if ( ! ( $pool instanceof Football_Pool_Pool ) ) $pool = new Football_Pool_Pool();

		$cache_key = "match_info_for_user_{$user_id}";
		$rows = wp_cache_get( $cache_key, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );

		if ( $rows === false ) {
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			$order = $this->get_match_sorting_method();
			// Make sure we include match type in the query for the match order method.
			// Also, match type is used for the joker functions.
			$sql = $wpdb->prepare(
				"SELECT 
                    m.id, p.home_score, p.away_score, p.has_joker, t.name AS matchtype,
                    COALESCE( p.user_id, 0 ) AS has_prediction
				FROM {$prefix}matches m 
				JOIN {$prefix}matchtypes t ON ( m.matchtype_id = t.id )
				LEFT OUTER JOIN {$prefix}predictions p 
					ON ( p.match_id = m.id AND p.user_id = %d )
				ORDER BY {$order}",
				$user_id
			);
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			wp_cache_set( $cache_key, $rows, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		}

		$match_info = array();

		// Loop through the user match info and count all jokers: total and per match type.
		$match_types_in_predictions = array();
		$locked_jokers = array(
			// 0:       total of all jokers
			// 1..x:    total for match type id
		);
		$locked_jokers[0] = 0;
		foreach ( $rows as $row ) {
			$i = (int) $row['id'];
			// Get detailed match info from cache.
			$match_info[$i] = $this->get_match_info( $i );

			// Set to true if user has a prediction for this match stored in the database
			// (if row exists but has null values, then we still consider it a true value).
			$match_info[$i]['has_prediction'] = ! ( (int)$row['has_prediction'] === 0 );

			// Save the real result.
			$match_info[$i]['real_home_score'] = $match_info[$i]['home_score'];
			$match_info[$i]['real_away_score'] = $match_info[$i]['away_score'];

			// Change match result to predictions from user.
			$match_info[$i]['home_score'] = $row['home_score'];
			$match_info[$i]['away_score'] = $row['away_score'];

			$match_type_id = (int) $match_info[$i]['match_type_id'];
			if ( ! in_array( $match_type_id, $match_types_in_predictions ) ) {
				$match_types_in_predictions[] = $match_type_id;
			}

			// Add joker value.
			$match_info[$i]['has_joker'] = (int)$row['has_joker'];
			// And count the locked jokers (total and per match type)
			if ( $match_info[$i]['has_joker'] === 1 ) {
				if ( ! $match_info[$i]['match_is_editable'] ) {
					$locked_jokers[0]++;
					if ( array_key_exists( $match_type_id, $locked_jokers ) ) {
						$locked_jokers[$match_type_id]++;
					} else {
						$locked_jokers[$match_type_id] = 1;
					}
				}
			}
		}

		// Disable the jokers icon for this user if all jokers are used and those matches are locked for editing.
		// jokers_per: 1 = pool / 2 = match type
		if ( $pool->jokers_per === 1 && $locked_jokers[0] >= $pool->num_jokers ) {
			$this->block_joker( 0 );
		} elseif ( $pool->jokers_per === 2 ) {
			foreach ( $match_types_in_predictions as $match_type_id ) {
				$match_type_counted = array_key_exists( $match_type_id, $locked_jokers );
				if ( $match_type_counted && $locked_jokers[ $match_type_id ] >= $pool->num_jokers ) {
					$this->block_joker( $match_type_id );
				}
			}
		}

		return apply_filters( 'footballpool_match_array_for_user', $match_info, $user_id );
	}

	public function get_match_info_for_user_unfiltered( $user_id ) {
		return $this->get_match_info_array_for_user( $user_id );
	}

	public function get_match_info_for_user( $user_id, $match_ids = [], $all_matches = false ) {
		$match_info = $this->get_match_info_array_for_user( $user_id );

		if ( is_array( $match_ids ) && count( $match_ids ) > 0 ) {
			// filter out matches that were not passed to the function
			foreach ( $match_info as $id => $val ) {
				if ( ! in_array( $id, $match_ids ) ) unset( $match_info[$id] );
			}
		}

		if ( $all_matches === false ) {
			// filter out all matches that are in an invisible match type
			$match_info = array_filter( $match_info, function( $v, $k ) {
				return $v['match_is_visible'] === 1;
			}, ARRAY_FILTER_USE_BOTH );
		};

		return apply_filters( 'footballpool_matches_for_user', $match_info, $user_id );
	}
	
	public function get_joker_value_for_user( $user_id ) {
		$jokers = [];
		$user_match_info = $this->get_match_info_for_user_unfiltered( $user_id );
		foreach ( $user_match_info as $match ) {
			if ( $match['has_joker'] === 1 ) $jokers[] = $match['id'];
		}

		return $jokers;
	}
	
	public function first_empty_match_for_user( $user_id ) {
		if ( ! is_int( $user_id ) ) return 0;

		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$sql = $wpdb->prepare( "SELECT m.id FROM {$prefix}matches m 
								LEFT OUTER JOIN {$prefix}predictions p 
									ON ( p.match_id = m.id AND p.user_id = %d )
								WHERE p.user_id IS NULL OR p.home_score IS NULL OR p.away_score IS NULL
								ORDER BY m.play_date ASC, id ASC LIMIT 1",
								$user_id
							);
		$row = $wpdb->get_row( $sql, ARRAY_A );
		
		return ( $row ) ? $row['id'] : 0;
	}
	
	public function get_match_info_for_teams( $a, $b ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$sql = $wpdb->prepare( "SELECT home_team_id, away_team_id, home_score, away_score 
								FROM {$prefix}matches 
								WHERE ( home_team_id = %d AND away_team_id = %d ) 
									OR ( home_team_id = %d AND away_team_id = %d )",
								$a, $b,
								$b, $a
							);
		
		$row = $wpdb->get_row( $sql, ARRAY_A );
		
		if ( $row ) {
			return array(
						$row['home_team_id'] => $row['home_score'], 
						$row['away_team_id'] => $row['away_score']
					);
		} else {
			return 0;
		}
	}
	
	public function print_matches( $matches, $page = '' ) {
		$teams = new Football_Pool_Teams;
		$teamspage = Football_Pool::get_page_link( 'teams' );
		$statisticspage = Football_Pool::get_page_link( 'statistics' );
		$matchtype = $date_title = '';
		
		// define templates
		if ( $this->match_table_layout === 0 ) {
			$template_start = sprintf( '<table class="matchinfo classic-layout %s">', $page );
		} else {
			$template_start = sprintf( '<div class="matchinfo new-layout %s">', $page );
		}
		$template_start = apply_filters( 'footballpool_match_table_template_start', $template_start, $page );

		if ( $this->match_table_layout === 0 ) {
			$template_end = '</table>';
		} else {
			$template_end = '</div>';
		}
		$template_end = apply_filters( 'footballpool_match_table_template_end', $template_end, $page );

		if ( $this->match_table_layout === 0 ) {
			$match_template = '<tr id="match-%match_id%" class="%css_class% match-type-%match_type_id%" 
								title="' . __( 'match', 'football-pool' ) . ' %match_id%">
									<td class="time">%match_time%</td>
									<td class="home">%home_team%</td>
									<td class="flag">%home_team_flag%</td>
									<td class="score">
										<a title="' . __( 'Match statistics', 'football-pool' ) . '" href="%match_stats_url%">%home_score% - %away_score%</a>
									</td>
									<td class="flag">%away_team_flag%</td>
									<td class="away">%away_team%</td>
									</tr>';
		} else {
			$match_template = '<div id="match-%match_id%" class="%css_class% match-card match-type-%match_type_id%"
								title="' . __( 'match', 'football-pool' ) . ' %match_id%">
									<div class="match-card-header">
										<span class="matchdate">%match_datetime_formatted%</span><span class="time">%match_time%</span>
									</div>
									<div class="flag home">%home_team_flag%</div>
									<div class="flag away">%away_team_flag%</div>
									<div class="home">%home_team%</div>
									<div class="away">%away_team%</div>
									<div class="score">
										<a title="' . __( 'Match statistics', 'football-pool' ) . '" href="%match_stats_url%">%home_score%</a>
									</div>
									<div class="score">
										<a title="' . __( 'Match statistics', 'football-pool' ) . '" href="%match_stats_url%">%away_score%</a>
									</div>
								</div>';
		}
		$match_template = apply_filters( 'footballpool_match_table_match_template', $match_template, $page );

		if ( $this->match_table_layout === 0 ) {
			$match_type_template = '<tr><td class="matchtype" colspan="6">%match_type%</td></tr>';
		} else {
			$match_type_template = '<div class="matchtype">%match_type%</div>';
		}
		$match_type_template = apply_filters( 'footballpool_match_table_match_type_template'
			, $match_type_template, $page );

		if ( $this->match_table_layout === 0 ) {
			$date_row_template = '<tr><td class="matchdate" colspan="6" title="%match_day%">%match_datetime_formatted%</td></tr>';
		} else {
			$date_row_template = '';
		}
		$date_row_template = apply_filters( 'footballpool_match_table_date_row_template', $date_row_template, $page );
		
		// define the start and end template params
		$template_params = array();
		$template_params = apply_filters( 'footballpool_match_table_template_params', $template_params, $page );
		
		// start output
		$output = Football_Pool_Utils::placeholder_replace( $template_start, $template_params );
		$matches = apply_filters( 'footballpool_print_matches_matches_filter', $matches, $page );
		foreach ( $matches as $row ) {
			$matchdate = new DateTime( $row['play_date'] );
			$localdate = new DateTime( $this->format_match_time( $matchdate, 'Y-m-d H:i' ) );
			$localdate_formatted = date_i18n( FOOTBALLPOOL_MATCH_DATE_FORMAT, $localdate->format( 'U' ) );
			$match_day = date_i18n( FOOTBALLPOOL_MATCH_DAY_FORMAT, $localdate->format( 'U' ) );

			$css_class = $row['match_is_editable'] ? 'match open' : 'match closed';

			$home_team_id = (int) $row['home_team_id'];
			$away_team_id = (int) $row['away_team_id'];
			$home_team = $teams->team_names[$home_team_id] ?? '';
			$away_team = $teams->team_names[$away_team_id] ?? '';

			if ( $teams->is_favorite( $home_team_id ) ) {
				$css_class .= ' home-team-favorite';
			}
			if ( $teams->is_favorite( $away_team_id ) ) {
				$css_class .= ' away-team-favorite';
			}

			// define the template param values
			$match_template_params = array(
				'match_id' => $row['id'],
				'match_type_id' => $row['match_type_id'],
				'match_type' => Football_Pool_Utils::xssafe( __( $row['match_type'], 'football-pool' ) ),
				'match_timestamp' => $row['match_timestamp'],
				'match_date' => date_i18n( $this->date_format, $localdate->format( 'U' ) ),
				'match_time' => date_i18n( $this->time_format, $localdate->format( 'U' ) ),
				'match_day' => $match_day,
				'match_datetime_formatted' => $localdate_formatted,
				'match_utcdate' => $row['play_date'],
				'match_stats_url' => esc_url(
										add_query_arg(
											array( 'view' => 'matchpredictions', 'match' => $row['id'] ),
											$statisticspage
										)
									),
				'stats_link' => $this->show_users_link( $row['id'], $row['match_timestamp'] ),
				'stadium_id' => $row['stadium_id'],
				'stadium_name' => Football_Pool_Utils::xssafe( $row['stadium_name'] ),
				'home_team_id' => $home_team_id,
				'away_team_id' => $away_team_id,
				'home_team' => Football_Pool_Utils::xssafe( $home_team ),
				'away_team' => Football_Pool_Utils::xssafe( $away_team ),
				'home_team_flag' => $teams->flag_image( $home_team_id ),
				'away_team_flag' => $teams->flag_image( $away_team_id ),
				'home_score' => $row['home_score'],
				'away_score' => $row['away_score'],
				'group_id' => $row['group_id'],
				'group_name' => Football_Pool_Utils::xssafe( $row['group_name'] ),
				'css_class' => $css_class,
			);
			if ( $teams->show_team_links ) {
				$match_template_params['home_team'] =
					sprintf(
						'<a title="%s" href="%s">%s</a>',
						$match_template_params['home_team'],
						esc_url(
							add_query_arg(
								array( 'team' => $row['home_team_id'] ),
								$teamspage
							)
						),
						$match_template_params['home_team']
					);
				$match_template_params['away_team'] =
					sprintf(
						'<a title="%s" href="%s">%s</a>',
						$match_template_params['away_team'],
						esc_url(
							add_query_arg(
								array( 'team' => $row['away_team_id'] ),
								$teamspage
							)
						),
						$match_template_params['away_team']
					);
			}
			// allow for extra fields to be added to the template
			$match_template_params = apply_filters( 'footballpool_match_table_match_template_params'
				, $match_template_params, $row['id'], $page );
			
			if ( $matchtype != $row['matchtype'] ) {
				$matchtype = $row['matchtype'];
				$output .= Football_Pool_Utils::placeholder_replace( $match_type_template, $match_template_params );
			}
			
			if ( $date_title != $localdate_formatted ) {
				$date_title = $localdate_formatted;
				$output .= Football_Pool_Utils::placeholder_replace( $date_row_template, $match_template_params );
			}
			
			$output .= Football_Pool_Utils::placeholder_replace( $match_template, $match_template_params );
		}
		$output .= Football_Pool_Utils::placeholder_replace( $template_end, $template_params );
		
		return $output;
	}
	
	public function print_matches_for_input( $matches, $form_id, $user_id, $is_user_page = false, $show_actual = false ) {
		global $pool;
		if ( $is_user_page ) $this->disable_edits();

		$teams = new Football_Pool_Teams();
		$statistics_page = Football_Pool::get_page_link( 'statistics' );
		$date_title = $match_type = '';
		$joker = array();
		
		// Define templates
		$table_class = $is_user_page ? '' : ' input';
		if ( $this->match_table_layout === 0 ) {
			$template_start = "<table id='matchinfo-%form_id%' class='matchinfo classic-layout{$table_class}'>";
		} else {
			$template_start = "<div id='matchinfo-%form_id%' class='matchinfo new-layout{$table_class}'>";
		}
		$template_start = apply_filters( 'footballpool_predictionform_template_start', $template_start, $is_user_page );

		if ( $this->match_table_layout === 0 ) {
			$template_end = '</table>';
		} else {
			$template_end = '</div>';
		}
		$template_end = apply_filters( 'footballpool_predictionform_template_end', $template_end, $is_user_page );

		if ( $this->match_table_layout === 0 ) {
			$match_template = '<tr id="match-%match_id%-%form_id%" class="%css_class% match-type-%match_type_id%"
							title="' . __( 'match', 'football-pool' ) . ' %match_id%">
								<td class="time">%match_time%</td>
								<td class="home">%home_team%</td>
								<td class="flag">%home_team_flag%</td>
								<td class="score">%home_input%%home_actual_score%</td>
								<td>-</td>
								<td class="score">%away_input%%away_actual_score%</td>
								<td class="flag">%away_team_flag%</td>
								<td class="away">%away_team%</td>
								<td>%joker%</td>
								<td title="' . __( 'score', 'football-pool' ) . '" class="numeric user-score">%user_score%</td>
								<td class="matchstats">%stats_link%</td>
								</tr>';
		} else {
			$match_template = '<div id="match-%match_id%-%form_id%" class="%css_class% match-card match-type-%match_type_id%"
								title="' . __( 'match', 'football-pool' ) . ' %match_id%">
									<div class="match-card-header">
										<span class="matchdate">%match_datetime_formatted%</span><span class="time">%match_time%</span>
									</div>
									<div class="flag home">%home_team_flag%</div>
									<div class="flag away">%away_team_flag%</div>
									<div class="home">%home_team%</div>
									<div class="away">%away_team%</div>
									<div class="score">
										%home_input%
										<div class="actual-score">%home_actual_score%</div>
									</div>
									<div class="score">
										%away_input%
										<div class="actual-score">%away_actual_score%</div>
									</div>
									<div class="match-card-footer">
										<div class="user-score">%user_score_txt%</div>
										<div class="fp-icon">%stats_link%</div>
										<div class="fp-icon">%joker%</div>
									</div>
								</div>';
		}
		$match_template = apply_filters( 'footballpool_predictionform_match_template', $match_template, $is_user_page );

		if ( $this->match_table_layout === 0 ) {
			$match_type_template = '<tr><td class="matchtype" colspan="11">%match_type%</td></tr>';
		} else {
			$match_type_template = '<div class="matchtype">%match_type%</div>';
		}
		$match_type_template = apply_filters( 'footballpool_predictionform_match_type_template', $match_type_template, $is_user_page );

		if ( $this->match_table_layout === 0 ) {
			$date_row_template = '<tr><td class="matchdate" colspan="11" title="%match_day%">%match_datetime_formatted%</td></tr>';
		} else {
			$date_row_template = '';
		}
		$date_row_template = apply_filters( 'footballpool_predictionform_date_row_template', $date_row_template, $is_user_page );

		if ( $this->match_table_layout === 0 ) {
			$linked_question_template = '<tr id="match-%match_id%-%form_id%-question-%question_id%" class="linked-question">
											<td colspan="11">%question%</td></tr>';
		} else {
			$linked_question_template = '<div id="match-%match_id%-%form_id%-question-%question_id%" class="linked-question">
											%question%</div>';
		}
		$linked_question_template = apply_filters( 'footballpool_predictionform_linked_questions_template', $linked_question_template, $is_user_page );
		
		// Define the start and end template params
		$template_params = array(
			'form_id' => $form_id,
			'user_id' => $user_id,
			'is_user_page' => $is_user_page,
		);
		$template_params = apply_filters( 'footballpool_predictionform_template_params', $template_params, $is_user_page );
		
		// Start output
		if ( $this->match_table_layout === 0 ) {
			$actual_home_score = '<br><span class="actual-score home">(%s)</span>';
			$actual_away_score = '<br><span class="actual-score away">(%s)</span>';
		} else {
			$actual_home_score = '<span class="actual-score home">%s</span>';
			$actual_away_score = '<span class="actual-score away">%s</span>';
		}

		// Get the matches that will run next.
//		$active_matches = $this->get_next_match();

		$output = Football_Pool_Utils::placeholder_replace( $template_start, $template_params );
		$matches = apply_filters( 'footballpool_predictionform_matches_filter', $matches, $user_id, $is_user_page );
		foreach ( $matches as $row ) {
			$info = $this->get_match_info( (int)$row['id'] );

			// Add the joker to the array of jokers (for saving in the joker input)
			if ( (int)$row['has_joker'] === 1 ) {
				$joker[] = (int) $row['id'];
			}

			$matchdate = new DateTime( $row['play_date'] );
			$localdate = new DateTime( $this->format_match_time( $matchdate, 'Y-m-d H:i' ) );
			$localdate_formatted = date_i18n( FOOTBALLPOOL_MATCH_DATE_FORMAT, $localdate->format( 'U' ) );
			$match_day = date_i18n( FOOTBALLPOOL_MATCH_DAY_FORMAT, $localdate->format( 'U' ) );

			$css_class = $info['match_is_editable'] ? 'match open' : 'match closed';

			$home_team_id = (int) $info['home_team_id'];
			$away_team_id = (int) $info['away_team_id'];
			$home_team = $teams->team_names[$home_team_id] ?? '';
			$away_team = $teams->team_names[$away_team_id] ?? '';

			if ( $teams->is_favorite( $home_team_id ) ) {
				$css_class .= ' home-team-favorite';
			}
			if ( $teams->is_favorite( $away_team_id ) ) {
				$css_class .= ' away-team-favorite';
			}

			// Define the template param values
			$user_score = $this->show_score(
				$info['home_score'],
				$info['away_score'],
				$row['home_score'],
				$row['away_score'],
				$row['has_joker'],
				$info['match_timestamp'],
				$info['id'],
				$user_id
			);
			$number = is_numeric( $user_score ) ? $user_score : 2; // number parameter for _n()
			$match_template_params = array(
				'form_id' => $form_id,
				'match_id' => $info['id'],
				'match_type_id' => $info['match_type_id'],
				'match_type' => Football_Pool_Utils::xssafe( __( $info['match_type'], 'football-pool' ) ),
				'match_timestamp' => $info['match_timestamp'],
				'match_is_editable' => $this->match_is_editable( $info['match_timestamp'] ),
				'match_date' => date_i18n( $this->date_format, $localdate->format( 'U' ) ),
				'match_time' => date_i18n( $this->time_format, $localdate->format( 'U' ) ),
				'match_day' => $match_day,
				'match_datetime_formatted' => $localdate_formatted,
				'match_utcdate' => $info['play_date'],
				'match_stats_url' => esc_url(
										add_query_arg(
											array( 'view' => 'matchpredictions', 'match' => $info['id'] ),
											$statistics_page
										)
									),
				'stats_link' => $this->show_users_link( $info['id'], $info['match_timestamp'] ),
				'stadium_id' => $info['stadium_id'],
				'stadium_name' => Football_Pool_Utils::xssafe( $info['stadium_name'] ),
				'home_team_id' => $home_team_id,
				'away_team_id' => $away_team_id,
				'home_team' => $home_team,
				'away_team' => $away_team,
				'home_team_flag' => $teams->flag_image( $home_team_id ),
				'away_team_flag' => $teams->flag_image( $away_team_id ),
				'home_score' => $info['home_score'],
				'away_score' => $info['away_score'],
				'group_id' => $info['group_id'],
				'group_name' => Football_Pool_Utils::xssafe( $info['group_name'] ),
				'home_input' => $this->show_pool_input( '_home_' . $info['id'], $row['home_score'], $info['match_timestamp'] ),
				'away_input' => $this->show_pool_input( '_away_' . $info['id'], $row['away_score'], $info['match_timestamp'] ),
				'joker' => $this->show_pool_joker( $pool, $joker, (int) $info['id'], $info['match_timestamp'], $form_id ),
				'user_score' => $user_score,
				'user_score_txt' => is_numeric( $user_score ) ? $user_score . ' ' . _n( 'point', 'points', $number, 'football-pool' ) : $user_score,
				'css_class' => $css_class,
				'is_user_page' => $is_user_page,
				'home_actual_score' => ( $show_actual && is_numeric( $info['home_score'] ) )
											? sprintf( $actual_home_score, $info['home_score'] ) : '',
				'away_actual_score' => ( $show_actual && is_numeric( $info['away_score'] ) )
											? sprintf( $actual_away_score, $info['away_score'] ) : '',
				'user_home_score' => $row['home_score'],
				'user_away_score' => $row['away_score'],
			);
			// Allow for extra fields to be added to the template. Or update fields.
			$match_template_params = 
				apply_filters( 'footballpool_predictionform_match_template_params'
								, $match_template_params, $row['id'], $user_id, $is_user_page );
			
			if ( $match_type !== $row['matchtype'] ) {
				$match_type = $row['matchtype'];
				$output .= Football_Pool_Utils::placeholder_replace( $match_type_template, $match_template_params );
			}
			
			if ( $date_title !== $localdate_formatted ) {
				$date_title = $localdate_formatted;
				$output .= Football_Pool_Utils::placeholder_replace( $date_row_template, $match_template_params );
			}

			$output .= Football_Pool_Utils::placeholder_replace( $match_template, $match_template_params );
			
			if ( is_array( $info['linked_questions'] ) && count( $info['linked_questions'] ) > 0 ) {
				$questions = $pool->get_bonus_questions_for_user( $user_id, $info['linked_questions'] );
				foreach( $questions as $question ) {
					$linked_question_template_params = array(
						'form_id' => $form_id,
						'match_id' => $info['id'],
						'question_id' => $question['id'],
						'question' => $pool->print_bonus_question( $question, '', $is_user_page ),
					);
					// Allow extra fields to be added to the template
					$linked_question_template_params = 
						apply_filters( 'footballpool_linked_question_template_params'
							, $linked_question_template_params, $question['id'], $user_id, $is_user_page );
					
					$output .= Football_Pool_Utils::placeholder_replace( $linked_question_template, $linked_question_template_params );
				}
			}
		}

		$output .= Football_Pool_Utils::placeholder_replace( $template_end, $template_params );

		$this->joker_value = $this->get_joker_value_for_user( $user_id );

		return apply_filters( 'footballpool_predictionform_matches_html', $output, $matches, $user_id, $is_user_page );
	}
	
	public function format_match_time( $datetime, $format = false ) {
		if ( $format === false ) $format = $this->time_format;
		
		$display = Football_Pool_Utils::get_fp_option( 'match_time_display', 1, 'int' );
		if ( $display === 0 ) { // WordPress setting
			$datetime = new DateTime( Football_Pool_Utils::date_from_gmt( $datetime->format( 'Y-m-d H:i' ) ) );
		} elseif ( $display === 2 ) { // custom setting
			$offset = HOUR_IN_SECONDS * (float) Football_Pool_Utils::get_fp_option( 'match_time_offset' );
			if ( $offset >= 0 ) $offset = '+' . $offset;
			$datetime->modify( $offset . ' seconds' );
		} // else UTC
		
		return $datetime->format( $format );
	}
	
	public static function get_match_types() {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = "SELECT id, name, visibility FROM {$prefix}matchtypes ORDER BY name ASC";
		return $wpdb->get_results( $sql );
	}
	
	public function get_match_type_by_id( $id ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = $wpdb->prepare( "SELECT id, name, visibility FROM {$prefix}matchtypes WHERE id = %d", $id );
		return $wpdb->get_row( $sql );
	}
	
	public function get_match_type_by_name( $name, $addnew = 'no' ) {
		if ( $name === '' ) return 0;
		
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = $wpdb->prepare( "SELECT id, name, visibility FROM {$prefix}matchtypes WHERE name = %s", $name );
		$result = $wpdb->get_row( $sql );
		
		if ( $addnew === 'addnew' && $result === null ) {
			$sql = $wpdb->prepare( "INSERT INTO {$prefix}matchtypes ( name ) VALUES ( %s )", $name );
			$wpdb->query( $sql );
			$id = $wpdb->insert_id;
			$result = (object) array( 
									'id' => $id, 
									'name' => $name, 
									'visibility' => 1, 
									'inserted' => true 
									);
		}
		
		return $result;
	}
	
	public function get_matches_for_match_type( $ids = array() ) {
		if ( count( $ids ) === 0 ) return array();
		
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$matchtype_ids = implode( ',', $ids );
		$sql = "SELECT id FROM {$prefix}matches WHERE matchtype_id IN ( {$matchtype_ids} )";
		return $wpdb->get_col( $sql );
	}
	
	public function show_score( $home, $away, $user_home, $user_away, $joker, $ts, $match_id, $user_id = null ) {
		global $pool;
		if ( ! $this->match_is_editable( $ts ) ) {
			$score = $pool->calc_score( $home, $away, $user_home, $user_away, $joker, $match_id, $user_id );
			return $score['score'];
		} else {
			return '<span class="no-score"></span>';
		}
	}
	
	private function show_users_link( $match, $ts ) {
		$output = '';
		if ( $this->always_show_predictions || ! $this->match_is_editable( $ts ) ) {
			$title = __( 'view other users predictions', 'football-pool' );
			$output .= sprintf( '<a href="%s" title="%s">'
							, esc_url(
								add_query_arg( 
										array( 'view' => 'matchpredictions', 'match' => $match ), 
										Football_Pool::get_page_link( 'statistics' ) )
								)
							, $title
						);
			$output .= sprintf(
				'<img class="pie-chart-icon" src="%sassets/images/pie-chart.svg" alt="%s" title="%s"></a>',
				FOOTBALLPOOL_PLUGIN_URL,
				$title,
				$title
			);
		}
		return $output;
	}
	
	public function match_is_editable( $ts ) {
		if ( $this->force_lock_time ) {
			$editable = ( current_time( 'timestamp' ) < $this->lock );
		} else {
			$diff = $ts - time();
			$editable = ( $diff > $this->lock );
		}
		
		return $editable;
	}

	private function block_joker( $el ) {
		$this->joker_blocked[$el] = true;
	}
	
	/**
	 * Shows pool input for games that are still editable. For games where the edit period has expired 
	 * only the value is shown.
	 * The property matches_are_editable is used for the users page. It prevents the display of inputs
	 * and it prevents the display of games that are still editable. The latter to make sure users do 
	 * not copy results from each other. This may be overridden with the always_show_predictions option.
	 */
	public function show_pool_input( $name, $value, $ts ) {
		if ( $this->match_is_editable( $ts ) ) {
			if ( $this->matches_are_editable ) {
				if ( $this->use_spin_controls ) {
					$control = 'type="number" min="0" max="999"';
				} else {
					$control = 'type="text" maxlength="3"';
				}

				return sprintf(
					'<input %s name="%s" value="%s" class="prediction" tabindex="%d"/>'
//					. '<span class="fp-focus-border"><i></i></span>'
					, $control, $name, $value, FOOTBALLPOOL_TABINDEX
				);
			} else {
				return ( $this->always_show_predictions ? $value : '' );
			}
		} else {
			return $value;
		}
	}

	private function is_joker_blocked( $match_type_id ) {
		if ( Football_Pool_Utils::get_fp_option( 'jokers_per', 1, 'int' ) === 1 ) {
			$el = 0;
		} else {
			$el = $match_type_id;
		}

		return isset( $this->joker_blocked[$el] ) && $this->joker_blocked[$el];
	}

	private function show_pool_joker( $pool, $jokers, $match, $ts, $form_id = 1 ) {
		if ( ! $pool->has_jokers ) {
			return '';
		}

		$add_joker = '';

		// get the match type for the match
		$match_type_id = isset( $this->matches[$match] ) ? $this->matches[$match]['match_type_id'] : 0;

		if ( is_array( $jokers ) && count( $jokers ) > 0 && $match > 0 && in_array( $match, $jokers ) ) {
			$class = 'fp-joker';
		} else {
			$class = 'fp-nojoker';
		}
		/*
		Make sure joker is not shown for matches that are editable in case 
		the matches_are_editable property is set to false. Unless we have the new 
		'always display predictions' set, in that case we can ignore this.
		*/
		if ( ! $this->always_show_predictions ) {
			if ( ! $this->matches_are_editable && $this->match_is_editable( $ts ) ) {
				$class = 'fp-nojoker';
			}
		}

		if ( ! $this->is_joker_blocked( $match_type_id ) ) {
			if ( $this->match_is_editable( $ts ) ) {
				$add_joker = sprintf( ' onclick="FootballPool.change_joker( this.id )" title="%s"'
									, __( 'Click to toggle your multiplier.', 'football-pool' ) );
			}
		} else {
			if ( Football_Pool_Utils::get_fp_option( 'jokers_per', 1, 'int' ) === 1 ) {
				$msg = __( 'Available multiplier(s) already used.', 'football-pool' );
			} else {
				$msg = __( 'Available multiplier(s) for this set of matches already used.', 'football-pool' );
			}
			$add_joker = sprintf( ' title="%s"', $msg );
			$class .= ' readonly';
		}
		
		return sprintf( '<div class="fp-joker-box %s"%s id="joker-%d-%d"></div>'
			, $class, $add_joker, $match, $form_id );
	}
}
