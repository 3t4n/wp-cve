<?php /*
Plugin Name: AntiHacker 
Plugin URI: http://antihackerplugin.com
Description: Improve security, prevent unauthorized access by restrict access to login to whitelisted IP, Firewall, Scanner and more.
version: 5.05
Text Domain: antihacker
Domain Path: /language
Author: Bill Minozzi
Author URI: http://billminozzi.com
License:     GPL2
Copyright (c) 2015-2019 Bill Minozzi
Antihacker is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
Antihacker is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with Antihacker. If not, see {License URI}.
Permission is hereby granted, free of charge subject to the following conditions:
The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.
*/
// ob_start();
if (!defined('ABSPATH'))
  exit; // Exit if accessed directly
// Fix memory
$antihacker_maxMemory = @ini_get('memory_limit');
$antihacker_last = strtolower(substr($antihacker_maxMemory, -1));
$antihacker_maxMemory = (int) $antihacker_maxMemory;
if ($antihacker_last == 'g') {
  $antihacker_maxMemory = $antihacker_maxMemory * 1024 * 1024 * 1024;
  $antihacker_maxMemory = $antihacker_maxMemory * 1024 * 1024;
  $antihacker_maxMemory = $antihacker_maxMemory * 1024;
}
if ($antihacker_maxMemory < 134217728 /* 128 MB */ && $antihacker_maxMemory > 0) {
  if (strpos(ini_get('disable_functions'), 'ini_set') === false) {
    @ini_set('memory_limit', '128M');
  }
}
$antihacker_plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
$antihacker_plugin_version = $antihacker_plugin_data['Version'];
define('ANTIHACKERVERSION', $antihacker_plugin_version);
define('ANTIHACKERPATH', plugin_dir_path(__file__));
define('ANTIHACKERURL', plugin_dir_url(__file__));
$antihackerserver = sanitize_text_field($_SERVER['SERVER_NAME']);
define('ANTIHACKERIMAGES', plugin_dir_url(__file__) . 'images');
define('ANTIHACKERHOMEURL', admin_url());
$antihacker_current_url = sanitize_url($_SERVER['REQUEST_URI']);
$antihacker_version = trim(sanitize_text_field(get_site_option('antihacker_version', '')));
// debug
//$antihacker_version = '4.41';
define('ANTIHACKERVERSIONANT', $antihacker_version);
$antihacker_request_url = trim(sanitize_url($_SERVER['REQUEST_URI']));
$antihacker_method = sanitize_text_field($_SERVER["REQUEST_METHOD"]);
if (isset($_SERVER['HTTP_REFERER']))
  $antihacker_referer = sanitize_text_field($_SERVER['HTTP_REFERER']);
else
  $antihacker_referer = '';

define( 'ANTIHACKERPATHLANGUAGE', dirname( plugin_basename( __FILE__ ) ) . '/language/');
require_once ANTIHACKERPATH . 'includes/functions/bill-catch-errors.php';

