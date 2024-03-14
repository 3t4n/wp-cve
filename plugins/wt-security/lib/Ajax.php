<?php
if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die('Protected By WebTotem!');
}

class WebTotemAjax
{

	/**
	 * Activation plugin.
	 *
	 * @return void
	 */
	public static function activation()
	{

		if (WebTotemRequest::post('ajax_action') !== 'activation') {
			return;
		}

		if ($api_key = WebTotemRequest::post('api_key')) {

			$result = WebTotemAPI::auth($api_key);

			if ($result == 'success') {
				if (WebTotem::isMultiSite()) {
					$link = WebTotem::adminURL('admin.php?page=wtotem_all_sites');
				} else {
					$link = WebTotem::adminURL('admin.php?page=wtotem');
				}
				$email = WebTotemAPI::getEmail();
				WebTotemOption::setOptions(['user_email' => $email]);
				wp_send_json([
					'link' => $link,
					'success' => true,
					'user' => $email,
				], 200);
			} else {

				wp_send_json([
					'notifications' => self::notifications(),
					'success' => false,
				], 200);
			}
		}

	}

	/**
	 * The process of installing agents (WAF, AV) on the main page.
	 *
	 * @return void
	 */
	public static function agentsInstallation()
	{

		if (WebTotemRequest::post('ajax_action') !== 'agents_installation') {
			return;
		}

		$av_installed = WebTotemOption::getOption('av_installed');
		$waf_installed = WebTotemOption::getOption('waf_installed');

		// Check if the agents are installed.
		if ($av_installed and $waf_installed) {
			$agents_statuses = [
				'process_statuses' => [
					'av' => 'installed',
					'waf' => 'installed',
				],
			];
		} else {
			// If not installed, then request statuses from the WebTotem API.
			$host = WebTotemAPI::siteInfo();
			$data = WebTotemAPI::getAgentsStatusesFromAPI($host['id']);

			$agents_statuses = [
				'av' => $data['av']['status'],
				'waf' => $data['waf']['status'],
			];

			$agents_statuses = WebTotem::getAgentsStatuses($agents_statuses);
		}

		$build[] = [
			'variables' => [
				'process_status' => $agents_statuses['process_statuses'],
			],
			'template' => 'agents_installation',
		];

		$status = [
			'av' => $agents_statuses['process_statuses']['av'] == 'installed',
			'waf' => $agents_statuses['process_statuses']['waf'] == 'installed',
		];

		WebTotemOption::setOptions([
			'av_installed' => $status['av'],
			'waf_installed' => $status['waf'],
		]);

		$template = new WebTotemTemplate();
		$agents = $template->arrayRender($build);

		wp_send_json([
			'success' => true,
			'notifications' => self::notifications(),
			'agents' => $agents,
			'agents_statuses' => $status['av'] && $status['waf'],
		]);
	}

	/**
	 * Reinstall agents.
	 *
	 * @return void
	 */
	public static function reinstallAgents()
	{

		if (WebTotemRequest::post('ajax_action') !== 'reinstall_agents') {
			return;
		}

		WebTotemAgentManager::amInstall();

		$response['success'] = true;
		$response['redirect_link'] = WebTotem::adminURL('admin.php?page=wtotem');
		wp_send_json($response);

	}

	/**
	 * Deleting plugin activation data and redirecting to the activation page.
	 *
	 * @return void
	 */
	public static function logout()
	{

		if (WebTotemRequest::post('ajax_action') !== 'logout') {
			return;
		}

		WebTotemOption::logout();

		$response['success'] = true;
		$response['redirect_link'] = WebTotem::adminURL('admin.php?page=wtotem_activation');
		wp_send_json($response);

	}

	/**
	 * Creating a modal window.
	 *
	 * @return void
	 */
	public static function popup()
	{

		if (WebTotemRequest::post('ajax_action') !== 'popup') {
			return;
		}

		$action = WebTotemRequest::post('popup_action');
		$template = new WebTotemTemplate();

		if ($action) {
			switch ($action) {
				case 'reinstall_agents':
					$build[] = [
						'variables' => [
							'message' => sprintf(__('Some scanning data for %s may be deleted.', 'wtotem'), WEBTOTEM_SITE_DOMAIN),
							'action' => 'reinstall_agents',
							'page_nonce' => wp_create_nonce('wtotem_page_nonce'),
						],
						'template' => 'popup',
					];
					break;

				case 'logout':
					$build[] = [
						'variables' => [
							'message' => __('Are you sure you want to change the API key?', 'wtotem'),
							'action' => 'logout',
							'page_nonce' => wp_create_nonce('wtotem_page_nonce'),
						],
						'template' => 'popup',
					];
					break;
			}

			wp_send_json([
				'success' => true,
				'content' => $template->arrayRender($build),
			]);
		}

		wp_send_json([
			'success' => false,
		]);

	}

	/**
	 * Request to update charts with parameters.
	 *
	 * @return void
	 */
	public static function chart()
	{

		if (WebTotemRequest::post('ajax_action') !== 'chart') {
			return;
		}

		$template = new WebTotemTemplate();

		$days = (integer)WebTotemRequest::post('days');
		$service = WebTotemRequest::post('service');

		$host = WebTotemAPI::siteInfo();

		switch ($service) {
			case 'waf':

				WebTotemOption::setSessionOptions(['firewall_period' => $days]);

				// Firewall chart.
				$data = WebTotemAPI::getFirewallChart($host['id'], $days);
				$chart = WebTotem::generateWafChart($data['chart']);

				$_chart[] = [
					'variables' => [
						'days' => $days,
						'chart' => $chart['chart'],
					],
					'template' => 'firewall_chart',
				];

				// Firewall logs.
				$data = WebTotemAPI::getFirewall($host['id'], 10, NULL, $days);
				$firewall = $data['firewall'];

				$waf_logs[] = [
					'variables' => [
						'logs' => WebTotem::wafLogs($firewall['logs']['edges']),
					],
					'template' => 'firewall_logs',
				];

				// Firewall stats.
				$waf_stats[] = [
					'variables' => [
						'is_waf_training' => WebTotem::isWafTraining($data['agentManager']['createdAt']),
						'all_attacks' => $chart['count_attacks'],
						'blocking' => $chart['count_blocks'],
						'not_blocking' => $chart['count_attacks'] - $chart['count_blocks'],
						'most_attacks' => WebTotem::getMostAttacksData($firewall['map']),
					],
					'template' => 'firewall_stats',
				];

				WebTotemOption::setSessionOptions([
					'firewall_cursor' => $firewall['logs']['pageInfo']['endCursor'],
				]);

				$has_next_page = $firewall['logs']['pageInfo']['hasNextPage'];

				$response = [
					'chart' => $template->arrayRender($_chart),
					'waf_logs' => $template->arrayRender($waf_logs),
					'waf_stats' => $template->arrayRender($waf_stats),
					'has_next_page' => $has_next_page,
					'service' => 'waf',
				];

				break;

			case 'cpu':
				WebTotemOption::setSessionOptions(['cpu_period' => $days]);

				$data = WebTotemAPI::getServerStatusData($host['id'], $days);
				$chart = WebTotem::generateChart($data['cpuChart'], $days);

				$_chart[] = [
					'variables' => [
						'days' => $days,
						'chart' => $chart,
					],
					'template' => 'cpu_chart',
				];

				$response = [
					'chart' => $template->arrayRender($_chart),
					'service' => 'cpu',
				];

				break;

			case 'ram':
				WebTotemOption::setSessionOptions(['ram_period' => $days]);

				$data = WebTotemAPI::getServerStatusData($host['id'], $days);
				$chart = WebTotem::generateChart($data['ramChart'], $days);

				$_chart[] = [
					'variables' => [
						'days' => $days,
						'chart' => $chart,
					],
					'template' => 'ram_chart',
				];

				$response = [
					'chart' => $template->arrayRender($_chart),
					'service' => 'ram',
				];

				break;

			case 'map':
				$data = WebTotemAPI::getFirewallChart($host['id'], $days);
				$chart = WebTotem::generateAttacksMapChart($data['map']);
				$world_map_json = WEBTOTEM_URL . '/includes/js/world_map.json';

				$_chart[] = [
					'variables' => [
						'attacks_map' => $chart,
						'world_map_json' => $world_map_json,
					],
					'template' => 'map_chart',
				];

				$response = [
					'chart' => $template->arrayRender($_chart),
					'service' => 'map',
				];

				break;

		}

		if ($service) {
			$response['success'] = true;
			$response['notifications'] = self::notifications();
			wp_send_json($response);
		}

	}

