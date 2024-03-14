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

VAPLoader::import('libraries.tax.loader');

/**
 * Taxes factory class.
 *
 * @since 1.7
 */
abstract class VAPTaxFactory
{
	/**
	 * Lookup used to store the TAX id of the specified subjects.
	 *
	 * @var array
	 */
	protected static $lookup = array();

	/**
	 * Calculates the taxes of the specified amount according
	 * to the rules of the given tax ID.
	 *
	 * @param 	integer  $id       The tax ID.
	 * @param 	float    $total    The total amount to check.
	 * @param 	array    $options  An array of options.
	 *
	 * @return 	mixed    An object containing the resulting taxes.
	 */
	public static function calculate($id, $total, array $options = array())
	{
		// create fake tax instance to handle prices without a
		// specified tax rule or to speed up the process in case
		// of free/negative amounts
		$tax = new VAPTax();
		
		$default = $tax->calculate($total, $options);

		if ($total <= 0)
		{
			// immediately return default object
			return $default;
		}

		// calculate costs
		$amount = static::_calculate($id, $total, $options);

		if ($amount === null)
		{
			// tax not found, return default object
			return $default;
		}

		return $amount;
	}

	/**
	 * Calculates the taxes of the specified amount according
	 * to the rules of the given tax ID.
	 *
	 * @param 	integer  $id       The tax ID.
	 * @param 	float    $total    The total amount to check.
	 * @param 	array    $options  An array of options.
	 *
	 * @return 	mixed    An object containing the resulting taxes on success,
	 *                   null in case the tax doesn't exist.
	 */
	protected static function _calculate($id, $total, array $options = array())
	{
		// obtain tax object
		$tax = static::getTaxObject($id, $options);

		if (!$tax)
		{
			// unable to detect tax handler
			return null;
		}

		// calculate taxes
		return $tax->calculate($total, $options);
	}

	/**
	 * Obtains the object able to calculate the taxes for the specified element.
	 * 
	 * @param 	integer  $id       The tax ID.
	 * @param 	array    $options  An array of options.
	 * 
	 * @return  VAPTax|null
	 * 
	 * @since   1.7.4
	 */
	public static function getTaxObject($id, array $options = array())
	{
		// check whether the specified ID is referring to
		// a specific database table
		if (!empty($options['subject']))
		{
			// find tax ID of given subject
			$id = static::getTaxOf($id, $options['subject']);
		}

		/**
		 * Trigger hook to allow external plugins to switch tax ID at
		 * runtime, which may vary according to the specified options.
		 *
		 * @param 	integer  $id       The current tax ID.
		 * @param 	array    $options  An array of options.
		 *
		 * @return 	mixed    The new ID of the tax to apply. Return false to ignore
		 *                   the taxes calculation.
		 *
		 * @since 	1.7
		 */
		$result = VAPFactory::getEventDispatcher()->trigger('onBeforeUseTax', array($id, $options));

		if ($result)
		{
			if (in_array(false, $result, true))
			{
				// do not calculate, as instructed by a third-party plugin
				return null;
			}

			// a plugin manipulated the ID, overwrite the default one
			$id = (int) $result[0];
		}

		if (!$id)
		{
			// get global tax
			$id = VAPFactory::getConfig()->getUint('deftax', 0);
		}

		if (!$id)
		{
			// no existing taxes
			return null;
		}

		try
		{
			// check whether the caller requested a specific language
			// for translating the details of the taxes
			$lang = isset($options['lang']) ? $options['lang'] : null;

			// obtain tax handler
			$tax = VAPTaxLoader::load($id, $lang);
		}
		catch (Exception $e)
		{
			// tax not found, abort
			return null;
		}

		return $tax;
	}

	/**
	 * Returns the tax ID used by the specified subject.
	 *
	 * @param 	integer  $id       The record id.
	 * @param 	string   $subject  An identifier to detect the table of the record.
	 *
	 * @return 	mixed    The tax ID on success, false otherwise.
	 */
	public static function getTaxOf($id, $subject)
	{
		if (!isset(static::$lookup[$subject]))
		{
			// init subject pool
			static::$lookup[$subject] = array();
		}

		// look for a cached ID
		if (!isset(static::$lookup[$subject][$id]))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true);

			$q->select($dbo->qn('id_tax'));
			$q->where($dbo->qn('id') . ' = ' . (int) $id);

			switch ($subject)
			{
				case 'service':
					$q->from($dbo->qn('#__vikappointments_service'));
					break;

				case 'option':
					$q->from($dbo->qn('#__vikappointments_option'));
					break;

				case 'package':
					$q->from($dbo->qn('#__vikappointments_package'));
					break;

				case 'payment':
					$q->from($dbo->qn('#__vikappointments_gpayments'));
					break;

				case 'subscription':
					$q->from($dbo->qn('#__vikappointments_subscription'));
					break;

				default:
					return false;
			}

			$dbo->setQuery($q, 0, 1);
			$taxId = $dbo->loadResult();

			if ($taxId)
			{
				// cache tax ID
				static::$lookup[$subject][$id] = (int) $taxId;
			}
			else
			{
				// tax not found
				static::$lookup[$subject][$id] = false;
			}
		}

		return static::$lookup[$subject][$id];
	}

	/**
	 * Returns a list of supported math operators.
	 *
	 * @return 	array
	 */
	public static function getMathOperators()
	{
		$op = array();

		// load default drivers
		$default = glob(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'rules' . DIRECTORY_SEPARATOR . '*.php');

		foreach ($default as $file)
		{
			// get name of the file without extension
			$name = preg_replace("/\.php$/i", '', basename($file));

			// register operator
			$op[$name] = JText::translate('VAPTAXMATHOP_' . strtoupper($name));
		}

		/**
		 * Trigger hook to allow external plugins to support custom operations.
		 * New operations have to be appended to the given associative array.
		 * The key of the array is the unique ID of the operation, the value is
		 * a readable name to display.
		 *
		 * @param 	array  &$operators  An array of operations.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger('onLoadTaxOperators', array(&$op));

		// sort by ascending name and preserve keys
		asort($op);

		return $op;
	}
}
