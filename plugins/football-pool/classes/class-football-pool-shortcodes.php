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

/** @noinspection HtmlUnknownTarget */

// shortcodes
add_shortcode( 'fp-next-match-form', ['Football_Pool_Shortcodes', 'shortcode_next_match_form'] );
add_shortcode( 'fp-last-calc-date', ['Football_Pool_Shortcodes', 'shortcode_last_calc_date'] );
add_shortcode( 'fp-money-in-the-pot', ['Football_Pool_Shortcodes', 'shortcode_money_in_the_pot'] );
add_shortcode( 'fp-user-list', ['Football_Pool_Shortcodes', 'shortcode_user_list'] );
add_shortcode( 'fp-predictions', ['Football_Pool_Shortcodes', 'shortcode_predictions'] );
add_shortcode( 'fp-predictionform', ['Football_Pool_Shortcodes', 'shortcode_predictionform'] );
// add_shortcode( 'fp-last-predictions', ['Football_Pool_Shortcodes', 'shortcode_last_predictions'] );
add_shortcode( 'fp-group', ['Football_Pool_Shortcodes', 'shortcode_group'] );
add_shortcode( 'fp-matches', ['Football_Pool_Shortcodes', 'shortcode_matches'] );
add_shortcode( 'fp-ranking', ['Football_Pool_Shortcodes', 'shortcode_ranking'] );
// todo: I will remove the fp-scores at some point in time (changed the name in v2.10.0)
add_shortcode( 'fp-scores', ['Football_Pool_Shortcodes', 'shortcode_scores'] );
add_shortcode( 'fp-match-scores', ['Football_Pool_Shortcodes', 'shortcode_scores'] );
add_shortcode( 'fp-question-scores', ['Football_Pool_Shortcodes', 'shortcode_question_scores'] );
add_shortcode( 'fp-user-score', ['Football_Pool_Shortcodes', 'shortcode_user_score'] );
add_shortcode( 'fp-user-ranking', ['Football_Pool_Shortcodes', 'shortcode_user_ranking'] );
add_shortcode( 'fp-countdown', ['Football_Pool_Shortcodes', 'shortcode_countdown'] );
add_shortcode( 'fp-register', ['Football_Pool_Shortcodes', 'shortcode_register_link'] );
add_shortcode( 'fp-link', ['Football_Pool_Shortcodes', 'shortcode_link'] );
add_shortcode( 'fp-totopoints', ['Football_Pool_Shortcodes', 'shortcode_totopoints'] );
add_shortcode( 'fp-fullpoints', ['Football_Pool_Shortcodes', 'shortcode_fullpoints'] );
add_shortcode( 'fp-goalpoints', ['Football_Pool_Shortcodes', 'shortcode_goalpoints'] );
add_shortcode( 'fp-diffpoints', ['Football_Pool_Shortcodes', 'shortcode_diffpoints'] );
add_shortcode( 'fp-jokermultiplier', ['Football_Pool_Shortcodes', 'shortcode_jokermultiplier'] );
add_shortcode( 'fp-league-info', ['Football_Pool_Shortcodes', 'shortcode_league_info'] );
add_shortcode( 'fp-stats-settings', ['Football_Pool_Shortcodes', 'shortcode_stats_settings'] );
add_shortcode( 'fp-chart-settings', ['Football_Pool_Shortcodes', 'shortcode_stats_settings'] );
add_shortcode( 'fp-plugin-option', ['Football_Pool_Shortcodes', 'shortcode_plugin_option'] );
add_shortcode( 'fp-next-matches', ['Football_Pool_Shortcodes', 'shortcode_next_matches'] );
add_shortcode( 'fp-last-matches', ['Football_Pool_Shortcodes', 'shortcode_last_matches'] );

class Football_Pool_Shortcodes {
	private static function league_helper( $league, $user_id, $default_league = FOOTBALLPOOL_LEAGUE_ALL ) {
		if ( strtolower( $league ) === 'user' && $user_id > 0 ) {
			global $pool;
			$league = $pool->get_league_for_user( $user_id );
			if ( $league === 0 ) $league = $default_league;
		}
		
		return $league;
	}
	
	private static function date_helper( $date ) {
		if ( $date === 'postdate' ) {
			$the_date = get_the_date( 'Y-m-d H:i' );
		} elseif ( $date !== 'now' && ( $the_date = date_create( $date ) ) !== false ) {
			$the_date = $the_date->format( 'Y-m-d H:i' );
		} else {
			$the_date = '';
		}
		
		return $the_date;
	}
	
	private static function format_helper( $input, $format ) {
		if ( isset( $format ) && is_string( $format ) ) {
			$input = sprintf( $format, $input );
		}
		
		return $input;
	}
	
	// TODO: finish fp-last-predictions shortcode
	//[fp-last-predictions] 
	//  Displays the last X predictions for matches for a set of users.
	//
	//    users   : collection of user Ids, defaults to (only) the logged in user
	//    top     : if set, the "users" setting is ignored and the predictions for the top X users are shown
	//    league  : the league to get the top users from, defaults to the overall league
	//    ranking : the ranking to get the top users from, defaults to the default ranking
	//    num     : number of matches to show, defaults to 5
	public static function shortcode_last_predictions( $atts ) {
		global $wpdb, $pool;
		$users = $top = $league = $ranking = $num = '';
		extract( shortcode_atts( array(
					'users' => '',
					'top' => '',
					'league' => FOOTBALLPOOL_LEAGUE_ALL,
					'ranking' => FOOTBALLPOOL_RANKING_DEFAULT,
					'num' => 5,
				), $atts ) );

		$output = '';
		$user_set = [];

		if ( $users === '' && $top === '' ) {
			$user_set[] = get_current_user_id();
		} elseif ( $top !== '' && is_numeric( $top ) && (int) $top > 0 ) {
			$ranking_users = $pool->get_pool_ranking_limited( $league, $num, $ranking, 'now' );
			foreach ( $ranking_users as $user ) {
				$user_set[] = $user['user_id'];
			}
		} else {
			$users = explode( ',', $users );
			foreach ( $users as $user ) {
				if ( is_numeric( $user ) ) $user_set[] = (int) $user;
			}
		}
		
		if ( count( $user_set ) > 0 ) {
			$user_set = implode( ',', $user_set );
			$prefix = FOOTBALLPOOL_DB_PREFIX;
			$sql = "SELECT p.user_id, p.match_id, p.home_score, p.away_score
					FROM {$prefix}predictions p
					INNER JOIN {$prefix}matches m ON ( m.id = p.match_id )
					WHERE p.user_id IN ( {$user_set} )
					ORDER BY m.play_date DESC, m.id DESC";
			$rows = $wpdb->get_results( $sql, ARRAY_A );
			
			$output .= '<div class="fp-last-predictions">';
			
			$matches = $pool->matches;
			$prev_match = $match = 0;
			
			foreach( $rows as $row ) {
				$user = $pool->user_name( (int) $row['user_id'] );
				$match = (int) $row['match_id'];
				$home_team = Football_Pool_Utils::xssafe( $matches->matches[(int) $row['match_id']]['home_team'] );
				$away_team = Football_Pool_Utils::xssafe( $matches->matches[(int) $row['match_id']]['away_team'] );
				
				if ( $prev_match !== $match ) {
					if ( $prev_match !== 0 ) $output .= '</table>';
					$output .= "<div class='match-teams'>{$home_team}<span>-</span>{$away_team}</div>";
					$output .= '<table class="prediction-table">';
					$prev_match = $match;
				}
				
				$output .= "<tr><td class='user-name'>{$user}</td>";
				$output .= "<td class='match-teams'>{$home_team} - {$away_team}</td>";
				$output .= "<td class='home-score'>{$row['home_score']}</td>";
				$output .= "<td class='score-separator'>-</td>";
				$output .= "<td class='away-score'>{$row['away_score']}</td>";
				$output .= "</tr>";
			}
			
			if ( $match !== 0 ) $output .= '</table>';
			$output .= '</div>';
		}
		
		return apply_filters( 'footballpool_shortcode_html_fp-last-predictions', $output, $atts );
	}

