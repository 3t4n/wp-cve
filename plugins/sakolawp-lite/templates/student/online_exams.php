<?php

defined( 'ABSPATH' ) || exit;

get_header(); 
do_action( 'sakolawp_before_main_content' ); 

global $wpdb;

$running_year = get_option('running_year');

$student_id = get_current_user_id();

$enroll = $wpdb->get_row( "SELECT class_id, section_id FROM {$wpdb->prefix}sakolawp_enroll WHERE student_id = $student_id");

if(!empty($enroll)) :
$class = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $enroll->class_id");
$section = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $enroll->section_id");

$user_info = get_userdata($student_id);
$student_name = $user_info->display_name;

?>

<div class="exams-online-page skwp-content-inner skwp-clearfix">

	<div class="skwp-page-title">
		<h5><?php esc_html_e('Online Exams', 'sakolawp'); ?>
			<span class="skwp-subtitle">
				<?php echo esc_html($class->name). esc_html__('-', 'sakolawp') . esc_html($section->name); ?>
			</span>
		</h5>
	</div>

	<div class="table-responsive">
		<table id="tableini" class="table dataTable exams-table">
			<thead>
				<tr>
					<th class="online_exams"><?php esc_html_e('Title', 'sakolawp'); ?></th>
					<th><?php esc_html_e('Subject', 'sakolawp'); ?></th>
					<th><?php esc_html_e('Date Start', 'sakolawp'); ?></th>
					<th><?php esc_html_e('Date End', 'sakolawp'); ?></th>
					<th><?php esc_html_e('Teacher', 'sakolawp'); ?></th>
					<th><?php esc_html_e('Status', 'sakolawp'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$today1 = strtotime(date("m/d/Y"));
				$today2 = strtotime(date("d-m-Y"));

				$count    = 1;
				$class_id = $enroll->class_id;
				$section_id = $enroll->section_id;

				$exam = $wpdb->get_results( "SELECT exam_code, availablefrom, availableto, clock_start, clock_end, title, subject_id,teacher_id FROM {$wpdb->prefix}sakolawp_exams WHERE class_id = $class_id AND section_id = $section_id", ARRAY_A );

				foreach ($exam as $row):
					$exam_code = $row['exam_code'];
					$student_answer_db = $wpdb->get_row( "SELECT answered FROM {$wpdb->prefix}sakolawp_student_answer WHERE exam_code = '$exam_code' AND student_id = $student_id", ARRAY_A );
					$terjawab = $student_answer_db['answered'];

					$exam_ques = $wpdb->get_results( "SELECT exam_code FROM {$wpdb->prefix}sakolawp_questions WHERE exam_code = '$exam_code'" );  
					$query = $wpdb->num_rows;
					?>
						<?php $dbstart = $row['availablefrom'].' '.$row['clock_start'];?>
						<?php $dbend = $row['availableto'].' '.$row['clock_end'];

					$today = current_time('m/d/Y H:i');
					$dbstart1 = strtotime($today);
					$dbstart = strtotime($dbstart);
					$dbend = strtotime($dbend);  ?>

					<tr>
						<td class="tes">
							<?php echo esc_html($row['title']);?>
						</td>
						<td>
							<?php 
							$subject_id = $row["subject_id"];
							$subject_name = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = '$subject_id'", ARRAY_A );
							echo esc_html($subject_name['name']); ?>
						</td>
						<td>
							<a class="btn btn-rounded btn-sm btn-success skwp-btn">
								<?php echo esc_html($row['availablefrom']);?>
								<?php echo esc_html($row['clock_start']);?>
							</a>
						</td>
						<td>
							<a class="btn btn-rounded btn-sm btn-danger skwp-btn">
								<?php echo esc_html($row['availableto']);?>
								<?php echo esc_html($row['clock_end']);?>
							</a>
						</td>
						<td>
							<?php 
							$user_info = get_user_meta($row['teacher_id']);
							$first_name = $user_info["first_name"][0];
							$last_name = $user_info["last_name"][0];

							$user_name = $first_name .' '. $last_name;

							if(empty($first_name)) {
								$user_info = get_userdata($row['teacher_id']);
								$user_name = $user_info->display_name;
							}
							echo esc_html($user_name); ?>
						</td>
						<td>
							<?php if($dbstart1 < $dbstart && $terjawab != 'answered'):?>
							<a class="btn nc btn-rounded btn-sm btn-warning skwp-btn"><?php esc_html_e('Not Started', 'sakolawp'); ?></a>
							<?php endif;?>
							<?php if($dbstart1 >= $dbend &&  $terjawab != 'answered'):?>
							<a class="btn nc btn-rounded btn-sm btn-danger skwp-btn"><?php esc_html_e('Exam Done', 'sakolawp'); ?></a>
							<?php endif;?>
							<?php if($terjawab != 'answered' && $query > 0 && $dbstart1 >= $dbstart && $dbstart1 < $dbend):?><a class="btn btn-rounded btn-sm btn-success skwp-btn" href="<?php echo add_query_arg( 'exam_code', esc_html($row['exam_code']), home_url( 'examroom' ) );?>"><?php esc_html_e('Take Exam', 'sakolawp'); ?></a>
							<?php endif;?>
							<?php if($query <= 0 && $dbstart1 < $dbend && $dbstart1 >= $dbstart):?>
							<a class="btn nc btn-rounded btn-sm btn-info skwp-btn"><?php esc_html_e('Not Started', 'sakolawp'); ?></a>
							<?php endif;?>
							<?php if($terjawab == 'answered'):?>
							<a class="btn btn-rounded btn-sm btn-primary skwp-btn" href="<?php echo add_query_arg( array('exam_code' => esc_html($exam_code), 'student_id' => intval($student_id)), home_url( 'view_exam_result' ) );?>" ><?php esc_html_e('View Result', 'sakolawp'); ?></a>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php 
else :
	echo esc_html_e('You are not create a homework for your class yet', 'sakolawp' ); ?>
	<?php
endif;
?>

<?php

do_action( 'sakolawp_after_main_content' );
get_footer();