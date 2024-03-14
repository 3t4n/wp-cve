<?php
namespace WPHR\HR_MANAGER\HRM;

use WPHR\HR_MANAGER\HRM\Models\Dependents;
use WPHR\HR_MANAGER\HRM\Models\Education;
use WPHR\HR_MANAGER\HRM\Models\Work_Experience;

/**
 * Employee Class
 */
class Employee {

	/**
	 * array for lazy loading data from wphr table
	 *
	 * @see __get function for this
	 * @var array
	 */
	private $wphr_rows = array(
		'employee_id',
		'designation',
		'designation_title',
		'job_title_detail',
		'department',
		'department_profile_label',
		'department_title',
		'location',
		'location_name',
		'hiring_source',
		'hiring_date',
		'date_of_birth',
		'reporting_to',
		'send_mail_to_reporter',
		'manage_leave_by_reporter',
		'pay_rate',
		'pay_type',
		'type',
		'status',
		'leave_year',
		'apply_leave_year',
	);

	/**
	 * [__construct description]
	 *
	 * @param int|WP_User|email user id or WP_User object or user email
	 */
	public function __construct($employee = null) {

		$this->id = 0;
		$this->user = new \stdClass();
		$this->wphr = new \stdClass();

		if (is_int($employee)) {

			$user = get_user_by('id', $employee);

			if ($user) {
				$this->id = $employee;
				$this->user = $user;
			}

		} elseif (is_a($employee, 'WP_User')) {

			$this->id = $employee->ID;
			$this->user = $employee;

		} elseif (is_email($employee)) {

			$user = get_user_by('email', $employee);

			if ($user) {
				$this->id = $employee;
				$this->user = $user;
			}

		}
	}

	/**
	 * Magic method to get item data values
	 *
	 * @param  string
	 *
	 * @return string
	 */
	public function __get($key) {

		// lazy loading
		// if we are requesting any data from wphr table,
		// only then query to get those row
		if (in_array($key, $this->wphr_rows)) {
			$this->wphr = $this->get_wphr_row();
		}

		if (isset($this->wphr->$key)) {
			return stripslashes($this->wphr->$key);
		}

		if (isset($this->user->$key)) {
			return stripslashes($this->user->$key);
		}
	}

	/**
	 * Get the user info as an array
	 *
	 * @return array
	 */
	public function to_array() {
		$fields = array(
			'id' => 0,
			'employee_id' => '',
			'name' => array(
				'first_name' => '',
				'middle_name' => '',
				'last_name' => '',
			),
			'avatar' => array(
				'id' => 0,
				'url' => '',
			),
			'user_email' => '',
			'work' => array(
				'designation' => 0,
				'department' => 0,
				'job_title_detail' => '',
				'location' => '',
				'hiring_source' => '',
				'hiring_date' => '',
				'date_of_birth' => '',
				'reporting_to' => 0,
				'send_mail_to_reporter' => '',
				'manage_leave_by_reporter' => '',
				'pay_rate' => '',
				'pay_type' => '',
				'type' => '',
				'status' => '',
				'leave_year' => 1,
				'apply_leave_year' => '',
				'anniversary_permission' => '',
				'work_permission' => '',
				'inout_office' => '',

			),
			'personal' => array(
				'other_email' => '',
				'phone' => '',
				'work_phone' => '',
				'mobile' => '',
				'address' => '',
				'gender' => '',
				'marital_status' => '',
				'nationality' => '',
				'driving_license' => '',
				'hobbies' => '',
				'user_url' => '',
				'description' => '',
				'street_1' => '',
				'street_2' => '',
				'city' => '',
				'country' => '',
				'state' => '',
				'postal_code' => '',
			),
		);

		if ($this->id) {
			$fields['id'] = $this->id;
			$fields['employee_id'] = $this->employee_id;
			$fields['user_email'] = $this->user->user_email;

			$fields['name'] = array(
				'first_name' => $this->first_name,
				'last_name' => $this->last_name,
				'middle_name' => $this->middle_name,
				'full_name' => $this->get_full_name(),
			);

			$avatar_id = (int) $this->user->photo_id;
			$fields['avatar']['id'] = $avatar_id;
			$fields['avatar']['image'] = $this->get_avatar();

			if ($avatar_id) {
				$fields['avatar']['url'] = $this->get_avatar_url($avatar_id);
			}

			foreach ($fields['work'] as $key => $value) {
				if ($key == 'date_of_birth' || $key == 'hiring_date') {
					$fields['work'][$key] = wphr_format_date($this->$key);
				} else {
					$fields['work'][$key] = $this->$key;
				}
			}

			foreach ($fields['personal'] as $key => $value) {
				$fields['personal'][$key] = $this->user->$key;
			}
		}

		return apply_filters('wphr_hr_get_employee_fields', $fields, $this->id, $this->user);
	}

