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

jimport('joomla.application.component.view');

class VikrentitemsViewItemdetails extends JViewVikRentItems {
	function display($tpl = null) {
		VikRentItems::prepareViewContent();
		$pelemid = VikRequest::getString('elemid', '', 'request');
		$dbo = JFactory::getDBO();
		$vri_tn = VikRentItems::getTranslator();
		$q = "SELECT * FROM `#__vikrentitems_items` WHERE `id`=".$dbo->quote($pelemid)." AND `avail`='1';";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() == 1) {
			$item = $dbo->loadAssocList();
			$vri_tn->translateContents($item, '#__vikrentitems_items');
			$kit_relations = array();
			$q = "SELECT `id`,`cost` FROM `#__vikrentitems_dispcost` WHERE `iditem`=".$dbo->quote($item[0]['id'])." AND `days`='1' ORDER BY `#__vikrentitems_dispcost`.`cost` ASC LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$tar = $dbo->loadAssocList();
				$item[0]['cost'] = $tar[0]['cost'];
			} else {
				$q = "SELECT `id`,`days`,`cost` FROM `#__vikrentitems_dispcost` WHERE `iditem`=".$dbo->quote($item[0]['id'])." ORDER BY `#__vikrentitems_dispcost`.`cost` ASC LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() == 1) {
					$tar = $dbo->loadAssocList();
					$item[0]['cost'] = ($tar[0]['cost'] / $tar[0]['days']);
				} else {
					$item[0]['cost'] = 0;
				}
			}
			$actnow = time();
			$q = "SELECT `b`.*,`ob`.`idorder`,`o`.`closure` FROM `#__vikrentitems_busy` AS `b` LEFT JOIN `#__vikrentitems_ordersbusy` `ob` ON `ob`.`idbusy`=`b`.`id` LEFT JOIN `#__vikrentitems_orders` `o` ON `o`.`id`=`ob`.`idorder` WHERE `b`.`iditem`='".$item[0]['id']."' AND (`b`.`ritiro`>=".$actnow." OR `b`.`consegna`>=".$actnow.");";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$busy = $dbo->loadAssocList();
			} else {
				$busy = "";
			}
			$discounts = array();
			if (intval(VikRentItems::getItemParam($item[0]['params'], 'discsquantstab')) == 1) {
				$q = "SELECT * FROM `#__vikrentitems_discountsquants` WHERE `iditems` LIKE '%-".$item[0]['id']."-%' ORDER BY `#__vikrentitems_discountsquants`.`quantity` ASC;";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$discounts = $dbo->loadAssocList();
				}
			}
			$timeslots = array();
			if (intval(VikRentItems::getItemParam($item[0]['params'], 'timeslots')) == 1) {
				$timeslots = VikRentItems::loadItemTimeSlots($item[0]['id'], $vri_tn);
			}
			//VRI 1.4
			$item_params = !empty($item[0]['jsparams']) ? json_decode($item[0]['jsparams'], true) : array();
			$document = JFactory::getDocument();
			if (!empty($item_params['custptitle'])) {
				$ctitlewhere = !empty($item_params['custptitlew']) ? $item_params['custptitlew'] : 'before';
				$set_title = $item_params['custptitle'].' - '.$document->getTitle();
				if ($ctitlewhere == 'after') {
					$set_title = $document->getTitle().' - '.$item_params['custptitle'];
				} elseif ($ctitlewhere == 'replace') {
					$set_title = $item_params['custptitle'];
				}
				$document->setTitle($set_title);
			}
			if (!empty($item_params['metakeywords'])) {
				$document->setMetaData('keywords', $item_params['metakeywords']);
			}
			if (!empty($item_params['metadescription'])) {
				$document->setMetaData('description', $item_params['metadescription']);
			}
			//
			//VRI 1.5
			if ($item[0]['isgroup'] > 0) {
				$q="SELECT `g`.`childid`,`g`.`units`,`i`.`name`,`i`.`units` AS `maxunits` FROM `#__vikrentitems_groupsrel` AS `g` LEFT JOIN `#__vikrentitems_items` AS `i` ON `g`.`childid`=`i`.`id` WHERE `g`.`parentid`=".(int)$item[0]['id'].";";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$kit_relations = $dbo->loadAssocList();
				}
			}
			//
			$this->item = $item[0];
			$this->kit_relations = $kit_relations;
			$this->busy = $busy;
			$this->discounts = $discounts;
			$this->timeslots = $timeslots;
			$this->vri_tn = $vri_tn;
			//theme
			$theme = VikRentItems::getTheme();
			if ($theme != 'default') {
				$thdir = VRI_SITE_PATH.DS.'themes'.DS.$theme.DS.'itemdetails';
				if (is_dir($thdir)) {
					$this->_setPath('template', $thdir.DS);
				}
			}
			//
			parent::display($tpl);
		} else {
			$mainframe = JFactory::getApplication();
			$mainframe->redirect(JRoute::rewrite("index.php?option=com_vikrentitems&view=itemslist", false));
		}
	}
}
