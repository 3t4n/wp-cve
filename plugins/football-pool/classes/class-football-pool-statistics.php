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

/** @noinspection SqlResolve */

class Football_Pool_Statistics {
	public $data_available = false;
	public $stats_visible = false;
	public $stats_enabled = false;

	private $always_show_predictions = false;
	
	public function __construct() {
		$this->always_show_predictions = ( (int) Football_Pool_Utils::get_fp_option( 'always_show_predictions' ) === 1 );
		$this->data_available = $this->check_data();
		
		if ( Football_Pool_Utils::get_fp_option( 'simple_calculation_method' ) == 0 ) {
			$chart = new Football_Pool_Chart;
			$this->stats_enabled = $chart->stats_enabled;
		} else {
			// With simple calculation method, the stats are always disabled
			$this->stats_enabled = false;
		}
	}
	
	public function page_content() {
		$output = new Football_Pool_Statistics_Page();
		return $output->page_content();
	}
	
	private function check_data( $match = 0 ) {
		global $wpdb, $pool;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$scorehistory = $pool->get_score_table();
		
		$ranking_id = FOOTBALLPOOL_RANKING_DEFAULT;
		$single_match = ( $match > 0 ) ? '' : '--';
		$sql = $wpdb->prepare( sprintf( "SELECT COUNT( * ) FROM {$prefix}{$scorehistory} 
								WHERE ranking_id = %%d %s AND type = 0 AND source_id = %%d", $single_match )
								, $ranking_id, $match
							);
		$num = $wpdb->get_var( $sql );
		
		return ( $num > 0 );
	}
	
	public function data_available_for_match( $match ) {
		return $this->check_data( $match );
	}

	/**
	 * @param $user
	 * @return false|WP_User
	 */
	public function get_user_info( $user ) {
		return get_userdata( $user );
	}

	/**
	 * @param $user
	 * @return string
	 */
	public function show_user_info( $user ): string
	{
		global $pool;
		if ( $user ) {
			$output = sprintf( '<h1 class="user-%d">%s</h1>', $user->ID, $pool->user_name( $user->ID ) );
			$this->stats_visible = true;
		} else {
			$output = sprintf( '<p>%s</p>', __( 'User unknown.', 'football-pool' ) );
			$this->stats_visible = false;
		}
		
		return $output;
	}

	/**
	 * @param $info
	 * @return string
	 */
	public function show_match_info( $info ): string
	{
		$output = '';
		$this->stats_visible = false;

		if ( count( $info ) > 0 ) {
			if ( $this->always_show_predictions || $info['match_is_editable'] == false ) {
				$output .= sprintf( '<h2 class="match-%d">%s - %s'
					, $info['id']
					, Football_Pool_Utils::xssafe( $info['home_team'] )
					, Football_Pool_Utils::xssafe( $info['away_team'] )
				);
				if ( is_int( $info['home_score'] ) && is_int( $info['away_score'] ) ) {
					$output .= sprintf( ' (%d - %d)', $info['home_score'], $info['away_score'] );
				}
				$output .= '</h2>';
				$output .= sprintf( '<h3 class="stadium-name">%s</h3>', Football_Pool_Utils::xssafe( $info['stadium_name'] ) );
				$this->stats_visible = true;
			} else {
				$output .= sprintf( '<h2 class="match-%d">%s - %s</h2>'
					, $info['id']
					, Football_Pool_Utils::xssafe( $info['home_team'] )
					, Football_Pool_Utils::xssafe( $info['away_team'] )
				);
				$output .= sprintf( '<h3 class="stadium-name">%s</h3>', Football_Pool_Utils::xssafe( $info['stadium_name'] ) );
				$output .= sprintf( '<p>%s</p>', __( 'This data is not (yet) available.', 'football-pool' ) );
			}
		} else {
			$output .= sprintf( '<p>%s</p>', __( 'This data is not (yet) available.', 'football-pool' ) );
		}
		
		$output = apply_filters( 'footballpool_statistics_show_match_info', $output, $info );
		return $output;
	}
	
	public function show_bonus_question_info( $question ) {
		global $pool;
		$output = '';
		$info = $pool->get_bonus_question_info( $question );
		if ( $info ) {
			$points = $info['points'] == 0 ? __( 'variable', 'football-pool' ) : $info['points'];
			$output .= sprintf( '<h2 class="q%d">%s</h2>'
				, $info['id']
				, Football_Pool_Utils::xssafe( $info['question'] )
			);
			$output .= sprintf( '<p class="question-info q%d"><span class="question-points">%s: %s</span>'
				, $info['id']
				, __( 'points', 'football-pool' )
				, $points
			);
			if ( $this->always_show_predictions || $info['question_is_editable'] == false ) {
				$this->stats_visible = true;
				if ( ! $info['question_is_editable'] ) {
					$output .= sprintf( '<br><span class="question-answer">%s: %s</span>'
									, __( 'answer', 'football-pool' )
									, Football_Pool_Utils::xssafe( $info['answer'] )
								);
				}
				$output .= '</p>';
			} else {
				$output .= '</p>';
				$output .= sprintf( '<p>%s</p>', __( 'This data is not (yet) available.', 'football-pool' ) );
				$this->stats_visible = false;
			}
		} else {
			$output .= sprintf( '<p>%s</p>', __( 'This data is not (yet) available.', 'football-pool' ) );
			$this->stats_visible = false;
		}
		
		return $output;
	}
	
	public function show_answers_for_bonus_question( $id ) {
		global $pool;
		$info = $pool->get_bonus_question_info( $id );

		// todo: Only show when score date is in the past? Post by fimo66 on the forum.
		$show_answer_status = ( $info && $info['score_date'] !== null );
		
		$answers = $pool->get_bonus_question_answers_for_users( $id );
		$rows = apply_filters( 'footballpool_statistics_bonusquestion', $answers );

		$template_start = sprintf( '<table class="statistics prediction-table-questions q%d">
									<tr><th>%s</th><th>%s</th><th class="correct">%s</th></tr>'
			, $id
			, __( 'user', 'football-pool' )
			, __( 'answer', 'football-pool' )
			, __( 'correct?', 'football-pool' )
		);
		$template_start = apply_filters( 'footballpool_statistics_bonusquestion_template_start'
			, $template_start, $info );

		$template_end = '</table>';
		$template_end = apply_filters( 'footballpool_statistics_bonusquestion_template_end'
			, $template_end, $info );

		$user_page = Football_Pool::get_page_link('user');

		$row_template =
			'<tr>
				<td class="user-name"><a href="%user_url%">%user_name%</a></td>
				<td class="user-answer">%user_answer%</td>
				<td class="score" title="%score_text%">
					<span class="%score_class%"></span>
					<span class="points-awarded">%points%</span>
				</td>
				</tr>';
		$row_template = apply_filters( 'footballpool_statistics_bonusquestion_row_template'
			, $row_template, $info );

		// define the start and end template params
		$template_params = [];
		$template_params = apply_filters( 'footballpool_statistics_bonusquestion_template_params'
			, $template_params, $info );

		// start output
		$output = Football_Pool_Utils::placeholder_replace( $template_start, $template_params );

		$class = $title = '';
		foreach ( $rows as $answer ) {
			$answer_correct = ( (int)$answer['correct'] === 1 );
			if ( $show_answer_status ) {
				if ( $answer_correct ) {
					$class = 'correct fp-icon-check';
					$title = __('correct answer', 'football-pool');
				} else {
					$class = 'wrong fp-icon-times';
					$title = __('wrong answer', 'football-pool');
				}
			}

			if ( $answer_correct ) {
				if ( (int)$answer['points'] === 0 || $answer['points'] === null ) {
					$points_awarded = $info['points'];
				} else {
					$points_awarded = $answer['points'];
				}
			} else {
				$points_awarded = 0;
			}

			$row_params = [];
			$row_params['user_name'] = $pool->user_name( $answer['user_id'] );
			$row_params['user_url'] = esc_url( add_query_arg( array( 'user' => $answer['user_id'] ), $user_page ) );
			$row_params['score_class'] = $class;
			$row_params['score_text'] = $title;
			$row_params['user_answer'] = Football_Pool_Utils::xssafe( $answer['answer'], null, false );
			$row_params['points'] = $points_awarded;

			$row_params = apply_filters( 'footballpool_statistics_bonusquestion_row_params'
				, $row_params, $info, $answer['user_id'] );

			// output the row
			$output .= Football_Pool_Utils::placeholder_replace( $row_template, $row_params );
		}

		$output .= Football_Pool_Utils::placeholder_replace( $template_end, $template_params );

		return apply_filters( 'footballpool_statistics_bonusquestion_html', $output, $answers );
	}
	
	public function show_predictions_for_match( $match_info ) {
		global $wpdb, $pool;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$sql = "SELECT
					m.home_team_id, m.away_team_id, 
					p.home_score, p.away_score, p.has_joker, u.ID AS user_id, u.display_name AS user_name ";
		if ( $pool->has_leagues ) $sql .= ", l.id AS league_id ";
		$sql .= "FROM {$prefix}matches m 
				LEFT OUTER JOIN {$prefix}predictions p 
					ON ( p.match_id = m.id AND m.id = %d ) 
				RIGHT OUTER JOIN {$wpdb->users} u 
					ON ( u.ID = p.user_id ) ";
		if ( $pool->has_leagues ) {
			$sql .= "INNER JOIN {$prefix}league_users lu ON ( u.ID = lu.user_id )
					INNER JOIN {$prefix}leagues l ON ( l.id = lu.league_id ) ";
		} else {
			$sql .= "LEFT OUTER JOIN {$prefix}league_users lu ON ( lu.user_id = u.ID ) ";
			$sql .= "WHERE ( lu.league_id <> 0 OR lu.league_id IS NULL ) ";
		}
		$sql .= "ORDER BY u.display_name ASC";
		$sql = $wpdb->prepare( $sql, $match_info['id'] );
		
		$predictions = $wpdb->get_results( $sql, ARRAY_A );
		$rows = apply_filters( 'footballpool_statistics_matchpredictions', $predictions, $match_info );
		
		$output = '';
		if ( count( $rows ) > 0 ) {
			// define templates
			$layout_class = $pool->match_table_layout === 0 ? 'classic-layout' : 'new-layout';
			$template_start = sprintf( '<table class="matchinfo match-%d %s statistics">
										<tr><th class="username">%s</th>
										<th colspan="%d">%s</th><th>%s</th></tr>'
				, $match_info['id']
				, $layout_class
				, __( 'name', 'football-pool' )
				, ( $pool->has_jokers ? 4 : 3 )
				, __( 'prediction', 'football-pool' )
				, __( 'score', 'football-pool' )
			);
			$template_start = apply_filters( 'footballpool_matchpredictions_template_start'
											, $template_start, $match_info );
			
			$template_end = '</table>';
			$template_end = apply_filters( 'footballpool_matchpredictions_template_end'
											, $template_end, $match_info );
			
			$row_template = '<tr class="%current_user_css_class%">
								<td><a href="%user_url%">%user_name%</a></td>
								<td class="home">%home_score%</td>
								<td class="match-hyphen">-</td>
								<td class="away">%away_score%</td>';
			if ( $pool->has_jokers ) {
				$row_template .= '<td title="%joker_title_text%"><div class="nopointer %joker_css_class%"></div></td>';
			}
			$row_template .= '<td class="score">%score%</td></tr>';
			
			$row_template = apply_filters( 'footballpool_matchpredictions_row_template'
											, $row_template, $match_info );
			
			// define the start and end template params
			$template_params = [];
			$template_params = apply_filters( 'footballpool_matchpredictions_template_params'
											, $template_params, $match_info );
			
			// start output
			$output .= Football_Pool_Utils::placeholder_replace( $template_start, $template_params );
			
			$current_user_id = get_current_user_id();
			$userpage = Football_Pool::get_page_link( 'user' );
			foreach ( $rows as $row ) {
				// set the params for this row
				$row_params = [];
				$row_params['user_name'] = $pool->user_name( $row['user_id'] );
				$row_params['user_url'] = esc_url( add_query_arg( array( 'user' => $row['user_id'] ), $userpage ) );
				$row_params['current_user_css_class'] = ( (int) $row['user_id'] === $current_user_id ? 'currentuser' : '' );
				$row_params['home_score'] = $row['home_score'];
				$row_params['away_score'] = $row['away_score'];
				if ( $row['has_joker'] == 1 ) {
					$row_params['joker_title_text'] = _x( 'multiplier set', 'to indicate that a user has set a multiplier for this match'
														, 'football-pool' );
					$row_params['joker_css_class'] = 'fp-joker';
				} else {
					$row_params['joker_title_text'] = '';
					$row_params['joker_css_class'] = 'fp-nojoker';
				}
				$score = $pool->calc_score(
					$match_info['home_score'],
					$match_info['away_score'],
					$row['home_score'],
					$row['away_score'],
					$row['has_joker'],
					$match_info['id'],
					$row['user_id']
				);
				$row_params['score'] = $score['score'];
				if ( $this->always_show_predictions && $match_info['match_is_editable'] ) {
					$row_params['score'] = '';
				}

				$row_params = apply_filters( 'footballpool_matchpredictions_row_params'
											, $row_params, $match_info, $row['user_id'] );
				
				// output the row
				$output .= Football_Pool_Utils::placeholder_replace( $row_template, $row_params );
			}
			
			$output .= Football_Pool_Utils::placeholder_replace( $template_end, $template_params );
		}
		
		return apply_filters( 'footballpool_statistics_matchpredictions_html', $output, $predictions );
	}
	
}
