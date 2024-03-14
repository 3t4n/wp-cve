<?php
defined( 'ABSPATH' ) || exit;
/**
 * Facebook Script
 *
 * @package YAHMAN Add-ons
 */

function yahman_addons_facebook_script(){

	
	if(isset( $GLOBALS['wp_scripts']->registered[ 'yahman_addons_facebook_script' ] )) return;

	$option = get_option('yahman_addons');

	
	if(!isset($option['sns_account']['facebook_script'])){
		return;
	}

	$fb_lang = yahman_addons_facebook_lang(get_locale());

	$facebook_app_id = isset($option['sns_account']['facebook_app_id']) ? $option['sns_account']['facebook_app_id'] : '';
	?>
	<div id="fb-root"></div>
	<?php
  /*
  <script async defer crossorigin="anonymous" src="https://connect.facebook.net/<?php echo $fb_lang; ?>/sdk.js#xfbml=1&version=v10.0&appId=<?php echo $facebook_app_id; ?>&autoLogAppEvents=1" nonce="<?php echo yahman_addons_rand_nonce(8); ?>"></script>
  */
  
  wp_register_script( 'yahman_addons_facebook_script','');

  //wp_enqueue_script( 'yahman_addons_facebook_script'  );
  //wp_add_inline_script( 'yahman_addons_facebook_script', '(function(e,d){function a(){var f=e.createElement("script");f.type="text/javascript";f.async=true;f.defer=true;f.nonce="'.yahman_addons_rand_nonce(8).'";f.src="https://connect.facebook.net/'.$fb_lang.'/sdk.js#xfbml=1&version=v10.0&appId='.$facebook_app_id.'&autoLogAppEvents=1";var g=e.getElementsByTagName("script")[0];g.parentNode.insertBefore(f,g);}var c=false;function b(){if(c===false){c=true;d.removeEventListener("scroll",b);d.removeEventListener("mousemove",b);d.removeEventListener("mousedown",b);d.removeEventListener("touchstart",b);a();}}d.addEventListener("scroll",b);d.addEventListener("mousemove",b);d.addEventListener("mousedown",b);d.addEventListener("touchstart",b);d.addEventListener("load",function(){if(e.documentElement.scrollTop!=0||e.body.scrollTop!=0){b();}});})(document,window);' );


}

function yahman_addons_facebook_lang($lang_code){
	$fb_locales = array(
        'es_ES', 'en_US', 'fr_FR', 'tr_TR', 'sv_SE', // prefered codes are moved to line
        'af_ZA', 'sq_AL', 'ar_AR', 'hy_AM', 'ay_BO', 'az_AZ', 'eu_ES', 'be_BY', 'bn_IN', 'bs_BA', 'bg_BG', 'ca_ES', 'ck_US',
        'hr_HR', 'cs_CZ', 'da_DK', 'nl_NL', 'nl_BE', 'en_PI', 'en_GB', 'en_UD', 'eo_EO', 'et_EE', 'fo_FO', 'tl_PH', 'fi_FI',
        'fb_FI', 'fr_CA', 'gl_ES', 'ka_GE', 'de_DE', 'el_GR', 'gn_PY', 'gu_IN', 'he_IL', 'hi_IN', 'hu_HU', 'is_IS', 'id_ID',
        'ga_IE', 'it_IT', 'ja_JP', 'jv_ID', 'kn_IN', 'kk_KZ', 'km_KH', 'tl_ST', 'ko_KR', 'ku_TR', 'la_VA', 'lv_LV', 'fb_LT', 'li_NL',
        'lt_LT', 'mk_MK', 'mg_MG', 'ms_MY', 'ml_IN', 'mt_MT', 'mr_IN', 'mn_MN', 'ne_NP', 'se_NO', 'nb_NO', 'nn_NO', 'ps_AF', 'fa_IR',
        'pl_PL', 'pt_BR', 'pt_PT', 'pa_IN', 'qu_PE', 'ro_RO', 'rm_CH', 'ru_RU', 'sa_IN', 'sr_RS', 'zh_CN', 'sk_SK', 'sl_SI', 'so_SO',
        'es_LA', 'es_CL', 'es_CO', 'es_MX', 'es_VE', 'sw_KE', 'sy_SY', 'tg_TJ', 'ta_IN', 'tt_RU', 'te_IN', 'th_TH',
        'zh_HK', 'zh_TW', 'uk_UA', 'ur_PK', 'uz_UZ', 'vi_VN', 'cy_GB', 'xh_ZA', 'yi_DE', 'zu_ZA'
    );
	foreach($fb_locales as $fbl){
		if(stripos($fbl,$lang_code)!==false){
			return $fbl;
		}
	}

	return 'en_US';
}

function yahman_addons_rand_nonce($length) {
	$str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
	$r_str = null;
	for ($i = 0; $i < $length; $i++) {
		$r_str .= $str[rand(0, count($str) - 1)];
	}
	return $r_str;
}
