<?php
/**
 * @package     VikAppointments
 * @subpackage  mod_vikappointments_search
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2023 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access to this file
defined('ABSPATH') or die('No script kiddies please!');

jimport('adapter.module.widget');

/**
 * One-Page Booking Module implementation for WP.
 *
 * @see 	JWidget
 * @since 	1.2.5
 */
class ModVikappointmentsOnepageBooking_Widget extends JWidget
{
	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		// attach the absolute path of the module folder
		parent::__construct(dirname(__FILE__));
	}
}
