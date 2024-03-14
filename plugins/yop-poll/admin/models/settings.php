<?php
class YOP_Poll_Settings {
	private static $errors_present = false,
		$error_text,
		$settings;
	public static function create_settings() {
		$settings = array(
			'general' => array(
				'i-date' => current_time( 'mysql' ),
				'show-guide' => 'yes',
				'remove-data' => 'no',
				'use-custom-headers-for-ip' => 'no',
			),
			'notifications' => array(
				'new-vote' => array(
					'from-name'  => 'Your Name Here',
					'from-email' => 'Your Email Address Here',
					'recipients' => '',
					'subject'    => 'New vote for %POLL-NAME% on %VOTE-DATE%',
					'message'    => 'There is a new vote for %POLL-NAME%
									Here are the details

									[QUESTION]
									Question - %QUESTION-TEXT%
									Answer - %ANSWER-VALUE%
									[/QUESTION]

									[CUSTOM_FIELDS]
									%CUSTOM_FIELD_NAME% - %CUSTOM_FIELD_VALUE%
									[/CUSTOM_FIELDS]
								',
				),
				'automatically-reset-votes' => array(
					'from-name'  => 'Your Name Here',
					'from-email' => 'Your Email Address Here',
					'recipients' => '',
					'subject'    => 'Stats for %POLL-NAME% on %RESET-DATE%',
					'message'    => 'Poll - %POLL-NAME%
									Reset Date - %RESET-DATE%
									
									[RESULTS]
									%QUESTION-TEXT%
									[ANSWERS]
									%ANSWER-TEXT% - %ANSWER-VOTES% votes - %ANSWER-PERCENTAGES%
									[/ANSWERS]
									
									[OTHER-ANSWERS]
									%ANSWER-TEXT% - %ANSWER-VOTES% votes
									[/OTHER-ANSWERS]
									[/RESULTS]',
				),
			),
			'integrations' => array(
				'reCaptcha' => array(
					'enabled' => 'no',
					'site-key' => '',
					'secret-key' => '',
				),
				'reCaptchaV2Invisible' => array(
					'enabled' => 'no',
					'site-key' => '',
					'secret-key' => '',
				),
				'reCaptchaV3' => array(
					'enabled' => 'no',
					'site-key' => '',
					'secret-key' => '',
					'min-allowed-score' => '',
				),
				'facebook' => array(
					'enabled' => 'no',
					'app-id'      => '',
				),
				'google'   => array(
					'enabled' => 'no',
					'app-id'      => '',
					'app-secret'  => '',
				),
				'hCaptcha' => array(
					'enabled' => 'no',
					'site-key' => '',
					'secret-key' => '',
				),
			),
			'messages' => array(
				'captcha' => array(
					'accessibility-alt' => 'Sound icon',
					'accessibility-title' => 'Accessibility option: listen to a question and answer it!',
					'accessibility-description' => 'Type below the [STRONG]answer[/STRONG] to what you hear. Numbers or words:',
					'explanation' => 'Click or touch the [STRONG]ANSWER[/STRONG]',
					'refresh-alt' => 'Refresh/reload icon',
					'refresh-title' => 'Refresh/reload: get new images and accessibility option!',
				),
				'buttons' => array(
					'anonymous' => 'Anonymous Vote',
					'wordpress' => 'Sign in with WordPress',
					'facebook' => 'Sign in with Facebook',
					'google' => 'Sign in with Google',
				),
				'voting' => array(
					'poll-ended' => 'This poll is no longer accepting votes',
					'poll-not-started' => 'This poll is not accepting votes yet',
					'already-voted-on-poll' => 'Thank you for your vote',
					'invalid-poll' => 'Invalid Poll',
					'no-answers-selected' => 'No answer selected',
					'min-answers-required' => 'At least {min_answers_allowed} answer(s) required',
					'max-answers-required' => 'A max of {max_answers_allowed} answer(s) accepted',
					'no-answer-for-other' => 'No other answer entered',
					'no-value-for-custom-field' => '{custom_field_name} is required',
					'consent-not-checked' => 'You must agree to our terms and conditions',
					'no-captcha-selected' => 'Captcha is required',
					'not-allowed-by-ban' => 'Vote not allowed',
					'not-allowed-by-block' => 'Vote not allowed',
					'not-allowed-by-limit' => 'Vote not allowed',
					'thank-you' => 'Thank you for your vote',
				),
				'results' => array(
					'single-vote' => 'vote',
					'multiple-votes' => 'votes',
					'single-answer' => 'answer',
					'multiple-answers' => 'answers',
				),
			),
		);
		return serialize( $settings );
	}
    public static function import_settings_from_5x( $old_settings ) {
        $new_settings = array(
            'general' => array(
                'i-date' => current_time( 'mysql' ),
				'show-guide' => 'yes',
				'remove-data' => 'no',
				'use-custom-headers-for-ip' => 'no',
            ),
            'email'        => array(
                'from-name'  => isset( $old_settings['email_notifications_from_name'] ) ? sanitize_text_field( $old_settings['email_notifications_from_name'] ) : 'Your Name Here',
                'from-email' => isset( $old_settings['email_notifications_from_email'] ) ? sanitize_text_field( $old_settings['email_notifications_from_email'] ) : 'Your Email Address Here',
                'recipients' => isset( $old_settings['email_notifications_recipients'] ) ? sanitize_text_field( $old_settings['email_notifications_recipients'] ) : '',
                'subject'    => isset( $old_settings['email_notifications_subject'] ) ? sanitize_text_field( $old_settings['email_notifications_subject'] ) : 'Your Subject Here',
                'message'    => isset( $old_settings['email_notifications_body'] ) ? sanitize_text_field( $old_settings['email_notifications_body'] ) : 'Your Message Here',
            ),
            'integrations' => array(
                'reCaptcha' => array(
                    'enabled' => 'no',
                    'site-key' => '',
                    'secret-key' => '',
                ),
                'reCaptchaV2Invisible' => array(
					'enabled' => 'no',
					'site-key' => '',
					'secret-key' => '',
                ),
                'reCaptchaV3' => array(
					'enabled' => 'no',
					'site-key' => '',
                    'secret-key' => '',
                    'min-allowed-score' => '',
				),
                'facebook' => array(
                    'enabled'  => isset( $old_settings['facebook_integration'] ) ? sanitize_text_field( $old_settings['facebook_integration'] ) : 'no',
                    'app-id'     => isset( $old_settings['facebook_appID'] ) ? sanitize_text_field( $old_settings['facebook_appID'] ) : '',
                ),
                'google'   => array(
                    'enabled' => isset( $old_settings['google_integration'] ) ? sanitize_text_field( $old_settings['google_integration'] ) : 'no',
                    'app-id'      => isset( $old_settings['google_appID'] ) ? sanitize_text_field( $old_settings['google_appID'] ) : '',
                    'app-secret'  => isset( $old_settings['google_appSecret'] ) ? sanitize_text_field( $old_settings['google_appSecret'] ) : '',
				),
            ),
            'messages' => array(
                'captcha' => array(
                    'accessibility-alt' => 'Sound icon',
                    'accessibility-title' => 'Accessibility option: listen to a question and answer it!',
                    'accessibility-description' => 'Type below the [STRONG]answer[/STRONG] to what you hear. Numbers or words:',
                    'explanation' => 'Click or touch the [STRONG]ANSWER[/STRONG]',
                    'refresh-alt' => 'Refresh/reload icon',
                    'refresh-title' => 'Refresh/reload: get new images and accessibility option!',
                ),
                'buttons' => array(
                    'anonymous' => 'Anonymous Vote',
                    'wordpress' => 'Sign in with WordPress',
                    'facebook' => 'Sign in with Facebook',
                    'google' => 'Sign in with Google',
                ),
                'voting' => array(
                    'poll-ended' => 'This poll is no longer accepting votes',
                    'poll-not-started' => 'This poll is not accepting votes yet',
                    'already-voted-on-poll' => 'Thank you for your vote',
                    'invalid-poll' => 'Invalid Poll',
                    'no-answers-selected' => 'No answer selected',
                    'min-answers-required' => 'At least {min_answers_allowed} answer(s) required',
                    'max-answers-required' => 'A max of {max_answers_allowed} answer(s) accepted',
                    'no-answer-for-other' => 'No other answer entered',
                    'no-value-for-custom-field' => '{custom_field_name} is required',
                    'consent-not-checked' => 'You must agree to our terms and conditions',
                    'no-captcha-selected' => 'Captcha is required',
                    'not-allowed-by-ban' => 'Vote not allowed',
                    'not-allowed-by-block' => 'Vote not allowed',
                    'not-allowed-by-limit' => 'Vote not allowed',
                    'thank-you' => 'Thank you for your vote',
                ),
                'results' => array(
                    'single-vote' => 'vote',
                    'multiple-votes' => 'votes',
                    'single-answer' => 'answer',
                    'multiple-answers' => 'answers',
				),
            ),
        );
        return serialize( $new_settings );
    }
    public static function update_settings_to_version_6_0_4() {
        $current_settings = self::get_all_settings();
        $current_settings_decoded = unserialize( $current_settings );
        $captcha_enabled = 'no';
        if ( true === isset( $current_settings_decoded['integrations']['reCaptcha']['enabled'] ) ) {
            $captcha_enabled = $current_settings_decoded['integrations']['reCaptcha']['enabled'];
        } else {
            if ( true === isset( $current_settings_decoded['integrations']['reCaptcha']['integration'] ) ) {
                $captcha_enabled = $current_settings_decoded['integrations']['reCaptcha']['integration'];
            }
        }
        $new_settings = array(
            'general' => array(
                'i-date' => isset( $current_settings_decoded['general']['idate'] ) ? sanitize_text_field( $current_settings_decoded['general']['idate'] ) : current_time( 'mysql' ),
            ),
            'email'        => array(
                'from-name'  => isset( $current_settings_decoded['email']['from_name'] ) ? sanitize_text_field( $current_settings_decoded['email']['from_name'] ) : '',
                'from-email' => isset( $current_settings_decoded['email']['from_email'] ) ? sanitize_text_field( $current_settings_decoded['email']['from_email'] ) : '',
                'recipients' => isset( $current_settings_decoded['email']['recipients'] ) ? sanitize_text_field( $current_settings_decoded['email']['recipients'] ) : '',
                'subject'    => isset( $current_settings_decoded['email']['subject'] ) ? sanitize_text_field( $current_settings_decoded['email']['subject'] ) : '',
                'message'    => isset( $current_settings_decoded['email']['message'] ) ? sanitize_text_field( $current_settings_decoded['email']['message'] ) : '',
            ),
            'integrations' => array(
                'reCaptcha' => array(
                    'enabled' => sanitize_text_field( $captcha_enabled ),
                    'site-key' => isset( $current_settings_decoded['integrations']['reCaptcha']['site_key'] ) ? sanitize_text_field( $current_settings_decoded['integrations']['reCaptcha']['site_key'] ) : '',
                    'secret-key' => isset( $current_settings_decoded['integrations']['reCaptcha']['secret_key'] ) ? sanitize_text_field( $current_settings_decoded['integrations']['reCaptcha']['secret_key'] ) : '',
                ),
                'facebook' => array(
                    'enabled' => isset( $current_settings_decoded['integrations']['facebook']['integration'] ) ? sanitize_text_field( $current_settings_decoded['integrations']['facebook']['integration'] ) : 'no',
                    'app-id' => isset( $current_settings_decoded['integrations']['facebook']['app_id'] ) ? sanitize_text_field( $current_settings_decoded['integrations']['facebook']['app_id'] ) : '',
                ),
                'google'   => array(
                    'enabled' => isset( $current_settings_decoded['integrations']['google']['integration'] ) ? sanitize_text_field( $current_settings_decoded['integrations']['google']['integration'] ) : 'no',
                    'app-id' => isset( $current_settings_decoded['integrations']['google']['app_id'] ) ? sanitize_text_field( $current_settings_decoded['integrations']['google']['app_id'] ) : '',
                    'app-secret' => isset( $current_settings_decoded['integrations']['google']['app_secret'] ) ? sanitize_text_field( $current_settings_decoded['integrations']['google']['app_secret'] ) : '',
				),
            ),
            'messages' => array(
                'captcha' => array(
                    'accessibility-alt' => 'Sound icon',
                    'accessibility-title' => 'Accessibility option: listen to a question and answer it!',
                    'accessibility-description' => 'Type below the [STRONG]answer[/STRONG] to what you hear. Numbers or words:',
                    'explanation' => 'Click or touch the [STRONG]ANSWER[/STRONG]',
                    'refresh-alt' => 'Refresh/reload icon',
                    'refresh-title' => 'Refresh/reload: get new images and accessibility option!',
                ),
                'buttons' => array(
                    'anonymous' => 'Anonymous Vote',
                    'wordpress' => 'Sign in with WordPress',
                    'facebook' => 'Sign in with Facebook',
                    'google' => 'Sign in with Google',
                ),
                'voting' => array(
                    'poll-ended' => 'This poll is no longer accepting votes',
                    'poll-not-started' => 'This poll is not accepting votes yet',
                    'already-voted-on-poll' => 'Thank you for your vote',
                    'invalid-poll' => 'Invalid Poll',
                    'no-answers-selected' => 'No answer selected',
                    'min-answers-required' => 'At least {min_answers_allowed} answer(s) required',
                    'max-answers-required' => 'A max of {max_answers_allowed} answer(s) accepted',
                    'no-answer-for-other' => 'No other answer entered',
                    'no-value-for-custom-field' => '{custom_field_name} is required',
                    'consent-not-checked' => 'You must agree to our terms and conditions',
                    'no-captcha-selected' => 'Captcha is required',
                    'not-allowed-by-ban' => 'Vote not allowed',
                    'not-allowed-by-block' => 'Vote not allowed',
                    'not-allowed-by-limit' => 'Vote not allowed',
                    'thank-you' => 'Thank you for your vote',
                ),
                'results' => array(
                    'single-vote' => 'vote',
                    'multiple-votes' => 'votes',
                    'single-answer' => 'answer',
                    'multiple-answers' => 'answers',
				),
            ),
        );
        update_option( 'yop_poll_settings', serialize( $new_settings ) );
    }
    public static function update_settings_to_version_6_1_7() {
        $current_settings = unserialize( self::get_all_settings() );
        $current_settings['general']['remove-data'] = 'no';
        $current_settings['notifications'] = array(
            'new-vote' => array(
                'from-name'  => isset( $current_settings['email']['from-name'] ) ? sanitize_text_field( $current_settings['email']['from-name'] ) : 'Your Name Here',
                'from-email' => isset( $current_settings['email']['from-email'] ) ? sanitize_text_field( $current_settings['email']['from-email'] ) : 'Your Email Address Here',
                'recipients' => isset( $current_settings['email']['recipients'] ) ? sanitize_text_field( $current_settings['email']['recipients'] ) : '',
                'subject'    => isset( $current_settings['email']['subject'] ) ? sanitize_text_field( $current_settings['email']['subject'] ) : 'New vote for %POLL-NAME% on %VOTE-DATE%',
                'message'    => isset( $current_settings['email']['message'] ) ? wp_kses(
                    $current_settings['email']['message'],
                    array(
                        'br' => array(),
                    )
                ) : 'There is a new vote for %POLL-NAME%
                Here are the details

                [QUESTION]
                Question - %QUESTION-TEXT%
                Answer - %ANSWER-VALUE%
                [/QUESTION]

                [CUSTOM_FIELDS]
                %CUSTOM_FIELD_NAME% - %CUSTOM_FIELD_VALUE%
                [/CUSTOM_FIELDS]',
            ),
            'automatically-reset-votes' => array(
                'from-name'  => 'Your Name Here',
                'from-email' => 'Your Email Address Here',
                'recipients' => '',
                'subject'    => 'Stats for %POLL-NAME% on %RESET-DATE%',
                'message'    => 'Poll - %POLL-NAME%
                                Reset Date - %RESET-DATE%
                                
                                [RESULTS]
                                %QUESTION-TEXT%
                                [ANSWERS]
                                %ANSWER-TEXT% - %ANSWER-VOTES% votes - %ANSWER-PERCENTAGES%
                                [/ANSWERS]
                                
                                [OTHER-ANSWERS]
                                %ANSWER-TEXT% - %ANSWER-VOTES% votes
                                [/OTHER-ANSWERS]
                                [/RESULTS]',
			),
        );
        unset( $current_settings['email'] );
        update_option( 'yop_poll_settings', serialize( $current_settings ) );
    }
    public static function update_settings_to_version_6_2_0() {
        $current_settings = unserialize( self::get_all_settings() );
        $current_settings['integrations']['reCaptchaV3']['enabled'] = 'no';
        $current_settings['integrations']['reCaptchaV3']['site-key'] = '';
        $current_settings['integrations']['reCaptchaV3']['secret-key'] = '';
        $current_settings['integrations']['reCaptchaV3']['min-allowed-score'] = '';
        update_option( 'yop_poll_settings', serialize( $current_settings ) );
    }
	public static function update_settings_to_version_6_4_3() {
		$current_settings = unserialize( self::get_all_settings() );
		$current_settings['general']['use-custom-headers-for-ip'] = 'no';
		update_option( 'yop_poll_settings', serialize( $current_settings ) );
	}
    public static function get_all_settings() {
        if ( ( false === isset( self::$settings ) ) || ( '' === self::$settings ) ) {
            self::$settings = get_option( 'yop_poll_settings' );
        }
        return self::$settings;
    }
    public static function get_install_date() {
        $install_date = '';
        $settings = self::get_all_settings();
        if ( '' !== $settings ) {
            $unserialized_settings = unserialize( $settings );
            $install_date = $unserialized_settings['general']['i-date'];
        }
        return $install_date;
    }
    public static function get_show_guide() {
        $show_guide = '';
        $settings = self::get_all_settings();
        if ( '' !== $settings ) {
            $unserialized_settings = unserialize( $settings );
            if ( isset( $unserialized_settings['general']['show-guide'] ) ) {
                $show_guide = $unserialized_settings['general']['show-guide'];
            } else {
                $show_guide = 'yes';
            }
        }
        return $show_guide;
    }
    public static function update_show_guide( $show_guide ) {
        $settings = self::get_all_settings();
        if ( '' !== $settings ) {
            $unserialized_settings = unserialize( $settings );
            $unserialized_settings['general']['show-guide'] = $show_guide;
            $serialized_settings = serialize( $unserialized_settings );
            update_option( 'yop_poll_settings', $serialized_settings );
            self::$settings = $serialized_settings;
        }
    }
    public static function get_notifications() {
		$email_settings = array();
		$settings = self::get_all_settings();
		if ( '' !== $settings ) {
			$unserialized_settings = unserialize( $settings );
			$email_settings = isset( $unserialized_settings['notifications'] ) ? $unserialized_settings['notifications'] : array();
		}
		return $email_settings;
	}
    public static function get_integrations() {
        $integrations_settings = array();
        $settings = self::get_all_settings();
        if ( '' !== $settings ) {
            $unserialized_settings = unserialize( $settings );
            $integrations_settings = array(
                'reCaptcha' => array(
                    'enabled' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['reCaptcha'] ) && isset( $unserialized_settings['integrations']['reCaptcha']['enabled'] ) ) ? $unserialized_settings['integrations']['reCaptcha']['enabled'] : '',
                    'site-key' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['reCaptcha'] ) && isset( $unserialized_settings['integrations']['reCaptcha']['site-key'] ) ) ? $unserialized_settings['integrations']['reCaptcha']['site-key'] : '',
                    'secret-key' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['reCaptcha'] ) && isset( $unserialized_settings['integrations']['reCaptcha']['secret-key'] ) ) ? $unserialized_settings['integrations']['reCaptcha']['secret-key'] : '',
                ),
                'reCaptchaV2Invisible' => array(
					'enabled' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['reCaptchaV2Invisible'] ) && isset( $unserialized_settings['integrations']['reCaptchaV2Invisible']['enabled'] ) ) ? $unserialized_settings['integrations']['reCaptchaV2Invisible']['enabled'] : '',
	                'site-key' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['reCaptchaV2Invisible'] ) && isset( $unserialized_settings['integrations']['reCaptchaV2Invisible']['site-key'] ) ) ? $unserialized_settings['integrations']['reCaptchaV2Invisible']['site-key'] : '',
					'secret-key' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['reCaptchaV2Invisible'] ) && isset( $unserialized_settings['integrations']['reCaptchaV2Invisible']['secret-key'] ) ) ? $unserialized_settings['integrations']['reCaptchaV2Invisible']['secret-key'] : '',
                ),
                'reCaptchaV3' => array(
					'enabled' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['reCaptchaV3'] ) && isset( $unserialized_settings['integrations']['reCaptchaV3']['enabled'] ) ) ? $unserialized_settings['integrations']['reCaptchaV3']['enabled'] : '',
	                'site-key' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['reCaptchaV3'] ) && isset( $unserialized_settings['integrations']['reCaptchaV3']['site-key'] ) ) ? $unserialized_settings['integrations']['reCaptchaV3']['site-key'] : '',
                    'secret-key' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['reCaptchaV3'] ) && isset( $unserialized_settings['integrations']['reCaptchaV3']['secret-key'] ) ) ? $unserialized_settings['integrations']['reCaptchaV3']['secret-key'] : '',
                    'min-allowed-score' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['reCaptchaV3'] ) && isset( $unserialized_settings['integrations']['reCaptchaV3']['min-allowed-score'] ) ) ? $unserialized_settings['integrations']['reCaptchaV3']['min-allowed-score'] : '',
				),
                'hCaptcha' => array(
                    'enabled' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['hCaptcha'] ) && isset( $unserialized_settings['integrations']['hCaptcha']['enabled'] ) ) ? $unserialized_settings['integrations']['hCaptcha']['enabled'] : '',
	                'site-key' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['hCaptcha'] ) && isset( $unserialized_settings['integrations']['hCaptcha']['site-key'] ) ) ? $unserialized_settings['integrations']['hCaptcha']['site-key'] : '',
					'secret-key' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['hCaptcha'] ) && isset( $unserialized_settings['integrations']['hCaptcha']['secret-key'] ) ) ? $unserialized_settings['integrations']['hCaptcha']['secret-key'] : '',
                ),
                'facebook' => array(
                    'enabled' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['facebook'] ) && isset( $unserialized_settings['integrations']['facebook']['enabled'] ) ) ? $unserialized_settings['integrations']['facebook']['enabled'] : '',
                    'app-id' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['facebook'] ) && isset( $unserialized_settings['integrations']['facebook']['app-id'] ) ) ? $unserialized_settings['integrations']['facebook']['app-id'] : '',
                ),
                'google' => array(
                    'enabled' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['google'] ) && isset( $unserialized_settings['integrations']['google']['enabled'] ) ) ? $unserialized_settings['integrations']['google']['enabled'] : '',
                    'app-id' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['google'] ) && isset( $unserialized_settings['integrations']['google']['app-id'] ) ) ? $unserialized_settings['integrations']['google']['app-id'] : '',
                    'app-secret' => ( isset( $unserialized_settings['integrations'] ) && isset( $unserialized_settings['integrations']['google'] ) && isset( $unserialized_settings['integrations']['google']['app-secret'] ) ) ? $unserialized_settings['integrations']['google']['app-secret'] : '',
				),
            );
        }
        return $integrations_settings;
    }
    public static function get_remove_data() {
		$remove_data = 'no';
		$settings = self::get_all_settings();
		if ( '' !== $settings ) {
			$unserialized_settings = unserialize( $settings );
			$remove_data = $unserialized_settings['general']['remove-data'];
		}
		return $remove_data;
	}
    public static function get_messages() {
        $messages = array();
        $settings = self::get_all_settings();
        if ( '' !== $settings ) {
            $unserialized_settings = unserialize( $settings );
            $messages = $unserialized_settings['messages'];
        }
        return $messages;
    }
    public static function validate_data( $settings ) {
        if ( false === is_object( $settings ) ) {
            self::$errors_present = true;
            self::$error_text = esc_html__( 'Invalid data', 'yop-poll' );
        } else {
            /*
            if (
                ( false === self::$errors_present ) &&
                ( !isset( $settings->email->{'from-name'} ) ||
                    ( '' === trim( $settings->email->{'from-name'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "From Name" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( !isset( $settings->email->{'from-email'} ) ||
                    ( '' === trim( $settings->email->{'from-email'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "From Email" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( !isset( $settings->email->{'recipients'} ) ||
                    ( '' === trim( $settings->email->{'recipients'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Recipients" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( !isset( $settings->email->{'subject'} ) ||
                    ( '' === trim( $settings->email->{'subject'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Subject" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( !isset( $settings->email->{'message'} ) ||
                    ( '' === trim( $settings->email->{'message'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Message" is invalid', 'yop-poll' );
            }
            */
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->integrations->reCaptcha->{'enabled'} ) ||
                    ( '' === sanitize_text_field( $settings->integrations->reCaptcha->{'enabled'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Use Google reCaptcha" is invalid', 'yop-poll' );
            }
            if ( 'yes' === $settings->integrations->reCaptcha->{'enabled'} ) {
                if ( ( false === isset( $settings->integrations->reCaptcha->{'site-key'} ) ) || ( '' === sanitize_text_field( $settings->integrations->reCaptcha->{'site-key'} ) ) ) {
                    self::$errors_present = true;
                    self::$error_text = esc_html__( 'Data for "Site Key" is invalid', 'yop-poll' );
                }
                if ( ( false === isset( $settings->integrations->reCaptcha->{'secret-key'} ) ) || ( '' === sanitize_text_field( $settings->integrations->reCaptcha->{'secret-key'} ) ) ) {
                    self::$errors_present = true;
                    self::$error_text = esc_html__( 'Data for "Secret Key" is invalid', 'yop-poll' );
                }
            }
            if ( 'yes' === $settings->integrations->reCaptchaV2Invisible->{'enabled'} ) {
				if ( ( false === isset( $settings->integrations->reCaptchaV2Invisible->{'site-key'} ) ) || ( '' === sanitize_text_field( $settings->integrations->reCaptchaV2Invisible->{'site-key'} ) ) ) {
					self::$errors_present = true;
					self::$error_text = esc_html__( 'Data for "Site Key" is invalid', 'yop-poll' );
				}
				if ( ( false === isset( $settings->integrations->reCaptchaV2Invisible->{'secret-key'} ) ) || ( '' === sanitize_text_field( $settings->integrations->reCaptchaV2Invisible->{'secret-key'} ) ) ) {
					self::$errors_present = true;
					self::$error_text = esc_html__( 'Data for "Secret Key" is invalid', 'yop-poll' );
                }
            }
            if ( 'yes' === $settings->integrations->reCaptchaV3->{'enabled'} ) {
				if ( ( false === isset( $settings->integrations->reCaptchaV3->{'site-key'} ) ) || ( '' === sanitize_text_field( $settings->integrations->reCaptchaV3->{'site-key'} ) ) ) {
					self::$errors_present = true;
					self::$error_text = esc_html__( 'Data for "Site Key" is invalid', 'yop-poll' );
				}
				if ( ( false === isset( $settings->integrations->reCaptchaV3->{'secret-key'} ) ) || ( '' === sanitize_text_field( $settings->integrations->reCaptchaV3->{'secret-key'} ) ) ) {
					self::$errors_present = true;
					self::$error_text = esc_html__( 'Data for "Secret Key" is invalid', 'yop-poll' );
                }
                if ( ( false === isset( $settings->integrations->reCaptchaV3->{'min-allowed-score'} ) ) || ( '' === sanitize_text_field( $settings->integrations->reCaptchaV3->{'min-allowed-score'} ) ) ) {
					self::$errors_present = true;
					self::$error_text = esc_html__( 'Data for "Min Allowed Score" is invalid', 'yop-poll' );
				}
			}
            if ( 'yes' === $settings->integrations->hCaptcha->{'enabled'} ) {
				if ( ( false === isset( $settings->integrations->hCaptcha->{'site-key'} ) ) || ( '' === sanitize_text_field( $settings->integrations->hCaptcha->{'site-key'} ) ) ) {
					self::$errors_present = true;
					self::$error_text = esc_html__( 'Data for "Site Key" is invalid', 'yop-poll' );
				}
				if ( ( false === isset( $settings->integrations->hCaptcha->{'secret-key'} ) ) || ( '' === sanitize_text_field( $settings->integrations->hCaptcha->{'secret-key'} ) ) ) {
					self::$errors_present = true;
					self::$error_text = esc_html__( 'Data for "Secret Key" is invalid', 'yop-poll' );
                }
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->integrations->facebook->{'enabled'} ) ||
                    ( '' === sanitize_text_field( $settings->integrations->facebook->{'enabled'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Use Facebook integration" is invalid', 'yop-poll' );
            }
            if ( 'yes' === $settings->integrations->facebook->{'enabled'} ) {
                if ( ( false === isset( $settings->integrations->facebook->{'app-id'} ) ) || ( '' === sanitize_text_field( $settings->integrations->facebook->{'app-id'} ) ) ) {
                    self::$errors_present = true;
                    self::$error_text = esc_html__( 'Data for "App ID" is invalid', 'yop-poll' );
                }
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->integrations->google->{'enabled'} ) ||
                    ( '' === trim( $settings->integrations->google->{'enabled'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Use Google integration" is invalid', 'yop-poll' );
            }
            if ( 'yes' === $settings->integrations->google->enabled ) {
                if ( ( false === isset( $settings->integrations->google->{'app-id'} ) ) || ( '' === sanitize_text_field( $settings->integrations->google->{'app-id'} ) ) ) {
                    self::$errors_present = true;
                    self::$error_text = esc_html__( 'Data for "App ID" is invalid', 'yop-poll' );
                }
                if ( ( false === isset( $settings->integrations->google->{'app-secret'} ) ) || ( '' === sanitize_text_field( $settings->integrations->google->{'app-secret'} ) ) ) {
                    self::$errors_present = true;
                    self::$error_text = esc_html__( 'Data for "App Secret" is invalid', 'yop-poll' );
                }
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->buttons->{'anonymous'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->buttons->{'anonymous'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Vote as anonymous" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->buttons->{'wordpress'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->buttons->{'wordpress'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Vote with your WordPress account" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->buttons->{'facebook'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->buttons->{'facebook'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Vote with your Facebook account" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->buttons->{'google'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->buttons->{'google'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Vote with your Google account" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'poll-ended'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'poll-ended'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Poll Ended" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'poll-not-started'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'poll-not-started'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Poll Not Started" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'already-voted-on-poll'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'already-voted-on-poll'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Already voted on poll" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'invalid-poll'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'invalid-poll'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Invalid Poll" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'no-answers-selected'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'no-answers-selected'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "No Answer(s) selected" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'min-answers-required'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'min-answers-required'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Minimum answers required" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'max-answers-required'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'max-answers-required'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Maximum answers required" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'no-answer-for-other'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'no-answer-for-other'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "No value for other" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'no-value-for-custom-field'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'no-value-for-custom-field'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "No value for custom field" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'consent-not-checked'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'consent-not-checked'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Consent not checked" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'no-captcha-selected'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'no-captcha-selected'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Captcha missing" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'not-allowed-by-ban'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'not-allowed-by-ban'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Vote not allowed by ban setting" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'not-allowed-by-block'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'not-allowed-by-block'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Vote not allowed by block setting" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'not-allowed-by-limit'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'not-allowed-by-limit'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Vote not allowed by limit setting" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->voting->{'thank-you'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->voting->{'thank-you'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Thank you for your vote" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->results->{'single-vote'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->results->{'single-vote'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Single Vote" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->results->{'multiple-votes'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->results->{'multiple-votes'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Multiple Votes" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->results->{'single-answer'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->results->{'single-answer'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Single Answer" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->results->{'multiple-answers'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->results->{'multiple-answers'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Multiple Answers" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->captcha->{'accessibility-alt'} ) ||
                ( '' === sanitize_text_field( $settings->messages->captcha->{'accessibility-alt'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Accessibility Alt" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->captcha->{'accessibility-title'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->captcha->{'accessibility-title'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Accessibility Title" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->captcha->{'accessibility-description'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->captcha->{'accessibility-description'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Accessibility Description" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->captcha->{'explanation'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->captcha->{'explanation'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Accessibility Explanation" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->captcha->{'refresh-alt'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->captcha->{'refresh-alt'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Refresh Alt" is invalid', 'yop-poll' );
            }
            if (
                ( false === self::$errors_present ) &&
                ( ! isset( $settings->messages->captcha->{'refresh-title'} ) ||
                    ( '' === sanitize_text_field( $settings->messages->captcha->{'refresh-title'} ) ) )
            ) {
                self::$errors_present = true;
                self::$error_text = esc_html__( 'Data for "Refresh Title" is invalid', 'yop-poll' );
            }
        }
    }
    public static function save_settings( $settings ) {
        self::validate_data( $settings );
        if ( false === self::$errors_present ) {
			$current_settings = unserialize( self::get_all_settings() );
            $yop_poll_settings = array(
                'general' => array(
                    'i-date' => $current_settings['general']['i-date'],
                    'show-guide' => $current_settings['general']['show-guide'],
                    'remove-data' => sanitize_text_field( $settings->general->{'remove-data'} ),
					'use-custom-headers-for-ip' => sanitize_text_field( $settings->general->{'use-custom-headers-for-ip'} ),
                ),
                'notifications'  => array(
                    'new-vote' => array(
                        'from-name'  => sanitize_text_field( $settings->notifications->{'new-vote'}->{'from-name'} ),
                        'from-email' => sanitize_text_field( $settings->notifications->{'new-vote'}->{'from-email'} ),
                        'recipients' => sanitize_text_field( $settings->notifications->{'new-vote'}->{'recipients'} ),
                        'subject'    => sanitize_text_field( $settings->notifications->{'new-vote'}->{'subject'} ),
                        'message'    => wp_kses(
							$settings->notifications->{'new-vote'}->{'message'},
							array(
								'br' => array(),
							)
						),
                    ),
                    'automatically-reset-votes' => array(
                        'from-name'  => 'Your Name Here',
                        'from-email' => 'Your Email Address Here',
                        'recipients' => '',
                        'subject'    => 'Stats for %POLL-NAME% on %RESET-DATE%',
                        'message'    => 'Poll - %POLL-NAME%
									Reset Date - %RESET-DATE%
									
									[RESULTS]
									%QUESTION-TEXT%
									[ANSWERS]
									%ANSWER-TEXT% - %ANSWER-VOTES% votes - %ANSWER-PERCENTAGES%
									[/ANSWERS]
									
									[OTHER-ANSWERS]
									%ANSWER-TEXT% - %ANSWER-VOTES% votes
									[/OTHER-ANSWERS]
									[/RESULTS]',
					),
                ),
                'integrations' => array(
                    'reCaptcha' => array(
                        'enabled' => sanitize_text_field( $settings->integrations->reCaptcha->{'enabled'} ),
                        'site-key' => sanitize_text_field( $settings->integrations->reCaptcha->{'site-key'} ),
                        'secret-key' => sanitize_text_field( $settings->integrations->reCaptcha->{'secret-key'} ),
                    ),
                    'reCaptchaV2Invisible' => array(
						'enabled' => sanitize_text_field( $settings->integrations->reCaptchaV2Invisible->{'enabled'} ),
						'site-key' => sanitize_text_field( $settings->integrations->reCaptchaV2Invisible->{'site-key'} ),
						'secret-key' => sanitize_text_field( $settings->integrations->reCaptchaV2Invisible->{'secret-key'} ),
                    ),
                    'reCaptchaV3' => array(
						'enabled' => sanitize_text_field( $settings->integrations->reCaptchaV3->{'enabled'} ),
						'site-key' => sanitize_text_field( $settings->integrations->reCaptchaV3->{'site-key'} ),
                        'secret-key' => sanitize_text_field( $settings->integrations->reCaptchaV3->{'secret-key'} ),
                        'min-allowed-score' => sanitize_text_field( $settings->integrations->reCaptchaV3->{'min-allowed-score'} ),
					),
                    'hCaptcha' => array(
						'enabled' => sanitize_text_field( $settings->integrations->hCaptcha->{'enabled'} ),
						'site-key' => sanitize_text_field( $settings->integrations->hCaptcha->{'site-key'} ),
						'secret-key' => sanitize_text_field( $settings->integrations->hCaptcha->{'secret-key'} ),
                    ),
                    'facebook' => array(
                        'enabled' => sanitize_text_field( $settings->integrations->facebook->{'enabled'} ),
                        'app-id'  => sanitize_text_field( $settings->integrations->facebook->{'app-id'} ),
                    ),
                    'google'   => array(
                        'enabled' => sanitize_text_field( $settings->integrations->google->{'enabled'} ),
                        'app-id'      => sanitize_text_field( $settings->integrations->google->{'app-id'} ),
                        'app-secret'  => sanitize_text_field( $settings->integrations->google->{'app-secret'} ),
					),
                ),
                'messages' => array(
                    'captcha' => array(
                        'accessibility-alt' => sanitize_text_field( $settings->messages->captcha->{'accessibility-alt'} ),
                        'accessibility-title' => sanitize_text_field( $settings->messages->captcha->{'accessibility-title'} ),
                        'accessibility-description' => sanitize_text_field( $settings->messages->captcha->{'accessibility-description'} ),
                        'explanation' => sanitize_text_field( $settings->messages->captcha->{'explanation'} ),
                        'refresh-alt' => sanitize_text_field( $settings->messages->captcha->{'refresh-alt'} ),
                        'refresh-title' => sanitize_text_field( $settings->messages->captcha->{'refresh-title'} ),
                    ),
                    'buttons' => array(
                        'anonymous' => sanitize_text_field( $settings->messages->buttons->{'anonymous'} ),
                        'wordpress' => sanitize_text_field( $settings->messages->buttons->{'wordpress'} ),
                        'facebook' => sanitize_text_field( $settings->messages->buttons->{'facebook'} ),
                        'google' => sanitize_text_field( $settings->messages->buttons->{'google'} ),
                    ),
                    'voting' => array(
                        'poll-ended' => sanitize_text_field( $settings->messages->voting->{'poll-ended'} ),
                        'poll-not-started' => sanitize_text_field( $settings->messages->voting->{'poll-not-started'} ),
                        'already-voted-on-poll' => sanitize_text_field( $settings->messages->voting->{'already-voted-on-poll'} ),
                        'invalid-poll' => sanitize_text_field( $settings->messages->voting->{'invalid-poll'} ),
                        'no-answers-selected' => sanitize_text_field( $settings->messages->voting->{'no-answers-selected'} ),
                        'min-answers-required' => sanitize_text_field( $settings->messages->voting->{'min-answers-required'} ),
                        'max-answers-required' => sanitize_text_field( $settings->messages->voting->{'max-answers-required'} ),
                        'no-answer-for-other' => sanitize_text_field( $settings->messages->voting->{'no-answer-for-other'} ),
                        'no-value-for-custom-field' => sanitize_text_field( $settings->messages->voting->{'no-value-for-custom-field'} ),
                        'consent-not-checked' => sanitize_text_field( $settings->messages->voting->{'consent-not-checked'} ),
                        'no-captcha-selected' => sanitize_text_field( $settings->messages->voting->{'no-captcha-selected'} ),
                        'not-allowed-by-ban' => sanitize_text_field( $settings->messages->voting->{'not-allowed-by-ban'} ),
                        'not-allowed-by-block' => sanitize_text_field( $settings->messages->voting->{'not-allowed-by-block'} ),
                        'not-allowed-by-limit' => sanitize_text_field( $settings->messages->voting->{'not-allowed-by-limit'} ),
                        'thank-you' => sanitize_text_field( $settings->messages->voting->{'thank-you'} ),
                    ),
                    'results' => array(
                        'single-vote' => sanitize_text_field( $settings->messages->results->{'single-vote'} ),
                        'multiple-votes' => sanitize_text_field( $settings->messages->results->{'multiple-votes'} ),
                        'single-answer' => sanitize_text_field( $settings->messages->results->{'single-answer'} ),
                        'multiple-answers' => sanitize_text_field( $settings->messages->results->{'multiple-answers'} ),
					),
                ),
            );
            update_option( 'yop_poll_settings', serialize( $yop_poll_settings ) );
            self::$settings = serialize( $yop_poll_settings );
        }
        return array(
            'success' => ! self::$errors_present,
            'error' => self::$error_text,
        );
    }
}
