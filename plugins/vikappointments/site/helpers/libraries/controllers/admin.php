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

jimport('joomla.application.component.controlleradmin');

/**
 * Extends the JControllerAdmin methods.
 *
 * @since 	1.6
 */
class UIControllerAdmin extends JControllerAdmin
{
	/**
	 * @override
	 * Redirects the browser or returns false if no redirect is set.
	 *
	 * @return  boolean  False if no redirect exists.
	 */
	public function redirect($url = null)
	{
		// if empty URL get parent property
		if (empty($url) && isset($this->redirect))
		{
			$url = $this->redirect;
		}

		// proceed only if the URL is not empty
		if (!empty($url))
		{
			$app = JFactory::getApplication();

			// enqueue message (only if set)
			if (!empty($this->message))
			{
				$this->messageType = !empty($this->messageType) ? $this->messageType : '';
				$app->enqueueMessage($this->message, $this->messageType);
			}

			// get Item ID
			$itemid = $app->input->getInt('Itemid');

			// append Item ID if set and it is not contained in the URL
			if ($itemid && strpos($url, 'Itemid=') === false)
			{
				$url .= '&Itemid=' . $itemid;
			}

			// do redirect
			$app->redirect(JRoute::rewrite($url, false));
		}
	}

	/**
	 * Protected method to delete the given records.
	 *
	 * @param 	mixed 	$id 	The primary key value or a list of values.
	 * @param 	mixed 	$table 	The database table or an associative array
	 * 							containing the DB requirements:
	 * 							- table 	The DB table name (mandatory).
	 * 							- pk 		The primary key column name (optional).
	 * @param 	array 	$where 	An array of WHERE statements to use (with AND glue).
	 *
	 * @return 	boolean
	 */
	protected function _delete($id, $table, array $where = array())
	{
		if (empty($id))
		{
			return false;
		}

		$dbo = JFactory::getDbo();

		if (!is_array($table))
		{
			// if the table is a string we need to push
			// it in an associative array
			$table = array('table' => $table);
		}

		// get database requirements
		$pk 	= isset($table['pk']) 	 ? $table['pk'] 	: 'id';
		$table 	= isset($table['table']) ? $table['table']	: '';

		$q = $dbo->getQuery(true)
			->delete($dbo->qn($table));

		// create PRIMARY KEY statement
		if (is_array($id))
		{
			// quote the IDs
			$id = array_map(array($dbo, 'q'), $id);
			// create IN statement
			$q->where($dbo->qn($pk) . ' IN (' . implode(',', $id) . ')');
		}
		// skip if we should delete all ignoring the PK
		else if ($id != '*')
		{
			$q->where($dbo->qn($pk) . ' = ' . $dbo->q($id));
		}

		// create WHERE statement with custom claus
		foreach ($where as $column => $values)
		{
			if (is_array($values) && count($values) > 1)
			{
				$values = array_map(array($dbo, 'q'), $values);
				$q->where($dbo->qn($column) . ' IN (' . implode(',', $values) . ')');
			}
			else
			{
				$values = is_array($values) ? array_shift($values) : $values;
				$q->where($dbo->qn($column) . ' = ' . $dbo->q($values));
			}
		}

		$dbo->setQuery($q);
		$dbo->execute();

		return (bool) $dbo->getAffectedRows();
	}

