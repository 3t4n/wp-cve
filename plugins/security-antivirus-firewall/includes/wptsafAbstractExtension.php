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

abstract class wptsafAbstractExtension{
	protected $name;
	protected $title;
	protected $description;
	protected $settings;
	protected $log;
	protected $cron;

	protected function __construct(){
		$className = get_called_class();
		$settingsClassName = "{$className}Settings";
		$logClassName = "{$className}Log";
		$cronClassName = "{$className}Cron";

		if (class_exists($settingsClassName)) {
			$this->settings = new $settingsClassName($this);
		}
		if (class_exists($logClassName)) {
			$this->log = new $logClassName($this);
		}
		if (class_exists($cronClassName)) {
			$this->cron = new $cronClassName($this);
		}

		if ($this->isEnabled()) {
			$this->init();
		}
	}

	protected function __clone(){}

	public static function getInstance(){
		throw new Exception('Not implemented method');
	}

	protected function init(){}

	public function getName(){
		return $this->name;
	}

	public function getTitle(){
		return $this->title;
	}

	public function getDescription(){
		return $this->description;
	}

	public function isEnabled(){
		return $this->settings->get('is_enabled');
	}

	public function getSettings(){
		return $this->settings;
	}

	public function getLog(){
		return $this->log;
	}

	public function getCron(){
		return $this->cron;
	}

	public function getExtensionDir(){
		return 'extensions/' . $this->name . '/';
	}

	public function getLogRotationFrequency(){
		$logRotation = $this->getSettings()->get('log_rotation');
		if (-1 == $logRotation || null === $logRotation) {
			$logRotation = wptsafSecurity::getInstance()->getSettings()->get('log_rotation');
		}
		return 0 == $logRotation ? false : "{$logRotation}D";
	}

	public function createWidget(){
		$className = get_called_class();
		$widgetClassName = "{$className}Widget";
		return class_exists($widgetClassName) ? new $widgetClassName($this) : null;
	}

	public function createAjaxHandler(){
		$extensionClass = get_called_class();
		$ajaxHandlerClass = $extensionClass . 'AjaxHandle';
		return class_exists($ajaxHandlerClass) ? new $ajaxHandlerClass($this) : null;
	}

	public function createReportBuilder(){
		$extensionClass = get_called_class();
		$reportBuilderClass = $extensionClass . 'ReportBuilder';
		return class_exists($reportBuilderClass) ? new $reportBuilderClass($this) : null;
	}

	public function activate(){
		if ($log = $this->getLog()) {
			$log->createTable();
		}
		$cron = $this->getCron();
		if ($this->isEnabled() && $cron) {
			$cron->initSchedule();
		}
	}

	public function deactivate(){
		if ($cron = $this->getCron()) {
			$cron->clearSchedule();
		}
	}

	public function uninstall(){
		if ($settings = $this->getSettings()) {
			$settings->delete();
		}
		if ($log = $this->getLog()) {
			$log->dropTable();
		}
	}
}
