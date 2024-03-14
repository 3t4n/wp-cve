<?php


class arflitespamfiltercontroller {
		const nonce_action         = 'form_spam_filter';
		const nonce_name           = 'arm_nonce_check';
		const nonce_start_time     = 'form_filter_st';
		const nonce_keyboard_press = 'form_filter_kp';

		var $nonce_fields;

	function __construct() {
		add_filter( 'arflite_is_to_validate_spam_filter', array( $this, 'arflite_check_spam_filter_fields' ), 10, 2 );
		add_shortcode( 'arflite_spam_filters', array( $this, 'arflite_spam_filters_func' ) );
		add_filter( 'arflite_reset_built_in_captcha', array( $this, 'arflite_reset_built_in_captcha_key' ), 10, 2 );
		add_action( 'wp_ajax_arflite_generate_captcha', array( $this, 'arflite_generate_captcha' ), 10 );
		add_action( 'wp_ajax_nopriv_arflite_generate_captcha', array( $this, 'arflite_generate_captcha' ), 10 );
	}

	function arflite_generate_captcha() {
		global $arformsmain;

		$hidden_captcha = $arformsmain->arforms_get_settings('hidden_captcha','general_settings');
		$hidden_captcha = !empty( $hidden_captcha ) ? $hidden_captcha : false;
		if ( 1 == $hidden_captcha ) {
			die;
		}

		global $maincontroller;

		if ( ! session_id() ) {
			global $arflitemaincontroller;
			$arflitemaincontroller->arflite_start_session( true );
		}

		$form_ids = ! empty( $_POST['form_ids'] ) ? explode( ',', sanitize_text_field( $_POST['form_ids'] ) ) : array(); //phpcs:ignore

		if ( empty( $form_ids ) ) {
			die;
		}

		$form_ids = array_map( 'sanitize_text_field', $form_ids );

		global $arflitemainhelper;

		$return_arr = array();

		foreach ( $form_ids as $frm_data_id ) {

			$frm_data = explode( '_', $frm_data_id );

			$form_id = $frm_data[0];

			$formRandomID = $form_id . '_' . $arflitemainhelper->arflite_generate_captcha_code( '10' );

			$captcha_code = $arflitemainhelper->arflite_generate_captcha_code( '8' );

			 $_SESSION['ARFLITE_VALIDATE_SCRIPT']               = true;
			 $_SESSION['ARFLITE_FILTER_INPUT'][ $formRandomID ] = $captcha_code;

			$return_arr[ $frm_data_id ] = array(
				'data_random_id'      => $formRandomID,
				'data_submission_key' => $captcha_code,
				'data_key_validate'   => false,
			);
		}

		echo json_encode( $return_arr );
		die;
	}

	function arflite_reset_built_in_captcha_key( $return, $post_val ) {
		global $arformsmain;

		if ( ! isset( $_SESSION ) ) {
			global $arflitemaincontroller;
			$arflitemaincontroller->arflite_start_session( true );
		}

		$hidden_captcha = $arformsmain->arforms_get_settings('hidden_captcha','general_settings');
		$hidden_captcha = !empty( $hidden_captcha ) ? $hidden_captcha : false;

		if ( 1 == $hidden_captcha ) {
			return $return;
		}
		if ( empty( $post_val ) ) {
			$return['recaptcha_key'] = '';
		} else {
			global $arflitemainhelper;
			$form_id          = intval( $post_val['form_id'] );
			$frm_id           = isset( $post_val['form_random_key'] ) ? sanitize_text_field( $post_val['form_random_key'] ) : '';
			$possible_letters = '23456789bcdfghjkmnpqrstvwxyz';
			$random_dots      = 0;
			$random_lines     = 20;

			$session_var = '';
			$i           = 0;
			while ( $i < 8 ) {
				$session_var .= substr( $possible_letters, mt_rand( 0, strlen( $possible_letters ) - 1 ), 1 );
				$i++;
			}
			$_SESSION['ARFLITE_FILTER_INPUT'][ $frm_id ] = $session_var;
			$return['recaptcha_key']                     = base64_encode( $session_var . '~|~' . $form_id . '~|~' . $frm_id );
		}
		return $return;
	}

