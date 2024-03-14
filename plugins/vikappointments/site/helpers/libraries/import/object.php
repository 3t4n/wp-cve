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

VAPLoader::import('libraries.import.column');

/**
 * Class used to handle a generic import event.
 * This class is able to import a list of records starting 
 * from a CSV file.
 *
 * The CSV must start with a valid heading, otherwise the first row
 * will be skipped.
 *
 * @since 1.6
 */
class ImportObject
{
	/**
	 * The XML instructions object.
	 *
	 * @var SimpleXMLElement
	 */
	protected $xml;

	/**
	 * The import entity type.
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Property used to load the table columns only once.
	 *
	 * @var   array
	 * @since 1.7
	 */
	protected $columns = null;

	/**
	 * The filter input handler.
	 *
	 * @var mixed
	 */
	protected $filter;

	/**
	 * The path of the file to import.
	 *
	 * @var string
	 */
	protected $file = null;

	/**
	 * The total number of records fetched.
	 * Used by both the import and export methods.
	 *
	 * @var integer
	 */
	protected $total = 0;

	/**
	 * A list of errors.
	 *
	 * @var array
	 */
	protected $errors = array();

	/**
	 * Class constructor.
	 *
	 * @param 	object 	$xml 	The XML object.
	 * @param 	string 	$type 	The entity type to import.
	 *
	 */
	public function __construct($xml, $type)
	{
		$this->xml    = $xml;
		$this->type   = $type;
		$this->filter = JFilterInput::getInstance();
	}

	/**
	 * Returns the path of the file to import.
	 * The path found is always cached to avoid retrieving
	 * it during the next accesses.
	 *
	 * @return 	mixed 	The file path if exists, otherwise false.
	 */
	public function getFile()
	{
		if ($this->file === null)
		{
			$folder = VAPADMIN . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR;

			$file = glob($folder . $this->type . '_*.csv');

			if (count($file))
			{
				$this->file = array_pop($file);
			}
			else
			{
				$this->file = false;
			}
		}

		return $this->file;
	}

	/**
	 * Checks if the file is ready to be imported.
	 *
	 * @return 	boolean  True if the file exists, otherwise false.
	 *
	 * @uses 	getFile()
	 */
	public function hasFile()
	{
		return (bool) $this->getFile();
	}

	/**
	 * Returns the database table to use while importing the records.
	 *
	 * @param 	boolean  True to return a database table instance.
	 *                   Otherwise the database table name will be returned.
	 *
	 * @return 	mixed 	 The DB table name or a JTable instance.
	 */
	public function getTable($object = false)
	{
		/**
		 * Check whether we should return a JTable instance.
		 * 
		 * @since 1.7
		 */
		if ($object)
		{
			// create table instance
			return JTableVAP::getInstance($this->xml->table->attributes()->id, 'VAPTable');
		}

		// return table name
		return (string) $this->xml->table->attributes()->name;
	}

	/**
	 * Returns the primary key of the database table.
	 *
	 * @param 	string 	$def 	The default primary key to use
	 * 							if not specified.
	 *
	 * @return 	string 	The primary key.
	 */
	public function getPrimaryKey($def = 'id')
	{
		$pk = (string) $this->xml->table->attributes()->pk;

		if (empty($pk))
		{
			$pk = $def;
		}

		return $pk;
	}

	/**
	 * Returns all the available columns that can be assigned
	 * to the values listed in the CSV file.
	 * @since 1.7 columns are always translated and loaded once.
	 *
	 * @return 	array 	 The list of available columns.
	 */
	public function getColumns()
	{
		if (is_null($this->columns))
		{
			$this->columns = array();

			foreach ($this->xml->table->column as $column)
			{
				/**
				 * Use an apposite instance to hold the column attributes.
				 * For backward compatibility, the instance will act as
				 * a plain object.
				 *
				 * @since 1.7
				 */
				$obj = ImportColumn::getInstance($column);

				// skip in case the column didn't specify a name
				if ($obj->name)
				{
					$this->columns[$obj->name] = $obj;
				}
			}

			$dispatcher = VAPFactory::getEventDispatcher();

			/**
			 * Trigger event to let external plugins be able to include
			 * additional columns that are not mentioned within the default
			 * XML file of this import/export type.
			 *
			 * Any attached element should support the following parameters:
			 * - name      string  the column name equals to array key (mandatory);
			 * - label     string  the column readable label (optional);
			 * - required  bool    true if required (optional);
			 * - default   mixed   the default value if missing (optional);
			 * - filter    string  the filter type (optional);
			 * - type      string  the type of column (optional);
			 * - options   array   an array of placeholders (optional);
			 *
			 * @param 	string   $type  The current import/export type.
			 *
			 * @return 	array    A list of columns to append.
			 *
			 * @since 	1.7
			 */
			$results = $dispatcher->trigger('onLoadImportExportColumns', array($this->type));
			
			// iterate all returned results
			foreach ($results as $arr)
			{
				foreach ($arr as $column)
				{
					// instantiate column with returned properties
					$obj = ImportColumn::getInstance($column);

					// skip in case the column didn't specify a name
					if ($obj->name)
					{
						$this->columns[$obj->name] = $obj;
					} 
				}
			}
		}

		return $this->columns;
	}