	//[fp-last-calc-date]
	//  Displays the date of the last time a ranking calculation was done.
	//
	//  format	: a datetime value format like this 'Y-m-d H:i'
	public static function shortcode_last_calc_date( $atts ) {
		$format = '';
		extract( shortcode_atts( array(
			'format' => 'd-m-Y \a\t H:i',
		), $atts ) );

		$calc_date = get_option( FOOTBALLPOOL_LAST_CALC_DATE, '' );

		if ( $calc_date !== '' && ( $calc_date = date_create( $calc_date ) ) !== false ) {
			$output = sprintf( '<span class="last-calc">%s</span>', $calc_date->format( $format ) );
		} else {
			$output = '<span class="last-calc-empty"></span>';
		}

		return apply_filters( 'footballpool_shortcode_html_fp-last-calc-date', $output );
	}

	//[fp-money-in-the-pot]
	//    Simple shortcode that calculates the amount of users in a league (or all users) times a given
	//    amount and returns this total sum.
	//    Can be useful if your players add a stake to the pot and you want to show the total amount in
	//    a post or in another part of the website.
	//
	//    League parameter can be entered in the following formats:
	//        league for the user   -> league="user"
	//        league 1              -> league="1"
	//        leagues 1 to 5        -> league="1-5"
	//        leagues 1, 3 and 6    -> league="1,3,6"
	//        leagues 1 to 5 and 10 -> league="1-5,10"
	//
	//    If you omit the league parameter, the shortcode will assume all players in the pool are paying
	//    participants.
	//
	//    league  : optional collection of league ids, or 'user' for the league of the currently logged on user
	//    amount  : the stake
	//    format  : optional format for the output (uses sprintf notation: http://php.net/sprintf)
	public static function shortcode_money_in_the_pot( $atts ) {
		$league = $amount = $format = '';
		extract( shortcode_atts( array(
					'league' => FOOTBALLPOOL_LEAGUE_ALL,
					'amount' => 0,
					'format' => null,
				), $atts ) );

		global $pool;
		$numplayers = 0;
		
		$league = self::league_helper( $league, get_current_user_id() );
		$league_ids = Football_Pool_Utils::extract_ids( $league );

		if ( count( $league_ids ) > 0 ) {
			foreach ( $league_ids as $league_id ) {
				$users = $pool->get_users( $league_id );
				$numplayers += count( $users );
			}
		}
		
		$output = $numplayers * $amount;
		
		return apply_filters( 'footballpool_shortcode_html_fp-money-in-the-pot'
			, self::format_helper( $output, $format ), $atts );
	}
	
	//[fp-match-scores]
	//  Displays the scores or predictions for every user for one or more matches.
	//
	//    Users, match & matchtype arguments can be entered in the following formats (example for matches):
	//        match 1               -> match="1"
	//        matches 1 to 5        -> match="1-5"
	//        matches 1, 3 and 6    -> match="1,3,6"
	//        matches 1 to 5 and 10 -> match="1-5,10"
	//
	//    league           : the league to get the users from, defaults to the overall league
	//    users            : collection of user ids, if set then league setting is ignored
	//    match            : collection of match ids
	//    matchtype        : collection of match type ids
	//    use_querystring  : if set to 'yes' all parameters will be retrieved from the querystring values
	//    show_total       : if set to 'yes' the total score for a row will be shown
	//    hide_zeroes      : if set to 'yes' a score of 0 points will not be shown
    //    display          : defaults to 'points' (scored per match), can also be 'predictions' or 'both'
	public static function shortcode_scores( $atts ) {
		$users = $league = $match = $matchtype = $use_querystring = $show_total = $display = $hide_zeroes = '';
		extract( shortcode_atts( array(
			'users' => '',
			'league' => FOOTBALLPOOL_LEAGUE_ALL,
			'match' => '',
			'matchtype' => '',
			'use_querystring' => 'no',
			'show_total' => 'no',
			'hide_zeroes' => 'no',
            'display' => 'points',
		), $atts ) );

		$show_total = ( $show_total === 'yes' );
		$hide_zeroes = ( $hide_zeroes === 'yes' );

		if ( $use_querystring === 'yes' ) {
			$users = Football_Pool_Utils::get_string( 'users', '' );
			$league = Football_Pool_Utils::get_string( 'league', '' );
			$match = Football_Pool_Utils::get_string( 'match', '' );
			$matchtype = Football_Pool_Utils::get_string( 'matchtype', '' );
		}

		global $pool;
		$output = '';
		
		// get the users
		$the_users = [];
		if ( $users !== '' ) {
			$the_users = Football_Pool_Utils::extract_ids( $users );
		} else {
			if ( is_numeric( $league ) ) {
				$users = $pool->get_users( $league );
				foreach ( $users as $user ) {
					$the_users[] = $user['user_id'];
				}
			}
		}
		
		// get the matches
		$the_matches = [];
		
		$match_ids = Football_Pool_Utils::extract_ids( $match );
		$matchtype_ids = Football_Pool_Utils::extract_ids( $matchtype );
		// add all matches in the match types collection to the match_ids
		$match_ids = array_merge( $match_ids, $pool->matches->get_matches_for_match_type( $matchtype_ids ) );
		
		foreach ( $pool->matches->matches as $match ) {
			if ( in_array( $match['id'], $match_ids ) ) $the_matches[] = $match;
		}

		// only continue if we have some data to show
		if ( count( $the_users ) > 0 && count( $the_matches ) > 0 ) {
			$output .= '<div class="shortcode fp-match-scores">';
			$output .= '<table class="shortcode fp-match-scores"><thead><tr>';
			$output .= '<th class="player-name"></th>';
			foreach ( $the_matches as $match ) {
				if ( $pool->matches->always_show_predictions || $match['match_is_editable'] === false ) {
					$output .= sprintf( '<th class="match"><div><span>%s - %s</span></div>'
						, Football_Pool_Utils::xssafe( $match['home_team'] )
						, Football_Pool_Utils::xssafe( $match['away_team'] )
					);
					if ( is_numeric( $match['home_score'] ) && is_numeric( $match['away_score'] ) ) {
						$output .= sprintf( '<div class="match-result">%s - %s</div>'
							, $match['home_score']
							, $match['away_score']
						);
					}
					$output .= '</th>';
				}
			}
			if ( $show_total === true ) {
				$output .= sprintf( '<th class="total-score">%s</th>', __( 'total', 'football-pool' ) );
			}
			$output .= '</tr></thead>';

			$output .= '<tbody>';
			foreach ( $the_users as $user_id ) {
				$total_score = 0;
				// get the match info for this user
				$match_info = $pool->matches->get_match_info_for_user( $user_id, $match_ids, 'all matches' );
				// If match_info is empty then don't show a line for this user.
				// This can be used in conjunction with the `footballpool_matches_for_user` filter to filter
				// out users that do not have predictions.
				if ( $match_info !== null ) {
					$output .= '<tr>';
					$output .= sprintf( '<td class="player-name user-id-%d">%s</td>'
						, $user_id
						, $pool->user_name( $user_id )
					);
					foreach( $the_matches as $match ) {
						if ( $pool->matches->always_show_predictions || $match['match_is_editable'] === false ) {
							$score = $pool->calc_score(
								$match_info[$match['id']]['real_home_score'],
								$match_info[$match['id']]['real_away_score'],
								$match_info[$match['id']]['home_score'],
								$match_info[$match['id']]['away_score'],
								$match_info[$match['id']]['has_joker'],
								$match['id'],
								$user_id
							);
							$css_class = '';
							if ( $score['full'] > 0 ) $css_class .= ' full';
							if ( $score['toto'] > 0 ) $css_class .= ' toto';
							if ( $score['goal_bonus'] > 0 ) $css_class .= ' goal-bonus';
							if ( $score['goal_diff_bonus'] > 0 ) $css_class .= ' goal-diff-bonus';
							if ( ! is_numeric( $match_info[$match['id']]['home_score'] ) ||
								! is_numeric( $match_info[$match['id']]['away_score'] ) )
								$css_class .= ' not-a-valid-prediction';

							$output .= sprintf( '<td class="score %s%s">',
								Football_Pool_Utils::xssafe( $display ), $css_class );
							if ( $display === 'predictions' || $display === 'both' ) {
								$output .= sprintf( '<span class="user-prediction">%s-%s</span>'
									, $match_info[$match['id']]['home_score']
									, $match_info[$match['id']]['away_score']
								);
							}
							if ( $display === 'points' || $display === 'both' ) {
								if ( ( (int) $score['score'] > 0 ) ||
									( (int) $score['score'] === 0 && ! $hide_zeroes ) ) {
									$output .= sprintf( '<span class="user-score">%s</span>', $score['score'] );
								}
							}
							$output .= '</td>';

							$total_score += (int) $score['score'];
						}
					}
					if ( $show_total === true ) {
						$output .= sprintf( '<td class="score total-score">%s</td>', $total_score );
					}
					$output .= '</tr>';
				}
			}
			$output .= '</tbody>';
			
			$output .= '</table></div>';
		}
		
		return apply_filters( 'footballpool_shortcode_html_fp-match-scores', $output, $atts );
	}

