<?php
defined( 'ABSPATH' ) || exit;

global $wpdb;

$class_id = sanitize_text_field($_GET['class_id']);
$section_id = sanitize_text_field($_GET['section_id']);
$timestamp = sanitize_text_field($_GET['timestamp']);

$running_year = get_option('running_year');

$teacher_id = get_current_user_id();

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;

if(isset($_POST['submit'])) {
	if(wp_verify_nonce($_POST['sakola_attendance_submit_csrf'], 'sakola-attendance-submit-csrf')) {
		if(current_user_can( 'read' )) {

			$attendance_of_students = $wpdb->get_results( "SELECT attendance_id,student_id FROM {$wpdb->prefix}sakolawp_attendance WHERE class_id = $class_id AND section_id = $section_id AND year = '$running_year' AND timestamp = '$timestamp'", ARRAY_A );

			$tgl_d = date("j", $timestamp);
			$tgl_m = date("n", $timestamp);
			$tgl_y = date("Y", $timestamp);

			foreach($attendance_of_students as $row)
			{
				$attendance_status = sanitize_text_field($_POST['status_'.$row['attendance_id']]);

				$wpdb->update(
					$wpdb->prefix . 'sakolawp_attendance',
					array(
						'status' => $attendance_status
					),
					array(
						'attendance_id' => $row['attendance_id']
					)
				);

				$wpdb->update(
					$wpdb->prefix . 'sakolawp_attendance_log',
					array(
						'day_'.$tgl_d => $attendance_status,
						'time_'.$tgl_d => $timestamp,
					),
					array(
						'student_id' => $row['student_id'],
						'month' => $tgl_m,
						'year' => $tgl_y
					)
				);
			}
		}
	}
}

