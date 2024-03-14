<?php

defined( 'ABSPATH' ) || exit;

global $wpdb;

$running_year = get_option('running_year');

$student_id = get_current_user_id();

$exam_code = sanitize_text_field($_GET['exam_code']);

if(isset($_POST['submit'])) {

	if(wp_verify_nonce($_POST['sakola_exam_csrf'], 'sakola-exam-csrf')) {
		if(current_user_can( 'read' )) {
			$answers = array();
			foreach ( (array) $_POST['answer'] as $k => $v ) {
				$answers[$k] = sanitize_text_field( $v );
			}

			$ques_ids = array();
			foreach ( (array) $_POST['ques_id'] as $q => $i ) {
				$ques_ids[$q] = sanitize_text_field( $i );
			}
			
			$how_hear = count($answers) ? $answers : array();
			$how = count($ques_ids) ? $ques_ids : array();

			$answers2 = array();
			foreach ( (array) $how_hear as $k2 => $v2 ) {
				$answers2[$k2] = sanitize_text_field( $v2 );
			}

			$ques_ids2 = array();
			foreach ( (array) $how as $q2 => $i2 ) {
				$ques_ids2[$q2] = sanitize_text_field( $i2 );
			}

			$exam_code = sanitize_text_field($_POST['exam_code']);
			$student_answer  = sanitize_text_field(implode('^', $answers2));
			$student_id      = sanitize_text_field($student_id);
			$question_ids    = sanitize_text_field(implode(',', $ques_ids2));
			$answered        = sanitize_text_field("answered");
			$time            = sanitize_text_field($_POST['time_left']);
			$total_time      = sanitize_text_field($_POST['time']);
			$exam_code       = sanitize_text_field($exam_code);

			$ans = (explode('^', $student_answer));
			$ids = (explode(',', $question_ids));
			$number = count($ids) - 1;
			$mark = 0;
			$anscor = 0;
			$average = 0;
			$teach = 0;
			for ($i = 0; $i <= $number; $i++):

			$question_id = $ids[$i];
			$dats = $wpdb->get_results( "SELECT correct_answer, marks FROM {$wpdb->prefix}sakolawp_questions WHERE exam_code = '$exam_code' AND question_id = $question_id", ARRAY_A );
			$dataaf = $dats;

			$this_exam = $wpdb->get_row( "SELECT questions FROM {$wpdb->prefix}sakolawp_exams WHERE exam_code = '$exam_code'", ARRAY_A );
			$pertanyaan = $this_exam['questions'];
				foreach ($dataaf as $row3): ?>
					<?php if($ans[$i] == $row3['correct_answer']) {$mark+=$row3['marks']; $anscor+=1;}?>
					<?php $average = intval(round(($anscor/$pertanyaan) * 100)); ?>
				<?php endforeach;?>
			<?php endfor;

			$point       = sanitize_text_field($average);

			$wpdb->insert(
				$wpdb->prefix . 'sakolawp_student_answer',
				array( 
					'student_answer' => $student_answer,
					'student_id' => $student_id,
					'question_id' => $question_ids,
					'answered' => $answered,
					'time' => $time,
					'total_time' => $total_time,
					'exam_code' => $exam_code,
					'point' => $point
				)
			);

			wp_redirect(home_url('online_exams'));

			die;
		}
	}
}

get_header(); 
do_action( 'sakolawp_before_main_content' ); 

$enroll = $wpdb->get_row( "SELECT class_id, section_id FROM {$wpdb->prefix}sakolawp_enroll WHERE student_id = $student_id");

if(!empty($enroll)) :

$user_info = get_userdata($student_id);
$student_name = $user_info->display_name;
?>

