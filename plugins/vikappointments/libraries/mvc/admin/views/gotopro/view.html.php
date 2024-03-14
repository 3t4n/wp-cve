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
 * VikAppointments validate PRO view.
 *
 * @since 1.0
 */
class VikAppointmentsViewgotopro extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		JHtml::fetch(
			'stylesheet',
			VIKAPPOINTMENTS_CORE_MEDIA_URI . 'css/license.css',
			array('version' => VIKAPPOINTMENTS_SOFTWARE_VERSION),
			array('id' => 'vap-license-style')
		);

		// Set the toolbar
		$this->addToolBar();

		VikAppointmentsLoader::import('update.license');

		$lic_key  = VikAppointmentsLicense::getKey();
		$lic_date = VikAppointmentsLicense::getExpirationDate();
		$is_pro   = VikAppointmentsLicense::isPro();

		if ($is_pro) 
		{
			$tpl = 'pro';
		}
		
		$this->licenseKey  = $lic_key;
		$this->licenseDate = $lic_date;
		$this->isPro       = $is_pro;
		
		// display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar()
	{
		JToolBarHelper::title(__('VikAppointments - Upgrade to Pro', 'vikappointments'), 'vikappointments');
	}
}
