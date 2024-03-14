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

/**
 * Invoices factory class.
 *
 * @since 	1.6
 */
class VAPInvoiceFactory
{
	/**
	 * A list of instances.
	 *
	 * @var array
	 */
	protected static $classes = array();

	/**
	 * Returns a new instance of this object, only creating it
	 * if it doesn't already exist.
	 *
	 * @param 	array 	$order 	The order details.
	 * @param 	string 	$group 	The invoices group.
	 *
	 * @return 	VAPInvoice 	A new instance of the invoice object.
	 *
	 * @deprecated 1.8      Use getInvoice() instead.
	 */
	public static function getInstance($order, $group = null)
	{
		return static::getInvoice($order, $group);
	}

	/**
	 * Returns a new instance of this object, only creating it
	 * if it doesn't already exist.
	 *
	 * @param 	array 	$order 	The order details.
	 * @param 	string 	$group 	The invoices group.
	 *
	 * @return 	VAPInvoice 	A new instance of the invoice object.
	 *
	 * @since 	1.7
	 */
	public static function getInvoice($order, $group = null)
	{
		if (!isset(static::$classes[$group]))
		{
			if (!VAPLoader::import('libraries.invoice.classes.' . $group))
			{
				throw new Exception('Invoice group [' . $group . '] not supported', 404);
			}

			$classname = 'VAPInvoice' . ucwords($group);

			if (!class_exists($classname))
			{
				throw new Exception('Invoice handler [' . $classname . '] not found', 404);
			}

			static::$classes[$group] = $classname;
		}

		// get cached classname
		$classname = static::$classes[$group];

		// instantiate new object
		$obj = new $classname($order);

		if (!$obj instanceof VAPInvoice)
		{
			throw new Exception('The invoice handler [' . $classname . '] is not a valid instance', 500);
		}

		return $obj;
	}

	/**
	 * Returns a new generator instance.
	 *
	 * @param 	mixed       $data     An object containing the invoice arguments or
	 *                                the ID of the employee issuing the invoice.
	 *                                
	 * @param 	VAPInvoice  $invoice  The invoice instance to generate. If not
	 *                                specified, it will be possible to set it in a
	 *                                second time.
	 *
	 * @return 	VAPInvoiceGenerator   A new generator instance.
	 *
	 * @since 	1.7
	 */
	public static function getGenerator($data = null, $invoice = null)
	{
		if (is_numeric($data))
		{
			VAPLoader::import('libraries.invoice.generators.employee');

			/**
			 * @todo Implement employee generator once the employees will be
			 *       able to issue their own invoices.
			 */
			// return new VAPInvoiceGeneratorEmployee($invoice, $data);
		}

		// create default generator
		VAPLoader::import('libraries.invoice.generator');
		return new VAPInvoiceGenerator($invoice, $data);
	}
}
