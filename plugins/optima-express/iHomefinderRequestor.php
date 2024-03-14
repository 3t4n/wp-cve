<?php

class iHomefinderRequestor {
	
	private $parameters = array();
	private $cacheExpiration = 0;
	private $remoteResponse;
	
	private $logger;
	private $displayRules;
	private $utility;
	private $stateManager;
	private $enqueueResource;
	
	public function __construct() {
		$this->logger = iHomefinderLogger::getInstance();
		$this->displayRules = iHomefinderDisplayRules::getInstance();
		$this->utility = iHomefinderUtility::getInstance();
		$this->stateManager = iHomefinderStateManager::getInstance();
		$this->enqueueResource = iHomefinderEnqueueResource::getInstance();
	}
	
	public function remoteGetRequest() {
		
		if($this->remoteResponse === null) {
			if($this->stateManager->isErrorMode()) {
				$responseBodyObject = null;
				$this->remoteResponse = new iHomefinderRemoteResponse();
				$this->remoteResponse->setBody("The server is temporarily unavailable due to maintenance or high demand.");
				$this->enqueueResource->setHttpStatusCode(503);
				$this->enqueueResource->addHttpHeader("Retry-After: " . iHomefinderStateManager::ERROR_MODE_TIMEOUT);
			}
		}
		
		if($this->remoteResponse === null) {
			if($this->isSpam()) {
				wp_die("invalid request 807a");
				//$result = "responceBodyGoesHere";
			}
		}
		
		if($this->remoteResponse === null) {
			$requestUrl = $this->getExternalUrl();
			
			//only add user specific info if the request is not cacheable
			if(!$this->isCacheable()) {
					
				//add jsession id to the end of the url
				if($this->stateManager->hasSessionId()) {
					$sessionId = $this->stateManager->getSessionId();
					$requestUrl .= ";jsessionid=" . $sessionId;
				}
					
				//add subscriber id to the reqest parameters
				if($this->stateManager->hasSubscriberId()) {
					$subscriberId = $this->stateManager->getSubscriberId();
					//CF cannot handle multiple parameters with the same case insensitive name
					$this->removeParameter("subscriberId")->removeParameter("subscriberID");
					$this->addParameter("subscriberId", $subscriberId);
				}
					
				//add lead capture id to the reqest parameters
				if($this->stateManager->hasLeadCaptureUserId()) {
					$leadCaptureUserId = $this->stateManager->getLeadCaptureUserId();
					$this->addParameter("leadCaptureId", $leadCaptureUserId);
				}
					
				//if remember me cookie add it to the reqest parameters
				if($this->stateManager->hasRememberMe()) {
					$this->addParameter("rmuser", true);
				}
					
				//add user agent to the request parameters
				if($this->stateManager->hasUserAgent()) {
					$userAgent = $this->stateManager->getUserAgent();
					$this->addParameter("uagent", $userAgent);
				}

				//add IP of user to the request parameters
				$ipAddress = $this->stateManager->getUserIpAddress();
				$this->addParameter("ipAddress", $ipAddress);
					
			}
			
			//add authentication token to the reqest parameters
			$authenticationToken = iHomefinderAdmin::getInstance()->getAuthenticationToken();
			$this->addParameter("authenticationToken", $authenticationToken);
			
			$this->addParameter("version", iHomefinderConstants::VERSION);
			
			$this->addParameter("leadCaptureSupport", true);
			$this->addParameter("phpStyle", true);
			
			$requestUrl = $this->utility->buildUrl($requestUrl, $this->getParameters());
			
			$ihfid = iHomefinderUrlFactory::getInstance()->getBaseUrl() . ";" . "WordpressPlugin";
			$ihfUserInfo = "WordPress/" . get_bloginfo("version") . "; " . iHomefinderUrlFactory::getInstance()->getBaseUrl();
			//Modified user-agent in the request header to pass original user-agent. This information is used to determine if request came from mobile devices.
			
			$requestArgs = array(
					"timeout" => "35",
					"ihfid" => $ihfid,
					"ihfUserInfo" => $ihfUserInfo,
					"sslverify" => false,
			);
			
			if(isset($userAgent)) {
				$requestArgs["user-agent"] = $userAgent;
			}
			
			$response = null;
			if($this->isCacheable()) {
				$response = iHomefinderCacheUtility::getInstance()->getItem($requestUrl);
			}
			if($response === null) {
				$this->logger->debug("ihfUrl: " . $requestUrl);
				$this->logger->debug($requestArgs);
				$this->logger->debug("before request");
					
				$response = wp_remote_get($requestUrl, $requestArgs);
					
				$this->logger->debug("after request");
				$this->logger->debug($response);
					
				if(!is_wp_error($response) && $this->isCacheable() && $response["response"]["code"] < 400) {
					iHomefinderCacheUtility::getInstance()->updateItem($requestUrl, $response, $this->getCacheExpiration());
				}
			}
			
			if(is_wp_error($response)) {
				$this->remoteResponse = new iHomefinderRemoteResponse();
				$this->remoteResponse->setBody("<br />Sorry we are experiencing system issues. Please try again.<br />");
				$this->enqueueResource->setHttpStatusCode(500);
			}
			
			if(!is_wp_error($response)) {
				
				$responseCode = $response["response"]["code"];
				
				if($this->stateManager->isErrorForTimeout($responseCode)) {
					$this->stateManager->addErrorTime();
				}
				
				if($responseCode == 404) {
					global $wp_query;
					$wp_query->set_404();
					status_header(404);
					nocache_headers();
				}
				
				if($responseCode >= 500) {
					$this->remoteResponse = new iHomefinderRemoteResponse();
					$this->enqueueResource->setHttpStatusCode($responseCode);
				}
				
				if($responseCode < 500) {
					$responseBody = wp_remote_retrieve_body($response);
					$contentType = wp_remote_retrieve_header($response, "content-type");
					$responseBodyObject = null;
					if($contentType == "text/xml;charset=UTF-8") {
						$responseBodyObject = simplexml_load_string($responseBody, null, LIBXML_NOCDATA);
					} else {
						$responseBodyObject = json_decode($responseBody);
					}
					$this->remoteResponse = new iHomefinderRemoteResponse();
					$this->remoteResponse->setResponse($responseBodyObject);
					$this->enqueueResource->setHttpStatusCode($responseCode);
				}
				
			}
			
			if(is_object($this->remoteResponse) && !$this->remoteResponse->hasError() && !$this->isCacheable()) {
					
				if($this->remoteResponse->hasLeadCaptureUserId()) {
					$this->stateManager->setLeadCaptureUserId($this->remoteResponse->getLeadCaptureUserId());
				}
					
				if($this->remoteResponse->hasSessionId()) {
					$sessionId = $this->remoteResponse->getSessionId();
					$this->stateManager->setSessionId($sessionId);
				}
					
				if($this->remoteResponse->hasListingInfo()) {
					$listingInfo = $this->remoteResponse->getListingInfo();
					$listingNumber = null;
					$listingAddress = null;
					$boardId = null;
					$clientPropertyId = null;
					$sold = false;
					if(property_exists($listingInfo, "listingNumber") && property_exists($listingInfo, "boardId")) {
						$listingNumber = $listingInfo->listingNumber;
						$boardId = $listingInfo->boardId;
						if(property_exists($listingInfo, "clientPropertyId")) {
							$clientPropertyId = $listingInfo->clientPropertyId;
						}
						if(property_exists($listingInfo, "listingAddress")) {
							$listingAddress = $listingInfo->listingAddress;
						}
						if(property_exists($listingInfo, "sold")) {
							$sold = $listingInfo->sold;
						}
						$listingInfo = new iHomefinderListingInfo($listingNumber, $boardId, $listingAddress, $clientPropertyId, $sold);
						$this->stateManager->setListingInfo($listingInfo);
					}
				}
			
				if($this->remoteResponse->hasSubscriberId()) {
					$subscriberId = $this->remoteResponse->getSubscriberId();
					$this->stateManager->setSubscriberId($subscriberId);
				}	
			}
			
		}
		
		// as a last resort, return an instance of iHomefinderRemoteResponse
		if(!is_object($this->remoteResponse)) {
			$this->remoteResponse = new iHomefinderRemoteResponse();
		}
		
		return $this->remoteResponse;
	}
	
