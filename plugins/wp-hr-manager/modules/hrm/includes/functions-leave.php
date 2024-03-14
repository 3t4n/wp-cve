<?php

/**
 * Get holiday between two date
 *
 * @since  0.1
 *
 * @param  date  $start_date
 * @param  date  $end_date
 *
 * @return array
 */
function wphr_hr_leave_get_holiday_between_date_range($start_date, $end_date, $employee_id = 0) {

	if (is_admin()) {
		$user_id = $employee_id;
	} else {
		$user_id = get_current_user_id();
	}

	$holiday = new \WPHR\HR_MANAGER\HRM\Models\Leave_Holiday();
	$holiday = $holiday->where(function ($condition) use ($start_date) {
		$condition->where('start', '<=', $start_date);
		$condition->where('end', '>=', $start_date);
	});
	$holiday = $holiday->orWhere(function ($condition) use ($end_date) {
		$condition->where('start', '<=', $end_date);
		$condition->where('end', '>=', $end_date);
	});
	$holiday = $holiday->orWhere(function ($condition) use ($start_date, $end_date) {
		$condition->where('start', '>=', $start_date);
		$condition->where('start', '<=', $end_date);
	});
	$holiday = $holiday->orWhere(function ($condition) use ($start_date, $end_date) {
		$condition->where('end', '>=', $start_date);
		$condition->where('end', '<=', $end_date);
	});
	$location_id = \WPHR\HR_MANAGER\HRM\Models\Employee::select('location')->where('user_id', $user_id)->get()->toArray();

	if (count($location_id)) {
		$location_id = $location_id[0]['location'];
	} else {
		$location_id = 0;
	}

	//to get holidays list ( Global + By Location )
	$result_data = $holiday->get()->where('location_id', '0')->toArray();
	$result_byLocation = $holiday->get()->where('location_id', $location_id)->toArray();

	if ($result_byLocation) {
		$results = array_merge($result_data, $result_byLocation);
	} else {
		$results = $result_data;
	}

	$holiday_extrat = [];
	$given_date_extrat = wphr_extract_dates($start_date, $end_date);
	foreach ($results as $result) {
		$date_extrat = wphr_extract_dates($result['start'], $result['end']);
		$holiday_extrat = array_merge($holiday_extrat, $date_extrat);
	}
	$extract = array_intersect($given_date_extrat, $holiday_extrat);
	return $extract;
}

/**
 * Checking is user take leave within date rang in before
 *
 * @since  0.1
 *
 * @param  string $start_date
 * @param  string $end_date
 * @param  int $user_id
 *
 * @return boolean
 */
function wphr_hrm_is_leave_recored_exist_between_date($start_date, $end_date, $user_id) {
	$format = 'Y-m-d';
	if (strtotime($start_date) == strtotime($end_date)) {
		$format = 'Y-m-d H:i:s';
	}
	$start_date = date('Y-m-d 00:00:00', strtotime($start_date));
	$end_date = date('Y-m-d 23:59:59', strtotime($end_date));
	$holiday = new \WPHR\HR_MANAGER\HRM\Models\Leave_request();
	$holiday->where('user_id', '=', $user_id);
	$holiday = $holiday->where(function ($condition) use ($start_date, $user_id) {
		$condition->where('start_date', '<=', $start_date);
		$condition->where('end_date', '>=', $start_date);
		$condition->whereIn('status', [1, 2]);
		$condition->where('user_id', '=', $user_id);
	});
	$holiday = $holiday->orWhere(function ($condition) use ($end_date, $user_id) {
		$condition->where('start_date', '<=', $end_date);
		$condition->where('end_date', '>=', $end_date);
		$condition->whereIn('status', [1, 2]);
		$condition->where('user_id', '=', $user_id);
	});
	$holiday = $holiday->orWhere(function ($condition) use ($start_date, $end_date, $user_id) {
		$condition->where('start_date', '>=', $start_date);
		$condition->where('start_date', '<=', $end_date);
		$condition->whereIn('status', [1, 2]);
		$condition->where('user_id', '=', $user_id);
	});
	$holiday = $holiday->orWhere(function ($condition) use ($start_date, $end_date, $user_id) {
		$condition->where('end_date', '>=', $start_date);
		$condition->where('end_date', '<=', $end_date);
		$condition->whereIn('status', [1, 2]);
		$condition->where('user_id', '=', $user_id);
	});
	$results = $holiday->get()->toArray();
	$holiday_extrat = [];
	$given_date_extrat = wphr_extract_dates($start_date, $end_date, $format);
	foreach ($results as $result) {
		$date_extrat = wphr_extract_dates($result['start_date'], $result['end_date'], $format);
		$holiday_extrat = array_merge($holiday_extrat, $date_extrat);
	}
	$extract = array_intersect($given_date_extrat, $holiday_extrat);
	return $extract;
}

/**
 * Checking is user take 1 day leave within time rang in before
 *
 * @since  0.1
 *
 * @param  string $start_dateTime
 * @param  string $end_dateTime
 * @param  int $user_id
 *
 * @return boolean
 */
function wphr_hrm_is_leave_recored_exist_between_dateTime($start_dateTime, $end_dateTime, $user_id) {
	global $wpdb;
	$start_date = date('Y-m-d', strtotime($start_dateTime));
	$end_date = date('Y-m-d', strtotime($end_dateTime));
	$leave_requests = new \WPHR\HR_MANAGER\HRM\Models\Leave_request();
	$leave_requests->where('user_id', '=', $user_id);
	$leave_requests = $leave_requests->where(function ($condition) use ($start_dateTime, $end_dateTime, $user_id) {
		$condition->whereIn('status', [1, 2]);
		$condition->where('user_id', $user_id);
		$condition->where('start_date', '<=', $start_dateTime);
		$condition->where('end_date', '>=', $end_dateTime);
	});
	$results = $leave_requests->get()->toArray();
	$query = $wpdb->prepare("select * from `{$wpdb->prefix}wphr_hr_leave_requests` where `status` in (1, 2) and `user_id` = %d and ( `start_date` BETWEEN %s AND %s OR  `end_date` BETWEEN %s AND %s )", $user_id, $start_dateTime, $end_dateTime, $start_dateTime, $end_dateTime);
	$results2 = $wpdb->get_results($query);
	$exist = array();
	if ($results2) {
		foreach ($results2 as $key => $value) {
			if ($start_dateTime > $value->start_date && $start_dateTime < $value->end_date || $end_dateTime > $value->start_date && $end_dateTime < $value->end_date || $start_dateTime == $value->start_date && $end_dateTime == $value->end_date) {
				$exist[] = $value;
			}
		}
	}
	return $exist;
}

function wphr_hrm_calculate_financial_year_from_date($input_date, $fy_start, $fy_end) {
	$input_year = date('Y', strtotime($input_date));
	$input_month = date('m', strtotime($input_date));
	$input_day = date('t', strtotime($input_date));
	$financial_start_date = strtotime($fy_start);
	$financial_end_date = strtotime($fy_end);
	$fsm = date('m', strtotime($fy_start));
	$fem = date('m', strtotime($fy_end));
	$fed = date('t', strtotime($fy_end));
	$fey = date('y', strtotime($fy_end));
	$check_date = date('Y-m-d', "{$input_day}-{$input_month}-{$fey}");

	if ($check_date > $financial_end_date) {
		$financial_end_date = date('Y-m-d', strtotime("{$input_year}-{$fem}-{$fed} + 1 Year"));
	} else {
		$financial_end_date = date('Y-m-d', strtotime("{$input_year}-{$fem}-{$fed}"));
	}

	$financial_start_date = date('Y-m-d', strtotime("{$financial_end_date} -12 Month +1 Day"));
	$years = array(
		'new_financial_start_date' => $financial_start_date,
		'new_financial_end_date' => $financial_end_date,
	);
	return $years;
}

/**
 * Check leave duration exist or not the plicy days
 *
 * @since  0.1
 *
 * @param  string $start_date
 * @param  string $end_date
 * @param  int $policy_id
 * @param  int $user_id
 *
 * @return boolean
 */
