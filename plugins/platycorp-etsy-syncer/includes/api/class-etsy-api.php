<?php
namespace platy\etsy\api;

use platy\etsy\EtsySyncerException;
use platy\etsy\api\Client as Oauth2Client;

/**
*
*/
class EtsyApi
{
	const LANGUAGE = "language";

	private static $instance = null;
	private $client;
	private $language;
	private $methods = array();
	private $returnJson = false;

	private function __construct($client, $language = "en", $methods_file = null)
	{
		$this->language = $language;
		if ($methods_file === null)
		{
			$methods_file = dirname(realpath(__FILE__)) . '/methods.json';
		}

		if (!file_exists($methods_file))
		{
			exit("Etsy methods file '{$methods_file}' does not exist!");
		}
		$this->methods = json_decode(file_get_contents($methods_file), true);
		$this->client = $client;
	}

	public function setReturnJson($returnJson)
	{
	    $this->client->setReturnJson($returnJson);
	}

	public static function get_instance($api_key, $token, $legacy_token, $language = "en") {
		if(EtsyApi::$instance != null) {
			return EtsyApi::$instance;
		}
		$oauth2 = new Oauth2Client($api_key);
		if($token != null){
			$oauth2->setApiKey($token);
		}
		if($legacy_token != null) {
			$oauth2->set_legacy_token($legacy_token);
		}
		EtsyApi::$instance = new EtsyApi(new EtsyClient($oauth2), $language);
		return EtsyApi::$instance;
	}

	private function request($arguments)
	{
		$method = $this->methods[$arguments['method']];
		$args = $arguments['args'];
		$params = $this->prepareParameters($args['params']);
		$data = @$this->prepareData($args['data']);

		$uri = preg_replace_callback('@:(.+?)(\/|$)@', function($matches) use ($args) {
			return $args["params"][$matches[1]].$matches[2];
		}, $method['uri']);

		// if (!empty($args['associations']))
		// {
		// 	$params['includes'] = $this->prepareAssociations($args['associations']);
		// }

		// if (!empty($args['fields']))
		// {
		// 	$params['fields'] = $this->prepareFields($args['fields']);
		// }

		// if(!empty($params)) {
		// 	$uri .= "?" . http_build_query($params);
		// }
        // $encoding = 'json';
		// if($method['http_method']=='PUT'){
		//     $encoding = 'form_params';
		// }
		// if(isset($method['multipart'])){
		//     $encoding = 'multipart';
		//     $data = $this->to_multi_part($data,$method['params']);
		// }
		$encoding = empty($method['encoding']) ? 'form_params' : $method['encoding'];
		return $this->validateResponse( $args, $this->client->request($uri, $data, $method['http_method'],  $method['visibility'], $encoding) );
	}
	
	private function to_multi_part($data){
	    $multipart = array();
	    foreach($data as $key => $value){
	        if($key=='image'){
	            $source_file = $data['image'];
	            $mimetype = mime_content_type($source_file);
	            $filesize = filesize($source_file);
	            $header = [];
	            //buid some header params
	            $header["Content-Type"] = 'multipart/form-data; boundary='.md5(time());
	            $header["Content-Length"] = $filesize;
	            $header["Content-Disposition"] = 'form-data; name="image"; filename="'.$source_file.'"';
	            $header["Content-Transfer-Encoding"] = 'binary';
	            $multipart[] = [
	                'name' => 'image',
	                'contents' => fopen($source_file, 'r'),
	                'header' => $header
	            ];  
	        }else{
	            $multipart[] = [
	                'name' => $key,
	                'contents' => $value
	            ];
	        }
	    }
	    return $multipart;
	}

	protected function validateResponse($request_args, $response)
	{
		// currently no validation.
		return $response;
	}

	private function prepareData($data) {
		$result = array();
		foreach ($data as $key => $value) {
			$type = gettype($value);
			if ($type !== 'boolean') {
				$result[$key] = $value;
				continue;
			}

			$result[$key] = $value ? 1 : 0;
		}

		return $result;
	}

	private function prepareParameters($params) {
		$query_pairs = array();
		$allowed = array("limit", "offset", "page", "sort_on", "sort_order", "include_private", "language");

		if ($params) {
			foreach($params as $key=>$value) {
				if (in_array($key, $allowed)) {
					$query_pairs[$key] = $value;
				}
			}
		}

		return $query_pairs;
	}

	private function prepareAssociations($associations)
	{
		$includes = array();
		foreach ($associations as $key => $value)
		{
			if (is_array($value))
			{
				$includes[] = $this->buildAssociation($key, $value);
			} else {
				$includes[] = $value;
			}
		}

		return implode(',', $includes);
	}

	private function prepareFields($fields)
	{

		return implode(',', $fields);
	}

	private function buildAssociation($assoc, $conf)
	{
		$association = $assoc;
		if (isset($conf['select']))
		{
			$association .= "(".implode(',', $conf['select']).")";
		}
		if (isset($conf['scope']))
		{
			$association .= ':' . $conf['scope'];
		}
		if (isset($conf['limit']))
		{
			$association .= ':' . $conf['limit'];
		}
		if (isset($conf['offset']))
		{
			$association .= ':' . $conf['offset'];
		}
		if (isset($conf['associations']))
		{
			$association .= '/' . $this->prepareAssociations($conf['associations']);
		}

		return $association;
	}

	/*
	* array('params' => array(), 'data' => array())
	* :params for uri params
	* :data for "post fields"
	*/
	public function __call($method, $args) {
	    
		if (isset($this->methods[$method]))
		{
			$validArguments = RequestValidator::validateParams(@$args[0], $this->methods[$method]);
			if (isset($validArguments['_invalid']))
			{
				throw new EtsySyncerException('Invalid params for method "'.$method.'": ' . implode(', ', $validArguments['_invalid']) . ' - ' . json_encode($this->methods[$method]));
			}
			
			@$validArguments['_valid'][self::LANGUAGE] = $this->language;

			try {
				return call_user_func_array(array($this, 'request'), array(
					array(
						'method' => $method,
						'args' => array(
									'data' => @$validArguments['_valid'],
									'params' => @$args[0]['params'],
									'associations' => @$args[0]['associations'],
									'fields' => @$args[0]['fields']
									)
					)));
			}catch(OAuthRefreshException $e) {
				$this->client->invalidate_token();
				return $this->__call($method, $args);
			}
			catch(OAuthException $e){
				throw $e;
			}
			catch(\Exception $e){
				throw new EtsySyncerException($e->getMessage());
			}

		} else {
			throw new EtsySyncerException('Method "'.$method.'" not exists');
		}
	}

	
	public function get_client() {
		return $this->client;
	}

}
