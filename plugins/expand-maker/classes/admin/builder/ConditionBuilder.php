<?php
namespace yrm;
use \ReadMoreFunctions;

class ConditionBuilder {

	private $columnCount = 3;
	private $valueFromFirst = true;
	private $currentIndex;
	private $nameString = '';
	private $paramKey;
	private $configData;

	public function setColumnCount($columnCount) {
		$this->columnCount = $columnCount;
	}

	public function getColumnCount() {
		return $this->columnCount;
	}

	public function setValueFromFirst($valueFromFirst) {
		$this->valueFromFirst = $valueFromFirst;
	}

	public function getValueFromFirst() {
		return $this->valueFromFirst;
	}

	public function setSavedData($savedData) {
		$this->savedData = $savedData;
	}

	public function getSavedData() {
		return $this->savedData;
	}

	public function setConfigData($configData) {
		$this->configData = $configData;
	}

	public function getConfigData() {
		return $this->configData;
	}

	public function setCurrentIndex($currentIndex) {
		$this->currentIndex = $currentIndex;
	}

	public function getCurrentIndex() {
		return $this->currentIndex;
	}

	public function setNameString($nameString) {
		$this->nameString = $nameString;
	}

	public function getNameString() {
		return $this->nameString;
	}

	public function setParamKey($paramKey) {
		$this->paramKey = $paramKey;
	}

	public function getParamKey() {
		return $this->paramKey;
	}

	public function getChildClassName() {
		$path = explode('\\', get_class($this));

		return array_pop($path);
	}

	public function render() {
		$childClassName = $this->getChildClassName();
		$content = '<div class="yrm-conditions-wrapper" data-child-class="'.esc_attr($childClassName).'">';
		$content .= $this->renderConditions();
		$content .= '</div>';

		return $content;
	}

	private function renderConditions() {
		$savedData = $this->getSavedData();

		if(empty($savedData)) {
			return '<div>'.__('No Data', YRM_LANG).'</div>';
		}
		$conditions = '';

		foreach($savedData as $currentIndex => $data) {
			$conditions .=  $this->getConditionRowFromCurrentData($data, $currentIndex);
		}

		return $conditions;
	}

	private function getConditionRowFromCurrentData($data, $currentIndex) {
		$conditions = '';
		$configData = $this->getConfigData();

		$configValues = $configData['values'];
		$attributes = $configData['attributes'];
		$valueFromFirst = $this->getValueFromFirst();
		$savedData = $this->getSavedData();

		$conditions = '<div class="yrm-condion-wrapper row" data-value-from-first="'.esc_attr($valueFromFirst).'" data-condition-id="'.esc_attr($currentIndex).'">';
		if(empty($data['key3']) && isset($configData['values'][$data['key1']])) {
			$data['key3'] = '';
		}
		$currentConditionIndex = 1;
		foreach($data as $keyName => $value) {
			$paramKey = $keyName;
			// get 3th column key
			if($currentConditionIndex > 1) {
				if($valueFromFirst) {
					if($keyName == 'key3') {
						$paramKey = $data['key1'];
					}
				}
				else {
					$lastIndex = --$currentConditionIndex;
					$paramKey = $data['key'.esc_attr($lastIndex)];
				}
			}
			$this->setParamKey($paramKey);

			if(empty($attributes[$paramKey])) {
				continue;
			}
			++$currentConditionIndex;
			$this->setCurrentIndex($currentIndex);
			$attributes[$paramKey]['savedValue'] = $value;

			$conditions .= $this->renderCurrentConditionRow($configValues, $attributes, $keyName, $currentIndex);
		}
		$conditions .= $this->renderCondtionConfig($currentIndex, $data);
		$conditions .= '</div>';

		return $conditions;
	}

	public function renderCurrentConditionRow($configValues, $attributes, $keyName, $key) {
		$paramKey = $this->getParamKey();
		$nameString = $this->getNameString();
		$name = $nameString.'['.esc_attr($key).']['.esc_attr($keyName).']';
		$currentData = $configValues[$paramKey];
		$currentAttributes = $attributes[$paramKey];
		$conditions = $this->renderConditionRow($name, $currentData, $currentAttributes);

		return $conditions;
	}

	private function renderConditionRow($name, $currentData, $attributes) {
		$fieldHtml = '<div class="col-md-3">';

		$fieldHtml .= $this->renderConditionRowHeader($name, $currentData, $attributes);
		if($attributes['fieldType'] == 'select') {
			$fieldAttributes = $attributes['fieldAttributes'];
			if(!empty($fieldAttributes['multiple'])) {
				$name .= '[]';
			}
			$fieldAttributes['name'] = $name;
			$savedValue = $attributes['savedValue'];
			if(!empty($fieldAttributes['data-select-type']) && $fieldAttributes['data-select-type'] == 'ajax') {
				$currentData = $savedValue;
				$savedValue = @array_keys($savedValue);
			}
			$fieldHtml .= ReadMoreFunctions::yrmSelectBox($currentData, $savedValue, $fieldAttributes);
		}
		$fieldHtml .= '</div>';

		return $fieldHtml;
	}

	private function renderConditionRowHeader($name, $currentData, $attributes) {
		$fieldHtml = '<div class="yrm-condition-header">';

		if(!empty($attributes['label'])) {
			$fieldHtml .= '<label>'.__($attributes['label'], YRM_LANG).'</label>';
		}
		$fieldHtml .= '</div>';

		return $fieldHtml;
	}

	private function renderCondtionConfig($currentConditionIndex, $data) {
		if(empty($data) || $data['key1'] == 'select_settings') {
			return '';
		}
		$html = '<div class="col-md-3"><div class="yrm-condition-header yrm-conditions-buttons"><label></label></div>';
		$html .= '<div data-condition-id="'.esc_attr($currentConditionIndex).'" class="yrm-condition-add btn btn-primary">'.__('Add', YRM_LANG).'</div>';
		$html .= '<div data-condition-id="'.esc_attr($currentConditionIndex).'" class="yrm-condition-delete btn btn-danger">'.__('Delete', YRM_LANG).'</div>';
		$html .= '</div>';

		return $html;
	}

	public function renderConditionRowFromParam($param, $currentIndex) {
		$savedData = array(
			'key1' => $param,
			'key2' => '',
			'key3' => ''
		);
		if($param == 'select_settings') {
			$savedData = array(
				'key1' => $param
			);
		}

		return $this->getConditionRowFromCurrentData($savedData, $currentIndex);
	}

	public function filterForSave() {
		$settings = $this->getSavedData();
		$configData = $this->getConfigData();

		$configValues = $configData['values'];
		$attributes = $configData['attributes'];

		foreach($settings as $index => $setting) {
			$valueFromFirst = $this->getValueFromFirst();
			$valueKey = $setting['key1'];
			if (!$valueFromFirst) {
				$valueKey = $setting['key2'];
			}
			if(empty($setting['key3'])) {
				$settings[$index]['key3'] = array();
				continue;
			}
			$currentAttributes = $attributes[$valueKey]['fieldAttributes'];
			if(!empty($currentAttributes['data-select-type'])) {

				$args = array(
					'post__in' => array_values($setting['key3']),
					'posts_per_page' => 10,
					'post_type'      => $currentAttributes['data-post-type']
				);

				$searchResults = ReadMoreFunctions::getPostTypeData($args);
				$settings[$index]['key3'] = $searchResults;
			}
		}

		return $settings;
	}
}