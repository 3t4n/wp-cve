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
 * VikAppointments component helper class.
 *
 * @since 	1.0
 */
abstract class VikAppointments
{	
	/**
	 * Checks if the system supports multi-lingual contents.
	 *
	 * @return 	integer
	 *
	 * @deprecated 	1.8 	Use VAPConfig instead.
	 */
	public static function isMultilanguage()
	{
		return VAPFactory::getConfig()->getBool('ismultilang');
	}
	
	/**
	 * Returns a list of admin e-mails.
	 *
	 * @return 	array
	 */
	public static function getAdminMailList()
	{
		// get all e-mails
		$admin_mail_list = VAPFactory::getConfig()->getString('adminemail');

		if (!strlen($admin_mail_list))
		{
			return array();
		}

		return array_map('trim', explode(',', $admin_mail_list));
	}
	
	/**
	 * Returns the admin e-mail. If not specified, the one set in the global
	 * configuration of the CMS will be used.
	 *
	 * @return 	string
	 */
	public static function getAdminMail()
	{
		// get all e-mails
		$mails = self::getAdminMailList();

		if ($mails)
		{
			// returns first e-mail available
			return $mails[0];
		}

		// use owner e-mail
		return JFactory::getApplication()->get('mailfrom');
	}
	
	/**
	 * Returns the sender e-mail. If not provided, the first one 
	 * specified for the admin e-mail field will be used.
	 *
	 * @return 	string
	 */
	public static function getSenderMail()
	{
		// get sender from config
		$sender = VAPFactory::getConfig()->getString('senderemail');

		if (empty($sender))
		{
			// missing sender, use the default one
			$sender = self::getAdminMail();
		}

		return $sender;
	} 
	
	/**
	 * Returns the file path to attach within the e-mail for customers (if any).
	 *
	 * @return 	string
	 *
	 * @deprecated 1.8  Use VikAppointments::getMailAttachmentsURL() instead.
	 */
	public static function getMailAttachmentURL()
	{
		$attachments = static::getMailAttachmentsURL();

		return array_shift($attachments);
	}

	/**
	 * Returns a list of file paths to attach within the e-mail for customers (if any).
	 *
	 * @return 	array
	 *
	 * @since 	1.7
	 */
	public static function getMailAttachmentsURL()
	{
		// get attachments list
		$attachments = VAPFactory::getConfig()->getArray('mailattach');

		// map the attachments to have a full path
		return array_map(function($attachment)
		{
			return VAPMAIL_ATTACHMENTS . DIRECTORY_SEPARATOR . $attachment;
		}, $attachments);
	}
	
	/**
	 * Returns an array containing the e-mail sending rules.
	 * The array contains the rules for these entities: customer, employee, admin.
	 *
	 * @return 	array
	 */
	public static function getSendMailWhen()
	{
		$config = VAPFactory::getConfig();

		return array(
			'customer' 	=> $config->getUint('mailcustwhen'),
			'employee' 	=> $config->getUint('mailempwhen'),
			'admin' 	=> $config->getUint('mailadminwhen'),
		);
	}

	/**
	 * Returns an array containing the rules to attach the ICS file within the e-mail.
	 * The array contains the rules for these entities: customer, employee, admin.
	 *
	 * @param 	mixed 	$client  When provided, the method will return a boolean meaning
	 *                           whether the attachments should be included for that client.
	 *
	 * @return 	array|boolean
	 */
	public static function getAttachmentPropertiesICS($client = null)
	{
		$ics = explode(';', VAPFactory::getConfig()->get('icsattach'));

		$prop = array(
			'customer' => $ics[0],
			'employee' => $ics[1],
			'admin'    => $ics[2],
		);

		if ($client)
		{
			// immediately check whether the client supports ICS as attachment
			return isset($prop[$client]) ? (bool) $prop[$client] : false;
		}

		return $prop;
	}
	
	/**
	 * Returns an array containing the rules to attach the CSV file within the e-mail.
	 * The array contains the rules for these entities: customer, employee, admin.
	 *
	 * @param 	mixed 	$client  When provided, the method will return a boolean meaning
	 *                           whether the attachments should be included for that client.
	 *
	 * @return 	array|boolean
	 */
	public static function getAttachmentPropertiesCSV($client = null)
	{
		$csv = explode(';', VAPFactory::getConfig()->get('csvattach'));

		$prop = array(
			'customer' => $csv[0],
			'employee' => $csv[1],
			'admin'    => $csv[2],
		);

		if ($client)
		{
			// immediately check whether the client supports CSV as attachment
			return isset($prop[$client]) ? (bool) $prop[$client] : false;
		}

		return $prop;
	}

	/**
	 * Returns an array containing the opening hours and minutes.
	 *
	 * @return 	array
	 */
	public static function getOpeningTime()
	{
		$op = explode(':', VAPFactory::getConfig()->get('openingtime'));

		return array(
			'hour' => (int) $op[0],
			'min'  => (int) $op[1],
		);
	}
	
	/**
	 * Returns an array containing the closing hours and minutes.
	 *
	 * @return 	array
	 */
	public static function getClosingTime()
	{
		$cl = explode(':', VAPFactory::getConfig()->get('closingtime'));

		return array(
			'hour' => (int) $cl[0],
			'min'  => (int) $cl[1],
		);
	}

	/**
	 * Returns an array containing all the available modes to sort
	 * the employees.
	 *
	 * @return 	array
	 */
	public static function getEmployeesAvailableOrderings()
	{
		$modes = VAPFactory::getConfig()->getJSON('emplistmode');

		$arr  = array();

		foreach ($modes as $i => $v)
		{
			if ($v == 1)
			{
				$arr[] = $i;
			}
		}

		if (!count($arr))
		{
			// always allow the default ordering (a..Z)
			$arr[0] = 1;
		}

		return $arr;
	}

	/**
	 * Returns the default ordering to use to list the employees.
	 *
	 * @return 	string
	 */
	public static function getEmployeesListingMode()
	{
		$arr = self::getEmployeesAvailableOrderings();

		return $arr[0];
	}

	/**
	 * Returns the configuration array containing the listing details
	 * of the employees. The array will contain the following keys:
	 *
	 * @property 	integer  desclength 		The maximum number of characters.
	 * @property 	integer  linkhref 			The event to use when clicking the image.
	 * @property 	integer  filtergroups 		Whether the group filtering is enabled or not.
	 * @property 	integer  filterordering 	Whether the ordering selection is enabled or not.
	 * @property 	integer  ajaxsearch 		The type of AJAX search.
	 *
	 * @return 	array 	An associative array.
	 *
	 * @deprecated 1.8  Use VAPConfig instead.
	 */
	public static function getEmployeesListingDetails()
	{
		$config = VAPFactory::getConfig();

		return array( 
			'desclength'     => $config->getInt('empdesclength'),
			'linkhref'       => $config->getInt('emplinkhref'),
			'filtergroups'   => $config->getInt('empgroupfilter'),
			'filterordering' => $config->getInt('empordfilter'),
			'ajaxsearch'     => $config->getInt('empajaxsearch'),
		);
	}
	
	/**
	 * Returns the configuration array containing the listing details
	 * of the services. The array will contain the following keys:
	 *
	 * @property 	integer  desclength  The maximum number of characters.
	 * @property 	integer  linkhref 	 The event to use when clicking the image.
	 *
	 * @return 	array 	An associative array.
	 *
	 * @deprecated 1.8  Use VAPConfig instead.
	 */
	public static function getServicesListingDetails()
	{
		$config = VAPFactory::getConfig();

		return array( 
			'desclength' => $config->getInt('serdesclength'),
			'linkhref' 	 => $config->getInt('serlinkhref'),
		);
	}
	
	/**
	 * Returns the first month of the calendar to display (used only if the month is not in the past).
	 *
	 * @return 	integer
	 *
	 * @see 	getCalendarFirstYear()
	 *
	 * @deprecated 	1.8 	Use VAPConfig instead.
	 */
	public static function getCalendarFirstMonth()
	{
		return VAPFactory::getConfig()->getInt('calsfrom');
	}
	
	/**
	 * Returns the year to which the first month is referring to.
	 *
	 * @return 	integer
	 *
	 * @see 	getCalendarFirstMonth()
	 *
	 * @deprecated 	1.8 	Use VAPConfig instead.
	 */
	public static function getCalendarFirstYear()
	{
		$year = VAPFactory::getConfig()->getInt('calsfromyear');
		
		if (!$year)
		{
			// return current year
			$arr  = getdate();
			$year = $arr['year'];
		}

		return $year;
	}
	
	/**
	 * Checks if the customer can add an item into its cart.
	 *
	 * @param 	integer  $cart_size  The current number of items.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public static function canAddItemToCart($cart_size)
	{
		$config = VAPFactory::getConfig();

		$max_cart_size = $config->getInt('maxcartsize');

		return ($max_cart_size == -1 || $cart_size < $max_cart_size || !$config->getBool('enablecart'));
	}

	/**
	 * Checks if the packages system is enabled.
	 *
	 * @return 	integer
	 *
	 * @deprecated 	1.8 	Use VAPConfig instead.
	 */
	public static function isPackagesEnabled()
	{
		return VAPFactory::getConfig()->getBool('enablepackages');
	}
	
	/**
	 * Returns the confirmation message that will be asked while deleting an item.
	 * In case the confirmation message is disabled, an empty string will be returned.
	 *
	 * @return 	string
	 */
	public static function getConfirmSystemMessage()
	{
		if (VAPFactory::getConfig()->getBool('askconfirm', true))
		{
			return JText::translate('VAPSYSTEMCONFIRMATIONMSG');
		}

		return '';
	}
	
	/**
	 * Returns a list containing the closing days.
	 *
	 * Each element of the list is an associative array with the following properties:
	 * @property  integer 	ts 	  The unix timestamp.
	 * @property  string 	date  The formatted date.
	 * @property  integer 	freq  The closing frequency (0 = single day, 1 = weekly, 2 = monthly, 3 = yearly).
	 *
	 * @param 	integer  $id_ser  If specified, filters the closing days for this service.
	 *
	 * @return 	array
	 */
	public static function getClosingDays($id_ser = null)
	{
		$config = VAPFactory::getConfig();
		$_str = $config->get('closingdays');

		if (!strlen($_str))
		{
			return array();
		}

		static $pool = array();

		// check if the closing days were already fetched
		if (isset($pool[$id_ser]))
		{
			// return cached list
			return $pool[$id_ser];
		}

		$cd = explode(';;', $_str);

		$list = array();

		for ($i = 0, $n = count($cd); $i < $n; $i++)
		{
			$_app = explode(':', $cd[$i]);

			/**
			 * Fetch services assigned to the closing day.
			 *
			 * @since 1.6.3
			 */
			$_app[2] = empty($_app[2]) || $_app[2] == '*' ? array() : explode(',', $_app[2]);

			// copy closing day only if it can be used for the specified service, if any
			if (!$id_ser || !$_app[2] || in_array($id_ser, $_app[2]))
			{
				/**
				 * The closing days are now saved in military format
				 * as UTC dates and, since they are globally considered,
				 * they do not have to be adjusted to the local timezone.
				 *
				 * @since 1.7
				 */
				$list[] = array(
					'ts'       => $_app[0],
					'date'     => JDate::getInstance($_app[0])->format($config->get('dateformat')),
					'freq'     => $_app[1],
					'services' => $_app[2],
				);
			}
		}

		// cache closing days
		$pool[$id_ser] = $list;

		return $list;
	}

	/**
	 * Returns a list containing the closing periods.
	 *
	 * Each element of the list is an associative array with the following properties:
	 * @property  start  start 	The starting closing period (UNIX timestamp).
	 * @property  end 	 end 	The ending closing period (UNIX timestamp).
	 *
	 * @param 	integer  $id_ser  If specified, filters the closing days for this service.
	 *
	 * @return 	array
	 */
	public static function getClosingPeriods($id_ser = null)
	{
		$config = VAPFactory::getConfig();
		$_str = $config->get('closingperiods');

		if (!strlen($_str))
		{
			return array();
		}

		static $pool = array();

		// check if the closing periods were already fetched
		if (isset($pool[$id_ser]))
		{
			// return cached list
			return $pool[$id_ser];
		}

		$cp = explode(';;', $_str);

		$list = array();

		for ($i = 0, $n = count($cp); $i < $n; $i++)
		{
			$_app = explode(':', $cp[$i]);

			/**
			 * Fetch services assigned to the closing day.
			 *
			 * @since 1.6.3
			 */
			$_app[2] = empty($_app[2]) || $_app[2] == '*' ? array() : explode(',', $_app[2]);

			// copy closing day only if it can be used for the specified service, if any
			if (!$id_ser || !$_app[2] || in_array($id_ser, $_app[2]))
			{
				/**
				 * The closing periods are now saved in military format
				 * as UTC dates and, since they are globally considered,
				 * they do not have to be adjusted to the local timezone.
				 *
				 * @since 1.7
				 */
				$list[] = array(
					'start'     => $_app[0],
					'end'       => $_app[1],
					'datestart' => JDate::getInstance($_app[0])->format($config->get('dateformat')),
					'dateend'   => JDate::getInstance($_app[1])->format($config->get('dateformat')),
					'services'  => $_app[2],
				);
			}
		}

		// cache closing periods
		$pool[$id_ser] = $list;

		return $list;
	}
	
	/**
	 * Returns the configuration array containing the recurrence parameters.
	 * The array will contain the following keys:
	 *
	 * @property  array 	repeat  The allowed repeat options.
	 * @property  integer  	min 	The minimum number of elements that can be selected.
	 * @property  integer  	max 	The maximum number of elements that can be selected.
	 * @property  array  	for 	The allowed for options.
	 *
	 * @return 	array
	 */
	public static function getRecurrenceParams()
	{
		$config = VAPFactory::getConfig();

		return array( 
			'repeat' => explode(';', $config->get('repeatbyrecur')),
			'min' 	 => $config->getUint('minamountrecur'),
			'max' 	 => $config->getUint('maxamountrecur'),
			'for' 	 => explode(';', $config->get('fornextrecur')),
		);
	}
	
	/**
	 * Checks if the reviews for the services are enabled.
	 *
	 * @return 	boolean
	 */
	public static function isServicesReviewsEnabled()
	{
		$config = VAPFactory::getConfig();

		return $config->getBool('enablereviews') && $config->getBool('revservices');
	}
	
	/**
	 * Checks if the reviews for the employees are enabled.
	 *
	 * @return 	boolean
	 */
	public static function isEmployeesReviewsEnabled()
	{
		$config = VAPFactory::getConfig();

		return $config->getBool('enablereviews') && $config->getBool('revemployees');
	}

	/**
	 * Checks if the waiting list system is enabled.
	 *
	 * @return 	integer
	 *
	 * @deprecated 	1.8 	Use VAPConfig instead.
	 */
	public static function isWaitingList()
	{
		return VAPFactory::getConfig()->getBool('enablewaitlist');
	}
	
	/**
	 * Returns an array containing the fields to display within the
	 * reservations list (back-end).
	 *
	 * @param 	boolean  $custom  True to return the custom fields in place
	 *                            of the default ones (@since 1.7).
	 *
	 * @return 	array
	 */
	public static function getListableFields($custom = false)
	{
		$config = VAPFactory::getConfig();

		// get custom fields
		$str = $config->get($custom ? 'listablecf' : 'listablecols');

		if (empty($str))
		{
			return array();
		}
		
		return explode(',', $str);
	}
	
	/**
	 * Checks if the SMS notifications should be send to the customers.
	 *
	 * @return 	integer
	 */
	public static function getSmsApiToCustomer()
	{
		$str = explode(',', VAPFactory::getConfig()->get('smsapito'));
		return intval($str[0]);
	}
	
	/**
	 * Checks if the SMS notifications should be send to the employees.
	 *
	 * @return 	integer
	 */
	public static function getSmsApiToEmployee()
	{
		$str = explode(',', VAPFactory::getConfig()->get('smsapito'));
		return intval($str[1]);
	}
	
	/**
	 * Checks if the SMS notifications should be send to the administrator.
	 *
	 * @return 	integer
	 */
	public static function getSmsApiToAdmin()
	{
		$str = explode(',', VAPFactory::getConfig()->get('smsapito'));
		return intval($str[2]);
	}
	
	/**
	 * Returns the configuration array of the selected SMS driver.
	 *
	 * @return 	array
	 *
	 * @deprecated 	1.8 	Use VAPConfig instead.
	 */
	public static function getSmsApiFields()
	{
		return VAPFactory::getConfig()->getArray('smsapifields');
	}

	/**
	 * Returns the value of the specified configuration setting.
	 *
	 * @param 	string 	$param 	The setting name.
	 *
	 * @return 	string 	The configuration value.
	 *
	 * @deprecated 	1.8 	Use VAPConfig instead.
	 */
	private static function getFieldFromConfig($param)
	{
		return VAPFactory::getConfig()->getString($param, '');
	}
	
	/**
	 * Returns the audio file that will be used to play a
	 * notification sound every time a new order comes in.
	 *
	 * It is possible to use a different audio simply by uploading
	 * that file within the admin/assets/audio/ folder. The most
	 * recent file will be always used.
	 *
	 * @return 	string 	The file URI.
	 *
	 * @since 	1.7
	 */
	public static function getNotificationSound()
	{
		// get all files placed within audio folder
		$files = glob(VAPADMIN . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'audio' . DIRECTORY_SEPARATOR . '*');

		// take only audio files (exclude default one too)
		$files = array_values(array_filter($files, function($f)
		{
			if (preg_match("/[\/\\\\]notification\.mp3$/i", $f))
			{
				// ignore default file
				return false;
			}

			// keep only the most common audio files
			return preg_match("/\.(mp3|mp4|wav|ogg|aac|flac)$/i", $f);
		}));

		if (!$files)
		{
			// no additional audio files, use the default one
			return VAPASSETS_ADMIN_URI . 'audio/notification.mp3';
		}

		// sort files from the most recent to the oldest
		usort($files, function($a, $b)
		{
			// sort by descending creation date
			return filemtime($b) - filemtime($a);
		});

		// return most recent file
		return VAPASSETS_ADMIN_URI . 'audio/' . basename($files[0]);
	}

	/**
	 * Loads the cart framework.
	 *
	 * @return 	void
	 */
	public static function loadCartLibrary()
	{
		VAPLoader::import('libraries.cart.cart');
		VAPLoader::import('libraries.cart.utils');
		VAPLoader::import('libraries.cart.core');
	}

	/**
	 * Loads the cart packages framework.
	 *
	 * @return 	void
	 */
	public static function loadCartPackagesLibrary()
	{
		VAPLoader::import('libraries.cartpack.cart');
		VAPLoader::import('libraries.cartpack.core');
	}
	
	/**
	 * Loads the cron framework.
	 *
	 * @return 	boolean  True if the framework was loaded, false otherwise. 
	 */
	public static function loadCronLibrary()
	{
		static $loaded = 0;

		if (!$loaded)
		{
			// include base framework
			VAPLoader::import('libraries.cron.core');
			// include system overrides
			VAPLoader::import('libraries.cron.overrides.formbuilder');

			// register folder containing the supported cron jobs
			VAPCronDispatcher::addIncludePath(VAPADMIN . DIRECTORY_SEPARATOR . 'cronjobs');
		}

		// do not load more than once
		$loaded = 1;
	}
	
	/**
	 * Checks whether the user can cancel an appointment.
	 *
	 * @param 	object   $appointment  The appointment details.
	 *
	 * @return 	boolean  True if allowed, false otherwise.
	 */
	public static function canUserCancelOrder($appointment)
	{
		// make sure the appointment is confirmed
		if ($appointment->statusRole != 'APPROVED')
		{
			// appointment not confirmed
			return false;
		}

		$config = VAPFactory::getConfig();

		if (!$config->getBool('enablecanc'))
		{
			// do not go ahead in case the cancellation is disabled
			return false;
		}

		// get current time (UTC)
		$threshold = JFactory::getDate();

		// get minimum required days
		$mindays = $config->getUint('canctime');

		// sum minimum required days to current date and time
		$threshold->modify('+' . $mindays . ' days');

		if ($threshold >= $appointment->checkin->utc)
		{
			// not enough time to complete the cancellation, the check-in
			// is too close to the current date and time
			return false;
		}

		/**
		 * This event can be used to apply additional conditions to the 
		 * cancellation restrictions. When this event is triggered, the
		 * system already validated the standard conditions and the
		 * cancellation has been approved for the usage.
		 *
		 * @param 	mixed 	 $appointment  The appointment to check.
		 *
		 * @return 	boolean  Return false to deny the cancellation.
		 *
		 * @since 	1.7
		 */
		if (VAPFactory::getEventDispatcher()->false('onCheckAppointmentCancellation', array($appointment)))
		{
			// a plugin prevented the cancellation
			return false;
		}

		// cancellation allowed
		return true;
	}

	/**
	 * Checks whether the user can approve its own appointment.
	 *
	 * @param 	object   $appointment  The appointment details.
	 *
	 * @return  boolean  True if possible, false otherwise.
	 *
	 * @since 	1.7.1
	 */
	public static function canUserApproveOrder($appointment)
	{
		// make sure the order is pending
		if ($appointment->statusRole != 'PENDING')
		{
			// order not pending
			return false;
		}

		// check if the order has been assigned to a payment
		if ($appointment->payment)
		{
			// get payment details
			$payment = JModelVAP::getInstance('payment')->getItem($appointment->payment->id);

			// check if the payment allows the self-confirmation
			$enabled = $payment && $payment->selfconfirm;
		}
		else
		{
			// otherwise check global parameter
			$enabled = VAPFactory::getConfig()->getBool('selfconfirm');
		}

		if (!$enabled)
		{
			// do not go ahead in case the self-confirmation is disabled
			return false;
		}

		/**
		 * This event can be used to apply additional conditions to the 
		 * self-confirmation restrictions. When this event is triggered, the
		 * system already validated the standard conditions and the
		 * confirmation has been approved for the usage.
		 *
		 * @param 	mixed 	 $appointment  The appointment to check.
		 *
		 * @return 	boolean  Return false to deny the confirmation.
		 *
		 * @since 	1.7.1
		 */
		$res = VAPFactory::getEventDispatcher()->trigger('onCheckAppointmentSelfConfirmation', array($appointment));

		// check if at least a plugin returned FALSE to prevent the confirmation
		return !in_array(false, $res, true);
	}

	/**
	 * Checks whether the system should display the price of the selected service.
	 *
	 * @param 	mixed 	 $service  Either an object holding the service details
	 *                             or its identifier.
	 *
	 * @return 	boolean  True to display the price, false otherwise.
	 *
	 * @since 	1.7
	 */
	public static function shouldDisplayServicePrice($service)
	{
		if (!$service)
		{
			// invalid argument, auto-hide price
			return false;
		}

		if (is_int($service))
		{
			// load service details
			$service = JModelVAP::getInstance('service')->getItem((int) $service);

			// service not found...
			if ($service)
			{
				return false;
			}
		}
		else
		{
			// cast service to array
			$service = (object) $service;
		}

		if ($service->price <= 0)
		{
			// the service has not cost, do not display the price
			return false;
		}

		// get details of the currently logged-in user
		$customer = VikAppointments::getCustomer();

		// in case the customer exists and it is subscribed to the
		// specified service, hide the cost per appointment
		if ($customer && $customer->isSubscribed($service->id))
		{
			return false;
		}

		/**
		 * This event can be used to apply additional conditions to the 
		 * visibility restrictions. When this event is triggered, the
		 * system already validated all the standard conditions.
		 *
		 * @param 	object   $service  The service details.
		 *
		 * @return 	boolean  Return false to hide the price.
		 *
		 * @since 	1.7
		 */
		if (VAPFactory::getEventDispatcher()->false('onChooseDisplayServicePrice', array($service)))
		{
			// a plugin decided to hide the price
			return false;
		}

		// display the price
		return true;
	}

	/**
	 * Returns the media upload settings.
	 *
	 * @return 	array
	 *
	 * @since 	1.7
	 */
	public static function getMediaProperties()
	{
		$config = VAPFactory::getConfig();
		
		$prop = array();
		$prop['oriwres']   = $config->getUint('oriwres', 512);
		$prop['orihres']   = $config->getUint('orihres', 512);
		$prop['smallwres'] = $config->getUint('smallwres', 256);
		$prop['smallhres'] = $config->getUint('smallhres', 256);
		$prop['isresize']  = $config->getUint('isresize', 0);	

		return $prop;
	}

	/**
	 * Updates the media upload settings.
	 *
	 * @param  	array 	&$prop
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	public static function storeMediaProperties(&$prop)
	{
		$config = VAPFactory::getConfig();

		$lookup = array(
			'oriwres',
			'orihres',
			'smallwres',
			'smallhres',
			'isresize',
		);

		foreach ($lookup as $k)
		{
			if (isset($prop[$k]))
			{
				$config->set($k, $prop[$k]);
			}
		}

		$config->set('isconfig', 1);
	}
	
	/**
	 * Helper method used to upload the given image (retrieved from $_FILES)
	 * into the specified destination.
	 *
	 * @param 	array 	$img 	An associative array with the file details.
	 * @param 	string 	$dest 	The destination path.
	 *
	 * @return 	object 	The uploading result.
	 *
	 * @uses 	uploadFile()
	 */
	public static function uploadImage($img, $dest)
	{
		// upload as a normal file
		return self::uploadFile($img, $dest, 'jpeg,jpg,png,gif,bmp', $overwrite = false);
	}

	/**
	 * Uploads a media file.
	 *
	 * @param 	string 	 $name       The media name.
	 * @param 	mixed 	 $prop       The upload settings.
	 * @param 	boolean  $overwrite  True to overwrite the existing media.
	 *
	 * @return 	array 	 A response.
	 *
	 * @since 	1.7
	 *
	 * @uses 	uploadFile()
	 */
	public static function uploadMedia($name, $prop = null, $overwrite = false)
	{
		$model = JModelVAP::getInstance('media');

		// upload as a normal file
		$resp = self::uploadFile($name, VAPMEDIA . DIRECTORY_SEPARATOR, $model->getFileAllowedRegex('image'), $overwrite);

		// import image cropper
		VAPLoader::import('libraries.image.resizer');

		if ($resp->status)
		{
			if ($prop === null)
			{
				// get media settings if not specified
				$prop = self::getMediaProperties();
			}
			
			if ($prop['isresize'] == 1)
			{	
				// crop original image
				$crop_dest = str_replace($resp->name, '$_' . $resp->name, $resp->path);
				
				VAPImageResizer::proportionalImage($resp->path,  $crop_dest, $prop['oriwres'], $prop['orihres']);
				copy($crop_dest, $resp->path);
				unlink($crop_dest);
			}

			// generate thumbnail
			$thumb_dest = VAPMEDIA_SMALL . DIRECTORY_SEPARATOR . $resp->name;
			VAPImageResizer::proportionalImage($resp->path, $thumb_dest,  $prop['smallwres'],  $prop['smallhres']);
		}

		return $resp;
	}
	
