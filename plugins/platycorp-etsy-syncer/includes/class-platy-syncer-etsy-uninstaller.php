<?php

use platy\etsy\EtsyDataService;
use platy\etsy\PlatysService;
/**
 * Fired during plugin deactivation
 *
 * @link       inon_kaplan
 * @since      1.0.0
 *
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/includes
 * @author     Inon Kaplan <inonkp@gmail.com>
 */
class Platy_Syncer_Etsy_Uninstaller {

	function __construct(){
		include_once dirname(__FILE__) . "/class-platy-syncer-etsy.php";
		include_once dirname(__FILE__) . "/../admin/class-platy-syncer-etsy-admin.php";
		include_once dirname(__FILE__) . "/data/platys/class-platys-service.php";
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function truncate_all($delete = false) {
		
		$this->truncate(Platy_Syncer_Etsy::SHOP_TABLE_NAME, $delete);
		$this->truncate(Platy_Syncer_Etsy::TEMPLATES_TABLE_NAME, $delete);

		$this->truncate(Platy_Syncer_Etsy::TEMPLATES_META_TABLE_NAME, $delete);

		$this->truncate(Platy_Syncer_Etsy::PRODUCT_TABLE_NAME, $delete);
		$this->truncate(Platy_Syncer_Etsy::PRODUCT_META_TABLE_NAME, $delete);

		$this->truncate(Platy_Syncer_Etsy::CONNECTIONS_TABLE_NAME, $delete);
		$this->truncate(Platy_Syncer_Etsy::OPTIONS_TABLE_NAME, $delete);
		$this->truncate(Platy_Syncer_Etsy::LOG_TABLE_NAME, $delete);
		$this->truncate(Platy_Syncer_Etsy::PRODUCT_ATTRIBUTES_TABLE_NAME, $delete);
		$this->delete_current_shop();
		$this->delete_platy_version();
	}

	public function truncate($tblname, $delete = false){
		global $table_prefix, $wpdb;
		$tblname = $table_prefix . $tblname;
		$wpdb->query( "TRUNCATE TABLE $tblname" );
		if($delete){
			$wpdb->query( "DROP TABLE $tblname" );
		}
	}

	public function remove_license(){
		delete_option( PlatysService::PRO_OPTION_KEY );

	}

	public function delete_current_shop(){
		delete_option( "platy_etsy_default_etsy_shop" );

	}

	public function delete_platy_version(){
		delete_option( "platy_syncer_etsy_version" );
	}

}
