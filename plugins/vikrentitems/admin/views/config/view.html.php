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

class VikRentItemsViewConfig extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$cookie = JFactory::getApplication()->input->cookie;
		$curtabid = $cookie->get('vriConfPt', '', 'string');
		$curtabid = empty($curtabid) ? 1 : (int)$curtabid;

		$this->curtabid = &$curtabid;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRMAINCONFIGTITLE'), 'vikrentitemsconfig');
		if (JFactory::getUser()->authorise('core.edit', 'com_vikrentitems')) {
			JToolBarHelper::apply( 'saveconfig', JText::translate('VRSAVE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancel', JText::translate('VRANNULLA'));
		JToolBarHelper::spacer();
	}

}
