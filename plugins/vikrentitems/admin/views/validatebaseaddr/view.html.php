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

// import Joomla view library
jimport('joomla.application.component.view');

class VikRentItemsViewValidatebaseaddr extends JViewVikRentItems {
	
	function display($tpl = null) {
		// No toolbar for this modal View 

		$pbaseaddress = VikRequest::getString('baseaddress', '', 'request');
		
		$this->pbaseaddress = &$pbaseaddress;
		
		// Display the template
		parent::display($tpl);
	}

}
