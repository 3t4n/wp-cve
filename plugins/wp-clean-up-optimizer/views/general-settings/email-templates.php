<?php
/**
 * This Template is used for displaying Email Templates.
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
		$email_template_data = wp_create_nonce( 'email_template_data' );
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
						<?php echo esc_attr( $cpo_email_templates_label ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-link"></i>
							<?php echo esc_attr( $cpo_email_templates_label ); ?>
						</div>
						<p class="premium-editions-clean-up-optimizer">
							<?php echo esc_attr( $cpo_upgrade_know_about ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>" target="_blank" class="premium-editions-documentation"> <?php echo esc_attr( $cpo_full_features ); ?></a> <?php echo esc_attr( $cpo_chek_our ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>/backend-demos" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpo_online_demos ); ?></a>
						</p>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_email_templates">
							<div class="form-body">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpo_email_templates_choose_email_template_label ); ?> :
										<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
									</label>
									<select name="ux_ddl_user_success" id="ux_ddl_user_success" class="form-control" onchange="template_change_data_clean_up_optimizer();">
										<option value="template_for_user_success"><?php echo esc_attr( $cpo_email_templates_successful_login_dropdown ); ?></option>
										<option value="template_for_user_failure"><?php echo esc_attr( $cpo_email_templates_failure_login_dropdown ); ?></option>
										<option value="template_for_ip_address_blocked"><?php echo esc_attr( $cpo_email_templates_ip_address_blocked_dropdown ); ?></option>
										<option value="template_for_ip_address_unblocked"><?php echo esc_attr( $cpo_email_templates_ip_address_unblocked_dropdown ); ?></option>
										<option value="template_for_ip_range_blocked"><?php echo esc_attr( $cpo_email_templates_ip_range_blocked_dropdown ); ?></option>
										<option value="template_for_ip_range_unblocked"><?php echo esc_attr( $cpo_email_templates_ip_range_unblocked_dropdown ); ?></option>
									</select>
									<i class="controls-description"><?php echo esc_attr( $cpo_email_templates_choose_template_tooltip ); ?></i>
								</div>
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpo_email_templates_send_to_label ); ?> :
										<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
									</label>
									<input type="text" class="form-control" name="ux_txt_send_to" id="ux_txt_send_to" value="" placeholder="<?php echo esc_attr( $cpo_email_templates_email_address_placeholder ); ?>">
									<i class="controls-description"><?php echo esc_attr( $cpo_email_templates_send_emails_address_tooltip ); ?></i>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">
												<?php echo esc_attr( $cpo_email_templates_cc_label ); ?> :
												<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
											</label>
											<input type="text" class="form-control" name="ux_txt_cc" id="ux_txt_cc" placeholder="<?php echo esc_attr( $cpo_email_templates_cc_email_placeholder ); ?>">
											<i class="controls-description"><?php echo esc_attr( $cpo_email_templates_cc_email_address_tooltip ); ?></i>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">
												<?php echo esc_attr( $cpo_email_templates_bcc_label ); ?> :
												<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
											</label>
											<input type="text" class="form-control" name="ux_txt_bcc" id="ux_txt_bcc" placeholder="<?php echo esc_attr( $cpo_email_templates_bcc_email_placeholder ); ?>">
											<i class="controls-description"><?php echo esc_attr( $cpo_email_templates_bcc_email_address_tooltip ); ?></i>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpo_subject_label ); ?> :
										<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
									</label>
									<input type="text" class="form-control" name="ux_txt_subject" id="ux_txt_subject"  placeholder="<?php echo esc_attr( $cpo_email_templates_subject_placeholder ); ?>">
									<i class="controls-description"><?php echo esc_attr( $cpo_email_templates_subject_email_tooltip ); ?></i>
								</div>
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpo_email_templates_message_label ); ?> :
										<span class="required" aria-required="true">*<?php echo ' ( ' . esc_attr( $cpo_upgrade ) . ' )'; ?></span>
									</label>
									<?php
									$distribution = '';
									wp_editor(
										$distribution, 'ux_heading_content', array(
											'media_buttons' => false,
											'textarea_rows' => 8,
											'tabindex' => 4,
										)
									);
									?>
									<i class="controls-description"><?php echo esc_attr( $cpo_email_templates_content_email_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
								<div class="form-actions">
									<div class="pull-right">
										<input type="hidden" id="ux_email_template_meta_id" value=""/>
										<input type="submit" class="btn vivid-green" name="ux_btn_email_change" id="ux_btn_email_change" value="<?php echo esc_attr( $cpo_save_changes ); ?>">
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
						<?php echo esc_attr( $cpo_email_templates_label ); ?>
					</span>
				</li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
						<i class="icon-custom-link"></i>
							<?php echo esc_attr( $cpo_email_templates_label ); ?>
						</div>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_email_templates">
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
