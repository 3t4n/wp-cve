<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */ /** @noinspection SqlResolve */

// constants
const FOOTBALLPOOL_CALC_INFO_MESSAGE = 'info';
const FOOTBALLPOOL_CALC_WARNING_MESSAGE = 'warning';
const FOOTBALLPOOL_CALC_ERROR_MESSAGE = 'error';

class Football_Pool_Admin_Score_Calculation extends Football_Pool_Admin {
	/**
	 * @var array $calculation_steps_cache
	 */
	private static array $calculation_steps_cache = [];

	/**
	 * @param bool $is_cli
	 * @param array $args
	 *
	 * @return array|void Returns the $params array in cli mode, echoes the $params as a JSON response in
	 *                    AJAX mode or echoes a piece of javascript to refresh the page with the params in
	 *                    the querystring in NOAJAX mode.
	 * @throws Exception
	 */
	public static function process( bool $is_cli = false, array $args = [] ) {
		// initialize variables
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;

		$pool = new Football_Pool_Pool( FOOTBALLPOOL_DEFAULT_SEASON );

		$is_cli = ( $is_cli === true );

		$params = $calculation_steps_cache = [];
		$completed = 0;
		$check = true;
		$result = 0;
		$output = '';
		$msg = null;
		$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
		$simple_calculation_method = Football_Pool_Utils::get_fp_option( 'simple_calculation_method', 0, 'int' ) === 1;

		// Note: Uses the same function for $now as when auto-setting the score date in the bonus questions,
		// otherwise the question won't be included when you also start the calculation within the minute (e.g. when
		// automatic calculation is active).
		$now = current_time( 'mysql', true );
		$calc_start_time = $now;

		// get parameters
		if ( $is_cli === true ) {
			$force_calculation = false;
			$total_iterations = $iteration = $sub_iteration = 0;
			$calculation_step = 'undefined';
			$sub_iterations = '1,1,1,1';
			$user = $ranking = $match = $question = $prev_total_score = $score_order = 0;
			
			extract( $args, EXTR_IF_EXISTS );

			$sub_iterations = explode( ',', $sub_iterations );
		} else {
			$force_calculation = self::post_int( 'force_calculation', 0 ) === 1 || FOOTBALLPOOL_FORCE_CALCULATION;
			$calculation_step = self::post_string( 'calculation_step', 'undefined' );

			$total_iterations = self::post_int( 'total_iterations', 0 );
			$sub_iterations = self::post_string( 'sub_iterations', '1,1,1,1' );
			$sub_iterations = explode( ',', $sub_iterations );

			$iteration = self::post_int( 'iteration', 0 );
			$sub_iteration = self::post_int( 'sub_iteration', 0 );

			$user = self::post_int( 'user', 0 );
			$ranking = self::post_int( 'ranking', 0 );
			$match = self::post_int( 'match', 0 );
			$question = self::post_int( 'question', 0 );
			$prev_total_score = self::post_int( 'prev_total_score', 0 );
			$score_order = self::post_int( 'score_order', 0 );

			$calc_start_time = self::post_string( 'calc_start_time', $now );

			$calculation_steps_cache = self::get_cache_from_db();
		}

		// security
		if ( ! $is_cli ) {
			if ( FOOTBALLPOOL_RANKING_CALCULATION_AJAX === false ) {
				if ( $iteration > 0 ) check_admin_referer( FOOTBALLPOOL_NONCE_SCORE_CALC, 'fp_recalc_nonce' );
			} else {
				check_ajax_referer( FOOTBALLPOOL_NONCE_SCORE_CALC, 'fp_recalc_nonce' );
			}
		}
		$nonce = wp_create_nonce( FOOTBALLPOOL_NONCE_SCORE_CALC );


		// save the retrieved value cache in the variable
		self::$calculation_steps_cache = $calculation_steps_cache;

		// check if we want to start a calculation but another calculation is in progress
		if ( $iteration === 0 ) {
			// set the start time of the calculation (we use this to get all score sources before this date)
			if ( ! Football_Pool_Utils::is_valid_mysql_date( $calc_start_time ) ) $calc_start_time = $now;

			// enable override when the calculation is forced
			if ( $force_calculation ) {
				Football_Pool_Utils::set_fp_option( 'calculation_in_progress', 0 );
			}

			if ( Football_Pool_Utils::get_fp_option( 'calculation_in_progress', 0, 'int' ) === 1 ) {
				// stop, because the system indicates that already there is a calculation in progress
				$calculation_step = 'stop_message';
			} else {
				// continue, lock calculation and set step to 'prepare'
				Football_Pool_Utils::set_fp_option( 'calculation_in_progress', 1 );
				$calculation_step = 'prepare';
			}
		}
		
		// determine the active and new history table
		$active_history_table = $pool->get_score_table( true );
		$new_history_table = $pool->get_score_table( false );
		
		// get the data to work on from the session
		if ( ! in_array( $calculation_step, array( 'cancel_calculation', 'finalize', 'stop_message' ) ) ) {
			$ranking_ids = self::get_rankings();
			$ranking_id = $ranking_ids[$ranking];
			
			$match_ids = self::get_matches( $ranking_id, $calc_start_time );
			$question_ids = self::get_questions( $ranking_id, $calc_start_time );
			
			$user_ids = self::get_user_set( $pool->has_leagues );
			
			if ( $ranking_id === FOOTBALLPOOL_RANKING_DEFAULT ) {
				// no calculation needed if there are no users or no matches and questions finished
				if ( count( $user_ids ) === 0 || ( count( $match_ids ) === 0 && count( $question_ids ) === 0 ) ) {
					$calculation_step = 'no_calc_needed';
				}
			}
		}
		
		// prepare lightbox
		$output .= sprintf( '<h2>%s</h2>' , __( 'Score and ranking calculation', 'football-pool' ) );
		
		// calculation steps
		switch ( $calculation_step ) {
			case 'prepare':
				do_action( 'football_pool_score_calculation_prepare_before' );
				
				$msg = __( 'Preparing the calculation', 'football-pool' );
				$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
				
				// empty the new table
				$check = self::empty_scorehistory( 'all', $new_history_table );

				// reset session vars for calculation
				self::empty_cache();

				$simple_calc_ranking_iteration_counter = 0;
				$total_matches = $total_questions = array( 0, 0 );
				
				$user_ids = self::get_user_set( $pool->has_leagues );
				$total_users = count( $user_ids );
				
				$ranking_ids = self::get_rankings();
				$total_rankings = count( $ranking_ids );
				foreach ( $ranking_ids as $ranking_id ) {
					$ranking_match_ids = self::get_matches( $ranking_id, $calc_start_time );
					$total_ranking_match_ids = count( $ranking_match_ids );
					$total_matches[0] += $total_ranking_match_ids;
					
					$ranking_question_ids = self::get_questions( $ranking_id, $calc_start_time );
					$total_ranking_question_ids = count( $ranking_question_ids );
					$total_questions[0] += $total_ranking_question_ids;
					
					if ( $ranking_id === FOOTBALLPOOL_RANKING_DEFAULT ) {
						$total_matches[1] = $total_ranking_match_ids;
						$total_questions[1] = $total_ranking_question_ids;
					}
					
					if ( $total_ranking_match_ids > 0 || $total_ranking_question_ids > 0 ) {
						$simple_calc_ranking_iteration_counter++;
					}
				}
				
				// calculate total number of iterations
				if ( $simple_calculation_method ) {
					$multiplier = $total_matches[1] === 0 ? 0 : 1;
					$match_iterations = (int) ceil( ( $multiplier * $total_users ) / FOOTBALLPOOL_CALC_STEPSIZE_MATCH );
					
					$multiplier = $total_questions[1] === 0 ? 0 : 1;
					$question_iterations = (int) ceil( ( $multiplier * $total_users ) / FOOTBALLPOOL_CALC_STEPSIZE_QUESTION );
					
					$score_iterations = 0;
					
					$ranking_iterations = (int) ceil( ( ( $simple_calc_ranking_iteration_counter * $total_users ) 
														+ ( $total_rankings - $simple_calc_ranking_iteration_counter ) )
													/ FOOTBALLPOOL_CALC_STEPSIZE_RANKING );
				} else {
					$match_iterations = (int) ceil( ( $total_matches[1] * $total_users ) / FOOTBALLPOOL_CALC_STEPSIZE_MATCH );
					
					$question_iterations = (int) ceil( ( $total_questions[1] * $total_users )
														/ FOOTBALLPOOL_CALC_STEPSIZE_QUESTION );
					
					$score_iterations = (int) ceil( ( $total_users 
															* ( $total_questions[0] + $total_matches[0] + $total_rankings ) ) 
													/ FOOTBALLPOOL_CALC_STEPSIZE_SCORE );
					
					$ranking_iterations = (int) ceil( ( $total_users * ( $total_questions[0] + $total_matches[0] ) )
						/ FOOTBALLPOOL_CALC_STEPSIZE_RANKING );
				}

				$total_iterations = 1						// prepare
									+ $match_iterations		// match score calculation
									+ $question_iterations	// question score calculation
									+ $score_iterations		// incremented total scores calculation
									+ $ranking_iterations	// compute ranking
									+ 1;					// finalize
				
				// adjust for case where there are no matches or no questions
				if ( $total_matches[1] === 0 ) {
					$total_iterations++;
					$match_iterations = 1;
				}
				if ( $total_questions[1] === 0 ) {
					$total_iterations++;
					$question_iterations = 1;
				}
				
				$sub_iterations = array( $match_iterations, $question_iterations, $score_iterations, $ranking_iterations );
				
				// prepare lightbox
				$output .= '<div class="fp-calc-progress" id="fp-calc-progress">';
				$output .= sprintf( '<h4>%s</h4>'
									, __( 'Please do not interrupt this process.', 'football-pool' ) );
				$output .= sprintf( '<p>%s</p>'
									, __( 'Sit back and relax, this may take a while :-)', 'football-pool' ) );
				$output .= '<div id="fp-calc-progressbar"></div>';
				$output .= '<div><div id="ajax-loader"></div><p id="calculation-message">&nbsp;</p></div>';
				$output .= sprintf( '<p id="calculation-timer">%s:&nbsp;&nbsp;<span id="time-elapsed"></span><br>'
									, __( 'Time elapsed', 'football-pool' ) );
				$output .= sprintf( '%s:&nbsp;&nbsp;<span id="time-left"></span></p>'
									, __( 'Estimated time left', 'football-pool' ) );
				$output .= '</div>';
				
				if ( $pool->has_matches && count( $match_ids ) > 0 ) {
					$msg = sprintf( __( "Updating match scores (step %s of %s)", 'football-pool' )
									, 1, $sub_iterations[0] );
					$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
				} else {
					$msg = __( 'No matches to calculate.', 'football-pool' );
					$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
				}

				// continue with match score calculation
				$calculation_step = 'match_scores';

				do_action( 'football_pool_score_calculation_prepare_after' );
				break;
			case 'match_scores':
				$calculation_step = 'match_scores';
				$sub_iteration++;

				if ( $pool->has_matches && count( $match_ids ) > 0 ) {
					$msg = sprintf( __( "Updating match scores (step %s of %s)", 'football-pool' )
									, ( $sub_iteration + 1 ), $sub_iterations[0] );
					$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
					
					$ranking_ids = self::get_rankings();
					$match_results = self::get_match_results();
					
					$i = 0;
					while ( $i++ < FOOTBALLPOOL_CALC_STEPSIZE_MATCH ) {
						$user_id = $user_ids[$user];
						$match_id = $match_ids[$match];
						
						$user_home = $user_away = $home_score = $away_score = $joker_used = null;
						$has_joker = 0;
						
						if ( ! $simple_calculation_method ) {
							// Get user prediction for the current match
							$sql = $wpdb->prepare( "SELECT home_score, away_score, has_joker 
													FROM {$prefix}predictions 
													WHERE user_id = %d AND match_id = %d"
													, $user_id, $match_id );
							$row = $wpdb->get_row( $sql, ARRAY_A );
							if ( $row !== null ) {
								$user_home = is_numeric( $row['home_score'] ) ? (int) $row['home_score'] : null;
								$user_away = is_numeric( $row['away_score'] ) ? (int) $row['away_score'] : null;
								$has_joker = (int) $row['has_joker'];
							}
						
							// Get actual score
							$home_score = (int) $match_results[$match_id]['home_score'];
							$away_score = (int) $match_results[$match_id]['away_score'];
							
							// Calculate the score for this match
							$score = $pool->calc_score(
								$home_score,
								$away_score,
								$user_home,
								$user_away,
								$has_joker,
								$match_id,
								$user_id
							);
							$match_score = $score['score'];
							$full = $score['full'];
							$toto = $score['toto'];
							$goal_bonus = $score['goal_bonus'];
							$goal_diff_bonus = $score['goal_diff_bonus'];
							$total_score = 0;
							$joker_used = $has_joker;
						}
						
						// update for each ranking
						foreach ( $ranking_ids as $ranking_id ) {
							if ( $simple_calculation_method ) {
								// In simple calculation mode we do all matches for a ranking in one iteration
								// and we only add one row with the total score in the history table
								$matches = self::get_matches( $ranking_id, $calc_start_time );
								if ( count( $matches ) > 0 ) {
									$matches_str = implode( ',', $matches );
									$sql = $wpdb->prepare(
										"SELECT 
											p.match_id, p.home_score AS user_home, 
											p.away_score AS user_away, p.has_joker, 
											m.home_score, m.away_score
										FROM {$prefix}predictions p 
										INNER JOIN {$prefix}matches m
											ON ( p.match_id = m.id )
										WHERE p.user_id = %d AND m.id IN ( {$matches_str} )
										  AND ( p.home_score IS NOT NULL OR p.away_score IS NOT NULL )"
										, $user_id
									);
									$rows = $wpdb->get_results( $sql, ARRAY_A );
									$total_score = $match_id = $match_score = $full = $toto = 0;
									$goal_bonus = $goal_diff_bonus = $joker_used = 0;
									foreach ( $rows as $row ) {
										$user_home = is_numeric( $row['user_home'] ) ?
											(int) $row['user_home'] : null;
										$user_away = is_numeric( $row['user_away'] ) ?
											(int) $row['user_away'] : null;

										$match_score = $pool->calc_score(
											(int) $row['home_score'],
											(int) $row['away_score'],
											$user_home,
											$user_away,
											(int) $row['has_joker'],
											(int) $row['match_id'],
											$user_id
										);
										$total_score += $match_score['score'];
										$full += $match_score['full'];
										$toto += $match_score['toto'];
										$goal_bonus += $match_score['goal_bonus'];
										$goal_diff_bonus += $match_score['goal_diff_bonus'];
										$joker_used += (int) $row['has_joker'];
									}
									
									// Add a record to the history table
									$sql = $wpdb->prepare(
										"INSERT INTO {$prefix}{$new_history_table}
											( ranking_id, score_order, type, score_date, source_id, user_id, 
											 score, full, toto, goal_bonus, goal_diff_bonus, joker_used, 
											 total_score, ranking )
										VALUES ( %d, 0, %d, NOW(), 0, %d, 0, %d, %d, %d, %d, %d, %d, 0 )"
										, $ranking_id
										, FOOTBALLPOOL_TYPE_MATCH
										, $user_id
										, $full
										, $toto
										, $goal_bonus
										, $goal_diff_bonus
										, $joker_used
										, $total_score
									);
									$result = $wpdb->query( $sql );
									$check = $check && ( $result !== false );
								}
							} elseif ( in_array( $match_id, self::get_matches( $ranking_id, $calc_start_time ) ) ) {
								// Or when the match is part of the ranking, and we're in normal calculation mode
								$sql = $wpdb->prepare(
									"INSERT INTO {$prefix}{$new_history_table}
										( ranking_id, score_order, type, score_date, source_id, 
										 user_id, score, full, toto, goal_bonus, goal_diff_bonus, 
										 joker_used, total_score, ranking )
									SELECT %d, 0, %d, play_date, id, %d, %d, %d, %d, %d, %d, %d, 0, 0
									FROM {$prefix}matches
									WHERE id = %d"
									, $ranking_id
									, FOOTBALLPOOL_TYPE_MATCH
									, $user_id
									, $match_score
									, $full
									, $toto
									, $goal_bonus
									, $goal_diff_bonus
									, $joker_used
									, $match_id );
								$result = $wpdb->query( $sql );
								$check = $check && ( $result !== false );
							}
						}
						// Next match (for the normal calculation method)
						$match++;
						
						if ( $simple_calculation_method || $match >= count( $match_ids ) ) {
							// We proceed with the next user when all matches are done.
							// Or when we are in simple calculation mode, in that case we always move on
							// to the next user
							$user++;
							$match = 0;
							
							if ( $user >= count( $user_ids ) ) {
								// all users finished, proceed with questions
								$user = 0;
								$sub_iteration = 0;
								$calculation_step = 'question_scores';
								break;
							}
						}
					}
				} else {
					// no matches in this season, proceed with questions
					$sub_iteration = 0;
					$calculation_step = 'question_scores';
				}
				
				if ( $calculation_step === 'question_scores' ) {
					if ( $pool->has_bonus_questions && count( $question_ids ) > 0 ) {
						$msg = sprintf( __( "Updating question scores (step %s of %s)", 'football-pool' )
										, 1, $sub_iterations[1] );
						$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
					} else {
						$msg = __( 'No questions to calculate.', 'football-pool' );
						$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
					}
				}
				break;
			case 'question_scores':
				$calculation_step = 'question_scores';
				$sub_iteration++;
				
				if ( $pool->has_bonus_questions && count( $question_ids ) > 0 ) {
					$msg = sprintf( __( "Updating question scores (step %s of %s)", 'football-pool' )
									, ( $sub_iteration + 1 ), $sub_iterations[1] );
					$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
					
					$ranking_ids = self::get_rankings();
					$i = 0;
					while ( $i++ < FOOTBALLPOOL_CALC_STEPSIZE_QUESTION ) {
						$user_id = $user_ids[$user];
						$question_id = $question_ids[$question];
				
						// update for each ranking
						foreach ( $ranking_ids as $ranking_id ) {
							if ( $simple_calculation_method ) {
								// we do all questions in one iteration when in simple calculation mode
								// and add/update one record in the history table
								$questions = self::get_questions( $ranking_id, $calc_start_time );
								if ( count( $questions ) > 0 ) {
									// get user's total points after calculation of the matches
									$sql = "SELECT total_score FROM {$prefix}{$new_history_table}
											WHERE ranking_id = %d AND user_id = %d";
									$sql = $wpdb->prepare( $sql, $ranking_id, $user_id );
									$match_score = $wpdb->get_var( $sql );
									$total_score = ( $match_score !== null ) ? $match_score : 0;
									
									// do a sum of all points scored for the questions
									$questions_str = implode( ',', $questions );
									$sql = "SELECT 
												SUM( IF ( a.points <> 0, a.points, q.points ) * IFNULL( a.correct, 0 ) )
													AS total_score,
												SUM( CASE WHEN a.correct = 1 THEN 1 ELSE 0 END )
													AS bonus
											FROM {$prefix}bonusquestions q
											LEFT OUTER JOIN {$prefix}bonusquestions_useranswers a 
												ON ( a.question_id = q.id AND ( a.user_id = %d OR a.user_id IS NULL ) )
											WHERE q.score_date IS NOT NULL AND q.id IN ( {$questions_str} )";
									$sql = $wpdb->prepare( $sql, $user_id );
									
									$question_score = $wpdb->get_var( $sql );
									$bonus_right = $wpdb->get_var( null, 1 );
									
									$total_score += ( $question_score !== null ) ? $question_score : 0;
									
									// and update the total_score and total correct questions in the history table with this info
									// we mis-use the score column to store the amount of bonus questions that were correct
									// (this number is needed for the ranking order in case of a tie)
									if ( $match_score === null ) {
										$sql = "INSERT INTO {$prefix}{$new_history_table} 
													( ranking_id, score_order, type, score_date, source_id, user_id, 
														score, full, toto, goal_bonus, goal_diff_bonus, 
														total_score, ranking )
												VALUES
													( %d, 0, %d, NOW(), 0, %d, %d, 0, 0, 0, 0, %d, 0 )";
										$sql = $wpdb->prepare( $sql
																, $ranking_id
																, FOOTBALLPOOL_TYPE_QUESTION
																, $user_id
																, $bonus_right
																, $total_score );
									} else {
										$sql = "UPDATE {$prefix}{$new_history_table} 
												SET total_score = %d, score = %d
												WHERE ranking_id = %d AND user_id = %d";
										$sql = $wpdb->prepare( $sql
																, $total_score
																, $bonus_right
																, $ranking_id
																, $user_id );
									}
									$result = $wpdb->query( $sql );			
									$check = $check && ( $result !== false );
								}
							} elseif ( in_array( $question_id, self::get_questions( $ranking_id, $calc_start_time ) ) ) {
								// or add a record when the question is part of the ranking and we are in normal calculation mode 
								$sql = "INSERT INTO {$prefix}{$new_history_table} 
											( score_order, type, score_date, source_id, user_id
											, score, full, toto, goal_bonus, goal_diff_bonus
											, ranking, ranking_id ) 
										SELECT 
											0, %d, q.score_date, q.id, %d, 
											IF ( a.points <> 0, a.points, q.points ) * IFNULL( a.correct, 0 ), 
											NULL, NULL, NULL, NULL,
											0, %d 
										FROM {$prefix}bonusquestions q
										LEFT OUTER JOIN {$prefix}bonusquestions_useranswers a 
											ON ( a.question_id = q.id AND ( a.user_id = %d OR a.user_id IS NULL ) )
										WHERE q.score_date IS NOT NULL AND q.id = %d";
								$sql = $wpdb->prepare( $sql
														, FOOTBALLPOOL_TYPE_QUESTION
														, $user_id
														, $ranking_id
														, $user_id
														, $question_id );
								$result = $wpdb->query( $sql );			
								$check = $check && ( $result !== false );
							}
						}
						// next question
						$question++;
						
						if ( $simple_calculation_method || $question >= count( $question_ids ) ) {
							// we proceed with the next user when all questions are done
							// or when we are in simple calculation mode, in that case we always move on to the next user
							$user++;
							$question = 0;
							
							if ( $user >= count( $user_ids ) ) {
								// all users finished, proceed with total score calculation
								$user = 0;
								$sub_iteration = 0;
								if ( $simple_calculation_method ) {
									// we can skip to the ranking calculation when in simple calculation mode
									$calculation_step = 'compute_ranking';
								} else {
									// proceed with total score calculation when in normal calculation mode
									$calculation_step = 'total_scores';
								}
								break;
							}
						}
					}
				} else {
					// no bonus questions in this season
					$sub_iteration = 0;
					if ( $simple_calculation_method ) {
						// we can skip to the ranking update when in simple calculation mode
						$calculation_step = 'compute_ranking';
					} else {
						// proceed with total score calculation when in normal calculation mode
						$calculation_step = 'total_scores';
					}
				}
				
				if ( $calculation_step === 'total_scores' ) {
					$msg = sprintf( __( "Updating total scores (step %s of %s)", 'football-pool' )
									, 1, $sub_iterations[2] );
					$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
				} elseif ( $calculation_step === 'compute_ranking' ) {
					$msg = sprintf( __( 'Calculating the user rankings (step %s of %s).', 'football-pool' )
									, 1, $sub_iterations[3] );
					$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
				}

				break;
			case 'total_scores':
				// this calculation step is not needed when using the simple calculation method
				$calculation_step = 'total_scores';
				$sub_iteration++;
				
				$msg = sprintf( __( 'Updating total scores (step %s of %s)', 'football-pool' )
								, ( $sub_iteration + 1 ), $sub_iterations[2] );
				$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
				
				$i = 0;
				while ( $i++ < FOOTBALLPOOL_CALC_STEPSIZE_SCORE ) {
					$user_id = $user_ids[$user];
					$ranking_id = $ranking_ids[$ranking];
					
					// get the row to update
					$row = self::get_score_row( $user_id, $ranking_id, $new_history_table, $simple_calculation_method );
					
					if ( $row !== null ) {
						// update the new total score
						$total_score = (int) $row['score'] + $prev_total_score;
						$sql = $wpdb->prepare( "UPDATE {$prefix}{$new_history_table} 
												SET total_score = %d, score_order = %d
												WHERE user_id = %d AND source_id = %d 
												AND ranking_id = %d AND type = %d"
												, $total_score
												, ++$score_order
												, $user_id
												, $row['source_id']
												, $ranking_id
												, $row['type'] );
						$result = $wpdb->query( $sql );
						$check = $check && ( $result !== false );
						
						$prev_total_score = $total_score;
					} else {
						// next ranking
						self::remove_key_from_cache( 'fp_calc_score_rows' );
						$ranking++;
						$prev_total_score = 0;
						$score_order = 0;
						
						if ( $ranking >= count( $ranking_ids ) ) {
							$ranking = 0;
							$user++;
							
							if ( $user >= count( $user_ids ) ) {
								// when all users are done, proceed with ranking update
								$sub_iteration = 0;
								$user = 0;
								$calculation_step = 'compute_ranking';
								break;
							}
						}
					}
				}
				
				if ( $calculation_step === 'compute_ranking' ) {
					$msg = sprintf( __( 'Calculating the user rankings (step %s of %s).', 'football-pool' )
									, 1, $sub_iterations[3] );
					$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
				}
				break;
			case 'compute_ranking':
				$calculation_step = 'compute_ranking';
				$sub_iteration++;
				
				$msg = sprintf( __( 'Calculating the user rankings (step %s of %s).', 'football-pool' )
								, ( $sub_iteration + 1 ), $sub_iterations[3] );
				$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
				
				$i = 0;
				while ( $i++ < FOOTBALLPOOL_CALC_STEPSIZE_RANKING ) {
					$user_id = $user_ids[$user];
					$ranking_id = $ranking_ids[$ranking];
					$scores_for_ranking = $ranking_order = [];
					$score_for_ranking = 0;
					
					if ( $simple_calculation_method ) {
						// get the ranking
						$ranking_order = self::get_ranking_order_simple( $new_history_table, $pool->has_leagues, $ranking_id );
					} else {
						// for each ranking and score (can be match or question) calculate the ranking at that point
						$scores_for_ranking = self::get_scores_for_ranking( $ranking_id, $new_history_table );
						
						if ( count( $scores_for_ranking ) > 0 ) {
							$score_for_ranking = $scores_for_ranking[$score_order];
							
							// get the ranking
							$ranking_order = self::get_ranking_order(
								$new_history_table,
								$pool->has_leagues,
								$ranking_id,
								$score_for_ranking
														);
						}
					}
					
					if ( count( $ranking_order ) > 0 ) {
						// save ranking for user
						$ranking_for_user = array_search( $user_id, $ranking_order );
						if ( $ranking_for_user !== false ) {
							$ranking_for_user += 1; // because arrays are zero-based
							$sql = $wpdb->prepare( "UPDATE {$prefix}{$new_history_table}
													SET ranking = %d
													WHERE ranking_id = %d AND score_order = %d AND user_id = %d"
													, $ranking_for_user
													, $ranking_id
													, $score_for_ranking
													, $user_id );
							$result = $wpdb->query( $sql );
							$check = $check && ( $result !== false );
						} else {
							// whut? user not found in score table?!?
							error_log( "user '{$user_id}' not found in ranking_order array for ranking '{$ranking_id}'." );
							error_log( var_export( $ranking_order, true ) );
							$check = false;
						}
						
						// next user
						$user++;
						
						if ( $user >= count( $user_ids ) ) {
							$user = 0;
							
							// in normal calculation mode also proceed with next score
							if ( ! $simple_calculation_method ) $score_order++;

							self::remove_key_from_cache( 'fp_calc_ranking_order' );

							if ( $simple_calculation_method || $score_order >= count( $scores_for_ranking ) ) {
								// in simple calculation or when all score rows are done, proceed with next ranking
								$ranking++;
								$score_order = 0;

								self::remove_key_from_cache( 'fp_calc_ranking_scores' );

								if ( $ranking >= count( $ranking_ids ) ) {
									// all rankings finished
									$sub_iteration = 0;
									$calculation_step = 'finalize';
									break;
								}
							}
						}
					} else {
						// no scores in this ranking, so go to next ranking
						self::remove_key_from_cache( 'fp_calc_ranking_scores' );
						self::remove_key_from_cache( 'fp_calc_ranking_order' );
						$ranking++;
						$user = 0;
						$score_order = 0;
						
						if ( $ranking >= count( $ranking_ids ) ) {
							// all rankings finished, so on to the final step to activate the new ranking and stop the calculation
							$ranking = 0;
							$sub_iteration = 0;
							$calculation_step = 'finalize';
							break;
						}
					}
				}
				
				if ( $calculation_step === 'finalize' ) {
					$msg = __( 'Activating new ranking and clean-up.', 'football-pool' );
					$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
				}
				break;
			case 'finalize':
				do_action( 'football_pool_score_calculation_final_before' );
				
				$msg = __( 'Calculation completed. Thanks for your patience.', 'football-pool' );
				$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
				
				// make the new table active
				$pool->set_score_table( $new_history_table );

				// empty the old table
				$check = self::empty_scorehistory( 'all', $active_history_table );

				// empty session storage for this calculation
				self::empty_cache();

				// calculation finished
				Football_Pool_Utils::set_fp_option( 'calculation_in_progress', 0 );
				$completed = 1;

				// save the date & time of this successful calculation
				$calc_finish_time = Football_Pool_Utils::date_from_gmt( date( 'Y-m-d H:i', time() ) );
				update_option( FOOTBALLPOOL_LAST_CALC_DATE, $calc_finish_time );
				
				do_action( 'football_pool_score_calculation_final_after' );
				break;
			case 'cancel_calculation':
				do_action( 'football_pool_score_calculation_cancelled_before' );
				
				// empty session storage
				self::empty_cache();

				// calculation cancelled, so no longer in progress
				Football_Pool_Utils::set_fp_option( 'calculation_in_progress', 0 );
				
				do_action( 'football_pool_score_calculation_cancelled_after' );
				break;
			case 'stop_message':
				// show message
				$str1 = __( 'There is already a calculation in progress. Please wait for it to finish before starting a new one.', 'football-pool' );
				/** @noinspection HtmlUnknownAnchorTarget */
				$str2   = __( 'If - for some reason - this message is wrong, see the <a href="?page=footballpool-help#calculation-already-running">help page</a> for tips on how to force a calculation start.' , 'football-pool' );
				$output .= sprintf( '<p>%s</p><p>%s</p>', $str1, $str2 );
				if ( FOOTBALLPOOL_RANKING_CALCULATION_AJAX ) $output .= self::ok_button();
				if ( $is_cli ) {
					$msg = $str1;
					$msg_type = FOOTBALLPOOL_CALC_WARNING_MESSAGE;
				}
				$completed = 1;
				break;
			case 'no_calc_needed':
				do_action( 'football_pool_score_calculation_no_calc_needed_before' );

				$str = __( 'No matches or questions to calculate, or no users in the pool. Ranking cleared.', 'football-pool' );
				$output .= $str;
				if ( $is_cli ) {
					$msg = $str;
					$msg_type = FOOTBALLPOOL_CALC_INFO_MESSAGE;
				}
				// empty the new table (just in case and because we didn't do the prepare step)
				$check = self::empty_scorehistory( 'all', $new_history_table );
				
				$total_iterations = 2; // just this one and the finalize step
				$calculation_step = 'finalize';

				do_action( 'football_pool_score_calculation_no_calc_needed_after' );
				break;
			default:
				// start with the prepare step
				$calculation_step = 'prepare';
				$check = true;
				break;
		}

		// set the params to send back to the calling script
		$params['colorbox_html'] = $output;
		$params['error'] = false;
		$params['calc_start_time'] = $calc_start_time;
		if ( $check === true ) {
			$params['fp_recalc_nonce'] = $nonce;
			$params['force_calculation'] = $force_calculation ? 1 : 0;
			$params['completed'] = $completed;
			$params['iteration'] = ++$iteration;
			$params['sub_iteration'] = $sub_iteration;
			$params['total_iterations'] = $total_iterations;
			$params['calculation_step'] = $calculation_step;
			$params['user'] = $user;
			$params['ranking'] = $ranking;
			$params['match'] = $match;
			$params['question'] = $question;
			$params['prev_total_score'] = $prev_total_score;
			$params['score_order'] = $score_order;
			$params['sub_iterations'] = implode( ',', $sub_iterations );
			$params['message_type'] = $msg_type;
			// unset($msg);
			if ( isset( $msg ) ) {
				$params['message'] = $msg;
			} else {
				$i = $iteration - 1;
				$params['message'] = "step '{$calculation_step}': user {$user}, match {$match}, question {$question}, ranking {$ranking}, iteration {$i} of {$total_iterations}";
			}
		} else {
			$params['error']        = sprintf( '%s %s: %s'
										, __( 'Step', 'football-pool' )
										, "'{$calculation_step}'"
										, __( 'Something went wrong while (re)calculating the scores. See the <a href="?page=footballpool-help#ranking-calculation">help page</a> for details on solving this problem.', 'football-pool' )
								);
			$params['message']      = sprintf( '%s %s: %s'
										, __( 'Step', 'football-pool' )
										, "'{$calculation_step}'"
										, 'Calculation not successful.'
								);
			$params['message_type'] = FOOTBALLPOOL_CALC_ERROR_MESSAGE;
			
			do_action( 'footballpool_score_calc_error' );
		}
		

		if ( $is_cli ) {
			// store the value cache (self::$calculation_steps_cache) in the params so we can pass
			// it back to the calling cli script.
			$params['calculation_steps_cache'] = self::$calculation_steps_cache;

			return $params;
		} else {
			// store the value cache in the database
			self::persist_cache_in_db();

			if ( FOOTBALLPOOL_RANKING_CALCULATION_AJAX === false ) {
				// remove params that are not needed in the no-ajax calc
				unset( $params['colorbox_html'] );

				printf( '<div>%s</div>', $output );
				printf( '<p>%s</p>', $msg );

				if ( $completed !== 1 && $params['error'] === false ) {
					unset( $params['error'] );
					$url = add_query_arg( $params, "{$_SERVER['PHP_SELF']}?page=footballpool-score-calculation" );
					// printf( '<a href="%s">debug next</a>', $url );
					printf( '<script>location.href = "%s";</script>', $url );
				} else {
					if ( $params['error'] !== false ) {
						printf( '<p class="error">%s</p>', $params['error'] );
					}
				}
			} else {
				Football_Pool_Utils::ajax_response( $params );
			}
		}
	}

