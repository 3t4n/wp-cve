<?php
class YOP_Poll_Public {
	public function __construct() {
		add_filter( 'script_loader_tag', array( $this, 'clean_recaptcha_url' ), 10, 2 );
		add_action( 'yop_poll_hourly_event', array( $this, 'cron' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_dependencies' ) );
		add_action( 'init', array( $this, 'load_translation' ) );
		add_action( 'init', array( $this, 'create_shortcodes' ) );
	}
	public function clean_recaptcha_url( $tag, $handle ) {
		if (
			( 'yop-reCaptcha' === $handle ) ||
			( 'yop-hCaptcha' === $handle )
		) {
			return str_replace( '&#038;', '&', str_replace( ' src', ' async defer src', $tag ) );
		} else {
			return $tag;
		}
	}
	public function load_dependencies() {
		$this->load_styles();
		$this->load_scripts();
	}
	public function load_styles() {
		wp_enqueue_style( 'yop-public', YOP_POLL_URL . 'public/assets/css/yop-poll-public-' . YOP_POLL_VERSION . '.css' );
	}
	public function load_scripts() {
		$plugin_frontend_js_file = '';
		$plugin_settings = YOP_Poll_Settings::get_all_settings();
		if ( false !== $plugin_settings ) {
			$plugin_settings_decoded = unserialize( $plugin_settings );
		}
		if ( true === YOP_POLL_TEST_MODE ) {
			$plugin_frontend_js_file = 'yop-poll-public-' . YOP_POLL_VERSION . '.js';
		} else {
			$plugin_frontend_js_file = 'yop-poll-public-' . YOP_POLL_VERSION . '.min.js';
		}
		wp_enqueue_script( 'yop-public', YOP_POLL_URL . 'public/assets/js/' . $plugin_frontend_js_file, array( 'jquery' ) );
		if (
			(
			( 'yes' === $plugin_settings_decoded['integrations']['reCaptcha']['enabled'] ) &&
			( '' !== $plugin_settings_decoded['integrations']['reCaptcha']['site-key'] ) &&
			( '' !== $plugin_settings_decoded['integrations']['reCaptcha']['secret-key'] )
			) ||
		(
			( true === isset( $plugin_settings_decoded['integrations']['reCaptchaV2Invisible']['enabled'] ) ) &&
			( 'yes' === $plugin_settings_decoded['integrations']['reCaptchaV2Invisible']['enabled'] ) &&
			( '' !== $plugin_settings_decoded['integrations']['reCaptchaV2Invisible']['site-key'] ) &&
			( '' !== $plugin_settings_decoded['integrations']['reCaptchaV2Invisible']['secret-key'] )
		) ) {
			/* add reCaptcha if enabled */
			$args = array(
				'render' => 'explicit',
				'onload' => 'YOPPollOnLoadRecaptcha'
			);
			wp_register_script( 'yop-reCaptcha', add_query_arg( $args, 'https://www.google.com/recaptcha/api.js' ), '', null );
			wp_enqueue_script( 'yop-reCaptcha' );
			/* done adding reCaptcha */
		} else {
			if (
				( true === isset( $plugin_settings_decoded['integrations']['reCaptchaV3']['enabled'] ) ) &&
				( 'yes' === $plugin_settings_decoded['integrations']['reCaptchaV3']['enabled'] ) &&
				( '' !== $plugin_settings_decoded['integrations']['reCaptchaV3']['site-key'] ) &&
				( '' !== $plugin_settings_decoded['integrations']['reCaptchaV3']['secret-key'] )
			) {
				/* add reCaptcha if enabled */
				$args = array(
					'render' => isset( $plugin_settings_decoded['integrations']['reCaptchaV3']['site-key'] ) ? $plugin_settings_decoded['integrations']['reCaptchaV3']['site-key'] : ''
				);
				wp_register_script( 'yop-reCaptcha', add_query_arg( $args, 'https://www.google.com/recaptcha/api.js' ), '', null );
				wp_enqueue_script( 'yop-reCaptcha' );
				/* done adding reCaptcha */
			}
		}
		if (
			( true === isset( $plugin_settings_decoded['integrations']['hCaptcha']['enabled'] ) ) &&
			( 'yes' === $plugin_settings_decoded['integrations']['hCaptcha']['enabled'] ) &&
			( '' !== $plugin_settings_decoded['integrations']['hCaptcha']['site-key'] ) &&
			( '' !== $plugin_settings_decoded['integrations']['hCaptcha']['secret-key'] )
		) {
			//add hCaptcha code since it's enabled
			wp_register_script(
				'yop-hCaptcha',
				add_query_arg(
					array(
						'render' => 'explicit',
						'onload' => 'YOPPollOnLoadHCaptcha',
					),
					'https://js.hcaptcha.com/1/api.js',
					'',
					null
				)
			);
			wp_enqueue_script( 'yop-hCaptcha' );
		}
		$captcha_accessibility_description = str_replace( '[STRONG]', '<strong>', esc_html( $plugin_settings_decoded['messages']['captcha']['accessibility-description'] ) );
		$captcha_accessibility_description = str_replace( '[/STRONG]', '</strong>', $captcha_accessibility_description );
		$captcha_explanation = str_replace( '[STRONG]', '<strong>', esc_html( $plugin_settings_decoded['messages']['captcha']['explanation'] ) );
		$captcha_explanation = str_replace( '[/STRONG]', '</strong>', $captcha_explanation );
		wp_localize_script(
			'yop-public',
			'objectL10n',
			array(
				'yopPollParams' => array(
					'urlParams' => array(
						'ajax' => admin_url( 'admin-ajax.php' ),
						'wpLogin' => wp_login_url( admin_url( 'admin-ajax.php?action=yop_poll_record_wordpress_vote' ) ),
					),
					'apiParams' => array(
						'reCaptcha' => array(
							'siteKey' => isset( $plugin_settings_decoded['integrations']['reCaptcha']['site-key'] ) ? $plugin_settings_decoded['integrations']['reCaptcha']['site-key'] : '',
						),
						'reCaptchaV2Invisible' => array(
							'siteKey' => isset( $plugin_settings_decoded['integrations']['reCaptchaV2Invisible']['site-key'] ) ? $plugin_settings_decoded['integrations']['reCaptchaV2Invisible']['site-key'] : '',
						),
						'reCaptchaV3' => array(
							'siteKey' => isset( $plugin_settings_decoded['integrations']['reCaptchaV3']['site-key'] ) ? $plugin_settings_decoded['integrations']['reCaptchaV3']['site-key'] : '',
						),
						'hCaptcha' => array(
							'siteKey' => isset( $plugin_settings_decoded['integrations']['hCaptcha']['site-key'] ) ? $plugin_settings_decoded['integrations']['hCaptcha']['site-key'] : '',
						),
					),
					'captchaParams' => array(
						'imgPath' => YOP_POLL_URL . 'public/assets/img/',
						'url' => YOP_POLL_URL . 'app.php',
						'accessibilityAlt' => esc_html( $plugin_settings_decoded['messages']['captcha']['accessibility-alt'] ),
						'accessibilityTitle' => esc_html( $plugin_settings_decoded['messages']['captcha']['accessibility-title'] ),
						'accessibilityDescription' => $captcha_accessibility_description,
						'explanation' => $captcha_explanation,
						'refreshAlt' => esc_html( $plugin_settings_decoded['messages']['captcha']['refresh-alt'] ),
						'refreshTitle' => esc_html( $plugin_settings_decoded['messages']['captcha']['refresh-title'] ),
					),
					'voteParams' => array(
						'invalidPoll' => str_replace(
							array( '[strong]', '[/strong]', '[i]', '[/i]', '[u]', '[/u]', '[br]' ),
							array( '<strong>', '</strong>', '<i>', '</i>', '<u>', '</u>', '</br>' ),
							esc_html( $plugin_settings_decoded['messages']['voting']['invalid-poll'] )
						),
						'noAnswersSelected' => str_replace(
							array( '[strong]', '[/strong]', '[i]', '[/i]', '[u]', '[/u]', '[br]' ),
							array( '<strong>', '</strong>', '<i>', '</i>', '<u>', '</u>', '</br>' ),
							esc_html( $plugin_settings_decoded['messages']['voting']['no-answers-selected'] )
						),
						'minAnswersRequired' => str_replace(
							array( '[strong]', '[/strong]', '[i]', '[/i]', '[u]', '[/u]', '[br]' ),
							array( '<strong>', '</strong>', '<i>', '</i>', '<u>', '</u>', '</br>' ),
							esc_html( $plugin_settings_decoded['messages']['voting']['min-answers-required'] )
						),
						'maxAnswersRequired' => str_replace(
							array( '[strong]', '[/strong]', '[i]', '[/i]', '[u]', '[/u]', '[br]' ),
							array( '<strong>', '</strong>', '<i>', '</i>', '<u>', '</u>', '</br>' ),
							esc_html( $plugin_settings_decoded['messages']['voting']['max-answers-required'] )
						),
						'noAnswerForOther' => str_replace(
							array( '[strong]', '[/strong]', '[i]', '[/i]', '[u]', '[/u]', '[br]' ),
							array( '<strong>', '</strong>', '<i>', '</i>', '<u>', '</u>', '</br>' ),
							esc_html( $plugin_settings_decoded['messages']['voting']['no-answer-for-other'] )
						),
						'noValueForCustomField' => str_replace(
							array( '[strong]', '[/strong]', '[i]', '[/i]', '[u]', '[/u]', '[br]' ),
							array( '<strong>', '</strong>', '<i>', '</i>', '<u>', '</u>', '</br>' ),
							esc_html( $plugin_settings_decoded['messages']['voting']['no-value-for-custom-field'] )
						),
						'consentNotChecked' => str_replace(
							array( '[strong]', '[/strong]', '[i]', '[/i]', '[u]', '[/u]', '[br]' ),
							array( '<strong>', '</strong>', '<i>', '</i>', '<u>', '</u>', '</br>' ),
							esc_html( $plugin_settings_decoded['messages']['voting']['consent-not-checked'] )
						),
						'noCaptchaSelected' => str_replace(
							array( '[strong]', '[/strong]', '[i]', '[/i]', '[u]', '[/u]', '[br]' ),
							array( '<strong>', '</strong>', '<i>', '</i>', '<u>', '</u>', '</br>' ),
							esc_html( $plugin_settings_decoded['messages']['voting']['no-captcha-selected'] )
						),
						'thankYou' => str_replace(
							array( '[strong]', '[/strong]', '[i]', '[/i]', '[u]', '[/u]', '[br]' ),
							array( '<strong>', '</strong>', '<i>', '</i>', '<u>', '</u>', '</br>' ),
							esc_html( $plugin_settings_decoded['messages']['voting']['thank-you'] )
						),
					),
					'resultsParams' => array(
						'singleVote' => str_replace(
							array( '[strong]', '[/strong]', '[i]', '[/i]', '[u]', '[/u]', '[br]' ),
							array( '<strong>', '</strong>', '<i>', '</i>', '<u>', '</u>', '</br>' ),
							esc_html( $plugin_settings_decoded['messages']['results']['single-vote'] )
						),
						'multipleVotes' => str_replace(
							array( '[strong]', '[/strong]', '[i]', '[/i]', '[u]', '[/u]', '[br]' ),
							array( '<strong>', '</strong>', '<i>', '</i>', '<u>', '</u>', '</br>' ),
							esc_html( $plugin_settings_decoded['messages']['results']['multiple-votes'] )
						),
						'singleAnswer' => str_replace(
							array( '[strong]', '[/strong]', '[i]', '[/i]', '[u]', '[/u]', '[br]' ),
							array( '<strong>', '</strong>', '<i>', '</i>', '<u>', '</u>', '</br>' ),
							esc_html( $plugin_settings_decoded['messages']['results']['single-answer'] )
						),
						'multipleAnswers' => str_replace(
							array( '[strong]', '[/strong]', '[i]', '[/i]', '[u]', '[/u]', '[br]' ),
							array( '<strong>', '</strong>', '<i>', '</i>', '<u>', '</u>', '</br>' ),
							esc_html( $plugin_settings_decoded['messages']['results']['multiple-answers'] )
						),
					),
				),
			)
		);
	}
	public function load_translation() {
		load_plugin_textdomain( 'yop-poll', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	public function create_shortcodes() {
		add_shortcode( 'yop_poll', array( $this, 'parse_regular_shortcode' ) );
		add_shortcode( 'yop_poll_archive', array( $this, 'parse_archive_shortcode' ) );
		add_shortcode( 'yop_poll_stats', array( $this, 'parse_stats_shortcode' ) );
	}
	public function parse_regular_shortcode( $atts ) {
		$params = shortcode_atts(
			array(
                'id'      => - 1,
                'results' => 0,
                'tracking_id'   => '',
                'show_results' => '',
            ),
			$atts,
			'yop_poll'
		);
		$params['page_id'] = get_the_ID();
		return $this->generate_poll( $params );
	}
    public function parse_archive_shortcode( $atts ) {
		$params = shortcode_atts(
			array(
				'max' => 0,
				'sort' => 'date_added',
				'sortdir' => 'asc',
				'show' => 'all',
			),
			$atts,
			'yop_poll_archive'
		);
		$params['page_id'] = get_the_ID();
		return $this->generate_polls_for_archive( $params );
	}
	public function generate_poll( $params ) {
		if ( isset( $params['id'] ) && ( '' !== $params['id'] ) && ( '0' != $params['id'] ) ) {
			$poll = '';
			$poll_ready_for_output = '';
			$params['loaded_with'] = '1';
			switch ( $params['id'] ) {
				case '-1': {
					$poll_id = YOP_Poll_Polls::get_current_active();
					break;
				}
				case '-2': {
					$poll_id = YOP_Poll_Polls::get_latest();
					break;
				}
				case '-3': {
					$poll_id = YOP_Poll_Polls::get_random();
					break;
				}
				default: {
					$poll_id = sanitize_text_field( wp_unslash( $params['id'] ) );
					break;
				}
			}
			if ( isset( $poll_id ) ) {
				$poll = YOP_Poll_Polls::get_info( $poll_id );
				if ( false !== $poll ) {
					$poll->meta_data = unserialize( $poll->meta_data );
				}
			}
			if ( false !== $poll ) {
				if ( ( true === isset( $poll->meta_data['options']['poll']['loadWithAjax'] ) ) && ( 'yes' === $poll->meta_data['options']['poll']['loadWithAjax'] ) ) {
					$poll_ready_for_output = YOP_Poll_Basic::create_poll_view_for_ajax( $poll, $params );
				} else {
					switch ( $poll->template_base ) {
						case 'basic': {
							if ( ( true === isset( $params['show_results'] ) ) && ( '1' == $params['show_results'] ) ) {
								$params['show_thank_you_message'] = '0';
								$poll_ready_for_output = YOP_Poll_Basic::create_poll_view_for_results( $poll, $params );
							} else {
								$params['show_thank_you_message'] = '1';
								$poll_ready_for_output = YOP_Poll_Basic::create_poll_view( $poll, $params );
							}
							break;
						}
					}
				}
				$content_for_output = "<div class='bootstrap-yop yop-poll-mc'>
							{$poll_ready_for_output}
						</div>";
			} else {
				$content_for_output = '';
			}
			return $content_for_output;
		}
	}
	public static function generate_poll_for_ajax( $poll_id, $params ) {
		$poll_ready_for_output = '';
		$params['loaded_with'] = '2';
		if ( isset( $poll_id ) ) {
			$poll = YOP_Poll_Polls::get_info( $poll_id );
			if ( false !== $poll ) {
				$poll->meta_data = unserialize( $poll->meta_data );
				switch ( $poll->template_base ) {
					case 'basic': {
						if ( ( true === isset( $params['show_results'] ) ) && ( '1' == $params['show_results'] ) ) {
							$params['show_thank_you_message'] = '0';
							$poll_ready_for_output = YOP_Poll_Basic::create_poll_view_for_results( $poll, $params );
						} else {
							$params['show_thank_you_message'] = '1';
							$poll_ready_for_output = YOP_Poll_Basic::create_poll_view( $poll, $params );
						}
						break;
					}
				}
			}
		}
		if ( '' !== $poll_ready_for_output ) {
			return $poll_ready_for_output;
		} else {
			return false;
		}
	}
	public function generate_polls_for_archive( $params ) {
		$order_by = '';
		switch ( $params['sort'] ) {
			case 'date_added': {
				$order_by = 'ORDER BY `added_date`';
				break;
			}
			case 'num_votes': {
				$order_by = 'ORDER BY `total_submits`';
				break;
			}
			default: {
				$order_by = 'ORDER BY `added_date`';
				break;
			}
		}
		switch ( $params['sortdir'] ) {
			case 'asc': {
				$order_by .= ' ASC';
				break;
			}
			case 'desc': {
				$order_by .= ' DESC';
				break;
			}
			default: {
				$order_by .= ' ASC';
				break;
			}
		}
		$limit = '';
		switch ( $params['show'] ) {
			case 'all': {
				$polls = YOP_Poll_Polls::get_all_polls_for_archive( $params, $order_by );
				break;
			}
			case 'active': {
				$polls = YOP_Poll_Polls::get_active_polls_for_archive( $params, $order_by );
				break;
			}
			case 'ended': {
				$polls = YOP_Poll_Polls::get_ended_polls_for_archive( $params, $order_by );
				break;
			}
			default: {
				$polls = YOP_Poll_Polls::get_all_polls_for_archive( $params, $order_by );
			}
		}
		$content = '';
        if ( count( $polls ) > 0 ) {
            foreach ( $polls as $poll ) {
				$poll_params = array(
					'id' => $poll['id'],
					'results' => 0,
					'tracking_id' => '',
					'show_results' => '',
					'page_id' => $params['page_id'],
				);
                $content .= $this->generate_poll( $poll_params );
            }
        }
        return $content;
	}
	public function cron() {
		$polls = YOP_Poll_Polls::get_polls_for_cron();
		foreach ( $polls as $poll ) {
			if ( 'yes' === $poll['resetPollStatsAutomatically'] ) {
				if ( strtotime( $poll['resetPollStatsOn'] ) <= time() ) {
					YOP_Poll_Polls::reset_stats_for_poll( $poll['id'] );
					switch ( $poll['resetPollStatsEveryPeriod'] ) {
						case 'hours': {
							$unit_multiplier = 60 * 60;
							break;
						}
						case 'days': {
							$unit_multiplier = 60 * 60 * 24;
							break;
						}
					}
					$next_reset_date = strtotime( $poll['resetPollStatsOn'] ) + intval( $poll['resetPollStatsEvery'] ) * $unit_multiplier;
					YOP_Poll_Polls::update_meta_data( $poll['id'], 'poll', 'resetPollStatsOn', date( 'Y-m-d H:i', $next_reset_date ) );
				}
			}
		}
	}
}
