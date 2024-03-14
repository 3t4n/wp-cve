<?php

namespace IfSo\PublicFace\Services\PageVisitsService;

require_once (IFSO_PLUGIN_BASE_DIR . 'services/plugin-settings-service/plugin-settings-service.class.php');
require_once('models/timed-collection/ifso-page-visits-timed-collection.php');
require_once('models/timed-collection/persist-strategy/ifso-cookie-persist-strategy.php');
require_once('models/timed-collection/item-converter-strategy/ifso-page-visit-item-converter-strategy.php');

use IfSo\Services\PluginSettingsService\PluginSettingsService;

class PageVisitsService {

	private static $instance; 

	protected $cookie_name;
	protected $settings_service;
	protected $page_visits_collection;
	protected $remove_cookie_checked;


	private function __construct() {
		$this->settings_service = PluginSettingsService::get_instance();
		$this->remove_cookie_checked = $this->settings_service->removePageVisitsCookie->get(); //get cookie auth. from settings. muliCohen
        $this->cookie_name = 'ifso_page_visits';
        if($this->remove_cookie_checked)
			$this->delete_cookie();
		$this->page_visits_collection = $this->create_page_visits_collection();
	}

	private function delete_cookie() {
			unset($_COOKIE['ifso_page_visits']);
			setcookie('ifso_page_visits', '', 1, '/');
		}
		
	private function create_page_visits_collection() {
		/* create persist strategy */
        $persistStrategy = new \IfSo_CookiePersistStrategy($this->cookie_name);
		
		/* create item converter strategy */
		$itemConverterStrategy = new \IfSo_PageVisitItemConverterStrategy();
		$save_time = $this->get_save_time_span();

		return 
			new \IfSo_PageVisitsTimedCollection( $persistStrategy, 
												$itemConverterStrategy,
												$save_time );
	}

	public static function get_instance() {
		if ( NULL == self::$instance )
			self::$instance = new PageVisitsService();

		return self::$instance;
	}

	private function get_save_time_span() {
		$options = $this->settings_service->pagesVisitedOption->get();

		$duration_value = $options->get_duration_value();
		$duration_type = $options->get_duration_type();
		$seconds_of_type = $this->get_type_seconds($duration_type);

		return $seconds_of_type * $duration_value;
	}

	/*
	 * Returns the time in seconds of the given type
	 * @type - hours/days/weeks/months
	 */
	private function get_type_seconds($type) {
		$seconds = 0;

		if ($type == 'minutes')
			$seconds = 60;
		else if ($type == 'hours')
			$seconds = 60*60;
		else if ($type == 'days')
			$seconds = 60*60*24;
		else if ($type == 'weeks')
			$seconds = 60*60*24*7;
		else if ($type == 'months')
			$seconds = 60*60*24*7*30;

		return $seconds;
	}

	public function is_visited($page_url, $operator) {
        if ($this->remove_cookie_checked) return false;

		$page_visits = $this->page_visits_collection->get_models();
		$is_visited = false;

		if ( empty( $operator ) ) {
			foreach ( $page_visits as $page ) {
				if ( $page->is_equal( $page_url ) )
					$is_visited = true;
			}
		} else {

			if ( $operator == 'url is' || $operator == 'url contains' ) {
				foreach ($page_visits as $page) {
					if ($this->are_page_urls_equal($page_url, $page->get_page(), $operator)) {
						$is_visited = true;
						break;
					}
				}
			} else {
				$is_visited = true;

				foreach ($page_visits as $page) {
					if ( !$this->are_page_urls_equal($page_url, $page->get_page(), $operator) ) {
						$is_visited = false;
						break;
					}
				}
			}

		}
		
		return $is_visited;
	}

	private function are_page_urls_equal($first_page_url, $second_page_url, $operator) {
		if($operator == 'url is' || $operator == 'url is not') {
			$first_page_url = $this->clean_page_url($first_page_url);
			$second_page_url = $this->clean_page_url($second_page_url);
		}
		

		$compare_val = false;
		
		if ( $operator == 'url contains' && 
			(strpos($second_page_url, $first_page_url) !== false ) )
			$compare_val = true;
		else if($operator == 'url is' && $second_page_url == $first_page_url) 
			$compare_val = true;
		else if($operator == 'url is not' && $second_page_url != $first_page_url)
			$compare_val = true;
		else if($operator == 'url not contains' && 
			(strpos($second_page_url, $first_page_url) === false))
			$compare_val = true;

		return $compare_val;
	}

	private function clean_page_url($page_url) {
		$page_url = trim($page_url, '/');
		$page_url = str_replace('https://', '', $page_url);
		$page_url = str_replace('http://', '', $page_url);
		$page_url = str_replace('www.', '', $page_url);

		return $page_url;
	}

	public function save_page($page_url) {
		if ( empty($page_url) || $this->remove_cookie_checked) return;

		$save_time = time() + $this->get_save_time_span();
		$this->page_visits_collection->add_page( $page_url,
												 $save_time );
	}

	public function remove_page($page_url) {
		if ( empty($page_url) || $this->remove_cookie_checked ) return;

		$this->page_visits_collection->remove_page( $page_url );
	}
}