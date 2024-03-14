<?php

/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

class Football_Pool_Groups {
	public static function add( $name ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$sql = $wpdb->prepare( "INSERT INTO {$prefix}groups ( name ) VALUES ( %s )", $name );
		do_action( 'footballpool_groups_before_add', $name );
		$wpdb->query( $sql );
		do_action( 'footballpool_groups_after_add', $name );
		return $wpdb->insert_id;
	}
	
	public static function update( $id, $name ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		if ( $id == 0 ) {
			$id = self::add( $name );
		} else {
			$sql = $wpdb->prepare( "UPDATE {$prefix}groups SET name = %s WHERE id = %d", $name, $id );
			do_action( 'footballpool_groups_before_update', $id, $name );
			$wpdb->query( $sql );
			do_action( 'footballpool_groups_after_update', $id, $name );
		}
		
		return $id;
	}
	
	public static function get_groups() {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = "SELECT id, name FROM {$prefix}groups ORDER BY name ASC";
		return apply_filters( 'footballpool_get_groups', $wpdb->get_results( $sql ) );
	}
	
	public static function get_group_by_id( $id ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = $wpdb->prepare( "SELECT id, name FROM {$prefix}groups WHERE id = %d", $id );
		return $wpdb->get_row( $sql );
	}
	
	public static function get_group_by_name( $name, $addnew = '' ) {
		if ( $name == '' ) return 0;
		
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = $wpdb->prepare( "SELECT id, name FROM {$prefix}groups WHERE name = %s", $name );
		$result = $wpdb->get_row( $sql );
		
		if ( $addnew === 'addnew' && $result === null ) {
			$id = self::add( $name );
			$result = (object) array( 
									'id'          => $id, 
									'name'        => $name,
									'inserted'    => true
									);
		}
		
		return $result;
	}
	
	public function get_group_names() {
		$group_names = [];
		
		$rows = $this->get_group_composition();
		foreach ( $rows as $row ) {
			$group_names[(integer) $row['id']] = htmlentities( $row['name'], ENT_COMPAT, 'UTF-8' );
		}
		
		return $group_names;
	}
	
	public function get_ranking_array() {
		$ranking = $this->get_standings();
		$group_ranking = [];
		
		$rows = $this->get_group_composition();
		foreach ( $rows as $row ) {
			$group_ranking[$row['id']][(int) $row['team_id']] = 
								$this->get_standing_for_team( $ranking, $row['team_id'] );
		}
				
		return $this->order_groups( $group_ranking );
	}
	
	private function order_groups( $arr ) {
		foreach ( $arr as $group => $teams ) {
			uasort( $arr[$group], array( $this, 'compare_teams' ) );
		}
		return apply_filters( 'footballpool_group_sort', $arr );
	}

	public static function compare_teams( $a, $b ) {
		// if points are equal
		if ( $a['points'] === $b['points'] ) {
			// check if they have the same number of plays
			if ( $a['plays'] === $b['plays'] ) {
				// check goal difference
				if ( ( $a['for'] - $a['against'] ) === ( $b['for'] - $b['against'] ) ) {
					// now check the goals scored
					if ( $a['for'] === $b['for'] ) {
						// all failed, so we check a hardcoded ordering
						$teams = new Football_Pool_Teams;
						return ( $teams->get_group_order( $a['team'] ) > $teams->get_group_order( $b['team'] ) ? +1 : -1 );
					}
					// the one with more goals wins (descending order)
					return ( $a['for'] < $b['for'] ) ? +1 : -1;
				}
				// the one with more goals wins (descending order)
				return ( ( $a['for'] - $a['against'] ) < ( $b['for'] - $b['against'] ) ? +1 : -1 );
			}
			// the one with the least plays has the advantage
			return ( $a['plays'] > $b['plays'] ) ? +1 : -1;
		}
		// order descending
		return ( $a['points'] < $b['points'] ) ? +1 : -1;
	}

