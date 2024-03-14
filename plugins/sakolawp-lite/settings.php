<?php

// Sakolawp Default Wrapper Open
if (!function_exists('sakolawp_output_content_wrapper')) {
	function sakolawp_output_content_wrapper()
	{
		require_once plugin_dir_path(__FILE__) . 'template/wrapper-start.php';
	}
}

// Sakolawp Default Wrapper Close
if (!function_exists('sakolawp_output_content_wrapper_end')) {
	function sakolawp_output_content_wrapper_end()
	{
		require_once plugin_dir_path(__FILE__) . 'template/wrapper-end.php';
	}
}

// Sakolawp User Gender Information
add_action('sakolawp_before_main_content', 'sakolawp_output_content_wrapper');
add_action('sakolawp_after_main_content', 'sakolawp_output_content_wrapper_end');

add_action('show_user_profile', 'sakolawp_show_user_gender_fields');
add_action('edit_user_profile', 'sakolawp_show_user_gender_fields');

function sakolawp_show_user_gender_fields($user)
{ ?>
	<h3><?php echo esc_html__('User Gender', 'sakolawp'); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="gender"><?php echo esc_html__('Gender', 'sakolawp'); ?></label></th>
			<td>
				<select name="gender" id="gender">
					<option value="<?php echo esc_attr('Male'); ?>" <?php selected('Male', get_the_author_meta('gender', $user->ID)); ?>><?php echo esc_html__('Male', 'sakolawp'); ?></option>
					<option value="<?php echo esc_attr('Female'); ?>" <?php selected('Female', get_the_author_meta('gender', $user->ID)); ?>><?php echo esc_html__('Female', 'sakolawp'); ?></option>
				</select>
			</td>
		</tr>
	</table>
	<?php }

add_action('personal_options_update', 'sakolawp_save_user_gender_fields');
add_action('edit_user_profile_update', 'sakolawp_save_user_gender_fields');

function sakolawp_save_user_gender_fields($user_id)
{
	if (!current_user_can('edit_user', $user_id))
		return false;

	update_user_meta($user_id, 'gender', sanitize_text_field($_POST['gender']));
}

// Sakolawp Student Related Parent
add_action('show_user_profile', 'sakolawp_related_parent_student');
add_action('edit_user_profile', 'sakolawp_related_parent_student');
function sakolawp_related_parent_student($user)
{
	$userrole = $user;
	if (in_array('parent', (array) $userrole->roles)) { ?>
		<h3><?php echo esc_html__('Related Student', 'sakolawp'); ?></h3>
		<table class="form-table">
			<tr>
				<th><label for="student"><?php echo esc_html__('Student', 'sakolawp'); ?></label></th>
				<td>
					<select name="student" id="student">
						<?php $std_args = array(
							'role'    => 'student',
							'orderby' => 'user_nicename',
							'order'   => 'ASC'
						);
						$students = get_users($std_args);
						foreach ($students as $stdn) : ?>
							<option value="<?php echo esc_attr($stdn->ID); ?>"><?php echo esc_html($stdn->display_name); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>
	<?php }
}

add_action('personal_options_update', 'sakolawp_save_related_parent_student');
add_action('edit_user_profile_update', 'sakolawp_save_related_parent_student');

function sakolawp_save_related_parent_student($user_id)
{
	if (!current_user_can('edit_user', $user_id))
		return false;

	$user_meta = get_userdata($user_id);

	$user_roles = $user_meta->roles;
	if (in_array('parent', (array) $user_roles)) {
		update_user_meta($user_id, 'related_student', sanitize_text_field($_POST['student']));
	}
}

// Sakolawp User Approval
add_action('show_user_profile', 'sakolawp_show_user_approval_fields');
add_action('edit_user_profile', 'sakolawp_show_user_approval_fields');

function sakolawp_show_user_approval_fields($user)
{ ?>
	<h3><?php echo esc_html__('User Status', 'sakolawp'); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="user_active"><?php echo esc_html__('Status', 'sakolawp'); ?></label></th>
			<td>
				<select name="user_active" id="user_active">
					<option value="0" <?php selected('0', get_the_author_meta('user_active', $user->ID)); ?>><?php echo esc_html__('User Not Active', 'sakolawp'); ?></option>
					<option value="1" <?php selected('1', get_the_author_meta('user_active', $user->ID)); ?>><?php echo esc_html__('User Active', 'sakolawp'); ?></option>
				</select>
			</td>
		</tr>
	</table>
<?php }

