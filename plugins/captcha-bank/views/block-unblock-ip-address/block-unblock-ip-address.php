<?php
/**
 * This Template is used for managing IP addresses.
 *
 * @author  Tech Banker
 * @package captcha-bank/views/block-unblock-ip-address
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
	} elseif ( BLACKLIST_CAPTCHA_BANK === '1' ) {
		$advance_security_manage_ip_address_nonce  = wp_create_nonce( 'captcha_manage_ip_address' );
		$advance_security_manage_ip_address_delete = wp_create_nonce( 'captcha_manage_ip_address_delete' );
		$timestamp                                 = time();
		$start_date                                = $timestamp - 2592000;
		$captcha_manage_ip_range                   = wp_create_nonce( 'captcha_manage_ip_ranges' );
		$advance_security_manage_ip_ranges_delete  = wp_create_nonce( 'captcha_manage_ip_ranges_delete' );
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
					<?php echo esc_attr( $cpb_ip_address_range ); ?>
				</span>
			</li>
		</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
						<i class="icon-custom-globe"></i>
							<?php echo esc_attr( $cpb_ip_address_range ); ?>
						</div>
						<p class="premium-editions">
							<?php echo esc_attr( $cpb_upgrade_need_help ); ?><a href="https://tech-banker.com/captcha-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_documentation ); ?></a><?php echo esc_attr( $cpb_read_and_check ); ?><a href="https://tech-banker.com/captcha-bank/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_demos_section ); ?></a>
						</p>
					</div>
					<div class="portlet-body form">
						<div class="form-body">
							<div class="tabbable-custom">
								<ul class="nav nav-tabs ">
									<li class="active">
										<a aria-expanded="true" href="#ip_address" data-toggle="tab">
											<?php echo esc_attr( $cpb_block_unblock_ip_address_label ); ?>
										</a>
									</li>
									<li>
										<a aria-expanded="false" href="#ip_range" data-toggle="tab">
											<?php echo esc_attr( $cpb_block_unblock_ip_range_label ); ?>
										</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="ip_address">
										<div class="portlet-body form">
											<form id="ux_frm_manage_ip_addreses">
												<div class="form-body">
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<?php echo esc_attr( $cpb_ip_address ); ?> :
																	<span class="required" aria-required="true">*</span>
																</label>
																<input type="text" class="form-control" name="ux_txt_address" id="ux_txt_address" value='<?php echo isset( $_REQUEST['ip_address'] ) ? esc_attr( long2ip_captcha_bank( wp_unslash( $_REQUEST['ip_address'] ) ) ) : ''; // WPCS: CSRF ok, input var ok, sanitization ok. ?>' placeholder='<?php echo esc_attr( $cpb_ip_address ); ?>' onblur="check_ip_address_captcha_bank('#ux_txt_address');" onfocus="prevent_paste_captcha_bank(this.id);" onkeypress="valid_ip_address_captcha_bank(event);">
																<i class="controls-description"><?php echo esc_attr( $cpb_manage_ip_addresses_tooltip ); ?></i>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<?php echo esc_attr( $cpb_block_for_title ); ?> :
																	<span class="required" aria-required="true">*</span>
																</label>
																<select name="ux_ddl_hour" id="ux_ddl_hour" class="form-control">
																	<option value="1Hour"><?php echo esc_attr( $cpb_one_hour ); ?></option>
																	<option value="12Hour"><?php echo esc_attr( $cpb_twelve_hours ); ?></option>
																	<option value="24hours"><?php echo esc_attr( $cpb_twenty_four_hours ); ?></option>
																	<option value="48hours"><?php echo esc_attr( $cpb_forty_eight_hours ); ?></option>
																	<option value="week"><?php echo esc_attr( $cpb_one_week ); ?></option>
																	<option value="month"><?php echo esc_attr( $cpb_one_month ); ?></option>
																	<option value="permanently"><?php echo esc_attr( $cpb_permanently ); ?></option>
																</select>
																<i class="controls-description"><?php echo esc_attr( $cpb_block_for_tooltip ); ?></i>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label">
															<?php echo esc_attr( $cpb_comments ); ?> :
														</label>
														<textarea class="form-control" name="ux_txtarea_comments" id="ux_txtarea_comments" rows="4" placeholder="<?php echo esc_attr( $cpb_placeholder_comment ); ?>"></textarea>
														<i class="controls-description"><?php echo esc_attr( $cpb_tooltip_comment ); ?></i>
													</div>
													<div class="form-actions">
														<div class="pull-right">
															<input type="button" class="btn vivid-green" name="ux_btn_clear" id="ux_btn_clear" value="<?php echo esc_attr( $cpb_button_clear ); ?>" onclick="clear_value_ip_address_captcha_bank();">
															<input type="submit" class="btn vivid-green" name="ux_btn_block_ip" id="ux_btn_block_ip" value="<?php echo esc_attr( $cpb_block_ip_address ); ?>">
														</div>
													</div>
												</div>
											</form>
										</div>
										<div class="line-separator"></div>
										<div class="portlet box vivid-green">
											<div class="portlet-title">
												<div class="caption">
												<i class="icon-custom-wrench"></i>
													<?php echo esc_attr( $cpb_manage_ip_addresses_view_block ); ?>
												</div>
											</div>
											<div class="portlet-body form">
												<form id="ux_frm_view_blocked_ip_addresses">
													<div class="form-body">
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label class="control-label">
																		<?php echo esc_attr( $cpb_start_date_heading ); ?> :
																		<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></</span>
																	</label>
																	<input type="text" class="form-control" name="ux_txt_cpb_start_date" value="<?php echo esc_attr( date( 'm/d/Y', $start_date ) ); ?>" id="ux_txt_cpb_start_date" onkeypress="prevent_data_captcha_bank(event)" placeholder="<?php echo esc_attr( $cpb_start_date_placeholder ); ?>">
																	<i class="controls-description"><?php echo esc_attr( $cpb_recent_login_logs_start_date_tooltip ); ?></i>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group">
																	<label class="control-label">
																		<?php echo esc_attr( $cpb_end_date_heading ); ?> :
																		<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
																	</label>
																	<input type="text" class="form-control" name="ux_txt_cpb_end_date" value="<?php echo esc_attr( date( 'm/d/Y', $timestamp ) ); ?>" id="ux_txt_cpb_end_date" onkeypress="prevent_data_captcha_bank(event);" placeholder="<?php echo esc_attr( $cpb_end_date_placeholder ); ?>">
																	<i class="controls-description"><?php echo esc_attr( $cpb_recent_login_logs_end_date_tooltip ); ?></i>
																</div>
															</div>
														</div>
														<div class="form-actions">
															<div class="pull-right">
																<input type="submit" class="btn vivid-green" name="ux_btn_recent_logs" id="ux_btn_recent_logs" value="<?php echo esc_attr( $cpb_submit ); ?>">
															</div>
														</div>
														<div class="line-separator"></div>
														<div class="table-top-margin">
															<select name="ux_ddl_manage_ip_addesses" id="ux_ddl_manage_ip_addesses" class="custom-bulk-width">
																<option value=""><?php echo esc_attr( $cpb_bulk_action ); ?></option>
																<option value="delete" style="color:red;"> <?php echo esc_attr( $cpb_delete ) . ' ( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></option>
															</select>
															<input type="button" class="btn vivid-green" name="ux_btn_apply" id="ux_btn_apply" value="<?php echo esc_attr( $cpb_apply ); ?>" onclick='premium_edition_notification_captcha_bank();'>
														</div>
														<table class="table table-striped table-bordered table-hover table-margin-top" id="ux_tbl_manage_ip_addresses">
															<thead>
																<tr>
																	<th style="text-align: center;" class="chk-action">
																		<input type="checkbox" name="ux_chk_all_manage_ip_address" id="ux_chk_all_manage_ip_address">
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
																			<?php echo esc_attr( $cpb_block_time ); ?>
																		</label>
																	</th>
																	<th>
																		<label class="control-label">
																			<?php echo esc_attr( $cpb_release_time ); ?>
																		</label>
																	</th>
																	<th>
																		<label class="control-label">
																			<?php echo esc_attr( $cpb_comments ); ?>
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
																foreach ( $manage_ip_address_date as $row ) {
																	?>
																	<tr>
																		<td style="text-align: center;">
																			<input type="checkbox" onclick="check_all_captcha_bank('#ux_chk_all_manage_ip_address', oTable_manage_ip_address );" name="ux_chk_manage_ip_address_<?php echo esc_attr( $row['meta_id'] ); ?>" id="ux_chk_manage_ip_address_<?php echo esc_attr( $row['meta_id'] ); ?>" value="<?php echo esc_attr( $row['meta_id'] ); ?>">
																		</td>
																		<td>
																			<label>
																				<?php echo esc_attr( long2ip_captcha_bank( $row['ip_address'] ) ); ?>
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
																	<label>
																		<?php
																		$blocking_time = $row['blocked_for'];
																		switch ( $blocking_time ) {
																			case '1Hour':
																				$newtime = $row['date_time'] + 60 * 60;
																				echo esc_attr( date_i18n( 'd M Y h:i A', $newtime ) );
																				break;

																			case '12Hour':
																				$newtime = $row['date_time'] + 12 * 60 * 60;
																				echo esc_attr( date_i18n( 'd M Y h:i A', $newtime ) );
																				break;

																			case '24hours':
																				$newtime = $row['date_time'] + 24 * 60 * 60;
																				echo esc_attr( date_i18n( 'd M Y h:i A', $newtime ) );
																				break;

																			case '48hours':
																				$newtime = $row['date_time'] + 2 * 24 * 60 * 60;
																				echo esc_attr( date_i18n( 'd M Y h:i A', $newtime ) );
																				break;

																			case 'week':
																				$newtime = $row['date_time'] + 7 * 24 * 60 * 60;
																				echo esc_attr( date_i18n( 'd M Y h:i A', $newtime ) );
																				break;

																			case 'month':
																				$newtime = $row['date_time'] + 30 * 24 * 60 * 60;
																				echo esc_attr( date_i18n( 'd M Y h:i A', $newtime ) );
																				break;

																			case 'permanently':
																				echo esc_attr( $cpb_never );
																				break;
																		}
																		?>
																	</label>
																</td>
																<td>
																	<label>
																		<?php echo esc_attr( $row['comments'] ); ?>
																	</label>
																</td>
																<td class="custom-alternative" style="text-align:center;vertical-align:middle;width:10%;">
																	<a href="javascript:void(0);">
																		<a class="btn captcha-bank-buttons" onclick="delete_ip_address_captcha_bank(<?php echo intval( $row['meta_id'] ); ?>)"><?php echo esc_attr( $cpb_delete ); ?></a>
																	</a>
																</td>
															</tr>
																	<?php
																}
																?>
														</tbody>
													</table>
												</div>
											</form>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="ip_range">
									<div class="portlet-body form">
										<form id="ux_frm_manage_ip_ranges">
											<div class="form-body">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label">
																<?php echo esc_attr( $cpb_manage_ip_ranges_start_range_title ); ?> :
																<span class="required" aria-required="true">*</span>
															</label>
															<input type="text" class="form-control" name="ux_txt_start_ip_range" id="ux_txt_start_ip_range" onfocus="prevent_paste_captcha_bank(this.id);" onblur="check_captcha_bank_ip_ranges_all(this);" value="" onkeyPress="valid_ip_address_captcha_bank(event);" placeholder="<?php echo esc_attr( $cpb_manage_ip_ranges_start_range_placeholder ); ?>">
															<i class="controls-description"><?php echo esc_attr( $cpb_manage_ip_ranges_start_range_tooltip ); ?></i>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label">
																<?php echo esc_attr( $cpb_manage_ip_ranges_end_range_title ); ?> :
															<span class="required" aria-required="true">*</span>
															</label>
															<input type="text" class="form-control" name="ux_txt_end_range" id="ux_txt_end_range" onfocus="prevent_paste_captcha_bank(this.id);" onblur="check_captcha_bank_ip_ranges_all(this);" value="" onkeyPress="valid_ip_address_captcha_bank(event);" placeholder="<?php echo esc_attr( $cpb_manage_ip_ranges_end_range_placeholder ); ?>">
															<i class="controls-description"><?php echo esc_attr( $cpb_manage_ip_ranges_end_range_tooltip ); ?></i>
														</div>
													</div>
													</div>
													<div class="form-group">
														<label class="control-label">
															<?php echo esc_attr( $cpb_block_for_title ); ?> :
															<span class="required" aria-required="true">*</span>
														</label>
														<select name="ux_ddl_blocked" id="ux_ddl_blocked" class="form-control">
															<option value="1Hour"><?php echo esc_attr( $cpb_one_hour ); ?></option>
															<option value="12Hour"><?php echo esc_attr( $cpb_twelve_hours ); ?></option>
															<option value="24hours"><?php echo esc_attr( $cpb_twenty_four_hours ); ?></option>
															<option value="48hours"><?php echo esc_attr( $cpb_forty_eight_hours ); ?></option>
															<option value="week"><?php echo esc_attr( $cpb_one_week ); ?></option>
															<option value="month"><?php echo esc_attr( $cpb_one_month ); ?></option>
															<option value="permanently"><?php echo esc_attr( $cpb_permanently ); ?></option>
														</select>
														<i class="controls-description"><?php echo esc_attr( $cpb_block_for_tooltip ); ?></i>
													</div>
													<div class="form-group">
														<label class="control-label">
															<?php echo esc_attr( $cpb_comments ); ?> :
														</label>
														<textarea class="form-control" name="ux_txtarea_manage_ip_range" id="ux_txtarea_manage_ip_range" rows="4" placeholder="<?php echo esc_attr( $cpb_placeholder_comment ); ?>"></textarea>
														<i class="controls-description"><?php echo esc_attr( $cpb_tooltip_comment ); ?></i>
													</div>
													<div class="form-actions">
														<div class="pull-right">
															<input type="button" class="btn vivid-green" name="ux_btn_clear" id="ux_btn_clear" value="<?php echo esc_attr( $cpb_button_clear ); ?>" onclick="clear_value_ip_range_captcha_bank();"/>
															<input type="submit" class="btn vivid-green" name="ux_btn_advance_security_ip_range_submit" id="ux_btn_advance_security_ip_range_submit" value="<?php echo esc_attr( $cbp_manage_ip_ranges_block ); ?>">
														</div>
													</div>
												</div>
											</form>
										</div>
										<div class="line-separator"></div>
										<div class="portlet box vivid-green">
											<div class="portlet-title">
												<div class="caption">
													<i class="icon-custom-wrench"></i>
													<?php echo esc_attr( $cpb_manage_ip_ranges_view_block ); ?>
												</div>
											</div>
											<div class="portlet-body form">
												<form id="ux_view_manage_ip_ranges">
													<div class="form-body">
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label class="control-label">
																		<?php echo esc_attr( $cpb_start_date_heading ); ?> :
																		<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></</span>
																	</label>
																	<div class="input-icon-custom right">
																		<input type="text" class="form-control" value="<?php echo esc_attr( date( 'm/d/Y', $start_date ) ); ?>" name="ux_txt_cpb_start_date_ip_range" id="ux_txt_cpb_start_date_ip_range" onkeypress="prevent_data_captcha_bank(event);"  placeholder="<?php echo esc_attr( $cpb_start_date_placeholder ); ?>">
																		<i class="controls-description"><?php echo esc_attr( $cpb_recent_login_logs_start_date_tooltip ); ?></i>
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group">
																	<label class="control-label">
																	<?php echo esc_attr( $cpb_end_date_heading ); ?> :
																		<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
																	</label>
																	<input type="text" class="form-control" name="ux_txt_cpb_end_date_ip_range" value="<?php echo esc_attr( date( 'm/d/Y', $timestamp ) ); ?>" id="ux_txt_cpb_end_date_ip_range" onkeypress="prevent_data_captcha_bank(event);" placeholder="<?php echo esc_attr( $cpb_end_date_placeholder ); ?>">
																	<i class="controls-description"><?php echo esc_attr( $cpb_recent_login_logs_end_date_tooltip ); ?></i>
																</div>
															</div>
														</div>
														<div class="form-actions">
															<div class="pull-right">
																<input type="submit" class="btn vivid-green" name="ux_btn_ip_range" id="ux_btn_ip_range" value="<?php echo esc_attr( $cpb_submit ); ?>">
															</div>
														</div>
														<div class="line-separator"></div>
														<div class="table-top-margin">
															<select name="ux_ddl_manage_ip_range" id="ux_ddl_manage_ip_range" class="custom-bulk-width">
																<option value=""><?php echo esc_attr( $cpb_bulk_action ); ?></option>
																<option value="delete" style="color:red;"><?php echo esc_attr( $cpb_delete ) . ' ( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></option>
															</select>
															<input type="button" class="btn vivid-green" name="ux_btn_apply" id="ux_btn_apply" value="<?php echo esc_attr( $cpb_apply ); ?>" onclick='premium_edition_notification_captcha_bank();'>
														</div>
														<table class="table table-striped table-bordered table-hover table-margin-top" id="ux_tbl_manage_ip_range">
															<thead>
																<tr>
																	<th style="text-align: center;" class="chk-action">
																		<input type="checkbox" name="ux_chk_all_manage_ip_range" id="ux_chk_all_manage_ip_range">
																	</th>
																	<th>
																		<label class="control-label">
																			<?php echo esc_attr( $cpb_ip_ranges ); ?>
																		</label>
																	</th>
																	<th>
																		<label class="control-label">
																			<?php echo esc_attr( $cpb_location ); ?>
																		</label>
																	</th>
																	<th>
																		<label class="control-label">
																			<?php echo esc_attr( $cpb_block_time ); ?>
																		</label>
																	</th>
																	<th>
																		<label class="control-label">
																			<?php echo esc_attr( $cpb_release_time ); ?>
																		</label>
																	</th>
																	<th>
																		<label class="control-label">
																			<?php echo esc_attr( $cpb_comments ); ?>
																		</label>
																	</th>
																	<th style="text-align:center;" class="chk-action">
																	<label class="control-label">
																		<?php echo esc_attr( $cpb_action ); ?>
																	</label>
																</th>
															</tr>
														</thead>
														<tbody id="dynamic_table_filter_range">
															<?php
															foreach ( $manage_ip_range_date as $row ) {
																?>
																<tr>
																<td style="text-align: center;">
																	<input type="checkbox" onclick="check_all_captcha_bank('#ux_chk_all_manage_ip_range', oTable_manage_ip_range );" name="ux_chk_manage_ip_range_<?php echo esc_attr( $row['meta_id'] ); ?>" id="ux_chk_manage_ip_range_<?php echo esc_attr( $row['meta_id'] ); ?>" value="<?php echo esc_attr( $row['meta_id'] ); ?>">
																</td>
																<td>
																	<label>
																		<?php $ip_address = explode( ',', $row['ip_range'] ); ?>
																		<?php echo esc_attr( long2ip_captcha_bank( $ip_address[0] ) ); ?> - <?php echo esc_attr( long2ip_captcha_bank( $ip_address[1] ) ); ?>
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
																	<label>
																		<?php
																		$blocking_time = $row['blocked_for'];
																		switch ( $blocking_time ) {
																			case '1Hour':
																				$newtime = $row['date_time'] + 60 * 60;
																				echo esc_attr( date_i18n( 'd M Y h:i A', $newtime ) );
																				break;

																			case '12Hour':
																				$newtime = $row['date_time'] + 12 * 60 * 60;
																				echo esc_attr( date_i18n( 'd M Y h:i A', $newtime ) );
																				break;

																			case '24hours':
																				$newtime = $row['date_time'] + 24 * 60 * 60;
																				echo esc_attr( date_i18n( 'd M Y h:i A', $newtime ) );
																				break;

																			case '48hours':
																				$newtime = $row['date_time'] + 2 * 24 * 60 * 60;
																				echo esc_attr( date_i18n( 'd M Y h:i A', $newtime ) );
																				break;

																			case 'week':
																				$newtime = $row['date_time'] + 7 * 24 * 60 * 60;
																				echo esc_attr( date_i18n( 'd M Y h:i A', $newtime ) );
																				break;

																			case 'month':
																				$newtime = $row['date_time'] + 30 * 24 * 60 * 60;
																				echo esc_attr( date_i18n( 'd M Y h:i A', $newtime ) );
																				break;

																			case 'permanently':
																				echo esc_attr( $cpb_never );
																				break;
																		}
																		?>
																		</label>
																	</td>
																	<td>
																		<label>
																			<?php echo esc_attr( $row['comments'] ); ?>
																		</label>
																	</td>
																	<td class="custom-alternative" style="text-align:center;vertical-align:middle;width:10%;">
																		<a href="javascript:void(0);">
																			<a class="btn captcha-bank-buttons" onclick="delete_ip_range_captcha_bank(<?php echo esc_attr( $row['meta_id'] ); ?>)"><?php echo esc_attr( $cpb_delete ); ?></a>
																		</a>
																	</td>
																</tr>
																<?php
															}
															?>
															</tbody>
														</table>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
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
				<span>
					<?php echo esc_attr( $cpb_ip_address_range ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-globe"></i>
						<?php echo esc_attr( $cpb_ip_address_range ); ?>
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