	private function get_standings() {
		global $pool;
		$wins = $draws = $losses = $for = $against = [];
		
		$match_types = Football_Pool_Utils::get_fp_option(
													'groups_page_match_types' 
													, array( FOOTBALLPOOL_GROUPS_PAGE_DEFAULT_MATCHTYPE ) 
												);
		$rows = $pool->matches->get_info( $match_types );
		
		foreach ( $rows as $row ) {
			if ( ( $row['home_score'] !== null ) && ( $row['away_score'] !== null ) ) {
				// set goals
				$this->set_goals_array( 
								$for, $against, 
								$row['home_team_id'], $row['away_team_id'], 
								$row['home_score'], $row['away_score'] 
						);
				// set wins, draws and losses
				if ( (int) $row['home_score'] > (int) $row['away_score'] ) {
					$wins   = $this->set_standing_array( $wins, $row['home_team_id'] );
					$losses = $this->set_standing_array( $losses, $row['away_team_id'] );
				} elseif ( (int) $row['home_score'] < (int) $row['away_score'] ) {
					$losses = $this->set_standing_array( $losses, $row['home_team_id'] );
					$wins   = $this->set_standing_array( $wins, $row['away_team_id'] );
				} elseif ( (int) $row['home_score'] == (int) $row['away_score'] ) {
					$draws = $this->set_standing_array( $draws, $row['home_team_id'] );
					$draws = $this->set_standing_array( $draws, $row['away_team_id'] );
				} else {
					echo 'what the fuck? this shouldn\'t happen: ', $row['home_team_id'], '-', $row['away_team_id'], '<br>';
				}
			}
		}
		
		return array( $wins, $draws, $losses, $for, $against );
	}
	
	private function get_standing_for_team( $ranking, $id ) {
		$team_points_win  = Football_Pool_Utils::get_fp_option( 'team_points_win', FOOTBALLPOOL_TEAM_POINTS_WIN, 'int' );
		$team_points_draw = Football_Pool_Utils::get_fp_option( 'team_points_draw', FOOTBALLPOOL_TEAM_POINTS_DRAW, 'int' );
		
		$wins =    ( isset( $ranking[0][$id] ) ? $ranking[0][$id] : 0 );
		$draws =   ( isset( $ranking[1][$id] ) ? $ranking[1][$id] : 0 );
		$losses =  ( isset( $ranking[2][$id] ) ? $ranking[2][$id] : 0 );
		$for =     ( isset( $ranking[3][$id] ) ? $ranking[3][$id] : 0 );
		$against = ( isset( $ranking[4][$id] ) ? $ranking[4][$id] : 0 );
		$points =  ( $wins * $team_points_win ) + ( $draws * $team_points_draw );
		$plays =   $wins + $draws + $losses;
		return array(
					'team' => $id, 
					'plays' => $plays, 
					'wins' => $wins, 
					'draws' => $draws, 
					'losses' => $losses, 
					'points' => $points, 
					'for' => $for, 
					'against' => $against
					);
	}
	
	// only return games for the first round
	public function get_plays( $group_id ) {
		global $wpdb, $pool;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sorting = $pool->matches->get_match_sorting_method();
		$match_types = Football_Pool_Utils::get_fp_option( 
													'groups_page_match_types' 
													, array( FOOTBALLPOOL_GROUPS_PAGE_DEFAULT_MATCHTYPE ) 
												);
		if ( ! is_array( $match_types ) || count( $match_types ) == 0 ) {
			$match_types = array( FOOTBALLPOOL_GROUPS_PAGE_DEFAULT_MATCHTYPE );
		}
		$match_types = implode( ',', $match_types );
		
		$sql = $wpdb->prepare( "SELECT DISTINCT m.id, t.name AS matchtype
								FROM {$prefix}matches m, {$prefix}matchtypes t, {$prefix}teams tm 
								WHERE m.matchtype_id = t.id AND t.id IN ( {$match_types} )
									AND ( m.home_team_id = tm.id OR m.away_team_id = tm.id )
									AND tm.group_id = %d
								ORDER BY {$sorting}"
								, $group_id
						);
		
		$match_ids = $wpdb->get_col( $sql );
		if ( ! is_array( $match_ids ) ) $match_ids = [];
		$match_ids = apply_filters( 'footballpool_group_plays', $match_ids, $group_id );
		
		$matches = $pool->matches->matches;
		
		$plays = [];
		foreach( $matches as $match ) {
			if ( in_array( $match['id'], $match_ids, false ) ) $plays[] = $match;
		}
		
		return $plays;
	}

