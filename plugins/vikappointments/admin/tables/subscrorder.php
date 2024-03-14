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
 * VikAppointments subscription order table.
 *
 * @since 1.7
 */
class VAPTableSubscrorder extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_subscr_order', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'sid';
		$this->_requiredFields[] = 'id_subscr';
	}

	/**
	 * Method to bind an associative array or object to the Table instance. This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   array|object  $src     An associative array or object to bind to the Table instance.
	 * @param   array|string  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  boolean  True on success.
	 */
	public function bind($src, $ignore = array())
	{
		$src = (array) $src;

		if (empty($src['id']))
		{
			$src['createdon'] = JDate::getInstance()->toSql();

			if (!isset($src['sid']))
			{
				// generate order key
				$src['sid'] = VikAppointments::generateSerialCode(16, 'subscr-sid');
			}
		}

		if (isset($src['total_cost']))
		{
			// total cost cannot be lower than 0
			$src['total_cost'] = max(array(0, (float) $src['total_cost']));
		}

		// stringify coupon code when passed as array/object
		if (isset($src['coupon']) && !is_string($src['coupon']))
		{
			$cpn = (array) $src['coupon'];
			$src['coupon'] = $cpn['code'] . ';;' . $cpn['percentot'] . ';;' . $cpn['value'];
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}
}
