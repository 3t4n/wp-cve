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

class wptsafExtensionLoginBruteForceWidget extends wptsafAbstractExtensionWidget{
	
	public function content(){
		$view = new wptsafView();
		return $view->content(
			$this->extension->getExtensionDir() . 'template/widget.php',
			array(
				'title' => $this->extension->getTitle(),
				'description' => $this->extension->getDescription(),
				'isEnabled' => $this->extension->isEnabled(),
				'logHeader' => array(
					'date_gmt' 	=> __('Date', 'wptsaf_security'),
					'ip' 		=> __('IP address', 'wptsaf_security'),
					'username' 	=> __('Username', 'wptsaf_security'),
					'status' 	=> __('Status', 'wptsaf_security')
				),
				'rows' => $this->extension->getLog()->getRows(10)
			)
		);
	}
}
