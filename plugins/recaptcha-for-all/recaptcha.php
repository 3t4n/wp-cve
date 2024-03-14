<?php /*
Plugin Name: reCAPTCHA For All
Description: Protect ALL pages of your site against Spam and Hackers bots with reCAPTCHA Version: 1.30
Version: 1.53
Domain Path: /language
Author: Bill Minozzi
Author URI: http://billminozzi.com
Text Domain: recaptcha-for-all
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
// Make sure the file is not directly accessible.
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
// ob_start();
   // die(var_export(__LINE__));
 
$recaptcha_for_all_plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
$recaptcha_for_all_plugin_version = $recaptcha_for_all_plugin_data['Version'];
$recaptcha_for_all_sitekey = trim(sanitize_text_field(get_option('recaptcha_for_all_sitekey', '')));
$recaptcha_for_all_secretkey = trim(sanitize_text_field(get_option('recaptcha_for_all_secretkey', '')));
// active?
$recaptcha_for_all_settings = trim(sanitize_text_field(get_option('recaptcha_for_all_settings', '')));

$recaptcha_for_all_settings_provider = trim(sanitize_text_field(get_option('recaptcha_for_all_settings_provider', 'google')));

$recaptcha_for_all_update = trim(sanitize_text_field(get_option('recaptcha_for_all_update', '')));


$recaptcha_for_all_recaptcha_score = trim(sanitize_text_field(get_option('recaptcha_for_all_recaptcha_score', '')));
define('RECAPTCHA_FOR_ALLVERSION', $recaptcha_for_all_plugin_version);
define('RECAPTCHA_FOR_ALLPATH', plugin_dir_path(__file__));
define('RECAPTCHA_FOR_ALLURL', plugin_dir_url(__file__));
define('RECAPTCHA_FOR_ALL_IMAGES', plugin_dir_url(__file__) . 'images');
$recaptcha_for_all_plugin = plugin_basename(__FILE__);

$recaptcha_for_all_visitor_ip = recaptcha_for_all_findip();
$recaptcha_for_all_visitor_ua = trim(recaptcha_for_all_get_ua());
$recaptcha_for_all_string_whitelist = implode(PHP_EOL, array_map('sanitize_textarea_field', explode(PHP_EOL, get_site_option('recaptcha_for_all_string_whitelist', ''))));
$arecaptcha_for_all_string_whitelist = explode(PHP_EOL, $recaptcha_for_all_string_whitelist);
$recaptcha_for_all_ip_whitelist = trim(get_site_option('recaptcha_for_all_ip_whitelist', ''));


if(recaptcha_for_all_maybe_search_engine())
  return;

$arecaptcha_for_all_ip_whitelist = explode(PHP_EOL, $recaptcha_for_all_ip_whitelist);


for ($i = 0; $i < count($arecaptcha_for_all_ip_whitelist); $i++) {
    $arecaptcha_for_all_ip_whitelist[$i] = trim(sanitize_text_field($arecaptcha_for_all_ip_whitelist[$i]));
    if (!filter_var($arecaptcha_for_all_ip_whitelist[$i], FILTER_VALIDATE_IP))
        $arecaptcha_for_all_ip_whitelist[$i] = '';
}
$recaptcha_for_all_ip_whitelist = implode(PHP_EOL, $arecaptcha_for_all_ip_whitelist);


$arecaptcha_for_all_slugs = array_map('sanitize_textarea_field', explode(PHP_EOL, get_option('recaptcha_for_all_slugs', '')));
$recaptcha_for_all_pages = trim(sanitize_text_field(get_option('recaptcha_for_all_pages', 'yes_all')));


$recaptcha_for_all_background = trim(sanitize_text_field(get_option('recaptcha_for_all_background', 'yes')));
$recaptcha_request_url = esc_url($_SERVER['REQUEST_URI']);


if(is_admin()){
  add_action('plugins_loaded', 'recaptcha_localization_init');

}


if (
    !isset($_COOKIE['recaptcha_cookie'])
    and !recaptcha_for_all_maybe_search_engine()
    and !recaptcha_for_all_isourserver()
    and !recaptcha_for_all_is_ip_whitelisted($recaptcha_for_all_visitor_ip, $arecaptcha_for_all_ip_whitelist)
    and !recaptcha_for_all_is_string_whitelisted($recaptcha_for_all_visitor_ua, $arecaptcha_for_all_string_whitelist)
) {

    // google
    if (isset($_POST['token'])) {
        $token = sanitize_text_field($_POST['token']);

         // die(var_export($token));

        $action = sanitize_text_field($_POST['action']);
        $response = (array)wp_remote_get(sprintf('https://www.recaptcha.net/recaptcha/api/siteverify?secret=%s&response=%s', $recaptcha_for_all_secretkey, $token));
       
       
        $recaptchaResponse = isset($response['body']) ? json_decode($response['body'], 1) : ['success' => false, 'error-codes' => ['general-fail']];
        //  (1.0 is very likely a good interaction, 0.0 is very likely a bot). 
       
        // fail
        if (!$recaptchaResponse["success"]) {
            add_action('parse_query', 'recaptcha_block');
            return; // fail...
        }

        // Block...
        $recaptcha_for_all_recaptcha_score = $recaptcha_for_all_recaptcha_score / 10;
        if ($recaptchaResponse["score"] < $recaptcha_for_all_recaptcha_score) {

            if ($recaptcha_for_all_settings == 'yes') 
              add_action('parse_query', 'recaptcha_block');
        }

        // ok
        recaptcha_for_all_add_stats_ok();
        add_action('wp_enqueue_scripts', 'recaptcha_for_all_register_cookie', 1000);
        return;
    }

    // turnstile
    if(isset($_POST['cf-turnstile-response']) and !empty($_POST['cf-turnstile-response'] )) {

       // if(empty($_POST['cf-turnstile-response']) )
         //  die(var_export(__LINE__));


          $results = array();
          if (empty($postdata) && isset($_POST['cf-turnstile-response'])) {
              $postdata = sanitize_text_field($_POST['cf-turnstile-response']);
          }   

          // bill 24
          // die(var_export($postdata));

         
          if ($recaptcha_for_all_sitekey && $recaptcha_for_all_secretkey) {
              $headers = array(
                  'body' => [
                      'secret' => $recaptcha_for_all_secretkey,
                      'response' => $postdata
                  ]
              );


                $verify = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', $headers);
                $verify = wp_remote_retrieve_body($verify);


               // die(var_export($verify));


                if(!$verify){
                   return;
                   //add_action('parse_query', 'recaptcha_block');
                }

                $response = json_decode($verify);


                if($response->success) {

                    recaptcha_for_all_add_stats_ok();
                    add_action('wp_enqueue_scripts', 'recaptcha_for_all_register_cookie', 1000);
                    return; // works...

                }

                // die(var_export($response));

                // block
                add_action('parse_query', 'recaptcha_block');
                return; // fail...

                foreach ($response as $key => $val) {
                    if ($key == 'error-codes') {
                        foreach ($val as $key => $error_val) {
                            $results['error_code'] = $error_val;
                        }
                    }
                }

                 
            }
            else
              return; // fail.. missing keys...
            
      
    }
    add_action('wp_enqueue_scripts', 'recaptcha_for_all_add_scripts', 1000);

    add_action('wp_enqueue_scripts', 'recaptcha_for_all_enqueueScripts', 1000);


    // desvia if not cookie

    if( !isset($_COOKIE['recaptcha_cookie'])) {
            // if ($recaptcha_for_all_settings == 'yes' and !is_admin()){ 
        if ($recaptcha_for_all_settings == 'yes'){ 
            add_action('parse_query', 'recaptcha_is_active');
        }
    }

    /*
    if ($recaptcha_for_all_settings == 'yes') {
        if (!empty($recaptcha_for_all_sitekey) and !empty($recaptcha_for_all_secretkey))
            add_filter('template_include', 'recaptcha_for_all_page_template');
    }
    */
}
else
    add_action('wp_enqueue_scripts', 'recaptcha_for_all_add_scripts', 1000);