	/**
	 * Data lazy load.
	 * @return void
	 */
	public static function lazyLoad()
	{

		if (WebTotemRequest::post('ajax_action') !== 'lazy_load') {
			return;
		}

		$template = new WebTotemTemplate();

		$service = WebTotemRequest::post('service');

		$host = WebTotemAPI::siteInfo();

		switch ($service) {
			case 'all_sites':
				$cursor = WebTotemOption::getSessionOption('sites_cursor') ?: NULL;
				$allSites = WebTotemAPI::getSites($cursor);

				$has_next_page = $allSites['pageInfo']['hasNextPage'];

				WebTotemOption::setSessionOptions([
					'sites_cursor' => $allSites['pageInfo']['endCursor'],
				]);

				// Sites list.
				$build[] = [
					'variables' => [
						'sites' => WebTotem::allSitesData($allSites),
						'has_next_page' => $has_next_page,
					],
					'template' => 'multisite_list'
				];

				break;

			case 'firewall':
				$cursor = WebTotemOption::getSessionOption('firewall_cursor') ?: NULL;
				$period = WebTotemOption::getSessionOption('firewall_period') ?: 365;
				$data = WebTotemAPI::getFirewall($host['id'], 10, $cursor, $period);
				$service_data = $data['firewall'];
				$has_next_page = $service_data['logs']['pageInfo']['hasNextPage'];

				WebTotemOption::setSessionOptions([
					'firewall_cursor' => $service_data['logs']['pageInfo']['endCursor'],
				]);

				// Firewall logs.
				$build[] = [
					'variables' => [
						'logs' => WebTotem::wafLogs($service_data['logs']['edges']),
					],
					'template' => 'firewall_logs',
				];

				break;

			case 'antivirus':
				$cursor = WebTotemOption::getSessionOption('antivirus_cursor') ?: NULL;
				$event = WebTotemOption::getSessionOption('antivirus_event') ?: NULL;
				$permissions = WebTotemOption::getSessionOption('antivirus_permissions') ?: NULL;

				$params = [
					'host_id' => $host['id'],
					'limit' => 10,
					'days' => 365,
					'cursor' => $cursor,
					'event' => $event,
					'permissions' => $permissions,
				];

				$data = WebTotemAPI::getAntivirus($params);
				$has_next_page = $data['log']['pageInfo']['hasNextPage'];

				WebTotemOption::setSessionOptions([
					'antivirus_cursor' => $data['log']['pageInfo']['endCursor'],
				]);

				// Antivirus logs.
				$build[] = [
					'variables' => [
						'logs' => WebTotem::getAntivirusLogs($data['log']['edges']),
					],
					'template' => 'antivirus_logs',
				];

				break;

			case 'reports':
				$cursor = WebTotemOption::getSessionOption('reports_cursor') ?: NULL;

				$data = WebTotemAPI::getAllReports($host['id'], 10, $cursor);
				$has_next_page = $data['pageInfo']['hasNextPage'];

				WebTotemOption::setSessionOptions([
					'reports_cursor' => $data['pageInfo']['endCursor'],
				]);

				// Reports.
				$build[] = [
					'variables' => [
						"reports" => WebTotem::getReports($data['edges']),
						"has_next_page" => $data['pageInfo']['hasNextPage'],
					],
					'template' => 'reports_list',
				];

				break;

			case 'reports_m':
				$cursor = WebTotemOption::getSessionOption('reports_m_cursor') ?: NULL;

				$data = WebTotemAPI::getAllReports($host['id'], 10, $cursor);
				$has_next_page = $data['pageInfo']['hasNextPage'];

				WebTotemOption::setSessionOptions([
					'reports_m_cursor' => $data['pageInfo']['endCursor'],
				]);

				// Reports mobile.
				$build[] = [
					'variables' => [
						"reports" => WebTotem::getReports($data['edges']),
						"has_next_page" => $data['pageInfo']['hasNextPage'],
					],
					'template' => 'reports_list_mobile',
				];

				break;
		}

		if ($service) {

			wp_send_json([
				'success' => true,
				'content' => $template->arrayRender($build),
				'has_next_page' => $has_next_page,
				'notifications' => self::notifications(),
			]);
		}
	}

	/**
	 * Data lazy load.
	 * @return void
	 */
	public static function logs()
	{
		if (WebTotemRequest::post('ajax_action') !== 'logs') {
			return;
		}

		$template = new WebTotemTemplate();
		$logs_action = WebTotemRequest::post('logs_action');

		switch ($logs_action) {
			case 'audit_logs_pagination':
				$order = WebTotemRequest::post('order') === 'ascending' ? 'ASC' : 'DESC';
				$current_page = (int)WebTotemRequest::post('current_page');
				$event = WebTotemRequest::post('event');
				$filter = $event === 'All' ? [] : ['LIKE', ['event' => $event . '%']];

				$audit_logs = WebTotemDB::getRows(
					$filter,
					'audit_logs',
					false,
					['limit' => 10, 'page' => $current_page],
					['order_by' => 'created_at', 'direction' => $order]
				);

				$build[] = [
					'variables' => [
						"audit_logs" => WebTotem::getAuditLogs($audit_logs['data'], $audit_logs['dates_count']),
					],
					'template' => 'scan_audit_logs',
				];

				$response = [
					'success' => true,
					'content' => $template->arrayRender($build),
					"pagination" => WebTotem::paginationBuild(10, $audit_logs['count'], $current_page),
					'notifications' => self::notifications(),
				];

				break;

			case 'audit_logs_sort_filter':
				$order = WebTotemRequest::post('order') === 'ascending' ? 'ASC' : 'DESC';
				$event = WebTotemRequest::post('event');
				$filter = $event === 'All' ? [] : ['LIKE', ['event' => $event . '%']];
				$audit_logs = WebTotemDB::getRows(
					$filter,
					'audit_logs',
					false,
					['limit' => 10, 'page' => 1],
					['order_by' => 'created_at', 'direction' => $order]
				);

				$build[] = [
					'variables' => [
						"audit_logs" => WebTotem::getAuditLogs($audit_logs['data'], $audit_logs['dates_count']),
					],
					'template' => 'scan_audit_logs',
				];

				$response = [
					'success' => true,
					'content' => $template->arrayRender($build),
					"pagination" => WebTotem::paginationBuild(10, $audit_logs['count'], 1),
					'notifications' => self::notifications(),
				];

				break;

			case 'confidential_files':
				$id = WebTotemRequest::post('id') ?? false;
				if ($id) {
					$file = WebTotemDB::getData(['id' => $id], 'confidential_files');
					if ($file['path']) {
						$path = urldecode($file['path']);
						if(file_exists($path)) unlink($path);
						WebTotemDB::deleteData(['id' => $id], 'confidential_files');
						WebTotemOption::setNotification('info', sprintf(__('File %s was deleted', 'wtotem'), json_decode($file['name'])));
					}
				}
				$order_by = WebTotemRequest::post('order');
				$direction = WebTotemRequest::post('direction') === 'ascending' ? 'ASC' : 'DESC';
				$current_page = (int)WebTotemRequest::post('current_page') ?: 1;

				$confidential_files = WebTotemDB::getRows(
					[],
					'confidential_files',
					false,
					['limit' => 10, 'page' => $current_page],
					$order_by ? ['order_by' => $order_by, 'direction' => $direction] : ['order_by' => 'id', 'direction' => 'DESC']
				);

				$build[] = [
					'variables' => [
						"confidential_files" => WebTotem::getConfidentialFiles($confidential_files['data']),
					],
					'template' => 'scan_confidential_files',
				];

				$response = [
					'success' => true,
					'content' => $template->arrayRender($build),
					'count' => $confidential_files['count'],
					"pagination" => WebTotem::paginationBuild(10, $confidential_files['count'], $current_page),
					'notifications' => self::notifications(),
				];

				break;

			case 'logs_pagination':
				$current_page = (int)WebTotemRequest::post('current_page');
				$type = WebTotemRequest::post('type');
				$direction = WebTotemRequest::post('direction') === 'ascending' ? 'ASC' : 'DESC';

				$scan_logs = WebTotemDB::getRows(
					['AND', ['data_type' => $type]],
					'scan_logs',
					'content',
					['limit' => 10, 'page' => $current_page],
					!empty($direction) ? ['order_by' => 'is_internal', 'direction' => $direction] : ['order_by' => 'id', 'direction' => 'DESC']
				);

				$build[] = [
					'variables' => [
						"logs" => $scan_logs['data'],
						"data_type" => $type
					],
					'template' => 'scan_logs_items',
				];

				$response = [
					'success' => true,
					'content' => $template->arrayRender($build),
					"pagination" => WebTotem::paginationBuild(10, $scan_logs['count'], $current_page),
					'notifications' => self::notifications(),
				];

				break;

			case 'rescan':
				WebTotemOption::setOptions(['scan_init' => 1]);
				WebTotemScan::initialize();
				$response = [
						'success' => true,
						'notifications' => self::notifications(),
				];

			break;

			case 'check_scan':

				if(WebTotemOption::getOption('scan_init')){
					WebTotemScan::initialize();
					$response = [
						'success' => true,
						'scan_finished' => false,
						'notifications' => self::notifications(),
					];
				} else {
					$content = [];
					$pagination = [];
					$count = [];
					$types = ['links', 'scripts', 'iframes'];

					foreach ($types as $type) {
						$scan_logs = WebTotemDB::getRows(
								['AND', ['data_type' => $type]],
								'scan_logs',
								'content'
						);

						$build[$type][] = [
								'variables' => [
										"logs" => $scan_logs['data'],
										"data_type" => $type
								],
								'template' => 'scan_logs_items',
						];
						$content[$type] = $template->arrayRender($build[$type]);
						$pagination[$type] = WebTotem::paginationBuild(10, $scan_logs['count']);
						$count[$type] = $scan_logs['count'];
					}

					$confidential_files = WebTotemDB::getRows([], 'confidential_files');
					$content['confidential_files'] = $template->arrayRender([
							'variables' => [
									"confidential_files" => WebTotem::getConfidentialFiles($confidential_files['data']),
							],
							'template' => 'scan_confidential_files',
					]);
					$pagination['confidential_files'] = WebTotem::paginationBuild(10, $confidential_files['count']);
					$count['confidential_files'] = $confidential_files['count'];

					// Resetting the task in the cron.
					wp_clear_scheduled_hook('webtotem_daily_cron');
					wp_schedule_event(time() + 86395, 'daily', 'webtotem_daily_cron');

					$until_next_scan = wp_next_scheduled('webtotem_daily_cron') - time();

					$hr = floor($until_next_scan / 3600);
					$min = floor(($until_next_scan % 3600) / 60);

					$response = [
							'success' => true,
							'scan_finished' => true,
							'content' => $content,
							"pagination" => $pagination,
							"next_scan" => sprintf(__('%dh %dm', 'wtotem'), $hr, $min),
							"count" => $count,
							'notifications' => self::notifications(),
					];
				}

				break;
		}

		wp_send_json($response ?? []);
	}

