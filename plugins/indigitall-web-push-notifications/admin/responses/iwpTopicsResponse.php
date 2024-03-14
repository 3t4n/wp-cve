<?php
	require_once IWP_PLUGIN_PATH . 'admin/responses/iwpApiManagerResponse.php';

	class iwpTopicsResponse extends iwpApiManagerResponse {

		const GET_TOPICS_OK = 0;
		const GET_TOPICS_KO = 1;
		const GET_TOPICS_EMPTY = 2;

		const CREATE_TOPIC_OK = 0;
		const CREATE_TOPIC_KO = 1;
		const CREATE_TOPIC_CODE_EXISTS = 2;

		const UPDATE_TOPIC_OK = 0;
		const UPDATE_TOPIC_KO = 1;
		const UPDATE_TOPIC_CODE_EXISTS = 2;

		const DELETE_TOPIC_OK = 0;
		const DELETE_TOPIC_KO = 1;

		public function __construct($internalCode, $data = '', $message = '') {
			parent::__construct($internalCode, $data, $message);
		}
	}