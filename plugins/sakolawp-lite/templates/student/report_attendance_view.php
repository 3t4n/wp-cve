<?php
defined( 'ABSPATH' ) || exit;

if(isset($_POST['submit'])) {

	$class_id_t = sanitize_text_field($_POST['class_id']);
	$section_id_t = sanitize_text_field($_POST['section_id']);
	$year_sel = sanitize_text_field($_POST['year_sel']);
	$month = sanitize_text_field($_POST['month']);

	wp_redirect(add_query_arg(array('class_id' => intval($class_id_t), 'section_id' => intval($section_id_t), 'month' => intval($month), 'year_sel' => intval($year_sel)), home_url( 'report_attendance_view' ) ));

	die;
}

get_header(); 
do_action( 'sakolawp_before_main_content' ); 

global $wpdb;

$running_year = get_option('running_year');

$student_id = get_current_user_id();

$user_info = get_userdata($student_id);
$student_name = $user_info->display_name;

$class_id = sanitize_text_field($_GET['class_id']);
$section_id = sanitize_text_field($_GET['section_id']);
$month = sanitize_text_field($_GET['month']);
$year_sel = sanitize_text_field($_GET['year_sel']);

$enroll = $wpdb->get_row( "SELECT class_id, section_id FROM {$wpdb->prefix}sakolawp_enroll WHERE student_id = $student_id");
if(!empty($enroll)) :

$class = $wpdb->get_row( "SELECT class_id FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $enroll->class_id");
$section = $wpdb->get_row( "SELECT section_id FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $enroll->section_id"); ?>

<style>
	.attendance-page form {
		margin-bottom: 30px;
	}
</style>

