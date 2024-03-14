<?php

namespace WPHR\HR_MANAGER\HRM;

use WPHR\HR_MANAGER\Framework\Traits\Hooker;

/**
 * The HRM Class
 *
 * This is loaded in `init` action hook
 */
class Human_Resource {
	use Hooker;
	private $plugin;
	/**
	 * Kick-in the class
	 *
	 * @param \clsWP_HR $plugin
	 */
	public function __construct(\clsWP_HR $plugin) {
		// prevent duplicate loading
		if (did_action('wphr_hrm_loaded')) {
			return;
		}
		$this->plugin = $plugin;
		// Define constants
		$this->hr_define_constants();
		// Include required files
		$this->includes_files();
		// Initialize the classes
		$this->init_classes();
		// Initialize the action hooks
		$this->wphr_init_actions();
		// Initialize the filter hooks
		$this->init_filters();
		do_action('wphr_hrm_loaded');
	}

	/**
	 * Define the plugin constants
	 *
	 * @return void
	 */
	private function hr_define_constants() {
		define('WPHR_HRM_FILE', __FILE__);
		define('WPHR_HRM_PATH', dirname(__FILE__));
		define('WPHR_HRM_VIEWS', dirname(__FILE__) . '/views');
		define('WPHR_HRM_JS_TMPL', WPHR_HRM_VIEWS . '/js-templates');
		define('WPHR_HRM_ASSETS', plugins_url('/assets', __FILE__));
	}

	/**
	 * Include the required files
	 *
	 * @since 1.0.0
	 * @since 1.2.0 Include CLI class
	 *
	 * @return void
	 */
	private function includes_files() {
		require_once WPHR_HRM_PATH . '/includes/functions-url.php';
		require_once WPHR_HRM_PATH . '/includes/functions.php';
		require_once WPHR_HRM_PATH . '/includes/functions-department.php';
		require_once WPHR_HRM_PATH . '/includes/functions-designation.php';
		require_once WPHR_HRM_PATH . '/includes/functions-employee.php';
		require_once WPHR_HRM_PATH . '/includes/functions-leave.php';
		require_once WPHR_HRM_PATH . '/includes/functions-capabilities.php';
		require_once WPHR_HRM_PATH . '/includes/functions-dashboard-widgets.php';
		require_once WPHR_HRM_PATH . '/includes/functions-reporting.php';
		require_once WPHR_HRM_PATH . '/includes/functions-announcement.php';
		require_once WPHR_HRM_PATH . '/includes/actions-filters.php';
		// cli command
		if (defined('WP_CLI') && WP_CLI) {
			include WPHR_HRM_PATH . '/includes/cli/commands.php';
		}
	}

	/**
	 * Initialize WordPress action hooks
	 *
	 * @return void
	 */
	private function wphr_init_actions() {
		$this->action('admin_enqueue_scripts', 'admin_scripts');
		$this->action('admin_footer', 'admin_js_templates');
	}

	/**
	 * Initialize WordPress filter hooks
	 *
	 * @return void
	 */
	private function init_filters() {
		add_filter('wphr_settings_pages', array($this, 'add_settings_page'));
	}

	/**
	 * Init classes
	 *
	 * @return void
	 */
	private function init_classes() {
		new Ajax_Handler();
		new Form_Handler();
		new Announcement();
		new Admin\Admin_Menu();
		new Admin\User_Profile();
		new Hr_Log();
		new Emailer();
	}

	/**
	 * Register HR settings page
	 *
	 * @param array
	 */
	public function add_settings_page($settings = array()) {
		$settings[] = (include __DIR__ . '/includes/class-settings.php');
		return $settings;
	}

