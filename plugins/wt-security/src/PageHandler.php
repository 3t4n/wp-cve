<?php

/**
 * Load page and ajax handlers
 */

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}

/**
 * Handles all the AJAX plugin's requests.
 *
 * @return void
 */
function wtotem_ajax_callback() {

	if (WebTotemRequest::get('ajax_action') != NULL) {
		WebTotemAjax::wtotem_scan();
	}

    $composer_autoload = WEBTOTEM_PLUGIN_PATH . '/vendor/autoload.php';
    if ( file_exists( $composer_autoload ) ) {
        require_once $composer_autoload;
    }

    if (WebTotemRequest::post('ajax_action') != NULL) {
        WebTotemAjax::authenticate();
    }

	if (WebTotemRequest::post('ajax_action') != NULL && WebTotemInterface::checkNonce()) {

		WebTotemAjax::activation();
		WebTotemAjax::agentsInstallation();
		WebTotemAjax::reinstallAgents();
        WebTotemAjax::chart();
        WebTotemAjax::logs();
		WebTotemAjax::wafDateFilter();
		WebTotemAjax::ignorePorts();
		WebTotemAjax::lazyLoad();
		WebTotemAjax::antivirus();
		WebTotemAjax::changeThemeMode();
		WebTotemAjax::userTimeZone();
		WebTotemAjax::quarantine();
		WebTotemAjax::reports();
		WebTotemAjax::settings();
		WebTotemAjax::remove();
		WebTotemAjax::reloadPage();
		WebTotemAjax::logout();
		WebTotemAjax::popup();
		WebTotemAjax::multisite();
        WebTotemAjax::twoFactorAuth();
        WebTotemAjax::force_check();
    	WebTotemAjax::user_feedback();
	}

	wp_send_json([
		'success' => false,
		'error' => 'invalid ajax request',
		'notifications' => WebTotemAjax::notifications(),
	], 200);
}

/**
 * Handles all the AJAX plugin's public requests.
 *
 * @return void
 */
function wtotem_public_ajax_callback() {

	if (WebTotemRequest::post('ajax_action') != NULL) {
		WebTotemAjax::authenticate();
	}

	wp_send_json([
		'success' => false,
		'error' => 'invalid ajax request',
	], 200);

}

/**
 * Activation page.
 *
 * @return void
 */
function wtotem_activation_page() {

	$build[] = [
		'variables' => [
			'notifications' => WebTotem::getNotifications(),
			'current_year' => date('Y'),
      		'page' => 'activation',
		],
		'template' => 'activation'
	];

	$template = new WebTotemTemplate();
	echo $template->arrayRender($build);
}

/**
 * All sites page.
 *
 * @return void
 */
function wtotem_all_sites_page() {

	$allSites = WebTotemAPI::getSites(null, 1000000);

	// Reset session data.
	WebTotemOption::setSessionOptions([
		'sites_cursor' => $allSites['pageInfo']['endCursor'],
	]);


	$build[] = [
		'variables' => [
			'notifications' => WebTotem::getNotifications(),
			'current_year' => date('Y'),
			'sites' => WebTotem::allSitesData($allSites),
			'theme_mode' => WebTotem::getThemeMode()
		],
		'template' => 'multisite'
	];

	$template = new WebTotemTemplate();
	$page_content = $template->arrayRender($build);
	echo $template->baseTemplate($page_content);
}

/**
 * Error page.
 *
 * @return void
 */
function wtotem_error_page(){
	$template = new WebTotemTemplate();
	$build[] = [
		'template' => 'error',
	];
	$page_content = $template->arrayRender($build);
	echo $template->baseTemplate($page_content);
}

/**
 * Dashboard, main page.
 *
 * @return void
 */