<div class="attendance-page skwp-content-inner skwp-clearfix">
	
	<div class="skwp-page-title no-border">
		<h5><?php esc_html_e('Attendance', 'sakolawp'); ?></h5>
	</div>
	
	<form id="myForm" name="save_student_attendance" action="" method="POST">
		<div class="skwp-row">
			<input type="hidden" name="class_id" value="<?php echo esc_attr($class->class_id); ?>">
			<input type="hidden" name="section_id" value="<?php echo esc_attr($section->section_id); ?>">
			<input type="hidden" name="operation" value="selection">
			<div class="skwp-column skwp-column-3">
				<div class="form-group">  <label><?php echo esc_html__( 'Month', 'sakolawp' ); ?></label>
					<select name="month" class="form-control" id="month" onchange="show_year()">
					<?php
						for ($i = 1; $i <= 12; $i++):
						if ($i == 1)
							$m = esc_html__( 'January', 'sakolawp' );
						else if ($i == 2)
							$m = esc_html__( 'February', 'sakolawp' );
						else if ($i == 3)
							$m = esc_html__( 'March', 'sakolawp' );
						else if ($i == 4)
							$m = esc_html__( 'April', 'sakolawp' );
						else if ($i == 5)
							$m = esc_html__( 'May', 'sakolawp' );
						else if ($i == 6)
							$m = esc_html__( 'June', 'sakolawp' );
						else if ($i == 7)
							$m = esc_html__( 'July', 'sakolawp' );
						else if ($i == 8)
							$m = esc_html__( 'August', 'sakolawp' );
						else if ($i == 9)
							$m = esc_html__( 'September', 'sakolawp' );
						else if ($i == 10)
							$m = esc_html__( 'October', 'sakolawp' );
						else if ($i == 11)
							$m = esc_html__( 'November', 'sakolawp' );
						else if ($i == 12)
							$m = esc_html__( 'December', 'sakolawp' );
						?>
						<option value="<?php echo esc_attr($i); ?>"<?php if($month == $i) echo esc_attr( 'selected' ); ?>  ><?php echo esc_html($m); ?></option>
					<?php endfor;?>
				</select>
				</div>
			</div>
			<div class="skwp-column skwp-column-3">
				<div class="form-group"><label><?php echo esc_html__( 'Year', 'sakolawp' ); ?></label>
					<select name="year_sel" class="form-control" required="">
						<option value=""><?php echo esc_html__('Select', 'sakolawp'); ?></option>
						<?php $year = explode('-', $running_year); ?>
						<option value="<?php echo esc_attr($year[0]);?>" <?php if($year_sel == $year[0]) echo esc_attr( 'selected' ); ?>><?php echo esc_html($year[0]);?></option>
						<option value="<?php echo esc_attr($year[1]);?>" <?php if($year_sel == $year[1]) echo esc_attr( 'selected' ); ?>><?php echo esc_html($year[1]);?></option>
					</select>
				</div>
			</div>
			<div class="skwp-column skwp-column-3">
				<div class="form-group skwp-mt-20"> 
					<button class="btn btn-rounded btn-success btn-upper skwp-btn" type="submit" name="submit" value="submit">
						<i class="fa fa-search"></i>
						<span><?php echo esc_html__('Search Attendance', 'sakolawp'); ?></span>
					</button>
				</div>
			</div>
		</div>
	</form>

	<?php if ($class_id != '' && $section_id != '' && $month != ''): ?>
	<div class="sakolawp-report-attendances skwp-clearfix">                
		<div class="skwp-page-title">
			<h5 class="skwp-title"><?php echo esc_html__('Attendance Report', 'sakolawp') .' '. esc_html($year_sel);?></h5>
		</div>

		<div class="skwp-table table-responsive">
			<table id="dataTableNot2" class="table attendance attendance-table">
				<thead>   
					<tr class="text-center" height="50px">
						<th class="text-left"><?php echo esc_html__('Date', 'sakolawp'); ?></th>
						<th class="text-left"><?php echo esc_html__('Status', 'sakolawp'); ?></th>
					</tr> 
				</thead>
				<tbody>
					<?php
					$year = explode('-', $running_year);
					$days = cal_days_in_month(CAL_GREGORIAN, $month, $year_sel);
					
					$attendance = get_option('sakolawp_routine');
					$libur = $attendance;

					$status = 0;
					for ($i = 1; $i <= $days; $i++) {
					$timestamps = strtotime($i . '-' . $month . '-' . $year_sel);
					$tanggal_asli = date('j M Y', $timestamps);
					$dayw2 = date('l', $timestamps);

					$attendances = $wpdb->get_results( "SELECT timestamp, status FROM {$wpdb->prefix}sakolawp_attendance WHERE class_id = '$class_id' AND section_id = '$section_id' AND year = '$running_year' AND timestamp = '$timestamps' AND student_id = '$student_id'", ARRAY_A );
					foreach ($attendances as $row1): 
					$month_dummy = date('d', $row1['timestamp']);
					$dayw = date('l', $row1['timestamp']);
					if ($i == $month_dummy) { $status = $row1['status']; }
					endforeach;

					?>
					<tr>
						<td><?php echo esc_html($tanggal_asli); ?></td>
						<td>
						<?php 
							if($libur == 2) {
								if($dayw2 == "Sunday" || $dayw2 == "Saturday" ) {
									echo esc_html__('Break', 'sakolawp');
								}
								if ($status == 1 && $dayw2 != "Sunday" && $dayw2 != "Saturday") {
									echo esc_html__('Present', 'sakolawp');
								} 
								if($status == 2 && $dayw2 != "Sunday" && $dayw2 != "Saturday" )  {
									echo esc_html__('Not Present', 'sakolawp');
								} 
								if($status == 3 && $dayw2 != "Sunday" && $dayw2 != "Saturday" )  {
									echo esc_html__('Present', 'sakolawp');
								} 
								if($status == 4 && $dayw2 != "Sunday" && $dayw2 != "Saturday" )  {
									echo esc_html__('Not Present', 'sakolawp');
								} 
								if($status == 5 && $dayw2 != "Sunday" && $dayw2 != "Saturday" )  {
									echo esc_html__('Not Present', 'sakolawp');
								} 
								if($status == 6 && $dayw2 != "Sunday" && $dayw2 != "Saturday" )  {
									
								} 
								if($status == 0 || $status == NULL)  { 
									
								} 
								else { 
									$status = 0; 
								}
							}
							else {
								if($dayw2 == "Sunday" ) {
									echo esc_html__('Break', 'sakolawp');
								}
								if ($status == 1 && $dayw2 != "Sunday") {
									echo esc_html__('Present', 'sakolawp');
								} 
								if($status == 2 && $dayw2 != "Sunday" )  {
									echo esc_html__('Not Present', 'sakolawp');
								} 
								if($status == 3 && $dayw2 != "Sunday" )  {
									echo esc_html__('Present', 'sakolawp');
								} 
								if($status == 4 && $dayw2 != "Sunday" )  {
									echo esc_html__('Not Present', 'sakolawp');
								} 
								if($status == 5 && $dayw2 != "Sunday" )  {
									echo esc_html__('Not Present', 'sakolawp');
								} 
								if($status == 6 && $dayw2 != "Sunday" )  {
									
								} 
								if($status == 0 || $status == NULL)  { 
									
								} 
								else { 
									$status = 0; 
								}
							} ?>
						</td> 
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<?php endif;?>
</div>

<?php

else :
	esc_html_e('You are not assign to a class yet', 'sakolawp' );
endif;

do_action( 'sakolawp_after_main_content' );
get_footer();