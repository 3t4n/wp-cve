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
 * This class implements helpful methods for models instances.
 * JModelBaseUI is a placeholder used to support both JModel and JModelLegacy.
 *
 * @since 1.7
 */
class JModelVAP extends JModelBaseUI
{
	/**
	 * Returns a Model object, always creating it.
	 *
	 * @param   string  $type    The model type to instantiate.
	 * @param   string  $prefix  Prefix for the model class name.
	 * @param   array   $config  Configuration array for model.
	 *
	 * @return  mixed   A model instance or false on failure.
	 */
	public static function getInstance($type, $prefix = '', $config = array())
	{
		if (!$prefix)
		{
			// use default system prefix
			$prefix = 'VikAppointmentsModel';
		}

		// invoke parent to complete instantiation
		return parent::getInstance($type, $prefix, $config);
	}

	/**
	 * Basic item loading implementation.
	 *
	 * @param   mixed    $pk   An optional primary key value to load the row by, or an array of fields to match.
	 *                         If not set the instance property value is used.
	 * @param   boolean  $new  True to return an empty object if missing.
	 *
	 * @return 	mixed    The record object on success, null otherwise.
	 */
	public function getItem($pk, $new = false)
	{
		$table = $this->getTable();

		// do not save in the user state the loaded columns
		$table->_setUserState = false;
		
		// reset table to make sure we obtain valid values
		$table->reset();

		/**
		 * We need to avoid using a default value equals to '' in case
		 * the default value is set to an empty string via SQL.
		 * 
		 * @since 1.7.1
		 */
		foreach ($table->getProperties() as $k => $v)
		{
			// check whether the value is equals to an empty string
			if (is_string($v) && preg_match("/^(\"\"|'')$/", $v))
			{
				// use an empty string
				$table->{$k} = '';
			}
		}

		// attempt to load record
		$loaded = ($pk && $table->load($pk));

		// enable user state again
		$table->_setUserState = true;
		
		if ($loaded || $new === true)
		{
			// loaded successfully or requested an empty object
			return (object) $table->getProperties();
		}

		// something went wrong, try to obtain an error
		$error = $table->getError($get_last = null, $string = true);

		if ($error)
		{
			// error found, register it within the model
			$this->setError($error);
		}

		return null;
	}

	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$table = $this->getTable();
		
		// attempt to save data
		if ($table->save($data))
		{
			// register save data within the internal state
			$this->set('data', $table->getProperties());

			// saved successfully, return PK
			return $table->{$table->getKeyName()};
		}

		// something went wrong, try to obtain an error
		$error = $table->getError($get_last = null, $string = true);

		if ($error)
		{
			// error found, register it within the model
			$this->setError($error);
		}

		return false;
	}

	/**
	 * Returns the table properties, useful to retrieve the information
	 * that have been registered while saving a record.
	 *
	 * @return 	array
	 */
	public function getData()
	{
		// try to get save data
		$data = $this->get('data', null);

		if (!$data)
		{
			// no saved data, return the table properties
			$data = $this->getTable()->getProperties();
		}

		return $data;
	}

	/**
	 * Basic delete implementation.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		$table = $this->getTable();
		
		// attempt to delete data
		if ($table->delete($ids))
		{
			// deleted successfully
			return true;
		}

		// something went wrong, try to obtain an error
		$error = $table->getError($get_last = null, $string = true);

		if ($error)
		{
			// error found, register it within the model
			$this->setError($error);
		}

		return false;
	}

	/**
	 * Basic publish/unpublish implementation.
	 *
	 * @param   mixed    $ids    Either the record ID or a list of records.
	 * @param 	integer  $state  The publishing status.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function publish($ids, $state = 1, $alias = null)
	{
		$table = $this->getTable();

		$defaultAlias = null;

		if ($alias)
		{
			// get default column alias
			$defaultAlias = $table->getColumnAlias('published');
			// then change it with the specified one
			$table->setColumnAlias('published', $alias);
		}

		$status = true;

		// attempt to delete data
		if (!$table->publish($ids, $state))
		{
			// Something went wrong or the status didn't change.
			// Try to obtain an error.
			$error = $table->getError($get_last = null, $string = true);

			if ($error)
			{
				// error found, register it within the model
				$this->setError($error);

				$status = false;
			}	
		}

		// finally restore the default alias
		$table->setColumnAlias('published', $defaultAlias);

		return $status;
	}

	/**
	 * Basic duplicate implementation.
	 *
	 * @param   mixed    $ids     Either the record ID or a list of records.
	 * @param 	mixed    $src     Specifies some values to be used while duplicating.
	 * @param 	array    $ignore  A list of columns to skip.
	 *
	 * @return 	mixed    The ID of the records on success, false otherwise.
	 */
	public function duplicate($ids, $src = array(), $ignore = array())
	{
		$table = $this->getTable();

		// always treat as an array
		$ids = (array) $ids;

		$result = array();

		// duplicate one by one
		foreach ($ids as $id)
		{
			// load existing record
			if ($table->load($id))
			{
				// get record columns
				$data = $table->getProperties();

				// unset primary key
				$data[$table->getKeyName()] = 0;

				// unset all the properties to skip
				foreach ($ignore as $col)
				{
					unset($data[$col]);
				}

				// iterate properties to use
				foreach ($src as $col => $val)
				{
					$data[$col] = $val;
				}

				// reset table before save
				$table->reset();

				// create new record by using the model
				$tmp = $this->save($data);

				if ($tmp)
				{
					// register saved ID
					$result[] = $tmp;
				}
			}
		}

		return $result;
	}

	/**
	 * Sets the relations between the given entry and the specified records list.
	 *
	 * @param 	mixed  $id       The assoc column of the primary table.
	 * @param 	array  $records  The assoc column of the foreign table.
	 *
	 * @return 	void
	 */
	public function setRelation($id, array $records)
	{
		// make relation
		$this->getTable()->setRelation($id, $records);
	}

	/**
	 * Method to get a table object.
	 *
	 * @param   string  $name     The table name.
	 * @param   string  $prefix   The class prefix.
	 * @param   array   $options  Configuration array for table.
	 *
	 * @return  JTable  A table object.
	 *
	 * @throws  Exception
	 */
	public function getTable($name = '', $prefix = '', $options = array())
	{
		if (!$name)
		{
			// use same name of model
			$name = $this->getName();
		}

		if (!$prefix)
		{
			// use default system prefix
			$prefix = 'VAPTable';
		}

		// invoke parent
		return parent::getTable($name, $prefix, $options);
	}
}
