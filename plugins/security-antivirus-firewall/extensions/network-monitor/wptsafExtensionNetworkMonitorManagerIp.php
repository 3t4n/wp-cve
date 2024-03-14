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


class wptsafExtensionNetworkMonitorManagerIp extends wptsafAbstractLog{
	const SESSION_DENY_TIME = 300; //sec
	const SESSION_ALLOW_TIME = 60; //sec


	public function __construct(wptsafAbstractExtension $extension){
		parent::__construct($extension);

		$this->table = WPTSAF_DB_PREFIX . 'network_monitor_manager_ip';
		$this->fieldList = array(
			'ip',
			'date_gmt',
			'duration',
			'is_active',
			'description',
		);
	}


	public function insertRow(array $fields = array()) {
		$changeLogComment = isset($fields['change_log_comment']) ? $fields['change_log_comment'] : '';
		unset($fields['change_log_comment']);

		$insertId = parent::insertRow($fields);
		if (is_int($insertId)) {
			$this->extension->getManagerIpChangeLog()->addChange($insertId, $changeLogComment, $fields);
		}

		return $insertId;
	}


	public function updateRow(array $fields = array()) {
		$changeLogComment = isset($fields['change_log_comment']) ? $fields['change_log_comment'] : '';
		unset($fields['change_log_comment']);

		$result = parent::updateRow($fields);
		if (is_int($result)) {
			$this->extension->getManagerIpChangeLog()->addChange($fields['id'], $changeLogComment, $fields);
		}

		return $result;
	}


	public function prepareRow(array $row){
		$row['lock_date_to_gmt'] = $row['duration']
			? date(
				WPTSAF_DATE_FORMAT,
				$row['date_gmt'] + $row['duration'] * MINUTE_IN_SECONDS + WPTSAF_DATE_GMT_OFFSET
			)
			: __('Permanent', 'wptsaf_security');
		$row['permanent'] = $row['duration'] ? 0 : 1;
		
		return parent::prepareRow($row);
	}


	public function isBanIp($ip){
		$banHash = md5($ip . get_bloginfo('url'));
		$now = wptsafEnv::getInstance()->getDateGmt();

		if (!session_id()) {
			session_start();
		}

		if (isset($_SESSION['wptsaf_security']['network-monitor']['deny'][$banHash])) {
			$denyTimeEnd = $_SESSION['wptsaf_security']['network-monitor']['deny'][$banHash];
			if ($denyTimeEnd > $now) {
				return true;
			}
			unset($_SESSION['wptsaf_security']['network-monitor']['deny'][$banHash]);
		}
		if (isset($_SESSION['wptsaf_security']['network-monitor']['allow'][$banHash])) {
			$allowTimeEnd = $_SESSION['wptsaf_security']['network-monitor']['allow'][$banHash];
			if ($allowTimeEnd > $now) {
				return false;
			}
			unset($_SESSION['wptsaf_security']['network-monitor']['allow'][$banHash]);
		}

		$ban = $this->getBanByIp($ip);
		$isDeny = false;
		if ($ban && $ban['is_active']) {
			if (0 == $ban['duration'] || ($now < $ban['date_gmt'] + $ban['duration'] * MINUTE_IN_SECONDS)) {
				$isDeny = true;
			}
		}

		if ($isDeny) {
			$_SESSION['wptsaf_security']['network-monitor']['deny'][$banHash] = $now + self::SESSION_DENY_TIME;
		} else {
			$_SESSION['wptsaf_security']['network-monitor']['allow'][$banHash] = $now + self::SESSION_ALLOW_TIME;
		}

		return $isDeny;
	}


	public function getBanById($id){
		global $wpdb;
		return $wpdb->get_row($wpdb->prepare(
			"SELECT * FROM " . $this->table . " WHERE id = '%d'",
			intval($id)
		), ARRAY_A);
	}


	public function getBanByIp($ip){
		global $wpdb;
		return $wpdb->get_row($wpdb->prepare(
			"SELECT * FROM " . $this->table . " WHERE ip = '%s'",
			preg_replace('/[^0-9\.]/', '', $ip)
		), ARRAY_A);
	}


	public function banByIp($ip, $duration = null, $comment = ''){
		$ip = preg_replace('/[^0-9\.]/', '', $ip);
		$comment = strip_tags($comment);
		$ban = $this->getBanByIp($ip);
		$banId = null;

		if ($ban) {
			return $this->banById($ban['id'], $duration, $comment);
		}

		$ban = array(
			'ip' => $ip,
			'duration' => $duration && is_numeric($duration)
				? absint($duration)
				: $this->extension->getSettings()->get('lock_duration'),
			'is_active' => 1,
			'description' => '',
			'change_log_comment' => $comment
		);
		$result = $this->extension->getManagerIp()->insertRow($ban);

		if (is_wp_error($result)) {
			return $result;
		}
		return true;
	}


	public function banById($id, $duration = null, $comment = ''){
		$ban = $this->getBanById($id);
		if (!$ban) {
			return new WP_Error('wptsaf_security', __("Could not find IP address", 'wptsaf_security'));
		}

		$nowGmt = wptsafEnv::getInstance()->getDateGmt();
		$duration = null !== $duration && is_numeric($duration)
			? absint($duration)
			: $this->extension->getSettings()->get('lock_duration');
		$newDateTo = $nowGmt + ($duration * MINUTE_IN_SECONDS);
		$currentDateTo = $ban['date_gmt'] + ($ban['duration'] * MINUTE_IN_SECONDS);

		$ban['is_active'] = 1;
		$ban['change_log_comment'] = strip_tags($comment);
		if (0 == $duration || $newDateTo > $currentDateTo) {
			$ban['date_gmt'] = $nowGmt;
			$ban['duration'] = $duration;
		}
		return $this->extension->getManagerIp()->updateRow($ban);
	}


	public function rotate(){
	}


	public function createTable(){
		global $wpdb;
		$wpdb->query(
			"CREATE TABLE IF NOT EXISTS `{$this->table}` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`date_gmt` int(11) NOT NULL,
				`ip` varchar(15) NOT NULL,
				`duration` int(11) NOT NULL,
				`is_active` tinyint(1) NOT NULL DEFAULT '1',
				`description` text NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `ip` (`ip`)
			)
			DEFAULT CHARACTER SET utf8
  			DEFAULT COLLATE utf8_general_ci"
		);
	}
}