	/**
	 * Method to start the calculation.
	 *
	 * @throws Exception
	 */
	public static function admin() {
		self::process();
	}

	/**
	 * Returns the HTML for an OK button that closes the colorbox.
	 *
	 * @return string
	 */
	private static function ok_button() {
		return self::link_button(
			__( 'OK', 'football-pool' ),
			array( '', 'jQuery.colorbox.close()' ),
			true,
			'js-button',
			'primary'
		);
	}

	/**
	 * Returns all users (user ids) that are players in the pool.
	 *
	 * @param bool $has_leagues
	 *
	 * @return array|null
	 */
	private static function get_user_set( bool $has_leagues ): ?array
	{
		$cache_key = 'fp_calc_users';
		if ( ! self::cache_key_exists( $cache_key ) ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			
			if ( $has_leagues ) {
				$sql = "SELECT DISTINCT( u.ID ) FROM {$wpdb->users} u 
						INNER JOIN {$prefix}league_users lu ON ( u.ID = lu.user_id )
						INNER JOIN {$prefix}leagues l ON ( l.id = lu.league_id )
						ORDER BY 1 ASC";
			} else {
				$sql = "SELECT DISTINCT( u.ID ) FROM {$wpdb->users} u 
						LEFT OUTER JOIN {$prefix}league_users lu ON ( u.ID = lu.user_id )
						WHERE lu.league_id > 0 OR lu.league_id IS NULL
						ORDER BY 1 ASC";
			}

			$users = $wpdb->get_col( $sql );
			self::set_value_in_cache(
				$cache_key,
				apply_filters( 'footballpool_score_calc_users', $users, $has_leagues )
			);
		}

		return self::get_value_from_cache( $cache_key );
	}

