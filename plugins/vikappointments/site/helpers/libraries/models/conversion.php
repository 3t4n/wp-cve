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
 * VikAppointments conversion code class handler.
 * In order to work, the configuration must own the 
 * following settings:
 * - conversion_track 	boolean  Flag to check if the conversion track is enabled.
 *
 * @since  	1.6
 */
class VAPConversion
{
	/**
	 * A list of instances.
	 *
	 * @var array
	 */
	protected static $instances = array();

	/**
	 * A list of supported conversion rules.
	 *
	 * @var array
	 */
	protected $list = array();

	/**
	 * The database table.
	 *
	 * @var string
	 */
	protected $table;

	/**
	 * The page that we are visiting.
	 *
	 * @var string
	 */
	protected $page;

	/**
	 * Returns the list of all the pages that support conversions.
	 *
	 * @return array
	 */
	public static function getSupportedPages()
	{
		$pages = array(
			'confirmapp',
			'order',
		);

		/**
		 * Loads a list of supported pages that can be used while creating/editing a conversion code.
		 *
		 * The name of the page must be equals to the view name in the front-end. In example, the page
		 * displaying the list of employees is called "employeeslist".
		 *
		 * @return 	array  An array of supported pages.
		 *
		 * @since 	1.7
		 */
		$results = VAPFactory::getEventDispatcher()->trigger('onLoadSupportedConversionPages');

		// join resulting pages with the default ones
		foreach ($results as $list)
		{
			$pages = array_merge($pages, $list);
		}

		// get rid of duplicates
		return array_values(array_unique($pages));
	}

	/**
	 * Returns the list of all the types (db tables) that support conversions.
	 *
	 * @return array
	 */
	public static function getSupportedTypes()
	{
		$types = array(
			'reservation',
		);

		/**
		 * Loads a list of supported types that can be used while creating/editing a conversion code.
		 *
		 * The name of the type must be equals to the database table name. In example, the table holding
		 * the orders of the packages is called "package_order". The selected table must own the following
		 * columns for a correct tracking:
		 * - `id`         int          the primary key;
		 * - `conversion` varchar(64)  holds the conversion cookie signature.
		 *
		 *
		 * @return 	array  An array of supported types.
		 *
		 * @since 	1.7
		 */
		$results = VAPFactory::getEventDispatcher()->trigger('onLoadSupportedConversionTypes');

		// join resulting types with the default ones
		foreach ($results as $list)
		{
			$types = array_merge($types, $list);
		}

		// get rid of duplicates
		return array_values(array_unique($types));
	}

	/**
	 * Returns a new instance of this object, only creating it
	 * if it doesn't already exist.
	 *
	 * @param 	mixed 	$options 	The database table or an array of options.
	 *
	 * @return 	self 	A new instance of this object.
	 *
	 * @see 	__construct() 	for further details about the $options array.
	 */
	public static function getInstance($options = null)
	{
		$sign = serialize($options);

		if (!isset(static::$instances[$sign]))
		{
			static::$instances[$sign] = new static($options);
		}

		return static::$instances[$sign];
	}

	/**
	 * Class constructor.
	 *
	 * @param 	mixed 	$options 	The database table or an array of options.
	 *								The options array can contain the values below
	 * 								- table 	 the database table name ("reservations" by default).
	 * 											 Since we are using a class of VikAppointments,
	 * 											 the prefix "#__vikappointments_" must be omitted;
	 *
	 * @uses 	loadConversionRules()
	 */
	public function __construct($options = null)
	{
		if (!is_array($options))
		{
			// string given, create an array of options
			$options = array('table' => $options);
		}

		if (empty($options['table']))
		{
			// the table attribute is empty, use the default table
			$options['table'] = '#__vikappointments_reservation';
		}
		else
		{
			// prepend the table prefix to the existing value
			$options['table'] = '#__vikappointments_' . $options['table'];
		}

		if (empty($options['page']))
		{
			// the page attribute is empty, ignore this filter
			$options['page'] = '*';
		}

		$this->table = $options['table'];
		$this->page  = $options['page'];

		$this->loadConversionRules();
	}