	/**
	 * Add date filter.
	 *
	 * @return void
	 */
	public static function wafDateFilter()
	{

		if (WebTotemRequest::post('ajax_action') !== 'waf_date_filter') {
			return;
		}

		$template = new WebTotemTemplate();

		$date_from = WebTotemRequest::post('date_from');

		$period = explode(" to ", $date_from);
		WebTotemOption::setSessionOptions(['firewall_period' => $period]);

		$host = WebTotemAPI::siteInfo();

		// Firewall logs.
		$data = WebTotemAPI::getFirewall($host['id'], 10, NULL, $period);
		$firewall = $data['firewall'];

		$waf_logs[] = [
			'variables' => [
				'logs' => WebTotem::wafLogs($firewall['logs']['edges']),
			],
			'template' => 'firewall_logs',
		];

		// Firewall chart.
		$data = WebTotemAPI::getFirewallChart($host['id'], $period);
		$chart = WebTotem::generateWafChart($data['chart']);

		$_chart[] = [
			'variables' => [
				'days' => $chart['days'],
				'chart' => $chart['chart'],
			],
			'template' => 'firewall_chart',
		];

		// Firewall stats.
		$waf_stats[] = [
			'variables' => [
				'is_waf_training' => WebTotem::isWafTraining($data['agentManager']['createdAt']),
				'all_attacks' => $chart['count_attacks'],
				'blocking' => $chart['count_blocks'],
				'not_blocking' => $chart['count_attacks'] - $chart['count_blocks'],
				'most_attacks' => WebTotem::getMostAttacksData($firewall['map']),
			],
			'template' => 'firewall_stats',
		];

		WebTotemOption::setSessionOptions([
			'firewall_cursor' => $firewall['logs']['pageInfo']['endCursor'],
		]);

		$has_next_page = $firewall['logs']['pageInfo']['hasNextPage'];

		$response = [
			'success' => true,
			'chart' => $template->arrayRender($_chart),
			'waf_logs' => $template->arrayRender($waf_logs),
			'waf_stats' => $template->arrayRender($waf_stats),
			'has_next_page' => $has_next_page,
			'notifications' => self::notifications(),
		];

		wp_send_json($response);
	}


	/**
	 * Request to restart re-scan and receive antivirus data.
	 *
	 * @return void
	 */
	public static function antivirus()
	{

		if (WebTotemRequest::post('ajax_action') !== 'antivirus') {
			return;
		}

		$action = WebTotemRequest::post('av_action');

		$host = WebTotemAPI::siteInfo();

		switch ($action) {
			case 'rescan':
				$response = WebTotemAPI::forceCheck($host['id'], 'av');

				if (!isset($response['errors'])) {
					$data = WebTotemAPI::getAntivirusLastTest($host['id']);
					$response['last_scan'] = WebTotem::dateFormatter($data['lastTest']['time']);
				}
				break;

			case 'download_report':
				$response = WebTotemAPI::avExport($host['id']);
				if (!isset($response['errors'])) {
					$response['doc_link'] = $response['data']['auth']['sites']['av']['export'];
				}
				break;

			case 'filter':

				$file_status = WebTotemRequest::post('file_status');
				$permission = filter_var(WebTotemRequest::post('permission'), FILTER_VALIDATE_BOOLEAN);

				WebTotemOption::setSessionOptions([
					'antivirus_permissions' => $permission,
					'antivirus_event' => $file_status,
				]);

				$params = [
					'host_id' => $host['id'],
					'limit' => 10,
					'days' => 365,
					'cursor' => NULL,
					'event' => $file_status,
					'permissions' => $permission,
				];

				$data = WebTotemAPI::getAntivirus($params);
				$has_next_page = $data['log']['pageInfo']['hasNextPage'];

				WebTotemOption::setSessionOptions([
					'antivirus_cursor' => $data['log']['pageInfo']['endCursor'],
				]);

				// Antivirus logs.
				$build[] = [
					'variables' => [
						'logs' => WebTotem::getAntivirusLogs($data['log']['edges']),
					],
					'template' => 'antivirus_logs',
				];

				$template = new WebTotemTemplate();
				$response = [
					'logs' => $template->arrayRender($build),
					'has_next_page' => $has_next_page,
				];

				break;
		}

		$response['success'] = true;
		$response['notifications'] = self::notifications();

		wp_send_json($response);
	}

	/**
	 * Request to add a file to quarantine.
	 *
	 * @return void
	 */
	public static function quarantine()
	{
		if (WebTotemRequest::post('ajax_action') !== 'quarantine') {
			return;
		}

		$action = WebTotemRequest::post('quarantine_action');
		$id_or_path = WebTotemRequest::post('id_or_path');

		$host = WebTotemAPI::siteInfo();
		$response = [];

		switch ($action) {
			case 'add':
				$api_response = WebTotemAPI::moveToQuarantine($host['id'], $id_or_path);
				break;

			case 'remove':
				$api_response = WebTotemAPI::moveFromQuarantine($id_or_path);
				break;
		}

		if (!isset($api_response['errors'])) {

			$quarantine_logs = WebTotemAPI::getQuarantineList($host['id']);
			$quarantine_count = count($quarantine_logs);

			// Quarantine logs.
			$quarantine[] = [
				'variables' => [
					"logs" => WebTotem::getQuarantineLogs($quarantine_logs),
					"count" => $quarantine_count,
				],
				'template' => 'quarantine',
			];

			$cursor = WebTotemOption::getSessionOption('antivirus_cursor') ?: NULL;
			$event = WebTotemOption::getSessionOption('antivirus_event') ?: NULL;
			$permissions = WebTotemOption::getSessionOption('antivirus_permissions') ?: NULL;

			$params = [
				'host_id' => $host['id'],
				'limit' => 10,
				'days' => 365,
				'cursor' => $cursor,
				'event' => $event,
				'permissions' => $permissions,
			];

			$data = WebTotemAPI::getAntivirus($params);
			WebTotemCache::setData(['getAntivirus' => $data], $host['id']);

			$has_next_page = $data['log']['pageInfo']['hasNextPage'];

			WebTotemOption::setSessionOptions([
				'antivirus_cursor' => $data['log']['pageInfo']['endCursor'],
			]);

			// Antivirus logs.
			$antivirus_logs[] = [
				'variables' => [
					'logs' => WebTotem::getAntivirusLogs($data['log']['edges']),
				],
				'template' => 'antivirus_logs',
			];


			$template = new WebTotemTemplate();
			$response = [
				'antivirus_logs' => $template->arrayRender($antivirus_logs),
				'quarantine' => $template->arrayRender($quarantine),
				'has_next_page' => $has_next_page,
			];

		}

		$response['success'] = true;
		$response['notifications'] = self::notifications();

		wp_send_json($response);

	}