	function arflite_check_spam_filter_fields( $validate = true, $form_key = '' ) {
		global $arformsmain;

		if ( ! isset( $_SESSION ) ) {
			global $arflitemaincontroller;
			$arflitemaincontroller->arflite_start_session( true );
		}

		$hidden_captcha = $arformsmain->arforms_get_settings('hidden_captcha','general_settings');
		$hidden_captcha = isset( $hidden_captcha ) ? $hidden_captcha : false;

		if ( 1 == $hidden_captcha ) {
			return true;
		}
		$is_form_key = $arf_is_removed_field = true;

		if ( ! isset( $_SESSION['ARFLITE_FILTER_INPUT'] ) && isset( $_SESSION['ARFLITE_VALIDATE_SCRIPT'] ) && $_SESSION['ARFLITE_VALIDATE_SCRIPT'] == true ) {
			$arf_is_removed_field = false;
		}

		if ( $form_key == '' || ( isset( $_SESSION['ARFLITE_FILTER_INPUT'] ) && ! array_key_exists( $form_key, $_SESSION['ARFLITE_FILTER_INPUT'] ) ) ) {
			$is_form_key = false;
		}

		$field_name = isset( $_SESSION['ARFLITE_FILTER_INPUT'][ $form_key ] ) ? sanitize_text_field( $_SESSION['ARFLITE_FILTER_INPUT'][ $form_key ] ) : '';

		if ( isset( $_REQUEST[ $field_name ] ) ) {
			$field_value          = sanitize_text_field( $_REQUEST[ $field_name ] );
			$arf_is_dynamic_field = true;
			if ( $field_value != '' || ! empty( $field_value ) || $field_value != null ) {
				$arf_is_dynamic_field = false;
			}
		} else {
			$arf_is_dynamic_field = false;
		}

		$is_removed_field_exists = false;
		if ( isset( $_REQUEST['arf_filter_input'] ) || isset( $_POST['arf_filter_input'] ) || isset( $_GET['arf_filter_input'] ) ) { //phpcs:ignore
			$arf_is_removed_field    = false;
			$is_removed_field_exists = true;
		}

		unset( $_SESSION['ARFLITE_FILTER_INPUT'][ $form_key ] );

		if ( ! isset( $_SESSION['ARFLITE_VALIDATE_SCRIPT'] ) || $_SESSION['ARFLITE_VALIDATE_SCRIPT'] == false ) {
			$arf_is_dynamic_field = true;
			$is_form_key          = true;
		}

		$validateNonce = $validateReferer = $in_time = $is_user_keyboard = false;
		if ( isset( $_REQUEST ) && isset( $_REQUEST[ self::nonce_name ] ) ) {
			$referer = $this->arflitevalidateReferer();
			if ( $referer['pass'] === true && $referer['hasReferrer'] === true ) {
				$validateReferer = true;
			}
			$in_time          = $this->arflitevalidateTimedFormSubmission();
			$is_user_keyboard = $this->arflitevalidateUsedKeyboard();
		}
		$validateNonce = true;

		if ( $validateNonce && $validateReferer && $in_time && $is_user_keyboard && $is_form_key && $arf_is_dynamic_field && $arf_is_removed_field ) {
			$validate = true;
		} elseif ( ! $is_user_keyboard && $validateNonce && $validateReferer && $in_time && $is_form_key && $arf_is_dynamic_field && $arf_is_removed_field ) {
			$validate = true;
		} else {
			$validate = false;
		}
		
		return $validate;
	}

