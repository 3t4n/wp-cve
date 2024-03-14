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
 * VikAppointments plugin Shortcode controller.
 *
 * @since 1.0
 */
class VikAppointmentsControllerShortcode extends VAPControllerAdmin
{
	/**
	 * Save new record task.
	 *
	 * @return 	void
	 */
	public function savenew()
	{
		if ($this->save())
		{
			// get return URL
			$encoded = JFactory::getApplication()->input->getBase64('return', '');

			$this->setRedirect('admin.php?page=vikappointments&task=shortcodes.add&return=' . $encoded);
		}
	}

	/**
	 * Save and close task.
	 *
	 * @return 	void
	 */
	public function saveclose()
	{
		if ($this->save())
		{
			// get return URL
			$encoded = JFactory::getApplication()->input->getBase64('return', '');

			$this->setRedirect('admin.php?page=vikappointments&view=shortcodes&return=' . $encoded);
		}
	}

	/**
	 * Save (and stay) task.
	 *
	 * @return  boolean
	 */
	public function save($close = 0)
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		// get return URL
		$encoded = $input->getBase64('return', '');

		// make sure the user is authorised to change shortcodes
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();
			return false;
		}

		// get item from request
		$data = $this->model->getFormData();

		// dispatch model to save the item
		$id = $this->model->save($data);
		
		if (!$id)
		{
			// get string error
			$error = $city->getError(null, true);

			// display error message
			$app->enqueueMessage(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $error), 'error');

			$url = 'admin.php?page=vikappointments&view=shortcode';

			if ($data->id)
			{
				$url .= '&cid[]=' . $data->id;
			}

			// redirect to new/edit page
			$this->setRedirect($url);
				
			return false;
		}

		// display generic successful message
		$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));

		$this->setRedirect('admin.php?page=vikappointments&task=shortcodes.edit&cid[]=' . $id . '&return=' . $encoded);

		return true;
	}

	/**
	 * AJAX end-point used to access the parameters of the shortcodes.
	 *
	 * @return 	void
	 */
	public function params()
	{
		$input = JFactory::getApplication()->input;

		$id   = $input->getInt('id', 0);
		$type = $input->getString('type', '');

		$model = $this->getModel();

		// dispatch model to get the item (an empty ITEM if not exists)
		$item = $this->model->getItem($id);

		// inject the type to load the right form
		$item->type = $type;

		// obtain the type form
		$form = $this->model->getTypeForm($item);

		// if the form doesn't exist, the type is probably empty
		if (!$form)
		{
			// return an empty HTML
			$json = "";
		}
		// render the form and encode the response
		else
		{
			$args = json_decode($item->json);
			$json = json_encode($form->renderForm($args));
		}
		
		$this->sendJSON($json);
	}

	/**
	 * Creates a page on WordPress with the requested Shortcode inside it.
	 * This is useful to automatically link Shortcodes in pages with no manual actions.
	 *
	 * @return 	void
	 *
	 * @since 	1.1.9
	 */
	public function addpage()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		// get return URL
		$encoded = $input->getBase64('return', '');

		// always back to shortcodes list
		$this->setRedirect('admin.php?page=vikappointments&view=shortcodes' . ($encoded ? '&return=' . $encoded : ''));

		// make sure the user is authorised to change shortcodes
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikappointments'))
		{
			return;
		}

		// get selected shortcodes
		$cid = $input->getUint('cid', array());

		// attempt to assign the shortcodes to a page
		if ($this->model->addPage($cid))
		{
			// add success message and redirect
			$app->enqueueMessage(JText::translate('VAP_SHORTCODE_CREATE_PAGE_SUCCESS'));
		}

		// fetch all registered errors (if any)
		$errors = $this->model->getErrors();

		foreach ($errors as $error)
		{
			if ($error instanceof Exception)
			{
				$error = $error->getMessage();
			}

			// enqueue error message
			$app->enqueueMessage($error, 'error');
		}
	}
}
