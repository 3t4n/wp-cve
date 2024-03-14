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
 * VikAppointments rates test view.
 *
 * @since 1.6
 */
class VikAppointmentsViewratestest extends JViewVAP
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

		$layout = $input->get('layout');

		$args = array();

		if ($layout == 'quick')
		{
			/**
			 * QUICK LAYOUT
			 */

			$args['id_service']  = $input->getUint('id_service', 0);
			$args['id_employee'] = $input->getUint('id_employee', 0);
			$args['checkin']     = $input->getString('checkin', '');
			$args['people']      = $input->getUint('people', 1);

			$args['id_user']   = $input->getUint('uid', 0);
			$args['juser']     = $input->getUint('jid', 0);
			$args['usergroup'] = $input->getUint('usergroup', 0);

			$service = new stdClass;

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('max_capacity', 'priceperpeople')))
				->from($dbo->qn('#__vikappointments_service'))
				->where($dbo->qn('id') . ' = ' . $args['id_service']);

			$dbo->setQuery($q, 0, 1);
			$service = $dbo->loadObject();

			if (!$service)
			{
				throw new Exception('Service not found', 404);
			}

			$trace 	= array('debug' => array());

			if ($args['usergroup'])
			{
				// inject property to force usergroup
				$trace['usergroup']	= $args['usergroup'];
			}
			else if ($args['juser'])
			{
				// inject property to force the usergroup 
				// related to the specified Joomla user
				$trace['id_user'] = $args['juser'];
			}
			else if ($args['id_user'])
			{
				// inject property to force the usergroup 
				// related to the specified customer
				$q = $dbo->getQuery(true)
					->select($dbo->qn('jid'))
					->from($dbo->qn('#__vikappointments_users'))
					->where($dbo->qn('id') . ' = ' . $args['id_user']);

				$dbo->setQuery($q, 0, 1);
				$trace['id_user'] = (int) $dbo->loadResult();
			}

			// auto-calculate the rate for the given parameters
			$rate = VAPSpecialRates::getRate($args['id_service'], $args['id_employee'], $args['checkin'], $args['people'], $trace);

			$finalcost = $rate;

			if ($service->max_capacity > 1 && $service->priceperpeople == 1)
			{
				$finalcost *= $args['people'];
			}

			// register final cost per user within the trace
			$trace['finalcostperuser'] = $rate;
			
			// register final cost within the trace
			$trace['finalcost'] = $finalcost;

			$this->trace     = $trace;
			$this->rate      = $rate;
			$this->finalCost = $finalcost;

			$this->setLayout($layout);
		}
		else
		{
			/**
			 * DEFAULT LAYOUT
			 */

			$args['id_service']  = $app->getUserState('ratestest.id_service', 0);
			$args['id_employee'] = $app->getUserState('ratestest.id_employee', 0);
			$args['usergroup']   = $app->getUserState('ratestest.usergroup', 0);
			$args['checkin']     = $app->getUserState('ratestest.checkin', null);
			$args['people']      = $app->getUserState('ratestest.people', 1);
			$args['debug']       = $app->getUserState('ratestest.debug', 0);

			// get services

			$services = array();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('s.id', 's.name', 's.id_group', 's.max_capacity', 's.min_per_res', 's.max_per_res', 's.priceperpeople')))
				->select($dbo->qn('g.name', 'group_name'))
				->from($dbo->qn('#__vikappointments_service', 's'))
				->leftjoin($dbo->qn('#__vikappointments_group', 'g') . ' ON ' . $dbo->qn('s.id_group') . ' = ' . $dbo->qn('g.id'))
				->order(array(
					$dbo->qn('g.ordering') . ' ASC',
					$dbo->qn('s.ordering') . ' ASC',
				));

			$dbo->setQuery($q);
			
			foreach ($dbo->loadObjectList() as $s)
			{
				$id_group = $s->id_group <= 0 ? 0 : (int) $s->id_group;

				if (!isset($services[$id_group]))
				{
					$group = new stdClass;
					$group->id   = $id_group;
					$group->name = $s->group_name;
					$group->list = array();

					$services[$id_group] = $group;
				}

				$range = array(1, 1);

				if ($s->max_capacity > 1)
				{
					$range = array($s->min_per_res, $s->max_per_res);
				}

				$service = new stdClass;
				$service->id             = $s->id;
				$service->name           = $s->name;
				$service->capacity       = $range;
				$service->priceperpeople = $s->priceperpeople;

				$services[$id_group]->list[] = $service;
			}

			if (isset($services[0]))
			{
				// always move services without group at the end of the list
				$tmp = $services[0];
				unset($services[0]);
				$services[0] = $tmp;
			}

			$this->services = $services;
		}
		
		$this->args = $args;
		
		// display the template (default.php)
		parent::display($tpl);
	}
}
