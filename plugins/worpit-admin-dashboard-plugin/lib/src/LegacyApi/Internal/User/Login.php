<?php

namespace FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi\Internal\User;

use FernleafSystems\Wordpress\Plugin\iControlWP\LegacyApi;

class Login extends LegacyApi\Internal\Base {

	const LoginTokenKey = 'icwplogintoken';

	public function process() :LegacyApi\ApiResponse {
		$source = home_url().'$'.\uniqid().'$'.\time();
		$token = hash( 'sha256', $source );

		$this->loadWP()
			 ->setTransient(
				 self::LoginTokenKey,
				 [
					 'token'    => $token,
					 'redirect' => $this->getActionParam( 'redirect', '' )
				 ],
				 \MINUTE_IN_SECONDS
			 );

		return $this->success( [
			'source' => $source,
			'token'  => $token
		] );
	}
}