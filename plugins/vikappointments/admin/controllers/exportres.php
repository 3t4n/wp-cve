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

VAPLoader::import('libraries.mvc.controllers.admin');

/**
 * VikAppointments export appointments controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerExportres extends VAPControllerAdmin
{
	/**
	 * Task used to access the creation page of a new record.
	 *
	 * @return 	boolean
	 */
	public function add()
	{
		$app = JFactory::getApplication();

		$data = array();
		$type = $app->input->get('type', 'appointment');
		$cid  = $app->input->get('cid', array(), 'uint');

		if ($type)
		{
			$data['type'] = $type;
		}

		if ($cid)
		{
			$data['cid'] = $cid;
		}

		// unset user state for being recovered again
		$app->setUserState('vap.exportres.data', $data);

		$this->setRedirect('index.php?option=com_vikappointments&view=exportres');

		return true;
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the management
	 * page of the record that has been saved.
	 *
	 * @return 	boolean
	 */
	public function save()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$user  = JFactory::getUser();

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		$driver   = $input->get('driver', '', 'string');
		$type     = $input->get('type', 'appointment', 'string');
		$filename = $input->get('filename', '', 'string');
		
		$args = array();
		$args['fromdate']    = $input->get('fromdate', '', 'string');
		$args['todate']      = $input->get('todate', '', 'string');
		$args['cid']         = $input->get('cid', array(), 'uint');
		$args['id_employee'] = $input->get('id_employee', 0, 'uint');
		$args['admin']       = true;

		/**
		 * Reformat dates in UTC according to the user locale.
		 *
		 * @since 1.7
		 */
		$args['fromdate'] = VAPDateHelper::getSqlDateLocale($args['fromdate'],  0,  0,  0);
		$args['todate']   = VAPDateHelper::getSqlDateLocale(  $args['todate'], 23, 59, 59);

		switch ($type)
		{
			case 'appointment':
				$rule = 'core.access.reservations';
				break;

			default:
				$rule = 'core.admin';
		}

		// check user permissions
		if (!$user->authorise($rule, 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		VAPLoader::import('libraries.order.export.factory');

		try
		{
			// get driver instance ready to the usage
			$driver = VAPOrderExportFactory::getDriver($driver, $type, $args);

			// save driver parameters before exporting, otherwise
			// the database update won't be performed
			$driver->saveParams();

			// download the exported data
			$driver->download();
		}
		catch (Exception $e)
		{
			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $e->getMessage()), 'error');

			$url = 'index.php?option=com_vikappointments&view=exportres&type=' . $type;

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		// do not go ahead to avoid including template resources
		$app->close();
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @return 	void
	 */
	public function cancel()
	{
		$app = JFactory::getApplication();

		$type = $app->input->get('type', 'appointment');

		switch ($type)
		{
			case 'appointment':
				$view = 'reservations';
				break;

			default:
				$view = $type;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=' . $view);
	}

	/**
	 * AJAX end-point used to retrieve the configuration
	 * of the selected driver.
	 *
	 * @return 	void
	 */
	public function getdriverformajax()
	{
		$input = JFactory::getApplication()->input;

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}
		
		$driver = $input->getString('driver');
		$type   = $input->getString('type', 'appointment');
		
		VAPLoader::import('libraries.order.export.factory');

		// get driver instance
		$driver = VAPOrderExportFactory::getInstance($driver, $type);

		// get configuration form
		$form = $driver->getForm();
		
		// get configuration params
		$params = $driver->getParams();
		
		// build display data
		$data = array(
			'fields' => $form,
			'params' => $params,
			'prefix' => 'export_',
		);

		// render form by using the payment fields layout
		$html = JLayoutHelper::render('form.fields', $data);

		// get driver description
		$description = $driver->getDescription();

		if ($description)
		{
			// include description within the form
			$html = VAPApplication::getInstance()->alert($description, 'info') . $html;
		}
		
		// send HTML form to caller
		$this->sendJSON(json_encode($html));
	}
}
