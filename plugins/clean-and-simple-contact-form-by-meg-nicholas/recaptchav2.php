<?php
if ( ! class_exists( 'csf_ReCaptchaResponse' ) ) {
	class csf_ReCaptchaResponseV2 {
		public $success;
		public $errorCodes;
	}
}
if ( ! class_exists( 'csf_RecaptchaV2' ) ) {
	class csf_RecaptchaV2 {
		const DESCRIPTION = 'Recaptcha Version 2';

		public $RecaptchaTheme = 'light';

		static $signUpUrl = 'https://www.google.com/recaptcha/admin';
		static $siteVerifyUrl = 'https://www.google.com/recaptcha/api/siteverify?';

		/**
		 * Encodes the given data into a query string format.
		 *
		 * @param array $data array of string elements to be encoded.
		 *
		 * @return string - encoded request.
		 */
		static function EncodeQS( $data ) {
			$req = "";
			foreach ( $data as $key => $value ) {
				$req .= $key . '=' . urlencode( stripslashes( $value ) ) . '&';
			}
			// Cut the last '&'
			$req = substr( $req, 0, strlen( $req ) - 1 );

			return $req;
		}

		/**
		 * Submits an HTTP GET to a reCAPTCHA server.
		 *
		 * @param string $path url path to recaptcha server.
		 * @param array $data array of parameters to be sent.
		 *
		 * @return array response
		 */
		static function SubmitHTTPGet( $path, $data ) {
			$req      = self::EncodeQS( $data );
			$response = file_get_contents( $path . $req );

			return $response;
		}


		/**
		 * Calls the reCAPTCHA siteverify API to verify whether the user passes
		 * CAPTCHA test.
		 *
		 * @param string $remoteIp IP address of end user.
		 * @param string $secret google recaptcha secret key.
		 * @param string $response response string from recaptcha verification.
		 *
		 * @return ReCaptchaResponse
		 */
		static function VerifyResponse( $remoteIp, $secret, $response ) {
			// Discard empty solution submissions
			if ( $response == null || strlen( $response ) == 0 ) {
				$recaptchaResponse             = new csf_ReCaptchaResponseV2();
				$recaptchaResponse->success    = false;
				$recaptchaResponse->errorCodes = 'missing-input';

				return $recaptchaResponse;
			}
			$getResponse       = self::SubmitHttpGet(
				self::$siteVerifyUrl,
				array(
					'secret'   => $secret,
					'remoteip' => $remoteIp,
					'response' => $response
				)
			);
			$answers           = json_decode( $getResponse, true );
			$recaptchaResponse = new csf_ReCaptchaResponseV2();
			if ( trim( $answers ['success'] ) == true ) {
				$recaptchaResponse->success = true;
			} else {
				$recaptchaResponse->success    = false;
				$recaptchaResponse->errorCodes = $answers ['error-codes'];
			}

			return $recaptchaResponse;
		}

	}

}