// require_once(ANTIHACKERPATH . "debug.php");
// add_action('shutdown', 'mostra_log', 999);
// Add settings link on plugin page
function antihacker_plugin_settings_link($links)
{
  // $settings_link = '<a href="options-general.php?page=anti-hacker">Settings</a>'; 
  $settings_link = '<a href="admin.php?page=anti-hacker">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'antihacker_plugin_settings_link');

/* Begin Language */
if (is_admin()) {
  add_action('plugins_loaded', 'ah_localization_init');
  function ah_localization_init_fail()
  {
    if(isset($_COOKIE["antihacker_dismiss_language"])) {

      $r = update_option('antihacker_dismiss_language', '1');
      if (!$r) {
        $r = add_option('antihacker_dismiss_language', '1');
      }
    }

    if(get_option('antihacker_dismiss_language') == '1')
		return;

    echo '<div id="antihacker_an2" class="notice notice-warning is-dismissible">';
    echo '<br />';
    echo esc_attr__('Anti Hacker Plugin: Could not load the localization file (Language file)', 'antihacker');
    echo '.<br />';
    echo esc_attr__('Please, take a look in our site, FAQ page, item => How can i translate this plugin?', 'antihacker');
    echo '<br /><br /></div>';
  }
  /*
  if (isset($_GET['page'])) {
    $page = sanitize_text_field($_GET['page']);
    if ($page == 'anti-hacker') {
      $path = dirname(plugin_basename(__FILE__)) . '/language/';
      $loaded = load_plugin_textdomain('antihacker', false, $path);
      if (!$loaded and get_locale() <> 'en_US') {
        //if( function_exists('ah_localization_init_fail'))
        // add_action( 'admin_notices', 'ah_localization_init_fail' );
      }
    }
  }
  */
//  add_action('plugins_loaded', 'ah_localization_init');
}


// antihacker dismissible_notice2
function antihacker_dismissible_notice2() {
	$r = update_option('antihacker_dismiss_language', '1');
	if (!$r) {
		$r = add_option('antihacker_dismiss_language', '1');
	}
	if($r)
	  die('OK!!!!!');
	else
	  die('NNNN');
}
add_action('wp_ajax_antihacker_dismissible_notice2', 'antihacker_dismissible_notice2');







function ah_localization_init()
{

  $loaded = load_plugin_textdomain( 'antihacker', false, ANTIHACKERPATHLANGUAGE );

	if (!$loaded and get_locale() <> 'en_US') {
        if (function_exists('ah_localization_init_fail'))
            add_action('admin_notices', 'ah_localization_init_fail');
    }

}
/* End language */


 // require_once(ANTIHACKERPATH . "settings/load-plugin.php");


 //add_action('wp_enqueue_scripts', 'antihacker_include_scripts');
 //add_action('admin_enqueue_scripts', 'antihacker_include_scripts');
 //add_action('wp_ajax_antihacker_get_ajax_data', 'antihacker_get_ajax_data');
 //add_action('wp_ajax_antihacker_grava_fingerprint', 'antihacker_grava_fingerprint');
 //add_action('wp_ajax_nopriv_antihacker_grava_fingerprint', 'antihacker_grava_fingerprint');


if (is_admin()) {

  require_once(ANTIHACKERPATH . "includes/functions/plugin-check-list.php");

  add_action('wp_ajax_ah_check_plugins_and_display_results', 'ah_check_plugins_and_display_results');

  
  function antihacker_add_admstylesheet()
  {



    global $antihacker_request_url;


    wp_enqueue_style('admin_enqueue_scripts', ANTIHACKERURL . 'settings/styles/admin-settings.css');



    $pos = strpos($antihacker_request_url, 'page=anti_hacker_plugin');
    $pos2 = strpos($antihacker_request_url, 'wp-admin/index.php');
    $pos3 = substr($antihacker_request_url, -10) == '/wp-admin/';

    if ($pos !== false or $pos2 !== false or $pos3) {
      wp_enqueue_script('ah-flot', ANTIHACKERURL .
        'js/jquery.flot.min.js', array('jquery'));
      wp_enqueue_script('flotpie', ANTIHACKERURL .
        'js/jquery.flot.pie.js', array('jquery'));
    }
    wp_enqueue_script('circle', ANTIHACKERURL .
      'js/radialIndicator.js', array('jquery'));
    wp_enqueue_style('bill-datatables-jquery', ANTIHACKERURL . 'assets/css/jquery.dataTables.min.css');
    wp_enqueue_script('botstrap', ANTIHACKERURL .
      'js/bootstrap.bundle.min.js', array('jquery'));
    wp_enqueue_script('easing', ANTIHACKERURL .
      'js/jquery.easing.min.js', array('jquery'));
    wp_enqueue_script('datatables1', ANTIHACKERURL .
      'js/jquery.dataTables.min.js', array('jquery'));
    wp_localize_script('datatables1', 'datatablesajax', array('url' => admin_url('admin-ajax.php')));
    wp_enqueue_script('botstrap4', ANTIHACKERURL .
      'js/dataTables.bootstrap4.min.js', array('jquery'));
    wp_enqueue_script('datatables2', ANTIHACKERURL .
      'js/dataTables.buttons.min.js', array('jquery'));


    $pos = strpos($antihacker_request_url, 'page=antihacker_my-custom-submenu-page');
    if ($pos !== false) {
      wp_register_script('datatables_visitors', ANTIHACKERURL .
        'js/antihacker_table.js', array(), '1.0', true);
    }


    wp_enqueue_script('datatables_visitors');
    $pos = strpos($antihacker_request_url, 'page=antihacker_scan');
    if ($pos !== false) {
      wp_register_script("anti-hacker-scan", ANTIHACKERURL . 'scan/scan.js', array('jquery'), ANTIHACKERVERSION, true);
      wp_enqueue_script('anti-hacker-scan');
    }

    wp_enqueue_script(
			'antihacker_dismiss',
			plugin_dir_url(__FILE__) . 'js/antihacker_dismiss.js'
		);

    
    //12-23
    wp_enqueue_script('ah-ncplugin-check-script', ANTIHACKERURL . 'dashboard/js/antihacker_plugin_check.js', array('jquery'), ANTIHACKERVERSION, true);
  
      // Adicione a variável ajaxurl ao script
      //wp_localize_script('plugin-check-script', 'pluginCheckAjax', array('ajaxurl' => admin_url('admin-ajax.php')));




  }
  add_action('admin_enqueue_scripts', 'antihacker_add_admstylesheet', 1000);
}



$my_whitelist = trim(sanitize_text_field(get_site_option('my_whitelist', '')));
$amy_whitelist = explode(" ", $my_whitelist);
$antihacker_string_whitelist = trim(sanitize_text_field(get_site_option('antihacker_string_whitelist', '')));
$aantihacker_string_whitelist = explode(" ", $antihacker_string_whitelist);
$ah_admin_email = trim(sanitize_text_field(get_option('my_email_to')));
$antihacker_my_radio_report_all_visits =  sanitize_text_field(get_site_option('antihacker_my_radio_report_all_visits', 'No')); // Alert me All Logins


$antihacker_hide_wp = sanitize_text_field(get_option('antihacker_hide_wp', 'yes'));
$antihacker_block_enumeration = sanitize_text_field(get_option('antihacker_block_enumeration', 'no'));
//$antihacker_version = trim(sanitize_text_field(get_site_option('antihacker_version', '')));
// Notifications...
// $antihacker_checkbox_all_failed =  sanitize_text_field(get_site_option('antihacker_checkbox_all_failed', '0')); // Alert me all Failed Login Attempts
$antihacker_checkbox_all_failed  = trim(sanitize_text_field(get_site_option('antihacker_checkbox_all_failed', '0')));


$antihacker_Blocked_else_email = trim(sanitize_text_field(get_site_option('antihacker_Blocked_else_email', 'no')));
$antihacker_Blocked_else_email = strtolower($antihacker_Blocked_else_email);
$antihacker_my_radio_report_all_logins =  sanitize_text_field(get_site_option('antihacker_my_radio_report_all_logins', 'No')); // Alert me All Logins
//
$antihacker_block_all_feeds = trim(sanitize_text_field(get_site_option('antihacker_block_all_feeds', 'no')));
$antihacker_new_user_subscriber = trim(sanitize_text_field(get_site_option('antihacker_new_user_subscriber', 'no')));
$antihacker_checkversion = trim(sanitize_text_field(get_option('antihacker_checkversion', '')));
$antihacker_rate404_limiting = trim(sanitize_text_field(get_option('antihacker_rate404_limiting', 'unlimited')));
$antihacker_application_password = trim(sanitize_text_field(get_option('antihacker_application_password', 'yes')));
$antihacker_update_http_tools = trim(sanitize_text_field(get_option('antihacker_update_http_tools', 'no')));
$antihacker_block_tor = trim(sanitize_text_field(get_site_option('antihacker_block_tor', 'no')));
$antihacker_block_falsegoogle = trim(sanitize_text_field(get_site_option('antihacker_block_falsegoogle', 'no')));
$antihacker_show_widget = trim(sanitize_text_field(get_site_option('antihacker_show_widget', 'no')));
$antihacker_last_plugin_scan = trim(sanitize_text_field(get_site_option('antihacker_last_plugin_scan', '0')));
$antihacker_notif_scan = sanitize_text_field(get_option('antihacker_notif_scan', '0'));
$antihacker_notif_level = sanitize_text_field(get_option('antihacker_notif_level', '0'));
$antihacker_notif_visit = sanitize_text_field(get_option('antihacker_notif_visit', '0'));
$antihacker_last_theme_scan = sanitize_text_field(get_option('antihacker_notif_visit', '0'));
$antihacker_last_theme_update = sanitize_text_field(get_option('antihacker_last_theme_update', '0'));
$antihacker_disable_sitemap = sanitize_text_field(get_option('antihacker_disable_sitemap', 'no'));



$antihacker_plugin_abandoned_email = sanitize_text_field(get_option('antihacker_plugin_abandoned_email', 'yes'));
$antihacker_auto_updates = sanitize_text_field(get_option('antihacker_auto_updates', ''));



if (!empty($antihacker_checkversion)) {
  // $antihacker_block_tor = trim(sanitize_text_field(get_site_option('antihacker_block_tor', 'no')));
  // $antihacker_block_falsegoogle = trim(sanitize_text_field(get_site_option('antihacker_block_falsegoogle', 'no')));
  $antihacker_block_search_plugins = trim(sanitize_text_field(get_site_option('antihacker_block_search_plugins', 'no')));
  $antihacker_block_search_themes = trim(sanitize_text_field(get_site_option('antihacker_block_search_themes', 'no')));
  $antihacker_block_http_tools = sanitize_text_field(get_site_option('antihacker_block_http_tools', 'no'));
  $antihacker_blank_ua = sanitize_text_field(get_site_option('antihacker_blank_ua', 'no'));
  $antihacker_radio_limit_visits =  sanitize_text_field(get_site_option('antihacker_radio_limit_visits', 'no'));
}
else
{
  $antihacker_block_tor = 'no';
  $antihacker_block_falsegoogle = 'no';
  $antihacker_block_search_plugins = 'no';
  $antihacker_block_search_themes = 'no';
  $antihacker_block_http_tools = 'no';
  $antihacker_blank_ua = 'no';
  $antihacker_radio_limit_visits = 'no';
}
require_once(ANTIHACKERPATH . "includes/functions/functions.php");

$anti_hacker_firewall = sanitize_text_field(get_option('antihacker_firewall', 'yes'));
$antihacker_Blocked_Firewall = sanitize_text_field(get_option('antihacker_Blocked_Firewall', 'no'));

if(antihacker_isourserver()) {
   $anti_hacker_firewall = 'no';
   $antihacker_Blocked_Firewall = 'no';
}

if (!empty($_POST["myemail"])) {
  $myemail = sanitize_text_field($_POST["myemail"]);
} else
  $myemail = '';
require_once(ANTIHACKERPATH . 'dashboard/main.php');
require_once(ANTIHACKERPATH . 'scan/dashboard_scan.php');

if (is_admin()){

  require_once(ANTIHACKERPATH . 'includes/functions/health.php');

  add_action('setup_theme', 'antihacker_load_settings');

  function antihacker_load_settings() {
		require_once(ANTIHACKERPATH . "settings/load-plugin.php");
		require_once(ANTIHACKERPATH . "settings/options/plugin_options_tabbed.php");
	}
}

$ah_admin_email = trim(sanitize_text_field(get_option('my_email_to')));
if (!empty($ah_admin_email)) {
  if (!is_email($ah_admin_email)) {
    $ah_admin_email = '';
    update_option('my_email_to', '');
  }
}
if (empty($ah_admin_email))
  $ah_admin_email = sanitize_email(get_option('admin_email'));
// Firewall
if (!is_admin()) {
  if ($anti_hacker_firewall != 'no') {
    $antihacker_request_uri_array  = array('@eval', 'eval\(', 'UNION(.*)SELECT', '\(null\)', 'base64_', '\/localhost', '\%2Flocalhost', '\/pingserver', 'wp-config\.php', '\/config\.', '\/wwwroot', '\/makefile', 'crossdomain\.', 'proc\/self\/environ', 'usr\/bin\/perl', 'var\/lib\/php', 'etc\/passwd', '\/https\:', '\/http\:', '\/ftp\:', '\/file\:', '\/php\:', '\/cgi\/', '\.cgi', '\.cmd', '\.bat', '\.exe', '\.sql', '\.ini', '\.dll', '\.htacc', '\.htpas', '\.pass', '\.asp', '\.jsp', '\.bash', '\/\.git', '\/\.svn', ' ', '\<', '\>', '\/\=', '\.\.\.', '\+\+\+', '@@', '\/&&', '\/Nt\.', '\;Nt\.', '\=Nt\.', '\,Nt\.', '\.exec\(', '\)\.html\(', '\{x\.html\(', '\(function\(', '\.php\([0-9]+\)', '(benchmark|sleep)(\s|%20)*\(', 'indoxploi', 'xrumer');
    $antihacker_query_string_array = array('@@', '\(0x', '0x3c62723e', '\;\!--\=', '\(\)\}', '\:\;\}\;', '\.\.\/', '127\.0\.0\.1', 'UNION(.*)SELECT', '@eval', 'eval\(', 'base64_', 'localhost', 'loopback', '\%0A', '\%0D', '\%00', '\%2e\%2e', 'allow_url_include', 'auto_prepend_file', 'disable_functions', 'input_file', 'execute', 'file_get_contents', 'mosconfig', 'open_basedir', '(benchmark|sleep)(\s|%20)*\(', 'phpinfo\(', 'shell_exec\(', '\/wwwroot', '\/makefile', 'path\=\.', 'mod\=\.', 'wp-config\.php', '\/config\.', '\$_session', '\$_request', '\$_env', '\$_server', '\$_post', '\$_get', 'indoxploi', 'xrumer');
    $antihacker_user_agent_array   = array('drivermysqli', 'acapbot', '\/bin\/bash', 'binlar', 'casper', 'cmswor', 'diavol', 'dotbot', 'finder', 'flicky', 'md5sum', 'morfeus', 'nutch', 'planet', 'purebot', 'pycurl', 'semalt', 'shellshock', 'skygrid', 'snoopy', 'sucker', 'turnit', 'vikspi', 'zmeu');
    $antihacker_request_uri_string  = false;
    $antihacker_query_string_string = false;

    $antihacker_request_uri_string  = '';
    $antihacker_query_string_string = '';
    $antihacker_user_agent_string   = '';
    // $referrer_string     = '';

    if (isset($_SERVER['REQUEST_URI'])     && !empty($_SERVER['REQUEST_URI']))     $antihacker_request_uri_string  = sanitize_text_field($_SERVER['REQUEST_URI'] );
    if (isset($_SERVER['QUERY_STRING'])    && !empty($_SERVER['QUERY_STRING']))    $antihacker_query_string_string = sanitize_text_field($_SERVER['QUERY_STRING']);
    if (isset($_SERVER['HTTP_USER_AGENT']) && !empty($_SERVER['HTTP_USER_AGENT'])) $antihacker_user_agent_string   = sanitize_text_field($_SERVER['HTTP_USER_AGENT']);
    if ($antihacker_request_uri_string || $antihacker_query_string_string || $antihacker_user_agent_screen_string) {
      if (
        preg_match('/' . implode('|', $antihacker_request_uri_array)  . '/i', $antihacker_request_uri_string, $matches)  ||
        preg_match('/' . implode('|', $antihacker_query_string_array) . '/i', $antihacker_query_string_string, $matches2) ||
        preg_match('/' . implode('|', $antihacker_user_agent_array)   . '/i', $antihacker_user_agent_string, $matches3)
      ) {
        // $anti_hacker_firewall
        if ($antihacker_Blocked_Firewall == 'yes') {
          if (isset($matches)) {
            if (is_array($matches)) {
              if (count($matches) > 0) {
                antihacker_alertme3($matches[0]);
              }
            }
          }
          if (isset($matches2)) {
            if (is_array($matches2)) {
              if (count($matches2) > 0)
                antihacker_alertme3($matches2[0]);
            }
          }
          if (isset($matches3)) {
            if (is_array($matches3)) {
              if (count($matches3) > 0)
                antihacker_alertme4($matches3[0]);
            }
          }
        }
        antihacker_stats_moreone('qfire');
        antihacker_response('Firewall');
      } // Endif match...     
    } // endif if ($antihacker_query_string_string || $user_agent_string) 
  } // firewall <> no
}
// End Firewall


if (!ah_whitelisted($antihackerip, $amy_whitelist)) {

  if (sanitize_text_field(get_site_option('antihacker_replace_login_error_msg', 'no')) == 'yes')
  add_filter('login_errors', function ($error) {
    return '<strong>' . __('Wrong Username, Password or eMail', 'antihacker') . '</strong>';
  });


  add_action('login_form', 'ah_email_display');
  add_action('wp_authenticate_user', 'ah_validate_email_field', 10, 2);

  function ah_validate_email_field($user, $password)
  {
    global $myemail;
    global $antihacker_method;
    global $antihacker_referer;
    global $antihacker_request_url;
    global $antihacker_Blocked_else_email;

 
    if(!antihacker_isourserver()    ) {
      if (!empty($antihacker_request_url)) {
        $pos = strpos($antihacker_request_url, 'xmlrpc.php');
        if ($pos !== false) {
          if ($antihacker_Blocked_else_email == 'yes')
            antihacker_alertme13();
          antihacker_stats_moreone('qlogin');
          antihacker_response('Brute Force Login using xmlrpc');
          return;
        }
      }
    }


    // var_dump($antihacker_referer);
    if ($antihacker_method == 'POST' and trim($antihacker_referer) == '') {
      antihacker_stats_moreone('qnoref');
      antihacker_alertme13();
      antihacker_response('Login Post Without Referrer');
    }

    if (!is_email($myemail)) {
      // Blank email
      add_filter('login_errors', function ($error) {
        return '<strong>' . __('Empty email', 'antihacker') . '</strong>';
      });
      antihacker_stats_moreone('qlogin');
      antihacker_alertme13();
      antihacker_gravalog('Failed Login');
      return new WP_Error('wrong_email', 'Please, fill out the email field!');
    } // empty

      // The Query
      $user_query = new WP_User_Query(array('orderby' => 'registered', 'order' => 'ASC'));

      
      // User Loop

      // var_dump($user_query);


      if (!empty($user_query->results)) {
        foreach ($user_query->results as $user) {
          if (strtolower(trim($user->user_email)) == $myemail)
            return $user;
        }
        // echo 'No users found.';
      }

      return new WP_Error('wrong_email', 'email not found!');

  }
} /* endif if (! ah_whitelisted($antihackerip, $my_whitelist)) */
else{

  if (sanitize_text_field(get_site_option('antihacker_replace_login_error_msg', 'no')) == 'yes')
  add_filter('login_errors', function ($error) {
    return '<strong>' . __('Wrong Username or Password', 'antihacker') . '</strong>';
  });


}

add_action('wp_login', 'ah_successful_login');
add_action('wp_login_failed', 'ah_failed_login');
register_deactivation_hook(__FILE__, 'ah_my_deactivation');
register_activation_hook(__FILE__, 'ah_activated');





if (sanitize_text_field(get_site_option('antihacker_disallow_file_edit', 'yes')) == 'yes') {
  if (!defined('DISALLOW_FILE_EDIT'))
    define('DISALLOW_FILE_EDIT', true);
}
if (WP_DEBUG and get_site_option('antihacker_debug_is_true', 'yes') == 'yes')
  add_action('admin_notices', 'ah_debug_enabled');
function antihacker_load_feedback()
{
  if (is_admin()) {
    // ob_start();
    require_once(ANTIHACKERPATH . "includes/feedback/feedback.php");
    require_once(ANTIHACKERPATH . "includes/feedback/feedback-last.php");
  }  // ob_end_clean();
}
add_action('wp_loaded', 'antihacker_load_feedback');
function antihackerplugin_load_activate()
{
  if (is_admin()) {
   // require_once(ANTIHACKERPATH . 'includes/feedback/activated-manager.php');
  }
}
add_action('in_admin_footer', 'antihackerplugin_load_activate');


if (is_admin() or is_super_admin()) {
  if (get_option('antihacker_was_activated', '0') == '1') {
      add_action('admin_enqueue_scripts', 'antihacker_adm_enqueue_scripts2');
  }
}




if ($antihacker_disable_sitemap == 'yes') {
  add_filter( 'wp_sitemaps_add_provider', function ($provider, $name) {
    return ( $name == 'users' ) ? false : $provider;
  }, 10, 2);
}


function antihacker_custom_dashboard_help()
{
  global $antihacker_checkversion;
  $perc = antihacker_find_perc() * 10;
  if ($perc < 71)
    $color = '#ff0000';
  $color = '#000000';
  echo '<img src="' . esc_attr(ANTIHACKERURL) . '/images/logo.png" style="text-align:center; max-width: 300px;margin: 0px 0 auto;"  />';
  echo '<br />';
  if ($perc < 71) {
    echo '<img src="' . esc_attr(ANTIHACKERURL) . '/images/unlock-icon-red-small.png" style="text-align:center; max-width: 20px;margin: 0px 0 auto;"  />';
    echo '<h2 style="margin-top: -39px; margin-left: 25px; color:' . esc_attr($color) . ';">';

  }
  echo 'Protection rate: ' . esc_attr($perc) . '%';
  echo '</h2>';
  $site = esc_url(ANTIHACKERHOMEURL) . "admin.php?page=anti_hacker_plugin";
  echo  '&nbsp;<a href="' . esc_url($site) . '">For details, visit the plugin dashboard</a>';
  echo '<br />';
  echo '<br />';
  echo '<h3 style="font-size:18px; text-align:center;">Total Attacks Blocked Last 15 days</h3>';
  echo '<br />';
  echo '<div style="max-width: 100%;">';
  require_once("dashboard/attacksgraph.php");
  echo '</div>';
  echo '<br />';
  echo '<hr>';
  echo '<br />';
  echo '<h3 style="font-size:18px; text-align:center;">Blocked Attacks by Type</h3>';
  echo '<br />';
  echo '<br />';
  require_once("dashboard/attacksgraph_pie.php");
  echo '<br />';
  // echo '<hr>';
  echo '<br />';
 

  echo '<a href="' . esc_url($site) . '" class="button button-primary">Dashboard</a>';
  echo '<br /><br />';

  echo "</p>";
}
function antihacker_add_dashboard_widgets()
{
  // wp_add_dashboard_widget('antihacker-dashboard', 'Anti Hacker  Activities', 'antihacker_custom_dashboard_help', 'dashboardsbb', 'normal', 'high');
  wp_add_dashboard_widget('antihacker_dashboard_widgets', 'Plugin Anti Hacker Activities', 'antihacker_custom_dashboard_help');
}
function anti_hacker_show_dashboard()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "ah_stats";

  //$query = "SELECT date,qtotal FROM " . $table_name;
  //$results9 = $wpdb->get_results($query);

  $results9 = $wpdb->get_results($wpdb->prepare("
  SELECT date,qtotal FROM  `$table_name`"));


  $results8 = json_decode(json_encode($results9), true);
  unset($results9);
  $x = 0;
  $d = 15;
  for ($i = $d; $i > 0; $i--) {
    $timestamp = time();
    $tm = 86400 * ($x); // 60 * 60 * 24 = 86400 = 1 day in seconds
    $tm = $timestamp - $tm;
    $the_day = date("d", $tm);
    $this_month = date('m', $tm);
    $array30d[$x] = $this_month . $the_day;
    $mykey = array_search(trim($array30d[$x]), array_column($results8, 'date'));
    if ($mykey) {
      $awork = $results8[$mykey]['qtotal'];
      $array30[$x] = $awork;
      $array30[$x] = 0;
      $x++;
    }
  }
  if (count($array30) > 1) {
    for ($i = 0; $i < count($array30); $i++) {
      if ($array30[$i] > 0) {
        return true;
      }
    }
    return false;
  }
}
if (is_admin() and $antihacker_show_widget != 'no')
  add_action("wp_dashboard_setup", "antihacker_add_dashboard_widgets");
if ($antihacker_hide_wp == 'yes')
  remove_action('wp_head', 'wp_generator');

if(!antihacker_isourserver()) {
  if (!is_admin() and $antihacker_block_enumeration == 'yes') {
    // antihacker_block_enumeration();
    add_action('init', 'antihacker_block_enumeration');
    add_filter('rest_endpoints', 'antihacker_filter_rest_endpoints', 10, 1);
  }
}

// Dangerous files...
$anti_hacker_dangerous_files = array(
  'wp-config.php.bak',
  'wp-config.php.bak.a2',
  'wp-config.php.swo',
  'wp-config.php.save',
  'wp-config.php~',
  'wp-config.old',
  '.wp-config.php.swp',
  'wp-config.bak',
  'wp-config.save',
  'wp-config.php_bak',
  'wp-config.php.swp',
  'wp-config.php.old',
  'wp-config.php.original',
  'wp-config.php.orig',
  'wp-config.txt',
  'wp-config.original',
  'wp-config.orig'
);
// $anti_hacker_dangerous_files = array('wp-config.bak', 'wp-config.old', 'wp-config.txt');
if (is_admin()) {
  for ($i = 0; $i < count($anti_hacker_dangerous_files); $i++) {
    $ah_dangerous_file =  ABSPATH . $anti_hacker_dangerous_files[$i];
    if (file_exists($ah_dangerous_file))
      add_action('admin_notices', 'anti_hacker_dangerous_file');
    break;
  }
}
// End Dangerous ...
if ($antihacker_block_all_feeds == 'yes') {


    

  function antihacker_disable_feed()
  {
    wp_die(__('No feed available,please visit our <a href="' . get_bloginfo('url') . '">homepage</a>!'));
  }

  if(!antihacker_isourserver()) {

    add_action('do_feed', 'antihacker_disable_feed', 1);
    add_action('do_feed_rdf', 'antihacker_disable_feed', 1);
    add_action('do_feed_rss', 'antihacker_disable_feed', 1);
    add_action('do_feed_rss2', 'antihacker_disable_feed', 1);
    add_action('do_feed_atom', 'antihacker_disable_feed', 1);
    add_action('do_feed_rss2_comments', 'antihacker_disable_feed', 1);
    add_action('do_feed_atom_comments', 'antihacker_disable_feed', 1);

 }
}
$antihacker_block_media_comments = trim(sanitize_text_field(get_site_option('antihacker_block_media_comments', 'yes')));
if ($antihacker_block_media_comments == 'yes') {
  function anti_hacker_filter_media_comment($open, $post_id)
  {
    $post = get_post($post_id);
    if ($post->post_type == 'attachment') {
      return false;
    }
    return $open;
  }
  add_filter('comments_open', 'anti_hacker_filter_media_comment', 10, 2);
  add_action('template_redirect', 'antihacker_final_step');
}
if (!empty($antihacker_checkversion))
  add_action('plugins_loaded', 'antihacker_update');
if (antihacker_check_blocklist($antihackerip)) {
  if ($antihacker_Blocked_else_email == 'yes') {
    antihacker_alertme8();
  }
  antihacker_stats_moreone('qblack');
  antihacker_response('Black Listed');
}
if ($antihacker_block_tor == 'yes') {
  if (antihacker_is_tor()) {
    if ($antihacker_Blocked_else_email == 'yes') {
      antihacker_alertme9();
    }
    antihacker_stats_moreone('qtor');
    antihacker_response('Tor');
  }
}
require_once ANTIHACKERPATH . 'table/visitors.php';
function ah_whitelisted($antihackerip, $amy_whitelist)
{

  if(gettype($amy_whitelist) != 'array')
  return;

  for ($i = 0; $i < count($amy_whitelist); $i++) {
    if (trim($amy_whitelist[$i]) == $antihackerip)
      return 1;
  }
  return 0;
}
function ah_string_whitelisted($antihacker_ua, $aantihacker_string_whitelist)
{

  if(gettype($aantihacker_string_whitelist) != 'array')
  return;

  for ($i = 0; $i < count($aantihacker_string_whitelist); $i++) {
    if (empty(trim($aantihacker_string_whitelist[$i])))
      continue;
    if (strpos($antihacker_ua, $aantihacker_string_whitelist[$i]) !== false)
      return 1;
  }
  return 0;
}
// To completely disable Application Passwords 
if ($antihacker_application_password == 'yes')
  add_filter('wp_is_application_passwords_available', '__return_false');
if (is_admin())
  add_action('admin_menu', 'antihacker_menu');
function antihacker_menu()
{
  add_submenu_page(
    'anti_hacker_plugin', // $parent_slug
    'Scan For Malware', // string $page_title
    'Scan For Malware', // string $menu_title
    'manage_options', // string $capability
    'antihacker_scan', // menu slug
    'antihacker_scan_dashboard', // callable function
    1 // position
  );
}
if (file_exists(ANTIHACKERPATH . 'scan/functions_scan.php')) 
require_once(ANTIHACKERPATH . "scan/functions_scan.php");
else
  add_action( 'admin_notices', 'antihacker_missing_file' );


//////////////////////////////////////


function antihacker_custom_toolbar_link($wp_admin_bar)
{
  global $wp_admin_bar;
  $site = ANTIHACKERHOMEURL . "admin.php?page=anti_hacker_plugin&tab=notifications";
  $args = array(
    'id' => 'antihacker',
    'title' => '<div class="antihacker-logo"></div><span class="text"> Anti Hacker </span>',
    'href' => $site,
    'meta' => array(
      'class' => 'antihacker',
      'title' => ''
    )
  );
  $wp_admin_bar->add_node($args);
  echo '<style>';
  echo '#wpadminbar .antihacker  {
      background: red !important;
      color: black !important;
    }';
  $logourl = ANTIHACKERIMAGES . "/sologo-gray.png";
  echo '#wpadminbar .antihacker-logo  {
      background-image: url("' . esc_url($logourl) . '");
      float: left;
      width: 26px;
      height: 30px;
      background-repeat: no-repeat;
      background-position: 0 6px;
      background-size: 20px;
    }';
  echo '</style>';
}
// $antihacker_timeout_level = time() > ($antihacker_notif_level + 60 * 60 * 24 * 7);

