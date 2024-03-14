<?php
defined( 'ABSPATH' ) || exit;

get_header();
do_action( 'sakolawp_before_main_content' );

$running_year = get_option('running_year');

$exam_id = sanitize_text_field($_GET['exam_id']);
$student_id = sanitize_text_field($_GET['student_id']);
$subject_id = sanitize_text_field($_GET['subject_id']);

?>

<div class="sakolawp-marks-page skwp-content-inner skwp-clearfix">
	<div class="skwp-container">
		<div class="skwp-table table-responsive">
			<div class="title-marks">
				<h3><?php echo esc_html__('Subject','sakolawp'); ?> 
				<?php 
				$subjects = $wpdb->get_row( "SELECT name, total_lab FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = $subject_id");
				$subjectName = $subjects->name;
				echo esc_html( $subjectName ); ?></h3>
			</div>
			<table id="tabbles" class="table table-marks table-lightborder">
				<thead>
					<tr>
						<th>#</th>
						<th></th>
						<th><?php echo esc_html__('Mark','sakolawp'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$total_kd = $subjects->total_lab; ?>
					<?php if($total_kd == 1 || empty($total_kd)) { ?>
					<tr>
						<td><?php echo esc_html('1', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 1', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT mark_obtained FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->mark_obtained);  ?></td>
					</tr>
					<?php } ?>
					<?php if($total_kd == 2) { ?>
					<tr>
						<td><?php echo esc_html('1', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 1', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT mark_obtained FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->mark_obtained);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('2', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 2', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab2 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab2);  ?></td>
					</tr>
					<?php } ?>
					<?php if($total_kd == 3) { ?>
					<tr>
						<td><?php echo esc_html('1', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 1', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT mark_obtained FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->mark_obtained);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('2', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 2', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab2 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab2);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('3', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 3', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab3 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab3);  ?></td>
					</tr>
					<?php } ?>
					<?php if($total_kd == 4) { ?>
					<tr>
						<td><?php echo esc_html('1', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 1', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT mark_obtained FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->mark_obtained);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('2', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 2', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab2 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab2);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('3', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 3', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab3 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab3);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('4', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 4', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab4 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab4);  ?></td>
					</tr>
					<?php } ?>
					<?php if($total_kd == 5) { ?>
					<tr>
						<td><?php echo esc_html('1', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 1', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT mark_obtained FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->mark_obtained);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('2', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 2', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab2 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab2);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('3', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 3', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab3 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab3);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('4', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 4', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab4 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab4);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('5', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 5', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab5 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab5);  ?></td>
					</tr>
					<?php } ?>
					<?php if($total_kd == 6) { ?>
					<tr>
						<td><?php echo esc_html('1', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 1', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT mark_obtained FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->mark_obtained);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('2', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 2', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab2 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab2);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('3', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 3', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab3 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab3);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('4', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 4', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab4 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab4);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('5', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 5', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab5 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab5);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('6', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 6', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab6 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab6);  ?></td>
					</tr>
					<?php } ?>
					<?php if($total_kd == 7) { ?>
					<tr>
						<td><?php echo esc_html('1', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 1', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT mark_obtained FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->mark_obtained);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('2', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 2', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab2 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab2);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('3', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 3', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab3 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab3);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('4', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 4', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab4 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab4);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('5', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 5', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab5 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab5);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('6', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 6', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab6 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab6);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('7', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 7', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab7 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab7);  ?></td>
					</tr>
					<?php } ?>
					<?php if($total_kd == 8) { ?>
					<tr>
						<td><?php echo esc_html('1', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 1', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT mark_obtained FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->mark_obtained);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('2', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 2', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab2 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab2);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('3', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 3', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab3 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab3);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('4', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 4', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab4 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab4);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('5', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 5', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab5 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab5);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('6', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 6', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab6 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab6);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('7', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 7', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab7 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab7);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('8', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 8', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab8 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab8);  ?></td>
					</tr>
					<?php } ?>
					<?php if($total_kd == 9) { ?>
					<tr>
						<td><?php echo esc_html('1', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 1', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT mark_obtained FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->mark_obtained);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('2', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 2', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab2 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab2);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('3', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 3', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab3 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab3);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('4', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 4', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab4 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab4);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('5', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 5', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab5 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab5);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('6', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 6', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab6 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab6);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('7', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 7', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab7 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab7);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('8', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 8', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab8 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab8);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('9', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 9', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab9 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab9);  ?></td>
					</tr>
					<?php } ?>
					<?php if($total_kd == 10) { ?>
					<tr>
						<td><?php echo esc_html('1', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 1', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT mark_obtained FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->mark_obtained);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('2', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 2', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab2 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab2);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('3', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 3', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab3 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab3);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('4', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 4', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab4 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab4);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('5', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 5', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab5 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab5);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('6', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 6', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab6 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab6);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('7', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 7', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab7 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab7);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('8', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 8', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab8 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab8);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('9', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 9', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab9 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab9);  ?></td>
					</tr>
					<tr>
						<td><?php echo esc_html('10', 'sakolawp'); ?></td>
						<td><?php echo esc_html('Lab 10', 'sakolawp'); ?></td>
						<td><?php $mark = $wpdb->get_row( "SELECT lab10 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND year = '$running_year' AND student_id = {$student_id} AND exam_id = {$exam_id}");
						echo esc_html($mark->lab10);  ?></td>
					</tr>
					<?php } ?>
					<tr style="border-top: solid #a5a5a5;">
						<td>
							-
						</td>
						<td>
							<?php echo esc_html_e('Total', 'sakolawp'); ?>
						</td>
						<td>
							<?php 
							$mark2 = $wpdb->get_results( "SELECT mark_obtained, lab2, lab3, lab4, lab5, lab6, lab7, lab8, lab9, lab10 FROM {$wpdb->prefix}sakolawp_mark WHERE subject_id = {$subject_id} AND student_id = {$student_id} AND year = '$running_year' AND exam_id = {$exam_id}", ARRAY_A );
							$nilai = $mark2[0];

							if(empty($total_kd)) {
								$labtotal = $nilai['mark_obtained'];
							}
							else {
								$total_nol = array();
								if($total_kd == 1) {
									$labtotal = $nilai['mark_obtained'];

									if($nilai['mark_obtained'] == NULL) {
										$total_nol = array($nilai['mark_obtained']);

										$array_null = array();
										foreach ($total_nol as $val) {
											if($val == NULL) {
												$array_null[] = $val;
											}
										}
										$varvar = $total_kd - count($array_null);
										if($varvar != 0) {
											$total_kd2 = $total_kd - count($array_null);
										}
										else {
											$total_kd2 = $total_kd;
										}
									}
									else {
										$total_kd2 = $total_kd;
									}
								}
								elseif($total_kd == 2) {
									$labtotal = $nilai['mark_obtained'] + $nilai['lab2'];

									if($nilai['mark_obtained'] == NULL || $nilai['lab2'] == NULL) {
										$total_nol = array($nilai['mark_obtained']);

										$array_null = array();
										foreach ($total_nol as $val) {
											if($val == NULL) {
												$array_null[] = $val;
											}
										}
										$varvar = $total_kd - count($array_null);
										if($varvar != 0) {
											$total_kd2 = $total_kd - count($array_null);
										}
										else {
											$total_kd2 = $total_kd;
										}
									}
									else {
										$total_kd2 = $total_kd;
									}
								}
								elseif($total_kd == 3) {
									$labtotal = $nilai['mark_obtained'] + $nilai['lab2'] + $nilai['lab3'];

									if($nilai['mark_obtained'] == NULL || $nilai['lab2'] == NULL || $nilai['lab3'] == NULL) {
										$total_nol = array($nilai['mark_obtained'], $nilai['lab2'], $nilai['lab3']);

										$array_null = array();
										foreach ($total_nol as $val) {
											if($val == NULL) {
												$array_null[] = $val;
											}
										}
										$varvar = $total_kd - count($array_null);
										if($varvar != 0) {
											$total_kd2 = $total_kd - count($array_null);
										}
										else {
											$total_kd2 = $total_kd;
										}
									}
									else {
										$total_kd2 = $total_kd;
									}
								}
								elseif($total_kd == 4) {
									$labtotal = $nilai['mark_obtained'] + $nilai['lab2'] + $nilai['lab3'] + $nilai['lab4'];

									if($nilai['mark_obtained'] == NULL || $nilai['lab2'] == NULL || $nilai['lab3'] == NULL || $nilai['lab4'] == NULL) {
										$total_nol = array($nilai['mark_obtained'], $nilai['lab2'], $nilai['lab3'], $nilai['lab4']);

										$array_null = array();
										foreach ($total_nol as $val) {
											if($val == NULL) {
												$array_null[] = $val;
											}
										}
										$varvar = $total_kd - count($array_null);
										if($varvar != 0) {
											$total_kd2 = $total_kd - count($array_null);
										}
										else {
											$total_kd2 = $total_kd;
										}
									}
									else {
										$total_kd2 = $total_kd;
									}
								}
								elseif($total_kd == 5) {
									$labtotal = $nilai['mark_obtained'] + $nilai['lab2'] + $nilai['lab3'] + $nilai['lab4'] + $nilai['lab5'];

									if($nilai['mark_obtained'] == NULL || $nilai['lab2'] == NULL || $nilai['lab3'] == NULL || $nilai['lab4'] == NULL || $nilai['lab5'] == NULL) {
										$total_nol = array($nilai['mark_obtained'], $nilai['lab2'], $nilai['lab3'], $nilai['lab4'], $nilai['lab5']);

										$array_null = array();
										foreach ($total_nol as $val) {
											if($val == NULL) {
												$array_null[] = $val;
											}
										}
										$varvar = $total_kd - count($array_null);
										if($varvar != 0) {
											$total_kd2 = $total_kd - count($array_null);
										}
										else {
											$total_kd2 = $total_kd;
										}
									}
									else {
										$total_kd2 = $total_kd;
									}
								}
								elseif($total_kd == 6) {
									$labtotal = $nilai['mark_obtained'] + $nilai['lab2'] + $nilai['lab3'] + $nilai['lab4'] + $nilai['lab5'] + $nilai['lab6'];

									if($nilai['mark_obtained'] == NULL || $nilai['lab2'] == NULL || $nilai['lab3'] == NULL || $nilai['lab4'] == NULL || $nilai['lab5'] == NULL || $nilai['lab6'] == NULL) {
										$total_nol = array($nilai['mark_obtained'], $nilai['lab2'], $nilai['lab3'], $nilai['lab4'], $nilai['lab5'], $nilai['lab6']);

										$array_null = array();
										foreach ($total_nol as $val) {
											if($val == NULL) {
												$array_null[] = $val;
											}
										}
										$varvar = $total_kd - count($array_null);
										if($varvar != 0) {
											$total_kd2 = $total_kd - count($array_null);
										}
										else {
											$total_kd2 = $total_kd;
										}
									}
									else {
										$total_kd2 = $total_kd;
									}
								}
								elseif($total_kd == 7) {
									$labtotal = $nilai['mark_obtained'] + $nilai['lab2'] + $nilai['lab3'] + $nilai['lab4'] + $nilai['lab5'] + $nilai['lab6'] + $nilai['lab7'];

									if($nilai['mark_obtained'] == NULL || $nilai['lab2'] == NULL || $nilai['lab3'] == NULL || $nilai['lab4'] == NULL || $nilai['lab5'] == NULL || $nilai['lab6'] == NULL || $nilai['lab7'] == NULL ) {
										$total_nol = array($nilai['mark_obtained'], $nilai['lab2'], $nilai['lab3'], $nilai['lab4'], $nilai['lab5'], $nilai['lab6'], $nilai['lab7']);

										$array_null = array();
										foreach ($total_nol as $val) {
											if($val == NULL) {
												$array_null[] = $val;
											}
										}
										$varvar = $total_kd - count($array_null);
										if($varvar != 0) {
											$total_kd2 = $total_kd - count($array_null);
										}
										else {
											$total_kd2 = $total_kd;
										}
									}
									else {
										$total_kd2 = $total_kd;
									}
								}
								elseif($total_kd == 8) {
									$labtotal = $nilai['mark_obtained'] + $nilai['lab2'] + $nilai['lab3'] + $nilai['lab4'] + $nilai['lab5'] + $nilai['lab6'] + $nilai['lab7'] + $nilai['lab8'];

									if($nilai['mark_obtained'] == NULL || $nilai['lab2'] == NULL || $nilai['lab3'] == NULL || $nilai['lab4'] == NULL || $nilai['lab5'] == NULL || $nilai['lab6'] == NULL || $nilai['lab7'] == NULL || $nilai['lab8'] == NULL) {
										$total_nol = array($nilai['mark_obtained'], $nilai['lab2'], $nilai['lab3'], $nilai['lab4'], $nilai['lab5'], $nilai['lab6'], $nilai['lab7'], $nilai['lab8']);

										$array_null = array();
										foreach ($total_nol as $val) {
											if($val == NULL) {
												$array_null[] = $val;
											}
										}
										$varvar = $total_kd - count($array_null);
										if($varvar != 0) {
											$total_kd2 = $total_kd - count($array_null);
										}
										else {
											$total_kd2 = $total_kd;
										}
									}
									else {
										$total_kd2 = $total_kd;
									}
								}
								elseif($total_kd == 9) {
									$labtotal = $nilai['mark_obtained'] + $nilai['lab2'] + $nilai['lab3'] + $nilai['lab4'] + $nilai['lab5'] + $nilai['lab6'] + $nilai['lab7'] + $nilai['lab8'] + $nilai['lab9'];

									if($nilai['mark_obtained'] == NULL || $nilai['lab2'] == NULL || $nilai['lab3'] == NULL || $nilai['lab4'] == NULL || $nilai['lab5'] == NULL || $nilai['lab6'] == NULL || $nilai['lab7'] == NULL || $nilai['lab8'] == NULL || $nilai['lab9'] == NULL) {
										$total_nol = array($nilai['mark_obtained'], $nilai['lab2'], $nilai['lab3'], $nilai['lab4'], $nilai['lab5'], $nilai['lab6'], $nilai['lab7'], $nilai['lab8'], $nilai['lab9']);

										$array_null = array();
										foreach ($total_nol as $val) {
											if($val == NULL) {
												$array_null[] = $val;
											}
										}
										$varvar = $total_kd - count($array_null);
										if($varvar != 0) {
											$total_kd2 = $total_kd - count($array_null);
										}
										else {
											$total_kd2 = $total_kd;
										}
									}
									else {
										$total_kd2 = $total_kd;
									}
								}
								elseif($total_kd == 10) {
									$labtotal = $nilai['mark_obtained'] + $nilai['lab2'] + $nilai['lab3'] + $nilai['lab4'] + $nilai['lab5'] + $nilai['lab6'] + $nilai['lab7'] + $nilai['lab8'] + $nilai['lab9'] + $nilai['lab10'];

									if($nilai['mark_obtained'] == NULL || $nilai['lab2'] == NULL || $nilai['lab3'] == NULL || $nilai['lab4'] == NULL || $nilai['lab5'] == NULL || $nilai['lab6'] == NULL || $nilai['lab7'] == NULL || $nilai['lab8'] == NULL || $nilai['lab9'] == 0 || $nilai['lab10'] == 0) {
										$total_nol = array($nilai['mark_obtained'], $nilai['lab2'], $nilai['lab3'], $nilai['lab4'], $nilai['lab5'], $nilai['lab6'], $nilai['lab7'], $nilai['lab8'], $nilai['lab9'], $nilai['lab10']);

										$array_null = array();
										foreach ($total_nol as $val) {
											if($val == NULL) {
												$array_null[] = $val;
											}
										}
										$varvar = $total_kd - count($array_null);
										if($varvar != 0) {
											$total_kd2 = $total_kd - count($array_null);
										}
										else {
											$total_kd2 = $total_kd;
										}
									}
									else {
										$total_kd2 = $total_kd;
									}
								}
							} ?>

							<a class="btn btn-rounded btn-sm btn-success skwp-btn">
								<?php 
								if(empty($total_kd)) {
									echo esc_html($labtotal);
								}
								else {
									echo esc_html(round($labtotal / $total_kd2, 1));
								} ?>
							</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php
do_action( 'sakolawp_after_main_content' );
get_footer();
