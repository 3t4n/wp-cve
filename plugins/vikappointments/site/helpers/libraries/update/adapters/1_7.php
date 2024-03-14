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
 * Update adapter for com_vikappointments 1.7 version.
 *
 * NOTE. do not call exit() or die() because the update won't be finalised correctly.
 * Return false instead to stop in anytime the flow without errors.
 *
 * @since 1.7
 */
class VAPUpdateAdapter1_7 extends VAPUpdateAdapter
{
	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		JModelLegacy::addIncludePath(VAPADMIN . DIRECTORY_SEPARATOR . 'models');

		// setup update rules
		$this->attachRule('update', 'custom_fields_mapper');
		$this->attachRule('update', 'payments_icons_upgrade');
		$this->attachRule('update', 'invoice_settings_adapter');

		// setup after update rules
		$this->attachRule('afterupdate', 'closing_periods_fixer');
		$this->attachRule('afterupdate', 'service_employee_overrides');
		$this->attachRule('afterupdate', 'status_codes_mapper');
		$this->attachRule('afterupdate', 'user_notes_migrator');
		$this->attachRule('afterupdate', 'working_days_adapter');
		$this->attachRule('afterupdate', 'option_var_translations');
		$this->attachRule('afterupdate', 'timestamp_datetime_converter');
		$this->attachRule('afterupdate', 'register_invoices_data');
		$this->attachRule('afterupdate', 'define_default_tax');
		$this->attachRule('afterupdate', 'generate_random_colors');
	}
}
