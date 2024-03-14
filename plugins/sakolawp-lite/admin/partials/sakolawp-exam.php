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
<div class="exam-page skwp-content-inner">


	<nav class="skwp-tabs-menu">
		<div class="nav nav-tabs" id="nav-tab" role="tablist">
			<div class="skwp-logo">
				<img src="<?php echo esc_url(plugin_dir_url(__DIR__) . 'img/swp-logo.png'); ?>" alt="<?php echo esc_attr('Sakola Logo'); ?>">
			</div>
			<a class="nav-item nav-link active" href="admin.php?page=sakolawp-exam"><?php esc_html_e('Exams', 'sakolawp'); ?></a>

		</div>
	</nav>

	<div class="skwp-tab-content tab-content" id="nav-tabContent">
		<?php if (!isset($_GET['exam_code'])) { ?>
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
						$post = $wpdb->get_results("SELECT title, class_id, section_id, subject_id, availablefrom, clock_start, availableto, clock_end, exam_code FROM {$wpdb->prefix}sakolawp_exams", ARRAY_A);
						$total_exams = $wpdb->num_rows;
						foreach ($post as $row) :
						?>
							<tr>
								<td class="tes">
									<?php echo esc_html($row['title']); ?>
								</td>
								<td>
									<?php
										$class_id = esc_html($row['class_id']);
										$section_id = esc_html($row['section_id']);
										$class = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id");
										echo esc_html($class->name);

										echo esc_html__(' - ', 'sakolawp');

										$section = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $section_id");
										echo esc_html($section->name);
									?>
								</td>
								<td>
									<?php $subject_id = esc_html($row['subject_id']);
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

			<?php if($total_exams >= 50) { ?>
			<div class="dash-info">
				<p><?php echo esc_html__('Exams reach the limit, buy PRO version to create unlimited exams.', 'sakolawp'); ?></p>
				<a class="btn skwp-btn btn-sm btn-primary" href="<?php echo esc_url('https://1.envato.market/D7AL2'); ?>">
					<span><?php echo esc_html__('Buy Now', 'sakolawp'); ?></span>
				</a>
			</div>
			<?php } ?>
			<!-- end of class table -->
		<?php } ?>

		<?php if (isset($_GET['exam_code'])) {
			$action = sanitize_text_field($_GET['action']);
			$exam_code = sanitize_text_field($_GET['exam_code']); ?>
			<div class="skwp-tabs-menu">
				<ul class="nav nav-tabs upper">
					<li class="nav-item">
						<a class="nav-link <?php if ($action == 'exam_room') {
												echo esc_attr( 'active' );
											} ?>" href="<?php echo add_query_arg(array('exam_code' => esc_html($exam_code), 'action' => 'exam_room'), admin_url('admin.php?page=sakolawp-exam')); ?>"><i class="os-icon picons-thin-icon-thin-0016_bookmarks_reading_book"></i><span><?php echo esc_html__('Exam Detail', 'sakolawp'); ?></span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?php if ($action == 'exam_questions') {
												echo esc_attr( 'active' );
											} ?>" href="<?php echo add_query_arg(array('exam_code' => esc_html($exam_code), 'action' => 'exam_questions'), admin_url('admin.php?page=sakolawp-exam')); ?>"><i class="os-icon picons-thin-icon-thin-0067_line_thumb_view"></i><span><?php echo esc_html__('Questions', 'sakolawp'); ?></span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?php if ($action == 'exam_results') {
												echo esc_attr( 'active' );
											} ?>" href="<?php echo add_query_arg(array('exam_code' => esc_html($exam_code), 'action' => 'exam_results'), admin_url('admin.php?page=sakolawp-exam')); ?>"><i class="os-icon picons-thin-icon-thin-0100_to_do_list_reminder_done"></i><span><?php echo esc_html__('Exam Result', 'sakolawp'); ?></span></a>
					</li>
				</ul>
			</div>

			<!-- IF EXAM ROOM -->
			<?php if ($action == 'exam_room') {

				$exam = $wpdb->get_results("SELECT title, class_id, section_id, subject_id, availablefrom, clock_start, availableto, clock_end, exam_code, pass, questions, duration, description  FROM {$wpdb->prefix}sakolawp_exams WHERE exam_code = '$exam_code'", ARRAY_A);
				foreach ($exam as $row) :
			?>
					<div class="back hidden-sm-down">
						<a href="<?php echo esc_url(admin_url('admin.php?page=sakolawp-exam')); ?>"><?php echo esc_html__('Back', 'sakolawp'); ?></a>
					</div>
					<div class="skwp-row skwp-clearfix">
						<div class="skwp-column skwp-column-75">
							<div class="exam-info-title">
								<h5>
									<?php echo esc_html($row['title']); ?>
								</h5>
							</div>
							<p>
								<?php echo esc_html($row['description']); ?>
							</p>
						</div>

						<div class="skwp-column skwp-column-4">
							<div class="exam-info-wrap">
								<div class="exam-info-head">
									<h3 class="skwp-header-form">
										<?php echo esc_html__('Exam Information', 'sakolawp'); ?>
									</h3>
								</div>
								<div class="table-responsive">
									<table class="table table-lightbor text-left table-lightfont">
										<tr>
											<th>
												<?php echo esc_html__('Subject', 'sakolawp'); ?>
											</th>
											<td>
												<?php $subject_id = esc_html($row['subject_id']);
												$subject = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = $subject_id");
												echo esc_html($subject->name);
												?>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Class', 'sakolawp'); ?>
											</th>
											<td>
												<?php
												$class_id = esc_html($row['class_id']);
												$section_id = esc_html($row['section_id']);
												$class = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id");
												echo esc_html($class->name);

												echo esc_html__(' - ', 'sakolawp');

												$section = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $section_id");
												echo esc_html($section->name); ?>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Start Date', 'sakolawp'); ?>
											</th>
											<td>
												<?php echo esc_html($row['availablefrom']); ?> - <?php echo esc_html($row['clock_start']); ?>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Due Date', 'sakolawp'); ?>
											</th>
											<td>
												<?php echo esc_html($row['availableto']); ?> - <?php echo esc_html($row['clock_end']); ?>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Minimum Mark', 'sakolawp'); ?>
											</th>
											<td>
												<a class="btn btn-rounded btn-sm btn-primary skwp-btn"><?php echo esc_html($row['pass']); ?></a>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Total Questions', 'sakolawp'); ?>
											</th>
											<td>
												<a class="btn btn-rounded btn-sm btn-secondary skwp-btn"><?php echo esc_html($row['questions']); ?></a>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Duration', 'sakolawp'); ?>
											</th>
											<td>
												<a class="btn btn-rounded btn-sm btn-success skwp-btn"><?php echo esc_html($row['duration']); ?> <?php echo esc_html__('minutes', 'sakolawp'); ?></a>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
			<?php endforeach;
			} ?>
			<!-- IF EXAM ROOM -->

			<!-- IF EXAM QUESTIONS -->
			<?php if ($action == 'exam_questions') { ?>

				<?php
				$exam = $wpdb->get_results("SELECT subject_id, class_id, section_id, availablefrom, clock_start, availableto, clock_end, pass, questions, duration FROM {$wpdb->prefix}sakolawp_exams WHERE exam_code = '$exam_code'", ARRAY_A);
				foreach ($exam as $row) :
				?>
					<div class="back hidden-sm-down">
						<a href="<?php echo esc_url(admin_url('admin.php?page=sakolawp-exam')); ?>"><?php echo esc_html__('Back', 'sakolawp'); ?></a>
					</div>
					<div class="skwp-row skwp-clearfix">
						<div class="skwp-column skwp-column-75">
							<div class="exam-questions">
								<h5><?php echo esc_html__('Question', 'sakolawp'); ?></h5>
							</div>
							<div class="table-responsive">
								<table id="tableini" class="table table-lightborder list-pertanyaan-ujian">
									<thead>
										<tr>
											<th>#</th>
											<th><?php echo esc_html__('Question', 'sakolawp'); ?></th>
											<th class="text-center"><?php echo esc_html__('Answer', 'sakolawp'); ?></th>
											<th class="text-center"><?php echo esc_html__('Mark', 'sakolawp'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$n = 1;
										$ques = $wpdb->get_results("SELECT question_excerpt, question, correct_answer, optiona, optionb, optionc, optiond, marks FROM {$wpdb->prefix}sakolawp_questions WHERE exam_code = '$exam_code'", ARRAY_A);
										foreach ($ques as $row1) : ?>
											<tr>
												<td>
													<?php echo esc_html($n++); ?>
												</td>
												<td>
													<?php if (!empty($row1['question_excerpt'])) {
														echo esc_html($row1['question_excerpt']);
													} else {
														echo esc_html($row1['question']);
													} ?>
												</td>
												<td class="text-center">
													<?php
													if ($row1['optiona'] == $row1['correct_answer']) { ?>A
													<?php }
													if ($row1['optionb'] == $row1['correct_answer']) { ?>B
													<?php }
													if ($row1['optionc'] == $row1['correct_answer']) { ?>C
													<?php }
													if ($row1['optiond'] == $row1['correct_answer']) { ?>D
												<?php } ?>
												</td>
												<td class="text-center">
													<?php echo intval(round($row1['marks'], 2)); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>

						<div class="skwp-column skwp-column-4">
							<div class="exam-info-wrap">
								<div class="exam-info-head">
									<h3 class="skwp-header-form">
										<?php echo esc_html__('Exam Information', 'sakolawp'); ?>
									</h3>
								</div>
								<div class="table-responsive">
									<table class="table table-lightbor text-left table-lightfont">
										<tr>
											<th>
												<?php echo esc_html__('Subject', 'sakolawp'); ?>
											</th>
											<td>
												<?php $subject_id = esc_html($row['subject_id']);
												$subject = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = $subject_id");
												echo esc_html($subject->name);
												?>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Class', 'sakolawp'); ?>
											</th>
											<td>
												<?php
												$class_id = esc_html($row['class_id']);
												$section_id = esc_html($row['section_id']);
												$class = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id");
												echo esc_html($class->name);

												echo esc_html__(' - ', 'sakolawp');

												$section = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $section_id");
												echo esc_html($section->name); ?>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Start Date', 'sakolawp'); ?>
											</th>
											<td>
												<?php echo esc_html($row['availablefrom']); ?> - <?php echo esc_html($row['clock_start']); ?>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Due Date', 'sakolawp'); ?>
											</th>
											<td>
												<?php echo esc_html($row['availableto']); ?> - <?php echo esc_html($row['clock_end']); ?>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Minimum Mark', 'sakolawp'); ?>
											</th>
											<td>
												<a class="btn btn-rounded btn-sm btn-primary skwp-btn"><?php echo esc_html($row['pass']); ?></a>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Total Questions', 'sakolawp'); ?>
											</th>
											<td>
												<a class="btn btn-rounded btn-sm btn-secondary skwp-btn"><?php echo esc_html($row['questions']); ?></a>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Duration', 'sakolawp'); ?>
											</th>
											<td>
												<a class="btn btn-rounded btn-sm btn-success skwp-btn"><?php echo esc_html($row['duration']); ?> <?php echo esc_html__('minutes', 'sakolawp'); ?></a>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
			<?php endforeach;
			} ?>
			<!-- IF EXAM QUESTIONS -->

			<!-- IF EXAM RESULT -->
			<?php if ($action == 'exam_results') { ?>

				<?php
				$exam = $wpdb->get_results("SELECT class_id, section_id, subject_id, availablefrom, clock_start, availableto, clock_end, exam_code, pass, questions, duration FROM {$wpdb->prefix}sakolawp_exams WHERE exam_code = '$exam_code'", ARRAY_A);
				foreach ($exam as $row) :
				?>
					<div class="back hidden-sm-down">
						<a href="<?php echo esc_url(admin_url('admin.php?page=sakolawp-exam')); ?>"><?php echo esc_html__('Back', 'sakolawp'); ?></a>
					</div>
					<div class="skwp-row skwp-clearfix">
						<div class="skwp-column skwp-column-75">
							<div class="table-responsive">
								<table id="hasil-ujian-guru" class="table table-lightborder">
									<thead>
										<tr>
											<th><?php echo esc_html__('Student', 'sakolawp'); ?></th>
											<th class="text-center"><?php echo esc_html__('Mark', 'sakolawp'); ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$teach = esc_html($row['pass']);
										$class_id = esc_html($row['class_id']);
										$section_id = esc_html($row['section_id']);
										$students = $wpdb->get_results("SELECT student_id FROM {$wpdb->prefix}sakolawp_enroll WHERE class_id = $class_id AND section_id = $section_id AND year = '$running_year'", ARRAY_A);

										foreach ($students as $row1) : ?>
											<?php
											$count = 1;

											$student_id = $row1['student_id'];
											$questions = $wpdb->get_results("SELECT question_id, point FROM {$wpdb->prefix}sakolawp_student_answer WHERE student_id = '$student_id' AND exam_code = '$exam_code'", ARRAY_A);
											foreach ($questions as $row2) : ?>
												<?php
												$ids = (explode(',', $row2['question_id']));
												$tt_num = count($ids);
												if ($tt_num == 1) {
													$number = count($ids);
												} else {
													$number = count($ids) - 1;
												}
												?>
												<?php if ($number > 0) : ?>
													<tr>
														<td style="min-width:170px">
															<?php
															$user_info = get_userdata($row1['student_id']);
															echo esc_html($user_info->display_name); ?>
														</td>
														<td class="text-center">
															<?php
															if (!empty($row2["point"]) || $row2["point"] != "") {
																if ($row2["point"] >= $teach) : ?>
																	<a class="btn nc btn-rounded btn-sm btn-success skwp-btn"> <?php echo esc_html($row2["point"]); ?></a>
																<?php endif; ?>
																<?php if ($row2["point"] < $teach) : ?>
																	<a class="btn nc btn-rounded btn-sm btn-danger skwp-btn"> <?php echo esc_html($row2["point"]); ?></a>
															<?php endif;
															} else {
																echo esc_html__('No Mark', 'sakolawp');
															}
															?>
														</td>
													</tr>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>

						<div class="skwp-column skwp-column-4">
							<div class="exam-info-wrap">
								<div class="exam-info-head">
									<h3 class="skwp-header-form">
										<?php echo esc_html__('Exam Information', 'sakolawp'); ?>
									</h3>
								</div>
								<div class="table-responsive">
									<table class="table table-lightbor text-left table-lightfont">
										<tr>
											<th>
												<?php echo esc_html__('Subject', 'sakolawp'); ?>
											</th>
											<td>
												<?php $subject_id = esc_html($row['subject_id']);
												$subject = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = $subject_id");
												echo esc_html($subject->name);
												?>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Class', 'sakolawp'); ?>
											</th>
											<td>
												<?php
												$class_id = $row['class_id'];
												$section_id = $row['section_id'];
												$class = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id");
												echo esc_html($class->name);

												echo esc_html__(' - ', 'sakolawp');

												$section = $wpdb->get_row("SELECT name FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $section_id");
												echo esc_html($section->name); ?>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Start Date', 'sakolawp'); ?>
											</th>
											<td>
												<?php echo esc_html($row['availablefrom']); ?> - <?php echo esc_html($row['clock_start']); ?>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Due Date', 'sakolawp'); ?>
											</th>
											<td>
												<?php echo esc_html($row['availableto']); ?> - <?php echo esc_html($row['clock_end']); ?>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Minimum Mark', 'sakolawp'); ?>
											</th>
											<td>
												<a class="btn btn-rounded btn-sm btn-primary skwp-btn"><?php echo esc_html($row['pass']); ?></a>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Total Questions', 'sakolawp'); ?>
											</th>
											<td>
												<a class="btn btn-rounded btn-sm btn-secondary skwp-btn"><?php echo esc_html($row['questions']); ?></a>
											</td>
										</tr>
										<tr>
											<th>
												<?php echo esc_html__('Duration', 'sakolawp'); ?>
											</th>
											<td>
												<a class="btn btn-rounded btn-sm btn-success skwp-btn"><?php echo esc_html($row['duration']); ?> <?php echo esc_html__('minutes', 'sakolawp'); ?></a>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
			<?php endforeach;
			} ?>
			<!-- IF EXAM RESULT -->

		<?php } ?>
	</div>
	</div>