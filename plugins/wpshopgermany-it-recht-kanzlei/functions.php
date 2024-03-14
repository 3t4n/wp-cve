<?php
 
	/**
	 * Prüfung ob befülltes Array vorhanden ist
	 * @param 	array	$array
	 * @return 	boolean
	 */
	function wpsgitrecht_isSizedArray(&$array, $size = 1)
	{
		
		if(isset($array) && is_array($array) && sizeof($array) >= $size) return true;
		else return false;
		
	} // function wpsgitrecht_isSizedArray($array, $size = 1)

	/**
	 * Prüft ob eine Varible ein String ist und die Länge > 0 ist
	 */
	function wpsgitrecht_isSizedString(&$strValue, $value = false)
	{
	
		$isset = true;
		if (!isset($strValue) || !is_string($strValue) || strlen($strValue) <= 0) $isset = false;
	
		if ($isset === true && $value !== false)
		{
				
			if ($value === $strValue) return true;
			else return false;
				
		}
	
		return $isset;
	
	} // function wpsgitrecht_isSizedString($strValue)
	
	function wpsgitrecht_drawForm_Input($field_name, $field_label, $field_value, $field_conf)
	{
	
		$field_id = $field_name;
		$field_id = preg_replace('/\[|\]/', '', $field_id);
	
		$class_div = '';
		$class_p = '';
		$class = '';
		$att = '';
	
		$strReturn = '
			<div class="wpsgitrecht_form_field">
			<div class="wpsgitrecht_form_left">
			<label for="'.$field_id.'">'.$field_label.':</label>
			</div>
			<div class="'.$class_div.'wpsgitrecht_form_right">
			';
	
		$strType = 'text';
	
		if (isset($field_conf['readonly']) && $field_conf['readonly'] === true) $att .= ' readonly="readonly" ';
		if (!isset($field_conf['nohspc']) || $field_conf['nohspc'] !== true) $field_value = htmlspecialchars($field_value);
		
		$strReturn .= '<input id="'.$field_id.'" type="'.$strType.'" class="text '.$class.'" '.$att.' name="'.$field_name.'" value="'.htmlspecialchars($field_value).'" />';
	
		$strReturn .= '</div></div>';
	
		return $strReturn;
	
	} // function wpsgitrecht_drawForm_Input($field_name, $field_label, $field_value, $conf = array())
