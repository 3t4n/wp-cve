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
 * This class implements helpful methods for abstract tables.
 *
 * @since 1.7
 */
class JTableVAP extends JTable
{
	/**
	 * A list of fields that requires a validation.
	 * Children classes must inherit this property in
	 * order to include additional fields within the
	 * validation process.
	 *
	 * @var array
	 */
	protected $_requiredFields = array();

	/**
	 * Flag used to overwrite the "update nulls" argument
	 * received by the store method without having to
	 * override it.
	 *
	 * @var boolean
	 */
	protected $_updateNulls = false;

	/**
	 * Flag used to temporarily prevent the storage of the
	 * user state while binding the table.
	 *
	 * @var boolean
	 */
	public $_setUserState = true;

	/**
	 * Method to provide a shortcut to binding, checking and storing a Table instance to the database table.
	 *
	 * The method will check a row in once the data has been stored and if an ordering filter is present will attempt to reorder
	 * the table rows based on the filter.  The ordering filter is an instance property name.  The rows that will be reordered
	 * are those whose value matches the Table instance for the property specified.
	 *
	 * @param   array|object  $src             An associative array or object to bind to the Table instance.
	 * @param   string        $orderingFilter  Filter for the order updating.
	 * @param   array|string  $ignore          An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 */
	public function save($src, $orderingFilter = '', $ignore = '')
	{
		$result = true;

		// create "before save" event
		$event = 'onBeforeSave' . ucfirst($this->getName());

		try
		{
			/**
			 * Trigger event to allow the plugins to bind the object that
			 * is going to be saved. The event to use is built as:
			 * onBeforeSave[TABLE_NAME], where [TABLE_NAME] is the name of
			 * file in which the table child is written.
			 *
			 * @param 	mixed 	 &$src 	 The array/object to bind.
			 * @param 	JTable   $table  The table instance.
			 *
			 * @return 	boolean  False to abort saving.
			 *
			 * @throws 	Exception  It is possible to throw an exception to abort
			 *                     the saving process and return a readable message.
			 *
			 * @since 	1.7
			 */
			if (VAPFactory::getEventDispatcher()->false($event, array(&$src, $this)))
			{
				// abort in case a plugin returned false
				$result = false;
			}
		}
		catch (Exception $e)
		{
			// register the error thrown by the plugin and abort 
			$this->setError($e);

			$result = false;
		}

		// always store the specified data before binding
		if ($src)
		{
			$this->setUserStateData($src);
		}

		// dispatch parent to complete saving
		return $result && parent::save($src, $orderingFilter, $ignore);
	}

	/**
	 * Method to perform sanity checks on the Table instance properties to
	 * ensure they are safe to store in the database.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 */
	public function check()
	{
		$pk = $this->getKeyName();

		// iterate required fields
		foreach ($this->_requiredFields as $col)
		{
			if (isset($this->{$col}))
			{
				// parameter given, make sure it is not an empty string
				$blank = is_scalar($this->{$col}) && !strlen(trim($this->{$col}));
			}
			else
			{
				// parameter missing, mark as mandatory only in case of insert
				$blank = !$this->{$pk};
			}

			// in case the property was specified, make sure it is not an empty string
			if ($blank)
			{
				// register error message
				$this->setError(JText::sprintf('VAP_MISSING_REQ_FIELD', $col));

				// unsafe record
				return false;
			}
		}

		// safe record
		return true;
	}

