<div class="row">
	<div class="col-md-12">
		&nbsp;
	</div>
</div>
<div class="row">
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
					<input type="text" class="form-control vote-button-label" value="<?php esc_html_e( 'Vote', 'yop-poll' ); ?>"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Show [Results] Link', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<select class="show-results-link admin-select" style="width:100%">
			            <option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group results-link-option hide">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( '[Results] Link Label', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control results-label-text" value="<?php esc_html_e( 'Results', 'yop-poll' ); ?>"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Show Total Votes', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<select class="show-total-votes admin-select" style="width:100%">
			            <option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Show Total Answers', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<select class="show-total-answers admin-select" style="width:100%">
			            <option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
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
					<select class="load-with-ajax admin-select" style="width:100%">
						<option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
						<option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Start Date', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<select class="start-date-option admin-select" style="width:100%">
			            <option value="now" selected><?php esc_html_e( 'Now', 'yop-poll' ); ?></option>
			            <option value="custom"><?php esc_html_e( 'Custom Date', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group start-date-section hide">
				<div class="col-md-3">
				</div>
				<div class="col-md-9">
					<div class="input-group">
						<input type="text" class="form-control start-date-custom" readonly />
						<input type="hidden" class="form-control start-date-custom-hidden" />
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
					<select class="end-date-option admin-select" style="width:100%">
			            <option value="never" selected><?php esc_html_e( 'Never', 'yop-poll' ); ?></option>
			            <option value="custom"><?php esc_html_e( 'Custom Date', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group end-date-section hide">
				<div class="col-md-3">
				</div>
				<div class="col-md-9">
					<div class="input-group">
						<input type="text" class="form-control end-date-custom" readonly />
						<input type="hidden" class="form-control end-date-custom-hidden" />
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
					<select class="redirect-after-vote admin-select" style="width:100%" autocomplete="off">
			            <option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group redirect-url-section hide">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Redirect Url', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control redirect-url"/>
				</div>
			</div>
			<div class="form-group redirect-after-section hide">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Redirect After (in seconds)', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control redirect-after" value="2"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Reset Poll Stats automatically', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<select class="reset-poll-stats-automatically admin-select" style="width:100%">
			            <option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group reset-poll-stats-section hide">
				<div class="col-md-3">
					<?php esc_html_e( 'Reset on', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<div class="input-group">
						<input type="text" class="form-control reset-poll-stats-on" readonly />
						<input type="hidden" class="form-control reset-poll-stats-on-hidden" />
		                <div class="input-group-addon">
							<span class="dashicons dashicons-calendar-alt show-reset-poll-stats-on"></span>
		                </div>
					</div>
				</div>
			</div>
			<div class="form-group reset-poll-stats-section hide">
				<div class="col-md-3">
					<?php esc_html_e( 'Reset every', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control reset-poll-stats-every" value="24"/>
					<select class="reset-poll-stats-every-period admin-select" style="width:100%">
                        <option value="hours" selected><?php esc_html_e( 'Hours', 'yop-poll' ); ?></option>
                        <option value="days"><?php esc_html_e( 'Days', 'yop-poll' ); ?></option>
                    </select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Auto Generate Poll Page', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<select class="auto-generate-poll-page admin-select" style="width:100%">
			            <option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Use Captcha', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<select class="use-captcha admin-select" style="width:100%">
						<option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			            <optgroup label="<?php esc_html_e( 'Yes', 'yop-poll' ); ?>">
							<option value="yes"><?php esc_html_e( 'Use built in Captcha', 'yop-poll' ); ?></option>
							<option value="yes-recaptcha"><?php esc_html_e( 'Use reCaptcha v2 Checkbox', 'yop-poll' ); ?></option>
							<option value="yes-recaptcha-invisible"><?php esc_html_e( 'Use reCaptcha v2 Invisible', 'yop-poll' ); ?></option>
							<option value="yes-recaptcha-v3"><?php esc_html_e( 'Use reCaptcha v3', 'yop-poll' ); ?></option>
							<option value="yes-hcaptcha"><?php esc_html_e( 'Use hCaptcha', 'yop-poll' ); ?></option>
						</optgroup>
			        </select>
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-3">
					<?php esc_html_e( 'Location for Notification', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<select class="poll-options-notification-message-location admin-select" style="width: 100%">
						<option value="top" selected>
							<?php esc_html_e( 'Top', 'yop-poll' ); ?>
						</option>
						<option value="bottom">
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
					<select class="send-email-notifications admin-select" style="width:100%">
			            <option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group send-email-notifications-section hide">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'From Name', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control email-notifications-from-name" value="<?php echo esc_attr( $notifications['new-vote']['from-name'] ); ?>"/>
				</div>
			</div>
			<div class="form-group send-email-notifications-section hide">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'From Email', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control email-notifications-from-email" value="<?php echo esc_attr( $notifications['new-vote']['from-email'] ); ?>"/>
				</div>
			</div>
            <div class="form-group send-email-notifications-section hide">
                <div class="col-md-3 field-caption">
                    <?php esc_html_e( 'Recipients', 'yop-poll' ); ?>
                </div>
                <div class="col-md-9">
                    <?php esc_html_e( 'Use comma separated email addresses: email@xmail.com,email2@ymail.com', 'yop-poll' ); ?>
                    <input class="form-control email-notifications-recipients" value="<?php echo esc_attr( $notifications['new-vote']['recipients'] ); ?>">
                </div>
            </div>
			<div class="form-group send-email-notifications-section hide">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Subject', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<input type="text" class="form-control email-notifications-subject" value="<?php echo esc_attr( $notifications['new-vote']['subject'] ); ?>"/>
				</div>
			</div>
			<div class="form-group send-email-notifications-section hide">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Message', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<textarea class="form-control email-notifications-message"><?php echo esc_textarea( $notifications['new-vote']['message'] ); ?></textarea>
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
					<select class="enable-gdpr admin-select" style="width:100%">
			            <option value="yes"><?php esc_html_e( 'Yes', 'yop-poll' ); ?></option>
			            <option value="no" selected><?php esc_html_e( 'No', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group gdpr-solution-section hide">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Solution', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<select class="gdpr-solution admin-select" style="width:100%">
			            <option value="consent"><?php esc_html_e( 'Ask for consent ( Ip Addresses will be stored and cookies will be enabled )', 'yop-poll' ); ?></option>
			            <option value="anonymize"><?php esc_html_e( 'Anonymize Ip Addresses ( Cookies will be disabled ) ', 'yop-poll' ); ?></option>
						<option value="nostore"><?php esc_html_e( 'Do not store Ip Addresses ( Cookies will be disabled ) ', 'yop-poll' ); ?></option>
			        </select>
				</div>
			</div>
			<div class="form-group gdpr-consent-section hide">
				<div class="col-md-3 field-caption">
					<?php esc_html_e( 'Text for consent checkbox', 'yop-poll' ); ?>
				</div>
				<div class="col-md-9">
					<textarea class="form-control gdpr-consent-text"></textarea>
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
