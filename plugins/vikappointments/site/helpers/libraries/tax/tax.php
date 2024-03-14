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

VAPLoader::import('libraries.tax.rule');

/**
 * Encapsulates the details and the rules of a tax.
 *
 * @since 1.7
 */
class VAPTax extends JObject
{
	/**
	 * Internal property used to handle the rules, 
	 * which cannot be altered from outside.
	 *
	 * @var array
	 */
	protected $_rules = array();

	/**
	 * Class constructor.
	 *
	 * @param   mixed  $data  Either and associative array or another
	 *                        object to set the initial properties of the object.
	 */
	public function __construct($data = array())
	{
		// invoke parent to construct object
		parent::__construct($data);

		// get loaded rules, if any
		$rules = $this->get('rules', null);

		if ($rules)
		{
			// properly import them with dedicated method
			foreach ($rules as $rule)
			{
				// attach new rule
				$this->attachRule($rule);
			}

			// clear public property
			$this->set('rules', null);
		}
	}

	/**
	 * Proxy to access the internal rules.
	 *
	 * @return 	array
	 */
	public function getRules()
	{
		return $this->_rules;
	}

	/**
	 * Attaches a calculation rule to this object.
	 *
	 * @param 	mixed  $rule  Either a rule instance or an array/object.
	 *
	 * @return 	self   This object to support chaining. 
	 */
	public function attachRule($rule)
	{
		if (!$rule instanceof VAPTaxRule)
		{
			// create new instance
			$rule = VAPTaxRule::getInstance($rule);
		}

		// register rule
		$this->_rules[] = $rule;

		return $this;
	}

	/**
	 * Calculates the taxes for the specified amount.
	 *
	 * @param 	float   $total    The total amount to use.
	 * @param 	array   $options  An array of options.
	 *
	 * @return 	object  An object containing the resulting taxes.
	 */
	public function calculate($total, array $options = array())
	{
		// prepare object to return
		$result = new stdClass;
		$result->net       = (float) $total;
		$result->tax       = 0;
		$result->gross     = $result->net;
		$result->breakdown = array();

		// calculate costs only in case the given amount
		// is higher than 0, because it doesn't make sense
		// to calculate the taxes on a discount or for a
		// free item
		if ($result->net > 0)
		{
			// iterate supported rules
			foreach ($this->getRules() as $rule)
			{
				// let the rule manipulate the properties
				// of the resulting object
				$rule->calculate($total, $result, $options);
			}
		}

		return $result;
	}
}
