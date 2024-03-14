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

class wptsafEnv{

	protected static $instance;
	protected $date;
	protected $dateGmt;
	protected $ip;
	protected $uri;
	protected $url;
	protected $query;
	protected $referrer;
	protected $clientData;

	protected function __construct(){}

	protected function __clone(){}

	public static function getInstance(){
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function getData(){
		return array(
			'date' => $this->getDate(),
			'date_gmt' => $this->getDateGmt(),
			'ip' => $this->getIp(),
			'uri' => $this->getUri(),
			'url' => $this->getUrl(),
			'query' => $this->getQuery(),
			'referrer' => $this->getReferrer(),
			'user_id' => $this->getUserId(),
			'username' => $this->getUsername(),
			'client_data' => $this->getClientData(),
		);
	}

	public function getDate(){
		if (!$this->date) {
			$this->date = current_time('timestamp');
		}
		return $this->date;
	}

	public function getDateGmt(){
		if (!$this->dateGmt) {
			$this->dateGmt = current_time('timestamp', true);
		}
		return $this->dateGmt;
	}

	public function getIp(){
		if (null === $this->ip) {
			$headers = array(
				'HTTP_CF_CONNECTING_IP', // CloudFlare
				'HTTP_X_FORWARDED_FOR',  // Squid and most other forward and reverse proxies
				'REMOTE_ADDR',           // Default source of remote IP
			);

			$headers = apply_filters('wptsaf_security_filter_remote_addr_headers', $headers);
			$headers = (array)$headers;
			if (!in_array('REMOTE_ADDR', $headers)) {
				$headers[] = 'REMOTE_ADDR';
			}

			$ip = null;
			foreach ($headers as $header) {
				if (empty($_SERVER[$header])) {
					continue;
				}

				$ip = filter_var($_SERVER[$header], FILTER_VALIDATE_IP);

				if (!empty($ip)) {
					break;
				}
			}

			$this->ip = esc_sql((string)$ip);
			
		}
		
		if($this->ip=='::1') $this->ip = '127.0.0.1';

		return $this->ip;
	}

	public function getUri(){
		if (null === $this->uri) {
			$this->uri = isset($_SERVER['REQUEST_URI']) ? esc_sql(urldecode($_SERVER['REQUEST_URI'])) : null;
		}

		return $this->uri;
	}

	public function getUrl(){
		if (null === $this->url) {
			$uri = isset($_SERVER['REQUEST_URI']) ? explode('?', $_SERVER['REQUEST_URI']) : null;
			$this->url = isset($uri[0]) ? esc_sql(urldecode($uri[0])) : '';
		}
		
		return $this->url;
	}

	public function getQuery(){
		if (null === $this->query) {
			$uri = isset($_SERVER['REQUEST_URI']) ? explode('?', $_SERVER['REQUEST_URI']) : null;
			$this->query = isset($uri[1]) ? esc_sql(urldecode($uri[1])) : '';
		}
		
		return $this->query;
	}

	public function getReferrer(){
		if (null === $this->referrer) {
			$this->referrer = isset($_SERVER['HTTP_REFERER']) ? esc_sql($_SERVER['HTTP_REFERER']) : null;
		}
		return $this->referrer;
	}

	public function getUserId(){
		return get_current_user_id();
	}

	public function getUsername(){
		/** @var WP_User $currentUser */
		$user = function_exists('wp_get_current_user') ? wp_get_current_user() : 'Not auth yet';
		$user = is_a($user, 'WP_User') ? $user->user_login : ((string) $user);

		if (empty($user) && defined('DOING_CRON')) {
			$user = 'DOING_CRON';
		}

		return $user;
	}

	public function getClientData(){
		$runtimeClientData = array(
			'username' => $this->getUsername()
		);

		if (!$this->clientData) {
			$this->clientData = array(
				'ip' => $this->getIp(),

				'REQUEST_METHOD' 	=> esc_sql($_SERVER['REQUEST_METHOD']),
				'SERVER_NAME' 		=> esc_sql($_SERVER['SERVER_NAME']),
				'REQUEST_URI' 		=> esc_sql(urldecode($_SERVER['REQUEST_URI'])),
				'QUERY_STRING' 		=> esc_sql(urldecode($_SERVER['QUERY_STRING'])),
				'HTTP_ACCEPT' 		=> esc_sql($_SERVER['HTTP_ACCEPT']),

				'HTTP_REFERER' => 			isset($_SERVER['HTTP_REFERER']) 			? esc_sql($_SERVER['HTTP_REFERER']) 			: '',
				'HTTP_FORWARDED_FOR' => 	isset($_SERVER['HTTP_FORWARDED_FOR']) 		? esc_sql($_SERVER['HTTP_FORWARDED_FOR']) 		: '',
				'HTTP_X_FORWARDED_FOR' => 	isset($_SERVER['HTTP_X_FORWARDED_FOR']) 	? esc_sql($_SERVER['HTTP_X_FORWARDED_FOR']) 	: '',
				'HTTP_FROM' => 				isset($_SERVER['HTTP_FROM']) 				? esc_sql($_SERVER['HTTP_FROM']) 				: '',
				'HTTP_CLIENT_IP' => 		isset($_SERVER['HTTP_CLIENT_IP']) 			? esc_sql($_SERVER['HTTP_CLIENT_IP']) 			: '',
				'HTTP_HTTP_VIA' => 			isset($_SERVER['HTTP_HTTP_VIA']) 			? esc_sql($_SERVER['HTTP_HTTP_VIA']) 			: '',
				'HTTP_XROXY_CONNECTION' => 	isset($_SERVER['HTTP_XROXY_CONNECTION']) 	? esc_sql($_SERVER['HTTP_XROXY_CONNECTION']) 	: '',
				'HTTP_PROXY_CONNECTION' => 	isset($_SERVER['HTTP_PROXY_CONNECTION']) 	? esc_sql($_SERVER['HTTP_PROXY_CONNECTION']) 	: '',
				'HTTP_PROXY_USER' => 		isset($_SERVER['HTTP_PROXY_USER']) 			? esc_sql($_SERVER['HTTP_PROXY_USER']) 			: '',
				'HTTP_PC_REMOTE_ADDR' => 	isset($_SERVER['HTTP_PC_REMOTE_ADDR']) 		? esc_sql($_SERVER['HTTP_PC_REMOTE_ADDR']) 		: '',
				'HTTP_X_REMOTECLIENT_IP' => isset($_SERVER['HTTP_X_REMOTECLIENT_IP']) 	? esc_sql($_SERVER['HTTP_X_REMOTECLIENT_IP']) 	: '',
				'HTTP_PROXY_PORT' => 		isset($_SERVER['HTTP_PROXY_PORT']) 			? esc_sql($_SERVER['HTTP_PROXY_PORT']) 			: '',
			);
		}

		return array_merge($runtimeClientData, $this->clientData);
	}

	public function setMinimumMemoryLimit( $new_memory_limit ) {
		$memory_limit = @ini_get( 'memory_limit' );

		if ( - 1 < $memory_limit ) {
			$unit = strtolower( substr( $memory_limit, - 1 ) );
			$new_unit = strtolower( substr( $new_memory_limit, - 1 ) );

			if ( 'm' == $unit ) {
				$memory_limit *= 1048576;
			} else if ( 'g' == $unit ) {
				$memory_limit *= 1073741824;
			} else if ( 'k' == $unit ) {
				$memory_limit *= 1024;
			}

			if ( 'm' == $new_unit ) {
				$new_memory_limit *= 1048576;
			} else if ( 'g' == $new_unit ) {
				$new_memory_limit *= 1073741824;
			} else if ( 'k' == $new_unit ) {
				$new_memory_limit *= 1024;
			}

			if ((int) $memory_limit < (int) $new_memory_limit ) {
				@ini_set( 'memory_limit', $new_memory_limit );
			}
		}
	}
}
