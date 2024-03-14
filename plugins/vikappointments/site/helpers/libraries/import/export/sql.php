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

VAPLoader::import('libraries.import.exportable');

/**
 * This class implements the abstract methods defined by the
 * exportable interface in order to export the records in SQL format.
 *
 * @since 1.6
 */
class ExportableSql extends Exportable
{
	/**
	 * Class constructor.
	 *
	 * @param 	array 	$options 	An array of options.
	 */
	public function __construct(array $options = array())
	{
		/**
		 * Always force the export in raw mode, because a INSERT
		 * must use the values stored within the database.
		 *
		 * @since 1.7
		 */
		$options['raw'] = true;

		parent::__construct($options);
	}

	/**
	 * Creates the file with the given records and downloads it
	 * using the specified filename.
	 *
	 * @param 	string 		  $name 	The file name.
	 * @param 	array 		  $records 	The records to export.
	 * @param 	ImportObject  $obj 		The object handler.
	 *
	 * @return 	void
	 */
	public function download($name, array $records = array(), ImportObject $obj = null)
	{
		$dbo = JFactory::getDbo();
		
		// get the maximum number of records to insert within the same query
		$maxquery = $this->options->get('maxquery', 1000);
		// get the database prefix to use (if not provided, the current one will be used)
		$dbprefix = $this->options->get('dbprefix', $dbo->getPrefix());

		$q = $dbo->getQuery(true);

		// replace placeholder with the real database prefix
		$db_table = $obj->getTable();
		$db_table = preg_replace("/^#__/", $dbprefix, $db_table);

		// prepare INSERT query
		$q->insert($dbo->qn($db_table));

		// get supported columns
		$columns = $obj->getColumns();

		/**
		 * Check whether we need to exclude some columns from the query.
		 *
		 * @since 1.7
		 */
		if ($selectedColumns = $this->options->get('columns', array()))
		{
			// take only the columns included within the list
			$columns = array_filter($columns, function($col) use ($selectedColumns)
			{
				return in_array($col->name, $selectedColumns);
			});
		}

		// iterate the columns
		foreach ($columns as $column)
		{
			$q->columns($dbo->qn($column->name));
		}

		// make sure the maxquery is positive to avoid loops
		if ($maxquery <= 0)
		{
			$maxquery = 1000;
		}

		$arr = array();

		// splice the records until we have an empty array
		while (count($records) > $maxquery)
		{
			// push the sub-array within the temporary list
			$arr[] = array_splice($records, 0, $maxquery); 
		}

		// if there is still something to export, push it at the end of the list
		if (count($records))
		{
			$arr[] = $records;
		}

		$app = JFactory::getApplication();

		// start headers to download SQL file
		$app->setHeader('Cache-Control', 'no-store, no-cache');
		$app->setHeader('Content-Type', 'application/octet-stream');
		$app->setHeader('Content-Transfer-Encoding', 'Binary'); 
		$app->setHeader('Content-Disposition', 'attachment; filename="' . $name . '.sql"');
		$app->sendHeaders();

		// open output files
		$handle = fopen('php://output', 'w');

		// iterate the sub-arrays
		foreach ($arr as $subarray)
		{
			// clear previous values
			$q->clear('values');

			foreach ($subarray as $row)
			{
				$values = array();

				foreach ($row as $k => $v)
				{
					if (is_null($v))
					{
						// use NULL operator
						$values[] = 'NULL';
					}
					else
					{
						// escape the value
						$values[] = $dbo->q($v);
					}
				}

				$q->values(implode(",", $values));
			}

			// add a new line after each record
			$buffer = ltrim(preg_replace("/\),\(/", "),\n(", (string) $q)) . ";\n";

			fwrite($handle, $buffer);
		}
		
		fclose($handle);
	}
}
