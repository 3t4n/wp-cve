<?php

class ExpmMultipleChoiceButton {

	private $buttonsData = array();
	private $savedValue = '';
	private $template = array();
	private $buttonPosition = 'right';
	private $fields = array();

	/**
	 * RadioButtons constructor.
	 *
	 * @since 2.2.0
	 *
	 * @param $buttonsData
	 * @param $savedValue
	 */
	public function __construct($buttonsData, $savedValue) {
		$this->setButtonsData($buttonsData);
		$this->setSavedValue($savedValue);
		$this->prepareBuild();
	}

	public function __toString() {
		return $this->render();
	}

	public function setButtonsData($buttonsData) {
		$this->buttonsData = $buttonsData;
	}

	public function getButtonsData() {
		return $this->buttonsData;
	}

	/**
	 * Radio buttons saved value
	 *
	 * @since 2.2.0
	 *
	 * @param string $savedValue
	 */
	public function setSavedValue($savedValue) {
		$this->savedValue = $savedValue;
	}

	public function getSavedValue() {
		return $this->savedValue;
	}

	/**
	 * Radio buttons template
	 *
	 * @since 2.2.0
	 *
	 * @param array $template
	 */
	public function setTemplate($template) {
		$this->template = $template;
	}

	public function getTemplate() {
		return $this->template;
	}

	/**
	 * Radio buttons position
	 *
	 * @since 2.2.0
	 *
	 * @param string $buttonPosition
	 */
	public function setButtonPosition($buttonPosition) {
		$this->buttonPosition = $buttonPosition;
	}

	public function getButtonPosition() {
		return $this->buttonPosition;
	}

	/**
	 * Fields Data
	 *
	 * @since 2.2.0
	 *
	 * @param array $fields
	 */
	public function setFields($fields) {
		$this->fields = $fields;
	}

	public function getFields() {
		return $this->fields;
	}

	private function prepareBuild() {
		$buttonsData = $this->getButtonsData();

		if(!empty($buttonsData['template'])) {
			$this->setTemplate($buttonsData['template']);
		}
		if(!empty($buttonsData['buttonPosition'])) {
			$this->setButtonPosition($buttonsData['buttonPosition']);
		}
		if(!empty($buttonsData['fields'])) {
			$this->setFields($buttonsData['fields']);
		}
	}

	public function render() {
		$allowedTag = ReadMoreAdminHelper::getAllowedTags();
		ob_start();
		?>
		<div class="ycd-buttons-wrapper">
			<?php echo wp_kses($this->renderFields(), $allowedTag)?>
		</div>
		<?php
		$content = ob_get_contents();
		ob_get_clean();

		return $content;
	}

	private function renderFields() {
		$fields = $this->getFields();
		$groupAttrStr = '';
		$template = $this->getTemplate();
		$buttonPosition = $this->getButtonPosition();
		$buttonsView = '';

		if(empty($fields)) {
			return $buttonsView;
		}

		if(!empty($template['groupWrapperAttr'])) {
			$groupAttrStr = $this->createAttrs($template['groupWrapperAttr']);
		}

		foreach($fields as $field) {
			$labelView = $this->createLabel($field);
			$radioButton = $this->createRadioButton($field);

			$buttonsView .= "<div $groupAttrStr>";

			if($buttonPosition == 'right') {
				$buttonsView .= $labelView.$radioButton;
			}
			else {
				$buttonsView .= $radioButton.$labelView;
			}
			$buttonsView .= '</div>';
		}
		return $buttonsView;
	}

	private function createRadioButton($field) {
		$template = $this->getTemplate();
		$savedValue = $this->getSavedValue();
		$parentAttrsStr = '';
		$inputAttrStr = '';
		$value = '';
		$checked = '';

		if(!empty($template['fieldWrapperAttr'])) {
			$parentAttrsStr = $this->createAttrs($template['fieldWrapperAttr']);
		}

		if(!empty($field['attr'])) {

			if(!empty($field['attr']['value'])) {
				$value = $field['attr']['value'];
			}

 			$inputAttrStr = $this->createAttrs($field['attr']);
		}

		if($savedValue == $value) {
			$checked = 'checked';
		}

		$label = "<div $parentAttrsStr>";
		$label .= "<input id='".esc_attr($value)."' $inputAttrStr $checked >";
		$label .=  '</div>';

		return $label;
	}

	private function createLabel($field) {
		$template = $this->getTemplate();
		$parentAttrsStr = '';
		$label =  '';
		$value =  '';
		$labelName = '';

		if(empty($field['label'])) {
			return $label;
		}

		if(!empty($field['attr']['value'])) {
			$value = $field['attr']['value'];
		}

		$labelData = $field['label'];
		if(!empty($template['labelAttr'])) {
			$parentAttrsStr = $this->createAttrs($template['labelAttr']);
		}

		if (!empty($labelData['name'])) {
			$labelName = $labelData['name'];
		}

		$label = "<div $parentAttrsStr>";
		$label .= "<label for='".esc_attr($value)."'>$labelName</label>";
		$label .=  '</div>';

		return $label;
	}

	/**
	 * Create html attrs
	 *
	 * @since 2.2.0
	 *
	 * @param array $attrs
	 *
	 * @return string $attrStr
	 */
	private function createAttrs($attrs) {
		$attrStr = '';

		if (empty($attrs)) {
			return $attrStr;
		}

		foreach ($attrs as $attrKey => $attrValue) {
			$attrStr .= $attrKey.'="'.esc_attr($attrValue).'" ';
		}

		return $attrStr;
 	}
}