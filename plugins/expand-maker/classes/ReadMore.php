<?php

abstract class ReadMore {

	private $postData;
	private $sanitizedData;
	private $savedData;
	private $savedId;

	public function setPostData($postData) {
		$this->postData = $postData;
	}

	public function getPostData() {
		return $this->postData;
	}

	public function setSavedId($id) {
		$this->savedId = (int)$id;
	}

	public function getSavedId() {
		return $this->savedId;
	}

	public function setSavedData($savedData) {
		$this->savedData = $savedData;
	}

	public function getSavedData() {
		return $this->savedData;
	}

	public function getDBData() {


	}

	public function insertIntoSanitizedData($sanitizedData) {
		if (!empty($sanitizedData)) {
			$this->sanitizedData[$sanitizedData['name']] = $sanitizedData['value'];
		}
	}

	public function getSanitizedData() {
		return $this->sanitizedData;
	}

	public function setSanitizedData($sanitizedData) {
		$this->sanitizedData = $sanitizedData;
	}

	public function getRemoveOptions() {
		return array();
	}

	public function includeOptionsBlock($dataObj) {

	}

	public static function RemoveOption($option) {

		global $YrmRemoveOptions;
		return isset($YrmRemoveOptions[$option]);
	}

	public static function isActiveReadMore($id) {
		$isActiveSaved = get_option('yrm-read-more-'.esc_attr($id));

		if ($isActiveSaved == -1) {
			return false;
		}

		return true;
	}

	public static function allowRender($shortcodeData) {
		if (is_admin()) {
			return true;
		}
		$id = $shortcodeData->getId();
		$status = self::isActiveReadMore($id);
		if(!$status) {
			return false;
		}
		$savedData = $shortcodeData->getDataObj();
		$options = $savedData->getSavedOptions();
		$allowForCurrentDevice = self::allowForCurrentDevice($options, $shortcodeData);
		
		if(!$allowForCurrentDevice) {
			return false;
		}

		$allowForBlogPost = self::allowForBlogPostPage($options);
        if(!$allowForBlogPost) {
            return false;
        }

		return true;
	}

	public static function allowForBlogPostPage($options) {
        $isBlogPostPage = (is_front_page() && is_home());
	    if(!empty($options['hide-button-blog-post']) && $isBlogPostPage) {
	        return false;
        }

        return true;
    }

	public static function allowForCurrentDevice($options, $shortcodeData) {
		$status = true;
		if (!empty($options['show-only-devices'])) {
			$devices = $options['yrm-selected-devices'];
			$hideContent = $options['hide-content'];
			$currentDevice = ReadMoreAdminHelperPro::getUserDevice();
			if(!in_array($currentDevice, $devices)) {
				if($hideContent) {
					$shortcodeData->setToggleContent('');
				}
				$status = false;
			}
		}

		return $status;
	}

	public function includeCustomScript() {

    }

	public static function parseDataFromPost($data) {
		$cdData = array();

		if (empty($data)) {
			return $cdData;
		}

		foreach ($data as $key => $value) {
			if (strpos($key, 'yrm') === 0) {
				$cdData[$key] = $value;
			}
		}

		return $cdData;
	}

	public function saveData() {
		$postData = $this->getPostData();
		$this->filterData($postData);

		$this->save();
	}

	public static function create($postData) {
		$type = $postData['yrm-type'];

		if (empty($type)) {
			return;
		}

		$obj = self::createObjByType($type);

		if (empty($obj)) {
			return;
		}
		$obj->setPostData($postData);
		$obj->saveData();

		$id = $obj->getSavedId();
		wp_redirect(admin_url()."admin.php?page=".esc_attr(YRM_FIND_PAGE)."&farId=".esc_attr($id)."&yrmFindPage=create&saved=1");
	}

	public static function createObjByType($type) {
		$className = ucfirst($type).'TypeReadMore';
		$path = YRM_ADMIN_TYPE_CLASSES.$className.'.php';

		if (!file_exists($path)) {
			return false;
		}
		require_once($path);
		$obj = new $className;

		return $obj;
	}

	public function filterData($filterData) {
		YrmConfig::defaultOptions();
		foreach ($filterData as $name => $value) {
			$defaultData = $this->getDefaultDataByName($name);
			if (empty($defaultData['type'])) {
				$defaultData['type'] = 'string';
			}

			$sanitizedValue = $this->sanitizeValueByType($value, $defaultData['type']);
			$this->insertIntoSanitizedData(array('name' => $name,'value' => $sanitizedValue));
		}
	}