<?php 
$pages = $wpdb->get_results( "SELECT duration, exam_code, title FROM {$wpdb->prefix}sakolawp_exams WHERE exam_code = '$exam_code'", ARRAY_A );
$nomor_soal = 0;

	foreach ($pages as $row):
		$skwp_exam_time_left = $row['duration'] * 60;
	?>
	
	<input value="<?php echo esc_attr($skwp_exam_time_left); ?>" name="skwp_exam_time_left" id="skwp_exam_time_left" type="hidden">
	<input value="<?php echo esc_attr($row['exam_code']); ?>" name="skwp_exam_req_id" id="skwp_exam_req_id" type="hidden">
	<?php  
		$hours = floor($row['duration'] / 60);
		$minutes = floor(($row['duration']) % 60);
		$seconds =  "00";
		if ($hours < 10)   { $hours = "0".$hours; }
		if ($minutes < 10) { $minutes = "0".$minutes; }

		$rand = rand();

		$list_questions = $wpdb->get_results( "SELECT optiona, optionb, optionc, optiond, question, question_id FROM {$wpdb->prefix}sakolawp_questions WHERE exam_code = '$exam_code'", ARRAY_A );
	?>


<div class="ujian hal_ujian_murid skwp-content-inner skwp-clearfix">

	<form id="myForm" name="save_exam_student" action="" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="sakola_exam_csrf" value="<?php echo wp_create_nonce('sakola-exam-csrf'); ?>" />
		<div class="skwp-row padbot-10">
			<div class="skwp-column skwp-column-2 huruf-25"><h5><?php echo esc_html($row['title']); ?></h5></div>
			<div class="skwp-column skwp-column-2 text-right">
				<div id="exam-timer">
					<strong><?php echo esc_html__('Time Left:', 'sakolawp'); ?></strong>  
					<input value="" name="time_left" id="exam-time-left" readonly class="no-border" />
					<input type="hidden" name="time" value="<?php echo esc_attr($hours).':'.esc_attr($minutes).':'.esc_attr($seconds);?>" />
				</div>
			</div>
		</div>

		<div class="skwp-row ujian">
			<div class="margin-tengah skwp-column skwp-column-1" id="second-container">
				<div class="">
					<div class="target-page">
					<?php
					
					// default sequence
					$new_questions_sorted = $list_questions;

					//$total_soal = count($questions);
					foreach($new_questions_sorted as $row2) { 
						$nomor_soal++;
						
					$input_optiona = htmlentities($row2['optiona']);
					$input_optionb = htmlentities($row2['optionb']);
					$input_optionc = htmlentities($row2['optionc']);
					$input_optiond = htmlentities($row2['optiond']);

					?>
						<div>
							<div class="soal-ujian-wrapper" data-index="<?php echo esc_attr($nomor_soal); ?>">
								<div class="pipeline white lined-primary soal-ujian-murid">
									<div class="pipeline-header">
										<h5 class="pertanyaan-asli">
											<span class="no-soal"><?php echo esc_html($nomor_soal); ?></span>

											 <?php echo esc_html($row2['question']); ?>
										</h5>
									</div>

									<div class="jawaban-wrap skwp-clearfix">
										<div class="form-check skwp-column skwp-column-1">
											<label class="form-check-label">
												<input type="radio" class="form-check-input" name="answer[<?php echo esc_attr($row2['question_id']); ?>]" id="ans-a-<?php echo esc_attr($row2['question_id']); ?>" value="<?php echo esc_attr($input_optiona); ?>" > <?php echo esc_html($row2['optiona']); ?>
											</label>
											<input type="hidden" name="ques_id[<?php echo esc_attr($row2['question_id']); ?>]" value="<?php echo esc_attr($row2['question_id']);?>">
										</div>
										<div class="form-check skwp-column skwp-column-1">
											<label class="form-check-label">
												<input type="radio" class="form-check-input" name="answer[<?php echo esc_attr($row2['question_id']); ?>]" id="ans-b-<?php echo esc_attr($row2['question_id']); ?>" value="<?php echo esc_attr($input_optionb); ?>"> <?php echo esc_html($row2['optionb']); ?>
											</label>
											<input type="hidden" name="ques_id[<?php echo esc_attr($row2['question_id']); ?>]" value="<?php echo esc_attr($row2['question_id']);?>">
										</div>
										<div class="form-check skwp-column skwp-column-1">
											<label class="form-check-label">
												<input type="radio" class="form-check-input" name="answer[<?php echo esc_attr($row2['question_id']); ?>]" id="ans-c-<?php echo esc_attr($row2['question_id']); ?>" value="<?php echo esc_attr($input_optionc); ?>"> <?php echo esc_html($row2['optionc']); ?>
											</label>
											<input type="hidden" name="ques_id[<?php echo esc_attr($row2['question_id']); ?>]" value="<?php echo esc_attr($row2['question_id']);?>">
										</div>		
										<div class="form-check skwp-column skwp-column-1">
											<label class="form-check-label">
												<input type="radio" class="form-check-input" name="answer[<?php echo esc_attr($row2['question_id']); ?>]" id="ans-d-<?php echo esc_attr($row2['question_id']); ?>" value="<?php echo esc_attr($input_optiond); ?>"> <?php echo esc_html($row2['optiond']); ?>
											</label>
											<input type="hidden" name="ques_id[<?php echo esc_attr($row2['question_id']); ?>]" value="<?php echo esc_attr($row2['question_id']);?>">
										</div>
										<div class="form-check skwp-column skwp-column-1 pt-5" style="order: 100;">
											<div class="tombol-ragu-ragu">
												<label class="button-ragu"> <?php echo esc_html__('Doubt', 'sakolawp'); ?>
													<input type="radio" class="form-check-input" name="answer[<?php echo esc_attr($row2['question_id']); ?>]" id="ans-ragu-<?php echo esc_attr($row2['question_id']); ?>" value="ragu2wae">
													<span class="checkmark"></span>
												</label>
											</div>
											<input type="radio" checked class="form-check-input" name="answer[<?php echo esc_attr($row2['question_id']); ?>]" id="ans-not-<?php echo esc_attr($row2['question_id']); ?>" value="cacauae" style="display: none;">
											<input type="hidden" name="ques_id[<?php echo esc_attr($row2['question_id']); ?>]" value="<?php echo esc_attr($row2['question_id']);?>">
										</div>
									</div>
									
								</div>
							</div>
						</div>
					<?php } ?>
					</div>
					<input type="hidden" name="student_id" id="student_id" value="<?php echo esc_attr($student_id); ?>" />
					<input type="hidden" name="exam_code" id="exam_code" value="<?php echo esc_attr($exam_code); ?>">
				</div>
				<div class="pagination-wrapper clearfix">
					<div class="nomor-ujian-wrap">
						<ul class="pagicod ujian_pagination no-ujian">
							<li class="simple-pagination-page-numbers"></li>
						</ul>
					</div>
					<div class="pagination-prev-next text-center">
						<ul class="pagicod ujian_pagination prev-next text-center">
							<li class="simple-pagination-first"></li>
							<li class="simple-pagination-previous"></li>
							<li class="simple-pagination-next"></li>
							<li class="simple-pagination-last"></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="skwp-column skwp-column-1 text-center selesai-ujian">
				<button class="btn btn-rounded btn-selesai-ujian btn-lg btn-success text-center" type="submit" name="submit" value="submit"><?php echo esc_html__('Done Exam', 'sakolawp'); ?></button>
				<button type="hidden" class="btn btn-rounded btn-lg btn-success text-center no-visible" value="<?php echo esc_html__('Exam Done', 'sakolawp'); ?>" id="subbutton" type="submit" name="submit" value="submit"></button>
			</div>
		</div>
	</form>
			
</div>
<?php endforeach; 

else :
	esc_html_e('You are not assigned to a class yet.', 'sakolawp' );
endif;

do_action( 'sakolawp_after_main_content' );
get_footer();