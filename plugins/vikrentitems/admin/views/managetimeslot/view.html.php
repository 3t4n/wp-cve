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

class VikRentItemsViewManagetimeslot extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$cid = VikRequest::getVar('cid', array(0));
		if (!empty($cid[0])) {
			$id = $cid[0];
		}

		$row = array();
		$split = array();
		$wsel = '';
		$dbo = JFactory::getDBO();
		if (!empty($cid[0])) {
			$q = "SELECT * FROM `#__vikrentitems_timeslots` WHERE `id`=".(int)$id.";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() != 1) {
				VikError::raiseWarning('', 'Not found.');
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php?option=com_vikrentitems&task=timeslots");
				exit;
			}
			$row = $dbo->loadAssoc();
			$split = explode(",", $row['iditems']);
		}

		$q = "SELECT `id`,`name` FROM `#__vikrentitems_items` ORDER BY `#__vikrentitems_items`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$wsel .= "<select name=\"iditems[]\" id=\"iditems\" multiple=\"multiple\" size=\"5\">\n";
			$data = $dbo->loadAssocList();
			foreach ($data as $d) {
				$wsel .= "<option value=\"".$d['id']."\"".(count($row) && in_array("-".$d['id']."-", $split) ? " selected=\"selected\"" : "").">".$d['name']."</option>\n";
			}
			$wsel .= "</select>\n";
		}
		
		$this->row = &$row;
		$this->wsel = &$wsel;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		$cid = VikRequest::getVar('cid', array(0));
		
		if (!empty($cid[0])) {
			//edit
			JToolBarHelper::title(JText::translate('VRMAINTIMESLOTTITLEEDIT'), 'vikrentitems');
			if (JFactory::getUser()->authorise('core.edit', 'com_vikrentitems')) {
				JToolBarHelper::save( 'updatetimeslot', JText::translate('VRSAVE'));
				JToolBarHelper::spacer();
			}
			JToolBarHelper::cancel( 'canceltimeslot', JText::translate('VRANNULLA'));
			JToolBarHelper::spacer();
		} else {
			//new
			JToolBarHelper::title(JText::translate('VRMAINTIMESLOTTITLENEW'), 'vikrentitems');
			if (JFactory::getUser()->authorise('core.create', 'com_vikrentitems')) {
				JToolBarHelper::save( 'createtimeslot', JText::translate('VRSAVE'));
				JToolBarHelper::spacer();
			}
			JToolBarHelper::cancel( 'canceltimeslot', JText::translate('VRANNULLA'));
			JToolBarHelper::spacer();
		}
	}

}
