<?php
/**
 * This Template is used for managing email settings.
 *
 * @author  Tech Banker
 * @package captcha-bank/views/general-settings
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} //exit if accessed directly
if ( ! is_user_logged_in() ) {
	return;
} else {
	$access_granted = false;
	foreach ( $user_role_permission as $permission ) {
		if ( current_user_can( $permission ) ) {
			$access_granted = true;
			break;
		}
	}
	if ( ! $access_granted ) {
		return;
	} elseif ( GENERAL_SETTINGS_CAPTCHA_BANK === '1' ) {
		?>
		<div class="page-bar">
			<ul class="page-breadcrumb">
			<li>
				<i class="icon-custom-home"></i>
				<a href="admin.php?page=captcha_bank">
					<?php echo esc_attr( $cpb_captcha_bank_title ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<a href="admin.php?page=captcha_bank_notifications_setup">
					<?php echo esc_attr( $cpb_general_settings_menu ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<span>
					<?php echo esc_attr( $cpb_notification_setup_label ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-bell"></i>
						<?php echo esc_attr( $cpb_notification_setup_label ); ?>
					</div>
					<p class="premium-editions">
						<?php echo esc_attr( $cpb_upgrade_need_help ); ?><a href="https://tech-banker.com/captcha-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_documentation ); ?></a><?php echo esc_attr( $cpb_read_and_check ); ?><a href="https://tech-banker.com/captcha-bank/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_demos_section ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_alert_setup">
						<div class="form-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $cpb_alert_setup_email_fails_login_title ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
								</label>
								<select name="ux_ddl_fail" id="ux_ddl_fail" class="form-control">
									<option value="enable"><?php echo esc_attr( $cpb_enable ); ?></option>
									<option value="disable"><?php echo esc_attr( $cpb_disable ); ?></option>
								</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $cpb_alert_setup_email_success_login_title ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
								</label>
								<select name="ux_ddl_success" id="ux_ddl_success" class="form-control">
									<option value="enable"><?php echo esc_attr( $cpb_enable ); ?></option>
									<option value="disable"><?php echo esc_attr( $cpb_disable ); ?></option>
								</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $cpb_alert_setup_email_ip_address_blocked_title ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
								</label>
								<select name="ux_ddl_Ip_address" id="ux_ddl_Ip_address" class="form-control">
									<option value="enable"><?php echo esc_attr( $cpb_enable ); ?></option>
									<option value="disable"><?php echo esc_attr( $cpb_disable ); ?></option>
								</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpb_alert_setup_email_ip_address_unblocked_title ); ?> :
										<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
									</label>
									<select name="ux_ddl_address" id="ux_ddl_address" class="form-control">
										<option value="enable"><?php echo esc_attr( $cpb_enable ); ?></option>
										<option value="disable"><?php echo esc_attr( $cpb_disable ); ?></option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpb_alert_setup_email_ip_range_blocked_title ); ?> :
										<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
									</label>
									<select name="ux_ddl_Ip" id="ux_ddl_Ip" class="form-control">
										<option value="enable"><?php echo esc_attr( $cpb_enable ); ?></option>
										<option value="disable"><?php echo esc_attr( $cpb_disable ); ?></option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpb_alert_setup_email_ip_range_unblocked_title ); ?> :
										<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
									</label>
									<select name="ux_ddl_range" id="ux_ddl_range" class="form-control">
										<option value="enable"><?php echo esc_attr( $cpb_enable ); ?></option>
										<option value="disable"><?php echo esc_attr( $cpb_disable ); ?></option>
									</select>
								</div>
							</div>
						</div>
						<div class="line-separator"></div>
						<div class="form-actions">
							<div class="pull-right">
								<input type="submit" class="btn vivid-green" name="ux_btn_save_changes" id="ux_btn_save_changes" value="<?php echo esc_attr( $cpb_save_changes ); ?>">
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
				<a href="admin.php?page=captcha_bank">
					<?php echo esc_attr( $cpb_captcha_bank_title ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<a href="admin.php?page=captcha_bank_notifications_setup">
					<?php echo esc_attr( $cpb_general_settings_menu ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<span>
					<?php echo esc_attr( $cpb_notification_setup_label ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-bell"></i>
						<?php echo esc_attr( $cpb_notification_setup_label ); ?>
					</div>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<strong><?php echo esc_attr( $cpb_user_access_message ); ?></strong>
					</div>
				</div>
			</div>
		</div>
	</div>
		<?php
	}
}
