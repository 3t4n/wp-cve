<?php
	require_once IWP_ADMIN_PATH . 'includes/iwpApiManager.php';
	require_once IWP_ADMIN_PATH . 'responses/iwpTopicsResponse.php';
	require_once IWP_ADMIN_PATH . 'models/iwpTopicModel.php';

	class iwpTopicsModel {

		/** @var array|iwpTopicModel[] */
		private $topics;

		/**
		 */
		public function __construct() {
			$this->topics = array();
		}

		/**
		 * @return iwpTopicModel[]
		 */
		final public function getTopics() {
			return $this->topics;
		}

		/**
		 * @param $appResponse iwpTopicsResponse
		 */
		private function addTopics($appResponse) {
			if ($appResponse->getInternalCode() === iwpTopicsResponse::GET_TOPICS_OK) {
				$topicList = [];
				$data = json_decode($appResponse->getData(), false);
				$data = is_array($data) ? array_shift($data) : $data;
				foreach ($data as $app) {
					if (isset($app->id, $app->name, $app->code, $app->visible) && $app->visible) {
						// Revisamos por si acaso que las propiedades existen. De lo contrario, seguimos con la siguiente
						// Solamente trabajamos con los topics visibles
						$topicList[$app->id] = new iwpTopicModel($app->id, $app->name, $app->code);
					}
				}
				$this->topics = $topicList;
			}
		}

		/* Llamadas a la consola */
		/**
		 * @return iwpTopicsResponse
		 */
		final public function consoleGetTopics() {
			$response = iwpApiManager::getTopicsPagination(0,100000);
			$this->addTopics($response);
			return $response;
		}
	}