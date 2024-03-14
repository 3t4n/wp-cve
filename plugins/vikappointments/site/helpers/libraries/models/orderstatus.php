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
 * VikAppointments order status class handler.
 *
 * @since  	1.6
 */
class VAPOrderStatus
{
	/**
	 * A list of instances.
	 *
	 * @var array
	 */
	protected static $instances = array();

	/**
	 * The database table.
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * The table primary key.
	 *
	 * @var string
	 */
	protected $pk;

	/**
	 * The table status column.
	 *
	 * @var string
	 */
	protected $statusColumn;

	/**
	 * Returns a new instance of this object, only creating it
	 * if it doesn't already exist.
	 *
	 * @param 	mixed 	$options 	The database table or an array of options.
	 *
	 * @return 	self 	A new instance of this object.
	 *
	 * @see 	__construct() 	for further details about the $options array.
	 */
	public static function getInstance($options = null)
	{
		$sign = serialize($options);

		if (!isset(static::$instances[$sign]))
		{
			static::$instances[$sign] = new static($options);
		}

		return static::$instances[$sign];
	}

	/**
	 * Class constructor.
	 *
	 * @param 	mixed 	$options 	The database table or an array of options.
	 *								The options array can contain the values below
	 * 								- table 	 the database table name ("reservations" by default).
	 * 											 Since we are using a class of VikAppointments,
	 * 											 the prefix "#__vikappointments_" must be omitted;
	 *								- pk 		 the primary key of the table ("id" by default);
	 * 								- statuscol  the status column name ("status" by default).
	 */
	public function __construct($options = null)
	{
		if (!is_array($options))
		{
			// string given, create an array of options
			$options = array('table' => $options);
		}

		if (empty($options['table']))
		{
			// the table attribute is empty, use the default table
			$options['table'] = '#__vikappointments_reservation';
		}
		else
		{
			// prepend the table prefix to the existing value
			$options['table'] = '#__vikappointments_' . $options['table'];
		}

		if (empty($options['pk']))
		{
			// the primary key is empty, use the default one (id)
			$options['pk'] = 'id';
		}

		if (empty($options['statuscol']))
		{
			// the status column is empty, use the default one (status)
			$options['statuscol'] = 'status';
		}

		$this->table 		= $options['table'];
		$this->pk 			= $options['pk'];
		$this->statusColumn = $options['statuscol'];
	}

	/**
	 * Method used to change the status of an order.
	 *
	 * @param 	string 	 $status  	The new status of the order.
	 * @param 	integer  $id 		The order ID.
	 * @param 	mixed 	 $track 	Used to track the status change or not.
	 * 								- false  	the order status won't be tracked;
	 * 								- true 		the order status will be tracked;
	 * 								- string 	the order status will be tracked by
	 * 											registering the given string as comment.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 *
	 * @uses 	keepTrack()
	 */
	public function change($status, $id, $track = false)
	{
		// fetch status group from internal type
		switch ($this->getType())
		{
			case 'reservation':
				$group = 'appointments';
				break;

			case 'package_order':
				$group = 'packages';
				break;

			case 'subscr_order':
				$group = 'subscriptions';
				break;
		}

		// load matching status code
		$status = JHtml::fetch('vaphtml.status.' . strtolower($status), $group, 'code');

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->update($dbo->qn($this->table))
			->set($dbo->qn($this->statusColumn) . ' = ' . $dbo->q(strtoupper($status)))
			->where($dbo->qn($this->pk) . ' = ' . $dbo->q($id));

		$dbo->setQuery($q);
		$dbo->execute();

		$res = (bool) $dbo->getAffectedRows();

		// If there is no affected rows, we don't need to track anything
		// because the query hasn't altered any record.
		if ($track && $res)
		{
			// get the comment if specified
			$comment = is_string($track) ? $track : '';

			// track the status change
			$res = $this->keepTrack($status, $id, $comment);
		}

		return $res;
	}

	/**
	 * Method used to change the status of an order to CONFIRMED.
	 *
	 * @param 	integer  $id 		The order ID.
	 * @param 	mixed 	 $track 	Used to track the status change or not.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 *
	 * @uses 	change()
	 */
	public function confirm($id, $track = false)
	{
		return $this->change('CONFIRMED', $id, $track);
	}

	/**
	 * Method used to change the status of an order to PENDING.
	 *
	 * @param 	integer  $id 		The order ID.
	 * @param 	mixed 	 $track 	Used to track the status change or not.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 *
	 * @uses 	change()
	 */
	public function pendent($id, $track = false)
	{
		return $this->change('PENDING', $id, $track);
	}

	/**
	 * Method used to change the status of an order to REMOVED.
	 *
	 * @param 	integer  $id 		The order ID.
	 * @param 	mixed 	 $track 	Used to track the status change or not.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 *
	 * @uses 	change()
	 */
	public function remove($id, $track = false)
	{
		return $this->change('REMOVED', $id, $track);
	}

