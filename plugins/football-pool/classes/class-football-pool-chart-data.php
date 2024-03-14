<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

// Football Pool uses the Highcharts javascript API
class Football_Pool_Chart_Data {
	/************************************************
	 All the functions to get the data for the charts
	*************************************************/
	private $scorehistory;

	public function __construct() {
		$this->scorehistory = $this->get_score_table();
	}
	
	private function get_score_table() {
		global $pool;
		return $pool->get_score_table();
	}
	
	public function predictions_pie_chart_data( $match, $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$sql = $wpdb->prepare( "SELECT
									  COUNT( IF( full = 1, 1, NULL ) ) AS scorefull
									, COUNT( IF( toto = 1, 1, NULL ) ) AS scoretoto
									, COUNT( IF( goal_bonus = 1, 
												IF( toto = 1, NULL, 1 ), 
												NULL ) 
									  ) AS goalbonus
									, COUNT( IF( goal_diff_bonus = 1, 1, NULL ) ) AS diffbonus
									, COUNT( user_id ) AS scoretotal
								FROM {$prefix}{$this->scorehistory} 
								WHERE `type` = %d AND ranking_id = %d
								GROUP BY source_id HAVING source_id = %d" 
								, FOOTBALLPOOL_TYPE_MATCH
								, $ranking_id
								, $match
						);
		$sql = apply_filters( 'footballpool_match_predictions_pie_chart_data_sql', $sql, $match, $ranking_id );
		$data = $wpdb->get_row( $sql, ARRAY_A );
		
		return apply_filters( 'footballpool_predictions_pie_chart_data', $data, $match, $ranking_id );
	}
	
	public function score_chart_data( $users = [], $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT ) {
		$data = [];
		
		if ( count( $users ) > 0 ) {
			global $wpdb, $pool;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			
			$user_ids = implode( ',', $users );
			
			$sql = "SELECT 
						  COUNT( IF( s.full = 1, 1, NULL ) ) AS scorefull
						, COUNT( IF( s.toto = 1, 1, NULL ) ) AS scoretoto
						, COUNT( IF( s.goal_bonus = 1, 1, NULL ) ) AS all_goal_bonus
						, COUNT( IF( s.goal_bonus = 1, IF( s.toto = 1, NULL, 1 ), NULL ) ) AS single_goal_bonus
						, COUNT( IF( s.goal_diff_bonus = 1, 1, NULL ) ) AS goal_diff_bonus
						, COUNT( s.source_id ) AS scoretotal
						, u.ID AS user_id
					FROM {$prefix}{$this->scorehistory} s 
					INNER JOIN {$wpdb->users} u ON ( u.ID = s.user_id ) 
					WHERE s.ranking_id = %d AND s.type = %d AND s.user_id IN ( {$user_ids} ) 
					GROUP BY s.user_id";
			$sql = $wpdb->prepare( $sql, $ranking_id, FOOTBALLPOOL_TYPE_MATCH );
			
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			if ( count( $rows ) > 0 ) {
				foreach ( $rows as $row ) {
					$user_name = $pool->user_name( $row['user_id'] );
					$data[ $row['user_id'] ] = array(
												'user_name' => $user_name,
												'data' => array(
															'scorefull'  => $row['scorefull'],
															'scoretoto'  => $row['scoretoto'],
															'scoretotal' => $row['scoretotal'],
															'goalbonus' => $row['single_goal_bonus'], // only counted when no toto score
															'goalbonus_all' => $row['all_goal_bonus'], // all goalbonus rewards
															'diffbonus' => $row['goal_diff_bonus'],
														)
											);
				}
			}
		}
		
		return apply_filters( 'footballpool_score_chart_data', $data, $users, $ranking_id );
	}
	
	public function bonus_question_for_users_pie_chart_data( $users = [],
	                                                         $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT ) {
		global $pool;
		$data = [];
		if ( count( $users ) > 0 ) {
			$questions = $pool->get_bonus_questions();
			$numquestions = count( $questions );
			
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			$users = implode( ',', $users );
			$sql = "SELECT
						  COUNT( IF( s.score > 0, 1, NULL ) ) AS bonuscorrect
						, COUNT( IF( s.score = 0, 1, NULL ) ) AS bonuswrong
						, COUNT( s.source_id ) AS bonustotal
						, u.display_name AS user_name
						, u.ID AS user_id
					FROM {$prefix}{$this->scorehistory} s
					INNER JOIN {$wpdb->users} u ON ( u.ID = s.user_id ) 
					WHERE s.ranking_id = {$ranking_id} AND s.type = %d AND s.user_id IN ( {$users} ) 
					GROUP BY s.user_id";
			
			$rows = $wpdb->get_results( $wpdb->prepare( $sql, FOOTBALLPOOL_TYPE_QUESTION ), ARRAY_A );
			foreach ( $rows as $row ) {
				$user_name = $pool->user_name( $row['user_id'] );
				$data[ $user_name ] = array(
										'user_name' => $user_name,
										'data' => array(
														'bonustotal'   => $numquestions,
														'bonuscorrect' => $row['bonuscorrect'],
														'bonuswrong'   => $row['bonuswrong']
														)
										);
			}
		}
		
		return apply_filters( 'footballpool_bonus_question_for_users_pie_chart_data', $data, $users, $ranking_id );
	}

