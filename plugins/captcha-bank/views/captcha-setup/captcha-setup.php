<?php
/**
 * This Template is used for managing captcha type settings.
 *
 * @author  Tech Banker
 * @package captcha-bank/views/captcha-setup
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
	} elseif ( CAPTCHA_SETTINGS_CAPTCHA_BANK === '1' ) {
		$captcha_type_update = wp_create_nonce( 'captcha_bank_file' );
		$border_style        = explode( ',', isset( $meta_data_array['border_style'] ) ? $meta_data_array['border_style'] : '' );
		$signature_style     = explode( ',', isset( $meta_data_array['signature_style'] ) ? $meta_data_array['signature_style'] : '' );
		$arithmetic_actions  = explode( ',', isset( $meta_data_array['arithmetic_actions'] ) ? $meta_data_array['arithmetic_actions'] : '' );
		$relational_actions  = explode( ',', isset( $meta_data_array['relational_actions'] ) ? $meta_data_array['relational_actions'] : '' );
		$arrange_order       = explode( ',', isset( $meta_data_array['arrange_order'] ) ? $meta_data_array['arrange_order'] : '' );
		$text_style          = explode( ',', isset( $meta_data_array['text_style'] ) ? $meta_data_array['text_style'] : '' );
		$display_setting     = explode( ',', isset( $meta_data_display_settings_array['settings'] ) ? $meta_data_display_settings_array['settings'] : '' );
		if ( class_exists( 'WooCommerce' ) ) {
			$version = captcha_bank_plugin_get_version( 'woocommerce/woocommerce.php' );
		}
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
				<a href="admin.php?page=captcha_bank">
					<?php echo esc_attr( $cpb_captcha_wizard_label ); ?>
				</a>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-layers"></i>
						<?php echo esc_attr( $cpb_captcha_wizard_label ); ?>
					</div>
					<p class="premium-editions">
						<?php echo esc_attr( $cpb_upgrade_need_help ); ?><a href="https://tech-banker.com/captcha-bank/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_documentation ); ?></a><?php echo esc_attr( $cpb_read_and_check ); ?><a href="https://tech-banker.com/captcha-bank/frontend-demos/" target="_blank" class="premium-editions-documentation"><?php echo esc_attr( $cpb_demos_section ); ?></a>
					</p>
				</div>
				<div class="portlet-body form">
					<div class="form-body">
						<div class="form-wizard" id="ux_div_frm_wizard">
							<ul class="nav nav-pills nav-justified steps">
								<li class="active">
									<a aria-expanded="true" href="javascript:void(0);" class="step">
										<span class="number"> 1 </span>
										<span class="desc"> <?php echo esc_attr( $cpb_captcha_type ); ?> </span>
									</a>
								</li>
								<li>
									<a href="javascript:void(0);" class="step">
										<span class="number"> 2 </span>
										<span class="desc"><?php echo esc_attr( $cpb_captcha_setting ); ?> </span>
									</a>
								</li>
								<li>
									<a href="javascript:void(0);" class="step">
										<span class="number"> 3 </span>
										<span class="desc"><?php echo esc_attr( $cpb_choose_form ); ?> </span>
									</a>
								</li>
								<li>
									<a href="javascript:void(0);" class="step">
										<span class="number"> 4 </span>
										<span class="desc"><?php echo esc_attr( $cpb_captcha_confirm ); ?> </span>
									</a>
								</li>
							</ul>
						</div>
						<div id="ux_div_step_progres_bar" class="progress progress-striped" role="progressbar">
							<div id="ux_div_step_progres_bar_width" style="width: 25%;" class="progress-bar progress-bar-success"></div>
						</div>
						<div class="line-separator"></div>
							<div class="tab-content" id="mailer_settings">
								<form id="ux_frm_captcha_type">
									<div id="ux_div_first_step">
										<div class="form-group">
											<label class="control-label">
												<?php echo esc_attr( $cpb_captcha_type ); ?> :
												<span class="required" aria-required="true">*</span>
											</label>
											<div class="row" style="margin-top: 10px;margin-bottom: 10px;">
												<div class="col-md-4">
													<input type="radio" name="ux_rdl_captcha_type" id="ux_rdl_captcha_type_recaptcha" value="recaptcha" <?php echo 'recaptcha' === $meta_data_array['captcha_type_text_logical'] ? 'checked=checked' : ''; ?> onclick="change_captcha_type_captcha_bank('recaptcha'), show_captcha_type_shortcode_captcha_bank();"><?php echo esc_attr( $cpb_captcha_bank_google_recaptcha ); ?>
												</div>
												<div class="col-md-4">
													<input type="radio" name="ux_rdl_captcha_type" id="ux_rdl_captcha_type_text_captcha" value="text_captcha" <?php echo 'text_captcha' === $meta_data_array['captcha_type_text_logical'] ? 'checked=checked' : ''; ?> onclick="change_captcha_type_captcha_bank('text_captcha'), show_captcha_type_shortcode_captcha_bank();"><?php echo esc_attr( $cpb_captcha_bank_text_captcha ); ?>
												</div>
												<div class="col-md-4">
													<input type="radio" name="ux_rdl_captcha_type" id="ux_rdl_captcha_type_logical_captcha" value="logical_captcha" <?php echo 'logical_captcha' === $meta_data_array['captcha_type_text_logical'] ? 'checked=checked' : ''; ?> onclick="change_captcha_type_captcha_bank('logical_captcha'), show_captcha_type_shortcode_captcha_bank();"><?php echo esc_attr( $cpb_captcha_bank_logical_captcha ); ?>
												</div>
											</div>
											<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_type_tooltip ); ?></i>
											<div class="line-separator"></div>
											<div class="form-actions">
												<div class="pull-right">
													<button class="btn vivid-green" name="ux_btn_next_step_second" id="ux_btn_next_step_second" onclick="captcha_bank_move_to_second_step();"><?php echo esc_attr( $cpb_next_step ); ?> >> </button>
												</div>
											</div>
										</div>
									</div>
									<div id="ux_div_second_step" style="display:none">
										<div id="ux_div_recaptcha">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															<?php echo esc_attr( $cpb_site_key ); ?> :
															<span class="required" aria-required="true">*</span>
														</label>
														<a href="https://www.google.com/recaptcha/" target="_blank" id="ux_link_reference" class="recaptcha-style"><span id="ux_link_content"> ( Get API Key </a> / <a class="recaptcha-style" href="https://tech-banker.com/how-to-add-google-recaptcha-api/" target="
															"> How to Setup? )</a></span>
														<input type="text" id="ux_txt_site_key" name="ux_txt_site_key" class="form-control" value="<?php echo isset( $meta_data_array['recaptcha_site_key'] ) ? esc_attr( $meta_data_array['recaptcha_site_key'] ) : ''; ?>" >
														<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_site_key_tooltip ); ?></i>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															<?php echo esc_attr( $cpb_secret_key ); ?> :
															<span class="required" aria-required="true">*</span>
														</label>
														<input type="text" id="ux_txt_secret_key" name="ux_txt_secret_key" class="form-control" value="<?php echo isset( $meta_data_array['recaptcha_secret_key'] ) ? esc_attr( $meta_data_array['recaptcha_secret_key'] ) : ''; ?>">
														<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_secret_key_tooltip ); ?></i>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6" id="ux_div_recaptcha_key_type">
													<div class="form-group">
														<label class="control-label">
															<?php echo esc_attr( $cpb_captcha_key_type ); ?> :
															<span class="required" aria-required="true">*</span>
														</label>
														<select name="ux_ddl_recaptcha_key_type" id="ux_ddl_recaptcha_key_type" class="form-control" onchange="show_hide_recaptcha_attribute_captcha_bank();">
															<option value="v3"><?php echo esc_attr( $cpb_recaptcha_v3 ); ?></option>
															<option value="v2"><?php echo esc_attr( $cpb_recaptcha_v2 ); ?></option>
															<option value="invisible"><?php echo esc_attr( $cpb_invisible_recaptcha ); ?></option>
														</select>
														<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_recaptcha_key_type_tooltip ); ?></i>
													</div>
												</div>
												<div class="col-md-6" id="ux_div_recaptcha_type">
													<div class="form-group">
														<label class="control-label">
															<?php echo esc_attr( $cpb_captcha_type ); ?> :
															<span class="required" aria-required="true">*</span>
														</label>
														<select name="ux_ddl_recaptcha_type" id="ux_ddl_recaptcha_type" class="form-control">
															<option value="image"><?php echo esc_attr( $cpb_image ); ?></option>
															<option value="audio"><?php echo esc_attr( $cpb_audio ); ?></option>
														</select>
														<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_recaptcha_type_tooltip ); ?></i>
													</div>
												</div>
											</div>
											<div class="row" id="ux_div_recaptcha_settings">
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															<?php echo esc_attr( $cpb_recaptcha_theme ); ?> :
															<span class="required" aria-required="true">*</span>
														</label>
														<select name="ux_ddl_recaptcha_theme_type" id="ux_ddl_recaptcha_theme_type" class="form-control">
															<option value="light"><?php echo esc_attr( $cpb_light ); ?></option>
															<option value="dark"><?php echo esc_attr( $cpb_dark ); ?></option>
														</select>
														<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_recaptcha_theme_tooltip ); ?></i>
													</div>
												</div>
												<div class="col-md-6" id="ux_data_size">
													<div class="form-group">
														<label class="control-label">
															<?php echo esc_attr( $cpb_recaptcha_size ); ?> :
															<span class="required" aria-required="true">*</span>
														</label>
														<select name="ux_ddl_recaptcha_size" id="ux_ddl_recaptcha_size" class="form-control">
															<option value="normal"><?php echo esc_attr( $cpb_normal ); ?></option>
															<option value="compact"><?php echo esc_attr( $cpb_compact ); ?></option>
														</select>
														<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_recaptcha_size_tooltip ); ?></i>
													</div>
												</div>
												<div class="col-md-6" id="ux_data_badge" style="display:none;">
													<div class="form-group">
														<label class="control-label">
															<?php echo esc_attr( $cpb_data_badge ); ?> :
															<span class="required" aria-required="true">*</span>
														</label>
														<select name="ux_ddl_recaptcha_data_badge" id="ux_ddl_recaptcha_data_badge" class="form-control">
															<option value="bottomright"><?php echo esc_attr( $cpb_bottom_right ); ?></option>
															<option value="bottomleft"><?php echo esc_attr( $cpb_bottom_left ); ?></option>
															<option value="inline"><?php echo esc_attr( $cpb_inline ); ?></option>
														</select>
														<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_data_badge_tooltip ); ?></i>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label">
													<?php echo esc_attr( $cpb_recaptcha_language ); ?> :
													<span style="color:red">* ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
												</label>
												<select name="ux_ddl_recaptcha_language" id="ux_ddl_recaptcha_language" class="form-control">
													<option value="ar" "="">Arabic</option>
													<option value="af" "="">Afrikaans</option>
													<option value="am" "="">Amharic</option>
													<option value="hy" "="">Armenian</option>
													<option value="az" "="">Azerbaijani</option>
													<option value="eu" "="">Basque</option>
													<option value="bn" "="">Bengali</option>
													<option value="bg" "="">Bulgarian</option>
													<option value="ca" "="">Catalan</option>
													<option value="zh-HK" "="">Chinese (Hong Kong)</option>
													<option value="zh-CN" "="">Chinese (Simplified)</option>
													<option value="zh-TW" "="">Chinese (Traditional)</option>
													<option value="hr" "="">Croatian</option>
													<option value="cs" "="">Czech</option>
													<option value="da" "="">Danish</option>
													<option value="nl" "="">Dutch</option>
													<option value="en-GB" "="">English (UK)</option>
													<option value="en" selected="selected" "="">English (US)</option>
													<option value="et" "="">Estonian</option>
													<option value="fil" "="">Filipino</option>
													<option value="fi" "="">Finnish</option>
													<option value="fr" "="">French</option>
													<option value="fr-CA" "="">French (Canadian)</option>
													<option value="gl" "="">Galician</option>
													<option value="ka" "="">Georgian</option>
													<option value="de" "="">German</option>
													<option value="de-AT" "="">German (Austria)</option>
													<option value="de-CH" "="">German (Switzerland)</option>
													<option value="el" "="">Greek</option>
													<option value="gu" "="">Gujarati</option>
													<option value="iw" "="">Hebrew</option>
													<option value="hi" "="">Hindi</option>
													<option value="hu" "="">Hungarain</option>
													<option value="is" "="">Icelandic</option>
													<option value="id" "="">Indonesian</option>
													<option value="it" "="">Italian</option>
													<option value="ja" "="">Japanese</option>
													<option value="kn" "="">Kannada</option>
													<option value="ko" "="">Korean</option>
													<option value="lo" "="">Laothian</option>
													<option value="lv" "="">Latvian</option>
													<option value="lt" "="">Lithuanian</option>
													<option value="ms" "="">Malay</option>
													<option value="ml" "="">Malayalam</option>
													<option value="mr" "="">Marathi</option>
													<option value="mn" "="">Mongolian</option>
													<option value="no" "="">Norwegian</option>
													<option value="fa" "="">Persian</option>
													<option value="pl" "="">Polish</option>
													<option value="pt" "="">Portuguese</option>
													<option value="pt-BR" "="">Portuguese (Brazil)</option>
													<option value="pt-PT" "="">Portuguese (Portugal)</option>
													<option value="ro" "="">Romanian</option>
													<option value="ru" "="">Russian</option>
													<option value="sr" "="">Serbian</option>
													<option value="si" "="">Sinhalese</option>
													<option value="sk" "="">Slovak</option>
													<option value="sl" "="">Slovenian</option>
													<option value="es" "="">Spanish</option>
													<option value="es-419" "="">Spanish (Latin America)</option>
													<option value="sw" "="">Swahili</option>
													<option value="sv" "="">Swedish</option>
													<option value="ta" "="">Tamil</option>
													<option value="te" "="">Telugu</option>
													<option value="th" "="">Thai</option>
													<option value="tr" "="">Turkish</option>
													<option value="uk" "="">Ukrainian</option>
													<option value="ur" "="">Urdu</option>
													<option value="vi" "="">Vietnamese</option>
													<option value="zu" "="">Zulu</option>
												</select>
												<i class="controls-description"><?php echo esc_attr( $cpb_recaptcha_language_tooltip ); ?></i>
											</div>
											<table style="margin-bottom:20px;">
												<tr>
													<td>
														<label class="control-label" style="margin-right: 30px;">
															<?php echo esc_attr( $cpb_website_is_behind_a_proxy ); ?> :
															<span style="color:red">* ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
														</label>
													</td>
													<td>
														<div style="margin-left: 64px;">
															<input id="ux_chk_proxy" type="checkbox" disabled='disabled' name="ux_chk_proxy" value="1" <?php echo isset( $meta_data_array['captcha_bank_behind_proxy'] ) && ( '1' === $meta_data_array['captcha_bank_behind_proxy'] ) ? 'checked=checked' : ''; ?> />
														</div>
													</td>
												</tr>
											</table>
										</div>
										<div id="ux_div_text_captcha" style="display:none;">
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
																		<?php echo esc_attr( $cpb_captcha_bank_character_title ); ?> :
																		<span class="required" aria-required="true">*</span>
																	</label>
																	<input type="text" class="form-control" name="ux_txt_character" maxlength="4" onfocus="paste_only_digits_captcha_bank(this.id);" id="ux_txt_character" value="<?php echo isset( $meta_data_array['captcha_characters'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['captcha_characters'] ) ) ) ) : '4'; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_bank_character_title ); ?>">
																	<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_character_tooltip ); ?></i>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group">
																	<label class="control-label">
																		<?php echo esc_attr( $cpb_captcha_bank_string_type_title ); ?> :
																		<span class="required" aria-required="true">*</span>
																	</label>
																	<select name="ux_ddl_alphabets" id="ux_ddl_alphabets" class="form-control">
																		<option value="alphabets_and_digits"><?php echo esc_attr( $cpb_captcha_bank_alphabets_digits ); ?></option>
																		<option value="only_alphabets"><?php echo esc_attr( $cpb_captcha_bank_only_alphabets ); ?></option>
																		<option value="only_digits"><?php echo esc_attr( $cpb_captcha_bank_only_digits ); ?></option>
																	</select>
																	<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_string_type_tooltip ); ?></i>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label class="control-label">
																		<?php echo esc_attr( $cpb_captcha_bank_text_case_title ); ?> :
																		<span class="required" aria-required="true">*</span>
																	</label>
																	<select name="ux_ddl_case" id="ux_ddl_case" class="form-control">
																		<option value="upper_case"><?php echo esc_attr( $cpb_captcha_bank_upper_case ); ?></option>
																		<option value="lower_case"><?php echo esc_attr( $cpb_captcha_bank_lower_case ); ?></option>
																		<option value="random"><?php echo esc_attr( $cpb_captcha_bank_random_case ); ?></option>
																	</select>
																	<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_text_case_tooltip ); ?></i>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group">
																	<label class="control-label">
																		<?php echo esc_attr( $cpb_captcha_bank_case_sensitive_title ); ?> :
																		<span class="required" aria-required="true">*</span>
																	</label>
																	<select name="ux_ddl_case_disable" id="ux_ddl_case_disable" class="form-control">
																		<option value="enable"><?php echo esc_attr( $cpb_enable ); ?></option>
																		<option value="disable"><?php echo esc_attr( $cpb_disable ); ?></option>
																	</select>
																	<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_case_sensitive_tooltip ); ?></i>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="control-label">
																<?php echo esc_attr( $cpb_captcha_bank_text_transparency_title ); ?> :
																<span class="required" aria-required="true">*</span>
															</label>
															<input type="text" class="form-control" name="ux_txt_transperancy" id="ux_txt_transperancy" maxlength="4" onfocus="paste_only_digits_captcha_bank(this.id);" value="<?php echo isset( $meta_data_array['text_transperancy'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['text_transperancy'] ) ) ) ) : ''; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_bank_text_transparency_placeholder ); ?>" onblur="check_value_captcha_bank('#ux_txt_transperancy');">
															<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_noise_level_tooltip ); ?></i>
													</div>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																<?php echo esc_attr( $cpb_captcha_bank_lines_title ); ?> :
																<span class="required" aria-required="true">*</span>
															</label>
															<input type="text" class="form-control" name="ux_txt_line" onfocus="paste_only_digits_captcha_bank(this.id);" id="ux_txt_line" maxlength="4" value="<?php echo isset( $meta_data_array['lines'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['lines'] ) ) ) ) : ''; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_bank_lines_title ); ?>" onblur="check_value_captcha_bank('#ux_txt_line');">
															<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_lines_tooltip ); ?></i>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<?php echo esc_attr( $cpb_captcha_bank_noise_level_title ); ?> :
																	<span class="required" aria-required="true">*</span>
																</label>
																<input type="text" class="form-control" name="ux_txt_noise_level" id="ux_txt_noise_level" maxlength="4" onfocus="paste_only_digits_captcha_bank(this.id);" value="<?php echo isset( $meta_data_array['noise_level'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['noise_level'] ) ) ) ) : ''; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_bank_noise_level_placeholder ); ?>" onblur="check_value_captcha_bank('#ux_txt_noise_level');">
																<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_noise_level_tooltip ); ?></i>
															</div>
														</div>
													</div>
												</div>
												<div class="tab-pane" id="layout_settings">
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<?php echo esc_attr( $cpb_captcha_bank_width_title ); ?> :
																	<span class="required" aria-required="true">*</span>
																</label>
																<input type="text" class="form-control" name="ux_txt_width" id="ux_txt_width" maxlength="4" onfocus="paste_only_digits_captcha_bank(this.id);" value="<?php echo isset( $meta_data_array['captcha_width'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['captcha_width'] ) ) ) ) : '180'; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_bank_width_title ); ?>">
																<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_width_tooltip ); ?></i>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<?php echo esc_attr( $cpb_captcha_bank_height_title ); ?> :
																	<span class="required" aria-required="true">*</span>
																</label>
																<input type="text" class="form-control" name="ux_txt_height" id="ux_txt_height" maxlength="4" onfocus="paste_only_digits_captcha_bank(this.id);" value="<?php echo isset( $meta_data_array['captcha_height'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['captcha_height'] ) ) ) ) : '60'; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_bank_height_title ); ?>">
																<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_height_tooltip ); ?></i>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<?php echo esc_attr( $cpb_captcha_bank_background_title ); ?> :
																	<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
																</label>
																<select name="ux_ddl_background" id="ux_ddl_background" class="form-control">
																	<option disabled="disabled" value="bg1.gif"><?php echo esc_attr( $cpb_captcha_bank_background_pattern1 ); ?></option>
																	<option disabled="disabled" value="bg2.gif"><?php echo esc_attr( $cpb_captcha_bank_background_pattern2 ); ?></option>
																	<option disabled="disabled" value="bg3.jpg"><?php echo esc_attr( $cpb_captcha_bank_background_pattern3 ); ?></option>
																	<option value="bg4.jpg"><?php echo esc_attr( $cpb_captcha_bank_background_pattern4 ); ?></option>
																	<option disabled="disabled" value="bg5.jpg"><?php echo esc_attr( $cpb_captcha_bank_background_pattern5 ); ?></option>
																	<option disabled="disabled" value="bg6.png"><?php echo esc_attr( $cpb_captcha_bank_background_pattern6 ); ?></option>
																	<option disabled="disabled" value="bg7.gif"><?php echo esc_attr( $cpb_captcha_bank_background_pattern7 ); ?></option>
																	<option disabled="disabled" value="bg8.gif"><?php echo esc_attr( $cpb_captcha_bank_background_pattern8 ); ?></option>
																	<option disabled="disabled" value="bg9.gif"><?php echo esc_attr( $cpb_captcha_bank_background_pattern9 ); ?></option>
																	<option disabled="disabled" value="bg10.gif"><?php echo esc_attr( $cpb_captcha_bank_background_pattern10 ); ?></option>
																	<option disabled="disabled" value="bg11.gif"><?php echo esc_attr( $cpb_captcha_bank_background_pattern11 ); ?></option>
																	<option disabled="disabled" value="bg12.gif"><?php echo esc_attr( $cpb_captcha_bank_background_pattern12 ); ?></option>
																	<option disabled="disabled" value="bg13.gif"><?php echo esc_attr( $cpb_captcha_bank_background_pattern13 ); ?></option>
																	<option disabled="disabled" value="bg14.gif"><?php echo esc_attr( $cpb_captcha_bank_background_pattern14 ); ?></option>
																	<option disabled="disabled" value="bg15.gif"><?php echo esc_attr( $cpb_captcha_bank_background_pattern15 ); ?></option>
																	<option disabled="disabled" value="bg16.gif"><?php echo esc_attr( $cpb_captcha_bank_background_pattern16 ); ?></option>
																	<option disabled="disabled" value="bg17.jpg"><?php echo esc_attr( $cpb_captcha_bank_background_pattern17 ); ?></option>
																	<option disabled="disabled" value="bg18.png"><?php echo esc_attr( $cpb_captcha_bank_background_pattern18 ); ?></option>
																</select>
																<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_background_tooltip ); ?></i>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<?php echo esc_attr( $cpb_captcha_bank_signature_font_title ); ?> :
																	<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
																</label>
																<select name="ux_ddl_text_font" id="ux_ddl_text_font" class="form-control">
																	<?php
																	if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'lib/web-fonts.php' ) ) {
																		include CAPTCHA_BANK_DIR_PATH . 'lib/web-fonts.php';
																	}
																	?>
																</select>
																<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_text_font_tooltip ); ?></i>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<?php echo esc_attr( $cpb_captcha_bank_text_style_title ); ?> :
																	<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
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
																			$disable = 24 === $flag ? '' : 'disabled=disabled';
																			?>
																			<option <?php echo esc_attr( $disable ); ?> value="<?php echo intval( $flag ); ?>"><?php echo intval( $flag ); ?> Px</option>
																			<?php
																		}
																	}
																	?>
																	</select>
																	<input type="text" name="ux_ddl_text_style[]" id="ux_ddl_text_color"  class="form-control custom-input-medium input-inline valid" onblur="check_color_captcha_bank('#ux_ddl_text_color')" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset( $meta_data_array['text_style'] ) ? esc_attr( $text_style[1] ) : '#cccccc'; ?>" placeholder="<?php echo esc_attr( $cpb_color_code ); ?>">
																	<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_text_style_tooltip ); ?></i>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<?php echo esc_attr( $cpb_captcha_bank_border_style_title ); ?> :
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
																		<option value="solid"><?php echo esc_attr( $cpb_captcha_bank_border_solid ); ?></option>
																		<option value="dotted" ><?php echo esc_attr( $cpb_captcha_bank_border_dotted ); ?></option>
																		<option value="dashed"><?php echo esc_attr( $cpb_captcha_bank_border_dashed ); ?></option>
																	</select>
																	<input type="text" name="ux_txt_border_style[]" id="ux_txt_border_text"  class="form-control input-normal input-inline" onblur="check_color_captcha_bank('#ux_txt_border_text')" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset( $meta_data_array['border_style'] ) ? esc_attr( $border_style[2] ) : '#cccccc'; ?>" placeholder="<?php echo esc_attr( $cpb_color_code ); ?>">
																	<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_border_style_tooltip ); ?></i>
																</div>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<?php echo esc_attr( $cpb_captcha_bank_lines_color_title ); ?> :
																	<span class="required" aria-required="true">*</span>
																</label>
																<input type="text" class="form-control" name="ux_txt_color" id="ux_txt_color" onblur="check_color_captcha_bank('#ux_txt_color');" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset( $meta_data_array['lines_color'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['lines_color'] ) ) ) ) : '#cc1f1f'; ?>" placeholder="<?php echo esc_attr( $cpb_color_code ); ?>">
																<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_lines_color_tooltip ); ?></i>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<?php echo esc_attr( $cpb_captcha_bank_noise_color_title ); ?> :
																	<span class="required" aria-required="true">*</span>
																</label>
																<input type="text" class="form-control" name="ux_txt_noise_color" id="ux_txt_noise_color" onblur="check_color_captcha_bank('#ux_txt_noise_color');" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset( $meta_data_array['noise_color'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['noise_color'] ) ) ) ) : '#cc1f1f'; ?>" placeholder="<?php echo esc_attr( $cpb_color_code ); ?>">
																<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_noise_color_tooltip ); ?></i>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="control-label">
															<?php echo esc_attr( $cpb_captcha_bank_shadow_color_title ); ?> :
															<span class="required" aria-required="true">*</span>
														</label>
														<input type="text" class="form-control" name="ux_txt_shadow_color" id="ux_txt_shadow_color" onblur="check_color_captcha_bank('#ux_txt_shadow_color');" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset( $meta_data_array['text_shadow_color'] ) ? esc_attr( stripslashes( htmlspecialchars_decode( urldecode( $meta_data_array['text_shadow_color'] ) ) ) ) : '#c722c7'; ?>" placeholder="<?php echo esc_attr( $cpb_color_code ); ?>">
														<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_shadow_color_tooltip ); ?></i>
													</div>
												</div>
												<div class="tab-pane" id="signature_settings">
													<div class="form-group">
														<label class="control-label">
															<?php echo esc_attr( $cpb_captcha_bank_signature_text_title ); ?> :
															<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
														</label>
														<input type="text" class="form-control" disabled='disabled' name="ux_txt_signature_text" id="ux_txt_signature_text" value="<?php echo isset( $meta_data_array['signature_text'] ) ? esc_attr( $meta_data_array['signature_text'] ) : ''; ?>" placeholder="<?php echo esc_attr( $cpb_captcha_bank_signature_text_title ); ?>">
														<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_signature_text_tooltip ); ?></i>
													</div>
													<div class="row">
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<?php echo esc_attr( $cpb_captcha_bank_signature_font_title ); ?> :
																	<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
																</label>
																<select name="ux_ddl_sign_font" id="ux_ddl_sign_font" class="form-control">
																	<?php
																	if ( file_exists( CAPTCHA_BANK_DIR_PATH . 'lib/web-fonts.php' ) ) {
																		include CAPTCHA_BANK_DIR_PATH . 'lib/web-fonts.php';
																	}
																	?>
																</select>
																<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_text_font_tooltip ); ?></i>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label class="control-label">
																	<?php echo esc_attr( $cpb_captcha_bank_text_style_title ); ?> :
																	<span class="required" aria-required="true">* <?php echo '( ' . esc_attr( $cpb_upgrade ) . ' )'; ?></span>
																</label>
																<div class="input-icon-custom right">
																	<select class="form-control custom-input-medium input-inline valid" id="ux_ddl_signature_style_value" name="ux_txt_signature_style[]">
																		<?php
																		for ( $flag = 0; $flag <= 99; $flag++ ) {
																			if ( $flag < 10 ) {
																				$disable_sign = 7 === $flag ? '' : 'disabled=disabled';
																				?>
																				<option <?php echo esc_attr( $disable_sign ); ?> value="<?php echo intval( $flag ); ?>">0<?php echo intval( $flag ); ?> Px</option>
																				<?php
																			} else {
																				?>
																				<option disabled="disabled" value="<?php echo intval( $flag ); ?>"><?php echo intval( $flag ); ?> Px</option>
																				<?php
																			}
																		}
																		?>
																	</select>
																	<input name="ux_txt_signature_style[]" disabled='disabled' id="ux_txt_style_text" type="text" class="form-control custom-input-medium input-inline valid" onblur="check_color_captcha_bank('#ux_txt_style_text');" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset( $meta_data_array['signature_style'] ) ? esc_attr( $signature_style[1] ) : '#cccccc'; ?>" placeholder="<?php echo esc_attr( $cpb_color_code ); ?>">
																	<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_text_style_tooltip ); ?></i>
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
											<?php echo esc_attr( $cpb_captcha_bank_mathematical_title ); ?> :
											<span class="required" aria-required="true">*</span>
										</label>
										<select class="form-control" id="ux_ddl_mathematical_operations" name="ux_ddl_mathematical_operations" onclick="change_mathematical_captcha_bank();">
											<option value="arithmetic" ><?php echo esc_attr( $cpb_captcha_bank_arithmetic ); ?></option>
											<option value="relational" style="color:red;"><?php echo esc_attr( $cpb_captcha_bank_relational ) . ' ( ' . esc_attr( $cpb_upgrade ) . ' ) '; ?></option>
											<option value="arrange_order" style="color:red;"><?php echo esc_attr( $cpb_captcha_bank_arrange_title ) . ' ( ' . esc_attr( $cpb_upgrade ) . ' ) '; ?></option>
										</select>
										<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_mathematical_tooltip ); ?></i>
									</div>
									<div id="ux_div_arithmetic_captcha" style="display:block;">
										<label class="control-label">
											<?php echo esc_attr( $cpb_captcha_bank_arithmetic_title ); ?> :
											<span class="required" aria-required="true">*</span>
										</label>
										<table class="table table-striped table-bordered table-margin-top" id="ux_tbl_arithmetic">
											<thead>
												<tr>
												<th class="control-label">
													<input type="checkbox" class="custom-chkbox-operation" name="ux_chk_arithmetic_action" id="ux_chk_addition_action" value="1" <?php echo isset( $arithmetic_actions['0'] ) && '1' === $arithmetic_actions['0'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_bank_addition ); ?>
												</th>
												<th class="control-label">
													<input type="checkbox" class="custom-chkbox-operation" name="ux_chk_arithmetic_action" id="ux_chk_subtraction_action" value="1" <?php echo isset( $arithmetic_actions['1'] ) && '1' === $arithmetic_actions['1'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_bank_subtraction ); ?>
												</th>
												<th class="control-label">
													<input type="checkbox" class="custom-chkbox-operation" name="ux_chk_arithmetic_action" id="ux_chk_multiplication_action" value="1" <?php echo isset( $arithmetic_actions['2'] ) && '1' === $arithmetic_actions['2'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_bank_multiplication ); ?>
												</th>
												<th class="control-label">
													<input type="checkbox" class="custom-chkbox-operation" name="ux_chk_arithmetic_action" id="ux_chk_division_action" value="1" <?php echo isset( $arithmetic_actions['3'] ) && '1' === $arithmetic_actions['3'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_bank_division ); ?>
												</th>
											</tr>
											</thead>
										</table>
										<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_arithmetic_tooltip ); ?>	</i>
									</div>
									<div id="ux_div_relational_captcha" style="display:none;">
										<label class="control-label">
											<?php echo esc_attr( $cpb_captcha_bank_relational_title ); ?> :
											<span class="required" aria-required="true">*</span>
										</label>
										<table class="table table-striped table-bordered table-margin-top" id="ux_tbl_relational">
											<thead>
												<tr>
													<th class="control-label">
														<input type="checkbox" class="form-control" name="ux_chk_relational_action" id="ux_chk_largest_action" value="1" <?php echo isset( $relational_actions['0'] ) && '1' === $relational_actions['0'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_bank_largest_number ); ?>
														<span style="color:red">( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
													</th>
													<th class="control-label">
														<input type="checkbox" class="form-control" name="ux_chk_relational_action" id="ux_chk_smallest_action" value="1" <?php echo isset( $relational_actions['1'] ) && '1' === $relational_actions['1'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_bank_smallest_number ); ?>
														<span style="color:red">( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
													</th>
												</tr>
											</thead>
										</table>
										<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_arithmetic_tooltip ); ?></i>
									</div>
									<div id="ux_div_arrange_captcha" style="display:none;">
										<label class="control-label">
											<?php echo esc_attr( $cpb_captcha_bank_arrange_title ); ?> :
											<span class="required" aria-required="true">*</span>
										</label>
										<table class="table table-striped table-bordered table-margin-top" id="ux_tbl_arrange">
											<thead>
												<tr>
													<th class="control-label">
														<input type="checkbox" class="form-control" name="ux_chk_arrange_action" id="ux_chk_arrange_action" value="1" <?php echo isset( $arrange_order['0'] ) && '1' === $arrange_order['0'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_bank_ascending_order ); ?>
														<span style="color:red">( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
													</th>
													<th class="control-label">
														<input type="checkbox" class="form-control" name="ux_chk_arrange_action" id="ux_chk_order_action" value="1" <?php echo isset( $arrange_order['1'] ) && '1' === $arrange_order['1'] ? 'checked=checked' : ''; ?>><?php echo esc_attr( $cpb_captcha_bank_descending_order ); ?>
														<span style="color:red">( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
													</th>
												</tr>
											</thead>
										</table>
										<i class="controls-description"><?php echo esc_attr( $cpb_captcha_bank_arithmetic_tooltip ); ?></i>
									</div>
								</div>
								<div class="line-separator"></div>
								<div class="form-actions">
									<div class="pull-left">
										<button type="button" class="btn vivid-green" name="ux_btn_previsious_step_first" id="ux_btn_previsious_step_first" onclick="captcha_bank_move_to_first_step()"> << <?php echo esc_attr( $cpb_previous_step ); ?></button>
									</div>
									<div class="pull-right">
										<button  class="btn vivid-green" name="ux_btn_next_step_third" id="ux_btn_next_step_third" onclick="captcha_bank_move_to_third_step();"><?php echo esc_attr( $cpb_next_step ); ?> >></button>
									</div>
								</div>
							</div>
							<div id="ux_div_third_step" style="display:none">
								<div class="form-body">
									<label class="control-label">
										<?php echo esc_attr( $cpb_display_settings_enable_captcha_for ); ?> :
										<span class="required" aria-required="true">*</span>
									</label>
									<table class="table table-striped table-bordered table-margin-top" id="ux_tbl_display_settings">
										<thead>
											<tr>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_login_form" value="1" <?php echo isset( $display_setting[0] ) && '1' === $display_setting[0] ? 'checked=checked' : ''; ?>  onclick="check_conditions_display_settings_captcha_bank('login');">
													<?php echo esc_attr( $cpb_display_settings_login_form ); ?>
												</th>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_captcha_bbpress_login" value="1" disabled='disabled'>
													<?php echo esc_attr( $cpb_display_settings_captcha_bbpress_login ); ?>
													<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
												</th>
											</tr>
											<tr>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_registration_form" value="1" <?php echo isset( $display_setting[2] ) && '1' === $display_setting[2] ? 'checked=checked' : ''; ?> onclick="check_conditions_display_settings_captcha_bank('register');">
													<?php echo esc_attr( $cpb_display_settings_registration_form ); ?>
												</th>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_captcha_bbpress_register" value="1" disabled='disabled'>
													<?php echo esc_attr( $cpb_display_settings_captcha_bbpress_register ); ?>
													<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
												</th>
											</tr>
											<tr>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_password_form" value="1" <?php echo isset( $display_setting[4] ) && '1' === $display_setting[4] ? 'checked=checked' : ''; ?> onclick="check_conditions_display_settings_captcha_bank('lost_password');">
													<?php echo esc_attr( $cpb_display_settings_reset_password_form ); ?>
												</th>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_captcha_bbpress_lost_password" value="1" <?php echo isset( $display_setting[5] ) && '1' === $display_setting[5] && ( class_exists( 'bbPress' ) ) ? 'checked=checked' : ''; ?> <?php echo ( class_exists( 'bbPress' ) ) ? '' : 'disabled=disabled'; ?>>
													<?php echo esc_attr( $cpb_display_settings_captcha_bbpress_lost_password ); ?>
												</th>
											</tr>
											<tr>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_comment_form" value="1" <?php echo isset( $display_setting[6] ) && '1' === $display_setting[6] ? 'checked=checked' : ''; ?>>
													<?php echo esc_attr( $cpb_display_settings_comment_form ); ?>
												</th>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_captcha_bbpress_new_topic" value="1" disabled='disabled'>
													<?php echo esc_attr( $cpb_display_settings_captcha_bbpress_new_topic ); ?>
													<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
												</th>
											</tr>
											<tr>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_admin_form" value="1" <?php echo isset( $display_setting[8] ) && '1' === $display_setting[8] ? 'checked=checked' : ''; ?>>
													<?php echo esc_attr( $cpb_display_settings_admin_comment_form ); ?>
												</th>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_captcha_bbpress_reply_topic" value="1" disabled='disabled'>
													<?php echo esc_attr( $cpb_display_settings_captcha_bbpress_reply_topic ); ?>
													<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
												</th>
											</tr>
											<tr>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_hide_captcha_for_user" value="1" disabled='disabled'>
													<?php echo esc_attr( $cpb_display_settings_hide_captcha_register_user ); ?>
													<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
												</th>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_captcha_buddypress" value="1" disabled='disabled'>
													<?php echo esc_attr( $cpb_display_settings_buddypress ); ?>
													<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
												</th>
											</tr>
											<tr>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_captcha_woocommerce_login" value="1" disabled='disabled'>
													<?php echo esc_attr( $cpb_display_settings_captcha_woocommerce_login ); ?>
													<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
												</th>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_captcha_woocommerce_register" value="1" disabled='disabled'>
													<?php echo esc_attr( $cpb_display_settings_captcha_woocommerce_register ); ?>
													<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
												</th>
											</tr>
											<tr>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_captcha_woocommerce_lost_password" value="1" disabled='disabled'>
													<?php echo esc_attr( $cpb_display_settings_captcha_woocommerce_lost_password ); ?>
													<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
												</th>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_captcha_woocommerce_checkout" value="1" disabled='disabled'>
													<?php echo esc_attr( $cpb_display_settings_captcha_woocommerce_checkout ); ?>
													<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
												</th>
											</tr>
											<tr>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_captcha_jetpack_form" value="1" disabled='disabled'>
													<?php echo esc_attr( $cpb_display_settings_captcha_jetpack_form ); ?>
													<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
												</th>
												<th class="control-label">
													<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_captcha_wpforo_login" value="1" disabled='disabled'>
													<?php echo esc_attr( $cpb_display_settings_captcha_wpforo_login ); ?>
													<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
												</th>
												<tr>
													<th class="control-label">
														<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_captcha_wpforo_register" value="1" disabled='disabled'>
														<?php echo esc_attr( $cpb_display_settings_captcha_wpforo_register ); ?>
														<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
													</th>
													<th class="control-label">
														<input type="checkbox" name="ux_chk_captcha_form" id="ux_chk_contact_form7" value="1" disabled='disabled'>
														<?php echo esc_attr( $cpb_display_settings_contact_form7 ); ?>
														<span style="color:red"> ( <?php echo esc_attr( $cpb_upgrade ); ?> )</span>
													</th>
												</tr>
											</thead>
										</table>
										<i class="controls-description"><?php echo esc_attr( $cpb_display_settings_enable_captcha_tooltip ); ?></i>
										<div class="line-separator"></div>
										<div class="form-actions">
											<div class="pull-left">
												<button type="button" class="btn vivid-green" name="ux_btn_previsious_step_second" id="ux_btn_previsious_step_second" onclick="captcha_bank_second_step_settings()"> << <?php echo esc_attr( $cpb_previous_step ); ?></button>
											</div>
											<div class="pull-right">
												<button  class="btn vivid-green" name="ux_btn_next_step_fourth" id="ux_btn_next_step_fourth" onclick="captcha_bank_move_to_fourth_step();"><?php echo esc_attr( $cpb_next_step ); ?> >></button>
											</div>
										</div>
									</div>
								</div>
								<div id="ux_div_fourth_step" style="display:none">
									<label class="control-label captcha-preview">
										<?php echo esc_attr( __( 'Captcha Live Preview', 'captcha-bank' ) ); ?> :
									</label>
									<div name="ux_div_captcha_preview" id="ux_div_captcha_preview" class="captcha-preview">
										<?php
										switch ( $meta_data_array['captcha_type_text_logical'] ) {
											case 'recaptcha':
												if ( '' !== $meta_data_array['recaptcha_site_key'] && '' !== $meta_data_array['recaptcha_secret_key'] ) {
													if ( 'v3' === $meta_data_array['recaptcha_key_type'] ) {
														echo '<script src="https://www.google.com/recaptcha/api.js?&hl=' . $meta_data_array['recaptcha_language'] . '&render=' . $meta_data_array['recaptcha_site_key'] . '" async></script>';// @codingStandardsIgnoreLine.
													} else {
														echo '<script src="https://www.google.com/recaptcha/api.js?explicit&hl=' . $meta_data_array['recaptcha_language'] . '" async></script>';// @codingStandardsIgnoreLine.
													}
												}
												if ( 'v2' === $meta_data_array['recaptcha_key_type'] ) {
													echo '<div class="g-recaptcha" id="recaptcha" data-sitekey="' . esc_attr( $meta_data_array['recaptcha_site_key'] ) . '" data-theme="' . esc_attr( $meta_data_array['recaptcha_theme'] ) . '" data-size="' . esc_attr( $meta_data_array['recaptcha_size'] ) . '" data-type="' . esc_attr( $meta_data_array['recaptcha_type'] ) . '" ></div>';
												} elseif ( 'invisible' === $meta_data_array['recaptcha_key_type'] ) {
													if ( 'bottomleft' === $meta_data_array['recaptcha_data_badge'] || 'bottomright' === $meta_data_array['recaptcha_data_badge'] ) {
														echo esc_attr( $cpb_live_preview_message );
													}
													echo '<div class="g-recaptcha" id="recaptcha" data-badge="' . esc_attr( $meta_data_array['recaptcha_data_badge'] ) . '" data-size="invisible" data-callback="onSubmit" data-sitekey="' . esc_attr( $meta_data_array['recaptcha_site_key'] ) . '" data-theme="' . esc_attr( $meta_data_array['recaptcha_theme'] ) . '" data-type="' . esc_attr( $meta_data_array['recaptcha_type'] ) . '" ></div>';
												} elseif ( 'v3' === $meta_data_array['recaptcha_key_type'] ) {
													echo esc_attr( $cpb_live_preview_message );
													echo '<div class="g-recaptcha" id="recaptcha" data-size="' . esc_attr( $meta_data_array['recaptcha_size'] ) . '" data-callback="onSubmit" data-sitekey="' . esc_attr( $meta_data_array['recaptcha_site_key'] ) . '" data-theme="' . esc_attr( $meta_data_array['recaptcha_theme'] ) . '" data-type="' . esc_attr( $meta_data_array['recaptcha_type'] ) . '" ></div>';
												}
												break;
											case 'text_captcha':
												global $captcha_array;
												$border_style = explode( ',', $captcha_array['border_style'] );
												$captcha_url  = admin_url( 'admin-ajax.php' ) . '?captcha_code=';
												?>
												<img src="<?php echo esc_attr( $captcha_url . rand( 111, 99999 ) ); ?>" class="captcha_code_img"  id="captcha_code_img" style= "margin-top:10px; cursor:pointer; border:<?php echo intval( $border_style[0] ); ?>px <?php echo esc_attr( $border_style[1] ); ?> <?php echo esc_attr( $border_style[2] ); ?>" />
												<img class="refresh-img" style = "cursor:pointer;margin-top:9px;vertical-align: top;" onclick="refresh();"  alt="Reload Image" height="16" width="16" src="<?php echo esc_attr( plugins_url( '/assets/global/img/refresh-icon.png', ( dirname( dirname( __FILE__ ) ) ) ) ); ?>"/>

												<script type="text/javascript">
													function refresh()
													{
														var randNum = Math.floor((Math.random() * 99999) + 1);
														jQuery("#captcha_code_img").attr("src", "<?php echo esc_attr( $captcha_url ); ?>" + randNum);
														return true;
													}
												</script>
												<?php
												break;
											case 'logical_captcha':
												global $captcha_bank_options, $captcha_time, $captcha_plugin_info, $wpdb, $captcha_array;
												$captcha_bank_ascending_order  = __( 'Arrange in Ascending Order', 'captcha-bank' );
												$captcha_bank_descending_order = __( 'Arrange in Descending Order', 'captcha-bank' );
												$captcha_bank_seperate_numbers = __( " (Use ',' to separate the numbers) :", 'captcha-bank' );
												$captcha_bank_larger_number    = __( 'Which Number is Larger ', 'captcha-bank' );
												$captcha_bank_smaller_number   = __( 'Which Number is Smaller ', 'captcha-bank' );
												$captcha_bank_arithemtic       = __( 'Solve', 'captcha-bank' );
												$captcha_bank_logical_or       = __( ' or ', 'captcha-bank' );

												if ( ! $captcha_plugin_info ) {
													include_once ABSPATH . 'wp-admin/includes/plugin.php';
													$captcha_plugin_info = get_plugin_data( __FILE__ );
												}
												if ( ! isset( $captcha_bank_options['captcha_key'] ) ) {
													$captcha_bank_options = get_option( 'captcha_option' );
												}
												if ( '' === $captcha_bank_options['captcha_key']['key'] || $captcha_bank_options['captcha_key']['time'] < CAPTCHA_BANK_LOCAL_TIME - ( 24 * 60 * 60 ) ) {
													captcha_bank_generate_key();
												}
												$str_key = $captcha_bank_options['captcha_key']['key'];
												if ( 'logical_captcha' === $captcha_array['captcha_type_text_logical'] && 'arrange_order' === $captcha_array['mathematical_operations'] ) {
													$arrange_order = explode( ',', isset( $captcha_array['arrange_order'] ) ? $captcha_array['arrange_order'] : '' );
													$arrange_array = captcha_bank_random_numbers( 10, 20, 5 );
													$copy_array    = $arrange_array;
													$arrange_type  = array();
													if ( '1' === $arrange_order[0] ) {
														$arrange_type[] = 'Ascending';
													}
													if ( '1' === $arrange_order[1] ) {
														$arrange_type[] = 'Descending';
													}
													$rand_arrange_array = rand( 0, count( $arrange_type ) - 1 );
													switch ( $arrange_type[ $rand_arrange_array ] ) {
														case 'Ascending':
															sort( $arrange_array );
															$arr_convert = implode( ',', $arrange_array );
															break;

														case 'Descending':
															rsort( $arrange_array );
															$arr_convert = implode( ',', $arrange_array );
															break;
													}
													$imploded_form          = implode( ',', $copy_array );
													$str_arrange_expretion  = '';
													$str_arrange_expretion .= ( 'Ascending' === $arrange_type[ $rand_arrange_array ] ) ? $captcha_bank_ascending_order : $captcha_bank_descending_order;
													$str_arrange_expretion .= '<br>' . __( " (Use ',' to separate the numbers) :", 'captcha-bank' ) . "<span style='color:red'>*</span><br><br>";
													$str_arrange_expretion .= $imploded_form . ' = ';
													$str_arrange_expretion .= '<input id=cptch_input class=cptch_input type=text autocomplete=off name=ux_txt_captcha_input size=10 aria-required=true style="margin-bottom:0;display:inline;font-size: 12px;width: 100px;" />';
													/* Add hidden field with encoding result */
													?>
													<input type="hidden" name="captcha_bank_result" value="<?php echo captcha_bank_encode( $arr_convert, $str_key, $captcha_time );// WPCS: XSS ok. ?>" />
													<input type="hidden" name="captcha_bank_time" value="<?php echo esc_attr( $captcha_time ); ?>" />
													<input type="hidden" value="Version: <?php echo esc_attr( $captcha_plugin_info['Version'] ); ?>" />
													<?php
													echo $str_arrange_expretion;// WPCS: XSS ok.
												} elseif ( 'logical_captcha' === $captcha_array['captcha_type_text_logical'] && 'relational' === $captcha_array['mathematical_operations'] ) {
													$relational_actions = explode( ',', isset( $captcha_array['relational_actions'] ) ? $captcha_array['relational_actions'] : '' );
													$relation_op        = array();
													if ( '1' === $relational_actions[0] ) {
														$relation_op[] = 'Larger';
													}
													if ( '1' === $relational_actions[1] ) {
														$relation_op[] = 'Smaller';
													}
													$rand_relation_op = rand( 0, count( $relation_op ) - 1 );
													$array_number     = array();
													$array_number[0]  = rand( 0, 9 );
													$array_number[1]  = rand( 0, 9 );
													while ( $array_number[0] === $array_number[1] ) {
														$array_number[0] = rand( 0, 9 );
													}
													switch ( $relation_op[ $rand_relation_op ] ) {
														case 'Smaller':
															if ( $array_number[0] < $array_number[1] ) {
																$array_number[2] = $array_number[0];
															} else {
																$array_number[2] = $array_number[1];
															}
															break;

														case 'Larger':
															if ( $array_number[0] > $array_number[1] ) {
																$array_number[2] = $array_number[0];
															} else {
																$array_number[2] = $array_number[1];
															}
															break;
													}
													$str_relational_expretion  = '';
													$str_relational_expretion .= $captcha_bank_arithemtic . " : <span style='color:red'>*</span><br>";
													$str_relational_expretion .= ( 'Smaller' === $relation_op[ $rand_relation_op ] ) ? $captcha_bank_smaller_number : $captcha_bank_larger_number;
													$str_relational_expretion .= $array_number[0] . ' ';
													$str_relational_expretion .= $captcha_bank_logical_or;
													$str_relational_expretion .= ' ' . $array_number[1] . ' ? ';
													$str_relational_expretion .= "<input id=cptch_input class=cptch_input type=text autocomplete=off name=ux_txt_captcha_input maxlength=2 size=2 onkeypress='validate_digits_frontend_captcha_bank(event)' aria-required=true style=\"display:inline;font-size: 12px;width: 40px;\" />";
													/* Add hidden field with encoding result */
													?>
													<input type="hidden" name="captcha_bank_result" value="<?php echo captcha_bank_encode( $array_number[2], $str_key, $captcha_time ); // WPCS: XSS ok. ?>" />
													<input type="hidden" name="captcha_bank_time" value="<?php echo esc_attr( $captcha_time ); ?>" />
													<input type="hidden" value="Version: <?php echo esc_attr( $captcha_plugin_info['Version'] ); ?>" />
													<?php
													echo $str_relational_expretion; // WPCS: XSS ok.
												} else {
													/* The array of math actions */
													$math_actions = array();
													$maths_action = $wpdb->get_var(
														$wpdb->prepare(
															'SELECT meta_value FROM ' . $wpdb->prefix . 'captcha_bank_meta WHERE meta_key = %s', 'captcha_type'
														)
													);// db call ok; no-cache ok.
													$maths_array  = maybe_unserialize( $maths_action );

													$arithmetic_actions = explode( ',', isset( $maths_array['arithmetic_actions'] ) ? $maths_array['arithmetic_actions'] : '' );
													/* If value for Plus on the settings page is set */
													if ( '1' === $arithmetic_actions[0] ) {
														$math_actions[] = '&#43;';
													}
													/* If value for Minus on the settings page is set */
													if ( '1' === $arithmetic_actions[1] ) {
														$math_actions[] = '&minus;';
													}
													/* If value for Increase on the settings page is set */
													if ( '1' === $arithmetic_actions[2] ) {
														$math_actions[] = '&times;';
													}
													/* if value for division on setting page is set */
													if ( '1' === $arithmetic_actions[3] ) {
														$math_actions[] = '&#8260;';
													}
													/* What is math action to display in the form */
													$rand_math_action = rand( 0, count( $math_actions ) - 1 );

													$array_math_expretion = array();
													/* Add first part of mathematical expression */
													$array_math_expretion[0] = rand( 1, 30 );
													/* Add second part of mathematical expression */
													$array_math_expretion[1] = rand( 1, 30 );
													/* Calculation of the mathematical expression result */
													switch ( $math_actions[ $rand_math_action ] ) {
														case '&#43;':
															$array_math_expretion[2] = $array_math_expretion[0] + $array_math_expretion[1];
															break;

														case '&minus;':
															/* Result must not be equal to the negative number */
															if ( $array_math_expretion[0] < $array_math_expretion[1] ) {
																$number                  = $array_math_expretion[0];
																$array_math_expretion[0] = $array_math_expretion[1];
																$array_math_expretion[1] = $number;
															}
															$array_math_expretion[2] = $array_math_expretion[0] - $array_math_expretion[1];
															break;

														case '&times;':
															$array_math_expretion[2] = $array_math_expretion[0] * $array_math_expretion[1];
															break;

														case '&#8260;':
															if ( $array_math_expretion[0] < $array_math_expretion[1] ) {
																$number                  = $array_math_expretion[0];
																$array_math_expretion[0] = $array_math_expretion[1];
																$array_math_expretion[1] = $number;
															}
															while ( 0 !== $array_math_expretion[0] % $array_math_expretion[1] ) {
																$array_math_expretion[0] ++;
															}
															$array_math_expretion[2] = $array_math_expretion[0] / $array_math_expretion[1];
															if ( is_float( $array_math_expretion[2] ) ) {
																$float_value             = round( $array_math_expretion[2], 1 );
																$devision                = explode( '.', $float_value );
																$array_math_expretion[2] = $devision[1] >= 5 ? ceil( $float_value ) : floor( $float_value );
															}
															break;
													}
													/* String for display */
													$str_math_expretion  = '';
													$str_math_expretion .= $captcha_bank_arithemtic . " : <span style='color:red'>*</span> <br>";
													$str_math_expretion .= $array_math_expretion[0];
													/* Add math action */
													$str_math_expretion .= ' ' . $math_actions[ $rand_math_action ];
													$str_math_expretion .= ' ' . $array_math_expretion[1];
													$str_math_expretion .= ' = ';
													$str_math_expretion .= ' <input id="cptch_input" class="cptch_input" type="text" autocomplete="off" name="ux_txt_captcha_input" value="" maxlength="5" size="2" aria-required="true" onkeypress="validate_digits_frontend_captcha_bank(event);"  style="margin-bottom:0;display:inline;font-size: 12px;width: 40px;" />';
													/* Add hidden field with encoding result */
													$str_math_expretion .= '<input type="hidden" name="captcha_bank_result" value="' . captcha_bank_encode( $array_math_expretion[2], $str_key, $captcha_time ) . '" />
													<input type="hidden" name="captcha_bank_time" value="' . $captcha_time . '" />
													<input type="hidden" value="Version: ' . $captcha_plugin_info['Version'] . '" />';
													echo $str_math_expretion;// WPCS: XSS ok.
												}
												break;
										}
										?>
								</div>
								<div class="line-separator"></div>
								<div class="form-actions">
									<div class="pull-left">
										<button type="button" class="btn vivid-green" name="ux_btn_previous_step_third" id="ux_btn_previous_step_third" onclick="captcha_bank_third_step_settings();">  << <?php echo esc_attr( $cpb_previous_step ); ?></button>
									</div>
									<div class="pull-right">
										<button type="button" class="btn vivid-green" name="ux_btn_save_changes" id="ux_btn_save_changes" onclick="captcha_bank_save_changes();" > <?php echo esc_attr( $cpb_save_changes ); ?></button>
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
				<a href="admin.php?page=captcha_bank">
					<?php echo esc_attr( $cpb_captcha_wizard_label ); ?>
				</a>
			</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box vivid-green">
				<div class="portlet-title">
					<div class="caption">
						<i class="icon-custom-layers"></i>
						<?php echo esc_attr( $cpb_captcha_wizard_label ); ?>
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