	/**
	 * Get data from wphr employee table
	 *
	 * @param boolean $force if force from cache
	 *
	 * @return object the wpdb row object
	 */
	private function get_wphr_row($force = false) {
		global $wpdb;

		if ($this->id) {
			$cache_key = 'wphr-empl-' . $this->id;
			$row = wp_cache_get($cache_key, 'wphr', $force);

			if (false === $row) {
				$query = $wpdb->prepare("SELECT e.*, d.title as designation_title, dpt.title as department_title, dpt.employee_label as department_profile_label, loc.name as location_name
                    FROM {$wpdb->prefix}wphr_hr_employees AS e
                    LEFT JOIN {$wpdb->prefix}wphr_hr_designations AS d ON d.id = e.designation
                    LEFT JOIN {$wpdb->prefix}wphr_hr_depts AS dpt ON dpt.id = e.department
                    LEFT JOIN {$wpdb->prefix}wphr_company_locations AS loc ON loc.id = e.location
                    WHERE user_id = %d", $this->id);
				$row = $wpdb->get_row($query);
				wp_cache_set($cache_key, $row, 'wphr');
			}

			return $row;
		}

		return false;
	}

	/**
	 * Get data from wphr Department Name
	 *
	 * @param boolean $force if force from cache
	 *
	 * @return object the wpdb row object
	 */
	public function get_wphr_department_name($ids, $force = false) {
		global $wpdb;

		if ($ids) {
			$cache_key = 'wphr-empl-' . $ids;
			$row = wp_cache_get($cache_key, 'wphr', $force);

			if (false === $row) {
				$query = $wpdb->prepare("SELECT e.*, d.title as designation_title, dpt.title as department_Title, dpt.employee_label as department_profile_label, loc.name as location_name
		                  FROM {$wpdb->prefix}wphr_hr_employees AS e
		                  LEFT JOIN {$wpdb->prefix}wphr_hr_designations AS d ON d.id = e.designation
		                  LEFT JOIN {$wpdb->prefix}wphr_hr_depts AS dpt ON dpt.id = e.department
		                  LEFT JOIN {$wpdb->prefix}wphr_company_locations AS loc ON loc.id = e.location
		                  WHERE user_id = %d", $ids);
				$row = $wpdb->get_row($query);
				wp_cache_set($cache_key, $row, 'wphr');
			}

			return $row->department_Title;
		}

		return 'false';
	}

	/**
	 * Get an employee avatar
	 *
	 * @param  integer  avatar size in pixels
	 *
	 * @return string   image with HTML tag
	 */
	public function get_avatar_url($size = 32) {
		if ($this->id && !empty($this->user->photo_id)) {
			return wp_get_attachment_url($this->user->photo_id, ['size' => $size]);
		}

		return get_avatar_url($this->id, ['size' => $size]);
	}

	/**
	 * Get an employee avatar url
	 *
	 * @param  integer  avatar size in pixels
	 *
	 * @return string   image with HTML tag
	 */
	public function get_avatar($size = 32) {
		if ($this->id && !empty($this->user->photo_id)) {
			$image = wp_get_attachment_thumb_url($this->user->photo_id);
			return sprintf('<img src="%1$s" alt="" class="avatar avatar-%2$s photo" height="auto" width="%2$s" />', $image, $size);
		}

		$avatar = get_avatar($this->id, $size);

		if (!$avatar) {
			$image = WPHR_ASSETS . '/images/mystery-person.png';
			$avatar = sprintf('<img src="%1$s" alt="" class="avatar avatar-%2$s photo" height="auto" width="%2$s" />', $image, $size);
		}

		return $avatar;
	}

	/**
	 * Get single employee page view url
	 *
	 * @return string the url
	 */
	public function get_details_url() {
		if ($this->id) {
			return admin_url('admin.php?page=wphr-hr-employee&action=view&id=' . $this->id);
		}
	}

	/**
	 * Get the employees full name
	 *
	 * @return string
	 */
	public function get_full_name() {
		$name = array();

		if (!empty($this->user->first_name)) {
			$name[] = $this->user->first_name;
		}

		if (!empty($this->user->middle_name)) {
			$name[] = $this->user->middle_name;
		}

		if (!empty($this->user->last_name)) {
			$name[] = $this->user->last_name;
		}

		return implode(' ', $name);
	}

	/**
	 * Get an HTML link to single employee view
	 *
	 * @return string url to details
	 */
	public function get_link() {
		return sprintf('<a href="%s">%s</a>', $this->get_details_url(), $this->get_full_name());
	}

	/**
	 * Get the role
	 *
	 * @return string
	 */
	public function get_job_title() {
		if ($this->id && $this->designation_title) {
			return stripslashes($this->designation_title);
		}
	}
	/**
	 * Get the role
	 *
	 * @return string
	 */
	public function get_job_title_detail() {
		return $this->job_title_detail;
	}

	/**
	 * Get the department title
	 *
	 * @return string
	 */
	public function get_department_title() {
		if ($this->id && $this->department_title) {
			return stripslashes($this->department_title);
		}
	}

	/**
	 * Get the department wise employee label
	 *
	 * @return string
	 */
	public function get_department_emp_label() {
		return stripslashes($this->department_profile_label);
	}

	/**
	 * Get the work location title
	 *
	 * @return string
	 */
	public function get_work_location() {
		return $this->location_name;
	}

	/**
	 * Get the work location id
	 *
	 * @since 1.2.0
	 *
	 * @return int
	 */
	public function get_work_location_id() {
		return $this->location;
	}

	/**
	 * Get the employee status
	 *
	 * @return string
	 */
	public function get_status() {
		if ($this->status) {
			$statuses = wphr_hr_get_employee_statuses();

			if (array_key_exists($this->status, $statuses)) {
				return $statuses[$this->status];
			}
		}
	}

	/**
	 * Get the employee type
	 *
	 * @return string
	 */
	public function get_type() {
		if ($this->type) {
			$types = wphr_hr_get_employee_types();

			if (array_key_exists($this->type, $types)) {
				return $types[$this->type];
			}
		}
	}

	/**
	 * Get the employee work_hiring_source
	 *
	 * @return string
	 */
	public function get_hiring_source() {
		if ($this->hiring_source) {
			$sources = wphr_hr_get_employee_sources();

			if (array_key_exists($this->hiring_source, $sources)) {
				return $sources[$this->hiring_source];
			}
		}
	}

	/**
	 * Get the employee gender
	 *
	 * @return string
	 */
	public function get_gender() {
		if (!empty($this->user->gender)) {
			$genders = wphr_hr_get_genders();

			if (array_key_exists($this->user->gender, $genders)) {
				return $genders[$this->user->gender];
			}
		}
	}

	/**
	 * Get the marital status
	 *
	 * @return string
	 */
	public function get_marital_status() {
		if (!empty($this->user->marital_status)) {
			$statuses = wphr_hr_get_marital_statuses();

			if (array_key_exists($this->user->marital_status, $statuses)) {
				return $statuses[$this->user->marital_status];
			}
		}
	}

	/**
	 * Get the employee nationalit
	 *
	 * @return string
	 */
	public function get_nationality() {
		if (!empty($this->user->nationality)) {
			$countries = \WPHR\HR_MANAGER\Countries::instance()->get_countries();

			if (array_key_exists($this->user->nationality, $countries)) {
				return $countries[$this->user->nationality];
			}
		}
	}

	/**
	 * Get joined date
	 *
	 * @return string
	 */
	public function get_joined_date() {
		if ($this->hiring_date != '0000-00-00') {
			return wphr_format_date($this->hiring_date);
		}
	}

	/**
	 * Get birth date
	 *
	 * @return string
	 */
	public function get_birthday() {
		if ($this->date_of_birth != '0000-00-00') {
			return wphr_format_date($this->date_of_birth);
		}
	}

	/**
	 * Get Address 1
	 *
	 * @return string
	 */
	public function get_street_1() {
		return ($this->street_1) ? $this->street_1 : '—';
	}

	/**
	 * Get Address 2
	 *
	 * @return string
	 */
	public function get_street_2() {
		return ($this->street_2) ? $this->street_2 : '—';
	}

	/**
	 * Get City
	 *
	 * @return string
	 */
	public function get_city() {
		return ($this->city) ? $this->city : '—';
	}

	/**
	 * Get leave year
	 *
	 * @return int
	 */
	public function get_leave_year() {
		return ($this->leave_year) ? $this->leave_year : 1;
	}

	/**
	 * Get leave year
	 *
	 * @return int
	 */
	public function get_apply_leave_year() {
		return ($this->apply_leave_year) ? $this->apply_leave_year : '';
	}

	/**
	 * Get Country
	 *
	 * @return string
	 */
	public function get_country() {
		return wphr_get_country_name($this->country);
	}

	/**
	 * Get State
	 *
	 * @return string
	 */
	public function get_state() {
		return wphr_get_state_name($this->country, $this->state);
	}

	/**
	 * Get Postal Code
	 *
	 * @return string
	 */
	public function get_postal_code() {
		return ($this->postal_code) ? $this->postal_code : '—';
	}

	/**
	 * Get the name of reporting user
	 *
	 * @return string
	 */
	public function get_reporting_to() {
		if ($this->reporting_to) {
			$user_id = (int) $this->reporting_to;
			$user = new Employee($user_id);

			if ($user->id) {
				return $user;
			}
		}

		return false;
	}

	/**
	 * Get a phone number
	 *
	 * @param  string  type of phone. work|mobile|phone
	 *
	 * @return string
	 */
	public function get_phone($which = 'work') {
		$phone = '';

		switch ($which) {
		case 'mobile':
			$phone = isset($this->user->mobile) ? $this->user->mobile : '';
			break;

		case 'phone':
			$phone = isset($this->user->phone) ? $this->user->phone : '';
			break;

		default:
			$phone = isset($this->user->work_phone) ? $this->user->work_phone : '';
			break;
		}

		return $phone;
	}

	/**
	 * Get educational qualifications
	 *
	 * @return array the qualifications
	 */
	public function get_educations() {
		return Education::where('employee_id', '=', $this->id)->get();
	}

	/**
	 * Get work experiences
	 *
	 * @return array
	 */
	public function get_experiences() {
		return Work_Experience::where('employee_id', '=', $this->id)->get();
	}

	/**
	 * Get dependents
	 *
	 * @return array
	 */
	public function get_dependents() {
		return Dependents::where('employee_id', '=', $this->id)->get();
	}

	/**
	 * Get Performance
	 *
	 * @return array
	 */
	public function get_performance($emp_id, $type = '') {

		$performance = array('reviews' => array(), 'comments' => array(), 'goals' => array());
		$results = wphr_array_to_object(\WPHR\HR_MANAGER\HRM\Models\Performance::where('employee_id', $emp_id)->get()->toArray());

		if ($results) {

			foreach ($results as $key => $value) {
				if (isset($performance[$value->type])) {
					$performance[$value->type][] = $value;
				}
			}
		}

		if (!empty($type) && isset($performance[$type])) {
			return $performance[$type];
		}

		return $performance;

	}

	/**
	 * Update employment status
	 *
	 * @param string  $new_status   the employee status
	 * @param string  $date         date in mysql format
	 * @param string  $comment      comment
	 */
	public function update_employment_status($new_status, $date = '', $comment = '', $additional = '') {
		global $wpdb;

		$wpdb->update($wpdb->prefix . 'wphr_hr_employees', array(
			'type' => $new_status,
		), array(
			'user_id' => $this->id,
		), array(
			'%s',
		));

		// add in history
		$date = empty($date) ? current_time('mysql') : $date;
		$data = array(
			'user_id' => $this->id,
			'module' => 'employment',
			'type' => $new_status,
			'comment' => $comment,
			'date' => $date,
			'additional' => $additional,
		);

		wphr_hr_employee_add_history($data);
	}
/**
 * Update compensation of the employee
 *
 * @param  integer  $rate   the salary
 * @param  string   $type   the pay type
 * @param  string   $reason reason to change the salary
 * @param  string   $date   changed date
 * @param  string   $comment
 *
 * @return void
 */
	public function update_compensation($rate = 0, $type = '', $reason = '', $date = '', $comment = '', $additional = '') {
		global $wpdb;

		$wpdb->update($wpdb->prefix . 'wphr_hr_employees', array(
			'pay_rate' => $rate,
			'pay_type' => $type,
		), array(
			'user_id' => $this->id,
		), array(
			'%f',
			'%s',
		));

		// add in history
		$date = empty($date) ? current_time('mysql') : $date;
		$data = array(
			'user_id' => $this->id,
			'module' => 'compensation',
			'category' => $type,
			'type' => $rate,
			'comment' => $comment,
			'data' => $reason,
			'date' => $date,
			'additional' => $additional,

		);

		wphr_hr_employee_add_history($data);
	}

	/**
	 * Update job info
	 *
	 * @param  int   $department_id
	 * @param  int   $designation_id
	 * @param  int   $reporting_to
	 * @param  int   $location the location id
	 *
	 * @return void
	 */
	public function update_job_info($department_id, $designation_id, $reporting_to = 0, $location = 0, $date = '', $additional = '') {
		global $wpdb;

		$wpdb->update($wpdb->prefix . 'wphr_hr_employees', array(
			'designation' => $designation_id,
			'department' => $department_id,
			'location' => $location,
			'reporting_to' => $reporting_to,
		), array(
			'user_id' => $this->id,
		), array(
			'%d',
			'%d',
			'%d',
			'%d',
		));

		// force update the value if cached
		$this->wphr = $this->get_wphr_row(true);
		$date = empty($date) ? current_time('mysql') : $date;

		$data = array(
			'user_id' => $this->id,
			'module' => 'job',
			'category' => $this->get_department_title(),
			'type' => $this->get_work_location(),
			'comment' => $this->get_job_title(),
			'data' => $reporting_to,
			'date' => $date,
			'additional' => $additional,
		);
		wphr_hr_employee_add_history($data);
	}

	/**
	 * Get various hob history
	 *
	 * @param  string  $module the name of module or empty to get all
	 *
	 * @return array
	 */
	public function get_history($module = '') {
		global $wpdb;

		$sql = $wpdb->prepare("SELECT *
                FROM {$wpdb->prefix}wphr_hr_employee_history
                WHERE user_id = %d
                ORDER BY id DESC", $this->id);

		$history = array('job' => array(), 'compensation' => array(), 'employment' => array());
		$results = $wpdb->get_results($sql);

		if ($results) {
			foreach ($results as $key => $value) {
				if (isset($history[$value->module])) {
					$history[$value->module][] = $value;
				}
			}
		}

		if (!empty($module) && isset($history[$module])) {
			return $history[$module];
		}

		return $history;
	}

	/**
	 * Add a new note
	 *
	 * @param string  $note the note to be added
	 * @param int  $comment_by
	 *
	 * @return int note id
	 */
	public function add_note($note, $comment_by, $additional) {
		global $wpdb;

		//print_r($additional);
		//die();

		$data = array(
			'user_id' => $this->id,
			'comment' => $note,
			'comment_by' => $comment_by,
			'additional' => serialize($additional),
		);

		$inserted = \WPHR\HR_MANAGER\HRM\Models\Employee_Note::create($data);

		if ($inserted->id) {
			$note_id = $inserted->id;
			do_action('wphr_hr_employee_note_new', $note_id, $this->id);

			return $note_id;
		}

		return false;
	}

	/**
	 * Add a new note
	 *
	 * @param string  $note the note to be added
	 * @param int  $comment_by
	 *
	 * @return int note id
	 */
	public function add_note_field($comment_by, $additional) {
		global $wpdb;

		$data = array(
			'user_id' => $this->id,
			// 'comment'    => $note,
			'comment_by' => $comment_by,
			'additional' => $additional,
		);

		$inserted = \WPHR\HR_MANAGER\HRM\Models\Employee_Note::create($data);

		if ($inserted->id) {
			$note_id = $inserted->id;
			do_action('wphr_hr_employee_note_new', $note_id, $this->id);

			return $note_id;
		}

		return false;
	}

	/**
	 * Get all notes
	 *
	 * @param  int  $limit
	 * @param  int  $offset
	 *
	 * @return array
	 */
	public function get_notes($limit = 30, $offset = 0) {

		return \WPHR\HR_MANAGER\HRM\Models\Hr_User::find($this->id)
			->notes()
			->skip($offset)
			->take($limit)
			->get();
	}

	/**
	 * Get all notes
	 *
	 * @param integer $note_id
	 *
	 * @return boolean
	 */
	public function delete_note($note_id) {
		return \WPHR\HR_MANAGER\HRM\Models\Employee_Note::find($note_id)->delete();
	}

	/**
	 * Get all notes
	 *
	 * @return integer
	 */
	public function count_notes() {

		return \WPHR\HR_MANAGER\HRM\Models\Hr_User::find($this->id)
			->notes()
			->count();
	}

}
