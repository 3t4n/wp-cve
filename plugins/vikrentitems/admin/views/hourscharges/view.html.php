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

class VikRentItemsViewHourscharges extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$cid = VikRequest::getVar('cid', array(0));
		$aid = $cid[0];

		$dbo = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		if (empty($aid)) {
			$q = "SELECT `id` FROM `#__vikrentitems_items` ORDER BY `#__vikrentitems_items`.`name` ASC LIMIT 1";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$aid = $dbo->loadResult();
			}
		}
		if (empty($aid)) {
			VikError::raiseWarning('', 'No Items.');
			$mainframe->redirect("index.php?option=com_vikrentitems&task=items");
			exit;
		}
		$q = "SELECT `id`,`name`,`img` FROM `#__vikrentitems_items` WHERE `id`=".$dbo->quote($aid).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() != 1) {
			VikError::raiseWarning('', 'No Items.');
			$mainframe->redirect("index.php?option=com_vikrentitems&task=items");
			exit;
		}
		$itemrows = $dbo->loadAssoc();
		$q = "SELECT * FROM `#__vikrentitems_prices`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$prices = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
		$pnewtar = VikRequest::getString('newdispcost', '', 'request');
		$phhoursfrom = VikRequest::getInt('hhoursfrom', '', 'request');
		$phhoursto = VikRequest::getInt('hhoursto', '', 'request');
		//maximum 23 hours
		if (!empty($phhoursfrom) && intval($phhoursfrom) > 23) {
			$phhoursfrom = 23;
		}
		if (!empty($phhoursto) && intval($phhoursto) > 23) {
			$phhoursto = 23;
		}
		//
		if (!empty($pnewtar) && !empty($phhoursfrom) && is_array($prices)) {
			if (empty($phhoursto) || $phhoursfrom == $phhoursto) {
				foreach ($prices as $pr) {
					$tmpvarone = VikRequest::getFloat('hprice'.$pr['id'], '', 'request');
					if (!empty($tmpvarone)) {
						$tmpvartwo = VikRequest::getString('hattr'.$pr['id'], '', 'request');
						$multipattr = is_numeric($tmpvartwo) ? true : false;
						$safeq = "SELECT `id` FROM `#__vikrentitems_hourscharges` WHERE `ehours`=".$dbo->quote($phhoursfrom)." AND `iditem`='".$itemrows['id']."' AND `idprice`='".$pr['id']."';";
						$dbo->setQuery($safeq);
						$dbo->execute();
						if ($dbo->getNumRows() == 0) {
							$q = "INSERT INTO `#__vikrentitems_hourscharges` (`iditem`,`ehours`,`idprice`,`cost`) VALUES('".$itemrows['id']."',".$dbo->quote($phhoursfrom).",'".$pr['id']."','".($tmpvarone * $phhoursfrom)."');";
							$dbo->setQuery($q);
							$dbo->execute();
						} elseif ($dbo->getNumRows() == 1) {
							$upd_id = $dbo->loadResult();
							$q = "UPDATE `#__vikrentitems_hourscharges` SET `cost`='".($tmpvarone * $phhoursfrom)."' WHERE `id`=".(int)$upd_id." AND `ehours`=".$dbo->quote($phhoursfrom)." AND `iditem`='".$itemrows['id']."' AND `idprice`='".$pr['id']."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
				}
			} else {
				$phhoursto = intval($phhoursto) > 365 ? 365 : $phhoursto;
				for ($i = intval($phhoursfrom); $i <= intval($phhoursto); $i++) {
					foreach ($prices as $pr) {
						$tmpvarone = VikRequest::getFloat('hprice'.$pr['id'], '', 'request');
						if (!empty($tmpvarone)) {
							$tmpvartwo = VikRequest::getString('hattr'.$pr['id'], '', 'request');
							$multipattr = is_numeric($tmpvartwo) ? true : false;
							$safeq = "SELECT `id` FROM `#__vikrentitems_hourscharges` WHERE `ehours`=".$dbo->quote($i)." AND `iditem`='".$itemrows['id']."' AND `idprice`='".$pr['id']."';";
							$dbo->setQuery($safeq);
							$dbo->execute();
							if ($dbo->getNumRows() == 0) {
								$q = "INSERT INTO `#__vikrentitems_hourscharges` (`iditem`,`ehours`,`idprice`,`cost`) VALUES('".$itemrows['id']."',".$dbo->quote($i).",'".$pr['id']."','".($tmpvarone * $i)."');";
								$dbo->setQuery($q);
								$dbo->execute();
							} elseif ($dbo->getNumRows() == 1) {
								$upd_id = $dbo->loadResult();
								$q = "UPDATE `#__vikrentitems_hourscharges` SET `cost`='".($tmpvarone * $i)."' WHERE `id`=".(int)$upd_id." AND `ehours`=".$dbo->quote($i)." AND `iditem`='".$itemrows['id']."' AND `idprice`='".$pr['id']."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
							}
						}
					}
				}
			}
		}
		$q = "SELECT * FROM `#__vikrentitems_hourscharges` WHERE `iditem`='".$itemrows['id']."' ORDER BY `#__vikrentitems_hourscharges`.`ehours` ASC, `#__vikrentitems_hourscharges`.`idprice` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$rows = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
		$q = "SELECT `id`,`name` FROM `#__vikrentitems_items` ORDER BY `#__vikrentitems_items`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		$allc = $dbo->loadAssocList();

		$this->itemrows = &$itemrows;
		$this->rows = &$rows;
		$this->prices = &$prices;
		$this->allc = &$allc;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRMAINTARIFFETITLE'), 'vikrentitems');
		JToolBarHelper::save( 'cancel', JText::translate('VRMAINTARIFFEBACK'));
		if (JFactory::getUser()->authorise('core.delete', 'com_vikrentitems')) {
			JToolBarHelper::deleteList(JText::translate('VRIDELCONFIRM'), 'removehourscharges', JText::translate('VRMAINTARIFFEDEL'));
		}
	}

}