if (!is_admin()) {
    $recaptcha_for_all_settings_china = trim(sanitize_text_field(get_option('recaptcha_for_all_settings_china', '')));
    if ($recaptcha_for_all_settings_china == 'yes') {
        if (isset($_COOKIE['recaptcha_cookie'])) {
            $recaptcha_fingerprint = sanitize_text_field($_COOKIE['recaptcha_cookie']);
            if (!empty($recaptcha_fingerprint)) {
                if (
                    strpos($recaptcha_fingerprint, 'Asia/Shanghai') !== false
                    or strpos($recaptcha_fingerprint, 'Asia/Hong_Kong') !== false
                    or strpos($recaptcha_fingerprint, 'Asia/Macau') !== false
                ){
                    header('HTTP/1.1 403 Forbidden');
                    header('Status: 403 Forbidden');
                    header('Connection: Close');
                    http_response_code(403);
                    wp_die("Forbidden");
                }
            }
        }
    }
}



if (is_admin()) {
    if (empty($recaptcha_for_all_sitekey) or empty($recaptcha_for_all_secretkey)) {
        add_action('admin_notices', 'recaptcha_for_all_alert_keys');
    }

    add_action('admin_init', 'recaptcha_for_all_add_admstylesheet');
    add_action('admin_menu', 'recaptcha_for_all_memory_init');
    //register_activation_hook(__FILE__, 'recaptcha_for_all_was_activated');
    add_action('admin_init', 'recaptcha_for_all_check_string_whitelist');

    //  add_filter("plugin_action_links_$plugin", 'recaptcha_for_all_plugin_settings_link');
    add_filter("plugin_action_links_$recaptcha_for_all_plugin", 'recaptcha_for_all_settings_link');



    if (!recaptcha_for_all_is_ip_whitelisted($recaptcha_for_all_visitor_ip, $arecaptcha_for_all_ip_whitelist)) {
        //  update_option('recaptcha_for_all_ip_whitelist', $recaptcha_for_all_ip_whitelist . PHP_EOL . $recaptcha_for_all_visitor_ip);
    }


    // register_activation_hook(__FILE__, 'recaptcha_for_all_plugin_activate');

    if (is_admin() or is_super_admin()) {

        /*
        if (get_option('recaptcha_for_all_was_activated', '0') == '1') {

            
            // add_action('admin_notices', 'recaptcha_for_all_plugin_act_message');
            $r = update_option('recaptcha_for_all_was_activated', '0');
            if (!$r) {
                add_option('recaptcha_for_all_was_activated', '0');
            }
            
        

            if (!recaptcha_for_all_is_ip_whitelisted($recaptcha_for_all_visitor_ip, $arecaptcha_for_all_ip_whitelist)) {
                update_option('recaptcha_for_all_ip_whitelist', $recaptcha_for_all_ip_whitelist . PHP_EOL . $recaptcha_for_all_visitor_ip);
            }

        }
        */

    }


    add_action('wp_ajax_recaptcha_dismiss_notice2', 'recaptcha_dismiss_notice2');
    add_action('wp_head', 'recaptcha_ajaxurl');
    add_action('wp_ajax_recaptcha_for_all_dismissible_notice', 'recaptcha_for_all_dismissible_notice');
    register_activation_hook(__FILE__, 'recaptcha_for_all_activated');

   // if (get_option('recaptcha_for_all_dismiss', true) and is_admin())
    //add_action('admin_notices', 'recaptcha_for_all_dismiss_admin_notice');


    if (is_admin() or is_super_admin()   ) {

        add_action('wp_head', 'recaptcha_for_all_ajaxurl');

        function recaptcha_for_all_ajaxurl()
        {
            echo '<script type="text/javascript">
               var ajaxurl = "' . admin_url('admin-ajax.php') . '";
             </script>';
        }

        add_action( 'wp_ajax_recaptcha_for_all_image_select', 'recaptcha_for_all_image_select' );
        add_action('admin_enqueue_scripts', 'recaptcha_for_all_adm_enqueue_scripts1');


         
        
/*
        // Verifique se $_SERVER['REQUEST_URI'] está definido antes de usá-lo
        $current_page = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

        // Sanitize a URL antes de usá-la
        $current_page_sanitized = esc_url($current_page);

       // die($current_page_sanitized);
        //wp-admin/tools.php?page=recaptcha_for_all_admin_page&tab=keys

        // Verifique se a URL da página contém a string desejada
        if (strpos($current_page_sanitized, 'page=recaptcha_for_all_admin_page&tab=keys') !== false) {
           // add_action('admin_enqueue_scripts', 'recaptcha_for_all_adm_enqueue_scripts2');
           // die('-----------------------------');
        } 
*/





        // Obtenha a URL atual da página no painel de administração
        $current_page_url = admin_url(add_query_arg(array()));
        // https://recaptchaforall.com/wp-admin/wp-admin/tools.php?page=recaptcha_for_all_admin_page&tab=keys


        if (strpos($current_page_url, 'page=recaptcha_for_all_admin_page&tab=keys') !== false) {
                add_action('admin_enqueue_scripts', 'recaptcha_for_all_adm_enqueue_scripts3');
    
        } 

        $r = get_option('recaptcha_for_all_was_activated', '0') ;
        //die(var_export($r));
        if (get_option('recaptcha_for_all_was_activated', '0') == '1') {
                add_action('admin_enqueue_scripts', 'recaptcha_for_all_adm_enqueue_scripts2');
            }
    }

  
    add_action('admin_enqueue_scripts', 'recaptcha_for_all_load_upsell');
    add_action('wp_ajax_recaptcha_for_all_install_plugin', 'recaptcha_for_all_install_plugin');
  

}

/*
$page = ob_get_contents();
ob_end_clean();
error_log($page,0);
*/

// 08 2023
require_once ABSPATH . 'wp-includes/pluggable.php';
// check 4 errors...

if(is_admin() and current_user_can("manage_options")){
    if (!class_exists('Bill_Class_Diagnose') and !function_exists('bill_my_custom_hooking_function')) {

		function bill_my_custom_hooking_function() {
            $plugin_slug = "recaptcha-for-all"; // Replace with your actual text domain
            $plugin_text_domain = "recaptcha-for-all"; // Replace with your actual text domain
                $notification_url = "https://wpmemory.com/fix-low-memory-limit/";
			$notification_url2 =
				"https://wptoolsplugin.com/site-language-error-can-crash-your-site/";
			require_once(RECAPTCHA_FOR_ALLPATH . "includes/checkup/bill_class_diagnose.php");
		}
		add_action('init', 'bill_my_custom_hooking_function');
    }
}
// catch js errors...
   if (!class_exists('bill_catch_errors') and !function_exists('bill_my_custom_hooking_function2')) {

    function bill_my_custom_hooking_function2() {
        require_once(RECAPTCHA_FOR_ALLPATH . "includes/checkup/class_bill_catch_errors.php");   
    }
    add_action('init', 'bill_my_custom_hooking_function2');
 }

 add_filter('plugin_row_meta', 'recaptcha_for_all_custom_plugin_row_meta', 10, 2);

 //die(var_export(__LINE__));
 add_action('wp_loaded', 'recaptcha_for_all_load_feedback');



 ///////////////   recaptcha_auto_updates


