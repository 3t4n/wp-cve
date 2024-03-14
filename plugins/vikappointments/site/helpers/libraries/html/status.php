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
 * Utility class working with VikAppointments status codes.
 *
 * @since  1.7
 */
abstract class VAPHtmlStatus
{
	/**
	 * A list of status codes to be cached.
	 *
	 * @var array
	 */
	protected static $statusCodes = null;

	/**
	 * Get a list of the available status codes.
	 *
	 * @return  array
	 */
	public static function codes()
	{
		if (static::$statusCodes === null)
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_status_code'))
				->order($dbo->qn('ordering') . ' ASC');

			$dbo->setQuery($q);
			static::$statusCodes = $dbo->loadObjectList();
		}

		return static::$statusCodes;
	}

	/**
	 * Checks whether the specified status code owns the APPROVED rule.
	 *
	 * @param 	string   $group  The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed 	 $code   The code to look for.
	 *
	 * @return 	boolean  True if matching, false otherwise.
	 */
	public static function isapproved($group, $code)
	{
		$where = array('approved' => 1, 'code' => $code, $group => 1);
		
		return (bool) self::findStrict('code', $where, $strict = false);
	}

	/**
	 * Returns the CONFIRMED status record or a specific column.
	 *
	 * @param 	string   $group   The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed 	 $column  The column to return or a list of columns.
	 * 							  Use * to return the whole record.
	 * @param 	boolean  $strict  True to throw an exception in case of missing status.
	 *
	 * @return 	mixed 	 The object status or the column value.
	 * 					 False if the status doesn't exist.
	 */
	public static function confirmed($group, $columns = '*', $strict = true)
	{
		return self::findStrict($columns, array('approved' => 1, 'reserved' => 1, 'paid' => 0, $group => 1), $strict, 'confirmed');
	}

	/**
	 * Checks whether the specified status code owns the CONFIRMED rules.
	 *
	 * @param 	string   $group  The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed 	 $code   The code to look for.
	 *
	 * @return 	boolean  True if matching, false otherwise.
	 */
	public static function isconfirmed($group, $code)
	{
		$where = array('approved' => 1, 'reserved' => 1, 'paid' => 0, 'code' => $code, $group => 1);
		
		return (bool) self::findStrict('code', $where, $strict = false);
	}

	/**
	 * Returns the PENDING status record or a specific column.
	 *
	 * @param 	string   $group   The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed 	 $column  The column to return or a list of columns.
	 * 							  Use * to return the whole record.
	 * @param 	boolean  $strict  True to throw an exception in case of missing status.
	 *
	 * @return 	mixed 	 The object status or the column value.
	 * 					 False if the status doesn't exist.
	 */
	public static function pending($group, $columns = '*', $strict = true)
	{
		return self::findStrict($columns, array('approved' => 0, 'reserved' => 1, $group => 1), $strict, 'pending');
	}

	/**
	 * Checks whether the specified status code owns the PENDING rules.
	 *
	 * @param 	string   $group  The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed 	 $code   The code to look for.
	 *
	 * @return 	boolean  True if matching, false otherwise.
	 */
	public static function ispending($group, $code)
	{
		$where = array('approved' => 0, 'reserved' => 1, 'code' => $code, $group => 1);
		
		return (bool) self::findStrict('code', $where, $strict = false);
	}

	/**
	 * Returns the REMOVED status record or a specific column.
	 *
	 * @param 	string   $group   The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed 	 $column  The column to return or a list of columns.
	 * 							  Use * to return the whole record.
	 * @param 	boolean  $strict  True to throw an exception in case of missing status.
	 *
	 * @return 	mixed 	 The object status or the column value.
	 * 					 False if the status doesn't exist.
	 */
	public static function removed($group, $columns = '*', $strict = true)
	{
		return self::findStrict($columns, array('expired' => 1, 'reserved' => 0, $group => 1), $strict, 'removed');
	}

	/**
	 * Checks whether the specified status code owns the REMOVED rules.
	 *
	 * @param 	string   $group  The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed 	 $code   The code to look for.
	 *
	 * @return 	boolean  True if matching, false otherwise.
	 */
	public static function isremoved($group, $code)
	{
		$where = array('expired' => 1, 'reserved' => 0, 'code' => $code, $group => 1);
		
		return (bool) self::findStrict('code', $where, $strict = false);
	}

	/**
	 * Returns the CANCELLED status record or a specific column.
	 *
	 * @param 	string   $group   The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed 	 $column  The column to return or a list of columns.
	 * 							  Use * to return the whole record.
	 * @param 	boolean  $strict  True to throw an exception in case of missing status.
	 *
	 * @return 	mixed 	 The object status or the column value.
	 * 					 False if the status doesn't exist.
	 */
	public static function cancelled($group, $columns = '*', $strict = true)
	{
		return self::findStrict($columns, array('cancelled' => 1, 'paid' => 0, $group => 1), $strict, 'cancelled');
	}

	/**
	 * Checks whether the specified status code owns the CANCELLED rules.
	 *
	 * @param 	string   $group  The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed 	 $code   The code to look for.
	 *
	 * @return 	boolean  True if matching, false otherwise.
	 */
	public static function iscancelled($group, $code)
	{
		$where = array('cancelled' => 1, 'code' => $code, $group => 1);
		
		return (bool) self::findStrict('code', $where, $strict = false);
	}

	/**
	 * Returns the REFUNDED status record or a specific column.
	 *
	 * @param 	string   $group   The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed 	 $column  The column to return or a list of columns.
	 * 							  Use * to return the whole record.
	 * @param 	boolean  $strict  True to throw an exception in case of missing status.
	 *
	 * @return 	mixed 	 The object status or the column value.
	 * 					 False if the status doesn't exist.
	 */
	public static function refunded($group, $columns = '*', $strict = true)
	{
		return self::findStrict($columns, array('cancelled' => 1, 'paid' => 1, $group => 1), $strict, 'refunded');
	}

	/**
	 * Checks whether the specified status code owns the REFUNDED rules.
	 *
	 * @param 	string   $group  The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed 	 $code   The code to look for.
	 *
	 * @return 	boolean  True if matching, false otherwise.
	 */
	public static function isrefunded($group, $code)
	{
		$where = array('cancelled' => 1, 'paid' => 1, 'code' => $code, $group => 1);
		
		return (bool) self::findStrict('code', $where, $strict = false);
	}

	/**
	 * Returns the PAID status record or a specific column.
	 *
	 * @param 	string   $group   The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed 	 $column  The column to return or a list of columns.
	 * 							  Use * to return the whole record.
	 * @param 	boolean  $strict  True to throw an exception in case of missing status.
	 *
	 * @return 	mixed 	 The object status or the column value.
	 * 					 False if the status doesn't exist.
	 */
	public static function paid($group, $columns = '*', $strict = true)
	{
		return self::findStrict($columns, array('approved' => 1, 'reserved' => 1, 'paid' => 1, $group => 1), $strict, 'paid');
	}

	/**
	 * Checks whether the specified status code owns the PAID rules.
	 *
	 * @param 	string   $group  The group to look for (appointments, packages or subscriptions).
	 * @param 	mixed 	 $code   The code to look for.
	 *
	 * @return 	boolean  True if matching, false otherwise.
	 */
	public static function ispaid($group, $code)
	{
		$where = array('approved' => 1, 'reserved' => 1, 'paid' => 1, 'code' => $code, $group => 1);
		
		return (bool) self::findStrict('code', $where, $strict = false);
	}

	/**
	 * Displays the specified status by using the given layout.
	 *
	 * @param 	mixed 	$status  Either an array, an object or the status code.
	 * @param 	string 	$layout  The type of layout to use.
	 *
	 * @return 	string  The HTML code to display.
	 */
	public static function display($status, $layout = null)
	{
		if (is_scalar($status))
		{
			// find status by code
			$status = self::find('*', array('code' => $status), $limit = true);
		}

		if (!$status)
		{
			// status not found, do not go ahead
			return '';
		}

		if (is_object($status))
		{
			// clone object to lose the reference from the original element
			$status = clone $status;
		}
		else
		{
			// otherwise treat as object
			$status = (object) $status;
		}

		/**
		 * Ignore translation in case the multilingual feature is disabled.
		 * 
		 * @since 1.7.4
		 */
		if (VAPFactory::getConfig()->getBool('ismultilang'))
		{
			// get translator object
			$translator = VAPFactory::getTranslator();
			// translate status code
			$tx = $translator->translate('statuscode', $status->id);

			if ($tx)
			{
				// apply translation
				$status->name        = $tx->name;
				$status->description = $tx->description;
			}
		}

		if (!$layout)
		{
			// use default layout when omitted
			$layout = 'default';
		}
		else if ($layout == 'plain')
		{
			// return only the status name without style
			return $status->name;
		}

		// render layout, hoping that the specified one exists
		return JLayoutHelper::render('status.' . $layout, array('status' => $status));
	}

	/**
	 * Displays the popup used to change status code.
	 *
	 * @param 	integer  $group     The group to which the codes belong (appointments,
	 *                              packages of subscriptions).
	 * @param 	string   $selector  The popup trigger selector.
	 *
	 * @return 	void
	 */
	public static function contextmenu($group, $selector = null)
	{
		$document = JFactory::getDocument();

		static $loaded = array();

		if (!isset($loaded[$group]))
		{
			// get status codes
			$codes = static::find('*', array($group => 1));

			// encode codes for JS usage
			$json = json_encode($codes);

			// register supported status codes
			$document->addScriptDeclaration(
<<<JS
onInstanceReady(() => {
	return typeof VIKAPPOINTMENTS_STATUS_CODES_MAP !== 'undefined';
}).then(() => {
	VIKAPPOINTMENTS_STATUS_CODES_MAP['$group'] = $json;
});
JS
			);
		}

		if ($selector)
		{
			switch ($group)
			{
				case 'appointments':
					$controller = 'reservation';
					break;

				case 'packages':
					$controller = 'packorder';
					break;

				case 'subscriptions':
					$controller = 'subscrorder';
					break;

				default:
					$controller = '';
			}

			// fetch AJAX URL
			$url = "index.php?option=com_vikappointments&task={$controller}.changestatusajax";
			$url = VAPApplication::getInstance()->ajaxUrl($url, false);

			$document->addScriptDeclaration(
<<<JS
jQuery(function($) {
	$('$selector').statusCodesPopup({
		group: '{$group}',
		url: '{$url}',
	});
});
JS
			);
		}

		static $_loaded = 0;

		if (!$_loaded)
		{
			$_loaded = 1;

			JText::script('VAPCONNECTIONLOSTERROR');

			// load script
			JHtml::fetch('vaphtml.assets.statuscodes');

			$document->addScriptDeclaration(
<<<JS
jQuery(window).on('statuscode.error', function(event, error) {
	alert(error.responseText || Joomla.JText._('VAPCONNECTIONLOSTERROR'));
});
JS
			);
		}
	}

	/**
	 * Helper method used to find the requested status(es).
	 *
	 * @param 	mixed 	$column  The column to return or a list of columns.
	 * 							 Use * to return the whole record.
	 * @param 	array 	$where 	 An associative array containing the keys and
	 * 							 the values that should assume.
	 * 
	 *
	 * @return 	mixed 	The object status or the column value.
	 *                  In case of omitted limit, the resulting values
	 *                  will be wrapped within an array.
	 * 					False (or an empty list) if the status doesn't exist.
	 */
	public static function find($columns, array $where, $limit = false)
	{
		// get codes
		$codes = self::codes();

		$result = array();

		// iterate the codes
		foreach ($codes as $status)
		{
			$found = true;

			// check if where is matching
			foreach ($where as $k => $v)
			{
				$found = $found && ($status->{$k} == $v);
			}

			// check if the status has been found
			if ($found)
			{
				if ($columns == '*')
				{
					// return the whole record
					$ret = $status;
				}
				else if (is_scalar($columns))
				{
					// return requested column
					$ret = isset($status->{$columns}) ? $status->{$columns} : null;
				}
				else
				{
					// return an object containing the specified columns
					$ret = new stdClass;
					
					// copy the columns within the temporary object
					foreach ($columns as $col)
					{
						$ret->{$col} = isset($status->{$col}) ? $status->{$col} : null;
					}
				}

				// include return value within the list
				$result[] = $ret;
			}
		}

		if (!$limit)
		{
			// return list of codes
			return $result;
		}

		// in case of matching result, return the first one available, otherwise false
		return $result ? $result[0] : false;
	}

	/**
	 * Helper method used to find the requested status.
	 * In case of missing status code, an exception will be thrown.
	 *
	 * @param 	mixed 	 $column  The column to return or a list of columns.
	 * 							  Use * to return the whole record.
	 * @param 	array 	 $where   An associative array containing the keys and
	 * 							  the values that should assume.
	 * @param 	boolean  $strict  True to raise the exception in case of error.
	 * @param 	string   $id      The status code identifier, only use to describe
	 *                            the error message.
	 * 
	 *
	 * @return 	mixed 	The object status or the column value.
	 *
	 * @throws 	Exception
	 */
	protected static function findStrict($columns, $where, $strict, $id = '')
	{
		// load requested status code
		$result = self::find($columns, $where, $limit = true);

		if ($result === false && $strict)
		{
			// status code not found, throw the exception
			throw new Exception(sprintf('Status code [%s] not found!', $id), 404);
		}

		return $result;
	}
}
