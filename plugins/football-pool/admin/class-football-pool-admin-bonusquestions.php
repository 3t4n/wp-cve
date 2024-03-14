<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2012-2022 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/LICENSE
 */

/** @noinspection SqlResolve */

class Football_Pool_Admin_Bonus_Questions extends Football_Pool_Admin {
	public function __construct() {}
	
	public static function help() {
		$help_tabs = array(
					array(
						'id' => 'overview',
						'title' => __( 'Overview', 'football-pool' ),
						'content' => __( '<p>On this page you can add, change or delete bonus questions.</p><p>The <em>\'User Answers\'</em> link in the table view, or <em>\'Edit User Answers\'</em> button in the detail view, is used to check answers from your players.</p><p><strong>Important:</strong> points are only rewarded <em>after</em> the admin has checked the user answers!</p>', 'football-pool' )
					),
					array(
						'id' => 'calculation',
						'title' => __( 'Score calculation', 'football-pool' ),
						'content' => __( '<p>The score for a bonus question will be added to the players total score after an admin has \'approved\' the answer (<em>\'Edit user answers\'</em>) and when the Score Date is filled. The Score Date is the point in time where the points are added to the total (needed for the charts and/or a ranking for a given date).</p>
						<p>You can give a user more points (or less) for a question. Use the field <em>\'points\'</em> in the Edit User Answers screen for this; leave the field empty for standard points.</p>', 'football-pool' )
					),
					array(
						'id' => 'linkedquestions',
						'title' => __( 'Linked questions', 'football-pool' ),
						'content' => __( '<p>If a question is linked to a match it will be shown beneath that match in the prediction screen. Linked questions cannot be shown separately on a prediction form for questions, but are always shown with the linked match.</p><p>When the linked match is deleted the question will be unlinked, but will still be available in the prediction form.</p>', 'football-pool' )
					),
				);
		/** @noinspection HtmlUnknownAnchorTarget */
		$help_sidebar = sprintf( '<a href="?page=footballpool-help#bonusquestions">%s</a></p><p><a href="?page=footballpool-help#rankings">%s</a>'
								, __( 'Help section about bonus questions', 'football-pool' )
								, __( 'Help section about ranking calculation', 'football-pool' )
						);
	
		self::add_help_tabs( $help_tabs, $help_sidebar );
	}
	
	public static function screen_options() {
		$screen = Football_Pool_Utils::request_str( 'action', 'view' );

		if ( $screen === 'user-answers' ) {
			$args = [
				'label' => __( 'User Answers', 'football-pool' ),
				'default' => FOOTBALLPOOL_ADMIN_USER_ANWERS_PER_PAGE,
				'option' => 'footballpool_user_anwers_per_page'
			];
		} else {
			$args = [
				'label' => __( 'Bonus questions', 'football-pool' ),
				'default' => FOOTBALLPOOL_ADMIN_DEFAULT_PER_PAGE,
				'option' => 'footballpool_bonus_questions_per_page'
			];
		}
		add_screen_option( 'per_page', $args );
	}

	/** @noinspection PhpMissingBreakStatementInspection */
	public static function admin() {
		$search = Football_Pool_Utils::request_str( 's' );
		$subtitle = self::get_search_subtitle( $search );
		self::admin_header( __( 'Bonus questions', 'football-pool' ), $subtitle, 'add new' );
		
		$question_id = Football_Pool_Utils::request_int( 'item_id', 0 );
		$bulk_ids = Football_Pool_Utils::post_int_array( 'itemcheck' );
		$action = Football_Pool_Utils::request_string( 'action', 'list' );

		if ( count( $bulk_ids ) > 0 && $action === '-1' )
			$action = Football_Pool_Utils::request_string( 'action2', 'list' );

		$search_submit = ( Football_Pool_Utils::post_str( 'search_submit', '' ) !== '' );
		if ( $search_submit ) {
			$action = Football_Pool_Utils::post_str( 'prev_action', 'list' );
		}

		switch ( $action ) {
			case 'save':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				// new or updated question
				$question_id = self::update( $question_id );
				self::notice( __( 'Question saved.', 'football-pool' ) );
				if ( Football_Pool_Utils::post_str( 'submit' ) == __( 'Save & Close', 'football-pool' ) ) {
					self::view();
					break;
				}
			case 'edit':
				self::edit( $question_id );
				break;
			case 'user-answers-save':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				$id = Football_Pool_Utils::post_integer( 'item_id' );
				self::set_bonus_question_for_users( $id );
				self::update_score_history();

				if ( ! $search_submit ) {
					self::notice( __( 'Answers updated.', 'football-pool' ) );
				}

				if ( Football_Pool_Utils::post_str( 'submit' ) == __( 'Save & Close', 'football-pool' ) ) {
					self::view();
					break;
				}
			case 'user-answers':
				self::edit_user_answers();
				break;
			case 'delete':
				check_admin_referer( FOOTBALLPOOL_NONCE_ADMIN );
				if ( $question_id > 0 ) {
					self::delete( $question_id );
					self::notice(sprintf( __( 'Question id:%s deleted.', 'football-pool' ), $question_id ) );
				}
				if ( count( $bulk_ids ) > 0 ) {
					self::delete( $bulk_ids );
					self::notice( sprintf( __( '%s questions deleted.', 'football-pool' ), count( $bulk_ids ) ) );
				}
			default:
				self::view();
		}
		
		self::admin_footer();
	}
	
