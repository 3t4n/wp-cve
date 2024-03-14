<?php
/**
 * @file
 * Support for Intelligence system
 *
 * @author Tom McCracken <tomm@getlevelten.com>
 */



function intel_get_needed_updates($plugin_un = '') {

  $system_info = intel()->system_info();

  $system_data = get_option('intel_system', array());

  $system_meta = get_option('intel_system_meta', array());

  // originally schema_version for intel was stored in intel_system_meta.
  // Extended update management moved it to intel_system.
  // Check if old settings still exists and if so migrate it to new format
  if (!empty($system_meta['schema_version'])) {
    if (!isset($system_data['intel'])) {
      $system_data['intel'] = array(
        'plugin_version' => INTEL_VER,
      );
    }
    $system_data['intel']['schema_version'] = $system_meta['schema_version'];
    unset($system_meta['schema_version']);
    update_option('intel_system_meta', $system_meta);
    update_option('intel_system', $system_data);
  }

  $updates = array();
  // if schema
  $schema_ver_exists = 0;
  foreach ($system_info as $p_un => $p_info) {
    if (!empty($plugin_un) && ($p_un != $plugin_un)) {
      continue;
    }
    $p_system_data = array();
    if (!empty($system_data[$p_un])) {
      $p_system_data = $system_data[$p_un];
    }

    // check if updates file found and include it
    if(!intel_include_update_file($p_un, $system_info)) {
      continue;
    }

    $schema_ver = !empty($p_info['update_start']) ? $p_info['update_start'] : 1000;
    if (!empty($p_system_data['schema_version'])) {
      $schema_ver = $p_system_data['schema_version'];
      $schema_ver_exists = 1;
    }

    for ($i = $schema_ver + 1; $i < 2000; $i++) {
      $update = array(
        'info' => array(),
      );
      if (!empty($p_info['update_callback_class'])) {
        if (!is_callable(array($p_info['update_callback_class'], "update_$i"))) {
          break;
        }
        $update['callback'] = array($p_info['update_callback_class'], "update_$i");
        if (is_callable(array($p_info['update_callback_class'], "update_{$i}_info"))) {
          $update['info'] = call_user_func(array($p_info['update_callback_class'], "update_{$i}_info"));
        }
      }
      else {
        if (!is_callable("{$p_un}_update_$i")) {
          break;
        }
        $update['callback'] = "{$p_un}_update_$i";
        if (is_callable("{$p_un}_update_{$i}_info")) {
          $update['info'] = call_user_func("{$p_un}_update_{$i}_info");
        }
      }

      $update['plugin_un'] = $p_un;
      $update['schema_version'] = $i;

      $updates["{$p_un}_{$i}"] = $update;
    }

  }

  /*
  $schema_ver = !empty($system_meta['schema_version']) ? $system_meta['schema_version'] : 1000;
  $updates = array();
  for ($i = $schema_ver + 1; $i < 2000; $i++) {
    if (!is_callable(array("self", "update_$i"))) {
      break;
    }
    $updates[$i] = 1;
  }
  */
  return $updates;
}

function intel_include_update_file($plugin_un, $system_info = null) {
  if (empty($system_info)) {
    $system_info = intel()->system_info();
  }
  if (empty($system_info[$plugin_un])) {
    return FALSE;
  }
  $p_info = $system_info[$plugin_un];
  // TODO: plugin_path is deprecated, plugin_dir is correct property
  $file_path = !empty($p_info['plugin_path']) ? $p_info['plugin_path'] : $p_info['plugin_dir'];
  $file_path .= !empty($p_info['update_file_path']) ? $p_info['update_file_path'] : '';
  $file = !empty($p_info['update_file']) ? $p_info['update_file'] : "$plugin_un.install.php";
  if (!file_exists($file_path . $file)) {
    return FALSE;
  }

  include_once ($file_path . $file);

  return $file_path . $file;
}

function intel_activate_updates($plugin_un = '') {
  $needed_updates = $updates = intel_get_needed_updates($plugin_un);

  //Intel_Df::watchdog('intel_activate_updates updates', print_r($needed_updates, 1));

  $system_info = $system_info = intel()->system_info();
  $system_data = get_option('intel_system', array());
  $system_meta = get_option('intel_system_meta', array());

  $versions = array();
  foreach ($updates as $k => $v) {
    if (!empty($plugin_un) && $v['plugin_un'] != $plugin_un) {
      continue;
    }
    $p_un = $v['plugin_un'];
    $p_info = $system_info[$p_un];

    if (!isset($system_data[$p_un])) {
      $system_data[$p_un] = array(
        'plugin_version' => isset($system_info[$p_un]['plugin_version']) ? $system_info[$p_un]['plugin_version'] : '',
        'schema_version' => !empty($p_info['update_start']) ? $p_info['update_start'] : 1000,
      );
    }

    if ($v['schema_version'] > $system_data[$p_un]['schema_version']) {
      $system_data[$p_un]['schema_version'] = $v['schema_version'];
    }
    unset($needed_updates[$k]);

  }
  $system_meta['needed_updates'] = $needed_updates;

  //Intel_Df::watchdog('intel_activate_updates system_data', print_r($system_data, 1));
  //Intel_Df::watchdog('intel_activate_updates system_meta', print_r($system_meta, 1));

  $system_data = update_option('intel_system', $system_data);
  $system_meta = update_option('intel_system_meta', $system_meta);
}

function intel_uninstall_updates($plugin_un = '') {
  // unset system data for plugin
  $system_data = get_option('intel_system', array());
  if (isset($system_data[$plugin_un])) {
    unset($system_data[$plugin_un]);
    $system_data = update_option('intel_system', $system_data);
  }
}