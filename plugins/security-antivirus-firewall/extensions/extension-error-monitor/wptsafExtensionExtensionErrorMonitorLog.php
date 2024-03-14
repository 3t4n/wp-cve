<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

class wptsafExtensionExtensionErrorMonitorLog extends wptsafAbstractLog{

	public function __construct(wptsafAbstractExtension $extension){
		parent::__construct($extension);

		$this->table = WPTSAF_DB_PREFIX . 'extension_error_monitor_log';
		$this->fieldList = array(
			'date_gmt',
			'extension',
			'is_show',
			'hide_by'
		);
	}

	public function getShowMessages(){
		global $wpdb;
		$result = $wpdb->get_results(
			"SELECT * FROM {$this->table} WHERE is_show = 1 ORDER BY id DESC",
		ARRAY_A);

		foreach ($result as $rowKey => $row) {
			$result[$rowKey] = $this->prepareRow($row);
		}

		return $result;
	}

	public function createTable(){
		global $wpdb;
		$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `{$this->table}` (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				 `date_gmt` int(11) NOT NULL,
				 `extension` varchar(50) NOT NULL,
				 `is_show` tinyint(1) NOT NULL,
				 `hidden_by` varchar(50) NOT NULL,
				 PRIMARY KEY (`id`)
			)
			DEFAULT CHARACTER SET utf8
  			DEFAULT COLLATE utf8_general_ci"
		);
	}
}
