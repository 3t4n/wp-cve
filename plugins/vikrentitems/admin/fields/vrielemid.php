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

class JFormFieldVrielemid extends JFormField { 
	protected $type = 'vrielemid';
	
	function getInput() {
		$key = ($this->element['key_field'] ? $this->element['key_field'] : 'value');
		$val = ($this->element['value_field'] ? $this->element['value_field'] : $this->name);
		$html = 'no items found. please create some from the back-end of VikRentItems';
		$dbo = JFactory::getDBO();
		$q="SELECT * FROM `#__vikrentitems_items` ORDER BY `#__vikrentitems_items`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$allvrie=$dbo->loadAssocList();
			$items="";
			foreach($allvrie as $vrie) {
				$items.='<option value="'.$vrie['id'].'"'.($this->value == $vrie['id'] ? " selected=\"selected\"" : "").'>'.$vrie['name'].'</option>';
			}
			$html = '<select class="inputbox" name="' . $this->name . '" >';
			$html .= '<option value=""></option>';
			$html .= $items;
			$html .='</select>';
		}
		return $html;
    }
}


?>