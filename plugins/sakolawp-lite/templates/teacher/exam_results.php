<?php
defined( 'ABSPATH' ) || exit;

global $wpdb;

$running_year = get_option('running_year');

$teacher_id = get_current_user_id();
$exam_code = sanitize_text_field($_GET['exam_code']);

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;

get_header(); 
do_action( 'sakolawp_before_main_content' ); 
?>

<div class="content-w exams-table">

	<div class="skwp-tab-menu">
		<ul class="skwp-tab-wrap">
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'examroom' ) );?>">
					<i class="os-icon picons-thin-icon-thin-0016_bookmarks_reading_book"></i>
					<span><?php echo esc_html__( 'Exam Detail', 'sakolawp' ); ?></span>
				</a>
			</li>
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'exam_questions' ) );?>">
					<i class="os-icon picons-thin-icon-thin-0067_line_thumb_view"></i>
					<span><?php echo esc_html__( 'Question', 'sakolawp' ); ?></span>
				</a>
			</li>
			<li class="skwp-tab-items active">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'exam_results' ) );?>">
					<i class="os-icon picons-thin-icon-thin-0100_to_do_list_reminder_done"></i>
					<span><?php echo esc_html__( 'Result', 'sakolawp' ); ?></span>
				</a>
			</li>
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'exam_edit' ) );?>">
					<i class="os-icon picons-thin-icon-thin-0001_compose_write_pencil_new"></i>
					<span><?php echo esc_html__( 'Edit Exams', 'sakolawp' ); ?></span>
				</a>
			</li>
		</ul>
	</div>

	<?php 
	$exam = $wpdb->get_results( "SELECT pass,class_id,section_id,subject_id,availablefrom,clock_start,availableto,clock_end,questions, duration FROM {$wpdb->prefix}sakolawp_exams WHERE teacher_id = '$teacher_id' AND exam_code = '$exam_code'", ARRAY_A );
	foreach($exam as $row):
	?>
	<div class="back hidden-sm-down">		
		<a href="<?php echo esc_url(site_url('online_exams'));?>"><i class="os-icon os-icon-common-07"></i></a>	
	</div>
	<div class="skwp-clearfix skwp-row">	
		<div class="skwp-column skwp-column-1">
			<div class="table-responsive">
				<table id="tableini" class="table table-lightborder">
					<thead>
						<tr>
							<th><?php echo esc_html__( 'Student', 'sakolawp' ); ?></th>
							<th class="text-center"><?php echo esc_html__( 'Mark', 'sakolawp' ); ?></th>
							<th class="text-center"><?php echo esc_html__( 'View Result', 'sakolawp' ); ?></th>
						</tr>
					</thead>
					<tbody>	
 					<?php 
 					$teach = intval($row['pass']);
 					$class_id = intval($row['class_id']);
 					$section_id = intval($row['section_id']);
 					$students = $wpdb->get_results( "SELECT student_id FROM {$wpdb->prefix}sakolawp_enroll WHERE class_id = $class_id AND section_id = $section_id AND year = '$running_year'", ARRAY_A );
					foreach($students as $row1): ?>
					<?php  
					$count = 1;

					$student_id = $row1['student_id'];
 					$questions = $wpdb->get_results( "SELECT question_id,point FROM {$wpdb->prefix}sakolawp_student_answer WHERE student_id = '$student_id' AND exam_code = '$exam_code'", ARRAY_A );	
						foreach($questions as $row2): ?>
						<?php 
							$ids =(explode(',', $row2['question_id']));
							$tt_num = count($ids);
							if($tt_num == 1) {
								$number = count($ids);
							}
							else {
								$number = count($ids) - 1;
							}
						?>
							<?php if ($number > 0): ?>
								<tr>
									<td style="min-width:170px">
										<?php 
										$user_info = get_userdata($row1['student_id']);
										echo esc_html($user_info->display_name); ?>
									</td>
									<td class="text-center">
										<?php 
											if(!empty($row2["point"]) || $row2["point"] != "") {
												if($row2["point"] >= $teach) : ?>
													<a class="btn nc btn-rounded btn-sm btn-success skwp-btn"> <?php echo esc_html($row2["point"]); ?></a>
												<?php endif;?>
												<?php if($row2["point"] < $teach): ?>
													<a class="btn nc btn-rounded btn-sm btn-danger skwp-btn"> <?php echo esc_html($row2["point"]); ?></a>
												<?php endif;
											}
											else {
												echo esc_html__("Empty", 'sakolawp');
											}
										?>
									</td>
									<td class="text-center">
										<a href="<?php echo add_query_arg( array('exam_code' => esc_html($exam_code), 'student_id' => intval($row1['student_id'])), home_url( 'view_exam_result' ) );?>" class="btn btn-rounded btn-sm btn-info skwp-btn">
											<?php echo esc_html__( 'View', 'sakolawp' ); ?>
										</a>
									</td>
								</tr>
							<?php endif;?>
						<?php endforeach; ?>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="skwp-column skwp-column-1 exam-info">
			<h5>
				<?php echo esc_html__( 'Exam Information', 'sakolawp' ); ?>
			</h5>
			<div class="table-responsive">
				<table class="table table-lightbor table-lightfont">
					<tr>
						<th>
							<?php echo esc_html__( 'Subject', 'sakolawp' ); ?>
						</th>
						<td>
							<?php 
								$subject_id = $row['subject_id'];
								$subject = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = $subject_id");
								echo esc_html($subject->name);
							?>
						</td>
					</tr>
					<tr>
						<th>
							<?php echo esc_html__( 'Class', 'sakolawp' ); ?>
						</th>
						<td>
							<?php 
							$class_id = $row['class_id'];
							$section_id = $row['section_id'];
							$class = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id");
							echo esc_html($class->name);

							echo esc_html__(' - ', 'sakolawp');

							$section = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $section_id");
							echo esc_html($section->name); ?>
						</td>
					</tr>
					<tr>
						<th>
							<?php echo esc_html__( 'Start Date', 'sakolawp' ); ?>
						</th>
						<td>
							<?php echo esc_html($row['availablefrom']);?> - <?php echo esc_html($row['clock_start']);?>
						</td>
					</tr>
					<tr>
						<th>
							<?php echo esc_html__( 'End Date', 'sakolawp' ); ?>
						</th>
						<td>
							<?php echo esc_html($row['availableto']);?> - <?php echo esc_html($row['clock_end']);?>
						</td>
					</tr>
					<tr>
						<th>
							<?php echo esc_html__( 'Minimum Score', 'sakolawp' ); ?>
						</th>
						<td>
							<a class="skwp-btn btn-rounded btn-sm btn-primary skwp-btn"><?php echo esc_html($row['pass']);?></a>
						</td>
					</tr>
					<tr>
						<th>
							<?php echo esc_html__( 'Total Question', 'sakolawp' ); ?>
						</th>
						<td>
							<?php echo esc_html($row['questions']);?>
						</td>
					</tr>
					<tr>
						<th>
							<?php echo esc_html__( 'Duration', 'sakolawp' ); ?>
						</th>
						<td>
							<a class="skwp-btn btn-rounded btn-sm btn-success skwp-btn"><?php echo esc_html($row['duration']);?> <?php echo esc_html__( 'Minutes', 'sakolawp' ); ?></a>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<?php endforeach;?>
</div>

<?php
do_action( 'sakolawp_after_main_content' );
get_footer();