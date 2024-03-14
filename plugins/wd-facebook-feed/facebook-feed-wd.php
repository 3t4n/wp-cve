<?php
/**
 * Plugin Name: 10Web Social Feed
 * Plugin URI: https://10web.io/plugins/wordpress-facebook-feed/?utm_source=facebook_feed&utm_medium=free_plugin
 * Description: 10Web Social Feed is a completely customizable, responsive solution to help you display your Facebook feed on your WordPress website.
 * Version: 1.2.9
 * Author: 10Web
 * Author URI: https://10web.io/plugins/?utm_source=facebook_feed&utm_medium=free_plugin
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

define('WD_FFWD_DIR', WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)));
define('WD_FFWD_URL', plugins_url(plugin_basename(dirname(__FILE__))));
define('WD_FFWD_MAIN_FILE', plugin_basename(__FILE__));
define('WD_FB_PREFIX', 'ffwd');
define('WD_FB_IS_FREE', true);
define('WD_FB_TIMELINE_MAX_CALL_COUNT', 5);
if ( !defined('FFWD_VERSION') ) {
  define('FFWD_VERSION', '1.2.9');
}
add_action( 'admin_init', 'ffwd_init' );

function ffwd_init() {
  ffwd_privacy_policy();
}

function ffwd_use_home_url() {
    $home_url = str_replace("http://", "", home_url());
    $home_url = str_replace("https://", "", $home_url);
    $pos = strpos($home_url, "/");
    if ($pos) {
        $home_url = substr($home_url, 0, $pos);
    }

    $site_url = str_replace("http://", "", WD_FFWD_URL);
    $site_url = str_replace("https://", "", $site_url);
    $pos = strpos($site_url, "/");
    if ($pos) {
        $site_url = substr($site_url, 0, $pos);
    }

    return $site_url != $home_url;
}

if (ffwd_use_home_url()) {
    define('WD_FFWD_FRONT_URL', home_url("wp-content/plugins/" . plugin_basename(dirname(__FILE__))));
} else {
    define('WD_FFWD_FRONT_URL', WD_FFWD_URL);
}

add_action('init', 'ffwd_silent_update');

function ffwd_silent_update(){
    if (get_option('ffwd_old_version') === false && get_option('ffwd_version') !== false) {
        add_option('ffwd_old_version', get_option('ffwd_version'));
    }
}

// Plugin menu.
function ffwd_menu_panel() {
  if ( empty(get_option('ffwd_pages_list')) ) {
    $parent_slug = 'options_ffwd';
    add_menu_page('Facebook Feed', 'Facebook Feed', 'manage_options', $parent_slug, 'ffwd_menu', WD_FFWD_URL . '/images/ffwd/ffwd_logo_small.png');
    $galleries_page = add_submenu_page($parent_slug, 'Options', 'Options', 'manage_options', 'options_ffwd', 'ffwd_menu');
    add_action('admin_print_styles-' . $galleries_page, 'ffwd_styles');
    add_action('admin_print_scripts-' . $galleries_page, 'ffwd_scripts');
    add_action('load-' . $galleries_page, 'ffwd_add_themes_per_page_option');
  }
  else {
    $parent_slug = 'info_ffwd';
    add_menu_page('Facebook Feed', 'Facebook Feed', 'manage_options', $parent_slug, 'ffwd_menu', WD_FFWD_URL . '/images/ffwd/ffwd_logo_small.png');
    $galleries_page = add_submenu_page($parent_slug, 'Feeds', 'Feeds', 'manage_options', 'info_ffwd', 'ffwd_menu');
    add_action('admin_print_styles-' . $galleries_page, 'ffwd_styles');
    add_action('admin_print_scripts-' . $galleries_page, 'ffwd_scripts');
    add_action('load-' . $galleries_page, 'ffwd_add_ffwd_info_per_page_option');
    $options_page = add_submenu_page($parent_slug, 'Options', 'Options', 'manage_options', 'options_ffwd', 'ffwd_menu');
    add_action('admin_print_styles-' . $options_page, 'ffwd_styles');
    add_action('admin_print_scripts-' . $options_page, 'ffwd_admin_scripts');
    $themes_page = add_submenu_page($parent_slug, 'Themes', 'Themes', 'manage_options', 'themes_ffwd', 'ffwd_menu');
    add_action('admin_print_styles-' . $themes_page, 'ffwd_styles');
    add_action('admin_print_scripts-' . $themes_page, 'ffwd_admin_scripts');
    add_action('load-' . $themes_page, 'ffwd_add_themes_per_page_option');
    if ( WD_FB_IS_FREE ) {
      $licensing_page = add_submenu_page($parent_slug, 'Get Premium', 'Get Premium', 'manage_options', 'ffwd_licensing', 'ffwd_licensing_page');
      add_action('admin_print_styles-' . $licensing_page, 'ffwd_styles');
      /* Custom link to wordpress.org*/ global $submenu;
      $url = 'https://wordpress.org/support/plugin/wd-facebook-feed/#new-post';
      $submenu[$parent_slug][] = array(
        '<div id="ffwd_ask_question">' . __('Ask a question', 'ffwd_menu') . '</div>',
        'manage_options',
        $url,
      );
    }
  }
  $uninstall_page = add_submenu_page($parent_slug, 'Uninstall', 'Uninstall', 'manage_options', 'uninstall_ffwd', 'ffwd_menu');
  add_action('admin_print_styles-' . $uninstall_page, 'ffwd_styles');
  add_action('admin_print_scripts-' . $uninstall_page, 'ffwd_admin_scripts');
}
add_action('admin_menu', 'ffwd_menu_panel', 9);

function ffwd_privacy_policy() {
  if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
    return;
  }

  $title = __('Facebook Feed by 10Web', "ffwd");
  $link = '<a target="_blank" href="https://www.facebook.com/policy/">' . __('Privacy Policy', "ffwd") . '</a>';
  $text  = sprintf(__('Inform visitors that your website makes use of  Facebook API to receive public data for facebook feed. Provide message that may request you to delete their Facebook data if it is accidentally cached in your website database with feed data. If you enabled “show page plugin” option for Facebook feed, Facebook will load some JS and embedded content which may track visitors. Facebook embeds are regulated under terms of Facebook %s', "ffwd"), $link);
  $text .= "<br/>";
  $text .= __('10Web Disclaimer: The above text is for informational purposes only and is not a legal advice. You must not rely on it as an alternative to legal advice. You should contact your legal counsel to obtain advice with respect to your particular case.', "ffwd");
  $text .= "<br/>&nbsp;";

  wp_add_privacy_policy_content(
    $title,
    $text
  );
}

add_action("init", "ffwd_overview", 9);

