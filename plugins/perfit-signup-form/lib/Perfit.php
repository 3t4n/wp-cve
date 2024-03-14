<?php namespace PerfitSDK;

require_once "Exceptions/AccountRequired.php";
require_once "Exceptions/UnauthorizedLogin.php";

//use function explode;
use PerfitSDK\Exceptions\AccountRequired;
use PerfitSDK\Exceptions\UnauthorizedLogin;

/**
 * Perfit class wrapper for Perfit UI communication
 *
 * @package PerfitSDK
 * @author Perfit
 */
class Perfit {

	/**
	 * @version 1.0.0
	 */
	const VERSION  = "1.0.0";

	/**
	 * @var array Default settings
	 */
	private $defaultSettings = [
		'url'				=> "https://api.myperfit.com",
		'version'			=> 2,
	];

	/**
	 * @var $curl_opts Curl options
	 */
	public $curl_opts = array(
		CURLOPT_USERAGENT => "PERFIT-PHP-SDK-1.0.0",
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_TIMEOUT => 60,
		CURLOPT_HTTPHEADER => array (
			"Content-Type: application/x-www-form-urlencoded",
		),
	);

	/**
	 * @var array Permitted http methods
	 */
	private $httpMethods = ['GET', 'POST', 'PUT', 'DELETE'];

	/**
	 * @var $namespace Namespace to make the request to
	 */
	protected $namespace = null;

	/**
	 * @var $token API version that will be included in the request url
	 */
	protected $token = null;

	/**
	 * @var $params Stores params for next request
	 */
	protected $params = array();

	/**
	 * @var $account Stores current account for all calls
	 */
	protected $account = null;

    /**
     * @var $apiKey Stores current apiKey for all calls
     */
	protected $apiKey = null;

	/**
	 * @var $id Stores id for next request
	 */
	protected $id = null;

	/**
	 * @var $action Action to execute for a resource
	 */
	protected $action = null;

	/**
	 * @var $methodOverride Force certain method
	 */
	protected $methodOverride = null;

	/**
	 * Constructor method. Set all variables to connect in Meli
	 *
	 * @param array $settings Settings to override
	 * @return object
	 */
	public function __construct($settings = null) {
		// Store settings
		$this->settings($settings);
		$this->apiKey($settings["apiKey"]);
	}

	/**
	 * Override default settings
	 *
	 * @param array $settings Settings to override
	 * @return array Current stored settigns
	 */
	public function settings($settings=null) {

		if ($settings) {
			foreach ($this->defaultSettings as $keySetting => $valSetting) {
				if (isset($settings[$keySetting])) {
					$this->defaultSettings[$keySetting] = $settings[$keySetting];
				}
			}
		}
		return $this->defaultSettings;
	}

	/**
	 * Login method
	 *
	 * @param string $user
	 * @param string $password
	 * @param string $account Optional account
	 * @return boolean
	 */
	public function login($user, $password, $account=null) {

		$params = ['user' => $user, 'password' => $password];
		if ($account) {
			$params['account'] = $account;
		}
		$response = $this->execute("POST", '/login', $params);

		// Successful login, store token
		if ($response->success) {
			$this->token($response->data->token);
			$this->account($response->data->account);
		} else {
		    if($response->error->type == "UNAUTHORIZED"){
		        throw new UnauthorizedLogin();
            } else if ($response->error->type == "ACCOUNT_REQUIRED"){
                throw new AccountRequired();
            }
            throw new \Exception();
        }
		return $response;

		return true;
	}


    /**
     * ApiKey setter/getter
     *
     * @param string $apiKey
     * @return string ApiKey
     */
	public function apiKey($apikey = null){
	    if($apikey){
	        $this->apiKey = $apikey;
	        $this->account(explode("-", $apikey)[0]);
            $this->curl_opts[CURLOPT_HTTPHEADER][] = "Authorization: Bearer $apikey";
        }
        return $this->apiKey;
    }

	/**
	 * Token setter/getter
	 *
	 * @param string $token
	 * @return string Token
	 */
	public function token($token = null) {
		if ($token) {
			$this->token = $token;
			$this->curl_opts[CURLOPT_HTTPHEADER][] = "X-Auth-Token: $token";
		}
		return $this->token;
	}

	/**
	 * Account setter/getter
	 *
	 * @param string $account
	 * @return string Account name
	 */
	public function account($account = null) {
		if ($account) {
			$this->account = $account;
		}
		return $this->account;
	}

