<?php
namespace WPHR\HR_MANAGER\HRM;

/**
 * List table class
 */
class Employee_List_Table extends \WP_List_Table {

	private $counts = array();
	private $page_status = '';

	function __construct() {
		global $status, $page, $page_status;

		parent::__construct(array(
			'singular' => 'employee',
			'plural' => 'employees',
			'ajax' => false,
		));
	}

	/**
	 * Render extra filtering option in
	 * top of the table
	 *
	 * @since 0.1
	 *
	 * @param  string $which
	 *
	 * @return void
	 */
	function extra_tablenav($which) {
		if ($which != 'top') {
			return;
		}

		$selected_desingnation = (isset($_GET['filter_designation'])) ? sanitize_text_field($_GET['filter_designation']) : 0;
		$selected_department = (isset($_GET['filter_department'])) ? sanitize_text_field($_GET['filter_department']) : 0;
		$selected_type = (isset($_GET['filter_employment_type'])) ? sanitize_text_field($_GET['filter_employment_type']) : '';
		?>
        <div class="alignleft actions">

            <label class="screen-reader-text" for="new_role"><?php _e('Filter by Role', 'wphr')?></label>
            <select name="filter_designation" id="filter_designation">
                <?php echo wphr_hr_get_designation_dropdown($selected_desingnation); ?>
            </select>

            <label class="screen-reader-text" for="new_role"><?php _e('Filter by Role', 'wphr')?></label>
            <select name="filter_department" id="filter_department">
                <?php echo wphr_hr_get_departments_dropdown($selected_department); ?>
            </select>

            <label class="screen-reader-text" for="new_role"><?php _e('Filter by Employment Type', 'wphr')?></label>
            <select name="filter_employment_type" id="filter_employment_type">
                <option value="-1"><?php _e('- Select Employment Type -', 'wphr')?></option>
                <?php
$types = wphr_hr_get_employee_types();

		foreach ($types as $key => $title) {
			echo sprintf("<option value='%s'%s>%s</option>\n", $key, selected($selected_type, $key, false), $title);
		}
		?>
            </select>

            <?php
submit_button(__('Filter'), 'button', 'filter_employee', false);
		echo '</div>';
	}

	/**
	 * Message to show if no employee found
	 *
	 * @return void
	 */
	function no_items() {
		_e('No employee found.', 'wphr');
	}

	/**
	 * Default column values if no callback found
	 *
	 * @param  object  $item
	 * @param  string  $column_name
	 *
	 * @return string
	 */
	function column_default($employee, $column_name) {

		// print_r($employee);
		// die();

		switch ($column_name) {
		case 'designation':
			return $employee->get_job_title();

		case 'department':
			return $employee->get_department_title();

		case 'type':
			return $employee->get_type();

		case 'date_of_hire':
			return $employee->get_joined_date();

		case 'status':
			return wphr_hr_get_employee_statuses_icons($employee->status);

		case 'resend_email':
			return sprintf('<span class="spinner"></span><a class="button ressend_welcome_email" data-user_id="%d" href="#" data-success="%s">%s</a>', $employee->ID, __('Send', 'wphr'), __('Resend', 'wphr'));
		default:
			return isset($employee->$column_name) ? $employee->$column_name : '';
		}
	}

	public function current_action() {

		if (isset($_REQUEST['filter_employee'])) {
			return 'filter_employee';
		}

		if (isset($_REQUEST['employee_search'])) {
			return 'employee_search';
		}

		return parent::current_action();
	}

	/**
	 * Get sortable columns
	 *
	 * @return array
	 */
	function get_sortable_columns() {
		$sortable_columns = array(
			'name' => array('employee_name', false),
			'date_of_hire' => array('hiring_date', false),
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
			'designation' => __('Role', 'wphr'),
			'department' => __('Department', 'wphr'),
			'type' => __('Employment Type', 'wphr'),
			'date_of_hire' => __('Joined', 'wphr'),
			'status' => __('Status', 'wphr'),
		);
		if (current_user_can(wphr_hr_get_manager_role())) {
			$columns['resend_email'] = __('Resend Welcome Email', 'wphr');
		}
		return apply_filters('wphr_hr_employee_table_cols', $columns);
	}

	/**
	 * Render the employee name column
	 *
	 * @param  object  $item
	 *
	 * @return string
	 */
	function column_name($employee) {
		$actions = array();
		$delete_url = '';
		$data_hard = (isset($_REQUEST['status']) && sanitize_text_field($_REQUEST['status']) == 'trash') ? 1 : 0;
		$delete_text = (isset($_REQUEST['status']) && sanitize_text_field($_REQUEST['status']) == 'trash') ? __('Permanent Delete', 'wphr') : __('Delete', 'wphr');

		if (current_user_can('wphr_edit_employee', $employee->id)) {
			$actions['edit'] = sprintf('<a href="%s" data-id="%d"  title="%s">%s</a>', $delete_url, $employee->id, __('Edit this item', 'wphr'), __('Edit', 'wphr'));
		}

		if (current_user_can('wphr_delete_employee')) {
			$actions['delete'] = sprintf('<a href="%s" class="submitdelete" data-id="%d" data-hard=%d title="%s">%s</a>', $delete_url, $employee->id, $data_hard, __('Delete this item', 'wphr'), $delete_text);
		}

		if ($data_hard) {
			$actions['restore'] = sprintf('<a href="%s" class="submitrestore" data-id="%d" title="%s">%s</a>', $delete_url, $employee->id, __('Restore this item', 'wphr'), __('Restore', 'wphr'));
		}

		return sprintf('%4$s <a href="%3$s"><strong>%1$s</strong></a> %2$s', $employee->get_full_name(), $this->row_actions($actions), wphr_hr_url_single_employee($employee->id), $employee->get_avatar());
	}

