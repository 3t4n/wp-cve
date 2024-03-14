<?php
defined( 'ABSPATH' ) || exit;

global $wpdb;

if(isset($_POST['submit_filter'])) {
	if(wp_verify_nonce($_POST['sakola_report_att_csrf'], 'sakola-report-att-csrf')) {
		if(current_user_can( 'read' )) {
			$class_id = sanitize_text_field($_POST['class_id']);
			$section_id = sanitize_text_field($_POST['section_id']);
			$year_sel = sanitize_text_field($_POST['year_sel']);
			$month = sanitize_text_field($_POST['month']);

			wp_redirect(add_query_arg(array('class_id' => intval($class_id), 'section_id' => intval($class_id), 'month' => intval($month), 'year_sel' => intval($year_sel)), home_url( 'report_attendance_view' ) ));
			die;
		}
	}
}

get_header();

do_action( 'sakolawp_before_main_content' );

$running_year = get_option('running_year');

$teacher_id = get_current_user_id();

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;

$class_id = sanitize_text_field($_GET['class_id']);
$section_id = sanitize_text_field($_GET['section_id']);
$month = sanitize_text_field($_GET['month']);
$year_sel = sanitize_text_field($_GET['year_sel']);  ?>

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
		<input type="hidden" name="sakola_report_att_csrf" value="<?php echo wp_create_nonce('sakola-report-att-csrf'); ?>" />
		<div class="skwp-clearfix skwp-row">
			<div class="skwp-column skwp-column-5">
				<div class="form-group"> <label class="gi" for=""><?php echo esc_html__( 'Class', 'sakolawp' ); ?></label> 
					<?php 
					$section_teached = $wpdb->get_row( "SELECT class_id, name, section_id FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $section_id"); ?>
					<?php
						$classes = $wpdb->get_results( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id", ARRAY_A );
						foreach($classes as $row):
					?>
					<input type="text" class="skwp-form-control" required disabled value="<?php echo esc_attr($row['name']); ?>">
					<input type="hidden" class="skwp-form-control" name="class_id" required value="<?php echo esc_attr($section_teached->class_id); ?>">
					<?php endforeach; ?>
				</div>
			</div>
			<div class="skwp-column skwp-column-5">
				<div class="form-group"> <label class="gi" for=""><?php echo esc_html__( 'Section', 'sakolawp' ); ?></label> 
					<input type="text" class="form-control" required disabled value="<?php echo esc_attr( $section_teached->name ); ?>">
					<input type="hidden" class="form-control" name="section_id" required value="<?php echo esc_attr( $section_teached->section_id ); ?>">
				</div>
			</div>
			<div class="skwp-column skwp-column-5">
				<div class="form-group"> <label class="gi" for=""><?php echo esc_html__( 'Month', 'sakolawp' ); ?></label>
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
				<div class="form-group">
					<label class="gi" for=""><?php echo esc_html__( 'Year', 'sakolawp' ); ?></label>
					<select name="year_sel" class="form-control" required="">
						<option value=""><?php echo esc_html__( 'Select', 'sakolawp' ); ?></option>
						<?php $year = explode('-', $running_year); ?>
						<option value="<?php echo esc_attr($year[0]);?>" <?php if($year_sel == $year[0]) echo esc_attr( 'selected' );?>><?php echo esc_html($year[0]);?></option>
						<option value="<?php echo esc_attr($year[1]);?>" <?php if($year_sel == $year[1]) echo esc_attr( 'selected' );?>><?php echo esc_html($year[1]);?></option>
					</select>
				</div>
			</div>
			<div class="skwp-column skwp-column-5">
				<div class="form-group"> <button class="btn btn-success btn-rounded btn-upper skwp-btn skwp-mt-20" type="submit" name="submit_filter" value="submit_filter"><span><?php echo esc_html__( 'View', 'sakolawp' ); ?></span></button></div>
			</div>
		</div>
		<input type="hidden" name="operation" value="selection">
		<input type="hidden" name="year" value="<?php echo esc_attr($running_year);?>">
	</form>

	<?php if ($class_id != '' && $section_id != '' && $month != ''):
	$year = explode('-', $running_year); ?>
	<div class="skwp-page-title no-border skwp-mt-20">
		<h5>
			<?php 
			if ($month == 1) {$mo = esc_html__('January', 'sakolawp');}
			else if ($month == 2)  {$mo = esc_html__('February', 'sakolawp');}
			else if ($month == 3)  {$mo = esc_html__('March', 'sakolawp');}
			else if ($month == 4)  {$mo = esc_html__('April', 'sakolawp');}
			else if ($month == 5)  {$mo = esc_html__('May', 'sakolawp');}
			else if ($month == 6)  {$mo = esc_html__('June', 'sakolawp');}
			else if ($month == 7)  {$mo = esc_html__('July', 'sakolawp');}
			else if ($month == 8)  {$mo = esc_html__('August', 'sakolawp');}
			else if ($month == 9)  {$mo = esc_html__('September', 'sakolawp');}
			else if ($month == 10) {$mo = esc_html__('October', 'sakolawp');}
			else if ($month == 11) {$mo = esc_html__('November', 'sakolawp');}
			else if ($month == 12) {$mo = esc_html__('December', 'sakolawp');} echo esc_html($mo) .' '. esc_html($year_sel);?>
		</h5>
	</div>

	<ul class="skwp-legend-attendance">
		<li><span class="status-pilli green" data-title="Present"></span> <?php echo esc_html__( 'Present', 'sakolawp' ); ?></li>
		<li><span class="status-pilli yellow" data-title="Late"></span> <?php echo esc_html__( 'Late', 'sakolawp' ); ?></li>
		<li><span class="status-pilli red" data-title="Absent"></span> <?php echo esc_html__( 'Absent', 'sakolawp' ); ?></li>
		<li data-title="Sakit"><span class="status-pilli orange" data-title="Absent"></span> <?php echo esc_html__( 'Sick', 'sakolawp' ); ?></li>
		<li data-title="Izin"><span class="status-pilli transpar" data-title="Absent"></span> <?php echo esc_html__( 'Permit', 'sakolawp' ); ?></li>
		<li data-title="Izin"><span class="status-pilli blue" data-title="Day Off"></span> <?php echo esc_html__( 'Day Off', 'sakolawp' ); ?></li>
	</ul>
	<div class="skwp-table table-responsive skwp-mt-10">
		<table id="laporanKehadiranSiswa" class="table table-sm table-lightborder">
			<thead>
				<tr height="40px">
					<th class="text-name">
						<?php echo esc_html__( 'Name', 'sakolawp' ); ?>
					</th>
					<?php
					$year = explode('-', $running_year);
					$days = cal_days_in_month(CAL_GREGORIAN, $month, $year_sel);
					for ($i = 1; $i <= $days; $i++) 
					{
					?>
					<th>
						<?php echo intval($i); ?>
						</th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php 
					$attendance = get_option('sakolawp_routine');
					$libur = $attendance;
					
					$students = $wpdb->get_results( "SELECT student_id FROM {$wpdb->prefix}sakolawp_enroll WHERE class_id = $class_id AND section_id = $section_id AND year = '$running_year'", ARRAY_A );
					foreach ($students as $row):?>
				<tr>
					<td class="name"> 
						<?php 
						global $wp;
						$user_info = get_userdata($row['student_id']);
						$user_name = $user_info->display_name;
						
						$user_info = get_userdata($row['student_id']);
						echo esc_html($user_info->display_name); ?> 
					</td>
					<?php
					$status = 0;
					for ($i = 1; $i <= $days; $i++) 
					{ ?>
					<td class="text-center"> 
						<?php 
						$cek_tanggal = date("j-n-Y", strtotime($month.'/'.$i.'/'.$year_sel));
						$cek_tgl2 = strtotime($cek_tanggal);
						$dayw3 = date('l', $cek_tgl2);

						if($libur == 2) {
							if($dayw3 == "Sunday" || $dayw3 == "Saturday") { ?>
								<div class="status-pilli blue" data-title="Day Off" data-toggle="tooltip"></div>
							<?php }
						}
						else {
							if($dayw3 == "Sunday") { ?>
								<div class="status-pilli blue" data-title="Day Off" data-toggle="tooltip"></div>
							<?php }
						}

						$student_id = $row['student_id'];
						
						$absens = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}sakolawp_attendance_log WHERE class_id = $class_id AND month = '$month' AND year = '$year_sel' AND section_id = $section_id AND student_id = '$student_id'", ARRAY_A );
						foreach ($absens as $absen):
						$timestamps = $absen['time_'.$i];
						$dayw2 = date('l', $timestamps);
						$status = $absen['day_'.$i]; ?>

						<?php 
						if ($status == 1) { ?>
							<div class="status-pilli green" data-title="Present" data-toggle="tooltip"></div>
						<?php } 
						if($status == 2 )  { ?>
							<div class="status-pilli red" data-title="Absent" data-toggle="tooltip"></div>
						<?php } 
						if($status == 3 )  { ?>
							<div class="status-pilli yellow" data-title="Late" data-toggle="tooltip"></div>
						<?php } 
						if($status == 4 )  { ?>
							<div class="status-pilli orange" data-title="Sakit" data-toggle="tooltip"></div>
						<?php } 
						if($status == 5 )  { ?>
							<div class="status-pilli transpar" data-title="Izin" data-toggle="tooltip"></div>
						<?php } 
						if($status == 6 )  { ?>
							<div class="status-pilli blue" data-title="Day Off" data-toggle="tooltip"></div>
						<?php } 
						if($status == 0 || $status == NULL)  { ?> 
						
						<?php } ?>
					</td>
					<?php endforeach;
					} ?>
				</tr>
					<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr height="40px">
					<th class="text-name">
						<?php echo esc_html__( 'Name', 'sakolawp' ); ?>
					</th>
					<?php
					$year = explode('-', $running_year);
					$days = cal_days_in_month(CAL_GREGORIAN, $month, $year_sel);
					for ($i = 1; $i <= $days; $i++) 
					{
					?>
					<th>
						<?php echo esc_html($i); ?>
						</th>
					<?php } ?>
				</tr>
			</tfoot>
		</table>

	</div>
	<?php endif;?>

	<ul class="skwp-legend-attendance skwp-mt-30">
		<li><span class="status-pilli green" data-title="Present"></span> <?php echo esc_html__( 'Present', 'sakolawp' ); ?></li>
		<li><span class="status-pilli yellow" data-title="Late"></span> <?php echo esc_html__( 'Late', 'sakolawp' ); ?></li>
		<li><span class="status-pilli red" data-title="Absent"></span> <?php echo esc_html__( 'Absent', 'sakolawp' ); ?></li>
		<li data-title="Sakit"><span class="status-pilli orange" data-title="Absent"></span> <?php echo esc_html__( 'Sick', 'sakolawp' ); ?></li>
		<li data-title="Izin"><span class="status-pilli transpar" data-title="Absent"></span> <?php echo esc_html__( 'Permit', 'sakolawp' ); ?></li>
		<li data-title="Izin"><span class="status-pilli blue" data-title="Day Off"></span> <?php echo esc_html__( 'Day Off', 'sakolawp' ); ?></li>
	</ul>
</div>

<?php

do_action( 'sakolawp_after_main_content' );
get_footer();