<div class="row">
	<div class="col-md-12">
		&nbsp;
	</div>
</div>
<div class="row vote-options">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<h4>
					<?php esc_html_e( 'Vote Button', 'yop-poll' ); ?>
				</h4>
			</div>
		</div>
		<div class="form-horizontal poll-vote-button">
			<div class="form-group">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Vote Button Label', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control vote-button-label" value="<?php echo esc_attr( $poll->meta_data['options']['poll']['voteButtonLabel'] ); ?>"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Show [Results] Link', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'yes' === $poll->meta_data['options']['poll']['showResultsLink'] ) {
			            $show_results_link_yes = 'selected';
			            $show_results_link_no = '';
						$show_results_link_class = '';
			        } else {
			            $show_results_link_yes = '';
			            $show_results_link_no = 'selected';
						$show_results_link_class = 'hide';
			        }
			        ?>
			        <select class="show-results-link admin-select" style="width:100%">
			            <option value="yes" <?php echo esc_attr( $show_results_link_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" <?php echo esc_attr( $show_results_link_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group results-link-option <?php echo esc_attr( $show_results_link_class ); ?>">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( '[Results] Link Label', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control results-label-text" value="<?php echo esc_attr( $poll->meta_data['options']['poll']['resultsLabelText'] ); ?>"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Show Total Votes', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'yes' === $poll->meta_data['options']['poll']['showTotalVotes'] ) {
			            $show_total_votes_yes = 'selected';
			            $show_total_votes_no = '';
			        } else {
			            $show_total_votes_yes = '';
			            $show_total_votes_no = 'selected';
			        }
			        ?>
			        <select class="show-total-votes admin-select" style="width:100%">
			            <option value="yes" <?php echo esc_attr( $show_total_votes_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" <?php echo esc_attr( $show_total_votes_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Show Total Answers', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'yes' === $poll->meta_data['options']['poll']['showTotalAnswers'] ) {
			            $show_total_answers_yes = 'selected';
			            $show_total_answers_no = '';
			        } else {
			            $show_total_answers_yes = '';
			            $show_total_answers_no = 'selected';
			        }
			        ?>
			        <select class="show-total-answers admin-select" style="width:100%">
			            <option value="yes" <?php echo esc_attr( $show_total_answers_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" <?php echo esc_attr( $show_total_answers_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h4>
					<?php esc_html_e( 'Preferences', 'yop-poll' ); ?>
				</h4>
			</div>
		</div>
		<div class="form-horizontal poll-preferences">
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Load using AJAX' ); ?>
				</div>
				<div class="col-md-9">
					<?php
					if ( true === isset( $poll->meta_data['options']['poll']['loadWithAjax'] ) ) {
						if ( 'yes' === $poll->meta_data['options']['poll']['loadWithAjax'] ) {
							$load_with_ajax_yes = 'selected';
							$load_with_ajax_no = '';
						} else {
							$load_with_ajax_yes = '';
							$load_with_ajax_no = 'selected';
						}
					} else {
						$load_with_ajax_yes = '';
						$load_with_ajax_no = 'selected';
					}
					?>
					<select class="load-with-ajax admin-select" style="width:100%">
			            <option value="yes" <?php echo esc_attr( $load_with_ajax_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" <?php echo esc_attr( $load_with_ajax_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Start Date', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'now' === $poll->meta_data['options']['poll']['startDateOption'] ) {
			            $start_date_now = 'selected';
			            $start_date_custom = '';
			            $start_date_custom_class = 'hide';
			        } else {
			            $start_date_now = '';
			            $start_date_custom = 'selected';
			            $start_date_custom_class = '';
			        }
			        ?>
					<select class="start-date-option admin-select" style="width:100%">
			            <option value="now" <?php echo esc_attr( $start_date_now ); ?>><?php esc_html_e( 'Now', 'yop-poll' ); ?></option>
			            <option value="custom" <?php echo esc_attr( $start_date_custom ); ?>><?php esc_html_e( 'Custom Date', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group start-date-section <?php echo esc_attr( $start_date_custom_class ); ?>">
				<div class="col-md-3">
				</div>
				<div class="col-md-9">
					<div class="input-group">
						<input type="text" class="form-control start-date-custom" value="<?php echo esc_attr( $poll->meta_data['options']['poll']['startDateCustom'] ); ?>" readonly />
						<input type="hidden" class="form-control start-date-custom-hidden" value="<?php echo esc_attr( $poll->meta_data['options']['poll']['startDateCustom'] ); ?>" />
		                <div class="input-group-addon">
							<span class="dashicons dashicons-calendar-alt show-start-date"></span>
		                </div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'End Date', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'never' === $poll->meta_data['options']['poll']['endDateOption'] ) {
			            $end_date_never = 'selected';
			            $end_date_custom = '';
			            $end_date_custom_class = 'hide';
			        } else {
			            $end_date_never = '';
			            $end_date_custom = 'selected';
			            $end_date_custom_class = '';
			        }
			        ?>
			        <select class="end-date-option admin-select" style="width:100%">
			            <option value="never" <?php echo esc_attr( $end_date_never ); ?>><?php esc_html_e( 'Never', 'yop-poll' ); ?></option>
			            <option value="custom" <?php echo esc_attr( $end_date_custom ); ?>><?php esc_html_e( 'Custom Date', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group end-date-section <?php echo esc_attr( $end_date_custom_class ); ?>">
				<div class="col-md-3">
				</div>
				<div class="col-md-9">
					<div class="input-group">
						<input type="text" class="form-control end-date-custom" value="<?php echo esc_attr( $poll->meta_data['options']['poll']['endDateCustom'] ); ?>" readonly />
						<input type="hidden" class="form-control end-date-custom-hidden" value="<?php echo esc_attr( $poll->meta_data['options']['poll']['endDateCustom'] ); ?>" />
		                <div class="input-group-addon">
							<span class="dashicons dashicons-calendar-alt show-end-date"></span>
		                </div>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Redirect after vote', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'yes' === $poll->meta_data['options']['poll']['redirectAfterVote'] ) {
			            $redirect_after_vote_yes = 'selected';
			            $redirect_after_vote_no = '';
						$redirect_after_vote_class = '';
						$redirect_url = $poll->meta_data['options']['poll']['redirectUrl'];
						$redirect_after = isset( $poll->meta_data['options']['poll']['redirectAfter'] ) ? $poll->meta_data['options']['poll']['redirectAfter'] : '2';
			        } else {
			            $redirect_after_vote_yes = '';
			            $redirect_after_vote_no = 'selected';
						$redirect_after_vote_class = 'hide';
						$redirect_url = '';
						$redirect_after = '';
			        }
			        ?>
			        <select class="redirect-after-vote admin-select" style="width:100%">
			            <option value="yes" <?php echo esc_attr( $redirect_after_vote_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" <?php echo esc_attr( $redirect_after_vote_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group redirect-url-section <?php echo esc_attr( $redirect_after_vote_class ); ?>">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Redirect Url', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control redirect-url" value="<?php echo esc_attr( $redirect_url ); ?>"/>
				</div>
			</div>
			<div class="form-group redirect-after-section <?php echo esc_attr( $redirect_after_vote_class ); ?>">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Redirect After (in seconds)', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control redirect-after" value="<?php echo esc_attr( $redirect_after ); ?>"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Reset Poll Stats automatically', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'yes' === $poll->meta_data['options']['poll']['resetPollStatsAutomatically'] ) {
			            $reset_poll_stats_automatically_yes = 'selected';
			            $reset_poll_stats_automatically_no = '';
						$reset_poll_stats_automatically_class = '';
			        } else {
			            $reset_poll_stats_automatically_yes = '';
			            $reset_poll_stats_automatically_no = 'selected';
						$reset_poll_stats_automatically_class = 'hide';
			        }
			        ?>
			        <select class="reset-poll-stats-automatically admin-select" style="width:100%">
			            <option value="yes" <?php echo esc_attr( $reset_poll_stats_automatically_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" <?php echo esc_attr( $reset_poll_stats_automatically_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group reset-poll-stats-section <?php echo esc_attr( $reset_poll_stats_automatically_class ); ?>">
				<div class="col-md-3">
					<?php esc_html_e( 'Reset on', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<div class="input-group">
						<input type="text" class="form-control reset-poll-stats-on" value="<?php echo esc_attr( $poll->meta_data['options']['poll']['resetPollStatsOn'] ); ?>" readonly />
						<input type="hidden" class="form-control reset-poll-stats-on-hidden" value="<?php echo esc_attr( $poll->meta_data['options']['poll']['resetPollStatsOn'] ); ?>" />
		                <div class="input-group-addon">
							<span class="dashicons dashicons-calendar-alt show-reset-poll-stats-on"></span>
		                </div>
					</div>
				</div>
			</div>
			<div class="form-group reset-poll-stats-section <?php echo esc_attr( $reset_poll_stats_automatically_class ); ?>">
				<div class="col-md-3">
					<?php esc_html_e( 'Reset every', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control reset-poll-stats-every" value="<?php echo esc_attr( $poll->meta_data['options']['poll']['resetPollStatsEvery'] ); ?>"/>
                    <?php
                    $reset_poll_stats_every_period_hours = '';
                    $reset_poll_stats_every_period_days = '';
                    switch ( $poll->meta_data['options']['poll']['resetPollStatsEveryPeriod'] ) {
                        case 'hours': {
                            $reset_poll_stats_every_period_hours = 'selected';
                            $reset_poll_stats_every_period_days = '';
                            break;
                        }
                        case 'days': {
                            $reset_poll_stats_every_period_hours = '';
                            $reset_poll_stats_every_period_days = 'selected';
                            break;
                        }
                    }
                    ?>
                    <select class="reset-poll-stats-every-period admin-select" style="width:100%">
                        <option value="hours" <?php echo esc_attr( $reset_poll_stats_every_period_hours ); ?>><?php esc_html_e( 'Hours', 'yop-poll' ); ?></option>
                        <option value="days" <?php echo esc_attr( $reset_poll_stats_every_period_days ); ?>><?php esc_html_e( 'Days', 'yop-poll' ); ?></option>
                    </select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Auto Generate Poll Page', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'yes' === $poll->meta_data['options']['poll']['autoGeneratePollPage'] ) {
			            $auto_generate_poll_page_yes = 'selected';
			            $auto_generate_poll_page_no = '';
			        } else {
			            $auto_generate_poll_page_yes = '';
			            $auto_generate_poll_page_no = 'selected';
			        }
			        ?>
			        <select class="auto-generate-poll-page admin-select" style="width:100%">
			            <option value="yes" <?php echo esc_attr( $auto_generate_poll_page_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" <?php echo esc_attr( $auto_generate_poll_page_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Use Captcha', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
					$use_captcha_built_in_yes = '';
					$use_reCaptcha_v2_checkbox_yes = '';
					$use_reCaptcha_v2_invisible_yes = '';
					$use_reCaptcha_v3_yes = '';
					$use_h_captcha = '';
					$use_captcha_no = '';
					switch ( $poll->meta_data['options']['poll']['useCaptcha'] ) {
						case 'yes': {
							$use_captcha_built_in_yes = 'selected';
							break;
						}
						case 'yes-recaptcha': {
							$use_reCaptcha_v2_checkbox_yes = 'selected';
							break;
						}
						case 'yes-recaptcha-invisible': {
							$use_reCaptcha_v2_invisible_yes = 'selected';
							break;
						}
						case 'yes-recaptcha-v3': {
							$use_reCaptcha_v3_yes = 'selected';
							break;
						}
						case 'yes-hcaptcha': {
							$use_h_captcha = 'selected';
							break;
						}
						case 'no': {
							$use_captcha_no = 'selected';
							break;
						}
					}
			        ?>
			        <select class="use-captcha admin-select" style="width:100%">
						<option value="no" <?php echo esc_attr( $use_captcha_no ); ?>>
							<?php esc_html_e( 'No', 'yop-poll' ); ?>
						</option>
						<optgroup label="<?php esc_html_e( 'Yes', 'yop-poll' ); ?>">
							<option value="yes" <?php echo esc_attr( $use_captcha_built_in_yes ); ?>><?php esc_html_e( 'Use built in Captcha', 'yop-poll' ); ?></option>
							<option value="yes-recaptcha"  <?php echo esc_attr( $use_reCaptcha_v2_checkbox_yes ); ?>><?php esc_html_e( 'Use reCaptcha v2 Checkbox', 'yop-poll' ); ?></option>
							<option value="yes-recaptcha-invisible"  <?php echo esc_attr( $use_reCaptcha_v2_invisible_yes ); ?>><?php esc_html_e( 'Use reCaptcha v2 Invisible', 'yop-poll' ); ?></option>
							<option value="yes-recaptcha-v3"  <?php echo esc_attr( $use_reCaptcha_v3_yes ); ?>><?php esc_html_e( 'Use reCaptcha v3', 'yop-poll' ); ?></option>
							<option value="yes-hcaptcha"  <?php echo esc_attr( $use_h_captcha ); ?>><?php esc_html_e( 'Use hCaptcha', 'yop-poll' ); ?></option>
						</optgroup>
			        </select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Location for Notification', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
					$poll_options_notification_message_location_top = 'selected';
					$poll_options_notification_message_location_bottom = '';
					if ( true === isset( $poll->meta_data['options']['poll']['notificationMessageLocation'] ) ) {
						if ( 'bottom' === $poll->meta_data['options']['poll']['notificationMessageLocation'] ) {
							$poll_options_notification_message_location_bottom = 'selected';
						} else {
							$poll_options_notification_message_location_top = 'selected';
						}
					} else {
						$poll_options_notification_message_location_top = 'selected';
						$poll_options_notification_message_location_bottom = '';
					}
					?>
					<select class="poll-options-notification-message-location admin-select" style="width: 100%">
						<option value="top" <?php echo esc_attr( $poll_options_notification_message_location_top ); ?>>
							<?php esc_html_e( 'Top', 'yop-poll' ); ?>
						</option>
						<option value="bottom" <?php echo esc_attr( $poll_options_notification_message_location_bottom ); ?>>
							<?php esc_html_e( 'Bottom', 'yop-poll' ); ?>
						</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h4>
					<?php esc_html_e( 'Notifications', 'yop-poll' ); ?>
				</h4>
			</div>
		</div>
		<div class="form-horizontal poll-notifications">
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Send Email notifications', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'yes' === $poll->meta_data['options']['poll']['sendEmailNotifications'] ) {
			            $send_email_notifications_yes = 'selected';
			            $send_email_notifications_no = '';
			            $send_email_notifications_class = '';
			        } else {
			            $send_email_notifications_yes = '';
			            $send_email_notifications_no = 'selected';
			            $send_email_notifications_class = 'hide';
			        }
			        ?>
			        <select class="send-email-notifications admin-select" style="width:100%">
			            <option value="yes" <?php echo esc_attr( $send_email_notifications_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" <?php echo esc_attr( $send_email_notifications_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group send-email-notifications-section <?php echo esc_attr( $send_email_notifications_class ); ?>">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'From Name', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control email-notifications-from-name" value="<?php echo esc_attr( $poll->meta_data['options']['poll']['emailNotificationsFromName'] ); ?>"/>
				</div>
			</div>
			<div class="form-group send-email-notifications-section <?php echo esc_attr( $send_email_notifications_class ); ?>">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'From Email', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control email-notifications-from-email" value="<?php echo esc_attr( $poll->meta_data['options']['poll']['emailNotificationsFromEmail'] ); ?>"/>
				</div>
			</div>
            <div class="form-group send-email-notifications-section <?php echo esc_attr( $send_email_notifications_class ); ?>">
                <div class="col-md-3 field-caption">
                    <?php esc_html_e( 'Recipients', 'yop-poll' ); ?>
                </div>
                <div class="col-md-9">
                    <?php esc_html_e( 'Use comma separated email addresses: email@xmail.com,email2@ymail.com', 'yop-poll' ); ?>
                    <input class="form-control email-notifications-recipients" value="<?php echo esc_attr( isset( $poll->meta_data['options']['poll']['emailNotificationsRecipients'] ) ? $poll->meta_data['options']['poll']['emailNotificationsRecipients'] : '' ); ?>">
                </div>
            </div>
			<div class="form-group send-email-notifications-section <?php echo esc_attr( $send_email_notifications_class ); ?>">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Subject', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control email-notifications-subject" value="<?php echo esc_attr( $poll->meta_data['options']['poll']['emailNotificationsSubject'] ); ?>"/>
				</div>
			</div>
			<div class="form-group send-email-notifications-section <?php echo esc_attr( $send_email_notifications_class ); ?>">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Message', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<textarea class="form-control email-notifications-message"><?php echo esc_textarea( $poll->meta_data['options']['poll']['emailNotificationsMessage'] ); ?></textarea>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<h4>
					<?php esc_html_e( 'Compliance', 'yop-poll' ); ?>
				</h4>
			</div>
		</div>
		<div class="form-horizontal poll-compliance">
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Enable GDPR', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
			        if ( 'yes' === $poll->meta_data['options']['poll']['enableGdpr'] ) {
			            $enable_gdpr_yes = 'selected';
			            $enable_gdpr_no = '';
						$enable_gdpr_class = '';
						if ( 'consent' === $poll->meta_data['options']['poll']['gdprSolution'] ) {
							$enable_gdpr_consent_class = '';
						} else {
							$enable_gdpr_consent_class = 'hide';
						}
			        } else {
						$enable_gdpr_yes = '';
						$enable_gdpr_no = 'selected';
			            $enable_gdpr_class = 'hide';
						$enable_gdpr_consent_class = 'hide';
			        }
			        ?>
					<select class="enable-gdpr admin-select" style="width:100%">
			            <option value="yes" <?php echo esc_attr( $enable_gdpr_yes ); ?>><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no"  <?php echo esc_attr( $enable_gdpr_no ); ?>><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group gdpr-solution-section <?php echo esc_attr( $enable_gdpr_class ); ?>">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Solution', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<?php
					$gdpr_solution_consent = '';
					$gdpr_solution_anonymize = '';
					$gdpr_solution_nostore = '';
					switch ( $poll->meta_data['options']['poll']['gdprSolution'] ) {
						case 'consent': {
							$gdpr_solution_consent = 'selected';
							break;
						}
						case 'anonymize': {
							$gdpr_solution_anonymize = 'selected';
							break;
						}
						case 'nostore': {
							$gdpr_solution_nostore = 'selected';
							break;
						}
						default: {
							$gdpr_solution_consent = 'selected';
							break;
						}
					}
					?>
					<select class="gdpr-solution admin-select" style="width:100%">
			            <option value="consent" <?php echo esc_attr( $gdpr_solution_consent ); ?>><?php esc_html_e( 'Ask for consent ( Ip Addresses will be stored and cookies will be enabled )', 'yop-poll' ); ?></option>
			            <option value="anonymize" <?php echo esc_attr( $gdpr_solution_anonymize ); ?>><?php esc_html_e( 'Anonymize Ip Addresses ( Cookies will be disabled ) ', 'yop-poll' ); ?></option>
						<option value="nostore" <?php echo esc_attr( $gdpr_solution_nostore ); ?>><?php esc_html_e( 'Do not store Ip Addresses ( Cookies will be disabled ) ', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group gdpr-consent-section <?php echo esc_attr( $enable_gdpr_consent_class ); ?>">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Text for consent checkbox', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<textarea class="form-control gdpr-consent-text"><?php echo esc_textarea( $poll->meta_data['options']['poll']['gdprConsentText'] ); ?></textarea>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		&nbsp;
	</div>
</div>
