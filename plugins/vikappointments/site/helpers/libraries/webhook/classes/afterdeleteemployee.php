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
 * Web hook handler for employees deletion.
 *
 * @since 1.7
 */
class VAPWebHookAfterDeleteEmployee extends VAPWebHook
{
	/**
	 * @override
	 * Class constructor.
	 *
	 * @param 	string 	 $hook     The hook name.
	 * @param 	mixed    $payload  The payload to delivery.
	 */
	public function __construct($hook, $payload)
	{
		if (is_array($payload))
		{
			// The payload is an array containing the list of all the deleted
			// employees and a JTable instance. We need to use only the first
			// argument of the list.
			$payload = array_shift($payload);
		}

		// construct through parent
		parent::__construct('onAfterDeleteEmployee', $payload);
	}

	/**
	 * @override
	 * Returns a readable name of the hook.
	 *
	 * @return 	string
	 */
	public function getName()
	{
		return JText::translate('VAP_WEBHOOK_DELETE_EMPLOYEE');
	}

	/**
	 * @override
	 * Returns the registered payload.
	 *
	 * @param 	mixed  $options  Either an array or an object, which should contain
	 *                           the value of the specified parameters.
	 *
	 * @return 	mixed
	 */
	public function getPayload($options = array())
	{
		// avoid duplicates
		$ids = array_values(array_unique($this->payload));

		if (count($ids) > 1)
		{
			// load list of IDs
			$payload = array('id' => $ids);
		}
		else
		{
			// take only the first element
			$payload = array('id' => array_shift($ids));
		}
		
		return $payload;
	}

	/**
	 * @override
	 * Comparator to check whether 2 instances share the same payload parent.
	 *
	 * @return 	boolean
	 */
	public function equalsTo($webhook)
	{
		if (!$webhook instanceof VAPWebHookAfterDeleteEmployee)
		{
			// invalid instance
			return false;
		}

		// always use a single instance containing all the deleted records
		return true;
	}
}
