<?php
/**
 * This Template is used for displaying custom crons.
 *
 * @author Tech Banker
 * @package wp-cleanup-optimizer/views/cron-jobs
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//exit if accessed directly
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
	if ( isset( $user_role_permission ) && count( $user_role_permission ) > 0 ) {
		foreach ( $user_role_permission as $permission ) {
			if ( current_user_can( $permission ) ) {
				$access_granted = true;
				break;
			}
		}
	}
	if ( ! $access_granted ) {
		return;
	} elseif ( CRON_JOBS_CLEAN_UP_OPTIMIZER === '1' ) {
		?>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-custom-home"></i>
					<a href="admin.php?page=cpo_dashboard">
						<?php echo esc_attr( $cpo_clean_up_optimizer ); ?>
					</a>
					<span>></span>
				</li>
				<li>
					<a href="admin.php?page=cpo_core_jobs">
						<?php echo esc_attr( $cpo_cron_jobs_label ); ?>
					</a>
					<span>></span>
				</li>
				<li>
					<span>
						<?php echo esc_attr( $cpo_cron_custom_jobs_label ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-layers"></i>
							<?php echo esc_attr( $cpo_cron_custom_jobs_label ); ?>
						</div>
						<p class="premium-editions-clean-up-optimizer">
							<?php echo esc_attr( $cpo_upgrade_know_about ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>" target="_blank" class="premium-editions-documentation"> <?php echo esc_attr( $cpo_full_features ); ?></a> <?php echo esc_attr( $cpo_chek_our ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>/backend-demos" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpo_online_demos ); ?></a>
						</p>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_custom_cron_jobs">
							<div class="form-body">
								<div class="table-margin-top">
									<select name="ux_ddl_custom_cron_jobs" id="ux_ddl_custom_cron_jobs" class="custom-bulk-width">
										<option value=""><?php echo esc_attr( $cpo_bulk_action_dropdown ); ?></option>
										<option value="delete" style="color: red;"><?php echo esc_attr( $cpo_delete ) . ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></option>
									</select>
									<input type="button" id="ux_btn_apply" name="ux_btn_apply" class="btn vivid-green" value="<?php echo esc_attr( $cpo_apply ); ?>" onclick="premium_edition_notification_clean_up_optimizer();">
								</div>
								<table class="table table-striped table-bordered table-hover" id="ux_tbl_data_table_custom_cron">
									<thead>
										<tr>
											<th class="chk-action">
												<input type="checkbox" id="ux_chk_select_all_scheduler" name="ux_chk_select_all_scheduler" style="margin:1px 0px 0px 1px" />
											</th>
											<th scope="col" style="width:25%;">
												<?php echo esc_attr( $cpo_name_hook_label ); ?>
											</th>
											<th scope="col" style="width:25%;">
												<?php echo esc_attr( $cpo_interval_hook_label ); ?>
											</th>
											<th scope="col" style="width:25%;">
												<?php echo esc_attr( $cpo_args_label ); ?>
											</th>
											<th scope="col" style="width:25%;">
												<?php echo esc_attr( $cpo_next_execution_label ); ?>
											</th>
										</tr>
									</thead>
									<tbody class="all_wp_chks">
										<?php
										$flag = 0;
										if ( isset( $schedulers ) && count( $schedulers ) > 0 ) {
											foreach ( $schedulers as $time => $time_cron_array ) {
												foreach ( $time_cron_array as $hook => $data ) {
													if ( ! in_array( $hook, $core_cron_hooks, true ) ) {
														$times_class = CLEAN_UP_OPTIMIZER_LOCAL_TIME;
														?>
														<tr>
														<td>
															<input type="checkbox" id="ux_chk_schedulers_<?php echo intval( $flag ); ?>" name="ux_chk_schedulers_<?php echo intval( $flag ); ?>" value=<?php echo $hook; // WPCS:XSS ok. ?> onclick="check_all_clean_up_optimizer('#ux_chk_select_all_scheduler');" />
														</td>
														<td class="custom-alternative" style="text-align:left;">
															<?php echo esc_attr( wp_strip_all_tags( $hook ) ); ?><br/>
															<a href="#data-table-custom-cron" onclick="premium_edition_notification_clean_up_optimizer();"><?php echo esc_attr( $cpo_delete ); ?></a>
														</td>
														<?php
														foreach ( $data as $hash => $info ) {
															?>
															<td>
																<?php
																if ( empty( $info['interval'] ) ) {
																	echo 'Single Event';
																} else {
																	if ( array_key_exists( $info['schedule'], $schedule_details ) ) {
																		echo esc_attr( $schedule_details[ $info['schedule'] ]['display'] );
																	} else {
																		echo esc_attr( $info['schedule'] );
																	}
																}
																?>
															</td>
															<td>
																<?php
																if ( is_array( $info['args'] ) && ! empty( $info['args'] ) ) {
																	foreach ( $info['args'] as $key => $value ) {
																		display_cron_arguments_clean_up_optimizer( $key, $value );
																	}
																} elseif ( is_string( $info['args'] ) && '' !== $info['args'] ) {
																	echo esc_html( $info['args'] );
																} else {
																	echo 'No Args';
																}
																?>
															</td>
															<td <?php echo esc_attr( $times_class ); ?>>
																<label style="display:none;"><?php echo esc_attr( $time ); ?></label>
																	<?php
																	$current_offset = get_option( 'gmt_offset' ) * 60 * 60;
																	echo date_i18n( 'd M, Y g:i A e', $time + $current_offset ) . '<br /> <b>In About ' . human_time_diff( $time ) . '</b>' // WPCS: XSS ok.
																	?>
															</td>
														</tr>
															<?php
														}
													}
													$flag++;
												}
											}
										}
										?>
									</tbody>
								</table>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="icon-custom-home"></i>
					<a href="admin.php?page=cpo_dashboard">
						<?php echo esc_attr( $cpo_clean_up_optimizer ); ?>
					</a>
					<span>></span>
				</li>
				<li>
					<a href="admin.php?page=cpo_core_jobs">
						<?php echo esc_attr( $cpo_cron_jobs_label ); ?>
					</a>
					<span>></span>
				</li>
				<li>
					<span>
						<?php echo esc_attr( $cpo_cron_custom_jobs_label ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
						<i class="icon-custom-layers"></i>
							<?php echo esc_attr( $cpo_cron_custom_jobs_label ); ?>
						</div>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_custom_cron_jobs">
						<div class="form-body">
							<strong><?php echo esc_attr( $cpo_roles_capabilities_message ); ?></strong>
						</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