	/**
	 * @return array Array of all matches with the score result
	 */
	private static function get_match_results(): array
	{
		$cache_key = 'fp_calc_match_results';
		if ( ! self::cache_key_exists( $cache_key ) ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			$match_results = [];
			
			$sql = "SELECT id AS match_id, home_score, away_score FROM {$prefix}matches ORDER BY 1 ASC";
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			foreach ( $rows as $row ) {
				$i = (int) $row['match_id'];
				$match_results[$i]['match_id'] = $i;
				$match_results[$i]['home_score'] = $row['home_score'];
				$match_results[$i]['away_score'] = $row['away_score'];
			}
			self::set_value_in_cache( $cache_key, $match_results );
		}
		
		return self::get_value_from_cache( $cache_key );
	}

	/**
	 * @param int $ranking_id
	 * @param string $date A date in format "Y-m-d H:i" or "Y-m-d H:i:s"
	 *
	 * @return array Array of all matches in the given ranking.
	 */
	private static function get_matches( int $ranking_id, string $date ): array
	{
		$cache_key = "fp_calc_matches_{$ranking_id}";
		if ( ! self::cache_key_exists( $cache_key ) ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			
			if ( $ranking_id !== FOOTBALLPOOL_RANKING_DEFAULT ) {
				$sql = $wpdb->prepare( "SELECT m.id FROM {$prefix}matches m
										INNER JOIN {$prefix}rankings_matches r ON ( m.id = r.match_id )
										WHERE m.home_score IS NOT NULL AND m.away_score IS NOT NULL
										AND r.ranking_id = %d AND m.play_date <= %s
										ORDER BY m.play_date ASC"
										, $ranking_id, $date );
			} else {
				$sql = $wpdb->prepare( "SELECT id FROM {$prefix}matches 
										WHERE home_score IS NOT NULL AND away_score IS NOT NULL 
										AND play_date <= %s
										ORDER BY play_date ASC"
										, $date );
			}

			$ids = $wpdb->get_col( $sql );
			if ( $ids === null ) $ids = [];
			$ids = array_map( 'intval', $ids );

			self::set_value_in_cache(
				$cache_key,
				apply_filters( 'footballpool_score_calc_matches', $ids, $ranking_id )
			);
		}

		return self::get_value_from_cache( $cache_key );
	}

