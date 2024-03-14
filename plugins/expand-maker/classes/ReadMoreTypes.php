<?php
abstract class ReadMoreTypes {
	private $id;
	private $type;
	private $title;
	private $options;
	private $mainSavedObj;
	public $functionsObj;

	abstract public function renderContent();
	abstract public function getRemoveOptions();

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return (int)$this->id;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setType($type) {
		$this->type = $type;
	}

	public function getType() {
		return $this->type;
	}

	public function setOptions($options) {
		$this->options = $options;
	}

	public function getOptions() {
		return $this->options;
	}

	private function getViews() {
		return apply_filters('yrmTypesViewFiles', array());
	}

	public function renderView() {
		$functions = $this->functionsObj;
		$allViews = $this->getViews();
		$this->renderScripts();
		require_once(YRM_VIEWS.'typesSettingsPage.php');
	}

	private function renderScripts() {
        wp_enqueue_script('jquery-ui-draggable');
    }

    public function create($postData) {
        $postData = ReadMoreTypes::parseDataFromPost($postData);

        $id = @$postData['yrm-post-id'];
        $this->setId($id);

        // set up apply filter
        YrmConfig::optionsValues();

        foreach ($postData as $name => $value) {
            $defaultData = $this->getDefaultDataByName($name);
            if (empty($defaultData['type'])) {
                $defaultData['type'] = 'string';
            }
            $sanitizedValue = $this->sanitizeValueByType($value, $defaultData['type']);
            $this->insertIntoSanitizedData(array('name' => $name,'value' => $sanitizedValue));
        }

        $this->save();
    }

    public function insertIntoSanitizedData($sanitizedData) {
        if (!empty($sanitizedData)) {
            $this->sanitizedData[$sanitizedData['name']] = $sanitizedData['value'];
        }
    }

    public function getDefaultDataByName($optionName) {
        global $YRM_OPTIONS;

        if(empty($YRM_OPTIONS)) {
            return array();
        }
        foreach ($YRM_OPTIONS as $option) {
            if ($option['name'] == $optionName) {
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
            case 'yrm':
                $sanitizedValue = $value;
                break;
            case 'email':
                $sanitizedValue = sanitize_email($value);
                break;
            case "checkbox":
                $sanitizedValue = sanitize_text_field($value);
                break;
            default:
                $sanitizedValue = sanitize_text_field($value);
                break;
        }

        return $sanitizedValue;
    }

    public function save() {
    	global $wpdb;
    	$options = $this->sanitizedData;
    	$options = json_encode($options);
		$id = $this->getId();
		$title = $this->sanitizedData['yrm-title'];
		$type = $this->sanitizedData['yrm-type'];
		$width = '';
		$height = '';
		$duration = '';
	
		$data = array(
			'type' => $type,
			'expm-title' => $title,
			'button-width' => $width,
			'button-height' => $height,
			'animation-duration' => $duration,
			'options' => $options
		);
	
		$format = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
		);
		if(!$id) {
			$wpdb->insert($wpdb->prefix.'expm_maker', $data, $format);
			$readMoreId = $wpdb->insert_id;
		}
		else {
			$data['id'] = $id;
			$wpdb->update($wpdb->prefix.'expm_maker', $data, array('id'=>$id), $format, array('%d'));
			$readMoreId = $id;
		}

		wp_redirect(admin_url()."admin.php?page=button&readMoreId=".esc_attr($readMoreId)."&yrm_type=".esc_attr($type)."&saved=1");
    }

    public static function parseDataFromPost($data) {
		$cdData = array();

		foreach ($data as $key => $value) {
			if (strpos($key, 'yrm') === 0) {
				$cdData[$key] = $value;
			}
		}

		return $cdData;
	}

	private function setReadMoreSavedData() {
		$id = $this->getId();
		global $wpdb;

		$getSavedSql = $wpdb->prepare("SELECT * FROM ".sanitize_text_field($wpdb->prefix)."expm_maker WHERE id = %d", $id);
		$result = $wpdb->get_row($getSavedSql, ARRAY_A);

		$this->setTitle($result['expm-title']);
		$this->setType($result['type']);

		$options = $result['options'];
		$options = json_decode($options, true);

		$this->setOptions($options);

		return $result;
	}

	public function prepareSavedValue() {
		YrmConfig::optionsValues();
		$this->setReadMoreSavedData();
	}

	public function getOptionValue($optionName, $forceDefaultValue = false) {
        $defaultData = $this->getDefaultDataByName($optionName);
        $savedData = $this->getOptions();
        $optionValue = null;

        if(empty($defaultData['type'])) {
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

        if(isset($defaultData['ver']) && $defaultData['ver'] > YRM_PKG) {
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
}