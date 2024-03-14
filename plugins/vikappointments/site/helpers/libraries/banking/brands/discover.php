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
 * This class is used to handle Discover credit cards.
 *
 * @since  1.6
 */
class CCDiscover extends CreditCard
{
	/**
	 * Checks if the credit card number is valid.
	 * The card number is valid when its length is equals to 16.
	 *
	 * @return 	boolean 	True if the card number is valid.
	 *
	 * @uses 	getCardNumber()
	 * @uses 	getCardNumberDigits()
	 */
	public function isCardNumberValid()
	{
		return strlen($this->getCardNumber()) == $this->getCardNumberDigits();
	}

	/**
	 * Gets the credit card number digits count.
	 *
	 * @return 	integer  Return the digits count (16).
	 */
	public function getCardNumberDigits()
	{
		return 16;
	}

	/**
	 * Formats the credit card number to be more human-readable.
	 * e.g. 6565 0000 0000 0000
	 *
	 * @return 	string 	The formatted card number.
	 *
	 * @uses 	getCardNumber()
	 */
	public function formatCardNumber()
	{
		$cc = $this->getCardNumber();

		return substr($cc, 0, 4) . ' ' . substr($cc, 4, 4) . ' ' . substr($cc, 8, 4) . ' ' . substr($cc, 12, 4);
	}

	/**
	 * Gets a masked version of the credit card for privacy.
	 * e.g. **** **** **** 0000
	 * e.g. 6565 0000 0000 ****
	 *
	 * @return 	array 	A list containing 2 different masked versions of card number.
	 *
	 * @uses 	getCardNumber()
	 */
	public function getMaskedCardNumber()
	{
		$cc = $this->getCardNumber();

		return array(
			'**** **** **** ' . substr($cc, 12, 4),
			substr($cc, 0, 4) . ' ' . substr($cc, 4, 4) . ' ' . substr($cc, 8, 4) . ' ****',
		);
	}

	/**
	 * Gets the Discover alias.
	 *
	 * @return 	string 	The alias of the credit card brand (discover).
	 */
	public function getBrandAlias()
	{
		return CreditCard::DISCOVER;
	}

	/**
	 * Gets the name of the credit card brand.
	 *
	 * @return 	string 	The name of the credit card brand (Discover).
	 */
	public function getBrandName()
	{
		return 'Discover';
	}
}
