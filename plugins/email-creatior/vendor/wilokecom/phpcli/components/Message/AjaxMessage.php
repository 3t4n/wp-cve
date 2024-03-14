<?php

#namespace WilokeTest;

/**
 * Class AjaxMessage
 * @package HSBlogCore\Helpers
 */
class AjaxMessage extends AbstractMessage
{
	/**
	 * @param       $msg
	 * @param       $code
	 * @param null $aAdditional
	 *
	 * @return void
	 */
	public function retrieve($msg, $code, $aAdditional = null)
	{
		if ($code == 200) {
			$this->success($msg, $aAdditional);
		} else {
			$this->error($msg, $code, $aAdditional);
		}
	}

	public function response( array $aResponse ) {
		if ( $aResponse['status'] === 'success' ) {
			$this->success( $aResponse['message'], $aResponse['data'] ?? null );
		} else {
			$this->error( $aResponse['message'], $aResponse['code'], $aResponse['data'] ?? null );
		}
	}

	private function sendJson(array $aMessage, $statusCode)
	{
		if (!headers_sent()) {
			header('Content-Type: application/json; charset=' . get_option('blog_charset'));
			if (null !== $statusCode) {
				status_header($statusCode);
			}
		}

		echo wp_json_encode($aMessage);

		die;
	}

	/**
	 * @param       $msg
	 * @param null $aAdditional
	 *
	 * @return void
	 */
	public function success($msg, $aAdditional = null)
	{
		$this->sendJson($this->handleSuccess($msg, $aAdditional), 200);
	}

	/**
	 * @param       $msg
	 * @param null $aAdditional
	 * @param       $code
	 *
	 * @return void
	 */
	public function error($msg, $code, $aAdditional = null)
	{
		$this->sendJson($this->handleError($msg, $code, $aAdditional), $code);
	}
}