	//[fp-question-scores]
	//  Displays the scores for every user for one or more questions.
	//
	//    Users & question arguments can be entered in the following formats (example for users):
	//        users 1               -> users="1"
	//        users 1 to 5          -> users="1-5"
	//        users 1, 3 and 6      -> users="1,3,6"
	//        users 1 to 5 and 10   -> users="1-5,10"
	//
	//    league           : the league to get the users from, defaults to the overall league
	//    users            : collection of user ids, if set then league setting is ignored
	//    question         : collection of question ids
	//    use_querystring  : if set to 'yes' all parameters will be retrieved from the querystring values
	//    show_total       : if set to 'yes' the total score for a row will be shown
	//    hide_zeroes      : if set to 'yes' a score of 0 points will not be shown
	public static function shortcode_question_scores( $atts ) {
		global $pool;
		$users = $league = $question = $use_querystring = $show_total = $hide_zeroes = '';
		extract( shortcode_atts( array(
			'users' => '',
			'league' => FOOTBALLPOOL_LEAGUE_ALL,
			'question' => '',
			'use_querystring' => 'no',
			'show_total' => 'no',
			'hide_zeroes' => 'no',
		), $atts ) );

		$show_total = ( $show_total === 'yes' );
		$hide_zeroes = ( $hide_zeroes === 'yes' );

		if ( $use_querystring === 'yes' ) {
			$users = Football_Pool_Utils::get_string( 'users', '' );
			$league = Football_Pool_Utils::get_string( 'league', '' );
			$question = Football_Pool_Utils::get_string( 'question', '' );
		}

		$output = '';

		// get the users
		$the_users = [];
		if ( $users !== '' ) {
			$the_users = Football_Pool_Utils::extract_ids( $users );
		} else {
			if ( is_numeric( $league ) ) {
				$users = $pool->get_users( $league );
				foreach ( $users as $user ) {
					$the_users[] = $user['user_id'];
				}
			}
		}

		// get the question ids
		$question_ids = Football_Pool_Utils::extract_ids( $question );

		// get the question info
		$all_questions = [];
		foreach ( $question_ids as $question_id ) {
			$question = $pool->get_bonus_question_info( $question_id );
			if ( $question !== false ) $all_questions[$question_id] = $question;
		}
		// remove all question ids that do not exist in the all questions
		$question_ids = array_keys( $all_questions );

		// only continue if we have some data to show
		if ( count( $the_users ) > 0 && count( $question_ids ) > 0 ) {

			$output .= '<div class="shortcode fp-question-scores">';
			$output .= '<table class="shortcode fp-question-scores"><thead><tr>';
			$output .= '<th class="player-name"></th>';
			$nr = 1;
			foreach ( $question_ids as $question_id ) {
				$output .= sprintf(
					'<th class="question">
						<span class="question-id" title="%1$s">%2$d</span>
						<span class="question-nr" title="%1$s">%3$d</span>
					</th>'
					, esc_attr($all_questions[ $question_id ]['question'])
					, $question_id
					, $nr++
				);
			}
			if ( $show_total === true ) {
				$output .= sprintf( '<th class="total-score">%s</th>', __( 'total', 'football-pool' ) );
			}
			$output .= '</tr><tr class="question-text"><th></th>';
			foreach ( $question_ids as $question_id ) {
				$output .= sprintf( '<th class="question-text">%s</th>', $all_questions[$question_id]['question'] );
			}
			if ( $show_total === true ) {
				$output .= '<th></th>';
			}
			$output .= '</tr></thead>';

			$output .= '<tbody>';
			foreach ( $the_users as $user ) {
				$total_score = 0;
				// get the question info for this user
				$user_question_info = $pool->get_bonus_questions_for_user( $user );
				$temp_questions = [];
				foreach ( $user_question_info as $question ) {
					if ( in_array( $question['id'], $question_ids ) ) {
						$temp_questions[$question['id']] = $question;
					}
				}
				$user_question_info = $temp_questions;
				// If user_question_info is empty then don't show a line for this user.
				// This can be used in conjunction with the `footballpool_bonusquestions_for_user` filter to filter
				// out users that do not have predictions.
				if ( count( $user_question_info ) > 0 ) {
					$output .= '<tr>';
					$output .= sprintf( '<td class="player-name">%s</td>', $pool->user_name( $user ) );
					foreach( $question_ids as $question_id ) {
						$css_class = '';
						$question = $user_question_info[$question_id];

						if ( is_null( $question['correct'] ) ) $question['correct'] = 0;

						$score = 0;
						if ( ! is_null( $question['score_date'] ) ) {
							$score = $question['correct'] *
								($question['user_points'] > 0 ? $question['user_points'] : $question['points']);
						}

						if ( is_null( $question['score_date'] ) ) $css_class .= ' unfinished';
						if ( $question['correct'] === '1' ) $css_class .= ' correct';
						if ( is_null( $question['user_answer'] ) || $question['user_answer'] === '' ) {
							$css_class .= ' no-answer-given';
						}

						$output .= sprintf( '<td class="score%s">', $css_class );
						if ( ! is_null( $question['score_date'] ) ) {
							if ($score > 0 || ($score === 0 && ! $hide_zeroes)) {
								$output .= sprintf('<span class="user-score">%s</span>', $score);
							}
						}
						$output .= '</td>';

						$total_score += $score;
					}
					if ( $show_total === true ) {
						$output .= sprintf( '<td class="score total-score">%s</td>', $total_score );
					}
					$output .= '</tr>';
				}
			}
			$output .= '</tbody>';

			$output .= '</table></div>';
		}

		return apply_filters( 'footballpool_shortcode_html_fp-question-scores', $output, $atts );
	}