	private static function edit_user_answers() {
		global $pool;
		$id = Football_Pool_Utils::request_integer( 'item_id' );
		
		if ( $id > 0 ) {
			$question = $pool->get_bonus_question( $id );
			/** @noinspection PhpUnhandledExceptionInspection */
			$question_date = new DateTime( $question['answer_before_date'] );
			$answers = $pool->get_bonus_question_answers_for_users( $id );

			// filter by username or answer
			$search = Football_Pool_Utils::request_str( 's' );
			if ( $search !== '' ) {
				$answers = array_filter( $answers, function( $v ) use ( $search ) {
								return stripos( $v['answer'], $search ) !== false
									|| stripos( $v['name'], $search ) !== false;
							} );
			}
			$pagination = new Football_Pool_Pagination( count( $answers ) );
			$pagination->wrap = true;
			$pagination->add_query_arg( 'action', 'user-answers' );
			$pagination->add_query_arg( 'item_id', $id );
			$pagination->set_page_size( self::get_screen_option( 'per_page' ) );
			
			$answers = array_slice( $answers,
									( $pagination->current_page - 1 ) * $pagination->get_page_size(),
									$pagination->get_page_size() 
								);

			echo '<h3>', $question['question'], '</h3>';
			echo '<p>', __( 'answer', 'football-pool' ), ':<br>', nl2br( $question['answer'] ), '<br>';
			
			$points = $question['points'] == 0 ? __( 'variable', 'football-pool' ) : $question['points'];
			echo '<span style="font-size: 80%; font-style: italic;">', $points, ' ', __( 'point(s)', 'football-pool' ), 
						', ', __( 'answer before', 'football-pool' ), ' ', $question_date->format( 'Y-m-d H:i' ), '</span></p>';
			
			if ( count( $answers ) > 0 ) {
				echo '<p class="submit">';
				submit_button( __( 'Save & Close', 'football-pool' ), 'primary', 'submit', false );
				submit_button( null, 'secondary', 'save', false );
				self::cancel_button();
				echo '</p>';
			}

			self::search_box(
				array( 'text' => __( 'Search', 'football-pool' ), 'value' => $search ),
				'user-answers' // always pass user-answers as return-to action
			);

			$pagination->show();

			echo '<table class="widefat bonus user-answers">';
			echo '<thead><tr>
					<th>', __( 'user', 'football-pool' ), '</th>
					<th>', __( 'answer', 'football-pool' ), '</th>
					<th>', __( 'correct', 'football-pool' ), '</th>
					<th>', __( 'false', 'football-pool' ), '</th>
					<th title="', __( "Leave empty if you don't want to change the standard points.", 'football-pool' ), '">', __( 'points', 'football-pool' ), ' <span class="sup">*)</span></th>
				</tr></thead>';
			echo '<tbody>';
			if ( count( $answers ) > 0 ) {
				foreach ( $answers as $answer ) {
					if ( $answer['correct'] == 1 ) {
						$correct = 'checked="checked" ';
						$wrong = '';
						$input = '';
					} else {
						$correct = '';
						$wrong = 'checked="checked" ';
						$input = 'style="display:none;" ';
					}
					$points = $answer['points'] == 0 ? '' : $answer['points'];
					
					echo '<tr><td>', $answer['name'], '</td><td>', nl2br( $answer['answer'] ), '</td>';
					echo '<td><input onchange="FootballPoolAdmin.toggle_points( this.name )" name="_user_', $answer['user_id'], '" value="1" type="radio" ', $correct, '></td>';
					echo '<td><input onchange="FootballPoolAdmin.toggle_points( this.name )" name="_user_', $answer['user_id'], '" value="0" type="radio" ', $wrong, '></td>';
					echo '<td><input name="_user_', $answer['user_id'], '_points" id="_user_', $answer['user_id'], '_points" title="', __( "Leave empty if you don't want to change the standard points.", 'football-pool' ), '" value="', $points, '" type="text" size="3" ', $input, '></td>';
					echo '</tr>';
				}
			} else {
				echo '<tr><td colspan="4">', __( 'No answers found.', 'football-pool' ), '</td></tr>';
			}
			
			echo '</tbody>';
			echo '</table>';

			echo '<p class="submit">';
			submit_button( __( 'Save & Close', 'football-pool' ), 'primary', 'submit', false );
			submit_button( null, 'secondary', 'save', false );
			self::cancel_button();
			echo '</p>';
			self::hidden_input( 'item_id', $id );
			self::hidden_input( 'action', 'user-answers-save' );
		} else {
			self::notice( __( 'No questions, users or answers found.', 'football-pool' ), 'info' );
		}
	}
	