	public function bonus_question_pie_chart_data( $question ) {
		global $wpdb, $pool;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$sql = "SELECT 
					COUNT( IF( ua.correct > 0, 1, NULL ) ) AS bonuscorrect, 
					COUNT( IF( ua.correct = 0, 1, NULL ) ) AS bonuswrong,
					COUNT( u.ID ) AS totalusers 
				FROM {$prefix}bonusquestions_useranswers AS ua 
				RIGHT OUTER JOIN {$wpdb->users} AS u
					ON ( u.ID = ua.user_id AND question_id = %d ) ";
		if ( $pool->has_leagues ) {
			$sql .= "INNER JOIN {$prefix}league_users lu ON ( u.ID = lu.user_id ) ";
			$sql .= "INNER JOIN {$prefix}leagues l ON ( lu.league_id = l.id ) ";
			$sql .= "WHERE 1=1 "; // makes it easier to extend the query via a filter
		} else {
			$sql .= "LEFT OUTER JOIN {$prefix}league_users lu ON ( lu.user_id = u.ID ) ";
			$sql .= "WHERE ( lu.league_id <> 0 OR lu.league_id IS NULL ) ";
		}

		$sql = apply_filters( 'footballpool_bonus_question_pie_chart_data_sql', $sql, $question );
		$sql = $wpdb->prepare( $sql, $question );
		$row = $wpdb->get_row( $sql, ARRAY_A );
		
		$data = array(
			'totalusers'   => $row['totalusers'],
			'bonuscorrect' => $row['bonuscorrect'],
			'bonuswrong'   => $row['bonuswrong']
		);

		return apply_filters( 'footballpool_bonus_question_pie_chart_data', $data, $question );
	}
	
	public function points_total_pie_chart_data( $user, $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT ) {
		global $wpdb, $pool;
		$prefix = FOOTBALLPOOL_DB_PREFIX;

		$output = [];

		// get the user's score
		$sql = $wpdb->prepare( "SELECT total_score FROM {$prefix}{$this->scorehistory} 
								WHERE user_id = %d AND ranking_id = %d
								ORDER BY score_date DESC, source_id DESC, type DESC LIMIT 1", 
								$user, $ranking_id
							);
		$data = $wpdb->get_var( $sql );
		$output['total_score'] = ( $data != null ) ? $data : 0;

		// get the matches for which there are results
		$sql = $wpdb->prepare( "SELECT source_id FROM {$prefix}{$this->scorehistory}
								WHERE type = %d AND user_id = %d AND ranking_id = %d"
			, FOOTBALLPOOL_TYPE_MATCH, $user, $ranking_id );
		$data = $wpdb->get_col($sql);
		$num_matches = count( $data );

		// construct arrays of match ID's grouped by match type ID
		$matches = $pool->matches->all_matches;
		$match_types = [];
		foreach ( $data as $match_id ) {
			$match_type_id = $matches[$match_id]['match_type_id'];
			$match_types[$match_type_id][] = $match_id;
		}

		// on a full score you get the fullpoints and two times the goal bonus
		$full = Football_Pool_Utils::get_fp_option( 'fullpoints', FOOTBALLPOOL_FULLPOINTS, 'int' ) +
				( 2 * Football_Pool_Utils::get_fp_option( 'goalpoints', FOOTBALLPOOL_GOALPOINTS, 'int' ) );
		$output['max_score'] = 0;

		$num_jokers = $pool->get_amount_of_jokers_allowed();

		if ( $num_jokers > 0 ) {
			$joker_multiplier = Football_Pool_Utils::get_fp_option( 'joker_multiplier', FOOTBALLPOOL_JOKERMULTIPLIER, 'int' );
			$jokers_per = Football_Pool_Utils::get_fp_option( 'jokers_per', 1, 'int' );

			// loop through matches per match type and account for amount of jokers (possibly per match type)
			$jokers_counted = 0;
			foreach( $match_types as $match_ids ) {
				if ( $jokers_per === 2 ) $jokers_counted = 0;
				foreach( $match_ids as $match_id ) {
					if ( $jokers_counted++ < $num_jokers ) {
						$output['max_score'] += $full * $joker_multiplier;
					} else {
						$output['max_score'] += $full;
					}
				}
			}
		} else {
			$output['max_score'] += $num_matches * $full;
		}

		// add the bonusquestions
		$sql = "SELECT SUM( q.points ) FROM {$prefix}bonusquestions q ";
		if ( $ranking_id != FOOTBALLPOOL_RANKING_DEFAULT ) {
			$sql .= $wpdb->prepare( "JOIN {$prefix}rankings_bonusquestions rb 
										ON ( rb.question_id = q.id AND rb.ranking_id = %d ) "
									, $ranking_id );
		}
		$sql .= "WHERE q.score_date IS NOT NULL";
		$data = $wpdb->get_var( $sql );
		$max_points = ( $data != null ) ? (int) $data : 0;
		$output['max_score'] += $max_points;
		
		return apply_filters( 'footballpool_points_total_pie_chart_data', $output, $user, $ranking_id );
	}
	
