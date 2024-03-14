<?php
/**
 * This Template is used to view details of tables.
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
		$get_selected_database_data = wp_create_nonce( 'get_selected_database_data' );
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
						<?php echo esc_attr( $cpo_database_manual_clean_up_view_records_label ); ?>
					</span>
				</li>
			</ul>
		</div>
		<a href="admin.php?page=cpo_db_optimizer" class="btn vivid-green" name="ux_btn_back" id="ux_btn_back" style="margin-bottom:20px;"><?php echo esc_attr( $cpo_database_view_record_back_button_label ); ?></a>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-doc"></i>
							<?php echo esc_attr( $cpo_database_manual_clean_up_view_records_label ); ?>
						</div>
						<p class="premium-editions-clean-up-optimizer">
							<?php echo esc_attr( $cpo_upgrade_know_about ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>" target="_blank" class="premium-editions-documentation"> <?php echo esc_attr( $cpo_full_features ); ?></a> <?php echo esc_attr( $cpo_chek_our ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>/backend-demos" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpo_online_demos ); ?></a>
							</p>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_database_view_records_manual_clean_up">
							<div class="form-body">
								<div class="form-actions">
									<div class="table-top-margin">
										<select name="ux_ddl_view_records" id="ux_ddl_view_records" class="custom-bulk-width">
											<option value=""><?php echo esc_attr( $cpo_bulk_action_dropdown ); ?></option>
											<option value="delete" style="color:red;"><?php echo esc_attr( $cpo_delete ); ?> ( <?php echo esc_attr( $cpo_upgrade ); ?>)</option>
										</select>
										<input type="button" class="btn vivid-green" name="ux_btn_bulk_action" id="ux_btn_bulk_action" onclick=" premium_edition_notification_clean_up_optimizer(); " value="<?php echo esc_attr( $cpo_apply ); ?>">
									</div>
									<table class="table table-striped table-bordered table-hover custom-dataTables-scrollHead" id="ux_tbl_view_records_manual_clean_up">
										<thead>
											<tr>
												<th style="width: 1%; text-align: center;" class="chk-action">
													<input type="checkbox" class="custom-chkbox-operation" name="ux_chk_all_db_view_records" id="ux_chk_all_db_view_records">
												</th>
												<?php
												for ( $flag = 0; $flag < count( $table_columns_name ); $flag++ ) {// @codingStandardsIgnoreLine.
													?>
													<th>
														<?php
														echo esc_attr( $table_columns_name[ $flag ] );
														?>
													</th>
													<?php
												}
												?>
												<th style="text-align:center;" class="chk-action">
													<?php echo esc_attr( $cpo_action ); ?>
												</th>
											</tr>
										</thead>
										<tbody>
											<?php
											for ( $data = 0; $data < count( $view_records ); $data++ ) { // @codingStandardsIgnoreLine.
												$index_id = $table_columns_name[0];
												?>
												<tr>
													<td>
														<input type="checkbox" class="custom-chkbox-operation" name="ux_chk_view_tbl_data" id="ux_chk_view_tbl_data_<?php echo esc_attr( $data ); ?>" value="<?php echo $view_records[ $data ]->$index_id; // @codingStandardsIgnoreLine.?>" onclick="check_all_clean_up_optimizer('#ux_chk_all_db_view_records');">
													</td>
													<?php
													for ( $flag = 0; $flag < count( $table_columns_name ); $flag++ ) { // @codingStandardsIgnoreLine.
														$column_name = $table_columns_name[ $flag ];
														?>
														<td>
															<?php echo $view_records[ $data ]->$column_name; // WPCS: XSS ok. ?>
														</td>
														<?php
													}
													?>
													<td class="custom-alternative">
														<a href="javascript:void(0);" class="btn clean-up-optimizer-buttons" onclick="premium_edition_notification_clean_up_optimizer();"><?php echo esc_attr( $cpo_delete ); ?>
														</a>
													</td>
												</tr>
												<?php
											}
											?>
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
						<?php echo esc_attr( $cpo_database_manual_clean_up_view_records_label ); ?>
					</span>
				</li>
			</ul>
		</div>
		<a href="admin.php?page=cpo_db_optimizer" class="btn vivid-green" name="ux_btn_back" id="ux_btn_back" style="margin-bottom:20px;"><?php echo esc_attr( $cpo_database_view_record_back_button_label ); ?></a>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-doc"></i>
							<?php echo esc_attr( $cpo_database_manual_clean_up_view_records_label ); ?>
						</div>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_database_view_records_manual_clean_up">
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
