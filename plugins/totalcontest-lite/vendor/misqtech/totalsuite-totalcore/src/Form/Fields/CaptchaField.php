<?php

namespace TotalContestVendors\TotalCore\Form\Fields;

use TotalContestVendors\TotalCore\Form\Field as FieldAbstract;

/**
 * Class CaptchaField
 * @package TotalContestVendors\TotalCore\Form\Fields
 */
class CaptchaField extends FieldAbstract {
	/**
	 * @return mixed
	 */
	public function getInputHtmlElement() {
		$field = new \TotalContestVendors\TotalCore\Helpers\Html(
			'div',
			[ 'class' => 'g-recaptcha', 'data-sitekey' => $this->getOption( 'key' ), 'data-size' => $this->getOption( 'invisible', false ) ? 'invisible' : '' ]
		);

		return $field;
	}

	/**
	 * @param array $rules
	 *
	 * @return array|bool
	 */
	public function validate( $rules = [] ) {
		if ( $this->getOption( 'key' ) && $this->getOption( 'secret' ) ):
			$response   = \TotalContestVendors\TotalCore\Application::get( 'http.request' )->request( 'g-recaptcha-response', '' );
			$siteSecret = $this->getOption( 'secret' );
			$ip         = \TotalContestVendors\TotalCore\Application::get( 'http.request' )->ip();
			$valid      = false;

			if ( $siteSecret ):
				$curlHandle = curl_init();
				// set URL and other appropriate options
				curl_setopt( $curlHandle, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify?secret={$siteSecret}&response={$response}&remoteip={$ip}" );
				curl_setopt( $curlHandle, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $curlHandle, CURLOPT_TIMEOUT, '10' );
				//curl_setopt( $curlHandle, CURLOPT_SSL_VERIFYPEER, false );

				$valid     = json_decode( curl_exec( $curlHandle ), true );
				$curlError = curl_error( $curlHandle );

			endif;

			if ( ! $valid || ! is_array( $valid ) || ! array_key_exists( 'success', $valid ) || $valid['success'] == false || ! empty( $curlError ) ):
				$this->errors['invalid'] = __( 'You have entered an invalid captcha code.', \TotalContestVendors\TotalCore\Application::getInstance()->env( 'slug' ) );
			endif;
		endif;

		return empty( $this->errors ) ? true : $this->errors;
	}

	public function render( $purgeCache = false ) {
		wp_enqueue_script( 'google-recaptcha', 'https://www.google.com/recaptcha/api.js' );

		return parent::render( $purgeCache );
	}
}