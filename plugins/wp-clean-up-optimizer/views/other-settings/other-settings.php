<?php
/**
 * This Template is used for managing Other Plugin settings.
 *
 * @author Tech Banker
 * @package wp-cleanup-optimizer/views/other_settings
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
	} elseif ( OTHER_SETTINGS_CLEAN_UP_OPTIMIZER === '1' ) {
		$clean_up_other_settings = wp_create_nonce( 'clean_up_other_settings' );
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
				<span>
					<?php echo esc_attr( $cpo_general_other_settings ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-wrench"></i>
						<?php echo esc_attr( $cpo_general_other_settings ); ?>
					</div>
					<p class="premium-editions-clean-up-optimizer">
							<?php echo esc_attr( $cpo_upgrade_know_about ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>" target="_blank" class="premium-editions-documentation"> <?php echo esc_attr( $cpo_full_features ); ?></a> <?php echo esc_attr( $cpo_chek_our ); ?> <a href="<?php echo esc_attr( TECH_BANKER_BETA_URL ); ?>/backend-demos" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpo_online_demos ); ?></a>
						</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_other_settings">
						<div class="form-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $cpo_other_settings_trackbacks_label ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<select name="ux_ddl_trackback" id="ux_ddl_trackback" class="form-control">
									<option value="enable"><?php echo esc_attr( $cpo_enable ); ?></option>
									<option value="disable"><?php echo esc_attr( $cpo_disable ); ?></option>
								</select>
								<i class="controls-description"><?php echo esc_attr( $cpo_other_settings_trackbacks_tooltip ); ?></i>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $cpo_comments ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<select name="ux_ddl_Comments" id="ux_ddl_Comments" class="form-control">
									<option value="enable"><?php echo esc_attr( $cpo_enable ); ?></option>
									<option value="disable"><?php echo esc_attr( $cpo_disable ); ?></option>
								</select>
								<i class="controls-description"><?php echo esc_attr( $cpo_other_settings_comments_tooltip ); ?></i>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $cpo_other_settings_live_traffic_monitoring_label ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<select name="ux_ddl_live_traffic_monitoring" id="ux_ddl_live_traffic_monitoring" class="form-control">
									<option value="enable"><?php echo esc_attr( $cpo_enable ); ?></option>
									<option value="disable"><?php echo esc_attr( $cpo_disable ); ?></option>
								</select>
								<i class="controls-description"><?php echo esc_attr( $cpo_other_settings_live_traffic_monitoring_tooltip ); ?></i>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $cpo_other_settings_visitor_logs_monitoring_label ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<select name="ux_ddl_visitor_log_monitoring" id="ux_ddl_visitor_log_monitoring" class="form-control">
									<option value="enable"><?php echo esc_attr( $cpo_enable ); ?></option>
									<option value="disable"><?php echo esc_attr( $cpo_disable ); ?></option>
								</select>
								<i class="controls-description"><?php echo esc_attr( $cpo_other_settings_visitor_logs_monitoring_tooltip ); ?></i>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpo_other_settings_remove_tables_at_uninstall ); ?> :
								<span class="required" aria-required="true">*</span>
							</label>
							<select name="ux_ddl_remove_tables" id="ux_ddl_remove_tables" class="form-control ">
								<option value="enable"><?php echo esc_attr( $cpo_enable ); ?></option>
								<option value="disable"><?php echo esc_attr( $cpo_disable ); ?></option>
							</select>
							<i class="controls-description"><?php echo esc_attr( $cpo_other_settings_remove_tables_at_uninstall_tooltip ); ?></i>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpo_other_settings_ip_address_fetching_method ); ?> :
								<span class="required" aria-required="true">*</span>
							</label>
							<select name="ux_ddl_ip_address_fetching_method" id="ux_ddl_ip_address_fetching_method" class="form-control">
								<option value=""><?php echo esc_attr( $cpo_other_settings_ip_address_fetching_option1 ); ?></option>
								<option value="REMOTE_ADDR"><?php echo esc_attr( $cpo_other_settings_ip_address_fetching_option2 ); ?></option>
								<option value="HTTP_X_FORWARDED_FOR"><?php echo esc_attr( $cpo_other_settings_ip_address_fetching_option3 ); ?></option>
								<option value="HTTP_X_REAL_IP"><?php echo esc_attr( $cpo_other_settings_ip_address_fetching_option4 ); ?></option>
								<option value="HTTP_CF_CONNECTING_IP"><?php echo esc_attr( $cpo_other_settings_ip_address_fetching_option5 ); ?></option>
							</select>
							<i class="controls-description"><?php echo esc_attr( $cpo_other_settings_ip_address_tooltips ); ?></i>
						</div>
						<div class="line-separator"></div>
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
				<span>
					<?php echo esc_attr( $cpo_general_other_settings ); ?>
				</span>
			</li>
		</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-wrench"></i>
							<?php echo esc_attr( $cpo_general_other_settings ); ?>
						</div>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_live_traffic">
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
