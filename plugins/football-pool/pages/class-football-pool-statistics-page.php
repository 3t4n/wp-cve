<?php

/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

//todo: add a bar or pie chart with jokers used (successfull vs unsuccessful)?
class Football_Pool_Statistics_Page {
	public static function stats_page_title( $title ) {
		if ( @in_the_loop() && is_page() && get_the_ID() == Football_Pool_Utils::get_fp_option( 'page_id_statistics' ) ) {
			$view = Football_Pool_Utils::get_string( 'view', 'stats' );
			if ( ! in_array( $view, array( 'bonusquestion' ,'matchpredictions' ) ) ) {
				$stats = new Football_Pool_Statistics;
				if ( $stats->data_available ) {
					$title .= sprintf( '<span title="%s" class="fp-icon-cog charts-settings-switch fp-pulse" onclick="jQuery( \'#fp-charts-settings\' ).slideToggle( \'slow\' );return false;"></span>'
										, __( 'Chart settings', 'football-pool' )
									);
				}
			}
		}
		
		return $title;
	}
	
	private function settings_panel( $panel_content ) {
		if ( $panel_content === '' ) $panel_content = __( 'No settings available', 'football-pool' );
		
		$output = sprintf( '<div id="fp-charts-settings">%s<p><input type="submit" value="%s"></p></div>'
							, $panel_content
							, __( 'Change charts', 'football-pool' ) 
						);
		$output .= sprintf( '<input type="hidden" name="view" value="%s">',
			Football_Pool_Utils::get_string( 'view', 'stats' ) );
		$output .= sprintf( '<input type="hidden" name="user" value="%d">',
			Football_Pool_Utils::get_int( 'user' ) );
		return $output;
	}
	
