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
 * VikAppointments configuration controller.
 *
 * @since 1.7
 */
class VikAppointmentsControllerConfiguration extends VAPControllerAdmin
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
		////////////////////// GLOBAL //////////////////////
		////////////////////////////////////////////////////

		// SYSTEM

		$args['agencyname']   = $input->getString('agencyname', '');
		$args['companylogo']  = $input->getString('companylogo', '');
		$args['ismultilang']  = $input->getUint('ismultilang', 0);
		$args['router']       = $input->getUint('router', 0);
		$args['showfooter']   = $input->getUint('showfooter', 0);
		$args['googleapikey'] = $input->getString('googleapikey', '');
		$args['sitetheme']    = $input->getString('sitetheme');
		$args['refreshtime']  = $input->getInt('refreshtime', 30);

		// date & time
		$args['dateformat']      = $input->getString('dateformat');
		$args['timeformat']      = $input->getString('timeformat');
		$args['formatduration']  = $input->getUint('formatduration', 0);
		$args['minuteintervals'] = $input->getUint('minuteintervals');
		$args['openingtime']     = $input->getString('openingtime');
		$args['closingtime']     = $input->getString('closingtime');

		// booking
		$args['minrestr']         = $input->getUint('minrestr', 0);
		$args['mindate']          = $input->getUint('mindate', 0);
		$args['maxdate']          = $input->getUint('maxdate', 0);
		$args['keepapplock']      = $input->getUint('keepapplock', 15);
		$args['showphprefix']     = $input->getUint('showphprefix', 0);
		$args['conversion_track'] = $input->getUint('conversion_track', 0);

		// CALENDARS

		$args['calendarlayoutsite'] = $input->getString('calendarlayoutsite');
		$args['calendarweekdays']   = $input->getUint('calendarweekdays');
		$args['numcals']            = $input->getUint('numcals');
		$args['nummonths']          = $input->getUint('nummonths');
		$args['calsfrom']           = $input->getUint('calsfrom');
		$args['calsfromyear']       = $input->getUint('calsfromyear');
		$args['legendcal']          = $input->getUint('legendcal', 0);
		$args['firstday']           = $input->getUint('firstday');

		// GDPR

		$args['gdpr']       = $input->getUint('gdpr', 0);
		$args['policylink'] = $input->getString('policylink', '');

		// TIMEZONE

		$args['multitimezone'] = $input->getUint('multitimezone', 0);

		// APPOINTMENTS SYNC

		$args['synckey'] = $input->getString('synckey', 'secret');

		// ZIP RESTRICTIONS

		$args['zipcfid']      = $input->getInt('zipcfid', 0);
		$args['zipcodesfrom'] = $input->get('zip_code_from', array(), 'array');
		$args['zipcodesto']   = $input->get('zip_code_to', array(), 'array');

		// COLUMNS

		$args['listablecols'] = $input->get('listablecols', array(), 'array');
		$args['listablecf']   = $input->get('listablecf', array(), 'array');

		////////////////////////////////////////////////////
		////////////////////// E-MAIL //////////////////////
		////////////////////////////////////////////////////

		// E-MAIL

		$args['adminemail']  = $input->getString('adminemail', '');
		$args['senderemail'] = $input->getString('senderemail', '');

		// NOTIFICATIONS

		$args['mailcustwhen']  = $input->getString('mailcustwhen', array());
		$args['mailempwhen']   = $input->getString('mailempwhen', array());
		$args['mailadminwhen'] = $input->getString('mailadminwhen', array());

		// TEMPLATES

		$args['mailtmpl']      = $input->getString('mailtmpl', '');
		$args['adminmailtmpl'] = $input->getString('adminmailtmpl', '');
		$args['empmailtmpl']   = $input->getString('empmailtmpl', '');
		$args['cancmailtmpl']  = $input->getString('cancmailtmpl', '');

		// ATTACHMENTS

		$args['mailattach'] = $input->getString('mailattach', array());
		$args['icsattach']  = array(
			$input->getUint('icsattach1', 0),
			$input->getUint('icsattach2', 0),
			$input->getUint('icsattach3', 0),
		);
		$args['csvattach']  = array(
			$input->getUint('csvattach1', 0),
			$input->getUint('csvattach2', 0),
			$input->getUint('csvattach3', 0),
		);

		////////////////////////////////////////////////////
		///////////////////// CURRENCY /////////////////////
		////////////////////////////////////////////////////

		$args['currencysymb']     = $input->getString('currencysymb', '');
		$args['currencyname']     = $input->getString('currencyname', '');
		$args['currsymbpos']      = $input->getInt('currsymbpos', 1);
		$args['currdecimalsep']   = $input->getString('currdecimalsep', '.');
		$args['currthousandssep'] = $input->getString('currthousandssep', ',');
		$args['currdecimaldig']   = $input->getUint('currdecimaldig', 2);

		////////////////////////////////////////////////////
		/////////////////////// SHOP ///////////////////////
		////////////////////////////////////////////////////

		// SHOP

		$args['defstatus']     = $input->getString('defstatus');
		$args['selfconfirm']   = $input->getUint('selfconfirm', 0);
		$args['showcheckout']  = $input->getUint('showcheckout', 0);
		$args['loginreq']      = $input->getUint('loginreq', 0);
		$args['printorders']   = $input->getUint('printorders', 0);
		$args['invoiceorders'] = $input->getUint('invoiceorders', 0);
		$args['showcountdown'] = $input->getUint('showcountdown', 0);

		// cart
		$args['enablecart']      = $input->getUint('enablecart', 0);
		$args['maxcartsize']     = $input->getInt('maxcartsize', -1);
		$args['cartallowsync']   = $input->getUint('cartallowsync', 0);
		$args['shoplink']        = $input->getInt('shoplink', 0);
		$args['shoplinkcustom']  = $input->getString('shoplinkcustom', '');
		$args['confcartdisplay'] = $input->getUint('confcartdisplay', 0);

		// cancellation
		$args['enablecanc'] = $input->getUint('enablecanc', 0);
		$args['canctime']   = $input->getUint('canctime', 0);
		$args['usercredit'] = $input->getUint('usercredit', 0);

		// deposit
		$args['usedeposit']   = $input->getUint('usedeposit', 0);
		$args['depositafter'] = $input->getFloat('depositafter', 300);
		$args['depositvalue'] = $input->getFloat('depositvalue', 40);
		$args['deposittype']  = $input->getInt('deposittype', 1);

		// WAITING LIST

		$args['enablewaitlist']   = $input->getUint('enablewaitlist', 0);
		$args['waitlistsmscont']  = $input->get('waitlistsmscont', array(), 'array');
		$args['waitlistmailtmpl'] = $input->getString('waitlistmailtmpl', '');

		// RECURRING APPOINTMENTS

		$args['enablerecur']    = $input->getUint('enablerecur', 0);
		$args['minamountrecur'] = $input->getUint('minamountrecur', 1);
		$args['maxamountrecur'] = $input->getUint('maxamountrecur', 12);
		$args['repeatbyrecur']  = array();
		$args['fornextrecur']   = array();

		for ($i = 1; $i <= 5; $i++)
		{
			$args['repeatbyrecur'][] = $input->getUint('repeatby' . $i, 0);
		}

		for ($i = 1; $i <= 3; $i++)
		{
			$args['fornextrecur'][] = $input->getUint('fornext' . $i, 0);
		}

		// REVIEWS

		$args['enablereviews']    = $input->getUint('enablereviews', 0);
		$args['revservices']      = $input->getUint('revservices', 0);
		$args['revemployees']     = $input->getUint('revemployees', 0);
		$args['revcommentreq']    = $input->getUint('revcommentreq', 0);
		$args['revminlength']     = $input->getUint('revminlength');
		$args['revmaxlength']     = $input->getUint('revmaxlength');
		$args['revlimlist']       = $input->getUint('revlimlist', 5);
		$args['revlangfilter']    = $input->getUint('revlangfilter', 0);
		$args['revautopublished'] = $input->getUint('revautopublished', 0);
		$args['revloadmode']      = $input->getUint('revloadmode', 1);

		// PACKAGES
		
		$args['enablepackages'] = $input->getUint('enablepackages', 0);
		$args['packsperrow']    = $input->getUint('packsperrow', 3);
		$args['maxpackscart']   = $input->getInt('maxpackscart', -1);
		$args['packsreguser']   = $input->getUint('packsreguser', 0);
		$args['packsmandatory'] = $input->getUint('packsmandatory', 0);
		$args['packmailtmpl']   = $input->getString('packmailtmpl', '');

		// SUBSCRIPTIONS
		
		$args['subscrreguser']   = $input->getUint('subscrreguser', 0);
		$args['subscrmandatory'] = $input->getUint('subscrmandatory', 0);
		$args['subscrthreshold'] = $input->getUint('subscrthreshold', 0);

		// INVOICE

		VAPLoader::import('libraries.invoice.factory');
		$invGen = VAPInvoiceFactory::getGenerator();

		// details
		$args['deftax']   = $input->getUint('deftax', 0);
		$args['usetaxbd'] = $input->getUint('usetaxbd', 0);

		$invoice['number']      = $input->getUint('attr_invoicenumber', 1);
		$invoice['suffix']      = $input->getString('attr_invoicesuffix', '');
		$invoice['datetype']    = $input->getUint('attr_datetype', 1);
		$invoice['legalinfo']   = $input->getString('attr_legalinfo', '');
		$invoice['sendinvoice'] = $input->getUint('attr_sendinvoice', 0);

		$invGen->setParams($invoice);
		
		// properties

		$properties = new stdClass;
		$properties->pageOrientation = $input->getString('prop_page_orientation', 'P');
		$properties->pageFormat      = $input->getString('prop_page_format', 'A4');
		$properties->unit            = $input->getString('prop_unit', 'mm');
		$properties->imageScaleRatio = max(array(5, $input->getFloat('prop_scale', 125))) / 100;

		$invGen->setConstraints($properties);

		// update invoice details here
		$invGen->save();

		////////////////////////////////////////////////////
		///////////////////// LISTINGS /////////////////////
		////////////////////////////////////////////////////

		// EMPLOYEES

		$args['emplistlim']     = $input->getUint('emplistlim');
		$args['emplistmode']    = $input->get('emplistmode', array(), 'array');
		$args['empdesclength']  = $input->getUint('empdesclength', 256);
		$args['emplinkhref']    = $input->getUint('emplinkhref', 1);
		$args['empgroupfilter'] = $input->getUint('empgroupfilter', 0);
		$args['empordfilter']   = $input->getUint('empordfilter', 0);
		$args['empajaxsearch']  = $input->getUint('empajaxsearch', 0);

		// SERVICES

		$args['serdesclength'] = $input->getUint('serdesclength', 256);
		$args['serlinkhref']   = $input->getUint('serlinkhref', 1);

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
		$this->setRedirect('index.php?option=com_vikappointments&view=editconfig');
	}

	/**
	 * AJAX end-point used to validate the requested ZIP code
	 * against the specified ones.
	 *
	 * @return 	void
	 */
	public function testzip()
	{
		$input  = JFactory::getApplication()->input;
		$config = VAPFactory::getConfig();

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}

		$zip   = $input->get('zipcode', '', 'string');
		$pool  = $input->get('pool', null, 'array');
		$field = $input->get('field', null, 'int');

		if (!is_null($pool))
		{
			// a specific validation pool was provided, temporarily
			// update the configuration to apply the validation
			// against the given zip codes
			$tmpCodes = $config->get('zipcodes', '');
			$config->set('zipcodes', $pool);
		}

		if (!is_null($field))
		{
			// a specific validation field was provided, temporarily
			// update the configuration to apply the validation by
			// through the specified custom field
			$tmpField = $config->get('zipcfid', '');
			$config->set('zipcfid', $field);
		}
		
		// validate specified zip code
		$result = (int) VikAppointments::validateZipCode($zip, array(-1));

		if (!is_null($pool))
		{
			// restore previous value
			$config->set('zipcodes', $tmpCodes);
		}

		if (!is_null($field))
		{
			// restore previous value
			$config->set('zipcfid', $tmpField);
		}
		
		// return response to caller
		$this->sendJSON($result);
	}

	/**
	 * AJAX end-point used to import a list of ZIP codes
	 * contained within the uploaded file.
	 *
	 * The file must contain only one ZIP code per line.
	 * The system will sort the zip codes and will try
	 * to group them in case of contiguous codes.
	 *
	 * @return 	void
	 */
	public function uploadzip()
	{
		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}	

		// handle file upload
		$dest = VAPADMIN . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR;
		$result = VikAppointments::uploadFile('file', $dest);
		
		if (!$result->status)
		{
			// unable to upload the file
			UIErrorFactory::raiseError(500, JText::translate('VAPCONFIGUPLOADFILEERR'));
		}
		
		// extract data from file
		$handle = fopen($result->path, 'r');

		$zips = array();

		while (!feof($handle))
		{
			// accept only letters and digits
			$zip = strtoupper(preg_replace('/[^A-Z0-9]/i', '', fgets($handle)));

			if ($zip)
			{
				$zips[] = $zip;
			}
		}

		fclose($handle);

		// delete file
		unlink($result->path);

		$data = array();

		if (count($zips))
		{
			sort($zips);
		
			$from_zip = array();
			$to_zip   = array();
			
			$start = $zips[0];
			$end   = $zips[0];
			$ok    = false;

			for ($i = 1; $i < count($zips); $i++)
			{
				if (is_numeric($zips[$i - 1]) && ($zips[$i - 1] + 1) == $zips[$i])
				{
					$end = $zips[$i];
					$ok  = true;
				}
				else
				{
					$from_zip[] = $start;
					$to_zip[]   = $end;
					
					$start = $zips[$i];
					$end   = $zips[$i];
					$ok    = false;
				}
				
				if ($i == count($zips) - 1)
				{
					$from_zip[] = $start;
					$to_zip[]   = $end;
				}
			}
			
			// iterate all zip codes
			for ($i = 0; $i < count($from_zip); $i++)
			{
				// copy interval within the response
				$data[] = array(
					'from' => $from_zip[$i],
					'to'   => $to_zip[$i],
				);
			}
		}

		// send response to caller
		$this->sendJSON($data);
	}

	/**
	 * Tries to render a preview of the selected e-mail template.
	 *
	 * @return 	void
	 */
	public function mailpreview()
	{
		$app   = JFactory::getApplication();
		$input = $app->input;
		$dbo   = JFactory::getDbo();

		/**
		 * Added token validation (GET only).
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken('get'))
		{
			// back to main list, missing CSRF-proof token
			$app->enqueueMessage(JText::translate('JINVALID_TOKEN'), 'error');
			$this->cancel();

			return false;
		}

		$id    = $input->get('id', 0, 'uint');
		$alias = $input->get('alias', '', 'string');
		$file  = $input->get('file', '', 'string');
		$lang  = $input->get('langtag', '', 'string');

		$options = array();

		// load mail factory
		VAPLoader::import('libraries.mail.factory');

		// build base arguments
		$args = array($alias);
		
		if ($alias == 'package')
		{
			if (!$id)
			{
				// find latest package order
				$q = $dbo->getQuery(true)
					->select($dbo->qn('id'))
					->from($dbo->qn('#__vikappointments_package_order'))
					->order($dbo->qn('id') . ' DESC');

				$dbo->setQuery($q, 0, 1);
				$id = (int) $dbo->loadResult();

				if (!$id)
				{
					throw new Exception('Before to see a preview of the e-mail template, you have to create at least a package first.', 400);
				}
			}

			// inject package ID within the arguments
			$args[] = $id;
		}
		else
		{
			if (!$id)
			{
				// find latest appointment
				$q = $dbo->getQuery(true)
					->select($dbo->qn('id'))
					->from($dbo->qn('#__vikappointments_reservation'))
					->where($dbo->qn('closure') . ' = 0')
					->order($dbo->qn('id') . ' DESC');

				if ($alias == 'waitlist')
				{
					// exclude parent orders
					$q->where($dbo->qn('id_parent') . ' > 0');
				}

				$dbo->setQuery($q, 0, 1);
				$id = (int) $dbo->loadResult();

				if (!$id)
				{
					throw new Exception('Before to see a preview of the e-mail template, you have to create at least an appointment first.', 400);
				}
			}

			// inject appointment ID within the arguments
			$args[] = $id;
		}

		if ($lang)
		{
			// force language tag too
			$options['lang'] = $lang;
		}

		if ($alias == 'cancellation')
		{
			// we should include a sample cancellation reason
			// text to make it visible for styling
			$options['cancellation_reason'] = 'The cancellation reason will be printed here in case the system supports it.';
		}
		else if ($alias == 'waitlist')
		{
			// recover first appointment assigned to order ID
			VAPLoader::import('libraries.order.factory');
			$order = VAPOrderFactory::getAppointments($args[1]);
			$args[1] = array_shift($order->appointments);

			// inject junk waiting list data
			$args[] = array(
				'id_service'  => $args[1]->service->id,
				'id_employee' => $args[1]->employee->id,
				'jid'         => 0,
				'email'       => 'no-reply@domain.com',
			);

			// use current date
			$args[] = JDate::getInstance()->format('Y-m-d');

			// use junk times
			$args[] = array(600, 660, 720);
		}

		$args[] = $options;

		// instantiate provider by using the fetched arguments
		$mail = call_user_func_array(array('VAPMailFactory', 'getInstance'), $args);

		// overwrite template file
		$mail->setFile($file);

		// get mail subject (page title)
		$title = $mail->getSubject();

		// render mail template (page body)
		$tmpl = $mail->getHtml();

		// include style to prevent body from having margins
		$tmpl = '<style>body{margin:0;padding:0;}</style>' . $tmpl;

		$data = array(
			'title' => $title,
			'body'  => $tmpl,
		);

		// display resulting template
		$base = VAPBASE . DIRECTORY_SEPARATOR . 'layouts';
		echo JLayoutHelper::render('document.blankpage', $data, $base);
		exit;
	}
}
