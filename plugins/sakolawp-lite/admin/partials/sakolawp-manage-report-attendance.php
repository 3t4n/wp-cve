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

if (isset($_POST['submit'])) {
	$class_id = sanitize_text_field($_POST['class_id']);
	$section_id = sanitize_text_field($_POST['section_id']);
	$year_sel = sanitize_text_field($_POST['year_sel']);
	$month = sanitize_text_field($_POST['month']);
}
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="manage-report-attendance skwp-content-inner">


	<nav class="skwp-tabs-menu">
		<div class="nav nav-tabs" id="nav-tab" role="tablist">
			<div class="skwp-logo">
				<img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'img/swp-logo.png'); ?>" alt="<?php echo esc_attr('Sakola Logo'); ?>">
			</div>
			<a class="nav-item nav-link" href="admin.php?page=sakolawp-manage-attendance"><?php esc_html_e('Student', 'sakolawp'); ?></a>
			<a class="nav-item nav-link active" href="#"><?php esc_html_e('Attendance Report', 'sakolawp'); ?></a>
		</div>
	</nav>
	<div class="skwp-tab-content tab-content" id="nav-tabContent">
		<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
			<?php if (isset($class_id) == '' && isset($section_id) == '' && isset($month) == '') : ?>
				<form id="myForm" name="save_student_attendance" action="" method="POST">
					<div class="skwp-row skwp-clearfix">
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group"> <label class="gi" for=""><?php esc_html_e('Class', 'sakolawp'); ?></label>
								<select class="skwp-form-control" name="class_id" id="class_holder">
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
							<div class="skwp-form-group"> <label class="gi" for=""><?php esc_html_e('Section', 'sakolawp'); ?></label>
								<select class="skwp-form-control" name="section_id" id="section_holder">
									<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
								</select>
							</div>
						</div>
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group"> <label class="gi" for=""><?php esc_html_e('Month', 'sakolawp'); ?></label>
								<select name="month" class="skwp-form-control" id="month">
									<?php
									for ($i = 1; $i <= 12; $i++) :
										if ($i == 1)
											$m = esc_html__('January', 'sakolawp');
										else if ($i == 2)
											$m = esc_html__('February', 'sakolawp');
										else if ($i == 3)
											$m = esc_html__('March', 'sakolawp');
										else if ($i == 4)
											$m = esc_html__('April', 'sakolawp');
										else if ($i == 5)
											$m = esc_html__('May', 'sakolawp');
										else if ($i == 6)
											$m = esc_html__('June', 'sakolawp');
										else if ($i == 7)
											$m = esc_html__('July', 'sakolawp');
										else if ($i == 8)
											$m = esc_html__('August', 'sakolawp');
										else if ($i == 9)
											$m = esc_html__('September', 'sakolawp');
										else if ($i == 10)
											$m = esc_html__('October', 'sakolawp');
										else if ($i == 11)
											$m = esc_html__('November', 'sakolawp');
										else if ($i == 12)
											$m = esc_html__('December', 'sakolawp');
									?>
										<option value="<?php echo esc_attr($i); ?>">
											<?php echo esc_html($m); ?>
										</option>
									<?php
									endfor;
									?>
								</select>
							</div>
						</div>
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group">
								<label class="gi" for=""><?php esc_html_e('Year', 'sakolawp'); ?></label>
								<select name="year_sel" class="skwp-form-control" required="">
									<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
									<?php $year = explode('-', $running_year); ?>
									<option value="<?php echo esc_attr($year[0]); ?>"><?php echo esc_html($year[0]); ?></option>
									<option value="<?php echo esc_attr($year[1]); ?>"><?php echo esc_html($year[1]); ?></option>
								</select>
							</div>
						</div>
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group skwp-mt-30"> <button class="btn skwp-btn btn-rounded btn-primary" type="submit" value="submit" name="submit"><span><?php esc_html_e('View', 'sakolawp'); ?></span></button></div>
						</div>
					</div>
					<input type="hidden" name="year" value="<?php echo esc_attr($running_year); ?>">
				</form>
			<?php endif; ?>

			<?php if (isset($class_id) != '' && isset($section_id) != '' && isset($month) != '') :
				$year = explode('-', $running_year); ?>
				<form id="myForm" name="save_student_attendance" action="" method="POST">
					<div class="skwp-row skwp-clearfix">
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group"> <label class="gi" for=""><?php esc_html_e('Class', 'sakolawp'); ?></label>
								<select class="skwp-form-control" name="class_id" id="class_holder">
									<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
									<?php
									global $wpdb;
									$classes = $wpdb->get_results("SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", OBJECT);
									foreach ($classes as $class) :
									?>
										<option value="<?php echo esc_attr($class->class_id); ?>" <?php if ($class->class_id == $class_id) { echo "selected"; } ?>><?php echo esc_html($class->name); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group"> <label class="gi" for=""><?php esc_html_e('Section', 'sakolawp'); ?></label>
								<select class="skwp-form-control" name="section_id" id="section_holder">
									<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
									<?php
									$sections = $wpdb->get_results("SELECT section_id, name FROM {$wpdb->prefix}sakolawp_section WHERE class_id = '$class_id'", ARRAY_A);
									echo '<option value="">' . esc_html__('Select', 'sakolawp') . '</option>';
									foreach ($sections as $row) { ?>
										<option value="<?php echo esc_attr($row['section_id']); ?>" <?php if ($row['section_id'] == $section_id) { echo "selected"; } ?>><?php echo esc_html($row['name']); ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group"> <label class="gi" for=""><?php esc_html_e('Month', 'sakolawp'); ?></label>
								<select name="month" class="skwp-form-control" id="month">
									<?php
									for ($i = 1; $i <= 12; $i++) :
										if ($i == 1)
											$m = esc_html__('January', 'sakolawp');
										else if ($i == 2)
											$m = esc_html__('February', 'sakolawp');
										else if ($i == 3)
											$m = esc_html__('March', 'sakolawp');
										else if ($i == 4)
											$m = esc_html__('April', 'sakolawp');
										else if ($i == 5)
											$m = esc_html__('May', 'sakolawp');
										else if ($i == 6)
											$m = esc_html__('June', 'sakolawp');
										else if ($i == 7)
											$m = esc_html__('July', 'sakolawp');
										else if ($i == 8)
											$m = esc_html__('August', 'sakolawp');
										else if ($i == 9)
											$m = esc_html__('September', 'sakolawp');
										else if ($i == 10)
											$m = esc_html__('October', 'sakolawp');
										else if ($i == 11)
											$m = esc_html__('November', 'sakolawp');
										else if ($i == 12)
											$m = esc_html__('December', 'sakolawp');
									?>
										<option value="<?php echo esc_attr($i); ?>" <?php if ($month == $i) echo esc_attr( 'selected' ); ?>>
											<?php echo esc_html($m); ?>
										</option>
									<?php
									endfor;
									?>
								</select>
							</div>
						</div>
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group">
								<label class="gi" for=""><?php esc_html_e('Year', 'sakolawp'); ?></label>
								<select name="year_sel" class="skwp-form-control" required="">
									<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
									<?php $year = explode('-', $running_year); ?>
									<option value="<?php echo esc_attr($year[0]); ?>" <?php if ($year_sel == $year[0]) echo esc_attr( 'selected' ); ?>><?php echo esc_html($year[0]); ?></option>
									<option value="<?php echo esc_attr($year[1]); ?>" <?php if ($year_sel == $year[1]) echo esc_attr( 'selected' ); ?>><?php echo esc_html($year[1]); ?></option>
								</select>
							</div>
						</div>
						<div class="skwp-column skwp-column-5">
							<div class="skwp-form-group skwp-mt-30"> <button class="btn skwp-btn btn-rounded btn-primary" type="submit" value="submit" name="submit"><span><?php esc_html_e('View', 'sakolawp'); ?></span></button></div>
						</div>
					</div>
					<input type="hidden" name="year" value="<?php echo esc_attr($running_year); ?>">
				</form>
				<div class="skwp-month-year">
					<h5 class="skwp-form-header">
						<?php
						if ($month == 1)
							$mo = esc_html__('January', 'sakolawp');
						else if ($month == 2)
							$mo = esc_html__('February', 'sakolawp');
						else if ($month == 3)
							$mo = esc_html__('March', 'sakolawp');
						else if ($month == 4)
							$mo = esc_html__('April', 'sakolawp');
						else if ($month == 5)
							$mo = esc_html__('May', 'sakolawp');
						else if ($month == 6)
							$mo = esc_html__('June', 'sakolawp');
						else if ($month == 7)
							$mo = esc_html__('July', 'sakolawp');
						else if ($month == 8)
							$mo = esc_html__('August', 'sakolawp');
						else if ($month == 9)
							$mo = esc_html__('September', 'sakolawp');
						else if ($month == 10)
							$mo = esc_html__('October', 'sakolawp');
						else if ($month == 11)
							$mo = esc_html__('November', 'sakolawp');
						else if ($month == 12)
							$mo = esc_html__('December', 'sakolawp');

						echo esc_html($mo) . ' ' . esc_html($year_sel); ?>
					</h5>
				</div>
				<div class="table-responsive">
					<table id="laporanKehadiranSiswa" class="table table-sm table-lightborder">
						<thead>
							<tr class="text-center" height="50px">
								<th class="text-left">
									<?php esc_html_e('Students', 'sakolawp'); ?>
								</th>
								<?php
								$year = explode('-', $running_year);
								$days = cal_days_in_month(CAL_GREGORIAN, $month, $year_sel);
								for ($i = 1; $i <= $days; $i++) {
								?>
									<td style="text-align: center;">
										<?php echo esc_html($i); ?>
									</td>
								<?php } ?>
							</tr>
						</thead>
						<tbody>
							<?php
							$attendance = get_option('sakolawp_routine');
							$libur = $attendance;

							$students = $wpdb->get_results("SELECT student_id FROM {$wpdb->prefix}sakolawp_enroll WHERE class_id = $class_id AND section_id = $section_id AND year = '$running_year'", ARRAY_A);
							foreach ($students as $row) : ?>
								<tr>
									<td nowrap>
										<?php
										$user_info = get_userdata($row['student_id']);
										echo esc_html($user_info->display_name); ?>
									</td>
									<?php
									$status = 0;
									for ($i = 1; $i <= $days; $i++) { ?>
										<td class="text-center">
											<?php
											$cek_tanggal = date("j-n-Y", strtotime($month . '/' . $i . '/' . $year_sel));
											$cek_tgl2 = strtotime($cek_tanggal);
											$dayw3 = date('l', $cek_tgl2);

											if ($libur == 2) {
												if ($dayw3 == "Sunday" || $dayw3 == "Saturday") { ?>
													<div class="status-pilli blue" data-title="Day Off" data-toggle="tooltip"></div>
												<?php }
											} else {
												if ($dayw3 == "Sunday") { ?>
													<div class="status-pilli blue" data-title="Day Off" data-toggle="tooltip"></div>
												<?php }
											}

											$student_id = $row['student_id'];

											$absens = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}sakolawp_attendance_log WHERE class_id = $class_id AND month = '$month' AND year = '$year_sel' AND section_id = $section_id AND student_id = '$student_id'", ARRAY_A);
											foreach ($absens as $absen) :
												$timestamps = $absen['time_' . $i];
												$dayw2 = date('l', $timestamps);
												$status = $absen['day_' . $i]; ?>

												<?php
												if ($status == 1) { ?>
													<div class="status-pilli green" data-title="Present" data-toggle="tooltip"></div>
												<?php }
												if ($status == 2) { ?>
													<div class="status-pilli red" data-title="Absent" data-toggle="tooltip"></div>
												<?php }
												if ($status == 3) { ?>
													<div class="status-pilli yellow" data-title="Late" data-toggle="tooltip"></div>
												<?php }
												if ($status == 4) { ?>
													<div class="status-pilli transpar" data-title="Sakit" data-toggle="tooltip">S</div>
												<?php }
												if ($status == 5) { ?>
													<div class="status-pilli transpar" data-title="Izin" data-toggle="tooltip">P</div>
												<?php }
												if ($status == 6) { ?>
													<div class="status-pilli" data-title="Day Off" data-toggle="tooltip"></div>
												<?php }
												if ($status == 0 || $status == NULL) { ?>

												<?php } ?>
											</td>
										<?php endforeach;
									} ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<ul class="keterangan-kehadiran">
						<li><span class="status-pilli green" data-title="Present"></span> : <?php esc_html_e('Present', 'sakolawp'); ?></li>
						<li><span class="status-pilli yellow" data-title="Late"></span> : <?php esc_html_e('Late', 'sakolawp'); ?></li>
						<li><span class="status-pilli red" data-title="Absent"></span> : <?php esc_html_e('Absent', 'sakolawp'); ?></li>
						<li data-title="Sakit"><span>S</span> : <?php esc_html_e('Sick', 'sakolawp'); ?></li>
						<li data-title="Izin"><span>P </span> : <?php esc_html_e('Permit', 'sakolawp'); ?></li>
						<li><span class="status-pilli blue" data-title="Absent"></span> : <?php esc_html_e('Day Off', 'sakolawp'); ?></li>
					</ul>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>