<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	exit(1);
}

/**
 * WebTotem Base class for Wordpress.
 */
class WebTotem {

	/**
	 * Returns an URL from the admin dashboard.
	 *
	 * @param  string $url
	 *    Optional trailing of the URL.
	 * @return string
	 *    Full valid URL from the admin dashboard.
	 */
	public static function adminURL($url = '') {
		if (self::isMultiSite() and is_super_admin()) {
			return network_admin_url($url);
		}
		return admin_url($url);
	}

	/**
	 * Define role of current user.
	 *
	 */
	public static function getUserRole() {

		if (defined('WEBTOTEM_USER_ROLE')) {
			return true;
		}
		$current_user = wp_get_current_user();
		if ( !($current_user instanceof WP_User) ){
			$user_role = 0;
		} else {
			$roles = $current_user->roles;

			if(in_array('administrator', $roles)) {
				$user_role = 1;
			} elseif(in_array('editor', $roles) or current_user_can('publish_posts')) {
				$user_role = 2;
			} else {
				$user_role = 0;
			}
		}

		define( 'WEBTOTEM_USER_ROLE', $user_role );

		return true;
	}

	/**
	 * Check whether the current site is working as a multi-site instance.
	 *
	 * @return bool
	 *    Either TRUE or FALSE in case WordPress is being used as a multi-site instance.
	 */
	public static function isMultiSite() {
		return (bool) ( (function_exists('is_multisite') && is_multisite()) || (defined('MULTISITE') && MULTISITE == true) );
	}

	/**
	 * Returns the md5 hash representing the content of a file.
	 *
	 * @param  string $file
	 *    Relative path to the file.
	 * @return string
	 *    Seven first characters in the hash of the file.
	 */
	public static function fileVersion($file = '') {
		return substr(md5_file(WEBTOTEM_PLUGIN_PATH . '/' . $file), 0, 7);
	}

	/**
	 * Returns full path to image.
	 *
	 * @param  string $image
	 *    Relative path to the file.
	 * @return string
	 *    Full path to image.
	 */
	public static function getImagePath($image) {
		return WEBTOTEM_URL. '/includes/img/' . $image;
	}

  /**
   * Checking whether the current domain belongs to the kz domain zone.
   *
   * @return bool
   *    true is returned if the domain belongs to the kz domain zone.
   */
  public static function isKz() {
    $is_kz = json_decode(WebTotemOption::getOption('is_kz'), true);
    if(is_array($is_kz)){
      return $is_kz['value'];
    }

    if(function_exists('idn_to_utf')){
        $host = idn_to_utf8($_SERVER['HTTP_HOST']);
    } else {
        $host = $_SERVER['HTTP_HOST'];
    }

    $parts = explode('.', $host);
    $domain_zone = $parts[count($parts)-1];

    if($domain_zone === 'kz' or $domain_zone === 'қаз'){
      $is_kz['value'] = true;
    } else {
      $is_kz['value'] = false;
    }

    WebTotemOption::setOptions(['is_kz' => $is_kz]);

    return $is_kz['value'];
  }

  /**
   * Convert object to array.
   *
   * @param array $data
   *    Array.
   * @return array
   *    Returns array.
   */
  public static function convertObjectToArray($data){

        if(!is_array($data)) $data = (array)$data;
        array_walk_recursive($data, function(&$item){
            if(is_object($item)) $item = (array)$item;
        });

        return $data;
    }

	/**
	 * Removing duplicates by one key.
	 *
	 * @param array $array
	 *    Array.
	 * @param string $key
	 *    Delete duplicates with the same key.
	 *
	 * @return array
	 *    Returns array.
	 */
	public static function arrayUniqueKey($array, $key) {
		$tmp = $key_array = array();
		$i = 0;

		foreach ($array as $val) {
			if (!in_array($val[$key], $key_array)) {
				$key_array[$i] = $val[$key];
				$tmp[$i] = $val;
			}
			$i++;
		}
		return $tmp;
	}

