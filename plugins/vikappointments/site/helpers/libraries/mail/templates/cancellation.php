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
VAPLoader::import('libraries.order.factory');

/**
 * Wrapper used to handle mail notifications for the
 * administrators and employees after a cancellation.
 *
 * @since 1.7
 */
class VAPMailTemplateCancellation implements VAPMailTemplate
{
	/**
	 * The order object.
	 *
	 * @var VAPOrderAppointment
	 */
	protected $order;

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
	 * @param 	mixed   $order    Either the order ID or the order object.
	 * @param 	array   $options  An array of options.
	 */
	public function __construct($order, array $options = array())
	{
		if (empty($options['lang']))
		{
			// always use default language in case it is not specified
			$options['lang'] = VikAppointments::getDefaultLanguage();
		}

		if (is_numeric($order))
		{
			// recover order details for the given language
			$this->order = VAPOrderFactory::getAppointments($order, $options['lang']);
		}
		else
		{
			// use order as provided
			$this->order = $order;
		}

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
	 * @param 	string 	 $who  The entity to check (admin or employee).
	 *
	 * @return 	string
	 */
	public function getTemplate($who = 'admin')
	{
		// copy order details in a local variable for being used directly
		// within the template file
		$order = $this->order;

		if (!$this->templateFile)
		{
			// get template file from configuration
			$file = VAPFactory::getConfig()->get('cancmailtmpl');

			// build template path
			$this->templateFile = VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_tmpls' . DIRECTORY_SEPARATOR . $file;
		}

		// make sure the file exists
		if (!is_file($this->templateFile))
		{
			// missing file, return empty string
			return '';
		}

		// start output buffering 
		ob_start();
		// include file to catch its contents
		include $this->templateFile;
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
		$subject = JText::sprintf('VAPORDERCANCELEDSUBJECT', $fromname);

		// let plugins manipulate the subject for this e-mail template
		$res = VAPMailFactory::letPluginsManipulateMail('cancellation', 'subject', $subject, $this->order);

		if ($res === false)
		{
			// a plugin prevented the e-mail sending
			return '';
		}

		/**
		 * Parse e-mail subject to replace tags with the related order details.
		 *
		 * @since 1.6.6
		 */
		VikAppointments::parseEmailSubject($subject, $this->order);

		return $subject;
	}

	/**
	 * Parses the HTML of the template and returns it.
	 *
	 * @param 	string 	 $who  The entity to check (admin or employee).
	 *
	 * @return 	string
	 */
	public function getHtml($who = 'admin')
	{
		$config   = VAPFactory::getConfig();
		$currency = VAPFactory::getCurrency();

		// load template HTML
		$tmpl = $this->getTemplate($who);

		// fetch payment name
		if ($this->order->payment)
		{
			// use payment name
			$payment_name = $this->order->payment->name;
		}
		else
		{
			$payment_name = '';
		}

		// fetch cancellation content
		if ($who == 'admin')
		{
			$cancellation_content = JText::translate('VAPORDERCANCELEDCONTENT');
		}
		else
		{
			$cancellation_content = JText::translate('VAPORDERCANCELEDCONTENTEMP');
		}

		$vik = VAPApplication::getInstance();

		// fetch order link HREF
		$order_link_href = "index.php?option=com_vikappointments&view=order&ordnum={$this->order->id}&ordkey={$this->order->sid}";
		$order_link_href = $vik->routeForExternalUse($order_link_href);

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

		// get name chunks
		$customerNameChunks = preg_split("/\s+/", (string) $this->order->purchaser_nominative);

		// extract last name from the list
		$customerLastName = array_pop($customerNameChunks);
		// join remaining chunks into the first name
		$customerFirstName = implode(' ', $customerNameChunks);

		if (!$customerFirstName)
		{
			// only one name provided, use it as first name
			$customerFirstName = $customerLastName;
			$customerLastName  = '';
		}

		// build placeholders lookup
		$placeholders = array(
			'logo'                 => $logo_str,
			'company_name'         => $config->get('agencyname'),
			'cancellation_content' => $cancellation_content,
			'order_payment'        => $payment_name,
			'order_total_cost'     => $currency->format($this->order->totals->gross),
			'order_total_net'      => $currency->format($this->order->totals->net),
			'order_total_tax'      => $currency->format($this->order->totals->tax),
			'order_total_discount' => $currency->format($this->order->totals->discount),
			'order_link'           => $order_link_href,
			'user_name'            => $this->order->author ? $this->order->author->name : '',
			'user_username'        => $this->order->author ? $this->order->author->username : '',
			'user_email'           => $this->order->author ? $this->order->author->email : '',
			'customer_full_name'   => $this->order->purchaser_nominative,
			'customer_first_name'  => $customerFirstName,
			'customer_last_name'   => $customerLastName,
		);

		// get e-mail custom text model
		$model = JModelVAP::getInstance('mailtext');

		// set up options
		$options = $this->options;
		$options['file'] = $this->templateFile;

		// parse e-mail template
		$tmpl = $model->parseTemplate($tmpl, $this->order, $options);

		// parse e-mail template placeholders
		foreach ($placeholders as $tag => $value)
		{
			$tmpl = str_replace("{{$tag}}", $value, $tmpl);
		}

		// let plugins manipulate the content for this e-mail template
		$res = VAPMailFactory::letPluginsManipulateMail('cancellation', 'content', $tmpl, $this->order);

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

		// get administrators e-mail
		$adminmails = VikAppointments::getAdminMailList();
		// get sender e-mail address
		$sendermail = VikAppointments::getSenderMail();
		// get company name
		$fromname = $config->getString('agencyname');
		
		// fetch subject
		$subject = $this->getSubject();

		if (empty($subject))
		{
			// do not send e-mail in case the subject or is empty
			return false;
		}

		$attachments = array();

		// let plugins manipulate the attachments for this e-mail template
		$res = VAPMailFactory::letPluginsManipulateMail('cancellation', 'attachment', $attachments, $this->order);

		if ($res === false)
		{
			// a plugin prevented the e-mail sending
			return false;
		}

		$sent = false;
		
		if ($this->shouldSend('admin'))
		{
			// parse e-mail template
			$html = $this->getHtml('admin');

			if ($html)
			{
				foreach ($adminmails as $recipient)
				{
					// send the e-mail notification
					$sent = VAPApplication::getInstance()->sendMail(
						$sendermail,
						$fromname,
						$recipient,
						$this->order->purchaser_mail,
						$subject,
						$html,
						array_keys($attachments)
					) || $sent;
				}
			}
		}

		// send the e-mail notification to the employee only
		// in case the order doesn't own several appointments
		// assigned to different employees
		if ($this->order->sameEmp && $this->shouldSend('employee'))
		{
			// parse e-mail template
			$html = $this->getHtml('employee');

			if ($html)
			{
				$sent = VAPApplication::getInstance()->sendMail(
					$sendermail,
					$fromname,
					$this->order->appointments[0]->employee->email,
					$this->order->purchaser_mail,
					$subject,
					$html,
					array_keys($attachments)
				) || $sent;
			}
		}

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
	 * @param 	string 	 $who  The entity to check (admin or employee).
	 *
	 * @return 	boolean
	 */
	public function shouldSend($who = null)
	{
		if (!is_null($who))
		{
			// fetch configuration key
			$key = $who == 'admin' ? 'mailadminwhen' : 'mailempwhen';

			// get list of statuses for which the notification should be sent
			$list = VAPFactory::getConfig()->getArray('mailadminwhen');

			// make sure the order status is contained within the list
			return in_array($this->order->status, $list);
		}
		
		// Recursively check both the entities in case of no
		// specified parameter. At least one of them should be ok.
		return $this->shouldSend('admin') || $this->shouldSend('employee');
	}
}