	public function getDefaultDataByName($optionName) {
		global $YRM_OPTIONS;
		if(empty($YRM_OPTIONS)) {
			return array();
		}
		foreach ($YRM_OPTIONS as $option) {
			if (isset($option['name']) && $option['name'] == $optionName) {
				return $option;
			}
		}

		return array();
	}

	public function sanitizeValueByType($value, $type) {
		switch ($type) {
			case 'string':
			case 'number':
				$sanitizedValue = sanitize_text_field($value);
				break;
			case 'html':
				$sanitizedValue = $value;
				break;
			case 'array':
				$sanitizedValue = $this->recursiveSanitizeTextField($value);
				break;
			case 'email':
				$sanitizedValue = sanitize_email($value);
				break;
			case "checkbox":
				$sanitizedValue = sanitize_text_field($value);
				break;
			case "yrm":
				$sanitizedValue = $value;
				break;
			default:
				$sanitizedValue = sanitize_text_field($value);
				break;
		}

		return $sanitizedValue;
	}

	public function recursiveSanitizeTextField($array) {
		if (!is_array($array)) {
			return $array;
		}

		foreach ($array as $key => &$value) {
			if (is_array($value)) {
				$value = $this->recursiveSanitizeTextField($value);
			}
			else {
				/*get simple field type and do sensitization*/
				$defaultData = $this->getDefaultDataByName($key);
				if (empty($defaultData['type'])) {
					$defaultData['type'] = 'string';
				}
				$value = $this->sanitizeValueByType($value, $defaultData['type']);
			}
		}

		return $array;
	}
	/**
	 * Get option value from name
	 * @since 1.0.0
	 *
	 * @param string $optionName
	 * @param bool $forceDefaultValue
	 * @return string
	 */
	public function getOptionValue($optionName, $forceDefaultValue = false) {

		return $this->getOptionValueFromSavedData($optionName, $forceDefaultValue);
	}

	public function getOptionValueFromSavedData($optionName, $forceDefaultValue = false) {

		$defaultData = $this->getDefaultDataByName($optionName);
		$savedData = $this->getSavedData();

		$optionValue = null;

		if (empty($defaultData['type'])) {
			$defaultData['type'] = 'string';
		}

		if (!empty($savedData)) { //edit mode
			if (isset($savedData[$optionName])) { //option exists in the database
				$optionValue = $savedData[$optionName];
			}
			/* if it's a checkbox, it may not exist in the db
			 * if we don't care about it's existence, return empty string
			 * otherwise, go for it's default value
			 */
			else if ($defaultData['type'] == 'checkbox' && !$forceDefaultValue) {
				$optionValue = '';
			}
		}

		if (($optionValue === null && !empty($defaultData['defaultValue'])) || ($defaultData['type'] == 'number' && !isset($optionValue))) {
			$optionValue = $defaultData['defaultValue'];
		}

		if ($defaultData['type'] == 'checkbox') {
			$optionValue = $this->boolToChecked($optionValue);
		}

		if(isset($defaultData['ver']) && $defaultData['ver'] > EXPM_VERSION) {
			if (empty($defaultData['allow'])) {
				return $defaultData['defaultValue'];
			}
			else if (!in_array($optionValue, $defaultData['allow'])) {
				return $defaultData['defaultValue'];
			}
		}

		return $optionValue;
	}

	public function boolToChecked($var) {
		return ($var ? 'checked' : '');
	}

	public static function find($id) {
		$options = CountdownModel::getDataById($id);

		if(empty($options)) {
			return false;
		}
		$type = $options['ycd-type'];

		$typePath = self::getTypePathFormCountdownType($type);
		$className = self::getClassNameCountdownType($type);

		if (!file_exists($typePath.$className.'.php')) {
			return false;
		}

		require_once(esc_attr($typePath).esc_attr($className).'.php');
		$className = __NAMESPACE__.'\\'.esc_attr($className);
		$postTitle = get_the_title($id);

		$typeObj = new $className();
		$typeObj->setId($id);
		$typeObj->setType($type);
		$typeObj->setTitle($postTitle);
		$typeObj->setSavedData($options);

		return $typeObj;
	}

	public function delete() {

	}
}