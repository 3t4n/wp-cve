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
 * VikAppointments analytics controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerAnalytics extends VAPControllerAdmin
{
	/**
	 * Task used to access the creation page of a new record.
	 *
	 * @return 	boolean
	 */
	public function add()
	{
		$app = JFactory::getApplication();

		$data  = array();

		$location = $app->input->get('location', null, 'string');

		if ($location)
		{
			$data['location'] = $location;
		}

		// unset user state for being recovered again
		$app->setUserState('vap.statistics.data', $data);

		// calculate the ACL rule according to the specified request data
		$acl = $this->getACL($data);

		// check user permissions
		if (!JFactory::getUser()->authorise($acl, 'com_vikappointments'))
		{
			// back to main list, not authorised to create records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=manageanalytics');

		return true;
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the main list.
	 *
	 * @return 	void
	 */
	public function saveclose()
	{
		if ($this->save())
		{
			$this->cancel();
		}
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

		$widgets_id       = $input->get('widget_id', array(), 'uint');
		$widgets_id_user  = $input->get('widget_id_user', array(), 'uint');
		$widgets_name     = $input->get('widget_name', array(), 'string');
		$widgets_class    = $input->get('widget_class', array(), 'string');
		$widgets_position = $input->get('widget_position', array(), 'string');
		$widgets_size     = $input->get('widget_size', array(), 'string');

	    $location = $input->get('location', null, 'string');

	    // calculate the ACL rule according to the specified request data
		$acl = $this->getACL(array('location' => $location));

		// check user permissions
		if (!$user->authorise($acl, 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get widget model
		$widget = $this->getModel('statswidget');

		for ($i = 0; $i < count($widgets_id); $i++)
		{
			// prepare data
			$data = array(
				'id'       => $widgets_id[$i],
				'id_user'  => $widgets_id_user[$i],
				'name'     => $widgets_name[$i],
				'widget'   => $widgets_class[$i],
				'position' => $widgets_position[$i],
				'size'     => $widgets_size[$i],
				'location' => $location,
				'ordering' => $i + 1,
			);

			// save widget
			$widget->save($data);
		}

		// delete widgets
		$widgets_delete = $input->get('widgets_delete', array(), 'uint');
		$widget->delete($widgets_delete);

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=analytics.add&location='  . $location);

		return true;
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @return 	void
	 */
	public function cancel()
	{
		$input = JFactory::getApplication()->input;

		// get location
		$location = $input->get('location', null, 'string');

		if ($location && $location != 'dashboard')
		{
			// back to specific analytics locations
			$this->setRedirect('index.php?option=com_vikappointments&view=analytics&location=' . $location);
		}
		else
		{
			// back to dashboard
			$this->setRedirect('index.php?option=com_vikappointments');
		}
	}

	/**
	 * AJAX end-point used to obtain the widget contents or datasets.
	 *
	 * @return 	void
	 */
	public function loadwidgetdata()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$user  = JFactory::getUser();

		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}

		// get widget name and ID
		$widget = $input->get('widget', '', 'string');
		$id     = $input->get('id', 0, 'uint');

		VAPLoader::import('libraries.statistics.factory');

		try
		{
			// try to instantiate the widget
			$widget = VAPStatisticsFactory::getWidget($widget);

			if (!$widget->checkPermissions($user))
			{
				// not authorised to access this widget
				throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
			}

			// set up widget ID
			$widget->setID($id);
		}
		catch (Exception $e)
		{
			// an error occurred while trying to access the widget
			UIErrorFactory::raiseError($e->getCode(), $e->getMessage());
		}

		// fetch widget data
		$data = $widget->getData();

		// save only in case of existing widget
		if ($input->getBool('tmp') == false)
		{
			// save parameters for later use
			$widget->saveParams();
		}

		// send response to caller
		$this->sendJSON(json_encode($data));
	}

	/**
	 * AJAX end-point used to save the widget contents.
	 *
	 * @return 	void
	 */
	public function savewidgetdata()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$user  = JFactory::getUser();

		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}

		// get widget name and ID
		$widget = $input->get('widget', '', 'string');
		$id     = $input->get('id', 0, 'uint');

		VAPLoader::import('libraries.statistics.factory');

		try
		{
			// try to instantiate the widget
			$widget = VAPStatisticsFactory::getWidget($widget);

			if (!$widget->checkPermissions($user))
			{
				// not authorised to access this widget
				throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
			}

			// set up widget ID
			$widget->setID($id);
		}
		catch (Exception $e)
		{
			// an error occurred while trying to access the widget
			UIErrorFactory::raiseError($e->getCode(), $e->getMessage());
		}

		// save parameters
		$widget->saveParams();

		// send response to caller
		$this->sendJSON(1);
	}

	/**
	 * AJAX end-point used to export the widget contents or datasets.
	 *
	 * @return 	void
	 */
	public function export()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$user  = JFactory::getUser();

		if (!JSession::checkToken('get'))
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}

		// get widget name and ID
		$widget = $input->get('widget', '', 'string');
		$id     = $input->get('id', 0, 'uint');
		$rule   = $input->get('rule', null, 'string');

		VAPLoader::import('libraries.statistics.factory');

		try
		{
			// try to instantiate the widget
			$widget = VAPStatisticsFactory::getInstance($widget);

			if (!$widget->checkPermissions($user))
			{
				// not authorised to access this widget
				throw new Exception(JText::translate('JERROR_ALERTNOAUTHOR'), 403);
			}

			if (!$widget->isExportable())
			{
				// the widget doesn't support exportable data
				throw new Exception('Widget not exportable', 500);
			}

			// set up widget ID
			$widget->setID($id);
		}
		catch (Exception $e)
		{
			// an error occurred while trying to access the widget
			UIErrorFactory::raiseError($e->getCode(), $e->getMessage());
		}

		// load widget parameters
		$widget->setOptions($widget->getParams());

		// fetch export data according to the specified rule (if any)
		$widget->export($rule);

		// Do not terminate because the widget might display some contents
		// to support the browser print features. Inject tmpl=component
		// to display a blank page.
		$input->set('tmpl', 'component');

		// append widget name to the browser title
		$doc = JFactory::getDocument();
		$doc->setTitle($doc->getTitle() . ' - ' . $widget->getTitle());
	}

	/**
	 * Calculate the ACL rule according to the specified request data.
	 *
	 * @param 	array 	$data  The request array.
	 *
	 * @return 	string  The related ACL rule.
	 */
	protected function getACL(array $data)
	{
		// default super user
		$acl = 'core.admin';

		$location = isset($data['location']) ? $data['location'] : '';

		if ($location == 'dashboard' || !$location)
		{
			// allow dashboard management
			$acl = 'core.access.dashboard';
		}
		else
		{
			// allow specific location of analytics
			$acl = 'core.access.analytics.' . $location;
		}

		return $acl;
	}
}
