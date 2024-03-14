<?php

defined('ABSPATH') or die('No script kiddies please!');

require_once(__DIR__ . '/../common/chats/interfaces.php');
require_once(__DIR__ . '/../common/chats/exceptions.php');
require_once(__DIR__ . '/../wp_common.php');


class CBroNonExistentChats
{
}

class CBroWPChatsBackend implements ICBroChatsBackend
{

  public $non_existent_chats;
  const chat_list = "chatbro_chat_list";

  function __construct()
  {
    $this->non_existent_chats = new CBroNonExistentChats();
  }

  function update_option($name, $value)
  {
    if (is_multisite() && is_plugin_active_for_network(plugin_basename(__FILE__))) {
      return update_site_option($name, $value);
    } else {
      return update_option($name, $value);
    }
  }

  function get()
  {
    $val = CBroWPCommon::get_option(self::chat_list, $this->non_existent_chats);

    if ($val === $this->non_existent_chats)
      throw new CBroChatsNotFound(self::chat_list);

    return $val;
  }

  function set($value)
  {
    if (!CBroWPCommon::add_option(self::chat_list, $value)) {
      self::update_option(self::chat_list, $value);
    }
  }

  function postpone_write()
  {
  }

  function flush()
  {
  }

  function del()
  {
    delete_option(self::chat_list);
  }
}

?>