	//[fp-stats-settings] or [fp-chart-settings]
	//    Displays a link to the stats settings (only works on the statistics page when needed, otherwise it 
	//    returns an empty string).
	public static function shortcode_stats_settings() {
		return Football_Pool_Statistics_Page::stats_page_title( '' );
	}
	
	//[fp-user-list]
	//    Displays a list of users (in a league).
	//
	//    league  : league ID (optional)
	//    num     : limit the number of users to output (optional)
	//    latest  : if set to 'yes' a list of the last registrations will be returned (only works in combination
	//              with the num parameter) (optional)
	public static function shortcode_user_list( $atts ) {
		global $pool;
		$league = '';
		$num = '';
		$latest = false;
		extract( shortcode_atts( array(
			'league' => 0,
			'num' => $num,
			'latest' => 'no',
		), $atts ) );
		
		$output = '';
		$user_id = get_current_user_id();
		$league = self::league_helper( $league, $user_id );
		$latest = ( $latest === 'yes' );

		if ( is_numeric( $league ) && $league >= 0 ) {
			$template_start = sprintf( '<ol class="fp-user-list league-%s">', $league );
			$template_start = apply_filters( 'footballpool_fp-user-list_template_start', $template_start, $league, $user_id );
			
			$template_end = '</ol>';
			$template_end = apply_filters( 'footballpool_fp-user-list_template_end', $template_end, $league, $user_id );

			$row_template = '<li class="fp-user-list-row user-%user_id%">
								<span class="user-avatar">%user_avatar%</span>
								<span class="user-name">%user_name%</span></li>';
			$row_template = apply_filters( 'footballpool_fp-user-list_template_row', $row_template, $league, $user_id );
			
			$template_params = [];
			$template_params = apply_filters( 'footballpool_fp-user-list_template_params', $template_params, $league, $user_id );
			
			$output .= Football_Pool_Utils::placeholder_replace( $template_start, $template_params );
			
			$users = $pool->get_users( $league, 'user_registered DESC, user_name ASC' );

			// Should we limit the results to a max number of users?
			if ( is_numeric( $num ) && $num > 0 && count( $users ) > 0 ) {
				if ( $latest === true ) {
					// Only query users from the given league, order by registration date (desc) and return top X
					$user_ids = array_column( $users, 'user_id' );
					$user_query = new WP_User_Query( [
							'include' => $user_ids,
							'number' => $num,
							'orderby' => 'user_registered',
							'order' => 'DESC',
							'fields' => ['id', 'user_registered']
						]
					);
					// Execute query and retrieve only the user ID's from the resulting array
					$user_ids = array_column( $user_query->get_results(), 'id' );
					// Filter the list of pool users with these user ID's to get the final list
					$users = array_filter( $users, function( $item ) use ( $user_ids ) {
						return in_array( $item['user_id'], $user_ids );
					} );
				} else {
					$users = array_slice( $users, 0, $num );
				}
			}

			// make sure we can paginate the user list, so filter this array
			$users = apply_filters( 'footballpool_fp-user-list_users', $users, $league, $user_id );

			$user_page = Football_Pool::get_page_link( 'user' );

			foreach ( $users as $user ) {
				if ( $pool->has_leagues ) {
					$user_league_id = $user['league_id'];
					$user_league_name = $pool->league_name( $user['league_id'] );
					$user_league_image = $pool->league_image( $user['league_id'] );
				} else {
					$user_league_id = _x( 'n.a.', 'abbreviation for not applicable', 'football-pool' );
					$user_league_name = _x( 'n.a.', 'abbreviation for not applicable', 'football-pool' );
					$user_league_image = _x( 'n.a.', 'abbreviation for not applicable', 'football-pool' );
				}
				
				$template_row_params = array(
					'user_id' => $user['user_id'],
					'user_name' => $user['user_name'],
					'user_email' => $user['email'],
					'user_link' => esc_url( add_query_arg( array( 'user' => $user['user_id'] ), $user_page ) ),
					'user_league_id' => $user_league_id,
					'user_league_name' => $user_league_name,
					'user_league_image' => $user_league_image,
					'user_avatar' => $pool->get_avatar( $user['user_id'], 'medium' ),
				);
				$template_row_params = apply_filters( 'footballpool_fp-user-list_template_row_params'
													, $template_row_params, $league, $user );
				
				$output .= Football_Pool_Utils::placeholder_replace( $row_template, $template_row_params );
			}
			
			$output .= Football_Pool_Utils::placeholder_replace( $template_end, $template_params );
		}
		
		return $output;
	}
	
	//[fp-league-info] 
	//    Displays info about a league. 
	//    E.g the total points or the average points (points divided by the number of players) of a league.
	//
	//    league  : league ID, or 'user' for the league of the currently logged on user
	//    info    : what info to show:
	//              - name: name of league
	//              - points: total points in the league
	//              - avgpoints: average points (total divided by number of players)
	//              - wavgpoints: weighted average points (average weighted by the number of predictions)
	//              - numplayers: number of players in the league
	//              - playernames: list of players names
	//    ranking : optional ranking ID (defaults to the default ranking) when used in conjunction with the points,
	//              average points or weighted average
	//    format  : optional format for the output (uses sprintf notation: http://php.net/sprintf)
	public static function shortcode_league_info( $atts ) {
		global $pool;
		$league = $info = $ranking = $format = '';
		extract( shortcode_atts( array(
					'league' => FOOTBALLPOOL_LEAGUE_ALL,
					'info' => 'name',
					'ranking' => FOOTBALLPOOL_RANKING_DEFAULT,
					'format' => null,
				), $atts ) );
		
		$output = '';
		
		$league = self::league_helper( $league, get_current_user_id() );
		
		if ( is_numeric( $league ) 
				&& in_array( $info, ['name', 'points', 'avgpoints', 'wavgpoints', 'numplayers', 'playernames'] ) ) {
			if ( $pool->has_leagues && array_key_exists( $league, $pool->leagues ) ) {
				if ( $info === 'name' ) {
					$output = Football_Pool_Utils::xssafe( $pool->leagues[$league]['league_name'] );
				} else {
					$rows = $pool->get_pool_ranking( $league, $ranking );
					if ( count( $rows ) === 0 ) {
						// no data in the pool ranking yet, or at least nothing is returned
						// so try to get a list of users
						$users = $pool->get_users( $league );
						$rows = [];
						$i = 0;
						foreach ( $users as $user ) {
							$rows[$i]['ranking'] = $i+1;
							$rows[$i]['user_id'] = $user['user_id'];
							$rows[$i]['points'] = 0;
							$i++;
						}
					}
					$numplayers = count( $rows );
					if ( $info === 'numplayers' ) {
						$output = $numplayers;
					} elseif ( in_array( $info, array( 'points', 'avgpoints', 'wavgpoints' ) ) ) {
						$points = 0;
						$users = [];
						foreach ( $rows as $row ) {
							$users[] = $row['user_id'];
							$points += $row['points'];
						}
						if ( $info === 'points' ) {
							$output = $points;
						} elseif ( $info === 'avgpoints' ) {
							$output = ( $numplayers > 0 ) ? ( $points / $numplayers ) : 0;
						} elseif ( $info === 'wavgpoints' ) {
							// weighted average, number of predictions is the weight
							$num_predictions = $pool->get_prediction_count_per_user( $users, $ranking );
							$w = $x = $wx = $sum_w = $sum_wx = 0;
							foreach ( $rows as $row ) {
								$w = isset( $num_predictions[$row['user_id']] ) ? $num_predictions[$row['user_id']] : 0;
								$x = $row['points'];
								$wx = $w * $x;
								$sum_w += $w;
								$sum_wx += $wx;
							}
							$output = ( $sum_w > 0 ) ? ( $sum_wx / $sum_w ) : 0;
						}
					} elseif ( $info === 'playernames' ) {
						$output = '<ul class="fp-player-list shortcode">';
						foreach ( $rows as $row ) {
							$output .= sprintf( '<li id="fp-player-list-%d">%s</li>'
								, $row['user_id']
								, Football_Pool_Utils::xssafe( $pool->user_name( $row['user_id'] ) )
							);
						}
						$output .= '</ul>';
					}
				}
			}
		}
		
		return apply_filters( 'footballpool_shortcode_html_fp-league-info', self::format_helper( $output, $format ), $atts );
	}
	
