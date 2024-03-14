<?php
defined( 'ABSPATH' ) || exit;

global $wpdb;

if(isset($_POST['submit'])) {
	if(wp_verify_nonce($_POST['sakola_m_attendance_csrf'], 'sakola-m-attendance-csrf')) {
		if(current_user_can( 'read' )) {
			$class_id = sanitize_text_field($_POST['class_id']);
			$section_id = sanitize_text_field($_POST['section_id']);
			$year = sanitize_text_field($_POST['year']);
			$originalDate = sanitize_text_field($_POST['timestamp']);
			$newDate = date("d-m-Y", strtotime($originalDate));
			$timestamp = strtotime($newDate);


			$students = $wpdb->get_results( "SELECT student_id FROM {$wpdb->prefix}sakolawp_enroll WHERE class_id = '$class_id' AND section_id = '$section_id' AND year = '$year'", ARRAY_A );
			foreach($students as $row) {
				$student_id = $row['student_id'];
				$status = 0;
				

				$exist_attendance = $wpdb->get_row( "SELECT class_id FROM {$wpdb->prefix}sakolawp_attendance WHERE class_id = '$class_id' AND section_id = '$section_id' AND year = '$year' AND timestamp = '$timestamp' AND student_id = '$student_id'", ARRAY_A );
				if(empty($exist_attendance)) {
					$wpdb->insert(
						$wpdb->prefix . 'sakolawp_attendance',
						array( 
							'class_id'   => $class_id,
							'year'       => $year,
							'timestamp'  => sanitize_text_field($timestamp),
							'section_id' => $section_id,
							'student_id' => sanitize_text_field($student_id)
						)
					);
				}
			}

			$tgl_m = date("n", strtotime($originalDate));
			$tgl_y = date("Y", strtotime($originalDate));


			$students2 = $wpdb->get_results( "SELECT student_id FROM {$wpdb->prefix}sakolawp_enroll WHERE class_id = '$class_id' AND section_id = '$section_id' AND year = '$year'", ARRAY_A );
			foreach($students2 as $row2) {
				$student_id = $row2['student_id'];
				$month = sanitize_text_field($tgl_m);
				$year = sanitize_text_field($tgl_y);

				$exist_attendance_log = $wpdb->get_row( "SELECT class_id FROM {$wpdb->prefix}sakolawp_attendance_log WHERE class_id = '$class_id' AND section_id = '$section_id' AND month = '$tgl_m' AND year = '$tgl_y' AND student_id = '$student_id'", ARRAY_A );
				if(empty($exist_attendance_log)) {
					$wpdb->insert(
						$wpdb->prefix . 'sakolawp_attendance_log',
						array( 
							'student_id' => sanitize_text_field($student_id),
							'timestamp' => sanitize_text_field($timestamp),
							'month' => $month,
							'year' => $year,
							'class_id' => $class_id,
							'section_id' => $section_id
						)
					);
				}
			}

			wp_redirect(add_query_arg(array('class_id' => intval($class_id), 'section_id' => intval($section_id), 'timestamp' => intval($timestamp)), home_url( 'manage_attendance_view' ) ));
			die;
		}
	}
}

get_header();
do_action( 'sakolawp_before_main_content' ); 

$running_year = get_option('running_year');

$teacher_id = get_current_user_id();

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;
?>

<div class="attendance-page skwp-content-inner skwp-clearfix">
	
	<div class="skwp-page-title no-border">
		<h5><?php esc_html_e('Manage Attendance', 'sakolawp'); ?></h5>
	</div>

	<div class="skwp-tab-menu">
		<ul class="skwp-tab-wrap">
			<li class="skwp-tab-items active">
				<a class="skwp-tab-item" href="<?php echo site_url('manage_attendance');?>">
					<span><?php echo esc_html__( 'Attendances', 'sakolawp' ); ?></span>
				</a>
			</li>
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo site_url('attendance_report');?>">
					<span><?php echo esc_html__( 'Attendances Report', 'sakolawp' ); ?></span>
				</a>
			</li>
		</ul>
	</div>

	<form id="myForm" name="save_student_attendance" action="" method="POST">
		<input type="hidden" name="sakola_m_attendance_csrf" value="<?php echo wp_create_nonce('sakola-m-attendance-csrf'); ?>" />
		<div class="skwp-row">
			<div class="skwp-column skwp-column-4">
				<div class="form-group"> <label class="gi" for=""><?php echo esc_html__( 'Class', 'sakolawp' ); ?></label> 
					<?php 
					$section_teached = $wpdb->get_results( "SELECT class_id,name,section_id FROM {$wpdb->prefix}sakolawp_section WHERE teacher_id = $teacher_id");
					$selected_class = array();
					foreach ($section_teached as $the_class) {
						$selected_class[] = $the_class->class_id;
					}
					$listofclass = array_unique($selected_class); ?>
					<?php
						$classes = $wpdb->get_results( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $section_teached->class_id", ARRAY_A );
						$class_ajar = array();
						foreach($classes as $row):

							$class_ajar[] = $section_teached->class_id;
					?><!-- 
					<input type="text" class="form-control" required disabled value="<?php echo esc_attr($row['name']); ?>">
					<input type="hidden" class="form-control" name="class_id" required value="<?php echo esc_attr($section_teached->class_id); ?>"> -->
					<?php endforeach;
						$sellistofclass = implode(', ', $listofclass); ?>

					<select class="form-control" name="class_id" required="" id="class_holder_spe">
						<option value=""><?php echo esc_html__( 'Select', 'sakolawp' ); ?></option>
						<?php 
						global $wpdb;
						$classes = $wpdb->get_results( "SELECT name,class_id FROM {$wpdb->prefix}sakolawp_class WHERE class_id IN ($sellistofclass)", OBJECT );
						foreach($classes as $class):
						?>
						<option value="<?php echo esc_attr($class->class_id); ?>"><?php echo esc_html($class->name); ?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
			<div class="skwp-column skwp-column-4">
				<div class="form-group"> <label class="gi" for=""><?php echo esc_html__( 'Section', 'sakolawp' ); ?></label> 
					<!-- <input type="text" class="form-control" required disabled value="<?php echo esc_attr($section_teached->name); ?>">
					<input type="hidden" class="form-control" name="section_id" required value="<?php echo esc_attr($section_teached->section_id); ?>"> -->

						<select class="form-control teacher-section" name="section_id" required="" id="section_holder_spe">
							<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
						</select>
				</div>
			</div>
			<div class="skwp-column skwp-column-4">
				<div class="form-group"> <label class="gi" for=""><?php echo esc_html__( 'Date', 'sakolawp' ); ?></label> 
					<input class="single-daterange form-control" placeholder="Date" required="" name="timestamp" type="text">
				</div>
			</div>
			<div class="skwp-column skwp-column-4">
				<div class="form-group"> <button class="btn btn-rounded btn-success btn-upper skwp-btn skwp-mt-20" type="submit" value="submit" name="submit"><span><?php echo esc_html__( 'View', 'sakolawp' ); ?></span></button></div>
			</div>
		</div>
		<input type="hidden" name="year" value="<?php echo esc_attr($running_year);?>">
	</form>
</div>

<?php
do_action( 'sakolawp_after_main_content' );
get_footer();