$antihacker_timeout_visit = time() > ($antihacker_notif_visit + 60 * 60 * 24 * 5);


$table_name = $wpdb->prefix . "ah_scan";

// $query = "select `date_end`  from $table_name ORDER BY id DESC limit 1";


$r = $wpdb->get_var("SELECT `date_end` FROM `$table_name` ORDER BY id DESC limit 1");



if( $r !== null and !empty(trim($r)) ){
  // $last_scan =  strtotime(trim($wpdb->get_var($query)));
   $last_scan =  strtotime(trim($r)); 
}
else
   $last_scan = 0;


$antihacker_timeout_scan = time() > ($antihacker_notif_scan + 60 * 60 * 24 * 7);
if($antihacker_timeout_scan){
  $antihacker_timeout_scan = time() > ($last_scan + 60 * 60 * 24 * 7);
}


$antihacker_timeout_level = time() > ($antihacker_notif_level + 60 * 60 * 24 * 7);
//$antihacker_timeout_level = time() > ($antihacker_notif_level + 10 );

if($antihacker_timeout_level) {

    if(antihacker_find_perc() < 8)
      $antihacker_timeout_level = true;
    else
      $antihacker_timeout_level = false;

}

if ($antihacker_timeout_scan or $antihacker_timeout_level or $antihacker_timeout_visit) {
  if (!is_multisite() and is_admin())
    add_action('admin_bar_menu', 'antihacker_custom_toolbar_link', 999);
}
// require_once ANTIHACKERPATH . "includes/functions/functions_api.php";
function antihacker_add_cors_http_header(){
  header("Access-Control-Allow-Origin: https://antihackerplugin.com");
}
function antihacker_missing_file(){
  echo '<div class="notice notice-warning is-dismissible">';
  echo '<p>Warning - Missing file: functions_scan.php';
  echo '<br>File Path: '.esc_attr(ANTIHACKERPATH); 
  echo '<br>Probably was deleted by some other antivirus because it has some virus signature to detect them.';
  echo '<br>Please, reinstall Anti Hacker plugin.';
  echo '</p></div>';
}

