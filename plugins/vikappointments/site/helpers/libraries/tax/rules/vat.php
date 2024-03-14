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
 * Tax rule VAT calculator (included taxes).
 *
 * @since 1.7
 */
class VAPTaxRuleVAT extends VAPTaxRule
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
		// get specified tax amount
		$tax = (float) $this->get('amount', 0.0);

		// DO NOT take care of "apply" parameter, because
		// inclusive taxes can be calculated only on the
		// base total gross

		// calculate resulting VAT
		$tax = round($data->gross - $data->gross / (1 + $tax / 100), 2, PHP_ROUND_HALF_UP);

		// make sure the calculated taxes do not exceed
		// the specified threshold (TAX CAP)
		$cap = (float) $this->get('cap', 0);

		if ($cap > 0)
		{
			$tax = min(array($tax, $cap));
		}

		// sub to taxes
		$data->tax += $tax;
		// subtract from net
		$data->net -= $tax;

		// merge breakdown
		$this->addBreakdown($tax, $data->breakdown);
	}
}
