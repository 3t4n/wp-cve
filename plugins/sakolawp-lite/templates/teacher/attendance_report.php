<?php
defined( 'ABSPATH' ) || exit;

global $wpdb;

$running_year = get_option('running_year');

$teacher_id = get_current_user_id();

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;

if(isset($_POST['submit'])) {
	if(wp_verify_nonce($_POST['sakola_red_attendance_csrf'], 'sakola-red-attendance-csrf')) {
		if(current_user_can( 'read' )) {
			$class_id = sanitize_text_field($_POST['class_id']);
			$section_id = sanitize_text_field($_POST['section_id']);
			$year_sel = sanitize_text_field($_POST['year_sel']);
			$month = sanitize_text_field($_POST['month']);

			wp_redirect(add_query_arg(array('class_id' => intval($class_id), 'section_id' => intval($section_id), 'month' => intval($month), 'year_sel' => intval($year_sel)), home_url( 'report_attendance_view' ) ));
			die;
		}
	}
}

get_header(); 
do_action( 'sakolawp_before_main_content' );  ?>

<div class="attendance-page skwp-content-inner">
	
	<div class="skwp-page-title no-border">
		<h5><?php esc_html_e('Attendance Report', 'sakolawp'); ?></h5>
	</div>

	<div class="skwp-tab-menu">
		<ul class="skwp-tab-wrap">
			<li class="skwp-tab-items">
				<a class="skwp-tab-item" href="<?php echo esc_url(site_url('manage_attendance'));?>">
					<span><?php echo esc_html__( 'Attendances', 'sakolawp' ); ?></span>
				</a>
			</li>
			<li class="skwp-tab-items active">
				<a class="skwp-tab-item" href="<?php echo esc_url(site_url('attendance_report'));?>">
					<span><?php echo esc_html__( 'Attendances Report', 'sakolawp' ); ?></span>
				</a>
			</li>
		</ul>
	</div>
	<form id="myForm" name="save_student_attendance" action="" method="POST">
		<input type="hidden" name="sakola_red_attendance_csrf" value="<?php echo wp_create_nonce('sakola-red-attendance-csrf'); ?>" />
		<div class="skwp-clearfix skwp-row">
			<div class="skwp-column skwp-column-5">
				<div class="skwp-form-group"> <label class="gi" for=""><?php echo esc_html__( 'Class :', 'sakolawp' ); ?></label> 
					<?php 
					$section_teached = $wpdb->get_results( "SELECT class_id,name,section_id FROM {$wpdb->prefix}sakolawp_section WHERE teacher_id = $teacher_id");
					$selected_class = array();
					foreach ($section_teached as $the_class) {
						$selected_class[] = $the_class->class_id;
					}
					$listofclass = array_unique($selected_class); ?>
					<?php
						$classes = $wpdb->get_results( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $section_teached->class_id", ARRAY_A );
						$class_ajar = array();
						foreach($classes as $row):

							$class_ajar[] = $section_teached->class_id;
					?><!-- 
					<input type="text" class="form-control" required disabled value="<?php echo esc_attr($row['name']); ?>">
					<input type="hidden" class="form-control" name="class_id" required value="<?php echo esc_attr($section_teached->class_id); ?>"> -->
					<?php endforeach;
						$sellistofclass = implode(', ', $listofclass); ?>

					<select class="form-control" name="class_id" required="" id="class_holder_spe">
						<option value=""><?php echo esc_html__( 'Select', 'sakolawp' ); ?></option>
						<?php 
						global $wpdb;
						$classes = $wpdb->get_results( "SELECT name,class_id FROM {$wpdb->prefix}sakolawp_class WHERE class_id IN ($sellistofclass)", OBJECT );
						foreach($classes as $class):
						?>
						<option value="<?php echo esc_attr($class->class_id); ?>"><?php echo esc_html($class->name); ?></option>
						<?php endforeach;?>
					</select>
				</div>
			</div>
			<div class="skwp-column skwp-column-5">
				<div class="skwp-form-group"> <label class="gi" for=""><?php echo esc_html__( 'Section', 'sakolawp' ); ?></label> 
					<select class="form-control teacher-section" name="section_id" required="" id="section_holder_spe">
							<option value=""><?php esc_html_e('Select', 'sakolawp'); ?></option>
						</select>
				</div>
			</div>
			<div class="skwp-column skwp-column-5">
				<div class="skwp-form-group"> <label class="gi" for=""><?php echo esc_html__( 'Month', 'sakolawp' ); ?></label>
					<select name="month" class="skwp-form-control" id="month">
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
						<option value="<?php echo esc_attr($i); ?>" <?php if($month == $i) echo esc_attr( 'selected' ); ?>  >
							<?php echo esc_html($m); ?>
						</option>
						<?php
					endfor;
					?>
					</select>
				</div>
			</div>
			<div class="skwp-column skwp-column-5">
				<div class="skwp-form-group">
					<label class="gi" for=""><?php echo esc_html__( 'Year', 'sakolawp' ); ?></label>
					<select name="year_sel" class="skwp-form-control" required="">
						<option value=""><?php echo esc_html__( 'Select', 'sakolawp' ); ?></option>
						<?php $year = explode('-', $running_year); ?>
						<option value="<?php echo esc_attr($year[0]);?>"><?php echo esc_html($year[0]);?></option>
						<option value="<?php echo esc_attr($year[1]);?>"><?php echo esc_html($year[1]);?></option>
					</select>
				</div>
			</div>
			<div class="skwp-column skwp-column-5">
				<div class="skwp-form-group"> <button class="btn btn-rounded btn-success btn-upper skwp-btn skwp-mt-20" type="submit" name="submit" value="submit"><span><?php echo esc_html__( 'View', 'sakolawp' ); ?></span></button></div>
			</div>
		</div>
		<input type="hidden" name="operation" value="selection">
		<input type="hidden" name="year" value="<?php echo esc_attr($running_year); ?>">
	</form>
</div>

<?php
do_action( 'sakolawp_after_main_content' );
get_footer();