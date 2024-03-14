<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}

/**
 * WebTotem page scan class for Wordpress.
 */
class WebTotemCrawler
{
	/**
	 * Running a single iteration
     *
     * @param array $scan_temp
     *   The data of the current scan.
	 */
	public static function init($scan_temp) {

		$crawler_temp = json_decode(WebTotemOption::getOption('crawler_temp'), true) ?: [];

		$i = 1;
		if (!$crawler_temp) {

			$pre_scan = self::pre_scan();

			$crawler_temp['internal']['new'] = $pre_scan['internal'];
			$crawler_temp['external'] = WebTotem::arrayUniqueKey($pre_scan['external'], 'link');
			$crawler_temp['scripts'] = WebTotem::arrayUniqueKey($pre_scan['scripts'], 'link');
			$crawler_temp['iframes'] = WebTotem::arrayUniqueKey( $pre_scan['iframes'], 'link');
			$crawler_temp['exclude'] = array_unique($pre_scan['exclude']);

			$crawler_temp['internal']['new'] = WebTotem::arrayUniqueKey( array_merge($crawler_temp['internal']['new'], $scan_temp['links']), 'link');

			$i++;
		}

		foreach ($crawler_temp['internal']['new'] as $key => $item) {
			if($result = self::explore_page($item['link'], $crawler_temp['exclude'])) {
				$crawler_temp['internal']['visited'][] = $item;

				$crawler_temp['internal']['new'] = WebTotem::arrayUniqueKey(array_merge($crawler_temp['internal']['new'] ?? [], $result['internal'] ?? []), 'link');
				$crawler_temp['external'] = WebTotem::arrayUniqueKey(array_merge($crawler_temp['external'] ?? [], $result['external'] ?? []), 'link');
				$crawler_temp['scripts'] = WebTotem::arrayUniqueKey(array_merge($crawler_temp['scripts'] ?? [], $result['scripts'] ?? []), 'link');
				$crawler_temp['iframes'] = WebTotem::arrayUniqueKey(array_merge($crawler_temp['iframes'] ?? [], $result['iframes'] ?? []), 'link');
				$crawler_temp['exclude'] = array_merge($crawler_temp['exclude'] ?? [], $result['exclude'] ?? []);
			}

			unset($crawler_temp['internal']['new'][$key]);
            WebTotemOption::setOptions(['crawler_temp' => $crawler_temp]);

			if ($i >= 5) break;
			$i++;
		}

		if (empty($crawler_temp['internal']['new'])) {

			if($scan_temp['ready_to_save']){

			    if(isset($crawler_temp['internal']['visited']) and $crawler_temp['external']){
			        $links = array_merge($crawler_temp['internal']['visited'], $crawler_temp['external']);
                } elseif (isset($crawler_temp['internal']['visited'])){
                    $links = $crawler_temp['internal']['visited'];
                } else {
                    $links = $crawler_temp['external'];
                }

				$data = [
                    'links' => $links ?? [],
                    'scripts' => $crawler_temp['scripts'] ?? [],
                    'iframes' => $crawler_temp['iframes'] ?? [],
				];
				self::saveData($data);

				WebTotemOption::setOptions(['crawler_temp' => '']);
				WebTotemOption::setOptions(['scan_temp' => '']);
				WebTotemOption::setOptions(['scan_init' => 0]);

				// Resetting the task in the cron.
				wp_clear_scheduled_hook('webtotem_daily_cron');
				wp_schedule_event(time() + 86395, 'daily', 'webtotem_daily_cron');
			} else {
				WebTotemOption::setOptions([
                    'scan_temp' => [
                        'current_scan' => 'crawler',
                        'links' => [],
                        'ready_to_save' => true,
                    ]
				]);
			}

		}

	}

    /**
     * Preliminary scan of the site.
     *
     * @return array
     */
	private static function pre_scan() {
		$site_url = get_site_url();
		$internal = [];
		$exclude = [];

		// Scanning the file robots.txt
		$robotsTxt = file_get_contents(ABSPATH . '/robots.txt');
		$lines = explode("\n", $robotsTxt);

		foreach ($lines as $line) {
			if (strpos($line, 'Disallow:') === 0 || strpos($line, 'Allow:') === 0) {
				$url = trim(substr($line, strpos($line, ':') + 1));
				$exclude[] = $url;
				$robots_urls[] = (string)$url->loc;
			}
		}

		foreach ($robots_urls as $url) {
			if (substr($url, 0, 1) == "#") {
				continue;
			}
			$internal[] = ['link' => $url, 'page' => $site_url . '/robots.txt', 'is_internal' => self::isInternal($url)];
		}

		// Adding links from popular sitemaps plugins
		$sitemaps = [
				$site_url . '/sitemaps.xml',
				$site_url . '/index.php?xml_sitemap=params=.',
				$site_url . '/?sitemap=1',
				$site_url . '/sitemap_index.xml',
		];

		foreach ($sitemaps as $url) {
			$internal[] = ['link' => $url, 'page' => __('by sitemap plugins', 'wtotem'), 'is_internal' => true];
		}

		// Scanning the file sitemap.xml
		$xml = simplexml_load_file(ABSPATH . '/sitemap.xml');

		$sitemap_urls = [];
		foreach ($xml->url as $url) {
			$exclude[] = (string)$url->loc;
			$sitemap_urls[] = (string)$url->loc;
		}

		foreach ($sitemap_urls as $url) {
			if (substr($url, 0, 1) == "#") {
				continue;
			}
			$internal[] = ['link' => $url, 'page' => $site_url . '/sitemap.xml', 'is_internal' => self::isInternal($url)];
		}

		// Scanning the main page
		$result = self::explore_page($site_url);

		$internal = array_merge($internal, $result['internal']);
		$external = array_unique($result['external']);
		$exclude =  array_merge($exclude, $result['exclude']);

		return [
            'internal' => $internal ?: [],
            'external' => $external ?: [],
            'scripts' => $result['scripts'] ?: [],
            'iframes' => $result['iframes'] ?: [],
            'exclude' => $exclude ?: [],
		];

	}

