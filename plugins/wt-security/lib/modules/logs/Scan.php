<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}

require_once 'FileInfo.php';

/**
 * WebTotem scan class for WordPress.
 */
class WebTotemScan {
	/**
	 *
	 */
	public static function initialize() {
		if(WebTotemOption::getOption('scan_init')){
			$time_start = microtime(true);

			$max_execution_time = ini_get('max_execution_time');
			if($max_execution_time < 300){
				if (function_exists('set_time_limit')) @set_time_limit(300);
				@ini_set('max_execution_time', '300');
			}
			$max_execution_time = ini_get('max_execution_time');

			$scan_temp = json_decode(WebTotemOption::getOption('scan_temp'), true) ?: [];

			if(empty($scan_temp)){
				$scan_temp = [
					'current_scan' => 'scanDB',
					'need_to_scan' => [],
					'links' => [],
				];
			}

			$scan_running = json_decode(WebTotemOption::getOption('scan_running'), true) ?: ['status' => 'stop'];
            $seconds_from_previous_start = $time_start - ($scan_running['time_start'] ?? $time_start);
			if($scan_running['status'] == 'stop' || $seconds_from_previous_start > $max_execution_time ){

				WebTotemOption::setOptions(['scan_running' => ['status' => 'run', 'time_start' => $time_start]]);

				if($scan_temp['current_scan'] == 'scanDB'){
					self::scanDB($scan_temp, $max_execution_time, $time_start);
                    WebTotemOption::setOptions(['scan_running' => ['status' => 'stop']]);
					return;
				}

				if($scan_temp['current_scan'] == 'scanFiles') {
					self::scanFiles($scan_temp, $max_execution_time, $time_start);
					WebTotemOption::setOptions(['scan_running' => ['status' => 'stop']]);
					return;
				}

				if($scan_temp['current_scan'] == 'checkConfidentialFiles') {
					self::checkConfidentialFiles($scan_temp, $max_execution_time, $time_start);
					WebTotemOption::setOptions(['scan_running' => ['status' => 'stop']]);
					return;
				}

				if($scan_temp['current_scan'] == 'crawler') {
					WebTotemCrawler::init($scan_temp);
					WebTotemOption::setOptions(['scan_running' => ['status' => 'stop']]);
                    return;
				}

			}

		}

	}

	/**
	 * Database scanning, search for links, scripts and iframe tags,
	 * formation of an array of data on them
	 */
	public static function scanDB($scan_temp, $max_execution_time, $time_start ) {
		$tables = $scan_temp['need_to_scan'] ?: self::getTables();
		$links = $scan_temp['links'] ?: [];

		$needles = ['%href%', '%<iframe%', '%.js%'];

		foreach ($tables['posts'] as $key => $table) {
			$rows = self::getRows($table, ['post_content' => $needles], 'guid');

			foreach ($rows as $row) {
				$links[] = ['link' => $row->guid, 'page' => __('DB scan', 'wtotem'), 'is_internal' => true];;
			}

			unset($tables['posts'][$key]);

			$time_end = microtime(true);
			if (($time_end - $time_start) > $max_execution_time - 5) {
				WebTotemOption::setOptions([
					'scan_temp' => [
						'current_scan' => 'scanDB',
						'need_to_scan' => $tables,
						'links' => $links,
					]
				]);
				return;
			}

		}

		foreach ($tables['comments'] as $relation => $table) {
			$rows = self::getRows($table, ['comment_content' => $needles], 'guid');

			$posts_ids = array_column($rows, 'comment_post_ID');
			$posts_rows = self::getRows($relation, ['ID' => $posts_ids]);
			$posts_rows = WebTotem::arrayMapIndex(WebTotem::convertObjectToArray($posts_rows), 'ID');

			foreach ($rows as $row) {
				$links[] = ['link' => $posts_rows[$row->comment_post_ID]['guid'], 'page' => __('DB scan', 'wtotem'), 'is_internal' => true];
			}

			unset($tables['comments'][$relation]);

			$time_end = microtime(true);
			if (($time_end - $time_start) > $max_execution_time - 5) {
				WebTotemOption::setOptions([
					'scan_temp' => [
						'current_scan' => 'scanDB',
						'need_to_scan' => $tables,
						'links' => $links,
					]
				]);
				return;
			}
		}

		WebTotemOption::setOptions([
			'scan_temp' => [
				'current_scan' => 'scanFiles',
				'need_to_scan' => [],
				'links' => $links,
			]
		]);

	}

