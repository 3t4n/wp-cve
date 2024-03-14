<?php
/*
Plugin Name: Social2Blog
Plugin URI: http://www.social2blog.com/
Description: Keep your WordPress site up to date with social media (Facebook, Twitter, Instagram) posts and Facebook Events. (ITA) Il tuo sito web sempre aggiornato con i post e gli eventi dai social media.
Author: Andrea Dotta, Jacopo Campani, di xkoll.com
Version: 0.2.990
Author URI: http://www.xkoll.com/
Text Domain: social2blog-text
*/


/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

This program incorporates work covered by the following copyright and
permission notices:

  Andrea Dotta(c) 2016 Andrea Dotta
  http://xkoll.com - http://www.social2blog.com/

  Social2Blog is released under the GPL

*/

define("SOCIAL2BLOG_PLUGINGFOLDERNAME", "social2blog");
define("SOCIAL2BLOG_PLUGING", ".");
define("SOCIAL2BLOG_PLUGINGNAME", plugin_basename( __FILE__ ));
define("SOCIAL2BLOG_LOCALURL", admin_url()."admin.php?page=social2blog");
define("SOCIAL2BLOG_TWITTER_FL_LOCAL_URL", admin_url()."admin.php?action=loginTwitter");
define("SOCIAL2BLOG_PLUGINGDIR", plugin_dir_path(__FILE__));
define("SOCIAL2BLOG_SERVER_URL","https://www.social2blog.com/api/donatella.php");
define("SOCIAL2BLOG_ACCESSURL", "https://www.social2blog.com/api/access_token/proxysocial.php");
define("SOCIAL2BLOG_TWITTERCALLBACKURL", "https://www.social2blog.com/api/access_token/twitterauthproxy.php?auth=ok");
define("SOCIAL2BLOG_DEBUG", get_option("social2blog_mod_debug")==1 ? "1" : "");
define("SOCIAL2BLOG_PREMIUMLINK", "https://www.social2blog.com/#premium");
define("SOCIAL2BLOG_LINK", "https://www.social2blog.com/");
define("SOCIAL2BLOG_FORCESYNC", "1");
define("SOCIAL2BLOG_NOTTOSYNC", "0");
define("SOCIAL2BLOG_CACERT", SOCIAL2BLOG_PLUGINGDIR.'splws.pem');


/** gestisce le traduzione */
add_action('plugins_loaded', 'social2blog_wanLoadTextdomain');

function social2blog_wanLoadTextdomain() {

	load_plugin_textdomain( 'social2blog-text', false, SOCIAL2BLOG_PLUGINGFOLDERNAME."/lang/"  );
}


require_once 'commons/helpers/class.social2blog-http.php';
require_once 'commons/helpers/class.social2blog-log.php';
require_once 'twitter/class.social2blog-twitter.php';
require_once 'facebook/class.social2blog-facebook.php';
require_once 'class.social2blog-serverdownload.php';
require_once 'class.social2blog-exception.php';

global $social2blogfacebook;
$social2blogfacebook = new Social2blog_Facebook();
global $social2blogtwitter;
$social2blogtwitter = new Social2blog_Twitter();


/**
 * Admin page di twitter
 */
function social2blog_twitter() {
	/**
	 * Esegue lo script
	 */
	global $social2blogtwitter;
	global $social2blogfacebook;

	include "social2blog_twitter.php";
}

/**
 * Admin page di facebook
 */
function social2blog_facebook() {
	/**
	 * Esegue lo script
	 */
	global $social2blogtwitter;
	global $social2blogfacebook;

	include "social2blog_facebook.php";
}

/**
 * Page di instagram
 */
function social2blog_instagram() {
	/**
	 * Esegue lo script
	 */
	global $social2blogtwitter;
	global $social2blogfacebook;

	include "social2blog_instagram.php";
}


/**
 * Page gallery
 */
function social2blog_gallery() {
	/**
	 * Esegue lo script
	 */
	global $social2blogtwitter;
	global $social2blogfacebook;

	include "social2blog_gallery.php";
}

/**
 *
 * Admin page del plugin
 * Questa funzione esegue l'admin page del plugin
 */
function social2blog_page() {
	global $social2blogtwitter;
	global $social2blogfacebook;

 	include "social2blog_htmlpage.php";
}
/**
 * Inizializza l'admin menu del plugin
 */
