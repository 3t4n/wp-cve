<?php
defined( 'ABSPATH' ) || exit;

global $wpdb;

$running_year = get_option('running_year');

$teacher_id = get_current_user_id();

$rand = substr(md5(rand(100000000, 200000000)), 0, 10);

if(isset($_POST['submit'])) {
	if(wp_verify_nonce($_POST['sakola_add_exams_csrf'], 'sakola-add-exams-csrf')) {
		if(current_user_can( 'read' )) {
			
			$title = sanitize_text_field($_POST['title']);
			$description = sanitize_textarea_field($_POST['description']);
			$availablefrom = sanitize_text_field($_POST['availablefrom']);
			$availableto = sanitize_text_field($_POST['availableto']);

			$availfromtime = strtotime(date('d-m-Y', strtotime($availablefrom)));
			$availtotime = strtotime(date('d-m-Y', strtotime($availableto)));

			$class_id = sanitize_text_field($_POST['class_id']);
			$clock_start = sanitize_text_field($_POST['clock_start']);
			$clock_end = sanitize_text_field($_POST['clock_end']);
			$section_id = sanitize_text_field($_POST['section_id']);
			$subject_id = sanitize_text_field($_POST['subject_id']);
			$duration = sanitize_text_field($_POST['duration']);
			$pass = sanitize_text_field($_POST['pass']);
			$questions = sanitize_text_field($_POST['questions']);
			$exam_code = sanitize_text_field($rand);
			$year = sanitize_text_field($running_year);

			$wpdb->insert(
				$wpdb->prefix . 'sakolawp_exams',
				array( 
					'title' => $title,
					'description' => $description,
					'availablefrom' => $availablefrom,
					'availableto' => $availableto,
					'availfromtime' => $availfromtime,
					'availtotime' => $availtotime,
					'class_id' => $class_id,
					'clock_start' => $clock_start,
					'clock_end' => $clock_end,
					'section_id' => $section_id,
					'subject_id' => $subject_id,
					'duration' => $duration,
					'teacher_id' => $teacher_id,
					'pass' => $pass,
					'questions' => $questions,
					'exam_code' => $exam_code,
					'year' => $year
				)
			);
		}
	}
}

if(isset($_GET['action']) == 'delete') {
	if(current_user_can( 'read' )) {
		$exam_code = sanitize_text_field($_GET['exam_code']);

		$wpdb->delete(
			$wpdb->prefix . 'sakolawp_exams',
			array( 
				'exam_code' => $exam_code
			)
		);
	}
}

get_header(); 
do_action( 'sakolawp_before_main_content' ); 

$all_exams = $wpdb->get_results( "SELECT exam_code FROM {$wpdb->prefix}sakolawp_exams", ARRAY_A );
$total_all_exams = $wpdb->num_rows;

$my_exams = $wpdb->get_row( "SELECT teacher_id FROM {$wpdb->prefix}sakolawp_exams WHERE teacher_id = $teacher_id"); ?>

<input id="teacher_id_sel" type="hidden" name="teacher_id_target" value="<?php echo esc_attr($teacher_id); ?>">

<?php if(!empty($my_exams)) :

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;

