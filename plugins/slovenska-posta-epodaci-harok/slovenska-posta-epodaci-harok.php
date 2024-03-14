<?PHP
/*
Plugin Name: Slovenská pošta - ePodací hárok
Plugin URI:  https://www.matejpodstrelenec.sk
Description: Plugin pre generovanie podacieho hárku určeného na import do webovej aplikácie slovenskej pošty. 
Version:     1.4.3
Author: Matej Podstrelenec
Author URI: https://www.matejpodstrelenec.sk/en/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: spirit-eph

Slovak post office - eph service
This plugin connects WooCommerce to eph service of Slovak post office.
It is designed mainly for local community and is not translated to English.
If you think that this plugin could be useful to you, let us know and we will proceed with translation.   
Team TheSpirit.studio

Slovenská pošta - ePodací hárok is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version

Slovenská pošta - ePodací hárok is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define('SPIRIT_EPH_PLUGIN_PATH', plugin_dir_path( __FILE__));
define('SPIRIT_EPH_VERSION', '1.4.3' );

include (SPIRIT_EPH_PLUGIN_PATH . "includes/functions.php");
include (SPIRIT_EPH_PLUGIN_PATH . "includes/woo-table.php");
include (SPIRIT_EPH_PLUGIN_PATH . "includes/woo-metabox.php");
include (SPIRIT_EPH_PLUGIN_PATH . "includes/woo-order-email.php");
include (SPIRIT_EPH_PLUGIN_PATH . "includes/posta_api.php");
include (SPIRIT_EPH_PLUGIN_PATH . 'includes/admin.php');
include (SPIRIT_EPH_PLUGIN_PATH . 'includes/export.php');
include (SPIRIT_EPH_PLUGIN_PATH . 'includes/podacie_cisla.php');
include (SPIRIT_EPH_PLUGIN_PATH . 'includes/bonus.php');
include (SPIRIT_EPH_PLUGIN_PATH . 'includes/settings.php');

//include (SPIRIT_EPH_PLUGIN_PATH . "templates/settings-page.php");

/*
* Plugin activation
*/
function tsseph_activate() {

	$tsseph_options= get_option( 'tsseph_options' );

	if (!isset($tsseph_options)) {
	
		//Prepare default option values
		$tsseph_options = array(
			'UserId' => '',
			'ApiKey' => '',
			'PaymentType' => ['cod'],
			'OdosielatelID' => '',
			'Meno' => '',
			'Organizacia' => '',
			'Ulica' => '',
			'Mesto' => '',
			'PSC' => '',
			'Krajina' => 'SK',
			'SMeno' => '',
			'SOrganizacia' => '',
			'SUlica' => '',
			'SMesto' => '',
			'SPSC' => '',
			'SKrajina' => 'SK',			
			'Telefon' => '',
			'Email' => '',
			'CisloUctu' => '',
			'TypEPH' => '',
			'SposobUhrady' => '',
			'Trieda' => '',
			'PodacieCisla' => array(
				'8' => array(),
				'14' => array()
			),
			'PredvolenyDruhZasielky' => 1,
			'RovnakaNavratova' => 1,
			'SendTrackingNo' => 1,
			'UloznaLehota' => 0,
			'LastLog' => array(
				'importSheet' => '',
				'getSheetStatus' => '',
				'getSheet' => ''
			)
		);

		update_option( 'tsseph_options', $tsseph_options );

		/*
			Bonus options
			
			$tsseph_bonus_options = array(
				'LicenseKey' => '',
				'LicenseStatus' => 0,
				'Enabled' => 0
			)
		*/
	}
}
register_activation_hook( __FILE__, 'tsseph_activate' );