function wtotem_dashboard_page() {

	if(WebTotemRequest::get('hid')){
		$host = WebTotemOption::getHost(WebTotemRequest::get('hid'));
	} else {
		$host = WebTotemAPI::siteInfo();
	}

	$template = new WebTotemTemplate();
	if (!isset($host['id']) or !$host['id']) {
		wtotem_error_page();
		exit();
	}

	// Get data from WebTotem API.
	if($cacheData = WebTotemCache::getdata('getAllData', $host['id'])){
		$data = $cacheData['data'];
	} else {
		$data = WebTotemAPI::getAllData($host['id']);
		WebTotemCache::setData(['getAllData' => $data], $host['id']);
	}

	if (empty($data)) {
		wtotem_error_page();
		exit();
	}

	// MultiSite page header (site name)
	if(WebTotem::isMultiSite() and is_super_admin()){
		// Submenu block.
		$pages['dashboard'] = 'wtotem_page-header__link_active';

		$build[] = [
			'variables' => [
				'is_active' => $pages,
				'site_name' => $host['name'],
				'hid' => $host['id'],
			],
			'template' => 'multisite_submenu',
		];
	}

	// Reset session data.
	WebTotemOption::setSessionOptions([
		'firewall_period' => NULL,
		'ram_period' => NULL,
		'cpu_period' => NULL,
	]);

	// Scoring block.
	$service_data = $data['scoring']['result'];
	$total_score = round($data['scoring']['score']);
	$score_grading = WebTotem::scoreGrading($total_score);
	$build[] = [
		'variables' => [
			"host_id" => $host['id'],
			"total_score" => $total_score . "%",
			"tested_on" => WebTotem::dateFormatter($data['scoring']['lastTest']['time']),
			"server_ip" => $service_data['ip'] ?: ' - ',
			"location" => WebTotem::getCountryName($service_data['country']) ?: ' - ',
			"is_higher_than"  => $service_data['isHigherThan'] . '%',
			"grade" => $score_grading['grade'],
			"color" => $score_grading['color'],
		],
		'template' => 'score',
	];

	// Agents installing process.
	$agents_data = [
		'av' => $data['antivirus']['status'],
		'waf'  => $data['firewall']['status'],
	];

	$agents_statuses = WebTotem::getAgentsStatuses($agents_data);

	if (!$agents_statuses['option_statuses']['av'] or !$agents_statuses['option_statuses']['waf']) {

		$status = [
			'av' => $agents_statuses['process_statuses']['av'] == 'installed',
			'waf' => $agents_statuses['process_statuses']['waf'] == 'installed',
		];

		WebTotemOption::setOptions([
			'av_installed' => $status['av'],
			'waf_installed' => $status['waf'],
		]);

		$build[] = [
			'variables' => [
				"process_status" => $agents_statuses['process_statuses'],
			],
			'template' => 'agents',
		];
	}

	// Firewall header.
	$build[] = [
		'variables' => [
			"title" => __('Firewall activity', 'wtotem'),
		],
		'template' => 'section_header',
	];

	$is_period_available = WebTotem::isPeriodAvailable($data['agentManager']['createdAt']);

	// Firewall stats.
	$service_data = (isset($data['firewall'])) ? $data['firewall'] : [];
	$chart = WebTotem::generateWafChart($service_data['chart']);
	$build[] = [
		'variables' => [
			"is_waf_training" => $data['agentManager'] && WebTotem::isWafTraining( $data['agentManager']['createdAt'] ),
			"is_period_available" => $is_period_available,
			"most_attacks" => WebTotem::getMostAttacksData($service_data['map']),
			"all_attacks" => $chart['count_attacks'],
			"blocking" => $chart['count_blocks'],
			"not_blocking" => (int) $chart['count_attacks'] - (int) $chart['count_blocks'],
		],
		'template' => 'firewall_stats',
	];

	// Firewall filter form
	$build[] = [
		'variables' => [
				"is_period_available" => $is_period_available,
		],
		'template' => 'waf_filter_form',
	];

	// Firewall blocks.
	$build[] = [
		'variables' => [
			"chart" => $chart['chart'],
			"logs"  => WebTotem::wafLogs($service_data['logs']['edges']),
            'host_name' => $host['name'],
		],
		'template' => 'firewall',
	];

	// Display AV and SS data only to the super admin, or it's not a MultiSite network.
	if(!WebTotem::isMultiSite() or is_super_admin()) {

		// Server Status header.
		$build[] = [
			'variables' => [
				"title" => __('Server resources', 'wtotem'),
				"tooltip" => [
					'title' => __('Server resources', 'wtotem'),
					'test' => __('Displays critical data about web-server usage. A large load on a server can slow down the website performance.', 'wtotem'),
				],
			],
			'template' => 'section_header',
		];

		// Server Status RAM.
		$service_data = $data['serverStatus'];
		$build[] = [
			'variables' => [
				"is_period_available" => $is_period_available,
				"info" => $service_data['info'],
				"ram_chart" => WebTotem::generateChart($service_data['ramChart']),
			],
			'template' => 'server_status_ram',
		];

		// Server Status CPU.
		$build[] = [
			'variables' => [
				"is_period_available" => $is_period_available,
				"cpu_chart" => WebTotem::generateChart($service_data['cpuChart']),
			],

			'template' => 'server_status_cpu',
		];

		// Antivirus header.
		$build[] = [
			'variables' => [
				"title" => __('Antivirus', 'wtotem'),
			],
			'template' => 'section_header',
		];

		// Antivirus stats blocks.
		$antivirus_stats = $data['antivirus']['stats'];
		$build[] = [
			'variables' => [
				"changes"  => $antivirus_stats['changed'] ?: 0,
				"scanned"  => $antivirus_stats['scanned'] ?: 0,
				"deleted"  => $antivirus_stats['deleted'] ?: 0,
				"infected" => $antivirus_stats["infected"] ?: 0,
			],

			'template' => 'antivirus_stats',
		];
	}

	// Monitoring header.
	$build[] = [
		'variables' => [
			"title" => __('Monitoring', 'wtotem'),
		],
		'template' => 'section_header',
	];

  $ssl = false;
  if ($data['sslResults']['results']) {
    $ssl = [
      'status' => WebTotem::getStatusData($data['sslResults']['results'][0]['certStatus']),
      'cert_name' => $data['sslResults']['results'][0]['certIssuerName'],
      'days_left' => WebTotem::daysLeft($data['sslResults']['results'][0]['certExpiryDate']),
      'issue_date' => WebTotem::dateFormatter($data['sslResults']['results'][0]['certIssueDate']),
      'expiry_date' => WebTotem::dateFormatter($data['sslResults']['results'][0]['certExpiryDate']),
    ];
  }
  $domain = false;
  if (WebTotem::isKz()) {
    $domain = [
      'status' => WebTotem::getStatusData($data['domain']['lastScanResult']['status']),
      "redirect_link" => $data['domain']['lastScanResult']['redirectLink'],
      "is_created_at" => (bool)$data['domain']['lastScanResult']['time'],
      "created_at" => WebTotem::dateFormatter($data['domain']['lastScanResult']['time']),
      "is_taken" => $data['domain']['lastScanResult']['isTaken'],
      "ips" => $data['domain']['lastScanResult']['ips'],
      "protection" => $data['domain']['lastScanResult']['protection'],
    ];
  }

	// Monitoring blocks.
	$build[] = [
		'variables' => [
			"ssl"  => $ssl,
			"domain"  => $domain,
			'reputation' => [
				"status" => WebTotem::getStatusData($data['reputation']['status']),
				"blacklists_entries" => WebTotem::blacklistsEntries(
					$data['reputation']['status'],
					$data['reputation']['virusList']),
				"info" => WebTotem::getReputationInfo($data['reputation']['status']),
				"last_test" => WebTotem::dateFormatter($data['reputation']['lastTest']['time']),
			],
		],
		'template' => 'monitoring',
	];

	$build[] = [
		'variables' => [
			"ports" => WebTotemAPI::getAllPortsList($host['id']),
		],
		'template' => 'ports_form',
	];

	// Scanning header.
	$build[] = [
		'variables' => [
			"title" => __('Scanning', 'wtotem'),
		],
		'template' => 'section_header',
	];


	// Scanning blocks.
	$build[] = [
		'variables' => [
			"ports"  => [
				'status' => WebTotem::getStatusData($data['ports']['status']),
				"TCPResults" => WebTotem::getOpenPortsData($data['ports']['TCPResults']),
				"ignore_ports" => $data['ports']['ignorePorts'],
				"last_test" => WebTotem::dateFormatter($data['ports']['lastTest']['time']),
			],
            "open_path"  => [
                'status' => WebTotem::getStatusData(($data['openPathSearch']['paths']) ? 'warning' : 'clean'),
                "last_test" => WebTotem::dateFormatter($data['openPathSearch']['time']),
                "paths" => $data['openPathSearch']['paths'],
            ],
		],
		'template' => 'scanning',
	];

	$page_content = $template->arrayRender($build);
	echo $template->baseTemplate($page_content);
}