	/**
	 * Load admin scripts and styles
	 *
	 * @param  string
	 *
	 * @return void
	 */
	public function admin_scripts($hook) {
		$hook = str_replace(sanitize_title(__('WPHR Manager', 'wphr')), 'wphr-manager', $hook);
		$suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min');
		wp_enqueue_media();
		wp_enqueue_script('wphr-tiptip');

		if ('wphr-manager_page_wphr-hr-employee' == $hook) {
			wp_enqueue_style('wphr-sweetalert');
			wp_enqueue_script('wphr-sweetalert');
			wp_enqueue_style('wphr-timepicker');
			wp_enqueue_script('wphr-timepicker');
		}

		$version = filemtime(WPHR_HRM_PATH . "/assets/js/hrm{$suffix}.js");
		wp_enqueue_script(
			'wp-wphr-hr',
			WPHR_HRM_ASSETS . "/js/hrm{$suffix}.js",
			array('wphr-script'),
			$version,
			true
		);
		//        $version = filemtime( WPHR_HRM_PATH . "/assets/js/leave$suffix.js" );
		//        wp_enqueue_script( 'wp-wphr-hr-leave', WPHR_HRM_ASSETS . "/js/leave$suffix.js", array(
		$version = filemtime(WPHR_HRM_PATH . "/assets/js/leave.js");
		wp_enqueue_script(
			'wp-wphr-hr-leave',
			WPHR_HRM_ASSETS . "/js/leave.js",
			array('wphr-script', 'wp-color-picker'),
			$version,
			true
		);
		/*wp_enqueue_script( 'wp-wphr-hr-leave', WPHR_HRM_ASSETS . "/js/leave.js", array(
			              'wphr-script',
			              'wp-color-picker'
		*/
		$localize_script = apply_filters('wphr_hr_localize_script', array(
			'nonce' => wp_create_nonce('wp-wphr-hr-nonce'),
			'popup' => array(
				'dept_title' => __('New Department', 'wphr'),
				'dept_submit' => __('Create Department', 'wphr'),
				'location_title' => __('New Location', 'wphr'),
				'location_submit' => __('Create Location', 'wphr'),
				'dept_update' => __('Update Department', 'wphr'),
				'desig_title' => __('New Role', 'wphr'),
				'desig_submit' => __('Create Role', 'wphr'),
				'desig_update' => __('Update Role', 'wphr'),
				'employee_title' => __('New Employee', 'wphr'),
				'employee_create' => __('Create Employee', 'wphr'),
				'employee_update' => __('Update Employee', 'wphr'),
				'employment_status' => __('Employment Status', 'wphr'),
				'update_status' => __('Update', 'wphr'),
				'policy' => __('Leave Policy', 'wphr'),
				'policy_create' => __('Create Policy', 'wphr'),
				'holiday' => __('Holiday', 'wphr'),
				'holiday_create' => __('Create Holiday', 'wphr'),
				'holiday_update' => __('Update Holiday', 'wphr'),
				'new_leave_req' => __('Leave Request', 'wphr'),
				'take_leave' => __('Send Leave Request', 'wphr'),
				'terminate' => __('Terminate', 'wphr'),
				'leave_reject' => __('Reject Reason', 'wphr'),
				'already_terminate' => __('Sorry, this employee is already terminated', 'wphr'),
				'already_active' => __('Sorry, this employee is already active', 'wphr'),
			),
			'emp_upload_photo' => __('Upload Employee Photo', 'wphr'),
			'emp_set_photo' => __('Set Photo', 'wphr'),
			'confirm' => __('Are you sure?', 'wphr'),
			'delConfirmDept' => __('Are you sure to delete this department?', 'wphr'),
			'delConfirmPolicy' => __('If you delete this policy, the leave entitlements and requests related to it will also be deleted. Are you sure to delete this policy?', 'wphr'),
			'delConfirmHoliday' => __('Are you sure to delete this Holiday?', 'wphr'),
			'delConfirmEmployee' => __('Are you sure to delete this employee?', 'wphr'),
			'restoreConfirmEmployee' => __('Are you sure to restore this employee?', 'wphr'),
			'delConfirmEmployeeNote' => __('Are you sure to delete this employee note?', 'wphr'),
			'delConfirmEntitlement' => __('Are you sure to delete this Entitlement? If yes, then all leave request under this entitlement also permanently deleted', 'wphr'),
			'make_employee_text' => __('This user already exists, Do you want to make this user as a employee?', 'wphr'),
			'employee_exit' => __('This employee already exists', 'wphr'),
			'employee_created' => __('Employee successfully created', 'wphr'),
			'create_employee_text' => __('Click to create employee', 'wphr'),
			'empty_entitlement_text' => sprintf(
				'<span>%s <a href="%s" title="%s">%s</a></span>',
				__('Please create entitlement first', 'wphr'),
				add_query_arg([
					'page' => 'wphr-leave-assign',
					'tab' => 'assignment',
				], admin_url('admin.php')),
				__('Create Entitlement', 'wphr'),
				__('Create Entitlement', 'wphr')
			),
			'date_format' => str_replace(['d', 'm', 'Y'], ['dd', 'mm', 'yy'], wphr_get_option('date_format', 'wphr_settings_general', 'd-m-Y')),
			'confirmMsg_1' => __('Are you sure?', 'wphr'),
		));
		// if its an employee page

		if ('wphr-manager_page_wphr-hr-employee' == $hook) {
			wp_enqueue_script('post');
			$employee = new Employee();
			$localize_script['employee_empty'] = $employee->to_array();
		}

		wp_localize_script('wp-wphr-hr', 'wpHr', $localize_script);
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style('wphr-select2');
		wp_enqueue_style('wphr-tiptip');
		wp_enqueue_style('wphr-style');

		if ('wphr-manager_page_wphr-hr-reporting' == $hook) {
			wp_enqueue_script('wphr-flotchart');
			wp_enqueue_script('wphr-flotchart-time');
			wp_enqueue_script('wphr-flotchart-pie');
			wp_enqueue_script('wphr-flotchart-orerbars');
			wp_enqueue_script('wphr-flotchart-axislables');
			wp_enqueue_script('wphr-flotchart-valuelabel');
			wp_enqueue_style('wphr-flotchart-valuelabel-css');
		}

	}

