<?php
defined( 'ABSPATH' ) || exit;

if(isset($_POST['submit'])) {
	$class_id = sanitize_text_field($_POST['class_id']);
	$section_id = sanitize_text_field($_POST['section_id']);
	$year_sel = sanitize_text_field($_POST['year_sel']);
	$month = sanitize_text_field($_POST['month']);

	wp_redirect(add_query_arg(array('class_id' => intval($class_id), 'section_id' => intval($section_id), 'month' => intval($month), 'year_sel' => intval($year_sel)), home_url( 'report_attendance_view' ) ));
	die;
}

get_header(); 
do_action( 'sakolawp_before_main_content' ); 

global $wpdb;

$running_year = get_option('running_year');

$student_id = get_current_user_id();

$user_info = get_userdata($student_id);
$student_name = $user_info->display_name;

$enroll = $wpdb->get_row( "SELECT class_id, section_id FROM {$wpdb->prefix}sakolawp_enroll WHERE student_id = $student_id");
if(!empty($enroll)) : ?>

<div class="attendance-page skwp-content-inner skwp-clearfix">
	
	<div class="skwp-page-title no-border">
		<h5><?php esc_html_e('Attendance', 'sakolawp'); ?></h5>
	</div>

	<form id="myForm" name="save_student_attendance" action="" method="POST">
		<div class="skwp-row">
			<input type="hidden" name="class_id" value="<?php echo esc_attr($enroll->class_id); ?>">
			<input type="hidden" name="section_id" value="<?php echo esc_attr($enroll->section_id); ?>">
			<div class="skwp-column skwp-column-3">
				<div class="form-group">  
					<label class="gi" for=""><?php echo esc_html__( 'Month', 'sakolawp' ); ?></label> 
					<select name="month" class="form-control" id="month">
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
						<option value="<?php echo esc_attr($year[0]);?>"><?php echo esc_html($year[0]);?></option>
						<option value="<?php echo esc_attr($year[1]);?>"><?php echo esc_html($year[1]);?></option>
					</select>
				</div>
			</div>
			<div class="skwp-column skwp-column-3">
				<div class="form-group skwp-mt-20"> 
					<button class="btn btn-rounded btn-success btn-upper skwp-btn" type="submit" name="submit" value="submit">
						<span><?php echo esc_html__('Search Attendance', 'sakolawp'); ?></span>
					</button>
				</div>
			</div>
		</div>
	</form>
</div>

<?php

else :
	esc_html_e('You are not assign to a class yet', 'sakolawp' );
endif;

do_action( 'sakolawp_after_main_content' );
get_footer();