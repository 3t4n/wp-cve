<?php
/**
 * @package     VikRentItems
 * @subpackage  com_vikrentitems
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

defined('ABSPATH') or die('No script kiddies please!');

jimport('joomla.form.formfield');

class JFormFieldVricategory extends JFormField { 
	protected $type = 'vricategory';
	
	function getInput() {
		$key = ($this->element['key_field'] ? $this->element['key_field'] : 'value');
		$val = ($this->element['value_field'] ? $this->element['value_field'] : $this->name);
		$categories="";
		$dbo = JFactory::getDBO();
		$q="SELECT * FROM `#__vikrentitems_categories` ORDER BY `#__vikrentitems_categories`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$allvric=$dbo->loadAssocList();
			foreach($allvric as $vric) {
				$categories.='<option value="'.$vric['id'].'"'.($this->value == $vric['id'] ? " selected=\"selected\"" : "").'>'.$vric['name'].'</option>';
			}
		}
		$html = '<select class="inputbox" name="' . $this->name . '" >';
		$html .= '<option value=""></option>';
		$html .= $categories;
		$html .='</select>';
		return $html;
    }
}


?>