	//[fp-matches] 
	//    Displays a matches table for a given collection of matches or match types. 
	//    All arguments (except group) can be entered in the following formats (example for matches):
	//        match 1               -> match="1"
	//        matches 1 to 5        -> match="1-5"
	//        matches 1, 3 and 6    -> match="1,3,6"
	//        matches 1 to 5 and 10 -> match="1-5,10"
	//    If an argument is left empty it is ignored. If group is given, all other arguments are ignored.
	//
	//    match     : collection of match ids 
	//    matchtype : collection of match type ids
	//    group     : a group ID
	public static function shortcode_matches( $atts ) {
		global $pool;
		$match = $matchtype = $group = '';
		extract( shortcode_atts( array(
					'match' => '',
					'matchtype' => '',
					'group' => '',
				), $atts ) );
		
		$output = '';
		
		$matches = $pool->matches;
		$the_matches = [];
		
		if ( is_numeric( $group ) ) {
			$groups = new Football_Pool_Groups;
			$the_matches = $groups->get_plays( (int) $group );
		} else {
			// extract all ids from the arguments
			$match_ids = Football_Pool_Utils::extract_ids( $match );
			$matchtype_ids = Football_Pool_Utils::extract_ids( $matchtype );
			// add all matches in the match types collection to the match_ids
			$match_ids = array_merge( $match_ids, $matches->get_matches_for_match_type( $matchtype_ids ) );
			
			foreach ( $matches->matches as $match ) {
				if ( in_array( $match['id'], $match_ids ) ) $the_matches[] = $match;
			}
		}
		
		if ( count( $the_matches ) > 0 ) {
			$output .= $matches->print_matches( $the_matches, 'shortcode matches-shortcode' );
		}
		return apply_filters( 'footballpool_shortcode_html_fp-matches', $output, $atts );
	}

	//[fp-next-matches]
	//    Displays a matches table for the next matches.
	//    Matchtype can be entered in the following formats:
	//        matchtype 1              -> matchtype="1"
	//        matchtypes 1 to 5        -> matchtype="1-5"
	//        matchtypes 1, 3 and 6    -> matchtype="1,3,6"
	//        matchtypes 1 to 5 and 10 -> matchtype="1-5,10"
	//
	//    date      : show matches that are scheduled after this date
	//                possible values 'now', 'postdate', a datetime value formatted like this 'Y-m-d H:i',
	//                defaults to 'now'
	//    matchtype : only include matches for the given match type (optional)
	//    group     : only include matches for the given group (optional)
	//    num       : how many matches to show (defaults to 5)
	public static function shortcode_next_matches( $atts ) {
		global $pool;
		$date = $matchtype = $group = $num = '';
		extract( shortcode_atts( array(
					'date' => 'now',
					'matchtype' => '',
					'group' => '',
					'num' => 5,
				), $atts ) );

		$output = '';
		
		$the_date = self::date_helper( $date );
		if ( !is_numeric( $num ) ) $num = 5;
		
		$matches = $pool->matches;
		$the_matches = $match_ids = $next_matches = [];
		
		if ( is_numeric( $group ) ) {
			$groups = new Football_Pool_Groups();
			$the_matches = $groups->get_plays( (int) $group );
		} elseif ( $matchtype !== '' ) {
			// extract all ids from the matchtype
			$matchtype_ids = Football_Pool_Utils::extract_ids( $matchtype );
			// get all matches for the match types collection
			$match_ids = $matches->get_matches_for_match_type( $matchtype_ids );
			
			foreach ( $matches->matches as $match ) {
				if ( in_array( $match['id'], $match_ids ) ) $the_matches[] = $match;
			}
		} else {
			$the_matches = $matches->matches;
		}
		
		// remove matches before given date and include $num matches to the $next_matches array
		$ts = new DateTime( Football_Pool_Utils::gmt_from_date( $the_date ) );
		$ts = $ts->format( "U" );
		foreach ( $the_matches as $match ) {
			if ( $ts <= $match['match_timestamp'] && count( $next_matches ) < (int) $num ) {
				$next_matches[] = $match;
			}
		}
		
		$next_matches = apply_filters( 'footballpool_shortcode_fp-next-matches_the_matches', $next_matches );
		if ( count( $next_matches ) > 0 ) {
			$output .= $matches->print_matches( $next_matches, 'shortcode nextmatches-shortcode' );
		}
		return apply_filters( 'footballpool_shortcode_html_fp-next-matches', $output, $atts );
	}

