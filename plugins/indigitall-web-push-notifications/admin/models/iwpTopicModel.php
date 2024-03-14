<?php
	require_once IWP_ADMIN_PATH . 'includes/iwpApiManager.php';
	require_once IWP_ADMIN_PATH . 'responses/iwpTopicsResponse.php';

	class iwpTopicModel {

		/** @var int|null */
		private $id;
		/** @var string|null */
		private $name;
		/** @var string|null */
		private $code;

		/**
		 * @param $id
		 * @param $name
		 * @param $code
		 */
		public function __construct($id = null, $name = null, $code = null) {
			$this->id   = $id;
			$this->name = iwpAdminUtils::sanitizeText($name);
			$this->code = $code;
		}

		/**
		 * @return int|null
		 */
		final public function getId() {
			return $this->id;
		}

		/**
		 * @return string|null
		 */
		final public function getName() {
			return $this->name;
		}

		/**
		 * @return string|null
		 */
		final public function getCode() {
			return $this->code;
		}

		/**
		 * @return array
		 */
		final public function getCreateBody() {
			return array(
				'name' => $this->name,
				'code' => $this->code,
				'visible' => true,
			);
		}

		/**
		 * @return array
		 */
		final public function getUpdateBody() {
			return array(
				'name' => $this->name,
			);
		}

		/* Llamadas a la consola */
		/**
		 * @return iwpTopicsResponse
		 */
		final public function consoleCreateTopic() {
			$body = array('topics' => [$this->getCreateBody()]);
			return iwpApiManager::createTopic($body);
		}

		/**
		 * @return iwpTopicsResponse
		 */
		final public function consoleUpdateTopic() {
			return iwpApiManager::updateTopic($this->getUpdateBody(), $this->getId());
		}

		/**
		 * @return iwpTopicsResponse
		 */
		final public function consoleDeleteTopic() {
			$body = array('topics' => [$this->getId()]);
			return iwpApiManager::deleteTopic($body);
		}
	}