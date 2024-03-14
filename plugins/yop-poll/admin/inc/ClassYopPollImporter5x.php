<?php
class ClassYopPollImporter5x {
	private static $initial_limit           = 500;
	private static $ajax_limit              = 100;
	private static $unprocessed_polls       = 0;
	private static $processed_polls         = 0;
	private static $unprocessed_bans        = 0;
	private static $processed_bans          = 0;
	private static $unprocessed_votes       = 0;
	private static $processed_votes         = 0;
	private static $unprocessed_logs        = 0;
	private static $processed_logs          = 0;
	private static $checked_existence_polls = false;
	private static $checked_existence_bans  = false;
	private static $checked_existence_votes = false;
	private static $checked_existence_logs  = false;
	private static $polls_table_exists      = false;
	private static $bans_table_exists       = false;
	private static $votes_table_exists      = false;
	private static $logs_table_exists       = false;
	private static $maxElementID            = 0;
	private static $enableGdpr              = 'no';
	private static $gdprSolution            = 'consent';
	public function __construct( $initial_limit, $ajax_limit ) {
		if ( $initial_limit ) {
			self::$initial_limit = $initial_limit;
		}
		if ( $ajax_limit ) {
			self::$ajax_limit    = $ajax_limit;
		}
		add_action( 'wp_ajax_yop_ajax_import', array( &$this, 'yop_ajax_import' ) );
	}
	public function initialise() {
		self::import_polls( self::$initial_limit );
	}
	private static function import_polls( $query_limit, $skip_table_check = false ) {
		global $wpdb;
		$polls_table_name                = $GLOBALS['wpdb']->prefix . 'yop2_polls';
		$polls_meta_table_name           = $GLOBALS['wpdb']->prefix . 'yop2_pollmeta';
		$polls_questions_table_name      = $GLOBALS['wpdb']->prefix . 'yop2_poll_questions';
		$polls_questions_meta_table_name = $GLOBALS['wpdb']->prefix . 'yop2_poll_questionmeta';
		$polls_answers_table_name        = $GLOBALS['wpdb']->prefix . 'yop2_poll_answers';
		$polls_answers_meta_table_name   = $GLOBALS['wpdb']->prefix . 'yop2_poll_answermeta';
		$polls_questions_customs_table   = $GLOBALS['wpdb']->prefix . 'yop2_poll_custom_fields';
		if ( $skip_table_check ) {
			self::$checked_existence_polls = true;
			self::$polls_table_exists      = true;
		}
		if ( ! self::$checked_existence_polls ) {
			if ( self::check_if_table_exists( $polls_table_name ) && self::check_if_table_exists( $polls_meta_table_name ) ) {
				if ( ! self::check_if_column_exists( $polls_table_name, 'processed' ) ) {
					$wpdb->query( "ALTER TABLE `{$polls_table_name}` ADD processed BOOLEAN DEFAULT FALSE " );
				}
				self::$checked_existence_polls = true;
				self::$polls_table_exists      = true;
			}
		}
		if ( self::$checked_existence_polls && self::$polls_table_exists ) {
			self::$unprocessed_polls = $wpdb->get_var( "SELECT count(ID) FROM `{$polls_table_name}` WHERE `poll_title` != '' AND processed = false" );
			$polls                   = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT `ID`, `poll_title`, `poll_name`, `poll_author`, `poll_date`, `poll_status`, `poll_modified`, `poll_type`, `poll_start_date`, `poll_end_date`, `poll_total_votes`, `meta_key`, `meta_value` FROM `{$polls_table_name}` INNER JOIN `{$polls_meta_table_name}` ON `{$polls_table_name}`.`ID` = `{$polls_meta_table_name}`.`yop_poll_id` WHERE `poll_title` != '' AND `processed` = false LIMIT %d",
					$query_limit
				)
			);
			if ( count( $polls ) > 0 ) {
				if ( self::check_if_table_exists( $polls_questions_table_name ) &&
					self::check_if_table_exists( $polls_questions_meta_table_name ) ) {
					$maxID              = $wpdb->get_var( "SELECT MAX(`ID`) FROM `{$polls_questions_table_name}`" );
					self::$maxElementID = $maxID + 1;
				}
				foreach ( $polls as $poll ) {
					$unserialized_meta = unserialize( $poll->meta_value );
					$poll_style        = self::create_css_from_template( '' !== $unserialized_meta['template'] ? $unserialized_meta['template'] : 1 );
					$skin_base         = self::create_skin_base_from_template( '' !== $unserialized_meta['template'] ? $unserialized_meta['template'] : 1 );
					$vote_perms        = array();
					if ( isset( $unserialized_meta['vote_permisions'] ) ) {
						foreach ( $unserialized_meta['vote_permisions'] as $vp ) {
							if ( 'registered' === $vp ) {
								$vote_perms[] = 'wordpress';
							} else {
								$vote_perms[] = $vp;
							}
						}
					}
					if ( 0 == count( $vote_perms ) ) {
						$vote_perms[] = 'wordpress';
					}
					$results_moment = array();
					if ( isset( $unserialized_meta['view_results'] ) && is_array( $unserialized_meta['view_results'] ) ) {
						foreach ( $unserialized_meta['view_results'] as $vr ) {
							if ( 'after' === $vr ) {
								$results_moment[] = 'after-vote';
							} elseif ( 'before' === $vr ) {
								$results_moment[] = 'before-vote';
							} elseif ( '' == $vr ) {
								$results_moment[] = 'after-vote';
							} else {
								$results_moment[] = $vr;
							}
						}
					}
					if ( 0 == count( $results_moment ) ) {
						$results_moment[] = 'after-vote';
					}
					$show_results_to = array();
					if ( isset( $unserialized_meta['view_results_permissions'] ) && is_array( $unserialized_meta['view_results_permissions'] ) ) {
						foreach ( $unserialized_meta['view_results_permissions'] as $vrp ) {
							if ( 'registered' === $vrp ) {
								$show_results_to[] = 'registered';
							} else {
								$show_results_to[] = $vrp;
							}
						}
					}
					if ( 0 == count( $show_results_to ) ) {
						$show_results_to[] = 'guest';
						$show_results_to[] = 'registered';
					}
					$sorting_results = 'as-defined';
					if ( isset( $unserialized_meta['sorting_results'] ) && '' != $unserialized_meta['sorting_results'] ) {
						if ( 'votes' === $unserialized_meta['sorting_results'] ) {
							$sorting_results = 'number-of-votes';
						} elseif ( 'as_defined' === $unserialized_meta['sorting_results'] || 'exact' === $unserialized_meta['sorting_results'] ) {
							$sorting_results = 'as-defined';
						} else {
							$sorting_results = $unserialized_meta['sorting_results'];
						}
					}
					$newBlVoters = array();
					if ( isset( $unserialized_meta['blocking_voters'] ) && is_array( $unserialized_meta['blocking_voters'] ) ) {
						foreach ( $unserialized_meta['blocking_voters'] as $bv ) {
							$blocking_voters = 'no-block';
							switch ( $bv ) {
								case 'dont-block':
									$blocking_voters = 'no-block';
									break;
								case 'cookie':
									$blocking_voters = 'by-cookie';
									break;
								case 'ip':
									$blocking_voters = 'by-ip';
									break;
								case 'user_id':
									$blocking_voters = 'by-user-id';
									break;
								default:
									break;
							}
							$newBlVoters[] = $blocking_voters;
						}
					} else {
						$newBlVoters[] = 'no-block';
					}
					if ( isset( $unserialized_meta['view_results_type'] ) ) {
						switch ( $unserialized_meta['view_results_type'] ) {
							case 'votes-number': {
								$resultsDetails = ['votes-number'];
								break;
							}
							case 'percentages': {
								$resultsDetails = ['percentages'];
								break;
							}
							case 'votes-number-and-percentages' : {
								$resultsDetails = ['votes-number', 'percentages'];
								break;
							}
							default: {
								$resultsDetails = ['votes-number'];
								break;
							}
						}
					} else {
						$resultsDetails = ['percentages'];
					}
					$pro_templates_ids = [16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28];
					if ( isset( $unserialized_meta['template'] ) ) {
						if ( in_array( $unserialized_meta['template'], $pro_templates_ids ) ) {
							$template_base = 'basic-pretty';
						} else {
							$template_base = 'basic';
						}
					} else {
						$template_base = 'basic';
					}
					if ( isset( $unserialized_meta['show_results_in'] ) ) {
						if ( 'bar' === $unserialized_meta['show_results_in'] ) {
							$displayResultsAs = 'bar';
						} else {
							$displayResultsAs = 'pie';
						}
					} else {
						$displayResultsAs = 'bar';
					}
					if ( '' !== $poll->poll_end_date ) {
						if ( '2038-01-01 23:59:59' === $poll->poll_end_date ) {
							$poll_end_date_option = 'never';
							$poll_end_date = '';
						} else {
							$poll_end_date_option = 'custom';
							$poll_end_date = $poll->poll_end_date;
						}
					} else {
						$poll_end_date_option = 'never';
						$poll_end_date = '';
					}
					$adminUser = wp_get_current_user();
					$pollArray         = array(
						'ID'                     => $poll->ID,
						'name'                   => $poll->poll_title,
						'poll_author'            => $poll->poll_author,
						'status'                 => 'published',
						'stype'                  => 'poll',
						'total_submits'          => $poll->poll_total_votes,
						'total_submited_answers' => $poll->poll_total_votes,
						'added_date'             => $poll->poll_date,
						'modified_date'          => $poll->poll_modified,
						'design'                 => array(
							'template'     => 1,
							'templateBase' => $template_base,
							'skinBase'     => $skin_base,
							'style'        => $poll_style,
						),
						'options'                => array(
							'poll'    => array(
								'voteButtonLabel'               => isset( $unserialized_meta['vote_button_label'] ) && '' != $unserialized_meta['vote_button_label'] ? $unserialized_meta['vote_button_label'] : 'Vote',
								'showResultsLink'               => 'no',
								'resultsLabelText'              => 'Results',
								'showTotalVotes'                => isset( $unserialized_meta['view_total_votes'] ) && '' != $unserialized_meta['view_total_votes'] ? $unserialized_meta['view_total_votes'] : 'no',
								'showTotalAnswers'              => isset( $unserialized_meta['view_total_answers'] ) && '' != $unserialized_meta['view_total_answers'] ? $unserialized_meta['view_total_answers'] : 'no',
								'startDateOption'               => 'custom',
								'startDateCustom'               => $poll->poll_start_date,
								'endDateOption'                 => $poll_end_date_option,
								'endDateCustom'                 => $poll_end_date,
								'showEndDateOnFrontend'         => 'no',
								'showEndDateOnFrontendLocation' => 'top',
								'showEndDateOnFrontendText'     => '',
								'redirectAfterVote'             => isset( $unserialized_meta['redirect_after_vote'] ) && '' != $unserialized_meta['redirect_after_vote'] ? $unserialized_meta['redirect_after_vote'] : 'no',
								'redirectUrl'                   => $unserialized_meta['redirect_after_vote_url'],
								'resetPollStatsAutomatically'   => isset( $unserialized_meta['schedule_reset_poll_stats'] ) && '' != $unserialized_meta['schedule_reset_poll_stats'] ? $unserialized_meta['schedule_reset_poll_stats'] : 'no',
								'resetPollStatsOn'              => $unserialized_meta['schedule_reset_poll_date'],
								'resetPollStatsEvery'           => $unserialized_meta['schedule_reset_poll_recurring_value'],
								'resetPollStatsEveryPeriod'     => $unserialized_meta['schedule_reset_poll_recurring_unit'] . 's',
								'autoGeneratePollPage'          => isset( $unserialized_meta['auto_generate_poll_page'] ) && '' != $unserialized_meta['auto_generate_poll_page'] ? $unserialized_meta['auto_generate_poll_page'] : 'no',
								'pageId'                        => '',
								'pageLink'                      => '',
								'useCaptcha'                    => isset( $unserialized_meta['use_captcha'] ) && '' != $unserialized_meta['use_captcha'] ? $unserialized_meta['use_captcha'] : 'no',
								'sendEmailNotifications'        => isset( $unserialized_meta['send_email_notifications'] ) && '' != $unserialized_meta['send_email_notifications'] ? $unserialized_meta['send_email_notifications'] : 'no',
								'emailNotificationsFromName'    =>
									isset( $unserialized_meta['email_notifications_from_name'] ) && '' !== $unserialized_meta['email_notifications_from_name'] ? $unserialized_meta['email_notifications_from_name'] : 'Voting Alerts',
								'emailNotificationsFromEmail'   =>
									isset( $unserialized_meta['email_notifications_from_email'] ) && '' !== $unserialized_meta['email_notifications_from_email'] ? $unserialized_meta['email_notifications_from_email'] : $adminUser->user_email,
								'emailNotificationsRecipients'  => isset( $unserialized_meta['email_notifications_recipients'] ) && '' !== $unserialized_meta['email_notifications_recipients'] ? $unserialized_meta['email_notifications_recipients'] : $adminUser->user_email,
								'emailNotificationsSubject'     =>
									isset( $unserialized_meta['email_notifications_subject'] ) && '' !== $unserialized_meta['email_notifications_subject'] ? $unserialized_meta['email_notifications_subject'] : 'New Vote',
								'emailNotificationsMessage'     =>
									isset( $unserialized_meta['email_notifications_body'] ) ? $unserialized_meta['email_notifications_body'] : 'New Vote',
								'enableGdpr' => 'no',
								'gdprSolution' => 'consent',
								'gdprConsentText' => '',
								'loadWithAjax' => 'no',
								'notificationMessageLocation' => 'top',
							),
							'access'  => array(
								'votePermissions'     => $vote_perms,
								'blockVoters'         => $newBlVoters,
								'blockLengthType'     => 'limited-time',
								'blockForValue'       => isset( $unserialized_meta['blocking_voters_interval_value'] ) ? $unserialized_meta['blocking_voters_interval_value'] : '',
								'blockForPeriod'      => isset( $unserialized_meta['blocking_voters_interval_unit'] ) ? $unserialized_meta['blocking_voters_interval_unit'] : 'minutes',
								'limitVotesPerUser'   =>
									isset( $unserialized_meta['limit_number_of_votes_per_user'] ) && '' != $unserialized_meta['limit_number_of_votes_per_user'] ? $unserialized_meta['limit_number_of_votes_per_user'] : 'no',
								'votesPerUserAllowed' => isset( $unserialized_meta['number_of_votes_per_user'] ) && '' != $unserialized_meta['number_of_votes_per_user'] ? $unserialized_meta['number_of_votes_per_user'] : 3,
							),
							'results' => array(
								'showResultsMoment' => $results_moment,
								'customDateResults' => isset( $unserialized_meta['view_results_start_date'] ) ? $unserialized_meta['view_results_start_date'] : '',
								'showResultsTo'     => $show_results_to,
								'backToVoteOption'  => isset( $unserialized_meta['view_back_to_vote_link'] ) && '' != $unserialized_meta['view_back_to_vote_link'] ? $unserialized_meta['view_back_to_vote_link'] : 'no',
								'backToVoteCaption' => 'Back to vote',
								'sortResults'       => $sorting_results,
								'sortResultsRule'   => isset( $unserialized_meta['sorting_results_direction'] ) && '' != $unserialized_meta['sorting_results_direction'] ? $unserialized_meta['sorting_results_direction'] : 'asc',
								'displayResultsAs'  => $displayResultsAs,
								'resultsDetails'    => $resultsDetails,
							),
						),
					);
					$pollElementsArray = array();
					if ( self::check_if_table_exists( $polls_questions_table_name ) && self::check_if_table_exists( $polls_questions_meta_table_name ) ) {
						$pollQuestions = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT * FROM `{$polls_questions_table_name}` INNER JOIN `{$polls_questions_meta_table_name}` ON
								`{$polls_questions_table_name}`.`ID` = `$polls_questions_meta_table_name`.`yop_poll_question_id` WHERE `poll_id` = %s",
								$poll->ID
							)
						);
						foreach ( $pollQuestions as $pQ ) {
							$qArr                     = array();
							$unserialized_q_meta      = unserialize( $pQ->meta_value );
							$pollQuestionAnswers      = $wpdb->get_results(
								$wpdb->prepare(
									"SELECT * FROM `{$polls_answers_table_name}` INNER JOIN `{$polls_answers_meta_table_name}` ON `{$polls_answers_table_name}`.`ID` = `{$polls_answers_meta_table_name}`.`yop_poll_answer_id` WHERE `poll_id` = %s AND `question_id` = %s",
									$poll->ID,
									$pQ->ID
								)
							);
							$pollQuestionCustoms      = $wpdb->get_results(
								$wpdb->prepare(
									"SELECT `custom_field`, `required` FROM `{$polls_questions_table_name}` INNER JOIN `{$polls_questions_customs_table}` on `{$polls_questions_table_name}`.`ID` = `{$polls_questions_customs_table}`.`question_id` WHERE `{$polls_questions_table_name}`.`poll_id` = %s AND `question_id` = %s",
									$poll->ID,
									$pQ->ID
								)
							);
							$x                        = 0;
							$pollQuestionAnswersArray = array();
							switch ( $pQ->type ) {
								case 'text': {
									foreach ( $pollQuestionAnswers as $pQA ) {
										$unserialized_a_meta = unserialize( $pQA->meta_value );
										switch ( $pQA->type ) {
											case 'text': {
												$pQAA = array(
													'ID'             => $pQA->ID,
													'question_order' => $pQA->question_order,
													'type'           => 'text',
													'text'           => stripslashes( $pQA->answer ),
													'options'        => array(
														'makeDefault'  => isset( $unserialized_a_meta['is_default_answer'] ) ? $unserialized_a_meta['is_default_answer'] : 'no',
														'makeLink'     => 'no',
														'link'         => '',
														'resultsColor' => isset( $unserialized_a_meta['bar_background'] ) ? $unserialized_a_meta['bar_background'] : '',
													),
												);
												break;
											}
											case 'other': {
												$pQAA = array(
													'ID'             => $pQA->ID,
													'question_order' => $pQA->question_order,
													'type'           => 'text',
													'text'           => stripslashes( $pQA->answer ),
													'is_other'       => true,
													'options'        => array(
														'makeDefault'  => isset( $unserialized_a_meta['is_default_answer'] ) ? $unserialized_a_meta['is_default_answer'] : 'no',
														'makeLink'     => 'no',
														'link'         => '',
														'resultsColor' => isset( $unserialized_a_meta['bar_background'] ) ? $unserialized_a_meta['bar_background'] : '',
													),
												);
												break;
											}
											case 'image': {
												$pQAA = array(
													'ID'             => $pQA->ID,
													'question_order' => $pQA->question_order,
													'type'           => 'image',
													'text'           => stripslashes( $pQA->answer ),
													'options'        => array(
														'makeDefault'  => isset( $unserialized_a_meta['is_default_answer'] ) ? $unserialized_a_meta['is_default_answer'] : 'no',
														'makeLink'     => 'no',
														'addText'      => 'no',
														'text'         => '',
														'resultsColor' => isset( $unserialized_a_meta['bar_background'] ) ? $unserialized_a_meta['bar_background'] : '#000',
													),
												);
												break;
											}
										}
										$pollQuestionAnswersArray[ $x ] = $pQAA;
										$x ++;
									}
									if ( isset( $unserialized_q_meta['display_answers'] ) && '' != $unserialized_q_meta['display_answers'] ) {
										if ( 'orizontal' === $unserialized_q_meta['display_answers'] ) {
											$answersDisplay = 'horizontal';
										} elseif ( 'tabulated' === $unserialized_q_meta['display_answers'] ) {
											$answersDisplay = 'columns';
										} else {
											$answersDisplay = $unserialized_q_meta['display_answers'];
										}
									} else {
										$answersDisplay = 'vertical';
									}
									$pollElementsArray[] = array(
										'ID'         => $pQ->ID,
										'type'       => 'text-question',
										'text'       => stripslashes( $pQ->question ),
										'poll_order' => $pQ->poll_order,
										'answers'    => $pollQuestionAnswersArray,
										'options'    => array(
											'allowOtherAnswers'            => isset( $unserialized_q_meta['allow_other_answers'] ) && '' != $unserialized_q_meta['allow_other_answers'] ? $unserialized_q_meta['allow_other_answers'] : 'no',
											'otherAnswersLabel'            => isset( $unserialized_q_meta['other_answers_label'] ) && '' != trim( $unserialized_q_meta['other_answers_label'] ) ? $unserialized_q_meta['other_answers_label'] : 'Other',
											'addOtherAnswers'              =>
												( isset( $unserialized_q_meta['add_other_answers_to_default_answers'] ) && ( '' !== strval( $unserialized_q_meta['add_other_answers_to_default_answers'] ) ) ) ? $unserialized_q_meta['add_other_answers_to_default_answers'] : 'no',
											'displayOtherAnswersInResults' =>
												isset( $unserialized_q_meta['display_other_answers_values'] ) ? $unserialized_q_meta['display_other_answers_values'] : 'no',
											'resultsColorForOtherAnswers' => '#000000',
											'allowMultipleAnswers'         =>
												isset( $unserialized_q_meta['allow_multiple_answers'] ) && '' != $unserialized_q_meta['allow_multiple_answers'] ? $unserialized_q_meta['allow_multiple_answers'] : 'no',
											'multipleAnswersMinim'         =>
												isset( $unserialized_q_meta['allow_multiple_answers_min_number'] ) && $unserialized_q_meta['allow_multiple_answers_min_number'] > 0 ? $unserialized_q_meta['allow_multiple_answers_min_number'] : 1,
											'multipleAnswersMaxim'         => isset( $unserialized_q_meta['allow_multiple_answers_number'] ) && '' !== $unserialized_q_meta['allow_multiple_answers_number'] ? $unserialized_q_meta['allow_multiple_answers_number'] : 3,
											'answersDisplay'               => $answersDisplay,
											'answersColumns'               =>
												isset( $unserialized_q_meta['display_answers_tabulated_cols'] ) && '' !== $unserialized_q_meta['display_answers_tabulated_cols'] ? $unserialized_q_meta['display_answers_tabulated_cols'] : 2,
											'answersSort'                  => 'as-defined',
										),
									);
									break;
								}
								case 'media': {
									foreach ( $pollQuestionAnswers as $pQA ) {
										$unserialized_a_meta = unserialize( $pQA->meta_value );
										switch ( $pQA->type ) {
											case 'text': {
												$pQAA = array(
													'ID'             => $pQA->ID,
													'question_order' => $pQA->question_order,
													'type'           => 'text',
													'text'           => stripslashes( $pQA->answer ),
													'options'        => array(
														'makeDefault'  => isset( $unserialized_a_meta['is_default_answer'] ) ? $unserialized_a_meta['is_default_answer'] : 'no',
														'makeLink'     => 'no',
														'link'         => '',
														'resultsColor' => isset( $unserialized_a_meta['bar_background'] ) ? $unserialized_a_meta['bar_background'] : '',
													),
												);
												break;
											}
											case 'image': {
												$pQAA = array(
													'ID'             => $pQA->ID,
													'question_order' => $pQA->question_order,
													'type'           => 'image',
													'text'           => stripslashes( $pQA->answer ),
													'options'        => array(
														'makeDefault'  => isset( $unserialized_a_meta['is_default_answer'] ) ? $unserialized_a_meta['is_default_answer'] : 'no',
														'makeLink'     => 'no',
														'addText'      => 'no',
														'text'         => '',
														'resultsColor' => isset( $unserialized_a_meta['bar_background'] ) ? $unserialized_a_meta['bar_background'] : '#000',
													),
												);
												break;
											}
										}
										$pollQuestionAnswersArray[ $x ] = $pQAA;
										$x ++;
									}
									if ( isset( $unserialized_q_meta['display_answers'] ) && '' != $unserialized_q_meta['display_answers'] ) {
										if ( 'orizontal' === $unserialized_q_meta['display_answers'] ) {
											$answersDisplay = 'horizontal';
										} elseif ( 'tabulated' === $unserialized_q_meta['display_answers'] ) {
											$answersDisplay = 'columns';
										} else {
											$answersDisplay = $unserialized_q_meta['display_answers'];
										}
									} else {
										$answersDisplay = 'vertical';
									}
									$pollElementsArray[] = array(
										'ID'         => $pQ->ID,
										'type'       => 'media-question',
										'text'       => stripslashes( $pQ->question ),
										'poll_order' => $pQ->poll_order,
										'answers'    => $pollQuestionAnswersArray,
										'options'    => array(
											'allowOtherAnswers'            => isset( $unserialized_q_meta['allow_other_answers'] ) && '' != $unserialized_q_meta['allow_other_answers'] ? $unserialized_q_meta['allow_other_answers'] : 'no',
											'otherAnswersLabel'            => isset( $unserialized_q_meta['other_answers_label'] ) && '' != trim( $unserialized_q_meta['other_answers_label'] ) ? $unserialized_q_meta['other_answers_label'] : 'Other',
											'addOtherAnswers'              =>
												( isset( $unserialized_q_meta['add_other_answers_to_default_answers'] ) && ( '' !== strval( $unserialized_q_meta['add_other_answers_to_default_answers'] ) ) ) ? $unserialized_q_meta['add_other_answers_to_default_answers'] : 'no',
											'displayOtherAnswersInResults' =>
												isset( $unserialized_q_meta['display_other_answers_values'] ) ? $unserialized_q_meta['display_other_answers_values'] : 'no',
											'resultsColorForOtherAnswers' => '#000000',
											'allowMultipleAnswers'         =>
												isset( $unserialized_q_meta['allow_multiple_answers'] ) ? $unserialized_q_meta['allow_multiple_answers'] : 'no',
											'multipleAnswersMinim'         =>
												isset( $unserialized_q_meta['allow_multiple_answers_min_number'] ) && '' != $unserialized_q_meta['allow_multiple_answers_min_number'] ? $unserialized_q_meta['allow_multiple_answers_min_number'] : 1,
											'multipleAnswersMaxim'         => isset( $unserialized_q_meta['allow_multiple_answers_number'] ) && '' != $unserialized_q_meta['allow_multiple_answers_number'] ? $unserialized_q_meta['allow_multiple_answers_number'] : 3,
											'answersDisplay'               => $answersDisplay,
											'answersColumns'               =>
												isset( $unserialized_q_meta['display_answers_tabulated_cols'] ) && '' !== $unserialized_q_meta['display_answers_tabulated_cols'] ? $unserialized_q_meta['display_answers_tabulated_cols'] : 2,
											'answersSort'                  => 'as-defined',
										),
									);
									break;
								}
							}
							foreach ( $pollQuestionCustoms as $pQC ) {
								$pollElementsArray[] = array(
									'ID'             => self::$maxElementID,
									'poll_order' => 1,
									'type'    => 'custom-field',
									'text'    => stripslashes( $pQC->custom_field ),
									'options' => array(
										'makeRequired' => 'yes' === $pQC->required ? 'yes' : 'no',
										'cType' => 'textfield',
									),
								);
								self::$maxElementID++;
							}
						}
					}
					$pollArray['elements'] = $pollElementsArray;
					$responseArray         = YOP_Poll_Polls::add( json_decode( json_encode( $pollArray ) ) );
					if ( '' !== $responseArray['poll_id'] ) {
						$result = $wpdb->update( $polls_table_name, array( 'processed' => true ), array( 'ID' => $poll->ID ) );
						self::$processed_polls += $result;
					}
					unset( $pollArray );
					unset( $responseArray );
				}
			}
		}
	}

	private static function set_gdpr( $enableGdpr, $gdprSolution ) {
		self::$enableGdpr = $enableGdpr;
		self::$gdprSolution = $gdprSolution;
	}

	private static function make_ip_gdpr_compliant( $ipaddress ) {
		$compliant_ipaddress = '';
		if ( 'yes' === self::$enableGdpr ) {
			switch ( self::$gdprSolution ) {
				case 'consent': {
					$compliant_ipaddress = $ipaddress;
					break;
				}
				case 'anonymize': {
					$compliant_ipaddress = YOP_Poll_Helper::anonymize_ip( $ipaddress );
					break;
				}
				case 'nostore': {
					$compliant_ipaddress = '';
					break;
				}
				default: {
					$compliant_ipaddress = $ipaddress;
					break;
				}
			}
		} else {
			$compliant_ipaddress = $ipaddress;
		}
		return $compliant_ipaddress;
	}

	private static function make_cookie_gdpr_compliant( $cookie ) {
		$compliant_cookie = '';
		if ( 'yes' === self::$enableGdpr ) {
			switch ( self::$gdprSolution ) {
				case 'consent': {
					$compliant_cookie = $cookie;
					break;
				}
				case 'anonymize': {
					$compliant_cookie = '';
					break;
				}
				case 'nostore': {
					$compliant_cookie = '';
					break;
				}
				default: {
					$compliant_cookie = $cookie;
					break;
				}
			}
		} else {
			$compliant_cookie = $cookie;
		}
		return $compliant_cookie;
	}

	private static function import_bans( $skip_table_check = false ) {
		global $wpdb;
		$polls_bans_table = $GLOBALS['wpdb']->prefix . 'yop2_poll_bans';
		$values           = array();
		$current_user = wp_get_current_user();
		if ( $skip_table_check ) {
			self::$checked_existence_bans = true;
			self::$bans_table_exists      = true;
		}
		if ( ! self::$checked_existence_bans ) {
			if ( self::check_if_table_exists( $polls_bans_table ) ) {
				if ( ! self::check_if_column_exists( $polls_bans_table, 'processed' ) ) {
					$wpdb->query( "ALTER TABLE `{$polls_bans_table}` ADD processed BOOLEAN DEFAULT FALSE " );
				}
				self::$checked_existence_bans = true;
				self::$bans_table_exists      = true;
			}
		}

		if ( self::$checked_existence_bans && self::$bans_table_exists ) {
			self::$unprocessed_bans = $wpdb->get_var( "SELECT COUNT(ID) FROM `{$polls_bans_table}` WHERE `processed` = false" );
			$bans                   = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM `{$polls_bans_table}` LIMIT %d",
					self::$ajax_limit
				)
			);
			$bansIds = array();
			if ( count( $bans ) > 0 ) {
				foreach ( $bans as $ban ) {
					$values[] = $wpdb->prepare(
						'(%d, %d, %s, %s, %s)',
						$current_user->ID,
						$ban->poll_id,
						$ban->type,
						$ban->value,
						current_time( 'mysql' )
					);
					$bansIds[] = $wpdb->prepare( '%s', $ban->id );
				}
				$query = "INSERT INTO `{$GLOBALS['wpdb']->yop_poll_bans}` (`author`, `poll_id`, `b_by`, `b_value`, `added_date`) VALUES ";
				if ( count( $values ) > 0 ) {
					$query  .= implode( ",\n", $values );
					$result = $wpdb->query( $query );
					if ( ! $result ) {
						$last_error = $wpdb->last_error;
						return array(
							'response_code' => 1,
							'message' => esc_html__( $last_error, 'yop-poll' ),
						);
					} else {
						$res = $wpdb->query( "UPDATE {$polls_bans_table} SET `processed` = true WHERE `ID` IN (" . implode( ',', $bansIds ) . ')' );
						self::$processed_bans += $res;
						if ( self::$processed_bans == self::$unprocessed_bans ) {
							return array(
								'response_code' => - 1,
								'message' => esc_html__( 'Processed ', 'yop-poll' ) . self::$processed_bans . esc_html__( ' out of ', 'yop-poll' ) . self::$unprocessed_bans . esc_html__( ' records on table bans.', 'yop-poll' ),
							);
						} else {
							return array(
								'response_code' => 1,
								'message' => esc_html__( 'Processed ', 'yop-poll' ) . self::$processed_bans . esc_html__( ' out of ', 'yop-poll' ) . self::$unprocessed_bans . esc_html__( ' remaining records on table bans.', 'yop-poll' ),
							);
						}
					}
				}
			} else {
				return array(
					'response_code' => - 1,
					'message' => esc_html__( 'No bans to process.', 'yop-poll' ),
				);
			}
		} else {
			return array(
				'response_code' => - 1,
				'message' => esc_html__( 'No bans table, skipping.', 'yop-poll' ),
			);
		}

	}

	private static function import_votes( $skip_table_check = false ) {
		global $wpdb;
		$polls_results_table_name         = $GLOBALS['wpdb']->prefix . 'yop2_poll_results';
		$polls_results_customs_table_name = $GLOBALS['wpdb']->prefix . 'yop2_poll_votes_custom_fields';
		$current_user                     = wp_get_current_user();
		if ( $skip_table_check ) {
			self::$checked_existence_votes = true;
			self::$votes_table_exists      = true;
		}
		if ( ! self::$checked_existence_votes ) {
			if ( self::check_if_table_exists( $polls_results_table_name ) ) {
				if ( ! self::check_if_column_exists( $polls_results_table_name, 'processed' ) ) {
					$wpdb->query( "ALTER TABLE `{$polls_results_table_name}` ADD processed BOOLEAN DEFAULT FALSE " );
				}
				self::$checked_existence_votes = true;
				self::$votes_table_exists      = true;
			}
		}

		if ( self::$checked_existence_votes && self::$votes_table_exists ) {
			self::$unprocessed_votes = $wpdb->get_var( "SELECT COUNT(ID) FROM `{$polls_results_table_name}` WHERE processed = false" );
			$results                 = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT `{$polls_results_table_name}`.* FROM  `{$polls_results_table_name}` WHERE `processed` = false LIMIT %d",
					self::$ajax_limit
				)
			);
			if ( 0 === count( $results ) ) {
				return array(
					'response_code' => - 1,
					'message' => esc_html__( 'No votes to process.', 'yop-poll' ),
				);
			} else {
				$voteData   = array(
					'elements' => array(),
					'user' => array(),
				);
				$votesArray = array();
				$resultsIds = array();
				foreach ( $results as $result ) {
					$voteData   = array(
						'elements' => array(),
						'user' => array(),
					);
					$resultsIds[]   = $wpdb->prepare( '%s', $result->ID );
					$result_details = get_object_vars( json_decode( $result->result_details ) );
					foreach ( $result_details as $rd ) {
						$a_data = array();
						foreach ( $rd->a as $answer_id ) {
							$a_data[] = array(
								'id'   => $answer_id,
								'data' => true,
							);
							$wpdb->query(
								$wpdb->prepare(
									"UPDATE `{$GLOBALS['wpdb']->yop_poll_subelements}` SET `total_submits` = ( `total_submits` + 1 ) WHERE `id` = %d",
									array( $answer_id )
								)
							);

						}
						$voteData['elements'][] = array(
							'id'   => $rd->id,
							'type' => 'question',
							'data' => $a_data,
						);
						if ( property_exists( $rd, 'cf' ) && count( $rd->cf ) > 0 ) {
							$customs_array = array();
							foreach ( $rd->cf as $custom_item ) {
								$customs_array[] = $wpdb->prepare( '%s', $custom_item );
							}
							$customs = $wpdb->get_results( "SELECT `id`, `user_id`,`custom_field_value` FROM `{$polls_results_customs_table_name}` WHERE ID IN (" . implode( ',', $customs_array ) . ')' );
							if ( $customs ) {
								foreach ( $customs as $cust ) {
									$custom_text = $wpdb->get_var(
										$wpdb->prepare(
											"SELECT `custom_field` FROM {$GLOBALS['wpdb']->prefix}yop2_poll_custom_fields WHERE ID = %s",
											$cust->id
										)
									);
									$added_custom = $wpdb->get_var(
										$wpdb->prepare(
											"SELECT `id` FROM `{$GLOBALS['wpdb']->yop_poll_elements}` WHERE `poll_id` = %d AND `etext` = %s LIMIT 1",
											array(
												$result->poll_id,
												$custom_text,
											)
										)
									);
									if ( '' != $added_custom && $added_custom > 0 ) {
										$custom_id = $added_custom;
									} else {
										$wpdb->insert(
											$GLOBALS['wpdb']->yop_poll_elements,
											array(
												'poll_id' => $result->poll_id,
												'author' => $current_user->ID,
												'etext' => 'Custom field',
												'etype' => 'custom_field',
												'status' => 'active',
												'meta_data' => serialize( ['makeRequired' => 'no'] ),
												'added_date' => date( 'Y-m-d H:i:s' ),
												'modified_date' => date( 'Y-m-d H:i:s' ),
											),
											array(
												'%d',
												'%d',
												'%s',
												'%s',
												'%s',
												'%s',
												'%s',
												'%s',
										 	)
										);
										$custom_id = $wpdb->insert_id;
									}
									$voteData['elements'][] = array(
										'id'   => $custom_id,
										'type' => 'custom-field',
										'data' => [$cust->custom_field_value],
									);
								}
							}
						}
					}
					$voteData['user'] = array(
						'first_name' => '',
						'last_name'  => '',
					);
					$data             = array(
						'poll_id'           => $result->poll_id,
						'user_id'           => $result->user_id,
						'user_email'        => '',
						'user_type'         => $result->user_type,
						'ipaddress'         => self::make_ip_gdpr_compliant( $result->ip ),
						'tracking_id'       => $result->tr_id,
						'voter_id'          => self::make_cookie_gdpr_compliant( $result->vote_id ),
						'voter_fingerprint' => '',
						'vote_data'         => serialize( $voteData ),
						'status'            => 'active',
						'added_date'        => $result->vote_date,
					);
					$votesArray[] = $wpdb->prepare(
						'(%d, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s)',
						$data['poll_id'],
						$data['user_id'],
						$data['user_email'],
						$data['user_type'],
						$data['ipaddress'],
						$data['tracking_id'],
						$data['voter_id'],
						$data['voter_fingerprint'],
						$data['vote_data'],
						$data['status'],
						$data['added_date']
					);
				}
				$query = "INSERT INTO `{$GLOBALS['wpdb']->yop_poll_votes}` (`poll_id`, `user_id`, `user_email`, `user_type`, `ipaddress`, `tracking_id`, `voter_id`, `voter_fingerprint`, `vote_data`,
					`status`, `added_date`) VALUES ";
				if ( count( $votesArray ) > 0 ) {
					$query    .= implode( ",\n", $votesArray );
					$response = $wpdb->query( $query );
					if ( $response ) {
						$result = $wpdb->query( "UPDATE `{$polls_results_table_name}` SET `processed` = true WHERE ID IN (" . implode( ',', $resultsIds ) . ')' );
						self::$processed_votes += $result;
						if ( self::$processed_votes == self::$unprocessed_votes ) {
							return array(
								'response_code' => -1,
								'message' => esc_html__( 'Processed ', 'yop-poll' ) . self::$processed_votes . esc_html__( ' out of ', 'yop-poll' ) . self::$unprocessed_votes . esc_html__( ' records on table votes.', 'yop-poll' ),
							);
						} else {
							return array(
								'response_code' => 1,
								'message' => esc_html__( 'Processed ', 'yop-poll' ) . self::$processed_votes . esc_html__( ' out of ', 'yop-poll' ) . self::$unprocessed_votes . esc_html__( ' remaining records on table votes.', 'yop-poll' ),
							);
						}
					} else {
						return array(
							'response_code' => 1,
							'message' => esc_html__( $wpdb->last_error, 'yop-poll' ),
						);
					}
				} else {
					return array(
						'response_code' => - 1,
						'message' => esc_html__( 'No votes to process.', 'yop-poll' ),
					);
				}
			}
		} else {
			return array(
				'response_code' => - 1,
				'message' => esc_html__( 'No votes table, skipping.', 'yop-poll' ),
			);
		}

	}

	private static function import_logs( $skip_table_check = false ) {
		global $wpdb;
		$polls_logs_table_name            = $GLOBALS['wpdb']->prefix . 'yop2_poll_logs';
		$polls_results_customs_table_name = $GLOBALS['wpdb']->prefix . 'yop2_poll_votes_custom_fields';
		$current_user                     = wp_get_current_user();
		if ( $skip_table_check ) {
			self::$checked_existence_logs = true;
			self::$logs_table_exists      = true;
		}
		if ( ! self::$checked_existence_bans ) {
			if ( self::check_if_table_exists( $polls_logs_table_name ) ) {
				if ( ! self::check_if_column_exists( $polls_logs_table_name, 'processed' ) ) {
					$wpdb->query( "ALTER TABLE `{$polls_logs_table_name}` ADD processed BOOLEAN DEFAULT FALSE " );
				}
				self::$checked_existence_logs = true;
				self::$logs_table_exists      = true;
			}
		}
		if ( self::$checked_existence_logs && self::$logs_table_exists ) {
			self::$unprocessed_logs = $wpdb->get_var( "SELECT COUNT(ID) FROM `{$polls_logs_table_name}` WHERE processed = false" );
			$log_results            = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT `{$polls_logs_table_name}`.* FROM  `{$polls_logs_table_name}` WHERE `processed` = false LIMIT %d",
					self::$ajax_limit
				)
			);
			if ( 0 == count( $log_results ) ) {
				return array(
					'response_code' => - 1,
					'message' => esc_html__( 'No logs to process.', 'yop-poll' ),
				);
			} else {
				$logData   = array(
					'elements' => array(),
					'user' => array(),
				);
				$logsArray = array();
				$logsIds   = array();
				foreach ( $log_results as $lresult ) {
					$logData   = array(
						'elements' => array(),
						'user' => array(),
					);
					$logsIds[] = $wpdb->prepare( '%s', $lresult->ID );
					if ( 'Success' === $lresult->message ) {
						$lresult_details = get_object_vars( json_decode( $lresult->vote_details ) );
						$q_data          = array();
						foreach ( $lresult_details as $rd ) {
							$a_data = array();
							foreach ( $rd->a as $answer_id ) {
								$a_data[] = array(
									'id'   => $answer_id,
									'data' => true,
								);
							}
							$logData['elements'][] = array(
								'id'   => $rd->id,
								'type' => 'question',
								'data' => $a_data,
							);
							if ( count( $rd->cf ) > 0 ) {
								$customs_array = array();
								foreach ( $rd->cf as $custom_item ) {
									$customs_array[] = $wpdb->prepare( '%s', $custom_item );
								}
								$customs = $wpdb->get_results( "SELECT `id`, `custom_field_value` FROM `{$polls_results_customs_table_name}` WHERE ID IN (" . implode( ',', $customs_array ) . ')' );
								if ( $customs ) {
									foreach ( $customs as $cust ) {
										$custom_text = $wpdb->get_var(
											$wpdb->prepare(
												"SELECT `custom_field` FROM {$GLOBALS['wpdb']->prefix}yop2_poll_custom_fields WHERE ID = %s",
												$cust->id
											)
										);
										$added_custom = $wpdb->get_var(
											$wpdb->prepare(
												"SELECT `id` FROM `{$GLOBALS['wpdb']->yop_poll_elements}` WHERE `poll_id` = %d AND `etext` = %s LIMIT 1",
												array(
													$lresult->poll_id,
													$custom_text,
												)
											)
										);
										if ( '' != $added_custom && $added_custom > 0 ) {
											$custom_id = $added_custom;
										} else {
											$wpdb->insert(
												$GLOBALS['wpdb']->yop_poll_elements,
												array(
													'poll_id' => $lresult->poll_id,
													'author' => $current_user->ID,
													'etext' => 'Custom field',
													'etype' => 'custom_field',
													'status' => 'active',
													'meta_data' => serialize( ['makeRequired' => 'no'] ),
													'added_date' => date( 'Y-m-d H:i:s' ),
													'modified_date' => date( 'Y-m-d H:i:s' ),
												),
												array(
													'%d',
													'%d',
													'%s',
													'%s',
													'%s',
													'%s',
													'%s',
													'%s',
												)
											);
											$custom_id = $wpdb->insert_id;
										}
										$logData['elements'][] = array(
											'id'   => $custom_id,
											'type' => 'custom-field',
											'data' => array(
												$cust->custom_field_value,
											),
										);
									}
								}
							}
						}
					}

					$logData['user'] = array(
						'first_name' => '',
						'last_name'  => '',
					);
					$vote_message    = [ $lresult->message ];
					$data            = array(
						'poll_id'           => $lresult->poll_id,
						'poll_author'       => $current_user->ID,
						'user_id'           => $lresult->user_id,
						'user_email'        => '',
						'user_type'         => $lresult->user_type,
						'ipaddress'         => self::make_ip_gdpr_compliant( $lresult->ip ),
						'tracking_id'       => $lresult->tr_id,
						'voter_id'          => self::make_cookie_gdpr_compliant( $lresult->vote_id ),
						'voter_fingerprint' => '',
						'vote_data'         => serialize( $logData ),
						'vote_message'      => serialize( $vote_message ),
						'added_date'        => $lresult->vote_date,
					);
					$logsArray[] = $wpdb->prepare(
						'(%d, %d, %d, %s, %s, %s, %s, %s, %s, %s, %s, %s)',
						$data['poll_id'],
						$data['poll_author'],
						$data['user_id'],
						$data['user_email'],
						$data['user_type'],
						$data['ipaddress'],
						$data['tracking_id'],
						$data['voter_id'],
						$data['voter_fingerprint'],
						$data['vote_data'],
						$data['vote_message'],
						$data['added_date']
					);
				}
				$query     = "INSERT INTO `{$GLOBALS['wpdb']->yop_poll_logs}` (`poll_id`, `poll_author`, `user_id`, `user_email`, `user_type`, `ipaddress`, `tracking_id`, `voter_id`, `voter_fingerprint`, `vote_data`,
				`vote_message`, `added_date`) VALUES ";
				$query    .= implode( ",\n", $logsArray );
				$response = $wpdb->query( $query );
				if ( $response ) {
					$result = $wpdb->query( "UPDATE `{$polls_logs_table_name}` SET `processed` = true WHERE `ID` IN (" . implode( ',', $logsIds ) . ')' );
					self::$processed_logs += $result;
					if ( self::$processed_logs == self::$unprocessed_logs ) {
						return array(
							'response_code' => -1,
							'message' => esc_html__( 'Processed ', 'yop-poll' ) . self::$processed_logs . esc_html__( ' out of ', 'yop-poll' ) . self::$unprocessed_logs . esc_html__( ' records on table logs.', 'yop-poll' ),
						);
					} else {
						return array(
							'response_code' => 1,
							'message' => esc_html__( 'Processed ', 'yop-poll' ) . self::$processed_logs . esc_html__( ' out of remaining ', 'yop-poll' ) . self::$unprocessed_logs . esc_html__( ' records on table logs.', 'yop-poll' ),
						);
					}
				} else {
					return array(
						'response_code' => 1,
						'message' => esc_html__( $wpdb->last_error, 'yop-poll' ),
					);
				}
			}
		} else {
			return array(
				'response_code' => - 1,
				'message' => esc_html__( 'No logs table, skipping.', 'yop-poll' ),
			);
		}
	}

	private static function check_if_table_exists( $table_name ) {
		global $wpdb;
		if ( 0 == $wpdb->get_var(
			$wpdb->prepare(
				'SELECT COUNT(1) FROM `information_schema`.`tables` WHERE `table_schema` = %s AND `table_name` = %s',
				DB_NAME,
				$table_name
			)
		) ) {
			return false;
		}
		return true;
	}

	private static function check_if_column_exists( $table_name, $column_name ) {
		global $wpdb;
		if ( 0 == $wpdb->get_var(
			$wpdb->prepare(
				'SELECT count(1) FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA` = %s AND `TABLE_NAME` = %s AND `COLUMN_NAME` = %s ',
				DB_NAME,
				$table_name,
				$column_name
			)
		) ) {
			return false;
		}
		return true;
	}

	private static function create_skin_base_from_template( $template_id ) {
		$skin_base = '';
		switch ( $template_id ) {
			case 1: {
				$skin_base = 'white-v1';
				break;
			}
			case 2: {
				$skin_base = 'gray-v1';
				break;
			}
			case 3: {
				$skin_base = 'dark-v1';
				break;
			}
			case 4: {
				$skin_base = 'blue-v1';
				break;
			}
			case 5: {
				$skin_base = 'blue-v2';
				break;
			}
			case 6: {
				$skin_base = 'blue-v3';
				break;
			}
			case 7: {
				$skin_base = 'red-v1';
				break;
			}
			case 8: {
				$skin_base = 'red-v2';
				break;
			}
			case 9: {
				$skin_base = 'red-v3';
				break;
			}
			case 10: {
				$skin_base = 'green-v1';
				break;
			}
			case 11: {
				$skin_base = 'green-v2';
				break;
			}
			case 12: {
				$skin_base = 'green-v3';
				break;
			}
			case 13: {
				$skin_base = 'orange-v1';
				break;
			}
			case 14: {
				$skin_base = 'orange-v2';
				break;
			}
			case 15: {
				$skin_base = 'orange-v3';
				break;
			}
			case 16: {
				$skin_base = 'minimal-black';
				break;
			}
			case 17: {
				$skin_base = 'minimal-black';
				break;
			}
			case 18: {
				$skin_base = 'minimal-black';
				break;
			}
			case 19: {
				$skin_base = 'minimal-black';
				break;
			}
			case 20: {
				$skin_base = 'minimal-black';
				break;
			}
			case 21: {
				$skin_base = 'minimal-black';
				break;
			}
			case 22: {
				$skin_base = 'minimal-black';
				break;
			}
			case 23: {
				$skin_base = 'minimal-black';
				break;
			}
			case 24: {
				$skin_base = 'minimal-black';
				break;
			}
			case 25: {
				$skin_base = 'minimal-black';
				break;
			}
			case 26: {
				$skin_base = 'minimal-black';
				break;
			}
			case 27: {
				$skin_base = 'minimal-black';
				break;
			}
			case 28: {
				$skin_base = 'minimal-black';
				break;
			}
			default: {
				$skin_base = 'minimal-black';
				break;
			}
		}
		return $skin_base;
	}

	private static function create_css_from_template( $template_id ) {
		$css_array = array();
		switch ( $template_id ) {
			case 1: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '0',
						'borderColor' => '#ffffff',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#000',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 2: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#EEEEEE',
						'borderSize' => '0',
						'borderColor' => '#EEEEEE',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#000',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
							text-align: center;
							}',
					),
				);
				break;
			}
			case 3: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#555555',
						'borderSize' => '0',
						'borderColor' => '#555555',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 4: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#327BD6',
						'borderSize' => '0',
						'borderColor' => '#327BD6',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 5: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '0',
						'borderColor' => '#ffffff',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}
							.basic-yop-poll-container[data-uid] .basic-question-title h5 {
								background-color: #327BD6;
								padding: 5px 0;
							}',
					),
				);
				break;
			}
			case 6: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#327BD6',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 7: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#B70004',
						'borderSize' => '0',
						'borderColor' => '#B70004',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 8: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '0',
						'borderColor' => '#ffffff',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}
							.basic-yop-poll-container[data-uid] .basic-question-title h5 {
								background-color: #B70004;
								padding: 5px 0;
							}',
					),
				);
				break;
			}
			case 9: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#B70004',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 10: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#3F8B43',
						'borderSize' => '0',
						'borderColor' => '#3F8B43',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 11: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '0',
						'borderColor' => '#ffffff',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 12: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#3F8B43',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 13: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#FB6911',
						'borderSize' => '0',
						'borderColor' => '#FB6911',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 14: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '0',
						'borderColor' => '#ffffff',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}
							.basic-yop-poll-container[data-uid] .basic-question-title h5 {
								background-color: #FB6911;
								padding: 5px 0;
							}',
					),
				);
				break;
			}
			case 15: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#FB6911',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#000',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#55555',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 16: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#858585',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#858585',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#333',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#333',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 17: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#bfe5f8',
						'borderSize' => '1',
						'borderColor' => '#bfe5f8',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#90c1cf',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#90c1cf',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#333',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#333',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 18: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#bfe5f8',
						'borderSize' => '1',
						'borderColor' => '#bfe5f8',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#90c1cf',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#90c1cf',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#333',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#333',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 19: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#f1f0ff',
						'borderSize' => '2',
						'borderColor' => '#ACCBE0',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#666666',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#666666',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#333',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#333',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 20: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#333333',
						'borderSize' => '1',
						'borderColor' => '#FFF',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 21: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#333333',
						'borderSize' => '1',
						'borderColor' => '#333333',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 22: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#333333',
						'borderSize' => '0',
						'borderColor' => '#333333',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 23: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#333333',
						'borderSize' => '0',
						'borderColor' => '#333333',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 24: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#1D2328',
						'borderSize' => '1',
						'borderColor' => '#ffffff',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#E9E9E9',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#E9E9E9',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#69C9D4',
						'borderSize' => '0',
						'borderColor' => '#000000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 25: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '2',
						'borderColor' => '#FB6911',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#333',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '0',
						'borderColor' => '#333',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 26: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '2',
						'borderColor' => '#6b7552',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#333333',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#333333',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '0',
						'borderColor' => '#38595E',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 27: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#333333',
						'borderSize' => '2',
						'borderColor' => '#d23c3d',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#ffffff',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#fffff',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#595959',
						'borderSize' => '0',
						'borderColor' => '#ffffff',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			case 28: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '2',
						'borderColor' => '#6b7552',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#333333',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#333333',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#38595E',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#ffffff',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid]  .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
			default: {
				$css_array = array(
					'poll' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000000',
						'borderRadius' => '5',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '10',
					),
					'questions' => array(
						'textColor' => '#000',
						'textSize' => '16',
						'textWeight' => 'normal',
						'textAlign' => 'center',
					),
					'answers' => array(
						'paddingLeftRight' => '0',
						'paddingTopBottom' => '0',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
						'skin' => '',
						'colorScheme' => '',
					),
					'buttons' => array(
						'backgroundColor' => '#ffffff',
						'borderSize' => '1',
						'borderColor' => '#000',
						'borderRadius' => '0',
						'paddingLeftRight' => '10',
						'paddingTopBottom' => '5',
						'textColor' => '#000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'captcha' => array(),
					'errors' => array(
						'borderLeftColorForSuccess' => '#008000',
						'borderLeftColorForError' => '#ff0000',
						'borderLeftSize' => '10',
						'paddingTopBottom' => '0',
						'textColor' => '#000000',
						'textSize' => '14',
						'textWeight' => 'normal',
					),
					'custom' => array(
						'css' => '.basic-yop-poll-container[data-uid] .basic-vote {
								text-align: center;
							}',
					),
				);
				break;
			}
		}

		return $css_array;
	}

	public static function yop_ajax_import() {
		if ( false === is_user_logged_in() ) {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
			wp_die();
		}
		if ( check_ajax_referer( 'yop-poll-ajax-importer', '_csrf_token', false ) ) {
			$skip_table_check = false;
			if ( isset( $_REQUEST['enableGdpr'] ) && isset( $_REQUEST['gdprSolution'] ) ) {
				self::set_gdpr( sanitize_text_field( wp_unslash( $_REQUEST['enableGdpr'] ) ), sanitize_text_field( wp_unslash( $_REQUEST['gdprSolution'] ) ) );
			}
			if ( true === isset( $_REQUEST['table'] ) ) {
				switch ( $_REQUEST['table'] ) {
					case 'polls': {
						$response = self::import_polls( self::$ajax_limit, $skip_table_check );
						if ( -1 == $response['response_code'] ) {
							$table = 'bans';
							$response_code = 1;
							wp_send_json_success(
								array(
									'table' => $table,
									'response_code' => $response_code,
									'message' => $response['message'],
									'skip_table_check' => false,
								)
							);
						} else {
							$table = 'polls';
							$response_code = $response['response_code'];
							wp_send_json_success(
								array(
									'table' => $table,
									'response_code' => $response_code,
									'message' => $response['message'],
									'skip_table_check' => true,
								)
							);
						}
						break;
					}
					case 'bans': {
						$response = self::import_bans( $skip_table_check );
						if ( -1 == $response['response_code'] ) {
							$table = 'votes';
							$response_code = 1;
							wp_send_json_success(
								array(
									'table' => $table,
									'response_code' => $response_code,
									'message' => $response['message'],
									'skip_table_check' => false,
								)
							);
						} else {
							$table = 'bans';
							$response_code = $response['response_code'];
							wp_send_json_success(
								array(
									'table' => $table,
									'response_code' => $response_code,
									'message' => $response['message'],
									'skip_table_check' => true,
								)
							);
						}
						break;
					}
					case 'votes': {
						$response = self::import_votes( $skip_table_check );
						if ( -1 == $response['response_code'] ) {
							$table = 'logs';
							$response_code = 1;
							wp_send_json_success(
								array(
									'table' => $table,
									'response_code' => $response_code,
									'message' => $response['message'],
									'skip_table_check' => false,
								)
							);
						} else {
							$table = 'votes';
							$response_code = $response['response_code'];
							wp_send_json_success(
								array(
									'table' => $table,
									'response_code' => $response_code,
									'message' => $response['message'],
									'skip_table_check' => true,
								)
							);
						}
						break;
					}
					case 'logs': {
						$response = self::import_logs( $skip_table_check );
						if ( -1 == $response['response_code'] ) {
							$response_code = 'done';
							delete_option( 'yop_poll_old_version' );
							wp_send_json_success(
								array(
									'table' => 'logs',
									'response_code' => $response_code,
									'message' => $response['message'],
								)
							);
						} else {
							$response_code = $response['response_code'];
							wp_send_json_success(
								array(
									'table' => 'logs',
									'response_code' => $response_code,
									'message' => $response['message'],
									'skip_table_check' => $skip_table_check,
								)
							);
						}
						break;
					}
				}
			}
			wp_die();
		} else {
			wp_send_json_error( esc_html__( 'You are not allowed to perform this action', 'yop-poll' ) );
			wp_die();
		}
	}
}