function social2blog_adminmenu() {
	global $submenu;
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

	global $social2blogtwitter;
	global $social2blogfacebook;

	add_menu_page( 'Social2Blog', 'Social2Blog', 'manage_options', 'social2blog', 'social2blog_page', plugin_dir_url( __FILE__ )."icon.png" );
	if ($social2blogfacebook->isFBConnected())
		add_submenu_page('social2blog', "Facebook", __( 'Manage Facebook', 'social2blog-text' ), 'manage_options', "social2blog-facebook", 'social2blog_facebook' );

	if ($social2blogtwitter->isTWConnected())
		add_submenu_page('social2blog', "Twitter", __( 'Manage Twitter', 'social2blog-text' ), 'manage_options', "social2blog-twitter", 'social2blog_twitter' );

	add_submenu_page('social2blog', "Instagram", __( 'Manage Instagram', 'social2blog-text' ), 'manage_options', "social2blog-instagram", 'social2blog_instagram' );
	add_submenu_page('social2blog', "Gallery", __( 'Manage Gallery', 'social2blog-text' ), 'manage_options', "social2blog-gallery", 'social2blog_gallery' );
}
add_action('admin_menu', 'social2blog_adminmenu');

/**
 * Registrazione degli script
 */
function social2blog_adminscripts() {
  wp_enqueue_script('social2blog', '/js/social2blog.js', 'jquery');
}
add_action( 'admin_init', 'social2blog_adminscripts' );

 /**
  * Controllo eliminazione categorie
  */
add_action('delete_term_taxonomy', "social2blog_checkcategory");

/**
 * Effettua il check delle categorie
 * @param unknown $tt_id
 */
function social2blog_checkcategory($tt_id){

	$tag_fb = Social2blog_Facebook::getTags();
	$tag_tw = Social2blog_Twitter::getTags();

	$tag_to_delete = "#".get_the_category_by_ID( $tt_id );

	$tags = explode( ' ', $tag_fb);
	$tags_string = "";
	$check = false;

	if ( in_array($tag_to_delete , $tags) ){
		$check = true;
		for($i = 0; $i < count($tags); $i++){
			if($tag_to_delete != $tags[$i]){
				$tags_string .= $tags[$i]. " ";
			}
		}
	}
	Social2blog_Facebook::saveTags($tags_string);

	$tags = explode( ' ', $tag_tw);
	$tags_string = "";
	if ( in_array($tag_to_delete , $tags) ){
		$check = true;
		for($i = 0; $i < count($tags); $i++){
			if($tag_to_delete != $tags[$i]){
				$tags_string .= $tags[$i]. " ";
			}
		}
	}
	Social2blog_Twitter::saveTags($tags_string);


	/**
	 * Aggiorna informazioni sul server
	 */
 	if ( $check ){
 		update_option('social2blog_advice_cat', '1');
		$serv = new Social2blog_Serverdownload();
 		$sync_server = $serv->updateServerInfo();
 		if ($sync_server != "ok"){
 			social2blog_setstate( SOCIAL2BLOG_FORCESYNC );
 		}
 	}
}
/**
 * Pulsante aggiuntivo
 *
 */

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'social2blog_addActionLink' );

function social2blog_addActionLink( $links ) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=social2blog') ) .'">Settings</a>';
   return $links;
}
/**
 * Effettua il check delle categoria durante l'attivazione
 */
function social2blog_chkcategoryonactivation(){
	$tag_fb = Social2blog_Facebook::getTags();
	$tag_tw = Social2blog_Twitter::getTags();

	$fb_tags = explode( ' ', $tag_fb);
	$new_fb_tags = "";
	$check = false;
	for ($i = 0; $i < count($fb_tags); $i++){
		$category = str_replace("#", "", $fb_tags[$i]);
		$cat_id = get_category_by_slug( $category );
		if ( $cat_id != false ){
			$new_fb_tags .=  $fb_tags[$i]." ";
		}
	}
	Social2blog_Facebook::saveTags($new_fb_tags);

	$tw_tags = explode( ' ', $tag_tw);
	$new_tw_tags = "";
	for ($i = 0; $i < count($tw_tags); $i++){
		$category = str_replace("#", "", $tw_tags[$i]);
		$cat_id = get_category_by_slug( $category );
		if ( $cat_id != false ){
			$new_tw_tags .=  $tw_tags[$i]." ";
		}
	}
	Social2blog_Twitter::saveTags($new_tw_tags);

}

 /**
  * SOCIAL hook ajax
  */
 add_action('wp_ajax_captureAccessTokenFB', 'social2blog_captureatb');
 add_action('wp_ajax_removeAccessTokenFB','social2blog_removeatf');
 add_action('wp_ajax_removeOAuthTokenTW','social2blog_removeoauthtw');


 /**
  * TWITTER action
  */
add_action( 'admin_action_loginTwitter', 'social2blog_loginTwitteraction' );

