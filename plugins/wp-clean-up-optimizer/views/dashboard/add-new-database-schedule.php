<?php
/**
 * This Template is used for adding new schedulers of database.
 *
 * @author Tech Banker
 * @package wp-cleanup-optimizer/views/dashboard
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
	} elseif ( SCHEDULE_DB_OPTIMIZER_CLEAN_UP_OPTIMIZER === '1' ) {
		global $wp_version;
		$start_time = explode( ',', isset( $get_array['start_time_database'] ) ? $get_array['start_time_database'] : '' );
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
					<a href="admin.php?page=cpo_dashboard">
						<?php echo esc_attr( $cpo_dashboard ); ?>
					</a>
					<span>></span>
				</li>
				<li>
					<span>
						<?php echo isset( $_REQUEST['id'] ) ? esc_attr( $cpo_data_update_scheduled_clean_up ) : esc_attr( $cpo_add_new_scheduled_clean_up_label ); // WPCS:input var ok,CSRF ok. ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-plus"></i>
							<?php echo isset( $_REQUEST['id'] ) ? esc_attr( $cpo_update_database_schedule ) : esc_attr( $cpo_add_new_database_schedule ); // WPCS:input var ok,CSRF ok. ?>
						</div>
						<p class="premium-editions-clean-up-optimizer">
								<?php echo esc_attr( $cpo_upgrade_know_about ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>" target="_blank" class="premium-editions-documentation"> <?php echo esc_attr( $cpo_full_features ); ?></a> <?php echo esc_attr( $cpo_chek_our ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>/backend-demos" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpo_online_demos ); ?></a>
							</p>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_add_new_schedule_clean_up_db">
							<div class="form-body">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">
												<?php echo esc_attr( $cpo_action ); ?> :
												<span class="required" aria-required="true">* ( <?php echo esc_attr( $cpo_upgrade ); ?> )</span>
											</label>
											<select name="ux_ddl_action" id="ux_ddl_action" class="form-control">
												<option value=""><?php echo esc_attr( $cpo_bulk_action_dropdown ); ?></option>
												<option value="Empty"><?php echo esc_attr( $cpo_empty ); ?></option>
												<option value="Delete" ><?php echo esc_attr( $cpo_delete ); ?></option>
												<option value="Optimize"><?php echo esc_attr( $cpo_optimize_dropdown ); ?></option>
												<option value="Repair"><?php echo esc_attr( $cpo_repair_dropdown ); ?></option>
											</select>
											<i class="controls-description"><?php echo esc_attr( $cpo_data_action_label_scheduled_clean_up_tooltip ); ?></i>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">
												<?php echo esc_attr( $cpo_duration ); ?> :
												<span class="required" aria-required="true">* ( <?php echo esc_attr( $cpo_upgrade ); ?> )</span>
											</label>
											<div class="input-icon right">
												<select name="ux_ddl_duration" id="ux_ddl_duration" class="form-control" onchange="change_duration_clean_up_optimizer();">
													<option value="Hourly"><?php echo esc_attr( $cpo_hourly ); ?></option>
													<option value="Daily"><?php echo esc_attr( $cpo_daily ); ?></option>
												</select>
											</div>
											<i class="controls-description" ><?php echo esc_attr( $cpo_add_new_scheduled_duration_label_tooltip ); ?></i>
										</div>
									</div>
								</div>
								<div id="ux_div_start_on_start_time">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">
													<?php echo esc_attr( $cpo_start_on ); ?> :
													<span class="required" aria-required="true">* ( <?php echo esc_attr( $cpo_upgrade ); ?> )</span>
												</label>
												<input name="ux_txt_start_date" id="ux_txt_start_date" type="text" class="form-control" placeholder="<?php echo esc_attr( $cpo_start_on_placeholder ); ?>" value="<?php echo isset( $get_array['start_on_database'] ) ? date( 'm/d/Y', $get_array['start_on_database'] ) : date( 'm/d/Y' ); // WPCS:XSS ok. ?>" onkeypress="prevent_data_clean_up_optimizer(event)">
												<i class="controls-description" ><?php echo esc_attr( $cpo_start_on_tooltip ); ?></i>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">
													<?php echo esc_attr( $cpo_start_time ); ?> :
													<span class="required" aria-required="true">* ( <?php echo esc_attr( $cpo_upgrade ); ?> )</span>
												</label>
												<div class="input-icon right">
													<select class="form-control custom-input-medium input-inline" name="ux_ddl_start_hours" id="ux_ddl_start_hours">
														<?php
														for ( $flag = 0; $flag < 24; $flag++ ) {
															if ( $flag < 10 ) {
																?>
																<option value="<?php echo intval( $flag ) * 60 * 60; ?>">0<?php echo intval( $flag ); ?><?php echo esc_attr( $cpo_hrs ); ?></option>
																<?php
															} else {
																?>
																<option value="<?php echo intval( $flag ) * 60 * 60; ?>"><?php echo intval( $flag ); ?><?php echo esc_attr( $cpo_hrs ); ?></option>
																<?php
															}
														}
														?>
													</select>
													<select class="form-control custom-input-medium input-inline" name="ux_ddl_start_minutes" id="ux_ddl_start_minutes">
														<?php
														for ( $flag = 0; $flag < 60; $flag++ ) {
															if ( $flag < 10 ) {
																?>
																<option value="<?php echo intval( $flag ) * 60; ?>">0<?php echo intval( $flag ); ?><?php echo esc_attr( $cpo_mins ); ?></option>
																<?php
															} else {
																?>
																<option value="<?php echo intval( $flag ) * 60; ?>"><?php echo intval( $flag ); ?><?php echo esc_attr( $cpo_mins ); ?></option>
																<?php
															}
														}
														?>
													</select>
												</div>
												<i class="controls-description" ><?php echo esc_attr( $cpo_start_time_tooltip ); ?></i>
											</div>
										</div>
									</div>
								</div>
								<div id="ux_div_repeat_every">
									<div class="form-group">
										<label class="control-label">
											<?php echo esc_attr( $cpo_repeat_every ); ?> :
											<span class="required" aria-required="true">* ( <?php echo esc_attr( $cpo_upgrade ); ?> )</span>
										</label>
										<select class="form-control" name="ux_ddl_repeat_every" id="ux_ddl_repeat_every">
											<?php
											for ( $flag = 1; $flag < 24; $flag++ ) {
												if ( $flag < 10 ) {
													if ( '4' === $flag ) {
														?>
														<option selected="selected" value="<?php echo intval( $flag ) . 'Hour'; ?>">0<?php echo intval( $flag ); ?><?php echo esc_attr( $cpo_hrs ); ?></option>
														<?php
													} else {
														?>
														<option value="<?php echo intval( $flag ) . 'Hour'; ?>">0<?php echo intval( $flag ); ?><?php echo esc_attr( $cpo_hrs ); ?></option>
														<?php
													}
												} else {
													?>
													<option value="<?php echo intval( $flag ) . 'Hour'; ?>"><?php echo intval( $flag ); ?><?php echo esc_attr( $cpo_hrs ); ?></option>
													<?php
												}
											}
											?>
										</select>
										<i class="controls-description" ><?php echo esc_attr( $cpo_repeat_every_tooltip ); ?></i>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover table-margin-top" id="ux_tbl_database_schedule_clean_up">
									<thead>
										<tr>
											<th style="width: 4%;">
												<input type="checkbox" id="ux_chk_select_all_first" value="0" name="ux_chk_select_all_first">
											</th>
											<th>
												<?php echo esc_attr( $cpo_table_name_heading ); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php
										for ( $flag = 0; $flag < count( $result ); $flag++ ) { // @codingStandardsIgnoreLine.
											$checked = isset( $tables_array ) ? in_array( $result[ $flag ]->Name, $tables_array, true ) : '';
											if ( 0 === $flag % 2 ) {
												$tables         = $result[ $flag ]->Name;
												$table_termmeta = $wp_version >= 4.4 ? strstr( $tables, $wpdb->termmeta ) : '';
												if ( ( strstr( $tables, $wpdb->terms ) || strstr( $tables, $wpdb->term_taxonomy ) || strstr( $tables, $wpdb->term_relationships ) || strstr( $tables, $wpdb->commentmeta ) || strstr( $tables, $wpdb->comments ) || strstr( $tables, $wpdb->links ) || strstr( $tables, $wpdb->options ) || strstr( $tables, $wpdb->postmeta ) || strstr( $tables, $wpdb->posts ) || strstr( $tables, $wpdb->users ) || strstr( $tables, $wpdb->usermeta ) || strstr( $tables, clean_up_optimizer() ) || strstr( $tables, clean_up_optimizer_meta() ) || strstr( $tables, $wpdb->signups ) || strstr( $tables, $wpdb->sitemeta ) || strstr( $tables, $wpdb->site ) || strstr( $tables, $wpdb->registration_log ) || strstr( $tables, $wpdb->blogs ) || strstr( $tables, $wpdb->blog_versions ) || $table_termmeta ) == true ) { // @codingStandardsIgnoreLine.
													?>
													<tr>
														<td style="text-align:center;">
															<input type="checkbox"  table="inbuilt" id="ux_chk_add_new_schedule_db_<?php echo intval( $flag ); ?>" name="ux_chk_add_new_schedule_db[]" onclick="check_all_clean_up_optimizer('#ux_chk_select_all_first');" value="<?php echo esc_attr( $result[ $flag ]->Name ); ?>" <?php echo '' != $checked ? 'checked=checked' : '';// WPCS: loose comparison ok. ?> >
														</td>
														<td class="custom-manual-td">
															<label style="font-size:13px;color:#FF0000 !important;"><?php echo esc_attr( $result[ $flag ]->Name ) . '*'; ?></label>
														</td>
													<?php
												} else {
													?>
													<tr>
														<td style="text-align:center;">
															<input type="checkbox" id="ux_chk_add_new_schedule_db_<?php echo intval( $flag ); ?>" name="ux_chk_add_new_schedule_db[]" onclick="check_all_clean_up_optimizer('#ux_chk_select_all_first');" value="<?php echo esc_attr( $result[ $flag ]->Name ); ?>" <?php echo '' != $checked ? 'checked=checked' : '';// WPCS: loose comparison ok. ?> >
														</td>
														<td class="custom-manual-td green-custom">
															<label><?php echo esc_attr( $result[ $flag ]->Name ); ?></label>
														</td>
														<?php
												}
											} else {
												$tables         = $result[ $flag ]->Name;
												$table_termmeta = $wp_version >= 4.4 ? strstr( $tables, $wpdb->termmeta ) : '';
												if ( ( strstr( $tables, $wpdb->terms ) || strstr( $tables, $wpdb->term_taxonomy ) || strstr( $tables, $wpdb->term_relationships ) || strstr( $tables, $wpdb->commentmeta ) || strstr( $tables, $wpdb->comments ) || strstr( $tables, $wpdb->links ) || strstr( $tables, $wpdb->options ) || strstr( $tables, $wpdb->postmeta ) || strstr( $tables, $wpdb->posts ) || strstr( $tables, $wpdb->users ) || strstr( $tables, $wpdb->usermeta ) || strstr( $tables, clean_up_optimizer() ) || strstr( $tables, clean_up_optimizer_meta() ) || strstr( $tables, $wpdb->signups ) || strstr( $tables, $wpdb->sitemeta ) || strstr( $tables, $wpdb->site ) || strstr( $tables, $wpdb->registration_log ) || strstr( $tables, $wpdb->blogs ) || strstr( $tables, $wpdb->blog_versions ) || $table_termmeta ) == true ) { // @codingStandardsIgnoreLine.
													?>
													<td style="text-align:center;">
														<input type="checkbox"  table="inbuilt" id="ux_chk_add_new_schedule_db_<?php echo intval( $flag ); ?>" name="ux_chk_add_new_schedule_db[]" onclick="check_all_clean_up_optimizer('#ux_chk_select_all_first');" value="<?php echo esc_attr( $result[ $flag ]->Name ); ?>" <?php echo '' != $checked ? 'checked=checked' : '';// WPCS: loose comparison ok. ?> >
													</td>
													<td class="custom-manual-td">
														<label style="font-size:13px;color:#FF0000 !important;"><?php echo esc_attr( $result[ $flag ]->Name ) . '*'; ?></label>
													</td>
												</tr>
													<?php
												} else {
													?>
													<td style="text-align:center;">
														<input type="checkbox"  id="ux_chk_add_new_schedule_db_<?php echo intval( $flag ); ?>" name="ux_chk_add_new_schedule_db[]" onclick="check_all_clean_up_optimizer('#ux_chk_select_all_first');" value="<?php echo esc_attr( $result[ $flag ]->Name ); ?>" <?php echo '' != $checked ? 'checked=checked' : '';// WPCS: loose comparison ok. ?> >
													</td>
													<td class="custom-manual-td green-custom">
														<label><?php echo esc_attr( $result[ $flag ]->Name ); ?></label>
													</td>
													<?php
												}
												?>
											</tr>
												<?php
											}
											if ( count( $result ) - 1 == $flag && 0 === $flag % 2 ) { // WPCS: loose comparison ok.
												?>
												<td style="text-align:center;">
												</td>
												<td class="custom-manual-td green-custom">
												<label></label>
												</td>
												<?php
											}
										}
										$flag++;
										?>
									</tbody>
								</table>
								<div class="line-separator"></div>
								<div class="form-actions">
									<div class="pull-right">
										<input type="submit" class="btn vivid-green" name="ux_btn_schedule_save_changes" id="ux_btn_schedule_save_changes" value="<?php echo esc_attr( $cpo_save_changes ); ?>">
									</div>
								</div>
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
					<a href="admin.php?page=cpo_dashboard">
						<?php echo esc_attr( $cpo_dashboard ); ?>
					</a>
					<span>></span>
				</li>
				<li>
					<span>
						<?php echo esc_attr( $cpo_add_new_scheduled_clean_up_label ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-plus"></i>
							<?php echo esc_attr( $cpo_add_new_database_schedule ); ?>
						</div>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_add_new_schedule_clean_up_db">
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