	private static function edit( $id ) {
		global $pool;

		$values = array(
			'question'				=> '',
			'points'				=> '',
			'answer_before_date'	=> self::example_date( 'gmt' ),
			'score_date'			=> '',
			'answer'				=> '',
			'type'					=> 1,
			'options'				=> '',
			'max_answers'			=> '',
			'image'					=> '',
			'match_id'				=> 0,
			'question_order'		=> 1,
		);
		
		$question = $pool->get_bonus_question( $id );
		if ( $question ) {
			$values = $question;
		}
		
		// question types
		$types = array( 
						array( 'value' => '1', 'text' => __( 'text', 'football-pool' ) ), 
						array( 'value' => '4', 'text' => __( 'multiline text', 'football-pool' ) ), 
						array( 'value' => '2', 'text' => __( 'multiple choice, 1 answer (radio list)', 'football-pool' ) ), 
						array( 'value' => '5', 'text' => __( 'multiple choice, 1 answer (dropdown)', 'football-pool' ) ), 
						array( 'value' => '3', 'text' => __( 'multiple choice, one or more answers (checkbox list)', 'football-pool' ) ), 
					);
		// matches
		$matches = array( array( 'value' => 0, 'text' => __( 'not linked', 'football-pool' ) ) );
		foreach( $pool->matches->matches as $match ) {
			$matches[] = array(
							'value' => $match['id'],
							'text' => sprintf( '%d: %s - %s', $match['id'], $match['home_team'], $match['away_team'] )
						);
		}

		$cols = array(
			array( 'text', __( 'question', 'football-pool' ), 'question', $values['question'], '' ),
			array( 'integer', __( 'points', 'football-pool' ), 'points', $values['points'], __( 'The points a user gets as an award for answering the question correctly.', 'football-pool' ) ),
			array( 'integer', __( 'question order', 'football-pool' ), 'question_order', $values['question_order'], __( 'An optional numeric value (1-..) that can be used to sort the questions on the prediction form.', 'football-pool' ) ),
			array( 'datetime', __( 'answer before', 'football-pool' ).'<br><span style="font-size:80%">(' . _x( 'e.g.', 'abbreviation for "for example"', 'football-pool' ) . ' ' . self::example_date() . ')</span>', 'lastdate', $values['answer_before_date'], __( 'A user may give an answer untill this date and time.', 'football-pool' ) . sprintf( ' (%s)', __( 'local time', 'football-pool' ) ) ),
			array( 'datetime', __( 'score date', 'football-pool' ).'<br><span style="font-size:80%">(' . _x( 'e.g.', 'abbreviation for "for example"', 'football-pool' ) . ' ' . self::example_date() . ')</span>', 'scoredate', $values['score_date'], __( "The points awarded will be added to the total points for a user after this date. If not supplied, the points won't be added.", 'football-pool' ) . sprintf( ' (%s)', __( 'local time', 'football-pool' ) ) ),
			array(
				'select',
				__( 'link to match', 'football-pool' ),
				'match_id',
				$values['match_id'],
				$matches,
				__( 'Linked questions are placed directly beneath the match on the prediction form.', 'football-pool' )
			),
			array( 'textarea', __( 'answer', 'football-pool' ), 'answer', $values['answer'], __( 'The correct answer (used as a reference).', 'football-pool' ) ),
			array( 'checkbox', __( 'auto set user answers', 'football-pool' ), 'auto_set', 0, __( 'If checked on save the user answers will be checked against the given answer (a text compare is used). Useful for questions of type multiple choice.', 'football-pool' ) ),
			array(
				'radiolist',
				__( 'type', 'football-pool' ),
				'type',
				$values['type'],
				$types,
				'',
				array(
					'onclick="FootballPoolAdmin.toggle_linked_options( null, [ \'#r-options\', \'#r-max_answers\' ] )"',
					'onclick="FootballPoolAdmin.toggle_linked_options( null, [ \'#r-options\', \'#r-max_answers\' ] )"',
					'onclick="FootballPoolAdmin.toggle_linked_options( \'#r-options\', \'#r-max_answers\' )"',
					'onclick="FootballPoolAdmin.toggle_linked_options( \'#r-options\', \'#r-max_answers\' )"',
					'onclick="FootballPoolAdmin.toggle_linked_options( [ \'#r-options\', \'#r-max_answers\' ], null )"',
				),
			),
			array(
				'text',
				__( 'multiple choice options', 'football-pool' ),
				'options',
				$values['options'],
				__( 'A semicolon separated list of answer possibilities. Only applicable for multiple choice questions.', 'football-pool' ),
				null,
				( (int) $values['type'] === 1 )
			),
			array(
				'integer',
				__( 'max answers for multiple choice', 'football-pool' ),
				'max_answers',
				( $values['max_answers'] == 0 ? '' : $values['max_answers'] ),
				__( 'Optional: The maximum number of options a user may select (empty = unlimited). Only applicable for multiple choice questions with one or more answers.', 'football-pool' ),
				null,
				( (int) $values['type'] !== 3 )
			),
			array( 'image', __( 'photo question', 'football-pool' ), 'image', $values['image'], __( 'Add a URL to a photo for a photo question, or choose one from the media library (optional).', 'football-pool' ) ),
			array( 'hidden', '', 'item_id', $id ),
			array( 'hidden', '', 'action', 'save' )
		);
		self::value_form( $cols );
		echo '<p class="submit">';
		submit_button( __( 'Save & Close', 'football-pool' ), 'primary', 'submit', false );
		submit_button( null, 'secondary', 'save', false );
		self::cancel_button();
		if ( $id > 0 ) self::secondary_button( __( 'Edit User Answers', 'football-pool' ), 'user-answers', false );
		echo '</p>';
	}
	
