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

$exam_limit = 50;
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="dashboard-admin skwp-content-inner">

	<nav class="skwp-tabs-menu">
		<div class="nav nav-tabs" id="nav-tab" role="tablist">
			<div class="skwp-logo">
				<img src="<?php echo plugin_dir_url(__DIR__); ?>img/swp-logo.png" alt="<?php echo esc_attr('Sakola Logo'); ?>">
			</div>
			<a class="nav-item nav-link active" id="nav-dashboard-tab" data-toggle="tab" href="#nav-dashboard" role="tab" aria-controls="nav-home" aria-selected="true"><?php esc_html_e('Dashboard', 'sakolawp'); ?></a>
			<a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true"><?php esc_html_e('Main Setting', 'sakolawp'); ?></a>
			<a class="nav-item nav-link" id="nav-semester-tab" data-toggle="tab" href="#nav-semester" role="tab" aria-controls="nav-semester" aria-selected="true"><?php esc_html_e('Semester Periode', 'sakolawp'); ?></a>
		</div>
	</nav>
	
	<div class="admin-home-dashboard skwp-tab-content tab-content" id="nav-tabContent">

		<div class="admin-home-dashboard-spec tab-pane fade show active" id="nav-dashboard" role="tabpanel" aria-labelledby="nav-dashboard-tab">
			<div class="admin-information skwp-clearfix">
				<div class="admin-welcome admin-dash-grid-area">
					<div class="admin-welcome-inner admin-dash-grid-item skwp-clearfix">
						<?php 
						global $wp;
						$current_id = get_current_user_id();
						$user_info = get_user_meta($current_id);
						$first_name = $user_info["first_name"][0];
						$last_name = $user_info["last_name"][0];

						$user_name = $first_name .' '. $last_name;

						if(empty($first_name)) {
							$user_info = get_userdata($current_id);
							$user_name = $user_info->display_name;
						} ?>
						<div class="skwp-user-img">
							<?php 
							$user_img = wp_get_attachment_image_src( get_user_meta($current_id,'_user_img', array('80','80'), true, true ));
							if(!empty($user_img)) { ?>
								<img class="profile_img" src="<?php echo esc_url($user_img[0]); ?>" alt="<?php echo esc_attr($user_name); ?>">
							<?php }
							else {
								echo get_avatar( $current_id, 80 );
							} ?>
						</div>
						<div class="skwp-admin-info">
							<h2 class="welcome-user"><?php esc_html_e('Hello, ', 'sakolawp'); ?><?php echo esc_html( $user_name ); ?></h2>
							<h4 class="welcome-txt"><?php esc_html_e('Welcome Back', 'sakolawp'); ?></h4>
						</div>
					</div>

					<div class="teacher-counter admin-dash-grid-item skwp-user-counter-item skwp-clearfix">
						<?php
							$teacher_query = new WP_User_Query( array( 'role' => 'teacher' ) );
							$teacher_count = (int) $teacher_query->get_total();
						?>
						<div class="skwp-role-info">
							<h2 class="user-item-count"><?php echo esc_html($teacher_count); ?></h2>
							<h4 class="user-count-role"><?php esc_html_e('Teachers', 'sakolawp'); ?></h4>
						</div>
					</div>
					<div class="student-counter admin-dash-grid-item skwp-user-counter-item skwp-clearfix">
						<?php
							$student_query = new WP_User_Query( array( 'role' => 'student' ) );
							$student_count = (int) $student_query->get_total();
						?>
						<div class="skwp-role-info">
							<h2 class="user-item-count"><?php echo esc_html($student_count); ?></h2>
							<h4 class="user-count-role"><?php esc_html_e('Students', 'sakolawp'); ?></h4>
						</div>
					</div>
					<div class="parent-counter admin-dash-grid-item skwp-user-counter-item skwp-clearfix">
						<?php
							$parent_query = new WP_User_Query( array( 'role' => 'parent' ) );
							$parent_count = (int) $parent_query->get_total();
						?>
						<div class="skwp-role-info">
							<h2 class="user-item-count"><?php echo esc_html($parent_count); ?></h2>
							<h4 class="user-count-role"><?php esc_html_e('Parents', 'sakolawp'); ?></h4>
						</div>
					</div>
				</div>
			</div>

			<!-- sakolawp card -->
			<div class="skwp-card-info-wrap skwp-clearfix">
				<div class="skwp-dash-widget-area">
					<div class="skwp-info-card skwp-exam-card">
						<div class="skwp-card-inner">
							<h2 class="card-title"><?php esc_html_e('SakolaWP Stats', 'sakolawp'); ?></h2>
							<!-- start of class table -->
							<div class="table-responsive">
								<table id="dataTable1" width="100%" class="table table-striped table-lightfont">
									<thead>
										<tr>
											<th>
												<?php esc_html_e('Item Name', 'sakolawp'); ?>
											</th>
											<th>
												<?php esc_html_e('Total', 'sakolawp'); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										global $wpdb;
										$total_exams = $wpdb->get_results("SELECT exam_code FROM {$wpdb->prefix}sakolawp_exams", ARRAY_A); 
										$total_total_exams = $wpdb->num_rows; ?>
										<tr <?php if($total_total_exams >= $exam_limit) {?>class="disabled"<?php } ?>>
											<td>
												<?php esc_html_e('Exams Created', 'sakolawp'); ?>
											</td>
											<td>
												<?php echo esc_html($total_total_exams).esc_html('/50'); ?>
											</td>
										</tr>
										<tr <?php if($total_total_exams >= $exam_limit) {?>class="disabled"<?php } ?>>
											<td>
												<?php esc_html_e('Exams Taken By Student', 'sakolawp'); ?>
											</td>
											<td>
												<?php 
													global $wpdb;
													$total_exams_done = $wpdb->get_results("SELECT exam_code FROM {$wpdb->prefix}sakolawp_student_answer", ARRAY_A); 
													$total_total_exams_done = $wpdb->num_rows;

													echo esc_html($total_total_exams_done); 
												?>
											</td>
										</tr>
										<tr>
											<td>
												<?php esc_html_e('Homeworks Created', 'sakolawp'); ?>
											</td>
											<td>
												<a href="<?php echo esc_url('https://1.envato.market/D7AL2'); ?>">
													<?php 
														esc_html_e('Pro Feature', 'sakolawp');
													?>
												</a>
											</td>
										</tr>
										<tr>
											<td>
												<?php esc_html_e('Homeworks Taken By Student', 'sakolawp'); ?>
											</td>
											<td>
												<a href="<?php echo esc_url('https://1.envato.market/D7AL2'); ?>">
													<?php 
														esc_html_e('Pro Feature', 'sakolawp');
													?>
												</a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<!-- end of class table -->
						</div>
					</div>

					<div class="skwp-info-card skwp-exam-card">
						<div class="skwp-card-inner">
							<h2 class="card-title"><?php esc_html_e('Latest Exams', 'sakolawp'); ?></h2>
							<!-- start of class table -->
							<div class="table-responsive">
								<table id="ujian-online" width="100%" class="table table-lightborder table-lightfont">
									<thead>
										<tr>
											<th><?php echo esc_html__('Title', 'sakolawp'); ?></th>
											<th class="text-center"><?php echo esc_html__('Class', 'sakolawp'); ?></th>
											<th><?php echo esc_html__('Subject', 'sakolawp'); ?></th>
											<th><?php echo esc_html__('Start', 'sakolawp'); ?></th>
											<th><?php echo esc_html__('Due', 'sakolawp'); ?></th>
											<th class="text-center"><?php echo esc_html__('Select', 'sakolawp'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$post = $wpdb->get_results("SELECT exam_id, title, class_id, section_id, subject_id, availablefrom, clock_start, availableto, clock_end, exam_code FROM {$wpdb->prefix}sakolawp_exams ORDER BY exam_id DESC LIMIT 5", ARRAY_A);
										$total_exams = $wpdb->num_rows;
										foreach ($post as $row) :
										?>
											<tr>
												<td class="tes">
													<?php echo esc_html($row['title']); ?>
												</td>
												<td>
													<?php
													$class_id = $row['class_id'];
													$section_id = $row['section_id'];
													$class = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id");
													echo esc_html($class->name);

													echo esc_html__(' - ', 'sakolawp');

													$section = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $section_id");
													echo esc_html($section->name);
													?>
												</td>
												<td>
													<?php $subject_id = $row['subject_id'];
													$subject = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = $subject_id");
													echo esc_html($subject->name);
													?>
												</td>
												<td>
													<?php echo esc_html($row['availablefrom']); ?>
													<?php echo esc_html($row['clock_start']); ?>
												</td>
												<td>
													<?php echo esc_html($row['availableto']); ?>
													<?php echo esc_html($row['clock_end']); ?>
												</td>
												<td class="skwp-row-actions">
													<a class="btn skwp-btn btn-sm btn-success" href="<?php echo add_query_arg(array('exam_code' => esc_html($row['exam_code']), 'action' => 'exam_room'), admin_url('admin.php?page=sakolawp-exam')); ?>">
														<span><?php echo esc_html__('View', 'sakolawp'); ?></span>
													</a>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>

							<?php if($total_exams >= $exam_limit) { ?>
							<div class="dash-info">
								<p><?php echo esc_html__('Exams reach the limit, buy PRO version to create unlimited exams.', 'sakolawp'); ?></p>
								<a class="btn skwp-btn btn-sm btn-primary" href="<?php echo esc_url('https://1.envato.market/D7AL2'); ?>">
									<span><?php echo esc_html__('Buy Now', 'sakolawp'); ?></span>
								</a>
							</div>
							<?php } ?>
							<!-- end of class table -->
						</div>
					</div>
				</div>
			</div>
			<!-- sakolawp card -->

		</div>

		<div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

			<?php settings_errors(); ?>
			<form method="POST" action="options.php">
				<?php
				settings_fields('sakolawp_general_settings');
				do_settings_sections('sakolawp_general_settings');
				?>
				<input type="submit" value="<?php echo esc_attr__('Save Changes', 'sakolawp'); ?>" class="add-new-semes">
			</form>

			<div class="shortcode-list">
				<h2><?php esc_html_e('List of Shortcodes', 'sakolawp'); ?></h2>
				<p><?php esc_html_e('You can use this shortcode and copy to any pages with shortcode block. Read our online documentation for more details.', 'sakolawp'); ?></p>
				<div class="shortcode-item">
					<h4 class="title-shortcode"><?php esc_html_e('My Account', 'sakolawp'); ?></h4>
					<code><?php echo esc_html('[sakolawp_myaccount_shortcodes]'); ?></code>
				</div>
				<div class="shortcode-item">
					<h4 class="title-shortcode"><?php esc_html_e('Register Form', 'sakolawp'); ?></h4>
					<code><?php echo esc_html('[sakolawp_register_shortcodes]'); ?></code>
				</div>
				<div class="shortcode-item">
					<h4 class="title-shortcode"><?php esc_html_e('Login Form', 'sakolawp'); ?></h4>
					<code><?php echo esc_html('[sakolawp_login_shortcodes]'); ?></code>
				</div>
			</div>
		</div>
		<div class="tab-pane fade" id="nav-semester" role="tabpanel" aria-labelledby="nav-semester-tab">

			<a class="add-new-semes" href="<?php echo add_query_arg(array('create' => 'create'), admin_url('admin.php?page=sakolawp-settings')); ?>"><?php esc_html_e('Add New', 'sakolawp'); ?></a>

			<!-- start of class table -->
			<div class="table-responsive">
				<table id="dataTable1" width="100%" class="table table-striped table-lightfont">
					<thead>
						<tr>
							<th>
								<?php esc_html_e('Semester', 'sakolawp'); ?>
							</th>
							<th>
								<?php esc_html_e('Start', 'sakolawp'); ?>
							</th>
							<th>
								<?php esc_html_e('End', 'sakolawp'); ?>
							</th>
							<th class="text-center">
								<?php esc_html_e('Action', 'sakolawp'); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						global $wpdb;
						$skwp_exams = $wpdb->get_results("SELECT exam_id, start_exam, end_exam, name FROM {$wpdb->prefix}sakolawp_exam", ARRAY_A);
						foreach ($skwp_exams as $exam) :

							if ($exam['start_exam'] == 1)
								$start_exam = esc_html__('January', 'sakolawp');
							else if ($exam['start_exam'] == 2)
								$start_exam = esc_html__('February', 'sakolawp');
							else if ($exam['start_exam'] == 3)
								$start_exam = esc_html__('March', 'sakolawp');
							else if ($exam['start_exam'] == 4)
								$start_exam = esc_html__('April', 'sakolawp');
							else if ($exam['start_exam'] == 5)
								$start_exam = esc_html__('May', 'sakolawp');
							else if ($exam['start_exam'] == 6)
								$start_exam = esc_html__('June', 'sakolawp');
							else if ($exam['start_exam'] == 7)
								$start_exam = esc_html__('July', 'sakolawp');
							else if ($exam['start_exam'] == 8)
								$start_exam = esc_html__('August', 'sakolawp');
							else if ($exam['start_exam'] == 9)
								$start_exam = esc_html__('September', 'sakolawp');
							else if ($exam['start_exam'] == 10)
								$start_exam = esc_html__('October', 'sakolawp');
							else if ($exam['start_exam'] == 11)
								$start_exam = esc_html__('November', 'sakolawp');
							else if ($exam['start_exam'] == 12)
								$start_exam = esc_html__('December', 'sakolawp');

							if ($exam['end_exam'] == 1)
								$end_exam = esc_html__('January', 'sakolawp');
							else if ($exam['end_exam'] == 2)
								$end_exam = esc_html__('February', 'sakolawp');
							else if ($exam['end_exam'] == 3)
								$end_exam = esc_html__('March', 'sakolawp');
							else if ($exam['end_exam'] == 4)
								$end_exam = esc_html__('April', 'sakolawp');
							else if ($exam['end_exam'] == 5)
								$end_exam = esc_html__('May', 'sakolawp');
							else if ($exam['end_exam'] == 6)
								$end_exam = esc_html__('June', 'sakolawp');
							else if ($exam['end_exam'] == 7)
								$end_exam = esc_html__('July', 'sakolawp');
							else if ($exam['end_exam'] == 8)
								$end_exam = esc_html__('August', 'sakolawp');
							else if ($exam['end_exam'] == 9)
								$end_exam = esc_html__('September', 'sakolawp');
							else if ($exam['end_exam'] == 10)
								$end_exam = esc_html__('October', 'sakolawp');
							else if ($exam['end_exam'] == 11)
								$end_exam = esc_html__('November', 'sakolawp');
							else if ($exam['end_exam'] == 12)
								$end_exam = esc_html__('December', 'sakolawp');
						?>
							<tr>
								<td>
									<?php echo esc_html($exam['name']); ?>
								</td>
								<td>
									<?php echo esc_html($start_exam); ?>
								</td>
								<td>
									<?php echo esc_html($end_exam); ?>
								</td>
								<td>
									<a class="btn skwp-btn btn-sm btn-primary" href="<?php echo add_query_arg(array('edit' => intval($exam['exam_id'])), admin_url('admin.php?page=sakolawp-settings')); ?>">
										<i class="os-icon picons-thin-icon-thin-0056_bin_trash_recycle_delete_garbage_empty"></i>
										<?php esc_html_e('Edit', 'sakolawp'); ?>
									</a>
									<a class="btn skwp-btn btn-sm btn-danger" href="<?php echo add_query_arg(array('delete' => intval($exam['exam_id'])), admin_url('admin.php?page=sakolawp-settings')); ?>">
										<i class="os-icon picons-thin-icon-thin-0056_bin_trash_recycle_delete_garbage_empty"></i>
										<?php esc_html_e('Delete', 'sakolawp'); ?>
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
			<!-- end of class table -->
		</div>
	</div>
</div>