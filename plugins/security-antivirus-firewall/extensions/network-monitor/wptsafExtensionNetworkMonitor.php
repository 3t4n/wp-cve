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

class wptsafExtensionNetworkMonitor extends wptsafAbstractExtension{
	protected static $instance;
	protected $managerIp;
	protected $managerIpChangeLog;

	public function __construct(){
		$this->name = 'network-monitor';
		$this->title = __('Firewall (Network Monitor)', 'wptsaf_security');
		$this->description = __("Firewall module protect your website from intrusions and hacker attacks. Network monitoring detect attacks and ban IP's of the attacker. Firewall provide wide range settings for monitoring process and banned IP's management. Ban manager provide few modes for temporary and permanent ban attackers IP's. ", 'wptsaf_security');
		$this->managerIp = new wptsafExtensionNetworkMonitorManagerIp($this);
		$this->managerIpChangeLog = new wptsafExtensionNetworkMonitorManagerIpChangeLog($this);
		parent::__construct();
	}

	public static function getInstance(){
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init(){
		parent::init();
		add_action('plugins_loaded', array($this, 'monitor'));
		add_action('wptsaf_security_ban_ip', array($this, 'banIp'), 10, 3);
	}

	public function getManagerIp(){
		return $this->managerIp;
	}

	public function getManagerIpChangeLog(){
		return $this->managerIpChangeLog;
	}

	public function monitor() {
		$ip = wptsafEnv::getInstance()->getIp();
		if (!$this->managerIp->isBanIp($ip)) {
			return;
		}

		$this->log->insertRow();

		header('HTTP/1.0 403 Forbidden');
		die();
	}

	public function banIp($ip, $duration = null, $comment = ''){
		$this->getManagerIp()->banByIp($ip, $duration, $comment);
	}

	public function activate(){
		parent::activate();
		$this->getManagerIp()->createTable();
		$this->getManagerIpChangeLog()->createTable();
	}

	public function uninstall(){
		parent::uninstall();
		$this->getManagerIp()->dropTable();
		$this->getManagerIpChangeLog()->dropTable();
	}
}
