<?php
/**
 * @package Techotronic
 * @subpackage All in one Favicon
 *
 * @since 4.0
 * @author Arne Franken
 *
 * Object that handles all actions in the WordPress backend
 */

require_once (dirname (__FILE__) . '/favicon-render-helper.php');

class AioFaviconBackend {

  /**
   * Constructor
   *
   * @since 4.0
   * @access public
   * @access static
   * @author Arne Franken
   *
   * @param array $aioFaviconSettings user settings
   * @param array $aioFaviconDefaultSettings default plugin settings
   * @param AIOFaviconDonationLoader $donationLoader the donationloader
   * @param array $faviconFrontendMap mapping of favicon types to translatable Strings
   * @param array $faviconBackendMap mapping of favicon types to translatable Strings
   *
   * @return void
   */
  //public static function AioFaviconBackend($aioFaviconSettings) {
  function __construct($aioFaviconSettings, $aioFaviconDefaultSettings, $donationLoader,
                              $faviconFrontendMap, $faviconBackendMap) {

    $this->aioFaviconSettings = $aioFaviconSettings;
    $this->aioFaviconDefaultSettings = $aioFaviconDefaultSettings;
    $this->donationLoader = $donationLoader;
    $this->faviconRenderHelper = new FaviconRenderHelper($this->aioFaviconSettings,AIOFAVICON_BACKEND);
    $this->faviconFrontendMap = $faviconFrontendMap;
    $this->faviconBackendMap = $faviconBackendMap;
    $this->faviconMap = array_merge($faviconFrontendMap,$faviconBackendMap);
  }

  // AioFaviconBackend()


  /**
   * Initialize
   *
   * @since 4.0
   * @access public
   * @author Arne Franken
   *
   * @return void
   */
  //public function init() {
  function init() {
    add_action('admin_head', array(& $this->faviconRenderHelper, 'renderFavicons'));

    // add options page
    add_action('admin_menu', array(& $this, 'registerAdminMenu'));

    add_action('admin_post_aioFaviconDeleteSettings', array(& $this, 'aioFaviconDeleteSettings'));
    add_action('admin_post_aioFaviconUpdateSettings', array(& $this, 'aioFaviconUpdateSettings'));

    //only load JavaScript if we are on this plugin's settingspage
    if (isset($_GET['page']) && $_GET['page'] == AIOFAVICON_PLUGIN_BASENAME) {
      add_action('admin_enqueue_scripts', array(& $this->donationLoader, 'registerDonationJavaScript'));
      add_action('admin_enqueue_scripts', array(& $this, 'registerAdminScripts'));
    }

    add_action("admin_menu", array(& $this, 'admin_menu_link'));

    add_action( 'admin_init', [ $this, 'aiof_admin_init' ] );
  }

  public function aiof_admin_init() {
    $this->aoif_handle_external_redirects();

    wp_enqueue_script('all-in-one-favicon-admin',plugin_dir_url( __FILE__ ). '../scripts/aiof-scripts.js',array('jquery'));
    wp_enqueue_style('all-in-one-favicon-admin-style',plugin_dir_url( __FILE__ ).'../styles/aiof-style-common.css', array(), '3.1.1');
  }
  //init()

