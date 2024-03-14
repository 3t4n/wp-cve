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
 * VikAppointments plugin Feedback controller.
 *
 * @since 1.1.2
 */
class VikAppointmentsControllerFeedback extends VAPControllerAdmin
{
	/**
	 * Submits a feedback to VikWP servers after deactivating the plugin.
	 *
	 * @return 	void
	 */
	public function submit()
	{
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikappointments'))
		{
			// not authorised to view this resource
			throw new Exception(JText::translate('RESOURCE_AUTH_ERROR'), 403);
		}
		
		$input = JFactory::getApplication()->input;

		// validation end-points
		$url = 'https://vikwp.com/api/?task=logs.track';

		$version = new JVersion();

		$env = array(
			'ipaddr'  => $input->server->getString('REMOTE_ADDR'),
			'wpver'   => $version->getLongVersion(),
			'version' => VIKAPPOINTMENTS_SOFTWARE_VERSION,
			'phpver'  => phpversion(),
		);

		$body = print_r($env, true);

		$notes = $input->getString('notes');

		if ($notes)
		{
			$body = $notes . "\n\n" . $body;
		}

		// init HTTP transport
		$http = new JHttp();

		// build post data
		$data = array(
			'type' => 'feedback.vikappointments',
			'desc' => $input->getString('type'),
			'body' => $body,
		);

		// build headers
		$headers = array(
			/**
			 * Always bypass SSL validation while reaching our end-point.
			 *
			 * @since 1.2
			 */
			'sslverify' => false,
		);

		// make connection with VikWP server
		$response = $http->post($url, $data, $headers);

		if ($response->code != 200)
		{
			// raise error returned by VikWP
			throw new Exception($response->body, $response->code);
		}
		
		echo $response->body;
	}

	/**
	 * Submits a survey to VikWP servers.
	 *
	 * @return 	void
	 */
	public function survey()
	{
		if (!JFactory::getUser()->authorise('core.admin', 'com_vikappointments'))
		{
			// not authorised to view this resource
			throw new Exception(JText::translate('RESOURCE_AUTH_ERROR'), 403);
		}
		
		$input = JFactory::getApplication()->input;

		// validation end-points
		$url = 'https://vikwp.com/api/?task=logs.track';

		$version = new JVersion();

		$env = array(
			'wpver'   => $version->getLongVersion(),
			'version' => VIKAPPOINTMENTS_SOFTWARE_VERSION,
			'phpver'  => phpversion(),
		);

		// include environment details
		$body = print_r($env, true);

		// get form from request
		$form = $input->get('survey', array(), 'array');

		// filter form to exclude empty data, then reset keys
		$form = array_values(array_filter($form));

		if (!$form)
		{
			// the survey doesn't contain data
			throw new Exception('Empty survey', 400);
		}

		// map array to indent new lines
		$form = array_map(function($str)
		{
			// add 2 white spaces after every new line
			return preg_replace("/\R/", "\n  ", $str);
		}, $form);

		// prepend survey
		$body = '* ' . implode("\n* ", $form) . "\n\n" . $body;

		// retrieve subject from request
		$subject = $input->get('subject', 'Survey', 'string');

		// init HTTP transport
		$http = new JHttp();

		// build post data
		$data = array(
			'type' => 'survey.vikappointments',
			'desc' => $subject,
			'body' => $body,
		);

		// build headers
		$headers = array(
			/**
			 * Always bypass SSL validation while reaching our end-point.
			 *
			 * @since 1.2
			 */
			'sslverify' => false,
		);

		// make connection with VikWP server
		$response = $http->post($url, $data, $headers);

		if ($response->code != 200)
		{
			// raise error returned by VikWP
			throw new Exception($response->body, $response->code);
		}
		
		echo $response->body;
	}
}