	private static function view() {
		global $pool;
		$questions = $pool->get_bonus_questions();

		$search = Football_Pool_Utils::request_string( 's' );
		if ( $search !== '' ) {
			$questions = array_filter( $questions, function( $v ) use ( $search ) {
				return stripos( $v['question'], $search ) !== false || stripos( $v['answer'], $search ) !== false;
			} );
		}

//		$pagination = false;
		$pagination = new Football_Pool_Pagination( count( $questions ) );
		$pagination->set_page_size( self::get_screen_option( 'per_page' ) );
		$pagination->add_query_arg( 's', $search );
		$pagination->wrap = false;

		$example_date = '<br><span style="font-size:80%">(' .
			_x( 'e.g.', 'abbreviation for "for example"', 'football-pool' ) . ' ' . self::example_date() . ')</span>';

		$cols = [
			['text', __( 'question', 'football-pool' ), 'question', ''],
			['integer', __( 'points', 'football-pool' ), 'points', ''],
			['date', __( 'answer before', 'football-pool' ) . $example_date, 'lastdate', ''],
			['date', __( 'score date', 'football-pool' ) . $example_date, 'scoredate', ''],
//			['text', __( 'answer', 'football-pool' ), 'answer', ''],
			['integer', __( 'question order', 'football-pool' ), 'question_order', ''],
			['link', __( 'linked to match', 'football-pool' ), 'match', '']
		];
		
		$rows = [];
		if ( is_array( $questions) ) {
			foreach( $questions as $question ) {
				if ( $question['match_id'] > 0 ) {
					$match = sprintf( '<a href="?page=footballpool-games&item_id=%d&action=edit">%d &gt;&gt;</a>'
										, $question['match_id']
										, $question['match_id']
								);
				} else {
					$match = '';
				}
				$question_text = strip_tags( $question['question'] );
				$question_text = strlen( $question_text ) > FOOTBALLPOOL_ADMIN_QUESTION_MAX_CHARS ?
					substr( $question_text, 0, ( FOOTBALLPOOL_ADMIN_QUESTION_MAX_CHARS - 3 ) ) . '...': $question_text;
				$score_date = '';
				if ( ! is_null( $question['score_date'] ) ) {
					$score_date = Football_Pool_Utils::date_from_gmt( $question['score_date'] );
				}
				$rows[] = [
					$question_text,
					$question['points'],
					Football_Pool_Utils::date_from_gmt( $question['answer_before_date'] ),
					$score_date,
//					$question['answer'],
					$question['question_order'],
					$match,
					$question['id']
				];
			}
		}

		$rows = array_slice(
			$rows,
			( $pagination->current_page - 1 ) * $pagination->get_page_size(),
			$pagination->get_page_size()
		);

		$search_box = array(
			'text' => __( 'Search', 'football-pool' ),
			'value' => $search,
		);
		$bulkactions[] = [
			'delete',
			__( 'Delete' ),
			__( 'You are about to delete one or more bonus questions.', 'football-pool' ) . ' ' .
				__( 'Are you sure? `OK` to delete, `Cancel` to stop.', 'football-pool' )
		];
		$rowactions[] = ['user-answers', __( 'User Answers', 'football-pool' )];
		self::list_table( $cols, $rows, $bulkactions, $rowactions, $pagination, $search_box );
	}
	
