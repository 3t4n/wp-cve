<?php
global $current_user, $arfliteformcontroller, $arformsmain, $arforms_general_settings;

$arforms_all_settings = $arformsmain->arforms_global_option_data();
$arflitesettings = json_decode( json_encode( $arforms_all_settings['general_settings'] ) );

$arflitesettings->arf_load_js_css = !empty( $arflitesettings->arf_load_js_css ) ? json_decode( $arflitesettings->arf_load_js_css, true ) : array();

$setting_tab = get_option( 'arforms_current_tab' );
$setting_tab = ( ! isset( $setting_tab ) || empty( $setting_tab ) ) ? 'general_settings' : $setting_tab;

if( !empty( $_GET['current_tab'] ) && 'status_settings' == $_GET['current_tab'] ){
	$setting_tab = sanitize_text_field( $_GET['current_tab'] );
}

$general_setting_tab_selection = ( 'general_settings' == $setting_tab ) ? 'btn_sld' : 'tab-unselected';
$autoresponder_tab_selection = ( $setting_tab == 'autoresponder_settings' ) ? 'btn_sld' : 'tab-unselected';
$log_tab_selection = ( $setting_tab == 'logs_settings' ) ? 'btn_sld' : 'tab-unselected';
$status_tab_selection = ( $setting_tab == 'status_settings' ) ? 'btn_sld' : 'tab-unselected';


$arforms_settings_page_url = admin_url( 'admin.php?page=ARForms-settings');
if( !empty( $_GET['arflite_settings_nonce'] ) ){
	$arforms_settings_page_url .= '&arflite_settings_nonce='.sanitize_text_field($_GET['arflite_settings_nonce']);
}

$show_success = false;

if( !empty( $_GET['is_success'] ) ){
	$show_success = true;
}

