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
 * VikAppointments reservations view.
 *
 * @since 1.0
 */
class VikAppointmentsViewreservations extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$dbo   = JFactory::getDbo();

		// set the toolbar
		$this->addToolBar();

		$filters = array();
		$filters['keysearch']   = $app->getUserStateFromRequest($this->getPoolName() . '.keysearch', 'keysearch', '', 'string');
		$filters['status']      = $app->getUserStateFromRequest($this->getPoolName() . '.status', 'status', '', 'string');
		$filters['id_payment']  = $app->getUserStateFromRequest($this->getPoolName() . '.id_payment', 'id_payment', 0, 'uint');
		$filters['datestart']   = $app->getUserStateFromRequest($this->getPoolName() . '.datestart', 'datestart', '', 'string');
		$filters['dateend']     = $app->getUserStateFromRequest($this->getPoolName() . '.dateend', 'dateend', '', 'string');
		$filters['id_location'] = $app->getUserStateFromRequest($this->getPoolName() . '.id_location', 'id_location', 0, 'uint');
		$filters['res_id']      = $app->getUserStateFromRequest($this->getPoolName() . '.res_id', 'res_id', 0, 'uint');

		$this->filters = $filters;

		$this->ordering = $app->getUserStateFromRequest($this->getPoolName() . '.ordering', 'filter_order', 'r.createdon', 'string');
		$this->orderDir = $app->getUserStateFromRequest($this->getPoolName() . '.orderdir', 'filter_order_Dir', 'DESC', 'string');

		// db object
		$lim 	= $app->getUserStateFromRequest('com_vikappointments.limit', 'limit', $app->get('list_limit'), 'uint');
		$lim0 	= $this->getListLimitStart($filters);
		$navbut	= "";

		$rows = array();

		// get reservations details

		$search_has_id = false;

		$q = $dbo->getQuery(true);

		$q->select('SQL_CALC_FOUND_ROWS r.*')
			->select(array(
				$dbo->qn('e.nickname', 'employee_name'),
				$dbo->qn('e.timezone'),
				$dbo->qn('s.name', 'service_name'),
				$dbo->qn('p.name', 'payment_name'),
				$dbo->qn('u.name', 'author'),
			))
			->from($dbo->qn('#__vikappointments_reservation', 'r'))
			->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('r.id_service') . ' = ' . $dbo->qn('s.id'))
			->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('r.id_employee') . ' = ' . $dbo->qn('e.id'))
			->leftjoin($dbo->qn('#__vikappointments_gpayments', 'p') . ' ON ' . $dbo->qn('r.id_payment') . ' = ' . $dbo->qn('p.id'))
			->leftjoin($dbo->qn('#__users', 'u') . ' ON ' . $dbo->qn('r.createdby') . ' = ' . $dbo->qn('u.id'))
			->where(1)
			->order($dbo->qn($this->ordering) . ' ' . $this->orderDir);

		if ($this->ordering == 'r.createdon')
		{
			// then follow the ID ordering because 2 appointments might
			// be created simultaneously
			$q->order($dbo->qn('r.id') . ' ' . $this->orderDir);
		}

		if (strlen($filters['keysearch']))
		{
			$where = array(
				$dbo->qn('r.purchaser_nominative') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"),
				$dbo->qn('r.purchaser_mail') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"),
				$dbo->qn('r.purchaser_phone') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%"),
			);

			/**
			 * Reverse the search key in order to try finding
			 * users by name even if it was wrote in the opposite way.
			 * If we searched by "John Smith", the system will search
			 * for "Smith John" too.
			 *
			 * @since 1.7
			 */
			$reverse = preg_split("/\s+/", $filters['keysearch']);
			$reverse = array_reverse($reverse);
			$reverse = implode(' ', $reverse);

			$where[] = $dbo->qn('r.purchaser_nominative') . ' LIKE ' . $dbo->q("%{$reverse}%");

			// filter by service
			$where[] = $dbo->qn('s.name') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%");
			
			/**
			 * Reverse the search key in order to try finding employees
			 * by name even if it was wrote in the opposite way.
			 * If we searched by "John Smith", the system will search
			 * for "Smith John" too.
			 *
			 * @since 1.7
			 */
			$where[] = $dbo->qn('e.nickname') . ' LIKE ' . $dbo->q("%{$filters['keysearch']}%");
			$where[] = $dbo->qn('e.nickname') . ' LIKE ' . $dbo->q("%{$reverse}%");

			// filter by coupon code (must start with the code)
			$where[] = $dbo->qn('coupon_str') . ' LIKE ' . $dbo->q("{$filters['keysearch']}%");

			/**
			 * It is now possible to search reservations by ID/SID and
			 * user ID through the main key search input.
			 *
			 * @since 1.7
			 */
			if (preg_match("/^[A-Z0-9]{16,16}$/i", $filters['keysearch']))
			{
				// alphanumeric string of 16 characters, we are probably searching for "SID"
				$where[] = $dbo->qn('r.sid') . ' = ' . $dbo->q($filters['keysearch']);
			}
			else if (preg_match("/^\d+\-[A-Z0-9]{16,16}$/i", $filters['keysearch']))
			{
				// we are probably searching for "ID" - "SID"
				$where[] = sprintf('CONCAT_WS(\'-\', %s, %s) = %s', $dbo->qn('r.id'), $dbo->qn('r.sid'), $dbo->q($filters['keysearch']));
			}
			else if (preg_match("/^id:\s*(\d+)/i", $filters['keysearch'], $match))
			{
				// we are searching by ID
				$where[] = $dbo->qn('r.id') . ' = ' . (int) $match[1];

				$search_has_id = true;
			}
			else if (preg_match("/^id_user:\s*(\d+)/i", $filters['keysearch'], $match))
			{
				// we are searching by user ID
				$where[] = $dbo->qn('r.id_user') . ' = ' . (int) $match[1];
			}

			$q->andWhere($where, 'OR');
		}

		if (!empty($filters['status']))
		{
			if ($filters['status'] != 'CLOSURE')
			{
				$q->where(array(
					$dbo->qn('status') . ' = ' . $dbo->q($filters['status']),
					$dbo->qn('r.closure') . ' = 0',
				));
			}
			else
			{
				$q->where($dbo->qn('r.closure') . ' = 1');
			}
		}

		if ($filters['id_payment'])
		{
			$q->where($dbo->qn('id_payment') . ' = ' . $filters['id_payment']);
		}

		if (VAPDateHelper::isNull($filters['datestart']) === false)
		{
			/**
			 * Added support for end date filter.
			 *
			 * @since 1.7
			 */
			if (VAPDateHelper::isNull($filters['dateend']))
			{
				// missing end-date, fetch appointments for the selected date
				$filters['dateend'] = $filters['datestart'];
			}

			/**
			 * Get locale SQL strings to have dates adjusted to the current
			 * timezone. This way the dates will be refactored for being
			 * used in UTC, even if the locale is different.
			 *
			 * @since 1.7
			 */
			$start = VAPDateHelper::getSqlDateLocale($filters['datestart'],  0,  0,  0);
			$end   = VAPDateHelper::getSqlDateLocale($filters['dateend'], 23, 59, 59);

			$q->where($dbo->qn('r.checkin_ts') . ' BETWEEN ' . $dbo->q($start) . ' AND ' . $dbo->q($end));
		}

		/**
		 * Added support for location filter.
		 *
		 * @since 1.7
		 */
		if ($filters['id_location'])
		{
			$q->leftjoin($dbo->qn('#__vikappointments_emp_worktime', 'w')
				. ' ON ' . $dbo->qn('w.id_service') . ' = ' . $dbo->qn('r.id_service') . ' AND ' . $dbo->qn('w.id_employee') . ' = ' . $dbo->qn('r.id_employee'));
			
			// filter working days by time
			$q->where(sprintf(
				'CAST(DATE_FORMAT(%1$s, \'%%H\') AS unsigned) * 60 + CAST(DATE_FORMAT(%1$s, \'%%i\') AS unsigned) BETWEEN %2$s AND %3$s',
				$dbo->qn('r.checkin_ts'),
				$dbo->qn('w.fromts'),
				$dbo->qn('w.endts')
			));
			   
			// filter by date/day
			$q->where(sprintf(
				'(%1$s = CAST(DATE_FORMAT(%2$s, \'%%w\') AS unsigned) AND %3$s <= 0) OR %4$s = DATE_FORMAT(%2$s, \'%%Y-%%m-%%d\')',
				$dbo->qn('w.day'),
				$dbo->qn('r.checkin_ts'),
				$dbo->qn('w.ts'),
				$dbo->qn('w.tsdate')
			));

			// filter by location
			$q->where($dbo->qn('w.id_location') . ' = ' . $filters['id_location']);

			// avoid duplicates
			$q->group($dbo->qn('r.id'));
		}

		// hide the parent orders if the reservations are not sorted by ID or
		// if we are searching by check-in date/location
		if (!in_array($this->ordering, array('r.id', 'r.createdon')) || $filters['datestart'] || $filters['id_location'])
		{
			$q->where($dbo->qn('r.id_parent') . ' <> -1');
		}
		// otherwise hide the children reservations (only if we are not filtering
		// or searching by reservation ID.
		else if ($filters['res_id'] == 0 && !$search_has_id)
		{
			$q->andWhere(array(
				$dbo->qn('r.id_parent') . ' = -1',
				$dbo->qn('r.id_parent') . ' = ' . $dbo->qn('r.id'),
			), 'OR');
		}

		if ($filters['res_id'])
		{
			// clear any other filter
			$q->clear('where');
			$q->where(1);

			$q->andWhere(array(
				$dbo->qn('r.id') . ' = ' . $filters['res_id'],
				$dbo->qn('r.id_parent') . ' = ' . $filters['res_id'],
			), 'OR');
		}

		// hide closures in case the ordering is not supported
		$closure_columns = array(
			'r.id',
			'r.createdon',
			'r.checkin_ts',
			'r.status',
			'e.nickname',
		);

		if (!in_array($this->ordering, $closure_columns))
		{
			$q->where($dbo->qn('r.closure') . ' = 0');
		}

		/**
		 * Add support for manipulating query through the plugins.
		 *
		 * @see 	/site/helpers/libraries/mvc/view.php @ JViewVAP::onBeforeListQuery()
		 *
		 * @since 	1.6.2
		 */
		$this->onBeforeListQuery($q);
		
		$dbo->setQuery($q, $lim0, $lim);
		$dbo->execute();

		// assert limit used for list query
		$this->assertListQuery($lim0, $lim);

		if ($dbo->getNumRows())
		{
			$rows = $dbo->loadAssocList();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination($dbo->loadResult(), $lim0, $lim);
			$navbut = JLayoutHelper::render('blocks.pagination', ['pageNav' => $pageNav]);
		}

		$invoiceModel = JModelVAP::getInstance('invoice');

		foreach ($rows as $k => $row)
		{
			$id_order = $row['id'];

			if ($row['id_parent'] != -1 && $row['id'] != $row['id_parent'])
			{
				$id_order = $row['id_parent'];
			}

			// load invoice details
			$rows[$k]['invoice'] = $invoiceModel->getInvoice($id_order, 'appointments');

			if (!$rows[$k]['invoice'])
			{
				// try to check whether we have an invoice file, since invoices generated
				// before the 1.7 version are not stored within the database
				$rows[$k]['invoice'] = $invoiceModel->getInvoiceBC($id_order, $row['sid'], 'appointments');
			}
		}

		// import custom fields loader
		VAPLoader::import('libraries.customfields.loader');

		// get relevant custom fields only
		$this->customFields = VAPCustomFieldsLoader::getInstance()
			->translate()
			->noRequiredCheckbox()
			->noSeparator()
			->noInputFile()
			->fetch();
		
		$this->rows   = $rows;
		$this->navbut = $navbut;
		
		// display the template (default.php)
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
		JToolBarHelper::title(JText::translate('VAPMAINTITLEVIEWRESERVATIONS'), 'vikappointments');

		$user = JFactory::getUser();
		
		if ($user->authorise('core.create', 'com_vikappointments'))
		{
			JToolBarHelper::addNew('findreservation');
		}
		
		if ($user->authorise('core.edit', 'com_vikappointments'))
		{	
			JToolBarHelper::editList('reservation.edit');
		}

		if ($user->authorise('core.create', 'com_vikappointments') && VAPFactory::getConfig()->getBool('enablerecur'))
		{
			JToolBarHelper::custom('makerecurrence', 'loop', 'loop', JText::translate('VAPMAKERECURRENCE'), true);
		}

		JToolBarHelper::custom('exportres.add', 'download', 'download', JText::translate('VAPEXPORT'), false);
		JToolBarHelper::custom('printorders', 'print', 'print', JText::translate('VAPPRINT'), true);

		if ($this->isApiSmsConfigured())
		{
			JToolBarHelper::custom('reservation.sendsms', 'comment', 'comment', JText::translate('VAPSENDSMS'), true);
		}

		if ($user->authorise('core.create', 'com_vikappointments'))
		{	
			JToolBarHelper::custom('invoice.generate', 'vcard', 'vcard', JText::translate('VAPINVOICE'), true);
			JToolBarHelper::custom('closure.add', 'unpublish', 'unpublish', JText::translate('VAPBLOCK'), false);
		}
		
		if ($user->authorise('core.delete', 'com_vikappointments'))
		{
			JToolBarHelper::deleteList(VikAppointments::getConfirmSystemMessage(), 'reservation.delete');
		}
	}

	/**
	 * Checks for advanced filters set in the request.
	 *
	 * @return 	boolean  True if active, otherwise false.
	 */
	protected function hasFilters()
	{
		if ($this->filters['res_id'])
		{
			return false;
		}

		return (!empty($this->filters['status'])
			|| $this->filters['id_payment']
			|| $this->filters['id_location']
			|| !VAPDateHelper::isNull($this->filters['datestart']));
	}
	
	/**
	 * Check if the SMS API is configured.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 */
	protected function isApiSmsConfigured()
	{
		// first of all, check ACL
		if (!JFactory::getUser()->authorise('core.edit.state', 'com_vikappointments'))
		{
			return false;
		}

		try
		{
			// try to instantiate the SMS API provider
			$provider = VAPApplication::getInstance()->getSmsInstance();
		}
		catch (Exception $e)
		{
			// SMS provider not configured
			return false;
		}

		// provider (probably) configured
		return true;
	}
}