	//[fp-last-matches]
	//    Displays a matches table for the last matches.
	//    Matchtype can be entered in the following formats:
	//        matchtype 1              -> matchtype="1"
	//        matchtypes 1 to 5        -> matchtype="1-5"
	//        matchtypes 1, 3 and 6    -> matchtype="1,3,6"
	//        matchtypes 1 to 5 and 10 -> matchtype="1-5,10"
	//
	//    date      : show matches that are scheduled before this date
	//                possible values 'now', 'postdate', a datetime value formatted like this 'Y-m-d H:i',
	//                defaults to 'now'
	//    matchtype : only include matches for the given match type (optional)
	//    group     : only include matches for the given group (optional)
	//    num       : how many matches to show (defaults to 5)
	public static function shortcode_last_matches( $atts ) {
		global $pool;
		$date = $matchtype = $group = $num = '';
		extract( shortcode_atts( array(
			'date' => 'now',
			'matchtype' => '',
			'group' => '',
			'num' => 5,
		), $atts ) );

		$output = '';

		$the_date = self::date_helper( $date );
		if ( !is_numeric( $num ) ) $num = 5;

		$matches = $pool->matches;
		$the_matches = $match_ids = $result_matches = [];

		if ( is_numeric( $group ) ) {
			$groups = new Football_Pool_Groups();
			$the_matches = $groups->get_plays( (int) $group );
		} elseif ( $matchtype !== '' ) {
			// extract all ids from the matchtype
			$matchtype_ids = Football_Pool_Utils::extract_ids( $matchtype );
			// get all matches for the match types collection
			$match_ids = $matches->get_matches_for_match_type( $matchtype_ids );

			foreach ( $matches->matches as $match ) {
				if ( in_array( $match['id'], $match_ids ) ) $the_matches[] = $match;
			}
		} else {
			$the_matches = $matches->matches;
		}

		// Make sure that the matches are sorted date desc
		usort( $the_matches, function( $a, $b ) {
			return (int) $b['match_timestamp'] - (int) $a['match_timestamp'];
		} );

		// Remove matches after given date, that have a score and include $num matches to the $last_matches array
		$ts = new DateTime( Football_Pool_Utils::gmt_from_date( $the_date ) );
		$ts = $ts->format( "U" );
		foreach ( $the_matches as $match ) {
			if ( $ts >= $match['match_timestamp'] && $match['match_has_finished']
				&& count( $result_matches ) < (int) $num ) {
				$result_matches[] = $match;
			}
		}

		$result_matches = apply_filters( 'footballpool_shortcode_fp-last-matches_the_matches', $result_matches );
		if ( count( $result_matches ) > 0 ) {
			$output .= $matches->print_matches( $result_matches, 'shortcode lastmatches-shortcode' );
		}
		return apply_filters( 'footballpool_shortcode_html_fp-last-matches', $output, $atts );
	}

	//[fp-predictions]
	//  Displays the prediction and score table for a given match or question. 
	//  If an invalid match or question is given, the shortcode returns the default text.
	//
	//    match           : match Id
	//    question        : question Id
	//    text            : a text to show if no prediction table can be displayed, defaults to no text
	//    use_querystring : if set to 'yes', then the match and/or question will be retrieved from the querystring
	public static function shortcode_predictions( $atts ) {
		global $pool;
		$match = $question = $text = $use_querystring = '';
		extract( shortcode_atts( array(
					'match' => null,
					'question' => null,
					'text' => '',
					'use_querystring' => 'no',
				), $atts ) );
		
		$output = '';
		
		if ( $use_querystring === 'yes' ) {
			$match = Football_Pool_Utils::get_int( 'match', 0 );
			$question = Football_Pool_Utils::get_int( 'question', 0 );
		}
		
		if ( is_numeric( $match ) || is_numeric( $question ) ) {
			$stats = new Football_Pool_Statistics;
			
			$match = (int) $match;
			if ( $match > 0 ) {
				$matches = $pool->matches;
				$match_info = $matches->get_match_info( $match );
				if ( count( $match_info ) > 0 ) {
					if ( $matches->always_show_predictions || $match_info['match_is_editable'] == false ) {
						$output .= $stats->show_predictions_for_match( $match_info );
					}
				}
			}
			
			$question = (int) $question;
			if ( $question > 0 ) {
				$question_info = $pool->get_bonus_question_info( $question );
				if ( $question_info ) {
					if ( $pool->always_show_predictions || $question_info['question_is_editable'] == false ) {
						$output .= $stats->show_answers_for_bonus_question( $question );
					}
				}
			}
			
			if ( $output === '' ) {
				$output = Football_Pool_Utils::xssafe( $text );
			}
		}
		
		return apply_filters( 'footballpool_shortcode_html_fp-predictions', $output, $atts );
	}
	
	//[fp-user-ranking] 
	//  Displays the ranking for a given user in the given ranking.  
	//
	//    user        : user Id, defaults to the logged in user 
	//    ranking     : ranking Id, defaults to the default ranking
	//    league_rank : if "1" then the ranking in user's league is returned, defaults to 0
	//    date        : show score up until this date, 
	//                  possible values 'now', 'postdate', a datetime value formatted like this 'Y-m-d H:i',
	//                  defaults to 'now'
	//    text        : text to display if no user or no ranking is found, defaults to ""
	public static function shortcode_user_ranking( $atts ) {
		global $pool;
		$user = $ranking = $date = $text = $league_rank = '';
		extract( shortcode_atts( array(
					'user' => '',
					'ranking' => FOOTBALLPOOL_RANKING_DEFAULT,
					'date' => 'now',
					'text' => '',
					'league_rank' => 'no',
				), $atts ) );
		
		$output = Football_Pool_Utils::xssafe( $text );
		
		if ( $user === '' || ! is_numeric( $user ) ) {
			$user = get_current_user_id();
		}
		
		if ( ( int ) $user > 0 ) {
			if ( $league_rank === 'yes' && $pool->has_leagues ) {
				$league_id = $pool->get_league_for_user( $user );
				if ( $league_id > 0 ) {
					$league_ranking = $pool->get_pool_ranking( $league_id, $ranking );
					foreach ( $league_ranking as $row ) {
						if ( $row['user_id'] == $user ) {
							$output = $row['ranking'];
							break;
						}
					}
				}
			} else {
				$rank = $pool->get_user_rank( $user, $ranking, self::date_helper( $date ) );
				if ( $rank !== null ) $output = $rank;
			}
		}
		
		return apply_filters( 'footballpool_shortcode_html_fp-user-ranking', $output, $atts );
	}
	
	//[fp-user-score] 
	//  Displays the score for a given user in the given ranking.  
	//
	//    user    : user Id, defaults to the logged in user 
	//    ranking : ranking Id, defaults to the default ranking
	//    date    : show score up until this date, 
	//              possible values 'now', 'postdate', a datetime value formatted like this 'Y-m-d H:i',
	//              defaults to 'now'
	//    text    : text to display if no user or no score is found, defaults to "0"
	//    use_querystring : if set to 'yes', then the user will be retrieved from the querystring
	public static function shortcode_user_score( $atts ) {
		global $pool;
		$user = $ranking = $date = $text = $use_querystring = '';
		extract( shortcode_atts( array(
			'user' => '',
			'ranking' => FOOTBALLPOOL_RANKING_DEFAULT,
			'date' => 'now',
			'text' => '0',
			'use_querystring' => 'no',
		), $atts ) );

		if ( $use_querystring === 'yes' ) {
			$user = Football_Pool_Utils::get_int( 'user', 0 );
		} else {
			if ( $user === '' || ! is_numeric( $user ) ) {
				$user = get_current_user_id();
			}
		}

		$output = Football_Pool_Utils::xssafe( $text );

		if ( (int) $user > 0 ) {
			$score = $pool->get_user_score( $user, $ranking, self::date_helper( $date ) );
			if ( $score !== null ) $output = $score;
		}
		
		return apply_filters( 'footballpool_shortcode_html_fp-user-score', $output, $atts );
	}