$hostname = isset( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field($_SERVER['SERVER_NAME']) : '';

function is_captcha_act() {
	if ( ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	return is_plugin_active( 'arformsgooglecaptcha/arformsgooglecaptcha.php' );
}

$sections = apply_filters( 'arfliteaddsettingssection', array() );
if( $arformsmain->arforms_is_pro_active() ){
	$sections = apply_filters('arfaddsettingssection', array());;
}

$google_recaptcha_theme = '';
$google_rclang			= '';
$selected_list_label   = '';

$captcha_theme = array(
	'light' => __( 'Light', 'arforms-form-builder' ),
	'dark'  => __( 'Dark', 'arforms-form-builder' ),
);
$rc_default_theme = 'light';

$rc_default_lang       = 'en';
$rc_default_lang_label = __( 'English (US)', 'arforms-form-builder' );
$rclang                = array();
$rclang['en']          = __( 'English (US)', 'arforms-form-builder' );
$rclang['ar']          = __( 'Arabic', 'arforms-form-builder' );
$rclang['bn']          = __( 'Bengali', 'arforms-form-builder' );
$rclang['bg']          = __( 'Bulgarian', 'arforms-form-builder' );
$rclang['ca']          = __( 'Catalan', 'arforms-form-builder' );
$rclang['zh-CN']       = __( 'Chinese(Simplified)', 'arforms-form-builder' );
$rclang['zh-TW']       = __( 'Chinese(Traditional)', 'arforms-form-builder' );
$rclang['hr']          = __( 'Croatian', 'arforms-form-builder' );
$rclang['cs']          = __( 'Czech', 'arforms-form-builder' );
$rclang['da']          = __( 'Danish', 'arforms-form-builder' );
$rclang['nl']          = __( 'Dutch', 'arforms-form-builder' );
$rclang['en-GB']       = __( 'English (UK)', 'arforms-form-builder' );
$rclang['et']          = __( 'Estonian', 'arforms-form-builder' );
$rclang['fil']         = __( 'Filipino', 'arforms-form-builder' );
$rclang['fi']          = __( 'Finnish', 'arforms-form-builder' );
$rclang['fr']          = __( 'French', 'arforms-form-builder' );
$rclang['fr-CA']       = __( 'French (Canadian)', 'arforms-form-builder' );
$rclang['de']          = __( 'German', 'arforms-form-builder' );
$rclang['gu']          = __( 'Gujarati', 'arforms-form-builder' );
$rclang['de-AT']       = __( 'German (Autstria)', 'arforms-form-builder' );
$rclang['de-CH']       = __( 'German (Switzerland)', 'arforms-form-builder' );
$rclang['el']          = __( 'Greek', 'arforms-form-builder' );
$rclang['iw']          = __( 'Hebrew', 'arforms-form-builder' );
$rclang['hi']          = __( 'Hindi', 'arforms-form-builder' );
$rclang['hu']          = __( 'Hungarian', 'arforms-form-builder' );
$rclang['id']          = __( 'Indonesian', 'arforms-form-builder' );
$rclang['it']          = __( 'Italian', 'arforms-form-builder' );
$rclang['ja']          = __( 'Japanese', 'arforms-form-builder' );
$rclang['kn']          = __( 'Kannada', 'arforms-form-builder' );
$rclang['ko']          = __( 'Korean', 'arforms-form-builder' );
$rclang['lv']          = __( 'Latvian', 'arforms-form-builder' );
$rclang['lt']          = __( 'Lithuanian', 'arforms-form-builder' );
$rclang['ms']          = __( 'Malay', 'arforms-form-builder' );
$rclang['ml']          = __( 'Malayalam', 'arforms-form-builder' );
$rclang['mr']          = __( 'Marathi', 'arforms-form-builder' );
$rclang['no']          = __( 'Norwegian', 'arforms-form-builder' );
$rclang['fa']          = __( 'Persian', 'arforms-form-builder' );
$rclang['pl']          = __( 'Polish', 'arforms-form-builder' );
$rclang['pt']          = __( 'Portuguese', 'arforms-form-builder' );
$rclang['pt-BR']       = __( 'Portuguese (Brazil)', 'arforms-form-builder' );
$rclang['pt-PT']       = __( 'Portuguese (Portugal)', 'arforms-form-builder' );
$rclang['ro']          = __( 'Romanian', 'arforms-form-builder' );
$rclang['ru']          = __( 'Russian', 'arforms-form-builder' );
$rclang['sr']          = __( 'Serbian', 'arforms-form-builder' );
$rclang['sk']          = __( 'Slovak', 'arforms-form-builder' );
$rclang['sl']          = __( 'Slovenian', 'arforms-form-builder' );
$rclang['es']          = __( 'Spanish', 'arforms-form-builder' );
$rclang['es-149']      = __( 'Spanish (Latin America)', 'arforms-form-builder' );
$rclang['sv']          = __( 'Swedish', 'arforms-form-builder' );
$rclang['ta']          = __( 'Tamil', 'arforms-form-builder' );
$rclang['te']          = __( 'Telugu', 'arforms-form-builder' );
$rclang['th']          = __( 'Thai', 'arforms-form-builder' );
$rclang['tr']          = __( 'Turkish', 'arforms-form-builder' );
$rclang['uk']          = __( 'Ukrainian', 'arforms-form-builder' );
$rclang['ur']          = __( 'Urdu', 'arforms-form-builder' );
$rclang['vi']          = __( 'Vietnamese', 'arforms-form-builder' );


$arf_character_arr = array(
	'latin'        => 'Latin',
	'latin-ext'    => 'Latin-ext',
	'menu'         => 'Menu',
	'greek'        => 'Greek',
	'greek-ext'    => 'Greek-ext',
	'cyrillic'     => 'Cyrillic',
	'cyrillic-ext' => 'Cyrillic-ext',
	'vietnamese'   => 'Vietnamese',
	'arabic'       => 'Arabic',
	'khmer'        => 'Khmer',
	'lao'          => 'Lao',
	'tamil'        => 'Tamil',
	'bengali'      => 'Bengali',
	'hindi'        => 'Hindi',
	'korean'       => 'Korean',
);
?>

<div class="wrap arf_setting_page">
	<div class="top_bar">
		<span class="h2"><?php echo esc_html__( 'General Settings', 'arforms-form-builder' ); ?></span>
	</div>	
	<div id="poststuff" class="metabox-holder">
		<div id="post-body">
			<div class="inside arfwhitebackground">
				<div class="formsettings1 arfwhitebackground">
					<div class="setting_tabrow">
						<div class="arftab" id="arftab">
							<ul id="arfsettingpagenav" class="arfmainformnavigation">
								
								<?php $arformsmain->arforms_render_settings_tab( $general_setting_tab_selection, $autoresponder_tab_selection, $log_tab_selection, $status_tab_selection ); ?>
								<?php foreach ( $sections as $sec_name => $section ) { ?>
									<li><a href="#<?php echo esc_attr( $sec_name ); ?>_settings"><?php echo esc_html( ucfirst( $sec_name ) ); ?></a></li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<input type="hidden" id="arforms_settings_page_url" value="<?php echo esc_url( $arforms_settings_page_url ); ?>" />
					<input type="hidden" id="show_success_msg"  value="<?php echo esc_html( $show_success ); ?>" />
					<form name="frm_settings_form" method="post" enctype="multipart/form-data" class="frm_settings_form" onsubmit="return arflite_global_form_validate();">
						<input type="hidden" name="arflite_validation_nonce" id="arflite_validation_nonce" value="<?php echo esc_attr( wp_create_nonce( 'arflite_wp_nonce' ) ); ?>" />
						<input type="hidden" id="arforms_wp_nonce" value="<?php echo esc_attr(wp_create_nonce( 'arforms_wp_nonce' )); ?>" />
						<input type="hidden" name="arfaction" value="process-form" />
						<div id="arfsaveformloader"><?php echo ARFLITE_LOADER_ICON; //phpcs:ignore ?></div>
						<input type="hidden" name="arfcurrenttab" id="arfcurrenttab" value="<?php echo esc_attr( get_option( 'arforms_current_tab' ) ); ?>" />
						<?php wp_nonce_field( 'update-options' ); ?>
						<div class="margin-left15">
							<div id="success_message" class="arf_success_message">
								<div class="message_descripiton">
									<div id="form_suc_message_des" class="arf_setting_msg"></div>
									<div class="message_svg_icon">
										<svg class="arfheightwidth14"><path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M6.075,14.407l-5.852-5.84l1.616-1.613l4.394,4.385L17.181,0.411l1.616,1.613L6.392,14.407H6.075z"></path></svg>
									</div>
								</div>
								</div>

								<div id="error_message" class="arf_error_message">
								<div class="message_descripiton">
									<div id="form_error_message_des"></div>
									<div class="message_svg_icon">
										<svg class="arfheightwidth14"><path fill-rule="evenodd" clip-rule="evenodd" fill="#ffffff" d="M10.702,10.909L6.453,6.66l-4.249,4.249L1.143,9.848l4.249-4.249L1.154,1.361l1.062-1.061l4.237,4.237l4.238-4.237l1.061,1.061L7.513,5.599l4.249,4.249L10.702,10.909z"></path></svg>
									</div>
								</div>
								</div>
							<?php
							if ( isset( $message ) && $message != '' ) {
								?>
									<div id="success_message" class="arf_success_message" data-id="arflite_success_msg_setting_forms">
										<div class="message_descripiton">
											<div class="arffloatmargin">
												<?php echo esc_html( $message ); ?>
											</div>
											<div class="message_svg_icon">
												<svg class="arfheightwidth14">
													<path fill-rule="evenodd" clip-rule="evenodd" fill="#FFFFFF" d="M6.075,14.407l-5.852-5.84l1.616-1.613l4.394,4.385L17.181,0.411 l1.616,1.613L6.392,14.407H6.075z"></path>
												</svg>
											</div>
										</div>
									</div>
								<?php
							}

							if ( isset( $arflite_errors ) && is_array( $arflite_errors ) && count( $arflite_errors ) > 0 ) {
								foreach ( $arflite_errors as $error_val ) {
									?>
									<div id="error_message" class="arf_error_message" data-id="arflite_error_msg_setting_forms">
										<div class="message_descripiton">
											<?php echo stripslashes( esc_html( $error_val ) ); //phpcs:ignore ?>
										</div>
									</div>
								<?php }
							} ?>
						</div>

						<div class="arflite-clear-float"></div>

						<div id="general_settings" class="<?php echo ( 'general_settings' != $setting_tab ) ? 'display-none-cls' : 'display-blck-cls'; ?>">
							<table class="form-table">
								<?php if( is_captcha_act() ): ?>
								<tr class="arfmainformfield" valign="top" style="<?php echo ( ! is_captcha_act() ) ? 'display: none;' : 'display: table-row'; ?>">
									<td class="lbltitle" colspan="2"><?php echo esc_html__( 'reCAPTCHA Configuration', 'arforms-form-builder' ); ?>&nbsp;</td>
								</tr>
								<tr class="arfmainformfield" valign="top" style="<?php echo ( ! is_captcha_act() ) ? 'display: none;' : 'display: table-row'; ?>">
									<td colspan="2" style="padding-left:0px; padding-bottom:30px;padding-top:15px;">
										<label class="lblsubtitle"><?php echo stripslashes( esc_html__( 'reCAPTCHA requires an API key, consisting of a "site" and a "private" key. You can sign up for a', 'arforms-form-builder' ) ); ?>&nbsp;&nbsp;<a href="https://www.google.com/recaptcha/" target="_blank" class="arlinks"><b><?php echo esc_html__( 'free reCAPTCHA key', 'arforms-form-builder' ); //phpcs:ignore ?></b></a>.</label>
									</td>
								</tr>
								<tr class="arfmainformfield" valign="top" style="<?php echo ( ! is_captcha_act() ) ? 'display: none;' : 'display: table-row'; ?>">
									<td class="tdclass email-setting-label-td" width="18%">
										<label class="lblsubtitle"><?php echo esc_html__( 'Site Key', 'arforms-form-builder' ); ?></label>
									</td>
									<td>
										<input type="text" name="frm_pubkey" id="frm_pubkey" class="txtmodal1" size="42" value="<?php echo esc_attr( $arflitesettings->pubkey ); ?>" />
									</td>
								</tr>
								<tr class="arfmainformfield" valign="top" style="<?php echo ( ! is_captcha_act() ) ? 'display: none;' : 'display: table-row'; ?>">
									<td class="tdclass">
										<label class="lblsubtitle"><?php echo esc_html__( 'Secret Key', 'arforms-form-builder' ); ?></label>
									</td>
									<td>
										<input type="text" name="frm_privkey" id="frm_privkey" class="txtmodal1" size="42" value="<?php echo esc_attr( $arflitesettings->privkey ); ?>" />
									</td>
								</tr>
								<tr class="arfmainformfield" valign="top" style="<?php echo ( ! is_captcha_act() ) ? 'display: none;' : 'display: table-row'; ?>">
									<td class="tdclass">
										<label class="lblsubtitle"><?php echo esc_html__( 'reCAPTCHA Theme', 'arforms-form-builder' ); ?></label>
									</td>
									<td class="email-setting-input-td">
										<?php
										foreach ( $captcha_theme as $theme_value => $theme_name ) {
											if ( $arflitesettings->re_theme == $theme_value ) {
												$rc_default_theme    = esc_attr( $theme_value );
												$selected_list_label = $theme_name;
											}
											$google_recaptcha_theme .= '<li class="arf_selectbox_option" data-value="' . esc_attr( $rc_default_theme ) . '" data-label="' . esc_attr( $theme_name ) . '">' . $theme_name . '</li>';
										}
										?>
										<div class="sltstandard arffloat-none">
											<input id="frm_re_theme" name="frm_re_theme" value="<?php echo esc_attr( $rc_default_theme ); ?>" type="hidden" class="frm-dropdown frm-pages-dropdown">
											<dl class="arf_selectbox width400px" data-name="frm_re_theme" data-id="frm_re_theme">
												<dt><span><?php echo esc_html( $selected_list_label ); ?></span>
												<svg viewBox="0 0 2000 1000" width="15px" height="15px">
												<g fill="#000">
												<path d="M1024 320q0 -26 -19 -45t-45 -19h-896q-26 0 -45 19t-19 45t19 45l448 448q19 19 45 19t45 -19l448 -448q19 -19 19 -45z"></path>
												</g>
												</svg>
												</dt>
												<dd>
													<ul class="field_dropdown_menu field_dropdown_list_menu display-none-cls" data-id="frm_re_theme">
														<?php
														echo wp_kses(
															$google_recaptcha_theme,
															array(
																'li' => array(
																	'class'      => array(),
																	'data-label' => array(),
																	'data-value' => array(),
																),
															)
														);
														?>
													</ul>
												</dd>
											</dl>
										</div>
									</td>
								</tr>
								<tr class="arfmainformfield" valign="top" style="<?php echo ( ! is_captcha_act() ) ? 'display: none;' : 'display: table-row'; ?>">
									<td class="tdclass">
										<label class="lblsubtitle"><?php echo esc_html__( 'reCAPTCHA Language', 'arforms-form-builder' ); ?></label>
									</td>

									<td class="email-setting-input-td">
										<div class="sltstandard arfrecaptchalang">
											<?php
											foreach ( $rclang as $lang => $lang_name ) {
												if ( $arflitesettings->re_lang == $lang ) {
													$rc_default_lang    = esc_attr( $lang );
													$rc_default_lang_label = $lang_name;
												}
												$google_rclang .= '<li class="arf_selectbox_option" data-value="' . esc_attr( $lang ) . '" data-label="' . esc_attr( $lang_name ) . '">' . $lang_name . '</li>';
											}
											?>
											<input id="frm_re_lang" name="frm_re_lang" value="<?php echo esc_attr( $rc_default_lang ); ?>" type="hidden" class="frm-dropdown frm-pages-dropdown">
											<dl class="arf_selectbox width400px" data-name="frm_re_lang" data-id="frm_re_lang">
												<dt><span><?php echo esc_html( $rc_default_lang_label ); ?></span>
												<svg viewBox="0 0 2000 1000" width="15px" height="15px">
												<g fill="#000">
												<path d="M1024 320q0 -26 -19 -45t-45 -19h-896q-26 0 -45 19t-19 45t19 45l448 448q19 19 45 19t45 -19l448 -448q19 -19 19 -45z"></path>
												</g>
												</svg>
												</dt>
												<dd>
													<ul class="field_dropdown_menu field_dropdown_list_menu display-none-cls" data-id="frm_re_lang">
														<?php
														echo wp_kses(
															$google_rclang,
															array(
																'li' => array(
																	'class'      => array(),
																	'data-value' => array(),
																	'data-label' => array(),
																),
															)
														);
														?>
													</ul>
												</dd>
											</dl>
										</div>
									</td>
								</tr>
								<tr class="arfmainformfield" valign="top" style="<?php echo ( ! is_captcha_act() ) ? 'display: none;' : 'display: table-row'; ?>">
									<td class="tdclass" >
										<label class="lblsubtitle"><?php echo esc_html__( 'reCAPTCHA Failed Message', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<span style="vertical-align:middle" class="arfglobalrequiredfield">*</span></label>
									</td>
									
									<td>				
										<input type="text" class="txtmodal1" value="<?php echo esc_attr( $arflitesettings->re_msg ); ?>" id="arfvaluerecaptcha" name="frm_recaptcha_value" />
										<div class="arferrmessage" id="arferrorsubmitvalue" style="display:none;"><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div>
									</td>
								</tr>
								<tr class="arfmainformfield" valign="top" style="<?php echo ( ! is_captcha_act() ) ? 'display: none;' : 'display: table-row'; ?>">
									<td colspan="2"><div  class="dotted_line dottedline-width96"></div></td>
								</tr>
								<?php endif; ?>
								<?php $arforms_general_settings->arforms_render_pro_settings('arforms_render_settings_before_messages'); ?>
								<tr class="arfmainformfield">
									<td valign="top" colspan="2" class="lbltitle titleclass"><?php echo esc_html__( 'Default Messages On Form', 'arforms-form-builder' ); ?> </td>
								</tr>
								<tr>
									<td class="tdclass default-blnk-msgtd" width="18%">
										<label class="lblsubtitle"><?php echo esc_html__( 'Blank Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<span class="arfglobalrequiredfield default-msg_require">*</span></label> <br/>
									</td>
									<td class="arfmainformfield" >
										<input type="text" id="frm_blank_msg" name="frm_blank_msg" class="txtmodal1 arfgelsetfloatstyle" value="<?php echo esc_attr( $arflitesettings->blank_msg ); ?>"/>

										<div class="arf_tooltip_main arfgelsetfloatstyle"><img alt='' src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/tooltips-icon.png" alt="?" class="arfhelptip default-msg-toltip" title="<?php echo esc_html__( 'Message will be displayed when required fields is left blank.', 'arforms-form-builder' ); ?>"/></div>
										<div class="default-msg_require"></div>
										<div class="arferrmessage display-none-cls" id="arfblankerrmsg"><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div>
									</td>
								</tr>
								<tr class="arfmainformfield">

									<td class="tdclass">
										<label class="lblsubtitle"><?php echo esc_html__( 'Incorrect Field', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<span class="arfglobalrequiredfield default-msg_require">*</span></label> <br/>
									</td>

									<td>
										<input type="text" id="arfinvalidmsg" name="frm_invalid_msg" class="txtmodal1 arfgelsetfloatstyle" value="<?php echo esc_attr( $arflitesettings->invalid_msg ); ?>"/>

										<div class="arf_tooltip_main arfgelsetfloatstyle">
											<img alt='' src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/tooltips-icon.png" alt="?" class="arfhelptip default-msg-toltip" title="<?php echo esc_html__( 'Message will be displayed when incorrect data is inserted of missing.', 'arforms-form-builder' ); ?>" />
										</div>
										<div class="default-msg_require"></div>
										<div class="arferrmessage display-none-cls" id="arfinvalidmsg_error">
											<?php
												echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' );
											?>
										</div>
									</td>
								</tr>
								<tr class="arfmainformfield">
									<td class="tdclass">
										<label class="lblsubtitle"><?php echo esc_html__( 'Success Message', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<span class="arfglobalrequiredfield default-msg_require">*</span></label>
									</td>
									<td>
										<input type="text" id="arfsuccessmsg" name="frm_success_msg" class="txtmodal1 arfgelsetfloatstyle" value="<?php echo esc_attr( $arflitesettings->success_msg ); ?>" />
										<div class="arf_tooltip_main arfgelsetfloatstyle"><img alt='' src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/tooltips-icon.png" alt="?" class="arfhelptip default-msg-toltip" title="<?php echo esc_html__( 'Default message displayed after form is submitted.', 'arforms-form-builder' ); ?>"/></div>
										<div class="arflite-clear-float"></div>
										<div class="arferrmessage display-none-cls" id="arfsuccessmsgerr"><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div>
									</td>
								</tr>
								<tr class="arfmainformfield">
									<td class="tdclass">
										<label class="lblsubtitle"><?php echo esc_html__( 'Submission Failed Message', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<span class="arfglobalrequiredfield default-msg_require">*</span></label>
									</td>
									<td>
										<input type="text" id="arfmessagefailed" name="frm_failed_msg" class="txtmodal1 arfgelsetfloatstyle" value="<?php echo esc_attr( $arflitesettings->failed_msg ); ?>"/>
										<div class="arf_tooltip_main arfgelsetfloatstyle" ><img alt='' src="<?php echo esc_url( ARFLITEIMAGESURL ); ?>/tooltips-icon.png" alt="?" class="arfhelptip default-msg-toltip" title="<?php echo esc_html__( 'Message will be displayed when form is submitted but Duplicate entry exists.', 'arforms-form-builder' ); ?>"/></div>
										<div class="arflite-clear-float"></div>
										<div class="arferrmessage display-none-cls" id="arferrormessagefailed"><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div>
									</td>
								</tr>
								<tr class="arfmainformfield">
									<td class="tdclass" >
										<label class="lblsubtitle"><?php echo esc_html__( 'Default Submit Button', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<span class="arfglobalrequiredfield default-msg_require">*</span></label>
									</td>
									<td>
										<input type="text" class="txtmodal1" value="<?php echo esc_attr( $arflitesettings->submit_value ); ?>" id="arfvaluesubmit" name="frm_submit_value" />
										<div class="arferrmessage display-none-cls" id="arferrsubmitvalue"><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div>
									</td>
								</tr>
								<tr class="arfmainformfield" valign="top">
									<td colspan="2"><div class="dotted_line dottedline-width96"></div></td>
								</tr>
								<tr class="arfmainformfield">
									<td valign="top" colspan="2" class="lbltitle titleclass"><?php echo esc_html__( 'Email Settings', 'arforms-form-builder' ); ?></td>
								</tr>
								<tr>
									<td class="tdclass email-setting-label-td" valign="top">
										<label class="lblsubtitle"><?php echo esc_html__( 'From/Replyto Name', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<span class="arfglobalrequiredfield default-msg_require">*</span></label>
									</td>

									<td valign="top" class="email-setting-input-td">
										<input type="text" class="txtmodal1 width400px" id="frm_reply_to_name" name="frm_reply_to_name" value="<?php echo esc_attr( $arflitesettings->reply_to_name ); ?>">
										<div class="arferrmessage display-none-cls" id="frm_reply_to_name_error"><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div>
									</td>
								</tr>
								<tr>
									<td class="tdclass email-setting-label-td" valign="top">
										<label class="lblsubtitle"><?php echo esc_html__( 'From Email', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<span class="arfglobalrequiredfield default-msg_require">*</span></label>
									</td>
									<td valign="top " class="email-setting-input-td">
										<input type="text" class="txtmodal1 width400px" id="frm_reply_to" name="frm_reply_to" value="<?php echo esc_attr( $arflitesettings->reply_to ); ?>">
										<div class="arferrmessage display-none-cls" id="frm_reply_to_error"><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div>
									</td>
								</tr>
								<tr>
									<td class="tdclass email-setting-label-td" valign="top">
										<label class="lblsubtitle"><?php echo esc_html__( 'Reply to Email', 'arforms-form-builder' ); ?>&nbsp;&nbsp;<span class="arfglobalrequiredfield default-msg_require">*</span></label>
									</td>
									<td valign="top " class="email-setting-input-td">
										<input type="text" class="txtmodal1 width400px" id="reply_to_email" name="reply_to_email" value="<?php echo esc_attr( $arflitesettings->reply_to_email ); ?>">
										<div class="arferrmessage display-none-cls" id="frm_reply_to_error"><?php echo esc_html__( 'This field cannot be blank.', 'arforms-form-builder' ); ?></div>
									</td>
								</tr>
								<tr>
									<td class="tdclass email-setting-label-td" valign="top"><label class="lblsubtitle"><?php echo esc_html__( 'Send Email SMTP', 'arforms-form-builder' ); ?></label> </td>
									<td valign="top" class="email-setting-input-td">
										<div class="arf_radio_wrapper">
											<div class="arf_custom_radio_div">
												<div class="arf_custom_radio_wrapper">
													<input type="radio" class="arf_custom_radio arf_submit_action" name="frm_smtp_server" id="arf_wordpress_smtp" value="wordpress" <?php checked( $arflitesettings->smtp_server, 'wordpress' ); ?> onchange="arformschangesmtpsetting();"  />
													<svg width="18px" height="18px">
														<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
														<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="arf_wordpress_smtp"><?php echo esc_html__( 'WordPress Server', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
										<div class="arf_radio_wrapper">
											<div class="arf_custom_radio_div">
												<div class="arf_custom_radio_wrapper">
													<input type="radio" class="arf_custom_radio arf_submit_action" name="frm_smtp_server" id="arf_custom_custom" onchange="arformschangesmtpsetting();" value="custom" <?php checked( $arflitesettings->smtp_server, 'custom' ); ?>  />
													<svg width="18px" height="18px">
														<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
														<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="arf_custom_custom"><?php echo esc_html__( 'SMTP Server', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
										<div class="arf_radio_wrapper">
											<div class="arf_custom_radio_div">
												<div class="arf_custom_radio_wrapper">
													<input type="radio" class="arf_custom_radio arf_submit_action" name="frm_smtp_server" id="arf_wordpress_phpmailer" value="phpmailer" <?php checked( $arflitesettings->smtp_server, 'phpmailer' ); ?> onchange="arformschangesmtpsetting();"  />
													<svg width="18px" height="18px">
														<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
														<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="arf_wordpress_phpmailer"><?php echo esc_html__( 'PHP Mailer', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
										<!-- Gmail API changes start -->
										<div class="arf_radio_wrapper">
											<div class="arf_custom_radio_div">
												<div class="arf_custom_radio_wrapper">
													<input type="radio" class="arf_custom_radio arf_submit_action" name="frm_smtp_server" id="arf_wordpress_gmail_api" value="arflite_gmail_api" <?php checked( $arflitesettings->smtp_server, 'arflite_gmail_api' ); ?> onchange="arformschangesmtpsetting();"  />
													<svg width="18px" height="18px">
														<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
														<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="arf_wordpress_gmail_api"><?php echo esc_html__( 'Google/Gmail API', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
										<!-- Gmail API changes end -->
									</td>
								</tr>

								<tr class="arflite_check_email_format" <?php echo ('arflite_gmail_api' ==  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?>>
									<td class="tdclass email-setting-label-td" valign="top">
										<label class="lblsubtitle"><?php echo esc_html__( 'Email Format', 'arforms-form-builder' ); ?></label>
									</td>
									<td valign="top" class="email-setting-input-td">
										<div class="arf_radio_wrapper">
											<div class="arf_custom_radio_div" >
												<div class="arf_custom_radio_wrapper">
													<input type="radio" name="arf_email_format" id="arf_email_html" class="arf_submit_action arf_custom_radio" value="html" <?php echo ( 'html' == $arflitesettings->arf_email_format || empty( $arflitesettings->arf_email_format ) ) ? 'checked="checked"' : ''; ?> />
													<svg width="18px" height="18px">
														<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
														<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="arf_email_html"><?php echo esc_html__( 'HTML', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
										<div class="arf_radio_wrapper">
											<div class="arf_custom_radio_div" >
												<div class="arf_custom_radio_wrapper">
													<input type="radio" name="arf_email_format" id="arf_email_plain" class="arf_submit_action arf_custom_radio" value="plain" <?php checked( $arflitesettings->arf_email_format, 'plain' ); ?> />
													<svg width="18px" height="18px">
														<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
														<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="arf_email_plain"><?php echo esc_html__( 'Plain Text', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
									</td>
								</tr>
								
								<!-- Google/Gmail api related changes start -->
								<tr class="arfgmailapisetting"  <?php echo ('arflite_gmail_api' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?> >
									<td colspan="3" style="padding-left:12%;">
									<span style="color:#f26500e0;">
										<?php echo esc_html__( "Note: The Gmail mailer works well for sites that send low numbers of emails. However, Gmail's API has rate limitations and a number of additional restrictions that can lead to challenges during setup. If you expect to send a high volume of emails, or if you find that your web host is not compatible with the Gmail API restrictions, then we recommend considering a different mailer option.", 'arforms-form-builder' ); ?>
									</span>
									</td>
								</tr>
								<tr class="arfgmailapisetting" <?php echo ('arflite_gmail_api' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?> >
									<td class="tdclass" valign="top" style="padding-left:30px;"><label class="lblsubtitle"><?php echo esc_html__( 'Client ID', 'arforms-form-builder' ); ?></label></td>
									<td valign="top" style="padding-bottom:10px;">
										<input type="text" class="txtmodal1" id="frm_gmail_api_clientid" name="frm_gmail_api_clientid" value="<?php echo $arflitesettings->gmail_api_clientid; //phpcs:ignore ?>" style="width:400px;">
										<p class="arflite_error_msg_gmail_client_id"> <?php echo esc_html__( 'Please Enter Client ID', 'arforms-form-builder' ); ?> </p>
									</td>
								</tr>
								<tr class="arfgmailapisetting" <?php echo ('arflite_gmail_api' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?>>
									<td class="tdclass" valign="top" style="padding-left:30px;"><label class="lblsubtitle"><?php echo esc_html__( 'Client Secret', 'arforms-form-builder' ); ?></label></td>
									<td valign="top" style="padding-bottom:10px;">
										<input type="text" class="txtmodal1" id="frm_gmail_api_clientsecret" name="frm_gmail_api_clientsecret" value="<?php echo $arflitesettings->gmail_api_clientsecret; //phpcs:ignore ?>" style="width:400px;">
										<p class="arflite_error_msg_gmail_client_secret"> <?php echo esc_html__( 'Please Enter Client Secret', 'arforms-form-builder' ); ?> </p>
									</td>
								</tr>

								<tr class="arfgmailapisetting" <?php echo ('arflite_gmail_api' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?> >
									<td class="tdclass" valign="top" style="padding-left:30px;"><label class="lblsubtitle"><?php echo esc_html__( 'Authorized redirect URI', 'arforms-form-builder' ); ?></label></td>
									<td valign="top" style="padding-bottom:10px;">
											<?php $authURL = get_home_url() . '?page=ARForms-settings'; ?>
										<input type="text" class="txtmodal1" id="frm_arflite_gmail_auth_url" name="frm_arflite_gmail_auth_uri" value="<?php echo esc_url($authURL); ?>" readonly>

										<span style="width: 30px; height: 30px; display: inline-block; margin-left: 10px; cursor:pointer;" id="arflite_gmail_api_copy" data-copy-title="<?php esc_html_e( 'Click to Copy', 'arforms-form-builder' ); ?>" data-copied-title="<?php esc_html_e( 'Copied to Clipboard', 'arforms-form-builder' ); ?>" ><i class="far fa-copy" style="font-size:18px; padding:2px; color:#3f74e7; cursor:pointer; position:relative;top:2px;left:-2px;"></i></span>

									</td>
								</tr>

								<tr class="arfgmailapisetting" <?php echo ('arflite_gmail_api' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?> >
									<td class="tdclass" valign="top" style="padding-left:30px;"><label class="lblsubtitle"><?php echo esc_html__( 'Authentication Token', 'arforms-form-builder' ); ?></label></td>
									<td valign="top" style="padding-bottom:10px;">
										<?php
											$arf_gmail_api_access_token = '';
											$arf_gmail_api_access_token = $arformsmain->arforms_get_settings('arf_gmail_api_access_token','general_settings');
											$arf_gmail_api_access_token = json_decode( $arf_gmail_api_access_token, true );

										?>
										<input type="text" class="txtmodal1" id="frm_gmail_api_accesstoken" name="frm_gmail_api_accesstoken" value="<?php echo $arf_gmail_api_access_token; //phpcs:ignore ?>" style="width:400px;" disabled>
										<?php
											$arf_gmail_connected_email = $arformsmain->arforms_get_settings('arf_gmail_api_connected_email','general_settings');
											$arf_gmail_connected_email = json_decode( $arf_gmail_connected_email, true );
										?>
										<input type="hidden" id="arflite_connected_email" name="arflite_connected_email" value="<?php echo $arf_gmail_connected_email; //phpcs:ignore ?>">
										<span id="arflite_google_api_auth_link_remove" style=" <?php echo ( $arf_gmail_api_access_token != '' ) ? 'display:inline-block; margin-left:5px;' : 'display:none;'; ?> ">
											<a target="_blank" id="arf_gmail_disconnect_btn" onclick="arflite_gmail_api_remove('<?php echo $arflitesettings->gmail_api_clientid; ?>','<?php echo $arflitesettings->gmail_api_clientsecret; ?> ',' <?php echo $arf_gmail_api_access_token; ?>',' <?php echo $arf_gmail_connected_email; ?>');" class="arlinks"><?php echo esc_html__('Disconnect', 'arforms-form-builder'); ?></a> <?php //phpcs:ignore ?>
										</span>
										<span id="arflite_google_api_auth_link" style=" <?php echo ( $arf_gmail_api_access_token == '' ) ? 'display:inline-block; margin-left:5px;' : 'display:none;'; ?>" >
											<a target="_blank" class="arf_gmail_connect_btn rounded_button arf_btn_dark_blue arf_gmail_connect_cls" onclick="arflite_connect_gmail_api('<?php echo $authURL; ?>','<?php echo $arflitesettings->gmail_api_clientid; ?>','<?php echo $arflitesettings->gmail_api_clientsecret; ?> ');" class="arlinks"><?php echo esc_html__('Connect','arforms-form-builder'); ?> </a> <?php //phpcs:ignore ?>
										</span>
									</td>
								</tr>

								<?php
									$gmail_test_mail_style = "disabled='disabled'";
									$gmail_test_main_class = 'arfdisabled';

								if ( $arflitesettings->smtp_server == 'arflite_gmail_api' && $arflitesettings->gmail_api_clientid != '' && $arflitesettings->gmail_api_clientsecret != '' ) {
									$gmail_test_mail_style = '';
									$gmail_test_main_class = '';
								} else {
									$gmail_test_mail_style = "disabled='disabled'";
									$gmail_test_main_class = 'arfdisabled';
								}
								?>
								<tr class="arfgmailapisetting" <?php echo ('arflite_gmail_api' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?>>
									<td class="tdclass" valign="top" style="padding-left:20px;">
										<label class="lbltitle">
											<?php echo addslashes( esc_html__( 'Send Test E-mail', 'arforms-form-builder' ) ); //phpcs:ignore ?>
										</label>
									</td>
									<td valign="top" style="padding-bottom:10px;">
										<label id="arflite_success_test_gmail"><?php echo addslashes( esc_html__( 'Your test mail is successfully sent', 'arforms-form-builder' ) ); //phpcs:ignore ?> </label>
										<label id="arflite_error_test_gmail"><?php echo addslashes( esc_html__( 'Your test mail is not sent for some reason, Please check your Gmail setting', 'arforms-form-builder' ) ); //phpcs:ignore ?> </label>
									</td>
								</tr>
								<tr class="arfgmailapisetting" <?php echo ('arflite_gmail_api' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?>>
									<td class="tdclass" valign="top" style="padding-left:20px;">
										<label class="lblsubtitle">
											<?php echo addslashes( esc_html__( 'To', 'arforms-form-builder' ) ); //phpcs:ignore ?>
										</label>
									</td>
									<td valign="top" style="padding-bottom:10px;">
										<input type="text" id="arflite_sendtestgmail_to" name="arflite_sendtestgmail_to" class="txtmodal1 <?php echo esc_html($gmail_test_main_class); ?>" value="<?php echo isset( $arflitesettings->smtp_send_test_mail_to ) ? $arflitesettings->smtp_send_test_mail_to : ''; //phpcs:ignore ?>" <?php echo $gmail_test_mail_style; //phpcs:ignore ?> />
										<p class="arf_error_msg_gmail_to_email"> <?php echo esc_html__( 'Please Enter To Email', 'arforms-form-builder' ); ?> </p>
									</td>
								</tr>

								<tr class="arfgmailapisetting" <?php echo ('arflite_gmail_api' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?>>
									<td class="tdclass" valign="top" style="padding-left:20px;">
										<label class="lblsubtitle">
											<?php echo addslashes( esc_html__( 'Message', 'arforms-form-builder' ) ); //phpcs:ignore ?>
										</label>
									</td>
									<td valign="top" style="padding-bottom:10px;">
										<textarea class="txtmultinew testmailmsg  <?php echo esc_html($gmail_test_main_class); ?>" name="arflite_sendtestgmail_msg" <?php echo $gmail_test_mail_style; //phpcs:ignore ?> id="arflite_sendtestgmail_msg" ><?php echo isset( $arflitesettings->smtp_send_test_mail_msg ) ? $arflitesettings->smtp_send_test_mail_msg : ''; ?></textarea>
										<p class="arf_error_msg_gmail_to_msg"> <?php echo esc_html__( 'Please Enter Message', 'arforms-form-builder' ); ?> </p>
									</td>
								</tr>

								<tr class="arfgmailapisetting" <?php echo ('arflite_gmail_api' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?>>
									<td class="tdclass" valign="top" style="padding-left:20px;">
										<label class="lblsubtitle">&nbsp;</label>
									</td>
									<td valign="top" style="padding-bottom:10px;">
										<input type="button" value="<?php echo addslashes( esc_html__( 'Send test mail', 'arforms-form-builder' ) ); //phpcs:ignore ?>" class="rounded_button arf_btn_dark_blue <?php echo esc_html($gmail_test_main_class); ?>" id="arflite_send_test_gmail" <?php echo $gmail_test_mail_style; ?> style="<?php echo ( is_rtl() ) ? 'margin-right: -4px;' : 'margin-left: -4px;'; ?>color:#ffffff;width: 118px !important;"> <img alt='' src="<?php echo ARFLITEIMAGESURL . '/ajax_loader_gray_32.gif'; ?>" id="arflite_send_test_gmail_loader" style="display:none;position:relative;left:5px;top:5px;" width="16" height="16" /> <span  class="lblnotetitle">(<?php echo addslashes( esc_html__( 'Test e-mail works only after configure Gmail API settings', 'arforms-form-builder' ) ); ?>)</span>
									</td>
								</tr>
								<!-- Google/Gmail api related changes end -->

								<tr class="arfsmptpsettings" <?php echo ( $arflitesettings->smtp_server != 'custom' ) ? 'style="display:none;"' : ''; ?> >
									<td class="tdclass email-setting-label-td" valign="top"><label class="lblsubtitle"><?php echo esc_html__( 'Authentication', 'arforms-form-builder' ); ?></label> </td>
									<td valign="top" class="email-setting-input-td">
										<div class="arf_custom_checkbox_div">
											<div class="arf_custom_checkbox_wrapper">
												<input type="checkbox" class="" onclick="arf_is_smtp_authentication();" id="is_smtp_authentication" name="is_smtp_authentication" value="1" <?php checked( $arflitesettings->is_smtp_authentication, 1 ); ?>>
												<svg width="18px" height="18px">
													<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
													<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
												</svg>
											</div>
											<span class="arf_gerset_checkoption"><label for="is_smtp_authentication"><?php echo esc_html__( 'Enable SMTP authentication', 'arforms-form-builder' ); ?></label></span>
										</div>
									</td>
								</tr>

								<tr class="arfsmptpsettings" <?php echo ('custom' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?>>
									<td class="tdclass email-setting-label-td" valign="top"><label class="lblsubtitle"><?php echo esc_html__( 'SMTP Host', 'arforms-form-builder' ); ?></label></td>
									<td valign="top" class="email-setting-input-td">
										<input type="text" class="txtmodal1 width400px" id="frm_smtp_host" name="frm_smtp_host" value="<?php echo esc_attr( $arflitesettings->smtp_host ); ?>">
									</td>
								</tr>

								<tr class="arfsmptpsettings" <?php echo ('custom' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?>>
									<td class="tdclass email-setting-label-td" valign="top"><label class="lblsubtitle"><?php echo esc_html__( 'SMTP Port', 'arforms-form-builder' ); ?></label></td>
									<td valign="top" class="email-setting-input-td">
										<input onkeyup="arflite_show_test_mail();" type="text" class="txtmodal1 width400px" id="frm_smtp_port" name="frm_smtp_port" value="<?php echo esc_attr( $arflitesettings->smtp_port ); ?>">
									</td>
								</tr>

								<tr class="arfsmptpsettings arf_authentication_field" <?php echo( 'custom' != $arflitesettings->smtp_server ) ? 'style="display:none;"' : ( ('1' != $arflitesettings->is_smtp_authentication) ? 'style="display:none;"' : '' ) //phpcs:ignore ?>>
									<td class="tdclass email-setting-label-td" valign="top"><label class="lblsubtitle"><?php echo esc_html__( 'SMTP Username', 'arforms-form-builder' ); ?></label></td>
									<td valign="top" class="email-setting-input-td">
										<input onkeyup="arflite_show_test_mail();" type="text" class="txtmodal1 width400px" id="frm_smtp_username" name="frm_smtp_username" value="<?php echo esc_attr( $arflitesettings->smtp_username ); ?>">
									</td>
								</tr>

								<tr class="arfsmptpsettings arf_authentication_field" <?php echo( 'custom' != $arflitesettings->smtp_server ) ? 'style="display:none;"' : ( ('1' != $arflitesettings->is_smtp_authentication) ? 'style="display:none;"' : '' ) //phpcs:ignore ?> >
									<td class="tdclass email-setting-label-td" valign="top"><label class="lblsubtitle"><?php echo esc_html__( 'SMTP Password', 'arforms-form-builder' ); ?></label></td>
									<td valign="top" class="email-setting-input-td">
										<input onkeyup="arflite_show_test_mail();" type="password" class="txtmodal1 width400px" id="frm_smtp_password" name="frm_smtp_password" value="<?php echo esc_attr( $arflitesettings->smtp_password ); ?>">
									</td>
								</tr>

								<tr class="arfsmptpsettings" <?php echo ('custom' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?> >
									<td class="tdclass email-setting-label-td" valign="top"><label class="lblsubtitle"><?php echo esc_html__( 'SMTP Encryption', 'arforms-form-builder' ); ?></label></td>
									<td valign="top" class="email-setting-input-td">
										<div class="arf_radio_wrapper">
											<div class="arf_custom_radio_div" >
												<div class="arf_custom_radio_wrapper">
													<input type="radio" name="frm_smtp_encryption" id="frm_smtp_encryption_none" class="arf_submit_action arf_custom_radio" value="none" <?php checked( $arflitesettings->smtp_encryption, 'none' ); ?> />
													<svg width="18px" height="18px">
														<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
														<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="frm_smtp_encryption_none"><?php echo esc_html__( 'None', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
										<div class="arf_radio_wrapper">
											<div class="arf_custom_radio_div" >
												<div class="arf_custom_radio_wrapper">
													<input type="radio" name="frm_smtp_encryption" id="frm_smtp_encryption_ssl" class="arf_submit_action arf_custom_radio" value="ssl" <?php checked( $arflitesettings->smtp_encryption, 'ssl' ); ?> />
													<svg width="18px" height="18px">
														<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
														<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="frm_smtp_encryption_ssl"><?php echo esc_html__( 'SSL', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
										<div class="arf_radio_wrapper">
											<div class="arf_custom_radio_div" >
												<div class="arf_custom_radio_wrapper">
													<input type="radio" name="frm_smtp_encryption" id="frm_smtp_encryption_tls" class="arf_submit_action arf_custom_radio" value="tls" <?php checked( $arflitesettings->smtp_encryption, 'tls' ); ?> />
													<svg width="18px" height="18px">
														<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
														<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="frm_smtp_encryption_tls"><?php echo esc_html__( 'TLS', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
									</td>
								</tr>
								<?php
								$smtp_test_mail_style = "disabled='disabled'";
								$smtp_test_main_class = 'arfdisabled';

								if ( $arflitesettings->is_smtp_authentication == '1' ) {
									if ( $arflitesettings->smtp_server == 'custom' && $arflitesettings->smtp_port != '' && $arflitesettings->smtp_host != '' && $arflitesettings->smtp_username != '' && $arflitesettings->smtp_password != '' ) {
										$smtp_test_mail_style = '';
										$smtp_test_main_class = '';
									} else {
										$smtp_test_mail_style = "disabled='disabled'";
										$smtp_test_main_class = 'arfdisabled';
									}
								} else {
									if ( $arflitesettings->smtp_server == 'custom' && $arflitesettings->smtp_port != '' && $arflitesettings->smtp_host != '' ) {
										$smtp_test_mail_style = '';
										$smtp_test_main_class = '';
									} else {
										$smtp_test_mail_style = "disabled='disabled'";
										$smtp_test_main_class = 'arfdisabled';
									}
								}
								?>
								<tr class="arfsmptpsettings" <?php echo ('custom' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?> >
									<td class="tdclass testemail-lbl" valign="top">
										<label class="lbltitle">
											<?php echo esc_html__( 'Send Test E-mail', 'arforms-form-builder' ); ?>
										</label>
									</td>
									<td valign="top" class="email-setting-input-td">
										<label id="arf_success_test_mail"><?php echo esc_html__( 'Your test mail is successfully sent', 'arforms-form-builder' ); ?> </label>
										<label id="arf_error_test_mail"><?php echo esc_html__( 'Your test mail is not sent for some reason, Please check your SMTP setting', 'arforms-form-builder' ); ?> </label>
									</td>
								</tr>
								<tr class="arfsmptpsettings" <?php echo ('custom' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?> >
									<td class="tdclass testemail-lbl" valign="top">
										<label class="lblsubtitle">
											<?php echo esc_html__( 'To', 'arforms-form-builder' ); ?>
										</label>
									</td>
									<td valign="top" class="email-setting-input-td">
										<input type="text" id="sendtestmail_to" name="sendtestmail_to" class="txtmodal1 <?php echo esc_attr( $smtp_test_main_class ); ?>" value="<?php echo isset( $arflitesettings->smtp_send_test_mail_to ) ? esc_attr( $arflitesettings->smtp_send_test_mail_to ) : ''; ?>" <?php echo esc_attr( $smtp_test_mail_style ); ?> />
									</td>
								</tr>

								<tr class="arfsmptpsettings" <?php echo ('custom' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?>>
									<td class="tdclass testemail-lbl" valign="top">
										<label class="lblsubtitle">
											<?php echo esc_html__( 'Message', 'arforms-form-builder' ); ?>
										</label>
									</td>
									<td valign="top" class="email-setting-input-td">
										<textarea class="txtmultinew testmailmsg  <?php echo esc_attr( $smtp_test_main_class ); ?>" name="sendtestmail_msg" <?php echo esc_attr( $smtp_test_mail_style ); ?> id="sendtestmail_msg" ><?php echo isset( $arflitesettings->smtp_send_test_mail_msg ) ? esc_attr( $arflitesettings->smtp_send_test_mail_msg ) : ''; ?></textarea>
									</td>
								</tr>

								<tr class="arfsmptpsettings" <?php echo ('custom' !=  $arflitesettings->smtp_server ) ? 'style="display:none;"' : ''; ?>>
									<td class="tdclass testemail-lbl" valign="top">
										<label class="lblsubtitle">&nbsp;</label>
									</td>

									<td valign="top" class="email-setting-input-td">
										<input type="button" value="<?php echo esc_html__( 'Send test mail', 'arforms-form-builder' ); ?>" class="rounded_button --arf-auto-width-btn arf_btn_dark_blue send-testmail-input <?php echo esc_attr( $smtp_test_main_class ); ?>" id="arf_send_test_mail" style="<?php echo ( is_rtl() ) ? 'margin-right: -4px;' : 'margin-left: -4px;'; ?>color:#ffffff;width: 118px !important;" <?php echo esc_attr( $smtp_test_mail_style ); ?>> <img alt='' src="<?php echo esc_url( ARFLITEIMAGESURL ) . '/ajax_loader_gray_32.gif'; ?>" id="arf_send_test_mail_loader" class="display-none-cls" width="16" height="16" /> <span  class="lblnotetitle">(<?php echo esc_html__( 'Test e-mail works only after configure SMTP server settings', 'arforms-form-builder' ); ?>)</span>
									</td>
								</tr>
								<tr class="arfmainformfield" valign="top">
									<td colspan="2"><div class="dotted_line dottedline-width96"></div></td>
								</tr>
								<tr class="arfmainformfield">
									<td valign="top" colspan="2" class="lbltitle titleclass"><?php echo esc_html__( 'Other Settings', 'arforms-form-builder' ); ?></td>
								</tr>
								
								<?php $arforms_general_settings->arforms_render_pro_settings( 'rebranding' ); ?>
								<?php $arforms_general_settings->arforms_render_pro_settings( 'affiliate_code' ); ?>
								<tr>
									<td class="tdclass genenal-setlbl-padding" valign="top"><label class="lblsubtitle"><?php echo esc_html__( 'Disable built-in Anti-spam feature in signup forms', 'arforms-form-builder' ); ?></label> </td>
									<td valign="top" class="arfhidden_captcha-td">
										<div class="arf_custom_checkbox_div">
											<div class="arf_custom_checkbox_wrapper">
												<input type="checkbox" name="arfdisablehiddencaptcha" id="arfdisablehiddencaptcha" value="1" <?php checked( $arflitesettings->hidden_captcha, 1 ); ?> />
												<svg width="18px" height="18px">
													<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
													<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
												</svg>
											</div>
											<span class="arf_gerset_checkoption"><label for="arfdisablehiddencaptcha"><?php echo esc_html__( 'Yes', 'arforms-form-builder' ); ?></label></span>
										</div>
									</td>
								</tr>
								<tr>
									<td class="tdclass email-setting-label-td" valign="top"><label class="lblsubtitle"><?php echo esc_html__( 'Form Submission Method', 'arforms-form-builder' ); ?></label> </td>

									<td valign="top" class="email-setting-input-td">
										<div class="arf_radio_wrapper">
											<div class="arf_custom_radio_div" >
												<div class="arf_custom_radio_wrapper">
													<input type="radio" onchange="arflite_change_form_submission_type(this);" name="arfmainformsubmittype" id="ajax_base_sbmt" class="arf_submit_action arf_custom_radio" value="1" <?php checked( $arflitesettings->form_submit_type, 1 ) ?> />
													<svg width="18px" height="18px">
														<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
														<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="ajax_base_sbmt"><?php echo esc_html__( 'Ajax based submission', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
										<div class="arf_radio_wrapper">
											<div class="arf_custom_radio_div" >
												<div class="arf_custom_radio_wrapper">
													<input type="radio" onchange="arflite_change_form_submission_type(this);" name="arfmainformsubmittype" id="normal_form_sbmt" class="arf_submit_action arf_custom_radio" value="0" <?php checked( $arflitesettings->form_submit_type, 0 ); ?> />
													<svg width="18px" height="18px">
														<?php echo ARFLITE_CUSTOM_UNCHECKEDRADIO_ICON; //phpcs:ignore ?>
														<?php echo ARFLITE_CUSTOM_CHECKEDRADIO_ICON; //phpcs:ignore ?>
													</svg>
												</div>
											</div>
											<span>
												<label for="normal_form_sbmt"><?php echo esc_html__( 'Normal submission', 'arforms-form-builder' ); ?></label>
											</span>
										</div>
									</td>
								</tr>

								<tr class="arf_success_message_show_time_wrapper" <?php echo ( 0 == $arflitesettings->form_submit_type ) ? 'style="display: none;"' :""; ?> >
									<td class="tdclass email-setting-label-td" valign="top"><label class="lblsubtitle"><?php echo esc_html__( 'Hide success message after', 'arforms-form-builder' ); ?></label> </td>
									<td valign="top" class="email-setting-input-td">
										<?php
										if ( ! ( isset( $arflitesettings->arf_success_message_show_time ) && $arflitesettings->arf_success_message_show_time >= 0 ) ) {
											$arflitesettings->arf_success_message_show_time = 3;
										}
										?>
										<div class="arf_success_message_show_time_inner">
											<input type="text" name="arf_success_message_show_time" onkeydown="arflitevalidatenumber_admin(this, event);" maxlength="3" value="<?php echo esc_attr( $arflitesettings->arf_success_message_show_time ); ?>" id="arf_success_message_show_time" class="arf_success_message_show_time txtmodal1 arf_small_width_txtbox arfcolor"/>
											<?php echo esc_html__( 'seconds', 'arforms-form-builder' ) . '&nbsp;&nbsp;'; ?>
											<span class="arf_success_message_show_time_inner">( <?php echo esc_html__( 'Note : 0 ( zero ) means it will never hide success message', 'arforms-form-builder' ); ?> )</span>										
										</div>
									</td>
								</tr>

								<?php $arforms_general_settings->arforms_render_pro_settings( 'decimal_separator' ); ?>

								<tr>
									<td class="tdclass font-general-setting" valign="top"><label class="lblsubtitle"><?php echo esc_html__( 'Select character sets for google fonts', 'arforms-form-builder' ); ?></label> </td>
									<td valign="top" class="email-setting-input-td">
										<div class="font-setting-div">
											<span class="font-setting-span">
												<?php
												$arf_chk_counter = 1;
												foreach ( $arf_character_arr as $arf_character => $arf_character_value ) {

													$default_charset = '';
													if ( isset( $arflitesettings->arf_css_character_set ) ) {
														if ( is_object( $arflitesettings->arf_css_character_set ) ) {
															$default_charset = isset( $arflitesettings->arf_css_character_set->$arf_character ) ? $arflitesettings->arf_css_character_set->$arf_character : '';
														} elseif ( is_array( $arflitesettings->arf_css_character_set ) ) {
															$default_charset = ( isset( $arflitesettings->arf_css_character_set[ $arf_character ] ) ) ? $arflitesettings->arf_css_character_set[ $arf_character ] : '';
														} else {
															$default_charset = '';
														}
													}
													?>
													<div class="arf_custom_checkbox_div">
														<div class="arf_custom_checkbox_wrapper">
															<input type="checkbox" id="arf_character_<?php echo esc_attr( $arf_character ); ?>" name="arf_css_character_set[<?php echo esc_attr( $arf_character ); ?>]" <?php checked( $default_charset, $arf_character ); ?> value="<?php echo esc_attr( $arf_character ); ?>" />
															<svg width="18px" height="18px">
																<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
																<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
															</svg>
														</div>
														<span class="arf-character-span"><label for="arf_character_<?php echo esc_attr( $arf_character ); ?>"><?php echo esc_html( $arf_character_value ); ?></label></span>
													</div>
													<?php echo ( $arf_chk_counter % 4 == 0 ) ? '</span><span class="arf_charcounter_span">' : ''; ?>
													<?php $arf_chk_counter++;
												} ?>
											</span>
										</div>
									</td>

								</tr>

								<tr>
									<td class="tdclass email-setting-label-td" valign="top"><label class="lblsubtitle arfform-global-css"><?php echo esc_html__( 'Form Global CSS', 'arforms-form-builder' ); ?></label></td>

									<td valign="top" class="email-setting-input-td"><div class="arf_gloabal_css_wrapper"><textarea name="arf_global_css" id="arf_global_css" class="txtmultinew"><?php echo stripslashes_deep( $arflitesettings->arf_global_css );//phpcs:ignore ?></textarea></div></td>

								</tr>

								<?php $arforms_general_settings->arforms_render_pro_settings( 'upload_file_in_wpmedia' ); ?>

								<?php $arforms_general_settings->arforms_render_pro_settings( 'upload_file_path' ); ?>

								<?php $arforms_general_settings->arforms_render_pro_settings( 'remove_junk_file' ); ?>

								<tr> 
									<td class="tdclass" valign="top" style="padding-left:30px;"><label class="lblsubtitle"><?php echo esc_html__('Help us improve ARForms by sending anonymous usage stats', 'arforms-form-builder'); ?></label> </td>

									<td valign="top" style="padding-bottom:10px;padding-top:15px;vertical-align: top;">
										<div class="arf_custom_checkbox_div">
											<div class="arf_custom_checkbox_wrapper">
												
												<input type="checkbox" name="anonymous_data" id="anonymous_data" value="1" <?php checked($arflitesettings->anonymous_data, 1) ?> />
												<svg width="18px" height="18px">
												<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
												<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
												</svg>
											</div>
											<span style="margin-left: 5px;"><label for="anonymous_data"><?php echo esc_html__('Yes', 'arforms-form-builder'); ?></label></span>
										</div>
									</td>
								</tr>

								<tr class="arfmainformfield" valign="top">
									<td colspan="2"><div class="dotted_line dottedline-width96"></div></td>
								</tr>
								<tr class="arfmainformfield">
									<td valign="top" colspan="2" class="lbltitle titleclass"><?php echo esc_html__( 'Load JS & CSS in all pages', 'arforms-form-builder' ); ?></td>
								</tr>

								<tr class="arfmainformfield" valign="top">
									<td colspan="2" class="load-jscss-labl-wrap">
										<label class="lblsubtitle">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo stripslashes( __( '( Not recommended - If you have any js/css loading issue in your theme, only in that case you should enable this settings )', 'arforms-form-builder' ) ); //phpcs:ignore ?></label>
									</td>
								</tr>

								<tr>
									<td class="tdclass email-setting-label-td" valign="top"><label class="lblsubtitle"><?php echo esc_html__( 'Load JS & CSS', 'arforms-form-builder' ); ?></label> </td>
									<td valign="top" class="email-setting-input-td">
										<div class="arf_js_switch_wrapper">
											<input type="checkbox" class="js-switch" name="frm_arfmainformloadjscss" value="1" <?php checked( $arflitesettings->arfmainformloadjscss, 1 ); ?> onchange="arflite_change_load_js_css_wrapper(this);" />
											<span class="arf_js_switch"></span>
										</div>
										<label class="arf_js_switch_label"><span>&nbsp;<?php echo esc_html__( 'Enable', 'arforms-form-builder' ); ?></span></label>
									</td>
								</tr>
								<tr class="arf_global_js_css_wrapper_show" <?php echo ( $arflitesettings->arfmainformloadjscss ) ? 'style="display:table-row";' : 'style="display:none;"'; ?>>
									<td></td>
									<td>
										<div  class="arf_global_js_css_div">
											<?php
												$i            = 1;
												$js_css_array = $arfliteformcontroller->arflite_field_wise_js_css();
												foreach ( $js_css_array as $key => $value ) {
													?>
													<div class="arf_custom_checkbox_div arf_load_js_css_option_wrapper" id="arf_load_js_css_option_wrapper">
														<div class="arf_custom_checkbox_wrapper">
															<input type="checkbox" id="arf_all_<?php echo esc_attr( $key ); ?>" name="arf_load_js_css[]" value="<?php echo esc_attr( $key ); ?>" <?php echo ( is_array( $arflitesettings->arf_load_js_css ) && in_array( $key, $arflitesettings->arf_load_js_css ) ) ? 'checked="checked"' : ''; ?> />
															<svg width="18px" height="18px">
																<?php echo ARFLITE_CUSTOM_UNCHECKED_ICON; //phpcs:ignore ?>
																<?php echo ARFLITE_CUSTOM_CHECKED_ICON; //phpcs:ignore ?>
															</svg>
														</div>
														<span style="<?php echo ( is_rtl() ) ? '' : 'margin-left: 5px;'; ?>"><label for="arf_all_<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value['title'] ); ?></label></span>
													</div>
													<?php
													$i++;
												}
											?>
										</div>
									</td>
								</tr>							
								<input type="hidden" id="frm_permalinks" name="frm_permalinks" value="0" />

								<?php $arforms_general_settings->arforms_render_pro_settings('arforms_render_additional_settings'); ?>
							</table>
						</div>

						<?php

							$arformsmain->arforms_load_autoresponder_settings_view();
							$arformsmain->arforms_load_log_settings_view();

							foreach ( $sections as $sec_name => $section ) {
								if ( isset( $section['class'] ) ) {
									call_user_func( array( $section['class'], $section['function'] ) );
								} else {
									call_user_func( ( isset( $section['function'] ) ? $section['function'] : $section ) );
								}
							}
							$user_roles = $current_user->roles;
							$user_role = array_shift( $user_roles );
						?>
						<br />
						<p class="submit <?php echo ( 'status_settings' == $setting_tab ) ? 'display-none-cls' : ''; ?>">
							<button class="rounded_button arf_btn_dark_blue general_submit_button gnral-save-changes-btn" type="submit" ><?php echo esc_html__( 'Save Changes', 'arforms-form-builder' ); ?></button>
						</p>
						<br />
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php do_action( 'arforms_quick_help_links' ); ?>