	function arflitevalidateReferer() {
		if ( isset( $_SERVER['HTTPS'] ) ) {
			$protocol = 'https://';
		} else {
			$protocol = 'http://';
		}
		$arfhttphost = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field($_SERVER['HTTP_HOST']) : '';
		$arfscriptname = isset( $_SERVER['SCRIPT_NAME'] ) ? sanitize_text_field($_SERVER['SCRIPT_NAME']) : '';
		$absurl                = $protocol . $arfhttphost . $arfscriptname;
		$absurlParsed          = parse_url( $absurl );
		$result['pass']        = false;
		$result['hasReferrer'] = false;
		$httpReferer           = !empty( $_SERVER['HTTP_REFERER'])  ? esc_url_raw($_SERVER['HTTP_REFERER']) : '';
		if ( isset( $httpReferer ) ) {
			$refererParsed = parse_url( $httpReferer );
			if ( isset( $refererParsed['host'] ) ) {
				$result['hasReferrer'] = true;
				$absUrlRegex           = '/' . strtolower( $absurlParsed['host'] ) . '/';
				$isRefererValid        = preg_match( $absUrlRegex, strtolower( $refererParsed['host'] ) );
				if ( $isRefererValid == 1 ) {
					$result['pass'] = true;
				}
			} else {
				$result['status'] = 'Absolute URL: ' . $absurl . ' Referer: ' . $httpReferer;
			}
		} else {
			$result['status'] = 'Absolute URL: ' . $absurl . ' Referer: ' . $httpReferer;
		}
		return $result;
	}

	function arflitevalidateTimedFormSubmission( $formContents = array() ) {
		$in_time = false;
		if ( empty( $formContents[ self::nonce_start_time ] ) ) {
			$formContents[ self::nonce_start_time ] = isset( $_REQUEST[ self::nonce_start_time ] ) ? sanitize_text_field( $_REQUEST[ self::nonce_start_time ] ) : '';
		}
		if ( isset( $formContents[ self::nonce_start_time ] ) ) {
			$displayTime = $formContents[ self::nonce_start_time ] - 14921;
			$submitTime  = time();
			$fillOutTime = $submitTime - $displayTime;
			if ( $fillOutTime < 3 ) {
				$in_time = false;
			} else {
				$in_time = true;
			}
		}
		return $in_time;
	}
	function arflitevalidateUsedKeyboard( $formContents = array() ) {
		$is_user_keyboard = false;
		if ( empty( $formContents[ self::nonce_keyboard_press ] ) ) {
			$formContents[ self::nonce_keyboard_press ] = isset( $_REQUEST[ self::nonce_keyboard_press ] ) ? sanitize_text_field( $_REQUEST[ self::nonce_keyboard_press ] ) : '';
		}
		if ( isset( $formContents[ self::nonce_keyboard_press ] ) ) {
			if ( is_numeric( $formContents[ self::nonce_keyboard_press ] ) !== false ) {
				$is_user_keyboard = true;
			}
		}
		return $is_user_keyboard;
	}

	function arflite_spam_filters_func( $atts, $content = '' ) {
		global $arformsmain;

		$hidden_captcha = $arformsmain->arforms_get_settings('hidden_captcha','general_settings');
		$hidden_captcha = !empty( $hidden_captcha ) ? $hidden_captcha : false;

		if ( 1 == $hidden_captcha ) {
			return '';
		}
		$defaults = array(
			'var' => '',
		);
		$opts     = shortcode_atts( $defaults, $atts, 'spam_filters' );
		extract( $opts );
		
		$content .= $this->arflite_add_form_fields();

		return do_shortcode( $content );
	}

	function arflite_add_form_fields() {
		$this->nonce_fields  = '<input type="hidden" data-jqvalidate="false" class="kpress" value="" />';
		$this->nonce_fields .= '<input type="hidden" data-jqvalidate="false" class="stime" value="' . ( time() + 14921 ) . '" />';
		$this->nonce_fields .= '<input type="hidden" data-jqvalidate="false" data-id="nonce_start_time" class="nonce_start_time" value="' . self::nonce_start_time . '" />';
		$this->nonce_fields .= '<input type="hidden" data-jqvalidate="false" data-id="nonce_keyboard_press" class="nonce_keyboard_press" value="' . self::nonce_keyboard_press . '" />';
		$this->nonce_fields .= '<input type="hidden" data-jqvalidate="false" data-id="' . self::nonce_name . '" name="' . self::nonce_name . '" value="' . wp_create_nonce( self::nonce_action ) . '" />';
		return $this->nonce_fields;
	}

}
