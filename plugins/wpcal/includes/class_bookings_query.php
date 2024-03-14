<?php
/**
 * WPCal.io
 * Copyright (c) 2020 Revmakx LLC
 * revmakx.com
 */

if (!defined('ABSPATH')) {exit;}

class WPCal_Bookings_Query {
	private $service_obj;

	private static $total_bookings = '';

	private static $cached_service_objs = [];

	public function __construct() {

	}

	// public static function get_past_bookings_for_admin_client($options) {
	// 	$args = array();
	// 	$args['order_by'] = 'booking_from_time';
	// 	$args['order'] = 'DESC';

	// 	$args['to_time'] = WPCal_DateTime_Helper::unix_to_DateTime_obj($options['view_base_timing'], new DateTimeZone('UTC')); //its ok use UTC, because later it again converted to unix

	// 	$result = self::get_upcoming_and_past_bookings_for_admin_client($options, $args);
	// 	return $result;
	// }

	// public static function get_upcoming_bookings_for_admin_client($options) {
	// 	$args = array();
	// 	$args['order_by'] = 'booking_from_time';
	// 	$args['order'] = 'ASC';

	// 	$args['to_time_greater_than'] = WPCal_DateTime_Helper::unix_to_DateTime_obj($options['view_base_timing'], new DateTimeZone('UTC')); //its ok use UTC, because later it again converted to unix

	// 	$result = self::get_upcoming_and_past_bookings_for_admin_client($options, $args);
	// 	return $result;
	// }

	// public static function get_custom_bookings_for_admin_client($options) {
	// 	$args = array();
	// 	$args['order_by'] = 'booking_from_time';
	// 	$args['order'] = 'DESC';

	// 	$result = self::get_upcoming_and_past_bookings_for_admin_client($options, $args);
	// 	return $result;
	// }

	public static function get_managed_bookings_for_current_admin($options) {

		$allowed_keys = [
			'page',
			'list_type',
			'filters',
		];

		$options = wpcal_get_allowed_fields($options, $allowed_keys);

		$options = wpcal_sanitize_all($options);

		$validate_obj = new WPCal_Validate($options);
		$validate_obj->rules([
			'required' => [
				'list_type',
			],
			'requiredWithIf' => [
				['filters.date_range.from_date_ts', ['filters.when' => 'date_range']],
				['filters.date_range.to_date_ts', ['filters.when' => 'date_range']],
			],
			'integer' => [
				'page',
				'filters.admin_user_id',
				'filters.service_id',
				'filters.booking_status',
				'filters.date_range.from_date_ts',
				'filters.date_range.to_date_ts',
			],
			'in' => [
				['filters.when', ['upcoming', 'past', 'date_range', '0']],
				['list_type', ['upcoming', 'past', 'custom']],
			],
			// 'dateFormat' => [
			// 	['filters.date_range.from_date', 'Y-m-d'],
			// 	['filters.date_range.to_date', 'Y-m-d'],
			// ],
		]);

		if (!$validate_obj->validate()) {
			$validation_errors = $validate_obj->errors(); //output should be an array
			throw new WPCal_Exception('validation_errors', '', $validation_errors);
		}

		$args = array();

		$args['current_admin_user_id'] = $args['admin_user_id'] = get_current_user_id();

		$args['select_cols'] = [
			'id',
			'service_id',
			'status',
			'unique_link',
			'admin_user_id',
			'invitee_wp_user_id',
			'invitee_name',
			'invitee_email',
			'invitee_tz',
			'invitee_question_answers',
			'booking_from_time',
			'booking_to_time',
			'booking_ip',
			'location',
			'rescheduled_booking_id',
			'reschedule_cancel_reason',
			'reschedule_cancel_user_id',
			'reschedule_cancel_action_ts',
			'added_ts',
			'updated_ts',
		];

		$args['get_service_details'] = [
			'name',
			'color',
		];

		$args['filters'] = !empty($options['filters']) ? $options['filters'] : [];

		$args['status'] = 1;
		$args['order_by'] = 'booking_from_time';
		$args['order'] = 'ASC';
		$args['page'] = $options['page'];
		$args['items_per_page'] = 20;
		$args['get_plain_data'] = true;
		$args['order_by'] = 'booking_from_time';

		$args['other_admins_bookings'] = true;
		$args['get_names'] = true;
		$args['who_views'] = 'admin';

		if ($options['list_type'] == 'past' || $options['list_type'] == 'custom') {
			$args['order'] = 'DESC';
		} elseif ($options['list_type'] == 'upcoming') {
			$args['order'] = 'ASC';
		}

		if ($options['list_type'] == 'past') {
			$args['to_time'] = WPCal_DateTime_Helper::now_DateTime_obj(new DateTimeZone('UTC')); //its ok use UTC, because later it again converted to unix

		} elseif ($options['list_type'] == 'upcoming') {
			$args['to_time_greater_than'] = WPCal_DateTime_Helper::now_DateTime_obj(new DateTimeZone('UTC')); //its ok use UTC, because later it again converted to unix
		}

		if (!empty($options['filters']['service_id'])) {
			$args['service_id'] = $options['filters']['service_id'];
		} else {
			unset($args['service_id']);
		}

		if (!empty($options['filters']['admin_user_id'])) {
			$args['admin_user_id'] = $options['filters']['admin_user_id'];
		} else {
			unset($args['admin_user_id']);
		}

		if (isset($options['filters']['booking_status'])) {
			$args['status'] = $options['filters']['booking_status'];
		}

		if (!empty($options['filters']['when'])) {
			if ($options['filters']['when'] == 'upcoming') {

				$args['to_time_greater_than'] = WPCal_DateTime_Helper::now_DateTime_obj(new DateTimeZone('UTC')); //its ok use UTC, because later it again converted to unix

			} elseif ($options['filters']['when'] == 'past') {

				$args['to_time'] = WPCal_DateTime_Helper::now_DateTime_obj(new DateTimeZone('UTC')); //its ok use UTC, because later it again converted to unix

			} elseif ($options['filters']['when'] == 'date_range') {

				$args['from_time'] = WPCal_DateTime_Helper::unix_to_DateTime_obj((int) $options['filters']['date_range']['from_date_ts'], new DateTimeZone('UTC')); //its ok use UTC, because later it again converted to unix
				$args['to_time'] = WPCal_DateTime_Helper::unix_to_DateTime_obj((int) ($options['filters']['date_range']['to_date_ts'] + 86400), new DateTimeZone('UTC')); //its ok use UTC, because later it again converted to unix
			}
		}

		if (!empty($options['filters']['search_text'])) {
			$args['search_text'] = $options['filters']['search_text'];
		}

		$result = array();
		$result['bookings'] = self::_get_bookings($args);
		$total_items = self::$total_bookings;

		$result['page'] = $args['page'];
		$result['total_items'] = $total_items;
		$result['items_per_page'] = $args['items_per_page'];

		$result['eol'] = !($total_items > ($args['items_per_page'] * ($result['page'])));

		return $result;
	}

