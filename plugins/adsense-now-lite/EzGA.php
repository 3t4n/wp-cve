<?php
// Suppress errors on AJAX requests
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  error_reporting(E_ERROR | E_PARSE);
// CORS headers
  header("access-control-allow-origin: *", true);
  header("access-control-allow-methods: GET, POST, PUT, DELETE, OPTIONS", true);
}

if (!class_exists("EzGA")) {

  require_once 'EZWP.php';

  class EzGA extends EZWP {

    static $metaOptions = array();
    static $plgPrice = array('easy-adsense' => 7.95,
        'adsense-now' => 6.95,
        'google-adsense' => 9.45);
    static $border = '';
    static $kills = array('feed', 'page', 'sticky', 'home', 'front_page', 'category',
        'tag', 'archive', 'search', 'single', 'attachment');
    static $unSettable = array('Phone', 'Tablet', 'All', 'Mobile');
    static $noAds = false;
    static $noAdsReason = '';
    static $paras = array();

    static function isActive() {
      if (strpos(__FILE__, 'mu-plugins') !== false) {
        return true;
      }
      if (class_exists("GoogleAdSense")) {
        return true;
      }
      if (!function_exists('is_plugin_active')) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
      }
      $plgSlug = self::getPlgMode();
      $plgSlug = str_replace("-ultra", "", $plgSlug);
      $plugin = basename(__DIR__) . "/$plgSlug.php";
      if (is_plugin_active($plugin)) {
        return true;
      }
      if (is_plugin_active_for_network($plugin)) {
        return true;
      }
      return false;
    }

    static function isLoggedIn() {
      $isLoggedIn = false;
      if (function_exists('current_user_can')) {
        if (current_user_can('activate_plugins')) {
          $isLoggedIn = true;
        }
      }
      return $isLoggedIn;
    }

    static function doPluginActions() {
      $suspend_ads = self::getGenOption('suspend_ads');
      if (!empty($suspend_ads)) {
        return;
      }
      $plugin_slug = self::getSlug();
      require_once plugin_dir_path(__FILE__) . $plugin_slug . '-frontend.php';
    }

    static function urlExists($url) {//se passar a URL existe
      $c = curl_init();
      curl_setopt($c, CURLOPT_URL, $url);
      curl_setopt($c, CURLOPT_HEADER, 1); //get the header
      curl_setopt($c, CURLOPT_NOBODY, 1); //and *only* get the header
      curl_setopt($c, CURLOPT_RETURNTRANSFER, 1); //get the response as a string from curl_exec(), rather than echoing it
      curl_setopt($c, CURLOPT_FRESH_CONNECT, 1); //don't use a cached version of the url
      if (!curl_exec($c)) {
        return false;
      }
      else {
        return true;
      }
      //$httpcode=curl_getinfo($c,CURLINFO_HTTP_CODE);
      //return ($httpcode<400);
    }

    static function validate_url($url) {
      $format = "Use the format http[s]://[www].site.com[/file[?p=v]]";
      if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $text = "$format";
        return $text;
      }
      $pattern = '#^(http(?:s)?\:\/\/[a-zA-Z0-9\-]+(?:\.[a-zA-Z0-9\-]+)*\.[a-zA-Z]{2,6}(?:\/?|(?:\/[\w\-]+)*)(?:\/?|\/\w+\.[a-zA-Z]{2,4}(?:\?[\w]+\=[\w\-]+)?)?(?:\&[\w]+\=[\w\-]+)*)$#';
      if (!preg_match($pattern, $url)) {
        $text = "$format";
        return $text;
      }
      if (!self::urlExists($url)) {
        $text = "URL not accessible";
        return $text;
      }
      return true;
    }

    static function validate_email($s) {
      if (!filter_var($s, FILTER_VALIDATE_EMAIL)) {
        return "Bad email address";
      }
      return true;
    }

    static function validate_notNull($s) {
      $s = trim($s);
      if (empty($s)) {
        return "Null value not allowed";
      }
      return true;
    }

    static function validate_number($s) {
      if (!is_numeric($s)) {
        return "Need a number here";
      }
      return true;
    }

    static function validate_alnum($s) {
      $aValid = array('_', '-');
      $s = str_replace($aValid, '', $s);
      if (!ctype_alnum($s)) {
        return "Please use only letters, numbers, - and _";
      }
      return true;
    }

    // AJAX CRUD implementation. Create.
    static function create() { // creates a new DB record
      self::update();
    }

    // AJAX CRUD implementation. Delete.
    static function read() {
      // not implemented
    }

    // AJAX CRUD implementation. Update using wpdb->replace()
    static function update() {
      $posted_pk = $posted_name = $posted_value = $posted_validator = "";
      if (!EzGA::isLoggedIn()) {
        http_response_code(400);
        die("Please login before changing options!");
      }
      global $wpdb;
      if (empty($wpdb)) {
        http_response_code(400);
        die("Global variable wpdb not set!");
      }
      $table = $wpdb->prefix . "ez_adsense_options";
      $row = array();
      extract($_POST, EXTR_PREFIX_ALL, 'posted');
      if (empty($posted_pk)) {
        http_response_code(400);
        die("Empty primary key");
      }
      if (empty($posted_name)) {
        http_response_code(400);
        die("Empty name ($posted_name) in data");
      }
      if (!isset($posted_value)) { // Checkbox, unchecked
        $posted_value = 0;
      }
      if (is_array($posted_value)) { // Checkbox (from checklist), checked
        $posted_value = 1;
      }
      if (!empty($posted_validator)) { // a server-side validator is specified
        $fun = "validate_$posted_validator";
        if (method_exists('EzGA', $fun)) {
          $valid = self::$fun($posted_value);
        }
        else {
          http_response_code(400);
          die("Unknown validator ($posted_validator) specified");
        }
        if ($valid !== true) {
          http_response_code(400);
          die("$valid");
        }
      }
      $row['name'] = $posted_pk;
      $row['value'] = str_ireplace("_script", "script", $posted_value);
      foreach (array('plugin_slug', 'theme', 'provider', 'optionset') as $col) {
        $posted = "posted_$col";
        if (!empty($$posted)) {
          $row[$col] = $$posted;
        }
        else {
          $row[$col] = "All";
        }
      }
      $status = $wpdb->replace($table, $row);
      if ($status === false) {
        http_response_code(400);
        die("Database Replace/Insert Error");
      }
      http_response_code(200);
      exit();
    }

    // AJAX CRUD implementation. Delete.
    static function delete() {
      // not implemented
    }

    static function isAssoc($options) {
      $isAssoc = count(array_filter(array_keys($options), 'is_string')) > 0;
      return $isAssoc;
    }

    static function mkSelectSource($options) {
      $isAssoc = self::isAssoc($options);
      $source = "[";
      foreach ($options as $k => $o) {
        if ($isAssoc) {
          $source .= "{value: '$k', text: '$o'},";
        }
        else {
          $source .= "{value: '$o', text: '$o'},";
        }
      }
      $source .= "]";
      return $source;
    }

    static function getThemes($killCurrent = true) {
      global $wpdb;
      $table = $wpdb->prefix . "ez_adsense_options";
      $sql = "SELECT DISTINCT theme FROM $table ";
      $themsDB = $wpdb->get_results($sql);
      $themes = array();
      foreach ($themsDB as $t) {
        if ($t->theme != 'All') {
          $themes[] = $t->theme;
        }
      }
      $theme = array(get_option('stylesheet'));
      if ($killCurrent) {
        $themes = array_diff($themes, $theme);
      }
      $themes = array_values($themes);
      return $themes;
    }

    static function getOptionSets($settableOnly = false) {
      global $wpdb;
      $table = $wpdb->prefix . "ez_adsense_options";
      $plugin_slug = self::getSlug();
      $sql = "SELECT DISTINCT optionset FROM $table WHERE plugin_slug = '$plugin_slug'";
      $optionSetsDB = $wpdb->get_results($sql);
      $optionSets = array();
      foreach ($optionSetsDB as $t) {
        if ($t->optionset != 'All') {
          $optionSets[] = $t->optionset;
        }
      }
      if ($settableOnly) {
        $unSettable = self::$unSettable;
        $unSettable[] = self::getGenOption('optionset');
        $optionSets = array_diff($optionSets, $unSettable);
      }
      $optionSets = array_values($optionSets);
      return $optionSets;
    }

    static function setOptionset($name) {
      self::$options = self::getOptions($name);
      self::putGenOption('editing', $name);
      if (!in_array($name, self::$unSettable)) {
        self::putGenOption('optionset', $name);
      }
    }

    static function getAllOptions() {
      global $wpdb;
      $table = $wpdb->prefix . "ez_adsense_options";
      $sql = "SELECT name, value FROM $table WHERE  plugin_slug='All' AND theme='All' AND provider='All' AND optionset='All'";
      $row = $wpdb->get_results($sql);
      $options = array();
      if (!empty($row)) {
        foreach ($row as $r) {
          $options[$r->name] = $r->value;
        }
      }
      return $options;
    }

    static function getOptions($optionset = "", $provider = "AdSense") {
      $allOptions = self::getAllOptions();
      global $wpdb;
      $table = $wpdb->prefix . "ez_adsense_options";
      if (empty($optionset)) {
        if (is_admin()) {
          $optionset = self::getGenOption('editing');
        }
        else {
          $optionset = self::getGenOption('optionset');
        }
      }
      if (empty($optionset)) {
        $optionset = "Default";
        self::setOptionset($optionset);
      }
      $plugin_slug = self::getSlug();
      $lookup = array('google-adsense' => 'Google AdSense', 'easy-adsense' => 'Easy AdSense', 'adsense-now' => 'AdSense Now!');
      $plugin = $lookup[$plugin_slug];
      $theme = get_option('stylesheet');
      self::putGenOption('theme', $theme);
      $sql = "SELECT name,value FROM $table WHERE plugin_slug='$plugin_slug' AND theme='$theme' AND provider='$provider' AND optionset='$optionset'";
      $rows = $wpdb->get_results($sql);
      $defaultText = 'Please generate and paste your ad code here. If left empty, the ad location will be highlighted on your blog pages with a reminder to enter your code.';
      $options = compact('plugin', 'theme', 'provider', 'optionset', 'plugin_slug', 'defaultText');
      foreach ($rows as $row) {
        $options[$row->name] = $row->value;
      }
      self::$options = $options = array_merge($allOptions, $options);
      require "admin/$plugin_slug-options.php";
      $defaults = EzGA::getDefaults($ezOptions);
      self::$options = $options = array_merge($defaults, $options);
      return $options;
    }

    static function info($hide = true) {
      if (!function_exists('get_plugin_data')) {
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
      }
      $dir = plugin_dir_path(__FILE__);
      $slug = '';
      foreach (self::$plgPrice as $plg => $price) {
        if (strpos($dir, $plg) !== false) {
          $slug = $plg;
          break;
        }
      }
      if (empty($slug)) {
        $slug = 'easy-adsense';
      }
      $plugin_data = get_plugin_data("$dir$slug.php");
      $str = "{$plugin_data['Name']} V{$plugin_data['Version']}";
      if ($hide) {
        $str = "<!-- $str -->";
      }
      return $str;
    }

    static function getDefaults($ezOptions, $echo = false) {
      $defaults = array();
      $msg = '<pre>';
      foreach ($ezOptions as $k => $v) {
        if (isset($v['value'])) {
          $defaults[$k] = $v['value'];
        }
        else {
          $msg .= "No default for <code>$k</code>\n";
        }
      }
      $msg .= "</pre>";
      if ($echo) {
        echo $msg;
      }
      return $defaults;
    }

    static function putDefaults($ezOptions, $optionset = '', $force = false, $provider = "AdSense") {
      $row = array();
      foreach (array('plugin_slug', 'theme', 'provider', 'optionset') as $col) {
        $row[$col] = self::$options[$col];
      }
      if (!empty($optionset)) {
        $row['optionset'] = $optionset;
      }
      global $wpdb;
      $table = $wpdb->prefix . "ez_adsense_options";
      $defaults = self::getDefaults($ezOptions);
      foreach ($defaults as $name => $value) {
        if ($force || !isset(self::$options[$name])) {
          $row['name'] = $name;
          $row['value'] = $value;
          $wpdb->replace($table, $row);
        }
      }
    }

    static function renderOption($pk, $option) {
      $optionsDB = EzGA::$options;
      if (isset($optionsDB[$pk])) {
        $value = $optionsDB[$pk];
        $option['value'] = $value;
        if (!empty($option['options']) && is_array($option['options']) && self::isAssoc($option['options'])) {
          $option['value'] = $option['options'][$value];
        }
      }
      return self::renderRow($pk, $option);
    }

    static function renderOptionCell($pk, $option) {
      $optionsDB = EzGA::$options;
      if (isset($optionsDB[$pk])) {
        $value = $optionsDB[$pk];
        $option['value'] = $value;
        if (!empty($option['type']) && $option['type'] == 'select' &&
                !empty($option['options']) && is_array($option['options']) &&
                self::isAssoc($option['options'])
        ) {
          $option['key'] = $value;
          $option['value'] = $option['options'][$value];
        }
      }
      $name = $option['name'];
      if (!empty($option['type'])) {
        $type = $option['type'];
      }
      else {
        $type = 'text';
      }
      $helpAttr = self::renderHelpAttr($pk, $option);
      switch ($type) {
        case 'textarea':
          $value = self::renderOptionValue($pk, $option);
          $cell = "<div $helpAttr class='form-group ez-wide'><label>$name</label><span class='form-control' style='display:inline-block;height:120px;overflow-y:scroll'>$value</span></div>";
          break;
        case 'colorpicker': // render as a <div> with a colorpicker
          $value = self::renderOptionValue($pk, $option);
          $cell = "<div $helpAttr class='ez-row'><div class='col-lg-5' style='height:43px'><label class='ez-row' for='$pk-value'>$name</label></div><div class='col-lg-7'><div class='input-group colorpicker'>$value</div></div></div>";
          break;
        case 'colorpicker2': // render as a <div> with a wider colorpicker
          $option['type'] = 'colorpicker';
          $value = self::renderOptionValue($pk, $option);
          $cell = "<div $helpAttr class='ez-row'><div class='col-lg-3' style='height:43px'><label class='ez-row' for='$pk-value'>$name</label></div><div class='col-lg-9'><div class='input-group colorpicker'>$value</div></div></div>";
          break;
        case 'radio': // render as <tr>
          $value = self::renderOptionValue($pk, $option);
          $cell = "<tr $helpAttr><td>$name</td>";
          foreach ($option['options'] as $k => $o) {
            if (!empty($optionsDB[$pk]) && $optionsDB[$pk] !== 0 && $optionsDB[$pk] == $o) {
              $checked = "checked='checked'";
            }
            else {
              $checked = '';
            }
            $cell .= "<td class='center-text'><input $checked type='radio' name='$pk' id='$pk-$k' data-value='$o'></td>";
          }
          $cell .= "</tr>\n";
          break;
        case 'radio2': // render as a line
          if (empty($option['noCenter'])) {
            $class = "class='center-block'";
          }
          else {
            $class = "";
          }
          $cell = "<div $class $helpAttr><label>$name</label><div style='padding:10px;border: 1px solid #ccc;'>";
          foreach ($option['options'] as $o => $v) {
            if (!empty($optionsDB[$pk]) && $optionsDB[$pk] !== 0 && $optionsDB[$pk] == $o) {
              $checked = "checked='checked'";
            }
            else {
              $checked = '';
            }
            $cell .= "<span style='padding-right:10px;display:inline-block'><input $checked type='radio' name='$pk' id='$pk-$o' data-value='$o'> $v</span>";
          }
          $cell .= "</div></div>\n";
          break;
        case 'checkbox2': // render in two <td>s
          $option['type'] = 'checkbox';
          $value = self::renderOptionValue($pk, $option);
          $cell = "<td $helpAttr>$name</td><td class='center-text'>$value</td>";
          break;
        default:
          if (empty($option['noCenter'])) {
            $center = "center-block";
          }
          else {
            $center = "";
          }
          $value = self::renderOptionValue($pk, $option);
          $cell = "<div $helpAttr class='form-group $center'><label>$name</label><span class='form-control'>$value</span></div>";
      }
      return $cell;
    }

    static function renderHelpAttr($pk, $option, $tip = false) {
      $placement = $type = $dataMode = $name = $more_help = $help = "";
      if (is_array($option)) {
        extract($option);
      }
      if (empty($placement)) {
        if ($dataMode == 'popup' || $type == 'checkbox') {
          $placement = 'bottom';
        }
        else {
          $placement = 'top';
        }
      }
      if (!empty($more_help)) {
        $clickHelp = "class='btn-help'";
      }
      else {
        $clickHelp = '';
      }
      if ($tip) {
        return "data-toggle='tooltip' data-placement='top' title='$name<br>$help'p";
      }
      else {
        return "data-content='$help' data-help='$more_help' data-toggle='popover' data-placement='$placement' data-trigger='hover' title='$name' $clickHelp";
      }
    }

    static function renderOptionHelp($pk, $option, $size = "1.5em") {
      $type = $name = $more_help = $help = "";
      if (is_array($option)) {
        extract($option);
      }
      if ($type == 'hidden') {
        $tr = '';
        return $tr;
      }
      if (!empty($more_help)) {
        $clickHelp = "class='btn-help'";
      }
      else {
        $clickHelp = '';
      }
      $helpAttr = self::renderHelpAttr($pk, $option);
      $str = "<a style='font-size:$size' $helpAttr><i class='glyphicon glyphicon-question-sign blue'></i></a>\n";
      return $str;
    }

    static function renderRow($pk, $option) {
      $type = $name = "";
      if (is_array($option)) {
        extract($option);
      }
      if ($type == 'hidden') {
        $str = '';
        return $str;
      }
      $str = "<tr><td>$name</td>";
      $str .= "<td>"
              . self::renderOptionValue($pk, $option)
              . "</td><td class='center-text'>"
              . self::renderOptionHelp($pk, $option)
              . "</td></tr>\n";
      return $str;
    }

    static function renderOptionValue($pk, $option, $dbValue = "") {
      $type = 'text';
      $dataMode = "data-mode='inline'";
      $options = array(); // for select and radio controls
      $value = $more_help = $dataValue = $dataTpl = $dataSource = $slug = $validator = $button = $reveal = $reload = $class = "";
      if (is_array($option)) {
        extract($option);
      }
      if ($dataMode == 'popup') {
        $dataMode = "data-mode='popup'";
      }
      else {
        $dataMode = "data-mode='inline'";
      }
      if (!empty($dbValue)) {
        $value = $dbValue;
      }
      if ($type == 'hidden') {
        $str = '';
        return $str;
      }
      $dataType = "data-type='$type'";
      if (!empty($more_help)) {
        $clickHelp = "class='btn-help'";
      }
      else {
        $clickHelp = '';
      }
      if (!empty($reload)) {
        $class .= "xeditReload ";
      }
      if (empty($slug)) {
        $slug = "$pk-value";
      }
      switch ($type) {
        case 'radio':
          return '';
        case 'no-edit':
          $class .= "black";
          break;
        case 'checkbox' :
          $class .= "xedit-checkbox";
          $dataType = "data-type='checklist'";
          $dataMode = '';
          $dataValue = "data-value='$value'";
          if ($value) {
            $class .= ' btn-sm btn-success';
            $value = "";
          }
          else {
            $class .= ' btn-sm btn-danger';
            $value = "";
          }
          break;
        case 'select':
          $class .= "xedit";
          $dataType = "data-type='select'";
          if (!empty($key)) {
            $dataValue = "data-value='$key'";
          }
          else {
            $dataValue = "data-value='$value'";
          }
          $dataSource = 'data-source="' . self::mkSelectSource($options) . '"';
          break;
        case 'file': // special case, return from here
          $type = '';
          $class .= 'red';
          $value = "<input data-pk='$pk' id='fileinput' type='file' class='file' multiple=true data-show-preview='false' data-show-upload='false'>";
          break;
        case 'colorpicker':
          $str = "<input id='$slug' class='form-control' type='text' value='$value' /><span class='input-group-addon' style='width:50%'><i style='width:80%'></i></span>";
          break;
        case 'submit':
        case 'button':
          $class .= "btn btn-primary btn-ez";
          break;
        case 'textarea':
          $str = "<a href='#' class='xedit' data-type='textarea' $dataMode data-pk='$pk' data-name='value'>" . htmlspecialchars(stripslashes($value)) . "</a>";
          break;
        case 'dbselect':
        case 'dbeditableselect':
        case 'editableselect':
        case 'text':
        default :
          $class .= "xedit";
          break;
      }
      if (!empty($validator)) {
        $valid = "data-validator='$validator'";
      }
      else {
        $valid = "";
      }
      if (!empty($button)) {
        $fun = "proc_$reveal";
        $options = self::$options;
        if (!empty($options[$reveal])) {
          $revealOption = $options[$reveal];
        }
        else {
          $revealOption = '';
        }
        if (method_exists("EzGA", $fun)) {
          $dataReveal = @self::$fun($revealOption);
        }
        else {
          $dataReveal = "data-value='$revealOption' class='btn-sm btn-success reveal'";
        }
        $reveal = "<a href='#' style='float:right' $dataReveal>$button</a>";
      }
      else {
        $reveal = '';
      }
      if (empty($str)) {
        $str = "<input id='$slug' style='display:none'><a class='$class' data-name='$slug' data-pk='$pk' $dataType $dataMode $dataValue $dataTpl $dataSource $valid>$value $reveal</a>\n";
      }
      return $str;
    }

    static function isUpdateAvailable() { // not ready yet
      return false;
    }

    static function randString($len = 32) {
      $chars = 'abcdefghijklmnopqrstuvwxyz';
      $chars .= strtoupper($chars) . '0123456789';
      $charLen = strlen($chars) - 1;
      $string = '';
      for ($i = 0; $i < $len; $i++) {
        $pos = rand(0, $charLen);
        $string .= $chars[$pos];
      }
      return $string;
    }

    static function flashMsg($msg, $class, $noflash = false, $noPre = false) {
      if ($noflash) {
        $fun = "show";
      }
      else {
        $fun = "flash";
      }
      $cleaned = str_replace(array("\n"), array('\n'), $msg);
      if (!empty($cleaned)) {
        $msg = htmlspecialchars($cleaned);
        if (!$noPre) {
          $msg = "<pre>$msg</pre>";
        }
        echo '<script>$(document).ready(function() {' .
        $fun . $class . '("' . $msg . '");
        });
        </script>';
      }
    }

    static function flashError($msg, $noPre = false) {
      self::flashMsg($msg, 'Error', false, $noPre);
    }

    static function showError($msg, $noPre = false) {
      self::flashMsg($msg, 'Error', true, $noPre);
    }

    static function flashWarning($msg, $noPre = false) {
      self::flashMsg($msg, 'Warning', false, $noPre);
    }

    static function showWarning($msg, $noPre = false) {
      self::flashMsg($msg, 'Warning', true, $noPre);
    }

    static function flashSuccess($msg, $noPre = false) {
      self::flashMsg($msg, 'Success', false, $noPre);
    }

    static function showSuccess($msg, $noPre = false) {
      self::flashMsg($msg, 'Success', true, $noPre);
    }

    static function flashInfo($msg, $noPre = false) {
      self::flashMsg($msg, 'Info', false, $noPre);
    }

    static function showInfo($msg, $noPre = false) {
      self::flashMsg($msg, 'Info', true, $noPre);
    }

    static function toggleMenu($header) {
      if (!empty($_SERVER["HTTP_REFERER"])) {
        $referer = $_SERVER["HTTP_REFERER"];
      }
      else {
        $referer = '';
      }
      $standAlone = (strpos($referer, 'wp-admin/options-general.php') === false) && !isset($_REQUEST['inframe']);
      if (!$standAlone) {
        $search = array('<div class="col-sm-2 col-lg-2">',
            '<div class="sidebar-nav">',
            '<div class="nav-canvas">',
            '<ul class="nav nav-pills nav-stacked main-menu">',
            '<li class="accordion">',
            '<ul class="nav nav-pills nav-stacked">',
            '<a href="#">',
            '<div id="content" class="col-lg-10 col-sm-10">');
        $replace = array('<div>',
            '<div>',
            '<div>',
            '<ul class="nav nav-pills main-menu">',
            '<li class="dropdown">',
            '<ul class="dropdown-menu">',
            '<a href="#" data-toggle="dropdown">',
            '<div id="content" class="col-lg-12 col-sm-12">');
        $header = str_replace($search, $replace, $header);
      }
      return $header;
    }

    static function showService() {
      $select = rand(0, 4);
      echo "<div class='center-block' style='margin-left:10px;'><a href='http://www.thulasidas.com/professional-php-services/' target='_blank' class='popup-long' title='Professional Services' data-content='The author of this plugin may be able to help you with your WordPress or plugin customization needs and other PHP related development. Find a plugin that almost, but not quite, does what you are looking for? Need any other professional PHP/jQuery dev services? Click here!' data-toggle='popover' data-trigger='hover' data-placement='left'><img src='img/svcs/300x250-0$select.jpg' style='border:0' alt='Professional Services from the Plugin Author' /></a></div><div class='clearfix'></div>";
    }

    // Frontend functions

    static function preFilter($content, $isWidget = false) {
      $plgName = self::getPlgName();
      if (self::$noAds) {
        $reason = self::$noAdsReason;
        return $content . " <!-- $plgName: PreFiltered - NoAds [ $reason] -->\n";
      }
      $killed = self::isKilled();
      if (!empty($killed)) {
        $reason = "is Killed - $killed. ";
        self::$noAds = true;
        self::$noAdsReason .= $reason;
        return $content . " <!-- $plgName: $reason -->\n";
      }
      if (empty(self::$options['allow_feeds']) && is_feed()) {
        $reason = 'is Feed and not allowed. ';
        self::$noAds = true;
        self::$noAdsReason .= $reason;
        return $content . " <!-- $plgName: $reason -->\n";
      }

      $metaOptions = self::getMetaOptions();
      if (strpos($content, "<!--noadsense-->") !== false) {
        $reason = 'Unfiltered - suppressed by noadsense comment. ';
        self::$metaOptions['adsense'] = 'no';
        self::$noAds = true;
        self::$noAdsReason .= $reason;
        return $content . " <!-- $plgName: $reason -->\n";
      }
      if (isset($metaOptions['adsense']) && $metaOptions['adsense'] == 'no') {
        $reason = 'Unfiltered - suppressed by meta option adsense = no. ';
        self::$noAds = true;
        self::$noAdsReason .= $reason;
        return $content . " <!-- $plgName: $reason -->\n";
      }
      if (self::isBanned()) {
        $reason = 'IP is in Banned IP list. ';
        self::$noAds = true;
        self::$noAdsReason .= $reason;
        return $content . " <!-- $plgName: $reason -->\n";
      }
      if (!in_the_loop() && !$isWidget && !is_singular() && !is_front_page()) {
        $reason = 'WP is not in the loop. ';
        self::$noAds = true;
        self::$noAdsReason .= $reason;
        return $content . " <!-- $plgName: $reason -->\n";
      }

      self::cacheContent($content);
      if (!self::isSafe()) {
        self::$noAds = true;
        $reason = 'Unfiltered - Content is not Safe. ';
        self::$noAdsReason .= $reason;
        return $content . " <!-- $plgName: $reason -->\n";
      }
      return $content . " <!-- $plgName: Filtered -->\n";
    }

    static function showUnreal($print = true) {
      $unreal = "<div style='text-align:center;margin-left:auto;margin-right:auto;font-size:0.6em'><a href='http://www.thulasidas.com/adsense/' target='_blank' title='The simplest way to put AdSense to work for you!'> Easy Plugin for AdSense</a> by <a href='http://www.Thulasidas.com/' target='_blank' title='Unreal Blog proudly brings you Easy Plugin for AdSense'>Unreal</a></div>";
      if ($print) {
        echo $unreal;
      }
      else {
        return $unreal;
      }
    }

    static function mkRedBox($w, $h, $p) {
      if (!current_user_can('activate_plugins')) {
        return;
      }
      if (!empty(self::$options['suppressBoxes'])) {
        return;
      }
      $line1 = "Your ads will be inserted here by";
      $line2 = self::getPlgName() . ".";
      $option1 = "<u title='Go to your plugin admin page to paste your ad code from your provider or enter your account details'>Setup your Ads</u>";
      $option2 = "<u title='Use the options in the Ad Alignment panel to suppress this ad slot'>Suppress this ad slot</u>";
      $option3 = "<u title='Use the Pro option to suppress placement boxes'>Suppress Placement Boxes</u>";
      $line3 = "Please go to the plugin admin page to ";
      $area = $h * $w;
      if ($h > 400) {
        $or = "<br><br>OR<br><br>";
        $br = "<br><br>";
      }
      else if ($area > 300 * 250) {
        $or = "<br>OR<br>";
        $br = "<br><br>";
      }
      else if ($h > 240) {
        $or = " or ";
        $br = "<br>";
      }
      else {
        $or = " or ";
        $br = " ";
      }
      if ($area < 468 * 60) {
        $or = $line3 = $option1 = $option2 = $option3 = "";
      }
      else if ($area < 728 * 90) {
        $line3 = "";
      }
      if ($area == 300 * 250) { // default ads
        $line4 = "<br><br><b>This red box is shown only when logged on as Admin</b>";
      }
      else {
        $line4 = "";
      }
      $ret = "<div style='width:{$w}px;height:{$h}px;border:1px solid red;'><div style='padding:{$p}px;text-align:center;font-family:arial;font-size:8pt;'>$line1 $br $line2 <br> $br $line3 $br $option1 $or $option2 $or $option3.$line4</div></div>";
      return $ret;
    }

    static function handleDefaultText($text, $key = '300x250') {
      $ret = $text;
      if ($text == self::$options['defaultText'] || strlen(trim($text)) == 0) {
        $x = strpos($key, 'x');
        $w = substr($key, 0, $x);
        $h = substr($key, $x + 1);
        $p = (int) (min($w, $h) / 6);
        $ret = self::mkRedBox($w, $h, $p);
      }
      return $ret;
    }

    static function getMetaOptions() {
      if (empty(self::$metaOptions)) {
        global $post;
        if (is_object($post)) {
          $postID = $post->ID;
        }
        else {
          global $wp;
          $url = home_url(add_query_arg(array(), $wp->request));
          $postID = url_to_postid($url);
        }
        $metaOptions = array();
        if (!empty($postID)) {
          $postMeta = get_post_meta($postID);
          $lookup = array('adsense' => 'adsense',
              'show_leadin' => 'adsense-top',
              'show_top' => 'adsense-top',
              'show_midtext' => 'adsense-middle',
              'show_middle' => 'adsense-middle',
              'show_leadout' => 'adsense-bottom',
              'show_bottom' => 'adsense-bottom',
              'show_widget' => 'adsense-widget',
              'title_gsearch' => 'adsense-search',
              'show_lu' => 'adsense-linkunits');
          foreach ($lookup as $optKey => $metaKey) {
            $metaStyle = $metaOptions[$optKey] = '';
            if (!empty($postMeta[$metaKey])) {
              $metaStyle = strtolower($postMeta[$metaKey][0]);
            }
            else if (!empty(self::$options[$optKey])) {
              $metaStyle = self::$options[$optKey];
            }
            $style = $metaStyle; // if the option contains CSS directive
            switch ($metaStyle) {
              case 'left';
                $style = 'text-align:left';
                break;
              case 'leftfloat';
              case 'floatleft';
              case 'leftfloat';
                $style = 'float:left;display:block';
                break;
              case 'center';
                $style = 'text-align:center';
                break;
              case 'right';
                $style = 'text-align:right';
                break;
              case 'rightfloat';
              case 'floatright';
              case 'rightfloat';
                $style = 'float:right;display:block';
                break;
              default:
                $style = $metaStyle;
                break;
            }
            $metaOptions[$optKey] = $style;
          }
        }
        self::$metaOptions = $metaOptions;
      }
      return self::$metaOptions;
    }

    static function findParas($content) {
      if (!empty(self::$paras)) {
        return self::$paras;
      }
      $paras = array();
      $paraMarker = "<p";
      if (stripos($content, "<p") === false) {
        $paraMarker = "<br";
      }
      $lastpos = 0;
      do {
        $paras[] = $lastpos;
        $lastpos = stripos($content, $paraMarker, $lastpos + 1);
      } while ($lastpos !== false);
      self::$paras = $paras;
      return $paras;
    }

    static function getSplit($content) {
      $paras = EzGA::findParas($content);
      $split = 0;
      if (!empty($paras)) {
        $nParas = count($paras);
        if (!empty(EZGA::$options['para_split'])) {
          if ($nParas > EZGA::$options['para_split']) {
            $split = $paras[EZGA::$options['para_split']];
          }
          else {
            $split = $nParas;
          }
        }
        else {
          $split = $paras[floor($nParas / 2)];
        }
      }
      return $split;
    }

    static function mkBorder() {
      if (!empty(self::$options['show_borders']) && empty(self::$border)) {
        self::$border = 'border:#' . self::$options['border_normal'] .
                ' solid ' . self::$options['border_width'] . 'px;" ' .
                ' onmouseover="this.style.border=\'#' . self::$options['border_color'] .
                ' solid ' . self::$options['border_width'] . 'px\'" ' .
                'onmouseout="this.style.border=\'#' . self::$options['border_normal'] .
                ' solid ' . self::$options['border_width'] . 'px\'';
      }
      return self::$border;
    }

    static function isKilled() {
      foreach (self::$kills as $k) {
        $fn = "is_$k";
        if (!empty(self::$options["kill_$k"]) && $fn()) {
          return $k;
        }
      }
      return EzGAPro::isKilled();
    }

  }

}

EzGA::$isPro = EzGA::isPro();

// For 4.3.0 <= PHP <= 5.4.0
if (!function_exists('http_response_code')) {

  function http_response_code($newcode = NULL) {
    static $code = 200;
    if ($newcode !== NULL) {
      if (!headers_sent()) {
        header('X-PHP-Response-Code: ' . $newcode, true, $newcode);
        $code = $newcode;
      }
    }
    return $code;
  }

}

// To prevent warning notices from date()
if (!ini_get('date.timezone')) {
  date_default_timezone_set('GMT');
}

// Extra local settings, if any.
// Visit http://support.thulasidas.com if you need help with this feature.
if (file_exists(__DIR__ . '../ez-local.php')) { // in root or plugin folder
  include_once __DIR__ . '../ez-local.php';
}
else if (defined('ABSPATH')) {
  if (file_exists(ABSPATH . "../ez-local.php")) { // in blogroot parent
    include_once ABSPATH . "../ez-local.php";
  }
  else if (file_exists(ABSPATH . "ez-local.php")) { // in blogroot
    include_once ABSPATH . "ez-local.php";
  }
}