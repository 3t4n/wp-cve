 <?php
defined( 'ABSPATH' ) || exit;

get_header();
do_action( 'sakolawp_before_main_content' );

$running_year = get_option('running_year');

?>

<div class="sakolawp-marks-page skwp-content-inner skwp-clearfix">

	<div class="skwp-container">
		<div class="skwp-table table-responsive">
				<?php 

				$exams = $wpdb->get_results( "SELECT exam_id, name FROM {$wpdb->prefix}sakolawp_exam WHERE year = '$running_year'", ARRAY_A );
				foreach ($exams as $exam): 

				$student_id = get_current_user_id();
				$enroll = $wpdb->get_results( "SELECT class_id, section_id FROM {$wpdb->prefix}sakolawp_enroll WHERE student_id = $student_id AND year = '$running_year'", ARRAY_A ); ?>
				<div class="title-marks">
					<h3><?php echo esc_html($exam['name']); ?></h3>
				</div>
				<table id="tabbles" class="table table-marks table-lightborder">
					<thead>
						<tr>
							<th><?php echo esc_html__('Subject','sakolawp'); ?></th>
							<th><?php echo esc_html__('Teacher','sakolawp'); ?></th>
							<th><?php echo esc_html__('Marks','sakolawp'); ?></th>
							<th><?php echo esc_html__('Options','sakolawp'); ?></th>
						</tr>
					</thead>
					<tbody>
					<?php $class_id = $enroll[0]['class_id'];
					$section_id = $enroll[0]['section_id'];

					$subjects = $wpdb->get_results( "SELECT subject_id, teacher_id, name FROM {$wpdb->prefix}sakolawp_subject WHERE class_id = $class_id AND section_id = $section_id", ARRAY_A );
					foreach ($subjects as $subject): 
					$obtained_mark_query = $wpdb->get_results( "SELECT mark_obtained FROM {$wpdb->prefix}sakolawp_mark WHERE class_id = $class_id AND section_id = $section_id AND subject_id = {$subject['subject_id']} AND student_id = $student_id AND year = '$running_year' AND exam_id = {$exam['exam_id']}", ARRAY_A );

					foreach ($obtained_mark_query as $row):

					$subject2 = $wpdb->get_row( "SELECT total_lab FROM {$wpdb->prefix}sakolawp_subject WHERE subject_id = {$subject['subject_id']}");
					$total_kd = $subject2->total_lab; ?>
					<tr>
						<td><?php echo esc_html( $subject['name'] ); ?></td>
						<td><?php 
							$user_info = get_user_meta($subject['teacher_id']);
							$first_name = $user_info["first_name"][0];
							$last_name = $user_info["last_name"][0];

							$user_name = $first_name .' '. $last_name;

							if(empty($first_name)) {
								$user_info = get_userdata($current_id);
								$user_name = $user_info->display_name;
							}

							echo esc_html($user_name); ?></td>
						<td>
							<?php if(empty($total_kd)) { 
								$labtotal = $row['mark_obtained']; 
							}
							else {
								$mark2 = $wpdb->get_results( "SELECT mark_obtained, lab2, lab3, lab4, lab5, lab6, lab7, lab8, lab9, lab10 FROM {$wpdb->prefix}sakolawp_mark WHERE class_id = $class_id AND section_id = $section_id AND subject_id = {$subject['subject_id']} AND student_id = $student_id AND year = '$running_year' AND exam_id = {$exam['exam_id']}", ARRAY_A );
								$nilai = $mark2[0];

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
							}  
							if(empty($total_kd)) {
								echo esc_html($row['mark_obtained']);
							}
							else {
								echo esc_html(round($labtotal / $total_kd2, 1));
							} ?>
						</td>
						<td>
							<a href="<?php echo add_query_arg( array('exam_id' => intval($exam['exam_id']), 'student_id' => intval($student_id), 'subject_id' => intval($subject['subject_id'])), home_url( 'view_mark' ) );?>" class="btn btn-rounded btn-success skwp-btn"><?php echo esc_html__( 'View', 'sakolawp' ); ?></a>
						</td>
					</tr>
				<?php endforeach; endforeach; ?>
				</tbody>
			</table>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<?php
do_action( 'sakolawp_after_main_content' );
get_footer();
