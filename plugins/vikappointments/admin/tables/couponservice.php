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
 * VikAppointments coupon-service relation table.
 *
 * @since 1.7
 */
class VAPTableCouponservice extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_coupon_service_assoc', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'id_coupon';
		$this->_requiredFields[] = 'id_service';

		// set relation columns
		$this->_tbl_assoc_pk = 'id_coupon';
		$this->_tbl_assoc_fk = 'id_service';
	}
}
