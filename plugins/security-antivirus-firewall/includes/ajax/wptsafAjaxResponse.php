<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

final class wptsafAjaxResponse {
	const MESSAGE_TYPE_SUCCESS = 'success';
	const MESSAGE_TYPE_INFO = 'info';
	const MESSAGE_TYPE_WARNING = 'warning';
	const MESSAGE_TYPE_DANGER = 'danger';

	private $response = null;
	private $messages = array();
	private $errors = array();
	private $jsCallbacks = array();

	public function setResponse($response) {
		$this->response = $response;
	}

	public function getResponse() {
		return $this->response;
	}

	public function addMessage($message, $type = self::MESSAGE_TYPE_SUCCESS) {
		$this->messages[] = array(
			'type' => $type,
			'text' => $message,
		);
	}

	public function getMessages() {
		return $this->messages;
	}

	public function addError($error) {
		$this->errors[] = $error;
	}

	public function getErrors() {
		return $this->errors;
	}

	public function addJsCallback($jsFunction) {
		if (!in_array($jsFunction, $this->jsCallbacks)) {
			$this->jsCallbacks[] = $jsFunction;
		}
	}

	public function getJsCallbacks() {
		return $this->jsCallbacks;
	}

	public function sendJson() {
		if (is_wp_error($this->response)) {
			$this->addMessage($this->response, self::MESSAGE_TYPE_DANGER);
			$this->setResponse( null );
		}

		foreach ($this->errors as $error) {
			$error = $this->getErrorStrings($error);
			if (is_array($error)) {
				foreach ($error as $stringError) {
					$this->addMessage($stringError, self::MESSAGE_TYPE_DANGER);
				}
			} else {
				$this->addMessage($error, self::MESSAGE_TYPE_DANGER);
			}
		}

		$data = array(
			'response'      => $this->response,
			'messages'      => $this->messages,
			'jsCallbacks' => $this->jsCallbacks
		);

		wp_send_json($data);
	}

	public function getErrorStrings($error) {
		if (is_string($error)) {
			return array( $error );
		} else if ( is_a( $error, 'WP_Error' ) ) {
			/** @var WP_Error $error */
			/* translators: 1: error message, 2: error code */
			$format = __( '%1$s <span class="wptsaf-security-error-code">(%2$s)</span>', 'rmse-security' );
			$errors = array();

			foreach ( $error->get_error_codes() as $code ) {
				$message = implode( ' ', (array) $error->get_error_messages( $code ) );
				$errors[] = sprintf( $format, $message, $code ) . ' ';
			}

			return $errors;
		} else if ( is_array( $error ) ) {
			$errors = array();

			foreach ( $error as $error_item ) {
				$new_errors = self::getErrorStrings( $error_item );
				$errors = array_merge( $errors, $new_errors );
			}

			return $errors;
		}

		/* translators: 1: variable type */
		return array(sprintf( __( 'Unknown error type received: %1$s.', 'wptsaf-security'), gettype($error)));
	}
}
