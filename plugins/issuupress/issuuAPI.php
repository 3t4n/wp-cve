<?php
if (!class_exists('issuuAPI')) {
	class issuuAPI {
		// url to Issuu api
		var $requestUrl = 'https://api.issuu.com/1_0';
		var $uploadUrl = 'https://api.issuu.com/1_0';

		var $apiKey;
		var $apiSecret;
		var $issuuCacheFile;
		var $cacheFolder;
		var $cacheDuration = 3600; // in seconds
		var $forceCache = false;
		var $result_order = 'desc';
		var $result_orderby = 'publishDate';
		var $result_access = 'public';

		function __construct($key)
		{
			$this->apiKey = $key['apiKey'];
			$this->apiSecret = $key['apiSecret'];
			$this->cacheFolder= plugin_dir_path(__FILE__).'cache';
			$this->issuuCacheFile = $this->cacheFolder . '/issuu.json';
			$this->cacheDuration = ($key['cacheDuration']!='')? $key['cacheDuration']: $this->cacheDuration;
			$this->forceCache = ($key['forceCache']!='')? $key['forceCache']: $this->forceCache;   
			$this->result_order = ($key['result_order']!='')? $key['result_order']: $this->result_order;
			$this->result_orderby = ($key['result_orderby']!='')? $key['result_orderby']: $this->result_orderby;
			$this->result_access = ($key['result_access']!='')? $key['result_access']: $this->result_access;
		}

		public function getListing()
		{
			// see documentation: http://issuu.com/services/api/issuu.document.list.html

			if (($this->forceCache) || (!$this->cache_is_valid())){
				//ini_set('max_execution_time', 120);
				$apiKey = $this->apiKey;
				$apiSecret = $this->apiSecret;

				$args=array(
					'format'=>'json',
					'action'=>'issuu.documents.list',
					'apiKey'=>$apiKey,
					'documentSortBy'=> $this->result_orderby, //'publishDate',
					'documentStates'=>'A',
					'pageSize'=>'30',
					'resultOrder'=>$this->result_order,
					'startIndex'=>'0',
					'access'=>$this->result_access
				);

				ksort($args);
/*
echo '<pre>';
print_r($args);
exit;
*/
				$argAsSignature = $argAsUrlParameters = '';

				foreach($args as $k=>$v){
					$argAsSignature .=$k.$v;
					$argAsUrlParameters.= '&'.$k.'='.$v;
				}

				$signature = md5($apiSecret.$argAsSignature);
				$request_url = $this->requestUrl .'?signature='.$signature.$argAsUrlParameters;
				$response = wp_remote_get($request_url);

				/*
					check if issuu returns an error.
				*/
				if( is_wp_error($response) || isset($response->errors) || $response == null) {
					return array('error'=>"Could not connect to issuu.");
				}
				$json=json_decode($response['body'],true);
				$error = (isset($json["rsp"]["_content"]["error"]));

				if($error){
					$error = $json["rsp"]["_content"]["error"];
					return array('error'=>'issuu API sent an error: '.$error["code"].' : '.$error["message"] );
				}
				file_put_contents($this->issuuCacheFile, $response['body']);

			}
			else{
				// Fetch from cache
				$response['body'] = file_get_contents($this->issuuCacheFile);

			}
			$response = json_decode( $response['body'] );
			if (empty( $response) )
				return array('error'=>"Issuu 's response is empty.");

			if ( $response->rsp->stat == "fail" )
				return array('error'=>"Issuu failed to provide the requested data (returned a 'FAILED' flag).");

			return $response->rsp->_content->result;
		}


		function cache_is_valid(){
			// Check if we need to refresh the cache (see: http://php.net/manual/en/function.filemtime.php)
			if(!is_file($this->issuuCacheFile)){
				@chmod($this->cacheFolder, 0777);
				$file = fopen($this->issuuCacheFile, 'w');// or die("can't create file");
				fclose($file);
				@chmod($this->issuuCacheFile, 0777);
				return false;
			}
			$filemtime = @filemtime($this->issuuCacheFile);
			return ($filemtime && (time() - $filemtime < $this->cacheDuration));
		}
	}
}
?>