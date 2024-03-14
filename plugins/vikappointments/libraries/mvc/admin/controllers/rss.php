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
 * VikAppointments plugin RSS controller.
 *
 * @since 1.1.9
 */
class VikAppointmentsControllerRss extends VAPControllerAdmin
{
	/**
	 * Sets the opt-in status for the RSS service of the user.
	 *
	 * @return 	void
	 */
	public function optin()
	{
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikappointments'))
		{
			// not authorised to view this resource
			throw new Exception(JText::translate('RESOURCE_AUTH_ERROR'), 403);
		}

		$input = JFactory::getApplication()->input;

		// get opt-in status
		$status = $input->getBool('status', false);
		
		// get RSS instance
		$rss = VikAppointmentsBuilder::setupRssReader();

		// update opt-in status
		$rss->optIn($status);

		if (wp_doing_ajax())
		{
			wp_die();
		}

		// back to the dashboard
		$this->setRedirect('admin.php?page=vikappointments');
	}

	/**
	 * Dismesses the specified RSS feed.
	 *
	 * @return 	void
	 */
	public function dismiss()
	{
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikappointments'))
		{
			// not authorised to view this resource
			throw new Exception(JText::translate('RESOURCE_AUTH_ERROR'), 403);
		}

		$input = JFactory::getApplication()->input;

		// get ID of the feed to dismiss
		$id = $input->getString('id', '');

		if (!$id)
		{
			// make sure the feed ID is set
			throw new Exception('Missing feed ID', 400);
		}

		JLoader::import('adapter.rss.feed');
		
		// get RSS feed instance
		$feed = new JRssFeed(array('id' => $id), 'vikappointments');

		// dismiss the feed for this user
		$feed->dismiss();

		if (wp_doing_ajax())
		{
			wp_die();	
		}

		// back to the dashboard
		$this->setRedirect('admin.php?page=vikappointments');
	}

	/**
	 * Delays the specified RSS feed.
	 *
	 * @return 	void
	 */
	public function remind()
	{
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikappointments'))
		{
			// not authorised to view this resource
			throw new Exception(JText::translate('RESOURCE_AUTH_ERROR'), 403);
		}

		$input = JFactory::getApplication()->input;

		// get ID of the feed to dismiss
		$id = $input->getString('id', '');

		if (!$id)
		{
			// make sure the feed ID is set
			throw new Exception('Missing feed ID', 400);
		}

		// get specified delay
		$delay = $input->getUint('delay', 60);

		JLoader::import('adapter.rss.feed');
		
		// get RSS feed instance
		$feed = new JRssFeed(array('id' => $id), 'vikappointments');

		// delay the feed for this user
		$feed->delay($delay);

		if (wp_doing_ajax())
		{
			wp_die();	
		}

		// back to the dashboard
		$this->setRedirect('admin.php?page=vikappointments');
	}
}
