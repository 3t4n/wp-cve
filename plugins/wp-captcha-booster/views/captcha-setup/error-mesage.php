<?php
/**
 * This Template is used for saving error messages.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/views/captcha-setup
 * @version 1.0.0
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
	} elseif ( CAPTCHA_SETUP_CAPTCHA_BOOSTER === '1' ) {
		$captcha_type_error = wp_create_nonce( 'captcha_setup_error_message' );
		?>
		<div class="page-bar">
			<ul class="page-breadcrumb">
			<li>
				<i class="icon-custom-home"></i>
				<a href="admin.php?page=cpb_captcha_booster">
					<?php echo esc_attr( $cpb_captcha_booster_breadcrumb ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<a href="admin.php?page=cpb_captcha_booster">
					<?php echo esc_attr( $cpb_captcha_setup_menu ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<span>
					<?php echo esc_attr( $cpb_error_message_common ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-shield"></i>
						<?php echo esc_attr( $cpb_error_message_common ); ?>
					</div>
					<p class="premium-editions-booster">
						<a href="https://tech-banker.com/captcha-booster/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_full_features ); ?></a> <?php echo esc_attr( $cpb_or ); ?> <a href="https://tech-banker.com/captcha-booster/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_online_demos ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_error_message">
						<div class="form-body">
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpb_error_message_login_failure_title ); ?> :
								<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
							</label>
							<textarea class="form-control" name="ux_txt_attempts" id="ux_txt_attempts"  placeholder="<?php echo esc_attr( $cpb_error_message_login_failure_placeholder ); ?>"><?php echo isset( $error_messages_unserialize_data['for_login_attempts_error'] ) ? esc_attr( $error_messages_unserialize_data['for_login_attempts_error'] ) : ''; ?></textarea>
							<i class="controls-description"><?php echo esc_attr( $cpb_error_message_login_failure_tooltip ); ?></i>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpb_error_message_invalid_captcha_title ); ?> :
								<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
							</label>
							<textarea class="form-control" name="ux_txt_invalid" id="ux_txt_invalid"  placeholder="<?php echo esc_attr( $cpb_error_message_login_failure_placeholder ); ?>"><?php echo isset( $error_messages_unserialize_data['for_invalid_captcha_error'] ) ? esc_attr( $error_messages_unserialize_data['for_invalid_captcha_error'] ) : ''; ?></textarea>
							<i class="controls-description"><?php echo esc_attr( $cpb_error_message_invalid_captcha_tooltip ); ?></i>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpb_error_message_blocked_ip_address_title ); ?> :
								<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
							</label>
							<textarea class="form-control" name="ux_txt_ip_address" id="ux_txt_ip_address"  placeholder="<?php echo esc_attr( $cpb_error_message_login_failure_placeholder ); ?>"><?php echo isset( $error_messages_unserialize_data['for_blocked_ip_address_error'] ) ? esc_attr( $error_messages_unserialize_data['for_blocked_ip_address_error'] ) : ''; ?></textarea>
							<i class="controls-description"><?php echo esc_attr( $cpb_error_message_blocked_ip_address_tooltip ); ?></i>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpb_error_message_empty_captcha_title ); ?> :
								<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
							</label>
							<textarea class="form-control" name="ux_txt_empty" id="ux_txt_empty"  placeholder="<?php echo esc_attr( $cpb_error_message_login_failure_placeholder ); ?>"><?php echo isset( $error_messages_unserialize_data['for_captcha_empty_error'] ) ? esc_html( $error_messages_unserialize_data['for_captcha_empty_error'] ) : ''; ?></textarea>
							<i class="controls-description"><?php echo esc_attr( $cpb_error_message_empty_captcha_tooltip ); ?></i>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpb_error_message_blocked_ip_range_title ); ?> :
								<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
							</label>
							<textarea class="form-control" name="ux_txt_ip_range" id="ux_txt_ip_range"  placeholder="<?php echo esc_attr( $cpb_error_message_login_failure_placeholder ); ?>"><?php echo isset( $error_messages_unserialize_data['for_blocked_ip_range_error'] ) ? esc_attr( $error_messages_unserialize_data['for_blocked_ip_range_error'] ) : ''; ?></textarea>
							<i class="controls-description"><?php echo esc_attr( $cpb_error_message_blocked_ip_range_tooltip ); ?></i>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpb_error_messages_blocked_country_label ); ?> :
								<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
							</label>
							<textarea class="form-control" name="ux_txt_blocked_country" id="ux_txt_blocked_country"  placeholder="<?php echo esc_attr( $cpb_error_message_login_failure_placeholder ); ?>"><?php echo isset( $error_messages_unserialize_data['for_blocked_country_error'] ) ? esc_attr( trim( htmlspecialchars( htmlspecialchars_decode( $error_messages_unserialize_data['for_blocked_country_error'] ) ) ) ) : ''; ?></textarea>
							<i class="controls-description"><?php echo esc_attr( $cpb_error_messages_blocked_country_tooltip ); ?></i>
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
				<a href="admin.php?page=cpb_captcha_booster">
					<?php echo esc_attr( $cpb_captcha_booster_breadcrumb ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<a href="admin.php?page=cpb_captcha_booster">
					<?php echo esc_attr( $cpb_captcha_setup_menu ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<span>
					<?php echo esc_attr( $cpb_error_message_common ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-shield"></i>
						<?php echo esc_attr( $cpb_error_message_common ); ?>
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