/* =============================== */

function antihacker_add_more_plugins()
{
    if (is_multisite()) {
        add_submenu_page(
            'anti_hacker_plugin', // $parent_slug
            'More Tools Same Author', // string $page_title
            'More Tools Same Author', // string $menu_title
            'manage_options', // string $capability
            'antihacker_more_plugins', // menu slug
            'antihacker_more_plugins', // callable function
            8 // position
        );
	} else {

		add_submenu_page(
            'anti_hacker_plugin', // $parent_slug
            'More Tools Same Author', // string $page_title
            'More Tools Same Author', // string $menu_title
            'manage_options', // string $capability
			// 'wptools_options39', // menu slug
			// 'wptools_new_more_plugins', // callable function
            'antihacker_new_more_plugins', // menu slug
            'antihacker_new_more_plugins', // callable function
			8 // position
		);
	}
}
 add_action('admin_menu', 'antihacker_add_more_plugins');
 add_action('admin_menu', 'antihacker_menu');


function antihacker_more_plugins() {

  echo '<script>';
  echo 'window.location.replace("'.esc_url(ANTIHACKERHOMEURL).'plugin-install.php?s=sminozzi&tab=search&type=author");';
  echo '</script>';
}

function antihacker_show_logo()
{
    echo '<div id="antihackers_logo" style="margin-top:10px;">';
    // echo '<br>';
    echo '<img src="';
    echo esc_url(ANTIHACKERIMAGES) . '/logo.png';
    echo '">';
    echo '<br>';
    echo '</div>';
}

