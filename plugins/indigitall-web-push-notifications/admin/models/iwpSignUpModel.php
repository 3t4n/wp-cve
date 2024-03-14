<?php
	require_once IWP_ADMIN_PATH . 'includes/iwpApiManager.php';
	require_once IWP_ADMIN_PATH . 'responses/iwpSignUpResponse.php';

	class iwpSignUpModel {

		const DEFAULT_ORIGIN = 'Plugin-WordPress';

		/** @var string */
		private $email;

		/** @var string */
		private $password;

		/** @var int */
		private $country;

		/** @var bool */
		private $newsletterCheckbox;

		/**
		 */
		public function __construct($email, $password, $country, $newsletterCheckbox) {
			$this->email = $email;
			$this->password = $password;
			$this->country = $country;
			$this->newsletterCheckbox = $newsletterCheckbox;
		}

		/* Llamadas a la consola */

		/**
		 * @return iwpSignUpResponse
		 */
		final public function consoleSignUpUser() {
			$signUpBody = array(
				"action" => "sign-up-free",
				"email" => $this->email,
				"password" => $this->password,
				"country" => $this->country,
				"acceptNewsletters" => $this->newsletterCheckbox,
				"signUpOrigin" => self::DEFAULT_ORIGIN
			);
			return iwpApiManager::createUser($signUpBody);
		}
	}