	/**
	 * Returns the cancellation task, if any.
	 *
	 * @return 	mixed 	The cancel task if specified, otherwise false.
	 */
	public function getCancelTask()
	{
		$task = (string) $this->xml->cancel->attributes()->task;

		if (empty($task))
		{
			return false;
		}

		return $task;
	}

	/**
	 * Returns the file containing the sample data to import this type of object.
	 *
	 * @return 	mixed 	The file path if exists, otherwise false.
	 */
	public function getSampleFile()
	{
		$sample = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'samples' . DIRECTORY_SEPARATOR . $this->type . '.csv';

		if (file_exists($sample))
		{
			return $sample;
		}

		return false;
	}

	/**
	 * Checks if this object owns any sample data file.
	 *
	 * @return 	boolean  True if exists, otherwise false.
	 *
	 * @uses 	getSampleFile()
	 */
	public function hasSampleFile()
	{
		return $this->getSampleFile() !== false;
	}

	/**
	 * Returns a preview of the records contained in the file.
	 *
	 * @param 	integer  $lim 	The maximum number of records to obtain.
	 *
	 * @return 	array 	 The records list.
	 *
	 * @uses 	getFile()
	 */
	public function getRecords($lim = 10)
	{
		$rows = array();

		$file = $this->getFile();

		$handle = fopen($file, 'r');

		$count = 0;

		while (($buffer = fgetcsv($handle)) && $count <= $lim)
		{
			$rows[] = $buffer;
			$count++;
		}

		fclose($handle);

		return $rows;
	}

	/**
	 * Returns the total number of records fetched.
	 *
	 * @return 	integer  The total count.
	 */
	public function getTotalCount()
	{
		return $this->total;
	}

	/**
	 * Pushes a new error in the list.
	 *
	 * @param 	object 	$data 	The record failed.
	 * @param 	string 	$err 	The error message.
	 *
	 * @return 	self 	This object to support chaining.
	 */
	protected function setError($data, $err = '')
	{
		if (empty($err))
		{
			$err = JText::translate('VAPIMPORTINSERTERR');
		}

		$str = '<b>' . $err . '</b><br />';

		$data = (array) $data;

		if ($data)
		{
			$str .= '<pre>' . implode(', ', $data) . '</pre>';
		}

		$this->errors[] = $str;

		return $this;
	}

