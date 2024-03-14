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
 * VikAppointments employee-services relations management view.
 *
 * @since 1.3
 */
class VikAppointmentsViewemprates extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		$dbo   = JFactory::getDbo();
		$app   = JFactory::getApplication();
		$input = $app->input;
		
		$ids = $input->get('cid', array(0), 'uint');

		$q = $dbo->getQuery(true)
			->select($dbo->qn('nickname'))
			->from($dbo->qn('#__vikappointments_employee'))
			->where($dbo->qn('id') . ' = ' . $ids[0]);
		
		$dbo->setQuery($q, 0, 1);
		$employeeName = $dbo->loadResult();

		if (!$employeeName)
		{
			// employee not found, back to the list
			$app->redirect('index.php?option=com_vikappointments&view=employees');
		}

		// set the toolbar
		$this->addToolBar($employeeName);

		// load services
		$services = array();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array(
				's.id', 's.name', 's.price', 's.duration', 's.sleep',
			)))
			->from($dbo->qn('#__vikappointments_service', 's'))
			->order($dbo->qn('s.ordering') . ' ASC');

		$dbo->setQuery($q);
		
		// map services by ID for easy access
		foreach ($dbo->loadObjectList() as $service)
		{
			$services[$service->id] = $service;
		}

		// load overrides
		$assigned = array();

		$q = $dbo->getQuery(true)
			->select('a.*')
			->from($dbo->qn('#__vikappointments_ser_emp_assoc', 'a'))
			->where($dbo->qn('a.id_employee') . ' = ' . $ids[0])
			->order($dbo->qn('a.id') . ' ASC');

		$dbo->setQuery($q);
		
		// assign service name to relations
		foreach ($dbo->loadObjectList() as $assoc)
		{
			$assoc->name = $services[$assoc->id_service]->name;

			$assigned[] = $assoc;
		}
		
		$this->services   = $services;
		$this->assigned   = $assigned;
		$this->idEmployee = $ids[0];
		
		// display the template (default.php)
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar($nickname)
	{
		// add menu title and some buttons to the page
		JToolBarHelper::title(JText::sprintf('VAPMAINTITLEVIEWEMPRATES', $nickname), 'vikappointments');
	
		if (JFactory::getUser()->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::apply('emprates.save', JText::translate('VAPSAVE'));
			JToolBarHelper::save('emprates.saveclose', JText::translate('VAPSAVEANDCLOSE'));
			JToolBarHelper::divider();
		}
		
		JToolBarHelper::cancel('employee.cancel');
	}	
}