	/**
	 * only used for registration
	 */
	public function remotePostRequest() {
		
		$requestUrl = $this->getExternalUrl();
		$ihfid = iHomefinderUrlFactory::getInstance()->getBaseUrl() . ";" . "WordpressPlugin";
		$ihfUserInfo = "WordPress/" . get_bloginfo("version") . "; " . iHomefinderUrlFactory::getInstance()->getBaseUrl();
		//Modified user-agent in the request header to pass original user-agent. This information is used to determine if request came from mobile devices.
		
		$userAgent = $_SERVER["HTTP_USER_AGENT"];
		if($userAgent !== null) {
			$userAgent = urlencode($userAgent);
		}
		
		$this->addParameter("version", iHomefinderConstants::VERSION);
		$this->addParameter("method", "handleRequest");
		$this->addParameter("viewType", "json");
		$this->addParameter("phpStyle", true);
		
		$requestArgs = array(
			"timeout" => "200",
			"body" => $this->getParameters(),
			"ihfid" => $ihfid,
			"ihfUserInfo" => $ihfUserInfo,
			"user-agent" => $userAgent,
			"sslverify" => false,
		);
		
		$this->logger->debug("ihfUrl: " . $requestUrl);
		$this->logger->debug($requestArgs);
		$this->logger->debug("before request");
		$response = wp_remote_post($requestUrl, $requestArgs);
		$this->logger->debug("after request");
		$this->logger->debug($response);
		
		if(!is_wp_error($response)) {
			$responseBody = wp_remote_retrieve_body($response);
			if($response["response"]["code"] >= 400) {
				$responseBodyObject = new stdClass();
				$responseBodyObject->view = $responseBody;
			} else {
				$contentType = wp_remote_retrieve_header($response, "content-type");
				if($contentType !== null && $contentType == "text/xml;charset=UTF-8") {
					$responseBodyObject = simplexml_load_string($responseBody, null, LIBXML_NOCDATA);
					$responseBodyObject = json_decode(json_encode($responseBodyObject));
				} else {
					$responseBodyObject = json_decode($responseBody);
				}
			}
			$this->remoteResponse = new iHomefinderRemoteResponse();
			$this->remoteResponse->setResponse($responseBodyObject);
		}
			
		return $this->remoteResponse;
	}
	
