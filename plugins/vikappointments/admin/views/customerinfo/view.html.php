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

jimport('joomla.html.pagination');
VAPLoader::import('libraries.order.factory');

/**
 * VikAppointments customer info view.
 *
 * @since 1.3
 */
class VikAppointmentsViewcustomerinfo extends JViewVAP
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

		// force blank component template
		$input->set('tmpl', 'component');
	
		$id = $input->getUint('cid', array(0), 'uint');
		$id = $id[0];

		// load customer data
		$this->customer = VikAppointments::getCustomer($id);

		if (!$this->customer)
		{
			throw new Exception('Customer not found', 404);
		}

		$langtag = JFactory::getLanguage()->getTag();

		////////////////////////
		///// APPOINTMENTS /////
		////////////////////////
		
		$app_start = $this->getListLimitStart(array(), $id, 'appointments');
		$app_limit = 5;
		$app_nav   = '';
		$app_count = 0;
		$app_total = 0;

		$this->appointments = array();

		// get any reserved codes
		$reserved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'reserved' => 1));

		$q = $dbo->getQuery(true)
			->select('SQL_CALC_FOUND_ROWS r.id')
			->from($dbo->qn('#__vikappointments_reservation', 'r'))
			->where($dbo->qn('r.id_parent') . ' > 0')
			->andWhere(array(
				$dbo->qn('r.id_user') . ' = ' . $this->customer->id,
				$dbo->qn('r.id_user') . ' <= 0 AND ' . $dbo->qn('r.purchaser_mail') . ' = ' . $dbo->q($this->customer->billing_mail),
			), 'OR')
			->order($dbo->qn('r.id') . ' DESC');

		if ($reserved)
		{
			// filter by reserved status
			$q->where($dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $reserved)) . ')');
		}

		$totalQuery = $q;
		
		$dbo->setQuery($q, $app_start, $app_limit);
		$column = $dbo->loadColumn();
		
		if ($column)
		{
			// get total number of appointments
			$dbo->setQuery('SELECT FOUND_ROWS();');
			$app_count = (int) $dbo->loadResult();

			// iterate orders found
			foreach ($column as $id_res)
			{
				// get appointment details
				$this->appointments[] = VAPOrderFactory::getAppointments($id_res, $langtag);
			}

			// create appointments pagination
			$pageNav = new JPagination($app_count, $app_start, $app_limit, 'appointments');
			$app_nav = JLayoutHelper::render('blocks.pagination', ['pageNav' => $pageNav]);

			// calculate total spent
			$totalQuery->clear('select')
				->clear('limit')
				->select(sprintf('SUM(%s) AS %s', $dbo->qn('total_cost'), $dbo->qn('total')));

			$dbo->setQuery($q);
			$app_total = (float) $dbo->loadResult();
		}
		
		$this->appNav   = $app_nav;
		$this->appCount = $app_count;
		$this->appTotal = $app_total;

		////////////////////////
		/////// PACKAGES ///////
		////////////////////////
		
		$pack_start = $this->getListLimitStart(array(), $id, 'packages');
		$pack_limit = 5;
		$pack_nav   = '';
		$pack_count = 0;
		$pack_total = 0;

		$this->packages = array();

		// get any approved codes
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array('packages' => 1, 'approved' => 1));

		$q = $dbo->getQuery(true)
			->select('SQL_CALC_FOUND_ROWS o.id')
			->from($dbo->qn('#__vikappointments_package_order', 'o'))
			->where(1)
			->andWhere(array(
				$dbo->qn('o.id_user') . ' = ' . $this->customer->id,
				$dbo->qn('o.id_user') . ' <= 0 AND ' . $dbo->qn('o.purchaser_mail') . ' = ' . $dbo->q($this->customer->billing_mail),
			), 'OR')
			->order($dbo->qn('o.id') . ' DESC');

		if ($approved)
		{
			// filter by approved status
			$q->where($dbo->qn('o.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}

		$totalQuery = $q;
		
		$dbo->setQuery($q, $pack_start, $pack_limit);
		$column = $dbo->loadColumn();
		
		if ($column)
		{
			// get total number of packages
			$dbo->setQuery('SELECT FOUND_ROWS();');
			$pack_count = (int) $dbo->loadResult();

			// iterate orders found
			foreach ($column as $id_ord)
			{
				// get package details
				$this->packages[] = VAPOrderFactory::getPackages($id_ord, $langtag);
			}

			// create packages pagination
			$pageNav = new JPagination($pack_count, $pack_start, $pack_limit, 'packages');
			$pack_nav = JLayoutHelper::render('blocks.pagination', ['pageNav' => $pageNav]);

			// calculate total spent
			$totalQuery->clear('select')
				->clear('limit')
				->select(sprintf('SUM(%s) AS %s', $dbo->qn('total_cost'), $dbo->qn('total')));

			$dbo->setQuery($q);
			$pack_total = (float) $dbo->loadResult();
		}
		
		$this->packNav   = $pack_nav;
		$this->packCount = $pack_count;
		$this->packTotal = $pack_total;

		/**
		 * Count the total number of purchased/used packages.
		 * 
		 * @since 1.7.4
		 */
		$this->packUserCount = JModelVAP::getInstance('customer')->countPackages($this->customer->id);

		////////////////////////
		////// USER NOTES //////
		////////////////////////

		$this->notes = array();
		$this->notesCount = 0;

		// load only the latest 4 modified notes (without blank content)
		$q = $dbo->getQuery(true)
			->select('SQL_CALC_FOUND_ROWS n.*')
			->from($dbo->qn('#__vikappointments_user_notes', 'n'))
			->where($dbo->qn('n.id_user') . ' = ' . $this->customer->id)
			->where($dbo->qn('n.content') . ' <> ' . $dbo->q(''))
			->order(sprintf(
				'IFNULL(%s, %s) %s',
				$dbo->qn('n.modifiedon'),
				$dbo->qn('n.createdon'),
				'DESC'
			));

		$dbo->setQuery($q, 0, 4);
		$this->notes = $dbo->loadObjectList();

		if ($this->notes)
		{	
			// get total number of notes
			$dbo->setQuery('SELECT FOUND_ROWS();');
			$this->notesCount = (int) $dbo->loadResult();
		}

		// get tag model
		$this->tagModel = JModelVAP::getInstance('tag');

		// display the template
		parent::display($tpl);
	}
}
