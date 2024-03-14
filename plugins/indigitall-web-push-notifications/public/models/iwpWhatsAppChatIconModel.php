<?php

	class iwpWhatsAppChatIconModel {
		const ICON_OPTION_DEFAULT   = 'default';
		const ICON_OPTION_CUSTOMIZED= 'customized';
		const POSITION_LEFT         = 'l';
		const POSITION_RIGHT        = 'r';
		const DEFAULT_COLOR         = '#00bb2d';
		const ICON_TYPE_WHATSAPP    = 'whatsApp';
		const ICON_TYPE_SUPPORT     = 'support';
		const ICON_TYPE_CHAT        = 'chat';
		const ICON_TYPE_QUESTION    = 'question-bubble';
		const ICON_TYPE_CUSTOM      = 'custom';
		const BUBBLE_TYPE_NONE      = '';
		const BUBBLE_TYPE_SHOW      = 'show';
		const BUBBLE_TYPE_HOVER     = 'hover';
		const DEFAULT_SLEEP         = 5;

		/** @var string */
		private $position;

		/** @var string */
		private $color;

		/** @var int|null */
		private $imageId;

		/** @var string */
		private $imageName;

		/** @var boolean */
		private $transparent;

		/** @var string|boolean */
		private $bubble;

		/** @var string */
		private $bubbleText;

		/** @var int */
		private $sleep;

		/**
		 * @param string $position
		 * @param string $color
		 * @param int|null $imageId
		 * @param string $imageName
		 * @param bool $transparent
		 * @param string $bubble
		 * @param string $bubbleText
		 * @param int $sleep
		 */
		public function __construct(
			$position    = self::POSITION_RIGHT,
			$color       = self::DEFAULT_COLOR,
			$imageId     = null,
			$imageName   = self::ICON_TYPE_WHATSAPP,
			$transparent = false,
			$bubble      = self::BUBBLE_TYPE_NONE,
			$bubbleText  = '',
			$sleep       = self::DEFAULT_SLEEP
		) {
			$this->position    = $position;
			$this->color       = $color;
			$this->imageId     = $imageId;
			$this->imageName   = $imageName;
			$this->transparent = $transparent;
			$this->bubble      = $bubble;
			$this->bubbleText  = $bubbleText;
			$this->sleep       = $sleep;
		}

		/**
		 * Si tenemos una posición definida y es 'izquierda', la devolvemos. En el resto de casos, devolvemos 'derecha'
		 * @return string
		 */
		public function getPosition() {
			if (!empty($this->position) && ($this->position === self::POSITION_LEFT)) {
				return self::POSITION_LEFT;
			}
			return self::POSITION_RIGHT;
		}

		/**
		 * Si tenemos un color definido, lo devolvemos. De lo contrario, devolvemos el color predeterminado
		 * @return string
		 */
		public function getColor() {
			if ($this->transparent) {
				// Tenemos color transparente
				return 'transparent';
			}
			if (!empty($this->color)) {
				return $this->color;
			}
			// No tenemos color definido y devolvemos el predeterminado
			return self::DEFAULT_COLOR;
		}

		/**
		 * Devolvemos la URL de la imagen elegida o del icono elegido. En última instancia, devolvemos el icono de
		 *      WhatsApp. Si de forma remota no hubiese ningún icono, devolvemos null.
		 * @return string|null
		 */
		public function getImageUrl() {
			if (($this->imageName === self::ICON_TYPE_CUSTOM) && ($url = wp_get_attachment_image_url($this->imageId, 'full'))) {
				return $url;
			}

			$icon = "images/{$this->imageName}-icon.svg";
			$path = IWP_ADMIN_PATH . $icon;
			if (file_exists($path)) {
				return IWP_ADMIN_URL . $icon;
			}

			// Icono de WhatsApp será el icono predeterminado
			$defaultIcon = "images/" . self::ICON_TYPE_WHATSAPP . "-icon.svg";
			$path = IWP_ADMIN_PATH . $defaultIcon;
			if (file_exists($path)) {
				return IWP_ADMIN_URL . $defaultIcon;
			}
			// No debería suceder porque el icono predeterminado debería existir. Better safe than sorry
			return null;
		}

		/**
		 * Si tenemos definido un tipo de bubble, lo devolvemos. Para el resto de casos, devolvemos vacío
		 * @return string|boolean
		 */
		public function getBubble() {
			if (($this->bubble === self::BUBBLE_TYPE_SHOW) || ($this->bubble === self::BUBBLE_TYPE_HOVER)) {
				return $this->bubble;
			}
			return self::BUBBLE_TYPE_NONE;
		}

		/**
		 * Si tenemos definido el texto del bubble, lo devolvemos. Si no, devolvemos el texto predeterminado
		 * @return string
		 */
		public function getBubbleText() {
			if (!empty($this->bubbleText)) {
				return $this->bubbleText;
			}
			return '';
//			return __('Hi', 'iwp-text-domain');
		}

		/**
		 * Si tenemos definido los segundos, lo devolvemos. Si no, devolvemos los segundos predeterminados
		 * @return int
		 */
		public function getSleep() {
			return $this->sleep;
		}
	}