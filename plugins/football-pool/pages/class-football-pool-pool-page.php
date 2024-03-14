<?php

/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

class Football_Pool_Pool_Page {
	public function page_content() {
		global $pool;

		$user_id = get_current_user_id();
		$user_is_player = $pool->user_is_player( $user_id );

		// todo: add option to show a disabled form for non-registered users.
		if ( $user_id === 0 || ! $user_is_player ) {
			$output = '<p>';
			/** @noinspection HtmlUnknownTarget */
			$output .= sprintf( __( 'You have to be a <a href="%s">registered</a> user and <a href="%s">logged in</a> to play in this pool.', 'football-pool' )
				, wp_registration_url()
				, wp_login_url( get_permalink() )
			);
			$output .= '</p>';
		} else {
			// save any updates
			$output = $pool->prediction_form_update();

			$questions = $pool->get_bonus_questions_for_user( $user_id );
			// determine if there are any questions not linked to a match
			$show_question_form = false;
			if ( $pool->has_bonus_questions ) {
				foreach ( $questions as $question ) {
					if ( $question['match_id'] == 0 ) {
						$show_question_form = true;
						break;
					}
				}
			}

			$result = $pool->matches->get_match_info_for_user( $user_id );
			$result = apply_filters( 'footballpool_page_pool_matches_filter_2', $result, $user_id );
			$filtered_result = apply_filters( 'footballpool_page_pool_matches_filter', $result, $user_id );

			$id = Football_Pool_Utils::get_counter_value( 'fp_predictionform_counter' );

			$empty_prediction = $pool->matches->first_empty_match_for_user( $user_id );
			if ( $show_question_form && $pool->has_matches ) {
				/** @noinspection HtmlUnknownAnchorTarget */
				$output .= sprintf( '<p><a href="#bonus">%s</a> | <a href="#match-%d-%d">%s</a></p>'
					, __( 'Bonus questions', 'football-pool' )
					, $empty_prediction
					, $id
					, __( 'Predictions', 'football-pool' )
				);
			}

			$output .= $pool->prediction_form_start( $id );

			if ( $pool->has_matches ) {
				$output .= sprintf( '<h2>%s</h2>', __( 'matches', 'football-pool' ) );
				// the matches
				$output .= $pool->prediction_form_matches( $filtered_result, false, $id, 'matches pool-page' );
			}

			// the questions
			if ( $show_question_form ) {
				$output .= sprintf( '<h2 id="bonus">%s</h2>', strtolower( __( 'Bonus questions', 'football-pool' ) ) );
				$output .= $pool->prediction_form_questions( $questions, false, $id, 1, 'questions pool-page' );
			}

			$output .= $pool->prediction_form_end();
			$output = apply_filters( 'footballpool_pool_page_html', $output, $result );
		}
		
		return $output;
	}
	
}
