<?php
class YcfFunctions {

	public static function createSelectBox($data, $selectedValue, $attrs) {

		$attrString = '';
		$selected = '';

		if(!empty($attrs) && isset($attrs)) {

			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.$attrName.'="'.$attrValue.'" ';
			}
		}

		$selectBox = '<select '.$attrString.'>';

		foreach ($data as $value => $label) {

			/*When is multiselect*/
			if(is_array($selectedValue)) {
				$isSelected = in_array($value, $selectedValue);
				if($isSelected) {
					$selected = 'selected';
				}
			}
			else if($selectedValue == $value) {
				$selected = 'selected';
			}
			else if(is_array($value) && in_array($selectedValue, $value)) {
				$selected = 'selected';
			}

			$selectBox .= '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';
			$selected = '';
		}

		$selectBox .= '</select>';

		return $selectBox;
	}

	public static function createAdminViewHtml($formElement) {
		ob_start();
		$elementId = $formElement['id'];
		?>
		<div class="ycf-element-info-wrapper">
			<div class="ycf-view-element-wrapper" data-options="false" id="<?php echo $elementId ?>">
				<span><?php echo $formElement['label'] ?></span>
				<div class="ycf-element-conf-wrapper">
					<span class="ycf-conf-element ycf-conf-home"></span>
					<span class="ycf-conf-element ycf-delete-element ycf-hide-element" data-id="<?php echo $elementId ?>"></span>
				</div>
			</div>
			<div class="ycf-element-options-wrapper ycf-hide-element">
				<div class="ycf-sub-option-wrapper">
					<span class="element-option-sub-label">Label</span>
					<input type="text" class="element-label ycf-element-sub-option"  value="<?php echo $formElement['label'];?>" data-key="label" data-id="<?php echo $elementId;?>">
				</div>
				<div class="ycf-sub-option-wrapper">
					<span class="element-option-sub-label">Name</span>
					<input type="text" class="element-name ycf-element-sub-option" value="<?php echo $formElement['name']; ?>" data-key="name" data-id="<?php echo $elementId;?>">
				</div>
			</div>
			<div class="ycf-element-margin-bottom"></div>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}