	private static function delete( $question_id ) {
		if ( is_array( $question_id ) ) {
			foreach ( $question_id as $id ) self::delete_bonus_question( $id );
		} else {
			self::delete_bonus_question( $question_id );
		}
		wp_cache_delete( FOOTBALLPOOL_CACHE_QUESTIONS, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		self::update_score_history();
	}
	
	private static function delete_bonus_question( $id ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		do_action( 'footballpool_admin_question_delete', $id );
		
		$sql = $wpdb->prepare( "DELETE FROM {$prefix}bonusquestions WHERE id = %d", $id );
		$success = ( $wpdb->query( $sql ) !== false );
		if ( $success ) {
			$sql = $wpdb->prepare( "DELETE FROM {$prefix}rankings_bonusquestions WHERE question_id = %d", $id );
			$wpdb->query( $sql );
			$sql = $wpdb->prepare( "DELETE FROM {$prefix}bonusquestions_type WHERE question_id = %d", $id );
			$wpdb->query( $sql );
			$sql = $wpdb->prepare( "DELETE FROM {$prefix}bonusquestions_useranswers WHERE question_id = %d", $id );
			$wpdb->query( $sql );
		}
	}
	
	private static function update( $question_id ) {
		$question = array(
			$question_id,
			Football_Pool_Utils::post_string( 'question' ),
			Football_Pool_Utils::post_string( 'answer' ),
			Football_Pool_Utils::post_int( 'points' ),
			Football_Pool_Utils::gmt_from_date( self::make_date_from_input( 'lastdate' ) ),
			Football_Pool_Utils::gmt_from_date( self::make_date_from_input( 'scoredate' ) ),
			Football_Pool_Utils::post_int( 'type', 1 ),
			Football_Pool_Utils::post_string( 'options' ),
			Football_Pool_Utils::post_string( 'image' ),
			Football_Pool_Utils::post_int( 'max_answers', 0 ),
			Football_Pool_Utils::post_int( 'match_id', 0 ),
			Football_Pool_Utils::post_int( 'auto_set', 0 ),
			Football_Pool_Utils::post_int( 'question_order', 1 ),
		);
		
		$id = self::update_bonus_question( $question );
		self::update_score_history();
		return $id;
	}
	
	private static function update_bonus_question( $input ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$id = $input[0];
		$question = $input[1];
		$answer = $input[2];
		$points = $input[3];
		$date = $input[4];
		$scoredate = $input[5];
		$type = $input[6];
		$options = $input[7];
		$image = $input[8];
		$max_answers = $input[9];
		$match_id = $input[10];
		$auto_set = $input[11];
		$question_order = $input[12];

		if ( $question_order < 0 || $question_order > 65535 ) $question_order = 1; // unsigned smallint

		// check if the question date is valid
		if ( ! Football_Pool_Utils::is_valid_mysql_date( $date ) ) $date = current_time( 'mysql', 1 );

		if ( $id == 0 ) {
			$sql = $wpdb->prepare( "INSERT INTO {$prefix}bonusquestions 
										( question, points, answer_before_date, answer, match_id, question_order )
									VALUES ( %s, %d, %s, %s, %d, %d )",
							$question, $points, $date, $answer, $match_id, $question_order
						);
			$wpdb->query( $sql );
			$id = $wpdb->insert_id;
			
			if ( $id ) {
				// set the score date if the date is valid
				if ( $scoredate !== '' && Football_Pool_Utils::is_valid_mysql_date( $scoredate ) ) {
					$sql = $wpdb->prepare( "UPDATE {$prefix}bonusquestions SET score_date = %s 
											WHERE id = %d", $scoredate, $id );
					$wpdb->query( $sql );
				}
				// set the type
				$sql = $wpdb->prepare( "INSERT INTO {$prefix}bonusquestions_type 
											( question_id, type, options, image, max_answers )
										VALUES ( %d, %d, %s, %s, %d )"
										, $id, $type, $options, $image, $max_answers
								);
				$wpdb->query( $sql );
			}
		} else {
			$sql = $wpdb->prepare( "UPDATE {$prefix}bonusquestions SET
										question = %s,
										points = %d,
										answer_before_date = %s,
										answer = %s,
										score_date = NULL,
										match_id = %d,
										question_order = %d
									WHERE id = %d",
							$question, $points, $date, $answer, $match_id, $question_order, $id
						);
			$wpdb->query( $sql );
			// set the score date if the date is valid
			if ( $scoredate !== '' && Football_Pool_Utils::is_valid_mysql_date( $scoredate ) ) {
				$sql = $wpdb->prepare( "UPDATE {$prefix}bonusquestions SET score_date = %s WHERE id = %d",
										$scoredate, $id );
				$wpdb->query( $sql );
			}
			
			$sql = $wpdb->prepare( "UPDATE {$prefix}bonusquestions_type 
									SET type = %d, options = %s, image = %s, max_answers = %d 
									WHERE question_id = %d"
									, $type, $options, $image, $max_answers, $id );
			$wpdb->query( $sql );
			// auto set user answers?
			if ( $auto_set ) self::auto_set( $id, $answer );
		}
		
		wp_cache_delete( FOOTBALLPOOL_CACHE_QUESTIONS, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		
		do_action( 'footballpool_admin_question_save', $input, $id );
		return $id;
	}
	
	private static function set_score_date( $question_id ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		$sql = $wpdb->prepare( "UPDATE {$prefix}bonusquestions SET score_date = %s WHERE score_date IS NULL AND id = %d"
								, current_time( 'mysql', true )
								, $question_id );
		$wpdb->query( $sql );
	}
	
	private static function auto_set( $question_id, $answer ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$answer = trim( strtolower( $answer ) );
		if ( $answer !== '' ) {
			$sql = $wpdb->prepare( "UPDATE {$prefix}bonusquestions_useranswers SET correct = 1 
									WHERE question_id = %d AND LOWER( answer ) = %s"
									, $question_id
									, $answer );
			$wpdb->query( $sql );
			$sql = $wpdb->prepare( "UPDATE {$prefix}bonusquestions_useranswers SET correct = 0 
									WHERE question_id = %d AND LOWER( answer ) <> %s"
									, $question_id
									, $answer );
			$wpdb->query( $sql );

			// if the score date for this question is not set, then set it to the current time and date.
			self::set_score_date( $question_id );
		}
	}
	
	private static function set_bonus_question_for_users( $question_id ) {
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		$users = get_users();
		foreach ( $users as $user ) {
			$correct = Football_Pool_Utils::post_integer( '_user_' . $user->ID, -1 );
			$points = Football_Pool_Utils::post_integer( '_user_' . $user->ID . '_points', 0 );
			if ( $correct !== -1 ) {
				$sql = $wpdb->prepare( "UPDATE {$prefix}bonusquestions_useranswers 
											SET correct = %d, points = %d 
											WHERE user_id = %d AND question_id = %d"
										, $correct, $points, $user->ID, $question_id
								);
				$wpdb->query( $sql );
			}
		}
		
		// if the score date for this question is not set, then set it to the current time and date.
		self::set_score_date( $question_id );
	}

}
