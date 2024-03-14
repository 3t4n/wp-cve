<?php
/**
 * @file
 * Support for adding intelligence to pages and processing form submissions
 * 
 * @author Tom McCracken <tomm@getlevelten.com>
 */

function intel_env_info_content() {
  $info = intel_env_info();
  $json_options = 0x0000;
  $print = '';
  if (!empty($_GET['print'])) {
    $print = $_GET['print'];
  }

  $json = json_encode($info, $json_options);
  $output = "<script>var intel_info_env = $json;</script>";

  if ($print == 'pretty') {
    $json_options = $json_options | JSON_PRETTY_PRINT;
    $json = json_encode($info, $json_options);
  }

  if ($print) {
    $output .= $json;
  }
  else {
    $output .= '<textarea cols="120" rows="20">' . $json . '</textarea>';
  }

  if ($print == 'pretty') {
    //$output = str_replace("\n", "\n<br>", $output);
  }

  return $output;
}

function intel_env_info($info = array()) {
  $info['php'] = intel_parse_phpinfo();
  $info['wp'] = array();
  $info['intel'] = array();

  $consts = array(
    'HOME_URL',
    'SITE_URL',
    'WPLANG',
  );
  foreach ($consts as $k) {
    $info['wp'][$k] = defined($k) ? constant($k) : '(not set)';
  }

  $globals = array(
    'wp_version',
    'wp_db_version',
  );
  foreach ($globals as $k) {
    $info['wp'][$k] = $GLOBALS[$k];
  }

  $info['wp']['get_plugins'] = get_plugins();
  $info['wp']['wp_get_theme'] = wp_get_theme();


  $consts = array(
    'INTEL_VER',
    'INTEL_DIR',
    'INTEL_URL',
  );
  foreach ($consts as $k) {
    $info['intel'][$k] = defined($k) ? constant($k) : '(not set)';
  }

  // create summary elements
  $info['_summary'] = array();
  $info['_summary']['PHP Version'] = isset($info['php']['Core']['PHP Version']) ? $info['php']['Core']['PHP Version'] : '(not set)';
  $info['_summary']['WP Version'] = isset($info['wp']['wp_version']) ? $info['wp']['wp_version'] : '(not set)';
  $info['_summary']['Intel Version'] = isset($info['intel']['INTEL_VER']) ? $info['intel']['INTEL_VER'] : '(not set)';

  return $info;
}

function intel_parse_phpinfo() {
  ob_start(); phpinfo(); $s = ob_get_contents(); ob_end_clean();
  $s = strip_tags($s, '<h2><th><td>');
  $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/', '<info>\1</info>', $s);
  $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/', '<info>\1</info>', $s);
  $t = preg_split('/(<h2[^>]*>[^<]+<\/h2>)/', $s, -1, PREG_SPLIT_DELIM_CAPTURE);
  $r = array(); $count = count($t);
  $p1 = '<info>([^<]+)<\/info>';
  $p2 = '/'.$p1.'\s*'.$p1.'\s*'.$p1.'/';
  $p3 = '/'.$p1.'\s*'.$p1.'/';
  for ($i = 1; $i < $count; $i++) {
    if (preg_match('/<h2[^>]*>([^<]+)<\/h2>/', $t[$i], $matchs)) {
      $name = trim($matchs[1]);
      $vals = explode("\n", $t[$i + 1]);
      foreach ($vals AS $val) {
        if (preg_match($p2, $val, $matchs)) { // 3cols
          $r[$name][trim($matchs[1])] = array(trim($matchs[2]), trim($matchs[3]));
        } elseif (preg_match($p3, $val, $matchs)) { // 2cols
          $r[$name][trim($matchs[1])] = trim($matchs[2]);
        }
      }
    }
  }
  return $r;
}
