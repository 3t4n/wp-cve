<?php
defined( 'ABSPATH' ) || exit;

get_header();
do_action( 'sakolawp_before_main_content' ); 

$running_year = get_option('running_year');

$teacher_id = get_current_user_id();

$my_routines = $wpdb->get_row( "SELECT teacher_id FROM {$wpdb->prefix}sakolawp_class_routine WHERE teacher_id = $teacher_id");

if(!empty($my_routines)) :

$user_info = get_userdata($teacher_id);
$teacher_name = $user_info->display_name;

?>

<div class="skwp-page-title">
	<h5><?php esc_html_e('My Class Routines', 'sakolawp'); ?>
		<span class="skwp-subtitle">
			<?php echo esc_html($teacher_name); ?>
		</span>
	</h5>
</div>

<div class="routines-table skwp-content-inner">

	<table class="skwp-table table table-schedule table-hover" cellpadding="0" cellspacing="0">
	<?php 
		$days = get_option('sakolawp_routine');
		if(!empty($days)) {
			$days = $days;
		} else {
			$days = 1;
		}
		if($days == 2) { $nday = 6;}else{$nday = 7;}
		for($d=$days; $d <= $nday; $d++):
		if($d==1) { 
			$day = esc_html('Sunday'); 
			$day2 = esc_html__('Sun', 'sakolawp'); 
		}
		elseif($d==2) { 
			$day = esc_html('Monday');
			$day2 = esc_html__('Mon', 'sakolawp'); 
		}
		elseif($d==3) {
			$day = esc_html('Tuesday');
			$day2 = esc_html__('Tue', 'sakolawp');
		}
		elseif($d==4) {
			$day = esc_html('Wednesday'); 
			$day2 = esc_html__('Wed', 'sakolawp');
		}
		elseif($d==5) {
			$day = esc_html('Thursday'); 
			$day2 = esc_html__('Thr', 'sakolawp');
		}
		elseif($d==6) {
			$day = esc_html('Friday'); 
			$day2 = esc_html__('Fri', 'sakolawp');
		}
		elseif($d==7) {
			$day = esc_html('Saturday'); 
			$day2 = esc_html__('Sat', 'sakolawp');
		}
	?>
		<tr>
			<table class="table table-schedule table-hover text-center" cellpadding="0" cellspacing="0">
				<td width="120" height="90"><strong><?php echo esc_html($day2);?></strong></td>
				<?php
					$routines = $wpdb->get_results( "SELECT time_start_min, time_end_min, time_start, time_end, subject_id, class_id, section_id  FROM {$wpdb->prefix}sakolawp_class_routine 
						WHERE day = '$day' 
						AND teacher_id = $teacher_id 
						AND year = '$running_year'
						ORDER BY time_start ASC", ARRAY_A );
					foreach($routines as $row2):
				?>
				<td>
					<?php
						if ($row2['time_start_min'] == 0 && $row2['time_end_min'] == 0) 
						echo esc_html($row2['time_start']).':'.esc_html($row2['time_start_min']).'-'.esc_html($row2['time_end']).':'.esc_html($row2['time_end_min']);
						if ($row2['time_start_min'] != 0 || $row2['time_end_min'] != 0)
						echo esc_html($row2['time_start']).':'.esc_html($row2['time_start_min']).'-'.esc_html($row2['time_end']).':'.esc_html($row2['time_end_min']);
					?>
					<br>
					<b>
						<?php $subject_id = $row2['subject_id'];
							$subject = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = $subject_id");
							echo esc_html($subject->name);
						?>
					</b>
					<br>
					<small>
						<?php 
						$class_id = $row2['class_id'];
						$section_id = $row2['section_id'];
							$class = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_class WHERE class_id = $class_id");
							echo esc_html($class->name);

							echo esc_html__(' - ', 'sakolawp');

							$section = $wpdb->get_row( "SELECT name FROM {$wpdb->prefix}sakolawp_section WHERE section_id = $section_id");
							echo esc_html($section->name);
						?>
					</small>
				</td>
				<?php endforeach; ?>
			</table>
		</tr>
		<?php endfor;?>
	</table>
</div>

<?php 
else :
	_e('You are not having a routine yet', 'sakolawp' );
endif;

do_action( 'sakolawp_after_main_content' );
get_footer();