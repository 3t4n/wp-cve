<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * VikAppointments dashboard view.
 *
 * @since 1.0
 */
class VikAppointmentsViewvikappointments extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		// get wizard instance
		$wizard = VAPFactory::getWizard();

		if ($wizard->isDone())
		{
			VAPLoader::import('libraries.statistics.factory');

			// load active widgets
			$this->dashboard = VAPStatisticsFactory::getDashboard('dashboard');
		}
		else
		{
			/**
			 * Added support for wizard page.
			 *
			 * @since 1.7.1
			 */
			$this->setLayout('wizard');

			$this->wizard = $wizard;
		}

		// set the toolbar
		$this->addToolBar();
		
		// display the template (default.php)
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	private function addToolBar()
	{
		// add menu title and some buttons to the page
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWDASHBOARD'), 'vikappointments');

		if (JFactory::getUser()->authorise('core.access.dashboard', 'com_vikappointments'))
		{
			if (isset($this->dashboard))
			{
				// add button to manage the widgets
				JToolbarHelper::addNew('analytics.add', JText::translate('VAP_TOOLBAR_NEW_WIDGET'));
			}
			else
			{
				// add button to dismiss the wizard
				JToolbarHelper::custom('wizard.done', 'cancel', 'cancel', JText::translate('VAPWIZARDBTNDONE'), false);
			}
		}

		if (JFactory::getUser()->authorise('core.admin', 'com_vikappointments'))
		{
			JToolBarHelper::preferences('com_vikappointments');
		}
	}
}