	/**
	 * Request to add or remove a port to the ignore list.
	 *
	 * @return void
	 */
	public static function ignorePorts()
	{

		if (WebTotemRequest::post('ajax_action') !== 'ignore_ports') {
			return;
		}

		$template = new WebTotemTemplate();

		$action = WebTotemRequest::post('port_action');
		$port = (int)WebTotemRequest::post('port');

		$host = WebTotemAPI::siteInfo();

		switch ($action) {
			case 'add':
				$response = WebTotemAPI::addIgnorePort($host['id'], $port);
				break;

			case 'remove':
				$response = WebTotemAPI::removeIgnorePort($host['id'], $port);
				break;
		}

		if (!isset($response['errors'])) {

			$ports = WebTotemAPI::getAllPortsList($host['id']);
			$open_ports[] = [
				'variables' => [
					"ports" => WebTotem::getOpenPortsData($ports['TCPResults']),
				],
				'template' => 'open_ports',
			];

			$ignore_ports[] = [
				'variables' => [
					"ports" => $ports,
				],
				'template' => 'ignore_ports',
			];
			$response = [
				'open_ports' => $template->arrayRender($open_ports),
				'ignore_ports' => $template->arrayRender($ignore_ports),
			];

		}

		$response['success'] = true;
		$response['notifications'] = self::notifications();

		wp_send_json($response);
	}

	/**
	 * Request for a report link.
	 *
	 * @return void
	 */
	public static function reports()
	{

		if (WebTotemRequest::post('ajax_action') !== 'reports') {
			return;
		}

		$template = new WebTotemTemplate();

		$action = WebTotemRequest::post('report_action');

		switch ($action) {
			case 'download':
				$id = WebTotemRequest::post('id');
				$link = WebTotemAPI::downloadReport($id);
				if ($link) {
					$response['link'] = $link;
				}
				break;
			case 'report_form':

				$period = explode(" to ", WebTotemRequest::post('date_period'));
				$modules_data = WebTotemRequest::post('modules');

				$modules = [
					'wa' => 'false',
					'dc' => 'false',
					'ps' => 'false',
					'rc' => 'false',
					'sc' => 'false',
					'av' => 'false',
					'waf' => 'false'
				];

				foreach ($modules_data as $module => $value) {
					$modules[$module] = 'true';
				}

				$host = WebTotemAPI::siteInfo();
				$api_response = WebTotemAPI::generateReport($host['id'], $period, $modules);

				if (!$api_response) {
					$massage = '<div class="message error_message">' . __('Report generation error', 'wtotem') . '</div>';
				} else {
					$data = WebTotemAPI::getAllReports($host['id']);
					WebTotemCache::setData(['getAllReports' => $data], $host['id']);

					// Reports.
					$build[] = [
						'variables' => [
							"reports" => WebTotem::getReports($data['edges']),
							"has_next_page" => $data['pageInfo']['hasNextPage'],
						],
						'template' => 'reports_list',
					];

					// Reports mobile.
					$build_mobile[] = [
						'variables' => [
							"reports" => WebTotem::getReports($data['edges']),
							"has_next_page" => $data['pageInfo']['hasNextPage'],
						],
						'template' => 'reports_list_mobile',
					];

					$response = [
						'reports' => $template->arrayRender($build),
						'reports_m' => $template->arrayRender($build_mobile),
						'link' => $api_response,
					];

					$massage = '<div class="message success_message">' . __('The report was successfully generated', 'wtotem') . '</div>';
				}

				$response['massage'] = $massage;

				break;
		}

		$response['success'] = true;
		$response['notifications'] = self::notifications();
		wp_send_json($response);
	}

