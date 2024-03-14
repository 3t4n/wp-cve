<?php
global $wpdb, $ARMemberLite, $arm_slugs, $arm_members_class, $arm_member_forms, $arm_global_settings, $arm_social_feature;
$date_format       = $arm_global_settings->arm_get_wp_date_format();
$globalSettings    = $arm_global_settings->global_settings;
$thank_you_page_id = isset( $globalSettings['thank_you_page_id'] ) ? $globalSettings['thank_you_page_id'] : 0;
$add_form_select   = '';
?>
<div class="wrap arm_page arm_manage_forms_main_wrapper">
	<div class="content_wrapper arm_manage_forms_container" id="content_wrapper">
		<div class="page_title"><?php esc_html_e( 'Manage Forms', 'armember-membership' ); ?></div>
		  
		<div class="armclear"></div>
		<div class="arm_manage_forms_content armPageContainer">
			<div class="arm_form_content_box">
				<!-- ****************************/.Registration Forms./***************************** -->
				<div class="arm_form_heading">
					<span><?php esc_html_e( 'Registration / Signup Forms', 'armember-membership' ); ?></span>
					
					
					<div class="armclear"></div>
				</div>
				<div class="armclear"></div>
				<div class="arm_form_list_container">
				<?php
				$registration_forms    = $wpdb->get_results( $wpdb->prepare('SELECT `arm_form_id`, `arm_form_label`, `arm_form_slug`, `arm_is_default`, `arm_form_updated_date` FROM `' . $ARMemberLite->tbl_arm_forms . "` WHERE `arm_form_type`=%s ORDER BY `arm_form_id` ASC LIMIT 0,1",'registration'), ARRAY_A );//phpcs:ignore --Reason $tbl_arm_forms is a table name. False Positive Alarm
				$add_form_select      .= '<input type="hidden" name="existing_form_registration" id="existing_form_registration_val" class="existing_form_select" value=""/>';
				$add_form_select      .= '<dl id="existing_form_registration" class="arm_selectbox existing_form_select">';
				$add_form_select      .= '<dt><span>' . esc_html__( 'Select Form', 'armember-membership' ) . '</span><input type="text" style="display:none;" value="" class="arm_autocomplete"/><i class="armfa armfa-caret-down armfa-lg"></i></dt>';
				$add_form_select_style = ( is_rtl() ) ? 'margin-right: 35px! important;' : '';
				$add_form_select      .= '<dd><ul data-id="existing_form_registration_val" style="' . $add_form_select_style . ' width: 362px;">';
				$add_form_select      .= "<li data-label='" . esc_html__( 'Select Form', 'armember-membership' ) . "' data-value=''>" . esc_html__( 'Select Form', 'armember-membership' ) . '</li>';
				?>
				<table class="form-table">
					<tr class="arm_form_list_header">
						<td></td>
						<td class="arm_form_id_col"><?php esc_html_e( 'Form ID', 'armember-membership' ); ?></td>
						<td class="arm_form_title_col"><?php esc_html_e( 'Form Name', 'armember-membership' ); ?></td>
						<td class="arm_form_shortcode_col"><?php esc_html_e( 'Shortcode', 'armember-membership' ); ?></td>
						<td class="arm_form_shortcode_col"><?php esc_html_e( 'Last Modified', 'armember-membership' ); ?></td>
						<td class="arm_form_action_col"><?php esc_html_e( 'Action', 'armember-membership' ); ?></td>
						<td></td>
					</tr>
					<?php if ( ! empty( $registration_forms ) ) : ?>
						<?php foreach ( $registration_forms as $_form ) : ?>
							<?php
							$_fid             = $_form['arm_form_id'];
							$add_form_select .= "<li data-label='" . strip_tags( stripslashes( esc_attr($_form['arm_form_label']) ) ) . "' data-value='" . esc_attr($_fid) . "' class='existing_form_li_" . $_fid . "'>" . strip_tags( stripslashes( $_form['arm_form_label'] ) ) . '</li>';
							?>
							<tr class="arm_form_tr_<?php echo intval($_fid); ?>">
								<td></td>
								<td class="arm_form_title_col"><?php echo intval($_fid); ?></td>
								<td class="arm_form_title_col"><a href="<?php echo esc_url(admin_url( 'admin.php?page=' . $arm_slugs->manage_forms . '&action=edit_form&form_id=' . $_fid )); //phpcs:ignore ?>" class="arm_get_form_link" data-form_id="<?php echo esc_attr($_fid); ?>"><?php echo strip_tags( stripslashes( $_form['arm_form_label'] ) ); //phpcs:ignore ?></a></td>
								<td class="arm_form_shortcode_col">
									<div class="arm_short_code_detail">
										<?php $shortCode = '[arm_form id="' . $_fid . '"]'; ?>
										<div class="arm_shortcode_text arm_form_shortcode_box">
											<span class="armCopyText"><?php echo esc_html( $shortCode ); ?></span>
											<span class="arm_click_to_copy_text" data-code="<?php echo esc_attr( $shortCode ); ?>"><?php esc_html_e( 'Click to copy', 'armember-membership' ); ?></span>
											<span class="arm_copied_text"><img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); //phpcs:ignore ?>/copied_ok.png" alt="ok"/><?php esc_html_e( 'Code Copied', 'armember-membership' ); ?></span>
										</div>
									</div>
								</td>
								<td class="arm_form_date_col">( <?php echo date_i18n( $date_format, strtotime( $_form['arm_form_updated_date'] ) ); //phpcs:ignore ?> )</td>
								<td class="arm_form_action_col">
									<div class="arm_form_action_btns arm_reg_form_action_btns">
										<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $arm_slugs->manage_forms . '&action=edit_form&form_id=' . $_fid ) ); //phpcs:ignore ?>" class="arm_get_form_link" data-form_id="<?php echo esc_attr($_fid); //phpcs:ignore ?>">
											<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); //phpcs:ignore ?>/edit_icon.png" onmouseover="this.src='<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); ?>/edit_icon_hover.png';" class="armhelptip" title="<?php esc_html_e( 'Edit Form', 'armember-membership' ); ?>" onmouseout="this.src='<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); ?>/edit_icon.png';" /> <?php //phpcs:ignore ?>
										</a>
										<?php if ( $_form['arm_is_default'] != '1' ) : ?>
											<a href="javascript:void(0)" class="arm_delete_form_link" onclick="showConfirmBoxCallback(<?php echo esc_attr($_fid); //phpcs:ignore ?>);" data-form_id="<?php echo esc_attr($_fid); ?>">
												<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); ?>/delete.png" class="armhelptip" title="<?php esc_html_e( 'Delete Form', 'armember-membership' ); ?>" onmouseover="this.src='<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); ?>/delete_hover.png';" onmouseout="this.src='<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); ?>/delete.png';" style='cursor:pointer'/> <?php //phpcs:ignore ?>
											</a>
											<?php
											$formDeleteHtml  = esc_html__( 'Are you sure you want to delete this form?', 'armember-membership' );
											$formDeleteHtml .= '<label>';
											$formDeleteHtml .= '<input type="checkbox" class="arm_icheckbox arm_form_field_chk_' . esc_attr($_fid) . '" value="1">';
											$formDeleteHtml .= '<span>' . esc_html__( 'Delete fields of this specific form.', 'armember-membership' ) . '</span>';
											$formDeleteHtml .= '</label>';
											$formDeleteHtml .= '<span class="armnote"><em>(' . esc_html__( 'Fields those which are used somewhere else, will not be deleted.', 'armember-membership' ) . ')</em></span>';
											echo $arm_global_settings->arm_get_confirm_box( $_fid, $formDeleteHtml, 'arm_delete_form_confirm_ok' ); //phpcs:ignore
											?>
										<?php endif; ?>
									</div>
								</td>
								<td></td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php $add_form_select .= '</ul></dd></dl>'; ?>
				</table>
				</div>
				<div class="armclear"></div>
				<!-- ********************************/.Other Forms./******************************** -->
				<div class="arm_form_heading">
					<span><?php esc_html_e( 'Other Forms (Login / Forgot Password / Change Password)', 'armember-membership' ); ?></span>
					
					<div class="armclear"></div>
				</div>
				<div class="armclear"></div>
				<div class="arm_form_list_container arm_form_set_list_container">
				<?php $otherForms = $arm_member_forms->arm_get_member_form_sets(); ?>
				<table class="form-table">
					<tr class="arm_form_list_header">
						<td></td>
						<td class="arm_form_id_col"><?php esc_html_e( 'Set ID', 'armember-membership' ); ?></td>
						<td class="arm_form_title_col"><?php esc_html_e( 'Set Name', 'armember-membership' ); ?></td>
						<td class="arm_form_shortcode_col"><?php esc_html_e( 'Shortcode', 'armember-membership' ); ?></td>
						<td class="arm_form_action_col"><?php esc_html_e( 'Action', 'armember-membership' ); ?></td>
						<td></td>
					</tr>
					<?php if ( ! empty( $otherForms ) ) : ?>
						<?php foreach ( $otherForms as $setID => $formSet ) : ?>
							<?php if ( ! empty( $formSet ) ) : ?>
								<?php
								$formSetValues = array_values( $formSet );
								$firstForm     = array_shift( $formSetValues );
								reset( $formSet );
								?>
						<tr class="arm_form_set_tr_<?php echo intval($setID); ?>">
							<td></td>
							<td class="arm_form_id_col">
								<?php echo intval($firstForm['arm_form_id']); ?>
							</td>
							<td class="arm_form_title_col">
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $arm_slugs->manage_forms . '&action=edit_form&form_id=' . $firstForm['arm_form_id'] ) ); //phpcs:ignore ?>" class="arm_get_form_link" data-form_id="<?php echo esc_attr($firstForm['arm_form_id']); ?>"><?php echo stripslashes( $firstForm['arm_set_name'] ); //phpcs:ignore ?></a>
								<span class="arm_form_date_col">( <?php echo date_i18n( $date_format, strtotime( $firstForm['arm_form_updated_date'] ) ); //phpcs:ignore ?> )</span>
							</td>
							<td class="arm_form_shortcode_col">
								<ul>
									<?php foreach ( $formSet as $_form ) : ?>
									<li>
										<h4>
										<?php
										if ( $_form['arm_form_type'] == 'login' ) {
											esc_html_e( 'Login', 'armember-membership' );
										} elseif ( $_form['arm_form_type'] == 'forgot_password' ) {
											esc_html_e( 'Forgot Password', 'armember-membership' );
										} elseif ( $_form['arm_form_type'] == 'change_password' ) {
											esc_html_e( 'Change Password', 'armember-membership' );
										}
										?>
										</h4>
										<div class="arm_short_code_detail">
											<?php $shortCode = '[arm_form id="' . $_form['arm_form_id'] . '"]'; ?>
											<div class="arm_shortcode_text arm_form_shortcode_box">
												<span class="armCopyText"><?php echo esc_html($shortCode); ?></span>
												<span class="arm_click_to_copy_text" data-code="<?php echo esc_attr( $shortCode ); ?>"><?php esc_html_e( 'Click to copy', 'armember-membership' ); ?></span>
												<span class="arm_copied_text"><img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); //phpcs:ignore ?>/copied_ok.png" alt="ok"/><?php esc_html_e( 'Code Copied', 'armember-membership' ); ?></span>
											</div>
										</div>
									</li>
									<?php endforeach; ?>
								</ul>
							</td>
							<td class="arm_form_action_col">
								<div class="arm_form_action_btns">
									
									<a href="<?php echo esc_url(admin_url( 'admin.php?page=' . $arm_slugs->manage_forms . '&action=edit_form&form_id=' . $firstForm['arm_form_id'] ) ); ?>" class="arm_get_form_link" data-form_id="<?php echo esc_attr($_fid); ?>"><img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); ?>/edit_icon.png" onmouseover="this.src='<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); ?>/edit_icon_hover.png';" class="armhelptip" title="<?php esc_html_e( 'Edit Form', 'armember-membership' ); ?>" onmouseout="this.src='<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); ?>/edit_icon.png';" /></a><?php //phpcs:ignore ?>
									<?php if ( $firstForm['arm_is_default'] != '1' ) : ?>
									<a href="javascript:void(0)" class="arm_delete_set_link" onclick="showConfirmBoxCallback('<?php echo 'set_' . intval($setID); ?>');"  data-set_id="<?php echo intval($setID); ?>">
										<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); ?>/delete.png" class="armhelptip" title="<?php esc_html_e( 'Delete Form Set', 'armember-membership' ); ?>" onmouseover="this.src='<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); ?>/delete_hover.png';" onmouseout="this.src='<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); ?>/delete.png';" style='cursor:pointer'/> <?php //phpcs:ignore ?>
									</a>
										<?php
										echo $arm_global_settings->arm_get_confirm_box( 'set_' . $setID, esc_html__( 'Are you sure you want to delete this form set?', 'armember-membership' ), 'arm_delete_form_set_confirm_ok' ); //phpcs:ignore
										?>
									<?php endif; ?>
								</div>
							</td>
							<td></td>
						</tr>
						<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</table>
				</div>
				<!-- ********************************/.Additional Shortcodes./******************************** -->
				<div class="arm_form_heading">
					<span><?php esc_html_e( 'Additional Shortcodes', 'armember-membership' ); ?></span>
					<div class="armclear"></div>
				</div>
				<div class="armclear"></div>
				<div class="arm_form_list_container arm_form_additional_shortcodes">
					<table class="form-table">
						<tr>
							<td></td>
							<td class="arm_form_title_col"><?php esc_html_e( 'Edit Profile', 'armember-membership' ); ?></td>
							<td class="arm_form_shortcode_col" colspan="2">
								<div class="arm_short_code_detail">
								<span class="arm_shortcode_title"><?php esc_html_e( 'Short Code', 'armember-membership' ); ?>&nbsp;&nbsp;</span>
									<div class="arm_shortcode_text arm_form_shortcode_box">
										<?php
										$arm_default_signup_form_label = $arm_member_forms->arm_get_default_form_label( 'registration' );
										$edit_profile_code             = '[arm_edit_profile title="' . esc_html__( 'Edit Profile', 'armember-membership' ) . '" form_id="101" form_position="center" social_fields="facebook,twitter,linkedin" submit_text="' . esc_html__( 'Update Profile', 'armember-membership' ) . '" message="' . esc_html__( 'Your profile has been updated successfully.', 'armember-membership' ) . '" view_profile="true" view_profile_link="' . esc_html__( 'View Profile', 'armember-membership' ) . '"]';
										?>
										<span class="armCopyText"><?php echo esc_attr( $edit_profile_code ); ?></span>
										<span class="arm_click_to_copy_text" data-code="<?php echo esc_attr( $edit_profile_code ); ?>"><?php esc_html_e( 'Click to copy', 'armember-membership' ); ?></span>
										<span class="arm_copied_text"><img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); //phpcs:ignore ?>/copied_ok.png" alt="ok"/><?php esc_html_e( 'Code Copied', 'armember-membership' ); ?></span>
									</div>
								</div>
							</td>
							<td>
								<ul>
									<li><strong><?php esc_html_e( 'Possible Arguments :', 'armember-membership' ); ?></strong></li>
									<li>title="<?php esc_html_e( 'Edit Profile', 'armember-membership' ); ?>"</li>
									<li>form_id="101"</li>
									<li><small><i><?php esc_html_e( 'In form_id pass id of registration form of which styling and fields you want to inherit in Edit Profile Form.', 'armember-membership' ); ?></i></small></li>
									<li>submit_text="<?php esc_html_e( 'Update Profile', 'armember-membership' ); ?>"</li>
									<li>message="<?php esc_html_e( 'Your profile has been updated successfully.', 'armember-membership' ); ?>"</li>
									<li>view_profile="true"</li>
									<li>view_profile_link="<?php esc_html_e( 'View Profile', 'armember-membership' ); ?>"</li>
									<li>social_fields="facebook,twitter,linkedin"</li>
									<li><small><i><?php echo esc_html__( 'In social_fields, pass coma seperated social networks name (facebook, twitter,linkedin, vk, instagram,  pinterest,youtube, dribbble, delicious, tumblr, vine).', 'armember-membership' ); ?></i></small></li>
								</ul>
							</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td class="arm_form_title_col"><?php esc_html_e( 'Logout', 'armember-membership' ); ?></td>
							<td class="arm_form_shortcode_col" colspan="2">
								<div class="arm_short_code_detail">
									<span class="arm_shortcode_title"><?php esc_html_e( 'Short Code', 'armember-membership' ); ?>&nbsp;&nbsp;</span>
									<div class="arm_shortcode_text arm_form_shortcode_box">
										<?php $logout_code = '[arm_logout label="' . esc_html__( 'Logout', 'armember-membership' ) . '" type="button"]'; ?>
										<span class="armCopyText"><?php echo esc_attr( $logout_code ); ?></span>
										<span class="arm_click_to_copy_text" data-code="<?php echo esc_attr( $logout_code ); ?>"><?php esc_html_e( 'Click to copy', 'armember-membership' ); ?></span>
										<span class="arm_copied_text"><img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); //phpcs:ignore ?>/copied_ok.png" alt="ok"/><?php esc_html_e( 'Code Copied', 'armember-membership' ); ?></span>
									</div>
								</div>
							</td>
							<td>
								<ul>
									<li><strong><?php esc_html_e( 'Possible Arguments :', 'armember-membership' ); ?></strong></li>
									<li>label="<?php esc_html_e( 'Logout', 'armember-membership' ); ?>"</li>
									<li>type="link"</li>
									<li>user_info="true"</li>
									<li>redirect_to="<?php echo ARMLITE_HOME_URL; //phpcs:ignore ?>"</li>
									<li>link_css="color: #000000;"</li>
									<li>link_hover_css="color: #ffffff;"</li>
								</ul>
							</td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td class="arm_form_title_col"><?php esc_html_e( 'Close Account', 'armember-membership' ); ?></td>
							<td class="arm_form_shortcode_col" colspan="2">
								<div class="arm_short_code_detail">
									<span class="arm_shortcode_title"><?php esc_html_e( 'Short Code', 'armember-membership' ); ?>&nbsp;&nbsp;</span>
									<div class="arm_shortcode_text arm_form_shortcode_box">
										<?php $close_account_code = '[arm_close_account set_id="102"]'; ?>
										<span class="armCopyText"><?php echo esc_attr( $close_account_code ); ?></span>
										<span class="arm_click_to_copy_text" data-code="<?php echo esc_attr( $close_account_code ); ?>"><?php esc_html_e( 'Click to copy', 'armember-membership' ); ?></span>
										<span class="arm_copied_text"><img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL); //phpcs:ignore ?>/copied_ok.png" alt="ok"/><?php esc_html_e( 'Code Copied', 'armember-membership' ); ?></span>
									</div>
								</div>
							</td>
							<td>
								<ul>
									<li><strong><?php esc_html_e( 'Possible Arguments :', 'armember-membership' ); ?></strong></li>
									<li>set_id="102"</li>
									<li><?php esc_html_e( 'This set_id is id of set of form created for Login, Forgot Password, Change Password forms. And according to that set, Close account form styling will be set.', 'armember-membership' ); ?></li>
								</ul>
							</td>
							<td></td>
						</tr>
						
					</table>
				</div>
				<div class="armclear"></div>
			</div>
		</div>
		<div class="armclear"></div>
	</div>
</div>
<!--./******************** Add New Member Form ********************/.-->
<div class="add_new_form_wrapper popup_wrapper">
	<form method="post" id="form_arm_add_new_reg_form" class="arm_admin_form">
		<table cellspacing="0">
			<tr class="popup_wrapper_inner">
				<td class="add_new_form_close_btn arm_popup_close_btn"></td>
				<td class="popup_header"><?php esc_html_e( 'Add new form', 'armember-membership' ); ?></td>
				<td class="popup_content_text">
					<div class="arm_message arm_error_message arm_add_new_form_error">
						<div class="arm_message_text"><?php esc_html_e( 'There is a error while adding form, Please try again.', 'armember-membership' ); ?></div>
					</div>
					<div class="arm_registration_popup_inner_content_wrapper arm_position_relative" style="min-height: 400px;">
					<table class="arm_table_label_on_top">
						<tr>
							<th><label><?php esc_html_e( 'Form Name', 'armember-membership' ); ?><span class="required_star">*</span></label></th>
							<td><input type="text" id="unique_form_name" name="arm_new_form[arm_form_label]" value="" required data-msg-required="<?php esc_html_e( 'Form name can not be left blank.', 'armember-membership' ); ?>" class="arm_width_422"></td>
						</tr>
						<tr>
							<th><label><?php esc_html_e( 'Form Fields', 'armember-membership' ); ?></label></th>
							<td>
								<div class="arm_form_existing_options">
									<label style="<?php echo ( is_rtl() ) ? 'margin-left: 15px;' : 'margin-right: 15px;'; ?>">
										<input type="radio" name="existing_type" value="form" class="arm_iradio add_new_form_existing_type" checked="checked">
										<?php echo ( is_rtl() ) ? '' : '&nbsp;'; ?><?php esc_html_e( 'Clone from existing forms', 'armember-membership' ); ?> (<?php esc_html_e( 'Recommend', 'armember-membership' ); ?>)<?php echo ( is_rtl() ) ? '&nbsp;' : ''; ?>
									</label>
									<div class="add_new_form_existing_options existing_type_form" style="margin:0 0 5px 0;">
										<?php echo $add_form_select; //phpcs:ignore ?>
									</div>
									<label style="<?php echo ( is_rtl() ) ? 'margin-left: 15px;' : 'margin-right: 15px;'; ?>">
										<input type="radio" name="existing_type" value="template" class="arm_iradio add_new_form_existing_type" />
										<?php echo ( is_rtl() ) ? '' : '&nbsp;'; ?> <?php esc_html_e( 'Select Template', 'armember-membership' ); ?><?php echo ( is_rtl() ) ? '&nbsp;' : ''; ?>
									</label>
									<div class="add_new_form_existing_options template_type_form" style="margin:0 0 5px 0;display:none;">
										<input id="template_form_registration_val" class="existing_form_select" type="hidden" value="" name="template_form_registration" style="display:none;" />
										<dl id="template_form_registration" class="arm_selectbox existing_form_select" style="display:inline-block">
											<dt><span><?php esc_html_e( 'Select Template', 'armember-membership' ); ?></span>
												<input type="text" class="arm_autocomplete" value="" style="display:none;" />
												<i class="armfa armfa-caret-down armfa-lg"></i>
											</dt>
											<dd>
												<ul data-id="template_form_registration_val" style="<?php echo $add_form_select_style = ( is_rtl() ) ? 'margin-right: 35px! important;' : ''; ?>">
													<li data-value="" data-label="<?php esc_html_e( 'Select Template', 'armember-membership' ); ?>"><?php esc_html_e( 'Select Template', 'armember-membership' ); ?></li>
													<?php
														$registration_templates = $wpdb->get_results( $wpdb->prepare('SELECT * FROM ' . $ARMemberLite->tbl_arm_forms . " WHERE arm_is_template =%d AND arm_form_slug LIKE 'template-registration%' AND arm_form_type=%s ",1,'template') );//phpcs:ignore --Reason: $tbl_arm_forms is a table name. False Positive Alarm 
													foreach ( $registration_templates as $key => $template ) {
														?>
													<li data-value="<?php echo esc_attr($template->arm_form_id); //phpcs:ignore ?>" data-label="<?php echo esc_attr($template->arm_set_name); //phpcs:ignore ?>"><?php echo $template->arm_set_name; //phpcs:ignore ?></li>
														<?php
													}
													?>
												</ul>
											</dd>
										</dl>
										<label class="arm_template_form_registration_select_meta" >
											<input type="checkbox" name="arm_meta_fields_for_template" value="meta_fields" class="arm_iradio" id="select_arm_field_metas" />
											<?php echo ( is_rtl() ) ? '' : '&nbsp;'; ?><?php esc_html_e( 'Select meta fields', 'armember-membership' ); ?><?php echo ( is_rtl() ) ? '&nbsp;' : ''; ?>
										</label>
										<div class="existing_type_field hidden_section" id="arm_existing_type_fields" style="margin-left:60px;">
											<?php
											$metaFields = $arm_member_forms->arm_get_db_form_fields( true );

											if ( ! empty( $metaFields ) ) {
												foreach ( $metaFields as $_key => $_field ) {
													$fAttr = '';
													if ( in_array( $_key, array( 'user_email', 'user_login', 'first_name', 'last_name', 'user_pass' ) ) ) {
														$fAttr = 'checked="checked" disabled="disabled"';
													}

													echo '<div class="arm_add_new_form_field arm_field_' . esc_html($_key) . '">';
													echo '<label><input type="checkbox" class="arm_icheckbox" name="specific_fields[]" value="' . esc_html($_key) . '" ' . esc_html($fAttr) . '> ' . esc_html($_field['label']) . '</label>';
													echo '</div>';
												}
											}
											?>
											<input type="hidden" name="specific_fields[]" value="submit">
										</div>
									</div>
								</div>
							</td>
						</tr>
						
					</table>
					</div>
									<div class="arm_template_preview_wrapper arm_registration_templates" >
						<?php
						$reg_temp_id = 1;
						foreach ( $registration_templates as $key => $template ) {
							$arm_set_id = $template->arm_form_id;
							?>
							<div class="arm_image_register_placeholder_wrapper" data-template-set-id="<?php echo esc_attr($arm_set_id); ?>" data-set-id="<?php echo esc_attr($reg_temp_id); ?>">
								<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/form_templates/arm_signup_template_' . esc_attr($reg_temp_id) . '.png'; //phpcs:ignore ?>" />
							</div>
							<?php
							$reg_temp_id++;
						}
						?>
					</div>
				</td>
				<td class="popup_content_btn popup_footer">
					<div class="popup_content_btn_wrapper">
						<input type="hidden" name="arm_new_form[arm_form_type]" id="add_new_form_type" value="" />
						<button class="arm_submit_btn arm_add_new_form_submit_btn" type="submit"><?php esc_html_e( 'Add', 'armember-membership' ); ?></button>
						<button class="arm_cancel_btn add_new_form_close_btn" type="button"><?php esc_html_e( 'Cancel', 'armember-membership' ); ?></button>
					</div>
					<?php $wpnonce = wp_create_nonce( 'arm_wp_nonce' );?>
					<input type="hidden" name="arm_wp_nonce" value="<?php echo esc_attr($wpnonce);?>"/>
				</td>
			</tr>
		</table>
		<div class="armclear"></div>
	</form>