/**
 * Action che reindirizza a twitter con token temporanei
 */
function social2blog_loginTwitteraction(){

	$loginTwitter = $_REQUEST ["loginTwitter"];

	$api_key_xkoll = get_option('social2blog_apikey');
	if ($api_key_xkoll !=  $_REQUEST ["api_key"]){
		header("Location: ".SOCIAL2BLOG_LOCALURL."&error_message=".__( "APY KEY ERRATA. Twitter OAuth token non salvato.", "social2blog-text" ));
		exit();
	}

	$twitMain = new Social2blog_Twitter();

	if (!empty($loginTwitter)) {
		//
		header("Location: ".urldecode($loginTwitter));
		exit();
	} else {
		$loginTwitter = $_REQUEST ["twitter_access_token"];
		if ( !empty($loginTwitter) && $loginTwitter == "true" ){

			$auth_token = 	$_REQUEST ["oauth_token"];
			$auth_tok_secret = $_REQUEST ["oauth_token_secret"];
			$user_id = 	$_REQUEST ["user_id"];
			$screen_name = 	$_REQUEST ["screen_name"];

			$twitMain->saveOAuthToken($auth_token);
			$twitMain->saveOAuthSecret($auth_tok_secret);
			$twitMain->saveUser_id($user_id);
			$twitMain->saveUser_name($screen_name);
			header("Location: ".SOCIAL2BLOG_LOCALURL);
			exit();
		}
	}
}



//ottiene i contenuti
add_action('wp_ajax_updatecontent', 'social2blog_updateContent');
//ottiene i contenuti
add_action('wp_ajax_moddebug', 'social2blog_moddebug');

/**
 * Attivia disattiva la modalità debug
 */
function social2blog_moddebug() {
	update_option("social2blog_mod_debug", filter_var ( $_REQUEST["mod_debug"], FILTER_SANITIZE_NUMBER_INT));
}

/**
 * Scarica i contenuti da social
 */
function social2blog_updateContent() {

	$a = new Social2blog_Serverdownload();
	$social2blog_state = social2blog_retrievestate();



	if ( !isset($social2blog_state) or $social2blog_state === "0"){

		$val = $a->get_posts();
		$events = $a->get_events();

		if ($val === "ok" and $events === "ok") {
			echo "ok";
			die();
		} else {
			Social2blog_Log::error($val);
			Social2blog_Log::error($events);			
			echo "fail";
			die();
		}
	}
}

 /**
  * Memorizza l'access token e lo restituisce
  */
 function social2blog_captureatb(){

 	$access_token = $_REQUEST["access_token"];
 	$facebook = new Social2blog_Facebook();
 	$api_key_xkoll = get_option('social2blog_apikey');

 	if (!empty($access_token)) {
 		//
 		$facebook->saveAccessToken($access_token, $api_key_xkoll);

 		echo $facebook->getAccess_token();
 	} else {
 		$facebook->retrieveAccessToken();
 		echo $facebook->getAccess_token();
 	}
	exit();
 }

 /**
  * Rimuove l'access token
  */
 function social2blog_removeatf(){
	global $social2blogfacebook;
 	$social2blogfacebook->removeAccessToken();
 	echo "Rimosso access_token";
 }

/**
 * Rimuove l'oauth token
 */
function social2blog_removeoauthtw(){
	global $social2blogtwitter;
	$social2blogtwitter->removeInfoDB();
}


/**
 * Accoda CSS e JS
 */
add_action( 'admin_enqueue_scripts', 'social2blog_loadstyle' );
function social2blog_loadstyle() {
	wp_enqueue_style( 'social2blog__css', plugins_url( 'css/social2blog_style.css', __FILE__ ), false, '1.0.0' );
	wp_enqueue_style( 'tm_css', plugins_url( '/tagManager/tagmanager.css', __FILE__ ), false, '1.0.0' );
}
function social2blog_admininit() {
	//wp_register_script( 'tagmanager-script',  );
	wp_enqueue_script( 'tagmanager-script', plugins_url( '/tagManager/tagmanager.js', __FILE__ ), '','', true );
}
add_action( 'admin_init', 'social2blog_admininit');


/**
 * Controllo disattivazione Social2Blog
 */
register_deactivation_hook( __FILE__, 'social2blog_deactivation' );

function social2blog_deactivation(){
	$apikey = get_option('social2blog_apikey');
	$url = SOCIAL2BLOG_SERVER_URL."?api_key=" . $apikey . "&act=deactivate";
	
	Social2blog_Http::requestHttp($url);
	
}
register_uninstall_hook(__FILE__, 'social2blog_uninstall');

