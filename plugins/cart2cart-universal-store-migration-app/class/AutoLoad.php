<?php

namespace Cart2cart;

class AutoLoad
{
  private static $_loadDir = null;

  public static function init()
  {
    spl_autoload_register(__NAMESPACE__ . '\AutoLoad::load', false);
    !self::$_loadDir and (self::$_loadDir = plugin_dir_path(__FILE__));
  }

  public static function load($className)
  {
    if (stripos($className, 'cart2cart') !== 0) {
      return false;
    }

    $path = self::$_loadDir . str_replace(array('\\', '_', __NAMESPACE__ . '/'), array('/', '/', ''), $className) . '.php';

    if (@file_exists($path)) {
      include $path;
      return true;
    }

    return false;
  }
}