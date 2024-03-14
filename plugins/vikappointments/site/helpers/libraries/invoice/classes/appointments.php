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

/**
 * Class used to generate the invoices of the appointments.
 *
 * @since 	1.6
 */
class VAPInvoiceAppointments extends VAPInvoice
{
	/**
	 * @override
	 * Returns the destination path of the invoice.
	 *
	 * @return 	string 	The invoice path.
	 */
	protected function getInvoicePath()
	{
		$parts = array(
			$this->getInvoiceFolderPath(),
			$this->order->id . '-' . $this->order->sid . '.pdf',
		);

		return implode(DIRECTORY_SEPARATOR, $parts);
	}

	/**
	 * @override
	 * Returns the page template that will be used to 
	 * generate the invoice.
	 *
	 * @return 	string 	The base HTML.
	 */
	protected function getPageTemplate()
	{
		$data = array(
			'order'     => $this->order,
			'breakdown' => $this->getBreakdown(),
			'usetaxbd'  => VAPFactory::getConfig()->getBool('usetaxbd', false),
		);

		// create layout file (always read from the site section of VikAppointments)
		$layout = new JLayoutFile('templates.invoice.appointment', null, [
			'component' => 'com_vikappointments',
			'client'    => 'site',
		]);

		return $layout->render($data);
	}

	/**
	 * @override
	 * Parses the given template to replace the placeholders
	 * with the values contained in the order details.
	 *
	 * @param 	string 	$tmpl   The template to parse.
	 * @param 	array   &$data  An array data to fill.
	 *
	 * @return 	mixed 	The invoice page or an array of pages.
	 */
	protected function parseTemplate($tmpl, &$data)
	{
		$tmpl = parent::parseTemplate($tmpl, $data);
		
		// use default system timezone for dates
		$tz = JFactory::getApplication()->get('offset', 'UTC');

		if (empty($this->params->date))
		{
			switch ($this->params->datetype)
			{
				case 2:
					// booking date
					$date = $this->order->createdon;
					break;

				case 3:
					if (!empty($this->order->appointments))
					{
						// use check-in date of the first appointment
						$date = $this->order->appointments[0]->checkin->utc;
					}
					else
					{
						// fallback to creation date
						$date = $this->order->createdon;
					}
					break;

				default:
					// current date
					$date = 'now';
			}
		}
		else
		{
			// directly use the specified date (expressed in UTC)
			$date = $this->params->date;
		}

		// register date within invoice data
		$data['inv_date'] = JFactory::getDate($date)->toSql();

		// format date
		$invoice_date = JHtml::fetch('date', $date, VAPFactory::getConfig()->get('dateformat'), $tz);

		$tmpl = str_replace('{invoice_date}', $invoice_date, $tmpl);
		
		// customer info
		$custinfo = "";

		if (!empty($this->order->displayFields))
		{
			foreach ($this->order->displayFields as $k => $v)
			{
				// add colon as separator only in case the label doesn't
				// end with a punctuation
				if (preg_match("/[.,:;?!_\-]$/", $k))
				{
					// ends with a punctuation, do not use separator
					$sep = '';
				}
				else
				{
					$sep = ':';
				}

				$custinfo .= $k . $sep . ' ' . $v . "<br/>\n";
			}
		}

		$tmpl = str_replace('{customer_info}', $custinfo, $tmpl);
		
		// billing info
		$billing_info = "";

		if ($this->order->billing)
		{
			$parts = array();

			if (empty($this->order->displayFields))
			{
				// in case of empty custom fields, display the purchaser details
				$parts[] = $this->order->billing->billing_name;
				$parts[] = $this->order->billing->billing_mail;
				$parts[] = $this->order->billing->billing_phone;

				// remove blank info
				$parts = array_values(array_filter($parts));
			}

			// VAT and company name
			$company_info = array();

			if (!empty($this->order->billing->company))
			{
				$company_info[] = $this->order->billing->company;
			}

			if (!empty($this->order->billing->vatnum))
			{
				$company_info[] = $this->order->billing->vatnum;
			}

			if ($company_info)
			{
				$parts[] = implode(' ', $company_info);
			}

			// Address information
			$address_info = array();

			if (!empty($this->order->billing->billing_address))
			{
				$address_info[] = $this->order->billing->billing_address;
			}

			if (!empty($this->order->billing->billing_address_2))
			{
				$address_info[] = $this->order->billing->billing_address_2;
			}

			if ($address_info)
			{
				$parts[] = implode(', ', $address_info);
			}
			
			// City information
			$city_info = array();

			if (!empty($this->order->billing->billing_city))
			{
				$city_info[] = $this->order->billing->billing_city;
			}

			if (!empty($this->order->billing->billing_zip))
			{
				$city_info[] = $this->order->billing->billing_zip;
			}

			if (!empty($this->order->billing->billing_state))
			{
				$city_info[] = $this->order->billing->billing_state;
			}

			if ($city_info)
			{
				$parts[] = implode(', ', $city_info);
			}

			// build details
			$billing_info = implode("<br />\n", $parts);
		}

		$tmpl = str_replace('{billing_info}', $billing_info, $tmpl);
		
		// totals
		$currency = VAPFactory::getCurrency();

		$tmpl = str_replace('{invoice_totalnet}'  , $currency->format($this->order->totals->net)      , $tmpl);
		$tmpl = str_replace('{invoice_totaltax}'  , $currency->format($this->order->totals->tax)      , $tmpl);
		$tmpl = str_replace('{invoice_grandtotal}', $currency->format($this->order->totals->gross)    , $tmpl);
		$tmpl = str_replace('{invoice_paycharge}' , $currency->format($this->order->totals->payCharge), $tmpl);
		$tmpl = str_replace('{invoice_discount}'  , $currency->format($this->order->totals->discount) , $tmpl);

		return $tmpl;
	}

	/**
	 * @override
	 * Returns the e-mail address of the user that should
	 * receive the invoice via mail.
	 *
	 * @return 	string 	The customer e-mail.
	 */
	public function getRecipient()
	{
		return $this->order->purchaser_mail;
	}

	/**
	 * Extracts an overall breakdown from the order.
	 *
	 * @return 	array
	 *
	 * @since 	1.7
	 */
	protected function getBreakdown()
	{
		$arr = array();

		// group all breakdowns at the same level
		foreach ($this->order->appointments as $app)
		{
			if ($app->totals->breakdown)
			{
				$arr = array_merge($arr, $app->totals->breakdown);
			}

			foreach ($app->options as $opt)
			{
				if ($opt->totals->breakdown)
				{
					$arr = array_merge($arr, $opt->totals->breakdown);
				}
			}
		}

		$breakdown = array();

		// iterate breakdowns
		foreach ($arr as $bd)
		{
			if (!isset($breakdown[$bd->name]))
			{
				$breakdown[$bd->name] = 0;
			}

			$breakdown[$bd->name] += $bd->tax;
		}

		// check if we have a tax for the payment
		if ($this->order->totals->payTax > 0)
		{
			// manually register payment tax within the breakdown
			$breakdown[JText::translate('VAPINVPAYTAX')] = $this->order->totals->payTax;
		}

		return $breakdown;
	}
}