	/**
	 * @param int $ranking_id
	 * @param string $date A date in format "Y-m-d H:i" or "Y-m-d H:i:s"
	 *
	 * @return array Array with all question ids in the given ranking.
	 */
	private static function get_questions( int $ranking_id, string $date ): array
	{
		$cache_key = "fp_calc_questions_{$ranking_id}";
		if ( ! self::cache_key_exists( $cache_key ) ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;

			if ( $ranking_id !== FOOTBALLPOOL_RANKING_DEFAULT ) {
				$sql = $wpdb->prepare( "SELECT q.id FROM {$prefix}rankings_bonusquestions r
										INNER JOIN {$prefix}bonusquestions q ON ( r.question_id = q.id )
										WHERE r.ranking_id = %d AND q.score_date IS NOT NULL AND q.score_date <= %s
										ORDER BY q.score_date ASC"
										, $ranking_id, $date );
			} else {
				$sql = $wpdb->prepare( "SELECT id FROM {$prefix}bonusquestions 
										WHERE score_date IS NOT NULL AND score_date <= %s
										ORDER BY score_date ASC"
										, $date );
			}

			$ids = $wpdb->get_col( $sql );
			if ( $ids === null ) $ids = [];
			$ids = array_map( 'intval', $ids );

			self::set_value_in_cache(
				$cache_key,
				apply_filters( 'footballpool_score_calc_questions', $ids, $ranking_id )
			);
		}

		return self::get_value_from_cache( "fp_calc_questions_{$ranking_id}" );
	}

