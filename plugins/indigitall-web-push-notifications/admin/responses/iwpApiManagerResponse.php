<?php
	abstract class iwpApiManagerResponse {
		const ERROR_TRY_CATCH = -1;

		/** @var int */
		private $internalCode;

		/** @var string */
		private $message;

		/** @var string */
		private $data;

		/**
		 * @param $internalCode
		 * @param $data
		 * @param $message
		 */
		public function __construct($internalCode, $data, $message) {
			$this->internalCode = $internalCode;
			$this->data         = $data;
			$this->message      = $message;
		}

		/**
		 * @return int
		 */
		final public function getInternalCode() {
			return $this->internalCode;
		}

		/**
		 * @return string
		 */
		final public function getData() {
			return $this->data;
		}

		/**
		 * @return string
		 */
		final public function getMessage() {
			return $this->message;
		}
	}