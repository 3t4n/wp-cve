<?php
/**
 * This Template is used for managing roles and capabilities.
 *
 * @author Tech Banker
 * @package wp-cleanup-optimizer/views/general-settings
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
	} elseif ( GENERAL_SETTINGS_CLEAN_UP_OPTIMIZER === '1' ) {
		$roles_and_capabilities = explode( ',', isset( $details_roles_capabilities['roles_and_capabilities'] ) ? $details_roles_capabilities['roles_and_capabilities'] : '' );
		$administrator          = explode( ',', isset( $details_roles_capabilities['administrator_privileges'] ) ? $details_roles_capabilities['administrator_privileges'] : '' );
		$author                 = explode( ',', isset( $details_roles_capabilities['author_privileges'] ) ? $details_roles_capabilities['author_privileges'] : '' );
		$editor                 = explode( ',', isset( $details_roles_capabilities['editor_privileges'] ) ? $details_roles_capabilities['editor_privileges'] : '' );
		$contributor            = explode( ',', isset( $details_roles_capabilities['contributor_privileges'] ) ? $details_roles_capabilities['contributor_privileges'] : '' );
		$subscriber             = explode( ',', isset( $details_roles_capabilities['subscriber_privileges'] ) ? $details_roles_capabilities['subscriber_privileges'] : '' );
		$other                  = explode( ',', isset( $details_roles_capabilities['other_privileges'] ) ? $details_roles_capabilities['other_privileges'] : '' );
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
					<a href="admin.php?page=cpo_notifications_setup">
						<?php echo esc_attr( $cpo_general_settings_label ); ?>
					</a>
					<span>></span>
				</li>
				<li>
					<span>
						<?php echo esc_attr( $cpo_roles_capabilities_label ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-users"></i>
							<?php echo esc_attr( $cpo_roles_capabilities_label ); ?>
						</div>
						<p class="premium-editions-clean-up-optimizer">
							<?php echo esc_attr( $cpo_upgrade_know_about ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>" target="_blank" class="premium-editions-documentation"> <?php echo esc_attr( $cpo_full_features ); ?></a> <?php echo esc_attr( $cpo_chek_our ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>/backend-demos" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpo_online_demos ); ?></a>
						</p>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_roles_and_capabilities">
							<div class="form-body">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpo_roles_and_capabilities_clean_up_optimizer_menu_label ); ?> :
										<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
									</label>
									<table class="table table-striped table-bordered table-margin-top" style="margin-bottom: 0px!important;" id="ux_tbl_clean_up_roles" >
										<thead>
											<tr>
												<th>
													<input type="checkbox"  name="ux_chk_administrator" id="ux_chk_administrator" value="1" checked="checked" disabled="disabled" <?php echo '1' === $roles_and_capabilities[0] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpo_roles_and_capabilities_administrator_label ); ?>
												</th>
												<th>
													<input type="checkbox"  name="ux_chk_author" id="ux_chk_author"  value="1" onclick="show_roles_capabilities_clean_up_optimizer(this, 'ux_div_author_roles');" <?php echo '1' === $roles_and_capabilities[1] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpo_roles_and_capabilities_author_label ); ?>
												</th>
												<th>
													<input type="checkbox"  name="ux_chk_editor" id="ux_chk_editor" value="1" onclick="show_roles_capabilities_clean_up_optimizer(this, 'ux_div_editor_roles');" <?php echo '1' === $roles_and_capabilities[2] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpo_roles_and_capabilities_editor_label ); ?>
												</th>
												<th>
													<input type="checkbox"  name="ux_chk_contributor" id="ux_chk_contributor"  value="1" onclick="show_roles_capabilities_clean_up_optimizer(this, 'ux_div_contributor_roles');" <?php echo '1' === $roles_and_capabilities[3] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpo_roles_and_capabilities_contributor_label ); ?>
												</th>
												<th>
													<input type="checkbox"  name="ux_chk_subscriber" id="ux_chk_subscriber" value="1" onclick="show_roles_capabilities_clean_up_optimizer(this, 'ux_div_subscriber_roles');" <?php echo '1' === $roles_and_capabilities[4] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpo_roles_and_capabilities_subscriber_label ); ?>
												</th>
												<th>
													<input type="checkbox"  name="ux_chk_other" id="ux_chk_other" value="1" onclick="show_roles_capabilities_clean_up_optimizer(this, 'ux_div_other_roles');" <?php echo '1' === $roles_and_capabilities[5] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpo_roles_and_capabilities_other_label ); ?>
												</th>
											</tr>
										</thead>
									</table>
									<i class="controls-description"><?php echo esc_attr( $cpo_roles_and_capabilities_choose_specific_role ); ?></i>
								</div>
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpo_roles_and_capabilities_clean_up_top_bar_menu_label ); ?> :
										<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
									</label>
									<select name="ux_ddl_clean_up_optimizer_menu" id="ux_ddl_clean_up_optimizer_menu" class="form-control">
										<option value="enable"><?php echo esc_attr( $cpo_enable ); ?></option>
										<option value="disable"><?php echo esc_attr( $cpo_disable ); ?></option>
									</select>
									<i class="controls-description"><?php echo esc_attr( $cpo_roles_and_capabilities_clean_up_top_bar_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
								<div class="form-group">
									<div id="ux_div_administrator_roles">
										<label class="control-label">
											<?php echo esc_attr( $cpo_roles_and_capabilities_administrator_role_label ); ?> :
											<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
										</label>
										<div class="table-margin-top">
											<table class="table table-striped table-bordered table-hover" id="ux_tbl_administrator">
												<thead>
													<tr>
														<th style="width: 40% !important;">
															<input type="checkbox" name="ux_chk_full_control_administrator" id="ux_chk_full_control_administrator" checked="checked" disabled="disabled" value="1">
															<?php echo esc_attr( $cpo_roles_and_capabilities_full_control_label ); ?>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_wordpress_data_manual_clean_up_admin" disabled="disabled" checked="checked" id="ux_chk_wordpress_data_manual_clean_up_admin" value="1">
															<?php echo esc_attr( $cpo_wp_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_wordpress_schedule_manual_clean_up_admin" disabled="disabled" checked="checked" id="ux_chk_wordpress_schedule_manual_clean_up_admin" value="1">
															<?php echo esc_attr( $cpo_schedule_wp_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_database_manual_clean_up_admin" disabled="disabled" checked="checked" id="ux_chk_database_manual_clean_up_admin" value="1">
															<?php echo esc_attr( $cpo_database_optimizer ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_database_schedule_clean_up_admin" disabled="disabled" checked="checked" id="ux_chk_database_schedule_clean_up_admin" value="1">
															<?php echo esc_attr( $cpo_schedule_database_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_logs_admin" disabled="disabled" checked="checked" id="ux_chk_logs_admin" value="1">
															<?php echo esc_attr( $cpo_logs_label ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_cron_jobs_admin" disabled="disabled" checked="checked" id="ux_chk_cron_jobs_admin" value="1">
															<?php echo esc_attr( $cpo_cron_jobs_label ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_general_settings_admin" disabled="disabled" checked="checked" id="ux_chk_general_settings_admin" value="1">
															<?php echo esc_attr( $cpo_general_settings_label ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_advance_security_admin" disabled="disabled" checked="checked" id="ux_chk_advance_security_admin" value="1">
															<?php echo esc_attr( $cpo_security_settings ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_template_admin" disabled="disabled" checked="checked" id="ux_chk_template_admin" value="1">
															<?php echo esc_attr( $cpo_general_other_settings ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_system_information_admin" disabled="disabled" checked="checked" id="ux_chk_system_information_admin" value="1">
															<?php echo esc_attr( $cpo_system_information_label ); ?>
														</td>
														<td>
														</td>
														<td>
														</td>
													</tr>
												</tbody>
											</table>
											<i class="controls-description"><?php echo esc_attr( $cpo_roles_and_capabilities_choose_page_admin_access_tooltip ); ?></i>
										</div>
										<div class="line-separator"></div>
									</div>
								</div>
								<div class="form-group">
									<div id="ux_div_author_roles">
										<label class="control-label">
											<?php echo esc_attr( $cpo_roles_and_capabilities_author_role_label ); ?> :
											<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
										</label>
										<div class="table-margin-top">
											<table class="table table-striped table-bordered table-hover" id="ux_tbl_author">
												<thead>
													<tr>
														<th style="width: 40% !important;">
															<input type="checkbox" name="ux_chk_full_control_author" id="ux_chk_full_control_author" value="1"  onclick="full_control_function_clean_up_optimizer(this, 'ux_div_author_roles');"  <?php echo isset( $author ) && '1' === $author[0] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_roles_and_capabilities_full_control_label ); ?>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_wordpress_data_manual_clean_up_author" id="ux_chk_wordpress_data_manual_clean_up_author" value="1" <?php echo isset( $author ) && '1' === $author[1] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_wp_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_wordpress_schedule_manual_clean_up_author" id="ux_chk_wordpress_schedule_manual_clean_up_author" value="1" <?php echo isset( $author ) && '1' === $author[2] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_schedule_wp_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_database_manual_clean_up_author" id="ux_chk_database_manual_clean_up_author" value="1" <?php echo isset( $author ) && '1' === $author[3] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_database_optimizer ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_database_schedule_clean_up_author" id="ux_chk_database_schedule_clean_up_author" value="1" <?php echo isset( $author ) && '1' === $author[4] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_schedule_database_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_logs_author" id="ux_chk_logs_author" value="1" <?php echo isset( $author ) && '1' === $author[5] ? 'checked = checked' : ''; ?>?>
															<?php echo esc_attr( $cpo_logs_label ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_cron_jobs_author" id="ux_chk_cron_jobs_author" value="1" <?php echo isset( $author ) && '1' === $author[6] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_cron_jobs_label ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_general_settings_author" id="ux_chk_general_settings_author" value="1" <?php echo isset( $author ) && '1' === $author[7] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_general_settings_label ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_advance_security_author" id="ux_chk_advance_security_author" value="1" <?php echo isset( $author ) && '1' === $author[8] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_security_settings ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_templates_author" id="ux_chk_templates_author" value="1" <?php echo isset( $author ) && '1' === $author[9] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_general_other_settings ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_system_information_author" id="ux_chk_system_information_author" value="1" <?php echo isset( $author ) && '1' === $author[10] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_system_information_label ); ?>
														</td>
														<td>
														</td>
														<td>
														</td>
													</tr>
												</tbody>
											</table>
											<i class="controls-description"><?php echo esc_attr( $cpo_roles_and_capabilities_choose_page_author_access_tooltip ); ?></i>
										</div>
										<div class="line-separator"></div>
									</div>
								</div>
								<div class="form-group">
									<div id="ux_div_editor_roles">
										<label class="control-label">
											<?php echo esc_attr( $cpo_roles_and_capabilities_editor_role_label ); ?> :
											<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
										</label>
										<div class="table-margin-top">
											<table class="table table-striped table-bordered table-hover" id="ux_tbl_editor">
												<thead>
													<tr>
														<th style="width: 40% !important;">
															<input type="checkbox" name="ux_chk_full_control_editor" id="ux_chk_full_control_editor" value="1" onclick="full_control_function_clean_up_optimizer(this, 'ux_div_editor_roles');" <?php echo isset( $editor ) && '1' === $editor[0] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_roles_and_capabilities_full_control_label ); ?>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_wordpress_data_manual_clean_up_editor" id="ux_chk_wordpress_data_manual_clean_up_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[1] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_wp_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_wordpress_schedule_manual_clean_up_editor" id="ux_chk_wordpress_schedule_manual_clean_up_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[2] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_schedule_wp_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_database_manual_clean_up_editor" id="ux_chk_database_manual_clean_up_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[3] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_database_optimizer ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_database_schedule_clean_up_editor" id="ux_chk_database_schedule_clean_up_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[4] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_schedule_database_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_logs_editor" id="ux_chk_logs_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[5] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_logs_label ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_cron_jobs_editor" id="ux_chk_cron_jobs_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[6] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_cron_jobs_label ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_general_settings_editor" id="ux_chk_general_settings_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[7] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_general_settings_label ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_advance_security_editor" id="ux_chk_advance_security_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[8] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_security_settings ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_templates_editor" id="ux_chk_templates_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[9] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_general_other_settings ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_system_information_editor" id="ux_chk_system_information_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[10] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_system_information_label ); ?>
														</td>
														<td>
														</td>
														<td>
														</td>
													</tr>
												</tbody>
											</table>
											<i class="controls-description"><?php echo esc_attr( $cpo_roles_and_capabilities_choose_page_editor_access_tooltip ); ?></i>
										</div>
										<div class="line-separator"></div>
									</div>
								</div>
								<div class="form-group">
									<div id="ux_div_contributor_roles">
										<label class="control-label">
											<?php echo esc_attr( $cpo_roles_and_capabilities_contributor_role_label ); ?> :
											<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
										</label>
										<div class="table-margin-top">
											<table class="table table-striped table-bordered table-hover" id="ux_tbl_contributor">
												<thead>
													<tr>
														<th style="width: 40% !important;">
															<input type="checkbox" name="ux_chk_full_control_contributor" id="ux_chk_full_control_contributor" value="1" onclick="full_control_function_clean_up_optimizer(this, 'ux_div_contributor_roles');" <?php echo isset( $contributor ) && '1' === $contributor[0] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_roles_and_capabilities_full_control_label ); ?>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_wordpress_data_manual_clean_up_contributor" id="ux_chk_wordpress_data_manual_clean_up_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[1] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_wp_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_wordpress_schedule_manual_clean_up_contributor" id="ux_chk_wordpress_schedule_manual_clean_up_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[2] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_schedule_wp_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_database_manual_clean_up_contributor" id="ux_chk_database_manual_clean_up_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[3] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_database_optimizer ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_database_schedule_clean_up_contributor" id="ux_chk_database_schedule_clean_up_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[4] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_schedule_database_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_logs_contributor" id="ux_chk_logs_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[5] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_logs_label ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_cron_jobs_contributor" id="ux_chk_cron_jobs_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[6] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_cron_jobs_label ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_general_settings_contributor" id="ux_chk_general_settings_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[7] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_general_settings_label ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_advance_security_contributor" id="ux_chk_advance_security_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[8] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_security_settings ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_templates_contributor" id="ux_chk_templates_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[9] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_general_other_settings ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_system_information_contributor" id="ux_chk_system_information_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[10] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_system_information_label ); ?>
														</td>
														<td>
														</td>
														<td>
														</td>
													</tr>
												</tbody>
											</table>
											<i class="controls-description"><?php echo esc_attr( $cpo_roles_and_capabilities_choose_page_contributor_access_tooltip ); ?></i>
										</div>
										<div class="line-separator"></div>
									</div>
								</div>
								<div class="form-group">
									<div id="ux_div_subscriber_roles">
										<label class="control-label">
											<?php echo esc_attr( $cpo_roles_and_capabilities_subscriber_role_label ); ?> :
											<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
										</label>
										<div class="table-margin-top">
											<table class="table table-striped table-bordered table-hover" id="ux_tbl_subscriber">
												<thead>
													<tr>
														<th style="width: 40% !important;">
															<input type="checkbox" name="ux_chk_full_control_subscriber" id="ux_chk_full_control_subscriber" value="1" onclick="full_control_function_clean_up_optimizer(this, 'ux_div_subscriber_roles');" <?php echo isset( $subscriber ) && '1' === $subscriber[0] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_roles_and_capabilities_full_control_label ); ?>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_wordpress_data_manual_clean_up_subscriber" id="ux_chk_wordpress_data_manual_clean_up_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[1] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_wp_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_wordpress_schedule_manual_clean_up_subscriber" id="ux_chk_wordpress_schedule_manual_clean_up_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[2] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_schedule_wp_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_database_manual_clean_up_subscriber" id="ux_chk_database_manual_clean_up_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[3] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_database_optimizer ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_database_schedule_clean_up_subscriber" id="ux_chk_database_schedule_clean_up_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[4] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_schedule_database_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_logs_subscriber" id="ux_chk_logs_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[5] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_logs_label ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_cron_jobs_subscriber" id="ux_chk_cron_jobs_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[6] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_cron_jobs_label ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_general_settings_subscriber" id="ux_chk_general_settings_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[7] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_general_settings_label ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_advance_security_subscriber" id="ux_chk_advance_security_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[8] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_security_settings ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_templates_subscriber" id="ux_chk_templates_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[9] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_general_other_settings ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_system_information_subscriber" id="ux_chk_system_information_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[10] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_system_information_label ); ?>
														</td>
														<td>
														</td>
														<td>
														</td>
													</tr>
												</tbody>
											</table>
											<i class="controls-description"><?php echo esc_attr( $cpo_roles_and_capabilities_choose_page_subscriber_access_tooltip ); ?></i>
										</div>
										<div class="line-separator"></div>
									</div>
								</div>
								<div class="form-group">
									<div id="ux_div_other_roles">
										<label class="control-label">
											<?php echo esc_attr( $cpo_roles_and_capabilities_other_role_label ); ?> :
											<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
										</label>
										<div class="table-margin-top">
											<table class="table table-striped table-bordered table-hover" id="ux_tbl_other">
												<thead>
													<tr>
														<th style="width: 40% !important;">
															<input type="checkbox" name="ux_chk_full_control_other" id="ux_chk_full_control_other" value="1" onclick="full_control_function_clean_up_optimizer(this, 'ux_div_other_roles');" <?php echo isset( $other ) && '1' === $other[0] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_roles_and_capabilities_full_control_label ); ?>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_wordpress_data_manual_clean_up_other" id="ux_chk_wordpress_data_manual_clean_up_other" value="1" <?php echo isset( $other ) && '1' === $other[1] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_wp_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_wordpress_schedule_manual_clean_up_other" id="ux_chk_wordpress_schedule_manual_clean_up_other" value="1" <?php echo isset( $other ) && '1' === $other[2] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_schedule_wp_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_database_manual_clean_up_other" id="ux_chk_database_manual_clean_up_other" value="1" <?php echo isset( $other ) && '1' === $other[3] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_database_optimizer ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_database_schedule_clean_up_other" id="ux_chk_database_schedule_clean_up_other" value="1" <?php echo isset( $other ) && '1' === $other[4] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_schedule_database_optimizer ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_logs_other" id="ux_chk_logs_other" value="1" <?php echo isset( $other ) && '1' === $other[5] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_logs_label ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_cron_jobs_other" id="ux_chk_cron_jobs_other" value="1" <?php echo isset( $other ) && '1' === $other[6] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_cron_jobs_label ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_general_settings_other" id="ux_chk_general_settings_other" value="1" <?php echo isset( $other ) && '1' === $other[7] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_general_settings_label ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_advance_security_other" id="ux_chk_advance_security_other" value="1" <?php echo isset( $other ) && '1' === $other[8] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_security_settings ); ?>
														</td>
														<td>
															<input type="checkbox" name="ux_chk_templates_other" id="ux_chk_templates_other" value="1" <?php echo isset( $other ) && '1' === $other[9] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_general_other_settings ); ?>
														</td>
													</tr>
													<tr>
														<td>
															<input type="checkbox" name="ux_chk_system_information_other" id="ux_chk_system_information_other" value="1" <?php echo isset( $other ) && '1' === $other[10] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_system_information_label ); ?>
														</td>
														<td>
														</td>
														<td>
														</td>
													</tr>
												</tbody>
											</table>
											<i class="controls-description"><?php echo esc_attr( $cpo_roles_and_capabilities_choose_page_other_access_tooltip ); ?></i>
										</div>
										<div class="line-separator"></div>
									</div>
								</div>
								<div class="form-group">
									<div id="ux_div_other_roles_capabilities">
										<label class="control-label">
											<?php echo esc_attr( $cpo_roles_and_capabilities_other_roles_capabilities ); ?> :
											<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
										</label>
										<div class="table-margin-top">
											<table class="table table-striped table-bordered table-hover" id="ux_tbl_other_roles">
												<thead>
													<tr>
														<th style="width: 40% !important;">
															<input type="checkbox" name="ux_chk_full_control_other_roles" id="ux_chk_full_control_other_roles" value="1" onclick="full_control_function_clean_up_optimizer(this, 'ux_div_other_roles_capabilities');" <?php echo '1' === $details_roles_capabilities['others_full_control_capability'] ? 'checked = checked' : ''; ?>>
															<?php echo esc_attr( $cpo_roles_and_capabilities_full_control_label ); ?>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$flag              = 0;
													$user_capabilities = get_others_capabilities_clean_up_optimizer();
													if ( isset( $user_capabilities ) && count( $user_capabilities ) > 0 ) {
														foreach ( $user_capabilities as $key => $value ) {
															$other_roles = in_array( $value, $other_roles_array, true ) ? 'checked=checked' : '';
															$flag++;
															if ( 0 === $key % 3 ) {
																?>
																<tr>
																<?php
															}
															?>
															<td>
																<input type="checkbox" name="ux_chk_other_capabilities_<?php echo esc_attr( $value ); ?>" id="ux_chk_other_capabilities_<?php echo esc_attr( $value ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo esc_attr( $other_roles ); ?>>
																<?php echo esc_attr( $value ); ?>
															</td>
															<?php
															if ( count( $user_capabilities ) === $flag && 1 === $flag % 3 ) {
																?>
																<td>
																</td>
																<td>
																</td>
																<?php
															}
															?>
															<?php
															if ( count( $user_capabilities ) === $flag && 2 === $flag % 3 ) {
																?>
																<td>
																</td>
																<?php
															}
															?>
															<?php
															if ( 0 === $flag % 3 ) {
																?>
															</tr>
																<?php
															}
														}
													}
													?>
													</tbody>
												</table>
												<i class="controls-description"><?php echo esc_attr( $cpo_roles_and_capabilities_other_roles_capabilities_tooltip ); ?></i>
											</div>
										<div class="line-separator"></div>
									</div>
								</div>
								<div class="form-actions">
									<div class="pull-right">
										<input type="submit" class="btn vivid-green" name="ux_btn_save_changes" id="ux_btn_save_changes" value="<?php echo esc_attr( $cpo_save_changes ); ?>">
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
					<a href="admin.php?page=cpo_notifications_setup">
						<?php echo esc_attr( $cpo_general_settings_label ); ?>
					</a>
					<span>></span>
				</li>
				<li>
					<span>
						<?php echo esc_attr( $cpo_roles_capabilities_label ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-users"></i>
							<?php echo esc_attr( $cpo_roles_capabilities_label ); ?>
						</div>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_roles_and_capabilities">
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