	private function set_goals_array( &$for, &$against, $home_team, $away_team, $home_score, $away_score ) {
		$home_team = (int) $home_team;
		$away_team = (int) $away_team;
		
		$for[$home_team]     = $this->set_goals( $for, $home_team, $home_score );
		$for[$away_team]     = $this->set_goals( $for, $away_team, $away_score );
		$against[$home_team] = $this->set_goals( $against, $home_team, $away_score );
		$against[$away_team] = $this->set_goals( $against, $away_team, $home_score );
	}
	
	private function set_goals( $goals, $team, $score ) {
		if ( ! isset( $goals[$team] ) ) {
			return $score;
		} else {
			return $goals[$team] + $score;
		}
	}
	
	private function set_standing_array( $arr, $id ) {
		$id = (int) $id;
		
		if ( isset( $arr[$id] ) && $arr[$id] != null ) {
			$arr[$id]++;
		} else {
			$arr[$id] = 1;
		}
		
		return $arr;
	}
	
	private function get_group_composition() {
		$cache_key = 'fp_get_group_composition';
		$rows = wp_cache_get( $cache_key, FOOTBALLPOOL_WPCACHE_PERSISTENT );
		
		if ( $rows === false ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			$sql = "SELECT t.id AS team_id, t.name AS team_name, g.id, g.name 
					FROM {$prefix}teams t, {$prefix}groups g 
					WHERE t.group_id = g.id AND t.is_real = 1 AND t.is_active = 1
					ORDER BY g.name ASC, t.group_order ASC, t.id ASC";
			$rows = apply_filters( 'footballpool_group_composition', $wpdb->get_results( $sql, ARRAY_A ) );
			wp_cache_set( $cache_key, $rows, FOOTBALLPOOL_WPCACHE_PERSISTENT );
		}
		
		return $rows;
	}
	