	/**
	 * Method to store a row in the database from the Table instance properties.
	 *
	 * If a primary key value is set the row with that primary key value will be updated with the instance property values.
	 * If no primary key value is set a new row will be inserted into the database with the properties from the Table instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 */
	public function store($updateNulls = false)
	{
		$pk = $this->getKeyName();

		$is_new = empty($this->{$pk});

		if (!empty($this->_updateNulls))
		{
			// force update of NULL columns
			$updateNulls = true;

			// reset internal property
			$this->_updateNulls = false;
		}

		// invoke parent to store the record
		if (!parent::store($updateNulls))
		{
			// do not proceed in case of error
			return false;
		}

		// always clean previous data after a successful saving
		$this->setUserStateData(null);

		// get customer data
		$args = $this->getProperties();

		// create "after save" event
		$event = 'onAfterSave' . ucfirst($this->getName());

		/**
		 * Trigger event to allow the plugins to make something after saving
		 * a record in the database. The event to use is built as:
		 * onAfterSave[TABLE_NAME], where [TABLE_NAME] is the name of
		 * file in which the table child is written.
		 *
		 * @param 	array 	 $args    The saved record.
		 * @param 	boolean  $is_new  True if the record was inserted.
		 * @param 	JTable   $table   The table instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger($event, array($args, $is_new, $this));

		return true;
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return  boolean  True on success.
	 */
	public function delete($ids = null)
	{
		if (!$ids)
		{
			return false;
		}

		// always treat the IDs as an array
		$ids = (array) $ids;

		$dispatcher = VAPFactory::getEventDispatcher();

		// create "before delete" event
		$event = 'onBeforeDelete' . ucfirst($this->getName());

		try
		{
			/**
			 * Trigger event to allow the plugins to make something before deleting
			 * one or more records from the database. The event to use is built as:
			 * onBeforeDelete[TABLE_NAME], where [TABLE_NAME] is the name of the
			 * file in which the table child is written.
			 *
			 * @param 	array 	 $ids    An array of IDs to delete.
			 * @param 	JTable   $table  The table instance.
			 *
			 * @return 	boolean  False to abort delete.
			 *
			 * @throws 	Exception  It is possible to throw an exception to abort
			 *                     the delete process and return a readable message.
			 *
			 * @since 	1.7
			 */
			if ($dispatcher->false($event, array($ids, $this)))
			{
				// a plugin aborted this action
				return false;
			}
		}
		catch (Exception $e)
		{
			// register the error thrown by the plugin and abort 
			$this->setError($e);

			return false;
		}

		$dbo = JFactory::getDbo();

		// delete records
		$q = $dbo->getQuery(true)
			->delete($dbo->qn($this->getTableName()))
			->where($dbo->qn($this->getKeyName()) . ' IN (' . implode(',', $ids) . ')');

		$dbo->setQuery($q);
		$dbo->execute();

		if (!$dbo->getAffectedRows())
		{
			// nothing to delete
			return false;
		}

		// create "after delete" event
		$event = 'onAfterDelete' . ucfirst($this->getName());

		// trigger a separated event for each ID in the list
		foreach ($ids as $id)
		{
			/**
			 * Trigger event to allow the plugins to make something after deleting
			 * one or more records from the database. The event to use is built as:
			 * onAfterDelete[TABLE_NAME], where [TABLE_NAME] is the name of the
			 * file in which the table child is written.
			 *
			 * @param 	integer  $id     The deleted ID.
			 * @param 	JTable   $table  The table instance.
			 *
			 * @return 	void
			 *
			 * @since 	1.7
			 */
			$dispatcher->trigger($event, array($id, $this));
		}

		return true;
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database table.
	 *
	 * The method respects checked out rows by other users and will attempt to checkin rows that it can after adjustments are made.
	 *
	 * @param   mixed    $pks     An optional array of primary key values to update. If not set the instance property value is used.
	 * @param   integer  $state   The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param   integer  $userId  The user ID of the user performing the operation.
	 *
	 * @return  boolean  True on success; false if $pks is empty.
	 *
	 * @since   1.7.1
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		if (!$pks)
		{
			return false;
		}

		$pks = (array) $pks;

		$dispatcher = VAPFactory::getEventDispatcher();

		// create "before publish" event
		$event = 'onBeforePublish' . ucfirst($this->getName());

		try
		{
			/**
			 * Trigger event to allow the plugins to make something before publishing
			 * or unpublishing one or more records. The event to use is built as:
			 * onBeforePublish[TABLE_NAME], where [TABLE_NAME] is the name of the
			 * file in which the table child is written.
			 *
			 * @param   mixed    $pks    An optional array of primary key values to update.
			 * @param 	integer  $state  The publishing state. 
			 * @param 	JTable   $table  The table instance.
			 *
			 * @return 	boolean  False to abort publishing.
			 *
			 * @throws 	Exception  It is possible to throw an exception to abort the
			 *                     publishing process and return a readable message.
			 *
			 * @since 	1.7.1
			 */
			if ($dispatcher->false($event, array($pks, $state, $this)))
			{
				// a plugin aborted this action
				return false;
			}
		}
		catch (Exception $e)
		{
			// register the error thrown by the plugin and abort 
			$this->setError($e);

			return false;
		}

		// publish through parent
		if (!parent::publish($pks, $state, $userId))
		{
			// something went wrong...
			return false;
		}

		// create "after publish" event
		$event = 'onAfterPublish' . ucfirst($this->getName());

		// trigger a separated event for each ID in the list
		foreach ($pks as $id)
		{
			/**
			 * Trigger event to allow the plugins to make something after publishing
			 * or unpublishing one or more records. The event to use is built as:
			 * onAfterPublish[TABLE_NAME], where [TABLE_NAME] is the name of the
			 * file in which the table child is written.
			 *
			 * @param 	integer  $id     The ID of the updated record.
			 * @param 	integer  $state  The publishing state.
			 * @param 	JTable   $table  The table instance.
			 *
			 * @return 	void
			 *
			 * @since 	1.7.1
			 */
			$dispatcher->trigger($event, array($id, $state, $this));
		}

		return true;
	}

	/**
	 * Sets the relations between the given entry and the specified records list.
	 * In order to be used, the children class must declare the following properties:
	 * - _tbl_assoc_pk  the assoc column of the primary table;
	 * - _tbl_assoc_fk  the assoc column of the foreign table.
	 *
	 * @param 	mixed  $id       The assoc column of the primary table.
	 * @param 	array  $records  The assoc column of the foreign table.
	 *
	 * @return 	void
	 */
	public function setRelation($id, array $records)
	{
		if (empty($id) || !isset($this->_tbl_assoc_pk) || !isset($this->_tbl_assoc_fk))
		{
			return;
		}

		$dbo = JFactory::getDbo();

		// get existing records
		$q = $dbo->getQuery(true)
			->select($dbo->qn($this->_tbl_assoc_fk))
			->from($dbo->qn($this->getTableName()))
			->where($dbo->qn($this->_tbl_assoc_pk) . ' = ' . (int) $id);

		$dbo->setQuery($q);
		$existing = $dbo->loadColumn();

		// insert new records

		$has = false;

		$q = $dbo->getQuery(true)
			->insert($dbo->qn($this->getTableName()))
			->columns($dbo->qn(array($this->_tbl_assoc_pk, $this->_tbl_assoc_fk)));

		foreach ($records as $s)
		{
			// make sure the record to push doesn't exist yet
			if (!in_array($s, $existing))
			{
				$q->values($id . ', ' . $s);
				$has = true;
			}
		}

		if ($has)
		{
			$dbo->setQuery($q);
			$dbo->execute();
		}

		// delete records

		$delete = array();

		foreach ($existing as $s)
		{
			// make sure the records to delete is not contained in the selected records
			if (!in_array($s, $records))
			{
				$delete[] = $s;
			}
		}

		if (count($delete))
		{
			$q = $dbo->getQuery(true)
				->delete($dbo->qn($this->getTableName()))
				->where(array(
					$dbo->qn($this->_tbl_assoc_pk) . ' = ' . $id,
					$dbo->qn($this->_tbl_assoc_fk) . ' IN (' . implode(',', $delete) . ')',
				));

			$dbo->setQuery($q);
			$dbo->execute();
		}
	}

	/**
	 * Helper method used to store the user data within the session.
	 *
	 * @param 	mixed 	$data  The array data to store.
	 *
	 * @return 	self    This object to support chaining.
	 */
	public function setUserStateData($data = null)
	{
		// extract table name from class
		if (preg_match("/^([a-z]+)Table(.+?)$/i", get_class($this), $match) && $this->_setUserState)
		{
			$prefix = strtolower($match[1]);
			$name   = strtolower($match[2]);

			// before registering the user state, make sure that
			// the headers haven't been sent yet, in order to avoid
			// post fatal errors
			if (headers_sent() == false)
			{
				// set user state for later use
				JFactory::getApplication()->setUserState($prefix . '.' . $name . '.data', $data);
			}
		}

		return $this;
	}

	/**
	 * Helper method used to retrieve the user data saved in the session.
	 *
	 * @return 	array
	 */
	public function getUserStateData()
	{
		// extract table name from class
		if (preg_match("/^([a-z]+)Table(.+?)$/i", get_class($this), $match))
		{
			$prefix = strtolower($match[1]);
			$name   = strtolower($match[2]);

			// before registering the user state, make sure that
			// the headers haven't been sent yet, in order to avoid
			// post fatal errors
			if (headers_sent() == false)
			{
				// set user state for later use
				return JFactory::getApplication()->getUserState($prefix . '.' . $name . '.data', array());
			}
		}

		return array();
	}

	/**
	 * Recovers the table class name.
	 *
	 * @return 	string  The class name.
	 */
	protected function getName()
	{
		// extract table name from object class
		if (preg_match("/Table([a-z0-9_]+)$/i", get_class($this), $match))
		{
			return strtolower(end($match));
		}

		return null;
	}

	/**
	 * Returns an associative array of object properties.
	 * Override parent method to excluded native vars.
	 *
	 * @param   boolean  $public  If true, returns only the public properties.
	 *
	 * @return  array
	 *
	 * @since   1.7.1
	 */
	public function getProperties($public = true)
	{
		// invoke parent
		$args = parent::getProperties($public);

		// introduced in J4
		unset($args['typeAlias']);

		return $args;
	}
}