/** Open paths page.
 *
 * @return void
 */
function wtotem_open_paths_page() {

    if(WebTotemRequest::get('hid')){
        $host = WebTotemOption::getHost(WebTotemRequest::get('hid'));
    } else {
        $host = WebTotemAPI::siteInfo();
    }

    $template = new WebTotemTemplate();
    if (!isset($host['id']) or !$host['id']) {
        wtotem_error_page();
        exit();
    }

    // Get data from WebTotem API.
    if($cacheData = WebTotemCache::getdata('getOpenPaths', $host['id'])){
        $open_path = $cacheData['data'];
    } else {
        $open_path = WebTotemAPI::getOpenPaths($host['id']);;
        WebTotemCache::setData(['getOpenPaths' => $open_path], $host['id'], 1);
    }

    $build[] = [
        'variables' => [
            "paths" => $open_path['paths'],
        ],
        'template' => 'open_paths_page',
    ];

    $page_content = $template->arrayRender($build);
    echo $template->baseTemplate($page_content);

}

/** Firewall page.
 *
 * @return void
 */
function wtotem_firewall_page() {

	if(WebTotemRequest::get('hid')){
		$host = WebTotemOption::getHost(WebTotemRequest::get('hid'));
	} else {
		$host = WebTotemAPI::siteInfo();
	}

	$template = new WebTotemTemplate();
	if (!isset($host['id']) or !$host['id']) {
		wtotem_error_page();
		exit();
	}

	// Get data from WebTotem API.
	if($cacheData = WebTotemCache::getdata('getFirewall', $host['id'])){
		$data = $cacheData['data'];
	} else {
		$data = WebTotemAPI::getFirewall($host['id'], 10, NULL, 7);
		WebTotemCache::setData(['getFirewall' => $data], $host['id'], 1);
	}

	if (empty($data)) {
		wtotem_error_page();
		exit();
	}

	$service_data = $data['firewall'];

	// Reset session data.
	WebTotemOption::setSessionOptions([
		'firewall_period' => NULL,
		'firewall_cursor' => $service_data['logs']['pageInfo']['endCursor'],
	]);

	// MultiSite page header (site name)
	if(WebTotem::isMultiSite() and is_super_admin()){
		// Submenu block.
		$pages['firewall'] = 'wtotem_page-header__link_active';

		$build[] = [
			'variables' => [
				'is_active' => $pages,
				'site_name' => $host['name'],
				'hid' => $host['id'],
			],
			'template' => 'multisite_submenu',
		];
	}

	// Start build array for rendering.
	// Firewall header.
	$build[] = [
		'variables' => [
			"title" => __('Firewall activity', 'wtotem'),
		],
		'template' => 'section_header',
	];

	// Attacks map blocks.
	// Get world_map json data
	$world_map_json = WEBTOTEM_URL . '/includes/js/world_map.json';
	$map_data = WebTotem::generateAttacksMapChart($service_data['map']);
	$is_period_available = WebTotem::isPeriodAvailable($data['agentManager']['createdAt']);

	$build[] = [
		'variables' => [
			"is_period_available"   => $is_period_available,
			"attacks_map" => $map_data,
			"world_map_json" => $world_map_json,
		],
		'template' => 'attacks_map',
	];

	// Firewall stats.
	$chart = WebTotem::generateWafChart($service_data['chart']);
	$build[] = [
		'variables' => [
			"is_waf_training" => isset( $data['agentManager']['createdAt'] ) && WebTotem::isWafTraining( $data['agentManager']['createdAt'] ),
			"is_period_available"   => $is_period_available,
			"all_attacks"   => $chart['count_attacks'],
			"blocking"    => $chart['count_blocks'],
			"not_blocking"  => $chart['count_attacks'] - $chart['count_blocks'],
			"most_attacks"  => WebTotem::getMostAttacksData($service_data['map']),
		],
		'template' => 'firewall_stats',
	];

	// Firewall filter form
	$build[] = [
		'template' => 'waf_filter_form',
	];

	// Firewall blocks.
	$build[] = [
		'variables' => [
			"chart" => $chart['chart'],
			"logs"  => WebTotem::wafLogs($service_data['logs']['edges']),
			'has_next_page' => $service_data['logs']['pageInfo']['hasNextPage'],
            'host_name' => $host['name'],
			'page'  => 'firewall',
		],
		'template' => 'firewall',
	];

	$page_content = $template->arrayRender($build);
	echo $template->baseTemplate($page_content);

}

