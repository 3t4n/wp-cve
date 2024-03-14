<?php

defined('ABSPATH') or die('No script kiddies please!');

require_once(__DIR__ . '/../wp_common.php');
require_once('version.php');

class CBroWPApiBackend 
{

  function delete_access_token()
  {
    return CBroWPCommon::delete_option('chatbro_access_token');
  }

  function delete_refresh_token()
  {
    return CBroWPCommon::delete_option('chatbro_refresh_token');
  }

  function set_access_token($access_token)
  {
    return CBroWPCommon::update_option('chatbro_access_token', $access_token);
  }

  function set_refresh_token($refresh_token)
  {
    return CBroWPCommon::update_option('chatbro_refresh_token', $refresh_token);
  }

  function get_access_token()
  {
    return CBroWPCommon::get_option('chatbro_access_token');
  }

  function get_refresh_token()
  {
    return CBroWPCommon::get_option('chatbro_refresh_token');
  }
}

?>