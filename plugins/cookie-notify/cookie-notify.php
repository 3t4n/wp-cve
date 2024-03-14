<?php
/*
  Plugin Name:		Cookie Notify
  Version:			1.0.1
  Description:		Cookie usage notification. Quick and easy configuration of position, appearance, colors, links, buttons and text content.
  Author:			Piotr Markowski
  Author URI:		http://jakwylaczyccookie.pl
  Text Domain:		cookie-notify
  Domain Path:		/languages
  License URI:		http://www.apache.org/licenses/LICENSE-2.0
  License:			Apache-2.0
  
  Copyright 2017 Piotr Markowski http://jakwylaczyccookie.pl

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

	http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License.
  
 */
 
 

 
 
  /* --------------------------------------------------- */
  /* INSTALLATION / ACTIVATION / DEACTIVATION / DELETION */
  /* --------------------------------------------------- */
  
  
/* exit if accessed directly */
if ( ! defined( 'ABSPATH' ) )
	exit;
  
function zp20_cnpl_install() {
	
	/* Random values for how to link text */
	load_plugin_textdomain( 'cookie-notify', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
	$link_howto_random[] = __('How to disable cookie files', 'cookie-notify'); /*  Jak wyłączyć pliki cookie */
	$link_howto_random[] = __('How to disable cookies', 'cookie-notify'); /*  Jak wyłączyć cookies  */
	$link_howto_random[] = __('Learn more', 'cookie-notify'); /*  Dowiedz się więcej  */
	$link_howto_random[] = __('More', 'cookie-notify'); /*  Więcej  */
	$link_howto_random[] = __('Information about cookies', 'cookie-notify'); /*  Informacje o cookies  */
	$link_howto_random[] = __('How to manage cookies', 'cookie-notify'); /*  Zarządzanie plikami cookies  */
	$link_howto_random[] = __('Manage cookies', 'cookie-notify'); /*  Zarządzanie cookies  */
	$link_howto_random[] = __('Cookie files', 'cookie-notify'); /*  Pliki cookies  */
	$link_howto_random[] = __('About cookies', 'cookie-notify'); /*  O cookies  */
	$link_howto_random[] = __('About cookies files', 'cookie-notify'); /*  O ciasteczkach  */
	$link_howto_random[] = __('Cookie', 'cookie-notify'); /*  Cookie  */
	$link_howto_random[] = __('Cookies', 'cookie-notify'); /*  Ciasteczka  */
	$link_howto_random[] = __('How to turn off cookies', 'cookie-notify'); /*  Jak wyłączyć ciasteczka  */

	/* Random values for regulation link text */
	
	$link_regulation_random[] = __('Privacy Policy', 'cookie-notify'); /*  'Polityka Prywatności'; */
	$link_regulation_random[] = __('Cookie Policy', 'cookie-notify'); /*  'Polityka Cookies'; */
	$link_regulation_random[] = __('How do we use cookies', 'cookie-notify'); /*  'Jak używamy Cookies'; */
	$link_regulation_random[] = __('How do we use cookie files', 'cookie-notify'); /*  'Jak używamy Ciasteczek'; */
	$link_regulation_random[] = __('Cookie usage', 'cookie-notify'); /*  'Jak wykorzystujemy Cookies'; */
	$link_regulation_random[] = __('Usage of Cookies', 'cookie-notify'); /*  'Wykorzystywanie plików Cookie'; */
	$link_regulation_random[] = __('Regulations', 'cookie-notify'); /*  'Regulamin'; */
	$link_regulation_random[] = __('Terms of service', 'cookie-notify'); /*  'Regulamin Serwisu'; */
	$link_regulation_random[] = __('Legal note', 'cookie-notify'); /*  'Nota prawna'; */
	
	/* Random values for information text */
	
	$content_random[] = __('We use cookie files.', 'cookie-notify'); /* 'Wykorzystujemy pliki cookies.';	 */
	$content_random[] = __('This website use cookie files.', 'cookie-notify'); /* 'Ta strona używa plików cookies.';	 */
	$content_random[] = __('Our website use cookie files.', 'cookie-notify'); /* 'Nasze strony wykorzystują pliki cookies.';	 */
	$content_random[] = __('We use cookies', 'cookie-notify'); /* 'Wykorzystujemy cisteczka.';	 */
	$content_random[] = __('This website use cookies.', 'cookie-notify'); /* 'Ta strona używa ciasteczek';	 */
	$content_random[] = __('Our pages use cookies', 'cookie-notify'); /* 'Nasze strony wykorzystują cisteczka.';	 */
	if(get_bloginfo('language') == 'pl'||get_bloginfo('language') == 'pl-PL'){
		$content_random[] = 'Wykorzystujemy pliki cookies (cisteczka).';	 
		$content_random[] = 'Ta strona używa plików cookies (cisteczka).';	 
		$content_random[] = 'Nasze strony wykorzystują pliki cookies (cisteczka).';	
	}

					 

	add_option( 'cnpl_last_active_panel', 'cnpl_position_panel');	/* Turn position panel active */
	add_option( 'cnpl_text_panel_opened', '0');	/* If 0 alert will be shown to user to make modyfication in default links urls */
	add_option( 'cnpl_position', 'topright');	/* Set top left corner by default */
	
	add_option( 'cnpl_content_text', $content_random[rand(0,count($content_random)-1)]); /* set random content text */
	add_option( 'cnpl_content_link_regulation_br', '1'); /* set break line before regulation link by default */
	add_option( 'cnpl_content_link_regulation_text', $link_regulation_random[rand(0,count($link_regulation_random)-1)]); /* set random regulation link text */
	add_option( 'cnpl_content_link_regulation_url', ((get_bloginfo('language') == 'pl'||get_bloginfo('language') == 'pl-PL') ? 'http://jakwylaczyccookie.pl/generator-polityki-cookie/' : 'http://cookienotify.com/private-policy-generator.html')); /* set default regulation link url (can be changed anytime) */
	add_option( 'cnpl_content_link_howto_br', '1'); /* set break line before how to link by default */
	add_option( 'cnpl_content_link_howto_text', $link_howto_random[rand(0,count($link_howto_random)-1)]); /* set random how to link text */
	add_option( 'cnpl_content_link_howto_url', ((get_bloginfo('language') == 'pl'||get_bloginfo('language') == 'pl-PL') ? 'http://jakwylaczyccookie.pl/jak-wylaczyc-pliki-cookies/' : 'http://cookienotify.com/how-to-disable-cookies.html'));  /* set default how to link url (can be changed anytime) */
	add_option( 'cnpl_content_button_text', __('I ACCEPT', 'cookie-notify')); /* Set accept button text by default */
	
	
	add_option( 'cnpl_theme_css_box_inner', 'padding:10px; margin-left:15px; margin-right:15px; font-size:14px; font-weight:normal;');
	add_option( 'cnpl_theme_css_box_outer', 'display: block; z-index: 99999; min-height: 35px; width: 300px; position: fixed; background: rgb(245, 245, 245); border-width: 5px 1px 1px; border-style: solid; border-image: initial; border-color: rgb(139, 195, 74); text-align: center; color: rgb(119, 119, 119); border-radius: 15px; box-shadow: black 0px 8px 6px -6px; top: 10px; right: 10px;');
	add_option( 'cnpl_theme_css_button', 'position: relative; background: rgb(139, 195, 74); color: rgb(255, 255, 255); padding: 5px 15px; text-decoration: none; font-size: 12px; font-weight: normal; border: 0px solid rgb(245, 245, 245); border-radius: 0px;');
	add_option( 'cnpl_theme_css_links', 'color: rgb(139, 195, 74);');
	add_option( 'cnpl_theme_css_text', 'color: rgb(0, 0, 0);');
	
	add_option( 'cnpl_settings_time', '7'); /* 7 day memmory */
	add_option( 'cnpl_on_off', '0'); /* pugin is not showing notice by default - user must turn it on */
	
	add_option('cnpl_remember_border', 'border-width: 5px 1px 1px; border-style: solid; border-image: initial; border-color: rgb(139, 195, 74);');
	add_option('cnpl_remember_corners', 'border-radius: 15px;');
	add_option('cnpl_remember_button', 'position: relative; background: rgb(255, 152, 0); color: rgb(255, 255, 255); padding: 5px 15px; text-decoration: none; font-size: 12px; font-weight: normal; border: 0px solid rgb(255, 243, 224); border-radius: 0px;');
	
	add_option('cnpl_html', ''); /* Combined HTML code (frontend cookie notice html code) */
	add_option('cnpl_js', ''); /* Combined JavaScript code (frontend cookie notice JS code) */
	add_option('cnpl_css', ''); /* Combined CSS style (frontend cookie notice CSS style) */
	
	/* redirect variable */
	add_option('my_plugin_do_activation_redirect', true);
	
	add_option('cnpl_form_hash');
	
}	

register_activation_hook(__FILE__, 'zp20_cnpl_install');


/* REDIRECT AFTER ACTIVATION */
function zp20_cnpl_redirect() {
    if (get_option('my_plugin_do_activation_redirect', false)) {
        delete_option('my_plugin_do_activation_redirect');
		$url = admin_url('admin.php?page=cnpl_settings');
        wp_redirect($url);
    }
}
add_action('admin_init', 'zp20_cnpl_redirect');




function zp20_cnpl_uninstall(){
	
	/* remove all data from db when deactivated */
	
	delete_option( 'cnpl_last_active_panel');	
	delete_option( 'cnpl_position');
	delete_option( 'cnpl_text_panel_opened' );
	
	delete_option( 'cnpl_content_text');
	delete_option( 'cnpl_content_link_regulation_br'); 
	delete_option( 'cnpl_content_link_regulation_text');
	delete_option( 'cnpl_content_link_regulation_url');
	delete_option( 'cnpl_content_link_howto_br');
	delete_option( 'cnpl_content_link_howto_text');
	delete_option( 'cnpl_content_link_howto_url');
	delete_option( 'cnpl_content_button_text');
	
	
	delete_option( 'cnpl_theme_css_box_outer');
	delete_option( 'cnpl_theme_css_box_inner');
	delete_option( 'cnpl_theme_css_button');
	delete_option( 'cnpl_theme_css_links');
	delete_option( 'cnpl_theme_css_text');
	
	delete_option( 'cnpl_settings_time');
	delete_option( 'cnpl_on_off');
	
	delete_option('cnpl_remember_border');
	delete_option('cnpl_remember_corners');
	delete_option('cnpl_remember_button');
	
	delete_option('cnpl_html');
	delete_option('cnpl_js');
	delete_option('cnpl_css');
	delete_option('cnpl_form_hash');
	
}
register_deactivation_hook(__FILE__, 'zp20_cnpl_uninstall');




  /* --------------------------------------------------- */
  /*                       FRONTEND                      */
  /* --------------------------------------------------- */
  
  
  
function zp20_cnpl_frontEndInlineCSS() {
	/* SMALL INLINE CSS IS BETTER THEN LOADING SMALL CSS FILE - PERFORMANCE IN LOADING SITE - PAGESPEED WILL BE HAPPY */
	echo '<style type="text/css">'.htmlspecialchars_decode(stripslashes_deep(get_option('cnpl_css'))).'</style>';
}
function zp20_cnpl_frontEndHTML() {
	
	/*ECHO HTML IN FOOTER - UNFORTUNATLY THERE IS NO PRETTY SOLUTION TO PUBLISH THE CODE JUST AFTER BODY TAG - IT COULD NOT WORK ALSO WIHT ALL THEMES - IT HAVE TO BE FOOTER THEN :/ */
	echo "<!-- COOKIE NOTIFY &copy; ".((get_bloginfo('language') == 'pl'||get_bloginfo('language') == 'pl-PL') ? 'http://jakwylaczyccookie.pl/' : 'http://cookienotify.com/')." Piotr Markowski -->";
	echo htmlspecialchars_decode(stripslashes_deep(get_option('cnpl_html')));
	
	/* IT IS IMPORTANT TO INJECT JS AFTER HTML*/
	/* SMALL INLINE CSS IS BETTER THEN LOADING SMALL CSS FILE - PERFORMANCE IN LOADING SITE - PAGESPEED WILL BE HAPPY */
	echo '<script>';
	echo htmlspecialchars_decode(stripslashes_deep(get_option('cnpl_js')));
	echo "</script>";
	echo "<!-- END COOKIE NOTIFIY-->";
}
if(get_option('cnpl_on_off')==1){
	
	/* IF NOTIVE VIEW IS TURNED ON */
	/* PRINT FRONTEND CODE */
	
	add_action( 'wp_print_styles', 'zp20_cnpl_frontEndInlineCSS' );
	add_action( 'wp_footer', 'zp20_cnpl_frontEndHTML' );
	
}






  /* --------------------------------------------------- */
  /*                       BACKEND                       */
  /* --------------------------------------------------- */


  
  
/* CODE THAT NEED TO BE EXECUTED AFTER PLUGINS ARE LOADED (DEPENDENCIES) */
function zp20_cnpl_afterPluginsLoaded(){
	if(current_user_can( 'manage_options' )) { 
	
		/* if user can manage_option */
		/* add menu*/
		
		add_action('admin_menu', 'zp20_cnpl_plugin_menu');
		
		/* NOTE! Plugin settings will not be avaible for any user that dont have rights to manage options of website */
	}
}
/* LANGUAGES */
function zp20_cnpl_load_translation(){
	load_plugin_textdomain( 'cookie-notify', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
function zp20_cnpl_plugin_menu() {
	
	if(current_user_can( 'manage_options' )) {
		add_menu_page('Cookie Notify', 'Cookie Notify', 'administrator', 'cnpl_settings', 'zp20_cnpl_display_settings', plugins_url('images/plugin-icon.png',__FILE__));
	}
	
}

/* execute function after all plugins are loaded */
/* to check user right with current_user_can you have to wait for all functions to be loaded. This function is in pluggable.php file which is loaded after plugins. !!DO NOT INCLUDE IT IN PLUGIN!! */ 

add_action( 'plugins_loaded', 'zp20_cnpl_afterPluginsLoaded' );
add_action( 'init', 'zp20_cnpl_load_translation');

function zp20_cnpl_update_settings(){
	
	/* ATTENCTION! THIS FUNCTION SHOULD BE FIRED BEFORE NEW NONCE IS GENERATED */
	
	/* SAVE DATA TO DATABASE WHEN SAVE BUTTON OR TURN ON/OFF BUTTON ARE CLICKED */

	/* CHECK NONCE */
	if(current_user_can( 'manage_options' )) {
		if(check_admin_referer('update-options-'.get_option('cnpl_form_hash'))){
			
			/* backend variables */
		
			update_option('cnpl_last_active_panel', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_last_active_panel']),'a-z_',25)); /* max 25 length (If changes tab name should verify length again) (a-z_) */
			update_option('cnpl_text_panel_opened', (int) sanitize_text_field($_POST['cnpl_text_panel_opened'])); /* integer */
			update_option('cnpl_position', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_position']),'a-z',12)); /* a-z max 12 */
			update_option('cnpl_content_text', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_content_text']),'HTML',512)); /* html max 512 */
			update_option('cnpl_content_link_regulation_br', (int) sanitize_text_field($_POST['cnpl_content_link_regulation_br'])); /* integer */
			update_option('cnpl_content_link_regulation_text', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_content_link_regulation_text']),'HTML',256)); /* html max 256 */
			update_option('cnpl_content_link_regulation_url', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_content_link_regulation_url']),'URL',256)); /* URL max 256 */
			update_option('cnpl_content_link_howto_br', (int) sanitize_text_field($_POST['cnpl_content_link_howto_br'])); /* integer */
			update_option('cnpl_content_link_howto_text', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_content_link_howto_text']),'HTML',256));/* html max 256 */
			update_option('cnpl_content_link_howto_url', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_content_link_howto_url']),'URL',256)); /* URL max 256 */
			update_option('cnpl_content_button_text', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_content_button_text']),'HTML',256)); /* html max 256 */
			update_option('cnpl_theme_css_box_outer', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_theme_css_box_outer']),'CSS',1024)); /* css max 1024 */
			update_option('cnpl_theme_css_box_inner', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_theme_css_box_inner']),'CSS',1024)); /* css max 1024 */
			update_option('cnpl_theme_css_button', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_theme_css_button']),'CSS',1024));/* css max 1024 */
			update_option('cnpl_theme_css_links', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_theme_css_links']),'CSS',1024));/* css max 1024 */
			update_option('cnpl_theme_css_text', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_theme_css_text']),'CSS',1024));/* css max 1024 */
			update_option('cnpl_settings_time', (int) sanitize_text_field($_POST['cnpl_settings_time'])); /* integer */
			update_option('cnpl_on_off', (int) sanitize_text_field($_POST['cnpl_on_off'])); /* integer */
			update_option('cnpl_remember_border', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_remember_border']),'CSS',1024)); /* css max 1024 */
			update_option('cnpl_remember_corners', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_remember_corners']),'CSS',1024)); /* css max 1024 */
			update_option('cnpl_remember_button', zp20_cnpl_validate(sanitize_text_field($_POST['cnpl_remember_button']),'CSS',1024)); /* css max 1024 */
			
			/* frontend code */
			
			update_option('cnpl_js', zp20_cnpl_validate(sanitize_textarea_field(esc_html($_POST['cnpl_js'])),'JS',2048)); /* js max 2048 */
			update_option('cnpl_css', zp20_cnpl_validate(sanitize_textarea_field(esc_html($_POST['cnpl_css'])),'CSS',5120)); /* css max 5120 */
			
			/* NOTICE! replace on the fly is needed to hack Google Chrome protection against sendig "javascript:" string in input / textarea */
			update_option('cnpl_html', zp20_cnpl_validate(str_replace("{{jasc}}","javascript:",sanitize_textarea_field(esc_html($_POST['cnpl_html']))),'HTML',10240)); /* html max 10240 */
			
			return true;
		}
		else{
			return false;
		}
	}
	else{
		return false;
	}
}



/* CUSTOM FUNCTION TO CHECK TYPE AND LENGTH*/
function zp20_cnpl_validate($val, $type, $length){

	if(strlen($val)>$length){
		exit;
	} else if($type=="HTML"||$type=="CSS"||$type=="JS"){
		/* NO FAST VALIDATION */
		/* REQUIRE SOME WORK IN FUTURE */
	}  else if($type=="URL"){
		/* CHECKING WHOLE URL */
		if (filter_var($val, FILTER_VALIDATE_URL) !== false){} else {
			/* CHECKING LINK IN THE SAME DOMAIN*/
			if (filter_var("http://test.com".$val, FILTER_VALIDATE_URL) !== false){} else {
				exit;
			}
		}
	} else if (!preg_match('/^['.$type.']*$/',$val)){
		// Warning - this may case errors if some wrong $val will be places in function
		exit;
	}
	return $val;
}

function zp20_cnpl_display_settings() {
	
  /* --------------------------------------------------- */
  /*            BACKEND MAIN FUNCTIONALITY               */
  /* --------------------------------------------------- */
	
	/* SAVE DATA IF ISSET POST */

	if(isset($_POST['cnpl_last_active_panel'])){
		if(!zp20_cnpl_update_settings()){
			/* NONCE / PERMISSIONS */
			exit;
		}
	}
	
	/* INLINE STYLE (To small plugin for creating structured filesystem) */
	$css = '
	<style type="text/css">
		
		.cnpl { /* wrapper */ 
			width:800px
		}
		.cnpl .inline {
			display:inline;
		}
		.cnpl .w60 {
			width:60px;
		}
		.w180 {
			width:180px;
		}
		.cnpl .wAll{
			width:100%;
		}
		.cnpl .h100{
			height:100px
		}
		.cnpl .h200{
			height:200px
		}
		.cnpl .p10 {
			padding:10px;
		}

		.cnpl .nodecoration{
			text-decoration:none;
		}
		.cnpl .text-center {
			text-align:center;
		}
		.cnpl .clear {
			clear:both
		}
		.cnpl .column180{
			display:inline; width:180px; float:left; margin-right:10px;
		}
		.cnpl .column180 a{
			text-decoration:none;
		}
	
		.cnpl .palette {
			margin-bottom:10px; height:60px; width:100%; 
		}
		.cnpl .paletteText {
			line-height:40px; font-size:10px;padding-left:10px; color:#000;
		}
		.cnpl .paletteTextWhite {
			line-height:40px; font-size:10px;padding-left:10px; color:#FFF;
		}
		.cnpl .paletteLink {
			line-height:40px; font-size:10px;padding-left:10px;
		}
		.cnpl .paletteButton {
			position:relative; float:right; margin-right:10px; margin-top:30px; text-align:center; width:30px; height:23px; font-size:9px;
		}
		.cnpl .plateEmpy {
			border:1px solid #7E57C2; height:60px; background-color:#EDE7F6;
		}
		.cnpl .noborder {
			border:0px
		}
		.cnpl .lbtn
		{
			text-decoration:none;
			outline: none;
			display:inline;
			margin:25px;
		}
		.cnpl .lbtn:hover
		{
			cursor:pointer;
		}
		.cnpl .lbtn img
		{
			outline: none;
		}
		.cnpl .lbtn:focus
		{
			outline: none;
		}   
		.cnpl .cnpl_container{
			border:1px solid #CCC;
			background-color:#FFF;
			margin-top:-1px;
			margin-bottom:0px;
			padding:7px;
		}
		.cnpl .menu_item{
			border:1px solid #CCC;
			background-color:#DDD;
			padding:10px;
			line-height:3em;
			text-decoration:none;
			border-bottom:0;
		}
		.cnpl .menu_item.active{
			border:1px solid #CCC;
			background-color:#FFF;
			border-bottom:1px solid #FFF;
		}
		.cnpl .menu_item i::before{
			margin-top:10px;
		}
		
		.cnpl .menu_item_bottom{
			border:1px solid #CCC;
			background-color:#DDD;
			padding:10px;
			line-height:3em;
			text-decoration:none;
		}
		.cnpl .menu_item_bottom.active{
			border:1px solid #CCC;
			background-color:#FFF;
			border-top:1px solid #FFF;
		}
		.cnpl .menu_item_bottom i::before{
			margin-top:10px;
		}
		
		
		.cnpl .panel{
			display:none;
		}
		.cnpl .panel.active{
			display:block;
		}
		.cnpl .panel_button{
			margin-bottom:-1px;
			margin-top:-1px;
		}
		.cnpl .cnpl_header,
		.cnpl .cnpl_footer{
			
		}
		.cnpl .panel_button:hover{
			cursor:pointer;
		}
		.cnpl select{
			color:#0073AA;
			font-weight:bold;
		}
		.cnpl blockquote h3{
			margin-top:0px;
		}
		.cnpl blockquote{
			padding:20px;
			background-color:#f3f3f3;
			border:1px solid #DDD;
		}
		.cnpl .panel_button_save{
			background-color:#DBFCCE;
			color:#0F2E03;
			float:right;
			line-height: 1.1em;
		}
		
		.cnpl .panel_button_save.blink,
		.cnpl .panel_button_save2.blink{
			margin-left:5px;
		}
		.cnpl .panel_button_save.menu_item_bottom,
		.cnpl .panel_button_turnoff.menu_item_bottom
		{
			margin-top:2px;
		}
		
		.cnpl .panel_button_turnoff{
			background-color:#F5B9B9;
			color:#C00;
			float:right;
			line-height: 1.1em;
			margin-left:5px;
		}
		
		.cnpl .panel_button_turnoff2{
			background-color:#F5B9B9;
			color:#C00;
			float:right;
			line-height: 1.1em;
			margin-left:5px;
			display:none;
		}
		
		.cnpl .panel_button_save2{
			background-color:#DBFCCE;
			color:#0F2E03;
			float:right;
			line-height: 1.1em;
			display:none;
		}
		.cnpl_facebook{
			position:absolute;
			margin-left:700px;
			margin-top:7px;
		}
		.cnpl div.error {
			margin:0px;
			padding-left:45px;
		}
		.cnpl div.error .dashicons-warning{
			font-size:36px;
			color:#C00;
			margin-left:-40px;
			position:absolute; 
			margin-top:10px
		}
		@media screen and (max-width: 900px) {
			.cnpl .panel{
				display:block;
			}
			.cnpl .cnpl_header,
			.cnpl .cnpl_footer{
				display:none; 
			}
			.cnpl {
				width:100%;
			}
			.cnpl .panel_button_save2,
			.cnpl .panel_button_turnoff2{
				display:block;
			}
			.cnpl .fb {
				display:none;
			}
			
		}
	</style>
	';
	/* FACEBOOK LIKE BUTTON */
	$fbinit = '<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v2.9&appId=228382620542919";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>';
	
	$fb = '<div class="fb-like" data-href="https://facebook.com/jakwylaczyccookies/" data-layout="box_count" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>';
	
	/* FORM HASH PROTECTION FOR NONCE */
	/* !! This will prevent to open plugin settings in many tabs / windows also */
	
	$hash = md5(time());
	$html = "".$hash;
	update_option('cnpl_form_hash',$hash);
	
	/* BACKEND HTML */
	
	$html = '
	
	<div class="cnpl">
		<form id="cnpl_form" action="admin.php?page=cnpl_settings" method="post" name="options">
			' .  wp_nonce_field('update-options-'.$hash) . '
			<!--<input type="hidden" name="action" value="update'.__('Update').'" /> -->
			<input type="hidden" id="cnpl_last_active_panel" name="cnpl_last_active_panel" value="' . esc_attr(get_option('cnpl_last_active_panel')) . '" />
			<input type="hidden" id="cnpl_text_panel_opened" name="cnpl_text_panel_opened" value="' . esc_attr(get_option('cnpl_text_panel_opened')) . '" />
			
			<input type="hidden" id="remember_border" name="remember_border" value="' . esc_attr(get_option('cnpl_remember_border')) . '" />
			<input type="hidden" id="remember_corners" name="remember_corners" value="' . esc_attr(get_option('cnpl_remember_corners')) . '" />
			
			<input type="hidden" id="cnpl_position" name="cnpl_position" value="' . esc_attr(get_option('cnpl_position')) . '" />
			<input type="hidden" id="cnpl_content_text" name="cnpl_content_text" value="' . esc_attr(get_option('cnpl_content_text')) . '" />
			<input type="hidden" id="cnpl_content_link_regulation_br" name="cnpl_content_link_regulation_br" value="' . esc_attr(get_option('cnpl_content_link_regulation_br')) . '" />
			<input type="hidden" id="cnpl_content_link_regulation_text" name="cnpl_content_link_regulation_text" value="' . esc_attr(get_option('cnpl_content_link_regulation_text')) . '" />
			<input type="hidden" id="cnpl_content_link_regulation_url" name="cnpl_content_link_regulation_url" value="' . esc_attr(get_option('cnpl_content_link_regulation_url')) . '" />
			<input type="hidden" id="cnpl_content_link_howto_br" name="cnpl_content_link_howto_br" value="' . esc_attr(get_option('cnpl_content_link_howto_br')) . '" />
			<input type="hidden" id="cnpl_content_link_howto_text" name="cnpl_content_link_howto_text" value="' . esc_attr(get_option('cnpl_content_link_howto_text')) . '" />
			<input type="hidden" id="cnpl_content_link_howto_url" name="cnpl_content_link_howto_url" value="' . esc_attr(get_option('cnpl_content_link_howto_url')) . '" />
			<input type="hidden" id="cnpl_content_button_text" name="cnpl_content_button_text" value="' . esc_attr(get_option('cnpl_content_button_text')) . '" />
			<input type="hidden" id="cnpl_theme_css_box_outer" name="cnpl_theme_css_box_outer" value="' . esc_attr(get_option('cnpl_theme_css_box_outer')) . '" />
			<input type="hidden" id="cnpl_theme_css_box_inner" name="cnpl_theme_css_box_inner" value="' . esc_attr(get_option('cnpl_theme_css_box_inner')) . '" />
			<input type="hidden" id="cnpl_theme_css_button" name="cnpl_theme_css_button" value="' . esc_attr(get_option('cnpl_theme_css_button')) . '" />
			<input type="hidden" id="cnpl_theme_css_links" name="cnpl_theme_css_links" value="' . esc_attr(get_option('cnpl_theme_css_links')) . '" />
			<input type="hidden" id="cnpl_theme_css_text" name="cnpl_theme_css_text" value="' . esc_attr(get_option('cnpl_theme_css_text')) . '" />
			<input type="hidden" id="cnpl_settings_time" name="cnpl_settings_time" value="' . esc_attr(get_option('cnpl_settings_time')) . '" />
			<input type="hidden" id="cnpl_on_off" name="cnpl_on_off" value="' . esc_attr(get_option('cnpl_on_off')) . '" />
			
			<div class="cnpl_facebook">'.$fb.'</div>
			<br /><h1 style="margin-top:15px; margin-bottom:10px;">Cookie Notify</h1><br />
			<hr />
			';
	
	if(get_option('cnpl_text_panel_opened') != '1'){
		$html .= '<div class="error">
			<i class="dashicons dashicons-warning"></i> 
			<p>';
			$html .= __('There are default link values in the CONTENT settings.', 'cookie-notify'); /* W ustawieniach TREŚCI znajdują się domyślne odnośniki do stron informacyjnych. */
			$html .= "<br />";
			$html .= __('Go to ', 'cookie-notify'); /*  Przejdź do  */
			$html .= '<a href="javascript:cnpl_changePanel(\'cnpl_content_panel\');">';
			$html .= __('CONTENT tab ', 'cookie-notify'); /*  panelu TREŚCI  */
			$html .= '</a>';
			$html .= __('and if necessary insert your own links. ', 'cookie-notify'); /*  i wprowadź odpwiednie zmiany jeśli będą potrzebne.  */
			$html .= '</p>
		</div><hr />';
	}

	
	$tabs[] = __('POSITION', 'cookie-notify'); /* POŁOŻENIE */
	$tabs[] = __('COLORS', 'cookie-notify'); /* KOLORY */
	$tabs[] = __('APPEARANCE', 'cookie-notify'); /* WYGLĄD */
	$tabs[] = __('CONTENT', 'cookie-notify'); /* TREŚĆ */
	$tabs[] = __('ADVANCED', 'cookie-notify'); /* ZAAWANSOWANE */
	$tabs[] = __('SAVE', 'cookie-notify'); /* ZAPISZ */
	$tabs[] = __('TURN ON', 'cookie-notify'); /* WŁĄCZ */
	$tabs[] = __('TURN OFF', 'cookie-notify'); /* WYŁĄCZ */
	$html .= '
			<div class="cnpl_header">
			
				<span onclick="javascript:cnpl_changePanel(\'cnpl_position_panel\');" class="'.((get_option('cnpl_last_active_panel') == 'cnpl_position_panel') ? 'active' : '').' panel_button cnpl_position_panel_button menu_item"><i class="dashicons-before dashicons-welcome-widgets-menus"></i> '.$tabs[0].'</span>
				<span onclick="javascript:cnpl_changePanel(\'cnpl_colors_panel\');" class="'.((get_option('cnpl_last_active_panel') == 'cnpl_colors_panel') ? 'active' : '').' panel_button cnpl_colors_panel_button menu_item"><i class="dashicons-before dashicons-art"></i> '.$tabs[1].'</span>
				<span onclick="javascript:cnpl_changePanel(\'cnpl_theme_panel\');" class="'.((get_option('cnpl_last_active_panel') == 'cnpl_theme_panel') ? 'active' : '').' panel_button cnpl_theme_panel_button menu_item"><i class="dashicons-before dashicons-admin-appearance"></i> '.$tabs[2].'</span>
				<span onclick="javascript:cnpl_changePanel(\'cnpl_content_panel\');" class="'.((get_option('cnpl_last_active_panel') == 'cnpl_content_panel') ? 'active' : '').' panel_button cnpl_content_panel_button menu_item"><i class="dashicons-before dashicons-format-aside"></i> '.$tabs[3].'</span>
				<span onclick="javascript:cnpl_changePanel(\'cnpl_advanced_panel\');" class="'.((get_option('cnpl_last_active_panel') == 'cnpl_advanced_panel') ? 'active' : '').' panel_button cnpl_advanced_panel_button menu_item"><i class="dashicons-before dashicons-admin-settings"></i> '.$tabs[4].'</span>
				<span onclick="javascript:turnOnOff();" class="panel_button panel_button_'.((get_option('cnpl_on_off') == '1') ? 'turnoff' : 'save blink').' menu_item"><i class="fa fa-power-off"></i> '.((get_option('cnpl_on_off') == '1') ? ''.$tabs[7].'' : ''.$tabs[6].'').'</span>
				<span onclick="javascript:submitForm();" class="panel_button panel_button_save menu_item"><i class="fa fa-save"></i> '.$tabs[5].'</span>
			</div>
			<div class="cnpl_container">
				
				<!-- POSITION PANEL -->
				<div class="panel '.((get_option('cnpl_last_active_panel') == 'cnpl_position_panel') ? 'active' : '').'  cnpl_position_panel">
				
					<!-- POSITION SETTING -->
					<h3 class="text-center">'.__('Select the location of the cookie notification', 'cookie-notify').'</h3>
					<div class="inline">
						<div class="lbtn" onclick="javascript:noticeposition(\'topleft\');" class="p25 inline">
							<img id="cnpl_w_topleft" src="'.plugins_url('images/powiadomienie-cookie-top-left.png',__FILE__).'"  />
						</div>
					</div>
					<div class="inline">
						<div class="lbtn" onclick="javascript:noticeposition(\'top\');" class="p25 inline">
							<img id="cnpl_w_top" src="'.plugins_url('images/powiadomienie-cookie-top.png',__FILE__).'"  />
						</div>
					</div>
					
					<div class="inline">
						<div class="lbtn" onclick="javascript:noticeposition(\'topright\');" class="p25 inline">
							<img id="cnpl_w_topright" src="'.plugins_url('images/powiadomienie-cookie-top-right.png',__FILE__).'" />
						</div>
					</div>
					<br />
					<div class="inline">
						<div class="lbtn" onclick="javascript:noticeposition(\'bottomleft\');" class="p25 inline">
							<img id="cnpl_w_bottomleft" src="'.plugins_url('images/powiadomienie-cookie-bottom-left.png',__FILE__).'"  />
						</div>
					</div>
					<div class="inline">
						<div class="lbtn" onclick="javascript:noticeposition(\'bottom\');" class="p25 inline">
							<img id="cnpl_w_bottom" src="'.plugins_url('images/powiadomienie-cookie-bottom.png',__FILE__).'"/>
						</div>
					</div>
					<div class="inline">
						<div class="lbtn" onclick="javascript:noticeposition(\'bottomright\');" class="p25 inline">
							<img id="cnpl_w_bottomright" src="'.plugins_url('images/powiadomienie-cookie-bottom-right.png',__FILE__).'" />
						</div>
					</div>
				</div>
				
				<!-- COLORS PANEL -->
				<div class="panel '.((get_option('cnpl_last_active_panel') == 'cnpl_colors_panel') ? 'active' : '').' cnpl_colors_panel">
				
					<!-- COLOR PALETTE SETTING -->
					<h3 class="text-center">'.__('Select the color palette for the cookie notification', 'cookie-notify').'</h3>
					<div class="column180" >
						<a href="javascript:noticepalete(\'#E3F2FD\',\'#1E88E5\',\'#000\',\'#FFF\')">
							<div class="palette" style="border:1px solid #1E88E5; background-color:#E3F2FD">
								<span class="paletteText">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#1E88E5;">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#1E88E5; color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#FBE9E7\',\'#FF5722\',\'#000\',\'#FFF\')">
							<div class="palette" style="border:1px solid #FF5722; background-color:#FBE9E7">
								<span class="paletteText">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#FF5722">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#FF5722;color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#E8F5E9\',\'#4CAF50\',\'#000\',\'#FFF\')">
							<div class="palette" style="border:1px solid #4CAF50; background-color:#E8F5E9">
								<span class="paletteText">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#4CAF50">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#4CAF50; color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#FFF3E0\',\'#FF9800\',\'#000\',\'#FFF\')">
							<div class="palette" style="border:1px solid #FF9800; background-color:#FFF3E0">
								<span class="paletteText">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#FF9800">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#FF9800; color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#E0F7FA\',\'#00BCD4\',\'#000\',\'#FFF\')">
							<div class="palette" style="border:1px solid #00BCD4; background-color:#E0F7FA">
								<span class="paletteText">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#00BCD4">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#00BCD4; color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#FCE4EC\',\'#E91E63\',\'#000\',\'#FFF\')">
							<div class="palette" style="border:1px solid #E91E63; background-color:#FCE4EC">
								<span class="paletteText">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#E91E63">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#E91E63; color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#F5F5F5\',\'#9C27B0\',\'#000\',\'#FFF\')">
							<div class="palette" style="border:1px solid #9C27B0; background-color:#F5F5F5">
								<span class="paletteText">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#9C27B0">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#9C27B0; color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#EDE7F6\',\'#7E57C2\',\'#000\',\'#FFF\')">
							<div class="palette" style="border:1px solid #7E57C2; background-color:#EDE7F6">
								<span class="paletteText">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#7E57C2">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#7E57C2; color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#E0F2F1\',\'#009688\',\'#000\',\'#FFF\')">
							<div class="palette" style="border:1px solid #009688; background-color:#E0F2F1">
								<span class="paletteText">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#009688">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#009688; color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#F5F5F5\',\'#8BC34A\',\'#000\',\'#FFF\')">
							<div class="palette" style="border:1px solid #8BC34A; background-color:#F5F5F5">
								<span class="paletteText">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#8BC34A">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#8BC34A; color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#EFEBE9\',\'#795548\',\'#000\',\'#FFF\')">
							<div class="palette" style="border:1px solid #795548; background-color:#EFEBE9">
								<span class="paletteText">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#795548">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#795548; color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#EEEECC\',\'#CC0000\',\'#000\',\'#FFF\')">
							<div class="palette" style="border:1px solid #CC0000; background-color:#EEEECC">
								<span class="paletteText">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#CC0000">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#CC0000; color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#2B3643\',\'#A0B2C0\',\'#FFF\',\'#000\')">
							<div class="palette" style="border:1px solid #A0B2C0; background-color:#2B3643">
								<span class="paletteTextWhite">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#A0B2C0">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#A0B2C0; color:#000; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#1F1F1F\',\'#C6C6C6\',\'#FFF\',\'#000\')">
							<div class="palette" style="border:1px solid #C6C6C6; background-color:#1F1F1F">
								<span class="paletteTextWhite">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#C6C6C6">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#C6C6C6; color:#000; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#270808\',\'#F0ADAD\',\'#FFF\',\'#000\')">
							<div class="palette" style="border:1px solid #F0ADAD; background-color:#270808">
								<span class="paletteTextWhite">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#F0ADAD">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#F0ADAD; color:#000; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div class="column180">
						<a href="javascript:noticepalete(\'#1C0521\',\'#F0D1F5\',\'#FFF\',\'#000\')">
							<div class="palette" style="border:1px solid #F0D1F5; background-color:#1C0521">
								<span class="paletteTextWhite">'.__('Message text', 'cookie-notify').'</span>
								<span class="paletteLink" style="color:#F0D1F5">'.__('Link', 'cookie-notify').'</span>
								<div style="background:#F0D1F5; color:#000; " class="paletteButton">
									BTN
								</div>
							</div>
						</a> 
					</div>
					<div style="clear:both"></div>
				</div>
				
				<!-- THEME PANEL -->
				<div class="panel '.((get_option('cnpl_last_active_panel') == 'cnpl_theme_panel') ? 'active' : '').' cnpl_theme_panel">
					
					<!-- COOKIE NOTICE CORNER SETTING -->
					<h3 class="text-center">'.__('CORNERS', 'cookie-notify').'</h3>
					<div class="column180">
						<a href="javascript:noticecorner(\'0\')">
							<div class="plateEmpy" style="border-radius:0px; "></div>
						</a>
					</div>
					<div class="column180">
						<a href="javascript:noticecorner(\'5\')">
							<div class="plateEmpy" style="border-radius:5px; "></div>
						</a>
					</div>
					<div class="column180">
						<a href="javascript:noticecorner(\'10\')">
							<div class="plateEmpy" style="border-radius:10px; "></div>
						</a>
					</div>
					<div class="column180">
						<a href="javascript:noticecorner(\'15\')">
							<div class="plateEmpy" style="border-radius:15px; "></div>
						</a>
					</div>
					<div style="clear:both"></div>
					<br />
					<br />
					
					
					
					<!-- COOKIE NOTICE SHADOW SETTING -->
					<h3 class="text-center">'.__('SHADOW', 'cookie-notify').'</h3>
					<div class="column180">
						<a href="javascript:noticeshadow(\'\')">
							<div class="plateEmpy"></div>
						</a>
					</div>
					<div class="column180">
						<a href="javascript:noticeshadow(\'0 8px 6px -6px black\')">
							<div class="plateEmpy" style="-webkit-box-shadow: 0 8px 6px -6px black;  -moz-box-shadow: 0 8px 6px -6px black;  box-shadow: 0 8px 6px -6px black; "></div>
						</a>
					</div>			
					<div class="column180">
						<a href="javascript:noticeshadow(\'2px 2px 3px 0px rgba(0, 0, 0, 0.6)\')">
							<div class="plateEmpy" style="-moz-box-shadow:2px 2px 3px 0px rgba(0, 0, 0, 0.6);  -webkit-box-shadow: 2px 2px 3px 0px rgba(0, 0, 0, 0.6);  box-shadow:2px 2px 3px 0px rgba(0, 0, 0, 0.6); "></div>
						</a>
					</div>
					<div class="column180">
						<a href="javascript:noticeshadow(\'0px 0px 4px 1px rgba(0, 0, 0, 0.8)\')">
							<div class="plateEmpy" style="-moz-box-shadow:0px 0px 4px 1px rgba(0, 0, 0, 0.8);  -webkit-box-shadow: 0px 0px 4px 1px rgba(0, 0, 0, 0.8);  box-shadow:0px 0px 4px 1px rgba(0, 0, 0, 0.8); "></div>
						</a>
					</div>
					<div style="clear:both"></div>
					<br />
					<br />
					
					
					
					<!-- COOKIE NOTICE BORDER SETTING -->
					<h3 class="text-center">'.__('BORDER', 'cookie-notify').'</h3>
					<div class="column180">
						<a href="javascript:noticeborder(\'border:0; border-top:0\')">
							<div class="plateEmpy noborder"></div>
						</a>
					</div>
					<div class="column180">
						<a href="javascript:noticeborder(\'border-top:0; border:1px solid\')">
							<div class="plateEmpy noborder" style="border-top:0; border:1px solid #7E57C2; "></div>
						</a>
					</div>
					<div class="column180">
						<a href="javascript:noticeborder(\'border:1px solid; border-top:5px solid\')">
							<div class="plateEmpy " style="border-top:5px solid #7E57C2; "></div>
						</a>
					</div>
					<div class="column180">
						<a href="javascript:noticeborder(\'border-top:2px outset; border:2px outset\')">
							<div class="plateEmpy noborder" style="border:2px outset #7E57C2; "></div>
						</a>
					</div>
					<div style="clear:both"></div>
					<br />
					<br />
					
					
					<!-- COOKIE NOTICE BUTTON SETTING -->
					<h3 class="text-center">'.__('BUTTON', 'cookie-notify').'</h3>
					<div class="column180">
						<a href="javascript:noticebutton(\'1\')">
							<div class="plateEmpy">
								<div style="background:#7E57C2; color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a>
					</div>
					<div class="column180">
						<a href="javascript:noticebutton(\'2\')">
							<div class="plateEmpy" style="border-radius:5px; border:1px solid #7E57C2; ">
								<div style="border-radius:5px; background:#7E57C2; color:#FFF; " class="paletteButton">
									BTN
								</div>
							</div>
						</a>
					</div>
					<div class="column180">
						<a href="javascript:noticebutton(\'3\')">
							<div class="plateEmpy">
								<div style="border:1px solid #7E57C2; background:#FFF; color:#7E57C2; " class="paletteButton">
									BTN
								</div>
							</div>
						</a>
					</div>
					<div class="column180">
						<a href="javascript:noticebutton(\'4\')">
							<div class="plateEmpy" style="border-radius:5px; border:1px solid #7E57C2; ">
								<div style="border:1px solid #7E57C2; border-radius:5px; background:#FFF; color:#7E57C2; " class="paletteButton">
									BTN
								</div>
							</div>
						</a>
					</div>
					<div style="clear:both"></div>
				</div>
				
				
				<!-- CONTENT PANEL -->
				<div class="panel '.((get_option('cnpl_last_active_panel') == 'cnpl_content_panel') ? 'active' : '').' cnpl_content_panel">
				';
					$html .= '
					<!-- COOKIE NOTICE INFO TEXT SETTING -->
					<blockquote>
						<h3 class="text-center">'.__('MESSAGE', 'cookie-notify').'</h3>
						<select class="wAll" name="komunikat" id="komunikat" onchange="javascript:komunikatProcess()">
							<option value="">'.__('-SELECT-', 'cookie-notify').'</option>
							<option>'.__('We use cookie files.', 'cookie-notify').'</option>
							<option>'.__('We use cookies', 'cookie-notify').'</option>
							<option>'.__('This website use cookie files.', 'cookie-notify').'</option>
							<option>'.__('This website use cookies.', 'cookie-notify').'</option>
							
							<option>'.__('Our website use cookie files.', 'cookie-notify').'</option>
							<option>'.__('Our pages use cookies', 'cookie-notify').'</option>
							';
							
							if(get_bloginfo('language') == 'pl'||get_bloginfo('language') == 'pl-PL'){
								$html .= '<option>Nasze strony wykorzystują pliki cookies (ciasteczka).</option>
									  <option>Wykorzystujemy pliki cookies (ciaseczka).</option>
									  <option>Ta strona używa plików cookies (ciasteczek).</option>';
							}
							$html .='
						</select>
						<textarea class="wAll h100" id="komunikatTmp" onchange="javascript:komunikatTmpProcess()" onkeyup="javascript:komunikatTmpProcess()">'.esc_textarea(get_option('cnpl_content_text')).'</textarea>
						<small>'.__('Other longer proposals:', 'cookie-notify').'<br />
							<a href="javascript:komunikatLognerProcess(\'1\');" id="cookie_message_proposal_1">'.__('This site uses cookies to provide the highest quality of service. Further use of this site constitutes agreement to their usage and acceptance of the Privacy Policy.', 'cookie-notify').'</a><br />
							<a href="javascript:komunikatLognerProcess(\'2\');" id="cookie_message_proposal_2">'.__('In order to maintain the highest quality of service, we use information stored in cookies. Cookies usage rules can be changed in your browser settings.', 'cookie-notify').'</a><br />
							<a href="javascript:komunikatLognerProcess(\'3\');" id="cookie_message_proposal_3">'.__('The site uses cookies. You can disable them in any time. Using your site without changing your browser settings means that they will be placed on your device.', 'cookie-notify').'</a><br />
							<a href="javascript:komunikatLognerProcess(\'4\');" id="cookie_message_proposal_4">'.__('This site uses cookies and other technology. By using this site you consent to their use, according to your browser settings.', 'cookie-notify').'</a><br />
							<a href="javascript:komunikatLognerProcess(\'5\');" id="cookie_message_proposal_5">'.__('This site uses cookies for the purposes of providing services, advertising or statistics. You can block them by configuring your web browser.', 'cookie-notify').'</a>
						</small>
					</blockquote>
					<br />
					<br />
					<br />

					<!-- COOKIE NOTICE CLOSE BUTTON TEXT SETTING -->
					<blockquote>
						<h3 class="text-center">'.__('ACCEPT BUTTON', 'cookie-notify').'</h3>
						
						<select class="wAll" name="button" id="button" onchange="javascript:buttonProcess()">
							<option value="">'.__('-SELECT-', 'cookie-notify').'</option>
							<option>'.__('AGREE', 'cookie-notify').'</option>
							<option>'.__('I AGREE', 'cookie-notify').'</option>
							<option>'.__('I ACCEPT', 'cookie-notify').'</option>
							<option>'.__('OK', 'cookie-notify').'</option>
							<option>'.__('I UNDERSTAND', 'cookie-notify').'</option>
							<option>'.__('CLOSE', 'cookie-notify').'</option>
							<option>&times;</option>
							<option value="<i class=\'fa fa-times\'></i>"><i class=\'fa fa-times\'></i> fa-times (font awesome icon)</option>
							<option value="<i class=\'fa fa-check\'></i>"><i class=\'fa fa-check\'></i> fa-check (font awesome icon)</option>
						</select>
						
						<input type="text" class="wAll" id="buttonTmp" onchange="javascript:buttonTmpProcess()" onkeyup="javascript:buttonTmpProcess()" value="'.esc_attr(get_option('cnpl_content_button_text')).'" />
					</blockquote>
					<br />
					<br />
					<br />

					
					<!-- COOKIE NOTICE PRIVATE POLICY LINK TEXT SETTING -->
					<blockquote>
					
						<h3 class="text-center">'.__('Private Policy Link', 'cookie-notify').'</h3>
						'.__('New line before: ', 'cookie-notify').' 
						<input type="checkbox" id="br_pc_title" '.((get_option('cnpl_content_link_regulation_br') == '1') ? 'checked="checked"' : '').' onchange="javascript:addbreak(\'br_pc_title\');" />
						<br /><br />
						'.__('Private Policy Link Text: ', 'cookie-notify').' 
						<input type="text" id="pc_title" value="'.esc_attr(get_option('cnpl_content_link_regulation_text')).'" onkeyup="javascript:updatePolitykaText();" onchange="javascript:updatePolitykaText();" class="wAll">
						<small>
							'.__('OTHER PROPOSALS: ', 'cookie-notify').' 
						</small>
						<br />
						
						
	
						
						
						<a href="javascript:setTextToInput(\'pc_title\' , \''.__('Private Policy', 'cookie-notify').'\')" class="label label-primary">'.__('Private Policy', 'cookie-notify').'</a> &nbsp; 
						<a href="javascript:setTextToInput(\'pc_title\' , \''.__('Cookie Policy', 'cookie-notify').'\')" class="label label-primary">'.__('Cookie Policy', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc_title\' , \''.__('How do we use cookies', 'cookie-notify').'\')" class="label label-primary">'.__('How do we use cookies', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc_title\' , \''.__('How do we use cookie files', 'cookie-notify').'\')" class="label label-primary">'.__('How do we use cookie files', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc_title\' , \''.__('Cookie usage', 'cookie-notify').'\')" class="label label-primary">'.__('Cookie usage', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc_title\' , \''.__('Usage of Cookies', 'cookie-notify').'\')" class="label label-primary">'.__('Usage of Cookies', 'cookie-notify').'</a> &nbsp; 
						<a href="javascript:setTextToInput(\'pc_title\' , \''.__('Regulations', 'cookie-notify').'\')" class="label label-primary">'.__('Regulations', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc_title\' , \''.__('Terms of service', 'cookie-notify').'\')" class="label label-primary">'.__('Terms of service', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc_title\' , \''.__('Legal note', 'cookie-notify').'\')" class="label label-primary">'.__('Legal note', 'cookie-notify').'</a> 
						<br />
						<br />
						'.__('PRIVATE POLICY URL (should be local): ', 'cookie-notify').' 
						<input type="text" id="pc_url" class="wAll" onkeyup="javascript:updatePolitykaText();" onchange="javascript:updatePolitykaText();" value="'.esc_attr(get_option('cnpl_content_link_regulation_url')).'">
						<br />
						<small>
							<i class="dashicons-before dashicons-warning"></i> '.__('You should chage this URL to internal website link', 'cookie-notify').' 
							'.__('You can generate PRIVATE POLICY content ', 'cookie-notify').' <a href="'.((get_bloginfo('language') == 'pl'||get_bloginfo('language') == 'pl-PL') ? 'http://jakwylaczyccookie.pl/generator-polityki-cookie/' : 'http://cookienotify.com/private-policy-generator.html').'">'.__('on this site', 'cookie-notify').' <i class="dashicons-before dashicons-external"></i></a>
						</small>
					</blockquote>
					<br />
					<br />
					<br />

					
					<!-- COOKIE NOTICE COOKIE INFO LINK TEXT SETTING -->
					<blockquote>
						<h3 class="text-center">'.__('HOW TO LINK: ', 'cookie-notify').' </h3>
						'.__('New line before: ', 'cookie-notify').' <input type="checkbox" id="br_pc2_title"  '.((get_option('cnpl_content_link_howto_br') == '1') ? 'checked="checked"' : '').' onchange="javascript:addbreak(\'br_pc2_title\');" />
						<br />
						<br />
						'.__('TEXT OF HOW TO DISABLE COOKIES INFORMATION LINK: ', 'cookie-notify').' 
						<input type="text" id="pc2_title" value="'.esc_attr(get_option('cnpl_content_link_howto_text')).'" onkeyup="javascript:updateInfoText();" onchange="javascript:updateInfoText();" class="wAll">
						<small>
							'.__('OTHER PROPOSALS: ', 'cookie-notify').' 
						</small>
						<br />

						<a href="javascript:setTextToInput(\'pc2_title\' , \''.__('How to disable cookie files', 'cookie-notify').'\')" class="label label-primary">'.__('How to disable cookie files', 'cookie-notify').'</a> &nbsp; 
						<a href="javascript:setTextToInput(\'pc2_title\' , \''.__('How to disable cookies', 'cookie-notify').'\')" class="label label-primary">'.__('How to disable cookies', 'cookie-notify').'</a> &nbsp; 
						<a href="javascript:setTextToInput(\'pc2_title\' , \''.__('Learn more', 'cookie-notify').'\')" class="label label-primary">'.__('Learn more', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc2_title\' , \''.__('More', 'cookie-notify').'\')" class="label label-primary">'.__('More', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc2_title\' , \''.__('Information about cookies', 'cookie-notify').'\')" class="label label-primary">'.__('Information about cookies', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc2_title\' , \''.__('How to manage cookies', 'cookie-notify').'\')" class="label label-primary">'.__('How to manage cookies', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc2_title\' , \''.__('Manage cookies', 'cookie-notify').'\')" class="label label-primary">'.__('Manage cookies', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc2_title\' , \''.__('Cookie files', 'cookie-notify').'\')" class="label label-primary">'.__('Cookie files', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc2_title\' , \''.__('About cookies', 'cookie-notify').'\')" class="label label-primary">'.__('About cookies', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc2_title\' , \''.__('About cookies files', 'cookie-notify').'\')" class="label label-primary">'.__('About cookies files', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc2_title\' , \''.__('Cookie', 'cookie-notify').'\')" class="label label-primary">'.__('Cookie', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc2_title\' , \''.__('Cookies', 'cookie-notify').'\')" class="label label-primary">'.__('Cookies', 'cookie-notify').'</a>  &nbsp; 
						<a href="javascript:setTextToInput(\'pc2_title\' , \''.__('How to turn off cookies', 'cookie-notify').'\')" class="label label-primary">'.__('How to turn off cookies', 'cookie-notify').'</a>  
						<br />
						<br />
						'.__('HOW TO DISABLE COOKIES INFORMATION PAGE URL (can be external): ', 'cookie-notify').' 
						<input type="text" id="pc2_url" class="wAll" onkeyup="javascript:updateInfoText();" onchange="javascript:updateInfoText();" value="'.esc_attr(get_option('cnpl_content_link_howto_url')).'">
						<small>
							<i class="dashicons-before dashicons-warning"></i> <strong>'.__('Providing this information on the external source is legal.', 'cookie-notify').'</strong> '.__('However, you can provide your own internal URL.', 'cookie-notify').'
						</small>
					</blockquote>
				</div>
				
				<!-- ADVANCED PANEL -->
				<div class="panel '.((get_option('cnpl_last_active_panel') == 'cnpl_advanced_panel') ? 'active' : '').' cnpl_advanced_panel">
				
					<!-- COOKIE MEMORY -->
					<p>'.__('User Acceptance will expire in: ', 'cookie-notify').'</p>
					<p>
						<input type="text" onkeyup="javascript:updateTime();" onchange="javascript:updateTime();" id="pc_wybor" value="'.esc_attr(get_option('cnpl_settings_time')).'" class="w60 inline">
						<span class="inline"> '.__('days.', 'cookie-notify').'</span>
					</p>
					<br />
					<hr />
					<br />
				   
					<div class="hidden">
						<!-- DEBUD INPUTS -->
						<hr />
						Podgląd kodu HTML (Kod jest wstrzykiwany pod sekcją BODY):
						<textarea id="cnpl_html" name="cnpl_html"  class="wAll"></textarea>
						<small>
							Można edytować (Twórca plugina nie odpowiada za prawidłowe działanie w przypadku modyfikacji)
						</small>
						<hr />
						Podgląd kodu JavaScript (Kod jest wstrzykiwany jako INLINE w sekcji HEAD):
						<textarea id="cnpl_js" name="cnpl_js" class="wAll"></textarea>
						<small>
							Można edytować (Twórca plugina nie odpowiada za prawidłowe działanie w przypadku modyfikacji)
						</small>
						<hr />
						Podgląd kodu CSS (Kod jest wstrzykiwany jako INLINE w sekcji HEAD):
						<textarea id="cnpl_css" name="cnpl_css" class="wAll"></textarea>
						<small>
							Można edytować (Twórca plugina nie odpowiada za prawidłowe działanie w przypadku modyfikacji)
						</small>
						
						<!-- .cnpl_inner -->
						<p>Styl CSS boksu powiadomienia (wewnętrzny)</p>
						<input type="text" id="cnpl_css_1" value="" onkeyup="javascript:updateCSS();" onchange="javascript:updateCSS();" class="wAll">
						
						<!-- #cnpl_v01 -->
						<p>Styl CSS boksu powiadomienia (zewnętrzny)</p>
						<input type="text" id="cnpl_css_2" value="" onkeyup="javascript:updateCSS();" onchange="javascript:updateCSS();" class="wAll">
						
						<!-- #jwc_hr1 / #jwc_hr2 -->
						<p>Styl CSS odestępów pomiędzy tekstami i przyciskiem</p>
						<input type="text" id="cnpl_css_3" value="" onkeyup="javascript:updateCSS();" onchange="javascript:updateCSS();" class="wAll">
						
						<!-- #okbutton -->
						<p>Styl CSS przycisku</p>
						<input type="text" id="cnpl_css_4" value="" onkeyup="javascript:updateCSS();" onchange="javascript:updateCSS();" class="wAll">
						
						<!-- #okbutton -->
						<p>Styl CSS odnośnika</p>
						<input type="text" id="cnpl_css_5" value="" onkeyup="javascript:updateCSS();" onchange="javascript:updateCSS();" class="wAll">
					</div>
				</div>
				<br />
				<span onclick="javascript:turnOnOff();" class="panel_button panel_button_'.((get_option('cnpl_on_off') == '1') ? 'turnoff2' : 'save2 blink').' menu_item"><i class="fa fa-power-off"></i> '.((get_option('cnpl_on_off') == '1') ? $tabs[6] : $tabs[7]).'</span>
				<span onclick="javascript:submitForm();" class="panel_button panel_button_save2 menu_item_bottom"><i class="fa fa-save"></i> '.$tabs[5].'</span>

			</div>
			<div class="cnpl_footer">
			
				<span onclick="javascript:cnpl_changePanel(\'cnpl_position_panel\');" class="'.((get_option('cnpl_last_active_panel') == 'cnpl_position_panel') ? 'active' : '').' panel_button cnpl_position_panel_button menu_item_bottom"><i class="dashicons-before dashicons-welcome-widgets-menus"></i>  '.$tabs[0].'</span>
				<span onclick="javascript:cnpl_changePanel(\'cnpl_colors_panel\');" class="'.((get_option('cnpl_last_active_panel') == 'cnpl_colors_panel') ? 'active' : '').' panel_button cnpl_colors_panel_button menu_item_bottom"><i class="dashicons-before dashicons-art"></i>  '.$tabs[1].'</span>
				<span onclick="javascript:cnpl_changePanel(\'cnpl_theme_panel\');" class="'.((get_option('cnpl_last_active_panel') == 'cnpl_theme_panel') ? 'active' : '').' panel_button cnpl_theme_panel_button menu_item_bottom"><i class="dashicons-before dashicons-admin-appearance"></i>  '.$tabs[2].'</span>
				<span onclick="javascript:cnpl_changePanel(\'cnpl_content_panel\');" class="'.((get_option('cnpl_last_active_panel') == 'cnpl_content_panel') ? 'active' : '').' panel_button cnpl_content_panel_button menu_item_bottom"><i class="dashicons-before dashicons-format-aside"></i>  '.$tabs[3].'</span>
				<span onclick="javascript:cnpl_changePanel(\'cnpl_advanced_panel\');" class="'.((get_option('cnpl_last_active_panel') == 'cnpl_advanced_panel') ? 'active' : '').' panel_button cnpl_advanced_panel_button menu_item_bottom"><i class="dashicons-before dashicons-admin-settings"></i>  '.$tabs[4].'</span>
				<span onclick="javascript:turnOnOff();" class="panel_button panel_button_'.((get_option('cnpl_on_off') == '1') ? 'turnoff' : 'save blink').' menu_item_bottom"><i class="fa fa-power-off"></i> '.((get_option('cnpl_on_off') == '1') ? $tabs[6] : $tabs[7]).'</span>
				<span onclick="javascript:submitForm();" class="panel_button panel_button_save menu_item_bottom"><i class="fa fa-save"></i> '.$tabs[5].'</span>
			</div>
			
			
			<input type="submit" name="Submit" value="'.$tabs[5].'" class="hidden" />
			<br /><br /><hr />
		</form>
		<a href="http://cookienotify.com" style="float:right">Cookie Notify</a>
	<a href="http://jakwylaczyccookie.pl"><img src="'.plugins_url('images/jakwylaczyccookie-logo-2017-login.png',__FILE__).'" style="width:100px" /><a/>
	
	</div>
		 
		 
	
		 
		 
	<div id="tothebit">
		<div id="cnpl_v01" style="'.esc_attr(get_option('cnpl_theme_css_box_outer')).'">
			<div class="cnpl_inner" style="'.esc_attr(get_option('cnpl_theme_css_box_inner')).'">
				<span id="cnpl_v01_powiadomienie" style="'.esc_attr(get_option('cnpl_theme_css_text')).'"></span><span id="br_pc_title_html"></span>
				<a id="cnpl_v01_polityka" style="'.esc_attr(get_option('cnpl_theme_css_links')).'"></a><span id="br_pc2_title_html"></span>
				<a id="cnpl_v01_info" style="'.esc_attr(get_option('cnpl_theme_css_links')).'"></a><div id="jwc_hr1" style="height:10px"></div>
				<a id="okbutton" href="javascript:cnpl_v01_create_cookie(\'cnpl_v01\',1,7);" style="'.esc_attr(get_option('cnpl_theme_css_button')).'">'.esc_attr(get_option('cnpl_content_button_text')).'</a><div id="jwc_hr2" style="height:10px"></div>
			</div>
		</div>
	</div>
	
	';


/* INLINE JAVASCRIPT (To small plugin for creating structured filesystem) */

$js = "<script>
	/* GLOBAL VALUES */
	var buttonStyle = 1;
	var buttonTextColor = '';
	/* FUNCTIONS */

	String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g, '');};
	
	function komunikatTmpProcess(){
		updatePowiadomienieText(jQuery('#komunikatTmp').val());
	}
	function komunikatLognerProcess(id){
		jQuery('#komunikatTmp').val(jQuery('#cookie_message_proposal_'+id).text());
		komunikatTmpProcess();
	}
	function komunikatProcess(){
		var selectedOption = jQuery('#komunikat').val();
		jQuery('#komunikat :nth-child(1)').prop('selected', true);
		if(selectedOption!=''){
			updatePowiadomienieText(selectedOption);
			jQuery('#komunikatTmp').val(selectedOption);
		}
	}


	function buttonTmpProcess(){
		updateButtonText(jQuery('#buttonTmp').val());
	}

	function buttonProcess(){
		var selectedOption = jQuery('#button').val();
		jQuery('#button :nth-child(1)').prop('selected', true);
		if(selectedOption!=''){
			updateButtonText(selectedOption);
			jQuery('#buttonTmp').val(selectedOption);
		}
	}

	function cnpl_v01_create_cookie(a,b,c){
		jQuery('#cnpl_v01').hide('fast');
	}
	function update(){
		
		/*
			INFO: CREATES CODE AND UPDATES TEXTAREAS
		*/
		
		jQuery('#okbutton').attr('href',\"javascript:cnpl_v01_create_cookie('cnpl_v01',1,\"+jQuery('#pc_wybor').val()+\");\");

		/* CSS */
		
		jQuery('#cnpl_css_1').val(jQuery('.cnpl_inner').attr('style'));
		jQuery('#cnpl_css_2').val(jQuery('#cnpl_v01').attr('style'));
		jQuery('#cnpl_css_3').val(jQuery('#jwc_hr1').attr('style'));
		jQuery('#cnpl_css_4').val(jQuery('#okbutton').attr('style'));
		jQuery('#cnpl_css_5').val(jQuery('#cnpl_v01_polityka').attr('style'));
		
		var css = '.cnpl_inner{ '+jQuery('#cnpl_css_1').val()+' }';
		css += '#cnpl_v01 {'+jQuery('#cnpl_css_2').val()+'}';
		css += '#okbutton {'+jQuery('#cnpl_css_4').val()+'} ';
		css += '#cnpl_v01_polityka {'+jQuery('#cnpl_css_5').val()+'} ';
		css += '#cnpl_v01_info {'+jQuery('#cnpl_css_5').val()+'} ';
		
		
		/* HTML */
		var html = 	'<div id=\"cnpl_v01\">';
		html +=			'<div class=\"cnpl_inner\">';
		html +=				'<span id=\"cnpl_v01_powiadomienie\">'+jQuery('#cnpl_v01_powiadomienie').text()+'</span>';
		html +=				'<span id=\"br_pc_title_html\">'+jQuery('#br_pc_title_html').html()+'</span>';
		html +=				'<a id=\"cnpl_v01_polityka\" href=\"'+jQuery('#cnpl_v01_polityka').attr('href')+'\">'+jQuery('#cnpl_v01_polityka').text()+'</a>';
		html +=				'<span id=\"br_pc2_title_html\">'+jQuery('#br_pc2_title_html').html()+'</span>';
		html +=				'<a id=\"cnpl_v01_info\" href=\"'+jQuery('#cnpl_v01_info').attr('href')+'\">'+jQuery('#cnpl_v01_info').text()+'</a>';
		html +=				'<div id=\"jwc_hr1\"></div>';
		html +=				'<a id=\"okbutton\" href=\"javascript:cnpl_v01_create_cookie(\'cnpl_v01\',1,7);\">'+jQuery('#okbutton').text()+'</a>';
		html +=				'<div id=\"jwc_hr2\"></div>';
		html +=			'</div>';
		html +=		'</div>';
			
		
		/* JavaScript */
		var js = 'var galTable= new Array(); var galx = 0;';
		
		js += 	'function cnpl_v01_create_cookie(name,value,days) { ';
		js += 		'if (days) { ';
		js += 			'var date = new Date(); ';
		js += 			'date.setTime(date.getTime()+(days*24*60*60*1000)); ';
		js += 			'var expires = \"; expires=\"+date.toGMTString(); ';
		js += 		'} ';
		js += 		'else { var expires = \"\"; } ';	
		js += 		'document.cookie = name+\"=\"+value+expires+\"; path=\/\"; ';
		js += 		'document.getElementById(\"cnpl_v01\").style.display = \"none\"; ';
		js += 	'}';
		
		js += 	'function cnpl_v01_read_cookie(name) { ';
		js += 		'var nameEQ = name + \"=\"; ';
		js += 		'var ca = document.cookie.split(\";\"); ';
		js += 		'for(var i=0;i < ca.length;i++) { ';
		js += 			'var c = ca[i]; ';
		js += 			'while (c.charAt(0)==\" \") c = c.substring(1,c.length); ';
		js += 			'if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length); ';
		js += 		'}';
		js += 		'return null;';
		js += 	'}';
		
		js += 	'var cnpl_v01_jest = cnpl_v01_read_cookie(\"cnpl_v01\");';
		js += 	'if(cnpl_v01_jest==1){ document.getElementById(\"cnpl_v01\").style.display = \"none\"; }';
        
		/* Hack to prevent google chrome block POST data */
		html = html.replace('javascript:','{{jasc}}')
		
		document.getElementById('cnpl_js').value =  js;
		document.getElementById('cnpl_html').value =  html;
		document.getElementById('cnpl_css').value =  css;
		
		
		/* SET VALUES TO SAVE*/
		jQuery('#cnpl_content_text').val(jQuery('#komunikatTmp').val());
		jQuery('#cnpl_content_link_regulation_br').val(function(){if(jQuery('#br_pc_title').prop('checked')){return '1'; }else{ return '0';}});
		jQuery('#cnpl_content_link_regulation_text').val(jQuery('#pc_title').val());
		jQuery('#cnpl_content_link_regulation_url').val(jQuery('#pc_url').val());
		jQuery('#cnpl_content_link_howto_br').val(function(){if(jQuery('#br_pc2_title').prop('checked')){return '1'; }else{ return '0';}});
		jQuery('#cnpl_content_link_howto_text').val(jQuery('#pc2_title').val());
		jQuery('#cnpl_content_link_howto_url').val(jQuery('#pc2_url').val());
		jQuery('#cnpl_content_button_text').val(jQuery('#buttonTmp').val());
		jQuery('#cnpl_theme_css_box_outer').val(jQuery('#cnpl_v01').attr('style'));
		jQuery('#cnpl_theme_css_box_inner').val(jQuery('.cnpl_inner').attr('style'));
		jQuery('#cnpl_theme_css_button').val(jQuery('#okbutton').attr('style'));
		jQuery('#cnpl_theme_css_links').val(jQuery('#cnpl_v01_polityka').attr('style'));
		jQuery('#cnpl_theme_css_text').val(jQuery('#cnpl_v01_powiadomienie').attr('style'));
		
		
	}

	function addbreak(id){
		
		
		/*
			INFO: ADD BR DEPENDING ON USER CHOICE
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		
		if(id=='br_pc_title'){
			if(jQuery('#br_pc_title').prop('checked')){
				jQuery('#br_pc_title_html').html('<br />');
			}
			else {  jQuery('#br_pc_title_html').html(' &nbsp;&nbsp; '); }
		}

		else if(id==\"br_pc2_title\"){
			if(jQuery('#br_pc2_title').prop('checked')){
				jQuery('#br_pc2_title_html').html('<br />');
			}
			else {  jQuery('#br_pc2_title_html').html(' &nbsp;&nbsp; '); }
		}
	}


	function setTextToInput(inputid, text){
		
		/*
			INFO: UNIVERSAL FUNCTION TO UPDATE INPUTS WITH PREDEFINED VALUES
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		
		/* INPUT UPDATE */
		jQuery('#'+inputid).val(text);
		
		/* PREVIEW AND CODE UPDATE */
		updatePolitykaText();
		updateInfoText();

	}


	function updateButtonText(text){
		
		/*
			INFO: UPDATE COOKIE NOTICE BUTTON TEXT
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		
		jQuery('#okbutton').html(text);
	}


	function updatePowiadomienieText(text){
		
		/*
			INFO: UPDATE COOKIE NOTICE STANDARD TEXT
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		
		jQuery('#cnpl_v01_powiadomienie').html(text);
		
	}

	function updatePolitykaText(){
		
		/*
			INFO: UPDATE COOKIE NOTICE REGULATIONS LINK
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		
		
		jQuery('#cnpl_v01_polityka').html(jQuery('#pc_title').val());
		jQuery('#cnpl_v01_polityka').attr('href',jQuery('#pc_url').val());
		
	}

	function updateInfoText(){
		
		/*
			INFO: UPDATE COOKIE NOTICE INFORMATION LINK
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		
		
		jQuery('#cnpl_v01_info').html(jQuery('#pc2_title').val());
		jQuery('#cnpl_v01_info').attr('href',jQuery('#pc2_url').val());

	}


	function noticebutton(bStyle){
		
		/*
			INFO: UPDATE COOKIE NOTICE BUTTON STYLE
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		buttonStyle = bStyle;

		/* SAVING CURRENT COLOR PALETTE STYLE */
		var col1 = document.getElementById('cnpl_v01').style.background;
		var col2 = document.getElementById('cnpl_v01').style.borderColor;

		/* SAVING COOKIE NOTICE BUTTON STYLE */
		if(bStyle==1){
			  document.getElementById('okbutton').style.background = \"\"+col2;
			  document.getElementById('okbutton').style.border = \"0px solid\";
			  document.getElementById('okbutton').style.borderColor = \"\"+rgbToHex(col1);
			  document.getElementById('okbutton').style.borderRadius = '0px';
			  document.getElementById('okbutton').style.color = buttonTextColor;
		}
		if(bStyle==2){
			  document.getElementById('okbutton').style.background = \"\"+col2;
			  document.getElementById('okbutton').style.border = \"0px solid\";
			  document.getElementById('okbutton').style.borderColor = \"\"+rgbToHex(col1);
			  document.getElementById('okbutton').style.borderRadius = '5px';
			  document.getElementById('okbutton').style.color = buttonTextColor;
		}
		if(bStyle==3){
			  document.getElementById('okbutton').style.background = \"#FFF\";
			  document.getElementById('okbutton').style.border = \"1px solid\";
			  document.getElementById('okbutton').style.borderRadius = '0px';
			  document.getElementById('okbutton').style.borderColor = \"\"+rgbToHex(col2);
			  document.getElementById('okbutton').style.color = \"\"+rgbToHex(col2);
		}
		if(bStyle==4){
			  document.getElementById('okbutton').style.background = \"#FFF\";
			  document.getElementById('okbutton').style.border = \"1px solid\";
			  document.getElementById('okbutton').style.borderRadius = '5px';
			  document.getElementById('okbutton').style.borderColor = \"\"+rgbToHex(col2);
			  document.getElementById('okbutton').style.color = \"\"+rgbToHex(col2);
		}
		
		/* UPDATING CODE AND VIEW */
		
		jQuery('#cnpl_theme_css_button').val(jQuery('#okbutton').attr('style'));
		update();
	}        


	function noticecorner(val){
		
		/*
			INFO: UPDATE COOKIE NOTICE CORNERS STYLE
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		
		jQuery('#remember_corners').val(val+'px');
		
		
		/* SETTING CORNER RADIUS */
		document.getElementById('cnpl_v01').style.borderRadius=val+'px';
		
		/* UPDATING BUTTON STYLE (border colors mostly) */
		noticebutton(buttonStyle);
	}


	function noticeshadow(cien){

		/*
			INFO: UPDATE COOKIE NOTICE SHADOW
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		
		
		/* SETTING SHADOW STYLE (whole style is placed on the show buttons) */
		document.getElementById('cnpl_v01').style.boxShadow =cien;
		
		/* UPDATING BUTTON STYLE (border colors mostly) */
		noticebutton(buttonStyle );
	}


	function noticeborder(br){
		
		/*
			INFO: UPDATE COOKIE NOTICE BORDER
		*/
		
		/* save selection to tmp */
		jQuery('#remember_border').val(br);
		
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		
		var color = document.getElementById('cnpl_v01').style.borderColor;
		var bottom = document.getElementById('cnpl_v01').style.bottom;
		var top= document.getElementById('cnpl_v01').style.top;
		
		var brs = br.split(';');
		var br1 = brs[0].split(':');
		var br2 = brs[1].split(':');
		if(br1[0]==\"border\"){
			
			if(bottom==\"0\"||bottom==\"0px\"){
				document.getElementById('cnpl_v01').style.border=\"0\";
				document.getElementById('cnpl_v01').style.borderTop=br1[1]+\" \"+color;
			}
			else if(top==\"0\"||top==\"0px\"){
				document.getElementById('cnpl_v01').style.border=\"0\";
				document.getElementById('cnpl_v01').style.borderBottom=br1[1]+\" \"+color;
			}
			else {
				document.getElementById('cnpl_v01').style.border=br1[1]+\" \"+color;
			}
			document.getElementById('cnpl_v01').style.borderTop=br2[1]+\" \"+color;
		}
		else {
			document.getElementById('cnpl_v01').style.borderTop=br2[1]+\" \"+color;
			if(bottom==\"0\"||bottom==\"0px\"){
				document.getElementById('cnpl_v01').style.border=\"0\";
				document.getElementById('cnpl_v01').style.borderTop=br2[1]+\" \"+color;
			}
			else if(top==\"0\"||top==\"0px\"){
				document.getElementById('cnpl_v01').style.border=\"0\";
				document.getElementById('cnpl_v01').style.borderBottom=br2[1]+\" \"+color;
		 
			}
			else {
				document.getElementById('cnpl_v01').style.border=br2[1]+\" \"+color;
			}
		}
		document.getElementById('cnpl_v01').style.borderColor = color;
		noticebutton(buttonStyle );
	}
	function noticeposition(setposition){
		
		/* 
			INFO: SETS POSITION OF THE COOKIE NOTICE 
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		
		/* SET POSITION TO SAVE*/
		jQuery('#cnpl_position').val(setposition);
		
		/* RESET BREAK-LINES BETWEEN LINKS AND BUTTON */
		document.getElementById('jwc_hr1').style.display='block';
		document.getElementById('jwc_hr2').style.display='block';
		
		/* GET CURRENT COLOR PALLETE & CORNERS*/
		var color = document.getElementById('cnpl_v01').style.borderColor;
		jQuery('#cnpl_v01').attr('style',jQuery('#cnpl_v01').attr('style')+'; '+jQuery('#remember_border').val()+'; '+jQuery('#remember_corners').val());
		var bt = document.getElementById('cnpl_v01').style.borderTop;
		var bb = document.getElementById('cnpl_v01').style.borderBottom;
		var bl = document.getElementById('cnpl_v01').style.borderLeft;
		var br = document.getElementById('cnpl_v01').style.borderRight;
		var bottom = document.getElementById('cnpl_v01').style.bottom;
		var top= document.getElementById('cnpl_v01').style.top;
		var corners = document.getElementById('cnpl_v01').style.borderRadius;

		
		
		/* RESET POSITION SELECTION BACKGROUND */
		document.getElementById('cnpl_w_top').style.background='';
		document.getElementById('cnpl_w_topleft').style.background='';
		document.getElementById('cnpl_w_topright').style.background='';
		document.getElementById('cnpl_w_bottom').style.background='';
		document.getElementById('cnpl_w_bottomleft').style.background='';
		document.getElementById('cnpl_w_bottomright').style.background='';
		
		/* SET SELECTED POSITION BACKGROUND */
		document.getElementById('cnpl_w_'+setposition).style.background='#D9EDF7';

		/* SETTING POSITION */
		if(setposition=='bottom'){
			
			/* disabling break-lines */
			document.getElementById('jwc_hr1').style.display='none';
			document.getElementById('jwc_hr2').style.display='none';
			
			/* disabling corners*/
			document.getElementById('cnpl_v01').style.borderRadius='0';
			
			/* setting proper notice style */
			document.getElementById('cnpl_v01').style.bottom = 0;
			document.getElementById('cnpl_v01').style.left = 0;
			document.getElementById('cnpl_v01').style.right = 0;
			document.getElementById('cnpl_v01').style.top = '';
			document.getElementById('cnpl_v01').style.width = '100%';
			document.getElementById('cnpl_v01').style.borderTop = bt;
			document.getElementById('cnpl_v01').style.borderBottom = '';
			document.getElementById('cnpl_v01').style.borderLeft = '';
			document.getElementById('cnpl_v01').style.borderRight = '';

			/* setting proper notice button style and position */
			document.getElementById('okbutton').style.position = 'absolute';
			document.getElementById('okbutton').style.top = '5px';
			document.getElementById('okbutton').style.right = '5px';
		}
		else  if(setposition=='top'){
			
			/* disabling break-lines */
			document.getElementById('jwc_hr1').style.display='none';
			document.getElementById('jwc_hr2').style.display='none';
			
			/* disabling corners*/
			document.getElementById('cnpl_v01').style.borderRadius='0';
			console.log('set border radius to 0');
			
			/* setting proper notice style */
			document.getElementById('cnpl_v01').style.bottom = '';
			document.getElementById('cnpl_v01').style.left = 0;
			document.getElementById('cnpl_v01').style.right = 0;
			document.getElementById('cnpl_v01').style.top = 0;
			document.getElementById('cnpl_v01').style.width = '100%';
			document.getElementById('cnpl_v01').style.borderTop = '';
			document.getElementById('cnpl_v01').style.borderBottom = bb;
			document.getElementById('cnpl_v01').style.borderLeft = '';
			document.getElementById('cnpl_v01').style.borderRight = '';

			/* setting proper notice button style and position */
			document.getElementById('okbutton').style.position = 'absolute';
			document.getElementById('okbutton').style.top = '5px';
			document.getElementById('okbutton').style.right = '5px';


		}
		else  if(setposition=='topleft'){
			
			/* break-lines setted at the beginning */
			
			/* enabling corners*/
			document.getElementById('cnpl_v01').style.borderRadius=corners;
			
			/* setting proper notice style */
			document.getElementById('cnpl_v01').style.bottom = '';
			document.getElementById('cnpl_v01').style.left = '10px';
			document.getElementById('cnpl_v01').style.right = '';
			document.getElementById('cnpl_v01').style.top = '10px';
			document.getElementById('cnpl_v01').style.width = '300px';
			document.getElementById('cnpl_v01').style.borderTop = bt;
			document.getElementById('cnpl_v01').style.borderBottom = bb;
			document.getElementById('cnpl_v01').style.borderLeft = bl;
			document.getElementById('cnpl_v01').style.borderRight = br;

			/* setting proper notice button style and position */
			document.getElementById('okbutton').style.position = 'relative';
			document.getElementById('okbutton').style.top = '';
			document.getElementById('okbutton').style.right = '';
		}
		else  if(setposition=='topright'){
			
			/* break-lines setted at the beginning */
			
			/* enabling corners*/
			document.getElementById('cnpl_v01').style.borderRadius=corners;
			
			/* setting proper notice style */
			document.getElementById('cnpl_v01').style.bottom = '';
			document.getElementById('cnpl_v01').style.left = '';
			document.getElementById('cnpl_v01').style.right = '10px';
			document.getElementById('cnpl_v01').style.top = '10px';
			document.getElementById('cnpl_v01').style.width = '300px';
			document.getElementById('cnpl_v01').style.borderTop = bt;
			document.getElementById('cnpl_v01').style.borderBottom = bb;
			document.getElementById('cnpl_v01').style.borderLeft = bl;
			document.getElementById('cnpl_v01').style.borderRight = br;

			/* setting proper notice button style and position */
			document.getElementById('okbutton').style.position = 'relative';
			document.getElementById('okbutton').style.top = '';
			document.getElementById('okbutton').style.right = '';
		}
		else  if(setposition=='bottomleft'){
			
			/* break-lines setted at the beginning */
			
			/* enabling corners*/
			document.getElementById('cnpl_v01').style.borderRadius=corners;
			
			/* setting proper notice style */
			document.getElementById('cnpl_v01').style.bottom = '10px';
			document.getElementById('cnpl_v01').style.left = '10px';
			document.getElementById('cnpl_v01').style.right = '';
			document.getElementById('cnpl_v01').style.top = '';
			document.getElementById('cnpl_v01').style.width = '300px';
			document.getElementById('cnpl_v01').style.borderTop = bt;
			document.getElementById('cnpl_v01').style.borderBottom = bb;
			document.getElementById('cnpl_v01').style.borderLeft = bl;
			document.getElementById('cnpl_v01').style.borderRight = br;

			/* setting proper notice button style and position */
			document.getElementById('okbutton').style.position = 'relative';
			document.getElementById('okbutton').style.top = '';
			document.getElementById('okbutton').style.right = '';
		}
		else  if(setposition=='bottomright'){
			
			/* break-lines setted at the beginning */
			
			/* enabling corners*/
			document.getElementById('cnpl_v01').style.borderRadius=corners;
			
			/* setting proper notice style */
			document.getElementById('cnpl_v01').style.bottom = '10px';
			document.getElementById('cnpl_v01').style.left = '';
			document.getElementById('cnpl_v01').style.right = '10px';
			document.getElementById('cnpl_v01').style.top = '';
			document.getElementById('cnpl_v01').style.width = '300px';
			document.getElementById('cnpl_v01').style.borderTop = bt;
			document.getElementById('cnpl_v01').style.borderBottom = bb;
			document.getElementById('cnpl_v01').style.borderLeft = bl;
			document.getElementById('cnpl_v01').style.borderRight = br;

			/* setting proper notice button style and position */
			document.getElementById('okbutton').style.position = 'relative';
			document.getElementById('okbutton').style.top = '';
			document.getElementById('okbutton').style.right = '';
		}
		
		/* setting border color from selected palette */
		document.getElementById('cnpl_v01').style.borderColor = color;
		
		/* resetting button style based on selected palette */
		noticebutton(buttonStyle );
		
	}


	function noticepalete(col1,col2, col3, col4){
		
		/* 
			INFO: SETS COLOR PALETTE OF COOKIE NOTICE 
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		
		
		/* background, border and normal text color */
		document.getElementById('cnpl_v01').style.background = col1;
		document.getElementById('cnpl_v01').style.borderColor = col2;
		document.getElementById('cnpl_v01_powiadomienie').style.color = col3;
		
		/* color of the links (not button) */
		document.getElementById('cnpl_v01_polityka').style.color = col2;
		document.getElementById('cnpl_v01_info').style.color = col2;
		
		
		/* resetting button style based on NEW selected palette */
		noticebutton(buttonStyle);
		if(buttonStyle<3){
			document.getElementById('okbutton').style.color = col4;
			buttonTextColor = jQuery('#okbutton').css('color');
		}
		else{
			document.getElementById('okbutton').style.color = buttonTextColor;
		}
		
		jQuery('#cnpl_theme_css_button').val(jQuery('#okbutton').attr('style'));
	}


	function componentToHex(c) {
		
		/* 
			INFO: CONVERT NUMBER TO BASE16 STRING
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		

		x = parseInt(c).toString(16);
		return (x.length==1) ? '0'+x : x;
	}

	function rgbToHex(rgb) {
		
		/* 
			INFO: CONVERT REG COLOR TO HEX COLOR
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		
		
		rgb = rgb.replace('rgb(','');
		rgb = rgb.replace(')','');
		rgb = rgb.replace(' ','');
		rgb = rgb.replace(' ','');
		rgb = rgb.replace(' ','');
		rgb = rgb.split(',');
		
		r = rgb[0];
		g = rgb[1];
		b = rgb[2];
		
		return '#' + componentToHex(r) + componentToHex(g) + componentToHex(b);
	}
	function cnpl_changePanel(panel){
		jQuery('#cnpl_last_active_panel').val(panel);
		jQuery('.panel').removeClass('active');
		jQuery('.panel_button').removeClass('active');
		jQuery('.'+panel).addClass('active');
		jQuery('.'+panel+'_button').addClass('active');
		if(panel=='cnpl_content_panel'){
			
		jQuery('#cnpl_text_panel_opened').val('1');
		}
	}
	window.onload = function(e) {
		
		/* 
			INFO: INIT FUNCTION
		*/
		
		/* visability reload */
		jQuery('#cnpl_v01').show('fast');
		
		jQuery('#okbutton').attr('style',jQuery('#cnpl_theme_css_button').val());
		buttonTextColor = jQuery('#okbutton').css('color');
		/* INTERVAL TO UPDATE CSS, HTML AND JAVASCRIPT TEXTAREAS */
		var mi = setInterval('update()',100);
		
		/* RESETTING COOKIE NOTICE */
		updateButtonText('".esc_attr(get_option('cnpl_content_button_text'))."');
		updatePowiadomienieText('".esc_attr(get_option('cnpl_content_text'))."');
		
		updatePolitykaText();
		updateInfoText();
		
		addbreak('br_pc_title');
		addbreak('br_pc2_title');
		
		noticeposition('".esc_attr(get_option('cnpl_position'))."');
		//noticepalete('#FFF3E0','#FF9800');
		
		jQuery('.blink').each(function() {
			var elem = jQuery(this);
			setInterval(function() {
				if (elem.css('opacity') == '0.7') {
					elem.css('opacity', '1');
				} else {
					elem.css('opacity', '0.7');
				}    
			}, 500);
		});
		
	}
	function turnOnOff(){
		var state = jQuery('#cnpl_on_off').val();
		console.log(state);
		if(state=='0'||state==0){
			jQuery('#cnpl_on_off').val('1');
		}
		else{
			jQuery('#cnpl_on_off').val('0');
		}
		submitForm();
	}
	function submitForm(){
		jQuery('#cnpl_form').submit();
	}
	function updateTime(){
		jQuery('#cnpl_settings_time').val(parseInt(jQuery('#pc_wybor').val(),10));
	}
	</script>";
	
	
	
	
	if(current_user_can( 'manage_options' )) {
		echo $css.$fbinit.$html.$js;
	}
}














