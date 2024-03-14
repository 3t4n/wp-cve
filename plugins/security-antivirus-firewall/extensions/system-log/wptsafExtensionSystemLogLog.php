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


class wptsafExtensionSystemLogLog extends wptsafAbstractLog{

	const MESSAGE_TYPE_SUCCESS 	= 'success';
	const MESSAGE_TYPE_INFO 	= 'info';
	const MESSAGE_TYPE_WARNING 	= 'warning';
	const MESSAGE_TYPE_DANGER 	= 'danger';

	public function __construct(wptsafAbstractExtension $extension){
		parent::__construct($extension);

		$this->table = WPTSAF_DB_PREFIX . 'system_log';
		$this->fieldList = array(
			'date_gmt',
			'username',
			'extension',
			'message',
		);
	}


	public function createTable(){
		global $wpdb;
		$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`date_gmt` int(11) NOT NULL,
				`username` varchar(50) NOT NULL,
				`extension` varchar(50) NOT NULL,
				`message` text NOT NULL,
				`type` varchar(10) NOT NULL,
				PRIMARY KEY (`id`),
				KEY `type` (`type`)
			)
			DEFAULT CHARACTER SET utf8
  			DEFAULT COLLATE utf8_general_ci"
		);
	}


	public function addSuccessMessage(wptsafAbstractExtension $extension, $message){
		return $this->insertRow(array(
			'extension' => $extension->getTitle(),
			'message' => (string)$message,
			'type' => self::MESSAGE_TYPE_SUCCESS
		));
	}


	public function addInfoMessage(wptsafAbstractExtension $extension, $message){
		return $this->insertRow(array(
			'extension' => $extension->getTitle(),
			'message' => (string)$message,
			'type' => self::MESSAGE_TYPE_INFO
		));
	}


	public function addWarningMessage(wptsafAbstractExtension $extension, $message){
		return $this->insertRow(array(
			'extension' => $extension->getTitle(),
			'message' => (string)$message,
			'type' => self::MESSAGE_TYPE_WARNING
		));
	}


	public function addDangerMessage(wptsafAbstractExtension $extension, $message){
		return $this->insertRow(array(
			'extension' => $extension->getTitle(),
			'message' => (string)$message,
			'type' => self::MESSAGE_TYPE_DANGER
		));
	}


	public function getMessagesByExtension(wptsafAbstractExtension $extension){
		global $wpdb;
		$result = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM {$this->table}
			WHERE extension = '%s'
			ORDER BY id DESC",
			$extension->getTitle()
		), ARRAY_A);

		foreach ($result as $rowKey => $row) {
			$result[$rowKey] = $this->prepareRow($row);
		}

		return $result;
	}

	public function prepareRow(array $row){
	  $row = parent::prepareRow($row);
	  if(strpos( $row['message'], '====dump===')!==false){
	  	$row['message'] = trim($row['message']);
	  	$row['message'] = str_replace("====dump===\n\n", "====dump===", $row['message']);
	  	
	  	$buttonstring = '
	  		<button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#collapseDump'.$row['id'].'" aria-expanded="false" aria-controls="collapseExample">
  				Details
			</button>
			<div class="collapse" id="collapseDump'.$row['id'].'">
  				<div class="well wpsaf_dump"><code>';
	  	$row['message'] = str_replace('====dump===', $buttonstring, $row['message']);
	  	$row['message'] .=	'</code></div>
			</div>';
		} else $row['message'] = '<div class="message">'.$row['message'].'</div>';
	  return $row;
 	}
}