  function admin_menu_link() {
    add_menu_page( 'All in one Favicon', 'All in one Favicon', 'manage_options', 'all-in-one-favicon', array(& $this, 'renderSettingsPage'), 'dashicons-star-filled');

    add_submenu_page( 'all-in-one-favicon', 'Other Tools', 'Other Tools', 'manage_options', 'all-in-one-favicon-plugin-other-tools', array(& $this, 'aiof_other_tools_page'));

    add_submenu_page(
        'all-in-one-favicon',
        'Appsumo',
        '<span class="all-in-one-favicon-sidebar-appsumo-link"><span class="dashicons dashicons-star-filled" style="font-size: 17px"></span> AppSumo</span>',
        'manage_options',
        'aiof_go_appsumo_pro',
        array(&$this,'aoif_handle_external_redirects')
      );

    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__),'aiof_filter_plugin_actions', 10, 2 );
  }

  function aiof_filter_plugin_actions($links, $file) {
    $settings_link = '<a href="admin.php?page=all-in-one-favicon">' . __('Settings') . '</a>';
    array_unshift( $links, $settings_link );

    return $links;
  }

  function aiof_other_tools_page() {
    include(AIOFAVICON_PLUGIN_DIR.'/other_tools.php');
  }

  function aoif_handle_external_redirects() {
    if ( empty( $_GET['page'] ) ) {
      return;
    }

    if ( 'aiof_go_appsumo_pro' === $_GET['page'] ) {
      wp_redirect( ( 'https://appsumo.com/tools/wordpress/?utm_source=sumo&utm_medium=wp-widget&utm_campaign=all-in-one-favicon' ) );
      die;
    }
  }

  /**
   * Builds the JavaScript array of all uploaded Favicons.
   * Also registers JavaScript file.
   *
   * @since 4.0
   * @access public
   * @author Arne Franken
   *
   * @return void
   */
  //public function registerAdminScripts() {
  function registerAdminScripts() {
    $backendJavaScriptArray = array();

    if (!empty($this->aioFaviconSettings)) {
      foreach ((array)$this->aioFaviconSettings as $type => $url) {
        if (!empty($url)) {
          if (preg_match('/backend/i', $type) || preg_match('/frontend/i', $type)) {
            $backendJavaScriptArray[$type] = $url;
          }
        }
      }
    }
    wp_register_script('aiofavicon', AIOFAVICON_PLUGIN_URL . '/js/aiofavicon-min.js', array('jquery'));
    wp_enqueue_script('aiofavicon');
    wp_localize_script('aiofavicon', 'Aiofavicon', $backendJavaScriptArray);
  }

  // registerAdminScripts()

  /**
   * Render Settings page
   *
   * @since 1.0
   * @access public
   * @author Arne Franken
   *
   * @return void
   */
  //public function renderSettingsPage() {
  function renderSettingsPage() {
    include_once 'settings-page.php';
  }

  // renderSettingsPage()

  /**
   * Add settings link to plugin management page
   *
   * @since 1.0
   * @access public
   * @author Arne Franken
   *
   * @param  array $action_links original links
   *
   * @return array $action_links with link to settings page
   */
  //public function addPluginActionLinks($action_links) {
  function addPluginActionLinks($action_links) {
    $settings_link = '<a href="admin.php?page=' . AIOFAVICON_PLUGIN_NAME . '">' . __('Settings', AIOFAVICON_TEXTDOMAIN) . '</a>';
    array_unshift($action_links, $settings_link);

    return $action_links;
  }

  //addPluginActionLinks()

  /**
   * Registers the Settings Page in the Admin Menu
   *
   * @since 1.0
   * @access public
   * @author Arne Franken
   *
   * @return void
   */
  //public function registerAdminMenu() {
  function registerAdminMenu() {
    $return_message = '';

    if (function_exists('add_management_page') && current_user_can('manage_options')) {

      // update, uninstall message
      if (strpos($_SERVER['REQUEST_URI'], 'all-in-one-favicon.php') && isset($_GET['aioFaviconUpdateSettings'])) {
        $return_message = sprintf(__('Successfully updated %1$s settings.', AIOFAVICON_TEXTDOMAIN), AIOFAVICON_NAME);
      } elseif (strpos($_SERVER['REQUEST_URI'], 'all-in-one-favicon.php') && isset($_GET['aioFaviconDeleteSettings'])) {
        $return_message = sprintf(__('%1$s settings were successfully deleted.', AIOFAVICON_TEXTDOMAIN), AIOFAVICON_NAME);
      }
    }
    $this->registerAdminNotice($return_message);

    $this->registerSettingsPage();
  }

  // registerAdminMenu()

  /**
   * Update plugin settings wrapper
   *
   * handles checks and redirect
   *
   * @since 1.0
   * @access public
   * @author Arne Franken
   *
   * @return void
   */
  //public function aioFaviconUpdateSettings() {
  function aioFaviconUpdateSettings() {
    if (!current_user_can('manage_options'))
      wp_die(__('Did not update settings, you do not have the necessary rights.', AIOFAVICON_TEXTDOMAIN));

    //cross check the given referer for nonce set in settings form
    check_admin_referer('aio-favicon-settings-form');

    // get new settings from POST
    $usersettings = $_POST[AIOFAVICON_SETTINGSNAME];

    // if URL was copy / pasted into form, fields named "*-text", but must be saved without "-text" suffix.
    foreach ($usersettings as $settingsName => $settingsValue) {
      if(preg_match('/(.*)-text/i',$settingsName, $matches)) {
        if (count($matches) > 1) {
          $match = $matches[1];
          if(!empty($settingsValue)) {
            $usersettings[$match] = $settingsValue;
          }
        }
        //delete "*-text" from incoming array
        unset($usersettings[$settingsName]);
      }
    }

    // Create the settings array by merging the user's settings and the defaults
    $defaultArray = $this->aioFaviconDefaultSettings;
    $this->aioFaviconSettings = wp_parse_args($usersettings, wp_parse_args((array)get_option(AIOFAVICON_SETTINGSNAME), $defaultArray));

    //don't show link in meta box by default any more.
    if (!isset($usersettings['removeLinkFromMetaBox'])) {
      $this->aioFaviconSettings['removeLinkFromMetaBox'] = false;
    }

    // handle file upload
    $overrides = array('action' => 'aioFaviconUpdateSettings');
    foreach ($_FILES as $icoName => $icoArray) {
      if(!empty($icoArray['name'])) {
        $file = wp_handle_upload($_FILES[$icoName], $overrides);
        if (isset($file['url'])) {
          $this->aioFaviconSettings[$icoName] = $file['url'];
        }
      }
    }

    // delete files if checkboxes are checked
    foreach ($_POST as $key => $value) {
      if (preg_match('/delete-(.*)/i', $key, $matches)) {
        if (count($matches) > 1) {
          $match = $matches[1];
          $this->deleteFile($match);
        }
      }
    }

    $this->updateSettingsInDatabase();
    $referrer = str_replace(array('&aioFaviconUpdateSettings', '&aioFaviconDeleteSettings'), '', $_POST['_wp_http_referer']);
    wp_redirect($referrer . '&aioFaviconUpdateSettings');
  }

  // aioFaviconUpdateSettings()

  /**
   * Delete plugin settings wrapper
   *
   * handles checks and redirect
   *
   * @since 1.0
   * @access public
   * @author Arne Franken
   *
   * @return void
   */
  //public function aioFaviconDeleteSettings() {
  function aioFaviconDeleteSettings() {

    if (current_user_can('manage_options') && isset($_POST['delete_settings-true'])) {
      //cross check the given referer for nonce set in delete settings form
      check_admin_referer('aio-favicon-delete_settings-form');

      foreach($this->faviconMap as $key => $value) {
        $this->deleteFile($key);
      }

      $this->deleteSettingsFromDatabase();

    }
    else {
      wp_die(sprintf(__('Did not delete %1$s settings. Either you dont have the nececssary rights or you didnt check the checkbox.', AIOFAVICON_TEXTDOMAIN), AIOFAVICON_NAME));
    }
    //clean up referrer
    $referrer = str_replace(array('&aioFaviconUpdateSettings', '&aioFaviconDeleteSettings'), '', $_POST['_wp_http_referer']);
    wp_redirect($referrer . '&aioFaviconDeleteSettings');
  }

  // aioFaviconDeleteSettings()

  //===================================================================

  /**
   * Registers Admin Notices
   *
   * @since 1.0
   * @access private
   * @author Arne Franken
   *
   * @param string $notice to register notice with.
   *
   * @return void
   */
  //private function registerAdminNotice($notice) {
  function registerAdminNotice($notice) {
    if ($notice != '') {
      $message = '<div class="updated fade"><p>' . $notice . '</p></div>';
      add_action('admin_notices', create_function('', "echo '$message';"));
    }
  }

  // registerAdminNotice()

  /**
   * Delete favicon file
   *
   * @since 4.0
   * @access private
   * @author Arne Franken
   *
   * @param String $faviconName
   *
   * @return void
   */
  //private function deleteFile($faviconName) {
  function deleteFile($faviconName) {
    $url = $this->aioFaviconSettings[$faviconName];

    // Sanitize the file path
    $url = str_replace(['../', '..'], '', $url);

    if ($url != '') {
      $uploads = wp_upload_dir();
      $regex = '#' . $uploads['baseurl'] . '/(.*)#i';
      preg_match($regex, $url, $relativePath);

      if (count($relativePath) > 1) {
        $pathToFile = $uploads['basedir'] . '/' . $relativePath[1];
        @ unlink($pathToFile);
      }
    }

    //delete setting
    $this->aioFaviconSettings[$faviconName] = '';
  }

  // deleteFile()

  /**
   * Update plugin settings
   *
   * handles updating settings in the WordPress database
   *
   * @since 1.0
   * @access private
   * @author Arne Franken
   *
   * @return void
   */
  //private function updateSettingsInDatabase() {
  function updateSettingsInDatabase() {
    update_option(AIOFAVICON_SETTINGSNAME, $this->aioFaviconSettings);
  }

  //updateSettingsInDatabase()

  /**
   * Register the settings page in WordPress
   *
   * @since 1.0
   * @access private
   * @author Arne Franken
   *
   * @return void
   */
  //private function registerSettingsPage() {
  function registerSettingsPage() {
    if (current_user_can('manage_options')) {
      add_filter('plugin_action_links_' . AIOFAVICON_PLUGIN_BASENAME, array(& $this, 'addPluginActionLinks'));
      //add_options_page(AIOFAVICON_NAME, AIOFAVICON_NAME, 'manage_options', AIOFAVICON_PLUGIN_BASENAME, array(& $this, 'renderSettingsPage'));
    }
  }

  // registerSettingsPage()

  /**
   * Delete plugin settings
   *
   * handles deletion from WordPress database
   *
   * @since 1.0
   * @access private
   * @author Arne Franken
   *
   * @return void
   */
  //private function deleteSettingsFromDatabase() {
  function deleteSettingsFromDatabase() {
    delete_option(AIOFAVICON_SETTINGSNAME);
  }

  // deleteSettingsFromDatabase()

  /**
   * Read HTML from a remote url
   *
   * @since 2.1
   * @access private
   * @author Arne Franken
   *
   * @param string $url
   *
   * @return string the response
   */
  //private function getRemoteContent($url) {
  function getRemoteContent($url) {
    if (function_exists('wp_remote_request')) {

      $options = array('user-agent' => AIOFAVICON_USERAGENT);

      $response = wp_remote_request($url, $options);

      if (is_wp_error($response)) {
        return false;
      }

      if (200 != wp_remote_retrieve_response_code($response)) {
        return false;
      }

      return wp_remote_retrieve_body($response);
    }

    return false;
  }

  // getRemoteContent()

  /**
   * gets current URL to return to after donating
   *
   * @since 2.1
   * @access private
   * @author Arne Franken
   *
   * @return void
   */
  //private function getReturnLocation(){
  function getReturnLocation() {
    $currentLocation = "http";
    $currentLocation .= ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? "s" : "") . "://";
    $currentLocation .= $_SERVER['SERVER_NAME'];

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {

      if ($_SERVER['SERVER_PORT'] != '443') {
        $currentLocation .= ":" . $_SERVER['SERVER_PORT'];
      }
    }
    else {

      if ($_SERVER['SERVER_PORT'] != '80') {
        $currentLocation .= ":" . $_SERVER['SERVER_PORT'];
      }
    }

    $currentLocation .= $_SERVER['REQUEST_URI'];
    echo $currentLocation;
  }

  // getReturnLocation()

}

// AioFaviconBackend()
?>