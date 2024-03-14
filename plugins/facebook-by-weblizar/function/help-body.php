<?php if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly?>
<div class="block ui-tabs-panel " id="option-general">
	<div class="col-md-12">
		<div id="heading">
			<h2><?php esc_html_e('Social Like Box Shortcode Settings', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
			</h2>
		</div>
		<div class="row">
			<div class="col-md-6">
				<form name='fb-form' id='fb-form'>
					<?php $nonce = wp_create_nonce('facebook_shortcode_settings'); ?>
					<input type="hidden" name="security"
						value="<?php echo esc_attr($nonce); ?>">
					<p>
					<p><label class="ffp_font_bold"><?php esc_html_e('Facebook Page URL or ID : ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label>
					</p>
					<input class="widefat form-inline" id="facebook-page-url" name="facebook-page-url" type="text"
						value="<?php echo esc_attr($FacebookPageUrl); ?>">
					</p>
					<br>

					<p class="col-md-4 "><label class="ffp_font_bold"><?php esc_html_e('Show Faces : ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label>
						<select id="show-fan-faces" name="show-fan-faces" class="float-lg-right">
							<option value="true" <?php if ($ShowFaces == "true") {
    echo esc_attr("selected=selected");
} ?>><?php esc_html_e('Yes', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
							</option>
							<option value="false" <?php if ($ShowFaces == "false") {
    echo esc_attr("selected=selected");
} ?>><?php esc_html_e('No', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
							</option>
						</select>
					</p>
					<br>
					<p class="col-md-4">
						<label class="ffp_font_bold"><?php esc_html_e('Show Live Stream : ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label>
						<select id="show-live-stream" name="show-live-stream" class="float-lg-right">
							<option value="true" <?php if ($Stream == "true") {
    echo esc_attr("selected=selected");
} ?>><?php esc_html_e('Yes', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
							</option>
							<option value="false" <?php if ($Stream == "false") {
    echo esc_attr("selected=selected");
} ?>><?php esc_html_e('No', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
							</option>
						</select>
					</p>
					<br>
					<p>
					<p><label class="ffp_font_bold"><?php esc_html_e('Widget Width : ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label>
					</p>
					<!-- <input class="widefat" id="widget-width" name="widget-width" type="text" value="<?php echo esc_attr($Width); ?>"> -->
					<input class="widefat" id="widget-width" name="widget-width" type="number" value="<?php echo esc_attr($Width); ?>">
					</p>
					<br>
					<p>
					<p><label class="ffp_font_bold"><?php esc_html_e('Widget Height : ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label>
					</p>
					<input class="widefat" id="widget-height" name="widget-height" type="number" value="<?php echo esc_attr($Height); ?>">
					</p>
					<br>
					<p>
					<p><label class="ffp_font_bold"><?php esc_html_e('Facebook App ID : ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
							(<?php esc_html_e('Optional', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>)</label>
					</p>
					<input class="widefat" id="fb-app-id" name="fb-app-id" type="text"
						value="<?php echo esc_attr($FbAppId); ?>">
					<?php esc_html_e('Get Your Own Facebook APP Id ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>:
					<a href="http://weblizar.com/get-facebook-app-id/" target="_blank"><?php esc_html_e('HERE', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></a>
					</p>
					<br>
					<p>
					<p><label class="ffp_font_bold"><?php esc_html_e('Select Language for Like Button : ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></label>
					</p>
					<!-- <?php
                              if (!isset($locale_fb_like)) {
                                  wp_dropdown_languages($args = array());
                              } else {
                                  wp_dropdown_languages($args = array(
                                      'selected'     => $locale_fb_like,
                                  ));
                              }
                              
                          ?> -->
					<select name="weblizar_locale_fb" id="weblizar_locale_fb">
						<option value="af_ZA" <?php if ($weblizar_locale_fb == "af_ZA") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Afrikaans', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ar_AR" <?php if ($weblizar_locale_fb == "ar_AR") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Arabic', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="az_AZ" <?php if ($weblizar_locale_fb == "az_AZ") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Azerbaijani', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="be_BY" <?php if ($weblizar_locale_fb == "be_BY") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Belarusian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="bg_BG" <?php if ($weblizar_locale_fb == "bg_BG") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Bulgarian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="bn_IN" <?php if ($weblizar_locale_fb == "bn_IN") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Bengali', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="bs_BA" <?php if ($weblizar_locale_fb == "bs_BA") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Bosnian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ca_ES" <?php if ($weblizar_locale_fb == "ca_ES") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Catalan', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="cs_CZ" <?php if ($weblizar_locale_fb == "cs_CZ") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Czech', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="cy_GB" <?php if ($weblizar_locale_fb == "cy_GB") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Welsh', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="da_DK" <?php if ($weblizar_locale_fb == "da_DK") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Danish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="de_DE" <?php if ($weblizar_locale_fb == "de_DE") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('German', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="el_GR" <?php if ($weblizar_locale_fb == "el_GR") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Greek', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="en_GB" <?php if ($weblizar_locale_fb == "en_GB") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('English (UK)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="en_PI" <?php if ($weblizar_locale_fb == "en_PI") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('English (Pirate)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="en_UD" <?php if ($weblizar_locale_fb == "en_UD") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('English (Upside Down)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="en_US" <?php if ($weblizar_locale_fb == "en_US") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('English (US)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="eo_EO" <?php if ($weblizar_locale_fb == "eo_EO") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Esperanto', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="es_ES" <?php if ($weblizar_locale_fb == "es_ES") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Spanish (Spain)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="es_LA" <?php if ($weblizar_locale_fb == "es_LA") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Spanish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="et_EE" <?php if ($weblizar_locale_fb == "et_EE") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Estonian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="eu_ES" <?php if ($weblizar_locale_fb == "eu_ES") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Basque', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="fa_IR" <?php if ($weblizar_locale_fb == "fa_IR") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Persian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="fb_LT" <?php if ($weblizar_locale_fb == "fb_LT") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Leet Speak', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="fi_FI" <?php if ($weblizar_locale_fb == "fi_FI") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Finnish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="fo_FO" <?php if ($weblizar_locale_fb == "fo_FO") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Faroese', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="fr_CA" <?php if ($weblizar_locale_fb == "fr_CA") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('French (Canada)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="fr_FR" <?php if ($weblizar_locale_fb == "fr_FR") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('French (France)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="fy_NL" <?php if ($weblizar_locale_fb == "fy_NL") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Frisian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ga_IE" <?php if ($weblizar_locale_fb == "ga_IE") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Irish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="gl_ES" <?php if ($weblizar_locale_fb == "gl_ES") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Galician', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="he_IL" <?php if ($weblizar_locale_fb == "he_IL") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Hebrew', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="hi_IN" <?php if ($weblizar_locale_fb == "hi_IN") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Hindi', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="hr_HR" <?php if ($weblizar_locale_fb == "hr_HR") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Croatian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="hu_HU" <?php if ($weblizar_locale_fb == "hu_HU") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Hungarian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="hy_AM" <?php if ($weblizar_locale_fb == "hy_AM") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Armenian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="id_ID" <?php if ($weblizar_locale_fb == "id_ID") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Indonesian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="is_IS" <?php if ($weblizar_locale_fb == "is_IS") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Icelandic', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="it_IT" <?php if ($weblizar_locale_fb == "it_IT") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Italian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ja_JP" <?php if ($weblizar_locale_fb == "ja_JP") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Japanese', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ka_GE" <?php if ($weblizar_locale_fb == "ka_GE") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Georgian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="km_KH" <?php if ($weblizar_locale_fb == "km_KH") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Khmer', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ko_KR" <?php if ($weblizar_locale_fb == "ko_KR") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Korean', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ku_TR" <?php if ($weblizar_locale_fb == "ku_TR") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Kurdish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="la_VA" <?php if ($weblizar_locale_fb == "la_VA") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Latin', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="lt_LT" <?php if ($weblizar_locale_fb == "lt_LT") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Lithuanian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="lv_LV" <?php if ($weblizar_locale_fb == "lv_LV") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Latvian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="mk_MK" <?php if ($weblizar_locale_fb == "mk_MK") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Macedonian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ml_IN" <?php if ($weblizar_locale_fb == "ml_IN") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Malayalam', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ms_MY" <?php if ($weblizar_locale_fb == "ms_MY") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Malay', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="nb_NO" <?php if ($weblizar_locale_fb == "nb_NO") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Norwegian (bokmal)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ne_NP" <?php if ($weblizar_locale_fb == "ne_NP") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Nepali', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="nl_NL" <?php if ($weblizar_locale_fb == "nl_NL") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Dutch', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="nn_NO" <?php if ($weblizar_locale_fb == "nn_NO") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Norwegian (nynorsk)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="pa_IN" <?php if ($weblizar_locale_fb == "pa_IN") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Punjabi', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="pl_PL" <?php if ($weblizar_locale_fb == "pl_PL") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Polish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ps_AF" <?php if ($weblizar_locale_fb == "ps_AF") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Pashto', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="pt_BR" <?php if ($weblizar_locale_fb == "pt_BR") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Portuguese (Brazil)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="pt_PT" <?php if ($weblizar_locale_fb == "pt_PT") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Portuguese (Portugal)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ro_RO" <?php if ($weblizar_locale_fb == "ro_RO") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Romanian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ru_RU" <?php if ($weblizar_locale_fb == "ru_RU") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Russian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="sk_SK" <?php if ($weblizar_locale_fb == "sk_SK") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Slovak', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="sl_SI" <?php if ($weblizar_locale_fb == "sl_SI") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Slovenian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="sq_AL" <?php if ($weblizar_locale_fb == "sq_AL") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Albanian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="sr_RS" <?php if ($weblizar_locale_fb == "sr_RS") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Serbian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="sv_SE" <?php if ($weblizar_locale_fb == "sv_SE") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Swedish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="sw_KE" <?php if ($weblizar_locale_fb == "sw_KE") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Swahili', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="ta_IN" <?php if ($weblizar_locale_fb == "ta_IN") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Tamil', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="te_IN" <?php if ($weblizar_locale_fb == "te_IN") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Telugu', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="th_TH" <?php if ($weblizar_locale_fb == "th_TH") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Thai', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="tl_PH" <?php if ($weblizar_locale_fb == "tl_PH") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Filipino', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="tr_TR" <?php if ($weblizar_locale_fb == "tr_TR") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Turkish', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="uk_UA" <?php if ($weblizar_locale_fb == "uk_UA") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Ukrainian', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="vi_VN" <?php if ($weblizar_locale_fb == "vi_VN") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Vietnamese', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="zh_CN" <?php if ($weblizar_locale_fb == "zh_CN") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Simplified Chinese (China)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="zh_HK" <?php if ($weblizar_locale_fb == "zh_HK") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Traditional Chinese (Hong Kong)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
						<option value="zh_TW" <?php if ($weblizar_locale_fb == "zh_TW") {
                              echo esc_attr('selected="selected"');
                          } ?>
							><?php esc_html_e('Traditional Chinese (Taiwan)', 'WEBLIZAR_FACEBOOK_TEXT_DOMAIN'); ?>
						</option>
					</select>
					</p>
					<br>
					<p>
						<input onclick="return SaveSettings();" type="button" class="button button-primary button-hero"
							id="fb-save-settings" name="fb-save-settings"
							value="<?php esc_attr_e('SAVE', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>">
					</p>
					<p>
					<div id="fb-img" style="display: none;"><img
							src="<?php echo esc_url(WEBLIZAR_FACEBOOK_PLUGIN_URL.'images/loading.gif'); ?>" />
					</div>
					<div id="fb-msg" style="display: none;" class="alert">
						<?php esc_html_e('Settings successfully saved. Reloading page for generating preview below.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
					</div>
					</p>
					<br>
				</form>
			</div>
			<div class="col-md-6">
				<?php
                 if ($FbAppId && $FacebookPageUrl) { ?>
				<div id="heading">
					<h2>
						<?php esc_html_e('Social Likebox "[FBW]" Shortcode Preview', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
					</h2>
				</div>
				<p>
				<div id="fb-root"></div>
				<script>
					(function(d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0];
						if (d.getElementById(id)) return;
						js = d.createElement(s);
						js.id = id;
						js.src =
							"//connect.facebook.net/<?php echo esc_attr($weblizar_locale_fb); ?>/sdk.js#xfbml=1&appId=<?php echo esc_attr($FbAppId); ?>&version=v2.0";
						fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));
				</script>
				<div class="fb-like-box"
					data-small-header="<?php echo esc_attr($Header); ?>"
					data-height="<?php echo esc_attr($Height); ?>"
					data-href="<?php echo esc_url($FacebookPageUrl); ?>"
					data-show-border="<?php echo esc_attr($ShowBorder); ?>"
					data-show-faces="<?php echo esc_attr($ShowFaces); ?>"
					data-stream="<?php echo esc_attr($Stream); ?>"
					data-width="<?php echo esc_attr($Width); ?>"
					data-force-wall="<?php echo esc_attr($ForceWall); ?>">
				</div>
				</p>
				<?php } ?>
			</div>
		</div>
	</div>
</div>


<!-- need help tab -->
<div class="block ui-tabs-panel deactive" id="option-needhelp">
	<div class="col-md-12">
		<div id="heading">
			<h2><?php esc_html_e('Social LikeBox & Feed', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
			</h2>
		</div>
	</div>
	<div class="col-md-12">
		<div class="col-md-6 col-xl-6">
			<p><strong><?php esc_html_e('Social Page Like Box', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong>
			</p>
			<hr>
			<p><strong>1 - <?php esc_html_e('Social Like Box Widget', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong>
			</p>
			<p><strong>2 - <?php esc_html_e('Social Like Box Short-code', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
					[FBW]</strong></p>
			<hr>
			<p><?php esc_html_e('You can use the widget to display your Facebook Like Box in any theme Widget Sections', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>.
			</p>
			<p><?php esc_html_e('Simple go to your', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
				<a
					href="<?php echo get_site_url(); ?>/wp-admin/widgets.php">
					<strong><?php esc_html_e('Widgets', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong></a>
				<?php esc_html_e('section and activate available', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
				<strong>"<?php esc_html_e('Social Like Box', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>"
				</strong>
				<?php esc_html_e('widget in any sidebar section, like in left sidebar, right sidebar or footer sidebar', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
				.
			</p>
			<br><br>

			<p><strong><?php esc_html_e('Social Like Box Short-Code', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
					[FBW]</strong></p>
			<hr>
			<p><strong>[FBW]</strong> <?php esc_html_e('Shortcode give ability to display Facebook Like Box in any Page / Post with content', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.
			</p>
			<p><?php esc_html_e('To use shortcode, just copy ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?><strong>[FBW]</strong>
				<?php esc_html_e('shortcode and paste into content editor of any Page / Post', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.
			</p>
		</div>
		<div class="col-md-6">
			<p><strong><?php esc_html_e('Social Page Feed', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong>
			</p>
			<hr>
			<p><strong>1 - <?php esc_html_e('Social Page Feed Widget', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong>
			</p>
			<p><strong>2 - <?php esc_html_e('Social Page Feed Short-Code', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
					[facebook_feed]</strong></p>
			<hr>
			<p><?php esc_html_e('You can use the widget to display your Facebook Page Feed in any theme Widget Sections', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.
			</p>
			<p><?php esc_html_e('Simple go to your', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
				<a
					href="<?php echo get_site_url(); ?>/wp-admin/widgets.php"><strong><?php esc_html_e('Widgets', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong></a>
				<?php esc_html_e('section and activate available', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
				<strong><?php esc_html_e('Social Feed & Like Box', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong>
				<?php esc_html_e('widget in any sidebar section, like in left sidebar, right sidebar or footer sidebar', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
				.
			</p>
			<br><br>
			<p><strong><?php esc_html_e('Social Page Feed Short-Code', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
					[facebook_feed]</strong></p>
			<hr>
			<p><strong>[facebook_feed]</strong> <?php esc_html_e('shortcode give ability to display Facebook Like Box in any Page / Post with content', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.
			</p>
			<p><?php esc_html_e('To use shortcode, just copy ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?><strong>[facebook_feed]</strong>
				<?php esc_html_e('shortcode and paste into content editor of any Page / Post', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.
			</p>
		</div>
		<div class="col-md-12 col-xl-12">
			<br><br>
			<p><strong>Q. <?php esc_html_e('What is Facebook Page URL', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
					?</strong></p>
			<p><strong> Ans. <?php esc_html_e('Facebook Page URL', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
				</strong> <?php esc_html_e('is your Facebook page your where you promote your business. Here your customers, clients, friends, guests can like, share, comment review your POST', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.
			</p>
			<br><br>
			<p><strong>Q. <?php esc_html_e('What is Facebook APP ID', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
					?</strong></p>
			<p><strong>Ans. <?php esc_html_e('Facebook Application ID', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></strong>
				<?php esc_html_e(' used to authenticate your Facebook Page data & settings. To get your own Facebook APP ID please read our 4 Steps very simple and easy ', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
				<a href="http://weblizar.com/get-facebook-app-id/" target="_blank"><strong> <?php esc_html_e(' Tutorial', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>.</strong></a>
			</p>
		</div>
	</div>
</div>
<!-- our product tab -->
<div class="block ui-tabs-panel deactive" id="option-upgradetopro">
	<div class="row-fluid pricing-table pricing-three-column">
		<div id="get_pro-settings" class="container-fluid top get_pro-settings">
			<div class="col-md-12 form-group cs-back">
				<div class="ms-links">
					<div class="cs-top">
						<h2> <?php esc_html_e('Pro Plugin', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
						</h2>
					</div>
					<div class="row">
						<ul class="cs-desc col-md-6  col-sm-12 col-xs-12">
							<li> <?php esc_html_e('Unlimited Profile, Page & Group Feeds', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
							</li>
							<li> <?php esc_html_e('Unlimited Feeds Per Page/Post', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
							</li>
							<li><?php esc_html_e('Light-Box Layouts 9+', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
							</li>
							<li><?php esc_html_e('Tons of Feed Short-Code', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
							</li>
							<li><?php esc_html_e('Specific Content Facebook Feeds', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
							</li>
							<li><?php esc_html_e('Many Loading & Hover CSS Effect', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
							</li>
							<li><?php esc_html_e('Auto-Update Feeds', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
							</li>
						</ul>
						<ul class="cs-desc col-md-6  col-sm-12 col-xs-12">
							<li><?php esc_html_e('Top Level & Stream Type Comment Display', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
							</li>
							<li><?php esc_html_e('Sharing On Social Media', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
							</li>
							<li><?php esc_html_e('No Code Require', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
							</li>
							<li><?php esc_html_e('Feed Widgets', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
							</li>
							<li><?php esc_html_e('Like & Share Button For Each Feed in Like-box', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
							</li>
							<li><?php esc_html_e('Fast & Friendly Support', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?>
							</li>
							<li><?php esc_html_e('Fully Responsive And Optimized', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
							</li>
						</ul>
					</div>
					<div class="col-md-12 row link-cont">
						<div class="col-md-4 col-sm-4 ms-btn">
							<b><?php esc_html_e('Try Live Demo', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></b>
							<a class="btn" target="_blank" href="http://demo.weblizar.com/facebook-feed-pro/"
								rel="nofollow"><?php esc_html_e('Click Here', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></a>
						</div>
						<div class="col-md-4 col-sm-4 ms-btn">
							<b><?php esc_html_e('Try Before Buy Using Admin Demo', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></b>
							<a class="btn" target="_new" href="http://demo.weblizar.com/facebook-feed-pro-admin/"
								rel="nofollow"><?php esc_html_e('Click Here', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></a>
							<br><span><b>Username:</b> userdemo</span><br><span><b>Password:</b> userdemo</span>
						</div>
						<div class="col-md-4 col-sm-4 ms-btn">
							<a href="https://weblizar.com/plugins/facebook-feed-pro/" target="_blank"
								class="button-face"><?php esc_html_e('Buy Now ($19)', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