function antihacker_new_more_plugins()
{
  antihacker_show_logo();
	$plugins_to_install = array();
	$plugins_to_install[0]["Name"] = "Anti Hacker Plugin";
	$plugins_to_install[0]["Description"] = "Firewall, Scanner, Login Protect, block user enumeration and TOR, disable Json WordPress Rest API, xml-rpc (xmlrpc) & Pingback and more security tools...";
	$plugins_to_install[0]["image"] = "https://ps.w.org/antihacker/assets/icon-256x256.gif?rev=2524575";
	$plugins_to_install[0]["slug"] = "antihacker";
	$plugins_to_install[1]["Name"] = "Stop Bad Bots";
	$plugins_to_install[1]["Description"] = "Stop Bad Bots, Block SPAM bots, Crawlers and spiders also from botnets. Save bandwidth, avoid server overload and content steal. Blocks also by IP.";
	$plugins_to_install[1]["image"] = "https://ps.w.org/stopbadbots/assets/icon-256x256.gif?rev=2524815";
	$plugins_to_install[1]["slug"] = "stopbadbots";
	$plugins_to_install[2]["Name"] = "WP Tools";
	$plugins_to_install[2]["Description"] = "More than 35 useful tools! It is a swiss army knife, to take your site to the next level.";
	$plugins_to_install[2]["image"] = "https://ps.w.org/wptools/assets/icon-256x256.gif?rev=2526088";
	$plugins_to_install[2]["slug"] = "wptools";
	$plugins_to_install[3]["Name"] = "reCAPTCHA For All";
	$plugins_to_install[3]["Description"] = "Protect ALL Pages of your site against bots (spam, hackers, fake users and other types of automated abuse)
	with invisible reCaptcha V3 (Google). You can also block visitors from China.";
	$plugins_to_install[3]["image"] = "https://ps.w.org/recaptcha-for-all/assets/icon-256x256.gif?rev=2544899";
	$plugins_to_install[3]["slug"] = "recaptcha-for-all";
	$plugins_to_install[4]["Name"] = "WP Memory";
	$plugins_to_install[4]["Description"] = "Check High Memory Usage, Memory Limit, PHP Memory, show result in Site Health Page and fix php low memory limit.";
	$plugins_to_install[4]["image"] = "https://ps.w.org/wp-memory/assets/icon-256x256.gif?rev=2525936";
	$plugins_to_install[4]["slug"] = "wp-memory";

/*
	$plugins_to_install[5]["Name"] = "Truth Social";
	$plugins_to_install[5]["Description"] = "Tools and feeds for Truth Social new social media platform and Twitter.";
	$plugins_to_install[5]["image"] = "https://ps.w.org/toolstruthsocial/assets/icon-256x256.png?rev=2629666";
	$plugins_to_install[5]["slug"] = "toolstruthsocial";
	*/
	$plugins_to_install[5]["Name"] = "Database Backup";
	$plugins_to_install[5]["Description"] = "Database Backup with just one click.";
	$plugins_to_install[5]["image"] = "https://ps.w.org/database-backup/assets/icon-256x256.gif?rev=2862571";
	$plugins_to_install[5]["slug"] = "database-backup";

	$plugins_to_install[6]["Name"] = "Database Restore Bigdump";
	$plugins_to_install[6]["Description"] = "Database Restore with BigDump script.";
	$plugins_to_install[6]["image"] = "https://ps.w.org/bigdump-restore/assets/icon-256x256.gif?rev=2872393";
	$plugins_to_install[6]["slug"] = "bigdump-restore";


	$plugins_to_install[7]["Name"] = "Easy Update URLs";
	$plugins_to_install[7]["Description"] = "Fix your URLs at database after cloning or moving sites.";
	$plugins_to_install[7]["image"] = "https://ps.w.org/easy-update-urls/assets/icon-256x256.gif?rev=2866408";
	$plugins_to_install[7]["slug"] = "easy-update-urls";

	$plugins_to_install[8]["Name"] = "S3 Cloud Contabo";
	$plugins_to_install[8]["Description"] = "Connect you with your Contabo S3-compatible Object Storage.";
	$plugins_to_install[8]["image"] = "https://ps.w.org/s3cloud/assets/icon-256x256.gif?rev=2855916";
	$plugins_to_install[8]["slug"] = "s3cloud";

	$plugins_to_install[9]["Name"] = "Tools for S3 AWS Amazon";
	$plugins_to_install[9]["Description"] = "Connect you with your Amazon S3-compatible Object Storage.";
	$plugins_to_install[9]["image"] = "https://ps.w.org/toolsfors3/assets/icon-256x256.gif?rev=2862487";
	$plugins_to_install[9]["slug"] =  "toolsfors3";

?>
	<div style="padding-right:20px;">
		<br>
		<h1>Useful FREE Plugins of the same author</h1>
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
		<table style="margin-right:20px; border-spacing: 0 25px; " class="widefat" cellspacing="0" id="antihacker-more-plugins-table">
			<tbody class="antihacker-more-plugins-body">
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
					if (antihacker_plugin_installed($plugins_to_install[$i]["slug"]))
						echo '<a href="#" class="button activate-now">Installed</a>';
					else
						echo '<a href="#" id="' . esc_attr($plugins_to_install[$i]["slug"]) . '"class="button button-primary ah-bill-install-now">Install</a>';
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
    <?php echo '<div id="antihacker_nonce" style="display:none;" >'. wp_create_nonce('antihacker_install_plugin'); ?>

	</div>
<?php
}
function antihacker_plugin_installed($slug)
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
if (!function_exists('wp_get_current_user')) {
	require_once(ABSPATH . "wp-includes/pluggable.php");
}
add_action('admin_menu', 'antihacker_add_menu_items9');

