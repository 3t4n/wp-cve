<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	exit(1);
}

/**
 * WebTotem Option class.
 */
class WebTotemOption {

  /**
   * Get config option.
   *
   * @param string $option
   *   Option name.
   *
   * @return mixed
   *   Returns saved data by option name.
   */
  public static function getOption($option) {
    $data = WebTotemDB::getData([ 'name' => $option ],'settings');
    return (array_key_exists('value', $data)) ? $data['value'] : '';
  }

  /**
   * Save multiple configuration options.
   *
   * @param array $options
   *   Array of data, key is name of option.
   *
   * @return bool
   *   Returns TRUE after setting the options.
   */
  public static function setOptions(array $options) {

    foreach ($options as $option => $value) {
	    $value = is_array($value) ? json_encode($value) : $value;
	    WebTotemDB::setData(['name' => $option, 'value' => $value,], 'settings', ['name' => $option]);
    }

    return TRUE;
  }

  /**
   * Clear multiple configuration options.
   *
   * @param array $options
   *   Array of data, key is name of option.
   *
   * @return bool
   *   Returns TRUE after clearing the options.
   */
  public static function clearOptions(array $options) {

    foreach ($options as $option) {
	    WebTotemDB::deleteData([ 'name' => $option ], 'settings');
    }

    return TRUE;
  }

  /**
   * Save multiple some options to session.
   *
   * @param array $options
   *   Array of data, key is name of option.
   *
   * @return bool
   *   Returns TRUE after setting the session options.
   */
  public static function setSessionOptions(array $options) {

	  $sessions = json_decode(self::getOption('sessions'), true) ?: [];
	  $user_id = get_current_user_id();

		foreach ($options as $option => $value){
			$sessions[$user_id][$option] = $value;
		}

		self::setOptions(['sessions' => $sessions]);

    return TRUE;
  }

  /**
   * Get option from session.
   *
   * @param string $option
   *   Option name.
   *
   * @return mixed
   *   Returns saved data by option name.
   */
  public static function getSessionOption($option) {

	  $sessions = json_decode(self::getOption('sessions'), true) ?: [];
	  $user_id = get_current_user_id();

		if(array_key_exists($user_id, $sessions) and array_key_exists($option, $sessions[$user_id])){
			return $sessions[$user_id][$option];
		} else {
			return [];
		}

  }

	/**
	 * Save multiple some plugin settings.
	 *
	 * @param array $options
	 *   Array of data, key is name of option.
	 *
	 * @return bool
	 *   Returns TRUE after save settings.
	 */
	public static function setPluginSettings(array $options) {

		$settings = json_decode(self::getOption('settings'), true) ?: [];

		foreach ($options as $option => $value){
			$settings[$option] = $value;
		}

		self::setOptions(['settings' => $settings]);

		return TRUE;
	}

	/**
	 * Get plugin settings.
	 *
	 * @param string $option
	 *   Option name.
	 *
	 * @return mixed
	 *   Returns saved data by option name.
	 */
	public static function getPluginSettings($option = null) {

		$settings = json_decode(self::getOption('settings'), true) ?: [];

		if($option){
			if(array_key_exists($option, $settings)){
				return $settings[$option];
			} else {
				return [];
			}
		} else{
			return $settings;
		}
	}


	/**
	 * Check has reCaptcha enabled.
	 *
	 * @return bool
	 *   Returns TRUE if reCaptcha enabled.
	 */
	public static function reCaptchaEnabled() {
		return self::getPluginSettings('recaptcha') ?: false;
	}


	/**
   * Save authentication token and token expiration dates in settings.
   *
   * @param array $params
   *   Parameters for authorization.
   *
   * @return string
   *   Returns TRUE after setting the options.
   */
  public static function login(array $params) {
    $token_expired = time() + $params['token']['expiresIn'] - 60;

    self::setOptions([
      'activated' => TRUE,
      'auth_token_expired' => $token_expired,
      'auth_token' => $params['token']['value'],
      'api_key' => $params['api_key'],
      'multisite_options' => WebTotem::isMultiSite()
    ]);

    return TRUE;
  }

  /**
   * Checks whether the user has activated the plugin using the API key.
   *
   * @return bool
   *   Returns the module activation status.
   */
  public static function isActivated() {
    return (boolean) self::getOption('activated');
  }

  /**
   * Remove module settings.
   *
   * @return string
   *   Returns TRUE after clearing the options.
   */
  public static function logout() {

    self::clearOptions([
      'activated',
      'auth_token_expired',
      'auth_token',
      'api_key',
      'api_url',
      'host_id',
      'host_name',
    ]);
    return TRUE;
  }

