<?php
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpApiManagerResponse.php';

	class iwpWebPushResponse extends iwpApiManagerResponse {

		const WEB_PUSH_OK = 0;
		const WEB_PUSH_KO = 1;
		const WEB_PUSH_NO_CAMPAIGN = 2;
		const WEB_PUSH_MANDATORY_FIELDS_ERROR = 3;

		public function __construct($internalCode = null, $data = '', $message = '') {
			parent::__construct($internalCode, $data, $message);
		}

	}