	public function score_per_match_line_chart_data( $users, $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT ) {
		return $this->per_match_line_chart_data( $users, 'total_score', $ranking_id );
	}
	
	public function ranking_per_match_line_chart_data( $users, $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT ) {
		return $this->per_match_line_chart_data( $users, 'ranking', $ranking_id );
	}
	
	private function per_match_line_chart_data( $users, $history_data_to_plot,
												$ranking_id = FOOTBALLPOOL_RANKING_DEFAULT ) {
		$data = [];
		if ( count( $users ) > 0 ) {
			global $wpdb, $pool;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			
			$user_ids = implode( ',', $users );
			$sql = $wpdb->prepare(
					"SELECT 
						h.source_id, h.{$history_data_to_plot}, u.ID AS user_id, u.display_name, h.type 
					FROM {$prefix}{$this->scorehistory} h, {$wpdb->users} u 
					WHERE h.ranking_id = %d AND u.ID = h.user_id AND h.user_id IN ( {$user_ids} )
					ORDER BY h.score_date ASC, h.type ASC, h.source_id ASC, h.user_id ASC"
				, $ranking_id
			);
			
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			foreach ( $rows as $row ) {
				$data[] = array(
								'match'     => $row['source_id'],
								'type'      => $row['type'],
								'value'     => $row[$history_data_to_plot],
								'user_name' => $pool->user_name( $row['user_id'] ),
								'user_id'   => $row['user_id'],
							);
			}
		}
		
		return apply_filters( 'footballpool_per_match_line_chart_data'
			, $data, $users, $history_data_to_plot, $ranking_id );
	}
	
	/*****************************************
	Build data arrays for the series option 
	******************************************/
	public function score_chart_series( $rows ) {
		$goal_bonus_enabled = 
			( Football_Pool_Utils::get_fp_option( 'goalpoints', FOOTBALLPOOL_GOALPOINTS, 'int' ) > 0 );
		$goal_diff_bonus_enabled = 
			( Football_Pool_Utils::get_fp_option( 'diffpoints', FOOTBALLPOOL_DIFFPOINTS, 'int' ) > 0 );
		
		$data = [];
		foreach ( $rows as $user_id => $data_row ) {
			$name = $data_row['user_name'];
			$row = $data_row['data'];
			if ( $goal_diff_bonus_enabled ) {
				$toto = (int) $row['scoretoto'] - (int) $row['diffbonus'];
			} else {
				$toto = (int) $row['scoretoto'];
			}
			if ( $toto < 0 ) $toto = 0;
			
			$no_score = (int) $row['scoretotal'] - (int) $row['scorefull'] - (int) $row['scoretoto'];
			if ( $goal_bonus_enabled ) $no_score -= (int) $row['goalbonus'];
			
			$data[$user_id] = [];
			$data[$user_id]['user_name'] = $name;
			$data[$user_id]['data'] = [
				[__( 'full score', 'football-pool' ), (int) $row['scorefull']],
				[__( 'toto score', 'football-pool' ), $toto],
				[__( 'no score', 'football-pool' ), $no_score],
			];
			if ( $goal_bonus_enabled ) {
				$data[$user_id]['data'][] = [
					__( 'just the goal bonus', 'football-pool' ),
					(int) $row['goalbonus']
				];
			}
			if ( $goal_diff_bonus_enabled ) {
				$data[$user_id]['data'][] = [
					__( 'goal difference bonus', 'football-pool' ),
					(int) $row['diffbonus']
				];
			}
		}
		
		$data = apply_filters( 'footballpool_score_chart_series', $data, $rows );
		return $data;
	}
	
