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

class wptsafExtension404DetectionLog extends wptsafAbstractLog{

	public function __construct(wptsafAbstractExtension $extension){
		parent::__construct($extension);

		$this->table = WPTSAF_DB_PREFIX . '404_detection_log';
		$this->fieldList = array(
			'date_gmt',
			'uri',
			'ip',
			'client_data',
		);
	}

	function createTable(){
		global $wpdb;
		$wpdb->query(
			"CREATE TABLE IF NOT EXISTS  `{$this->table}` (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				 `date_gmt` int(11) NOT NULL,
				 `uri` varchar(500) NOT NULL,
				 `ip` varchar(15) NOT NULL,
				 `type` varchar(10) NOT NULL,
				 `client_data` text NOT NULL,
				 PRIMARY KEY (`id`)
			)
			DEFAULT CHARACTER SET utf8
  			DEFAULT COLLATE utf8_general_ci"
		);
	}

	public function prepareRow(array $row){
	  $row = parent::prepareRow($row);
	  if(isset($row['client_data'])){
	  	if( isset($row['client_data']['REQUEST_URI']) ) unset($row['client_data']['REQUEST_URI']);
	  	if( isset($row['client_data']['ip']) ) unset($row['client_data']['ip']);	
	  }
	  //$row['ip'] = wpToolsSAFHelperClass::getIpInfo( $row['ip'], 1 );
	  return $row;
 	}
}
