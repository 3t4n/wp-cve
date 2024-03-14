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
 * VikAppointments cron job model.
 *
 * @since 1.7
 */
class VikAppointmentsModelCronjob extends JModelVAP
{
	/**
	 * Method used to dispatch a cron job.
	 *
	 * @param 	integer  $id_cron     The ID of the cron to launch.
	 * @param 	string 	 $secure_key  The secure key to execute the command. Leave empty to
	 *                                ignore the validation.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function dispatch($id_cron, $secure_key = null)
	{
		$dbo    = JFactory::getDbo();
		$config = VAPFactory::getConfig();
		
		if (empty($id_cron))
		{
			$this->setError(new InvalidArgumentException('Missing CRON ID', 400));
			return false;
		}
		
		// validate secure key only in case the 2nd argument was passed
		if (!is_null($secure_key))
		{
			// match the specified secure key with the one stored in the configuration
			$match = md5($config->get('cron_secure_key'));
			
			if (strcmp($match, $secure_key))
			{
				$this->setError(new Exception('CRON secure key is not correct', 403));
				return false;
			}
		}

		// load cron job parameters
		$cron = $this->getItem((int) $id_cron);
		
		if (!$cron || !$cron->published)
		{
			$this->setError(new Exception(sprintf('Cron job [%d] not found', (int) $id_cron), 404));
			return false;
		}
		
		// dispatch cron job
		VikAppointments::loadCronLibrary();
		$job = VAPCronDispatcher::getJob($cron->class, $cron->id, $cron->params);
		
		if (!$job)
		{
			$this->setError(new Exception(sprintf('Cron job [%s] not executable', $cron->class), 500));
			return false;
		}
		
		// get response
		$response = $job->doJob();

		// prepare log data
		$log = array(
			'content'    => $response->getContent(),
			'status'     => (int) $response->isVerified(),
			'mailed'     => (int) $response->isNotify(),
			'id_cronjob' => $cron->id,
		);

		/**
		 * Fetch the preferences and check whether the e-mail notifications should be allowed or not.
		 * This because, in case the cron job executes very frequently, the system might send hundreds
		 * of error e-mails.
		 * 
		 * @since 1.7.4
		 */
		if ($response->isNotify() && JFactory::getDate($cron->resume_notif) > JFactory::getDate())
		{
			// notification still paused
			$log['mailed'] = 2;

			// prevent notification
			$response->setNotify(false);
		}

		// store log details
		JModelVAP::getInstance('cronjoblog')->save($log);
		
		// mail response
		if ($response->isNotify())
		{
			$vik = VAPApplication::getInstance();

			$sendermail 	 = VikAppointments::getSenderMail();
			$admin_mail_list = VikAppointments::getAdminMailList();

			// fetch e-mail subject
			$subject = JText::sprintf('VAPCRONJOBNOTIFYSUBJECT', $config->get('agencyname'));

			// include cron title
			$body = sprintf("<b>Cron #%d - %s (%s)</b>\n\n",
				$cron->id,
				$cron->name,
				$cron->class
			);

			// fetch e-mail body
			$body .= JText::sprintf(
				'VAPCRONJOBNOTIFYCONTENT', 
				JHtml::fetch('date', $response->getLastUpdate(), JText::translate('DATE_FORMAT_LC6')), 
				JText::translate('VAPCRONLOGSTATUS' . (int) $response->isVerified()),
				$response->getContent()
			);

			/**
			 * Add a link in the footer to temporarily pause the notifications.
			 * 
			 * @since 1.7.4 
			 */
			$pauseUri = $vik->routeForExternalUse('index.php?option=com_vikappointments&task=cron_pause_notif&id=' . $cron->id . '&key=' . $config->get('cron_secure_key', ''));
			$body .= '<br />' . JText::translate('VAPCRONJOBNOTIFYFOOTER') . '<br /><br /><a href="' . $pauseUri . '" target="_blank">' . $pauseUri . '</a>';

			// send notification to the administrators
			foreach ($admin_mail_list as $_m)
			{
				$vik->sendMail($sendermail, $sendermail, $_m, $_m, $subject, nl2br($body), $attachments = null, $is_html = true);
			}
		}