if($recaptcha_for_all_update !== 'no'){
	if( !recaptcha_for_all_check_autoupdate()){
		recaptcha_for_all_check_autoupdate_activate();
	}
}

add_action('wp_ajax_recaptcha_for_all_test_keys', 'recaptcha_for_all_test_keys');
add_action('wp_ajax_recaptcha_for_all_test_keys_google', 'recaptcha_for_all_test_keys_google');

add_action('admin_init', 'recaptcha_for_all_store_last_plugin_version');

return;
// ===========  only functions ... =============================

// Store the last version in the options table
function recaptcha_for_all_store_last_plugin_version() {
    $last_version = get_option('recaptcha_for_all_last_plugin_version','');
    // Compare with the last version
    if (RECAPTCHA_FOR_ALLVERSION != $last_version) {
        // Plugin has been updated, perform your actions here
        if(empty(get_option('recaptcha_for_all_sitekey', ''))){
            // New Install...
            update_option('recaptcha_for_all_btn_background_color', '#BF4040');
            update_option('recaptcha_for_all_box_position', 'footer');
            update_option('recaptcha_for_all_background_color', '#000000');
            update_option('recaptcha_for_all_image_background', esc_url(RECAPTCHA_FOR_ALLURL.'images/background-plugin2.jpg'));
            update_option('recaptcha_for_all_box_width', '100%');
        }
        else {
            // Not New Install...
            if(!empty(get_option('recaptcha_for_all_custom_image_background',''))){
                update_option("recaptcha_for_all_image_option","custom");
            }
        }
    }
    // Update the last version in the options table
    update_option('recaptcha_for_all_last_plugin_version', RECAPTCHA_FOR_ALLVERSION);

}


function recaptcha_for_all_check_autoupdate(){
	$plugin_slug = 'recaptcha-for-all';
	$auto_update_settings = get_site_option('auto_update_plugins', array());
	if ($auto_update_settings && is_array($auto_update_settings) ) {
		foreach ($auto_update_settings as $plugin_path) {
			if (strpos($plugin_path, $plugin_slug) !== false) 
				return true;
		}
	} 
	return false;
}

function recaptcha_for_all_check_autoupdate_activate(){

	$auto_update_settings = get_site_option('auto_update_plugins', array());
	$target_plugin_slug_details = 'recaptcha-for-all/recaptcha.php';
	$auto_update_settings[] = $target_plugin_slug_details;
	update_site_option('auto_update_plugins', $auto_update_settings);
	return;
}

///////////////   recaptcha_auto_updates END