function wphr_hrm_is_valid_leave_duration(
	$start_date,
	$end_date,
	$policy_id,
	$user_id,
	$check_current_day_request = false
) {
	global $wpdb;
	$new_financial_start_date = $new_financial_end_date = '';
	if (!$user_id || !$policy_id) {
		return true;
	}
	$financial_start_date = date('Y-m-d', strtotime(wphr_financial_start_date()));
	$financial_end_date = date('Y-m-d', strtotime(wphr_financial_end_date()));
	/*if ($end_date > $financial_end_date) {
		            $financial_start_date = date('Y-m-d', strtotime("$financial_end_date + 1 Day"));
		            $financial_end_date = date('Y-m-d', strtotime("$financial_start_date +12 Month -1 Day"));

		            if ($end_date > $financial_end_date) {

		                $year = wphr_hrm_calculate_financial_year_from_date($end_date, $financial_start_date, $financial_end_date);

		                if ($year) {
		                    $new_financial_start_date = $year['new_financial_start_date'];
		                    $new_financial_end_date = $year['new_financial_end_date'];
		                    $error_msg = sprintf(__('Sorry, you are not allowed to send leave request between this financial year %s to %s', 'hrm'), $new_financial_start_date, $new_financial_end_date);
		                } else {
		                    $error_msg = __('Sorry, you are not allowed to send leave request between provided financial year', 'wphr');
		                }

		                wp_die($error_msg);
		            }
		        } elseif ($end_date < $financial_start_date) {
		            $financial_start_date = date('Y-m-01', strtotime("$financial_start_date -12 Month"));
		            $financial_end_date = date('Y-m-d', strtotime("$financial_start_date - 1 Day"));

		            if ($end_date < $financial_start_date || $start_date < $financial_start_date) {
		                $year = wphr_hrm_calculate_financial_year_from_date($start_date, $financial_start_date, $financial_end_date);

		                if ($year) {
		                    $new_financial_start_date = $year['new_financial_start_date'];
		                    $new_financial_end_date = $year['new_financial_end_date'];
		                    $error_msg = sprintf(__('Sorry, you are not allowed to send leave request between this financial year %s to %s', 'hrm'), $new_financial_start_date, $new_financial_end_date);
		                } else {
		                    $error_msg = __('Sorry, you are not allowed to send leave request between provided financial year', 'wphr');
		                }

		                wp_die($error_msg);
		            }
	*/
	$user_request = new \WPHR\HR_MANAGER\HRM\Models\Leave_request();
	$policy = new \WPHR\HR_MANAGER\HRM\Models\Leave_Policies();
	$user_request = $user_request->where(function ($condition) use (
		$financial_start_date,
		$financial_end_date,
		$user_id,
		$policy_id
	) {
		//$start_date = date( 'Y-m-d', strtotime( $start_date ) );
		// $end_date   = date( 'Y-m-d', strtotime( $end_date ) );
		$condition->where('start_date', '>=', $financial_start_date);
		$condition->where('end_date', '<=', $financial_end_date);
		$condition->where('user_id', '=', $user_id);
		$condition->where('policy_id', '=', $policy_id);
		$condition->where('status', '!=', 3);
	});
	$working_hours = get_employee_working_hours($user_id);
	$requested_hours = 0;

	if ($user_request) {
		$request_list = $user_request->get()->toArray();
		if (is_array($request_list) && count($request_list)) {
			foreach ($request_list as $request) {
				$start_time = strtotime($request['start_date']);
				$end_time = strtotime($request['end_date']);
				$hours = ($end_time - $start_time) / 3600;

				if ($hours < $working_hours) {
					$requested_hours += $hours;
				} else {
					$requested_hours += $request['days'] * $working_hours;
				}

			}
		}
	}

	$user_enti_count = $user_request->sum('days');
	$policy_count = $policy->where('id', '=', $policy_id)->pluck('value');
	$working_day = wphr_hr_get_work_days_without_off_day($start_date, $end_date);
	//wphr_hr_get_work_days_between_dates( $start_date, $end_date );
	//wphr_hr_get_work_days_without_holiday
	$apply_days = $working_day['total'] + $user_enti_count;
	$apply_hours = $requested_hours + $working_day['total'] * $working_hours;
	$policy_hours = $policy_count * $working_hours;
	if (!$check_current_day_request && $working_day['total'] == 1 && $policy_hours > $requested_hours) {
		return true;
	}

	if ($check_current_day_request) {
		$start_time = strtotime($start_date);
		$end_time = strtotime($end_date);
		$hours = ($end_time - $start_time) / 3600;
		if ($hours < $working_hours) {
			$apply_hours = $requested_hours + $hours;
		}
	}

	if ($apply_hours > $policy_hours) {
		//if ( $apply_days >  $policy_count ) {
		return false;
	}
	return true;
}

/**
 * Leave request time checking the apply date duration with the financial date duration
 *
 * @since  0.1
 *
 * @param  string $start_date
 * @param  string $end_date
 *
 * @return boolean
 */
function wphr_hrm_is_valid_leave_date_range_within_financial_date_range($start_date, $end_date) {
	$financial_start_date = date('Y-m-d', strtotime(wphr_financial_start_date()));
	$financial_end_date = date('Y-m-d', strtotime(wphr_financial_end_date()));
	$apply_start_date = date('Y-m-d', strtotime($start_date));
	$apply_end_date = date('Y-m-d', strtotime($end_date));
	if ($financial_start_date > $apply_start_date || $apply_start_date > $financial_end_date) {
		return false;
	}
	if ($financial_start_date > $apply_end_date || $apply_end_date > $financial_end_date) {
		return false;
	}
	return true;
}

/**
 * Insert a new leave policy
 *
 * @since 0.1
 *
 * @param array $args
 *
 * @return int $policy_id
 */
function wphr_hr_leave_insert_policy($args = array()) {
	$defaults = array(
		'id' => null,
		'name' => '',
		'value' => 0,
		'color' => '',
		'description' => '',
	);
	$args = wp_parse_args($args, $defaults);
	// some validation
	if (empty($args['name'])) {
		return new WP_Error('no-name', __('No name provided.', 'wphr'));
	}
	if (!intval($args['value'])) {
		return new WP_Error('no-value', __('No duration provided.', 'wphr'));
	}
	$args['name'] = sanitize_text_field($args['name']);
	$policy_id = (int) $args['id'];
	unset($args['id']);
	$leave_policies = new \WPHR\HR_MANAGER\HRM\Models\Leave_Policies();

	if (!$policy_id) {
		// insert a new
		$leave_policy = $leave_policies->create($args);

		if (!empty($leave_policy)) {
			do_action('wphr_hr_leave_policy_new', $leave_policy, $args);
			return $leave_policy;
		}

	} else {
		do_action('wphr_hr_leave_before_policy_updated', $policy_id, $args);

		if ($leave_policies->find($policy_id)->update($args)) {
			do_action('wphr_hr_leave_after_policy_updated', $policy_id, $args);
			return $policy_id;
		}

	}

}

/**
 * Apply policy in existing employee
 *
 * @since 0.1
 * @since 1.2.0 Using `wphr_hr_apply_policy_to_employee` for both Immediate and
 *              Scheduled policy when `instant_apply` is true
 *
 * @param  object $policy Leave_Policies model
 * @param  array $args
 *
 * @return void
 */
function wphr_hr_apply_policy_existing_employee($policy, $args) {
	if (!wphr_validate_boolean($args['instant_apply'])) {
		return;
	}
	wphr_hr_apply_policy_to_employee($policy);
}

/**
 * Apply a leave policy to all or filtered employees
 *
 * @since 1.2.0
 *
 * @param object $policy      Leave_Policies eloquent object
 * @param array $employee_ids Employee ids
 *
 * @return void
 */
function wphr_hr_apply_policy_to_employee($policy, $employee_ids = array()) {
	if (is_int($policy)) {
		$policy = $policy = \WPHR\HR_MANAGER\HRM\Models\Leave_Policies::find($policy);
	}
	$db = \WPHR\ORM\Eloquent\Facades\DB::instance();
	$prefix = $db->db->prefix;
	$employees = $db->table('wphr_hr_employees as employee')->select('employee.user_id')->leftJoin("{$prefix}usermeta as gender", function ($join) {
		$join->on('employee.user_id', '=', 'gender.user_id')->where('gender.meta_key', '=', 'gender');
	})->leftJoin("{$prefix}usermeta as marital_status", function ($join) {
		$join->on('employee.user_id', '=', 'marital_status.user_id')->where('marital_status.meta_key', '=', 'marital_status');
	})->where('status', '=', 'active');
	if (!empty($employee_ids) && is_array($employee_ids)) {
		$employees->whereIn('employee.user_id', $employee_ids);
	}
	if ($policy->department > 0) {
		$employees->where('department', $policy->department);
	}
	if ($policy->designation > 0) {
		$employees->where('designation', $policy->designation);
	}
	if ($policy->location > 0) {
		$employees->where('location', $policy->location);
	}
	if ($policy->gender != -1) {
		$employees->where('gender.meta_value', $policy->gender);
	}
	if ($policy->marital != -1) {
		$employees->where('marital_status.meta_value', $policy->marital);
	}

	if ($policy->activate == 2 && !empty($policy->execute_day)) {
		$current_date = date('Y-m-d', current_time('timestamp'));
		$employees->where($db->raw("DATEDIFF( '{$current_date}', `employee`.`hiring_date` )"), '>=', $policy->execute_day);
	}

	$employees = $employees->get();
	if (!empty($employees)) {
		foreach ($employees as $employee) {
			wphr_hr_apply_leave_policy($employee->user_id, $policy);
		}
	}
}

/**
 * Assign entitlement
 *
 * @since 0.1
 * @since 1.2.0 Calculate from_date and to_date based on policy
 *              effective_date and financial start/end dates
 *
 * @param  int    $user_id
 * @param  object $leave_policy
 *
 * @return void
 */
function wphr_hr_apply_leave_policy($user_id, $policy) {
	$financial_year = wphr_get_financial_year_dates();
	$from_date = (!empty($policy->effective_date) ? $policy->effective_date : $financial_year['start']);
	$from_date_timestamp = strtotime($from_date);
	$financial_year_start_timestamp = strtotime($financial_year['start']);

	if ($from_date_timestamp < $financial_year_start_timestamp) {
		$from_date = date('Y-m-d 00:00:00', $financial_year_start_timestamp);
	} else {
		$from_date = date('Y-m-d 00:00:00', $from_date_timestamp);
	}

	$to_date = $financial_year['end'];
	$financial_year_end_timestamp = strtotime($financial_year['end']);

	if ($from_date_timestamp > $financial_year_end_timestamp) {
		$financial_year_end_timestamp += YEAR_IN_SECONDS;
		$to_date = date('Y-m-d 23:59:59', $financial_year_end_timestamp);
	}

	$policy = [
		'user_id' => $user_id,
		'policy_id' => $policy->id,
		'days' => $policy->value,
		'from_date' => $from_date,
		'to_date' => $to_date,
		'comments' => $policy->description,
	];
	wphr_hr_leave_insert_entitlement($policy);
}

/**
 * Insert a new policy entitlement for an employee
 *
 * @since 0.1
 * @since 1.2.0 Use `wphr_get_financial_year_dates` for financial start and end dates
 *
 * @param  array $args
 *
 * @return int|object New entitlement id or WP_Error object
 */