	/**
	 * Moves the given file within the specified destination.
	 *
	 * @param 	mixed 	 $name       Either the file object or the $_FILES name in
	 *                               which the file is located.
	 * @param 	string 	 $dest       The path (including filename) in which to move the uploaded file.
	 * @param 	string 	 $filters    Either a regex or a comma-separated list of supported extensions.
	 * @param 	boolean  $overwrite  True to overwrite the file if the destination is already occupied.
	 *                               Otherwise a progressive file name will be used.
	 *
	 * @return 	object 	 An object containing the information of the uploaded file. It is possible to
	 *                   check whether the file was uploaded by looking the "status" property. In case of
	 *                   errors, the "errno" property will return an error code to understand why the error
	 *                   occurred (1: unsupported file, 2: generic upload error).
	 */
	public static function uploadFile($name, $dest, $filters = '*', $overwrite = false)
	{
		if (is_string($name))
		{
			$file = JFactory::getApplication()->input->files->get($name, null, 'array');
		}
		else
		{
			$file = (array) $name;
		}

		/**
		 * Check whether the destination path includes the file name or
		 * just the upload directory.
		 *
		 * @since 1.7
		 */
		if (preg_match("/\.[a-zA-Z0-9]+$/", $dest) && !is_dir($dest))
		{
			// We found a path ending with a probable extension and
			// the destination path is not a directory.
			// Extract the filename from the destination path.
			$filename = basename($dest);
			// remove file name from destination
			$dest = dirname($dest);
		}
		else
		{
			// otherwise use the file name of the uploaded file
			$filename = isset($file['name']) ? $file['name'] : null;
		}

		$dest = rtrim($dest, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		
		/**
		 * Added support for status property.
		 * The [esit] property will be temporarily
		 * left for backward compatibility.
		 *
		 * @since 1.7
		 *
		 * @deprecated 1.8  [esit] property will be removed.
		 */
		$obj = new stdClass;
		$obj->status = 0;
		$obj->esit   = 0;
		$obj->errno  = null;
		$obj->path   = '';
		
		if (isset($file) && strlen(trim($file['name'])) > 0)
		{
			jimport('joomla.filesystem.file');

			$filename = JFile::makeSafe(str_replace(' ', '-', $filename));
			$src = $file['tmp_name'];

			// use a different name if the file path is already occupied
			if (!$overwrite && file_exists($dest . $filename))
			{
				$j = 2;

				// split file name and file extension
				if (preg_match("/(.*?)(\.[a-z0-9]+)$/i", $filename, $match))
				{
					$basename = $match[1];
					$file_ext = $match[2];
				}
				else
				{
					$basename = $filename;
					$file_ext = '';
				}

				// increase counter as long as the path is occupied
				while (file_exists($dest . $basename . '-' . $j . $file_ext))
				{
					$j++;
				}

				// construct file name
				$filename = $basename . '-' . $j . $file_ext;
			}

			// create file object
			$obj->path = $dest . $filename;
			$obj->src  = $src;
			$obj->name = $filename;

			// make sure the file is compatible
			if (self::isFileTypeCompatible($filename, $filters))
			{
				// complete file upload
				if (JFile::upload($src, $obj->path, $use_streams = false, $allow_unsafe = true))
				{
					$obj->status = 1;
					$obj->esit   = 1;
				}
				else
				{
					// unable to upload the file
					$obj->errno = 2;
				}
			}
			else
			{
				// file not supported
				$obj->errno = 1;
				// include fetched MIME type
				$obj->mimeType = $file['type'];
			}
		}

		return $obj;
	}
	
	/**
	 * Helper method used to print formatted prices according to the global configuration.
	 *
	 * @param 	float 	 $price  The price to format.
	 * @param 	string 	 $symb 	 The currency symbol. If not provided the default one will be used.
	 * @param 	integer  $pos 	 The currency position (1 = after price, 2 = before price).
	 * 							 If not provided, the default one will be used.
	 *
	 * @return 	string 	 The formatted price.
	 *
	 * @deprecated 	1.8  Use VAPCurrency::format() instead.
	 */
	public static function printPriceCurrencySymb($price, $symb = null, $pos = null)
	{
		$options = array();

		if ($symb)
		{
			$options['symbol'] = $symb;
		}
		
		if ($pos)
		{
			$options['position'] = (int) $pos;
		}

		return VAPFactory::getCurrency()->format($price, $options);
	}
	
	/**
	 * Checks if the value for the specified custom field is valid.
	 *
	 * @param 	array 	 $cf 	The custom field details.
	 * @param 	mixed 	 $val 	The given value.
	 *
	 * @return 	boolean  True if valid, false otherwise.
	 */
	public static function isCustomFieldValid($cf, $val)
	{
		return $cf['required'] == 0
		|| ($cf['type'] != 'file' && strlen($val))
		|| ($cf['type'] == 'file' && !empty($val['name']));
	}
	
	/**
	 * Helper method used to check whether the given file name
	 * supports one of the given filters.
	 *
	 * @param 	mixed 	 $file     Either the file name or the uploaded file.
	 * @param 	string 	 $filters  Either a regex or a comma-separated list of supported extensions.
	 *                             The regex must be inclusive of 
	 *
	 * @return 	boolean  True if supported, false otherwise.
	 */
	public static function isFileTypeCompatible($file, $filters)
	{
		// make sure the filters query is not empty
		if (strlen($filters) == 0)
		{
			// cannot assert whether the file could be accepted or not
			return false;
		}

		// check whether all the files are accepted
		if ($filters == '*')
		{
			return true;
		}

		// use the file MIME TYPE in case of array
		if (is_array($file))
		{
			$file = $file['type'];
		}

		/**
		 * Check if we are handling a regex.
		 *
		 * @since 1.7
		 */
		if (static::isRegex($filters))
		{
			return (bool) preg_match($filters, $file);
		}
		
		// fallback to comma-separated list
		$types = array_filter(preg_split("/\s*,\s*/", $filters));

		foreach ($types as $t)
		{
			// remove initial dot if specified
			$t = ltrim($t, '.');
			// escape slashes to avoid breaking the regex
			$t = preg_replace("/\//", '\/', $t);

			// check if the file ends with the given extension
			if (preg_match("/{$t}$/", $file))
			{
				return true;
			}
		}
		
		return false;
	}

	/**
	 * Checks whether the given string is a structured PCRE regex.
	 * It simply makes sure that the string owns valid delimiters.
	 * A delimiter can be any non-alphanumeric, non-backslash,
	 * non-whitespace character.
	 *
	 * @param 	string   $str  The string to check.
	 *
	 * @return 	boolean  True if a regex, false otherwise.
	 *
	 * @since 	1.7
	 */
	public static function isRegex($str)
	{
		// first of all make sure the first character is a supported delimiter
		if (!preg_match("/^([!#$%&'*+,.\/:;=?@^_`|~\-(\[{<\"])/", $str, $match))
		{
			// no valid delimiter
			return false;
		}

		// get delimiter
		$d = $match[1];

		// lookup used to check if we should take a different ending delimiter
		$lookup = array(
			'{' => '}',
			'[' => ']',
			'(' => ')',
			'<' => '>',
		);

		if (isset($lookup[$d]))
		{
			$d = $lookup[$d];
		}

		// make sure the regex ends with the delimiter found
		return (bool) preg_match("/\\{$d}[gimsxU]*$/", $str);
	}

	/**
	 * Helper method used to check whether the system supports the coupon codes, simply
	 * by checking whether the coupons database table contains at least a record.
	 *
	 * @param 	string   $applicable  The section to which the coupon should be
	 *                                applied (@since 1.7).
	 *
	 * @return 	boolean
	 */
	public static function hasCoupon($applicable = null)
	{
		$dbo = JFactory::getDbo();

		// Check if there is at least a coupon stored in the system.
		// It is not needed to check if it is valid because we have just 
		// to know if the owner used them, so that the system can display 
		// a form to redeem the coupons or not.
		$q = $dbo->getQuery(true)
			->select(1)
			->from($dbo->qn('#__vikappointments_coupon'));

		if ($applicable)
		{
			$q->where(array(
				$dbo->qn('applicable') . ' IS NULL',
				$dbo->qn('applicable') . ' = ' . $dbo->q(''),
				$dbo->qn('applicable') . ' = ' . $dbo->q($applicable),
			), 'OR');
		}

		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		return (bool) $dbo->getNumRows();
	}
	
	/**
	 * Validates the given coupon code.
	 *
	 * @param 	array    $coupon  The coupon details.
	 * @param 	mixed    $cart 	  The cart instance. When this method is called
	 *                            from the back-end, this argument will be empty.
	 *
	 * @return 	boolean  True if the coupon can be redeemed, false otherwise.
	 */
	public static function validateCoupon($coupon, $cart = null)
	{
		// always treat as array
		$coupon = (array) $coupon;

		/**
		 * Check whether the coupon code is applicable for the appointments.
		 *
		 * @since 1.7
		 */
		if (!empty($coupon['applicable']) && $coupon['applicable'] != 'appointments')
		{
			return false;
		}

		/**
		 * Treat dates in UTC.
		 *
		 * @since 1.7
		 */
		$now = JDate::getInstance()->toSql();
		
		if ($cart)
		{
			$items = $cart->getItemsList();
		}
		else
		{
			$items = array();
		}

		if ($coupon['type'] == 2 && $coupon['max_quantity'] - $coupon['used_quantity'] <= 0)
		{
			// reached the maximum number of usages
			return false;
		}

		/**
		 * Check whether the current user should be able to redeem the coupon one more time.
		 * Go ahead only in case the cart argument is set, in order to bypass this restriction
		 * while validating the coupon code from the back-end.
		 *
		 * @since 1.7
		 */
		if ($coupon['maxperuser'] > 0 && $cart)
		{
			// get current user
			$user = JFactory::getUser();

			if ($user->guest)
			{
				// when a maximum amount is specified, the coupon can be redeemed only by logged-in users
				return false;
			}

			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select('COUNT(1)')
				->from($dbo->qn('#__vikappointments_reservation', 'r'))
				->leftjoin($dbo->qn('#__vikappointments_users', 'u') . ' ON ' . $dbo->qn('r.id_user') . ' = ' . $dbo->qn('u.id'))
				->where($dbo->qn('r.coupon_str') . ' LIKE ' . $dbo->q($coupon['code'] . ';;%'))
				->andWhere(array(
					$dbo->qn('r.createdby') . ' = ' . $user->id,
					$dbo->qn('u.jid') . ' = ' . $user->id,
				), 'OR');

			$dbo->setQuery($q);
			
			// compare the number of usages against the maximum limit
			if ((int) $dbo->loadResult() >= $coupon['maxperuser'])
			{
				// the user already redeemed the coupon all the allowed times
				return false;
			}
		}

		/**
		 * Validate publishing dates using specified mode.
		 *
		 * @since 1.6.3
		 */
		if (!VAPDateHelper::isNull($coupon['dstart']) || !VAPDateHelper::isNull($coupon['dend']))
		{
			if ($coupon['pubmode'] == 1 || !$items)
			{
				// compare current day with starting date (if specified)
				if (!VAPDateHelper::isNull($coupon['dstart']) && $coupon['dstart'] > $now)
				{
					// the coupon is not yet valid
					return false;
				}

				// compare current day with ending date (if specified)
				if (!VAPDateHelper::isNull($coupon['dend']) && $coupon['dend'] < $now)
				{
					// the coupon is expired
					return false;
				}
			}
			else
			{
				// all items must match the specified dates
				foreach ($items as $i)
				{
					// get appointment check-in
					$checkin = $i->getCheckinDate();

					// compare check-in with starting date (if specified)
					if (!VAPDateHelper::isNull($coupon['dstart']) && $coupon['dstart'] > $checkin)
					{
						// the coupon is not yet valid
						return false;
					}

					// compare check-in with ending date (if specified)
					if (!VAPDateHelper::isNull($coupon['dend']) && $coupon['dend'] < $checkin)
					{
						// the coupon is expired
						return false;
					}
				}
			}
		}

		/**
		 * Re-added the minimum cost condition that had
		 * been accidentally removed.
		 *
		 * @since 1.6.5
		 */
		if ($cart && $cart->getTotalCost() < $coupon['mincost'])
		{
			// total cost is too low
			return false;
		}

		if ($items)
		{
			$coupon_services  = self::getAllCouponServices($coupon['id']);
			$coupon_employees = self::getAllCouponEmployees($coupon['id']);

			$ok_coupon_service  = true;
			$ok_coupon_employee = true;
			
			foreach ($items as $i)
			{
				$ok_coupon_service  = $ok_coupon_service  && (count($coupon_services)  == 0 || in_array($i->getServiceID() , $coupon_services));
				$ok_coupon_employee = $ok_coupon_employee && (count($coupon_employees) == 0 || in_array($i->getEmployeeID(), $coupon_employees));
			}

			if (!$ok_coupon_service || !$ok_coupon_employee)
			{
				return false;
			}
		}

		if ($coupon['lastminute'])
		{
			// get last minute threshold
			$threshold = JDate::getInstance('+' . $coupon['lastminute'] . ' hours')->toSql();
			
			foreach ($items as $i)
			{
				// get appointment check-in
				$checkin = $i->getCheckinDate();

				if ($threshold < $checkin)
				{
					return false;
				}
			}
		}

		/**
		 * This event can be used to apply additional conditions to the coupon validation.
		 * When this event is triggered, the system already validated the standard conditions
		 * and the coupon has been approved for the usage.
		 *
		 * @param 	string   $scope   For which entity we are redeeming the coupon.
		 * @param 	array    $coupon  The coupon code to check.
		 * @param 	mixed    $cart    The cart instance.
		 *
		 * @return 	boolean  Return false to deny the coupon activation.
		 *
		 * @since 	1.7
		 */
		if (VAPFactory::getEventDispatcher()->false('onBeforeActivateCoupon', array('appointment', $coupon, $cart)))
		{
			// a plugin decided to deny the coupon activation
			return false;
		}

		return true;	
	}

	/**
	 * Validates the given coupon code (for packages purchase).
	 *
	 * @param 	array    $coupon  The coupon details.
	 * @param 	mixed    $cart 	  The cart instance. When this method is called
	 *                            from the back-end, this argument will be empty.
	 *
	 * @return 	boolean  True if the coupon can be redeemed, false otherwise.
	 *
	 * @since 	1.7
	 */
	public static function validatePackagesCoupon($coupon, $cart = null)
	{
		// always treat as array
		$coupon = (array) $coupon;

		/**
		 * Check whether the coupon code is applicable for the packages.
		 *
		 * @since 1.7
		 */
		if (!empty($coupon['applicable']) && $coupon['applicable'] != 'packages')
		{
			return false;
		}

		$now = JDate::getInstance()->toSql();

		if ($coupon['type'] == 2 && $coupon['max_quantity'] - $coupon['used_quantity'] <= 0)
		{
			// reached the maximum number of usages
			return false;
		}

		// Check whether the current user should be able to redeem the coupon one more time.
		// Go ahead only in case the cart argument is set, in order to bypass this restriction
		// while validating the coupon code from the back-end.
		if ($coupon['maxperuser'] > 0 && $cart)
		{
			// get current user
			$user = JFactory::getUser();

			if ($user->guest)
			{
				// when a maximum amount is specified, the coupon can be redeemed only by logged-in users
				return false;
			}

			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select('COUNT(1)')
				->from($dbo->qn('#__vikappointments_package_order', 'o'))
				->leftjoin($dbo->qn('#__vikappointments_users', 'u') . ' ON ' . $dbo->qn('o.id_user') . ' = ' . $dbo->qn('u.id'))
				->where($dbo->qn('o.coupon') . ' LIKE ' . $dbo->q($coupon['code'] . ';;%'))
				->andWhere(array(
					$dbo->qn('o.createdby') . ' = ' . $user->id,
					$dbo->qn('u.jid') . ' = ' . $user->id,
				), 'OR');

			$dbo->setQuery($q);
			
			// compare the number of usages against the maximum limit
			if ((int) $dbo->loadResult() >= $coupon['maxperuser'])
			{
				// the user already redeemed the coupon all the allowed times
				return false;
			}
		}

		// validate publishing dates using specified mode
		if (!VAPDateHelper::isNull($coupon['dstart']) || !VAPDateHelper::isNull($coupon['dend']))
		{
			// compare current day with starting date (if specified)
			if (!VAPDateHelper::isNull($coupon['dstart']) && $coupon['dstart'] > $now)
			{
				// the coupon is not yet valid
				return false;
			}

			// compare current day with ending date (if specified)
			if (!VAPDateHelper::isNull($coupon['dend']) && $coupon['dend'] < $now)
			{
				// the coupon is expired
				return false;
			}
		}

		if ($cart && $cart->getTotalCost() < $coupon['mincost'])
		{
			// total cost is too low
			return false;
		}

		/**
		 * This event can be used to apply additional conditions to the coupon validation.
		 * When this event is triggered, the system already validated the standard conditions
		 * and the coupon has been approved for the usage.
		 *
		 * @param 	string   $scope   For which entity we are redeeming the coupon.
		 * @param 	array    $coupon  The coupon code to check.
		 * @param 	mixed    $cart    The cart instance.
		 *
		 * @return 	boolean  Return false to deny the coupon activation.
		 *
		 * @since 	1.7
		 */
		if (VAPFactory::getEventDispatcher()->false('onBeforeActivateCoupon', array('package', $coupon, $cart)))
		{
			// a plugin decided to deny the coupon activation
			return false;
		}

		return true;	
	}

	/**
	 * Validates the given coupon code (for subscriptions purchase).
	 *
	 * @param 	array    $coupon  The coupon details.
	 * @param 	JModel   $model   The cart model instance. When this method is called
	 *                            from the back-end, this argument will be empty.
	 *
	 * @return 	boolean  True if the coupon can be redeemed, false otherwise.
	 *
	 * @since 	1.7
	 */
	public static function validateSubscriptionsCoupon($coupon, $cart = null)
	{
		// always treat as array
		$coupon = (array) $coupon;

		/**
		 * Check whether the coupon code is applicable for the subscriptions.
		 *
		 * @since 1.7
		 */
		if (!empty($coupon['applicable']) && $coupon['applicable'] != 'subscriptions')
		{
			return false;
		}

		$now = JDate::getInstance()->toSql();

		if ($coupon['type'] == 2 && $coupon['max_quantity'] - $coupon['used_quantity'] <= 0)
		{
			// reached the maximum number of usages
			return false;
		}

		// Check whether the current user should be able to redeem the coupon one more time.
		// Go ahead only in case the cart argument is set, in order to bypass this restriction
		// while validating the coupon code from the back-end.
		if ($coupon['maxperuser'] > 0 && $cart)
		{
			// get current user
			$user = JFactory::getUser();

			if ($user->guest)
			{
				// when a maximum amount is specified, the coupon can be redeemed only by logged-in users
				return false;
			}

			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select('COUNT(1)')
				->from($dbo->qn('#__vikappointments_package_order', 'o'))
				->leftjoin($dbo->qn('#__vikappointments_users', 'u') . ' ON ' . $dbo->qn('o.id_user') . ' = ' . $dbo->qn('u.id'))
				->where($dbo->qn('o.coupon') . ' LIKE ' . $dbo->q($coupon['code'] . ';;%'))
				->andWhere(array(
					$dbo->qn('o.createdby') . ' = ' . $user->id,
					$dbo->qn('u.jid') . ' = ' . $user->id,
				), 'OR');

			$dbo->setQuery($q);
			
			// compare the number of usages against the maximum limit
			if ((int) $dbo->loadResult() >= $coupon['maxperuser'])
			{
				// the user already redeemed the coupon all the allowed times
				return false;
			}
		}

		// validate publishing dates using specified mode
		if (!VAPDateHelper::isNull($coupon['dstart']) || !VAPDateHelper::isNull($coupon['dend']))
		{
			// compare current day with starting date (if specified)
			if (!VAPDateHelper::isNull($coupon['dstart']) && $coupon['dstart'] > $now)
			{
				// the coupon is not yet valid
				return false;
			}

			// compare current day with ending date (if specified)
			if (!VAPDateHelper::isNull($coupon['dend']) && $coupon['dend'] < $now)
			{
				// the coupon is expired
				return false;
			}
		}

		if ($cart)
		{
			// get selected subscription
			$subscr = $cart->getSubscription();

			// make sure the base cost of the subscription is equals or higher than
			// the coupon minimum threshold
			if ($subscr['price'] < $coupon['mincost'])
			{
				// total cost is too low
				return false;
			}
		}

		/**
		 * This event can be used to apply additional conditions to the coupon validation.
		 * When this event is triggered, the system already validated the standard conditions
		 * and the coupon has been approved for the usage.
		 *
		 * @param 	string   $scope   For which entity we are redeeming the coupon.
		 * @param 	array    $coupon  The coupon code to check.
		 * @param 	mixed    $cart    The cart instance.
		 *
		 * @return 	boolean  Return false to deny the coupon activation.
		 *
		 * @since 	1.7
		 */
		if (VAPFactory::getEventDispatcher()->false('onBeforeActivateCoupon', array('subscription', $coupon, $cart)))
		{
			// a plugin decided to deny the coupon activation
			return false;
		}

		return true;	
	}

	/**
	 * Returns all the services assigned to the specified coupon.
	 *
	 * @param 	integer  $id_coupon  The coupon ID.
	 *
	 * @return 	array 	 A list containing the ID of the assigned services.
	 */
	public static function getAllCouponServices($id_coupon)
	{
		$services = array();
		
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn('id_service'))
			->from($dbo->qn('#__vikappointments_coupon_service_assoc'))
			->where($dbo->qn('id_coupon') . ' = ' . (int) $id_coupon);

		$dbo->setQuery($q);
		return $dbo->loadColumn();
	}

	/**
	 * Returns all the employees assigned to the specified coupon.
	 *
	 * @param 	integer  $id_coupon  The coupon ID.
	 *
	 * @return 	array 	 A list containing the ID of the assigned employees.
	 */
	public static function getAllCouponEmployees($id_coupon)
	{
		$employees = array();
		
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn('id_employee'))
			->from($dbo->qn('#__vikappointments_coupon_employee_assoc'))
			->where($dbo->qn('id_coupon') . ' = ' . (int) $id_coupon);