function recaptcha_for_all_test_keys_google() {
    $sitekey = sanitize_text_field($_POST['sitekey']);
    $secretkey = sanitize_text_field($_POST['secretkey']);
    $token = sanitize_text_field($_POST['mytoken']);
    $response = wp_remote_get('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretkey . '&response=' . $token);
    if (is_array($response) && isset($response['body'])) {
        // Decodifique o JSON no corpo da resposta
        $response_body = json_decode($response['body']);
        // Verifique se a decodificação foi bem-sucedida
        if ($response_body) {
            // Agora você pode acessar os dados do corpo da resposta
            $success = $response_body->success;
            /*
            $challenge_ts = $response_body->challenge_ts;
            $hostname = $response_body->hostname;
            $score = $response_body->score;
            $action = $response_body->action;
            */
            wp_die($success);
            // Faça o que precisar com esses dados
        } else {
            // A decodificação do JSON falhou
            wp_die('-1');
        }
    } else {
        // A solicitação falhou ou não há corpo na resposta
        wp_die('-2');
    }
    die('111111aaa');
}
// add_action('wp_ajax_recaptcha_for_all_test_keys_google', 'recaptcha_for_all_test_keys_google');


function recaptcha_for_all_test_keys() {
    $sitekey = sanitize_text_field($_POST['sitekey']);
    $secretkey = sanitize_text_field($_POST['secretkey']);
    $token = sanitize_text_field($_POST['mytoken']);
        $headers = array(
            'body' => [
                'secret' => $secretkey,
                'response' => $token
            ]
        );
          $verify = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', $headers);
          $verify = wp_remote_retrieve_body($verify);
         if(!$verify){
            wp_die('NOT');
         }
         $response = json_decode($verify);
         if($response->success) {
            wp_die('OK');
         }
         else
         wp_die('NOT');
        wp_die('Fail to Validate!');
}
// add_action('wp_ajax_recaptcha_for_all_test_keys', 'recaptcha_for_all_test_keys');



   // run the ajax...
if (!function_exists('bill_get_js_errors')) {
    function bill_get_js_errors()
        {
            if (isset($_REQUEST)) {
                if (!isset($_REQUEST['bill_js_error_catched']))
                    die("empty error");
                if (!wp_verify_nonce($_POST['_wpnonce'], 'jquery-bill')) {
                    status_header(406, 'Invalid nonce');
                    die();
                }
                $bill_js_error_catched = sanitize_text_field($_REQUEST['bill_js_error_catched']);
                $bill_js_error_catched = trim($bill_js_error_catched);
                if (!empty($bill_js_error_catched)) {
                    $txt = 'Javascript ' . $bill_js_error_catched;
                    error_log($txt);
                    // send email
                    // bill_php_error($txt);
                    //set_transient( 'sbb_javascript_error', '1', (3600*24) );
                    //add_option( 'sbb_javascript_error', time() );
                    die('OK!!!');
                }
            }
            die('NOT OK!');
        }
}



function recaptcha_is_active()
{


    global $wp_query;
    global $recaptcha_for_all_pages;
    global $arecaptcha_for_all_slugs;
    global $recaptcha_for_all_sitekey;
    global $recaptcha_for_all_secretkey;
    
    $recaptcha_active = false;

    if(isset($wp_query->post->ID)){
        $recaptcha_postID = $wp_query->post->ID;
        if ($recaptcha_for_all_pages == 'yes_all') {
           $recaptcha_active = true;
        } elseif ($recaptcha_for_all_pages == 'yes_pages' and recaptcha_is_page($recaptcha_postID) ) {
            $recaptcha_active = true;
        } elseif ($recaptcha_for_all_pages == 'yes_posts' and recaptcha_is_post($recaptcha_postID) ) {
            $recaptcha_active = true;
        } elseif ($recaptcha_for_all_pages == 'no') {
            $slug = get_post_field( 'post_name', $recaptcha_postID );
            for($i = 0; $i < count($arecaptcha_for_all_slugs); $i++) {
                if($arecaptcha_for_all_slugs[$i] == $slug ) {
                    $recaptcha_active = true;
                    break;
                }
            }
        }
    }
    else
    {
        if ($recaptcha_for_all_pages == 'yes_all') 
            $recaptcha_active = true;
    }


    // deviation
    if ($recaptcha_active) {
        if (!empty($recaptcha_for_all_sitekey) and !empty($recaptcha_for_all_secretkey)){
           
            // die(var_export($recaptcha_active));
               
            add_filter('template_include', 'recaptcha_for_all_page_template');
        }
    }
    return;
}

function recaptcha_block()
{
    global $wp_query;
    global $recaptcha_for_all_pages;
    global $arecaptcha_for_all_slugs;


    $recaptcha_postID = '';
    if(isset($wp_query->post))
       if(gettype($wp_query->post) == 'object')
          $recaptcha_postID = $wp_query->post->ID;

    $recaptcha_active = false;
    if ($recaptcha_for_all_pages == 'yes_all') {
        $recaptcha_active = true;
    } elseif ($recaptcha_for_all_pages == 'yes_pages' and recaptcha_is_page($recaptcha_postID) ) {
        $recaptcha_active = true;
    } elseif ($recaptcha_for_all_pages == 'yes_posts' and recaptcha_is_post($recaptcha_postID) ) {
        $recaptcha_active = true;
    } elseif ($recaptcha_for_all_pages == 'no') {
        $slug = get_post_field( 'post_name', $recaptcha_postID );
        for($i = 0; $i < count($arecaptcha_for_all_slugs); $i++) {
            if($arecaptcha_for_all_slugs[$i] == $slug ) {
                $recaptcha_active = true;
                break;
            }
        }
    }

    // block
    if ($recaptcha_active) {
            header('HTTP/1.1 403 Forbidden');
            header('Status: 403 Forbidden');
            header('Connection: Close');
            http_response_code(403);
            wp_die("Forbidden");
    }
    
    return;
}


function recaptcha_for_all_add_admstylesheet()
{
    wp_register_style('recaptcha-admin ', plugin_dir_url(__FILE__) . '/css/recaptcha.css');
    wp_enqueue_style('recaptcha-admin ');
    wp_enqueue_style('recaptcha-pointer', plugin_dir_url(__FILE__) . '/css/bill-wp-pointer.css');


}
function recaptcha_for_all_memory_init()
{
    add_management_page(
        'reCAPTCHA for all',
        'reCAPTCHA for all',
        'manage_options',
        'recaptcha_for_all_admin_page', // slug
        'recaptcha_for_all_admin_page'
    );
}
function recaptcha_for_all_admin_page()
{
    require_once RECAPTCHA_FOR_ALLPATH . "/dashboard/dashboard-container.php";
}
function recaptcha_for_all_enqueueScripts()
{
    global $recaptcha_for_all_sitekey;
    global $recaptcha_for_all_settings_provider;

    //die(var_export($recaptcha_for_all_settings_provider));

    if( $recaptcha_for_all_settings_provider == 'google'){


      $api_url = sprintf('https://www.google.com/recaptcha/api.js?render=%s', $recaptcha_for_all_sitekey);
      wp_register_script('recaptcha_for_all', $api_url, array(), '1.0', true);
      wp_enqueue_script('recaptcha_for_all');


    }
    else {
         wp_enqueue_script("recaptcha_for_all", "https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback", array(), null, 'true');
    }



}
function recaptcha_for_all_add_scripts()
{

    
    //error_log(-'enqueue...');
    wp_enqueue_script("jquery");
    wp_enqueue_media();
    // wp_enqueue_script('wp-pointer');
    wp_register_script("recaptcha_for_all-scripts", RECAPTCHA_FOR_ALLURL . 'js/recaptcha_for_all.js', array('jquery'), RECAPTCHA_FOR_ALLVERSION, true);
    wp_enqueue_script('recaptcha_for_all-scripts');

}


function recaptcha_for_all_page_template()
{
    // die(var_export(RECAPTCHA_FOR_ALLPATH));
    recaptcha_for_all_add_stats_challenge(); 
    
    return RECAPTCHA_FOR_ALLPATH . 'template.php';
}
function recaptcha_for_all_register_cookie()
{
    $script_url = RECAPTCHA_FOR_ALLURL . 'js/recaptcha_for_all_cookie.js';
    wp_register_script('recaptcha_for_all-cookie', $script_url, array(), 1.0, true); //true = footer
    wp_enqueue_script('recaptcha_for_all-cookie');
}
function recaptcha_for_all_maybe_search_engine()
{
    global $recaptcha_for_all_visitor_ip;
    global $recaptcha_for_all_visitor_ua;
    $ua = $recaptcha_for_all_visitor_ua;
    // crawl-66-249-73-151.googlebot.com
    // msnbot-157-55-39-204.search.msn.com
    if ($ua !== null) 
      $ua = trim(strtolower($ua));
      
    $mysearch = array(
        'googlebot',
        'bingbot',
        'slurp',
        'Twitterbot',
        'facebookexternalhit',
        'WhatsApp'
    );
    for ($i = 0; $i < count($mysearch); $i++) {

        if (is_string($ua) && stripos($ua, $mysearch[$i]) !== false) {

            if (strpos($mysearch[$i], 'facebookexternalhit') !== false) {
                return true;
            }
            if (strpos($mysearch[$i], 'Twitterbot') !== false) {
                return true;
            }
            if (strpos($mysearch[$i], 'WhatsApp') !== false) {
                return true;
            }

            // gethostbyaddr(): Address is not a valid IPv4 or IPv6 address i

            if (filter_var($recaptcha_for_all_visitor_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE | FILTER_FLAG_NO_PRIV_RANGE))
                $host = strip_tags(gethostbyaddr($recaptcha_for_all_visitor_ip));
            else
                return false;



            $mysearch1 = array(
                'googlebot',
                'msn.com',
                'slurp'
            );
            $host = trim(strip_tags(gethostbyaddr($recaptcha_for_all_visitor_ip)));
            if ($host == trim($recaptcha_for_all_visitor_ip))
                return false;
            if (is_string($host) && stripos($host, $mysearch1[$i]) !== false) {
                return true;
            }
            
        }
    }
    return false;
}

function recaptcha_for_all_get_ua()
{
    if (!isset($_SERVER['HTTP_USER_AGENT'])) {
        return ""; // mozilla compatible";
    }
    $ua = trim(sanitize_text_field($_SERVER['HTTP_USER_AGENT']));
    //  $ua = recaptcha_for_all_clear_extra($ua);
    return $ua;
}
function recaptcha_for_all_findip()
{
    $ip = '';
    $headers = array(
        'HTTP_CF_CONNECTING_IP', // CloudFlare
        'HTTP_CLIENT_IP', // Bill
        'HTTP_X_REAL_IP', // Bill
        'HTTP_X_FORWARDED', // Bill
        'HTTP_FORWARDED_FOR', // Bill
        'HTTP_FORWARDED', // Bill
        'HTTP_X_CLUSTER_CLIENT_IP', //Bill
        'HTTP_X_FORWARDED_FOR', // Squid and most other forward and reverse proxies
        'REMOTE_ADDR', // Default source of remote IP
    );
    for ($x = 0; $x < 8; $x++) {
        foreach ($headers as $header) {
            /*
            if(!array_key_exists($header, $_SERVER))
            continue;
             */
            if (!isset($_SERVER[$header])) {
                continue;
            }
            $myheader = trim(sanitize_text_field($_SERVER[$header]));
            if (empty($myheader)) {
                continue;
            }
            $ip = trim(sanitize_text_field($_SERVER[$header]));
            if (empty($ip)) {
                continue;
            }
            if (false !== ($comma_index = strpos(sanitize_text_field($_SERVER[$header]), ','))) {
                $ip = substr($ip, 0, $comma_index);
            }
            // First run through. Only accept an IP not in the reserved or private range.
            if ($ip == '127.0.0.1') {
                $ip = '';
                continue;
            }
            if (0 === $x) {
                $ip = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE |
                    FILTER_FLAG_NO_PRIV_RANGE);
            } else {
                $ip = filter_var($ip, FILTER_VALIDATE_IP);
            }
            if (!empty($ip)) {
                break;
            }
        }
        if (!empty($ip)) {
            break;
        }
    }
    if (!empty($ip)) {
        return $ip;
    } else {
        return 'unknow';
    }
}
function recaptcha_for_all_isourserver()
{
    global $recaptcha_for_all_visitor_ip;
    // $server_ip = $_SERVER['REMOTE_ADDR'];
    $server_ip = $_SERVER['SERVER_ADDR'];
    if ($server_ip == $recaptcha_for_all_visitor_ip)
        return true;
    return false;
}
function recaptcha_for_all_was_activated()
{
    recaptcha_for_all_create_db_stats();
    /*
    $recaptcha_for_all_string_whitelist = implode( PHP_EOL, array_map( 'sanitize_textarea_field', explode(PHP_EOL, get_site_option('recaptcha_for_all_string_whitelist', '')) ) );
    $arecaptcha_for_all_string_whitelist = explode(PHP_EOL, $recaptcha_for_all_string_whitelist);
    if(count($arecaptcha_for_all_string_whitelist) < 1)
       recaptcha_for_all_create_string_whitelist();
       */
}

