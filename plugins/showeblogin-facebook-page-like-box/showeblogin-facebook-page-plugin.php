<?php
/*
Plugin Name: Showeblogin Social Plugin
Plugin URI: https://www.superwebtricks.com/facebook-page-wordpress-plugin
Description: Brings the power of simplicity to display Facebook Page Plugin (Like Box) into your WordPress Site.
Version: 6.7
Author: Suresh Prasad
Author URI: https://www.superwebtricks.com
License: GPLv3+

 * Copyright (C) 2022  Suresh Prasad  (email : spsmiter@gmail.com)

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
	any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $theme;

$swt_fb_page_defaults = array(
    'title' => 'Showeblogin Facebook Page',
    'url' => 'https://www.facebook.com/SuperWebTricks',
    'width' => '340',
    'height' => '500',
	'data_tabs' => 'timeline, events, messages', 
	'data_small_header' => 'false',
	'data_adapt_container_width' => 'true',
	'data_hide_cover' => 'false',
	'data_hide_cta' => 'false',
	'data_lazy' => 'false',
    'show_faces' => 'true',       
);

$theme->options['widgets_options']['facebook'] =  isset($theme->options['widgets_options']['facebook'])
    ? array_merge($swt_fb_page_defaults, $theme->options['widgets_options']['facebook'])
    : $swt_fb_page_defaults;
	
	function showeblogin_plugin_review_notice() {
	$screen = get_current_screen();		
		if( $screen->base === 'plugins' ){
    ?>
    <div id="showeblogin-facebook-page-plugin-review" class="updated notice is-dismissible">
        <p><?php _e( '<strong>Review: </strong> Please give some seconds to <a href="https://wordpress.org/support/view/plugin-reviews/showeblogin-facebook-page-like-box?rate=5#postform" target="_blank" title="Give your precious Feedback">Rate/Review Showeblogin Social plugin</a>. It means a lot for us.', 'spsmiter' ); ?></p>
    </div>
    <?php
		}
	}
add_action( 'admin_notices', 'showeblogin_plugin_review_notice' );         
add_action('widgets_init', function() {register_widget("ShowebloginFacebookPagePlugin");});
add_action( 'wp_enqueue_scripts', 'register_showeblogin_fbpage_plugin_styles' );
add_action( 'admin_enqueue_scripts', 'register_showeblogin_fbpage_plugin_admin_styles' );
function register_showeblogin_fbpage_plugin_styles() {
	wp_register_style( 'ShowebloginFbpagePluginStyle', plugins_url( 'css/style.css', __FILE__ ) );
	wp_enqueue_style( 'ShowebloginFbpagePluginStyle' );
}
function register_showeblogin_fbpage_plugin_admin_styles() {
	wp_register_style( 'ShowebloginFbpagePluginAdminStyle', plugins_url( 'css/admin-style.css', __FILE__ ) );
	wp_enqueue_style( 'ShowebloginFbpagePluginAdminStyle' );
}
class ShowebloginFacebookPagePlugin extends WP_Widget 
{
    function __construct() 
    {
        $widget_options = array('description' => __('Showeblogin Social Plugin for social widget. Enables Facebook Page owners to attract and gain Likes and share from their websites itself.', 'spsmiter') );
        $control_options = array( 'width' => 550);
		parent::__construct('spsmiter_facebook', '&raquo; Showeblogin Social', $widget_options, $control_options);
    }

    function widget($args, $instance)
    {
        global $wpdb, $theme;
        extract( $args );
        $instance = ! empty( $instance ) ? $instance : $theme->options['widgets_options']['facebook'];
        $title = apply_filters('widget_title', $instance['title']);
        $url = $instance['url'];
        $width = $instance['width'];
        $height = $instance['height'];
		$tabs= $instance['tabs'];
		$data_small_header = $instance['data_small_header'] == 'true' ? 'true' : 'false';
		$data_adapt_container_width = $instance['data_adapt_container_width'] == 'true' ? 'true' : 'false';
		$data_hide_cta = $instance['data_hide_cta'] == 'true' ? 'true' : 'false';
		$lazy = $instance['data_lazy'] == 'true' ? 'true' : 'false';
        $show_faces = $instance['show_faces'] == 'true' ? 'true' : 'false';        
        $data_hide_cover = $instance['data_hide_cover'] == 'true' ? 'true' : 'false';
		$language = $instance['language'];
        ?>
        <section id="showeblogin-widget-container" class="widget widget_facebook">		
		<!-- Showeblogin Social Plugin v6.6 - https://wordpress.org/plugins/showeblogin-facebook-page-like-box/ -->
        <?php  if ( $title ) {  ?> <h2 class="widget-title"><?php echo $title; ?></h2> <?php }  ?>
            <div id="fb-root"></div><script>(function(d, s, id) {  var js, fjs = d.getElementsByTagName(s)[0];  if (d.getElementById(id)) return;  js = d.createElement(s); js.id = id;  js.src = "//connect.facebook.net/<?php echo $language; ?>/sdk.js#xfbml=1&version=v18.0&appId=214112425590307&autoLogAppEvents=1";
  fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script>
			<div class="fb-page" data-href="<?php echo $url; ?>" data-tabs="<?php echo $tabs; ?>" data-small-header="<?php echo $data_small_header; ?>" data-adapt-container-width="<?php echo $data_adapt_container_width; ?>" data-hide-cta="<?php echo $data_hide_cta; ?>" data-hide-cover="<?php echo $data_hide_cover; ?>" data-show-facepile="<?php echo $show_faces; ?>" data-lazy="<?php echo $lazy; ?>" data-width="<?php echo $width; ?>" data-height="<?php echo $height; ?>"><div class="fb-xfbml-parse-ignore"><blockquote cite="<?php echo $url; ?>"><a href="https://www.superwebtricks.com/">SuperWebTricks</a> Loading...</blockquote></div></div>         
		<!-- Showeblogin Social Plugin HELP - https://www.superwebtricks.com/facebook-page-wordpress-plugin/ 26-05-2022 -->
		   </section>
     <?php
    }

    function update($new_instance, $old_instance) 
    {		
    	$instance = $old_instance;
    	$instance['title'] = strip_tags($new_instance['title']);
        $instance['url'] = strip_tags($new_instance['url']);
        $instance['width'] = strip_tags($new_instance['width']);
        $instance['height'] = strip_tags($new_instance['height']);
		$instance['tabs'] = strip_tags($new_instance['tabs']);
		$instance['data_small_header'] = strip_tags($new_instance['data_small_header']);
		$instance['data_adapt_container_width'] = strip_tags($new_instance['data_adapt_container_width']);
		$instance['data_hide_cta'] = strip_tags($new_instance['data_hide_cta']);
		$instance['data_lazy'] = strip_tags($new_instance['data_lazy']);
        $instance['show_faces'] = strip_tags($new_instance['show_faces']);        
        $instance['data_hide_cover'] = strip_tags($new_instance['data_hide_cover']);
		$instance['language'] = strip_tags($new_instance['language']);
        return $instance;
    }
    
    function form($instance) 
    {	
        global $theme;
		$instance = wp_parse_args( (array) $instance, $theme->options['widgets_options']['facebook'] );
        
        ?>        
            <div class="swt-fb-page-widget">
                <table width="100%">
                    <tr>
                        <td class="swt-fb-page-widget-label" width="30%"><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label><span title="The Title of Widget" class="help-desription">?</span></td>
                        <td class="swt-fb-page-widget-content" width="70%"><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></td>
                    </tr>                    
                    <tr>
                        <td class="swt-fb-page-widget-label"><label for="<?php echo $this->get_field_id('url'); ?>">Facebook Page URL:</label><span title="The URL of your Facebook Page" class="help-desription">?</span></td>
                        <td class="swt-fb-page-widget-content"><input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo esc_attr($instance['url']); ?>" /></td>
                    </tr>                    
                    <tr>
                        <td class="swt-fb-page-widget-label">Sizes:<span title="Width (Min. is 180 & Max. is 500) and Height (Min. is 70)" class="help-desription">?</span></td>
                        <td class="swt-fb-page-widget-content">
                            Width: <input type="text" style="width: 50px;" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo esc_attr($instance['width']); ?>" /> px. &nbsp; &nbsp;
                            Height: <input type="text" style="width: 50px;" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo esc_attr($instance['height']); ?>" /> px.
                        </td>
                    </tr>
					<tr>
                        <td class="swt-fb-page-widget-label">Tabs:<span title="Tabs to render i.e. timeline, events, messages." class="help-desription">?</span></td>
                        <td class="swt-fb-page-widget-content">
						<select name="<?php echo $this->get_field_name('tabs'); ?>">
                                <option value="" <?php selected('', $instance['tabs']); ?>>None</option>
								<option value="timeline" <?php selected('timeline', $instance['tabs']); ?>>Timeline</option>
                                <option value="messages"  <?php selected('messages', $instance['tabs']); ?>>Messages</option>
								<option value="events"  <?php selected('events', $instance['tabs']); ?>>Events</option>
								<option value="timeline,messages"  <?php selected('timeline,messages', $instance['tabs']); ?>>Timeline and Messages</option>
								<option value="timeline,events"  <?php selected('timeline,events', $instance['tabs']); ?>>Timeline and Events</option>
								<option value="messages,events"  <?php selected('messages,events', $instance['tabs']); ?>>Messages and Events</option>
								<option value="timeline,messages,events"  <?php selected('timeline,messages,events', $instance['tabs']); ?>>Timeline, Messages and Events</option>
                        </select>
						</td>						
                    </tr>
					<tr>
                        <td class="swt-fb-page-widget-label">Select Site Language:<span title="Choose local Language." class="help-desription">?</span></td>
                        <td class="swt-fb-page-widget-content">
							<select name="<?php echo $this->get_field_name('language'); ?>">
                                <option value="en_US" <?php selected('en_US', $instance['language']); ?>>English (US)</option>
								<option value="en_IN" <?php selected('en_IN', $instance['language']); ?>>English (India)</option>
								<option value="af_ZA" <?php selected('af_ZA', $instance['language']); ?>>Afrikaans</option>
								<option value="ak_GH" <?php selected('ak_GH', $instance['language']); ?>>Akan</option>
								<option value="sq_AL" <?php selected('sq_AL', $instance['language']); ?>>Albanian</option>
								<option value="am_ET" <?php selected('am_ET', $instance['language']); ?>>Amharic</option>
								<option value="ar_AR" <?php selected('ar_AR', $instance['language']); ?>>Arabic</option>
								<option value="hy_AM" <?php selected('hy_AM', $instance['language']); ?>>Armenian</option>
								<option value="as_IN" <?php selected('as_IN', $instance['language']); ?>>Assamese</option>
								<option value="ay_BO" <?php selected('ay_BO', $instance['language']); ?>>Aymara</option>
								<option value="az_AZ" <?php selected('az_AZ', $instance['language']); ?>>Azerbaijani</option>
								<option value="eu_ES" <?php selected('eu_ES', $instance['language']); ?>>Basque</option>
								<option value="be_BY" <?php selected('be_BY', $instance['language']); ?>>Belarusian</option>
								<option value="bn_IN" <?php selected('bn_IN', $instance['language']); ?>>Bengali</option>
								<option value="bs_BA" <?php selected('bs_BA', $instance['language']); ?>>Bosnian</option>
								<option value="br_FR" <?php selected('br_FR', $instance['language']); ?>>Breton</option>
								<option value="bg_BG" <?php selected('bg_BG', $instance['language']); ?>>Bulgarian</option>
								<option value="my_MM" <?php selected('my_MM', $instance['language']); ?>>Burmese</option>
								<option value="ca_ES" <?php selected('ca_ES', $instance['language']); ?>>Catalan</option>
								<option value="cx_PH" <?php selected('cx_PH', $instance['language']); ?>>Cebuano</option>
								<option value="ny_MW" <?php selected('ny_MW', $instance['language']); ?>>Chewa</option>								
								<option value="ck_US" <?php selected('ck_US', $instance['language']); ?>>Cherokee</option>
								<option value="zh_CN" <?php selected('zh_CN', $instance['language']); ?>>Chinese (Simplified China)</option>
								<option value="zh_HK" <?php selected('zh_HK', $instance['language']); ?>>Chinese (Traditional Hong Kong)</option>
								<option value="zh_TW" <?php selected('zh_TW', $instance['language']); ?>>Chinese (Traditional Taiwan)</option>
								<option value="co_FR" <?php selected('co_FR', $instance['language']); ?>>Corsican</option>
								<option value="hr_HR" <?php selected('hr_HR', $instance['language']); ?>>Croatian</option>
								<option value="cs_CZ" <?php selected('cs_CZ', $instance['language']); ?>>Czech</option>
								<option value="da_DK" <?php selected('da_DK', $instance['language']); ?>>Danish</option>
								<option value="nl_NL" <?php selected('nl_NL', $instance['language']); ?>>Dutch</option>
								<option value="nl_BE" <?php selected('nl_BE', $instance['language']); ?>>Dutch (Belgie)</option>
								<option value="eo_EO" <?php selected('eo_EO', $instance['language']); ?>>Esperanto</option>
								<option value="en_PI" <?php selected('en_PI', $instance['language']); ?>>English (Pirate)</option>
								<option value="en_GB" <?php selected('en_GB', $instance['language']); ?>>English (UK)</option>								
								<option value="en_UD" <?php selected('en_UD', $instance['language']); ?>>English (Upside Down)</option>
								<option value="et_EE" <?php selected('et_EE', $instance['language']); ?>>Estonian</option>
								<option value="fo_FO" <?php selected('fo_FO', $instance['language']); ?>>Faroese</option>
								<option value="tl_PH" <?php selected('tl_PH', $instance['language']); ?>>Filipino</option>
								<option value="fi_FI" <?php selected('fi_FI', $instance['language']); ?>>Finnish</option>								
								<option value="fr_CA" <?php selected('fr_CA', $instance['language']); ?>>French (Canada)</option>
								<option value="fr_FR" <?php selected('fr_FR', $instance['language']); ?>>French (France)</option>
								<option value="fy_NL" <?php selected('fy_NL', $instance['language']); ?>>Frisian</option>
								<option value="ff_NG" <?php selected('ff_NG', $instance['language']); ?>>Fulah</option>
								<option value="gl_ES" <?php selected('gl_ES', $instance['language']); ?>>Galician</option>
								<option value="lg_UG" <?php selected('lg_UG', $instance['language']); ?>>Ganda</option>
								<option value="ka_GE" <?php selected('ka_GE', $instance['language']); ?>>Georgian</option>
								<option value="de_DE" <?php selected('de_DE', $instance['language']); ?>>German</option>
								<option value="el_GR" <?php selected('el_GR', $instance['language']); ?>>Greek</option>
								<option value="gx_GR" <?php selected('gx_GR', $instance['language']); ?>>Greek (Classical)</option>
								<option value="gn_PY" <?php selected('gn_PY', $instance['language']); ?>>Guarani</option>
								<option value="gu_IN" <?php selected('gu_IN', $instance['language']); ?>>Gujarati</option>
								<option value="ja_JP" <?php selected('ja_JP', $instance['language']); ?>>Japanese</option>
								<option value="ja_KS" <?php selected('ja_KS', $instance['language']); ?>>Japanese (Kansai)</option>
								<option value="jv_ID" <?php selected('jv_ID', $instance['language']); ?>>Javanese</option>
								<option value="ht_HT" <?php selected('ht_HT', $instance['language']); ?>>Haitian Creole</option>
								<option value="ha_NG" <?php selected('ha_NG', $instance['language']); ?>>Hausa</option>
								<option value="he_IL" <?php selected('he_IL', $instance['language']); ?>>Hebrew</option>
								<option value="hi_IN" <?php selected('hi_IN', $instance['language']); ?>>Hindi</option>
								<option value="hu_HU" <?php selected('hu_HU', $instance['language']); ?>>Hungarian</option>
								<option value="is_IS" <?php selected('is_IS', $instance['language']); ?>>Icelandic</option>
								<option value="ig_NG" <?php selected('ig_NG', $instance['language']); ?>>Igbo</option>
								<option value="ga_IE" <?php selected('ga_IE', $instance['language']); ?>>Irish</option>
								<option value="id_ID" <?php selected('id_ID', $instance['language']); ?>>Indonesian</option>
								<option value="it_IT" <?php selected('it_IT', $instance['language']); ?>>Italian</option>
								<option value="ja_JP" <?php selected('ja_JP', $instance['language']); ?>>Japanese</option>								
								<option value="kn_IN" <?php selected('kn_IN', $instance['language']); ?>>Kannada</option>								
								<option value="kk_KZ" <?php selected('kk_KZ', $instance['language']); ?>>Kazakh</option>
								<option value="km_KH" <?php selected('km_KH', $instance['language']); ?>>Khmer</option>
								<option value="rw_RW" <?php selected('rw_RW', $instance['language']); ?>>Kinyarwanda</option>
								<option value="tl_ST" <?php selected('tl_ST', $instance['language']); ?>>Klingon</option>
								<option value="ko_KR" <?php selected('ko_KR', $instance['language']); ?>>Korean</option>
								<option value="ku_TR" <?php selected('ku_TR', $instance['language']); ?>>Kurdish (Kurmanji)</option>
								<option value="ky_KG" <?php selected('ky_KG', $instance['language']); ?>>Kyrgyz</option>
								<option value="lo_LA" <?php selected('lo_LA', $instance['language']); ?>>Lao</option>								
								<option value="la_VA" <?php selected('la_VA', $instance['language']); ?>>Latin</option>
								<option value="fb_LT" <?php selected('fb_LT', $instance['language']); ?>>Leet Speak</option>
								<option value="li_NL" <?php selected('li_NL', $instance['language']); ?>>Limburgish</option>
								<option value="ln_CD" <?php selected('ln_CD', $instance['language']); ?>>Lingala</option>
								<option value="lt_LT" <?php selected('lt_LT', $instance['language']); ?>>Lithuanian</option>
								<option value="lv_LV" <?php selected('lv_LV', $instance['language']); ?>>Latvian</option>
								<option value="mk_MK" <?php selected('mk_MK', $instance['language']); ?>>Macedonian</option>								
								<option value="mg_MG" <?php selected('mg_MG', $instance['language']); ?>>Malagasy</option>
								<option value="ms_MY" <?php selected('ms_MY', $instance['language']); ?>>Malay</option>								
								<option value="ml_IN" <?php selected('ml_IN', $instance['language']); ?>>Malayalam</option>
								<option value="mt_MT" <?php selected('mt_MT', $instance['language']); ?>>Maltese</option>
								<option value="mr_IN" <?php selected('mr_IN', $instance['language']); ?>>Marathi</option>
								<option value="mi_NZ" <?php selected('mi_NZ', $instance['language']); ?>>Māori</option>
								<option value="mn_MN" <?php selected('mn_MN', $instance['language']); ?>>Mongolian</option>
								<option value="nd_ZW" <?php selected('nd_ZW', $instance['language']); ?>>Ndebele</option>
								<option value="ne_NP" <?php selected('ne_NP', $instance['language']); ?>>Nepali</option>
								<option value="se_NO" <?php selected('se_NO', $instance['language']); ?>>Northern Sámi</option>
								<option value="nb_NO" <?php selected('nb_NO', $instance['language']); ?>>Norwegian (bokmal)</option>
								<option value="nn_NO" <?php selected('nn_NO', $instance['language']); ?>>Norwegian (nynorsk)</option>
								<option value="ne_NP" <?php selected('ne_NP', $instance['language']); ?>>Nepali</option>
								<option value="ps_AF" <?php selected('ps_AF', $instance['language']); ?>>Pashto</option>
								<option value="fa_IR" <?php selected('fa_IR', $instance['language']); ?>>Persian</option>			
								<option value="pl_PL" <?php selected('pl_PL', $instance['language']); ?>>Polish</option>
								<option value="pt_BR" <?php selected('pt_BR', $instance['language']); ?>>Portuguese (Brazil)</option>
								<option value="pt_PT" <?php selected('pt_PT', $instance['language']); ?>>Portuguese (Portugal)</option>
								<option value="pa_IN" <?php selected('pa_IN', $instance['language']); ?>>Punjabi</option>
								<option value="or_IN" <?php selected('or_IN', $instance['language']); ?>>Oriya</option>
								<option value="qu_PE" <?php selected('qu_PE', $instance['language']); ?>>Quechua</option>
								<option value="pt_PT" <?php selected('pt_PT', $instance['language']); ?>>Romansh</option>
								<option value="ro_RO" <?php selected('ro_RO', $instance['language']); ?>>Romanian</option>
								<option value="ru_RU" <?php selected('ru_RU', $instance['language']); ?>>Russian</option>
								<option value="sa_IN" <?php selected('sa_IN', $instance['language']); ?>>Sanskrit</option>
								<option value="sc_IT" <?php selected('sc_IT', $instance['language']); ?>>Sardinian</option>
								<option value="sr_RS" <?php selected('sr_RS', $instance['language']); ?>>Serbian</option>
								<option value="sn_ZW" <?php selected('sn_ZW', $instance['language']); ?>>Shona</option>
								<option value="si_LK" <?php selected('si_LK', $instance['language']); ?>>Sinhala</option>
								<option value="sz_PL" <?php selected('sz_PL', $instance['language']); ?>>Silesian</option>								
								<option value="sk_SK" <?php selected('sk_SK', $instance['language']); ?>>Slovak</option>
								<option value="sl_SI" <?php selected('sl_SI', $instance['language']); ?>>Slovenian</option>
								<option value="so_SO" <?php selected('so_SO', $instance['language']); ?>>Somali</option>
								<option value="cb_IQ" <?php selected('cb_IQ', $instance['language']); ?>>Sorani Kurdish</option>
								<option value="es_LA" <?php selected('es_LA', $instance['language']); ?>>Spanish</option>
								<option value="es_CL" <?php selected('es_CL', $instance['language']); ?>>Spanish (Chile)</option>
								<option value="es_CO" <?php selected('es_CO', $instance['language']); ?>>Spanish (Colombia)</option>
								<option value="es_MX" <?php selected('es_MX', $instance['language']); ?>>Spanish (Mexico)</option>
								<option value="es_ES" <?php selected('es_ES', $instance['language']); ?>>Spanish (Spain)</option>							
								<option value="es_VE" <?php selected('es_VE', $instance['language']); ?>>Spanish (Venezuela)</option>						
								<option value="sw_KE" <?php selected('sw_KE', $instance['language']); ?>>Swahili</option>
								<option value="sv_SE" <?php selected('sv_SE', $instance['language']); ?>>Swedish</option>
								<option value="sy_SY" <?php selected('sy_SY', $instance['language']); ?>>Syriac</option>
								<option value="tg_TJ" <?php selected('tg_TJ', $instance['language']); ?>>Tajik</option>								
								<option value="tz_MA" <?php selected('tz_MA', $instance['language']); ?>>Tamazight</option>
								<option value="ta_IN" <?php selected('ta_IN', $instance['language']); ?>>Tamil</option>
								<option value="tt_RU" <?php selected('tt_RU', $instance['language']); ?>>Tatar</option>
								<option value="te_IN" <?php selected('te_IN', $instance['language']); ?>>Telugu</option>
								<option value="th_TH" <?php selected('th_TH', $instance['language']); ?>>Thai</option>								
								<option value="tr_TR" <?php selected('tr_TR', $instance['language']); ?>>Turkish</option>
								<option value="tk_TM" <?php selected('tk_TM', $instance['language']); ?>>Turkmen</option>								
								<option value="uk_UA" <?php selected('uk_UA', $instance['language']); ?>>Ukrainian</option>
								<option value="ur_PK" <?php selected('ur_PK', $instance['language']); ?>>Urdu</option>
								<option value="uz_UZ" <?php selected('uz_UZ', $instance['language']); ?>>Uzbek</option>
								<option value="vi_VN" <?php selected('vi_VN', $instance['language']); ?>>Vietnamese</option>
								<option value="cy_GB" <?php selected('cy_GB', $instance['language']); ?>>Welsh</option>
								<option value="wo_SN" <?php selected('wo_SN', $instance['language']); ?>>Wolof</option>
								<option value="xh_ZA" <?php selected('xh_ZA', $instance['language']); ?>>Xhosa</option>
								<option value="yi_DE" <?php selected('yi_DE', $instance['language']); ?>>Yiddish</option>								
								<option value="yo_NG" <?php selected('yo_NG', $instance['language']); ?>>Yoruba</option>
								<option value="zu_ZA" <?php selected('zu_ZA', $instance['language']); ?>>Zulu</option>
								<option value="zz_TR" <?php selected('zz_TR', $instance['language']); ?>>Zazaki</option>
							</select>
						</td>						
                    </tr>					
                    <tr>
                        <td class="swt-fb-page-widget-label">Adapt Container Width:</td>
                        <td class="swt-fb-page-widget-content">
							<input type="checkbox" name="<?php echo $this->get_field_name('data_adapt_container_width'); ?>"  <?php checked('true', $instance['data_adapt_container_width']); ?> value="true" />  <?php _e('Fit to Widget Width', 'spsmiter'); ?>                 
                        </td>
                    </tr>					
					<tr>
                        <td class="swt-fb-page-widget-label">Call to Action:</td>
                        <td class="swt-fb-page-widget-content">
							<input type="checkbox" name="<?php echo $this->get_field_name('data_hide_cta'); ?>"  <?php checked('true', $instance['data_hide_cta']); ?> value="true" />  <?php _e('Hide Call to Action Button', 'spsmiter'); ?>                     
                        </td>
                    </tr>										
					<tr>
                        <td class="swt-fb-page-widget-label">Header Cover:</td>
                        <td class="swt-fb-page-widget-content">
							<input type="checkbox" name="<?php echo $this->get_field_name('data_hide_cover'); ?>"  <?php checked('true', $instance['data_hide_cover']); ?> value="true" />  <?php _e('Hide Header Cover Photo', 'spsmiter'); ?>                     
                        </td>
                    </tr>
					<tr>
                        <td class="swt-fb-page-widget-label">Small Header:</td>
                        <td class="swt-fb-page-widget-content">
							<input type="checkbox" name="<?php echo $this->get_field_name('data_small_header'); ?>"  <?php checked('true', $instance['data_small_header']); ?> value="true" />  <?php _e('Show Small Header', 'spsmiter'); ?>                     
                        </td>
                    </tr>
                    <tr>
                        <td class="swt-fb-page-widget-label">Show Friend's Faces:</td>
                        <td class="swt-fb-page-widget-content">
                            <input type="checkbox" name="<?php echo $this->get_field_name('show_faces'); ?>"  <?php checked('true', $instance['show_faces']); ?> value="true" />  <?php _e('Show Profile Photos when friends like', 'spsmiter'); ?>
                        </td>
                    </tr>
					<tr>
                        <td class="swt-fb-page-widget-label">Lazy Loading:</td>
                        <td class="swt-fb-page-widget-content">
							<input type="checkbox" name="<?php echo $this->get_field_name('data_lazy'); ?>"  <?php checked('true', $instance['data_lazy']); ?> value="true" />  <?php _e('Enable lazy-loading mechanism', 'spsmiter'); ?>                     
                        </td>
                    </tr>
					<tr>
                        <td class="swt-fb-page-widget-label">Shortcode:</td>
                        <td class="swt-fb-page-widget-content">
                            <b>You may also use this shortcode anywhere in your post and pages to show Likebox.<b><br />
							<br />Default: <code>[swt-fb-likebox]</code><br />
							Customize:<br /> <textarea rows="7" cols="40">[swt-fb-likebox url="https://www.facebook.com/SuperWebTricks" width="340" height="500" tabs="timeline,events,messages" hide_cover="false" show_faces="true" hide_call_action="false" small_header="false" adapt_container_width="true" data_lazy="true"]</textarea> 
                        </td>
                    </tr>
					<tr>
                        <td class="swt-widget-label">Showeblogin Help:</td>
                        <td class="swt-widget-content">
							<p><a href="https://wordpress.org/support/plugin/showeblogin-facebook-page-like-box" target="_blank" title="Ask any Query">Support</a> | <a href="https://www.superwebtricks.com/facebook-page-wordpress-plugin/" target="_blank" title="How to use - Step by Step Guide" >Tutorial Guide</a> | <a href="https://wordpress.org/support/view/plugin-reviews/showeblogin-facebook-page-like-box?rate=5#postform" target="_blank" title="Rate this plugin">Feedback</a>.</p>					
						</td>
                    </tr>					
                </table>
            </div>            
        <?php 
    }
}

function swt_fb_add_shortcode( $atts ) {
	extract( shortcode_atts( array(
			'url' => 'https://www.facebook.com/SuperWebTricks',
			'width' => '340',
			'height' => '500',
			'tabs' => 'timeline, events, messages',
			'hide_cover' => 'false',
			'show_faces' => 'true',
			'data_lazy' => 'true',
			'hide_call_action' => 'false',
			'small_header' => 'false',
			'adapt_container_width' => 'true'
	), $atts, 'swt-fb-likebox' ));
	$html='<div class="fb-page" data-href="'.$url.'" data-width="'.$width.'" data-height="'.$height.'" data-tabs="'.$tabs.'" data-hide-cover="'.$hide_cover.'" data-show-facepile="'.$show_faces.'" data-hide-cta="'.$hide_call_action.'" data-small-header="'.$small_header.'" data-lazy="'.$lazy.'" data-adapt-container-width="'.$adapt_container_width.'"></div>';
	return $html;
}
add_shortcode( 'swt-fb-likebox', 'swt_fb_add_shortcode' ); 
?>