	/**
	 * Protected method to change the ordering of a record.
	 *
	 * @param 	mixed 	$id 	The primary key value.
	 * @param 	mixed 	$table 	The database table or an associative array
	 * 							containing the DB requirements:
	 * 							- table 	The DB table name (mandatory).
	 * 							- pk 		The primary key column name (optional).
	 *							- ordering 	The ordering column name (optional).
	 * @param 	string 	$mode 	The code to move the record UP ('up') or DOWN ('down').
	 * @param 	array 	$where 	An array of WHERE statements to use (with AND glue).
	 *
	 * @return 	void
	 */
	protected function _move($id, $table, $mode = 'up', array $where = array())
	{
		$dbo = JFactory::getDbo();

		if (!is_array($table))
		{
			// if the table is a string we need to push
			// it in an associative array
			$table = array('table' => $table);
		}

		// get database requirements
		$ordcol = isset($table['ordering']) ? $table['ordering'] 	: 'ordering';
		$idcol 	= isset($table['pk']) 		? $table['pk'] 			: 'id';
		$table 	= isset($table['table'])	? $table['table']		: '';

		// get selected record
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array($idcol, $ordcol)))
			->from($dbo->qn($table))
			->where($dbo->qn($idcol) . ' = ' . $dbo->q($id));
		
		$dbo->setQuery($q, 0, 1);
		$data = $dbo->loadObject();

		if (!$data)
		{
			// row not found
			return;
		}

		// get next/prev record
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array($idcol, $ordcol)))
			->from($dbo->qn($table))
			->where($dbo->qn($ordcol) . ' ' . ($mode == 'up' ? '<' : '>') . ' ' . $data->{$ordcol})
			->order($dbo->qn($ordcol) . ' ' . ($mode == 'up' ? 'DESC' : 'ASC'));

		// create WHERE statement with custom claus
		foreach ($where as $column => $values)
		{
			if (is_array($values) && count($values) > 1)
			{
				$values = array_map(array($dbo, 'q'), $values);
				$q->where($dbo->qn($column) . ' IN (' . implode(',', $values) . ')');
			}
			else
			{
				$values = is_array($values) ? array_shift($values) : $values;
				$q->where($dbo->qn($column) . ' = ' . $dbo->q($values));
			}
		}
		
		$dbo->setQuery($q, 0, 1);
		$next = $dbo->loadObject();

		if (!$next)
		{
			// the row is probably the first/last
			return;
		}

		// swap orderings
		$tmp 			 = $data->{$ordcol};
		$data->{$ordcol} = $next->{$ordcol};
		$next->{$ordcol} = $tmp;

		// update the records
		$dbo->updateObject($table, $data, $idcol);
		$dbo->updateObject($table, $next, $idcol);
	}

	/**
	 * Protected method to change the status of the given records.
	 *
	 * @param 	mixed 	$id 	 The primary key value or a list of values.
	 * @param 	mixed 	$status  The status key to set.
	 * @param 	mixed 	$table 	 The database table or an associative array
	 * 							 containing the DB requirements:
	 * 							 - table 	The DB table name (mandatory).
	 * 							 - pk 		The primary key column name (optional).
	 *							 - status 	The status column name (optional).
	 * @param 	array 	$where 	 An array of WHERE statements to use (with AND glue).
	 *
	 * @return 	boolean
	 */
	protected function _publish($id, $status, $table, array $where = array())
	{
		if (empty($id))
		{
			return false;
		}

		$dbo = JFactory::getDbo();

		if (!is_array($table))
		{
			// if the table is a string we need to push
			// it in an associative array
			$table = array('table' => $table);
		}

		// get database requirements
		$pk 	= isset($table['pk']) 		? $table['pk'] 		: 'id';
		$col 	= isset($table['status']) 	? $table['status'] 	: 'status';
		$table 	= isset($table['table'])	? $table['table']	: '';

		$q = $dbo->getQuery(true)
			->update($dbo->qn($table))
			->set($dbo->qn($col) . ' = ' . $dbo->q($status));

		// create PRIMARY KEY statement
		if (is_array($id))
		{
			// quote the IDs
			$id = array_map(array($dbo, 'q'), $id);
			// create IN statement
			$q->where($dbo->qn($pk) . ' IN (' . implode(',', $id) . ')');
		}
		else
		{
			$q->where($dbo->qn($pk) . ' = ' . $dbo->q($id));
		}

		// create WHERE statement with custom claus
		foreach ($where as $column => $values)
		{
			if (is_array($values) && count($values) > 1)
			{
				$values = array_map(array($dbo, 'q'), $values);
				$q->where($dbo->qn($column) . ' IN (' . implode(',', $values) . ')');
			}
			else
			{
				$values = is_array($values) ? array_shift($values) : $values;
				$q->where($dbo->qn($column) . ' = ' . $dbo->q($values));
			}
		}

		$dbo->setQuery($q);
		$dbo->execute();

		return (bool) $dbo->getAffectedRows();
	}
}
