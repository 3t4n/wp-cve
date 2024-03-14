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
 * VikAppointments employee management view.
 *
 * @since 1.0
 */
class VikAppointmentsViewmanageemployee extends JViewVAP
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

		// load wd options

		$wd_options = array();
		$wd_options['hide_past_wd'] = $input->cookie->getBool('vikappointments_hide_past_wd', true);
		
		$employee = array();
		
		$worktime_week = array();
		$worktime_date = array();
		
		if ($type == 'edit')
		{
			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_employee'))
				->where($dbo->qn('id') . ' = ' . $ids[0]);

			$dbo->setQuery($q, 0, 1);
			$employee = $dbo->loadObject();

			$config = VAPFactory::getConfig();

			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_emp_worktime'))
				->where(array(
					$dbo->qn('id_employee') . ' = ' . $ids[0],
					$dbo->qn('id_service') . ' = -1',
				))
				->order(array(
					$dbo->qn('ts') . ' ASC',
					$dbo->qn('day') . ' ASC',
					$dbo->qn('fromts') . ' ASC',
					$dbo->qn('closed') . ' ASC',
				));

			if ($wd_options['hide_past_wd'])
			{
				// exclude all working days created for past days
				$threshold = strtotime('00:00:00');

				$q->andWhere(array(
					$dbo->qn('ts') . ' = -1',
					$dbo->qn('ts') . ' >= ' . $threshold,
				));
			}
			
			$dbo->setQuery($q);

			foreach ($dbo->loadObjectList() as $w)
			{
				// create shorten aliases
				$w->from = $w->fromts;
				$w->to 	 = $w->endts;

				if ($w->ts == -1)
				{
					if (!isset($worktime_week[$w->day]))
					{
						$worktime_week[$w->day] = array();
					}

					// group under day of the week
					$worktime_week[$w->day][] = $w;
				}
				else
				{
					// create instance from GMT date
					$dt = JDate::getInstance($w->tsdate);

					// create YMD date
					$w->ymd = $dt->format('Ymd');
					// create system date
					$w->date = $dt->format($config->get('dateformat'));

					if (!isset($worktime_date[$w->ymd]))
					{
						$worktime_date[$w->ymd] = array();
					}

					// group under special day
					$worktime_date[$w->ymd][] = $w;
				}
			}
		}

		if (empty($employee))
		{
			$employee = (object) $this->getBlankItem();
		}

		// use employee data stored in user state
		$this->injectUserStateData($employee, 'vap.employee.data');

		// fetch CMS users
		$inner = $dbo->getQuery(true)
			->select(1)
			->from($dbo->qn('#__vikappointments_employee', 'a'))
			->where($dbo->qn('a.jid') . ' = ' . $dbo->qn('u.id'));

		$q = $dbo->getQuery(true);

		$q->select($dbo->qn(array('u.id', 'u.name', 'u.username', 'u.email')))
			->from($dbo->qn('#__users', 'u'))
			->where($dbo->qn('u.id') . ' = ' . (int) $employee->jid, 'OR')
			->where('NOT EXISTS (' . $inner . ')')
			->order($dbo->qn('u.name') . ' ASC');
		
		$dbo->setQuery($q);
		$users = $dbo->loadObjectList();

		// subscriptions

		$hasSubscr = VikAppointments::isSubscriptions();

		// import custom fields renderer and loader (as dependency)
		VAPLoader::import('libraries.customfields.renderer');

		// get relevant custom fields only
		$this->customFields = VAPCustomFieldsLoader::getInstance()
			->employees()
			->translate()
			->noRequiredCheckbox()
			->fetch();
		
		$this->employee     = $employee;
		$this->worktimeWeek = $worktime_week;
		$this->worktimeDate = $worktime_date;
		$this->wdOptions    = $wd_options;
		$this->users        = $users;
		$this->hasSubscr    = $hasSubscr;

		// display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @param 	string 	$type 	The request type (new or edit).
	 *
	 * @return 	void
	 */
	protected function addToolBar($type)
	{
		// add menu title and some buttons to the page
		if ($type == 'edit')
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLEEDITEMPLOYEE'), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLENEWEMPLOYEE'), 'vikappointments');
		}
		
		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('employee.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('employee.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('employee.savenew', JText::translate('VAPSAVEANDNEW'));
		}

		if ($type == 'edit' && $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2copy('employee.savecopy', JText::translate('VAPSAVEASCOPY'));
		}
		
		JToolBarHelper::cancel('employee.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}

	/**
	 * Returns a blank item.
	 *
	 * @return 	array 	A blank item for new requests.
	 */
	protected function getBlankItem()
	{
		return array(
			'id'             => 0,
			'firstname'      => '',
  			'lastname'       => '',
  			'nickname'       => '',
  			'alias'          => '',
  			'email'          => '',
  			'phone'          => '',
  			'notify'         => 0,
  			'showphone'      => 0,
  			'quick_contact'  => 0,
  			'listable'       => 1,
  			'image'          => '',
  			'note'           => '',
  			'jid'            => -1,
  			'id_group'       => 0,
  			'active_to'      => -1,
  			'active_to_date' => '',
  			'timezone'       => '',
  			'ical_url'       => '',
  			'synckey'        => VikAppointments::generateSerialCode(12, 'employee-synckey'),
		);
	}
}