	/**
	 * Method used to change the status of an order to CANCELED.
	 *
	 * @param 	integer  $id 		The order ID.
	 * @param 	mixed 	 $track 	Used to track the status change or not.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 *
	 * @uses 	change()
	 */
	public function cancel($id, $track = false)
	{
		return $this->change('CANCELLED', $id, $track);
	}

	/**
	 * Method used to change the status of an order to PAID.
	 *
	 * @param 	integer  $id 		The order ID.
	 * @param 	mixed 	 $track 	Used to track the status change or not.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 *
	 * @since 	1.7
	 *
	 * @uses 	change()
	 */
	public function paid($id, $track = false)
	{
		return $this->change('PAID', $id, $track);
	}

	/**
	 * Method used to change the status of an order to REFUNDED.
	 *
	 * @param 	integer  $id 		The order ID.
	 * @param 	mixed 	 $track 	Used to track the status change or not.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 *
	 * @since 	1.7
	 *
	 * @uses 	change()
	 */
	public function refunded($id, $track = false)
	{
		return $this->change('REFUNDED', $id, $track);
	}

	/**
	 * Method used to keep track of the status changes.
	 * Every time the status of an order changes, this method
	 * should be invoked to keep an history log of the order.
	 *
	 * @param 	string 	 $status   The new status of the order.	
	 * @param 	mixed  	 $id       The order ID or an array of IDs.
	 * @param 	string 	 $comment  An optional comment to detect the event
	 * 							   the triggered the status change.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 *
	 * @uses 	getType()
	 */
	public function keepTrack($status, $id, $comment = '')
	{
		$ids = (array) $id;

		// prepare save data
		$data = array(	
			'status'  => strtoupper($status),
			'comment' => (string) $comment,
			'type'    => $this->getType(),
		);

		$saved = false;

		// get order status model
		$model = JModelVAP::getInstance('orderstatus');

		// iterate all specified IDs
		foreach ($ids as $id)
		{
			// save status for current order
			$data['id_order'] = (int) $id;

			// save order status
			$saved = $model->save($data) || $saved;
		}

		return $saved;
	}

	/**
	 * Returns the list of all the status changes that 
	 * have been tracked for the specified order ID.
	 *
	 * @param 	mixed  	 $id 	  The order ID or an array of IDs.
	 * @param 	boolean  $locale  True to translate the records, false otherwise.
	 *
	 * @return 	array 	 The track list.
	 *
	 * @uses 	getType()
	 */
	public function getOrderTrack($id, $locale = false)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('`o`.*')
			->select($dbo->qn(array('u.name', 'u.username')))
			->from($dbo->qn('#__vikappointments_order_status', 'o'))
			->leftjoin($dbo->qn('#__users', 'u') . ' ON ' . $dbo->qn('u.id') . ' = ' . $dbo->qn('o.createdby'))
			->where($dbo->qn('o.type') . ' = ' . $dbo->q($this->getType()))
			->order($dbo->qn('o.id') . ' ASC');

		if (is_scalar($id) || !$id)
		{
			$q->where($dbo->qn('o.id_order') . ' = ' . (int) $id);
		}
		else
		{
			// mainly used for reservation with multi-order (see 'id_parent' column)
			$id = array_map('intval', $id);
			$q->where($dbo->qn('o.id_order') . ' IN (' . implode(', ', $id) . ')');
		}

		$dbo->setQuery($q);
		$track = $dbo->loadObjectList();

		if (!$track)
		{
			return array();
		}

		if ($locale)
		{
			// translate the records
			foreach ($track as $i => $change)
			{
				// keep also the default status code
				$track[$i]->statusCode = $change->status;

				// get status code name
				$statusName = JHtml::fetch('vaphtml.status.find', 'name', array('code' => $change->status), $limit = true);
				
				// register status name
				$track[$i]->status = $statusName ? $statusName : $change->status;

				// A comment can be translated only if it doesn't contain any spaces,
				// as the language keys don't support them.
				if (!preg_match("/\s/", $change->comment))
				{
					// try to translate the comment
					$track[$i]->comment = JText::translate($change->comment);
				}
			}
		}

		return $track;
	}

	/**
	 * Returns the current status of the specified order.
	 *
	 * @param 	integer  $id  The order ID.
	 *
	 * @return 	mixed 	 The order status if exists, false otherwise.
	 *
	 * @since 	1.6.3
	 */
	public function getStatus($id)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn($this->statusColumn))
			->from($dbo->qn($this->table))
			->where($dbo->qn($this->pk) . ' = ' . $dbo->q($id));

		$dbo->setQuery($q, 0, 1);

		if ($status = $dbo->loadResult())
		{
			return strtoupper($status);
		}

		return false;
	}

	/**
	 * Returns the type for which a status is changed.
	 * It depends on the database table assigned to this object
	 * and it is taken by excluding the extension DB prefix.
	 *
	 * @return 	string 	The type.
	 */
	protected function getType()
	{
		if (preg_match("/#__vikappointments_(.+)/", $this->table, $matches))
		{
			return $matches[1];
		}

		return false;
	}
}
