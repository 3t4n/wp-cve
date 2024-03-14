<?php

namespace WilcitySC2Tests;

trait HTTP
{
	protected $aAccounts;

	protected array $aAdminInfo
		= [
			'username' => 'admin'
		];
	protected       $aCurrentUser;

	protected $isEnableUserLogin;
	protected $oUser;
	protected $userId;
	protected $restBase;
	protected $ajaxUrl;
	protected $isAjax = false;
	protected $homeUrl;
	protected $password;
	protected $aGeneralSettings;

	protected function getAdminId(): int
	{
		$this->oUser = get_user_by('login', $this->aAdminInfo['username']);
		$this->userId = $this->oUser->ID;
		return $this->userId;
	}

	protected function getCookiePath(): string
	{
		return plugin_dir_path(__FILE__) . 'cookies.txt';
	}

	protected function restLogin($account = '')
	{
		return $this->restLogin($account);
	}

	protected function ajaxLogin($account = '')
	{
		$curl = curl_init();

		$aHeader = [
			"Referer: " . trailingslashit($this->homeUrl) . "wp-login.php",
			"Origin: " . $this->homeUrl,
			"Content-Type: application/x-www-form-urlencoded",
			"Cache-Control: no-cache",
			"Pragma: no-cache",
			"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
			"User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Safari/605.1.15"
		];

		curl_setopt($curl, CURLOPT_HTTPHEADER, $aHeader);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt(
			$curl,
			CURLOPT_USERAGENT,
			'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.5 Safari/605.1.15'
		);
		curl_setopt($curl, CURLOPT_AUTOREFERER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_COOKIESESSION, true);
		curl_setopt($curl, CURLOPT_COOKIEFILE, $this->getCookiePath());
		curl_setopt($curl, CURLOPT_COOKIEJAR, $this->getCookiePath());


		$url = trailingslashit($this->homeUrl) . 'wp-login.php';
		curl_setopt($curl, CURLOPT_URL, $url);

		$post = http_build_query(
			[
				'log'       => $this->getAccount($account)['username'],
				'pwd'       => $this->getAccount($account)['password'],
				'wp-submit' => 'Log+In',
				'action'    => 'login'
			]
		);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

		$output = curl_exec($curl);
		curl_close($curl);
		return $this;
	}

	protected function addAccounts($account, $aInfo)
	{
		$this->aAccounts[$account] = $aInfo;
		return $this;
	}

	protected function getAccount($account = ''): array
	{
		if (empty($account)) {
			$account = 'admin';
		}

		return isset($this->aAccounts[$account]) ? $this->aAccounts[$account] : [];
	}

	protected function getAccountInfo($account)
	{
		return get_user_by('login', $this->getAccount($account)['username']);
	}

	protected function configureAPI()
	{
		global $aWILOKEGLOBAL;
		$this->restBase = trailingslashit($aWILOKEGLOBAL['restBaseUrl']);
		$this->ajaxUrl = $aWILOKEGLOBAL['ajaxUrl'];
		$this->homeUrl = $aWILOKEGLOBAL['homeUrl'];

		$this->aAccounts = [
			'admin' => [
				'username' => $aWILOKEGLOBAL['ADMIN_USERNAME'],
				'password' => $aWILOKEGLOBAL['ADMIN_PASSWORD'],
				'auth'     => $aWILOKEGLOBAL['ADMIN_AUTH_PASS'],
			]
		];

		$this->aAdminInfo = [
			'username' => $aWILOKEGLOBAL['ADMIN_USERNAME'],
			'password' => $aWILOKEGLOBAL['ADMIN_PASSWORD'],
		];

		$this->aGeneralSettings = $aWILOKEGLOBAL;

		return $this;
	}

	/**
	 * @param $object
	 * @param $methodName
	 * @param array $aParams
	 * @return mixed
	 * @throws \ReflectionException
	 */
	public function invokeMethod($object, $methodName, array $aParams = [])
	{
		$reflection = new \ReflectionClass(get_class($object));
		$method = $reflection->getMethod($methodName);
		$method->setAccessible(true);

		return $method->invokeArgs($object, $aParams);
	}

	public function ajaxPost(array $aArgs)
	{
		$this->isAjax = true;
		return $this->restAPI('', 'POST', $aArgs);
	}

	public function ajaxGet(array $aArgs)
	{
		$this->isAjax = true;
		return $this->restAPI('', 'GET', $aArgs);
	}

	protected function setUserLogin($account)
	{
		$this->aCurrentUser = $this->getAccount($account);
		$this->isEnableUserLogin = true;
		wp_set_current_user($this->getAccountInfo($account)->ID);

		return $this;
	}

	protected function logout()
	{
		$this->isEnableUserLogin = false;
		$this->aCurrentUser = [];
		wp_set_current_user(0);
	}

	protected function restAPI($endpoint, $method = 'POST', array $aArgs = [])
	{
		$ch = curl_init();
		$url = $this->isAjax ? $this->ajaxUrl : $this->restBase . trailingslashit($endpoint);

		if ($method !== 'POST' && !empty($aArgs)) {
			$url = add_query_arg($aArgs, $url);
		}

		$url = trim($url, '/');
		curl_setopt($ch, CURLOPT_URL, $url);

		if ($this->isEnableUserLogin) {
			if (!$this->isAjax) {
				curl_setopt($ch, CURLOPT_USERPWD, $this->aCurrentUser['username'] . ':' . $this->aCurrentUser['auth']);
			} else {
				$this->ajaxLogin($this->aCurrentUser['username']);
			}
		}

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

		if ($method === 'POST') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aArgs));
		}

		if ($this->isAjax) {
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->getCookiePath());
		}

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		$output = curl_exec($ch);

		if (curl_errno($ch)) {
			$errMsg = curl_error($ch);
		}
		curl_close($ch);

		$this->logout();
		$this->isAjax = false;

		if ( isset( $errMsg ) ) {
			return [
				'status'  => 'error',
				'message' => $errMsg
			];
		}

		$aOutput = is_array( $output ) ? $output : json_decode( $output, true );

		if ( isset( $aOutput['data'] ) && isset( $aOutput['data']['status'] ) && $aOutput['data']['status'] == 200 ) {
			return [
				'status'  => 'error',
				'message' => $aOutput['message']
			];
		}

		return empty( $aOutput ) ? $output : $aOutput;
	}

	public function restGET($endpoint, array $aArgs = [])
	{
		return $this->restAPI($endpoint, 'GET', $aArgs);
	}

	public function restPOST($endpoint, array $aArgs = [])
	{
		return $this->restAPI($endpoint, 'POST', $aArgs);
	}

	public function restPUT($endpoint, array $aArgs = [])
	{
		return $this->restAPI($endpoint, 'PUT', $aArgs);
	}

	public function restDELETE($endpoint, array $aArgs = [])
	{
		return $this->restAPI($endpoint, 'DELETE', $aArgs);
	}

	public function restPATCH($endpoint, array $aArgs = [])
	{
		return $this->restAPI($endpoint, 'PATCH', $aArgs);
	}
}
