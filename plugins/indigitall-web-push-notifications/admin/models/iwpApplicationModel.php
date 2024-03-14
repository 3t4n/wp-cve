<?php

	class iwpApplicationModel {

		/** @var int */
		private $id;
		/** @var string */
		private $name;
		/** @var string */
		private $publicKey;

		/**
		 * @param $id
		 * @param $name
		 * @param $publicKey
		 */
		public function __construct($id, $name, $publicKey) {
			$this->id        = $id;
			$this->name      = $name;
			$this->publicKey = $publicKey;
		}

		/**
		 * @return int
		 */
		final public function getId() {
			return $this->id;
		}

		/**
		 * @return string
		 */
		final public function getName() {
			return $this->name;
		}

		/**
		 * @return string
		 */
		final public function getPublicKey() {
			return $this->publicKey;
		}
	}