	public function page_content() {
		global $pool;
		$user_selector = '';
		/** @noinspection HtmlUnknownTarget */
		$output = sprintf( '<form action="%s" method="get">', get_page_link() );

		$stats = new Football_Pool_Statistics();

		$view = Football_Pool_Utils::get_string( 'view', 'stats' );
		$match = Football_Pool_Utils::get_integer( 'match' );
		$question = Football_Pool_Utils::get_integer( 'question' );
		$user = Football_Pool_Utils::get_integer( 'user' );
		
		$goal_bonus = ( Football_Pool_Utils::get_fp_option( 'goalpoints', FOOTBALLPOOL_GOALPOINTS, 'int' ) > 0 );
		$goal_diff_bonus = ( Football_Pool_Utils::get_fp_option( 'diffpoints', FOOTBALLPOOL_DIFFPOINTS, 'int' ) > 0 );
		
		$user_id = get_current_user_id();

		$league_id = FOOTBALLPOOL_LEAGUE_ALL; // TODO: maybe change this later to the user's league (with the option to show all users)
		
		$users = Football_Pool_Utils::get_integer_array( 'users' );
		if ( $user > 0 && ! in_array( $user, $users ) && $pool->user_is_player( $user ) ) $users[] = $user;
		$include_current_user = Football_Pool_Utils::get_fp_option( 'auto_select_current_user_for_stats', 'int', 1 ) === 1;
		if ( $include_current_user && $user_id > 0 && ! in_array( $user_id, $users )
			&& $pool->user_is_player( $user_id ) ) {
			$users[] = $user_id;
		}
		
		$ranking_display = Football_Pool_Utils::get_fp_option( 'ranking_display', 0 );
		if ( $ranking_display == 1 ) {
			$ranking = Football_Pool_Utils::request_int( 'ranking', FOOTBALLPOOL_RANKING_DEFAULT );
		} elseif ( $ranking_display == 2 ) {
			$ranking = Football_Pool_Utils::get_fp_option( 'show_ranking', FOOTBALLPOOL_RANKING_DEFAULT );
		} else {
			$ranking = FOOTBALLPOOL_RANKING_DEFAULT;
		}
		
		if ( ! $stats->data_available && $view !== 'matchpredictions' ) {
			$output.= sprintf(
				'<h2>%s</h2><p>%s</p>',
				__( 'Statistics not yet available', 'football-pool' ),
				__( 'After the first match you can view your scores and those of other users here.',
					'football-pool' )
			);
		} else {
			$chart_data = new Football_Pool_Chart_Data();
			
			// show the user selector
			if ( $view !== 'matchpredictions' && $view !== 'bonusquestion' && $view !== 'user' ) {
				$rows = apply_filters( 'footballpool_userselector_users', $pool->get_users( $league_id ) );
				if ( count( $rows ) > 0 ) {
					$user_selector .= '<div class="user-selector">';
					// TODO: add user search to user selector for charts
					$user_selector .= '<ol>';
					foreach( $rows as $row ) {
						$selected = in_array( $row['user_id'], $users );
						$user_selector .= sprintf(
							'<li class="user-%d%s">
								<label><input type="checkbox" name="users[]" value="%d" %s/> %s</label>
							</li>'
							, $row['user_id']
							, ( $selected ? ' selected' : '' )
							, $row['user_id']
							, ( $selected ? 'checked="checked" ' : '' )
							, $pool->user_name( $row['user_id'] )
						);
					}
					$user_selector .= '</ol></div>';
				}
			}
			
			$ranking_selector = '';
			if ( in_array( $view, array( 'stats', 'user' ) ) ) {
				// show the ranking selector if applicable
				$user_defined_rankings = $pool->get_rankings( 'user defined' );
				if ( $ranking_display == 1 && count( $user_defined_rankings ) > 0 ) {
					$ranking_selector .= '<div class="ranking-select-wrapper">';
					
					if ( $ranking_display == 1 && count( $user_defined_rankings ) > 0 ) {
						$options = [];
						$options[FOOTBALLPOOL_RANKING_DEFAULT] = '';
						foreach( $user_defined_rankings as $user_defined_ranking ) {
							$options[$user_defined_ranking['id']] = 
								Football_Pool_Utils::xssafe( $user_defined_ranking['name'] );
						}
						$ranking_selector .= sprintf(
							'<br>%s: %s'
							, __( 'Choose ranking', 'football-pool' )
							, Football_Pool_Utils::select(
								'ranking',
								$options,
								$ranking,
								'',
								'statistics-page ranking-select'
							)
						);
					}
					$ranking_selector .= '</div>';
				}
			}
			
			switch ( $view ) {
				case 'bonusquestion': 
					$output .= $stats->show_bonus_question_info( $question );
					if ( $stats->stats_visible ) {
						$output .= $stats->show_answers_for_bonus_question( $question );
						$info = $pool->get_bonus_question_info( $question );
						if ( $stats->stats_enabled && $info['score_date'] !== null ) {
							// chart 1: pie, what did the players score on this bonus question?
							$raw_data = $chart_data->bonus_question_pie_chart_data( $question );
							$chart = new Football_Pool_Chart( 'chart1', 'pie' );
							$chart->data = $chart_data->bonus_question_pie_series_one_question( $raw_data );
							$chart->title = __( 'what did other users score?', 'football-pool' );
							$chart->custom_css = 'stats-page';
							$output .= $chart->draw();
						}
					}
					break;
				case 'matchpredictions':
					$match_info = $pool->matches->get_match_info( $match );
					$output .= $stats->show_match_info( $match_info );
					if ( $stats->stats_visible ) {
						$output .= $stats->show_predictions_for_match( $match_info );
						if ( $stats->stats_enabled && $stats->data_available_for_match( $match ) ) {
							// chart 1: pie, what did the players score with the game predictions for this match?
							$raw_data = $chart_data->predictions_pie_chart_data( $match );
							$chart = new Football_Pool_Chart( 'chart1', 'pie' );
							$chart->data = $chart_data->predictions_pie_series( $raw_data );
							$chart->title = __( 'other users scores', 'football-pool' );
							// $chart->options[] = '';
							$chart->custom_css = 'stats-page';
							$output .= $chart->draw();
						}
					}
					break;
				/** @noinspection PhpMissingBreakStatementInspection */
				case 'user':
					$user_info = get_userdata( $user );
					$output .= $stats->show_user_info( $user_info );
					if ( $stats->stats_visible ) {
						// can't use esc_url() here because it also strips the square brackets from users[]??
						$url = esc_url( add_query_arg( 
												array( 
													'user' => false,
													'view' => false,
													'users[]' => $user_info->ID
												) 
										) );
						$txt = __( 'Compare the scores of %s with other users.', 'football-pool' );
						/** @noinspection PhpFormatFunctionParametersMismatchInspection */
						$output .= sprintf( "<p><a href='%s'>{$txt}</a></p>"
											, $url
											, $pool->user_name( $user_info->ID )
									);
						
						$output .= $this->settings_panel( $ranking_selector );

						$output .= '<div class="charts-container">'; // start charts container

						$pool->get_bonus_questions_for_user( $user );
						// chart 1: pie, what did the players score with the match predictions?
						$raw_data = $chart_data->score_chart_data( [$user], $ranking );
						if ( count( $raw_data ) > 0 ) {
							$chart = new Football_Pool_Chart( 'chart1', 'pie' );
							// only one user
							$chart->data = $chart_data->score_chart_series( $raw_data );
							$chart->data = array_shift( $chart->data );
							$chart->data = $chart->data['data'];
							$chart->title = __( 'scores in matches', 'football-pool' );
							$chart->custom_css = 'stats-page';
							if ( $pool->has_bonus_questions ) $chart->custom_css .= ' stats-pie';
							$output .= $chart->draw();
						}
						if ( $pool->has_bonus_questions ) {
							// chart 4: pie, bonus questions wrong or right
							$raw_data = $chart_data->bonus_question_for_users_pie_chart_data( [$user], $ranking );
							if ( count( $raw_data ) > 0 ) {
								$chart = new Football_Pool_Chart( 'chart4', 'pie' );
								// only one user
								$data = $chart_data->bonus_question_pie_series( $raw_data );
								$chart->data = array_shift( $data );
								$chart->title = __( 'scores in bonus questions', 'football-pool' );
								$chart->custom_css = 'stats-page stats-pie';
								$output .= $chart->draw();
							}
						}

						// chart 5: pie, percentage of total points scored
						$raw_data = $chart_data->points_total_pie_chart_data( $user, $ranking );
						if ( count( $raw_data ) ) {
							$chart = new Football_Pool_Chart( 'chart5', 'pie' );
							$chart->data = $chart_data->points_total_pie_series( $raw_data );
							/* xgettext:no-php-format */
							$chart->title = __( '% of the max points', 'football-pool' );
							if ( $pool->has_jokers ) {
								$chart->options[] = sprintf(
									"subtitle: { text: '(%s)' }",
									_x( 'with the multiplier used',
										'used as a subtitle below "% of the max points" in the charts',
										'football-pool'
									)
								);
							}
							$chart->JS_options[] = "options.series[0].data[0].sliced = true";
							$chart->JS_options[] = "options.series[0].data[0].selected = true";
							$chart->custom_css = 'stats-page stats-pie';
							$output .= $chart->draw();
						}

						$output .= '</div>'; // end charts container
					}
				/** @noinspection PhpMissingBreakStatementInspection */
				case 'stats':
					if ( $view !== 'user' ) {
						if ( count( $users ) < 1 ) {
							$output .= sprintf( '<h2>%s</h2>', __( 'No users selected', 'football-pool' ) );
							$output .= sprintf( '<p>%s %s</p>'
								, sprintf(
									__( 'The top %d players are shown below.', 'football-pool' )
									, FOOTBALLPOOL_TOP_PLAYERS
								)
								, __( 'You can select other users in the chart settings.', 'football-pool' ) );
							
							$rows = $pool->get_pool_ranking_limited( FOOTBALLPOOL_LEAGUE_ALL, FOOTBALLPOOL_TOP_PLAYERS, $ranking );
							foreach( $rows as $row ) $users[] = $row['user_id'];
						} else {
							$output .= sprintf( '<h2>%s</h2>', __( 'You can select other users in the chart settings.', 'football-pool' ) );
						}

						$output .= $this->settings_panel( $user_selector . $ranking_selector );

						// column charts
						// chart6: column, what did the players score with the game predictions?
						$raw_data = $chart_data->score_chart_data( $users, $ranking );
						if ( count( $raw_data ) > 0 ) {
							$chart = new Football_Pool_Chart( 'chart6', 'column' );
							$chart->data = $chart_data->score_chart_series( $raw_data );
							$chart->title = __( 'scores', 'football-pool' );
							$chart->custom_css = 'stats-page';
							$axis = [];
							$axis[] = __( 'full score', 'football-pool' );
							$axis[] = __( 'toto score', 'football-pool' );
							$axis[] = __( 'no score', 'football-pool' );
							if ( $goal_bonus ) {
								$axis[] = __( 'just the goal bonus', 'football-pool' );
							}
							if ( $goal_diff_bonus ) {
								$axis[] = __( 'toto score with goal difference bonus', 'football-pool' );
							}
							$axis_definition = implode( "', '", $axis );
							$chart->options[] = "xAxis: { 
														categories: [ '{$axis_definition}' ]
												}";
							$chart->options[] = "tooltip: {
													formatter: function() {
														return this.x + '<br>'
															+ '<b>' + this.series.name + '</b>: '
															+ this.y;
													}
												}";
							$output .= $chart->draw();
						}

						// chart7: bonus questions
						$raw_data = $chart_data->bonus_question_for_users_pie_chart_data( $users, $ranking );
						if ( count( $raw_data ) > 0 ) {
							$chart = new Football_Pool_Chart( 'chart7', 'column' );
							$chart->data = $chart_data->bonus_question_pie_series( $raw_data, 'no open questions' );
							$chart->title = __( 'bonus question', 'football-pool' );
							$chart->custom_css = 'stats-page';
							$chart->options[] = sprintf( "xAxis: { categories: [ '%s', '%s' ] }"
														, __( 'correct answer', 'football-pool' )
														, __( 'wrong answer', 'football-pool' )
												);
							$chart->options[] = "tooltip: {
													formatter: function() {
														return this.x + '<br>'
															+ '<b>' + this.series.name + '</b>: '
															+ this.y;
													}
												}";
							$output .= $chart->draw();
							// remove last point from series; we don't need it :)
							// $output .= $chart->remove_last_point_from_series();
						}
					}
				default:
					// chart 2: points over time
					if ( count( $users ) >= 1 ) {
						$output .= '<br class="clear">';
						$raw_data = $chart_data->score_per_match_line_chart_data( $users, $ranking );
						if ( count( $raw_data ) > 0 ) {
							$chart = new Football_Pool_Chart( 'chart2', 'line' );
							$chart->data = $chart_data->score_per_match_line_series( $raw_data );
							$chart->title = __( 'points scored', 'football-pool' );
							$chart->custom_css = 'stats-page';
							$txt = __( 'points', 'football-pool' );
							/** @noinspection CssInvalidPropertyValue */
							$chart->options[] = "tooltip: {
													shared: true, crosshairs: true, 
													formatter: function() {
														s = '<b>' + categories[this.x] + '</b><br>';
														jQuery.each( this.points, function( i, point ) {
															s += '<b style=\"color:' + point.series.color + '\">' 
																+ point.series.name + '</b>: ' 
																+ point.y + ' {$txt}<br>';
														} );
														return s;
													}
												}";
							$chart->JS_options[] = 'options.xAxis.labels.enabled = false';
							$chart->JS_options[] = 'options.yAxis.min = -1';
							$chart->JS_options[] = 'options.yAxis.showFirstLabel = false';
							$output .= $chart->draw();
						}
						