function social2blog_uninstall() {
	$apikey = get_option('social2blog_apikey');
	$url = SOCIAL2BLOG_SERVER_URL."?api_key=" . $apikey . "&act=uninstall";
	
	Social2blog_Http::requestHttp($url);

	delete_option('social2blog_apikey');
	delete_option('social2blog_stateeventcalendar');
	delete_option('social2blog_outofsync');
	delete_option('social2blog_advice_cat');
	delete_option('social2blog_tw_user_name');
	delete_option('social2blog_isactive');
	delete_option('social2blog_mod_debug');
	delete_option('social2blog__inst_postStatus');
	delete_option('social2blog_tw_postStatus');
	delete_option('social2blog_fb_tags');
	delete_option('social2blog_fb_postStatus');
	delete_option('social2blog_fb_post');
	delete_option('social2blog_fb_event');
	delete_option('social2blog_fb_EventStatus');
	delete_option('social2blog_fb_organizerEvent');
	delete_option('social2blog_fb_postAuthor');
	delete_option('social2blog_fb_access_token');
	delete_option('social2blog_fb_access_token_expire');
	delete_option('social2blog_fb_access_token_expire');
	delete_option('social2blog_fb_id_page');
	delete_option('social2blog_fb_title_count');
	delete_option('social2blog_tw_oauth_token');
	delete_option('social2blog_tw_oauth_secret');
	delete_option('social2blog_tw_user_id');
	delete_option('social2blog_tw_title_count');
	delete_option('social2blog_tw_post_foto');
	delete_option('social2blog_tw_tags');
	delete_option('social2blog_tw_postAuthor');
	delete_option('social2blog_tw_postStatus');

}



/**
 * Registrazione del plugin
 */
function  social2blog_socialactivate() {
	/**
	 * Controllo versione PHP e Blog
	 *  1 = tutto ok
	 *  0 = PHP obsoleto
	 * -1 = Blog Obsoleto
	 * -2 = Permessi /wp-content/uploads non corretti
	 * -3 = Plug-in The Event Calendar mancante
	 */
	$check_version = social2blog_mincaratactivate();


	if ($check_version == 1){
		$php_arr = explode("-", phpversion());
		$php = $php_arr[0];

		$apikey = get_option('social2blog_apikey');
		if (empty($apikey)) {
			$req = array(
					'url' 			=> get_bloginfo( 'siteurl', 'raw' ),
				'name'			=> get_bloginfo( 'name', 'raw' ),
				'email'			=> get_bloginfo( 'admin_email', 'raw' ),
				'wp_version'	=> get_bloginfo( 'version', 'raw' ),
					'php_version'	=> $php
			);
		} else {
			$req = array(
				'url' 			=> get_bloginfo( 'siteurl', 'raw' ),
				'name'			=> get_bloginfo( 'name', 'raw' ),
				'email'			=> get_bloginfo( 'admin_email', 'raw' ),
				'wp_version'	=> get_bloginfo( 'version', 'raw' ),
					'php_version'	=> $php,
					'api_key'		=> $apikey
			);
		}

		$enc_req = urlencode(json_encode($req));

		$url = SOCIAL2BLOG_SERVER_URL;
		$action = '?act=addblog&xk_data='.$enc_req;
		$_xresponse = Social2blog_Http::requestHttp($url.$action);

		social2blog_apikeycapture($_xresponse);

		/**
		 * Aggiorna informazioni sul server
		 */
		social2blog_chkcategoryonactivation();
		$server = new Social2blog_Serverdownload();
		$result = $server->updateServerInfo();
		if ($result === "ok"){
			social2blog_setstate( SOCIAL2BLOG_NOTTOSYNC );
		}else {
			social2blog_setstate( SOCIAL2BLOG_FORCESYNC );
		}


	}
	elseif ($check_version == -3) {
		social2blog_setstateeventscalendar(false);
		?><?php
	} elseif ($check_version == -2){
		deactivate_plugins( SOCIAL2BLOG_PLUGINGDIR . SOCIAL2BLOG_PLUGINGNAME);
		?>
    	<div class='notice error my-acf-notice is-dismissible' style="width: 95%;" >
        <p><?php echo __( 'Permessi di scrittura insufficienti su wp-content/uploads/ .', 'social2blog-text' ); ?></p>
   		</div>
	    <?php
	    die();
	} elseif ($check_version == -1){
		deactivate_plugins( SOCIAL2BLOG_PLUGINGDIR . SOCIAL2BLOG_PLUGINGNAME);
		?>
    	<div class='notice error my-acf-notice is-dismissible' style="width: 95%;" >
        <p><?php echo __( 'Versione Wordpress obsoleta. Versione minima: 4.4 .', 'social2blog-text' ); ?></p>
   		</div>
	    <?php
	    die();
	} elseif ($check_version == 0){
		deactivate_plugins( SOCIAL2BLOG_PLUGINGDIR . SOCIAL2BLOG_PLUGINGNAME);
		?>
    	<div class='notice error my-acf-notice is-dismissible' style="width: 95%;" >
        <p><?php echo __( 'Versione PHP obsoleta! Versione minima: 5.4.0.', 'social2blog-text' ); ?></p>
   		</div>
	    <?php
	    die();
	} else {
		deactivate_plugins( SOCIAL2BLOG_PLUGINGDIR . SOCIAL2BLOG_PLUGINGNAME);
		die();
	}


}
register_activation_hook( __FILE__, 'social2blog_socialactivate' );

