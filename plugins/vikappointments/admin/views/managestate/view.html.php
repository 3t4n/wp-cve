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
 * VikAppointments state management view.
 *
 * @since 1.5
 */
class VikAppointmentsViewmanagestate extends JViewVAP
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
		
		$state = null;
		
		if ($type == 'edit')
		{	
			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_states'))
				->where($dbo->qn('id') . ' = ' . $ids[0]);

			$dbo->setQuery($q, 0, 1);
			$state = $dbo->loadObject();
		}

		if (empty($state))
		{
			$state = (object) $this->getBlankItem();
		}

		// use state data stored in user state
		$this->injectUserStateData($state, 'vap.state.data');

		// load country name
		$q = $dbo->getQuery(true)
			->select($dbo->qn('country_name'))
			->from($dbo->qn('#__vikappointments_countries'))
			->where($dbo->qn('id') . ' = ' . $state->id_country);

		$dbo->setQuery($q, 0, 1);
		$countryName = $dbo->loadResult();

		if (!$countryName)
		{
			// country not found, back to list
			$app->redirect('index.php?option=com_vikappointments&view=countries');
			exit;
		}

		// set the toolbar
		$this->addToolBar($type, $countryName);
		
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
			'id'           => 0,
			'state_name'   => '',
			'state_2_code' => '',
			'state_3_code' => '',
			'published'    => 1,
			'id_country'   => $input->getUint('id_country', 0),
		);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar($type, $country_name)
	{
		// add menu title and some buttons to the page
		if ($type == 'edit')
		{
			JToolBarHelper::title(JText::sprintf('VAPMAINTITLEEDITSTATE', $country_name), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::sprintf('VAPMAINTITLENEWSTATE', $country_name), 'vikappointments');
		}
		
		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('state.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('state.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('state.savenew', JText::translate('VAPSAVEANDNEW'));
		}
		
		JToolBarHelper::cancel('state.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}
}
