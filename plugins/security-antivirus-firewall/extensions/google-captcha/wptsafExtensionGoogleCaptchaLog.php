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

class wptsafExtensionGoogleCaptchaLog extends wptsafAbstractLog{
	const STATUS_SUCCESS = 'success';
	const STATUS_FAILED = 'failed';


	public function __construct(wptsafAbstractExtension $extension){
		parent::__construct($extension);

		$this->table = WPTSAF_DB_PREFIX . 'google_captcha_log';
		$this->fieldList = array(
			'ip',
			'date_gmt',
			'user_id',
			'status',
			'reason',
			'client_data',
		);
	}


	public function createTable(){
		global $wpdb;
		$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `{$this->table}` (
				 `id` int(11) NOT NULL AUTO_INCREMENT,
				 `ip` varchar(15) NOT NULL,
				 `date_gmt` int(11) NOT NULL,
				 `user_id` int(11) NOT NULL,
				 `status` VARCHAR(10) NOT NULL,
				 `reason` VARCHAR(30) NOT NULL,
				 `client_data` text NOT NULL,
				 PRIMARY KEY (`id`)
			)
			DEFAULT CHARACTER SET utf8
  			DEFAULT COLLATE utf8_general_ci"
		);
	}
}