/**
 * Antivirus page.
 *
 * @return void
 */
function wtotem_antivirus_page() {

	$host = WebTotemAPI::siteInfo();

	$template = new WebTotemTemplate();
	if (!isset($host['id']) or !$host['id']) {
		wtotem_error_page();
		exit();
	}

	if(WebTotem::isMultiSite() and !is_super_admin()) {
		echo $template->baseTemplate(__('Sorry, you are not allowed to view this page.', 'wtotem'));
		exit();
	}

	$params = [
		'host_id' => $host['id'],
		'limit' => 10,
		'cursor' => NULL,
		'days' => 365,
		'event' => FALSE,
		'permissions' => FALSE,
	];

	// Get data from WebTotem API.
	if($cacheData = WebTotemCache::getdata('getAntivirus', $host['id'])){

		$data = $cacheData['data'];
	} else {
		$data = WebTotemAPI::getAntivirus($params);
		WebTotemCache::setData(['getAntivirus' => $data], $host['id']);
	}

	if (empty($data)) {
		wtotem_error_page();
		exit();
	}

	// Reset session data.
	WebTotemOption::setSessionOptions([
		'antivirus_event' => NULL,
		'antivirus_permissions' => NULL,
		'antivirus_cursor' => $data['log']['pageInfo']['endCursor'],
	]);

	// MultiSite page header (site name)
	if(WebTotem::isMultiSite() and is_super_admin()){
		// Submenu block.
		$host_ = WebTotemOption::getHost(WebTotemRequest::get('hid'));
		$pages['antivirus'] = 'wtotem_page-header__link_active';

		$build[] = [
			'variables' => [
				'is_active' => $pages,
				'site_name' => $host_['name'],
				'hid' => $host_['id'],
			],
			'template' => 'multisite_submenu',
		];
	}

	// Antivirus header.
	$build[] = [
		'variables' => [
			"title" => __('Antivirus', 'wtotem'),
		],
		'template' => 'section_header',
	];

	// Antivirus stats blocks.
	$stats = $data['stats'];
	$build[] = [
		'variables' => [
			'changes'  => $stats['changed'] ?: 0,
			'scanned'  => $stats['scanned'] ?: 0,
			'deleted'  => $stats['deleted'] ?: 0,
			'infected' => $stats["infected"] ?: 0,
			'page' => 'antivirus',
		],
		'template' => 'antivirus_stats',
	];

	// Quarantine logs blocks.
	$quarantine_logs = $data['quarantine'] ?: [];
	$quarantine_count = count($quarantine_logs);

	$build[] = [
		'variables' => [
			"logs"  => WebTotem::getQuarantineLogs($quarantine_logs) ?: [],
			"count"  => $quarantine_count,
		],
		'template' => 'quarantine',
	];

	// Antivirus filter form.
	$build[] = [
		'template' => 'antivirus_filter_form',
	];

	// Antivirus blocks.
	$build[] = [
		'variables' => [
			"logs" => WebTotem::getAntivirusLogs($data['log']['edges']),
			"has_next_page" => $data['log']['pageInfo']['hasNextPage'],
			'last_scan' => WebTotem::dateFormatter($data['lastTest']['time']),
		],

		'template' => 'antivirus',
	];

	$page_content = $template->arrayRender($build);
	echo $template->baseTemplate($page_content);
}

