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

class wptsafExtensionLoginBruteForce extends wptsafAbstractExtension{
	
	protected static $instance;
	protected $authenticateUser;
	protected $authenticateUsername;

	public function __construct(){
		$this->name = 'login-brute-force';
		$this->title = __('Brute Force Monitor', 'wptsaf_security');
		$this->description = __("With brute force protection module you can limit number of attempts for failed logins. IP of the hacker will be blocked. In settings section you can customize lock time, amount of attempts for the first and second time. Admin notifications settings.", 'wptsaf_security');
		
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

		// priority - 100000 for invoke filter as last
		add_filter('authenticate', array($this,'authenticate' ), 100000, 3);

		add_action('wp_login', array($this,'wpLogin'), 10, 2);
		add_action('wp_login_failed', array($this, 'wpLoginFailed'), 1, 1);
		add_filter('xmlrpc_login_error', array( $this, 'xmlrpcLoginError'), 10, 2);
	}

	public function authenticate($user, $username = '', $password = '') {
		$this->authenticateUser = $user;

		if (defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST === true) {
			$this->authenticateUsername = $username;
		}

		return $user;
	}

	public function wpLogin($username, $user = null ) {
		$this->log->insertRow(array(
			'username' => $username,
			'status' => wptsafExtensionLoginBruteForceLog::LOGIN_STATUS_SUCCESS,
			'description' => ''
		));
	}

	public function wpLoginFailed($username) {
		$description = '';

		if ($this->authenticateUser && is_wp_error($this->authenticateUser)) {
			/** @var WP_Error $error */
			$error = $this->authenticateUser;
			$description = strip_tags($error->get_error_message());
		}

		$this->log->insertRow(array(
			'username' => $username,
			'status' => wptsafExtensionLoginBruteForceLog::LOGIN_STATUS_FAILED,
			'description' => $description
		));

		$this->banIpIfNeed();
	}

	public function xmlrpcLoginError($error) {
		$this->log->insertRow(array(
			'username' => $this->authenticateUsername,
			'status' => wptsafExtensionLoginBruteForceLog::LOGIN_STATUS_FAILED,
			'description' => 'XMLRPC_REQUEST. ' . $error
		));

		$this->banIpIfNeed();

		return $error;
	}

	protected function banIpIfNeed(){
		$settings = $this->getSettings();
		$env = wptsafEnv::getInstance();
		$ip = $env->getIp();

		$timeCountingLoginSecond = $settings->get('time_counting_login_second');
		$dateGmtToCountingLoginSecond = 0 == $timeCountingLoginSecond
			? 0
			: ($env->getDateGmt() - $timeCountingLoginSecond * MINUTE_IN_SECONDS);
		$logins = $this->log->getLoginsByIp($ip, $dateGmtToCountingLoginSecond);
		$countFailedLogin = 0;
		foreach ($logins as $login) {
			if (wptsafExtensionLoginBruteForceLog::LOGIN_STATUS_SUCCESS == $login['status']) {
				break;
			}
			$countFailedLogin++;
		}
		if ($settings->get('count_failed_login_second') <= $countFailedLogin) {
			do_action(
				'wptsaf_security_ban_ip',
				$ip,
				$settings->get('lock_time_second'),
				sprintf(__('Ban by extension %s', 'wptsaf_security'), $this->getTitle())
			);

			if ($settings->get('is_notify_admin_second')) {
				$this->notifyAdmin($ip, $countFailedLogin, $timeCountingLoginSecond);
			}

			return;
		}

		$timeCountingLoginFirst = $settings->get('time_counting_login_first');
		$dateGmtToCountingLoginFirst = 0 == $timeCountingLoginFirst
			? 0
			: ($env->getDateGmt() - $timeCountingLoginFirst * MINUTE_IN_SECONDS);
		$logins = $this->log->getLoginsByIp($ip, $dateGmtToCountingLoginFirst);
		$countFailedLogin = 0;
		foreach ($logins as $login) {
			if (wptsafExtensionLoginBruteForceLog::LOGIN_STATUS_SUCCESS == $login['status']) {
				break;
			}
			$countFailedLogin++;
		}
		if ($settings->get('count_failed_login_first') <= $countFailedLogin) {
			do_action(
				'wptsaf_security_ban_ip',
				$ip,
				$settings->get('lock_time_first'),
				sprintf(__('Ban by extension %s', 'wptsaf_security'), $this->getTitle())
			);

			if ($settings->get('is_notify_admin_first')) {
				$this->notifyAdmin($ip, $countFailedLogin, $timeCountingLoginFirst);
			}
		}
	}

	protected function notifyAdmin($ip, $countFailedLogin, $timeCounting){
		$view = new wptsafView();
		$emailContent = $view->content(
			$this->getExtensionDir() . 'template/email/ban-ip.php',
			array(
				'ip' => $ip,
				'countFailedLogin' => $countFailedLogin,
				'timeCounting' => $timeCounting,
			)
		);

		$recipients = wptsafSecurity::getInstance()->getSettings()->get('notification_emails');
		$blogName = get_bloginfo('name');
		foreach ($recipients as $recipient) {
			wp_mail(
				$recipient,
				$blogName.': '.__('wpTools S.A.F - Brute Force module - IP banned', 'wptsaf_security'),
				$emailContent
			);
		}
	}
}
