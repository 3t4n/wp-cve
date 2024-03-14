<?php
if ( isset($_SERVER['SCRIPT_FILENAME']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit(esc_html__('Please don\'t access this file directly.', 'WP2SL'));
}
## Get html element - START
function WP2SL_getHTMLElement($type, $nameID, $values, $setValue, $class='', $jqueryCls='', $extra='') {
	$element = '';
	switch($type) {
		case 'text':
			$element = '<input type="text" name="' . $nameID . '" id="' . $nameID . '" value="' . $setValue . '" class="' . $class . $jqueryCls . '" '.$extra.' />';
			break;
			
		case 'select':
			$element = '<select name="' . $nameID . '" class="' . $jqueryCls . '" id="' . $nameID . '" '.$extra.'>';
			$sObj = unserialize($values);
			foreach($sObj as $k=>$v) {
				if($v->name === $setValue) $selected = 'selected="selected"';
				else  $selected = '';
				$element .= '<option value="' . $v->name . '" ' . $selected . '>' . $v->value . '</option>';
			}
			$element .= '</select>';
			break;
		case 'radio':
			$sObj = unserialize($values);
			foreach($sObj as $k=>$v) {
				if($v->value === $setValue) $selected = 'checked="checked""';
				else  $selected = '';
				$element .= '<input type="radio" name="' . $nameID . '" id="' . $nameID . '_' . $v->value . '" value="' . $v->value . '" class="Hradio ' . $class . $jqueryCls . '" ' . $selected . ' '.$extra.'/>&nbsp;' . $v->name . '&nbsp;&nbsp;';		    
			}
			break;
		case 'checkbox':
			if(1 === $setValue) $selected = 'checked="checked"';
			else  $selected = '';
			$sObj = unserialize($values);
			$element = '';
			$element .= '<input type="checkbox" name="'. $nameID .'" '.$selected.' class="'.$jqueryCls.'" id="'. $nameID .'" value="1" '.$extra.' />';
			break;
		case 'textarea':
			$element = '<textarea cols="30" class="' . $jqueryCls . '" rows="5" name="' . $nameID . '" '.$extra.' id="' . $nameID . '">' . $setValue . '</textarea>';
			break;
		
		case 'file':
			$element = '<input type="file" name="' . $nameID . '" id="' . $nameID . '" class="' . $jqueryCls . '" '.$extra.'  />';
			break;
				
		default:
			$element = '';
			break;
	}
	return $element;
}
## Get html element - END

## Number format - START
function NumFrmt($val, $separator = ',') {
	$val = str_replace(',', '', $val);
	if($val === '') return $val;
	$val = number_format($val, 0, '', $separator);
	return $val;
}
## Number format - END

## Number format for target - START
function NumFrmtForTraget($val, $separator = ',') {
	$val = str_replace(',', '', $val);
	if($val === '') return $val;
	$val = number_format($val, 0, '', $separator);
	return $val;
}
## Number format for target - END