						// chart 3: position of the players in the pool
						$raw_data = $chart_data->ranking_per_match_line_chart_data( $users, $ranking );
						if ( count( $raw_data ) > 0 ) {
							$chart = new Football_Pool_Chart( 'chart3', 'line' );
							$chart->data = $chart_data->ranking_per_match_line_series( $raw_data );
							$chart->title = __( 'position in the pool', 'football-pool' );
							$chart->custom_css = 'stats-page';
							$ordinal_suffixes = _x(
								'["th", "st", "nd", "rd", "th"]',
								"The ordinal suffixes th, st, nd, rd, th are used in the sentence 'Xth position in the pool'",
								'football-pool'
							);
							$txt = __( 'position in the pool', 'football-pool' );
							/** @noinspection CssInvalidPropertyValue */
							$chart->options[] = "tooltip: {
													shared: true, crosshairs: true,
													formatter: function() {
														s = '<b>' + categories[this.x] + '</b><br>';
														jQuery.each( this.points, function ( i, point ) {
															s += '<b style=\"color:' + point.series.color + '\">' 
																+ point.series.name + '</b>: ' 
																+ FootballPool.add_ordinal_suffix( point.y, {$ordinal_suffixes} ) 
																+ ' {$txt}<br>';
														} );
														return s;
													}
												}";
							$chart->JS_options[] = sprintf( 'options.yAxis.title.text = "%s"'
															, __( 'position in the pool', 'football-pool' )
													);
							// $chart->JS_options[] = 'options.yAxis.endOnTick = true';
							$chart->JS_options[] = 'options.yAxis.reversed = true';
							$chart->JS_options[] = 'options.yAxis.showFirstLabel = false';
							// $chart->JS_options[] = 'options.yAxis.min = 1';
							$chart->JS_options[] = 'options.xAxis.labels.enabled = false';
							$output .= $chart->draw();
						}
					}
					break;
			}
		}
		
		$output .= sprintf( '<input type="hidden" name="page_id" value="%d"></form>', get_the_ID() );
		return $output;
	}
}