/**
 * Cattura APIKey
 */
function social2blog_apikeycapture($response){

	$info = json_decode($response);
	if($info->state == "success"){
		$temp = $info->body;
		$apikey = $temp->api_key;
		social2blog_apikeysave($apikey);
	}
	else{
		deactivate_plugins( SOCIAL2BLOG_PLUGINGDIR . SOCIAL2BLOG_PLUGINGNAME);
		return false;
	}

}

/**
 * Salvataggio ApiKey sul DB
 */
function social2blog_apikeysave($apikey){
	update_option('social2blog_apikey', $apikey);
}

/**
 * Cancella ApiKey dal DB
 */
function social2blog_apikey_remove(){
	remove_option('social2blog_apikey');
}

/**
 * Gestione del messaggio di errore di API KEY
 */
$apik = get_option( 'social2blog_apikey' );
if( empty( $apik ) ) {
	add_action( 'admin_notices', 'social2blog_acfadminnotice' );
}

/** Gestione avviso */
$is_error = isset($_REQUEST["alert_message"]);
if ($is_error) {
	add_action( 'admin_notices', 'social2blog_alertmessage' );
}

/**
 * Registrazone degli input alert message
 */
function social2blog_alertmessage() {
	?>
    <div class='notice update-nag my-acf-notice is-dismissible' style="width: 95%;">
    	<p><?php echo $_REQUEST["alert_message"]; ?></p>
    </div>

    <?php
}

/** Gestione errore */
$is_error = isset($_REQUEST["error_message"]);
if ($is_error) {
	add_action( 'admin_notices', 'social2blog_inerrormessage' );
}

/**
 * Messaggi di errore
 */
function social2blog_inerrormessage() {
	?>
    <div class="notice error my-acf-notice is-dismissible" >
        <p><?php echo $_REQUEST["error_message"]; ?></p>
    </div>

    <?php
}

/**
 * Gestione admin notice
 */
function social2blog_acfadminnotice() {
	?>
    <div class='notice error my-acf-notice is-dismissible ' style="width: 95%;" >
        <p><?php echo __( 'API KEY NOT AVAILABLE. Please reactivate the plugin or contact info@xkoll.com!', 'social2blog-text' ); ?></p>
    </div>

    <?php
}

/**
 * Caratteristiche minime
 */
function social2blog_mincarat (){

	$var = social2blog_mincaratactivate();

	if ($var == 0 or $var == -1 or $var == -2){
		return $var;
	}

	if ( ! function_exists( 'is_plugin_active' ) or !  function_exists( 'get_plugin_data' )) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$The_Events_Calendar = "the-events-calendar/the-events-calendar.php";
	$pluginFolder = dirname(plugin_dir_path(__FILE__));
	$pluginEventPath = $pluginFolder ."/".$The_Events_Calendar;

	$pluginEvent =  is_plugin_active( $The_Events_Calendar );


	if (!$pluginEvent) {
		return -3;
	}
	$theec = get_plugin_data($pluginEventPath ,$markup = true, $translate = true);
	$The_Events_Calendar_Version =  $theec['Version'];
	$versionEventsCalendar_int = str_replace (".", "", $The_Events_Calendar_Version);
	$versionEventsCalendar_int = ( strlen($versionEventsCalendar_int) >= 3) ? $versionEventsCalendar_int : $versionEventsCalendar_int."0" ;
	if ( $versionEventsCalendar_int < 412 ) {
		return -3;
	}

	return 1;
}


/**
 * Caratteristiche minime
 */
