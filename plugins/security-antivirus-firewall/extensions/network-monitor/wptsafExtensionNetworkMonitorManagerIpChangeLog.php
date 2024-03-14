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


class wptsafExtensionNetworkMonitorManagerIpChangeLog extends wptsafAbstractLog{

	public function __construct(wptsafAbstractExtension $extension){
		parent::__construct($extension);

		$this->table = WPTSAF_DB_PREFIX . 'network_monitor_manager_ip_change_log';
		$this->fieldList = array(
			'manager_ip_id',
			'date_gmt',
			'comment',
		);
	}

	public function addChange($ipId, $comment = '', $data = array()){
		$data = $this->formatRow($data, '  ');
		$comment .= (empty($comment) ? '' : "\n\n ")
				 . __('Changes:', 'wptsaf_security') . "\n"
			     . $data;

		return $this->insertRow(array(
			'manager_ip_id' => $ipId,
			'comment' => $comment
		));
	}

	public function getRowsByManagerIpId($id){
		global $wpdb;
		$rows = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM {$this->table} where manager_ip_id = '%d' ORDER BY id DESC",
			absint($id)
		), ARRAY_A);

		foreach ($rows as $rowKey => $row) {
			$rows[$rowKey] = $this->prepareRow($row);
		}

		return $rows;
	}

	public function prepareRow(array $row){
		$row['comment'] = trim($row['comment']);
		
		return parent::prepareRow($row);
	}


	public function rotate(){}

	public function createTable(){
		global $wpdb;
		$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`date_gmt` int(11) NOT NULL,
				`manager_ip_id` int(11) NOT NULL,
				`comment` tinytext NOT NULL,
				PRIMARY KEY (`id`)
			)
			DEFAULT CHARACTER SET utf8
  			DEFAULT COLLATE utf8_general_ci"
		);
	}
}