	/**
	 * @return array|null Array containing all ranking ids
	 */
	private static function get_rankings(): ?array
	{
		$cache_key = 'fp_calc_rankings';
		if ( ! self::cache_key_exists( $cache_key ) ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			
			$sql = "SELECT id FROM {$prefix}rankings ORDER BY id ASC";
			$rankings = $wpdb->get_col( $sql );

			// throw error if the default ranking is missing from the database
			if ( count( $rankings ) === 0 || ! in_array( FOOTBALLPOOL_RANKING_DEFAULT, $rankings ) ) {
				trigger_error( 'Football Pool => default ranking with ID ' . FOOTBALLPOOL_RANKING_DEFAULT .
					' is missing in the `' . FOOTBALLPOOL_DB_PREFIX . 'rankings` table', E_USER_ERROR );
			}

			$rankings = array_map( 'intval', $rankings );

			self::set_value_in_cache(
				$cache_key,
				apply_filters( 'footballpool_score_calc_rankings', $rankings )
			);
		}

		return self::get_value_from_cache( $cache_key );
	}

	/**
	 * @param int $user_id
	 * @param int $ranking_id
	 * @param string $new_history_table
	 * @param bool $simple_calculation_method
	 *
	 * @return array|null An array with values from the scorehistory table or null if all values are processed for this user.
	 */
	private static function get_score_row( int $user_id, int $ranking_id, string $new_history_table, bool $simple_calculation_method ): ?array
	{
		$cache_key = 'fp_calc_score_rows';
		if ( ! self::cache_key_exists( $cache_key ) ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			
			if ( $simple_calculation_method ) {
				$sql = $wpdb->prepare( "SELECT user_id, SUM( score ) AS score 
										FROM {$prefix}{$new_history_table} 
										WHERE ranking_id = %d GROUP BY user_id ORDER BY user_id ASC"
										, $ranking_id );
			} else {
				$sql = $wpdb->prepare( "SELECT score, total_score, source_id, type 
										FROM {$prefix}{$new_history_table} 
										WHERE user_id = %d AND ranking_id = %d AND score_order = 0
										ORDER BY score_date ASC, type ASC, source_id ASC"
										, $user_id, $ranking_id );
			}

			self::set_value_in_cache( $cache_key, $wpdb->get_results( $sql, ARRAY_A ) );
		}

		// get all the rows from the value cache
		$fp_calc_score_rows = self::get_value_from_cache( $cache_key );

		// remove first row from the array so we can return it to the calling script
		$row = array_shift( $fp_calc_score_rows );
		
		// but before we return it, we first save the resulting array back in the value cache
		self::set_value_in_cache( $cache_key, $fp_calc_score_rows );

		// and we reset the value cache if the array was empty
		if ( $row === null ) self::remove_key_from_cache( $cache_key );

		return $row;
	}

