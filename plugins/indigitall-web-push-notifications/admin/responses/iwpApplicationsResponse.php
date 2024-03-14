<?php
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpApiManagerResponse.php';

	class iwpApplicationsResponse extends iwpApiManagerResponse {

		const GET_APPLICATIONS_OK = 0;
		const GET_APPLICATIONS_KO = 1;
		const GET_APPLICATIONS_EMPTY = 2;

		const CREATE_APPLICATIONS_OK = 3;
		const CREATE_APPLICATIONS_KO = 4;

		public function __construct($internalCode, $data = '', $message = '') {
			$checkData = json_decode($data,false);
			if (empty($checkData)) {
				// Si los datos recibidos están vacíos, cambiamos el código a vacío
				$internalCode = self::GET_APPLICATIONS_EMPTY;
			}

			parent::__construct($internalCode, $data, $message);
		}
	}