	public static function get_bookings_for_day_by_service(WPCal_Service $service_obj, $date_obj, $exclude_booking_id = null) {

		$args = array();
		$args['from_time'] = clone $date_obj;
		$args['calc_to_time_by_from_time'] = '+1 day';
		$args['service_id'] = $service_obj->get_id();
		$args['service_obj'] = $service_obj;
		$args['get_slot_obj_by'] = 'service_obj';
		$args['status'] = 1;

		if (!empty($exclude_booking_id) && is_numeric($exclude_booking_id)) {
			$args['exclude_booking_id'] = $exclude_booking_id;
		}

		return self::_get_bookings($args);
	}

	public static function get_bookings_for_day_by_admin_and_exclude_service(WPCal_Service $exclude_service_obj, $date_obj, $admin_id) {

		$args = array();
		$args['from_time'] = clone $date_obj;
		$args['calc_to_time_by_from_time'] = '+1 day';
		$args['exclude_service_id'] = $exclude_service_obj->get_id();
		$args['get_slot_obj_by'] = 'respective_service_obj';
		$args['admin_user_id'] = $admin_id;
		$args['status'] = 1;

		return self::_get_bookings($args);
	}

	private static function _get_bookings($args) {
		/**
		 * 'from_time', 'to_time' date objects from_time always >= is used, to_time always < is used
		 * 'get_slot_obj_by' => 'service_obj' (get from $args['service_obj']) | 'respective_service_obj' get on the go
		 */
		$default_args = array(
			'status' => 1,
		);

		$args = array_merge($default_args, $args);

		global $wpdb;
		$table_wpcal_bookings = $wpdb->prefix . 'wpcal_bookings';

		$after_select = '';

		if (isset($args['items_per_page']) && is_int($args['items_per_page']) && $args['items_per_page'] > 0) {
			$after_select = 'SQL_CALC_FOUND_ROWS';
		}

		$select_cols = array('id', 'service_id', 'booking_from_time', 'booking_to_time');

		if (!empty($args['select_cols'])) {
			$select_cols = $args['select_cols'];
		}

		$select_cols_imploded = wpcal_implode_for_sql($select_cols, '`');
		$query = "SELECT $after_select $select_cols_imploded FROM `$table_wpcal_bookings` as `booking` ";
		$query_where = " WHERE 1=1 ";

		if (isset($args['from_time'])) {
			$query_where .= $wpdb->prepare(" AND `booking_from_time` >=  %s", WPCal_DateTime_Helper::DateTime_Obj_to_unix($args['from_time']));
		}

		if (isset($args['calc_to_time_by_from_time']) && isset($args['from_time'])) {
			$args['to_time'] = clone $args['from_time'];
			$args['to_time']->modify($args['calc_to_time_by_from_time']);
		}

		if (isset($args['to_time'])) {
			$query_where .= $wpdb->prepare(" AND `booking_to_time` < %s", WPCal_DateTime_Helper::DateTime_Obj_to_unix($args['to_time']));
		}

		if (isset($args['to_time_greater_than'])) {
			$query_where .= $wpdb->prepare(" AND `booking_to_time` >=  %s", WPCal_DateTime_Helper::DateTime_Obj_to_unix($args['to_time_greater_than']));
		}

		if (isset($args['service_id'])) {
			$query_where .= $wpdb->prepare(" AND `service_id` =  %s", $args['service_id']);
		}

		if (isset($args['exclude_service_id'])) {
			$query_where .= $wpdb->prepare(" AND `service_id` != %s", $args['exclude_service_id']);
		}

		if (!empty($args['other_admins_bookings'])) {
			if (empty($args['current_admin_user_id'])) {
				//something wrong
				return [];
			}

			$table_services = $wpdb->prefix . 'wpcal_services';
			$table_service_admins = $wpdb->prefix . 'wpcal_service_admins';

			//sub-query
			$query_where .= " AND (";
			$query_where .= " (
				SELECT `service`.`id` FROM `$table_services` as `service` JOIN `$table_service_admins` as `service_admin` ON `service`.`id` = `service_admin`.`service_id` WHERE `service`.`id` = `booking`.`service_id`";

			if (!isset($args['admin_user_id']) || $args['admin_user_id'] == 0) {

				$query_where .= $wpdb->prepare(" AND ( (`service`.`is_manage_private` = 1 AND `service_admin`.`admin_user_id` = %s) OR (`service`.`is_manage_private` = 0)
				) ", $args['current_admin_user_id']);

			} elseif ($args['admin_user_id'] == $args['current_admin_user_id']) {
				$query_where .= $wpdb->prepare(" AND `admin_user_id` = %s", $args['admin_user_id']);

			} else {
				$query_where .= $wpdb->prepare(" AND `service`.`is_manage_private` = 0 AND `service_admin`.`admin_user_id` = %s ", $args['admin_user_id']);
			}

			$query_where .= ") OR (" . $wpdb->prepare("`admin_user_id` = %s", $args['current_admin_user_id']) . ")";

			$query_where .= ")";
		}

		if (isset($args['admin_user_id'])) {
			$query_where .= $wpdb->prepare(" AND `admin_user_id` = %s", $args['admin_user_id']);
		}

		if (isset($args['exclude_booking_id'])) {
			$query_where .= $wpdb->prepare(" AND `id` != %s", $args['exclude_booking_id']);
		}

		if (isset($args['status']) && $args['status'] !== 0 && $args['status'] !== '0') {
			$query_where .= $wpdb->prepare(" AND `status` = %s", $args['status']);
		}

		if (!empty($args['search_text'])) {
			if (is_numeric($args['search_text'])) {
				$numeric_value = $args['search_text'];
				$query_where .= $wpdb->prepare(" AND `id` = %s", $numeric_value);
			} else {
				$text_value = $args['search_text'];
				$search_text_like = '%' . $wpdb->esc_like($text_value) . '%';

				$query_where .= " AND (";

				$query_where .= $wpdb->prepare("`invitee_name` LIKE %s OR `invitee_email` LIKE %s", $search_text_like, $search_text_like);

				if (strlen($text_value) === 40) {
					$query_where .= $wpdb->prepare(" OR `unique_link` = %s", $text_value);
				}

				$query_where .= ")";
			}
		}

		$query_order = "";

		if (isset($args['order_by'])) {
			$order = "ASC";
			$order_by = $args['order_by'];
			$order_by_escaped = esc_sql($order_by);

			if (isset($args['order']) && in_array($args['order'], array('ASC', 'DESC'))) {
				$order = $args['order'];
			}
			$query_order = " ORDER BY `" . $order_by_escaped . "` $order";
		}

		// $args['order_by'] = 'booking_from_time';
		// $args['order'] = 'ASC';

		$query_limit = "";
		$offset = 0;
		$page = 0;
		if (isset($args['items_per_page']) && is_int($args['items_per_page']) && $args['items_per_page'] > 0) {
			$limit = $args['items_per_page'];

			if (isset($args['page'])) {
				$page = $args['page'];
			}

			$offset = $limit * ($page - 1);

			$query_limit = " LIMIT " . $limit . " OFFSET " . $offset;
		}

		$query = $query . $query_where . $query_order . $query_limit;

		$results = $wpdb->get_results($query);
		self::$total_bookings = $wpdb->get_var("SELECT FOUND_ROWS()");
		if (empty($results)) {
			return array();
		}

		if (!empty($args['get_service_details']) && is_array($args['get_service_details'])) {
			$allowed_service_details = ['name', 'color'];

			if (!wpcal_is_subset($allowed_service_details, $args['get_service_details'])) {
				throw new WPCal_Exception('invalid_service_details');
			}
		}

		foreach ($results as $key => $row) {

			$service_obj = self::get_service_obj_may_use_cache($row->service_id);

			if (!empty($args['get_service_details']) && is_array($args['get_service_details'])) {

				$booking_service_data = new stdClass();
				foreach ($args['get_service_details'] as $_service_feild) {
					$booking_service_data->{$_service_feild} = call_user_func([$service_obj, 'get_' . $_service_feild]);
				}
				$results[$key]->service_details = $booking_service_data;
			}

			if (!isset($args['get_plain_data']) || !$args['get_plain_data']) {

				$results[$key]->booking_from_time = WPCal_DateTime_Helper::unix_to_DateTime_obj($row->booking_from_time, $service_obj->get_tz());
				$results[$key]->booking_to_time = WPCal_DateTime_Helper::unix_to_DateTime_obj($row->booking_to_time, $service_obj->get_tz());
			}

			if (isset($args['get_slot_obj_by'])) {
				if ($args['get_slot_obj_by'] === 'service_obj') {
					$_service_obj = $args['service_obj'];
				} elseif ($args['get_slot_obj_by'] === 'respective_service_obj') {
					$_service_obj = $service_obj;
				}
				$results[$key]->slot_obj = new WPCal_Slot($_service_obj, $results[$key]->booking_from_time, $results[$key]->booking_to_time);
			}

			if (isset($row->invitee_question_answers)) {
				if (!empty($row->invitee_question_answers)) {
					$results[$key]->invitee_question_answers = json_decode($row->invitee_question_answers, true);
				}
				if (!is_array($results[$key]->invitee_question_answers)) {
					$results[$key]->invitee_question_answers = [];
				}
			}
			if (isset($row->location)) {
				if (!empty($row->location)) {
					$results[$key]->location = json_decode($row->location, true);
				}
				if (!is_array($results[$key]->location)) {
					$results[$key]->location = [];
				}
			}
			if (!empty($args['get_names'])) {
				if ($row->status == -5 || $row->status == -1) {
					$row->reschedule_cancel_user_full_name = '';
					$row->reschedule_cancel_user_full_name = wpcal_related_get_user_full_name('reschedule_cancel_person_name', $row->reschedule_cancel_user_id, $args['who_views'] ?? 'admin', null, [], ['invitee_wp_user_id' => $row->invitee_wp_user_id,
						'invitee_name' => $row->invitee_name]);
				}
				if (isset($row->admin_user_id)) {
					$row->admin_user_full_name = '';
					if (!empty($row->admin_user_id)) {
						$row->admin_user_full_name = wpcal_related_get_user_full_name('admin_full_name', $row->admin_user_id, $args['who_views'] ?? 'admin', null, [], []);
					}
				}
			}

		}

		self::$cached_service_objs = []; //clear it - cache can work once per call

		return $results;
	}

	private static function get_service_obj_may_use_cache($service_id) {
		if (!isset(self::$cached_service_objs[$service_id])) {
			self::$cached_service_objs[$service_id] = new WPCal_Service($service_id);
		}
		$service_obj = self::$cached_service_objs[$service_id];
		return $service_obj;
	}
}
