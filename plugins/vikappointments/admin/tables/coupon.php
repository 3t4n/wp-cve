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
 * VikAppointments coupon table.
 *
 * @since 1.7
 */
class VAPTableCoupon extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_coupon', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'code';
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

		// auto-generate the coupon code if not specified
		if ((empty($src['id']) || isset($src['code'])) && empty($src['code']))
		{
			$src['code'] = VikAppointments::generateSerialCode(12, 'coupon');
		}

		if (!empty($src['code']))
		{
			/**
			 * Sanitize coupon code. Only the following characters are accepted:
			 * - letters (a-z and A-Z)
			 * - numbers (0-9)
			 * - hyphens (-) and underscores (_)
			 *
			 * @since 1.7
			 */
			$src['code'] = preg_replace("/[^a-zA-Z0-9\-_]+/", '', $src['code']);
		}

		// make sure the type is an accepted value
		if (isset($src['type']) && !in_array($src['type'], array(1, 2)))
		{
			// set the default PERMANENT type
			$src['type'] = 1;
		}

		// make sure the percentot is an accepted value
		if (isset($src['type']) && !in_array($src['percentot'], array(1, 2)))
		{
			// set the default TOTAL type
			$src['percentot'] = 2;
		}

		// only positive amount
		if (isset($src['value']))
		{
			$src['value'] = abs($src['value']);
		}

		// only positive amount
		if (isset($src['mincost']))
		{
			$src['mincost'] = abs($src['mincost']);
		}

		// make sure the start date is not higher than the end date
		if (isset($src['dstart']) && isset($src['dend']) && $src['dstart'] > $src['dend'] && !VAPDateHelper::isNull($src['dend']))
		{
			// swap the specified dates
			$app           = $src['dstart'];
			$src['dstart'] = $src['dend'];
			$src['dend']   = $app;
		}

		// bind the details before save
		return parent::bind($src, $ignore);
	}

	/**
	 * Method to perform sanity checks on the Table instance properties to
	 * ensure they are safe to store in the database.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 */
	public function check()
	{
		// check integrity using parent
		if (!parent::check())
		{
			return false;
		}

		// in case of coupon code, make sure we are not going to create a duplicate
		if (!empty($this->code))
		{
			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true);

			$q->select(1);
			$q->from($dbo->qn($this->getTableName()));
			$q->where($dbo->qn('id') . ' <> ' . (int) $this->id);
			$q->where($dbo->qn('code') . ' = ' . $dbo->q($this->code));
			
			$dbo->setQuery($q, 0, 1);
			$dbo->execute();
			
			if ($dbo->getNumRows() > 0)
			{
				// a coupon already exists with the given code
				$this->setError(JText::translate('VAPCOUPONDUPLICATEERR'));

				return false;
			}
		}

		return true;
	}
}
