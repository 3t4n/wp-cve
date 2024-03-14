<?php

namespace WPHR\HR_MANAGER\HRM;
use WPHR\HR_MANAGER\HRM\Employee;

/**
 * List table class
 */
class Leave_Requests_List_Table extends \WP_List_Table {
	private $counts = array();
	private $page_status;
	function __construct() {
		global $status, $page;
		parent::__construct(array(
			'singular' => 'leave',
			'plural' => 'leaves',
			'ajax' => false,
		));
		$this->table_css();
	}

	/**
	 * Message to show if no requests found
	 *
	 * @return void
	 */
	function no_items() {
		_e('No requests found.', 'wphr');
	}

	/**
	 * Default column values if no callback found
	 *
	 * @param  object  $item
	 * @param  string  $column_name
	 *
	 * @return string
	 */
	function column_default($item, $column_name) {

		switch ($column_name) {
		case 'policy':
			return stripslashes($item->policy_name);
		case 'department':
			/*return Employee::get_wphr_department_name($item->user_id);*/
			$employeeObj = new Employee();
			return $employeeObj->get_wphr_department_name($item->user_id);

		case 'from_date':
			$from_date = wphr_format_date($item->start_date);
			$to_date = wphr_format_date($item->end_date);

			if ($from_date == $to_date) {
				$start_time = strtotime($item->start_date);
				$end_time = strtotime($item->end_date);
				$hours = ($end_time - $start_time) / 3600;
				if ($hours < 23) {
					$from_date .= date(' H:i:s', $start_time);
				}
			}

			return $from_date;
		case 'to_date':
			$from_date = wphr_format_date($item->start_date);
			$to_date = wphr_format_date($item->end_date);

			if ($from_date == $to_date) {
				$start_time = strtotime($item->start_date);
				$end_time = strtotime($item->end_date);
				$hours = ($end_time - $start_time) / 3600;
				if ($hours < 23) {
					$to_date .= date(' H:i:s', $end_time);
				}
			}

			return $to_date;
		case 'status':
			return '<span class="status-' . $item->status . '">' . wphr_hr_leave_request_get_statuses($item->status) . '</span>';
		case 'available':
			$balance = wphr_hr_leave_get_balance($item->user_id, $item->start_date);
			$policy = wphr_hr_leave_get_policy($item->policy_id);
			$days = 0;
			$hours = 0;
			$minutes = 0;
			$available = false;

			if (isset($balance[$item->policy_id]) && isset($balance[$item->policy_id]['entitlement_hours']) && isset($balance[$item->policy_id]['total_minutes']) && isset($balance[$item->policy_id]['working_hours'])) {
				$total_minutes = $balance[$item->policy_id]['total_minutes'];
				$total_approved_minutes = $balance[$item->policy_id]['total_approved_minutes'];
				$available_arr = wphr_get_balance_details_from_minutes($total_minutes - $total_approved_minutes, $balance[$item->policy_id]['working_hours']);

				if (!empty($available_arr)) {
					$available = $days = $available_arr['days'];
					$hours = $available_arr['hours'];
					$minutes = $available_arr['minutes'];
				} else {
					$available = $days = 0;
					$hours = 0;
					$minutes = 0;
				}

			}

			if ($days > 0 && $hours > 0 && $minutes > 0) {
				return sprintf(
					'<span class="green">%d %s, %d %s, %d %s</span>',
					number_format_i18n($days),
					__('days', 'wphr'),
					number_format_i18n($hours),
					__('hours', 'wphr'),
					number_format_i18n($minutes),
					__('minutes', 'wphr')
				);
			} elseif ($days > 0 && $hours > 0) {
				return sprintf(
					'<span class="green">%d %s, %d %s</span>',
					number_format_i18n($days),
					__('days', 'wphr'),
					number_format_i18n($hours),
					__('hours', 'wphr')
				);
			} elseif ($hours > 0 && $minutes > 0) {
				return sprintf(
					'<span class="green">%d %s, %d %s</span>',
					number_format_i18n($hours),
					__('hours', 'wphr'),
					number_format_i18n($minutes),
					__('minutes', 'wphr')
				);
			} elseif ($hours > 0) {
				return sprintf('<span class="green">%d %s</span>', number_format_i18n($hours), __('hours', 'wphr'));
			} elseif ($minutes > 0) {
				return sprintf('<span class="green">%d %s</span>', number_format_i18n($minutes), __('minutes', 'wphr'));
			} elseif ($available < 0) {
				return sprintf('<span class="red">%d %s</span>', number_format_i18n($available), __('days', 'wphr'));
			} elseif ($available > 0) {
				return sprintf('<span class="green">%d %s</span>', number_format_i18n($available), __('days', 'wphr'));
			} else {

				if ($available === 0) {
					return sprintf('<span class="gray">%d %s</span>', 0, __('days', 'wphr'));
				} else {
					return sprintf('<span class="green">%d %s</span>', number_format_i18n($policy->value), __('days', 'wphr'));
				}

			}

		case 'reason':
			return stripslashes($item->reason);
		case 'comment':
			return stripslashes($item->comments);
		case 'days':
			$days = $item->days;

			if (wphr_format_date($item->start_date) == wphr_format_date($item->end_date)) {
				$start_time = strtotime($item->start_date);
				$end_time = strtotime($item->end_date);
				$time_difference = $end_time - $start_time;
				$hours = date('G', $time_difference);
				$minutes = date('i', $time_difference);
				//$hours = ( $end_time - $start_time );
				$display_time = '';

				if ($hours < 23) {
					if ($hours > 0) {
						$display_time .= ($hours > 1 ? sprintf(__('%d Hours', 'hrm'), $hours) : sprintf(__('%d Hour', 'hrm'), $hours));
					}
					if ($minutes > 0) {
						$display_time .= ' ' . sprintf(__('%d Minutes', 'hrm'), $minutes);
					}
					$days = $display_time;
				} else {
					$days = ($days > 1 ? sprintf(__('%d Days', 'hrm'), $days) : sprintf(__('%d Day', 'hrm'), $days));
				}

				return $days;
			}

			return ($days > 1 ? sprintf(__('%d Days', 'hrm'), $days) : sprintf(__('%d Day', 'hrm'), $days));
		default:
			return (isset($item->{$column_name}) ? $item->{$column_name} : '');
		}
	}