	public function predictions_pie_series( $row ) {
		$goal_bonus = ( Football_Pool_Utils::get_fp_option( 'goalpoints', FOOTBALLPOOL_GOALPOINTS, 'int' ) > 0 );
		$goal_diff_bonus = ( Football_Pool_Utils::get_fp_option( 'diffpoints', FOOTBALLPOOL_DIFFPOINTS, 'int' ) > 0 );
		
		$toto = $goal_diff_bonus ? (int) $row['scoretoto'] - (int) $row['diffbonus'] : (int) $row['scoretoto'];
		
		$data = array(
					array( __( 'full score', 'football-pool' ), (int) $row['scorefull'] ),
					array( __( 'toto score', 'football-pool' ), $toto ),
					array( __( 'no score', 'football-pool' )
							, (int) $row['scoretotal'] - $row['scorefull'] - $row['scoretoto'] 
								- ( $goal_bonus ? $row['goalbonus'] : 0 ) 
					),
				);
		if ( $goal_bonus ) {
			$data[] = array( __( 'just the goal bonus', 'football-pool' ), (int) $row['goalbonus'] );
		}
		if ( $goal_diff_bonus ) {
			$data[] = array( __( 'toto score with goal difference bonus', 'football-pool' )
							, (int) $row['diffbonus'] );
		}
		
		$data = apply_filters( 'footballpool_predictions_pie_series', $data, $row );
		return $data;
	}
	
	public function points_total_pie_series( $row ) {
		$data = array(
					array( __( 'points scored', 'football-pool' ), (int) $row['total_score'] ),
					array( __( 'points missed', 'football-pool' ), (int) $row['max_score'] - $row['total_score'] )
				);
		$data = apply_filters( 'footballpool_points_total_pie_series', $data, $row );
		return $data;
	}
	
	public function bonus_question_pie_series( $rows, $open = 'open' ) {
		$data = [];
		foreach ( $rows as $name => $row ) {
			$rdata = $row['data'];
			$data[$name] = array(
								array( __( 'correct', 'football-pool' ), (int) $rdata['bonuscorrect'] ), 
								array( __( 'wrong', 'football-pool' ), (int) $rdata['bonuswrong'] ),
								//array( __( 'no answer', 'football-pool' ), (int) $rdata['bonusnoanswer'] )
							);
			if ( $open === 'open' ) {
				$data[$name][] = array( __( 'still open', 'football-pool' )
										, (int) $rdata['bonustotal'] - $rdata['bonuscorrect'] - $rdata['bonuswrong'] );
			}
		}
		$data = apply_filters( 'footballpool_bonus_question_pie_series', $data, $rows, $open );
		return $data;
	}
	
	public function bonus_question_pie_series_one_question( $row ) {
		$data = array(
					array( __( 'correct', 'football-pool' ), (int) $row['bonuscorrect'] ), 
					array( __( 'wrong', 'football-pool' ), (int) $row['bonuswrong'] ),
					array( __( 'no answer', 'football-pool' ), (int) $row['totalusers'] - $row['bonuscorrect'] - $row['bonuswrong'] )
				);
		$data = apply_filters( 'footballpool_bonus_question_pie_series_one_question', $data, $row );
		return $data;
	}
	
	private function per_match_line_series( $lines ) {
		global $pool;

		$output = [
			'categories' => [],
			'series' => []
		];
		
		if ( count( $lines ) > 0 ) {
			$categories_data = [];
			$series_data = [];
			
			$match_id = 0;
			$question_id = 0;
			$match = '';
			$type = '';
			
			foreach ( $lines as $data_row ) {
				// if new user, then start a new series
				$user = $data_row['user_name'];
				$user_id = $data_row['user_id'];
				if ( ! array_key_exists( $user_id, $series_data ) ) {
					$series_data[$user_id] = [
						'name' => $user,
						'data' => []
					];
				}
				// new match or question?
				if ( $match != $data_row['match'] || $type != $data_row['type'] ) {
					$match = (int) $data_row['match'];
					$type = $data_row['type'];
					if ( $type == 0 ) {
						$match_info = $pool->matches->get_match_info( $match );
						$category_data = __( 'match', 'football-pool' ) . ' ' . ++$match_id;
						if ( isset( $match_info['home_team'] ) ) {
							$category_data .= ': ' . $match_info['home_team'] . ' - ' . $match_info['away_team'];
						}
						$categories_data[] = $category_data;
					} else {
						$categories_data[] = __( 'bonus question', 'football-pool' ) . ' ' . ++$question_id;
					}
				}
				$series_data[$user_id]['data'][] = (int) $data_row['value'];
			}
			
			$output = [
				'categories' => $categories_data,
				'series' => $series_data
			];
		}

		return apply_filters( 'footballpool_per_match_line_series', $output, $lines );
	}
	
	public function score_per_match_line_series( $lines ) {
		return $this->per_match_line_series( $lines );
	}

	public function ranking_per_match_line_series( $lines ) {
		return $this->per_match_line_series( $lines );
	}
}