	/**
	 * Request for a report link.
	 *
	 * @return void
	 */
	public static function settings()
	{

		if (WebTotemRequest::post('ajax_action') !== 'settings') {
			return;
		}

		$av_installed = WebTotemOption::getOption('av_installed');
		$waf_installed = WebTotemOption::getOption('waf_installed');
		$action = WebTotemRequest::post('settings_action');

		if (in_array($action, ['module_toggle', 'module_notifications', 'waf_settings', 'add_allow_ip', 'add_deny_ip', 'add_allow_url', 'add_ip_list', 'country_blocking'])) {
			if (!$av_installed && !$waf_installed) {
				WebTotemOption::setNotification('warning', __('It is not possible to make changes because the agents are not installed.', 'wtotem'));

				wp_send_json([
					'success' => false,
					'notifications' => self::notifications()
				]);
				return;
			}
		}

		$host = WebTotemAPI::siteInfo();
		$template = new WebTotemTemplate();

		switch ($action) {

			case 'module_toggle':
				$config = WebTotemAPI::toggleConfigs(WebTotemRequest::post('value'));

				$configs_data = WebTotemAPI::getConfigs($host['id']);
				WebTotemCache::setData(['getConfigs' => $configs_data], $host['id']);

				WebTotemOption::setNotification('success', __('Your changes have been applied successfully.', 'wtotem'));
				$response['isActive'] = $config['isActive'];
				$response['success'] = true;
				break;

			case 'country_blocking':
				$countries = WebTotemRequest::post('checked_countries');

				if (WebTotemAPI::syncBlockedCountries($host['id'], $countries)) {
					$waf_data = WebTotemAPI::getBlockedCountries($host['id']);
					WebTotemCache::setData(['getBlockedCountries' => $waf_data], $host['id']);

					WebTotemOption::setNotification('success', __('Your changes have been applied successfully.', 'wtotem'));
					$response['blocked_countries_list'] = $waf_data['blockedCountries'];
					$response['success'] = true;
				} else {
					WebTotemOption::setNotification('success', __('Your changes have not been applied.', 'wtotem'));
					$response['success'] = false;
				}

				break;

			case 'module_notifications':
				$config = WebTotemAPI::toggleNotifications($host['id'], WebTotemRequest::post('value'));

				$configs_data = WebTotemAPI::getConfigs($host['id']);
				WebTotemCache::setData(['getConfigs' => $configs_data], $host['id']);

				WebTotemOption::setNotification('success', __('Your changes have been applied successfully.', 'wtotem'));
				$response['isActive'] = $config;
				$response['success'] = true;
				break;

			case 'waf_settings':

				$response['success'] = true;

				$dos = WebTotemRequest::post('dos');
				$dos_limit = WebTotemRequest::post('dos_limit');
//              $login_attempt = WebTotemRequest::post('login_attempt');
//              $login_attempt_limit = WebTotemRequest::post('login_attempt_limit');

				if ($dos) {
					if (empty($dos_limit)) {
						$response['errors']['dos_limit'] = __('The field is required.', 'wtotem');
					} else if ($dos_limit < 500 or $dos_limit > 10000) {
						$response['success'] = false;
						$response['errors']['dos_limit'] = sprintf(__('Please specify a value from %s to %s.', 'wtotem'), '500', '10 000');
					}
				}

//              if ($login_attempt) {
//                  if (empty($login_attempt_limit)) {
//                      $response['errors']['login_attempt_limit'] = __('The field is required.', 'wtotem');
//                  } else if ($login_attempt_limit < 5 or $login_attempt_limit > 30) {
//                      $response['success'] = false;
//                      $response['errors']['login_attempt_limit'] = sprintf(__('Please specify a value from %s to %s.', 'wtotem'), '5', '30');
//                  }
//              }

				if (!$response['success']) {
					break;
				} else {
					$response['errors'] = false;
				}

				$settings = [
					'gdn' => WebTotemRequest::post('gdn'),
					'dosProtection' => $dos,
					'dosLimit' => $dos_limit,
					'loginAttemptsProtection' => 'false',
					'loginAttemptsLimit' => 20,
				];

				$host = WebTotemAPI::siteInfo();
				$api_response = WebTotemAPI::setFirewallSettings($host['id'], $settings);

				if (!$api_response['errors']) {

					$data = WebTotemAPI::getIpLists($host['id']);
					WebTotemCache::setData(['getIpLists' => $data], $host['id']);

					WebTotemOption::setNotification('success', __('Your changes have been applied successfully.', 'wtotem'));
				}

				break;

			case 'recaptcha_settings':

				$recaptcha_v3_site_key = WebTotemRequest::post('recaptcha_v3_site_key');
				$recaptcha_v3_secret = WebTotemRequest::post('recaptcha_v3_secret');
				$recaptcha_token = WebTotemRequest::post('recaptcha_token');
				$recaptcha = filter_var(WebTotemRequest::post('recaptcha'), FILTER_VALIDATE_BOOLEAN) ?: false;

				if ($recaptcha) {
					if (empty($recaptcha_v3_site_key) or empty($recaptcha_v3_secret) or strlen($recaptcha_v3_site_key) != 40 or strlen($recaptcha_v3_secret) != 40) {
						$response['success'] = false;

						$response['errors'] = ['recaptcha_v3_site_key' => '', 'recaptcha_v3_secret' => ''];

						if (empty($recaptcha_v3_site_key)) {
							$response['errors']['recaptcha_v3_site_key'] = __('The field is required.', 'wtotem');
						} else if (strlen($recaptcha_v3_site_key) != 40) {
							$response['errors']['recaptcha_v3_site_key'] = __('Invalid field length.', 'wtotem');
						}
						if (empty($recaptcha_v3_secret)) {
							$response['errors']['recaptcha_v3_secret'] = __('The field is required.', 'wtotem');
						} else if (strlen($recaptcha_v3_secret) != 40) {
							$response['errors']['recaptcha_v3_secret'] = __('Invalid field length.', 'wtotem');
						}

						break;
					}

					$score = WebTotemCaptcha::score($recaptcha_token, $recaptcha_v3_secret);

					if ($score == 0) {
						$response['success'] = false;
						$response['errors']['recaptcha_v3_secret'] = __('Please check your keys and try again.', 'wtotem');
						$response['errors']['recaptcha_v3_site_key'] = __('Please check your keys and try again.', 'wtotem');
						break;
					}
				}

				if ($recaptcha) {
					$settings = [
						'recaptcha_v3_site_key' => $recaptcha_v3_site_key,
						'recaptcha_v3_secret' => $recaptcha_v3_secret,
					];
				}
				$settings['recaptcha'] = $recaptcha;

				if ($settings['hide_wp_version']) {
					WebTotemOption::hideReadme();
				} else {
					WebTotemOption::restoreReadme();
				}

				WebTotemOption::setPluginSettings($settings);

				WebTotemOption::setNotification('success', __('Your changes have been applied successfully.', 'wtotem'));
				WebTotemOption::setNotification('warning', __('Please make sure that no other recaptcha is used on your site. Otherwise, there may be a conflict that will cause problems when logging into the admin panel.', 'wtotem'));

				$response['success'] = true;


				break;

			case 'two_factor_settings':
				$two_factor = filter_var(WebTotemRequest::post('two_factor'), FILTER_VALIDATE_BOOLEAN) ?: false;

				WebTotemOption::setPluginSettings([
					'two_factor' => $two_factor,
				]);

				WebTotemOption::setNotification('success', __('Your changes have been applied successfully.', 'wtotem'));
				if ($two_factor) {
					WebTotemOption::setNotification('warning', __('Please make sure that no other 2FA is used on your site. Otherwise, there may be a conflict that will cause problems when logging into the admin panel.', 'wtotem'));
				}

				$response['success'] = true;

				break;


			case 'other_settings':

				$settings = [
					'hide_wp_version' => filter_var(WebTotemRequest::post('hide_wp_version'), FILTER_VALIDATE_BOOLEAN) ?: false,
				];

				if ($settings['hide_wp_version']) {
					WebTotemOption::hideReadme();

				} else {
					WebTotemOption::restoreReadme();
				}

				WebTotemOption::setPluginSettings($settings);

				WebTotemOption::setNotification('success', __('Your changes have been applied successfully.', 'wtotem'));

				$response['success'] = true;

				break;

			case 'bruteforce_protection_settings':

				$data = WebTotemRequest::post('data');
				$response['success'] = true;

				$login_attempts = filter_var($data['login_attempts'], FILTER_VALIDATE_BOOLEAN) ?: false;
				$password_reset = filter_var($data['password_reset'], FILTER_VALIDATE_BOOLEAN) ?: false;

				if ($login_attempts) {
					$response['errors'] = ['login_number_of_attempts' => '', 'login_minutes_of_ban' => ''];

					if (empty($data['login_number_of_attempts']) or empty($data['login_minutes_of_ban'])) {
						$response['success'] = false;

						if (empty($data['login_number_of_attempts'])) {
							$response['errors']['login_number_of_attempts'] = __('The field is required.', 'wtotem');
						}
						if (empty($data['login_minutes_of_ban'])) {
							$response['errors']['login_minutes_of_ban'] = __('The field is required.', 'wtotem');
						}
					} else if ($data['login_number_of_attempts'] <= 0 or $data['login_number_of_attempts'] > 1000000) {
						$response['success'] = false;
						$response['errors']['login_number_of_attempts'] = sprintf(__('Please specify a value from %s to %s.', 'wtotem'), '1', '1000000');
					}
				}

				if ($password_reset) {
					if (empty($data['password_reset_number_of_attempts']) or empty($data['password_reset_minutes_of_ban'])) {
						$response['success'] = false;

						$response['errors']['password_reset_number_of_attempts'] = '';
						$response['errors']['password_reset_minutes_of_ban'] = '';

						if (empty($data['password_reset_number_of_attempts'])) {
							$response['errors']['password_reset_number_of_attempts'] = __('The field is required.', 'wtotem');
						}
						if (empty($data['password_reset_minutes_of_ban'])) {
							$response['errors']['password_reset_minutes_of_ban'] = __('The field is required.', 'wtotem');
						}
					} else if ($data['password_reset_number_of_attempts'] <= 0 or $data['password_reset_number_of_attempts'] > 1000000) {
						$response['success'] = false;
						$response['errors']['password_reset_number_of_attempts'] = sprintf(__('Please specify a value from %s to %s.', 'wtotem'), '1', '1000000');
					}
				}
				if (!$response['success']) {
					break;
				} else {
					$response['errors'] = false;
				}

				$settings = [
					'login_attempts' => $login_attempts,
					'password_reset' => $password_reset,
				];

				if ($login_attempts) {
					$settings['login_number_of_attempts'] = $data['login_number_of_attempts'];
					$settings['login_minutes_of_ban'] = $data['login_minutes_of_ban'];
				}
				if ($password_reset) {
					$settings['password_reset_number_of_attempts'] = $data['password_reset_number_of_attempts'];
					$settings['password_reset_minutes_of_ban'] = $data['password_reset_minutes_of_ban'];
				}

				WebTotemOption::setPluginSettings($settings);

				WebTotemOption::setNotification('success', __('Your changes have been applied successfully.', 'wtotem'));

				break;

			case 'add_allow_ip':
				$api_response = WebTotemAPI::addIpToList($host['id'], WebTotemRequest::post('value'), 'white');
				if ($api_response) {
					$data = WebTotemAPI::getIpLists($host['id']);
					WebTotemCache::setData(['getIpLists' => $data], $host['id']);
					$build[] = [
						'variables' => [
							"list" => WebTotem::getIpList($data['whiteList'], 'ip_allow'),
						],
						'template' => 'allow_deny_list',
					];

					$response['content'] = $template->arrayRender($build);
				}

				$response['success'] = true;
				break;

			case 'add_deny_ip':
				$api_response = WebTotemAPI::addIpToList($host['id'], WebTotemRequest::post('value'), 'black');
				if ($api_response) {
					$data = WebTotemAPI::getIpLists($host['id']);
					WebTotemCache::setData(['getIpLists' => $data], $host['id']);
					$build[] = [
						'variables' => [
							"list" => WebTotem::getIpList($data['blackList'], 'ip_deny'),
						],
						'template' => 'allow_deny_list',
					];

					$response['content'] = $template->arrayRender($build);
				}

				$response['success'] = true;
				break;

			case 'add_allow_url':
				$api_response = WebTotemAPI::addUrlToAllowList($host['id'], WebTotemRequest::post('value'));
				if ($api_response) {
					$data = WebTotemAPI::getAllowUrlList($host['id']);
					$build[] = [
						'variables' => [
							"list" => WebTotem::getUrlAllowList($data),
						],
						'template' => 'allow_url_list',
					];

					$response['content'] = $template->arrayRender($build);
				}

				$response['success'] = true;
				break;

			case 'add_ip_list':
				$ips = WebTotemRequest::post('ips');
				$list_name = WebTotemRequest::post('list');

				$host = WebTotemAPI::siteInfo();
				$api_response = WebTotemAPI::addIpToList($host['id'], $ips, $list_name);

				if ($api_response) {
					$data = WebTotemAPI::getIpLists($host['id']);

					$data_list = ($list_name == 'white') ? $data['whiteList'] : $data['blackList'];
					$ip_list = ($list_name == 'white') ? 'ip_allow' : 'ip_deny';

					$build[] = [
						'variables' => [
							"list" => WebTotem::getIpList($data_list, $ip_list),
						],
						'template' => 'allow_deny_list',
					];

					if ($api_response['status'] != 0) {
						$response['invalidIPs'] = implode("\n", $api_response['invalidIPs']);
					}

					$response['wrap'] = ($list_name == 'white') ? '#wtotem_ip_allow_list' : '#wtotem_ip_deny_list';
					$response['content'] = $template->arrayRender($build);
				}
				$response['success'] = true;

				break;
		}

		$response['notifications'] = self::notifications();
		wp_send_json($response);
	}

