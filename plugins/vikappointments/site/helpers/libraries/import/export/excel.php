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
 * @since 1.7
 */
class ExportableExcel extends Exportable
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

		// check whether we need to exclude some columns from the CSV
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

		// iterate all the cells to switch from UTF-8 encoding to
		// UTF-16LE encoding, which seems to be mandatory for Excel
		foreach ($records as $i => $record)
		{
			foreach ($record as $k => $v)
			{
				$records[$i][$k] = mb_convert_encoding($v, 'UTF-16LE', 'UTF-8');
			}
		}

		$app = JFactory::getApplication();

		$app->setHeader('Cache-Control', 'no-store, no-cache');
		$app->setHeader('Content-Type', 'text/csv; charset=UTF-16LE');
		$app->setHeader('Content-Disposition', 'attachment; filename="' . $name . '.csv"');
		$app->sendHeaders();
		
		$handle = fopen('php://output', 'w');

		// UTF-8 BOM at the beginning of the file should not be needed,
		// since we already converted the whole contents
		// fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

		// insert role to inform Excel what's the character used to
		// separate the values (use a semicolon by default)
		fputs($handle, "sep=;\n");

		foreach ($records as $row)
		{
			// generate CSV row by using semicolon as separator
			// and double quotes as enclosure
			fputcsv($handle, $row, ";", "\"");
		}

		fclose($handle);
	}
}
