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

class wptsafExtensionReport extends wptsafAbstractExtension{

	protected static $instance;

	public function __construct(){
		$this->name = 'report';
		$this->title = __('Security Report', 'wptsaf_security');
		$this->description = __("Advanced security reporting system send your daily, weekly or monthly email reports with all attacks and protections details. Every your system activity will be logged and reported by email to keep you posted. In module settings you can select reporting period, time when you wish to receive reports, title for report.", 'wptsaf_security');
		
		parent::__construct();
	}

	public static function getInstance(){
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function getSettings(){
		return parent::getSettings();
	}
}