	/**
	 * Getting values from the table.
	 *
	 * @param array $options
	 *    Array options.
	 * @param string $table
	 *    Table name.
	 * @param string $fields
	 *    Required fields.
	 *
	 * @return array
	 */
	private static function getRows($table, $options = false, $fields = false) {
		global $wpdb;
		$table_name = self::add_prefix($table);

		if ($options) {
			foreach ($options as $key => $value) {
				if (is_array($value)) {
					foreach ($value as $val) {
						$where[] = $key . " LIKE '" . $val . "'";
					}
				} else {
					$where[] = $key . " LIKE '" . $value . "'";
				}
			}
		}
		$where = isset($where) ? 'WHERE (' . implode(' OR ', $where) . ')' : '';
		if(strpos($table, 'posts') !== false) {
			$where .= $where ? " AND " : "WHERE ";
			$where .= "post_status = 'publish'";
		}

		$fields = $fields ?: '*';
		$rows = $wpdb->get_results("SELECT $fields FROM $table_name $where");

		return (array)$rows ?: [];
	}

	/**
	 * Get an array of tables
	 */
	private static function getTables() {
		$tables = [
			'posts' => [],
			'comments' => []
		];

		if (WebTotem::isMultiSite()) {
			$blogs = self::getRows(self::add_prefix('blogs'));
			foreach ($blogs as $blog) {
				$tables['posts'][] = $blog['blog_id'] . '_posts';
				$tables['comments'][$blog['blog_id'] . '_posts'] = $blog['blog_id'] . '_comments';
			}
		}
		return $tables;
	}

	/**
	 * Returns the table with the site prefix added.
	 *
	 * @param string $table
	 *    Table name.
	 * @return string
	 */
	public static function add_prefix($table) {
		global $wpdb;
		return $wpdb->prefix . $table;
	}

	/**
	 * Files scanning, search for links, scripts and iframe tags,
	 * formation of an array of data on them
	 */
	public static function scanFiles($scan_temp, $max_execution_time, $time_start) {

		$tree = $scan_temp['need_to_scan'] ?? [];
		$links = $scan_temp['links'] ?? [];

		$site_url = get_site_url();
		$fileInfo = new WebTotemFileInfo();
		$abspath = ABSPATH;

		if(empty($tree)){
			// Adding files of active plugins
			if (WebTotem::isMultiSite()) {
				$all_plugs = array_keys(get_site_option('active_sitewide_plugins'));
			} else {
				$all_plugs = get_option('active_plugins');
			}
			foreach ($all_plugs as $value) {
				$plugin = explode('/', $value);
				$tree = array_merge($tree, $fileInfo->getDirectoryTree(WP_PLUGIN_DIR . '/' . $plugin[0]));
			}

			// Adding files of active theme
			$tree = array_merge($tree, $fileInfo->getDirectoryTree(get_template_directory()));
		}

		foreach ($tree as $key => $file_path) {
			$content = $fileInfo::fileContent($file_path);
			if(self::hasMatches($content)){
				$link = $site_url . str_replace($abspath, '/', $file_path);
				$links[] = ['link' => $link, 'page' => __('File scan', 'wtotem'), 'is_internal' => true];
			}
			unset($tree[$key]);

			$time_end = microtime(true);
			if (($time_end - $time_start) > $max_execution_time - 5) {
				WebTotemOption::setOptions([
					'scan_temp' => [
						'current_scan' => 'scanFiles',
						'need_to_scan' => $tree,
						'links' => $links,
					]
				]);
				return;
			}

		}

		WebTotemOption::setOptions([
			'scan_temp' => [
				'current_scan' => 'checkConfidentialFiles',
				'need_to_scan' => [],
				'ready_to_save' => false,
				'links' => $links,
			]
		]);
	}


	/**
	 * Get matches.
	 *
	 * @param string $content
	 *
	 * @return bool
	 */
	private static function hasMatches($content) {
		$pattern = '/(<a.*?href=["\'](([\da-z\.-\/]+)([\/\w\.-\?\%\&]*)*\/?)["\'].*?>|<script.*?src=["\'](.*?)["\'].*?>|<iframe.*?src=["\'](.*?)["\'].*?>|onclick="[^"]*location[^"][^\'"]+\'([^\']+)\')/i';
		if (preg_match($pattern, $content)) {
			return true;
		}
		return false;
	}

