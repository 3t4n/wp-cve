<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themesawesome.com/
 * @since      1.0.0
 *
 * @package    Sakolawp
 * @subpackage Sakolawp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sakolawp
 * @subpackage Sakolawp/admin
 * @author     Themes Awesome <themesawesome@gmail.com>
 */
class Sakolawp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// custom construct
		add_action('admin_menu', array( $this, 'addPluginAdminMenu' ), 9);
		add_action('admin_init', array( $this, 'registerAndBuildFields' ));

		add_action('wp_ajax_sakolawp_select_section',        'sakolawp_select_section_f');
        add_action('wp_ajax_nopriv_sakolawp_select_section', 'sakolawp_select_section_f');
        
        add_action( 'wp_ajax_sakolawp_select_subject', 'sakolawp_select_subject_f' );
        add_action( 'wp_ajax_nopriv_sakolawp_select_subject', 'sakolawp_select_subject_f' );

		add_action( 'wp_ajax_sakolawp_select_section_first', 'sakolawp_select_section_first_f' );
		add_action( 'wp_ajax_nopriv_sakolawp_select_section_first', 'sakolawp_select_section_first_f' );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sakolawp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sakolawp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name.'-fonts', plugin_dir_url( __FILE__ ) . 'css/sakolawp-admin-fonts.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-rtl', plugin_dir_url( __FILE__ ) . 'css/sakolawp-admin-rtl.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sakolawp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sakolawp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sakolawp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), false );
		wp_enqueue_script( 'modal', plugin_dir_url( __FILE__ ) . 'js/modal.js', array( 'jquery' ), false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sakolawp-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'daterange', plugin_dir_url( __FILE__ ) . 'js/daterange.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'skwp-custom', plugin_dir_url( __FILE__ ) .'js/skwp-custom.js', array(), '1.0.0', true );
        wp_localize_script( 'skwp-custom', 'skwp_ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}


	public function addPluginAdminMenu() {
		add_menu_page( 'Sakola WP', 'Sakola WP', 'administrator', $this->plugin_name.'-settings', array( $this, 'displayPluginAdminSettings'), 'dashicons-welcome-learn-more', 2 );
		add_submenu_page( $this->plugin_name.'-settings', 'Dashboard', 'Dashboard', 'administrator', $this->plugin_name.'-settings', array( $this, 'displayPluginAdminSettings' ));
		
		add_submenu_page( $this->plugin_name.'-settings', esc_html__('Classes', 'sakolawp'), esc_html__('Manage Classes', 'sakolawp'), 'administrator', $this->plugin_name.'-manage-class', array( $this, 'manageClassAdminSettings' ));
		add_submenu_page( $this->plugin_name.'-settings', esc_html__('Sections', 'sakolawp'), esc_html__('Manage Sections', 'sakolawp'), 'administrator', $this->plugin_name.'-manage-section', array( $this, 'manageSectionAdminSettings' ));
		add_submenu_page( $this->plugin_name.'-settings', esc_html__('Subjects', 'sakolawp'), esc_html__('Manage Subjects', 'sakolawp'), 'administrator', $this->plugin_name.'-manage-subject', array( $this, 'manageSubjectAdminSettings' ));
		add_submenu_page( $this->plugin_name.'-settings', esc_html__('Routine', 'sakolawp'), esc_html__('Manage Routines', 'sakolawp'), 'administrator', $this->plugin_name.'-manage-routine', array( $this, 'manageRoutineAdminSettings' ));
		add_submenu_page( $this->plugin_name.'-settings', esc_html__('Attendance', 'sakolawp'), esc_html__('Manage Attendance', 'sakolawp'), 'administrator', $this->plugin_name.'-manage-attendance', array( $this, 'manageAttendanceAdminSettings' ));
		add_submenu_page( $this->plugin_name.'-settings', esc_html__('Report Attendance', 'sakolawp'), esc_html__('Manage Report Attendance', 'sakolawp'), 'administrator', $this->plugin_name.'-manage-report-attendance', array( $this, 'manageReportAttendanceAdminSettings' ));
		add_submenu_page( $this->plugin_name.'-settings', esc_html__('Students Area', 'sakolawp'), esc_html__('Students Area', 'sakolawp'), 'administrator', $this->plugin_name.'-student-area', array( $this, 'studentAreaAdminSettings' ));
		add_submenu_page( $this->plugin_name.'-settings', esc_html__('Assign Student', 'sakolawp'), esc_html__('Assign Student', 'sakolawp'), 'administrator', $this->plugin_name.'-assign-student', array( $this, 'assignStudentAdminSettings' ));
		add_submenu_page( $this->plugin_name.'-settings', esc_html__('Homeworks', 'sakolawp'), esc_html__('Homeworks', 'sakolawp'), 'administrator', $this->plugin_name.'-homework', array( $this, 'homeworkAdminSettings' ));
		add_submenu_page( $this->plugin_name.'-settings', esc_html__('Exams', 'sakolawp'), esc_html__('Exams', 'sakolawp'), 'administrator', $this->plugin_name.'-exam', array( $this, 'examAdminSettings' ));
		$docs_url = esc_url('https://themesawesome.zendesk.com/hc/en-us/categories/360003331032-SakolaWP');
		add_submenu_page( $this->plugin_name.'-settings', esc_html__('Documentation', 'sakolawp'), esc_html__('Documentation', 'sakolawp'), 'administrator', $docs_url, '');
	}

	public function displayPluginAdminSettings() {
		 

		if(isset($_GET['delete'])) {
			$exam_id = sanitize_text_field($_GET['delete']); ?>
			<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Delete Semester', 'sakolawp'); ?></h5>
							<a href="?page=sakolawp-settings">
								<span aria-hidden="true">&times;</span>
							</a>
						</div>
						<form id="myForm" name="myform" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
							<input type="hidden" name="action" value="delete_exam_setting" />
							<input type="hidden" name="exam_id" value="<?php echo esc_attr($exam_id); ?>" />
							<div class="modal-body">
								<?php esc_html_e('Are you sure ?', 'sakolawp'); ?>
							</div>
							<div class="modal-footer">
								<a href="?page=sakolawp-settings" class="btn skwp-btn btn-sm btn-danger"><?php esc_html_e('Close', 'sakolawp'); ?></a>
								<button class="btn btn-primary skwp-btn" type="submit"><?php esc_html_e('Delete', 'sakolawp'); ?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-backdrop fade show"></div>
		<?php }

		if(isset($_GET['edit'])) {
			$exam_id = sanitize_text_field($_GET['edit']); ?>
			<!-- Modal -->
			<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Edit Semester', 'sakolawp'); ?></h5>
							<a href="?page=sakolawp-settings">
								<span aria-hidden="true">&times;</span>
							</a>
						</div>
						<?php 
							global $wpdb;
							$exams = $wpdb->get_results( "SELECT start_exam, name, end_exam FROM {$wpdb->prefix}sakolawp_exam WHERE exam_id =".$exam_id."", ARRAY_A );
							foreach($exams as $exam):
						?>
						<form id="myForm" name="myform" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
							<input type="hidden" name="action" value="edit_exam_setting" />
							<input type="hidden" name="exam_id" value="<?php echo esc_attr($exam_id); ?>" />
							<div class="modal-body">
								<div class="form-group skwp-row">
									<label class="skwp-column skwp-column-3 col-form-label" for=""> <?php esc_html_e('Semester', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<input class="skwp-form-control" placeholder="<?php esc_attr_e('Semester', 'sakolawp'); ?>" name="name" required="" type="text" value="<?php echo esc_attr($exam['name']); ?>">
										</div>
									</div>
								</div>
								<div class="form-group skwp-row">
									<label class="skwp-column skwp-column-3 col-form-label" for=""> <?php esc_html_e('Start', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<select class="skwp-form-control" name="start">
												<option value="1" <?php if($exam['start_exam'] == 1){echo "selected";} ?>><?php esc_html_e('January', 'sakolawp'); ?></option>
												<option value="2" <?php if($exam['start_exam'] == 2){echo "selected";} ?>><?php esc_html_e('February', 'sakolawp'); ?></option>
												<option value="3" <?php if($exam['start_exam'] == 3){echo "selected";} ?>><?php esc_html_e('March', 'sakolawp'); ?></option>
												<option value="4" <?php if($exam['start_exam'] == 4){echo "selected";} ?>><?php esc_html_e('April', 'sakolawp'); ?></option>
												<option value="5" <?php if($exam['start_exam'] == 5){echo "selected";} ?>><?php esc_html_e('May', 'sakolawp'); ?></option>
												<option value="6" <?php if($exam['start_exam'] == 6){echo "selected";} ?>><?php esc_html_e('June', 'sakolawp'); ?></option>
												<option value="7" <?php if($exam['start_exam'] == 7){echo "selected";} ?>><?php esc_html_e('July', 'sakolawp'); ?></option>
												<option value="8" <?php if($exam['start_exam'] == 8){echo "selected";} ?>><?php esc_html_e('August', 'sakolawp'); ?></option>
												<option value="9" <?php if($exam['start_exam'] == 9){echo "selected";} ?>><?php esc_html_e('September', 'sakolawp'); ?></option>
												<option value="10" <?php if($exam['start_exam'] == 10){echo "selected";} ?>><?php esc_html_e('October', 'sakolawp'); ?></option>
												<option value="11" <?php if($exam['start_exam'] == 11){echo "selected";} ?>><?php esc_html_e('November', 'sakolawp'); ?></option>
												<option value="12" <?php if($exam['start_exam'] == 12){echo "selected";} ?>><?php esc_html_e('December', 'sakolawp'); ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group skwp-row">
									<label class="skwp-column skwp-column-3 col-form-label" for=""> <?php esc_html_e('End', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<select class="skwp-form-control" name="end">
												<option value="1" <?php if($exam['end_exam'] == 1){echo "selected";} ?>><?php esc_html_e('January', 'sakolawp'); ?></option>
												<option value="2" <?php if($exam['end_exam'] == 2){echo "selected";} ?>><?php esc_html_e('February', 'sakolawp'); ?></option>
												<option value="3" <?php if($exam['end_exam'] == 3){echo "selected";} ?>><?php esc_html_e('March', 'sakolawp'); ?></option>
												<option value="4" <?php if($exam['end_exam'] == 4){echo "selected";} ?>><?php esc_html_e('April', 'sakolawp'); ?></option>
												<option value="5" <?php if($exam['end_exam'] == 5){echo "selected";} ?>><?php esc_html_e('May', 'sakolawp'); ?></option>
												<option value="6" <?php if($exam['end_exam'] == 6){echo "selected";} ?>><?php esc_html_e('June', 'sakolawp'); ?></option>
												<option value="7" <?php if($exam['end_exam'] == 7){echo "selected";} ?>><?php esc_html_e('July', 'sakolawp'); ?></option>
												<option value="8" <?php if($exam['end_exam'] == 8){echo "selected";} ?>><?php esc_html_e('August', 'sakolawp'); ?></option>
												<option value="9" <?php if($exam['end_exam'] == 9){echo "selected";} ?>><?php esc_html_e('September', 'sakolawp'); ?></option>
												<option value="10" <?php if($exam['end_exam'] == 10){echo "selected";} ?>><?php esc_html_e('October', 'sakolawp'); ?></option>
												<option value="11" <?php if($exam['end_exam'] == 11){echo "selected";} ?>><?php esc_html_e('November', 'sakolawp'); ?></option>
												<option value="12" <?php if($exam['end_exam'] == 12){echo "selected";} ?>><?php esc_html_e('December', 'sakolawp'); ?></option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<a href="?page=sakolawp-settings" class="btn skwp-btn btn-sm btn-danger"><?php esc_html_e('Close', 'sakolawp'); ?></a>
								<button class="btn btn-primary skwp-btn" type="submit"><?php esc_html_e('Save changes', 'sakolawp'); ?></button>
							</div>
						</form>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="modal-backdrop fade show"></div>
		<?php }

		if(isset($_GET['create'])) { ?>
			<!-- Modal -->
			<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('New Semester', 'sakolawp'); ?></h5>
							<a href="?page=sakolawp-settings">
								<span aria-hidden="true">&times;</span>
							</a>
						</div>
						<form id="myForm" name="myform" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
							<input type="hidden" name="action" value="save_exam_setting" />
							<input type="hidden" name="exam_id" value="<?php echo esc_attr($exam_id); ?>" />
							<div class="modal-body">
								<div class="form-group skwp-row">
									<label class="skwp-column skwp-column-3 col-form-label" for=""> <?php esc_html_e('Semester', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<input class="skwp-form-control" placeholder="<?php esc_attr_e('Semester', 'sakolawp'); ?>" name="name" required="" type="text">
										</div>
									</div>
								</div>
								<div class="form-group skwp-row">
									<label class="skwp-column skwp-column-3 col-form-label" for=""> <?php esc_html_e('Start', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<select class="skwp-form-control" name="start">
												<option value="1"><?php esc_html_e('January', 'sakolawp'); ?></option>
												<option value="2"><?php esc_html_e('February', 'sakolawp'); ?></option>
												<option value="3"><?php esc_html_e('March', 'sakolawp'); ?></option>
												<option value="4"><?php esc_html_e('April', 'sakolawp'); ?></option>
												<option value="5"><?php esc_html_e('May', 'sakolawp'); ?></option>
												<option value="6"><?php esc_html_e('June', 'sakolawp'); ?></option>
												<option value="7"><?php esc_html_e('July', 'sakolawp'); ?></option>
												<option value="8"><?php esc_html_e('August', 'sakolawp'); ?></option>
												<option value="9"><?php esc_html_e('September', 'sakolawp'); ?></option>
												<option value="10"><?php esc_html_e('October', 'sakolawp'); ?></option>
												<option value="11"><?php esc_html_e('November', 'sakolawp'); ?></option>
												<option value="12"><?php esc_html_e('December', 'sakolawp'); ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group skwp-row">
									<label class="skwp-column skwp-column-3 col-form-label" for=""> <?php esc_html_e('End', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<select class="skwp-form-control" name="end">
												<option value="1"><?php esc_html_e('January', 'sakolawp'); ?></option>
												<option value="2"><?php esc_html_e('February', 'sakolawp'); ?></option>
												<option value="3"><?php esc_html_e('March', 'sakolawp'); ?></option>
												<option value="4"><?php esc_html_e('April', 'sakolawp'); ?></option>
												<option value="5"><?php esc_html_e('May', 'sakolawp'); ?></option>
												<option value="6"><?php esc_html_e('June', 'sakolawp'); ?></option>
												<option value="7"><?php esc_html_e('July', 'sakolawp'); ?></option>
												<option value="8"><?php esc_html_e('August', 'sakolawp'); ?></option>
												<option value="9"><?php esc_html_e('September', 'sakolawp'); ?></option>
												<option value="10"><?php esc_html_e('October', 'sakolawp'); ?></option>
												<option value="11"><?php esc_html_e('November', 'sakolawp'); ?></option>
												<option value="12"><?php esc_html_e('December', 'sakolawp'); ?></option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<a href="?page=sakolawp-settings" class="btn skwp-btn btn-sm btn-danger"><?php esc_html_e('Close', 'sakolawp'); ?></a>
								<button class="btn btn-primary skwp-btn" type="submit"><?php esc_html_e('Save', 'sakolawp'); ?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-backdrop fade show"></div>
		<?php }

		require_once 'partials/'.$this->plugin_name.'-admin-display.php';
	}

	public function manageClassAdminSettings() {
		global $wpdb;

		if(isset($_GET['delete'])) {
			$class_id = sanitize_text_field($_GET['delete']); ?>
			<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Delete Class', 'sakolawp'); ?></h5>
							<a href="?page=sakolawp-manage-class">
								<span aria-hidden="true">&times;</span>
							</a>
						</div>
						<form id="myForm" name="myform" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
							<input type="hidden" name="action" value="delete_classes_setting" />
							<input type="hidden" name="class_id" value="<?php echo esc_attr($class_id); ?>" />
							<div class="modal-body">
								<?php esc_html_e('Are you sure ?', 'sakolawp'); ?>
							</div>
							<div class="modal-footer">
								<a href="?page=sakolawp-manage-class" class="btn skwp-btn btn-sm btn-danger"><?php esc_html_e('Close', 'sakolawp'); ?></a>
								<button class="btn btn-primary skwp-btn " type="submit"><?php esc_html_e('Delete', 'sakolawp'); ?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-backdrop fade show"></div>
		<?php }

		if(isset($_GET['edit'])) {
			$class_id = sanitize_text_field($_GET['edit']); ?>
			<!-- Modal -->
			<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Edit Class', 'sakolawp'); ?></h5>
							<a href="?page=sakolawp-manage-class">
								<span aria-hidden="true">&times;</span>
							</a>
						</div>
						<?php 
							global $wpdb;
							$classes = $wpdb->get_results( "SELECT name, class_id FROM {$wpdb->prefix}sakolawp_class WHERE class_id =".$class_id."", OBJECT );
							foreach($classes as $class):
						?>
						<form id="myForm" name="myform" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
							<input type="hidden" name="action" value="edit_classes_setting" />
							<input type="hidden" name="class_id" value="<?php echo esc_attr($class_id); ?>" />
							<div class="modal-body">
								<div class="form-group skwp-row">
									<label class="skwp-column skwp-column-3 col-form-label" for=""> <?php esc_html_e('Class Name', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<input class="skwp-form-control" placeholder="<?php esc_attr_e('Class Name', 'sakolawp'); ?>" name="name" required="" type="text" value="<?php echo esc_attr($class->name); ?>">
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<a href="?page=sakolawp-manage-class" class="btn skwp-btn btn-sm btn-danger"><?php esc_html_e('Close', 'sakolawp'); ?></a>
								<button class="btn btn-primary skwp-btn" type="submit"><?php esc_html_e('Save changes', 'sakolawp'); ?></button>
							</div>
						</form>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="modal-backdrop fade show"></div>
		<?php }
		
		require_once 'partials/'.$this->plugin_name.'-manage-class.php';
		
	}

	public function manageSectionAdminSettings() {
		

		if(isset($_GET['delete'])) {
			$section_id = sanitize_text_field($_GET['delete']); ?>
			<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Delete Section', 'sakolawp'); ?></h5>
							<a href="?page=sakolawp-manage-section">
								<span aria-hidden="true">&times;</span>
							</a>
						</div>
						<form id="myForm" name="myform" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
							<input type="hidden" name="action" value="delete_section_setting" />
							<input type="hidden" name="section_id" value="<?php echo esc_attr($section_id); ?>" />
							<div class="modal-body">
								<?php esc_html_e('Are you sure ?', 'sakolawp'); ?>
							</div>
							<div class="modal-footer">
								<a href="?page=sakolawp-manage-section" class="btn skwp-btn btn-sm btn-danger"><?php esc_html_e('Close', 'sakolawp'); ?></a>
								<button class="btn btn-primary skwp-btn" type="submit"><?php esc_html_e('Delete', 'sakolawp'); ?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-backdrop fade show"></div>
		<?php }

		if(isset($_GET['edit'])) {
			$section_id = sanitize_text_field($_GET['edit']);
			$args = array(
				'role'    => 'teacher',
				'orderby' => 'user_nicename',
				'order'   => 'ASC'
			);
			$teachers = get_users( $args ); ?>
			<!-- Modal -->
			<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Edit Section', 'sakolawp'); ?></h5>
							<a href="?page=sakolawp-manage-section">
								<span aria-hidden="true">&times;</span>
							</a>
						</div>
						<?php 
							global $wpdb;
							$sections = $wpdb->get_results( "SELECT section_id, name, teacher_id, class_id FROM {$wpdb->prefix}sakolawp_section WHERE section_id =".$section_id."", OBJECT );
							foreach($sections as $section):
						?>
						<form id="myForm" name="myform" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
							<input type="hidden" name="action" value="edit_section_setting" />
							<input type="hidden" name="section_id" value="<?php echo esc_attr($section_id); ?>" />
							<div class="modal-body">
								<div class="form-group skwp-row">
									<label class="skwp-column skwp-column-3 col-form-label" for=""> <?php esc_html_e('Section Name', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<input class="skwp-form-control" placeholder="<?php esc_attr_e('Section Name', 'sakolawp'); ?>" name="name" required="" type="text" value="<?php echo esc_attr($section->name) ?>">
										</div>
									</div>
								</div>
								<div class="form-group skwp-row">
									<label class="col-form-label skwp-column skwp-column-3" for=""> <?php esc_html_e('Class', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<select class="skwp-form-control" name="class_id">
												<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
												<?php 
												global $wpdb;
												$classes = $wpdb->get_results( "SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", OBJECT );
												foreach($classes as $class):
												?>
												<option value="<?php echo esc_attr($class->class_id);?>" <?php if($class->class_id == $section->class_id){ echo "selected"; } ?>><?php echo esc_html($class->name);?></option>
											 <?php endforeach;?>
											 </select>
										</div>
									</div>
								</div>
								<div class="form-group skwp-row">
									<label class="col-form-label skwp-column skwp-column-3" for=""> <?php esc_html_e('Edit Section', 'sakolawp'); ?>Teacher</label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<select class="skwp-form-control" name="teacher_id">
												<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
												<?php 
								
												foreach($teachers as $teacher):
												?>
												<option value="<?php echo esc_attr($teacher->ID);?>" <?php if($teacher->ID == $section->teacher_id){ echo "selected"; } ?>><?php echo esc_html($teacher->display_name);?></option>
											 <?php endforeach;?>
											 </select>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<a href="?page=sakolawp-manage-section" class="btn skwp-btn btn-sm btn-danger"><?php esc_html_e('Close', 'sakolawp'); ?></a>
								<button class="btn btn-primary skwp-btn" type="submit"><?php esc_html_e('Save changes', 'sakolawp'); ?></button>
							</div>
						</form>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="modal-backdrop fade show"></div>
		<?php }

		require_once 'partials/'.$this->plugin_name.'-manage-section.php';
	}

	public function manageSubjectAdminSettings() {
		

		if(isset($_GET['delete'])) {
			$subject_id = sanitize_text_field($_GET['delete']); ?>
			<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Delete Subject', 'sakolawp'); ?></h5>
							<a href="?page=sakolawp-manage-section">
								<span aria-hidden="true">&times;</span>
							</a>
						</div>
						<form id="myForm" name="myform" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
							<input type="hidden" name="action" value="delete_subject_setting" />
							<input type="hidden" name="subject_id" value="<?php echo esc_attr($subject_id); ?>" />
							<div class="modal-body">
								<?php esc_html_e('Are you sure ?', 'sakolawp'); ?>
							</div>
							<div class="modal-footer">
								<a href="?page=sakolawp-manage-section" class="btn skwp-btn btn-sm btn-danger"><?php esc_html_e('Close', 'sakolawp'); ?></a>
								<button class="btn btn-primary skwp-btn" type="submit"><?php esc_html_e('Delete', 'sakolawp'); ?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-backdrop fade show"></div>
		<?php }

		if(isset($_GET['edit'])) {
			$subject_id = sanitize_text_field($_GET['edit']);
			$args = array(
				'role'    => 'teacher',
				'orderby' => 'user_nicename',
				'order'   => 'ASC'
			);
			$teachers = get_users( $args ); ?>
			<!-- Modal -->
			<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Edit Subject', 'sakolawp'); ?></h5>
							<a href="?page=sakolawp-manage-subject">
								<span aria-hidden="true">&times;</span>
							</a>
						</div>
						<?php 
							global $wpdb;
							$subjects = $wpdb->get_results( "SELECT name, class_id, teacher_id, total_lab FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id =".$subject_id."", OBJECT );
							foreach($subjects as $subject):
						?>
						<form id="myForm" name="myform" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
							<input type="hidden" name="action" value="edit_subject_setting" />
							<input type="hidden" name="subject_id" id="subject_id" value="<?php echo esc_attr($subject_id); ?>" />
							<div class="modal-body">
								<div class="form-group skwp-row">
									<label class="skwp-column skwp-column-3 col-form-label" for=""> <?php esc_html_e('Subject Name', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<input class="skwp-form-control" placeholder="<?php esc_attr_e('Subject Name', 'sakolawp'); ?>" name="name" required="" type="text" value="<?php echo esc_attr($subject->name) ?>">
										</div>
									</div>
								</div>
								<div class="form-group skwp-row">
									<label class="col-form-label skwp-column skwp-column-3" for=""> <?php esc_html_e('Class', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<select class="skwp-form-control first" name="class_id" id="class_holder">
												<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
												<?php 
												global $wpdb;
												$classes = $wpdb->get_results( "SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", OBJECT );
												foreach($classes as $class):
												?>
												<option value="<?php echo esc_attr($class->class_id);?>" <?php if($class->class_id == $subject->class_id){ echo "selected"; } ?>><?php echo esc_html($class->name);?></option>
											 <?php endforeach;?>
											 </select>
										</div>
									</div>
								</div>
								<div class="form-group skwp-row">
									<label class="col-form-label skwp-column skwp-column-3" for=""> <?php esc_html_e('Section', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<select class="skwp-form-control" name="section_id" id="section_holder">
												<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
											 </select>
										</div>
									</div>
								</div>
								<div class="form-group skwp-row">
									<label class="col-form-label skwp-column skwp-column-3" for=""> <?php esc_html_e('Teacher', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<select class="skwp-form-control" name="teacher_id">
												<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
												<?php 
								
												foreach($teachers as $teacher):
												?>
												<option value="<?php echo esc_attr($teacher->ID);?>" <?php if($teacher->ID == $subject->teacher_id){ echo "selected"; } ?>><?php echo esc_html($teacher->display_name);?></option>
											 <?php endforeach;?>
											 </select>
										</div>
									</div>
								</div>
								<div class="form-group skwp-row">
									<label class="skwp-column skwp-column-3 col-form-label" for=""> <?php esc_html_e('Total Lab', 'sakolawp'); ?></label>
									<div class="skwp-column skwp-column-2of3">
										<div class="input-group">
											<input class="skwp-form-control" name="total_lab" type="number" min="1" max="10" value="<?php echo esc_attr($subject->total_lab) ?>">
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<a href="?page=sakolawp-manage-subject" class="btn skwp-btn btn-sm btn-danger"><?php esc_html_e('Close', 'sakolawp'); ?></a>
								<button class="btn btn-primary skwp-btn" type="submit"><?php esc_html_e('Save changes', 'sakolawp'); ?></button>
							</div>
						</form>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="modal-backdrop fade show"></div>
		<?php }

		require_once 'partials/'.$this->plugin_name.'-manage-subject.php';
	}

	public function manageAttendanceAdminSettings() {
		
		require_once 'partials/'.$this->plugin_name.'-manage-attendance.php';
	}

	public function manageReportAttendanceAdminSettings() {
		
		require_once 'partials/'.$this->plugin_name.'-manage-report-attendance.php';
	}

	public function manageRoutineAdminSettings() {
		

		if(isset($_GET['edit'])) {
			$class_routine_id = sanitize_text_field($_GET['edit']);
			$args = array(
				'role'    => 'teacher',
				'orderby' => 'user_nicename',
				'order'   => 'ASC'
			);
			$teachers = get_users( $args ); ?>
			<!-- Modal -->
			<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Class Routine Edit', 'sakolawp'); ?></h5>
							<a href="?page=sakolawp-manage-routine">
								<span aria-hidden="true">&times;</span>
							</a>
						</div>
						<?php 
							global $wpdb;
							$edit_data = $wpdb->get_results( "SELECT class_id, section_id, subject_id, day, time_start, time_start_min, time_end, time_end_min FROM {$wpdb->prefix}sakolawp_class_routine WHERE class_routine_id =".$class_routine_id."", ARRAY_A );
							foreach($edit_data as $routine):
						?>
						<form id="myForm" name="myform" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
							<input type="hidden" name="action" value="sakolawp_edit_routine" />
							<input type="hidden" name="class_routine_id" id="class_routine_id" value="<?php echo esc_attr($class_routine_id); ?>" />
							<div class="modal-body skwp-clearfix">
								<div class="form-group skwp-row">
									<div class="skwp-column skwp-column-1">
										<label  for=""> <?php echo esc_html__('Class', 'sakolawp');?></label>
										<div class="input-group">
											<div class="input-group-addon">
												<i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
											</div>
											<select class="skwp-form-control" name="class_id" disabled>
												<option value=""><?php echo esc_html__('Select', 'sakolawp');?></option>
												<?php $cl = $wpdb->get_results( "SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", ARRAY_A );
												foreach($cl as $row9): ?>
												<option value="<?php echo esc_attr($row9['class_id']); ?>" <?php if($routine['class_id']==$row9['class_id'])echo esc_attr( 'selected' );?>><?php echo esc_html($row9['name']); ?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								</div>

								<div class="form-group skwp-row">
									<div class="skwp-column skwp-column-1">
										<label for=""> <?php echo esc_html__('Section', 'sakolawp');?></label>
										<div class="input-group">
											<div class="input-group-addon">
												<i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
											</div>
											<select class="skwp-form-control" name="section_id" disabled>
												<?php 
												$class_id = $routine['class_id'];
												$sec =  $wpdb->get_results( "SELECT section_id, name FROM {$wpdb->prefix}sakolawp_section WHERE class_id = $class_id", ARRAY_A );
												foreach ($sec as $row3): ?>
												<option value="<?php echo esc_attr($row3['section_id']); ?>" <?php if($routine['section_id'] == $row3['section_id'])echo esc_attr( 'selected' );?>><?php echo esc_html($row3['name']); ?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								</div>

								<div class="form-group skwp-row">
									<div class="skwp-column skwp-column-1">
										<label for=""> <?php echo esc_html__('Subject', 'sakolawp');?></label>
										<div class="input-group">
											<div class="input-group-addon">
												<i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
											</div>
											<select class="skwp-form-control" name="subject_id" disabled>
												<?php 
												$subject_id = $routine['subject_id'];
												$sub = $wpdb->get_results( "SELECT subject_id, name FROM {$wpdb->prefix}sakolawp_subject WHERE class_id = $subject_id", ARRAY_A );
												foreach($sub as $row4): ?>
												<option value="<?php echo esc_attr($row4['subject_id']); ?>" <?php if($routine['subject_id'] == $row4['subject_id'])echo esc_attr( 'selected' );?>><?php echo esc_html($row4['name']);?></option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
								</div>
								
								<div class="form-group skwp-row">
									<div class="skwp-column skwp-column-1">
										<label for=""> <?php echo esc_html__('Day', 'sakolawp');?></label>
										<div class="input-group">
											<div class="input-group-addon">
												<i class="picons-thin-icon-thin-0024_calendar_month_day_planner_events"></i>
											</div>
											<select name="day" class="skwp-form-control" required="">
												<option value=""><?php echo esc_html__('Select', 'sakolawp');?></option>
												<?php
												$days = get_option('sakolawp_routine');
												if(!empty($days)) {
													$days = $days;
												} else {
													$days = 1;
												}
												if($days == 1):?>
													<option value="Sunday" <?php if($routine['day']== "Sunday") echo "selected";?>><?php echo esc_html__('Sunday', 'sakolawp');?></option>
												<?php endif;?>
												<option value="Monday" <?php if($routine['day']== "Monday") echo "selected";?>><?php echo esc_html__('Monday', 'sakolawp');?></option>
												<option value="Tuesday" <?php if($routine['day']== "Tuesday") echo "selected";?>><?php echo esc_html__('Tuesday', 'sakolawp');?></option>
												<option value="Wednesday" <?php if($routine['day']== "Wednesday") echo "selected";?>><?php echo esc_html__('Wednesday', 'sakolawp');?></option>
												<option value="Thursday" <?php if($routine['day']== "Thursday") echo "selected";?>><?php echo esc_html__('Thursday', 'sakolawp');?></option>
												<option value="Friday" <?php if($routine['day']== "Friday") echo "selected";?>><?php echo esc_html__('Friday', 'sakolawp');?></option>
												<?php if($days == 1):?>
													<option value="Saturday" <?php if($routine['day']== "Saturday") echo "selected";?>><?php echo esc_html__('Saturday', 'sakolawp');?></option>
												<?php endif;?>
											</select>
										</div>
									</div>
								</div>

								<div class="form-group skwp-row">
									<label class="skwp-column skwp-column-1" for=""> <?php echo esc_html__('Time Start', 'sakolawp');?></label>
									<div class="skwp-column skwp-column-3">
										<?php 
										if($routine['time_start'] < 13)
										{
											$time_start     =   $routine['time_start'];
											$time_start_min =   $routine['time_start_min'];
											$starting_ampm  =   1;
										}
										else if($routine['time_start'] > 12)
										{
											$time_start     =   $routine['time_start'] - 12;
											$time_start_min =   $routine['time_start_min'];
											$starting_ampm  =   2;
										}
										?>
										<div class="input-group">
											<div class="input-group-addon">
												<i class="picons-thin-icon-thin-0029_time_watch_clock_wall"></i>
											</div>
											<select name="time_start" class="skwp-form-control" required>
												<option value=""><?php echo esc_html__('Hour', 'sakolawp');?></option>
												<?php for($i = 0; $i <= 12 ; $i++):?>
													<option value="<?php echo esc_attr($i);?>" <?php if($i ==$time_start)echo "selected";?>><?php echo esc_html($i);?></option>
												<?php endfor;?>
											</select>
										</div>
									</div>

									<div class="skwp-column skwp-column-3">
										<div class="input-group">
											<select name="time_start_min" class="skwp-form-control" required>
												<option value=""><?php echo esc_html__('Minutes', 'sakolawp');?></option>
												<?php for($i = 0; $i <= 11 ; $i++):?>
												<option value="<?php $n = $i * 5; if($n < 10) echo '0'.esc_attr($n); else echo esc_attr($n);?>" <?php if (($i * 5) == $time_start_min) echo esc_attr( 'selected' );?>><?php $n = $i * 5; if($n < 10) echo '0'.esc_html($n); else echo esc_html($n);?></option>
												<?php endfor;?>
											</select>
										</div>
									</div>

									<div class="skwp-column skwp-column-3">
										<div class="input-group">
											<select class="skwp-form-control" name="starting_ampm" required>
												<option value="1" <?php if($starting_ampm == '1') echo "selected";?>><?php echo esc_html('AM'); ?></option>
												<option value="2" <?php if($starting_ampm == '2') echo "selected";?>><?php echo esc_html('PM'); ?></option>
											</select>
										</div>
									</div>
								</div>
							  
								<div class="form-group skwp-row">
									<label class="skwp-column skwp-column-1" for=""> <?php echo esc_html__('Time End', 'sakolawp');?></label>
									<div class="skwp-column skwp-column-3">
										<?php 
										if($routine['time_end'] < 13)
										{
											$time_end		=	$routine['time_end'];
											$time_end_min   =   $routine['time_end_min'];
											$ending_ampm	=	1;
										}
										else if($routine['time_end'] > 12)
										{
											$time_end		=	$routine['time_end'] - 12;
											$time_end_min   =   $routine['time_end_min'];
											$ending_ampm	=	2;
										}	
										?>
										<div class="input-group">
											<div class="input-group-addon">
												<i class="picons-thin-icon-thin-0029_time_watch_clock_wall"></i>
											</div>
											<select name="time_end" class="skwp-form-control" required>
												<option value=""><?php echo esc_html__('hour', 'sakolawp');?></option>
												<?php for($i = 0; $i <= 12 ; $i++):?>
													<option value="<?php echo esc_attr($i);?>" <?php if($i ==$time_end) echo "selected";?>><?php echo esc_html($i);?></option>
												<?php endfor;?>
											</select>
										</div>
									</div>
									<div class="skwp-column skwp-column-3">
										<div class="input-group">
											<select name="time_end_min" class="skwp-form-control" required>
												<option value=""><?php echo esc_html__('Minutes', 'sakolawp'); ?></option>
												<?php for($i = 0; $i <= 11 ; $i++):?>
												<option value="<?php $n = $i * 5; if($n < 10) echo '0'.esc_attr($n); else echo esc_attr($n);?>" <?php if (($i * 5) == $time_end_min) echo 'selected';?>><?php $n = $i * 5; if($n < 10) echo '0'.esc_html($n); else echo esc_html($n);?></option>
												<?php endfor;?>
											</select>
										</div>
									</div>
									<div class="skwp-column skwp-column-3">
										<div class="input-group">
											<select class="skwp-form-control" required="" name="ending_ampm"> 
												<option value="1" <?php if($ending_ampm	==	'1') echo "selected";?>><?php echo esc_html('AM'); ?></option>
												<option value="2" <?php if($ending_ampm	==	'2') echo "selected";?>><?php echo esc_html('PM'); ?></option>
										  	</select>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<a href="?page=sakolawp-manage-routine" class="btn skwp-btn btn-sm btn-danger"><?php esc_html_e('Close', 'sakolawp'); ?></a>
								<button class="btn skwp-btn btn-primary" type="submit"><?php esc_html_e('Save changes', 'sakolawp'); ?></button>
							</div>
						</form>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<div class="modal-backdrop fade show"></div>
		<?php }

		if(isset($_GET['delete'])) {
			$class_routine_id = sanitize_text_field($_GET['delete']); ?>
			<div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: block;">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Delete Subject', 'sakolawp'); ?></h5>
							<a href="?page=sakolawp-manage-routine">
								<span aria-hidden="true">&times;</span>
							</a>
						</div>
						<form id="myForm" name="myform" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST">
							<input type="hidden" name="action" value="delete_routine_setting" />
							<input type="hidden" name="class_routine_id" value="<?php echo esc_attr($class_routine_id); ?>" />
							<div class="modal-body">
								<?php esc_html_e('Are you sure ?', 'sakolawp'); ?>
							</div>
							<div class="modal-footer">
								<a href="?page=sakolawp-manage-routine" class="btn skwp-btn btn-sm btn-danger"><?php esc_html_e('Close', 'sakolawp'); ?></a>
								<button class="btn btn-danger skwp-btn" type="submit"><?php esc_html_e('Delete', 'sakolawp'); ?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-backdrop fade show"></div>
		<?php }

		require_once 'partials/'.$this->plugin_name.'-manage-routine.php';
	}

	public function studentAreaAdminSettings() {
		
		require_once 'partials/'.$this->plugin_name.'-student-area.php';
	}

	public function assignStudentAdminSettings() {
		
		require_once 'partials/'.$this->plugin_name.'-assign-student.php';
	}

	public function homeworkAdminSettings() {
				
		require_once 'partials/'.$this->plugin_name.'-homework.php';
	}

	public function examAdminSettings() {

		require_once 'partials/'.$this->plugin_name.'-exam.php';
	}

	public function pluginNameSettingsMessages($error_message){
		switch ($error_message) {
			case '1':
				$message = __( 'There was an error adding this setting. Please try again.  If this persists, shoot us an email.', 'sakolawp' );
				$err_code = esc_attr( 'school_name' );
				$setting_field = 'school_name';
				break;
		}
		$type = 'error';
		add_settings_error(
			$setting_field,
			$err_code,
			$message,
			$type
		);
	}

	public function registerAndBuildFields() {
		 /**
		* First, we add_settings_section. This is necessary since all future settings must belong to one.
		* Second, add_settings_field
		* Third, register_setting
		*/     
		add_settings_section(
			// ID used to identify this section and with which to register options
			'sakolawp_general_section', 
			// Title to be displayed on the administration page
			'',  
			// Callback used to render the description of the section
			 array( $this, 'plugin_name_display_general_account' ),    
			// Page on which to add this section of options
			'sakolawp_general_settings'                   
		);
		unset($opt1);
		$opt1 = array (
			'type'      => 'input',
			'subtype'   => 'text',
			'id'    => 'school_name',
			'name'      => 'school_name',
			'required' => 'true',
			'get_options_list' => '',
			'value_type'=>'normal',
			'wp_data' => 'option'
		);

		add_settings_field(
			'school_name',
			esc_html__( 'School Name', 'sakolawp' ),
			array( $this, 'plugin_name_render_settings_field' ),
			'sakolawp_general_settings',
			'sakolawp_general_section',
			$opt1
		);

		add_settings_field( 
			'running_year', 
			esc_html__( 'Running Year', 'sakolawp' ), 
			array( $this, 'Dropdown_select_field_render' ), 
			'sakolawp_general_settings', 
			'sakolawp_general_section'
		);
		
		add_settings_field( 
			'sakolawp_routine', 
			esc_html__( 'Use Saturday', 'sakolawp' ), 
			array( $this, 'Sakolawp_routine_radio_button' ), 
			'sakolawp_general_settings', 
			'sakolawp_general_section'
		);

		register_setting('sakolawp_general_settings', 'school_name');
		register_setting('sakolawp_general_settings','running_year');
		register_setting('sakolawp_general_settings','sakolawp_routine');
	}

	public function plugin_name_display_general_account() {
		
	}

	public function Dropdown_settings_section_callback(  ) { 
		echo __( 'Running Years', 'sakolawp' );
	}

	public function Dropdown_select_field_render($args) {
		$options = get_option('running_year');
		//$items = array("Red", "Green", "Blue", "Orange", "White", "Violet", "Yellow");
		echo "<select id='running_year' name='running_year'>";
		for($i = 0; $i < 10; $i++):
			$selected = ($options == (2016+$i).'-'.(2016+$i+1)) ? 'selected="selected"' : '';
			echo "<option value=".(2016+$i).'-'.(2016+$i+1)." $selected>".(2016+$i).'-'.(2016+$i+1)."</option>";
		endfor;
		echo "</select>";
	}

	public function Sakolawp_routine_radio_button($args) {
		$options = get_option('sakolawp_routine'); ?>
		<label class="form-check-label"><input class="form-check-input" name="sakolawp_routine" type="radio" value="1" <?php if($options == 1) echo 'checked';?>><?php esc_html_e('Yes', 'sakolawp'); ?></label>
		<label class="form-check-label"><input class="form-check-input" name="sakolawp_routine" type="radio" value="2" <?php if($options == 2) echo 'checked';?> style="margin-left:5px;"><?php esc_html_e('No', 'sakolawp'); ?></label>
		<?php
	}

	public function plugin_name_render_settings_field($args) { 
	   if($args['wp_data'] == 'option'){
			$wp_data_value = get_option($args['name']);
		} elseif($args['wp_data'] == 'post_meta'){
			$wp_data_value = get_post_meta($args['post_id'], $args['name'], true );
		}

		switch ($args['type']) {

			case 'input':
				$value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
				if($args['subtype'] != 'checkbox'){
					$prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">'.$args['prepend_value'].'</span>' : '';
					$prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';
					$step = (isset($args['step'])) ? 'step="'.$args['step'].'"' : '';
					$min = (isset($args['min'])) ? 'min="'.$args['min'].'"' : '';
					$max = (isset($args['max'])) ? 'max="'.$args['max'].'"' : '';
					if(isset($args['disabled'])){
						// hide the actual input bc if it was just a disabled input the informaiton saved in the database would be wrong - bc it would pass empty values and wipe the actual information
						echo wp_specialchars_decode( $prependStart ) . '<input type="' . esc_attr( $args['subtype'] ) . '" id="' . esc_attr( $args['id'] ) . '_disabled" '. esc_attr( $step ).' '. esc_attr( $max ) .' '. esc_attr( $min ) .' name="'. esc_attr( $args['name'] ) .'_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="'. esc_attr( $args['id'] ).'" '. esc_attr( $step ) .' '. esc_attr( $max ) .' '. esc_attr( $min ) .' name="'. esc_attr( $args['name'] ) .'" size="40" value="' . esc_attr($value) . '" />' . wp_specialchars_decode( $prependEnd );
					} else {
						echo wp_specialchars_decode( $prependStart ) . '<input type="' . esc_attr( $args['subtype'] ) . '" id="'. esc_attr( $args['id'] ) .'" "'. esc_attr( $args['required'] ) .'" '. esc_attr( $step ).' '. esc_attr( $max ) .' '. esc_attr( $min ) .' name="'. esc_attr( $args['name'] ) .'" size="40" value="' . esc_attr($value) . '" />' . wp_specialchars_decode( $prependEnd );
					}

				} else {
					$checked = ($value) ? 'checked' : '';
					echo '<input type="' . esc_attr( $args['subtype'] ) . '" id="'. esc_attr( $args['id'] ) .'" "'. esc_attr( $args['required'] ) .'" name="'. esc_attr( $args['name'] ) .'" size="40" value="1" '. esc_attr( $checked ) .' />';
				}
				break;
			default:
				# code...
				break;
		}
	}

	function sakolawp_select_section_f() {
		// Implement ajax function here
		global $wpdb;
		$class_id = sanitize_text_field($_REQUEST['class_id']);
		$sections = $wpdb->get_results( "SELECT section_id, name FROM {$wpdb->prefix}sakolawp_section WHERE class_id = '$class_id'", ARRAY_A );
		echo '<option value="">'.esc_html__('Select', 'sakolawp').'</option>';
		foreach ($sections as $row) 
		{
			echo '<option value="' . esc_attr($row['section_id']) . '">' . esc_html($row['name']) . '</option>';
		}

		exit();
	}

	function sakolawp_select_subject_f() {
		// Implement ajax function here
		global $wpdb;
		$section_id = sanitize_text_field($_REQUEST['section_id']);
		$subjects = $wpdb->get_results( "SELECT subject_id, name FROM {$wpdb->prefix}sakolawp_subject WHERE section_id = '$section_id'", ARRAY_A );
		echo '<option value="">'.esc_html__('Select', 'sakolawp').'</option>';
		foreach ($subjects as $row) 
		{
			echo '<option value="' . esc_attr($row['subject_id']) . '">' . esc_html($row['name']) . '</option>';
		}

		exit();
	}

	function sakolawp_select_section_first_f() {
		// Implement ajax function here
		global $wpdb;
		$class_id = sanitize_text_field($_REQUEST['class_id']);
		$subject_id = sanitize_text_field($_REQUEST['subject_id']);

		$section_id = $wpdb->get_results( "SELECT section_id FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = '$subject_id'", ARRAY_A );

		$sections = $wpdb->get_results( "SELECT section_id, name FROM {$wpdb->prefix}sakolawp_section WHERE class_id = '$class_id'", ARRAY_A );
		echo '<option value="">'.esc_html__('Select', 'sakolawp').'</option>';
		foreach ($sections as $row) 
		{ ?>
			<option value="<?php echo esc_attr($row['section_id']) ?>" <?php if ($row['section_id'] == $section_id[0]['section_id']) {echo "selected";} ?>><?php echo esc_html($row['name']); ?></option>
		<?php }
	}

}