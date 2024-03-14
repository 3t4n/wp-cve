<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}

/**
 * WebTotem API class.
 *
 * Mostly contains wrappers for API methods. Check and send methods.
 *
 * @version 1.0
 * @copyright (C) 2022 WebTotem team (http://wtotem.com)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 */
class WebTotemAPI extends WebTotem {

  /**
   * Method for getting an auth token.
   *
   * @param string $api_key
   *   Application programming interface key.
   *
   * @return bool|string
   *   Returns auth status
   */
  public static function auth($api_key) {
    $domain = WEBTOTEM_SITE_DOMAIN;

    if(substr($api_key, 1, 1) == "-"){
      $prefix = substr($api_key, 0, 1);
      if($api_url = self::getApiUrl($prefix)){
        WebTotemOption::setOptions(['api_url' => $api_url]);
      } else {
        WebTotemOption::setNotification('error', __('Invalid API key', 'wtotem'));
        return FALSE;
      }
      $api_key = substr($api_key, 2);
    }

    if(empty($api_key)) { return FALSE; }
    $payload = '{"query":"mutation{ guest{ apiKeys{ auth(apiKey:\"' . $api_key . '\", source:\"' . $domain . '\"),{ token{ value, refreshToken, expiresIn } } } } }"}';
    $result = self::sendRequest($payload, FALSE, TRUE);

    if (isset($result['data']['guest']['apiKeys']['auth']['token']['value'])) {
      $auth_token = $result['data']['guest']['apiKeys']['auth']['token'];
      WebTotemOption::login(['token' => $auth_token, 'api_key' => $api_key]);
      return 'success';
    } elseif($result['errors'][0]['message'] == 'INVALID_API_KEY') {
	    WebTotemOption::logout();
    }

    return FALSE;
  }

  /**
   * Method for getting API url.
   *
   * @param string $prefix
   *
   * @return string|bool
   *   API url
   */
  public static function getApiUrl($prefix){
    $urls = [
        'P' => '.wtotem.com',
        'C' => '.webtotem.kz',
    ];

    if(array_key_exists($prefix, $urls)){
      return 'https://api' . $urls[$prefix] . '/graphql';
    }
    return false;
  }

  /**
   * Get site info from API server.
   *
   * @param string $attempt
   *   Is the request an attempt to get host data.
   *
   * @return array
   *   Returns host data.
   */
  public static function siteInfo($attempt = FALSE) {

  	if(self::isMultiSite()){
		  $host['id'] = WebTotemOption::getSessionOption('host_id');

		  if ($host['id']) {
			  return $host;
		  }
	  }

    $host = WebTotemOption::getHost();

    if ($host['id']) {
      return $host;
    }

	  $all_sites = self::getSites(null, 1000000);
		if($all_sites){
			if(self::isMultiSite()) {
				$sites = get_sites();
				foreach ($sites as $site){
					$domain = untrailingslashit($site->domain . $site->path);
					self::addSite($domain, $all_sites);
				}

				if (!$attempt) {
					return self::siteInfo(TRUE);
				}
			}
			else {
				$domain = WEBTOTEM_SITE_DOMAIN;
				return self::addSite($domain, $all_sites);
			}
		}

    return [];
  }

	/**
	 * Method for adding a site to the WebTotem platform.
	 *
	 * @param string $domain
	 *   Domain to add.
	 * @param array $all_sites
	 *   Array with site data on the WebTotem platform.
	 *
	 * @return array
	 *   Returns host data.
	 */
	public static function addSite( $domain, $all_sites) {

		if(function_exists('idn_to_utf8')){
			$domain = idn_to_utf8($domain);
		}

		// Checking if the site has been added to the WebTotem.
		if(array_key_exists('edges', $all_sites)){

			foreach ($all_sites['edges'] as $site){
				$site = $site['node'];
				$hostname = untrailingslashit($site['hostname']);
				// If it added, save site data to DB.
				if($hostname == $domain or $hostname == 'www.' . $domain){
					WebTotemOption::setHost($site['hostname'], $site['id']);
					return [
						'id' => $site['id'],
						'name' => $site['hostname'],
					];
				}
			}

		}

    $scheme =  is_ssl() ? 'https' : 'http';

		// If the site is not added then try to add.
		$payload = '{"variables":{"input":{"title":"' . $domain . '","hostname":"' . $domain . '","configs":{"scheme":"' . $scheme . '","port":' . $_SERVER['SERVER_PORT'] . ',"wa":{},"dec":{},"ps":{}}}},"query":"mutation ($input: CreateSiteInput) { auth { sites { create(input: $input) { id hostname title } } } }"}';
		$add_site = self::sendRequest($payload, TRUE);
		if (isset($add_site['errors'])) {
			WebTotemOption::setNotification('error', __('Failed to add the site to the WebTotem platform.', 'wtotem'));
		}
		else {
			if($host = $add_site['data']['auth']['sites']['create']) {
				// If it added, save site ID.
				WebTotemOption::setHost($host['title'], $host['id']);
				return [
					'id' => $host['id'],
					'name' => $host['title'],
				];
			}
		}
		return [];
	}

