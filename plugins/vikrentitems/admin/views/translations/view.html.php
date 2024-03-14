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

class VikRentItemsViewTranslations extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		$vri_tn = VikRentItems::getTranslator();
		
		$this->vri_tn = &$vri_tn;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRIMAINTRANSLATIONSTITLE'), 'vikrentitems');
		if (JFactory::getUser()->authorise('core.create', 'com_vikrentitems')) {
			JToolBarHelper::apply( 'savetranslationstay', JText::translate('VRSAVE'));
			JToolBarHelper::spacer();
			JToolBarHelper::save( 'savetranslation', JText::translate('VRSAVECLOSE'));
			JToolBarHelper::spacer();
		}
		JToolBarHelper::cancel( 'cancel', JText::translate('VRBACK'));
		JToolBarHelper::spacer();
	}

}