	/**
	 * Loads all the conversion rules supported
	 * by the specified table.
	 *
	 * @return 	void
	 */
	protected function loadConversionRules()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select('*')
			->from($dbo->qn('#__vikappointments_conversion'))
			->where(array(
				$dbo->qn('published') . ' = 1',
				$dbo->qn('type') . ' = ' . $dbo->q(preg_replace("/^#__vikappointments_/i", '', $this->table)),
			));

		if ($this->page != '*')
		{
			$q->where($dbo->qn('page') . ' = ' . $dbo->q($this->page));
		}

		$dbo->setQuery($q);
		
		foreach ($dbo->loadObjectList() as $obj)
		{
			// decode statuses array
			$obj->statuses = (array) json_decode($obj->statuses);
			// push the record within the list
			$this->list[] = $obj;
		}
	}

	/**
	 * Attaches the script used for the conversion code.
	 * The script will be printed only if it is configured
	 * and the order hasn't been tracked yet.
	 *
	 * @param 	VAPOrderWrapper  $order  The order details.
	 *
	 * @return 	void
	 *
	 * @uses 	shouldBeTracked()
	 * @uses 	registerOrder()
	 * @uses 	parseSnippet()
	 */
	public function trackCode($order = null)
	{
		$config = VAPFactory::getConfig();

		// check if conversion is enabled
		$enabled = $config->getBool('conversion_track', 0);

		if (!$enabled)
		{
			// disabled conversion
			return;
		}

		// cast order to object
		$order = (object) $order;

		// get compliant conversion object
		$conversion = $this->shouldBeTracked($order);

		if (!$conversion)
		{
			// conversion code disabled or not compliant
			return;
		}

		// register order
		$this->registerOrder($order);

		if ($conversion->jsfile)
		{
			// append JS file
			JHtml::fetch('script', $conversion->jsfile);
		}

		// extract <script> and <noscript> from snippet
		$script = $this->parseSnippet($conversion, $order, $noscript);

		if ($script)
		{
			// attach the script to the <head> of the document
			JFactory::getDocument()->addScriptDeclaration($script);
		}

		if ($noscript)
		{
			// display <noscript> as soon as possible
			echo $noscript;
		}
	}

	/**
	 * Checks if the given order should be tracked.
	 * 
	 * @param 	mixed  $order  The order that should be tracked.
	 *
	 * @return 	mixed  The conversion record object if found, otherwise false.
	 */
	public function shouldBeTracked($order)
	{
		if (!$this->list)
		{
			// no conversion track found
			return false;
		}

		if (isset($order->conversion))
		{
			$conversion = $order->conversion;
		}
		else
		{
			$cookie = JFactory::getApplication()->input->cookie;
			// try to get the last conversion used from the cookie
			$conversion = $cookie->get('vapconversion', '', 'string');
		}
		
		$status = isset($order->status) ? $order->status : '*';

		$new_code = $this->page . '.' . strtolower($status);

		// iterate the records list
		foreach ($this->list as $code)
		{
			if (($status == '*' || in_array($status, $code->statuses)) && strcasecmp($new_code, $conversion))
			{
				// the status changed, track it
				$order->conversion = $new_code;

				// return the tracking record
				return $code;
			}
		}

		// type not supported or same conversion type
		return false;
	}

	/**
	 * Updates the order in the database to register the conversion code.
	 * In case the ID is not provided, the conversion will be registered in
	 * the cookie of the browser.
	 *
	 * @param 	mixed  $order  The order to track.
	 *
	 * @return 	void
	 */
	protected function registerOrder($order)
	{
		if (isset($order->id))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->update($dbo->qn($this->table))
				->set($dbo->qn('conversion') . ' = ' . $dbo->q($order->conversion))
				->where($dbo->qn('id') . ' = ' . (int) $order->id);

			$dbo->setQuery($q);
			$dbo->execute();
		}
		else
		{
			$cookie = JFactory::getApplication()->input->cookie;

			// keep the tracking cookie only for 15 minutes
			$cookie->set('vapconversion', $order->conversion, time() + (15 * 60), '/');
		}
	}

	/**
	 * Parses the snippet to inject some information about 
	 * the order, such as the total amount paid.
	 *
	 * @param 	object 	$conversion  The conversion code.
	 * @param 	mixed   $order       The order that should be tracked.
	 * @param 	string  &$noscript   The <noscript> declaration in case it
	 * 								 was specified within the code snippet.
	 *
	 * @param 	string 	The resulting snippet.
	 */
	public function parseSnippet($conversion, $order, &$noscript = '')
	{
		// extract snippet from conversion code
		$snippet = $conversion->snippet;

		/**
		 * Check if the snippet specifies the <script> declaration,
		 * so that we can support <noscript> tag if specified.
		 *
		 * @since 1.6.5
		 */
		if (preg_match("/\s*<\s*?script[\s0-9a-zA-Z=\"'\/]*>/", $snippet))
		{
			// tags found, try to extract <script> and <noscript> from snippet
			if (preg_match("/(?:^\s*<\s*?script[\s0-9a-zA-Z=\"'\/]*>)(.*?)(?:<\/script>)/is", $snippet, $match))
			{
				// extract pure JavaScript from matching results
				$script = trim(end($match));
			}
			else
			{
				// script not found
				$script = '';
			}

			if (preg_match("/\s*<\s*?noscript[\s0-9a-zA-Z=\"'\/]*>.*?<\/noscript>/is", $snippet, $match))
			{
				// extract full <noscript> declaration
				$noscript = trim(end($match));
			}
			else
			{
				// script not found
				$noscript = '';
			}
		}
		else
		{
			// no <script> tag found, a pure JavaScript code is assumed
			$script = $snippet;
		}

		// create lookup array for placeholders injection
		$lookup = array();

		VAPLoader::import('libraries.order.wrapper');

		if ($order instanceof VAPOrderWrapper)
		{
			// extract basic details from order
			$lookup['id']         = $order->id;
			$lookup['total_cost'] = $order->totals->gross;
			$lookup['status']     = $order->statusRole;

			if ($order instanceof VAPOrderAppointment)
			{
				$lookup['service'] = $lookup['employee'] = array();

				// extract booked services and employees
				foreach ($order->appointments as $appointment)
				{
					$lookup['service'][] = $appointment->service->name;

					// register employee only if selected by the user
					if ($appointment->viewEmp)
					{
						$lookup['employee'][] = $appointment->employee->name;
					}
				}

				// stringify selected services and employees
				$lookup['service']  = implode(', ', array_unique($lookup['service']));
				$lookup['employee'] = implode(', ', array_unique($lookup['employee']));
			}
		}
		else
		{
			// use the whole order
			$lookup = $order;
		}

		/**
		 * Trigger hook before parsing the placeholders contained within a snippet of
		 * a conversion/tracking code. It is possible to use this hook to inject or
		 * change the attributes of the $lookup array.
		 *
		 * Here's how to add support for a new placeholder:
		 * $lookup['tax'] = 12.50;
		 * And here's how to include that value within the JS snippet:
		 * var taxAmount = {tax};
		 *
		 * @param 	array   &$lookup     An array of placeholders.
		 * @param 	mixed   $order       An array/object holding the order details.
		 * @param 	object  $conversion  The conversion details.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger('onBeforeParseConversionSnippet', array(&$lookup, $order, $conversion));

		foreach ($lookup as $k => $v)
		{
			if (is_scalar($v))
			{
				/**
				 * Replace specified placeholders with the order vars.
				 *
				 * @since 1.6.5  Placeholders are properly escaped.
				 */
				$script   = preg_replace('/{' . addslashes($k) . '}/i', $v, $script);
				$noscript = preg_replace('/{' . addslashes($k) . '}/i', $v, $noscript);
			}
		}

		return $script;
	}
}
