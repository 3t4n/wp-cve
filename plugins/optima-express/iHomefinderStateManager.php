<?php

class iHomefinderStateManager {
	
	//stored in session
	const SESSION_ID = "ihf_session_id";
	const LAST_SEARCH_URL = "ihf_last_search_url";
	const ERROR_MODE_TIMES = "ihf_error_mode_times";
	
	//stored as cookie
	const SUBSCRIBER_ID = "ihf_subscriber_id";
	const REMEMBER_ME = "ihf_remember_me";
	const LEAD_CAPTURE_USER_ID = "ihf_lead_capture_user_id";
	
	const COOKIE_TIMEOUT = 157680000; // 5 years
	const ERROR_MODE_TIMEOUT = 300; // 5 min
	const ERROR_MODE_ATTEMPTS = 3;
	
	private static $instance;
	private $listingInfo;
	private $enqueueResource;
	
	private function __construct() {
		$this->enqueueResource = iHomefinderEnqueueResource::getInstance();
		$this->displayRules = iHomefinderDisplayRules::getInstance();
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
		
	/** 
	 * Ajax calls are always made as admin so we check to see if the ajax call is ours
	 * */
	private function isIhfAjax() {
		$result = false;
		if(wp_doing_ajax()) {
			if(isset($_GET["action"])){
				if (strpos($_GET["action"], "ihf") !== false) {
					$result = true;
				}
			}
		}
		return $result;
	}
	
	public function getUserAgent() {
		if($this->hasUserAgent()) {
			return $_SERVER["HTTP_USER_AGENT"];
		}
	}
	
	public function hasUserAgent() {
		return array_key_exists("HTTP_USER_AGENT", $_SERVER);
	}

	public function getUserIpAddress() {
		$result = null;
		if(array_key_exists("REMOTE_ADDR", $_SERVER)) {
			$result = $_SERVER["REMOTE_ADDR"];
		}
		return $result;
	}
	
	/**
	 * Returns true is the user agent is a known web crawler.
	 * We're currently not using this because we do checks on the IHF servers
	 * @return boolean
	 */
	public function isWebCrawler() {
		$result = false;
		if($this->hasUserAgent()) {
			$userAgent = $this->getUserAgent();
			$crawlers = array(
				"Mediapartners-Google",
				"Googlebot",
				"Baiduspider",
				"Bingbot",
				"msnbot",
				"Slurp",
				"Twiceler",
				"YandexBot"
			);
			foreach($crawlers as $crawler) {
				if(stripos($userAgent, $crawler)) {
					$result = true;
					break;
				}
			}
		}
		return $result;
	}
	
	public function getSessionId() {
		return $this->getCookie(self::SESSION_ID);
	}
	
	public function setSessionId($value) {
		if(!empty($value)) {
			$this->setCookie(self::SESSION_ID, $value);
		}
	}
	
	public function hasSessionId() {
		return $this->hasCookie(self::SESSION_ID);
	}
	
	public function getLastSearchUrl() {
		return $this->getCookie(self::LAST_SEARCH_URL);
	}
	
	public function setLastSearchUrl() {
		$value = $this->getCurrentUrl();
		$value = str_replace("newSearch=true&", "", $value);
		$this->setCookie(self::LAST_SEARCH_URL, $value);
	}
	
	public function hasLastSearch() {
		return $this->hasCookie(self::LAST_SEARCH_URL);
	}
	
	public function getSubscriberId() {
		return $this->getCookie(self::SUBSCRIBER_ID);
	}
	
	public function setSubscriberId($value) {
		if(!empty($value)) {
			$this->setCookie(self::SUBSCRIBER_ID, $value);
		}
	}
	
	public function hasSubscriberId() {
		return $this->hasCookie(self::SUBSCRIBER_ID);
	}
	
	public function removeSubscriberId() {
		$this->removeCookie(self::SUBSCRIBER_ID);
	}
	
	public function getRememberMe() {
		return $this->getCookie(self::REMEMBER_ME);
	}
	
	public function setRememberMe($value) {
		$this->setCookie(self::REMEMBER_ME, $value);
	}
	
	public function hasRememberMe() {
		return $this->hasCookie(self::REMEMBER_ME);
	}
	
	public function removeRememberMe() {
		$this->removeCookie(self::REMEMBER_ME);
	}
	
	public function getLeadCaptureUserId() {
		return $this->getCookie(self::LEAD_CAPTURE_USER_ID);
	}
	
	public function setLeadCaptureUserId($value) {
		if(!empty($value)) {
			$this->setCookie(self::LEAD_CAPTURE_USER_ID, $value);
		}
	}
	
	public function hasLeadCaptureUserId() {
		return $this->hasCookie(self::LEAD_CAPTURE_USER_ID);
	}
	
	public function setListingInfo($value) {
		if(is_a($value, "iHomefinderListingInfo")) {
			$this->listingInfo = $value;
		}
	}
	
	public function getListingInfo() {
		return $this->listingInfo;
	}
	
	public function hasListingInfo() {
		return $this->listingInfo !== null;
	}
	
	public function isListingIdResults() {
		return array_key_exists("listingIdList", $_REQUEST);
	}
	
	public function isListingAddressResults() {
		return array_key_exists("streetNumber", $_REQUEST);
	}
	
	public function getCurrentUrl() {
		$scheme = "http";
		if(is_ssl()) {
			$scheme = "https";
		}
		$host = $_SERVER["HTTP_HOST"];
		$requestUri = $_SERVER["REQUEST_URI"];
		$result = $scheme . "://" . $host . $requestUri;
		return $result;
	}
	
	public function getLastSearch() {
		$result = null;
		$url = $this->getLastSearchUrl();
		$queryString = parse_url($url, PHP_URL_QUERY);
		if(!empty($queryString)) {
			parse_str($queryString, $result);
		}
		return $result;
	}
	
	private function getCookie($name) {
		if($this->hasCookie($name)) {
			return $_COOKIE[$name];
		}
	}

	public function isErrorForTimeout($responseCode) {
		$result = false;
		$errors = array(
			503,
		);
		foreach($errors as $error) {
			if($responseCode === $error) {
				$result = true;
				break;
			}
		}
		return $result;
	}
	
	public function getErrorsTimes() {
		$errorsTimesJson = $this->getCookie(self::ERROR_MODE_TIMES);
		$errorsTimes = json_decode( $errorsTimesJson ) ;
		return $errorsTimes ;
	}

	private function setErrorTimes($errorsTimes) {
		$errorsTimes = $this->limitErrorsTimes($errorsTimes);
		$errorsTimesJson = json_encode($errorsTimes);
		$this->setCookie(self::ERROR_MODE_TIMES, $errorsTimesJson );
	}

	private function emptyErrorTimes() {
		$this->setErrorTimes( array() ) ;
	}
	
	private function limitErrorsTimes($errorsTimes) {
		while(count($errorsTimes) > self::ERROR_MODE_ATTEMPTS) {
			array_shift($errorsTimes);
		}
		return $errorsTimes;
	}
	
	public function addErrorTime() {
		$errorsTimes = $this->getErrorsTimes();
		$errorsTimes[] = time();
		$this->setErrorTimes($errorsTimes);
	}
		
	public function isErrorMode() {
		$result = false;
		$errorTimes = $this->getErrorsTimes();
		if(is_array($errorTimes) && count($errorTimes) >= self::ERROR_MODE_ATTEMPTS) {
			$errorTimeDifference = end($errorTimes) - reset($errorTimes);
			//if the errors occur within a given time, enter into error mode
			if($errorTimeDifference < self::ERROR_MODE_TIMEOUT) {
				$result = true;
			}
			//if the last error happened more than a given time from the current time, remove error mode
			if(time() - end($errorTimes) > self::ERROR_MODE_TIMEOUT) {
				$this->emptyErrorTimes( array() ) ;
				$result = false;				
			}			
		}
		return $result;
	}

	private function setCookie($name, $value) {
		$_COOKIE[$name] = $value;
		$expireTime = time() + self::COOKIE_TIMEOUT;
		if(headers_sent()) {
			//WordPress does not buffer the response so we use JS to set cookies on shortcode requests because headers have already been sent
			$value = '
				<script type="text/javascript">
					(function() {
						var expire = new Date();
						expire.setSeconds(expire.getSeconds() + ' . self::COOKIE_TIMEOUT . ');
						document.cookie = "' . $name . '=' . $value . '; expires=" + expire.toUTCString() + "; path=/";
					})();
				</script>
			';
			$this->enqueueResource->addToFooter($value);
		} else {
			setcookie($name, $value, $expireTime, "/");
		}
	}

	private function hasCookie($name) {
		return array_key_exists($name, $_COOKIE);
	}
	
	private function removeCookie($name) {
		if($this->hasCookie($name)) {
			unset($_COOKIE[$name]);
		}
		$expireTime = time() - 3600;
		setcookie($name, null, $expireTime, "/");
	}
	
	public function setupLeadCaptureUser() {
		if(!$this->isWebCrawler() && !$this->displayRules->isKestrelAll()) {
			$leadSource = iHomefinderUtility::getInstance()->getRequestVar("leadSource");
			if(!$this->hasLeadCaptureUserId() || $leadSource != null){
				$remoteRequest = new iHomefinderRequestor();
				$remoteRequest
				->addParameter("requestType", "initialize-lead-capture-user")
				->addParameters($_REQUEST)
				;
				$remoteResponse = $remoteRequest->remoteGetRequest();
			}			
		}
	}
	
}