function social2blog_mincaratactivate (){

	$blog_version = get_bloginfo('version', 'raw');
// 	$folder_a = wp_upload_dir();
// 	$folder = $folder_a['basedir'];
	$dir_writable = social2blog_testfile();





	if ( version_compare(PHP_VERSION, '5.4.0', '<') )  {
		return 0;
	}elseif (version_compare($blog_version,  '4.4.0', '<' )){
		return -1;
	}elseif ( $dir_writable == false ){
		return -2;
	}

	return 1;
}

/**
 * Test creazione file
 */
function social2blog_testfile(){
	$folder_a = wp_upload_dir();
	$folder = $folder_a['basedir'];
	$folder_exist = wp_mkdir_p( $folder );

	$file = file_put_contents($folder."/test.txt", "test");
	$result = $file == "4" ? true : false;
	return $result;
}

/**
 * Parte della schedulazione del controllo sui nuovi post
 */

//On plugin activation schedule our daily database backup
register_activation_hook( __FILE__, 'social2blog_checknewpostschedule' );
function social2blog_checknewpostschedule(){
	//Use wp_next_scheduled to check if the event is already scheduled
	$timestamp = wp_next_scheduled( 'social2blog_checknewpostschedule' );

	//If $timestamp == false esegue
	if( $timestamp == false ){
		//Schedule the event for right now, then to repeat every 15-min using the hook 'wi_add_QuindiciMin_schedule'
		wp_schedule_event( time(), 'QuindiciMin', 'social2blog_checknewpostschedule' );
	}
}

/**
 * Automaticamente scarica gli ultimi post ogni 15 minuti
 */
add_action( 'social2blog_checknewpostschedule', 'social2blog_checknewpost' );

function social2blog_checknewpost(){

	$xkoll_state = social2blog_retrievestate();

	if ($xkoll_state === "0"){
		$a = new Social2blog_Serverdownload();
		$val = $a->get_posts();
		$val = $a->get_events();
		if( SOCIAL2BLOG_DEBUG ){
			Social2blog_Log::debug("!-- CHECK NEW POST/EVENT SCHEDULED --!");
		}
	}
	else{
		if ( SOCIAL2BLOG_DEBUG ){
			Social2blog_Log::debug("!-- CHECK NEW POST/EVENT SCHEDULED BLOCKED, SYNC WITH SERVER --!");
		}
	}
}

/**
 * Schedule
 */
add_filter( 'cron_schedules', 'social2blog_quindicischedule' );
function social2blog_quindicischedule( $schedules ) {
	$schedules['QuindiciMin'] = array(
			'interval' =>15 * 60 /*, //15 minutes * 60 seconds
			'display' =>_( 'Once 15min', 'my-plugin-domain' )*/
	);
	/*
	 You could add another schedule by creating an additional array element
	 $schedules['biweekly'] = array(
	 'interval' => 7 * 24 * 60 * 60 * 2
	 'display' => __( 'Every Other Week', 'my-plugin-domain' )
	 );
	 */

	return $schedules;
}

// Gestione OUT OF SYNC
add_action('admin_footer', 'social2blog_checkstate', 1);
add_action('wp_ajax_syncserver', 'social2blog_syncserver');

/**
 * Gestione della sincronizzazione con il server
 */
function social2blog_syncserver(){
	if ( SOCIAL2BLOG_DEBUG ){
		Social2blog_Log::debug("!-- Sync Server Forced --!");
	}
	$server = new Social2blog_Serverdownload();
	$result = $server->updateServerInfo();

	if ($result === "ok"){
		social2blog_setstate( SOCIAL2BLOG_NOTTOSYNC );
		echo "ok";
		exit();
	}elseif ($result === "apikey_errata"){
		social2blog_setstate( SOCIAL2BLOG_FORCESYNC );
		echo "error_api_key";
		exit();
	}else{
		social2blog_setstate( SOCIAL2BLOG_FORCESYNC );
		echo "error";
		exit();
	}

}
/**
 *
 * @param int $state
 * 0 = ok
 * 1 = non sincronizzato con il server
 */

function social2blog_setstate($state){
	update_option('social2blog_outofsync', $state);
}

/** Controlla se può utilizzare events calendar */
function social2blog_setstateeventscalendar($state){
	update_option('social2blog_stateeventcalendar', $state);
}
/** ottine lo stato del plugin event calendar */
function get_state_event_calendar($state){
	get_option('social2blog_stateeventcalendar', $state);
}

/**
 * Effettua il check della sincronizzazione
 * @return boolean
 */
function check_sync(){
	$xkoll_state = social2blog_retrievestate();
	if ($xkoll_state === 0 || empty($xkoll_state)){
		return false;
	} else {
		return true;
	}
}
/**
 * Controlla il valore dell'out of sync
 * @return mixed|boolean
 */
