<?php
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpApiManagerResponse.php';

	class iwpRefresh2FaResponse extends iwpApiManagerResponse {

		const REFRESH_OK = 0;
		const REFRESH_KO = 1;

		public function __construct($internalCode, $data = '', $message = '') {
			parent::__construct($internalCode, $data, $message);
		}
	}