	/**
	 * Get sortable columns
	 *
	 * @return array
	 */
	function get_sortable_columns() {
		$sortable_columns = array(
			'days' => array('days', false),
		);
		return $sortable_columns;
	}

	/**
	 * Get the column names
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'name' => __('Employee Name', 'wphr'),
			'policy' => __('Leave Policy', 'wphr'),
			'department' => __('Department', 'wphr'),
			'from_date' => __('From Date', 'wphr'),
			'to_date' => __('To Date', 'wphr'),
			'days' => __('Days', 'wphr'),
			'available' => __('Leave Entitlement', 'wphr'),
			'status' => __('Status', 'wphr'),
			'reason' => __('Leave Reason', 'wphr'),
		);
		if (isset($_GET['status']) && sanitize_text_field($_GET['status']) == 3) {
			$columns['comment'] = __('Reject Reason', 'wphr');
		}
		return $columns;
	}

	/**
	 * Render the employee name column
	 *
	 * @param  object  $item
	 *
	 * @return string
	 */
	function column_name($item) {
		$tpl = '?page=wphr-leave&leave_action=%s&id=%d';
		$nonce = 'wphr-hr-leave-req-nonce';
		$actions = array();
		$delete_url = wp_nonce_url(sprintf($tpl, 'delete', $item->id), $nonce);
		$reject_url = wp_nonce_url(sprintf($tpl, 'reject', $item->id), $nonce);
		$approve_url = wp_nonce_url(sprintf($tpl, 'approve', $item->id), $nonce);
		$pending_url = wp_nonce_url(sprintf($tpl, 'pending', $item->id), $nonce);
		if (wphr_get_option('wphr_debug_mode', 'wphr_settings_general', 0)) {
			$actions['delete'] = sprintf('<a href="%s">%s</a>', $delete_url, __('Delete', 'wphr'));
		}

		if ($item->status == '2') {
			$actions['reject'] = sprintf(
				'<a class="wphr-hr-leave-reject-btn" data-id="%s" href="%s">%s</a>',
				$item->id,
				$reject_url,
				__('Reject', 'wphr')
			);
			$actions['approved'] = sprintf('<a href="%s">%s</a>', $approve_url, __('Approve', 'wphr'));
			$actions['delete'] = sprintf('<a href="%s">%s</a>', $delete_url, __('Delete', 'wphr'));
		} elseif ($item->status == '1') {
			$actions['pending'] = sprintf('<a href="%s">%s</a>', $pending_url, __('Mark Pending', 'wphr'));
		} elseif ($item->status == '3') {
			$actions['approved'] = sprintf('<a href="%s">%s</a>', $approve_url, __('Approve', 'wphr'));
		}

		return sprintf(
			'<a href="%3$s"><strong>%1$s</strong></a> %2$s',
			$item->display_name,
			$this->row_actions($actions),
			wphr_hr_url_single_employee($item->user_id)
		);
	}

