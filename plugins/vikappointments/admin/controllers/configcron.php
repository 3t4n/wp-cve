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
 * VikAppointments cron jobs configuration controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerConfigcron extends VAPControllerAdmin
{
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

		// check user permissions
		if (!$user->authorise('core.access.config', 'com_vikappointments'))
		{
			// back to main list, not authorised to access the configuration
			$app->enqueueMessage(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
			$this->cancel();

			return false;
		}
		
		$args = array();
		
		////////////////////////////////////////////////////
		///////////////////// SETTINGS /////////////////////
		////////////////////////////////////////////////////

		$args['cron_secure_key'] = $input->getString('cron_secure_key', '');
		$args['cron_log_mode']   = $input->getUint('cron_log_mode', 1);
		$args['cron_log_flush']  = $input->getUint('cron_log_flush', 0);

		////////////////////////////////////////////////////

		// get configuration model
		$config = $this->getModel();

		// Save all configuration.
		// Do not care of any errors.
		$changed = $config->saveAll($args);

		if ($changed)
		{
			// display generic successful message
			$app->enqueueMessage(JText::translate('JLIB_APPLICATION_SAVE_SUCCESS'));
		}

		// redirect to configuration page
		$this->cancel();

		return true;
	}

	/**
	 * Redirects the users to the main records list.
	 *
	 * @return 	void
	 */
	public function cancel()
	{
		$this->setRedirect('index.php?option=com_vikappointments&view=editconfigcron');
	}

	/**
	 * Task used to download the installation file of the specified cron job.
	 *
	 * @return 	void
	 */
	function downloadInstallationFile()
	{
		$app = JFactory::getApplication();
		$vik = VAPApplication::getInstance();

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

		// get CRON ID from request
		$id_cron = $app->input->getUint('id_cron');
		// get secure key from config
		$key = VAPFactory::getConfig()->get('cron_secure_key');

		// build loopback query string
		$url = 'index.php?option=com_vikappointments&task=cronjob_listener_rq';
		// create full URI
		$url = $vik->routeForExternalUse($url, false);

		/**
		 * The code of the cron job now escapes the internal variables properly.
		 *
		 * @since 1.6.2
		 */

		$php_cron_code = "<?php

define(\"ID_CRON\", {$id_cron});
define(\"SECURE_KEY\", \"{$key}\");

// make sure the domain is correct
\$url = \"{$url}\";

\$fields = array(
	\"id_cron\"    => ID_CRON,
	\"secure_key\" => md5(SECURE_KEY),
);

\$ch = curl_init();
curl_setopt(\$ch, CURLOPT_URL, \$url);
curl_setopt(\$ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt(\$ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt(\$ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt(\$ch, CURLOPT_TIMEOUT, 20);
curl_setopt(\$ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt(\$ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt(\$ch, CURLOPT_POST, count(\$fields));
curl_setopt(\$ch, CURLOPT_POSTFIELDS, http_build_query(\$fields));

\$output = curl_exec(\$ch);

curl_close(\$ch);

echo \$output;";
		
		// configure headers
		$app->setHeader('Content-Disposition', 'attachment; filename="cron_runnable.php"');
		$app->setHeader('Content-Type', 'text/php');
		$app->sendHeaders();

		// download file
		echo $php_cron_code;
		
		$app->close();
	}
}