	/**
	 * Set id for next request
	 *
	 * @param integer $id
	 * @return object
	 */
	public function id($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * Set limit for next request
	 *
	 * @param integer $limit
	 * @return object
	 */
	public function limit($limit) {
		$this->params['limit'] = $limit;
		return $this;
	}

	/**
	 * Set offset for next request
	 *
	 * @param integer $offset
	 * @return object
	 */
	public function offset($offset) {
		$this->params['offset'] = $offset;
		return $this;
	}

	/**
	 * Set sort for next request
	 *
	 * @param integer $sortBy Column to sort by
	 * @param integer $sortDir Sorting direction
	 * @return object
	 */
	public function sort($sortBy, $sortDir=null) {
		$this->params['sortBy'] = $sortBy;
		if ($sortDir) {
			$this->params['sortDir'] = $sortDir;
		}
		return $this;
	}

	/**
	 * Overrides method
	 *
	 * @param string $method
	 * @return object
	 */
	public function method($method) {
		if (in_array(strtoupper($method), $this->httpMethods)) {
			$this->methodOverride = $method;
		}
		return $this;
	}

	/**
	 * Makes request to server and retrieves response object
	 *
	 * @param $params
	 * @return object
	 */
	public function params($params) {
	    if (is_array($params)){
            $this->params = array_merge($this->params, $params);
        } else {
	        $this->params = $params;
        }

		return $this;
	}

	/**
	 * Capture non existing variables and turn it into namespace
	 *
	 * @param $name
	 * @return object
	 */
	public function __get($namespace) {
		$this->namespace = $namespace;
		return $this;
	}

	/**
	 * Capture non existing functions and turn it into action or http verb
	 *
	 * @param $name
	 * @param $arguments
	 * @return object
	 */
	public function __call($name, $arguments=array()) {

		$url = null;
		$params = array();

		if (in_array(strtoupper($name), $this->httpMethods)) {
			$type = $name;

			if (isset($arguments[0])) {
				$url = $arguments[0];
			}

			if (isset($arguments[1])) {
				$params = $arguments[1];

			}
		}
		else {
			$type = 'POST';
			$this->action = $name;
		}

		if ($params) {
			$this->params($params);
		}

		return $this->execute($type, $url, $this->params);
	}

	/**
	 * Makes request to server and retrieves response object
	 *
	 * @param string $type HTTP verb (GET, POST, PUT, DELETE supported)
	 * @param string $url Request url
	 * @param array $params Parameters array if needed
	 * @return object
	 */
	private function execute($type='get', $url=null, $params=array()) {

		if ($this->methodOverride) {
			$type = $this->methodOverride;
		}

		$type = strtoupper($type);
		$urlParams = '';

		// Add params
		if ($params) {
			$this->params($params);
		}

		if (! $url){
            $url = $this->buildRequestUrl($url);
        } else {
            $url = $this->buildRequestUrlLink($url);
        }



		// Build curl opts
		$opts = $this->curl_opts;
		if (strtoupper($type) == 'GET') {
			$urlParams = http_build_query($this->params);
			$url .= '?'.$urlParams;
		}
		else {
			$opts[CURLOPT_POST] = true;
			// $opts[CURLOPT_POSTFIELDS] = $this->params;
            if (is_array($this->params)){
                $opts[CURLOPT_POSTFIELDS] = http_build_query($this->params);
            } else {
                $opts[CURLOPT_POSTFIELDS] = $this->params;
            }

		}

		$request = array (
			'host' => $this->defaultSettings['url'],
			'method' => $type,
			'url' => $url,
			'params' => $params,
            'account' => $this->account()
		);

		$opts[CURLOPT_CUSTOMREQUEST] = strtoupper($type);

		// Execute curl request
		$url = $this->defaultSettings['url'].$url;
		$ch = curl_init($url);
		if (!empty($opts)) {
			curl_setopt_array($ch, $opts);
		}

		$response = curl_exec($ch);
		if ($this->isJson($response)){
            $response = json_decode($response);
            $response->request = (object)$request;
        }



		curl_close($ch);

		$this->reset();

		return $response;
	}

    /**
     * Build request url with information provided
     *
     * @return string Request url
     */
    private function buildRequestUrl() {

        // Build request url
        $request = '';

        if ($this->defaultSettings['version']) {
            $request .= '/v'.$this->defaultSettings['version'];
        }

        if ($this->namespace) {
            if ($this->account()) {
                $request .= '/'.$this->account();
            }
            $request .= '/'.$this->namespace;
        }

        // Add id if set
        if ($this->id) {
            $request .= '/'.$this->id;
        }

        // Add action if set
        if ($this->action) {
            $request .= '/'.$this->action;
        }

        return $request;
    }

	/**
	 * Build request url with information provided
	 *
	 * @return string Request url
	 */
	private function buildRequestUrlLink($url) {

		// Build request url
		$request = '';

		if ($this->defaultSettings['version']) {
			$request .= '/v'.$this->defaultSettings['version'];
		}

        if ($this->account()) {
            $request .= '/'.$this->account();
        }
        $request .= $url;

		return $request;
	}

	/**
	 * Creates error object to return
	 *
	 * @param $message
	 * @param $code HTTP error code
	 * @return object
	 */
	private function error($message='Formato de solicitud invalido', $code=400) {
		return [
			'success' => false,
			'error' => [
				'status' => $code,
				'type' => 'Bad Request',
				'userMessage' => $message,
			]
		];
	}

	/**
	 * Reset variables for a clean new request
	 *
	 */
	private function reset() {
		$this->namespace = null;
		$this->id = null;
		$this->action = null;
		$this->methodOverride = null;
		$this->params = array();
	}

	private function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}
