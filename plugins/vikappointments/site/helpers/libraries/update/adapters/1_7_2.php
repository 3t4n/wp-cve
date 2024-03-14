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
 * Update adapter for com_vikappointments 1.7.2 version.
 *
 * NOTE. do not call exit() or die() because the update won't be finalised correctly.
 * Return false instead to stop in anytime the flow without errors.
 *
 * @since 1.7.2
 */
class VAPUpdateAdapter1_7_2 extends VAPUpdateAdapter
{
	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		JModelLegacy::addIncludePath(VAPADMIN . DIRECTORY_SEPARATOR . 'models');

		// setup after update rules
		$this->attachRule('afterupdate', 'fix_modules_update_error');
	}
}