		$dbo->setQuery($q);
		return $dbo->loadColumn();
	}

	/**
	 * Marks the specified coupon as used.
	 * In addition, removes the coupon if it should be deleted once
	 * the maximum number of usages is reached.
	 * 
	 * @param 	array 	 $coupon  The coupon details.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 *
	 * @deprecated 1.8   Use VikAppointmentsModelCoupon::redeem() instead.
	 */
	public static function couponUsed($coupon, $dbo = null)
	{
		return JModelVAP::getInstance('coupon')->redeem($coupon);
	}
	
	/**
	 * Validates the specified recurring data.
	 *
	 * @param 	integer  $repeat  The repeat by identifier.
	 * @param 	integer  $amount  The selected amount.
	 * @param 	integer  $for 	  The repeat for identifier.
	 *
	 * @return 	boolean  True if valid, false otherwise.
	 */
	public static function validateRecurringData($repeat, $amount, $for)
	{
		if (!VAPFactory::getConfig()->getBool('enablerecur'))
		{
			return false;
		}
		
		$params = self::getRecurrenceParams();
		
		if (($repeat - 1) < 0 || ($repeat - 1) >= count($params['repeat']) || $params['repeat'][$repeat - 1] == 0)
		{
			return false;
		}
		
		if (($for - 1) < 0 || ($for - 1) >= count($params['for']) || $params['for'][$for - 1] == 0)
		{
			return false;
		}
		
		if ($amount < $params['min'] || $params['max'] < $amount)
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * Calculates the discounted total cost considering the coupon code and
	 * the user credit (if specified).
	 *
	 * @param 	float 	$total_cost 	The base total cost.
	 * @param 	array 	$coupon 		The coupon code.
	 * @param 	mixed 	&$credit 		The current user credit. Provide true
	 * 									to retrieve the user credit from the database.
	 * @param 	float 	&$creditUsed 	The credit amount that has been used.
	 *
	 * @return 	float 	The final discounted total cost.
	 */
	public static function getDiscountTotalCost($total_cost, $coupon, &$credit = false, &$creditUsed = 0)
	{
		if (!empty($coupon))
		{
			if ($coupon['percentot'] == 1)
			{
				// percent
				$total_cost -= $total_cost * $coupon['value'] / 100.0;
			}
			else
			{
				// total
				$total_cost -= $coupon['value'];
			}
		}

		/**
		 * If the credit is specified, use it.
		 *
		 * @since 1.6
		 */
		if ($credit === true)
		{
			$user 	= JFactory::getUser();
			$credit = 0.0;

			if (!$user->guest)
			{
				$dbo = JFactory::getDbo();

				$q = $dbo->getQuery(true)
					->select($dbo->qn('credit'))
					->from($dbo->qn('#__vikappointments_users'))
					->where($dbo->qn('jid') . ' = ' . $user->id)
					->orWhere(array(
						$dbo->qn('jid') . ' <= 0',
						$dbo->qn('billing_mail') . ' = ' . $dbo->q($user->email),
					), 'AND');

				$dbo->setQuery($q, 0, 1);
				$credit = (float) $dbo->loadResult();
			}
		}

		if ($credit && $total_cost > 0)
		{
			if ($credit > $total_cost)
			{
				$creditUsed = $total_cost;
			}
			else
			{
				$creditUsed = $credit;
			}

			$total_cost -= $credit;
		}
		
		return max(array($total_cost, 0));
	}
	
	/**
	 * Returns the deposit amount that should be left.
	 *
	 * @param 	float 	 $total_cost 	The total cost of the order.
	 * @param 	boolean  $ignore 		True to skip the deposit calculation.
	 * 									It should be verified when the customer decides
	 * 									to pay the full amount (only for OPTIONAL mode).
	 *
	 * @return 	mixed 	 The new amount if the deposit should be left, otherwise false.
	 */
	public static function getDepositAmountToLeave($total_cost, $ignore = false)
	{
		$config = VAPFactory::getConfig();

		$use = $config->getUint('usedeposit');

		if (!$use)
		{
			// [NO] do not use deposit
			return false;
		}

		if ($use == 1 && $ignore)
		{
			// [OPTIONAL] the customer decided to pay the full amount
			return false;
		}

		$deposit_after 	= $config->getFloat('depositafter', 0);
		$deposit_value 	= $config->getFloat('depositvalue', 0);
		$deposit_type 	= $config->getUint('deposittype', 1);
		
		// make sure the condition is verified
		if ($total_cost > $deposit_after)
		{
			if ($deposit_type == 1)
			{
				// percent
				return round($total_cost * $deposit_value / 100.0, 2);
			}
			else
			{
				// total
				return $deposit_value;
			}
		}
		
		// the total cost is still lower than the minimum required
		return false;
	}

	/**
	 * Returns all the ZIP codes of the given employee.
	 *
	 * @param 	integer  $id_employee 	The employee ID.
	 *
	 * @return 	mixed 	 The ZIP codes array if any, false otherwise.
	 */
	public static function getEmployeeZipCodes($id_employee)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn('zipcodes'))
			->from($dbo->qn('#__vikappointments_employee_settings'))
			->where($dbo->qn('id_employee') . ' = ' . (int) $id_employee);

		$dbo->setQuery($q, 0, 1);
		$zips = $dbo->loadResult();

		return $zips ? json_decode($zips, true) : false;
	}

	/**
	 * Returns the ID of the custom field that will be used to validate the ZIP code.
	 *
	 * Tthis method will check also whether the selected services require a ZIP validation.
	 * This way, we can prevent the blocking issue that occurred when the ZIP restriction
	 * was enabled and the configuration didn't specify a field to validate the entered
	 * ZIP Code (@since 1.7).
	 *
	 * @param 	integer  $id_employee  The employee ID to search for in case ths
	 *                                 global field is not set.
	 * @param 	mixed    $services     Either an array or a service ID. When specified
	 *                                 the system will make sure that the selected
	 *                                 services requires the ZIP restriction (@since 1.7).
	 *
	 * @return 	mixed    The field ID if specified, false otherwise.
	 */
	public static function getZipCodeValidationFieldId($id_employee = null, $services = array())
	{
		// get global setting
		$id_field = VAPFactory::getConfig()->getInt('zipcfid');

		if ($id_field <= 0 && $id_employee > 0)
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn('zip_field_id'))
				->from($dbo->qn('#__vikappointments_employee_settings'))
				->where($dbo->qn('id_employee') . ' = ' . (int) $id_employee);

			$dbo->setQuery($q, 0, 1);
			$id_field = $dbo->loadResult();
		}

		if ($id_field <= 0)
		{
			// no field found
			return false;
		}

		// when specified, validate the services
		if (!$services)
		{
			// nothing else to validate
			return $id_field;
		}
		
		if (!is_array($services))
		{
			$services = (array) $services;
		}

		// get service model
		$model = JModelVAP::getInstance('service');

		foreach ($services as $id_service)
		{
			// check whether the service requires a ZIP validation
			if ($model->hasZipRestriction($id_service))
			{
				// yes, we can return the field found
				return $id_field;
			}
		}

		// the booked services do not require the ZIP validation
		return false;
	}
	
	/**
	 * Helper method used to validate the specified ZIP code.
	 *
	 * @param 	string   $zip_code   The specified ZIP code.
	 * @param 	mixed    $employees  Either an array or an employee ID.
	 * @param 	mixed    $services   Either an array or a service ID. When specified
	 *                               the system will make sure that the selected
	 *                               services requires the ZIP restriction (@since 1.7).
	 *
	 * @return 	boolean  True if valid, false otherwise.
	 *
	 * @uses 	getZipCodeValidationFieldId()
	 */
	public static function validateZipCode($zip_code, $employees, $services = array())
	{
		if (!$employees)
		{
			$employees = array(0);
		}
		else if (!is_array($employees))
		{
			$employees = (array) $employees;
		}

		// check whether the ZIP validation is required for the selected employee and service
		$id_field = self::getZipCodeValidationFieldId($employees[0], $services);

		if (!$id_field)
		{
			// ZIP code validation not needed
			return true;	
		}

		if (empty($zip_code))
		{
			/**
			 * Try to recover the ZIP code from the request by using the
			 * name of the custom field.
			 *
			 * @since 1.7
			 */
			$zip_code = JFactory::getApplication()->input->getString('vapcf' . $id_field);
		}
		
		if (empty($zip_code))
		{
			// empty ZIP code, nothing to validate
			return false;
		}

		// make ZIP code uppercase for a better validation
		$zip_code = strtoupper($zip_code);
		// accept only letters and digits
		$zip_code = preg_replace('/[^A-Z0-9]/i', '', $zip_code);

		$global_zips = VAPFactory::getConfig()->getArray('zipcodes', array());

		$dispatcher = VAPFactory::getEventDispatcher();

		foreach ($employees as $id_emp)
		{
			$args = false;

			if ($id_emp > 0)
			{
				// get ZIP codes specified by the employee
				$args = self::getEmployeeZipCodes($id_emp);
			}

			if (!$args)
			{
				// use global ZIP codes
				$args = $global_zips;
			}

			$valid = false;

			/**
			 * It is possible to use this hook to enhance or change the default algorithm
			 * while checking whether a specific ZIP code is allowed or not.
			 *
			 * @param 	string 	 $zip       The ZIP code to validate.
			 * @param 	array    $accepted  An array of accepted ZIP codes.
			 * @param   integer  $id_emp    The employee ID (0 or -1 mean global).
			 * @param 	array    $services  An array of booked services.
			 *
			 * @return 	boolean  Return true to accept the ZIP code. Return false to deny the
			 *                   ZIP Code. Return null to rely on the default algorithm.
			 *
			 * @since 	1.7
			 */
			$result = $dispatcher->falseOrTrue('onValidateZipCode', array($zip_code, $args, $id_emp, $services));

			if (!is_null($result))
			{
				// a plugin validated the ZIP code, use its decision
				return $result;
			}

			// go ahead with the defaul algorithm
			for ($i = 0; $i < count($args) && !$valid; $i++)
			{
				if ($args[$i]['from'] <= $zip_code && $zip_code <= $args[$i]['to'])
				{
					$valid = true;
				}
			}

			if (!$valid)
			{
				// ZIP code not accepted by this employee
				return false;
			}
		}
	
		// the selected ZIP code is accepted by all the selected employees
		return true;
	}
	
	/**
	 * Helper method used to format a UNIX timestamp to the closest unit.
	 * In case there is not a close unit, the specified date format will be used.
	 *
	 * @param 	string 	 $dt_f 	The date format.
	 * @param 	integer  $ts 	The timestamp to format.
	 *
	 * @return 	string 	 The formatted date.
	 */
	public static function formatTimestamp($dt_f, $ts)
	{	
		$diff = time() - $ts;

		if (abs($diff) < 60)
		{
			return JText::translate('VAPDFNOW');
		}
		
		$minutes = abs($diff) / 60;

		if ($minutes < 60)
		{
			return JText::sprintf('VAPDFMINS' . ($diff > 0 ? 'AGO' : 'AFT'), floor($minutes));
		}
		
		$hours = $minutes / 60;

		if ($hours < 24)
		{
			$hours = floor($hours);

			if ($hours == 1)
			{
				return JText::translate('VAPDFHOUR' . ($diff > 0 ? 'AGO' : 'AFT'));
			}

			return JText::sprintf('VAPDFHOURS' . ($diff > 0 ? 'AGO' : 'AFT'), $hours);
		}
		
		$days = $hours / 24;

		if ($days < 7)
		{
			$days = floor($days);

			if ($days == 1)
			{
				return JText::translate('VAPDFDAY' . ($diff > 0 ? 'AGO' : 'AFT'));
			}

			return JText::sprintf('VAPDFDAYS' . ($diff > 0 ? 'AGO' : 'AFT'), $days);
		}
		
		$weeks = $days / 7;

		if ($weeks < 3)
		{
			$weeks = floor($weeks);

			if ($weeks == 1)
			{
				return JText::translate('VAPDFWEEK' . ($diff > 0 ? 'AGO' : 'AFT'));
			}

			return JText::sprintf('VAPDFWEEKS'.($diff > 0 ? 'AGO' : 'AFT'), $weeks);
		}
		
		return date($dt_f, $ts);
	}
	
	/**
	 * Helper method to format the specified minutes to the closest unit.
	 * For example, 150 minutes will be formatted as "1 hour & 30 min.".
	 *
	 * @param 	string 	 $minutes 	The minutes amount.
	 * @param 	boolean  $apply 	True to format, false to return it plain.
	 *
	 * @return 	string 	 The formatted string.
	 */
	public static function formatMinutesToTime($minutes, $apply = null)
	{
		$min_str = array(
			JText::translate('VAPSHORTCUTMINUTE'), 	// singular
			'', 							// plural
		);

		/**
		 * If not specified, rely on global setting.
		 *
		 * @since 1.7
		 */
		if (is_null($apply))
		{
			$apply = VAPFactory::getConfig()->getBool('formatduration');
		}
		
		if (!$apply)
		{
			return $minutes . ' ' . $min_str[0];
		}
		
		$hours_str = array(
			JText::translate('VAPFORMATHOUR'), 	// singular
			JText::translate('VAPFORMATHOURS'), // plural
		);

		$days_str  = array(
			JText::translate('VAPFORMATDAY'), 	// singular
			JText::translate('VAPFORMATDAYS'), 	// plural
		);

		$weeks_str = array(
			JText::translate('VAPFORMATWEEK'), 	// singular
			JText::translate('VAPFORMATWEEKS'), // plural
		);
		
		$comma_char = JText::translate('VAPFORMATCOMMASEP');
		$and_char 	= JText::translate('VAPFORMATANDSEP');
		
		$is_negative = $minutes < 0 ? 1 : 0;
		$minutes = abs($minutes);
		
		$format = "";

		while ($minutes >= 60)
		{
			$app_str = "";

			if ($minutes >= 10080)
			{
				// weeks
				$val = floor($minutes / 10080);

				$app_str = $val . ' ' . $weeks_str[(int) ($val > 1)]; // if greater than 1 then plural, otherwise singular
				$minutes = $minutes % 10080;
			} 
			else if ($minutes >= 1440)
			{
				// days
				$val = floor($minutes / 1440);

				$app_str = $val . ' ' . $days_str[(int) ($val > 1)]; // if greater than 1 then plural, otherwise singular
				$minutes = $minutes % 1440;
			}
			else
			{
				// hours
				$val = floor($minutes / 60);

				$app_str = $val . ' ' . $hours_str[(int) ($val > 1)]; // if greater than 1 then plural, otherwise singular
				$minutes = $minutes % 60;
			}
			
			$sep = '';

			if ($minutes > 0)
			{
				$sep = $comma_char;
			}
			else if ($minutes == 0)
			{
				$sep = " $and_char";
			}
			
			$format .= (!empty($format) ? $sep . ' ' : '') . $app_str;
		}
		
		if ($minutes > 0)
		{
			$format .= (!empty($format) ? " $and_char " : '') . $minutes . ' ' . $min_str[0];
		}
		
		if ($is_negative)
		{
			$format = '-' . $format;
		}
			
		return $format;
	}

	/**
	 * Helper method used to format the checkin timestamp.
	 * It may return all the following values:
	 * - today 		In case the checkin is for the current day (e.g. today in 2 hours).
	 * - tomorrow 	In case the checkin is for the next day (e.g. tomorrow @ 10:00).
	 * - datetime 	A formatted datetime (e.g. 2018-07-28 @ 10:00).
	 *
	 * @param 	string 	 $dt_f 	The default date format.
	 * @param 	string 	 $t_f 	The default time format.
	 * @param 	integer  $ts 	The checkin timestamp.
	 *
	 * @return 	string 	 The formatted checkin.
	 *
	 * @uses 	formatMinutesToTime()
	 */
	public static function formatCheckinTimestamp($dt_f, $t_f, $ts)
	{
		$today = getdate();
		$date  = getdate($ts);
		$diff  = $date[0] - $today[0];

		$today_no_time  = strtotime('00:00:00');
		$date_no_time   = strtotime('00:00:00', $ts);
		$diff_no_time  	= $date_no_time - $today_no_time;

		if ($diff > 0 && $diff_no_time >= -3600 && $diff_no_time <= 3600)
		{
			return JText::sprintf('VAPTODAYIN', self::formatMinutesToTime(ceil($diff / 60)));
		}
		else if ($diff_no_time >= 82800 && $diff_no_time <= 90000)
		{
			return JText::sprintf('VAPTOMORROWAT', date($t_f, $ts));
		}
		
		return date($dt_f, $ts);
	}
	
	/**
	 * Helper method used to render the contents of HTML descriptions.
	 *
	 * @param 	string 	$description 	The description to render.
	 * @param 	string 	$task 			The view/task that invoked this method.
	 * @param 	array 	$params 		An array of options.
	 *
	 * @return 	string 	The rendered description.
	 */
	public static function renderHtmlDescription($description, $task, $params = array())
	{
		$dispatcher = VAPFactory::getEventDispatcher();
		$dispatcher->import('content');

		$content = JTable::getInstance('content');
		$content->text = $description;

		$lookup = array(
			'employeeslist'	 => 0, // short
			'employeesearch' => 1, // full
			'serviceslist'	 => 0, // short
			'servicesearch'	 => 1, // full
			'microdata' 	 => 0, // short
			'paymentconfirm' => 0, // short
			'paymentorder'   => 1, // full			
		);

		// checks if the task should use the short or full description
		$full = !empty($lookup[$task]);

		/**
		 * In case of e-mail custom text we should route
		 * any URLs for being used externally by prepending
		 * the base domain.
		 * 
		 * @since 1.6.5
		 */
		if ($task == 'custmail')
		{
			// look for any src/href attributes
			$content->text = preg_replace_callback("/\s*(src|href)=([\"'])(.*?)[\"']/i", function($match)
			{
				// check if the URL starts with the base domain
				if (stripos($match[3], JUri::root()) !== 0 && !preg_match("/^(https?:\/\/|www\.)/i", $match[3]))
				{
					// prepend base domain to URL
					$match[0] = ' ' . $match[1] . '=' . $match[2] . JUri::root() . $match[3] . $match[2];
				}
				
				return $match[0];
			}, $content->text);
		}

		/**
		 * Lets the platform handler prepares the content.
		 *
		 * @since 1.6.3
		 */
		VAPApplication::getInstance()->onContentPrepare($content, $full);
		
		return $content->text;
	}

	/**
	 * Extracts and renders the short description from the specified argument.
	 * In case the HTML do not use a READ MORE separator, the short description 
	 * will be created at runtime by taking a substring of the whole content.
	 *
	 * @param 	string 	 $description  The description to render.
	 * @param 	integer  $maxlen       The maximum length of characters. If not
	 *                                 specified, up to 256 chars will be taken.
	 *
	 * @return 	string   The resulting description.
	 *
	 * @since 	1.7
	 */
	public static function renderShortHtmlDescription($description, $maxlen = null)
	{
		// render HTML description
		VAPApplication::getInstance()->onContentPrepare($description);

		// in case we have a short description, use it without taking a substrinh
		if (!$description->introtext)
		{
			// check whether the plain text exceeds the maximum number of characters
			$plain = strip_tags($description->text);
			
			if (mb_strlen($plain, 'UTF-8') > $maxlen) 
			{
				// The length of the description exceeded the maximum amount.
				// We need to display a substring of the description by stripping
				// all the HTML tags to avoid breaking the whole code.
				$description->introtext = mb_substr($plain, 0, $maxlen, 'UTF-8');
				// trim any ending space and dots to properly concat the ellipsis
				$description->introtext = rtrim($description->introtext, '. ') . '...';
			}
			else
			{
				// not exceeding length, use it in full
				$description->introtext = $description->text;
			}
		}

		return $description->introtext;
	}
	
	/**
	 * Loads the main assets (CSS and JS) of the component.
	 *
	 * @return 	void
	 */
	public static function load_css_js()
	{
		$vik = VAPApplication::getInstance();

		$options = array(
			'version' => VIKAPPOINTMENTS_SOFTWARE_VERSION,
		);

		// since jQuery is a required dependency, the framework should be 
		// invoked even if jQuery is disabled
		$vik->loadFramework('jquery.framework');
		
		$vik->addScript(VAPASSETS_URI . 'js/jquery-ui.min.js');
		$vik->addScript(VAPASSETS_URI . 'js/vikappointments.js', $options);

		/**
		 * Load the CSS file containing the environment variables.
		 * 
		 * @since 1.7.2
		 */
		JHtml::fetch('vaphtml.assets.environment');
		
		$vik->addStyleSheet(VAPASSETS_URI . 'css/jquery-ui.min.css');
		$vik->addStyleSheet(VAPASSETS_URI . 'css/vikappointments.css', $options);
		$vik->addStyleSheet(VAPASSETS_URI . 'css/vikappointments-mobile.css', $options);
		$vik->addStyleSheet(VAPASSETS_URI . 'css/input-select.css', $options);

		/**
		 * Include adapter to adjust some layouts according to the current platform version.
		 *
		 * @since 1.7
		 */
		if (VersionListener::isJoomla3x())
		{
			$vik->addStyleSheet(VAPASSETS_URI . 'css/adapter/J30.css');
		}
		else if (VersionListener::isJoomla4x())
		{
			$vik->addStyleSheet(VAPASSETS_URI . 'css/adapter/J40.css');
		}
	
		/**
		 * Adjust component layout to fit the specified theme.
		 *
		 * @since 1.6
		 */
		$theme = VAPFactory::getConfig()->get('sitetheme');

		if ($theme)
		{
			$vik->addStyleSheet(VAPASSETS_URI . 'css/themes/' . $theme . '.css', $options);
		}

		/**
		 * Loads the custom CSS file.
		 * 
		 * @since 1.7.2  Moved in a specified helper function.
		 */
		JHtml::fetch('vaphtml.assets.customcss');

		/**
		 * Loads utils.
		 *
		 * @since 1.7
		 */
		JHtml::fetch('vaphtml.assets.utils');

		/**
		 * Always instantiate the currency object.
		 *
		 * @since 1.7
		 */
		JHtml::fetch('vaphtml.assets.currency');

		/**
		 * Auto set CSRF token to ajaxSetup so all jQuery ajax call will contain CSRF token.
		 *
		 * @since 1.7
		 */
		JHtml::fetch('vaphtml.sitescripts.ajaxcsrf');
	}
	
	/**
	 * Loads the scripts needed to use Select2 jQuery plugin.
	 *
	 * @return 	void
	 *
	 * @deprecated 1.8  Use VAPHtmlAssets::select2() instead.
	 */
	public static function load_complex_select()
	{
		JHtml::fetch('vaphtml.assets.select2');
	}

	/**
	 * Loads the stylesheets needed to use Font Awesome.
	 *
	 * @return 	void
	 *
	 * @deprecated 1.8  Use VAPHtmlAssets::fontawesome() instead.
	 */
	public static function load_font_awesome()
	{
		JHtml::fetch('vaphtml.assets.fontawesome');
	}
	
	/**
	 * Loads the scripts needed to use Chart JS jQuery plugin.
	 *
	 * @return 	void
	 *
	 * @deprecated 1.8  Use VAPHtmlAssets::chartjs() instead.
	 */
	public static function load_charts()
	{	
		JHtml::fetch('vaphtml.assets.chartjs');
	}
	
	/**
	 * Loads the scripts needed to use Fancybox jQuery plugin.
	 *
	 * @return 	void
	 *
	 * @deprecated 1.8  Use VAPHtmlAssets::fancybox() instead.
	 */
	public static function load_fancybox()
	{
		JHtml::fetch('vaphtml.assets.fancybox');
	}
	
	/**
	 * Loads the scripts needed to use Google Maps javascript framework.
	 * Requires a valid Google API Key.
	 *
	 * @return 	void
	 *
	 * @deprecated 1.8  Use VAPHtmlAssets::googlemaps() instead.
	 */
	public static function load_googlemaps()
	{
		JHtml::fetch('vaphtml.assets.googlemaps');
	}

	/**
	 * Loads the scripts needed to use Colorpicker jQuery plugin.
	 *
	 * @return 	void
	 *
	 * @since 	   1.6
	 * @deprecated 1.8  Use VAPHtmlAssets::colorpicker() instead.
	 */
	public static function load_colorpicker()
	{
		JHtml::fetch('vaphtml.assets.colorpicker');
	}

	/**
	 * Loads the javascript utils.
	 *
	 * @param 	array  $options  A list of options for the scripts to load.
	 *
	 * @return 	void
	 *
	 * @since 	   1.6
	 * @deprecated 1.8  Use VAPHtmlAssets::utils() instead.
	 */
	public static function load_utils(array $options = array())
	{
		JHtml::fetch('vaphtml.assets.utils');
	}

	/**
	 * Loads the javascript utils and configure the Currency JS object.
	 *
	 * @return 	void
	 *
	 * @uses 	load_utils()
	 *
	 * @since 	   1.6
	 * @deprecated 1.8  Use VAPHtmlAssets::currency() instead.
	 */
	public static function load_currency_js()
	{
		JHtml::fetch('vaphtml.assets.currency');
	}

	/**
	 * Prepares the datepicker regional object.
	 *
	 * @return 	void
	 *
	 * @since 	1.6
	 */
	public static function load_datepicker_regional()
	{	
		// Labels
		$done 	= JText::translate('VAPJQCALDONE');
		$prev 	= JText::translate('VAPJQCALPREV');
		$next 	= JText::translate('VAPJQCALNEXT');
		$today 	= JText::translate('VAPJQCALTODAY');
		$wk 	= JText::translate('VAPJQCALWKHEADER');

		// Months
		$months = array(
			JText::translate('JANUARY'),
			JText::translate('FEBRUARY'),
			JText::translate('MARCH'),
			JText::translate('APRIL'),
			JText::translate('MAY'),
			JText::translate('JUNE'),
			JText::translate('JULY'),
			JText::translate('AUGUST'),
			JText::translate('SEPTEMBER'),
			JText::translate('OCTOBER'),
			JText::translate('NOVEMBER'),
			JText::translate('DECEMBER'),
		);

		$months_short = array(
			JText::translate('JANUARY_SHORT'),
			JText::translate('FEBRUARY_SHORT'),
			JText::translate('MARCH_SHORT'),
			JText::translate('APRIL_SHORT'),
			JText::translate('MAY_SHORT'),
			JText::translate('JUNE_SHORT'),
			JText::translate('JULY_SHORT'),
			JText::translate('AUGUST_SHORT'),
			JText::translate('SEPTEMBER_SHORT'),
			JText::translate('OCTOBER_SHORT'),
			JText::translate('NOVEMBER_SHORT'),
			JText::translate('DECEMBER_SHORT'),
		);

		$months 		= json_encode($months);
		$months_short 	= json_encode($months_short);

		// Days
		$days = array(
			JText::translate('SUNDAY'),
			JText::translate('MONDAY'),
			JText::translate('TUESDAY'),
			JText::translate('WEDNESDAY'),
			JText::translate('THURSDAY'),
			JText::translate('FRIDAY'),
			JText::translate('SATURDAY'),
		);

		$days_short_3 = array(
			JText::translate('SUN'),
			JText::translate('MON'),
			JText::translate('TUE'),
			JText::translate('WED'),
			JText::translate('THU'),
			JText::translate('FRI'),
			JText::translate('SAT'),
		);

		$days_short_2 = array();
		foreach ($days_short_3 as $d)
		{
			$days_short_2[] = mb_substr($d, 0, 2, 'UTF-8');
		}

		// snippet used to make sure the substring of
		// the week days doesn't return the same value (see Hebrew)
		// for all the elements
		$days_short_2 = array_unique($days_short_2);

		if (count($days_short_2) != count($days_short_3))
		{
			// the count doesn't match, use the 3 chars days
			$days_short_2 = $days_short_3;
		}

		$days 			= json_encode($days);
		$days_short_3 	= json_encode($days_short_3);
		$days_short_2 	= json_encode($days_short_2);

		$lang = JFactory::getLanguage();

		// should return a value between 0-6 (1: Monday, 0: Sunday)
		$start_of_week  = $lang->getFirstDay();
		$is_rtl 		= $lang->isRtl() ? 'true' : 'false';

		JFactory::getDocument()->addScriptDeclaration(
<<<JS
jQuery(function($){
	$.datepicker.regional["vikappointments"] = {
		closeText: "$done",
		prevText: "$prev",
		nextText: "$next",
		currentText: "$today",
		monthNames: $months,
		monthNamesShort: $months_short,
		dayNames: $days,
		dayNamesShort: $days_short_3,
		dayNamesMin: $days_short_2,
		weekHeader: "$wk",
		firstDay: $start_of_week,
		isRTL: $is_rtl,
		showMonthAfterYear: false,
		yearSuffix: ""
	};

	$.datepicker.setDefaults($.datepicker.regional["vikappointments"]);
});
JS
		);
	}
	
	/**
	 * Creates a UNIX timestamp starting from a string date.
	 *
	 * @param 	string 	 $date 	The date to parse.
	 * @param 	integer  $hour 	The hours to use.
	 * @param 	integer  $min 	The minutes to use.
	 *
	 * @return 	integer  The resulting UNIX timestamp.
	 */
	public static function createTimestamp($date, $hour = 0, $min = 0)
	{
		if ($hour == 23 && $min == 59)
		{
			$sec = 59;
		}
		else
		{
			$sec = 0;
		}

		return VAPDateHelper::getTimestamp($date, $hour, $min, $sec);
	}

	/**
	 * Returns the current time adjusted to the global timezone.
	 * Proxy for timestamp() method without passing any arguments.
	 *
	 * @param 	mixed    $offset  The timezone to use.
	 *
	 * @return 	integer  The current time.
	 *
	 * @since 	1.7
	 */
	public static function now($offset = null)
	{
		return self::timestamp(null, $offset);
	}

	/**
	 * Adjusts the given timestamp to the global timezone.
	 *
	 * @param 	integer  $ts      The timestamp to adjust.
	 * @param 	mixed    $offset  The timezone to use.
	 *
	 * @return 	integer  A timestamp adjusted to the given timezone.
	 *
	 * @since 	1.7
	 */
	public static function timestamp($ts = null, $offset = null)
	{
		if (!$offset)
		{
			// use global timezone
			$offset = JFactory::getConfig()->get('offset', 'UTC');
		}

		// create timezone instance
		$timezone = new DateTimeZone($offset);

		if (is_null($ts))
		{
			// get current time based on server configuration
			$date = new JDate();
		}
		else
		{
			// instantiate date object using the given timestamp
			$date = new JDate(date('Y-m-d H:i:s', $ts));
		}

		// adjust to global timezone
		$date->setTimezone($timezone);

		// convert adjusted datetime to timestamp (based on server timezone)
		return strtotime($date->format('Y-m-d H:i:s', true));
	}

	/**
	 * Returns the check-out date time.
	 *
	 * @param 	mixed    $checkin 	Either a date string to a timestamp.
	 * @param 	integer  $duration 	The duration of the appointment (in minutes).
	 * 
	 * @return 	string   The resulting date string.
	 *
	 * @since 	1.6
	 */
	public static function getCheckout($checkin, $duration)
	{
		if (is_numeric($checkin))
		{
			$checkin = date('Y-m-d H:i:s', $checkin);
		}

		$date = new JDate($checkin);
		$date->modify('+' . (int) $duration . ' minutes');

		return $date->format('Y-m-d H:i:s');
	}
	
	/**
	 * Checks if the given minute is a correct/supported interval.
	 *
	 * @param 	integer  $minute 	The minute value to check.
	 *
	 * @return 	boolean  True if correct, false otherwise.
	 */
	public static function isMinuteAnInterval($minute)
	{
		$min = VAPFactory::getConfig()->getUint('minuteintervals');

		for ($i = 0; $i < 60; $i += $min)
		{
			if ($i == $minute)
			{
				return true;
			}
		}

		return false;
	}
	
	/**
	 * Helper method used to calculate the right day with the given shift.
	 * It is used to display the correct position of the days depending on the
	 * first day of the week.
	 *
	 * @param 	integer  $day_index  The index of the day.
	 * @param 	integer  $shift 	 The index of the first day.
	 *
	 * @return 	integer  The resulting position
	 */
	public static function getShiftedDay($day_index, $shift)
	{
		if ($day_index + $shift < 7)
		{
			return $day_index + $shift;
		}
	 
		return $day_index + $shift - 7;
	}
	
	/**
	 * Helper method used to generate a serial code.
	 * In a remote case, this method may generate 2 identical codes.
	 * The probability to have 2 identical strings is:
	 * 1 / count($map)^$len
	 *
	 * @param 	integer  $length  The length of the serial code.
	 * @param 	string 	 $scope   The purpose of the serial code.
	 * @param 	array 	 $map 	  A map containing all the allowed tokens.
	 *
	 * @return 	string 	 The resulting serial code.
	 */
	public static function generateSerialCode($length = 12, $scope = null, $map = null)
	{
		$code = '';

		/**
		 * This event can be used to change the way the system generates
		 * a serial code. It is possible to edit the code or simply to
		 * alter the map of allowed tokens. In case the serial code
		 * didn't reach the specified length, the remaining characters
		 * will be generated according to the default algorithm.
		 *
		 * @param 	string 	 	 $code    The serial code.
		 * @param 	array|null 	 &$map    A map of allowed tokens.
		 * @param 	integer  	 $length  The length of the serial code.
		 * @param 	string|null  $scope   The purpose of the code.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger('onGenerateSerialCode', array(&$code, &$map, $length, $scope));

		if (!is_scalar($code))
		{
			// reset code in case of invalid string
			$code = '';
		}

		// check if we already have a complete serial code
		if (strlen($code) >= $length)
		{
			// just return the specified number of characters
			return substr($code, 0, $length);
		}

		if (!$map)
		{
			// use default tokens if not specified/modified
			$map = array(
				'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
				'0123456789'
			);
		}
		else
		{
			// always treat as array
			$map = (array) $map;
		}
		
		// iterate until the specified length is reached
		for ($i = strlen($code); $i < $length; $i++)
		{
			// toss tokens block
			$_row = rand(0, count($map) - 1);
			// toss block character
			$_col = rand(0, strlen($map[$_row]) - 1);

			// append character to serial code
			$code .= (string) $map[$_row][$_col];
		}

		return $code;
	}

	/**
	 * Checks if the given service owns a private calendar
	 * that cannot be shared with other services.
	 *
	 * @param 	integer  $id_ser  The service ID.
	 *
	 * @return 	boolean  True if own calendar, false otherwise.
	 *
	 * @deprecated 1.8   Use VikAppointmentsModelService::hasOwnCalendar() instead.
	 */
	public static function hasServiceOwnCalendar($id_ser)
	{
		return JModelVAP::getInstance('service')->hasOwnCalendar($id_ser);
	}
	
	/**
	 * Helper method used to get all the reservations (with extended details) that belong
	 * to the specified employee and service.
	 *
	 * @param 	integer  $id_emp 	The employee ID.
	 * @param 	integer  $id_ser 	The service ID.
	 * @param 	integer  $start_ts 	The start of the time range.
	 * @param 	integer  $end_ts 	The end of the time range.
	 * @param 	mixed 	 $dbo 		The database object.
	 *
	 * @return 	array 	 The list containing all the reservations found.
	 */
	public static function getAllEmployeeExtendedReservations($id_emp, $id_ser, $start_ts, $end_ts, $dbo = null)
	{
		if (!$dbo)
		{
			$dbo = JFactory::getDbo();
		}

		// if id_ser NOT -1 and service has own calendar, don't consider the 
		// reservations of the other services (of the same employee)
		if (!self::hasServiceOwnCalendar($id_ser))
		{
			// don't apply own search (unset service)
			$id_ser = -1;
		}
		
		/*$q = "SELECT `r`.`id` AS `rid`, `r`.`checkin_ts` AS `checkin`, `r`.`people`, `r`.`duration` AS `rduration`, `r`.`total_cost` AS `total_cost`, `r`.`status` AS `status`, `r`.`sid` AS `rsid`, `r`.`purchaser_mail` AS `rmail`,
		`r`.`sleep` AS `rsleep`, `r`.`paid` AS `paid`, `r`.`tot_paid`, `r`.`id_payment`, `r`.`purchaser_nominative`, `e`.`id` AS `id_employee`, `e`.`nickname` AS `ename`, `s`.`name` AS `sname` 
		FROM `#__vikappointments_reservation` AS `r` 
		LEFT JOIN `#__vikappointments_employee` AS `e` ON `r`.`id_employee`=`e`.`id` 
		LEFT JOIN `#__vikappointments_service` AS `s` ON `r`.`id_service`=`s`.`id`  
		WHERE `r`.`status`<>'REMOVED' AND `r`.`status`<>'CANCELED' AND `e`.`id`=$id_emp AND 
		((`s`.`has_own_cal`=0 AND $id_ser=-1) OR (`s`.`has_own_cal`=1 AND `r`.`id_service`=$id_ser)) AND 
		$start_ts <= `r`.`checkin_ts` AND `r`.`checkin_ts` <= $end_ts 
		ORDER BY `r`.`checkin_ts`;";*/

		$excluded_status = array('REMOVED', 'CANCELED');

		$q = $dbo->getQuery(true)
			->select(array(
				$dbo->qn('r.id', 'rid'),
				$dbo->qn('r.sid', 'rsid'),
				$dbo->qn('r.id_employee'),
				$dbo->qn('r.id_service'),
				$dbo->qn('r.checkin_ts', 'checkin'),
				$dbo->qn('r.people'),
				$dbo->qn('r.duration', 'rduration'),
				$dbo->qn('r.sleep', 'rsleep'),
				$dbo->qn('r.total_cost'),
				$dbo->qn('r.tot_paid'),
				$dbo->qn('r.paid'),
				$dbo->qn('r.id_payment'),
				$dbo->qn('r.status'),
				$dbo->qn('r.purchaser_nominative'),
				$dbo->qn('r.purchaser_mail', 'rmail'),
				$dbo->qn('r.closure'),
			))
			->select($dbo->qn('e.nickname', 'ename'))
			->select($dbo->qn('s.name', 'sname'));

		$q->from($dbo->qn('#__vikappointments_reservation', 'r'));
		$q->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('r.id_employee') . ' = ' . $dbo->qn('e.id'));
		$q->leftjoin($dbo->qn('#__vikappointments_service', 's') . ' ON ' . $dbo->qn('r.id_service') . ' = ' . $dbo->qn('s.id'));

		$q->where(array(
			$dbo->qn('r.status') . ' NOT IN (' . implode(', ', array_map(array($dbo, 'q'), $excluded_status)) . ')',
			$dbo->qn('e.id') . ' = ' . (int) $id_emp,
			$dbo->qn('r.checkin_ts') . ' BETWEEN ' . (int) $start_ts . ' AND ' . (int) $end_ts,
		));

		/**
		 * Do not display closure records within the front-end.
		 *
		 * @since 1.6
		 */
		if (JFactory::getApplication()->isClient('site'))
		{
			$q->where($dbo->qn('r.closure') . ' = 0');
		}

		/**
		 * (
		 * 	   (`s`.`has_own_cal` = 0 AND $id_ser          = -1     ) OR 
		 *     (`s`.`has_own_cal` = 1 AND `r`.`id_service` = $id_ser)
		 * )";
		 */
		$q->andWhere(array(
			'(' . $dbo->qn('s.has_own_cal') . ' = 0 AND ' . (int) $id_ser . ' = -1)',
			'(' . $dbo->qn('s.has_own_cal') . ' = 1 AND ' . (int) $id_ser . ' = ' . $dbo->qn('r.id_service') . ')',
		), 'OR');

		$q->order($dbo->qn('r.checkin_ts') . ' ASC');
			
		$dbo->setQuery($q);
		$rows = $dbo->loadAssocList();

		if (!$rows)
		{
			return array();
		}

		for ($i = 0; $i < count($rows); $i++)
		{
			$rows[$i]['pname'] = '';
			if ($rows[$i]['id_payment'] != -1)
			{
				$p = self::getPayment($rows[$i]['id_payment'], false);
				if (count($p) > 0)
				{
					$rows[$i]['pname'] = $p['name'];
				}
			}
		}
		
		return $rows;
	}
	
	/**
	 * Returns the list of the employee reservations for the given day.
	 * In order to support midnight reservations (that starts on a day and ends on the next one),
	 * it is needed to return also the reservations for the previous day and for the next day.
	 *
	 * @param 	integer  $id_emp 	The employee ID.
	 * @param 	integer  $id_ser 	The service ID.
	 * @param 	integer  $start_ts 	The starting delimiter (UNIX timestamp).
	 * @param 	integer  $end_ts 	The ending delimiter (UNIX timestamp)
	 * @param 	mixed 	 $dbo 		The database object.
	 *
	 * @return 	array 	 A list of matching reservations.
	 */
	public static function getAllEmployeeReservations($id_emp, $id_ser, $start_ts, $end_ts, $dbo = null)
	{
		if (!$dbo)
		{
			$dbo = JFactory::getDbo();
		}

		// if id_ser NOT -1 and service has own calendar -> don't consider the reservations of the other services (of the same employee)
		if (!self::hasServiceOwnCalendar($id_ser))
		{
			// don't apply own search
			$id_ser = -1; // unset service
		}

		$bounds = array($start_ts, $end_ts);

		if ($start_ts + 86399 == $end_ts)
		{
			/**
			 * We are looking for the reservations for the current day.
			 * Extend this bounds in order to support midnight reservations.
			 *
			 * Instead having:
			 * 2018-07-09 @ 00:00:00 - 2018-07-09 23:59:59,
			 * we need to have :
			 * 2018-07-08 @ 00:00:00 - 2018-07-10 23:59:59
			 *
			 * @since 1.6
			 */
			$start_ts 	= strtotime('-1 day 00:00:00', $bounds[0]);
			$end_ts 	= strtotime('+1 day 23:59:59', $bounds[0]);
		}

		$q = "SELECT `r`.`checkin_ts`, `r`.`duration`, `r`.`sleep`, `r`.`people`, SUM(`r`.`people`) AS `people_count`, `r`.`id`, `r`.`id_service`, `r`.`closure`, `r`.`id_employee`
		FROM `#__vikappointments_reservation` AS `r` 
		LEFT JOIN `#__vikappointments_service` AS `s` ON `r`.`id_service`=`s`.`id`
		WHERE `r`.`status`<>'REMOVED' AND `r`.`status`<>'CANCELED' AND `r`.`id_employee`=$id_emp AND 
		((`s`.`has_own_cal`=0 AND $id_ser=-1) OR (`s`.`has_own_cal`=1 AND `r`.`id_service`=$id_ser)) AND 
		$start_ts <= `r`.`checkin_ts` AND `r`.`checkin_ts` <= $end_ts GROUP BY `r`.`checkin_ts` ORDER BY `r`.`checkin_ts`;";

		$dbo->setQuery($q);
		$list = $dbo->loadAssocList();

		foreach ($list as $i => $b)
		{
			if ($list[$i]['checkin_ts'] < $bounds[0])
			{
				$day = -1;
			}
			else if ($list[$i]['checkin_ts'] > $bounds[1])
			{
				$day = 1;
			}
			else
			{
				$day = 0;
			}

			$list[$i]['@day'] = $day;
		}

		return $list;
	}
	
	/**
	 * Returns the list of the employee reservations for the given day excluding the specified ID.
	 * In order to support midnight reservations (that starts on a day and ends on the next one),
	 * it is needed to return also the reservations for the previous day and for the next day.
	 *
	 * @param 	integer  $id_emp 	The employee ID.
	 * @param 	integer  $id_ser 	The service ID.
	 * @param 	integer  $no_id 	The reservation ID to exclude.
	 * @param 	integer  $start_ts 	The starting delimiter (UNIX timestamp).
	 * @param 	integer  $end_ts 	The ending delimiter (UNIX timestamp)
	 * @param 	mixed 	 $dbo 		The database object.
	 *
	 * @return 	array 	 A list of matching reservations.
	 *
	 * @uses 	getAllEmployeeReservations()
	 */
	public static function getAllEmployeeReservationsExcludingResId($id_emp, $id_ser, $no_id, $start_ts, $end_ts, $dbo = null)
	{
		$bookings = self::getAllEmployeeReservations($id_emp, $id_ser, $start_ts, $end_ts, $dbo);

		if ($no_id > 0 && $bookings)
		{
			$i = 0;
			while ($i < count($bookings) && $bookings[$i]['id'] != $no_id)
			{
				// iterate while the current booking is not the one to exclude
				$i++;
			}

			if ($i < count($bookings))
			{
				// booking found, splice the array
				array_splice($bookings, $i, 1);
			}
		}

		return $bookings;
	}
	
	/**
	 * Returns the list of the service reservations for the given day.
	 *
	 * @param 	integer  $id_ser 	The service ID.
	 * @param 	integer  $start_ts 	The starting delimiter (UNIX timestamp).
	 * @param 	integer  $end_ts 	The ending delimiter (UNIX timestamp)
	 * @param 	mixed 	 $dbo 		The database object.
	 *
	 * @return 	array 	 A list of matching reservations.
	 *
	 * @deprecated 1.8   Use VAPAvailabilitySearch::getReservations() instead.
	 */
	public static function getAllServiceReservations($id_ser, $start_ts, $end_ts, $dbo = null)
	{
		VAPLoader::import('libraries.availability.manager');
		$search = VAPAvailabilityManager::getInstance($id_ser);

		$start = date('Y-m-d', $start_ts);
		$end   = date('Y-m-d', $end_ts);

		return $search->getReservations($start, $end);
	}
	
	/**
	 * Evaluates all the bookings that intersect the specified day.
	 *
	 * @param 	array 	 $bookings 		The bookings list.
	 * @param 	integer  $curr_index 	The current index of the list, in order to ignore 
	 * 									all the records lower than this value.
	 * @param 	integer  $start 		The UNIX timestamp of the day.
	 *
	 * @return 	array 	 An array containing the following properties:
	 * 					 0: integer  num of bookings evaluated;
	 * 					 1: array 	 daily hour reservations found.
	 *
	 * @deprecated 1.8   Without replacement.
	 */
	public static function evaluateBookingArray($bookings, $curr_index, $start)
	{
		if (!count($bookings))
		{
			// no bookings
			return array(0, array());	
		}

		$end = strtotime('23:59:59', $start);
		
		$skip = $curr_index;
		while ($skip < count($bookings) && $bookings[$skip]['checkin_ts'] < $start)
		{
			$skip++;
		}

		if ($skip)
		{
			/**
			 * Consider also the previous booking as it may be straddling 2 different days (midnight appointments).
			 *
			 * @since 1.6
			 */ 
			$skip--;
		}

		$same_day = true;
		$rows 	  = array();

		for ($i = $skip, $n = count($bookings); $i < $n && $bookings[$i]['checkin_ts'] <= $end; $i++)
		{
			$same_day = self::isBetween($bookings[$i]['checkin_ts'], $start, $end);

			if (!$same_day)
			{
				/**
				 * Fallback to check if the checkout intersects the delimiters.
				 *
				 * @since 1.6
				 */
				$checkout = self::getCheckout($bookings[$i]['checkin_ts'], $bookings[$i]['duration']);
				$same_day = self::isBetween($checkout, $start + 1, $end); // +1 is used to make sure the checkout is not equals to the start delimiter
			}
			
			if ($same_day)
			{
				// $rows[$i - $skip] = $bookings[$i];
				$rows[] = $bookings[$i];
			}
		}
		
		return array($skip + count($rows), $rows);
	}
	
	/**
	 * Checks if the given value is between the 2 delimiters.
	 *
	 * @param 	integer  $val 	 The value to check.
	 * @param 	integer  $start  The starting delimiter.
	 * @param 	integer  $end 	 The ending delimiter.
	 *
	 * @return 	boolean  True if the value is between the delimiters, false otherwise.
	 */
	public static function isBetween($val, $start, $end)
	{
		return $start <= $val && $val <= $end;
	}
	
	/**
	 * Checks if the specified employee is not fully occupied for the given day.
	 *
	 * @param 	integer  $id_emp 	  The employee ID.
	 * @param 	integer  $id_ser 	  The service ID.
	 * @param 	array 	 $arr_res 	  An array containing all the daily reservations.
	 * @param 	integer  $day_ts 	  The day UNIX timestamp.
	 * @param 	integer  $max_people  The maximum number of people.
	 * @param  	mixed 	 $dbo 		  The database object.
	 * @param 	array 	 $locations   The locations array.
	 *
	 * @return 	boolean  False if fully occupied, otherwise true.
	 *
	 * @deprecated 1.8   Use VAPAvailabilitySearch::isDayAvailable() instead.
	 */
	public static function isFreeIntervalOnDay($id_emp, $id_ser, $arr_res, $day_ts, $max_people, $dbo = null, $locations = array())
	{
		if (is_numeric($day_ts))
		{
			// convert to date string
			$day_ts = date('Y-m-d', $day_ts);
		}

		VAPLoader::import('libraries.availability.manager');
		$search = VAPAvailabilityManager::getInstance($id_ser, $id_emp, array('locations' => $locations));

		return $search->isDayAvailable($day_ts);
	}
	
	/**
	 * Checks if the specified service owns at least an employee that
	 * is not fully occupied for the given day.
	 *
	 * @param 	array 	 $employees	  The employees IDs.
	 * @param 	integer  $id_ser 	  The service ID.
	 * @param 	array 	 $arr_res 	  An array containing all the daily reservations.
	 * @param 	integer  $day_ts 	  The day UNIX timestamp.
	 * @param  	mixed 	 $dbo 		  The database object.
	 * @param 	array 	 $locations   The locations array.
	 *
	 * @return 	boolean  False if fully occupied, otherwise true.
	 *
	 * @deprecated 1.8   Use VAPAvailabilitySearch::isDayAvailable() instead.
	 */
	public static function isFreeIntervalOnDayService($employees, $id_ser, $arr_res, $day_ts, $dbo = null, $locations = array())
	{
		if (is_numeric($day_ts))
		{
			// convert to date string
			$day_ts = date('Y-m-d', $day_ts);
		}

		VAPLoader::import('libraries.availability.manager');
		$search = VAPAvailabilityManager::getInstance($id_ser, null, array('locations' => $locations));

		return $search->isDayAvailable($day_ts);
	}

	/**
	 * Checks if the specified service owns at least an employee that
	 * is not fully occupied for the given day. This method supports
	 * services with maximum capacity higher than 1.
	 *
	 * @param 	array 	 $employees	    The employees IDs.
	 * @param 	integer  $id_ser 	    The service ID.
	 * @param 	array 	 $arr_res 	    An array containing all the daily reservations.
	 * @param 	integer  $day_ts 	    The day UNIX timestamp.
	 * @param 	integer  $max_capacity 	The maximum number of people.
	 * @param  	mixed 	 $dbo 		    The database object.
	 * @param 	array 	 $locations     The locations array.
	 *
	 * @return 	boolean  False if fully occupied, otherwise true.
	 *
	 * @since 	1.2
	 * @deprecated 1.8   Use VAPAvailabilitySearch::isDayAvailable() instead.
	 */
	public static function isFreeIntervalOnDayGroupService($employees, $id_ser, $arr_res, $day_ts, $max_capacity, $dbo = null, $locations = array())
	{
		if (is_numeric($day_ts))
		{
			// convert to date string
			$day_ts = date('Y-m-d', $day_ts);
		}

		VAPLoader::import('libraries.availability.manager');
		$search = VAPAvailabilityManager::getInstance($id_ser, null, array('locations' => $locations));

		return $search->isDayAvailable($day_ts);
	}
	
	/**
	 * Method used to obtain a list of reservations at the given date and time.
	 *
	 * @param 	integer  $id_emp 	The employee ID.
	 * @param 	integer  $checkin 	The reservations checkin.
	 * @param 	mixed 	 $dbo 		The database object.
	 * @param 	mixed 	 $q 		A query builder used to overwrite SELECT and FROM statements.
	 *
	 * @return 	array 	 The list of matching reservations.
	 */
	public static function getEmployeeAppointmentAt($id_emp, $checkin, $dbo = null, $q = null)
	{
		if (!$dbo)
		{
			$dbo = JFactory::getDbo();
		}

		if (!$q)
		{
			$q = $dbo->getQuery(true);

			$q->select($dbo->qn('r.id', 'rid'))->from($dbo->qn('#__vikappointments_reservation', 'r'));
		}

		if ($id_emp)
		{
			$q->where($dbo->qn('r.id_employee') . ' = ' . (int) $id_emp);
		}

		$q->where(array(
			$dbo->qn('r.status') . ' IN (\'CONFIRMED\', \'PENDING\')',
			$dbo->qn('r.checkin_ts') . ' <= ' . (int) $checkin,
			(int) $checkin . ' < (' . $dbo->qn('r.checkin_ts') . ' + ' . $dbo->qn('r.duration') . ' * 60 + ' . $dbo->qn('r.sleep') . ')',
		));
	
		$dbo->setQuery($q);
		return $dbo->loadAssocList();
	}
	
	/**
	 * Elaborates the timeline by intersecting the worktimes and the bookings found.
	 *
	 * @param 	array 	 $worktime 	The list of the available working times.
	 * @param 	array 	 $bookings 	The reservations to intersect.
	 * @param 	mixed 	 $service 	The service details.
	 *
	 * @return 	array  	 A list of associative arrays containing the elaborated timeline.
	 * 					 The keys contain the time (hour * 60 + min) and the values
	 * 					 contain the status (0: blocked, 1: available, 2: not enough space).
	 *
	 * @deprecated 1.8   VAPAvailabilityTimelineEmployee::getTimeline() instead.
	 */
	public static function elaborateTimeLine($worktime, $bookings, $service)
	{	
		/**
		 * Load service details in case the service ID was passed.
		 * 
		 * @since 1.6.5
		 */
		if (is_scalar($service))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select($dbo->qn(array('id', 'interval', 'duration', 'sleep')))
				->from($dbo->qn('#__vikappointments_service'))
				->where($dbo->qn('id') . ' = ' . (int) $service);

			$dbo->setQuery($q, 0, 1);
			$service = $dbo->loadAssoc();

			if (!$service)
			{
				throw new Exception(sprintf('Service [%d] not found', $service), 404);
			}
		}
		else
		{
			$service = (array) $service;
		}
		
		$min_int = VAPFactory::getConfig()->getUint('minuteintervals');

		if ($service['interval'] == 1)
		{
			$min_int = 5;
		}
		
		$arr = array();
		
		for ($i = 0; $i < count($worktime); $i++)
		{
			//for( $j = $worktime[$i]['fromts'], $len = 0; $j < $worktime[$i]['endts']; $j+=$min_int) {
			for ($j = $worktime[$i]['fromts'], $len = 0; ($j + $min_int) <= $worktime[$i]['endts']; $j += $min_int)
			{
				$arr[$i][$j] = 1;
			}
		}
		
		foreach ($bookings as $b)
		{
			$date  = getdate($b['checkin_ts']);
			$start = ($date['hours'] * 60) + $date['minutes'];

			if (isset($b['@day']))
			{
				/**
				 * Check the day factor of a reservation to check if it is referring
				 * to the current day or if it close to the bounds of this working time.
				 * Used to support midnight reservations.
				 *
				 * @since 1.6
				 */

				if ($b['@day'] == 1)
				{
					// we are evaluating a reservation for the next day, so we need to increase the 
					// initial time by 1440 minutes (24 hours * 60).
					$start += 1440;
				}
				else if ($b['@day'] == -1)
				{
					// we are evaluating a reservation for the previous day, so we need to decrease the 
					// initial time by 1440 minutes (24 hours * 60).
					$start -= 1440;
				}
			}

			for ($i = $start; $i < $start + $b['duration'] + $b['sleep']; $i += $min_int)
			{
				$found = false;
				for ($j = 0; $j < count($arr) && !$found; $j++)
				{
					if (!empty($arr[$j][$i]))
					{
						$found = true;
						$arr[$j][$i] = 0;
					}
				}
			}
		}
		
		if ($service['interval'] != 1)
		{
			$n_step = $service['duration'] + $service['sleep'];
			
			for ($i = 0; $i < count($arr); $i++)
			{
				$step = 0;
				//for( $j = $worktime[$i]['fromts'], $len = 0; $j < $worktime[$i]['endts']; $j+=$min_int) {
				for ($j = $worktime[$i]['fromts'], $len = 0; ($j + $min_int) <= $worktime[$i]['endts']; $j += $min_int)
				{
					if ($arr[$i][$j] == 1)
					{
						$step += $min_int;
						if ($step >= $n_step)
						{
							$step-=$min_int;
						}
					}
					else
					{
						if ($step != 0 && $step < $n_step)
						{
							for ($back = $j - $min_int; $back >= $j - $step; $back -= $min_int)
							{
								$arr[$i][$back] = 2;
							}
						}
						
						$step = 0;
					}
				}
				
				if ($step != 0 && $step < $n_step)
				{
					for ($back = $j - $min_int; $back >= $j - $step; $back -= $min_int)
					{
						$arr[$i][$back] = 2;
					}
				}
			}
		}

		$mod = round(($service['duration'] + $service['sleep']) / $min_int);

		if ($service['interval'] == 1 && $mod != 1)
		{
			$new_arr = array();
			
			for ($i = 0; $i < count($arr); $i++)
			{
				$new_arr[$i] = array();
				$value = 1;
				$start = 0;
				$all_free = true;
				
				$count = 0;
				
				for ($j = $worktime[$i]['fromts']; $j < $worktime[$i]['endts']; $j += $min_int, $count++)
				{
					if ($count % $mod == 0)
					{
						$start = $j;
						$value = 1;
						$all_free = true;
					}
					
					$hourmin = intval($j / 60) . ' : ' . ($j % 60);
					if ($arr[$i][$j] == 0)
					{
						$all_free = false;
					}

					$value &= ($arr[$i][$j] == 2 ? 0 : $arr[$i][$j]); 
					
					if ((($count + 1) % $mod == 0 || $j + $min_int == $worktime[$i]['endts']))
					{
						// LAST TIME SLOTS is not enough length
						if (($count+1) % $mod != 0)
						{
							$value = 0;
						}
						
						if ($value == 0 && $all_free)
						{
							$value = 2;
						}

						$new_arr[$i][$start] = $value;
					}
				}
			}
			
			$arr = $new_arr;
		}
		
		return $arr;
	}

	/**
	 * Elaborates the timeline by intersecting the worktimes and the bookings found.
	 * Filters the arrays of timelines to support a single associative array.
	 *
	 * @param 	array 	 $worktime 	The list of the available working times.
	 * @param 	array 	 $bookings 	The reservations to intersect.
	 * @param 	array 	 $service 	The service details.
	 *
	 * @return 	array  	 An associative array containing the elaborated timeline.
	 * 					 The keys contain the time (hour * 60 + min) and the values
	 * 					 contain the status (0: blocked, 1: available, 2: not enough space).
	 *
	 * @deprecated 1.8   VAPAvailabilityTimelineService::getTimeline() instead.
	 */
	public static function elaborateTimeLineService($worktime, $bookings, $service)
	{
		$arr = self::elaborateTimeLine($worktime, $bookings, $service);
		
		$timeline = array();
		foreach ($arr as $a)
		{
			foreach ($a as $hour => $val)
			{
				$timeline[$hour] = $val;
			}
		}
		
		return $timeline;
	}
	
	/**
	 * Elaborates the timeline by intersecting the worktimes and the bookings found.
	 * This method accepts multiple bookings at the same date and time depending on
	 * the maximum capacity defined by the given service.
	 *
	 * @param 	array 	 $worktime 	The list of the available working times.
	 * @param 	array 	 $bookings 	The reservations to intersect.
	 * @param 	array 	 $service 	The service details.
	 * @param 	integer  $people 	The number of specified people.
	 * @param 	mixed 	 &$seats 	An array containing the remaining seats for each time.
	 *
	 * @return 	array  	 An associative array containing the elaborated timeline.
	 * 					 The keys contain the time (hour * 60 + min) and the values
	 * 					 contain the status (0: blocked, 1: available, 2: not enough space).
	 *
	 * @deprecated 1.8   VAPAvailabilityTimelineGroup::getTimeline() instead.
	 */
	public static function elaborateTimeLineGroupService($worktime, $bookings, $service, $people = 1, &$seats = null)
	{	
		$min_int = 0;

		if ($service['interval'] == 1)
		{
			$min_int = $service['duration'] + $service['sleep'];
		}
		else
		{
			$min_int = VAPFactory::getConfig()->getUint('minuteintervals');
		}
		
		$arr = array();
		
		for ($i = 0; $i < count($worktime); $i++)
		{
			//for( $j = $worktime[$i]['fromts'], $len = 0; $j < $worktime[$i]['endts']; $j+=$min_int) {
			for ($j = $worktime[$i]['fromts'], $len = 0; ($j + $min_int) <= $worktime[$i]['endts']; $j += $min_int)
			{
				$arr[$i][$j] = 1;
			}
		}
		
		$cont_people = 0;
		for ($k = 0; $k < count($bookings); $k++)
		{
			$b = $bookings[$k];
			
			$cont_people += $b['people_count'];
			if ($k == count($bookings) - 1 || $bookings[$k + 1]['checkin_ts'] != $b['checkin_ts'])
			{
				$date  = getdate($b['checkin_ts']);
				$start = ($date['hours'] * 60) + $date['minutes'];

				if (isset($b['@day']))
				{
					/**
					 * Check the day factor of a reservation to check if it is referring
					 * to the current day or if it close to the bounds of this working time.
					 * Used to support midnight reservations.
					 *
					 * @since 1.6
					 */

					if ($b['@day'] == 1)
					{
						// we are evaluating a reservation for the next day, so we need to increase the 
						// initial time by 1440 minutes (24 hours * 60).
						$start += 1440;
					}
					else if ($b['@day'] == -1)
					{
						// we are evaluating a reservation for the previous day, so we need to decrease the 
						// initial time by 1440 minutes (24 hours * 60).
						$start -= 1440;
					}
				}

				for ($i = $start; $i < $start + $b['duration'] + $b['sleep']; $i += $min_int)
				{
					$found = false;
					for ($j = 0; $j < count($arr) && !$found; $j++)
					{
						/**
						 * Try to block appointments that come from a different service or
						 * if the number of people exceeds the total capacity.
						 *
						 * @since 1.6 	check if the services are different only if $arr[$j][$i] is set
						 */
						if (!empty($arr[$j][$i]) && ($cont_people + $people > $service['max_capacity'] || $b['id_service'] != $service['id'] || $b['closure']))
						{
							// if $b['id_service'] doesn't exist, take a look at the VikAppointments::getAllEmployeeReservations() function
							// if $service['id'] doesn't exist, take a look at the VikAppointmentsController::get_day_time_line() and VikAppointmentsController::get_day_time_line_service() functions
							$found = true;
							$arr[$j][$i] = 0;
						}

						/**
						 * If $seats argument is an array, push the remaining seats.
						 * 
						 * @since 1.6
						 */
						if (is_array($seats))
						{
							if ($b['id_service'] == $service['id'] && !$b['closure'])
							{
								// same service, we can display the remaining seats
								$seats[$i] = $service['max_capacity'] - $cont_people;
							}
							else
							{
								// booked for a different service, unset the remaining seats
								$seats[$i] = 0;
							}
						}
					}

					/**
					 * We may have different services that display shifted
					 * timelines. This would cause an issue as previous check
					 * ignores the times that don't match the evaluated slots.
					 *
					 * We need to unset here all the times that intersect with
					 * an existing reservation, which might have been created for
					 * a different service.
					 *
					 * @since 1.6.2
					 */
					if (!$found)
					{
						// find all slots that intersect this one
						for ($j = 0; $j < count($arr); $j++)
						{
							foreach ($arr[$j] as $arr_hm => &$v)
							{
								if (($start < $arr_hm && $arr_hm < $start + $b['duration'] + $b['sleep'])
									|| ($arr_hm < $start && $start < $arr_hm + $service['duration'] + $service['sleep']))
								{
									$v = 0;
								}
							}
						}
					}
				}
				
				$cont_people = 0;
			}
		}
		
		$n_step = $service['duration'] + $service['sleep'];

		/*
		
		for( $i = 0; $i < count($arr); $i++ ) {
			$step = 0;
			//for( $j = $worktime[$i]['fromts'], $len = 0; $j < $worktime[$i]['endts']; $j+=$min_int ) {
			for( $j = $worktime[$i]['fromts']; ($j+$min_int) <= $worktime[$i]['endts']; $j+=$min_int ) {
				if( $arr[$i][$j] == 1 ) {
					$step+=$min_int;
					if( $step == $n_step ) {
						$step-=$min_int;
					}
				} else {
					if( $step != 0 && $step < $n_step ) {
						for( $back = $j-$min_int; $back >= $j-$step; $back-=$min_int ) {
							$arr[$i][$back] = 2;
						}
					}
					
					$step = 0;
				}
			}
			
			if( $step != 0 && $step < $n_step ) {
				for( $back = $j-$min_int; $back >= $j-$step; $back-=$min_int ) {
					$arr[$i][$back] = 2;
				}
			}
		}

		*/

		// array deep : elaborate each timeline 
		for ($level = 0; $level < count($arr); $level++)
		{
			// get all the times in the current timeline
			$keys = array_keys($arr[$level]);
			// insert the end working time to evaluate properly the last available time
			$keys[] = $worktime[$level]['endts'];

			for ($i = 0; $i < count($keys)-1; $i++)
			{
				$last_index = -1;

				for ($j = $i + 1; $j < count($keys) && $last_index == -1; $j++)
				{
					/**
					 * If index is last or if current time is not available.
					 *
					 * @since 1.6 	Use empty($arr[$level][$keys[$j]]) to avoid "Undefined Index" notices.
					 * 				These notices may be raised when the reservations were stored for certain
					 * 				times that don't exist anymore.
					 */
					// if ($keys[$j] == count($keys) -1 || $arr[$level][$keys[$j]] == 0)
					if ($keys[$j] == count($keys) -1 || empty($arr[$level][$keys[$j]]))
					{
						// store last index found and stop for statement
						$last_index = $j;
					}
				}

				// if subtraction of last index found with current index is not enough
				if ($keys[$last_index] - $keys[$i] < $n_step)
				{
					// if current time is still available
					if ($arr[$level][$keys[$i]] == 1)
					{
						// mark current time as no more available
						$arr[$level][$keys[$i]] = 2;
					}
				}
			}
		}
		
		$timeline = array();
		foreach ($arr as $a)
		{
			foreach ($a as $hour => $val)
			{
				$timeline[$hour] = $val;
			}
		}
		
		return $timeline;
	}

	// TIMEZONE

	/**
	 * Elaborates the timeline by intersecting the worktimes and the bookings found.
	 * This method should be used in case the times need to be adjusted to the
	 * employee timezone.
	 *
	 * @param 	array 	 $worktime 	The list of the available working times.
	 * @param 	array 	 $bookings 	The reservations to intersect.
	 * @param 	array 	 $service 	The service details.
	 * @param 	string 	 $timezone  The timezone string.
	 *
	 * @return 	array  	 A list of associative arrays containing the elaborated timeline.
	 * 					 The keys contain the time (hour * 60 + min) and the values
	 * 					 contain the status (0: blocked, 1: available, 2: not enough space).
	 *
	 * @since 	1.4
	 *
	 * @deprecated 1.8   Without replacement.
	 */
	public static function elaborateTimeLineTimezone($worktime, $bookings, $service, $timezone)
	{	
		$min_int = VAPFactory::getConfig()->getUint('minuteintervals');
		if ($service['interval'] == 1)
		{
			$min_int = 5;
		}
		
		$arr = array();
		
		for ($i = 0; $i < count($worktime); $i++)
		{
			for ($j = $worktime[$i]['fromts'], $len = 0; ($j + $min_int) <= $worktime[$i]['endts']; $j += $min_int)
			{
				$arr[$i][$j] = 1;
			}
		}
		
		self::setCurrentTimezone($timezone);
		
		foreach ($bookings as $b)
		{
			$date  = getdate($b['checkin_ts']);
			$start = $date['hours'] * 60 + $date['minutes'];

			if (isset($b['@day']))
			{
				/**
				 * Check the day factor of a reservation to check if it is referring
				 * to the current day or if it close to the bounds of this working time.
				 * Used to support midnight reservations.
				 *
				 * @since 1.6
				 */

				if ($b['@day'] == 1)
				{
					// we are evaluating a reservation for the next day, so we need to increase the 
					// initial time by 1440 minutes (24 hours * 60).
					$start += 1440;
				}
				else if ($b['@day'] == -1)
				{
					// we are evaluating a reservation for the previous day, so we need to decrease the 
					// initial time by 1440 minutes (24 hours * 60).
					$start -= 1440;
				}
			}

			for ($i = $start; $i < $start + $b['duration'] + $b['sleep']; $i += $min_int)
			{
				$found = false;
				for ($j = 0; $j < count($arr) && !$found; $j++)
				{
					if (!empty($arr[$j][$i]))
					{
						$found = true;
						$arr[$j][$i] = 0;
					}
				}

				/**
				 * We may have different service that display shifted
				 * timelines. This would cause an issue as previous check
				 * ignores the times that don't matches the evaluated slots.
				 *
				 * We need to unset here all the times that intersect with
				 * an existing reservation, which might have been created for
				 * a different service.
				 *
				 * @since 1.6.2
				 */
				if (!$found)
				{
					// find all slots that intersect this one
					for ($j = 0; $j < count($arr); $j++)
					{
						foreach ($arr[$j] as $arr_hm => &$v)
						{
							if (($start < $arr_hm && $arr_hm < $start + $b['duration'] + $b['sleep'])
								|| ($arr_hm < $start && $start < $arr_hm + $service['duration'] + $service['sleep']))
							{
								$v = 0;
							}
						}
					}
				}
			}
		}
		
		if ($service['interval'] != 1)
		{
			$n_step = $service['duration'] + $service['sleep'];
			
			for ($i = 0; $i < count($arr); $i++)
			{
				$step = 0;
				//for( $j = $worktime[$i]['fromts'], $len = 0; $j < $worktime[$i]['endts']; $j+=$min_int) {
				for ($j = $worktime[$i]['fromts'], $len = 0; ($j + $min_int) <= $worktime[$i]['endts']; $j += $min_int)
				{
					if ($arr[$i][$j] == 1)
					{
						$step += $min_int;
						if ($step >= $n_step)
						{
							$step-=$min_int;
						}
					}
					else
					{
						if ($step != 0 && $step < $n_step)
						{
							for ($back = $j - $min_int; $back >= $j - $step; $back -= $min_int)
							{
								$arr[$i][$back] = 2;
							}
						}
						
						$step = 0;
					}
				}
				
				if ($step != 0 && $step < $n_step)
				{
					for ($back = $j - $min_int; $back >= $j - $step; $back -= $min_int)
					{
						$arr[$i][$back] = 2;
					}
				}
			}

		}

		$mod = round(($service['duration'] + $service['sleep']) / $min_int);

		if ($service['interval'] == 1 && $mod != 1)
		{
			$new_arr = array();

			for ($i = 0; $i < count($arr); $i++)
			{
				$new_arr[$i] = array();
				$value = 1;
				$start = 0;
				$all_free = true;
				
				$count = 0;
				for ($j = $worktime[$i]['fromts']; $j < $worktime[$i]['endts']; $j += $min_int, $count++)
				{
					if ($count % $mod == 0)
					{
						$start = $j;
						$value = 1;
						$all_free = true;
					}
					
					$hourmin = intval($j / 60) . ' : ' . ($j % 60);
					
					if ($arr[$i][$j] == 0)
					{
						$all_free = false;
					}

					$value &= ($arr[$i][$j] == 2 ? 0 : $arr[$i][$j]);
					
					if ((($count + 1) % $mod == 0 || $j + $min_int == $worktime[$i]['endts']))
					{
						// LAST TIME SLOTS is not enough length
						if (($count + 1) % $mod != 0)
						{
							$value = 0;
						}
						
						if ($value == 0 && $all_free)
						{
							$value = 2;
						}

						$new_arr[$i][$start] = $value;
					}
				}
			}
			
			$arr = $new_arr;
		}
		
		return $arr;
		
	}

	/**
	 * Elaborates the timeline by intersecting the worktimes and the bookings found.
	 * Filters the arrays of timelines to support a single associative array.
	 * This method should be used in case the times need to be adjusted to the
	 * employee timezone.
	 *
	 * @param 	array 	 $worktime 	The list of the available working times.
	 * @param 	array 	 $bookings 	The reservations to intersect.
	 * @param 	array 	 $service 	The service details.
	 * @param 	string 	 $timezone 	The timezone string.
	 *
	 * @return 	array  	 An associative array containing the elaborated timeline.
	 * 					 The keys contain the time (hour * 60 + min) and the values
	 * 					 contain the status (0: blocked, 1: available, 2: not enough space).
	 *
	 * @since 	1.4
	 *
	 * @deprecated 1.8   Without replacement.
	 */
	public static function elaborateTimeLineServiceTimezone($worktime, $bookings, $service, $timezone)
	{
		$arr = self::elaborateTimeLineTimezone($worktime, $bookings, $service, $timezone);
		
		$timeline = array();
		foreach ($arr as $a)
		{
			foreach ($a as $hour => $val)
			{
				$timeline[$hour] = $val;
			}
		}
		
		return $timeline;
	}
	
	/**
	 * Elaborates the timeline by intersecting the worktimes and the bookings found.
	 * This method accepts multiple bookings at the same date and time depending on
	 * the maximum capacity defined by the given service.
	 *
	 * This method always converts the timezone according to the configuration of
	 * the employee, only in case the multi-timezone setting is enabled.
	 *
	 * @param 	array 	 $worktime 	The list of the available working times.
	 * @param 	array 	 $bookings 	The reservations to intersect.
	 * @param 	array 	 $service 	The service details.
	 * @param 	integer  $people 	The number of specified people.
	 * @param 	string 	 $timezone  The employee timezone (if set).
	 * @param 	mixed 	 &$seats 	An array containing the remaining seats for each time.
	 *
	 * @return 	array  	 An associative array containing the elaborated timeline.
	 * 					 The keys contain the time (hour * 60 + min) and the values
	 * 					 contain the status (0: blocked, 1: available, 2: not enough space).
	 *
	 * @since 	1.4
	 *
	 * @deprecated 1.8   Without replacement.
	 */
	public static function elaborateTimeLineGroupServiceTimezone($worktime, $bookings, $service, $people, $timezone, &$seats = null)
	{	
		$min_int = 0;

		if ($service['interval'] == 1)
		{
			$min_int = $service['duration'] + $service['sleep'];
		}
		else
		{
			$min_int = VAPFactory::getConfig()->getUint('minuteintervals');
		}
		
		$arr = array();
		
		for ($i = 0; $i < count($worktime); $i++)
		{
			//for( $j = $worktime[$i]['fromts'], $len = 0; $j < $worktime[$i]['endts']; $j+=$min_int) {
			for ($j = $worktime[$i]['fromts'], $len = 0; ($j+$min_int) <= $worktime[$i]['endts']; $j += $min_int)
			{
				$arr[$i][$j] = 1;
			}
		}
		
		self::setCurrentTimezone($timezone);
		
		$cont_people = 0;
		for ($k = 0; $k < count($bookings); $k++)
		{
			$b = $bookings[$k];
			
			$cont_people += $b['people_count'];
			if ($k == count($bookings) - 1 || $bookings[$k + 1]['checkin_ts'] != $b['checkin_ts'])
			{
				$date = getdate($b['checkin_ts']);
				$start = ($date['hours'] * 60) + $date['minutes'];

				if (isset($b['@day']))
				{
					/**
					 * Check the day factor of a reservation to check if it is referring
					 * to the current day or if it close to the bounds of this working time.
					 * Used to support midnight reservations.
					 *
					 * @since 1.6
					 */

					if ($b['@day'] == 1)
					{
						// we are evaluating a reservation for the next day, so we need to increase the 
						// initial time by 1440 minutes (24 hours * 60).
						$start += 1440;
					}
					else if ($b['@day'] == -1)
					{
						// we are evaluating a reservation for the previous day, so we need to decrease the 
						// initial time by 1440 minutes (24 hours * 60).
						$start -= 1440;
					}
				}

				for ($i = $start; $i < $start + $b['duration'] + $b['sleep']; $i += $min_int)
				{
					$found = false;
					for ($j = 0; $j < count($arr) && !$found; $j++)
					{
						/**
						 * Try to block appointments that come from a different service or
						 * if the number of people exceeds the total capacity.
						 *
						 * @since 1.6 	check if the services are different only if $arr[$j][$i] is set
						 */
						if (!empty($arr[$j][$i]) && ($cont_people + $people > $service['max_capacity'] || $b['id_service'] != $service['id'] || $b['closure']))
						{
							// if $b['id_service'] doesn't exist, take a look at the VikAppointments::getAllEmployeeReservationsExcludingResId() function
							// if $service['id'] doesn't exist, take a look at the VikAppointmentsController::get_day_time_line() and VikAppointmentsController::get_day_time_line_service() functions
							$found = true;
							$arr[$j][$i] = 0;
						}

						/**
						 * If $seats argument is an array, push the remaining seats.
						 * 
						 * @since 1.6
						 */
						if (is_array($seats))
						{
							if ($b['id_service'] == $service['id'] && !$b['closure'])
							{
								// same service, we can display the remaining seats
								$seats[$i] = $service['max_capacity'] - $cont_people;
							}
							else
							{
								// booked for a different service, unset the remaining seats
								$seats[$i] = 0;
							}
						}
					}
				}
				
				$cont_people = 0;
			}
		}
		
		$n_step = $service['duration'] + $service['sleep'];
		
		for ($i = 0; $i < count($arr); $i++)
		{
			$step = 0;
			//for( $j = $worktime[$i]['fromts'], $len = 0; $j < $worktime[$i]['endts']; $j+=$min_int) {
			for ($j = $worktime[$i]['fromts'], $len = 0; ($j + $min_int) <= $worktime[$i]['endts']; $j += $min_int)
			{
				if ($arr[$i][$j] == 1)
				{
					$step += $min_int;
					if ($step == $n_step)
					{
						$step -= $min_int;
					}
				}
				else
				{
					if ($step != 0 && $step < $n_step)
					{
						for ($back = $j - $min_int; $back >= $j - $step; $back -= $min_int)
						{
							$arr[$i][$back] = 2;
						}
					}
					
					$step = 0;
				}
			}
			
			if ($step != 0 && $step < $n_step)
			{
				for ($back = $j - $min_int; $back >= $j - $step; $back -= $min_int)
				{
					$arr[$i][$back] = 2;
				}
			}
		}
		
		$timeline = array();
		foreach ($arr as $a)
		{
			foreach ($a as $hour => $val)
			{
				$timeline[$hour] = $val;
			}
		}
		
		return $timeline;
	}

	///////////
	
	/**
	 * Parses the timelines in the array to fetch a single timeline.
	 *
	 * @param 	array 	$timelines 	The fetched timelines.
	 *
	 * @return 	array 	The resulting timeline.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function parseServiceTimeline($timelines)
	{
		$arr = array();
		
		foreach ($timelines as $tl)
		{
			foreach ($tl as $hour => $val)
			{
				$res = $val;
				$is_pending = false;
				
				for ($i = 0; $i < count($timelines) && $res != 1; $i++) 
				{
					$res = (!empty($timelines[$i][$hour])) ? $timelines[$i][$hour] : 0;
					if ($res == 2)
					{
						$is_pending = true;
					}
				}
				
				if ($res == 0 && $is_pending)
				{
					$res = 2;
				}
				
				$arr[$hour] = $res;
			}
		}
		
		return $arr;
	}
	
	/**
	 * Checks if the employees works on the specified day for the given service.
	 *
	 * @param 	integer  $id_emp 	 The employee ID.
	 * @param 	integer  $id_ser 	 The service ID.
	 * @param 	integer  $ts 		 The day UNIX timestamp.
	 * @param 	mixed 	 $dbo 		 The database object.
	 * @param 	array 	 $locations  The locations array.
	 *
	 * @return 	boolean  True if it works, otherwise false.
	 *
	 * @deprecated 1.8   Use VAPAvailabilitySearch::hasWorkingDay() instead.
	 */
	public static function hasEmployeeWorkingTimeOn($id_emp, $id_ser, $ts, $dbo = '', $locations = array())
	{
		if (is_numeric($ts))
		{
			$ts = date('Y-m-d', $ts);
		}

		VAPLoader::import('libraries.availability.manager');
		$search = VAPAvailabilityManager::getInstance($id_service, $id_emp, array('locations' => $locations));

		return $search->hasWorkingDay($ts);
	}
	
	/**
	 * Checks if the specified timestamp is in the past or
	 * doesn't follow the booking minutes restriction.
	 *
	 * @param 	integer  $timestamp  The UNIX timestamp to check.
	 * @param 	mixed 	 $service    Either the service details or the ID.
	 *
	 * @return 	boolean  True if not allowed, false otherwise.
	 *
	 * @deprecated 1.8   Use VAPAvailabilitySearch::isPastTime() instead.
	 */
	public static function isTimeInThePast($timestamp, $service = null)
	{
		if (is_numeric($timestamp))
		{
			// convert check-in from timestamp to date string
			$timestamp = date('Y-m-d H:i:s', $timestamp);
		}

		if (!$service)
		{
			$input = JFactory::getApplication()->input;

			// get service ID from request
			$service = $input->getUint('id_service');

			if (!$service)
			{
				// service not found, try a different name
				$service = $input->getUint('id_ser');
			}
		}

		if ($service && !is_numeric($service))
		{
			// we had a service array, take only the ID
			$service = (array) $service;
			$service = $service['id'];
		}

		VAPLoader::import('libraries.availability.manager');
		$search = VAPAvailabilityManager::getInstance($service);

		return $search->isPastTime($timestamp);
	}
	
	/**
	 * Checks if the specified timestamp belong to a closing day/period.
	 *
	 * @param 	integer  $ts      The timestamp to check.
	 * @param 	integer  $id_ser  The service ID to restrict the closing days.
	 *
	 * @return 	boolean  True if closing day, false otherwise.
	 *
	 * @deprecated 1.8   Use VAPAvailabilitySearch::isClosingDay() instead.
	 */
	public static function isClosingDay($ts, $id_ser = null)
	{
		if (is_numeric($ts))
		{
			// convert to date string
			$ts = date('Y-m-d', $ts);
		}

		VAPLoader::import('libraries.availability.manager');
		$search = VAPAvailabilityManager::getInstance($id_ser);

		return $search->isClosingDay($ts);
	}
	
	/**
	 * Checks if the employees works on the specified day by checking the closing days too.
	 *
	 * @param 	integer  $id_emp 	 	  The employee ID.
	 * @param 	integer  $id_ser 	 	  The service ID.
	 * @param 	integer  $ts 		 	  The day UNIX timestamp.
	 * @param 	array 	 $closing_days 	  The closing days list.
	 * @param 	array 	 $closing_perios  The closing periods list.
	 * @param 	mixed 	 $dbo 		 	  The database object.
	 * @param 	array 	 $locations  	  The locations array.
	 *
	 * @return 	boolean  True if it works, otherwise false.
	 *
	 * @deprecated 1.8   Use VAPAvailabilitySearch::isDayOpen() instead.
	 */
	public static function isTableDayAvailable($id_emp, $id_ser, $ts, $closing_days = null, $closing_periods = null, $dbo = null, $locations = array())
	{
		if (is_numeric($ts))
		{
			// convert to date string
			$ts = date('Y-m-d', $ts);
		}

		VAPLoader::import('libraries.availability.manager');
		$search = VAPAvailabilityManager::getInstance($id_ser, $id_emp, array('locations' => $locations));

		return $search->isDayOpen($ts);
	}
	
	/**
	 * Checks if there is at least an employee that works on the 
	 * specified day by checking the closing days too.
	 *
	 * @param 	array  	 $employees 	  The employee IDs.
	 * @param 	integer  $id_ser 	 	  The service ID.
	 * @param 	integer  $ts 		 	  The day UNIX timestamp.
	 * @param 	array 	 $closing_days 	  The closing days list.
	 * @param 	array 	 $closing_perios  The closing periods list.
	 * @param 	mixed 	 $dbo 		 	  The database object.
	 * @param 	array 	 $locations  	  The locations array.
	 *
	 * @return 	boolean  True if it works, otherwise false.
	 *
	 * @deprecated 1.8   Use VAPAvailabilitySearch::isDayOpen() instead.
	 */
	public static function isGenericTableDayAvailable($employees, $id_ser, $ts, $closing_days = null, $closing_periods = null, $dbo = null, $locations = array())
	{
		if (is_numeric($ts))
		{
			// convert to date string
			$ts = date('Y-m-d', $ts);
		}

		VAPLoader::import('libraries.availability.manager');
		$search = VAPAvailabilityManager::getInstance($id_ser, null, array('locations' => $locations));

		return $search->isDayOpen($ts);
	}
	
	/**
	 * Returns the list of employees that offer the specified service.
	 *
	 * @param 	integer  $id_ser 	The service ID.
	 * @param 	boolean  $ordering  True to sort the employees.
	 * @param 	boolean  $listable 	True to take only listable employees.
	 *
	 * @return 	array 	 The employees list.
	 */
	public static function getEmployeesRelativeToService($id_ser, $ordering = false, $listable = false)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select($dbo->qn(array(
				'e.id',
				'e.nickname',
				'e.timezone',
			)))
			->from($dbo->qn('#__vikappointments_employee', 'e'))
			->leftjoin($dbo->qn('#__vikappointments_ser_emp_assoc', 'a') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('a.id_employee'))
			->where($dbo->qn('a.id_service') . ' = ' . (int) $id_ser);

		/**
		 * Take only employees that should be listed.
		 *
		 * @since 1.6.5
		 */
		if ($listable === true)
		{
			$q->where($dbo->qn('e.listable') . ' = 1');
			$q->andWhere(array(
				$dbo->qn('active_to') . ' = -1',
				$dbo->qn('active_to') . ' > ' . time(),
			), 'OR');
		}

		if ($ordering)
		{
			/**
			 * Use custom ordering.
			 *
			 * @since 1.6.4
			 */
			$q->order($dbo->qn('a.ordering') . ' ASC');
		}
		
		$dbo->setQuery($q);
		return $dbo->loadAssocList();
	}
	
	/**
	 * Checks if the specified employee is available for the specified checkin.
	 *
	 * @param 	integer  $id_emp 		The employee ID.
	 * @param 	integer  $id_ser 		The service ID.
	 * @param 	integer  $res_id 		The reservation to exlude, if any.
	 * @param 	integer  $checkin 		The checkin timestamp to check.
	 * @param 	integer  $duration 		The duration (in min.) to calculate the checkout.
	 * @param 	integer  $people 		The number of people.
	 * @param 	integer  $max_capacity 	The maximum number of allowed people.
	 * @param 	mixed 	 $dbo 			The database object.
	 *
	 * @return 	boolean  True if available, otherwise false.
	 */
	public static function isEmployeeAvailableFor($id_emp, $id_ser, $res_id, $checkin, $duration, $people, $max_capacity, $dbo = null)
	{
		if (!$dbo)
		{
			$dbo = JFactory::getDbo();
		}
		
		$checkout = $duration * 60;
		
		$start_res = date('H:i', $checkin);
		$exp       = explode(':', $start_res);
		$start_res = $exp[0] * 60 + $exp[1];
		$end_res   = $start_res + $duration;
		
		$wt = self::getEmployeeWorkingTimes($id_emp, $id_ser, $checkin);
		
		/**
		 * Subtract 1 second from the closing time to make sure that
		 * the check-in doesn't start when the working shift ends.
		 *
		 * @since 1.6.5
		 */
		for ($i = 0; $i < count($wt) && !self::isBetween($start_res, $wt[$i]['fromts'], $wt[$i]['endts'] - 1); $i++);

		if ($i >= count($wt))
		{
			// closing time
			return -1;
		}

		// if id_ser NOT -1 and service has own calendar, don't consider 
		// the reservations of the other services (of the same employee)
		$id_ser_app = $id_ser;

		if (!self::hasServiceOwnCalendar($id_ser))
		{
			// don't apply own search
			$id_ser_app = -1; // unset service
		}

		/**
		 * Use timestamp for checkout.
		 *
		 * @since 1.6.2
		 */
		$checkout += $checkin;
		
		$q = "SELECT `r`.`id`, `r`.`people` 
		FROM `#__vikappointments_reservation` AS `r`
		LEFT JOIN `#__vikappointments_service` AS `s` ON `s`.`id`=`r`.`id_service` 
		WHERE `r`.`id` <> $res_id AND `r`.`id_employee` = $id_emp AND 
			(
				(`s`.`has_own_cal` = 0 AND $id_ser_app = -1)
				OR (`s`.`has_own_cal` = 1 AND `r`.`id_service` = $id_ser_app)
			)
			AND `r`.`status` <> 'REMOVED' AND `r`.`status` <> 'CANCELED' AND
			(
				(
					`r`.`checkin_ts` <= $checkin AND $checkin < (`r`.`checkin_ts` + `r`.`duration` * 60 + `r`.`sleep` * 60)
				)
				OR
				(
					`r`.`checkin_ts` < $checkout AND $checkout <= (`r`.`checkin_ts` + `r`.`duration` * 60 + `r`.`sleep` * 60)
				)
				OR
				(
					`r`.`checkin_ts` <= $checkin AND $checkout <= (`r`.`checkin_ts` + `r`.`duration` * 60 + `r`.`sleep` * 60)
				)
				OR
				(
					`r`.`checkin_ts` >= $checkin AND $checkout >= (`r`.`checkin_ts` + `r`.`duration` * 60 + `r`.`sleep` * 60)
				)
				OR
				(
					`r`.`checkin_ts` = $checkin AND $checkout = (`r`.`checkin_ts` + `r`.`duration` * 60 + `r`.`sleep` * 60)
				)
			)";
		
		$dbo->setQuery($q, 0, $max_capacity);
		$rows = $dbo->loadAssocList();
		
		if ($rows)
		{
			$cont_people = 0;

			foreach ($rows as $r)
			{
				$cont_people += $r['people'];
			}
			
			if ($cont_people + $people > $max_capacity)
			{
				// not available
				return 0;
			}
		}
		
		/**
		 * Sum 1 second to the opening time to make sure that
		 * the check-out doesn't end when the working shift starts.
		 *
		 * @since 1.6.5
		 */
		for ($i = 0; $i < count($wt) && !self::isBetween($end_res, $wt[$i]['fromts'] + 1, $wt[$i]['endts']); $i++);
		
		if ($i >= count($wt))
		{
			// closing time
			return -1;
		}

		/**
		 * Get employee timeline in order to make sure that
		 * the selected time slot is accepted by the employee.
		 *
		 * This is needed in order to assign the reservation to an
		 * employee that work on the specified check-in but that
		 * shouldn't accept the requested time slot.
		 *
		 * @since 1.6.5
		 */
		$timeline = VikAppointments::elaborateTimeLine($wt, array(), $id_ser);

		foreach ($timeline as $shift)
		{
			if (isset($shift[$start_res]))
			{
				// time slot found
				return 1;
			}
		}
		
		// not available
		return 0;
	}

	/**
	 * Checks if there is at least an employee available for the specified checkin.
	 *
	 * @param 	integer  $id_ser 		The service ID.
	 * @param 	integer  $checkin 		The checkin timestamp to check.
	 * @param 	integer  $duration 		The duration (in min.) to calculate the checkout.
	 * @param 	integer  $people 		The number of people.
	 * @param 	integer  $max_capacity 	The maximum number of allowed people.
	 * @param 	mixed 	 $dbo 			The database object.
	 *
	 * @return 	boolean  True if available, otherwise false.
	 *
	 * @uses 	isEmployeeAvailableFor()
	 */
	public static function getAvailableEmployeeOnService($id_ser, $checkin, $duration, $people, $max_capacity, $dbo = null)
	{
		if (!$dbo)
		{
			$dbo = JFactory::getDbo();
		}

		$id_ser = (int) $id_ser;
		
		$q = "SELECT `e`.`id`, COUNT(`r`.`id`) AS `count`
		FROM `#__vikappointments_employee` AS `e`
		LEFT JOIN `#__vikappointments_ser_emp_assoc` AS `a` ON `e`.`id` = `a`.`id_employee`
		LEFT JOIN `#__vikappointments_reservation` AS `r` ON `e`.`id` = `r`.`id_employee`
		WHERE `a`.`id_service` = $id_ser
		GROUP BY `e`.`id`
		ORDER BY `count` ASC;";
		
		$dbo->setQuery($q);
		$employees = $dbo->loadAssocList();

		if (!$employees)
		{
			return -1;
		}
		
		foreach ($employees as $e)
		{
			if (self::isEmployeeAvailableFor($e['id'], $id_ser, -1, $checkin, $duration, $people, $max_capacity, $dbo) == 1)
			{
				return $e['id'];
			}
		}
		
		return 0;
	}

	/**
	 * Returns the employee working times for the given day.
	 * In case of 24h working days, the system will extend the ending
	 * time of the last working day in order to support midnight appointments.
	 *
	 * @param 	integer  $id_emp 	 The employee ID.
	 * @param 	integer  $id_ser 	 The service ID.
	 * @param 	integer  $day 		 The date timestamp.
	 * @param 	array 	 $locations  The supported locations.
	 *
	 * @return 	array 	 A list containing the matching working days.
	 *
	 * @uses 	_getEmployeeWorkingTimes()
	 */
	public static function getEmployeeWorkingTimes($id_emp, $id_ser, $day, array $locations = array())
	{
		// get working times for the given day
		$worktimes = self::_getEmployeeWorkingTimes($id_emp, $id_ser, $day, $locations);

		// update current timestamp by one day
		$day = strtotime('+1 day 00:00:00', $day);

		// fallback to obtain the working times for the next day
		$next = self::_getEmployeeWorkingTimes($id_emp, $id_ser, $day, $locations);

		if ($worktimes && $next && $next[0]['fromts'] == 0)
		{
			// We have probably a 24H working time.
			// Extend the last working time with the first
			// one of the next day
			$last = &$worktimes[count($worktimes) - 1];

			$last['endts'] += $next[0]['endts'];
		}

		return $worktimes;
	}

	/**
	 * Returns the employee working times for the given day.
	 *
	 * @param 	integer  $id_emp 	 The employee ID.
	 * @param 	integer  $id_ser 	 The service ID.
	 * @param 	integer  $day 		 The date timestamp.
	 * @param 	array 	 $locations  The supported locations.
	 *
	 * @return 	array 	 A list containing the matching working days.
	 *
	 * @since 	1.6
	 */
	protected static function _getEmployeeWorkingTimes($id_emp, $id_ser, $day, array $locations = array())
	{
		$dbo = JFactory::getDbo();
		
		$date = getdate($day);
		//$timestamp = mktime( 0, 0, 0, $date['mon'], $date['mday'], $date['year'] );
		$timestamp = date('Ymd', $date[0]);
		
		// obtain the working days for the given custom day

		/**
		 * Convert the timestamp in the database to UTC format,
		 * so that an offset lower than 0 won't shift anymore the
		 * dates to the previous ones.
		 *
		 * @see   CONVERT_TZ()
		 *
		 * @since 1.6.2
		 */

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_emp_worktime'))
			->where(array(
				$dbo->qn('id_employee') . ' = ' . (int) $id_emp,
				$dbo->qn('id_service') . ' = ' . (int) $id_ser,
				'DATE_FORMAT(
					CONVERT_TZ(FROM_UNIXTIME(' . $dbo->qn('ts') . '), @@session.time_zone, \'+00:00\'),
					\'%Y%m%d\'
				) = ' . $timestamp,
			))
			->order(array(
				$dbo->qn('closed') . ' DESC',
				$dbo->qn('fromts') . ' ASC',
			));

		if (count($locations))
		{
			$q->andWhere(array(
				$dbo->qn('id_location') . ' = -1',
				$dbo->qn('id_location') . ' IN (' . implode(',', array_map('intval', $locations)) . ')',
			), 'OR');
		}
		
		$dbo->setQuery($q);
		$rows = $dbo->loadAssocList();

		if ($rows)
		{
			if ($rows[0]['closed'])
			{
				// this custom day is closed
				return [];
			}
			
			return $rows;
		}

		// obtain the working days for the given week day

		$q->clear('where');

		$q->where(array(
			$dbo->qn('id_employee') . ' = ' . (int) $id_emp,
			$dbo->qn('id_service') . ' = ' . (int) $id_ser,
			$dbo->qn('day') . ' = ' . $date['wday'],
		));

		/**
		 * Fixed query to ignore weekday when the timestamp is specified.
		 *
		 * @since 1.6.1
		 */
		$q->where($dbo->qn('ts') . ' <= 0');
	
		if (count($locations))
		{
			$q->andWhere(array(
				$dbo->qn('id_location') . ' = -1',
				$dbo->qn('id_location') . ' IN (' . implode(',', array_map('intval', $locations)) . ')',
			), 'OR');
		}
			
		$dbo->setQuery($q);
		$rows = $dbo->loadAssocList();

		if ($rows)
		{
			if ($rows[0]['closed'])
			{
				// this day of the week is closed
				return [];
			}
			
			return $rows;
		}
		
		return [];
	}

	/**
	 * Attaches the working days of the employee to the specified service.
	 *
	 * This method have been moved here from the `empeditservice` controller
	 * as it needed to be used in other sections of the program.
	 *
	 * @param 	integer  $id_service 	The service ID.
	 * @param 	integer  $id_employee 	The employee ID.
	 *
	 * @return 	boolean  True if attached, otherwise false.
	 *
	 * @since 	1.7
	 */
	public static function attachWorkingDays($id_service, $id_employee)
	{
		$dbo = JFactory::getDbo();

		$attached = false;

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_emp_worktime'))
			->where(array(
				$dbo->qn('id_employee') . ' = ' . (int) $id_employee,
				$dbo->qn('id_service') . ' = -1',
			));

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $w)
		{
			/**
			 * Keep a relation with the parent working day
			 * while assigning a new employee to this service.
			 *
			 * @since 1.6.2
			 */
			$w->parent = $w->id;
			
			// inject service ID
			$w->id_service = $id_service;
			// unset ID for insert
			unset($w->id);

			$dbo->insertObject('#__vikappointments_emp_worktime', $w, 'id');

			$attached = $attached || $w->id;
		}

		return $attached;
	}
	
	/**
	 * Updates the status of all the orders out of time to REMOVED.
	 * This method is used to free the slots occupied by pending orders
	 * that haven't been confirmed within the specified range of time.
	 *
	 * Affects only the reservations that match the specified employee ID.
	 *
	 * @param 	integer  $id_emp 	The employee ID.
	 * @param 	mixed 	 $dbo 		The database object.
	 *
	 * @return 	void
	 *
	 * @deprecated 1.8   Use VikAppointmentsModelReservation::checkExpired() instead.
	 */
	public static function removeAllReservationsOutOfTime($id_emp, $dbo = null)
	{
		JModelVAP::getInstance('reservation')->checkExpired(array('id_employee' => $id_emp));
	}
	
	/**
	 * Updates the status of all the orders out of time to REMOVED.
	 * This method is used to free the slots occupied by pending orders
	 * that haven't been confirmed within the specified range of time.
	 *
	 * Affects only the reservations that match the specified service ID.
	 *
	 * @param 	integer  $id_ser 	The service ID.
	 * @param 	mixed 	 $dbo 		The database object.
	 *
	 * @return 	void
	 *
	 * @deprecated 1.8   Use VikAppointmentsModelReservation::checkExpired() instead.
	 */
	public static function removeAllServicesReservationsOutOfTime($id_ser, $dbo = null)
	{	
		JModelVAP::getInstance('reservation')->checkExpired(array('id_service' => $id_ser));
	}

	/**
	 * Returns the list of the payments created by the specified employee.
	 * If the employee doesn't own any custom payment, the global ones
	 * will be returned.
	 *
	 * @param 	integer  $id_emp 	The employee ID.
	 *
	 * @return 	array 	 The payments list.
	 *
	 * @deprecated 1.8   Use getPayments() instead.
	 */
	public static function getAllEmployeePayments($id_emp = 0)
	{
		return static::getPayments('appointments', array('id_employee' => $id_emp));
	}

	/**
	 * Returns a list of available payments.
	 *
	 * @param 	string  $group    The group to which the payments belong (appointments, packages, subscriptions or empsubscriptions).
	 * @param 	array   $options  An array of options to filter the payments, such as "id_employee" to take only the payments assigned
	 *                            to the specified employee (only for appointments) and "strict" to validate the publishing options of
	 *                            the payments (by default it relies on the client).
	 *
	 * @return 	array 	The payments list.
	 *
	 * @since 	1.7
	 */
	public static function getPayments($group = null, array $options = array())
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		$dbo  = JFactory::getDbo();
		$user = JFactory::getUser();

		$q = $dbo->getQuery(true);

		$q->select('p.*');
		$q->from($dbo->qn('#__vikappointments_gpayments', 'p'));

		if ($group == 'appointments')
		{
			// allowed for appointments
			$q->where($dbo->qn('p.appointments') . ' = 1');

			// if employee is set, get global and custom payments
			if (!empty($options['id_employee']))
			{
				$q->andWhere(array(
					$dbo->qn('p.id_employee') . ' <= 0',
					$dbo->qn('p.id_employee') . ' = ' . (int) $options['id_employee'],
				), 'OR');

				// custom payments come first
				$q->order($dbo->qn('p.id_employee') . ' DESC');
			}
			// otherwise get only global payments
			else
			{
				$q->where($dbo->qn('p.id_employee') . ' <= 0');
			}
		}
		else
		{
			// allowed for packages and subscriptions
			$q->where($dbo->qn('p.subscr') . ' = 1');
		}

		if (!isset($options['strict']))
		{
			// strict mode undefined, lean on the current application client
			$options['strict'] = JFactory::getApplication()->isClient('site');
		}

		// check whether we should validate the publishing options of the payments
		if ($options['strict'])
		{
			$q->where($dbo->qn('p.published') . ' = 1');

			/**
			 * Retrieve only the payments that belong to the view
			 * access level of the current user.
			 *
			 * @since 1.6.2
			 */
			$levels = $user->getAuthorisedViewLevels();

			if ($levels)
			{
				$q->where($dbo->qn('p.level') . ' IN (' . implode(', ', $levels) . ')');
			}
		}
			
		$q->order(array(
			// published payments before unpublished ones
			$dbo->qn('p.published') . ' DESC',
			// finally sort by ordering column
			$dbo->qn('p.ordering') . ' ASC',
		));

		/**
		 * Trigger event to allow the plugins to manipulate the query used to retrieve
		 * the available payment gateways.
		 *
		 * @param 	mixed   &$query   The query string or a query builder object.
		 * @param 	string  $group    The group to which the payments belong.
		 * @param 	array   $options  An array of options to filter the payments.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onFetchAvailablePaymentMethods', array(&$q, $group, $options));
		
		$dbo->setQuery($q);
		$payments = $dbo->loadAssocList();

		// check if there is at least a payment
		if (!$payments)
		{
			// no available payments
			return [];
		}

		$count = 0;

		/**
		 * The payment can be available only for trusted customer.
		 * In this case, we have to count the total number of orders
		 * made by the specified user, which must be equals or greater
		 * than the "trust" factor of the payment.
		 *
		 * @since 1.7.1
		 */
		if (!$user->guest)
		{
			$q = $dbo->getQuery(true);
			$q->select('COUNT(1)');
			$q->where(1);

			if ($group == 'appointments')
			{
				// count number of approved appointments
				$approved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'approved' => 1)); 

				$q->from($dbo->qn('#__vikappointments_reservation', 'o'));
				$q->leftjoin($dbo->qn('#__vikappointments_users', 'c') . ' ON ' . $dbo->qn('c.id') . ' = ' . $dbo->qn('o.id_user'));

				$q->andWhere(array(
					$dbo->qn('o.createdby') . ' = ' . $user->id,
					$dbo->qn('c.jid') . ' = ' . $user->id,
				), 'OR');
			}
			else if ($group == 'packages')
			{
				// count number of approved packages
				$approved = JHtml::fetch('vaphtml.status.find', 'code', array('packages' => 1, 'approved' => 1)); 

				$q->from($dbo->qn('#__vikappointments_package_order', 'o'));
				$q->leftjoin($dbo->qn('#__vikappointments_users', 'c') . ' ON ' . $dbo->qn('c.id') . ' = ' . $dbo->qn('o.id_user'));

				$q->andWhere(array(
					$dbo->qn('o.createdby') . ' = ' . $user->id,
					$dbo->qn('c.jid') . ' = ' . $user->id,
				), 'OR');
			}
			else if ($group == 'subscriptions')
			{
				// count number of approved subscriptions (customers)
				$approved = JHtml::fetch('vaphtml.status.find', 'code', array('subscriptions' => 1, 'approved' => 1)); 

				$q->from($dbo->qn('#__vikappointments_subscr_order', 'o'));
				$q->leftjoin($dbo->qn('#__vikappointments_users', 'c') . ' ON ' . $dbo->qn('c.id') . ' = ' . $dbo->qn('o.id_user'));

				$q->where($dbo->qn('c.jid') . ' = ' . $user->id);
			}
			else if ($group == 'empsubscriptions')
			{
				// count number of approved subscriptions (employees)
				$approved = JHtml::fetch('vaphtml.status.find', 'code', array('subscriptions' => 1, 'approved' => 1)); 

				$q->from($dbo->qn('#__vikappointments_subscr_order', 'o'));
				$q->leftjoin($dbo->qn('#__vikappointments_employee', 'e') . ' ON ' . $dbo->qn('e.id') . ' = ' . $dbo->qn('o.id_employee'));

				$q->where($dbo->qn('e.jid') . ' = ' . $user->id);
			}

			if ($approved)
			{
				// filter by approved status
				$q->where($dbo->qn('o.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
			}

			$dbo->setQuery($q);
			$count = (int) $dbo->loadResult();
		}

		// remove all the payments that do not match with the minimum required count
		// of appointments/orders confirmed by the customer
		$payments = array_values(array_filter($payments, function($p) use ($count)
		{
			return $p['trust'] <= $count;
		}));

		if (!$payments)
		{
			// no available payments
			return [];
		}

		// check whether the first available payment is published and belongs
		// to the specified employee
		if ($payments[0]['id_employee'] > 0 && $payments[0]['published'])
		{
			// filter array to remove the global payments
			$payments = array_values(array_filter($payments, function($item)
			{
				return $item['id_employee'] > 0;
			}));	
		}

		/**
		 * Trigger event to allow the plugins to manipulate the list containing
		 * all the payment methods that are going to be displayed.
		 *
		 * @param 	array   &$payments  An array of available payments.
		 * @param 	string  $group      The group to which the payments belong.
		 * @param 	array   $options    An array of options to filter the payments.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onManipulateAvailablePaymentMethods', array(&$payments, $group, $options));

		return array_values($payments);
	}
	
	/**
	 * Returns the payment record that matches the given ID.
	 * The payment can be global or owned by a specific employee.
	 *
	 * @param 	integer  $id_pay 	The payment ID.
	 * @param 	boolean  $strict 	True to get the payment only if it is published.
	 *
	 * @return 	mixed 	 An associative array on success, otherwise null.
	 */
	public static function getPayment($id_pay, $strict = true)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select('*')
			->from($dbo->qn('#__vikappointments_gpayments'))
			->where($dbo->qn('id') . ' = ' . $id_pay);

		if ($strict)
		{
			$q->where($dbo->qn('published') . ' = 1');

			/**
			 * Retrieve only the payments that belong to the view
			 * access level of the current user.
			 *
			 * @since 1.6.2
			 */
			$levels = JFactory::getUser()->getAuthorisedViewLevels();

			if ($levels)
			{
				$q->where($dbo->qn('level') . ' IN (' . implode(', ', $levels) . ')');
			}
		}
		
		$dbo->setQuery($q, 0, 1);
		return $dbo->loadAssoc();
	}

	/////////////////////////////////////////////
	///////////////// LOCATIONS /////////////////
	/////////////////////////////////////////////
	
	/**
	 * Returns the location related to the specified employee, service and checkin.
	 *
	 * @param 	integer  $id_emp 	The employee ID. If not provided, it won't be used.
	 * @param 	integer  $id_ser 	The service ID.
	 * @param 	integer  $ts 		The checkin UNIX timestamp.
	 * @param 	mixed 	 $dbo 		The database object.
	 *
	 * @return 	mixed 	 The location ID on success, otherwise false.
	 *
	 * @deprecated 1.8   Use VikAppointmentsModelWorktime::getLocation() instead.
	 */
	public static function getEmployeeLocationFromTime($id_emp, $id_ser, $ts, $dbo = null)
	{
		if (is_numeric($ts))
		{
			$ts = date('Y-m-d H:i:s', $ts);
		}

		return JModelVAP::getInstance('worktime')->getLocation($ts, $id_ser, $id_emp);
	}

	/**
	 * Method used to return the details of the given location.
	 *
	 * @param 	integer  $id_location 	The location ID.
	 * @param 	mixed 	 $dbo 			The database object.
	 *
	 * @return 	mixed 	 The location details on success, false otherwise.
	 *
	 * @deprecated 1.8   Use VikAppointmentsModelLocation::getInfo() instead.
	 */
	public static function fillEmployeeLocation($id_location, $dbo = null)
	{
		return (array) JModelVAP::getInstance('location')->getInfo($id_location);
	}

	/**
	 * Parses the locations details to create a human-readable string.
	 *
	 * @param 	mixed 	$location 	The location details.
	 *
	 * @return 	string 	The location information as string.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function locationToString($location)
	{
		if (!$location)
		{
			return '';
		}
		
		$location = (array) $location;

		return isset($location['text']) ? $location['text'] : '';
	}

	/**
	 * Calculates the distance between 2 coordinates.
	 *
	 * @param 	float 	$lat_1 	The latitude of the first point.
	 * @param 	float 	$lng_1 	The longitude of the first point.
	 * @param 	float 	$lat_2 	The latitude of the first point.
	 * @param 	float 	$lng_2 	The longitude of the second point.
	 *
	 * @return 	float 	The distance between the 2 points (in km).
	 *
	 * @since 	1.5
	 */
	public static function getGeodeticaDistance($lat_1, $lng_1, $lat_2, $lng_2)
	{
		$lat_1 = $lat_1 * pi() / 180.0;
		$lng_1 = $lng_1 * pi() / 180.0;

		$lat_2 = $lat_2 * pi() / 180.0;
		$lng_2 = $lng_2 * pi() / 180.0;

		/** distance between 2 coordinates
		 * R = 6371 (Eart radius ~6371 km)
		 *
		 * coordinates in radians
		 * lat1, lng1, lat2, lng2
		 *
		 * Calculate the included angle fi
		 * fi = abs( lng1 - lng2 );
		 *
		 * Calculate the third side of the spherical triangle
		 * p = acos( 
		 *      sin(lat2) * sin(lat1) + 
		 *      cos(lat2) * cos(lat1) * 
		 *      cos( fi ) 
		 * )
		 * 
		 * Multiply the third side per the Earth radius (distance in km)
		 * D = p * R;
		 *
		 * MINIFIED EXPRESSION
		 *
		 * acos( 
		 *      sin(lat2) * sin(lat1) + 
		 *      cos(lat2) * cos(lat1) * 
		 *      cos( abs(lng1-lng2) ) 
		 * ) * R
		 *
		 */

		return acos(
			sin($lat_2) * sin($lat_1) + 
			cos($lat_2) * cos($lat_1) *
			cos(abs($lng_1 - $lng_2))
		) * 6371;
	}

	/**
	 * Helper method used to format the distance
	 * in meters and kilometers.
	 *
	 * @param 	float 	$distance 	The distance to format (in km).
	 * @param 	mixed 	$unit 		The unit to use or the filters array 
	 * 								containing the unit parameter.
	 *
	 * @return 	string 	The formatted distance.
	 *
	 * @since 	1.5
	 */
	public static function formatDistance($distance, $unit = null)
	{
		VAPLoader::import('libraries.helpers.distance');

		if (!$unit)
		{
			$input = JFactory::getApplication()->input;
			$unit  = $input->get('filters', array(), 'array');
		}

		if (is_array($unit))
		{
			$unit = isset($unit['distunit']) ? $unit['distunit'] : VAPDistanceHelper::METER;
		}

		// distance is always passed in meters and needs to be converted
		// to the specified unit
		return VAPDistanceHelper::format($distance * 1000, $unit, VAPDistanceHelper::METER);
	}

	/**
	 * Helper method used to convert the distance in kilometers.
	 *
	 * @param 	float 	$distance 	The distance to convert.
	 * @param 	mixed 	$unit 		The unit to use or the filters array 
	 * 								containing the unit parameter.
	 *
	 * @return 	string 	The converted distance.
	 *
	 * @since 	1.6
	 */
	public static function convertDistanceToKilometers($distance, $unit = null)
	{
		VAPLoader::import('libraries.helpers.distance');

		if (!$unit)
		{
			$input = JFactory::getApplication()->input;
			$unit  = $input->get('filters', array(), 'array');
		}

		if (is_array($unit))
		{
			$unit = isset($unit['distunit']) ? $unit['distunit'] : VAPDistanceHelper::KILOMETER;
		}

		return VAPDistanceHelper::convert($distance, VAPDistanceHelper::KILOMETER, $unit);
	}
	
	/**
	 * Returns the timezone of the given employee.
	 *
	 * @param 	integer  $id_emp  The employee ID.
	 *
	 * @return 	string 	 The employee timezone.
	 *
	 * @deprecated 1.8   Use VikAppointmentsModelEmployee::getTimezone() instead.
	 */
	public static function getEmployeeTimezone($id_emp)
	{
		return JModelVAP::getInstance('employee')->getTimezone($id_emp);
	}
	
	/**
	 * Alters the server timezone with the given one.
	 *
	 * @param 	mixed 	$tz  The new timezone to set.
	 *
	 * @return 	mixed 	The changing result.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function setCurrentTimezone($tz)
	{
		if (!$tz || !VAPFactory::getConfig()->getBool('multitimezone'))
		{
			return false;
		}

		return date_default_timezone_set($tz);
	}

	/////////////////////////////////////////////
	////////////////// REVIEWS //////////////////
	/////////////////////////////////////////////
	
	/**
	 * Helper method used to round a float value to the closest half.
	 *
	 * @param 	float 	$d 	The amount to round.
	 *
	 * @return 	float 	The rounded amount.
	 */
	public static function roundHalfClosest($d)
	{
		$floor = floor($d * 2) / 2;
		$ceil  =  ceil($d * 2) / 2;
		
		if (abs($d - $floor) < abs($d - $ceil))
		{
			return $floor;
		}

		return $ceil;
	}
	
	/**
	 * Loads the reviews for the given entity.
	 *
	 * @todo 	Need a method refactoring.
	 *
	 * @param 	string 	 $figure 	The entity to get (employee or service).
	 * @param 	integer  $id 		The entity ID.
	 * @param 	integer  $start 	The limit start.
	 *
	 * @return 	array 	 The reviews list.
	 *
	 * @since 	1.4
	 */
	public static function loadReviews($figure, $id, $start = 0)
	{
		$result = new stdClass;
		$result->size  = 0;
		$result->votes = 0;

		$dbo = JFactory::getDbo();

		$config = VAPFactory::getConfig();
		
		$lim  = $config->getUint('revlimlist');
		$lim0 = $start;

		$session  = JFactory::getSession();
		$ordering = $session->get('reviewsOrdering', '', 'vikappointments');

		if (empty($ordering))
		{
			$ordering = array(
				'by' => 'timestamp',
				'mode' => 'DESC',
			);
		}

		$q = $dbo->getQuery(true);

		$q->select('SQL_CALC_FOUND_ROWS r.*');
		$q->select($dbo->qn('u.image'));

		$q->from($dbo->qn('#__vikappointments_reviews', 'r'));
		
		/**
		 * Fixed LEFT JOIN which was loading all the customers that was not assigned to a
		 * specific Joomla/WordPress user ID. The JOIN must exclude all the records that
		 * owns a `jid` equals or lower than 0.
		 *
		 * @since 1.6.3
		 */
		$q->leftjoin($dbo->qn('#__vikappointments_users', 'u')
			. ' ON ' . $dbo->qn('r.jid') . ' = ' . $dbo->qn('u.jid') . ' AND ' . $dbo->qn('r.jid') . ' > 0');

		$q->where(array(
			$dbo->qn('r.id_' . $figure) . ' = ' . (int) $id,
			$dbo->qn('r.published') . ' = 1',
			$dbo->qn('r.comment') . ' <> \'\'',
		));
		
		if ($config->getBool('revlangfilter'))
		{
			$q->where($dbo->qn('r.langtag') . ' = ' . $dbo->q(JFactory::getLanguage()->getTag()));
		}

		$q->order($dbo->qn('r.' . $ordering['by']) . ' ' . $ordering['mode']);

		$dbo->setQuery($q, $lim0, $lim);
		$result->rows = $dbo->loadObjectList();

		if ($result->rows)
		{
			$dbo->setQuery('SELECT FOUND_ROWS();');
			$result->size = (int) $dbo->loadResult();
		}

		/**
		 * Always look for any reviews without comment.
		 * Before 1.7 version, the votes were calculated
		 * only in case the entity owned at least a review.
		 *
		 * @since 1.7
		 */
		$q = $dbo->getQuery(true)
			->select('COUNT(1)')
			->from($dbo->qn('#__vikappointments_reviews'))
			->where(array(
				$dbo->qn('id_' . $figure) . ' = ' . (int) $id,
				$dbo->qn('published') . ' = 1',
			));

		$dbo->setQuery($q);
		$result->votes = (int) $dbo->loadResult();
		
		return $result;
	}
	
	/**
	 * Returns the links used to switch ordering.
	 *
	 * @param 	string 	$base 	The base URI.
	 * @param 	string 	$by 	The current ordering column.
	 * @param 	string 	$mode 	The current ordering direction.
	 *
	 * @return 	array 	An array containing the link details.
	 *
	 * @since 	1.4
	 */
	public static function getReviewsOrderingLinks($base, $by, $mode)
	{
		$columns = array(
			'timestamp' => 'DESC',
			'rating' 	=> 'DESC',
		);
		
		$session  = JFactory::getSession();
		$ordering = $session->get('reviewsOrdering', '', 'vikappointments');

		if (empty($ordering))
		{
			$ordering = array(
				'by' 	=> 'timestamp',
				'mode' 	=> 'DESC',
			);
		}
		
		if (empty($by))
		{
			$by   = $ordering['by'];
			$mode = $ordering['mode'];
		}
		
		if (!array_key_exists($by, $columns))
		{
			$by = $ordering['by'];
		}
		
		$links = array();

		foreach ($columns as $col => $m)
		{
			$arr = array(
				'uri' 		=> '',
				'active' 	=> false,
				'mode' 		=> '',
				'name' 		=> JText::translate('VAPREVIEWORDERING' . strtoupper($col)),
			);
			
			$l = "{$base}&revordby={$col}&revordmode=";

			if ($by == $col)
			{
				$l .= $mode == 'ASC' ? 'DESC' : 'ASC';
				$arr['active'] 	= true;
				$arr['mode'] 	= $mode;
			}
			else
			{
				$l .= $m;
			}

			$arr['uri'] = $l;
			
			$links[] = $arr;
		}
		
		$ordering['by'] 	= $by;
		$ordering['mode'] 	= $mode;
		
		$session->set('reviewsOrdering', $ordering, 'vikappointments');
		
		return $links;
	}

	/**
	 * Helper method used to check if the current customer
	 * is allowed to leave a review for the specified employee.
	 *
	 * @param   integer  $id_emp  The ID of the employee.
	 *
	 * @return  boolean  True if the review can be left, otherwise false.
	 *
	 * @uses    userCanLeaveReview()
	 *
	 * @since   1.4
	 */
	public static function userCanLeaveEmployeeReview($id_emp)
	{	
		if (!self::isEmployeesReviewsEnabled())
		{
			// reviews for employees are not enabled
			return false;
		}
		
		// evaluate system criteria
		return self::userCanLeaveReview($id_emp, 'employee');
	}

	/**
	 * Helper method used to check if the current customer
	 * is allowed to leave a review for the specified service.
	 *
	 * @param 	integer  $id_ser 	The ID of the service.
	 *
	 * @return 	boolean  True if the review can be left, otherwise false.
	 *
	 * @uses 	userCanLeaveReview()
	 *
	 * @since 	1.4
	 */
	public static function userCanLeaveServiceReview($id_ser)
	{	
		if (!self::isServicesReviewsEnabled())
		{
			// reviews for services are not enabled
			return false;
		}

		// evaluate system criteria
		return self::userCanLeaveReview($id_ser, 'service');
	}

	/**
	 * Helper method used to check if the current customer
	 * is allowed to leave a review.
	 *
	 * @param 	integer  $id 	The ID of the entity.
	 * @param 	string 	 $type  The entity type (employee or service).
	 *
	 * @return 	boolean  True if the review can be left, otherwise false.
	 *
	 * @since 	1.6
	 */
	protected static function userCanLeaveReview($id, $type)
	{	
		$user 		= JFactory::getUser();
		$dispatcher = VAPFactory::getEventDispatcher();
		
		/**
		 * Trigger event to override the default system criteria used to
		 * validate whether a review should be left or not.
		 *
		 * @param 	string   $type  The entity type (service or employee).
		 * @param 	integer  $id    The entity ID for which the review should be left.
		 * @param 	JUser 	 $user 	The current user object.
		 *
		 * @return 	boolean  True if the review should be left.
		 *
		 * @since 	1.6
		 */
		if ($dispatcher->is('onValidateLeaveReview', array($type, $id, $user)))
		{
			// ignore the default system criteria as the plugin overrided it
			// to allow the user to leave the review
			return true;
		}

		if ($user->guest)
		{
			// user not logged in
			return false;
		}

		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select(1)
			->from($dbo->qn('#__vikappointments_reviews'))
			->where(array(
				$dbo->qn('id_' . $type) . ' = ' . (int) $id,
				$dbo->qn('jid') . ' = ' . $user->id,
			));

		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			// user already wrote a review for this entity
			return false;
		}

		$q = $dbo->getQuery(true)
			->select(1)
			->from($dbo->qn('#__vikappointments_reservation', 'r'))
			->leftjoin($dbo->qn('#__vikappointments_users', 'u') . ' ON ' . $dbo->qn('r.id_user') . ' = ' . $dbo->qn('u.id'))
			->where(array(
				$dbo->qn('r.id_' . $type) . ' = ' . (int) $id,
				$dbo->qn('u.jid') . ' = ' . $user->id,
				$dbo->qn('r.checkin_ts') . ' < ' . $dbo->q(JFactory::getDate()->toSql()),
			));

		// get approved statuses
		$approved = JHtml::fetch('vaphtml.status.find', 'code', array('appointments' => 1, 'approved' => 1));

		if ($approved)
		{
			// filter by approved status
			$q->where($dbo->qn('r.status') . ' IN (' . implode(',', array_map(array($dbo, 'q'), $approved)) . ')');
		}
		
		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		if ($dbo->getNumRows() == 0)
		{
			// user never placed an order for this entity
			return false;
		}
		
		// the user can leave the review
		return true;
	}

	/////////////////////////////////////////////
	/////////////// SUBSCRIPTIONS ///////////////
	/////////////////////////////////////////////

	/**
	 * Checks if there is at least a published subscription.
	 *
	 * @return 	boolean  True if any, false otherwise.
	 *
	 * @since 	   1.5
	 * @deprecated 1.8   Use VAPSubscriptions::has() instead.
	 */
	public static function isSubscriptions()
	{
		// Always search for employees subscriptions because when this method was
		// written the subscriptions for the customers were not exist.
		VAPLoader::import('libraries.models.subscriptions');
		return VAPSubscriptions::has($group = 1);
	}
	
	/**
	 * Returns the trial subscription (if any).
	 *
	 * @param 	boolean  $translate  True to translate the subscriptions, false otherwise.
	 *
	 * @return 	array 	 The trial subscription. False if it doesn't exist.
	 *
	 * @since 	   1.5
	 * @deprecated 1.8   Use VAPSubscriptions::getTrial() instead.
	 */
	public static function getTrialSubscription($translate = true)
	{
		// Always search for employees subscriptions because when this method was
		// written the subscriptions for the customers were not exist.
		VAPLoader::import('libraries.models.subscriptions');
		return VAPSubscriptions::getTrial($group = 1, $translate);
	}
	
	/**
	 * Returns a list of active subscriptions.
	 *
	 * @param 	boolean  $translate  True to translate the subscriptions, false otherwise.
	 *
	 * @return 	array 	 The subscriptions list. False if the list is empty.
	 *
	 * @since 	   1.5
	 * @deprecated 1.8   Use VAPSubscriptions::getList() instead.
	 */
	public static function getSubscriptions($trial = false, $translate = true)
	{
		// Always search for employees subscriptions because when this method was
		// written the subscriptions for the customers were not exist.
		VAPLoader::import('libraries.models.subscriptions');
		return VAPSubscriptions::getList($group = 1, $trial, $translate);
	}

	/**
	 * Returns the details of the given subscription.
	 *
	 * @param 	integer  $id 		 The subscription ID.
	 * @param 	boolean  $strict 	 True to get the subscription only if it is published.
	 * @param 	boolean  $translate  True to translate the subscriptions, false otherwise.
	 *
	 * @return 	array 	 The trial subscription. False if it doesn't exist.
	 *
	 * @since 	   1.6
	 * @deprecated 1.8   Use VAPSubscriptions::get() instead.
	 */
	public static function getSubscription($id, $strict = true, $translate = true)
	{
		// Always search for employees subscriptions because when this method was
		// written the subscriptions for the customers were not exist.
		VAPLoader::import('libraries.models.subscriptions');
		return VAPSubscriptions::get($id, $group = 1, $strict, $translate);
	}

	/**
	 * Returns a list of subscriptions matching the given query.
	 *
	 * @param 	array  	 $where 	 An associative array containing the query terms.
	 * @param 	integer  $lim 		 The number of records to retrieve. Null to ignore this value.
	 * @param 	boolean  $translate  True to translate the subscriptions, false otherwise.
	 *
	 * @return 	array 	 The subscriptions list. False if the list is empty. The associative array
	 * 					 of the subscription in case $lim is equals to 1.
	 *
	 * @since 	   1.6
	 * @deprecated 1.8   Use VAPSubscriptions::search() instead.
	 */
	public static function _getSubscriptions(array $where = array(), $lim = null, $translate = false)
	{
		// Always search for employees subscriptions because when this method was
		// written the subscriptions for the customers were not exist.
		$where['group'] = 1;

		VAPLoader::import('libraries.models.subscriptions');
		return VAPSubscriptions::search($where, $lim, $translate);
	}
	
	/**
	 * Method used to extend the subscription lifetime of the given employee.
	 *
	 * @param 	array 	$subscr 	The subscription purchased.
	 * @param 	array 	$employee 	The employee details.
	 *
	 * @return 	void
	 *
	 * @since      1.5
	 * @deprecated 1.8  Use JModelSubscrorder::extendEmployee() instead.
	 */
	public static function applyAdditionalSubscription($subscr, $employee)
	{
		JModelVAP::getInstance('subscrorder')->extendEmployee($employee, $subscr);
	}

	/////////////////////////////////////////////
	///////////////// CUSTOMERS /////////////////
	/////////////////////////////////////////////

	/**
	 * Returns the details of the given customer.
	 *
	 * @param 	mixed  $id  The customer ID. If not specified,
	 * 						the customer assigned to the current
	 * 						user will be retrieved, if any.
	 *
	 * @return 	mixed  The customer object if exists, NULL otherwise
	 *
	 * @since 	1.7
	 */
	public static function getCustomer($id = null)
	{
		// import customer object handler
		VAPLoader::import('libraries.models.customer');

		try
		{
			// return customer details
			return VAPCustomer::getInstance($id);
		}
		catch (Exception $e)
		{
			// catch any errors (probably user not found)
		}

		// unable to fetch customer data, return null
		return null;
	}

	/**
	 * Returns the timezone of the currently logged-in user.
	 *
	 * @return 	DateTimeZone
	 *
	 * @since 	1.7
	 */
	public static function getUserTimezone()
	{
		static $tz = null;

		// fetch timezone only once
		if (!$tz)
		{
			$app = JFactory::getApplication();

			// first of all, extract timezone from user cookie
			$tz = $app->input->cookie->getString('vikappointments_user_timezone', null);

			// ignore cookie in case we are accessing from the back-end
			if (!$tz || $app->isClient('administrator'))
			{
				$user = JFactory::getUser();

				if (!$user->guest)
				{
					// use current user timezone
					$tz = $user->getTimezone();
				}
				else
				{
					// use the default system timezone
					$tz = $app->get('offset', 'UTC');
				}
			}

			if (!$tz instanceof DateTimeZone)
			{
				$tz = new DateTimeZone($tz);
			}
		}

		return $tz;
	}

	/////////////////////////////////////////////
	////////////////// ORDERS ///////////////////
	/////////////////////////////////////////////
	
	/**
	 * Returns the order details of the given ID.
	 *
	 * @param 	integer  $order_id 	 The order number (ID).
	 * @param 	string 	 $order_key  The order key (sid). If not provided, 
	 * 								 this field won't used while fetching the records.
	 * @param 	string 	 $langtag 	 The translation language. If not provided
	 * 								 it will be used the default one.
	 * 
	 * @return 	mixed 	 A list of orders on success, otherwise false.
	 *
	 * @deprecated 1.8   Use VAPOrderFactory::getAppointments() instead.
	 */
	public static function fetchOrderDetails($order_id, $order_key = null, $langtag = null)
	{
		try
		{
			VAPLoader::import('libraries.order.factory');
			return VAPOrderFactory::getAppointments($order_id, $langtag, array('sid' => $order_key));
		}
		catch (Exception $e)
		{

		}

		return false;
	}

	/**
	 * Countes the number of services within the purchased packages that can be still used.
	 *
	 * @param 	integer  $id_ser 	The service ID.
	 * @param 	integer  $id_user 	The user ID. If not provided, 
	 * 								the current user will be retrieved.
	 *
	 * @return 	integer  The remaining number of services.
	 *
	 * @deprecated 1.8   Use VikAppointmentsModelPackorder::countRemaining() instead.
	 */
	public static function countRemainingServicePackages($id_ser, $id_user = null)
	{
		return JModelVAP::getInstance('packorder')->countRemaining($id_ser, $id_user);
	}

	/**
	 * Redeems the remaining packages for the services contained within the cart.
	 *
	 * @param 	mixed 	 $cart 	The cart instance.
	 *
	 * @return 	boolean  True in case at least a package was redeemed, false otherwise.
	 */
	public static function usePackagesForServicesInCart($cart)
	{
		// get customer details of the logged-in user
		$customer = static::getCustomer();

		if (!$customer)
		{
			// guest user, nothing to redeem
			return false;
		}

		$redeemed = false;

		$lookup = array();

		// iterate items in cart
		foreach ($cart->getItemsList() as $item)
		{
			// reset discounted flag
			$item->setDiscounted(0);

			if (!array_key_exists($item->getID(), $lookup))
			{
				// load remaining services to redeem
				$lookup[$item->getID()] = self::countRemainingServicePackages($item->getServiceID());
			}

			// try to redeem a package for each participant
			if ($lookup[$item->getID()] - $item->getPeople() >= 0)
			{
				// decrease lookup
				$lookup[$item->getID()] -= $item->getPeople();
				// mark product as discounted
				$item->setDiscounted(1);

				$redeemed = true;
			}
			else
			{
				// unset price in case of a valid subscription for the booked service
				if ($customer->isSubscribed($item->getServiceID(), $item->getCheckinDate()))
				{
					$item->setPrice(0);
				}
			}
		}

		if ($redeemed)
		{
			// update cart on success
			$cart->store();
		}

		return $redeemed;
	}

	/**
	 * Checks whether the customer is compliant with the mandatory purchase setting,
	 * by ensuring that the total number of appointments in cart is equals or lower
	 * than the total number of packages that can be redeemed.
	 *
	 * In case the mentioned setting is disabled, this method will always return true.
	 *
	 * @param 	mixed 	 $cart 	The cart instance.
	 *
	 * @return 	boolean  True if compliant, false otherwise.
	 *
	 * @since 	1.7
	 */
	public static function isCompliantWithMandatoryPackage($cart)
	{
		if (VAPFactory::getConfig()->getBool('packsmandatory') == false)
		{
			// feature disabled, doesn't need to go ahead
			return true;
		}

		if (JFactory::getUser()->guest)
		{
			// guest user, cannot be compliant
			return false;
		}

		$dispatcher = VAPFactory::getEventDispatcher();

		$lookup = array();

		// iterate items in cart
		foreach ($cart->getItemsList() as $item)
		{
			/**
			 * Checks whether a cart item (service) is compliant with the mandatory
			 * package setting. Triggers only when globally enabled.
			 *
			 * @param   VAPCartItem  $item  The cart item to validate.
			 * @param   VAPCart      $cart  The cart instance.
			 *
			 * @return 	boolean      True to ignore the validation, false in case
			 *                       the item is not compliant, null to fallback
			 *                       to the default validation.
			 * 
			 * @since 	1.7.4
			 */
			$valid = $dispatcher->trigger('onValidateMandatoryPackageCompliance', array($item, $cart));

			if (in_array(true, $valid, true))
			{
				// ignore item validation
				continue;
			}
			else if (in_array(false, $valid, true))
			{
				// item not compliant
				return false;
			}

			if (!array_key_exists($item->getID(), $lookup))
			{
				// load remaining services to redeem
				$lookup[$item->getID()] = self::countRemainingServicePackages($item->getServiceID());
			}

			// try to redeem a package for each participant
			if ($lookup[$item->getID()] - $item->getPeople() < 0)
			{
				// not enough packages to redeem, cannot book the appointment
				return false;
			}

			// decrease lookup
			$lookup[$item->getID()] -= $item->getPeople();
		}

		// enough packages to redeem, can book the appointment
		return true;
	}

	/**
	 * Registers all the packages that have been used to purchase a service.
	 * 
	 * @param 	array 	 $order_details  The order details list.
	 * @param 	boolean  $increase 		 True to increase the number of used packages,
	 * 									 false to free them.
	 *
	 * @return 	integer  The number of packages used.
	 *
	 * @deprecated 1.8   Use VikAppointmentsModelPackorder::usePackages() instead.
	 */
	public static function registerPackagesUsed($order_details, $increase = true)
	{
		if (is_array($order_details))
		{
			// order details retrieved with fetchOrderDetails
			$order_details = $order_details[0]['id'];
		}

		return JModelVAP::getInstance('packorder')->usePackages($order_details, $increase);
	}

	/////////////////////////////////////////////
	/////////////// ADMIN E-MAIL ////////////////
	/////////////////////////////////////////////

	/**
	 * Loads the admin e-mail template that should be parsed.
	 *
	 * @param 	array 	$orders 	The orders list.
	 *
	 * @return 	string 	The e-mail template.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function loadAdminEmailTemplate(array $orders)
	{
		ob_start();
		include VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_tmpls' . DIRECTORY_SEPARATOR . VAPFactory::getConfig()->get('adminmailtmpl');
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * Sends the notification e-mail to the administrator(s)
	 * and related employee(s).
	 *
	 * @param 	array 	$order_details 	The orders that should be notified.
	 *
	 * @return 	void
	 */
	public static function sendAdminEmail($order_details)
	{
		if (!$order_details)
		{
			return;
		}
		
		self::loadLanguage(self::getDefaultLanguage('site'));
		
		$send_when 			= self::getSendMailWhen();
		$admin_mail_list 	= self::getAdminMailList();
		$sendermail 		= self::getSenderMail();
		$adminname 			= VAPFactory::getConfig()->get('agencyname');

		$subject = JText::sprintf('VAPADMINEMAILSUBJECT', $adminname);

		/**
		 * Parse e-mail subject to replace tags with the
		 * related order details.
		 *
		 * @since 1.6.6
		 */
		static::parseEmailSubject($subject, $order_details);
		
		$admin_tmpl = self::loadAdminEmailTemplate($order_details);
		$html_mess 	= self::parseAdminEmailTemplate($admin_tmpl, $order_details);

		$emp_details = self::filterOrdersByEmployee($order_details);

		$ics_prop = self::getAttachmentPropertiesICS();
		$csv_prop = self::getAttachmentPropertiesCSV();

		$vik = VAPApplication::getInstance();
		
		// CUSTOM FIELDS ATTACHMENTS
		$order_details[0]['uploads'] = json_decode($order_details[0]['uploads']);
		$custom_f_attach = self::includeMailAttachments($order_details);
		
		if ($send_when['admin'] != 0 && ($send_when['admin'] == 2 || $order_details[0]['status'] == 'CONFIRMED'))
		{
			$admin_attachments = array();

			// ADMIN ICS GENERATOR //
			$ics_file_path = "";
			if ($ics_prop['admin'])
			{
				$ics_file_path = self::composeFileICS($order_details[0]['id'], true, -1);

				if (!empty($ics_file_path))
				{
					$admin_attachments[] = $ics_file_path;
				}
			}

			// ADMIN CSV GENERATOR //
			$csv_file_path = "";
			if ($csv_prop['admin'])
			{
				$csv_file_path = self::composeFileCSV($order_details[0]['id'], true, -1);
				if (!empty($csv_file_path))
				{
					$admin_attachments[] = $csv_file_path;
				}
			}
			
			$admin_attachments = array_merge($admin_attachments, $custom_f_attach);
			
			foreach ($admin_mail_list as $_m)
			{
				$vik->sendMail($sendermail, $adminname, $_m, $sendermail, $subject, $html_mess, $admin_attachments, true);
			}
			
			if (!empty($ics_file_path) && file_exists($ics_file_path))
			{
				unlink($ics_file_path);
			}

			if (!empty($csv_file_path) && file_exists($csv_file_path))
			{
				unlink($csv_file_path);
			}
		}

		if ($send_when['employee'] != 0 && ($send_when['employee'] == 2 || $order_details[0]['status'] == 'CONFIRMED'))
		{
			foreach ($emp_details as $emp_mail => $emp_order_details)
			{
				$emp_attachments = array();
				
				// EMPLOYEE ICS GENERATOR //
				$ics_file_path = "";
				if ($ics_prop['employee'])
				{
					$ics_file_path = self::composeFileICS($order_details[0]['id'], true, $emp_order_details[0]['id_employee']);
					if (!empty($ics_file_path))
					{
						$emp_attachments[] = $ics_file_path;
					}
				}

				// EMPLOYEE CSV GENERATOR //
				$csv_file_path = "";
				if ($csv_prop['employee'])
				{
					$csv_file_path = self::composeFileCSV($order_details[0]['id'], true, $emp_order_details[0]['id_employee']);
					if (!empty($csv_file_path))
					{
						$emp_attachments[] = $csv_file_path;
					}
				}
				
				$emp_attachments = array_merge($emp_attachments, $custom_f_attach);

				/**
				 * Reload employee e-mail template using the orders related to the specified employee.
				 *
				 * @since 1.6
				 */
				$emp_tmpl = self::loadEmployeeEmailTemplate($emp_order_details);
				$_html 	  = self::parseEmployeesEmailTemplate($emp_tmpl, $emp_order_details);
				
				$vik->sendMail($sendermail, $adminname, $emp_mail, $admin_mail_list[0], $subject, $_html, $emp_attachments, true);
				
				if (!empty($ics_file_path) && file_exists($ics_file_path))
				{
					unlink($ics_file_path);
				}

				if (!empty($csv_file_path) && file_exists($csv_file_path))
				{
					unlink($csv_file_path);
				}
			}
		}

		self::destroyMailAttachments($custom_f_attach);
	}
	
	/**
	 * Method used to parse the e-mail template for the administrator(s).
	 *
	 * @param 	string 	$tmpl 			The template string to parse.
	 * @param 	array 	$order_details 	The orders list.
	 *
	 * @return 	string 	The parsed template.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function parseAdminEmailTemplate($tmpl, $order_details)
	{
		// parse coupon string

		if (!empty($order_details[0]['coupon_str']))
		{
			list($code, $pt, $value) = explode(';;', $order_details[0]['coupon_str']);
			$coupon_str = $code . " : " . ($pt == 1 ? $value . '%' : self::printPriceCurrencySymb($value));
		}
		else
		{
			$coupon_str = JText::translate('VAPADMINEMAILNOCOUPON');
		}

		// parse payment name

		$payment_name = !empty($order_details[0]['payment_name']) ? $order_details[0]['payment_name'] : JText::translate('VAPADMINEMAILNOPAYMENT');

		// parse order total cost

		$order_total = self::printPriceCurrencySymb($order_details[0]['total_cost'] + $order_details[0]['payment_charge']);

		// fetch appointment details

		/**
		 * @deprecated 1.8 	the appointment details are parsed within the e-mail template
		 */
		$appointment_details = "";
		for ($i = ($order_details[0]['id_service'] == -1 ? 1 : 0); $i < count($order_details); $i++)
		{
			$row = $order_details[$i];
			
			$appointment_details .= '<div class="appointment">';
			$appointment_details .= '<div class="content ' . ($row['total_cost'] > 0 || count($row['options']) ? '' : 'fill-bottom') . '">';
			$appointment_details .= $row['sname'] . ' - ' . $row['ename'] . '<br />';
			$appointment_details .= $row['formatted_checkin'] . ' - ' . $row['formatted_duration'];
			$appointment_details .= '</div>';
			
			if (count($row['options']))
			{
				$appointment_details .= '<div class="options-list'.($row['total_cost'] > 0 ? '' : ' fill-bottom').'">';

				foreach ($row['options'] as $opt)
				{
					$appointment_details .= '<div class="option">';
					$appointment_details .= '<div class="name">- ' . $opt['full_name'] . '</div>';
					$appointment_details .= '<div class="quantity">' . $opt['formatted_quantity'] . '</div>';
					if ($opt['price'] != 0)
					{
						$appointment_details .= '<div class="price">' . $opt['formatted_price'] . '</div>';
					}
					$appointment_details .= '</div>';
				}
				$appointment_details .= '</div>';
			}

			if ($row['total_cost'] > 0)
			{
				$appointment_details .= '<div class="cost"><span>' . $row['formatted_total'] . '</span></div>';
			}

			$appointment_details .= '</div>';
		}

		// customer details
		
		$custom_fields = json_decode($order_details[0]['custom_f'], true);

		/**
		 * @deprecated 1.8 	the customer details are parsed within the e-mail template
		 */
		$customer_details = "";
		foreach ($custom_fields as $kc => $vc)
		{
			$customer_details .= '<div class="info">';
			$customer_details .= '<div class="label">'.JText::translate($kc).':</div>';
			$customer_details .= '<div class="value">'.$vc.'</div>';
			$customer_details .= '</div>';
		}

		// joomla user details
		$user_details = '';
		if (strlen($order_details[0]['user_email']) > 0)
		{
			/**
			 * @deprecated 1.8 	the joomla user details should be parsed within the e-mail template (if needed)
			 */

			$user_details = '<div class="separator"> </div>
			<div class="customer-details-wrapper">
				<div class="title">'.JText::translate('VAPUSERDETAILS').'</div>
					<div class="customer-details">
						<div class="info">
							<div class="label">'.JText::translate('VAPREGFULLNAME').':</div>
							<div class="value">'.$order_details[0]['user_name'].'</div>
						</div>
						<div class="info">
							<div class="label">'.JText::translate('VAPREGUNAME').':</div>
							<div class="value">'.$order_details[0]['user_uname'].'</div>
						</div>
						<div class="info">
							<div class="label">'.JText::translate('VAPREGEMAIL').':</div>
							<div class="value">'.$order_details[0]['user_email'].'</div>
						</div>
					</div>
				</div>';
		}

		$vik = VAPApplication::getInstance();

		// order link

		$order_link_href = "index.php?option=com_vikappointments&view=order&ordnum={$order_details[0]['id']}&ordkey={$order_details[0]['sid']}";
		$order_link_href = $vik->routeForExternalUse($order_link_href);

		$confirmation_link = "";
		if ($order_details[0]['status'] == 'PENDING')
		{
			$confirmation_link = "index.php?option=com_vikappointments&task=confirmord&oid={$order_details[0]['id']}&conf_key={$order_details[0]['conf_key']}";
			$confirmation_link = $vik->routeForExternalUse($confirmation_link);

			// $confirmation_link .= '<div class="order-link">';
			// $confirmation_link .= '<div class="title">'.JText::translate('VAPCONFIRMATIONLINK').'</div>';
			// $confirmation_link .= '<div class="content">';
			// $confirmation_link .= '<a href="'.$confirmation_link_href.'">'.$confirmation_link_href.'</a>';
			// $confirmation_link .= '</div>';
			// $confirmation_link .= '</div>';
		}

		// logo

		$logo_name = VAPFactory::getConfig()->get('companylogo');
		$agency_name = VAPFactory::getConfig()->get('agencyname');

		$logo_str = "";
		if (!empty($logo_name) && file_exists(VAPMEDIA . DIRECTORY_SEPARATOR . $logo_name))
		{ 
			$logo_str = '<img src="' . VAPMEDIA_URI . $logo_name . '" alt="' . htmlspecialchars($agency_name) . '" />';
		}

		// order status color

		switch ($order_details[0]['status'])
		{
			case 'CONFIRMED':
				$order_status_color = '#006600';
				break;

			case 'PENDING':
				$order_status_color = '#D9A300';
				break;

			case 'REMOVED':
				$order_status_color = '#B20000';
				break;

			case 'CANCELED':
				$order_status_color = '#F01B17';
				break;

			default:
				$order_status_color = 'inherit';
		}

		// replace tags from template

		$tmpl = str_replace('{company_name}'		, $agency_name											, $tmpl);
		$tmpl = str_replace('{order_number}'		, $order_details[0]['id']								, $tmpl);
		$tmpl = str_replace('{order_key}'			, $order_details[0]['sid']								, $tmpl);
		$tmpl = str_replace('{order_status_class}'	, strtolower($order_details[0]['status'])				, $tmpl);
		$tmpl = str_replace('{order_status}'		, JText::translate('VAPSTATUS' . $order_details[0]['status'])	, $tmpl);
		$tmpl = str_replace('{order_status_color}'	, $order_status_color									, $tmpl);
		$tmpl = str_replace('{order_payment}'		, $payment_name											, $tmpl);
		$tmpl = str_replace('{order_coupon_code}'	, $coupon_str											, $tmpl);
		$tmpl = str_replace('{order_total_cost}'	, $order_total 											, $tmpl);
		$tmpl = str_replace('{order_link}'			, $order_link_href										, $tmpl);
		$tmpl = str_replace('{confirmation_link}'	, $confirmation_link 									, $tmpl);
		$tmpl = str_replace('{logo}'				, $logo_str												, $tmpl);

		/**
		 * @deprecated 1.8
		 */
		$tmpl = str_replace('{appointment_details}'	, $appointment_details	, $tmpl);
		$tmpl = str_replace('{customer_details}'	, $customer_details		, $tmpl);
		$tmpl = str_replace('{user_details}'		, $user_details			, $tmpl);

		return $tmpl;
	}

	/////////////////////////////////////////////
	///////////// EMPLOYEE E-MAIL ///////////////
	/////////////////////////////////////////////

	/**
	 * Filters the orders (in case of shop enabled) by employee.
	 * The method will return an associative key built as follows:
	 * - the key is the e-mail of the employee;
	 * - the value is the list of all the related orders.
	 *
	 * @param 	array 	$order_details 	The orders to filter.
	 *
	 * @return 	array 	The resulting list.
	 */
	public static function filterOrdersByEmployee($order_details)
	{
		$arr = array();

		for ($i = ($order_details[0]['id_service'] == -1 ? 1 : 0); $i < count($order_details); $i++)
		{
			$row = $order_details[$i];

			if (!empty($row['empmail'])) 
			{
				if (empty($arr[$row['empmail']]))
				{
					$arr[$row['empmail']] = array();
					
					if ($i != 0)
					{
						// push always the parent order
						$arr[$row['empmail']][] = $order_details[0];
						// unset total cost
						$arr[$row['empmail']][0]['total_cost'] = 0.0;
					}
				}

				$arr[$row['empmail']][] = $row;

				if ($i != 0)
				{
					// recalculate the sum of the related orders
					$arr[$row['empmail']][0]['total_cost'] += $row['total_cost'];
				}
			}
		}

		return $arr;
	}

	/**
	 * Loads the employee e-mail template that should be parsed.
	 *
	 * @param 	array 	$orders 	The orders list.
	 *
	 * @return 	string 	The e-mail template.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function loadEmployeeEmailTemplate(array $orders)
	{
		ob_start();
		include VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_tmpls' . DIRECTORY_SEPARATOR . VAPFactory::getConfig()->get('empmailtmpl');
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
	
	/**
	 * Method used to parse the e-mail template for the employee(s).
	 *
	 * @param 	string 	$tmpl 			The template string to parse.
	 * @param 	array 	$order_details 	The orders list.
	 *
	 * @return 	string 	The parsed template.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function parseEmployeesEmailTemplate($tmpl, $order_details)
	{
		// parse coupon string

		if (!empty($order_details[0]['coupon_str']))
		{
			list($code, $pt, $value) = explode(';;', $order_details[0]['coupon_str']);
			$coupon_str = $code . " : " . ($pt == 1 ? $value . '%' : self::printPriceCurrencySymb($value));
		}
		else
		{
			$coupon_str = JText::translate('VAPADMINEMAILNOCOUPON');
		}

		// parse payment name

		$payment_name = !empty($order_details[0]['payment_name']) ? $order_details[0]['payment_name'] : JText::translate('VAPADMINEMAILNOPAYMENT');

		// parse order total cost

		$order_total = self::printPriceCurrencySymb($order_details[0]['total_cost'] + $order_details[0]['payment_charge']);

		// fetch appointment details

		/**
		 * @deprecated 1.8 	the appointment details are parsed within the e-mail template
		 */
		$appointment_details = "";
		for ($i = ($order_details[0]['id_service'] == -1 ? 1 : 0); $i < count($order_details); $i++)
		{
			$row = $order_details[$i];
			
			$appointment_details .= '<div class="appointment">';
			$appointment_details .= '<div class="content ' . ($row['total_cost'] > 0 || count($row['options']) ? '' : 'fill-bottom') . '">';
			$appointment_details .= $row['id'] . '-' . $row['sid'] . '<br />';
			$appointment_details .= $row['sname'] . '<br />';
			$appointment_details .= $row['formatted_checkin'] . ' - ' . $row['formatted_duration'];
			$appointment_details .= '</div>';
			
			if (count($row['options']))
			{
				$appointment_details .= '<div class="options-list'.($row['total_cost'] > 0 ? '' : ' fill-bottom').'">';

				foreach ($row['options'] as $opt)
				{
					$appointment_details .= '<div class="option">';
					$appointment_details .= '<div class="name">- ' . $opt['full_name'] . '</div>';
					$appointment_details .= '<div class="quantity">' . $opt['formatted_quantity'] . '</div>';
					if ($opt['price'] != 0)
					{
						$appointment_details .= '<div class="price">' . $opt['formatted_price'] . '</div>';
					}
					$appointment_details .= '</div>';
				}
				$appointment_details .= '</div>';
			}

			if ($row['total_cost'] > 0)
			{
				$appointment_details .= '<div class="cost"><span>' . $row['formatted_total'] . '</span></div>';
			}

			$appointment_details .= '</div>';
		}

		// customer details
		
		$custom_fields = json_decode($order_details[0]['custom_f'], true);

		/**
		 * @deprecated 1.8 	the customer details are parsed within the e-mail template
		 */
		$customer_details = "";
		foreach ($custom_fields as $kc => $vc)
		{
			$customer_details .= '<div class="info">';
			$customer_details .= '<div class="label">'.JText::translate($kc).':</div>';
			$customer_details .= '<div class="value">'.$vc.'</div>';
			$customer_details .= '</div>';
		}

		// joomla user details
		$user_details = '';
		if (strlen($order_details[0]['user_email']) > 0)
		{
			/**
			 * @deprecated 1.8 	the joomla user details should be parsed within the e-mail template (if needed)
			 */

			$user_details = '<div class="separator"> </div>
			<div class="customer-details-wrapper">
				<div class="title">'.JText::translate('VAPUSERDETAILS').'</div>
					<div class="customer-details">
						<div class="info">
							<div class="label">'.JText::translate('VAPREGFULLNAME').':</div>
							<div class="value">'.$order_details[0]['user_name'].'</div>
						</div>
						<div class="info">
							<div class="label">'.JText::translate('VAPREGUNAME').':</div>
							<div class="value">'.$order_details[0]['user_uname'].'</div>
						</div>
						<div class="info">
							<div class="label">'.JText::translate('VAPREGEMAIL').':</div>
							<div class="value">'.$order_details[0]['user_email'].'</div>
						</div>
					</div>
				</div>';
		}

		$vik = VAPApplication::getInstance();

		// order link

		$order_link_href = "index.php?option=com_vikappointments&view=order&ordnum={$order_details[0]['id']}&ordkey={$order_details[0]['sid']}";
		$order_link_href = $vik->routeForExternalUse($order_link_href);

		$confirmation_link = "";
		if ($order_details[0]['status'] == 'PENDING' && count($order_details) == 1)
		{
			$confirmation_link = "index.php?option=com_vikappointments&task=confirmord&oid={$order_details[0]['id']}&conf_key={$order_details[0]['conf_key']}";
			$confirmation_link = $vik->routeForExternalUse($confirmation_link);

			// $confirmation_link .= '<div class="order-link">';
			// $confirmation_link .= '<div class="title">'.JText::translate('VAPCONFIRMATIONLINK').'</div>';
			// $confirmation_link .= '<div class="content">';
			// $confirmation_link .= '<a href="'.$confirmation_link_href.'">'.$confirmation_link_href.'</a>';
			// $confirmation_link .= '</div>';
			// $confirmation_link .= '</div>';
		}

		// logo

		$logo_name = VAPFactory::getConfig()->get('companylogo');
		$agency_name = VAPFactory::getConfig()->get('agencyname');

		$logo_str = "";
		if (!empty($logo_name) && file_exists(VAPMEDIA . DIRECTORY_SEPARATOR . $logo_name))
		{ 
			$logo_str = '<img src="' . VAPMEDIA_URI . $logo_name . '" alt="' . htmlspecialchars($agency_name) . '" />';
		}

		// order status color

		switch ($order_details[0]['status'])
		{
			case 'CONFIRMED':
				$order_status_color = '#006600';
				break;

			case 'PENDING':
				$order_status_color = '#D9A300';
				break;

			case 'REMOVED':
				$order_status_color = '#B20000';
				break;

			case 'CANCELED':
				$order_status_color = '#F01B17';
				break;

			default:
				$order_status_color = 'inherit';
		}

		// replace tags from template

		$tmpl = str_replace('{company_name}'		, $agency_name											, $tmpl);
		$tmpl = str_replace('{order_status_class}'	, strtolower($order_details[0]['status'])				, $tmpl);
		$tmpl = str_replace('{order_status}'		, JText::translate('VAPSTATUS' . $order_details[0]['status'])	, $tmpl);
		$tmpl = str_replace('{order_status_color}'	, $order_status_color									, $tmpl);
		$tmpl = str_replace('{order_payment}'		, $payment_name											, $tmpl);
		$tmpl = str_replace('{order_coupon_code}'	, $coupon_str											, $tmpl);
		$tmpl = str_replace('{order_total_cost}'	, $order_total											, $tmpl);
		$tmpl = str_replace('{order_link}'			, $order_link_href										, $tmpl);
		$tmpl = str_replace('{confirmation_link}'	, $confirmation_link									, $tmpl);
		$tmpl = str_replace('{logo}'				, $logo_str												, $tmpl);

		/**
		 * @deprecated 1.8
		 */
		$tmpl = str_replace('{appointment_details}'	, $appointment_details, $tmpl);
		$tmpl = str_replace('{customer_details}'	, $customer_details, $tmpl);
		$tmpl = str_replace('{user_details}'		, $user_details, $tmpl);

		return $tmpl;
	}

	/////////////////////////////////////////////
	///////////// CUSTOMER E-MAIL ///////////////
	/////////////////////////////////////////////
	
	/**
	 * Loads the e-mail template that should be parsed.
	 *
	 * @param 	array 	$orders 	The orders list.
	 *
	 * @return 	string 	The e-mail template.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function loadEmailTemplate(array $orders)
	{
		ob_start();
		include VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_tmpls' . DIRECTORY_SEPARATOR . VAPFactory::getConfig()->get('mailtmpl');
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
	
	/**
	 * Sends the notification e-mail to the customer.
	 *
	 * @param 	array 	$order_details 	The orders that should be notified.
	 *
	 * @return 	void
	 *
	 * @deprecated 1.9  Use VAPMailFactory instead.
	 */
	public static function sendCustomerEmail($order_details)
	{
		VAPLoader::import('libraries.mail.factory');

		$mail = VAPMailFactory::getInstance('customer', $order_details['id']);

		if ($mail->shouldSend())
		{
			$mail->send();
		}
	}

	/**
	 * Method used to parse the e-mail template for the customers.
	 *
	 * @param 	string 	$tmpl 			The template string to parse.
	 * @param 	array 	$order_details 	The orders list.
	 *
	 * @return 	string 	The parsed template.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function parseEmailTemplate($tmpl, $order_details)
	{
		// parse payment name

		$payment_name = "";
		if (!empty($order_details[0]['payment_name']))
		{
			$payment_name = $order_details[0]['payment_name'];
			// $payment_name = '<div class="box'.($order_details[0]['total_cost'] > 0 ? '' : ' large').'">'.$order_details[0]['payment_name'].'</div>';
		}

		// parse total cost

		$total_cost = "";
		if ($order_details[0]['total_cost'] > 0)
		{
			$total_cost = self::printPriceCurrencySymb($order_details[0]['total_cost'] + $order_details[0]['payment_charge']);
			// $total_cost = '<div class="box'.(!empty($order_details[0]['payment_name']) ? '' : ' large').'">'.$total_cost.'</div>';
		}

		// parse coupon string

		$coupon_str = "";
		if (!empty($order_details[0]['coupon_str']))
		{
			list($code, $pt, $value) = explode(';;', $order_details[0]['coupon_str']);
			$coupon_str = $code . " : " . ($pt == 1 ? $value . '%' : self::printPriceCurrencySymb($value));
			// $coupon_str = '<div class="box large">'.$coupon_str.'</div>';
		}

		// fetch appointment details

		/**
		 * @deprecated 1.8 	the appointment details are parsed within the e-mail template
		 */
		$appointment_details = "";
		for ($i = ($order_details[0]['id_service'] == -1 ? 1 : 0); $i < count($order_details); $i++)
		{
			$row = $order_details[$i];
			
			$appointment_details .= '<div class="appointment">';
			$appointment_details .= '<div class="content ' . ($row['total_cost'] > 0 || count($row['options']) ? '' : 'fill-bottom') . '">';
			$appointment_details .= $row['sname'] . ' - ' . $row['ename'] . '<br />';
			$appointment_details .= $row['formatted_checkin'] . ' - ' . $row['formatted_duration'];
			$appointment_details .= '</div>';
			
			if (count($row['options']))
			{
				$appointment_details .= '<div class="options-list'.($row['total_cost'] > 0 ? '' : ' fill-bottom').'">';

				foreach ($row['options'] as $opt)
				{
					$appointment_details .= '<div class="option">';
					$appointment_details .= '<div class="name">- ' . $opt['full_name'] . '</div>';
					$appointment_details .= '<div class="quantity">' . $opt['formatted_quantity'] . '</div>';
					if ($opt['price'] != 0)
					{
						$appointment_details .= '<div class="price">' . $opt['formatted_price'] . '</div>';
					}
					$appointment_details .= '</div>';
				}
				$appointment_details .= '</div>';
			}

			if ($row['total_cost'] > 0)
			{
				$appointment_details .= '<div class="cost"><span>' . $row['formatted_total'] . '</span></div>';
			}

			$appointment_details .= '</div>';
		}

		// customer details
		
		$custom_fields = json_decode($order_details[0]['custom_f'], true);

		/**
		 * @deprecated 1.8 	the customer details are parsed within the e-mail template
		 */
		$customer_details = "";
		foreach ($custom_fields as $kc => $vc)
		{
			$customer_details .= '<div class="info">';
			$customer_details .= '<div class="label">'.JText::translate($kc).':</div>';
			$customer_details .= '<div class="value">'.$vc.'</div>';
			$customer_details .= '</div>';
		}

		// joomla user details
		$user_details = '';

		if (strlen($order_details[0]['user_email']) > 0)
		{
			/**
			 * @deprecated 1.8 	the joomla user details should be parsed within the e-mail template (if needed)
			 */

			$user_details = '<div class="separator"> </div>
			<div class="customer-details-wrapper">
				<div class="title">'.JText::translate('VAPUSERDETAILS').'</div>
					<div class="customer-details">
						<div class="info">
							<div class="label">'.JText::translate('VAPREGFULLNAME').':</div>
							<div class="value">'.$order_details[0]['user_name'].'</div>
						</div>
						<div class="info">
							<div class="label">'.JText::translate('VAPREGUNAME').':</div>
							<div class="value">'.$order_details[0]['user_uname'].'</div>
						</div>
						<div class="info">
							<div class="label">'.JText::translate('VAPREGEMAIL').':</div>
							<div class="value">'.$order_details[0]['user_email'].'</div>
						</div>
					</div>
				</div>';
		}

		// order link

		$order_link_href = "index.php?option=com_vikappointments&view=order&ordnum={$order_details[0]['id']}&ordkey={$order_details[0]['sid']}";
		$order_link_href = VAPApplication::getInstance()->routeForExternalUse($order_link_href);

		$cancellation_link = "";
		if ($order_details[0]['status'] == 'CONFIRMED' && VAPFactory::getConfig()->getBool('enablecanc'))
		{
			$cancellation_link = $order_link_href . "#cancel";

			// $cancellation_link .= '<div class="order-link">';
			// $cancellation_link .= '<div class="title">'.JText::translate('VAPCANCELLATIONLINK').'</div>';
			// $cancellation_link .= '<div class="content">';
			// $cancellation_link .= '<a href="'.$cancellation_link_href.'">'.$cancellation_link_href.'</a>';
			// $cancellation_link .= '</div>';
			// $cancellation_link .= '</div>';
		}

		// logo

		$logo_name = VAPFactory::getConfig()->get('companylogo');
		$agency_name = VAPFactory::getConfig()->get('agencyname');

		$logo_str = "";
		if (!empty($logo_name) && file_exists(VAPMEDIA . DIRECTORY_SEPARATOR . $logo_name))
		{ 
			$logo_str = '<img src="' . VAPMEDIA_URI . $logo_name . '" alt="' . htmlspecialchars($agency_name) . '" />';
		}

		// order status color

		switch ($order_details[0]['status'])
		{
			case 'CONFIRMED':
				$order_status_color = '#006600';
				break;

			case 'PENDING':
				$order_status_color = '#D9A300';
				break;

			case 'REMOVED':
				$order_status_color = '#B20000';
				break;

			case 'CANCELED':
				$order_status_color = '#F01B17';
				break;

			default:
				$order_status_color = 'inherit';
		}

		// replace tags from template

		$tmpl = str_replace('{company_name}'		, $agency_name											, $tmpl);
		$tmpl = str_replace('{order_number}'		, $order_details[0]['id']								, $tmpl);
		$tmpl = str_replace('{order_key}'			, $order_details[0]['sid']								, $tmpl);
		$tmpl = str_replace('{order_status_class}'	, strtolower($order_details[0]['status'])				, $tmpl);
		$tmpl = str_replace('{order_status}'		, JText::translate('VAPSTATUS' . $order_details[0]['status'])	, $tmpl);
		$tmpl = str_replace('{order_status_color}'	, $order_status_color									, $tmpl);
		$tmpl = str_replace('{order_payment}'		, $payment_name											, $tmpl);
		$tmpl = str_replace('{order_payment_notes}'	, $order_details[0]['payment_note']						, $tmpl);
		$tmpl = str_replace('{order_coupon_code}'	, $coupon_str											, $tmpl);
		$tmpl = str_replace('{order_total_cost}'	, $total_cost 											, $tmpl);
		$tmpl = str_replace('{order_link}'			, $order_link_href										, $tmpl);
		$tmpl = str_replace('{cancellation_link}'	, $cancellation_link 									, $tmpl);
		$tmpl = str_replace('{logo}'				, $logo_str 											, $tmpl);

		/**
		 * @deprecated 1.8
		 */
		$tmpl = str_replace('{appointment_details}'	, $appointment_details 	, $tmpl);
		$tmpl = str_replace('{customer_details}'	, $customer_details 	, $tmpl);
		$tmpl = str_replace('{user_details}'		, $user_details 		, $tmpl);
		
		// apply custom text

		/**
		 * Retrieve the list of the services and employees booked within this order.
		 *
		 * @since 1.6
		 */
		$services_booked  = array();
		$employees_booked = array();

		foreach ($order_details as $order)
		{
			if ($order['id_service'] > 0)
			{
				$services_booked[] = $order['id_service'];
			}

			if ($order['id_employee'] > 0)
			{
				$employees_booked[] = $order['id_employee'];
			}
		}

		$services_booked = array_unique($services_booked);
		//

		/**
		 * Search for an e-mail custom text to manually inject.
		 *
		 * @since 1.6.5
		 */
		if (isset($order_details[0]['mail_custom_text']))
		{
			// take specified IDs
			$cust_ids = $order_details[0]['mail_custom_text'];
		}
		else
		{
			// no custom IDs
			$cust_ids = null;
		}

		/**
		 * Check if we should include/exclude the default custom texts
		 *
		 * @since 1.6.5
		 */
		if (!empty($order_details[0]['exclude_default_mail_texts']))
		{
			// do not use default custom texts
			$default_texts = false;
		}
		else
		{
			// use them too
			$default_texts = true;
		}

		$tmpl = self::parseEmailCustomText($tmpl, $order_details[0]['status'], $order_details[0]['langtag'], $services_booked, $employees_booked, $cust_ids, $default_texts);

		return $tmpl;
	}

	/**
	 * Parses the e-mail custom texts.
	 *
	 * @param 	string 	 $tmpl 		 The e-mail template (HTML).
	 * @param 	string 	 $status 	 The required status.
	 * @param 	string 	 $lang 		 The required language.
	 * @param 	array 	 $services 	 A list of requested services (@since 1.6).
	 * @param 	array 	 $employees  A list of requested employees (@since 1.6).
	 * @param 	mixed 	 $id 		 Either an ID or a list of custom text to take (@since 1.6.5).
	 * @param 	boolean  $default    True to load the default custom fields, false to use only the specified ID (@since 1.6.6).
	 *
	 * @return 	string 	 The parsed HTML template.
	 *
	 * @deprecated 1.8   Use VikAppointmentsModelMailtext::parseTemplate() instead.
	 */
	private static function parseEmailCustomText($tmpl, $status, $lang = null, array $services = array(), array $employees = array(), $id = null, $default = true)
	{
		$model = JModelVAP::getInstance('mailtext');

		$order = new stdClass;
		$order->status  = $status;
		$order->langtag = $lang;
		$order->appointments = array();

		$n = max(array(count($services), count($employees)));

		for ($i = 0; $i < $n; $i++)
		{
			$app = new stdClass;
			
			$app->service = new stdClass;
			$app->service->id = isset($services[$i]) ? $services[$i] : 0;
			
			$app->employee = new stdClass;
			$app->employee->id = isset($employees[$i]) ? $employees[$i] : 0;

			$order->appointments[] = $app;
		}

		$options = array(
			'lang'    => $lang,
			'file'    => VAPFactory::getConfig()->get('mailtmpl'),
			'id'      => $id,
			'default' => $default,
		);

		return $model->parseTemplate($tmpl, $order, $options);
	}
	
	/**
	 * Includes the files uploaded by the customers as attachment.
	 * See the custom fields of type "file".
	 *
	 * @param 	mixed 	$order 	The order object.
	 *
	 * @return 	array 	The attachments array.
	 */
	public static function includeMailAttachments($order)
	{
		if (is_array($order))
		{
			/**
			 * Extract uploads from old array version for BC.
			 *
			 * @deprecated 1.8
			 */
			$uploads = $order_details[0]['uploads'];
		}
		else
		{
			$uploads = $order->uploads;
		}

		$attachments = array();

		foreach ($uploads as $files)
		{
			if (!is_array($files))
			{
				// always treat as a list of files
				$files = array($files);
			}

			foreach ($files as $filename)
			{
				// extract readable name from path
				$pretty = preg_replace("/^[a-f0-9]+_/", '', $filename);

				$original = VAPCUSTOMERS_UPLOADS . DIRECTORY_SEPARATOR . $filename;
				$rename   = VAPCUSTOMERS_UPLOADS . DIRECTORY_SEPARATOR . $pretty;

				// copy original file to have a more readable file name
				if (is_file($original) && copy($original, $rename))
				{
					$attachments[] = $rename;
				}
			}
		}
		
		return $attachments;
	}
	
	/**
	 * Destroys the attachments that have been sent to the administrator.
	 * See the custom fields of type "file".
	 *
	 * @param 	array 	$attachments 	The list of attachments to remove.
	 *
	 * @return 	void
	 *
	 * @deprecated 1.9  Without replacement.
	 */
	public static function destroyMailAttachments(array $attachments)
	{
		foreach ($attachments as $file)
		{
			unlink($file);
		}
	}
	
	/**
	 * Creates the ICS file to attach within the e-mail.
	 *
	 * @param 	integer  $id_order	The order to export.
	 * @param 	boolean  $is_admin 	True if the e-mail is sent to an administrator.
	 * @param 	integer  $id_emp 	The ID of the employee.
	 *
	 * @return 	mixed 	 The path of the ICS file on success, otherwise null.
	 *
	 * @uses 	composeExportableFile()
	 */
	public static function composeFileICS($id_order, $is_admin = false, $id_emp = 0)
	{
		return self::composeExportableFile('ics', $id_order, $is_admin, $id_emp);
	}
	
	/**
	 * Creates the CSV file to attach within the e-mail.
	 *
	 * @param 	integer  $id_order	The order to export.
	 * @param 	boolean  $is_admin 	True if the e-mail is sent to an administrator.
	 * @param 	integer  $id_emp 	The ID of the employee.
	 *
	 * @return 	mixed 	 The path of the CSV file on success, otherwise null.
	 *
	 * @uses 	composeExportableFile()
	 */
	public static function composeFileCSV($id_order, $is_admin = false, $id_emp = 0)
	{
		return self::composeExportableFile('csv', $id_order, $is_admin, $id_emp);
	}

	/**
	 * Creates the exportable file to attach within the e-mail.
	 *
	 * @param 	integer  $id_order	The order to export.
	 * @param 	boolean  $is_admin 	True if the e-mail is sent to an administrator.
	 * @param 	integer  $id_emp 	The ID of the employee.
	 *
	 * @return 	mixed 	 The path of the exported file on success, otherwise null.
	 */
	protected static function composeExportableFile($class, $id_order, $is_admin = false, $id_emp = 0)
	{
		// create destination path
		$path = VAPMAIL_ATTACHMENTS . DIRECTORY_SEPARATOR . JHtml::fetch('date', 'now', 'Y-m-d H_i_s');
		$tmp  = $path;

		$cont = 1;

		while (is_file($tmp . '.' . $class))
		{
			$tmp = $path . '-' . $cont;
			$cont++;
		}

		$path = $tmp . '.' . $class;

		// prepare driver options
		$options = array(
			'cid'         => array((int) $id_order),
			'id_employee' => (int) $id_emp,
			'admin'       => $is_admin,
		);
		
		// load driver instance
		VAPLoader::import('libraries.order.export.factory');
		$driver = VAPOrderExportFactory::getInstance($class, 'appointment', $options);

		// load previously saved driver parameters
		$params = $driver->getParams();

		foreach ($params as $k => $v)
		{
			// inject them as driver options
			$driver->setOption($k, $v);
		}

		// export data and write into a file
		jimport('joomla.filesystem.file');
		if (JFile::write($path, $driver->export()) !== false)
		{
			return $path;
		}

		return null;
	}

	/////////////////////////////////////////////
	/////////// CANCELLATION E-MAIL /////////////
	/////////////////////////////////////////////

	/**
	 * Sends the notification e-mail to the administrator(s)
	 * and related employee(s).
	 *
	 * @param 	array 	$order_details 	The orders that should be notified.
	 *
	 * @return 	void
	 */
	public static function sendCancellationAdminEmail($order_details)
	{
		if (!$order_details)
		{
			return;
		}
		
		self::loadLanguage(self::getDefaultLanguage('site'));
		
		$subject = JText::translate('VAPORDERCANCELEDSUBJECT');

		/**
		 * Parse e-mail subject to replace tags with the
		 * related order details.
		 *
		 * @since 1.6.6
		 */
		static::parseEmailSubject($subject, $order_details);
		
		$admin_mail_list 	= self::getAdminMailList();
		$sendermail 		= self::getSenderMail();
		$adminname 			= VAPFactory::getConfig()->get('agencyname');
		
		$canc_tmpl 	 = self::loadCancellationEmailTemplate($order_details, 1);
		$html_mess 	 = self::parseCancellationEmailTemplate($canc_tmpl, $order_details, 1);
		$emp_details = self::filterOrdersByEmployee($order_details);
			
		$vik = VAPApplication::getInstance();

		foreach ($admin_mail_list as $_m)
		{
			$vik->sendMail($sendermail, $adminname, $_m, $_m, $subject, $html_mess, array(), true);
		}
		
		foreach ($emp_details as $emp_mail => $emp_order_details)
		{
			/**
			 * Reload cancellation e-mail template using the orders related to the specified employee.
			 *
			 * @since 1.6
			 */
			$canc_tmpl 	= self::loadCancellationEmailTemplate($emp_order_details, 2);
			$_html 		= self::parseCancellationEmailTemplate($canc_tmpl, $emp_order_details, 2);
			
			$vik->sendMail($sendermail, $adminname, $emp_mail, $admin_mail_list[0], $subject, $_html, array(), true);
		}
	}

	/**
	 * Loads the cancellation e-mail template that should be parsed.
	 *
	 * @param 	array 	 $orders 	The orders list.
	 * @param 	integer  $type 		The entity type to render the template (1 for administrator, 2 for employee).
	 *
	 * @return 	string 	 The e-mail template.
	 *
	 * @deprecated 1.8   Without replacement.
	 */
	public static function loadCancellationEmailTemplate(array $orders, $type)
	{
		ob_start();
		include VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_tmpls' . DIRECTORY_SEPARATOR . VAPFactory::getConfig()->get('cancmailtmpl');
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
	
	/**
	 * Method used to parse the e-mail template for the administrator(s).
	 *
	 * @param 	string 	 $tmpl 			 The template string to parse.
	 * @param 	array 	 $order_details  The orders list.
	 * @param 	integer  $type 			 The entity type to render the template (1 for administrator, 2 for employee).
	 *
	 * @return 	string 	 The parsed template.
	 *
	 * @deprecated 1.8   Without replacement.
	 */
	public static function parseCancellationEmailTemplate($tmpl, $order_details, $type)
	{
		$vik = VAPApplication::getInstance();

		// retrieve cancellation content

		if ($type == 1)
		{
			$cancellation_content = JText::translate('VAPORDERCANCELEDCONTENT');
		}
		else
		{
			$cancellation_content = JText::translate('VAPORDERCANCELEDCONTENTEMP');
		}

		// fetch appointment details

		/**
		 * @deprecated 1.8 	the appointment details are parsed within the e-mail template
		 */
		$appointment_details = "";
		for ($i = ($order_details[0]['id_service'] == -1 ? 1 : 0); $i < count($order_details); $i++)
		{
			$row = $order_details[$i];

			if ($type == 1)
			{
				/**
				 * Route administrator URL depending on the current platform.
				 *
				 * @since 1.6.3
				 */
				$url = $vik->adminUrl('index.php?option=com_vikappointments&task=editreservation&cid[]=' . $row['id']);
			}
			else
			{
				$url = 'index.php?option=com_vikappointments&view=empmanres&cid[]=' . $row['id'];
				$url = $vik->routeForExternalUse($url);
			}
			
			$appointment_details .= '<div class="appointment">';

			$appointment_details .= '<div class="content">';
			$appointment_details .= '<div class="left">' . $row['id'] . ' - ' . $row['sid'] . '</div>';
			$appointment_details .= '<div class="right">' . JText::translate('VAPSTATUSCANCELED') . '</div>';
			$appointment_details .= '</div>';

			$appointment_details .= '<div class="subcontent">';
			$appointment_details .= $row['sname'] . ($type == 1 ? ' - '.$row['ename'] : '') . '<br />';
			$appointment_details .= $row['formatted_checkin'] . ' - ' . $row['formatted_duration'];
			$appointment_details .= '</div>';

			$appointment_details .= '<div class="link"><a href="' . $url . '">' . $url . '</a></div>';

			$appointment_details .= '</div>';
		}

		// customer details
		
		$custom_fields = json_decode($order_details[0]['custom_f'], true);

		/**
		 * @deprecated 1.8 	the customer details are parsed within the e-mail template
		 */
		$customer_details = "";
		foreach ($custom_fields as $kc => $vc)
		{
			$customer_details .= '<div class="info">';
			$customer_details .= '<div class="label">'.JText::translate($kc).':</div>';
			$customer_details .= '<div class="value">'.$vc.'</div>';
			$customer_details .= '</div>';
		}

		// order link

		if ($type == 1)
		{
			/**
			 * Route administrator URL depending on the current platform.
			 *
			 * @since 1.6.3
			 */
			$order_link = $vik->adminUrl('index.php?option=com_vikappointments&view=reservations&res_id=' . $order_details[0]['id']);
		}
		else
		{
			$order_link = '';
		}

		// logo

		$logo_name = VAPFactory::getConfig()->get('companylogo');
		$agency_name = VAPFactory::getConfig()->get('agencyname');

		$logo_str = "";
		if (!empty($logo_name) && file_exists(VAPMEDIA . DIRECTORY_SEPARATOR . $logo_name))
		{
			$logo_str = '<img src="' . VAPMEDIA_URI . $logo_name . '" alt="' . htmlspecialchars($agency_name) . '" />';
		}

		// replace tags from template

		$tmpl = str_replace('{company_name}'			, $agency_name			, $tmpl);
		$tmpl = str_replace('{cancellation_content}'	, $cancellation_content	, $tmpl);
		$tmpl = str_replace('{logo}'					, $logo_str				, $tmpl);
		$tmpl = str_replace('{order_link}'				, $order_link 			, $tmpl);

		/**
		 * @deprecated 1.8
		 */
		$tmpl = str_replace('{appointment_details}'		, $appointment_details	, $tmpl);
		$tmpl = str_replace('{customer_details}'		, $customer_details		, $tmpl);

		return $tmpl;
	}

	/**
	 * Parses e-mail subject to replace tags with the
	 * related order details.
	 *
	 * @param 	string 	&$subject  The subject to parse.
	 * @param 	mixed 	$order     The appointments details.
	 *
	 * @return 	void
	 *
	 * @since 	1.6.6
	 */
	public static function parseEmailSubject(&$subject, $order)
	{
		$config = VAPFactory::getConfig();

		$lookup = array();

		// look for multi-appointments
		if (count($order->appointments) != 1)
		{
			// display number of booked appointments
			$lookup['service'] = JText::sprintf('VAPPACKAGESNUMAPP', count($order->appointments));
			// do not use employees
			$lookup['employee'] = '/';
			// use creation date
			$lookup['checkin_date'] = JHtml::fetch('date', $order->createdon, $config->get('dateformat'));
			// use creation time
			$lookup['checkin_time'] = JHtml::fetch('date', $order->createdon, $config->get('timeformat'));
		}
		else
		{
			// replicate service name
			$lookup['service']  = $order->appointments[0]->service->name;
			// replicate employee name
			$lookup['employee'] = $order->appointments[0]->employee->name;
			// use creation date
			$lookup['checkin_date'] = JHtml::fetch('date', $order->appointments[0]->checkin->utc, $config->get('dateformat'));
			// use creation time
			$lookup['checkin_time'] = JHtml::fetch('date', $order->appointments[0]->checkin->utc, $config->get('timeformat'));
		}

		// include oid and sid
		$lookup['ordnum'] = $order->id;
		$lookup['ordkey'] = $order->sid;
		// specify date time too
		$lookup['checkin_datetime'] = $lookup['checkin_date'] . ' ' . $lookup['checkin_time'];
		// format total cost
		$lookup['total_cost'] = VAPFactory::getCurrency()->format($order->totals->gross);
		// translate status
		$lookup['status'] = JHtml::fetch('vaphtml.status.display', $order->status, 'plain');
		// replicate customer name
		$lookup['customer'] = $order->purchaser_nominative;

		// look for any placeholders
		$subject = preg_replace_callback("/{([a-zA-Z0-9\_]+)}/i", function($match) use ($lookup)
		{
			// obtain tag
			$tag = end($match);

			if (isset($lookup[$tag]))
			{
				// return related value
				return $lookup[$tag];
			}

			// unsupported tag, leave as is
			return $match[0];
		}, $subject);
	}

	/////////////////////////////////////////////
	/////////////// WAITING LIST ////////////////
	/////////////////////////////////////////////

	/**
	 * Checks all the customers subscribed to the waiting list that should
	 * be notified after a cancellation of a confirmed appointment.
	 *
	 * @param 	array 	$order 	The details of the order that is no more confirmed.
	 *
	 * @return  void
	 *
	 * @deprecated 1.8  Use VikAppointmentsModelWaitinglist::notify() instead.
	 */
	public static function notifyCustomersInWaitingList($order)
	{
		$model = JModelVAP::getInstance('waitinglist');

		if (is_array($order))
		{
			$order = $order[0]['id'];
		}
		else
		{
			$order = $order->id;
		}

		return $model->notify($order);
	}

	/**
	 * Loads the e-mail template that will be used to notify
	 * the waiting list subscriptions.
	 *
	 * @return 	string 	The HTML contents of the template.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function loadWaitListEmailTemplate()
	{	
		ob_start();
		include VAPBASE . DIRECTORY_SEPARATOR . "helpers" . DIRECTORY_SEPARATOR . "mail_tmpls" . DIRECTORY_SEPARATOR . VAPFactory::getConfig()->get('waitlistmailtmpl');
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * Removes from the waiting list the subscription that has been notified.
	 * Usually, when a customer receives the notification, it proceeds with the
	 * purchase of the appointment. At the end of this process, its subscription
	 * is automatically removed by using this method.
	 *
	 * In addition, removes all the waiting list subscriptions 
	 * that are older than the current day.
	 *
	 * @param 	object 	$order  The order details.
	 *
	 * @return 	void
	 */
	public static function flushWaitingList($order)
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->delete($dbo->qn('#__vikappointments_waitinglist'))
			->where($dbo->qn('timestamp') . ' < ' . $dbo->q(JFactory::getDate()->toSql()));

		$dbo->setQuery($q);
		$dbo->execute();

		$waitModel = JModelVAP::getInstance('waitinglist');

		foreach ($order->appointments as $app)
		{
			// the appointment was registered, unsubscribe the customer from
			// the related waiting list
			$waitModel->unsubscribe(array(
				'jid'          => $order->createdby,
				'email'        => $order->purchaser_mail,
				'phone_number' => $order->purchaser_phone,
				'timestamp'    => $app->checkin->utc,
				'id_service'   => $app->service->id,
			)); 		
		}
	}

	/////////////////////////////////////////////
	///////////// PACKAGES E-MAIL ///////////////
	/////////////////////////////////////////////

	/**
	 * Returns the packages order details of the given ID.
	 *
	 * @param 	integer  $order_id 	 The order number (ID).
	 * @param 	string 	 $order_key  The order key (sid). If not provided, 
	 * 								 this field won't used while fetching the records.
	 * @param 	string 	 $langtag 	 The translation language. If not provided
	 * 								 it will be used the default one.
	 * 
	 * @return 	mixed 	 A list of orders on success, otherwise false.
	 *
	 * @deprecated 1.8   Use VAPOrderFactory::getPackages() instead.
	 */
	public static function fetchPackagesOrderDetails($order_id, $order_key = null, $langtag = null)
	{
		try
		{
			VAPLoader::import('libraries.order.factory');
			return VAPOrderFactory::getPackages($order_id, $langtag, array('sid' => $order_key));
		}
		catch (Exception $e)
		{

		}

		return false;
	}

	// PACKAGES E-MAIL

	/**
	 * Loads the packages e-mail template that should be parsed.
	 *
	 * @param 	array 	$order 	The order details.
	 *
	 * @return 	string 	The e-mail template.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function loadPackagesEmailTemplate(array $order)
	{
		ob_start();
		include VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_tmpls' . DIRECTORY_SEPARATOR . VAPFactory::getConfig()->get('packmailtmpl');
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	/**
	 * Sends the notification e-mail to the customer for the packages.
	 *
	 * @param 	array 	$order_details 	The orders that should be notified.
	 *
	 * @return 	void
	 *
	 * @deprecated 1.9  Use VAPMailFactory instead.
	 */
	public static function sendPackagesCustomerEmail($order_details)
	{	
		VAPLoader::import('libraries.mail.factory');

		$mail = VAPMailFactory::getInstance('package', $order_details['id']);

		if ($mail->shouldSend())
		{
			$mail->send();
		}
	}

	/**
	 * Sends the notification e-mail to the administrator(s) for the packages.
	 *
	 * @param 	array 	$order_details 	The orders that should be notified.
	 *
	 * @return 	void
	 *
	 * @deprecated 1.9  Use VAPMailFactory instead.
	 */
	public static function sendPackagesAdminEmail($order_details)
	{	
		VAPLoader::import('libraries.mail.factory');

		$mail = VAPMailFactory::getInstance('packadmin', $order_details['id']);

		if ($mail->shouldSend())
		{
			$mail->send();
		}
	}

	/**
	 * Method used to parse the e-mail template for packages (admin and customers).
	 *
	 * @param 	string 	$tmpl 			The template string to parse.
	 * @param 	array 	$order_details 	The orders list.
	 *
	 * @return 	string 	The parsed template.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function parsePackagesEmailTemplate($tmpl, $order_details)
	{
		// parse payment name

		$payment_name = "";
		if (!empty($order_details['payment_name']))
		{
			$payment_name = $order_details['payment_name'];
			// $payment_name = '<div class="box'.($order_details['total_cost'] > 0 ? '' : ' large').'">'.$order_details['payment_name'].'</div>';
		}

		// parse total cost

		$total_cost = "";
		if ($order_details['total_cost'] > 0)
		{
			$total_cost = self::printPriceCurrencySymb($order_details['total_cost']);
			// $total_cost = '<div class="box'.(!empty($order_details['payment_name']) ? '' : ' large').'">'.$total_cost.'</div>';
		}

		// fetch package details

		/**
		 * @deprecated 1.8 	the package details are parsed within the e-mail template
		 */
		$packages_details = "";
		foreach ($order_details['items'] as $p)
		{
			$packages_details .= '<div class="package">';
			$packages_details .= '<div class="content '.($p['price'] > 0 ? '' : 'fill-bottom').'">';
			$packages_details .= '<span class="name">'.$p['name'].'</span>';
			$packages_details .= '<span class="numapp">'.JText::sprintf('VAPPACKAGESMAILAPP', $p['num_app']).'</span>';
			$packages_details .= '<span class="quantity">x'.$p['quantity'].'</span>';
			$packages_details .= '</div>';
			
			if ($p['price'] > 0)
			{
				$packages_details .= '<div class="cost"><span>'.self::printPriceCurrencySymb($p['price']*$p['quantity']).'</span></div>';
			}

			$packages_details .= '</div>';
		}

		// customer details
		
		$custom_fields = json_decode($order_details['custom_f'], true);

		/**
		 * @deprecated 1.8 	the customer details are parsed within the e-mail template
		 */
		$customer_details = "";
		foreach ($custom_fields as $kc => $vc)
		{
			$customer_details .= '<div class="info">';
			$customer_details .= '<div class="label">'.JText::translate($kc).':</div>';
			$customer_details .= '<div class="value">'.$vc.'</div>';
			$customer_details .= '</div>';
		}

		// joomla user details

		$user_details = '';
		if (strlen($order_details['user_email']))
		{
			/**
			 * @deprecated 1.8 	the joomla user details should be parsed within the e-mail template (if needed)
			 */

			$user_details = '<div class="separator"> </div>
			<div class="customer-details-wrapper">
				<div class="title">'.JText::translate('VAPUSERDETAILS').'</div>
					<div class="customer-details">
						<div class="info">
							<div class="label">'.JText::translate('VAPREGFULLNAME').':</div>
							<div class="value">'.$order_details['user_name'].'</div>
						</div>
						<div class="info">
							<div class="label">'.JText::translate('VAPREGUNAME').':</div>
							<div class="value">'.$order_details['user_uname'].'</div>
						</div>
						<div class="info">
							<div class="label">'.JText::translate('VAPREGEMAIL').':</div>
							<div class="value">'.$order_details['user_email'].'</div>
						</div>
					</div>
				</div>';
		}

		// order link

		$order_link_href = "index.php?option=com_vikappointments&view=packagesorder&ordnum={$order_details['id']}&ordkey={$order_details['sid']}";
		$order_link_href = VAPApplication::getInstance()->routeForExternalUse($order_link_href);

		// logo

		$logo_name = VAPFactory::getConfig()->get('companylogo');
		$agency_name = VAPFactory::getConfig()->get('agencyname');

		$logo_str = "";
		if (!empty($logo_name) && file_exists(VAPMEDIA . DIRECTORY_SEPARATOR . $logo_name))
		{
			$logo_str = '<img src="' . VAPMEDIA_URI . $logo_name . '" alt="' . htmlspecialchars($agency_name) . '" />';
		}

		// order status color

		switch ($order_details['status'])
		{
			case 'CONFIRMED':
				$order_status_color = '#006600';
				break;

			case 'PENDING':
				$order_status_color = '#D9A300';
				break;

			case 'REMOVED':
				$order_status_color = '#B20000';
				break;

			case 'CANCELED':
				$order_status_color = '#F01B17';
				break;

			default:
				$order_status_color = 'inherit';
		}

		// replace tags from template

		$tmpl = str_replace('{company_name}'		, $agency_name										, $tmpl);
		$tmpl = str_replace('{order_number}'		, $order_details['id']								, $tmpl);
		$tmpl = str_replace('{order_key}'			, $order_details['sid']								, $tmpl);
		$tmpl = str_replace('{order_status_class}'	, strtolower($order_details['status'])				, $tmpl);
		$tmpl = str_replace('{order_status}'		, JText::translate('VAPSTATUS' . $order_details['status'])	, $tmpl);
		$tmpl = str_replace('{order_status_color}'	, $order_status_color								, $tmpl);
		$tmpl = str_replace('{order_payment}'		, $payment_name										, $tmpl);
		$tmpl = str_replace('{order_payment_notes}'	, $order_details['payment_note']					, $tmpl);
		$tmpl = str_replace('{order_total_cost}'	, $total_cost										, $tmpl);
		$tmpl = str_replace('{order_link}'			, $order_link_href									, $tmpl);
		$tmpl = str_replace('{logo}'				, $logo_str											, $tmpl);

		/**
		 * @deprecated 1.8
		 */
		$tmpl = str_replace('{packages_details}', $packages_details	, $tmpl);
		$tmpl = str_replace('{customer_details}', $customer_details	, $tmpl);
		$tmpl = str_replace('{user_details}'	, $user_details		, $tmpl);

		return $tmpl;
	}

	/**
	 * Sends a notification about the purchased order to the specified e-mail.
	 * If allowed, a notification will be sent also to the employees and the administrators.
	 *
	 * @param 	mixed 	 $order  Either an order ID or an object.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 *
	 * @since 	1.7
	 */
	public static function sendMailAction($order)
	{
		if (is_numeric($order))
		{
			VAPLoader::import('libraries.order.factory');
			$order = VAPOrderFactory::getAppointments($order);
		}

		// get appointment model
		$model = JModelVAP::getInstance('reservation');

		$mailOptions = array();
		// validate e-mail rules before sending
		$mailOptions['check'] = true;

		// send e-mail notification to the customer
		$model->sendEmailNotification($order->id, $mailOptions);

		// send e-mail notification to the administrator(s)
		$mailOptions['client'] = $order->statusRole === 'CANCELLED' ? 'cancellation' : 'admin';
		$model->sendEmailNotification($order->id, $mailOptions);

		// send e-mail notification to all the booked employees
		$mailOptions['client'] = 'employee';

		$employees = array();

		// iterate all appointments to look for the employees to notify
		foreach ($order->appointments as $appointment)
		{
			// make sure the same employees hasn't been yet notified
			if (!in_array($appointment->employee->id, $employees))
			{
				$employees[] = $appointment->employee->id;

				$mailOptions['id_employee'] = (int) $appointment->employee->id;
				$model->sendEmailNotification($order->id, $mailOptions);
			}
		}
	}

	/////////////////////////////////////////////
	//////////////////// SMS ////////////////////
	/////////////////////////////////////////////
	
	/**
	 * Sends a notification about the purchased order to the specified number.
	 * If allowed, a notification will be sent also to the employees and the administrators.
	 *
	 * Removed the first parameter $phone_number @since 1.7 version, because it is 
	 * automatically retrieved from the details of the given order.
	 *
	 * @param 	mixed 	 $order  Either an order ID or an object.
	 *
	 * @return 	boolean  True on success, false otherwise.
	 *
	 * @uses 	sendAdminMailSmsFailed()
	 */
	public static function sendSmsAction($order)
	{
		$config = VAPFactory::getConfig();

		if (!$config->getBool('smsenabled'))
		{
			// automatic SMS disabled
			return false;
		}

		try
		{
			// get current SMS instance
			$smsapi = VAPApplication::getInstance()->getSmsInstance();
		}
		catch (Exception $e)
		{
			// SMS API not configured
			return false;
		}

		/**
		 * Check whether we received the phone number as first argument.
		 * In that case, we need to extract the order details array from
		 * the method arguments
		 *
		 * @deprecated 1.8
		 */
		if (is_string($order) && func_num_args() > 1)
		{
			// phone number given, take the second argument for BC
			$order = func_get_arg(1);
			// extract order ID from array 
			$order = $order[0]['id'];
		}

		if (is_numeric($order))
		{
			try
			{
				// load appointment details
				VAPLoader::import('libraries.order.factory');
				$order = VAPOrderFactory::getAppointments($order);
			}
			catch (Exception $e)
			{
				// order not found
				return false;
			}
		}

		$dispatcher = VAPFactory::getEventDispatcher();

		$errors = array();

		// check whether the customer should receive the SMS
		$should_send = VikAppointments::getSmsApiToCustomer();

		$text = '';

		/**
		 * Choose at runtime whether the customer should receive SMS notifications.
		 *
		 * @param 	object   $order  The order details.
		 * @param 	string   &$text  Fill to override the default SMS text (@since 1.7.3).
		 *
		 * @return 	boolean  Return true to send the notification. Return false to deny
		 *                   the notification. Return null to rely on the default setting.
		 *
		 * @since 	1.7
		 */
		$result = $dispatcher->falseOrTrue('onBeforeSendCustomerSmsNotification', array($order, &$text));

		if (!is_null($result))
		{
			// use the condition fetched by the plugin
			$should_send = $result;
		}
		else
		{
			// no attached plugin, make sure the status role of the order is APPROVED
			$should_send = $should_send && $order->statusRole === 'APPROVED';
		}

		// try to send SMS to the customer
		if ($order->purchaser_phone && $should_send)
		{
			// check whether the plugin built a custom message
			if (empty($text) || !is_string($text))
			{
				// fetch sms message
				$text = VikAppointments::getSmsCustomerTextMessage($order);
			}

			// send message
			$response = $smsapi->sendMessage($order->purchaser_phone, $text);

			// validate response
			if (!$smsapi->validateResponse($response))
			{
				// unable to send the notification, register error
				$errors[] = $smsapi->getLog();
			}
		}

		// try to send SMS to the administrator
		$admin_phone = $config->get('smsapiadminphone');

		// check whether the administrator should receive the SMS
		$should_send = VikAppointments::getSmsApiToAdmin();

		$text = '';

		/**
		 * Choose at runtime whether the administrator should receive SMS notifications.
		 *
		 * @param 	object   $order  The order details.
		 * @param 	string   &$text  Fill to override the default SMS text (@since 1.7.3).
		 *
		 * @return 	boolean  Return true to send the notification. Return false to deny
		 *                   the notification. Return null to rely on the default setting.
		 *
		 * @since 	1.7
		 */
		$result = $dispatcher->falseOrTrue('onBeforeSendAdminSmsNotification', array($order, &$text));

		if (!is_null($result))
		{
			// use the condition fetched by the plugin
			$should_send = $result;
		}
		else
		{
			// no attached plugin, make sure the status role of the order is APPROVED
			$should_send = $should_send && $order->statusRole === 'APPROVED';
		}

		if ($admin_phone && $should_send)
		{
			// check whether the plugin built a custom message
			if (empty($text) || !is_string($text))
			{
				// fetch sms message (reload contents into the correct language)
				$text = VikAppointments::getSmsAdminTextMessage($order->id);
			}

			// send message
			$response = $smsapi->sendMessage($admin_phone, $text);

			// validate response
			if (!$smsapi->validateResponse($response))
			{
				// unable to send the notification, register error
				$errors[] = $smsapi->getLog();
			}
		}

		$emp_lookup = array();

		// group appointments by employee
		foreach ($order->appointments as $app)
		{
			if (!isset($emp_lookup[$app->employee->phone]))
			{
				$emp_lookup[$app->employee->phone] = array();
			}

			// register appointment ID
			$emp_lookup[$app->employee->phone][] = $app->id;
		}

		// iterate all the employees to notify
		foreach ($emp_lookup as $phone => $ids)
		{
			// check whether the employee should receive the SMS
			$should_send = VikAppointments::getSmsApiToEmployee();

			$text = '';

			/**
			 * Choose at runtime whether the employee should receive SMS notifications.
			 *
			 * @param 	string   $phone  The phone number of the employee to notify.
			 * @param 	object   $order  The order details.
			 * @param 	string   &$text  Fill to override the default SMS text (@since 1.7.3).
			 *
			 * @return 	boolean  Return true to send the notification. Return false to deny
			 *                   the notification. Return null to rely on the default setting.
			 *
			 * @since 	1.7
			 */
			$result = $dispatcher->falseOrTrue('onBeforeSendEmployeeSmsNotification', array($phone, $order, &$text));

			if (!is_null($result))
			{
				// use the condition fetched by the plugin
				$should_send = $result;
			}
			else
			{
				// no attached plugin, make sure the status role of the order is APPROVED
				$should_send = $should_send && $order->statusRole === 'APPROVED';
			}

			if (!$should_send)
			{
				// go to the next employee
				continue;
			}

			// check whether the plugin built a custom message
			if (empty($text) || !is_string($text))
			{
				if (count($ids) == 1)
				{
					// fetch sms message for the found order
					$text = VikAppointments::getSmsAdminTextMessage($ids[0]);
				}
				else
				{
					// the employee received more than an order, use generic message
					$text = VikAppointments::getSmsAdminTextMessage($order->id);	
				}
			}

			// send message
			$response = $smsapi->sendMessage($phone, $text);

			// validate response
			if (!$smsapi->validateResponse($response))
			{
				// unable to send the notification, register error
				$errors[] = $smsapi->getLog();
			}
		}

		if ($errors)
		{
			// inform the administrator about all the fetched errors
			self::sendAdminMailSmsFailed($errors);
			return false;
		}

		return true;
	}
	
	/**
	 * Returns the SMS message that should be sent to the customers.
	 *
	 * @param 	mixed 	$order  Either an order ID or an object.
	 *
	 * @return 	string 	The SMS message to send to the customers.
	 *
	 * @uses 	parseContentSMS()
	 */
	public static function getSmsCustomerTextMessage($order)
	{
		// store current language tag
		$curr_lang = JFactory::getLanguage()->getTag();

		if (is_numeric($order))
		{
			VAPLoader::import('libraries.order.factory');

			// load order details without caring of the exceptions
			// that this method might throw
			$order = VAPOrderFactory::getAppointments($order);
		}

		if (empty($order->langtag))
		{
			// use default site language tag
			$order->langtag = static::getDefaultLanguage();
		}

		// load front-end language
		static::loadLanguage($order->langtag, JPATH_SITE);

		if (count($order->appointments) == 1)
		{
			// load content for single appointment
			$setting = 'smstmplcust';
		}
		else
		{
			// load content for multiple appointments (or none)
			$setting = 'smstmplcustmulti';
		}
		
		// get JSON array from configuration
		$sms_map = VAPFactory::getConfig()->getArray($setting);
		
		// make sure the SMS lookup specifies a template to
		// be used for the given language
		if (!empty($sms_map[$order->langtag]))
		{
			// use template
			$sms = $sms_map[$order->langtag];
		}
		else
		{
			// fallback to default template
			if (count($order->appointments) == 1)
			{
				// single-appointment template
				$sms = JText::translate('VAPSMSMESSAGECUSTOMER');
			}
			else
			{
				// multi-appointments template
				$sms = JText::translate('VAPSMSMESSAGECUSTOMERMULTI');
			}
		}
		
		// parse SMS template
		$sms = static::parseContentSMS($order, $sms);

		// restore previous language according to the current cllient
		static::loadLanguage($curr_lang);

		return $sms;
	}
	
	/**
	 * Returns the SMS message that should be sent to the administrator.
	 *
	 * @param 	mixed 	$order  Either an order ID or an object.
	 *
	 * @return 	string 	The SMS message to send to the administrator.
	 *
	 * @uses 	parseContentSMS()
	 */
	public static function getSmsAdminTextMessage($order)
	{
		// store current language tag
		$curr_lang = JFactory::getLanguage()->getTag();
		// get default site language tag
		$def_lang = static::getDefaultLanguage();

		// load front-end language
		static::loadLanguage($def_lang, JPATH_SITE);

		if (is_numeric($order))
		{
			VAPLoader::import('libraries.order.factory');

			// load order details without caring of the exceptions
			// that this method might throw
			$order = VAPOrderFactory::getAppointments($order, $def_lang);
		}

		if (count($order->appointments) == 1)
		{
			// load content for single appointment
			$setting = 'smstmpladmin';
		}
		else
		{
			// load content for multiple appointments (or none)
			$setting = 'smstmpladminmulti';
		}

		// get template from configuration
		$sms = VAPFactory::getConfig()->getString($setting);
		
		// make sure the template exists
		if (!trim($sms))
		{
			// fallback to default template
			if (count($order->appointments) == 1)
			{
				// single-appointment template
				$sms = JText::translate('VAPSMSMESSAGEADMIN');
			}
			else
			{
				// multi-appointments template
				$sms = JText::translate('VAPSMSMESSAGEADMINMULTI');
			}
		}
		
		// parse SMS template
		$sms = self::parseContentSMS($order, $sms);

		// restore previous language according to the current cllient
		static::loadLanguage($curr_lang);

		return $sms;
	}
	
	/**
	 * Parses the SMS template to inject the details of the given order.
	 *
	 * @param 	object 	$order  The object containing the order details.
	 * @param 	string 	$sms    The SMS template.
	 *
	 * @return 	string 	The SMS message to send.
	 */
	private static function parseContentSMS($order, $sms)
	{
		$config   = VAPFactory::getConfig();
		$currency = VAPFactory::getCurrency();

		// order placeholders
		$data = array(
			'total_cost' => $currency->format($order->totals->gross),
			'company'    => $config->get('agencyname'),
			'customer'   => $order->purchaser_nominative ? $order->purchaser_nominative : JText::translate('VAPMANAGERESERVATION29'),
			'created_on' => JHtml::fetch('date', $order->createdon, JText::translate('DATE_FORMAT_LC2'), $order->customerTimezone),
		);

		// appointment placeholders
		if (count($order->appointments) == 1)
		{
			$data['checkin']  = $order->appointments[0]->customerCheckin->lc2;
			$data['service']  = $order->appointments[0]->service->name;
			$data['employee'] = $order->appointments[0]->employee->name;
		}

		/**
		 * This event can be used to extend/alter the value of the available
		 * placeholders that are going to be injected within a SMS template.
		 *
		 * @param 	array   &$data  The array with the available tags.
		 * @param 	object  $order  The order details.
		 * @param 	string  $tmpl   The SMS template.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger('onPopulateSmsPlaceholders', array(&$data, $order, $sms));
		
		// look for any placeholders
		$sms = preg_replace_callback("/{([a-zA-Z0-9\_]+)}/i", function($match) use ($data)
		{
			// obtain tag
			$tag = end($match);

			if (isset($data[$tag]))
			{
				// return related value
				return $data[$tag];
			}

			// unsupported tag, leave as is
			return $match[0];
		}, $sms);

		return $sms;
	}
	
	/**
	 * Sends a notification e-mail to the administrator(s) to
	 * inform that a SMS was not sent correctly.
	 *
	 * @param 	string 	$text 	The error message.
	 *
	 * @return 	void
	 */
	public static function sendAdminMailSmsFailed($text)
	{
		$vik = VAPApplication::getInstance();
					
		$admin_mail_list = self::getAdminMailList();
		$sendermail 	 = self::getSenderMail();
		$subject 		 = JText::translate('VAPSMSFAILEDSUBJECT');

		if (is_array($text))
		{
			// get rid of repeated messages
			$text = array_unique(array_filter($text, 'trim'));
			// join them within a string
			$text = implode('<br />', $text);
		}

		if (!$text)
		{
			// nothing to notify
			return;
		}
		
		foreach ($admin_mail_list as $_m)
		{
			$vik->sendMail($sendermail, $sendermail, $_m, null, $subject, $text, null, true);
		}
	}

	/**
	 * Sends a notification e-mail to the administrator(s) every
	 * time an error occurs while trying to validate a payment.
	 *
	 * @param 	integer  $id    The order number.   
	 * @param 	mixed 	 $text  Either an array of messages or a string.
	 *
	 * @return 	boolean  True in case the notification was sent, false otherwise.
	 *
	 * @since 	1.7
	 */
	public static function sendAdminMailPaymentFailed($id, $text)
	{
		if (is_array($text))
		{
			// join messages, separated by an empty line
			$text = implode('<br /><br />', $text);
		}

		$config = VAPFactory::getConfig();

		// get administrators e-mail
		$adminmails = self::getAdminMailList();
		// get sender e-mail address
		$sendermail = self::getSenderMail();
		// get company name
		$fromname = $config->getString('agencyname');
		
		// fetch e-mail subject
		$subject = sprintf('%s #%d - %s', JText::translate('VAPINVALIDPAYMENTSUBJECT'), $id, $fromname);

		$vik = VAPApplication::getInstance();

		$sent = false;
		
		// iterate e-mails to notify
		foreach ($adminmails as $recipient)
		{
			// send the e-mail notification
			$sent = $vik->sendMail($sendermail, $fromname, $recipient, $recipient, $subject, $text) || $sent;
		}

		return $sent;
	}

	/////////////////////////////////////////////
	///////////// LANGUAGE & i18n ///////////////
	/////////////////////////////////////////////

	/**
	 * Returns the default language of the specified section.
	 *
	 * @param 	string 	$section 	The section to check (site or administrator).
	 *
	 * @return 	atring 	The default language tag.
	 */
	public static function getDefaultLanguage($section = 'site')
	{
		return JComponentHelper::getParams('com_languages')->get($section);
	}
	
	/**
	 * Method used to force the site language of VikAppointments
	 * according to the specified language tag. If the language is
	 * not specified, the default one will be used.
	 *
	 * @param 	string 	$tag 	 The language tag.
	 * @param 	mixed 	$client  The base path of the language.
	 *
	 * @return 	void
	 */
	public static function loadLanguage($tag = null, $client = null)
	{
		if (!empty($tag))
		{
			/**
			 * Added support for client argument to allow also
			 * the loading of back-end languages.
			 *
			 * @since 1.7
			 */
			if (is_null($client))
			{
				if (JFactory::getApplication()->isClient('site'))
				{
					$client = JPATH_SITE;
				}
				else
				{
					$client = JPATH_ADMINISTRATOR;
				}
			}

			$lang = JFactory::getLanguage();

			/**
			 * In case the extension doesn't support the specified language,
			 * Joomla loads by default the default en-GB version.
			 * So, we don't need to add a fallback.
			 */
			$lang->load('com_vikappointments', $client, $tag, true);

			/**
			 * Reload system language too.
			 *
			 * @since 1.7
			 */
			$lang->load('joomla', $client, $tag, true);
		}
	}
	
	/**
	 * Returns a list of the installed languages.
	 *
	 * @param 	boolean  $assoc  True to return an associative array with the language details,
	 *                           false to obtain a linear array with the supported language tags
	 *                           (added @since 1.7.1).
	 *
	 * @return 	array 	 The languages list.
	 */
	public static function getKnownLanguages($assoc = false)
	{
		// get default language
		$def_lang = self::getDefaultLanguage('site');

		// get installed languages
		$known_languages = VAPApplication::getInstance()->getKnownLanguages();
		
		$languages = array();

		foreach ($known_languages as $k => $v)
		{
			if ($assoc)
			{
				$languages[$k] = $v;
			}
			else
			{
				if ($k == $def_lang)
				{
					// move default language in first position
					array_unshift($languages, $k);
				}
				else
				{
					// otherwise insert at the end
					array_push($languages, $k);
				}
			}
		}
		
		return $languages;
	}

	/**
	 * Translates a list of services groups.
	 *
	 * @param 	array 	&$groups  A list of groups (objects or arrays).
	 * @param 	string  $lang     An optional language to use. If not
	 * 							  specified, the current one will be used.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 *
	 * @uses 	translateRecords()
	 */
	public static function translateServicesGroups(&$groups, $lang = null)
	{
		self::translateRecords('group', $groups, $lang);
	}

	/**
	 * Translates a list of services.
	 *
	 * @param 	array 	&$services  A list of services (objects or arrays).
	 * @param 	string  $lang       An optional language to use. If not
	 * 							    specified, the current one will be used.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 *
	 * @uses 	translateRecords()
	 */
	public static function translateServices(&$services, $lang = null)
	{
		self::translateRecords('service', $services, $lang);
	}

	/**
	 * Translates a list of employees.
	 *
	 * @param 	array 	&$employees  A list of employees (objects or arrays).
	 * @param 	string  $lang        An optional language to use. If not
	 * 							     specified, the current one will be used.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 *
	 * @uses 	translateRecords()
	 */
	public static function translateEmployees(&$employees, $lang = null)
	{
		self::translateRecords('employee', $employees, $lang);
	}

	/**
	 * Translates a list of payments.
	 *
	 * @param 	array 	&$payments  A list of payments (objects or arrays).
	 * @param 	string  $lang       An optional language to use. If not
	 * 							    specified, the current one will be used.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 *
	 * @uses 	translateRecords()
	 */
	public static function translatePayments(&$payments, $lang = null)
	{
		self::translateRecords('payment', $payments, $lang);
	}

	/**
	 * Translates a list of subscriptions.
	 *
	 * @param 	array 	&$subscriptions  A list of subscriptions (objects or arrays).
	 * @param 	string  $lang            An optional language to use. If not
	 * 							         specified, the current one will be used.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 *
	 * @uses 	translateRecords()
	 */
	public static function translateSubscriptions(&$subscriptions, $lang = null)
	{
		self::translateRecords('subscription', $subscriptions, $lang);
	}

	/**
	 * Translates a list of generic translatable records.
	 *
	 * @param 	string 	$table     The translatable table name.
	 * @param 	array 	&$records  A list of records (objects or arrays).
	 * @param 	string  $lang      An optional language to use. If not
	 * 							   specified, the current one will be used.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	public static function translateRecords($table, &$records, $lang = null)
	{
		// make sure multi-language is supported
		if (!$records || !static::isMultilanguage())
		{
			return false;
		}

		if (!$lang)
		{
			// get current language tag if not specified
			$lang = JFactory::getLanguage()->getTag();
		}

		// get translator
		$translator = VAPFactory::getTranslator();

		// get translation table foreign key
		$fk = $translator->getTable($table)->getLinkedPrimaryKey();

		if (!is_array($records))
		{
			// always use an array
			$records = array($records);
			// remember that the argument was NOT an array
			$was_array = false;
		}
		else
		{
			// remember that the argument was already an array
			$was_array = true;
		}

		// extract IDs from records
		$ids = array();

		foreach ($records as $item)
		{
			$ids[] = is_object($item) ? $item->{$fk} : $item[$fk];
		}

		// preload table translations
		$tbLang = $translator->load($table, array_unique($ids), $lang);

		foreach ($records as &$item)
		{
			$id = is_object($item) ? $item->{$fk} : $item[$fk];

			// translate record for the given language
			$tx = $tbLang->getTranslation($id, $lang);

			if ($tx)
			{
				// get translations columns lookup
				$columns = $tbLang->getContentColumns($original = true);

				// iterate all the columns
				foreach ($columns as $colName)
				{
					// inject translation within the record
					if (is_object($item))
					{
						// treat record as object
						$item->{$colName} = $tx->{$colName};
					}
					else
					{
						// treat record as associative array
						$item[$colName] = $tx->{$colName};
					}
				}
			}
		}

		if (!$was_array)
		{
			// revert to original value
			$records = array_shift($records);
		}
	}
	
	/**
	 * Returns a list of translated groups.
	 *
	 * @param 	mixed 	$id 	 The ID of the record or a list of IDs. Leave empty to retrieve all the records.
	 * @param 	string 	$tag 	 The language tag. Leave empty to get the default one.
	 * @param 	mixed 	$dbo 	 The database object.
	 *
	 * @return 	array 	The translated groups. Each object can be easily accessed by using its PK.
	 *
	 * @uses 	getTranslatedObjects()
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function getTranslatedGroups($id = null, $tag = null, $dbo = null)
	{
		return self::getTranslatedObjects('group', 'id_group', $id, $tag, $dbo);
	}
	
	/**
	 * Returns a list of translated employees groups.
	 *
	 * @param 	mixed 	$id 	 The ID of the record or a list of IDs. Leave empty to retrieve all the records.
	 * @param 	string 	$tag 	 The language tag. Leave empty to get the default one.
	 * @param 	mixed 	$dbo 	 The database object.
	 *
	 * @return 	array 	The translated groups. Each object can be easily accessed by using its PK.
	 *
	 * @uses 	getTranslatedObjects()
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function getTranslatedEmployeeGroups($id = null, $tag = null, $dbo = null)
	{
		return self::getTranslatedObjects('empgroup', 'id_empgroup', $id, $tag, $dbo);
	}
	
	/**
	 * Returns a list of translated services.
	 *
	 * @param 	mixed 	$id 	 The ID of the record or a list of IDs. Leave empty to retrieve all the records.
	 * @param 	string 	$tag 	 The language tag. Leave empty to get the default one.
	 * @param 	mixed 	$dbo 	 The database object.
	 *
	 * @return 	array 	The translated services. Each object can be easily accessed by using its PK.
	 *
	 * @uses 	getTranslatedObjects()
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function getTranslatedServices($id = null, $tag = null, $dbo = null)
	{
		return self::getTranslatedObjects('service', 'id_service', $id, $tag, $dbo);
	}
	
	/**
	 * Returns a list of translated employees.
	 *
	 * @param 	mixed 	$id 	 The ID of the record or a list of IDs. Leave empty to retrieve all the records.
	 * @param 	string 	$tag 	 The language tag. Leave empty to get the default one.
	 * @param 	mixed 	$dbo 	 The database object.
	 *
	 * @return 	array 	The translated employees. Each object can be easily accessed by using its PK.
	 *
	 * @uses 	getTranslatedObjects()
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function getTranslatedEmployees($id = null, $tag = null, $dbo = null)
	{
		return self::getTranslatedObjects('employee', 'id_employee', $id, $tag, $dbo);
	}
	
	/**
	 * Returns a list of translated options.
	 *
	 * @param 	mixed 	$id 	 The ID of the record or a list of IDs. Leave empty to retrieve all the records.
	 * @param 	string 	$tag 	 The language tag. Leave empty to get the default one.
	 * @param 	mixed 	$dbo 	 The database object.
	 *
	 * @return 	array 	The translated options. Each object can be easily accessed by using its PK.
	 *
	 * @uses 	getTranslatedObjects()
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function getTranslatedOptions($id = null, $tag = null, $dbo = null)
	{
		$options = self::getTranslatedObjects('option', 'id_option', $id, $tag, $dbo);

		foreach ($options as $k => $opt)
		{
			// decode variations, which are stored in JSON format
			$options[$k]['vars_json'] = json_decode($opt['vars_json'], true);
		}

		return $options;
	}

	/**
	 * Returns a list of translated packages.
	 *
	 * @param 	mixed 	$id 	 The ID of the record or a list of IDs. Leave empty to retrieve all the records.
	 * @param 	string 	$tag 	 The language tag. Leave empty to get the default one.
	 * @param 	mixed 	$dbo 	 The database object.
	 *
	 * @return 	array 	The translated packages. Each object can be easily accessed by using its PK.
	 *
	 * @uses 	getTranslatedObjects()
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function getTranslatedPackages($id = null, $tag = null, $dbo = null)
	{
		return self::getTranslatedObjects('package', 'id_package', $id, $tag, $dbo);
	}

	/**
	 * Returns a list of translated packages groups.
	 *
	 * @param 	mixed 	$id 	 The ID of the record or a list of IDs. Leave empty to retrieve all the records.
	 * @param 	string 	$tag 	 The language tag. Leave empty to get the default one.
	 * @param 	mixed 	$dbo 	 The database object.
	 *
	 * @return 	array 	The translated groups. Each object can be easily accessed by using its PK.
	 *
	 * @uses 	getTranslatedObjects()
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function getTranslatedPackGroups($id = null, $tag = null, $dbo = null)
	{
		return self::getTranslatedObjects('package_group', 'id_package_group', $id, $tag, $dbo);
	}

	/**
	 * Returns a list of translated subscriptions.
	 *
	 * @param 	mixed 	$id 	 The ID of the record or a list of IDs. Leave empty to retrieve all the records.
	 * @param 	string 	$tag 	 The language tag. Leave empty to get the default one.
	 * @param 	mixed 	$dbo 	 The database object.
	 *
	 * @return 	array 	The translated subscriptions. Each object can be easily accessed by using its PK.
	 *
	 * @uses 	getTranslatedObjects()
	 *
	 * @since 	1.6
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function getTranslatedSubscriptions($id = null, $tag = null, $dbo = null)
	{
		return self::getTranslatedObjects('subscr', 'id_subscr', $id, $tag, $dbo);
	}

	/**
	 * Returns a list of translated payments.
	 *
	 * @param 	mixed 	$id 	 The ID of the record or a list of IDs. Leave empty to retrieve all the records.
	 * @param 	string 	$tag 	 The language tag. Leave empty to get the default one.
	 * @param 	mixed 	$dbo 	 The database object.
	 *
	 * @return 	array 	The translated payments. Each object can be easily accessed by using its PK.
	 *
	 * @uses 	getTranslatedObjects()
	 *
	 * @since 	1.6
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function getTranslatedPayments($id = null, $tag = null, $dbo = null)
	{
		return self::getTranslatedObjects('payment', 'id_payment', $id, $tag, $dbo);
	}
	
	/**
	 * Returns a list of translated objects.
	 *
	 * @param 	string 	$object  The table suffix of the objects to get.
	 * @param 	string 	$column  The column name used to match the specified IDs.
	 * @param 	mixed 	$id 	 The ID of the record or a list of IDs. Leave empty to retrieve all the records.
	 * @param 	string 	$tag 	 The language tag. Leave empty to get the default one.
	 * @param 	mixed 	$dbo 	 The database object.
	 *
	 * @return 	array 	The translated objects. Each object can be easily accessed by using its PK.
	 * 
	 * @deprecated 1.8  Without replacement.
	 */
	private static function getTranslatedObjects($object, $column, $id = null, $tag = null, $dbo = null)
	{
		if (!self::isMultilanguage())
		{
			return array();
		}
		
		if (!$tag)
		{
			$tag = JFactory::getLanguage()->getTag();
		}

		if (!$dbo)
		{
			$dbo = JFactory::getDbo();
		}

		$lim = null;

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_lang_' . $object))
			->where($dbo->qn('tag') . ' = ' . $dbo->q($tag));

		if ($id)
		{
			if (is_array($id))
			{
				$q->where($dbo->qn($column) . ' IN (' . implode(',', array_map('intval', $id)) . ')');
				$lim = count($id);
			}
			else
			{
				$q->where($dbo->qn($column) . ' = ' . (int) $id);
				$lim = 1;
			}
		}
		
		$dbo->setQuery($q, 0, $lim);
		
		$list = [];

		foreach ($dbo->loadAssocList() as $r)
		{
			$list[$r[$column]] = $r;
		}
		
		return $list;
	}
	
	/**
	 * Obtains the translated value. If the element is not translated, the default one will be used.
	 *
	 * @param 	integer  $id 		The ID of the record to translate.
	 * @param 	array 	 $original 	The original record (associative array).
	 * @param 	array 	 $transl 	The array containing all the translations.
	 * @param 	string 	 $match1 	The column name of the record to translate.
	 * @param 	string 	 $match2 	The column name of the translation.
	 * @param 	mixed 	 $default 	The default value to return in case it is empty.
	 *
	 * @return 	mixed 	 The translated value.
	 *
	 * @deprecated 1.8  Without replacement.
	 */
	public static function getTranslation($id, $original, $transl, $match1, $match2, $default = '')
	{	
		if (empty($transl[$id][$match2]))
		{
			// the record doesn't own a translation of this column
			if (!empty($original[$match1]))
			{
				// get the original value
				return $original[$match1];
			}
			else
			{
				// get the default value
				return $default;
			}
		}
		
		// get the translated value
		return $transl[$id][$match2];
	}
	
	/////////////////////////////////////////////
	////////////// EMPLOYEES AREA ///////////////
	/////////////////////////////////////////////
	
	/**
	 * Returns the settings of the given employee.
	 *
	 * @return 	array 	 The settings associative array.
	 *
	 * @deprecated 1.8  Use VAPEmployeeAuth::getSettings() instead.
	 */
	public static function getEmployeeSettings()
	{
		return (array) VAPEmployeeAuth::getInstance()->getSettings();
	}

	/**
	 * Refreshes the employee settings stored within the user state.
	 *
	 * @return 	void
	 *
	 * @deprecated 1.8  Without replacement, since the settings are no more cached.
	 */
	public static function refreshEmployeeSettings($id_employee)
	{
		// do nothing...	
	}
	
	// PDF
	
	/**
	 * Returns an array containing the invoice arguments.
	 *
	 * @param 	string 	$group 	The invoice group.
	 *
	 * @return 	object 	The invoice arguments.
	 *
	 * @deprecated 1.8  Use VAPInvoiceGenerator::getParams() instead.
	 */
	public static function getPdfParams($group = 'appointments')
	{
		VAPLoader::import('libraries.invoice.factory');
		return VAPInvoiceFactory::getGenerator()->getParams();
	}
	
	/**
	 * Returns an object containing the invoice properties.
	 *
	 * @param 	string 	$group 	The invoice group.
	 *
	 * @return 	object 	The invoice properties.
	 *
	 * @deprecated 1.8  Use VAPInvoiceGenerator::getConstraints() instead.
	 */
	public static function getPdfConstraints($group = 'appointments')
	{
		// load old constraints class for BC
		VAPLoader::registerAlias('lib.constraints', 'constraints');
		VAPLoader::import('pdf.constraints');

		VAPLoader::import('libraries.invoice.factory');
		return VAPInvoiceFactory::getGenerator()->getConstraints();
	}

	/**
	 * Helper method used to generate the invoices related to the specified
	 * order, which belong to the given group.
	 *
	 * @param 	mixed    $order  Either the order details object or an ID.
	 * @param 	string   $group  The invoices group (appointments by default).
	 *
	 * @return 	boolean  True on success, otherwise false.
	 *
	 * @since 	1.6
	 */
	public static function generateInvoice($order, $group = null)
	{
		// check whether the invoices should be automatically generated
		if (!VAPFactory::getConfig()->getBool('invoiceorders'))
		{
			// nope...
			return false;
		}

		if (is_array($order))
		{
			/**
			 * Extract order ID from given array for BC.
			 *
			 * @deprecated 1.8
			 */
			$id_order = $order[0]['id'];
		}
		else if (is_object($order))
		{
			$id_order = $order->id;
		}
		else
		{
			$id_order = (int) $order;
		}

		if (!$group)
		{
			$group = 'appointments';
		}

		// prepare invoice data
		$data = array(
			'group'    => $group,
			'id_order' => $id_order,
		);

		// get invoice model
		$model = JModelVAP::getInstance('invoice');
		// generate the invoice and dispatch e-mail notification (if configured)
		$id = $model->save($data);

		return (bool) $id;
	}

	// EMPLOYEES FILTERING

	/**
	 * Extends the search query using the custom filters.
	 *
	 * @param 	mixed 	 &$q 		The query builder object.
	 * @param 	array 	 $filters 	The associative array of filters.
	 * @param 	string 	 $alias 	The alias used for "employees" DB table.
	 * @param 	mixed 	 $dbo 		The database object.
	 *
	 * @return 	boolean  True if the query has been altered, otherwise false.
	 *
	 * @since 	1.6
	 */
	public static function extendQueryWithCustomFilters(&$q, array $filters = array(), $alias = null, $dbo = null)
	{
		if (!$dbo)
		{
			$dbo = JFactory::getDbo();
		}

		$lookup = array();

		foreach ($filters as $k => $v)
		{
			if ($v && strpos($k, 'field_') === 0)
			{
				$lookup[] = substr($k, 6);
			}
		}

		if (!$lookup)
		{
			// no custom filters
			return false;
		}

		$lookup = array_map(array($dbo, 'q'), $lookup);

		$q2 = $dbo->getQuery(true)
			->select($dbo->qn('formname'))
			->from($dbo->qn('#__vikappointments_custfields'))
			->where(array(
				$dbo->qn('group') . ' = 1',
				$dbo->qn('formname') . ' IN (' . implode(',', $lookup) . ')',
			));

		$dbo->setQuery($q2);
		$fields = $dbo->loadColumn();

		if (!$fields)
		{
			// no custom fields, possible hack attempt
			return false;
		}

		foreach ($fields as $field)
		{
			$key = 'field_' . $field;

			$q->where($dbo->qn(($alias ? $alias . '.' : '') . $key) . ' = ' . $dbo->q($filters[$key]));
		}

		return true;
	}

	// FRONT BUILDING

	/**
	 * Prepares the document related to the specified view.
	 * Used also to implement OPEN GRAPH protocol and to include
	 * global meta data.
	 *
	 * @param 	mixed 	$page 	The view object.
	 *
	 * @return 	void
	 */
	public static function prepareContent($page)
	{
		VAPLoader::import('libraries.view.contents');

		$handler = VAPViewContents::getInstance($page);

		/**
		 * Set the browser page title.
		 *
		 * @since 1.6.1
		 */
		$handler->setPageTitle();

		// show the page heading (if not provided, an empty string will be returned)
		$handler->getPageHeading(true);

		// set the META description of the page
		$handler->setMetaDescription();

		// set the META keywords of the page
		$handler->setMetaKeywords();

		// set the META robots of the page
		$handler->setMetaRobots();

		// create OPEN GRAPH protocol
		$handler->buildOpenGraph();

		// create MICRODATA
		$handler->buildMicrodata();
	}

	// USERS

	/**
	 * Tries to populate the custom fields values according to the details
	 * of the currently logged-in user.
	 *
	 * @param 	array 	 $list     A list of custom fields.
	 * @param 	array 	 &$fields  Where to inject the fetched data.
	 * @param 	boolean  $first    True whether the first name is usually
	 * 							   specified before the last name.
	 *
	 * @return 	void
	 *
	 * @since 	1.7
	 */
	public static function populateFields(array $list, array &$fields, $first = true)
	{
		// we do not need to import this file because when we call this method we 
		// are one step away from rendering the custom fields, so we can expect to
		// have that class already loaded
		VAPCustomFieldsRenderer::autoPopulate($fields, $list, $user = null, $first);
	}

	/**
	 * Helper method used to check if the current user is logged.
	 *
	 * @param 	mixed 	 $user 	The user object.
	 *
	 * @return 	boolean  True if logged, false otherwise.
	 */
	public static function isUserLogged($user = null)
	{
		if (!$user)
		{
			$user = JFactory::getUser();
		}
		
		return !$user->guest;
	}
	
	/**
	 * Helper method used to check if the provided arguments are correct
	 * in order to register a new Joomla user.
	 *
	 * @param  	array 	 $args 	The arguments to check.
	 *
	 * @return 	boolean  True if correct, false otherwise.
	 */
	public static function checkUserArguments(array $args)
	{
		if (!self::isUserLogged())
		{
			// proceed only in case the user is not logged
			return (
				!empty($args['firstname'])
				&& !empty($args['lastname'])
				&& !empty($args['username'])
				&& !empty($args['password'])
				&& self::validateUserEmail($args['email'])
				&& !strcmp($args['password'], $args['confpassword'])
			);
		}
		
		return false;
	}
	
	/**
	 * Validates the specified e-mail.
	 *
	 * @param 	string 	 $email  The email to check.
	 *
	 * @return 	boolean  True if valid, false otherwise.
	 */
	public static function validateUserEmail($email = '')
	{
		$isValid = true;
		$atIndex = strrpos($email, "@");

		if (is_bool($atIndex) && !$atIndex)
		{
			return false;
		}
		
		$domain 	= substr($email, $atIndex +1);
		$local  	= substr($email, 0, $atIndex);
		$localLen 	= strlen($local);
		$domainLen 	= strlen($domain);

		if ($localLen < 1 || $localLen > 64)
		{
			// local part length exceeded or too short
			return false;
		}

		if ($domainLen < 1 || $domainLen > 255)
		{
			// domain part length exceeded or too short
			return false;
		}
			
		if ($local[0] == '.' || $local[$localLen -1] == '.')
		{
			// local part starts or ends with '.'
			return false;
		}
				
		if (preg_match('/\\.\\./', $local))
		{
			// local part has two consecutive dots
			return false;
		}
					
		if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
		{
			// character not valid in domain part
			return false;
		}
		
		if (preg_match('/\\.\\./', $domain))
		{
			// domain part has two consecutive dots
			return false;
		} 

		if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local)))
		{
			// character not valid in local part unless local part is quoted
			if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local)))
			{
				return false;
			}
		}

		if (!checkdnsrr($domain, "MX") && !checkdnsrr($domain, "A"))
		{
			// domain not found in DNS
			return false;
		}
		
		return true;
	}

	/**
	 * Registers a new Joomla User with the details specified in the given $args associative array.
	 *
	 * @param 	array 	 $args 	The user details.
	 * @param 	integer  $type 	The registration type (for employee [1] or for users [2]).
	 *
	 * @return 	mixed 	 The user ID on success, false on failure,
	 * 					 the string status during the activation.
	 *
	 * @since 	1.0
	 * @since 	1.7      Alias for deprecated createNewJoomlaUser() method.
	 */
	public static function createNewUserAccount(array $args, $type = 2)
	{
		$app = JFactory::getApplication();

		// load com_users site language
		JFactory::getLanguage()->load('com_users', JPATH_SITE, JFactory::getLanguage()->getTag(), true);

		// save registration data within the user state, so that in case of
		// errors we can recover the entered details to auto-fill the form
		$app->setUserState('vap.cms.user.register', $args);

		if (VersionListener::isJoomla())
		{
			/**
			 * Autoload the form fields of com_users to avoid fatal errors, since Joomla 3.9.27
			 * seems to autoload the model forms/fields according to the current component.
			 *
			 * @since 1.7
			 */
			JForm::addFormPath(JPATH_SITE . '/components/com_users/models/forms');

			/**
			 * Joomla 4.0 moved the XML forms into a different folder.
			 *
			 * @since 1.7.1
			 */
			JForm::addFormPath(JPATH_SITE . '/components/com_users/forms');
		}

		// load UsersModelRegistration
		JModelLegacy::addIncludePath(JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_users' . DIRECTORY_SEPARATOR . 'models');
		$model = JModelLegacy::getInstance('registration', 'UsersModel');

		// adapt data for model
		$args['name'] 		= trim($args['firstname'] . ' ' . $args['lastname']);
		$args['email1'] 	= $args['email'];
		$args['password1'] 	= $args['password'];
		$args['block'] 		= 0;

		if ($type == self::REGISTER_EMPLOYEE)
		{
			$args['groups'] = array(VAPEmployeeAreaManager::getSignUpUserGroup());
		}

		/**
		 * Attempt to hijack the Privacy Policy plugin to auto-flag
		 * the privacy consent of the newly registered user.
		 *
		 * @since 1.7
		 */

		// get current request arguments
		$option = $app->input->get('option');
		$task   = $app->input->get->get('task');
		$form   = $app->input->post->get('jform', array());

		if (VAPFactory::getConfig()->getBool('gdpr'))
		{
			// force privacy consent in case GDPR setting was enabled
			$form['privacyconsent'] = array('privacy' => 1);
		}

		// hijack the Privacy Policy plugin condition
		$app->input->set('option', 'com_users');
		$app->input->get->set('task', 'registration.register');
		$app->input->post->set('jform', $form);

		/**
		 * It is now possible to validate the password against the com_users configuration.
		 * Compatible only with J4.x
		 * 
		 * @since 1.7.4
		 */
		if (VersionListener::isJoomla4x())
		{
			// obtain com_users registration form
			$form = $model->getForm();

			if ($form)
			{
				try
				{
					// validate the password against the com_users configuration
					$validate = $form->getField('password1')->validate($args['password']);

					if ($validate instanceof Exception)
					{
						$app->enqueueMessage($validate->getMessage(), 'error');
						return false;
					}
				}
				catch (Throwable $t)
				{
					// ignore in case of fatal errors
				}
			}
		}

		// register user
		$return = $model->register($args);

		// restore previous request arguments
		$app->input->set('option', $option);
		$app->input->get->set('task', $task);

		if ($return === false)
		{
			// impossible to save the user
			$app->enqueueMessage($model->getError(), 'error');
		}
		else if ($return === 'adminactivate')
		{
			// user saved: admin activation required
			$app->enqueueMessage(JText::translate('COM_USERS_REGISTRATION_COMPLETE_VERIFY'));
		}
		else if ($return === 'useractivate')
		{
			// user saved: self activation required
			$app->enqueueMessage(JText::translate('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE'));
		}
		else
		{
			// user saved: can login immediately
			$app->enqueueMessage(JText::translate('COM_USERS_REGISTRATION_SAVE_SUCCESS'));
		}

		if ($return !== false)
		{
			// unset user registration data on success
			$app->setUserState('vap.cms.user.register', null);
		}

		return $return;
	}

	/**
	 * Registers a new Joomla User with the details
	 * specified in the given $args associative array.
	 *
	 * @param 	array 	 $args 	The user details.
	 * @param 	integer  $type 	The registration type (for employee [1] or for users [2]).
	 *
	 * @return 	mixed 	The user ID on success, false on failure,
	 * 					the string status during the activation.
	 *
	 * @deprecated 1.8  Use VikAppointments::createNewUserAccount() instead.
	 *                  Get rid of "Joomla" word whenever is possible.
	 */
	public static function createNewJoomlaUser(array $args, $type = 2)
	{
		return static::createNewUserAccount($args, $type);
	}
	
	const REGISTER_EMPLOYEE = 1;
	const REGISTER_USERS 	= 2;
}
