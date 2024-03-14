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

VAPLoader::import('libraries.invoice.invoice');
VAPLoader::import('libraries.invoice.constraints');

/**
 * Invoices generator class.
 *
 * @since 	1.7
 */
class VAPInvoiceGenerator
{
	/**
	 * The invoice instance.
	 *
	 * @var VAPInvoice
	 */
	protected $invoice;

	/**
	 * The invoice parameters and settings.
	 *
	 * @var object
	 */
	protected $data;

	/**
	 * Class constructor.
	 *
	 * @param 	VAPInvoice  $invoice  The invoice instance to generate. If not
	 *                                specified, it will be possible to set it
	 *                                in a second time.
	 * @param 	object      $data     An object containing the invoice arguments.
	 *                                Leave empty to autoload them.
	 */
	public function __construct(VAPInvoice $invoice = null, $data = null)
	{
		if ($data)
		{
			$this->data = $data;
		}
		else
		{
			// loads internal data
			$this->load();
		}

		if ($invoice)
		{
			$this->setInvoice($invoice);
		}
	}

	/**
	 * Loads the invoice settings.
	 *
	 * @return 	self  This object to support chaining.
	 */
	protected function load()
	{
		$data = VAPFactory::getConfig()->getObject('invoiceobj', null);

		if (!isset($data->params) || !is_object($data->params) || !get_object_vars($data->params))
		{
			// get system timezone
			$tz = JFactory::getApplication()->get('offset', 'UTC');

			$this->data = new stdClass;

			// create parameters for the first time
			$this->data->params = new stdClass;
			$this->data->params->number      = 1;
			$this->data->params->suffix      = (int) JHtml::fetch('date', 'now', 'Y', $tz);
			$this->data->params->datetype    = 1; // 1: today, 2: booking date, 3: check-in date
			$this->data->params->date        = null;
			$this->data->params->legalinfo   = '';
			$this->data->params->sendinvoice = 0;

			$this->data->constraints = null;
		}
		else
		{
			$this->data = $data;

			// always unset last stored date
			$this->data->params->date = null;
		}

		// check if the constraints was set in the stored JSON
		if (!isset($this->data->constraints) || !is_object($this->data->constraints))
		{
			// no constraints, use empty array to load default settings
			$this->data->constraints = array();
		}

		// create new constraints instance with stored data
		$this->data->constraints = new VAPInvoiceConstraints($this->data->constraints);

		return $this;
	}

	/**
	 * Sets the invoice into the internal state.
	 *
	 * @param 	VAPInvoice  $invoice  The invoice instance.
	 *
	 * @return 	self        This object to support chaining.
	 */
	public function setInvoice(VAPInvoice $invoice)
	{
		// set invoice and force it to use our internal parameters
		$this->invoice = $invoice;
		$this->invoice->setParams($this->data);

		return $this;
	}

	/**
	 * Returns an array containing the invoice arguments.
	 *
	 * @return 	object 	The invoice arguments.
	 */
	public function getParams()
	{
		return $this->data->params;
	}

	/**
	 * Overwrites the invoice parameters.
	 *
	 * @param 	object  $params  The parameters to set.
	 *
	 * @return 	self    This object to support chaining.
	 */
	public function setParams($params)
	{
		$params = (object) $params;

		foreach ($params as $k => $v)
		{
			if (property_exists($this->data->params, $k))
			{
				$this->data->params->{$k} = $v;
			}
		}

		if (!empty($params->inv_number))
		{
			list($this->data->params->number, $this->data->params->suffix) = explode('/', $params->inv_number);
		}

		return $this;
	}
	
	/**
	 * Returns an object containing the invoice properties.
	 *
	 * @return 	object 	The invoice properties.
	 */
	public function getConstraints()
	{
		return $this->data->constraints;
	}

	/**
	 * Overwrites the invoice constraints.
	 *
	 * @param 	object  $settings  The constraints to set.
	 *
	 * @return 	self    This object to support chaining.
	 */
	public function setConstraints($settings)
	{
		// DO NOT cast the settings to array/object because
		// the VAPInvoiceConstraints might be passed.
		// In that case, an iterator should be returned.

		foreach ($settings as $k => $v)
		{
			$this->data->constraints->{$k} = $v;
		}

		return $this;
	}

	/**
	 * Returns an object containing the invoice parameters and constraints.
	 *
	 * @param 	boolean  $array  True to return the data as array.
	 *
	 * @return 	mixed
	 */
	public function getData($array = false)
	{
		if (!$array)
		{
			return clone $this->data;
		}
		else
		{
			$data = array(
				'params'      => (array) $this->data->params,
				'constraints' => $this->data->constraints->toArray(),
			);
		}

		return $data;
	}

	/**
	 * Generates the invoices related to the specified order.
	 *
	 * @param 	boolean  $increase  True to increase the invoice number
	 *                              by one step after generation.
	 *
	 * @return 	mixed 	 The invoice array data on success, otherwise false.
	 */
	public function generate($increase = true)
	{
		if (!$this->invoice)
		{
			// invoice not yet set
			return false;
		}

		// get current language tag
		$lang = JFactory::getLanguage()->getTag();

		// always load the template by using the default language of the website
		VikAppointments::loadLanguage(VikAppointments::getDefaultLanguage(), JPATH_SITE);

		// generate PDF
		$path = $this->invoice->generate();

		// restore previous language
		VikAppointments::loadLanguage($lang);

		if (!$path)
		{
			// something went wrong
			return false;
		}

		if ($increase)
		{
			// increase invoice number in case we are generating progressively
			$this->increaseNumber();
		}

		return $path;
	}

	/**
	 * Sends the invoice via e-mail to the customer.
	 *
	 * @param 	string 	 $path 	The invoice path, which will be 
	 * 							included as attachment within the e-mail.
	 *
	 * @return 	boolean  True on success, otherwise false.
	 */
	public function send($path)
	{
		if (!$this->invoice)
		{
			// invoice not yet set
			return false;
		}

		$to = $this->invoice->getRecipient();

		if (!$to)
		{
			return false;
		}

		$sendermail = VikAppointments::getSenderMail();
		$fromname   = VAPFactory::getConfig()->get('agencyname');

		$id = basename($path);
		$id = substr($id, 0, strrpos($id, '.'));

		// get current language tag
		$lang = JFactory::getLanguage()->getTag();

		// always load the mail contents by using the default language of the website
		VikAppointments::loadLanguage(VikAppointments::getDefaultLanguage(), JPATH_SITE);
		
		// fetch mail subject
		$subject = JText::sprintf('VAPINVMAILSUBJECT', $fromname, $id);
		
		/**
		 * Added message to e-mail.
		 *
		 * @since 1.7
		 */
		$content = JText::sprintf('VAPINVMAILCONTENT', $fromname, $id);

		// restore previous language
		VikAppointments::loadLanguage($lang);
		
		$vik = VAPApplication::getInstance();

		return $vik->sendMail(
			$sendermail,
			$fromname,
			$to,
			null,
			$subject,
			$content,
			array($path),
			$isHtml = false
		);
	}

	/**
	 * Method used to save the current parameters and settings.
	 *
	 * @return 	self  This object to support chaining.
	 *
	 * @uses 	getData()
	 */
	public function save()
	{
		// update invoice data
		VAPFactory::getConfig()->set('invoiceobj', $this->getData());

		return $this;
	}

	/**
	 * Increase the invoice number after a successful generation.
	 *
	 * @return 	void
	 *
	 * @uses 	save()
	 */
	protected function increaseNumber()
	{
		// increase number by one
		$this->data->params->number++;
		// store parameters
		$this->save();
	}
}
