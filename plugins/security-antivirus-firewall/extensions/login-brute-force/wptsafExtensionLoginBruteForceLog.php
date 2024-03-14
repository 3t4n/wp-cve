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

class wptsafExtensionLoginBruteForceLog extends wptsafAbstractLog{
	const LOGIN_STATUS_SUCCESS = 'success';
	const LOGIN_STATUS_FAILED = 'failed';

	public function __construct(wptsafAbstractExtension $extension){
		parent::__construct($extension);

		$this->table = WPTSAF_DB_PREFIX . 'login_brute_force_log';
		$this->fieldList = array(
			'ip',
			'date_gmt',
			'username',
			'status',
			'description',
			'client_data',
		);
	}

	public function getLoginsByIp($ip, $dateGmtTo){
		global $wpdb;
		/*return $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM {$this->table} "
			. "WHERE ip = '%s' and date_gmt >= %d",
			preg_replace('/[^0-9\.]/', '', $ip),
			intval($dateGmtTo)
		), ARRAY_A);*/

		$where = array(
			array('ip', '=', preg_replace('/[^0-9\.]/', '', $ip)),
			array('date_gmt', '>=', intval($dateGmtTo))
		);
		return $this->getRows(WPTSAF_LOG_LIMIT, 0, 'DESC', 'id', $where);
	}

	public function createTable(){
		global $wpdb;
		$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `{$this->table}` (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				 `date_gmt` int(11) NOT NULL,
				 `ip` varchar(15) NOT NULL,
				 `username` varchar(50) NOT NULL,
				 `status` varchar(10) NOT NULL,
				 `description` text NOT NULL,
				 `client_data` text NOT NULL,
				 PRIMARY KEY (`id`),
				 KEY `ip` (`ip`,`status`)
			)
			DEFAULT CHARACTER SET utf8
  			DEFAULT COLLATE utf8_general_ci"
		);
	}

	public function prepareRow(array $row){
	  $row = parent::prepareRow($row);
	  if(isset($row['client_data'])){
	  	if( isset($row['client_data']['ip']) ) unset($row['client_data']['ip']);
	  	if( isset($row['client_data']['username']) ) unset($row['client_data']['username']);
	  }
	  if( isset($row['description']) && strpos( $row['description'], __('Lost your password?') )!==false ){
		  $row['description'] = str_replace( __('Lost your password?'), '', $row['description']);
	  }
	  
	  return $row;
 	}
}