	/**
	 * Returns a list of errors raised.
	 *
	 * @return 	array 	An errors list.
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Processes the event to import all the records
	 * contained in the CSV file.
	 *
	 * @param 	array 	 $assoc 	Associative array used to match
	 * 								the columns of the table with the columns
	 * 								of the CSV records.
	 * @param 	array 	 $args 		Associative list of additional parameters.
	 *
	 * @return 	integer  The number of imported records.
	 *
	 * @uses 	getColumns()
	 * @uses 	getFile()
	 * @uses 	getTable()
	 * @uses 	getPrimaryKey()
	 * @uses 	bind()
	 */
	public function save(array $assoc, array $args = array())
	{
		$dbo = JFactory::getDbo();
		
		$cols 	= $this->getColumns();
		$file 	= $this->getFile();
		$table 	= $this->getTable($object = true);
		$pk 	= $this->getPrimaryKey();

		// make sure this object supports a database table instance
		if (!$table)
		{
			// nope, lets load the database table name
			$table = $this->getTable();
		}

		$handle = fopen($file, 'r');

		$count = $this->total = 0;

		// reset errors list
		$this->errors = array();

		$head = null;

		while (($buffer = fgetcsv($handle)))
		{
			if ($head === null)
			{
				$head = $buffer;
			}
			else
			{
				$record = new stdClass;
				$valid 	= true;

				foreach ($buffer as $k => $v)
				{
					if (!empty($assoc[$head[$k]]))
					{
						// get the column related to the specified CSV head
						$column = $assoc[$head[$k]];

						// if the value is empty, try to use the default column value
						if (strlen($v) == 0 && !empty($cols[$column]->default))
						{
							$v = $cols[$column]->default;
						}
						else
						{
							/**
							 * Check whether this column requires some adjustments.
							 *
							 * @since 1.7
							 */
							$v = $cols[$column]->onImport($v);
						}

						// try to filter the specified value
						if (!empty($cols[$column]->filter))
						{
							$v = $this->filter->clean($v, $cols[$column]->filter);
						}

						// check if the value MUST NOT be empty
						if (empty($v) && $cols[$column]->required)
						{
							// empty required value, the object
							// should not be imported
							$valid = false;
						}

						$record->{$assoc[$head[$k]]} = $v;
					}
				}

				if ($valid && $this->bind($record, $args))
				{
					$msg = null;

					if ($table instanceof JTable)
					{
						// always reset table before creating a new record
						$table->reset();
						// unset primary key
						$record->{$table->getKeyName()} = 0;

						// attempt to save by using the table instance
						$res = $table->save($record);

						if (!$res)
						{
							// get registered error message
							$msg = $table->getError();
						}
					}
					else
					{
						try
						{
							// table not supported, use direct DB insert
							$res = $dbo->insertObject($table, $record, $pk) && $record->{$pk};
						}
						catch (Exception $e)
						{
							$res = false;
							$msg = $e->getMessage();
						}
					}

					if ($res)
					{
						// imported
						$count++;
					}
					else
					{
						// an error occurred
						$this->setError($record, $msg);
					}
				}

				$this->total++;
			}
		}

		fclose($handle);

		return $count;
	}

	/**
	 * Method used to bind the provided object. By returning
	 * false the system won't proceed importing the current record.
	 *
	 * A record won't be imported if it doesn't own any property.
	 *
	 * @param 	object 	 &$data  The object of the record to import.
	 * @param 	array 	 $args 	 Associative list of additional parameters.
	 *
	 * @return 	boolean  True if the record should be imported, otherwise false.
	 */
	protected function bind(&$data, array $args = array())
	{
		$vars = get_object_vars($data);

		return !is_null($vars) && count(array_keys($vars));
	}

	/**
	 * Returns a list of the records to export.
	 * @since 	1.7  $full argument has been replaced by an array of options,
	 *               which now includes it as attribute.
	 *
	 * @param 	array   $options  An array of export options.
	 *                            - full     bool   true to use a query limit;
	 *                            - raw      bool   true to format the records values;
	 *                            - columns  array  a list of columns to export.
	 *
	 * @return 	array 	The records to export.
	 *
	 * @uses 	buildExportQuery()
	 * @uses 	formatRecords()
	 */
	public function getExportableRows($options = array())
	{
		$app = JFactory::getApplication();
		$dbo = JFactory::getDbo();

		// check whether the options is a boolean for BC
		if (is_bool($options))
		{
			// define options with given full value
			$options = array('full' => $options);
		}

		// include application and database within options array
		$options['app'] = $app;
		$options['dbo'] = $dbo;

		// Create a registry for ease of use.
		// Use JObject in place of JRegistry because this
		// one accepts only standard elements.
		$options = new JObject($options);

		$q = $this->buildExportQuery($options);

		if ($options->get('full', false))
		{
			// unset limit
			$lim0 = $lim = null;
		}
		else
		{
			$lim0 = 0;
			$lim  = 10;
		}

		$dbo->setQuery($q, $lim0, $lim);
		$rows = $dbo->loadAssocList();

		if ($rows)
		{
			/**
			 * Check whether we should format the record
			 * while exporting them.
			 *
			 * @since 1.7
			 */
			if (!$options->get('raw'))
			{
				$this->formatRecords($rows);
			}

			// get the total number of rows
			$dbo->setQuery('SELECT FOUND_ROWS();');
			$this->total = $dbo->loadResult();

			return $rows;
		}

		return array();
	}

