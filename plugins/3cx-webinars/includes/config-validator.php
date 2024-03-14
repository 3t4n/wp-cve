<?php

class WP3CXW_ConfigValidator {

	const error = 100;
	const error_api_token_invalid = 110;
	const error_portal_fqdn_invalid = 111;
	const error_extension_invalid = 112;

	private $webinar_form;
	private $errors = array();

	public function __construct( WP3CXW_WebinarForm $webinar_form ) {
		$this->webinar_form = $webinar_form;
	}

	public function webinar_form() {
		return $this->webinar_form;
	}

	public function is_valid() {
		return ! $this->count_errors();
	}

	public function count_errors( $args = '' ) {
		$args = wp_parse_args( $args, array(
			'section' => '',
			'code' => '',
		) );

		$count = 0;

		foreach ( $this->errors as $key => $errors ) {
			if ( preg_match( '/^mail_[0-9]+\.(.*)$/', $key, $matches ) ) {
				$key = sprintf( 'mail.%s', $matches[1] );
			}

			if ( $args['section']
			&& $key != $args['section']
			&& preg_replace( '/\..*$/', '', $key, 1 ) != $args['section'] ) {
				continue;
			}

			foreach ( $errors as $error ) {
				if ( empty( $error ) ) {
					continue;
				}

				if ( $args['code'] && $error['code'] != $args['code'] ) {
					continue;
				}

				$count += 1;
			}
		}

		return $count;
	}

	public function collect_error_messages() {
		$error_messages = array();

		foreach ( $this->errors as $section => $errors ) {
			$error_messages[$section] = array();

			foreach ( $errors as $error ) {
				if ( empty( $error['args']['message'] ) ) {
					$message = $this->get_default_message( $error['code'] );
				} elseif ( empty( $error['args']['params'] ) ) {
					$message = $error['args']['message'];
				} else {
					$message = $this->build_message(
						$error['args']['message'],
						$error['args']['params'] );
				}

				$link = '';

				if ( ! empty( $error['args']['link'] ) ) {
					$link = $error['args']['link'];
				}

				$error_messages[$section][] = array(
					'message' => $message,
					'link' => esc_url( $link ),
				);
			}
		}

		return $error_messages;
	}

	public function build_message( $message, $params = '' ) {
		$params = wp_parse_args( $params, array() );

		foreach ( $params as $key => $val ) {
			if ( ! preg_match( '/^[0-9A-Za-z_]+$/', $key ) ) { // invalid key
				continue;
			}

			$placeholder = '%' . $key . '%';

			if ( false !== stripos( $message, $placeholder ) ) {
				$message = str_ireplace( $placeholder, $val, $message );
			}
		}

		return $message;
	}

	public function get_default_message( $code ) {
		switch ( $code ) {
			case self::error_api_token_invalid:
				return __( "3CX API Token is not valid.", '3cx-webinar' );
			case self::error_portal_fqdn_invalid:
				return __( "3CX Public HTTPS URL is not valid.", '3cx-webinar' );
			case self::error_extension_invalid:
				return __( "3CX Extension Number is not valid.", '3cx-webinar' );
			default:
				return '';
		}
	}

	public function add_error( $section, $code, $args = '' ) {
		$args = wp_parse_args( $args, array(
			'message' => '',
			'params' => array(),
		) );

		if ( ! isset( $this->errors[$section] ) ) {
			$this->errors[$section] = array();
		}

		$this->errors[$section][] = array( 'code' => $code, 'args' => $args );

		return true;
	}

	public function remove_error( $section, $code ) {
		if ( empty( $this->errors[$section] ) ) {
			return;
		}

		foreach ( (array) $this->errors[$section] as $key => $error ) {
			if ( isset( $error['code'] ) && $error['code'] == $code ) {
				unset( $this->errors[$section][$key] );
			}
		}
	}

	public function validate() {
		$this->errors = array();

		$this->validate_config();

		do_action( 'wp3cxw_config_validator_validate', $this );

		return $this->is_valid();
	}

	public function save() {
		if ( $this->webinar_form->initial() ) {
			return;
		}

		delete_post_meta( $this->webinar_form->id(), '_config_errors' );

		if ( $this->errors ) {
			update_post_meta( $this->webinar_form->id(), '_config_errors',
				$this->errors );
		}
	}

	public function restore() {
		$config_errors = get_post_meta(
			$this->webinar_form->id(), '_config_errors', true );

		foreach ( (array) $config_errors as $section => $errors ) {
			if ( empty( $errors ) ) {
				continue;
			}

			if ( ! is_array( $errors ) ) { // for back-compat
				$code = $errors;
				$this->add_error( $section, $code );
			} else {
				foreach ( (array) $errors as $error ) {
					if ( ! empty( $error['code'] ) ) {
						$code = $error['code'];
						$args = isset( $error['args'] ) ? $error['args'] : '';
						$this->add_error( $section, $code, $args );
					}
				}
			}
		}
	}
	
	public function validate_config() {
		$template = 'config';
		$components = (array) $this->webinar_form->prop( $template );

		if ( ! $components ) {
			return;
		}

		$components = wp_parse_args( $components, array(
    'active' => false,
		'apitoken' => '',
    'cache_expiry'=> 5,
		'portalfqdn' => '',
		'extension' => '',
		'country' => '',
		'maxparticipants' => 0,
    'subject' => '',
    'days' => 0
		) );

		$apitoken = trim($components['apitoken']);
		$portalfqdn = trim($components['portalfqdn']);
		$extension = wp3cxw_sanitize_extension(trim($components['extension']));

		if (strlen($apitoken)!=64) {
			$this->add_error( sprintf( '%s.apitoken', $template ),
				self::error_api_token_invalid, array(
				)
			);
		}
		
    if (empty($portalfqdn) || esc_url_raw( $portalfqdn, array('https'))!=$portalfqdn) {
			$this->add_error( sprintf( '%s.portalfqdn', $template ),
				self::error_portal_fqdn_invalid, array(
				)
			);
		}
		
		if ($extension!=$components['extension']){
			$this->add_error( sprintf( '%s.extension', $template ),
				self::error_extension_invalid, array(
				)
			);
		}
	}	

}
