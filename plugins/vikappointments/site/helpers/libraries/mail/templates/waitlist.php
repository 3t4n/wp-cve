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

VAPLoader::import('libraries.mail.template');

/**
 * Wrapper used to handle mail notifications for the
 * customers subscribed to waiting list.
 *
 * @since 1.7
 */
class VAPMailTemplateWaitlist implements VAPMailTemplate
{
	/**
	 * The appointment object.
	 * Extracted from VAPOrderAppointment instance.
	 *
	 * @var object
	 */
	protected $order;

	/**
	 * The waiting list record.
	 *
	 * @var object
	 */
	protected $record;

	/**
	 * The check-in date (military format)
	 *
	 * @var string
	 */
	protected $date;

	/**
	 * The cancelled check-in times (in minutes).
	 *
	 * @var array
	 */
	protected $times;

	/**
	 * An optional template file to use.
	 *
	 * @var string
	 */
	protected $templateFile;

	/**
	 * An array of options.
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Class constructor.
	 *
	 * @param 	object  $appointment  The appointment details object.
	 * @param 	array   $record       The waiting list record to notify.
	 * @param 	string  $date         The check-in date.
	 * @param 	array   $times        A list of check-in times.
	 * @param 	array   $options      An array of options.
	 */
	public function __construct($appointment, $record, $date, $times, array $options = array())
	{
		if (empty($options['lang']))
		{
			// use default site language
			$options['lang'] = VikAppointments::getDefaultLanguage();
		}

		// use appointment as provided
		$this->appointment = $appointment;

		// save waiting list record
		$this->record = (object) $record;

		// save check-in date
		$this->date = $date;

		// save check-in times
		$this->times = $times;

		// register options
		$this->options = $options;

		// load given language to translate template contents
		VikAppointments::loadLanguage($this->options['lang'], JPATH_SITE);
	}

	/**
	 * Returns the code of the template before being parsed.
	 *
	 * @param 	string  $file  An optional template file to use. If not specified,
	 *                         the one set in configuration will be used.
	 *
	 * @return 	void
	 */
	public function setFile($file)
	{
		// use specified template file
		$this->templateFile = $file;

		// check if a filename or a path was passed
		if ($file && !is_file($file))
		{
			// make sure we have a valid file path
			$this->templateFile = VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_tmpls' . DIRECTORY_SEPARATOR . $file;
		}
	}

	/**
	 * Returns the code of the template before being parsed.
	 *
	 * @return 	string
	 */
	public function getTemplate()
	{
		// copy appointment details in a local variable for being used directly
		// within the template file
		$appointment = $this->appointment;

		if ($this->templateFile)
		{
			// use specified template file
			$file = $this->templateFile;
		}
		else
		{
			// get template file from configuration
			$file = VAPFactory::getConfig()->get('waitlistmailtmpl');

			// build template path
			$file = VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_tmpls' . DIRECTORY_SEPARATOR . $file;
		}

		// make sure the file exists
		if (!is_file($file))
		{
			// missing file, return empty string
			return '';
		}

		// start output buffering 
		ob_start();
		// include file to catch its contents
		include $file;
		// write template contents within a variable
		$content = ob_get_contents();
		// clear output buffer
		ob_end_clean();

		// free space
		unset($order);

		return $content;
	}

	/**
	 * Fetches the subject to be used in the e-mail.
	 *
	 * @return 	string
	 */
	public function getSubject()
	{
		// get company name
		$fromname = VAPFactory::getConfig()->getString('agencyname');

		// fetch subject
		$subject = JText::sprintf('VAPWAITLISTEMAILSUBJECT', $fromname);

		// let plugins manipulate the subject for this e-mail template
		$res = VAPMailFactory::letPluginsManipulateMail('waitlist', 'subject', $subject, $this->appointment, $this->record, $this->date, $this->times);

		if ($res === false)
		{
			// a plugin prevented the e-mail sending
			return '';
		}

		return $subject;
	}

