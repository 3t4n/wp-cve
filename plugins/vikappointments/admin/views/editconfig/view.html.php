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
 * VikAppointments global configuration view.
 *
 * @since 1.0
 */
class VikAppointmentsVieweditconfig extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{	
		$dbo = JFactory::getDbo();

		// set the toolbar
		$this->addToolBar();

		// get config
		
		$params = array();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('param', 'setting')))
			->from($dbo->qn('#__vikappointments_config'));
		
		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			foreach ($dbo->loadObjectList() as $row)
			{
				$params[$row->param] = $row->setting;
			}
		}

		// import custom fields loader
		VAPLoader::import('libraries.customfields.loader');

		// get relevant custom fields only
		$this->customFields = VAPCustomFieldsLoader::getInstance()
			->translate()
			->noRequiredCheckbox()
			->noSeparator()
			->fetch();
		
		$this->params = $params;

		// display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar()
	{
		// add menu title and some buttons to the page
		JToolBarHelper::title(JText::translate('VAPMAINTITLECONFIG'), 'vikappointments');
		
		if (JFactory::getUser()->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::apply('configuration.save', JText::translate('VAPSAVE'));
		}
	
		JToolBarHelper::cancel('dashboard.cancel', 'JTOOLBAR_CLOSE');
	}
}