  /**
   * Set notification.
   *
   * @param string $type
   *   Notification Type.
   * @param string $notice
   *   Notification Text.
   */
  public static function setNotification($type, $notice) {
    $notifications = self::getSessionOption('notifications') ?: [];

    if (array_key_exists($type, $notifications)) {
      if (!in_array($notice, $notifications[$type])) {
        $notifications[$type][] = $notice;
        self::setSessionOptions(['notifications' => $notifications]);
      }
    }
    else {
      $notifications[$type][] = $notice;
      self::setSessionOptions(['notifications' => $notifications]);
    }

  }

  /**
   * Get notifications.
   *
   * @return array
   *   Notifications array.
   */
  public static function getNotificationsData() {
    $types = ['error', 'info', 'warning', 'success'];

    $notifications = self::getSessionOption('notifications') ?: [];
	  $result = [];

    foreach ($types as $type) {
      if (array_key_exists($type, $notifications)) {
        foreach ($notifications[$type] as $notification) {
          $result[] = ['type' => $type, 'notice' => $notification];
        }
      }
    }

    // Remove notifications.
    self::setSessionOptions(['notifications' => []]);

    return $result;
  }

	/**
	 * Set host data.
	 *
	 * @return void
	 */
	public static function setHost($host_name, $host_id) {

		if(WebTotem::isMultiSite()){
			$blog_id = self::getBlogId($host_name);

			add_blog_option($blog_id, 'wtotem_host_id', $host_id);
			add_blog_option($blog_id, 'wtotem_host_name', $host_name);

			if(!is_main_site($blog_id)){
				$all_hosts = json_decode(self::getOption('all_hosts'), true) ?: [];
				$all_hosts[$host_name] = $host_id;

				self::setOptions([
					'all_hosts' => $all_hosts,
				]);
			} else {
				self::setOptions([
					'host_id' => $host_id,
					'host_name' => $host_name,
				]);
			}

		} else {
			self::setOptions([
				'host_id' => $host_id,
				'host_name' => $host_name,
			]);
		}
	}

	/**
	 * Get host data.
	 *
	 * @param string $hid
	 *   Host id.
	 *
	 * @return array
	 *   Host data.
	 */
	public static function getHost($hid = false) {

		if ( $hid ) {
			$all_hosts = self::getAllHosts() ?: [];
			if ( $all_hosts and in_array( $hid, $all_hosts ) ) {
				return [
					'id'   => $hid,
					'name' => array_search( $hid, $all_hosts ),
				];
			}
		}

		return self::getMainHost();
	}

	/**
	 * Get host data.
	 *
	 * @return array
	 *   Host data.
	 */
	public static function getAllHosts() {
		$all_hosts = json_decode(self::getOption('all_hosts'), true) ?: [];

		$main_host = self::getMainHost();
		$all_hosts = ($main_host['id']) ? [$main_host['name'] => $main_host['id']] + $all_hosts : $all_hosts;

		return $all_hosts;
	}

	/**
	 * Get main host data.
	 *
	 * @return array
	 *   Main host data.
	 */
	public static function getMainHost() {

		return [
			'id' => self::getOption('host_id'),
			'name' => self::getOption('host_name'),
		];

	}

	/**
	 * Delete host data from DB.
	 *
	 * @return void
	 */
	public static function clearAllHosts() {

		$data = WebTotemAPI::getSites();
		foreach ($data['edges'] as $site) {
			$site = $site['node'];
			$blog_id = self::getBlogId($site['hostname']);
			delete_blog_option($blog_id, 'wtotem_host_id');
			delete_blog_option($blog_id, 'wtotem_host_name');
		}

	}

	/**
	 * Get an array of new sites.
	 *
	 * @return array
	 *   Returns either an empty array or an array with new sites.
	 */
//	public static function checkNewSites() {
//		$hosts = self::getAllHosts();
//		$sites = get_sites();
//		$new_sites = [];
//
//		foreach ($sites as $site){
//			$host_name = untrailingslashit($site->domain . $site->path);
//			if(!array_key_exists($host_name, $hosts) and !array_key_exists('www.' . $host_name, $hosts)) {
//				$new_sites[] = $host_name;
//			}
//		}
//		return $new_sites;
//	}

	/**
	 * Get host id from host name.
	 *
	 * @param $host_name
	 *   Host name.
	 *
	 * @return integer
	 *   Blog id.
	 */
  public static function getBlogId($host_name){
    $local_sites = get_sites();

    foreach ($local_sites as $site){
      $domain = untrailingslashit($site->domain . $site->path);
      if($host_name == $domain){
        return $site->blog_id;
      }
    }
    return 0;
  }

