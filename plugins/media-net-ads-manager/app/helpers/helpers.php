<?php
// taken from laravel 5.6

use Mnet\Admin\MnetSite;
use Mnet\Admin\MnetUser;

include __DIR__ . '/Arr.php';
include __DIR__ . '/Log.php';
include __DIR__ . '/../../libs/Mobile_Detect.php';


if (!function_exists('value')) {
  /**
   * Return the default value of the given value.
   *
   * @param  mixed  $value
   * @return mixed
   */
  function value($value)
  {
    return $value instanceof Closure ? $value() : $value;
  }
}

if (!function_exists('mnet_user')) {

  function mnet_user()
  {
    return MnetUser::getInstance();
  }
}

if (!function_exists('mnet_site')) {

  function mnet_site()
  {
    return MnetSite::getInstance();
  }
}
