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
 * VikAppointments service-restriction relation table.
 *
 * @since 1.7
 */
class VAPTableSerrestrassoc extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_ser_restr_assoc', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'id_restriction';
		$this->_requiredFields[] = 'id_service';

		// set relation columns
		$this->_tbl_assoc_pk = 'id_restriction';
		$this->_tbl_assoc_fk = 'id_service';
	}
}
