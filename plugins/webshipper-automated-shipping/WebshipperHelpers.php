<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Require the correct language files
require_once('lang/da.php');
require_once('lang/en.php');

// Hacky translation method
function ___($str)
{
  if (get_locale() === 'da_DK') {
    return WebshipperLangDa::translate($str);
  }

  return WebshipperLangEn::translate($str);
}

function ws_get_option($option, $default) {
  $str = get_option($option, '');

  if (strlen($str) < 1) {
    return $default;
  }

  return $str;
}

// Load javascript language files
function webshipper_js_lang()
{
  if (get_locale() === 'da_DK') {
    return WebshipperLangDa::js();
  }
  return WebshipperLangEn::js();
}

// Oh how I miss the Rails dig method
// in PHP
function dig($arr, $key)
{
  if (isset($arr[$key])) {
    return $arr[$key];
  }

  return '';
}
