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
 * VikAppointments service management view.
 *
 * @since 1.0
 */
class VikAppointmentsViewmanageservice extends JViewVAP
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
		
		$ids  = $input->getUint('cid', array());
		$type = $ids ? 'edit' : 'new';
		
		// set the toolbar
		$this->addToolBar($type);
		
		$service = null;
		
		if ($type == 'edit')
		{
			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_service'))
				->where($dbo->qn('id') . ' = ' . $ids[0]);
			
			$dbo->setQuery($q, 0, 1);
			$service = $dbo->loadObject();
			
			if ($service)
			{
				// decode attachments, if any
				$service->attachments = $service->attachments ? (array) json_decode($service->attachments, true) : array();

				// get assigned options		
				$q = $dbo->getQuery(true)
					->select($dbo->qn(array('o.id', 'o.name', 'o.published')))
					->select($dbo->qn('a.id', 'id_assoc'))
					->from($dbo->qn('#__vikappointments_option', 'o'))
					->leftjoin($dbo->qn('#__vikappointments_ser_opt_assoc', 'a') . ' ON ' . $dbo->qn('o.id') . ' = ' . $dbo->qn('a.id_option'))
					->where($dbo->qn('a.id_service') . ' = ' . $service->id);

				$dbo->setQuery($q);
				$service->options = $dbo->loadObjectList();

				// get assigned employees
				$q = $dbo->getQuery(true)
					->select('a.*')
					->select($dbo->qn('e.nickname'))
					->from($dbo->qn('#__vikappointments_employee', 'e'))
					->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('a.id_employee'))
					->where($dbo->qn('a.id_service') . ' = ' . $service->id)
					->order($dbo->qn('a.ordering') . ' ASC');

				$dbo->setQuery($q);
				$service->employees = $dbo->loadObjectList();
			}
		}

		if (empty($service))
		{
			$service = (object) $this->getBlankItem();
		}

		// use service data stored in user state
		$this->injectUserStateData($service, 'vap.service.data');

		/**
		 * Load com_contents language file to obtain metadata 
		 * labels and descriptions.
		 *
		 * @since 1.6.1
		 */
		JFactory::getLanguage()->load('com_content', JPATH_ADMINISTRATOR);
		
		$this->service = $service;

		// display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar($type)
	{
		// add menu title and some buttons to the page
		if ($type == 'edit')
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLEEDITSERVICE'), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLENEWSERVICE'), 'vikappointments');
		}
		
		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('service.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('service.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('service.savenew', JText::translate('VAPSAVEANDNEW'));
		}

		if ($type == 'edit' && $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2copy('service.savecopy', JText::translate('VAPSAVEASCOPY'));
		}
		
		JToolBarHelper::cancel('service.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}

	/**
	 * Returns a blank item.
	 *
	 * @return 	array 	A blank item for new requests.
	 */
	protected function getBlankItem()
	{
		return array(
			'id'                 => 0,
			'name'               => '',
			'alias'              => '',
			'description'        => '',
			'duration'           => 60,
			'sleep'              => 0,
			'interval'           => 1,
			'minrestr'           => -1,
			'mindate'            => -1,
			'maxdate'            => -1,
			'price'              => 0.0,
			'id_tax'             => 0,
			'max_capacity'       => 1,
			'min_per_res'        => 1,
			'max_per_res'        => 1,
			'priceperpeople'     => 1,
			'app_per_slot'       => 1,
			'published'          => 1,
			'quick_contact'      => 0,
			'choose_emp'         => 0,
			'random_emp'         => 0,
			'has_own_cal'        => 0,
			'checkout_selection' => 0,
			'display_seats'      => 0,
			'enablezip'          => 0,
			'use_recurrence'     => 0,
			'image'              => '',
			'start_publishing'   => '',
			'end_publishing'     => '',
			'id_group'           => 0,
			'level'              => 1,
			'metadata'           => null,
			'attachments'        => array(),
			'employees'          => array(),
			'options'            => array(),
		);
	}
}