	/**
	 * Set the bulk actions
	 *
	 * @return array
	 */
	function get_bulk_actions() {
		if (wphr_get_option('wphr_debug_mode', 'wphr_settings_general', 0)) {
			$actions['delete'] = __('Delete', 'wphr');
		}

		if ($this->page_status == '2') {
			$actions['reject'] = __('Reject', 'wphr');
			$actions['approved'] = __('Approve', 'wphr');
			$actions['delete'] = __('Delete', 'wphr');
		} elseif ($this->page_status == '1') {
			$actions['pending'] = __('Mark Pending', 'wphr');
			$actions['reject'] = __('Reject', 'wphr');
		} elseif ($this->page_status == '3') {
			$actions['approved'] = __('Approve', 'wphr');
			$actions['pending'] = __('Mark Pending', 'wphr');
		} elseif ($this->page_status == '4') {
			$actions['pending'] = __('Mark Pending', 'wphr');
		} else {
			$actions['reject'] = __('Reject', 'wphr');
			$actions['approved'] = __('Approve', 'wphr');
			$actions['pending'] = __('Mark Pending', 'wphr');
		}

		return $actions;
	}

	/**
	 * Render the checkbox column
	 *
	 * @param  object  $item
	 *
	 * @return string
	 */
	function column_cb($item) {
		return sprintf('<input type="checkbox" name="request_id[]" value="%s" />', $item->id);
	}

	/**
	 * Set the views
	 *
	 * @return array
	 */
	public function get_views() {
		$status_links = array();
		$base_link = admin_url('admin.php?page=wphr-leave');
		foreach ($this->counts as $key => $value) {
			$class = ($key == $this->page_status ? 'current' : 'status-' . $key);
			$status_links[$key] = sprintf(
				'<a href="%s" class="%s">%s <span class="count">(%s)</span></a>',
				add_query_arg(array(
					'status' => $key,
				), $base_link),
				$class,
				$value['label'],
				$value['count']
			);
		}
		return $status_links;
	}

	/**
	 * Prepare the class items
	 *
	 * @return void
	 */
	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$this->items = array('Department' => 'computersicen');
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$per_page = 20;
		$current_page = $this->get_pagenum();
		$offset = ($current_page - 1) * $per_page;
		$this->page_status = (isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '2');
		$is_archived = 0;
		if ($this->page_status == 4) {
			$is_archived = 1;
		}
		// only necessary because we have sample data
		$args = array(
			'offset' => $offset,
			'number' => $per_page,
			'status' => ($this->page_status == 4 ? 1 : $this->page_status),
			'is_archived' => $is_archived,
			'year' => '',
			'orderby' => (isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'created_on'),
			'order' => (isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'DESC'),
		);
		$employee_details = wp_get_current_user();
		$employee_id = $employee_details->ID;
		if (!in_array(wphr_hr_get_manager_role(), $employee_details->roles) && in_array(wphr_hr_get_employee_role(), $employee_details->roles)) {
			if (get_users_under_line_manager($employee_id, true)) {
				$args['user_id_in'] = get_users_under_line_manager($employee_id);
			}
		}
		$this->counts = wphr_hr_leave_get_requests_count($args);
		$this->items = wphr_hr_get_leave_requests($args);
		$this->set_pagination_args(array(
			'total_items' => $this->counts[$this->page_status]['count'],
			'per_page' => $per_page,
		));
	}

}