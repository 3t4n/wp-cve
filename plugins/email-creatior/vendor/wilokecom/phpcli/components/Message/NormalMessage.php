<?php

#namespace WilokeTest;

/**
 * Class AjaxMessage
 * @package HSBlogCore\Helpers
 */
class NormalMessage extends AbstractMessage
{
	/**
	 * @param       $msg
	 * @param       $code
	 * @param null $aAdditional
	 *
	 * @return array
	 */
	public function retrieve($msg, $code, $aAdditional = []): array
	{
		if ($code == 200) {
			return $this->success($msg, $aAdditional);
		} else {
			return $this->error($msg, $code, $aAdditional);
		}
	}

	public function response( array $aResponse ): array {
		if ( $aResponse['status'] === 'success' ) {
			return $this->success( $aResponse['message'], $aResponse['data'] ?? null );
		} else {
			return $this->error( $aResponse['message'], $aResponse['code'], $aResponse['data'] ?? null );
		}
	}

	public function success($msg, $aAdditional = null)
	{
		return $this->handleSuccess($msg, $aAdditional);
	}

	public function error($msg, $code, $aAdditional = null)
	{
		return $this->handleError($msg, $code, $aAdditional);
	}
}