function recaptcha_for_all_check_string_whitelist()
{


    $recaptcha_for_all_string_whitelist = implode(PHP_EOL, array_map('sanitize_textarea_field', explode(PHP_EOL, get_site_option('recaptcha_for_all_string_whitelist', ''))));
    $arecaptcha_for_all_string_whitelist = explode(PHP_EOL, $recaptcha_for_all_string_whitelist);


    if (count($arecaptcha_for_all_string_whitelist) == 1) {


        if (empty(trim($arecaptcha_for_all_string_whitelist[0]))) {


            recaptcha_for_all_create_string_whitelist();
            return;
        }
    }



    if (count($arecaptcha_for_all_string_whitelist) < 1)
        recaptcha_for_all_create_string_whitelist();
}


// create string
function recaptcha_for_all_create_string_whitelist()
{
    global $arecaptcha_for_all_string_whitelist;
    $mywhitelist = array(
        'DuckDuck',
        'Paypal',
        'Seznam',
        'Stripe',
        'SiteUptime',
        'Yandex'
    );
    $text = '';
    for ($i = 0; $i < count($mywhitelist); $i++) {
        if (!recaptcha_for_all_is_string_whitelisted($mywhitelist[$i], $arecaptcha_for_all_string_whitelist))
            $text .= $mywhitelist[$i] . PHP_EOL;
    }
    if (!add_option('recaptcha_for_all_string_whitelist', $text)) {
        update_option('recaptcha_for_all_string_whitelist', $text);
    }
}
// test string
function recaptcha_for_all_is_string_whitelisted($recaptcha_for_all_ua, $arecaptcha_for_all_string_whitelist)
{
    if (gettype($arecaptcha_for_all_string_whitelist) != 'array')
        return;
    for ($i = 0; $i < count($arecaptcha_for_all_string_whitelist); $i++) {
        if (empty(trim($arecaptcha_for_all_string_whitelist[$i])))
            continue;
        if (strpos($recaptcha_for_all_ua, $arecaptcha_for_all_string_whitelist[$i]) !== false)
            return 1;
    }
    return 0;
}
// test IP
function recaptcha_for_all_is_ip_whitelisted($recaptcha_for_all_visitor_ip, $arecaptcha_for_all_ip_whitelist)
{
    if (gettype($arecaptcha_for_all_ip_whitelist) != 'array')
        return;
    for ($i = 0; $i < count($arecaptcha_for_all_ip_whitelist); $i++) {
        if (trim($arecaptcha_for_all_ip_whitelist[$i]) == trim($recaptcha_for_all_visitor_ip))
            return true;
    }
    return false;
}
function recaptcha_for_all_alert_keys()
{
    echo '<div class="notice notice-warning is-dismissible">';
    echo '<br /><b>';
    esc_attr_e('Site Key and Secret Key are empty! Go to Manage Keys (tab)', 'recaptcha_for_all');
    echo '<br /><br /></div>';
}
function recaptcha_for_all_settings_link($links)
{
    $settings_link = '<a href="tools.php?page=recaptcha_for_all_admin_page">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}
/*
function recaptcha_for_all_plugin_act_message()
{
    echo '<div class="updated"><p>';
    $sbb_msg = '<h2>';
    $sbb_msg .= esc_attr__('reCAPTCHA For All was activated!', 'recaptcha-for-all');
    $sbb_msg .= '</h2>';
    $sbb_msg .= '<h3>';
    $sbb_msg .= esc_attr__(
        'For details and help, take a look at reCAPTCHA For All at your left menu => Tools',
        'recaptcha-for-all'
    );
    $sbb_msg .= '<br />';
    $sbb_msg .= '  <a class="button button-primary" href="tools.php?page=recaptcha_for_all_admin_page">';
    $sbb_msg .= esc_attr__('or click here', 'recaptcha-for-all');
    $sbb_msg .= '</a>';
    echo $sbb_msg;
    echo "</p></h3></div>";
}
*/
function recaptcha_for_all_plugin_activate()
{

    // do_action( 'recaptcha_for_all_plugin_act_message' );
    //  add_action('admin_init', 'recaptcha_for_all_plugin_act_message');
    add_option('recaptcha_for_all_was_activated', '1');
    update_option('recaptcha_for_all_was_activated', '1');
}

function recaptcha_is_post($id)
{
    $posts = get_posts();
    foreach ($posts as $posts) {
        if($posts->ID == $id)
          return true;
    }
    return false;
}
function recaptcha_is_page($id)
{
    $pages = get_pages();
    foreach ($pages as $page) {
        if($page->ID == $id)
          return true;
    }
    return false;
}
function recaptcha_localization_init()
{
    $path = basename( dirname( __FILE__ ) ) . '/language';
    // var_dump($path);
    $loaded = load_plugin_textdomain('recaptcha-for-all', false, $path);

    
    if (!$loaded and get_locale() <> 'en_US') {
        if (function_exists('recaptcha_localization_init_fail'))
            add_action('admin_notices', 'recaptcha_localization_init_fail');
    }
    

}  

function recaptcha_localization_init_fail()
{
 

        if(isset($_COOKIE["recaptcha_dismiss"])) {

            $r = update_option('recaptcha_dismiss_language', '1');
            if (!$r) {
                $r = add_option('recaptcha_dismiss_language', '1');
            }

        }

          if(get_option('recaptcha_dismiss_language') == '1')
          return;
          

    //wp_enqueue_script("jquery");
    wp_register_script("recaptcha_for_all-dismiss", RECAPTCHA_FOR_ALLURL . 'js/recaptcha_for_all_dismiss.js', array('jquery'), RECAPTCHA_FOR_ALLVERSION, true);
    wp_enqueue_script('recaptcha_for_all-dismiss');

    echo '<div id="recaptcha_an2" class="notice notice-warning is-dismissible">';
    echo '<br />';
    echo 'Recaptcha for all: Could not load the localization file (Language file)';
    echo '<br />';
    echo 'Please, take a look in our site, FAQ page, item => How can i translate this plugin?';
    echo '<br /><br /></div>';
    
    return;

}  
// recaptcha dismissible_notice2
function recaptcha_dismiss_notice2() {
    
    
	$r = update_option('recaptcha_dismiss_language', '1');
	if (!$r) {
		$r = add_option('recaptcha_dismiss_language', '1');
	}
    /*
	if($r)
	  die('OK!!!!!');
	else
	  die('NNNN');
    */
      
}

function recaptcha_ajaxurl()
{
    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}


// wp_enqueue_script('myHandle','pathToJS');
/*
wp_localize_script(
   'myHandle',
   'ajax_obj',
    array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
);
*/

/////////// Pointers ////////////////


function recaptcha_for_all_activated()
{
	$r = update_option('recaptcha_for_all_was_activated', '1');
	if (!$r) {
		add_option('recaptcha_for_all_was_activated', '1');
	}
	$pointers = get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true);
	$pointers = ''; // str_replace( 'plugins', '', $pointers );
	update_user_meta(get_current_user_id(), 'dismissed_wp_pointers', $pointers);
}
function recaptcha_for_all_dismissible_notice()
{
	$r = update_option('recaptcha_for_all_dismiss', false);
	if (!$r) {
		$r = add_option('recaptcha_for_all_dismiss', false);
	}
	wp_die($r);
}