/**
 * Settings page
 *
 * @return void
 */
function wtotem_settings_page() {

	$host = WebTotemAPI::siteInfo();

	$template = new WebTotemTemplate();
	if (!isset($host['id']) or !$host['id']) {
		wtotem_error_page();
		exit();
	}

	if(WebTotem::isMultiSite() and !is_super_admin()) {
		echo $template->baseTemplate(__('Sorry, you are not allowed to view this page.', 'wtotem'));
		exit();
	}

	// Get data from WebTotem API.
	if($cacheData = WebTotemCache::getdata('getConfigs', $host['id'])){
		$configs_data = $cacheData['data'];
	} else {
		$configs_data = WebTotemAPI::getConfigs($host['id']);
		WebTotemCache::setData(['getConfigs' => $configs_data], $host['id']);
	}

	if($cacheData = WebTotemCache::getdata('getAgentsStatusesFromAPI', $host['id'])){
		$agents_statuses = $cacheData['data'];
	} else {
		$agents_statuses = WebTotemAPI::getAgentsStatusesFromAPI($host['id']);
		WebTotemCache::setData(['getAgentsStatusesFromAPI' => $agents_statuses], $host['id']);
	}

	if($cacheData = WebTotemCache::getdata('getIpLists', $host['id'])){
		$ip_list = $cacheData['data'];
	} else {
		$ip_list = WebTotemAPI::getIpLists($host['id']);
		WebTotemCache::setData(['getIpLists' => $ip_list], $host['id']);
	}

	if($cacheData = WebTotemCache::getdata('getAllowUrlList', $host['id'])){
		$url_list = $cacheData['data'];
	} else {
		$url_list = WebTotemAPI::getAllowUrlList($host['id']) ?: [];
		WebTotemCache::setData(['getAllowUrlList' => $url_list], $host['id']);
	}

    if($cacheData = WebTotemCache::getdata('getBlockedCountries', $host['id'])){
        $waf_data = $cacheData['data'];
    } else {
        $waf_data = WebTotemAPI::getBlockedCountries($host['id']);
        WebTotemCache::setData(['getBlockedCountries' => $waf_data], $host['id']);
    }

	if (empty($configs_data) or
	    empty($agents_statuses) or
	    empty($ip_list)
	) {
		wtotem_error_page();
		exit();
	}

	// MultiSite page header (site name)
	if(WebTotem::isMultiSite() and is_super_admin()){
		// Submenu block.

		$host_ = WebTotemOption::getHost(WebTotemRequest::get('hid'));
		$pages['settings'] = 'wtotem_page-header__link_active';

		$build[] = [
			'variables' => [
				'is_active' => $pages,
				'site_name' => $host_['name'],
				'hid' => $host_['id'],
			],
			'template' => 'multisite_submenu',
		];
	}


	// Settings form.
	$build[] = [
		'variables' => [
			'configs' => WebTotem::getConfigsData($configs_data, 'service'),
			'deny_list' => WebTotem::getIpList($ip_list['blackList'], 'ip_deny'),
			'allow_list' => WebTotem::getIpList($ip_list['whiteList'], 'ip_allow'),
			'url_list' => WebTotem::getUrlAllowList($url_list),
			'av_status' => WebTotem::getStatusData($agents_statuses['av']['status']),
			'waf_status' => WebTotem::getStatusData($agents_statuses['waf']['status']),
			'waf_settings' => WebTotem::getWafSettingData($ip_list['settings']),
			'plugin_settings' => WebTotem::getPluginSettingsData(),
      'two_factor' => WebTotemLogin::getTwoFactorData(),
      'blocked_countries_list' => json_encode($waf_data['blockedCountries']),
      'mock_attacks' => json_encode(WebTotem::getTreeMostAttacksData($waf_data['map'])),
		],

		'template' => 'settings_form',
	];

	$page_content = $template->arrayRender($build);
	echo $template->baseTemplate($page_content);
}

