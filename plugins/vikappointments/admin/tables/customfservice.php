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
 * VikAppointments custom field-service relation table.
 *
 * @since 1.7
 */
class VAPTableCustomfservice extends JTableVAP
{
	/**
	 * Class constructor.
	 *
	 * @param 	object 	$db  The database driver instance.
	 */
	public function __construct($db)
	{
		parent::__construct('#__vikappointments_cf_service_assoc', 'id', $db);

		// register required fields
		$this->_requiredFields[] = 'id_field';
		$this->_requiredFields[] = 'id_service';

		// set relation columns
		$this->_tbl_assoc_pk = 'id_field';
		$this->_tbl_assoc_fk = 'id_service';
	}
}