?>
<div class="exams-online-page skwp-content-inner">
	<div class="skwp-page-title skwp-clearfix">
		<h5 class="pull-left"><?php echo esc_html_e('Online Exams', 'sakolawp'); ?>
			<span class="skwp-subtitle">
				<?php echo esc_html($teacher_name); ?>
			</span>
		</h5>
	
		<div class="pull-right">
			<?php if($total_all_exams < 50) { ?>
				<a class="btn btn-primary btn-rounded btn-upper skwp-btn" href="#" data-target="#exampleModal1" data-toggle="modal" type="button"><?php echo esc_html__('Add New', 'sakolawp'); ?></a>
			<?php }
			else { ?>
				<a class="btn btn-primary btn-rounded btn-upper skwp-btn" href="#" data-target="#exampleModal2" data-toggle="modal" type="button"><?php echo esc_html__('Add New', 'sakolawp'); ?></a>
			<?php } ?>
		</div>
	</div>

	<div class="skwp-table table-responsive skwp-mt-20">
		<table id="tableini" class="table dataTable exams-table">
			<thead>
				<tr>
					<th class="online_exams"><?php esc_html_e('Title', 'sakolawp'); ?></th>
					<th><?php esc_html_e('Class', 'sakolawp'); ?></th>
					<th><?php esc_html_e('Subject', 'sakolawp'); ?></th>
					<th><?php esc_html_e('Date Start', 'sakolawp'); ?></th>
					<th><?php esc_html_e('Date End', 'sakolawp'); ?></th>
					<th><?php esc_html_e('Options', 'sakolawp'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$today = strtotime(date("m/d/Y"));
				$today2 = strtotime(date("d-m-Y"));

				$post = $wpdb->get_results( "SELECT title,class_id,section_id,subject_id,availablefrom,clock_start,availableto,clock_end,exam_code FROM {$wpdb->prefix}sakolawp_exams WHERE teacher_id = $teacher_id", ARRAY_A );
				foreach ($post as $row):
				?>
				<tr>
					<td class="tes">
						<?php echo esc_html($row['title']);?>
					</td>
					<td>
						<?php
							$class_id = $row['class_id'];
							$section_id = $row['section_id'];
							$class = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id");
							echo esc_html($class->name);

							echo esc_html__(' - ', 'sakolawp');

							$section = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $section_id");
							echo esc_html($section->name);
						?>
					</td>
					<td>
						<?php $subject_id = $row['subject_id'];
							$subject = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = $subject_id");
							echo esc_html($subject->name);
						?>
					</td>
					<td>
						<a class="btn nc btn-rounded btn-sm btn-success skwp-btn">
							<?php echo esc_html($row['availablefrom']);?>
							<?php echo esc_html($row['clock_start']);?>
						</a>
					</td>
					<td>
						<a class="btn nc btn-rounded btn-sm btn-danger skwp-btn">
							<?php echo esc_html($row['availableto']);?>
							<?php echo esc_html($row['clock_end']);?>
						</a>
					</td>
					<td class="row-actions">
						<a class="btn btn-rounded btn-sm btn-primary skwp-btn" href="<?php echo add_query_arg( 'exam_code', esc_html($row['exam_code']), home_url( 'examroom' ) );?>">
							<i class="picons-thin-icon-thin-0071_document_file_paper"></i><span><?php esc_html_e('View', 'sakolawp'); ?></span>
						</a>
						<a class="btn btn-rounded btn-sm btn-danger skwp-btn" href="<?php echo add_query_arg( array('exam_code' => esc_html($row['exam_code']), 'action' => 'delete'), home_url( 'online_exams' ) );?>" onClick="return confirm('Konfirmasi Hapus')">
							<span><?php esc_html_e('Delete', 'sakolawp'); ?></span>
						</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php 
else :
	echo esc_html_e('You are not create an exams for your class yet', 'sakolawp' ); ?>
	<div style="margin-bottom:15px;text-align:right;">
		<button class="btn btn-primary btn-rounded btn-upper" data-target="#exampleModal1" data-toggle="modal" type="button"><?php esc_html_e('Add New Exam', 'sakolawp'); ?></button>
	</div>
	<?php
endif;
?>

<div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade bd-example-modal-lg" id="exampleModal1" role="dialog" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Add New Exam', 'sakolawp'); ?></h5>
				<button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
			</div>
			<div class="modal-body">
				<form id="myForm" name="create_online_exams" action="" method="POST">
					<input type="hidden" name="sakola_add_exams_csrf" value="<?php echo wp_create_nonce('sakola-add-exams-csrf'); ?>" />
					<div class="skwp-clearfix skwp-row">
						<div class="skwp-column skwp-column-3">
							<div class="form-group">
								<label class="col-form-label" for=""><?php esc_html_e('Class', 'sakolawp'); ?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="os-icon picons-thin-icon-thin-0003_write_pencil_new_edit"></i>
									</div>
									<!-- id="class_changer" -->
									<select class="form-control" name="class_id" required="" id="class_holder">
										<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
										<?php $cl = $wpdb->get_results( "SELECT class_id, name FROM {$wpdb->prefix}sakolawp_class", ARRAY_A );
											foreach($cl as $row):
										?>
											<option value="<?php echo esc_attr($row['class_id']);?>"><?php echo esc_html($row['name']);?></option>
										<?php endforeach;?>
									</select>
								</div>
							</div>
						</div>
						<div class="skwp-column skwp-column-3">
							<div class="form-group">
								<label class="col-form-label" for=""><?php esc_html_e('Section', 'sakolawp'); ?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="os-icon picons-thin-icon-thin-0002_write_pencil_new_edit"></i>
									</div>
									<select class="form-control teacher-section" name="section_id" required="" id="section_holder">
										<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
									</select>
								</div>
							</div>
						</div>
						<div class="skwp-column skwp-column-3">
							<div class="form-group">
								<label class="col-form-label" for=""><?php esc_html_e('Subject', 'sakolawp'); ?></label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="picons-thin-icon-thin-0004_pencil_ruler_drawing"></i>
									</div>
									<select class="form-control" name="subject_id" id="subject_holder" required="">
										<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label for=""> <?php esc_html_e('Title', 'sakolawp'); ?></label><input class="form-control" required="" name="title" required="" type="text">
					</div>
					<div class="form-group">
						<label> <?php esc_html_e('Description', 'sakolawp'); ?></label><textarea cols="80" id="editordatateacherbasic" name="description" rows="2"></textarea>
					</div>
					<div class="skwp-clearfix skwp-row">
						<div class="skwp-column skwp-column-2">
							<div class="form-group">
								<label for=""> <?php esc_html_e('Exam Start', 'sakolawp'); ?></label><input class="single-daterange form-control" required="" name="availablefrom" type="text" value="">
							</div>
						</div>
						<div class="skwp-column skwp-column-2">
							<label for=""> <?php esc_html_e('Start Time', 'sakolawp'); ?></label>
							<div class="input-group clockpicker" data-align="top" data-autoclose="true">
								<input type="text" required="" class="form-control" name="clock_start" value="09:30">
								<span class="input-group-addon">
									<span class="picons-thin-icon-thin-0029_time_watch_clock_wall"></span>
								</span>
							</div>
						</div>
						<div class="skwp-column skwp-column-2">
							<div class="form-group">
								<label for=""> <?php esc_html_e('Exam End', 'sakolawp'); ?></label><input class="single-daterange form-control" name="availableto" required type="text" value="">
							</div>
						</div>
						<div class="skwp-column skwp-column-2">
							<label for=""> <?php esc_html_e('Time Over', 'sakolawp'); ?></label>
							<div class="input-group clockpicker" data-align="top" data-autoclose="true">
								<input type="text" required="" name="clock_end" class="form-control" value="09:30">
								<span class="input-group-addon">
									<span class="picons-thin-icon-thin-0029_time_watch_clock_wall"></span>
								</span>
							</div>
						</div>
					</div>
					<div class="skwp-clearfix skwp-row">
						<div class="skwp-column skwp-column-2">
							<div class="form-group">
								<label for=""> <?php esc_html_e('Total Questions', 'sakolawp'); ?></label><input class="form-control" required placeholder="Questions" type="number" name="questions">
							</div>
						</div>
						<div class="skwp-column skwp-column-2">
							<div class="form-group">
								<label for=""> <?php esc_html_e('Duration (Minutes)', 'sakolawp'); ?></label><input class="form-control" required="" type="number" name="duration">
							</div>
						</div>
						<div class="skwp-column skwp-column-2">
							<div class="form-group">
								<label for=""> <?php esc_html_e('Minimum Test Score', 'sakolawp'); ?></label><input class="form-control" name="pass" required="" type="text">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer skwp-form-button">
					<button class="btn btn-rounded btn-success skwp-btn" name="submit" value="submit" type="submit"> <?php esc_html_e('Create', 'sakolawp'); ?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade bd-example-modal-lg" id="exampleModal2" role="dialog" tabindex="-1">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Add New Exam', 'sakolawp'); ?></h5>
				<button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true"> &times;</span></button>
			</div>
			<div class="modal-body">
				<?php esc_html_e('Cannot create new exam because it reach the limit. Contact administrator to buy sakolaWP PRO.', 'sakolawp'); ?>
			</div>
			<div class="modal-footer skwp-form-button">
				<button type="button" class="btn btn-danger skwp-btn" data-dismiss="modal"><?php echo esc_html__( 'Close', 'sakolawp' ); ?></button>
			</div>
		</div>
	</div>
</div>

<?php
do_action( 'sakolawp_after_main_content' );
get_footer();