	/**
	 * Builds the base query to export all the records.
	 * @since 	1.7  $app and $dbo are now included within the $options argument.
	 *
	 * @param 	JObject  $options  A registry of export options.
	 * @param 	string   $alias    The table alias.
	 *
	 * @return 	mixed 	The query builder object.
	 *
	 * @uses 	getColumns()
	 * @uses 	getTable()
	 * @uses 	getPrimaryKey()
	 */
	protected function buildExportQuery($options, $alias = '')
	{
		$app = $options->get('app');
		$dbo = $options->get('dbo');

		$columns = $this->getColumns();
		$table 	 = $this->getTable();
		$pk 	 = $this->getPrimaryKey();

		$q = $dbo->getQuery(true);

		/**
		 * Implemented table alias to support joins with
		 * external tables in children classes.
		 *
		 * @since 1.7
		 */
		$alias = $alias ? $alias : 't';

		// get list of columns to introduce within the query
		$queryColumns = array_keys($columns);

		/**
		 * Check whether we need to exclude some columns from the query.
		 *
		 * @since 1.7
		 */
		if ($selectedColumns = $options->get('columns', array()))
		{
			// take only the columns included within the list
			$queryColumns = array_filter($queryColumns, function($col) use ($selectedColumns)
			{
				return in_array($col, $selectedColumns);
			});
		}

		// Calculate the total number of records fetched.
		// Pop the first column to concat SQL_CALC_FOUND_ROWS.
		$q->select('SQL_CALC_FOUND_ROWS ' . $dbo->qn($alias . '.' . array_shift($queryColumns)));

		// map the columns to select
		foreach ($queryColumns as $col)
		{
			$q->select($dbo->qn($alias . '.' . $col));
		}

		// define the table to access
		$q->from($dbo->qn($table, $alias));

		$ids = $app->input->get('cid', array(), 'string');

		if (count($ids))
		{
			// map the array to quote each element
			$ids = array_map(array($dbo, 'q'), $ids);
			// build IN statement
			$q->where($dbo->qn($alias . '.' . $pk) . ' IN (' . implode(', ', $ids) . ')');
		}

		// create hook for query manipulation
		$event = 'onBeforeListQuery' . ucfirst($this->type);

		// Create a dummy object to replicate the behavior of a view.
		// Not the best solution but does its job, at least until
		// List models will be implemented...
		$view = new stdClass;
		$view->filters     = array();
		$view->ordering    = 'id';
		$view->orderingDir = 'asc';

		/**
		 * Replicate same hook used by the view in order to keep the
		 * custom filters also while exporting the records.
		 *
		 * @since 1.7
		 */
		VAPFactory::getEventDispatcher()->trigger($event, array(&$q, $view));

		return $q;
	}

	/**
	 * Formats the records according to the type of the columns
	 * specified with the XML manifest.
	 *
	 * @param 	array  &$rows  The rows to export.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 *
	 * @uses 	getColumns()
	 */
	protected function formatRecords(&$rows)
	{
		// get support columns
		$columns = $this->getColumns();

		// iterate exported records
		foreach ($rows as $i => $row)
		{
			// iterate columns of current record
			foreach ($row as $k => $v)
			{
				// make sure the column exists because the query of the import handler
				// might have manually selected the columns of another table
				if (isset($columns[$k]))
				{
					// attempt to format the value
					$rows[$i][$k] = $columns[$k]->format($v);
				}
			}
		}
	}

	/**
	 * Exports the records using the given handler.
	 *
	 * @param 	Exportable 	$handler  The export handler.
	 * @param 	string 		$name     The file name.
	 * @param 	array       $options  An array of export options.
	 *
	 * @return 	void
	 *
	 * @uses 	getExportableRows()
	 */
	public function export($handler, $name)
	{
		/**
		 * Extract export options from handler.
		 *
		 * @since 1.7
		 */
		$options = $handler->getOptions();

		// ignore query limits
		$options['full'] = true;

		// get rows to export
		$rows = $this->getExportableRows($options);

		if (!$name)
		{
			$name = strtolower($this->type);
		}

		// export the records
		$handler->download($name, $rows, $this);
	}
}
