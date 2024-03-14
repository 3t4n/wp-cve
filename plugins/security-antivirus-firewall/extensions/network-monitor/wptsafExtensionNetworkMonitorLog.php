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

class wptsafExtensionNetworkMonitorLog extends wptsafAbstractLog{

	public function __construct(wptsafAbstractExtension $extension)
	{
		parent::__construct($extension);
		
		global $wpdb;

		$this->table = WPTSAF_DB_PREFIX . 'network_monitor_log';
		$this->fieldList = array(
			'ip',
			'date_gmt',
			'client_data'
		);
	}


	public function getRow($id){
		global $wpdb;
		$row = $wpdb->get_row($wpdb->prepare(
			"SELECT * FROM {$this->table} where id = '%d'",
			absint($id)
		), ARRAY_A);

		if ($wpdb->last_error) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				$this->extension,
				__('Database query error', 'wptsaf_security')
				. "\n\nError: $wpdb->last_error"
				. "\n\nQuery: $wpdb->last_query"
			);
			return new WP_Error('wptsaf_security', __('Database query error', 'wptsaf_security'));
		}
		if (!$row) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				$this->extension,
				sprintf(__('Could not find row by id #%d', 'wptsaf_security'), absint($id))
			);
			return new WP_Error(
				'wptsaf_security',
				sprintf(__('Could not find row by id #%d', 'wptsaf_security'), absint($id))
			);
		}

		$row['lock_count'] = $this->getLockCount($row['ip']);

		return $this->prepareRow($row);
	}


	public function getLockCount($ip){
		global $wpdb;
		$result = $wpdb->get_var($wpdb->prepare(
			"SELECT count(*) as count FROM {$this->table} WHERE ip = '%s'",
			preg_replace('/[^0-9\.]/', '', $ip)
		));

		return $result;
	}


	public function createTable(){
		global $wpdb;
		$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `{$this->table}` (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				 `date_gmt` int(11) NOT NULL,
				 `ip` varchar(15) NOT NULL,
				 `client_data` text NOT NULL,
				 PRIMARY KEY (`id`)
			)
			DEFAULT CHARACTER SET utf8
  			DEFAULT COLLATE utf8_general_ci"
		);
	}
}
