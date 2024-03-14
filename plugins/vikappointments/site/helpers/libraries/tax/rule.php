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
 * Tax rule calculator.
 *
 * @since 1.7
 */
class VAPTaxRule extends JObject
{
	/**
	 * Creates an instance for the specified rule operator.
	 *
	 * @param   mixed  $data  Either and associative array or another
	 *                        object to set the initial properties of the object.
	 */
	public static function getInstance($data)
	{
		$data = (array) $data;

		// make sure the operator is set
		if (!isset($data['operator']))
		{
			// raise an error because the tax rule is not applicable
			throw new Exception('Tax rule did not specify an operator', 400);
		}

		// try to load specific rule
		VAPLoader::import('libraries.tax.rules.' . $data['operator']);

		// create class name
		$classname = 'VAPTaxRule' . ucfirst($data['operator']);

		if (!class_exists($classname))
		{
			// no specific rule found, use the default one to
			// let external plugins implement their own operations	
			$classname = 'VAPTaxRule';
		}

		// create new rule instance
		return new $classname($data);
	}

	/**
	 * Calculates the taxes for the specified amount.
	 *
	 * @param 	float   $total    The total amount to use.
	 * @param 	object  $data     An object containing the tax details.
	 * @param 	array   $options  An array of options.
	 *
	 * @return 	void
	 */
	public function calculate($total, $data, array $options = array())
	{
		/**
		 * Default rule triggers a hook to let plugins apply the taxes
		 * by using custom rules.
		 *
		 * @param 	VAPTaxRule  $rule     The rule instance.
		 * @param 	float       $total    The total amount to use.
		 * @param 	object      $data     An object containing the tax details.
		 * @param 	array       $options  An array of options.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		VAPFactory::getEventDispatcher()->trigger('onCalculateTax', array($this, $total, $data, $options));
	}

	/**
	 * Split the taxes according to the configuration
	 * of the rule breakdowns.
	 *
	 * @param 	float  $tax    The resulting taxes.
	 * @param 	array  &$list  A list of breakdowns.
	 *
	 * @return 	void
	 */
	public function addBreakdown($tax, array &$list = array())
	{
		// get breakdown configuration
		$breakdown = $this->get('breakdown', null);

		if (is_string($breakdown))
		{
			// attempt to decode breakdowns list
			$breakdown = json_decode($breakdown);
		}

		if ($breakdown)
		{
			$total_split = 0;

			// calculate the total sum defined by the breakdowns so
			// that we can proportionally split the taxes according
			// to their percentages
			foreach ($breakdown as $bd)
			{
				$total_split += $bd->amount;
			}

			// split taxes found
			foreach ($breakdown as $bd)
			{
				$tmp = new stdClass;
				// recalculate amount proportionally
				$tmp->tax  = round($tax * $bd->amount / $total_split, 2, PHP_ROUND_HALF_UP);
				$tmp->name = $bd->name;

				$list[] = $tmp;
			}
		}
		else
		{
			// use current rule as breakdown
			$tmp = new stdClass;
			$tmp->tax  = $tax;
			$tmp->name = $this->get('name', '');

			$list[] = $tmp;
		}
	}
}