	/**
	 * Parses the HTML of the template and returns it.
	 *
	 * @return 	string
	 */
	public function getHtml()
	{
		$config = VAPFactory::getConfig();

		// load template HTML
		$tmpl = $this->getTemplate();

		if ($this->appointment->service->id == $this->record->id_service)
		{
			// the customer did a cancellation for the same service
			$service = $this->appointment->service;
		}
		else
		{
			// the customer did the cancellation for a different service,
			// we need to fetch the details of the service for which this
			// user subscribed into the waiting list
			$service = JModelVAP::getInstance('service')->getItem($this->record->id_service);

			if (!$service)
			{
				return false;
			}
		}

		// Format check-in date. Force UTC timezone because the date should be
		// displayed as it is.
		$formatted_date = JHtml::fetch('date', $this->date, JText::translate('DATE_FORMAT_LC1'), 'UTC');

		$formatted_times = array();

		foreach ($this->times as $time)
		{
			// convert minutes in time
			$formatted_times[] = JHtml::fetch('vikappointments.min2time', $time, true);
		}

		$vik = VAPApplication::getInstance();

		// create link to access the service details page
		$date = JHtml::fetch('date', $this->date, $config->get('dateformat'), 'UTC');
		$url  = "index.php?option=com_vikappointments&view=servicesearch&id_service={$service->id}&date={$date}";

		if ($this->appointment->viewEmp > 0)
		{
			// include employee ID too, if selectable
			$url .= '&id_emp=' . $this->appointment->employee->id;
		}

		$details_link_href = $vik->routeForExternalUse($url);

		// create link to unsubscribe from waiting list
		$unsubscribe_link_href = $vik->routeForExternalUse('index.php?option=com_vikappointments&view=unsubscr_waiting_list');

		// fetch company logo image
		$logo_str = $config->get('companylogo');

		if ($logo_str && is_file(VAPMEDIA . DIRECTORY_SEPARATOR . $logo_str))
		{
			$logo_str = JHtml::fetch('vaphtml.media.display', $logo_str, [
				'alt'   => $config->get('agencyname'),
				'small' => false,
			]);
		}
		else
		{
			$logo_str = '';
		}

		// build placeholders lookup
		$placeholders = array(
			'company_name'     => $config->get('agencyname'),
			'service'          => $service->name,
			'checkin_day'      => $formatted_date,
			'checkin_time'     => $formatted_times[0],
			'checkin_times'    => implode(', ', $formatted_times),
			'details_link'     => $details_link_href,
			'unsubscribe_link' => $unsubscribe_link_href,
			'logo'             => $logo_str,
		);

		// parse e-mail template placeholders
		foreach ($placeholders as $tag => $value)
		{
			$tmpl = str_replace("{{$tag}}", $value, $tmpl);
		}

		// let plugins manipulate the content for this e-mail template
		$res = VAPMailFactory::letPluginsManipulateMail('waitlist', 'content', $tmpl, $this->appointment, $this->record, $this->date, $this->times);

		if ($res === false)
		{
			// a plugin prevented the e-mail sending
			return '';
		}

		return $tmpl;
	}

	/**
	 * Sends the HTML contents via e-mail.
	 *
	 * @return 	boolean
	 */
	public function send()
	{
		$config = VAPFactory::getConfig();

		// get recipient from order detail
		$recipient = $this->record->email;

		if (!$recipient)
		{
			// missing recipient
			return false;
		}

		// get administrator e-mail
		$adminmail = VikAppointments::getAdminMail();
		// get sender e-mail address
		$sendermail = VikAppointments::getSenderMail();
		// get company name
		$fromname = $config->getString('agencyname');
		
		// fetch subject
		$subject = $this->getSubject();
			
		// parse e-mail template
		$html = $this->getHtml();

		if (empty($subject) || empty($html))
		{
			// do not send e-mail in case the subject or
			// the content are empty
			return false;
		}

		$attachments = array();

		// let plugins manipulate the attachments for this e-mail template
		$res = VAPMailFactory::letPluginsManipulateMail('waitlist', 'attachment', $attachments, $this->appointment, $this->record, $this->date, $this->times);
		
		// send the e-mail notification
		$sent = VAPApplication::getInstance()->sendMail(
			$sendermail,
			$fromname,
			$recipient,
			null,
			$subject,
			$html,
			array_keys($attachments)
		);

		// destroy any temporary attachment here
		foreach ($attachments as $file => $keep)
		{
			// check if the file exists and it should be deleted
			if (!$keep && is_file($file))
			{
				// permanently delete file
				unlink($file);
			}
		}

		return $sent;
	}

	/**
	 * Checks whether the notification should be sent.
	 *
	 * @return 	boolean
	 */
	public function shouldSend()
	{
		// should send only in case the e-mail was specified
		return !empty($this->record->email);
	}
}
