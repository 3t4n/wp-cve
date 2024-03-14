<?php

	class iwpWhatsAppChatQrModel {
		const DEFAULT_COLOR = '#00bb2d';

		/** @var string */
		private $header;
		/** @var string */
		private $text;
		/** @var string */
		private $color;

		/**
		 * @param string $header
		 * @param string $text
		 */
		public function __construct(
			$header = '',
			$text = '',
			$color = self::DEFAULT_COLOR
		) {
			$this->header   = $header;
			$this->text     = $text;
			$this->color    = $color;
		}

		/**
		 * @return string
		 */
		public function getHeader() {
			return $this->header;
		}

		/**
		 * @return string
		 */
		public function getText() {
			return $this->text;
		}

		/**
		 * @return string
		 */
		public function getColor() {
			return $this->color;
		}
	}