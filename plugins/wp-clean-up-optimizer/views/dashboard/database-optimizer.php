<?php
/**
 * This Template is used for managing database tables manually.
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
	} elseif ( DATABASE_OPTIMIZER_CLEAN_UP_OPTIMIZER === '1' ) {
		global $wp_version;
		$manual_db_bulk_action      = wp_create_nonce( 'manual_db_bulk_action' );
		$manual_db_select_action    = wp_create_nonce( 'manual_db_select_action' );
		$get_selected_database_data = wp_create_nonce( 'get_selected_database_data' );

		$total_size = 0;
		$total_rows = 0;
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
						<?php echo esc_attr( $cpo_database_optimizer ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-book-open"></i>
							<?php echo esc_attr( $cpo_database_optimizer ); ?>
						</div>
						<p class="premium-editions-clean-up-optimizer">
							<?php echo esc_attr( $cpo_upgrade_know_about ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>" target="_blank" class="premium-editions-documentation"> <?php echo esc_attr( $cpo_full_features ); ?></a> <?php echo esc_attr( $cpo_chek_our ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>/backend-demos" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpo_online_demos ); ?></a>
							</p>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_manage_ip_addresses">
							<div class="form-body">
								<div class="form-actions">
									<div class="table-top-margin">
										<select name="ux_ddl_manual" id="ux_ddl_manual"  class="custom-bulk-width">
											<option value=""><?php echo esc_attr( $cpo_bulk_action_dropdown ); ?></option>
											<option value="empty" style="color:red;"><?php echo esc_attr( $cpo_empty ); ?> ( <?php echo esc_attr( $cpo_upgrade ); ?> )</option>
											<option value="delete" ><?php echo esc_attr( $cpo_delete ); ?></option>
											<option selected="selected" value="optimize"><?php echo esc_attr( $cpo_optimize_dropdown ); ?></option>
											<option value="repair" style="color:red;"><?php echo esc_attr( $cpo_repair_dropdown ); ?> ( <?php echo esc_attr( $cpo_upgrade ); ?> )</option>
										</select>
										<input type="button" class="btn vivid-green" name="ux_btn_bulk_action" id="ux_btn_bulk_action" onclick= "bulk_actions_manual_clean_up_optimizer();" value="<?php echo esc_attr( $cpo_apply ); ?>">
									</div>
									<table class="table table-striped table-bordered table-hover custom-dataTables-scrollHead" id="ux_tbl_manual_clean_up">
										<thead>
											<tr>
												<th style="width: 4%; text-align: center;">
													<input type="checkbox" class="custom-chkbox-operation" name="ux_chk_all_database_manual_clean_up" id="ux_chk_all_database_manual_clean_up">
												</th>
												<th style="width: 33%;">
													<label class="control-label">
														<?php echo esc_attr( $cpo_table_name_heading ); ?>
													</label>
												</th>
												<th style="width: 10%;">
													<label class="control-label">
														<?php echo esc_attr( $cpo_rows ); ?>
													</label>
												</th>
												<th style="width: 10%;">
													<label class="control-label">
														<?php echo esc_attr( $cpo_type ); ?>
													</label>
												</th>
												<th class="optimizer-table">
													<label class="control-label">
														<?php echo esc_attr( $cpo_table_size ); ?>
													</label>
												</th>
												<th style="width: 37%; text-align: center;">
													<label class="control-label">
														<?php echo esc_attr( $cpo_action ); ?>
													</label>
												</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$flag = 1;
											if ( isset( $result ) && count( $result ) > 0 ) {
												foreach ( $result as $row ) {
													$table_size = $row->Data_length + $row->Index_length; // @codingStandardsIgnoreLine.
													$table_size = $table_size / 1024;
													$table_size = sprintf( '%0.3f', $table_size );

													$every_size     = $row->Data_length + $row->Index_length; // @codingStandardsIgnoreLine.
													$every_size     = $every_size / 1024;
													$total_size    += $every_size;
													$count_rows     = $wpdb->get_var(
														"SELECT COUNT(*) FROM $row->Name"
													); // WPCS: db call ok,no cache ok,unprepared SQL ok..
													$total_rows    += $count_rows;
													$tables         = $row->Name; // @codingStandardsIgnoreLine.
													$table_termmeta = $wp_version >= 4.4 ? strstr( $tables, $wpdb->termmeta ) : '';
													if ( is_multisite() ) {
														if ( ( strstr( $tables, $wpdb->terms ) || strstr( $tables, $wpdb->term_taxonomy ) || strstr( $tables, $wpdb->term_relationships ) || strstr( $tables, $wpdb->commentmeta ) || strstr( $tables, $wpdb->comments ) || strstr( $tables, $wpdb->links ) || strstr( $tables, $wpdb->options ) || strstr( $tables, $wpdb->postmeta ) || strstr( $tables, $wpdb->posts ) || strstr( $tables, $wpdb->users ) || strstr( $tables, $wpdb->usermeta ) || strstr( $tables, clean_up_optimizer() ) || strstr( $tables, clean_up_optimizer_meta() ) || strstr( $tables, $wpdb->signups ) || strstr( $tables, $wpdb->sitemeta ) || strstr( $tables, $wpdb->site ) || strstr( $tables, $wpdb->registration_log ) || strstr( $tables, $wpdb->blogs ) || strstr( $tables, $wpdb->blog_versions ) || $table_termmeta ) == true ) { // @codingStandardsIgnoreLine.
															?>
														<tr>
															<td style="text-align:center;">
															<input class="checkall" type="checkbox" id="ux_chk_database_manual_<?php echo intval( $flag ); ?>" name="ux_chk_database_manual_<?php echo intval( $flag ); ?>" value="<?php echo $row->Name; // @codingStandardsIgnoreLine. ?>" onclick="check_all_clean_up_optimizer('#ux_chk_all_database_manual_clean_up');">
															</td>
															<td class="custom-manual-td">
																<label  onclick="manual_clean_up(ux_tbl_manual_clean_up);" style="font-size:13px;color:#FF0000 !important;"><?php echo $row->Name . '*';// @codingStandardsIgnoreLine. ?></label>
															</td>
															<td>
																<?php echo esc_attr( $count_rows ); ?>
															</td>
															<td>
																<?php echo esc_attr( $row->Engine ); // @codingStandardsIgnoreLine. ?>
															</td>
															<td  style="width: 10%;">
																<?php echo sprintf( '%0.1f', $table_size ) . ' KB'; // WPCS:XSS ok. ?>
															</td>
															<td>
																<select id="ux_ddl_action_table_<?php echo intval( $flag ); ?>" name="ux_ddl_action_table_<?php echo intval( $flag ); ?>" style="width:35%;">
																	<option value="optimize"><?php echo esc_attr( $cpo_optimize_dropdown ); ?></option>
																	<option value="repair"><?php echo esc_attr( $cpo_repair_dropdown ); ?></option>
																</select>
																<input type="button" value="<?php echo esc_attr( $cpo_apply ); ?>" class="btn vivid-green" style="font-size:11px;" onclick="select_action_clean_up_optimizer('<?php echo $row->Name . "','" . intval( $flag ); // @codingStandardsIgnoreLine. ?>');" />
																<a href="admin.php?page=cpo_database_view_records&row_type=<?php echo base64_encode( $row->Name ); // @codingStandardsIgnoreLine. ?>&nonce=<?php echo esc_attr( $get_selected_database_data ); ?>" class="btn vivid-green" name="ux_btn_view_records" id="ux_btn_view_records" style="font-size:11px;"><?php echo $cpo_view_records_label; ?></a>
															</td>
														</tr>
															<?php
														} else {
															?>
														<tr>
															<td style="text-align:center;">
																<input class="checkall" type="checkbox" id="ux_chk_database_manual_<?php echo intval( $flag ); ?>" name="ux_chk_database_manual_<?php echo intval( $flag ); ?>" value="<?php echo $row->Name; // @codingStandardsIgnoreLine. ?>" onclick="check_all_clean_up_optimizer('#ux_chk_all_database_manual_clean_up');">
															</td>
															<td class="custom-manual-td green-custom">
																<label><?php echo $row->Name; // @codingStandardsIgnoreLine. ?></label>
															</td>
															<td>
																<?php
																echo esc_attr( $count_rows );
																?>
															</td>
															<td>
																<?php
																echo esc_attr( $row->Engine ); // @codingStandardsIgnoreLine.
																?>
															</td>
															<td class="optimizer-table"  style="width: 10%;">
																<?php
																echo sprintf( '%0.1f', $table_size ) . ' KB'; // WPCS:XSS ok.
																?>
															</td>
															<td class="custom-width" style="text-align:center;">
																<select style="width: 35%;" id="ux_ddl_action_table_<?php echo intval( $flag ); ?>" name="ux_ddl_action_table_<?php echo intval( $flag ); ?>">
																	<option value="optimize"><?php echo esc_attr( $cpo_optimize_dropdown ); ?></option>
																	<option value="repair"><?php echo esc_attr( $cpo_repair_dropdown ); ?></option>
																	<option value="empty"><?php echo esc_attr( $cpo_empty ); ?></option>
																	<option value="delete" ><?php echo esc_attr( $cpo_delete ); ?></option>
																</select>
																<input type="button" value="<?php echo esc_attr( $cpo_apply ); ?>" class="btn vivid-green" style="font-size:11px;" onclick="select_action_clean_up_optimizer('<?php echo $row->Name . "','" . intval( $flag ); // @codingStandardsIgnoreLine. ?>');" />
																<a href="admin.php?page=cpo_database_view_records&row_type=<?php echo base64_encode( $row->Name );  // @codingStandardsIgnoreLine.?>&nonce=<?php echo esc_attr( $get_selected_database_data ); ?>" class="btn vivid-green" name="ux_btn_view_records" id="ux_btn_view_records" style="font-size:11px;"><?php echo $cpo_view_records_label; ?></a>
															</td>
														</tr>
															<?php
														}
													} else {
														if ( ( strstr( $tables, $wpdb->terms ) || strstr( $tables, $wpdb->term_taxonomy ) || strstr( $tables, $wpdb->term_relationships ) || strstr( $tables, $wpdb->commentmeta ) || strstr( $tables, $wpdb->comments ) || strstr( $tables, $wpdb->links ) || strstr( $tables, $wpdb->options ) || strstr( $tables, $wpdb->postmeta ) || strstr( $tables, $wpdb->posts ) || strstr( $tables, $wpdb->users ) || strstr( $tables, $wpdb->usermeta ) || strstr( $tables, clean_up_optimizer() ) || strstr( $tables, clean_up_optimizer_meta() ) || $table_termmeta ) == true ) { // @codingStandardsIgnoreLine.
															?>
														<tr>
															<td style="text-align:center;">
																<input class="checkall" type="checkbox"  table="inbuilt" id="ux_chk_database_manual_<?php echo intval( $flag ); ?>" name="ux_chk_database_manual_<?php echo intval( $flag ); ?>" value="<?php echo $row->Name; // @codingStandardsIgnoreLine. ?>" onclick="check_all_clean_up_optimizer('#ux_chk_all_database_manual_clean_up');">
															</td>
															<td class="custom-manual-td">
																<label style="font-size:13px;color:#FF0000 !important;" ><?php echo $row->Name . '*'; // @codingStandardsIgnoreLine. ?></label>
															</td>
															<td>
																<?php echo esc_attr( $count_rows ); ?>
															</td>
															<td style="width: 12%;">
																<?php echo esc_attr( $row->Engine ); // @codingStandardsIgnoreLine. ?>
															</td>
															<td  style="width: 10%;">
																<?php echo sprintf( '%0.1f', $table_size ) . ' KB'; // WPCS:XSS ok. ?>
															</td>
															<td style="text-align:center; width: 37%;">
																<select id="ux_ddl_action_table_<?php echo intval( $flag ); ?>" name="ux_ddl_action_table_<?php echo intval( $flag ); ?>" style="width:35%;">
																	<option value="optimize"><?php echo esc_attr( $cpo_optimize_dropdown ); ?></option>
																	<option value="repair"><?php echo esc_attr( $cpo_repair_dropdown ); ?></option>
																</select>
																<input type="button" value="<?php echo esc_attr( $cpo_apply ); ?>" class="btn vivid-green" style="font-size:11px;" onclick="select_action_clean_up_optimizer('<?php echo esc_attr( $row->Name ) . "','" . intval( $flag ); // @codingStandardsIgnoreLine. ?>');" />
																<a href="admin.php?page=cpo_database_view_records&row_type=<?php echo base64_encode( $row->Name ); // @codingStandardsIgnoreLine. ?>&nonce=<?php echo esc_attr( $get_selected_database_data ); ?>" class="btn vivid-green" name="ux_btn_view_records" id="ux_btn_view_records" style="font-size:11px;"><?php echo esc_attr( $cpo_view_records_label ); ?></a>
															</td>
														</tr>
															<?php
														} else {
															?>
															<tr>
																<td style="text-align:center;">
																	<input class="checkall" type="checkbox" id="ux_chk_database_manual_<?php echo intval( $flag ); ?>" name="ux_chk_database_manual_<?php echo intval( $flag ); ?>" value="<?php echo esc_attr( $row->Name ); // @codingStandardsIgnoreLine. ?>" onclick="check_all_clean_up_optimizer('#ux_chk_all_database_manual_clean_up');">
																</td>
																<td class="custom-manual-td green-custom">
																	<label><?php echo esc_attr( $row->Name ); // @codingStandardsIgnoreLine. ?></label>
																</td>
																<td>
																	<?php
																	echo esc_attr( $count_rows );
																	?>
																</td>
																<td>
																	<?php
																	echo $row->Engine; // @codingStandardsIgnoreLine.
																	?>
																</td>
																<td style="width: 10%;">
																	<?php
																	echo sprintf( '%0.1f', $table_size ) . ' KB'; // WPCS:XSS ok.
																	?>
																</td>
																<td style="text-align:center; width: 34%;">
																	<select id="ux_ddl_action_table_<?php echo intval( $flag ); ?>" name="ux_ddl_action_table_<?php echo intval( $flag ); ?>" style="width:35%;">
																		<option value="optimize"><?php echo esc_attr( $cpo_optimize_dropdown ); ?></option>
																		<option value="repair"><?php echo esc_attr( $cpo_repair_dropdown ); ?></option>
																		<option value="empty"><?php echo esc_attr( $cpo_empty ); ?></option>
																		<option value="delete" ><?php echo esc_attr( $cpo_delete ); ?></option>
																	</select>
																	<input type="button" value="<?php echo esc_attr( $cpo_apply ); ?>" class="btn vivid-green" style="font-size:11px;" onclick="select_action_clean_up_optimizer('<?php echo $row->Name . "','" . intval( $flag ); // @codingStandardsIgnoreLine. ?>');" />
																	<a href="admin.php?page=cpo_database_view_records&row_type=<?php echo base64_encode( $row->Name ); // @codingStandardsIgnoreLine. ?>&nonce=<?php echo esc_attr( $get_selected_database_data ); ?>" class="btn vivid-green" name="ux_btn_view_records" id="ux_btn_view_records" style="font-size:11px;"><?php echo esc_attr( $cpo_view_records_label ); ?></a>
																</td>
															</tr>
															<?php
														}
													}
													$flag++;
												}
											}
											?>
											<tr>
												<td></td>
												<td><strong>Total Rows</strong></td>
												<td><?php echo esc_attr( $total_rows ); ?></td>
												<td><strong>Total Size</strong></td>
												<td><?php echo esc_attr( $total_size ) . ' KB'; ?></td>
												<td></td>
											</tr>
										</tbody>
									</table>
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
						<?php echo esc_attr( $cpo_database_optimizer ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-book-open"></i>
							<?php echo esc_attr( $cpo_database_optimizer ); ?>
						</div>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_manage_ip_addresses">
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
