<?php

#namespace WilokeTest;
use WP_REST_Response;

/**
 * Class AjaxMessage
 * @package HSBlogCore\Helpers
 */
class RestMessage extends AbstractMessage
{
	/**
	 * @param       $msg
	 * @param       $code
	 * @param null $aAdditional
	 *
	 * @return WP_REST_Response
	 */
	public function retrieve($msg, $code, $aAdditional = null): WP_REST_Response
	{
		if ($code == 200) {
			return $this->success($msg, $aAdditional);
		} else {
			return $this->error($msg, $code);
		}
	}

	public function response( array $aResponse ): WP_REST_Response {
		if ( $aResponse['status'] === 'success' ) {
			return $this->success( $aResponse['message'], $aResponse['data'] ?? null );
		} else {
			return $this->error( $aResponse['message'], $aResponse['code'], $aResponse['data'] ?? null );
		}
	}

	/**
	 * @param       $msg
	 * @param null $aAdditional
	 *
	 * @return WP_REST_Response
	 */
	public function success($msg, $aAdditional = null): WP_REST_Response
	{
		return (new WP_REST_Response($this->handleSuccess($msg, $aAdditional), 200));
	}

	/**
	 * @param $msg
	 * @param $code
	 * @param null $aAdditional
	 * @return WP_REST_Response
	 */
	public function error($msg, $code, $aAdditional = null): WP_REST_Response
	{
		return new WP_REST_Response($this->handleError($msg, $code, $aAdditional), $code);
	}
}
