<?php
defined( 'ABSPATH' ) || exit;

$running_year = get_option('running_year');

$teacher_id = get_current_user_id();
$exam_code = sanitize_text_field($_GET['exam_code']);

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;

if(isset($_GET['import']) == 'single') {
	if(current_user_can( 'read' )) {
		$question_id = sanitize_text_field($_GET['question_id']);
		$exam_code = sanitize_text_field($_GET['exam_code']);

		$questions = $wpdb->get_results( "SELECT question_code, question, question_excerpt, optiona, optionb, optionc, optiond, correct_answer FROM {$wpdb->prefix}sakolawp_questions_bank WHERE owner_id = $teacher_id AND question_id = $question_id", ARRAY_A );

		$questions2 = $wpdb->get_results( "SELECT exam_code FROM {$wpdb->prefix}sakolawp_questions WHERE exam_code = '$exam_code'", ARRAY_A );
		$questions_count = $wpdb->num_rows;

		$get_max_question = $wpdb->get_results( "SELECT questions FROM {$wpdb->prefix}sakolawp_exams WHERE exam_code = '$exam_code'", ARRAY_A );

		foreach ($questions as $row) {
			$post_id = $row['question_code'];
			if($questions_count < $get_max_question[0]['questions']) {
				$marks = 100 / $get_max_question[0]['questions'];
				$wpdb->insert(
					$wpdb->prefix . 'sakolawp_questions',
					array( 
						'question' => sanitize_text_field($row['question']),
						'question_excerpt' => sanitize_text_field($row['question_excerpt']),
						'optiona' => sanitize_text_field($row['optiona']),
						'optionb' => sanitize_text_field($row['optionb']),
						'optionc' => sanitize_text_field($row['optionc']),
						'optiond' => sanitize_text_field($row['optiond']),
						'correct_answer' => sanitize_text_field($row['correct_answer']),
						'marks' => sanitize_text_field($marks),
						'question_code' => sanitize_text_field($post_id),
						'exam_code' => sanitize_text_field($exam_code)
					)
				);
			}
		}

		wp_redirect(home_url('exam_questions?exam_code='.esc_html($exam_code)));
		die;
	}
}

if(isset($_POST['submit'])) {
	if(wp_verify_nonce($_POST['sakola_import_question_csrf'], 'sakola-import-question-csrf')) {
		if(current_user_can( 'read' )) {
			foreach (sanitize_text_field($_POST['id_question']) as $row) {
				$questions = $wpdb->get_results( "SELECT question_code, question, question_excerpt, optiona, optionb, optionc, optiond, correct_answer FROM {$wpdb->prefix}sakolawp_questions_bank FROM {$wpdb->prefix}sakolawp_questions_bank WHERE owner_id = $teacher_id AND question_id = $row", ARRAY_A );

				$questions2 = $wpdb->get_results( "SELECT exam_code FROM {$wpdb->prefix}sakolawp_questions WHERE exam_code = '$exam_code'", ARRAY_A );
				$questions_count = $wpdb->num_rows;

				$get_max_question = $wpdb->get_results( "SELECT questions FROM {$wpdb->prefix}sakolawp_exams WHERE exam_code = '$exam_code'", ARRAY_A );

				foreach ($questions as $question) {
					$post_id = $question['question_code'];
					if($questions_count < $get_max_question[0]['questions']) {
						$marks = 100 / $get_max_question[0]['questions'];
						$wpdb->insert(
							$wpdb->prefix . 'sakolawp_questions',
							array( 
								'question' => sanitize_text_field($question['question']),
								'question_excerpt' => sanitize_text_field($question['question_excerpt']),
								'optiona' => sanitize_text_field($question['optiona']),
								'optionb' => sanitize_text_field($question['optionb']),
								'optionc' => sanitize_text_field($question['optionc']),
								'optiond' => sanitize_text_field($question['optiond']),
								'correct_answer' => sanitize_text_field($question['correct_answer']),
								'marks' => sanitize_text_field($marks),
								'question_code' => sanitize_text_field($post_id),
								'exam_code' => sanitize_text_field($exam_code)
							)
						);
					}
				}
			}

			wp_redirect(home_url('exam_questions?exam_code='.esc_html($exam_code)));
			die;
		}
	}
}

if(isset($_POST['bulk_delete'])) {
	foreach (sanitize_text_field($_POST['id_question']) as $row) {
		$wpdb->delete(
			$wpdb->prefix . 'sakolawp_questions',
			array(
				'question_id' => sanitize_text_field($row)
			)
		);
	}

	wp_redirect(home_url('exam_questions?exam_code='.esc_html($exam_code)));
	die;
}

