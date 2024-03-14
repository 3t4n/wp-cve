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
 * VikAppointments city management view.
 *
 * @since 1.5
 */
class VikAppointmentsViewmanagecity extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{	
		$app 	= JFactory::getApplication();
		$input 	= $app->input;
		$dbo 	= JFactory::getDbo();
		
		$ids  = $input->getUint('cid', array());
		$type = $ids ? 'edit' : 'new';

		$city = null;
		
		if ($type == 'edit')
		{	
			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_cities'))
				->where($dbo->qn('id') . ' = ' . $ids[0]);

			$dbo->setQuery($q, 0, 1);
			$city = $dbo->loadObject();
		}

		if (empty($city))
		{
			$city = (object) $this->getBlankItem();
		}

		// use city data stored in user city
		$this->injectUserStateData($city, 'vap.city.data');

		// load state name
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('s.state_name', 'c.country_2_code')))
			->from($dbo->qn('#__vikappointments_states', 's'))
			->leftjoin($dbo->qn('#__vikappointments_countries', 'c') . ' ON ' . $dbo->qn('c.id') . ' = ' . $dbo->qn('s.id_country'))
			->where($dbo->qn('s.id') . ' = ' . $city->id_state);

		$dbo->setQuery($q, 0, 1);
		$state = $dbo->loadObject();

		if (!$state)
		{
			// state not found, back to list
			$app->redirect('index.php?option=com_vikappointments&view=countries');
			exit;
		}

		// set the toolbar
		$this->addToolBar($type, $state->state_name);
		
		$this->city  = $city;
		$this->state = $state;

		$this->isTmpl = $input->get('tmpl') == 'component';

		// display the template
		parent::display($tpl);
	}

	/**
	 * Returns a blank item.
	 *
	 * @return 	array 	 A blank item for new requests.
	 */
	protected function getBlankItem()
	{
		$input = JFactory::getApplication()->input;

		return array(
			'id'          => 0,
			'city_name'   => '',
			'city_2_code' => '',
			'city_3_code' => '',
			'latitude'    => '',
			'longitude'   => '',
			'published'   => 1, 
			'id_state'    => $input->getUint('id_state', 0),
		);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar($type, $state_name)
	{
		// add menu title and some buttons to the page
		if ($type == 'edit')
		{
			JToolBarHelper::title(JText::sprintf('VAPMAINTITLEEDITCITY', $state_name), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::sprintf('VAPMAINTITLENEWCITY', $state_name), 'vikappointments');
		}
		
		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('city.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('city.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('city.savenew', JText::translate('VAPSAVEANDNEW'));
		}
		
		JToolBarHelper::cancel('city.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}
}
