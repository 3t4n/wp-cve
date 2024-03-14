<?php
	require_once IWP_ADMIN_PATH . 'includes/iwpApiManager.php';
	require_once IWP_ADMIN_PATH . 'responses/iwpApplicationsResponse.php';
	require_once IWP_ADMIN_PATH . 'models/iwpApplicationModel.php';

	class iwpApplicationsModel {

		/** @var array|iwpApplicationModel[] */
		private $applications;

		/**
		 */
		public function __construct() {
			$this->applications = array();
		}

		/**
		 * @return iwpApplicationModel[]
		 */
		final public function getApplications() {
			return $this->applications;
		}

		private function addApplications($appResponse) {
			if (($appResponse->getInternalCode() === iwpApplicationsResponse::GET_APPLICATIONS_OK)
			    || ($appResponse->getInternalCode() === iwpApplicationsResponse::CREATE_APPLICATIONS_OK)
			) {
				$applicationList = [];
				$data = json_decode($appResponse->getData(), false);
				foreach ($data as $app) {
					if (isset($app->id, $app->name, $app->publicKey)) {
						// Revisamos por si acaso que las propiedades existen. De lo contrario, seguimos con la siguiente
						$applicationList[] = new iwpApplicationModel($app->id, $app->name, $app->publicKey);
					}
				}
				$this->applications = $applicationList;
			}
		}

		/* Llamadas a la consola */
		/**
		 * @return iwpApplicationsResponse
		 */
		final public function consoleGetApplications() {
			$response = iwpApiManager::getApplications();
			$this->addApplications($response);
			return $response;
		}

		/**
		 * @return iwpApplicationsResponse
		 */
		final public function consoleCreateApplication() {
			$response = iwpApiManager::createApplication();
			$this->addApplications($response);
			return $response;
		}
	}