		return true;
	}

	/**
	 * Basic save implementation.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$data = (array) $data;

		$app = JFactory::getApplication();
		$job = null;

		// check whether the driver has been specified
		if (!empty($data['class']))
		{
			// load framework
			VikAppointments::loadCronLibrary();
			// get cron job
			$job = VAPCronDispatcher::getJob($data['class'], $data['id']);

			if (!$job)
			{
				// invalid cron job
				$this->setError(JText::translate('VAPCRONJOBERROR1'));

				return false;
			}

			$data['params'] = array();

			$input = $app->input;

			// load configuration
			foreach ($job->getConfiguration() as $f)
			{
				$name = $f->getName();

				if (in_array($f->getType(), [VAPCronFormField::TEXTAREA, VAPCronFormField::EDITOR]))
				{
					// use raw filter
					$filter = 'raw';
				}
				else
				{
					// use string filter
					$filter = 'string';
				}

				// get parameter from request
				$data['params'][$name] = $input->get('cronform_' . $name, '', $filter);

				if ($filter == 'raw')
				{
					// sanitize HTML
					$data['params'][$name] = JComponentHelper::filterText($data['params'][$name]);
				}
				
				// validate field if required
				if ($f->isRequired() && empty($data['params'][$name]))
				{
					// register error message
					$this->setError(JText::sprintf('VAP_MISSING_REQ_FIELD', $f->getLabel()));

					// unsafe record
					return false;
				}
			}
		}

		// attempt to save the cron job
		$id = parent::save($data);

		if (!$id)
		{
			// an error occurred
			return false;
		}

		// check whether we created a new cron job
		if (empty($data['id']))
		{
			// update JOB ID
			$job->setID($id);

			// perform installation
			if ($job->install())
			{
				// register successful message
				$app->enqueueMessage(JText::sprintf('VAPCRONJOBINSTALLED1', $data['class']));
			}
			else
			{
				// register error message
				$this->setError(JText::sprintf('VAPCRONJOBINSTALLED0', $data['class']));
			}
		}

		return $id;
	}

	/**
	 * Extend delete implementation to delete any related records
	 * stored within a separated table.
	 *
	 * @param   mixed    $ids  Either the record ID or a list of records.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 */
	public function delete($ids)
	{
		// only int values are accepted
		$ids = array_map('intval', (array) $ids);

		VikAppointments::loadCronLibrary();

		$app = JFactory::getApplication();
		$dbo = JFactory::getDbo();

		if ($ids)
		{
			// get cron jobs classes
			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'class')))
				->from($dbo->qn('#__vikappointments_cronjob'))
				->where($dbo->qn('id') . ' IN (' . implode(',', $ids) . ')');

			$dbo->setQuery($q);
			
			foreach ($dbo->loadObjectList() as $obj)
			{
				// create cron job instance
				$job = VAPCronDispatcher::getJob($obj->class, $obj->id);
				
				// uninstall cron job
				if ($job && $job->uninstall())
				{
					// register successful message
					$app->enqueueMessage(JText::sprintf('VAPCRONJOBUNINSTALLED1', $obj->class));
				}
				else
				{
					// register error message
					$this->setError(JText::sprintf('VAPCRONJOBUNINSTALLED0', $obj->class));
				}
			}
		}

		// invoke parent first
		if (!parent::delete($ids))
		{
			// nothing to delete
			return false;
		}

		// load any children logs
		$q = $dbo->getQuery(true)
			->select($dbo->qn('id'))
			->from($dbo->qn('#__vikappointments_cronjob_log'))
			->where($dbo->qn('id_cronjob') . ' IN (' . implode(',', $ids) . ')' );

		$dbo->setQuery($q);

		if ($log_ids = $dbo->loadColumn())
		{
			// get log model
			$model = JModelVAP::getInstance('cronjoblog');
			// delete children
			$model->delete($log_ids);
		}

		return true;
	}

	/**
	 * Pauses the notifications for the specified cron job for the
	 * number of given days.
	 * 
	 * @param   int  $cronId  The ID of the cron to pause.
	 * @param   int  $days    The number of days since now.
	 * 
	 * @return  JDate|false   The date when the pause will end on success,
	 *                        false otherwise.
	 * 
	 * @since   1.7.4
	 */
	public function pauseNotifications(int $cronId, int $days)
	{
		// create threshold date
		$threshold = JFactory::getDate('+' . abs($days) . ' days');

		// attempt to save the cron job
		$result = $this->save([
			'id'           => $cronId,
			'resume_notif' => $threshold->toSql(),
		]);

		if (!$result)
		{
			return false;
		}

		// return the date when the notifications will be resumed
		return $threshold;
	}
}
