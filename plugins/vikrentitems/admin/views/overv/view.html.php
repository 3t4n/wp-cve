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

class VikRentItemsViewOverv extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$rows = "";
		$navbut = "";
		$dbo = JFactory::getDbo();
		$mainframe = JFactory::getApplication();
		$session = JFactory::getSession();
		$cookie = $mainframe->input->cookie;
		$lim = $mainframe->getUserStateFromRequest("com_vikrentitems.limit", 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 = VikRequest::getVar('limitstart', 0, '', 'int');
		$q = "SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikrentitems_items` ORDER BY `#__vikrentitems_items`.`name` ASC";
		$dbo->setQuery($q, $lim0, $lim);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$rows = $dbo->loadAssocList();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
			$navbut = "<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		} else {
			VikError::raiseWarning('', JText::translate('VROVERVIEWNOITEMS'));
			$mainframe->redirect("index.php?option=com_vikrentitems");
			exit;
		}

		$pmnum = VikRequest::getInt('mnum', '', 'request');
		$pmonth = VikRequest::getString('month', '', 'request');
		$cmnum = $cookie->get('vriOvwMnum', '', 'string');

		if (empty($pmonth)) {
			$sess_month = $session->get('vrcOverviewMonth', '');
			if (!empty($sess_month)) {
				$pmonth = $sess_month;
			}
		}
		if (intval($cmnum) > 0 && empty($pmnum)) {
			$pmnum = $cmnum;
		}
		if ($pmnum > 0) {
			VikRequest::setCookie('vriOvwMnum', $pmnum, (time() + (86400 * 365)), '/');
			$session->set('vriOvwMnum', $pmnum);
		} else {
			$smnum = $session->get('vriOvwMnum', '1');
			$pmnum = intval($smnum) > 0 ? $smnum : 1;
		}

		if (!empty($pmonth)) {
			$session->set('vrcOverviewMonth', $pmonth);
			$tsstart = $pmonth;
		} else {
			$oggid = getdate();
			$tsstart = mktime(0, 0, 0, $oggid['mon'], 1, $oggid['year']);
		}
		$oggid = getdate($tsstart);
		$tsend = mktime(0, 0, 0, ($oggid['mon'] + $pmnum), 1, $oggid['year']);
		$today = getdate();
		$firstmonth = mktime(0, 0, 0, $today['mon'], 1, $today['year']);
		//oldest and furthest pickups
		$oldest_ritiro = 0;
		$furthest_consegna = 0;
		$q = "SELECT `ritiro` FROM `#__vikrentitems_busy` ORDER BY `ritiro` ASC LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$oldest_ritiro = $dbo->loadResult();
		}
		$q = "SELECT `consegna` FROM `#__vikrentitems_busy` ORDER BY `consegna` DESC LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$furthest_consegna = $dbo->loadResult();
		}
		//
		$wmonthsel = "<select name=\"month\" onchange=\"document.vroverview.submit();\">\n";
		if (!empty($oldest_ritiro)) {
			$oldest_date = getdate($oldest_ritiro);
			$oldest_month = mktime(0, 0, 0, $oldest_date['mon'], 1, $oldest_date['year']);
			if ($oldest_month < $firstmonth) {
				while ($oldest_month < $firstmonth) {
					$wmonthsel .= "<option value=\"".$oldest_month."\"".($oldest_month == $tsstart ? " selected=\"selected\"" : "").">".VikRentItems::sayMonth($oldest_date['mon'])." ".$oldest_date['year']."</option>\n";
					if ($oldest_date['mon'] == 12) {
						$nextmon = 1;
						$year = $oldest_date['year'] + 1;
					} else {
						$nextmon = $oldest_date['mon'] + 1;
						$year = $oldest_date['year'];
					}
					$oldest_month = mktime(0, 0, 0, $nextmon, 1, $year);
					$oldest_date = getdate($oldest_month);
				}
			}
		}
		$wmonthsel .= "<option value=\"".$firstmonth."\"".($firstmonth == $tsstart ? " selected=\"selected\"" : "").">".VikRentItems::sayMonth($today['mon'])." ".$today['year']."</option>\n";
		$futuremonths = 12;
		if (!empty($furthest_consegna)) {
			$furthest_date = getdate($furthest_consegna);
			$furthest_month = mktime(0, 0, 0, $furthest_date['mon'], 1, $furthest_date['year']);
			if ($furthest_month > $firstmonth) {
				$monthsdiff = ceil(($furthest_month - $firstmonth) / (86400 * 30));
				$futuremonths = $monthsdiff > $futuremonths ? $monthsdiff : $futuremonths;
			}
		}
		for ($i = 1; $i < $futuremonths; $i++) {
			$newts = getdate($firstmonth);
			if ($newts['mon'] == 12) {
				$nextmon = 1;
				$year = $newts['year'] + 1;
			} else {
				$nextmon = $newts['mon'] + 1;
				$year = $newts['year'];
			}
			$firstmonth = mktime(0, 0, 0, $nextmon, 1, $year);
			$newts = getdate($firstmonth);
			$wmonthsel .= "<option value=\"".$firstmonth."\"".($firstmonth == $tsstart ? " selected=\"selected\"" : "").">".VikRentItems::sayMonth($newts['mon'])." ".$newts['year']."</option>\n";
		}
		$wmonthsel .= "</select>\n";

		$arrbusy = array();
		$actnow = time();
		$all_locations = '';
		$q = "SELECT `id`,`name` FROM `#__vikrentitems_places` ORDER BY `#__vikrentitems_places`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$all_locations = $dbo->loadAssocList();
		}
		$session = JFactory::getSession();
		$plocation = VikRequest::getInt('location', '', 'request');
		$plocationw = VikRequest::getString('locationw', '', 'request');
		$plocationw = empty($plocationw) || !in_array($plocationw, array('pickup', 'dropoff', 'both')) ? 'pickup' : $plocationw;
		if ($plocation > 0) {
			$session->set('vriViewOverviewLocation', $plocation);
		} else {
			if (isset($_REQUEST['location'])) {
				$session->set('vriViewOverviewLocation', 0);
			} else {
				$plocation = $session->get('vriViewOverviewLocation', 0);
			}
		}
		$where_clause = '';
		if ($plocation > 0) {
			$where_clause = ' AND ';
			if ($plocationw == 'both') {
				$where_clause .= '(`o`.`idplace`='.$plocation.' OR `o`.`idplace` IS NULL OR `o`.`idreturnplace`='.$plocation.' OR `o`.`idreturnplace` IS NULL)';
			} elseif ($plocationw == 'dropoff') {
				$where_clause .= '(`o`.`idreturnplace`='.$plocation.' OR `o`.`idreturnplace` IS NULL)';
			} else {
				$where_clause .= '(`o`.`idplace`='.$plocation.' OR `o`.`idplace` IS NULL)';
			}
		}
		foreach ($rows as $r) {
			$q = "SELECT `b`.*,`o`.`idplace`,`o`.`idreturnplace`,`o`.`closure`,`ob`.`idorder` FROM `#__vikrentitems_busy` AS `b` LEFT JOIN `#__vikrentitems_ordersbusy` `ob` ON `ob`.`idbusy`=`b`.`id` LEFT JOIN `#__vikrentitems_orders` `o` ON `ob`.`idorder`=`o`.`id` WHERE `b`.`iditem`='".$r['id']."'".$where_clause." AND (`b`.`ritiro`>=".$tsstart." OR `b`.`consegna`>=".$tsstart.") AND (`b`.`ritiro`<=".$tsend." OR `b`.`consegna`<=".$tsstart.");";
			$dbo->setQuery($q);
			$dbo->execute();
			$cbusy = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
			$arrbusy[$r['id']] = $cbusy;
		}
		
		$this->rows = &$rows;
		$this->lim0 = &$lim0;
		$this->navbut = &$navbut;
		$this->arrbusy = &$arrbusy;
		$this->wmonthsel = &$wmonthsel;
		$this->tsstart = &$tsstart;
		$this->all_locations = &$all_locations;
		$this->plocation = &$plocation;
		$this->plocationw = &$plocationw;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRMAINOVERVIEWTITLE'), 'vikrentitems');
		JToolBarHelper::cancel( 'cancel', JText::translate('VRBACK'));
		JToolBarHelper::spacer();
	}

}
