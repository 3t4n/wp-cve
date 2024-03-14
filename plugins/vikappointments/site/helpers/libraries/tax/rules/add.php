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
 * Tax rule percentage SUM calculator.
 *
 * @since 1.7
 */
class VAPTaxRuleAdd extends VAPTaxRule
{
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
		// check whether we should apply the calculation
		// to the base cost (1) or on cascade (2)
		$apply = (int) $this->get('apply');

		if ($apply == 2)
		{
			// apply to total gross
			$total = $data->gross;
		}

		// get specified tax amount
		$tax = (float) $this->get('amount', 0.0);

		// calculate resulting taxes
		$tax = round($total * $tax / 100, 2, PHP_ROUND_HALF_UP);

		// make sure the calculated taxes do not exceed
		// the specified threshold (TAX CAP)
		$cap = (float) $this->get('cap', 0);

		if ($cap > 0)
		{
			$tax = min(array($tax, $cap));
		}

		// update resulting data
		$data->tax   += $tax;
		$data->gross += $tax;

		// merge breakdown
		$this->addBreakdown($tax, $data->breakdown);
	}
}
