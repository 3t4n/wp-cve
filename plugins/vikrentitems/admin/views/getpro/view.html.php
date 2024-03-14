<?php
/**
 * @package     VikRentItems
 * @subpackage  com_vikrentitems
 * @author      Alessio Gaggii - e4j - Extensionsforjoomla.com
 * @copyright   Copyright (C) 2018 e4j - Extensionsforjoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @link        https://vikwp.com
 */

defined('ABSPATH') or die('No script kiddies please!');

/**
 * @wponly this View is only for WP
 */

// import Joomla view library
jimport('joomla.application.component.view');

class VikRentItemsViewGetpro extends JViewVikRentItems {
	
	function display($tpl = null) {
		// Set the toolbar
		$this->addToolBar();

		VikRentItemsLoader::import('update.changelog');
		VikRentItemsLoader::import('update.license');

		$version = VikRequest::getString('version', '', 'request');
		if (!empty($version)) {
			/**
			 * Download Changelog
			 * 
			 * @since 	1.0.3
			 */
			$http = new JHttp;

			$url = 'https://vikwp.com/api/?task=products.changelog';

			$data = array(
				'sku' 		=> 'vri',
				'version' 	=> $version,
			);

			$response = $http->post($url, $data);

			if ($response->code == 200) {
				VikRentItemsChangelog::store(json_decode($response->body));
			}
		}

		$changelog = VikRentItemsChangelog::build();
		$lic_key = VikRentItemsLicense::getKey();
		$lic_date = VikRentItemsLicense::getExpirationDate();
		$is_pro = VikRentItemsLicense::isPro();

		if (!$is_pro) {
			VikError::raiseWarning('', JText::translate('VRINOPROERROR'));
			JFactory::getApplication()->redirect('index.php?option=com_vikrentitems&view=gotopro');
			exit;
		}
		
		$this->changelog = &$changelog;
		$this->lic_key = &$lic_key;
		$this->lic_date = &$lic_date;
		$this->is_pro = &$is_pro;
		
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Sets the toolbar
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::translate('VRIMAINGETPROTITLE'), 'vikrentitems');
	}

}