add_action('personal_options_update', 'sakolawp_save_user_approval_fields');
add_action('edit_user_profile_update', 'sakolawp_save_user_approval_fields');

function sakolawp_save_user_approval_fields($user_id)
{
	if (!current_user_can('edit_user', $user_id))
		return false;

	update_user_meta($user_id, 'user_active', sanitize_text_field($_POST['user_active']));
}

/* Template Redirect */
add_filter('template_include', 'sakolawp_redirect_to_template', 99);

function sakolawp_redirect_to_template($new_template)
{

	$user = wp_get_current_user();
	$user_id = get_current_user_id();

	$user_active = get_user_meta($user_id, 'user_active', true);

	// admin redirects
	if (in_array('administrator', (array) $user->roles)) {
		global $wp;
		$wp->is_404 = false;
		status_header('200');
		require_once(plugin_dir_path(__FILE__) . 'redirects/admin-redirects.php');
	}

	// teacher redirects
	if (in_array('teacher', (array) $user->roles)) {
		add_filter('show_admin_bar', '__return_false');
		remove_action('wp_head', '_admin_bar_bump_cb');

		global $wp;
		$wp->is_404 = false;
		status_header('200');
		require_once(plugin_dir_path(__FILE__) . 'redirects/teacher-redirects.php');
	}

	// student redirects
	if (in_array('student', (array) $user->roles)) {
		add_filter('show_admin_bar', '__return_false');
		remove_action('wp_head', '_admin_bar_bump_cb');

		global $wp;
		$wp->is_404 = false;
		status_header('200');
		require_once(plugin_dir_path(__FILE__) . 'redirects/student-redirects.php');
	}

	// parent redirects
	if (in_array('parent', (array) $user->roles)) {
		add_filter('show_admin_bar', '__return_false');
		remove_action('wp_head', '_admin_bar_bump_cb');

		global $wp;
		$wp->is_404 = false;
		status_header('200');
		require_once(plugin_dir_path(__FILE__) . 'redirects/parent-redirects.php');
	}
	return $new_template;
}

function sakolawp_modify_page_title($title_parts)
{
	$user = wp_get_current_user();

	// admin rename pages
	if (in_array('administrator', (array) $user->roles)) {
		global $wp;
		$wp->is_404 = false;
		status_header('200');
		require_once(plugin_dir_path(__FILE__) . 'rename/admin-rename.php');
	}

	// teacher rename pages
	if (in_array('teacher', (array) $user->roles)) {
		global $wp;
		$wp->is_404 = false;
		status_header('200');
		require_once(plugin_dir_path(__FILE__) . 'rename/teacher-rename.php');
	}

	// student rename pages
	if (in_array('student', (array) $user->roles)) {
		global $wp;
		$wp->is_404 = false;
		status_header('200');
		require_once(plugin_dir_path(__FILE__) . 'rename/student-rename.php');
	}

	// parent rename pages
	if (in_array('parent', (array) $user->roles)) {
		global $wp;
		$wp->is_404 = false;
		status_header('200');
		require_once(plugin_dir_path(__FILE__) . 'rename/parent-rename.php');
	}
	return $title_parts;
}
add_filter('document_title_parts', 'sakolawp_modify_page_title');

// Add custom column to student user table
function sakolawp_student_roles_custom_column( $column ) {
	$column['class'] = esc_html__('Class', 'sakolawp');
	$column['status'] = esc_html__('Status', 'sakolawp');
	return $column;
}
add_filter( 'manage_users_columns', 'sakolawp_student_roles_custom_column' );

