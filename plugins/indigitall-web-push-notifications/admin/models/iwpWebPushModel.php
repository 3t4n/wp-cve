<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_ADMIN_PATH . 'includes/iwpApiManager.php';
	require_once IWP_ADMIN_PATH . 'includes/iwpAdminUtils.php';
	require_once IWP_ADMIN_PATH . 'responses/iwpWebPushResponse.php';

	class iwpWebPushModel {

		/** @var string */
		private $name;

		/** @var int|null */
		private $campaignId;

		/** @var bool */
		private $enabled;

		/** @var string */
		private $title;

		/** @var string */
		private $body;

		/** @var string */
		private $url;

		/** @var int|null */
		private $imageId;

		/** @var string */
		private $imageUrl;

		/** @var string */
		private $imageUri;

		/** @var array */
		private $topicsCode;

		/** @var bool */
		private $isWelcomePush;

		/**
		 */
		public function __construct() {
			$this->name          = '';
			$this->campaignId    = null;
			$this->enabled       = false;
			$this->title         = '';
			$this->body          = '';
			$this->url           = '';
			$this->imageId       = null;
			$this->imageUrl      = '';
			$this->imageUri      = '';
			$this->topicsCode    = array();
			$this->isWelcomePush = false;
		}

		/**
		 * @return string
		 */
		public function getName() {
			return $this->name;
		}

		/**
		 * @param string $name
		 */
		public function setName($name) {
			$name = remove_accents($name);
			$name = sanitize_file_name($name);
			$name = mb_substr($name,0, 12, 'UTF-8');
			$name = str_replace('  ', ' ', $name);
			$name = str_replace(' ', '-', trim($name));
			$this->name = "WordPress_" . preg_replace('/[^A-Za-z0-9\-]/', '', $name); // Removes special chars.
		}

		/**
		 * @return int|null
		 */
		public function getCampaignId() {
			return $this->campaignId;
		}

		/**
		 * @param int|null $campaignId
		 */
		public function setCampaignId($campaignId) {
			$this->campaignId = (int)$campaignId;
		}

		/**
		 * @return bool
		 */
		public function isEnabled() {
			return $this->enabled;
		}

		/**
		 * @param bool $enabled
		 */
		public function setEnabled($enabled) {
			$this->enabled = filter_var($enabled, FILTER_VALIDATE_BOOLEAN);
		}

		/**
		 * @return string
		 */
		public function getTitle() {
			return $this->title;
		}

		/**
		 * @param string $title
		 */
		public function setTitle($title) {
			$this->title = iwpAdminUtils::sanitizeText($title);
		}

		/**
		 * @return string
		 */
		public function getBody() {
			return $this->body;
		}

		/**
		 * @param string $body
		 */
		public function setBody($body) {
			$this->body = iwpAdminUtils::sanitizeText($body);
		}

		/**
		 * @return string
		 */
		public function getUrl() {
			return $this->url;
		}

		/**
		 * @param string $url
		 */
		public function setUrl($url) {
			$this->url = $url;
		}

		/**
		 * @return int|null
		 */
		public function getImageId() {
			return $this->imageId;
		}

		/**
		 * @param int|null $imageId
		 */
		public function setImageId($imageId) {
			$this->imageId = $imageId;
		}

		/**
		 * @return string
		 */
		public function getImageUrl() {
			return $this->imageUrl;
		}

		/**
		 * @param string $imageUrl
		 */
		public function setImageUrl($imageUrl) {
			$this->imageUrl = $imageUrl;
		}

		/**
		 * @return string
		 */
		public function getImageUri() {
			return $this->imageUri;
		}

		/**
		 * @param string $imageUri
		 */
		public function setImageUri($imageUri) {
			$this->imageUri = $imageUri;
		}

		/**
		 * @return array
		 */
		public function getTopicsCode() {
			return $this->topicsCode;
		}

		/**
		 * @param array $topicsCode
		 */
		public function setTopicsCode($topicsCode) {
			$this->topicsCode = $topicsCode;
		}

		/**
		 * @return bool
		 */
		public function isWelcomePush() {
			return $this->isWelcomePush;
		}

		/**
		 * @param bool $isWelcomePush
		 */
		public function setIsWelcomePush($isWelcomePush) {
			$this->isWelcomePush = $isWelcomePush;
		}




		/* Llamadas a la consola */

		/**
		 * @return iwpWebPushResponse
		 */
		final public function consoleGetWelcomePush() {
			if (is_null($this->campaignId)) {
				return new iwpWebPushResponse(iwpWebPushResponse::WEB_PUSH_NO_CAMPAIGN);
			}

			$response = iwpApiManager::getWelcomePushByID($this->campaignId);

			if ($response->getInternalCode() === iwpWebPushResponse::WEB_PUSH_OK) {
				$data = json_decode($response->getData(), false);
				$this->name          = $data->name;
				$this->campaignId    = $data->id;
				$this->enabled       = $data->enabled;
				$this->title         = $data->properties->title;
				$this->body          = $data->properties->body;
				$this->url           = $data->properties->action->url;
				$this->setCampaignImage($data->properties);
				$this->isWelcomePush = true;
			}
			return $response;
		}

		/**
		 * @return iwpWebPushResponse
		 */
		final public function consoleCreateWebPush() {
			$this->campaignId = 0;
			$body = ($this->isWelcomePush ? $this->getWelcomePushBody() : $this->getWebPushBody());

			$response = iwpApiManager::createWebPush($body);
			if ($response->getInternalCode() === iwpWebPushResponse::WEB_PUSH_OK) {
				// El push se ha creado correctamente y es la welcomePush.  Almacenamos su id
				$data = json_decode($response->getData(), false);
				$this->name          = $data->name;
				$this->campaignId    = $data->id;
				$this->setCampaignImage($data->properties);
				if ($this->isWelcomePush) {
					update_option(iwpPluginOptions::WELCOME_PUSH_ID, $data->id);
				}
			}
			return $response;
		}

		/**
		 * @return iwpWebPushResponse
		 */
		final public function consoleUpdateWebPush() {
			$this->imageUrl = get_option(iwpPluginOptions::WELCOME_PUSH_IMAGE_URL, null);
			$body = ($this->isWelcomePush ? $this->getWelcomePushBody() : $this->getWebPushBody());

			$response = iwpApiManager::updateWebPush($body, $this->campaignId);
			if ($this->isWelcomePush && ($response->getInternalCode() === iwpWebPushResponse::WEB_PUSH_OK)) {
				// El push se ha actualizado correctamente.
				$data = json_decode($response->getData(), false);
				$this->setCampaignImage($data->properties);
			}
			return $response;
		}

		/**
		 * @return iwpWebPushResponse
		 */
		final public function consoleUpdateWebPushImage() {
			$body = array(
				'image' => $this->imageUri
			);
			$response =  iwpApiManager::updateWebPushImage($body, $this->campaignId);
			if ($response->getInternalCode() === iwpWebPushResponse::WEB_PUSH_OK) {
				// El push se ha actualizado correctamente.
				$data = json_decode($response->getData(), false);
				$this->setCampaignImage($data->properties);
			}
			return $response;
		}

		/**
		 * @return iwpWebPushResponse
		 */
		final public function consoleChangeWebPushStatus() {
			$body = array(
				"applicationId" => (int)get_option(iwpPluginOptions::APPLICATION_ID, 0),
				'enabled' => $this->isEnabled()
			);

			return iwpApiManager::updateWebPush($body, $this->campaignId);
		}

		/**
		 * @return iwpWebPushResponse
		 */
		final public function consoleSendWebPush() {
			return iwpApiManager::sendWebPush($this->campaignId);
		}

		private function getWelcomePushBody() {
			$ret = array(
				"name" => (!empty($this->name) ? $this->name : "plugin_wordpress_welcome_push"),
				"applicationId" => (int)get_option(iwpPluginOptions::APPLICATION_ID, 0),
				"enabled" => $this->enabled,
				"properties" => array(
					"title" => $this->title,
					"body" => $this->body,
					"action" => array(
						"destroy" => true,
						"type" => "url",
						"url" => $this->url
					)
				),
				"filters" => array(
					"platforms" => array("safari", "webpush")
				),
				"triggers" => array(
					"welcome" => array(
						"time" => 0
					)
				)
			);
			if (!empty($this->imageUrl)) {
				$ret['properties']['image'] = $this->imageUrl;
			}
			return $ret;
		}

		private function getWebPushBody() {
			$ret = array(
				"name" => $this->name,
				"applicationId" => (int)get_option(iwpPluginOptions::APPLICATION_ID, 0),
				"enabled" => $this->enabled,
				"properties" => array(
					"title" => $this->title,
					"body" => $this->body,
					"action" => array(
						"destroy" => true,
						"type" => "url",
						"url" => $this->url
					)
				),
				"filters" => array(
					"platforms" => array("safari", "webpush")
				)
			);
			if (!empty($this->topicsCode)) {
				$ret['properties']['action']['topics'] = $this->topicsCode;
			}
			return $ret;
		}

		private function setCampaignImage($property) {
			if (!empty($property->image)) {
				$image = $property->image;
				$this->imageUrl = $image;
				$this->imageUri = '';
				$this->imageId  = null;

				$image = iwpAdminUtils::getImageByName($image);
				if (!is_null($image)) {
					$this->imageUrl      = $image['url'];
					$this->imageUri      = $image['uri'];
					$this->imageId       = $image['id'];
				}
				update_option(iwpPluginOptions::WELCOME_PUSH_IMAGE_URL, $this->imageUrl);
			}
		}
	}