	/**
	 * Files scanning, search for confidential files.
	 */
	public static function checkConfidentialFiles($scan_temp, $max_execution_time, $time_start) {

		$files = $scan_temp['need_to_scan'] ?? [];
		$files_data = $scan_temp['confidential_files'] ?? [];
		$root_path = ABSPATH;

		if(empty($files) and !$scan_temp['ready_to_save']){
			$patterns = [
					'.user.ini',
					'wp-config.php.bak',
					'wp-config.php.bak.a2',
					'wp-config.php.swo',
					'wp-config.php.save',
					'wp-config.php~',
					'wp-config.old',
					'.wp-config.php.swp',
					'wp-config.bak',
					'wp-config.save',
					'wp-config.php_bak',
					'wp-config.php.swp',
					'wp-config.php.old',
					'wp-config.php.original',
					'wp-config.php.orig',
					'wp-config.txt',
					'wp-config.original',
					'wp-config.orig',
					'*.bak',
					'*.back',
					'*.backup',
					'*.old',
			];

			$mask = implode(',', $patterns);
			$files = self::glob_tree_search($root_path, '{' . $mask . '}',false);
			$files = array_merge(self::glob_tree_search($root_path . '/wp-content/', '{' . $mask . '}'), $files);
		}


		foreach ($files as $file_path) {
			$url = site_url(str_replace($root_path, '', $file_path));

			if (WebTotem::isPubliclyAccessible($url, $file_path)) {
				$array = explode(DIRECTORY_SEPARATOR, $file_path);
				$name = array_pop($array);
				$files_data[] = [
					'path' => $file_path,
					'name' => $name,
					'size' => filesize($file_path),
					'modified_at' => date("Y-m-d H:i:s", filectime($file_path)),
					'url' => $url,
				];
			}

			$time_end = microtime(true);
			if (($time_end - $time_start) > $max_execution_time - 5) {
				WebTotemOption::setOptions([
                    'scan_temp' => [
                        'current_scan' => 'checkConfidentialFiles',
                        'need_to_scan' => $files,
                        'links' => $scan_temp['links'],
                        'confidential_files' => $files_data,
                    ]
				]);
				return;
			}

		}

		if($scan_temp['ready_to_save']){
		    if($files_data){
                self::saveData($files_data);
            }
		} else {
			WebTotemOption::setOptions([
					'scan_temp' => [
							'current_scan' => 'checkConfidentialFiles',
							'need_to_scan' => [],
							'links' => $scan_temp['links'],
							'ready_to_save' => true,
							'confidential_files' => $files_data,
					]
			]);
			return;
		}

		WebTotemOption::setOptions([
				'scan_temp' => [
						'current_scan' => 'crawler',
						'need_to_scan' => [],
						'ready_to_save' => false,
						'links' => $scan_temp['links'],
						'confidential_files' => [],
				]
		]);

	}

	/**
	 * Save data.
	 *
	 * @param array $data
	 *    Array matches data.
	 */
	private static function saveData($data) {

		WebTotemDB::deleteData([], 'confidential_files');
		$values = '';
		foreach ($data as $file) {
				$values .= sprintf("('%s','%s','%s','%s','%s','%s'),",
						date("Y-m-d H:i:s"),
						urlencode($file['path']),
						urlencode($file['name']),
						$file['size'],
						$file['modified_at'],
						$file['url']
				);
		}

		$values = substr_replace($values, ";", -1);

		$columns = '(created_at, path, name, size, modified_at, url)';

		WebTotemDB::setRows('confidential_files', $columns, $values);
	}

	/**
	 * Search through all subdirectories using recursion.
	 *
	 * @param string $path
	 *    The initial directory of the search.
	 * @param string $mask
	 *    Search mask.
	 *
	 * @return array
	 *   Array of file paths found by mask.
	 */
	public static function glob_tree_search($path, $mask, $recursively = true) {
		$out = [];
		foreach (glob($path . $mask, GLOB_BRACE) as $file_path) {
			$out[] = $file_path;
		}

		if ($recursively) {
			foreach (glob($path . '/*', GLOB_ONLYDIR) as $dir) {
				$out = array_merge($out, self::glob_tree_search($dir, $mask));
			}
		}

		return $out;
	}

}