	/**
	 * Get all config options name.
	 *
	 * @return array
	 *   Returns saved data by option name.
	 */
	public static function getAllOptions() {
		return [
			'api_key',
			'activated',
			'auth_token_expired',
			'auth_token',
			'am_file',
			'waf_file',
			'av_file',
			'am_installed',
			'av_installed',
			'waf_installed',
			'time_zone_check',
			'time_zone_offset',
			'all_hosts',
			'plugin_version',
			'sessions',
			'multisite_options',

			'host_id',
			'host_name',
		];
	}

	/**
	 * Checking the old version of options.
	 *
	 * @return boolean
	 *   If there are old options, it will return true.
	 */
	public static function checkOldOptions() {

		// Creating a database with plugin settings.
		if(WebTotemDB::install()){

			$api_key = get_option('wtsec_api_key');
			$am_file = get_option('wtsec_am_installed_file');
			$waf_file = get_option('wtsec_waf_installed_file');

			if($api_key){
				self::setOptions([
					'api_key' => $api_key,
					'am_file' => $am_file,
					'waf_file' => $waf_file,
					'activated' => true,
					'am_installed' => true,
					'av_installed' => true,
					'waf_installed' => true,
				]);

				$old_options = [
					'api_key',
					'api_key_safe',
					'api_key_activated',
					'authorized',
					'authToken',
					'waf_installed_file',
					'av_installed_file',
					'am_installed_file',
					'am_installed',
					'logout',
					'av_installed',
					'waf_installed',
					'agents_installed',
					'api_url',
					'color_scheme' ,
					'time_zone',
					'token_expired',
					'deactivated',
					'antivirus_event',
					'antivirus_permissions_changed',
					'antivirus_endCursor',
					'antivirus_hasNextPage',
					'firewall_endCursor',
					'firewall_hasNextPage',
					'reports_endCursor',
					'reports_hasNextPage'
				];

				foreach ($old_options as $option) {
					delete_option('wtsec_' . $option);
					delete_site_option('wtsec_' .$option);
				}

			}

			$api_key = get_site_option('wtotem_api_key');
			$am_file = get_site_option('wtotem_am_installed_file');
			$waf_file = get_site_option('wtotem_waf_installed_file');

			if($api_key){
				self::setOptions([
					'api_key' => $api_key,
					'am_file' => $am_file,
					'waf_file' => $waf_file,
					'activated' => true,
					'am_installed' => true,
					'av_installed' => true,
					'waf_installed' => true,
				]);

				foreach (self::getAllOptions() as $option) {
					delete_option('wtotem_' . $option);
					delete_site_option('wtotem_' .$option);
				}
			}
		}

		return true;
	}

	/**
	 * Check multisite.
	 */
	public static function multisiteCheck() {
		// Check the transition to/from the multisite.
		if ( ( WebTotem::isMultiSite() && ! WebTotemOption::getOption( 'multisite_options' ) ) or
		     ( ! WebTotem::isMultiSite() && WebTotemOption::getOption( 'multisite_options' ) ) ) {

			self::setOptions([ 'multisite_options' => WebTotem::isMultiSite() ]);

			if(WebTotem::isMultiSite()){
				WebTotemOption::clearAllHosts();
				WebTotemOption::clearOptions([ 'host_id', 'host_name' ]);
			} else {
				WebTotemOption::clearOptions([ 'host_id', 'host_name' ]);
			}

			WebTotemAgentManager::removeAgents();
		}
	}

	/**
	 * Hide readme file
	 * @param string $readmeFile
	 * @return bool
	 */
	public static function hideReadme($readmeFile = null) {
		if ($readmeFile === null) {
            $readmeFile = ABSPATH . '/readme.html';
		}

		if (file_exists($readmeFile)) {
			$readmePathInfo = pathinfo($readmeFile);
			require_once(ABSPATH . WPINC . '/pluggable.php');
			$hiddenReadmeFile = $readmePathInfo['filename'] . '.' . wp_hash('readme') . '.' . $readmePathInfo['extension'];
			return @rename($readmeFile, $readmePathInfo['dirname'] . '/' . $hiddenReadmeFile);
		}

		return false;
	}

