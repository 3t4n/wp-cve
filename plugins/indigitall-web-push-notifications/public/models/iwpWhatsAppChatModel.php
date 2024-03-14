<?php
require_once 'iwpWhatsAppChatIconModel.php';
require_once 'iwpWhatsAppChatWindowModel.php';
require_once 'iwpWhatsAppChatQrModel.php';

	class iwpWhatsAppChatModel {
		const CHAT_TYPE_DISABLE    = 'disable';
		const CHAT_TYPE_QR         = 'qr';
		const CHAT_TYPE_CUSTOMIZED = 'customized';

		/** @var string */
		private $chatType;
		/** @var boolean */
		private $enabled;
		/** @var string */
		private $phone;
		/** @var string */
		private $welcomeMessage;
		/** @var iwpWhatsAppChatIconModel|null */
		private $icon;
		/** @var iwpWhatsAppChatWindowModel|null */
		private $window;
		/** @var iwpWhatsAppChatQrModel|null */
		private $qr;

		/**
		 * @param bool $enabled
		 * @param string $phone
		 * @param string $welcomeMessage
		 * @param string $chatType
		 */
		public function __construct(
			$enabled = false,
			$phone = '',
			$welcomeMessage = '',
			$chatType = 'disable'
		) {
			$this->enabled          = $enabled;
			$this->phone            = $phone;
			$this->welcomeMessage   = $welcomeMessage;
			$this->chatType         = $chatType;
		}

		/**
		 * @return string
		 */
		public function getChatType() {
			return $this->chatType;
		}

		/**
		 * Devolverá 'true' si está activo y tenemos un teléfono definido. De lo contrario, devuelve 'false'
		 * @return bool
		 */
		public function isEnabled() {
			return ($this->enabled && ! empty($this->phone));
		}

		/**
		 * @return string
		 */
		public function getPhone() {
			return $this->phone;
		}

		/**
		 * @return string
		 */
		public function getWelcomeMessage() {
			if (!empty($this->welcomeMessage)) {
				return $this->welcomeMessage;
			}
			return '';
//			return __('Hello, I have a question', 'iwp-text-domain');
		}

		/**
		 * @return iwpWhatsAppChatIconModel
		 */
		public function getIcon() {
			if (!is_null($this->icon)) {
				return $this->icon;
			}
			return new iwpWhatsAppChatIconModel();
		}

		/**
		 * @param iwpWhatsAppChatIconModel $icon
		 */
		public function setIcon($icon) {
			$this->icon = $icon;
		}

		/**
		 * @return iwpWhatsAppChatWindowModel
		 */
		public function getWindow() {
			if (!is_null($this->window)) {
				return $this->window;
			}
			return new iwpWhatsAppChatWindowModel();
		}

		/**
		 * @param iwpWhatsAppChatWindowModel $window
		 */
		public function setWindow($window) {
			$this->window = $window;
		}

		/**
		 * @return iwpWhatsAppChatQrModel
		 */
		public function getQr() {
			if (!is_null($this->qr)) {
				return $this->qr;
			}
			return new iwpWhatsAppChatQrModel();
		}

		/**
		 * @param iwpWhatsAppChatQrModel $qr
		 */
		public function setQr($qr) {
			$this->qr = $qr;
		}
	}