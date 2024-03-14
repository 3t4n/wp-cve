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

class VikRentItemsViewChoosebusy extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$rows = "";
		$navbut = "";
		$dbo = JFactory::getDBO();
		$mainframe = JFactory::getApplication();
		$pts = VikRequest::getInt('ts', '', 'request');
		$piditem = VikRequest::getInt('iditem', '', 'request');
		if (empty($pts) || empty($piditem)) {
			VikError::raiseWarning('', 'Not found.');
			$mainframe->redirect("index.php?option=com_vikrentitems&task=orders");
			exit;
		}
		//ultimo secondo del giorno scelto
		$realritiro = $pts + 86399;
		//
		$q = "SELECT COUNT(*) FROM `#__vikrentitems_busy` AS `b` WHERE `b`.`iditem`='".intval($piditem)."' AND `b`.`ritiro`<='".$realritiro."' AND `b`.`consegna`>='".$pts."';";
		$dbo->setQuery($q);
		$dbo->execute();
		$totres = $dbo->loadResult();

		$lim = $mainframe->getUserStateFromRequest("com_vikrentitems.limit", 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 = VikRequest::getVar('limitstart', 0, '', 'int');
		$q = "SELECT SQL_CALC_FOUND_ROWS `b`.`id`,`b`.`iditem`,`b`.`ritiro`,`b`.`consegna`,`b`.`realback`,`ob`.`idorder`,`o`.`custdata`,`o`.`ts`,`o`.`country`,`o`.`nominative`,`o`.`closure`,`i`.`name`,`i`.`img`,`i`.`units`,`i`.`params`,(SELECT SUM(`oi`.`itemquant`) FROM `#__vikrentitems_ordersitems` AS `oi` WHERE `oi`.`idorder`=`ob`.`idorder` AND `oi`.`iditem`=`b`.`iditem`) AS `totquant` FROM `#__vikrentitems_busy` AS `b`,`#__vikrentitems_orders` AS `o`,`#__vikrentitems_items` AS `i`,`#__vikrentitems_ordersbusy` AS `ob` WHERE `b`.`iditem`='".intval($piditem)."' AND `b`.`ritiro`<='".$realritiro."' AND `b`.`consegna`>='".$pts."' AND `ob`.`idbusy`=`b`.`id` AND `ob`.`idorder`=`o`.`id` AND `i`.`id`=`b`.`iditem` GROUP BY `ob`.`idorder` ORDER BY `b`.`ritiro` ASC";
		$dbo->setQuery($q, $lim0, $lim);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$rows = $dbo->loadAssocList();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
			$navbut = "<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		} else {
			VikError::raiseWarning('', 'No records.');
			$mainframe->redirect("index.php?option=com_vikrentitems&task=orders");
			exit;
		}
		
		$this->rows = &$rows;
		$this->lim0 = &$lim0;
		$this->navbut = &$navbut;
		$this->totres = &$totres;
		$this->pts = &$pts;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		$dbo = JFactory::getDBO();
		$pgoto = VikRequest::getString('goto', '', 'request');
		$pts = VikRequest::getInt('ts', '', 'request');
		$piditem = VikRequest::getInt('iditem', '', 'request');
		$q = "SELECT `name` FROM `#__vikrentitems_items` WHERE `id`=".$dbo->quote($piditem).";";
		$dbo->setQuery($q);
		$dbo->execute();
		$cname=$dbo->loadResult();
		JToolBarHelper::title(JText::translate('VRMAINCHOOSEBUSY')." ".$cname.", ".date('Y-M-d', $pts), 'vikrentitems');
		JToolBarHelper::cancel( ($pgoto == 'overv' ? 'canceloverv' : 'cancelcalendar'), JText::translate('VRBACK'));
	}

}
