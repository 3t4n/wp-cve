<?php
/**
 * This Template is used for sending Feature Requests.
 *
 * @author  Tech Banker
 * @package captcha-bank/views/feature-requests
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//exit if accessed directly
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
				<span>
					<?php echo esc_attr( $cpb_feature_requests ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-call-out"></i>
						<?php echo esc_attr( $cpb_feature_requests ); ?>
					</div>
					<p class="premium-editions">
						<?php echo esc_attr( $cpb_upgrade_need_help ); ?><a href="https://tech-banker.com/captcha-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_documentation ); ?></a><?php echo esc_attr( $cpb_read_and_check ); ?><a href="https://tech-banker.com/captcha-bank/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_demos_section ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_feature_requests">
						<div class="form-body">
						<div class="note note-warning">
							<h4 class="block">
								<?php echo esc_attr( $cpb_feature_requests_thank_you ); ?>
							</h4>
							<p>
								<?php echo esc_attr( $cpb_feature_requests_fill_form ); ?>
							</p>
							<p>
								<?php echo esc_attr( $cpb_feature_requests_any_suggestion ); ?>
							</p>
							<p>
								<?php echo esc_attr( $cpb_feature_requests_write_us_on ); ?>
								<a href="mailto:support@tech-banker.com" target="_blank">support@tech-banker.com</a>
							</p>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $cpb_feature_requests_name_title ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<input type="text" class="form-control" name="ux_txt_your_name" id="ux_txt_your_name" value="" placeholder= "<?php echo esc_attr( $cpb_feature_requests_name_placeholder ); ?>">
								<i class="controls-description"><?php echo esc_attr( $cpb_feature_requests_name_tooltip ); ?></i>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $cpb_feature_requests_email_title ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<input type="text" class="form-control" name="ux_txt_email_address" id="ux_txt_email_address" value=""  placeholder= "<?php echo esc_attr( $cpb_feature_requests_email_placeholder ); ?>">
								<i class="controls-description"><?php echo esc_attr( $cpb_feature_requests_email_tooltip ); ?></i>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpb_feature_requests ); ?> :
								<span class="required" aria-required="true">*</span>
							</label>
							<textarea class="form-control" name="ux_txtarea_feature_request" id="ux_txtarea_feature_request" rows="8"  placeholder= "<?php echo esc_attr( $cpb_feature_requests_placeholder ); ?>"></textarea>
							<i class="controls-description"><?php echo esc_attr( $cpb_feature_requests_tooltip ); ?></i>
						</div>
						<div class="line-separator"></div>
						<div class="form-actions">
							<div class="pull-right">
								<input type="submit" class="btn vivid-green" name="ux_btn_send_request" id="ux_btn_send_request" value= "<?php echo esc_attr( $cpb_feature_requests_send_request ); ?>">
							</div>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
		<?php
	}
}