/*
function recaptcha_for_all_dismiss_admin_notice()
{
	//if(!bill_check_resources(false))
	//   return;
	?>
		<div id="recaptcha_for_all_an1" class="notice-warning notice is-dismissible">
			<p>
			Please, look the Recaptcha For All Plugin Dashboard &nbsp;
			<a class="button button-primary" href="admin.php?page=recaptcha_for_all_admin_page">or click here</a>
		   </p>
		</div>
	<?php
	//endif;
}
*/

//die(var_export(__LINE__));
/*
if (is_admin() or is_super_admin()   ) {

    $r = get_option('recaptcha_for_all_was_activated', '0') ;

    //die(var_export($r));


    if (get_option('recaptcha_for_all_was_activated', '0') == '1') {
         add_action('admin_enqueue_scripts', 'recaptcha_for_all_adm_enqueue_scripts2');
     }
 }
 */



 
 function recaptcha_for_all_adm_enqueue_scripts2()
 {
    //wp_enqueue_script("jquery");
    //wp_enqueue_media();
    //wp_register_script("recaptcha_for_all-scripts", RECAPTCHA_FOR_ALLURL . 'js/recaptcha_for_all.js', array('jquery'), RECAPTCHA_FOR_ALLVERSION, true);
    //wp_enqueue_script('recaptcha_for_all-scripts');

     global $bill_current_screen;
       wp_enqueue_script('wp-pointer');
     //wp_enqueue_script('wp-pointer');


     require_once ABSPATH . 'wp-admin/includes/screen.php';
     $myscreen = get_current_screen();
     $bill_current_screen = $myscreen->id;
     $dismissed_string = get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true);
     // $dismissed = explode(',', (string) get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true));
    // if (in_array('plugins', $dismissed)) {  
     if ( !empty($dismissed_string))  {
         $r = update_option('recaptcha_for_all_was_activated', '0');
         if (!$r) {
             add_option('recaptcha_for_all_was_activated', '0');
         }
         return;
     }
     add_action('admin_print_footer_scripts', 'recaptcha_for_all_admin_print_footer_scripts');

     require_once ABSPATH . 'wp-admin/includes/screen.php';
     $myscreen = get_current_screen();
     $bill_current_screen = $myscreen->id;
     $dismissed_string = get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true);
     // $dismissed = explode(',', (string) get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true));
    // if (in_array('plugins', $dismissed)) {  
     if ( !empty($dismissed_string))  {
         $r = update_option('recaptcha_for_all_was_activated', '0');
         if (!$r) {
             add_option('recaptcha_for_all_was_activated', '0');
         }
         return;
     }
     // die(var_export(__LINE__));
     add_action('admin_print_footer_scripts', 'recaptcha_for_all_admin_print_footer_scripts');
 }

 
 function recaptcha_for_all_admin_print_footer_scripts()
 {
     global $bill_current_screen;
 
     $pointer_content = esc_attr__("Open Recaptcha For All Plugin Here!", "recaptcha-for-all");
     $pointer_content2 = esc_attr__("Just Click Over Tools => Recaptcha For All.","recaptcha-for-all");
 
  ?>
         <script type="text/javascript">
         //<![CDATA[
             // setTimeout( function() { this_pointer.pointer( 'close' ); }, 400 );
        
             jQuery(document).ready( function($) {


            console.log('entrou');


                jQuery('.dashicons-admin-tools').pointer({
              

                 content: '<?php echo '<h3>'.esc_attr($pointer_content).'</h3>'. '<div id="bill-pointer-body">'.esc_attr($pointer_content2).'</div>';?>',
 
                 position: {
                         edge: 'left',
                         align: 'right'
                     },
                 close: function() {
                     // Once the close button is hit
                     jQuery.post( ajaxurl, {
                         pointer: '<?php echo esc_attr($bill_current_screen); ?>',                        action: 'dismiss-wp-pointer'
                         });
                 }
             }).pointer('open');
             jQuery('.wp-pointer').css("margin-left", "100px");
             jQuery('#wp-pointer-0').css("padding", "10px");
       
         });
         //]]>
         </script>
         <?php
 }



function recaptcha_for_all_image_select(){
    if (isset($_POST['recaptcha_image_url'])) {
          error_log($_POST['recaptcha_image_url']);
        if ( ! isset( $_POST['recaptcha_my_plugin_nonce'] )){
          die('-1');
        }
       if( ! wp_verify_nonce( $_POST['recaptcha_my_plugin_nonce'], 'recaptcha_my_plugin_action_upd_image' ))  {
         die('-2');
       }
       if ( current_user_can( 'manage_options' ) ) {
            //error_log('User is an admin');
        } else {
            error_log('User is not admin');
            die('-6');
        }
        $recaptcha_image_url =  $_POST['recaptcha_image_url'];
        error_log($recaptcha_image_url);
        $r = update_option('recaptcha_for_all_custom_image_background', $recaptcha_image_url);
        if (!$r) {
            $r = add_option('recaptcha_for_all_custom_image_background', $recaptcha_image_url);
        }
        die(var_export($r,true));
    }
    else
      die('0');
 }

 
function recaptcha_for_all_adm_enqueue_scripts1()
{
    wp_enqueue_script("jquery");
    wp_enqueue_media();

    wp_enqueue_script('recaptcha_for_all_circle', RECAPTCHA_FOR_ALLURL .
    'js/radialIndicator.js', array('jquery'));

    wp_enqueue_script('recaptcha_for_all_chart', RECAPTCHA_FOR_ALLURL .
    'js/chart.min.js', array('jquery'));
  

    wp_register_script("recaptcha_for_all-scripts", RECAPTCHA_FOR_ALLURL . 'js/recaptcha_for_all.js', array('jquery'), RECAPTCHA_FOR_ALLVERSION, true);
    wp_enqueue_script('recaptcha_for_all-scripts');

    // Localize your script and pass the variable
    $recaptcha_my_plugin_nonce = wp_create_nonce( 'recaptcha_my_plugin_action_upd_image' );
     wp_localize_script( 'recaptcha_for_all-scripts', 'recaptcha_my_data', array(
         'recaptcha_my_nonce' => $recaptcha_my_plugin_nonce,
         'ajax_url' => admin_url( 'admin-ajax.php' )
     ) );
}

function recaptcha_for_all_adm_enqueue_scripts3()
{

    // test connect...
    global $recaptcha_for_all_settings_provider;

    if(empty($recaptcha_for_all_settings_provider))
       return;


    wp_register_script("recaptcha_for_all-scripts-test", RECAPTCHA_FOR_ALLURL . 'js/recaptcha_for_all_test.js', array('jquery'), RECAPTCHA_FOR_ALLVERSION, true);
    wp_enqueue_script('recaptcha_for_all-scripts-test');
    
    $recaptchaDebug = '1';

    if($recaptcha_for_all_settings_provider != 'google'){
        wp_localize_script( 'recaptcha_for_all-scripts-test', 'recaptcha_my_data3', array(
            'recaptchaDebug' => $recaptchaDebug,
            'provider' => 'turnstile'
        ) );
    }
    else {
        wp_localize_script( 'recaptcha_for_all-scripts-test', 'recaptcha_my_data3', array(
           'recaptchaDebug' => $recaptchaDebug,
           'provider' => 'recaptcha'
         ) );
    }

}




