<?php
defined( 'ABSPATH' ) || exit;

global $wpdb;

$running_year = get_option('running_year');

$teacher_id = get_current_user_id();
$exam_code = sanitize_text_field($_GET['exam_code']);

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;

if(isset($_POST['submit'])) {
	if(wp_verify_nonce($_POST['sakola_exam_edit_csrf'], 'sakola-exam-edit-csrf')) {
		if(current_user_can( 'read' )) {
			
			$title = sanitize_text_field($_POST['title']);
			$description = sanitize_textarea_field($_POST['description']);
			$availablefrom = sanitize_text_field($_POST['availablefrom']);
			$availableto = sanitize_text_field($_POST['availableto']);

			$availfromtime = strtotime(date('d-m-Y', strtotime($availablefrom)));
			$availtotime = strtotime(date('d-m-Y', strtotime($availableto)));

			$clock_start = sanitize_text_field($_POST['clock_start']);
			$clock_end = sanitize_text_field($_POST['clock_end']);
			$duration = sanitize_text_field($_POST['duration']);
			$pass = sanitize_text_field($_POST['pass']);

			$wpdb->update(
				$wpdb->prefix . 'sakolawp_exams',
				array( 
					'title' => $title,
					'description' => $description,
					'availablefrom' => $availablefrom,
					'availableto' => $availableto,
					'availfromtime' => $availfromtime,
					'availtotime' => $availtotime,
					'clock_start' => $clock_start,
					'clock_end' => $clock_end,
					'duration' => $duration,
					'pass' => $pass,
				),
				array(
					'exam_code' => $exam_code
				)
			);

			wp_redirect(home_url('exam_edit?exam_code='.esc_html($exam_code)));
			die;
		}
	}
}

get_header(); 
do_action( 'sakolawp_before_main_content' ); 

?>

<div class="edit-exam skwp-content-inner">

	<div class="skwp-tab-menu">
		<ul class="skwp-tab-wrap">
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'examroom' ) );?>"><i class="os-icon picons-thin-icon-thin-0016_bookmarks_reading_book"></i><span><?php echo esc_html__( 'Exam Detail', 'sakolawp' ); ?></span></a>
			</li>
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'exam_questions' ) );?>"><i class="os-icon picons-thin-icon-thin-0067_line_thumb_view"></i><span><?php echo esc_html__( 'Question', 'sakolawp' ); ?></span></a>
			</li>
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'exam_results' ) );?>"><i class="os-icon picons-thin-icon-thin-0100_to_do_list_reminder_done"></i><span><?php echo esc_html__( 'Result', 'sakolawp' ); ?></span></a>
			</li>
			<li class="skwp-tab-items active">
				<a class="skwp-tab-item" href="<?php echo add_query_arg( 'exam_code', esc_html($exam_code), home_url( 'exam_edit' ) );?>"><i class="os-icon picons-thin-icon-thin-0001_compose_write_pencil_new"></i><span><?php echo esc_html__( 'Edit Exam', 'sakolawp' ); ?></span></a>
			</li>
		</ul>
	</div>

	<?php 
	$exam = $wpdb->get_results( "SELECT title, description, availablefrom, clock_start, availableto, clock_end, duration, pass, class_id, section_id, subject_id, questions FROM {$wpdb->prefix}sakolawp_exams WHERE teacher_id = '$teacher_id' AND exam_code = '$exam_code'", ARRAY_A );
	foreach($exam as $row):
	?>
	<div class="skwp-clearfix skwp-row">	
		<div class="skwp-column skwp-column-1">
			<form name="save_create_homework" action="" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="sakola_exam_edit_csrf" value="<?php echo wp_create_nonce('sakola-exam-edit-csrf'); ?>" />
				<div class="skwp-form-group">
					<label for=""> <?php esc_html_e('Title', 'sakolawp'); ?></label><input class="skwp-form-control" required="" name="title" type="text" value="<?php echo esc_attr($row['title']); ?>">
				</div>
				<div class="skwp-form-group">
					<label> <?php esc_html_e('Description', 'sakolawp'); ?></label><textarea id="editordatateacher" name="description" required="" ><?php echo esc_textarea($row['description']); ?></textarea>
				</div>
				<div class="skwp-row">
					<div class="skwp-column skwp-column-3">
						<div class="skwp-form-group">
							<label for=""> <?php esc_html_e('Exam Start', 'sakolawp'); ?></label><input class="single-daterange skwp-form-control" required="" type="text" value="<?php echo esc_attr($row['availablefrom']); ?>" name="availablefrom">
						</div>
					</div>
					<div class="skwp-column skwp-column-3">
						<div class="skwp-form-group">
							<label for=""> <?php esc_html_e('Start Hour', 'sakolawp'); ?></label>
							<div class="input-group clockpicker" data-align="top" data-autoclose="true">
								<input type="text" required="" class="skwp-form-control" name="clock_start" value="<?php echo esc_attr($row['clock_start']); ?>">
								<span class="input-group-addon">
									<span class="picons-thin-icon-thin-0029_time_watch_clock_wall"></span>
								</span>
							</div>
						</div>
					</div>
					<div class="skwp-column skwp-column-3">
						<div class="skwp-form-group">
							<label for=""> <?php esc_html_e('Exam End', 'sakolawp'); ?></label><input class="single-daterange skwp-form-control" required="" type="text" value="<?php echo esc_attr($row['availableto']); ?>" name="availableto">
						</div>
					</div>
					<div class="skwp-column skwp-column-3">
						<div class="skwp-form-group">
							<label for=""> <?php esc_html_e('End Hour', 'sakolawp'); ?></label>
							<div class="input-group clockpicker" data-align="top" data-autoclose="true">
								<input type="text" required="" class="skwp-form-control" name="clock_end" value="<?php echo esc_attr($row['clock_end']); ?>">
								<span class="input-group-addon">
									<span class="picons-thin-icon-thin-0029_time_watch_clock_wall"></span>
								</span>
							</div>
						</div>
					</div>
					<div class="skwp-column skwp-column-3">
						<div class="skwp-form-group">
							<label for=""> <?php esc_html_e('Duration (minutes)', 'sakolawp'); ?></label><input class="skwp-form-control" required="" type="number" value="<?php echo esc_attr($row['duration']); ?>" name="duration">
						</div>
					</div>
					<div class="skwp-column skwp-column-3">
						<div class="skwp-form-group">
							<label for=""> <?php esc_html_e('Standard Mark', 'sakolawp'); ?></label><input class="skwp-form-control" required="" name="pass" type="number" value="<?php echo esc_attr($row['pass']); ?>">
						</div>
					</div>
				</div>
				<div class="skwp-form-button">
					<button class="btn btn-rounded btn-success skwp-btn skwp-mt-10" type="submit" name="submit" value="submit"><?php esc_html_e('Save', 'sakolawp'); ?></button>
				</div>
			</form>
		</div>
		
		<div class="skwp-column skwp-column-1 exam-info skwp-mt-20">
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
do_action( 'sakolawp_after_main_content' );
get_footer();