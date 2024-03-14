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

// import Joomla controller library
jimport('joomla.application.component.controller');

class VikRentItemsController extends JControllerVikRentItems {

	/**
	 * Default controller's method when no task is defined,
	 * or no method exists for that task. If a View is requested.
	 * attempts to set it, otherwise sets the default View.
	 */
	function display($cachable = false, $urlparams = array()) {

		$view = VikRequest::getVar('view', '');
		$header_val = '';

		if (!empty($view)) {
			VikRequest::setVar('view', $view);
		} else {
			$header_val = '18';
			VikRequest::setVar('view', 'dashboard');
		}

		VikRentItemsHelper::printHeader($header_val);
		
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function places() {
		VikRentItemsHelper::printHeader("3");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'places'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newplace() {
		VikRentItemsHelper::printHeader("3");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'manageplace'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editplace() {
		VikRentItemsHelper::printHeader("3");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'manageplace'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createplace() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$dbo = JFactory::getDbo();
		$pname = VikRequest::getString('placename', '', 'request');
		$paddress = VikRequest::getString('address', '', 'request');
		$plat = VikRequest::getString('lat', '', 'request');
		$plng = VikRequest::getString('lng', '', 'request');
		$ppraliq = VikRequest::getString('praliq', '', 'request');
		$pdescr = VikRequest::getString('descr', '', 'request', VIKREQUEST_ALLOWHTML);
		$popentimefh = VikRequest::getString('opentimefh', '', 'request');
		$popentimefm = VikRequest::getInt('opentimefm', '', 'request');
		$popentimeth = VikRequest::getString('opentimeth', '', 'request');
		$popentimetm = VikRequest::getInt('opentimetm', '', 'request');
		$pclosingdays = VikRequest::getString('closingdays', '', 'request');
		$psuggopentimeh = VikRequest::getInt('suggopentimeh', '', 'request');
		$pwopeningfh = VikRequest::getVar('wopeningfh', array());
		$pwopeningfm = VikRequest::getVar('wopeningfm', array());
		$pwopeningth = VikRequest::getVar('wopeningth', array());
		$pwopeningtm = VikRequest::getVar('wopeningtm', array());
		$opentime = "";
		$suggopentimeh = !empty($psuggopentimeh) ? ($psuggopentimeh * 3600) : '';
		if (strlen($popentimefh) > 0 && strlen($popentimeth) > 0) {
			$openingh = $popentimefh * 3600;
			$openingm = $popentimefm * 60;
			$openingts = $openingh + $openingm;
			$closingh = $popentimeth * 3600;
			$closingm = $popentimetm * 60;
			$closingts = $closingh + $closingm;
			if ($closingts > $openingts || $openingts > $closingts) {
				$opentime = $openingts."-".$closingts;
			}
		}
		if (!empty($pname)) {
			$q = "SELECT `ordering` FROM `#__vikrentitems_places` ORDER BY `#__vikrentitems_places`.`ordering` DESC LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$getlast = $dbo->loadResult();
				$newsortnum = $getlast + 1;
			} else {
				$newsortnum = 1;
			}
			
			// VRI 1.7 - override opening time
			$wopening = array();
			if (count($pwopeningfh)) {
				foreach ($pwopeningfh as $d_ind => $fh) {
					if (!strlen($fh) || isset($wopeing[$d_ind]) || $d_ind > 6 || !isset($pwopeningth[$d_ind]) || !strlen($pwopeningth[$d_ind])) {
						continue;
					}
					$wopening[$d_ind] = array(
						'fh' => (int)$fh,
						'fm' => (int)$pwopeningfm[$d_ind],
						'th' => (int)$pwopeningth[$d_ind],
						'tm' => (int)$pwopeningtm[$d_ind]
					);
				}
			}
			//

			$q = "INSERT INTO `#__vikrentitems_places` (`name`,`lat`,`lng`,`descr`,`opentime`,`closingdays`,`idiva`,`defaulttime`,`ordering`,`address`,`wopening`) VALUES(".$dbo->quote($pname).", ".$dbo->quote($plat).", ".$dbo->quote($plng).", ".$dbo->quote($pdescr).", '".$opentime."', ".$dbo->quote($pclosingdays).", ".(!empty($ppraliq) ? intval($ppraliq) : "NULL").", ".(!empty($suggopentimeh) ? "'".$suggopentimeh."'" : "NULL").", ".$newsortnum.", ".$dbo->quote($paddress).", ".$dbo->quote(json_encode($wopening)).");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=places");
	}

	function updateplace() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$dbo = JFactory::getDbo();
		$pname = VikRequest::getString('placename', '', 'request');
		$paddress = VikRequest::getString('address', '', 'request');
		$plat = VikRequest::getString('lat', '', 'request');
		$plng = VikRequest::getString('lng', '', 'request');
		$ppraliq = VikRequest::getString('praliq', '', 'request');
		$pdescr = VikRequest::getString('descr', '', 'request', VIKREQUEST_ALLOWHTML);
		$pwhereup = VikRequest::getString('whereup', '', 'request');
		$popentimefh = VikRequest::getString('opentimefh', '', 'request');
		$popentimefm = VikRequest::getInt('opentimefm', '', 'request');
		$popentimeth = VikRequest::getString('opentimeth', '', 'request');
		$popentimetm = VikRequest::getInt('opentimetm', '', 'request');
		$pclosingdays = VikRequest::getString('closingdays', '', 'request');
		$psuggopentimeh = VikRequest::getInt('suggopentimeh', '', 'request');
		$pwopeningfh = VikRequest::getVar('wopeningfh', array());
		$pwopeningfm = VikRequest::getVar('wopeningfm', array());
		$pwopeningth = VikRequest::getVar('wopeningth', array());
		$pwopeningtm = VikRequest::getVar('wopeningtm', array());
		$opentime = "";
		$suggopentimeh = !empty($psuggopentimeh) ? ($psuggopentimeh * 3600) : '';
		if (strlen($popentimefh) > 0 && strlen($popentimeth) > 0) {
			$openingh = $popentimefh * 3600;
			$openingm = $popentimefm * 60;
			$openingts = $openingh + $openingm;
			$closingh = $popentimeth * 3600;
			$closingm = $popentimetm * 60;
			$closingts = $closingh + $closingm;
			if ($closingts > $openingts || $openingts > $closingts) {
				$opentime = $openingts."-".$closingts;
			}
		}
		if (!empty($pname)) {
			
			// VRI 1.7 - override opening time
			$wopening = array();
			if (count($pwopeningfh)) {
				foreach ($pwopeningfh as $d_ind => $fh) {
					if (!strlen($fh) || isset($wopeing[$d_ind]) || $d_ind > 6 || !isset($pwopeningth[$d_ind]) || !strlen($pwopeningth[$d_ind])) {
						continue;
					}
					$wopening[$d_ind] = array(
						'fh' => (int)$fh,
						'fm' => (int)$pwopeningfm[$d_ind],
						'th' => (int)$pwopeningth[$d_ind],
						'tm' => (int)$pwopeningtm[$d_ind]
					);
				}
			}
			//

			$q = "UPDATE `#__vikrentitems_places` SET `name`=".$dbo->quote($pname).",`lat`=".$dbo->quote($plat).",`lng`=".$dbo->quote($plng).",`descr`=".$dbo->quote($pdescr).",`opentime`='".$opentime."',`closingdays`=".$dbo->quote($pclosingdays).",`idiva`=".(!empty($ppraliq) ? intval($ppraliq) : "NULL").",`defaulttime`=".(!empty($suggopentimeh) ? "'".$suggopentimeh."'" : "NULL").",`address`=".$dbo->quote($paddress).",`wopening`=".$dbo->quote(json_encode($wopening))." WHERE `id`=".$dbo->quote($pwhereup).";";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=places");
	}

	function removeplace() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "DELETE FROM `#__vikrentitems_places` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=places");
	}

	function cancelplace() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=places");
	}

	function iva() {
		VikRentItemsHelper::printHeader("2");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'iva'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newiva() {
		VikRentItemsHelper::printHeader("2");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'manageiva'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editiva() {
		VikRentItemsHelper::printHeader("2");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'manageiva'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createiva() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$paliqname = VikRequest::getString('aliqname', '', 'request');
		$paliqperc = VikRequest::getString('aliqperc', '', 'request');
		if (!empty($paliqperc)) {
			$dbo = JFactory::getDbo();
			$q = "INSERT INTO `#__vikrentitems_iva` (`name`,`aliq`) VALUES(".$dbo->quote($paliqname).", ".floatval($paliqperc).");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=iva");
	}

	function updateiva() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$paliqname = VikRequest::getString('aliqname', '', 'request');
		$paliqperc = VikRequest::getString('aliqperc', '', 'request');
		$pwhereup = VikRequest::getString('whereup', '', 'request');
		if (!empty($paliqperc)) {
			$dbo = JFactory::getDbo();
			$q = "UPDATE `#__vikrentitems_iva` SET `name`=".$dbo->quote($paliqname).",`aliq`=".floatval($paliqperc)." WHERE `id`=".intval($pwhereup).";";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=iva");
	}

	function removeiva() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "DELETE FROM `#__vikrentitems_iva` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=iva");
	}

	function canceliva() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=iva");
	}

	function prices() {
		VikRentItemsHelper::printHeader("1");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'prices'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newprice() {
		VikRentItemsHelper::printHeader("1");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'manageprice'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editprice() {
		VikRentItemsHelper::printHeader("1");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'manageprice'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createprice() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$pprice = VikRequest::getString('price', '', 'request');
		$pattr = VikRequest::getString('attr', '', 'request');
		$ppraliq = VikRequest::getString('praliq', '', 'request');
		if (!empty($pprice)) {
			$dbo = JFactory::getDbo();
			$q = "INSERT INTO `#__vikrentitems_prices` (`name`,`attr`,`idiva`) VALUES(".$dbo->quote($pprice).", ".$dbo->quote($pattr).", ".(!empty($ppraliq) ? intval($ppraliq) : 'NULL').");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=prices");
	}

	function updateprice() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$pprice = VikRequest::getString('price', '', 'request');
		$pattr = VikRequest::getString('attr', '', 'request');
		$ppraliq = VikRequest::getString('praliq', '', 'request');
		$pwhereup = VikRequest::getString('whereup', '', 'request');
		if (!empty($pprice)) {
			$dbo = JFactory::getDbo();
			$q = "UPDATE `#__vikrentitems_prices` SET `name`=".$dbo->quote($pprice).",`attr`=".$dbo->quote($pattr).",`idiva`=".(!empty($ppraliq) ? intval($ppraliq) : 'NULL')." WHERE `id`=".$dbo->quote($pwhereup).";";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=prices");
	}

	function removeprice() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "DELETE FROM `#__vikrentitems_prices` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=prices");
	}

	function cancelprice() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=prices");
	}

	function categories() {
		VikRentItemsHelper::printHeader("4");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'categories'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newcat() {
		VikRentItemsHelper::printHeader("4");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managecat'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editcat() {
		VikRentItemsHelper::printHeader("4");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managecat'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createcat() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$pcatname = VikRequest::getString('catname', '', 'request');
		$pdescr = VikRequest::getString('descr', '', 'request', VIKREQUEST_ALLOWHTML);
		$presizeto = VikRequest::getString('resizeto', '', 'request');
		$pautoresize = VikRequest::getString('autoresize', '', 'request');
		if (!empty($pcatname)) {
			/**
			 * @since 1.7
			 *
			 * Now categories possess an image. 
			 *
			 */
			if (intval($_FILES['catimg']['error']) == 0 && VikRentItems::caniWrite(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources'. DIRECTORY_SEPARATOR) && trim($_FILES['catimg']['name'])!="") {
				jimport('joomla.filesystem.file');
				if (@is_uploaded_file($_FILES['catimg']['tmp_name'])) {
					$safename = JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['catimg']['name'])));
					if (file_exists(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $safename)) {
						$j = 1;
						while (file_exists(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $j . $safename)) {
							$j++;
						}
						$pwhere = VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $j . $safename;
					} else {
						$j = "";
						$pwhere = VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $safename;
					}
					VikRentItems::uploadFile($_FILES['catimg']['tmp_name'], $pwhere);
					if (!getimagesize($pwhere)){
						@unlink($pwhere);
						$picon = "";
					} else {
						@chmod($pwhere, 0644);
						$picon = $j . $safename;
						if ($pautoresize=="1" && !empty($presizeto)) {
							$eforj = new VikResizer();
							$origmod = $eforj->proportionalImage($pwhere, VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'r_'.$j.$safename, $presizeto, $presizeto);
							if ($origmod) {
								@unlink($pwhere);
								$picon = 'r_' . $j . $safename;
							}
						}
					}
				} else {
					$picon = "";
				}
			} else {
				$picon = "";
			}

			$dbo = JFactory::getDbo();
			$q = "SELECT `ordering` FROM `#__vikrentitems_categories` ORDER BY `#__vikrentitems_categories`.`ordering` DESC LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$getlast = $dbo->loadResult();
				$newsortnum = $getlast + 1;
			} else {
				$newsortnum = 1;
			}
			$q = "INSERT INTO `#__vikrentitems_categories` (`name`,`descr`,`ordering`,`img`) VALUES(".$dbo->quote($pcatname).", ".$dbo->quote($pdescr).", ".(int)$newsortnum.", ".$dbo->quote($picon).");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=categories");
	}

	function updatecat() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$pcatname = VikRequest::getString('catname', '', 'request');
		$pdescr = VikRequest::getString('descr', '', 'request', VIKREQUEST_ALLOWHTML);
		$presizeto = VikRequest::getString('resizeto', '', 'request');
		$pautoresize = VikRequest::getString('autoresize', '', 'request');
		$pwhereup = VikRequest::getString('whereup', '', 'request');
		if (!empty($pcatname)) {
			if (intval($_FILES['catimg']['error']) == 0 && VikRentItems::caniWrite(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources'. DIRECTORY_SEPARATOR) && trim($_FILES['catimg']['name'])!="") {
				jimport('joomla.filesystem.file');
				if (@is_uploaded_file($_FILES['catimg']['tmp_name'])) {
					$safename = JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['catimg']['name'])));
					if (file_exists(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $safename)) {
						$j = 1;
						while (file_exists(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $j . $safename)) {
							$j++;
						}
						$pwhere = VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $j . $safename;
					} else {
						$j = "";
						$pwhere = VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $safename;
					}
					VikRentItems::uploadFile($_FILES['catimg']['tmp_name'], $pwhere);
					if (!getimagesize($pwhere)) {
						@unlink($pwhere);
						$picon = "";
					} else {
						@chmod($pwhere, 0644);
						$picon = $j . $safename;
						if ($pautoresize=="1" && !empty($presizeto)) {
							$eforj = new VikResizer();
							$origmod = $eforj->proportionalImage($pwhere, VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'r_'.$j.$safename, $presizeto, $presizeto);
							if ($origmod) {
								@unlink($pwhere);
								$picon = 'r_' . $j . $safename;
							}
						}
					}
				} else {
					$picon = "";
				}
			} else {
				$picon = "";
			}

			$dbo = JFactory::getDbo();
			$q = "UPDATE `#__vikrentitems_categories` SET `name`=".$dbo->quote($pcatname).", `descr`=".$dbo->quote($pdescr)."".(!empty($picon) ? ", `img`=" . $dbo->quote($picon) : "")." WHERE `id`=".$dbo->quote($pwhereup).";";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=categories");
	}

	function removecat() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "DELETE FROM `#__vikrentitems_categories` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=categories");
	}

	function cancelcat() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=categories");
	}

	function sortcategory() {
		$cid = VikRequest::getVar('cid', array(0));
		$sortid = (int)$cid[0];
		$pmode = VikRequest::getString('mode', '', 'request');
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		if (!empty($pmode)) {
			$q = "SELECT `id`,`ordering` FROM `#__vikrentitems_categories` ORDER BY `#__vikrentitems_categories`.`ordering` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			$totr=$dbo->getNumRows();
			if ($totr > 1) {
				$data = $dbo->loadAssocList();
				if ($pmode == "up") {
					foreach ($data as $v) {
						if ($v['id'] == $sortid) {
							$y = $v['ordering'];
						}
					}
					if ($y && $y > 1) {
						$vik = $y - 1;
						$found = false;
						foreach ($data as $v) {
							if (intval($v['ordering']) == intval($vik)) {
								$found = true;
								$q = "UPDATE `#__vikrentitems_categories` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								$q = "UPDATE `#__vikrentitems_categories` SET `ordering`='".$vik."' WHERE `id`='".$sortid."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								break;
							}
						}
						if (!$found) {
							$q = "UPDATE `#__vikrentitems_categories` SET `ordering`='".$vik."' WHERE `id`='".$sortid."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
				} elseif ($pmode == "down") {
					foreach ($data as $v) {
						if ($v['id'] == $sortid[0]) {
							$y = $v['ordering'];
						}
					}
					if ($y) {
						$vik = $y + 1;
						$found = false;
						foreach ($data as $v) {
							if (intval($v['ordering']) == intval($vik)) {
								$found = true;
								$q = "UPDATE `#__vikrentitems_categories` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								$q = "UPDATE `#__vikrentitems_categories` SET `ordering`='".$vik."' WHERE `id`='".$sortid."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								break;
							}
						}
						if (!$found) {
							$q = "UPDATE `#__vikrentitems_categories` SET `ordering`='".$vik."' WHERE `id`='".$sortid."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
				}
			}
			$mainframe->redirect("index.php?option=com_vikrentitems&task=categories");
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems");
		}
	}

	function carat() {
		VikRentItemsHelper::printHeader("5");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'carat'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newcarat() {
		VikRentItemsHelper::printHeader("5");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managecarat'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editcarat() {
		VikRentItemsHelper::printHeader("5");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managecarat'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createcarat() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$pcaratname = VikRequest::getString('caratname', '', 'request');
		$pcaratmix = VikRequest::getString('caratmix', '', 'request');
		$pcarattextimg = VikRequest::getString('carattextimg', '', 'request', VIKREQUEST_ALLOWHTML);
		$pautoresize = VikRequest::getString('autoresize', '', 'request');
		$presizeto = VikRequest::getString('resizeto', '', 'request');
		$piditems = VikRequest::getVar('iditems', array());
		if (!empty($pcaratname)) {
			if (intval($_FILES['caraticon']['error']) == 0 && VikRentItems::caniWrite(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR) && trim($_FILES['caraticon']['name'])!="") {
				jimport('joomla.filesystem.file');
				if (@is_uploaded_file($_FILES['caraticon']['tmp_name'])) {
					$safename = JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['caraticon']['name'])));
					if (file_exists(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$safename)) {
						$j = 1;
						while (file_exists(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$j.$safename)) {
							$j++;
						}
						$pwhere = VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$j.$safename;
					} else {
						$j = "";
						$pwhere = VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$safename;
					}
					VikRentItems::uploadFile($_FILES['caraticon']['tmp_name'], $pwhere);
					if (!getimagesize($pwhere)) {
						@unlink($pwhere);
						$picon = "";
					} else {
						@chmod($pwhere, 0644);
						$picon = $j.$safename;
						if ($pautoresize == "1" && !empty($presizeto)) {
							$eforj = new VikResizer();
							$origmod = $eforj->proportionalImage($pwhere, VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'r_'.$j.$safename, $presizeto, $presizeto);
							if ($origmod) {
								@unlink($pwhere);
								$picon = 'r_'.$j.$safename;
							}
						}
					}
				} else {
					$picon = "";
				}
			} else {
				$picon = "";
			}
			$dbo = JFactory::getDbo();
			$q = "SELECT `ordering` FROM `#__vikrentitems_caratteristiche` ORDER BY `#__vikrentitems_caratteristiche`.`ordering` DESC LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$getlast = $dbo->loadResult();
				$newsortnum = $getlast + 1;
			} else {
				$newsortnum = 1;
			}
			$q = "INSERT INTO `#__vikrentitems_caratteristiche` (`name`,`icon`,`align`,`textimg`,`ordering`) VALUES(".$dbo->quote($pcaratname).", ".$dbo->quote($picon).", ".$dbo->quote($pcaratmix).", ".$dbo->quote($pcarattextimg).", '".$newsortnum."');";
			$dbo->setQuery($q);
			$dbo->execute();

			/**
			 *
			 * @since 1.7
			 *
			 * Save new caracteristics and assign them to items directly when creating a new one.
			 *
			 */
			$new_carat_id = $dbo->insertid();
			if (!empty($new_carat_id)) {
				// assign/unset carat-items relations
				$items_with_carat = array();
				if (count($piditems)) {
					// assign this new carat to the requested items
					foreach ($piditems as $iditem) {
						if (empty($iditem)) {
							continue;
						}
						$q = "SELECT `id`, `idcarat` FROM `#__vikrentitems_items` WHERE `id`=" . (int)$iditem . ";";
						$dbo->setQuery($q);
						$dbo->execute();
						if (!$dbo->getNumRows()) {
							continue;
						}
						$item_data = $dbo->loadAssoc();
						array_push($items_with_carat, $item_data['id']);
						$current_carats = empty($item_data['idcarat']) ? array() : explode(';', rtrim($item_data['idcarat'], ';'));
						if (in_array((string)$new_carat_id, $current_carats)) {
							continue;
						}
						if (count($current_carats) === 1 && (string)$current_carats[0] == '0') {
							// make sure we do not concatenate a real ID to 0
							$current_carats = array();
						}
						array_push($current_carats, $new_carat_id);
						$new_opts = implode(';', $current_carats) . ';';
						$q = "UPDATE `#__vikrentitems_items` SET `idcarat`=" . $dbo->quote($new_opts) . " WHERE `id`={$item_data['id']};";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
				if (!count($items_with_carat)) {
					// get all items to unset this carat (if previously set)
					array_push($items_with_carat, '0');
				}
				// unset the carat from the other items that may have it
				$q = "SELECT `id`, `idcarat` FROM `#__vikrentitems_items` WHERE `id` NOT IN (" . implode(', ', $items_with_carat) . ");";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows()) {
					$unset_item_carat = $dbo->loadAssocList();
					foreach ($unset_item_carat as $item_data) {
						$current_carats = empty($item_data['idcarat']) ? array() : explode(';', rtrim($item_data['idcarat'], ';'));
						if (!in_array((string)$new_carat_id, $current_carats)) {
							// this item is not using this carat
							continue;
						}
						$caratkey = array_search((string)$new_carat_id, $current_carats);
						if ($caratkey === false) {
							// key not found
							continue;
						}
						// unset this carat ID from the string
						unset($current_carats[$caratkey]);
						if (!count($current_carats)) {
							// an item with no carats assigned will be listed as "0;"
							$current_carats = array(0);
						}
						$new_opts = implode(';', $current_carats) . ';';
						$q = "UPDATE `#__vikrentitems_items` SET `idcarat`=" . $dbo->quote($new_opts) . " WHERE `id`={$item_data['id']};";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
				//
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=carat");
	}

	function updatecarat() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$pcaratname = VikRequest::getString('caratname', '', 'request');
		$pcaratmix = VikRequest::getString('caratmix', '', 'request');
		$pcarattextimg = VikRequest::getString('carattextimg', '', 'request', VIKREQUEST_ALLOWHTML);
		$pwhereup = VikRequest::getString('whereup', '', 'request');
		$pautoresize = VikRequest::getString('autoresize', '', 'request');
		$presizeto = VikRequest::getString('resizeto', '', 'request');
		$piditems = VikRequest::getVar('iditems', array());
		if (!empty($pcaratname)) {
			if (intval($_FILES['caraticon']['error']) == 0 && VikRentItems::caniWrite(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR) && trim($_FILES['caraticon']['name'])!="") {
				jimport('joomla.filesystem.file');
				if (@is_uploaded_file($_FILES['caraticon']['tmp_name'])) {
					$safename = JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['caraticon']['name'])));
					if (file_exists(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $safename)) {
						$j = 1;
						while (file_exists(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $j . $safename)) {
							$j++;
						}
						$pwhere = VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $j . $safename;
					} else {
						$j = "";
						$pwhere = VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $safename;
					}
					VikRentItems::uploadFile($_FILES['caraticon']['tmp_name'], $pwhere);
					if (!getimagesize($pwhere)) {
						@unlink($pwhere);
						$picon = "";
					} else {
						@chmod($pwhere, 0644);
						$picon = $j.$safename;
						if ($pautoresize == "1" && !empty($presizeto)) {
							$eforj = new VikResizer();
							$origmod = $eforj->proportionalImage($pwhere, VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'r_'.$j.$safename, $presizeto, $presizeto);
							if ($origmod) {
								@unlink($pwhere);
								$picon = 'r_'.$j.$safename;
							}
						}
					}
				} else {
					$picon = "";
				}
			} else {
				$picon = "";
			}
			$dbo = JFactory::getDbo();
			$q = "UPDATE `#__vikrentitems_caratteristiche` SET `name`=".$dbo->quote($pcaratname).",".(!empty($picon) ? "`icon`=".$dbo->quote($picon)."," : "")."`align`=".$dbo->quote($pcaratmix).",`textimg`=".$dbo->quote($pcarattextimg)." WHERE `id`=".$dbo->quote($pwhereup).";";
			$dbo->setQuery($q);
			$dbo->execute();

			/**
			 *
			 * @since 1.7
			 *
			 * Save new caracteristics and assign them to items directly when updating an existing one.
			 *
			 */			
			$items_with_carat = array();
			if (count($piditems)) {
				// assign this new carat to the requested items
				foreach ($piditems as $iditems) {
					if (empty($iditems)) {
						continue;
					}
					$q = "SELECT `id`, `idcarat` FROM `#__vikrentitems_items` WHERE `id`=" . (int)$iditems . ";";
					$dbo->setQuery($q);
					$dbo->execute();
					if (!$dbo->getNumRows()) {
						continue;
					}
					$item_data = $dbo->loadAssoc();
					array_push($items_with_carat, $item_data['id']);
					$current_carats = empty($item_data['idcarat']) ? array() : explode(';', rtrim($item_data['idcarat'], ';'));
					if (in_array((string)$pwhereup, $current_carats)) {
						continue;
					}
					if (count($current_carats) === 1 && (string)$current_carats[0] == '0') {
						// make sure we do not concatenate a real ID to 0
						$current_carats = array();
					}
					array_push($current_carats, $pwhereup);
					$new_carats = implode(';', $current_carats) . ';';
					$q = "UPDATE `#__vikrentitems_items` SET `idcarat`=" . $dbo->quote($new_carats) . " WHERE `id`={$item_data['id']};";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
			if (!count($items_with_carat)) {
				// get all items to unset this carat (if previously set)
				array_push($items_with_carat, '0');
			}
			// unset the carat from the other items that may have it
			$q = "SELECT `id`, `idcarat` FROM `#__vikrentitems_items` WHERE `id` NOT IN (" . implode(', ', $items_with_carat) . ");";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows()) {
				$unset_items_carat = $dbo->loadAssocList();
				foreach ($unset_items_carat as $item_data) {
					$current_carats = empty($item_data['idcarat']) ? array() : explode(';', rtrim($item_data['idcarat'], ';'));
					if (!in_array((string)$pwhereup, $current_carats)) {
						// this item is not using this carat
						continue;
					}
					$caratkey = array_search((string)$pwhereup, $current_carats);
					if ($caratkey === false) {
						// key not found
						continue;
					}
					// unset this carat ID from the string
					unset($current_carats[$caratkey]);
					if (!count($current_carats)) {
						// an item with no carats assigned will be listed as "0;"
						$current_carats = array(0);
					}
					$new_carats = implode(';', $current_carats) . ';';
					$q = "UPDATE `#__vikrentitems_items` SET `idcarat`=" . $dbo->quote($new_carats) . " WHERE `id`={$item_data['id']};";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
			//
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=carat");
	}

	function removecarat() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "SELECT `icon` FROM `#__vikrentitems_caratteristiche` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() == 1) {
					$rows = $dbo->loadAssocList();
					if (!empty($rows[0]['icon']) && file_exists(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$rows[0]['icon'])) {
						@unlink(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$rows[0]['icon']);
					}
				}	
				$q = "DELETE FROM `#__vikrentitems_caratteristiche` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=carat");
	}

	function cancelcarat() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=carat");
	}

	function optionals() {
		VikRentItemsHelper::printHeader("6");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'optionals'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newoptional() {
		VikRentItemsHelper::printHeader("6");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'manageopt'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editoptional() {
		VikRentItemsHelper::printHeader("6");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'manageopt'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createoptional() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$app = JFactory::getApplication();
		$poptname = VikRequest::getString('optname', '', 'request');
		$poptdescr = VikRequest::getString('optdescr', '', 'request', VIKREQUEST_ALLOWHTML);
		$poptcost = VikRequest::getFloat('optcost', '', 'request');
		$poptperday = VikRequest::getString('optperday', '', 'request');
		$pmaxprice = VikRequest::getFloat('maxprice', '', 'request');
		$popthmany = VikRequest::getString('opthmany', '', 'request');
		$poptonlyonce = VikRequest::getString('optonlyonce', '', 'request');
		$poptonceperitem = VikRequest::getString('optonceperitem', '', 'request');
		$poptaliq = VikRequest::getString('optaliq', '', 'request');
		$pautoresize = VikRequest::getString('autoresize', '', 'request');
		$presizeto = VikRequest::getString('resizeto', '', 'request');
		$pforcesel = VikRequest::getString('forcesel', '', 'request');
		$pforceval = VikRequest::getString('forceval', '', 'request');
		$pforceifdays = VikRequest::getInt('forceifdays', '', 'request');
		$pforcevalperday = VikRequest::getString('forcevalperday', '', 'request');
		$pforcesel = $pforcesel == "1" ? 1 : 0;
		$pisspecification = VikRequest::getString('isspecification', '', 'request');
		$pisspecification = $pisspecification == "1" ? true : false;
		$pspecname = VikRequest::getVar('specname', array(0));
		$pspeccost = VikRequest::getVar('speccost', array(0));
		$piditems = VikRequest::getVar('iditems', array());
		if ($pforcesel == 1) {
			$strforceval = intval($pforceval)."-".($pforcevalperday == "1" ? "1" : "0");
		} else {
			$strforceval = "";
		}
		if (!empty($poptname)) {
			if (intval($_FILES['optimg']['error']) == 0 && VikRentItems::caniWrite(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources'. DIRECTORY_SEPARATOR) && trim($_FILES['optimg']['name'])!="") {
				jimport('joomla.filesystem.file');
				if (@is_uploaded_file($_FILES['optimg']['tmp_name'])) {
					$safename = JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['optimg']['name'])));
					if (file_exists(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $safename)) {
						$j = 1;
						while (file_exists(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $j . $safename)) {
							$j++;
						}
						$pwhere = VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $j . $safename;
					} else {
						$j = "";
						$pwhere = VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $safename;
					}
					VikRentItems::uploadFile($_FILES['optimg']['tmp_name'], $pwhere);
					if (!getimagesize($pwhere)){
						@unlink($pwhere);
						$picon = "";
					} else {
						@chmod($pwhere, 0644);
						$picon = $j . $safename;
						if ($pautoresize=="1" && !empty($presizeto)) {
							$eforj = new VikResizer();
							$origmod = $eforj->proportionalImage($pwhere, VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'r_'.$j.$safename, $presizeto, $presizeto);
							if ($origmod) {
								@unlink($pwhere);
								$picon = 'r_'.$j.$safename;
							}
						}
					}
				} else {
					$picon = "";
				}
			} else {
				$picon = "";
			}
			$poptperday=($poptperday=="each" ? "1" : "0");
			($popthmany=="yes" ? $popthmany="1" : $popthmany="0");
			$poptonlyonce = $poptonlyonce == "yes" ? 1 : 0;
			$poptonceperitem = $poptonceperitem == 'yes' ? 1 : 0;
			$specificationstr = '';
			if ($pisspecification == true && count($pspecname) > 0 && count($pspeccost) > 0 && count($pspecname) == count($pspeccost)) {
				foreach ($pspecname as $kspec => $vspec) {
					$sname = str_replace('_', ' ', $vspec);
					$scost = floatval($pspeccost[$kspec]);
					if (strlen($sname) > 0 && strlen($scost) > 0) {
						$specificationstr .= $sname.'_'.$scost.';;';
					}
				}
				$specificationstr = rtrim($specificationstr, ';;');
			}
			$dbo = JFactory::getDbo();
			$q = "SELECT `ordering` FROM `#__vikrentitems_optionals` ORDER BY `#__vikrentitems_optionals`.`ordering` DESC LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$getlast = $dbo->loadResult();
				$newsortnum = $getlast + 1;
			} else {
				$newsortnum = 1;
			}
			$q = "INSERT INTO `#__vikrentitems_optionals` (`name`,`descr`,`cost`,`perday`,`hmany`,`img`,`idiva`,`maxprice`,`forcesel`,`forceval`,`ordering`,`forceifdays`,`specifications`,`onlyonce`,`onceperitem`) VALUES(".$dbo->quote($poptname).", ".$dbo->quote($poptdescr).", ".$dbo->quote($poptcost).", ".$dbo->quote($poptperday).", ".$dbo->quote($popthmany).", ".$dbo->quote($picon).", ".$dbo->quote($poptaliq).", ".$dbo->quote($pmaxprice).", '".$pforcesel."', '".$strforceval."', '".$newsortnum."', '".$pforceifdays."', ".$dbo->quote($specificationstr).", ".$dbo->quote($poptonlyonce).", ".$dbo->quote($poptonceperitem).");";
			$dbo->setQuery($q);
			$dbo->execute();
			$newoptid = $dbo->insertid();
			$app->enqueueMessage(JText::translate('VRISUCCUPDOPTION'));

			if (!empty($newoptid)) {
				// assign/unset option-items relations
				$items_with_opt = array();
				if (count($piditems)) {
					// assign this new option to the requested items
					foreach ($piditems as $iditem) {
						if (empty($iditem)) {
							continue;
						}
						$q = "SELECT `id`, `idopt` FROM `#__vikrentitems_items` WHERE `id`=" . (int)$iditem . ";";
						$dbo->setQuery($q);
						$dbo->execute();
						if (!$dbo->getNumRows()) {
							continue;
						}
						$item_data = $dbo->loadAssoc();
						array_push($items_with_opt, $item_data['id']);
						$current_opts = empty($item_data['idopt']) ? array() : explode(';', rtrim($item_data['idopt'], ';'));
						if (in_array((string)$newoptid, $current_opts)) {
							continue;
						}
						if (count($current_opts) === 1 && (string)$current_opts[0] == '0') {
							// make sure we do not concatenate a real ID to 0
							$current_opts = array();
						}
						array_push($current_opts, $newoptid);
						$new_opts = implode(';', $current_opts) . ';';
						$q = "UPDATE `#__vikrentitems_items` SET `idopt`=" . $dbo->quote($new_opts) . " WHERE `id`={$item_data['id']};";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
				if (!count($items_with_opt)) {
					// get all items to unset this option (if previously set)
					array_push($items_with_opt, '0');
				}
				// unset the option from the other items that may have it
				$q = "SELECT `id`, `idopt` FROM `#__vikrentitems_items` WHERE `id` NOT IN (" . implode(', ', $items_with_opt) . ");";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows()) {
					$unset_items_opt = $dbo->loadAssocList();
					foreach ($unset_items_opt as $item_data) {
						$current_opts = empty($item_data['idopt']) ? array() : explode(';', rtrim($item_data['idopt'], ';'));
						if (!in_array((string)$newoptid, $current_opts)) {
							// this item is not using this option
							continue;
						}
						$optkey = array_search((string)$newoptid, $current_opts);
						if ($optkey === false) {
							// key not found
							continue;
						}
						// unset this option ID from the string
						unset($current_opts[$optkey]);
						if (!count($current_opts)) {
							// a item with no options assigned will be listed as "0;"
							$current_opts = array(0);
						}
						$new_opts = implode(';', $current_opts) . ';';
						$q = "UPDATE `#__vikrentitems_items` SET `idopt`=" . $dbo->quote($new_opts) . " WHERE `id`={$item_data['id']};";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
				//
			}
		}
		
		$app->redirect("index.php?option=com_vikrentitems&task=optionals");
	}

	function updateoptional() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$app = JFactory::getApplication();
		$poptname = VikRequest::getString('optname', '', 'request');
		$poptdescr = VikRequest::getString('optdescr', '', 'request', VIKREQUEST_ALLOWHTML);
		$poptcost = VikRequest::getFloat('optcost', '', 'request');
		$poptperday = VikRequest::getString('optperday', '', 'request');
		$pmaxprice = VikRequest::getFloat('maxprice', '', 'request');
		$popthmany = VikRequest::getString('opthmany', '', 'request');
		$poptonlyonce = VikRequest::getString('optonlyonce', '', 'request');
		$poptonceperitem = VikRequest::getString('optonceperitem', '', 'request');
		$poptaliq = VikRequest::getString('optaliq', '', 'request');
		$pwhereup = VikRequest::getString('whereup', '', 'request');
		$pautoresize = VikRequest::getString('autoresize', '', 'request');
		$presizeto = VikRequest::getString('resizeto', '', 'request');
		$pforcesel = VikRequest::getString('forcesel', '', 'request');
		$pforceval = VikRequest::getString('forceval', '', 'request');
		$pforceifdays = VikRequest::getInt('forceifdays', '', 'request');
		$pforcevalperday = VikRequest::getString('forcevalperday', '', 'request');
		$pforcesel = $pforcesel == "1" ? 1 : 0;
		$pisspecification = VikRequest::getString('isspecification', '', 'request');
		$pisspecification = $pisspecification == "1" ? true : false;
		$pspecname = VikRequest::getVar('specname', array(0));
		$pspeccost = VikRequest::getVar('speccost', array(0));
		$piditems = VikRequest::getVar('iditems', array());
		if ($pforcesel == 1) {
			$strforceval = intval($pforceval)."-".($pforcevalperday == "1" ? "1" : "0");
		} else {
			$strforceval = "";
		}
		if (!empty($poptname)) {
			if (intval($_FILES['optimg']['error']) == 0 && VikRentItems::caniWrite(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources'. DIRECTORY_SEPARATOR) && trim($_FILES['optimg']['name'])!="") {
				jimport('joomla.filesystem.file');
				if (@is_uploaded_file($_FILES['optimg']['tmp_name'])) {
					$safename = JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['optimg']['name'])));
					if (file_exists(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $safename)) {
						$j = 1;
						while (file_exists(VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $j . $safename)) {
							$j++;
						}
						$pwhere = VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $j . $safename;
					} else {
						$j = "";
						$pwhere = VRI_ADMIN_PATH . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $safename;
					}
					VikRentItems::uploadFile($_FILES['optimg']['tmp_name'], $pwhere);
					if (!getimagesize($pwhere)){
						@unlink($pwhere);
						$picon = "";
					} else {
						@chmod($pwhere, 0644);
						$picon = $j . $safename;
						if ($pautoresize=="1" && !empty($presizeto)) {
							$eforj = new VikResizer();
							$origmod = $eforj->proportionalImage($pwhere, VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'r_'.$j.$safename, $presizeto, $presizeto);
							if ($origmod) {
								@unlink($pwhere);
								$picon = 'r_' . $j . $safename;
							}
						}
					}
				} else {
					$picon = "";
				}
			} else {
				$picon = "";
			}
			($poptperday=="each" ? $poptperday="1" : $poptperday="0");
			($popthmany=="yes" ? $popthmany="1" : $popthmany="0");
			$poptonlyonce = $poptonlyonce == "yes" ? 1 : 0;
			$poptonceperitem = $poptonceperitem == 'yes' ? 1 : 0;
			$specificationstr = '';
			if ($pisspecification == true && count($pspecname) > 0 && count($pspeccost) > 0 && count($pspecname) == count($pspeccost)) {
				foreach ($pspecname as $kspec => $vspec) {
					$sname = str_replace('_', ' ', $vspec);
					$scost = floatval($pspeccost[$kspec]);
					if (strlen($sname) > 0 && strlen($scost) > 0) {
						$specificationstr .= $sname.'_'.$scost.';;';
					}
				}
				$specificationstr = rtrim($specificationstr, ';;');
			}
			$dbo = JFactory::getDbo();
			$q = "UPDATE `#__vikrentitems_optionals` SET `name`=".$dbo->quote($poptname).",`descr`=".$dbo->quote($poptdescr).",`cost`=".$dbo->quote($poptcost).",`perday`=".$dbo->quote($poptperday).",`hmany`=".$dbo->quote($popthmany).",".(!empty($picon) ? "`img`=".$dbo->quote($picon)."," : "")."`idiva`=".$dbo->quote($poptaliq).", `maxprice`=".$dbo->quote($pmaxprice).", `forcesel`='".$pforcesel."', `forceval`='".$strforceval."', `forceifdays`='".$pforceifdays."', `specifications`=".$dbo->quote($specificationstr).",`onlyonce`=".$dbo->quote($poptonlyonce).",`onceperitem`=".$dbo->quote($poptonceperitem)." WHERE `id`=".$dbo->quote($pwhereup).";";
			$dbo->setQuery($q);
			$dbo->execute();
			$app->enqueueMessage(JText::translate('VRISUCCUPDOPTION'));

			// assign/unset option-items relations
			$items_with_opt = array();
			if (count($piditems)) {
				// assign this new option to the requested items
				foreach ($piditems as $iditem) {
					if (empty($iditem)) {
						continue;
					}
					$q = "SELECT `id`, `idopt` FROM `#__vikrentitems_items` WHERE `id`=" . (int)$iditem . ";";
					$dbo->setQuery($q);
					$dbo->execute();
					if (!$dbo->getNumRows()) {
						continue;
					}
					$item_data = $dbo->loadAssoc();
					array_push($items_with_opt, $item_data['id']);
					$current_opts = empty($item_data['idopt']) ? array() : explode(';', rtrim($item_data['idopt'], ';'));
					if (in_array((string)$pwhereup, $current_opts)) {
						continue;
					}
					if (count($current_opts) === 1 && (string)$current_opts[0] == '0') {
						// make sure we do not concatenate a real ID to 0
						$current_opts = array();
					}
					array_push($current_opts, $pwhereup);
					$new_opts = implode(';', $current_opts) . ';';
					$q = "UPDATE `#__vikrentitems_items` SET `idopt`=" . $dbo->quote($new_opts) . " WHERE `id`={$item_data['id']};";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
			if (!count($items_with_opt)) {
				// get all items to unset this option (if previously set)
				array_push($items_with_opt, '0');
			}
			// unset the option from the other items that may have it
			$q = "SELECT `id`, `idopt` FROM `#__vikrentitems_items` WHERE `id` NOT IN (" . implode(', ', $items_with_opt) . ");";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows()) {
				$unset_items_opt = $dbo->loadAssocList();
				foreach ($unset_items_opt as $item_data) {
					$current_opts = empty($item_data['idopt']) ? array() : explode(';', rtrim($item_data['idopt'], ';'));
					if (!in_array((string)$pwhereup, $current_opts)) {
						// this item is not using this option
						continue;
					}
					$optkey = array_search((string)$pwhereup, $current_opts);
					if ($optkey === false) {
						// key not found
						continue;
					}
					// unset this option ID from the string
					unset($current_opts[$optkey]);
					if (!count($current_opts)) {
						// a item with no options assigned will be listed as "0;"
						$current_opts = array(0);
					}
					$new_opts = implode(';', $current_opts) . ';';
					$q = "UPDATE `#__vikrentitems_items` SET `idopt`=" . $dbo->quote($new_opts) . " WHERE `id`={$item_data['id']};";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
			//
		}
		
		$app->redirect("index.php?option=com_vikrentitems&task=optionals");
	}

	function removeoptionals() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "SELECT `img` FROM `#__vikrentitems_optionals` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() == 1) {
					$rows = $dbo->loadAssocList();
					if (!empty($rows[0]['img']) && file_exists(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$rows[0]['img'])) {
						@unlink(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$rows[0]['img']);
					}
				}	
				$q = "DELETE FROM `#__vikrentitems_optionals` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=optionals");
	}

	function canceloptional() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=optionals");
	}

	function stats() {
		VikRentItemsHelper::printHeader("10");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'stats'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function removestats() {
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "DELETE FROM `#__vikrentitems_stats` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=stats");
	}

	function cancelstats() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=stats");
	}

	function items() {
		VikRentItemsHelper::printHeader("7");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'items'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newitem() {
		VikRentItemsHelper::printHeader("7");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'manageitem'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function edititem() {
		VikRentItemsHelper::printHeader("7");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'manageitem'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createitem() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$mainframe = JFactory::getApplication();
		$pcname = VikRequest::getString('cname', '', 'request');
		$pccat = VikRequest::getVar('ccat', array(0));
		$pcdescr = VikRequest::getString('cdescr', '', 'request', VIKREQUEST_ALLOWRAW);
		$pshortdesc = VikRequest::getString('shortdesc', '', 'request', VIKREQUEST_ALLOWHTML);
		$pcplace = VikRequest::getVar('cplace', array(0));
		$pcretplace = VikRequest::getVar('cretplace', array(0));
		$pccarat = VikRequest::getVar('ccarat', array(0));
		$pcoptional = VikRequest::getVar('coptional', array(0));
		$pcavail = VikRequest::getString('cavail', '', 'request');
		$pautoresize = VikRequest::getString('autoresize', '', 'request');
		$presizeto = VikRequest::getString('resizeto', '', 'request');
		$pautoresizemore = VikRequest::getString('autoresizemore', '', 'request');
		$presizetomore = VikRequest::getString('resizetomore', '', 'request');
		$punits = VikRequest::getInt('units', '', 'request');
		$pimages = VikRequest::getVar('cimgmore', null, 'files', 'array');
		$pstartfrom = VikRequest::getString('startfrom', '', 'request');
		$pstartfromtext = VikRequest::getString('startfromtext', '', 'request');
		$pextraemail = VikRequest::getString('extraemail', '', 'request');
		$paskquantity = VikRequest::getString('askquantity', '', 'request');
		$paskquantity = $paskquantity == "yes" ? "1" : "0";
		$pdiscsquantstab = VikRequest::getString('discsquantstab', '', 'request');
		$pdiscsquantstab = $pdiscsquantstab == "yes" ? "1" : "0";
		$phourlycalendar = VikRequest::getString('hourlycalendar', '', 'request');
		$phourlycalendar = $phourlycalendar == "yes" ? "1" : "0";
		$ptimeslots = VikRequest::getString('timeslots', '', 'request');
		$ptimeslots = $ptimeslots == "yes" ? "1" : "0";
		$pdelivery = VikRequest::getString('delivery', '', 'request');
		$pdelivery = $pdelivery == "yes" ? "1" : "0";
		$poverdelcost = VikRequest::getString('overdelcost', '', 'request');
		$poverdelcost = floatval($poverdelcost);
		$pdropdaysplus = VikRequest::getString('dropdaysplus', '', 'request');
		$pdropdaysplus = strlen($pdropdaysplus) > 0 ? intval($pdropdaysplus) : '';
		$paramstr = 'startfromtext:'.$pstartfromtext.';_;hourlycalendar:'.$phourlycalendar.';_;discsquantstab:'.$pdiscsquantstab.';_;timeslots:'.$ptimeslots.';_;dropdaysplus:'.$pdropdaysplus.';_;delivery:'.$pdelivery.';_;overdelcost:'.$poverdelcost.';_;extraemail:'.$pextraemail.';_;';
		$pcustptitle = VikRequest::getString('custptitle', '', 'request');
		$pcustptitlew = VikRequest::getString('custptitlew', '', 'request');
		$pcustptitlew = in_array($pcustptitlew, array('before', 'after', 'replace')) ? $pcustptitlew : 'before';
		$pmetakeywords = VikRequest::getString('metakeywords', '', 'request');
		$pmetadescription = VikRequest::getString('metadescription', '', 'request');
		$psefalias = VikRequest::getString('sefalias', '', 'request');
		$psefalias = empty($psefalias) ? JFilterOutput::stringURLSafe($pcname) : JFilterOutput::stringURLSafe($psefalias);
		$pminquant = VikRequest::getInt('minquant', '', 'request');
		$pminquant = $pminquant < 1 ? 1 : $pminquant;
		//Items Grouping
		$pisgroup = VikRequest::getInt('isgroup', '', 'request');
		$pchildid = VikRequest::getVar('childid', array(0));
		$pgroupunits = VikRequest::getVar('groupunits', array(0));
		//
		jimport('joomla.filesystem.file');
		if (!empty($pcname)) {
			if (intval($_FILES['cimg']['error']) == 0 && VikRentItems::caniWrite(VRI_ADMIN_PATH.DS.'resources'.DS) && trim($_FILES['cimg']['name'])!="") {
				if (@is_uploaded_file($_FILES['cimg']['tmp_name'])) {
					$safename = JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['cimg']['name'])));
					if (file_exists(VRI_ADMIN_PATH.DS.'resources'.DS.$safename)) {
						$j = 1;
						while (file_exists(VRI_ADMIN_PATH.DS.'resources'.DS.$j.$safename)) {
							$j++;
						}
						$pwhere=VRI_ADMIN_PATH.DS.'resources'.DS.$j.$safename;
					} else {
						$j = "";
						$pwhere=VRI_ADMIN_PATH.DS.'resources'.DS.$safename;
					}
					VikRentItems::uploadFile($_FILES['cimg']['tmp_name'], $pwhere);
					if (!($mainimginfo = getimagesize($pwhere))){
						@unlink($pwhere);
						$picon = "";
					} else {
						@chmod($pwhere, 0644);
						$picon = $j . $safename;
						if ($pautoresize=="1" && !empty($presizeto)) {
							$eforj = new VikResizer();
							$origmod = $eforj->proportionalImage($pwhere, VRI_ADMIN_PATH.DS.'resources'.DS.'r_'.$j.$safename, $presizeto, $presizeto);
							if ($origmod) {
								@unlink($pwhere);
								$picon = 'r_' . $j . $safename;
							}
						}
						$thumbs_width = VikRentItems::getThumbnailsWidth();
						//VikRentItems 1.1 - Thumbnail for better CSS forcing result
						if ($mainimginfo[0] > $thumbs_width) {
							$eforj = new VikResizer();
							$eforj->proportionalImage(VRI_ADMIN_PATH.DS.'resources'.DS.$picon, VRI_ADMIN_PATH.DS.'resources'.DS.'vthumb_'.$picon, $thumbs_width, $thumbs_width);
						}
						//end VikRentItems 1.1 - Thumbnail for better CSS forcing result
					}
				} else {
					$picon = "";
				}
			} else {
				$picon = "";
			}
			//more images
			$creativik = new VikResizer();
			$bigsdest = VRI_ADMIN_PATH.DS.'resources'.DS;
			$thumbsdest = VRI_ADMIN_PATH.DS.'resources'.DS;
			$dest = VRI_ADMIN_PATH.DS.'resources'.DS;
			$moreimagestr = "";
			$arrimgs = array();
			foreach ($pimages['name'] as $kk => $ci) {
				if (!empty($ci)) {
					$arrimgs[] = $kk;
				}
			}
			if (is_array($arrimgs) && count($arrimgs)) {
				foreach ($arrimgs as $imgk) {
					if (strlen(trim($pimages['name'][$imgk]))) {
						$filename = JFile::makeSafe(str_replace(" ", "_", strtolower($pimages['name'][$imgk])));
						$src = $pimages['tmp_name'][$imgk];
						$j = "";
						if (file_exists($dest.$filename)) {
							$j=rand(171, 1717);
							while (file_exists($dest.$j.$filename)) {
								$j++;
							}
						}
						$finaldest = $dest.$j.$filename;
						$check = !empty($pimages['tmp_name'][$imgk]) ? getimagesize($pimages['tmp_name'][$imgk]) : [];
						if ($check[2] & imagetypes()) {
							if (VikRentItems::uploadFile($src, $finaldest)) {
								$gimg=$j.$filename;
								//orig img
								$origmod = true;
								if ($pautoresizemore == "1" && !empty($presizetomore)) {
									$origmod = $creativik->proportionalImage($finaldest, $bigsdest.'big_'.$j.$filename, $presizetomore, $presizetomore);
								} else {
									VikRentItems::uploadFile($finaldest, $bigsdest.'big_'.$j.$filename, true);
								}
								//thumb
								$thumb = $creativik->proportionalImage($finaldest, $thumbsdest.'thumb_'.$j.$filename, 70, 70);
								if (!$thumb || !$origmod) {
									if (file_exists($bigsdest.'big_'.$j.$filename)) @unlink($bigsdest.'big_'.$j.$filename);
									if (file_exists($thumbsdest.'thumb_'.$j.$filename)) @unlink($thumbsdest.'thumb_'.$j.$filename);
									VikError::raiseWarning('', 'Error While Uploading the File: '.$pimages['name'][$imgk]);
								} else {
									$moreimagestr.=$j.$filename.";;";
								}
								@unlink($finaldest);
							} else {
								VikError::raiseWarning('', 'Error While Uploading the File: '.$pimages['name'][$imgk]);
							}
						} else {
							VikError::raiseWarning('', 'Error While Uploading the File: '.$pimages['name'][$imgk]);
						}
					}
				}
			}
			//end more images
			if (is_array($pcplace) && count($pcplace)) {
				$pcplacedef="";
				foreach ($pcplace as $cpla) {
					$pcplacedef.=$cpla.";";
				}
			} else {
				$pcplacedef="";
			}
			if (is_array($pcretplace) && count($pcretplace)) {
				$pcretplacedef="";
				foreach ($pcretplace as $cpla) {
					$pcretplacedef.=$cpla.";";
				}
			} else {
				$pcretplacedef="";
			}
			if (is_array($pccat) && count($pccat)) {
				$pccatdef="";
				foreach ($pccat as $ccat) {
					$pccatdef.=$ccat.";";
				}
			} else {
				$pccatdef="";
			}
			if (is_array($pccarat) && count($pccarat)) {
				$pccaratdef="";
				foreach ($pccarat as $ccarat) {
					$pccaratdef.=$ccarat.";";
				}
			} else {
				$pccaratdef="";
			}
			if (is_array($pcoptional) && count($pcoptional)) {
				$pcoptionaldef="";
				foreach ($pcoptional as $coptional) {
					$pcoptionaldef.=$coptional.";";
				}
			} else {
				$pcoptionaldef="";
			}
			$pcavaildef=($pcavail=="yes" ? "1" : "0");
			//JSON params
			$item_jsparams = array();
			$item_jsparams['custptitle'] = $pcustptitle;
			$item_jsparams['custptitlew'] = $pcustptitlew;
			$item_jsparams['metakeywords'] = $pmetakeywords;
			$item_jsparams['metadescription'] = $pmetadescription;
			$item_jsparams['minquant'] = $pminquant;
			$dbo = JFactory::getDbo();
			$q = "INSERT INTO `#__vikrentitems_items` (`name`,`img`,`idcat`,`idcarat`,`idopt`,`info`,`idplace`,`avail`,`units`,`idretplace`,`moreimgs`,`startfrom`,`askquantity`,`params`,`shortdesc`,`jsparams`,`alias`,`isgroup`) VALUES(".$dbo->quote($pcname).",".$dbo->quote($picon).",".$dbo->quote($pccatdef).",".$dbo->quote($pccaratdef).",".$dbo->quote($pcoptionaldef).",".$dbo->quote($pcdescr).",".$dbo->quote($pcplacedef).",".$dbo->quote($pcavaildef).",".($punits > 0 ? $dbo->quote($punits) : "'1'").",".$dbo->quote($pcretplacedef).", ".$dbo->quote($moreimagestr).", ".(strlen($pstartfrom) > 0 ? "'".$pstartfrom."'" : "null").", '".$paskquantity."', ".$dbo->quote($paramstr).", ".$dbo->quote($pshortdesc).", ".$dbo->quote(json_encode($item_jsparams)).", ".$dbo->quote($psefalias).", ".($pisgroup > 0 && @count($pchildid) > 0 && !empty($pchildid[0]) ? '1' : '0').");";
			$dbo->setQuery($q);
			$dbo->execute();
			$lid = $dbo->insertid();
			if (!empty($lid)) {
				//check items grouping relations
				if ($pisgroup > 0 && @count($pchildid) > 0 && !empty($pchildid[0])) {
					foreach ($pchildid as $child_id) {
						if (empty($child_id)) {
							continue;
						}
						$set_units = isset($pgroupunits[(int)$child_id]) ? (int)$pgroupunits[(int)$child_id] : 1;
						$set_units = $set_units > 0 ? $set_units : 1;
						$q = "INSERT INTO `#__vikrentitems_groupsrel` (`parentid`,`childid`,`units`) VALUES(".$lid.", ".(int)$child_id.", ".$set_units.");";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
				//
				$mainframe->redirect("index.php?option=com_vikrentitems&task=tariffs&cid[]=".$lid);
				exit;
			}
		}
		$mainframe->redirect("index.php?option=com_vikrentitems&task=items");
	}

	function updateitem() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$this->do_updateitem();
	}

	function updateitemapply() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$this->do_updateitem(true);
	}

	function do_updateitem($stay = false) {
		$mainframe = JFactory::getApplication();
		$pcname = VikRequest::getString('cname', '', 'request');
		$pccat = VikRequest::getVar('ccat', array(0));
		$pcdescr = VikRequest::getString('cdescr', '', 'request', VIKREQUEST_ALLOWRAW);
		$pshortdesc = VikRequest::getString('shortdesc', '', 'request', VIKREQUEST_ALLOWHTML);
		$pcplace = VikRequest::getVar('cplace', array(0));
		$pcretplace = VikRequest::getVar('cretplace', array(0));
		$pccarat = VikRequest::getVar('ccarat', array(0));
		$pcoptional = VikRequest::getVar('coptional', array(0));
		$pcavail = VikRequest::getString('cavail', '', 'request');
		$pwhereup = VikRequest::getString('whereup', '', 'request');
		$pautoresize = VikRequest::getString('autoresize', '', 'request');
		$presizeto = VikRequest::getString('resizeto', '', 'request');
		$pautoresizemore = VikRequest::getString('autoresizemore', '', 'request');
		$presizetomore = VikRequest::getString('resizetomore', '', 'request');
		$punits = VikRequest::getInt('units', '', 'request');
		$pimages = VikRequest::getVar('cimgmore', null, 'files', 'array');
		$pactmoreimgs = VikRequest::getString('actmoreimgs', '', 'request');
		$pstartfrom = VikRequest::getString('startfrom', '', 'request');
		$pstartfromtext = VikRequest::getString('startfromtext', '', 'request');
		$pextraemail = VikRequest::getString('extraemail', '', 'request');
		$paskquantity = VikRequest::getString('askquantity', '', 'request');
		$paskquantity = $paskquantity == "yes" ? "1" : "0";
		$pdiscsquantstab = VikRequest::getString('discsquantstab', '', 'request');
		$pdiscsquantstab = $pdiscsquantstab == "yes" ? "1" : "0";
		$phourlycalendar = VikRequest::getString('hourlycalendar', '', 'request');
		$phourlycalendar = $phourlycalendar == "yes" ? "1" : "0";
		$ptimeslots = VikRequest::getString('timeslots', '', 'request');
		$ptimeslots = $ptimeslots == "yes" ? "1" : "0";
		$pdelivery = VikRequest::getString('delivery', '', 'request');
		$pdelivery = $pdelivery == "yes" ? "1" : "0";
		$poverdelcost = VikRequest::getString('overdelcost', '', 'request');
		$poverdelcost = floatval($poverdelcost);
		$pdropdaysplus = VikRequest::getString('dropdaysplus', '', 'request');
		$pdropdaysplus = strlen($pdropdaysplus) > 0 ? intval($pdropdaysplus) : '';
		$paramstr = 'startfromtext:'.$pstartfromtext.';_;hourlycalendar:'.$phourlycalendar.';_;discsquantstab:'.$pdiscsquantstab.';_;timeslots:'.$ptimeslots.';_;dropdaysplus:'.$pdropdaysplus.';_;delivery:'.$pdelivery.';_;overdelcost:'.$poverdelcost.';_;extraemail:'.$pextraemail.';_;';
		$pcustptitle = VikRequest::getString('custptitle', '', 'request');
		$pcustptitlew = VikRequest::getString('custptitlew', '', 'request');
		$pcustptitlew = in_array($pcustptitlew, array('before', 'after', 'replace')) ? $pcustptitlew : 'before';
		$pmetakeywords = VikRequest::getString('metakeywords', '', 'request');
		$pmetadescription = VikRequest::getString('metadescription', '', 'request');
		$psefalias = VikRequest::getString('sefalias', '', 'request');
		$psefalias = empty($psefalias) ? JFilterOutput::stringURLSafe($pcname) : JFilterOutput::stringURLSafe($psefalias);
		$pimgsorting = VikRequest::getVar('imgsorting', array());
		$pminquant = VikRequest::getInt('minquant', '', 'request');
		$pminquant = $pminquant < 1 ? 1 : $pminquant;
		//Items Grouping
		$pisgroup = VikRequest::getInt('isgroup', '', 'request');
		$pchildid = VikRequest::getVar('childid', array(0));
		$pgroupunits = VikRequest::getVar('groupunits', array(0));
		$current_item = VikRentItems::getItemInfo((int)$pwhereup);
		//
		jimport('joomla.filesystem.file');
		if (!empty($pcname)) {
			if (intval($_FILES['cimg']['error']) == 0 && VikRentItems::caniWrite(VRI_ADMIN_PATH.DS.'resources'.DS) && trim($_FILES['cimg']['name'])!="") {
				if (@is_uploaded_file($_FILES['cimg']['tmp_name'])) {
					$safename = JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['cimg']['name'])));
					if (file_exists(VRI_ADMIN_PATH.DS.'resources'.DS.$safename)) {
						$j = 1;
						while (file_exists(VRI_ADMIN_PATH.DS.'resources'.DS.$j.$safename)) {
							$j++;
						}
						$pwhere=VRI_ADMIN_PATH.DS.'resources'.DS.$j.$safename;
					} else {
						$j = "";
						$pwhere=VRI_ADMIN_PATH.DS.'resources'.DS.$safename;
					}
					VikRentItems::uploadFile($_FILES['cimg']['tmp_name'], $pwhere);
					if (!($mainimginfo = getimagesize($pwhere))){
						@unlink($pwhere);
						$picon = "";
					} else {
						@chmod($pwhere, 0644);
						$picon = $j . $safename;
						if ($pautoresize=="1" && !empty($presizeto)) {
							$eforj = new VikResizer();
							$origmod = $eforj->proportionalImage($pwhere, VRI_ADMIN_PATH.DS.'resources'.DS.'r_'.$j.$safename, $presizeto, $presizeto);
							if ($origmod) {
								@unlink($pwhere);
								$picon = 'r_' . $j . $safename;
							}
						}
						$thumbs_width = VikRentItems::getThumbnailsWidth();
						//VikRentItems 1.1 - Thumbnail for better CSS forcing result
						if ($mainimginfo[0] > $thumbs_width) {
							$eforj = new VikResizer();
							$eforj->proportionalImage(VRI_ADMIN_PATH.DS.'resources'.DS.$picon, VRI_ADMIN_PATH.DS.'resources'.DS.'vthumb_'.$picon, $thumbs_width, $thumbs_width);
						}
						//end VikRentItems 1.1 - Thumbnail for better CSS forcing result
					}
				} else {
					$picon = "";
				}
			} else {
				$picon = "";
			}
			//more images
			$creativik = new VikResizer();
			$bigsdest = VRI_ADMIN_PATH.DS.'resources'.DS;
			$thumbsdest = VRI_ADMIN_PATH.DS.'resources'.DS;
			$dest = VRI_ADMIN_PATH.DS.'resources'.DS;
			$moreimagestr = $pactmoreimgs;
			$arrimgs = array();
			foreach ($pimages['name'] as $kk => $ci) {
				if (!empty($ci)) {
					$arrimgs[] = $kk;
				}
			}
			if (is_array($arrimgs) && count($arrimgs)) {
				foreach ($arrimgs as $imgk){
					if (strlen(trim($pimages['name'][$imgk]))) {
						$filename = JFile::makeSafe(str_replace(" ", "_", strtolower($pimages['name'][$imgk])));
						$src = $pimages['tmp_name'][$imgk];
						$j = "";
						if (file_exists($dest.$filename)) {
							$j=rand(171, 1717);
							while (file_exists($dest.$j.$filename)) {
								$j++;
							}
						}
						$finaldest = $dest.$j.$filename;
						$check = !empty($pimages['tmp_name'][$imgk]) ? getimagesize($pimages['tmp_name'][$imgk]) : [];
						if ($check[2] & imagetypes()) {
							if (VikRentItems::uploadFile($src, $finaldest)) {
								$gimg=$j.$filename;
								//orig img
								$origmod = true;
								if ($pautoresizemore == "1" && !empty($presizetomore)) {
									$origmod = $creativik->proportionalImage($finaldest, $bigsdest.'big_'.$j.$filename, $presizetomore, $presizetomore);
								} else {
									VikRentItems::uploadFile($finaldest, $bigsdest.'big_'.$j.$filename, true);
								}
								//thumb
								$thumb = $creativik->proportionalImage($finaldest, $thumbsdest.'thumb_'.$j.$filename, 70, 70);
								if (!$thumb || !$origmod) {
									if (file_exists($bigsdest.'big_'.$j.$filename)) @unlink($bigsdest.'big_'.$j.$filename);
									if (file_exists($thumbsdest.'thumb_'.$j.$filename)) @unlink($thumbsdest.'thumb_'.$j.$filename);
									VikError::raiseWarning('', 'Error While Uploading the File: '.$pimages['name'][$imgk]);
								} else {
									$moreimagestr.=$j.$filename.";;";
								}
								@unlink($finaldest);
							} else {
								VikError::raiseWarning('', 'Error While Uploading the File: '.$pimages['name'][$imgk]);
							}
						} else {
							VikError::raiseWarning('', 'Error While Uploading the File: '.$pimages['name'][$imgk]);
						}
					}
				}
			}
			//end more images

			/**
			 * Sorting of extra images.
			 * 
			 * @since 	1.7
			 */
			$sorted_extraim = array();
			$extraim_parts = explode(';;', $moreimagestr);
			foreach ($pimgsorting as $k => $v) {
				$capkey = -1;
				if (isset($extraim_parts[$k])) {
					$sorted_extraim[] = $v;
				}
			}
			$tot_sorted_im = count($sorted_extraim);
			if ($tot_sorted_im != count($extraim_parts)) {
				foreach ($extraim_parts as $k => $v) {
					if ($k <= ($tot_sorted_im - 1)) {
						continue;
					}
					$sorted_extraim[] = $v;
				}
			}
			$moreimagestr = implode(';;', $sorted_extraim);
			//

			if (is_array($pcplace) && count($pcplace)) {
				$pcplacedef="";
				foreach ($pcplace as $cpla) {
					$pcplacedef.=$cpla.";";
				}
			} else {
				$pcplacedef="";
			}
			if (is_array($pcretplace) && count($pcretplace)) {
				$pcretplacedef="";
				foreach ($pcretplace as $cpla) {
					$pcretplacedef.=$cpla.";";
				}
			} else {
				$pcretplacedef="";
			}
			if (is_array($pccat) && count($pccat)) {
				$pccatdef="";
				foreach ($pccat as $ccat) {
					$pccatdef.=$ccat.";";
				}
			} else {
				$pccatdef="";
			}
			if (is_array($pccarat) && count($pccarat)) {
				$pccaratdef="";
				foreach ($pccarat as $ccarat) {
					$pccaratdef.=$ccarat.";";
				}
			} else {
				$pccaratdef="";
			}
			if (is_array($pcoptional) && count($pcoptional)) {
				$pcoptionaldef="";
				foreach ($pcoptional as $coptional) {
					$pcoptionaldef.=$coptional.";";
				}
			} else {
				$pcoptionaldef="";
			}
			$pcavaildef=($pcavail=="yes" ? "1" : "0");
			//JSON params
			$item_jsparams = array();
			$item_jsparams['custptitle'] = $pcustptitle;
			$item_jsparams['custptitlew'] = $pcustptitlew;
			$item_jsparams['metakeywords'] = $pmetakeywords;
			$item_jsparams['metadescription'] = $pmetadescription;
			$item_jsparams['minquant'] = $pminquant;
			$dbo = JFactory::getDbo();
			$q = "UPDATE `#__vikrentitems_items` SET `name`=".$dbo->quote($pcname).",".(!empty($picon) ? "`img`=".$dbo->quote($picon)."," : "")."`idcat`=".$dbo->quote($pccatdef).",`idcarat`=".$dbo->quote($pccaratdef).",`idopt`=".$dbo->quote($pcoptionaldef).",`info`=".$dbo->quote($pcdescr).",`idplace`=".$dbo->quote($pcplacedef).",`avail`=".$dbo->quote($pcavaildef).",`units`=".($punits > 0 ? $dbo->quote($punits) : "'1'").",`idretplace`=".$dbo->quote($pcretplacedef).",`moreimgs`=".$dbo->quote($moreimagestr).",`startfrom`=".(strlen($pstartfrom) > 0 ? "'".$pstartfrom."'" : "null").",`askquantity`='".$paskquantity."',`params`=".$dbo->quote($paramstr).",`shortdesc`=".$dbo->quote($pshortdesc).",`jsparams`=".$dbo->quote(json_encode($item_jsparams)).",`alias`=".$dbo->quote($psefalias).",`isgroup`=".($pisgroup > 0 && @count($pchildid) > 0 && !empty($pchildid[0]) ? '1' : '0')." WHERE `id`=".$dbo->quote($pwhereup).";";
			$dbo->setQuery($q);
			$dbo->execute();
			//check items grouping relations
			if ($pisgroup > 0 && @count($pchildid) > 0 && !empty($pchildid[0])) {
				$groups_rel = array();
				foreach ($pchildid as $child_id) {
					if (empty($child_id)) {
						continue;
					}
					$set_units = isset($pgroupunits[(int)$child_id]) ? (int)$pgroupunits[(int)$child_id] : 1;
					$set_units = $set_units > 0 ? $set_units : 1;
					array_push($groups_rel, array(
						'parentid' => $current_item['id'],
						'childid' => (int)$child_id,
						'units' => $set_units
					));
				}
				if ($current_item['isgroup'] > 0) {
					//it was a group also before, check if the relations are different and raise warning
					$q = "SELECT * FROM `#__vikrentitems_groupsrel` WHERE `parentid`=".$current_item['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
					$prev_rels = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
					$missing_ids = false;
					foreach ($prev_rels as $prev_rel) {
						$id_found = false;
						foreach ($groups_rel as $rel) {
							if ($rel['childid'] == $prev_rel['childid']) {
								$id_found = true;
								break;
							}
						}
						if (!$id_found) {
							$missing_ids = true;
							break;
						}
					}
					if ($missing_ids || count($prev_rels) != count($groups_rel)) {
						VikError::raiseWarning('', JText::translate('VRIUPDITEMDIFFGROUP'));
					}
				}
				//attempt to delete any possible previous relation
				$q = "DELETE FROM `#__vikrentitems_groupsrel` WHERE `parentid`=".$current_item['id'].";";
				$dbo->setQuery($q);
				$dbo->execute();
				//create new relations
				foreach ($groups_rel as $grel) {
					$q = "INSERT INTO `#__vikrentitems_groupsrel` (`parentid`,`childid`,`units`) VALUES(".$grel['parentid'].", ".$grel['childid'].", ".$grel['units'].");";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			} else {
				if ($current_item['isgroup'] > 0) {
					//no more a group, but it used to be. Remove relations and raise warning about the availability
					$q = "DELETE FROM `#__vikrentitems_groupsrel` WHERE `parentid`=".$current_item['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
					VikError::raiseWarning('', JText::translate('VRIUPDITEMNOMOREAGROUP'));
				}
			}
			//
			if ($stay === true) {
				$mainframe->redirect("index.php?option=com_vikrentitems&task=edititem&cid[]=".$pwhereup);
				exit;
			}
		}
		$mainframe->redirect("index.php?option=com_vikrentitems&task=items");
	}

	function removeitem() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "DELETE FROM `#__vikrentitems_items` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
				$q = "DELETE FROM `#__vikrentitems_dispcost` WHERE `iditem`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=items");
	}

	function modavail() {
		$cid = VikRequest::getVar('cid', array(0));
		$item = $cid[0];
		if (!empty($item)) {
			$dbo = JFactory::getDbo();
			$q = "SELECT `avail` FROM `#__vikrentitems_items` WHERE `id`=".$dbo->quote($item).";";
			$dbo->setQuery($q);
			$dbo->execute();
			$get = $dbo->loadAssocList();
			$q = "UPDATE `#__vikrentitems_items` SET `avail`='".(intval($get[0]['avail'])==1 ? 0 : 1)."' WHERE `id`=".$dbo->quote($item).";";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=items");
	}

	function tariffs() {
		VikRentItemsHelper::printHeader("fares");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'tariffs'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function removetariffs() {
		$ids = VikRequest::getVar('cid', array(0));
		$pelemid = VikRequest::getString('elemid', '', 'request');
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $r) {
				$x = explode(";", $r);
				foreach ($x as $rm) {
					if (!empty($rm)) {
						$q = "DELETE FROM `#__vikrentitems_dispcost` WHERE `id`=".$dbo->quote($rm).";";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=tariffs&cid[]=".$pelemid);
	}

	function tariffshours() {
		VikRentItemsHelper::printHeader("fares");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'tariffshours'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function removetariffshours() {
		$ids = VikRequest::getVar('cid', array(0));
		$pelemid = VikRequest::getString('elemid', '', 'request');
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $r) {
				$x = explode(";", $r);
				foreach ($x as $rm) {
					if (!empty($rm)) {
						$q = "DELETE FROM `#__vikrentitems_dispcosthours` WHERE `id`=".$dbo->quote($rm).";";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=tariffshours&cid[]=".$pelemid);
	}

	function hourscharges() {
		VikRentItemsHelper::printHeader("fares");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'hourscharges'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function removehourscharges() {
		$ids = VikRequest::getVar('cid', array(0));
		$pelemid = VikRequest::getString('elemid', '', 'request');
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $r) {
				$x = explode(";", $r);
				foreach ($x as $rm) {
					if (!empty($rm)) {
						$q = "DELETE FROM `#__vikrentitems_hourscharges` WHERE `id`=".$dbo->quote($rm).";";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=hourscharges&cid[]=".$pelemid);
	}

	function cancel() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=items");
	}

	function calendar() {
		VikRentItemsHelper::printHeader("19");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'calendar'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function cancelcalendar() {
		$piditem = VikRequest::getString('iditem', '', 'request');
		$preturn = VikRequest::getString('return', '', 'request');
		$pidorder = VikRequest::getString('idorder', '', 'request');
		$mainframe = JFactory::getApplication();
		if ($preturn == 'order' && !empty($pidorder)) {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=editorder&cid[]=".$pidorder);
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=calendar&cid[]=".$piditem);
		}
	}

	function goconfig() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=config");
	}

	function config() {
		VikRentItemsHelper::printHeader("11");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'config'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function saveconfig() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::_('JINVALID_TOKEN'), 403);
		}

		$dbo = JFactory::getDbo();
		$session = JFactory::getSession();
		$pallowrent = VikRequest::getString('allowrent', '', 'request');
		$pdisabledrentmsg = VikRequest::getString('disabledrentmsg', '', 'request', VIKREQUEST_ALLOWHTML);
		$ptimeopenstorealw = VikRequest::getString('timeopenstorealw', '', 'request');
		$ptimeopenstorefh = VikRequest::getString('timeopenstorefh', '', 'request');
		$ptimeopenstorefm = VikRequest::getString('timeopenstorefm', '', 'request');
		$ptimeopenstoreth = VikRequest::getString('timeopenstoreth', '', 'request');
		$ptimeopenstoretm = VikRequest::getString('timeopenstoretm', '', 'request');
		$phoursmorerentback = VikRequest::getString('hoursmorerentback', '', 'request');
		$phoursmoreitemavail = VikRequest::getString('hoursmoreitemavail', '', 'request');
		$pplacesfront = VikRequest::getString('placesfront', '', 'request');
		$pdateformat = VikRequest::getString('dateformat', '', 'request');
		$ptimeformat = VikRequest::getString('timeformat', '', 'request');
		$pshowcategories = VikRequest::getString('showcategories', '', 'request');
		$ptokenform = VikRequest::getString('tokenform', '', 'request');
		$padminemail = VikRequest::getString('adminemail', '', 'request');
		$psenderemail = VikRequest::getString('senderemail', '', 'request');
		$pminuteslock = VikRequest::getString('minuteslock', '', 'request');
		$pfooterordmail = VikRequest::getString('footerordmail', '', 'request', VIKREQUEST_ALLOWHTML);
		$prequirelogin = VikRequest::getString('requirelogin', '', 'request');
		$pusefa = VikRequest::getInt('usefa', '', 'request');
		$pusefa = $pusefa > 0 ? 1 : 0;
		$ploadjquery = VikRequest::getString('loadjquery', '', 'request');
		$ploadjquery = $ploadjquery == "yes" ? "1" : "0";
		$pcalendar = VikRequest::getString('calendar', '', 'request');
		$pcalendar = $pcalendar == "joomla" ? "joomla" : "jqueryui";
		$pehourschbasp = VikRequest::getString('ehourschbasp', '', 'request');
		$pehourschbasp = $pehourschbasp == "1" ? 1 : 0;
		$penablecoupons = VikRequest::getString('enablecoupons', '', 'request');
		$penablecoupons = $penablecoupons == "1" ? 1 : 0;
		$penablepin = VikRequest::getInt('enablepin', 0, 'request');
		$penablepin = $penablepin > 0 ? 1 : 0;
		$ptodaybookings = VikRequest::getInt('todaybookings', '', 'request');
		$ptodaybookings = $ptodaybookings === 1 ? 1 : 0;
		$ppickondrop = VikRequest::getInt('pickondrop', '', 'request');
		$ppickondrop = $ppickondrop === 1 ? 1 : 0;
		$picalkey = VikRequest::getString('icalkey', '', 'request');
		$pforcepickupt = VikRequest::getString('forcepickupt', '', 'request');
		$pforcepickupth = VikRequest::getString('forcepickupth', '', 'request');
		$pforcepickuptm = VikRequest::getString('forcepickuptm', '', 'request');
		$pforcedropofft = VikRequest::getString('forcedropofft', '', 'request');
		$pforcedropoffth = VikRequest::getString('forcedropoffth', '', 'request');
		$pforcedropofftm = VikRequest::getString('forcedropofftm', '', 'request');
		$pglobalclosingdays = VikRequest::getVar('globalclosingdays', array(0));
		$globalclosingdaystr = '';
		if (is_array($pglobalclosingdays) && count($pglobalclosingdays) > 0 && !empty($pglobalclosingdays[0])) {
			foreach ($pglobalclosingdays as $globcday) {
				$cdayparts = explode(':', $globcday);
				$cdaydate = strtotime(trim($cdayparts[0]));
				if ($cdaydate && !empty($cdaydate) && in_array($cdayparts[1], array('1', '2'))) {
					$globalclosingdaystr .= $cdaydate.':'.$cdayparts[1].';';
				}
			}
		}
		$pvrisef = VikRequest::getInt('vrisef', '', 'request');
		$vrisef = file_exists(VRI_SITE_PATH.DS.'router.php');
		if ($pvrisef === 1) {
			if (!$vrisef) {
				rename(VRI_SITE_PATH.DS.'_router.php', VRI_SITE_PATH.DS.'router.php');
			}
		} else {
			if ($vrisef) {
				rename(VRI_SITE_PATH.DS.'router.php', VRI_SITE_PATH.DS.'_router.php');
			}
		}
		$pcronkey = VikRequest::getString('cronkey', '', 'request');
		$pmultilang = VikRequest::getString('multilang', '', 'request');
		$pmultilang = $pmultilang == "1" ? 1 : 0;
		$psetdropdplus = VikRequest::getString('setdropdplus', '', 'request');
		$psetdropdplus = !empty($psetdropdplus) ? intval($psetdropdplus) : '';
		$pmindaysadvance = VikRequest::getInt('mindaysadvance', '', 'request');
		$pmindaysadvance = $pmindaysadvance < 0 ? 0 : $pmindaysadvance;
		$pmaxdate = VikRequest::getString('maxdate', '', 'request');
		$pmaxdate = intval($pmaxdate) < 1 ? 2 : $pmaxdate;
		$pmaxdateinterval = VikRequest::getString('maxdateinterval', '', 'request');
		$pmaxdateinterval = !in_array($pmaxdateinterval, array('d', 'w', 'm', 'y')) ? 'y' : $pmaxdateinterval;
		$maxdate_str = '+'.$pmaxdate.$pmaxdateinterval;
		$picon = "";
		if (intval($_FILES['sitelogo']['error']) == 0 && trim($_FILES['sitelogo']['name'])!="") {
			jimport('joomla.filesystem.file');
			if (@is_uploaded_file($_FILES['sitelogo']['tmp_name'])) {
				$safename = JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['sitelogo']['name'])));
				if (file_exists(VRI_ADMIN_PATH.DS.'resources'.DS.$safename)) {
					$j = 1;
					while (file_exists(VRI_ADMIN_PATH.DS.'resources'.DS.$j.$safename)) {
						$j++;
					}
					$pwhere=VRI_ADMIN_PATH.DS.'resources'.DS.$j.$safename;
				} else {
					$j = "";
					$pwhere=VRI_ADMIN_PATH.DS.'resources'.DS.$safename;
				}
				VikRentItems::uploadFile($_FILES['sitelogo']['tmp_name'], $pwhere);
				if (!getimagesize($pwhere)){
					@unlink($pwhere);
					$picon = "";
				} else {
					@chmod($pwhere, 0644);
					$picon = $j . $safename;
				}
			}
			if (!empty($picon)) {
				$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($picon)." WHERE `param`='sitelogo';";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$res_backend_path = VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR;
		$pbackicon = "";
		if (intval($_FILES['backlogo']['error']) == 0 && trim($_FILES['backlogo']['name'])!="") {
			jimport('joomla.filesystem.file');
			if (@is_uploaded_file($_FILES['backlogo']['tmp_name'])) {
				$safename = JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['backlogo']['name'])));
				if (file_exists($res_backend_path.$safename)) {
					$j = 1;
					while (file_exists($res_backend_path.$j.$safename)) {
						$j++;
					}
					$pwhere = $res_backend_path.$j.$safename;
				} else {
					$j = "";
					$pwhere = $res_backend_path.$safename;
				}
				if (!getimagesize($_FILES['backlogo']['tmp_name'])) {
					@unlink($pwhere);
					$pbackicon = "";
				} else {
					VikRentItems::uploadFile($_FILES['backlogo']['tmp_name'], $pwhere);
					@chmod($pwhere, 0644);
					$pbackicon = $j.$safename;
				}
			}
			if (!empty($pbackicon)) {
				$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='backlogo';";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pbackicon)." WHERE `param`='backlogo';";
					$dbo->setQuery($q);
					$dbo->execute();
				} else {
					$q = "INSERT INTO `#__vikrentitems_config` (`param`,`setting`) VALUES ('backlogo',".$dbo->quote($pbackicon).");";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
		}
		if (empty($pallowrent) || $pallowrent!="1") {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='0' WHERE `param`='allowrent';";
		} else {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='1' WHERE `param`='allowrent';";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		if (empty($pplacesfront) || $pplacesfront!="yes") {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='0' WHERE `param`='placesfront';";
		} else {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='1' WHERE `param`='placesfront';";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		$session->set('showPlacesFront', '');
		if (empty($pshowcategories) || $pshowcategories!="yes") {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='0' WHERE `param`='showcategories';";
		} else {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='1' WHERE `param`='showcategories';";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		$session->set('showCategoriesFront', '');
		if (empty($ptokenform) || $ptokenform!="yes") {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='0' WHERE `param`='tokenform';";
		} else {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='1' WHERE `param`='tokenform';";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_texts` SET `setting`=".$dbo->quote($pfooterordmail)." WHERE `param`='footerordmail';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_texts` SET `setting`=".$dbo->quote($pdisabledrentmsg)." WHERE `param`='disabledrentmsg';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($padminemail)." WHERE `param`='adminemail';";
		$dbo->setQuery($q);
		$dbo->execute();
		//Sender email address
		$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='senderemail' LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($psenderemail)." WHERE `param`='senderemail';";
			$dbo->setQuery($q);
			$dbo->execute();
		} else {
			$q = "INSERT INTO `#__vikrentitems_config` (`param`,`setting`) VALUES ('senderemail',".$dbo->quote($psenderemail).");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		//
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$pmultilang."' WHERE `param`='multilang';";
		$dbo->setQuery($q);
		$dbo->execute();
		if (empty($pdateformat)) {
			$pdateformat="%d/%m/%Y";
		}
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pdateformat)." WHERE `param`='dateformat';";
		$dbo->setQuery($q);
		$dbo->execute();
		$session->set('getDateFormat', '');
		if (empty($ptimeformat)) {
			$ptimeformat="";
		}
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($ptimeformat)." WHERE `param`='timeformat';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pminuteslock)." WHERE `param`='minuteslock';";
		$dbo->setQuery($q);
		$dbo->execute();
		if (!empty($ptimeopenstorealw)) {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='' WHERE `param`='timeopenstore';";
		} else {
			$openingh=$ptimeopenstorefh * 3600;
			$openingm=$ptimeopenstorefm * 60;
			$openingts=$openingh + $openingm;
			$closingh=$ptimeopenstoreth * 3600;
			$closingm=$ptimeopenstoretm * 60;
			$closingts=$closingh + $closingm;
			if ($closingts <= $openingts) {
				$q = "UPDATE `#__vikrentitems_config` SET `setting`='' WHERE `param`='timeopenstore';";
			} else {
				$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$openingts."-".$closingts."' WHERE `param`='timeopenstore';";
			}
		}
		$dbo->setQuery($q);
		$dbo->execute();
		if (empty($pforcepickupt)) {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='' WHERE `param`='globpickupt';";
		} else {
			$tforcepick = intval($pforcepickupth) < 10 ? "0".(int)$pforcepickupth : $pforcepickupth;
			$tforcepick .= ':';
			$tforcepick .= intval($pforcepickuptm) < 10 ? "0".(int)$pforcepickuptm : $pforcepickuptm;
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$tforcepick."' WHERE `param`='globpickupt';";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		if (empty($pforcedropofft)) {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='' WHERE `param`='globdropofft';";
		} else {
			$tforcedrop = intval($pforcedropoffth) < 10 ? "0".(int)$pforcedropoffth : $pforcedropoffth;
			$tforcedrop .= ':';
			$tforcedrop .= intval($pforcedropofftm) < 10 ? "0".(int)$pforcedropofftm : $pforcedropofftm;
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$tforcedrop."' WHERE `param`='globdropofft';";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		if (!ctype_digit($phoursmorerentback)) {
			$phoursmorerentback="0";
		}
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$phoursmorerentback."' WHERE `param`='hoursmorerentback';";
		$dbo->setQuery($q);
		$dbo->execute();
		if (!ctype_digit($phoursmoreitemavail)) {
			$phoursmoreitemavail="0";
		}
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$phoursmoreitemavail."' WHERE `param`='hoursmoreitemavail';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$globalclosingdaystr."' WHERE `param`='globalclosingdays';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".($prequirelogin == "1" ? "1" : "0")."' WHERE `param`='requirelogin';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".(string)$pusefa."' WHERE `param`='usefa';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$ploadjquery."' WHERE `param`='loadjquery';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$pcalendar."' WHERE `param`='calendar';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$pehourschbasp."' WHERE `param`='ehourschbasp';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$penablecoupons."' WHERE `param`='enablecoupons';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$penablepin."' WHERE `param`='enablepin';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".(string)$ptodaybookings."' WHERE `param`='todaybookings';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".(string)$ppickondrop."' WHERE `param`='pickondrop';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$picalkey."' WHERE `param`='icalkey';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$psetdropdplus."' WHERE `param`='setdropdplus';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$pmindaysadvance."' WHERE `param`='mindaysadvance';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$maxdate_str."' WHERE `param`='maxdate';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$pcronkey."' WHERE `param`='cronkey';";
		$dbo->setQuery($q);
		$dbo->execute();
		//Google Maps API Key
		$pgmapskey = VikRequest::getString('gmapskey', '', 'request');
		$q = "SELECT * FROM `#__vikrentitems_config` WHERE `param`='gmapskey';";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pgmapskey)." WHERE `param`='gmapskey';";
			$dbo->setQuery($q);
			$dbo->execute();
		} else {
			$q = "INSERT INTO `#__vikrentitems_config` (`param`,`setting`) VALUES ('gmapskey', ".$dbo->quote($pgmapskey).");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		//
		$psendemailwhen = VikRequest::getInt('sendemailwhen', '', 'request');
		$psendemailwhen = $psendemailwhen > 1 ? 2 : 1;
		$pattachical = VikRequest::getInt('attachical', 0, 'request');
		$pattachical = $pattachical >= 0 && $pattachical <= 3 ? $pattachical : 1;
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($psendemailwhen)." WHERE `param`='emailsendwhen';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pattachical)." WHERE `param`='attachical';";
		$dbo->setQuery($q);
		$dbo->execute();

		// preferred countries ordering, or custom countries.
		$pref_countries = VikRequest::getVar('pref_countries', array());
		$cust_pref_countries = VikRequest::getString('cust_pref_countries', '', 'request');
		$pref_countries = !is_array($pref_countries) || empty($pref_countries[0]) ? VikRentItems::preferredCountriesOrdering() : $pref_countries;
		if (!empty($cust_pref_countries)) {
			$all_custom_prefcountries = array();
			$cust_pref_countries = explode(',', $cust_pref_countries);
			foreach ($cust_pref_countries as $cust_pref_country) {
				$cust_pref_country = trim(strtolower($cust_pref_country));
				if (empty($cust_pref_country) || strlen($cust_pref_country) != 2) {
					continue;
				}
				array_push($all_custom_prefcountries, $cust_pref_country);
			}
			if (count($all_custom_prefcountries)) {
				$pref_countries = $all_custom_prefcountries;
			}
		}
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=" . $dbo->quote(json_encode($pref_countries)) . " WHERE `param`='preferred_countries';";
		$dbo->setQuery($q);
		$dbo->execute();

		$pfronttitle = VikRequest::getString('fronttitle', '', 'request');
		$pshowfooter = VikRequest::getString('showfooter', '', 'request');
		$pintromain = VikRequest::getString('intromain', '', 'request', VIKREQUEST_ALLOWHTML);
		$pclosingmain = VikRequest::getString('closingmain', '', 'request', VIKREQUEST_ALLOWHTML);
		$pcurrencyname = VikRequest::getString('currencyname', '', 'request', VIKREQUEST_ALLOWHTML);
		$pcurrencysymb = VikRequest::getString('currencysymb', '', 'request', VIKREQUEST_ALLOWHTML);
		$pcurrencycodepp = VikRequest::getString('currencycodepp', '', 'request');
		$pnumdecimals = VikRequest::getString('numdecimals', '', 'request');
		$pnumdecimals = intval($pnumdecimals);
		$pdecseparator = VikRequest::getString('decseparator', '', 'request');
		$pdecseparator = empty($pdecseparator) ? '.' : $pdecseparator;
		$pthoseparator = VikRequest::getString('thoseparator', '', 'request');
		$numberformatstr = $pnumdecimals.':'.$pdecseparator.':'.$pthoseparator;
		$pshowpartlyreserved = VikRequest::getString('showpartlyreserved', '', 'request');
		$pshowpartlyreserved = $pshowpartlyreserved == "yes" ? 1 : 0;
		$pnumcalendars = VikRequest::getInt('numcalendars', '', 'request');
		$pnumcalendars = $pnumcalendars > -1 ? $pnumcalendars : 3;
		$pthumbswidth = VikRequest::getInt('thumbswidth', '', 'request');
		$pthumbswidth = $pthumbswidth > 0 ? $pthumbswidth : 200;
		$pfirstwday = VikRequest::getString('firstwday', '', 'request');
		$pfirstwday = intval($pfirstwday) >= 0 && intval($pfirstwday) <= 6 ? $pfirstwday : '0';
		//theme
		$ptheme = VikRequest::getString('theme', '', 'request');
		if (empty($ptheme) || $ptheme == 'default') {
			$ptheme = 'default';
		} else {
			$validtheme = false;
			$themes = glob(VRI_SITE_PATH.DS.'themes'.DS.'*');
			if (count($themes) > 0) {
				$strip = VRI_SITE_PATH.DS.'themes'.DS;
				foreach ($themes as $th) {
					if (is_dir($th)) {
						$tname = str_replace($strip, '', $th);
						if ($tname == $ptheme) {
							$validtheme = true;
							break;
						}
					}
				}
			}
			if ($validtheme == false) {
				$ptheme = 'default';
			}
		}
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($ptheme)." WHERE `param`='theme';";
		$dbo->setQuery($q);
		$dbo->execute();
		//
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pshowpartlyreserved)." WHERE `param`='showpartlyreserved';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pnumcalendars)." WHERE `param`='numcalendars';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pthumbswidth)." WHERE `param`='thumbswidth';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$pfirstwday."' WHERE `param`='firstwday';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_texts` SET `setting`=".$dbo->quote($pfronttitle)." WHERE `param`='fronttitle';";
		$dbo->setQuery($q);
		$dbo->execute();
		if (empty($pshowfooter) || $pshowfooter!="yes") {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='0' WHERE `param`='showfooter';";
		} else {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='1' WHERE `param`='showfooter';";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_texts` SET `setting`=".$dbo->quote($pintromain)." WHERE `param`='intromain';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_texts` SET `setting`=".$dbo->quote($pclosingmain)." WHERE `param`='closingmain';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pcurrencyname)." WHERE `param`='currencyname';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pcurrencysymb)." WHERE `param`='currencysymb';";
		$dbo->setQuery($q);
		$dbo->execute();
		$session->set('getCurrencySymb', '');
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pcurrencycodepp)." WHERE `param`='currencycodepp';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($numberformatstr)." WHERE `param`='numberformat';";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$pivainclusa = VikRequest::getString('ivainclusa', '', 'request');
		$pccpaypal = VikRequest::getString('ccpaypal', '', 'request');
		$ppaytotal = VikRequest::getString('paytotal', '', 'request');
		$ppayaccpercent = VikRequest::getString('payaccpercent', '', 'request');
		$ptypedeposit = VikRequest::getString('typedeposit', '', 'request');
		$ptypedeposit = $ptypedeposit == 'fixed' ? 'fixed' : 'pcent';
		$ppaymentname = VikRequest::getString('paymentname', '', 'request');
		if (empty($pivainclusa) || $pivainclusa!="yes") {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='0' WHERE `param`='ivainclusa';";
		} else {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='1' WHERE `param`='ivainclusa';";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		if (empty($ppaytotal) || $ppaytotal!="yes") {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='0' WHERE `param`='paytotal';";
		} else {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='1' WHERE `param`='paytotal';";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pccpaypal)." WHERE `param`='ccpaypal';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_texts` SET `setting`=".$dbo->quote($ppaymentname)." WHERE `param`='paymentname';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($ppayaccpercent)." WHERE `param`='payaccpercent';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($ptypedeposit)." WHERE `param`='typedeposit';";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$psendjutility = VikRequest::getString('sendjutility', '', 'request');
		$psendpdf = VikRequest::getString('sendpdf', '', 'request');
		$pallowstats = VikRequest::getString('allowstats', '', 'request');
		$psendmailstats = VikRequest::getString('sendmailstats', '', 'request');
		$pdisclaimer = VikRequest::getString('disclaimer', '', 'request', VIKREQUEST_ALLOWHTML);
		if (empty($psendjutility) || $psendjutility!="yes") {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='0' WHERE `param`='sendjutility';";
		} else {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='1' WHERE `param`='sendjutility';";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		if (empty($psendpdf) || $psendpdf!="yes") {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='0' WHERE `param`='sendpdf';";
		} else {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='1' WHERE `param`='sendpdf';";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		if (empty($pallowstats) || $pallowstats!="yes") {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='0' WHERE `param`='allowstats';";
		} else {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='1' WHERE `param`='allowstats';";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		if (empty($psendmailstats) || $psendmailstats!="yes") {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='0' WHERE `param`='sendmailstats';";
		} else {
			$q = "UPDATE `#__vikrentitems_config` SET `setting`='1' WHERE `param`='sendmailstats';";
		}
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_texts` SET `setting`=".$dbo->quote($pdisclaimer)." WHERE `param`='disclaimer';";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$pdeliverybaseaddress = VikRequest::getString('deliverybaseaddress', '', 'request');
		$pdeliverycalcunit = VikRequest::getString('deliverycalcunit', '', 'request');
		$pdeliverycalcunit = $pdeliverycalcunit == 'km' ? 'km' : 'miles';
		$pdeliverycostperunit = VikRequest::getFloat('deliverycostperunit', '', 'request');
		$pdeliverymaxcost = VikRequest::getFloat('deliverymaxcost', '', 'request');
		$pdeliverybaselat = VikRequest::getString('deliverybaselat', '', 'request');
		$pdeliverybaselng = VikRequest::getString('deliverybaselng', '', 'request');
		$pdeliverymaxunitdist = VikRequest::getFloat('deliverymaxunitdist', '', 'request');
		$pdeliveryrounddist = VikRequest::getString('deliveryrounddist', '', 'request');
		$pdeliveryrounddist = $pdeliveryrounddist == "1" ? "1" : "0";
		$pdeliveryroundcost = VikRequest::getString('deliveryroundcost', '', 'request');
		$pdeliveryroundcost = $pdeliveryroundcost == "1" ? "1" : "0";
		$pdeliveryperord = VikRequest::getInt('deliveryperord', 0, 'request');
		$pdeliveryperord = $pdeliveryperord > 0 ? 1 : 0;
		$pdeliveryperitunit = VikRequest::getInt('deliveryperitunit', 0, 'request');
		$pdeliveryperitunit = $pdeliveryperitunit > 0 ? 1 : 0;
		$pdeliverytaxid = VikRequest::getInt('deliverytaxid', 0, 'request');
		$pdeliverymapnotes = VikRequest::getString('deliverymapnotes', '', 'request', VIKREQUEST_ALLOWHTML);
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pdeliverybaseaddress)." WHERE `param`='deliverybaseaddress';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pdeliverycalcunit)." WHERE `param`='deliverycalcunit';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pdeliverycostperunit)." WHERE `param`='deliverycostperunit';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pdeliverymaxcost)." WHERE `param`='deliverymaxcost';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pdeliverybaselat)." WHERE `param`='deliverybaselat';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pdeliverybaselng)." WHERE `param`='deliverybaselng';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pdeliverymaxunitdist)." WHERE `param`='deliverymaxunitdist';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$pdeliveryrounddist."' WHERE `param`='deliveryrounddist';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$pdeliveryroundcost."' WHERE `param`='deliveryroundcost';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$pdeliveryperord."' WHERE `param`='deliveryperord';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$pdeliveryperitunit."' WHERE `param`='deliveryperitunit';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`='".$pdeliverytaxid."' WHERE `param`='deliverytaxid';";
		$dbo->setQuery($q);
		$dbo->execute();
		$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote($pdeliverymapnotes)." WHERE `param`='deliverymapnotes';";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$mainframe = JFactory::getApplication();
		$mainframe->enqueueMessage(JText::translate('VRSETTINGSAVED'));
		$mainframe->redirect("index.php?option=com_vikrentitems&task=config");
	}

	function renewsession() {
		/*
		 * @wponly
		 * We just destroy the session
		 */
		JSessionHandler::destroy();
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=config");
	}

	function locfees() {
		VikRentItemsHelper::printHeader("12");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'locfees'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newlocfee() {
		VikRentItemsHelper::printHeader("12");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managelocfee'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editlocfee() {
		VikRentItemsHelper::printHeader("12");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managelocfee'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createlocfee() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$pfrom = VikRequest::getInt('from', '', 'request');
		$pto = VikRequest::getInt('to', '', 'request');
		$pcost = VikRequest::getFloat('cost', '', 'request');
		$pdaily = VikRequest::getString('daily', '', 'request');
		$paliq = VikRequest::getInt('aliq', '', 'request');
		$pinvert = VikRequest::getString('invert', '', 'request');
		$pinvert = $pinvert == "1" ? 1 : 0;
		$pnightsoverrides = VikRequest::getVar('nightsoverrides', array());
		$pvaluesoverrides = VikRequest::getVar('valuesoverrides', array());
		if (!empty($pfrom) && !empty($pto)) {
			$losverridestr = "";
			if (count($pnightsoverrides) > 0 && count($pvaluesoverrides) > 0) {
				foreach ($pnightsoverrides as $ko => $no) {
					if (!empty($no) && strlen(trim($pvaluesoverrides[$ko])) > 0) {
						$losverridestr .= $no.':'.trim($pvaluesoverrides[$ko]).'_';
					}
				}
			}
			$q = "INSERT INTO `#__vikrentitems_locfees` (`from`,`to`,`daily`,`cost`,`idiva`,`invert`,`losoverride`) VALUES(".$dbo->quote($pfrom).", ".$dbo->quote($pto).", '".(intval($pdaily) == 1 ? "1" : "0")."', ".$dbo->quote($pcost).", ".$dbo->quote($paliq).", '".$pinvert."', '".$losverridestr."');";
			$dbo->setQuery($q);
			$dbo->execute();
			$mainframe->enqueueMessage(JText::translate('VRLOCFEESAVED'));
		}
		$mainframe->redirect("index.php?option=com_vikrentitems&task=locfees");
	}

	function updatelocfee() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$pwhere = VikRequest::getString('where', '', 'request');
		$pfrom = VikRequest::getInt('from', '', 'request');
		$pto = VikRequest::getInt('to', '', 'request');
		$pcost = VikRequest::getFloat('cost', '', 'request');
		$pdaily = VikRequest::getString('daily', '', 'request');
		$paliq = VikRequest::getInt('aliq', '', 'request');
		$pinvert = VikRequest::getString('invert', '', 'request');
		$pinvert = $pinvert == "1" ? 1 : 0;
		$pnightsoverrides = VikRequest::getVar('nightsoverrides', array());
		$pvaluesoverrides = VikRequest::getVar('valuesoverrides', array());
		if (!empty($pwhere) && !empty($pfrom) && !empty($pto)) {
			$losverridestr = "";
			if (count($pnightsoverrides) > 0 && count($pvaluesoverrides) > 0) {
				foreach ($pnightsoverrides as $ko => $no) {
					if (!empty($no) && strlen(trim($pvaluesoverrides[$ko])) > 0) {
						$losverridestr .= $no.':'.trim($pvaluesoverrides[$ko]).'_';
					}
				}
			}
			$q = "UPDATE `#__vikrentitems_locfees` SET `from`=".$dbo->quote($pfrom).",`to`=".$dbo->quote($pto).",`daily`='".(intval($pdaily) == 1 ? "1" : "0")."',`cost`=".$dbo->quote($pcost).",`idiva`=".$dbo->quote($paliq).",`invert`='".$pinvert."',`losoverride`='".$losverridestr."' WHERE `id`=".$dbo->quote($pwhere).";";
			$dbo->setQuery($q);
			$dbo->execute();
			$mainframe->enqueueMessage(JText::translate('VRLOCFEEUPDATE'));
		}
		$mainframe->redirect("index.php?option=com_vikrentitems&task=locfees");
	}

	function removelocfee() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "DELETE FROM `#__vikrentitems_locfees` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=locfees");
	}

	function cancellocfee() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=locfees");
	}

	function seasons() {
		VikRentItemsHelper::printHeader("13");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'seasons'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newseason() {
		VikRentItemsHelper::printHeader("13");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'manageseason'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editseason() {
		VikRentItemsHelper::printHeader("13");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'manageseason'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createseason() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$this->do_createseason();
	}

	function createseason_new() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$this->do_createseason(true);
	}

	private function do_createseason($andnew = false) {
		$mainframe = JFactory::getApplication();
		$pfrom = VikRequest::getString('from', '', 'request');
		$pto = VikRequest::getString('to', '', 'request');
		$ptype = VikRequest::getString('type', '', 'request');
		$pdiffcost = VikRequest::getFloat('diffcost', '', 'request');
		$pidlocation = VikRequest::getInt('idlocation', '', 'request');
		$piditems = VikRequest::getVar('iditems', array(0));
		$pidprices = VikRequest::getVar('idprices', array(0));
		$pwdays = VikRequest::getVar('wdays', array());
		$pspname = VikRequest::getString('spname', '', 'request');
		$ppickupincl = VikRequest::getString('pickupincl', '', 'request');
		$ppickupincl = $ppickupincl == 1 ? 1 : 0;
		$pkeepfirstdayrate = VikRequest::getString('keepfirstdayrate', '', 'request');
		$pkeepfirstdayrate = $pkeepfirstdayrate == 1 ? 1 : 0;
		$pval_pcent = VikRequest::getString('val_pcent', '', 'request');
		$pval_pcent = $pval_pcent == "1" ? 1 : 2;
		$proundmode = VikRequest::getString('roundmode', '', 'request');
		$proundmode = (!empty($proundmode) && in_array($proundmode, array('PHP_ROUND_HALF_UP', 'PHP_ROUND_HALF_DOWN')) ? $proundmode : '');
		$pyeartied = VikRequest::getString('yeartied', '', 'request');
		$pyeartied = $pyeartied == "1" ? 1 : 0;
		$tieyear = 0;
		$ppromo = VikRequest::getInt('promo', '', 'request');
		$ppromodaysadv = VikRequest::getInt('promodaysadv', '', 'request');
		$ppromotxt = VikRequest::getString('promotxt', '', 'request', VIKREQUEST_ALLOWHTML);
		$pnightsoverrides = VikRequest::getVar('nightsoverrides', array());
		$pvaluesoverrides = VikRequest::getVar('valuesoverrides', array());
		$pandmoreoverride = VikRequest::getVar('andmoreoverride', array());
		$ppromominlos = VikRequest::getInt('promominlos', '', 'request');
		$ppromolastmind = VikRequest::getInt('promolastmind', 0, 'request');
		$ppromolastminh = VikRequest::getInt('promolastminh', 0, 'request');
		$promolastmin = ($ppromolastmind * 86400) + ($ppromolastminh * 3600);
		$ppromofinalprice = VikRequest::getInt('promofinalprice', 0, 'request');
		$ppromofinalprice = $ppromo ? $ppromofinalprice : 0;
		$losverridestr = "";
		$dbo = JFactory::getDbo();
		if ((!empty($pfrom) && !empty($pto)) || count($pwdays) > 0) {
			$skipseason = false;
			if (empty($pfrom) || empty($pto)) {
				$skipseason = true;
			}
			$skipdays = false;
			$wdaystr = null;
			if (count($pwdays) == 0) {
				$skipdays = true;
			} else {
				$wdaystr = "";
				foreach ($pwdays as $wd) {
					$wdaystr .= $wd . ';';
				}
			}
			$itemstr = "";
			if (@count($piditems) > 0) {
				foreach ($piditems as $it) {
					$itemstr .= "-" . $it . "-,";
				}
			}
			$pricestr = "";
			if (@count($pidprices) > 0) {
				foreach ($pidprices as $price) {
					if (empty($price)) {
						continue;
					}
					$pricestr .= "-" . $price . "-,";
				}
			}
			$valid = true;
			$double_records = array();
			$sfrom = null;
			$sto = null;
			// value overrides
			if (count($pnightsoverrides) > 0 && count($pvaluesoverrides) > 0) {
				foreach ($pnightsoverrides as $ko => $no) {
					if (!empty($no) && strlen(trim($pvaluesoverrides[$ko])) > 0) {
						$infiniteclause = intval($pandmoreoverride[$ko]) == 1 ? '-i' : '';
						$losverridestr .= intval($no).$infiniteclause.':'.trim($pvaluesoverrides[$ko]).'_';
					}
				}
			}
			//
			if (!$skipseason) {
				$first = VikRentItems::getDateTimestamp($pfrom, 0, 0);
				$second = VikRentItems::getDateTimestamp($pto, 0, 0);
				if ($second > 0 && $second == $first) {
					$second += 86399;
				}
				if ($second > $first) {
					$baseone = getdate($first);
					$basets = mktime(0, 0, 0, 1, 1, $baseone['year']);
					$sfrom = $baseone[0] - $basets;
					$basetwo = getdate($second);
					$basets = mktime(0, 0, 0, 1, 1, $basetwo['year']);
					$sto = $basetwo[0] - $basets;
					//check leap year
					if ($baseone['year'] % 4 == 0 && ($baseone['year'] % 100 != 0 || $baseone['year'] % 400 == 0)) {
						$leapts = mktime(0, 0, 0, 2, 29, $baseone['year']);
						if ($baseone[0] > $leapts) {
							$sfrom -= 86400;
							/**
							 * To avoid issue with leap years and dates near Feb 29th, we only reduce the seconds if these were reduced
							 * for the from-date of the seasons. Doing it just for the to-date in 2019 for 2020 (leap) produced invalid results.
							 * 
							 * @since 	July 2nd 2019
							 */
							if ($basetwo['year'] % 4 == 0 && ($basetwo['year'] % 100 != 0 || $basetwo['year'] % 400 == 0)) {
								$leapts = mktime(0, 0, 0, 2, 29, $basetwo['year']);
								if ($basetwo[0] > $leapts) {
									$sto -= 86400;
								}
							}
						}
					}
					//end leap year
					//tied to the year
					if ($pyeartied == 1) {
						$tieyear = $baseone['year'];
					}
					//
					//check if seasons dates are valid
					$q = "SELECT `id`,`spname` FROM `#__vikrentitems_seasons` WHERE `from`<=".$dbo->quote($sfrom)." AND `to`>".$dbo->quote($sfrom)." AND `iditems`=".$dbo->quote($itemstr)." AND `locations`=".$dbo->quote($pidlocation)."".(!$skipdays ? " AND `wdays`='".$wdaystr."'" : "").($skipdays ? " AND (`from` > 0 OR `to` > 0) AND `wdays`=''" : "").($pyeartied == 1 ? " AND `year`=".$tieyear : " AND `year` IS NULL")." AND `idprices`=".$dbo->quote($pricestr)." AND `promo`=".$ppromo." AND `losoverride`=".$dbo->quote($losverridestr).";";
					$dbo->setQuery($q);
					$dbo->execute();
					$totfirst = $dbo->getNumRows();
					if ($totfirst > 0) {
						$valid = false;
						$similar = $dbo->loadAssocList();
						foreach ($similar as $sim) {
							$double_records[] = $sim['spname'];
						}
					}
					$q = "SELECT `id`,`spname` FROM `#__vikrentitems_seasons` WHERE `from`<=".$dbo->quote($sto)." AND `to`>=".$dbo->quote($sto)." AND `iditems`=".$dbo->quote($itemstr)." AND `locations`=".$dbo->quote($pidlocation)."".(!$skipdays ? " AND `wdays`='".$wdaystr."'" : "").($skipdays ? " AND (`from` > 0 OR `to` > 0) AND `wdays`=''" : "").($pyeartied == 1 ? " AND `year`=".$tieyear : " AND `year` IS NULL")." AND `idprices`=".$dbo->quote($pricestr)." AND `promo`=".$ppromo." AND `losoverride`=".$dbo->quote($losverridestr).";";
					$dbo->setQuery($q);
					$dbo->execute();
					$totsecond = $dbo->getNumRows();
					if ($totsecond > 0) {
						$valid = false;
						$similar = $dbo->loadAssocList();
						foreach ($similar as $sim) {
							$double_records[] = $sim['spname'];
						}
					}
					$q = "SELECT `id`,`spname` FROM `#__vikrentitems_seasons` WHERE `from`>=".$dbo->quote($sfrom)." AND `from`<=".$dbo->quote($sto)." AND `to`>=".$dbo->quote($sfrom)." AND `to`<=".$dbo->quote($sto)." AND `iditems`=".$dbo->quote($itemstr)." AND `locations`=".$dbo->quote($pidlocation)."".(!$skipdays ? " AND `wdays`='".$wdaystr."'" : "").($skipdays ? " AND (`from` > 0 OR `to` > 0) AND `wdays`=''" : "").($pyeartied == 1 ? " AND `year`=".$tieyear : " AND `year` IS NULL")." AND `idprices`=".$dbo->quote($pricestr)." AND `promo`=".$ppromo." AND `losoverride`=".$dbo->quote($losverridestr).";";
					$dbo->setQuery($q);
					$dbo->execute();
					$totthird = $dbo->getNumRows();
					if ($totthird > 0) {
						$valid = false;
						$similar = $dbo->loadAssocList();
						foreach ($similar as $sim) {
							$double_records[] = $sim['spname'];
						}
					}
					//
				} else {
					VikError::raiseWarning('', JText::translate('ERRINVDATESEASON'));
					$mainframe->redirect("index.php?option=com_vikrentitems&task=newseason");
				}
			}
			if ($valid || $ppromo === 1) {
				$q = "INSERT INTO `#__vikrentitems_seasons` (`type`,`from`,`to`,`diffcost`,`iditems`,`locations`,`spname`,`wdays`,`pickupincl`,`val_pcent`,`losoverride`,`keepfirstdayrate`,`roundmode`,`year`,`idprices`,`promo`,`promodaysadv`,`promotxt`,`promominlos`,`promolastmin`,`promofinalprice`) VALUES('".($ptype == "1" ? "1" : "2")."', ".$dbo->quote($sfrom).", ".$dbo->quote($sto).", ".$dbo->quote($pdiffcost).", ".$dbo->quote($itemstr).", ".$dbo->quote($pidlocation).", ".$dbo->quote($pspname).", ".$dbo->quote($wdaystr).", '".$ppickupincl."', '".$pval_pcent."', ".$dbo->quote($losverridestr).", '".$pkeepfirstdayrate."', ".(!empty($proundmode) ? "'".$proundmode."'" : "null").", ".($pyeartied == 1 ? $tieyear : "NULL").", ".$dbo->quote($pricestr).", ".($ppromo == 1 ? '1' : '0').", ".(!empty($ppromodaysadv) ? $ppromodaysadv : "null").", ".$dbo->quote($ppromotxt).", ".(!empty($ppromominlos) ? $ppromominlos : "0").", ".(int)$promolastmin.", {$ppromofinalprice});";
				$dbo->setQuery($q);
				$dbo->execute();
				$mainframe->enqueueMessage(JText::translate('VRSEASONSAVED'));
				$mainframe->redirect("index.php?option=com_vikrentitems&task=".($andnew ? 'newseason' : 'seasons'));
			} else {
				VikError::raiseWarning('', JText::translate('ERRINVDATEITEMSLOCSEASON').(count($double_records) > 0 ? ' ('.implode(', ', $double_records).')' : ''));
				$mainframe->redirect("index.php?option=com_vikrentitems&task=newseason");
			}
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=newseason");
		}
	}

	function updateseason() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$this->do_updateseason();
	}

	function updateseasonstay() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$this->do_updateseason(true);
	}

	function do_updateseason($stay = false) {
		$mainframe = JFactory::getApplication();
		$pwhere = VikRequest::getString('where', '', 'request');
		$pfrom = VikRequest::getString('from', '', 'request');
		$pto = VikRequest::getString('to', '', 'request');
		$ptype = VikRequest::getString('type', '', 'request');
		$pdiffcost = VikRequest::getFloat('diffcost', '', 'request');
		$pidlocation = VikRequest::getInt('idlocation', '', 'request');
		$piditems = VikRequest::getVar('iditems', array(0));
		$pidprices = VikRequest::getVar('idprices', array(0));
		$pwdays = VikRequest::getVar('wdays', array());
		$pspname = VikRequest::getString('spname', '', 'request');
		$ppickupincl = VikRequest::getString('pickupincl', '', 'request');
		$ppickupincl = $ppickupincl == 1 ? 1 : 0;
		$pkeepfirstdayrate = VikRequest::getString('keepfirstdayrate', '', 'request');
		$pkeepfirstdayrate = $pkeepfirstdayrate == 1 ? 1 : 0;
		$pval_pcent = VikRequest::getString('val_pcent', '', 'request');
		$pval_pcent = $pval_pcent == "1" ? 1 : 2;
		$proundmode = VikRequest::getString('roundmode', '', 'request');
		$proundmode = (!empty($proundmode) && in_array($proundmode, array('PHP_ROUND_HALF_UP', 'PHP_ROUND_HALF_DOWN')) ? $proundmode : '');
		$pyeartied = VikRequest::getString('yeartied', '', 'request');
		$pyeartied = $pyeartied == "1" ? 1 : 0;
		$tieyear = 0;
		$ppromo = VikRequest::getInt('promo', '', 'request');
		$ppromo = $ppromo == 1 ? 1 : 0;
		$ppromodaysadv = VikRequest::getInt('promodaysadv', '', 'request');
		$ppromotxt = VikRequest::getString('promotxt', '', 'request', VIKREQUEST_ALLOWHTML);
		$pnightsoverrides = VikRequest::getVar('nightsoverrides', array());
		$pvaluesoverrides = VikRequest::getVar('valuesoverrides', array());
		$pandmoreoverride = VikRequest::getVar('andmoreoverride', array());
		$ppromominlos = VikRequest::getInt('promominlos', '', 'request');
		$ppromolastmind = VikRequest::getInt('promolastmind', 0, 'request');
		$ppromolastminh = VikRequest::getInt('promolastminh', 0, 'request');
		$promolastmin = ($ppromolastmind * 86400) + ($ppromolastminh * 3600);
		$ppromofinalprice = VikRequest::getInt('promofinalprice', 0, 'request');
		$ppromofinalprice = $ppromo ? $ppromofinalprice : 0;
		$losverridestr = "";
		$dbo = JFactory::getDbo();
		if ((!empty($pfrom) && !empty($pto)) || count($pwdays) > 0) {
			$skipseason = false;
			if (empty($pfrom) || empty($pto)) {
				$skipseason = true;
			}
			$skipdays = false;
			$wdaystr = null;
			if (count($pwdays) == 0) {
				$skipdays = true;
			} else {
				$wdaystr = "";
				foreach ($pwdays as $wd) {
					$wdaystr .= $wd.';';
				}
			}
			$itemstr = "";
			if (@count($piditems) > 0) {
				foreach ($piditems as $it) {
					$itemstr .= "-" . $it . "-,";
				}
			}
			$pricestr = "";
			if (@count($pidprices) > 0) {
				foreach ($pidprices as $price) {
					if (empty($price)) {
						continue;
					}
					$pricestr .= "-" . $price . "-,";
				}
			}
			$valid = true;
			$double_records = array();
			$sfrom = null;
			$sto = null;
			// value overrides
			if (count($pnightsoverrides) > 0 && count($pvaluesoverrides) > 0) {
				foreach ($pnightsoverrides as $ko => $no) {
					if (!empty($no) && strlen(trim($pvaluesoverrides[$ko])) > 0) {
						$infiniteclause = intval($pandmoreoverride[$ko]) == 1 ? '-i' : '';
						$losverridestr .= intval($no).$infiniteclause.':'.trim($pvaluesoverrides[$ko]).'_';
					}
				}
			}
			//
			if (!$skipseason) {
				$first = VikRentItems::getDateTimestamp($pfrom, 0, 0);
				$second = VikRentItems::getDateTimestamp($pto, 0, 0);
				if ($second > 0 && $second == $first) {
					$second += 86399;
				}
				if ($second > $first) {
					$baseone = getdate($first);
					$basets = mktime(0, 0, 0, 1, 1, $baseone['year']);
					$sfrom = $baseone[0] - $basets;
					$basetwo = getdate($second);
					$basets = mktime(0, 0, 0, 1, 1, $basetwo['year']);
					$sto = $basetwo[0] - $basets;
					//check leap year
					if ($baseone['year'] % 4 == 0 && ($baseone['year'] % 100 != 0 || $baseone['year'] % 400 == 0)) {
						$leapts = mktime(0, 0, 0, 2, 29, $baseone['year']);
						if ($baseone[0] > $leapts) {
							$sfrom -= 86400;
							/**
							 * To avoid issue with leap years and dates near Feb 29th, we only reduce the seconds if these were reduced
							 * for the from-date of the seasons. Doing it just for the to-date in 2019 for 2020 (leap) produced invalid results.
							 * 
							 * @since 	July 2nd 2019
							 */
							if ($basetwo['year'] % 4 == 0 && ($basetwo['year'] % 100 != 0 || $basetwo['year'] % 400 == 0)) {
								$leapts = mktime(0, 0, 0, 2, 29, $basetwo['year']);
								if ($basetwo[0] > $leapts) {
									$sto -= 86400;
								}
							}
						}
					}
					//end leap year
					//tied to the year
					if ($pyeartied == 1) {
						$tieyear = $baseone['year'];
					}
					//
					//check if seasons dates are valid
					$q = "SELECT `id`,`spname` FROM `#__vikrentitems_seasons` WHERE `from`<=".$dbo->quote($sfrom)." AND `to`>=".$dbo->quote($sfrom)." AND `id`!=".$dbo->quote($pwhere)." AND `iditems`=".$dbo->quote($itemstr)." AND `locations`=".$dbo->quote($pidlocation)."".(!$skipdays ? " AND `wdays`='".$wdaystr."'" : "").($skipdays ? " AND (`from` > 0 OR `to` > 0) AND `wdays`=''" : "").($pyeartied == 1 ? " AND `year`=".$tieyear : " AND `year` IS NULL")." AND `idprices`=".$dbo->quote($pricestr)." AND `promo`=".$ppromo." AND `losoverride`=".$dbo->quote($losverridestr).";";
					$dbo->setQuery($q);
					$dbo->execute();
					$totfirst = $dbo->getNumRows();
					if ($totfirst > 0) {
						$valid = false;
						$similar = $dbo->loadAssocList();
						foreach ($similar as $sim) {
							$double_records[] = $sim['spname'];
						}
					}
					$q = "SELECT `id`,`spname` FROM `#__vikrentitems_seasons` WHERE `from`<=".$dbo->quote($sto)." AND `to`>=".$dbo->quote($sto)." AND `id`!=".$dbo->quote($pwhere)." AND `iditems`=".$dbo->quote($itemstr)." AND `locations`=".$dbo->quote($pidlocation)."".(!$skipdays ? " AND `wdays`='".$wdaystr."'" : "").($skipdays ? " AND (`from` > 0 OR `to` > 0) AND `wdays`=''" : "").($pyeartied == 1 ? " AND `year`=".$tieyear : " AND `year` IS NULL")." AND `idprices`=".$dbo->quote($pricestr)." AND `promo`=".$ppromo." AND `losoverride`=".$dbo->quote($losverridestr).";";
					$dbo->setQuery($q);
					$dbo->execute();
					$totsecond = $dbo->getNumRows();
					if ($totsecond > 0) {
						$valid = false;
						$similar = $dbo->loadAssocList();
						foreach ($similar as $sim) {
							$double_records[] = $sim['spname'];
						}
					}
					$q = "SELECT `id`,`spname` FROM `#__vikrentitems_seasons` WHERE `from`>=".$dbo->quote($sfrom)." AND `from`<=".$dbo->quote($sto)." AND `to`>=".$dbo->quote($sfrom)." AND `to`<=".$dbo->quote($sto)." AND `id`!=".$dbo->quote($pwhere)." AND `iditems`=".$dbo->quote($itemstr)." AND `locations`=".$dbo->quote($pidlocation)."".(!$skipdays ? " AND `wdays`='".$wdaystr."'" : "").($skipdays ? " AND (`from` > 0 OR `to` > 0) AND `wdays`=''" : "").($pyeartied == 1 ? " AND `year`=".$tieyear : " AND `year` IS NULL")." AND `idprices`=".$dbo->quote($pricestr)." AND `promo`=".$ppromo." AND `losoverride`=".$dbo->quote($losverridestr).";";
					$dbo->setQuery($q);
					$dbo->execute();
					$totthird = $dbo->getNumRows();
					if ($totthird > 0) {
						$valid = false;
						$similar = $dbo->loadAssocList();
						foreach ($similar as $sim) {
							$double_records[] = $sim['spname'];
						}
					}
					//
				} else {
					VikError::raiseWarning('', JText::translate('ERRINVDATESEASON'));
					$mainframe->redirect("index.php?option=com_vikrentitems&task=editseason&cid[]=".$pwhere);
				}
			}
			if ($valid) {
				$q = "UPDATE `#__vikrentitems_seasons` SET `type`='".($ptype == "1" ? "1" : "2")."',`from`=".$dbo->quote($sfrom).",`to`=".$dbo->quote($sto).",`diffcost`=".$dbo->quote($pdiffcost).",`iditems`=".$dbo->quote($itemstr).",`locations`=".$dbo->quote($pidlocation).",`spname`=".$dbo->quote($pspname).",`wdays`='".$wdaystr."',`pickupincl`='".$ppickupincl."',`val_pcent`='".$pval_pcent."',`losoverride`=".$dbo->quote($losverridestr).",`keepfirstdayrate`='".$pkeepfirstdayrate."',`roundmode`=".(!empty($proundmode) ? "'".$proundmode."'" : "null").",`year`=".($pyeartied == 1 ? $tieyear : "NULL").",`idprices`=".$dbo->quote($pricestr).",`promo`=".$ppromo.",`promodaysadv`=".(!empty($ppromodaysadv) ? $ppromodaysadv : "null").",`promotxt`=".$dbo->quote($ppromotxt).",`promominlos`=".(!empty($ppromominlos) ? $ppromominlos : "0").",`promolastmin`=".(int)$promolastmin.",`promofinalprice`={$ppromofinalprice} WHERE `id`=".$dbo->quote($pwhere).";";
				$dbo->setQuery($q);
				$dbo->execute();
				$mainframe->enqueueMessage(JText::translate('VRSEASONUPDATED'));
				if ($stay) {
					$mainframe->redirect("index.php?option=com_vikrentitems&task=editseason&cid[]=".$pwhere);
				} else {
					$mainframe->redirect("index.php?option=com_vikrentitems&task=seasons");
				}
			} else {
				VikError::raiseWarning('', JText::translate('ERRINVDATEITEMSLOCSEASON').(count($double_records) > 0 ? ' ('.implode(', ', $double_records).')' : ''));
				$mainframe->redirect("index.php?option=com_vikrentitems&task=editseason&cid[]=".$pwhere);
			}
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=editseason&cid[]=".$pwhere);
		}
	}

	function removeseasons() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		$piditem = VikRequest::getInt('iditem', '', 'request');
		$pwhere = VikRequest::getInt('where', '', 'request');
		if (!empty($pwhere)) {
			$ids = array($pwhere);
		}
		if (count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				if (empty($d)) {
					continue;
				}
				$q = "DELETE FROM `#__vikrentitems_seasons` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=seasons".(!empty($piditem) ? '&iditem='.$piditem : ''));
	}

	function cancelseason() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=seasons");
	}

	function payments() {
		VikRentItemsHelper::printHeader("14");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'payments'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newpayment() {
		VikRentItemsHelper::printHeader("14");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managepayment'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editpayment() {
		VikRentItemsHelper::printHeader("14");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managepayment'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createpayment() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$mainframe = JFactory::getApplication();
		$pname = VikRequest::getString('name', '', 'request');
		$ppayment = VikRequest::getString('payment', '', 'request');
		$ppublished = VikRequest::getString('published', '', 'request');
		$pcharge = VikRequest::getFloat('charge', '', 'request');
		$psetconfirmed = VikRequest::getString('setconfirmed', '', 'request');
		$pshownotealw = VikRequest::getString('shownotealw', '', 'request');
		$pnote = VikRequest::getString('note', '', 'request', VIKREQUEST_ALLOWHTML);
		$pval_pcent = VikRequest::getString('val_pcent', '', 'request');
		$pval_pcent = !in_array($pval_pcent, array('1', '2')) ? 1 : $pval_pcent;
		$pch_disc = VikRequest::getString('ch_disc', '', 'request');
		$pch_disc = !in_array($pch_disc, array('1', '2')) ? 1 : $pch_disc;
		$vikpaymentparams = VikRequest::getVar('vikpaymentparams', array(0));
		$payparamarr = array();
		$payparamstr = '';
		if (count($vikpaymentparams) > 0) {
			foreach ($vikpaymentparams as $setting => $cont) {
				if (strlen($setting) > 0) {
					$payparamarr[$setting] = $cont;
				}
			}
			if (count($payparamarr) > 0) {
				$payparamstr = json_encode($payparamarr);
			}
		}
		$dbo = JFactory::getDbo();
		if (!empty($pname) && !empty($ppayment)) {
			$setpub = $ppublished == "1" ? 1 : 0;
			$psetconfirmed = $psetconfirmed == "1" ? 1 : 0;
			$pshownotealw = $pshownotealw == "1" ? 1 : 0;
			$q = "SELECT `id` FROM `#__vikrentitems_gpayments` WHERE `file`=".$dbo->quote($ppayment).";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() >= 0) {
				$q = "INSERT INTO `#__vikrentitems_gpayments` (`name`,`file`,`published`,`note`,`charge`,`setconfirmed`,`shownotealw`,`val_pcent`,`ch_disc`,`params`) VALUES(".$dbo->quote($pname).",".$dbo->quote($ppayment).",".$dbo->quote($setpub).",".$dbo->quote($pnote).",".$dbo->quote($pcharge).",".$dbo->quote($psetconfirmed).",".$dbo->quote($pshownotealw).",'".$pval_pcent."','".$pch_disc."',".$dbo->quote($payparamstr).");";
				$dbo->setQuery($q);
				$dbo->execute();
				$mainframe->enqueueMessage(JText::translate('VRPAYMENTSAVED'));
				$mainframe->redirect("index.php?option=com_vikrentitems&task=payments");
			} else {
				VikError::raiseWarning('', JText::translate('ERRINVFILEPAYMENT'));
				$mainframe->redirect("index.php?option=com_vikrentitems&task=newpayment");
			}
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=newpayment");
		}
	}

	function updatepayment() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$mainframe = JFactory::getApplication();
		$pwhere = VikRequest::getString('where', '', 'request');
		$pname = VikRequest::getString('name', '', 'request');
		$ppayment = VikRequest::getString('payment', '', 'request');
		$ppublished = VikRequest::getString('published', '', 'request');
		$pcharge = VikRequest::getFloat('charge', '', 'request');
		$psetconfirmed = VikRequest::getString('setconfirmed', '', 'request');
		$pshownotealw = VikRequest::getString('shownotealw', '', 'request');
		$pnote = VikRequest::getString('note', '', 'request', VIKREQUEST_ALLOWHTML);
		$pval_pcent = VikRequest::getString('val_pcent', '', 'request');
		$pval_pcent = !in_array($pval_pcent, array('1', '2')) ? 1 : $pval_pcent;
		$pch_disc = VikRequest::getString('ch_disc', '', 'request');
		$pch_disc = !in_array($pch_disc, array('1', '2')) ? 1 : $pch_disc;
		$vikpaymentparams = VikRequest::getVar('vikpaymentparams', array(0));
		$payparamarr = array();
		$payparamstr = '';
		if (count($vikpaymentparams) > 0) {
			foreach ($vikpaymentparams as $setting => $cont) {
				if (strlen($setting) > 0) {
					$payparamarr[$setting] = $cont;
				}
			}
			if (count($payparamarr) > 0) {
				$payparamstr = json_encode($payparamarr);
			}
		}
		$dbo = JFactory::getDbo();
		if (!empty($pname) && !empty($ppayment) && !empty($pwhere)) {
			$setpub = $ppublished == "1" ? 1 : 0;
			$psetconfirmed = $psetconfirmed == "1" ? 1 : 0;
			$pshownotealw = $pshownotealw == "1" ? 1 : 0;
			$q = "SELECT `id` FROM `#__vikrentitems_gpayments` WHERE `file`=".$dbo->quote($ppayment)." AND `id`!='".$pwhere."';";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() >= 0) {
				$q = "UPDATE `#__vikrentitems_gpayments` SET `name`=".$dbo->quote($pname).",`file`=".$dbo->quote($ppayment).",`published`=".$dbo->quote($setpub).",`note`=".$dbo->quote($pnote).",`charge`=".$dbo->quote($pcharge).",`setconfirmed`=".$dbo->quote($psetconfirmed).",`shownotealw`=".$dbo->quote($pshownotealw).",`val_pcent`='".$pval_pcent."',`ch_disc`='".$pch_disc."',`params`=".$dbo->quote($payparamstr)." WHERE `id`=".$dbo->quote($pwhere).";";
				$dbo->setQuery($q);
				$dbo->execute();
				$mainframe->enqueueMessage(JText::translate('VRPAYMENTUPDATED'));
				$mainframe->redirect("index.php?option=com_vikrentitems&task=payments");
			} else {
				VikError::raiseWarning('', JText::translate('ERRINVFILEPAYMENT'));
				$mainframe->redirect("index.php?option=com_vikrentitems&task=editpayment&cid[]=".$pwhere);
			}
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=editpayment&cid[]=".$pwhere);
		}
	}

	function removepayments() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "DELETE FROM `#__vikrentitems_gpayments` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=payments");
	}

	function cancelpayment() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=payments");
	}

	function setordconfirmed() {
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$cid = VikRequest::getVar('cid', array(0));
		$oid = $cid[0];
		$q = "SELECT * FROM `#__vikrentitems_orders` WHERE `id`=".(int)$oid.";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() == 1) {
			$order = $dbo->loadAssocList();
			$vri_tn = VikRentItems::getTranslator();
			//check if the language in use is the same as the one used during the checkout
			if (!empty($order[0]['lang'])) {
				$lang = JFactory::getLanguage();
				if ($lang->getTag() != $order[0]['lang']) {
					$lang->load('com_vikrentitems', JPATH_ADMINISTRATOR, $order[0]['lang'], true);
					$vri_tn::$force_tolang = $order[0]['lang'];
				}
			}
			//
			$totdelivery = $order[0]['deliverycost'];
			$checkhourscharges = 0;
			$ppickup = $order[0]['ritiro'];
			$prelease = $order[0]['consegna'];
			$secdiff = $prelease - $ppickup;
			$daysdiff = $secdiff / 86400;
			if (is_int($daysdiff)) {
				if ($daysdiff < 1) {
					$daysdiff = 1;
				}
			} else {
				if ($daysdiff < 1) {
					$daysdiff = 1;
				} else {
					$sum = floor($daysdiff) * 86400;
					$newdiff = $secdiff - $sum;
					$maxhmore = VikRentItems::getHoursMoreRb() * 3600;
					if ($maxhmore >= $newdiff) {
						$daysdiff = floor($daysdiff);
					} else {
						$daysdiff = ceil($daysdiff);
						$ehours = intval(round(($newdiff - $maxhmore) / 3600));
						$checkhourscharges = $ehours;
						if ($checkhourscharges > 0) {
							$aehourschbasp = VikRentItems::applyExtraHoursChargesBasp();
						}
					}
				}
			}
			$realback = VikRentItems::getHoursItemAvail() * 3600;
			$realback += $order[0]['consegna'];
			$isdue = 0;
			$vricart = array();
			$allbook = true;
			$notavail = array();
			$q = "SELECT `oi`.*,`i`.`name`,`i`.`units` FROM `#__vikrentitems_ordersitems` AS `oi`,`#__vikrentitems_items` AS `i` WHERE `oi`.`idorder`='".$order[0]['id']."' AND `oi`.`iditem`=`i`.`id` ORDER BY `oi`.`id` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			$orderitems = $dbo->loadAssocList();
			$vri_tn->translateContents($orderitems, '#__vikrentitems_items', array('id' => 'iditem'));
			foreach ($orderitems as $koi => $oi) {
				if (!VikRentItems::itemBookable($oi['iditem'], $oi['units'], $order[0]['ritiro'], $order[0]['consegna'], $oi['itemquant'])) {
					$allbook = false;
					$notavail[] = $oi['name'];
				}
			}
			if (!$allbook) {
				VikError::raiseWarning('', JText::sprintf('ERRCONFORDERITEMNA', implode(", ", $notavail)));
			} else {
				$arrnewbusy = array();
				foreach ($orderitems as $koi => $oi) {
					for ($i = 1; $i <= $oi['itemquant']; $i++) {
						$q = "INSERT INTO `#__vikrentitems_busy` (`iditem`,`ritiro`,`consegna`,`realback`) VALUES('".$oi['iditem']."','".$order[0]['ritiro']."','".$order[0]['consegna']."','".$realback."');";
						$dbo->setQuery($q);
						$dbo->execute();
						$busynow = $dbo->insertid();
						$arrnewbusy[] = $busynow;
					}
					$kit_relations = VikRentItems::getKitRelatedItems($oi['iditem']);
					if (count($kit_relations)) {
						//VRI 1.5 - store busy records for the children or parent items, in case of a kit (Group/Set of Items)
						foreach ($kit_relations as $kit_rel) {
							for ($i = 1; $i <= $kit_rel['units']; $i++) {
								$q = "INSERT INTO `#__vikrentitems_busy` (`iditem`,`ritiro`,`consegna`,`realback`) VALUES(" . $dbo->quote($kit_rel['iditem']) . ", '" . $order[0]['ritiro'] . "', '" . $order[0]['consegna'] . "','" . $realback . "');";
								$dbo->setQuery($q);
								$dbo->execute();
								$busynow = $dbo->insertid();
								$arrnewbusy[] = $busynow;
							}
						}
						//
					}
				}
				foreach ($arrnewbusy as $newbusy) {
					$q = "INSERT INTO `#__vikrentitems_ordersbusy` (`idorder`,`idbusy`) VALUES('".$order[0]['id']."', '".$newbusy."');";
					$dbo->setQuery($q);
					$dbo->execute();
				}
				$q = "UPDATE `#__vikrentitems_orders` SET `status`='confirmed' WHERE `id`='".$order[0]['id']."';";
				$dbo->setQuery($q);
				$dbo->execute();
				$q = "DELETE FROM `#__vikrentitems_tmplock` WHERE `idorder`=".(int)$order[0]['id'].";";
				$dbo->setQuery($q);
				$dbo->execute();
				//send mail
				$ftitle = VikRentItems::getFrontTitle($vri_tn);
				$nowts = $order[0]['ts'];

				/**
				 * @wponly 	Rewrite URI for front-end
				 */
				$model 		= JModel::getInstance('vikrentitems', 'shortcodes');
				$itemid 	= $model->best('order');
				
				$viklink = VikRentItems::externalroute("index.php?option=com_vikrentitems&view=order&sid=" . $order[0]['sid'] . "&ts=" . $order[0]['ts'], false, ($itemid ? $itemid : null));
				//

				$ritplace = (!empty($order[0]['idplace']) ? VikRentItems::getPlaceName($order[0]['idplace'], $vri_tn) : "");
				$consegnaplace = (!empty($order[0]['idreturnplace']) ? VikRentItems::getPlaceName($order[0]['idreturnplace'], $vri_tn) : "");
				$maillocfee = "";
				$locfeewithouttax = 0;
				if (!empty($order[0]['idplace']) && !empty($order[0]['idreturnplace'])) {
					$locfee = VikRentItems::getLocFee($order[0]['idplace'], $order[0]['idreturnplace']);
					if ($locfee) {
						//VikRentItems 1.1 - Location fees overrides
						if (strlen($locfee['losoverride']) > 0) {
							$arrvaloverrides = array();
							$valovrparts = explode('_', $locfee['losoverride']);
							foreach ($valovrparts as $valovr) {
								if (!empty($valovr)) {
									$ovrinfo = explode(':', $valovr);
									$arrvaloverrides[$ovrinfo[0]] = $ovrinfo[1];
								}
							}
							if (array_key_exists($order[0]['days'], $arrvaloverrides)) {
								$locfee['cost'] = $arrvaloverrides[$order[0]['days']];
							}
						}
						//end VikRentItems 1.1 - Location fees overrides
						$locfeecost = intval($locfee['daily']) == 1 ? ($locfee['cost'] * $order[0]['days']) : $locfee['cost'];
						$locfeewith = VikRentItems::sayLocFeePlusIva($locfeecost, $locfee['idiva'], $order[0]);
						$isdue += $locfeewith;
						$locfeewithouttax = VikRentItems::sayLocFeeMinusIva($locfeecost, $locfee['idiva'], $order[0]);
						$maillocfee = $locfeewith;
					}
				}
				foreach ($orderitems as $koi => $oi) {
					$tar = array(array(
						'id' => 0,
						'iditem' => $oi['iditem'],
						'days' => $order[0]['days'],
						'idprice' => -1,
						'cost' => 0,
						'attrdata' => '',
					));
					$is_cust_cost = (!empty($oi['cust_cost']) && $oi['cust_cost'] > 0);
					if (!empty($oi['idtar'])) {
						if ($order[0]['hourly'] == 1) {
							$q = "SELECT * FROM `#__vikrentitems_dispcosthours` WHERE `id`=".(int)$oi['idtar'].";";
						} else {
							$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `id`=".(int)$oi['idtar'].";";
						}
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows() == 0) {
							if ($order[0]['hourly'] == 1) {
								$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `id`=".(int)$oi['idtar'].";";
								$dbo->setQuery($q);
								$dbo->execute();
								if ($dbo->getNumRows() == 1) {
									$tar = $dbo->loadAssocList();
								}
							}
						} else {
							$tar = $dbo->loadAssocList();
						}
					} elseif ($is_cust_cost) {
						//Custom Rate
						$tar = array(array(
							'id' => -1,
							'iditem' => $oi['iditem'],
							'days' => $order[0]['days'],
							'idprice' => -1,
							'cost' => $oi['cust_cost'],
							'attrdata' => '',
						));
					}
					if (count($tar) && !empty($tar[0]['id'])) {
						if ($order[0]['hourly'] == 1 && !empty($tar[0]['hours'])) {
							foreach ($tar as $kt => $vt) {
								$tar[$kt]['days'] = 1;
							}
						}
						if ($checkhourscharges > 0 && $aehourschbasp == true) {
							$ret = VikRentItems::applyExtraHoursChargesItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, false, true, true);
							$tar = $ret['return'];
							$calcdays = $ret['days'];
						}
						if ($checkhourscharges > 0 && $aehourschbasp == false) {
							$tar = VikRentItems::extraHoursSetPreviousFareItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, true);
							$tar = VikRentItems::applySeasonsItem($tar, $order[0]['ritiro'], $order[0]['consegna'], $order[0]['idplace']);
							$ret = VikRentItems::applyExtraHoursChargesItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, true, true, true);
							$tar = $ret['return'];
							$calcdays = $ret['days'];
						} else {
							$tar = VikRentItems::applySeasonsItem($tar, $order[0]['ritiro'], $order[0]['consegna'], $order[0]['idplace']);
						}
						$tar = VikRentItems::applyItemDiscounts($tar, $oi['iditem'], $oi['itemquant']);
					}
					$costplusiva = $is_cust_cost ? $tar[0]['cost'] : VikRentItems::sayCostPlusIva($tar[0]['cost'] * $oi['itemquant'], $tar[0]['idprice'], $order[0]);
					$costminusiva = $is_cust_cost ? VikRentItems::sayCustCostMinusIva($tar[0]['cost'], $oi['cust_idiva']) : VikRentItems::sayCostMinusIva($tar[0]['cost'] * $oi['itemquant'], $tar[0]['idprice'], $order[0]);
					$pricestr = ($is_cust_cost ? JText::translate('VRIRENTCUSTRATEPLAN').": ".$costplusiva : VikRentItems::getPriceName($tar[0]['idprice'], $vri_tn).": ".$costplusiva.(!empty($tar[0]['attrdata']) ? "\n".VikRentItems::getPriceAttr($tar[0]['idprice'], $vri_tn).": ".$tar[0]['attrdata'] : ""));
					$isdue += $is_cust_cost ? $tar[0]['cost'] : VikRentItems::sayCostPlusIva($tar[0]['cost'] * $oi['itemquant'], $tar[0]['idprice'], $order[0]);
					$optstr = "";
					$optarrtaxnet = array();
					if (!empty($oi['optionals'])) {
						$stepo = explode(";", $oi['optionals']);
						foreach ($stepo as $oo) {
							if (!empty($oo)) {
								$stept = explode(":", $oo);
								$q = "SELECT * FROM `#__vikrentitems_optionals` WHERE `id`='".intval($stept[0])."';";
								$dbo->setQuery($q);
								$dbo->execute();
								if ($dbo->getNumRows() == 1) {
									$actopt = $dbo->loadAssocList();
									$vri_tn->translateContents($actopt, '#__vikrentitems_optionals');
									$specvar = '';
									if (!empty($actopt[0]['specifications']) && strstr($stept[1], '-') != false) {
										$optspeccosts = VikRentItems::getOptionSpecIntervalsCosts($actopt[0]['specifications']);
										$optspecnames = VikRentItems::getOptionSpecIntervalsNames($actopt[0]['specifications']);
										$specstept = explode('-', $stept[1]);
										$stept[1] = $specstept[0];
										$specvar = $specstept[1];
										$actopt[0]['specintv'] = $specvar;
										$actopt[0]['name'] .= ' ('.$optspecnames[($specvar - 1)].')';
										$actopt[0]['quan'] = $stept[1];
										$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optspeccosts[($specvar - 1)]) * $order[0]['days'] * $stept[1]) : (floatval($optspeccosts[($specvar - 1)]) * $stept[1]));
									} else {
										$realcost = (intval($actopt[0]['perday'])==1 ? ($actopt[0]['cost'] * $order[0]['days'] * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
									}
									if (!empty($actopt[0]['maxprice']) && $actopt[0]['maxprice'] > 0 && $realcost > $actopt[0]['maxprice']) {
										$realcost = $actopt[0]['maxprice'];
										if (intval($actopt[0]['hmany']) == 1 && intval($stept[1]) > 1) {
											$realcost = $actopt[0]['maxprice'] * $stept[1];
										}
									}
									$opt_item_units = $actopt[0]['onceperitem'] ? 1 : $oi['itemquant'];
									$tmpopr = VikRentItems::sayOptionalsPlusIva($realcost * $opt_item_units, $actopt[0]['idiva'], $order[0]);
									$isdue += $tmpopr;
									$optnetprice = VikRentItems::sayOptionalsMinusIva($realcost * $opt_item_units, $actopt[0]['idiva'], $order[0]);
									$optarrtaxnet[] = $optnetprice;
									$optstr .= ($stept[1] > 1 ? $stept[1]." " : "").$actopt[0]['name'].": ".$tmpopr."\n";
								}
							}
						}
					}
					// VRI 1.6 - custom extra costs
					if (!empty($oi['extracosts'])) {
						$cur_extra_costs = json_decode($oi['extracosts'], true);
						foreach ($cur_extra_costs as $eck => $ecv) {
							$efee_cost = VikRentItems::sayOptionalsPlusIva($ecv['cost'], $ecv['idtax'], $order[0]);
							$isdue += $efee_cost;
							$efee_cost_without = VikRentItems::sayOptionalsMinusIva($ecv['cost'], $ecv['idtax'], $order[0]);
							$optarrtaxnet[] = $efee_cost_without;
							$optstr .= $ecv['name'].": ".$efee_cost."\n";
						}
					}
					//
					$arrayinfopdf = array('days' => $order[0]['days'], 'tarminusiva' => $costminusiva, 'tartax' => ($costplusiva - $costminusiva), 'opttaxnet' => $optarrtaxnet, 'locfeenet' => $locfeewithouttax);
					$vricart[$oi['iditem']][$koi]['itemquant'] = $oi['itemquant'];
					$vricart[$oi['iditem']][$koi]['info'] = VikRentItems::getItemInfo($oi['iditem'], $vri_tn);
					$vricart[$oi['iditem']][$koi]['pricestr'] = $pricestr;
					$vricart[$oi['iditem']][$koi]['optstr'] = $optstr;
					$vricart[$oi['iditem']][$koi]['optarrtaxnet'] = $optarrtaxnet;
					$vricart[$oi['iditem']][$koi]['infopdf'] = $arrayinfopdf;
					if (!empty($oi['timeslot'])) {
						$vricart[$oi['iditem']][$koi]['timeslot']['name'] = $oi['timeslot'];
					}
					if (!empty($oi['deliveryaddr'])) {
						$vricart[$oi['iditem']][$koi]['delivery']['vrideliveryaddress'] = $oi['deliveryaddr'];
						$vricart[$oi['iditem']][$koi]['delivery']['vrideliverydistance'] = $oi['deliverydist'];
					}
				}
				//delivery service
				if ($totdelivery > 0) {
					$isdue += $totdelivery;
				}
				//
				//vikrentitems 1.1 coupon
				$usedcoupon = false;
				$origisdue = $isdue;
				if (strlen($order[0]['coupon']) > 0) {
					$usedcoupon = true;
					$expcoupon = explode(";", $order[0]['coupon']);
					$isdue = $isdue - $expcoupon[1];
				}
				//
				$mainframe->enqueueMessage(JText::translate('VRORDERSETASCONF'));

				VikRentItems::sendOrderEmail($order[0]['id'], array('customer'));

				// VikRentItems::sendCustMailFromBack($order[0]['custmail'], strip_tags($ftitle)." ".JText::translate('VRRENTALORD'), $ftitle, $nowts, $order[0]['custdata'], $vricart, $order[0]['ritiro'], $order[0]['consegna'], $isdue, $viklink, JText::translate('VRIOMPLETED'), $ritplace, $consegnaplace, $maillocfee, $order[0]['id'], $order[0]['coupon'], true, $totdelivery);
			}
		}
		$mainframe->redirect("index.php?option=com_vikrentitems&task=editorder&cid[]=".$oid);
	}

	function overv() {
		VikRentItemsHelper::printHeader("15");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'overv'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function canceloverv() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=overv");
	}

	function cancelbusy() {
		$pidorder = VikRequest::getString('idorder', '', 'request');
		$pgoto = VikRequest::getString('goto', '', 'request', VIKREQUEST_ALLOWRAW);
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=editorder&cid[]=".$pidorder.($pgoto == 'overv' ? '&goto=overv' : ''));
	}

	function customf() {
		VikRentItemsHelper::printHeader("16");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'customf'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newcustomf() {
		VikRentItemsHelper::printHeader("16");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managecustomf'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editcustomf() {
		VikRentItemsHelper::printHeader("16");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managecustomf'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createcustomf() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$pname = VikRequest::getString('name', '', 'request', VIKREQUEST_ALLOWHTML);
		$ptype = VikRequest::getString('type', '', 'request');
		$pchoose = VikRequest::getVar('choose', array(0));
		$prequired = VikRequest::getString('required', '', 'request');
		$prequired = $prequired == "1" ? 1 : 0;
		$pflag = VikRequest::getString('flag', '', 'request');
		$pisemail = $pflag == 'isemail' ? 1 : 0;
		$pisnominative = $pflag == 'isnominative' && $ptype == 'text' ? 1 : 0;
		$pisphone = $pflag == 'isphone' && $ptype == 'text' ? 1 : 0;
		$pisaddress = $pflag == 'isaddress' && $ptype == 'text' ? 1 : 0;
		$piscity = $pflag == 'iscity' && $ptype == 'text' ? 1 : 0;
		$piszip = $pflag == 'iszip' && $ptype == 'text' ? 1 : 0;
		$piscompany = $pflag == 'iscompany' && $ptype == 'text' ? 1 : 0;
		$pisvat = $pflag == 'isvat' && $ptype == 'text' ? 1 : 0;
		$fieldflag = '';
		if ($pisaddress == 1) {
			$fieldflag = 'address';
		} elseif ($piscity == 1) {
			$fieldflag = 'city';
		} elseif ($piszip == 1) {
			$fieldflag = 'zip';
		} elseif ($piscompany == 1) {
			$fieldflag = 'company';
		} elseif ($pisvat == 1) {
			$fieldflag = 'vat';
		}
		$ppoplink = VikRequest::getString('poplink', '', 'request');
		$choosestr = "";
		if (@count($pchoose) > 0) {
			foreach ($pchoose as $ch) {
				if (!empty($ch)) {
					$choosestr .= $ch.";;__;;";
				}
			}
		}
		$dbo = JFactory::getDbo();
		$q = "SELECT `ordering` FROM `#__vikrentitems_custfields` ORDER BY `#__vikrentitems_custfields`.`ordering` DESC LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() == 1) {
			$getlast = $dbo->loadResult();
			$newsortnum = $getlast + 1;
		} else {
			$newsortnum = 1;
		}
		$q = "INSERT INTO `#__vikrentitems_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`,`flag`) VALUES(".$dbo->quote($pname).", ".$dbo->quote($ptype).", ".$dbo->quote($choosestr).", ".$dbo->quote($prequired).", ".$dbo->quote($newsortnum).", ".$dbo->quote($pisemail).", ".$dbo->quote($ppoplink).", ".$pisnominative.", ".$pisphone.", ".$dbo->quote($fieldflag).");";
		$dbo->setQuery($q);
		$dbo->execute();
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=customf");
	}

	function updatecustomf() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$pname = VikRequest::getString('name', '', 'request', VIKREQUEST_ALLOWHTML);
		$ptype = VikRequest::getString('type', '', 'request');
		$pchoose = VikRequest::getVar('choose', array(0));
		$prequired = VikRequest::getString('required', '', 'request');
		$prequired = $prequired == "1" ? 1 : 0;
		$pflag = VikRequest::getString('flag', '', 'request');
		$pisemail = $pflag == 'isemail' ? 1 : 0;
		$pisnominative = $pflag == 'isnominative' && $ptype == 'text' ? 1 : 0;
		$pisphone = $pflag == 'isphone' && $ptype == 'text' ? 1 : 0;
		$pisaddress = $pflag == 'isaddress' && $ptype == 'text' ? 1 : 0;
		$piscity = $pflag == 'iscity' && $ptype == 'text' ? 1 : 0;
		$piszip = $pflag == 'iszip' && $ptype == 'text' ? 1 : 0;
		$piscompany = $pflag == 'iscompany' && $ptype == 'text' ? 1 : 0;
		$pisvat = $pflag == 'isvat' && $ptype == 'text' ? 1 : 0;
		$fieldflag = '';
		if ($pisaddress == 1) {
			$fieldflag = 'address';
		} elseif ($piscity == 1) {
			$fieldflag = 'city';
		} elseif ($piszip == 1) {
			$fieldflag = 'zip';
		} elseif ($piscompany == 1) {
			$fieldflag = 'company';
		} elseif ($pisvat == 1) {
			$fieldflag = 'vat';
		}
		$ppoplink = VikRequest::getString('poplink', '', 'request');
		$pwhere = VikRequest::getInt('where', '', 'request');
		$choosestr = "";
		if (@count($pchoose) > 0) {
			foreach ($pchoose as $ch) {
				if (!empty($ch)) {
					$choosestr .= $ch.";;__;;";
				}
			}
		}
		$dbo = JFactory::getDbo();
		$q = "UPDATE `#__vikrentitems_custfields` SET `name`=".$dbo->quote($pname).",`type`=".$dbo->quote($ptype).",`choose`=".$dbo->quote($choosestr).",`required`=".$dbo->quote($prequired).",`isemail`=".$dbo->quote($pisemail).",`poplink`=".$dbo->quote($ppoplink).",`isnominative`=".$pisnominative.",`isphone`=".$pisphone.",`flag`=".$dbo->quote($fieldflag)." WHERE `id`=".$dbo->quote($pwhere).";";
		$dbo->setQuery($q);
		$dbo->execute();
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=customf");
	}

	function removecustomf() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "DELETE FROM `#__vikrentitems_custfields` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=customf");
	}

	function cancelcustomf() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=customf");
	}

	function sortfield() {
		$sortid = VikRequest::getVar('cid', array(0));
		$pmode = VikRequest::getString('mode', '', 'request');
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		if (!empty($pmode)) {
			$q = "SELECT `id`,`ordering` FROM `#__vikrentitems_custfields` ORDER BY `#__vikrentitems_custfields`.`ordering` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			$totr=$dbo->getNumRows();
			if ($totr > 1) {
				$data = $dbo->loadAssocList();
				if ($pmode=="up") {
					foreach ($data as $v){
						if ($v['id']==$sortid[0]) {
							$y=$v['ordering'];
						}
					}
					if ($y && $y > 1) {
						$vik=$y - 1;
						$found=false;
						foreach ($data as $v){
							if (intval($v['ordering'])==intval($vik)) {
								$found=true;
								$q = "UPDATE `#__vikrentitems_custfields` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								$q = "UPDATE `#__vikrentitems_custfields` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								break;
							}
						}
						if (!$found) {
							$q = "UPDATE `#__vikrentitems_custfields` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
				} elseif ($pmode=="down") {
					foreach ($data as $v){
						if ($v['id']==$sortid[0]) {
							$y=$v['ordering'];
						}
					}
					if ($y) {
						$vik=$y + 1;
						$found=false;
						foreach ($data as $v){
							if (intval($v['ordering'])==intval($vik)) {
								$found=true;
								$q = "UPDATE `#__vikrentitems_custfields` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								$q = "UPDATE `#__vikrentitems_custfields` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								break;
							}
						}
						if (!$found) {
							$q = "UPDATE `#__vikrentitems_custfields` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
				}
			}
			$mainframe->redirect("index.php?option=com_vikrentitems&task=customf");
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems");
		}
	}

	function removemoreimgs() {
		$mainframe = JFactory::getApplication();
		$dbo = JFactory::getDbo();
		$pelemid = VikRequest::getInt('elemid', '', 'request');
		$pimgind = VikRequest::getInt('imgind', '', 'request');
		if (!empty($pelemid) && strlen($pimgind) > 0) {
			$q = "SELECT `moreimgs` FROM `#__vikrentitems_items` WHERE `id`='".$pelemid."';";
			$dbo->setQuery($q);
			$dbo->execute();
			$actmore=$dbo->loadResult();
			if (strlen($actmore) > 0) {
				$actsplit = explode(';;', $actmore);
				if (array_key_exists($pimgind, $actsplit)) {
					@unlink(VRI_ADMIN_PATH.DS.'resources'.DS.'big_'.$actsplit[$pimgind]);
					@unlink(VRI_ADMIN_PATH.DS.'resources'.DS.'thumb_'.$actsplit[$pimgind]);
					unset($actsplit[$pimgind]);
					$newstr = "";
					foreach ($actsplit as $oi) {
						if (!empty($oi)) {
							$newstr.=$oi.';;';
						}
					}
					$q = "UPDATE `#__vikrentitems_items` SET `moreimgs`=".$dbo->quote($newstr)." WHERE `id`='".$pelemid."';";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
			$mainframe->redirect("index.php?option=com_vikrentitems&task=edititem&cid[]=".$pelemid);
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems");
		}
	}

	function coupons() {
		VikRentItemsHelper::printHeader("17");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'coupons'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newcoupon() {
		VikRentItemsHelper::printHeader("17");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managecoupon'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editcoupon() {
		VikRentItemsHelper::printHeader("17");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managecoupon'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createcoupon() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$mainframe = JFactory::getApplication();
		$pcode = VikRequest::getString('code', '', 'request');
		$pvalue = VikRequest::getFloat('value', '', 'request');
		$pfrom = VikRequest::getString('from', '', 'request');
		$pto = VikRequest::getString('to', '', 'request');
		$piditems = VikRequest::getVar('iditems', array(0));
		$ptype = VikRequest::getString('type', '', 'request');
		$ptype = $ptype == "1" ? 1 : 2;
		$ppercentot = VikRequest::getString('percentot', '', 'request');
		$ppercentot = $ppercentot == "1" ? 1 : 2;
		$pallvehicles = VikRequest::getString('allvehicles', '', 'request');
		$pallvehicles = $pallvehicles == "1" ? 1 : 0;
		$pmintotord = VikRequest::getFloat('mintotord', '', 'request');
		$striditems = "";
		if (@count($piditems) > 0 && $pallvehicles != 1) {
			foreach ($piditems as $ch) {
				if (!empty($ch)) {
					$striditems .= ";".$ch.";";
				}
			}
		}
		$strdatevalid = "";
		if (strlen($pfrom) > 0 && strlen($pto) > 0) {
			$first = VikRentItems::getDateTimestamp($pfrom, 0, 0);
			$second = VikRentItems::getDateTimestamp($pto, 0, 0);
			if ($first < $second) {
				$strdatevalid .= $first."-".$second;
			}
		}
		$dbo = JFactory::getDbo();
		$q = "SELECT * FROM `#__vikrentitems_coupons` WHERE `code`=".$dbo->quote($pcode).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			VikError::raiseWarning('', JText::translate('VRICOUPONEXISTS'));
		} else {
			$mainframe->enqueueMessage(JText::translate('VRICOUPONSAVEOK'));
			$q = "INSERT INTO `#__vikrentitems_coupons` (`code`,`type`,`percentot`,`value`,`datevalid`,`allvehicles`,`iditems`,`mintotord`) VALUES(".$dbo->quote($pcode).",'".$ptype."','".$ppercentot."',".$dbo->quote($pvalue).",'".$strdatevalid."','".$pallvehicles."','".$striditems."', ".$dbo->quote($pmintotord).");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		$mainframe->redirect("index.php?option=com_vikrentitems&task=coupons");
	}

	function updatecoupon() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$mainframe = JFactory::getApplication();
		$pcode = VikRequest::getString('code', '', 'request');
		$pvalue = VikRequest::getFloat('value', '', 'request');
		$pfrom = VikRequest::getString('from', '', 'request');
		$pto = VikRequest::getString('to', '', 'request');
		$piditems = VikRequest::getVar('iditems', array(0));
		$pwhere = VikRequest::getString('where', '', 'request');
		$ptype = VikRequest::getString('type', '', 'request');
		$ptype = $ptype == "1" ? 1 : 2;
		$ppercentot = VikRequest::getString('percentot', '', 'request');
		$ppercentot = $ppercentot == "1" ? 1 : 2;
		$pallvehicles = VikRequest::getString('allvehicles', '', 'request');
		$pallvehicles = $pallvehicles == "1" ? 1 : 0;
		$pmintotord = VikRequest::getFloat('mintotord', '', 'request');
		$striditems = "";
		if (@count($piditems) > 0 && $pallvehicles != 1) {
			foreach ($piditems as $ch) {
				if (!empty($ch)) {
					$striditems .= ";".$ch.";";
				}
			}
		}
		$strdatevalid = "";
		if (strlen($pfrom) > 0 && strlen($pto) > 0) {
			$first = VikRentItems::getDateTimestamp($pfrom, 0, 0);
			$second = VikRentItems::getDateTimestamp($pto, 0, 0);
			if ($first < $second) {
				$strdatevalid .= $first."-".$second;
			}
		}
		$dbo = JFactory::getDbo();
		$q = "SELECT * FROM `#__vikrentitems_coupons` WHERE `code`=".$dbo->quote($pcode)." AND `id`!='".$pwhere."';";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			VikError::raiseWarning('', JText::translate('VRICOUPONEXISTS'));
		} else {
			$mainframe->enqueueMessage(JText::translate('VRICOUPONSAVEOK'));
			$q = "UPDATE `#__vikrentitems_coupons` SET `code`=".$dbo->quote($pcode).",`type`='".$ptype."',`percentot`='".$ppercentot."',`value`=".$dbo->quote($pvalue).",`datevalid`='".$strdatevalid."',`allvehicles`='".$pallvehicles."',`iditems`='".$striditems."',`mintotord`=".$dbo->quote($pmintotord)." WHERE `id`='".$pwhere."';";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		$mainframe->redirect("index.php?option=com_vikrentitems&task=coupons");
	}

	function removecoupons() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "DELETE FROM `#__vikrentitems_coupons` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=coupons");
	}

	function cancelcoupon() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=coupons");
	}

	function resendordemail() {
		$cid = VikRequest::getVar('cid', array(0));
		$oid = (int)$cid[0];
		$this->do_resendordemail($oid);
	}

	private function do_resendordemail($oid, $checkdbsendpdf = false) {
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$q = "SELECT * FROM `#__vikrentitems_orders` WHERE `id`=".(int)$oid.";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() == 1) {
			$order = $dbo->loadAssocList();
			$vri_tn = VikRentItems::getTranslator();
			//check if the language in use is the same as the one used during the checkout
			if (!empty($order[0]['lang'])) {
				$lang = JFactory::getLanguage();
				if ($lang->getTag() != $order[0]['lang']) {
					$lang->load('com_vikrentitems', JPATH_ADMINISTRATOR, $order[0]['lang'], true);
					$vri_tn::$force_tolang = $order[0]['lang'];
				}
			}
			//
			$totdelivery = $order[0]['deliverycost'];
			$checkhourscharges = 0;
			$ppickup = $order[0]['ritiro'];
			$prelease = $order[0]['consegna'];
			$secdiff = $prelease - $ppickup;
			$daysdiff = $secdiff / 86400;
			if (is_int($daysdiff)) {
				if ($daysdiff < 1) {
					$daysdiff = 1;
				}
			} else {
				if ($daysdiff < 1) {
					$daysdiff = 1;
				} else {
					$sum = floor($daysdiff) * 86400;
					$newdiff = $secdiff - $sum;
					$maxhmore = VikRentItems::getHoursMoreRb() * 3600;
					if ($maxhmore >= $newdiff) {
						$daysdiff = floor($daysdiff);
					} else {
						$daysdiff = ceil($daysdiff);
						$ehours = intval(round(($newdiff - $maxhmore) / 3600));
						$checkhourscharges = $ehours;
						if ($checkhourscharges > 0) {
							$aehourschbasp = VikRentItems::applyExtraHoursChargesBasp();
						}
					}
				}
			}
			//send mail
			$ftitle = VikRentItems::getFrontTitle($vri_tn);
			$nowts = $order[0]['ts'];

			/**
			 * @wponly 	Rewrite URI for front-end
			 */
			$model 		= JModel::getInstance('vikrentitems', 'shortcodes');
			$itemid 	= $model->best('order');
			
			$viklink = VikRentItems::externalroute("index.php?option=com_vikrentitems&view=order&sid=".$order[0]['sid']."&ts=".$order[0]['ts'], false, ($itemid ? $itemid : null));
			//

			$ritplace = (!empty($order[0]['idplace']) ? VikRentItems::getPlaceName($order[0]['idplace'], $vri_tn) : "");
			$consegnaplace=(!empty($order[0]['idreturnplace']) ? VikRentItems::getPlaceName($order[0]['idreturnplace'], $vri_tn) : "");
			$isdue = 0;
			$vricart = array();
			$q = "SELECT `oi`.*,`i`.`name`,`i`.`units` FROM `#__vikrentitems_ordersitems` AS `oi`,`#__vikrentitems_items` AS `i` WHERE `oi`.`idorder`='".$order[0]['id']."' AND `oi`.`iditem`=`i`.`id` ORDER BY `oi`.`id` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			$orderitems = $dbo->loadAssocList();
			$vri_tn->translateContents($orderitems, '#__vikrentitems_items', array('id' => 'iditem'));
			$maillocfee = "";
			$locfeewithouttax = 0;
			if (!empty($order[0]['idplace']) && !empty($order[0]['idreturnplace'])) {
				$locfee = VikRentItems::getLocFee($order[0]['idplace'], $order[0]['idreturnplace']);
				if ($locfee) {
					//VikRentItems 1.1 - Location fees overrides
					if (strlen($locfee['losoverride']) > 0) {
						$arrvaloverrides = array();
						$valovrparts = explode('_', $locfee['losoverride']);
						foreach ($valovrparts as $valovr) {
							if (!empty($valovr)) {
								$ovrinfo = explode(':', $valovr);
								$arrvaloverrides[$ovrinfo[0]] = $ovrinfo[1];
							}
						}
						if (array_key_exists($order[0]['days'], $arrvaloverrides)) {
							$locfee['cost'] = $arrvaloverrides[$order[0]['days']];
						}
					}
					//end VikRentItems 1.1 - Location fees overrides
					$locfeecost = intval($locfee['daily']) == 1 ? ($locfee['cost'] * $order[0]['days']) : $locfee['cost'];
					$locfeewith = VikRentItems::sayLocFeePlusIva($locfeecost, $locfee['idiva'], $order[0]);
					$isdue += $locfeewith;
					$locfeewithouttax = VikRentItems::sayLocFeeMinusIva($locfeecost, $locfee['idiva'], $order[0]);
					$maillocfee = $locfeewith;
				}
			}
			foreach ($orderitems as $koi => $oi) {
				$tar = array(array(
					'id' => 0,
					'iditem' => $oi['iditem'],
					'days' => $order[0]['days'],
					'idprice' => -1,
					'cost' => 0,
					'attrdata' => '',
				));
				$is_cust_cost = (!empty($oi['cust_cost']) && $oi['cust_cost'] > 0);
				if (!empty($oi['idtar'])) {
					if ($order[0]['hourly'] == 1) {
						$q = "SELECT * FROM `#__vikrentitems_dispcosthours` WHERE `id`=".(int)$oi['idtar'].";";
					} else {
						$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `id`=".(int)$oi['idtar'].";";
					}
					$dbo->setQuery($q);
					$dbo->execute();
					if ($dbo->getNumRows() == 0) {
						if ($order[0]['hourly'] == 1) {
							$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `id`=".(int)$oi['idtar'].";";
							$dbo->setQuery($q);
							$dbo->execute();
							if ($dbo->getNumRows() == 1) {
								$tar = $dbo->loadAssocList();
							}
						}
					} else {
						$tar = $dbo->loadAssocList();
					}
				} elseif ($is_cust_cost) {
					//Custom Rate
					$tar = array(array(
						'id' => -1,
						'iditem' => $oi['iditem'],
						'days' => $order[0]['days'],
						'idprice' => -1,
						'cost' => $oi['cust_cost'],
						'attrdata' => '',
					));
				}
				if (count($tar) && !empty($tar[0]['id'])) {
					if ($order[0]['hourly'] == 1 && !empty($tar[0]['hours'])) {
						foreach ($tar as $kt => $vt) {
							$tar[$kt]['days'] = 1;
						}
					}
					if ($checkhourscharges > 0 && $aehourschbasp == true) {
						$ret = VikRentItems::applyExtraHoursChargesItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, false, true, true);
						$tar = $ret['return'];
						$calcdays = $ret['days'];
					}
					if ($checkhourscharges > 0 && $aehourschbasp == false) {
						$tar = VikRentItems::extraHoursSetPreviousFareItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, true);
						$tar = VikRentItems::applySeasonsItem($tar, $order[0]['ritiro'], $order[0]['consegna'], $order[0]['idplace']);
						$ret = VikRentItems::applyExtraHoursChargesItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, true, true, true);
						$tar = $ret['return'];
						$calcdays = $ret['days'];
					} else {
						$tar = VikRentItems::applySeasonsItem($tar, $order[0]['ritiro'], $order[0]['consegna'], $order[0]['idplace']);
					}
					$tar = VikRentItems::applyItemDiscounts($tar, $oi['iditem'], $oi['itemquant']);
				}
				$costplusiva = $is_cust_cost ? $tar[0]['cost'] : VikRentItems::sayCostPlusIva($tar[0]['cost'] * $oi['itemquant'], $tar[0]['idprice'], $order[0]);
				$costminusiva = $is_cust_cost ? VikRentItems::sayCustCostMinusIva($tar[0]['cost'], $oi['cust_idiva']) : VikRentItems::sayCostMinusIva($tar[0]['cost'] * $oi['itemquant'], $tar[0]['idprice'], $order[0]);
				$pricestr = ($is_cust_cost ? JText::translate('VRIRENTCUSTRATEPLAN').": ".$costplusiva : VikRentItems::getPriceName($tar[0]['idprice'], $vri_tn).": ".$costplusiva.(!empty($tar[0]['attrdata']) ? "\n".VikRentItems::getPriceAttr($tar[0]['idprice'], $vri_tn).": ".$tar[0]['attrdata'] : ""));
				$isdue += $is_cust_cost ? $tar[0]['cost'] : VikRentItems::sayCostPlusIva($tar[0]['cost'] * $oi['itemquant'], $tar[0]['idprice'], $order[0]);
				$optstr = "";
				$optarrtaxnet = array();
				if (!empty($oi['optionals'])) {
					$stepo = explode(";", $oi['optionals']);
					foreach ($stepo as $oo){
						if (!empty($oo)) {
							$stept = explode(":", $oo);
							$q = "SELECT * FROM `#__vikrentitems_optionals` WHERE `id`='".intval($stept[0])."';";
							$dbo->setQuery($q);
							$dbo->execute();
							if ($dbo->getNumRows() == 1) {
								$actopt = $dbo->loadAssocList();
								$vri_tn->translateContents($actopt, '#__vikrentitems_optionals');
								$specvar = '';
								if (!empty($actopt[0]['specifications']) && strstr($stept[1], '-') != false) {
									$optspeccosts = VikRentItems::getOptionSpecIntervalsCosts($actopt[0]['specifications']);
									$optspecnames = VikRentItems::getOptionSpecIntervalsNames($actopt[0]['specifications']);
									$specstept = explode('-', $stept[1]);
									$stept[1] = $specstept[0];
									$specvar = $specstept[1];
									$actopt[0]['specintv'] = $specvar;
									$actopt[0]['name'] .= ' ('.$optspecnames[($specvar - 1)].')';
									$actopt[0]['quan'] = $stept[1];
									$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optspeccosts[($specvar - 1)]) * $order[0]['days'] * $stept[1]) : (floatval($optspeccosts[($specvar - 1)]) * $stept[1]));
								} else {
									$realcost = (intval($actopt[0]['perday'])==1 ? ($actopt[0]['cost'] * $order[0]['days'] * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
								}
								if (!empty($actopt[0]['maxprice']) && $actopt[0]['maxprice'] > 0 && $realcost > $actopt[0]['maxprice']) {
									$realcost = $actopt[0]['maxprice'];
									if (intval($actopt[0]['hmany']) == 1 && intval($stept[1]) > 1) {
										$realcost = $actopt[0]['maxprice'] * $stept[1];
									}
								}
								$opt_item_units = $actopt[0]['onceperitem'] ? 1 : $oi['itemquant'];
								$tmpopr = VikRentItems::sayOptionalsPlusIva($realcost * $opt_item_units, $actopt[0]['idiva'], $order[0]);
								$isdue += $tmpopr;
								$optnetprice = VikRentItems::sayOptionalsMinusIva($realcost * $opt_item_units, $actopt[0]['idiva'], $order[0]);
								$optarrtaxnet[] = $optnetprice;
								$optstr .= ($stept[1] > 1 ? $stept[1]." " : "").$actopt[0]['name'].": ".$tmpopr."\n";
							}
						}
					}
				}
				// VRI 1.6 - custom extra costs
				if (!empty($oi['extracosts'])) {
					$cur_extra_costs = json_decode($oi['extracosts'], true);
					foreach ($cur_extra_costs as $eck => $ecv) {
						$efee_cost = VikRentItems::sayOptionalsPlusIva($ecv['cost'], $ecv['idtax'], $order[0]);
						$isdue += $efee_cost;
						$efee_cost_without = VikRentItems::sayOptionalsMinusIva($ecv['cost'], $ecv['idtax'], $order[0]);
						$optarrtaxnet[] = $efee_cost_without;
						$optstr .= $ecv['name'].": ".$efee_cost."\n";
					}
				}
				//
				$arrayinfopdf = array('days' => $order[0]['days'], 'tarminusiva' => $costminusiva, 'tartax' => ($costplusiva - $costminusiva), 'opttaxnet' => $optarrtaxnet, 'locfeenet' => $locfeewithouttax);
				$vricart[$oi['iditem']][$koi]['itemquant'] = $oi['itemquant'];
				$vricart[$oi['iditem']][$koi]['info'] = VikRentItems::getItemInfo($oi['iditem'], $vri_tn);
				$vricart[$oi['iditem']][$koi]['pricestr'] = $pricestr;
				$vricart[$oi['iditem']][$koi]['optstr'] = $optstr;
				$vricart[$oi['iditem']][$koi]['optarrtaxnet'] = $optarrtaxnet;
				$vricart[$oi['iditem']][$koi]['infopdf'] = $arrayinfopdf;
				if (!empty($oi['timeslot'])) {
					$vricart[$oi['iditem']][$koi]['timeslot']['name'] = $oi['timeslot'];
				}
				if (!empty($oi['deliveryaddr'])) {
					$vricart[$oi['iditem']][$koi]['delivery']['vrideliveryaddress'] = $oi['deliveryaddr'];
					$vricart[$oi['iditem']][$koi]['delivery']['vrideliverydistance'] = $oi['deliverydist'];
				}
			}
			//delivery service
			if ($totdelivery > 0) {
				$isdue += $totdelivery;
			}
			//
			$usedcoupon = false;
			$origisdue = $isdue;
			if (strlen($order[0]['coupon']) > 0) {
				$usedcoupon = true;
				$expcoupon = explode(";", $order[0]['coupon']);
				$isdue = $isdue - $expcoupon[1];
			}
			if (!empty($order[0]['custmail'])) {
				$sendpdf = true;
				if (!$checkdbsendpdf) {
					$psendpdf = VikRequest::getString('sendpdf', '', 'request');
					if ($psendpdf != "1") {
						$sendpdf = false;
					}
				}
				$mainframe->enqueueMessage(JText::sprintf('VRORDERMAILRESENT', $order[0]['custmail']));
				$saystatus = $order[0]['status'] == 'confirmed' ? JText::translate('VRIOMPLETED') : JText::translate('VRSTANDBY');

				VikRentItems::sendOrderEmail($order[0]['id'], array('customer'), true, $sendpdf);

				// VikRentItems::sendCustMailFromBack($order[0]['custmail'], strip_tags($ftitle)." ".JText::translate('VRRENTALORD'), $ftitle, $nowts, $order[0]['custdata'], $vricart, $order[0]['ritiro'], $order[0]['consegna'], $isdue, $viklink, $saystatus, $ritplace, $consegnaplace, $maillocfee, $order[0]['id'], $order[0]['coupon'], $sendpdf, $totdelivery);
			} else {
				VikError::raiseWarning('', JText::translate('VRORDERMAILRESENTNOREC'));
			}
		}
		$mainframe->redirect("index.php?option=com_vikrentitems&task=editorder&cid[]=".$oid);
	}

	function sortcarat() {
		$sortid = VikRequest::getVar('cid', array(0));
		$pmode = VikRequest::getString('mode', '', 'request');
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		if (!empty($pmode)) {
			$q = "SELECT `id`,`ordering` FROM `#__vikrentitems_caratteristiche` ORDER BY `#__vikrentitems_caratteristiche`.`ordering` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			$totr=$dbo->getNumRows();
			if ($totr > 1) {
				$data = $dbo->loadAssocList();
				if ($pmode=="up") {
					foreach ($data as $v){
						if ($v['id']==$sortid[0]) {
							$y=$v['ordering'];
						}
					}
					if ($y && $y > 1) {
						$vik=$y - 1;
						$found=false;
						foreach ($data as $v){
							if (intval($v['ordering'])==intval($vik)) {
								$found=true;
								$q = "UPDATE `#__vikrentitems_caratteristiche` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								$q = "UPDATE `#__vikrentitems_caratteristiche` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								break;
							}
						}
						if (!$found) {
							$q = "UPDATE `#__vikrentitems_caratteristiche` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
				} elseif ($pmode=="down") {
					foreach ($data as $v){
						if ($v['id']==$sortid[0]) {
							$y=$v['ordering'];
						}
					}
					if ($y) {
						$vik=$y + 1;
						$found=false;
						foreach ($data as $v){
							if (intval($v['ordering'])==intval($vik)) {
								$found=true;
								$q = "UPDATE `#__vikrentitems_caratteristiche` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								$q = "UPDATE `#__vikrentitems_caratteristiche` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								break;
							}
						}
						if (!$found) {
							$q = "UPDATE `#__vikrentitems_caratteristiche` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
				}
			}
			$mainframe->redirect("index.php?option=com_vikrentitems&task=carat");
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems");
		}
	}

	function sortoptional() {
		$sortid = VikRequest::getVar('cid', array(0));
		$pmode = VikRequest::getString('mode', '', 'request');
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		if (!empty($pmode)) {
			$q = "SELECT `id`,`ordering` FROM `#__vikrentitems_optionals` ORDER BY `#__vikrentitems_optionals`.`ordering` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			$totr=$dbo->getNumRows();
			if ($totr > 1) {
				$data = $dbo->loadAssocList();
				if ($pmode=="up") {
					foreach ($data as $v){
						if ($v['id']==$sortid[0]) {
							$y=$v['ordering'];
						}
					}
					if ($y && $y > 1) {
						$vik=$y - 1;
						$found=false;
						foreach ($data as $v){
							if (intval($v['ordering'])==intval($vik)) {
								$found=true;
								$q = "UPDATE `#__vikrentitems_optionals` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								$q = "UPDATE `#__vikrentitems_optionals` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								break;
							}
						}
						if (!$found) {
							$q = "UPDATE `#__vikrentitems_optionals` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
				} elseif ($pmode=="down") {
					foreach ($data as $v){
						if ($v['id']==$sortid[0]) {
							$y=$v['ordering'];
						}
					}
					if ($y) {
						$vik=$y + 1;
						$found=false;
						foreach ($data as $v){
							if (intval($v['ordering'])==intval($vik)) {
								$found=true;
								$q = "UPDATE `#__vikrentitems_optionals` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								$q = "UPDATE `#__vikrentitems_optionals` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								break;
							}
						}
						if (!$found) {
							$q = "UPDATE `#__vikrentitems_optionals` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
				}
			}
			$mainframe->redirect("index.php?option=com_vikrentitems&task=optionals");
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems");
		}
	}

	function discounts() {
		VikRentItemsHelper::printHeader("discounts");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'discounts'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newdiscount() {
		VikRentItemsHelper::printHeader("discounts");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managediscount'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editdiscount() {
		VikRentItemsHelper::printHeader("discounts");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managediscount'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function creatediscount() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$pdiffcost = VikRequest::getFloat('diffcost', '', 'request');
		$piditems = VikRequest::getVar('iditems', array(0));
		$pdiscname = VikRequest::getString('discname', '', 'request');
		$pquantity = VikRequest::getInt('quantity', '', 'request');
		$pquantity = $pquantity < 1 ? 1 : $pquantity;
		$pifmorequant = VikRequest::getInt('ifmorequant', '', 'request');
		$pifmorequant = $pifmorequant == 1 ? 1 : 0;
		$pval_pcent = VikRequest::getString('val_pcent', '', 'request');
		$pval_pcent = $pval_pcent == "1" ? 1 : 2;
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		if (strlen($pdiffcost) > 0) {
			$itemstr="";
			if (@count($piditems) > 0) {
				foreach ($piditems as $item) {
					$itemstr.="-".$item."-,";
				}
			}
			$q = "INSERT INTO `#__vikrentitems_discountsquants` (`discname`,`iditems`,`quantity`,`val_pcent`,`diffcost`,`ifmorequant`) VALUES(".$dbo->quote($pdiscname).", ".$dbo->quote($itemstr).", '".$pquantity."', '".$pval_pcent."', ".$dbo->quote($pdiffcost).", '".$pifmorequant."');";
			$dbo->setQuery($q);
			$dbo->execute();
			$mainframe->enqueueMessage(JText::translate('VRIDISCOUNTSAVED'));
			$mainframe->redirect("index.php?option=com_vikrentitems&task=discounts");
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=newdiscount");
		}
	}

	function updatediscount() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$pwhere = VikRequest::getString('where', '', 'request');
		$pdiffcost = VikRequest::getFloat('diffcost', '', 'request');
		$piditems = VikRequest::getVar('iditems', array(0));
		$pdiscname = VikRequest::getString('discname', '', 'request');
		$pquantity = VikRequest::getInt('quantity', '', 'request');
		$pquantity = $pquantity < 1 ? 1 : $pquantity;
		$pifmorequant = VikRequest::getInt('ifmorequant', '', 'request');
		$pifmorequant = $pifmorequant == 1 ? 1 : 0;
		$pval_pcent = VikRequest::getString('val_pcent', '', 'request');
		$pval_pcent = $pval_pcent == "1" ? 1 : 2;
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		if (strlen($pdiffcost) > 0) {
			$itemstr="";
			if (@count($piditems) > 0) {
				foreach ($piditems as $item) {
					$itemstr.="-".$item."-,";
				}
			}
			$q = "UPDATE `#__vikrentitems_discountsquants` SET `discname`=".$dbo->quote($pdiscname).",`iditems`=".$dbo->quote($itemstr).",`quantity`='".$pquantity."',`val_pcent`='".$pval_pcent."',`diffcost`=".$dbo->quote($pdiffcost).",`ifmorequant`='".$pifmorequant."' WHERE `id`='".$pwhere."';";
			$dbo->setQuery($q);
			$dbo->execute();
			$mainframe->enqueueMessage(JText::translate('VRIDISCOUNTUPDATED'));
			$mainframe->redirect("index.php?option=com_vikrentitems&task=discounts");
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=editdiscount&cid[]=".$pwhere);
		}
	}

	function removediscounts() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d){
				$q = "DELETE FROM `#__vikrentitems_discountsquants` WHERE `id`=".intval($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=discounts");
	}

	function canceldiscount() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=discounts");
	}

	function timeslots() {
		VikRentItemsHelper::printHeader("timeslots");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'timeslots'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newtimeslot() {
		VikRentItemsHelper::printHeader("timeslots");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managetimeslot'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function edittimeslot() {
		VikRentItemsHelper::printHeader("timeslots");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managetimeslot'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createtimeslot() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$piditems = VikRequest::getVar('iditems', array(0));
		$ptname = VikRequest::getString('tname', '', 'request');
		$pfromh = VikRequest::getString('fromh', '', 'request');
		$pfromm = VikRequest::getString('fromm', '', 'request');
		$ptoh = VikRequest::getString('toh', '', 'request');
		$ptom = VikRequest::getString('tom', '', 'request');
		$pglobal = VikRequest::getString('global', '', 'request');
		$pglobal = $pglobal == 1 ? 1 : 0;
		$pdays = VikRequest::getString('days', '', 'request');
		$pdays = intval($pdays) < 0 ? 0 : intval($pdays);
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		if (strlen($ptname) > 0 && strlen($pfromh) > 0 && strlen($ptoh) > 0) {
			$itemstr="";
			if (@count($piditems) > 0) {
				foreach ($piditems as $item) {
					$itemstr.="-".$item."-,";
				}
			}
			$q = "INSERT INTO `#__vikrentitems_timeslots` (`tname`,`fromh`,`fromm`,`toh`,`tom`,`iditems`,`global`,`days`) VALUES(".$dbo->quote($ptname).", '".$pfromh."', '".$pfromm."', '".$ptoh."', '".$ptom."', ".$dbo->quote($itemstr).", '".$pglobal."', ".$pdays.");";
			$dbo->setQuery($q);
			$dbo->execute();
			$mainframe->enqueueMessage(JText::translate('VRITIMESLOTSAVED'));
			$mainframe->redirect("index.php?option=com_vikrentitems&task=timeslots");
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=newtimeslot");
		}
	}

	function updatetimeslot() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$piditems = VikRequest::getVar('iditems', array(0));
		$ptname = VikRequest::getString('tname', '', 'request');
		$pfromh = VikRequest::getString('fromh', '', 'request');
		$pfromm = VikRequest::getString('fromm', '', 'request');
		$ptoh = VikRequest::getString('toh', '', 'request');
		$ptom = VikRequest::getString('tom', '', 'request');
		$pwhere = VikRequest::getString('where', '', 'request');
		$pglobal = VikRequest::getString('global', '', 'request');
		$pglobal = $pglobal == 1 ? 1 : 0;
		$pdays = VikRequest::getString('days', '', 'request');
		$pdays = intval($pdays) < 0 ? 0 : intval($pdays);
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		if (strlen($ptname) > 0 && strlen($pfromh) > 0 && strlen($ptoh) > 0) {
			$itemstr="";
			if (@count($piditems) > 0) {
				foreach ($piditems as $item) {
					$itemstr.="-".$item."-,";
				}
			}
			$q = "UPDATE `#__vikrentitems_timeslots` SET `tname`=".$dbo->quote($ptname).",`fromh`='".$pfromh."',`fromm`='".$pfromm."',`toh`='".$ptoh."',`tom`='".$ptom."',`iditems`=".$dbo->quote($itemstr).",`global`='".$pglobal."',`days`=".$pdays." WHERE id='".$pwhere."';";
			$dbo->setQuery($q);
			$dbo->execute();
			$mainframe->enqueueMessage(JText::translate('VRITIMESLOTUPDATED'));
			$mainframe->redirect("index.php?option=com_vikrentitems&task=timeslots");
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=edittimeslot&cid[]=".$pwhere);
		}
	}

	function removeTimeslots() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d){
				$q = "DELETE FROM `#__vikrentitems_timeslots` WHERE `id`=".intval($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=timeslots");
	}

	function canceltimeslot() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=timeslots");
	}

	function relations() {
		VikRentItemsHelper::printHeader("relations");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'relations'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newrelation() {
		VikRentItemsHelper::printHeader("relations");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managerelation'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editrelation() {
		VikRentItemsHelper::printHeader("relations");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managerelation'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createrelation() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$piditems = VikRequest::getVar('iditems', array());
		$piditemstwo = VikRequest::getVar('iditemstwo', array(0));
		$prelname = VikRequest::getString('relname', '', 'request');
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		if (strlen($prelname) > 0 && count($piditems) > 0) {
			$itemstr="";
			$itemstrtwo="";
			if (@count($piditems) > 0) {
				foreach ($piditems as $item) {
					$itemstr.="-".$item."-;";
				}
			}
			if (@count($piditemstwo) > 0) {
				foreach ($piditemstwo as $item) {
					$itemstrtwo.="-".$item."-;";
				}
			}
			$q = "INSERT INTO `#__vikrentitems_relations` (`relname`,`relone`,`reltwo`) VALUES(".$dbo->quote($prelname).", '".$itemstr."', '".$itemstrtwo."');";
			$dbo->setQuery($q);
			$dbo->execute();
			$mainframe->enqueueMessage(JText::translate('VRIRELATIONSAVED'));
			$mainframe->redirect("index.php?option=com_vikrentitems&task=relations");
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=newrelation");
		}
	}

	function updaterelation() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$piditems = VikRequest::getVar('iditems', array());
		$piditemstwo = VikRequest::getVar('iditemstwo', array(0));
		$prelname = VikRequest::getString('relname', '', 'request');
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$pwhere = VikRequest::getString('where', '', 'request');
		if (strlen($prelname) > 0 && count($piditems) > 0) {
			$itemstr="";
			$itemstrtwo="";
			if (@count($piditems) > 0) {
				foreach ($piditems as $item) {
					$itemstr.="-".$item."-;";
				}
			}
			if (@count($piditemstwo) > 0) {
				foreach ($piditemstwo as $item) {
					$itemstrtwo.="-".$item."-;";
				}
			}
			$q = "UPDATE `#__vikrentitems_relations` SET `relname`=".$dbo->quote($prelname).",`relone`='".$itemstr."',`reltwo`='".$itemstrtwo."' WHERE `id`='".(int)$pwhere."';";
			$dbo->setQuery($q);
			$dbo->execute();
			$mainframe->enqueueMessage(JText::translate('VRIRELATIONUPDATED'));
			$mainframe->redirect("index.php?option=com_vikrentitems&task=relations");
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=editrelation&cid[]=".$pwhere);
		}
	}

	function removerelations() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d){
				$q = "DELETE FROM `#__vikrentitems_relations` WHERE `id`=".intval($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=relations");
	}

	function cancelrelation() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=relations");
	}

	function validatebaseaddr() {
		VikRequest::setVar('view', VikRequest::getCmd('view', 'validatebaseaddr'));
	
		parent::display();
	}

	function export() {
		VikRentItemsHelper::printHeader("8");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'export'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function doexport() {
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$oids = VikRequest::getVar('cid', array(0));
		$oids = count($oids) > 0 && intval($oids[key($oids)]) > 0 ? $oids : array();
		$pfrom = VikRequest::getString('from', '', 'request');
		$pto = VikRequest::getString('to', '', 'request');
		$pdatetype = VikRequest::getString('datetype', '', 'request');
		$pdatetype = $pdatetype == 'ts' ? 'ts' : 'ritiro';
		$plocation = VikRequest::getString('location', '', 'request');
		$ptype = VikRequest::getString('type', '', 'request');
		$ptype = $ptype == "csv" ? "csv" : "ics";
		$pstatus = VikRequest::getString('status', '', 'request');
		$pdateformat = VikRequest::getString('dateformat', '', 'request');
		$nowdf = VikRentItems::getDateFormat(true);
		$nowtf = VikRentItems::getTimeFormat(true);
		$pdateformat .= ' '.$nowtf;
		$tf = $nowtf;
		if ($nowdf == "%d/%m/%Y") {
			$df = 'd/m/Y';
		} elseif ($nowdf == "%m/%d/%Y") {
			$df = 'm/d/Y';
		} else {
			$df = 'Y/m/d';
		}
		$clauses = array();
		if (count($oids) > 0) {
			$clauses[] = "`o`.`id` IN(".implode(',', $oids).")";
		}
		if ($pstatus == "C") {
			$clauses[] = "`o`.`status`='confirmed'";
		}
		if (!empty($pfrom) && VikRentItems::dateIsValid($pfrom)) {
			$fromts = VikRentItems::getDateTimestamp($pfrom, '0', '0');
			$clauses[] = "`o`.`".$pdatetype."`>=".$fromts;
		}
		if (!empty($pto) && VikRentItems::dateIsValid($pto)) {
			$tots = VikRentItems::getDateTimestamp($pto, '23', '59');
			$clauses[] = "`o`.`".$pdatetype."`<=".$tots;
		}
		if (!empty($plocation)) {
			$clauses[] = "(`o`.`idplace`=".intval($plocation)." OR `o`.`idreturnplace`=".intval($plocation).")";
		}
		$q = "SELECT `o`.* FROM `#__vikrentitems_orders` AS `o`".(count($clauses) > 0 ? " WHERE ".implode(' AND ', $clauses) : "")." ORDER BY `o`.`ritiro` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$rows = $dbo->loadAssocList();
			if ($ptype == "csv") {
				//init csv creation
				$csvlines = array();
				$csvlines[] = array('ID', JText::translate('VRIEXPCSVPICK'), JText::translate('VRIEXPCSVDROP'), JText::translate('VRIEXPCSVITEMS'), JText::translate('VRIEXPCSVPICKLOC'), JText::translate('VRIEXPCSVDROPLOC'), JText::translate('VRIEXPCSVCUSTINFO'), JText::translate('VRIEXPCSVPAYMETH'), JText::translate('VRIEXPCSVORDSTATUS'), JText::translate('VRIEXPCSVTOT'), JText::translate('VRIEXPCSVTOTPAID'));
				foreach ($rows as $r) {
					$pickdate = $pdatetype == 'ts' ? $r['ritiro'] : date($pdateformat, $r['ritiro']);
					$dropdate = $pdatetype == 'ts' ? $r['consegna'] : date($pdateformat, $r['consegna']);
					$nowitems = array();
					$q = "SELECT `oi`.`itemquant`,`i`.`name` FROM `#__vikrentitems_ordersitems` AS `oi` LEFT JOIN `#__vikrentitems_items` `i` ON `i`.`id`=`oi`.`iditem` WHERE `oi`.`idorder`=".$r['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
					if ($dbo->getNumRows() > 0) {
						$allitems = $dbo->loadAssocList();
						foreach ($allitems as $it) {
							$nowitems[] = ($it['itemquant'] > 1 ? 'x'.$it['itemquant'].' ' : '').$it['name'];
						}
					}
					$pickloc = VikRentItems::getPlaceName($r['idplace']);
					$droploc = VikRentItems::getPlaceName($r['idreturnplace']);
					$custdata = preg_replace('/\s+/', ' ', trim($r['custdata']));
					$payment = VikRentItems::getPayment($r['idpayment']);
					$saystatus = ($r['status']=="confirmed" ? JText::translate('VRIONFIRMED') : JText::translate('VRSTANDBY'));
					$csvlines[] = array($r['id'], $pickdate, $dropdate, implode(', ', $nowitems), $pickloc, $droploc, $custdata, $payment['name'], $saystatus, VikRentItems::numberFormat($r['order_total']), VikRentItems::numberFormat($r['totpaid']));
				}
				//end csv creation
			} else {
				$icslines = array();
				$icscontent = "BEGIN:VCALENDAR\n";
				$icscontent .= "VERSION:2.0\n";
				$icscontent .= "PRODID:-//e4j//VikRentItems//EN\n";
				$icscontent .= "CALSCALE:GREGORIAN\n";
				$icscontent .= "X-WR-TIMEZONE:".date_default_timezone_get()."\n";
				$str = "";

				/**
				 * @wponly 	Rewrite URI for front-end
				 */
				$model 		= JModel::getInstance('vikrentitems', 'shortcodes');
				$itemid 	= $model->best('order');
				//
				foreach ($rows as $r) {
					/**
					 * @wponly 	Rewrite URI for front-end by passing the third argument
					 */
					$uri = VikRentItems::externalroute('index.php?option=com_vikrentitems&view=order&sid=' . $r['sid'] . '&ts=' . $r['ts'], false, ($itemid ? $itemid : null));
					//
					$nowitems = array();
					$q = "SELECT `oi`.`itemquant`,`i`.`name` FROM `#__vikrentitems_ordersitems` AS `oi` LEFT JOIN `#__vikrentitems_items` `i` ON `i`.`id`=`oi`.`iditem` WHERE `oi`.`idorder`=".$r['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
					if ($dbo->getNumRows() > 0) {
						$allitems = $dbo->loadAssocList();
						foreach ($allitems as $it) {
							$nowitems[] = ($it['itemquant'] > 1 ? 'x'.$it['itemquant'].' ' : '').$it['name'];
						}
					}
					$pickloc = VikRentItems::getPlaceName($r['idplace']);
					//$custdata = preg_replace('/\s+/', ' ', trim($r['custdata']));
					$description = implode(', ', $nowitems)."\\n".str_replace("\n", "\\n", trim($r['custdata']));
					$str .= "BEGIN:VEVENT\n";
					//End of the Event set as Drop Off Date, decomment line below to have it on Pickup Date
					//$str .= "DTEND:".date('Ymd\THis\Z', $r['ritiro'])."\n";
					$str .= "DTEND;TZID=".date_default_timezone_get().":".date('Ymd\THis', $r['consegna'])."\n";
					//
					$str .= "UID:".uniqid()."\n";
					//Date format for DTSTAMP is with Timezone info (\Z)
					$str .= "DTSTAMP:".date('Ymd\THis\Z', time())."\n";
					$str .= "LOCATION:".preg_replace('/([\,;])/','\\\$1', $pickloc)."\n";
					$str .= ((strlen($description) > 0 ) ? "DESCRIPTION:".preg_replace('/([\,;])/','\\\$1', $description)."\n" : "");
					$str .= "URL;VALUE=URI:".preg_replace('/([\,;])/','\\\$1', $uri)."\n";
					$str .= "SUMMARY:".JText::sprintf('VRIICSEXPSUMMARY', date($tf, $r['ritiro']))."\n";
					$str .= "DTSTART;TZID=".date_default_timezone_get().":".date('Ymd\THis', $r['ritiro'])."\n";
					$str .= "END:VEVENT\n";
				}
				$icscontent .= $str;
				$icscontent .= "END:VCALENDAR\n";
			}
			//download file from buffer
			$dfilename = 'export_'.date('Y-m-d_H_i').'.'.$ptype;
			if ($ptype == "csv") {
				header("Content-type: text/csv");
				header("Cache-Control: no-store, no-cache");
				header('Content-Disposition: attachment; filename="'.$dfilename.'"');
				$outstream = fopen("php://output", 'w');
				foreach ($csvlines as $csvline) {
					fputcsv($outstream, $csvline);
				}
				fclose($outstream);
				exit;
			} else {
				header("Content-Type: application/octet-stream; ");
				header("Cache-Control: no-store, no-cache");
				header("Content-Disposition: attachment; filename=\"".$dfilename."\"");
				$f = fopen('php://output', "w");
				fwrite($f, $icscontent);
				fclose($f);
				exit;
			}
		} else {
			VikError::raiseWarning('', JText::translate('VRIEXPORTERRNOREC'));
			$mainframe->redirect("index.php?option=com_vikrentitems&task=orders");
		}
	}

	function sortlocation() {
		$cid = VikRequest::getVar('cid', array(0));
		$sortid = (int)$cid[0];
		$pmode = VikRequest::getString('mode', '', 'request');
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		if (!empty($pmode)) {
			$q = "SELECT `id`,`ordering` FROM `#__vikrentitems_places` ORDER BY `#__vikrentitems_places`.`ordering` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			$totr=$dbo->getNumRows();
			if ($totr > 1) {
				$data = $dbo->loadAssocList();
				if ($pmode == "up") {
					foreach ($data as $v) {
						if ($v['id'] == $sortid) {
							$y = $v['ordering'];
						}
					}
					if ($y && $y > 1) {
						$vik = $y - 1;
						$found = false;
						foreach ($data as $v) {
							if (intval($v['ordering'])==intval($vik)) {
								$found = true;
								$q = "UPDATE `#__vikrentitems_places` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								$q = "UPDATE `#__vikrentitems_places` SET `ordering`='".$vik."' WHERE `id`='".$sortid."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								break;
							}
						}
						if (!$found) {
							$q = "UPDATE `#__vikrentitems_places` SET `ordering`='".$vik."' WHERE `id`='".$sortid."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
				} elseif ($pmode == "down") {
					foreach ($data as $v) {
						if ($v['id'] == $sortid[0]) {
							$y = $v['ordering'];
						}
					}
					if ($y) {
						$vik = $y + 1;
						$found = false;
						foreach ($data as $v) {
							if (intval($v['ordering']) == intval($vik)) {
								$found = true;
								$q = "UPDATE `#__vikrentitems_places` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								$q = "UPDATE `#__vikrentitems_places` SET `ordering`='".$vik."' WHERE `id`='".$sortid."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								break;
							}
						}
						if (!$found) {
							$q = "UPDATE `#__vikrentitems_places` SET `ordering`='".$vik."' WHERE `id`='".$sortid."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
				}
			}
			$mainframe->redirect("index.php?option=com_vikrentitems&task=places");
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems");
		}
	}

	function loadcronparams() {
		//to be called via ajax
		$html = '---------';
		$phpfile = VikRequest::getString('phpfile', '', 'request');
		if (!empty($phpfile)) {
			$html = VikRentItems::displayCronParameters($phpfile);
		}
		echo $html;
		exit;
	}

	function loadpaymentparams() {
		$html = '---------';
		$phpfile = VikRequest::getString('phpfile', '', 'request');
		if (!empty($phpfile)) {
			$html = VikRentItems::displayPaymentParameters($phpfile);
		}
		echo $html;
		exit;
	}

	function translations() {
		VikRentItemsHelper::printHeader("20");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'translations'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function savetranslation() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::_('JINVALID_TOKEN'), 403);
		}
		$this->do_savetranslation();
	}

	function savetranslationstay() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::_('JINVALID_TOKEN'), 403);
		}
		$this->do_savetranslation(true);
	}

	private function do_savetranslation($stay = false) {
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$vri_tn = VikRentItems::getTranslator();
		$table = VikRequest::getString('vri_table', '', 'request');
		$cur_langtab = VikRequest::getString('vri_lang', '', 'request');
		$langs = $vri_tn->getLanguagesList();
		$xml_tables = $vri_tn->getTranslationTables();
		if (!empty($table) && array_key_exists($table, $xml_tables)) {
			$tn = VikRequest::getVar('tn', array(), 'request', 'array', VIKREQUEST_ALLOWRAW);
			$tn_saved = 0;
			$table_cols = $vri_tn->getTableColumns($table);
			$table = $vri_tn->replacePrefix($table);
			foreach ($langs as $ltag => $lang) {
				if ($ltag == $vri_tn->default_lang) {
					continue;
				}
				if (array_key_exists($ltag, $tn) && count($tn[$ltag]) > 0) {
					foreach ($tn[$ltag] as $reference_id => $translation) {
						$lang_translation = array();
						foreach ($table_cols as $field => $fdetails) {
							if (!array_key_exists($field, $translation)) {
								continue;
							}
							$ftype = $fdetails['type'];
							if ($ftype == 'skip') {
								continue;
							}
							if ($ftype == 'json') {
								$translation[$field] = json_encode($translation[$field]);
							}
							$lang_translation[$field] = $translation[$field];
						}
						if (count($lang_translation) > 0) {
							$q = "SELECT `id` FROM `#__vikrentitems_translations` WHERE `table`=".$dbo->quote($table)." AND `lang`=".$dbo->quote($ltag)." AND `reference_id`=".$dbo->quote((int)$reference_id).";";
							$dbo->setQuery($q);
							$dbo->execute();
							if ($dbo->getNumRows() > 0) {
								$last_id = $dbo->loadResult();
								$q = "UPDATE `#__vikrentitems_translations` SET `content`=".$dbo->quote(json_encode($lang_translation))." WHERE `id`=".(int)$last_id.";";
							} else {
								$q = "INSERT INTO `#__vikrentitems_translations` (`table`,`lang`,`reference_id`,`content`) VALUES (".$dbo->quote($table).", ".$dbo->quote($ltag).", ".$dbo->quote((int)$reference_id).", ".$dbo->quote(json_encode($lang_translation)).");";
							}
							$dbo->setQuery($q);
							$dbo->execute();
							$tn_saved++;
						}
					}
				}
			}
			if ($tn_saved > 0) {
				$mainframe->enqueueMessage(JText::translate('VRITRANSLSAVEDOK'));
			}
		} else {
			VikError::raiseWarning('', JText::translate('VRITRANSLATIONERRINVTABLE'));
		}
		$mainframe->redirect("index.php?option=com_vikrentitems".($stay ? '&task=translations&vri_table='.$vri_tn->replacePrefix($table).'&vri_lang='.$cur_langtab : ''));
	}

	function edittmplfile() {
		//modal box, so we do not set menu or footer

		VikRequest::setVar('view', VikRequest::getCmd('view', 'edittmplfile'));
	
		parent::display();
	}

	function tmplfileprew() {
		//modal box, so we do not set menu or footer
	
		VikRequest::setVar('view', VikRequest::getCmd('view', 'tmplfileprew'));
	
		parent::display();
	}

	function savetmplfile() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::_('JINVALID_TOKEN'), 403);
		}
		$fpath = VikRequest::getString('path', '', 'request', VIKREQUEST_ALLOWRAW);
		$pcont = VikRequest::getString('cont', '', 'request', VIKREQUEST_ALLOWRAW);
		$mainframe = JFactory::getApplication();
		$exists = file_exists($fpath) ? true : false;
		if (!$exists) {
			$fpath = urldecode($fpath);
		}
		$fpath = file_exists($fpath) ? $fpath : '';
		if (!empty($fpath)) {
			$fp = fopen($fpath, 'wb');
			$byt = (int)fwrite($fp, $pcont);
			fclose($fp);
			if ($byt > 0) {
				$mainframe->enqueueMessage(JText::translate('VRIUPDTMPLFILEOK'));
				/**
				 * @wponly  call the UpdateManager Class to temporary store modifications made to template files
				 */
				VikRentItemsUpdateManager::storeTemplateContent($fpath, $pcont);
				//
			} else {
				VikError::raiseWarning('', JText::translate('VRIUPDTMPLFILENOBYTES'));
			}
		} else {
			VikError::raiseWarning('', JText::translate('VRIUPDTMPLFILEERR'));
		}
		$mainframe->redirect("index.php?option=com_vikrentitems&task=edittmplfile&path=".$fpath."&tmpl=component");
		exit;
	}

	function unlockrecords() {
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d){
				$q = "DELETE FROM `#__vikrentitems_tmplock` WHERE `id`=".$dbo->quote($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems");
	}

	function checkversion() {
		$params = new stdClass;
		$params->version 	= VIKRENTITEMS_SOFTWARE_VERSION;
		$params->alias 		= 'com_vikrentitems';

		$result = array();

		if (!count($result)) {
			$result = new stdClass;
			$result->status = 0;
		} else {
			$result = $result[0];
		}

		echo json_encode($result);
		exit;
	}

	function updateprogram() {
		$params = new stdClass;
		$params->version 	= VIKRENTITEMS_SOFTWARE_VERSION;
		$params->alias 		= 'com_vikrentitems';

		$result = array();

		if (!count($result) || !$result[0]) {
			if (class_exists('JEventDispatcher')) {
				$result = $dispatcher->trigger('checkVersion', array(&$params));
			} else {
				$app = JFactory::getApplication();
				if (method_exists($app, 'triggerEvent')) {
					$result = $app->triggerEvent('checkVersion', array(&$params));
				}
			}
		}

		if (!count($result) || !$result[0]->status || !$result[0]->response->status) {
			exit('Error, plugin disabled');
		}

		JToolbarHelper::title(JText::translate('VRMAINTITLEUPDATEPROGRAM'));

		VikRentItemsHelper::pUpdateProgram($result[0]->response);
	}

	function updateprogramlaunch() {
		$params = new stdClass;
		$params->version 	= VIKRENTITEMS_SOFTWARE_VERSION;
		$params->alias 		= 'com_vikrentitems';

		$json = new stdClass;
		$json->status = false;

		echo json_encode($json);
		exit;
	}

	function cloneitem() {
		$ids = VikRequest::getVar('cid', array(0));
		$itid = isset($ids[0]) ? $ids[0] : 0;
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		if (empty($itid)) {
			VikError::raiseWarning('', 'Empty Item ID for cloning');
			$mainframe->redirect('index.php?option=com_vikrentitems&task=items');
			exit;
		}
		$q = "SELECT * FROM `#__vikrentitems_items` WHERE `id`=".(int)$itid.";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$parentitem = $dbo->loadAssoc();
			//change some values from the parent Item
			unset($parentitem['id']);
			$parentitem['name'] .= ' '.JText::translate('VRICLONEITEMCOPY');
			$parentitem['alias'] .= '-copy'.date('njgi');
			//
			$itemcols = array();
			$itemvals = array();
			foreach ($parentitem as $col => $val) {
				array_push($itemcols, '`'.$col.'`');
				if ($val == null) {
					array_push($itemvals, 'NULL');
				} else {
					array_push($itemvals, $dbo->quote($val));
				}
			}
			$q = "INSERT INTO `#__vikrentitems_items` (".implode(', ', $itemcols).") VALUES(".implode(', ', $itemvals).");";
			$dbo->setQuery($q);
			$dbo->execute();
			$newid = $dbo->insertid();
			//check discounts per quantity
			$q = "SELECT `id`,`iditems` FROM `#__vikrentitems_discountsquants` WHERE `iditems` LIKE '%-".(int)$itid."-%';";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$discounts = $dbo->loadAssocList();
				foreach ($discounts as $disc) {
					$q = "UPDATE `#__vikrentitems_discountsquants` SET `iditems`=".$dbo->quote($disc['iditems'].'-'.$newid.'-,')." WHERE `id`=".$disc['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
			//
			//check daily fares
			$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `iditem`=".(int)$itid.";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$fares = $dbo->loadAssocList();
				foreach ($fares as $fare) {
					unset($fare['id']);
					$fare['iditem'] = $newid;
					$cols = array();
					$vals = array();
					foreach ($fare as $fk => $fv) {
						array_push($cols, '`'.$fk.'`');
						if ($fv == null) {
							array_push($vals, 'NULL');
						} else {
							array_push($vals, $dbo->quote($fv));
						}
					}
					$q = "INSERT INTO `#__vikrentitems_dispcost` (".implode(', ', $cols).") VALUES(".implode(', ', $vals).");";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
			//
			//check hourly fares
			$q = "SELECT * FROM `#__vikrentitems_dispcosthours` WHERE `iditem`=".(int)$itid.";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$fares = $dbo->loadAssocList();
				foreach ($fares as $fare) {
					unset($fare['id']);
					$fare['iditem'] = $newid;
					$cols = array();
					$vals = array();
					foreach ($fare as $fk => $fv) {
						array_push($cols, '`'.$fk.'`');
						if ($fv == null) {
							array_push($vals, 'NULL');
						} else {
							array_push($vals, $dbo->quote($fv));
						}
					}
					$q = "INSERT INTO `#__vikrentitems_dispcosthours` (".implode(', ', $cols).") VALUES(".implode(', ', $vals).");";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
			//
			//check extra hours charges
			$q = "SELECT * FROM `#__vikrentitems_hourscharges` WHERE `iditem`=".(int)$itid.";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$fares = $dbo->loadAssocList();
				foreach ($fares as $fare) {
					unset($fare['id']);
					$fare['iditem'] = $newid;
					$cols = array();
					$vals = array();
					foreach ($fare as $fk => $fv) {
						array_push($cols, '`'.$fk.'`');
						if ($fv == null) {
							array_push($vals, 'NULL');
						} else {
							array_push($vals, $dbo->quote($fv));
						}
					}
					$q = "INSERT INTO `#__vikrentitems_hourscharges` (".implode(', ', $cols).") VALUES(".implode(', ', $vals).");";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
			//
			//check special prices
			$q = "SELECT `id`,`iditems` FROM `#__vikrentitems_seasons` WHERE `iditems` LIKE '%-".(int)$itid."-%';";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$sprices = $dbo->loadAssocList();
				foreach ($sprices as $sprice) {
					$q = "UPDATE `#__vikrentitems_seasons` SET `iditems`=".$dbo->quote($sprice['iditems'].'-'.$newid.'-,')." WHERE `id`=".$sprice['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
			//
			//check time slots
			$q = "SELECT `id`,`iditems` FROM `#__vikrentitems_timeslots` WHERE `iditems` LIKE '%-".(int)$itid."-%';";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$tslots = $dbo->loadAssocList();
				foreach ($tslots as $tslot) {
					$q = "UPDATE `#__vikrentitems_timeslots` SET `iditems`=".$dbo->quote($tslot['iditems'].'-'.$newid.'-,')." WHERE `id`=".$tslot['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
			//
			$mainframe->enqueueMessage(JText::translate('VRICLONEITEMOK'));
			$mainframe->redirect('index.php?option=com_vikrentitems&task=edititem&cid[]='.$newid);
			exit;
		} else {
			VikError::raiseWarning('', 'Invalid Item ID for cloning');
			$mainframe->redirect('index.php?option=com_vikrentitems&task=items');
			exit;
		}
	}

	function customers() {
		VikRentItemsHelper::printHeader("customers");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'customers'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newcustomer() {
		VikRentItemsHelper::printHeader("customers");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managecustomer'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editcustomer() {
		VikRentItemsHelper::printHeader("customers");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managecustomer'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function removecustomers() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			$cpin = VikRentItems::getCPinIstance();
			foreach ($ids as $d) {
				$cpin->pluginCustomerSync($d, 'delete');
				$q = "DELETE FROM `#__vikrentitems_customers` WHERE `id`=".(int)$d.";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=customers");
	}

	function savecustomer() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$pfirst_name = VikRequest::getString('first_name', '', 'request');
		$plast_name = VikRequest::getString('last_name', '', 'request');
		$pcompany = VikRequest::getString('company', '', 'request');
		$pvat = VikRequest::getString('vat', '', 'request');
		$pemail = VikRequest::getString('email', '', 'request');
		$pphone = VikRequest::getString('phone', '', 'request');
		$pcountry = VikRequest::getString('country', '', 'request');
		$ppin = VikRequest::getString('pin', '', 'request');
		$pujid = VikRequest::getInt('ujid', '', 'request');
		$paddress = VikRequest::getString('address', '', 'request');
		$pcity = VikRequest::getString('city', '', 'request');
		$pzip = VikRequest::getString('zip', '', 'request');
		$pgender = VikRequest::getString('gender', '', 'request');
		$pgender = in_array($pgender, array('F', 'M')) ? $pgender : '';
		$pbdate = VikRequest::getString('bdate', '', 'request');
		$ppbirth = VikRequest::getString('pbirth', '', 'request');
		$pdoctype = VikRequest::getString('doctype', '', 'request');
		$pdocnum = VikRequest::getString('docnum', '', 'request');
		$pnotes = VikRequest::getString('notes', '', 'request');
		$pscandocimg = VikRequest::getString('scandocimg', '', 'request');
		$pischannel = VikRequest::getInt('ischannel', '', 'request');
		$pcommission = VikRequest::getFloat('commission', '', 'request');
		$pcalccmmon = VikRequest::getInt('calccmmon', '', 'request');
		$papplycmmon = VikRequest::getInt('applycmmon', '', 'request');
		$pchname = VikRequest::getString('chname', '', 'request');
		$pchcolor = VikRequest::getString('chcolor', '', 'request');
		$ptmpl = VikRequest::getString('tmpl', '', 'request');
		$pbid = VikRequest::getInt('bid', '', 'request');
		if (!empty($pfirst_name) && !empty($plast_name)) {
			$cpin = VikRentItems::getCPinIstance();
			$q = "SELECT * FROM `#__vikrentitems_customers` WHERE `email`=".$dbo->quote($pemail)." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 0) {
				if (empty($ppin)) {
					$ppin = $cpin->generateUniquePin();
				} elseif ($cpin->pinExists($ppin)) {
					$ppin = $cpin->generateUniquePin();
				}
				//file upload
				$pimg = VikRequest::getVar('docimg', null, 'files', 'array');
				jimport('joomla.filesystem.file');
				$gimg = "";
				if (isset($pimg) && strlen(trim($pimg['name']))) {
					$filename = JFile::makeSafe(rand(100, 9999).str_replace(" ", "_", strtolower($pimg['name'])));
					$src = $pimg['tmp_name'];
					$dest = VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'idscans'.DIRECTORY_SEPARATOR;
					$j = "";
					if (file_exists($dest.$filename)) {
						$j = rand(171, 1717);
						while (file_exists($dest.$j.$filename)) {
							$j++;
						}
					}
					$finaldest = $dest.$j.$filename;
					$check = !empty($pimg['tmp_name']) ? getimagesize($pimg['tmp_name']) : [];
					if ($check[2] & imagetypes()) {
						if (VikRentItems::uploadFile($src, $finaldest)) {
							$gimg = $j.$filename;
						} else {
							VikError::raiseWarning('', 'Error while uploading image');
						}
					} else {
						VikError::raiseWarning('', 'Uploaded file is not an Image');
					}
				} elseif (!empty($pscandocimg)) {
					$gimg = $pscandocimg;
				}
				//
				$q = "INSERT INTO `#__vikrentitems_customers` (`first_name`,`last_name`,`email`,`phone`,`country`,`pin`,`ujid`,`address`,`city`,`zip`,`doctype`,`docnum`,`docimg`,`notes`,`company`,`vat`,`gender`,`bdate`,`pbirth`) VALUES(".$dbo->quote($pfirst_name).", ".$dbo->quote($plast_name).", ".$dbo->quote($pemail).", ".$dbo->quote($pphone).", ".$dbo->quote($pcountry).", ".$dbo->quote($ppin).", ".$dbo->quote($pujid).", ".$dbo->quote($paddress).", ".$dbo->quote($pcity).", ".$dbo->quote($pzip).", ".$dbo->quote($pdoctype).", ".$dbo->quote($pdocnum).", ".$dbo->quote($gimg).", ".$dbo->quote($pnotes).", ".$dbo->quote($pcompany).", ".$dbo->quote($pvat).", ".$dbo->quote($pgender).", ".$dbo->quote($pbdate).", ".$dbo->quote($ppbirth).");";
				$dbo->setQuery($q);
				$dbo->execute();
				$lid = $dbo->insertid();
				$cpin->pluginCustomerSync($lid, 'insert');
				if (!empty($lid)) {
					$mainframe->enqueueMessage(JText::translate('VRCUSTOMERSAVED'));
				}
			} else {
				//email already exists
				$ex_customer = $dbo->loadAssoc();
				VikError::raiseWarning('', JText::translate('VRERRCUSTOMEREMAILEXISTS').'<br/><a href="index.php?option=com_vikrentitems&task=editcustomer&cid[]='.$ex_customer['id'].'" target="_blank">'.$ex_customer['first_name'].' '.$ex_customer['last_name'].'</a>');
			}
		}
		$mainframe->redirect("index.php?option=com_vikrentitems&task=customers");
	}

	function updatecustomer() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$this->do_updatecustomer();
	}

	function updatecustomerstay() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$this->do_updatecustomer(true);
	}

	private function do_updatecustomer($stay = false) {
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$pfirst_name = VikRequest::getString('first_name', '', 'request');
		$plast_name = VikRequest::getString('last_name', '', 'request');
		$pcompany = VikRequest::getString('company', '', 'request');
		$pvat = VikRequest::getString('vat', '', 'request');
		$pemail = VikRequest::getString('email', '', 'request');
		$pphone = VikRequest::getString('phone', '', 'request');
		$pcountry = VikRequest::getString('country', '', 'request');
		$ppin = VikRequest::getString('pin', '', 'request');
		$pujid = VikRequest::getInt('ujid', '', 'request');
		$paddress = VikRequest::getString('address', '', 'request');
		$pcity = VikRequest::getString('city', '', 'request');
		$pzip = VikRequest::getString('zip', '', 'request');
		$pgender = VikRequest::getString('gender', '', 'request');
		$pgender = in_array($pgender, array('F', 'M')) ? $pgender : '';
		$pbdate = VikRequest::getString('bdate', '', 'request');
		$ppbirth = VikRequest::getString('pbirth', '', 'request');
		$pdoctype = VikRequest::getString('doctype', '', 'request');
		$pdocnum = VikRequest::getString('docnum', '', 'request');
		$pnotes = VikRequest::getString('notes', '', 'request');
		$pscandocimg = VikRequest::getString('scandocimg', '', 'request');
		$pischannel = VikRequest::getInt('ischannel', '', 'request');
		$pcommission = VikRequest::getFloat('commission', '', 'request');
		$pcalccmmon = VikRequest::getInt('calccmmon', '', 'request');
		$papplycmmon = VikRequest::getInt('applycmmon', '', 'request');
		$pchname = VikRequest::getString('chname', '', 'request');
		$pchcolor = VikRequest::getString('chcolor', '', 'request');
		$pwhere = VikRequest::getInt('where', '', 'request');
		$ptmpl = VikRequest::getString('tmpl', '', 'request');
		$pbid = VikRequest::getInt('bid', '', 'request');
		if (!empty($pwhere) && !empty($pfirst_name) && !empty($plast_name)) {
			$q = "SELECT * FROM `#__vikrentitems_customers` WHERE `id`=".(int)$pwhere." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 1) {
				$customer = $dbo->loadAssoc();
			} else {
				$mainframe->redirect("index.php?option=com_vikrentitems&task=customers");
				exit;
			}
			$q = "SELECT * FROM `#__vikrentitems_customers` WHERE `email`=".$dbo->quote($pemail)." AND `id`!=".(int)$pwhere." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() == 0) {
				$cpin = VikRentItems::getCPinIstance();
				if (empty($ppin)) {
					$ppin = $customer['pin'];
				} elseif ($cpin->pinExists($ppin, $customer['pin'])) {
					$ppin = $cpin->generateUniquePin();
				}
				//file upload
				$pimg = VikRequest::getVar('docimg', null, 'files', 'array');
				jimport('joomla.filesystem.file');
				$gimg = "";
				if (isset($pimg) && strlen(trim($pimg['name']))) {
					$filename = JFile::makeSafe(rand(100, 9999).str_replace(" ", "_", strtolower($pimg['name'])));
					$src = $pimg['tmp_name'];
					$dest = VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'idscans'.DIRECTORY_SEPARATOR;
					$j = "";
					if (file_exists($dest.$filename)) {
						$j = rand(171, 1717);
						while (file_exists($dest.$j.$filename)) {
							$j++;
						}
					}
					$finaldest = $dest.$j.$filename;
					$check = !empty($pimg['tmp_name']) ? getimagesize($pimg['tmp_name']) : [];
					if ($check[2] & imagetypes()) {
						if (VikRentItems::uploadFile($src, $finaldest)) {
							$gimg = $j.$filename;
						} else {
							VikError::raiseWarning('', 'Error while uploading image');
						}
					} else {
						VikError::raiseWarning('', 'Uploaded file is not an Image');
					}
				} elseif (!empty($pscandocimg)) {
					$gimg = $pscandocimg;
				}
				//
				$q = "UPDATE `#__vikrentitems_customers` SET `first_name`=".$dbo->quote($pfirst_name).",`last_name`=".$dbo->quote($plast_name).",`email`=".$dbo->quote($pemail).",`phone`=".$dbo->quote($pphone).",`country`=".$dbo->quote($pcountry).",`pin`=".$dbo->quote($ppin).",`ujid`=".$dbo->quote($pujid).",`address`=".$dbo->quote($paddress).",`city`=".$dbo->quote($pcity).",`zip`=".$dbo->quote($pzip).",`doctype`=".$dbo->quote($pdoctype).",`docnum`=".$dbo->quote($pdocnum).(!empty($gimg) ? ",`docimg`=".$dbo->quote($gimg) : "").",`notes`=".$dbo->quote($pnotes).",`company`=".$dbo->quote($pcompany).",`vat`=".$dbo->quote($pvat).",`gender`=".$dbo->quote($pgender).",`bdate`=".$dbo->quote($pbdate).",`pbirth`=".$dbo->quote($ppbirth)." WHERE `id`=".(int)$pwhere.";";
				$dbo->setQuery($q);
				$dbo->execute();
				$cpin->pluginCustomerSync($pwhere, 'update');
				$mainframe->enqueueMessage(JText::translate('VRCUSTOMERSAVED'));
			} else {
				//email already exists
				$ex_customer = $dbo->loadAssoc();
				VikError::raiseWarning('', JText::translate('VRERRCUSTOMEREMAILEXISTS').'<br/><a href="index.php?option=com_vikrentitems&task=editcustomer&cid[]='.$ex_customer['id'].'" target="_blank">'.$ex_customer['first_name'].' '.$ex_customer['last_name'].'</a>');
					$mainframe->redirect("index.php?option=com_vikrentitems&task=editcustomer&cid[]=".$pwhere);
					exit;
			}
		}
		if ($stay) {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=editcustomer&cid[]=".$pwhere);
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=customers");
		}
	}

	function cancelcustomer() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=customers");
	}

	function searchcustomer() {
		//to be called via ajax
		$kw = VikRequest::getString('kw', '', 'request');
		$nopin = VikRequest::getInt('nopin', '', 'request');
		$email = VikRequest::getInt('email', 0, 'request');
		$cstring = '';
		if (strlen($kw) > 0) {
			$dbo = JFactory::getDbo();
			if ($nopin > 0) {
				//page all bookings
				$q = "SELECT * FROM `#__vikrentitems_customers` WHERE CONCAT_WS(' ', `first_name`, `last_name`) LIKE ".$dbo->quote("%".$kw."%")." OR `email` LIKE ".$dbo->quote("%".$kw."%")." ORDER BY `first_name` ASC LIMIT 30;";
			} elseif ($email > 0) {
				// page calendar for checking if an email exists
				$q = "SELECT `first_name`, `last_name`, `email` FROM `#__vikrentitems_customers` WHERE `email`=".$dbo->quote($kw).";";
			} else {
				//page calendar
				$q = "SELECT * FROM `#__vikrentitems_customers` WHERE CONCAT_WS(' ', `first_name`, `last_name`) LIKE ".$dbo->quote("%".$kw."%")." OR `email` LIKE ".$dbo->quote("%".$kw."%")." OR `pin` LIKE ".$dbo->quote("%".$kw."%")." ORDER BY `first_name` ASC;";
			}
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$customers = $dbo->loadAssocList();
				$cust_old_fields = array();
				$cstring_search = '<div class="vri-custsearchres-inner">' . "\n";
				foreach ($customers as $k => $v) {
					$cstring_search .= '<div class="vri-custsearchres-entry" data-custid="'.$v['id'].'" data-email="'.$v['email'].'" data-phone="'.addslashes($v['phone']).'" data-country="'.$v['country'].'" data-pin="'.$v['pin'].'" data-firstname="'.addslashes($v['first_name']).'" data-lastname="'.addslashes($v['last_name']).'">'."\n";
					$cstring_search .= '<span class="vri-custsearchres-cflag">';
					if (is_file(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'countries'.DIRECTORY_SEPARATOR.$v['country'].'.png')) {
						$cstring_search .= '<img src="'.VRI_ADMIN_URI.'resources/countries/'.$v['country'].'.png'.'" title="'.$v['country'].'" class="vri-country-flag"/>'."\n";
					} else {
						$cstring_search .= '<i class="' . VikRentItemsIcons::i('globe') . '"></i>';
					}
					$cstring_search .= '</span>';
					$cstring_search .= '<span class="vri-custsearchres-name" title="'.$v['email'].'">'.$v['first_name'].' '.$v['last_name'].'</span>'."\n";
					if (!($nopin > 0)) {
						$cstring_search .= '<span class="vri-custsearchres-pin">'.$v['pin'].'</span>'."\n";
					}
					$cstring_search .= '</div>'."\n";
					if (!empty($v['cfields'])) {
						$oldfields = json_decode($v['cfields'], true);
						if (is_array($oldfields) && count($oldfields)) {
							$cust_old_fields[$v['id']] = $oldfields;
						}
					}
				}
				$cstring_search .= '</div>'."\n";
				$cstring = json_encode(array(($nopin > 0 ? '' : $cust_old_fields), $cstring_search));
			}
		}
		echo $cstring;
		exit;
	}

	function exportcustomers() {
		//we do not set the menu for this view
	
		VikRequest::setVar('view', VikRequest::getCmd('view', 'exportcustomers'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function exportcustomerslaunch() {
		$cid = VikRequest::getVar('cid', array(0));
		$dbo = JFactory::getDbo();
		$pnotes = VikRequest::getInt('notes', '', 'request');
		$pscanimg = VikRequest::getInt('scanimg', '', 'request');
		$ppin = VikRequest::getInt('pin', '', 'request');
		$pcountry = VikRequest::getString('country', '', 'request');
		$pfromdate = VikRequest::getString('fromdate', '', 'request');
		$ptodate = VikRequest::getString('todate', '', 'request');
		$pdatefilt = VikRequest::getInt('datefilt', '', 'request');
		$clauses = array();
		if (count($cid) > 0 && !empty($cid[0])) {
			$clauses[] = "`c`.`id` IN (".implode(', ', $cid).")";
		}
		if (!empty($pcountry)) {
			$clauses[] = "`c`.`country`=".$dbo->quote($pcountry);
		}
		$datescol = '`bk`.`ts`';
		if ($pdatefilt > 0) {
			if ($pdatefilt == 1) {
				$datescol = '`bk`.`ts`';
			} elseif ($pdatefilt == 2) {
				$datescol = '`bk`.`ritiro`';
			} elseif ($pdatefilt == 3) {
				$datescol = '`bk`.`consegna`';
			}
		}
		if (!empty($pfromdate)) {
			$from_ts = VikRentItems::getDateTimestamp($pfromdate, 0, 0);
			$clauses[] = $datescol.">=".$from_ts;
		}
		if (!empty($ptodate)) {
			$to_ts = VikRentItems::getDateTimestamp($ptodate, 23, 59);
			$clauses[] = $datescol."<=".$to_ts;
		}
		//this query below is safe with the error #1055 when sql_mode=only_full_group_by
		$q = "SELECT `c`.`id`,`c`.`first_name`,`c`.`last_name`,`c`.`email`,`c`.`phone`,`c`.`country`,`c`.`cfields`,`c`.`pin`,`c`.`ujid`,`c`.`address`,`c`.`city`,`c`.`zip`,`c`.`doctype`,`c`.`docnum`,`c`.`docimg`,`c`.`notes`,`c`.`company`,`c`.`vat`,`c`.`gender`,`c`.`bdate`,`c`.`pbirth`,".
			"(SELECT COUNT(*) FROM `#__vikrentitems_customers_orders` AS `co` WHERE `co`.`idcustomer`=`c`.`id`) AS `tot_bookings`,".
			"`cy`.`country_3_code`,`cy`.`country_name` ".
			"FROM `#__vikrentitems_customers` AS `c` LEFT JOIN `#__vikrentitems_countries` `cy` ON `cy`.`country_3_code`=`c`.`country` ".
			"LEFT JOIN `#__vikrentitems_customers_orders` `co` ON `co`.`idcustomer`=`c`.`id` ".
			"LEFT JOIN `#__vikrentitems_orders` `bk` ON `bk`.`id`=`co`.`idorder`".
			(count($clauses) > 0 ? " WHERE ".implode(' AND ', $clauses) : "")." 
			GROUP BY `c`.`id`,`c`.`first_name`,`c`.`last_name`,`c`.`email`,`c`.`phone`,`c`.`country`,`c`.`cfields`,`c`.`pin`,`c`.`ujid`,`c`.`address`,`c`.`city`,`c`.`zip`,`c`.`doctype`,`c`.`docnum`,`c`.`docimg`,`c`.`notes`,`c`.`company`,`c`.`vat`,`c`.`gender`,`c`.`bdate`,`c`.`pbirth`,`cy`.`country_3_code`,`cy`.`country_name` ".
			"ORDER BY `c`.`last_name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if (!($dbo->getNumRows() > 0)) {
			VikError::raiseWarning('', JText::translate('VRINORECORDSCSVCUSTOMERS'));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=com_vikrentitems&task=customers");
			exit;
		}
		$customers = $dbo->loadAssocList();
		$csvlines = array();
		$csvheadline = array('ID', JText::translate('VRCUSTOMERLASTNAME'), JText::translate('VRCUSTOMERFIRSTNAME'), JText::translate('VRCUSTOMEREMAIL'), JText::translate('VRCUSTOMERPHONE'), JText::translate('VRCUSTOMERADDRESS'), JText::translate('VRCUSTOMERCITY'), JText::translate('VRCUSTOMERZIP'), JText::translate('VRCUSTOMERCOUNTRY'), JText::translate('VRCUSTOMERTOTBOOKINGS'));
		if ($ppin > 0) {
			$csvheadline[] = JText::translate('VRCUSTOMERPIN');
		}
		if ($pscanimg > 0) {
			$csvheadline[] = JText::translate('VRCUSTOMERDOCTYPE');
			$csvheadline[] = JText::translate('VRCUSTOMERDOCNUM');
			$csvheadline[] = JText::translate('VRCUSTOMERDOCIMG');
		}
		if ($pnotes > 0) {
			$csvheadline[] = JText::translate('VRCUSTOMERNOTES');
		}
		$csvlines[] = $csvheadline;
		foreach ($customers as $customer) {
			$csvcustomerline = array($customer['id'], $customer['last_name'], $customer['first_name'], $customer['email'], $customer['phone'], $customer['address'], $customer['city'], $customer['zip'], $customer['country_name'], $customer['tot_bookings']);
			if ($ppin > 0) {
				$csvcustomerline[] = $customer['pin'];
			}
			if ($pscanimg > 0) {
				$csvcustomerline[] = $customer['doctype'];
				$csvcustomerline[] = $customer['docnum'];
				$csvcustomerline[] = (!empty($customer['docimg']) ? VRI_ADMIN_URI.'resources/idscans/'.$customer['docimg'] : '');
			}
			if ($pnotes > 0) {
				$csvcustomerline[] = $customer['notes'];
			}	
			$csvlines[] = $csvcustomerline;
		}
		header("Content-type: text/csv");
		header("Cache-Control: no-store, no-cache");
		header('Content-Disposition: attachment; filename="customers_export_'.(!empty($pcountry) ? strtolower($pcountry).'_' : '').date('Y-m-d').'.csv"');
		$outstream = fopen("php://output", 'w');
		foreach ($csvlines as $csvline) {
			fputcsv($outstream, $csvline);
		}
		fclose($outstream);
		exit;
	}

	function sendcustomemail() {
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$vri_tn = VikRentItems::getTranslator();
		$pbid = VikRequest::getInt('bid', '', 'request');
		$pemailsubj = VikRequest::getString('emailsubj', '', 'request');
		$pemail = VikRequest::getString('email', '', 'request');
		$pemailcont = VikRequest::getString('emailcont', '', 'request', VIKREQUEST_ALLOWRAW);
		$pemailfrom = VikRequest::getString('emailfrom', '', 'request');
		$pgoto = VikRequest::getString('goto', '', 'request', VIKREQUEST_ALLOWRAW);
		$pgoto = !empty($pgoto) ? urldecode($pgoto) : 'index.php?option=com_vikrentitems';
		if (!empty($pemail) && !empty($pemailcont)) {
			$email_attach = null;
			jimport('joomla.filesystem.file');
			$pemailattch = VikRequest::getVar('emailattch', null, 'files', 'array');
			if (isset($pemailattch) && strlen(trim($pemailattch['name']))) {
				$filename = JFile::makeSafe(str_replace(" ", "_", strtolower($pemailattch['name'])));
				$src = $pemailattch['tmp_name'];
				$dest = VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR;
				$j = "";
				if (file_exists($dest.$filename)) {
					$j = rand(171, 1717);
					while (file_exists($dest.$j.$filename)) {
						$j++;
					}
				}
				$finaldest = $dest.$j.$filename;
				if (VikRentItems::uploadFile($src, $finaldest)) {
					$email_attach = $finaldest;
				} else {
					VikError::raiseWarning('', 'Error uploading the attachment. Email not sent.');
					$mainframe->redirect($pgoto);
					exit;
				}
			}
			//VRI 1.6 - special tags for the custom email template files and messages
			$orig_mail_cont = $pemailcont;
			if (strpos($pemailcont, '{') !== false && strpos($pemailcont, '}') !== false) {
				$order = array();
				$q = "SELECT `o`.*,`co`.`idcustomer`,CONCAT_WS(' ',`c`.`first_name`,`c`.`last_name`) AS `customer_name`,`c`.`pin` AS `customer_pin`,`nat`.`country_name` FROM `#__vikrentitems_orders` AS `o` LEFT JOIN `#__vikrentitems_customers_orders` `co` ON `co`.`idorder`=`o`.`id` AND `co`.`idorder`=".(int)$pbid." LEFT JOIN `#__vikrentitems_customers` `c` ON `c`.`id`=`co`.`idcustomer` LEFT JOIN `#__vikrentitems_countries` `nat` ON `nat`.`country_3_code`=`o`.`country` WHERE `o`.`id`=".(int)$pbid.";";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$order = $dbo->loadAssoc();
					// get order items
					$q = "SELECT `oi`.*,`i`.`name` AS `item_name` FROM `#__vikrentitems_ordersitems` AS `oi` LEFT JOIN `#__vikrentitems_items` `i` ON `oi`.`iditem`=`i`.`id` WHERE `oi`.`idorder`=".$order['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
					if ($dbo->getNumRows() > 0) {
						$order['items'] = $dbo->loadAssocList();
					}
				}
				// parse the special tokens to build the message
				$pemailcont = VikRentItems::parseSpecialTokens($order, $pemailcont);
			}
			//
			$is_html = (strpos($pemailcont, '<') !== false && strpos($pemailcont, '>') !== false);
			$pemailcont = $is_html ? nl2br($pemailcont) : $pemailcont;
			$vri_app = VikRentItems::getVriApplication();
			$vri_app->sendMail($pemailfrom, $pemailfrom, $pemail, $pemailfrom, $pemailsubj, $pemailcont, $is_html, 'base64', $email_attach);
			$mainframe->enqueueMessage(JText::translate('VRSENDEMAILOK'));
			if ($email_attach !== null) {
				@unlink($email_attach);
			}
			//Save email template for future sending
			$config_rec_exists = false;
			$emtpl = array(
				'emailsubj' => $pemailsubj,
				'emailcont' => $orig_mail_cont,
				'emailfrom' => $pemailfrom
			);
			$cur_emtpl = array();
			$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='customemailtpls';";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$config_rec_exists = true;
				$cur_emtpl = $dbo->loadResult();
				$cur_emtpl = empty($cur_emtpl) ? array() : json_decode($cur_emtpl, true);
				$cur_emtpl = is_array($cur_emtpl) ? $cur_emtpl : array();
			}
			if (count($cur_emtpl) > 0) {
				$existing_subj = false;
				foreach ($cur_emtpl as $emk => $emv) {
					if (array_key_exists('emailsubj', $emv) && $emv['emailsubj'] == $emtpl['emailsubj']) {
						$cur_emtpl[$emk] = $emtpl;
						$existing_subj = true;
						break;
					}
				}
				if ($existing_subj === false) {
					$cur_emtpl[] = $emtpl;
				}
			} else {
				$cur_emtpl[] = $emtpl;
			}
			if (count($cur_emtpl) > 10) {
				//Max 10 templates to avoid problems with the size of the field and truncated json strings
				$exceed = count($cur_emtpl) - 10;
				for ($tl=0; $tl < $exceed; $tl++) { 
					unset($cur_emtpl[$tl]);
				}
				$cur_emtpl = array_values($cur_emtpl);
			}
			if ($config_rec_exists === true) {
				$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote(json_encode($cur_emtpl))." WHERE `param`='customemailtpls';";
				$dbo->setQuery($q);
				$dbo->execute();
			} else {
				$q = "INSERT INTO `#__vikrentitems_config` (`param`,`setting`) VALUES ('customemailtpls', ".$dbo->quote(json_encode($cur_emtpl)).");";
				$dbo->setQuery($q);
				$dbo->execute();
			}
			//
		} else {
			VikError::raiseWarning('', JText::translate('VRSENDEMAILERRMISSDATA'));
		}
		$mainframe->redirect($pgoto);
	}

	function rmcustomemailtpl() {
		$cid = VikRequest::getVar('cid', array(0));
		$oid = $cid[0];
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$tplind = VikRequest::getInt('tplind', '', 'request');
		if (empty($oid) || !(strlen($tplind) > 0)) {
			VikError::raiseWarning('', 'Missing Data.');
			$mainframe->redirect('index.php?option=com_vikrentitems');
			exit;
		}
		$cur_emtpl = array();
		$q = "SELECT `setting` FROM `#__vikrentitems_config` WHERE `param`='customemailtpls';";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$cur_emtpl = $dbo->loadResult();
			$cur_emtpl = empty($cur_emtpl) ? array() : json_decode($cur_emtpl, true);
			$cur_emtpl = is_array($cur_emtpl) ? $cur_emtpl : array();
		} else {
			VikError::raiseWarning('', 'Missing Templates Record.');
			$mainframe->redirect('index.php?option=com_vikrentitems');
			exit;
		}
		if (array_key_exists($tplind, $cur_emtpl)) {
			unset($cur_emtpl[$tplind]);
			$cur_emtpl = count($cur_emtpl) > 0 ? array_values($cur_emtpl) : array();
			$q = "UPDATE `#__vikrentitems_config` SET `setting`=".$dbo->quote(json_encode($cur_emtpl))." WHERE `param`='customemailtpls';";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		$mainframe->redirect('index.php?option=com_vikrentitems&task=editorder&cid[]='.$oid.'&customemail=1');
		exit;
	}

	function ratesoverv() {
		VikRentItemsHelper::printHeader("ratesoverv");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'ratesoverv'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function restrictions() {
		VikRentItemsHelper::printHeader("restrictions");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'restrictions'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function newrestriction() {
		VikRentItemsHelper::printHeader("restrictions");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managerestriction'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editrestriction() {
		VikRentItemsHelper::printHeader("restrictions");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managerestriction'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function createrestriction() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$session = JFactory::getSession();
		$pname = VikRequest::getString('name', '', 'request');
		$pmonth = VikRequest::getInt('month', '', 'request');
		$pmonth = empty($pmonth) ? 0 : $pmonth;
		$pname = empty($pname) ? 'Restriction '.$pmonth : $pname;
		$pdfrom = VikRequest::getString('dfrom', '', 'request');
		$pdto = VikRequest::getString('dto', '', 'request');
		$pwday = VikRequest::getString('wday', '', 'request');
		$pwdaytwo = VikRequest::getString('wdaytwo', '', 'request');
		$pwdaytwo = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday == $pwdaytwo ? '' : $pwdaytwo;
		$pcomboa = VikRequest::getString('comboa', '', 'request');
		$pcomboa = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcomboa : '';
		$pcombob = VikRequest::getString('combob', '', 'request');
		$pcombob = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcombob : '';
		$pcomboc = VikRequest::getString('comboc', '', 'request');
		$pcomboc = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcomboc : '';
		$pcombod = VikRequest::getString('combod', '', 'request');
		$pcombod = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcombod : '';
		$combostr = '';
		$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcomboa) ? $pcomboa.':' : ':';
		$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcombob) ? $pcombob.':' : ':';
		$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcomboc) ? $pcomboc.':' : ':';
		$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcombod) ? $pcombod : '';
		$pminlos = VikRequest::getInt('minlos', '', 'request');
		$pminlos = $pminlos < 1 ? 1 : $pminlos;
		$pmaxlos = VikRequest::getInt('maxlos', '', 'request');
		$pmaxlos = empty($pmaxlos) ? 0 : $pmaxlos;
		$pmultiplyminlos = VikRequest::getString('multiplyminlos', '', 'request');
		$pmultiplyminlos = empty($pmultiplyminlos) ? 0 : 1;
		$pallitems = VikRequest::getString('allitems', '', 'request');
		$pallitems = $pallitems == "1" ? 1 : 0;
		$piditems = VikRequest::getVar('iditems', array(0));
		$ridr = '';
		$itemidsforsess = array();
		if (!empty($piditems) && @count($piditems) && $pallitems == 0) {
			foreach ($piditems as $idr) {
				if (empty($idr)) {
					continue;
				}
				$ridr .= '-'.$idr.'-;';
				$itemidsforsess[] = (int)$idr;
			}
		} elseif ($pallitems > 0) {
			$q = "SELECT `id` FROM `#__vikrentitems_items`;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$fetchids = $dbo->loadAssocList();
				foreach ($fetchids as $fetchid) {
					$itemidsforsess[] = (int)$fetchid['id'];
				}
			}
		}
		$pcta = VikRequest::getInt('cta', '', 'request');
		$pctd = VikRequest::getInt('ctd', '', 'request');
		$pctad = VikRequest::getVar('ctad', array());
		$pctdd = VikRequest::getVar('ctdd', array());
		if ($pminlos == 1 && strlen($pwday) == 0 && empty($pctad) && empty($pctdd)) {
			VikError::raiseWarning('', JText::translate('VRUSELESSRESTRICTION'));
			$mainframe->redirect("index.php?option=com_vikrentitems&task=newrestriction");
		} else {
			//check if there are restrictions for this month
			if ($pmonth > 0) {
				$q = "SELECT `id` FROM `#__vikrentitems_restrictions` WHERE `month`='".$pmonth."';";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					VikError::raiseWarning('', JText::translate('VRRESTRICTIONMONTHEXISTS'));
					$mainframe->redirect("index.php?option=com_vikrentitems&task=newrestriction");
				}
				$pdfrom = 0;
				$pdto = 0;
			} else {
				//dates range
				if (empty($pdfrom) || empty($pdto)) {
					VikError::raiseWarning('', JText::translate('VRRESTRICTIONERRDRANGE'));
					$mainframe->redirect("index.php?option=com_vikrentitems&task=newrestriction");
				} else {
					$pdfrom = VikRentItems::getDateTimestamp($pdfrom, 0, 0);
					$pdto = VikRentItems::getDateTimestamp($pdto, 0, 0);
				}
			}
			//CTA and CTD
			$setcta = array();
			$setctd = array();
			if ($pcta > 0 && count($pctad) > 0) {
				foreach ($pctad as $ctwd) {
					if (strlen($ctwd)) {
						$setcta[] = '-'.(int)$ctwd.'-';
					}
				}
			}
			if ($pctd > 0 && count($pctdd) > 0) {
				foreach ($pctdd as $ctwd) {
					if (strlen($ctwd)) {
						$setctd[] = '-'.(int)$ctwd.'-';
					}
				}
			}
			//
			$q = "INSERT INTO `#__vikrentitems_restrictions` (`name`,`month`,`wday`,`minlos`,`multiplyminlos`,`maxlos`,`dfrom`,`dto`,`wdaytwo`,`wdaycombo`,`allitems`,`iditems`,`ctad`,`ctdd`) VALUES(".$dbo->quote($pname).", '".$pmonth."', ".(strlen($pwday) > 0 ? "'".$pwday."'" : "NULL").", '".$pminlos."', '".$pmultiplyminlos."', '".$pmaxlos."', ".$pdfrom.", ".$pdto.", ".(strlen($pwday) > 0 && strlen($pwdaytwo) > 0 ? intval($pwdaytwo) : "NULL").", ".(strlen($combostr) > 0 ? $dbo->quote($combostr) : "NULL").", ".$pallitems.", ".(strlen($ridr) > 0 ? $dbo->quote($ridr) : "NULL").", ".(count($setcta) > 0 ? $dbo->quote(implode(',', $setcta)) : "NULL").", ".(count($setctd) > 0 ? $dbo->quote(implode(',', $setctd)) : "NULL").");";
			$dbo->setQuery($q);
			$dbo->execute();
			$lid = $dbo->insertid();
			if (!empty($lid)) {
				/**
				 * Repeat restriction on the selected week days until the limit
				 * 
				 * @since 	1.14
				 */
				$prepeat = VikRequest::getInt('repeat', 0, 'request');
				$prepeatuntil = VikRequest::getString('repeatuntil', '', 'request');
				if ($prepeat > 0 && !empty($prepeatuntil) && $pdfrom > 0 && $pdto > 0) {
					$repeat_intervals = array();
					$start = getdate($pdfrom);
					$end = getdate($pdto);
					$wdays = array();
					while ($start[0] <= $end[0]) {
						// push requested week day
						array_push($wdays, $start['wday']);
						// next day
						$start = getdate(mktime($start['hours'], $start['minutes'], $start['seconds'], $start['mon'], ($start['mday'] + 1), $start['year']));
					}
					$dtuntil = VikRentItems::getDateTimestamp($prepeatuntil, 23, 59, 59);
					if (count($wdays) < 7 && $dtuntil > $pdto) {
						// increment end date for the repeat
						$end = getdate(mktime($end['hours'], $end['minutes'], $end['seconds'], $end['mon'], ($end['mday'] + 1), $end['year']));
						//
						$until_info = getdate($dtuntil);
						$interval = array();
						while ($end[0] <= $until_info[0]) {
							if (in_array($end['wday'], $wdays)) {
								if (!isset($interval['from'])) {
									$interval['from'] = $end[0];
								}
								$interval['to'] = $end[0];
							} else {
								if (isset($interval['from'])) {
									// append interval
									array_push($repeat_intervals, $interval);
									// reset interval
									$interval = array();
								}
							}
							// next day
							$end = getdate(mktime($end['hours'], $end['minutes'], $end['seconds'], $end['mon'], ($end['mday'] + 1), $end['year']));
						}
						if (isset($interval['from'])) {
							// append last hanging interval
							array_push($repeat_intervals, $interval);
						}
						if (count($repeat_intervals)) {
							// create the repeated records for the calculated intervals
							$repeat_count = 2;
							foreach ($repeat_intervals as $rp) {
								if (date('Y-m-d', $rp['from']) == date('Y-m-d', $rp['to'])) {
									// adjust time in case of equal dates (1 single day restriction)
									$rpfrom = getdate($rp['from']);
									$rpto = getdate($rp['to']);
									$rp['from'] = mktime(0, 0, 0, $rpfrom['mon'], $rpfrom['mday'], $rpfrom['year']);
									$rp['to'] = mktime(0, 0, 0, $rpto['mon'], $rpto['mday'], $rpto['year']);
								}
								// adjust name
								$restr_rp_name = $pname . " #{$repeat_count}";
								//
								$q = "INSERT INTO `#__vikrentitems_restrictions` (`name`,`month`,`wday`,`minlos`,`multiplyminlos`,`maxlos`,`dfrom`,`dto`,`wdaytwo`,`wdaycombo`,`allitems`,`iditems`,`ctad`,`ctdd`) VALUES(".$dbo->quote($restr_rp_name).", '".$pmonth."', ".(strlen($pwday) > 0 ? "'".$pwday."'" : "NULL").", '".$pminlos."', '".$pmultiplyminlos."', '".$pmaxlos."', ".$rp['from'].", ".$rp['to'].", ".(strlen($pwday) > 0 && strlen($pwdaytwo) > 0 ? intval($pwdaytwo) : "NULL").", ".(strlen($combostr) > 0 ? $dbo->quote($combostr) : "NULL").", ".$pallitems.", ".(strlen($ridr) > 0 ? $dbo->quote($ridr) : "NULL").", ".(count($setcta) > 0 ? $dbo->quote(implode(',', $setcta)) : "NULL").", ".(count($setctd) > 0 ? $dbo->quote(implode(',', $setctd)) : "NULL").");";
								$dbo->setQuery($q);
								$dbo->execute();
								$lid = $dbo->insertid();
								if (!empty($lid)) {
									$repeat_count++;
								}
							}
						}
					}
				}
				//
				$mainframe->enqueueMessage(JText::translate('VRRESTRICTIONSAVED'));
				$mainframe->redirect("index.php?option=com_vikrentitems&task=restrictions");
			} else {
				VikError::raiseWarning('', 'Error while saving');
				$mainframe->redirect("index.php?option=com_vikrentitems&task=newrestriction");
			}
		}
	}

	function updaterestriction() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$session = JFactory::getSession();
		$pwhere = VikRequest::getInt('where', '', 'request');
		$pname = VikRequest::getString('name', '', 'request');
		$pmonth = VikRequest::getInt('month', '', 'request');
		$pmonth = empty($pmonth) ? 0 : $pmonth;
		$pname = empty($pname) ? 'Restriction '.$pmonth : $pname;
		$pdfrom = VikRequest::getString('dfrom', '', 'request');
		$pdto = VikRequest::getString('dto', '', 'request');
		$pwday = VikRequest::getString('wday', '', 'request');
		$pwdaytwo = VikRequest::getString('wdaytwo', '', 'request');
		$pwdaytwo = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday == $pwdaytwo ? '' : $pwdaytwo;
		$pcomboa = VikRequest::getString('comboa', '', 'request');
		$pcomboa = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcomboa : '';
		$pcombob = VikRequest::getString('combob', '', 'request');
		$pcombob = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcombob : '';
		$pcomboc = VikRequest::getString('comboc', '', 'request');
		$pcomboc = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcomboc : '';
		$pcombod = VikRequest::getString('combod', '', 'request');
		$pcombod = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcombod : '';
		$combostr = '';
		$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcomboa) ? $pcomboa.':' : ':';
		$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcombob) ? $pcombob.':' : ':';
		$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcomboc) ? $pcomboc.':' : ':';
		$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcombod) ? $pcombod : '';
		$pminlos = VikRequest::getInt('minlos', '', 'request');
		$pminlos = $pminlos < 1 ? 1 : $pminlos;
		$pmaxlos = VikRequest::getInt('maxlos', '', 'request');
		$pmaxlos = empty($pmaxlos) ? 0 : $pmaxlos;
		$pmultiplyminlos = VikRequest::getString('multiplyminlos', '', 'request');
		$pmultiplyminlos = empty($pmultiplyminlos) ? 0 : 1;
		$pallitems = VikRequest::getString('allitems', '', 'request');
		$pallitems = $pallitems == "1" ? 1 : 0;
		$piditems = VikRequest::getVar('iditems', array(0));
		$ridr = '';
		$itemidsforsess = array();
		if (!empty($piditems) && @count($piditems) && $pallitems == 0) {
			foreach ($piditems as $idr) {
				if (empty($idr)) {
					continue;
				}
				$ridr .= '-'.$idr.'-;';
				$itemidsforsess[] = (int)$idr;
			}
		} elseif ($pallitems > 0) {
			$q = "SELECT `id` FROM `#__vikrentitems_items`;";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$fetchids = $dbo->loadAssocList();
				foreach ($fetchids as $fetchid) {
					$itemidsforsess[] = (int)$fetchid['id'];
				}
			}
		}
		$pcta = VikRequest::getInt('cta', '', 'request');
		$pctd = VikRequest::getInt('ctd', '', 'request');
		$pctad = VikRequest::getVar('ctad', array());
		$pctdd = VikRequest::getVar('ctdd', array());
		if ($pminlos == 1 && strlen($pwday) == 0 && empty($pctad) && empty($pctdd)) {
			VikError::raiseWarning('', JText::translate('VRUSELESSRESTRICTION'));
			$mainframe->redirect("index.php?option=com_vikrentitems&task=editrestriction&cid[]=".$pwhere);
		} else {
			//check if there are restrictions for this month
			if ($pmonth > 0) {
				$q = "SELECT `id` FROM `#__vikrentitems_restrictions` WHERE `month`='".$pmonth."' AND `id`!='".$pwhere."';";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					VikError::raiseWarning('', JText::translate('VRRESTRICTIONMONTHEXISTS'));
					$mainframe->redirect("index.php?option=com_vikrentitems&task=editrestriction&cid[]=".$pwhere);
				}
				$pdfrom = 0;
				$pdto = 0;
			} else {
				//dates range
				if (empty($pdfrom) || empty($pdto)) {
					VikError::raiseWarning('', JText::translate('VRRESTRICTIONERRDRANGE'));
					$mainframe->redirect("index.php?option=com_vikrentitems&task=editrestriction&cid[]=".$pwhere);
				} else {
					$pdfrom = VikRentItems::getDateTimestamp($pdfrom, 0, 0);
					$pdto = VikRentItems::getDateTimestamp($pdto, 0, 0);
				}
			}
			//CTA and CTD
			$setcta = array();
			$setctd = array();
			if ($pcta > 0 && count($pctad) > 0) {
				foreach ($pctad as $ctwd) {
					if (strlen($ctwd)) {
						$setcta[] = '-'.(int)$ctwd.'-';
					}
				}
			}
			if ($pctd > 0 && count($pctdd) > 0) {
				foreach ($pctdd as $ctwd) {
					if (strlen($ctwd)) {
						$setctd[] = '-'.(int)$ctwd.'-';
					}
				}
			}
			//
			$q = "UPDATE `#__vikrentitems_restrictions` SET `name`=".$dbo->quote($pname).",`month`='".$pmonth."',`wday`=".(strlen($pwday) > 0 ? "'".$pwday."'" : "NULL").",`minlos`='".$pminlos."',`multiplyminlos`='".$pmultiplyminlos."',`maxlos`='".$pmaxlos."',`dfrom`=".$pdfrom.",`dto`=".$pdto.",`wdaytwo`=".(strlen($pwday) > 0 && strlen($pwdaytwo) > 0 ? intval($pwdaytwo) : "NULL").",`wdaycombo`=".(strlen($combostr) > 0 ? $dbo->quote($combostr) : "NULL").",`allitems`=".$pallitems.",`iditems`=".(strlen($ridr) > 0 ? $dbo->quote($ridr) : "NULL").", `ctad`=".(count($setcta) > 0 ? $dbo->quote(implode(',', $setcta)) : "NULL").", `ctdd`=".(count($setctd) > 0 ? $dbo->quote(implode(',', $setctd)) : "NULL")." WHERE `id`='".$pwhere."';";
			$dbo->setQuery($q);
			$dbo->execute();
			$mainframe->enqueueMessage(JText::translate('VRRESTRICTIONSAVED'));
			$mainframe->redirect("index.php?option=com_vikrentitems&task=restrictions");
		}
	}

	function removerestrictions() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "DELETE FROM `#__vikrentitems_restrictions` WHERE `id`=".(int)$d.";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=restrictions");
	}

	function cancelrestriction() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=restrictions");
	}

	function setnewrates() {
		// @wponly lite - the seasonal prices function is not available with this version of the framework
		echo 'e4j.error.'.__('These Pricing Model and functions are only available with the Pro version.', 'vikrentitems');
		exit;
	}

	function moditemrateplans() {
		$dbo = JFactory::getDbo();
		$pid_item = VikRequest::getInt('id_item', '', 'request');
		$pid_price = VikRequest::getInt('id_price', '', 'request');
		$ptype = VikRequest::getString('type', '', 'request');
		$pfromdate = VikRequest::getString('fromdate', '', 'request');
		$ptodate = VikRequest::getString('todate', '', 'request');
		if (empty($pid_item) || empty($pid_price) || empty($ptype) || empty($pfromdate) || empty($ptodate) || !(strtotime($pfromdate) > 0)  || !(strtotime($ptodate) > 0)) {
			echo 'e4j.error.'.addslashes(JText::translate('VRIRATESOVWERRMODRPLANS'));
			exit;
		}
		$price_record = array();
		$q = "SELECT * FROM `#__vikrentitems_prices` WHERE `id`=".$pid_price.";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$price_record = $dbo->loadAssoc();
		}
		if (!count($price_record) > 0) {
			echo 'e4j.error.'.addslashes(JText::translate('VRIRATESOVWERRMODRPLANS')).'.';
			exit;
		}
		$current_closed = array();
		if (!empty($price_record['closingd'])) {
			$current_closed = json_decode($price_record['closingd'], true);
			if (!is_array($current_closed)) {
				$current_closed = array();
			}
		}
		$start_ts = strtotime($pfromdate);
		$end_ts = strtotime($ptodate);
		$infostart = getdate($start_ts);
		$all_days = array();
		$output = array();
		while ($infostart[0] > 0 && $infostart[0] <= $end_ts) {
			$all_days[] = date('Y-m-d', $infostart[0]);
			$indkey = $infostart['mday'].'-'.$infostart['mon'].'-'.$infostart['year'].'-'.$pid_price;
			$output[$indkey] = array();
			$infostart = getdate(mktime(0, 0, 0, $infostart['mon'], ($infostart['mday'] + 1), $infostart['year']));
		}
		if ($ptype == 'close') {
			if (!array_key_exists($pid_item, $current_closed)) {
				$current_closed[$pid_item] = array();
			}
			foreach ($all_days as $daymod) {
				if (!in_array($daymod, $current_closed[$pid_item])) {
					$current_closed[$pid_item][] = $daymod;
				}
			}
		} else {
			//open
			if (array_key_exists($pid_item, $current_closed)) {
				foreach ($all_days as $daymod) {
					if (in_array($daymod, $current_closed[$pid_item])) {
						foreach ($current_closed[$pid_item] as $ck => $cv) {
							if ($daymod == $cv) {
								unset($current_closed[$pid_item][$ck]);
							}
						}
					}
				}
			} else {
				$current_closed[$pid_item] = array();
			}
		}
		if (!count($current_closed[$pid_item]) > 0) {
			unset($current_closed[$pid_item]);
		}
		$q = "UPDATE `#__vikrentitems_prices` SET `closingd`=".(count($current_closed) > 0 ? $dbo->quote(json_encode($current_closed)) : "NULL")." WHERE `id`=".(int)$pid_price.";";
		$dbo->setQuery($q);
		$dbo->execute();
		$oldcsscls = $ptype == 'close' ? 'vri-roverw-rplan-on' : 'vri-roverw-rplan-off';
		$newcsscls = $ptype == 'close' ? 'vri-roverw-rplan-off' : 'vri-roverw-rplan-on';
		foreach ($output as $ok => $ov) {
			$output[$ok] = array('oldcls' => $oldcsscls, 'newcls' => $newcsscls);
		}
		
		$pdebug = VikRequest::getInt('e4j_debug', '', 'request');
		if ($pdebug == 1) {
			echo "e4j.error.\n".print_r($current_closed, true)."\n";
			echo print_r($output, true)."\n\n";
			echo print_r($all_days, true)."\n";
		}
		echo json_encode($output);
		exit;
	}

	function calc_rates() {
		$response = 'e4j.error.ErrorCode(1) Server is blocking the self-request';
		$currencysymb = VikRentItems::getCurrencySymb();
		$vri_df = VikRentItems::getDateFormat();
		$df = $vri_df == "%d/%m/%Y" ? 'd/m/Y' : ($vri_df == "%m/%d/%Y" ? 'm/d/Y' : 'Y/m/d');
		$pcheckinh = 0;
		$pcheckinm = 0;
		$pcheckouth = 0;
		$pcheckoutm = 0;
		$timeopst = VikRentItems::getTimeOpenStore();
		if (is_array($timeopst)) {
			$opent = VikRentItems::getHoursMinutes($timeopst[0]);
			$closet = VikRentItems::getHoursMinutes($timeopst[1]);
			$pcheckinh = $opent[0];
			$pcheckinm = $opent[1];
			// set drop off time equal to pick up time to avoid getting extra days of rent
			$pcheckouth = $pcheckinh;
			$pcheckoutm = $pcheckinm;
		}
		$id_item = VikRequest::getInt('id_item', '', 'request');
		$pickup = VikRequest::getString('pickup', '', 'request');
		$days = VikRequest::getInt('num_days', 1, 'request');
		/**
		 * The page Calendar may call this task via AJAX to obtain information
		 * about the various rate plans and final costs associated.
		 * 
		 * @since 	1.7
		 */
		$only_rates = VikRequest::getInt('only_rates', 0, 'request');
		$units = VikRequest::getInt('units', 1, 'request');
		$checkinfdate = VikRequest::getString('checkinfdate', '', 'request');
		if (!empty($checkinfdate) && empty($pickup)) {
			$pickup = date('Y-m-d', VikRentItems::getDateTimestamp($checkinfdate, 0, 0, 0));
		}
		$price_details = array();
		//
		$pickup_ts = strtotime($pickup);
		if (empty($pickup_ts)) {
			$pickup = date('Y-m-d');
			$pickup_ts = strtotime($pickup);
		}
		$is_dst = date('I', $pickup_ts);
		$dropoff_ts = $pickup_ts;
		for ($i = 1; $i <= $days; $i++) { 
			$dropoff_ts += 86400;
			$is_now_dst = date('I', $dropoff_ts);
			if ($is_dst != $is_now_dst) {
				if ((int)$is_dst == 1) {
					$dropoff_ts += 3600;
				} else {
					$dropoff_ts -= 3600;
				}
				$is_dst = $is_now_dst;
			}
		}
		$checkout = date('Y-m-d', $dropoff_ts);

		$endpoint = JUri::root().'index.php?option=com_vikrentitems&task=search&Itemid=' . VikRentItems::findProperItemIdType(array('vikrentitems'));
		if (defined('ABSPATH')) {
			$endpoint = str_replace(JUri::root(), '', $endpoint);
			$endpoint = JRoute::rewrite($endpoint, false);
		}
		$rates_data = 'e4jauth=%s&getjson=1&pickupdate='.date($df, $pickup_ts).'&pickuph='.$pcheckinh.'&pickupm='.$pcheckinm.'&releasedate='.date($df, $dropoff_ts).'&releaseh='.$pcheckouth.'&releasem='.$pcheckoutm;

		// make the request by using JHttp
		$http = new JHttp();
		$headers = array(
			'Content-Type' => 'application/x-www-form-urlencoded'
		);
		$cua = VikRequest::getString('HTTP_USER_AGENT', '', 'server');
		if (!empty($cua)) {
			$headers['userAgent'] = $cua;
		}
		$result = $http->post($endpoint, sprintf($rates_data, md5('vri.e4j.vri')), $headers);
		if ($result->code != 200) {
			$response = "e4j.error.Communication error ({$result->code}): {$result->body}";
		} else {
			$res = $result->body;
			$arr_res = json_decode($res, true);

			/**
			 * We try to check if decoding was unsuccessful, maybe because the response is mixed with HTML code of the Template/Theme.
			 * In this case we try to extract the JSON string from the plain response to decode only that text.
			 * 
			 * @since 	1.7 (J) - 1.0.0 (WP)
			 */
			if (function_exists('json_last_error') && json_last_error() !== JSON_ERROR_NONE) {
				$pattern = '/\{(?:[^{}]|(?R))*\}/x';
				$matchcount = preg_match_all($pattern, $res, $matches);
				if ($matchcount && isset($matches[0]) && count($matches[0])) {
					// we have found JSON strings inside the raw response, we get the last JSON string
					$arr_res = json_decode($matches[0][(count($matches[0]) - 1)], true);
				}
			}
			//

			if (is_array($arr_res)) {
				if (!array_key_exists('e4j.error', $arr_res)) {
					if (array_key_exists($id_item, $arr_res)) {
						$response = '';
						foreach ($arr_res[$id_item] as $rate) {
							// build pricing object
							$rplan_details = new stdClass;
							$rplan_details->idprice = $rate['idprice'];
							$rplan_details->name = $rate['pricename'];
							$rplan_details->tot = $rate['cost'];
							$rplan_details->ftot = $currencysymb . ' ' . VikRentItems::numberFormat(($rate['cost']));
							array_push($price_details, $rplan_details);
							//
							$extra_response = '';
							$response .= '<div class="vri-calcrates-rateblock" data-idprice="' . $rate['idprice'] . '" data-iditem="' . $id_item . '" data-pickup="' . $pickup . '" data-dropoff="' . $checkout . '">';
							$response .= '<span class="vri-calcrates-ratename">'.$rate['pricename'].'</span>';
							if (array_key_exists('affdays', $rate) && $rate['affdays'] > 0) {
								$extra_response .= '<span class="vri-calcrates-extrapricedet vri-calcrates-ratespaffdays"><span>'.JText::translate('VRICALCRATESSPAFFDAYS').'</span>'.$rate['affdays'].'</span>';
							}
							$tot = round($rate['cost'], 2);
							$response .= '<span class="vri-calcrates-pricedet vri-calcrates-ratetotal"><span>'.JText::translate('VRICALCRATESTOT').'</span>'.$currencysymb.' '.VikRentItems::numberFormat($tot).'</span>';
							if (!empty($extra_response)) {
								$response .= '<div class="vri-calcrates-info">'.$extra_response.'</div>';
							}
							$response .= '</div>';
						}
						//Debug
						//$response .= '<br/><pre>'.print_r($arr_res, true).'</pre><br/>';
					} else {
						$response = 'e4j.error.'.JText::sprintf('VRICALCRATESITEMNOTAVAILCOMBO', date($df, $pickup_ts), date($df, $dropoff_ts));
					}
				} else {
					$response = 'e4j.error.'.$arr_res['e4j.error'];
				}
			} else {
				$response = (strpos($res, 'e4j.error') === false ? 'e4j.error' : '').$res;
			}
		}

		if ($only_rates && strpos($response, 'e4j.error') === false) {
			echo json_encode($price_details);
			exit;
		}
		
		// Do not do only echo trim($response); or the currency symbol may not be encoded on some servers
		echo json_encode(array(trim($response)));
		exit;
	}

	/**
	 * AJAX request made to get the information about certain rental orders.
	 * 
	 * @return 	void
	 * 
	 * @since 	1.7
	 */
	function getordersinfo() {
		$dbo = JFactory::getDbo();
		$booking_infos = array();
		$bookings = array();
		$pidorders = VikRequest::getString('idorders', '', 'request');
		$psubitem = VikRequest::getString('subitem', '', 'request');
		if (!empty($pidorders)) {
			$bookings = explode(',', $pidorders);
			foreach ($bookings as $k => $v) {
				$v = intval(str_replace('-', '', $v));
				if (empty($v)) {
					unset($bookings[$k]);
					continue;
				}
				$bookings[$k] = $v;
			}
		}
		$bookings = array_values($bookings);
		if (!(count($bookings) > 0)) {
			echo 'e4j.error.1 Missing Data';
			exit;
		}
		$nowtf = VikRentItems::getTimeFormat(true);
		$nowdf = VikRentItems::getDateFormat(true);
		if ($nowdf == "%d/%m/%Y") {
			$df = 'd/m/Y';
		} elseif ($nowdf == "%m/%d/%Y") {
			$df = 'm/d/Y';
		} else {
			$df = 'Y/m/d';
		}
		$q = "SELECT `o`.*, `p`.`name` AS `pickup_place` 
			FROM `#__vikrentitems_orders` AS `o` 
			LEFT JOIN `#__vikrentitems_places` `p` ON `p`.`id`=`o`.`idplace` 
			WHERE `o`.`id` IN (".implode(', ', $bookings).");";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$booking_infos = $dbo->loadAssocList();
			foreach ($booking_infos as $k => $row) {
				//items, amounts and guests information
				$items = VikRentItems::loadOrdersItemsData($row['id']);
				$item_names = array();
				foreach ($items as $oi) {
					$item_names[] = $oi['item_name'];
				}
				$booking_infos[$k]['item_names'] = implode(', ', $item_names);
				$booking_infos[$k]['status_lbl'] = ($row['status'] != 'confirmed' && $row['status'] != 'standby' ? $row['status'] : ($row['status'] == 'confirmed' ? JText::translate('VRCONFIRMED') : JText::translate('VRSTANDBY')));
				$booking_infos[$k]['format_tot'] = VikRentItems::numberFormat($row['order_total']);
				$booking_infos[$k]['format_totpaid'] = VikRentItems::numberFormat($row['totpaid']);
				// to avoid using a double left join in the query for the return place name, we use a single query
				$booking_infos[$k]['dropoff_place'] = !empty($row['idreturnplace']) ? VikRentItems::getPlaceName($row['idreturnplace']) : '';
				//Customer Details
				$custdata = $row['custdata'];
				$custdata_parts = explode("\n", $row['custdata']);
				if (count($custdata_parts) > 2 && strpos($custdata_parts[0], ':') !== false && strpos($custdata_parts[1], ':') !== false) {
					//get the first two fields
					$custvalues = array();
					foreach ($custdata_parts as $custdet) {
						if (strlen($custdet) < 1) {
							continue;
						}
						$custdet_parts = explode(':', $custdet);
						if (count($custdet_parts) >= 2) {
							unset($custdet_parts[0]);
							array_push($custvalues, trim(implode(':', $custdet_parts)));
						}
						if (count($custvalues) > 1) {
							break;
						}
					}
					if (count($custvalues) > 1) {
						$custdata = implode(' ', $custvalues);
					}
				}
				if (strlen($custdata) > 45) {
					$custdata = substr($custdata, 0, 45)." ...";
				}

				$q = "SELECT `c`.*,`co`.`idorder` FROM `#__vikrentitems_customers` AS `c` LEFT JOIN `#__vikrentitems_customers_orders` `co` ON `c`.`id`=`co`.`idcustomer` WHERE `co`.`idorder`=".$row['id'].";";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() > 0) {
					$cust_country = $dbo->loadAssocList();
					$cust_country = $cust_country[0];
					if (!empty($cust_country['first_name'])) {
						$custdata = $cust_country['first_name'].' '.$cust_country['last_name'];
						if (!empty($cust_country['country'])) {
							if (is_file(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'countries'.DIRECTORY_SEPARATOR.$cust_country['country'].'.png')) {
								$custdata .= '<img src="'.VRI_ADMIN_URI.'resources/countries/'.$cust_country['country'].'.png'.'" title="'.$cust_country['country'].'" class="vri-country-flag vri-country-flag-left"/>';
							}
						}
					}
				}
				$custdata = JText::translate('VRDBTEXTROOMCLOSED') == $row['custdata'] ? '<span class="vriordersitemclosed">'.JText::translate('VRDBTEXTROOMCLOSED').'</span>' : $custdata;
				$booking_infos[$k]['cinfo'] = $custdata;
				//Formatted dates
				$booking_infos[$k]['ts'] = date($df . ' ' . $nowtf, $row['ts']);
				$booking_infos[$k]['pickup'] = date($df . ' ' . $nowtf, $row['ritiro']);
				$booking_infos[$k]['dropoff'] = date($df . ' ' . $nowtf, $row['consegna']);
			}
		}
		if (!(count($booking_infos) > 0)) {
			echo 'e4j.error.2 Missing Data';
			exit;
		}

		echo json_encode($booking_infos);
		exit;
	}

	function newcron() {
		VikRentItemsHelper::printHeader("crons");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managecron'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editcron() {
		VikRentItemsHelper::printHeader("crons");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'managecron'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function crons() {
		VikRentItemsHelper::printHeader("crons");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'crons'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function cancelcrons() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=crons");
	}

	function cron_exec() {
		//modal box, so we do not set menu or footer
	
		VikRequest::setVar('view', VikRequest::getCmd('view', 'cronexec'));
	
		parent::display();
	}

	function downloadcron() {
		/**
		 * @wponly lite - Cron Jobs are not needed in Vik Rent Items, we will implement them through WPCron later on.
		 */
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems");
		//
	}

	function cronlogs() {
		$dbo = JFactory::getDbo();
		$pcron_id = VikRequest::getInt('cron_id', '', 'request');
		$q = "SELECT * FROM `#__vikrentitems_cronjobs` WHERE `id`=".(int)$pcron_id.";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() == 1) {
			$cron_data = $dbo->loadAssoc();
			$cron_data['logs'] = empty($cron_data['logs']) ? '--------' : $cron_data['logs'];
			echo '<pre>'.print_r($cron_data['logs'], true).'</pre>';
		}
	}

	function updatecron() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$this->do_updatecron();
	}

	function updatecronstay() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$this->do_updatecron(true);
	}

	private function do_updatecron($stay = false) {
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$pwhereup = VikRequest::getInt('where', '', 'request');
		$pcron_name = VikRequest::getString('cron_name', '', 'request');
		$pclass_file = VikRequest::getString('class_file', '', 'request');
		$ppublished = VikRequest::getString('published', '', 'request');
		$ppublished = intval($ppublished) == 1 ? 1 : 0;
		$vikcronparams = VikRequest::getVar('vikcronparams', array(), 'request', 'none', VIKREQUEST_ALLOWHTML);
		$cronparamarr = array();
		$cronparamstr = '';
		if (count($vikcronparams) > 0) {
			foreach ($vikcronparams as $setting => $cont) {
				if (strlen($setting) > 0) {
					$cronparamarr[$setting] = $cont;
				}
			}
			if (count($cronparamarr) > 0) {
				$cronparamstr = json_encode($cronparamarr);
			}
		}
		$goto = "index.php?option=com_vikrentitems&task=crons";
		if (empty($pcron_name) || empty($pclass_file) || empty($pwhereup)) {
			$mainframe->redirect($goto);
			exit;
		}
		//launch update() method if available
		if (file_exists(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'cronjobs'.DIRECTORY_SEPARATOR.$pclass_file)) {
			require_once(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'cronjobs'.DIRECTORY_SEPARATOR.$pclass_file);
			if (method_exists('VikCronJob', 'update')) {
				$cron_obj = new VikCronJob($pwhereup, $cronparamarr);
				$cron_obj->update();
			}
		}
		//
		$q = "UPDATE `#__vikrentitems_cronjobs` SET `cron_name`=".$dbo->quote($pcron_name).",`class_file`=".$dbo->quote($pclass_file).",`params`=".$dbo->quote($cronparamstr).",`published`=".(int)$ppublished." WHERE `id`=".(int)$pwhereup.";";
		$dbo->setQuery($q);
		$dbo->execute();
		$mainframe->enqueueMessage(JText::translate('VRICRONUPDATED'));
		if ($stay) {
			$goto = "index.php?option=com_vikrentitems&task=editcron&cid[]=".$pwhereup;
		}
		$mainframe->redirect($goto);
	}

	function createcron() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$this->do_createcron();
	}

	function createcronstay() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$this->do_createcron(true);
	}

	private function do_createcron($stay = false) {
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$pcron_name = VikRequest::getString('cron_name', '', 'request');
		$pclass_file = VikRequest::getString('class_file', '', 'request');
		$ppublished = VikRequest::getString('published', '', 'request');
		$ppublished = intval($ppublished) == 1 ? 1 : 0;
		$vikcronparams = VikRequest::getVar('vikcronparams', array(), 'request', 'none', VIKREQUEST_ALLOWHTML);
		$cronparamarr = array();
		$cronparamstr = '';
		if (count($vikcronparams) > 0) {
			foreach ($vikcronparams as $setting => $cont) {
				if (strlen($setting) > 0) {
					$cronparamarr[$setting] = $cont;
				}
			}
			if (count($cronparamarr) > 0) {
				$cronparamstr = json_encode($cronparamarr);
			}
		}
		$goto = "index.php?option=com_vikrentitems&task=crons";
		if (empty($pcron_name) || empty($pclass_file)) {
			$goto = "index.php?option=com_vikrentitems&task=newcron";
			$mainframe->redirect($goto);
			exit;
		}
		$q = "INSERT INTO `#__vikrentitems_cronjobs` (`cron_name`,`class_file`,`params`,`published`) VALUES (".$dbo->quote($pcron_name).", ".$dbo->quote($pclass_file).", ".$dbo->quote($cronparamstr).", ".(int)$ppublished.");";
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if (!empty($lid)) {
			//launch install() method if available
			if (file_exists(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'cronjobs'.DIRECTORY_SEPARATOR.$pclass_file)) {
				require_once(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'cronjobs'.DIRECTORY_SEPARATOR.$pclass_file);
				if (method_exists('VikCronJob', 'install')) {
					$cron_obj = new VikCronJob($lid, $cronparamarr);
					$cron_obj->install();
				}
			}
			//
			$mainframe->enqueueMessage(JText::translate('VRICRONSAVED'));
			if ($stay) {
				$goto = "index.php?option=com_vikrentitems&task=editcron&cid[]=".$lid;
			}
		}
		$mainframe->redirect($goto);
	}

	function removecrons() {
		if (!JSession::checkToken()) {
			throw new Exception(JText::translate('JINVALID_TOKEN'), 403);
		}
		$ids = VikRequest::getVar('cid', array());
		if (count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d){
				$q = "SELECT * FROM `#__vikrentitems_cronjobs` WHERE `id`=".(int)$d.";";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() == 1) {
					$cur_cron = $dbo->loadAssoc();
					//launch uninstall() method if available
					if (file_exists(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'cronjobs'.DIRECTORY_SEPARATOR.$cur_cron['class_file'])) {
						require_once(VRI_ADMIN_PATH.DIRECTORY_SEPARATOR.'cronjobs'.DIRECTORY_SEPARATOR.$cur_cron['class_file']);
						if (method_exists('VikCronJob', 'uninstall')) {
							$cron_obj = new VikCronJob($cur_cron['id'], json_decode($cur_cron['params'], true));
							$cron_obj->uninstall();
						}
					}
					//
					$q = "DELETE FROM `#__vikrentitems_cronjobs` WHERE `id`=".(int)$d.";";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=crons");
	}

	function canceldash() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems");
	}

	function choosebusy() {
		VikRentItemsHelper::printHeader("8");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'choosebusy'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function orders() {
		VikRentItemsHelper::printHeader("8");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'orders'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function removeorders() {
		$mainframe = JFactory::getApplication();
		$ids = VikRequest::getVar('cid', array(0));
		if (@count($ids)) {
			$dbo = JFactory::getDbo();
			foreach ($ids as $d) {
				$q = "SELECT * FROM `#__vikrentitems_orders` WHERE `id`=".intval($d).";";
				$dbo->setQuery($q);
				$dbo->execute();
				if ($dbo->getNumRows() == 1) {
					$rows = $dbo->loadAssocList();
					$q = "SELECT * FROM `#__vikrentitems_ordersbusy` WHERE `idorder`=".$rows[0]['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
					if ($dbo->getNumRows() > 0) {
						$ordbusy = $dbo->loadAssocList();
						foreach ($ordbusy as $ob) {
							$q = "DELETE FROM `#__vikrentitems_busy` WHERE `id`=".(int)$ob['idbusy'].";";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
					$q = "DELETE FROM `#__vikrentitems_ordersbusy` WHERE `idorder`=".$rows[0]['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
					$q = "DELETE FROM `#__vikrentitems_tmplock` WHERE `idorder`=" . intval($rows[0]['id']) . ";";
					$dbo->setQuery($q);
					$dbo->execute();
					if ($rows[0]['status'] == 'cancelled') {
						$q = "DELETE FROM `#__vikrentitems_customers_orders` WHERE `idorder`=".$rows[0]['id'].";";
						$dbo->setQuery($q);
						$dbo->execute();
						$q = "DELETE FROM `#__vikrentitems_ordersitems` WHERE `idorder`=".$rows[0]['id'].";";
						$dbo->setQuery($q);
						$dbo->execute();
						$q = "DELETE FROM `#__vikrentitems_orders` WHERE `id`=".$rows[0]['id'].";";
						$dbo->setQuery($q);
						$dbo->execute();
					} else {
						$q = "UPDATE `#__vikrentitems_orders` SET `status`='cancelled' WHERE `id`=".$rows[0]['id'].";";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
			}
			$mainframe->enqueueMessage(JText::translate('VRMESSDELBUSY'));
		}
		$mainframe->redirect("index.php?option=com_vikrentitems&task=orders");
	}

	function canceledorder() {
		$pgoto = VikRequest::getString('goto', '', 'request', VIKREQUEST_ALLOWRAW);
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=".($pgoto == 'overv' ? 'overv' : 'orders'));
	}

	function removebusy() {
		$dbo = JFactory::getDbo();
		$pidorder = VikRequest::getString('idorder', '', 'request');
		$pgoto = VikRequest::getString('goto', '', 'request', VIKREQUEST_ALLOWRAW);
		$q = "SELECT * FROM `#__vikrentitems_orders` WHERE `id`=".$dbo->quote($pidorder).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() == 1) {
			$rows = $dbo->loadAssocList();
			if ($rows[0]['status'] != 'cancelled') {
				$q = "UPDATE `#__vikrentitems_orders` SET `status`='cancelled' WHERE `id`=".(int)$rows[0]['id'].";";
				$dbo->setQuery($q);
				$dbo->execute();
				$q = "DELETE FROM `#__vikrentitems_tmplock` WHERE `idorder`=" . intval($rows[0]['id']) . ";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
			$q = "SELECT * FROM `#__vikrentitems_ordersbusy` WHERE `idorder`=".(int)$rows[0]['id'].";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($dbo->getNumRows() > 0) {
				$ordbusy = $dbo->loadAssocList();
				foreach ($ordbusy as $ob) {
					$q = "DELETE FROM `#__vikrentitems_busy` WHERE `id`='".$ob['idbusy']."';";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
			$q = "DELETE FROM `#__vikrentitems_ordersbusy` WHERE `idorder`=".(int)$rows[0]['id'].";";
			$dbo->setQuery($q);
			$dbo->execute();
			if ($rows[0]['status'] == 'cancelled') {
				$q = "DELETE FROM `#__vikrentitems_customers_orders` WHERE `idorder`=" . intval($rows[0]['id']) . ";";
				$dbo->setQuery($q);
				$dbo->execute();
				$q = "DELETE FROM `#__vikrentitems_ordersitems` WHERE `idorder`=".(int)$rows[0]['id'].";";
				$dbo->setQuery($q);
				$dbo->execute();
				$q = "DELETE FROM `#__vikrentitems_orders` WHERE `id`=".(int)$rows[0]['id'].";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::translate('VRMESSDELBUSY'));
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_vikrentitems&task=".($pgoto == 'overv' ? 'overv' : 'orders'));
	}

	function editorder() {
		VikRentItemsHelper::printHeader("8");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'editorder'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function editbusy() {
		VikRentItemsHelper::printHeader("8");

		VikRequest::setVar('view', VikRequest::getCmd('view', 'editbusy'));
	
		parent::display();

		if (VikRentItems::showFooter()) {
			VikRentItemsHelper::printFooter();
		}
	}

	function updatebusy() {
		$pidorder = VikRequest::getString('idorder', '', 'request');
		$pritiro = VikRequest::getString('ritiro', '', 'request');
		$pconsegna = VikRequest::getString('consegna', '', 'request');
		$ppickuph = VikRequest::getString('pickuph', '', 'request');
		$ppickupm = VikRequest::getString('pickupm', '', 'request');
		$pdropoffh = VikRequest::getString('dropoffh', '', 'request');
		$pdropoffm = VikRequest::getString('dropoffm', '', 'request');
		$pcustdata = VikRequest::getString('custdata', '', 'request');
		$pidplace = VikRequest::getInt('idplace', '', 'request');
		$pidreturnplace = VikRequest::getInt('idreturnplace', '', 'request');
		$pdeliverycost = VikRequest::getFloat('deliverycost', 0, 'request');
		$pareprices = VikRequest::getString('areprices', '', 'request');
		$ptotpaid = VikRequest::getString('totpaid', '', 'request');
		$pgoto = VikRequest::getString('goto', '', 'request', VIKREQUEST_ALLOWRAW);
		$pextracn = VikRequest::getVar('extracn', array());
		$pextracc = VikRequest::getVar('extracc', array());
		$pextractx = VikRequest::getVar('extractx', array());
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$actnow = time();
		$nowdf = VikRentItems::getDateFormat(true);
		$nowtf = VikRentItems::getTimeFormat(true);
		if ($nowdf == "%d/%m/%Y") {
			$df = 'd/m/Y';
		} elseif ($nowdf == "%m/%d/%Y") {
			$df = 'm/d/Y';
		} else {
			$df = 'Y/m/d';
		}
		$q = "SELECT * FROM `#__vikrentitems_orders` WHERE `id`=".(int)$pidorder.";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() == 1) {
			$ord = $dbo->loadAssocList();
			$q = "SELECT `oi`.*,`i`.`name`,`i`.`idopt`,`i`.`units`,`i`.`params` FROM `#__vikrentitems_ordersitems` AS `oi`,`#__vikrentitems_items` AS `i` WHERE `oi`.`idorder`=".$ord[0]['id']." AND `oi`.`iditem`=`i`.`id` ORDER BY `oi`.`id` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			$orderitems = $dbo->loadAssocList();
			// Custom rate
			$is_cust_cost = false;
			foreach ($orderitems as $koi => $oi) {
				if (!empty($oi['cust_cost']) && $oi['cust_cost'] > 0.00) {
					$is_cust_cost = true;
					break;
				}
			}
			//
			//VRI 1.6 item switching
			$toswitch = array();
			$idbooked = array();
			$items_units = array();
			$q = "SELECT `id`,`name`,`units` FROM `#__vikrentitems_items`;";
			$dbo->setQuery($q);
			$dbo->execute();
			$all_items = $dbo->loadAssocList();
			foreach ($all_items as $rr) {
				$items_units[$rr['id']]['name'] = $rr['name'];
				$items_units[$rr['id']]['units'] = $rr['units'];
			}
			foreach ($orderitems as $ind => $oi) {
				$switch_command = VikRequest::getString('switch_'.$oi['id'], '', 'request');
				$book_item_units = VikRequest::getInt('itemquant'.$ind, 1, 'request');
				$book_item_units = $book_item_units < 1 ? 1 : $book_item_units;
				if (!empty($switch_command) && intval($switch_command) != $oi['iditem'] && array_key_exists(intval($switch_command), $items_units)) {
					$idbooked[$oi['iditem']]++;
					$orkey = count($toswitch);
					$toswitch[$orkey]['from'] = $oi['iditem'];
					$toswitch[$orkey]['to'] = intval($switch_command);
					$toswitch[$orkey]['newquantity'] = $book_item_units;
					$toswitch[$orkey]['record'] = $oi;
				}
			}
			if (count($toswitch) > 0 && (!empty($orderitems[0]['idtar']) || $is_cust_cost)) {
				foreach ($toswitch as $ksw => $rsw) {
					$plusunit = array_key_exists($rsw['to'], $idbooked) ? $idbooked[$rsw['to']] : 0;
					if (!VikRentItems::itemBookable($rsw['to'], ($items_units[$rsw['to']]['units'] + $plusunit), $ord[0]['ritiro'], $ord[0]['consegna'], $rsw['newquantity'])) {
						unset($toswitch[$ksw]);
						VikError::raiseWarning('', JText::sprintf('VRISWITCHITERR', $rsw['newquantity'], $rsw['record']['name'], $items_units[$rsw['to']]['name']));
					}
				}
				if (count($toswitch) > 0) {
					//reset first record rate
					reset($orderitems);
					$q = "UPDATE `#__vikrentitems_ordersitems` SET `idtar`=NULL WHERE `id`=".$orderitems[0]['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
					//
					$app = JFactory::getApplication();
					foreach ($toswitch as $ksw => $rsw) {
						$q = "UPDATE `#__vikrentitems_ordersitems` SET `iditem`=".$rsw['to'].",`idtar`=NULL WHERE `id`=".$rsw['record']['id'].";";
						$dbo->setQuery($q);
						$dbo->execute();
						$app->enqueueMessage(JText::sprintf('VRISWITCHITOK', $rsw['record']['name'], $items_units[$rsw['to']]['name']));
						//update Notes field for this booking to keep track of the previous item that was assigned
						$prev_room_name = array_key_exists($rsw['from'], $items_units) ? $items_units[$rsw['from']]['name'] : '';
						if (!empty($prev_room_name)) {
							$new_notes = JText::sprintf('VRIPREVITEMMOVED', $prev_room_name, date($df.' '.$nowtf))."\n".$ord[0]['adminnotes'];
							$q = "UPDATE `#__vikrentitems_orders` SET `adminnotes`=".$dbo->quote($new_notes)." WHERE `id`=".(int)$ord[0]['id'].";";
							$dbo->setQuery($q);
							$dbo->execute();
						}
						//
						if ($ord[0]['status'] == 'confirmed') {
							//update record in _busy
							$q = "SELECT `b`.`id`,`b`.`iditem`,`ob`.`idorder` FROM `#__vikrentitems_busy` AS `b`,`#__vikrentitems_ordersbusy` AS `ob` WHERE `b`.`iditem`=" . $rsw['from'] . " AND `b`.`id`=`ob`.`idbusy` AND `ob`.`idorder`=".$ord[0]['id']." LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
							if ($dbo->getNumRows() == 1) {
								$cur_busy = $dbo->loadAssocList();
								$q = "UPDATE `#__vikrentitems_busy` SET `iditem`=".$rsw['to']." WHERE `id`=".$cur_busy[0]['id']." AND `iditem`=".$cur_busy[0]['iditem']." LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								// old kit relations
								$kit_relations = VikRentItems::getKitRelatedItems($rsw['from']);
								if (count($kit_relations)) {
									// switched item was a kit: delete all busy records for children items
									$q = "SELECT * FROM `#__vikrentitems_ordersbusy` WHERE `idorder`=".$ord[0]['id'].";";
									$dbo->setQuery($q);
									$dbo->execute();
									$old_obs = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
									foreach ($kit_relations as $kit_rel) {
										for ($i = 1; $i <= $kit_rel['units']; $i++) {
											foreach ($old_obs as $old_ob) {
												$q = "DELETE FROM `#__vikrentitems_busy` WHERE `id`=".$old_ob['idbusy']." AND `iditem`=" . $dbo->quote($kit_rel['iditem']) . " LIMIT 1;";
												$dbo->setQuery($q);
												$dbo->execute();
												if ($dbo->getAffectedRows() > 0) {
													$q = "DELETE FROM `#__vikrentitems_ordersbusy` WHERE `id`=".$old_ob['id']." LIMIT 1;";
													$dbo->setQuery($q);
													$dbo->execute();
												}
											}
										}
									}
									//
								}
								// new kit relations
								$kit_relations = VikRentItems::getKitRelatedItems($rsw['to']);
								if (count($kit_relations)) {
									// newly switched item is a kit
									foreach ($kit_relations as $kit_rel) {
										for ($i = 1; $i <= $kit_rel['units']; $i++) {
											$q = "INSERT INTO `#__vikrentitems_busy` (`iditem`,`ritiro`,`consegna`,`realback`) VALUES(" . $dbo->quote($kit_rel['iditem']) . ", '" . $ord[0]['ritiro'] . "', '" . $ord[0]['consegna'] . "','" . $ord[0]['consegna'] . "');";
											$dbo->setQuery($q);
											$dbo->execute();
											$newbusyid = $dbo->insertid();
											$q = "INSERT INTO `#__vikrentitems_ordersbusy` (`idorder`,`idbusy`) VALUES(".$ord[0]['id'].", ".(int)$newbusyid.");";
											$dbo->setQuery($q);
											$dbo->execute();
										}
									}
									//
								}
								//
							}
						} elseif ($ord[0]['status'] == 'standby') {
							//remove record in _tmplock
							$q = "DELETE FROM `#__vikrentitems_tmplock` WHERE `idorder`=" . intval($ord[0]['id']) . ";";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
					$app->redirect("index.php?option=com_vikrentitems&task=editbusy&cid[]=".$ord[0]['id'].($pgoto == 'overv' ? "&goto=overv" : ""));
					exit;
				}
			}
			//
			$first = VikRentItems::getDateTimestamp($pritiro, $ppickuph, $ppickupm);
			$second = VikRentItems::getDateTimestamp($pconsegna, $pdropoffh, $pdropoffm);
			if ($second > $first) {
				$checkhourly = false;
				$checkhourscharges = 0;
				$hoursdiff = 0;
				$secdiff = $second - $first;
				$daysdiff = $secdiff / 86400;
				if (is_int($daysdiff)) {
					if ($daysdiff < 1) {
						$daysdiff = 1;
					}
				} else {
					if ($daysdiff < 1) {
						$daysdiff = 1;
						$checkhourly = true;
						$ophours = $secdiff / 3600;
						$hoursdiff = intval(round($ophours));
						if ($hoursdiff < 1) {
							$hoursdiff = 1;
						}
					} else {
						$sum = floor($daysdiff) * 86400;
						$newdiff = $secdiff - $sum;
						$maxhmore = VikRentItems::getHoursMoreRb() * 3600;
						if ($maxhmore >= $newdiff) {
							$daysdiff = floor($daysdiff);
						} else {
							$daysdiff = ceil($daysdiff);
							$ehours = intval(round(($newdiff - $maxhmore) / 3600));
							$checkhourscharges = $ehours;
							if ($checkhourscharges > 0) {
								$aehourschbasp = VikRentItems::applyExtraHoursChargesBasp();
							}
						}
					}
				}
				$groupdays = VikRentItems::getGroupDays($first, $second, $daysdiff);
				// VRI 1.6 - Allow pick ups on drop offs
				$picksondrops = VikRentItems::allowPickOnDrop();
				//
				$opertwounits = true;
				$notbookable = array();
				$units_counter = array();
				foreach ($orderitems as $ind => $oi) {
					$pitemquant = VikRequest::getInt('itemquant'.$ind, 1, 'request');
					$pitemquant = $pitemquant < 1 ? 1 : $pitemquant;
					$orderitems[$ind]['itemquant'] = $pitemquant;
					if (!isset($units_counter[$oi['iditem']])) {
						$units_counter[$oi['iditem']] = -1;
					}
					$units_counter[$oi['iditem']]++;
				}
				foreach ($orderitems as $ind => $oi) {
					$num = $ind + 1;
					$check = "SELECT `b`.`id`,`b`.`ritiro`,`b`.`realback`,`ob`.`idorder` FROM `#__vikrentitems_busy` AS `b`,`#__vikrentitems_ordersbusy` AS `ob` WHERE `b`.`iditem`='" . $oi['iditem'] . "' AND `b`.`id`=`ob`.`idbusy` AND `ob`.`idorder`!='".$ord[0]['id']."';";
					$dbo->setQuery($check);
					$dbo->execute();
					if ($dbo->getNumRows() > 0) {
						$busy = $dbo->loadAssocList();
						foreach ($groupdays as $gday) {
							$bfound = 0;
							foreach ($busy as $bu) {
								if ($gday >= $bu['ritiro'] && $gday <= $bu['realback']) {
									if ($picksondrops && !($gday > $bu['ritiro'] && $gday < $bu['realback']) && $gday != $bu['ritiro']) {
										// VRI 1.6 - pick ups on drop offs allowed
										continue;
									}
									$bfound++;
								} elseif (count($groupdays) == 2 && $gday == $groupdays[0]) {
									if ($groupdays[0] < $bu['ritiro'] && $groupdays[0] < $bu['realback'] && $groupdays[1] > $bu['ritiro'] && $groupdays[1] > $bu['realback']) {
										$bfound++;
									}
								}
							}
							if (($bfound + $oi['itemquant']) > $oi['units'] || !VikRentItems::itemNotLocked($oi['iditem'], $oi['units'], $first, $second, $oi['itemquant'])) {
								$notbookable[] = $oi['name'].($oi['itemquant'] > 1 ? " x".$oi['itemquant'] : "");
								$opertwounits = false;
							}
						}
					}
				}
				if ($opertwounits === true) {
					//update dates, customer information, amount paid and busy records before checking the rates
					$realback = VikRentItems::getHoursItemAvail() * 3600;
					$realback += $second;
					$newtotalpaid = strlen($ptotpaid) > 0 ? floatval($ptotpaid) : "";
					//Vik Rent Items 1.6 - Add Room to existing booking
					$item_added = false;
					$padd_item_id = VikRequest::getInt('add_item_id', '', 'request');
					$padd_item_quantity = VikRequest::getInt('add_item_quantity', 1, 'request');
					$padd_item_quantity = $padd_item_quantity < 1 ? 1 : $padd_item_quantity;
					$padd_item_price = VikRequest::getFloat('add_item_price', 0, 'request');
					$paliq_add_item = VikRequest::getInt('aliq_add_item', 0, 'request');
					if ($padd_item_id > 0) {
						//no need to re-validate the availability for this new item, as it was made via JS in the View.
						//insert the new item record
						$q = "INSERT INTO `#__vikrentitems_ordersitems` (`idorder`,`iditem`,`itemquant`,`cust_cost`,`cust_idiva`) VALUES(".$ord[0]['id'].", ".$padd_item_id.", ".$padd_item_quantity.", ".($padd_item_price > 0 ? $dbo->quote($padd_item_price) : 'NULL').", ".($padd_item_price > 0 && !empty($paliq_add_item) ? $dbo->quote($paliq_add_item) : 'NULL').");";
						$dbo->setQuery($q);
						$dbo->execute();
						$item_added = true;
					}
					//Vik Rent Items 1.6 - Remove Room from existing booking
					$item_removed = false;
					$prm_item_oid = VikRequest::getInt('rm_item_oid', '', 'request');
					if ($prm_item_oid > 0 && count($orderitems) > 1) {
						//check if the requested item record exists for removal
						$q = "SELECT * FROM `#__vikrentitems_ordersitems` WHERE `id`=".$prm_item_oid." AND `idorder`=".$ord[0]['id'].";";
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows() == 1) {
							$item_before_rm = $dbo->loadAssoc();
							//remove the requested item record
							$q = "DELETE FROM `#__vikrentitems_ordersitems` WHERE `id`=".$prm_item_oid." AND `idorder`=".$ord[0]['id']." LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
							$item_removed = $item_before_rm['iditem'];
						}
					}
					//
					//update booking's basic information (customer data, dates, tot paid, locations, delivery cost)
					$q = "UPDATE `#__vikrentitems_orders` SET `custdata`=".$dbo->quote($pcustdata).", `days`='".$daysdiff."', `ritiro`='".$first."', `consegna`='".$second."', `idplace`=".(!empty($pidplace) ? $pidplace : 'NULL').", `idreturnplace`=".(!empty($pidreturnplace) ? $pidreturnplace : 'NULL').(strlen($newtotalpaid) > 0 ? ", `totpaid`='".$newtotalpaid."'" : "").", `deliverycost`=".$dbo->quote($pdeliverycost)." WHERE `id`='".$ord[0]['id']."';";
					$dbo->setQuery($q);
					$dbo->execute();
					// update the order array information about the locations and delivery
					$ord[0]['idplace'] = $pidplace;
					$ord[0]['idreturnplace'] = $pidreturnplace;
					$ord[0]['deliverycost'] = $pdeliverycost;
					//
					if ($ord[0]['status'] == 'confirmed') {
						$q = "SELECT `b`.`id`,`b`.`iditem` FROM `#__vikrentitems_busy` AS `b`,`#__vikrentitems_ordersbusy` AS `ob` WHERE `b`.`id`=`ob`.`idbusy` AND `ob`.`idorder`='".$ord[0]['id']."';";
						$dbo->setQuery($q);
						$dbo->execute();
						$allbusy = $dbo->loadAssocList();
						foreach ($allbusy as $bb) {
							$q = "UPDATE `#__vikrentitems_busy` SET `ritiro`='".$first."', `consegna`='".$second."', `realback`='".$realback."' WHERE `id`='".$bb['id']."';";
							$dbo->setQuery($q);
							$dbo->execute();
						}
						//Vik Rent Items 1.6 - Add item to existing (Confirmed) booking
						if ($item_added === true) {
							//add busy record for the new item unit
							$q = "INSERT INTO `#__vikrentitems_busy` (`iditem`,`ritiro`,`consegna`,`realback`) VALUES(".$padd_item_id.", ".$dbo->quote($first).", ".$dbo->quote($second).", ".$dbo->quote($realback).");";
							$dbo->setQuery($q);
							$dbo->execute();
							$newbusyid = $dbo->insertid();
							$q = "INSERT INTO `#__vikrentitems_ordersbusy` (`idorder`,`idbusy`) VALUES(".$ord[0]['id'].", ".(int)$newbusyid.");";
							$dbo->setQuery($q);
							$dbo->execute();
							// Kit relations
							$kit_relations = VikRentItems::getKitRelatedItems($padd_item_id);
							if (count($kit_relations)) {
								//VRI 1.5 - store busy records for the children or parent items, in case of a kit (Group/Set of Items)
								foreach ($kit_relations as $kit_rel) {
									for ($i = 1; $i <= $kit_rel['units']; $i++) {
										$q = "INSERT INTO `#__vikrentitems_busy` (`iditem`,`ritiro`,`consegna`,`realback`) VALUES(" . $dbo->quote($kit_rel['iditem']) . ", '" . $first . "', '" . $second . "','" . $realback . "');";
										$dbo->setQuery($q);
										$dbo->execute();
										$newbusyid = $dbo->insertid();
										$q = "INSERT INTO `#__vikrentitems_ordersbusy` (`idorder`,`idbusy`) VALUES(".$ord[0]['id'].", ".(int)$newbusyid.");";
										$dbo->setQuery($q);
										$dbo->execute();
									}
								}
								//
							}
							//
						}
						//Vik Rent Items 1.6 - Remove item from existing (Confirmed) booking
						if ($item_removed !== false) {
							//remove busy record for the removed item
							foreach ($allbusy as $bb) {
								if ($bb['iditem'] == $item_removed) {
									//remove the first item with this ID that was booked
									$q = "DELETE FROM `#__vikrentitems_busy` WHERE `id`=".$bb['id']." AND `iditem`=".$item_removed.";";
									$dbo->setQuery($q);
									$dbo->execute();
									$q = "DELETE FROM `#__vikrentitems_ordersbusy` WHERE `idorder`=".$ord[0]['id']." AND `idbusy`=".$bb['id'].";";
									$dbo->setQuery($q);
									$dbo->execute();
									break;
								}
							}
							// Kit relations
							$kit_relations = VikRentItems::getKitRelatedItems($item_removed);
							if (count($kit_relations)) {
								// removed item was a kit: free up busy records
								$q = "SELECT * FROM `#__vikrentitems_ordersbusy` WHERE `idorder`=".$ord[0]['id'].";";
								$dbo->setQuery($q);
								$dbo->execute();
								$old_obs = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
								foreach ($kit_relations as $kit_rel) {
									for ($i = 1; $i <= $kit_rel['units']; $i++) {
										foreach ($old_obs as $old_ob) {
											$q = "DELETE FROM `#__vikrentitems_busy` WHERE `id`=".$old_ob['idbusy']." AND `iditem`=" . $dbo->quote($kit_rel['iditem']) . " LIMIT 1;";
											$dbo->setQuery($q);
											$dbo->execute();
											if ($dbo->getAffectedRows() > 0) {
												$q = "DELETE FROM `#__vikrentitems_ordersbusy` WHERE `id`=".$old_ob['id']." LIMIT 1;";
												$dbo->setQuery($q);
												$dbo->execute();
											}
										}
									}
								}
								//
							}
							//
						}
						//
					}
					$upd_esit = JText::translate('RESUPDATED');
					//
					$isdue = 0;
					$isdue += $ord[0]['deliverycost'];
					$doup = true;
					$notar = array();
					$tars = array();
					$cust_costs = array();
					$items_costs_map = array();
					foreach ($orderitems as $koi => $oi) {
						//Vik Rent Items 1.6 - Remove from existing booking
						if ($item_removed !== false) {
							if ($oi['id'] == $prm_item_oid) {
								//do not consider this item for the calculation of the new total amount
								unset($orderitems[$koi]);
								continue;
							}
						}
						//
						$num = $koi + 1;
						$ppriceid = VikRequest::getString('priceid'.$num, '', 'request');
						$pcust_cost = VikRequest::getString('cust_cost'.$num, '', 'request');
						$paliq = VikRequest::getString('aliq'.$num, '', 'request');
						if (empty($ppriceid) && !empty($pcust_cost) && floatval($pcust_cost) > 0) {
							$cust_costs[$num] = array('cust_cost' => $pcust_cost, 'aliq' => $paliq);
							$isdue += (float)$pcust_cost;
							continue;
						}
						$tar = array();
						if ($checkhourly) {
							$q = "SELECT * FROM `#__vikrentitems_dispcosthours` WHERE `iditem`=".(int)$oi['iditem']." AND `hours`=".(int)$hoursdiff." AND `idprice`=".(int)$ppriceid.";";
						} else {
							$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `iditem`=".(int)$oi['iditem']." AND `days`=".(int)$daysdiff." AND `idprice`=".(int)$ppriceid.";";
						}
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows() == 1) {
							$tar = $dbo->loadAssocList();
							if ($checkhourly) {
								// set the order to be hourly
								$ord[0]['hourly'] = 1;
								//
								foreach ($tar as $kt => $vt) {
									$tar[$kt]['days'] = 1;
								}
							}
						} else {
							//there are no hourly prices
							if ($checkhourly) {
								$q = "SELECT * FROM `#__vikrentitems_dispcost` WHERE `iditem`=".(int)$oi['iditem']." AND `days`=".(int)$daysdiff." AND `idprice`=".(int)$ppriceid.";";
								$dbo->setQuery($q);
								$dbo->execute();
								if ($dbo->getNumRows() == 1) {
									$tar = $dbo->loadAssocList();
								}
							}
						}
						if (count($tar) == 0) {
							$doup = false;
							$notar[] = $oi['name'];
							break;
						}
						if ($checkhourscharges > 0 && $aehourschbasp == true) {
							$ret = VikRentItems::applyExtraHoursChargesItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, false, false, true);
							$tar = $ret['return'];
							$calcdays = $ret['days'];
						}
						if ($checkhourscharges > 0 && $aehourschbasp == false) {
							$tar = VikRentItems::extraHoursSetPreviousFareItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, false);
							$tar = VikRentItems::applySeasonsItem($tar, $first, $second, $ord[0]['idplace']);
							$ret = VikRentItems::applyExtraHoursChargesItem($tar, $oi['iditem'], $checkhourscharges, $daysdiff, true, false, true);
							$tar = $ret['return'];
							$calcdays = $ret['days'];
						} else {
							$tar = VikRentItems::applySeasonsItem($tar, $first, $second, $ord[0]['idplace']);
						}
						$tar = VikRentItems::applyItemDiscounts($tar, $oi['iditem'], $oi['itemquant']);
						if ($oi['itemquant'] > 0) {
							$isdue += VikRentItems::sayCostPlusIva($tar[0]['cost'] * $oi['itemquant'], $tar[0]['idprice'], $ord[0]);
						}
						//when editing the reservation and hours charges, a different fare can be chosen so the ID must be updated in $tar
						if ($checkhourscharges > 0 && !empty($calcdays) && $calcdays > 0 && (int)$daysdiff != (int)$calcdays) {
							foreach ($tar as $kt => $tt) {
								$q = "SELECT `id` FROM `#__vikrentitems_dispcost` WHERE `iditem`=".(int)$tt['iditem']." AND `days`=".(int)$tt['days']." AND `idprice`=".(int)$tt['idprice'].";";
								$dbo->setQuery($q);
								$dbo->execute();
								$validdaytarid = $dbo->loadResult();
								if (strlen($validdaytarid) > 0) {
									$tar[$kt]['id'] = $validdaytarid;
								}
							}
						}
						//
						$orderitems[$koi]['tar'] = $tar;
						$tars[$num] = $tar;
						$items_costs_map[$num] = $tar[0]['cost'];
					}
					if ($doup === true) {
						if (isset($calcdays) && $calcdays > 0 && (int)$daysdiff != (int)$calcdays) {
							$daysdiff = $calcdays;
						}
						if ($item_added === true) {
							//Vik Rent Items 1.6 - Add item to existing booking may require to increase the total amount
							$padd_item_price = VikRequest::getFloat('add_item_price', 0, 'request');
							$paliq_add_item = VikRequest::getInt('aliq_add_item', 0, 'request');
							if (!empty($padd_item_price) && floatval($padd_item_price) > 0) {
								$isdue += (float)$padd_item_price;
							}
							//
						}
						if (!empty($ord[0]['idplace']) && !empty($ord[0]['idreturnplace'])) {
							$locfee = VikRentItems::getLocFee($ord[0]['idplace'], $ord[0]['idreturnplace']);
							if ($locfee) {
								//VikRentItems 1.1 - Location fees overrides
								if (strlen($locfee['losoverride']) > 0) {
									$arrvaloverrides = array();
									$valovrparts = explode('_', $locfee['losoverride']);
									foreach ($valovrparts as $valovr) {
										if (!empty($valovr)) {
											$ovrinfo = explode(':', $valovr);
											$arrvaloverrides[$ovrinfo[0]] = $ovrinfo[1];
										}
									}
									if (array_key_exists($daysdiff, $arrvaloverrides)) {
										$locfee['cost'] = $arrvaloverrides[$daysdiff];
									}
								}
								//end VikRentItems 1.1 - Location fees overrides
								$locfeecost = intval($locfee['daily']) == 1 ? ($locfee['cost'] * $daysdiff) : $locfee['cost'];
								$locfeewith = VikRentItems::sayLocFeePlusIva($locfeecost, $locfee['idiva'], $ord[0]);
								$isdue += $locfeewith;
							}
						}
						$toptionals = '';
						$q = "SELECT * FROM `#__vikrentitems_optionals` ORDER BY `#__vikrentitems_optionals`.`ordering` ASC;";
						$dbo->setQuery($q);
						$dbo->execute();
						if ($dbo->getNumRows() > 0) {
							$toptionals = $dbo->loadAssocList();
						}
						foreach ($orderitems as $koi => $oi) {
							$num = $koi + 1;
							$wop = "";
							if (is_array($toptionals)) {
								foreach ($toptionals as $opt) {
									$tmpvar = VikRequest::getString('optid'.$num.$opt['id'], '', 'request');
									if (!empty($tmpvar)) {
										if (!empty($opt['specifications'])) {
											$optspeccosts = VikRentItems::getOptionSpecIntervalsCosts($opt['specifications']);
											$optspecnames = VikRentItems::getOptionSpecIntervalsNames($opt['specifications']);
											$opt['quan'] = 1;
											$opt['cost'] = $optspeccosts[($tmpvar - 1)];
											$opt['name'] .= ': '.$optspecnames[($tmpvar - 1)];
											$opt['specintv'] = $tmpvar;
											$wop .= $opt['id'].":".$opt['quan']."-".$tmpvar.";";
											$realcost = (intval($opt['perday']) == 1 ? ($opt['cost'] * $daysdiff * $opt['quan']) : ($opt['cost'] * $opt['quan']));
										} else {
											$wop .= $opt['id'].":".$tmpvar.";";
											$realcost = (intval($opt['perday']) == 1 ? ($opt['cost'] * $daysdiff * $tmpvar) : ($opt['cost'] * $tmpvar));
										}
										if (!empty($opt['maxprice']) && $opt['maxprice'] > 0 && $realcost > $opt['maxprice']) {
											$realcost = $opt['maxprice'];
											if (intval($opt['hmany']) == 1 && intval($tmpvar) > 1) {
												$realcost = $opt['maxprice'] * $tmpvar;
											}
										}
										$opt_item_units = $opt['onceperitem'] ? 1 : $oi['itemquant'];
										$tmpopr = VikRentItems::sayOptionalsPlusIva($realcost * $opt_item_units, $opt['idiva'], $ord[0]);
										$isdue += $tmpopr;
									}
								}
							}
							$upd_fields = array();
							if (array_key_exists($num, $tars)) {
								//type of price
								$upd_fields[] = "`idtar`='".$tars[$num][0]['id']."'";
								$upd_fields[] = "`cust_cost`=NULL";
								$upd_fields[] = "`cust_idiva`=NULL";
							} elseif (array_key_exists($num, $cust_costs) && array_key_exists('cust_cost', $cust_costs[$num])) {
								//custom rate + custom tax rate
								$upd_fields[] = "`idtar`=NULL";
								$upd_fields[] = "`cust_cost`='".$cust_costs[$num]['cust_cost']."'";
								$upd_fields[] = "`cust_idiva`='".$cust_costs[$num]['aliq']."'";
							}
							if (is_array($toptionals)) {
								$upd_fields[] = "`optionals`='".$wop."'";
							}
							// quantity and delivery address
							$pdeliveryaddr = VikRequest::getString('deliveryaddr'.$oi['id'], '', 'request');
							$pdeliverydist = VikRequest::getFloat('deliverydist'.$oi['id'], 0, 'request');
							$upd_fields[] = "`itemquant`=".$oi['itemquant'];
							$upd_fields[] = "`deliveryaddr`=".$dbo->quote($pdeliveryaddr);
							$upd_fields[] = "`deliverydist`=".$dbo->quote($pdeliverydist);
							//calculate the extra costs and increase isdue
							$extracosts_arr = array();
							if (count($pextracn) > 0 && count($pextracn[$num]) > 0) {
								foreach ($pextracn[$num] as $eck => $ecn) {
									if (strlen($ecn) > 0 && array_key_exists($eck, $pextracc[$num]) && is_numeric($pextracc[$num][$eck])) {
										$ecidtax = array_key_exists($eck, $pextractx[$num]) && intval($pextractx[$num][$eck]) > 0 ? (int)$pextractx[$num][$eck] : '';
										$extracosts_arr[] = array('name' => $ecn, 'cost' => (float)$pextracc[$num][$eck], 'idtax' => $ecidtax);
										$ecplustax = !empty($ecidtax) ? VikRentItems::sayOptionalsPlusIva((float)$pextracc[$num][$eck], $ecidtax, $ord[0]) : (float)$pextracc[$num][$eck];
										$isdue += $ecplustax;
									}
								}
							}
							if (count($extracosts_arr) > 0) {
								$upd_fields[] = "`extracosts`=".$dbo->quote(json_encode($extracosts_arr));
							} else {
								$upd_fields[] = "`extracosts`=NULL";
							}
							//end extra costs
							if (count($upd_fields) > 0) {
								$q = "UPDATE `#__vikrentitems_ordersitems` SET ".implode(', ', $upd_fields)." WHERE `idorder`=".(int)$ord[0]['id']." AND `iditem`=".(int)$oi['iditem']." AND `id`=".(int)$oi['id'].";";
								$dbo->setQuery($q);
								$dbo->execute();
							}
						}
						$q = "UPDATE `#__vikrentitems_orders` SET `hourly`=".(int)$ord[0]['hourly'].", `order_total`='".$isdue."' WHERE `id`=".(int)$ord[0]['id'].";";
						$dbo->setQuery($q);
						$dbo->execute();
						$upd_esit = JText::translate('VRIRESRATESUPDATED');
					} else {
						VikError::raiseWarning('', JText::sprintf('VRIERRNOTAR', implode(", ", $notar)));
					}
					$mainframe->enqueueMessage($upd_esit);
				} else {
					VikError::raiseWarning('', JText::translate('VRIARNOTRIT')." ".date($df.' H:i', $first)." ".JText::translate('VRIARNOTCONSTO')." ".date($df.' H:i', $second).'<br/>'.implode(", ", $notbookable));
				}
			} else {
				VikError::raiseWarning('', JText::translate('ERRPREV'));
			}
			$mainframe->redirect("index.php?option=com_vikrentitems&task=editbusy&cid[]=".$ord[0]['id'].($pgoto == 'overv' ? "&goto=overv" : ""));
		} else {
			$mainframe->redirect("index.php?option=com_vikrentitems&task=rooms");
		}
	}

	function isitembookable() {
		//to be called via ajax
		$dbo = JFactory::getDbo();
		$res = array(
			'status' => 0,
			'err' => ''
		);
		$pitid = VikRequest::getInt('itid', 0, 'request');
		$pfdate = VikRequest::getString('fdate', '', 'request');
		$pfh = VikRequest::getInt('fh', 0, 'request');
		$pfm = VikRequest::getInt('fm', 0, 'request');
		$ptdate = VikRequest::getString('tdate', '', 'request');
		$pth = VikRequest::getInt('th', 0, 'request');
		$ptm = VikRequest::getInt('tm', 0, 'request');
		$item_info = array();
		$q = "SELECT * FROM `#__vikrentitems_items` WHERE `id`=".(int)$pitid.";";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$item_info = $dbo->loadAssoc();
		}
		$from_ts = VikRentItems::getDateTimestamp($pfdate, $pfh, $pfm);
		$to_ts = VikRentItems::getDateTimestamp($ptdate, $pth, $ptm);
		if (
			count($item_info) > 0 && 
			(!empty($pfdate) && !empty($ptdate) && !empty($from_ts) && !empty($to_ts)) && 
			VikRentItems::itemBookable($item_info['id'], $item_info['units'], $from_ts, $to_ts)) 
		{
			$res['status'] = 1;
		} else {
			if (!(count($item_info) > 0)) {
				$res['err'] = 'Item not found';
			} elseif (empty($pfdate) || empty($ptdate) || empty($from_ts) || empty($to_ts)) {
				$res['err'] = 'Invalid dates';
			} else {
				//not available
				$res['err'] = JText::sprintf('VRIBOOKADDITEMERR', $item_info['name'], $pfdate, $ptdate);
			}
		}

		echo json_encode($res);
		exit;
	}

	/**
	 * Hidden task to clean up duplicate records in certain database tables
	 * due to a double execution of the installation queries.
	 * 
	 * @since 	November 4th 2020
	 */
	function clean_duplicate_records() {
		$dbo = JFactory::getDbo();

		$tables_with_duplicates = array(
			'#__vikrentitems_config' => array(
				'id_key' 	  => 'id',
				'compare_key' => 'param',
			),
			'#__vikrentitems_countries' => array(
				'id_key' 	  => 'id',
				'compare_key' => 'country_3_code',
			),
			'#__vikrentitems_custfields' => array(
				'id_key' 	  => 'id',
				'compare_key' => 'name',
			),
			'#__vikrentitems_texts' => array(
				'id_key' 	  => 'id',
				'compare_key' => 'param',
			),
		);

		foreach ($tables_with_duplicates as $tblname => $data) {
			$doubles = array();
			$storage = array();
			$rmlist = array();
			$q = "SELECT * FROM `{$tblname}` ORDER BY `{$data['id_key']}` DESC;";
			$dbo->setQuery($q);
			$dbo->execute();
			if (!$dbo->getNumRows()) {
				echo "<p>No records found in table {$tblname}</p>";
				continue;
			}
			$rows = $dbo->loadAssocList();
			foreach ($rows as $row) {
				if (!isset($doubles[$row[$data['compare_key']]])) {
					$doubles[$row[$data['compare_key']]] = 0;
				}
				$doubles[$row[$data['compare_key']]]++;
				if (!isset($storage[$row[$data['compare_key']]])) {
					$storage[$row[$data['compare_key']]] = array();
				}
				array_push($storage[$row[$data['compare_key']]], $row[$data['id_key']]);
			}
			foreach ($doubles as $paramkey => $paramcount) {
				if ($paramcount < 2 || !isset($storage[$paramkey]) || count($storage[$paramkey]) < 2 || $paramcount != count($storage[$paramkey])) {
					continue;
				}
				$exceeding = $paramcount - 1;
				for ($x = 0; $x < $exceeding; $x++) {
					array_push($rmlist, $storage[$paramkey][$x]);
				}
			}
			echo "<p>Total records found in table {$tblname}: " . count($rows) . "</p>";
			echo '<p>Total records to remove: ' . count($rmlist) . '</p>';
			echo '<pre style="display: none;">'.print_r($rmlist, true).'</pre><br/>';
			if (count($rmlist)) {
				$q = "DELETE FROM `{$tblname}` WHERE `{$data['id_key']}` IN (" . implode(', ', $rmlist) . ");";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	}

}