function social2blog_retrievestate(){
	return get_option('social2blog_outofsync');
}

/**
 * Controlla lo stato di social2wp
 */
function social2blog_checkstate(){
	$url = $_SERVER["REQUEST_URI"];
	$stra = "admin-ajax.php";
	$pos = strpos($url, $stra);

	// Note o uso de ===.  Simples == não funcionaria como esperado
	// por causa da posição de 'a' é 0 (primeiro) caractere.
	if ($pos === false) {

	if(social2blog_retrievestate() === "1"){
		?>
			<div class='notice update-nag my-acf-notice is-dismissible ' style="width: 95%;" >
				<p><?php _e( 'Social2Blog is out of sync, please click on <i>Synchronize with server</i>. If problem persist please contact info@social2blog.com.', 'social2blog-text' ) ?></p>
				<p>Social2Blog non è sincronizzato, clicca su <i>Sincronizza server</i> e se il problema persiste contatta  info@social2blog.com</p>
			</div>
		<?php
	}

	}
}
/**
 * Hook per l'attivazione del debug
 */
add_action('admin_footer', 'social2blog_debug', 1);
function social2blog_debug(){
	if( SOCIAL2BLOG_DEBUG ){
			?>
			<div class='notice update-nag my-acf-notice is-dismissible ' style="width: 95%;" >
				<p><?php echo __( '<b>Social2Blog</b>: Debug mode enabled, the log file may become very large.', 'social2blog-text' ); ?></p>
			</div>
		<?php
	}
}

/**
 * Controllo delle categorie
 */
add_action('admin_footer', 'social2blog_catadv', 1);
function social2blog_catadv(){
	$adv_cat = get_option("social2blog_advice_cat");
	if( $adv_cat == 1 ){
		?>
			<div class='notice update-nag cat-adv-notice-s2w is-dismissible ' style="width: 95%;">
				<p><?php echo __( '<b>Social2Blog</b>: Category deleted. The corresponding tag has been removed by Social2Blog.', 'social2blog-text' ); ?></p>
			</div>
		<?php
	}
}

add_action('admin_init', 'social2blog_closeadvnotice', 1);
function social2blog_closeadvnotice(){
	update_option('social2blog_advice_cat', '0');
}


/**
 * Inserisce i custom field
 */

function social2blog_metaboxmarkup($object) {
	wp_nonce_field(basename(__FILE__), "social2blog-meta-box-nonce");

	?>

<div>

            <label for="social2blog-canonical-checkbox"><?php echo __( 'Do not use Canonical URL', 'social2blog-text' );?></label>
            <?php
                $checkbox_value = get_post_meta($object->ID, "social2blog-canonical-checkbox", true);

                if($checkbox_value == "")
                {
                    ?>
                        <input name="social2blog-canonical-checkbox" type="checkbox" value="true">
                    <?php
                }
                else if($checkbox_value == "true")
                {
                    ?>
                        <input name="social2blog-canonical-checkbox" type="checkbox" value="true" checked>
                    <?php
                }
            ?>
            <br /> <br />
            Canonical URL:
            <?php
            $fb_post = get_post_meta($object->ID, "social2blog_facebook_post_id", true);

            $tw_post = get_post_meta($object->ID, "social2blog_twitter_post_id", true);

            if (!empty($fb_post)) {

            	$fbcoords = explode("_", $fb_post);
            	$fb_url = "https://www.facebook.com/".$fbcoords[0]."/posts/".$fbcoords[1];

            	?>
            				<a href="<?php echo $fb_url?>" target="_blank">	<?php echo $fb_url?></a>

              <?php } elseif (!empty($tw_post)) {

            				//social2blog_tw_user_name

            				//tw user
            				$tw_user = get_option('social2blog_tw_user_name');

            				$tw_url = "https://twitter.com/".$tw_user."/status/".$tw_post; ?>

            				<a href="<?php echo $tw_url?>" target="_blank">	<?php echo $tw_url?></a>

            			<?php } else {
            				rel_canonical();
            			}



            ?>
            <blockquote cite="https://en.wikipedia.org/wiki/Canonical_link_element">
            <?php echo __( 'A canonical link element is an HTML element that helps webmasters prevent duplicate content issues by specifying the "canonical" or "preferred" version of a web page as part of search engine optimization.', 'social2blog-text' );?>
        	<a href="https://en.wikipedia.org/wiki/Canonical_link_element" target="_blank">Link</a>
        	</blockquote>

        </div>

<?php }

