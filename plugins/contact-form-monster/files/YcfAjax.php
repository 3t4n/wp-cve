<?php
class YcfAjax {

	public function __construct() {

		$this->init();
	}

	public function init() {

		add_action('wp_ajax_delete_contact_form', array($this, 'ycfDeleteContactForm'));
		add_action('wp_ajax_shape-form-element', array($this, 'YcfShapeElementsList'));
		add_action('wp_ajax_change-element-data', array($this, 'ycfChangeElementData'));
		add_action('wp_ajax_remove_element_from_list', array($this, 'YcfElementRemoveFromList'));
	}

	public function ycfDeleteContactForm() {

        $postData = sanitize_text_field($_POST);
        if(!isset($postData)) {
            return false;
        }

        $formId = (int)$postData['formId'];

        if($formId == 0) {
            return false;
        }

        $formDataObj = new YcfContactFormData();
		$formDataObj->deleteFormById($formId);
		return 0;
    }

	public function ycfChangeElementData() {

	    $elementData = sanitize_text_field($_POST['editElementData']);
        $formId = $elementData['formCurrentId'];
        $changedElementId = $elementData['changedElementId'];
        $changedValue = $elementData['changedElementValue'];
        $changedKey = $elementData['changedElementKey'];

		if($formId == 0) {
			$formListData = get_option('YcfFormDraft');
		}
		else {
			$formListData = YcfContactFormData::getFormListById($formId);
		}

		if(is_array($formListData) && !empty($formListData)) {
		    foreach($formListData as $key => $currentListFieldData) {
		        if($currentListFieldData['id'] == $changedElementId) {
					$formListData[$key][$changedKey] = $changedValue;
                }
            }
        }

		update_option('YcfFormDraft', $formListData);
    }

	public function YcfElementRemoveFromList() {

		$elementData = sanitize_text_field($_POST['removeElementData']);
		$elementId = sanitize_text_field($elementData['id']);
		$draftElements = get_option('YcfFormDraft');

		foreach ($draftElements as $key => $draftElement) {
			if($elementId == $draftElement['id']) {
				unset($draftElements[$key]);
			}
		}

		update_option('YcfFormDraft', $draftElements);
		echo '1';
		die();
	}

	public function addElementsToList($formElement, $contactFormId) {

		if($contactFormId == 0) {
			$formListData = get_option('YcfFormDraft');
		}
		else {
			$formListData = YcfContactFormData::getFormListById($contactFormId);
		}
		
		$formSize = sizeof($formListData);

		array_splice($formListData, $formSize-1, 0, array($formElement));
	
		update_option('YcfFormDraft', $formListData);
	}

	private function changeElementOrdering($formId, $replaceKey) {
		if($formId == 0) {
			$formListData = get_option('YcfFormDraft');
		}
		else {
			$formListData = YcfContactFormData::getFormListById($formId);
		}
		$keys = array_keys($formListData);
		$start = $replaceKey['start'];
		$end = $replaceKey['end'];

//		for($index = 0;$index <= count($formListData), $index++ ) {
//			//$cuurent =
//		}

		var_dump($array);
		die();
	}

	public function YcfShapeElementsList() {

		$dataArray = get_option('YcfFormElements');
		$formElement = sanitize_text_field($_POST['formElements']);
		$contactFormId = (int)$_POST['contactFormId'];
		
		if($_POST['modification'] == 'add-element') {
			$this->addElementsToList($formElement, $contactFormId);
		}
		else if($_POST['modification'] == 'reposition') {
			$this->changeElementOrdering($contactFormId, $_POST['position']);
		}
		$currentElement = array();
		$formElementId = $formElement['id'];

		if(!get_option('YcfFormElements')) {
			$dataArray = array();
		}
		
		$currentElement['type'] = $formElement['type'];
		$currentElement['label'] = $formElement['label'];
		$currentElement['name'] = $formElement['name'];
		$currentElement['id'] = $formElementId;

		array_push($dataArray, $currentElement);
		$element = YcfFunctions::createAdminViewHtml($formElement);
		echo $element;
		die();
		update_option('YcfFormElements', $dataArray);

		echo json_encode($dataArray);
		die();
	}

	public function addHiddenAccordionDiv($formElement) {
		$elementId = $formElement['id'];
		ob_start();
		?>
			<div class="ycf-element-options-wrapper ycf-hide-element">
				<div class="ycf-sub-option-wrapper">
					<span class="element-option-sub-label">Label</span>
					<input type="text" class="element-label"  value="<?php echo $formElement['label'];?>" data-id="<?php echo $elementId;?>">
				</div>
				<div class="ycf-sub-option-wrapper">
					<span class="element-option-sub-label">Name</span>
					<input type="text" class="element-name" value="<?php echo $formElement['name']; ?>">
				</div>
			</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}

$ajaxObj = new YcfAjax();