	/**
	 * @param int $ranking_id
	 * @param string $new_history_table
	 *
	 * @return array
	 */
	private static function get_scores_for_ranking( int $ranking_id, string $new_history_table ): array
	{
		$cache_key = 'fp_calc_ranking_scores';
		if ( ! self::cache_key_exists( $cache_key ) ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			$sql = $wpdb->prepare( "SELECT DISTINCT( score_order )
									FROM {$prefix}{$new_history_table}
									WHERE ranking_id = %d ORDER BY 1 ASC"
									, $ranking_id );

			self::set_value_in_cache( $cache_key, $wpdb->get_col( $sql ) );
		}

		return self::get_value_from_cache( $cache_key );
	}

	/**
	 * Returns the ranking of users for a ranking id as an array of user ids. The array is sorted top down with highest
	 * ranking users first. This method is used in the simple calculation mode.
	 *
	 * @param string $new_history_table
	 * @param bool $has_leagues
	 * @param int $ranking_id
	 *
	 * @return array
	 */
	private static function get_ranking_order_simple(
		string $new_history_table,
		bool   $has_leagues,
		int    $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT ): array
	{
		$cache_key = 'fp_calc_ranking_order';
		if ( ! self::cache_key_exists( $cache_key ) ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
		
			// Note: score column stores the amount of correct bonus questions in simple calculation mode
			$sql = "SELECT u.ID, s.total_score AS `points`, s.score AS `bonus` FROM {$wpdb->users} u ";
			if ( $has_leagues ) {
				$sql .= "INNER JOIN `{$prefix}league_users` lu ON ( u.ID = lu.user_id ) ";
				$sql .= "INNER JOIN `{$prefix}leagues` l ON ( lu.league_id = l.id ) ";
			} else {
				$sql .= "LEFT OUTER JOIN `{$prefix}league_users` lu ON ( lu.user_id = u.ID ) ";
			}
			$sql .= "LEFT OUTER JOIN `{$prefix}{$new_history_table}` s ON 
						( s.user_id = u.ID AND s.ranking_id = %d ) ";
			$sql .= "WHERE s.ranking_id IS NOT NULL ";
			if ( ! $has_leagues ) $sql .= "AND ( lu.league_id > 0 OR lu.league_id IS NULL ) ";
			$sql .= "ORDER BY `points` DESC, s.`full` DESC, s.`toto` DESC, `bonus` DESC, ";
//			if ( $has_leagues ) $sql .= "lu.league_id ASC, ";
			$sql .= "LOWER( u.display_name ) ASC";
			
			$sql = $wpdb->prepare( $sql, $ranking_id );
			// if you want to change the ranking (e.g. the ordering), you can do so with this filter
			$sql = apply_filters( 'footballpool_get_ranking_order_simple', $sql, $has_leagues, $ranking_id );

			self::set_value_in_cache( $cache_key, $wpdb->get_col( $sql, 0 ) );
		}
		
		return self::get_value_from_cache( $cache_key );
	}

