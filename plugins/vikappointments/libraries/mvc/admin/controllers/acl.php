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
 * VikAppointments plugin ACL controller.
 *
 * @since 1.0
 */
class VikAppointmentsControllerAcl extends VAPControllerAdmin
{
	/**
	 * Save and close task.
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
	 * Save (and stay) task.
	 *
	 * @return  boolean
	 */
	public function save($close = 0)
	{
		$app   = JFactory::getApplication();
		$input = $app->input;

		// make sure the user is authorised to change ACL
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikappointments'))
		{
			// back to main list, not authorised to create/edit records
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();
			return false;
		}

		$data = $input->get('acl', array(), 'array');

		if ($this->model->save($data))
		{
			$app->enqueueMessage(JText::translate('ACL_SAVE_SUCCESS'));
		}
		else
		{
			$app->enqueueMessage(JText::translate('ACL_SAVE_ERROR'), 'error');
		}

		$this->setRedirect('admin.php?page=vikappointments&view=acl&activerole=' . $active . '&return=' . $input->getBase64('return', ''));
	}

	/**
	 * Shortcodes back to list task.
	 *
	 * @return 	void
	 */
	public function cancel()
	{
		$return = JFactory::getApplication()->input->getBase64('return', '');

		if ($return)
		{
			$return = base64_decode($return);
		}

		$this->setRedirect($return);
	}
}
