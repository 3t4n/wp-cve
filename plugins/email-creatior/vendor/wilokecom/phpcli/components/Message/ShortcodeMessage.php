<?php

#namespace WilokeTest;

/**
 * Class AjaxMessage
 * @package HSBlogCore\Helpers
 */
class ShortcodeMessage extends AbstractMessage
{
	public function response( array $aResponse ): string {
		if ( $aResponse['status'] === 'success' ) {
			return $this->success( $aResponse['message'], $aResponse['data'] ?? null );
		} else {
			return $this->error( $aResponse['message'], $aResponse['code'], $aResponse['data'] ?? null );
		}
	}

	/**
	 * @param       $msg
	 * @param       $code
	 * @param null $aAdditional
	 *
	 * @return string
	 */
	public function retrieve($msg, $code, $aAdditional = null): string
	{
		if ($code == 200) {
			return $this->success($msg, $aAdditional);
		} else {
			return $this->error($msg, $code);
		}
	}

	/**
	 * @param       $msg
	 * @param null $aAdditional
	 *
	 * @return string
	 */
	public function success($msg, $aAdditional = null): string
	{
		return '%SC%' . json_encode($this->handleSuccess($msg, $aAdditional)) . '%SC%';
	}

	/**
	 * @param $msg
	 * @param $code
	 *
	 * @return string
	 */
	public function error($msg, $code, $aAdditional = null): string
	{
		return '%SC%' . json_encode($this->handleError($msg, $code, $aAdditional)) . '%SC%';
	}
}
