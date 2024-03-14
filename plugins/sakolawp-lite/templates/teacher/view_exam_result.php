<?php
defined( 'ABSPATH' ) || exit;

global $wpdb;

$running_year = get_option('running_year');

$teacher_id = get_current_user_id();
$exam_code = sanitize_text_field($_GET['exam_code']);
$student_id = sanitize_text_field($_GET['student_id']);

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;


get_header(); 
do_action( 'sakolawp_before_main_content' ); 

?>

<div class="exam-result ulangan-card skwp-content-inner">

	<div class="skwp-tab-menu">
		<ul class="skwp-tab-wrap">
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'examroom' ) );?>"><i class="os-icon picons-thin-icon-thin-0016_bookmarks_reading_book"></i><span><?php echo esc_html__( 'Exam Detail', 'sakolawp' ); ?></span></a>
			</li>
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'exam_questions' ) );?>"><i class="os-icon picons-thin-icon-thin-0067_line_thumb_view"></i><span><?php echo esc_html__( 'Question', 'sakolawp' ); ?></span></a>
			</li>
			<li class="skwp-tab-items active">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'exam_results' ) );?>"><i class="os-icon picons-thin-icon-thin-0100_to_do_list_reminder_done"></i><span><?php echo esc_html__( 'Result', 'sakolawp' ); ?></span></a>
			</li>
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'exam_edit' ) );?>"><i class="os-icon picons-thin-icon-thin-0001_compose_write_pencil_new"></i><span><?php echo esc_html__( 'Edit Exam', 'sakolawp' ); ?></span></a>
			</li>
		</ul>
	</div>

	<?php 
	$exam = $wpdb->get_results( "SELECT subject_id, class_id, section_id, availablefrom, availableto, questions, duration FROM {$wpdb->prefix}sakolawp_exams WHERE teacher_id = '$teacher_id' AND exam_code = '$exam_code'", ARRAY_A );
	foreach($exam as $row):
	?>


	<div class="skwp-page-title">
		<h5><?php echo esc_html__( 'Exam Result', 'sakolawp' ); ?></h5>
		<span class="skwp-subtitle">
			<?php 
				$user_info = get_userdata($student_id);
				$student_name = $user_info->display_name;

				echo esc_html($student_name);
			?>
		</span>
	</div>

	<?php  
		$mark = 0;
		$cor = 0;
		$count = 1;

		$questions = $wpdb->get_results( "SELECT question_id,student_answer, total_time, time FROM {$wpdb->prefix}sakolawp_student_answer WHERE student_id = '$student_id' AND exam_code = '$exam_code'", ARRAY_A );
		foreach($questions as $row2): ?>
	<?php 
		$ids =(explode(',', $row2['question_id']));
		$ans =(explode('^', $row2['student_answer']));
		$number = count($ids) - 1;

		$corary = array();
		$corarysalah = array();
		$coraryragu = array();
		$coraryrkosong = array();

		if ($number > 0):
		for ($i = 0; $i <= $number; $i++):
		$data = $wpdb->get_results( "SELECT marks,correct_answer FROM {$wpdb->prefix}sakolawp_questions WHERE question_id = '$ids[$i]'", ARRAY_A );
		foreach ($data as $row3):
			if($row3['correct_answer'] == $ans[$i]):
				$corary[] = $cor++; $mark+=$row3['marks'];
			endif;
			if($row3['correct_answer'] != $ans[$i] && 'cacauae' != $ans[$i] && 'ragu2wae' != $ans[$i]):
				$corarysalah[] = $mark+=$row3['marks'];
			endif;
			if('ragu2wae' == $ans[$i]):
				$coraryragu[] = $mark+=$row3['marks'];
			endif;
			if('cacauae' == $ans[$i]):
				$coraryrkosong[] = $mark+=$row3['marks'];
			endif;

		endforeach; endfor;  endif;

		$dif = date("H:i:s", strtotime("00:00:00") + strtotime($row2['total_time']) - strtotime($row2['time']));
	?>
	<div class="exam-info ulangan-card-inner clearfix">
		<div class="skwp-row">
			<div class="info-left info-det skwp-column skwp-column-2">
				<ul>
					<li>
						<span class="title"><?php echo esc_html__( 'Subject', 'sakolawp' ); ?></span><span class="isi">
						<?php 
						$subject_id = $row['subject_id'];
						$the_subject = $wpdb->get_results( "SELECT name FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = '$subject_id'", ARRAY_A );
						echo esc_html($the_subject[0]['name']); ?>
						</span>
					</li>
					<li>
						<span class="title"><?php echo esc_html__( 'Class', 'sakolawp' ); ?></span><span class="isi">
						<?php 
						$class_id = $row['class_id'];
						$the_class = $wpdb->get_results( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = '$class_id'", ARRAY_A );
						$section_id = $row['section_id'];
						$the_section = $wpdb->get_results( "SELECT name FROM {$wpdb->prefix}sakolawp_section WHERE section_id = '$section_id'", ARRAY_A );

						echo esc_html($the_class[0]['name'].'-'.$the_section[0]['name']); ?>
						</span>
					</li>
					<li>
						<span class="title"><?php echo esc_html__( 'Start Date', 'sakolawp' ); ?></span><span class="isi"><?php echo esc_html($row['availablefrom']);?></span>
					</li>
					<li>
						<span class="title"><?php echo esc_html__( 'End Date', 'sakolawp' ); ?></span><span class="isi"><?php echo esc_html($row['availableto']);?></span>
					</li>
				</ul>
			</div>

			<div class="info-right info-det skwp-column skwp-column-2">
				<ul>
					<li>
						<span class="title"><?php echo esc_html__( 'Mark', 'sakolawp' ); ?></span>:
						<span class="value">
							<button href="" class="btn btn-primary btn-sm btn-rounded"><?php echo intval(round((($cor/$row['questions']) * 100))); ?></button>
						</span>
					</li>
					<li>
						<span class="title"><?php echo esc_html__( 'Total Question', 'sakolawp' ); ?></span>:
						<span class="value">
							<button href="" class="btn btn-primary btn-sm btn-rounded"><?php echo esc_html($row['questions']); ?></button>
						</span>
					</li>
					<li>
						<span class="title"><?php echo esc_html__( 'Duration', 'sakolawp' ); ?></span>:
						<span class="value">
							<button href="" class="btn btn-success btn-sm btn-rounded"><?php echo esc_html($row['duration']); ?> <?php echo esc_html__( 'Minutes', 'sakolawp' ); ?></button>
						</span>
					</li>
					<li>
						<span class="title"><?php echo esc_html__( 'Exam Done in', 'sakolawp' ); ?></span>:
						<span class="value">
							<button href="" class="btn btn-primary btn-sm btn-rounded"><?php echo esc_html($dif); ?></button>
						</span>
					</li>
				</ul>
			</div>
		</div>

		<div class="analyze-exam analisis-ulangan skwp-row skwp-clearfix">
			<div class="skwp-column skwp-column-2">
				<div class="mark-exam nilai-ulangan">
					<h1 class="mark nilai"><?php echo intval(round((($cor/$row['questions']) * 100))); ?></h1>
					<span class="text-nilai"><?php echo esc_html__( 'Mark', 'sakolawp' ); ?></span>
				</div>
			</div>

			<div class="analisis-detail skwp-column skwp-column-2 skwp-clearfix">
				<div class="skwp-row">
					<div class="jawaban-benar skwp-column skwp-column-2">
						<div class="card">
							<h3 class="nilai"><?php echo intval(count($corary));?></h3>
							<span class="text-nilai"><?php echo esc_html__( 'Correct Answer', 'sakolawp' ); ?></span>
						</div>
					</div>
					<div class="jawaban-salah skwp-column skwp-column-2">
						<div class="card">
							<h3 class="nilai"><?php echo intval(count($corarysalah));?></h3>
							<span class="text-nilai"><?php echo esc_html__( 'Wrong Answer', 'sakolawp' ); ?></span>
						</div>
					</div>
				</div>

				<div class="skwp-row">
					<div class="jawaban-diisi skwp-column skwp-column-2">
						<div class="card">
							<h3 class="nilai"><?php echo intval(count($coraryrkosong));?></h3>
							<span class="text-nilai"><?php echo esc_html__( 'Not Answered', 'sakolawp' ); ?></span>
						</div>
					</div>
					<div class="jawaban-ragu skwp-column skwp-column-2">
						<div class="card">
							<h3 class="nilai"><?php echo intval(count($coraryragu));?></h3>
							<span class="text-nilai"><?php echo esc_html__( 'Doubt', 'sakolawp' ); ?></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end of ulangan-card-inner -->
	<?php endforeach; ?>

	<div class="skwp-table table-responsive">
		<table class="table table-lightborder list-pertanyaan-ujian">
			<thead>
				<tr>
					<th><?php echo esc_html__( 'No', 'sakolawp' ); ?></th>
					<th class="questions"><?php echo esc_html__( 'Question', 'sakolawp' ); ?></th>
					<th><?php echo esc_html__( 'Correct', 'sakolawp' ); ?></th>
					<th><?php echo esc_html__( 'Answer', 'sakolawp' ); ?></th>
					<th><?php echo esc_html__( 'Mark', 'sakolawp' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php  
				$mark = 0;
				$cor = 0;
				$count = 1;

				$questions = $wpdb->get_results( "SELECT question_id, student_answer FROM {$wpdb->prefix}sakolawp_student_answer WHERE student_id = '$student_id' AND exam_code = '$exam_code'", ARRAY_A );
				foreach($questions as $row2): ?>
			<?php 
					$ids =(explode(',', $row2['question_id']));
					$ans =(explode('^', $row2['student_answer']));
					$number = count($ids) - 1;
			?>
			<?php if ($number > 0):
				for ($i = 0; $i <= $number; $i++):

				$data = $wpdb->get_results( "SELECT question, optiona, optionb, optionc, optiond, correct_answer, marks FROM {$wpdb->prefix}sakolawp_questions WHERE question_id = '$ids[$i]'", ARRAY_A );
				foreach ($data as $row3):
			?>
				<tr>
					<td><?php echo esc_html( $count++ ); ?></td>
					<td class="questions">
						<?php
							if(!empty($row3['question_excerpt'])) {
								echo esc_html($row3['question_excerpt']);
							} else {
								echo esc_html($row3['question']);
							}
						?>
					</td>
					<td>
						<a class="btn btn-rounded btn-sm btn-success skwp-btn">
							<?php if($row3['optiona'] == $row3['correct_answer']) { ?><?php echo esc_html__( 'A', 'sakolawp' ); ?><?php } ?>
							<?php if($row3['optionb'] == $row3['correct_answer']) { ?><?php echo esc_html__( 'B', 'sakolawp' ); ?><?php } ?>
							<?php if($row3['optionc'] == $row3['correct_answer']) { ?><?php echo esc_html__( 'C', 'sakolawp' ); ?><?php } ?>
							<?php if($row3['optiond'] == $row3['correct_answer']) { ?><?php echo esc_html__( 'D', 'sakolawp' ); ?><?php } ?>
						</a>
					</td>
					<td>
					<?php if($row3['correct_answer'] == $ans[$i]):?>
						<?php $cor++; $mark+=$row3['marks'];?>
						<a class="btn nc btn-rounded btn-sm btn-success skwp-btn">
							<?php if($row3['optiona'] == $ans[$i]) { ?><?php echo esc_html__( 'A', 'sakolawp' ); ?><?php } ?>
							<?php if($row3['optionb'] == $ans[$i]) { ?><?php echo esc_html__( 'B', 'sakolawp' ); ?><?php } ?>
							<?php if($row3['optionc'] == $ans[$i]) { ?><?php echo esc_html__( 'C', 'sakolawp' ); ?><?php } ?>
							<?php if($row3['optiond'] == $ans[$i]) { ?><?php echo esc_html__( 'D', 'sakolawp' ); ?><?php } ?>
						</a>
					<?php endif;?>
					<?php if($ans[$i] == ''):?>
						<a class="btn nc btn-rounded btn-sm btn-warning skwp-btn"><?php echo esc_html__( 'Not Answered', 'sakolawp' ); ?></a>
					<?php endif;?>
					<?php if($ans[$i] == 'ragu2wae'):?>
						<a class="btn nc btn-rounded btn-sm btn-warning skwp-btn"><?php echo esc_html__( 'Doubt', 'sakolawp' ); ?></a>
					<?php endif;
					if($ans[$i] == 'cacauae'):?>
						<a class="btn nc btn-rounded btn-sm btn-warning skwp-btn"><?php echo esc_html__( 'Not Answered', 'sakolawp' ); ?></a>
					<?php endif;?>
					<?php if($row3['correct_answer'] != $ans[$i] && $ans[$i] != '' && $ans[$i] != 'ragu2wae' && $ans[$i] != 'cacauae'): ?>
						<a class="btn nc btn-rounded btn-sm btn-danger skwp-btn">
							<?php if($row3['optiona'] == $ans[$i]) { ?><?php echo esc_html__( 'A', 'sakolawp' ); ?><?php } ?>
							<?php if($row3['optionb'] == $ans[$i]) { ?><?php echo esc_html__( 'B', 'sakolawp' ); ?><?php } ?>
							<?php if($row3['optionc'] == $ans[$i]) { ?><?php echo esc_html__( 'C', 'sakolawp' ); ?><?php } ?>
							<?php if($row3['optiond'] == $ans[$i]) { ?><?php echo esc_html__( 'D', 'sakolawp' ); ?><?php } ?>
						</a>
					<?php endif;?>
					</td>
					<td>
						<?php if($row3['correct_answer'] == $ans[$i]):?>
							<a class="btn nc btn-rounded btn-sm btn-primary skwp-btn"><?php echo intval(round($row3['marks'], 2)); ?></a>
						<?php endif;?>
						<?php if($row3['correct_answer'] != $ans[$i] || $ans[$i] == '' || $ans[$i] == 'ragu2wae' || $ans[$i] == 'cacauae'):?>
								<a class="btn nc btn-rounded btn-sm btn-danger skwp-btn">0</a>
							<?php endif;?>
					</td>
				</tr>
				<?php endforeach; endfor;  endif;  ?>
			<?php endforeach;?>
			</tbody>
		</table>
	<?php endforeach;?>
	</div>
</div>

<?php

do_action( 'sakolawp_after_main_content' );
get_footer();