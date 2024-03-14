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
 * VikAppointments services reports model.
 *
 * @since 1.7
 */
class VikAppointmentsModelReportsser extends JModelVAP
{
	/**
	 * Loads the filters from the input request.
	 *
	 * @return 	array
	 */
	public function getFiltersFromRequest()
	{
		$app = JFactory::getApplication();

		$filters = array();
		$filters['datefrom']  = $app->getUserStateFromRequest('vap.reportsser.datefrom', 'datefrom', null, 'string');
		$filters['dateto']    = $app->getUserStateFromRequest('vap.reportsser.dateto', 'dateto', null, 'string');
		$filters['valuetype'] = $app->getUserStateFromRequest('vap.reportsser.valuetype', 'valuetype', 'total', 'string');
		$filters['checkin']   = $app->getUserStateFromRequest('vap.reportsser.checkin', 'checkin', 1, 'uint');

		// obtain employees filter
		$filters['employees'] = $app->input->get('employees', array(), 'uint');

		if (VAPDateHelper::isNull($filters['datefrom']))
		{
			$filters['datefrom'] = null;
		}

		if (VAPDateHelper::isNull($filters['dateto']))
		{
			$filters['dateto'] = null;
		}

		return $filters;
	}

	/**
	 * Returns the form data of the specified services.
	 *
	 * @param 	mixed  $id       Either a service ID or an array.
	 * @param 	array  $filters  An array of filters.
	 *
	 * @return 	array  An array of services.
	 */
	public function getFormData($id, array $filters = null)
	{
		$id = (array) $id;

		// load services list
		$services = array();

		if ($id)
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'name')))
				->from($dbo->qn('#__vikappointments_service'))
				->where($dbo->qn('id') . ' IN (' . implode(',', array_map('intval', $id)) . ')');

			$dbo->setQuery($q);
			$services = $dbo->loadObjectList();
		}

		// import statistics framework
		VAPLoader::import('libraries.statistics.factory');

		if (!$filters)
		{
			// load filters from request
			$filters = $this->getFiltersFromRequest();
		}

		foreach ($services as $service)
		{
			// prepare widget settings
			$filters['service'] = $service->id;
			$filters['chart']   = 'line';

			// get widget instance
			$service->lineChart = VAPStatisticsFactory::getInstance('services_employees_chart', $filters);

			// get widget instance
			$service->pieChart = VAPStatisticsFactory::getInstance('services_employees_count', $filters);
		}

		return $services;
	}

	/**
	 * Downloads the reports of the specified services.
	 *
	 * @param 	mixed  $id       Either a service ID or an array.
	 * @param 	array  $filters  An array of filters.
	 *
	 * @return 	void
	 */
	public function download($id, array $filters = null)
	{
		// get available widgets
		$data = $this->getFormData($id, $filters);

		// count number of services
		$count = count($data);

		if ($count == 0)
		{
			// nothing to export here...
			throw new UnexpectedValueException('Nothing to export', 400);
		}

		if ($count == 1)
		{
			// Directly download the report.
			// This method automatically terminates the session.
			$data[0]->lineChart->export();
		}

		// make sure the class to compress files exists
		if (!class_exists('ZipArchive'))
		{
			// class not found, unable to create archive
			throw new RuntimeException('The ZipArchive class is not installed on your server.', 500);
		}

		$files = array();

		// iterate all widgets and save the exported reports in a file
		foreach ($data as $service)
		{
			// save and obtain file path
			$path = $service->lineChart->export('file');

			// make sure the file exists
			if (is_file($path))
			{
				$files[] = $path;
			}
		}

		$app = JFactory::getApplication();

		// obtain temporary folder
		$path = rtrim($app->get('tmp_path'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		// create ZIP path
		$zipname = $path . 'reports-' . time() . '.zip';

		// create archive			
		$zip = new ZipArchive;
		$zip->open($zipname, ZipArchive::CREATE);

		// include all the created report files
		foreach ($files as $file)
		{
			// get readable file name
			$name = preg_replace("/-[\d]+\.csv$/i", '.csv', basename($file));
			// include file
			$zip->addFile($file, $name);
		}

		// compress archive
		$zip->close();

		// send headers for download
		$app->setHeader('Content-Type', 'application/zip');
		$app->setHeader('Content-Disposition', 'attachment; filename=reports.zip');
		$app->setHeader('Content-Length', filesize($zipname));
		$app->sendHeaders();
		
		// start downloading the file
		readfile($zipname);

		// delete archive on completion
		unlink($zipname);

		// delete single reports too
		foreach ($files as $file)
		{
			unlink($file);
		}
	}
}
