<?php

#namespace WilokeTest;

/**
 * Class
 * @package HSBlogCore\Helpers
 */
abstract class AbstractMessage
{
	/**
	 * @param       $msg
	 * @param       $code
	 * @param  $aAdditional
	 *
	 * @return mixed
	 */
	abstract public function retrieve($msg, $code, $aAdditional = null);

	abstract public function response( array $aResponse );

	/**
	 * @param       $msg
	 * @param null $aAdditional
	 *
	 * @return mixed
	 */
	abstract public function success($msg, $aAdditional = null);

	/**
	 * @param $msg
	 * @param $code
	 * @param null $aAdditional
	 * @return mixed
	 */
	abstract public function error($msg, $code, $aAdditional = null);

	/**
	 * @param       $msg
	 * @param null $aAdditional
	 *
	 * @return array
	 */
	protected function handleSuccess($msg, $aAdditional = null): array
	{
		$aData = [
			'message' => $msg,
			'status'  => 'success'
		];

		return array_merge(['data' => $aAdditional], $aData);
	}

	/**
	 * @param       $msg
	 * @param       $code
	 * @param null $aAdditional
	 *
	 * @return array
	 */
	protected function handleError($msg, $code, $aAdditional = null): array
	{
		$aData = [
			'message' => $msg,
			'code'    => $code,
			'status'  => 'error'
		];

		if (!empty($aAdditional)) {
			return array_merge($aData, ['data' => $aAdditional]);
		}

		return $aData;
	}
}