	public function hasParameter($name) {
		$result = false;
		if(array_key_exists($name, $this->parameters)) {
			$result = true;
		}
		return $result;
	}
	
	public function addParameter($name, $value) {
		$this->parameters[$name] = $value;
		return $this;
	}
	
	public function removeParameter($name) {
		if($this->hasParameter($name)) {
			unset($this->parameters[$name]);
		}
		return $this;
	}
	
	public function addParameters($parameters) {
		if(is_array($parameters)) {
			foreach($parameters as $name => $value) {
				$this->addParameter($name, $value);
			}
		}
		return $this;
	}
	
	public function getParameters() {
		return $this->parameters;
	}
	
	public function setCacheExpiration($cacheExpiration) {
		$this->cacheExpiration = $cacheExpiration;
	}
	
	public function getCacheExpiration() {
		return $this->cacheExpiration;
	}
	
	public function isCacheable() {
		$result = false;
		if(is_int($this->cacheExpiration) && $this->cacheExpiration > 0) {
			$result = true;
		}
		return $result;
	}
	
	public function getRemoteResponse() {
		return $this->remoteResponse;
	}
	
	private function isSpam() {
		$spam = false;
		$honeyPotValue = $this->utility->getRequestVar("JKGH00920");
		if(!empty($honeyPotValue)) {
			$spam = true;
		}
		return $spam;
	}
	
	private function getExternalUrl() {
		$result = null;
		$scheme = "http";
		if(is_ssl()) {
			$scheme = "https";
		}
		$result .= $scheme . "://";
		$result .= iHomefinderConstants::EXTERNAL_URL;
		return $result;
	}
	
}