	public function print_group_standing( $group_id, $layout = 'wide', $class = '' ) {
		if ( $class != '' ) $class = ' ' . $class;
		
		$output = '';
		$teams = new Football_Pool_Teams;
		$team_names = $teams->team_names;
		
		$group_names = $this->get_group_names();
		$ranking = apply_filters( 'footballpool_group_standing_array', $this->get_ranking_array(), $group_id );

		$template_params = array(
							'css_class' => $class,
							'wins_expl' => esc_attr__( 'wins', 'football-pool' ),
							'wins_thead' => _x( 'w', 'short notation for \'wins\'', 'football-pool' ),
							'draws_expl' => esc_attr__( 'draws', 'football-pool' ),
							'draws_thead' => _x( 'd', 'short notation for \'draws\'', 'football-pool' ),
							'losses_expl' => esc_attr__( 'losses', 'football-pool' ),
							'losses_thead' => _x( 'l', 'short notation for \'losses\'', 'football-pool' ),
							'matches_expl' => esc_attr__( 'matches', 'football-pool' ),
							'matches_thead' => _x( 'm', 'short notation for \'matches\'', 'football-pool' ),
							'points_expl' => esc_attr__( 'points', 'football-pool' ),
							'points_thead' => _x( 'p', 'short notation for \'points\'', 'football-pool' )
						);
		
		$template_end = '</tbody></table></div>';
		$template_start = '<div class="ranking group-ranking-'.$layout.'%css_class%"><h2>%group_name%</h2>
								<table class="ranking group-ranking">
								<thead>';
		if ( $layout === 'wide' ) {
			$template_start .= '<tr>
									<th class="team"></th>
									<th class="plays"></th>
									<th class="wins"><span title="%wins_expl%">%wins_thead%</span></th>
									<th class="draws"><span title="%draws_expl%">%draws_thead%</span></th>
									<th class="losses"><span title="%losses_expl%">%losses_thead%</span></th>
									<th class="points"></th>
									<th class="goals"></th>
								</tr>
								</thead>
								<tbody>';
			
			$group_row_template = '<tr>
									<td class="team">%team_name%</td>
									<td class="plays">%plays%</td>
									<td class="wins">%wins%</td>
									<td class="draws">%draws%</td>
									<td class="losses">%losses%</td>
									<td class="points">%points%</td>
									<td class="goals">(%goals_for%-%goals_against%)</td>
								</tr>';
		} else {
			$template_start .= '<tr>
									<th class="team"></th>
									<th class="plays"><span title="%matches_expl%">%matches_thead%</span></th>
									<th class="points"><span title="%points_expl%">%points_thead%</span></th>
									<th class="goals"></th>
								</tr>
								</thead>
								<tbody>';
			
			$group_row_template = '<tr>
									<td class="team">%team_name%</td>
									<td class="plays">%plays%</td>
									<td class="points">%points%</td>
									<td class="goals">(%goals_for%-%goals_against%)</td>
								</tr>';
		}
		
		$template_start = apply_filters( 'footballpool_group_table_start_template', $template_start, $layout );
		$group_row_template = apply_filters( 'footballpool_group_table_group_row_template', $group_row_template, $layout );
		$template_end = apply_filters( 'footballpool_group_table_end_template', $template_end, $layout );
		
		foreach ( $ranking as $group => $rank ) {
			if ( $group_id == '' || $group_id == $group ) {
				// finalize params for template start/end
				$template_params['group_id'] = $group;
				$template_params['group_name'] = Football_Pool_Utils::xssafe( $group_names[$group] );
				// allow for extra fields to be added to the template
				$template_params = apply_filters( 'footballpool_group_table_template_params', $template_params, $group );
				
				// start output
				$output .= Football_Pool_Utils::placeholder_replace( $template_start, $template_params );
				
				foreach ( $rank as $team_ranking ) {
					$row_template_params = array(
						'group_id' => $group,
						'group_name' => Football_Pool_Utils::xssafe( $group_names[$group] ),
						'team_id' => $team_ranking['team'],
						'css_class' => $class,
						'plays' => $team_ranking['plays'],
						'wins' => $team_ranking['wins'],
						'draws' => $team_ranking['draws'],
						'losses' => $team_ranking['losses'],
						'points' => $team_ranking['points'],
						'goals_for' => $team_ranking['for'],
						'goals_against' => $team_ranking['against'],
						'goals_diff' => $team_ranking['for'] - $team_ranking['against'],
					);
					
					if ( $teams->show_team_links ) {
						$row_template_params['team_name'] =
							sprintf( '<a href="%s">%s</a>'
									, esc_url(
											add_query_arg(
												array( 'team' => $team_ranking['team'] ),
												$teams->page
											)
										)
									, Football_Pool_Utils::xssafe( $team_names[$team_ranking['team']] )
								);
					} else {
						$row_template_params['team_name'] = Football_Pool_Utils::xssafe( $team_names[$team_ranking['team']] );
					}
					
					// allow for extra fields to be added to the template
					$row_template_params = apply_filters( 'footballpool_group_table_row_template_params'
															, $row_template_params, $team_ranking, $layout );
					
					$output .= Football_Pool_Utils::placeholder_replace( $group_row_template, $row_template_params );
				}
				// end output
				$output .= Football_Pool_Utils::placeholder_replace( $template_end, $template_params );
			}
		}
		
		return $output;
	}
}
