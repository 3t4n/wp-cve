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
 * VikAppointments plugin Shortcodes controller.
 *
 * @since 1.0
 */
class VikAppointmentsControllerShortcodes extends VAPControllerAdmin
{
	/**
	 * Shortcodes create task.
	 *
	 * @return 	void
	 */
	public function add()
	{
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikappointments'))
		{
			wp_die(
				'<h1>' . JText::translate('FATAL_ERROR') . '</h1>' .
				'<p>' . JText::translate('RESOURCE_AUTH_ERROR') . '</p>',
				403
			);
		}

		$input = JFactory::getApplication()->input;

		$input->set('view', 'shortcode');

		parent::display();
	}

	/**
	 * Shortcodes edit task.
	 *
	 * @return 	void
	 */
	public function edit()
	{
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikappointments'))
		{
			wp_die(
				'<h1>' . JText::translate('FATAL_ERROR') . '</h1>' .
				'<p>' . JText::translate('RESOURCE_AUTH_ERROR') . '</p>',
				403
			);
		}

		$input = JFactory::getApplication()->input;

		$input->set('view', 'shortcode');

		parent::display();
	}

	/**
	 * Shortcodes delete task.
	 *
	 * @return 	void
	 */
	public function delete()
	{
		$input = JFactory::getApplication()->input;

		$cid 	 = $input->getUint('cid', array());
		$encoded = $input->getBase64('return', '');

		$this->model->delete($cid);

		$this->setRedirect('admin.php?option=com_vikappointments&view=shortcodes&return=' . $encoded);
	}

	/**
	 * Shortcodes back to list task.
	 *
	 * @return 	void
	 */
	public function cancel()
	{
		$encoded = JFactory::getApplication()->input->getBase64('return', '');

		$this->setRedirect('admin.php?option=com_vikappointments&view=shortcodes&return=' . $encoded);
	}

	/**
	 * Shortcodes back button task.
	 *
	 * @return 	void
	 */
	public function back()
	{
		$return = JFactory::getApplication()->input->getBase64('return', '');

		if ($return)
		{
			$return = base64_decode($return);
		}
		else
		{
			$return = 'admin.php?page=vikappointments';
		}

		$this->setRedirect($return);
	}
}
