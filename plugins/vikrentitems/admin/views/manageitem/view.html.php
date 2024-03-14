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

class VikRentItemsViewManageitem extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$cid = VikRequest::getVar('cid', array(0));
		if (!empty($cid[0])) {
			$id = $cid[0];
		}

		$row = array();
		$dbo = JFactory::getDBO();
		if (!empty($cid[0])) {
			$q = "SELECT * FROM `#__vikrentitems_items` WHERE `id`=".(int)$id.";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() != 1) {
				VikError::raiseWarning('', 'Not found.');
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php?option=com_vikrentitems&task=items");
				exit;
			}
			$row = $dbo->loadAssoc();
		}
		$q = "SELECT * FROM `#__vikrentitems_places` ORDER BY `name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$places = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
		$q = "SELECT * FROM `#__vikrentitems_categories` ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$cats = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
		$q = "SELECT * FROM `#__vikrentitems_caratteristiche` ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$carats = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
		$q = "SELECT * FROM `#__vikrentitems_optionals` ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$optionals = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
		$q = "SELECT `id`,`name`,`units` FROM `#__vikrentitems_items` WHERE ".(count($row) ? "`id`!=".(int)$id." AND " : '')."`isgroup`=0 ORDER BY `name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$all_items = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
		$grouped_items = array();
		if (count($row)) {
			$q = "SELECT `g`.`childid`,`g`.`units`,`i`.`name`,`i`.`units` AS `maxunits` FROM `#__vikrentitems_groupsrel` AS `g` LEFT JOIN `#__vikrentitems_items` AS `i` ON `g`.`childid`=`i`.`id` WHERE `g`.`parentid`=".(int)$id.";";
			$dbo->setQuery($q);
			$dbo->execute();
			$grouped_items = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
		}
		
		$this->row = &$row;
		$this->cats = &$cats;
		$this->carats = &$carats;
		$this->optionals = &$optionals;
		$this->places = &$places;
		$this->all_items = &$all_items;
		$this->grouped_items = &$grouped_items;
		
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
			JToolBarHelper::title(JText::translate('VRMAINITEMTITLEEDIT'), 'vikrentitems');
			if (JFactory::getUser()->authorise('core.edit', 'com_vikrentitems')) {
				JToolBarHelper::apply( 'updateitemapply', JText::translate('VRSAVE'));
				JToolBarHelper::spacer();
				JToolBarHelper::save( 'updateitem', JText::translate('VRSAVECLOSE'));
				JToolBarHelper::spacer();
			}
			JToolBarHelper::cancel( 'cancel', JText::translate('VRANNULLA'));
			JToolBarHelper::spacer();
		} else {
			//new
			JToolBarHelper::title(JText::translate('VRMAINITEMTITLENEW'), 'vikrentitems');
			if (JFactory::getUser()->authorise('core.create', 'com_vikrentitems')) {
				JToolBarHelper::save( 'createitem', JText::translate('VRSAVE'));
				JToolBarHelper::spacer();
			}
			JToolBarHelper::cancel( 'cancel', JText::translate('VRANNULLA'));
			JToolBarHelper::spacer();
		}
	}

}
