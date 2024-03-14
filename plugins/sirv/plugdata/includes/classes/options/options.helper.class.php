<?php

defined('ABSPATH') or die('No script kiddies please!');


class OptionsHelper {

  protected static $options = array();


  public static function register_settings(){

    foreach (self::$options as $option_name => $option_data) {
      if (stripos($option_name, 'unreg_') !== false) continue;
      register_setting('sirv-settings-group', $option_name);
      if (!get_option($option_name)) update_option($option_name, $option_data['default']);
    }

  }


  public static function prepareOptionsData(){
    if( empty(self::$options) ) self::$options = self::parseOptions();

  }


  public static function get_options_names_list(){
    $names = array();

    foreach (self::$options as $option_name => $option_data) {
      if (stripos($option_name, 'unreg_') !== false || !$option_data['enabled_option']) continue;
      $names[] = $option_name;
    }

    return $names;
  }


  protected static function parseOptions(){
    $full_options = self::loadOptionsData();
    $flat_options_arr = array();

    foreach ($full_options as $options_by_class) {
      foreach ($options_by_class as $sub_options_by_class) {
        foreach ($sub_options_by_class['options'] as $option_name => $option_data) {
          $flat_options_arr[$option_name] = $option_data;
        }
      }
    }

    return $flat_options_arr;
  }


  protected static function loadOptionsData(){
    return array(
      'woo_options' => include(dirname(__FILE__) . '/../../../data/options/woo.options.data.php')
    );
  }
}

?>
