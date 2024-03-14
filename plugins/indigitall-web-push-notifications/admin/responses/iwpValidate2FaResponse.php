<?php
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpApiManagerResponse.php';

	class iwpValidate2FaResponse extends iwpApiManagerResponse {

		const VALIDATE_2FA_OK = 0;
		const VALIDATE_2FA_KO = 1;
		const VALIDATE_2FA_NO_PERMISSIONS = 3;

		public function __construct($internalCode, $data = '', $message = '') {
			parent::__construct($internalCode, $data, $message);
		}
	}