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
 * VikAppointments PRO download view.
 *
 * @since 1.0
 */
class VikAppointmentsViewGetpro extends JViewVAP
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

		$app = JFactory::getApplication();

		// set the toolbar
		$this->addToolBar();

		VikAppointmentsLoader::import('update.changelog');
		VikAppointmentsLoader::import('update.license');

		// get version from request
		$version = $app->input->getString('version');

		if ($version)
		{
			// init HTTP transport
			$http = new JHttp();

			/**
			 * Always re-download the changelog of VikAppointments
			 * before upgrading the files to the PRO version.
			 *
			 * @since 1.1.3
			 */
			$url = 'https://vikwp.com/api/?task=products.changelog';

			// use version set in request because the constant and the
			// database, at this point, are always up to date
			$data = array(
				'sku'     => 'vap',
				'version' => $version,
			);

			// build headers
			$headers = array(
				/**
				 * Always bypass SSL validation while reaching our end-point.
				 *
				 * @since 1.2
				 */
				'sslverify' => false,
			);

			$response = $http->post($url, $data, $headers);

			if ($response->code == 200)
			{
				// save changelog on success
				VikAppointmentsChangelog::store(json_decode($response->body));
			}
		}

		$changelog = VikAppointmentsChangelog::build();
		$lic_key   = VikAppointmentsLicense::getKey();
		$lic_date  = VikAppointmentsLicense::getExpirationDate();
		$is_pro    = VikAppointmentsLicense::isPro();

		if (!$is_pro)
		{	
			$app->enqueueMessage(__('No valid and active license key found.', 'vikappointments'), 'error');
			$app->redirect('index.php?option=com_vikappointments&view=gotopro');
			exit;
		}
		
		$this->changelog  = $changelog;
		$this->licenseKey = $lic_key;
		
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
		JToolBarHelper::title(__('VikAppointments - Downloading Pro version', 'vikappointments'), 'vikappointments');
	}
}
