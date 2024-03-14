<?php

class Migrator {

  var $table;

  function __construct() {
    global $wpdb;
    $this->table = $wpdb->prefix . "ez_adsense_options";
  }

  function getAttr($obj, $attr) {
    $ret = '';
    $matches = array();
    preg_match("/'$attr' => '(.*)',/", $obj, $matches); // extract strings
    if (!empty($matches[1]) && $matches[1] !== 'NULL') {
      $ret = $matches[1];
    }
    else {
      preg_match("/'$attr' => (.*),/", $obj, $matches); // extract the rest
      if (!empty($matches[1]) && $matches[1] !== 'NULL') {
        $ret = $matches[1];
      }
    }
    if (!empty($ret)) {
      if ($ret == 'NULL' || $ret == '\'\'') {
        $ret = '';
      }
    }
    return $ret;
  }

  static function ltrim($p) {
    return ltrim($p, '-');
  }

  static function add_($p) {
    return $p . "_";
  }

  function decodeEzAPIOption($o) {
    $oName = $o['option_name'];
    $plugins = array('ezAPI--' => 'unknown',
        'ezAPI-Easy-Ads-' => 'easy-ads',
        'ezAPI-Easy-Ads-Lite-' => 'easy-ads',
        'ezAPI-Easy-Chitika-' => 'easy-chitika',
        'ezAPI-Easy-Chitika1-' => 'easy-chitika',
        'ezAPI-Easy-Chitika-Lite-' => 'easy-chitika',
        'ezAPI-Google-Adsense-' => 'google-adsense',
        'ezAPI-Google-Adsense-Lite-' => 'google-adsense');
    $tabs = array("-AdSense", "-BidVertiser", "-Chitika", "-Clicksor", "-Admin");
    $providers = array_map(array('Migrator', 'ltrim'), $tabs);
    $prefixes = array_map(array('Migrator', 'add_'), $providers);
    $search = array_merge(array_keys($plugins), $tabs);
    $theme = str_ireplace($search, "", $oName);
    // $plugins = str_ireplace($tabs, "", $plugins);
    foreach ($plugins as $k => $p) {
      if (strpos($oName, $k) !== false) {
        $plugin_slug = $p;
        break;
      }
    }
    $provider = 'unknown';
    $optionset = 'Imported';
    foreach ($providers as $p) {
      if (strpos($oName, $p) !== false) {
        $provider = $p;
        break;
      }
    }
    if ($provider == 'Admin') {
      $provider = 'All';
    }
    $oldOptionValues = unserialize($o['option_value']);
    $oldOptions = array();
    foreach ($oldOptionValues as $k => $v) {
      $obj = var_export($v, true);
      $value = $this->getAttr($obj, 'value');
      if ($value == 'true') {
        $value = '1';
      }
      if ($value == 'false') {
        $value = '';
      }
      $name = $this->getAttr($obj, 'name');
      $name = str_replace($prefixes, "", $name);
      $oldOptions[] = compact('plugin_slug', 'theme', 'provider', 'optionset', 'name', 'value');
    }
    return $oldOptions;
  }

  function decodeOneOption($o) {
    $oName = $o['option_name'];
    $plugins = array("adsNow" => "adsense-now", "ezAdSense" => "easy-adsense");
    $search = array_keys($plugins);
    $theme = str_replace($search, "", $oName);
    foreach ($plugins as $k => $p) {
      if (strpos($oName, $k) !== false) {
        $plugin_slug = $p;
        break;
      }
    }
    $oldOptionValues = unserialize($o['option_value']);
    $oldOptions = array();
    $provider = 'AdSense';
    $optionset = 'Imported';
    foreach ($oldOptionValues as $name => $value) {
      if ($value == 'true') {
        $value = '1';
      }
      if ($value == 'false') {
        $value = '';
      }
      $oldOptions[] = compact('plugin_slug', 'theme', 'provider', 'optionset', 'name', 'value');
    }
    return $oldOptions;
  }

  function decodeOptions($options, $ezAPI = false) {
    $ret = array();
    foreach ($options as $o) {
      if ($ezAPI) {
        $ret[] = $this->decodeEzAPIOption($o);
      }
      else {
        $ret[] = $this->decodeOneOption($o);
      }
    }
    return $ret;
  }

  function insert($rows, $optionset = "") {
    global $wpdb;
    $query = "INSERT INTO $this->table
        ( plugin_slug, theme, provider, optionset, name, value )
          VALUES ( %s, %s, %s, %s, %s, %s )
          ON DUPLICATE KEY UPDATE plugin_slug=plugin_slug";
    $count = 0;
    $error = 0;
    foreach ($rows as $r) {
      if (!empty($optionset)) {
        $r['optionset'] = $optionset;
      }
      $sql = $wpdb->prepare($query, $r);
      if ($wpdb->query($sql, $r) !== false) {
        ++$count;
      }
      else {
        ++$error;
      }
    }
    return array($count, $error);
  }

  function migrateOne($plg, $pattern) {
    global $wpdb;
    echo "<pre>";
    echo "Migrating old <strong>$plg</strong> options...\n";
    $sql = "SELECT option_name, option_value FROM {$wpdb->prefix}options where option_name LIKE '$pattern%'";
    $data = $wpdb->get_results($sql, ARRAY_A);
    $ezAPI = $pattern === 'ezAPI-';
    $ezAPIOptions = $this->decodeOptions($data, $ezAPI);
    $rowCount = $errCount = 0;
    foreach ($ezAPIOptions as $rowSet) {
      list($count, $error) = $this->insert($rowSet);
      $rowCount += $count;
      $errCount += $error;
      $this->insert($rowSet, "Default");
    }
    $total = $rowCount + $errCount;
    echo "Migrated $total rows. Success: $rowCount. Errors: $errCount";
    echo "</pre>";
  }

  function createDB() {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();
    $table = $wpdb->prefix . "ez_adsense_options";
    $sql = "CREATE TABLE IF NOT EXISTS {$table} (
  id int(10) unsigned NOT NULL auto_increment,
  created TIMESTAMP DEFAULT NOW(),
  plugin_slug varchar(255) DEFAULT '' NOT NULL,
  theme varchar(255) DEFAULT '' NOT NULL,
  provider varchar(255) DEFAULT '' NOT NULL,
  optionset varchar(255) DEFAULT 'Default' NOT NULL,
  name varchar(255) DEFAULT '' NOT NULL,
  value text NOT NULL,
  PRIMARY KEY id (id),
  UNIQUE KEY option_id (plugin_slug(32), theme(32), optionset(32), name(32))
) $charset;";
    $wpdb->query($sql);
    $sql = "ALTER TABLE {$table}
   ALTER COLUMN optionset SET DEFAULT 'Default',
   ALTER COLUMN name SET DEFAULT ''";
    $wpdb->query($sql);
  }

  function migrate($verbose = false) {
    ob_start();
    $this->createDB();
    $this->migrateOne("Google AdSense and Easy Ads", "ezAPI-");
    $this->migrateOne("AdSense Now!", "adsNow");
    $this->migrateOne("Easy AdSense", "ezAdSense");
    $output = ob_get_clean();
    if ($verbose) {
      echo $output;
    }
    return;
  }

}