if(isset($_POST['submit2'])) {
	if(wp_verify_nonce($_POST['sakola_attendance_submit_dua_csrf'], 'sakola-attendance-submit-dua-csrf')) {
		if(current_user_can( 'read' )) {

			$class_id = sanitize_text_field($_POST['class_id']);
			$section_id = sanitize_text_field($_POST['section_id']);
			$year = sanitize_text_field($_POST['year']);
			$originalDate = sanitize_text_field($_POST['timestamp']);
			$newDate = date("d-m-Y", strtotime($originalDate));
			$timestamp = sanitize_text_field(strtotime($newDate));


			$students = $wpdb->get_results( "SELECT student_id FROM {$wpdb->prefix}sakolawp_enroll WHERE class_id = '$class_id' AND section_id = '$section_id' AND year = '$year'", ARRAY_A );
			foreach($students as $row) {
				$student_id = sanitize_text_field($row['student_id']);
				$status = 0;
				

				$exist_attendance = $wpdb->get_row( "SELECT class_id FROM {$wpdb->prefix}sakolawp_attendance WHERE class_id = '$class_id' AND section_id = '$section_id' AND year = '$year' AND timestamp = '$timestamp' AND student_id = '$student_id'", ARRAY_A );
				if(empty($exist_attendance)) {
					$wpdb->insert(
						$wpdb->prefix . 'sakolawp_attendance',
						array( 
							'class_id'   => $class_id,
							'year'       => $year,
							'timestamp'  => $timestamp,
							'section_id' => $section_id,
							'student_id' => $student_id
						)
					);
				}
			}

			$tgl_m = date("n", strtotime($originalDate));
			$tgl_y = date("Y", strtotime($originalDate));


			$students2 = $wpdb->get_results( "SELECT student_id FROM {$wpdb->prefix}sakolawp_enroll WHERE class_id = '$class_id' AND section_id = '$section_id' AND year = '$year'", ARRAY_A );
			foreach($students2 as $row2) {
				$student_id = sanitize_text_field($row2['student_id']);
				$month = sanitize_text_field($tgl_m);
				$year = sanitize_text_field($tgl_y);

				$exist_attendance_log = $wpdb->get_row( "SELECT year FROM {$wpdb->prefix}sakolawp_attendance_log WHERE class_id = '$class_id' AND section_id = '$section_id' AND month = '$tgl_m' AND year = '$tgl_y' AND student_id = '$student_id'", ARRAY_A );
				if(empty($exist_attendance_log)) {
					$wpdb->insert(
						$wpdb->prefix . 'sakolawp_attendance_log',
						array( 
							'student_id' => $student_id,
							'timestamp' => $timestamp,
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
do_action( 'sakolawp_before_main_content' ); ?>

<div class="attendance-page skwp-content-inner">
	
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
	
	<form action="" id="myForm" name="save_student_attendance_status2" method="POST" class="skwp-clearfix">
		<input type="hidden" name="sakola_attendance_submit_dua_csrf" value="<?php echo wp_create_nonce('sakola-attendance-submit-dua-csrf'); ?>" />
		<div class="skwp-row skwp-clearfix">
			<div class="skwp-column skwp-column-4">
				<div class="skwp-form-group"> 
					<label for=""><?php echo esc_html__( 'Class', 'sakolawp' ); ?></label> 
					<?php 
					$section_teached = $wpdb->get_row( "SELECT class_id,name,section_id FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $section_id"); ?>
					<?php
						$classes = $wpdb->get_results( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id", ARRAY_A );
						foreach($classes as $row):
					?>
					<input type="text" class="skwp-form-control" required disabled value="<?php echo esc_attr($row['name']); ?>">
					<input type="hidden" class="skwp-form-control" name="class_id" required value="<?php echo esc_attr($section_teached->class_id); ?>">
					<?php endforeach; ?>
				</div>
			</div>
			<div class="skwp-column skwp-column-4">
				<div class="skwp-form-group"> <label for=""><?php echo esc_html__( 'Section', 'sakolawp' ); ?></label> 
					<input type="text" class="skwp-form-control" required disabled value="<?php echo esc_attr($section_teached->name); ?>">
					<input type="hidden" class="skwp-form-control" name="section_id" required value="<?php echo esc_attr($section_teached->section_id); ?>">
				</div>
			</div>
			<div class="skwp-column skwp-column-4">
				<div class="skwp-form-group"> <label for=""><?php echo esc_html__( 'Date', 'sakolawp' ); ?></label> 
					<?php 
					$originalDate = sanitize_text_field($_GET['timestamp']);
					$newDate = date("m/d/Y", esc_html($originalDate)); ?>
					<input class="single-daterange skwp-form-control" placeholder="Date" required="" name="timestamp" type="text" value="<?php echo esc_attr($newDate); ?>"> 
				</div>
			</div>
			<div class="skwp-column skwp-column-4">
				<div class="skwp-form-group"> <button class="btn btn-rounded btn-success btn-upper skwp-btn skwp-mt-20" type="submit" value="submit2" name="submit2"><span><?php echo esc_html__( 'View', 'sakolawp' ); ?></span></button></div>
			</div>
		</div>
		<input type="hidden" name="year" value="<?php echo esc_attr($running_year);?>">
	</form>

	<form id="myForm" name="save_student_attendance_status" action="" method="POST" class="skwp-clearfix skwp-mt-20">
		<input type="hidden" name="sakola_attendance_submit_csrf" value="<?php echo wp_create_nonce('sakola-attendance-submit-csrf'); ?>" />
		<div class="skwp-table table-responsive">
			<table id="tableini" class="table table-lightborder">
				<thead>
					<tr>
						<th class="name">
							<?php echo esc_html__( 'Student', 'sakolawp' ); ?>
						</th>
						<!-- <th>
							NISN
						</th> -->
						<th class="text-center">
							<?php echo esc_html__( 'Status', 'sakolawp' ); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$count = 1;
					$attendance_of_students = $wpdb->get_results( "SELECT student_id,status,attendance_id  FROM {$wpdb->prefix}sakolawp_attendance WHERE class_id = $class_id AND section_id = $section_id AND year = '$running_year' AND timestamp = '$timestamp'", ARRAY_A );
					$no = 1;
					foreach ($attendance_of_students as $row):
					$no++;
					?>
					<tr>
						<td>
							<?php 
							global $wp;
							$user_info = get_userdata($row['student_id']);
							$user_name = $user_info->display_name; ?>
							<span><?php 
							$user_info = get_userdata($row['student_id']);
							echo esc_html($user_info->display_name); ?></span>
						</td>
						<td nowrap>
							<div class="skwp-form-check">
								<label class="skwp-form-check-label p" for="p-<?php echo esc_attr($no); ?>">
									<input checked class="skwp-form-check-input" <?php if ($row['status'] == 1) echo esc_attr( 'checked' ); ?> name="status_<?php echo esc_attr($row['attendance_id']); ?>" id="p-<?php echo esc_attr($no); ?>" type="radio" value="1"
									>
									<span><?php echo esc_html__( 'Present', 'sakolawp' ); ?></span>
									<span class="background"></span>
								</label>
								<label class="skwp-form-check-label l" for="l-<?php echo esc_attr($no); ?>">
									<input class="skwp-form-check-input" <?php if ($row['status'] == 3) echo esc_attr( 'checked' ); ?> name="status_<?php echo esc_attr($row['attendance_id']); ?>" id="l-<?php echo esc_attr($no); ?>" type="radio" value="3">
									<span><?php echo esc_html__( 'Late', 'sakolawp' ); ?></span>
									<span class="background"></span>
								</label>
								<label class="skwp-form-check-label a" for="a-<?php echo esc_attr($no); ?>">
									<input class="skwp-form-check-input" <?php if ($row['status'] == 2) echo esc_attr( 'checked' ); ?> name="status_<?php echo esc_attr($row['attendance_id']); ?>" id="a-<?php echo esc_attr($no); ?>" type="radio" value="2">
									<span><?php echo esc_html__( 'Absent', 'sakolawp' ); ?></span>
									<span class="background"></span>
								</label>
								<label class="skwp-form-check-label s" for="s-<?php echo esc_attr($no); ?>">
									<input class="skwp-form-check-input" <?php if ($row['status'] == 4) echo esc_attr( 'checked' ); ?> name="status_<?php echo esc_attr($row['attendance_id']); ?>" id="s-<?php echo esc_attr($no); ?>" type="radio" value="4">
									<span><?php echo esc_html__( 'Sick', 'sakolawp' ); ?></span>
									<span class="background"></span>
								</label>
								<label class="skwp-form-check-label i" for="i-<?php echo esc_attr($no); ?>">
									<input class="skwp-form-check-input" <?php if ($row['status'] == 5) echo esc_attr( 'checked' ); ?> name="status_<?php echo esc_attr($row['attendance_id']); ?>" id="i-<?php echo esc_attr($no); ?>" type="radio" value="5">
									<span><?php echo esc_html__( 'Permit', 'sakolawp' ); ?></span>
									<span class="background"></span>
								</label>
							</div>
						</td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<div class="skwp-form-button text-center">
				<button class="btn btn-rounded btn-success skwp-btn" type="submit" value="submit" name="submit"> <?php echo esc_html__( 'Update', 'sakolawp' ); ?></button>
			</div>
		</div>
	</form>
</div>

<?php
do_action( 'sakolawp_after_main_content' );
get_footer();