<?php

defined( 'ABSPATH' ) || exit;

get_header(); 
do_action( 'sakolawp_before_main_content' ); 

global $wpdb;

$running_year = get_option('running_year');

$student_id = get_current_user_id();

$enroll = $wpdb->get_row( "SELECT class_id, section_id FROM {$wpdb->prefix}sakolawp_enroll WHERE student_id = $student_id");

$exam_code = sanitize_text_field($_GET['exam_code']);

if(!empty($enroll)) :

$user_info = get_userdata($student_id);
$student_name = $user_info->display_name;

$details = $wpdb->get_results( "SELECT description, questions, duration, pass FROM {$wpdb->prefix}sakolawp_exams WHERE exam_code = '$exam_code'", ARRAY_A );
foreach($details as $row):
?>
<div class="news-page skwp-content-inner skwp-clearfix">
	
	<div class="skwp-page-title">
		<h5><?php esc_html_e('Description', 'sakolawp'); ?></h5>
		<p><?php echo esc_html($row['description']);?></p>
	</div>

	<div class="table-responsive">
		<table class="table table-lightbor table-lightfont">
			<tr>
				<th><i class="picons-thin-icon-thin-0014_notebook_paper_todo huruf-30"></i></th>
				<td>
				<strong> <?php esc_html_e('Total Questions :', 'sakolawp'); ?></strong> <?php echo esc_html($row['questions']);?>
				</td>
			</tr>
			<tr>
				<th><i class="picons-thin-icon-thin-0027_stopwatch_timer_running_time huruf-30"></i></th>
				<td>
				<strong> <?php esc_html_e('Duration :', 'sakolawp'); ?></strong> <?php echo esc_html($row['duration']); ?> <?php esc_html_e('Minutes', 'sakolawp'); ?>
				</td>
			</tr>
			<tr>
				<th><i class="picons-thin-icon-thin-0007_book_reading_read_bookmark huruf-30"></i></th>
				<td>
				<strong> <?php esc_html_e('Minimum Mark :', 'sakolawp'); ?></strong> <a class="btn btn-rounded btn-sm btn-primary skwp-btn"><?php echo esc_html($row['pass']);?></a>
				</td>
			</tr>
		</table>
	</div>
	<div class="text-center">	
		<a class="btn btn-rounded btn-lg btn-success skwp-form-btn skwp-btn" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'exam' ) );?>"><?php esc_html_e('Start Exam', 'sakolawp'); ?></a>
	</div>
		
</div>

<?php 
endforeach;

else :
	echo esc_html_e('You are not create a homework for your class yet', 'sakolawp' ); ?>
	<?php
endif;
?>

<?php

do_action( 'sakolawp_after_main_content' );
get_footer();