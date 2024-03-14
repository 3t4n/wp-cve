<?php

final class BWFAN_Wp_Sendemail extends BWFAN_Action {

	public static $TEMPLATE_RICH_TEXT = 1;
	public static $TEMPLATE_WC = 2;
	public static $TEMPLATE_RAW_HTML = 3;
	public static $TEMPLATE_DRAG_DROP = 4;
	private static $ins = null;
	public $is_preview = false;
	public $preview_body = '';
	public $support_language = true;
	public $support_sideheader = [ 'merge-tag', 'link-trigger', 'email-template' ];
	public $logs = [];

	protected function __construct() {
		$this->action_name     = __( 'Send Email', 'wp-marketing-automations' );
		$this->action_desc     = __( 'This action sends an email to a user', 'autonami-automations-connectors' );
		$this->required_fields = array( 'subject', 'body', 'email', 'from_email', 'from_name' );
		$this->support_v2      = true;
		add_filter( 'admin_body_class', array( $this, 'add_email_preview_class' ) );
	}

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	public function load_hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ), 98 );
	}

	/**
	 * Localize data for html fields for the current action.
	 */
	public function admin_enqueue_assets() {
		if ( false === BWFAN_Common::is_load_admin_assets( 'automation' ) ) {
			return;
		}
		wp_enqueue_media();
		$data = [];

		$data['raw_template'] = __( 'Rich Text', 'wp-marketing-automations' );
		if ( bwfan_is_woocommerce_active() ) {
			$data['wc_template'] = __( 'WooCommerce', 'wp-marketing-automations' );
		}
		$data['raw'] = __( 'Raw HTML', 'wp-marketing-automations' );
		if ( bwfan_is_autonami_pro_active() ) {
			$data['editor'] = __( 'Visual Builder', 'wp-marketing-automations' );
		}

		BWFAN_Core()->admin->set_actions_js_data( $this->get_class_slug(), 'template_options', $data );
		BWFAN_Core()->admin->set_actions_js_data( $this->get_class_slug(), 'default_promotional_status', apply_filters( "bwfan_default_enable_promotional_emails", 0 ) );
	}

	public function add_unsubscribe_merge_tag( $text ) {
		if ( isset( $this->data['promotional_email'] ) && 0 === absint( $this->data['promotional_email'] ) ) {
			return $text;
		}

		// add separator if there is footer text
		if ( trim( $text ) ) {
			$text .= apply_filters( 'bwfan_woo_email_footer_separator', ' - ' );
		}

		$global_settings  = BWFAN_Common::get_global_settings();
		$unsubscribe_link = BWFAN_Common::decode_merge_tags( '{{unsubscribe_link}}' );
		$text             .= '<a href="' . $unsubscribe_link . '">' . $global_settings['bwfan_unsubscribe_email_label'] . '</a>';

		return $text;
	}

	public function add_unsubscribe_query_args( $link ) {
		if ( empty( $this->data ) ) {
			return $link;
		}

		$link = add_query_arg( array(
			'mode' => 1,
		), $link );

		return $link;
	}

	/**
	 * Show the html fields for the current action.
	 */
	public function get_view() {
		include_once BWFAN_PLUGIN_DIR . '/includes/native-connectors/wordpress/views/bwfan-wp-send-email.php';
	}

	/**
	 * Make all the data which is required by the current action.
	 * This data will be used while executing the task of this action.
	 *
	 * @param $integration_object
	 * @param $task_meta
	 *
	 * @return array|void
	 */
	public function make_data( $integration_object, $task_meta ) {
		$user_id = isset( $task_meta['global']['user_id'] ) && ! empty( $task_meta['global']['user_id'] ) ? absint( $task_meta['global']['user_id'] ) : 0;
		$user_id = empty( $user_id ) && isset( $task_meta['data']['user_id'] ) && ! empty( $task_meta['data']['user_id'] ) ? absint( $task_meta['data']['user_id'] ) : 0;

		$global_email_settings = BWFAN_Common::get_global_settings();
		$data_to_set           = array(
			'subject'           => BWFAN_Common::decode_merge_tags( $task_meta['data']['subject'] ),
			'subject_raw'       => $task_meta['data']['subject'],
			'email'             => BWFAN_Common::decode_merge_tags( $task_meta['data']['to'] ),
			'name'              => BWFAN_Common::decode_merge_tags( '{{contact_first_name}}' ),
			'email_heading'     => BWFAN_Common::decode_merge_tags( $task_meta['data']['email_heading'] ),
			'preheading'        => empty( $task_meta['data']['preheading'] ) ? '' : BWFAN_Common::decode_merge_tags( $task_meta['data']['preheading'] ),
			'template'          => $task_meta['data']['template'],
			'promotional_email' => ( isset( $task_meta['data']['promotional_email'] ) ) ? 1 : 0,
			'append_utm'        => ( isset( $task_meta['data']['append_utm'] ) ) ? 1 : 0,
			'utm_source'        => ( isset( $task_meta['data']['utm_source'] ) ) ? BWFAN_Common::decode_merge_tags( $task_meta['data']['utm_source'] ) : '',
			'utm_medium'        => ( isset( $task_meta['data']['utm_medium'] ) ) ? BWFAN_Common::decode_merge_tags( $task_meta['data']['utm_medium'] ) : '',
			'utm_campaign'      => ( isset( $task_meta['data']['utm_campaign'] ) ) ? BWFAN_Common::decode_merge_tags( $task_meta['data']['utm_campaign'] ) : '',
			'utm_term'          => ( isset( $task_meta['data']['utm_term'] ) ) ? BWFAN_Common::decode_merge_tags( $task_meta['data']['utm_term'] ) : '',
			'event'             => $task_meta['event_data']['event_slug'],
			'body'              => $this->get_email_body( $task_meta ),
			'from_email'        => $global_email_settings['bwfan_email_from'],
			'from_name'         => $global_email_settings['bwfan_email_from_name'],
			'reply_to_email'    => $global_email_settings['bwfan_email_reply_to'],
			'user_id'           => empty( $user_id ) ? null : $user_id
		);

		$data_to_set['body'] = stripslashes( $data_to_set['body'] );
		if ( true === $this->is_preview ) {
			$this->preview_body  = $data_to_set['body'];
			$data_to_set['body'] = BWFAN_Common::decode_merge_tags( $data_to_set['body'] );
			$data_to_set['body'] = apply_filters( 'bwfan_before_send_email_body', $data_to_set['body'], $data_to_set );
			$data_to_set['body'] = $this->email_content( $data_to_set );
			$data_to_set['body'] = BWFAN_Common::bwfan_correct_protocol_url( $data_to_set['body'] );
		}

		/** in case email missing then get from global if available */
		if ( empty( $data_to_set['email'] ) && ! empty( $task_meta['global']['email'] ) ) {
			$data_to_set['email'] = $task_meta['global']['email'];
		}
		$contact            = new WooFunnels_Contact( '', $data_to_set['email'] );
		$data_to_set['uid'] = $contact->get_uid();

		return apply_filters( 'bwfan_sendemail_make_data', $data_to_set, $task_meta );
	}

	public function get_email_body( $task_meta ) {
		switch ( $task_meta['data']['template'] ) {
			case 'raw':
				return $task_meta['data']['body_raw'];
			case 'editor':
				return $task_meta['data']['editor']['body'];
			default:
				return $task_meta['data']['body'];
		}
	}

	public function email_content( $data ) {
		if ( method_exists( $this, 'email_body_' . $data['template'] ) ) {
			return call_user_func( [ $this, 'email_body_' . $data['template'] ], $data );
		}

		return $this->email_content_v2( $data );
	}

	public function make_v2_data( $automation_data, $step_data ) {
		$email_data   = $step_data['bwfan_email_data'];
		$to           = $step_data['bwfan_email_to'];
		$user_id      = isset( $automation_data['global'] ) && isset( $automation_data['global']['user_id'] ) ? absint( $automation_data['global']['user_id'] ) : 0;
		$utm_enabled  = ! empty( $email_data['data']['utmEnabled'] );
		$lang_enabled = isset( $step_data['set_language'] ) ? $step_data['set_language'] : 0;

		$email_headers = $this->get_final_email_header_settings( $step_data );

		$data_to_set = array(
			'subject'           => BWFAN_Common::decode_merge_tags( $email_data['subject'] ),
			'subject_raw'       => $email_data['subject'],
			'email'             => BWFAN_Common::decode_merge_tags( $to ),
			'name'              => BWFAN_Common::decode_merge_tags( '{{contact_first_name}}' ),
			'preheading'        => empty( $email_data['data']['preheader'] ) ? '' : BWFAN_Common::decode_merge_tags( $email_data['data']['preheader'] ),
			'template'          => absint( $email_data['mode'] ),
			'promotional_email' => ( isset( $email_data['data']['isTransactional'] ) && 1 === absint( $email_data['data']['isTransactional'] ) ) ? 0 : 1,
			'append_utm'        => $utm_enabled ? 1 : 0,
			'utm_source'        => ( $utm_enabled && isset( $email_data['data']['utm']['source'] ) ) ? BWFAN_Common::decode_merge_tags( $email_data['data']['utm']['source'] ) : '',
			'utm_medium'        => ( $utm_enabled && isset( $email_data['data']['utm']['medium'] ) ) ? BWFAN_Common::decode_merge_tags( $email_data['data']['utm']['medium'] ) : '',
			'utm_name'          => ( $utm_enabled && isset( $email_data['data']['utm']['name'] ) ) ? BWFAN_Common::decode_merge_tags( $email_data['data']['utm']['name'] ) : '',
			'utm_content'       => ( $utm_enabled && isset( $email_data['data']['utm']['content'] ) ) ? BWFAN_Common::decode_merge_tags( $email_data['data']['utm']['content'] ) : '',
			'utm_term'          => ( $utm_enabled && isset( $email_data['data']['utm']['term'] ) ) ? BWFAN_Common::decode_merge_tags( $email_data['data']['utm']['term'] ) : '',
			'event'             => $automation_data['event_data']['event_slug'],
			'body'              => $email_data['template'],
			'from_email'        => $email_headers['email'],
			'from_name'         => $email_headers['name'],
			'reply_to_email'    => $email_headers['reply_to'],
			'user_id'           => empty( $user_id ) ? null : $user_id,
			'lang_enabled'      => $lang_enabled,
			'selected_language' => ( $lang_enabled && isset( $step_data['language'] ) ) ? $step_data['language'] : '',
		);

		$data_to_set['body'] = stripslashes( $data_to_set['body'] );
		if ( true === $this->is_preview ) {
			$this->preview_body  = $data_to_set['body'];
			$data_to_set['body'] = BWFAN_Common::decode_merge_tags( $data_to_set['body'] );
			$data_to_set['body'] = apply_filters( 'bwfan_before_send_email_body', $data_to_set['body'], $data_to_set );
			$data_to_set['body'] = $this->email_content_v2( $data_to_set );
			$data_to_set['body'] = BWFAN_Common::bwfan_correct_protocol_url( $data_to_set['body'] );
		}

		/** in case email missing then get from global if available */
		if ( empty( $data_to_set['email'] ) && ! empty( $automation_data['global']['email'] ) ) {
			$data_to_set['email'] = $automation_data['global']['email'];
		}
		$contact            = new WooFunnels_Contact( '', $data_to_set['email'] );
		$data_to_set['uid'] = $contact->get_uid();

		return $data_to_set;
	}

	public function get_final_email_header_settings( $step_data ) {
		$email_data = $step_data['bwfan_email_data']['data'];

		$global_email_settings = BWFAN_Common::get_global_settings();

		$arr = array(
			'name'     => $global_email_settings['bwfan_email_from_name'],
			'email'    => $global_email_settings['bwfan_email_from'],
			'reply_to' => $global_email_settings['bwfan_email_reply_to'],
		);

		$override = ! empty( $email_data['overrideSenderInfo'] );

		if ( true === $override ) {
			/** From name */
			if ( isset( $email_data['from_name'] ) && ! empty( $email_data['from_name'] ) ) {
				$arr['name'] = BWFAN_Common::decode_merge_tags( $email_data['from_name'] );
			}
			/** From email */
			if ( isset( $email_data['from_email'] ) && ! empty( $email_data['from_email'] ) ) {
				$arr['email'] = BWFAN_Common::decode_merge_tags( $email_data['from_email'] );
			}
			/** Reply to email */
			if ( isset( $email_data['reply_to_email'] ) && ! empty( $email_data['reply_to_email'] ) ) {
				$arr['reply_to'] = BWFAN_Common::decode_merge_tags( $email_data['reply_to_email'] );
			}
		}

		return $arr;
	}

	public function email_content_v2( $data ) {
		$body = isset( $data['body'] ) ? $data['body'] : '';

		switch ( absint( $data['template'] ) ) {
			case self::$TEMPLATE_RICH_TEXT:
				return $this->email_body_raw_template( $data );
			case self::$TEMPLATE_RAW_HTML:
				return $this->email_body_raw( $data );
			case self::$TEMPLATE_DRAG_DROP:
				return $this->email_body_editor( $data );
		}
		/** replace the string in email body as string causing issue on saving  $body */
		$body = str_replace( "!(IE)", "(!IE)", $body );

		return $body;
	}

	/**
	 * Outputs Custom template email body
	 *
	 * @param $data
	 *
	 * @return string
	 */
	protected function email_body_raw_template( $data ) {
		$email_body = $this->prepare_email_content( $data['body'] );

		ob_start();
		include BWFAN_PLUGIN_DIR . '/templates/email-styles.php';
		$css = ob_get_clean();

		$email_body = $this->emogrifier_parsed_output( $css, $email_body );

		return $email_body;
	}

	/**
	 * @param $content
	 *
	 * @return string|null
	 */
	private function prepare_email_content( $content ) {
		$has_body      = stripos( $content, '<body' ) !== false;
		$preview_class = $this->is_preview ? 'bwfan_email_preview' : '';

		/** Check if body tag exists */
		if ( ! $has_body ) {
			return '<html><head></head><body><div id="body_content" class="' . $preview_class . '">' . $content . '</div></body></html>';
		}

		$pattern     = "/<body(.*?)>(.*?)<\/body>/is";
		$replacement = '<body$1><div id="body_content" class="' . $preview_class . '">$2</div></body>';

		return preg_replace( $pattern, $replacement, $content );
	}

	protected function emogrifier_parsed_output( $css, $email_body ) {
		if ( empty( $email_body ) || empty( $css ) ) {
			return $email_body;
		}

		if ( ! BWFAN_Common::supports_emogrifier() ) {
			$email_body = '<style type="text/css">' . $css . '</style>' . $email_body;

			return $email_body;
		}

		$emogrifier_class = '\\BWF_Pelago\\Emogrifier';
		if ( ! class_exists( $emogrifier_class ) ) {
			include_once BWFAN_PLUGIN_DIR . '/libraries/class-emogrifier.php';
		}
		try {
			/** @var Emogrifier $emogrifier */
			$emogrifier = new $emogrifier_class( $email_body, $css );
			$email_body = $emogrifier->emogrify();
		} catch ( Exception $e ) {
			BWFAN_Core()->logger->log( $e->getMessage(), 'send_email_emogrifier' );
		}

		return $email_body;
	}

	/**
	 * Outputs RAW HTML/CSS template email body
	 *
	 * @param $data
	 *
	 * @return string
	 */
	protected function email_body_raw( $data ) {
		$email_body = $this->prepare_email_content( $data['body'] );

		ob_start();
		include BWFAN_PLUGIN_DIR . '/templates/email-editor-styles.php';
		$css = ob_get_clean();

		$email_body = $this->emogrifier_parsed_output( $css, $email_body );

		return $email_body;
	}

	/**
	 * Outputs Editor template email body
	 *
	 * @param $data
	 *
	 * @return string
	 */
	protected function email_body_editor( $data ) {
		$email_body = $this->prepare_email_content( $data['body'] );

		ob_start();
		include BWFAN_PLUGIN_DIR . '/templates/email-editor-styles.php';
		$css = ob_get_clean();

		$email_body = $this->emogrifier_parsed_output( $css, $email_body );
		$email_body = apply_filters( 'bwfan_modify_editor_html_body', $email_body );

		return $email_body;
	}

	public function process_v2() {
		/** Perform Promotional checking */
		$promotional = $this->maybe_filter_promotional_emails();
		if ( true !== $promotional ) {
			return $promotional;
		}

		$is_language_support = $this->check_language_support();

		if ( true !== $is_language_support ) {
			return $is_language_support;
		}

		$result = $this->send_email();
		if ( true === $result ) {
			return $this->success_message( __( 'Mail Sent Successfully!' ) );
		}

		if ( bwfan_is_autonami_pro_active() && BWFCRM_Core()->campaigns->maybe_daily_limit_reached() ) {
			return $this->error_response( __( 'Daily Email Limit reached. Will retry after sometime' ) );
		}

		if ( is_array( $result ) && isset( $result['message'] ) ) {
			return $this->error_response( $result['message'] );
		}

		return $this->error_response( __( 'Unknown Error occurred during Send Email', 'wp-marketing-automations' ) );
	}

	public function check_language_support() {
		if ( ! bwfan_is_autonami_pro_active() ) {
			return true;
		}

		/** checking for language plugin **/
		if ( ! function_exists( 'icl_get_languages' ) && ! function_exists( 'pll_the_languages' ) && ! bwfan_is_translatepress_active() && function_exists( 'bwfan_is_weglot_active' ) && ! bwfan_is_weglot_active() ) {
			return true;
		}

		if ( ! isset( $this->data['selected_language'] ) || ! isset( $this->data['lang_enabled'] ) || 1 !== absint( $this->data['lang_enabled'] ) && ! isset( $this->data['selected_language'] ) || empty( $this->data['selected_language'] ) ) {
			return true;
		}

		$selected_lang = $this->data['selected_language'];
		$lang          = $this->data['current_language'];

		if ( empty( $lang ) ) {
			$lang = BWFAN_PRO_Common::passing_event_language( $this->data );
			$lang = $lang['language'];
		}
		if ( $lang === $selected_lang ) {
			return true;
		}

		return [
			'status'  => BWFAN_Action::$RESPONSE_SKIPPED,
			'message' => __( 'Selected language not matched.', 'wp-marketing-automations' )
		];
	}

	public function maybe_filter_promotional_emails() {
		$to     = trim( stripslashes( $this->data['email'] ) );
		$emails = explode( ',', $to );

		$emails = array_map( function ( $email ) {
			return trim( $email );
		}, $emails );

		$where = array(
			'recipient' => $emails,
			'mode'      => 1,
		);

		$status      = null;
		$bwf_contact = bwf_get_contact( '', $emails[0] );
		if ( $bwf_contact instanceof WooFunnels_Contact ) {
			$status = $bwf_contact->get_status();
		}

		/** Checking if contact is bounced than skip the action */
		if ( 2 === absint( $status ) ) {
			return [
				'status'  => BWFAN_Action::$RESPONSE_SKIPPED,
				'message' => __( 'Contact is Bounced', 'wp-marketing-automations' )
			];
		}

		if ( 1 !== absint( $this->data['promotional_email'] ) ) {
			return true;
		}

		$check_unsubscribe = BWFAN_Model_Message_Unsubscribe::get_message_unsubscribe_row( $where, false );
		if ( ! empty( $check_unsubscribe ) && is_array( $check_unsubscribe ) ) {
			$check_unsubscribe = array_map( function ( $unsubscribe_row ) {
				return $unsubscribe_row['recipient'];
			}, $check_unsubscribe );

			$unsubscribed_emails = implode( ', ', array_unique( $check_unsubscribe ) );

			return [
				'status'  => BWFAN_Action::$RESPONSE_SKIPPED,
				'message' => __( 'User(s) are already unsubscribed, with email(s): ' . $unsubscribed_emails, 'wp-marketing-automations' )
			];
		}

		return true;
	}

	/**
	 * Send an Email.
	 *
	 * subject, body , email are required.
	 *
	 * @return array|bool
	 */
	public function send_email() {
		$to = trim( stripslashes( $this->data['email'] ) );
		$this->set_log( 'email_send_start: ' . $to );
		$subject   = stripslashes( $this->data['subject'] );
		$headers   = [];
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-type:text/html;charset=UTF-8';

		if ( ! empty( $this->data['from_name'] ) && ! empty( $this->data['from_email'] ) ) {
			$headers[] = 'From: ' . $this->data['from_name'] . ' <' . $this->data['from_email'] . '>';
		}
		if ( isset( $this->data['reply_to_email'] ) && ! empty( $this->data['reply_to_email'] ) ) {
			$headers[] = 'Reply-To: ' . $this->data['reply_to_email'];
		}
		$aid = isset( $this->data['automation_id'] ) ? $this->data['automation_id'] : 0;
		$sid = isset( $this->data['step_id'] ) ? $this->data['step_id'] : 0;
		/** Set unsubscribe link in header */
		$unsubscribe_link = BWFAN_Common::get_unsubscribe_link( [ 'uid' => $this->data['uid'], 'automation_id' => $aid, 'sid' => $sid ] );
		if ( ! empty( $unsubscribe_link ) ) {
			$headers[] = "List-Unsubscribe: <$unsubscribe_link>";
			$headers[] = "List-Unsubscribe-Post: List-Unsubscribe=One-Click";
		}
		if ( empty( $subject ) ) {
			return array(
				'message' => __( 'Email subject missing. Please provide subject to send email.', 'wp-marketing-automations' ),
			);
		}
		if ( empty( $to ) ) {
			return array(
				'message' => __( 'Recipient email missing. Please provide email to send email.', 'wp-marketing-automations' ),
			);
		}

		/** Send Email */
		$global_settings = BWFAN_Common::get_global_settings();
		$emails          = explode( ',', $to );
		$emails          = array_map( function ( $email ) {
			return trim( $email );
		}, $emails );

		if ( true === $this->is_preview ) {
			$this->data['body'] = $this->preview_body;
		}

		$body = $this->data['body'];

		/** Set content type to prevent conflict with other plugins who are using 'wp_mail_content_type' filter */
		add_filter( 'wp_mail_content_type', array( $this, 'set_email_content_type' ), 999 );

		/**
		 * @todo optimize send email code
		 */
		$res           = false;
		$conversations = [];

		do_action( 'bwfan_before_send_email', $this->data, $body );
		/** this function will remove all wp_mail from_name and from_email filters  */
		$this->before_executing_automation();

		if ( ! isset( $global_settings['bwfan_email_service'] ) || 'wp' === $global_settings['bwfan_email_service'] ) {
			foreach ( $emails as $email ) {
				$this->data['email'] = $email;

				/** Modify email body for engagement tracking */
				if ( bwfan_is_autonami_pro_active() ) {
					$data_for_engagement            = $this->data;
					$data_for_engagement['subject'] = isset( $this->data['subject_raw'] ) && ! empty( $this->data['subject_raw'] ) ? $this->data['subject_raw'] : $this->data['subject'];
					$this->data['body']             = html_entity_decode( $this->data['body'] );
					$this->data['body']             = BWFAN_Core()->conversations->bwfan_modify_email_body_data( $this->data['body'], $data_for_engagement );
				} else {
					$this->data['body'] = BWFAN_Common::decode_merge_tags( $this->data['body'] );
				}

//				$this->data['body']  = BWFAN_Common::decode_merge_tags( $this->data['body'] );
				$this->data['body'] = apply_filters( 'bwfan_before_send_email_body', $this->data['body'], $this->data );
				$this->data['body'] = $this->email_content( $this->data );
				$this->data['body'] = BWFAN_Common::bwfan_correct_protocol_url( $this->data['body'] );
				$this->data['body'] = $this->append_to_email_body( $this->data['body'], $this->data['preheading'] );
				$this->set_log( 'before_email: ' . $email );
				$res = wp_mail( $email, $subject, $this->data['body'], $headers );
				$this->set_log( 'after_email: ' . $email );
				$this->data['body'] = $body; // Set the original body to use correct body in email.
				/** updating conversation only when the bwfan autonami pro is activated */
				if ( function_exists( 'bwfan_is_autonami_pro_active' ) && bwfan_is_autonami_pro_active() ) {
					$conversations[ $email ]['res']               = $res;
					$conversations[ $email ]['conversation_id']   = isset( $this->data['conversation_id'] ) ? $this->data['conversation_id'] : '';
					$conversations[ $email ]['hash_code']         = isset( $this->data['hash_code'] ) ? $this->data['hash_code'] : '';
					$conversations[ $email ]['subject_merge_tag'] = isset( $this->data['subject_merge_tag'] ) ? $this->data['subject_merge_tag'] : '';
				}
			}
		} else {
			// Every connector which registers itself for email service must have send_email() in its integration class.
			foreach ( $emails as $email ) {
				$this->data['email'] = $email;
				/** Modify email body for engagement tracking */
				if ( bwfan_is_autonami_pro_active() ) {
					$this->data['body'] = html_entity_decode( $this->data['body'] );
					$this->data['body'] = BWFAN_Core()->conversations->bwfan_modify_email_body_data( $this->data['body'], $this->data );
				} else {
					$this->data['body'] = BWFAN_Common::decode_merge_tags( $this->data['body'] );
				}

//				$this->data['body']     = BWFAN_Common::decode_merge_tags( $this->data['body'] );
				$this->data['body']     = apply_filters( 'bwfan_before_send_email_body', $this->data['body'], $this->data );
				$this->data['body']     = $this->email_content( $this->data );
				$this->data['body']     = BWFAN_Common::bwfan_correct_protocol_url( $this->data['body'] );
				$autonami_integrations  = BWFAN_Core()->integration->get_integrations();
				$selected_email_service = $global_settings['bwfan_email_service'];
				$this->set_log( 'before_email: ' . $email );
				$res = isset( $autonami_integrations[ $selected_email_service ] ) ? $autonami_integrations[ $selected_email_service ]->send_email( $email, $subject, $this->data['body'], $headers ) : wp_mail( $email, $subject, $this->data['body'], $headers );
				$this->set_log( 'after_email: ' . $email );
				$this->data['body'] = $body; // Set the original body to use correct body in email.
				$this->data['body'] = $this->append_to_email_body( $this->data['body'], $this->data['preheading'] );
				/** updating conversation only when the bwfan autonami pro is activated */
				if ( function_exists( 'bwfan_is_autonami_pro_active' ) && bwfan_is_autonami_pro_active() ) {
					$conversations[ $email ]['res']               = $res;
					$conversations[ $email ]['conversation_id']   = isset( $this->data['conversation_id'] ) ? $this->data['conversation_id'] : '';
					$conversations[ $email ]['hash_code']         = isset( $this->data['hash_code'] ) ? $this->data['hash_code'] : '';
					$conversations[ $email ]['subject_merge_tag'] = isset( $this->data['subject_merge_tag'] ) ? $this->data['subject_merge_tag'] : '';
				}
			}
		}

		remove_filter( 'wp_mail_content_type', array( $this, 'set_email_content_type' ), 999 );

		$return = true;
		if ( ! $res ) {
			$return = $this->maybe_get_failed_mail_error();
		}

		if ( ! isset( $this->data['test'] ) ) {
			do_action( 'bwfan_conversation_sendemail_action', $this, $body, $conversations );
		}
		$this->set_log( 'email_send_end: ' . $to );
		$this->log();

		return $return;
	}

	/**
	 * remove default wp mail filters before sending email
	 */
	public function before_executing_automation() {
		if ( ! class_exists( 'BWFAN_Compatibility_With_WP_SMTP' ) || ! method_exists( 'BWFAN_Compatibility_With_WP_SMTP', 'is_smart_routing_enabled' ) || ! BWFAN_Compatibility_With_WP_SMTP::is_smart_routing_enabled() ) {
			remove_all_filters( 'wp_mail' );
		}
		remove_all_filters( 'wp_mail_from' );
		remove_all_filters( 'wp_mail_from_name' );
		remove_all_filters( 'wp_mail_content_type' );
		remove_all_filters( 'wp_mail_charset' );
	}

	/**
	 * Append pre header in email body
	 *
	 * @param $body
	 * @param $pre_header
	 *
	 * @return string|string[]|null
	 */
	public function append_to_email_body( $body, $pre_header ) {

		if ( empty( $pre_header ) ) {
			return $body;
		}
		$pre_header = BWFAN_Common::decode_merge_tags( $pre_header );
		$pre_header = str_replace( "$", "\\$", $pre_header );
		$pre_header = '<div class="preheader" style="display:none;font-size:1px;color:#ffffff;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;">' . $pre_header . '</div>';

		/** it will add the space after the pre-header to not show the email body content */
		if ( true === apply_filters( 'bwfan_email_enable_pre_header_preview_only', false ) ) {
			$pre_header .= '<div style="display: none; max-height: 0; overflow: hidden;">&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;
							&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
							&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
							&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
							&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;&#847;&zwnj;&nbsp;
							&#847;&zwnj;&nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;
							&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;
							&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;
							&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;&#847; &zwnj; &nbsp;
							</div>';
		}

		$appended_body = $pre_header . ' ' . $body;
		if ( strpos( $body, '</body>' ) ) {
			$pattern       = '/<body(.*?)>(.*?)<\/body>/is';
			$replacement   = '<body$1>' . $pre_header . '$2</body>';
			$appended_body = preg_replace( $pattern, $replacement, $body );
		}

		return $appended_body;
	}

	public function maybe_get_failed_mail_error() {
		global $phpmailer;

		if ( ! class_exists( '\WPMailSMTP\MailCatcher' ) ) {
			return false;
		}

		if ( ! ( $phpmailer instanceof \WPMailSMTP\MailCatcher ) ) {
			return false;
		}

		$debug_log = get_option( 'wp_mail_smtp_debug', false );
		if ( empty( $debug_log ) || ! is_array( $debug_log ) ) {
			return false;
		}

		return array( 'message' => $debug_log[0] );
	}

	/**
	 * Execute the current action.
	 * Return 3 for successful execution , 4 for permanent failure.
	 *
	 * @param $action_data
	 *
	 * @return array
	 */
	public function execute_action( $action_data ) {
		global $wpdb;
		$this->set_data( $action_data['processed_data'] );
		$this->data['task_id'] = $action_data['task_id'];
		$sql_query             = 'Select meta_value FROM {table_name} WHERE bwfan_task_id = %d AND meta_key = %s';
		$sql_query             = $wpdb->prepare( $sql_query, $this->data['task_id'], 't_track_id' ); // WPCS: unprepared SQL OK
		$gids                  = BWFAN_Model_Taskmeta::get_results( $sql_query );
		$this->data['gid']     = '';

		if ( ! empty( $gids ) && is_array( $gids ) ) {
			foreach ( $gids as $gid ) {
				$this->data['gid'] = $gid['meta_value'];

			}
		}

		/** Checking contact status */
		$to          = trim( stripslashes( $this->data['email'] ) );
		$emails      = explode( ',', $to );
		$emails      = array_map( function ( $email ) {
			return trim( $email );
		}, $emails );
		$status      = null;
		$bwf_contact = bwf_get_contact( '', $emails[0] );
		if ( $bwf_contact instanceof WooFunnels_Contact ) {
			$status = $bwf_contact->get_status();
		}
		if ( 2 === intval( $status ) ) {
			return [
				'status'  => 4,
				'message' => __( 'Contact is bounced', 'wp-marketing-automations' )
			];
		}
		if ( 1 === absint( $this->data['promotional_email'] ) && ( false === apply_filters( 'bwfan_force_promotional_email', false, $this->data ) ) ) {
			$where             = array(
				'recipient' => $emails,
				'mode'      => 1,
			);
			$check_unsubscribe = BWFAN_Model_Message_Unsubscribe::get_message_unsubscribe_row( $where, false );
			if ( ! empty( $check_unsubscribe ) && is_array( $check_unsubscribe ) ) {
				$check_unsubscribe   = array_map( function ( $unsubscribe_row ) {
					return $unsubscribe_row['recipient'];
				}, $check_unsubscribe );
				$unsubscribed_emails = implode( ', ', array_unique( $check_unsubscribe ) );

				return array(
					'status'  => 4,
					'message' => __( 'User(s) are already unsubscribed, with email(s): ' . $unsubscribed_emails, 'wp-marketing-automations' ),
				);
			}
			if ( 1 !== intval( $status ) ) {
				return [
					'status'  => 4,
					'message' => __( 'Contact is not subscribed', 'wp-marketing-automations' )
				];
			}
		}
		$result = $this->process();
		if ( true === $result ) {
			return array(
				'status' => 3,
			);
		}

		if ( bwfan_is_autonami_pro_active() && BWFCRM_Core()->campaigns->maybe_daily_limit_reached() ) {
			return array(
				'status'  => 0,
				'message' => __( 'Daily Email Limit reached. Will retry after sometime' )
			);
		}

		if ( is_array( $result ) && isset( $result['message'] ) ) {
			return array(
				'status'  => 4,
				'message' => $result['message'],
			);
		}

		return array(
			'status'  => 4,
			'message' => __( 'Unknown Error occurred during Send Email', 'wp-marketing-automations' ),
		);
	}

	/**
	 * Process and do the actual processing for the current action.
	 * This function is present in every action class.
	 */
	public function process() {
		$is_required_fields_present = $this->check_fields( $this->data, $this->required_fields );
		if ( false === $is_required_fields_present ) {
			return $this->show_fields_error();
		}

		return $this->send_email();
	}

	public function set_email_content_type( $content_type ) {
		return 'text/html';
	}

	public function before_executing_task() {
		add_filter( 'bwfan_change_tasks_retry_limit', [ $this, 'modify_retry_limit' ], 99 );
		add_filter( 'woocommerce_email_footer_text', array( $this, 'add_unsubscribe_merge_tag' ) );
		add_filter( 'bwfan_unsubscribe_link', array( $this, 'add_unsubscribe_query_args' ) );
	}

	public function after_executing_task() {
		remove_filter( 'bwfan_change_tasks_retry_limit', [ $this, 'modify_retry_limit' ], 99 );
		remove_filter( 'woocommerce_email_footer_text', array( $this, 'add_unsubscribe_merge_tag' ) );
		remove_filter( 'bwfan_unsubscribe_link', array( $this, 'add_unsubscribe_query_args' ) );
	}

	public function modify_retry_limit( $retry_data ) {
		$retry_data[] = DAY_IN_SECONDS;

		return $retry_data;
	}

	public function add_email_preview_class( $classes ) {
		if ( isset( $_GET['section'] ) && 'preview_email' === $_GET['section'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$classes .= ' bwfan_preview_email';
		}

		return $classes;
	}

	public function get_fields_schema() {
		$schema = [
			[
				'id'                    => 'bwfan_email_to',
				'label'                 => __( 'To', 'wp-marketing-automations' ),
				'type'                  => 'text_with_button',
				'class'                 => '',
				'placeholder'           => 'Enter email',
				'hint'                  => '',
				'required'              => true,
				'toggler'               => array(),
				'automation_merge_tags' => true
			],
			[
				'id'       => 'bwfan_email_data',
				'type'     => 'emaileditor',
				'class'    => '',
				'required' => true,
				'toggler'  => array(),
			],
		];
		if ( ! bwfan_is_autonami_pro_active() ) {
			return $schema;
		}
		include BWFAN_PRO_PLUGIN_DIR . '/admin/class-bwfan-pro-admin.php';

		$lang_options  = [];
		$language_data = apply_filters( 'bwfan_automation_global_js_data', [] );
		if ( ! empty( $language_data ) && isset( $language_data['lang_options'] ) && ! empty( $language_data['lang_options'] ) ) {
			$lang_options = array_replace( [ '' => 'Select' ], $language_data['lang_options'] );
			$lang_options = BWFAN_Common::prepared_field_options( $lang_options );
		}

		if ( ! empty( $lang_options ) ) {
			$lang_options = [
				[
					'id'            => 'set_language',
					'checkboxlabel' => __( 'Perform this action for a particular language', 'wp-marketing-automations' ),
					'type'          => 'checkbox',
					'class'         => 'bwf-mt-minus-35',
					'required'      => false,
				],
				[
					'id'          => 'language',
					'type'        => 'select',
					'options'     => $lang_options,
					'label'       => __( 'Select Language', 'wp-marketing-automations' ),
					"class"       => 'bwfan-input-wrapper',
					"placeholder" => 'Select',
					"required"    => true,
					"errorMsg"    => 'Please select language.',
					"description" => "",
					'toggler'     => [
						'fields' => [
							[
								'id'    => 'set_language',
								'value' => true,
							]
						]
					]
				],
			];
			$schema       = array_merge( $schema, $lang_options );
		}

		return $schema;
	}

	/**
	 * Return default value of mail
	 *
	 * @return array
	 */
	public function get_default_values() {
		return [
			'bwfan_email_to' => '{{contact_email}}'
		];
	}

	/**
	 * Outputs WC template email body
	 *
	 * @param $data
	 *
	 * @return string
	 */
	protected function email_body_wc_template( $data ) {
		$email_body    = $data['body'];
		$email_heading = $data['email_heading'];

		// If promotional checkbox is not checked, then remove {{unsubscribe_link}} merge tag
		if ( isset( $data['promotional_email'] ) && 0 === absint( $data['promotional_email'] ) ) {
			remove_filter( 'woocommerce_email_footer_text', array( $this, 'add_unsubscribe_merge_tag' ) );
		}

		ob_start();
		do_action( 'bwfan_output_email_style' ); // for registering the css
		$css = ob_get_clean();

		$email_body = $this->emogrifier_parsed_output( $css, $email_body );

		$email_abstract_object = new WC_Email();
		ob_start();

		do_action( 'woocommerce_email_header', $email_heading, $email_abstract_object );

		echo $email_body; //phpcs:ignore WordPress.Security.EscapeOutput

		do_action( 'woocommerce_email_footer', $email_abstract_object );

		$email_body = ob_get_clean();

		return apply_filters( 'woocommerce_mail_content', $email_abstract_object->style_inline( wptexturize( $email_body ) ) );
	}

	public function get_desc_text( $data ) {
		$data = json_decode( wp_json_encode( $data ), true );
		if ( ! is_array( $data ) || ! isset( $data['bwfan_email_data'] ) || ! isset( $data['bwfan_email_data']['subject'] ) || empty( $data['bwfan_email_data']['subject'] ) ) {
			return '';
		}

		return $data['bwfan_email_data']['subject'];
	}

	public function set_log( $log ) {
		if ( empty( $log ) ) {
			return;
		}
		$this->logs[] = array(
			't' => microtime( true ),
			'm' => $log,
		);
	}

	protected function log() {
		if ( ! is_array( $this->logs ) || 0 === count( $this->logs ) ) {
			return;
		}
		if ( false === apply_filters( 'bwfan_allow_broadcast_logging', false ) ) {
			return;
		}

		BWFAN_Common::log_test_data( $this->logs, 'send_email', true );
		$this->logs = [];
	}
}

/**
 * Register this action. Registering the action will make it eligible to see it on single automation screen in select actions dropdown.
 */
return 'BWFAN_Wp_Sendemail';
