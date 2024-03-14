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
<div class="manage-outine skwp-content-inner">


	<nav class="skwp-tabs-menu">
		<div class="nav nav-tabs" id="nav-tab" role="tablist">
			<div class="skwp-logo">
				<img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'img/swp-logo.png'); ?>" alt="<?php echo esc_attr('Sakola Logo'); ?>">
			</div>
		</div>
	</nav>
	</nav>

	<div class="skwp-row skwp-tab-content skwp-clearfix">
		<form id="myForm" name="save_student_attendance" action="" method="POST" class="skwp-column skwp-column-75">
			<div class="skwp-row skwp-clearfix">
				<div class="skwp-column skwp-column-75">
					<label class="gi" for=""><?php esc_html_e('Class :', 'sakolawp'); ?></label>
					<select class="skwp-form-control" name="class_id">
						<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
						<?php $cl = $wpdb->get_results("SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", ARRAY_A);
						foreach ($cl as $row) :
						?>
							<option value="<?php echo esc_attr($row['class_id']); ?>"><?php echo esc_html($row['name']); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="skwp-column skwp-column-4">
					<button class="btn skwp-btn btn-rounded btn-primary" style="margin-top:20px" type="submit" value="submit" name="submit"><?php esc_html_e('Apply', 'sakolawp'); ?></button>
				</div>
			</div>
		</form>
		<div class="skwp-column skwp-column-4">
			<div class="text-right"><button class="btn skwp-btn btn-rounded btn-primary" data-target="#exampleModal1" data-toggle="modal" type="button"><?php esc_html_e('Add New', 'sakolawp'); ?></button></div>
		</div>
	</div>

	<div class="skwp-tabs-menu">
		<ul class="nav nav-tabs">

			<?php
			if (isset($_POST['submit'])) {
				$class_id = sanitize_text_field($_POST['class_id']);
				$sections = $wpdb->get_results("SELECT section_id, class_id, name FROM {$wpdb->prefix}sakolawp_section WHERE class_id = $class_id", OBJECT);
			} else {
				$class = $wpdb->get_row("SELECT class_id FROM {$wpdb->prefix}sakolawp_class", ARRAY_A);
				$class_id = esc_html($class['class_id']);
				if(!empty($class_id)) {
					$sections = $wpdb->get_results("SELECT section_id, class_id, name FROM {$wpdb->prefix}sakolawp_section WHERE class_id = $class_id", OBJECT);
				}
			}
			if(!empty($sections)) {
				foreach ($sections as $rows) : ?>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#rombel-<?php echo esc_attr($rows->section_id); ?>"><?php esc_html_e('Section', 'sakolawp'); ?> <?php echo esc_html($rows->name); ?></a>
					</li>
				<?php endforeach; 
			} ?>
		</ul>
	</div>

	<div class="skwp-tab-content tab-content">
		<?php
		if (isset($_POST['submit'])) {
			$class_id = sanitize_text_field($_POST['class_id']);
			$query = $wpdb->get_results("SELECT section_id, class_id, name FROM {$wpdb->prefix}sakolawp_section WHERE class_id = $class_id", OBJECT);
		} else {
			$class = $wpdb->get_row("SELECT class_id FROM {$wpdb->prefix}sakolawp_class", ARRAY_A);
			$class_id = esc_html($class['class_id']);
			if(!empty($class_id)) {
				$query = $wpdb->get_results("SELECT section_id, class_id, name FROM {$wpdb->prefix}sakolawp_section WHERE class_id = $class_id", OBJECT);
			}
		}
		if ($wpdb->num_rows > 0) :
			$sections = $query;
			foreach ($sections as $row) : ?>

				<div class="tab-pane" id="rombel-<?php echo esc_attr($row->section_id); ?>">
					<div class="element-wrapper">
						<div class="element-box table-responsive lined-primary shadow">
							<div class="row m-b">
								<div style="padding-left:20px;display:inline-block;">
									<p>
										<?php
										$class_name = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $row->class_id");
										echo esc_html($class_name->name); ?><br><?php esc_html_e('Section', 'sakolawp'); ?>
										<?php echo esc_html($row->name); ?>
									</p>
								</div>
							</div>

							<table class="table table-bordered table-schedule table-hover" cellpadding="0" cellspacing="0" width="100%">
								<?php
								$days = get_option('sakolawp_routine');
								if(!empty($days)) {
									$days = $days;
								} else {
									$days = 1;
								}
								if ($days == 2) {
									$nday = 6;
								} else {
									$nday = 7;
								}
								for ($d = $days; $d <= $nday; $d++) :
									if($d==1) { 
										$day = esc_html('Sunday'); 
										$day2 = esc_html__('Sun', 'sakolawp'); 
									}
									elseif($d==2) { 
										$day = esc_html('Monday');
										$day2 = esc_html__('Mon', 'sakolawp'); 
									}
									elseif($d==3) {
										$day = esc_html('Tuesday');
										$day2 = esc_html__('Tue', 'sakolawp');
									}
									elseif($d==4) {
										$day = esc_html('Wednesday'); 
										$day2 = esc_html__('Wed', 'sakolawp');
									}
									elseif($d==5) {
										$day = esc_html('Thursday'); 
										$day2 = esc_html__('Thr', 'sakolawp');
									}
									elseif($d==6) {
										$day = esc_html('Friday'); 
										$day2 = esc_html__('Fri', 'sakolawp');
									}
									elseif($d==7) {
										$day = esc_html('Saturday'); 
										$day2 = esc_html__('Sat', 'sakolawp');
									}
								?>
									<tr>
										<table class="table table-schedule table-hover" cellpadding="0" cellspacing="0">
											<td width="120" height="100" style="text-align: center;"><strong><?php echo esc_html($day2); ?></strong></td>
											<?php

											$routines = $wpdb->get_results("SELECT subject_id, class_routine_id, time_start_min, time_end_min, time_start, time_end FROM {$wpdb->prefix}sakolawp_class_routine 
											WHERE day = '$day' 
											AND class_id = $row->class_id  
											AND section_id = $row->section_id  
											AND year = '$running_year'
											ORDER BY time_start ASC", ARRAY_A);

											if(!empty($row->class_id)) {
												foreach ($routines as $row2) :
													$sub_id = $row2['subject_id'];
													$teacher = $wpdb->get_row("SELECT teacher_id FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = $sub_id");

													$subject = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = $sub_id");
												?>
													<td style="text-align:center">
														<div class="pi-controls" style="text-align:right;">
															<div class="pi-settings os-dropdown-trigger">
																<i class="os-icon picons-thin-icon-thin-0069a_menu_hambuger"></i>
																<div class="os-dropdown">
																	<div class="icon-w">
																		<i class="os-icon picons-thin-icon-thin-0069a_menu_hambuger"></i>
																	</div>
																	<ul>
																		<li>
																			<a class="btn danger skwp-btn btn-rounded btn-warning" href="<?php echo add_query_arg(array('edit' => intval($row2['class_routine_id'])), admin_url('admin.php?page=sakolawp-manage-routine')); ?>"><i class="os-icon picons-thin-icon-thin-0001_compose_write_pencil_new"></i><span><?php esc_html_e('Edit', 'sakolawp'); ?></span></a>
																		</li>
																		<li>
																			<a class="btn skwp-btn btn-rounded btn-danger" href="<?php echo add_query_arg(array('delete' => intval($row2['class_routine_id'])), admin_url('admin.php?page=sakolawp-manage-routine')); ?>"><i class="os-icon picons-thin-icon-thin-0056_bin_trash_recycle_delete_garbage_empty"></i><span><?php esc_html_e('Delete', 'sakolawp'); ?></span></a>
																		</li>
																	</ul>
																</div>
															</div>
														</div>
														<?php
														if ($row2['time_start_min'] == 0 && $row2['time_end_min'] == 0)
															echo esc_html($row2['time_start']) . ':' . esc_html($row2['time_start_min']) . '-' . esc_html($row2['time_end']) . ':' . esc_html($row2['time_end_min']);
														if ($row2['time_start_min'] != 0 || $row2['time_end_min'] != 0)
															echo esc_html($row2['time_start']) . ':' . esc_html($row2['time_start_min']) . '-' . esc_html($row2['time_end']) . ':' . esc_html($row2['time_end_min']);
														?>
														<br>
														<b><?php echo esc_html($subject->name); ?></b>
														<br>
														<small><?php
																$user_info = get_userdata($teacher->teacher_id);
																echo esc_html($user_info->display_name); ?></small>
														<br><br>
													</td>

												<?php endforeach; 
											} ?>
										</table>
									</tr>
								<?php endfor; ?>
							</table>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="exampleModal1" role="dialog" tabindex="-1">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">
						<?php esc_html_e('Add New Class Routine', 'sakolawp'); ?>
					</h5>
					<button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
				</div>
				<div class="modal-body">
					<form id="myForm" name="myform" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
						<input type="hidden" name="action" value="save_routine_setting" />
						<div class="skwp-form-group skwp-row skwp-clearfix">
							<div class="skwp-column skwp-column-1">
								<label for=""> <?php esc_html_e('Class', 'sakolawp'); ?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
									</div>
									<select class="skwp-form-control" name="class_id" id="class_holder" required="">
										<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
										<?php $cl = $wpdb->get_results("SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", ARRAY_A);
										foreach ($cl as $row) :
										?>
											<option value="<?php echo esc_attr($row['class_id']); ?>"><?php echo esc_html($row['name']); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="skwp-form-group skwp-row skwp-clearfix">
							<div class="skwp-column skwp-column-1">
								<label for=""> <?php esc_html_e('Section', 'sakolawp'); ?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
									</div>
									<select class="skwp-form-control" name="section_id" required="" id="section_holder">
										<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
									</select>
								</div>
							</div>
						</div>
						<div class="skwp-form-group skwp-row skwp-clearfix">
							<div class="skwp-column skwp-column-1">
								<label for=""> <?php esc_html_e('Subject', 'sakolawp'); ?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
									</div>
									<select class="skwp-form-control" name="subject_id" required="" id="subject_holder">
										<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
									</select>
								</div>
							</div>
						</div>
						<div class="skwp-form-group skwp-row skwp-clearfix">
							<div class="skwp-column skwp-column-1">
								<label for=""> <?php esc_html_e('Day', 'sakolawp'); ?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="picons-thin-icon-thin-0024_calendar_month_day_planner_events"></i>
									</div>
									<select name="day" class="skwp-form-control" required="">
										<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
										<?php
										$days = 1;
										if ($days == 1) : ?>
											<option value="Sunday"><?php esc_html_e('Sunday', 'sakolawp'); ?></option>
										<?php endif; ?>
										<option value="Monday"><?php esc_html_e('Monday', 'sakolawp'); ?></option>
										<option value="Tuesday"><?php esc_html_e('Tuesday', 'sakolawp'); ?></option>
										<option value="Wednesday"><?php esc_html_e('Wednesday', 'sakolawp'); ?></option>
										<option value="Thursday"><?php esc_html_e('Thursday', 'sakolawp'); ?></option>
										<option value="Friday"><?php esc_html_e('Friday', 'sakolawp'); ?></option>
										<?php if ($days == 1) : ?>
											<option value="Saturday"><?php esc_html_e('Saturday', 'sakolawp'); ?></option>
										<?php endif; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="skwp-form-group skwp-row skwp-clearfix">
							<div class="skwp-column skwp-column-1">
								<label for=""> <?php esc_html_e('Time Start', 'sakolawp'); ?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="picons-thin-icon-thin-0029_time_watch_clock_wall"></i>
									</div>
									<select name="time_start" class="skwp-form-control" required>
										<option value=""><?php esc_html_e('Hour', 'sakolawp'); ?></option>
										<?php for ($i = 0; $i <= 12; $i++) : ?>
											<option value="<?php echo esc_attr($i); ?>"><?php echo esc_html($i); ?></option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
							<div class="skwp-column skwp-column-1">
								<div class="input-group">
									<select name="time_start_min" class="skwp-form-control" required>
										<option value=""><?php esc_html_e('Minute', 'sakolawp'); ?></option>
										<?php for ($i = 0; $i <= 11; $i++) : ?>
											<option value="<?php $n = $i * 5;
															if ($n < 10) echo '0' . esc_attr($n);
															else echo esc_attr($n); ?>"><?php $n = $i * 5;
																				if ($n < 10) echo '0' . esc_html($n);
																				else echo esc_html($n); ?></option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
							<div class="skwp-column skwp-column-1">
								<div class="input-group">
									<select class="skwp-form-control" name="starting_ampm" required>
										<option value="1"><?php esc_html_e('AM', 'sakolawp'); ?></option>
										<option value="2"><?php esc_html_e('PM', 'sakolawp'); ?></option>
									</select>
								</div>
							</div>
						</div>
						<div class="skwp-form-group skwp-row skwp-clearfix">
							<div class="skwp-column skwp-column-1">
								<label for=""> <?php esc_html_e('Time End', 'sakolawp'); ?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="picons-thin-icon-thin-0029_time_watch_clock_wall"></i>
									</div>
									<select name="time_end" class="skwp-form-control" required>
										<option value=""><?php esc_html_e('Hour', 'sakolawp'); ?></option>
										<?php for ($i = 0; $i <= 12; $i++) : ?>
											<option value="<?php echo esc_attr($i); ?>"><?php echo esc_html($i); ?></option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
							<div class="skwp-column skwp-column-1">
								<div class="input-group">
									<select name="time_end_min" class="skwp-form-control" required>
										<option value=""><?php esc_html_e('Minute', 'sakolawp'); ?></option>
										<?php for ($i = 0; $i <= 11; $i++) : ?>
											<option value="<?php $n = $i * 5;
															if ($n < 10) echo '0' . esc_attr($n);
															else echo esc_attr($n); ?>"><?php $n = $i * 5;
																				if ($n < 10) echo '0' . esc_html($n);
																				else echo esc_html($n); ?></option>
										<?php endfor; ?>
									</select>
								</div>
							</div>
							<div class="skwp-column skwp-column-1">
								<div class="input-group">
									<select class="skwp-form-control" required="" name="ending_ampm">
										<option value="1"><?php esc_html_e('AM', 'sakolawp'); ?></option>
										<option value="2"><?php esc_html_e('PM', 'sakolawp'); ?></option>
									</select>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary skwp-btn" type="submit"> <?php esc_html_e('Save', 'sakolawp'); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</div>