	//[fp-next-match-form]
	// Displays the prediction form for the next match. If multiple matches start at the same time,
	// the shortcode will show all. Except if optional parameter 'max' is given.
	//
	//    num     : maximum number of matches to show in the form
	public static function shortcode_next_match_form( $atts ) {
		global $pool;
		$num = 0;
		extract( shortcode_atts( array(
			'num' => 0,
		), $atts ) );

		$user_id = get_current_user_id();
		$user_is_player = $pool->user_is_player( $user_id );

		if ( ! is_user_logged_in() || $user_is_player !== true ) {
			return '<div class="shortcode fp-next-match-form"><span class="not-a-valid-user"></span></div>';
		}

		$matches = $pool->matches;

		$output = "<div class='shortcode fp-next-match-form'>";

		// save user input
		$id = Football_Pool_Utils::get_counter_value( 'fp_predictionform_counter' );
		$output .= $pool->prediction_form_update( $id );

		$next_matches = $pool->matches->get_next_match();

		if ( $next_matches === false ) {
			$output .= '<span class="no-next-match"></span>';
		} else {
			$num = (int) $num;
			if ( $num > 0 ) $next_matches = array_slice( $next_matches, 0, $num, true );

			$match_ids = [];
			foreach ( $next_matches as $match ) $match_ids[] = $match['id'];

			$match_info = $matches->get_match_info_for_user( $user_id, $match_ids );

			$output .= $pool->prediction_form_start( $id );
			$output .= $pool->prediction_form_matches( $match_info, false, $id );
			$output .= $pool->prediction_form_end( $id );
		}

		$output .= "</div>";

		return apply_filters( 'footballpool_shortcode_html_fp-next-match-form', $output, $atts );
	}

	//[fp-predictionform] 
	//    All arguments can be entered in the following formats (example for matches):
	//        match 1               -> match="1"
	//        matches 1 to 5        -> match="1-5"
	//        matches 1, 3 and 6    -> match="1,3,6"
	//        matches 1 to 5 and 10 -> match="1-5,10"
	//    If an argument is left empty it is ignored. Matches are always displayed first.
	//    If the current visitor is not logged in, the shortcode returns a message to log on or register.
	//
	//    match     : collection of match ids 
	//    question  : collection of question ids
	//    matchtype : collection of match type ids
	public static function shortcode_predictionform( $atts ) {
		global $pool;
		$default_message =
			sprintf( __( 'You have to be a <a href="%s">registered</a> user and <a href="%s">logged in</a> to play in this pool.', 'football-pool' )
						, wp_registration_url()
						, wp_login_url( get_permalink() )
					);
		$match = $question = $matchtype = $text = '';
		extract( shortcode_atts( array(
					'match' => '',
					'question' => '',
					'matchtype' => '',
					'text' => $default_message,
				), $atts ) );
		
		$user_id = get_current_user_id();
		$user_is_player = $pool->user_is_player( $user_id );

		// todo: add option to show a disabled form for non-registered users.
		if ( ! is_user_logged_in() || $user_is_player !== true ) {
			return $text;
		}
		
		// $questions = new Football_Pool_Questions;
		$matches = $pool->matches;
		
		// save user input
		$id = Football_Pool_Utils::get_counter_value( 'fp_predictionform_counter' );
		$output = $pool->prediction_form_update( $id );
		
		// extract all ids from the arguments
		$match_ids = Football_Pool_Utils::extract_ids( $match );
		$question_ids = Football_Pool_Utils::extract_ids( $question );
		$matchtype_ids = Football_Pool_Utils::extract_ids( $matchtype );
		// add all matches in the match types collection to the match_ids
		$match_ids = array_merge( $match_ids, $matches->get_matches_for_match_type( $matchtype_ids ) );

		$matches = $matches->get_match_info_for_user( $user_id, $match_ids );
		$questions = $pool->get_bonus_questions_for_user( $user_id, $question_ids );
		
		// display form(s)
		$output .= $pool->prediction_form_start( $id );
		$output .= $pool->prediction_form_matches( $matches, false, $id );
		$output .= $pool->prediction_form_questions( $questions, false, $id );
		$output .= $pool->prediction_form_end( $id );
		
		return apply_filters( 'footballpool_shortcode_html_fp-predictionform', $output, $atts );
	}
	
	//[fp-group]
	//		id	: show the standing for the group with this id, defaults to a non-existing group and thus
	//			  will not show anything when none is given.
	public static function shortcode_group( $atts ) {
		$id = '';
		extract( shortcode_atts( array(
					'id' => 1,
				), $atts ) );
		
		$output = '';
		
		$groups = new Football_Pool_Groups;
		$group_names = $groups->get_group_names();
		
		if ( is_numeric( $id ) && array_key_exists( $id, $group_names ) ) {
			$output = $groups->print_group_standing( $id, 'wide', 'shortcode' );
		}
		
		return apply_filters( 'footballpool_shortcode_html_fp-group', $output, $atts );
	}
	
	//[fp-ranking] 
	//		league	: only show users in this league, defaults to all. Can be string 'user' to use the league
	//                of the logged on user.
	//		ranking	: only show points from this ranking, defaults to complete ranking
	//		num 	: number of users to show, defaults to 5
	//		date	: show ranking up until this date, 
	//				  possible values 'now', 'postdate', a datetime value formatted like this 'Y-m-d H:i',
	//				  defaults to 'now'
	public static function shortcode_ranking( $atts ) {
		global $pool;
		$default_num = 5;
		$league = $num = $ranking = $date = '';
		extract( shortcode_atts( array(
					'league' => FOOTBALLPOOL_LEAGUE_ALL,
					'num' => $default_num,
					'ranking' => FOOTBALLPOOL_RANKING_DEFAULT,
					'date' => 'now',
				), $atts ) );
		
		$user_id = get_current_user_id();

		if ( ! is_numeric( $num ) || $num <= 0 ) {
			$num = $default_num;
		} else {
			$num = (int) $num;
		}
		
		$league = self::league_helper( $league, $user_id );
		
		if ( ! is_numeric( $ranking ) || $ranking <= 0 ) {
			$ranking_id = FOOTBALLPOOL_RANKING_DEFAULT;
		} else {
			$ranking_id = (int) $ranking;
		}
		
		$rows = $pool->get_pool_ranking_limited( $league, $num, $ranking_id, self::date_helper( $date ) );
		$filtered_rows = apply_filters( 'footballpool_ranking_array', $rows );
		$num = ( count( $rows ) != count( $filtered_rows ) ) ? count( $filtered_rows ) : $num;
		
		$output = '';
		if ( count( $filtered_rows ) > 0 ) {
			$users = array();
			foreach ( $filtered_rows as $row ) $users[] = $row['user_id'];
			
			$output .= $pool->print_pool_ranking( $league, $user_id, $users, $filtered_rows, $ranking_id, 'shortcode', $num );
		} else {
			$output .= '<p>' . __( 'No data available.', 'football-pool' ) . '</p>';
		}
		
		return apply_filters( 'footballpool_shortcode_html_fp-ranking', $output, $rows, $filtered_rows, $atts );
	}
	
