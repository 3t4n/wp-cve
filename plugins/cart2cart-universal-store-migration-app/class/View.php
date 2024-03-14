<?php

namespace Cart2cart;

class View
{
  private $_params = array();

  public function __set($name, $value)
  {
    if ('_' == substr($name, 0, 1)) {
      throw new \Exception(_('Setting private or protected class members is not allowed'));
    }

    $this->_params[$name] = $value;
  }

  public function __get($key)
  {
    return isset($this->_params[$key]) ? $this->_params[$key] : null;
  }

  public function assign($key, $value)
  {
    $this->__set($key, $value);
  }

  public function render($script)
  {
    $filePath = CART2CART_PLUGIN_ROOT_DIR . 'views/' . $script;

    if (!$path = realpath($filePath)) {
      throw new \Exception(sprintf(_('File "%s" not found!'), $filePath));
    }

    ob_start();
    @include $path;

    return ob_get_clean();
  }
}