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
 * Since we may start supporting FontAwesome 5, we should include within the registered font icon
 * the prefix to use (fas, far or fab).
 *
 * @since 1.7
 */
class VAPUpdateRulePaymentsIconsUpgrade1_7 extends VAPUpdateRule
{
	/**
	 * Method run during update process.
	 *
	 * @param 	mixed 	 $parent  The parent that calls this method.
	 *
	 * @return 	boolean  True on success, otherwise false to stop the flow.
	 */
	protected function run($parent)
	{
		$this->upgradeFontAwesomeIcons();

		return true;
	}

	/**
	 * Adjusts the icons of the payments.
	 *
	 * @return 	void
	 */
	private function upgradeFontAwesomeIcons()
	{
		// create lookup to assign the correct icon
		$lookup = array(
			'paypal'          => 'fab fa-paypal',
			'credit-card'     => 'far fa-credit-card',
			'credit-card-alt' => 'fas fa-credit-card',
			'cc-visa'         => 'fab fa-cc-visa',
			'cc-mastercard'   => 'fab fa-cc-mastercard',
			'cc-amex'         => 'fab fa-cc-amex',
			'cc-discover'     => 'fab fa-cc-discover',
			'cc-jcb'          => 'fab fa-cc-jcb',
			'cc-diners-club'  => 'fab fa-cc-diners-club',
			'cc-stripe'       => 'fab fa-cc-stripe',
			'eur'             => 'fas fa-euro-sign',
			'usd'             => 'fas fa-dollar-sign',
			'gbp'             => 'fas fa-pound-sign',
			'money'           => 'fas fa-money-bill',
		);

		$dbo = JFactory::getDbo();

		// fetch all the payments that own a FontAwesome icon
		$q = $dbo->getQuery(true)
			->select($dbo->qn(array('id', 'icon')))
			->from($dbo->qn('#__vikappointments_gpayments'))
			->where($dbo->qn('icon') . ' <> ' . $dbo->q(''))
			->where($dbo->qn('icontype') . ' = 1');

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows())
		{
			foreach ($dbo->loadObjectList() as $p)
			{
				// make sure the icon is supported
				if (!isset($lookup[$p->icon]))
				{
					continue;
				}

				// assign the new icon
				$p->icon = $lookup[$p->icon];
				// finalise the update
				$dbo->updateObject('#__vikappointments_gpayments', $p, 'id');
			}
		}
	}
}
