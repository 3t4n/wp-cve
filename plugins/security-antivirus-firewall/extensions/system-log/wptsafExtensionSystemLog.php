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


class wptsafExtensionSystemLog extends wptsafAbstractExtension{
	
	protected static $instance;

	public function __construct(){
		$this->name = 'system-log';
		$this->title = __('Live System Monitor', 'wptsaf_security');
		$this->description = __("Security system monitor show you all events related with all security modules. You can simple control everything what's happening in your system. System monitor collect details of all changes in system including another security modules. You can see detailed log of every single change. You always know what's happening with your website you wouldn't miss any illegal activity.", 'wptsaf_security');
		parent::__construct();
	}

	public static function getInstance(){
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(){
		add_action('wptsaf_security_extension_settings_save', 	array($this, 'actionSettingsSave'), 10, 1);
		add_action('wptsaf_security_extension_log_rotate', 		array($this, 'actionLogRotate'), 10, 1);
		add_action('wptsaf_security_extension_log_clear', 		array($this, 'actionLogClear'), 10, 1);
		add_action('wptsaf_security_file_change_scan_run', 		array($this, 'actionFileChangeScanRun'), 10, 1);
	}

	public function isEnabled(){
		return true;
	}

	public function getLog(){
		return parent::getLog();
	}

	public function actionSettingsSave(wptsafAbstractExtension $extension){
		$this->log->addInfoMessage(
			$extension,
			"Save settings\n\n".
			"====dump===". 
			$this->log->formatRow($extension->getSettings()->get())
		);

		if ($cron = $extension->getCron()) {
			$extension->isEnabled() ? $cron->initSchedule() : $cron->clearSchedule();
		}
	}

	public function actionLogRotate(wptsafAbstractExtension $extension){
		$this->log->addInfoMessage($extension, __('Log rotate', 'wptsaf_security'));
	}

	public function actionLogClear(wptsafAbstractExtension $extension){
		$this->log->addInfoMessage($extension, __('Log clear', 'wptsaf_security'));
	}

	public function actionFileChangeScanRun(wptsafAbstractExtension $extension){
		$this->log->addInfoMessage($extension, __('Run scan file change.', 'wptsaf_security'));
	}
}
