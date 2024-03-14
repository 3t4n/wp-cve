<?php

include('ezKillLite.php');

if (!class_exists("GoogleAdSense")) {

  require_once 'EZWP.php';
  require_once 'EzPlugin.php';

  class GoogleAdSense extends EzPlugin {

    var $slug, $options, $plgName;

    function __construct() { //constructor
      $this->key = 'ezadsense';

      parent::__construct(__FILE__);

      $this->slug = EZWP::getSlug("{$this->plgDir}/admin");
      $this->name = $this->plgName = EZWP::getPlgName("{$this->plgDir}/admin");
    }

    function getQuery($atts) {
      $query = "";
      $vars = array("id" => "", "code" => "", "key" => "");
      $vars = shortcode_atts($vars, $atts);
      foreach ($vars as $k => $v) {
        if (!empty($v)) {
          $query = "&$k=$v";
          return $query;
        }
      }
    }

    function addAdminPage() {
      add_options_page($this->plgName, $this->plgName, 'activate_plugins', basename(__FILE__), array($this, 'printAdminPage'));
    }

    function addWidgets() {
      $widgetFile = "{$this->plgDir}/{$this->slug}-widget.php";
      if (file_exists($widgetFile)) {
        require_once $widgetFile;
      }
      return;
    }

    static function install($dir = '', $mOptions = '') {
      require_once 'admin/Migrator.php';
      $migrator = new Migrator();
      $migrator->migrate();
      EZWP::putGenOption('last_iframe_src', 'index.php');
      EZWP::putGenOption('editing', 'Default');
      return;
    }

    function printAdminPage() {
      $lastSrc = EZWP::getGenOption('last_iframe_src');
      parent::printAdminPage();
      if (!empty($_REQUEST['target'])) {
        EZWP::putGenOption('last_iframe_src', $_REQUEST['target']);
      }
    }

    static function switchTheme() {
      $oldTheme = EZWP::getGenOption('theme');
      $newTheme = get_option('stylesheet');
      global $wpdb;
      $table = $wpdb->prefix . "ez_adsense_options";
      $sql = "INSERT IGNORE INTO $table (plugin_slug, theme, provider, optionset, name, value) SELECT plugin_slug, '$newTheme', provider, optionset, name, value FROM $table s WHERE theme = '$oldTheme'";
      if ($wpdb->query($sql) === false) {
        // A warning may be shown, but not being able to create the options
        // is not serious enough. They will become defaults anyway.
      }
      EZWP::putGenOption('theme', $newTheme);
    }

    function verifyDB() {
      global $wpdb;
      $table = $wpdb->prefix . "ez_adsense_options";
      if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
        $this->install();
      }
      if (!empty($_POST['ez_force_admin'])) {
        update_option('ez_force_admin', true);
      }
      $forceAdmin = get_option('ezadsense_force_admin');
      if ($forceAdmin) {
        update_option('ez_force_admin', true);
        delete_option('ezadsense_force_admin');
      }
    }

    function pluginActionLinks($links, $file) {
      if (dirname($file) == basename(__DIR__)) {
        $settings_link = "<a href='options-general.php?page=wp-google-adsense.php'>Settings</a>";
        array_unshift($links, $settings_link);
        if (empty($this->isPro) && !empty($this->slug)) {
          $buyLink = "<a href='http://buy.thulasidas.com/$this->slug' class='popup'><b style='color:red'>Go <i>Pro</i>!</b></a>";
          array_unshift($links, $buyLink);
        }
      }
      return $links;
    }

  }

  //End Class GoogleAdSense
}
else {
  $ezFamily = array("google-adsense/google-adsense.php",
      "google-adsense-lite/google-adsense.php",
      "google-adsense-pro/google-adsense.php",
      "easy-adsense/easy-adsense.php",
      "easy-adsense-pro/easy-adsense.php",
      "easy-adsense-lite/easy-adsense.php",
      "easy-adsense-lite/easy-adsense-lite.php",
      "adsense-now/adsense-now.php",
      "adsense-now-pro/adsense-now.php",
      "adsense-now-lite/adsense-now.php",
      "adsense-now-lite/adsense-now-lite.php");
  $ezActive = array();
  foreach ($ezFamily as $lite) {
    $ezKillLite = new EzKillLite($lite);
    $liteName = $ezKillLite->deny();
    if (!empty($liteName)) {
      $ezActive[$lite] = $liteName;
    }
  }
  if (count($ezActive) > 1) {
    $ezAdminNotice = '<ul>';
    foreach ($ezActive as $k => $p) {
      $ezAdminNotice .= "<li><code>$k</code>: <b>{$p}</b></li>\n";
    }
    $ezAdminNotice .= "</ul>";
    EzKillLite::$adminNotice .= '<div class="error"><p><b><em>Ads EZ Family of Plugins</em></b>: Please have only one of these plugins active.</p>' . $ezAdminNotice . 'Otherwise they will interfere with each other and work as the last one.</div>';
    add_action('admin_notices', array('EzKillLite', 'admin_notices'));
  }
}

if (class_exists("GoogleAdSense")) {
  $gAd = new GoogleAdSense();
  if (isset($gAd)) {
    if (method_exists($gAd, 'verifyDB')) {
      $gAd->verifyDB();
    }
    add_action('admin_menu', array($gAd, 'addAdminPage'));
    $gAd->addWidgets();
    $file = __DIR__ . "/{$gAd->slug}.php";
    register_activation_hook($file, array("GoogleAdSense", 'install'));
    add_action('switch_theme', array("GoogleAdSense", 'switchTheme'));
    // Make endpoints
    add_action('parse_request', array($gAd, 'parseRequest'));
    add_filter('plugin_action_links', array($gAd, 'pluginActionLinks'), -10, 2);
  }
}

require plugin_dir_path(__FILE__) . 'EzGA.php';
EzGA::doPluginActions();
