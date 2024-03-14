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

class VikRentItemsViewManagecarat extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$cid = VikRequest::getVar('cid', array(0));
		if (!empty($cid[0])) {
			$id = $cid[0];
		}

		$row = array();
		$dbo = JFactory::getDBO();
		$allitems = array();
		if (!empty($cid[0])) {
			$q = "SELECT * FROM `#__vikrentitems_caratteristiche` WHERE `id`=".(int)$id.";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() != 1) {
				VikError::raiseWarning('', 'Not found.');
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php?option=com_vikrentitems&task=carat");
				exit;
			}
			$row = $dbo->loadAssoc();
		}
		// read all cars
		$q = "SELECT `id`, `name`, `idcarat` FROM `#__vikrentitems_items`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows()) {
			$records = $dbo->loadAssocList();
			foreach ($records as $r) {
				$r['idcarat'] = empty($r['idcarat']) ? array() : explode(';', rtrim($r['idcarat'], ';'));
				$allitems[$r['id']] = $r;
			}
		}
		$this->allitems = &$allitems;
		$this->row = &$row;
		
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
			JToolBarHelper::title(JText::translate('VRMAINCARATTITLEEDIT'), 'vikrentitems');
			if (JFactory::getUser()->authorise('core.edit', 'com_vikrentitems')) {
				JToolBarHelper::save( 'updatecarat', JText::translate('VRSAVE'));
				JToolBarHelper::spacer();
			}
			JToolBarHelper::cancel( 'cancelcarat', JText::translate('VRANNULLA'));
			JToolBarHelper::spacer();
		} else {
			//new
			JToolBarHelper::title(JText::translate('VRMAINCARATTITLENEW'), 'vikrentitems');
			if (JFactory::getUser()->authorise('core.create', 'com_vikrentitems')) {
				JToolBarHelper::save( 'createcarat', JText::translate('VRSAVE'));
				JToolBarHelper::spacer();
			}
			JToolBarHelper::cancel( 'cancelcarat', JText::translate('VRANNULLA'));
			JToolBarHelper::spacer();
		}
	}

}