function wphr_hr_leave_insert_entitlement($args = array()) {
	global $wpdb;

	if (isset($args['user_id']) && $args['user_id']) {
		$financial_year = wphr_get_financial_year_dates_by_user($args['user_id']);
	} else {
		$financial_year = wphr_get_financial_year_dates();
	}

	$defaults = array(
		'id' => null,
		'user_id' => 0,
		'policy_id' => 0,
		'days' => 0,
		'from_date' => $financial_year['start'],
		'to_date' => $financial_year['end'],
		'comments' => '',
		'status' => 1,
		'created_by' => get_current_user_id(),
		'created_on' => current_time('mysql'),
	);
	$fields = wp_parse_args($args, $defaults);
	if (!intval($fields['user_id'])) {
		return new WP_Error('no-user', __('No employee provided.', 'wphr'));
	}
	if (!intval($fields['policy_id'])) {
		return new WP_Error('no-policy', __('No policy provided.', 'wphr'));
	}
	if (empty($fields['from_date']) || empty($fields['to_date'])) {
		return new WP_Error('no-date', __('No date provided.', 'wphr'));
	}
	$entitlement = new \WPHR\HR_MANAGER\HRM\Models\Leave_Entitlement();
	$user_id = intval($fields['user_id']);
	$policy_id = intval($fields['policy_id']);
	$financial_start_date = ($fields['from_date'] ? $fields['from_date'] : $financial_year['start']);
	$financial_end_date = ($fields['to_date'] ? $fields['to_date'] : $financial_year['end']);
	$entitlement = $entitlement->where(function ($condition) use ($user_id, $policy_id, $fields) {
		$financial_start_date = ($fields['from_date'] ? $fields['from_date'] : $financial_year['start']);
		$financial_end_date = ($fields['to_date'] ? $fields['to_date'] : $financial_year['end']);
		$condition->where('from_date', '>=', $financial_start_date);
		$condition->where('to_date', '<=', $financial_end_date);
		$condition->where('user_id', '=', $user_id);
		$condition->where('policy_id', '=', $policy_id);
	});
	$existing_entitlement = $entitlement->get();
	if ($existing_entitlement->count()) {
		return $existing_entitlement->first()->id;
	}
	$diff = $diff1 = $diff2 = 0;
	$joined_date = \WPHR\HR_MANAGER\HRM\Models\Employee::select('hiring_date')->where('user_id', $user_id)->get()->toArray();
	$joined_date = date('Y-m-d H:i:s', strtotime($joined_date[0]['hiring_date']));
	$policy = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}wphr_hr_leave_policies` WHERE `id` = %d", $policy_id));
	$from_date = (!empty($policy->effective_date) ? $policy->effective_date : $financial_start_date);
	$from_date_timestamp = strtotime($from_date);
	$financial_year_start_timestamp = strtotime($financial_start_date);

	if ($from_date_timestamp < $financial_year_start_timestamp) {
		$from_date = date('Y-m-d 00:00:00', $financial_year_start_timestamp);
	} else {
		$from_date = date('Y-m-d 00:00:00', $from_date_timestamp);
	}

	$to_date = $financial_end_date;
	$financial_year_end_timestamp = strtotime($financial_end_date);

	if ($from_date_timestamp > $financial_year_end_timestamp) {
		$financial_year_end_timestamp += YEAR_IN_SECONDS;
		$to_date = date('Y-m-d 23:59:59', $financial_year_end_timestamp);
	}

	if (strtotime($from_date) < strtotime($joined_date) && strtotime($to_date) > strtotime($joined_date)) {
		$ts1 = strtotime($from_date);
		$ts2 = strtotime($joined_date);
		$year1 = date('Y', $ts1);
		$year2 = date('Y', $ts2);
		$month1 = date('m', $ts1);
		$month2 = date('m', $ts2);
		$diff1 = ($year2 - $year1) * 12 + ($month2 - $month1);
		if ($diff1 < 12 && $diff1 > 0) {

			if ($policy->execute_day) {
				$execute_date = date('Y-m-d H:i:s', strtotime($joined_date . " + {$policy->execute_day} day"));
				$ts3 = strtotime($execute_date);
				$year3 = date('Y', $ts3);
				$month3 = date('m', $ts3);
				$diff2 = ($year3 - $year2) * 12 + ($month3 - $month2);
			}

		}
		$diff = $diff1 + $diff2;
		$sub_from_days = ceil($fields['days'] * $diff / 12);
		//echo "$diff : $sub_from_days : ". $fields['days'];die;
		$fields['days'] -= $sub_from_days;
	}

	$wpdb->insert($wpdb->prefix . 'wphr_hr_leave_entitlements', $fields);
	do_action('wphr_hr_leave_insert_new_entitlement', $wpdb->insert_id, $fields);
	return $wpdb->insert_id;
}

/**
 * Apply `Immediately` type policies on new employee
 *
 * @since 1.2.0
 *
 * @param int $user_id Employee user_id provided by `wphr_hr_employee_new` hook
 *
 * @return void
 */
function wphr_hr_apply_policy_on_new_employee($user_id) {
	$policies = \WPHR\HR_MANAGER\HRM\Models\Leave_Policies::where('activate', 1)->get();
	$policies->each(function ($policy) use ($user_id) {
		wphr_hr_apply_policy_to_employee($policy, [$user_id]);
	});
}

/**
 * Apply `Scheduled` type policies on new employee
 *
 * @since 1.2.0
 *
 * @return void
 */
function wphr_hr_apply_scheduled_policies() {
	$policies = \WPHR\HR_MANAGER\HRM\Models\Leave_Policies::where('activate', 2)->get();
	$policies->each(function ($policy) {
		wphr_hr_apply_policy_to_employee($policy);
	});
}

/**
 * Insert a leave holiday
 *
 * @since 0.1
 *
 * @param array $args
 *
 * @return integer [$holiday_id]
 */
function wphr_hr_leave_insert_holiday($args = array()) {
	$defaults = array(
		'id' => null,
		'title' => '',
		'start' => current_time('mysql'),
		'end' => '',
		'description' => '',
		'location_id' => 0,
	);
	$args = wp_parse_args($args, $defaults);
	// some validation
	if (empty($args['title'])) {
		return new WP_Error('no-name', __('No title provided.', 'wphr'));
	}
	if (empty($args['start'])) {
		return new WP_Error('no-value', __('No start date provided.', 'wphr'));
	}
	if (empty($args['end'])) {
		return new WP_Error('no-value', __('No end date provided.', 'wphr'));
	}
	$args['title'] = sanitize_text_field($args['title']);
	$holiday_id = (int) $args['id'];
	unset($args['id']);
	$holiday = new \WPHR\HR_MANAGER\HRM\Models\Leave_Holiday();

	if (!$holiday_id) {
		// insert a new
		$leave_policy = $holiday->create($args);

		if ($leave_policy) {
			do_action('wphr_hr_new_holiday', $leave_policy->insert_id, $args);
			return $leave_policy->id;
		}

	} else {
		do_action('wphr_hr_before_update_holiday', $holiday_id, $args);

		if ($holiday->find($holiday_id)->update($args)) {
			do_action('wphr_hr_after_update_holiday', $holiday_id, $args);
			return $holiday_id;
		}

	}

}

/**
 * Get all leave policies with different condition
 *
 * @since 0.1
 *
 * @param array $args
 *
 * @return array
 */
function wphr_hr_leave_get_policies($args = array()) {
	$defaults = array(
		'number' => 20,
		'offset' => 0,
		'orderby' => 'name',
		'order' => 'ASC',
	);
	$args = wp_parse_args($args, $defaults);
	$cache_key = 'wphr-leave-pol';
	$policies = wp_cache_get($cache_key, 'wphr');

	if (false === $policies) {
		$policies = wphr_array_to_object(\WPHR\HR_MANAGER\HRM\Models\Leave_Policies::select(array(
			'id',
			'name',
			'value',
			'color',
			'department',
			'designation',
			'gender',
			'marital',
			'activate',
			'execute_day',
			'effective_date',
			'location',
			'description',
		))->skip($args['offset'])->take($args['number'])->orderBy($args['orderby'], $args['order'])->get()->toArray());
		wp_cache_set($cache_key, $policies, 'wphr');
	}

	return $policies;
}

/**
 * Fetch a leave policy by policy id
 *
 * @since 0.1
 * @since 1.2.0 Return Eloquent Leave_Policies model
 *
 * @param integer $policy_id
 *
 * @return \stdClass
 */
function wphr_hr_leave_get_policy($policy_id) {
	return \WPHR\HR_MANAGER\HRM\Models\Leave_Policies::find($policy_id);
}

/**
 * Count total leave policies
 *
 * @since 0.1
 *
 * @return integer
 */
function wphr_hr_count_leave_policies() {
	return \WPHR\HR_MANAGER\HRM\Models\Leave_Policies::count();
}

/**
 * Fetch all holidays by company
 *
 * @since 0.1
 *
 * @param array $args
 *
 * @return array
 */
function wphr_hr_get_holidays($args = array()) {
	$defaults = array(
		'number' => 20,
		'offset' => 0,
		'orderby' => 'created_at',
		'order' => 'DESC',
	);
	$args = wp_parse_args($args, $defaults);
	$holiday = new \WPHR\HR_MANAGER\HRM\Models\Leave_Holiday();
	$holiday_results = $holiday->select(array(
		'id',
		'title',
		'start',
		'end',
		'description',
	))->where('location_id', 0);
	$holiday_results = wphr_hr_holiday_filter_param($holiday_results, $args);
	//if not search then execute nex code

	if (isset($args['id']) && !empty($args['id'])) {
		$id = intval($args['id']);
		$holiday_results = $holiday_results->where('id', '=', "{$id}");
	}

	$cache_key = 'wphr-get-holidays-' . md5(serialize($args));
	$holidays = wp_cache_get($cache_key, 'wphr');

	if (false === $holidays) {

		if ($args['number'] == '-1') {
			$holidays = wphr_array_to_object($holiday_results->get()->toArray());
		} else {
			$holidays = wphr_array_to_object($holiday_results->skip($args['offset'])->take($args['number'])->orderBy($args['orderby'], $args['order'])->get()->toArray());
		}

		wp_cache_set($cache_key, $holidays, 'wphr');
	}

	return $holidays;
}

/**
 * Fetch all holidays by Location
 *
 * @since 0.1
 *
 * @param array $args
 *
 * @return array
 */
function wphr_hr_get_holidaysByLocation($args = array()) {
	$defaults = array(
		'number' => 20,
		'offset' => 0,
		'orderby' => 'created_at',
		'order' => 'DESC',
		'location_id' => 0,
	);
	$args = wp_parse_args($args, $defaults);
	$holidayByLocation = new \WPHR\HR_MANAGER\HRM\Models\Leave_Holiday();
	$holiday_results = $holidayByLocation->select(array(
		'id',
		'title',
		'start',
		'end',
		'description',
		'location_id',
	));
	$holiday_results = wphr_hr_holiday_filter_param($holiday_results, $args);
	//if not search then execute nex code

	if (isset($args['id']) && !empty($args['id'])) {
		$id = intval($args['id']);
		$holiday_results = $holiday_results->where('id', '=', $id);
	}

	$cache_key = 'wphr-get-holidays-' . md5(serialize($args));
	$holidays = wp_cache_get($cache_key, 'wphr');

	if (false === $holidays) {

		if ($args['number'] == '-1') {
			$holidays = wphr_array_to_object($holiday_results->get()->toArray());
		} else {
			$holidays = wphr_array_to_object($holiday_results->skip($args['offset'])->take($args['number'])->orderBy($args['orderby'], $args['order'])->get()->toArray());
		}

		wp_cache_set($cache_key, $holidays, 'wphr');
	}

	return $holidays;
}

/**
 * Count total holidays
 *
 * @since 0.1
 *
 * @return \stdClass
 */
function wphr_hr_count_holidays($args) {
	$holiday = new \WPHR\HR_MANAGER\HRM\Models\Leave_Holiday();
	$holiday = wphr_hr_holiday_filter_param($holiday, $args);
	return $holiday->count();
}

/**
 * Filter parameter for holidays
 *
 * @since 0.1
 *
 * @param  object $holiday
 * @param  array $args
 *
 * @return object
 */
function wphr_hr_holiday_filter_param($holiday, $args) {
	$args_s = (isset($args['s']) ? $args['s'] : '');

	if ($args_s && !empty($args['s'])) {
		if (isset($_GET['subpage']) && !empty($_GET['subpage'])) {
			$args['location_id'] = 0;
		}
		//$holiday = $holiday->where( 'title', 'LIKE',  "%$args_s%" );
		$holiday = $holiday->Where(function ($q) {
			$q->where('description', 'LIKE', '%' . sanitize_text_field($_GET['s']) . '%')->orwhere('title', 'LIKE', '%' . sanitize_text_field($_GET['s']) . '%');
		});
	}

	if (isset($args['from']) && !empty($args['from'])) {
		$holiday = $holiday->where('start', '>=', $args['from']);
	}
	if (isset($args['to']) && !empty($args['to'])) {
		$holiday = $holiday->where('end', '<=', $args['to']);
	}

	if (isset($args['location_id'])) {
		$holiday = $holiday->where('location_id', '!=', 0);
	} else {
		$holiday = $holiday->where('location_id', 0);
	}

	/* if ( isset( $args['s'] ) && ! empty( $args['s'] ) ) {
		       $holiday = $holiday->orWhere( 'description', 'LIKE',  "%$args_s%" );
	*/
	return $holiday;
}

/**
 * Remove holidays
 *
 * @since 0.1
 *
 * @return \stdClass
 */
function wphr_hr_delete_holidays($holidays_id) {

	if (is_array($holidays_id)) {
		foreach ($holidays_id as $key => $holiday_id) {
			do_action('wphr_hr_leave_holiday_delete', $holiday_id);
		}
		\WPHR\HR_MANAGER\HRM\Models\Leave_Holiday::destroy($holidays_id);
	} else {
		do_action('wphr_hr_leave_holiday_delete', $holidays_id);
		return \WPHR\HR_MANAGER\HRM\Models\Leave_Holiday::find($holidays_id)->delete();
	}

}

/**
 * Get policies as formatted for dropdown
 *
 * @since 0.1
 *
 * @return array
 */
function wphr_hr_leave_get_policies_dropdown_raw() {
	$policies = wphr_hr_leave_get_policies(array(
		'number' => 999,
	));
	$dropdown = array();
	foreach ($policies as $policy) {
		$dropdown[$policy->id] = stripslashes($policy->name);
	}
	return $dropdown;
}

/**
 * Delete a policy
 *
 * @since 0.1
 *
 * @param  int|array $policy_ids
 *
 * @return void
 */
function wphr_hr_leave_policy_delete($policy_ids) {
	if (!is_array($policy_ids)) {
		$policy_ids = [$policy_ids];
	}
	$policies = \WPHR\HR_MANAGER\HRM\Models\Leave_Policies::find($policy_ids);
	$policies->each(function ($policy) {
		$has_request = $policy->leave_requests()->count();

		if (!$has_request) {
			$policy->entitlements()->delete();
			$policy->delete();
			do_action('wphr_hr_leave_policy_delete', $policy);
		}

	});
}

/**
 * Get assign policies according to employee entitlement
 *
 * @since 0.1
 *
 * @param  integer $employee_id
 *
 * @return boolean|array
 */
function wphr_hr_get_assign_policy_from_entitlement($employee_id) {
	global $wpdb;
	$data = [];
	$dropdown = [];
	$policy = new \WPHR\HR_MANAGER\HRM\Models\Leave_Policies();
	$en = new \WPHR\HR_MANAGER\HRM\Models\Leave_Entitlement();
	$policy_tb = $wpdb->prefix . 'wphr_hr_leave_policies';
	$en_tb = $wpdb->prefix . 'wphr_hr_leave_entitlements';
	$financial_start_date = wphr_financial_start_date();
	$financial_end_date = wphr_financial_end_date();
	$emp_financial_year = wphr_get_financial_year_dates_by_user($employee_id);

	if (is_array($emp_financial_year) && isset($emp_financial_year['start'])) {
		$financial_start_date = $emp_financial_year['start'];
		$financial_end_date = $emp_financial_year['end'];
	}

	$policies = \WPHR\HR_MANAGER\HRM\Models\Leave_Policies::select($policy_tb . '.name', $policy_tb . '.id')->leftjoin(
		$en_tb,
		$en_tb . '.policy_id',
		'=',
		$policy_tb . '.id'
	)->where($en_tb . '.user_id', $employee_id)->where('from_date', '>=', $financial_start_date)->where('to_date', '<=', $financial_end_date)->distinct()->get()->toArray();

	if (!empty($policies)) {
		foreach ($policies as $policy) {
			$dropdown[$policy['id']] = stripslashes($policy['name']);
		}
		return $dropdown;
	}

	return false;
}

/**
 * Add a new leave request
 *
 * @since 0.1
 *
 * @param  array $args
 *
 * @return integet request_id
 */
function wphr_hr_leave_insert_request($args = array()) {
	global $wpdb;
	$defaults = array(
		'user_id' => 0,
		'leave_policy' => 0,
		'start_date' => current_time('mysql'),
		'end_date' => current_time('mysql'),
		'start_time' => ' 00:00:00',
		'end_time' => ' 23:59:5',
		'reason' => '',
		'status' => 0,
	);
	$args = wp_parse_args($args, $defaults);
	if (empty($user_id)) {
		$user_id = $args['user_id'];
	}
	if (empty($leave_policy)) {
		$leave_policy = $args['leave_policy'];
	}
	if (empty($start_date)) {
		$start_date = $args['start_date'];
	}
	if (empty($end_date)) {
		$end_date = $args['end_date'];
	}
	if (empty($reason)) {
		$reason = $args['reason'];
	}

	if (empty($start_time)) {
		$start_time = $args['start_time'];
	} else {
		$start_time = '00:00:00';
	}

	if (empty($end_time)) {
		$end_time = $args['end_time'];
	} else {
		$end_time = '00:00:00';
	}

	if (!intval($user_id)) {
		return new WP_Error('no-employee', __('No employee ID provided.', 'wphr'));
	}
	if (!intval($leave_policy)) {
		return new WP_Error('no-policy', __('No leave policy provided.', 'wphr'));
	}
	$period = wphr_hr_get_work_days_between_dates($start_date, $end_date, $user_id);
	if (is_wp_error($period)) {
		return $period;
	}

	if (isset($args['length_hours']) && !empty($args['length_hours']) && $args['length_hours'] != '') {
		$length_hours = $args['length_hours'];
	} else {
		$length_hours = 8;
	}

	// prepare the periods
	$leaves = array();
	if ($period['days']) {
		foreach ($period['days'] as $date) {
			if ($date['count'] != 0) {
				$leaves[] = array(
					'date' => $date['date'],
					'length_hours' => $length_hours,
					'length_days' => '1.00',
					'start_time' => $start_time,
					'end_time' => $end_time,
					'duration_type' => 1,
				);
			}
		}
	}

	if ($leaves) {
		$request = apply_filters('wphr_hr_leave_new_args', [
			'user_id' => $user_id,
			'policy_id' => $leave_policy,
			'days' => count($leaves),
			'start_date' => $start_date,
			'end_date' => $end_date,
			'reason' => $reason,
			'status' => 2,
			'created_by' => get_current_user_id(),
			'created_on' => current_time('mysql'),
		]);
		//if (is_admin()) {
		$record_exist = wphr_hrm_is_leave_recored_exist_between_dateTime($start_date, $end_date, $user_id);
		//}

		if (count($record_exist) == 0) {

			if ($wpdb->insert($wpdb->prefix . 'wphr_hr_leave_requests', $request)) {
				$request_id = $wpdb->insert_id;
				foreach ($leaves as $leave) {
					$leave['request_id'] = $request_id;
					$wpdb->insert($wpdb->prefix . 'wphr_hr_leaves', $leave);
				}
				do_action(
					'wphr_hr_leave_new',
					$request_id,
					$request,
					$leaves
				);
				return $request_id;
			}

		} else {
			return false;
		}

	}

	return false;
}

/**
 * Fetch a single request
 *
 * @param  int  $request_id
 *
 * @return object
 */
function wphr_hr_get_leave_request($request_id) {
	global $wpdb;
	$sql = "SELECT req.id, req.user_id, u.display_name, req.policy_id, pol.name as policy_name, req.status, req.reason, req.comments, req.created_on, req.days, req.start_date, req.end_date\r\n        FROM {$wpdb->prefix}wphr_hr_leave_requests AS req\r\n        LEFT JOIN {$wpdb->prefix}wphr_hr_leave_policies AS pol ON pol.id = req.policy_id\r\n        LEFT JOIN {$wpdb->users} AS u ON req.user_id = u.ID\r\n        WHERE req.id = %d";
	$row = $wpdb->get_row($wpdb->prepare($sql, $request_id));
	return $row;
}

/**
 * Fetch the leave requests
 *
 * @since 0.1
 *
 * @param  array   $args
 *
 * @return array
 */
function wphr_hr_get_leave_requests($args = array()) {
	global $wpdb;
	$defaults = array(
		'user_id' => 0,
		'policy_id' => 0,
		'status' => 1,
		'is_archived' => 0,
		'year' => date('Y'),
		'number' => 20,
		'offset' => 0,
		'orderby' => 'created_on',
		'order' => 'DESC',
	);
	$args = wp_parse_args($args, $defaults);
	$where = '';

	if ('all' != $args['status'] && $args['status'] != '') {

		if (empty($where)) {
			$where .= " WHERE";
		} else {
			$where .= " AND";
		}

		if (is_array($args['status'])) {
			$where .= " req.status IN(" . implode(",", array_map('intval', $args['status'])) . ") ";
		} else {
			$where .= " req.status = " . intval($args['status']) . " ";
		}

	}

	if ($args['user_id'] != '0') {

		if (empty($where)) {
			$where .= " WHERE req.user_id = " . intval($args['user_id']);
		} else {
			$where .= " AND req.user_id = " . intval($args['user_id']);
		}

	}
	if (isset($args['user_id_in']) && is_array($args['user_id_in'])) {

		if (empty($where)) {
			$where .= " WHERE req.user_id IN (" . implode(',', $args['user_id_in']) . " )";
		} else {
			$where .= " AND req.user_id IN (" . implode(',', $args['user_id_in']) . " )";
		}

	}
	if ($args['policy_id']) {

		if (empty($where)) {
			$where .= " WHERE req.policy_id = " . intval($args['policy_id']);
		} else {
			$where .= " AND req.policy_id = " . intval($args['policy_id']);
		}

	}

	if (!empty($args['year'])) {
		$from_date = $args['year'] . '-01-01';
		$to_date = $args['year'] . '-12-31';

		if (empty($where)) {
			$where .= " WHERE req.start_date >= date('{$from_date}') AND req.start_date <= date('{$to_date}')";
		} else {
			$where .= " AND req.start_date >= date('{$from_date}') AND req.start_date <= date('{$to_date}')";
		}

	}

	if ($args['is_archived'] == 1) {

		if (empty($where)) {
			$where .= " WHERE req.is_archived = " . $args['is_archived'];
		} else {
			$where .= " AND req.is_archived = " . $args['is_archived'];
		}

	} else {

		if (empty($where)) {
			$where .= " WHERE req.is_archived = 0";
		} else {
			$where .= " AND req.is_archived = 0";
		}

	}

	$cache_key = 'wphr_hr_leave_requests_' . md5(serialize($args));
	$requests = wp_cache_get($cache_key, 'wphr');
	$limit = ($args['number'] == '-1' ? '' : 'LIMIT %d, %d');
	$table_name = $wpdb->prefix . 'wphr_hr_leave_requests';
	$sql = "SELECT req.id, req.user_id, u.display_name, req.policy_id, pol.name as policy_name, req.status, req.reason, req.comments, req.created_on, req.days, req.start_date, req.end_date FROM {$table_name} as req LEFT JOIN {$wpdb->prefix}wphr_hr_leave_policies AS pol ON pol.id = req.policy_id LEFT JOIN {$wpdb->users} AS u ON req.user_id = u.ID {$where} ORDER BY {$args['orderby']} {$args['order']} {$limit}";

	if ($requests === false) {

		if ($args['number'] == '-1') {
			$requests = $wpdb->get_results($sql);
		} else {
			$requests = $wpdb->get_results($wpdb->prepare($sql, absint($args['offset']), absint($args['number'])));
		}

		wp_cache_set(
			$cache_key,
			$requests,
			'wphr',
			HOUR_IN_SECONDS
		);
	}

	return $requests;
}

/**
 * Get leave requests count
 *
 * @since 0.1
 *
 * @return array
 */
function wphr_hr_leave_get_requests_count($args = array()) {
	global $wpdb;
	$statuses = wphr_hr_leave_request_get_statuses();
	$counts = array();
	$cache_key = 'wphr-hr-leave-request-counts';
	$results = wp_cache_get($cache_key, 'wphr');
	$user_id_in = '';
	$user_id_list = (isset($args['user_id_in']) && is_array($args['user_id_in'])) ? $args['user_id_in'] : [];
	if (isset($args['user_id_in']) && is_array($args['user_id_in'])) {
		$user_id_in = 'AND user_id IN (' . implode(',', array_fill(0, count($args['user_id_in']), '%d')) . ') ';
	}
	foreach ($statuses as $status => $label) {
		$counts[$status] = array(
			'count' => 0,
			'label' => $label,
		);

		if ($status == 4) {
			$sql2 = "SELECT COUNT(id) as num FROM {$wpdb->prefix}wphr_hr_leave_requests WHERE status = %d AND is_archived = %d {$user_id_in} GROUP BY status;";
			$data = array(1, 1) + $user_id_list;
			$archived_cnt = $wpdb->get_row($wpdb->prepare($sql2, $data));
			if ($archived_cnt) {
				$counts[$status] = array(
					'count' => $archived_cnt->num,
					'label' => $label,
				);
			}
		} elseif ($status == 1) {
			$sql3 = "SELECT COUNT(id) as num FROM {$wpdb->prefix}wphr_hr_leave_requests WHERE status = %d AND is_archived = %d {$user_id_in} GROUP BY status;";
			$data = array(1, 0) + $user_id_list;
			$approved_cnt = $wpdb->get_row($wpdb->prepare($sql3, $data));
			if ($approved_cnt) {
				$counts[$status] = array(
					'count' => $approved_cnt->num,
					'label' => $label,
				);
			}
		}

	}

	if (false === $results) {
		$sql = "SELECT status, COUNT(id) as num FROM {$wpdb->prefix}wphr_hr_leave_requests WHERE status != %d {$user_id_in} GROUP BY status;";
		$data = array(0) + $user_id_list;
		$results = $wpdb->get_results($wpdb->prepare($sql, $data));
		wp_cache_set($cache_key, $results, 'wphr');
	}

	foreach ($results as $row) {
		if (array_key_exists($row->status, $counts)) {
			if ($row->status != 1) {
				$counts[$row->status]['count'] = (int) $row->num;
			}
		}
		$counts['all']['count'] += (int) $row->num;
	}
	if (isset($counts[4])) {
		$counts['all']['count'] -= $counts[4]['count'];
	}
	return $counts;
}

/**
 * Update leave request status
 *
 * Statuses and their ids
 * delete -  3
 * reject -  3
 * approve - 1
 * pending - 2
 *
 * @since 0.1
 *
 * @param  integer $request_id
 * @param  string $status
 *
 * @return object Eloquent Leave_request model
 */
function wphr_hr_leave_request_update_status($request_id, $status) {
	$request = \WPHR\HR_MANAGER\HRM\Models\Leave_request::find($request_id);
	if (empty($request)) {
		return new WP_Error('no-request-found', __('Invalid leave request', 'wphr'));
	}
	$status = absint($status);
	$request->status = $status;
	$request->updated_by = get_current_user_id();
	$request->save();
	// notification email

	if (1 === $status) {
		$approved_email = wphr()->emailer->get_email('Approved_Leave_Request');
		if (is_a($approved_email, '\\WPHR\\HR_MANAGER\\Email')) {
			$approved_email->trigger($request_id);
		}
	} else {

		if (3 === $status) {
			$rejected_email = wphr()->emailer->get_email('Rejected_Leave_Request');
			if (is_a($rejected_email, '\\WPHR\\HR_MANAGER\\Email')) {
				$rejected_email->trigger($request_id);
			}
		}

	}

	$status = ($status == 1 ? 'approved' : 'pending');
	do_action("wphr_hr_leave_request_{$status}", $request_id, $request);
	return $request;
}

/**
 * Archive/Unarchive approved leave request
 *
 * @since 0.1
 *
 * @param  integer $request_id
 *
 * @return object Eloquent Leave_request model
 */
function wphr_hr_archive_unarchive_approved_leaves($request_id, $unarchive = 1) {
	$request = \WPHR\HR_MANAGER\HRM\Models\Leave_request::find($request_id);
	if (empty($request)) {
		return new WP_Error('no-request-found', __('Invalid leave request', 'wphr'));
	}

	if ($request->status == 1) {
		$request->is_archived = $unarchive;
		$request->updated_by = get_current_user_id();
		$request->save();
	}

	return $request;
}

/**
 * Get leave requests status
 *
 * @since 0.1
 *
 * @param  int|boolean  $status
 *
 * @return array|string
 */
function wphr_hr_leave_request_get_statuses($status = false) {
	$statuses = array(
		'all' => __('All', 'wphr'),
		'1' => __('Approved', 'wphr'),
		'2' => __('Pending', 'wphr'),
		'3' => __('Rejected', 'wphr'),
	);
	if (false !== $status && array_key_exists($status, $statuses)) {
		return $statuses[$status];
	}
	return $statuses;
}

/**
 * Entitlement checking
 *
 * Check if an employee has already entitled to a policy in
 * a certain calendar year
 *
 * @since 0.1
 *
 * @param  integer  $employee_id
 * @param  integer  $policy_id
 * @param  integer  $year
 *
 * @return bool
 */
function wphr_hr_leave_has_employee_entitlement($employee_id, $policy_id, $year) {
	global $wpdb;
	$from_date = $year . '-01-01';
	$to_date = $year . '-12-31';
	$query = "SELECT id FROM {$wpdb->prefix}wphr_hr_leave_entitlements\r\n        WHERE user_id = %d AND policy_id = %d AND from_date = %s AND to_date = %s";
	$result = $wpdb->get_var($wpdb->prepare(
		$query,
		$employee_id,
		$policy_id,
		$from_date,
		$to_date
	));
	return $result;
}

/**
 * Get all the leave entitlements of a calendar year
 *
 * @since 0.1
 * @since 1.2.0 Depricate `year` arg and using `from_date` and `to_date` instead
 *
 * @param  integer  $year
 *
 * @return array
 */
function wphr_hr_leave_get_entitlements($args = array()) {
	global $wpdb;
	$financial_year_dates = wphr_get_financial_year_dates();
	if (isset($args['employee_id'])) {
		$financial_year_dates = wphr_get_financial_year_dates_by_user($args['employee_id']);
	}
	$defaults = array(
		'employee_id' => 0,
		'policy_id' => 0,
		'from_date' => $financial_year_dates['start'],
		'to_date' => $financial_year_dates['end'],
		'number' => 20,
		'offset' => 0,
		'orderby' => 'en.user_id, en.created_on',
		'order' => 'DESC',
		'debug' => false,
	);
	$args = wp_parse_args($args, $defaults);
	$where = 'WHERE 1 = 1';
	/**
	 * @deprecated 1.2.0 Use $args['from_date'] and $args['to_date'] instead
	 */

	if (!empty($args['year'])) {
		$from_date = date($args['year'] . '-m-d H:i:s', strtotime($financial_year_dates['start']));
		$to_date = date($args['year'] . '-m-d H:i:s', strtotime($financial_year_dates['end']));
		$where .= " AND en.from_date >= date('{$from_date}') AND en.to_date <= date('{$to_date}')";
	}

	if (!empty($args['from_date']) && !empty($args['to_date'])) {
		$where .= ' AND ( YEAR(en.from_date) = "' . $args['from_date'] . '" OR  YEAR(en.to_date) = "' . $args['to_date'] . '" )';
	} else {
		if (!empty($args['from_date'])) {
			$where .= ' AND YEAR(en.from_date) = "' . $args['from_date'] . '"';
		}
		if (!empty($args['to_date'])) {
			$where .= ' AND YEAR(en.to_date) = "' . $args['to_date'] . '"';
		}
	}

	if ($args['employee_id']) {
		$where .= " AND en.user_id = " . intval($args['employee_id']);
	}
	if ($args['policy_id']) {
		$where .= " AND en.policy_id = " . intval($args['policy_id']);
	}
	$query = "SELECT en.*, u.display_name as employee_name, pol.name as policy_name1 FROM {$wpdb->prefix}wphr_hr_leave_entitlements AS en LEFT JOIN {$wpdb->prefix}wphr_hr_leave_policies AS pol ON pol.id = en.policy_id LEFT JOIN {$wpdb->users} AS u ON en.user_id = u.ID {$where} ORDER BY {$args['orderby']} {$args['order']} LIMIT %d,%d";
	$sql = $wpdb->prepare($query, absint($args['offset']), absint($args['number']));
	$results = $wpdb->get_results($sql);
	return $results;
}

/**
 * Count leave entitlement
 *
 * @since 0.1
 *
 * @param  array  $args
 *
 * @return integer
 */
function wphr_hr_leave_count_entitlements($args = array()) {
	$financial_year_dates = wphr_get_financial_year_dates();
	$defaults = [
		'from_date' => $financial_year_dates['start'],
		'to_date' => $financial_year_dates['end'],
	];
	$args = wp_parse_args($args, $defaults);
	return \WPHR\HR_MANAGER\HRM\Models\Leave_Entitlement::whereYear('from_date', '=', date('Y', strtotime($args['from_date'])))->whereYear(
		'to_date',
		'=',
		date('Y', strtotime($args['to_date'])),
		'OR'
	)->count();
}

/**
 * Delete entitlement with leave request
 *
 * @since 0.1
 *
 * @param  integer $id
 * @param  integer $user_id
 * @param  integer $policy_id
 *
 * @return void
 */
function wphr_hr_delete_entitlement($id, $user_id, $policy_id) {
	global $wpdb;
	$financial_year = wphr_get_financial_year_dates_by_user($user_id);

	if (is_array($financial_year)) {
		$leave_recored = \WPHR\HR_MANAGER\HRM\Models\Leave_request::where('user_id', '=', $user_id)->where('policy_id', '=', $policy_id)->where('start_date', '>=', $financial_year['start'])->where('end_date', '<=', $financial_year['end'])->get()->toArray();
		$leave_recored = wp_list_pluck($leave_recored, 'status');
		if (in_array('1', $leave_recored)) {
			return;
		}
	}

	if (\WPHR\HR_MANAGER\HRM\Models\Leave_Entitlement::find($id)->delete()) {
		return \WPHR\HR_MANAGER\HRM\Models\Leave_request::where('user_id', '=', $user_id)->where('policy_id', '=', $policy_id)->delete();
	}
}

/**
 * wphr get leave balance details like days, hours and minutes
 *
 *
 * @param integer $total_minutes
 * @param integer $$working_hours
 *
 * @return array of days, hours, minutes and generated string
 */
function wphr_get_balance_details_from_minutes($total_minutes, $working_hours) {
	$data = array();
	$data['balance_string'] = '-';
	if (!$working_hours) {
		return $data;
	}
	$available = $total_minutes / ($working_hours * 60);

	if (is_float($available)) {
		$available = explode('.', $available);
		$days = $data['days'] = $available = $available[0];
	} else {
		$days = $data['days'] = $available;
	}

	$remaining_minutes = $available * ($working_hours * 60);
	$minutes_after_days = $total_minutes - $remaining_minutes;
	$available_hours = $minutes_after_days / 60;

	if (is_float($available_hours)) {
		$available_hours = explode('.', $available_hours);
		$hours = $data['hours'] = $available_hours = $available_hours[0];
	} else {
		$hours = $data['hours'] = $available_hours;
	}

	$remaining_minutes = $available_hours * 60;
	$remaining_minutes = $minutes_after_days - $remaining_minutes;
	$minutes = $data['minutes'] = ($remaining_minutes < 0 ? 0 : $remaining_minutes);
	$seperator = $balance_string = '';

	if ($days > 0) {
		$balance_string .= sprintf('%s days', number_format_i18n($days));
		$seperator = ', ';
	}

	if ($hours > 0) {
		$balance_string .= $seperator . sprintf('%s hours', number_format_i18n($hours));
		$seperator = ', ';
	}

	if ($minutes > 0) {
		$balance_string .= $seperator . sprintf('%s minutes', number_format_i18n($minutes));
		$seperator = ', ';
	}

	if (!$balance_string && $available < 0) {
		$balance_string .= sprintf('%s days', 0);
	}
	if ($balance_string) {
		$data['balance_string'] = $balance_string;
	}
	return $data;
}

/**
 * wphr get leave balance
 *
 * @since 0.1
 * @since 1.1.18 Add start_date in where clause
 * @since 1.2.1  Fix main query statement
 * @since 1.2  Add new $leave_date for future and last leave request
 *
 * @param  integer $user_id
 * @param  date $leave_date
 *
 * @return float|boolean
 */
function wphr_hr_leave_get_balance($user_id, $leave_date = false) {
	global $wpdb;
	/**
	 * Get finacial year base on user
	 */
	$finacial_year = wphr_get_financial_year_dates_by_user($user_id, $leave_date);
	if (!is_array($finacial_year)) {
		$finacial_year = wphr_get_financial_year_dates();
	}
	list($from_date, $to_date) = array_values($finacial_year);
	$query = "select en.policy_id, en.days";
	$query .= " from {$wpdb->prefix}wphr_hr_leave_entitlements AS en";
	$query .= " LEFT JOIN {$wpdb->prefix}wphr_hr_leave_policies AS pol ON pol.id = en.policy_id";
	$query .= " LEFT JOIN {$wpdb->users} AS u ON en.user_id = u.ID";
	$query .= " where en.user_id = %d";
	$query .= " AND en.from_date >= '{$from_date}' AND en.to_date <= '{$to_date}'";
	$query .= " GROUP BY en.policy_id ORDER BY en.id DESC";
	$results = $wpdb->get_results($wpdb->prepare($query, $user_id));
	$working_hours = get_employee_working_hours($user_id);
	$working_hours = ($working_hours ? $working_hours : 9);
	$balance = [];
	if (!empty($results)) {
		foreach ($results as $result) {
			$balance[$result->policy_id] = array(
				'policy_id' => $result->policy_id,
				'scheduled' => 0,
				'entitlement' => $result->days,
				'total' => 0,
				'available' => $result->days,
			);

			if ($working_hours) {
				$balance[$result->policy_id]['entitlement_hours'] = $result->days * $working_hours;
				$balance[$result->policy_id]['total_hours'] = $result->days * $working_hours;
				$balance[$result->policy_id]['working_hours'] = $working_hours;
			}

		}
	}
	$financial_start_date = $from_date;
	//wphr_financial_start_date();
	$financial_end_date = $to_date;
	//wphr_financial_end_date();
	$query = "SELECT req.id, req.days, req.policy_id, req.start_date, req.end_date, en.days as entitlement";
	$query .= " FROM {$wpdb->prefix}wphr_hr_leave_requests AS req";
	$query .= " LEFT JOIN {$wpdb->prefix}wphr_hr_leave_entitlements as en on (req.user_id = en.user_id and req.policy_id = en.policy_id and en.from_date >= %s )";
	$query .= " WHERE req.status = 1 and req.user_id = %d AND ( req.start_date >= %s AND req.end_date <= %s )";
	$sql = $wpdb->prepare($query, $financial_start_date, $user_id, $financial_start_date, $financial_end_date);
	$results = $wpdb->get_results($sql);
	$temp = [];
	$current_time = current_time('timestamp');

	if ($results) {
		// group by policy
		foreach ($results as $request) {
			$temp[$request->policy_id][$request->id] = $request;
		}
		// calculate each policy
		foreach ($temp as $policy_id => $requests) {
			$balance[$policy_id] = array(
				'policy_id' => $policy_id,
				'scheduled' => 0,
				'entitlement' => 0,
				'total' => 0,
				'available' => 0,
				'scheduled_hours' => 0,
				'entitlement_hours' => 0,
				'total_hours' => 0,
				'total_minutes' => 0,
				'total_approved_minutes' => 0,
				'scheduled_minutes' => 0,
				'available_hours' => 0,
			);
			foreach ($requests as $request) {
				$balance[$policy_id]['entitlement'] = (int) $request->entitlement;
				$balance[$policy_id]['total_minutes'] = $balance[$policy_id]['entitlement'] * $working_hours * 60;
				$balance[$policy_id]['total'] += $request->days;
				$balance[$policy_id]['entitlement_hours'] = (int) $request->entitlement * $working_hours;
				$start_time = strtotime($request->start_date);
				$end_time = strtotime($request->end_date);
				$time_difference = $end_time - $start_time;
				$hours = date('G', $time_difference);
				$minutes = date('i', $time_difference);
				$start_date = date('Y-m-d', $start_time);
				$end_date = date('Y-m-d', $end_time);
				$days = date_diff(date_create($start_date), date_create($end_date));
				$leave_days = $days->format("%a");
				//$hours = ( $end_time - $start_time )/3600;

				if ($hours <= $working_hours) {
					$balance[$policy_id]['total_hours'] += $hours;
					$balance[$policy_id]['total_approved_minutes'] += $hours * 60;
					$balance[$policy_id]['total_approved_minutes'] += $minutes;
				} else {
					$balance[$policy_id]['total_hours'] += $request->days * $working_hours;
					$balance[$policy_id]['total_approved_minutes'] += $request->days * 60 * $working_hours;
				}

				if ($current_time < strtotime($request->start_date)) {
					$balance[$policy_id]['scheduled'] += $request->days;

					if ($hours <= $working_hours) {
						$balance[$policy_id]['scheduled_hours'] += $hours;
					} else {
						$balance[$policy_id]['scheduled_hours'] += $request->days * $working_hours;
					}

					if ($minutes > 0) {
						$balance[$policy_id]['scheduled_minutes'] += $minutes;
					}
				}

			}
		}
	}

	// calculate available
	foreach ($balance as &$policy) {
		$available = $policy['entitlement'] - $policy['total'];

		if (isset($policy['total_hours'])) {
			$available_hours = $policy['entitlement_hours'] - $policy['total_hours'];
		} else {
			$available_hours = $available * $working_hours;
		}

		$policy['available'] = ($available < 0 ? 0 : $available);
		$policy['available_hours'] = ($available_hours < 0 ? 0 : $available_hours);
		$policy['working_hours'] = $working_hours;
		if (!isset($policy['scheduled_hours'])) {
			$policy['scheduled_hours'] = 0;
		}

		if (!isset($policy['total_minutes'])) {
			$policy['total_minutes'] = $policy['entitlement'] * $policy['working_hours'] * 60;
			$policy['available_hours'] = $policy['entitlement'] * $policy['working_hours'];
		}

		if (!isset($policy['total_approved_minutes'])) {
			$policy['total_approved_minutes'] = 0;
		}
		if (!isset($policy['scheduled_minutes'])) {
			$policy['scheduled_minutes'] = 0;
		}
	}
	return $balance;
}

/**
 * Get cuurent month approve leave request list
 *
 * @since 0.1
 * @since 1.2.0 Ignore terminated employees
 * @since 1.2.2 Exclude past requests
 *              Sort results by start_date
 *
 * @return array
 */
function wphr_hr_get_current_month_leave_list() {
	$db = new \WPHR\ORM\Eloquent\Database();
	$prefix = $db->db->prefix;
	return $db->table('wphr_hr_leave_requests as req')->select('req.user_id', 'req.start_date', 'req.end_date')->leftJoin(
		"{$prefix}wphr_hr_employees as em",
		'req.user_id',
		'=',
		'em.user_id'
	)->where('em.status', '!=', 'terminated')->where('req.start_date', '>=', current_time('mysql'))->where('req.start_date', '<=', date('Y-m-d 23:59:59', strtotime('last day of this month')))->where('req.status', 1)->orderBy('req.start_date', 'asc')->get();
}

/**
 * Get next month leave request approved list
 *
 * @since 0.1
 * @since 1.2.0 Ignore terminated employees
 * @since 1.2.2 Sort results by start_date
 *
 * @return array
 */
function wphr_hr_get_next_month_leave_list() {
	$db = new \WPHR\ORM\Eloquent\Database();
	$prefix = $db->db->prefix;
	return $db->table('wphr_hr_leave_requests as req')->select('req.user_id', 'req.start_date', 'req.end_date')->leftJoin(
		"{$prefix}wphr_hr_employees as em",
		'req.user_id',
		'=',
		'em.user_id'
	)->where('em.status', '!=', 'terminated')->where('req.start_date', '>=', date('Y-m-d 00:00:00', strtotime('first day of next month')))->where('req.start_date', '<=', date('Y-m-d 23:59:59', strtotime('last day of next month')))->where('req.status', 1)->orderBy('req.start_date', 'asc')->get();
}

/**
 * Leave period dropdown at entitlement create time
 *
 * @since 0.1
 *
 * @return void
 */
function wphr_hr_leave_period() {
	$next_sart_date = date('Y-m-01 H:i:s', strtotime('+1 year', strtotime(wphr_financial_start_date())));
	$next_end_date = date('Y-m-t H:i:s', strtotime('+1 year', strtotime(wphr_financial_end_date())));
	$date = [
		wphr_financial_start_date() => wphr_format_date(wphr_financial_start_date()) . ' - ' . wphr_format_date(wphr_financial_end_date()),
		$next_sart_date => wphr_format_date($next_sart_date) . ' - ' . wphr_format_date($next_end_date),
	];
	return $date;
}

/**
 * Apply entitlement yearly
 *
 * @since 0.1
 *
 * @return void
 */
function wphr_hr_apply_entitlement_yearly() {
	$financial_start_date = wphr_financial_start_date();
	$financial_end_date = wphr_financial_end_date();
	$before_financial_start_date = date('Y-m-01 H:i:s', strtotime('-1 year', strtotime($financial_start_date)));
	$before_financial_end_date = date('Y-m-t H:i:s', strtotime('+11 month', strtotime($before_financial_start_date)));
	$entitlement = new \WPHR\HR_MANAGER\HRM\Models\Leave_Entitlement();
	$entitlement = $entitlement->where(function ($condition) use ($before_financial_start_date, $before_financial_end_date) {
		$condition->where('from_date', '>=', $before_financial_start_date);
		$condition->where('to_date', '<=', $before_financial_end_date);
	});
	$entitlements = $entitlement->get()->toArray();
	foreach ($entitlements as $key => $entitlement) {
		$policy = array(
			'user_id' => $entitlement['user_id'],
			'policy_id' => $entitlement['policy_id'],
			'days' => $entitlement['days'],
			'from_date' => wphr_financial_start_date(),
			'to_date' => wphr_financial_end_date(),
			'comments' => $entitlement['comments'],
		);
		wphr_hr_leave_insert_entitlement($policy);
	}
}

/**
 * Get calendar leave events
 *
 * @param   array|boolean $get filter args
 * @param   int|boolean $user_id Get leaves for given user only
 * @param   boolean $approved_only Get leaves which are approved
 *
 * @since 0.1
 *
 * @return array
 */
function wphr_hr_get_calendar_leave_events($get = false, $user_id = false, $approved_only = false) {
	global $wpdb;
	$employee_tb = $wpdb->prefix . 'wphr_hr_employees';
	$users_tb = $wpdb->users;
	$request_tb = $wpdb->prefix . 'wphr_hr_leave_requests';
	$policy_tb = $wpdb->prefix . 'wphr_hr_leave_policies';
	$employee = new \WPHR\HR_MANAGER\HRM\Models\Employee();
	$leave_request = new \WPHR\HR_MANAGER\HRM\Models\Leave_request();
	$department = (isset($get['department']) && !empty($get['department']) && $get['department'] != '-1' ? intval($get['department']) : false);
	$designation = (isset($get['designation']) && !empty($get['designation']) && $get['designation'] != '-1' ? intval($get['designation']) : false);
	$leave_request = $leave_request->where($request_tb . '.status', '!=', 3);

	if (!$get) {
		$request = $leave_request->leftJoin(
			$users_tb,
			$request_tb . '.user_id',
			'=',
			$users_tb . '.ID'
		)->leftJoin(
			$policy_tb,
			$request_tb . '.policy_id',
			'=',
			$policy_tb . '.id'
		)->select($users_tb . '.display_name', $request_tb . '.*', $policy_tb . '.color');
		if ($user_id) {
			$request = $request->where($request_tb . '.user_id', $user_id);
		}
		if ($approved_only) {
			$request = $request->where($request_tb . '.status', 1);
		}
		return wphr_array_to_object($request->get()->toArray());
	}

	if ($department && $designation) {
		$leave_requests = $leave_request->leftJoin(
			$employee_tb,
			$request_tb . '.user_id',
			'=',
			$employee_tb . '.user_id'
		)->leftJoin(
			$users_tb,
			$request_tb . '.user_id',
			'=',
			$users_tb . '.ID'
		)->leftJoin(
			$policy_tb,
			$request_tb . '.policy_id',
			'=',
			$policy_tb . '.id'
		)->select($users_tb . '.display_name', $request_tb . '.*', $policy_tb . '.color')->where($employee_tb . '.designation', '=', $designation)->where($employee_tb . '.department', '=', $department);
		if ($approved_only) {
			$leave_requests = $leave_requests->where($request_tb . '.status', 1);
		}
		$leave_requests = wphr_array_to_object($leave_requests->get()->toArray());
	} else {

		if ($designation) {
			$leave_requests = $leave_request->leftJoin(
				$employee_tb,
				$request_tb . '.user_id',
				'=',
				$employee_tb . '.user_id'
			)->leftJoin(
				$users_tb,
				$request_tb . '.user_id',
				'=',
				$users_tb . '.ID'
			)->leftJoin(
				$policy_tb,
				$request_tb . '.policy_id',
				'=',
				$policy_tb . '.id'
			)->select($users_tb . '.display_name', $request_tb . '.*', $policy_tb . '.color')->where($employee_tb . '.designation', '=', $designation);
			if ($approved_only) {
				$leave_requests = $leave_requests->where($request_tb . '.status', 1);
			}
			$leave_requests = wphr_array_to_object($leave_requests->get()->toArray());
		} else {

			if ($department) {
				$leave_requests = $leave_request->leftJoin(
					$employee_tb,
					$request_tb . '.user_id',
					'=',
					$employee_tb . '.user_id'
				)->leftJoin(
					$users_tb,
					$request_tb . '.user_id',
					'=',
					$users_tb . '.ID'
				)->leftJoin(
					$policy_tb,
					$request_tb . '.policy_id',
					'=',
					$policy_tb . '.id'
				)->select($users_tb . '.display_name', $request_tb . '.*', $policy_tb . '.color')->where($employee_tb . '.department', '=', $department);
				if ($approved_only) {
					$leave_requests = $leave_requests->where($request_tb . '.status', 1);
				}
				$leave_requests = wphr_array_to_object($leave_requests->get()->toArray());
			}

		}

	}

	return $leave_requests;
}

/**
 * Get year ranges based on available financial years
 *
 * @since 1.2.0
 *
 * @return array
 */
function get_entitlement_financial_years() {
	$db = \WPHR\ORM\Eloquent\Facades\DB::instance();
	$prefix = $db->db->prefix;
	$min_max_dates = $db->table('wphr_hr_leave_entitlements')->select($db->raw('min( `from_date` ) as min'), $db->raw('max( `to_date` ) max'))->first();
	$start_year = $end_year = current_time('Y');

	if (!empty($min_max_dates->min)) {
		$min_date_fy_year = get_financial_year_from_date($min_max_dates->min);
		$start_year = $min_date_fy_year['start'];
		$end_year = date('Y', strtotime($min_max_dates->max));
	} else {
		return [];
	}

	$start_month = wphr_get_option('gen_financial_month', 'wphr_settings_general', 1);
	$years = [];
	for ($i = $start_year; $i <= $end_year; $i++) {

		if (1 === absint($start_month)) {
			$years[] = $i;
		} else {

			if (!($i + 1 > $end_year)) {
				$years[] = $i . '-' . ($i + 1);
			} else {
				if ($start_year === $end_year) {
					$years[] = $i - 1 . '-' . $i;
				}
			}

		}

	}
	return $years;
}

/**
 * Add leave entitlement for past and future
 *
 * @since 1.2
 *
 * @param  int    $user_id
 * @param  int 	  $policy_id
 * @param  date	  $leave_start_date
 * @param  date	  $$leave_end_date
 *
 * @return void
 */
function wphr_hr_add_custom_leave_entitlements(
	$user_id,
	$policy_id,
	$leave_start_date,
	$leave_end_date
) {
	global $wpdb;
	$policy = $wpdb->get_row($wpdb->prepare("SELECT * FROM `{$wpdb->prefix}wphr_hr_leave_policies` WHERE `id` = %d", $policy_id));
	$finacial_year = wphr_get_financial_year_dates_by_user($user_id, $leave_start_date);
	if (!is_array($finacial_year)) {
		$finacial_year = wphr_get_financial_year_dates();
	}
	list($from_date, $to_date) = array_values($finacial_year);
	$policy_arg = [
		'user_id' => $user_id,
		'policy_id' => $policy->id,
		'days' => $policy->value,
		'from_date' => $from_date,
		'to_date' => $to_date,
		'comments' => $policy->description,
	];
	$entitlement_id = wphr_hr_leave_insert_entitlement($policy_arg);

	if ($to_date > $leave_end_date) {
		$finacial_year = wphr_get_financial_year_dates_by_user($user_id, $leave_end_date);
		if (!is_array($finacial_year)) {
			$finacial_year = wphr_get_financial_year_dates();
		}
		list($from_date, $to_date) = array_values($finacial_year);
		$policy = [
			'user_id' => $user_id,
			'policy_id' => $policy->id,
			'days' => $policy->value,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'comments' => $policy->description,
		];
		$entitlement_id = wphr_hr_leave_insert_entitlement($policy);
	}

	return $entitlement_id;
}

/**
 * Update leave entitlement for past and future
 *
 * @since 1.7
 *
 * @param  int    $user_id
 *
 * @return void
 */
function wphr_hr_update_leave_entities($user_id = 0) {
	global $wpdb;
	$db = \WPHR\ORM\Eloquent\Facades\DB::instance();
	$prefix = $db->db->prefix;
	$result = [];

	if ($user_id) {
		$result = $db->table('wphr_hr_leave_entitlements')->select('*')->where('user_id', $user_id)->get();
	} else {
		$result = $db->table('wphr_hr_leave_entitlements')->select('*')->get();
	}

	if ($result) {
		$user_entitlement = [];
		foreach ($result as $row) {
			$entitlement_id = wphr_hr_add_custom_leave_entitlements(
				$row->user_id,
				$row->policy_id,
				$row->from_date,
				$row->to_date
			);

			if ($entitlement_id && $entitlement_id != $row->id) {
				$db->table('wphr_hr_leave_entitlements')->where('id', $row->id)->delete();

				if (!isset($user_entitlement[$row->user_id])) {
					$user_entitlement[$row->user_id] = [
						'min' => $row->from_date,
						'max' => $row->to_date,
					];
				} else {
					if ($user_entitlement[$row->user_id]['min'] < $row->from_date) {
						$user_entitlement[$row->user_id]['min'] = $row->from_date;
					}
					if ($user_entitlement[$row->user_id]['max'] < $row->to_date) {
						$user_entitlement[$row->user_id]['max'] = $row->to_date;
					}
				}

			}

		}
		if (count($user_entitlement)) {
			foreach ($user_entitlement as $user_id => $value) {
				$result = $db->table('wphr_hr_leave_requests')->select('*')->where('user_id', $user_id)->where(function ($query) use ($value) {
					$query->where('start_date', '<', $value['min'])->orWhere('end_date', '>', $value['max']);
				})->get();
				if (count($result)) {
					foreach ($result as $row) {
						wphr_hr_add_custom_leave_entitlements(
							$row->user_id,
							$row->policy_id,
							$row->start_date,
							$row->end_date
						);
					}
				}
			}
		}
	}

}

/**
 * Convert leave date format to standard sql format
 *
 * @param $date date
 * @return date
 * @since 1.7
 */
function convert_to_data_format($date) {
	$format = wphr_get_option('date_format', 'wphr_settings_general', 'd-m-Y');
	switch ($format) {
	case 'm-d-Y':
		list($m, $d, $y) = explode('-', $date);
		break;
	case 'd-m-Y':
		list($d, $m, $y) = explode('-', $date);
		break;
	case 'm/d/Y':
		list($m, $d, $y) = explode('/', $date);
		break;
	case 'd/m/Y':
		list($d, $m, $y) = explode('/', $date);
		break;
	case 'Y-m-d':
		list($y, $m, $d) = explode('-', $date);
		break;
	}
	return "{$y}-{$m}-{$d}";
}