	/**
	 * Returns the ranking of users for a ranking id as an array of user ids. The array is sorted top down with highest
	 * ranking users first. This method is used in the normal calculation mode.
	 *
	 * @param string $new_history_table
	 * @param bool $has_leagues
	 * @param int $ranking_id
	 * @param int $score_order
	 *
	 * @return array
	 */
	private static function get_ranking_order(
		string $new_history_table,
		bool   $has_leagues,
		int    $ranking_id = FOOTBALLPOOL_RANKING_DEFAULT,
		int    $score_order = 0 ): array
	{
		$cache_key = 'fp_calc_ranking_order';
		if ( ! self::cache_key_exists( $cache_key ) ) {
			global $wpdb;
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			$bonus = FOOTBALLPOOL_TYPE_QUESTION;
			$sql = "SELECT 
						u.ID AS `user_id`, 
						COALESCE( MAX( s.total_score ), 0 ) AS `points`, 
						COUNT( IF( s.full = 1, 1, NULL ) ) AS `full`, 
						COUNT( IF( s.toto = 1, 1, NULL ) ) AS `toto`,
						COUNT( IF( s.type = {$bonus} AND score > 0, 1, NULL ) ) AS `bonus`
					FROM {$wpdb->users} u ";
			if ( $has_leagues ) {
				$sql .= "INNER JOIN `{$prefix}league_users` lu ON ( u.ID = lu.user_id ) ";
				$sql .= "INNER JOIN `{$prefix}leagues` l ON ( lu.league_id = l.id ) ";
			} else {
				$sql .= "LEFT OUTER JOIN `{$prefix}league_users` lu ON ( lu.user_id = u.ID ) ";
			}
			$sql .= "LEFT OUTER JOIN `{$prefix}{$new_history_table}` s ON 
						( s.user_id = u.ID AND s.ranking_id = %d AND s.score_order <= %d ) ";
			$sql .= "WHERE s.ranking_id IS NOT NULL ";
			if ( ! $has_leagues ) $sql .= "AND ( lu.league_id > 0 OR lu.league_id IS NULL ) ";
			$sql .= "GROUP BY u.ID
					ORDER BY `points` DESC, `full` DESC, `toto` DESC, `bonus` DESC, ";
//			if ( $has_leagues ) $sql .= "lu.league_id ASC, ";
			$sql .= "LOWER( u.display_name ) ASC";
			$sql = $wpdb->prepare( $sql, $ranking_id, $score_order );
			// if you want to change the ranking (e.g. the ordering), you can do so with this filter
			$sql = apply_filters( 'footballpool_get_ranking_order', $sql, $has_leagues, $ranking_id, $score_order );

			self::set_value_in_cache( $cache_key, $wpdb->get_col( $sql, 0 ) );
		}

		return self::get_value_from_cache( $cache_key );
	}

