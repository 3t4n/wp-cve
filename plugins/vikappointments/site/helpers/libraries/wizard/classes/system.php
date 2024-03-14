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
 * Implement the wizard step used to setup the basic
 * settings of the global configuration.
 *
 * @since 1.7.1
 */
class VAPWizardStepSystem extends VAPWizardStep
{
	/**
	 * Returns the step title.
	 * Used as a very-short description.
	 *
	 * @return 	string  The step title.
	 */
	public function getTitle()
	{
		return JText::translate('VAPMENUCONFIG');
	}

	/**
	 * Returns an optional step icon.
	 *
	 * @return 	string  The step icon.
	 */
	public function getIcon()
	{
		return '<i class="fas fa-cog"></i>';
	}

	/**
	 * Return the group to which the step belongs.
	 *
	 * @return 	string  The group name.
	 */
	public function getGroup()
	{
		// belongs to GLOBAL group
		return JText::translate('VAPMENUTITLEHEADER3');
	}

	/**
	 * Implements the step execution.
	 *
	 * @param 	JRegistry  $data  The request data.
	 *
	 * @return 	boolean
	 */
	protected function doExecute($data)
	{
		$config = VAPFactory::getConfig();

		if ($currency = $data->get('currency'))
		{
			// get supported currencies
			$map = $this->getCurrencies();

			// make sure the currency exists
			if (!isset($map[$currency]))
			{
				return false;
			}

			$format = $map[$currency];

			// set currency parameters
			$config->set('currencyname', $currency);
			$config->set('currencysymb', $format['symbol']);
			$config->set('currsymbpos', $format['position']);
			$config->set('currdecimaldig', (int) $format['decimals']);
			$config->set('currthousandssep', $format['separator'] == '.' ? ',' : '.');
			$config->set('currdecimalsep', $format['separator']);
		}
		else
		{
			// set specified custom currency
			$config->set('currencyname', $data->get('currencyname'));
			$config->set('currencysymb', $data->get('currencysymb'));
		}

		$config->set('agencyname', $data->get('agencyname'));
		$config->set('adminemail', $data->get('adminemail'));

		if (!$config->get('senderemail'))
		{
			// set sender equals to admin e-mail if empty
			$config->set('senderemail', $data->get('adminemail'));
		}

		// set date/time format
		$config->set('dateformat', $data->get('dateformat'));
		$config->set('timeformat', $data->get('timeformat') ? 'H:i' : 'h:i A');

		return true;
	}

	/**
	 * Returns an associative array containing the most common currencies
	 * and the related formatting information.
	 *
	 * @return 	array
	 */
	public function getCurrencies()
	{
		return array(
			'EUR' => array(
				'currency'  => 'Euro',
				'symbol'    => '€',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => ',',
			),
			'USD' => array(
				'currency'  => 'US Dollar',
				'symbol'    => '$',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'GBP' => array(
				'currency'  => 'Pound Sterling',
				'symbol'    => '‎£',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'JPY' => array(
				'currency'  => 'Yen',
				'symbol'    => '¥',
				'position'  => 2,
				'decimals'  => 0,
				'separator' => '.',
			),
			'ARS' => array(
				'currency'  => 'Argentine Peso',
				'symbol'    => '$',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => ',',
			),
			'AUD' => array(
				'currency'  => 'Australian Dollar',
				'symbol'    => '$',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'BRL' => array(
				'currency'  => 'Brazilian Real',
				'symbol'    => 'R$',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => ',',
			),
			'CAD' => array(
				'currency'  => 'Canadian Dollar',
				'symbol'    => '$',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'CLP' => array(
				'currency'  => 'Chilean Peso',
				'symbol'    => '$',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'CNY' => array(
				'currency'  => 'Yuan Renminbi',
				'symbol'    => '¥',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'COP' => array(
				'currency'  => 'Colombian Peso',
				'symbol'    => '$',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'CZK' => array(
				'currency'  => 'Czech Koruna',
				'symbol'    => 'Kč',
				'position'  => 1,
				'decimals'  => 2,
				'separator' => ',',
			),
			'DKK' => array(
				'currency'  => 'Danish Krone',
				'symbol'    => 'kr.',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => ',',
			),
			'HKD' => array(
				'currency'  => 'Hong Kong Dollar',
				'symbol'    => 'HK$',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'HUF' => array(
				'currency'  => 'Hungarian Forint',
				'symbol'    => 'Ft',
				'position'  => 1,
				'decimals'  => 2,
				'separator' => ',',
			),
			'INR' => array(
				'currency'  => 'Indian Rupee',
				'symbol'    => '₹',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'ILS' => array(
				'currency'  => 'New Israeli Shekel',
				'symbol'    => '₪',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => ',',
			),
			'KRW' => array(
				'currency'  => 'Won',
				'symbol'    => '₩',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'MYR' => array(
				'currency'  => 'Malaysian Ringgit',
				'symbol'    => 'RM',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'MXN' => array(
				'currency'  => 'Mexican Peso',
				'symbol'    => '$',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'MAD' => array(
				'currency'  => 'Moroccan Dirham',
				'symbol'    => '.د.م.',
				'position'  => 1,
				'decimals'  => 2,
				'separator' => '.',
			),
			'NZD' => array(
				'currency'  => 'New Zealand Dollar',
				'symbol'    => '$',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'NOK' => array(
				'currency'  => 'Norwegian Krone',
				'symbol'    => 'kr',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'PHP' => array(
				'currency'  => 'Philippine Peso',
				'symbol'    => '₱',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'PLN' => array(
				'currency'  => 'Zloty',
				'symbol'    => 'zł',
				'position'  => 1,
				'decimals'  => 2,
				'separator' => ',',
			),
			'RUB' => array(
				'currency'  => 'Russian Ruble',
				'symbol'    => 'p.',
				'position'  => 1,
				'decimals'  => 2,
				'separator' => ',',
			),
			'SAR' => array(
				'currency'  => 'Saudi Riyal',
				'symbol'    => '﷼',
				'position'  => 1,
				'decimals'  => 2,
				'separator' => '.',
			),
			'SGD' => array(
				'currency'  => 'Singapore Dollar',
				'symbol'    => '$',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'ZAR' => array(
				'currency'  => 'Rand',
				'symbol'    => 'R',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'SEK' => array(
				'currency'  => 'Swedish Krona',
				'symbol'    => 'kr',
				'position'  => 1,
				'decimals'  => 2,
				'separator' => ',',
			),
			'CHF' => array(
				'currency'  => 'Swiss Franc',
				'symbol'    => 'fr.',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => ',',
			),
			'TWD' => array(
				'currency'  => 'New Taiwan Dollar',
				'symbol'    => '元',
				'position'  => 2,
				'decimals'  => 2,
				'separator' => '.',
			),
			'THB' => array(
				'currency'  => 'Baht',
				'symbol'    => '฿',
				'position'  => 1,
				'decimals'  => 2,
				'separator' => '.',
			),
			'TRY' => array(
				'currency'  => 'Turkish Lira',
				'symbol'    => '₺',
				'position'  => 1,
				'decimals'  => 2,
				'separator' => '.',
			),
			'VND' => array(
				'currency'  => 'Dong',
				'symbol'    => '₫',
				'position'  => 1,
				'decimals'  => 2,
				'separator' => ',',
			),
		);
	}
}
