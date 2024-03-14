<?php
/**
 * This Template is used for managing roles and capabilities.
 *
 * @author  Tech Banker
 * @package captcha-bank/views/roles-and-capabilities
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
		$roles_and_capabilities = explode( ',', isset( $details_roles_capabilities['roles_and_capabilities'] ) ? $details_roles_capabilities['roles_and_capabilities'] : '' );
		$author                 = explode( ',', isset( $details_roles_capabilities['author_privileges'] ) ? $details_roles_capabilities['author_privileges'] : '' );
		$editor                 = explode( ',', isset( $details_roles_capabilities['editor_privileges'] ) ? $details_roles_capabilities['editor_privileges'] : '' );
		$contributor            = explode( ',', isset( $details_roles_capabilities['contributor_privileges'] ) ? $details_roles_capabilities['contributor_privileges'] : '' );
		$subscriber             = explode( ',', isset( $details_roles_capabilities['subscriber_privileges'] ) ? $details_roles_capabilities['subscriber_privileges'] : '' );
		$others                 = explode( ',', isset( $details_roles_capabilities['other_privileges'] ) ? $details_roles_capabilities['other_privileges'] : '' );
		?>
		<div class="page-bar">
			<ul class="page-breadcrumb">
			<li>
				<i class="icon-custom-home"></i>
				<a href="admin.php?page=captcha_bank">
				</a>
				<?php echo esc_attr( $cpb_captcha_bank_title ); ?>
				<span>></span>
			</li>
			<li>
				<a href="admin.php?page=captcha_bank_notifications_setup">
				</a>
				<?php echo esc_attr( $cpb_general_settings_menu ); ?>
				<span>></span>
			</li>
			<li>
				<span>
				</span>
				<?php echo esc_attr( $cpb_roles_and_capabilities_menu ); ?>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-users"></i>
						<?php echo esc_attr( $cpb_roles_and_capabilities_menu ); ?>
					</div>
					<p class="premium-editions">
						<?php echo esc_attr( $cpb_upgrade_need_help ); ?><a href="https://tech-banker.com/captcha-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_documentation ); ?></a><?php echo esc_attr( $cpb_read_and_check ); ?><a href="https://tech-banker.com/captcha-bank/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_demos_section ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_roles_and_capabilities">
						<div class="form-body">
						<div id="ux_div_plugin_settings" style="margin-bottom:20px !important;">
							<label class="control-label">
								<?php echo esc_attr( $cpb_show_roles_and_capabilities_menu ); ?> :
								<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
							</label>
							<table class="table table-striped table-bordered table-margin-top" id="ux_tbl_plugin_settings" style="margin-bottom:0px !important;">
								<thead>
									<tr>
									<th>
										<input type="checkbox" name="ux_chk_administrator" id="ux_chk_administrator" checked="checked" disabled="disabled" value="1" <?php echo '1' === $roles_and_capabilities[0] ? 'checked = checked' : ''; ?>>
										<?php echo esc_attr( $cpb_roles_and_capabilities_administrator ); ?>
									</th>
									<th>
										<input type="checkbox" name="ux_chk_author" id="ux_chk_author" value="1" onclick="show_roles_capabilities_captcha_bank(this, 'ux_div_author_roles');" <?php echo '1' === $roles_and_capabilities[1] ? 'checked = checked' : ''; ?>>
										<?php echo esc_attr( $cpb_roles_and_capabilities_author ); ?>
									</th>
									<th>
										<input type="checkbox" name="ux_chk_editor" id="ux_chk_editor" value="1" onclick="show_roles_capabilities_captcha_bank(this, 'ux_div_editor_roles');" <?php echo '1' === $roles_and_capabilities[2] ? 'checked = checked' : ''; ?>>
										<?php echo esc_attr( $cpb_roles_and_capabilities_editor ); ?>
									</th>
									<th>
										<input type="checkbox"  name="ux_chk_contributor" id="ux_chk_contributor" value="1" onclick="show_roles_capabilities_captcha_bank(this, 'ux_div_contributor_roles');" <?php echo '1' === $roles_and_capabilities[3] ? 'checked = checked' : ''; ?>>
										<?php echo esc_attr( $cpb_roles_and_capabilities_contributor ); ?>
									</th>
									<th>
										<input type="checkbox" name="ux_chk_subscriber" id="ux_chk_subscriber" value="1" onclick="show_roles_capabilities_captcha_bank(this, 'ux_div_subscriber_roles');" <?php echo '1' === $roles_and_capabilities[4] ? 'checked = checked' : ''; ?>>
										<?php echo esc_attr( $cpb_roles_and_capabilities_subscriber ); ?>
									</th>
									<th>
										<input type="checkbox" name="ux_chk_other" id="ux_chk_other" value="1" onclick="show_roles_capabilities_captcha_bank(this, 'ux_div_other_roles');" <?php echo '1' === $roles_and_capabilities[5] ? 'checked = checked' : ''; ?>>
										<?php echo esc_attr( $cpb_roles_and_capabilities_others ); ?>
									</th>
								</tr>
								</thead>
							</table>
							<i class="controls-description"><?php echo esc_attr( $cpb_roles_and_capabilities_menu_tooltip ); ?></i>
						</div>
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpb_roles_and_capabilities_topbar_menu ); ?> :
								<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
							</label>
							<select name="ux_ddl_settings" id="ux_ddl_settings" class="form-control">
								<option value="enable"><?php echo esc_attr( $cpb_enable ); ?></option>
								<option value="disable"><?php echo esc_attr( $cpb_disable ); ?></option>
							</select>
							<i class="controls-description"><?php echo esc_attr( $cpb_roles_and_capabilities_topbar_menu_tooltip ); ?></i>
						</div>
						<div class="line-separator"></div>
						<div class="form-group">
							<div id="ux_div_administrator_roles">
								<label class="control-label">
									<?php echo esc_attr( $cpb_roles_and_capabilities_administrator_role ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_administrator">
									<thead>
										<tr>
											<th style="width: 40% !important;">
												<input type="checkbox" name="ux_chk_full_control_administrator" id="ux_chk_full_control_administrator" checked="checked" disabled="disabled" value="1">
												<?php echo esc_attr( $cpb_roles_and_capabilities_full_control ); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_captcha_setup_admin" disabled="disabled" checked="checked" id="ux_chk_captcha_setup_admin" value="1">
												<?php echo esc_attr( $cpb_captcha_wizard_label ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_general_settings_admin" disabled="disabled" checked="checked" id="ux_chk_general_settings_admin" value="1">
												<?php echo esc_attr( $cpb_general_settings_menu ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_whitelist_ip_admin" disabled="disabled" checked="checked" id="ux_chk_whitelist_ip_admin" value="1">
												<?php echo esc_attr( $cpb_whitelist_ip_address ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_ip_address_range_admin" disabled="disabled" checked="checked" id="ux_chk_ip_address_range_admin" value="1">
												<?php echo esc_attr( $cpb_ip_address_range ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_blockage_settings_admin" disabled="disabled" checked="checked" id="ux_chk_blockage_settings_admin" value="1">
												<?php echo esc_attr( $cpb_blockage_settings_label ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_block_unblock_countries_admin" disabled="disabled" checked="checked" id="ux_chk_block_unblock_countries_admin" value="1">
												<?php echo esc_attr( $cpb_block_unblock_countries_label ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_template_admin" disabled="disabled" checked="checked" id="ux_chk_template_admin" value="1">
												<?php echo esc_attr( $cpb_other_settings_menu ); ?>
											</td>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_system_information_admin" disabled="disabled" checked="checked" id="ux_chk_system_information_admin" value="1">
												<?php echo esc_attr( $cpb_system_information_menu ); ?>
											</td>
											<td>
											</td>
										</tr>
										</tbody>
								</table>
								<i class="controls-description"><?php echo esc_attr( $cpb_roles_and_capabilities_administrator_role_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
						<div class="form-group">
							<div id="ux_div_author_roles">
								<label class="control-label">
									<?php echo esc_attr( $cpb_roles_and_capabilities_author_role ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_author">
									<thead>
										<tr>
											<th style="width: 40% !important;">
												<input type="checkbox" name="ux_chk_full_control_author" id="ux_chk_full_control_author" value="1"  onclick="full_control_function_captcha_bank(this, 'ux_div_author_roles');"  <?php echo isset( $author ) && '1' === $author[0] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_roles_and_capabilities_full_control ); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_captcha_setup_author" id="ux_chk_captcha_setup_author" value="1" <?php echo isset( $author ) && '1' === $author[1] ? 'checked = checked' : ''; ?>?>
												<?php echo esc_attr( $cpb_captcha_wizard_label ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_general_settings_author" id="ux_chk_general_settings_author" value="1" <?php echo isset( $author ) && '1' === $author[2] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_general_settings_menu ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_whitelist_ip_author" id="ux_chk_whitelist_ip_author" value="1" <?php echo isset( $author ) && '1' === $author[3] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_whitelist_ip_address ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_ip_address_range_author" id="ux_chk_ip_address_range_author" value="1" <?php echo isset( $author ) && '1' === $author[4] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_ip_address_range ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_blockage_settings_author" id="ux_chk_blockage_settings_author" value="1" <?php echo isset( $author ) && '1' === $author[5] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_blockage_settings_label ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_block_unblock_countries_author" id="ux_chk_block_unblock_countries_author"value="1" <?php echo isset( $author ) && '1' === $author[6] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_block_unblock_countries_label ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_templates_author" id="ux_chk_templates_author" value="1" <?php echo isset( $author ) && '1' === $author[7] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_other_settings_menu ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_system_information_author" id="ux_chk_system_information_author" value="1" <?php echo isset( $author ) && '1' === $author[8] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_system_information_menu ); ?>
												</td>
											<td>
											</td>
										</tr>
										</tbody>
								</table>
								<i class="controls-description"><?php echo esc_attr( $cpb_roles_and_capabilities_author_role_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
						<div class="form-group">
							<div id="ux_div_editor_roles">
								<label class="control-label">
									<?php echo esc_attr( $cpb_roles_and_capabilities_editor_role ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_editor">
										<thead>
											<tr>
												<th style="width: 40% !important;">
													<input type="checkbox" name="ux_chk_full_control_editor" id="ux_chk_full_control_editor" value="1" onclick="full_control_function_captcha_bank(this, 'ux_div_editor_roles');" <?php echo isset( $editor ) && '1' === $editor[0] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_roles_and_capabilities_full_control ); ?>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<input type="checkbox" name="ux_chk_captcha_setup_editor" id="ux_chk_captcha_setup_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[1] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_captcha_wizard_label ); ?>
												</td>
												<td>
													<input type="checkbox" name="ux_chk_general_settings_editor" id="ux_chk_general_settings_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[2] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_general_settings_menu ); ?>
												</td>
												<td>
												<input type="checkbox" name="ux_chk_whitelist_ip_editor" id="ux_chk_whitelist_ip_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[3] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_whitelist_ip_address ); ?>
											</td>
												</tr>
												<tr>
													<td>
														<input type="checkbox" name="ux_chk_ip_address_range_editor" id="ux_chk_ip_address_range_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[4] ? 'checked = checked' : ''; ?>>
														<?php echo esc_attr( $cpb_ip_address_range ); ?>
													</td>
													<td>
														<input type="checkbox" name="ux_chk_blockage_settings_editor" id="ux_chk_blockage_settings_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[5] ? 'checked = checked' : ''; ?>>
														<?php echo esc_attr( $cpb_blockage_settings_label ); ?>
													</td>
													<td>
														<input type="checkbox" name="ux_chk_block_unblock_countries_editor" id="ux_chk_block_unblock_countries_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[6] ? 'checked = checked' : ''; ?>>
														<?php echo esc_attr( $cpb_block_unblock_countries_label ); ?>
													</td>
											</tr>
											<tr>
												<td>
													<input type="checkbox" name="ux_chk_templates_editor" id="ux_chk_templates_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[7] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_other_settings_menu ); ?>
												</td>
												<td>
													<input type="checkbox" name="ux_chk_system_information_editor" id="ux_chk_system_information_editor" value="1" <?php echo isset( $editor ) && '1' === $editor[8] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_system_information_menu ); ?>
													</td>
												<td>
												</td>
											</tr>
											</tbody>
									</table>
									<i class="controls-description"><?php echo esc_attr( $cpb_roles_and_capabilities_editor_role_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
						<div class="form-group">
							<div id="ux_div_contributor_roles">
								<label class="control-label">
									<?php echo esc_attr( $cpb_roles_and_capabilities_contributor_role ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_contributor">
									<thead>
										<tr>
											<th style="width: 40% !important;">
												<input type="checkbox" name="ux_chk_full_control_contributor" id="ux_chk_full_control_contributor" value="1" onclick="full_control_function_captcha_bank(this, 'ux_div_contributor_roles');" <?php echo isset( $contributor ) && '1' === $contributor[0] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_roles_and_capabilities_full_control ); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_captcha_setup_contributor" id="ux_chk_captcha_setup_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[1] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_captcha_wizard_label ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_general_settings_contributor" id="ux_chk_general_settings_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[2] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_general_settings_menu ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_whitelist_ip_contributor" id="ux_chk_whitelist_ip_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[3] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_whitelist_ip_address ); ?>
											</td>
										</tr>
										<tr>
											<td>
												<input type="checkbox" name="ux_chk_ip_address_range_contributor" id="ux_chk_ip_address_range_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[4] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_ip_address_range ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_blockage_settings_contributor" id="ux_chk_blockage_settings_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[5] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_blockage_settings_label ); ?>
											</td>
											<td>
												<input type="checkbox" name="ux_chk_block_unblock_countries_contributor" id="ux_chk_block_unblock_countries_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[6] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_block_unblock_countries_label ); ?>
											</td>
										</tr>
										<tr>
										<td>
											<input type="checkbox" name="ux_chk_templates_contributor" id="ux_chk_templates_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[7] ? 'checked = checked' : ''; ?>>
											<?php echo esc_attr( $cpb_other_settings_menu ); ?>
										</td>
										<td>
											<input type="checkbox" name="ux_chk_system_information_contributor" id="ux_chk_system_information_contributor" value="1" <?php echo isset( $contributor ) && '1' === $contributor[8] ? 'checked = checked' : ''; ?>>
											<?php echo esc_attr( $cpb_system_information_menu ); ?>
										</td>
										<td>
										</td>
									</tr>
										</tbody>
								</table>
								<i class="controls-description"><?php echo esc_attr( $cpb_roles_and_capabilities_contributor_role_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
						<div class="form-group">
							<div id="ux_div_subscriber_roles">
								<label class="control-label">
									<?php echo esc_attr( $cpb_roles_and_capabilities_subscriber_role ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_subscriber">
										<thead>
											<tr>
												<th style="width: 40% !important;">
													<input type="checkbox" name="ux_chk_full_control_subscriber" id="ux_chk_full_control_subscriber" value="1" onclick="full_control_function_captcha_bank(this, 'ux_div_subscriber_roles');" <?php echo isset( $subscriber ) && '1' === $subscriber[0] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_roles_and_capabilities_full_control ); ?>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<input type="checkbox" name="ux_chk_captcha_setup_subscriber" id="ux_chk_captcha_setup_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[1] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_captcha_wizard_label ); ?>
												</td>
												<td>
													<input type="checkbox" name="ux_chk_general_settings_subscriber" id="ux_chk_general_settings_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[2] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_general_settings_menu ); ?>
												</td>
												<td>
													<input type="checkbox" name="ux_chk_whitelist_ip_subscriber" id="ux_chk_whitelist_ip_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[3] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_whitelist_ip_address ); ?>
												</td>
											</tr>
											<tr>
												<td>
													<input type="checkbox" name="ux_chk_ip_address_ranges_subscriber" id="ux_chk_ip_address_ranges_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[4] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_ip_address_range ); ?>
												</td>
												<td>
													<input type="checkbox" name="ux_chk_blockage_settingss_subscriber" id="ux_chk_blockage_settingss_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[5] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_blockage_settings_label ); ?>
												</td>
												<td>
													<input type="checkbox" name="ux_chk_block_unblock_countries_subscriber" id="ux_chk_block_unblock_countries_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[6] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_block_unblock_countries_label ); ?>
												</td>
											</tr>
											<tr>
												<td>
													<input type="checkbox" name="ux_chk_templates_subscriber" id="ux_chk_templates_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[7] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_other_settings_menu ); ?>
												</td>
												<td>
													<input type="checkbox" name="ux_chk_system_information_subscriber" id="ux_chk_system_information_subscriber" value="1" <?php echo isset( $subscriber ) && '1' === $subscriber[8] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_system_information_menu ); ?>
												</td>
													<td>
													</td>
											</tr>
											</tbody>
									</table>
									<i class="controls-description"><?php echo esc_attr( $cpb_roles_and_capabilities_subscriber_role_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
						<div class="form-group">
							<div id="ux_div_other_roles">
								<label class="control-label">
									<?php echo esc_attr( $cpb_roles_and_capabilities_other_role ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_other">
										<thead>
											<tr>
												<th style="width: 40% !important;">
													<input type="checkbox" name="ux_chk_full_control_others" id="ux_chk_full_control_others" value="1" onclick="full_control_function_captcha_bank(this, 'ux_div_other_roles');" <?php echo isset( $others ) && '1' === $others[0] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_roles_and_capabilities_full_control ); ?>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<input type="checkbox" name="ux_chk_captcha_setup_others" id="ux_chk_captcha_setup_others" value="1" <?php echo isset( $others ) && '1' === $others[1] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_captcha_wizard_label ); ?>
												</td>
												<td>
													<input type="checkbox" name="ux_chk_general_settings_others" id="ux_chk_general_settings_others" value="1" <?php echo isset( $others ) && '1' === $others[2] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_general_settings_menu ); ?>
												</td>
												<td>
													<input type="checkbox" name="ux_chk_whitelist_ip_others" id="ux_chk_whitelist_ip_others" value="1" <?php echo isset( $others ) && '1' === $others[3] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_whitelist_ip_address ); ?>
												</td>
											</tr>
											<tr>
												<td>
													<input type="checkbox" name="ux_chk_ip_address_range_others" id="ux_chk_ip_address_range_others" value="1" <?php echo isset( $others ) && '1' === $others[4] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_ip_address_range ); ?>
												</td>
												<td>
													<input type="checkbox" name="ux_chk_blockage_settings_others" id="ux_chk_blockage_settings_others" value="1" <?php echo isset( $others ) && '1' === $others[5] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_blockage_settings_label ); ?>
												</td>
												<td>
													<input type="checkbox" name="ux_chk_block_unblock_countries_others" id="ux_chk_block_unblock_countries_others" value="1" <?php echo isset( $others ) && '1' === $others[6] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_block_unblock_countries_label ); ?>
												</td>
											</tr>
											<tr>
												<td>
													<input type="checkbox" name="ux_chk_templates_others" id="ux_chk_templates_others" value="1" <?php echo isset( $others ) && '1' === $others[7] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_other_settings_menu ); ?>
												</td>
												<td>
													<input type="checkbox" name="ux_chk_system_information_others" id="ux_chk_system_information_others" value="1" <?php echo isset( $others ) && '1' === $others[8] ? 'checked = checked' : ''; ?>>
													<?php echo esc_attr( $cpb_system_information_menu ); ?>
												</td>
													<td>
													</td>
											</tr>
											</tbody>
									</table>
									<i class="controls-description"><?php echo esc_attr( $cpb_roles_and_capabilities_other_role_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
						<div class="form-group">
							<div id="ux_div_other_roles_capabilities">
								<label class="control-label">
									<?php echo esc_attr( $cpb_roles_and_capabilities_other_roles_capabilities ); ?> :
									<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
								</label>
								<div class="table-margin-top">
									<table class="table table-striped table-bordered table-hover" id="ux_tbl_other_roles">
									<thead>
										<tr>
											<th style="width: 40% !important;">
												<input type="checkbox" name="ux_chk_full_control_other_roles" id="ux_chk_full_control_other_roles" value="1" onclick="full_control_function_captcha_bank(this, 'ux_div_other_roles_capabilities');" <?php echo '1' === $details_roles_capabilities['others_full_control_capability'] ? 'checked = checked' : ''; ?>>
												<?php echo esc_attr( $cpb_roles_and_capabilities_full_control ); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$flag              = 0;
										$user_capabilities = get_others_capabilities_captcha_bank();
										foreach ( $user_capabilities as $key => $value ) {
											$other_roles = in_array( $value, $other_roles_array, true ) ? 'checked=checked' : '';
											$flag++;
											if ( 0 === $key % 3 ) {
												?>
												<tr>
												<?php
											}
											?>
												<td>
												<input type="checkbox" name="ux_chk_other_capabilities_<?php echo esc_attr( $value ); ?>" id="ux_chk_other_capabilities_<?php echo esc_attr( $value ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo esc_attr( $other_roles ); ?>>
												<?php echo esc_attr( $value ); ?>
												</td>
												<?php
												if ( count( $user_capabilities ) === $flag && 1 === $flag % 3 ) {
													?>
													<td>
													</td>
													<td>
													</td>
													<?php
												}
												?>
												<?php
												if ( count( $user_capabilities ) === $flag && 2 === $flag % 3 ) {
													?>
													<td>
													</td>
													<?php
												}
												?>
												<?php
												if ( 0 === $flag % 3 ) {
													?>
												</tr>
													<?php
												}
										}
										?>
									</tbody>
									</table>
									<i class="controls-description"><?php echo esc_attr( $cpb_roles_and_capabilities_other_roles_capabilities_tooltip ); ?></i>
								</div>
								<div class="line-separator"></div>
							</div>
						</div>
						<div class="form-actions">
							<div class="pull-right">
								<input type="submit" class="btn vivid-green" name="ux_btn_plugin_change" id="ux_btn_plugin_change" value="<?php echo esc_attr( $cpb_save_changes ); ?>">
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
						<?php echo esc_attr( $cpb_roles_and_capabilities_menu ); ?>
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
						<?php echo esc_attr( $cpb_roles_and_capabilities_menu ); ?>
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
