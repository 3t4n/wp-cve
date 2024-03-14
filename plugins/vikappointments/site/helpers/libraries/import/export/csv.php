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
 * exportable interface in order to export the records in CSV format.
 *
 * @since 1.6
 */
class ExportableCsv extends Exportable
{
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
		$head = array();

		// get supported columns
		$columns = $obj->getColumns();

		/**
		 * Check whether we need to exclude some columns from the CSV.
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

		// iterate the columns to build the header
		foreach ($columns as $col)
		{
			$head[] = $col->label;
		}

		// prepend the CSV heading at the beginning of the records list
		array_unshift($records, $head);

		$delimiter = $this->options->get('delimiter', ',');
		$enclosure = $this->options->get('enclosure', '"');

		$app = JFactory::getApplication();

		$app->setHeader('Cache-Control', 'no-store, no-cache');
		$app->setHeader('Content-Type', 'text/csv; charset=UTF-8');
		$app->setHeader('Content-Disposition', 'attachment; filename="' . $name . '.csv"');
		$app->sendHeaders();
		
		$handle = fopen('php://output', 'w');

		foreach ($records as $row)
		{
			fputcsv($handle, $row, $delimiter, $enclosure);
		}

		fclose($handle);
	}
}