	/**
	 * Get all sites from API.
	 *
	 * @param string $cursor
	 *   Mark for loading data.
	 * @param string $limit
	 *   Limit of sites to loading.
	 *
	 * @return array
	 *   Returns host data.
	 */
	public static function getSites($cursor = null, $limit = 15, $filter = false) {
		$cursor = ($cursor == null) ? 'null' : '\"' . $cursor . '\"';
		if(!$filter) {
			$filter = ($limit === 0) ? '' : 'pagination:{ first: ' . $limit . ', cursor: ' . $cursor . ' }';
		}

		$payload = '{"query":"query getSites { auth { viewer { sites { ...sites } } } } fragment sites on SiteQueries { list(filter: { ' . $filter . ' }) { pageInfo{ hasNextPage endCursor } edges { node { id hostname title createdAt ssl { status } availability { status } reputation { status } ports { status } deface { status } antivirus { status } firewall { status } maliciousScript { stack { name } } } } } }"}';
		$result = self::sendRequest($payload, true);

		if (isset($result['data']['auth']['viewer']['sites']['list']['edges'])) {
			return $result['data']['auth']['viewer']['sites']['list'];
		}

		return [];
	}

    /**
     * Method to get the agents file names and AM file link.
     *
     * @param string $host_id
     *   Host id on WebTotem.
     *
     * @return array
     *   Returns agents files data.
     */
    public static function getAgentsFiles($host_id) {

        if(WebTotem::isMultiSite()){
            $all_hosts = WebTotemOption::getOption('all_hosts');
            $all_hosts = $all_hosts ? json_decode($all_hosts, true) : [];

            $siteIdsArray = $all_hosts ? array_values($all_hosts) : [];
            $siteIds = $siteIdsArray ? addslashes(WebTotem::convertArrayToString($siteIdsArray)) : '';

            $payload = '{"query":"mutation { auth { am { installMultisite(mainSiteId: \"' . $host_id . '\", siteIds: [' . $siteIds . ']){ downloadLink, amFilename, wafFilename, avFilename } } } }"}';
						$response = self::sendRequest($payload, TRUE);

            if (isset($response['data']['auth']['am']['installMultisite'])) {
                return $response['data']['auth']['am']['installMultisite'];
            }
        }
        else {
            $payload = '{"query":"mutation { auth { am { install(siteId: \"' . $host_id . '\"){ downloadLink, amFilename, wafFilename, avFilename } } } }"}';
            $response = self::sendRequest($payload, TRUE);
            if (isset($response['data']['auth']['am']['install'])) {
                return $response['data']['auth']['am']['install'];
            }
        }
        return [];
    }

	/**
	 * Add secondary MultiSite host.
	 *
	 * @param $new_sites
	 *   An array with sites to add.
	 *
	 * @return void.
	 */
  public static function addMultiSiteNewSites($new_sites){
		// Host id of the main site in MultiSite network.
	  $main_host = WebTotemOption::getMainHost();

  	foreach ($new_sites as $site){
		  $all_sites = self::getSites(null, 1000000);
		  $host = self::addSite($site, $all_sites);
		  if(key_exists('id', $host)){
			  $payload = '{"query":"mutation { auth { am { addMultisiteHost(mainSiteId: \"' . $main_host['id'] . '\", siteId: \"' . $host['id'] . '\") } } }"}';

				$result = self::sendRequest($payload, TRUE);
				if(!$result['errors'][0]['message']){
					WebTotemOption::setNotification( 'info', __('A new website has been added: ', 'wtotem') . $site);
				}
		  }
  	}
  }

	/**
	 * Remove secondary MultiSite host.
	 *
	 * @param $host_id
	 *   Host id on WebTotem.
	 *
	 * @return bool
	 *    Returns result removing host.
	 */
	public static function removeMultiSiteHost($host_id){
		$payload = '{"query":"mutation { auth { am { removeMultisiteHost(siteId: \"' . $host_id . '\") } } }"}';
		$response = self::sendRequest($payload, TRUE);
        if (isset($response['data']['auth']['am']['removeSecondaryMultisiteHost'])) {
            return $response['data']['auth']['am']['removeSecondaryMultisiteHost'];
        }

        return false;
	}

