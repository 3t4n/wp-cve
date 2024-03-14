<?php
/**
 * This Template is used for managing visitor logs.
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
		$cpb_selected_logs_delete        = wp_create_nonce( 'captcha_selected_logs_delete' );
		$cpb_visitor_logs_start_end_date = wp_create_nonce( 'captcha_visitor_logs_start_end_date' );
		$timestamp                       = CAPTCHA_BOOSTER_LOCAL_TIME;
		$start_date                      = $timestamp - 86340;
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
					<?php echo esc_attr( $cpb_visitor_logs_title ); ?>
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
						<?php echo esc_attr( $cpb_recent_login_on_world_map ); ?>
					</div>
					<p class="premium-editions-booster">
						<a href="https://tech-banker.com/captcha-booster/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_full_features ); ?></a> <?php echo esc_attr( $cpb_or ); ?> <a href="https://tech-banker.com/captcha-booster/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_online_demos ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_visitor_log">
						<div class="form-body">
						<div id="map_canvas" class="custom-map"></div>
					</div>
				</form>
			</div>
			</div>
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-users"></i>
						<?php echo esc_attr( $cpb_visitor_logs_title ); ?>
					</div>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_visitor_logs">
						<div class="form-body">
						<?php
						if ( 'enable' === $visitor_logs_data_unserialize['visitor_logs_monitoring'] ) {
							?>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpb_start_date_heading ); ?> :
										<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
									</label>
									<div class="input-icon-custom right">
										<input type="text" class="form-control" name="ux_txt_cpb_start_date" value="<?php echo esc_attr( date( 'm/d/Y', $start_date ) ); ?>" onkeypress="prevent_data_captcha_booster(event);" id="ux_txt_cpb_start_date"  placeholder="<?php echo esc_attr( $cpb_start_date_placeholder ); ?>">
										<i class="controls-description"><?php echo esc_attr( $cpb_retriving_start_date_tooltip ); ?></i>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpb_end_date_heading ); ?> :
										<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_premium ) . ' )'; ?></span>
									</label>
									<input type="text" class="form-control" name="ux_txt_cpb_end_date" value="<?php echo esc_attr( date( 'm/d/Y', $timestamp ) ); ?>" id="ux_txt_cpb_end_date" onkeypress="prevent_data_captcha_booster(event);" placeholder="<?php echo esc_attr( $cpb_end_date_placeholder ); ?>">
									<i class="controls-description"><?php echo esc_attr( $cpb_retriving_end_date_tooltip ); ?></i>
								</div>
							</div>
						</div>
						<div class="form-actions">
							<div class="pull-right">
								<input type="submit" class="btn vivid-green" name="ux_btn_visitor_logs" id="ux_btn_visitor_logs" value="<?php echo esc_attr( $cpb_submit ); ?>">
							</div>
						</div>
						<div class="line-separator"></div>
						<div class="table-top-margin">
							<select name="ux_ddl_visitor_logs" id="ux_ddl_visitor_logs" class="custom-bulk-width" onchange="captcha_booster_show_user_block_for('#ux_ddl_visitor_logs', '#ux_ddl_visitor_logs_hour')">
								<option value=""><?php echo esc_attr( $cpb_bulk_action ); ?></option>
								<option value="delete" style="color:red;"><?php echo esc_attr( $cpb_delete ) . ' ( ' . esc_attr( $cpb_premium ) . ' ) '; ?></option>
								<option value="block" style="color:red;"><?php echo esc_attr( $cpb_block ) . ' ( ' . esc_attr( $cpb_premium ) . ' ) '; ?></option>
							</select>
							<select name="ux_ddl_visitor_logs_hour" id="ux_ddl_visitor_logs_hour" style="display:none" class="custom-bulk-width">
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
						<table class="table table-striped table-bordered table-hover table-margin-top" id="ux_tbl_visitor_logs">
							<thead>
								<tr>
									<th style="text-align:center;width: 5% !important;" class="chk-action">
										<input type="checkbox" name="ux_chk_visitor_logs" id="ux_chk_visitor_logs">
									</th>
									<th style="width:15%;">
										<label class="control-label">
											<?php echo esc_attr( $cpb_user_name ); ?>
										</label>
									</th>
									<th style="width:15%;">
										<label class="control-label">
											<?php echo esc_attr( $cpb_ip_address ); ?>
										</label>
									</th>
									<th style="width:15%;">
										<label class="control-label">
											<?php echo esc_attr( $cpb_location ); ?>
										</label>
									</th>
									<th style="width:25%;">
										<label class="control-label">
											<?php echo esc_attr( $cpb_details ); ?>
										</label>
									</th>
									<th style="width:15%;">
										<label class="control-label">
											<?php echo esc_attr( $cpb_date_time ); ?>
										</label>
									</th>
									<th style="text-align:center;width: 10%;" class="chk-action">
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
											<td style="text-align: center;width: 5% !important;">
												<input type="checkbox" onclick="check_all_captcha_booster('#ux_chk_visitor_logs');" name="ux_chk_details_<?php echo intval( $row['meta_id'] ); ?>" id="ux_chk_details_<?php echo intval( $row['meta_id'] ); ?>" value="<?php echo intval( $row['meta_id'] ); ?>">
											</td>
											<td style="width: 15%;">
												<label>
												<?php echo false !== $row['username'] ? esc_attr( $row['username'] ) : esc_attr( $cpb_na ); ?>
											</label>
										</td>
										<td style="width: 15%;">
											<label>
												<?php echo esc_attr( long2ip_captcha_booster( $row['user_ip_address'] ) ); ?>
											</label>
										</td>
										<td style="width: 15%;">
											<label>
												<?php echo '' !== $row['location'] ? esc_attr( $row['location'] ) : esc_attr( $cpb_na ); ?>
											</label>
										</td>
										<td style="width: 25%;">
											<label>
												<?php echo esc_attr( $cpb_resources ); ?>: <?php echo esc_attr( $row['resources'] ); ?><br/>
												<?php echo esc_attr( $cpb_http_user_agent ); ?>: <?php echo esc_attr( $row['http_user_agent'] ); ?>
											</label>
										</td>
										<td style="width: 15%;">
											<label>
												<?php echo esc_attr( date_i18n( 'd M Y h:i A', $row['date_time'] ) ); ?>
											</label>
										</td>
										<td class="custom-alternative" style="width: 10%;">
											<a href="javascript:void(0);" class="btn captcha-booster-buttons" onclick='delete_selected_log_captcha_booster(<?php echo intval( $row['meta_id'] ); ?>, <?php echo wp_json_encode( $cpb_delete_data ); ?>, "admin.php?page=cpb_visitor_logs");'><?php echo esc_attr( $cpb_delete ); ?></a>
											<a href="admin.php?page=cpb_manage_ip_addresses&ip_address=<?php echo esc_attr( $row['user_ip_address'] ); ?>" class="btn captcha-booster-buttons"><?php echo esc_attr( $cpb_block_ip_address ); ?></a>
										</td>
									</tr>
										<?php
									}
									?>
								</tbody>
							</table>
							<?php
						} else {
							?>
							<strong>
								<?php echo esc_attr( $cpb_live_traffic_monitoring_message ); ?>
							</strong>
							<?php
						}
						?>
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
					<?php echo esc_attr( $cpb_visitor_logs_title ); ?>
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
						<?php echo esc_attr( $cpb_visitor_logs_title ); ?>
					</div>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_visitor_log">
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