	/**
	 * Print JS templates in footer
	 *
	 * @return void
	 */
	public function admin_js_templates() {
		global $current_screen;
		// main HR menu
		$hook = str_replace(sanitize_title(__('WPHR Manager', 'wphr')), 'wphr-manager', $current_screen->base);
		switch ($hook) {
		case 'toplevel_page_wphr-hr':
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/new-leave-request.php', 'wphr-new-leave-req');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/leave-days.php', 'wphr-leave-days');
			break;
		case 'wphr-manager_page_wphr-hr-depts':
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/new-dept.php', 'wphr-new-dept');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/row-dept.php', 'wphr-dept-row');
			break;
		case 'wphr-manager_page_wphr-hr-designation':
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/new-designation.php', 'wphr-new-desig');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/row-desig.php', 'wphr-desig-row');
			break;
		case 'wphr-manager_page_wphr-hr-employee':
		case 'wphr-manager_page_wphr-hr-my-profile':
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/new-employee.php', 'wphr-new-employee');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/row-employee.php', 'wphr-employee-row');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/employment-status.php', 'wphr-employment-status');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/compensation.php', 'wphr-employment-compensation');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/job-info.php', 'wphr-employment-jobinfo');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/work-experience.php', 'wphr-employment-work-experience');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/education-form.php', 'wphr-employment-education');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/performance-reviews.php', 'wphr-employment-performance-reviews');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/performance-comments.php', 'wphr-employment-performance-comments');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/performance-goals.php', 'wphr-employment-performance-goals');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/dependents.php', 'wphr-employment-dependent');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/new-dept.php', 'wphr-new-dept');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/new-designation.php', 'wphr-new-desig');
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/employee-terminate.php', 'wphr-employment-terminate');
			break;
		}
		// leave menu
		$hook = str_replace(sanitize_title(__('WPHR Leave', 'wphr')), 'wphr-leave', $current_screen->base);
		switch ($hook) {
		case 'wphr-leave_page_wphr-leave-policies':
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/leave-policy.php', 'wphr-leave-policy');
			break;
		case 'wphr-leave_page_wphr-holiday-assign':

			if (isset($_GET['subpage']) && sanitize_text_field($_GET['subpage']) == 'location') {
			} else {
				wphr_get_js_template(WPHR_HRM_JS_TMPL . '/holiday.php', 'wphr-hr-holiday-js-tmp');
			}

			break;
		case 'toplevel_page_wphr-leave':
			wphr_get_js_template(WPHR_HRM_JS_TMPL . '/leave-reject.php', 'wphr-hr-leave-reject-js-tmp');
			break;
		default:
			# code...
			break;
		}
	}

}