	/**
	 * Set the bulk actions
	 *
	 * @return array
	 */
	function get_bulk_actions() {
		$actions = [];

		if (!current_user_can(wphr_hr_get_manager_role())) {
			return $actions;
		}

		$actions = [
			'delete' => __('Move to Trash', 'wphr'),
		];

		if (isset($_REQUEST['status']) && sanitize_text_field($_REQUEST['status']) == 'trash') {
			unset($actions['delete']);

			$actions['permanent_delete'] = __('Permanent Delete', 'wphr');
			$actions['restore'] = __('Restore', 'wphr');
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
		return sprintf(
			'<input type="checkbox" name="employee_id[]" value="%s" />', $item->id
		);
	}

	/**
	 * Set the views
	 *
	 * @return array
	 */
	public function get_views() {
		$status_links = array();
		$base_link = admin_url('admin.php?page=wphr-hr-employee');

		foreach ($this->counts as $key => $value) {
			$class = ($key == $this->page_status) ? 'current' : 'status-' . $key;
			$status_links[$key] = sprintf('<a href="%s" class="%s">%s <span class="count">(%s)</span></a>', add_query_arg(array('status' => $key), $base_link), $class, $value['label'], $value['count']);
		}

		$status_links['trash'] = sprintf('<a href="%s" class="status-trash">%s <span class="count">(%s)</span></a>', add_query_arg(array('status' => 'trash'), $base_link), __('Trash', 'wphr'), wphr_hr_count_trashed_employees());

		return $status_links;
	}

	/**
	 * Search form for employee table
	 *
	 * @since 0.1
	 *
	 * @param  string $text
	 * @param  string $input_id
	 *
	 * @return void
	 */
	public function search_box($text, $input_id) {
		if (empty($_REQUEST['s']) && !$this->has_items()) {
			return;
		}

		$input_id = $input_id . '-search-input';

		if (!empty($_REQUEST['orderby'])) {
			echo '<input type="hidden" name="orderby" value="' . esc_attr($_REQUEST['orderby']) . '" />';
		}

		if (!empty($_REQUEST['order'])) {
			echo '<input type="hidden" name="order" value="' . esc_attr($_REQUEST['order']) . '" />';
		}

		if (!empty($_REQUEST['status'])) {
			echo '<input type="hidden" name="status" value="' . esc_attr($_REQUEST['status']) . '" />';
		}

		if (!empty($_REQUEST['post_mime_type'])) {
			echo '<input type="hidden" name="post_mime_type" value="' . esc_attr($_REQUEST['post_mime_type']) . '" />';
		}

		if (!empty($_REQUEST['detached'])) {
			echo '<input type="hidden" name="detached" value="' . esc_attr($_REQUEST['detached']) . '" />';
		}
		?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query();?>" />
            <?php submit_button($text, 'button', 'employee_search', false, array('id' => 'search-submit'));?>
        </p>
        <?php
}

	/**
	 * Prepare the class items
	 *
	 * @return void
	 */
	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

		$per_page = 20;
		$current_page = $this->get_pagenum();
		$offset = ($current_page - 1) * $per_page;
		$this->page_status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'active';

		// only ncessary because we have sample data
		$args = array(
			'offset' => $offset,
			'number' => $per_page,
		);

		if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
			$args['s'] = sanitize_text_field($_REQUEST['s']);
		}

		if (isset($_REQUEST['orderby']) && !empty($_REQUEST['orderby'])) {
			$args['orderby'] = sanitize_text_field($_REQUEST['orderby']);
		}

		if (isset($_REQUEST['status']) && !empty($_REQUEST['status']) && current_user_can(wphr_hr_get_manager_role())) {
			$args['status'] = sanitize_text_field($_REQUEST['status']);
		}

		if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
			$args['order'] = sanitize_text_field($_REQUEST['order']);
		}

		if (isset($_REQUEST['filter_designation']) && sanitize_text_field($_REQUEST['filter_designation'])) {
			$args['designation'] = sanitize_text_field($_REQUEST['filter_designation']);
		}

		if (isset($_REQUEST['filter_department']) && sanitize_text_field($_REQUEST['filter_department'])) {
			$args['department'] = sanitize_text_field($_REQUEST['filter_department']);
		}

		if (isset($_REQUEST['filter_employment_type']) && sanitize_text_field($_REQUEST['filter_employment_type'])) {
			$args['type'] = sanitize_text_field($_REQUEST['filter_employment_type']);
		}

		$this->counts = wphr_hr_employee_get_status_count();
		$this->items = wphr_hr_get_employees($args);

		$args['count'] = true;
		$total_items = wphr_hr_get_employees($args);

		$this->set_pagination_args(array(
			'total_items' => $total_items,
			'per_page' => $per_page,
		));
	}

}
