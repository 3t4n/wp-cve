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
 * VikAppointments employee area all subscriptions orders list view model.
 *
 * @since 1.7
 */
class VikAppointmentsModelEmpsubscrhistory extends JModelVAP
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
	 * Loads a list of orders to be displayed within the subscriptions
	 * history site view.
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

		// get currently logged in employee
		$auth = VAPEmployeeAuth::getInstance();

		if (!$auth->isEmployee())
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

		$q->select('SQL_CALC_FOUND_ROWS ' . $dbo->qn('o.id'));
		$q->from($dbo->qn('#__vikappointments_subscr_order', 'o'));
		// filter orders by employee
		$q->where($dbo->qn('o.id_employee') . ' = ' . $auth->id);

		$q->order($dbo->qn('o.id') . ' DESC');

		/**
		 * Trigger hook to manipulate the query at runtime. Third party plugins
		 * can extend the query by applying further conditions or selecting
		 * additional data.
		 *
		 * @param 	mixed   &$query    Either a query builder or a query string.
		 * @param 	array   &$options  An array of options.
		 * @param 	string  $group     The group alias (employee).
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onBuildSubscriptionOrdersQuery', array(&$q, &$options, 'employee'));
		
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
				$orders[] = VAPOrderFactory::getEmployeeSubscription($id_order, $tag);
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
		$dispatcher->trigger('onBuildSubscriptionOrdersData', array(&$orders, $this));

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
}