if(isset($_GET['action']) == 'delete') {

	$question_id = sanitize_text_field($_GET['question_id']);

	$wpdb->delete(
		$wpdb->prefix . 'sakolawp_questions',
		array(
			'question_id' => sanitize_text_field($question_id)
		)
	);

	wp_redirect(home_url('exam_questions?exam_code='.esc_html($exam_code)));
	die;
}


get_header(); 
do_action( 'sakolawp_before_main_content' ); 

global $wpdb;

?>

<div class="examroom-page skwp-content-inner">

	<div class="skwp-tab-menu">
		<ul class="skwp-tab-wrap">
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'examroom' ) );?>"><i class="os-icon picons-thin-icon-thin-0016_bookmarks_reading_book"></i><span><?php echo esc_html__( 'Exam Detail', 'sakolawp' ); ?></span></a>
			</li>
			<li class="skwp-tab-items active">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'exam_questions' ) );?>"><i class="os-icon picons-thin-icon-thin-0067_line_thumb_view"></i><span><?php echo esc_html__( 'Question', 'sakolawp' ); ?></span></a>
			</li>
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'exam_results' ) );?>"><i class="os-icon picons-thin-icon-thin-0100_to_do_list_reminder_done"></i><span><?php echo esc_html__( 'Result', 'sakolawp' ); ?></span></a>
			</li>
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'exam_edit' ) );?>"><i class="os-icon picons-thin-icon-thin-0001_compose_write_pencil_new"></i><span><?php echo esc_html__( 'Edit Exam', 'sakolawp' ); ?></span></a>
			</li>
		</ul>
	</div>

	<div id="hapus-soal">
		<div class="skwp-clearfix skwp-row">
			<?php 
			$exam = $wpdb->get_results( "SELECT subject_id, class_id, section_id, availablefrom, clock_start, availableto, clock_end, pass, questions, duration FROM {$wpdb->prefix}sakolawp_exams WHERE exam_code = '$exam_code'", ARRAY_A );
			foreach ($exam as $row): ?>
			<div class="skwp-column skwp-column-1">
				<div class="skwp-page-title">
					<h5><?php esc_html_e('Questions', 'sakolawp'); ?></h5>
					<div>
						<button type="button" class="btn btn-primary skwp-btn" data-toggle="modal" data-target="#exampleModal">
							<?php esc_html_e('Import Question', 'sakolawp'); ?>
						</button>
					</div>
				</div>
				<form id="frm-questions-delete" class="skwp-mt-20" name="save_create_homework" action="" method="POST" enctype="multipart/form-data">
					<div class="table-responsive">
						<table id="skwp-table-questions" class="table table-lightborder list-pertanyaan-ujian">
							<thead>
								<tr>
									<th></th>
									<th>#</th>
									<th><?php esc_html_e('Questions', 'sakolawp'); ?></th>
									<th class="text-center"><?php esc_html_e('Answer', 'sakolawp'); ?></th>
									<th class="text-center"><?php esc_html_e('Mark', 'sakolawp'); ?></th>
									<th class="text-center"><?php esc_html_e('Options', 'sakolawp'); ?></th>
									<th class="text-center"><?php esc_html_e('Delete', 'sakolawp'); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$n = 1;
									$ques = $wpdb->get_results( "SELECT question_id, question_excerpt, question, correct_answer, optiona, optionb, optionc, optiond, marks FROM {$wpdb->prefix}sakolawp_questions WHERE exam_code = '$exam_code'", ARRAY_A );
									foreach ($ques as $row1): ?>
									<tr>
										<td>
											<?php echo intval($row1['question_id']); ?>
										</td>
										<td>
											<?php echo intval($n++);?>
										</td>
										<td>
											<?php if(!empty($row1['question_excerpt'])) {
												echo esc_html($row1['question_excerpt']); 
											}
											else {
												echo esc_html($row1['question']);
											} ?>
										</td>
										<td class="text-center">
										<?php 
											if($row1['optiona'] == $row1['correct_answer']) {
												echo esc_html('A');
											}
											if($row1['optionb'] == $row1['correct_answer']) {
												echo esc_html('B');
											}
											if($row1['optionc'] == $row1['correct_answer']) {
												echo esc_html('C');
											}
											if($row1['optiond'] == $row1['correct_answer']) {
												echo esc_html('D');
											} ?>
										</td>
										<td class="text-center">
											<?php echo esc_html(round($row1['marks'], 2)); ?>
										</td>
										<td class="text-center">
											<a href="<?php echo add_query_arg( array('question_id' => intval($row1['question_id']), 
											'exam_code' => esc_html($exam_code)), home_url( 'view_exam_question' ) );?>" class="btn btn-rounded btn-sm btn-success skwp-btn"><?php esc_html_e('View', 'sakolawp'); ?></a>
										</td>
										<td class="text-center"><a class="btn btn-rounded btn-sm btn-danger skwp-btn" onClick="return confirm('Konfirmasi Hapus')" href="<?php echo add_query_arg( array('exam_code' => esc_html($exam_code), 'question_id' => intval($row1['question_id']), 'action' => 'delete'), home_url( 'exam_questions' ) );?>">
											<?php esc_html_e('Delete', 'sakolawp'); ?></a></td>
									</tr>
									<?php endforeach;?>
							</tbody>
						</table>
					</div>
					
					<div class="text-right btn-bulk-hapus-soal" style="padding: 20px 0 30px">
						<button class="btn btn-rounded btn-danger skwp-btn" name="bulk_delete" value="bulk_delete" type="submit"><?php esc_html_e('Bulk Delete', 'sakolawp'); ?></button>
					</div>
				</form>
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
								<?php $subject_id = $row['subject_id'];
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
			<?php endforeach;?>
		</div>
		<?php //echo form_close();?>
	</div>
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel"><?php echo esc_html__('Import Questions', 'sakolawp'); ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="frm-questions" name="save_create_homework" action="" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="sakola_import_question_csrf" value="<?php echo wp_create_nonce('sakola-import-question-csrf'); ?>" />
				<div class="modal-body">
					<table id="skwp_questions_table" class="table table-hover dataTable dt-checkboxes-select">
						<thead>
							<tr>
								<th></th>
								<th>No</th>
								<th><?php echo esc_html__('Question', 'sakolawp'); ?></th>
								<th><?php echo esc_html__('Answer', 'sakolawp'); ?></th>
								<th><?php echo esc_html__('Subject', 'sakolawp'); ?></th>
								<th><?php echo esc_html__('Class', 'sakolawp'); ?></th>
								<th><?php echo esc_html__('Options', 'sakolawp'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$counter = 1;
								$questions = $wpdb->get_results( "SELECT question_id,question_excerpt,correct_answer,subject_id,class_id,question FROM {$wpdb->prefix}sakolawp_questions_bank WHERE owner_id = $teacher_id", ARRAY_A );
								$no = 1;
								foreach ($questions as $row2):
								$the_questi = $row2['question'];
								$exist_question_num = $wpdb->get_results( "SELECT question, exam_code FROM {$wpdb->prefix}sakolawp_questions WHERE exam_code = '$exam_code' AND question = '$the_questi'", ARRAY_A );
								$exist_question = $wpdb->num_rows;
							?>
								<tr>
									<td><?php echo intval($row2['question_id']); ?></td>
									<td><?php echo intval($no++); ?></td>
									<td>
										<?php echo esc_html($row2['question_excerpt']); ?>
									</td>
									<td>
										<?php echo esc_html($row2['correct_answer']); ?>
									</td>
									<td>
										<?php $subject_id = intval($row2['subject_id']);
											$subject = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = $subject_id");
											echo esc_html($subject->name);
										?>
									</td>
									<td>
										<?php
											$class_id = intval($row2['class_id']);
											$class = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id");
											echo esc_html($class->name);
										?>
									</td>
									<td class="skwp-row-actions">
										<?php if($exist_question === 0) { ?>
										<a href="<?php echo add_query_arg(array('import' => 'single','question_id' => intval($row2['question_id']),'exam_code' => esc_html($exam_code)), home_url( 'exam_questions' ) );?>" class="btn btn-rounded btn-sm btn-success skwp-btn">
											<?php echo esc_html__('Import', 'sakolawp'); ?>
										</a>
										<?php } else { ?>
										<?php echo esc_html__( 'Question are exist', 'sakolawp' ); ?>
										<?php } ?>
									</td>
								</tr>
								<?php endforeach; ?>
						</tbody>
					</table>
					<input type="hidden" name="id_question" class="id_question">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger skwp-btn" data-dismiss="modal"><?php echo esc_html__( 'Close', 'sakolawp' ); ?></button>
					<button class="btn btn-rounded btn-success skwp-btn" name="submit" value="submit" type="submit"> <?php echo esc_html__( 'Add Question', 'sakolawp' ); ?></button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>

<?php
do_action( 'sakolawp_after_main_content' );
get_footer();