<?php
namespace platy\etsy\api;

/**
*
*/
class EtsyClient
{

	private $oauth = null;
	private $authorized = false;
	private $debug = true;
	private $api_key;
	private $consumer_key = "";
	private $consumer_secret = "";
    private $returnJson = false;
	/**
	 * 
	 * @param OAuth1 $oauth
	 */
	function __construct($oauth)
	{
		// $this->api_key = $api_key;
		$this->oauth = $oauth;
	}
	
	public function setReturnJson($returnJson){
	    $this->returnJson = $returnJson;
	}


	public function request($path, $params = array(), $method = "GET", $visibility = "",$encoding = 'json')
	{

        try{
			// if($this->oauth->has_legacty_token_only()) {
			// 	$new_token = $this->oauth->exchangeLegacyToken();
			// 	do_action('platy_etsy_refresh_oauth2_token', 0, $new_token);
			// }
			$response = $this->oauth->$method(
				$path,
				$params,
				$visibility, 
				$encoding
			);
		}catch(OAuthException $e){
			if($e->get_status_code() == 401) {
				$new_token = $this->oauth->refreshAccessToken();
				do_action('platy_etsy_refresh_oauth2_token', 0, $new_token);
				$response = $this->oauth->$method(
					$path,
					$params
				);
			}else{
				throw $e;
			}

		}

	    return $response;
    }

	public function invalidate_token() {
		$this->oauth->setApiKey([]);
	}

	public function get_oauth() {
		return $this->oauth;
	}

}

/**
*
*/
class EtsyResponseException extends \Exception
{
	private $response = null;

	function __construct($message, $response = array())
	{
		$this->response = $response;

		parent::__construct($message);
	}

	public function getResponse()
	{
		return $this->response;
	}
}

/**
*
*/
class EtsyRequestException extends \Exception
{
	private $lastResponse;
	private $lastResponseInfo;
	private $lastResponseHeaders;
	private $debugInfo;
	private $exception;
	private $params;

	function __construct($exception, $oauth, $params = array())
	{
		$this->lastResponse = $oauth->getLastResponse();
		$this->lastResponseInfo = $oauth->getLastResponseInfo();
		$this->lastResponseHeaders = $oauth->getLastResponseHeaders();
		$this->debugInfo = $oauth->debugInfo;
		$this->exception = $exception;
		$this->params = $params;

		parent::__construct($this->buildMessage(), 1, $exception);
	}

	private function buildMessage()
	{
		return $this->exception->getMessage().": " .
			print_r($this->params, true) .
			print_r($this->lastResponse, true) .
			print_r($this->lastResponseInfo, true) .
			// print_r($this->lastResponseHeaders, true) .
			print_r($this->debugInfo, true);
	}

	public function getLastResponse()
	{
		return $this->lastResponse;
	}

	public function getLastResponseInfo()
	{
		return $this->lastResponseInfo;
	}

	public function getLastResponseHeaders()
	{
		return $this->lastResponseHeaders;
	}

	public function getDebugInfo()
	{
		return $this->debugInfo;
	}

	public function getParams()
	{
		return $this->params;
	}

	public function __toString()
	{
		return __CLASS__ . ": [{$this->code}]: ". $this->buildMessage();
	}

}