/* ---------------------------------- */

function antihacker_load_upsell()
{

  global $antihacker_checkversion;

	wp_enqueue_style('antihacker-more2', ANTIHACKERURL . 'includes/more/more2.css');
	wp_register_script('antihacker-more2-js', ANTIHACKERURL . 'includes/more/more2.js', array('jquery'));
	wp_enqueue_script('antihacker-more2-js');

  if(!empty($antihacker_checkversion ))
  return;

  if(isset($_COOKIE["ah_dismiss"])) {
    $today = time();
    if (!update_option('bill_go_pro_hide', $today))
        add_option('bill_go_pro_hide', $today);
  }

  $antihacker_bill_go_pro_hide = trim(get_option('bill_go_pro_hide',''));


  // $antihacker_bill_go_pro_hide = '';
  // Debug ...
  $wtime = strtotime('-08 days');
  // update_option('antihacker_bill_go_pro_hide', $wtime);
  if (empty($antihacker_bill_go_pro_hide)) {
      $wtime = strtotime('-05 days');
      update_option('bill_go_pro_hide', $wtime);
      $antihacker_bill_go_pro_hide =  $wtime;
  }

  if(strlen($antihacker_bill_go_pro_hide) < 10)
     $antihacker_bill_go_pro_hide = strtotime($antihacker_bill_go_pro_hide);
    

  $now = time();
  $delta = $now - $antihacker_bill_go_pro_hide;
  // debug
  // $delta = time();


  $antihacker_activation_date = get_option('antihacker_activation_date');

	if ($antihacker_activation_date) {
		$antihacker_activation_date = date('Y-m-d', $antihacker_activation_date);
		$today = date('Y-m-d');

		if ($antihacker_activation_date === $today) {
			$delta = 0;
		} 

	} 

  if ($delta > (3600 * 24 * 6)) {
      $list = 'enqueued';
      if (!wp_script_is('bill-css-vendor-fix', $list)) {
          require_once(ANTIHACKERPATH . 'includes/vendor/vendor.php');
          wp_enqueue_style('bill-css-vendor-fix-ah', ANTIHACKERURL . 'includes/vendor/vendor_fix.css');

          wp_register_script("bill-js-vendor", ANTIHACKERURL . 'includes/vendor/vendor.js', array('jquery'), ANTIHACKERVERSION, true);
          wp_enqueue_script('bill-js-vendor');
        
      }
  }

  wp_register_script("bill-js-vendor-sidebar", ANTIHACKERURL . 'includes/vendor/vendor-sidebar.js', array('jquery'), ANTIHACKERVERSION, true);
	wp_enqueue_script('bill-js-vendor-sidebar');

  wp_enqueue_style('bill-css-vendor', ANTIHACKERURL . 'includes/vendor/vendor.css');
}


