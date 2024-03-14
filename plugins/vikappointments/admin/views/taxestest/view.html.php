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
 * VikAppointments taxes test view.
 *
 * @since 1.7
 */
class VikAppointmentsViewtaxestest extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		$app 	= JFactory::getApplication();
		$input 	= $app->input;
		$dbo 	= JFactory::getDbo();

		$args = array();
		$args['id_tax']  = $app->getUserState($this->getPoolName() . '.id_tax', 0);
		$args['amount']  = $app->getUserState($this->getPoolName() . '.amount', 0);
		$args['langtag'] = $app->getUserState($this->getPoolName() . '.langtag', null);
		
		$this->args = $args;
		
		// display the template (default.php)
		parent::display($tpl);
	}
}
