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

class wptsafSecuritySettings extends wptsafSettings{
	
	public function __construct(wptsafAbstractExtension $extension){
		$this->optionKey = WPTSAF_OPTION_KEY_PREFIX . 'settings';
		$this->defaultOptions = array(
			'log_rotation' => 30,
			'log_rotation_time' => array(
				'h' => 2,
				'm' => 0
			),
			'notification_emails' => array(
				get_site_option('admin_email')
			)
		);

		parent::__construct($extension);
	}
}
