<?php
/**
 * This Template is used for managing whitelist ip addresses.
 *
 * @author  Tech Banker
 * @package captcha-bank/views/whitelist-ip-addresses.
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
	} elseif ( WHITELIST_CAPTCHA_BANK === '1' ) {
		$captcha_bank_whitelist_ip_nonce  = wp_create_nonce( 'captcha_bank_whitelist_ip_nonce' );
		$captcha_bank_whitelist_ip_delete = wp_create_nonce( 'captcha_bank_whitelist_ip_delete' );
		?>
		<div class="page-bar">
			<ul class="page-breadcrumb">
			<li>
				<i class="icon-custom-home"></i>
				<a href="admin.php?page=captcha_bank_whitelist_ip_addresses">
					<?php echo esc_attr( $cpb_captcha_bank_title ); ?>
				</a>
				<span>></span>
			</li>
			<li>
				<span>
					<?php echo esc_attr( $cpb_whitelist_ip_address ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-target"></i>
						<?php echo esc_attr( $cpb_whitelist_ip_address ); ?>
					</div>
					<p class="premium-editions">
						<?php echo esc_attr( $cpb_upgrade_need_help ); ?><a href="https://tech-banker.com/captcha-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_documentation ); ?></a><?php echo esc_attr( $cpb_read_and_check ); ?><a href="https://tech-banker.com/captcha-bank/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_demos_section ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_whitelist_ip_addresses">
						<div class="form-body">
							<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $cpb_ip_type ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<select id="ux_ddl_whitelist_ip_type" name="ux_ddl_whitelist_ip_type" class="form-control" onchange="select_type_contact_bank('#ux_ddl_whitelist_ip_type');">
									<option value="single"> <?php echo esc_attr( $cpb_single_ip ); ?> </option>
									<option value="range"> <?php echo esc_attr( $cpb_ip_range ); ?> </option>
									<option value="multiple"> <?php echo esc_attr( $cpb_multiple ); ?> </option>
								</select>
								<i class="controls-description"><?php echo esc_attr( $cpb_whitelist_ip_type_tooltip ); ?></i>
							</div>
							<div class="form-group" id="ux_div_single_ip">
								<label class="control-label">
									<?php echo esc_attr( $cpb_ip_address ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<input type="text" class="form-control" name="ux_txt_whitelist_single_address" id="ux_txt_whitelist_single_address" onblur="check_ip_address_captcha_bank('#ux_txt_whitelist_single_address');" value='<?php echo isset( $_REQUEST['ip_address'] ) ? esc_attr( long2ip_captcha_bank( wp_unslash( $_REQUEST['ip_address'] ) ) ) : ''; // WPCS: CSRF ok, input var ok, sanitization ok. ?>' placeholder='<?php echo esc_attr( $cpb_ip_address ); ?>' >
								<i class="controls-description"><?php echo esc_attr( $cpb_whitelistip_for_login ); ?></i>
							</div>
							<div class="row" id="ux_div_ip_range" style="display:none;">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">
											<?php echo esc_attr( $cpb_start_ip ); ?> :
											<span class="required" aria-required="true">*</span>
										</label>
										<input type="text" class="form-control" name="ux_txt_whitelist_start_range" id="ux_txt_whitelist_start_range" onblur="check_captcha_bank_ip_ranges_all(this);" value='' placeholder='<?php echo esc_attr( $cpb_ip_address ); ?>' >
										<i class="controls-description"><?php echo esc_attr( $cpb_whitelistip_for_login ); ?></i>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpb_end_ip ); ?> :
									</label>
									<input type="text" class="form-control" name="ux_txt_whitelist_end_range" id="ux_txt_whitelist_end_range" onblur="check_captcha_bank_ip_ranges_all(this);" value='' placeholder='<?php echo esc_attr( $cpb_ip_address ); ?>' >
									<i class="controls-description"><?php echo esc_attr( $cpb_end_ip_range_tooltip ); ?></i>
								</div>
								</div>
							</div>
							<div class="form-group" id="ux_div_multiple_ip" style="display:none;">
								<label class="control-label">
									<?php echo esc_attr( $cpb_ip_address ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
								</label>
								<input type="text" class="form-control" name="ux_txt_whitelist_multiple_ip" id="ux_txt_whitelist_multiple_ip" onblur="check_multiple_ip_address_captcha_bank('#ux_txt_whitelist_multiple_ip');" value='' placeholder='<?php echo esc_attr( $cpb_ip_address ); ?>' >
								<i class="controls-description"><?php echo esc_attr( $cpb_exclude_ips_tooltip ); ?></i>
							</div>
							<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $cpb_comments ); ?> :
								</label>
								<textarea class="form-control" name="ux_txtarea_comments" id="ux_txtarea_comments" rows="2" placeholder="<?php echo esc_attr( $cpb_placeholder_comment ); ?>"></textarea>
								<i class="controls-description"><?php echo esc_attr( $cpb_give_your_remarks_here ); ?></i>
							</div>
							<div class="line-separator"></div>
							<div class="form-actions">
								<div class="pull-right">
									<input type="button" class="btn vivid-green" name="ux_btn_clear" id="ux_btn_clear" value="<?php echo esc_attr( $cpb_button_clear ); ?>" onclick="clear_value_ip_address_captcha_bank();">
									<input type="submit" class="btn vivid-green" name="ux_btn_block_ip" id="ux_btn_block_ip" value="<?php echo esc_attr( $cpb_whitelist_ip_address ); ?>">
								</div>
							</div>
						</div>
					</form>
					</div>
				</div>
				<div class="portlet box vivid-green">
					<div class="portlet-title">
						<div class="caption">
							<i class="icon-custom-globe"></i>
							<?php echo esc_attr( $cpb_manage_whitelist_ip_addresses ); ?>
						</div>
					</div>
					<div class="portlet-body form">
						<form id="ux_frm_manage_whitelist_ip">
							<div class="form-body">
								<div class="table-top-margin">
									<select name="ux_ddl_manage_whitelist_ip_addesses" id="ux_ddl_manage_whitelist_ip_addesses" class="custom-bulk-width">
										<option value=""><?php echo esc_attr( $cpb_bulk_action ); ?></option>
									<option value="delete" style="color:red;"><?php echo esc_attr( $cpb_delete ) . ' ( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></option>
									</select>
									<input type="button" class="btn vivid-green" name="ux_btn_apply" id="ux_btn_apply" value="<?php echo esc_attr( $cpb_apply ); ?>" onclick='premium_edition_notification_captcha_bank();'>
								</div>
								<table class="table table-striped table-bordered table-hover table-margin-top" id="ux_tbl_manage_whitelist_ip_addresses">
									<thead>
										<tr>
											<th style="text-align: center;" class="chk-action">
												<input type="checkbox" name="ux_chk_all_manage_ip_address" id="ux_chk_all_manage_ip_address">
											</th>
											<th >
												<label class="control-label">
													<?php echo esc_attr( $cpb_start_ip ); ?>
												</label>
											</th>
											<th >
												<label class="control-label">
													<?php echo esc_attr( $cpb_end_ip ); ?>
												</label>
											</th>
											<th >
												<label class="control-label">
													<?php echo esc_attr( $cpb_date_time ); ?>
												</label>
											</th>
											<th >
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
									<tbody>
										<?php
										foreach ( $data as $raw_row ) {
											$row = maybe_unserialize( $raw_row->meta_value );
											?>
											<tr>
												<td style="text-align: center;">
													<input type="checkbox" onclick="check_all_captcha_bank('#ux_chk_all_manage_ip_address', oTable );" name="ux_chk_manage_ip_address_<?php echo esc_attr( $raw_row->meta_id ); ?>" id="ux_chk_manage_ip_address_<?php echo esc_attr( $raw_row->meta_id ); ?>" value="<?php echo esc_attr( $raw_row->meta_id ); ?>">
												</td>
												<?php
												switch ( $row['whitelist_ip_type'] ) {
													case 'single':
														?>
														<td>
															<label>
																<?php echo esc_attr( long2ip_captcha_bank( $row['whitelist_single_ip'] ) ); ?>
															</label>
														</td>
														<?php
														break;
													case 'range':
														?>
														<td>
															<label>
																<?php echo esc_attr( long2ip_captcha_bank( $row['whitelist_ip_start_range'] ) ); ?>
															</label>
														</td>
														<?php
														break;
													case 'multiple':
														?>
														<td>
															<label>
																<?php echo long2ip_captcha_bank( $row['whitelist_multiple_ip'] );// WPCS: XSS ok. ?>
															</label>
														</td>
														<?php
														break;
												}
												?>
												<td>
													<label>
														<?php echo esc_attr( long2ip_captcha_bank( $row['whitelist_ip_end_range'] ) ); ?>
													</label>
												</td>
												<td>
													<label>
														<?php echo esc_attr( date_i18n( 'd M Y h:i A', $row['date_time'] ) ); ?>
													</label>
												</td>
												<td>
													<label>
														<?php echo( esc_attr( $row['whitelist_ip_comments'] ) ); ?>
													</label>
												</td>
												<td class="custom-alternative" style="text-align:center;vertical-align:middle;width:10%;">
													<a href="javascript:void(0);">
														<a class="btn captcha-bank-buttons" onclick='delete_ip_address_data_captcha_bank( <?php echo intval( $raw_row->id ); ?>, "captcha_delete_whitelist_ip_module", "<?php echo esc_attr( $captcha_bank_whitelist_ip_delete ); ?>", "captcha_bank_whitelist_ip_addresses" );'><?php echo esc_attr( $cpb_delete ); ?></a>
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
					<?php echo esc_attr( $cpb_whitelist_ip_address ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-target"></i>
						<?php echo esc_attr( $cpb_whitelist_ip_address ); ?>
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
