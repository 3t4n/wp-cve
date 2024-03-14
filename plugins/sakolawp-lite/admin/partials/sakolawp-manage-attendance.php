<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://themesawesome.com/
 * @since      1.0.0
 *
 * @package    Sakolawp
 * @subpackage Sakolawp/admin/partials
 */

global $wpdb;

$running_year = get_option('running_year');
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="manage-attendance skwp-content-inner">


	<nav class="skwp-tabs-menu">
		<div class="nav nav-tabs" id="nav-tab" role="tablist">
			<div class="skwp-logo">
				<img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'img/swp-logo.png'); ?>" alt="<?php echo esc_attr('Sakola Logo'); ?>">
			</div>
			<a class="nav-item nav-link active" href="#"><?php esc_html_e('Student', 'sakolawp'); ?></a>
			<a class="nav-item nav-link" href="admin.php?page=sakolawp-manage-report-attendance"><?php esc_html_e('Attendance Report', 'sakolawp'); ?></a>
		</div>
	</nav>
	<div class="skwp-tab-content tab-content" id="nav-tabContent">
		<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
			<?php
			if (!isset($_POST['submit'])) { ?>
				<form id="myForm" name="save_student_attendance" action="" method="POST">
					<div class="skwp-row skwp-clearfix">
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group"> <label class="gi" for=""><?php esc_html_e('Class :', 'sakolawp'); ?></label>
								<select class="skwp-form-control" name="class_id" id="class_holder" required="">
									<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
									<?php
									global $wpdb;
									$classes = $wpdb->get_results("SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", OBJECT);
									foreach ($classes as $class) :
									?>
										<option value="<?php echo esc_attr($class->class_id); ?>"><?php echo esc_html($class->name); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group"> <label class="gi" for=""><?php esc_html_e('Section :', 'sakolawp'); ?></label>
								<select class="skwp-form-control" name="section_id" id="section_holder" required="">
									<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
								</select>
							</div>
						</div>
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group"> <label class="gi" for=""><?php esc_html_e('Date :', 'sakolawp'); ?></label>
								<input class="single-daterange skwp-form-control" placeholder="Date" required="" name="timestamp" type="text" value="">
							</div>
						</div>
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group skwp-mt-30"> <button class="btn skwp-btn btn-rounded btn-primary" type="submit" value="submit" name="submit"><span><?php esc_html_e('View', 'sakolawp'); ?></span></button></div>
						</div>
					</div>
					<input type="hidden" name="year" value="<?php echo esc_attr($running_year); ?>">
				</form>
			<?php } ?>

			<?php

			if (isset($_POST['submit'])) {

				$class_id = sanitize_text_field($_POST['class_id']);
				$section_id = sanitize_text_field($_POST['section_id']);
				$year = sanitize_text_field($_POST['year']);
				$originalDate = sanitize_text_field($_POST['timestamp']);
				$newDate = sanitize_text_field(date("d-m-Y", strtotime($originalDate)));
				$timestamp = sanitize_text_field(strtotime($newDate));

				//var_dump($class_id);

				$students = $wpdb->get_results("SELECT student_id FROM {$wpdb->prefix}sakolawp_enroll WHERE class_id = '$class_id' AND section_id = '$section_id' AND year = '$year'", ARRAY_A);
				foreach ($students as $row) {
					$student_id = $row['student_id'];
					$status = 0;

					$exist_attendance = $wpdb->get_row("SELECT student_id FROM {$wpdb->prefix}sakolawp_attendance WHERE class_id = '$class_id' AND section_id = '$section_id' AND year = '$year' AND timestamp = '$timestamp' AND student_id = '$student_id'", ARRAY_A);
					if (empty($exist_attendance)) {
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


				$students2 = $wpdb->get_results("SELECT student_id FROM {$wpdb->prefix}sakolawp_enroll WHERE class_id = '$class_id' AND section_id = '$section_id' AND year = '$year'", ARRAY_A);

				foreach ($students2 as $row2) {
					$student_id = sanitize_text_field($row2['student_id']);
					$month = sanitize_text_field($tgl_m);
					$year = sanitize_text_field($tgl_y);

					$exist_attendance_log = $wpdb->get_row("SELECT student_id FROM {$wpdb->prefix}sakolawp_attendance_log WHERE class_id = '$class_id' AND section_id = '$section_id' AND month = '$tgl_m' AND year = '$tgl_y' AND student_id = '$student_id'", ARRAY_A);
					if (empty($exist_attendance_log)) {
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
				} ?>
				<form id="myForm" name="save_student_attendance" action="" method="POST">
					<div class="skwp-row skwp-clearfix">
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group"> <label class="gi" for=""><?php esc_html_e('Class:', 'sakolawp'); ?></label>
								<select class="skwp-form-control" name="class_id" id="class_holder" required="">
									<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
									<?php
									global $wpdb;
									$classes = $wpdb->get_results("SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", OBJECT);
									foreach ($classes as $class) :
									?>
										<option value="<?php echo esc_attr($class->class_id); ?>" <?php if ($class->class_id == $class_id) {
																										echo "selected";
																									} ?>><?php echo esc_html($class->name); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group"> <label class="gi" for=""><?php esc_html_e('Section:', 'sakolawp'); ?></label>
								<select class="skwp-form-control" name="section_id" id="section_holder" required="">
									<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
									<?php
									$sections = $wpdb->get_results("SELECT section_id, name FROM {$wpdb->prefix}sakolawp_section WHERE class_id = '$class_id'", ARRAY_A);
									foreach ($sections as $row) { ?>
										<option value="<?php echo esc_attr($row['section_id']); ?>" <?php if ($row['section_id'] == $section_id) {
																										echo "selected";
																									} ?>><?php echo esc_html($row['name']); ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group"> <label class="gi" for=""><?php esc_html_e('Date:', 'sakolawp'); ?></label>
								<input class="single-daterange skwp-form-control" placeholder="Date" required="" name="timestamp" type="text" value="">
							</div>
						</div>
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group skwp-mt-30"> <button class="btn skwp-btn btn-rounded btn-primary" type="submit" value="submit" name="submit"><span><?php esc_html_e('View', 'sakolawp'); ?></span></button></div>
						</div>
					</div>
					<input type="hidden" name="year" value="<?php echo esc_attr($running_year); ?>">
				</form>

				<form id="myForm" name="save_student_attendance_status" action="" method="POST">
					<h5 class="skwp-form-header" style="margin: 10px 0 30px;float: left;"><?php esc_html_e('Attendance', 'sakolawp'); ?></h5>
					<div class="table-responsive">
						<table id="kehadiranSiswa" class="table table-lightborder">
							<thead>
								<tr>
									<th style="text-align: center;">
										<?php esc_html_e('Student', 'sakolawp'); ?>
									</th>
									<th style="text-align: center;">
										<?php esc_html_e('Status', 'sakolawp'); ?>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$count = 1;
								$attendance_of_students = $wpdb->get_results("SELECT student_id, status, attendance_id FROM {$wpdb->prefix}sakolawp_attendance WHERE class_id = $class_id AND section_id = $section_id AND year = '$running_year' AND timestamp = '$timestamp'", ARRAY_A);
								foreach ($attendance_of_students as $row) :
								?>
									<tr>
										<td style="min-width:170px;">
											<?php
											$user_info = get_userdata($row['student_id']);
											echo esc_html($user_info->display_name); ?>
										</td>
										<td style="text-align: center;" nowrap>
											<div class="skwp-form-check">
												<label class="skwp-form-check-label"><input checked class="skwp-form-check-input" <?php if ($row['status'] == 1) echo esc_attr('checked'); ?> name="status_<?php echo esc_attr($row['attendance_id']); ?>" type="radio" value="1" style="margin-left:5px"><?php esc_html_e('Present', 'sakolawp'); ?></label>
												<label class="skwp-form-check-label"><input class="skwp-form-check-input" <?php if ($row['status'] == 3) echo esc_attr('checked'); ?> name="status_<?php echo esc_attr($row['attendance_id']); ?>" type="radio" value="3" style="margin-left:5px"><?php esc_html_e('Late', 'sakolawp'); ?></label>
												<label class="skwp-form-check-label"><input class="skwp-form-check-input" <?php if ($row['status'] == 2) echo esc_attr('checked'); ?> name="status_<?php echo esc_attr($row['attendance_id']); ?>" type="radio" value="2" style="margin-left:5px"><?php esc_html_e('Absent', 'sakolawp'); ?></label>
												<label class="skwp-form-check-label"><input class="skwp-form-check-input" <?php if ($row['status'] == 4) echo esc_attr('checked'); ?> name="status_<?php echo esc_attr($row['attendance_id']); ?>" type="radio" value="4" style="margin-left:5px"><?php esc_html_e('Sick', 'sakolawp'); ?></label>
												<label class="skwp-form-check-label"><input class="skwp-form-check-input" <?php if ($row['status'] == 5) echo esc_attr('checked'); ?> name="status_<?php echo esc_attr($row['attendance_id']); ?>" type="radio" value="5" style="margin-left:5px"><?php esc_html_e('Permit', 'sakolawp'); ?></label>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<input type="hidden" name="class_id" value="<?php echo esc_attr($class_id); ?>">
						<input type="hidden" name="section_id" value="<?php echo esc_attr($section_id); ?>">
						<input type="hidden" name="timestamp" value="<?php echo esc_attr($timestamp); ?>">
						<div class="skwp-skwp-form-button">
							<button class="btn skwp-btn btn-rounded btn-primary" type="submit" value="absensi" name="absensi"> <?php esc_html_e('Update', 'sakolawp'); ?></button>
						</div>
					</div>
				</form>
			<?php } ?>

			<?php if (isset($_POST['absensi'])) {

				$class_id = sanitize_text_field($_POST['class_id']);
				$section_id = sanitize_text_field($_POST['section_id']);
				$originalDate = sanitize_text_field($_POST['timestamp']);
				$newDate = sanitize_text_field(date("d-m-Y", strtotime($originalDate)));
				$timestamp = sanitize_text_field(strtotime($newDate));

				$attendance_of_students = $wpdb->get_results("SELECT attendance_id, student_id FROM {$wpdb->prefix}sakolawp_attendance WHERE class_id = $class_id AND section_id = $section_id AND year = '$running_year' AND timestamp = '$originalDate'", ARRAY_A);

				$tgl_d = esc_html(date("j", $originalDate));
				$tgl_m = sanitize_text_field(date("n", $originalDate));
				$tgl_y = sanitize_text_field(date("Y", $originalDate));

				foreach ($attendance_of_students as $row) {
					$attendance_status = sanitize_text_field($_POST['status_' . $row['attendance_id']]);

					$wpdb->update(
						$wpdb->prefix . 'sakolawp_attendance',
						array(
							'status' => $attendance_status
						),
						array(
							'attendance_id' => sanitize_text_field($row['attendance_id'])
						)
					);

					$wpdb->update(
						$wpdb->prefix . 'sakolawp_attendance_log',
						array(
							'day_' . $tgl_d => $attendance_status,
							'time_' . $tgl_d => $originalDate,
						),
						array(
							'student_id' => sanitize_text_field($row['student_id']),
							'month' => $tgl_m,
							'year' => $tgl_y
						)
					);
				}
			} ?>

		</div>
		<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">

		</div>
	</div>
</div>