	/**
	 * Gets the value of the calculation cache that was persisted in the database.
	 *
	 * @return array
	 */
	private static function get_cache_from_db(): array
	{
		return get_option( FOOTBALLPOOL_CALC_SESSION, [] );
	}

	/**
	 * Save the calculation cache in the WP options table. If the cache is empty, we remove the option.
	 */
	private static function persist_cache_in_db() {
		if ( count( self::$calculation_steps_cache ) > 0 ) {
			update_option( FOOTBALLPOOL_CALC_SESSION, self::$calculation_steps_cache );
		} else {
			self::empty_cache();
		}
	}

	/**
	 * Destroy the calculation cache.
	 */
	private static function empty_cache() {
		self::$calculation_steps_cache = [];
		update_option( FOOTBALLPOOL_CALC_SESSION, self::$calculation_steps_cache );
	}

	/**
	 * Check if a key exists in the calculation cache.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	private static function cache_key_exists( string $key ): bool
	{
		return isset( self::$calculation_steps_cache[$key] );
	}

	/**
	 * Remove a value from the calculation cache.
	 *
	 * @param string $key
	 */
	private static function remove_key_from_cache( string $key ) {
		unset( self::$calculation_steps_cache[$key] );
	}

	/**
	 * Retrieve a value from the calculation cache.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	private static function get_value_from_cache( string $key ) {
		return self::$calculation_steps_cache[$key];
	}

	/**
	 * Stores a value in the calculation cache/session, so following steps have the same data.
	 * NO-AJAX and AJAX use the database and WP-CLI passes the cache as return value between steps.
	 *
	 * @param string $key
	 * @param $val
	 */
	private static function set_value_in_cache( string $key, $val ) {
		self::$calculation_steps_cache[$key] = $val;
	}

	/**
	 * Returns the POST value for the given key when the calculation works in AJAX mode or WP-CLI.
	 * Uses the value from the GET when calculation works in NOAJAX mode.
	 *
	 * @param string $key
	 * @param int $default
	 *
	 * @return int
	 */
	private static function post_int( string $key, int $default = 0 ): int
	{
		if ( FOOTBALLPOOL_RANKING_CALCULATION_AJAX === false ) {
			return Football_Pool_Utils::get_int( $key, $default );
		} else {
			return Football_Pool_Utils::post_int( $key, $default );
		}
	}

	/**
	 * Returns the POST value for the given key when the calculation works in AJAX mode or WP-CLI.
	 * Uses the value from the GET when calculation works in NOAJAX mode.
	 *
	 * @param string $key
	 * @param string $default
	 *
	 * @return string
	 */
	private static function post_string( string $key, string $default = '' ): string
	{
		if ( FOOTBALLPOOL_RANKING_CALCULATION_AJAX === false ) {
			return Football_Pool_Utils::get_str( $key, $default );
		} else {
			return Football_Pool_Utils::post_str( $key, $default );
		}
	}
}