/**
 * Reports page.
 *
 * @return void
 */
function wtotem_reports_page() {

	if(WebTotemRequest::get('hid')){
		$host = WebTotemOption::getHost(WebTotemRequest::get('hid'));
	} else {
		$host = WebTotemAPI::siteInfo();
	}

	$template = new WebTotemTemplate();
	if (!isset($host['id']) or !$host['id']) {
		wtotem_error_page();
		exit();
	}

	// Get data from WebTotem API.
	if($cacheData = WebTotemCache::getdata('getAllReports', $host['id'])){
		$data = $cacheData['data'];
	} else {
		$data = WebTotemAPI::getAllReports($host['id']);
		WebTotemCache::setData(['getAllReports' => $data], $host['id']);
	}

	if (empty($data)) {
		wtotem_error_page();
		exit();
	}

	WebTotemOption::setSessionOptions([
		'reports_cursor' => $data['pageInfo']['endCursor'],
		'reports_m_cursor' => $data['pageInfo']['endCursor'],
	]);

	// MultiSite page header (site name)
	if(WebTotem::isMultiSite() and is_super_admin()){
		// Submenu block.
		$pages['reports'] = 'wtotem_page-header__link_active';

		$build[] = [
			'variables' => [
				'is_active' => $pages,
				'site_name' => $host['name'],
				'hid' => $host['id'],
			],
			'template' => 'multisite_submenu',
		];
	}

	// Reports form.
	$build[] = [
		'template' => 'reports_form',
	];

	// Reports.
	$build[] = [
		'variables' => [
			"reports" => WebTotem::getReports($data['edges']),
			"has_next_page" => $data['pageInfo']['hasNextPage'],
		],
		'template' => 'reports',
	];

	$page_content = $template->arrayRender($build);
	echo $template->baseTemplate($page_content);
}