function add_social2blogmetabox() {
	global $post;
	$is_s2b = get_post_meta($post->ID, "social2blog_post_id", true);
	if (!empty($is_s2b))	 {
		add_meta_box("social2blog-canonical-checkbox", "Social2Blog", "social2blog_metaboxmarkup", "post", "normal", "default", null);
	}
}

add_action("add_meta_boxes", "add_social2blogmetabox");

/** salva il meta box */
function save_social2blogmetabox($post_id, $post, $update)
{

	if (!isset($_POST["social2blog-meta-box-nonce"]) || !wp_verify_nonce($_POST["social2blog-meta-box-nonce"], basename(__FILE__)))
		return $post_id;

		if(!current_user_can("edit_post", $post_id))
			return $post_id;

			if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
				return $post_id;

				$slug = "post";
				if($slug != $post->post_type)
					return $post_id;


					$social2blog_canonicalcheckbox = "";

					if(isset($_POST["social2blog-canonical-checkbox"]))
					{
						$social2blog_canonicalcheckbox = $_POST["social2blog-canonical-checkbox"];
					}
					update_post_meta($post_id, "social2blog-canonical-checkbox", $social2blog_canonicalcheckbox);

}

add_action("save_post", "save_social2blogmetabox", 10, 3);

/** rel canonical in header */
function social2blog_relcan()
{
	global $post;
	
	if (!is_object($post)) {
		return;
	}
	
	$post_id = $post->ID;
	
	$checkbox_value = get_post_meta($post_id, "social2blog-canonical-checkbox", true);
	
	if ($checkbox_value === true) {
		return;
	} else {
		$fb_post = get_post_meta($post_id, "social2blog_facebook_post_id", true);

		$tw_post = get_post_meta($post_id, "social2blog_twitter_post_id", true);

		if (!empty($fb_post)) {

			$fbcoords = explode("_", $fb_post);
			$fb_url = "https://www.facebook.com/".$fbcoords[0]."/posts/".$fbcoords[1];

			?>
				<link rel="canonical" href="<?php echo $fb_url?>">

  		<?php 
		} elseif (!empty($tw_post)) {

				//social2blog_tw_user_name

				//tw user
				$tw_user = get_option('social2blog_tw_user_name');

				$tw_url = "https://twitter.com/".$tw_user."/status/".$tw_post; ?>

				<link rel="canonical" href="<?php echo $tw_url?>">

			<?php } else {
				rel_canonical();
			}

	 }

}
add_action('init', 'social2blog_init');
function social2blog_init() {
	remove_action( 'wp_head', 'rel_canonical' );
 	add_action( 'wp_head', 'social2blog_relcan' );
}


/**
 * controlla se è attivo
 */
function isSocial2blog_Registered() {
	$reg = get_option('social2blog_isactive');
	if (empty($reg)) {
		return false;
	} else {
		return true;
	}
}
function setSocial2blog_Registered() {
	update_option('social2blog_isactive', "1");
}
/**
 * Messaggio per l'attivazione del plugin
 *
 */
add_action('admin_notices', 'social2blog_activation');
function social2blog_activation() {
	$isreg = isSocial2blog_Registered();
	if ($isreg == true) {
		return;
	} else {
		$serv = new Social2blog_Serverdownload();
		$rego = $serv->verifyRemoteRegister();
		if ($rego == true) {
			setSocial2blog_Registered();
			return;
		}
	}

	$api_key_xkoll = get_option('social2blog_apikey');

?>
<div class="notice social2blog_activate_notice">
	<div class="social2blog_activate">
		<div  class="social2blog_activate_logo">
			<img src='<?php echo plugin_dir_url( __FILE__ )."icon-big.png"?>' style="margin-right: 10px" />
			<span style="color: #04173b">Social</span><span style="color: #fe3458">2</span><span style="color: #0a18a4">Blog</span>
		</div>
		<div  class="social2blog_activate_txt">
		<?php if (empty($api_key_xkoll)) { ?>

			We apologize, there is a problem in activation. Please contact info@social2blog.com. All your message is for us a precious help.<br />
			Ci scusiamo, c'è un problema nell'attivazione. Prego contattare info@social2blog.com. Ogni tua segnalazione è per noi un prezioso aiuto. <br />

		<?php } else { ?>
			Plugin not fully activated yet / Completa l'attivazione del plugin
		<?php } ?>
		</div>
		<div  class="social2blog_activate_button_container">
				<form action="https://www.social2blog.com/activate.php" method="POST">
						<input type="submit" class="social2blog_activate_button" value="Activate / Attiva"/>
						<input type="hidden" value="<?php echo $api_key_xkoll ?>" name="api_key" />
						<input type="hidden" value="activate" name="action" />
				</form>
		</div>
	</div>
</div>
<?php }