	//[fp-countdown]
	public static function shortcode_countdown( $atts ) {
		global $pool;
		$date = $match = $texts = $display = $format = $format_string = '';
		extract( shortcode_atts( array(
					'date' => '',
					'match' => '',
					'texts' => '',
					'display' => 'block',
					'format' => 2,
					'format_string' => '',
				), $atts ) );
		
		$matches = $pool->matches;
		
		$id = Football_Pool_Utils::get_counter_value( 'fp_countdown_id' );
		
		if ( $format_string === '' ) {
			switch ( $format ) {
				case 1:
					$format_string = '{s} {sec}';
					break;
				case 2:
					$format_string = '{d} {days}, {h} {hrs}, {m} {min}, {s} {sec}';
					break;
				case 3:
					$format_string = '{h} {hrs}, {m} {min}, {s} {sec}';
					break;
				case 4:
					$format_string = '{d} {days}, {h} {hrs}, {m} {min}';
					break;
				case 5:
					$format_string = '{h} {hrs}, {m} {min}';
					break;
			}
		}
		$format_string = Football_Pool_Utils::js_string_escape( $format_string );
		
		$countdown_date = 0;
		if ( (int) $match > 0 ) {
			$match_info = $matches->get_match_info( (int) $match );
			if ( array_key_exists( 'play_date', $match_info ) )
				$countdown_date = new DateTime( Football_Pool_Utils::date_from_gmt( $match_info['play_date'] ) );
		} elseif ( $match === 'next' ) {
			$match_info = $matches->get_next_match();
			if ( $match_info !== false ) {
				$countdown_date = new DateTime( Football_Pool_Utils::date_from_gmt( $match_info[0]['play_date'] ) );
			// } else {
				// // no next match found
				// if ( $display == 'inline' ) {
					// $output = "<span id='countdown-{$id}'>%s</span>";
				// } else {
					// $output = "<div style='text-align:center; width: 80%;'><h2 id='countdown-{$id}'>%s</h2></div>";
				// }
				// return sprintf( $output, esc_html__( 'No new matches found.', 'football-pool' ) );
			}
		}
		
		if ( ! is_object( $countdown_date ) ) {
			$countdown_date = date_create( $date );
			if ( $date === '' || $countdown_date === false ) {
				// Countdown shortcode defaults to the first match if no valid date can be created with the
				// other parameters.
				$first_match = $matches->get_first_match_info();
				$countdown_date = new DateTime(
					Football_Pool_Utils::date_from_gmt( $first_match['play_date'] )
				);
			}
		}
		
		if ( $texts === 'none' ) $texts = ';;;'; // 4 empty strings overwriting the default texts
		
		$texts = explode( ';', $texts );
		
		if ( is_array( $texts ) && count( $texts ) === 4 ) {
			$texts[0] = esc_js( $texts[0] );
			$texts[1] = esc_js( $texts[1] );
			$texts[2] = esc_js( $texts[2] );
			$texts[3] = esc_js( $texts[3] );
			$extra_text = "{'pre_before':'{$texts[0]}', 'post_before':'{$texts[1]}', 'pre_after':'{$texts[2]}', 'post_after':'{$texts[3]}'}";
		} else {
			$extra_text = 'null';
		}
		
		$year  = $countdown_date->format( 'Y' );
		$month = $countdown_date->format( 'm' );
		$day   = $countdown_date->format( 'd' );
		$hour  = $countdown_date->format( 'H' );
		$min   = $countdown_date->format( 'i' );
		$sec   = 0;
		
		$output = '';
		if ( $display === 'inline' ) {
			$output .= "<span class='shortcode countdown-shortcode' id='countdown-{$id}'>&nbsp;</span>";
		} else {
			$output .= "<div class='shortcode countdown-shortcode block'><h2 id='countdown-{$id}'>&nbsp;</h2></div>";
		}

		/** @noinspection CommaExpressionJS */
		$output .= "<script type='text/javascript'>
					FootballPool.countdown( '#countdown-{$id}', {$extra_text}, {$year}, {$month}, {$day}, {$hour}, {$min}, {$sec}, {$format}, '{$format_string}' );
					window.setInterval( function() { FootballPool.countdown( '#countdown-{$id}', {$extra_text}, {$year}, {$month}, {$day}, {$hour}, {$min}, {$sec}, {$format}, '{$format_string}' ); }, 1000 );
					</script>";
		
		return apply_filters( 'footballpool_shortcode_html_fp-countdown', $output, $atts );
	}
	
	//[fp-link slug=""]
	public static function shortcode_link( $atts ) {
		$output = '';
		if ( isset( $atts['slug'] ) ) {
			$id = Football_Pool_Utils::get_fp_option( 'page_id_' . $atts['slug'] );
			if ( $id ) {
				$output = get_page_link( $id );
			}
		}
		return apply_filters( 'footballpool_shortcode_html_fp-link', $output, $atts );
	}
	
	//[fp-register]
	//		title	: title parameter for the <a href>
	public static function shortcode_register_link( $atts, $content = '' ) {
		$title = $new = '';
		extract( shortcode_atts( array(
					'title' => '',
					'new' => '0',
				), $atts ) );
		
		$title = ( $title !== '' ) ? sprintf( ' title="%s"', Football_Pool_Utils::xssafe( $title ) ) : '';
		$site_url = get_site_url();
		$redirect = get_permalink();
		$redirect = ( $redirect !== false ) ? sprintf( '&amp;redirect_to=%s', $redirect ) : '';
		$content = ( $content > '' ) ? $content : __( 'register', 'football-pool' );
		$target = ( $new == '1' ) ? ' target="_blank"' : '';
		
		$output = sprintf( '<a href="%s/wp-login.php?action=register%s"%s%s>%s</a>'
						, $site_url
						, $redirect
						, $title
						, $target
						, $content
					);
		return apply_filters( 'footballpool_shortcode_html_fp-register', $output, $atts );
	}

	//[fp-plugin-option]
	//    Displays the value of a plugin setting
	public static function shortcode_plugin_option( $atts ) {
		$option = $default = $type = '';
		extract( shortcode_atts( array(
			'option' => '',
			'default' => '',
			'type' => 'text',
		), $atts ) );
		return Football_Pool_Utils::get_fp_option( $option, $default, $type );
	}

	//[fp-fullpoints]
	public static function shortcode_fullpoints( $atts ) {
		$output = Football_Pool_Utils::get_fp_option( 'fullpoints', FOOTBALLPOOL_FULLPOINTS, 'int' );
		return apply_filters( 'footballpool_shortcode_html_fp-fullpoints', $output, $atts );
	}

	//[fp-totopoints]
	public static function shortcode_totopoints( $atts ) {
		$output = Football_Pool_Utils::get_fp_option( 'totopoints', FOOTBALLPOOL_TOTOPOINTS, 'int' );
		return apply_filters( 'footballpool_shortcode_html_fp-totopoints', $output, $atts );
	}

	//[fp-goalpoints]
	public static function shortcode_goalpoints( $atts ) {
		$output = Football_Pool_Utils::get_fp_option( 'goalpoints', FOOTBALLPOOL_GOALPOINTS, 'int' );
		return apply_filters( 'footballpool_shortcode_html_fp-goalpoints', $output, $atts );
	}

	//[fp-diffpoints]
	public static function shortcode_diffpoints( $atts ) {
		$output = Football_Pool_Utils::get_fp_option( 'diffpoints', FOOTBALLPOOL_DIFFPOINTS, 'int' );
		return apply_filters( 'footballpool_shortcode_html_fp-diffpoints', $output, $atts );
	}
	
	//[fp-joker-multiplier]
	public static function shortcode_jokermultiplier( $atts ) {
		$output = Football_Pool_Utils::get_fp_option( 'joker_multiplier', FOOTBALLPOOL_JOKERMULTIPLIER, 'int' );
		return apply_filters( 'footballpool_shortcode_html_fp-jokermultiplier', $output, $atts );
	}
}
