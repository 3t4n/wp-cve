<?php
/**
 * This Template is used for managing recent login logs.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/views/logs
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
	} elseif ( LOGS_SETTINGS_CAPTCHA_BOOSTER === '1' ) {
		$cpb_selected_logs_delete = wp_create_nonce( 'captcha_selected_logs_delete' );
		$cpb_start_end_date       = wp_create_nonce( 'captcha_recent_start_end_date' );
		$end_date                 = CAPTCHA_BOOSTER_LOCAL_TIME;
		$start_date               = $end_date - 604380;
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
				<a href="admin.php?page=cpb_live_traffic">
					<?php echo esc_attr( $cpb_logs_menu ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<span>
					<?php echo esc_attr( $cpb_recent_login_log_title ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-clock"></i>
						<?php echo esc_attr( $cpb_recent_login_on_world_map ); ?>
					</div>
					<p class="premium-editions-booster">
						<a href="https://tech-banker.com/captcha-booster/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_full_features ); ?></a> <?php echo esc_attr( $cpb_or ); ?> <a href="https://tech-banker.com/captcha-booster/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_online_demos ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
						<form id="ux_frm_recent_login_logs">
							<div class="form-body">
						<div id="map_canvas" class="custom-map"></div>
						</div>
					</form>
				</div>
			</div>
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-clock"></i>
						<?php echo esc_attr( $cpb_recent_login_log_title ); ?>
					</div>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_recent_login">
						<div class="form-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
									<?php echo esc_attr( $cpb_start_date_heading ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</label>
								<input type="text" class="form-control" name="ux_txt_cpb_start_date" id="ux_txt_cpb_start_date" value="<?php echo esc_attr( date( 'm/d/Y', $start_date ) ); ?>" onkeypress="prevent_data_captcha_booster(event);" placeholder="<?php echo esc_attr( $cpb_start_date_placeholder ); ?>">
								<i class="controls-description"><?php echo esc_attr( $cpb_retriving_start_date_tooltip ); ?></i>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $cpb_end_date_heading ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
								</label>
								<input type="text" class="form-control" name="ux_txt_cpb_end_date" id="ux_txt_cpb_end_date" value="<?php echo esc_attr( date( 'm/d/Y', $end_date ) ); ?>" onkeypress="prevent_data_captcha_booster(event);" placeholder="<?php echo esc_attr( $cpb_end_date_placeholder ); ?>">
								<i class="controls-description"><?php echo esc_attr( $cpb_retriving_end_date_tooltip ); ?></i>
							</div>
						</div>
						</div>
						<div class="form-actions">
							<div class="pull-right">
								<input type="submit" class="btn vivid-green" name="ux_btn_recent_logs" id="ux_btn_recent_logs" value="<?php echo esc_attr( $cpb_submit ); ?>">
							</div>
						</div>
						<div class="line-separator"></div>
						<div class="form-actions">
							<div class="table-top-margin">
								<select name="ux_ddl_recent_logs" id="ux_ddl_recent_logs" class="custom-bulk-width" onchange="captcha_booster_show_user_block_for('#ux_ddl_recent_logs', '#ux_ddl_login_logs_blocked_for')">
									<option value=""><?php echo esc_attr( $cpb_bulk_action ); ?></option>
									<option value="delete" style="color: red;"><?php echo esc_attr( $cpb_delete ) . ' ( ' . esc_attr( $cpb_premium ) . ' ) '; ?></option>
									<option value="block" style="color: red;"><?php echo esc_attr( $cpb_block ) . ' ( ' . esc_attr( $cpb_premium ) . ' ) '; ?></option>
								</select>
								<select name="ux_ddl_login_logs_blocked_for" id="ux_ddl_login_logs_blocked_for" class="custom-bulk-width" style="display:none;">
									<option value="1Hour"><?php echo esc_attr( $cpb_one_hour ); ?></option>
									<option value="12Hour"><?php echo esc_attr( $cpb_twelve_hours ); ?></option>
									<option value="24hours"><?php echo esc_attr( $cpb_twenty_four_hours ); ?></option>
									<option value="48hours"><?php echo esc_attr( $cpb_forty_eight_hours ); ?></option>
									<option value="week"><?php echo esc_attr( $cpb_one_week ); ?></option>
									<option value="month"><?php echo esc_attr( $cpb_one_month ); ?></option>
									<option value="permanently"><?php echo esc_attr( $cpb_permanently ); ?></option>
								</select>
								<input type="button" class="btn vivid-green" name="ux_btn_apply" id="ux_btn_apply" onclick='premium_edition_notification_captcha_booster();' value="<?php echo esc_attr( $cpb_apply ); ?>">
							</div>
							<table class="table table-striped table-bordered table-hover table-margin-top" id="ux_tbl_recent_logs">
								<thead>
									<tr>
									<th style="text-align:center;width: 5%;" class="chk-action">
										<input type="checkbox" class="custom-chkbox-operation" name="ux_chk_all_user" id="ux_chk_all_user">
									</th>
									<th>
										<label class="control-label">
											<?php echo esc_attr( $cpb_user_name ); ?>
										</label>
									</th>
									<th>
										<label class="control-label">
											<?php echo esc_attr( $cpb_ip_address ); ?>
										</label>
									</th>
									<th>
										<label class="control-label">
											<?php echo esc_attr( $cpb_location ); ?>
										</label>
									</th>
									<th>
										<label class="control-label">
											<?php echo esc_attr( $cpb_date_time ); ?>
										</label>
									</th>
									<th>
										<label class="control-label">
											<?php echo esc_attr( $cpb_status ); ?>
										</label>
									</th>
									<th style="text-align:center;" class="chk-action">
										<label class="control-label">
											<?php echo esc_attr( $cpb_action ); ?>
										</label>
									</th>
								</tr>
							</thead>
							<tbody id="dynamic_table_filter">
									<?php
									foreach ( $cpb_data_logs as $row ) {
										?>
										<tr>
										<td style="text-align:center;width: 5%;">
											<label>
												<input  type="checkbox" onclick="check_all_captcha_booster('#ux_chk_all_user');" name="ux_chk_recent_login_<?php echo intval( $row['meta_id'] ); ?>" id="ux_chk_recent_login_<?php echo intval( $row['meta_id'] ); ?>" value="<?php echo intval( $row['meta_id'] ); ?>">
											</label>
										</td>
										<td>
											<label>
												<?php echo '' !== $row['username'] ? esc_attr( $row['username'] ) : esc_attr( $cpb_na ); ?>
											</label>
										</td>
										<td>
											<label>
												<?php echo esc_attr( long2ip_captcha_booster( $row['user_ip_address'] ) ); ?>
											</label>
										</td>
										<td>
											<label>
												<?php echo '' !== $row['location'] ? esc_attr( $row['location'] ) : esc_attr( $cpb_na ); ?>
											</label>
										</td>
										<td>
											<label>
												<?php echo esc_attr( date_i18n( 'd M Y h:i A', $row['date_time'] ) ); ?>
											</label>
										</td>
										<td>
											<?php $status = $row['status']; ?>
											<div style="background-color:<?php echo 'Success' === $status ? '#00934A' : '#D11919'; ?>; text-align:center; color:#FFFFFF;">
												<?php echo esc_attr( $status ); ?>
											</div>
										</td>
										<td class="custom-alternative" style="width: 10%;">
											<a href="javascript:void(0);" class="btn captcha-booster-buttons"  onclick='delete_selected_log_captcha_booster(<?php echo intval( $row['meta_id'] ); ?>,<?php echo wp_json_encode( $cpb_delete_data ); ?>, "admin.php?page=cpb_login_logs")'><?php echo esc_attr( $cpb_delete ); ?></a>
											<a href="admin.php?page=cpb_manage_ip_addresses&ip_address=<?php echo esc_attr( $row['user_ip_address'] ); ?>" class="btn captcha-booster-buttons"><?php echo esc_attr( $cpb_block_ip_address ); ?></a>
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
				<a href="admin.php?page=cpb_captcha_booster">
					<?php echo esc_attr( $cpb_captcha_booster_breadcrumb ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<a href="admin.php?page=cpb_live_traffic">
					<?php echo esc_attr( $cpb_logs_menu ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<span>
					<?php echo esc_attr( $cpb_recent_login_log_title ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-clock"></i>
						<?php echo esc_attr( $cpb_recent_login_log_title ); ?>
					</div>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_recent_login">
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
