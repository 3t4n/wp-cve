<?php
/**
 * This Template is used for managing captcha type settings.
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
		$captcha_type_update = wp_create_nonce( 'captcha_booster_file' );
		$border_style        = explode( ',', isset( $meta_data_array['border_style'] ) ? $meta_data_array['border_style'] : '' );
		$signature_style     = explode( ',', isset( $meta_data_array['signature_style'] ) ? $meta_data_array['signature_style'] : '' );
		$arithmetic_actions  = explode( ',', isset( $meta_data_array['arithmetic_actions'] ) ? $meta_data_array['arithmetic_actions'] : '' );
		$relational_actions  = explode( ',', isset( $meta_data_array['relational_actions'] ) ? $meta_data_array['relational_actions'] : '' );
		$arrange_order       = explode( ',', isset( $meta_data_array['arrange_order'] ) ? $meta_data_array['arrange_order'] : '' );
		$text_style          = explode( ',', isset( $meta_data_array['text_style'] ) ? $meta_data_array['text_style'] : '' );
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
					<?php echo esc_attr( $cpb_captcha_booster_type_breadcrumb ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-layers"></i>
						<?php echo esc_attr( $cpb_captcha_booster_type_breadcrumb ); ?>
					</div>
					<p class="premium-editions-booster">
						<a href="https://tech-banker.com/captcha-booster/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_full_features ); ?></a> <?php echo esc_attr( $cpb_or ); ?> <a href="https://tech-banker.com/captcha-booster/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_online_demos ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<form id="ux_frm_text_captcha">
						<div class="form-body">
							<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $cpb_captcha_booster_type_breadcrumb ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<select name="ux_ddl_captcha_type" id="ux_ddl_captcha_type" class="form-control" onchange="change_captcha_type_captcha_booster();">
									<option value="text_captcha"><?php echo esc_attr( $cpb_captcha_booster_text_captcha ); ?></option>
									<option value="logical_captcha" selected="selected"><?php echo esc_attr( $cpb_captcha_booster_logical_captcha ); ?></option>
								</select>
								<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_type_tooltip ); ?></i>
						</div>
						<div id="ux_div_text_captcha" style="display:none;">
							<div class="line-separator"></div>
							<div class="tabbable-custom">
								<ul class="nav nav-tabs ">
									<li class="active">
											<a aria-expanded="true" href="#general" data-toggle="tab">
												<?php echo esc_attr( $cpb_general_tab ); ?>
											</a>
											</li>
											<li>
												<a aria-expanded="false" href="#layout_settings" data-toggle="tab">
													<?php echo esc_attr( $cpb_layout_tab ); ?>
												</a>
											</li>
											<li>
												<a aria-expanded="false" href="#signature_settings" data-toggle="tab">
													<?php echo esc_attr( $cpb_signature_tab ); ?>
												</a>
											</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="general">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
													<label class="control-label">
														<?php echo esc_attr( $cpb_captcha_booster_character_title ); ?> :
														<span class="required" aria-required="true">*</span>
													</label>
													<input type="text" class="form-control" name="ux_txt_character" maxlength="4" onfocus="paste_only_digits_captcha_booster(this.id);" id="ux_txt_character" value="<?php echo isset( $meta_data_array['captcha_characters'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['captcha_characters'] ) ) ) ) : '4'; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_booster_character_title ); ?>">
													<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_character_tooltip ); ?></i>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">
														<?php echo esc_attr( $cpb_captcha_booster_string_type_title ); ?> :
														<span class="required" aria-required="true">*</span>
													</label>
													<select name="ux_ddl_alphabets" id="ux_ddl_alphabets" class="form-control">
														<option value="alphabets_and_digits"><?php echo esc_attr( $cpb_captcha_booster_alphabets_digits ); ?></option>
														<option value="only_alphabets"><?php echo esc_attr( $cpb_captcha_booster_only_alphabets ); ?></option>
														<option value="only_digits"><?php echo esc_attr( $cpb_captcha_booster_only_digits ); ?></option>
													</select>
													<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_string_type_tooltip ); ?></i>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">
														<?php echo esc_attr( $cpb_captcha_booster_text_case_title ); ?> :
														<span class="required" aria-required="true">*</span>
													</label>
													<select name="ux_ddl_case" id="ux_ddl_case" class="form-control">
														<option value="upper_case"><?php echo esc_attr( $cpb_captcha_booster_upper_case ); ?></option>
														<option value="lower_case"><?php echo esc_attr( $cpb_captcha_booster_lower_case ); ?></option>
														<option value="random"><?php echo esc_attr( $cpb_captcha_booster_random_case ); ?></option>
													</select>
													<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_text_case_tooltip ); ?></i>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">
														<?php echo esc_attr( $cpb_captcha_booster_case_sensitive_title ); ?> :
														<span class="required" aria-required="true">*</span>
													</label>
													<select name="ux_ddl_case_disable" id="ux_ddl_case_disable" class="form-control">
														<option value="enable"><?php echo esc_attr( $cpb_enable ); ?></option>
														<option value="disable"><?php echo esc_attr( $cpb_disable ); ?></option>
													</select>
													<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_case_sensitive_tooltip ); ?></i>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label">
												<?php echo esc_attr( $cpb_captcha_booster_text_transparency_title ); ?> :
												<span class="required" aria-required="true">*</span>
											</label>
											<input type="text" class="form-control" name="ux_txt_transperancy" id="ux_txt_transperancy" maxlength="4" onfocus="paste_only_digits_captcha_booster(this.id);" value="<?php echo isset( $meta_data_array['text_transperancy'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['text_transperancy'] ) ) ) ) : ''; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_booster_text_transparency_placeholder ); ?>" onblur="check_value_captcha_booster('#ux_txt_transperancy');">
											<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_noise_level_tooltip ); ?></i>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">
												<?php echo esc_attr( $cpb_captcha_booster_lines_title ); ?> :
												<span class="required" aria-required="true">*</span>
											</label>
											<input type="text" class="form-control" name="ux_txt_line" onfocus="paste_only_digits_captcha_booster(this.id);" id="ux_txt_line" maxlength="4" value="<?php echo isset( $meta_data_array['lines'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['lines'] ) ) ) ) : ''; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_booster_lines_title ); ?>" onblur="check_value_captcha_booster('#ux_txt_line');">
											<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_lines_tooltip ); ?></i>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="control-label">
													<?php echo esc_attr( $cpb_captcha_booster_noise_level_title ); ?> :
													<span class="required" aria-required="true">*</span>
												</label>
												<input type="text" class="form-control" name="ux_txt_noise_level" id="ux_txt_noise_level" maxlength="4" onfocus="paste_only_digits_captcha_booster(this.id);" value="<?php echo isset( $meta_data_array['noise_level'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['noise_level'] ) ) ) ) : ''; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_booster_noise_level_placeholder ); ?>" onblur="check_value_captcha_booster('#ux_txt_noise_level');">
												<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_noise_level_tooltip ); ?></i>
											</div>
										</div>
									</div>
							</div>
							<div class="tab-pane" id="layout_settings">
								<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpb_captcha_booster_width_title ); ?> :
										<span class="required" aria-required="true">*</span>
									</label>
									<input type="text" class="form-control" name="ux_txt_width" id="ux_txt_width" maxlength="4" onfocus="paste_only_digits_captcha_booster(this.id);" value="<?php echo isset( $meta_data_array['captcha_width'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['captcha_width'] ) ) ) ) : '180'; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_booster_width_title ); ?>">
									<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_width_tooltip ); ?></i>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpb_captcha_booster_height_title ); ?> :
										<span class="required" aria-required="true">*</span>
									</label>
									<input type="text" class="form-control" name="ux_txt_height" id="ux_txt_height" maxlength="4" onfocus="paste_only_digits_captcha_booster(this.id);" value="<?php echo isset( $meta_data_array['captcha_height'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['captcha_height'] ) ) ) ) : '60'; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_booster_height_title ); ?>">
									<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_height_tooltip ); ?></i>
								</div>
							</div>
						</div>
						<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">
								<?php echo esc_attr( $cpb_captcha_booster_background_title ); ?> :
								<span class="required" aria-required="true">* ( <?php echo esc_attr( $cpb_premium ); ?> )</span>
							</label>
							<select name="ux_ddl_background" id="ux_ddl_background" class="form-control">
								<option value="bg1.gif" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern1 ); ?></option>
								<option value="bg2.gif" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern2 ); ?></option>
								<option value="bg3.jpg" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern3 ); ?></option>
								<option value="bg4.jpg" selected="selected"><?php echo esc_attr( $cpb_captcha_booster_background_pattern4 ); ?></option>
								<option value="bg5.jpg" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern5 ); ?></option>
								<option value="bg6.png" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern6 ); ?></option>
								<option value="bg7.gif" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern7 ); ?></option>
								<option value="bg8.gif" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern8 ); ?></option>
								<option value="bg9.gif" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern9 ); ?></option>
								<option value="bg10.gif" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern10 ); ?></option>
								<option value="bg11.gif" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern11 ); ?></option>
								<option value="bg12.gif" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern12 ); ?></option>
								<option value="bg13.gif" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern13 ); ?></option>
								<option value="bg14.gif" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern14 ); ?></option>
								<option value="bg15.gif" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern15 ); ?></option>
								<option value="bg16.gif" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern16 ); ?></option>
								<option value="bg17.jpg" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern17 ); ?></option>
								<option value="bg18.png" disabled="disabled"><?php echo esc_attr( $cpb_captcha_booster_background_pattern18 ); ?></option>
							</select>
							<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_background_tooltip ); ?></i>
						</div>
					</div>
					<div class="col-md-6">
							<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $cpb_captcha_booster_text_font_title ); ?> :
									<span class="required" aria-required="true">* ( <?php echo esc_attr( $cpb_premium ); ?> )</span>
								</label>
								<select name="ux_ddl_text_font" id="ux_ddl_text_font" class="form-control">
									<?php
									if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'lib/web-fonts.php' ) ) {
										include CAPTCHA_BOOSTER_DIR_PATH . 'lib/web-fonts.php';
									}
									?>
								</select>
								<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_text_font_tooltip ); ?></i>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpb_captcha_booster_text_style_title ); ?> :
										<span class="required" aria-required="true">* ( <?php echo esc_attr( $cpb_premium ); ?> )</span>
									</label>
									<div class="input-icon-custom right">
										<select class="form-control custom-input-medium input-inline valid" id="ux_ddl_text_style_value" name="ux_ddl_text_style[]">
											<?php
											for ( $flag = 0; $flag <= 99; $flag++ ) {
												if ( $flag < 10 ) {
													?>
													<option disabled="disabled" value="<?php echo intval( $flag ); ?>">0<?php echo intval( $flag ); ?> Px</option>
													<?php
												} else {
													$disable = 20 === $flag ? '' : 'disabled=disabled';
													?>
													<option <?php echo esc_attr( $disable ); ?> value="<?php echo intval( $flag ); ?>"><?php echo intval( $flag ); ?> Px</option>
													<?php
												}
											}
											?>
										</select>
										<input type="text" name="ux_ddl_text_style[]" id="ux_ddl_text_color"  disabled='disabled' class="form-control custom-input-medium input-inline valid" onblur="check_color_captcha_booster('#ux_ddl_text_color')" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset( $meta_data_array['text_style'] ) ? esc_attr( $text_style[1] ) : '#cccccc'; ?>" placeholder="<?php echo esc_attr( $cpb_color_code ); ?>">
										<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_text_style_tooltip ); ?></i>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpb_captcha_booster_border_style_title ); ?> :
										<span class="required" aria-required="true">*</span>
									</label>
									<div class="input-icon-custom right">
										<select class="form-control input-width-25 input-inline" id="ux_ddl_border_style_value" name="ux_txt_border_style[]">
											<?php
											for ( $flag = 0; $flag <= 99; $flag++ ) {
												if ( $flag < 10 ) {
													?>
													<option value="<?php echo intval( $flag ); ?>">0<?php echo intval( $flag ); ?> Px</option>
													<?php
												} else {
													?>
													<option value="<?php echo intval( $flag ); ?>"><?php echo intval( $flag ); ?> Px</option>
													<?php
												}
											}
											?>
										</select>
										<select class="form-control input-width-27 input-inline" name="ux_txt_border_style[]" id="ux_ddl_border_style">
											<option value="solid"><?php echo esc_attr( $cpb_captcha_booster_border_solid ); ?></option>
											<option value="dotted" ><?php echo esc_attr( $cpb_captcha_booster_border_dotted ); ?></option>
											<option value="dashed"><?php echo esc_attr( $cpb_captcha_booster_border_dashed ); ?></option>
										</select>
										<input type="text" name="ux_txt_border_style[]" id="ux_txt_border_text"  class="form-control input-normal input-inline" onblur="check_color_captcha_booster('#ux_txt_border_text')" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset( $meta_data_array['border_style'] ) ? esc_attr( $border_style[2] ) : '#cccccc'; ?>" placeholder="<?php echo esc_attr( $cpb_color_code ); ?>">
										<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_border_style_tooltip ); ?></i>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">
											<?php echo esc_attr( $cpb_captcha_booster_lines_color_title ); ?> :
											<span class="required" aria-required="true">*</span>
										</label>
										<input type="text" class="form-control" name="ux_txt_color" id="ux_txt_color" onblur="check_color_captcha_booster('#ux_txt_color');" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset( $meta_data_array['lines_color'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['lines_color'] ) ) ) ) : '#cc1f1f'; ?>" placeholder="<?php echo esc_attr( $cpb_color_code ); ?>">
										<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_lines_color_tooltip ); ?></i>
									</div>
								</div>
							<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">
											<?php echo esc_attr( $cpb_captcha_booster_noise_color_title ); ?> :
											<span class="required" aria-required="true">*</span>
										</label>
										<input type="text" class="form-control" name="ux_txt_noise_color" id="ux_txt_noise_color" onblur="check_color_captcha_booster('#ux_txt_noise_color');" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset( $meta_data_array['noise_color'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['noise_color'] ) ) ) ) : '#cc1f1f'; ?>" placeholder="<?php echo esc_attr( $cpb_color_code ); ?>">
										<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_noise_color_tooltip ); ?></i>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">
									<?php echo esc_attr( $cpb_captcha_booster_shadow_color_title ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<input type="text" class="form-control" name="ux_txt_shadow_color" id="ux_txt_shadow_color" onblur="check_color_captcha_booster('#ux_txt_shadow_color');" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset( $meta_data_array['text_shadow_color'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['text_shadow_color'] ) ) ) ) : '#c722c7'; ?>" placeholder="<?php echo esc_attr( $cpb_color_code ); ?>">
								<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_shadow_color_tooltip ); ?></i>
								</div>
							</div>
						<div class="tab-pane" id="signature_settings">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpb_captcha_booster_signature_text_title ); ?> :
										<span class="required" aria-required="true">* ( <?php echo esc_attr( $cpb_premium ); ?> )</span>
									</label>
									<input type="text" class="form-control" name="ux_txt_signature_text" id="ux_txt_signature_text" disabled='disabled' value="<?php echo isset( $meta_data_array['signature_text'] ) ? esc_attr( $meta_data_array['signature_text'] ) : ''; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_booster_signature_text_title ); ?>">
									<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_signature_text_tooltip ); ?></i>
								</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label">
											<?php echo esc_attr( $cpb_captcha_booster_text_font_title ); ?> :
											<span class="required" aria-required="true">* ( <?php echo esc_attr( $cpb_premium ); ?> )</span>
										</label>
										<select name="ux_ddl_sign_font" id="ux_ddl_sign_font" class="form-control">
											<?php
											if ( file_exists( CAPTCHA_BOOSTER_DIR_PATH . 'lib/web-fonts.php' ) ) {
												include CAPTCHA_BOOSTER_DIR_PATH . 'lib/web-fonts.php';
											}
											?>
										</select>
										<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_text_font_tooltip ); ?></i>
									</div>
								</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">
										<?php echo esc_attr( $cpb_captcha_booster_text_style_title ); ?> :
										<span class="required" aria-required="true">* ( <?php echo esc_attr( $cpb_premium ); ?> )</span>
									</label>
									<div class="input-icon-custom right">
										<select class="form-control custom-input-medium input-inline valid" id="ux_ddl_signature_style_value" name="ux_txt_signature_style[]">
											<?php
											for ( $flag = 0; $flag <= 99; $flag++ ) {
												if ( $flag < 10 ) {
													$disable_sign = 8 === $flag ? '' : 'disabled=disabled';
													?>
													<option <?php echo esc_attr( $disable_sign ); ?> value="<?php echo intval( $flag ); ?>">0<?php echo intval( $flag ); ?> Px</option>
													<?php
												} else {
													?>
													<option disabled='disabled' value="<?php echo intval( $flag ); ?>"><?php echo intval( $flag ); ?> Px</option>
													<?php
												}
											}
											?>
										</select>
										<input name="ux_txt_signature_style[]" id="ux_txt_style_text" type="text" disabled='disabled' class="form-control custom-input-medium input-inline valid" onblur="check_color_captcha_booster('#ux_txt_style_text');" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset( $meta_data_array['signature_style'] ) ? esc_attr( $signature_style[1] ) : '#cccccc'; ?>" placeholder="<?php echo esc_attr( $cpb_color_code ); ?>">
										<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_text_style_tooltip ); ?></i>
									</div>
								</div>
							</div>
						</div>
							</div>
						</div>
					</div>
				</div>
					<div id="ux_div_logical_captcha" style="display:block;">
						<div class="form-group">
							<label class="control-label">
									<?php echo esc_attr( $cpb_captcha_booster_mathematical_title ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<select class="form-control" name="ux_rdl_mathematical_captcha" id="ux_rdl_mathematical_captcha" onchange="change_mathematical_captcha_booster();">
									<option value="arithmetic"><?php echo esc_attr( $cpb_captcha_booster_arithmetic ); ?></option>
									<option value="relational" style='color:red;'><?php echo esc_attr( $cpb_captcha_booster_relational ) . ' ( ' . esc_attr( $cpb_premium ) . ' ) '; ?></option>
									<option value="arrange_order" style='color:red;'><?php echo esc_attr( $cpb_captcha_booster_arrange_title ) . ' ( ' . esc_attr( $cpb_premium ) . ' ) '; ?></option>
								</select>
								<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_mathematical_tooltip ); ?></i>
							</div>
							<div id="ux_div_arithmetic_captcha" style="display:block;">
								<label class="control-label">
									<?php echo esc_attr( $cpb_captcha_booster_arithmetic_title ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<table class="table table-striped table-bordered table-margin-top" id="ux_tbl_arithmetic" style="margin-bottom: 0px !important;">
									<thead>
										<tr>
										<th class="control-label">
											<input type="checkbox" class="custom-chkbox-operation" name="ux_chk_arithmetic_action" id="ux_chk_addition_action" value="1" <?php echo isset( $arithmetic_actions['0'] ) && '1' === $arithmetic_actions['0'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_booster_addition ); ?>
										</th>
										<th class="control-label">
											<input type="checkbox" class="custom-chkbox-operation" name="ux_chk_arithmetic_action" id="ux_chk_subtraction_action" value="1" <?php echo isset( $arithmetic_actions['1'] ) && '1' === $arithmetic_actions['1'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_booster_subtraction ); ?>
										</th>
										<th class="control-label">
											<input type="checkbox" class="custom-chkbox-operation" name="ux_chk_arithmetic_action" id="ux_chk_multiplication_action" value="1" <?php echo isset( $arithmetic_actions['2'] ) && '1' === $arithmetic_actions['2'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_booster_multiplication ); ?>
										</th>
										<th class="control-label">
											<input type="checkbox" class="custom-chkbox-operation" name="ux_chk_arithmetic_action" id="ux_chk_division_action" value="1" <?php echo isset( $arithmetic_actions['3'] ) && '1' === $arithmetic_actions['3'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_booster_division ); ?>
										</th>
									</tr>
									</thead>
								</table>
							</div>
							<div id="ux_div_relational_captcha" style="display:none;">
								<label class="control-label">
									<?php echo esc_attr( $cpb_captcha_booster_relational_title ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<table class="table table-striped table-bordered table-margin-top" id="ux_tbl_relational" style="margin-bottom: 0px !important;">
									<thead>
										<tr>
										<th class="control-label">
											<input type="checkbox" class="form-control" name="ux_chk_relational_action" id="ux_chk_largest_action" value="1" <?php echo isset( $relational_actions['0'] ) && '1' === $relational_actions['0'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_booster_largest_number ); ?>
											<span style="color:red !important;">( <?php echo esc_attr( $cpb_premium ); ?> )</span>
										</th>
										<th class="control-label">
											<input type="checkbox" class="form-control" name="ux_chk_relational_action" id="ux_chk_smallest_action" value="1" <?php echo isset( $relational_actions['1'] ) && '1' === $relational_actions['1'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_booster_smallest_number ); ?>
											<span style="color:red !important;">( <?php echo esc_attr( $cpb_premium ); ?> )</span>
										</th>
									</tr>
									</thead>
								</table>
							</div>
							<div id="ux_div_arrange_captcha" style="display:none;">
								<label class="control-label">
									<?php echo esc_attr( $cpb_captcha_booster_arrange_title ); ?> :
									<span class="required" aria-required="true">*</span>
								</label>
								<table class="table table-striped table-bordered table-margin-top" id="ux_tbl_arrange" style="margin-bottom: 0px !important;">
									<thead>
										<tr>
										<th class="control-label">
											<input type="checkbox" class="form-control" name="ux_chk_arrange_action" id="ux_chk_arrange_action" value="1" <?php echo isset( $arrange_order['0'] ) && '1' === $arrange_order['0'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_booster_ascending_order ); ?>
											<span style="color:red !important;">( <?php echo esc_attr( $cpb_premium ); ?> )</span>
										</th>
										<th class="control-label">
											<input type="checkbox" class="form-control" name="ux_chk_arrange_action" id="ux_chk_order_action" value="1" <?php echo isset( $arrange_order['1'] ) && '1' === $arrange_order['1'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_booster_descending_order ); ?>
											<span style="color:red !important;">( <?php echo esc_attr( $cpb_premium ); ?> )</span>
										</th>
									</tr>
									</thead>
								</table>
							</div>
							<i class="controls-description"><?php echo esc_attr( $cpb_captcha_booster_arithmetic_tooltip ); ?></i>
						</div>
						<div class="line-separator"></div>
						<div class="form-actions">
							<div class="pull-right">
								<input type="submit" class="btn vivid-green" name="ux_btn_save_change" id="ux_btn_save_change" value="<?php echo esc_attr( $cpb_save_changes ); ?>">
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
					<?php echo esc_attr( $cpb_captcha_booster_type_breadcrumb ); ?>
				</span>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-layers"></i>
						<?php echo esc_attr( $cpb_captcha_booster_type_breadcrumb ); ?>
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
