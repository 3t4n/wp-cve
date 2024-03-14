<?php

	class iwpWhatsAppChatWindowModel {
		const DEFAULT_COLOR         = '#00bb2d';
		const ICON_TYPE_NONE        = 'none';
		const ICON_TYPE_SEND        = 'send';
		const ICON_TYPE_CHAT        = 'chat';
		const ICON_TYPE_QUESTION    = 'question';
		const ICON_TYPE_WHATSAPP    = 'whatsApp';
		const DEFAULT_SLEEP         = 5;

		/** @var string */
		private $header;

		/** @var string */
		private $body;

		/** @var string */
		private $color;

		/** @var string */
		private $buttonText;

		/** @var string */
		private $buttonImage;

		/** @var int */
		private $sleep;

		/**
		 * @param string $header
		 * @param string $body
		 * @param string $color
		 * @param string $buttonText
		 * @param string $buttonImage
		 * @param int $sleep
		 */
		public function __construct(
			$header         = '',
			$body           = '',
			$color          = self::DEFAULT_COLOR,
			$buttonText     = '',
			$buttonImage    = self::ICON_TYPE_SEND,
			$sleep          = self::DEFAULT_SLEEP
		) {
			$this->header      = $header;
			$this->body        = $body;
			$this->color       = $color;
			$this->buttonText  = $buttonText;
			$this->buttonImage = $buttonImage;
			$this->sleep       = $sleep;
		}

		/**
		 * @return string
		 */
		public function getHeader() {
			if (!empty($this->header)) {
				return $this->header;
			}
			return '';
//			return __('iurny.com', 'iwp-text-domain');
		}

		/**
		 * @return string
		 */
		public function getBody() {
			if (!empty($this->body)) {
				return $this->body;
			}
			return '';
//			return __("Welcome to iurny's chat", 'iwp-text-domain');
		}

		/**
		 * @return string
		 */
		public function getColor() {
			if (!empty($this->color)) {
				return $this->color;
			}
			return self::DEFAULT_COLOR;
		}

		/**
		 * @return string
		 */
		public function getButtonText() {
			if (!empty($this->buttonText)) {
				return $this->buttonText;
			}
			return '';
//			return __('Open chat', 'iwp-text-domain');
		}

		/**
		 * @return string|null
		 */
		public function getButtonImage() {
			$icon = "images/$this->buttonImage-icon.svg";
			$path = IWP_ADMIN_PATH . $icon;
			if (file_exists($path)) {
				return IWP_ADMIN_URL . $icon;
			}
			// Icono de Send será el icono predeterminado
			$icon = "images/" . self::ICON_TYPE_SEND . "-icon.svg";
			$path = IWP_ADMIN_PATH . $icon;
			if (file_exists($path)) {
				return IWP_ADMIN_URL . $icon;
			}
			// No debería suceder porque el icono predeterminado debería existir. Better safe than sorry
			return null;
		}

		/**
		 * @return int
		 */
		public function getSleep() {
			return $this->sleep;
		}
	}