/* =============================== */
function recaptcha_for_all_new_more_plugins()
{
	//recaptcha_for_all_show_logo();
	$plugins_to_install = array();
	$plugins_to_install[0]["Name"] = "Anti Hacker Plugin";
	$plugins_to_install[0]["Description"] = "Firewall, Malware Scanner, Login Protect, block user enumeration and TOR, disable Json WordPress Rest API, xml-rpc (xmlrpc) & Pingback and more security tools...";
	$plugins_to_install[0]["image"] = "https://ps.w.org/antihacker/assets/icon-256x256.gif?rev=2524575";
	$plugins_to_install[0]["slug"] = "antihacker";
	$plugins_to_install[1]["Name"] = "Stop Bad Bots";
	$plugins_to_install[1]["Description"] = "Stop Bad Bots, Block SPAM bots, Crawlers and spiders also from botnets. Save bandwidth, avoid server overload and content steal (that ruins your SEO). Blocks also by IP and Referer.";
	$plugins_to_install[1]["image"] = "https://ps.w.org/stopbadbots/assets/icon-256x256.gif?rev=2524815";
	$plugins_to_install[1]["slug"] = "stopbadbots";
	$plugins_to_install[2]["Name"] = "WP Tools";
	$plugins_to_install[2]["Description"] = "More than 35 useful tools! It is a swiss army knife, to take your site to the next level. Also, show hidden errors, file permissions, site health alert, database check, server info and perform a server benchmark.";
	$plugins_to_install[2]["image"] = "https://ps.w.org/wptools/assets/icon-256x256.gif?rev=2526088";
	$plugins_to_install[2]["slug"] = "wptools";
	$plugins_to_install[3]["Name"] = "reCAPTCHA For All and Cloudflare Turnstile";
	$plugins_to_install[3]["Description"] = "Protect ALL Pages (or just some) of your site against bots (spam, hackers, fake users and other types of automated abuse)
	with invisible reCaptcha V3 (Google) or Cloudflare turnstile. You can also block visitors from China.";
	$plugins_to_install[3]["image"] = "https://ps.w.org/recaptcha-for-all/assets/icon-256x256.gif?rev=2544899";
	$plugins_to_install[3]["slug"] = "recaptcha-for-all";
	$plugins_to_install[4]["Name"] = "WP Memory";
	$plugins_to_install[4]["Description"] = "Check High Memory Usage, Memory Limit, PHP Memory, show result in Site Health Page and fix WordPress and php low memory limit with 3 steps wizard.";
	$plugins_to_install[4]["image"] = "https://ps.w.org/wp-memory/assets/icon-256x256.gif?rev=2525936";
	$plugins_to_install[4]["slug"] = "wp-memory";

	/*
	$plugins_to_install[5]["Name"] = "Truth Social";
	$plugins_to_install[5]["Description"] = "Tools and feeds for Truth Social new social media platform and Twitter.";
	$plugins_to_install[5]["image"] = "https://ps.w.org/toolstruthsocial/assets/icon-256x256.png?rev=2629666";
	$plugins_to_install[5]["slug"] = "toolstruthsocial";
	*/
	$plugins_to_install[5]["Name"] = "Database Backup";
	$plugins_to_install[5]["Description"] = "Database Backup with just one click. Scheduling an automatic daily or weekly backup and choosing backup file retention time. This plugin prioritizes security, and backups are created with skip-extended-insert.";
	$plugins_to_install[5]["image"] = "https://ps.w.org/database-backup/assets/icon-256x256.gif?rev=2862571";
	$plugins_to_install[5]["slug"] = "database-backup";

	$plugins_to_install[6]["Name"] = "Database Restore Bigdump";
	$plugins_to_install[6]["Description"] = "Large and very large Database Restore with BigDump script. Just use your mouse.";
	$plugins_to_install[6]["image"] = "https://ps.w.org/bigdump-restore/assets/icon-256x256.gif?rev=2872393";
	$plugins_to_install[6]["slug"] = "bigdump-restore";


	$plugins_to_install[7]["Name"] = "Easy Update URLs";
	$plugins_to_install[7]["Description"] = "Fix your URLs at database after cloning or moving sites.";
	$plugins_to_install[7]["image"] = "https://ps.w.org/easy-update-urls/assets/icon-256x256.gif?rev=2866408";
	$plugins_to_install[7]["slug"] = "easy-update-urls";

	$plugins_to_install[8]["Name"] = "S3 Cloud Contabo";
	$plugins_to_install[8]["Description"] = "Connect you with your Contabo S3-compatible Object Storage.Transfer and manage your files in the cloud with a user-friendly interface.";
	$plugins_to_install[8]["image"] = "https://ps.w.org/s3cloud/assets/icon-256x256.gif?rev=2855916";
	$plugins_to_install[8]["slug"] = "s3cloud";

	$plugins_to_install[9]["Name"] = "Tools for S3 AWS Amazon";
	$plugins_to_install[9]["Description"] = "Connect you with your Amazon S3-compatible Object Storage. Transfer and manage your files in the cloud with a user-friendly interface.";
	$plugins_to_install[9]["image"] = "https://ps.w.org/toolsfors3/assets/icon-256x256.gif?rev=2862487";
	$plugins_to_install[9]["slug"] =  "toolsfors3";


?>
	<div style="padding-right:20px;">
		<!-- <h1>Useful FREE Plugins of the same author</h1> -->
		<div id="bill-wrap-install" class="bill-wrap-install" style="display:none">
			<h3>Please wait</h3>
			<big>
				<h4>
					Installing plugin <div id="billpluginslug">...</div>
				</h4>
			</big>
			<img src="/wp-admin/images/wpspin_light-2x.gif" id="billimagewaitfbl" style="display:none;margin-left:0px;margin-top:0px;" />
			<br />
		</div>


		<table style="margin-right:20px; border-spacing: 0 25px; " class="widefat" cellspacing="0" id="recaptcha_for_all-more-plugins-table">
			<tbody class="recaptcha_for_all-more-plugins-body">
				<?php
				$counter = 0;
				$total = count($plugins_to_install);
				for ($i = 0; $i < $total; $i++) {
					if ($counter % 2 == 0) {
						echo '<tr style="background:#f6f6f1;">';
					}
					++$counter;
					if ($counter % 2 == 1)
						echo '<td style="max-width:140px; max-height:140px; padding-left: 40px;" >';
					else
						echo '<td style="max-width:140px; max-height:140px;" >';
					echo '<img style="width:100px;" src="' . esc_url($plugins_to_install[$i]["image"]) . '">';
					echo '</td>';
					echo '<td style="width:40%;">';
					echo '<h3>' . esc_attr($plugins_to_install[$i]["Name"]) . '</h3>';
					echo esc_attr($plugins_to_install[$i]["Description"]);
					echo '<br>';
					echo '</td>';
					echo '<td style="max-width:140px; max-height:140px;" >';
					if (recaptcha_for_all_plugin_installed($plugins_to_install[$i]["slug"]))
						echo '<a href="#" class="button activate-now">Installed</a>';
					else
						echo '<a href="#" id="' . esc_attr($plugins_to_install[$i]["slug"]) . '"class="button button-primary rfa-bill-install-now">Install</a>';
					echo '</td>';
					if ($counter % 2 == 1) {
						echo '<td style="width; 100px; border-left: 1px solid gray;">';
						echo '</td>';
					}
					if ($counter % 2 == 0) {
						echo '</tr>';
					}
				}
				?>
			</tbody>
		</table>

		    <!-- Bill-11 -->
			<?php echo '<div id="recaptcha_for_all_nonce" style="display:none;" >'. wp_create_nonce('recaptcha_for_all_install_plugin'); ?>

	</div>


	<center>
	<a href="https://profiles.wordpress.org/sminozzi/#content-plugins" class="button button-primary">
	<?php esc_attr_e( 'More Plugins', 'wptools' ); ?>
	</a>
	</center>
	<?php 
}
function recaptcha_for_all_plugin_installed($slug)
{
	$all_plugins = get_plugins();
	foreach ($all_plugins as $key => $value) {
		$plugin_file = $key;
		$slash_position = strpos($plugin_file, '/');
		$folder = substr($plugin_file, 0, $slash_position);
		// match FOLDER against SLUG
		if ($slug == $folder) {
			return true;
		}
	}
	return false;
}
function recaptcha_for_all_load_upsell()
{
	wp_enqueue_style('recaptcha_for_all-more2', RECAPTCHA_FOR_ALLURL . 'includes/more/more2.css');
	wp_register_script('recaptcha_for_all-more2-js', RECAPTCHA_FOR_ALLURL . 'includes/more/more2.js', array('jquery'));
	wp_enqueue_script('recaptcha_for_all-more2-js');
	$recaptcha_for_all_bill_go_pro_hide = trim(get_option('bill_go_pro_hide'));
}
function recaptcha_for_all_install_plugin()
{

	if (isset($_POST['nonce'])) {
		$nonce = sanitize_text_field($_POST['nonce']);
		if ( ! wp_verify_nonce( $nonce, 'recaptcha_for_all_install_plugin' ) ) 
			 die('Bad Nonce');
	  }
	  else
		wp_die('nonce not set');
		

	if (isset($_POST['slug'])) {
		$slug = sanitize_text_field($_POST['slug']);
	} else {
		echo 'Fail error (-5)';
		wp_die();
	}

	if ($slug != "database-backup" &&  $slug != "bigdump-restore" &&  $slug != "easy-update-urls" &&  $slug != "s3cloud" &&  $slug != "toolsfors3" && $slug != "antihacker" && $slug != "toolstruthsocial" && $slug != "stopbadbots" && $slug != "wptools" && $slug != "recaptcha-for-all" && $slug != "wp-memory") {
		wp_die('wrong slug');
    }

	$plugin['source'] = 'repo'; // $_GET['plugin_source']; // Plugin source.
	require_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // Need for plugins_api.
	require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php'; // Need for upgrade classes.
	// get plugin information
	$api = plugins_api('plugin_information', array('slug' => $slug, 'fields' => array('sections' => false)));
	if (is_wp_error($api)) {
		echo 'Fail error (-1)';
		wp_die();
		// proceed
	} else {
		// Set plugin source to WordPress API link if available.
		if (isset($api->download_link)) {
			$plugin['source'] = $api->download_link;
			$source =  $api->download_link;
		} else {
			echo 'Fail error (-2)';
			wp_die();
		}
		$nonce = 'install-plugin_' . $api->slug;
		/*
        $type = 'web';
        $url = $source;
        $title = 'wptools';
        */
		$plugin = $slug;
		// verbose...
		//    $upgrader = new Plugin_Upgrader($skin = new Plugin_Installer_Skin(compact('type', 'title', 'url', 'nonce', 'plugin', 'api')));
		class recaptcha_for_all_QuietSkin extends \WP_Upgrader_Skin
		{
			public function feedback($string, ...$args)
			{ /* no output */
			}
			public function header()
			{ /* no output */
			}
			public function footer()
			{ /* no output */
			}
		}
		$skin = new recaptcha_for_all_QuietSkin(array('api' => $api));
		$upgrader = new Plugin_Upgrader($skin);
		// var_dump($upgrader);
		try {
			$upgrader->install($source);
			//	get all plugins
			$all_plugins = get_plugins();
			// scan existing plugins
			foreach ($all_plugins as $key => $value) {
				// get full path to plugin MAIN file
				// folder and filename
				$plugin_file = $key;
				$slash_position = strpos($plugin_file, '/');
				$folder = substr($plugin_file, 0, $slash_position);
				// match FOLDER against SLUG
				// if matched then ACTIVATE it
				if ($slug == $folder) {
					// Activate
					$result = activate_plugin(ABSPATH . 'wp-content/plugins/' . $plugin_file);
					if (is_wp_error($result)) {
						// Process Error
						echo 'Fail error (-3)';
						wp_die();
					}
					else {
						//works
						$url = 'https://billminozzi.com/httpapi/httpapi.php';
						$data = array(
							'slug' => $slug,
							'status' => '28'
						);
						$args = array(
							'body' => $data
						);
						try {
					    	$response = wp_remote_post( $url, $args );
						} catch (Exception $e) {
							//error_log('Erro '.$e->getMessage());
						}
					}

				} // if matched
			}
		} catch (Exception $e) {
			echo 'Fail error (-4)';
			wp_die();
		}
	} // activation
	echo 'OK';
	wp_die();
}

