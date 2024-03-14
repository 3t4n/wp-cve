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
 * customers that purchase a package.
 *
 * @since 1.7
 */
class VAPMailTemplatePackage implements VAPMailTemplate
{
	/**
	 * The order object.
	 *
	 * @var VAPOrderPackage
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
			$options['lang'] = null;
		}

		if (is_numeric($order))
		{
			// recover order details for the given language
			$this->order = VAPOrderFactory::getPackages($order, $options['lang']);
		}
		else
		{
			// use order as provided
			$this->order = $order;
		}

		if (!$options['lang'])
		{
			// use order lang tag in case it was not specified
			$options['lang'] = $this->order->get('langtag', null);

			if (!$options['lang'])
			{
				// the order is not assigned to any lang tag, use the current one
				$options['lang'] = JFactory::getLanguage()->getTag();
			}
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
	 * @return 	string
	 */
	public function getTemplate()
	{
		// copy order details in a local variable for being used directly
		// within the template file
		$order = $this->order;

		if ($this->templateFile)
		{
			// use specified template file
			$file = $this->templateFile;
		}
		else
		{
			// get template file from configuration
			$file = VAPFactory::getConfig()->get('packmailtmpl');

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
		$subject = JText::sprintf('VAPPACKAGESEMAILSUBJECT', $fromname);

		// let plugins manipulate the subject for this e-mail template
		$res = VAPMailFactory::letPluginsManipulateMail('package', 'subject', $subject, $this->order);

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
		$config   = VAPFactory::getConfig();
		$currency = VAPFactory::getCurrency();

		// load template HTML
		$tmpl = $this->getTemplate();

		$payment_name  = '';
		$payment_notes = '';

		// fetch payment name and notes
		if ($this->order->payment)
		{
			// use payment name
			$payment_name = $this->order->payment->name;

			if ($this->order->statusRole == 'PENDING')
			{
				// show notes before purchase when waiting for the payment
				$payment_notes = $this->order->payment->notes->beforePurchase;
			}
			else if ($this->order->statusRole == 'APPROVED')
			{
				// show notes after purchase when the order has been confirmed
				$payment_notes = $this->order->payment->notes->afterPurchase;	
			}
		}

		// fetch coupon string
		if ($this->order->coupon)
		{
			$coupon_str = $this->order->coupon->code;

			if ($this->order->coupon->amount > 0)
			{
				$coupon_str .= ' : ';

				if ($this->order->coupon->type == 1)
				{
					$coupon_str .= $this->order->coupon->amount . '%';
				}
				else
				{
					$coupon_str .= $currency->format($this->order->coupon->amount);
				}
			}
		}
		else
		{
			$coupon_str = '';
		}

		$vik = VAPApplication::getInstance();

		// fetch order link HREF
		$order_link_href = "index.php?option=com_vikappointments&view=packagesorder&ordnum={$this->order->id}&ordkey={$this->order->sid}";
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
			'order_number'         => $this->order->id,
			'order_key'            => $this->order->sid,
			'order_status'         => JHtml::fetch('vaphtml.status.display', $this->order->status),
			'order_payment'        => $payment_name,
			'order_payment_notes'  => $payment_notes,
			'order_total_cost'     => $currency->format($this->order->totals->gross),
			'order_total_net'      => $currency->format($this->order->totals->net),
			'order_total_tax'      => $currency->format($this->order->totals->tax),
			'order_total_discount' => $currency->format($this->order->totals->discount),
			'order_coupon_code'    => $coupon_str,
			'order_link'           => $order_link_href,
			'user_name'            => $this->order->author ? $this->order->author->name : '',
			'user_username'        => $this->order->author ? $this->order->author->username : '',
			'user_email'           => $this->order->author ? $this->order->author->email : '',
			'customer_full_name'   => $this->order->purchaser_nominative,
			'customer_first_name'  => $customerFirstName,
			'customer_last_name'   => $customerLastName,
		);

		// parse e-mail template placeholders
		foreach ($placeholders as $tag => $value)
		{
			$tmpl = str_replace("{{$tag}}", $value, $tmpl);
		}

		// let plugins manipulate the content for this e-mail template
		$res = VAPMailFactory::letPluginsManipulateMail('package', 'content', $tmpl, $this->order);

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
		$recipient = $this->order->purchaser_mail;

		if (!$recipient)
		{
			// missing recipient
			return false;
		}

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
		$res = VAPMailFactory::letPluginsManipulateMail('package', 'attachment', $attachments, $this->order);

		if ($res === false)
		{
			// a plugin prevented the e-mail sending
			return false;
		}
		
		// send the e-mail notification
		$sent = VAPApplication::getInstance()->sendMail(
			$sendermail,
			$fromname,
			$recipient,
			$this->getReplyTo(),
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
		// get list of statuses for which the notification should be sent
		$list = VAPFactory::getConfig()->getArray('mailcustwhen');

		// make sure the order status is contained within the list
		return in_array($this->order->status, $list);
	}

	/**
	 * Helper function used to obtain the most suitable reply-to mail address.
	 *
	 * @return 	string|null
	 */
	protected function getReplyTo()
	{
		if ($this->order->statusRole === 'APPROVED')
		{
			// use the first administrator e-mail as reply-to for confirmed orders
			return VikAppointments::getAdminMail();
		}
		
		return null;
	}
}
