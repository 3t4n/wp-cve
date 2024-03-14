<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.0.0
 *
 * @package    Intl
 * @subpackage Intl/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Intl
 * @subpackage Intl/includes
 * @author     Tom McCracken <tomm@getlevelten.com>
 */
class Intel_Annotation_Controller extends Intel_Entity_Controller  {

	public function __construct($entityType, $entity_info) {
		parent::__construct($entityType, $entity_info);

		add_filter('intel_sync_annotation', 'Intel_Annotation_Controller::sync_ga');
	}

	public function load($ids, $conditions = array(), $reset = FALSE) {
		global $wpdb;

		$entities = parent::load($ids, $conditions, $reset);

		// create summary for annotation
		foreach ($entities as $k => $v) {
			$entities[$k]->summary = intel_annotation_format_summary($v->message);
		}

		return $entities;
	}

	public static function sync_ga($annotation, $options = array()) {

		intel_load_include('includes/intel.annotation');

		$available_period = intel_annotation_get_latest_available_period($annotation);

		$period = !empty($options['period']) ? $options['period'] : $available_period;

		// check if new period timeframe is larger than what is currently cached in
		// annotation data.
		if (($annotation->analytics_period >= $period) && empty($options['refresh'])) {
			return $annotation;
		}

		$timeframe = !empty($timeframe['options']) ? $timeframe['options'] : array();

		if (empty($timeframe)) {
			// if period is less than a week, select the similar hours from the prior week
			// 360 secs in hour, 168 hours in a week
			if ($period < (168 * 3600)) {
				$timeframe[] = array(
					$annotation->started - (168 * 3600), // a week before start
					$annotation->started - (168 * 3600) + $period, // week prior plus period time
				);
			}
			else {
				$timeframe[] = array(
					$annotation->started - $period, // a week before start
					$annotation->started, // week prior plus period time
				);
			}
			$timeframe[] = array(
				$annotation->started,
				$annotation->started + $period,
			);
		}

		$data = self::fetch_ga_data($annotation, $timeframe, $options);

		$annotation->data['analytics'] = $data;
		$annotation->data['analytics_timeframe'] = $timeframe;
		$annotation->analytics_period = $period;
		$annotation->save();

		$annotation->setSyncProcessStatus('ga', 1);

		return $annotation;
	}

	public static function fetch_ga_data($annotation, $timeframe, $options = array()) {

    intel_load_include('includes/intel.reports');
    intel_load_include('includes/intel.ga');
		intel_include_library_file('ga/class.ga_model.php');

		$data = array();

		for ($i = 0; $i <= 1; $i++) {
			$vars = array(
				'timeframe' => date('YmdHi', $timeframe[$i][0]) . ',' . date('YmdHi', $timeframe[$i][1]),
			);
			$vars = intel_init_reports_vars('scorecard', 'scorecard', '-', '-', '-', $vars);

			$vars['ga_start_date_hour_minute'] = date('YmdHi', $vars['start_date']);
			$vars['ga_start_date_readable'] = date('D m/d/Y H:i', $vars['start_date']);
			$vars['ga_end_date_hour_minute'] = date('YmdHi', $vars['end_date']);
			$vars['ga_end_date_readable'] = date('D m/d/Y H:i', $vars['end_date']);

			$ga_data = new LevelTen\Intel\GAModel();
			$ga_data->setContext('site');
			$ga_data->setAttributeInfoAll($vars['attribute_info']);
			$ga_data->buildFilters($vars['filters'], $vars['subsite']);
			$ga_data->setDateRange($vars['start_date'], $vars['end_date']);
			$ga_data->setRequestCallback('intel_ga_feed_request_callback', array('cache_options' => $vars['cache_options']));
			$ga_data->setFeedRowsCallback('intel_get_ga_feed_rows_callback');

			$ga_data->setRequestSetting('details', 0);

			// get traffic metrics
			//$ga_data->setDebug(1);
			$ga_data->loadFeedData('entrances');
			//$ga_data->setDebug(0);

			// get valued events
			$ga_data->loadFeedData('entrances_events_valued', 'date', 0);

			$d = $ga_data->data;
			$d = $d['date']['_all'];

			$score_components = array();
  		$d['score'] = intel_score_item($d, 1, $score_components, '', 'entrance');
			$d['score_components'] = $score_components;

			$data[$i] = $d;
		}

		return $data;
	}
}
