<?php
defined( 'ABSPATH' ) || exit;

get_header(); 
do_action( 'sakolawp_before_main_content' ); 

global $wpdb;

$running_year = get_option('running_year');

$teacher_id = get_current_user_id();
$exam_code = sanitize_text_field($_GET['exam_code']);

$my_exams = $wpdb->get_row( "SELECT teacher_id FROM {$wpdb->prefix}sakolawp_exams WHERE teacher_id = $teacher_id");

if(!empty($my_exams)) :

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;

?>

<div class="examroom-page skwp-content-inner">

	<div class="skwp-tab-menu">
		<ul class="skwp-tab-wrap">
			<li class="skwp-tab-items active">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'examroom' ) );?>"><i class="os-icon picons-thin-icon-thin-0016_bookmarks_reading_book"></i><span><?php echo esc_html__( 'Exam Detail', 'sakolawp' ); ?></span></a>
			</li>
			<li class="skwp-tab-items">
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

	<?php 
	$exam = $wpdb->get_results( "SELECT title, description, availablefrom, clock_start, availableto, clock_end, duration, pass, class_id, section_id, subject_id, questions FROM {$wpdb->prefix}sakolawp_exams WHERE teacher_id = '$teacher_id' AND exam_code = '$exam_code'", ARRAY_A );
	foreach($exam as $row):
	?>
	<div class="skwp-clearfix skwp-row">	
		<div class="skwp-column skwp-column-1 main-exam-content">
			<h5>
				<?php echo esc_html($row['title']);?>
			</h5>
			<p>
				<?php echo esc_html($row['description']);?>
			</p>
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
							<?php $subject_id = intval($row['subject_id']);
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
							$class_id = intval($row['class_id']);
							$section_id = intval($row['section_id']);
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
							<a class="skwp-btn btn-rounded btn-sm btn-primary text-putih"><?php echo esc_html($row['pass']);?></a>
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
							<a class="skwp-btn btn-rounded btn-sm btn-success text-putih"><?php echo esc_html($row['duration']);?> <?php echo esc_html__( 'Minutes', 'sakolawp' ); ?></a>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<?php endforeach;?>
</div>

<?php 
else :
	echo esc_html_e('You are not create an exams for your class yet', 'sakolawp' ); ?>
	<div style="margin-bottom:15px;text-align:right;"><button class="skwp-btn btn-primary btn-rounded btn-upper" data-target="#exampleModal1" data-toggle="modal" type="button"> <?php echo esc_html__( 'Add Exam', 'sakolawp' ); ?></button></div>
	<?php
endif;

do_action( 'sakolawp_after_main_content' );
get_footer();