function recaptcha_for_all_custom_plugin_row_meta($links, $file)
{
    if (strpos($file, 'recaptcha.php') !== false) {
        $new_links = array();

        $custom_link = admin_url('tools.php?page=recaptcha_for_all_admin_page&tab=tools');
        $new_links['More'] = '<a href="'.$custom_link.'"><b><font color="#FF6600">Additional Free Tools by the Same Author</font></b></a>';

        $links = array_merge($links, $new_links);
    }
    return $links;
}
function recaptcha_for_all_load_feedback()
{
  if (is_admin()) {
    // ob_start();
    // require_once(RECAPTCHA_FOR_ALLPATH . "includes/feedback/feedback.php");
    require_once(RECAPTCHA_FOR_ALLPATH . "includes/feedback/feedback-last.php");
  }  // ob_end_clean();
}
// add_action('wp_loaded', 'recaptcha_for_all_load_feedback');
function recaptcha_for_all_add_stats_challenge(){
    global $recaptcha_for_all_visitor_ip;
    global $wpdb;
    $table = $wpdb->prefix . "recaptcha_for_all_stats";

    // Sanitize values if needed
    $ip = sanitize_text_field($recaptcha_for_all_visitor_ip);

    // Insert data into the table
    $sql = $wpdb->prepare(
        "INSERT INTO $table (ip, date, challenge) VALUES (%s, %s, %d)",
        $ip,
        current_time('mysql'),
        1
    );
    $result = $wpdb->query($sql);

    // Check for errors
    if ($result === false) 
      recaptcha_for_all_create_db_stats();
}

function recaptcha_for_all_add_stats_ok() {
    global $recaptcha_for_all_visitor_ip;
    global $wpdb;
    $table = $wpdb->prefix . "recaptcha_for_all_stats";

    // Sanitize values if needed
    $ip = sanitize_text_field($recaptcha_for_all_visitor_ip);

    // Insert data into the table
    $sql = $wpdb->prepare(
        "INSERT INTO $table (ip, date, ok) VALUES (%s, %s, %d)",
        $ip,
        current_time('mysql'),
        1
    );
    $result = $wpdb->query($sql);

    // Check for errors
    if ($result === false) 
      recaptcha_for_all_create_db_stats();
}



function recaptcha_for_all_tablexist($table)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "recaptcha_for_all_stats";
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name)
        return true;
    else
        return false;
}

function recaptcha_for_all_create_db_stats()
{
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    // creates my_tabin database if not exists
    $table = $wpdb->prefix . "recaptcha_for_all_stats"; 
    if (recaptcha_for_all_tablexist($table))
        return;
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE " . $table . " (
        `id` mediumint(9) NOT NULL AUTO_INCREMENT,
        `ip` text NOT NULL,
        `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `challenge` int(11) NOT NULL,
        `ok` int(11) NOT NULL,
        `url` text NOT NULL,
        `referer` text NOT NULL,  
        `ua` TEXT NOT NULL,
    UNIQUE (`id`)
    ) $charset_collate;";
    dbDelta($sql);
  
    ob_start();
    $wpdb->query("CREATE INDEX ip ON  `$table` (`ip`(50))");
    ob_end_clean();
}