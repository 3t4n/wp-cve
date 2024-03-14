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
 * VikAppointments customer model.
 *
 * @since 1.7
 */
class VikAppointmentsModelCustomer extends JModelVAP
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
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$data = (array) $data;

		if (empty($data['billing_name']) && isset($data['purchaser_nominative']))
		{
			// used the reservation notation, normalize it
			$data['billing_name'] = $data['purchaser_nominative'];
			unset($data['purchaser_nominative']);
		}

		if (empty($data['billing_mail']) && isset($data['purchaser_mail']))
		{
			// used the reservation notation, normalize it
			$data['billing_mail'] = $data['purchaser_mail'];
			unset($data['purchaser_mail']);
		}

		if (empty($data['billing_phone']) && isset($data['purchaser_phone']))
		{
			// used the reservation notation, normalize it
			$data['billing_phone'] = $data['purchaser_phone'];
			unset($data['purchaser_phone']);
		}

		if (empty($data['country_code']) && isset($data['purchaser_country']))
		{
			// used the reservation notation, normalize it
			$data['country_code'] = $data['purchaser_country'];
			unset($data['purchaser_country']);
		}

		$item = null;

		if (empty($data['id']) && !empty($data['jid']))
		{
			// user logged-in, check whether we already have an existing customer
			$item = $this->getItem(array('jid' => $data['jid']));

			if ($item)
			{
				// customer found, do update
				$data['id'] = $item->id;
			}
		}

		if (empty($data['id']) && !empty($data['billing_mail']))
		{
			// search also by e-mail (and the user ID is null)
			$item = $this->getItem(array('jid' => 0, 'billing_mail' => $data['billing_mail']));

			if ($item)
			{
				// customer found by mail, do update
				$data['id'] = $item->id;
			}
		}

		if (!empty($data['used_credit']) && $item)
		{
			// subtract the used credit to the existing one
			$data['credit'] = max(array(0, $item->credit - $data['used_credit']));
		}

		if (isset($data['active_to_date']) && !VAPDateHelper::isNull($data['active_to_date']))
		{
			// an expiration date was specified, unset lifetime flag
			$data['lifetime'] = 0;

			// register first active date
			$data['active_since'] = JFactory::getDate()->toSql();

			if (!empty($data['id']))
			{
				if (!$item)
				{
					// load current item
					$item = $this->getItem($data['id']);
				}

				// make sure the item exists and the activation date was already set
				if ($item && !VAPDateHelper::isNull($item->active_since))
				{
					// activation date already registered, avoid updating it
					unset($data['active_since']);
				}
			}
		}

		// attempt to save the customer
		return parent::save($data);
	}

	/**
	 * Increases (or decreases) the user credit by the given amount.
	 *
	 * @param 	mixed    $pk      The user primary key(s).
	 * @param 	float    $credit  A positive float to increase the credit,
	 *                            anegative amount to decrease it.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function addCredit($id, $credit)
	{
		if ($credit == 0)
		{
			// no credit to update
			return false;
		}

		// load current user credit
		$item = $this->getItem($id);

		if (!$item)
		{
			// user not found
			return false;
		}

		$data = array(
			'id'     => $item->id,
			'credit' => $item->credit + (float) $credit,
		);

		// make sure the credit is not lower than 0
		$data['credit'] = max(array(0, $data['credit']));

		// update used credit
		return (bool) $this->save($data);
	}

	/**
	 * Extend delete implementation to delete any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		$dbo = JFactory::getDbo();

		// load any assigned notes
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_user_notes'))
			->where($dbo->qn('id_user') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($note_ids = $dbo->loadColumn())
		{
			// get user notes model
			$model = JModelVAP::getInstance('usernote');
			// delete records
			$model->delete($note_ids);
		}

		// load any assigned reservations
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_reservation'))
			->where($dbo->qn('id_user') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($app_ids = $dbo->loadColumn())
		{
			// get appointment model
			$model = JModelVAP::getInstance('reservation');

			// detach customers from appointments
			foreach ($app_ids as $app_id)
			{
				$data = array(
					'id'      => (int) $app_id,
					'id_user' => 0,
				);

				$model->save($data);
			}
		}

		// load any assigned packages orders
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_package_order'))
			->where($dbo->qn('id_user') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($ord_ids = $dbo->loadColumn())
		{
			// get package order model
			$model = JModelVAP::getInstance('packorder');

			// detach customers from orders
			foreach ($ord_ids as $ord_id)
			{
				$data = array(
					'id'      => (int) $ord_id,
					'id_user' => 0,
				);

				$model->save($data);
			}
		}

		// load any assigned subscriptions orders
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_subscr_order'))
			->where($dbo->qn('id_user') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($ord_ids = $dbo->loadColumn())
		{
			// get subscription order model
			$model = JModelVAP::getInstance('subscrorder');

			// detach customers from orders
			foreach ($ord_ids as $ord_id)
			{
				$data = array(
					'id'      => (int) $ord_id,
					'id_user' => 0,
				);

				$model->save($data);
			}
		}

		return true;
	}

	/**
	 * Searches for the CMS users that match the specified query.
	 * It is possible to search the users by name, username and
	 * e-mail address.
	 *
	 * @param 	string  $search   The search string.
	 * @param 	mixed   $id       The user ID. When specified, the system will
	 *                            fetch also the user status, to check if the
	 *                            user has been assigned to another user.
	 * @param 	array 	$options  An array of options:
	 *                            - start  int|null  the query offset;
	 *                            - limit  int|null  the query limit;
	 *
	 * @return 	array   A list of matching users.
	 */
	public function searchUsers($search = '', $id = null, array $options = array())
	{
		// always reset pagination and total count
		$this->pagination = null;
		$this->total      = 0;

		$options = new JRegistry($options);

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select($dbo->qn('u.id'));
		$q->select($dbo->qn('u.name'));
		$q->select($dbo->qn('u.email'));
		$q->select($dbo->qn('u.username'));

		if (!is_null($id))
		{
			// create inner query to fetch enabled/disabled status
			$inner = $dbo->getQuery(true)
				->select(1)
				->from($dbo->qn('#__vikappointments_users', 'a'))
				->where($dbo->qn('a.jid') . ' = ' . $dbo->qn('u.id'));

			$q->select('(' . $dbo->qn('id') . ' <> ' . (int) $id . ' AND EXISTS (' . $inner . ')) AS ' . $dbo->qn('disabled'));
		}

		$q->from($dbo->qn('#__users', 'u'));
			
		if ($search)
		{
			/**
			 * Reverse the search key in order to try finding
			 * users by name even if it was wrote in the opposite way.
			 * If we search by "John Smith", the system will search
			 * for "Smith John" too.
			 *
			 * @since 1.7
			 */
			$reverse = preg_split("/\s+/", $search);
			$reverse = array_reverse($reverse);
			$reverse = implode(' ', $reverse);

			$q->where(array(
				$dbo->qn('u.name') . ' LIKE ' . $dbo->q("%$search%"),
				$dbo->qn('u.name') . ' LIKE ' . $dbo->q("%$reverse%"),
				$dbo->qn('u.username') . ' LIKE ' . $dbo->q("%$search%"),
				$dbo->qn('u.email') . ' LIKE ' . $dbo->q("%$search%"),
			), 'OR');
		}

		$q->order($dbo->qn('u.name') . ' ASC');
		$q->order($dbo->qn('u.username') . ' ASC');

		/**
		 * Fetch list limit.
		 *
		 * @since 1.7
		 */
		$start = $options->get('start', 0);
		$limit = $options->get('limit', null);

		$dbo->setQuery($q, $start, $limit);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// no users found
			return [];
		}

		$users = array();

		/**
		 * Reverse lookup used to check whether there is already
		 * a user with the same name.
		 * 
		 * @since 1.7
		 */
		$namesakesLookup = array();

		foreach ($rows as $u)
		{
			$u->text = $u->name;

			$users[$u->id] = $u;

			// insert name-id relation within the lookup
			if (!isset($namesakesLookup[$u->name]))
			{
				$namesakesLookup[$u->name] = array();
			}

			$namesakesLookup[$u->name][] = $u->id;
		}

		// iterate names lookup
		foreach ($namesakesLookup as $name => $ids)
		{
			// in case a name owns more than 1 ID, we have a homonym
			if (count($ids) > 1)
			{
				// iterate the list of IDS and append the e-mail to the name
				foreach ($ids as $id)
				{
					$users[$id]->text .= ' : ' . $users[$id]->username;
				}
			}
		}

		return $users;
	}

	/**
	 * Searches for the customers that match the specified query.
	 * It is possible to search the users by name, e-mail and
	 * phone number.
	 *
	 * @param 	string  $search   The search string.
	 * @param 	array 	$options  An array of options:
	 *                            - start        int|null  the query offset;
	 *                            - limit        int|null  the query limit;
	 *                            - id_employee  int|null  the employee filter.
	 *
	 * @return 	array   A list of matching customers.
	 */
	public function search($search = '', array $options = array())
	{
		// always reset pagination and total count
		$this->pagination = null;
		$this->total      = 0;

		$options = new JRegistry($options);

		$dbo = JFactory::getDbo();

		// fetch list based on search key
		$q = $dbo->getQuery(true);

		$q->select('SQL_CALC_FOUND_ROWS c.id');
		$q->select($dbo->qn('c.billing_name'));
		$q->select($dbo->qn('c.billing_mail'));
		$q->select($dbo->qn('c.billing_phone'));
		$q->select($dbo->qn('c.country_code'));
		$q->select($dbo->qn('c.billing_state'));
		$q->select($dbo->qn('c.billing_city'));
		$q->select($dbo->qn('c.billing_address'));
		$q->select($dbo->qn('c.billing_zip'));
		$q->select($dbo->qn('c.company'));
		$q->select($dbo->qn('c.vatnum'));
		$q->select($dbo->qn('c.jid'));
		$q->select($dbo->qn('c.image'));
		$q->select($dbo->qn('c.fields'));

		$q->from($dbo->qn('#__vikappointments_users', 'c'));

		$q->where(1);

		if ($search)
		{
			/**
			 * Reverse the search key in order to try finding
			 * users by name even if it was wrote in the opposite way.
			 * If we searched by "John Smith", the system will search
			 * for "Smith John" too.
			 *
			 * @since 1.7
			 */
			$reverse = preg_split("/\s+/", $search);
			$reverse = array_reverse($reverse);
			$reverse = implode(' ', $reverse);

			$q->andWhere(array(
				$dbo->qn('c.billing_name') . ' LIKE ' . $dbo->q("%$search%"),
				$dbo->qn('c.billing_name') . ' LIKE ' . $dbo->q("%$reverse%"),
				$dbo->qn('c.billing_mail') . ' LIKE ' . $dbo->q("%$search%"),
				$dbo->qn('c.billing_phone') . ' LIKE ' . $dbo->q("%$search%"),
			), 'OR');
		}

		/**
		 * When specified, take only the customers that booked at least an appointment 
		 * with the given employee.
		 *
		 * @since 1.7
		 */
		if (isset($options['id_employee']))
		{
			$q->leftjoin($dbo->qn('#__vikappointments_reservation', 'r') . ' ON ' . $dbo->qn('c.id') . ' = ' . $dbo->qn('r.id_user'));
			$q->andWhere($dbo->qn('r.id_employee') . ' = ' . (int) $options['id_employee']);
			$q->group($dbo->qn('c.id'));
		}

		$q->order($dbo->qn('c.billing_name') . ' ASC');
		$q->order($dbo->qn('c.billing_mail') . ' ASC');

		/**
		 * Fetch list limit.
		 *
		 * @since 1.7
		 */
		$start = $options->get('start', 0);
		$limit = $options->get('limit', null);

		$dbo->setQuery($q, $start, $limit);
		$rows = $dbo->loadObjectList();

		if (!$rows)
		{
			// no customers found
			return [];
		}

		$users = array();

		/**
		 * Reverse lookup used to check whether there is already
		 * a user with the same name.
		 * 
		 * @since 1.7
		 */
		$namesakesLookup = array();

		foreach ($rows as $u)
		{
			// decode JSON fields
			$u->fields = $u->fields ? json_decode($u->fields) : array();

			$u->text = $u->billing_name;

			// register user by ID
			$users[$u->id] = $u;

			// insert name-id relation within the lookup
			if (!isset($namesakesLookup[$u->billing_name]))
			{
				$namesakesLookup[$u->billing_name] = array();
			}

			$namesakesLookup[$u->billing_name][] = $u->id;
		}

		// iterate names lookup
		foreach ($namesakesLookup as $name => $ids)
		{
			// in case a name owns more than 1 ID, we have a homonym
			if (count($ids) > 1)
			{
				// iterate the list of IDS and append the e-mail to the name
				foreach ($ids as $id)
				{
					$users[$id]->text .= ' : ' . $users[$id]->billing_mail;
				}
			}
		}

		return $users;
	}

	/**
	 * Returns the list pagination.
	 *
	 * @param 	array  $filters  An array of filters.
	 * @param 	array  $options  An array of options.
	 *
	 * @return  JPagination
	 */
	public function getPagination(array $filters = array(), array $options = array())
	{
		if (!$this->pagination)
		{
			jimport('joomla.html.pagination');
			$dbo = JFactory::getDbo();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			$this->total = (int) $dbo->loadResult();

			$this->pagination = new JPagination($this->total, $options['start'], $options['limit']);

			foreach ($filters as $k => $v)
			{
				// append only filters that own a value as it doesn't
				// make sense to populate the URL using empty variables
				if ($v)
				{
					$this->pagination->setAdditionalUrlParam($k, $v);
				}
			}
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
	 * Counts the total number of purchased packages and the total number of redeemed ones.
	 * 
	 * @param   int|null  $userId  The ID of the user that owns the packages. If not specified, 
	 *                             the current used will be taken.
	 * 
	 * @return  object    The count information (`num_app` and `used_app`).
	 * 
	 * @since   1.7.4
	 */
	public function countPackages(int $userId = null)
	{
		if (!$userId)
		{
			// get customer ID assigned to the current user
			$userId = $this->getItem(['jid' => JFactory::getUser()->id], $blank = true)->id;
		}

		$db = JFactory::getDbo();

		// get any approved codes
		$approved = JHtml::fetch('vaphtml.status.find', 'code', ['packages' => 1, 'approved' => 1]);

		$query = $db->getQuery(true)
			->select(sprintf('SUM(%s) AS %s', $db->qn('i.used_app'), $db->qn('used_app')))
			->select(sprintf('SUM(%s) AS %s', $db->qn('i.num_app'), $db->qn('num_app')))
			->from($db->qn('#__vikappointments_package_order', 'o'))
			->leftjoin($db->qn('#__vikappointments_package_order_item', 'i') . ' ON ' . $db->qn('i.id_order') . ' = ' . $db->qn('o.id'))
			->where($db->qn('o.id_user') . ' = ' . $userId);

		if ($approved)
		{
			// filter by approved status
			$query->where($db->qn('o.status') . ' IN (' . implode(',', array_map(array($db, 'q'), $approved)) . ')');
		}
		
		$db->setQuery($query);
		$count = $db->loadObject();

		if (!$count)
		{
			$count = new stdClass;
		}

		$count->used_app = (int) ($count->used_app ?? 0);
		$count->num_app  = (int) ($count->num_app ?? 0);

		return $count;
	}
}
