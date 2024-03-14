<?php
#[AllowDynamicProperties]
class installerDbUpdaterGmp {
	static public function runUpdate() {
		self::update_201();
		self::update_202();
	}
	public static function update_201() {
		if(!dbGmp::exist('gmp_modules', 'code', 'csv')) {
			global $wpdb;
			$tableName = $wpdb->prefix . "gmp_modules";
			$wpdb->insert($tableName, array(
					'code' => 'csv',
					'active' => 1,
					'type_id' => 1,
					'params' => '',
					'has_tab' => 0,
					'label' => 'csv',
					'description' => 'csv',
			));
		}
	}
	public static function update_202() {
		if(!dbGmp::exist('gmp_modules', 'code', 'maps_widget')) {
			global $wpdb;
			$tableName = $wpdb->prefix . "gmp_modules";
			$wpdb->insert($tableName, array(
					'code' => 'gmap_widget',
					'active' => 1,
					'type_id' => 1,
					'params' => '',
					'has_tab' => 0,
					'label' => 'gmap_widget',
					'description' => 'gmap_widget',
			));
		}
	}
}