    /**
     * Get and explore the content of the page.
     *
     * @param string $url
     *   Link to the page.
     * @param string $exclude
     *   Links that have already been checked.
     *
     * @return array|bool
     */
	private static function explore_page($url, $exclude = []) {

        $headers = get_headers($url);

        if ($headers === false || strpos($headers[0], '200 OK') === false) {
            return false;
        }

        // Initializing the cURL session
        $curl = curl_init();

        // Setting the parameters of the cURL session
        curl_setopt($curl, CURLOPT_URL, $url); // Setting the URL
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // We return the result as a string
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // Follow the redirects
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Disabling SSL certificate verification

        // Execute the request and get the content of the page
        $content = curl_exec($curl);

        // Checking for errors when executing the request
        if (curl_errno($curl)) {
            WebTotemOption::setNotification('error', __('Request execution error: ', 'wtotem')) . curl_error($curl);
        }

        // Closing the cURL session
        curl_close($curl);

        // Checking the content for matches with the template, using regular expressions
        return self::getMatches($content, $url, $exclude);
	}

    /**
     * We are looking for links, scripts and frames on the page.
     *
     * @param string $content
     *   The content of the page being checked.
     * @param string $url
     *   Link to the page.
     * @param string $exclude
     *   Links that have already been checked.
     *
     * @return array
     */
	private static function getMatches($content, $url, $exclude) {

        $matches = [
            'internal' => [],
            'external' => [],
            'exclude' => [],
            'scripts' => [],
            'iframe' => [],
        ];

	    if($content){
            // Get all the matches.
            $pattern = '/(<a.*?href=["\'](([\da-z\.-\/]+)([\/\w\.-\?\%\&]*)*\/?)["\'].*?>|<script.*?src=["\'](.*?)["\'].*?>|<iframe.*?src=["\'](.*?)["\'].*?>|onclick="[^"]*location[^"][^\'"]+\'([^\']+)\')/i';
            preg_match_all($pattern, $content, $all_matches);

            $array = [
                'links' => [],
                'scripts' => [],
                'iframes' => [],
            ];

            // Divide by categories.
            foreach ($all_matches[0] as $match) {
                preg_match_all('/<a.*?href=["\'](.*?)["\'].*?>/i', $match, $links_matches);
                if ($links_matches[1]) $array['links'] = array_merge($array['links'], $links_matches[1]);
                preg_match_all('/onclick="[^"]*location[^"][^\'"]+\'([^\']+)\'/i', $match, $links_2_matches);
                if ($links_2_matches[1]) $array['links'] = array_merge($array['links'], $links_2_matches[1]);
                preg_match_all('/<script.*?src=["\'](.*?)["\'].*?>/i', $match, $js_matches);
                if ($js_matches[1]) $array['scripts'] = array_merge($array['scripts'], $js_matches[1]);
                preg_match_all('/<iframe.*?src=["\'](.*?)["\'].*?>/i', $match, $iframe_matches);
                if ($iframe_matches[1]) $array['iframes'] = array_merge($array['iframes'], $iframe_matches[1]);
            }

            foreach ($array['links'] as $link) {
                if (self::isInternal($link)) {
                    if (substr($link, 0, 1) == "#") {
                        continue;
                    }
                    if (in_array($link, $exclude)) {
                        continue;
                    }
                    $matches['internal'][] = ['link' => $link, 'page' => $url, 'is_internal' => true];
                    $matches['exclude'][] = $link;
                } else {
                    $matches['external'][] = ['link' => $link, 'page' => $url, 'is_internal' => false];
                }
            }

            foreach (array_unique($array['scripts']) as $script) {
                $matches['scripts'][] = ['link' => $script, 'page' => $url, 'is_internal' => self::isInternal($script)];
            }
            foreach (array_unique($array['iframes']) as $iframe) {
                $matches['iframe'][] = ['link' => $iframe, 'page' => $url, 'is_internal' => self::isInternal($iframe)];
            }

        }

		return $matches;
	}

    /**
     * We check whether the link is internal or external.
     *
     * @param string $string
     *   The link being checked.
     *
     * @return bool
     */
	private static function isInternal($string): bool {
		$current_domain_parts = parse_url(get_home_url());
		$current_domain = $current_domain_parts['host'];

		if (substr($string, 0, 5) == "https"
				|| substr($string, 0, 4) == "http"
				|| substr($string, 0, 2) == "//") {

			if (strpos($string, $current_domain) === false) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Save data.
	 *
	 * @param array $data
	 *    Array matches data.
	 */
	private static function saveData($data) {

		WebTotemDB::deleteData([], 'scan_logs');
		$values = '';
		foreach ($data as $data_type => $links) {
			foreach ($links as $datum) {
				$values .= sprintf("('%s','%s','%s','%s','%s'),",
						date("Y-m-d H:i:s"),
						$data_type,
						$datum['page'],
						$datum['link'],
						$datum['is_internal']
				);
			}
		}

		$values = substr_replace($values, ";", -1);

		$columns = '(created_at, data_type, source, content, is_internal)';

		WebTotemDB::setRows('scan_logs', $columns, $values);
	}

}
