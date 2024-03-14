<?php
/**
 * This Template is used for managing display settings.
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
		$display_setting      = explode( ',', isset( $display_settings_unserialized_data['settings'] ) ? $display_settings_unserialized_data['settings'] : '' );
		$captcha_type_display = wp_create_nonce( 'captcha_booster_settings' );
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
					<?php echo esc_attr( $cpb_display_settings_title ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-paper-clip"></i>
						<?php echo esc_attr( $cpb_display_settings_title ); ?>
					</div>
					<p class="premium-editions-booster">
						<a href="https://tech-banker.com/captcha-booster/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_full_features ); ?></a> <?php echo esc_attr( $cpb_or ); ?> <a href="https://tech-banker.com/captcha-booster/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_online_demos ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_display_settings">
						<div class="form-body">
						<label class="control-label">
							<?php echo esc_attr( $cpb_display_settings_enable_captcha_for ); ?> :
							<span class="required" aria-required="true">*</span>
						</label>
						<table class="table table-striped table-bordered table-margin-top" id="ux_tbl_display_settings" style="margin-bottom:0px !important;">
							<thead>
								<tr>
									<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_login_form" value="1" <?php echo isset( $display_setting[0] ) && '1' === $display_setting[0] ? 'checked=checked' : ''; ?>>
									<?php echo esc_attr( $cpb_display_settings_login_form ); ?>
								</th>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_bbpress_login" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_captcha_bbpress_login ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
							</tr>
							<tr>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_registration_form" value="1" <?php echo isset( $display_setting[2] ) && '1' === $display_setting[2] ? 'checked=checked' : ''; ?>>
									<?php echo esc_attr( $cpb_display_settings_registration_form ); ?>
								</th>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_bbpress_register" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_captcha_bbpress_register ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
							</tr>
							<tr>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_password_form" value="1" <?php echo isset( $display_setting[4] ) && '1' === $display_setting[4] ? 'checked=checked' : ''; ?>>
									<?php echo esc_attr( $cpb_display_settings_reset_password_form ); ?>
									</th>
									<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_bbpress_lost_password" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_captcha_bbpress_lost_password ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
							</tr>
							<tr>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_comment_form" value="1" <?php echo isset( $display_setting[6] ) && '1' === $display_setting[6] ? 'checked=checked' : ''; ?>>
									<?php echo esc_attr( $cpb_display_settings_comment_form ); ?>
								</th>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_bbpress_new_topic" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_captcha_bbpress_new_topic ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
							</tr>
							<tr>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_admin_form" value="1" <?php echo isset( $display_setting[8] ) && '1' === $display_setting[8] ? 'checked=checked' : ''; ?>>
									<?php echo esc_attr( $cpb_display_settings_admin_comment_form ); ?>
								</th>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_bbpress_reply_topic" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_captcha_bbpress_reply_topic ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
							</tr>
							<tr>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_hide_captcha_for_user" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_hide_captcha_register_user ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_buddypress_login" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_buddypress_login ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
							</tr>
							<tr>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_woocommerce_login" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_captcha_woocommerce_login ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_buddypress" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_buddypress ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
							</tr>
							<tr>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_woocommerce_register" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_captcha_woocommerce_register ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_buddypress_lost_password" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_buddypress_lost_password ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
							</tr>
							<tr>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_woocommerce_lost_password" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_captcha_woocommerce_lost_password ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_jetpack_form" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_captcha_jetpack_form ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
							</tr>
							<tr>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_woocommerce_checkout" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_captcha_woocommerce_checkout ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_wpforo_login" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_captcha_wpforo_login ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
								<tr>
									<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_contact_form7" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_contact_form7 ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
								<th class="control-label">
									<input type="checkbox" name="ux_chk[]" id="ux_chk_captcha_wpforo_register" value="1" disabled='disabled'>
									<?php echo esc_attr( $cpb_display_settings_captcha_wpforo_register ); ?>
									<span style="color:red;"> <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</th>
							</tr>
							</thead>
						</table>
						<i class="controls-description"><?php echo esc_attr( $cpb_display_settings_enable_captcha_tooltip ); ?></i>
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
					<?php echo esc_attr( $cpb_display_settings_title ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-paper-clip"></i>
						<?php echo esc_attr( $cpb_display_settings_title ); ?>
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
