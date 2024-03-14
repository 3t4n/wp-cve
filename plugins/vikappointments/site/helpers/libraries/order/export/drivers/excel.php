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

VAPLoader::import('libraries.order.export.drivers.csv');

/**
 * Driver class used to export the orders/appointments in a format
 * readable by Microsoft Excel. This object inherits the CSV class
 * because this driver just needs to encode the rows fetched to
 * build a CSV file.
 *
 * @since 1.7
 */
class VAPOrderExportDriverExcel extends VAPOrderExportDriverCSV
{
	/**
	 * @override
	 * Returns the form parameters required to the CSV driver.
	 *
	 * @return 	array
	 */
	public function getForm()
	{
		// get parent configuration
		$form = parent::buildForm();

		// unset delimiter and enclosure options because we
		// are going to use always the same ones
		unset($form['delimiter']);
		unset($form['enclosure']);

		// Do not add any hook to extend the form parameters because
		// this class doesn't trigger any actions to manipulate the
		// data to export. Rely on the CSV hooks in case you wish
		// to extend the functionalities of this driver.

		return $form;
	}

	/**
	 * @override
	 * Prepares the application headers to start the download.
	 *
	 * @param 	mixed   $app       The client application.
	 * @param 	string 	$filename  The name of the file that will be downloaded.
	 *
	 * @return 	void 
	 */
	protected function prepareDownload($app, $filename)
	{
		// prepare headers
		$app->setHeader('Cache-Control', 'no-store, no-cache');
		// Excel uses a different encoding (UTF-16LE) than CSV (UTF-8)
		$app->setHeader('Content-Type', 'text/csv; charset=UTF-16LE');
		$app->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');
	}

	/**
	 * @override
	 * Generates the CSV structure by putting the fetched
	 * bytes into the specified resource.
	 *
	 * @param 	mixed 	$handle  The resource pointer created with fopen().
	 *
	 * @return 	void
	 */
	protected function output($handle)
	{
		if (!$handle)
		{
			throw new RuntimeException('Invalid resource for Excel generation');
		}

		// UTF-8 BOM at the beginning of the file should not be needed,
		// since we already converted the whole contents
		// fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

		// insert role to inform Excel what's the character used to
		// separate the values (use a semicolon by default)
		fputs($handle, "sep=;\n");

		// output through parent method
		parent::output($handle);
	}

	/**
	 * @override
	 * Inserts the row within the CSV file.
	 *
	 * @param 	mixed 	$handle     The resource pointer created with fopen().
	 * @param 	array   $row        The row to include.
	 * @param 	mixed   $delimiter  The delimiter used to separate the columns
	 * @param 	mixed   $enclosure  The enclosure used to wrap the values.
	 * 
	 * @return 	void
	 */
	protected function putRow($handle, $row, $delimiter = null, $enclosure = null)
	{
		// iterate all the cells to switch from UTF-8 encoding to
		// UTF-16LE encoding, which seems to be mandatory for Excel
		foreach ($row as $k => $v)
		{
			// Transliterate € symbol because MS Excel seems to have
			// problems with the UTF-16LE encoded version...
			$v = preg_replace("/€/", 'EUR', (string) $v);

			// switch encoding
			$row[$k] = mb_convert_encoding($v, 'UTF-16LE', 'UTF-8');
		}

		// put row through parent by forcing semicolon as separator
		// and double quotes as enclosure
		parent::putRow($handle, $row, ";", "\"");
	}
}
