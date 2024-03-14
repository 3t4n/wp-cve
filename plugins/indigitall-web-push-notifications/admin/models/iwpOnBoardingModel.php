<?php
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpApiManager.php';
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpLoginResponse.php';

	class iwpOnBoardingModel {

		/** @var string  */
		private $userName;

		/** @var string  */
		private $password;

		/** @var bool  */
		private $has2FA;

		/** @var string  */
		private $secretKey;

		/** @var string  */
		private $token;

		/** @var string  */
		private $short2FaToken;

		/** @var string  */
		private $long2FaToken;

		/** @var string  */
		private $domain;

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->userName          = '';
			$this->password          = '';
			$this->has2FA            = false;
			$this->secretKey         = '';
			$this->token             = '';
			$this->short2FaToken     = '';
			$this->long2FaToken      = '';
			$this->domain            = '';
		}

		/**
		 * @return string
		 */
		final public function getUserName() {
			return $this->userName;
		}

		/**
		 * @param string $userName
		 */
		final public function setUserName($userName) {
			$this->userName = $userName;
		}

		/**
		 * @return string
		 */
		final public function getPassword() {
			return $this->password;
		}

		/**
		 * @param string $password
		 */
		final public function setPassword($password) {
			$this->password = $password;
		}

		/**
		 * @return bool
		 */
		final public function getHas2FA() {
			return $this->has2FA;
		}

		/**
		 * @param bool $has2FA
		 */
		final public function setHas2FA($has2FA) {
			$this->has2FA = $has2FA;
		}

		/**
		 * @return string
		 */
		final public function getSecretKey() {
			return $this->secretKey;
		}

		/**
		 * @param string $secretKey
		 */
		final public function setSecretKey($secretKey) {
			$this->secretKey = $secretKey;
		}

		/**
		 * @return string
		 */
		final public function getToken() {
			return $this->token;
		}

		/**
		 * @param string $token
		 */
		final public function setToken($token) {
			$this->token = $token;
		}

		/**
		 * @return string
		 */
		final public function getShort2FAToken() {
			return $this->short2FaToken;
		}

		/**
		 * @param string $short2FaToken
		 */
		final public function setShort2FAToken($short2FaToken) {
			$this->short2FaToken = $short2FaToken;
		}

		/**
		 * @return string
		 */
		final public function getLong2FAToken() {
			return $this->long2FaToken;
		}

		/**
		 * @param string $long2FaToken
		 */
		final public function setLong2FAToken($long2FaToken) {
			$this->long2FaToken = $long2FaToken;
		}

		/**
		 * @return string
		 */
		final public function getDomain() {
			return $this->domain;
		}

		/**
		 * @param string $domain
		 */
		final public function setDomain($domain) {
			$this->domain = iwpAdminUtils::sanitizeText($domain);
		}

		/* Llamadas a la consola */
		/**
		 * @return iwpLoginResponse
		 */
		final public function consoleLogin() {
			return iwpApiManager::login($this->userName, $this->password, $this->domain);
		}

		/**
		 * @return iwpLoginResponse
		 */
		final public function recoverPasswordEmail() {
			return iwpApiManager::recoverPassword($this->userName, $this->domain);
		}

		/**
		 * @return iwpRefresh2FaResponse
		 */
		final public function refresh2Fa() {
			return iwpApiManager::refresh2FAToken();
		}

		/**
		 * @return iwpValidate2FaResponse
		 */
		final public function validate2Fa() {
			return iwpApiManager::validate2FAToken($this->token);
		}
	}