	/**
	 * Request to remove from the list of deny/allowed ip or url addresses.
	 *
	 * @return void
	 */
	public static function remove()
	{

		if (WebTotemRequest::post('ajax_action') !== 'remove') {
			return;
		}

		$av_installed = WebTotemOption::getOption('av_installed');
		$waf_installed = WebTotemOption::getOption('waf_installed');

		if (!$av_installed && !$waf_installed) {
			WebTotemOption::setNotification('warning', __('It is not possible to make changes because the agents are not installed.', 'wtotem'));

			wp_send_json([
				'success' => false,
				'notifications' => self::notifications()
			]);
		}

		$action = WebTotemRequest::post('remove_action');
		$host = WebTotemAPI::siteInfo();
		$template = new WebTotemTemplate();

		switch ($action) {
			case 'ip_allow':
				$api_response = WebTotemAPI::removeIpFromList(WebTotemRequest::post('id'));

				if ($api_response) {
					$data = WebTotemAPI::getIpLists($host['id']);

					$build[] = [
						'variables' => [
							"list" => WebTotem::getIpList($data['whiteList'], 'ip_allow'),
						],
						'template' => 'allow_deny_list',
					];

					$response['content'] = $template->arrayRender($build);
					$response['wrap'] = '#wtotem_ip_allow_list';
				}
				break;

			case 'ip_deny':
				$api_response = WebTotemAPI::removeIpFromList(WebTotemRequest::post('id'));

				if ($api_response) {
					$data = WebTotemAPI::getIpLists($host['id']);

					$build[] = [
						'variables' => [
							"list" => WebTotem::getIpList($data['blackList'], 'ip_deny'),
						],
						'template' => 'allow_deny_list',
					];

					$response['content'] = $template->arrayRender($build);
					$response['wrap'] = '#wtotem_ip_deny_list';
				}
				break;

			case 'url_allow':
				$api_response = WebTotemAPI::removeUrlFromAllowList(WebTotemRequest::post('id'));

				if ($api_response) {
					$data = WebTotemAPI::getAllowUrlList($host['id']);

					$build[] = [
						'variables' => [
							"list" => WebTotem::getUrlAllowList($data),
						],
						'template' => 'allow_url_list',
					];

					$response['content'] = $template->arrayRender($build);
					$response['wrap'] = '#wtotem_allow_url';
				}
				break;
		}

		$response['success'] = true;
		$response['notifications'] = self::notifications();
		wp_send_json($response);
	}

	/**
	 * Request to remove site from WebTotem.
	 *
	 * @return void
	 */
	public static function multisite()
	{

		if (WebTotemRequest::post('ajax_action') !== 'multisite') {
			return;
		}

		$action = WebTotemRequest::post('multisite_action');
		$template = new WebTotemTemplate();

		switch ($action) {
			case 'remove_site':

				$host_id = WebTotemRequest::post('hid');
				$main_host = WebTotemOption::getMainHost();

				if ($host_id == $main_host['id']) {
					WebTotemOption::setNotification('error', __('You cannot delete the primary domain.', 'wtotem'));
					break;
				}
				WebTotemAPI::removeMultiSiteHost($host_id);

				break;

			case 'add_site':

				$new_site = WebTotemRequest::post('site_name');
				WebTotemAPI::addMultiSiteNewSites([$new_site]);

				break;
		}

		$allSites = WebTotemAPI::getSites();
		$has_next_page = $allSites['pageInfo']['hasNextPage'];

		WebTotemOption::setSessionOptions([
			'sites_cursor' => $allSites['pageInfo']['endCursor'],
		]);

		// Sites list.
		$build[] = [
			'variables' => [
				'sites' => WebTotem::allSitesData($allSites),
				'has_next_page' => $has_next_page,
			],
			'template' => 'multisite_list'
		];

		$response['content'] = $template->arrayRender($build);

		$response['success'] = true;
		$response['notifications'] = self::notifications();
		wp_send_json($response);
	}

	/**
	 * Request to remove site from WebTotem.
	 *
	 * @return void
	 */
	public static function twoFactorAuth()
	{

		if (WebTotemRequest::post('ajax_action') !== 'two_factor_auth') {
			return;
		}

		$action = WebTotemRequest::post('case_action');
		$template = new WebTotemTemplate();
		$current_user = wp_get_current_user();

		if ($user_id = (int)WebTotemRequest::post('user_id')) {
			if ($current_user->ID !== $user_id and !current_user_can('manage_options')) {

				WebTotemOption::setNotification('info', '$current_user->ID: ' . $current_user->ID . ', $user_id: ' . $user_id);
				WebTotemOption::setNotification('error', __('You cannot edit this user.', 'wtotem'));
				return;
			}
			$user = get_user_by('id', $user_id);
		} else {
			$user = $current_user;
		}

		switch ($action) {
			case 'activate':

				$g = new WebTotemGoogleAuthenticator();

				$secret = WebTotemRequest::post('secret');
				$recovery = WebTotemRequest::post('recovery');
				$code = WebTotemRequest::post('code');

				if ($g->checkCode($secret, $code)) {
					WebTotemLogin::saveData($user->ID, $recovery, $secret);
					WebTotemOption::setNotification('success', __('Your changes have been applied successfully.', 'wtotem'));
					$response['success'] = true;
				} else {
					WebTotemOption::setNotification('error', __('You have entered an incorrect activation code.', 'wtotem'));
					$response['success'] = false;
				}

				break;

			case 'deactivate':

				WebTotemLogin::delete($user->ID);

				$response['success'] = true;

				break;
		}

		$build[] = [
			'variables' => [
				'two_factor' => WebTotemLogin::getTwoFactorData($user),
				'page_nonce' => wp_create_nonce('wtotem_page_nonce'),
				'user_id' => $user->ID,
			],
			'template' => 'two_factor_auth'
		];

		$response['content'] = $template->arrayRender($build);

		$response['notifications'] = self::notifications();
		wp_send_json($response);
	}

	/**
	 * Changing the theme mode.
	 *
	 * @return void
	 */
	public static function changeThemeMode()
	{

		if (WebTotemRequest::post('ajax_action') !== 'theme_mode') {
			return;
		}

		$theme_mode = WebTotemOption::getSessionOption('theme_mode');

		if ($theme_mode == 'dark') {
			WebTotemOption::setSessionOptions(['theme_mode' => 'light']);
			$response = 'light';
		} else {
			WebTotemOption::setSessionOptions(['theme_mode' => 'dark']);
			$response = 'dark';
		}

		wp_send_json($response);
	}