	/**
	 * Returns user IP address.
	 *
	 * @return string
	 *    Returns user IP address.
	 */
	public static function getUserIP() {
		$arr =  [
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'HTTP_CF_CONNECTING_IP',
			'REMOTE_ADDR'
		];

		foreach ($arr as $key){
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					$ip = trim($ip);
					$ip = filter_var($ip, FILTER_VALIDATE_IP);
					if (!empty($ip)) {
						return $ip;
					}
				}
			}
		}
		return false;
	}

  /**
   * Convert the file size to а human-readable format.
   *
   * @param string $bytes
   *    File size in bytes.
   * @param string $decimals
   *    The number of characters after the decimal point.
   *
   * @return string
   *   Returns the file size in a human-readable format.
   */
  public static function humanFilesize($bytes, $decimals = 2) {
    $factor = floor((strlen($bytes) - 1) / 3);
    $unit_of_measurement = ($factor > 0) ? substr("KMGT", $factor - 1, 1) : '';
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $unit_of_measurement . 'B';
  }

  /**
   * Check whether the file is publicly accessible.
   *
   * @param string $url
   *    http link to the file.
   * @param string $path
   *    The path to the file.
   *
   * @return bool
   */
  public static function isPubliclyAccessible($url, $path) {
    $response = wp_remote_get($url);

    if ((int) floor(((int) wp_remote_retrieve_response_code($response) / 100)) === 2) {
      $handle = @fopen($path, 'r');
      if ($handle) {
        $contents = fread($handle, 700);
        fclose($handle);
        $remoteContents = substr(wp_remote_retrieve_body($response), 0, 700);

        return $contents === $remoteContents;
      }
    }
    return false;
  }

	/**
   * Check that the training period has passed for the firewall.
   *
   * @param string $created_at
   *   Date when the waf configuration was created.
   *
   * @return bool
   *   Returns boolean.
   */
  public static function isWafTraining($created_at) {
  	if($created_at) {
		  $when_waf_trained = strtotime('+2 day', strtotime($created_at));
		  $today = strtotime('today');

		  return ($when_waf_trained < $today) ? FALSE : TRUE;
	  }
	  return FALSE;
  }

	/**
	 * Check if the data for the period is available.
	 *
	 * @param string $created_at
	 *   Date when the agent manager was created.
	 *
	 * @return array
	 *   Returns an array with periods.
	 */
	public static function isPeriodAvailable($created_at) {

		$diff = strtotime(gmdate("Y-m-d H:i:s")) - strtotime($created_at);
		$daysCount = floor($diff / 86400);

		return [
			'monthly' => $daysCount > 7,
			'yearly' => $daysCount > 30,
		];

	}



	/**
   * Converting a date to the appropriate format.
   *
   * @param string $date
   *   Date in any format.
   * @param string $format
   *   The format to which you want to convert the date.
   *
   * @return string
   *   Returns converted Date.
   */
  public static function dateFormatter($date, $format = 'M j, Y \/ H:i') {
    if (!$date) {
      return __('Unknown', 'wtotem');
    }

    $time_zone = WebTotemOption::getOption('time_zone_offset');
    $user_time = ($time_zone) ? strtotime($time_zone . 'hours', strtotime($date)) : strtotime($date);

    return date_i18n($format, $user_time);
  }

  /**
   * Get theme mode data.
   *
   * @return array
   *   Returns array with current theme data.
   */
  public static function getThemeMode() {
    $theme_mode = WebTotemOption::getSessionOption('theme_mode');
    return [
      "is_dark_mode" => $theme_mode == 'dark' ? 'wtotem_theme—dark' : '',
      "dark_mode_checked" => $theme_mode == 'dark' ? 'checked' : '',
    ];
  }

	/**
	 * Get current user language.
	 *
	 * @return string
	 *   Returns current language in 2-letter abbreviations
	 */
	public static function getLanguage() {
		$current_language = substr(get_bloginfo('language'), 0,2);
		$language = (in_array($current_language,['ru','en','pl'])) ? $current_language : 'en' ;
		return $language;
	}

  /**
   * Converting a date to the appropriate format.
   *
   * @param string|array $days
   *   Number of days or period to convert.
   *
   * @return array
   *   Returns an array of two values "from" and "to"
   */
  public static function getPeriod($days) {

    if (!$days) {
      $days = 30;
    }

    switch ($days) {

      case is_array($days):
        $to = $days[1] ?: $days[0];
        $period = [
          'from' => strtotime(date('Y-m-d 00:00:01', strtotime(self::formatDate($days[0])))),
          'to' => strtotime(date('Y-m-d 23:59:59', strtotime(self::formatDate($to)))),
        ];
        break;

      case $days <= 1:
        $period = [
          'from' => strtotime('-24 hours'),
          'to' => time(),
        ];
        break;

      default:
        $period = [
          'from' => time() - ($days * 86400),
          'to' => time(),
        ];
    }

    return $period;
  }

  /**
   * Converting a date from "j M, Y" format to 'd-m-Y' format.
   *
   * @return string
   *   Returns date in new format
   */
  public static function formatDate($date){

    $pattern = '/^(\d{1,2})\s+([a-zA-Z]+),\s+(\d{4})$/';

    if (preg_match($pattern, $date, $matches)) {
      $monthNumber = date_parse($matches[2])['month'];

      return sprintf('%02d-%02d-%04d', $matches[1], $monthNumber, $matches[3]);
    }

    return $date;

  }

	/**
	 * Convert an array to a string with quotation marks.
	 *
	 * @param array $array
	 *   Data array.
	 *
	 * @return string
	 *   Array of data converted to string.
	 */
	public static function convertArrayToString($array) {
		if(empty($array)){
			return '';
		}
		return '"' . implode('","', $array) . '"';
	}

  /**
   * Converting the response to a readable form.
   *
   * @param string $message
   *   Message response from the API server to the request.
   *
   * @return string|bool
   *   Returns a message.
   */
  public static function messageForHuman($message) {

    $definition = $message;

    switch ($message) {
      case 'HOSTS_LIMIT_EXCEEDED':
        $definition = __('Limit of adding sites exceeded.', 'wtotem');
        break;

      case 'USER_ALREADY_REGISTERED':
        $definition = __('A user with this email already exists.', 'wtotem');
        break;

      case 'DUPLICATE_HOST':
        $definition = __('Duplicate host', 'wtotem');
        break;

      case 'INVALID_DOMAIN_NAME':
        $definition = __('Invalid Domain Name', 'wtotem');
        break;
	    default:
		    $definition = str_replace("_", " ", $definition);
		    $definition = ucfirst(strtolower($definition));

    }
    return $definition;
  }

  /**
   * Get the data associated with the status.
   *
   * @param string $status
   *   Module or agent status.
   *
   * @return array
   *   Returns an array with status data.
   */
  public static function getStatusData($status) {
    $path = self::getImagePath('');
	  $status = ($status == "installed") ? 'working' : $status;

    switch ($status) {

      case 'clean':
      case 'up':
      case 'installed':
      case 'working':
      case 'good':
        $status_data = [
          'class' => 'is--status--ok',
          'image' => $path . 'check-mark.svg',
          'icon' => $path . 'icon_success_status.svg',
        ];
        break;

      case 'pending':
        $status_data = [
          'class' => 'is--status--pending',
          'image' => $path . 'loading.svg',
          'icon' => $path . 'alert-warning.svg',
        ];
        break;

      case 'pause':
      case 'modified':
        $status_data = [
          'class' => 'is--status--pending',
          'image' => $path . 'warning.svg',
          'icon' => $path . 'alert-warning.svg',
        ];
        break;

      case 'expired':
      case 'no_cert':
      case 'expires':
      case 'open_ports':
      case 'warning':
      case 'not_supported':
      case 'not_registered':
        $status_data = [
          'class' => 'is--status--warning',
          'image' => $path . 'warning.svg',
          'icon' => $path . 'alert-warning.svg',
        ];
        break;

      case 'invalid':
      case 'revoked':
      case 'untrusted':
      case 'not_found':
      case 'wrong_host':
      case 'error':
      case 'down':
      case 'expires_today':
      case 'infected':
      case 'deface':
      case 'not_installed':
        $status_data = [
          'class' => 'is--status--error',
          'image' => $path . 'warning.svg',
          'icon' => $path . 'alert-warning.svg',
        ];
        break;

      default:
        $status_data = [
          'class' => 'is--status--pending',
          'image' => $path . 'warning.svg',
          'icon' => $path . 'alert-warning.svg',
        ];
    }
    $status_data['name'] = $status;
    $status_data['text'] = self::getStatusText($status);
    $status_data['tooltips'] = self::getTooltips($status);

    return $status_data;
  }

  /**
   * Get a readable status text.
   *
   * @param string $status
   *   Module or agent status.
   *
   * @return string
   *   Returns the status text in the current language.
   */
  public static function getStatusText($status) {
    $statuses = [
      'warning' => __('Warning', 'wtotem'),
      'error' => __('Error', 'wtotem'),
      'success' => __('Success', 'wtotem'),
      'info' => __('Info', 'wtotem'),
      'invalid' => __('Invalid', 'wtotem'),
      'ok' => __('Everything is OK', 'wtotem'),
      'expired' => __('Expired', 'wtotem'),
      'expires' => __('Expires', 'wtotem'),
      'expires_today' => __('Expires today', 'wtotem'),
      'missing' => __('Missing', 'wtotem'),
      'active' => __('Active', 'wtotem'),
      'inactive' => __('Inactive', 'wtotem'),
      'pending' => __('Pending', 'wtotem'),
      'pause' => __('Disabled', 'wtotem'),
      'available' => __('Available', 'wtotem'),
      'not_supported' => __('Not supported', 'wtotem'),
      'not_registered' => __('Not registered', 'wtotem'),
      'unsupported' => __('Unsupported', 'wtotem'),
      'clean' => __('Clean', 'wtotem'),
      'clear' => __('Clear', 'wtotem'),
      'blacklisted' => __('Infected', 'wtotem'),
      'miner_detected' => __('Infected', 'wtotem'),
      'deface' => __('Deface', 'wtotem'),
      'modified' => __('Modified', 'wtotem'),
      'detected' => __('Detected', 'wtotem'),
      'open_ports' => __('Open ports', 'wtotem'),
      'blocked' => __('Blocked', 'wtotem'),
      'connected' => __('Connected', 'wtotem'),
      'attacks_detected' => __('Attacks detected', 'wtotem'),
      'signature_found' => __('Signature found', 'wtotem'),
      'file_changes' => __('File changes', 'wtotem'),
      'no_cert' => __('No cert', 'wtotem'),
      'down' => __('Down', 'wtotem'),
      'up' => __('Up', 'wtotem'),
      'infected' => __('Infected', 'wtotem'),
      'not_installed' => __('Need to install', 'wtotem'),
      'agent_not_available' => __('Agent not available', 'wtotem'),
      'update_error' => __('Update error', 'wtotem'),
      'session_error' => __('Session Error', 'wtotem'),
      'internal_error' => __('Internal Error', 'wtotem'),
      'installing' => __('Installing', 'wtotem'),
      'installed' => __('Installed', 'wtotem'),
      'working' => __('Working', 'wtotem'),
      "critical" => __('Critical', 'wtotem'),
      "deleted" => __('Deleted', 'wtotem'),
      "changed" => __('Changed', 'wtotem'),
      "new" => __('New', 'wtotem'),
      "scanned" => __('Scanned', 'wtotem'),
      "quarantine" => __('In quarantine', 'wtotem'),
      "good" => __('Good', 'wtotem'),
      "wrong_host" => __('Wrong host', 'wtotem'),
      "revoked" => __('Revoked', 'wtotem'),
      "untrusted" => __('Untrusted', 'wtotem'),
      "not_found" => __('Not found', 'wtotem'),
    ];

    return (array_key_exists($status, $statuses)) ? $statuses[$status] : $status;
  }

  /**
   * Get tooltips text for status.
   *
   * @param string $status
   *   Module or agent status.
   *
   * @return string
   *   Returns the status tooltip in the current language.
   */
  public static function getTooltips($status) {
    $tooltips = [
      'invalid' => __('Invalid -The certificate is invalid. Please, make sure that relevant certificate details filled correctly.', 'wtotem'),
      'expired' => __('Expired - The certificate has expired. Connection is not secure. Please, renew it.', 'wtotem'),
      'expires' => __('Expires - The certificate expires soon. Please, take actions.', 'wtotem'),
      'expires_today' => __('Expires today - The certificate expires today. Please, take actions.', 'wtotem'),
      'error' => __("Error - Something went wrong. Please, contact us, we'll fix the problem.", 'wtotem'),
      'pending' => __('Pending - System processes your website. Data will be available soon.', 'wtotem'),
      'pause' => __('Pause - The module is paused.', 'wtotem'),
      'clean' => __('Everything is OK - Nothing to worry about. Everything is alright.', 'wtotem'),
      'deface' => __("Deface - Website hacked. Please, contact us, we'll fix the problem.", 'wtotem'),
      'open_ports' => __('Open ports - Open ports detected. Your website is vulnerable to attacks.', 'wtotem'),
      'blocked' => __('Blocked - The module is blocked due to billing issues.', 'wtotem'),
      'no_cert' => __("No cert - You don't have SSL certificate. We recommend you to install it for security concerns.", 'wtotem'),
      'down' => __('Down - The website is not available for visitors.', 'wtotem'),
      'up' => __('Up - The website is available for visitors.', 'wtotem'),
      'infected' => __('Infected - The website site is blacklisted and may have infected files. Please, check antivirus module.', 'wtotem'),
      'installing' => __('It means that the agent installation is in progress. Usually, it takes up to one hour.', 'wtotem'),
      'agent_not_available' => __('We cannot locate the agent right now.', 'wtotem'),
      'update_error' => __('It seems that your agent failed to update due to permissions restrictions.', 'wtotem'),
      'session_error' => __('This means that the agent did not create a secure session. Possible causes include network issues, wrong server configuration, third-party firewalls. Please contact our support..', 'wtotem'),
      'internal_error' => __('It means that the server is overloaded or there might be some problems with the connection. Usually, the issue resolves itself within 10-15 minutes. If the status does not change during two hours, please cordially contact our support..', 'wtotem'),
      'working' => __('Everything is alright.', 'wtotem'),
      'installed' => __('Everything is alright.', 'wtotem'),
      'not_installed' => __('You need to install agent manager to activate antivirus and firewall.', 'wtotem'),
    ];

    return (array_key_exists($status, $tooltips)) ? $tooltips[$status] : '';
  }

	/**
	 * Converting site data.
	 *
	 * @param array $data
	 *   Sites data from WebTotem.
	 *
	 * @return array
	 *   Converted data.
	 */
	public static function allSitesData($data) {

		$local_sites = get_sites();
		$main_host = WebTotemOption::getMainHost();
		$domains = [];

		foreach ($local_sites as $site){
			$domain = untrailingslashit($site->domain . $site->path);
			$domains[$domain] = $domain;
		}

		$sites = [];
		if(array_key_exists('edges', $data)){
			foreach ($data['edges'] as $site) {
				$site = $site['node'];
				// Take sites only from the multisite network.
				if(array_key_exists($site['hostname'], $domains)) {
					unset($domains[$site['hostname']]);
					$sites[] = [
						'hostname' => $site['hostname'],
						'title' => $site['title'],
						'main_host' => $main_host['id'] == $site['id'],
						'host_id' => $site['id'],
						'url' => admin_url('admin.php?page=wtotem_dashboard&hid=' . $site['id']),
						'firewall' => [
							'status' => self::getStatusData($site['firewall']['status']),
						],
						'antivirus' => [
							'status' => self::getStatusData($site['antivirus']['status']),
						],
						'stacks' => self::getStacksData($site['maliciousScript']['stack']),
						'services' => self::getSiteServicesData($site),
					];
				}
			}

		}
		return $sites;
	}

	/**
	 * Converting stacks data.
	 *
	 * @param array $stacks
	 *   Stacks data from WebTotem.
	 *
	 * @return array
	 *   Converted data.
	 */
	protected static function getStacksData($stacks) {
		$apps = file_get_contents(WEBTOTEM_PLUGIN_PATH . '/includes/js/apps.json');
		$apps = json_decode($apps, true);

		$path = 'https://assets.wtotem.net/images/apps/';
		$defaultIcon = WEBTOTEM_URL . '/includes/img/defaultTechnologiesIcon.svg';

		$stackList = array_slice($stacks, 0,3);
		$list = [];
		foreach ($stackList as $key => $stack){
			$list[$key] = [
				'name' => $stack['name'],
				'icon' => $path . ($apps[$stack['name']]['icon'] ?: $defaultIcon),
			];
		}

		if(count($stacks) <= 3){
			$other['count'] = 0;
			$other['names'] = [];
		} else {
			$otherStacks = array_slice($stacks, 3);
			$other['count'] = count($otherStacks);
			foreach ($otherStacks as $stack){
				$other['names'][] = $stack['name'];
			}
		}
		if($other['names']){
			$other['names'] = implode(",", $other['names']);
		}
		return  ['list' => $list, 'other' => $other];
	}

	/**
	 * Converting services data.
	 *
	 * @param $data
	 *   Site data from WebTotem.
	 *
	 * @return array
	 *   Converted data.
	 */
	protected static function getSiteServicesData($data) {

		$services = [
			'ssl' => 'ssl',
			'availability' => 'wa',
			'reputation' => 'rc',
			'ports' => 'ps',
			'deface' => 'dc',
			'domain' => 'dec',
		];

		$list = [];
		$other['count'] = 0;
		$other['names'] = [];

		foreach ($services as $key => $service){
			if(array_key_exists($key, $data) and is_array($data[$key]) and array_key_exists('status', $data[$key])){
				$status = self::getServiceStatus($data[$key]['status']);

				if(in_array($status['color'], ['red', 'yellow'])){
					if(count($list) < 2){
						$color = $status['color'] == 'red' ? 'white/' : '';

						$list[$key] = [
							'status' => $status,
							'icon' => 'services/'. $color . $service . '.svg',
							'name' => self::getServiceName( $service ),
						];
					} else {
						$other['names'][] = self::getServiceName( $service );
						$other['count']++;
					}
				}

			}
		}
		if($other['names']){
			$other['names'] = implode(",", $other['names']);
		}
		return ['list' => $list, 'other' => $other];
	}

	/**
	 * Get the data associated with the status.
	 *
	 * @param string $status
	 *   Module or agent status.
	 *
	 * @return array
	 *   Returns an array with status data.
	 */
	public static function getServiceStatus($status) {
		switch ($status) {

			case 'expired':
			case 'invalid':
			case 'error':
			case 'expires_today':
			case 'down':
			case 'infected':
			case 'deface':
			case 'not_installed':
			case 'quarantine':
				$status_data = [
					'color' => 'red',
				];
				break;

			case 'no_cert':
			case 'expires':
			case 'open_ports':
			case 'modified':
			case 'not_supported':
			case 'not_registered':
			case 'blocked':
			case 'pause':
			case 'internal_error':
			case 'update_error':
			case 'config_error':
			case 'agent_not_available':
			case 'session_error':
				$status_data = [
					'color' => 'yellow',
				];
				break;

			case 'clean':
			case 'installed':
			case 'up':
			case 'scanned':
			case 'working':
				$status_data = [
					'color' => 'green',
				];
				break;

			case 'deleted':
        $status_data = [
	        'color' => 'black',
        ];
        break;

			case 'installing':
			case 'pending':
			default:
				$status_data = [
					'color' => 'gray',
				];
				break;

		}

		return $status_data;
	}

	/**
	 * Get the translation of service.
	 *
	 * @param $service
	 *   Service short name.
	 *
	 * @return string
	 *   Translation of service.
	 */
	public static function getServiceName($service){
		$services = [
			"wa" => __('Availability', 'wtotem'),
			"rc" => __('Reputation', 'wtotem'),
			"ssl" => 'SSL',
			"cms" => __('Technologies', 'wtotem'),
			"dc" => __('Deface', 'wtotem'),
			"ps" => __('Ports', 'wtotem'),
			"waf" => __('Firewall', 'wtotem'),
			"av" => __('Antivirus', 'wtotem'),
			"dec" => __('Domain', 'wtotem'),
		];
		return $services[$service];
	}

  /**
   * Get reports with modules list.
   *
   * @param array $edges
   *   Data on generated reports.
   *
   * @return array
   *   Returns an array with converted data.
   */
  public static function getReports(array $edges) {
    $modulesLang = [
      'wa' => __('Availability log', 'wtotem'),
      'dc' => __('Deface log', 'wtotem'),
      'ps' => __('Port log', 'wtotem'),
      'rc' => __('Reputation log', 'wtotem'),
      'sc' => __('Evaluation log', 'wtotem'),
      'av' => __('Antivirus log', 'wtotem'),
      'waf' => __('Firewall log', 'wtotem'),
    ];

    $reports = [];

    foreach ($edges as $edge) {
      if (in_array(FALSE, $edge["node"])) {
        $arr = [];
        foreach ($edge["node"] as $module => $value) {
          if ($value == TRUE && array_key_exists($module, $modulesLang)) {
            $arr[] = $modulesLang[$module];
          }
        }
        $modules = implode(", ", $arr);
      }
      else {
        $modules = __('All modules', 'wtotem');
      }

      $reports[] = [
        'id' => $edge["node"]['id'],
        'modules' => $modules,
        'created_at' => self::dateFormatter($edge["node"]['createdAt']),
      ];
    }

    return $reports;
  }

  /**
   * Get reputation status description.
   *
   * @param string $status
   *   Reputation status.
   *
   * @return string
   *   Returns a description of the reputation status
   */
  public static function getReputationInfo($status) {
    switch ($status) {
      case 'clean':
        $data = __("Don't worry, your reputation is good", 'wtotem');
        break;

      case 'infected':
        $data = __('Oh, your reputation is bad', 'wtotem');
        break;

      default:
        $data = __('Information is being updated', 'wtotem');
    }
    return $data;
  }

  /**
   * Get blacklists entries counts.
   *
   * @param string $status
   *   Reputation status.
   * @param array $virus_list
   *   Sources where the site can be blacklisted.
   *
   * @return int
   *   Number of references in blacklists.
   */
  public static function blacklistsEntries($status, array $virus_list) {
    $count = 0;
    if ($status != "clean") {
      foreach ($virus_list as &$list) {
        if (!empty($list['virus']['type'])) {
          $count++;
        }
      }
    }
    return $count;
  }

  /**
   * Classification of the rating in the letter grades.
   *
   * @param int $score
   *   Site rating from 1 to 100.
   *
   * @return array
   *   Returns an array of data.
   */
  public static function scoreGrading($score) {
    if ($score < 0 || $score > 100) {
      return ['grade' => '', 'color' => ''];
    }

    $scores = [
      100 => 'A+',
      90 => 'A',
      80 => 'A-',
      70 => 'B+',
      60 => 'B',
      50 => 'B-',
      35 => 'C+',
      20 => 'C',
      0 => 'C-',
    ];

    foreach ($scores as $key => $value) {
      if ($score >= $key) {
        $grade = $value;
        break;
      }
    }

    // Set a color depending on the grade.
    switch ($score) {
      case $score >= 80:
        $color = 'green';
        break;

      case $score >= 50:
        $color = 'orange';
        break;

      default:
        $color = 'red';
    }

    return ['grade' => $grade, 'color' => $color];
  }

  /**
   * Calculate the number of remaining days.
   *
   * @param string $date
   *   Expiry date.
   *
   * @return string
   *   Returns the number of days before the expiration date.
   */
  public static function daysLeft($date) {
    if ((int) $date === 0) {
      $days_left = 0;
    }
    else {
      $now = new \DateTime();
      $expiry_date = new \DateTime();
	    $timestamp = strtotime($date);
	    $expiry_date->setTimestamp($timestamp);
      $days_left = $expiry_date->diff($now)->format("%a");
    }
    return $days_left;
  }

  /**
   * Converting the firewall logs.
   *
   * @param array $logs_
   *   Firewall logs from WebTotem.
   *
   * @return array
   *   Converted array of logs.
   */
  public static function wafLogs(array $logs_) {
    $logs = [];
    foreach ($logs_ as $key => $log) {
      $log = $log['node'];

      $logs[$key]['ip'] = $log['ip'];
      $logs[$key]['request'] = htmlspecialchars(urldecode($log['request']));
      $logs[$key]['time'] = self::dateFormatter($log['time']);
      $logs[$key]['country_code'] = strtolower($log['country']);
      $logs[$key]['country'] = $log['location']['country']['nameEn'];
      $logs[$key]['blocked'] = $log['blocked'] ? __('Blocked IP', 'wtotem') : __('Not blocked', 'wtotem');

      $more = [
          'ip' => $log['ip'],
          'proxy_ip' => $log['proxyIp'],
          'source' => $log['source'],
          'request' => htmlspecialchars(urldecode($log['request'])),
          'user_agent' => $log['userAgent'],
          'time' => self::dateFormatter($log['time']),
          'type' => $log['type'],
          'category' => $log['category'],
          'country' => $log['location']['country']['nameEn'],
          'payload' => htmlspecialchars(urldecode($log['payload'])),
      ];

      $logs[$key]['more'] = json_encode($more);
    }
    return $logs;
  }

  /**
   * Converting firewall data to json for a D3 chart.
   *
   * @param array $charts
   *   Charts data from WebTotem.
   *
   * @return array
   *   Returns the converted data for chart.
   */
  public static function generateWafChart(array $charts) {
    $sum = 0;
    foreach ($charts as $chart) {
      $sum += $chart['attacks'];
    }
    if ($sum == 0) {
      return ['chart' => FALSE, 'count_attacks' => 0, 'count_blocks' => 0];
    }

    // Get days count.
    $charts_ = $charts;
    $first = array_shift($charts_);
    $last = array_pop($charts_);
    $days = ceil((strtotime($last['time']) - strtotime($first['time'])) / 86400);

    // Set variables.
    $count_attacks = $count_blocks = 0;

    foreach ($charts as $chart) {
      if ($days <= 1) {
        $time_zone = WebTotemOption::getOption('time_zone_offset');
        $userTime = ($time_zone) ? strtotime($time_zone . ' hours', strtotime($chart['time'])) : strtotime($chart['time']);
      }
      if (($chart['attacks'] and $days == 2) or $days != 2) {
        $result[] = [
          'date' => ($days <= 1) ? date("Y-m-d H:00:00", $userTime) : date("Y-m-d", strtotime($chart['time'])),
          'count' => $chart['blocked'],
          'attacks' => $chart['attacks'],
          'blocked' => $chart['blocked'],
        ];
        $count_attacks += $chart['attacks'];
        $count_blocks += $chart['blocked'];
      }
    }

    if (!isset($result)) {
      return [
        'chart' => FALSE,
        'count_attacks' => 0,
        'count_blocks' => 0,
	      'days' => 0,
      ];
    }

    return [
      'chart' => json_encode($result),
      'count_attacks' => $count_attacks,
      'count_blocks' => $count_blocks,
      'days' => $days,
    ];
  }

  /**
   * Converting data to json for a D3 chart.
   *
   * @param array $charts
   *   Charts data from WebTotem.
   * @param int $days
   *   The number of days to build the chart.
   *
   * @return bool|string
   *   Returns the converted data for chart.
   */
  public static function generateChart(array $charts, $days = 7) {
    $sum = 0;
    foreach ($charts as $chart) {
      $sum += $chart['value'];
    }
    if ($sum == 0) {
      return FALSE;
    }

    $result = [];

    foreach ($charts as $chart) {
      if ($days <= 1) {
        $time_zone = WebTotemOption::getOption('time_zone_offset');
        $userTime = ($time_zone) ? strtotime($time_zone . 'hours', strtotime($chart['time'])) : strtotime($chart['time']);
      }
      $result[] = [
        'date' => ($days <= 1) ? date("Y-m-d H:00:00", $userTime) : date("Y-m-d", strtotime($chart['time'])),
        'value' => $chart['value'],
      ];
    }

    return json_encode($result, TRUE);
  }

  /**
   * Converting data to json for a D3 chart.
   *
   * @param array $data
   *   Charts data from WebTotem.
   *
   * @return array|bool
   *   Returns the converted data for chart.
   */
  public static function generateAttacksMapChart(array $data) {
    $attacks = [];
	  $countries = [];
	  $labels = [];
    foreach ($data as $value) {
      $attacks[] = $value['attacks'];
      $labels[] = self::getCountryName($value['country']);
	  $countries[] = $value['location']['country']['nameEn'];
    }
    $result = ['attacks' => $attacks, 'countries' => $countries, 'labels' => $labels];

    if (!$attacks) {
      return FALSE;
    }

    return json_encode($result, TRUE);
  }

  /**
   * Reassembling the antivirus logs.
   *
   * @param array $logs_
   *   Antivirus logs from WebTotem.
   *
   * @return array
   *   Reassembled array of logs.
   */
  public static function getAntivirusLogs(array $logs_) {
    $logs = [];
    foreach ($logs_ as $key => $log) {
      $log = $log['node'];

	    $file_info = new SplFileInfo(urldecode($log['filePath']));

      $log['original_path'] = $log['filePath'];
	    $log['file_path'] = $file_info->getPath() . '/';
	    $log['file_name'] = $file_info->getFilename();
      $log['time'] = self::dateFormatter($log['time']);
      $log['permissions_changed'] = $log['permissionsChanged'];
      $log['status'] = self::getStatusData($log['event']);
      $log['class'] = 'wt-text--green';

	    switch ($log['event']) {
		    case 'modified':
		    case 'quarantine':
			    $log['class'] = "wt-text--yellow";
			    break;

		    case 'deleted':
			    $log['class'] = "wt-text--light-gray";
			    break;

		    case 'infected':
			    $log['class'] = "wt-text--red";
			    break;
	    }

      $logs[$key] = $log;
    }
    return $logs;
  }

    /**
     * Get open path data.
     *
     * @param array $_ports
     *   Open ports array.
     *
     * @return array
     *   Reassembled array of open ports.
     */
    public static function getOpenPortsData($ports) {
        if(!$ports){
            return [];
        }
        foreach ($ports as $key => $port) {
            $summary = '';
            if($port['cveList']){
                foreach ($port['cveList'] as $item){
                    $summary .= '<p>' . $item['summary'] . '</p>';
                }
            }
            $ports[$key]['cve_summary'] = $summary;
        }
        return $ports;
    }


  /**
   * Reassembling the quarantine logs.
   *
   * @param array $logs_
   *   Quarantine logs from WebTotem.
   *
   * @return array
   *   Reassembled array of logs.
   */
  public static function getQuarantineLogs(array $logs_) {
    $logs = [];
    foreach ($logs_ as $key => $log) {
      $logs[$key] = $log;
      $logs[$key]['path'] = urldecode($log['path']);
      $logs[$key]['date'] = self::dateFormatter($log['date']);
    }

    return $logs;
  }

  /**
   * Generate an array of IP address data.
   *
   * @param array $data
   *   IP addresses data from WebTotem.
   * @param string $list_name
   *   Allow or deny list.
   *
   * @return array
   *   Returns array of data.
   */
  public static function getIpList(array $data, $list_name) {
    $list = [];
    foreach ($data as $item) {
      $list[] = [
        'ip' => $item['ip'],
        'id' => $item['id'],
        'created_at' => self::dateFormatter($item['createdAt']),
        'list_name' => $list_name,
      ];
    }
    return $list;
  }

  /**
   * Generate an array of URL address data.
   *
   * @param array $data
   *   URL addresses data from WebTotem.
   *
   * @return array
   *   Returns array of data.
   */
  public static function getUrlAllowList(array $data) {
    $list = [];
    foreach ($data as $item) {
      $list[] = [
        'url' => $item['url'],
        'id' => $item['id'],
        'created_at' => self::dateFormatter($item['createdAt']),
        'list_name' => 'url_allow',
      ];
    }
    return $list;
  }

  /**
   * Convert IP list to be transferred to WebTotem.
   *
   * @param string $data
   *   IP list.
   *
   * @return string
   *   Returns the converted string.
   */
  public static function convertIpListForApi($data) {
    if (!$data) {
      return FALSE;
    }

    $ips = preg_split("/(?(?=[\s,])[^.]|^$)/", $data);

    if (is_array($ips)) {
      $ips_ = '[';
      foreach ($ips as $ip) {
        if (!empty($ip)) {
          $ips_ .= '"' . $ip . '",';
        }
      }
      $ips_ = substr($ips_, 0, -1);
      $ips_ .= ']';
    }
    else {
      $ips_ = '"' . $ips . '"';
    }

    return $ips_;
  }

  /**
   * Get data of the country with the most attacks.
   *
   * @param array $map
   *   Map logs from WebTotem.
   *
   * @return array
   *   Returns array of data.
   */
  public static function getMostAttacksData($map) {

    if ($map) {
      $most_attacks_key = array_search(max(array_column($map, 'attacks')), array_column($map, 'attacks'));
	    $total_attacks = array_sum(array_column($map, 'attacks'));

      $data['percent'] = ($total_attacks) ? round($map[$most_attacks_key]['attacks'] / $total_attacks * 100) : 0;
      $data['country'] = self::getCountryName($map[$most_attacks_key]['country']);
      $data['offset'] = 176 / 100 * (100 - $data['percent']);

      return $data;
    }

    return ['percent' => 0, 'country' => FALSE, 'offset' => 0];
  }

    /**
     * Get data on the three most attacking countries.
     *
     * @param array $map
     *   Map logs from WebTotem.
     *
     * @return array
     *   Returns array of data.
     */
    public static function getTreeMostAttacksData($map) {
        $total_attacks = array_sum(array_column($map, 'attacks'));

        if ($map) {
            array_multisort (array_column($map, 'attacks'), SORT_DESC, $map);
            $data = array_slice($map, 0, 3);

            foreach ($data as $key => $value){
                $data[$key]['percent'] = round($value['attacks'] / $total_attacks * 100);
                $data[$key]['country'] = self::getCountryName($value['country']);
            }

            return $data;
        }

        return [];
    }

  /**
   * Getting the country name by two-letter code.
   *
   * @param string $key
   *   Two-letter code.
   *
   * @return string
   *   Returns country name.
   */
  public static function getCountryName($key) {
    $countries = WebTotemCountryManager::getStandardList();
    $key = (string) $key;

    return (array_key_exists($key, $countries)) ? $countries[$key] : $key;
  }

	/**
	 * Get configs data.
	 *
	 * @param array $array
	 *   Original array.
	 * @param string $key
	 *   The key to use as an index.
	 *
	 * @return array
	 *   Configs data array.
	 */
	public static function getConfigsData(array $array, $key) {
		$configs = self::arrayMapIndex($array, $key);

		foreach ($configs as $service => $config){
			$configs[$service]['checked'] = ($config['isActive']) ? 'checked' : '';
			$configs[$service]['notification_checked'] = (isset($config['notifications']) && $config['notifications']) ? 'checked' : '';
		}

		return $configs;
	}

	/**
	 * Get waf setting data.
	 *
	 * @param array $settings
	 *   Original array.
	 *
	 * @return array
	 *   Configs data array.
	 */
	public static function getWafSettingData(array $settings) {
		$_settings['gdn']['checked'] = (isset($settings['gdn']) && !$settings['gdn']) ? '' : 'checked';
		$_settings['dos'] = [
			'checked'  => (isset($settings['dosProtection']) && !$settings['dosProtection']) ? '' : 'checked',
			'visually' => (isset($settings['dosProtection']) && !$settings['dosProtection']) ? 'visually-hidden' : '',
		];
		$_settings['dos_limit'] = $settings['dosLimit'] ?: 1000;

		$_settings['login_attempt'] = [
			'checked'  => (isset($settings['loginAttemptsProtection']) && !$settings['loginAttemptsProtection']) ? '' : 'checked',
			'visually' => (isset($settings['loginAttemptsProtection']) && !$settings['loginAttemptsProtection']) ? 'visually-hidden' : '',
		];
		$_settings['login_attempt_limit'] = $settings['loginAttemptsLimit'] ?: 20;

		return $_settings;
	}

	/**
	 * Get plugin settings data.
	 *
	 * @return array
	 *   Configs data array.
	 */
	public static function getPluginSettingsData() {

		$settings = WebTotemOption::getPluginSettings();
		$_settings = $settings;

		$_settings['hide_wp_version_checked'] = (array_key_exists('hide_wp_version', $settings) and $settings['hide_wp_version']) ? 'checked' : '';
        $_settings['recaptcha_checked'] =  (array_key_exists('recaptcha', $settings) and $settings['recaptcha']) ? 'checked' : '';
        $_settings['two_factor_checked'] =  (array_key_exists('two_factor', $settings) and $settings['two_factor']) ? 'checked' : '';

		return $_settings;
	}

	/**
   * Replace array indexes by key.
   *
   * @param array $array
   *   Original array.
   * @param string $key
   *   The key to use as an index.
   *
   * @return array
   *   Returns a new array.
   */
  public static function arrayMapIndex(array $array, $key) {
    $new_array = [];
    foreach ($array as $item) {
      if (array_key_exists($key, $item)) {
        $new_array[$item[$key]] = $item;
      }
    }
    return $new_array;
  }

  /**
   * Generate random string.
   *
   * @param int $length
   *   The required length of the string.
   *
   * @return string
   *   Returns random string.
   */
  public static function generateRandomString( int $length = 10): string {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

	/**
     * Encodes the less than, greater than, ampersand,double quote
     * and single quote characters. Will never double encode entities.
     *
     * @see https://developer.wordpress.org/reference/functions/esc_attr/
     *
     * @param  string $text
     *     The text which is to be encoded.
     *
     * @return string
     *    The encoded text with HTML entities.
     */
    public static function escape($text = '') {
        return esc_attr($text);
    }

    /**
     * Throw generic exception.
     *
     * @throws Exception
     *
     * @param  string $message
     *   Error or information message.
     * @param  string $type
     *   Either info or error.
     *
     * @return bool
     *    False all the time, used for debug.
     */
    public static function throwException($message, $type = 'error') {
        if (defined('WTOTEM_THROW_EXCEPTIONS') && WTOTEM_THROW_EXCEPTIONS === true && is_string($message) ) {
            $message = str_replace( '<strong>WebTotem:</strong>', ($type === 'error' ? __('Error:', 'wtotem') : __('Info:', 'wtotem')), $message );
            throw new Exception($message, $type === 'error' ? 157 : 333);
        }
        return false;
    }

    /**
     * Get audit logs data
     *
     * @return array
     */
    public static function getAuditLogs($data, $dates_count) {
        $logs = [];
        foreach ($data as $datum){
            $date_time = strtotime($datum['created_at']);
            $date = date_i18n('M j, Y', $date_time);

            $logs[$date]['date'] = $date;
            $logs[$date]['count'] = $dates_count[$date];
            $logs[$date]['logs'][] = [
                'time' => date_i18n('H:i', $date_time),
                'user_name' => $datum['user_name'],
                'status' => $datum['status'],
                'title' => $datum['title'],
                'event' => $datum['event'],
                'description' => $datum['description'],
                'ip' => $datum['ip'],
                'viewed' => (int) !$datum['viewed']
            ];
        }
        return $logs;
    }

  /**
   * Get confidential files data
   *
   * @return array
   */
  public static function getConfidentialFiles($data) {
    foreach ($data as $key => $datum){
      $data[$key]['modified_at'] = date_i18n('M j, Y \/ H:i', strtotime($datum['modified_at']));
      $data[$key]['size'] = self::humanFilesize($datum['size']);
	    $data[$key]['name'] = urldecode($datum['name']);
	    $data[$key]['path'] = urldecode($datum['path']);
    }
    return $data;
  }

    /**
     * Building navigation and forming a template
     *
     * @param integer $limit
     *  number of entries per 1 page
     * @param integer $count_all
     *  total number of all entries
     * @param integer $currentPage
     *  the number of the page being viewed
     * @param integer $nextPrev
     *  Show the "Forward" and "Back" buttons
     * @return mixed
     *  Generated navigation template ready for output
     */
    public static function paginationBuild($limit, $count_all, $currentPage = 1, $nextPrev = true) {
        if( $limit < 1 OR $count_all <= $limit ) return '';
        $count_pages = ceil( $count_all / $limit );

        $spread = 3;
        $separator = "<i>...</i>";
        $wrap = "<div class=\"wtotem_pagination\">{pages}</div>";

        $nextTitle = '←';
        $prevTitle = '→';

        $currentPage = intval( $currentPage );
        if( $currentPage < 1 ) $currentPage = 1;

        $shift_start = max( $currentPage - $spread, 2 );
        $shift_end = min( $currentPage + $spread, $count_pages-1 );
        if( $shift_end < $spread * 2 ) {
            $shift_end = min( $spread * 2, $count_pages-1 );
        }
        if( $shift_end == $count_pages - 1 AND $shift_start > 3 ) {
            $shift_start = max( 3, min( $count_pages - $spread * 2 + 1, $shift_start ) );
        }

        $list = self::getPaginationItem( 1, $currentPage );

        if ($shift_start == 3) {
            $list .= self::getPaginationItem( 2, $currentPage );
        } elseif ( $shift_start > 3 ) {
            $list .= $separator;
        }

        for( $i = $shift_start; $i <= $shift_end; $i++ ) {
            $list .= self::getPaginationItem( $i, $currentPage );
        }

        $last_page = $count_pages - 1;
        if( $shift_end == $last_page-1 ){
            $list .= self::getPaginationItem( $last_page, $currentPage );
        } elseif( $shift_end < $last_page ) {
            $list .= $separator;
        }

        $list .= self::getPaginationItem( $count_pages, $currentPage );

        if( $nextPrev ) {
            $list = self::getPaginationItem(
                    $currentPage > 1 ? $currentPage - 1 : 0,
                    $currentPage,
                    $nextTitle,
                    true )
                . $list
                . self::getPaginationItem(
                    $currentPage < $count_pages ? $currentPage + 1 : 0,
                    $currentPage,
                    $prevTitle,
                    true
                );
        }

        return str_replace( "{pages}", $list, $wrap );
    }

    /**
     * Button/Link Formation
     * @param int $page_num
     *  page number
     * @param string $currentPage
     *  current page
     * @param string $page_name
     *  if specified, the text will be displayed instead of the page number
     * @return string
     *  span block with active page or link.
     */
    public static function getPaginationItem( $page_num, $currentPage, $page_name = '' ) {
        if($page_num === 0){return '';}
        $page_name = $page_name ?: $page_num;

        if( $currentPage == $page_num ) {
            return "<span class=\"wtotem_pagination__number wtotem_pagination__number_active\">{$page_name}</span>";
        } else {
            return "<a href=\"#\" data-page=\"{$page_num}\" class=\"wtotem_pagination__number\">{$page_name}</a>";
        }
    }

    /**
     * Get notifications array.
     *
     * @return array
     *   Returns notifications array.
     */
    public static function getNotifications() {

        $notifications_data = WebTotemOption::getNotificationsData();
        $notifications = [];

        foreach ($notifications_data as $notification) {
            switch ($notification['type']) {
                case 'error':
                    $image = 'alert-error.svg';
                    $class = 'wtotem_alert__title_red';
                    break;

                case 'warning':
                    $image = 'alert-warning.svg';
                    $class = 'wtotem_alert__title_yellow';
                    break;

                case 'success':
                    $image = 'alert-success.svg';
                    $class = 'wtotem_alert__title_green';
                    break;

                case 'info':
                    $image = 'info-blue.svg';
                    $class = 'wtotem_alert__title_blue';
                    break;
            }

            $notifications[] = [
                "text" => $notification['notice'],
                "id" => self::generateRandomString(8),
                "type" => self::getStatusText($notification['type']),
                "type_raw" => $notification['type'],
                "image" => $image,
                "class" => $class,
            ];
        }

        return $notifications;
    }

  /**
   * Get current agent installation statuses.
   *
   * @param array $agents_statuses
   *   Agents statuses got from the WebTotem API.
   *
   * @return array
   *   Returns an array with agent installation status data.
   */
  public static function getAgentsStatuses(array $agents_statuses) {
    $agents = ['am', 'waf', 'av'];
    $installing_statuses = [
      'not_installed',
      'installing',
      'internal_error',
      'update_error',
      'config_error',
      'session_error',
    ];

    $process_statuses = [];
    $option_statuses = [];

    foreach ($agents as $agent) {
      $status = WebTotemAgentManager::checkInstalledService($agent);
      $option_statuses[$agent] = $status['option_status'] ?: FALSE;

      if ($agent == 'am') {
        if ($status['file_status']) {
          $process_statuses[$agent] = 'installed';
        }
        else {
          $process_statuses[$agent] = 'failed';
        }
      }
      else {
        if ($status['file_status']) {
          if (in_array($agents_statuses[$agent], $installing_statuses)) {
            $process_statuses[$agent] = 'installing';
          }
          elseif ($agents_statuses[$agent] == 'agent_not_available') {
            $process_statuses[$agent] = 'failed';
          }
          else {
            $process_statuses[$agent] = 'installed';
          }
        }
        else {
          $process_statuses[$agent] = 'installing';
        }
      }
    }

    return [
      'process_statuses' => $process_statuses,
      'option_statuses' => $option_statuses,
    ];
  }

}