</div>
<!--./******************** Add New Other Member Forms ********************/.-->

<script type="text/javascript">
<?php if ( isset( $_REQUEST['setup'] ) && $_REQUEST['setup'] == 'true' ) : ?>
jQuery(window).on("load", function(){
	jQuery('.arm_add_new_form_btn').trigger('click');
});
<?php endif; ?>
jQuery(function($) {
	jQuery(document).on('click',".is_specific_field_input", function () {
		var form_type = jQuery('#add_new_form_type').val();
		var form_id = jQuery('#existing_form_'+form_type+'_val').val();
		jQuery('.existing_form_fields').slideUp('slow').addClass('hidden_section');
		if (jQuery(this).is(":checked")) {
			jQuery('.existing_form_fields_'+form_id).slideDown('slow').removeClass('hidden_section');
		}
	});
	jQuery(document).on('click',".new_form_action_type", function (e) {
		e.stopPropagation();
		var opt = jQuery(this).val();
		if(opt == 'page') {
					jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_redirect').slideUp();
					jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_referral').slideUp();
					jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_conditional_redirect').slideUp();    
					jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_page').slideDown();
					jQuery(this).parents('.arm_form_redirection_options').find('.login_form_action_option_conditional_redirect_info').slideUp();
					jQuery(this).parents('.arm_form_redirection_options').find('.login_form_action_option_referral_info').slideUp();
					jQuery(this).parents('.arm_form_redirection_options').find('.login_form_action_option_redirect_info').slideUp();
		} else if(opt == 'url') {
					jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_page').slideUp();
					jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_referral').slideUp();
					jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_conditional_redirect').slideUp();    
					jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_redirect').slideDown();
					jQuery(this).parents('.arm_form_redirection_options').find('.login_form_action_option_redirect_info').slideDown();
					jQuery(this).parents('.arm_form_redirection_options').find('.login_form_action_option_conditional_redirect_info').slideUp();
					jQuery(this).parents('.arm_form_redirection_options').find('.login_form_action_option_referral_info').slideUp();
		} else if(opt == 'referral' ){
					jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_page').slideUp();
					jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_redirect').slideUp();
					jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_conditional_redirect').slideUp();    
					jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_referral').slideDown();
					jQuery(this).parents('.arm_form_redirection_options').find('.login_form_action_option_referral_info').slideDown();
					jQuery(this).parents('.arm_form_redirection_options').find('.login_form_action_option_conditional_redirect_info').slideUp();
					jQuery(this).parents('.arm_form_redirection_options').find('.login_form_action_option_redirect_info').slideUp();
				}
				else if(opt == 'conditional_redirect')
				{
				   jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_page').slideUp();
				   jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_redirect').slideUp();
				   jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_referral').slideUp();
				   jQuery(this).parents('.arm_form_redirection_options').find('.add_new_form_conditional_redirect').slideDown();
				   jQuery(this).parents('.arm_form_redirection_options').find('.login_form_action_option_conditional_redirect_info').slideDown();
				   jQuery(this).parents('.arm_form_redirection_options').find('.login_form_action_option_referral_info').slideUp();
				   jQuery(this).parents('.arm_form_redirection_options').find('.login_form_action_option_redirect_info').slideUp();
				}
	});
	jQuery(".add_new_form_existing_type").click(function (e) {
		e.stopPropagation();
		var type = jQuery(this).val();
		if( type === 'form' ){
			jQuery('.add_new_form_existing_options.template_type_form').slideUp();
			jQuery('.add_new_form_existing_options.existing_type_form').slideDown();
		} else if (type === 'template') {
			jQuery('.add_new_form_existing_options.existing_type_form').slideUp();
			jQuery('.add_new_form_existing_options.template_type_form').slideDown();
		}
		
	});
});
jQuery(document).on('change','#select_arm_field_metas',function(e){
	if( jQuery(this).is(':checked') == true ){
		jQuery('#arm_existing_type_fields').slideDown();
	} else {
		jQuery('#arm_existing_type_fields').slideUp();
	}
});
</script>
<?php
	echo $ARMemberLite->arm_get_need_help_html_content('member-manage-forms'); //phpcs:ignore
?>