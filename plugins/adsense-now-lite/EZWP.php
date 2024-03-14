<?php

if (!class_exists('EZWP')) {

  require_once 'EzGAPro.php';

  class EZWP extends EzGAPro {

    static $isPro = false;
    static $strPro = 'Lite';
    static $pluginModes = array('adsense-now' => 'AdSense Now!',
        'google-adsense' => 'Google AdSense',
        'easy-adsense' => 'Easy AdSense',
        'ajax-adsense' => 'AJAX AdSense');

    static function isPro() {
      $file = __DIR__ . "/admin/pro.php";
      self::$isPro = file_exists($file);
      return self::$isPro;
    }

    static function getPlgMode($dir = "") {
      $pluginModes = self::$pluginModes;
      foreach ($pluginModes as $p => $name) {
        if (strpos(__FILE__, $p) !== false) {
          $mode = $p;
          break;
        }
      }
      $required = array('../%s-frontend.php', '%s-admin.php', '%s-options.php');
      $pwd = getcwd();
      if (empty($dir)) {
        $dir = __DIR__ . "/admin";
      }
      chdir($dir);
      if ($mode == "ajax-adsense") {
        $mode = "easy-adsense";
      }
      foreach ($required as $format) {
        $file = sprintf($format, $mode);
        if (!file_exists($file)) {
          trigger_error("stack trace");
          die("Required file <code>{$file}</code> not found in <code>$dir</code>.<br> Please reinstall the plugin.");
        }
      }
      if ($mode == 'google-adsense') {
        $ultra = true;
        unset($pluginModes['ajax-adsense']);
        foreach ($pluginModes as $p => $name) {
          foreach ($required as $format) {
            $file = sprintf($format, $p);
            if (!file_exists($file)) {
              $ultra = false;
              break 2;
            }
          }
        }
      }
      else {
        $ultra = false;
      }
      if ($ultra) {
        $mode = 'google-adsense-ultra';
      }
      chdir($pwd);
      return $mode;
    }

    static function getPlgName($dir = "") {
      $mode = self::getPlgMode($dir);
      if (strpos($dir, 'ajax-adsense') !== false) {
        $mode = 'ajax-adsense';
      }
      if ($mode == 'google-adsense-ultra') {
        self::$strPro = 'Ultra';
        $name = 'Google AdSense Ultra';
      }
      else {
        $name = self::$pluginModes[$mode];
        if (self::isPro()) {
          self::$strPro = 'Pro';
          $name .= ' Pro';
        }
        else {
          self::$strPro = 'Lite';
          $name .= ' Lite';
        }
      }
      return $name;
    }

    static function getProName($dir = "") {
      $mode = self::getPlgMode($dir);
      $proNames = array('adsense-now' => 'AdSense Now! Pro',
          'google-adsense' => 'Google AdSense Ultra',
          'easy-adsense' => 'Easy AdSense Pro');
      if (!empty($proNames[$mode])) {
        return $proNames[$mode];
      }
      else {
        return 'Google AdSense Ultra';
      }
    }

    static function getGenOption($name) {
      global $wpdb;
      $table = $wpdb->prefix . "ez_adsense_options";
      $sql = "SELECT value FROM $table WHERE name='$name' AND plugin_slug='All' AND theme='All' AND provider='All' AND optionset='All'";
      $row = $wpdb->get_results($sql);
      if (!empty($row)) {
        $row = $row[0];
        $value = $row->value;
      }
      else {
        $value = '';
      }
      return $value;
    }

    static function getSlug($dir = "") {
      $plugin_slug = self::getPlgMode($dir);
      if ($plugin_slug == 'google-adsense-ultra') {
        $plugin_slug = self::getGenOption('plugin_slug');
        if (empty($plugin_slug)) {
          $candidates = array('google-adsense', 'easy-adsense', 'adsense-now');
          foreach ($candidates as $c) {
            if (stripos($dir, $c) !== false) {
              $plugin_slug = $c;
              break;
            }
          }
        }
        if (empty($plugin_slug)) {
          $plugin_slug = 'google-adsense';
        }
        self::putGenOption('plugin_slug', $plugin_slug);
      }
      return $plugin_slug;
    }

    static function putGenOption($name, $value) {
      global $wpdb;
      $table = $wpdb->prefix . "ez_adsense_options";
      $row['name'] = $name;
      $row['value'] = $value;
      foreach (array('plugin_slug', 'theme', 'provider', 'optionset') as $col) {
        $row[$col] = "All";
      }
      $status = $wpdb->replace($table, $row);
      return $status;
    }

  }

}
