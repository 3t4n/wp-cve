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

VAPLoader::import('libraries.employee.area.controller');

/**
 * Employee area edit custom field controller.
 *
 * @since 1.6
 */
class VikAppointmentsControllerEmpeditcustfield extends VAPControllerAdmin
{
	/**
	 * Task used to access the creation page of a new record.
	 *
	 * @return 	boolean
	 *
	 * @since 	1.7
	 */
	public function add()
	{
		$app  = JFactory::getApplication();
		$auth = VAPEmployeeAuth::getInstance();

		// unset user state for being recovered again
		$app->setUserState('vap.emparea.field.data', array());

		// check user permissions
		if (!$auth->manageCustomFields())
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=empeditcustfield');

		return true;
	}

	/**
	 * Task used to access the management page of an existing record.
	 *
	 * @return 	boolean
	 *
	 * @since 	1.7
	 */
	public function edit()
	{
		$app  = JFactory::getApplication();
		$auth = VAPEmployeeAuth::getInstance();

		// unset user state for being recovered again
		$app->setUserState('vap.emparea.field.data', array());

		$cid = $app->input->getUint('cid', array(0));

		// check user permissions
		if (!$auth->manageCustomFields($cid[0]))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		$this->setRedirect('index.php?option=com_vikappointments&view=empeditcustfield&cid[]=' . $cid[0]);

		return true;
	}

	/**
	 * Task used to save the record data set in the request.
	 * After saving, the user is redirected to the main list.
	 *
	 * @return 	void
	 *
	 * @since   1.7
	 */
	public function saveclose()
	{
		if ($this->save())
		{
			$this->cancel();
		}
	}

	/**
	 * Save employee custom field.
	 *
	 * @return 	void
	 */
	public function save()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$auth  = VAPEmployeeAuth::getInstance();

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

		$id_field = $input->getUint('id', 0);

		// check user permissions
		if (!$auth->manageCustomFields($id_field))
		{
			// back to main list, not authorised to edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}

		// get args
		$args = array();
		$args['name']        = $input->getString('name', '');
		$args['description'] = JComponentHelper::filterText($input->getRaw('description', ''));
		$args['type']        = $input->getString('type', '');
		$args['required']    = $input->getUint('required', 0);
		$args['repeat']      = $input->getUint('repeat', 0);
		$args['rule']        = $input->getString('rule', '');
		$args['services']    = $input->getUint('services', array());
		$args['multiple']    = 0;
		$args['poplink']     = '';
		$args['choose']      = '';
		$args['id']          = $id_field;

		if ($args['type'] == 'select')
		{
			/**
			 * Do not use a string filter so that we can preserve the keys
			 * of the options. Use array_filter instead to get rid of the
			 * options with blank contents.
			 *
			 * @since 1.7
			 */
			$args['choose']   = array_filter($input->get('choose', array(), 'array'));
			$args['multiple'] = $input->getUint('multiple', 0);
		}
		else if ($args['type'] == 'textarea')
		{
			$args['choose'] = array(
				'editor' => $input->getUint('use_editor', 0),
			);
		}
		else if ($args['type'] == 'number')
		{
			$args['choose'] = array(
				'min'      => $input->getString('number_min', ''),
				'max'      => $input->getString('number_max', ''),
				'decimals' => $input->getUint('number_decimals', 0),
			);

			if (strlen($args['choose']['min']))
			{
				$args['choose']['min'] = (float) $args['choose']['min'];
			}

			if (strlen($args['choose']['max']))
			{
				$args['choose']['max'] = (float) $args['choose']['max'];
			}
		}
		else if ($args['type'] == 'checkbox')
		{
			$args['poplink'] = $input->getString('poplink', '');
		}
		else if ($args['type'] == 'file')
		{
			$args['choose']   = $input->getString('filters', '');
			$args['multiple'] = $input->getUint('multiple', 0);
		}
		else if ($args['type'] == 'separator')
		{
			$args['choose'] = $input->getString('sep_suffix', '');
		}
		
		if ($args['rule'] == 'phone')
		{
			$args['choose'] = $input->getString('country_code', '');
		}

		// get custom field model
		$model = $this->getModel();

		// try to save arguments
		$id = $model->save($args);

		if (!$id)
		{
			// get string error
			$error = $model->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'index.php?option=com_vikappointments&view=empeditcustfield';

			if ($id_field)
			{
				$url .= '&cid[]=' . $id_field;
			}

			// redirect to edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		// redirect to edit page
		$this->setRedirect('index.php?option=com_vikappointments&task=empeditcustfield.edit&cid[]=' . $id);

		return true;
	}

	/**
	 * Removes the custom field.
	 *
	 * @return 	void
	 */
	public function delete()
	{
		$app = JFactory::getApplication();
		$cid = $app->input->get('cid', array(), 'uint');

		if ($id = $app->input->getUint('id'))
		{
			$cid[] = $id;
		}

		/**
		 * Added token validation.
		 * Both GET and POST are supported.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken() && !JSession::checkToken('get'))
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		try
		{
			// delete selected records
			if ($this->getModel()->delete($cid))
			{
				$app->enqueueMessage(JText::translate('VAPEMPCUSTOMFREMOVED1'));	
			}
		}
		catch (Exception $e)
		{
			// an error occurred
			$app->enqueueMessage($e->getMessage(), 'error');
			$this->cancel();

			return false;
		}

		// back to main list (reset list limit)
		$this->cancel(['listlimit' => 0]);

		return true;
	}

	/**
	 * Publishes the custom field.
	 *
	 * @return 	void
	 */
	public function publish()
	{
		$app = JFactory::getApplication();

		$cid  = $app->input->get('cid', array(), 'uint');
		$task = $app->input->get('task', null);

		$state = $task == 'unpublish' ? 0 : 1;

		/**
		 * Added token validation.
		 * Both GET and POST are supported.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken() && !JSession::checkToken('get'))
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		// publish selected records
		$this->getModel()->publish($cid, $state, 'required');

		// back to main list
		$this->cancel();

		return true;
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @param 	array  $query  An array of query arguments.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	public function cancel(array $query = array())
	{
		$url = 'index.php?option=com_vikappointments&view=empcustfields';

		if ($query)
		{
			$url .= '&' . http_build_query($query);
		}

		$this->setRedirect($url);
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 */
	public function saveOrderAjax()
	{
		$app  = JFactory::getApplication();
		$auth = VAPEmployeeAuth::getInstance();

		// get filters set in request
		$filters = $app->input->get('filters', array(), 'array');

		// register group alias within the filters array to apply
		// the rearrangement of the records only to those fields
		// that belong to the customers
		$filters['group'] = 0;

		// inject updated filters within the request
		$app->input->set('filters', $filters);

		// invoke parent to commit the new ordering
		parent::saveOrderAjax();
	}
}
