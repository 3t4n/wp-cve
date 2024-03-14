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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments all orders list view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelAllorders extends JModelVAP
{
	/**
	 * The list view pagination object.
	 *
	 * @var JPagination
	 */
	protected $pagination = null;

	/**
	 * The total number of fetched rows.
	 *
	 * @var integer
	 */
	protected $total = 0;

	/**
	 * Loads a list of appointments to be displayed within the
	 * all orders site view.
	 *
	 * @param 	array  &$options  An array of options.
	 *
	 * @return 	array  A list of orders.
	 */
	public function getItems(array &$options = array())
	{
		// always reset pagination and total count
		$this->pagination = null;
		$this->total      = 0;

		$dispatcher = VAPFactory::getEventDispatcher();

		// get currently logged in user
		$user = JFactory::getUser();

		if ($user->guest)
		{
			// do not load orders assigned to guest user
			return array();
		}

		if (!array_key_exists('start', $options))
		{
			// start from the beginning
			$options['start'] = 0;
		}

		if (!array_key_exists('limit', $options))
		{
			// use default number of items
			$options['limit'] = 5;
		}

		$dbo = JFactory::getDbo();

		$orders = array();

		$q = $dbo->getQuery(true);

		$q->select('SQL_CALC_FOUND_ROWS ' . $dbo->qn('r.id'));
		$q->from($dbo->qn('#__vikappointments_reservation', 'r'));
		$q->leftjoin($dbo->qn('#__vikappointments_users', 'u') . ' ON ' . $dbo->qn('r.id_user') . ' = ' . $dbo->qn('u.id'));
		
		// filter reservations by user
		$q->where($dbo->qn('u.jid') . ' = ' . (int) $user->id);

		// get parent orders or single appointments
		$q->andWhere(array(
			$dbo->qn('r.id_parent') . ' <= 0',
			$dbo->qn('r.id_parent') . ' = ' . $dbo->qn('r.id'),
		), 'OR');

		$q->order($dbo->qn('r.id') . ' DESC');

		/**
		 * Trigger hook to manipulate the query at runtime. Third party plugins
		 * can extend the query by applying further conditions or selecting
		 * additional data.
		 *
		 * @param 	mixed  &$query    Either a query builder or a query string.
		 * @param 	array  &$options  An array of options.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBuildAllOrdersQuery', array(&$q, &$options));
		
		$dbo->setQuery($q, $options['start'], $options['limit']);

		if ($rows = $dbo->loadColumn())
		{
			// fetch pagination
			$this->getPagination($options);

			VAPLoader::import('libraries.order.factory');
			$tag = JFactory::getLanguage()->getTag();

			foreach ($rows as $id_order)
			{
				// get order details
				$orders[] = VAPOrderFactory::getAppointments($id_order, $tag);
			}
		}

		/**
		 * Trigger hook to manipulate the query response at runtime. Third party
		 * plugins can alter the resulting list of orders.
		 *
		 * @param 	array   &$orders  An array of fetched orders.
		 * @param 	JModel  $model    The current model.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBuildAllOrdersData', array(&$orders, $this));

		return $orders;
	}

	/**
	 * Returns the list pagination.
	 *
	 * @param 	array  $options  An array of options.
	 *
	 * @return  JPagination
	 */
	public function getPagination(array $options = array())
	{
		if (!$this->pagination)
		{
			jimport('joomla.html.pagination');
			$dbo = JFactory::getDbo();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			$this->total = (int) $dbo->loadResult();

			$this->pagination = new JPagination($this->total, $options['start'], $options['limit']);
		}

		return $this->pagination;
	}

	/**
	 * Returns the total number of employees matching the search query.
	 *
	 * @return 	integer
	 */
	public function getTotal()
	{
		return $this->total;
	}

	/**
	 * Checks whether the specified customer purchased
	 * at least a package order.
	 *
	 * @param 	integer  $id_user  The customer ID.  
	 *
	 * @return 	boolean  True in case of packages, false otherwise.
	 */
	public function hasPackages($id_user)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select(1)
			->from($dbo->qn('#__vikappointments_package_order'))
			->where($dbo->qn('id_user') . ' = ' . (int) $id_user);
		
		$dbo->setQuery($q, 0, 1);
		return $dbo->loadResult();
	}

	/**
	 * Checks whether the specified customer purchased
	 * at least a subscription order.
	 *
	 * @param 	integer  $id_user  The customer ID.  
	 *
	 * @return 	boolean  True in case of subscriptions, false otherwise.
	 */
	public function hasSubscriptions($id_user)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select(1)
			->from($dbo->qn('#__vikappointments_subscr_order'))
			->where($dbo->qn('id_user') . ' = ' . (int) $id_user);
		
		$dbo->setQuery($q, 0, 1);
		return $dbo->loadResult();
	}
}