function ffwd_overview() {
  if ( is_admin() && !isset($_REQUEST['ajax']) ) {
    if ( !class_exists("TenWebLib") ) {
      $plugin_dir = apply_filters('tenweb_free_users_lib_path', array(
        'version' => '1.1.1',
        'path' => WD_FFWD_DIR,
      ));
      require_once($plugin_dir['path'] . '/wd/start.php');
    }
    global $ffwd_options;
    $ffwd_options = array(
      "prefix" => "ffwd",
      "wd_plugin_id" => 151,
      "plugin_id" => 93,
      "plugin_wd_zip_name" => "wd-facebook-feed.zip",
      // to do
      "plugin_title" => "Facebook Feed by 10Web",
      "plugin_wordpress_slug" => "wd-facebook-feed",
      "plugin_dir" => WD_FFWD_DIR,
      "plugin_url" => WD_FFWD_URL,
      "plugin_main_file" => __FILE__,
      "wd_plugin_name_personal" => "Facebook Feed by 10Web Personal (WordPress)",
      "wd_plugin_name_business" => "Facebook Feed by 10Web Business (WordPress)",
      "wd_plugin_name_developer" => "Facebook Feed by 10Web Developer (WordPress)",
      "description" => __('Facebook Feed by 10Web is a completely customizable, responsive solution to help you display your Facebook feed on your WordPress website.', 'wd_ads'),
      "addons" => '',
      "plugin_features" => array(
        0 => array(
          "title" => __("Facebook Feed by 10Web", "wd_ads"),
          "description" => __("
Facebook Feed by 10Web is a completely customizable, responsive solution to help you display your Facebook feed on your WordPress website. The plugin comes with a number of great features and functionality. Add as many feeds as you want and easily display content from your Facebook profile, page or group in any posts or page using shortcodes.
The plugin allows you to display photos, videos and more. Facebook Feed by 10Web comes with the awesome Lightbox feature to display galleries in a pop-up window.
The plugin offers tons of customization options, including filtering by content type and user roles, theme and layout options and more. No matter how much design and technical knowledge you have, you can set-up in minutes and change the plugin to better fit your website.
                     ", "wd_ads"),
        ),
        1 => array(
          "title" => __("Simple Configuration", "wd_ads"),
          "description" => __("
Facebook Feed by 10Web is easy to install and set-up. This WordPress plugin allows you to display any Facebook feeds with advanced configuration in just a few simple steps.", "wd_ads"),
        ),
        2 => array(
          "title" => __("Completely Customizable", "wd_ads"),
          "description" => __("The plugin comes with a number of styling and customization options that are straightforward and easy to use. Create your own themes by adjusting border and background colors, font sizes and much more to completely adopt the plugin to better fit your website or choose one of the ready made themes.", "wd_ads"),
        ),
        3 => array(
          "title" => __("Display Post Types", "wd_ads"),
          "description" => __("Control what type of posts you want to display you want to display. You can decide to show all the content on your timeline or you can display specific content filtering by user roles or post types like photos, videos, etc.", "wd_ads"),
        ),
        4 => array(
          "title" => __("
Multiple Feeds per Post/Page", "wd_ads"),
          "description" => __("Add unlimited number of Facebook feeds on the same page or post. Use different themes/layouts for each feed.", "wd_ads"),
        ),
        5 => array(
          "title" => __("Advanced Layouts for Feeds"),
          "description" => __("Depending on the content type you can choose from the list of layout options to display each feed - blog style, thumbnails, masonry view, etc. Use layouts to completely match your website by using built-in customization options.", "wd_ads"),
        ),
        5 => array(
          "title" => __("Video content support"),
          "description" => __("Embed videos uploaded on your Facebook timeline or shared from YouTube, Vimeo or other sources. You can choose to open the videos with pop-up Lightbox or redirect users to Facebook.", "wd_ads"),
        ),
        6 => array(
          "title" => __("Lightbox"),
          "description" => __("The plugin comes with a Lightbox feature, which enables you to show your Facebook feed photos and videos in a pop-up window. Choose from a wide range of Lightbox effects and allow your users to view comments, shares, likes and more without leaving your website.", "wd_ads"),
        ),
        6 => array(
          "title" => __("Event Display"),
          "description" => __("Display events from your Facebook feed with featured images, address, map and detailed description of the event.", "wd_ads"),
        ),
        6 => array(
          "title" => __("Social Buttons"),
          "description" => __("Increase social engagement of your Facebook feed by activating the Facebook, Twitter buttons. Allow your site visitors to share posts, photos and videos from your FB feed to their preferred social channel.", "wd_ads"),
        ),
      ),
      "user_guide" => array(
        0 => array(
          "main_title" => __("Installation", "wd_ads"),
          "url" => "https://help.10web.io/hc/en-us/articles/360017959512-Getting-Facebook-Access-Token?utm_source=facebook_feed&utm_medium=free_plugin",
          "titles" => array()
        ),
        1 => array(
          "main_title" => __("Options", "wd_ads"),
          "url" => "https://help.10web.io/hc/en-us/articles/360018233951-Configuring-Facebook-feed?utm_source=facebook_feed&utm_medium=free_plugin",
          "titles" => array()
        ),
        2 => array(
          "main_title" => __("Creating a Facebook Feed", "wd_ads"),
          "url" => "https://help.10web.io/hc/en-us/articles/360018233951-Configuring-Facebook-feed?utm_source=facebook_feed&utm_medium=free_plugin",
          "titles" => array(
            array(
              "title" => __("Main Settings", "wd_ads"),
              "url" => "https://help.10web.io/hc/en-us/articles/360018233951-Configuring-Facebook-feed?utm_source=facebook_feed&utm_medium=free_plugin",
            ),
            array(
              "title" => __("Lightbox settings", "wd_ads"),
              "url" => "https://help.10web.io/hc/en-us/articles/360018233951-Configuring-Facebook-feed?utm_source=facebook_feed&utm_medium=free_plugin",
            ),
            array(
              "title" => __("Comments", "wd_ads"),
              "url" => "https://help.10web.io/hc/en-us/articles/360018233951-Configuring-Facebook-feed?utm_source=facebook_feed&utm_medium=free_plugin",
            ),
            array(
              "title" => __("Page plugin", "wd_ads"),
              "url" => "https://help.10web.io/hc/en-us/articles/360018233951-Configuring-Facebook-feed?utm_source=facebook_feed&utm_medium=free_plugin",
            )
          )
        ),
        3 => array(
          "main_title" => __("Themes", "wd_ads"),
          "url" => "https://help.10web.io/hc/en-us/articles/360017960352--Facebook-Feed-WD-Themes?utm_source=facebook_feed&utm_medium=free_plugin",
          "titles" => array(),
        ),
        4 => array(
          "main_title" => __("Publishing Facebook Feed", "wd_ads"),
          "url" => "https://help.10web.io/hc/en-us/articles/360017960592-Publishing-Facebook-feed?utm_source=facebook_feed&utm_medium=free_plugin",
          "titles" => array()
        ),
      ),
      "plugin_wd_demo_link" => "https://demo.10web.io/facebook-feed?utm_source=facebook_feed&utm_medium=free_plugin",
      "plugin_wd_url" => "https://10web.io/plugins/wordpress-facebook-feed/?utm_source=facebook_feed&utm_medium=free_plugin",
      "plugin_wd_docs_link" => "https://help.10web.io/hc/en-us/sections/4403737033874?utm_source=facebook_feed&utm_medium=free_plugin",
      "after_subscribe" => admin_url('admin.php?page=info_ffwd'),
      "plugin_wizard_link" => NULL,
      "plugin_menu_title" => "Facebook Feed by 10Web",
      "plugin_menu_icon" => WD_FFWD_URL . '/images/ffwd/ffwd_logo_small.png',
      "deactivate" => TRUE,
      "subscribe" => FALSE,
      "custom_post" => 'info_ffwd',
      "display_overview" => FALSE,
    );
    ten_web_lib_init($ffwd_options);
  }
}

function ffwd_add_uninstall_submenu()
{
    $uninstall_page = add_submenu_page('overview_ffwd', 'Uninstall', 'Uninstall', 'manage_options', 'uninstall_ffwd', 'ffwd_menu');
    add_action('admin_print_styles-' . $uninstall_page, 'ffwd_styles');
    add_action('admin_print_scripts-' . $uninstall_page, 'ffwd_admin_scripts');
}

add_action('admin_menu', 'ffwd_add_uninstall_submenu');


function ffwd_menu() {
    global $wpdb;
    require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
    $page = WDW_FFWD_Library::get('page');
    if (($page != '') && (($page == 'info_ffwd') || ($page == 'overview_ffwd') || ($page == 'options_ffwd') || ($page == 'themes_ffwd') || ($page == 'uninstall_ffwd') || ($page == 'FFWDShortcode'))) {

        $acc_tocken = $wpdb->get_var("SELECT access_token FROM " . $wpdb->prefix . "wd_fb_option WHERE id=1");
        if ($acc_tocken != '') {
            delete_option('ffwd_limit_notice');
        }
        $ffwd_limit_notice = get_option('ffwd_limit_notice');
        require_once 'framework/WDFacebookFeed.php';
        require_once(WD_FFWD_DIR . '/admin/controllers/FFWDController' . (($page == 'FFWDShortcode') ? $page : ucfirst(strtolower($page))) . '.php');
        $controller_class = 'FFWDController' . ucfirst(strtolower($page));
        $controller = new $controller_class();
        $controller->execute();
    }
}

function FFWD_licensing_page() {
  $controller_class = 'FFWDControllerLicensing_ffwd';
  require_once( WD_FFWD_DIR . '/admin/controllers/' . $controller_class . '.php' );
  $controller = new $controller_class();
  $controller->execute();
}

function ffwd_ajax_frontend() {
  require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
  $page = WDW_FFWD_Library::get('action');
  if ( $page != '' && $page == 'PopupBox' ) {
    require_once(WD_FFWD_DIR . '/frontend/controllers/FFWDController' . ucfirst($page) . '.php');
    $controller_class = 'FFWDController' . ucfirst($page);
    $controller = new $controller_class();
    $controller->execute();
  }
}

add_action('wp_ajax_PopupBox', 'ffwd_ajax_frontend');
add_action('wp_ajax_nopriv_PopupBox', 'ffwd_ajax_frontend');
// For facebook feed
add_action('wp_ajax_nopriv_save_facebook_feed', 'ffwd_ajax');
add_action('wp_ajax_save_facebook_feed', 'ffwd_ajax');
// For check app
add_action('wp_ajax_nopriv_check_app', 'ffwd_ajax');
add_action('wp_ajax_check_app', 'ffwd_ajax');
// For drop objects
add_action('wp_ajax_nopriv_dropp_objects', 'ffwd_ajax');
add_action('wp_ajax_dropp_objects', 'ffwd_ajax');

/* Insert FB data media data to DB */
function ffwd_set_cache_data()
{
  require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
  $content_type = WDW_FFWD_Library::get('content_type', 'timeline');

  require_once(WD_FFWD_DIR . '/framework/WDFacebookFeed.php');
  if ( $content_type === 'timeline' ) {
    WDFacebookFeed::set_timeline_cache_data();
  } else {
    WDFacebookFeed::set_specific_cache_data();
  }
}
add_action('wp_ajax_set_cache_data', 'ffwd_set_cache_data');
add_action('wp_ajax_nopriv_set_cache_data', 'ffwd_set_cache_data');

/* Update FB media data */
function ffwd_update_cache_data()
{
  require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
  $content_type = WDW_FFWD_Library::get('content_type', 'timeline');
  require_once(WD_FFWD_DIR . '/framework/WDFacebookFeed.php');

  if ( $content_type === 'timeline' ) {
    WDFacebookFeed::update_timeline_cache_data();
  } else {
    WDFacebookFeed::set_specific_cache_data();
  }
}
add_action('wp_ajax_update_cache_data', 'ffwd_update_cache_data');
add_action('wp_ajax_nopriv_update_cache_data', 'ffwd_update_cache_data');


// For reset_cache
add_action('wp_ajax_ffwd_reset_cache', 'ffwd_reset_cache');
function ffwd_reset_cache(){
  if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field($_POST['nonce']), WD_FFWD_URL . '_ajax_nonce' )) {
    delete_option("ffwd_autoupdate_time");
    require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
    WDW_FFWD_Library::remove_feed_data();
    echo json_encode(array("success"=>true));
    die;
  }
  echo json_encode(array("success"=>false)); die;
}

function ffwd_ajax()
{
    if (function_exists('current_user_can')) {
        if (!current_user_can('manage_options')) {
            die('Access Denied');
        }
    } else {
        die('Access Denied');
    }

    require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
    $page = WDW_FFWD_Library::get('action');
    $nonce = ($page == 'save_facebook_feed' || $page == 'dropp_objects') ? 'info_ffwd' : (($page == 'check_app') ? 'options_ffwd' : $page);
    if (($page != 'FFWDShortcode') && !WDW_FFWD_Library::verify_nonce($nonce)) {
        die('Sorry, your nonce did not verify.');
    }

    if ($page == 'FFWDShortcode') {
        require_once(WD_FFWD_DIR . '/admin/controllers/FFWDController' . ucfirst($page) . '.php');
        $controller_class = 'FFWDController' . ucfirst($page);
        $controller = new $controller_class();
        $controller->execute();
    }
    else if ($page == 'check_app' || $page == 'save_facebook_feed' || $page == 'dropp_objects') {
        require_once(WD_FFWD_DIR . '/framework/WDFacebookFeed.php');
        WDFacebookFeed::execute();
    }
}

function ffwd_shortcode($params) {
  global $wpdb;
  require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
  $check_fb_feed = $wpdb->get_var($wpdb->prepare("SELECT id FROM " . $wpdb->prefix . "wd_fb_info WHERE id='%d'", $params['id']));
  require_once(WD_FFWD_DIR . '/framework/WDFacebookFeed.php');
  if ( !$check_fb_feed ) {
    echo WDW_FFWD_Library::message(__('Feed Doesn\'t exists', 'bwg'), 'error');
    return;
  }
  $params['fb_id'] = $params['id'];
  ob_start();
  ffwd_front_end($params);

  return str_replace(array("\r\n", "\n", "\r"), '', ob_get_clean());
}
add_shortcode('WD_FB', 'ffwd_shortcode');

$ffwd = 0;
function ffwd_front_end($params) {
    /* Enqueue css/js in frontend */
    add_action('wp_enqueue_scripts', 'ffwd_front_end_scripts');
    global $ffwd;
    global $wpdb;
    require_once(WD_FFWD_DIR . '/frontend/controllers/FFWDControllerMain.php');
    $fb_view_type = $wpdb->get_var($wpdb->prepare("SELECT fb_view_type FROM " . $wpdb->prefix . "wd_fb_info WHERE id='%s'", $params['fb_id']));
    $controller = new FFWDControllerMain($params, 1, $ffwd, ucfirst($fb_view_type));
    $ffwd++;
    return;
}

/* Function called to print feed from js after update */
function ffwd_ajax_front_end() {
  require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
  $params['fb_id'] = WDW_FFWD_Library::get('fb_id', 0);
  if( $params['fb_id'] == 0 ) {
    echo '';
    die;
  }
  /* Enqueue css/js in frontend */
  add_action('wp_enqueue_scripts', 'ffwd_front_end_scripts');
  global $ffwd;
  global $wpdb;
  require_once(WD_FFWD_DIR . '/frontend/controllers/FFWDControllerMain.php');
  $fb_view_type = $wpdb->get_var($wpdb->prepare("SELECT fb_view_type FROM " . $wpdb->prefix . "wd_fb_info WHERE id='%s'", $params['fb_id']));
  $controller = new FFWDControllerMain($params, 1, $ffwd, ucfirst($fb_view_type));
  $ffwd++;
  return;
}
add_action('wp_ajax_ffwd_ajax_front_end', 'ffwd_ajax_front_end');
add_action('wp_ajax_nopriv_ffwd_ajax_front_end', 'ffwd_ajax_front_end');


// Add the Facebook Feed by 10Web button.
function ffwd_add_button($buttons)
{
    array_push($buttons, "wd_fb_mce");

    return $buttons;
}

// Register Facebook Feed by 10Web button.
function ffwd_register($plugin_array)
{
    if(is_admin()) {
      $url = WD_FFWD_URL . '/js/ffwd_editor_button.js';
      $plugin_array["wd_fb_mce"] = $url;
    }

    return $plugin_array;
}

function ffwd_admin_ajax()
{
    $query_url = wp_nonce_url(admin_url('admin-ajax.php'), '', 'ffwd_nonce');
    ?>
    <script>
        var ffwd_admin_ajax = '<?php echo add_query_arg(array('action' => 'FFWDShortcode'), admin_url('admin-ajax.php')); ?>';
        var ffwd_plugin_url = '<?php echo WD_FFWD_URL; ?>';
        var ajax_url = '<?php echo $query_url; ?>';
    </script>
    <?php
}



add_filter('tw_get_plugin_blocks', 'ffwd_register_plugin_block');
function ffwd_register_plugin_block($blocks) {
  $plugin_name =  __('Facebook Feed by 10Web', WD_FB_PREFIX);
  $icon_url = WD_FFWD_URL . '/images/wt-gb/ffwd_logo_editor.svg';
  $icon_svg = WD_FFWD_URL . '/images/wt-gb/icon.svg';
  global $wpdb;
  $rows = $wpdb->get_results('SELECT `id`, `name` FROM `' . $wpdb->prefix . 'wd_fb_info` ORDER BY `name` ASC');
  $data = array();
  $data['shortcode_prefix'] = 'WD_FB';
  $data['inputs'][] = array(
    'type' => 'select',
    'id' => 'WD_FB_id',
    'name' => 'WD_FB_id',
    'shortcode_attibute_name' => 'id',
    'options'  => $rows,
  );
  $data = json_encode($data);

  $blocks['tw/'.WD_FB_PREFIX] = array(
    'title' => __('Facebook Feed by 10Web', WD_FB_PREFIX),
    'titleSelect' => sprintf(__('Select %s', WD_FB_PREFIX), $plugin_name),
    'iconUrl' => $icon_url,
    'iconSvg' => array('width' => 30, 'height' => 30, 'src' => $icon_svg),
    'isPopup' => false,
    'data' => $data,
  );
  return $blocks;
}

// Enqueue block editor assets for Gutenberg.
add_filter('tw_get_block_editor_assets', 'ffwd_register_block_editor_assets');
add_action( 'enqueue_block_editor_assets', 'ffwd_enqueue_block_editor_assets');

function ffwd_register_block_editor_assets($assets) {
	$version = '2.0.3';
	$js_path = WD_FFWD_URL . '/js/tw-gb/block.js';
	$css_path = WD_FFWD_URL . '/css/tw-gb/block.css';
	if (!isset($assets['version']) || version_compare($assets['version'], $version) === -1) {
	  $assets['version'] = $version;
	  $assets['js_path'] = $js_path;
	  $assets['css_path'] = $css_path;
	}
	return $assets;
}

/**
* Enqueue block editor assets.
*/
function ffwd_enqueue_block_editor_assets() {

	

	// Remove previously registered or enqueued versions
	$wp_scripts = wp_scripts();
	foreach ($wp_scripts->registered as $key => $value) {
	  // Check for an older versions with prefix.
	  if (strpos($key, 'tw-gb-block') > 0) {
		wp_deregister_script( $key );
		wp_deregister_style( $key );
	  }
	}
    // Get plugin blocks from all 10Web plugins.
    $blocks = apply_filters('tw_get_plugin_blocks', array());
	// Get the last version from all 10Web plugins.
	$assets = apply_filters('tw_get_block_editor_assets', array());
	// Not performing unregister or unenqueue as in old versions all are with prefixes.
	wp_enqueue_script('tw-gb-block', $assets['js_path'], array( 'wp-blocks', 'wp-element' ), $assets['version']);
	wp_localize_script('tw-gb-block', 'tw_obj_translate', array(
	  'nothing_selected' => __('Nothing selected.', WD_FB_PREFIX),
	  'empty_item' => __('- Select -', WD_FB_PREFIX),
      'blocks' => json_encode($blocks)
	));
	wp_enqueue_style('tw-gb-block', $assets['css_path'], array( 'wp-edit-blocks' ), $assets['version']);
}



add_action('admin_head', 'ffwd_admin_ajax');

// Add the Facebook Feed by 10Web button to editor.
add_action('wp_ajax_FFWDShortcode', 'ffwd_ajax');
add_filter('mce_external_plugins', 'ffwd_register');
add_filter('mce_buttons', 'ffwd_add_button', 0);

// Activate plugin.
function ffwd_activate() {
  global $wpdb;
  $current_time=current_time('timestamp');
  $autoupdate_interval = 60;
  update_option('ffwd_autoupdate_time',$autoupdate_interval*60+$current_time);
  delete_transient('ffwd_update_check');
  $charset_collate = $wpdb->get_charset_collate();
  $wd_fb_shortcode = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wd_fb_shortcode` (
    `id` bigint(20) NOT NULL,
    `tagtext` mediumtext NOT NULL,
    PRIMARY KEY (`id`)
  ) " . $charset_collate . ";";
  $wpdb->query($wd_fb_shortcode);

  $wd_fb_info = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wd_fb_info` (
    `id` int(10) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `page_access_token` text NOT NULL,
    `type` varchar(10) NOT NULL,
    `content_type` varchar(15) NOT NULL,
    `content` varchar(256) NOT NULL,
    `content_url` varchar(512) NOT NULL,
    `timeline_type` varchar(16) NOT NULL,
    `from` varchar(32) NOT NULL,
    `limit` int(11) NOT NULL,
    `app_id` varchar(128) NOT NULL,
    `app_secret` varchar(256) NOT NULL,
    `exist_access` tinyint(1) NOT NULL,
    `access_token` varchar(256) NOT NULL,
    `order` bigint(20) DEFAULT NULL,
    `published` tinyint(1) NOT NULL,
    `update_mode` varchar(16) NOT NULL,
    `fb_view_type` varchar(25) NOT NULL,
    `theme` int(11) DEFAULT NULL,
    `masonry_hor_ver` varchar(255) DEFAULT NULL,
    `image_max_columns` int(11) DEFAULT NULL,
    `thumb_width` int(11) DEFAULT NULL,
    `thumb_height` int(11) DEFAULT NULL,
    `thumb_comments` int(11) DEFAULT NULL,
    `thumb_likes` int(11) DEFAULT NULL,
    `thumb_name` int(11) DEFAULT NULL,
    `blog_style_width` int(11) DEFAULT NULL,
    `blog_style_height` varchar(15) DEFAULT NULL,
    `blog_style_view_type` int(11) DEFAULT NULL,
    `blog_style_comments` int(11) DEFAULT NULL,
    `blog_style_likes` int(11) DEFAULT NULL,
    `blog_style_message_desc` int(11) DEFAULT NULL,
    `blog_style_shares` int(11) DEFAULT NULL,
    `blog_style_shares_butt` int(11) DEFAULT NULL,
    `blog_style_facebook` int(11) DEFAULT NULL,
    `blog_style_twitter` int(11) DEFAULT NULL,
    `blog_style_google` int(11) DEFAULT NULL,
    `blog_style_author` int(11) DEFAULT NULL,
    `blog_style_name` int(11) DEFAULT NULL,
    `blog_style_place_name` int(11) DEFAULT NULL,
    `fb_name` int(11) DEFAULT NULL,
    `fb_plugin` int(11) DEFAULT NULL,
    `album_max_columns` int(11) DEFAULT NULL,
    `album_title` varchar(15) DEFAULT NULL,
    `album_thumb_width` int(11) DEFAULT NULL,
    `album_thumb_height` int(11) DEFAULT NULL,
    `album_image_max_columns` int(11) DEFAULT NULL,
    `album_image_thumb_width` int(11) DEFAULT NULL,
    `album_image_thumb_height` int(11) DEFAULT NULL,
    `pagination_type` int(11) DEFAULT NULL,
    `objects_per_page` int(11) DEFAULT NULL,
    `popup_fullscreen` int(11) DEFAULT NULL,
    `popup_width` int(11) NOT NULL,
    `popup_height` int(11) DEFAULT NULL,
    `popup_effect` varchar(255) DEFAULT NULL,
    `popup_autoplay` int(11) DEFAULT NULL,
    `open_commentbox` int(11) DEFAULT NULL,
    `popup_interval` int(11) DEFAULT NULL,
    `popup_enable_filmstrip` int(11) DEFAULT NULL,
    `popup_filmstrip_height` int(11) DEFAULT NULL,
    `popup_comments` int(11) DEFAULT NULL,
    `popup_likes` int(11) DEFAULT NULL,
    `popup_shares` int(11) DEFAULT NULL,
    `popup_author` int(11) DEFAULT NULL,
    `popup_name` int(11) DEFAULT NULL,
    `popup_place_name` int(11) DEFAULT NULL,
    `popup_enable_ctrl_btn` int(11) DEFAULT NULL,
    `popup_enable_fullscreen` int(11) DEFAULT NULL,
    `popup_enable_info_btn` int(11) DEFAULT NULL,
    `popup_message_desc` int(11) DEFAULT NULL,
    `popup_enable_facebook` int(11) DEFAULT NULL,
    `popup_enable_twitter` int(11) DEFAULT NULL,
    `popup_enable_google` int(11) DEFAULT NULL,
    `view_on_fb` tinyint(1) NOT NULL,
    `post_text_length` bigint(20) NOT NULL,
    `event_street` tinyint(1) NOT NULL,
    `event_city` tinyint(1) NOT NULL,
    `event_country` tinyint(1) NOT NULL,
    `event_zip` tinyint(1) NOT NULL,
    `event_map` tinyint(1) NOT NULL,
    `event_date` tinyint(1) NOT NULL,
    `event_desp_length` bigint(20) NOT NULL,
    `comments_replies` tinyint(1) NOT NULL,
    `comments_filter` varchar(32) NOT NULL,
    `comments_order` varchar(32) NOT NULL,
    `page_plugin_pos` varchar(8) NOT NULL,
    `page_plugin_fans` tinyint(1) NOT NULL,
    `page_plugin_cover` tinyint(1) NOT NULL,
    `page_plugin_header` tinyint(1) NOT NULL,
    `page_plugin_width` int(4) NOT NULL,
     `image_onclick_action` varchar(32) NOT NULL,
     `event_order` tinyint(4) NOT NULL,
    `upcoming_events` tinyint(4) NOT NULL,
    `fb_page_id` varchar(32) NOT NULL,
      PRIMARY KEY (`id`)
    ) " . $charset_collate . ";";

  $wpdb->query($wd_fb_info);
  $old_version = ffwd_get_version();

  if ( substr($old_version, 0, 1) === '1' ) {
    $FFWD_version_compare = version_compare($old_version, '1.1.0', '<=');
  }
  else {
    $FFWD_version_compare = version_compare($old_version, '5.1.0', '<=');
  }
  if ( $FFWD_version_compare ) {
    $wd_fb_info_collation = "ALTER TABLE `" . $wpdb->prefix . "wd_fb_info` ADD `fb_page_id` varchar(32) NOT NULL AFTER `upcoming_events`";
    $wpdb->query($wd_fb_info_collation);
  }
  if ( substr($old_version, 0, 1) === '1' ) {
    $FFWD_version_compare = version_compare($old_version, '1.0.37', '<=');
  }
  else {
    $FFWD_version_compare = version_compare($old_version, '5.0.37', '<=');
  }
  if ( $FFWD_version_compare ) {
    $wd_fb_info_collation = "ALTER TABLE `" . $wpdb->prefix . "wd_fb_info` 
    ADD `page_access_token` text NOT NULL AFTER `name`";
    $wpdb->query($wd_fb_info_collation);
  }

  //message-i , description , name encoding --> utf16_bin
  $wd_fb_data = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wd_fb_data` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `fb_id` int NOT NULL,
    `from` varchar(32) NOT NULL,
    `object_id` varchar(64) NOT NULL,
    `name` text  NOT NULL,
    `description` mediumtext  NOT NULL,
    `type` varchar(32) NOT NULL,
    `message` mediumtext  NOT NULL,
    `story` mediumtext NOT NULL,
    `place` mediumtext NOT NULL,
    `message_tags` mediumtext NOT NULL,
    `with_tags` mediumtext NOT NULL,
    `story_tags` mediumtext NOT NULL,
    `status_type` mediumtext NOT NULL,
    `link` mediumtext NOT NULL,
    `source` mediumtext NOT NULL,
    `thumb_url` varchar(512) NOT NULL,
    `main_url` varchar(512) NOT NULL,
    `width` varchar(32) NOT NULL,
    `height` varchar(32) NOT NULL,
    `created_time` varchar(64) NOT NULL,
    `updated_time` varchar(64) NOT NULL,
    `created_time_number` bigint(255) NOT NULL,
    `reactions` text NOT NULL,
    `comments` text NOT NULL,
    `shares` text NOT NULL,
    `attachments` text NOT NULL,
    `who_post` text NOT NULL,
    PRIMARY KEY (`id`)
    ) " . $charset_collate . ";";
  $wpdb->query($wd_fb_data);

  $wd_fb_data_collation = "ALTER TABLE `" . $wpdb->prefix . "wd_fb_data` 
    MODIFY `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
    MODIFY `description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
    MODIFY `message` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin
    ";
  $wpdb->query($wd_fb_data_collation);

  $wd_fb_option = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wd_fb_option` (
                       `id` bigint(20) NOT NULL,
                       `autoupdate_interval` int(4) NOT NULL,
                       `app_id` varchar(255) NOT NULL,
                       `app_secret` varchar(255) NOT NULL,
                       `access_token` varchar(255) NOT NULL,
                       `date_timezone` varchar(64) NOT NULL,
                       `post_date_format` varchar(64) NOT NULL,
                       `event_date_format` varchar(64) NOT NULL
                       ) " . $charset_collate . ";";
    $wpdb->query($wd_fb_option);

  $wd_fb_theme = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wd_fb_theme` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `params` longtext,
      `default_theme` tinyint(1) NOT NULL,
      PRIMARY KEY (`id`)
      ) " . $charset_collate . ";";
  $wpdb->query($wd_fb_theme);

  $exists_default = $wpdb->get_var('SELECT count(id) FROM ' . $wpdb->prefix . 'wd_fb_option');
  if ( !$exists_default ) {
    $save = $wpdb->insert($wpdb->prefix . 'wd_fb_option', array(
      'id' => 1,
      'autoupdate_interval' => 90,
      'app_id' => '',
      'date_timezone' => '',
      'access_token' => '',
      'post_date_format' => 'ago',
      'event_date_format' => 'F j, Y, g:i a',
    ));
  }
  $exists_default = $wpdb->get_var('SELECT count(id) FROM ' . $wpdb->prefix . 'wd_fb_theme');
  if ( !$exists_default ) {
    $wpdb->insert($wpdb->prefix . 'wd_fb_theme', array(
      'name' => 'Theme 1',
      'default_theme' => 1,
      'params' => '{"thumb_margin":"10","thumb_padding":"2","thumb_border_radius":"0","thumb_border_width":"1","thumb_border_style":"none","thumb_border_color":"000000","thumb_bg_color":"FFFFFF","thumbs_bg_color":"FFFFFF","thumb_bg_transparent":"100","thumb_box_shadow":"0px 0px 0px #000000","thumb_transparent":"100","thumb_align":"center","thumb_hover_effect":"scale","thumb_hover_effect_value":"2deg","thumb_transition":"1","thumb_title_font_color":"797979","thumb_title_font_style":"inherit","thumb_title_pos":"bottom","thumb_title_font_size":"14","thumb_title_font_weight":"normal","thumb_title_margin":"5","thumb_title_shadow":"","thumb_like_comm_pos":"bottom","thumb_like_comm_font_size":"14","thumb_like_comm_font_color":"FFFFFF","thumb_like_comm_font_style":"inherit","thumb_like_comm_font_weight":"normal","thumb_like_comm_shadow":"0px 0px 1px #000000","masonry_thumb_padding":"10","masonry_thumb_border_radius":"2px","masonry_thumb_border_width":"1","masonry_thumb_border_style":"solid","masonry_thumb_border_color":"FFFFFF","masonry_thumbs_bg_color":"FFFFFF","masonry_thumb_bg_transparent":"100","masonry_thumb_transparent":"100","masonry_thumb_align":"center","masonry_thumb_hover_effect":"none","masonry_thumb_hover_effect_value":"1.1","masonry_thumb_transition":"1","masonry_description_font_size":"14","masonry_description_color":"A3A3A3","masonry_description_font_style":"inherit","masonry_like_comm_pos":"bottom","masonry_like_comm_font_size":"14","masonry_like_comm_font_color":"FFFFFF","masonry_like_comm_font_style":"inherit","masonry_like_comm_font_weight":"normal","masonry_like_comm_shadow":"0px 0px 1px #000000","blog_style_align":"center","blog_style_bg_color":"FFFFFF","blog_style_fd_name_bg_color":"FFFFFF","blog_style_fd_name_align":"left","blog_style_fd_name_padding":"10","blog_style_fd_name_color":"1C1C1C","blog_style_fd_name_size":"24","blog_style_fd_name_font_weight":"normal","blog_style_fd_icon":"","blog_style_fd_icon_color":"","blog_style_fd_icon_size":"","blog_style_transparent":"100","blog_style_obj_img_align":"left","blog_style_margin":"16","blog_style_box_shadow":"","blog_style_border_width":"1","blog_style_border_style":"solid","blog_style_border_color":"EBEBEB","blog_style_border_type":"top","blog_style_border_radius":"","blog_style_obj_icons_color":"gray","blog_style_obj_date_pos":"after","blog_style_obj_font_family":"inherit","blog_style_obj_info_bg_color":"FFFFFF","blog_style_page_name_color":"1C1C1C","blog_style_obj_page_name_size":"20","blog_style_obj_page_name_font_weight":"normal","blog_style_obj_story_color":"1C1C1C","blog_style_obj_story_size":"16","blog_style_obj_story_font_weight":"normal","blog_style_obj_place_color":"1C1C1C","blog_style_obj_place_size":"14","blog_style_obj_place_font_weight":"normal","blog_style_obj_name_color":"1C1C1C","blog_style_obj_name_size":"18","blog_style_obj_name_font_weight":"bold","blog_style_obj_message_color":"1C1C1C","blog_style_obj_message_size":"16","blog_style_obj_message_font_weight":"normal","blog_style_obj_hashtags_color":"000000","blog_style_obj_hashtags_size":"12","blog_style_obj_hashtags_font_weight":"normal","blog_style_obj_likes_social_bg_color":"FFFFFF","blog_style_obj_likes_social_color":"1C1C1C","blog_style_obj_likes_social_size":"14","blog_style_obj_likes_social_font_weight":"normal","blog_style_obj_comments_bg_color":"FFFFFF","blog_style_obj_comments_color":"000000","blog_style_obj_comments_font_family":"inherit","blog_style_obj_comments_font_size":"14","blog_style_obj_users_font_color":"000000","blog_style_obj_comments_social_font_weight":"normal","blog_style_obj_comment_border_width":"10","blog_style_obj_comment_border_style":"solid","blog_style_obj_comment_border_color":"FCFCFC","blog_style_obj_comment_border_type":"top","blog_style_evt_str_color":"1C1C1C","blog_style_evt_str_size":"16","blog_style_evt_str_font_weight":"normal","blog_style_evt_ctzpcn_color":"CFCFCF","blog_style_evt_ctzpcn_size":"14","blog_style_evt_ctzpcn_font_weight":"normal","blog_style_evt_map_color":"1C1C1C","blog_style_evt_map_size":"14","blog_style_evt_map_font_weight":"normal","blog_style_evt_date_color":"CFCFCF","blog_style_evt_date_size":"14","blog_style_evt_date_font_weight":"normal","blog_style_evt_info_font_family":"inherit","album_compact_back_font_color":"000000","album_compact_back_font_style":"inherit","album_compact_back_font_size":"16","album_compact_back_font_weight":"bold","album_compact_back_padding":"0","album_compact_title_font_color":"797979","album_compact_title_font_style":"inherit","album_compact_thumb_title_pos":"bottom","album_compact_title_font_size":"13","album_compact_title_font_weight":"normal","album_compact_title_margin":"2px","album_compact_title_shadow":"0px 0px 0px #888888","album_compact_thumb_margin":"4","album_compact_thumb_padding":"0","album_compact_thumb_border_radius":"0","album_compact_thumb_border_width":"0","album_compact_thumb_border_style":"none","album_compact_thumb_border_color":"CCCCCC","album_compact_thumb_bg_color":"FFFFFF","album_compact_thumbs_bg_color":"FFFFFF","album_compact_thumb_bg_transparent":"0","album_compact_thumb_box_shadow":"0px 0px 0px #888888","album_compact_thumb_transparent":"100","album_compact_thumb_align":"center","album_compact_thumb_hover_effect":"none","album_compact_thumb_hover_effect_value":"1.1","album_compact_thumb_transition":"0","lightbox_overlay_bg_color":"000000","lightbox_overlay_bg_transparent":"60","lightbox_bg_color":"1B1B1B","lightbox_ctrl_btn_pos":"bottom","lightbox_ctrl_btn_align":"center","lightbox_ctrl_btn_height":"16","lightbox_ctrl_btn_margin_top":"24","lightbox_ctrl_btn_margin_left":"10","lightbox_ctrl_btn_transparent":"100","lightbox_ctrl_btn_color":"ffffff","lightbox_toggle_btn_height":"14","lightbox_toggle_btn_width":"100","lightbox_ctrl_cont_bg_color":"0A0A0A","lightbox_ctrl_cont_transparent":"100","lightbox_ctrl_cont_border_radius":"4","lightbox_close_btn_transparent":"100","lightbox_close_btn_bg_color":"000000","lightbox_close_btn_border_width":"14","lightbox_close_btn_border_radius":"24px","lightbox_close_btn_border_style":"none","lightbox_close_btn_border_color":"FFFFFF","lightbox_close_btn_box_shadow":"0","lightbox_close_btn_color":"","lightbox_close_btn_size":"12","lightbox_close_btn_width":"24","lightbox_close_btn_height":"24","lightbox_close_btn_top":"0","lightbox_close_btn_right":"-30","lightbox_close_btn_full_color":"","lightbox_rl_btn_bg_color":"000000","lightbox_rl_btn_transparent":"70","lightbox_rl_btn_border_radius":"20px","lightbox_rl_btn_border_width":"0","lightbox_rl_btn_border_style":"none","lightbox_rl_btn_border_color":"FFFFFF","lightbox_rl_btn_box_shadow":"","lightbox_rl_btn_color":"","lightbox_rl_btn_height":"36","lightbox_rl_btn_width":"36","lightbox_rl_btn_size":"16","lightbox_close_rl_btn_hover_color":"","lightbox_obj_pos":"right","lightbox_obj_width":"350","lightbox_obj_icons_color":"gray","lightbox_obj_date_pos":"after","lightbox_obj_font_family":"inherit","lightbox_obj_info_bg_color":"FFFFFF","lightbox_page_name_color":"4B4B4B","lightbox_obj_page_name_size":"18","lightbox_obj_page_name_font_weight":"bold","lightbox_obj_story_color":"4B4B4B","lightbox_obj_story_size":"16","lightbox_obj_story_font_weight":"normal","lightbox_obj_place_color":"4B4B4B","lightbox_obj_place_size":"14","lightbox_obj_place_font_weight":"normal","lightbox_obj_name_color":"4B4B4B","lightbox_obj_name_size":"14","lightbox_obj_name_font_weight":"bold","lightbox_obj_message_color":"000000","lightbox_obj_message_size":"16","lightbox_obj_message_font_weight":"normal","lightbox_obj_hashtags_color":"000000","lightbox_obj_hashtags_size":"12","lightbox_obj_hashtags_font_weight":"normal","lightbox_obj_likes_social_bg_color":"F4F5F7","lightbox_obj_likes_social_color":"000000","lightbox_obj_likes_social_size":"12","lightbox_obj_likes_social_font_weight":"normal","lightbox_obj_comments_bg_color":"FFFFFF","lightbox_obj_comments_color":"4A4A4A","lightbox_obj_comments_font_family":"inherit","lightbox_obj_comments_font_size":"16","lightbox_obj_users_font_color":"4B4B4B","lightbox_obj_comments_social_font_weight":"normal","lightbox_obj_comment_border_width":"1","lightbox_obj_comment_border_style":"none","lightbox_obj_comment_border_color":"000000","lightbox_obj_comment_border_type":"top","lightbox_filmstrip_pos":"bottom","lightbox_filmstrip_rl_bg_color":"3B3B3B","lightbox_filmstrip_rl_btn_size":"20","lightbox_filmstrip_rl_btn_color":"ffffff","lightbox_filmstrip_thumb_margin":"0 1px","lightbox_filmstrip_thumb_border_width":"1","lightbox_filmstrip_thumb_border_style":"solid","lightbox_filmstrip_thumb_border_color":"000000","lightbox_filmstrip_thumb_border_radius":"0","lightbox_filmstrip_thumb_deactive_transparent":"80","lightbox_filmstrip_thumb_active_border_width":"0","lightbox_filmstrip_thumb_active_border_color":"FFFFFF","lightbox_rl_btn_style":"","lightbox_evt_str_color":"000000","lightbox_evt_str_size":"16","lightbox_evt_str_font_weight":"normal","lightbox_evt_ctzpcn_color":"000000","lightbox_evt_ctzpcn_size":"14","lightbox_evt_ctzpcn_font_weight":"normal","lightbox_evt_map_color":"000000","lightbox_evt_map_size":"14","lightbox_evt_map_font_weight":"normal","lightbox_evt_date_color":"000000","lightbox_evt_date_size":"14","lightbox_evt_date_font_weight":"normal","lightbox_evt_info_font_family":"inherit","page_nav_position":"bottom","page_nav_align":"center","page_nav_number":"0","page_nav_font_size":"12","page_nav_font_style":"inherit","page_nav_font_color":"666666","page_nav_font_weight":"bold","page_nav_border_width":"1","page_nav_border_style":"solid","page_nav_border_color":"E3E3E3","page_nav_border_radius":"0","page_nav_margin":"0","page_nav_padding":"3px 6px","page_nav_button_bg_color":"FFFFFF","page_nav_button_bg_transparent":"100","page_nav_box_shadow":"0","page_nav_button_transition":"1","page_nav_button_text":"0","lightbox_obj_icons_color_likes_comments_count":"white"}',
    ));
    $wpdb->insert($wpdb->prefix . 'wd_fb_theme', array(
      'name' => 'Theme 2',
      'default_theme' => 0,
      'params' => '{"thumb_margin":"10","thumb_padding":"2","thumb_border_radius":"0px","thumb_border_width":"0","thumb_border_style":"none","thumb_border_color":"000000","thumb_bg_color":"BBCED4","thumbs_bg_color":"FFFFFF","thumb_bg_transparent":"100","thumb_box_shadow":"0px 0px 0px #000000","thumb_transparent":"100","thumb_align":"center","thumb_hover_effect":"rotate","thumb_hover_effect_value":"1deg","thumb_transition":"1","thumb_title_font_color":"1F1F1F","thumb_title_font_style":"inherit","thumb_title_pos":"bottom","thumb_title_font_size":"14","thumb_title_font_weight":"normal","thumb_title_margin":"10","thumb_title_shadow":"","thumb_like_comm_pos":"bottom","thumb_like_comm_font_size":"14","thumb_like_comm_font_color":"FFFFFF","thumb_like_comm_font_style":"inherit","thumb_like_comm_font_weight":"normal","thumb_like_comm_shadow":"0px 0px 1px #000000","masonry_thumb_padding":"4","masonry_thumb_border_radius":"2px","masonry_thumb_border_width":"1","masonry_thumb_border_style":"solid","masonry_thumb_border_color":"FFFFFF","masonry_thumbs_bg_color":"BBCED2","masonry_thumb_bg_transparent":"100","masonry_thumb_transparent":"100","masonry_thumb_align":"center","masonry_thumb_hover_effect":"scale","masonry_thumb_hover_effect_value":"1.1","masonry_thumb_transition":"1","masonry_description_font_size":"14","masonry_description_color":"1F1F1F","masonry_description_font_style":"inherit","masonry_like_comm_pos":"bottom","masonry_like_comm_font_size":"14","masonry_like_comm_font_color":"FFFFFF","masonry_like_comm_font_style":"inherit","masonry_like_comm_font_weight":"normal","masonry_like_comm_shadow":"0px 0px 1px #000000","blog_style_align":"left","blog_style_bg_color":"FFFFFF","blog_style_fd_name_bg_color":"000000","blog_style_fd_name_align":"center","blog_style_fd_name_padding":"10","blog_style_fd_name_color":"FFFFFF","blog_style_fd_name_size":"15","blog_style_fd_name_font_weight":"normal","blog_style_fd_icon":"","blog_style_fd_icon_color":"","blog_style_fd_icon_size":"","blog_style_transparent":"100","blog_style_obj_img_align":"center","blog_style_margin":"10","blog_style_box_shadow":"","blog_style_border_width":"1","blog_style_border_style":"solid","blog_style_border_color":"C9C9C9","blog_style_border_type":"top","blog_style_border_radius":"","blog_style_obj_icons_color":"gray","blog_style_obj_date_pos":"after","blog_style_obj_font_family":"inherit","blog_style_obj_info_bg_color":"FFFFFF","blog_style_page_name_color":"000000","blog_style_obj_page_name_size":"13","blog_style_obj_page_name_font_weight":"bold","blog_style_obj_story_color":"000000","blog_style_obj_story_size":"14","blog_style_obj_story_font_weight":"normal","blog_style_obj_place_color":"000000","blog_style_obj_place_size":"13","blog_style_obj_place_font_weight":"normal","blog_style_obj_name_color":"000000","blog_style_obj_name_size":"13","blog_style_obj_name_font_weight":"bold","blog_style_obj_message_color":"000000","blog_style_obj_message_size":"14","blog_style_obj_message_font_weight":"normal","blog_style_obj_hashtags_color":"000000","blog_style_obj_hashtags_size":"12","blog_style_obj_hashtags_font_weight":"normal","blog_style_obj_likes_social_bg_color":"BBCED4","blog_style_obj_likes_social_color":"656565","blog_style_obj_likes_social_size":"14","blog_style_obj_likes_social_font_weight":"normal","blog_style_obj_comments_bg_color":"FFFFFF","blog_style_obj_comments_color":"000000","blog_style_obj_comments_font_family":"inherit","blog_style_obj_comments_font_size":"14","blog_style_obj_users_font_color":"000000","blog_style_obj_comments_social_font_weight":"normal","blog_style_obj_comment_border_width":"1","blog_style_obj_comment_border_style":"solid","blog_style_obj_comment_border_color":"C9C9C9","blog_style_obj_comment_border_type":"top","blog_style_evt_str_color":"000000","blog_style_evt_str_size":"14","blog_style_evt_str_font_weight":"normal","blog_style_evt_ctzpcn_color":"000000","blog_style_evt_ctzpcn_size":"14","blog_style_evt_ctzpcn_font_weight":"normal","blog_style_evt_map_color":"000000","blog_style_evt_map_size":"14","blog_style_evt_map_font_weight":"normal","blog_style_evt_date_color":"000000","blog_style_evt_date_size":"14","blog_style_evt_date_font_weight":"normal","blog_style_evt_info_font_family":"inherit","album_compact_back_font_color":"000000","album_compact_back_font_style":"inherit","album_compact_back_font_size":"16","album_compact_back_font_weight":"bold","album_compact_back_padding":"0","album_compact_title_font_color":"797979","album_compact_title_font_style":"inherit","album_compact_thumb_title_pos":"bottom","album_compact_title_font_size":"13","album_compact_title_font_weight":"normal","album_compact_title_margin":"2px","album_compact_title_shadow":"0px 0px 0px #888888","album_compact_thumb_margin":"0","album_compact_thumb_padding":"0","album_compact_thumb_border_radius":"0","album_compact_thumb_border_width":"0","album_compact_thumb_border_style":"none","album_compact_thumb_border_color":"CCCCCC","album_compact_thumb_bg_color":"BBCED4","album_compact_thumbs_bg_color":"FFFFFF","album_compact_thumb_bg_transparent":"0","album_compact_thumb_box_shadow":"0px 0px 0px #888888","album_compact_thumb_transparent":"100","album_compact_thumb_align":"center","album_compact_thumb_hover_effect":"scale","album_compact_thumb_hover_effect_value":"1.1","album_compact_thumb_transition":"0","lightbox_overlay_bg_color":"000000","lightbox_overlay_bg_transparent":"70","lightbox_bg_color":"000000","lightbox_ctrl_btn_pos":"bottom","lightbox_ctrl_btn_align":"center","lightbox_ctrl_btn_height":"20","lightbox_ctrl_btn_margin_top":"10","lightbox_ctrl_btn_margin_left":"7","lightbox_ctrl_btn_transparent":"100","lightbox_ctrl_btn_color":"ffffff","lightbox_toggle_btn_height":"14","lightbox_toggle_btn_width":"100","lightbox_ctrl_cont_bg_color":"000000","lightbox_ctrl_cont_transparent":"65","lightbox_ctrl_cont_border_radius":"4","lightbox_close_btn_transparent":"100","lightbox_close_btn_bg_color":"000000","lightbox_close_btn_border_width":"2","lightbox_close_btn_border_radius":"16px","lightbox_close_btn_border_style":"none","lightbox_close_btn_border_color":"FFFFFF","lightbox_close_btn_box_shadow":"0","lightbox_close_btn_color":"","lightbox_close_btn_size":"10","lightbox_close_btn_width":"20","lightbox_close_btn_height":"20","lightbox_close_btn_top":"-10","lightbox_close_btn_right":"-10","lightbox_close_btn_full_color":"","lightbox_rl_btn_bg_color":"000000","lightbox_rl_btn_transparent":"80","lightbox_rl_btn_border_radius":"20px","lightbox_rl_btn_border_width":"0","lightbox_rl_btn_border_style":"none","lightbox_rl_btn_border_color":"FFFFFF","lightbox_rl_btn_box_shadow":"","lightbox_rl_btn_color":"","lightbox_rl_btn_height":"40","lightbox_rl_btn_width":"40","lightbox_rl_btn_size":"20","lightbox_close_rl_btn_hover_color":"","lightbox_obj_pos":"left","lightbox_obj_width":"350","lightbox_obj_icons_color":"gray","lightbox_obj_date_pos":"after","lightbox_obj_font_family":"inherit","lightbox_obj_info_bg_color":"E2E2E2","lightbox_page_name_color":"000000","lightbox_obj_page_name_size":"14","lightbox_obj_page_name_font_weight":"bold","lightbox_obj_story_color":"4B4B4B","lightbox_obj_story_size":"14","lightbox_obj_story_font_weight":"normal","lightbox_obj_place_color":"000000","lightbox_obj_place_size":"13","lightbox_obj_place_font_weight":"normal","lightbox_obj_name_color":"4B4B4B","lightbox_obj_name_size":"14","lightbox_obj_name_font_weight":"bold","lightbox_obj_message_color":"000000","lightbox_obj_message_size":"14","lightbox_obj_message_font_weight":"normal","lightbox_obj_hashtags_color":"000000","lightbox_obj_hashtags_size":"12","lightbox_obj_hashtags_font_weight":"normal","lightbox_obj_likes_social_bg_color":"BBCED4","lightbox_obj_likes_social_color":"FFFFFF","lightbox_obj_likes_social_size":"14","lightbox_obj_likes_social_font_weight":"normal","lightbox_obj_comments_bg_color":"EAEAEA","lightbox_obj_comments_color":"4A4A4A","lightbox_obj_comments_font_family":"inherit","lightbox_obj_comments_font_size":"14","lightbox_obj_users_font_color":"4B4B4B","lightbox_obj_comments_social_font_weight":"normal","lightbox_obj_comment_border_width":"1","lightbox_obj_comment_border_style":"solid","lightbox_obj_comment_border_color":"C9C9C9","lightbox_obj_comment_border_type":"top","lightbox_filmstrip_pos":"top","lightbox_filmstrip_rl_bg_color":"3B3B3B","lightbox_filmstrip_rl_btn_size":"20","lightbox_filmstrip_rl_btn_color":"ffffff","lightbox_filmstrip_thumb_margin":"0 1px","lightbox_filmstrip_thumb_border_width":"1","lightbox_filmstrip_thumb_border_style":"solid","lightbox_filmstrip_thumb_border_color":"000000","lightbox_filmstrip_thumb_border_radius":"0","lightbox_filmstrip_thumb_deactive_transparent":"80","lightbox_filmstrip_thumb_active_border_width":"0","lightbox_filmstrip_thumb_active_border_color":"FFFFFF","lightbox_rl_btn_style":"","lightbox_evt_str_color":"000000","lightbox_evt_str_size":"14","lightbox_evt_str_font_weight":"normal","lightbox_evt_ctzpcn_color":"000000","lightbox_evt_ctzpcn_size":"14","lightbox_evt_ctzpcn_font_weight":"normal","lightbox_evt_map_color":"000000","lightbox_evt_map_size":"14","lightbox_evt_map_font_weight":"normal","lightbox_evt_date_color":"000000","lightbox_evt_date_size":"14","lightbox_evt_date_font_weight":"normal","lightbox_evt_info_font_family":"inherit","page_nav_position":"bottom","page_nav_align":"center","page_nav_number":"0","page_nav_font_size":"12","page_nav_font_style":"inherit","page_nav_font_color":"666666","page_nav_font_weight":"bold","page_nav_border_width":"1","page_nav_border_style":"solid","page_nav_border_color":"E3E3E3","page_nav_border_radius":"0","page_nav_margin":"0","page_nav_padding":"3px 6px","page_nav_button_bg_color":"FFFFFF","page_nav_button_bg_transparent":"100","page_nav_box_shadow":"0","page_nav_button_transition":"1","page_nav_button_text":"0","lightbox_obj_icons_color_likes_comments_count":"white"}',
    ));
    $wpdb->insert($wpdb->prefix . 'wd_fb_theme', array(
      'name' => 'Theme 3',
      'default_theme' => 0,
      'params' => '{"thumb_margin":"10","thumb_padding":"2","thumb_border_radius":"2px","thumb_border_width":"1","thumb_border_style":"none","thumb_border_color":"000000","thumb_bg_color":"C3E0CE","thumbs_bg_color":"FFFFFF","thumb_bg_transparent":"100","thumb_box_shadow":"0px 0px 1px #000000","thumb_transparent":"100","thumb_align":"center","thumb_hover_effect":"rotate","thumb_hover_effect_value":"2deg","thumb_transition":"1","thumb_title_font_color":"191919","thumb_title_font_style":"inherit","thumb_title_pos":"bottom","thumb_title_font_size":"14","thumb_title_font_weight":"normal","thumb_title_margin":"10","thumb_title_shadow":"","thumb_like_comm_pos":"bottom","thumb_like_comm_font_size":"14","thumb_like_comm_font_color":"FFFFFF","thumb_like_comm_font_style":"inherit","thumb_like_comm_font_weight":"normal","thumb_like_comm_shadow":"0px 0px 1px #000000","masonry_thumb_padding":"4","masonry_thumb_border_radius":"2px","masonry_thumb_border_width":"1","masonry_thumb_border_style":"solid","masonry_thumb_border_color":"FFFFFF","masonry_thumbs_bg_color":"C3E0CE","masonry_thumb_bg_transparent":"100","masonry_thumb_transparent":"100","masonry_thumb_align":"center","masonry_thumb_hover_effect":"scale","masonry_thumb_hover_effect_value":"1.1","masonry_thumb_transition":"1","masonry_description_font_size":"14","masonry_description_color":"191919","masonry_description_font_style":"inherit","masonry_like_comm_pos":"bottom","masonry_like_comm_font_size":"14","masonry_like_comm_font_color":"FFFFFF","masonry_like_comm_font_style":"inherit","masonry_like_comm_font_weight":"normal","masonry_like_comm_shadow":"0px 0px 1px #000000","blog_style_align":"left","blog_style_bg_color":"FFFFFF","blog_style_fd_name_bg_color":"000000","blog_style_fd_name_align":"center","blog_style_fd_name_padding":"10","blog_style_fd_name_color":"FFFFFF","blog_style_fd_name_size":"15","blog_style_fd_name_font_weight":"normal","blog_style_fd_icon":"","blog_style_fd_icon_color":"","blog_style_fd_icon_size":"","blog_style_transparent":"100","blog_style_obj_img_align":"center","blog_style_margin":"10","blog_style_box_shadow":"","blog_style_border_width":"1","blog_style_border_style":"solid","blog_style_border_color":"C9C9C9","blog_style_border_type":"top","blog_style_border_radius":"","blog_style_obj_icons_color":"gray","blog_style_obj_date_pos":"after","blog_style_obj_font_family":"inherit","blog_style_obj_info_bg_color":"FFFFFF","blog_style_page_name_color":"000000","blog_style_obj_page_name_size":"13","blog_style_obj_page_name_font_weight":"bold","blog_style_obj_story_color":"000000","blog_style_obj_story_size":"14","blog_style_obj_story_font_weight":"normal","blog_style_obj_place_color":"000000","blog_style_obj_place_size":"13","blog_style_obj_place_font_weight":"normal","blog_style_obj_name_color":"000000","blog_style_obj_name_size":"13","blog_style_obj_name_font_weight":"bold","blog_style_obj_message_color":"000000","blog_style_obj_message_size":"14","blog_style_obj_message_font_weight":"normal","blog_style_obj_hashtags_color":"000000","blog_style_obj_hashtags_size":"12","blog_style_obj_hashtags_font_weight":"normal","blog_style_obj_likes_social_bg_color":"C3E0CE","blog_style_obj_likes_social_color":"656565","blog_style_obj_likes_social_size":"14","blog_style_obj_likes_social_font_weight":"normal","blog_style_obj_comments_bg_color":"FFFFFF","blog_style_obj_comments_color":"000000","blog_style_obj_comments_font_family":"inherit","blog_style_obj_comments_font_size":"14","blog_style_obj_users_font_color":"000000","blog_style_obj_comments_social_font_weight":"normal","blog_style_obj_comment_border_width":"1","blog_style_obj_comment_border_style":"solid","blog_style_obj_comment_border_color":"C9C9C9","blog_style_obj_comment_border_type":"top","blog_style_evt_str_color":"000000","blog_style_evt_str_size":"14","blog_style_evt_str_font_weight":"normal","blog_style_evt_ctzpcn_color":"000000","blog_style_evt_ctzpcn_size":"14","blog_style_evt_ctzpcn_font_weight":"normal","blog_style_evt_map_color":"000000","blog_style_evt_map_size":"14","blog_style_evt_map_font_weight":"normal","blog_style_evt_date_color":"000000","blog_style_evt_date_size":"14","blog_style_evt_date_font_weight":"normal","blog_style_evt_info_font_family":"inherit","album_compact_back_font_color":"000000","album_compact_back_font_style":"inherit","album_compact_back_font_size":"16","album_compact_back_font_weight":"bold","album_compact_back_padding":"0","album_compact_title_font_color":"191919","album_compact_title_font_style":"inherit","album_compact_thumb_title_pos":"bottom","album_compact_title_font_size":"13","album_compact_title_font_weight":"normal","album_compact_title_margin":"2px","album_compact_title_shadow":"0px 0px 0px #888888","album_compact_thumb_margin":"4","album_compact_thumb_padding":"0","album_compact_thumb_border_radius":"0","album_compact_thumb_border_width":"0","album_compact_thumb_border_style":"none","album_compact_thumb_border_color":"CCCCCC","album_compact_thumb_bg_color":"C3E0CE","album_compact_thumbs_bg_color":"FFFFFF","album_compact_thumb_bg_transparent":"0","album_compact_thumb_box_shadow":"0px 0px 0px #888888","album_compact_thumb_transparent":"100","album_compact_thumb_align":"center","album_compact_thumb_hover_effect":"scale","album_compact_thumb_hover_effect_value":"1.1","album_compact_thumb_transition":"0","lightbox_overlay_bg_color":"000000","lightbox_overlay_bg_transparent":"70","lightbox_bg_color":"000000","lightbox_ctrl_btn_pos":"bottom","lightbox_ctrl_btn_align":"center","lightbox_ctrl_btn_height":"20","lightbox_ctrl_btn_margin_top":"10","lightbox_ctrl_btn_margin_left":"7","lightbox_ctrl_btn_transparent":"100","lightbox_ctrl_btn_color":"ffffff","lightbox_toggle_btn_height":"14","lightbox_toggle_btn_width":"100","lightbox_ctrl_cont_bg_color":"000000","lightbox_ctrl_cont_transparent":"65","lightbox_ctrl_cont_border_radius":"4","lightbox_close_btn_transparent":"100","lightbox_close_btn_bg_color":"000000","lightbox_close_btn_border_width":"2","lightbox_close_btn_border_radius":"16px","lightbox_close_btn_border_style":"none","lightbox_close_btn_border_color":"FFFFFF","lightbox_close_btn_box_shadow":"0","lightbox_close_btn_color":"","lightbox_close_btn_size":"10","lightbox_close_btn_width":"20","lightbox_close_btn_height":"20","lightbox_close_btn_top":"-10","lightbox_close_btn_right":"-10","lightbox_close_btn_full_color":"","lightbox_rl_btn_bg_color":"000000","lightbox_rl_btn_transparent":"80","lightbox_rl_btn_border_radius":"20px","lightbox_rl_btn_border_width":"0","lightbox_rl_btn_border_style":"none","lightbox_rl_btn_border_color":"FFFFFF","lightbox_rl_btn_box_shadow":"","lightbox_rl_btn_color":"","lightbox_rl_btn_height":"40","lightbox_rl_btn_width":"40","lightbox_rl_btn_size":"20","lightbox_close_rl_btn_hover_color":"","lightbox_obj_pos":"left","lightbox_obj_width":"350","lightbox_obj_icons_color":"gray","lightbox_obj_date_pos":"after","lightbox_obj_font_family":"inherit","lightbox_obj_info_bg_color":"E2E2E2","lightbox_page_name_color":"4B4B4B","lightbox_obj_page_name_size":"14","lightbox_obj_page_name_font_weight":"bold","lightbox_obj_story_color":"4B4B4B","lightbox_obj_story_size":"14","lightbox_obj_story_font_weight":"normal","lightbox_obj_place_color":"000000","lightbox_obj_place_size":"13","lightbox_obj_place_font_weight":"normal","lightbox_obj_name_color":"4B4B4B","lightbox_obj_name_size":"14","lightbox_obj_name_font_weight":"bold","lightbox_obj_message_color":"000000","lightbox_obj_message_size":"14","lightbox_obj_message_font_weight":"normal","lightbox_obj_hashtags_color":"000000","lightbox_obj_hashtags_size":"12","lightbox_obj_hashtags_font_weight":"normal","lightbox_obj_likes_social_bg_color":"C3E0CE","lightbox_obj_likes_social_color":"FFFFFF","lightbox_obj_likes_social_size":"14","lightbox_obj_likes_social_font_weight":"normal","lightbox_obj_comments_bg_color":"EAEAEA","lightbox_obj_comments_color":"4A4A4A","lightbox_obj_comments_font_family":"inherit","lightbox_obj_comments_font_size":"14","lightbox_obj_users_font_color":"4B4B4B","lightbox_obj_comments_social_font_weight":"normal","lightbox_obj_comment_border_width":"1","lightbox_obj_comment_border_style":"solid","lightbox_obj_comment_border_color":"C9C9C9","lightbox_obj_comment_border_type":"top","lightbox_filmstrip_pos":"top","lightbox_filmstrip_rl_bg_color":"3B3B3B","lightbox_filmstrip_rl_btn_size":"20","lightbox_filmstrip_rl_btn_color":"ffffff","lightbox_filmstrip_thumb_margin":"0 1px","lightbox_filmstrip_thumb_border_width":"1","lightbox_filmstrip_thumb_border_style":"solid","lightbox_filmstrip_thumb_border_color":"000000","lightbox_filmstrip_thumb_border_radius":"0","lightbox_filmstrip_thumb_deactive_transparent":"80","lightbox_filmstrip_thumb_active_border_width":"0","lightbox_filmstrip_thumb_active_border_color":"FFFFFF","lightbox_rl_btn_style":"","lightbox_evt_str_color":"000000","lightbox_evt_str_size":"14","lightbox_evt_str_font_weight":"normal","lightbox_evt_ctzpcn_color":"000000","lightbox_evt_ctzpcn_size":"14","lightbox_evt_ctzpcn_font_weight":"normal","lightbox_evt_map_color":"000000","lightbox_evt_map_size":"14","lightbox_evt_map_font_weight":"normal","lightbox_evt_date_color":"000000","lightbox_evt_date_size":"14","lightbox_evt_date_font_weight":"normal","lightbox_evt_info_font_family":"inherit","page_nav_position":"bottom","page_nav_align":"center","page_nav_number":"0","page_nav_font_size":"12","page_nav_font_style":"inherit","page_nav_font_color":"666666","page_nav_font_weight":"bold","page_nav_border_width":"1","page_nav_border_style":"solid","page_nav_border_color":"E3E3E3","page_nav_border_radius":"0","page_nav_margin":"0","page_nav_padding":"3px 6px","page_nav_button_bg_color":"FFFFFF","page_nav_button_bg_transparent":"100","page_nav_box_shadow":"0","page_nav_button_transition":"1","page_nav_button_text":"0","lightbox_obj_icons_color_likes_comments_count":"white"}',
    ));
    $wpdb->insert($wpdb->prefix . 'wd_fb_theme', array(
      'name' => 'Theme 4',
      'default_theme' => 0,
      'params' => '{"thumb_margin":"10","thumb_padding":"2","thumb_border_radius":"2px","thumb_border_width":"1","thumb_border_style":"none","thumb_border_color":"000000","thumb_bg_color":"CFC3DB","thumbs_bg_color":"FFFFFF","thumb_bg_transparent":"100","thumb_box_shadow":"0px 0px 1px #000000","thumb_transparent":"100","thumb_align":"center","thumb_hover_effect":"rotate","thumb_hover_effect_value":"2deg","thumb_transition":"1","thumb_title_font_color":"191919","thumb_title_font_style":"inherit","thumb_title_pos":"bottom","thumb_title_font_size":"14","thumb_title_font_weight":"normal","thumb_title_margin":"10","thumb_title_shadow":"","thumb_like_comm_pos":"bottom","thumb_like_comm_font_size":"14","thumb_like_comm_font_color":"FFFFFF","thumb_like_comm_font_style":"inherit","thumb_like_comm_font_weight":"normal","thumb_like_comm_shadow":"0px 0px 1px #000000","masonry_thumb_padding":"4","masonry_thumb_border_radius":"2px","masonry_thumb_border_width":"1","masonry_thumb_border_style":"solid","masonry_thumb_border_color":"FFFFFF","masonry_thumbs_bg_color":"CFC3DB","masonry_thumb_bg_transparent":"100","masonry_thumb_transparent":"100","masonry_thumb_align":"center","masonry_thumb_hover_effect":"scale","masonry_thumb_hover_effect_value":"1.1","masonry_thumb_transition":"1","masonry_description_font_size":"14","masonry_description_color":"191919","masonry_description_font_style":"inherit","masonry_like_comm_pos":"bottom","masonry_like_comm_font_size":"14","masonry_like_comm_font_color":"FFFFFF","masonry_like_comm_font_style":"inherit","masonry_like_comm_font_weight":"normal","masonry_like_comm_shadow":"0px 0px 1px #000000","blog_style_align":"left","blog_style_bg_color":"FFFFFF","blog_style_fd_name_bg_color":"000000","blog_style_fd_name_align":"center","blog_style_fd_name_padding":"10","blog_style_fd_name_color":"FFFFFF","blog_style_fd_name_size":"15","blog_style_fd_name_font_weight":"normal","blog_style_fd_icon":"","blog_style_fd_icon_color":"","blog_style_fd_icon_size":"","blog_style_transparent":"100","blog_style_obj_img_align":"center","blog_style_margin":"10","blog_style_box_shadow":"","blog_style_border_width":"1","blog_style_border_style":"solid","blog_style_border_color":"C9C9C9","blog_style_border_type":"top","blog_style_border_radius":"","blog_style_obj_icons_color":"gray","blog_style_obj_date_pos":"after","blog_style_obj_font_family":"inherit","blog_style_obj_info_bg_color":"FFFFFF","blog_style_page_name_color":"000000","blog_style_obj_page_name_size":"13","blog_style_obj_page_name_font_weight":"bold","blog_style_obj_story_color":"000000","blog_style_obj_story_size":"14","blog_style_obj_story_font_weight":"normal","blog_style_obj_place_color":"000000","blog_style_obj_place_size":"13","blog_style_obj_place_font_weight":"normal","blog_style_obj_name_color":"000000","blog_style_obj_name_size":"13","blog_style_obj_name_font_weight":"bold","blog_style_obj_message_color":"000000","blog_style_obj_message_size":"14","blog_style_obj_message_font_weight":"normal","blog_style_obj_hashtags_color":"000000","blog_style_obj_hashtags_size":"12","blog_style_obj_hashtags_font_weight":"normal","blog_style_obj_likes_social_bg_color":"CFC3DB","blog_style_obj_likes_social_color":"656565","blog_style_obj_likes_social_size":"14","blog_style_obj_likes_social_font_weight":"normal","blog_style_obj_comments_bg_color":"FFFFFF","blog_style_obj_comments_color":"000000","blog_style_obj_comments_font_family":"inherit","blog_style_obj_comments_font_size":"14","blog_style_obj_users_font_color":"000000","blog_style_obj_comments_social_font_weight":"normal","blog_style_obj_comment_border_width":"1","blog_style_obj_comment_border_style":"solid","blog_style_obj_comment_border_color":"C9C9C9","blog_style_obj_comment_border_type":"top","blog_style_evt_str_color":"000000","blog_style_evt_str_size":"14","blog_style_evt_str_font_weight":"normal","blog_style_evt_ctzpcn_color":"000000","blog_style_evt_ctzpcn_size":"14","blog_style_evt_ctzpcn_font_weight":"normal","blog_style_evt_map_color":"000000","blog_style_evt_map_size":"14","blog_style_evt_map_font_weight":"normal","blog_style_evt_date_color":"000000","blog_style_evt_date_size":"14","blog_style_evt_date_font_weight":"normal","blog_style_evt_info_font_family":"inherit","album_compact_back_font_color":"000000","album_compact_back_font_style":"inherit","album_compact_back_font_size":"16","album_compact_back_font_weight":"bold","album_compact_back_padding":"0","album_compact_title_font_color":"191919","album_compact_title_font_style":"inherit","album_compact_thumb_title_pos":"bottom","album_compact_title_font_size":"13","album_compact_title_font_weight":"normal","album_compact_title_margin":"2px","album_compact_title_shadow":"0px 0px 0px #888888","album_compact_thumb_margin":"0","album_compact_thumb_padding":"0","album_compact_thumb_border_radius":"0","album_compact_thumb_border_width":"0","album_compact_thumb_border_style":"none","album_compact_thumb_border_color":"CCCCCC","album_compact_thumb_bg_color":"CFC3DB","album_compact_thumbs_bg_color":"FFFFFF","album_compact_thumb_bg_transparent":"0","album_compact_thumb_box_shadow":"0px 0px 0px #888888","album_compact_thumb_transparent":"100","album_compact_thumb_align":"center","album_compact_thumb_hover_effect":"scale","album_compact_thumb_hover_effect_value":"1.1","album_compact_thumb_transition":"0","lightbox_overlay_bg_color":"000000","lightbox_overlay_bg_transparent":"70","lightbox_bg_color":"000000","lightbox_ctrl_btn_pos":"bottom","lightbox_ctrl_btn_align":"center","lightbox_ctrl_btn_height":"20","lightbox_ctrl_btn_margin_top":"10","lightbox_ctrl_btn_margin_left":"7","lightbox_ctrl_btn_transparent":"100","lightbox_ctrl_btn_color":"ffffff","lightbox_toggle_btn_height":"14","lightbox_toggle_btn_width":"100","lightbox_ctrl_cont_bg_color":"000000","lightbox_ctrl_cont_transparent":"65","lightbox_ctrl_cont_border_radius":"4","lightbox_close_btn_transparent":"100","lightbox_close_btn_bg_color":"000000","lightbox_close_btn_border_width":"2","lightbox_close_btn_border_radius":"16px","lightbox_close_btn_border_style":"none","lightbox_close_btn_border_color":"FFFFFF","lightbox_close_btn_box_shadow":"0","lightbox_close_btn_color":"","lightbox_close_btn_size":"10","lightbox_close_btn_width":"20","lightbox_close_btn_height":"20","lightbox_close_btn_top":"-10","lightbox_close_btn_right":"-10","lightbox_close_btn_full_color":"","lightbox_rl_btn_bg_color":"000000","lightbox_rl_btn_transparent":"80","lightbox_rl_btn_border_radius":"20px","lightbox_rl_btn_border_width":"0","lightbox_rl_btn_border_style":"none","lightbox_rl_btn_border_color":"FFFFFF","lightbox_rl_btn_box_shadow":"","lightbox_rl_btn_color":"","lightbox_rl_btn_height":"40","lightbox_rl_btn_width":"40","lightbox_rl_btn_size":"20","lightbox_close_rl_btn_hover_color":"","lightbox_obj_pos":"left","lightbox_obj_width":"350","lightbox_obj_icons_color":"gray","lightbox_obj_date_pos":"after","lightbox_obj_font_family":"inherit","lightbox_obj_info_bg_color":"E2E2E2","lightbox_page_name_color":"4B4B4B","lightbox_obj_page_name_size":"14","lightbox_obj_page_name_font_weight":"bold","lightbox_obj_story_color":"4B4B4B","lightbox_obj_story_size":"14","lightbox_obj_story_font_weight":"normal","lightbox_obj_place_color":"000000","lightbox_obj_place_size":"13","lightbox_obj_place_font_weight":"normal","lightbox_obj_name_color":"4B4B4B","lightbox_obj_name_size":"14","lightbox_obj_name_font_weight":"bold","lightbox_obj_message_color":"000000","lightbox_obj_message_size":"14","lightbox_obj_message_font_weight":"normal","lightbox_obj_hashtags_color":"000000","lightbox_obj_hashtags_size":"12","lightbox_obj_hashtags_font_weight":"normal","lightbox_obj_likes_social_bg_color":"CFC3DB","lightbox_obj_likes_social_color":"FFFFFF","lightbox_obj_likes_social_size":"14","lightbox_obj_likes_social_font_weight":"normal","lightbox_obj_comments_bg_color":"EAEAEA","lightbox_obj_comments_color":"4A4A4A","lightbox_obj_comments_font_family":"inherit","lightbox_obj_comments_font_size":"14","lightbox_obj_users_font_color":"4B4B4B","lightbox_obj_comments_social_font_weight":"normal","lightbox_obj_comment_border_width":"1","lightbox_obj_comment_border_style":"solid","lightbox_obj_comment_border_color":"C9C9C9","lightbox_obj_comment_border_type":"top","lightbox_filmstrip_pos":"top","lightbox_filmstrip_rl_bg_color":"3B3B3B","lightbox_filmstrip_rl_btn_size":"20","lightbox_filmstrip_rl_btn_color":"ffffff","lightbox_filmstrip_thumb_margin":"0 1px","lightbox_filmstrip_thumb_border_width":"1","lightbox_filmstrip_thumb_border_style":"solid","lightbox_filmstrip_thumb_border_color":"000000","lightbox_filmstrip_thumb_border_radius":"0","lightbox_filmstrip_thumb_deactive_transparent":"80","lightbox_filmstrip_thumb_active_border_width":"0","lightbox_filmstrip_thumb_active_border_color":"FFFFFF","lightbox_rl_btn_style":"","lightbox_evt_str_color":"000000","lightbox_evt_str_size":"14","lightbox_evt_str_font_weight":"normal","lightbox_evt_ctzpcn_color":"000000","lightbox_evt_ctzpcn_size":"14","lightbox_evt_ctzpcn_font_weight":"normal","lightbox_evt_map_color":"000000","lightbox_evt_map_size":"14","lightbox_evt_map_font_weight":"normal","lightbox_evt_date_color":"000000","lightbox_evt_date_size":"14","lightbox_evt_date_font_weight":"normal","lightbox_evt_info_font_family":"inherit","page_nav_position":"bottom","page_nav_align":"center","page_nav_number":"0","page_nav_font_size":"12","page_nav_font_style":"inherit","page_nav_font_color":"666666","page_nav_font_weight":"bold","page_nav_border_width":"1","page_nav_border_style":"solid","page_nav_border_color":"E3E3E3","page_nav_border_radius":"0","page_nav_margin":"0","page_nav_padding":"3px 6px","page_nav_button_bg_color":"FFFFFF","page_nav_button_bg_transparent":"100","page_nav_box_shadow":"0","page_nav_button_transition":"1","page_nav_button_text":"0","lightbox_obj_icons_color_likes_comments_count":"white"}',
    ));
  }
  wp_schedule_event(time(), 'wd_fb_autoupdate_interval', 'wd_fb_schedule_event_hook');
  ffwd_version();
}

register_activation_hook(__FILE__, 'ffwd_activate');

/* On deactivation, remove all functions from the scheduled action hook.*/
function ffwd_deactivate() {
  wp_clear_scheduled_hook('wd_fb_schedule_event_hook');
}

register_deactivation_hook(__FILE__, 'ffwd_deactivate');

// Plugin styles.
function ffwd_styles() {
  wp_admin_css('thickbox');
  wp_enqueue_style('ffwd_tables', WD_FFWD_URL . '/css/ffwd_tables.css', array(), ffwd_get_version());
  wp_register_style('ffwd_topbar', WD_FFWD_URL . '/css/topbar.css', array(), ffwd_get_version());
  wp_register_style('ffwd_roboto', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700');
}

// Plugin scripts.
function ffwd_scripts() {
  wp_enqueue_script('thickbox');
  wp_enqueue_script('ffwd_cache', WD_FFWD_URL . '/js/ffwd_cache.js', array('jquery'), ffwd_get_version());
  wp_localize_script('ffwd_cache', 'ffwd_cache', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'update_data' => defined('FFWD_FEED_DATA') ? FFWD_FEED_DATA : '',
    'need_update' => FFWD_NEED_UPDATE_CACHE
  ));

  wp_enqueue_script('ffwd_admin', WD_FFWD_URL . '/js/ffwd.js', array(), ffwd_get_version());

  global $wp_scripts;
  if ( isset($wp_scripts->registered['jquery']) ) {
    $jquery = $wp_scripts->registered['jquery'];
    if ( !isset($jquery->ver) OR version_compare($jquery->ver, '1.8.2', '<') ) {
      wp_deregister_script('jquery');
      wp_register_script('jquery', FALSE, array( 'jquery-core', 'jquery-migrate' ), '1.10.2');
    }
  }
  wp_enqueue_script('jquery');
  wp_enqueue_script('jquery-ui-sortable');
}

/* Add pagination to gallery admin pages.*/
function ffwd_add_ffwd_info_per_page_option() {
  $option = 'per_page';
  $args_galleries = array(
    'label' => 'Items',
    'default' => 20,
    'option' => 'ffwd_info_per_page',
  );
  add_screen_option($option, $args_galleries);
}

function ffwd_add_themes_per_page_option() {
  $option = 'per_page';
  $args_themes = array(
    'label' => 'Themes',
    'default' => 20,
    'option' => 'ffwd_themes_per_page',
  );
  add_screen_option($option, $args_themes);
}

add_filter('set-screen-option', 'ffwd_set_option_galleries', 10, 3);
add_filter('set-screen-option', 'ffwd_set_option_themes', 10, 3);
function ffwd_set_option_galleries( $status, $option, $value ) {
  if ( 'ffwd_info_per_page' == $option ) {
    return $value;
  }

  return $status;
}

function ffwd_set_option_themes( $status, $option, $value ) {
  if ( 'ffwd_themes_per_page' == $option ) {
    return $value;
  }

  return $status;
}

function ffwd_enqueue__admin_scripts(){
  wp_enqueue_script( 'ffwd_jquery_form_js', WD_FFWD_URL . '/js/jquery.form.js', array(), ffwd_get_version() );
}

function ffwd_admin_scripts() {
  wp_enqueue_script('thickbox');

  /*TODO no need as called from ffwd_scripts() function the same */
  wp_enqueue_script('ffwd_cache', WD_FFWD_URL . '/js/ffwd_cache.js', array('jquery'), ffwd_get_version());
  wp_localize_script('ffwd_cache', 'ffwd_cache', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'update_data' => defined('FFWD_FEED_DATA') ? FFWD_FEED_DATA : '',
    'need_update' => FFWD_NEED_UPDATE_CACHE
  ));

  wp_enqueue_script('ffwd_admin', WD_FFWD_URL . '/js/ffwd.js', array(), ffwd_get_version());
  wp_localize_script('ffwd_admin', 'ffwd_ajax', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'ajaxnonce' => wp_create_nonce(WD_FFWD_URL . '_ajax_nonce'),
  ));


  global $wp_scripts;
  if ( isset($wp_scripts->registered['jquery']) ) {
    $jquery = $wp_scripts->registered['jquery'];
    if ( !isset($jquery->ver) OR version_compare($jquery->ver, '1.8.2', '<') ) {
      wp_deregister_script('jquery');
      wp_register_script('jquery', FALSE, array( 'jquery-core', 'jquery-migrate' ), '1.10.2');
    }
  }
  wp_enqueue_script('jquery');
  wp_enqueue_script('jscolor', WD_FFWD_URL . '/js/jscolor/jscolor.js', array(), '1.3.9');
}

function ffwd_front_end_scripts() {
  $version = ffwd_get_version();
  global $wp_scripts;
  if ( isset($wp_scripts->registered['jquery']) ) {
    $jquery = $wp_scripts->registered['jquery'];
    if ( !isset($jquery->ver) OR version_compare($jquery->ver, '1.8.2', '<') ) {
      wp_deregister_script('jquery');
      wp_register_script('jquery', FALSE, array( 'jquery-core', 'jquery-migrate' ), '1.10.2');
    }
  }
  wp_enqueue_script('jquery');
  wp_enqueue_script('ffwd_cache', WD_FFWD_URL . '/js/ffwd_cache.js', array('jquery'), ffwd_get_version());
  wp_localize_script('ffwd_cache', 'ffwd_cache', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'update_data' => defined('FFWD_FEED_DATA') ? FFWD_FEED_DATA : '',
    'need_update' => FFWD_NEED_UPDATE_CACHE
  ));

  wp_enqueue_script('ffwd_frontend', WD_FFWD_FRONT_URL . '/js/ffwd_frontend.js', array('jquery'), $version);
  wp_enqueue_style('ffwd_frontend', WD_FFWD_FRONT_URL . '/css/ffwd_frontend.css', array(), $version);
  // Styles/Scripts for popup.
  wp_enqueue_style('ffwd_fonts', WD_FFWD_FRONT_URL . '/css/fonts.css', array(), $version);
  wp_enqueue_script('ffwd_jquery_mobile', WD_FFWD_FRONT_URL . '/js/jquery.mobile.js', array(), $version);
  wp_enqueue_script('ffwd_mCustomScrollbar', WD_FFWD_FRONT_URL . '/js/jquery.mCustomScrollbar.concat.min.js', array(), $version);
  wp_enqueue_style('ffwd_mCustomScrollbar', WD_FFWD_FRONT_URL . '/css/jquery.mCustomScrollbar.css', array(), $version);
  wp_enqueue_script('jquery-fullscreen', WD_FFWD_FRONT_URL . '/js/jquery.fullscreen-0.4.1.js', array(), '0.4.1');
  wp_enqueue_script('ffwd_gallery_box', WD_FFWD_FRONT_URL . '/js/ffwd_gallery_box.js', array(), $version);
  wp_localize_script('ffwd_gallery_box', 'ffwd_objectL10n', array(
    'ffwd_field_required' => __('field is required.', 'bwg'),
    'ffwd_mail_validation' => __('This is not a valid email address.', 'bwg'),
    'ffwd_search_result' => __('There are no images matching your search.', 'bwg'),
  ));
  wp_localize_script('ffwd_frontend', 'ffwd_frontend_text', array(
    'comment_reply' => __('Reply', 'ffwd'),
    'view' => __('View', 'ffwd'),
    'more_comments' => __('more comments', 'ffwd'),
    'year' => __('year', 'ffwd'),
    'years' => __('years', 'ffwd'),
    'hour' => __('hour', 'ffwd'),
    'hours' => __('hours', 'ffwd'),
    'months' => __('months', 'ffwd'),
    'month' => __('month', 'ffwd'),
    'weeks' => __('weeks', 'ffwd'),
    'week' => __('week', 'ffwd'),
    'days' => __('days', 'ffwd'),
    'day' => __('day', 'ffwd'),
    'minutes' => __('minutes', 'ffwd'),
    'minute' => __('minute', 'ffwd'),
    'seconds' => __('seconds', 'ffwd'),
    'second' => __('second', 'ffwd'),
    'ago' => __('ago', 'ffwd'),
    'ajax_url' => admin_url('admin-ajax.php'),
    'and' => __('and', 'ffwd'),
    'others' => __('others', 'ffwd'),
  ));
}

add_action( 'admin_enqueue_scripts', 'ffwd_enqueue__admin_scripts' );

/* Check every time from frontend and backend if need update token/data */
function ffwd_check_cache_update() {
  global $wpdb;
  require_once(WD_FFWD_DIR . '/framework/WDFacebookFeed.php');
  $current_time = current_time('timestamp');
  $update_time = get_option('ffwd_autoupdate_time');
  $autoupdate_interval = WDFacebookFeed::get_autoupdate_interval();
  if ( $current_time >= $update_time ) {
    update_option('ffwd_autoupdate_time', $autoupdate_interval * 60 + $current_time);
    $ff_wd_options = get_option('ffwd_pages_list');
      if ( !empty($ff_wd_options) ) {
        foreach ( $ff_wd_options as $ff_wd_option ) {
          $token = $ff_wd_option->access_token;
          $page_id = $ff_wd_option->id;
          WDFacebookFeed::update_page_access_token($token, $page_id);
        }
      }
      $query = "SELECT * FROM " . $wpdb->prefix . "wd_fb_info WHERE `update_mode` <> 'no_update'";
      $rows = $wpdb->get_results($query);
      if ( !defined('FFWD_FEED_DATA') ) {
        define('FFWD_FEED_DATA', json_encode($rows));
      }
      if ( !defined('FFWD_NEED_UPDATE_CACHE') ) {
        define('FFWD_NEED_UPDATE_CACHE', 'true');
      }
      return;
  } else {
      if ( !defined('FFWD_NEED_UPDATE_CACHE') ) {
        define('FFWD_NEED_UPDATE_CACHE', 'false');
      }
  }

  $query = "SELECT * FROM " . $wpdb->prefix . "wd_fb_info";
  $rows = $wpdb->get_results($query, ARRAY_A);
  foreach ( $rows as $key => $val ) {
    $query = "SELECT count(`id`) FROM " . $wpdb->prefix . "wd_fb_data WHERE `fb_id` = ".$val['id'];
    $count = $wpdb->get_var($query);
    $rows[$key]['data_count'] = $count;
  }
  if ( !defined('FFWD_FEED_DATA') ) {
    define('FFWD_FEED_DATA', json_encode($rows));
  }
  return;
}
add_action('init', 'ffwd_check_cache_update');

// Check Valid Token and show message
$ffwd_token_error_flag = get_option("ffwd_token_error_flag");
$ffwd_option_reauth_success = ((isset($_GET['success'])) ? $_GET["success"] : '');
if ( $ffwd_token_error_flag === '1' || $ffwd_option_reauth_success != '' ) {
  add_action('admin_notices', 'ffwd_token_error_flag_notice');
}

function ffwd_token_error_flag_notice() {
  global $ffwd_token_error_flag, $ffwd_option_reauth_success;
  $screen_base = get_current_screen()->base;
  if ( $ffwd_token_error_flag === '1' ) {
    if ( $screen_base === 'dashboard' || $screen_base === 'toplevel_page_info_ffwd' || $screen_base === 'facebook-feed_page_options_ffwd' || $screen_base === 'facebook-feed_page_themes_ffwd' || $screen_base === 'facebook-feed_page_uninstall_ffwd' ) {
      $link_to_reset = "<a href='" . site_url() . "/wp-admin/admin.php?page=options_ffwd' >reset token</a>";
      if ( $screen_base === "facebook-feed_page_options_ffwd" ) {
        $link_to_reset = "reset token";
      }
      echo "<div class='notice notice-error'><p>" . sprintf(__('Facebook token is invalid or expired. Please %s and sign-in again to get new one.', 'ffwd'), $link_to_reset) . "</p></div>";
    }
  }
  if ( !empty($ffwd_option_reauth_success) ) {
    if ( $ffwd_option_reauth_success == 1 ) {
      echo "<div class='notice notice-success'><p>" . __('The Access Token Successfully saved.', 'ffwd') . "</p></div>";
    }
    elseif ( $ffwd_option_reauth_success == 2 ) {
      echo "<div class='notice notice-error'><p>" . sprintf(__('No business pages were selected. Please uninstall the %s app from %s section and reinstall. Then choose the business page/s and re-connect.', 'ffwd'), '<b>10Web Social Feed</b>', '<b>Facebook > Settings > Security and login > Business integrations or Apps and websites</b>') . "</p></div>";
    }
    else {
      echo "<div class='notice notice-error'><p>" . __('Something wrong. Please try again.', 'ffwd') . "</p></div>";
    }
  }
}

// Facebook Feed by 10Web Widget.
if (class_exists('WP_Widget')) {
    add_action('wp_enqueue_scripts', 'ffwd_front_end_scripts');
    require_once(WD_FFWD_DIR . '/admin/controllers/FFWDControllerWidget.php');
    add_action('widgets_init', 'ffwd_register_widget');
}

function ffwd_register_widget(){
  return register_widget("FFWDControllerWidget");
}

// Languages localization.
function ffwd_language_load() {
  load_plugin_textdomain('ffwd', false, basename(dirname(__FILE__)) . '/languages');
}

add_action('init', 'ffwd_language_load');
function ffwd_version() {
  $version = FFWD_VERSION;
  if ( get_option('ffwd_version') === FALSE ) {
    add_option('ffwd_version', $version);
  }
  else {
    update_option('ffwd_version', $version);
  }

  return $version;
}

function ffwd_get_version() {
  if ( get_option('ffwd_version') === FALSE ) {
    ffwd_version();
  }

  return get_option('ffwd_version');
}

if ( !class_exists('Linkify') ) {
  include_once WD_FFWD_DIR . '/framework/linkify/LinkifyInterface.php';
  include_once WD_FFWD_DIR . '/framework/linkify/Linkify.php';
}

/*ELEMENTOR*/
add_action('plugins_loaded', 'ffwd_elementor');
function ffwd_elementor() {
  if ( defined('ELEMENTOR_VERSION') ) {
    include_once 'elementor/elementor.php';
    FFWDElementor::get_instance();
  }
}

require_once(WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)) . '/booster/init.php');
add_action('init', function() {
  TWB(array(
        'plugin_dir' => WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)) . '/booster',
        'plugin_url' => plugins_url(plugin_basename(dirname(__FILE__))) . '/booster',
        'submenu' => array(
          'parent_slug' => 'info_ffwd',
        ),
        'page' => array(
          'slug' => 'facebook-feed',
        ),
      ));
}, 11);
