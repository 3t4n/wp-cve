<?php
/**
 * This Template is used for saving email templates.
 *
 * @author  Tech Banker
 * @package captcha-bank/views/email-templates
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
		$captcha_type_email_templates = wp_create_nonce( 'captcha_type_email_templates' );
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
					<?php echo esc_attr( $cpb_email_templates_menu ); ?>
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
						<?php echo esc_attr( $cpb_email_templates_menu ); ?>
					</div>
					<p class="premium-editions">
						<?php echo esc_attr( $cpb_upgrade_need_help ); ?><a href="https://tech-banker.com/captcha-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_documentation ); ?></a><?php echo esc_attr( $cpb_read_and_check ); ?><a href="https://tech-banker.com/captcha-bank/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_demos_section ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_email_templates">
						<div class="form-body">
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpb_email_templates_choose_title ); ?> :
								<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
							</label>
							<select name="ux_ddl_user_success" id="ux_ddl_user_success" class="form-control" onchange="email_template_type_captcha_bank();">
								<option value="template_for_user_success"><?php echo esc_attr( $cpb_email_templates_successful_login ); ?></option>
								<option value="template_for_user_failure"><?php echo esc_attr( $cpb_email_templates_failure_login ); ?></option>
								<option value="template_for_ip_address_blocked"><?php echo esc_attr( $cpb_email_templates_ip_address_blocked ); ?></option>
								<option value="template_for_ip_address_unblocked"><?php echo esc_attr( $cpb_email_templates_ip_address_unblocked ); ?></option>
								<option value="template_for_ip_range_blocked"><?php echo esc_attr( $cpb_email_templates_ip_range_blocked ); ?></option>
								<option value="template_for_ip_range_unblocked"><?php echo esc_attr( $cpb_email_templates_ip_range_unblocked ); ?></option>
							</select>
							<i class="controls-description"><?php echo esc_attr( $cpb_email_templates_tooltip ); ?></i>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpb_email_templates_send_to_title ); ?> :
								<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
							</label>
							<input type="text" class="form-control" name="ux_txt_send_to" id="ux_txt_send_to" value="" placeholder="<?php echo esc_attr( $cpb_email_templates_send_email_address_placeholder ); ?>">
							<i class="controls-description"><?php echo esc_attr( $cpb_email_templates_send_to_email_tooltip ); ?></i>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $cpb_email_templates_cc_title ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
								</label>
								<input type="text" class="form-control" name="ux_txt_cc" id="ux_txt_cc" value="" placeholder="<?php echo esc_attr( $cpb_email_templates_cc_email_address_placeholder ); ?>">
								<i class="controls-description"><?php echo esc_attr( $cpb_email_templates_cc_email_tooltip ); ?></i>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpb_email_templates_bcc_title ); ?> :
										<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
									</label>
									<input type="text" class="form-control" name="ux_txt_bcc" id="ux_txt_bcc" value="" placeholder="<?php echo esc_attr( $cpb_email_templates_bcc_email_address_placeholder ); ?>">
									<i class="controls-description"><?php echo esc_attr( $cpb_email_templates_bcc_email_tooltip ); ?></i>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpb_email_subject_title ); ?> :
								<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
							</label>
							<input type="text" class="form-control" name="ux_txt_subject" id="ux_txt_subject" value="" placeholder="<?php echo esc_attr( $cpb_placeholder_subject ); ?>">
							<i class="controls-description"><?php echo esc_attr( $cpb_email_templates_subject_tooltip ); ?></i>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpb_email_templates_cpb_message_title ); ?> :
								<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
							</label>
							<?php
							$distribution = '';
							wp_editor(
								$distribution, 'ux_heading_content', array(
									'media_buttons' => false,
									'textarea_rows' => 8,
									'tabindex'      => 4,
								)
							);
							?>
							<i class="controls-description"><?php echo esc_attr( $cpb_email_templates_message_content_tooptip ); ?></i>
						</div>
						<div class="line-separator"></div>
						<div class="form-actions">
							<div class="pull-right">
								<input type="hidden" id="ux_email_template_meta_id" value=""/>
								<input type="submit" class="btn vivid-green" name="ux_btn_email_change" id="ux_btn_email_change" value="<?php echo esc_attr( $cpb_save_changes ); ?>">
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
					<?php echo esc_attr( $cpb_email_templates_menu ); ?>
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
						<?php echo esc_attr( $cpb_email_templates_menu ); ?>
					</div>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_email_templates">
						<div class="form-body">
						<strong><?php echo esc_attr( $cpb_user_access_message ); ?></strong>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
		<?php
	}
}