	/**
	 * Restore readme file
	 * @param string $readmeFile
	 * @return bool
	 */
	public static function restoreReadme($readmeFile = null) {
		if ($readmeFile === null) {
			$readmeFile = ABSPATH . '/readme.html';
		}
		$readmePathInfo = pathinfo($readmeFile);
		require_once(ABSPATH . WPINC . '/pluggable.php');
		$hiddenReadmeFile = $readmePathInfo['dirname'] . '/' . $readmePathInfo['filename'] . '.' . wp_hash('readme') . '.' . $readmePathInfo['extension'];
		if (file_exists($hiddenReadmeFile)) {
			return @rename($hiddenReadmeFile, $readmeFile);
		}

		return false;
	}
	/**
	 * Hide WP version
	 * @return void
	 */
	public static function hideWPVersion() {
		global $wp_version;
		global $wp_styles;

		if (!($wp_styles instanceof WP_Styles)) {
			$wp_styles = new WP_Styles();
		}
		if ($wp_styles->default_version === $wp_version) {
			$wp_styles->default_version = wp_hash($wp_styles->default_version);
		}

		foreach ($wp_styles->registered as $key => $val) {
			if ($wp_styles->registered[$key]->ver === $wp_version) {
				$wp_styles->registered[$key]->ver = wp_hash($wp_styles->registered[$key]->ver);
			}
		}

		global $wp_scripts;
		if (!($wp_scripts instanceof WP_Scripts)) {
			$wp_scripts = new WP_Scripts();
		}
		if ($wp_scripts->default_version === $wp_version) {
			$wp_scripts->default_version = wp_hash($wp_scripts->default_version);
		}

		foreach ($wp_scripts->registered as $key => $val) {
			if ($wp_scripts->registered[$key]->ver === $wp_version) {
				$wp_scripts->registered[$key]->ver = wp_hash($wp_scripts->registered[$key]->ver);
			}
		}
	}

    public static function replaceVersion($url) {
        return preg_replace_callback("/([&;\?]ver)=(.+?)(&|$)/", "WebTotemOption::replaceVersionCallback", $url);
    }

    public static function replaceVersionCallback($matches) {
        global $wp_version;
        return $matches[1] . '=' . ($wp_version === $matches[2] ? wp_hash($matches[2]) : $matches[2]) . $matches[3];
    }

    /**
     * Check the nonce comming from any of the settings pages.
     *
     * @return bool True if the nonce is valid, false otherwise.
     */
    public static function checkOptionsNonce() {
        // Create the option_page value if permalink submission.
        if (!isset($_POST['option_page']) && isset($_POST['permalink_structure'])) {
            $_POST['option_page'] = 'permalink';
        }

        /* check if the option_page has an allowed value */
        $option_page = WebTotemRequest::post('option_page');

        if (!$option_page) {
            return false;
        }

        $action = '';
        $nonce = '_wpnonce';

        switch ($option_page) {
            case 'general':
            case 'writing':
            case 'reading':
            case 'discussion':
            case 'media':
            case 'options':
                $action = $option_page . '-options';
                break;
            case 'permalink':
                $action = 'update-permalink';
                break;
        }

        /* check the nonce validity */
        return (bool) (
            !empty($action)
            && isset($_REQUEST[$nonce])
            && wp_verify_nonce($_REQUEST[$nonce], $action)
        );
    }

    /**
     * Retrieve all the options stored by Wordpress in the database.
     *
     * @return array All the options stored by Wordpress in the database.
     */
    private static function getSiteOptions() {
        $settings = array();

        if (array_key_exists('wpdb', $GLOBALS)) {
            $results = $GLOBALS['wpdb']->get_results(
                'SELECT * FROM ' . $GLOBALS['wpdb']->options . ' WHERE option_name NOT LIKE "%_transient_%" ORDER BY option_id ASC'
            );

            foreach ($results as $row) {
                $settings[$row->option_name] = $row->option_value;
            }
        }

        return $settings;
    }

    /**
     * Check what Wordpress options were changed comparing the values in the database
     * with the values sent through a simple request using a GET or POST method.
     *
     * @param  array $request The content of the global variable GET or POST considering SERVER[REQUEST_METHOD].
     * @return array          A list of all the options that were changes through this request.
     */
    public static function whatOptionsWereChanged($request = array())
    {
        $options_changed = [ 'original' => [], 'changed' => [] ];

        $site_options = self::getSiteOptions();

        foreach ($request as $req_name => $req_value) {
            if (array_key_exists($req_name, $site_options) && $site_options[ $req_name ] != $req_value ) {
                $options_changed['original'][ $req_name ] = $site_options[ $req_name ];
                $options_changed['changed'][ $req_name ] = $req_value;
            }
        }

        return $options_changed;
    }


}