	/**
	 * Set user time zone offset.
	 *
	 * @return void
	 */
	public static function userTimeZone()
	{

		if (WebTotemRequest::post('ajax_action') !== 'set_time_zone') {
			return;
		}

		$time_zone_offset = WebTotemRequest::post('offset');
		$now = strtotime('now');
		$check = WebTotemOption::getOption('time_zone_check') ?: 0;

		// Checking whether an hour has elapsed since the previous request.
		if ($now >= $check) {
			$time_zone = WebTotemAPI::getTimeZone();
			if ($time_zone) {
				$time_zone_offset = timezone_offset_get(new \DateTimeZone($time_zone), new \DateTime('now', new \DateTimeZone('Europe/London'))) / 3600;
				WebTotemOption::setOptions(['time_zone_check' => $now + 3600]);
			}
			WebTotemOption::setOptions(['time_zone_offset' => $time_zone_offset]);
		}

		wp_send_json([
			'success' => true,
			'time_zone_offset' => $time_zone_offset
		]);

	}

	/**
	 * Forced checking of services.
	 *
	 * @return void
	 */
	public static function force_check()
	{
		if (WebTotemRequest::post('ajax_action') !== 'force_check') {
			return;
		}

		$service = WebTotemRequest::post('service');
		$host = WebTotemAPI::siteInfo();
		$template = new WebTotemTemplate();

		$response['success'] = false;

		if($service){
			// force check service
			$_response = WebTotemAPI::forceCheck($host['id'], $service);

			if (!isset($_response['errors'])) {


				switch ($service) {

					case 'ssl':
					$data = WebTotemAPI::getMonitoring($host['id']);

					$ssl = [
						'status' => WebTotem::getStatusData($data['sslResults']['results'][0]['certStatus']),
						'cert_name' => $data['sslResults']['results'][0]['certIssuerName'],
						'days_left' => WebTotem::daysLeft($data['sslResults']['results'][0]['certExpiryDate']),
						'issue_date' => WebTotem::dateFormatter($data['sslResults']['results'][0]['certIssueDate']),
						'expiry_date' => WebTotem::dateFormatter($data['sslResults']['results'][0]['certExpiryDate']),
					];

					$build[] = [
						'variables' => [
							'ssl' => $ssl,
						],
						'template' => 'monitoring_ssl',
					];

					$response['content'] = $template->arrayRender($build);
					$response['success'] = true;
					break;

					case 'dec':
					$data = WebTotemAPI::getMonitoring($host['id']);

					$domain = [
						'status' => WebTotem::getStatusData($data['domain']['lastScanResult']['status']),
						"redirect_link" => $data['domain']['lastScanResult']['redirectLink'],
						"is_created_at" => (bool)$data['domain']['lastScanResult']['time'],
						"created_at" => WebTotem::dateFormatter($data['domain']['lastScanResult']['time']),
						"is_taken" => $data['domain']['lastScanResult']['isTaken'],
						"ips" => $data['domain']['lastScanResult']['ips'],
						"protection" => $data['domain']['lastScanResult']['protection'],
					];

					$build[] = [
						'variables' => [
							'domain' => $domain,
						],
						'template' => 'monitoring_domain',
					];

					$response['content'] = $template->arrayRender($build);
					$response['success'] = true;
					break;

					case 'rc':
					$data = WebTotemAPI::getMonitoring($host['id']);

					$reputation = [
						"status" => WebTotem::getStatusData($data['reputation']['status']),
						"blacklists_entries" => WebTotem::blacklistsEntries($data['reputation']['status'], $data['reputation']['virusList']),
						"info" => WebTotem::getReputationInfo($data['reputation']['status']),
						"last_test" => WebTotem::dateFormatter($data['reputation']['lastTest']['time']),
					];

					$build[] = [
						'variables' => [
							'reputation' => $reputation,
						],
						'template' => 'monitoring_reputation',
					];

					$response['content'] = $template->arrayRender($build);
					$response['success'] = true;
					break;

					case 'ps':
						$ports = WebTotemAPI::getAllPortsList($host['id']);

						if($ports['TCPResults']){
							$open_ports[] = [
								'variables' => [
									"ports" => WebTotem::getOpenPortsData($ports['TCPResults']),
								],
								'template' => 'open_ports',
							];

							$open_ports_few[] = [
								'variables' => [
									"more" => true,
									"ports" => $ports['TCPResults'] ? WebTotem::getOpenPortsData(array_slice($ports['TCPResults'], 0, 3)) : [],
								],
								'template' => 'open_ports',
							];
						}

						$ignore_ports[] = [
							'variables' => [
								"ports" => $ports,
							],
							'template' => 'ignore_ports',
						];
						$response = [
							'status' => WebTotem::getStatusData($ports['status']),
							'last_test' =>  WebTotem::dateFormatter($ports['lastTest']['time']),
							'open_ports' => (isset($open_ports)) ? $template->arrayRender($open_ports) : '',
							'open_ports_few' =>  (isset($open_ports_few)) ? $template->arrayRender($open_ports_few) : '',
							'ignore_ports' => $template->arrayRender($ignore_ports),
						];

						$response['success'] = true;

						break;

					case 'ops':

						$open_path_data = WebTotemAPI::getOpenPaths($host['id']);
						$open_path[] = [
							'variables' => [
								"paths" => $open_path_data['paths'],
							],
							'template' => 'open_paths',
						];

						$response = [
							'status' => WebTotem::getStatusData(($open_path_data['paths']) ? 'warning' : 'clean'),
							"last_test" => WebTotem::dateFormatter($open_path_data['time']),
							'open_paths' => $template->arrayRender($open_path),
						];

						$response['success'] = true;
						break;
				}



			}
		}

		$response['notifications'] = self::notifications();


		wp_send_json($response);
	}

	/**
	 * Initialization scanning and checking the current status.
	 *
	 * @return void
	 */
	public static function wtotem_scan()
	{

		if (WebTotemRequest::get('ajax_action') !== 'wtotem_scan') {
			return;
		}

		$logs_action = WebTotemRequest::get('scan_action');

		switch ($logs_action) {
			case 'init':
				if(!WebTotemOption::getOption('scan_init')){
					WebTotemOption::setOptions(['scan_init' => 1]);
					WebTotemScan::initialize();
					$response = [
							'success' => true,
							'scan_start' => 'success',
					];
				} else {
					$response = [
							'success' => false,
							'error' => 'The scan is already running',
					];
				}

				break;

			case 'push':
				if(WebTotemOption::getOption('scan_init')) {
					WebTotemScan::initialize();
					$response = [
							'success' => true,
							'scan_finished' => false,
							'push' => 'success',
					];
				} else {
					$response = [
							'success' => false,
							'scan_finished' => true,
							'push' => 'fail',
							'error' => 'Scan completed',
					];
				}

				break;
		}


		wp_send_json($response ?? ['success' => false, 'error' => 'No action found']);
	}

	/**
   * Forced checking of services.
   *
   * @return void
   */
  public static function user_feedback() {
	if (WebTotemRequest::post('ajax_action') !== 'user_feedback') {
	  return;
	}

	$data = [
	  'score' => (int)WebTotemRequest::post('score'),
	  'feedback' => WebTotemRequest::post('feedback')
	];

	$response_data = WebTotemAPI::setFeedback($data);
	if($response_data['message'] == 'Score added'){
	  $response['content'] = '<div style="text-align: center;"><img src="'.WebTotem::getImagePath('').'popup_success_icon.svg" style="width: 85px;"><p class="user-feedback__title" style="margin-bottom: 20px">'.__('Thank you for feedback', 'wtotem').'</p><button id="user-feedback-ok" class="wtotem_control__btn">Okay</button></div>';
	  $response['success'] = true;
	  WebTotemOption::setNotification('success', __('Your reply has been sent successfully.', 'wtotem'));
	} else {
	  WebTotemOption::setNotification('error', __('There were difficulties. Your reply has not been sent.', 'wtotem'));
	  $response['success'] = false;
	}

	$response['notifications'] = self::notifications();

	wp_send_json($response);
  }