function sakolawp_student_roles_custom_column_row( $val, $column_name, $user_id ) {

		$user_meta = get_userdata($user_id);
		$user_roles = $user_meta->roles;

			switch ($column_name) {
				case 'class' :
					global $wpdb;

					if($user_roles[0] == 'student') {
						$enroll = $wpdb->get_row( "SELECT class_id, section_id FROM {$wpdb->prefix}sakolawp_enroll WHERE student_id = $user_id");

						if(!empty($enroll)) {
							$class_id = $enroll->class_id;
							$section_id = $enroll->section_id;

							$class = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id");
							$section = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $section_id");

							if(!empty($class) && !empty($section)) {
								$classes_name = esc_html($class->name) . esc_html__(' - ', 'sakolawp') . esc_html($section->name);
							}
							else {
								$classes_name = esc_html__('Not Assign Yet.', 'sakolawp');
							}
						}
						else {
							$classes_name = esc_html__('Not Assign Yet.', 'sakolawp');
						}
					} // if student
					if($user_roles[0] == 'administrator') {
						$classes_name = esc_html__('-admin-', 'sakolawp');
					}
					if($user_roles[0] == 'teacher') {
						$classes_name = esc_html__('-teacher-', 'sakolawp');
					}
					if($user_roles[0] == 'parent') {
						$classes_name = esc_html__('-parent-', 'sakolawp');
					}
					return $classes_name;

				case 'status' :
					global $wpdb;
					$user_active = get_user_meta($user_id, 'user_active', true);

					if(!empty($user_active)) {
						if($user_roles[0] == 'administrator') {
							$status_col = esc_html__('Administrator', 'sakolawp');
						}
						else {
							$status_col = esc_html__('User Active', 'sakolawp');
						}
					}
					else {
						if($user_roles[0] == 'administrator') {
							$status_col = esc_html__('Administrator', 'sakolawp');
						}
						else {
							$status_col = esc_html__('User Not Active', 'sakolawp');
						}
					}
					return $status_col;
				default:
			}
			return $val;
}
add_filter( 'manage_users_custom_column', 'sakolawp_student_roles_custom_column_row', 10, 3 );

// Sakolawp Student Class
add_action('show_user_profile', 'sakolawp_student_class_select');
add_action('edit_user_profile', 'sakolawp_student_class_select');

function sakolawp_student_class_select($user)
{

	global $wpdb;
	$student_id = $user->data->ID;
	$enroll = $wpdb->get_row( "SELECT class_id, section_id FROM {$wpdb->prefix}sakolawp_enroll WHERE student_id = $student_id");

	$class_id_tgt = $enroll->class_id;
	$section_id_tgt = $enroll->section_id; ?>

	<h3><?php echo esc_html__('Re-Assign Class', 'sakolawp'); ?></h3>

	<div class="info">
		<?php echo esc_html__('Please note that if you change the student classes after assign them to a class, it will make the content that this student already have disappear.', 'sakolawp'); ?>
	</div>
	
	<table class="form-table">
		<tr>
			<th><label for="user_active"><?php echo esc_html__('Class', 'sakolawp'); ?></label></th>
			<td>
				<select class="skwp-form-control" name="class_id" id="class_holder">
					<option value=""><?php esc_html_e( 'Select', 'sakolawp' ); ?></option>
					<?php 
					$classes = $wpdb->get_results( "SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", OBJECT );
					foreach($classes as $class):
					?>
					<option value="<?php echo esc_attr($class->class_id); ?>" <?php if($class->class_id == $class_id_tgt) { ?> selected <?php } ?>><?php echo esc_html( $class->name ); ?></option>
					<?php endforeach;?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="user_active"><?php echo esc_html__('Section', 'sakolawp'); ?></label></th>
			<td>
				<select class="skwp-form-control" name="section_id" id="section_holder">
					<option value=""><?php esc_html_e( 'Select', 'sakolawp' ); ?></option>
					<?php 
					global $wpdb;
					$sections = $wpdb->get_results( "SELECT section_id, name FROM {$wpdb->prefix}sakolawp_section", OBJECT );
					foreach($sections as $section):
					?>
					<option value="<?php echo esc_attr($section->section_id); ?>" <?php if($section->section_id == $section_id_tgt) { ?> selected <?php } ?>><?php echo esc_html( $section->name ); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
	</table>
<?php }

add_action('personal_options_update', 'sakolawp_save_user_reassign_Class');
add_action('edit_user_profile_update', 'sakolawp_save_user_reassign_Class');

function sakolawp_save_user_reassign_Class($user_id)
{
	if (!current_user_can('edit_user', $user_id))
		return false;
	global $wpdb;
	var_dump($user_id);

	$class_id = sanitize_text_field($_POST['class_id']);
	$section_id = sanitize_text_field($_POST['section_id']);

	$wpdb->update(
		$wpdb->prefix . 'sakolawp_enroll',
		array( 
			'class_id' => $class_id,
			'section_id' => $section_id
		),
		array(
			'student_id' => $user_id
		)
	);
}