/**
 * Scan WP page.
 *
 * @return void
 */
function wtotem_wpscan_page() {
    $template = new WebTotemTemplate();
    $audit_logs = WebTotemDB::getRows([],'audit_logs');
    $confidential_files = WebTotemDB::getRows([],'confidential_files');
    $links = WebTotemDB::getRows(['AND', ['data_type' => 'links']],'scan_logs', 'content');
    $scripts = WebTotemDB::getRows(['AND', ['data_type' => 'scripts']],'scan_logs', 'content');
    $iframes = WebTotemDB::getRows(['AND', ['data_type' => 'iframes']],'scan_logs', 'content');

    $events = [
        'User authentication succeeded',
        'User authentication failed',
        'User account created',
        'User account deleted',
        'User account edited',
        'Attempt to reset password',
        'Password retrieval attempt',
        'User added to website',
        'User removed from website',
        'WordPress updated',

        'User account deleted',
        'Bookmark link added',
        'Bookmark link edited',
        'Category created',
        'Publication was published',
        'Publication was updated',
        'Post status has been changed',
        'Post deleted',
        'Post moved to trash',
        'Media file added',
        'Plugin activated',
        'Plugin deactivated',
        'Theme activated',
        'Settings changed',
        'Plugins deleted',
        'Plugin editor used',
        'Plugin installed',
        'Plugins updated',
        'Theme deleted',
        'Theme editor used',
        'Theme installed',
        'Themes updated',
        'Widget deleted',
        'Widget added',
    ];

    $until_next_scan = wp_next_scheduled('webtotem_daily_cron') - time();

    $hr = floor($until_next_scan / 3600);
    $min = floor(($until_next_scan % 3600) / 60);

    // Scan logs block.
    $build[] = [
        'variables' => [
            "audit_logs_count" => $audit_logs['count'],
            "audit_logs" => WebTotem::getAuditLogs($audit_logs['data'], $audit_logs['dates_count']),
            "audit_logs_pagination" => WebTotem::paginationBuild(10, $audit_logs['count']),
            "audit_logs_events" => WebTotemDB::checkAvailability('audit_logs', $events, 'event'),

            "confidential_files_count" => $confidential_files['count'],
            "confidential_files" =>  WebTotem::getConfidentialFiles($confidential_files['data']),
            "confidential_files_pagination" => WebTotem::paginationBuild(10, $confidential_files['count']),

            "links_count" => $links['count'],
            "links" => $links['data'],
            "links_pagination" => WebTotem::paginationBuild(10, $links['count']),

            "scripts_count" => $scripts['count'],
            "scripts" => $scripts['data'],
            "scripts_pagination" => WebTotem::paginationBuild(10, $scripts['count']),

            "iframes_count" => $iframes['count'],
            "iframes" => $iframes['data'],
            "iframes_pagination" => WebTotem::paginationBuild(10, $iframes['count']),

		        "next_scan" => sprintf(__('%dh %dm', 'wtotem'), $hr, $min),
		        "scan_init" => WebTotemOption::getOption('scan_init') ?: 0,
        ],
        'template' => 'scan_logs',
    ];

    $page_content = $template->arrayRender($build);
    echo $template->baseTemplate($page_content);
}


/**
 * Information page.
 *
 * @return void
 */
function wtotem_documentation_page() {

	$template = new WebTotemTemplate();

	$build[] = [
		'template' => 'help',
	];

	$page_content = $template->arrayRender($build);
	echo $template->baseTemplate($page_content);
}