  /**
   * Method to get agents (AM, WAF, AV) statuses.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   *
   * @return array
   *   Returns agents statuses data.
   */
  public static function getAgentsStatusesFromAPI($host_id) {
    $payload = '{"query":"query ($id: ID!) { auth { viewer { sites { one(id: $id) { agentManager { statuses { am { status } av { status } waf { status } } } } } } } }", "variables":{"id":"' . $host_id . '"}}';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['sites']['one']['agentManager']['statuses'])) {
      return $response['data']['auth']['viewer']['sites']['one']['agentManager']['statuses'];
    }

    return [];
  }

  /**
   * Method to get user time zone.
   *
   * @return string|bool
   *   Returns time zone data.
   */
  public static function getTimeZone() {
    $payload = '{"query":"query { auth { viewer{ timezone } } } "}';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['timezone'])) {
      return $response['data']['auth']['viewer']['timezone'];
    }
    return FALSE;
  }

  /**
   * Method for get all the site security data.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   * @param int|array $days
   *   For what period data is needed.
   *
   * @return array
   *   Returns all data.
   */
  public static function getAllData($host_id, $days = 7) {
    $language = WebTotem::getLanguage();
    $period = WebTotem::getPeriod($days);

    $payload = '{"query":"query($id: ID!, $dateRange: DateRangeInput!, $language: Language!, $dateRangeWeek: DateRangeInput!, $wafLogFilter: WafLogFilter!) { auth { viewer { sites { one(id: $id) { openPathSearch { time paths { httpCode severity path } } ports { status lastTest { time } ignorePorts TCPResults{ port technology version cveList{id summary } } UDPResults { port technology version cveList{id summary } } } domain { lastScanResult { isTaken hasSite redirectLink isLocal protection ips { ip location } status time  } } sslResults{ results{ certStatus certIssuerName certExpiryDate certIssueDate } } ssl { status daysLeft expiryDate issueDate } reputation { status lastTest { time } virusList { virus{ type path } antiVirus } } firewall { lastTest { time } logs(wafLogFilter: $wafLogFilter){ edges{ node{ type blocked payload ip proxyIp userAgent description source region signatureId location{ country{ nameEn } } time request status country category } } } map(dateRange: $dateRange) { attacks, country } status chart(dateRange: $dateRange) { time attacks blocked } report(dateRange: $dateRange) { time attacks ip } } serverStatus { info { phpVersion phpServerUser phpServerSoftware phpGatewayInterface phpServerProtocol osInfo cpuCount cpuModel CpuFreq cpuFamily lsCpu maxExecTime mathLibraries } ramChart(dateRange: $dateRangeWeek){ total value time } cpuChart(dateRange: $dateRangeWeek){ value time } discUsage{ total free } status } maliciousScript { lastTest { time } status } scoring( language: $language ){ score lastTest{ time } result{ ip country isHigherThan }} agentManager{ createdAt } antivirus { status stats { changed deleted scanned infected error } lastTest { time } isFirstCheck } } } } } }","variables":{"id":"' . $host_id . '","dateRange":{"to":' . $period['to'] . ',"from":' . $period['from'] . '}, "dateRangeWeek":{"to":' . $period['to'] . ',"from":' . $period['from'] . '}, "wafLogFilter": {"dateRange":{"to":' . $period['to'] . ',"from":' . $period['from'] . '},"order":{"direction":"DESC","field":"time"},"pagination":{"first": 10,"cursor":null}}, "language":"' . $language . '"}}';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['sites']['one'])) {
      return $response['data']['auth']['viewer']['sites']['one'];
    }

    return [];
  }


  /**
   * Method for get all the site security data.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   *
   * @return array
   *   Returns all data.
   */
  public static function getMonitoring($host_id) {

    $payload = '{"query":"query($id: ID!) { auth { viewer { sites { one(id: $id) {  domain { lastScanResult { isTaken hasSite redirectLink isLocal protection ips { ip location } status time  } } sslResults{ results{ certStatus certIssuerName certExpiryDate certIssueDate } } ssl { status daysLeft expiryDate issueDate } reputation { status lastTest { time } virusList { virus{ type path } antiVirus } } } } } } }","variables":{"id":"' . $host_id . '"}}';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['sites']['one'])) {
      return $response['data']['auth']['viewer']['sites']['one'];
    }

    return [];
  }

	/**
	 * Method to get firewall data.
	 *
	 * @param string $host_id
	 *   Host id on WebTotem.
	 * @param int $limit
	 *   Limit on the number of records.
	 * @param string $cursor
	 *   Mark for loading data.
	 * @param int|array $days
	 *   For what period data is needed.
	 *
	 * @return array
	 *   Returns firewall data.
	 */
	public static function getFirewall($host_id, $limit = 20, $cursor = NULL, $days = 365) {
		$period = WebTotem::getPeriod($days);
		$cursor = ($cursor == NULL) ? 'null' : '"' . $cursor . '"';

		$payload = '{"query":"query($id: ID!, $wafLogFilter: WafLogFilter!, $dateRange: DateRangeInput!) { auth { viewer { sites { one(id: $id) { firewall { lastTest { time } status map(dateRange: $dateRange) { attacks, country, location { country { nameEn } } } chart(dateRange: $dateRange) { time attacks blocked } ...FirewallLogFragment } agentManager { createdAt } } } } } } fragment FirewallLogFragment on Waf { logs(wafLogFilter: $wafLogFilter) { edges { cursor node { type blocked payload ip proxyIp userAgent description source region signatureId location { country { nameEn } } time request status country category } } pageInfo { endCursor hasNextPage } } }", "variables":{"dateRange":{"to":' . $period['to'] . ',"from":' . $period['from'] . '},"id":"' . $host_id . '","wafLogFilter":{"dateRange":{"to":' . $period['to'] . ',"from":' . $period['from'] . '},"order":{"direction":"DESC","field":"time"},"pagination":{"first":' . $limit . ',"cursor":' . $cursor . '}}} }';
		$response = self::sendRequest($payload, TRUE);

		if (isset($response['data']['auth']['viewer']['sites']['one'])) {
			return $response['data']['auth']['viewer']['sites']['one'];
		}

		return [];
	}

	/**
	 * Method to get firewall chart data.
	 *
	 * @param string $host_id
	 *   Host id on WebTotem.
	 * @param int $days
	 *   For what period data is needed.
	 *
	 * @return array
	 *   Returns firewall chart data.
	 */
	public static function getFirewallChart($host_id, $days = 7) {
		$period = WebTotem::getPeriod($days);

		$payload = '{ "query":"query($id: ID!, $dateRange: DateRangeInput!) { auth { viewer { sites { one(id: $id) { firewall { lastTest { time } status map(dateRange: $dateRange) { attacks, country, location { country { nameEn } } } chart(dateRange: $dateRange) { time attacks blocked } } } } } } }", "operationName":null,"variables":{"id":"' . $host_id . '","dateRange":{"to":' . $period['to'] . ',"from":' . $period['from'] . '} } }';
		$response = self::sendRequest($payload, TRUE);

		if (isset($response['data']['auth']['viewer']['sites']['one']['firewall'])) {
			return $response['data']['auth']['viewer']['sites']['one']['firewall'];
		}

		return [];
	}

  /**
   * Method to set firewall settings.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   * @param array $settings
   *   User-specified settings.
   *
   * @return array
   *   Returns information whether the request was successful.
   */
  public static function setFirewallSettings($host_id, array $settings) {
    $payload = '{"variables":{"input": {"siteId": "' . $host_id . '", "gdn": ' . $settings['gdn'] . ', "dosProtection": ' . $settings['dosProtection'] . ', "dosLimit": ' . $settings['dosLimit'] . ', "loginAttemptsProtection": ' . $settings['loginAttemptsProtection'] . ', "loginAttemptsLimit": ' . $settings['loginAttemptsLimit'] . '}},"query":"mutation WafSettings($input: WafSettingsInput!) { auth { sites { waf{ settings(input: $input) { gdn dosProtection loginAttemptsProtection dosLimit loginAttemptsLimit } } } } }"}';
    return self::sendRequest($payload, TRUE);
  }

  /**
   * Method to get antivirus data.
   *
   * @param array $params
   *   Parameters for filtering data.
   *
   * @return array
   *   Returns antivirus data.
   */
  public static function getAntivirus(array $params) {

    $cursor = ($params['cursor']) ? '"' . $params['cursor'] . '"' : 'null';
    $event = ($params['event']) ? '"' . $params['event'] . '"' : '"new"';
    $permissions = ($params['permissions']) ? ' "permissionsChanged":true, ' : '';
    $period = WebTotem::getPeriod($params['days']);

    $payload = '{"operationName":null,"variables":{"id":"' . $params['host_id'] . '","avLogFilter":{' . $permissions . '"event":' . $event . ', "dateRange":{"to":' . $period['to'] . ',"from":' . $period['from'] . '},"order":{"direction":"DESC","field":"time"},"pagination":{"first":' . $params['limit'] . ',"cursor":' . $cursor . '}}},"query":"query ($id: ID!, $avLogFilter: AvLogFilter!) { auth { viewer { sites { one(id: $id) { id ... on Site { configs { ... on AvConfig { isActive id } } } antivirus { quarantine{ id path date } status log(avLogFilter: $avLogFilter) { edges { node { filePath event signatures time permissions permissionsChanged } } pageInfo { endCursor hasNextPage  } } lastTest { time } stats { changed deleted scanned infected }  } } } } } }"}';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['sites']['one']['antivirus'])) {
      return $response['data']['auth']['viewer']['sites']['one']['antivirus'];
    }
    return [];
  }

  /**
   * Method to get antivirus last test.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   *
   * @return array
   *   Returns antivirus last test data.
   */
  public static function getAntivirusLastTest($host_id) {

    $payload = '{"variables":{"id":"' . $host_id . '"},"query":"query ($id: ID!) { auth { viewer { sites { one(id: $id) { antivirus { status  lastTest { time } } } } } } }"}';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['sites']['one']['antivirus'])) {
      return $response['data']['auth']['viewer']['sites']['one']['antivirus'];
    }
    return [];
  }

  /**
   * Method to force check services.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   * @param string $service
   *   Service that needs to be checked.
   *
   * @return array
   *   Returns information whether the request was successful.
   */
  public static function forceCheck($host_id, $service) {
    $payload = '{"variables":{"id":"' . $host_id . '","service":"' . $service . '"},"query":"mutation ($id: ID!, $service: ForceCheckService!) { auth { sites { forceCheck(siteId: $id, service: $service)  } } }"} ';
    return self::sendRequest($payload, TRUE);
  }

  /**
   * Method to export antivirus report.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   * @param int|array $days
   *   For what period data is needed.
   *
   * @return array
   *   Returns information whether the request was successful.
   */
  public static function avExport($host_id, $days = 30) {
    $period = WebTotem::getPeriod($days);
    $payload = '{"variables":{ "input":{"siteId":"' . $host_id . '", "dateRange":{"to":' . $period['to'] . ',"from":' . $period['from'] . '} }},"query":"mutation ($input: AvLogExportInput!) { auth { sites { av { export(input: $input) } } } }"} ';
    return self::sendRequest($payload, TRUE);
  }

  /**
   * Method to get quarantine data.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   *
   * @return array
   *   Returns quarantine data.
   */
  public static function getQuarantineList($host_id) {
    $payload = '{"query":"query{ auth{ viewer{ sites{ one(id:\"' . $host_id . '\"){ antivirus{ quarantine{ id path date } } } } } } } "}';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['sites']['one']['antivirus']['quarantine'])) {
      return $response['data']['auth']['viewer']['sites']['one']['antivirus']['quarantine'];
    }
    return [];
  }

  /**
   * Method to move file to quarantine.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   * @param string $path
   *   Path to the file.
   *
   * @return array
   *   Returns information whether the request was successful.
   */
  public static function moveToQuarantine($host_id, $path) {
    $payload = '{"query":"mutation{ auth{ sites{ av{ moveToQuarantine(input:{ siteId:\"' . $host_id . '\", path:\"' . $path . '\" }) } } } } "}';
    return self::sendRequest($payload, TRUE);
  }

  /**
   * Method to move file from quarantine.
   *
   * @param string $id
   *   Id assigned to the file.
   *
   * @return array
   *   Returns information whether the request was successful.
   */
  public static function moveFromQuarantine($id) {
    $payload = '{"query":"mutation{ auth{ sites{ av{ moveFromQuarantine(id: \"' . $id . '\") } } } } "}';
    return self::sendRequest($payload, TRUE);
  }

  /**
   * Method to get server status data.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   * @param int|array $days
   *   For what period data is needed.
   *
   * @return array
   *   Returns server status data.
   */
  public static function getServerStatusData($host_id, $days = 7) {
    $period = WebTotem::getPeriod($days);
    $payload = '{ "query":"query($id: ID!, $dateRange: DateRangeInput!) { auth { viewer { sites { one(id: $id) { serverStatus { info { phpVersion phpServerUser phpServerSoftware phpGatewayInterface phpServerProtocol osInfo cpuCount cpuModel CpuFreq cpuFamily lsCpu maxExecTime mathLibraries } ramChart(dateRange: $dateRange){ total value time } cpuChart(dateRange: $dateRange){ value time } } } } } } }", "variables":{"id":"' . $host_id . '","dateRange":{"to":' . $period['to'] . ',"from":' . $period['from'] . '} } }';

    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['sites']['one']['serverStatus'])) {
      return $response['data']['auth']['viewer']['sites']['one']['serverStatus'];
    }

    return [];
  }

  /**
   * Method to remove port from ignore list.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   * @param string $port
   *   User specified port.
   *
   * @return array
   *   Returns information whether the request was successful.
   */
  public static function removeIgnorePort($host_id, $port) {
    $payload = '{"variables":{ "input": { "siteId": "' . $host_id . '", "port":' . $port . '} },"query":"mutation($input: IgnorePortInput!) { auth { sites { ps { removeIgnorePort(input: $input) } } } }"} ';
    return self::sendRequest($payload, TRUE);
  }

  /**
   * Method to add port to ignore list.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   * @param string $port
   *   User specified port.
   *
   * @return array
   *   Returns information whether the request was successful.
   */
  public static function addIgnorePort($host_id, $port) {
    $payload = '{"variables":{ "input": { "siteId": "' . $host_id . '", "port":' . (int) $port . '} },"query":"mutation($input: IgnorePortInput!) { auth { sites { ps { addIgnorePort(input: $input) } } } }"} ';
    return self::sendRequest($payload, TRUE);
  }

  /**
   * Method to get all ports list.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   *
   * @return array
   *   Returns ports data.
   */
  public static function getAllPortsList($host_id) {
    $payload = '{"query":"query($id: ID!) { auth { viewer { sites { one(id: $id) { ports { status lastTest { time } ignorePorts TCPResults{ port technology version cveList{id summary } } UDPResults { port technology version cveList{id summary } } }  } } } } } ","variables":{"id":"' . $host_id . '"}}';

    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['sites']['one']['ports'])) {
      return $response['data']['auth']['viewer']['sites']['one']['ports'];
    }

    return [];
  }

    /**
     * Method to get all ports list.
     *
     * @param string $host_id
     *   Host id on WebTotem.
     *
     * @return array
     *   Returns ports data.
     */
    public static function getOpenPaths($host_id) {
        $payload = '{"query":"query($id: ID!) { auth { viewer { sites { one(id: $id) { openPathSearch { time paths { httpCode severity path } }  } } } } } ","variables":{"id":"' . $host_id . '"}}';

        $response = self::sendRequest($payload, TRUE);

        if (isset($response['data']['auth']['viewer']['sites']['one']['openPathSearch'])) {
            return $response['data']['auth']['viewer']['sites']['one']['openPathSearch'];
        }

        return [];
    }

  /**
   * Method to get all reports.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   * @param int $limit
   *   Limit on the number of records.
   * @param string $cursor
   *   Mark for loading data.
   *
   * @return array
   *   Returns reports data.
   */
  public static function getAllReports($host_id, $limit = 10, $cursor = NULL) {
    $cursor = ($cursor == NULL) ? 'null' : '"' . $cursor . '"';
    $payload = '{"variables":{"filter": { "order": { "direction": "DESC", "field": "created_at"}, "siteId":"' . $host_id . '", "pagination":{"first":' . $limit . ', "cursor":' . $cursor . '} } },"query":"query ReportsQuery($filter: ReportListFilter!) { auth { viewer { reports { list(filter: $filter) { edges { node { id site { hostname } createdAt wa dc ps rc sc av waf } cursor } pageInfo { endCursor hasNextPage } } } } } }"}';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['reports']['list']['edges'])) {
      return $response['data']['auth']['viewer']['reports']['list'];
    }

    return [];
  }

  /**
   * Method to generate report.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   * @param int|array $days
   *   For what period data is needed.
   * @param array $services
   *   User-specified module settings.
   *
   * @return string|bool
   *   Returns report download link.
   */
  public static function generateReport($host_id, $days, array $services) {
    $period = WebTotem::getPeriod($days);
    $language = WebTotem::getLanguage();

    $payload = '{"query":"query ($input: GenerateReportInput) { auth { viewer { reports { generate(input: $input) } } } }", "variables":{ "input": { "siteId": "' . $host_id . '", "from": ' . $period['from'] . ', "to": ' . $period['to'] . ', "wa": ' . $services['wa'] . ', "dc": ' . $services['dc'] . ', "ps": ' . $services['ps'] . ', "rc": ' . $services['rc'] . ', "sc": ' . $services['sc'] . ', "av": ' . $services['av'] . ', "waf": ' . $services['waf'] . ', "language": "' . $language . '" } } }';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['reports']['generate'])) {
      return $response['data']['auth']['viewer']['reports']['generate'];
    }

    return FALSE;
  }

  /**
   * Method to download report.
   *
   * @param string $id
   *   Assigned to the report.
   *
   * @return string|bool
   *   Returns report download link.
   */
  public static function downloadReport($id) {
    $payload = '{"query": "query { auth { viewer { reports { download(id: \"' . $id . '\") } } } }"}';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['reports']['download'])) {
      return $response['data']['auth']['viewer']['reports']['download'];
    }

    return FALSE;
  }

  /**
   * Method to get configs data.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   *
   * @return array|bool
   *   Returns configs data.
   */
  public static function getConfigs($host_id) {
    $payload = '{"query":"query{ auth{ viewer{ sites{ one(id:\"' . $host_id . '\"){ configs{ ... on WaConfig { id service isActive notifications } ... on WafConfig { id service isActive notifications } ... on AvConfig { id service isActive notifications } ... on DcConfig { id service isActive notifications } ... on DecConfig { id service isActive } ... on RcConfig { id service isActive notifications} ... on CmsConfig { id service isActive } ... on PsConfig { id service isActive notifications } ... on SsConfig { id service isActive } ... on ScConfig { id service isActive } } } } } } }  "}';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['sites']['one']['configs'])) {
      return $response['data']['auth']['viewer']['sites']['one']['configs'];
    }

    return FALSE;
  }

  /**
   * Method to toggle modules config.
   *
   * @param string $service_id
   *   Service id that we enable or disable.
   *
   * @return string|bool
   *   Returns information whether the request was successful.
   */
  public static function toggleConfigs($service_id) {
    $payload = '{"query":"mutation{ auth{ configs{ toggle(id: \"' . $service_id . '\"){ ... on WaConfig { service isActive } ... on AvConfig { service isActive } ... on DcConfig { service isActive } ... on DecConfig { service isActive } ... on RcConfig { service isActive } ... on CmsConfig { service isActive } ... on PsConfig { service isActive } ... on WafConfig { service isActive } } } } }   "}';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['configs']['toggle'])) {
      return $response['data']['auth']['configs']['toggle'];
    }

    return FALSE;
  }

  /**
   * Method to toggle modules notification.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   * @param string $service
   *   Service id in which we enable or disable notifications.
   *
   * @return string|bool
   *   Returns information whether the request was successful.
   */
  public static function toggleNotifications($host_id, $service) {
    $payload = '{"query":"mutation{ auth{ sites{ toggleNotifications(siteId: \"' . $host_id . '\", service: ' . $service . ') } } }"}';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['sites']['toggleNotifications'])) {
      return $response;//['data']['auth']['sites']['toggleNotifications'];
    }

    return FALSE;
  }

  /**
   * Method to get allow/deny ip list.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   *
   * @return array|bool
   *   Returns ip allow/deny lists.
   */
  public static function getIpLists($host_id) {
    $payload = '{"variables":{ "id": "' . $host_id . '" },"query":"query($id: ID!) { auth { viewer { sites{ one(id: $id){ firewall{ blackList{ id ip createdAt } whiteList{ id ip createdAt } settings{ gdn dosProtection dosLimit loginAttemptsProtection loginAttemptsLimit } } } } } } }"} ';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['sites']['one']['firewall'])) {
      return $response['data']['auth']['viewer']['sites']['one']['firewall'];
    }

    return [];
  }

  /**
   * Method to add ip to allow/deny list.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   * @param string $ips
   *   Ip address list.
   * @param string $list
   *   Allow or deny list.
   *
   * @return bool
   *   Returns information whether the request was successful.
   */
  public static function addIpToList($host_id, $ips, $list) {

    if ($ips) {
      $ips = WebTotem::convertIpListForApi($ips);
      $payload = '{"variables":{ "input": { "siteId": "' . $host_id . '", "ips": ' . $ips . ', "color": "' . $list . '" } }, "query":"mutation($input: WafListInput!) { auth { sites { waf { addToList(input: $input){ status invalidIPs} } } } }"} ';
      $response = self::sendRequest($payload, TRUE);

      if (isset($response['data']['auth']['sites']['waf']['addToList'])) {
        return $response['data']['auth']['sites']['waf']['addToList'];
      }
    }

    return FALSE;
  }

  /**
   * Method to remove ip from allow/deny list by id.
   *
   * @param string $id
   *   Id assignment to ip address.
   *
   * @return bool
   *   Returns information whether the request was successful.
   */
  public static function removeIpFromList($id) {
    $payload = '{"variables":{ "id": "' . $id . '" },"query":"mutation($id: ID!) { auth { sites { waf { removeFromList(id: $id) } } } }"} ';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['sites']['waf']['removeFromList'])) {
      return $response['data']['auth']['sites']['waf']['removeFromList'];
    }

    return FALSE;
  }

  /**
   * Method to get allow url list.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   *
   * @return array
   *   Returns url allow lists.
   */
  public static function getAllowUrlList($host_id) {
    $payload = '{"query":"query { auth { viewer { sites { one(id: \"' . $host_id . '\"){ firewall{ urlWhiteList{ id url createdAt } } } } } } }"} ';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['viewer']['sites']['one']['firewall']['urlWhiteList'])) {
      return $response['data']['auth']['viewer']['sites']['one']['firewall']['urlWhiteList'];
    }

    return [];
  }

  /**
   * Method to add url to allow list.
   *
   * @param string $host_id
   *   Host id on WebTotem.
   * @param string $url
   *   User-specified url.
   *
   * @return bool|string
   *   Returns information whether the request was successful.
   */
  public static function addUrlToAllowList($host_id, $url) {
    $payload = '{"variables":{ "input": { "siteId": "' . $host_id . '", "url": "' . $url . '" } }, "query":"mutation($input: WafUrlWhiteListInput!) { auth { sites { waf { addToUrlWhiteList(input: $input) } } } }"} ';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['sites']['waf']['addToUrlWhiteList'])) {
      return $response['data']['auth']['sites']['waf']['addToUrlWhiteList'];
    }

    return FALSE;
  }

  /**
   * Method to remove url from allow list.
   *
   * @param string $id
   *   Id assignment to url address.
   *
   * @return bool|string
   *   Returns information whether the request was successful.
   */
  public static function removeUrlFromAllowList($id) {
    $payload = '{"variables":{ "id": "' . $id . '" }, "query":"mutation($id: ID!) { auth { sites { waf { removeFromUrlWhiteList(id: $id) } } } }"} ';
    $response = self::sendRequest($payload, TRUE);

    if (isset($response['data']['auth']['sites']['waf']['removeFromUrlWhiteList'])) {
      return $response['data']['auth']['sites']['waf']['removeFromUrlWhiteList'];
    }

    return FALSE;
  }

    /**
     * Method to get blocked countries list.
     *
     * @param string $host_id
     *   Host id on WebTotem.
     *
     * @return array
     *   Returns blocked countries list.
     */
    public static function getBlockedCountries($host_id) {
        $period = WebTotem::getPeriod(7);
        $payload = '{"variables":{"dateRange":{"to":' . $period['to'] . ',"from":' . $period['from'] . '}} , "query":"query($dateRange: DateRangeInput!){ auth { viewer { sites { one(id: \"' . $host_id . '\"){ firewall{ blockedCountries map(dateRange: $dateRange) { attacks, country, location { country { nameEn } } }  } } } } } }"}';
        $response = self::sendRequest($payload, TRUE);

        if (isset($response['data']['auth']['viewer']['sites']['one']['firewall'])) {
            return $response['data']['auth']['viewer']['sites']['one']['firewall'];
        }

        return [];
    }

    /**
     * Method for synchronizing data on the list of blocked countries.
     *
     * @param string $host_id
     *   Host id on WebTotem.
     * @param array $countries
     *   Array of countries to block.
     *
     * @return bool|string
     *   Returns information whether the request was successful.
     */
    public static function syncBlockedCountries($host_id, $countries) {

        $countries = $countries ? WebTotem::convertArrayToString($countries) : '';
        $payload = '{"variables":{ "input": { "siteId": "' . $host_id . '", "countries": [' . $countries . '] } }, "query":"mutation($input: WafBlockedCountriesInput!) { auth { sites { waf { syncBlockedCountries(input: $input) } } } }"} ';
        $response = self::sendRequest($payload, TRUE);

        if (isset($response['data']['auth']['sites']['waf']['syncBlockedCountries'])) {
            return $response['data']['auth']['sites']['waf']['syncBlockedCountries'];
        }

        return FALSE;
    }

  /**
   * Method to get user's email.
   *
   * @return string
   *   Returns user's email.
   */
  public static function getEmail(){
      $payload = '{"query":"query { auth { viewer { email  }  } }"}';
      $response = self::sendRequest($payload, true);

      return $response['data']['auth']['viewer']['email'];
  }

  /**
   * Method to get user's feedback.
   *
   * @return array
   */
  public static function getFeedback(){
    return self::sendFeedbackRequest("GET");
  }

  /**
   * Method to set user's feedback.
   *
   * @return array
   */
  public static function setFeedback($data){
    return self::sendFeedbackRequest("POST", $data);
  }

  /**
   * Function sends data request to endpoint.
   *
   * @param array $data
   *   Data array to be sent to endpoint.
   *
   * @return array
   *   Returns response from WebTotem endpoint.
   */
  protected static function sendFeedbackRequest($method, $data = []) {
    $url = 'https://nps.dev.wtotem.paas.tsarka.net/user-score';
    $email = WebTotemOption::getOption( "user_email" );
    if(!$email){
      if(WebTotemOption::isActivated()) {
        $email = WebTotemAPI::getEmail();
      }
      WebTotemOption::setOptions(['user_email' => $email]);
    }

    if(!$email){
      WebTotemOption::setNotification('error', __( 'First you need to log in', 'wtotem' ));
      return [];
    }

    if($method == "GET"){

      $args = [
        'timeout' => '30',
        'sslverify' => FALSE,
      ];

      $response = wp_remote_get($url . '?email=' . urlencode($email), $args);

    } else {
      $data['email'] = $email;
      $data['platform'] = 'WORDPRESS';
      $data = json_encode($data);

      $args = [
        'body' => $data,
        'timeout' => '30',
        'sslverify' => FALSE,
        'headers' => [
          'Content-Type' => 'application/json',
        ],
      ];

      $response = wp_remote_post($url, $args);
    }

    $http_code = wp_remote_retrieve_response_code($response);

    if ($http_code < 200) {
      WebTotemOption::setNotification('error', __( 'Could not connect to feedback endpoint.', 'wtotem' ));
      return [];
    }

    $response_body = wp_remote_retrieve_body($response);
    return json_decode($response_body, true);
  }

  /**
   * Function sends GraphQL request to API server.
   *
   * @param string $payload
   *   Payload to be sent to API server.
   * @param bool $token
   *   Whether a token is needed when sending a request.
   * @param bool $repeat
   *   Required to avoid recursion.
   *
   * @return array
   *   Returns response from WebTotem API.
   */
  protected static function sendRequest($payload, $token = FALSE, $repeat = FALSE) {

    $api_key = WebTotemOption::getOption('api_key');

    // Remote URL where the public WebTotem API service is running.
    $api_url = WebTotemOption::getOption('api_url');
    if(!$api_url){
      $api_url = self::getApiUrl('P');
      WebTotemOption::setOptions(['api_url' => $api_url]);
    }

    // Checking whether a token is needed.
    if ($token) {
      $auth_token = WebTotemOption::getOption('auth_token');
      $auth_token_expired = WebTotemOption::getOption('auth_token_expired');

      // Checking whether the token has expired.
      if ($auth_token_expired <= time() && !$repeat) {
        $result = self::auth($api_key);
        if ($result === 'success') {
          return self::sendRequest($payload, $token, TRUE);
        }
        else {
        	if(isset($result['errors'])){
		        $message = WebTotem::messageForHuman($result['errors'][0]['message']);
		        WebTotemOption::setNotification('error', $message);
	        }
        }
      }
    }

    if (function_exists('wp_remote_post')) {

	    $args = [
		    'body' => $payload,
		    'timeout' => '60',
		    'sslverify' => false,
		    'headers' => [
		    	'Content-Type:application/json',
			    'Content-Type' => 'application/json',
			    'Accept: application/json',
			    'source: WORDPRESS',
		    ],
	    ];

	    if (isset($auth_token)) {
		    $auth = "Bearer " . $auth_token;
		    $args['headers'] = array_merge($args['headers'], ["Authorization" => $auth]);
	    }

	    $response = wp_remote_post($api_url, $args);
	    $response = wp_remote_retrieve_body($response);
	    $response = json_decode($response, true);

    }
    else {
      $error = 'WP_REMOTE_POST_NOT_EXIST';
    }

    // Checking if there are errors in the response.
    if (isset($response['errors'][0]['message'])) {
      $message = WebTotem::messageForHuman($response['errors'][0]['message']);
      if (stripos($response['errors'][0]['message'], "INVALID_TOKEN") !== FALSE && !$repeat) {
        $response = self::auth($api_key);
        if ($response === 'success') {
          return self::sendRequest($payload, $token, TRUE);
        }
      }
      elseif(stripos($response['errors'][0]['message'], "USERHOST_NOT_BELONG_TO_USER") !== FALSE){
          if(WebTotem::isMultiSite()){
              WebTotemOption::clearAllHosts();
              WebTotemOption::clearOptions([ 'host_id', 'host_name' ]);
          } else {
              WebTotemOption::clearOptions([ 'host_id', 'host_name' ]);
          }
      }
      else {
        WebTotemOption::setNotification('error', $message);
      }
    }

    if (!empty($error)) {
      WebTotemOption::setNotification('error', $error);
    }

    return  $response;
  }

}