if (!function_exists('wp_get_current_user')) {
	require_once(ABSPATH . "wp-includes/pluggable.php");
}
if (is_admin() or is_super_admin()) {
	add_action('admin_enqueue_scripts', 'antihacker_load_upsell');
	add_action('wp_ajax_antihacker_install_plugin', 'antihacker_install_plugin');
}

// Bill-11
function antihacker_install_plugin()
{
  if (isset($_POST['nonce'])) {
    $nonce = sanitize_text_field($_POST['nonce']);
    if ( ! wp_verify_nonce( $nonce, 'antihacker_install_plugin' ) ) 
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
		// $nonce = 'install-plugin_' . $api->slug;
		/*
        $type = 'web';
        $url = $source;
        $title = 'wptools';
        */
		$plugin = $slug;
		// verbose...
		//    $upgrader = new Plugin_Upgrader($skin = new Plugin_Installer_Skin(compact('type', 'title', 'url', 'nonce', 'plugin', 'api')));
		class antihacker_QuietSkin extends \WP_Upgrader_Skin
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
		$skin = new antihacker_QuietSkin(array('api' => $api));
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
add_action('admin_menu', 'antihacker_add_menu_items9');


/* ------------------------------------*/

function antihacker_plugin_row_meta($links, $file)
{
	if (strpos($file, 'antihacker.php') !== false) {


		if (is_multisite()) 
		    $url = ANTIHACKERHOMEURL . "plugin-install.php?s=sminozzi&tab=search&type=author";
     	else
	    	$url = ANTIHACKERHOMEURL . "admin.php?page=antihacker_new_more_plugins";


		$new_links['Pro'] = '<a href="' . $url . '" target="_blank"><b><font color="#FF6600">Click To see more plugins from same author</font></b></a>';
		$links = array_merge($links, $new_links);
	}
	return $links;
}
//add_filter('plugin_row_meta', 'antihacker_plugin_row_meta', 10, 2);

function antihacker_bill_go_pro_hide2()
{
    // $today = date('Ymd', strtotime('+06 days'));
    $today = time();
    if (!update_option('bill_go_pro_hide', $today))
        add_option('bill_go_pro_hide', $today);
    wp_die();
}
add_action('wp_ajax_antihacker_bill_go_pro_hide2', 'antihacker_bill_go_pro_hide2');


///////////////   antihacker_auto_updates

if($antihacker_auto_updates !== 'No'){
	if( !antihacker_check_autoupdate()){
		antihacker_check_autoupdate_activate();
	}
}

// volta false se plugin nao esta ativado.
function antihacker_check_autoupdate(){
	$plugin_slug = 'antihacker';
	$auto_update_settings = get_site_option('auto_update_plugins', array());
	if ($auto_update_settings && is_array($auto_update_settings) ) {
		foreach ($auto_update_settings as $plugin_path) {
      if (strpos($plugin_path, $plugin_slug) !== false) 
				return true;
		}
	} 
	return false;
}

function antihacker_check_autoupdate_activate(){

	$auto_update_settings = get_site_option('auto_update_plugins', array());
	$target_plugin_slug_details = 'antihacker/antihacker.php';
	$auto_update_settings[] = $target_plugin_slug_details;
	update_site_option('auto_update_plugins', $auto_update_settings);
	return;
}

///////////////   antihacker_auto_updates END

//---------------- BEGIN $antihacker_plugin_abandoned_email



/*
function antihacker_automatic_plugin_scan() {

   global $antihacker_plugin_abandoned_email;
   global $ah_admin_email;
   global $antihacker_last_plugin_scan;


  if($antihacker_plugin_abandoned_email != 'no'){

      if (empty($ah_admin_email))
      $ah_admin_email = sanitize_email(get_option('admin_email',''));


    $timeout_plugin_scan = time() > ($antihacker_last_plugin_scan + (60 * 60 * 24 * 6));
 
    if($timeout_plugin_scan){
      $r = antihacker_scan_plugins();
      update_option('antihacker_last_plugin_scan', time());


          if (gettype($r) === 'array' && count($r) > 0) {
            function filterElements($element) {
              return strpos($element, '***') !== false;
            }
            $result = array_filter($r, 'filterElements');

            if (empty($result)) {
              return;
            }

            foreach ($result as &$element) {
                $element = str_replace('***', '', $element);
                $element = str_replace('=>', '', $element);
                $element = str_replace('>', '', $element);
                $element = str_replace('&gt;', '', $element);
            }


            $string_result = implode("\n", $result);


            if (empty($string_result)) 
            return;




            if(isset($_SERVER['SERVER_NAME'])) {
              $dom = sanitize_text_field($_SERVER['SERVER_NAME']);
              $message =  __('This email was sent from your website', "antihacker");
              $message .= ': ' . $dom . ' ';
              $message .=  __('by the AntiHacker plugin.', "antihacker");
              $message .= "\n";
            }



            $message .= esc_attr__("We conducted tests on the WordPress repository, and it appears that some plugins are not being updated. Plugins not updated in the last year are suspect to be abandoned, and we suggest replacing them.",'antihacker');
            $message .= "\n";
            $message .= "\n";
            $subject = esc_attr__("Some plugins on the site require attention.",'antihacker');
            $message .= $string_result;
            $message .= "\n";
            $message .= "\n";
            $message .= __('Visit the Anti Hacker plugin dashboard for additional tips on enhancing site security.', "antihacker");
            $message .= "\n";
            $message .= "\n";
            $message .= __('You can stop emails at the Notifications Tab.', "antihacker");
            $message .= "\n";
            $message .= __('Dashboard => Anti Hacker => Settings => Notifications Settings (tab)', "antihacker");
            $headers = array('Content-Type: text/plain; charset=UTF-8');
            $success = wp_mail($ah_admin_email, $subject, $message, $headers);
            //$success = true;
            if (!$success) {
              error_log('Fail to send email antihacker_plugin_abandoned_email');
            }
          }
    }
  }
}
*/

antihacker_automatic_plugin_scan();

function antihacker_automatic_plugin_scan() {
  global $antihacker_plugin_abandoned_email, $ah_admin_email, $antihacker_last_plugin_scan;


  if ($antihacker_plugin_abandoned_email != 'no') {


      if (empty($ah_admin_email)) {
          $ah_admin_email = sanitize_email(get_option('admin_email', ''));
      }

      $timeout_plugin_scan = time() > ($antihacker_last_plugin_scan + (60 * 60 * 24 * 6));

      if ($timeout_plugin_scan) {


          $r = antihacker_scan_plugins();
          update_option('antihacker_last_plugin_scan', time());

          if (is_array($r) && !empty($r)) {




              $result = array_filter($r, function($element) {
                  return strpos($element, '***') !== false;
              });


              if (empty($result)) {
                  return;
              }

              foreach ($result as &$element) {
                  $element = str_replace(['***', '=>', '>', '&gt;'], '', $element);
              }

              $string_result = implode("\n", $result);


              if (empty($string_result)) {
                  return;
              }

              if (isset($_SERVER['SERVER_NAME'])) {
                  $dom = sanitize_text_field($_SERVER['SERVER_NAME']);
                  $message = __('This email was sent from your website', 'antihacker') . ': ' . $dom . ' ' . __('by the AntiHacker plugin.', 'antihacker') . "\n\n";
              }

              $message .= __('We conducted tests on the WordPress repository, and it appears that some plugins are not being updated. Plugins not updated in the last year are suspect to be abandoned, and we suggest replacing them.', 'antihacker') . "\n\n";
              $subject = __('Some plugins on the site require attention.', 'antihacker');
              $message .= $string_result . "\n\n";
              $message .= __('Visit the Anti Hacker plugin dashboard for additional tips on enhancing site security.', 'antihacker') . "\n\n";
              $message .= __('You can stop emails at the Notifications Tab.', 'antihacker') . "\n";
              $message .= __('Dashboard => Anti Hacker => Settings => Notifications Settings (tab)', 'antihacker') . "\n";
              $headers = ['Content-Type: text/plain; charset=UTF-8'];

              $success = wp_mail($ah_admin_email, $subject, $message, $headers);

              if (!$success) {
                  error_log('Failed to send email antihacker_plugin_abandoned_email');
              }
          }
      }
  }
}


function antihacker_scan_plugins()
{
    try {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $all_plugins_work = get_plugins();
        if (!is_array($all_plugins_work) || empty($all_plugins_work)) {
            throw new Exception("Unable to retrieve plugins. get_plugins() failed.");
        }
        $all_plugins = array_keys($all_plugins_work);
        $q = count($all_plugins);
        if ($q <= 0) {
            throw new Exception("No plugins found.");
        }
        $result = array(); 
        for ($i = 0; $i < $q; $i++) {
            $pos = strpos($all_plugins[$i], '/');
            $myplugin = trim(substr($all_plugins[$i], 0, $pos));
            if (empty($myplugin) || strlen($myplugin) < 3) {
                continue;
            }
            $pluginData = antihackerCheckPluginUpdate($myplugin);

            if (!is_array($pluginData) || !isset($pluginData['last_updated'])) {
                $last_update = 'Not Found';
            } else {
                $last_update = substr($pluginData['last_updated'], 0, 10);
            }
            // Ajuste da lógica para determinar o timeout
            if ($last_update !== 'Not Found') {
                $timeout = strtotime($last_update) + (60 * 60 * 24 * 365);
                $plugin_info = esc_attr($last_update) . ' - ' . esc_attr($myplugin);
                if ($timeout < time()) {
                    $plugin_info = '***' . $plugin_info . '***';
                }
            } else {
                // Defina um valor padrão para evitar problemas no loop
                $plugin_info = 'Not Found => ' . esc_attr($myplugin);
            }
            
            $result[] = $plugin_info; // Adiciona a string ao array
        }
        return $result; // Retorna o array de strings
    } catch (Exception $e) {
        // Tratar a exceção aqui ou logar para fins de depuração
        error_log("Exception in antihacker_scan_plugins(): " . $e->getMessage());
        return false; // Retorna false em caso de falha
    }
}

function antihackerCheckPluginUpdate($plugin) {
  try {
      $response = wp_remote_get('https://api.wordpress.org/plugins/info/1.0/' . esc_attr($plugin) . '.json');
      if (is_wp_error($response)) {
          throw new Exception("Failed to retrieve plugin information for $plugin. Error: " . $response->get_error_message());
      }
      $body = wp_remote_retrieve_body($response);
      if (empty($body)) {
          throw new Exception("Empty response body for $plugin.");
      }
      $decoded_body = json_decode($body, true);
      if (!$decoded_body) {
          throw new Exception("Failed to decode JSON response for $plugin.");
      }
      return $decoded_body;
  } catch (Exception $e) {
      error_log("Exception in antihackerCheckPluginUpdate($plugin): " . $e->getMessage());
      return array(); 
  }
}
//---------------- END $antihacker_plugin_abandoned_email

// add_action('init','antihacker_add_cors_http_header');
//$out2 = ob_get_contents();
//ob_end_clean ( );

add_action('init', 'antihacker_schedule_cron_event_plugins_scan');

function antihacker_schedule_cron_event_plugins_scan() {
    // Check if the cron event is already scheduled
    if (!wp_next_scheduled('antihacker_cron_event_plugins_scan')) {
        // Schedule the event to run once week
        wp_schedule_event(time(), 'antihacker_once_week', 'antihacker_cron_event_plugins_scan');
    }
}

// Add a new interval of 1 week
add_filter('cron_schedules', 'antihacker_add_cron_interval_plugins_scan');

function antihacker_add_cron_interval_plugins_scan($schedules) {
    $schedules['antihacker_once_week'] = array(
        'interval' => (60*24*7), 
        'display'  => 'Once Per Week'
    );
    return $schedules;
}

// Function to be executed by the cron event
add_action('antihacker_cron_event_plugins_scan', 'antihacker_automatic_plugin_scan');


// 2 antihacker_upd_tor_db -> daily

add_action('init', 'antihacker_schedule_cron_event_update_tor');

function antihacker_schedule_cron_event_update_tor() {
    // Check if the cron event is already scheduled
    if (!wp_next_scheduled('antihacker_cron_event_update_tor')) {
        // Schedule the event to run once week
        wp_schedule_event(time(), 'antihacker_daily_tor', 'antihacker_cron_event_update_tor');
    }
}

// Add a new interval of 1 week
add_filter('cron_schedules', 'antihacker_add_cron_interval_update_tor');

function antihacker_add_cron_interval_update_tor($schedules) {
    $schedules['antihacker_daily-tor'] = array(
        'interval' => (60*24), 
        'display'  => 'Daily'
    );
    return $schedules;
}

// Function to be executed by the cron event
add_action('antihacker_cron_event_update_tor', 'antihacker_upd_tor_db');


// 3 antihacker_cron_function_clean_db daily

add_action('init', 'antihacker_schedule_cron_event_clean_db');

function antihacker_schedule_cron_event_clean_db() {
    // Check if the cron event is already scheduled
    if (!wp_next_scheduled('antihacker_cron_event_clean_db')) {
        // Schedule the event to run once week
        wp_schedule_event(time(), 'antihacker_daily_clean', 'antihacker_cron_event_clean_db');
    }
}

// Add a new interval of 1 week
add_filter('cron_schedules', 'antihacker_add_cron_interval_clean_db');

function antihacker_add_cron_interval_clean_db($schedules) {
    $schedules['antihacker_daily_clean'] = array(
        'interval' => (60*24), 
        'display'  => 'Daily'
    );
    return $schedules;
}

// Function to be executed by the cron event
add_action('antihacker_cron_event_clean_db', 'antihacker_cron_function_clean_db');
// cron 24 end