	/**
	 * Updating the page data in the specified time interval.
	 *
	 * @return void
	 */
	public static function reloadPage()
	{

		if (WebTotemRequest::post('ajax_action') !== 'reload_page') {
			return;
		}

		$page = WebTotemRequest::post('page');

		$template = new WebTotemTemplate();

		// Get data from WebTotem API.
		$host = WebTotemAPI::siteInfo();

		switch ($page) {
			case 'dashboard':

		$data = WebTotemAPI::getAllData($host['id']);
		WebTotemCache::setData(['getAllData' => $data], $host['id']);

				// Start build array for rendering.
				// Scoring block.
				$service_data = $data['scoring']['result'];
				$total_score = round($data['scoring']['score']);
				$score_grading = WebTotem::scoreGrading($total_score);
				$build['scoring'] = [
					'variables' => [
						"host_id" => $host['id'],
						"total_score" => $total_score . "%",
						"tested_on" => WebTotem::dateFormatter($data['scoring']['lastTest']['time']),
						"server_ip" => $service_data['ip'] ?: ' - ',
						"location" => WebTotem::getCountryName($service_data['country']) ?: ' - ',
						"is_higher_than" => $service_data['isHigherThan'] . '%',
						"grade" => $score_grading['grade'],
						"color" => $score_grading['color'],
					],
					'template' => 'score',
				];

				$is_period_available = WebTotem::isPeriodAvailable($data['agentManager']['createdAt']);

				// Firewall stats.
				$period = WebTotemOption::getSessionOption('firewall_period');
				$service_data = $period ? WebTotemAPI::getFirewall($host['id'], 10, NULL, $period) : $data;
				$service_data = $service_data['firewall'];

				$chart = WebTotem::generateWafChart($service_data['chart']);
				$build['firewall_stats'] = [
					'variables' => [
						"is_waf_training" => $data['agentManager'] && WebTotem::isWafTraining($data['agentManager']['createdAt']),
						"is_period_available" => $is_period_available,
						"most_attacks" => WebTotem::getMostAttacksData($service_data['map']),
						"all_attacks" => $chart['count_attacks'],
						"blocking" => $chart['count_blocks'],
						"not_blocking" => (int)$chart['count_attacks'] - (int)$chart['count_blocks'],
					],
					'template' => 'firewall_stats',
				];

				$build['chart_periods'] = [
					'variables' => [
						"service" => 'waf',
						"days" => is_array($period) ? 7 : $period,
					],
					'template' => 'chart_periods',
				];

				// Firewall blocks.
				$build['firewall_data'] = [
					'variables' => [
						"is_period_available" => $is_period_available,
						"chart" => $chart['chart'],
						"days" => $chart['days'],
						"logs" => WebTotem::wafLogs($service_data['logs']['edges']),
					],
					'template' => 'firewall',
				];

				// Server Status RAM.
				$period = WebTotemOption::getSessionOption('ram_period') ?: 7;
				$service_data = $period ? WebTotemAPI::getServerStatusData($host['id'], $period) : $data['serverStatus'];

				$build['server_status_ram'] = [
					'variables' => [
						"is_period_available" => $is_period_available,
						"info" => $service_data['info'],
						"ram_chart" => WebTotem::generateChart($service_data['ramChart']),
						"days" => $period,
					],
					'template' => 'server_status_ram',
				];

				// Server Status CPU.
				$period = WebTotemOption::getSessionOption('cpu_period') ?: 7;
				$service_data = $period ? WebTotemAPI::getServerStatusData($host['id'], $period) : $data['serverStatus'];
				$build['server_status_cpu'] = [
					'variables' => [
						"is_period_available" => $is_period_available,
						"cpu_chart" => WebTotem::generateChart($service_data['cpuChart']),
						"days" => $period,
					],

					'template' => 'server_status_cpu',
				];

				// Antivirus stats blocks.
				$antivirus_stats = $data['antivirus']['stats'];
				$build['antivirus_stats'] = [
					'variables' => [
						"changes" => $antivirus_stats['changed'] ?: 0,
						"scanned" => $antivirus_stats['scanned'] ?: 0,
						"deleted" => $antivirus_stats['deleted'] ?: 0,
						"infected" => $antivirus_stats["infected"] ?: 0,
					],

					'template' => 'antivirus_stats',
				];

				// Monitoring blocks.
		$ssl= false;
		if($data['sslResults']['results']){
		  $ssl = [
			'status' => WebTotem::getStatusData($data['sslResults']['results'][0]['certStatus']),
			'cert_name' => $data['sslResults']['results'][0]['certIssuerName'],
			'days_left' => WebTotem::daysLeft($data['sslResults']['results'][0]['certExpiryDate']),
			'issue_date' => WebTotem::dateFormatter($data['sslResults']['results'][0]['certIssueDate']),
			'expiry_date' => WebTotem::dateFormatter($data['sslResults']['results'][0]['certExpiryDate']),
		  ];
		}

		$domain = false;
		if(WebTotem::isKz()){
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

		$build['monitoring'] = [
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

				// Scanning blocks.
				$build['scanning'] = [
					'variables' => [
						"ports" => [
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

				$response['content'][] = ['selector' => '#scoring', 'content' => $template->arrayRender($build['scoring'])];
				$response['content'][] = ['selector' => '#firewall_stats', 'content' => $template->arrayRender($build['firewall_stats'])];
				$response['content'][] = ['selector' => '#waf_chart_period', 'content' => $template->arrayRender($build['chart_periods'])];
				$response['content'][] = ['selector' => '#firewall_data', 'content' => $template->arrayRender($build['firewall_data'])];
				$response['content'][] = ['selector' => '#server_status_cpu', 'content' => $template->arrayRender($build['server_status_cpu'])];
				$response['content'][] = ['selector' => '#server_status_ram', 'content' => $template->arrayRender($build['server_status_ram'])];
				$response['content'][] = ['selector' => '#antivirus_stats', 'content' => $template->arrayRender($build['antivirus_stats'])];
				$response['content'][] = ['selector' => '#monitoring', 'content' => $template->arrayRender($build['monitoring'])];
				$response['content'][] = ['selector' => '#scanning', 'content' => $template->arrayRender($build['scanning'])];

				break;
		}

		$response['success'] = true;
		$response['notifications'] = self::notifications();
		wp_send_json($response);
	}


	public static function authenticate()
	{

		if (WebTotemRequest::post('ajax_action') !== 'authenticate') {
			return;
		}

		$credentials = array(
			'log' => 'pwd',
			'username' => 'password'
		);
		$username = null;
		$password = null;
		foreach ($credentials as $usernameKey => $passwordKey) {
			if (array_key_exists($usernameKey, $_POST) &&
				array_key_exists($passwordKey, $_POST) &&
				is_string($_POST[$usernameKey]) &&
				is_string($_POST[$passwordKey])) {
				$username = $_POST[$usernameKey];
				$password = $_POST[$passwordKey];
				break;
			}
		}
		if (empty($username) || empty($password)) {
			$response['error'] = wp_kses(sprintf(__('<strong>ERROR</strong>: A username and password must be provided. <a href="%s" title="Password Lost and Found">Lost your password</a>?'), wp_lostpassword_url()), array('strong' => array(), 'a' => array('href' => array(), 'title' => array())));
		}

		do_action_ref_array('wp_authenticate', array(&$username, &$password));

		$user = wp_authenticate($username, $password);
		$user = WebTotemBFProtection::checkBruteForceAttempts($user, $username);

		if (is_object($user) && ($user instanceof \WP_User)) {

			$response['login'] = true;

			if (WebTotemLogin::hasUser2faActivated($user)) {

				$template = new WebTotemTemplate();

				$response['2fa'] = true;
				$response['content'] = $template->getHtml('login_auth_form');

			}
		} else if (is_wp_error($user)) {
			$errors = array();
			foreach ($user->get_error_codes() as $code) {
				if ($code == 'invalid_username' || $code == 'invalid_email' || $code == 'incorrect_password' || $code == 'authentication_failed') {
					$errors[] = wp_kses(sprintf(__('<strong>ERROR</strong>: The username or password you entered is incorrect. <a href="%s" title="Password Lost and Found">Lost your password</a>?'), wp_lostpassword_url()), array('strong' => array(), 'a' => array('href' => array(), 'title' => array())));
				} else {
					foreach ($user->get_error_messages($code) as $error_message) {
						$errors[] = $error_message;
					}
				}
			}

			if (!empty($errors)) {
				$errors = implode('<br>', $errors);
				$response['error'] = apply_filters('login_errors', $errors);
			}

		}

		wp_send_json($response);
	}

	/**
	 * Notification output.
	 *
	 * @return string
	 */
	public static function notifications()
	{

		$notifications = WebTotem::getNotifications();

		if ($notifications) {
			$build[] = [
				'variables' => [
					'notifications' => $notifications,
				],

				'template' => 'notifications',
			];

			$template = new WebTotemTemplate();
			return $template->arrayRender($build);
		}
		return false;

	}


}
